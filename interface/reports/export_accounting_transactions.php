<?php
// Copyright (C) 2013, 2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is a report of transactions for export to an accounting system.
// Initially developed for a client using Peachtree Accounting.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");

$debugging  = false;

$cashmethod = 'Cash';
$now        = time();
$warnings   = '';

function bucks($amount) {
  if ($amount) return oeFormatMoney($amount);
  return '';
}

function display_csv($s) {
  return addslashes($s);
}

function display_html($s) {
  $s = trim($s);
  if ($s === '') return '&nbsp';
  return htmlspecialchars($s);
}

// Get a list item's title, translated if appropriate.
function getListTitle($list, $option) {
  $row = sqlQuery("SELECT title FROM list_options WHERE " .
    "list_id = '$list' AND option_id = '$option' AND activity = 1");
  if (empty($row['title'])) return $option;
  return xl_list_label($row['title']);
}

// Get a list item's mapping value.
function getListMapping($list, $option) {
  $row = sqlQuery("SELECT mapping FROM list_options WHERE " .
    "list_id = '$list' AND option_id = '$option' AND activity = 1");
  return $row['mapping'];
}

function recordNewInvoice($patient_id, $encounter_id, $invoice_refno, $pos, $npi, $exclude=false) {
  global $aItems;
  $invno = "$patient_id.$encounter_id";
  if (isset($aItems[$invno])) return $invno;
  $aItems[$invno] = array();
  recordNewChargeable($patient_id, $encounter_id, '', $invoice_refno);
  $aItems[$invno]['']['pos'] = $pos;
  $aItems[$invno]['']['npi'] = $npi;
  $aItems[$invno]['']['exc'] = $exclude;
  if ($exclude) {
    $aItems[$invno]['']['msg'] .= xl("Visit not in the report range so reporting charges as 0.") . ' ';
  }
  return $invno;
}

function recordNewChargeable($patient_id, $encounter_id, $codekey='', $description='', $glacct='') {
  global $aItems;
  $invno = "$patient_id.$encounter_id";
  if (isset($aItems[$invno][$codekey])) return;
  $aItems[$invno][$codekey] = array();
  $aItems[$invno][$codekey]['chg'] = 0;
  $aItems[$invno][$codekey]['qty'] = 0;
  $aItems[$invno][$codekey]['dsc'] = $description;
  $aItems[$invno][$codekey]['gla'] = $glacct;
  $aItems[$invno][$codekey]['msg'] = '';
  $aItems[$invno][$codekey]['adj'] = array();
  $aItems[$invno][$codekey]['pay'] = array();
  $aItems[$invno][$codekey]['adt'] = array();
  $aItems[$invno][$codekey]['pdt'] = array();
}

function accumulateChargeable($patient_id, $encounter_id, $codekey, $amount, $quantity) {
  global $aItems;
  $invno = "$patient_id.$encounter_id";
  $aItems[$invno][$codekey]['chg'] += $amount;
  $aItems[$invno][$codekey]['qty'] += $quantity;
}

function accumulateAdjustment($patient_id, $encounter_id, $codekey, $type, $amount, $adjdate='') {
  global $aItems;
  $invno = "$patient_id.$encounter_id";
  if (!isset($aItems[$invno][$codekey])) {
    // Adjustment matches no charge item and is not invoice level.
    // This is an error. Force invoice level.
    $codekey = '';
  }
  if (!isset($aItems[$invno][$codekey]['adj'][$type])) {
    $aItems[$invno][$codekey]['adj'][$type] = 0;
    $aItems[$invno][$codekey]['adt'][$type] = '';
  }
  $aItems[$invno][$codekey]['adj'][$type] += $amount;
  if ($adjdate) {
    $aItems[$invno][$codekey]['adt'][$type] = $adjdate;
  }
}

