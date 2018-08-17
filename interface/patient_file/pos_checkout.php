<?php
// Copyright (C) 2006-2018 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This module supports a popup window to handle patient checkout
// as a point-of-sale transaction.  Support for in-house drug sales
// is included.

// Important notes about system design:
//
// (1) Drug sales may or may not be associated with an encounter;
//     they are if they are paid for concurrently with an encounter, or
//     if they are "product" (non-prescription) sales via the Fee Sheet.
//     UPDATE: ENCOUNTER IS NOW ALWAYS REQUIRED.
// (2) Drug sales without an encounter will have 20YYMMDD, possibly
//     with a suffix, as the encounter-number portion of their invoice
//     number.
//     UPDATE: ENCOUNTER IS NOW ALWAYS REQUIRED.
// (3) Payments are saved as AR only, don't mess with the billing table.
//     See library/classes/WSClaim.class.php for posting code.
// (4) On checkout, the billing and drug_sales table entries are marked
//     as billed and so become unavailable for further billing.
// (5) Receipt printing must be a separate operation from payment,
//     and repeatable.

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/date_functions.php");
require_once("$srcdir/formdata.inc.php");
require_once("../../custom/code_types.inc.php");
require_once("$srcdir/appointment_status.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/sl_eob.inc.php");
require_once($GLOBALS['srcdir'] . "/checkout_receipt_array.inc.php");
require_once("$srcdir/FeeSheet.class.php");

$currdecimals = $GLOBALS['currency_decimals'];

if ($GLOBALS['oer_config']['ws_accounting']['enabled'] !== 2) {
  die("SQL-Ledger not supported!");
}

if (!acl_check('admin', 'super') && !acl_check('acct', 'bill') && !acl_check('acct', 'disc')) {
  die("Not authorized!");
}

// This will be used for SQL timestamps that we write.
$this_bill_date = date('Y-m-d H:i:s');

// Details default to yes now.
$details = (!isset($_GET['details']) || !empty($_GET['details'])) ? 1 : 0;

$patient_id   = empty($_GET['ptid']) ? $pid : intval($_GET['ptid']);
$encounter_id = empty($_GET['enid']) ?    0 : intval($_GET['enid']);
$checkout_id  = empty($_GET['coid']) ?   '' : $_GET['coid']; // timestamp of checkout

// This flag comes from the Fee Sheet form and perhaps later others.
$rapid_data_entry = empty($_GET['rde']) ? 0 : 1;

// Get the patient's name and chart number.
$patdata = getPatientData($patient_id, 'fname,mname,lname,pubpid,street,city,state,postal_code');

// Adjustments from the ar_activity table.
$aAdjusts = array();

// Format a money amount with decimals but no other decoration.
// Second argument is used when extra precision is required.
function formatMoneyNumber($value, $extradecimals=0) {
  return sprintf('%01.' . ($GLOBALS['currency_decimals'] + $extradecimals) . 'f', $value);
}

// Get a list item's title, translated if appropriate.
//
function getListTitle($list, $option) {
  $row = sqlQuery("SELECT title FROM list_options WHERE " .
    "list_id = '$list' AND option_id = '$option' AND activity = 1");
  if (empty($row['title'])) return $option;
  return xl_list_label($row['title']);
}

function generate_layout_display_field($formid, $fieldid, $currvalue) {
  $frow = sqlQuery("SELECT * FROM layout_options " .
    "WHERE form_id = '$formid' AND field_id = '$fieldid' " .
    "LIMIT 1");
  if (empty($frow)) return $currvalue;
  return generate_display_field($frow, $currvalue);
}

// This creates and loads the array $aAdjusts of adjustment data for this encounter.
//
function load_adjustments($patient_id, $encounter_id) {
  global $aAdjusts;
  // Create array aAdjusts from ar_activity rows for $encounter_id.
  $aAdjusts = array();
  $ares = sqlStatement("SELECT " .
    "a.payer_type, a.adj_amount, a.memo, a.code_type, a.code, a.post_time, a.post_date, " .
    "s.session_id, s.reference, s.check_date, lo.title AS memotitle " .
    "FROM ar_activity AS a " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'adjreason' AND lo.option_id = a.memo AND lo.activity = 1 " .
    "LEFT JOIN ar_session AS s ON s.session_id = a.session_id WHERE " .
    "a.pid = '$patient_id' AND a.encounter = '$encounter_id' AND a.deleted IS NULL AND " .
    "( a.adj_amount != 0 OR a.pay_amount = 0 ) " .
    "ORDER BY s.check_date, a.sequence_no");
  while ($arow = sqlFetchArray($ares)) {
    if (empty($arow['memotitle'])) $arow['memotitle'] = $arow['memo'];
    $aAdjusts[] = $arow;
  }
}

// Total and clear adjustments in $aAdjusts matching this line item. Should only
// happen for billed items, and matching includes the billing timestamp in order
// to handle the case of multiple checkouts.
function pull_adjustment($code_type, $code, $billtime, &$memo) {
  global $aAdjusts;
  $adjust = 0;
  $memo = '';
  if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) {
    for ($i = 0; $i < count($aAdjusts); ++$i) {
      if ($aAdjusts[$i]['code_type'] == $code_type && $aAdjusts[$i]['code'] == $code &&
        $aAdjusts[$i]['post_time'] == $billtime &&
        ($aAdjusts[$i]['adj_amount'] != 0 || $aAdjusts[$i]['memotitle'] !== ''))
      {
        $adjust += $aAdjusts[$i]['adj_amount'];
        if ($memo && $aAdjusts[$i]['memotitle']) $memo .= ', ';
        $memo .= $aAdjusts[$i]['memotitle'];
        $aAdjusts[$i]['adj_amount'] = 0;
        $aAdjusts[$i]['memotitle'] = '';
      }
    }
  }
  return $adjust;
}

// Generate $aTaxNames = array of tax names, and $aInvTaxes = array of taxes for this invoice.
// For a given tax ID and line ID, $aInvTaxes[$taxID][$lineID] = tax amount.
// $lineID identifies the invoice line item (product or service) that was taxed, and is of the
// form S:<billing.id> or P:<drug_sales.sale_id>
// Taxes may change from time to time and $aTaxNames reflects only the taxes that were present
// for this invoice.
//
function load_taxes($patient_id, $encounter) {
  global $aTaxNames, $aInvTaxes, $taxes;
  global $num_optional_columns, $rcpt_num_method_columns, $rcpt_num_ref_columns, $rcpt_num_amount_columns;
  global $form_num_type_columns, $form_num_method_columns, $form_num_ref_columns, $form_num_amount_columns;

  $aTaxNames = array();
  $aInvTaxes = array();

  /********************************************************************
  $tnres = sqlStatement("SELECT DISTINCT code, code_text " .
    "FROM billing WHERE " .
    "code_type = 'TAX' AND activity = '1' AND " .
    "pid = '$patient_id' AND encounter = '$encounter' " .
    "ORDER BY code, code_text");
  while ($tnrow = sqlFetchArray($tnres)) {
    $aTaxNames[$tnrow['code']] = $tnrow['code_text'];
    $aInvTaxes[$tnrow['code']] = array();
  }
  ********************************************************************/
  foreach ($taxes as $taxid => $taxarr) {
    $aTaxNames[$taxid] = $taxarr[0];
    $aInvTaxes[$taxid] = array();
  }

  $taxres = sqlStatement("SELECT code, fee, ndc_info " .
    "FROM billing WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter' AND " .
    "code_type = 'TAX' AND activity = 1 " .
    "ORDER BY id");
  while ($taxrow = sqlFetchArray($taxres)) {
    $aInvTaxes[$taxrow['code']][$taxrow['ndc_info']] = $taxrow['fee'];
  }
  // echo "<!--\n"; print_r($aInvTaxes); echo "-->\n"; // debugging

  // Knowing the number of tax columns we can now compute the total number of optional
  // columns and from that the colspan values for various things.
  $num_optional_columns = (empty($GLOBALS['gbl_checkout_charges']) ? 0 : 1) +
      (empty($GLOBALS['gbl_charge_categories']) ? 0 : 1) +
      (empty($GLOBALS['gbl_checkout_line_adjustments']) ? 0 : 2) +
      count($aTaxNames);
  // Compute colspans for receipt payment rows.
  // What's in play here are columns for Qty, Price, the optionals, and Total.
  $rcpt_num_method_columns = 1;
  $rcpt_num_ref_columns = 1;
  if ($num_optional_columns == 1) {
    $rcpt_num_method_columns = 2;
  }
  else if ($num_optional_columns > 1) {
    $rcpt_num_method_columns = 3;
    $rcpt_num_ref_columns = $num_optional_columns - 1;
  }
  $rcpt_num_amount_columns = 3 + $num_optional_columns - $rcpt_num_method_columns - $rcpt_num_ref_columns;
  // Compute colspans for form payment rows.
  $form_num_type_columns = 2;
  $form_num_method_columns = 1;
  $form_num_ref_columns = 1;
  if ($num_optional_columns > 0) {
    $form_num_method_columns = 2;
  }
  if ($num_optional_columns > 1) {
    $form_num_type_columns = 3;
  }
  $form_num_amount_columns = 5 + $num_optional_columns - $form_num_type_columns - $form_num_method_columns - $form_num_ref_columns;
}

// Use $lineid to match up (and delete) entries in $aInvTaxes with the line.
// $lineid looks like: S:<billing.id> or P:<drug_sales.sale_id>.
// This writes to the $aTaxes argument and returns the total tax for the line.
function pull_tax($lineid, &$aTaxes) {
  global $aInvTaxes;
  $totlinetax = 0;
  foreach ($aInvTaxes as $taxid => $taxarr) {
    $aTaxes[$taxid] = 0;
    if ($lineid !== '') {
      foreach ($taxarr as $taxlineid => $tax) {
        if ($taxlineid === $lineid) {
          $aTaxes[$taxid] += $tax;
          $totlinetax += $tax;
          $aInvTaxes[$taxid][$taxlineid] = 0;
        }
      }
    }
  }

  // echo "<!-- pull_tax($lineid) = $totlinetax -->\n"; // debugging

  // $aTaxes now contains the total of each tax type (keyed on tax ID) for this line item,
  // and those matched amounts are removed from $aInvTaxes.
  return $totlinetax;
}

// Output HTML for a receipt line item.
//
function receiptDetailLine($code_type, $code, $description, $quantity, $charge, &$aTotals='',
  $lineid='', $billtime='', $postdate='', $chargecat='')
{
  global $details;

  // Use $lineid to match up (and delete) entries in $aInvTaxes with the line.
  $aTaxes = array();
  $totlinetax = pull_tax($lineid, $aTaxes);
  // $aTaxes now contains the total of each tax type for this line item, and those matched
  // amounts are removed from $aInvTaxes.

  $adjust = 0;
  $memo = '';
  $isadjust = false;

  // If an adjustment, do appropriate interpretation.
  if ($code_type === '') {
    $isadjust = true;
    $adjust = 0 - $charge;
    $charge = 0;
    list($payer, $code_type, $code) = explode('|', $code);
    $memo = $description;
    $description = $GLOBALS['simplified_demographics'] ? '' : "$payer ";
    $description .= $code ? xl('Item Adjustment') : xl('Invoice Adjustment');
    $quantity = '';
  }
  else {
    // Total and clear adjustments in $aAdjusts matching this line item.
    $adjust += pull_adjustment($code_type, $code, $billtime, $memo);
  }

  $charge = formatMoneyNumber($charge);
  $total  = formatMoneyNumber($charge + $totlinetax - $adjust);
  if (empty($quantity)) $quantity = 1;
  $price = formatMoneyNumber($charge / $quantity, 2);
  $tmp = formatMoneyNumber($price);
  if ($price == $tmp) $price = $tmp;

  if (is_array($aTotals)) {
    $aTotals[0] += $quantity;
    $aTotals[1] += $price;
    $aTotals[2] += $charge;
    $aTotals[3] += $adjust;
    $aTotals[4] += $total;

    // Accumulate columns 5 and beyond for taxes.
    $i = 5;
    foreach ($aTaxes as $tax) {
      $aTotals[$i++] += $tax;
    }
  }

  if (!$details) return;

  if (empty($postdate) || substr($postdate, 0, 4) == '0000') $postdate = $billtime;

  echo " <tr>\n";
  echo "  <td title='" . xla('Entered') . ' ' .
       text(oeFormatShortDate($billtime)) . attr(substr($billtime, 10)) . "'>" .
       text(oeFormatShortDate($postdate)) . "</td>\n";
  echo "  <td>$code</td>\n";
  echo "  <td>$description</td>\n";
  echo "  <td align='center'>" . ($isadjust ? '' : $quantity) . "</td>\n";
  echo "  <td align='right'>" . oeFormatMoney($price,false,true) . "</td>\n";

  if (!empty($GLOBALS['gbl_checkout_charges'])) {
    echo "  <td align='right'>" . oeFormatMoney($charge,false,true) . "</td>\n";
  }

  // Write tax amounts.
  foreach ($aTaxes as $tax) {
    echo "  <td align='right'>" . oeFormatMoney($tax,false,true) . "</td>\n";
  }

  // Charge Category
  if (!empty($GLOBALS['gbl_charge_categories'])) {
    echo "  <td align='right'>" . text($chargecat) . "</td>\n";
  }

  // Adjustment and its description.
  if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) {
    echo "  <td align='right'>" . text($memo) . "</td>\n";
    echo "  <td align='right'>" . oeFormatMoney($adjust,false,true) . "</td>\n";
  }

  echo "  <td align='right'>" . oeFormatMoney($total) . "</td>\n";
  echo " </tr>\n";
}

