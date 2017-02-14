<?php
// Copyright (C) 2006-2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/sl_eob.inc.php");
require_once("$srcdir/invoice_summary.inc.php");
require_once("../../custom/code_types.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/options.inc.php");
?>
<html>
<head>
<?php html_header_show();?>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<?php

// Format dollars for display.
//
function bucks($amount) {
  if ($amount) {
    $amount = oeFormatMoney($amount);
    return $amount;
  }
  return '';
}

function rawbucks($amount) {
  if ($amount) {
    $amount = sprintf("%.2f", $amount);
    return $amount;
  }
  return '';
}


// Display a row of data for an encounter.
//
function echoLine($enc, $billed, $date, $charges, $ptpaid, $inspaid, $duept) {
  $balance = bucks($charges - $ptpaid - $inspaid);
  $getfrompt = ($duept > 0) ? $duept : 0;
  $iname = 'form_bpay[' . $enc . ']';
  $billed = $billed ? 1 : 0;
  echo " <tr>\n";
  echo "  <td class='detail'>" . oeFormatShortDate($date) . "</td>\n";
  echo "  <td class='detail' align='right'>" . bucks($charges) . "</td>\n";
  echo "  <td class='detail' align='right'>" . bucks($ptpaid) . "</td>\n";
  if (!$GLOBALS['simplified_demographics'])
    echo "  <td class='detail' align='right'>" . bucks($inspaid) . "</td>\n";
  echo "  <td class='detail' align='right'>$balance</td>\n";
  if (!$GLOBALS['simplified_demographics'])
    echo "  <td class='detail' align='right'>" . bucks($duept) . "</td>\n";
  echo "  <td class='detail' align='right'><input type='text' name='$iname' " .
    "size='6' value='" . rawbucks($getfrompt) . "' onchange='calctotal()' " .
    "onkeyup='calctotal()' style='text-align:right' /></td>\n";
  echo "  <td class='detail' align='right'>" .
    generate_select_list('form_meth[' . $enc . ']', 'paymethod', '', '', '') .
    "&nbsp;&nbsp;</td>\n";
  echo "  <td class='detail' align='right'>" .
    "<input type='text' name='form_src[" . $enc . "]' size='6' />" .
    "<input type='hidden' name='form_bill[" . $enc . "]' value='$billed' />" .
    "</td>\n";
  echo " </tr>\n";
}

// Post a payment to the payments table.  It is only used by the "Front Office Receipts"
// report which is only interested in payments made via this interface.
//
function frontPayment($patient_id, $encounter, $method, $source, $amount1, $amount2) {
  global $timestamp;
  $payid = sqlInsert("INSERT INTO payments ( " .
    "pid, encounter, dtime, user, method, source, amount1, amount2 " .
    ") VALUES ( " .
    "'$patient_id', " .
    "'$encounter', " .
    "'$timestamp', " .
    "'" . $_SESSION['authUser']  . "', " .
    "'$method', " .
    "'$source', " .
    "'$amount1', " .
    "'$amount2' " .
    ")");
  return $payid;
}

// Get the patient's encounter ID for today, if it exists.
// In the case of more than one encounter today, pick the last one.
//
function todaysEncounterIf($patient_id) {
  global $today;
  $tmprow = sqlQuery("SELECT encounter FROM form_encounter WHERE " .
    "pid = '$patient_id' AND date = '$today 00:00:00' " .
    "ORDER BY encounter DESC LIMIT 1");
  return empty($tmprow['encounter']) ? 0 : $tmprow['encounter'];
}

// Get the patient's encounter ID for today, creating it if there is none.
//
function todaysEncounter($patient_id) {
  global $today;

  $encounter = todaysEncounterIf($patient_id);
  if ($encounter) return $encounter;

  $tmprow = sqlQuery("SELECT username, facility, facility_id FROM users " .
    "WHERE id = '" . $_SESSION["authUserID"] . "'");
  $username = $tmprow['username'];
  $facility = $tmprow['facility'];
  $facility_id = $tmprow['facility_id'];
  $conn = $GLOBALS['adodb']['db'];
  $encounter = $conn->GenID("sequences");
  addForm($encounter, "New Patient Encounter",
    sqlInsert("INSERT INTO form_encounter SET " .
      "date = '$today', " .
      "onset_date = '$today', " .
      "reason = 'Please indicate visit reason', " .
      "facility = '$facility', " .
      "facility_id = '$facility_id', " .
      "pid = '$patient_id', " .
      "encounter = '$encounter'"
    ),
    "newpatient", $patient_id, "1", "NOW()", $username
  );
  return $encounter;
}