function accumulatePayment($patient_id, $encounter_id, $codekey, $method, $amount, $paydate='') {
  global $aItems, $debugging;
  $invno = "$patient_id.$encounter_id";

  if ($debugging) {echo "<!-- accumulatePayment for $invno $codekey: $method $amount $paydate -->\n";}

  /*******************************************************************
  if (!isset($aItems[$invno][$codekey])) {
    // Payment matches no charge item and is not invoice level.
    // This is an error. Force invoice level.
    if ($debugging) {echo "<!-- codekey '$codekey' not valid! -->\n";}
    $codekey = '';
  }
  *******************************************************************/
  // Per 2013-08-22 decision, force invoice level for all payments.
  $codekey = '';

  if (!isset($aItems[$invno][$codekey]['pay'][$method])) {
    $aItems[$invno][$codekey]['pay'][$method] = 0;
    $aItems[$invno][$codekey]['pdt'][$method] = '';
  }
  $aItems[$invno][$codekey]['pay'][$method] += $amount;
  if ($paydate) {
    $aItems[$invno][$codekey]['pdt'][$method] = $paydate;
  }
}

function getRelatedCode($related_code, $code_type='ACCT') {
  if (!empty($related_code)) {
    $relcodes = explode(';', $related_code);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);
      if ($codetype == $code_type) return $code;
    }
  }
  return '';
}

/****
  Here are the columns to generate:
  1. Customer ID             - e.g. 02
     Facility NPI
  2. Customer Name           - always blank
  3. Reference               - Same as Receipt Number except not a link
  4. Date                    - e.g. 07/16/13
     Posting date of payment
  5. Payment Method          - e.g. Cash
  6. Cash Account            - e.g. 1112-02
     Use global ID from payment method + facility suffix.
  7. Cash Amount             - e.g. 12.00
     Amount paid in cash.
  8. Number of Distributions - e.g. 2
     Number of exported rows for this receipt number.
  9. Description             - e.g. Consulta Ginecológica
     Service or product description
 10. Invoice Paid            - always blank
 11. G/L Account             - e.g. 4121-02
     For services: service ACCT code + facility code.
     For products: 4261 if POS=01 else 4262, + facility code.
 12. Quantity                - e.g. 1
     Quantity if a charge item, else empty.
     Empty for adjustments.
 13. Unit Price              - e.g. 15
     Charge (positive) or adjustment (negative) amount.
 14. Amount                  - e.g. -15
     Negative charge amount or positive adjustment amount.
 15. Receipt Number          - e.g. 02-443818
     Facility code and Invoice reference number as a link.
****/
function thisLineItem($patient_id, $encounter_id, $npi, $paydate, $paymethod,
  $payaccount, $payamount, $rowcount, $description, $saleaccount, $quantity,
  $price, $amount, $invoice_refno)
{
  global $now, $warnings;
  $reference = date("Ymd_h-m_$npi", $now);

  if ($paydate) {
    $paydate = substr($paydate, 5, 2) . '/' . substr($paydate, 8, 2) . '/' . substr($paydate, 2, 2);
  }

  if ($_POST['form_csvexport']) {
    echo '"' . display_csv($npi)                  . '",';
    echo '"' . ''                                 . '",';
    echo '"' . display_csv("$npi-$invoice_refno") . '",';
    echo '"' . display_csv($paydate)              . '",';
    echo '"' . display_csv($paymethod)            . '",';
    echo '"' . display_csv($payaccount)           . '",';
    echo '"' . bucks($payamount)                  . '",';
    echo '"' . $rowcount                          . '",';
    echo '"' . display_csv($description)          . '",';
    echo '"' . ''                                 . '",';
    echo '"' . display_csv($saleaccount)          . '",';
    echo '"' . display_csv($quantity)             . '",';
    echo '"' . bucks($price)                      . '",';
    echo '"' . bucks($amount)                     . '",';
    echo '"' . display_csv("$npi-$invoice_refno") . '"';
    echo "\n";
  }
  else {
?>
 <tr>
  <td class='detail'><?php echo display_html($npi); ?></td>
  <td class='detail'><?php echo display_html(''); ?></td>
  <td class='detail'><?php echo display_html("$npi-$invoice_refno"); ?></td>
  <td class='detail'><?php echo display_html($paydate); ?></td>
  <td class='detail'><?php echo display_html($paymethod); ?></td>
  <td class='detail'><?php echo display_html($payaccount); ?></td>
  <td class='detail' align='right'><?php echo bucks($payamount); ?></td>
  <td class='detail' align='right'><?php echo display_html($rowcount); ?></td>
  <td class='detail'><?php echo display_html($description); ?></td>
  <td class='detail'><?php echo display_html(''); ?></td>
  <td class='detail'><?php echo display_html($saleaccount); ?></td>
  <td class='detail' align='right'><?php echo display_html($quantity); ?></td>
  <td class='detail' align='right'><?php echo bucks($price); ?></td>
  <td class='detail' align='right'><?php echo bucks($amount); ?></td>
  <td class='delink' onclick='doinvopen(<?php echo "$patient_id,$encounter_id"; ?>)'
   ><?php echo display_html("$npi-$invoice_refno"); ?></td>
  <td class='detail'><?php echo display_html($warnings); ?></td>
 </tr>
<?php
  } // End not csv export
  $warnings = '';
} // end function thisLineItem