// Output HTML for a receipt payment line.
//
function receiptPaymentLine($paydate, $amount, $description='', $method='', $refno='', $billtime='') {
  global $aTaxNames, $num_optional_columns;
  global $rcpt_num_method_columns, $rcpt_num_ref_columns, $rcpt_num_amount_columns;
  $amount = formatMoneyNumber($amount); // make it negative
  if ($description == 'Pt') $description = '';
  // Resolve the payment method portion of the memo to display properly.
  if (!empty($method)) {
    $tmp = explode(' ', $method, 2);
    $method = getListTitle('paymethod', $tmp[0]);
    if (isset($tmp[1])) {
      // If the description is not interesting then let it hold the check number
      // or similar, otherwise append that to the payment method.
      if ($description == '') {
        $description = $tmp[1];
      }
      else {
        $method .= ' ' . $tmp[1];
      }
    }
  }
  echo " <tr>\n";
  echo "  <td";
  if (!empty($billtime) && substr($billtime, 0, 4) != '0000') {
    echo " title='" . xla('Entered') . ' ' .
      text(oeFormatShortDate($billtime)) . attr(substr($billtime, 10)) . "'";
  }
  echo ">" . oeFormatShortDate($paydate) . "</td>\n";
  echo "  <td colspan='2'>" . text($refno) . "</td>\n";
  echo "  <td colspan='$rcpt_num_method_columns' align='left'>" . text($method) . "</td>\n";
  echo "  <td colspan='$rcpt_num_ref_columns' align='left'>" . text($description) . "</td>\n";
  echo "  <td colspan='$rcpt_num_amount_columns' align='right'>" . oeFormatMoney($amount) . "</td>\n";
  echo " </tr>\n";
}

// Compute a current checksum of this encounter's invoice-related data from the database.
//
function invoiceChecksum($pid, $encounter) {
  $row1 = sqlQuery("SELECT BIT_XOR(CRC32(CONCAT_WS(',', " .
    "id, code, modifier, units, fee, authorized, provider_id, ndc_info, justify, billed, user, bill_date" .
    "))) AS checksum FROM billing WHERE " .
    "pid = ? AND encounter = ? AND activity = 1",
    array($pid, $encounter));
  $row2 = sqlQuery("SELECT BIT_XOR(CRC32(CONCAT_WS(',', " .
    "sale_id, inventory_id, prescription_id, quantity, fee, sale_date, billed, bill_date" .
    "))) AS checksum FROM drug_sales WHERE " .
    "pid = ? AND encounter = ?",
    array($pid, $encounter));
  $row3 = sqlQuery("SELECT BIT_XOR(CRC32(CONCAT_WS(',', " .
    "sequence_no, code, modifier, payer_type, post_time, post_user, memo, pay_amount, adj_amount, post_date" .
    "))) AS checksum FROM ar_activity WHERE " .
    "pid = ? AND encounter = ?",
    array($pid, $encounter));
  $row4 = sqlQuery("SELECT BIT_XOR(CRC32(CONCAT_WS(',', " .
    "id, date, reason, facility_id, provider_id, supervisor_id, invoice_refno" .
    "))) AS checksum FROM form_encounter WHERE " .
    "pid = ? AND encounter = ?",
    array($pid, $encounter));
  return (0 + $row1['checksum']) ^ (0 + $row2['checksum']) ^ (0 + $row3['checksum']) ^ (0 + $row4['checksum']);
}