// We use this to put dashes, colons, etc. back into a timestamp.
//
function decorateString($fmt, $str) {
  $res = '';
  while ($fmt) {
    $fc = substr($fmt, 0, 1);
    $fmt = substr($fmt, 1);
    if ($fc == '.') {
      $res .= substr($str, 0, 1);
      $str = substr($str, 1);
    } else {
      $res .= $fc;
    }
  }
  return $res;
}

// Compute taxes from a tax rate string and a possibly taxable amount.
//
function calcTaxes($row, $amount) {
  $total = 0;
  if (empty($row['taxrates'])) return $total;
  $arates = explode(':', $row['taxrates']);
  if (empty($arates)) return $total;
  foreach ($arates as $value) {
    if (empty($value)) continue;
    $trow = sqlQuery("SELECT option_value FROM list_options WHERE " .
      "list_id = 'taxrate' AND option_id = '$value' AND activity = 1 LIMIT 1");
    if (empty($trow['option_value'])) {
      echo "<!-- Missing tax rate '$value'! -->\n";
      continue;
    }
    $tax = sprintf("%01.2f", $amount * $trow['option_value']);
    // echo "<!-- Rate = '$value', amount = '$amount', tax = '$tax' -->\n";
    $total += $tax;
  }
  return $total;
}

function postPayment($form_pid, $enc, $form_method, $form_source, $amount, $post_time, $post_date) {
  $thissrc = '';
  if ($form_method) {
    $thissrc .= $form_method;
    if ($form_source) $thissrc .= " $form_source";
  }
  $session_id = 0; // Is this OK?
  arPostPayment($form_pid, $enc, $session_id, $amount, '', 0, $thissrc, 0, $post_time, '', $post_date);
}

function getListTitle($list, $option) {
  $row = sqlQuery("SELECT title FROM list_options WHERE " .
    "list_id = '$list' AND option_id = '$option' AND activity = 1");
  if (empty($row['title'])) return $option;
  return xl_list_label($row['title']);
}

$now = time();
$today = date('Y-m-d', $now);
$timestamp = date('Y-m-d H:i:s', $now);

$patdata = sqlQuery("SELECT " .
  "p.fname, p.mname, p.lname, p.pubpid, i.copay " .
  "FROM patient_data AS p " .
  "LEFT OUTER JOIN insurance_data AS i ON " .
  "i.pid = p.pid AND i.type = 'primary' " .
  "WHERE p.pid = '$pid' ORDER BY i.date DESC LIMIT 1");

$alertmsg = ''; // anything here pops up in an alert box

// If a Save button was clicked...
if ($_POST['form_save_pr'] || $_POST['form_save_op'] || $_POST['form_save_co'] || $_POST['form_save_cl']) {
  $form_pid = $_POST['form_pid'];

  // Get the posting date from the form as yyyy-mm-dd.
  $posting_date = substr($timestamp, 0, 10);
  if (preg_match("/(\d\d\d\d)\D*(\d\d)\D*(\d\d)/", $_POST['form_posting_date'], $matches)) {
    $posting_date = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
  }

  // Post payments.
  if ($_POST['form_bpay']) {
    foreach ($_POST['form_bpay'] as $enc => $payment) {
      $form_method = trim($_POST['form_meth'][$enc]);
      $form_source = trim($_POST['form_src'][$enc]);
      $billed = !empty($_POST['form_bill'][$enc]);
      if ($amount = 0 + $payment) {
        if (!$enc) $enc = todaysEncounter($form_pid);
        postPayment($form_pid, $enc, $form_method, $form_source, $amount,
          $timestamp, $posting_date);
        frontPayment($form_pid, $enc, $form_method, $form_source,
          $billed ? 0 : $amount, $billed ? $amount : 0);
      }
    }
  }
}