if (!acl_check('acct', 'rep_a')) die(xl("Unauthorized access."));

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_facility  = $_POST['form_facility'];

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=accounting_transactions.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";
  // CSV headers:
  echo '"' . xl('Customer ID'            ) . '",';
  echo '"' . xl('Customer Name'          ) . '",';
  echo '"' . xl('Reference'              ) . '",';
  echo '"' . xl('Date'                   ) . '",';
  echo '"' . xl('Payment Method'         ) . '",';
  echo '"' . xl('Cash Account'           ) . '",';
  echo '"' . xl('Cash Amount'            ) . '",';
  echo '"' . xl('Number of Distributions') . '",';
  echo '"' . xl('Invoice Paid'           ) . '",';
  echo '"' . xl('Description'            ) . '",';
  echo '"' . xl('G/L Account'            ) . '",';
  echo '"' . xl('Quantity'               ) . '",';
  echo '"' . xl('Unit Price'             ) . '",';
  echo '"' . xl('Amount'                 ) . '",';
  echo '"' . xl('Receipt Number'         ) . '"';
  echo "\n";
} // end export

else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Accounting Transactions','e') ?></title>

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
</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php xl('Accounting Transactions','e')?></h2>

<form method='post' action='export_accounting_transactions.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td>
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
   <input type='text' name='form_from_date' id="form_from_date" size='8' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
   &nbsp;<?php xl('To','e'); ?>:
   <input type='text' name='form_to_date' id="form_to_date" size='8' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
   &nbsp;
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
  <td class="dehead"><?php echo xl('Customer ID'); ?></td>
  <td class="dehead"><?php echo xl('Customer Name'); ?></td>
  <td class="dehead"><?php echo xl('Reference'); ?></td>
  <td class="dehead"><?php echo xl('Date'); ?></td>
  <td class="dehead"><?php echo xl('Payment Method'); ?></td>
  <td class="dehead"><?php echo xl('Cash Account'); ?></td>
  <td class="dehead"><?php echo xl('Cash Amount'); ?></td>
  <td class="dehead"><?php echo xl('Number of Distributions'); ?></td>
  <td class="dehead"><?php echo xl('Description'); ?></td>
  <td class="dehead"><?php echo xl('Invoice Paid'); ?></td>
  <td class="dehead"><?php echo xl('G/L Account'); ?></td>
  <td class="dehead"><?php echo xl('Quantity'); ?></td>
  <td class="dehead"><?php echo xl('Unit Price'); ?></td>
  <td class="dehead"><?php echo xl('Amount'); ?></td>
  <td class="dehead"><?php echo xl('Receipt Number'); ?></td>
  <td class="dehead"><?php echo xl('Warnings'); ?></td>
 </tr>
<?php
} // end not export