//////////////////////////////////////////////////////////////////////
//
// Generate a receipt from the last-billed invoice for this patient,
// or for the encounter specified as a GET parameter.
//
function generate_receipt($patient_id, $encounter=0) {
  global $css_header, $details, $rapid_data_entry, $aAdjusts;
  global $web_root, $webserver_root, $code_types;
  global $aTaxNames, $aInvTaxes, $checkout_times, $current_checksum;
  global $num_optional_columns, $rcpt_num_method_columns, $rcpt_num_ref_columns, $rcpt_num_amount_columns;

  // Get the most recent invoice data or that for the specified encounter.
  if ($encounter) {
    $ferow = sqlQuery("SELECT id, date, encounter, facility_id, invoice_refno " .
      "FROM form_encounter " .
      "WHERE pid = '$patient_id' AND encounter = '$encounter'");
  } else {
    $ferow = sqlQuery("SELECT id, date, encounter, facility_id, invoice_refno " .
      "FROM form_encounter " .
      "WHERE pid = '$patient_id' " .
      "ORDER BY id DESC LIMIT 1");
  }
  if (empty($ferow)) die(xl("This patient has no activity."));
  $trans_id = $ferow['id'];
  $encounter = $ferow['encounter'];
  $svcdate = substr($ferow['date'], 0, 10);
  $invoice_refno = $ferow['invoice_refno'];

  // Generate checksum.
  $current_checksum = invoiceChecksum($patient_id, $encounter);

  // Get details for the visit's facility.
  $frow = sqlQuery("SELECT f.* FROM facility AS f " .
    "WHERE f.id = '" . $ferow['facility_id'] . "'");

  $patdata = getPatientData($patient_id, 'fname,mname,lname,pubpid,street,city,state,postal_code');

  // Get array of checkout timestamps.
  $checkout_times = craGetTimestamps($patient_id, $encounter);

  // Generate $aTaxNames = array of tax names, and $aInvTaxes = array of taxes for this invoice.
  load_taxes($patient_id, $encounter);
?>
<html>
<head>
<?php html_header_show(); ?>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<title><?php xl('Client Receipt','e'); ?></title>

<style type="text/css">
body, td {
 font-family: sans-serif;
 font-size: 10pt;
}
</style>

<script type="text/javascript" src="../../library/dialog.js"></script>
<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 // Process click on Print button.
 function printme(checkout_id) {
<?php if (!empty($GLOBALS['gbl_custom_receipt'])) { ?>
  // Custom checkout receipt needs to be sent as a PDF in a new window or tab.
  window.open('pos_checkout.php?<?php echo "ptid=$patient_id&enc=$encounter&pdf=1&coid="; ?>' + encodeURIComponent(checkout_id),
   '_blank', 'width=750,height=550,resizable=1,scrollbars=1');
<?php } else { ?>
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  window.print();
<?php } ?>
  return false;
 }

 // Process click on Delete button.
 function deleteme() {

  // TBD: Make sure deleter.php is still doing the right thing.

  dlgopen('deleter.php?billing=<?php echo "$patient_id.$encounter"; ?>', '_blank', 500, 450);
  return false;
 }

 // Called by the deleteme.php window on a successful delete.
 function imdeleted() {
  window.close();
 }

 /*********************************************************************
 // Process click on a void option.
 function voidme(action) {
  if ((action == 'void' || action == 'voidall') &&
   !confirm('<?php echo xl('This will advance the receipt number. Please print the receipt if you have not already done so.'); ?>')) {
   return false;
  }
  top.restoreSession();
  document.location.href = 'pos_checkout.php?ptid=<?php echo $patient_id; ?>&' + action +
    '=<?php echo $encounter; ?>' + '&form_checksum=<?php echo $current_checksum; ?>' +
    '<?php if (!empty($_GET['framed'])) echo '&framed=1'; ?>';
  return false;
 }
 *********************************************************************/

 var voidaction  = ''; // saves action argument from voidme()

 // Submit the form to complete a void operation.
 function voidwrap(form_reason, form_notes) {
  top.restoreSession();
  document.location.href = 'pos_checkout.php?ptid=<?php echo $patient_id; ?>&' + voidaction +
    '=<?php echo $encounter; ?>' + '&form_checksum=<?php echo $current_checksum; ?>' +
    '&form_reason=' + encodeURIComponent(form_reason) +
    '&form_notes='  + encodeURIComponent(form_notes) +
    '<?php if (!empty($_GET['framed'])) echo '&framed=1'; ?>';
  return false;
 }

 // Process click on a void option.
 // action can be 'regen', 'void' or 'voidall'.
 function voidme(action) {
  voidaction = action;
  if (action == 'void' || action == 'voidall') {
   if (!confirm('<?php echo xl('This will advance the receipt number. Please print the receipt if you have not already done so.'); ?>')) {
     return false;
   }
   dlgopen('void_dialog.php', '_blank', 500, 450);
   return false;
  }
  // TBD: Better defaults for void reason and notes.
  voidwrap('', '');
  return false;
 }

</script>
</head>
<body class="body_top">
<center>

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
   <b><?php echo $frow['name'] ?>
   <br><?php echo $frow['street'] ?>
   <br><?php
  echo $frow['city'] . ", ";
  echo $frow['state'] . " ";
  echo $frow['postal_code']; ?>
   <br><?php echo $frow['phone']; ?>
  </td>
  <td width='25%' align='right' valign='top'>
   <!-- This space available. -->
   &nbsp;
  </td>
 </tr>
</table>

<p><b>
<?php
  echo xl("Client Receipt"); // . '<br />' . dateformat();
  if ($invoice_refno) echo " " . xl("for Invoice") . " $invoice_refno";
?>
<br>&nbsp;
</b></p>

<?php
  // Compute numbers for summary on right side of page.
  $head_begbal = get_patient_balance_excluding($patient_id, $encounter);
  $row = sqlQuery("SELECT SUM(fee) AS amount FROM billing WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter' AND activity = 1 AND " .
    "code_type != 'COPAY'");
  $head_charges = $row['amount'];
  $row = sqlQuery("SELECT SUM(fee) AS amount FROM drug_sales WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter'");
  $head_charges += $row['amount'];
  $row = sqlQuery("SELECT SUM(pay_amount) AS payments, " .
    "SUM(adj_amount) AS adjustments FROM ar_activity WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter' AND deleted IS NULL");
  $head_adjustments = $row['adjustments'];
  $head_payments = $row['payments'];
  $row = sqlQuery("SELECT SUM(fee) AS amount FROM billing WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter' AND activity = 1 AND " .
    "code_type = 'COPAY'");
  $head_payments -= $row['amount'];
  $head_endbal = $head_begbal + $head_charges - $head_adjustments - $head_payments;
?>
<table width='95%'>
 <tr>
  <td width='50%' align='left' valign='top'>
   <?php echo $patdata['fname'] . ' ' . $patdata['mname'] . ' ' . $patdata['lname']; ?>
   <br><?php echo $patdata['street']; ?>
   <br><?php
  echo generate_layout_display_field('DEM', 'city' , $patdata['city']) . ", ";
  echo generate_layout_display_field('DEM', 'state', $patdata['state']) . " ";
  echo $patdata['postal_code']; ?>
  </td>
  <td width='50%' align='right' valign='top'>
   <table>
    <tr>
     <td><?php xl('Beginning Account Balance','e'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td align='right'><?php echo oeFormatMoney($head_begbal); ?></td>
    </tr>
    <tr>
     <td><?php xl('Total Visit Charges','e'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td align='right'><?php echo oeFormatMoney($head_charges); ?></td>
    </tr>
    <tr>
     <td><?php xl('Adjustments','e'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td align='right'><?php echo oeFormatMoney($head_adjustments); ?></td>
    </tr>
    <tr>
     <td><?php xl('Payments','e'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td align='right'><?php echo oeFormatMoney($head_payments); ?></td>
    </tr>
    <tr>
     <td><?php xl('Ending Account Balance','e'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td align='right'><?php echo oeFormatMoney($head_endbal); ?></td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<table cellpadding='2' width='95%'>
<?php if ($details) { ?>

 <tr>
  <td colspan='<?php echo 6 + $num_optional_columns; ?>'
   style='padding-top:5pt;'>
    <b><?php echo xl('Charges for') . ' ' . oeFormatShortDate($svcdate); ?></b>
  </td>
 </tr>

 <tr>
  <td><b><?php echo xlt('Date'); ?></b></td>
  <td><b><?php xl('Code','e'); ?></b></td>
  <td><b><?php xl('Description','e'); ?></b></td>
  <td align='center'><b><?php echo $details ? xl('Qty'  ) : '&nbsp;'; ?></b></td>
  <td align='right'><b><?php echo $details ? xl('Price') : '&nbsp;'; ?></b></td>
<?php if (!empty($GLOBALS['gbl_checkout_charges'])) { ?>
  <td align='right'><b><?php xl('Charge','e'); ?></b></td>
<?php } ?>
<?php
  foreach ($aTaxNames as $taxname) {
    echo "  <td align='right'><b>" . text($taxname) . "</b></td>\n";
  }
?>
<?php if (!empty($GLOBALS['gbl_charge_categories'])) { ?>
  <td align='right'><b><?php echo xlt('Customer'); ?></b></td>
<?php } ?>
<?php if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) { ?>
  <td align='right'><b><?php xl('Type','e'); ?></b></td>
  <td align='right'><b><?php xl('Adj','e'); ?></b></td>
<?php } ?>
  <td align='right'><b><?php xl('Total','e'); ?></b></td>
 </tr>
<?php } // end if details ?>

 <tr>
  <td colspan='<?php echo 6 + $num_optional_columns; ?>'
   style='border-top:1px solid black; font-size:1px; padding:0;'>
    &nbsp;
  </td>
 </tr>

<?php
  // Create array aAdjusts from ar_activity rows for $encounter.
  load_adjustments($patient_id, $encounter);

  $aTotals = array(0, 0, 0, 0, 0);
  for ($i = 0; $i < count($aTaxNames); ++$i) $aTotals[5 + $i] = 0;

  // Product sales
  $inres = sqlStatement("SELECT s.sale_id, s.sale_date, s.fee, " .
    "s.quantity, s.drug_id, s.billed, s.bill_date, s.selector, d.name, lo.title " .
    "FROM drug_sales AS s " .
    "LEFT JOIN drugs AS d ON d.drug_id = s.drug_id " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'chargecats' and lo.option_id = s.chargecat AND lo.activity = 1 " .
    "WHERE s.pid = '$patient_id' AND s.encounter = '$encounter' " .
    "ORDER BY s.sale_id");
  while ($inrow = sqlFetchArray($inres)) {
    $billtime = $inrow['billed'] ? $inrow['bill_date'] : '';
    $tmpname = $inrow['name'];
    if ($tmpname !== $inrow['selector']) $tmpname .= ' / ' . $inrow['selector'];
    $units = $inrow['quantity'] / FeeSheet::getBasicUnits($inrow['drug_id'], $inrow['selector']);
    receiptDetailLine('PROD', $inrow['drug_id'], $tmpname,
      $units, $inrow['fee'], $aTotals, 'P:' . $inrow['sale_id'], $billtime, $svcdate, $inrow['title']);
  }

  // Service items.
  $inres = sqlStatement("SELECT * FROM billing AS b " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'chargecats' and lo.option_id = b.chargecat AND lo.activity = 1 " .
    "WHERE " .
    "b.pid = '$patient_id' AND b.encounter = '$encounter' AND " .
    "b.code_type != 'COPAY' AND b.code_type != 'TAX' AND b.activity = 1 " .
    "ORDER BY b.id");
  while ($inrow = sqlFetchArray($inres)) {
    // Write the line item if it allows fees or is not a diagnosis.
    if (!empty($code_types[$inrow['code_type']]['fee']) || empty($code_types[$inrow['code_type']]['diag'])) {
      $billtime = $inrow['billed'] ? $inrow['bill_date'] : '';
      receiptDetailLine($inrow['code_type'], $inrow['code'], $inrow['code_text'],
        $inrow['units'], $inrow['fee'], $aTotals, 'S:' . $inrow['id'], $billtime, $svcdate, $inrow['title']);
    }
  }

  // Write any adjustments left in the aAdjusts array.
  foreach ($aAdjusts as $arow) {
    if ($arow['adj_amount'] == 0 && $arow['memotitle'] == '') continue;
    $payer = empty($arow['payer_type']) ? 'Pt' : ('Ins' . $arow['payer_type']);
    receiptDetailLine('', "$payer|" . $arow['code_type'] . "|" . $arow['code'], $arow['memotitle'], 1,
      0 - $arow['adj_amount'], $aTotals, '', $arow['post_time'], $arow['post_date']);
  }
?>

 <tr>
  <td colspan='<?php echo 6 + $num_optional_columns; ?>'
   style='border-top:1px solid black; font-size:1px; padding:0;'>
    &nbsp;
  </td>
 </tr>

<?php
  // Sub-Total line with totals of all numeric columns.
  if ($details) {
    echo " <tr>\n";
    echo "  <td colspan='3' align='right'><b>" . xl('Sub-Total') . "</b></td>\n";
    echo "  <td align='center'>" . $aTotals[0] . "</td>\n";
    echo "  <td align='right'>" . oeFormatMoney($aTotals[1]) . "</td>\n";
    // Optional charge amount.
    if (!empty($GLOBALS['gbl_checkout_charges'])) {
      echo "  <td align='right'>" . oeFormatMoney($aTotals[2]) . "</td>\n";
    }
    // Put tax columns, if any, in the subtotals.
    for ($i = 0; $i < count($aTaxNames); ++$i) {
      echo "  <td align='right'>" . oeFormatMoney($aTotals[5 + $i]) . "</td>\n";
    }
    // Optional charge category empty column.
    if (!empty($GLOBALS['gbl_charge_categories'])) {
      echo "  <td align='right'>&nbsp;</td>\n";
    }
    // Optional adjustment columns.
    if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) {
      echo "  <td align='right'>&nbsp;</td>\n";
      echo "  <td align='right'>" . oeFormatMoney($aTotals[3]) . "</td>\n";
    }
    echo "  <td align='right'>" . oeFormatMoney($aTotals[4]) . "</td>\n";
    echo " </tr>\n";
  }

  // Write a line for each tax item that did not match.
  // Should only happen for old invoices before taxes were assigned to line items.
  foreach ($aInvTaxes as $taxid => $taxarr) {
    foreach ($taxarr as $taxlineid => $tax) {
      if ($tax) {
        receiptDetailLine('TAX', $taxid, $aTaxNames[$taxid], 1, $tax, $aTotals);
        $aInvTaxes[$taxid][$taxlineid] = 0;
      }
    }
  }

  /*******************************************************************
  // Tax items.
  $inres = sqlStatement("SELECT * FROM billing WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter' AND " .
    "code_type = 'TAX' AND activity = 1 " .
    "ORDER BY id");
  while ($inrow = sqlFetchArray($inres)) {
    receiptDetailLine($inrow['code_type'], $inrow['code'], $inrow['code_text'],
      1, $inrow['fee'], $aTotals);
  }
  *******************************************************************/

  // Total Charges line.
  echo " <tr>\n";
  echo "  <td colspan='" . (3 + $num_optional_columns) . "'>&nbsp;</td>\n";
  echo "  <td colspan='" . 2 .
       "' align='right'><b>" . xl('Total Charges') . "</b></td>\n";
  echo "  <td align='right'>" . oeFormatMoney($aTotals[4]) . "</td>\n";
  echo " </tr>\n";
?>

<!--
</table>
<table cellpadding='2' width='95%'>
-->

 <tr>
  <td colspan='<?php echo 6 + $num_optional_columns; ?>' style='padding-top:5pt;'>
    <b><?php echo xl('Payments'); ?></b>
  </td>
 </tr>

 <tr>
  <td><b><?php echo xlt('Date'); ?></b></td>
  <td colspan='2'><b><?php echo xlt('Checkout Receipt Ref'); ?></b></td>
  <td colspan="<?php echo $rcpt_num_method_columns; ?>"
   align='left'><b><?php echo xlt('Payment Method'); ?></b></td>
  <td colspan="<?php echo $rcpt_num_ref_columns; ?>"
   align='left'><b><?php echo xlt('Ref No'); ?></b></td>
  <td colspan='<?php echo $rcpt_num_amount_columns; ?>'
   align='right'><b><?php echo xlt('Amount'); ?></b></td>
 </tr>

 <tr>
  <td colspan='<?php echo 6 + $num_optional_columns; ?>'
   style='border-top:1px solid black; font-size:1px; padding:0;'>
    &nbsp;
  </td>
 </tr>

<?php
  $payments = 0;

  // Get co-pays.
  $inres = sqlStatement("SELECT fee, code_text FROM billing WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter' AND " .
    "code_type = 'COPAY' AND activity = 1 AND fee != 0 " .
    "ORDER BY id");
  while ($inrow = sqlFetchArray($inres)) {
    $payments -= formatMoneyNumber($inrow['fee']);
    receiptPaymentLine($svcdate, 0 - $inrow['fee'], $inrow['code_text'], 'COPAY');
  }

  // Get other payments.
  $inres = sqlStatement("SELECT " .
    "a.code, a.modifier, a.memo, a.payer_type, a.adj_amount, a.pay_amount, " .
    "a.post_time, IFNULL(a.post_date, a.post_time) AS post_date, " .
    "s.payer_id, s.reference, s.check_date, s.deposit_date " .
    "FROM ar_activity AS a " .
    "LEFT JOIN ar_session AS s ON s.session_id = a.session_id WHERE " .
    "a.pid = '$patient_id' AND a.encounter = '$encounter' AND a.deleted IS NULL AND " .
    "a.pay_amount != 0 " .
    "ORDER BY s.check_date, a.sequence_no");
  $payer = empty($inrow['payer_type']) ? 'Pt' : ('Ins' . $inrow['payer_type']);
  while ($inrow = sqlFetchArray($inres)) {
    $payments += formatMoneyNumber($inrow['pay_amount']);
    // Compute invoice number with payment suffix.
    $tmp = array_search($inrow['post_time'], $checkout_times);
    $tmp = $tmp === FALSE ? 0 : ($tmp + 1);
    $refno = $invoice_refno ? "$invoice_refno-$tmp" : "$encounter-$tmp";
    receiptPaymentLine($inrow['post_date'], $inrow['pay_amount'],
      trim($payer . ' ' . $inrow['reference']), $inrow['memo'], $refno, $inrow['post_time']);
  }
?>

 <tr>
  <td colspan='<?php echo 6 + $num_optional_columns; ?>'
   style='border-top:1px solid black; font-size:1px; padding:0;'>
    &nbsp;
  </td>
 </tr>

 <tr>
  <td colspan='<?php echo 3 + $num_optional_columns; ?>'>&nbsp;</td>
  <td colspan='2' align='right'><b><?php xl('Total Payments','e'); ?></b></td>
  <td align='right'><?php echo str_replace(' ', '&nbsp;', oeFormatMoney($payments, true)); ?></td>
 </tr>

</table>
</center>

<?php
  // The user-customizable note.
  if (!empty($GLOBALS['gbl_checkout_receipt_note'])) {
    echo "<p>";
    echo str_repeat('*', 80) . '<br />';
    echo '&nbsp;&nbsp;' . text($GLOBALS['gbl_checkout_receipt_note']) . '<br />';
    echo str_repeat('*', 80) . '<br />';
    echo "</p>";
  }
?>

<p>
<b><?php echo xl("Printed on") . ' ' . dateformat(); ?></b>
</p>

<div id='hideonprint'>
<p>
&nbsp;

<?php
  if (count($checkout_times) > 1 && !empty($GLOBALS['gbl_custom_receipt'])) {
    // Multiple checkouts so allow selection of the one to print.
    // This is only applicable for custom checkout receipts.
    echo "<select onchange='printme(this.value)' >\n";
    echo " <option value=''>" . xlt('Print Checkout') . "</option>\n";
    $i = 0;
    foreach ($checkout_times AS $tmp) {
      ++$i;
      echo " <option value='$tmp'>$i: $tmp</option>\n";
    }
    echo "</select>\n";
  }
  else {
    echo "<a href='#' onclick='return printme(\"\");'>" . xlt('Print') . "</a>\n";
  }
?>

<?php if (acl_check('acct','disc')) { ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href='#' onclick='return voidme("regen");'><?php echo xl('Generate New Receipt Number'); ?></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href='#' onclick='return voidme("void");' title='<?php echo xla('Applies to this visit only'); ?>'>
 <?php echo xlt('Void Last Checkout'); ?></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href='#' onclick='return voidme("voidall");' title='<?php echo xla('Applies to this visit only'); ?>'>
 <?php echo xl('Void All Checkouts'); ?></a>
<?php } ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php if ($details) { ?>
<a href='pos_checkout.php?details=0&ptid=<?php echo $patient_id; ?>&enc=<?php echo attr($encounter); ?>' onclick='top.restoreSession()'><?php xl('Hide Details','e'); ?></a>
<?php } else { ?>
<a href='pos_checkout.php?details=1&ptid=<?php echo $patient_id; ?>&enc=<?php echo attr($encounter); ?>' onclick='top.restoreSession()'><?php xl('Show Details','e'); ?></a>
<?php } ?>
</p>
</div>

<script language="JavaScript">
 top.restoreSession();

<?php if ($rapid_data_entry && $GLOBALS['concurrent_layout']) { ?>
 if (parent.left_nav) {
  parent.left_nav.setRadio('RTop', 'new');
  parent.left_nav.loadFrame('new1', 'RTop', 'new/new.php');
 }
<?php } ?>

<?php
if ($alertmsg) {
  echo " alert('" . addslashes($alertmsg) . "');\n";
}
?>

<?php if (!empty($GLOBALS['gbl_custom_receipt'])) { ?>
 // Custom checkout receipt needs to be sent as a PDF in a new window or tab.
 // printme(''); // No longer printing by default as of 2016-10-20.
<?php } ?>

</script>

</body>
</html>
<?php

}
// end function generate_receipt()
//
//////////////////////////////////////////////////////////////////////

// Function to write the heading lines for the data entry form.
// This is deferred because we need to know which encounter was chosen.
//
$form_headers_written = false;
function write_form_headers() {
  global $form_headers_written, $patdata, $patient_id, $encounter_id, $aAdjusts;
  global $taxes, $encounter_date, $num_optional_columns;

  if ($form_headers_written) return;
  $form_headers_written = true;

  // Create arrays $aAdjusts, $aTaxNames and $aInvTaxes for this encounter.
  load_adjustments($patient_id, $encounter_id);
  // This also initializes $num_optional_columns and related colspan values.
  load_taxes($patient_id, $encounter_id);

  $ferow = sqlQuery("SELECT date FROM form_encounter " .
    "WHERE pid = '$patient_id' AND encounter = '$encounter_id'");
  $encounter_date = substr($ferow['date'], 0, 10);
?>
 <tr>
  <td colspan='<?php echo 5 + $num_optional_columns; ?>' align='center' class='title'>
   <?php xl('Patient Checkout for ','e'); ?><?php echo $patdata['fname'] . " " .
   $patdata['lname'] . " (" . $patdata['pubpid'] . ")" ?>
   <br />&nbsp;
   <p class='bold'>
<?php
  $prvbal = get_patient_balance_excluding($patient_id, $encounter_id);
  echo xl('Previous Balance') . '&nbsp;&nbsp;&nbsp;&nbsp;';
  echo "<input type='text' value='" . oeFormatMoney($prvbal) . "' size='6' ";
  echo "style='text-align:right;background-color:transparent' readonly />\n";
  if ($prvbal > 0) {
    echo "&nbsp;<input type='button' value='" . xl('Pay Previous Balance') .
      "' onclick='payprevious()' />\n";
  }
?>
   <br />&nbsp;
   </p>
  </td>
 </tr>

 <tr>
  <td colspan='<?php echo 4 + (empty($GLOBALS['gbl_checkout_charges']) ? 0 : 1) + count($taxes); ?>' class='bold'>
   &nbsp;
  </td>
<?php if (!empty($GLOBALS['gbl_charge_categories'])) { ?>
  <td align='right' class='bold' nowrap>
   <?php echo xlt('Default Customer'); ?>
  </td>
<?php } ?>
<?php if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) { ?>
  <td align='right' class='bold' nowrap>
   <?php echo xlt('Default Adjust Type'); ?>
  </td>
  <td class='bold'>
   &nbsp;
  </td>
<?php } ?>
  <td class='bold'>
   &nbsp;
  </td>
 </tr>

 <tr>
  <td colspan='<?php echo 4 + (empty($GLOBALS['gbl_checkout_charges']) ? 0 : 1) + count($taxes); ?>'
   class='title'>
   <?php xl('Current Charges','e'); ?>
  </td>
<?php if (!empty($GLOBALS['gbl_charge_categories'])) { // charge category default ?>
  <td align='right' class='bold'>
   <?php echo generate_select_list('form_charge_category', 'chargecats', '', '',
    ' ', '', 'chargeCategoryChanged();'); ?>
  </td>
<?php } ?>
<?php if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) { // adjustmenty reason default ?>
  <td align='right' class='bold'>
   <?php echo generate_select_list('form_discount_type', 'adjreason', '', '',
    ' ', '', 'discountTypeChanged();billingChanged();'); ?>
  </td>
  <td class='bold'>
   &nbsp;
  </td>
<?php } ?>
  <td class='bold'>
   &nbsp;
  </td>
 </tr>

 <tr>
  <td class='bold'><?php xl('Date','e'); ?></td>
  <td class='bold'><?php xl('Description','e'); ?></td>
  <td align='right' class='bold'><?php xl('Quantity','e'); ?></td>
<?php if (empty($GLOBALS['gbl_checkout_charges'])) { // if no charges column ?>
  <td align='right' class='bold'><?php xl('Charge','e'); ?></td>
<?php
  foreach ($taxes as $taxarr) {
    echo "  <td align='right'><b>" . text($taxarr[0]) . "</b></td>";
  }
?>
<?php } else { // charges column needed ?>
  <td align='right' class='bold'><?php xl('Price','e'); ?></td>
  <td align='right' class='bold'><?php xl('Charge','e'); ?></td>
<?php
  foreach ($taxes as $taxarr) {
    echo "  <td align='right' class='bold'>" . text($taxarr[0]) . "</td>";
  }
?>
<?php } ?>
<?php if (!empty($GLOBALS['gbl_charge_categories'])) { // charge category ?>
  <td align='right' class='bold'><?php echo xlt('Customer'); ?></td>
<?php } ?>
<?php if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) { ?>
  <td align='right' class='bold'><?php echo xlt('Adjust Type'); ?></td>
  <td align='right' class='bold'><?php echo xlt('Adj'); ?></td>
<?php } ?>
  <td align='right' class='bold'><?php echo xlt('Total'); ?></td>
 </tr>
<?php
}

