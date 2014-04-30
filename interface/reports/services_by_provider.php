<?php
// Copyright (C) 2013 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is a report of services by provider.

// Note this script does not currently report payments, but the code includes
// some baggage for them anyway because it's easy to do so, and payments may
// be wanted in the future.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");

function display_desc($desc) {
  if (preg_match('/^\S*?:(.+)$/', $desc, $matches)) {
    $desc = $matches[1];
  }
  return $desc;
}

// For a given encounter, this gets all charges and allocates payments and
// adjustments among them, if that has not already been done.
// Any invoice-level adjustments and payments are allocated among the line
// items in proportion to their line-level remaining balances.
//
function ensureLineAmounts($patient_id, $encounter_id) {
  global $aItems, $overpayments;

  $invno = "$patient_id.$encounter_id";
  if (isset($aItems[$invno])) return $invno;

  $adjusts = 0;  // sum of invoice level adjustments
  $payments = 0; // sum of invoice level payments
  $denom = 0;    // sum of adjusted line item charges
  $aItems[$invno] = array();

  // Get charges and copays from billing table.
  $tres = sqlStatement("SELECT b.code_type, b.code, b.fee " .
    "FROM billing AS b WHERE " .
    "b.pid = '$patient_id' AND b.encounter = '$encounter_id' AND " .
    "b.activity = 1 AND b.fee != 0");
  while ($trow = sqlFetchArray($tres)) {
    if ($trow['code_type'] == 'COPAY') {
      $payments -= $trow['fee'];
    }
    else {
      $codekey = $trow['code_type'] . ':' . $trow['code'];
      if (!isset($aItems[$invno][$codekey])) {
        // Charges, Adjustments, Payments
        $aItems[$invno][$codekey] = array(0, 0, 0);
      }
      $aItems[$invno][$codekey][0] += $trow['fee'];
      $denom += $trow['fee'];
    }
  }

  // Get charges from drug_sales table.
  $tres = sqlStatement("SELECT s.drug_id, s.fee " .
    "FROM drug_sales AS s WHERE " .
    "s.pid = '$patient_id' AND s.encounter = '$encounter_id' AND s.fee != 0");
  while ($trow = sqlFetchArray($tres)) {
    $codekey = 'PROD:' . $trow['drug_id'];
    if (!isset($aItems[$invno][$codekey])) {
      $aItems[$invno][$codekey] = array(0, 0, 0);
    }
    $aItems[$invno][$codekey][0] += $trow['fee'];
    $denom += $trow['fee'];
  }

  // Get adjustments and other payments from ar_activity table.
  $tres = sqlStatement("SELECT " .
    "a.code_type, a.code, a.adj_amount, a.pay_amount " .
    "FROM ar_activity AS a WHERE " .
    "a.pid = '$patient_id' AND a.encounter = '$encounter_id'");
  while ($trow = sqlFetchArray($tres)) {
    $codekey = $trow['code_type'] . ':' . $trow['code'];
    if (isset($aItems[$invno][$codekey])) {
      $aItems[$invno][$codekey][1] += $trow['adj_amount'];
      $aItems[$invno][$codekey][2] += $trow['pay_amount'];
      $denom -= $trow['adj_amount'];
      $denom -= $trow['pay_amount'];
    }
    else {
      $adjusts  += $trow['adj_amount'];
      $payments += $trow['pay_amount'];
    }
  }

  // Allocate all unmatched payments and adjustments among the line items.
  $adjrem = $adjusts;  // remaining unallocated adjustments
  $payrem = $payments; // remaining unallocated payments
  $nlines = count($aItems[$invno]);
  foreach ($aItems[$invno] AS $codekey => $dummy) {
    if (--$nlines > 0) {
      // Avoid dividing by zero!
      if ($denom) {
        $factor = ($aItems[$invno][$codekey][0] - $aItems[$invno][$codekey][1] - $aItems[$invno][$codekey][2]) / $denom;
        $tmp = sprintf('%01.2f', $adjusts * $factor);
        $aItems[$invno][$codekey][1] += $tmp;
        $adjrem -= $tmp;
        $tmp = sprintf('%01.2f', $payments * $factor);
        $aItems[$invno][$codekey][2] += $tmp;
        $payrem -= $tmp;
      }
    }
    else {
      // Last line gets what's left to avoid rounding errors.
      $aItems[$invno][$codekey][1] += $adjrem;
      $aItems[$invno][$codekey][2] += $payrem;
    }
  }

  // Next part doesn't apply because we are not doing payments, but may if we do.
  /*******************************************************************
  // For each line item having (payment > charge - adjustment), move the
  // overpayment amount to a global variable $overpayments.
  foreach ($aItems[$invno] AS $codekey => $dummy) {
    $diff = $aItems[$invno][$codekey][2] + $aItems[$invno][$codekey][1] - $aItems[$invno][$codekey][0];
    $diff = sprintf('%01.2f', $diff);
    if ($diff > 0.00) {
      $overpayments += $diff;
      $aItems[$invno][$codekey][2] -= $diff;
    }
  }
  *******************************************************************/

  return $invno;
}