// If a receipt is to be printed...
//
if ($_POST['form_save_pr'] || $_REQUEST['receipt']) {

  if ($_REQUEST['receipt']) {
    $form_pid = $_GET['patient'];
    $timestamp = decorateString('....-..-.. ..:..:..', $_GET['time']);
  }

  // Get the patient's name and chart number.
  $patdata = getPatientData($form_pid, 'fname,mname,lname,pubpid');

  // Re-fetch payment info.
  $payrow = sqlQuery("SELECT " .
    "MAX(user) AS user, " .
    "MAX(encounter) as encounter ".
    "FROM payments WHERE " .
    "pid = '$form_pid' AND dtime = '$timestamp'");

  $pres = sqlStatement("SELECT " .
    "p.amount1, p.amount2, p.method, p.source, p.user, p.encounter, ".
    "fe.date " .
    "FROM payments AS p, form_encounter AS fe WHERE " .
    "p.pid = '$form_pid' AND p.dtime = '$timestamp' AND " .
    "fe.pid = p.pid AND fe.encounter = p.encounter " .
    "ORDER BY fe.date, p.encounter");

  // Create key for deleting, just in case.
  $payment_key = $form_pid . '.' . preg_replace('/[^0-9]/', '', $timestamp);

  // Get details for the user's default facility.
  $frow = sqlQuery("SELECT f.* FROM facility AS f, users AS u " .
    "WHERE u.id = '" . $_SESSION["authUserID"] . "' AND f.id = u.facility_id");

  ////////////////////////////////////////////////////////////////////
  // Begin receipt printing.                                        //
  ////////////////////////////////////////////////////////////////////
?>

<title><?php xl('Receipt for Payment','e'); ?></title>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 // Process click on Print button.
 function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  window.print();
  // divstyle.display = 'block';
 }
 // Process click on Delete button.
 function deleteme() {
  dlgopen('deleter.php?payment=<?php echo $payment_key ?>', '_blank', 500, 450);
  return false;
 }
 // Called by the deleteme.php window on a successful delete.
 function imdeleted() {
  window.close();
 }

 // Called to switch to the specified encounter having the specified DOS.
 // This also closes the popup window.
 function toencounter(enc, datestr, topframe) {
  topframe.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
  // Hard-coding of RBot for this purpose is awkward, but since this is a
  // pop-up and our opener is left_nav, we have no good clue as to whether
  // the top frame is more appropriate.
  topframe.left_nav.forceDual();
  topframe.left_nav.setEncounter(datestr, enc, '');
  topframe.left_nav.setRadio('RBot', 'enc');
  topframe.left_nav.loadFrame('enc2', 'RBot', 'patient_file/encounter/encounter_top.php?set_encounter=' + enc);
<?php } else { ?>
  topframe.Title.location.href = 'encounter/encounter_title.php?set_encounter='   + enc;
  topframe.Main.location.href  = 'encounter/patient_encounter.php?set_encounter=' + enc;
<?php } ?>
  window.close();
 }

</script>
</head>
<body bgcolor='#ffffff'>
<center>

<p><h2><?php xl('Receipt for Payment','e'); ?></h2>

<!--
<p><?php echo htmlspecialchars($frow['name']) ?>
<br><?php echo htmlspecialchars($frow['street']) ?>
<br><?php echo htmlspecialchars($frow['city'] . ', ' . $frow['state']) . ' ' .
    $frow['postal_code'] ?>
<br><?php echo htmlspecialchars($frow['phone']) ?>
-->

<table width='95%'>
 <tr>
  <td width='25%' align='left' valign='top'>
<?php
  // TBD: Maybe make a global for this file name.
  $ma_logo_path = "sites/" . $_SESSION['site_id'] . "/images/ma_logo.png";
  if (is_file("$webserver_root/$ma_logo_path")) {
    echo "<img src='$web_root/$ma_logo_path' />";
  }
  else {
    echo "&nbsp;";
  }
?>
  </td>
  <td width='50%' align='center' valign='top'>
   <b><?php echo text($frow['name']); ?>
   <br><?php echo text($frow['street']); ?>
   <br><?php
  echo text($frow['city']) . ", ";
  echo text($frow['state']) . " ";
  echo text($frow['postal_code']); ?>
   <br><?php echo text($frow['phone']); ?>
  </td>
  <td width='25%' align='right' valign='top'>
   <!-- This space available. -->
   &nbsp;
  </td>
 </tr>
</table>

<p>
<table border='0' cellspacing='8'>
 <tr>
  <td><?php xl('Date','e'); ?>:</td>
  <td><?php echo oeFormatSDFT(strtotime($timestamp)) ?></td>
 </tr>
 <tr>
  <td><?php xl('Patient','e'); ?>:</td>
  <td><?php echo $patdata['fname'] . " " . $patdata['mname'] . " " .
       $patdata['lname'] . " (" . $patdata['pubpid'] . ")" ?></td>
 </tr>
 <tr>
  <td><?php xl('Received By','e'); ?>:</td>
  <td><?php echo $payrow['user'] ?></td>
 </tr>
</table>

<!-- Table of payment lines starts here. -->