// Function to output a line item for the input form.
//
$totalchg = 0; // totals charges after adjustments
function write_form_line($code_type, $code, $id, $date, $description,
  $amount, $units, $taxrates, $billtime='', $chargecat='') {
  global $lino, $totalchg, $aAdjusts, $taxes, $encounter_date;

  // Write heading rows if that is not already done.
  write_form_headers();
  $amount = formatMoneyNumber($amount);
  if (empty($units)) $units = 1;
  $price = formatMoneyNumber($amount / $units, 2); // should be even cents, but...
  if (substr($price, -2) === '00') $price = formatMoneyNumber($price);
  // if ($code_type == 'COPAY' && !$description) $description = xl('Payment');

  // Total and clear adjustments in aAdjusts matching this line item. Should only
  // happen for billed items, and matching includes the billing timestamp in order
  // to handle the case of multiple checkouts.
  $memo = '';
  $adjust = pull_adjustment($code_type, $code, $billtime, $memo);
  $total = formatMoneyNumber($amount - $adjust);
  if (empty($GLOBALS['discount_by_money'])) {
    // Convert $adjust to a percentage of the amount, up to 4 decimal places.
    $adjust = round(100 * $adjust / $amount, 4);
  }

  // Compute the string of numeric tax rates to store with the charge line.
  $taxnumrates = '';
  $arates = explode(':', $taxrates);
  foreach ($taxes as $taxid => $taxarr) {
    $rate = $taxarr[1];
    if (empty($arates) || !in_array($taxid, $arates)) $rate = 0;
    $taxnumrates .= $rate . ':';
  }

  echo " <tr>\n";
  echo "  <td class='text'>" . oeFormatShortDate($encounter_date);
  echo "<input type='hidden' name='line[$lino][code_type]' value='$code_type'>";
  echo "<input type='hidden' name='line[$lino][code]' value='$code'>";
  echo "<input type='hidden' name='line[$lino][id]' value='$id'>";
  echo "<input type='hidden' name='line[$lino][description]' value='$description'>";
  // String of numeric tax rates is written here as a form field only for JavaScript tax computations.
  echo "<input type='hidden' name='line[$lino][taxnumrates]' value='$taxnumrates'>";
  echo "<input type='hidden' name='line[$lino][units]' value='$units'>";
  // Indicator of whether and when this line item was previously billed:
  echo "<input type='hidden' name='line[$lino][billtime]' value='$billtime'>";
  echo "</td>\n";
  echo "  <td class='text'>$description</td>";
  echo "  <td class='text' align='right'>$units</td>";

  if (empty($GLOBALS['gbl_checkout_charges'])) {
    // We show only total charges here.
    echo "  <td class='text' align='right'>";
    echo "<input type='hidden' name='line[$lino][price]' value='$price'>";
    echo "<input type='text' name='line[$lino][charge]' value='$amount' size='6'";
    echo " style='text-align:right;background-color:transparent' readonly />";
    echo "</td>\n";
  }
  else {
    // In this case show price and extended charge amount.
    echo "  <td class='text' align='right'>";
    echo "<input type='text' name='line[$lino][price]' value='$price' size='6'";
    echo " style='text-align:right;background-color:transparent' readonly />";
    echo "</td>\n";
    echo "  <td class='text' align='right'>";
    echo "<input type='text' name='line[$lino][charge]' value='$amount' size='6'";
    echo " style='text-align:right;background-color:transparent' readonly />";
    echo "</td>\n";
  }

  // Match up (and delete) entries in $aInvTaxes with the line.
  $lineid = $code_type == 'PROD' ? "P:$id" : "S:$id";
  $aTaxes = array();
  pull_tax($lineid, $aTaxes); // fills in $aTaxes

  // A tax column for each tax. JavaScript will compute the amounts and
  // account for how the discount affects them.
  $i = 0;
  foreach ($taxes as $taxid => $dummy) {
    echo "  <td class='text' align='right'>";
    echo "<input type='text' name='line[$lino][tax][$i]' size='6'";
    // Set tax amounts for existing billed items. JS must not recompute those.
    echo " value='" . formatMoneyNumber($aTaxes[$taxid]) . "'";
    echo " style='text-align:right;background-color:transparent' readonly />";
    echo "</td>\n";
    ++$i;
  }

  // Optional Charge Category.
  if (!empty($GLOBALS['gbl_charge_categories'])) {
    echo "  <td class='text' align='right'>";
    echo generate_select_list("line[$lino][chargecat]", 'chargecats', $chargecat, '', ' ', '',
      '', '', $billtime ? array('disabled' => 'disabled') : null);
    echo "</td>\n";
  }

  if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) {
    echo "  <td class='text' align='right'>";
    echo generate_select_list("line[$lino][memo]", 'adjreason', $memo, '', ' ', '',
      'billingChanged()', '', $billtime ? array('disabled' => 'disabled') : null);
    echo "</td>\n";
    echo "  <td class='text' align='right' nowrap>";
    echo empty($GLOBALS['discount_by_money']) ? '' : $GLOBALS['gbl_currency_symbol'];
    echo "<input type='text' name='line[$lino][adjust]' size='6'";
    echo " value='" . formatMoneyNumber($adjust) . "'";
    // Modifying discount requires the acct/disc permission.
    if ($billtime || $code_type == 'TAX' || $code_type == 'COPAY' || !acl_check('acct','disc'))
      echo " style='text-align:right;background-color:transparent' readonly";
    else
      echo " style='text-align:right' maxlength='8' onkeyup='lineDiscountChanged($lino)'";
    echo " /> ";
    echo empty($GLOBALS['discount_by_money']) ? '%' : '';
    echo "</td>\n";
  }

  // Extended amount after adjustments and taxes.
  echo "  <td class='text' align='right'>";
  echo "<input type='text' name='line[$lino][amount]' value='$total' size='6'";
  echo " style='text-align:right;background-color:transparent' readonly />";
  echo "</td>\n";

  echo " </tr>\n";
  ++$lino;
  $totalchg += $amount;
}

// Function to output a past payment/adjustment line to the form.
//
function write_old_payment_line($pay_type, $date, $method, $reference, $amount) {
  global $lino, $taxes, $num_optional_columns;
  global $form_num_type_columns, $form_num_method_columns, $form_num_ref_columns, $form_num_amount_columns;
  // Write heading rows if that is not already done.
  write_form_headers();
  $amount = formatMoneyNumber($amount);
  echo " <tr>\n";
  // if ($GLOBALS['gbl_checkout_line_adjustments']) {
  //   echo "  <td>&nbsp;</td>\n";
  // }
  echo "  <td class='text' colspan='$form_num_type_columns'>" . text($pay_type ) . "</td>\n";
  echo "  <td class='text' colspan='$form_num_method_columns'>" . text($method) . "</td>\n";
  echo "  <td class='text' colspan='$form_num_ref_columns'>" . text($reference) . "</td>\n";
  echo "  <td class='text' align='right' colspan='$form_num_amount_columns'><input type='text' name='oldpay[$lino][amount]' " .
       "value='$amount' size='6' maxlength='8'";
  echo " style='text-align:right;background-color:transparent' readonly";
  echo "></td>\n";
  echo " </tr>\n";
  ++$lino;
}

