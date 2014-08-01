<?php
// Copyright (C) 2006-2013 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This reports on cash receipts by cashier.
// Cloned from sl_receipts_report.php.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");

function bucks($amount) {
  if ($amount) echo oeFormatMoney($amount);
}

if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));

$form_use_edate  = $_POST['form_use_edate'];
$form_cptcode    = trim($_POST['form_cptcode']);
$form_icdcode    = trim($_POST['form_icdcode']);
$form_from_date  = fixDate($_POST['form_from_date'], date('Y-m-01'));
$form_to_date    = fixDate($_POST['form_to_date'], date('Y-m-d'));
$form_facility   = $_POST['form_facility'];
?>
<html>
<head>
<?php if (function_exists('html_header_show')) html_header_show(); ?>
<title><?xl('Cash Receipts by Cashier','e')?></title>

<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
 .delink { color:#0000cc; font-family:sans-serif; font-size:10pt; font-weight:normal; cursor:pointer }
</style>

<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>

<script language="JavaScript">
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
// Process click to pop up the invoice receipt.
function doinvopen(ptid,encid) {
 dlgopen('../patient_file/pos_checkout.php?ptid=' + ptid + '&enc=' + encid, '_blank', 750, 550);
}
</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?xl('Cash Receipts by Cashier','e')?></h2>

<form method='post' action='cash_receipts_by_cashier.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td colspan='2' align='left'>
<?php
 // Build a drop-down list of facilities.
 //
 $query = "SELECT id, name FROM facility ORDER BY name";
 $fres = sqlStatement($query);
 echo "   <select name='form_facility'>\n";
 echo "    <option value=''>-- " . xl('All Facilities') . " --\n";
 while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  echo "    <option value='$facid'";
  if ($facid == $form_facility) echo " selected";
  echo ">" . $frow['name'] . "\n";
 }
 echo "   </select>\n";
?>
<?php
	// Build a drop-down list of "cashiers".
	//
	$query = "select id, lname, fname from users where " .
		"username != '' and active = 1 order by lname, fname";
	$res = sqlStatement($query);
	echo "   <select name='form_cashier'>\n";
	echo "    <option value=''>-- " . xl('All Cashiers') . " --\n";
	while ($row = sqlFetchArray($res)) {
		$cashierid = $row['id'];
		echo "    <option value='$cashierid'";
		if ($cashierid == $_POST['form_cashier']) echo " selected";
		echo ">" . $row['lname'] . ", " . $row['fname'] . "\n";
	}
	echo "   </select>\n";
?>
   &nbsp;
   <input type='checkbox' name='form_details' value='1'<? if ($_POST['form_details']) echo " checked"; ?>><?xl('Details','e')?>
   <?php if (!$GLOBALS['simplified_demographics']) echo '&nbsp;' . xl('CPT') . ':'; ?>
   <input type='text' name='form_cptcode' size='5' value='<? echo $form_cptcode; ?>'
    title='<?php xl('Optional procedure code','e'); ?>'
    <?php if ($GLOBALS['simplified_demographics']) echo "style='display:none'"; ?>>
   <?php if (!$GLOBALS['simplified_demographics']) echo '&nbsp;' . xl('ICD') . ':'; ?>
   <input type='text' name='form_icdcode' size='5' value='<? echo $form_icdcode; ?>'
    title='<?php xl('Enter a diagnosis code to exclude all invoices not containing it','e'); ?>'
    <?php if ($GLOBALS['simplified_demographics']) echo "style='display:none'"; ?>>
  </td>
 </tr>
 <tr>
  <td align='left'>
   <select name='form_use_edate'>
    <option value='0'><?php xl('Payment Date','e'); ?></option>
    <option value='1'<?php if ($form_use_edate) echo ' selected' ?>><?php xl('Invoice Date','e'); ?></option>
   </select>
   &nbsp;<?php xl('From:','e'); ?>
   <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
   &nbsp;<?php xl('To','e'); ?>:	
   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
  </td>
  <td align='right'>
   &nbsp;
   <input type='submit' name='form_refresh' value="<?xl('Refresh','e')?>">
   &nbsp;
   <input type='button' value='<?php xl('Print','e'); ?>' onclick='window.print()' />
  </td>
 </tr>

 <tr>
  <td colspan='2' height="1">
  </td>
 </tr>

</table>

<table border='0' cellpadding='1' cellspacing='2' width='98%'>

 <tr bgcolor="#dddddd">
  <td class="dehead">
   <?php xl('Cashier','e') ?>
  </td>
  <td class="dehead">
   <?php xl('Date','e') ?>
  </td>
  <td class="dehead">
   <?php xl('Invoice','e') ?>
  </td>
  <td class="dehead">
   <?php xl('Patient','e') ?>
  </td>