<table cellpadding='2'>
 <tr>
  <td colspan='5'> <!-- style='border-top:1px solid black; padding-top:5pt;' -->
    <b><?php echo xl('Payments'); ?></b>
  </td>
 </tr>

 <tr>
  <td><b><?php xl('Date of Service','e'); ?></b>&nbsp;</td>
  <td><b><?php xl('Payment Method','e'); ?></b>&nbsp;</td>
  <td><b><?php xl('Ref No','e'); ?></b>&nbsp;</td>
  <td align='right'><b><?php xl('Amount for This Visit','e'); ?></b></td>
  <td align='right'>&nbsp;<b><?php xl('Amount for Past Balance','e'); ?></b></td>
 </tr>

<?php
  $payments1 = 0;
  $payments2 = 0;
  while ($prow = sqlFetchArray($pres)) {
    $payments1 += sprintf('%01.2f', $prow['amount1']);
    $payments2 += sprintf('%01.2f', $prow['amount2']);
    echo " <tr>\n";
    echo "  <td>" . oeFormatShortDate(substr($prow['date'], 0, 10)) . "</td>\n";
    echo "  <td>" . htmlspecialchars(getListTitle('paymethod', $prow['method'])) . "</td>\n";
    echo "  <td>" . $prow['source'] . "</td>\n";
    echo "  <td align='right'>" . oeFormatMoney($prow['amount1']) . "</td>\n";
    echo "  <td align='right'>" . oeFormatMoney($prow['amount2']) . "</td>\n";
    echo " </tr>\n";
  }
?>
 <tr>
  <td colspan='2'>&nbsp;</td>
  <td><b><?php xl('Total Payments','e'); ?></b></td>
  <td align='right'><?php echo oeFormatMoney($payments1, true) ?></td>
  <td align='right'><?php echo oeFormatMoney($payments2, true) ?></td>
 </tr>
</table>

<!-- End of payment lines. -->

<div id='hideonprint'>
<p>
<input type='button' value='<?php xl('Print','e'); ?>' onclick='printme()' />

<?php
  $todaysenc = todaysEncounterIf($pid);
  if ($todaysenc && $todaysenc != $encounter) {
    echo "&nbsp;<input type='button' " .
      "value='" . htmlspecialchars(xl('Open Today`s Visit')) . "' " .
      "onclick='toencounter($todaysenc,\"$today\",opener.top)' />\n";
  }
?>

&nbsp;
<input type='button' value='<?php echo xl('Cancel'); ?>' onclick='window.close()' />

<?php if (acl_check('admin', 'super')) { ?>
&nbsp;
<input type='button' value='<?php xl('Delete','e'); ?>' style='color:red' onclick='deleteme()' />
<?php } ?>

</div>
</center>
</body>

<?php
  ////////////////////////////////////////////////////////////////////
  // End of receipt printing logic.                                 //
  ////////////////////////////////////////////////////////////////////
}

// Otherwise if a new visit is to be opened...
//
else if ($_POST['form_save_op']) {
  $todaysenc = todaysEncounterIf($_POST['form_pid']);
  echo "</head>\n<body>\n<script language='JavaScript'>\n";
  if ($todaysenc) {
    // Today's visit already exists so open that.
    // Might be good to move this logic to a function in the top frame.
    echo "opener.top.restoreSession();\n";
    if ($GLOBALS['concurrent_layout']) {
      echo "opener.parent.left_nav.forceDual();\n";
      echo "opener.parent.left_nav.setEncounter('$today', $todaysenc, '');\n";
      echo "opener.parent.left_nav.setRadio('RBot', 'enc');\n";
      echo "opener.parent.left_nav.loadFrame('enc2', 'RBot', 'patient_file/encounter/encounter_top.php?set_encounter=$todaysenc');\n";
    }
    else {
      echo "opener.top.Title.location.href = 'encounter/encounter_title.php?set_encounter=$todaysenc';\n";
      echo "opener.top.Main.location.href  = 'encounter/patient_encounter.php?set_encounter=$todaysenc';\n";
    }
  }
  else {
    // Today's visit does not exist so open the form used to create it.
    echo "opener.parent.left_nav.loadFrame2('nen1','RBot','forms/newpatient/new.php?autoloaded=1&calenc=');\n";
  }
  echo "window.close();\n";
  echo "</script>\n</body>\n";
}