// Mark the tax rates that are referenced in this invoice.
function markTaxes($taxrates) {
  global $taxes;
  $arates = explode(':', $taxrates);
  if (empty($arates)) return;
  foreach ($arates as $value) {
    if (!empty($taxes[$value])) $taxes[$value][2] = '1';
  }
}

// Create the taxes array.  Key is tax id, value is
// (description, rate, indicator).  Indicator seems to be unused.
$taxes = array();
$pres = sqlStatement("SELECT option_id, title, option_value " .
  "FROM list_options WHERE list_id = 'taxrate' AND activity = 1 ORDER BY seq, title, option_id");
while ($prow = sqlFetchArray($pres)) {
  $taxes[$prow['option_id']] = array($prow['title'], $prow['option_value'], 0);
}

// Array of HTML for the 4 or 5 cells of an input payment row.
// "%d" will be replaced by a payment line number on the client side.
//
$aCellHTML = array();
// if ($GLOBALS['gbl_checkout_line_adjustments']) {
//   $aCellHTML[] = "&nbsp;";
// }
$aCellHTML[] = "<span id='paytitle_%d'>" . text(xl('New Payment')) . "</span>";
$aCellHTML[] = strtr(generate_select_list('payment[%d][method]', 'paymethod', '', '', ''), array("\n" => ""));
$aCellHTML[] = "<input type='text' name='payment[%d][refno]' size='10' />";
$aCellHTML[] = "<input type='text' name='payment[%d][amount]' size='6' style='text-align:right' onkeyup='setComputedValues()' />";

$alertmsg = ''; // anything here pops up in an alert box

// Make sure we have the encounter ID applicable to this request.
if (!empty($_POST['form_save'])) {
  $patient_id = 0 + $_POST['form_pid'];
  $encounter_id = 0 + $_POST['form_encounter'];
}
else {
  foreach(array('regen', 'enc', 'void', 'voidall') as $key) {
    if (!empty($_GET[$key])) {
      $encounter_id = 0 + $_GET[$key];
      break;
    }
  }
}

// Compute and validate the checksum.
$current_checksum = 0;
if ($patient_id && $encounter_id) {
  $current_checksum = invoiceChecksum($patient_id, $encounter_id);
  if (!empty($_REQUEST['form_checksum'])) {
    if ($_REQUEST['form_checksum'] != $current_checksum) {
      $alertmsg = xl('Someone else has just changed this visit. Please cancel this page and try again.');
    }
  }
}

// If the Save button was clicked...
//
if ($_POST['form_save'] && !$alertmsg) {

  // On a save, do the following:
  // Flag this form's drug_sales and billing items as billed.
  // Post line-level adjustments, replacing any existing ones for the same charges.
  // Post any invoice-level adjustment.
  // Post payments and be careful to use a unique invoice number.
  // Call the generate-receipt function.
  // Exit.

  // A current invoice reference number may be present if there was a previous checkout.
  $tmprow = sqlQuery("SELECT invoice_refno FROM form_encounter WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter_id'");
  $current_irnumber = $tmprow['invoice_refno'];

  // Get the posting date from the form as yyyy-mm-dd.
  $postdate = substr($this_bill_date, 0, 10);
  if (preg_match("/(\d\d\d\d)\D*(\d\d)\D*(\d\d)/", $_POST['form_date'], $matches)) {
    $postdate = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
  }
  $dosdate = $postdate; // not sure if this is appropriate

  if (! $encounter_id) {
    die("Internal error: Encounter ID is missing!");
  }

  // Delete unbilled TAX rows from billing because they will be recalculated.
  // Do not delete already-billed taxes; we must not touch billed stuff.
  sqlStatement("UPDATE billing SET activity = 0 WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter_id' AND " .
    "code_type = 'TAX' AND billed = 0 AND activity = 1");

  /********************************************************************
  // Clear any existing nonzero adjustments for this encounter.  Normally
  // there should not be any because checkout should only happen once.
  sqlStatement("UPDATE ar_activity SET adj_amount = 0, memo = '' WHERE " .
    "pid = '$form_pid' AND encounter = '$form_encounter' AND " .
    "adj_amount != 0");
  ********************************************************************/
  // The above removed 10/2015 as we now support multiple checkouts.

  $form_amount = $_POST['form_amount'];
  $lines = $_POST['line'];

  for ($lino = 0; $lines[$lino]['code_type']; ++$lino) {
    $line = $lines[$lino];
    $code_type = $line['code_type'];
    $code      = $line['code'];
    $id        = $line['id'];
    $chargecat = $line['chargecat'];
    $amount    = formatMoneyNumber(trim($line['amount']));
    $linetax   = 0;

    // Skip saving taxes and adjustments for billed items.
    if (!empty($line['billtime'])) continue;

    // Insert any taxes for this line.
    // There's a chance of input data and the $taxes array being out of sync if someone
    // updates the taxrate list during data entry... we oughta do something about that.
    if (is_array($line['tax'])) {
      // For tax rows the ndc_info field is used to identify the charge item that is taxed.
      // P indicates drug_sales.sale_id, S indicates billing.id.
      $ndc_info = $code_type == 'PROD' ? "P:$id" : "S:$id";
      $i = 0;
      foreach ($taxes as $taxid => $taxarr) {
        $taxamount = $line['tax'][$i++] + 0;
        if ($taxamount != 0) {
          addBilling($encounter_id, 'TAX', $taxid, $taxarr[0], $patient_id, 0, 0,
            '', '', $taxamount, $ndc_info, '', 0);
          // billed=0 because we will set billed and bill_date for unbilled items below.
          $linetax += $taxamount;
        }
      }
    }

    // If there is an adjustment for this line, insert it.
    if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) {
      /***************************************************************
      $charge = sprintf('%01.2f', trim($line['charge']));
      $adjust = sprintf('%01.2f', $charge + $linetax - $amount);
      ***************************************************************/
      $adjust = 0.00 + trim($line['adjust']);
      $memo = formDataCore($line['memo']);
      if ($adjust != 0 || $memo !== '') {
        // $memo = xl('Discount');
        if ($memo === '') $memo = formData('form_discount_type');
        $query = "INSERT INTO ar_activity ( " .
          "pid, encounter, code_type, code, modifier, payer_type, " .
          "post_user, post_time, post_date, session_id, memo, adj_amount " .
          ") VALUES ( " .
          "'$patient_id', " .
          "'$encounter_id', " .
          "'$code_type', " .
          "'$code', " .
          "'', " .
          "'0', " .
          "'" . $_SESSION['authUserID'] . "', " .
          "'$this_bill_date', " .
          "'$postdate', " .
          "'0', " .
          "'$memo', " .
          "'$adjust' " .
          ")";
        sqlStatement($query);
      }
    }

    /*****************************************************************
    if ($code_type == 'PROD') {
      // Product sales. The fee and encounter ID may have changed.
      $query = "update drug_sales SET fee = '$amount', " .
      "encounter = '$form_encounter', billed = 1 WHERE " .
      "sale_id = '$id'";
      sqlQuery($query);
    }
    else
    if ($code_type == 'TAX') {
      // We must save taxes somewhere, and in the billing table with
      // a code type of TAX seems easiest.
      // They will have to be stripped back out when building this
      // script's input form.
      addBilling($form_encounter, 'TAX', 'TAX', 'Taxes', $form_pid, 0, 0,
        '', '', $amount, '', '', 1);
    }
    else {
      // Because there is no insurance here, there is no need for a claims
      // table entry and so we do not call updateClaim().  Note we should not
      // eliminate billed and bill_date from the billing table!
      $query = "UPDATE billing SET fee = '$amount', billed = 1, " .
      "bill_date = NOW() WHERE id = '$id'";
      sqlQuery($query);
    }
    *****************************************************************/

    if (!empty($GLOBALS['gbl_charge_categories'])) {
      // Update charge category for this line item.
      if ($code_type == 'PROD') {
        $query = "UPDATE drug_sales SET chargecat = ? WHERE sale_id = ?";
        sqlQuery($query, array($chargecat, $id));
      }
      else {
        $query = "UPDATE billing SET chargecat = ? WHERE id = ?";
        sqlQuery($query, array($chargecat, $id));
      }
    }
  }

  // Flag the encounter as billed.
  $query = "UPDATE billing SET billed = 1, bill_date = '$this_bill_date' WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter_id' AND activity = 1 AND billed = 0";
  sqlQuery($query);
  $query = "update drug_sales SET billed = 1, bill_date = '$this_bill_date' WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter_id' AND billed = 0";
  sqlQuery($query);

  // Post discount.
  if ($_POST['form_discount'] != 0) {
    if ($GLOBALS['discount_by_money']) {
      $amount  = formatMoneyNumber(trim($_POST['form_discount']));
    }
    else {
      $amount  = formatMoneyNumber(trim($_POST['form_discount']) * $form_amount / 100);
    }
    // $memo = xl('Discount');
    $memo = formData('form_discount_type');
    $query = "INSERT INTO ar_activity ( " .
      "pid, encounter, code, modifier, payer_type, post_user, post_time, " .
      "post_date, session_id, memo, adj_amount " .
      ") VALUES ( " .
      "'$patient_id', " .
      "'$encounter_id', " .
      "'', " .
      "'', " .
      "'0', " .
      "'" . $_SESSION['authUserID'] . "', " .
      "'$this_bill_date', " .
      "'$postdate', " .
      "'0', " .
      "'$memo', " .
      "'$amount' " .
      ")";
    sqlStatement($query);
  }

  // Post the payments.
  if (is_array($_POST['payment'])) {
    $lines = $_POST['payment'];
    for ($lino = 0; isset($lines[$lino]['amount']); ++$lino) {
      $line = $lines[$lino];
      $amount = formatMoneyNumber(trim($line['amount']));
      if ($amount != 0.00) {
        $method = $line['method'];
        $refno  = $line['refno'];
        if ($method !== '' && $refno !== '') $method .= " $refno";
        $session_id = 0; // Is this OK?
        arPostPayment($patient_id, $encounter_id, $session_id, $amount, '', 0, $method, 0, $this_bill_date,'', $postdate);
      }
    }
  }

  // If applicable, set the invoice reference number.
  if (!$current_irnumber) {
    $invoice_refno = '';
    if (isset($_POST['form_irnumber'])) {
      $invoice_refno = formData('form_irnumber', 'P', true);
    }
    else {
      $invoice_refno = add_escape_custom(updateInvoiceRefNumber());
    }
    if ($invoice_refno) {
      sqlStatement("UPDATE form_encounter " .
        "SET invoice_refno = '$invoice_refno' " .
        "WHERE pid = '$patient_id' AND encounter = '$encounter_id'");
    }
  }

  // If appropriate, update the status of the related appointment to
  // "Checked out".
  updateAppointmentStatus($patient_id, $dosdate, '>');

  generate_receipt($patient_id, $encounter_id);
  exit();
}

// Void attributes.
$form_reason = empty($_GET['form_reason']) ? '' : $_GET['form_reason'];
$form_notes  = empty($_GET['form_notes' ]) ? '' : $_GET['form_notes'];

// If "regen" encounter ID was given, then we must generate a new receipt ID.
//
if (!$alertmsg && $patient_id && !empty($_GET['regen'])) {
  doVoid($patient_id, $encounter_id, false, '', $form_reason, $form_notes);
  $current_checksum = invoiceChecksum($patient_id, $encounter_id);
  $_GET['enc'] = $encounter_id;
}

// If "enc" encounter ID was given, then we must generate a receipt and exit.
//
if ($patient_id && !empty($_GET['enc'])) {
  if (empty($_GET['pdf'])) {
    generate_receipt($patient_id, $_GET['enc']);
  }
  else {
    // PDF receipt is requested. In this case we are probably in a new window.
    require_once($GLOBALS['OE_SITE_DIR'] . "/" . $GLOBALS['gbl_custom_receipt']);
    // $checkout_id is an optional specified checkout timestamp.
    $billtime = $checkout_id;
    if (!$billtime) {
      // No timestamp specified so use the last one.
      $checkout_times = craGetTimestamps($patient_id, $_GET['enc']);
      $billtime = empty($checkout_times) ? '' : $checkout_times[count($checkout_times) - 1];
    }
    generateCheckoutReceipt($patient_id, $_GET['enc'], $billtime);
  }
  exit();
}