function thisLineItem($rowpatientid, $rowencounterid, $rowcodetype, $rowcode,
  $rowcat, $rowdesc, $rowqty, $rowproviderid, $rowprovidername) {

  global $aItems;
  global $productname, $productcode;
  global $productqty, $productchg, $productadj, $productpay;
  global $category, $catleft;
  global $catqty, $catchg, $catadj, $catpay;
  global $providerid, $providername, $provleft;
  global $provqty, $provchg, $provadj, $provpay;
  global $grandqty, $grandchg, $grandadj, $grandpay;

  $codekey = $rowcodetype . ':' . $rowcode;

  if (empty($rowcat)) $rowcat = xl('None');

  if ($rowpatientid) {
    if (!$rowqty) $rowqty = 1;
    $invno = ensureLineAmounts($rowpatientid, $rowencounterid);
    $rowchg = $aItems[$invno][$codekey][0];
    $rowadj = $aItems[$invno][$codekey][1];
    $rowpay = $aItems[$invno][$codekey][2];
  }

  $rowproduct = $rowdesc;
  if (! $rowproduct) $rowproduct = 'Unknown';

  if ($productname != $rowproduct || $providerid != $rowproviderid) {
    if ($productname) {
      // Print product line.
      if ($_POST['form_csvexport']) {
        if (! $_POST['form_details']) {
          echo '"' . $providername . '",';
          echo '"' . $category . '",';
          echo '"' . $productcode . '",';
          echo '"' . $productname . '",';
          echo '"' . $productqty . '",';
          echo '"' . oeFormatMoney($productchg / $productqty) . '",';
          echo '"' . oeFormatMoney($productadj) . '",';
          echo '"' . oeFormatMoney($productchg - $productadj) . '"';
          echo "\n";
        }
      }
      else {
?>
 <tr bgcolor="#ddddff">
  <td class="detail">
   <?php echo $provleft; $provleft = "&nbsp;"; ?>
  </td>
  <td class="detail">
   <?php echo $catleft; $catleft = "&nbsp;"; ?>
  </td>
  <td class="detail">
   <?php echo htmlspecialchars($productcode); ?>
  </td>
  <td class="detail">
   <?php echo htmlspecialchars($productname); ?>
  </td>
  <td class="detail" align="right">
   <?php echo $productqty; ?>
  </td>
  <td class="detail" align="right">
   <?php echo oeFormatMoney($productchg / $productqty); ?>
  </td>
  <td class="detail" align="right">
   <?php echo oeFormatMoney($productadj); ?>
  </td>
  <td class="detail" align="right">
   <?php echo oeFormatMoney($productchg - $productadj); ?>
  </td>
 </tr>
<?php
      } // End not csv export
    }
    $productqty = 0;
    $productchg = 0;
    $productadj = 0;
    $productpay = 0;
    $productname = $rowproduct;
    $productcode = $rowcode;
    if ($category != $rowcat || $providerid != $rowproviderid) {
      $catleft = $category = $rowcat;
    }
  }

  if ($providerid != $rowproviderid) {
    if ($providerid) {
      // Print provider total.
      if (!$_POST['form_csvexport']) {
?>
 <tr bgcolor="#ffdddd">
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail" colspan="3">
   <?php echo xl('Total for') . ' '; echo $providername; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $provqty; ?>
  </td>
  <td class="detail">
   &nbsp; <!-- No prices at the provider level. -->
  </td>
  <td class="dehead" align="right">
   <?php echo oeFormatMoney($provadj); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo oeFormatMoney($provchg - $provadj); ?>
  </td>
 </tr>
<?php
      } // End not csv export
    }
    $providerid = $rowproviderid;
    $providername = $rowprovidername;
    $provleft = htmlspecialchars($providername);
    $provqty = 0;
    $provchg = 0;
    $provadj = 0;
    $provpay = 0;
  }

  $productqty  += $rowqty;
  $productchg  += $rowchg;
  $productadj  += $rowadj;
  $productpay  += $rowpay;

  $catqty      += $rowqty;
  $catchg      += $rowchg;
  $catadj      += $rowadj;
  $catpay      += $rowpay;

  $provqty     += $rowqty;
  $provchg     += $rowchg;
  $provadj     += $rowadj;
  $provpay     += $rowpay;

  $grandqty    += $rowqty;
  $grandchg    += $rowchg;
  $grandadj    += $rowadj;
  $grandpay    += $rowpay;

} // end function