// Otherwise if they want to go to checkout...
//
else if ($_POST['form_save_co']) {
  echo "</head>\n<body>\n<script language='JavaScript'>\n";
  echo "opener.parent.left_nav.loadFrame2('bil1','RBot','patient_file/pos_checkout.php?framed=1');\n";
  echo "window.close();\n";
  echo "</script>\n</body>\n";
}

// Otherwise if they want to just close the window...
//
else if ($_POST['form_save_cl']) {
  echo "</head>\n<body>\n<script language='JavaScript'>\n";
  echo "window.close();\n";
  echo "</script>\n</body>\n";
}

// Otherwise we are to present the form for data entry.
//
else {
?>
<title><?php xl('Record Payment','e'); ?></title>

<style type="text/css">
 body    { font-family:sans-serif; font-size:10pt; font-weight:normal }
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
</style>

<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>

<script language="JavaScript">
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

function calctotal() {
 var f = document.forms[0];
 var total = 0;
 for (var i = 0; i < f.elements.length; ++i) {
  var elem = f.elements[i];
  var ename = elem.name;
  if (ename.indexOf('form_bpay[') == 0) {
   if (elem.value.length > 0) total += Number(elem.value);
  }
 }
 f.form_paytotal.value = Number(total).toFixed(2);
 return true;
}

</script>

</head>

<body class="body_top" onunload='imclosing()'>

<form method='post' action='front_payment.php<?php if ($payid) echo "?payid=$payid"; ?>'
 onsubmit='return top.restoreSession()'>
<input type='hidden' name='form_pid' value='<?php echo $pid ?>' />

<center>

<table border='0' cellspacing='8'>

 <tr>
  <td colspan='2' align='center'>
   &nbsp;<br>
   <b><?php xl('Accept Payment for ','e','',' '); ?><?php echo $patdata['fname'] . " " .
    $patdata['lname'] . " (" . $patdata['pubpid'] . ")" ?></b>
    <br>&nbsp;
  </td>
 </tr>

 <tr>
  <td>
   <?php xl('Posting Date','e'); ?>:
  </td>
  <td>
   <input type='text' size='10' name='form_posting_date' id='form_posting_date'
    value='<?php echo date('Y-m-d'); ?>'
    title='<?php echo xl('yyyy-mm-dd date of payment if not today'); ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_posting_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php echo xl('Click here if you wish to choose a past date'); ?>'>
  </td>
 </tr>
</table>

<table border='0' cellpadding='2' cellspacing='0' width='98%'>
 <tr bgcolor="#cccccc">
  <td class="dehead">
   <?php xl('Visit Date','e')?>
  </td>
  <td class="dehead" align="right">
   <?php xl('Charges','e')?>
  </td>
  <td class="dehead" align="right">
   <?php xl('Client Paid','e')?>
  </td>
<?php if (!$GLOBALS['simplified_demographics']) { ?>
  <td class="dehead" align="right">
   <?php xl('Insurance','e')?>
  </td>
<?php } ?>
  <td class="dehead" align="right">
   <?php xl('Balance','e')?>
  </td>
<?php if (!$GLOBALS['simplified_demographics']) { ?>
  <td class="dehead" align="right">
   <?php xl('Due Client','e')?>
  </td>
<?php } ?>
  <td class="dehead" align="right">
   <?php xl('Payment','e')?>&nbsp;
  </td>
  <td class="dehead" align="right">
   <?php xl('Payment Method','e')?>
  </td>
  <td class="dehead" align="right">
   <?php xl('Ref No','e')?>&nbsp;&nbsp;&nbsp;
  </td>
 </tr>

<?php
  $omitenc = empty($_GET['omitenc']) ? -1 : intval($_GET['omitenc']);

  $query = "SELECT f.id, f.pid, f.encounter, f.date, " .
    "f.last_level_billed, f.last_level_closed, f.stmt_count, " .
    "p.fname, p.mname, p.lname, p.pubpid, p.genericname2, p.genericval2, " .
    "( SELECT SUM(s.fee) FROM drug_sales AS s WHERE " .
    "s.pid = f.pid AND s.encounter = f.encounter ) AS sales, " .
    "( SELECT SUM(b.fee) FROM billing AS b WHERE " .
    "b.pid = f.pid AND b.encounter = f.encounter AND " .
    "b.activity = 1 AND b.code_type != 'COPAY' ) AS charges, " .
    "( SELECT SUM(b.fee) FROM billing AS b WHERE " .
    "b.pid = f.pid AND b.encounter = f.encounter AND " .
    "b.activity = 1 AND b.code_type = 'COPAY' ) AS copays, " .
    "( SELECT SUM(b.billed) FROM billing AS b WHERE " .
    "b.pid = f.pid AND b.encounter = f.encounter AND " .
    "b.activity = 1 ) AS billed, " .
    "( SELECT SUM(a.pay_amount) FROM ar_activity AS a WHERE " .
    "a.pid = f.pid AND a.encounter = f.encounter AND a.deleted IS NULL AND " .
    "a.payer_type = 0 ) AS ptpaid, " .
    "( SELECT SUM(a.pay_amount) FROM ar_activity AS a WHERE " .
    "a.pid = f.pid AND a.encounter = f.encounter AND a.deleted IS NULL AND " .
    "a.payer_type != 0 ) AS inspaid, " .
    "( SELECT SUM(a.adj_amount) FROM ar_activity AS a WHERE " .
    "a.pid = f.pid AND a.encounter = f.encounter AND a.deleted IS NULL ) AS adjustments " .
    "FROM form_encounter AS f " .
    "JOIN patient_data AS p ON p.pid = f.pid " .
    "WHERE f.pid = '$pid' AND f.encounter != '$omitenc' " .
    "ORDER BY f.date, f.encounter";

  // Note that unlike the SQL-Ledger case, this query does not weed
  // out encounters that are paid up.  Also the use of sub-selects
  // will require MySQL 4.1 or greater.

  $ires = sqlStatement($query);
  $num_invoices = sqlNumRows($ires);
  $gottoday = false;

  while ($irow = sqlFetchArray($ires)) {
    $balance = $irow['charges'] + $irow['sales'] + $irow['copays']
      - $irow['ptpaid'] - $irow['inspaid'] - $irow['adjustments'];
    if (!$balance) continue;

    $patient_id = $irow['pid'];
    $enc = $irow['encounter'];
    $svcdate = substr($irow['date'], 0, 10);
    $duncount = $irow['stmt_count'];
    if (! $duncount) {
      for ($i = 1; $i <= 3 && arGetPayerID($irow['pid'], $irow['date'], $i); ++$i) ;
      $duncount = $irow['last_level_closed'] + 1 - $i;
    }

    $inspaid = $irow['inspaid'] + $irow['adjustments'];
    $ptpaid  = $irow['ptpaid'] - $irow['copays'];
    // $duept   = ($duncount < 0) ? 0 : $balance;
    $duept = ($duncount < 0) ? (getCopay($pid, $svcdate) - $ptpaid) : $balance;

    if (strcmp($svcdate, $today) == 0 && !$gottoday) {
      $svcdate = xl('Today');
      $gottoday = true;
    }

    echoLine($enc, $irow['billed'], $svcdate, $irow['charges'] + $irow['sales'],
      $ptpaid, $inspaid, $duept);
  }

  // If no billing was entered yet for today and we are not omitting anything,
  // then generate a line for entering today's co-pay.
  //
  if (!$gottoday && $omitenc <= 0) {
    echoLine(0, 0, xl('Today'), 0, 0, 0, getCopay($pid, $today));
  }

  // Continue with display of the data entry form.
?>

 <tr bgcolor="#cccccc">
  <td class="dehead" colspan="<?php echo $GLOBALS['simplified_demographics'] ? 4 : 6; ?>" align='right'>
   <?php xl('Total Amount Paid','e')?>
  </td>
  <td class="dehead" align="right">
   <input type='text' name='form_paytotal' size='6' value=''
    style='color:#00aa00; background-color:transparent; text-align:right;' readonly />
  </td>
  <td class="dehead" colspan="2">
   &nbsp;
  </td>
 </tr>

</table>

<p>
<input type='submit' name='form_save_pr' value='<?php xl('Save and Print Receipt','e'); ?>' /> &nbsp;
<input type='submit' name='form_save_op' value='<?php xl('Save and Open a Visit','e');  ?>' /> &nbsp;
<?php if (!empty($_GET['omitenc'])) { // indicates we got here from the checkout form ?>
<input type='submit' name='form_save_co' value='<?php xl('Save and Check Out','e');     ?>' /> &nbsp;
<?php } ?>
<input type='submit' name='form_save_cl' value='<?php xl('Save and Close','e');         ?>' /> &nbsp;
<input type='button' value='<?php xl('Cancel','e'); ?>' onclick='window.close()' />

</center>
</form>
<script language="JavaScript">
 Calendar.setup({inputField:"form_posting_date", ifFormat:"%Y-%m-%d", button:"img_posting_date"});
 calctotal();
</script>
</body>

<?php
} // end of data entry form
?>
</html>