// If "void" encounter ID was given, then we must undo the last checkout.
// Or for "voidall" undo all checkouts for the encounter.
//
if (!$alertmsg && $patient_id && !empty($_GET['void'])) {
  doVoid($patient_id, $encounter_id, true, '', $form_reason, $form_notes);
  $current_checksum = invoiceChecksum($patient_id, $encounter_id);
}
else if (!$alertmsg && $patient_id && !empty($_GET['voidall'])) {
  doVoid($patient_id, $encounter_id, true, 'all', $form_reason, $form_notes);
  $current_checksum = invoiceChecksum($patient_id, $encounter_id);
}

// Get the specified or first unbilled encounter ID for this patient.
//
if (!$encounter_id) {
  $query = "SELECT encounter FROM billing WHERE " .
    "pid = ? AND activity = 1 AND billed = 0 AND code_type != 'TAX' " .
    "ORDER BY encounter DESC LIMIT 1";
  $brow = sqlQuery($query, array($patient_id));
  $query = "SELECT encounter FROM drug_sales WHERE " .
    "pid = ? AND billed = 0 " .
    "ORDER BY encounter DESC LIMIT 1";
  $drow = sqlQuery($query, array($patient_id));
  if (!empty($brow['encounter'])) {
    if (!empty($drow['encounter'])) {
      $encounter_id = min(intval($brow['encounter']), intval($drow['encounter']));
    }
    else {
      $encounter_id = $brow['encounter'];
    }
  }
  else if (!empty($drow['encounter'])) {
    $encounter_id = $drow['encounter'];
  }
}

// If there are none, just redisplay the last receipt and exit.
//
if (!$encounter_id) {
  generate_receipt($patient_id);
  exit();
}

// Form requires billing permission.
if (!acl_check('admin', 'super') && !acl_check('acct', 'bill')) {
  die("Not authorized!");
}

// We have $patient_id and $encounter_id. Generate checksum if not already done.
if (!$current_checksum) $current_checksum = invoiceChecksum($patient_id, $encounter_id);

// Get the valid practitioners, including those not active.
$arr_users = array();
$ures = sqlStatement("SELECT id, username FROM users WHERE " .
  "( authorized = 1 OR info LIKE '%provider%' ) AND username != ''");
while ($urow = sqlFetchArray($ures)) {
  $arr_users[$urow['id']] = '1';
}

/**********************************************************************
// Determine if this site uses adjustment reasons to indicate if insurance applies
// to a charge item.  Currently this is specific to some but not all IPPF sites.
$tmp = sqlQuery("SELECT option_id FROM list_options WHERE " .
  "list_id = 'adjreason' AND activity = 1 AND notes LIKE '%=Ins%' LIMIT 1");
$adjustments_indicate_insurance = !empty($tmp['option_id']);
**********************************************************************/

// Now write a data entry form:
// List unbilled billing items (cpt, hcpcs, copays) for the patient.
// List unbilled product sales for the patient.
// Present an editable dollar amount for each line item, a total
// which is also the default value of the input payment amount,
// and OK and Cancel buttons.
?>
<html>
<head>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<title><?php xl('Patient Checkout','e'); ?></title>
<style>
</style>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.2.2.min.js"></script>
<script language="JavaScript">
 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 /********************************************************************
 // This clears the tax line items in preparation for recomputing taxes.
 function clearTax(visible) {
  var f = document.forms[0];
  for (var lino = 0; true; ++lino) {
   var pfx = 'line[' + lino + ']';
   if (! f[pfx + '[code_type]']) break;
   if (f[pfx + '[code_type]'].value != 'TAX') continue;
   f[pfx + '[price]'].value = '0.00';
<?php if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) { ?>
   f[pfx + '[charge]'].value = '0.00';
<?php } ?>
   if (visible) f[pfx + '[amount]'].value = '0.00';
  }
 }
 ********************************************************************/

 // This clears tax amounts in preparation for recomputing taxes.
 // TBD: Probably don't need this at all.
 function clearTax(visible) {
  var f = document.forms[0];
  for (var i = 0; f['totaltax[' + i + ']']; ++i) {
   f['totaltax[' + i + ']'].value = '0.00';
  }
 }

 /********************************************************************
 // For a given tax ID and amount, compute the tax on that amount and add it
 // to the "price" (same as "amount") of the corresponding tax line item.
 // Note the tax line items include their "taxrate" to make this easy.
 function addTax(rateid, amount, visible) {
  if (rateid.length == 0) return 0;
  var f = document.forms[0];
  for (var lino = 0; true; ++lino) {
   var pfx = 'line[' + lino + ']';
   if (! f[pfx + '[code_type]']) break;
   if (f[pfx + '[code_type]'].value != 'TAX') continue;
   if (f[pfx + '[code]'].value != rateid) continue;
   var tax = amount * parseFloat(f[pfx + '[taxrates]'].value);
   tax = parseFloat(tax.toFixed(<?php echo $currdecimals ?>));
   var cumtax = parseFloat(f[pfx + '[price]'].value) + tax;
   var tmp = cumtax.toFixed(<?php echo $currdecimals ?>); // requires JS 1.5
   f[pfx + '[price]'].value = tmp;
<?php if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) { ?>
   f[pfx + '[charge]'].value = tmp;
<?php } ?>
   if (visible) f[pfx + '[amount]'].value = tmp;
   if (isNaN(tax)) alert('Tax rate not numeric at line ' + lino);
   return tax;
  }
  return 0;
 }
 ********************************************************************/

 // This computes taxes and extended amount for the specified line, and returns
 // the extended amount.
 function calcTax(lino) {
   var f = document.forms[0];
   var pfx = 'line[' + lino + ']';
   var taxable = parseFloat(f[pfx + '[charge]'].value);
   var adjust = 0.00;
   if (f[pfx + '[adjust]']) {
    adjust = parseFloat(f[pfx + '[adjust]'].value);
   }
   var adjreason = '';
   if (f[pfx + '[memo]']) {
    adjreason = f[pfx + '[memo]'].value;
   }
   var extended = taxable - adjust;
   if (true
<?php
  // Generate JavaScript that checks if the chosen adjustment type is to be
  // applied before taxes are computed. option_value 1 indicates that the
  // "After Taxes" checkbox is checked for an adjustment type.
  $tmpres = sqlStatement("SELECT option_id FROM list_options WHERE " .
    "list_id = 'adjreason' AND option_value = 1 AND activity = 1");
  while ($tmprow = sqlFetchArray($tmpres)) {
    echo "    && adjreason != '" . addslashes($tmprow['option_id']) . "'\n";
  }
?>
   ) {
    taxable -= adjust;
   }
   var taxnumrates  = f[pfx + '[taxnumrates]'].value;
   var rates = taxnumrates.split(':');
   for (var i = 0; i < rates.length; ++i) {
    if (! f[pfx + '[tax][' + i + ']']) break;
    var tax = 0;
    if (f[pfx + '[billtime]'].value) {
      // Line item is billed, use the tax amounts that were previously set for it.
      tax = parseFloat(f[pfx + '[tax][' + i + ']'].value);
    }
    else {
      tax = taxable * parseFloat(rates[i]);
      tax = parseFloat(tax.toFixed(<?php echo $currdecimals ?>));
      if (isNaN(tax)) alert('Tax rate not numeric at line ' + lino);
      f[pfx + '[tax][' + i + ']'].value = tax.toFixed(<?php echo $currdecimals ?>);
    }
    extended += tax;
    var totaltax = parseFloat(f['totaltax[' + i + ']'].value) + tax;
    f['totaltax[' + i + ']'].value = totaltax.toFixed(<?php echo $currdecimals ?>);
   }
   f[pfx + '[amount]'].value = extended.toFixed(<?php echo $currdecimals ?>);
   return extended;
 }

 // This mess recomputes total charges and optionally applies a discount.
 // As part of this, taxes and extended amount are recomputed for each line.
 function computeDiscountedTotals(discount, visible) {
  clearTax(visible);
  var f = document.forms[0];
  var total = 0.00;
  for (var lino = 0; f['line[' + lino + '][code_type]']; ++lino) {
   var code_type = f['line[' + lino + '][code_type]'].value;
   // price is price per unit when the form was originally generated.
   // By contrast, amount is the dynamically-generated discounted line total.
   var price = parseFloat(f['line[' + lino + '][price]'].value);
   if (isNaN(price)) alert('Price not numeric at line ' + lino);
   if (code_type == 'COPAY' || code_type == 'TAX') {
    // I think this case is obsolete now.
    total += parseFloat(price.toFixed(<?php echo $currdecimals ?>));
    continue;
   }

   // Compute and set taxes and extended amount for the given line.
   // This also returns the extended amount.
   total += calcTax(lino);

   /******************************************************************
   var amount = f['line[' + lino + '][amount]'].value;
   total += parseFloat(amount);
   var taxrates  = f['line[' + lino + '][taxrates]'].value;
   var taxids = taxrates.split(':');
   for (var j = 0; j < taxids.length; ++j) {
    addTax(taxids[j], amount, visible);
   }
   ******************************************************************/
  }
  if (visible) f.totalchg.value = total.toFixed(<?php echo $currdecimals ?>);
  return total - discount;
 }

 // This computes and returns the total of payments.
 function computePaymentTotal() {
  var f = document.forms[0];
  var total = 0.00;
  for (var lino = 0; ('oldpay[' + lino + '][amount]') in f; ++lino) {
   var amount = parseFloat(f['oldpay[' + lino + '][amount]'].value);
   if (isNaN(amount)) continue;
   amount = parseFloat(amount.toFixed(<?php echo $currdecimals ?>));
   total += amount;
  }
  for (var lino = 0; ('payment[' + lino + '][amount]') in f; ++lino) {
   var amount = parseFloat(f['payment[' + lino + '][amount]'].value);
   if (isNaN(amount)) amount = parseFloat(0);
   amount = parseFloat(amount.toFixed(<?php echo $currdecimals ?>));
   total += amount;
   // Set payment row's description to Refund if the amount is negative.
   var title = amount < 0 ? '<?php echo xl('Refund'); ?>' : '<?php echo xl('New payment'); ?>';
   var span = document.getElementById('paytitle_' + lino);
   span.innerHTML = title;
  }
  return total;
 }

 // Recompute default payment amount with any discount applied, but
 // not if there is more than one input payment line.
 // This is called when the discount amount is changed, and initially.
 // As a side effect the tax line items are recomputed and
 // setComputedValues() is called.
 function billingChanged() {
  var f = document.forms[0];
  var discount = parseFloat(f.form_discount.value);
  if (isNaN(discount)) discount = 0;
<?php if (!$GLOBALS['discount_by_money']) { ?>
  // This site discounts by percentage, so convert it to a money amount.
  if (discount > 100) discount = 100;
  if (discount < 0  ) discount = 0;
  discount = 0.01 * discount * computeDiscountedTotals(0, false);
<?php } ?>
  var total = computeDiscountedTotals(discount, true);
  // Get out if there is more than one input payment line.
  if (!('payment[1][amount]' in f)) {
   f['payment[0][amount]'].value = 0;
   total -= computePaymentTotal();
   f['payment[0][amount]'].value = total.toFixed(<?php echo $currdecimals ?>);
  }
  setComputedValues();
  return true;
 }

 // A line item adjustment was changed, so recompute stuff.
 function lineDiscountChanged(lino) {
  var f = document.forms[0];
  var discount = parseFloat(f['line[' + lino + '][adjust]'].value);
  if (isNaN(discount)) discount = 0;
  var charge = parseFloat(f['line[' + lino + '][charge]'].value);
  if (isNaN(charge)) charge = 0;
<?php if (!$GLOBALS['discount_by_money']) { ?>
  // This site discounts by percentage, so convert it to a money amount.
  if (discount > 100) discount = 100;
  if (discount < 0  ) discount = 0;
  discount = 0.01 * discount * charge;
<?php } ?>
  var amount = charge - discount;
  f['line[' + lino + '][amount]'].value = amount.toFixed(<?php echo $currdecimals ?>);
  // alert(f['line[' + lino + '][amount]'].value); // debugging
  return billingChanged();
 }

 // Set Total Payments, Difference and Balance Due when any amount changes.
 function setComputedValues() {
  var f = document.forms[0];
  var payment = computePaymentTotal();
  var difference = computeDiscountedTotals(0, false) - payment;
  var discount = parseFloat(f.form_discount.value);
  if (isNaN(discount)) discount = 0;
<?php if (!$GLOBALS['discount_by_money']) { ?>
  // This site discounts by percentage, so convert it to a money amount.
  if (discount > 100) discount = 100;
  if (discount < 0  ) discount = 0;
  discount = 0.01 * discount * computeDiscountedTotals(0, false);
<?php } ?>
  var balance = difference - discount;
  f.form_totalpay.value = payment.toFixed(<?php echo $currdecimals ?>);
  f.form_difference.value = difference.toFixed(<?php echo $currdecimals ?>);
  f.form_balancedue.value = balance.toFixed(<?php echo $currdecimals ?>);
  return true;
 }

 // This is called when [Compute] is clicked by the user.
 // Computes and sets the discount value from total charges less payment.
 // This also calls setComputedValues() so the balance due will be correct.
 function computeDiscount() {
  var f = document.forms[0];
  var charges = computeDiscountedTotals(0, false);
  var payment = computePaymentTotal();
  var discount = charges - payment;
<?php if (!$GLOBALS['discount_by_money']) { ?>
  // This site discounts by percentage, so convert to that.
  discount = charges ? (100 * discount / charges) : 0;
  f.form_discount.value = discount.toFixed(4);
<?php } else { ?>
  f.form_discount.value = discount.toFixed(<?php echo $currdecimals ?>);
<?php } ?>
  setComputedValues();
  return false;
 }

 // When the main adjustment reason changes, duplicate it to all per-line reasons.
 function discountTypeChanged() {
  var f = document.forms[0];
  if (f.form_discount_type && f.form_discount_type.selectedIndex) {
   for (lino = 0; f['line[' + lino + '][memo]']; ++lino) {
    // But do not change adjustment reason for billed items.
    if (f['line[' + lino + '][billtime]'].value) continue;
    f['line[' + lino + '][memo]'].selectedIndex = f.form_discount_type.selectedIndex;
   }
  }
 }

 // When the main charge category changes, duplicate it to all per-line categories.
 function chargeCategoryChanged() {
  var f = document.forms[0];
  if (f.form_charge_category && f.form_charge_category.selectedIndex) {
   for (lino = 0; f['line[' + lino + '][chargecat]']; ++lino) {
    // But do not change categories for billed items.
    if (f['line[' + lino + '][billtime]'].value) continue;
    f['line[' + lino + '][chargecat]'].selectedIndex = f.form_charge_category.selectedIndex;
   }
  }
 }

 function validate() {
  var f = document.forms[0];
  var missingtypeamt = false;
  var missingtypeany = false;
  for (lino = 0; f['line[' + lino + '][memo]']; ++lino) {
   if (f['line[' + lino + '][memo]'].selectedIndex == 0 && f['line[' + lino + '][billtime]'].value == '') {
    missingtypeany = true;
    if (parseFloat(f['line[' + lino + '][adjust]'].value) != 0) {
     missingtypeamt = true;
    }
   }
  }
<?php if (false /* adjustments_indicate_insurance */) { ?>
  if (missingtypeany) {
   alert('<?php echo xls('Adjustment type is required for every line item.') ?>');
   return false;
  }
<?php } else { ?>
  if (missingtypeamt) {
   alert('<?php echo xls('Adjustment type is required for each line with an adjustment.') ?>');
   return false;
  }
<?php } ?>
  top.restoreSession();
  return true;
 }