if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_facility  = $_POST['form_facility'];
$form_provider  = $_POST['form_provider'];
$form_related_code = $_POST['form_related_code'];

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=services_by_provider.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";
  // CSV headers:
  echo '"' . xl('Provider'   ) . '",';
  echo '"' . xl('Category'   ) . '",';
  echo '"' . xl('Code'       ) . '",';
  echo '"' . xl('Description') . '",';
  echo '"' . xl('Units'      ) . '",';
  echo '"' . xl('Price'      ) . '",';
  echo '"' . xl('Adjustment' ) . '",';
  echo '"' . xl('Payment'    ) . '"';
  echo "\n";
} // end export
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Services by Provider','e') ?></title>

<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
 .delink { color:#0000cc; font-family:sans-serif; font-size:10pt; font-weight:normal; cursor:pointer }
</style>

<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

// Process click to pop up the add/edit window.
function doinvopen(ptid,encid) {
 dlgopen('../patient_file/pos_checkout.php?ptid=' + ptid + '&enc=' + encid, '_blank', 750, 550);
}

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
 var s = f.form_related_code.value;
 var t = '';
 if (code) {
  if (s.length > 0) {
   s += ';';
   t = f.form_related_code.title + '; ';
  }
  s += codetype + ':' + code;
  t += codedesc;
 } else {
  s = '';
 }
 f.form_related_code.value = s;
 f.form_related_code.title = t;
}

// This invokes the find-code popup.
function sel_related() {
 dlgopen('../patient_file/encounter/find_code_popup.php', '_blank', 500, 400);
}

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php xl('Services by Provider','e')?></h2>

<form method='post' action='services_by_provider.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td align='center'>
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
   &nbsp;<?php xl('From','e'); ?>:
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
 </tr>
 <tr>
  <td align='center'>
<?php
// Build a drop-down list of providers.
//
echo xl('Provider') . ':';
$query = "SELECT id, lname, fname FROM users WHERE " .
  "active = 1 AND authorized = 1 ORDER BY lname, fname";
$ures = sqlStatement($query);
echo "   <select name='form_provider'>\n";
echo "    <option value=''>-- " . xl('All') . " --\n";
while ($urow = sqlFetchArray($ures)) {
  $provid = $urow['id'];
  echo "    <option value='$provid'";
  if ($provid == $_POST['form_provider']) echo " selected";
  echo ">" . $urow['lname'] . ", " . $urow['fname'] . "\n";
}
echo "   </select>\n";
?>
   &nbsp;
   <?php echo xl('Service Filter'); ?>:
   <input type='text' size='30' name='form_related_code'
    value='<?php echo $form_related_code ?>' onclick="sel_related()"
    title='<?php xl('Click to select a code for filtering','e'); ?>' readonly />
  </td>
 </tr>
 <tr>
  <td align='center'>
   <input type='submit' name='form_refresh' value="<?php xl('Refresh','e') ?>">
   &nbsp;
   <input type='submit' name='form_csvexport' value="<?php xl('Export to CSV','e') ?>">
   &nbsp;
   <input type='button' value='<?php xl('Print','e'); ?>' onclick='window.print()' />
  </td>
 </tr>

 <tr>
  <td height="1">
  </td>
 </tr>