if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
  if ($_POST['form_csvexport']) $debugging = false;

  $from_date = $form_from_date;
  $to_date   = $form_to_date;

  // $aItems[$invno]['']                = invoice level info
  // $aItems[$invno]['']['npi']         = facility npi for the encounter
  // $aItems[$invno]['']['pos']         = facility pos code for the encounter
  // $aItems[$invno]['']['exc']         = true to exclude charges from reporting
  // $aItems[$invno][$codekey]          = info for an invoice and charge code
  // $aItems[$invno][$codekey]['chg']   = total charge for this item
  // $aItems[$invno][$codekey]['qty']   = quantity of this item
  // $aItems[$invno][$codekey]['dsc']   = description of this item
  // $aItems[$invno][?]['adj'][$type]   = adj amount for the given adj type
  // $aItems[$invno][?]['pay'][$method] = pay amount for the given pay method
  // $aItems[$invno][?]['adt'][$type]   = last adj date for the given adjustment type
  // $aItems[$invno][?]['pdt'][$method] = last pay date for the given payment method
  //
  $aItems = array();

  // Get billing table items for encounters in the date range.
  $query = "SELECT b.fee, b.pid, b.encounter, b.code_type, b.code, b.units, " .
    "b.code_text, c.related_code, fe.date, fe.invoice_refno, " .
    "fas.pos_code, fab.facility_npi " .
    "FROM billing AS b " .
    "JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter " .
    "LEFT JOIN facility AS fas ON fas.id = fe.facility_id " .
    "LEFT JOIN facility AS fab ON fab.id = fe.billing_facility " .
    "LEFT JOIN code_types AS ct ON ct.ct_key = b.code_type " .
    "LEFT JOIN codes AS c ON c.code_type = ct.ct_id AND c.code = b.code AND c.modifier = b.modifier " .
    "WHERE b.activity = 1 AND b.fee != 0 AND " .
    "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59'";
  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = '$form_facility'";
  }
  $query .= " ORDER BY fe.pid, fe.encounter, b.id";
  //
  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res)) {

    if ($debugging) {echo "<!-- Service row:\n"; print_r($row); echo "-->\n";}

    recordNewInvoice($row['pid'], $row['encounter'], $row['invoice_refno'],
      $row['pos_code'], $row['facility_npi']);
    if ($row['code_type'] == 'COPAY') {
      accumulatePayment($row['pid'], $row['encounter'], '', $row['code_text'], 0 - $row['fee'], $row['date']);
      continue;
    }
    $codekey = $row['code_type'] . ':' . $row['code'];
    $glacct = getRelatedCode($row['related_code'], 'ACCT') . '-' . $row['facility_npi'];
    recordNewChargeable($row['pid'], $row['encounter'], $codekey, $row['code_text'], $glacct);
    accumulateChargeable($row['pid'], $row['encounter'], $codekey, $row['fee'], $row['units']);
  }

  // Get product sales items for encounters in the date range.
  $query = "SELECT s.sale_date, s.fee, s.quantity, s.pid, s.encounter, " .
    "s.drug_id, d.name, fe.date, fe.facility_id, fe.invoice_refno, " .
    "fas.pos_code, fab.facility_npi " .
    "FROM drug_sales AS s " .
    "LEFT JOIN drugs AS d ON d.drug_id = s.drug_id " .
    "JOIN form_encounter AS fe ON " .
    "fe.pid = s.pid AND fe.encounter = s.encounter AND " .
    "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59' " .
    "LEFT JOIN facility AS fas ON fas.id = fe.facility_id " .
    "LEFT JOIN facility AS fab ON fab.id = fe.billing_facility " .
    "WHERE s.fee != 0";
  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = '$form_facility'";
  }
  $query .= " ORDER BY fe.pid, fe.encounter, s.sale_id";
  //
  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res)) {

    if ($debugging) {echo "<!-- Product row:\n"; print_r($row); echo "-->\n";}

    recordNewInvoice($row['pid'], $row['encounter'], $row['invoice_refno'],
      $row['pos_code'], $row['facility_npi']);
    $codekey = 'PROD:' . $row['drug_id'];
    $glacct = ($row['pos_code'] == '01' ? '4261' : '4262') . '-' . $row['facility_npi'];
    recordNewChargeable($row['pid'], $row['encounter'], $codekey, $row['name'], $glacct);
    accumulateChargeable($row['pid'], $row['encounter'], $codekey, $row['fee'], $row['quantity']);

    // if ($debugging) {echo "<!-- After product recorded:\n"; print_r($aItems); echo "-->\n";}
  }

  // Get adjustments and other payments from ar_activity table.
  $query = "SELECT " .
    "a.pid, a.encounter, a.code_type, a.code, a.adj_amount, a.pay_amount, a.post_date, a.post_time, a.memo, " .
    "fas.pos_code, fab.facility_npi " .
    "FROM ar_activity AS a " .
    "JOIN form_encounter AS fe ON fe.pid = a.pid AND fe.encounter = a.encounter " .
    "LEFT JOIN facility AS fas ON fas.id = fe.facility_id " .
    "LEFT JOIN facility AS fab ON fab.id = fe.billing_facility " .
    "WHERE " .
    "((a.post_date IS NOT NULL AND a.post_date >= '$from_date' AND a.post_date <= '$to_date') OR " .
    "(a.post_date IS NULL AND a.post_time >= '$from_date 00:00:00' AND a.post_time <= '$to_date 23:59:59'))";
  // If a facility was specified.
  if ($form_facility) $query .= " AND fe.facility_id = '$form_facility'";
  $query .= " ORDER BY fe.pid, fe.encounter, a.sequence_no";
  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res)) {

    if ($debugging) {echo "<!-- Pay/adjust row:\n"; print_r($row); echo "-->\n";}

    $patient_id = $row['pid'];
    $encounter_id = $row['encounter'];
    $invno = $patient_id . '.' . $encounter_id;
    $post_date = empty($row['post_date']) ? substr($row['post_time'], 0, 10) : $row['post_date'];

    if (!isset($aItems[$invno])) {
      // We have a visit with payments or adjustments in the reporting date range,
      // but the visit date is not. Need to gather info about its charge items so
      // that any invoice-level adjustments can be allocated among them.
      // However we will exlude reporting of those charges because they do not apply
      // to this date range.
      recordNewInvoice($patient_id, $encounter_id, $row['invoice_refno'],
        $row['pos_code'], $row['facility_npi'], true);
      //
      $query = "SELECT b.code_type, b.code, b.code_text, b.fee, b.units, c.related_code " .
        "FROM billing AS b " .
        "LEFT JOIN code_types AS ct ON ct.ct_key = b.code_type " .
        "LEFT JOIN codes AS c ON c.code_type = ct.ct_id AND c.code = b.code AND c.modifier = b.modifier " .
        "WHERE b.code_type != 'COPAY' AND b.activity = 1 AND b.fee != 0 AND " .
        "b.pid = '$patient_id' AND b.encounter = '$encounter_id'";
      $bres = sqlStatement($query);
      while ($brow = sqlFetchArray($bres)) {

        if ($debugging) {echo "<!-- Excluded service row:\n"; print_r($brow); echo "-->\n";}

        $tmpkey = $brow['code_type'] . ':' . $brow['code'];
        recordNewChargeable($patient_id, $encounter_id, $tmpkey, $brow['code_text']);
        accumulateChargeable($patient_id, $encounter_id, $tmpkey, $brow['fee'], $brow['units']);

        // if ($debugging) {echo "<!-- After excluded service recorded:\n"; print_r($aItems); echo "-->\n";}

      }
      //
      $query = "SELECT s.drug_id, s.fee, s.quantity, d.name " .
        "FROM drug_sales AS s " .
        "LEFT JOIN drugs AS d ON d.drug_id = s.drug_id " .
        "WHERE s.pid = '$patient_id' AND s.encounter = '$encounter_id'";
      $dres = sqlStatement($query);
      while ($drow = sqlFetchArray($dres)) {

        if ($debugging) {echo "<!-- Excluded product row:\n"; print_r($drow); echo "-->\n";}

        $tmpkey = 'PROD:' . $drow['drug_id'];
        recordNewChargeable($patient_id, $encounter_id, $tmpkey, $drow['name']);
        accumulateChargeable($patient_id, $encounter_id, $tmpkey, $drow['fee'], $drow['quantity']);
      }
    }

    $codekey = '';
    if ($row['code_type']) {
      $codekey = $row['code_type'] . ':' . $row['code'];
    }

    // Accumulate adjustments by adjustment reason within charge (or at invoice level).
    if ($row['adj_amount'] != 0.00) {
      accumulateAdjustment($patient_id, $encounter_id, $codekey, $row['memo'], $row['adj_amount'], $post_date);
    }

    // Accumulate payments by payment method within charge (or at invoice level).
    if ($row['pay_amount'] != 0.00) {
      $tmp = explode(' ', $row['memo']);
      accumulatePayment($patient_id, $encounter_id, $codekey, $tmp[0], $row['pay_amount'], $post_date);
    }
  }

  if ($debugging) {echo "<!-- After building from database:\n"; print_r($aItems); echo "-->\n";}

  // For each invoice...
  foreach ($aItems as $invno => $dummy) {
    list($patient_id, $encounter_id) = explode('.', $invno);

    // Compute $chgtotal as total charges for the invoice while removing dead charge entries.
    $chgtotal = 0;
    foreach ($aItems[$invno] as $codekey => $dummy) {
      if ($codekey !== '' &&
          $aItems[$invno][$codekey]['chg'] == 0.00 && 
          count($aItems[$invno][$codekey]['adj']) == 0 &&
          count($aItems[$invno][$codekey]['pay']) == 0)
      {
        if ($debugging) {echo "<!-- Removing $invno/$codekey as dead:\n"; print_r($aItems[$invno][$codekey]); echo "-->\n";}
        unset($aItems[$invno][$codekey]);
      }
      else {
        $chgtotal += $aItems[$invno][$codekey]['chg'];
      }
    }

    if ($chgtotal != 0.00) {
      // Allocate the invoice-level adjustments to line items in proportion to charges.
      // TBD: Could be better to look at (charges - adjustments) instead.
      foreach ($aItems[$invno]['']['adj'] as $type => $dummy) {
        $adjtot = $aItems[$invno]['']['adj'][$type];
        $codekey = '';
        foreach ($aItems[$invno] as $codekey => $dummy) {
          if ($codekey === '') continue;
          $adjthis = sprintf('%01.2f', $adjtot * $aItems[$invno][$codekey]['chg'] / $chgtotal);
          $aItems[$invno]['']['adj'][$type] -= $adjthis;
          accumulateAdjustment($patient_id, $encounter_id, $codekey, $type,
            $adjthis, $aItems[$invno]['']['adt'][$type]);
        }
        if ($codekey !== '') {
          // Deposit round-off errors into the final line item.
          accumulateAdjustment($patient_id, $encounter_id, $codekey, $type,
            $aItems[$invno]['']['adj'][$type]);
          unset($aItems[$invno]['']['adj'][$type]);
          unset($aItems[$invno]['']['adt'][$type]);
        }
      }
      /***************************************************************
      // Allocate the invoice-level payments to line items in proportion to charges.
      // TBD: Could be better to look at (charges - adjustments) instead.
      foreach ($aItems[$invno]['']['pay'] as $method => $dummy) {
        $paytot = $aItems[$invno]['']['pay'][$method];
        $codekey = '';
        foreach ($aItems[$invno] as $codekey => $dummy) {
          if ($codekey === '') continue;
          $paythis = sprintf('%01.2f', $paytot * $aItems[$invno][$codekey]['chg'] / $chgtotal);
          $aItems[$invno]['']['pay'][$method] -= $paythis;
          accumulatePayment($patient_id, $encounter_id, $codekey, $method,
            $paythis, $aItems[$invno]['']['pdt'][$method]);
        }
        if ($codekey !== '') {
          // Deposit round-off errors into the final line item.
          accumulatePayment($patient_id, $encounter_id, $codekey, $method,
            $aItems[$invno]['']['pay'][$method]);
          unset($aItems[$invno]['']['pay'][$method]);
          unset($aItems[$invno]['']['pdt'][$method]);
        }
      }
      ***************************************************************/
    }
    else {
      /***************************************************************
      $aItems[$invno]['']['msg'] .= xl("This visit has no charges.") . ' ';
      ***************************************************************/
      if ($debugging) {echo "<!-- Removing invoice $invno with no charges. -->\n";}
      unset($aItems[$invno]);
    }
    // if ($debugging) {echo "<!-- After massaging for $invno:\n"; print_r($aItems); echo "-->\n";}
  }

  if ($debugging) {echo "<!-- Before ksort:\n"; print_r($aItems); echo "-->\n";}

  // Sort by invoice number to make the output a bit nicer.
  ksort($aItems);

  // Now the main loop for output.
  foreach ($aItems as $invno => $dummy) {
    // Also sort each invoice's items by code key for nicer output.
    ksort($aItems[$invno]);

    $exclude       = $aItems[$invno]['']['exc'];
    $npi           = $aItems[$invno]['']['npi'];
    $pos_code      = $aItems[$invno]['']['pos'];
    $invoice_refno = $aItems[$invno]['']['dsc'];

    list($patient_id, $encounter_id) = explode('.', $invno);

    // Compute the number of rows that we'll generate. There is a row for each
    // charge that also includes all cash payments, a row for each non-cash
    // payment method, and a row for each of its adjustment types.
    $rowcount = 0;
    $cashpaid = 0;
    $cashdate = '';
    foreach ($aItems[$invno] as $codekey => $dummy) {
      if ($codekey !== '') ++$rowcount;
      $rowcount += count($aItems[$invno][$codekey]['adj']);
      foreach ($aItems[$invno][$codekey]['pay'] as $method => $amount) {
        if ($method == $cashmethod) {
          $cashpaid += $amount;
          $cashdate = $aItems[$invno][$codekey]['pdt'][$method];
          unset($aItems[$invno][$codekey]['pay'][$method]);
          unset($aItems[$invno][$codekey]['pdt'][$method]);
        }
        else {
          // Payments are only at invoice level so this happens only
          // once per invoice per non-cash method.
          ++$rowcount;
        }
        /*************************************************************
        // Cash payments are on the charge line and if there are none then invent one
        if ($method != $cashmethod || $rowcount == 0) {
          ++$rowcount;
        }
        *************************************************************/
      }
    }

    foreach ($aItems[$invno] as $codekey => $dummy) {
      $warnings .= $aItems[$invno][$codekey]['msg'];

      /***************************************************************
      $cashpaid = 0;
      $cashdate = '';
      ***************************************************************/
      if ($codekey !== '') {
        /*************************************************************
        foreach ($aItems[$invno][$codekey]['pay'] as $method => $amount) {
          if ($method == $cashmethod) {
            $cashpaid += $amount;
            $cashdate = $aItems[$invno][$codekey]['pdt'][$method];
            unset($aItems[$invno][$codekey]['pay'][$method]);
            unset($aItems[$invno][$codekey]['pdt'][$method]);
          }
        }
        *************************************************************/
        // Charges not in date range are reported as zero.
        $charge = $exclude ? 0 : $aItems[$invno][$codekey]['chg'];
        $quantity = 0 + $aItems[$invno][$codekey]['qty'];
        if (empty($quantity)) $quantity = 1;
        // Generate a row for the charge item.
        thisLineItem($patient_id, $encounter_id, $npi,
          $cashdate,
          getListTitle('paymethod', $cashmethod),
          getListMapping('paymethod', $cashmethod) . '-' . $npi,
          $cashpaid, $rowcount,
          $aItems[$invno][$codekey]['dsc'],
          $aItems[$invno][$codekey]['gla'],
          $quantity,
          $charge / $quantity,
          0 - $charge,
          $invoice_refno);
      }

      foreach ($aItems[$invno][$codekey]['adj'] as $type => $adjamount) {
        // Gen line item for an adjustment.
        thisLineItem($patient_id, $encounter_id, $npi,
          $aItems[$invno][$codekey]['adt'][$type],
          getListTitle('paymethod', $cashmethod),
          getListMapping('paymethod', $cashmethod) . '-' . $npi,
          $cashpaid, $rowcount,
          getListTitle('adjreason', $type),
          getListMapping('adjreason', $type) . '-' . $npi,
          '',
          0 - $adjamount, $adjamount,
          $invoice_refno);
      }
    }

    // Payments are only at invoice level, but for robustness handle them
    // here at the charge level too.
    foreach ($aItems[$invno] as $codekey => $dummy) {
      foreach ($aItems[$invno][$codekey]['pay'] as $method => $payamount) {
        // Gen line item for a non-cash payment.
        thisLineItem($patient_id, $encounter_id, $npi,
          $aItems[$invno][$codekey]['pdt'][$method],
          getListTitle('paymethod', $cashmethod),
          getListMapping('paymethod', $cashmethod) . '-' . $npi,
          $cashpaid, $rowcount,
          getListTitle('paymethod', $method),
          getListMapping('paymethod', $method) . '-' . $npi,
          '',
          0 - $payamount, $payamount,
          $invoice_refno);
      }
    }
  }
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