</script>
</head>

<body class="body_top">

<?php
echo "<form method='post' action='pos_checkout.php?rde=$rapid_data_entry";
if ($encounter_id) echo "&enid=$encounter_id";
if (!empty($_GET['framed'])) echo '&framed=1';
echo "' onsubmit='return validate()'>\n";
echo "<input type='hidden' name='form_pid' value='$patient_id' />\n";
?>

<center>

<p>
<table cellspacing='5' id='paytable' width='85%'>
<?php
$inv_date      = '';
$inv_provider  = 0;
$inv_payer     = 0;
$gcac_related_visit = false;
$gcac_service_provided = false;

// This is set by write_form_headers() when the encounter is known.
$encounter_date = '';

// This to save copays from the billing table.
$aCopays = array();

$lino = 0;

$query = "SELECT id, date, code_type, code, modifier, code_text, " .
  "provider_id, payer_id, units, fee, encounter, billed, bill_date, chargecat " .
  "FROM billing WHERE pid = ? AND encounter = ? AND activity = 1 AND " .
  "code_type != 'TAX' ORDER BY id ASC";
$bres = sqlStatement($query, array($patient_id, $encounter_id));

$query = "SELECT s.sale_id, s.sale_date, s.prescription_id, s.fee, s.quantity, " .
  "s.encounter, s.drug_id, s.billed, s.bill_date, s.selector, s.chargecat, d.name, r.provider_id " .
  "FROM drug_sales AS s " .
  "LEFT JOIN drugs AS d ON d.drug_id = s.drug_id " .
  "LEFT OUTER JOIN prescriptions AS r ON r.id = s.prescription_id " .
  "WHERE s.pid = ? AND s.encounter = ? " .
  "ORDER BY s.sale_id ASC";
$dres = sqlStatement($query, array($patient_id, $encounter_id));

// Process billing table items.  Note this includes co-pays.
// Items that are not allowed to have a fee are skipped.
//
while ($brow = sqlFetchArray($bres)) {
  $thisdate = substr($brow['date'], 0, 10);
  $code_type = $brow['code_type'];
  $inv_payer = $brow['payer_id'];
  if (!$inv_date || $inv_date < $thisdate) $inv_date = $thisdate;

  // Co-pays are saved for later.
  if ($code_type == 'COPAY') {
    $aCopays[] = $brow;
    continue;
  }

  $billtime = $brow['billed'] ? $brow['bill_date'] : '';

  // Collect tax rates, related code and provider ID.
  $taxrates = '';
  $related_code = '';
  if (!empty($code_types[$code_type]['fee'])) {
    $query = "SELECT taxrates, related_code FROM codes WHERE code_type = '" .
      $code_types[$code_type]['id'] . "' AND " .
      "code = '" . $brow['code'] . "' AND ";
    if ($brow['modifier']) {
      $query .= "modifier = '" . $brow['modifier'] . "'";
    } else {
      $query .= "(modifier IS NULL OR modifier = '')";
    }
    $query .= " LIMIT 1";
    $tmp = sqlQuery($query);
    $taxrates = $tmp['taxrates'];
    $related_code = $tmp['related_code'];
    markTaxes($taxrates);
  }

  // Write the line item if it allows fees or is not a diagnosis.
  if (!empty($code_types[$code_type]['fee']) || empty($code_types[$code_type]['diag'])) {
    write_form_line($code_type, $brow['code'], $brow['id'], $thisdate,
      ucfirst(strtolower($brow['code_text'])), $brow['fee'], $brow['units'],
      $taxrates, $billtime, $brow['chargecat']);
  }

  // Custom logic for IPPF to determine if a GCAC issue applies.
  if ($GLOBALS['ippf_specific'] && $related_code) {
    $relcodes = explode(';', $related_code);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);
      if ($codetype !== 'IPPF2') continue;
      if (preg_match('/^211/', $code)) {
        $gcac_related_visit = true;
        if (preg_match('/^211313030110/', $code) // Medical
         || preg_match('/^211323030230/', $code) // Surgical
         || preg_match('/^211403030110/', $code) // Incomplete Medical
         || preg_match('/^211403030230/', $code) // Incomplete Surgical
        ) {
          $gcac_service_provided = true;
        }
      }      
    }
  }
}

// Process drug sales / products.
//
while ($drow = sqlFetchArray($dres)) {
  // if ($encounter_id && $drow['encounter'] && $drow['encounter'] != $encounter_id) continue;
  if ($encounter_id && $drow['encounter'] != $encounter_id) continue;

  $thisdate = $drow['sale_date'];
  if (!$encounter_id) $encounter_id = $drow['encounter'];

  if (!$inv_provider && !empty($arr_users[$drow['provider_id']]))
    $inv_provider = $drow['provider_id'] + 0;

  if (!$inv_date || $inv_date < $thisdate) $inv_date = $thisdate;

  $billtime = $drow['billed'] ? $drow['bill_date'] : '';

  // Accumulate taxes for this product.
  $tmp = sqlQuery("SELECT taxrates FROM drug_templates WHERE drug_id = '" .
    $drow['drug_id'] . "' ORDER BY selector LIMIT 1");
  // accumTaxes($drow['fee'], $tmp['taxrates']);
  $taxrates = $tmp['taxrates'];
  markTaxes($taxrates);

  $tmpname = $drow['name'];
  if ($tmpname !== $drow['selector']) $tmpname .= ' / ' . $drow['selector'];

  $units = $drow['quantity'] / FeeSheet::getBasicUnits($drow['drug_id'], $drow['selector']);

  write_form_line('PROD', $drow['drug_id'], $drow['sale_id'], $thisdate,
    $tmpname, $drow['fee'], $units, $taxrates, $billtime, $drow['chargecat']);
}

/*********************************************************************
// Write a form line for each tax that has money, adding to $total.
foreach ($taxes as $key => $value) {
  if ($value[2]) {
    write_form_line('TAX', $key, $key, date('Y-m-d'), $value[0], 0, 1, $value[1]);
  }
}
*********************************************************************/

// Line for total charges.
$totalchg = formatMoneyNumber($totalchg);
echo " <tr>\n";
echo "  <td class='bold' colspan='" . ($GLOBALS['gbl_checkout_charges'] ? 4 : 3) .
     "' align='right'>" . xl('Total Charges This Visit') . "</td>\n";
echo "  <td class='text' align='right'><input type='text' name='totalcba' " .
     "value='$totalchg' size='6' maxlength='8' " .
     "style='text-align:right;background-color:transparent' readonly";
echo "></td>\n";
for ($i = 0; $i < count($taxes); ++$i) {
  echo "  <td class='text' align='right'><input type='text' name='totaltax[$i]' " .
       "value='0.00' size='6' maxlength='8' " .
       "style='text-align:right;background-color:transparent' readonly";
  echo "></td>\n";
}
if (!empty($GLOBALS['gbl_charge_categories'])) {
  echo "  <td class='text' align='right'>&nbsp;</td>\n"; // Empty space in charge category column.
}
if (!empty($GLOBALS['gbl_checkout_line_adjustments'])) {
  // Note $totalchg is the total of charges before adjustments, and the following
  // field will be recomputed at onload time and as adjustments are entered.
  echo "  <td class='text' align='right'>&nbsp;</td>\n"; // Empty space in adjustment type column.
  echo "  <td class='text' align='right'>&nbsp;</td>\n"; // TBD: Total adjustments can go here.
}
echo "  <td class='text' align='right'><input type='text' name='totalchg' " .
     "value='$totalchg' size='6' maxlength='8' " .
     "style='text-align:right;background-color:transparent' readonly";
echo "></td>\n";
echo " </tr>\n";
?>

<!--
</table>
<table cellspacing='5' id='paytable' width='100%'>
-->

 <tr>
  <td class='title' colspan='<?php echo 5 + $num_optional_columns; ?>'
   style='border-top:1px solid black; padding-top:5pt;'>
   <b><?php xl('Payments','e'); ?></b>
  </td>
 </tr>

<?php
// Start new section for payments.
echo "   <td class='bold' colspan='$form_num_type_columns'>" . xlt('Type') . "</td>\n";
echo "   <td class='bold' colspan='$form_num_method_columns'>" . xlt('Payment Method') . "</td>\n";
echo "   <td class='bold' colspan='$form_num_ref_columns'>" . xlt('Reference') . "</td>\n";
echo "   <td class='bold' colspan='$form_num_amount_columns' align='right' nowrap>" . xlt('Payment Amount') . "</td>\n";
echo "  </tr>\n";

$lino = 0;

// Write co-pays.
foreach ($aCopays as $brow) {
  $thisdate = substr($brow['date'], 0, 10);
  write_old_payment_line(xl('Prepayment'), $thisdate, $brow['code_text'], '', 0 - $brow['fee']);
}

// Write any adjustments left in the aAdjusts array. This should only happen if
// there was an invoice-level discount in a prior checkout of this encounter.
foreach ($aAdjusts as $arow) {
  $memo = $arow['memotitle'];
  if ($arow['adj_amount'] == 0 && $memo === '') continue;
  $reference = $arow['reference'];
  // The following removed because check numbers should not be in adjustments.
  /*******************************************************************
  if (empty($arow['session_id'])) {
    $atmp = explode(' ', $arow['memo'], 2);
    $memo = $atmp[0];
    $reference = $atmp[1];
  }
  *******************************************************************/
  write_old_payment_line(xl('Adjustment'), $thisdate, $memo, $reference, $arow['adj_amount']);
}