</table>

<table border='0' cellpadding='1' cellspacing='2' width='98%'>

 <tr bgcolor="#dddddd">
  <td class="dehead">
   <?php xl('Provider','e'); ?>
  </td>
  <td class="dehead">
   <?php xl('Category','e'); ?>
  </td>
  <td class="dehead">
   <?php xl('Code','e'); ?>
  </td>
  <td class="dehead">
   <?php xl('Description','e'); ?>
  </td>
  <td class="dehead" align="right">
   <?php xl('Units','e'); ?>
  </td>
  <td class="dehead" align="right">
   <?php xl('Price','e'); ?>
  </td>
  <td class="dehead" align="right">
   <?php xl('Adjustment','e'); ?>
  </td>
  <td class="dehead" align="right">
   <?php xl('Charge','e'); ?>
  </td>
 </tr>

<?php
} // end not export

if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
  $from_date = $form_from_date;
  $to_date   = $form_to_date;

  $productname = '';
  $productcode = '';
  $productqty = 0;
  $productchg = 0;
  $productadj = 0;
  $productpay = 0;

  $category = '';
  $catleft = '';
  $catqty = 0;
  $catchg = 0;
  $catadj = 0;
  $catpay = 0;

  $provider = '';
  $provleft = '';
  $provqty = 0;
  $provchg = 0;
  $provadj = 0;
  $provpay = 0;

  $grandqty = 0;
  $grandchg = 0;
  $grandadj = 0;
  $grandpay = 0;

  $query = "SELECT b.pid, b.encounter, b.code_type, b.code, b.units, " .
    "b.code_text, fe.date, fe.facility_id, lo.title, " .
    "u.id, CONCAT(u.lname, ', ', u.fname, ' ', u.mname) AS providername " .
    "FROM billing AS b " .
    "JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter " .
    "LEFT JOIN users AS u ON (b.provider_id = 0 AND fe.provider_id = u.id) OR (b.provider_id != 0 AND b.provider_id = u.id) " .
    "LEFT JOIN code_types AS ct ON ct.ct_key = b.code_type " .
    "LEFT JOIN codes AS c ON c.code_type = ct.ct_id AND c.code = b.code AND c.modifier = b.modifier " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'superbill' AND lo.option_id = c.superbill " .
    "WHERE b.code_type != 'COPAY' AND b.activity = 1 AND " .
    "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59'";

  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = '$form_facility'";
  }

  // If a provider was specified.
  if ($form_provider) {
    $query .= " AND u.id = '$form_provider'";
  }

  // If one or more service codes were specified.
  if ($form_related_code) {
    $qsvc = "";
    $arel = explode(';', $form_related_code);
    foreach ($arel as $tmp) {
      list($reltype, $relcode) = explode(':', $tmp);
      if (empty($relcode) || empty($reltype)) continue;
      if ($qsvc) $qsvc .= " OR ";
      $qsvc .= "( b.code_type = '$reltype' AND b.code = '$relcode' )";
    }
    if ($qsvc) $query .= "AND ( $qsvc )";
  }

  $query .= " ORDER BY u.lname, u.fname, u.mname, u.id, lo.seq, lo.title, b.code, fe.date, fe.id";

  $res = sqlStatement($query);

  while ($row = sqlFetchArray($res)) {
    thisLineItem($row['pid'], $row['encounter'], $row['code_type'], $row['code'],
      xl_list_label($row['title']), $row['code_text'], $row['units'], $row['id'],
      $row['providername']);
  }

  // Generate totals line for last provider.
  thisLineItem(0, 0, '', '', '', '', 0 , 0, '');

  // Generate grand totals.
  if (!$_POST['form_csvexport']) {
?>
 <tr bgcolor="#dddddd">
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail" colspan="3">
   <?php echo xl('Grand Totals'); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grandqty; ?>
  </td>
  <td class="detail">
   &nbsp; <!-- No prices at the provider level. -->
  </td>
  <td class="dehead" align="right">
   <?php echo oeFormatMoney($grandadj); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo oeFormatMoney($grandchg - $grandadj); ?>
  </td>
 </tr>
<?php
  } // End not csv export
}

if (! $_POST['form_csvexport']) {
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
<?php
} // End not csv export
?>