<?php if ($form_cptcode) { ?>
  <td class="dehead" align='right'>
   <?php xl('InvAmt','e') ?>
  </td>
<?php } ?>
<?php if ($form_cptcode) { ?>
  <td class="dehead">
   <?php xl('Insurance','e') ?>
  </td>
<?php } ?>
  <td class="dehead" align="right">
   <?php xl('Received','e') ?>
  </td>
  <td class="dehead" align="right">
   <?php xl('Voided','e') ?>
  </td>
 </tr>
<?php
  if ($_POST['form_refresh']) {
    $form_cashier = $_POST['form_cashier'];
    $arows = array();

    $ids_to_skip = array();
    $irow = 0;

    // Get copays.  These will be ignored if a CPT code was specified.
    //
    if (!$form_cptcode) {
      $query = "SELECT b.fee, b.pid, b.encounter, b.code_type, b.code, b.modifier, " .
        "fe.date, fe.id AS trans_id, b.user AS cashierid, fe.invoice_refno " .
        "FROM billing AS b " .
        "JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter " .
        "WHERE b.code_type = 'COPAY' AND b.activity = 1 AND " .
        "fe.date >= '$form_from_date 00:00:00' AND fe.date <= '$form_to_date 23:59:59'";
      // If a facility was specified.
      if ($form_facility) {
        $query .= " AND fe.facility_id = '$form_facility'";
      }
      if ($form_cashier) {
        $query .= " AND b.user = '$form_cashier'";
      }
      //
      $res = sqlStatement($query);
      while ($row = sqlFetchArray($res)) {
        $trans_id = $row['trans_id'];
        $thedate = substr($row['date'], 0, 10);
        $patient_id = $row['pid'];
        $encounter_id = $row['encounter'];
        //
        if (!empty($ids_to_skip[$trans_id])) continue;
        //
        // If a diagnosis code was given then skip any invoices without
        // that diagnosis.
        if ($form_icdcode) {
          $tmp = sqlQuery("SELECT count(*) AS count FROM billing WHERE " .
            "pid = '$patient_id' AND encounter = '$encounter_id' AND " .
            "code_type = 'ICD9' AND code LIKE '$form_icdcode' AND " .
            "activity = 1");
          if (empty($tmp['count'])) {
            $ids_to_skip[$trans_id] = 1;
            continue;
          }
        }
        //
        $key = sprintf("%08u%s%08u%08u%06u", $row['cashierid'], $thedate,
          $patient_id, $encounter_id, ++$irow);
        $arows[$key] = array();
        $arows[$key]['transdate'] = $thedate;
        $arows[$key]['amount'] = $row['fee'];
        $arows[$key]['voidamount'] = 0;
        $arows[$key]['cashierid'] = $row['cashierid'];
        $arows[$key]['project_id'] = 0;
        $arows[$key]['memo'] = '';
        $arows[$key]['invnumber'] = "$patient_id.$encounter_id";
        $arows[$key]['irnumber'] = $row['invoice_refno'];
      } // end while
    } // end copays (not $form_cptcode)

    // Get ar_activity (having payments), form_encounter, forms, users, optional ar_session
    $query = "SELECT a.pid, a.encounter, a.post_time, a.code, a.modifier, a.pay_amount, " .
      "fe.date, fe.id AS trans_id, a.post_user AS cashierid, fe.invoice_refno, " .
      "s.deposit_date, s.payer_id, b.provider_id " .
      "FROM ar_activity AS a " .
      "JOIN form_encounter AS fe ON fe.pid = a.pid AND fe.encounter = a.encounter " .
      "LEFT OUTER JOIN ar_session AS s ON s.session_id = a.session_id " .
      "LEFT OUTER JOIN billing AS b ON b.pid = a.pid AND b.encounter = a.encounter AND " .
      "b.code = a.code AND b.modifier = a.modifier AND b.activity = 1 AND " .
      "b.code_type != 'COPAY' AND b.code_type != 'TAX' " .
      "WHERE a.pay_amount != 0 AND ( " .
      "a.post_time >= '$form_from_date 00:00:00' AND a.post_time <= '$form_to_date 23:59:59' " .
      "OR fe.date >= '$form_from_date 00:00:00' AND fe.date <= '$form_to_date 23:59:59' " .
      "OR s.deposit_date >= '$form_from_date' AND s.deposit_date <= '$form_to_date' )";
    // If a procedure code was specified.
    if ($form_cptcode) $query .= " AND a.code = '$form_cptcode'";
    // If a facility was specified.
    if ($form_facility) $query .= " AND fe.facility_id = '$form_facility'";
    if ($form_cashier) $query .= " AND a.post_user = '$form_cashier'";
    //
    $res = sqlStatement($query);
    while ($row = sqlFetchArray($res)) {
      $trans_id = $row['trans_id'];
      $patient_id = $row['pid'];
      $encounter_id = $row['encounter'];
      //
      if (!empty($ids_to_skip[$trans_id])) continue;
      //
      if ($form_use_edate) {
        $thedate = substr($row['date'], 0, 10);
      } else {
        if (!empty($row['deposit_date']))
          $thedate = $row['deposit_date'];
        else
          $thedate = substr($row['post_time'], 0, 10);
      }
      if (strcmp($thedate, $form_from_date) < 0 || strcmp($thedate, $form_to_date) > 0) continue;
      //
      // If a diagnosis code was given then skip any invoices without
      // that diagnosis.
      if ($form_icdcode) {
        $tmp = sqlQuery("SELECT count(*) AS count FROM billing WHERE " .
          "pid = '$patient_id' AND encounter = '$encounter_id' AND " .
          "code_type = 'ICD9' AND code LIKE '$form_icdcode' AND " .
          "activity = 1");
        if (empty($tmp['count'])) {
          $ids_to_skip[$trans_id] = 1;
          continue;
        }
      }
      //
      $cashierid = $row['cashierid'];
      $key = sprintf("%08u%s%16s%08u%08u%06u", $cashierid, $thedate,
        $row['invoice_refno'], $patient_id, $encounter_id, ++$irow);
      // Note invoice_refno in the above key is right-aligned.
      $arows[$key] = array();
      $arows[$key]['transdate'] = $thedate;
      $arows[$key]['amount'] = 0 - $row['pay_amount'];
      $arows[$key]['voidamount'] = 0;
      $arows[$key]['cashierid'] = $cashierid;
      $arows[$key]['project_id'] = empty($row['payer_id']) ? 0 : $row['payer_id'];
      $arows[$key]['memo'] = $row['code'];
      $arows[$key]['invnumber'] = "$patient_id.$encounter_id";
      $arows[$key]['irnumber'] = $row['invoice_refno'];
    } // end while



    // Get voids, form_encounter.
    // This will be skipped of a CPT code was specified.
    if (!$form_cptcode) {
      $query = "SELECT v.patient_id, v.encounter_id, v.date_voided, v.amount2, " .
        "fe.date, fe.id AS trans_id, v.user_id AS cashierid, v.other_info, " .
        "fe.provider_id " .
        "FROM voids AS v " .
        "JOIN form_encounter AS fe ON fe.pid = v.patient_id AND fe.encounter = v.encounter_id " .
        "WHERE v.amount2 != 0 AND ( " .
        "v.date_voided >= '$form_from_date 00:00:00' AND v.date_voided <= '$form_to_date 23:59:59' " .
        "OR fe.date >= '$form_from_date 00:00:00' AND fe.date <= '$form_to_date 23:59:59' )";
      // If a facility was specified.
      if ($form_facility) $query .= " AND fe.facility_id = '$form_facility'";
      if ($form_cashier) $query .= " AND v.user_id = '$form_cashier'";
      //
      $res = sqlStatement($query);
      while ($row = sqlFetchArray($res)) {
        $trans_id = $row['trans_id'];
        $patient_id = $row['patient_id'];
        $encounter_id = $row['encounter_id'];
        //
        if (!empty($ids_to_skip[$trans_id])) continue;
        //
        if ($form_use_edate) {
          $thedate = substr($row['date'], 0, 10);
        } else {
          $thedate = substr($row['date_voided'], 0, 10);
        }
        if (strcmp($thedate, $form_from_date) < 0 || strcmp($thedate, $form_to_date) > 0) continue;
        //
        // If a diagnosis code was given then skip any invoices without
        // that diagnosis.
        if ($form_icdcode) {
          $tmp = sqlQuery("SELECT count(*) AS count FROM billing WHERE " .
            "pid = '$patient_id' AND encounter = '$encounter_id' AND " .
            "code_type = 'ICD9' AND code LIKE '$form_icdcode' AND " .
            "activity = 1");
          if (empty($tmp['count'])) {
            $ids_to_skip[$trans_id] = 1;
            continue;
          }
        }
        //
        $cashierid = $row['cashierid'];
        $key = sprintf("%08u%s%16s%08u%08u%06u", $cashierid, $thedate,
          $row['other_info'], $patient_id, $encounter_id, ++$irow);
        // Note invoice_refno in the above key is right-aligned.
        $arows[$key] = array();
        $arows[$key]['transdate'] = $thedate;
        $arows[$key]['amount'] = 0;
        $arows[$key]['voidamount'] = 0 + $row['amount2'];
        $arows[$key]['cashierid'] = $cashierid;
        $arows[$key]['project_id'] = 0;
        $arows[$key]['memo'] = '';
        $arows[$key]['invnumber'] = "$patient_id.$encounter_id";
        $arows[$key]['irnumber'] = $row['other_info'];
      } // end while
    }



    ksort($arows);
    $cashierid = 0;
    $cashiername = '';

    foreach ($arows as $row) {

      // Get insurance company name
      $insconame = '';
      if ($form_cptcode && $row['project_id']) {
        $tmp = sqlQuery("SELECT name FROM insurance_companies WHERE " .
          "id = '" . $row['project_id'] . "'");
        $insconame = $tmp['name'];
      }

      $amount1 = 0;
      $amount2 = 0;
      $amount1 -= $row['amount'];
      $amount2 -= $row['voidamount'];

      if ($cashierid != $row['cashierid']) {
        if ($cashierid) {
          // Print cashier totals.
?>

 <tr bgcolor="#ddddff">
  <td class="detail" colspan="<?php echo ($form_cptcode ? 6 : 4); ?>">
   <? echo xl('Totals for ') . $cashiername ?>
  </td>
  <td class="dehead" align="right">
   <?php bucks($cashiertotal1) ?>
  </td>
  <td class="dehead" align="right">
   <?php bucks($cashiertotal2) ?>
  </td>
 </tr>
<?php
        }
        $cashiertotal1 = 0;
        $cashiertotal2 = 0;

        $cashierid = $row['cashierid'];
        $tmp = sqlQuery("SELECT lname, fname FROM users WHERE id = '$cashierid'");
        $cashiername = empty($tmp) ? 'Unknown' : $tmp['fname'] . ' ' . $tmp['lname'];

        $cashiernameleft = htmlspecialchars($cashiername);
      }

      if ($_POST['form_details']) {
        list($patient_id, $encounter_id) = explode('.', $row['invnumber']);
        $tmp = sqlQuery("SELECT lname, fname FROM patient_data WHERE pid = '$patient_id'");
        $patientname = empty($tmp) ? 'Unknown' : $tmp['fname'] . ' ' . $tmp['lname'];
?>
 <tr>
  <td class="detail">
   <?php echo $cashiernameleft; $cashiernameleft = "&nbsp;"; ?>
  </td>
  <td class="detail">
   <?php echo oeFormatShortDate($row['transdate']); ?>
  </td>
  <td class='delink' onclick='doinvopen(<?php echo "$patient_id,$encounter_id"; ?>)'>
   <?php echo empty($row['irnumber']) ? $row['invnumber'] : $row['irnumber']; ?>
  </td>
  <td class="detail">
   <?php echo htmlspecialchars($patientname); ?>
  </td>
<?php
        if ($form_cptcode) {
          echo "  <td class='detail' align='right'>";
          list($patient_id, $encounter_id) = explode(".", $row['invnumber']);
          $tmp = sqlQuery("SELECT SUM(fee) AS sum FROM billing WHERE " .
            "pid = '$patient_id' AND encounter = '$encounter_id' AND " .
            "code = '$form_cptcode' AND activity = 1");
          bucks($tmp['sum']);
          echo "  </td>\n";
        }
?>
<?php if ($form_cptcode) { ?>
  <td class="detail">
   <?php echo $insconame ?>
  </td>
<?php } ?>
  <td class="detail" align="right">
   <?php bucks($amount1) ?>
  </td>
  <td class="detail" align="right">
   <?php bucks($amount2) ?>
  </td>
 </tr>
<?php
      } // end details
      $cashiertotal1   += $amount1;
      $cashiertotal2   += $amount2;
      $grandtotal1 += $amount1;
      $grandtotal2 += $amount2;
    }
?>

 <tr bgcolor="#ddddff">
  <td class="detail" colspan="<?php echo ($form_cptcode ? 6 : 4); ?>">
   <?echo xl('Totals for ') . $cashiername ?>
  </td>
  <td class="dehead" align="right">
   <?php bucks($cashiertotal1) ?>
  </td>
  <td class="dehead" align="right">
   <?php bucks($cashiertotal2) ?>
  </td>
 </tr>

 <tr bgcolor="#ffdddd">
  <td class="detail" colspan="<?php echo ($form_cptcode ? 6 : 4); ?>">
   <?php xl('Grand Totals','e') ?>
  </td>
  <td class="dehead" align="right">
   <?php bucks($grandtotal1) ?>
  </td>
  <td class="dehead" align="right">
   <?php bucks($grandtotal2) ?>
  </td>
 </tr>

<?php
  }
?>

</table>
</form>
</center>
</body>

<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>

</html>