// Write ar_activity payments.
$ares = sqlStatement("SELECT " .
  "a.payer_type, a.pay_amount, a.memo, s.session_id, s.reference, s.check_date " .
  "FROM ar_activity AS a " .
  "LEFT JOIN ar_session AS s ON s.session_id = a.session_id WHERE " .
  "a.pid = '$patient_id' AND a.encounter = '$encounter_id' AND a.deleted IS NULL AND a.pay_amount != 0 " .
  "ORDER BY s.check_date, a.sequence_no");
while ($arow = sqlFetchArray($ares)) {
  $memo = $arow['memo'];
  $reference = $arow['reference'];
  if (empty($arow['session_id'])) {
    $atmp = explode(' ', $memo, 2);
    $memo = $atmp[0];
    $reference = $atmp[1];
  }
  $rowtype = $arow['payer_type'] ? xl('Insurance payment') : xl('Prepayment');
  write_old_payment_line($rowtype, $thisdate, $memo, $reference, $arow['pay_amount']);
}

// Line for total payments.
echo " <tr id='totalpay'>\n";
// if ($GLOBALS['gbl_checkout_line_adjustments']) {
//   echo "  <td>&nbsp;</td>\n";
// }
echo "  <td class='bold' colspan='$form_num_type_columns'><a href='#' onclick='return addPayLine()'>[" . xl('Add Row') . "]</a></td>\n";
echo "  <td class='bold' colspan='" . ($form_num_method_columns + $form_num_ref_columns) .
     "' align='right'>" . xl('Total Payments This Visit') . "</td>\n";
echo "  <td class='text' align='right' colspan='$form_num_amount_columns'><input type='text' name='form_totalpay' " .
     "value='$amount' size='6' maxlength='8' " .
     "style='text-align:right;background-color:transparent' readonly";
echo "></td>\n";
echo " </tr>\n";

// Line for Difference.
echo "  <tr>\n";
echo "   <td class='text' colspan='" . (5 + $num_optional_columns) .
     "' style='border-top:1px solid black; font-size:1pt; padding:0px;'>&nbsp;</td>\n";
echo "  </tr>\n";

echo " <tr";
// Hide this if only showing line item adjustments.
if ($GLOBALS['gbl_checkout_line_adjustments'] == 1) echo " style='display:none'";
echo ">\n";
echo "  <td class='title' colspan='" . ($form_num_type_columns + $form_num_method_columns + $form_num_ref_columns) .
     "' align='right'><b>" . xl('Difference') . "</b></td>\n";
echo "  <td class='text' align='right' colspan='$form_num_amount_columns'><input type='text' name='form_difference' " .
     "value='' size='6' maxlength='8' " .
     "style='text-align:right;background-color:transparent' readonly";
echo "></td>\n";
echo " </tr>\n";

if ($encounter_id) {
  $erow = sqlQuery("SELECT provider_id FROM form_encounter WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter_id' " .
    "ORDER BY id DESC LIMIT 1");
  $inv_provider = $erow['provider_id'] + 0;
}

// Line for Discount.
echo " <tr";
// Hide this if only showing line item adjustments.
if ($GLOBALS['gbl_checkout_line_adjustments'] == 1) echo " style='display:none'";
echo ">\n";
echo "  <td class='bold' colspan='" . ($form_num_type_columns + $form_num_method_columns + $form_num_ref_columns) .
     "' align='right'>";
if (acl_check('acct','disc') || acl_check('acct','super')) {
  echo "<a href='#' onclick='return computeDiscount()'>[" . xl('Compute') ."]</a> <b>";
  echo xl('Discount/Adjustment') . "</b></td>\n";
  echo "  <td class='text' align='right' colspan='$form_num_amount_columns'>" .
       "<input type='text' name='form_discount' " .
       "value='' size='6' maxlength='8' onkeyup='billingChanged()' " .
       "style='text-align:right' />";
}
else {
  echo "" . xl('Discount/Adjustment') . "</td>\n";
  echo "  <td class='text' align='right' colspan='$form_num_amount_columns'>" .
       "<input type='text' name='form_discount' value='' size='6' " .
       "style='text-align:right;background-color:transparent' readonly />";
}
echo "</td>\n";
echo " </tr>\n";

// Line for Balance Due
echo " <tr>\n";
echo "  <td class='title' colspan='" . ($form_num_type_columns + $form_num_method_columns + $form_num_ref_columns) .
     "' align='right'>" . xl('Balance Due') . "</td>\n";
echo "  <td class='text' align='right' colspan='$form_num_amount_columns'>" .
     "<input type='text' name='form_balancedue' " .
     "value='' size='6' maxlength='8' " .
     "style='text-align:right;background-color:transparent' readonly";
echo "></td>\n";
echo " </tr>\n";
?>

 <tr>
  <td class='bold' colspan='<?php echo ($form_num_type_columns + $form_num_method_columns + $form_num_ref_columns) +
    (empty($GLOBALS['gbl_charge_categories']) ? 0 : 1); ?>' align='right'>
   <b><?php xl('Posting Date','e'); ?></b>
  </td>
  <td class='text' colspan='<?php echo $form_num_amount_columns; ?>' align='right'>
   <input type='text' size='10' name='form_date' id='form_date'
    value='<?php echo $inv_date ?>'
    title='yyyy-mm-dd date of service'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_date' border='0' alt='[?]' style='cursor:pointer'
    title='Click here to choose a date'>
  </td>
 </tr>

<?php
// A current invoice reference number may be present if there was a previous checkout.
$tmprow = sqlQuery("SELECT invoice_refno FROM form_encounter WHERE " .
  "pid = '$patient_id' AND encounter = '$encounter_id'");
$current_irnumber = $tmprow['invoice_refno'];

if (!$current_irnumber) {
  // If this user has a non-empty irnpool assigned, show the pending
  // invoice reference number.
  $irnumber = getInvoiceRefNumber();
  if (!empty($irnumber)) {
?>
 <tr>
  <td class='bold' colspan='<?php echo ($form_num_type_columns + $form_num_method_columns + $form_num_ref_columns) +
    (empty($GLOBALS['gbl_charge_categories']) ? 0 : 1); ?>' align='right'>
   <?php xl('Tentative Invoice Ref No','e'); ?>
  </td>
  <td class='text' align='right' colspan='<?php echo $form_num_amount_columns; ?>'>
   <?php echo $irnumber; ?>
  </td>
 </tr>
<?php
  }
  // Otherwise if there is an invoice reference number mask, ask for the refno.
  else if (!empty($GLOBALS['gbl_mask_invoice_number'])) {
?>
 <tr>
  <td class='bold' colspan='<?php echo ($form_num_type_columns + $form_num_method_columns + $form_num_ref_columns) +
    (empty($GLOBALS['gbl_charge_categories']) ? 0 : 1); ?>' align='right'>
   <?php xl('Invoice Reference Number','e'); ?>
  </td>
  <td class='text' align='right' colspan='<?php echo $form_num_amount_columns; ?>'>
   <input type='text' name='form_irnumber' size='10' value=''
    onkeyup='maskkeyup(this,"<?php echo addslashes($GLOBALS['gbl_mask_invoice_number']); ?>")'
    onblur='maskblur(this,"<?php echo addslashes($GLOBALS['gbl_mask_invoice_number']); ?>")'
    />
  </td>
 </tr>
<?php
  }
}
?>

 <tr>
  <td class='text' colspan='<?php echo 5 + $num_optional_columns; ?>' align='center'>
   &nbsp;<br>
   <input type='submit' name='form_save' value='<?php xl('Save','e'); ?>'
<?php if ($rapid_data_entry) echo "    style='background-color:#cc0000';color:#ffffff'"; ?>
   /> &nbsp;
<?php if (empty($_GET['framed'])) { ?>
   <input type='button' value='Cancel' onclick='window.close()' />
<?php } ?>
   <input type='hidden' name='form_provider'  value='<?php echo $inv_provider  ?>' />
   <input type='hidden' name='form_payer'     value='<?php echo $inv_payer     ?>' />
   <input type='hidden' name='form_encounter' value='<?php echo $encounter_id ?>' />
   <input type='hidden' name='form_checksum'  value='<?php echo $current_checksum; ?>' />
  </td>
 </tr>

</table>

</center>

</form>

<script language='JavaScript'>

 // Add a line for entering a payment.
 // Declared down here because $form_num_*_columns must be defined.
 var paylino = 0;
 function addPayLine() {
  var table = document.getElementById('paytable');
  for (var i = 0; i < table.rows.length; ++i) {
   if (table.rows[i].id == 'totalpay') {
    var row = table.insertRow(i);
    var cell;
<?php
foreach ($aCellHTML as $ix => $html) {
  echo "    var html = \"$html\";\n";
  echo "    cell = row.insertCell(row.cells.length);\n";
  if ($ix == 0) echo "    cell.colSpan = $form_num_type_columns;\n";
  if ($ix == 1) echo "    cell.colSpan = $form_num_method_columns;\n";
  if ($ix == 2) echo "    cell.colSpan = $form_num_ref_columns;\n";
  if ($ix == 3) echo "    cell.colSpan = $form_num_amount_columns;\n";
  echo "    cell.innerHTML = html.replace(/%d/, paylino);\n";
}
?>
    cell.align = 'right'; // last cell is right-aligned
    ++paylino;
    break;
   }
  }
  return false;
 }

 // Pop up the Payments window and close this one.
 function payprevious() {
  var width  = 750;
  var height = 550;
  var loc = '../patient_file/front_payment.php?omitenc=<?php echo $encounter_id; ?>';
<?php if (empty($_GET['framed'])) { ?>
  opener.parent.left_nav.dlgopen(loc, '_blank', width, height);
  window.close();
<?php } else { ?>
  var tmp = parent.left_nav ? parent.left_nav : parent.parent.left_nav;
  tmp.dlgopen(loc, '_blank', width, height);
<?php } ?>
 }

 Calendar.setup({inputField:"form_date", ifFormat:"%Y-%m-%d", button:"img_date"});
 discountTypeChanged();
 addPayLine();
 billingChanged();
<?php
if ($alertmsg) {
  echo "alert('" . addslashes($alertmsg) . "');\n";
}

if ($gcac_related_visit && !$gcac_service_provided) {
  // Skip this warning if the GCAC visit form is not allowed.
  $grow = sqlQuery("SELECT COUNT(*) AS count FROM layout_group_properties " .
    "WHERE grp_form_id = 'LBFgcac' AND grp_group_id = '' AND grp_activity = 1");
  if (!empty($grow['count'])) { // if gcac is used
    // Skip this warning if referral or abortion in TS.
    $grow = sqlQuery("SELECT COUNT(*) AS count FROM transactions " .
      "WHERE title = 'Referral' AND refer_date IS NOT NULL AND " .
      "refer_date = '$inv_date' AND pid = '$patient_id'");
    if (empty($grow['count'])) { // if there is no referral
      $grow = sqlQuery("SELECT COUNT(*) AS count FROM forms " .
        "WHERE pid = '$patient_id' AND encounter = '$encounter_id' AND " .
        "deleted = 0 AND formdir = 'LBFgcac'");
      if (empty($grow['count'])) { // if there is no gcac form
        echo " alert('" . xl('This visit will need a GCAC form, referral or procedure service.') . "');\n";
      }
    }
  }
} // end if ($gcac_related_visit)

if ($GLOBALS['ippf_specific']) {
  // More validation:
  // o If there is an initial contraceptive consult, make sure a LBFccicon form exists with that method on it.
  // o If a LBFccicon form exists with a new method on it, make sure the TS initial consult exists.

  require_once("$srcdir/contraception_billing_scan.inc.php");
  contraception_billing_scan($patient_id, $encounter_id);

  $csrow = sqlQuery("SELECT f.form_id, ld.field_value FROM forms AS f " .
    "LEFT JOIN lbf_data AS ld ON ld.form_id = f.form_id AND ld.field_id = 'newmethod' " .
    "WHERE " .
    "f.pid = '$patient_id' AND f.encounter = '$encounter_id' AND " .
    "f.formdir = 'LBFccicon' AND f.deleted = 0 " .
    "ORDER BY f.form_id DESC LIMIT 1");
  $csmethod = empty($csrow['field_value']) ? '' : $csrow['field_value'];

  if (($csmethod || $contraception_billing_code) && $csmethod != "IPPFCM:$contraception_billing_code") {
    echo " alert('" . xl('Warning') . ': ';
    if (!$csmethod) {
      echo xl('there is a contraception service but no contraception form new method');
    }
    else if (!$contraception_billing_code) {
      echo xl('there is a contraception form new method but no contraception service');
    }
    else {
      echo xl('new method in contraception form does not match the contraception service');
    }
    echo "');\n";
  }
}
?>
</script>

</body>
</html>
