<?php
// Copyright (C) 2010-2017 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is an inventory transactions list.

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("../drugs/drugs.inc.php");

// For each sorting option, specify the ORDER BY argument.
//
$ORDERHASH = array(
  'date' => 's.sale_date, s.sale_id, xfer_inventory_id != di_inventory_id',
  'tran' => 's.trans_type, s.sale_date, s.sale_id, xfer_inventory_id != di_inventory_id',
  'prod' => 'd.name, s.sale_date, s.sale_id, xfer_inventory_id != di_inventory_id',
  'fac'  => 'facname, s.sale_date, s.sale_id, xfer_inventory_id != di_inventory_id',
  'wh'   => 'warehouse, s.sale_date, s.sale_id, xfer_inventory_id != di_inventory_id',
  'lot'  => 'di.lot_number, d.name, s.sale_date, s.sale_id, xfer_inventory_id != di_inventory_id',
  'invoice' => '(isnull(fe.invoice_refno) OR fe.invoice_refno=""), fe.invoice_refno, s.pid, s.encounter, xfer_inventory_id != di_inventory_id'
  // 'who'  => 'plname, pfname, pmname, s.sale_date, s.sale_id',
);

function bucks($amount) {
  if ($amount != 0) return oeFormatMoney($amount);
  return '';
}

function esc4Export($str) {
  return str_replace('"', '\\"', $str);
}

function thisLineItem($row, $xfer=false) {
  global $grandtotal, $grandqty, $encount, $form_action, $include_sales_info;

  $ttype = '';
  // If this row is for the source lot of a transfer, invert quantity and fee.
  if (!empty($row['xfer_inventory_id'])) {
    if ($row['di_inventory_id'] == $row['xfer_inventory_id']) {
      $ttype = xl('Transfer Out');
      $row['quantity'] = 0 - $row['quantity'];
      $row['fee'] = 0 - $row['fee'];
    }
    else {
      $ttype = xl('Transfer In');
    }
  }

  $patient_id   = $row['pid'];
  $encounter_id = $row['encounter'];
  $invnumber = '';
  $dpname = '';

  if (!empty($patient_id)) {
    $ttype = xl('Sale');
    // Patient name display was removed in favor of invoice number, but leave
    // the logic here in case someone wants it again.
    $dpname = $row['plname'];
    if (!empty($row['pfname'])) {
      $dpname .= ', ' . $row['pfname'];
      if (!empty($row['pmname'])) $dpname .= ' ' . $row['pmname'];
    }
    $invnumber = empty($row['invoice_refno']) ?
      "$patient_id.$encounter_id" : $row['invoice_refno'];
  }
  else if (!empty($row['xfer_inventory_id']) || $xfer) {
    if (!$ttype) $ttype = xl('Transfer');
  }
  else if ($row['trans_type'] == 7) {
    $ttype = xl('Consumption');
  }
  else if ($row['trans_type'] == 2) {
    $ttype = xl('Purchase/Receipt');
  }
  else if ($row['trans_type'] == 3) {
    $ttype = xl('Return');
  }
  else {
    $ttype = xl('Adjustment');
  }

  if ($form_action == 'export') {
    echo '"' . oeFormatShortDate($row['sale_date']) . '",';
    echo '"' . $ttype                               . '",';
    echo '"' . esc4Export($row['name'])             . '",';
    echo '"' . esc4Export($row['lot_number'])       . '",';
    echo '"' . esc4Export($row['facname'])          . '",';
    echo '"' . esc4Export($row['warehouse'])        . '",';
    if ($include_sales_info) {
      echo '"' . esc4Export($invnumber)             . '",';
    }
    echo '"' . (0 - $row['quantity'])               . '",';
    echo '"' . bucks($row['fee'])                   . '",';
    if ($include_sales_info) {
      echo '"' . $row['billed']                     . '",';
    }
    echo '"' . esc4Export($row['notes'])            . '"' . "\n";
  }
  else {
    $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
?>

 <tr bgcolor="<?php echo $bgcolor; ?>">
  <td class="detail">
   <?php echo htmlspecialchars(oeFormatShortDate($row['sale_date'])); ?>
  </td>
  <td class="detail">
   <?php echo htmlspecialchars($ttype); ?>
  </td>
  <td class="detail">
   <?php echo htmlspecialchars($row['name']); ?>
  </td>
  <td class="detail">
   <?php echo htmlspecialchars($row['lot_number']); ?>
  </td>
  <td class="detail">
   <?php echo text($row['facname']); ?>
  </td>
  <td class="detail">
   <?php echo htmlspecialchars($row['warehouse']); ?>
  </td>
<?php
  if ($include_sales_info) {
    if ($patient_id) {
      echo "  <td class='delink' onclick='doinvopen($patient_id,$encounter_id)'>\n";
    }
    else {
      echo "  <td class='detail'>\n";
    }
    echo "   " . text($invnumber) . "\n  </td>\n";
  }
?>
  <td class="detail" align="right">
   <?php echo htmlspecialchars(0 - $row['quantity']); ?>
  </td>
  <td class="detail" align="right">
   <?php echo htmlspecialchars(bucks($row['fee'])); ?>
  </td>
<?php if ($include_sales_info) { ?>
  <td class="detail" align="center">
   <?php echo empty($row['billed']) ? '&nbsp;' : '*'; ?>
  </td>
<?php } ?>
  <td class="detail">
   <?php echo htmlspecialchars($row['notes']); ?>
  </td>
 </tr>
<?php
  } // End not csv export

  $grandtotal   += $row['fee'];
  $grandqty     -= $row['quantity'];

} // end function

// Build a drop-down list of facilities.
//
function genFacilitySelector($tagname, $toptext, $default='') {
  global $is_user_restricted;
  $fres = sqlStatement("SELECT id, name FROM facility ORDER BY name");
  $s = "<select name='" . $tagname . "'>";
  $s .= "<option value=''>" . text($toptext) . "</option>";
  while ($frow = sqlFetchArray($fres)) {
    $facid = $frow['id'];
    if ($is_user_restricted && !isFacilityAllowed($facid)) continue;
    $s .= "<option value='$facid'";
    if ($facid == $default) $s .= " selected";
    $s .= ">" . text($frow['name']) . "</option>";
  }
  $s .= "</select>";
  return $s;
}

// Build a drop-down list of warehouses.
//
function genWarehouseSelector($tagname, $toptext, $default='') {
  $s = "<select name='" . attr($tagname) . "'";
  $s .= ">";
  $s .= "<option value=''>" . text($toptext) . "</option>";
  $lres = sqlStatement("SELECT * FROM list_options " .
    "WHERE list_id = 'warehouse' AND activity = 1 ORDER BY seq, title");
  while ($lrow = sqlFetchArray($lres)) {
    $s .= "<option value='" . attr($lrow['option_id']) . "'";

    if (strlen($default) > 0 && $lrow['option_id'] == $default) {
      $s .= " selected";
    }
    $s .= ">" . text(xl_list_label($lrow['title'])) . "</option>\n";
  }
  $s .= "</select>";
  return $s;
}

// Build a drop-down list of products.
//
function genProductSelector($tagname, $toptext, $default='') {
  $query = "SELECT drug_id, name FROM drugs ORDER BY name, drug_id";
  $pres = sqlStatement($query);
  $s = "<select name='" . attr($tagname) . "'>";
  $s .= "<option value=''>" . text($toptext) . "</option>";
  while ($prow = sqlFetchArray($pres)) {
    $drug_id = $prow['drug_id'];
    $s .= "<option value='$drug_id'";
    if ($drug_id == $default) $s .= " selected";
    $s .= ">" . text($prow['name']) . "</option>";
  }
  $s .= "</select>";
  return $s;
}

// Build a date widget.
//
function genDateSelector($tagname, $imgid, $default='') {
  return "<input type='text' name='" . attr($tagname) . "' id='" . attr($tagname) . "' size='10'
       value='" . attr($default) . "'
       title='" . xla('yyyy-mm-dd') . "'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'>
      <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
       id='" . attr($imgid) . "' border='0' alt='[?]' style='cursor:pointer'
       title='" . xla('Click here to choose a date') . "'>";
}

// Check permission for this report.
$auth_drug_reports = $GLOBALS['inhouse_pharmacy'] && (
  acl_check('admin'    , 'drugs'    ) ||
  acl_check('inventory', 'reporting'));
if (!$auth_drug_reports) {
  die(xl("Unauthorized access."));
}

// Note if user is restricted to any facilities and/or warehouses.
$is_user_restricted = isUserRestricted();

// this is "" or "submit" or "export".
$form_action = $_POST['form_action'];

$form_from_date  = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date    = fixDate($_POST['form_to_date'  ], date('Y-m-d'));
$form_trans_type = isset($_POST['form_trans_type']) ? $_POST['form_trans_type'] : '0';
$form_product    = isset($_POST['form_product'   ]) ? intval($_POST['form_product']) : 0;
$form_consumable = isset($_POST['form_consumable']) ? intval($_POST['form_consumable']) : 0;
$form_from_wh    = isset($_POST['form_from_wh'   ]) ? $_POST['form_from_wh'] : '';
$form_to_wh      = isset($_POST['form_to_wh'     ]) ? $_POST['form_to_wh'] : '';

$include_sales_info = $form_trans_type == '0' || $form_trans_type == '1';

// The selected facility ID, if any.
$form_facility = 0 + empty($_POST['form_facility']) ? 0 : $_POST['form_facility'];

$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'date';
$orderby = $ORDERHASH[$form_orderby];

$encount = 0;

if ($form_action == 'export') {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=inventory_transactions.csv");
  header("Content-Description: File Transfer");
  // CSV headers:
  echo '"' . xl('Date'       ) . '",';
  echo '"' . xl('Transaction') . '",';
  echo '"' . xl('Product'    ) . '",';
  echo '"' . xl('Lot'        ) . '",';
  echo '"' . xl('Facility'   ) . '",';
  echo '"' . xl('Warehouse'  ) . '",';
  if ($include_sales_info) {
    echo '"' . xl('Invoice'  ) . '",';
  }
  echo '"' . xl('Qty'        ) . '",';
  echo '"' . xl('Amount'     ) . '",';
  if ($include_sales_info) {
    echo '"' . xl('Billed'   ) . '",';
  }
  echo '"' . xl('Notes'      ) . '"' . "\n";
} // end export
else {
?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo htmlspecialchars(xl('Inventory Transactions'), ENT_NOQUOTES) ?></title>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>

<style type="text/css">
 /* specifically include & exclude from printing */
 @media print {
  #report_parameters {visibility: hidden; display: none;}
  #report_parameters_daterange {visibility: visible; display: inline;}
  #report_results {margin-top: 30px;}
 }

 /* specifically exclude some from the screen */
 @media screen {
  #report_parameters_daterange {visibility: hidden; display: none;}
 }

 body       { font-family:sans-serif; font-size:10pt; font-weight:normal }
 .dehead    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
 .delink    { color:#0000cc; font-family:sans-serif; font-size:10pt; font-weight:normal; cursor:pointer }

 #report_results table thead {
  font-size:10pt;
 }

</style>

<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js?v=<?php echo $v_js_includes; ?>"></script>

<script language='JavaScript'>

 function mysubmit(action) {
  var f = document.forms[0];
  f.form_action.value = action;
  top.restoreSession();
  f.submit();
 }

 function dosort(orderby) {
  var f = document.forms[0];
  f.form_orderby.value = orderby;
  f.form_action.value = 'submit';
  top.restoreSession();
  f.submit();
  return false;
 }

 function doinvopen(ptid,encid) {
  dlgopen('../patient_file/pos_checkout.php?ptid=' + ptid + '&enc=' + encid, '_blank', 750, 550);
 }

$(document).ready(function() {
  transTypeChanged();
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
});

function transTypeChanged() {
  var sel = document.forms[0]['form_trans_type'];
  document.getElementById('row_transfer').style.display = sel.value == '4' ? '' : 'none';
}

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class='body_top'>
<center>

<h2><?php echo htmlspecialchars(xl('Inventory Transactions'), ENT_NOQUOTES) ?></h2>

<form method='post' action='inventory_transactions.php'>

<div id="report_parameters">
<!-- form_action is set to "submit" or "export" at form submit time -->
<input type='hidden' name='form_action' value='' />
<table>
 <tr>
  <td width='75%'>
   <table class='text'>

    <tr>
     <td nowrap>
      <?php echo genFacilitySelector('form_facility', '-- ' . xl('All Facilities') . ' --', $form_facility); ?>
     </td>
     <td nowrap>
      <?php echo genProductSelector('form_product', '-- ' . xl('All Products') . ' --', $form_product); ?>
     </td>
     <td nowrap>
      <select name='form_consumable'><?php
        foreach (array(
          '0' => xl('All Product Types'),
          '1' => xl('Consumable Only'),
          '2' => xl('Non-Consumable Only'),
        ) as $key => $value) {
          echo "<option value='$key'";
          if ($key == $form_consumable) echo " selected";
          echo ">" . text($value) . "</option>";
        }
        ?></select>
     </td>
    </tr>

    <tr>
     <td nowrap>
      <select name='form_trans_type' onchange='transTypeChanged()'><?php
        foreach (array(
          '0' => xl('All Transaction Types'),
          '2' => xl('Purchase/Receipt'),
          '3' => xl('Return'),
          '1' => xl('Sale'),
          // '6' => xl('Distribution'),
          '4' => xl('Transfer'),
          '7' => xl('Consumption'),
          '5' => xl('Adjustment'),
        ) as $key => $value) {
          echo "<option value='$key'";
          if ($key == $form_trans_type) echo " selected";
          echo ">" . text($value) . "</option>";
        }
        ?></select>
     </td>
     <td nowrap>
      <?php echo xlt('From') . ': ' . genDateSelector('form_from_date', 'img_from_date', $form_from_date); ?>
     </td>
     <td nowrap>
      <?php echo xlt('To') . ': ' . genDateSelector('form_to_date', 'img_to_date', $form_to_date); ?>
     </td>
    </tr>

    <tr id='row_transfer'>
     <td nowrap>
      <?php echo genWarehouseSelector('form_from_wh', '-- ' . xl('From Any Warehouse') . ' --', $form_from_wh); ?>
     </td>
     <td nowrap>
      <?php echo genWarehouseSelector('form_to_wh', '-- ' . xl('To Any Warehouse') . ' --', $form_to_wh); ?>
     </td>
     <td nowrap>
      &nbsp;
     </td>
    </tr>

   </table>
  </td>

  <td align='left' valign='middle'>
   <a href='#' class='css_button' onclick='mysubmit("submit")' style='margin-left:1em'>
    <span><?php echo htmlspecialchars(xl('Submit'), ENT_NOQUOTES); ?></span>
   </a>
<?php if ($form_action) { ?>
   <a href='#' class='css_button' onclick='window.print()' style='margin-left:1em'>
    <span><?php echo htmlspecialchars(xl('Print'), ENT_NOQUOTES); ?></span>
   </a>
   <a href='#' class='css_button' onclick='mysubmit("export")' style='margin-left:1em'>
    <span><?php echo htmlspecialchars(xl('CSV Export'), ENT_NOQUOTES); ?></span>
   </a>
<?php } ?>
  </td>

 </tr>
</table>
</div>

<?php if ($form_action) { // if submit (already not export here) ?>

<div id="report_results">
<table border='0' cellpadding='1' cellspacing='2' width='98%' id='mymaintable' class='mymaintable'>
 <thead>
 <tr bgcolor="#dddddd">
  <td class="dehead">
   <a href="#" onclick="return dosort('date')"
   <?php if ($form_orderby == "date") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Date'); ?> </a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('tran')"
   <?php if ($form_orderby == "tran") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Transaction'); ?> </a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('prod')"
   <?php if ($form_orderby == "prod") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Product'); ?> </a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('lot')"
   <?php if ($form_orderby == "lot") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Lot'); ?>
   </a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('fac')"
   <?php if ($form_orderby == "fac") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Facility'); ?> </a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('wh')"
   <?php if ($form_orderby == "wh") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Warehouse'); ?> </a>
  </td>
<?php if ($include_sales_info) { ?>
  <td class="dehead">
   <a href="#" onclick="return dosort('invoice')"
   <?php if ($form_orderby == "invoice") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Invoice'); ?> </a>
  </td>
<?php } ?>
  <td class="dehead" align="right">
   <?php echo xlt('Qty'); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo xlt('Amount'); ?>
  </td>
<?php if ($include_sales_info) { ?>
  <td class="dehead" align="Center">
   <?php echo xlt('Billed'); ?>
  </td>
<?php } ?>
  <td class="dehead">
   <?php echo xlt('Notes'); ?>
  </td>
 </tr>
 </thead>
 <tbody>
<?php
} // end if submit
} // end not export

if ($form_action) { // if submit or export
  $from_date = $form_from_date;
  $to_date   = $form_to_date;

  $grandtotal = 0;
  $grandqty = 0;

  $query = "SELECT s.sale_date, s.fee, s.quantity, s.pid, s.encounter, " .
    "s.billed, s.notes, s.distributor_id, s.inventory_id, s.xfer_inventory_id, s.trans_type, " .
    "p.fname AS pfname, p.mname AS pmname, p.lname AS plname, " .
    "d.name, fe.date, fe.invoice_refno, " .
    "di.lot_number, di.warehouse_id, di.inventory_id AS di_inventory_id, " .
    "lo.title AS warehouse, lo.option_value AS facid, fac.name AS facname " .
    "FROM drug_sales AS s " .
    "JOIN drugs AS d ON d.drug_id = s.drug_id " .
    "LEFT JOIN patient_data AS p ON p.pid = s.pid " .
    "LEFT JOIN drug_inventory AS di ON di.inventory_id = s.inventory_id OR di.inventory_id = s.xfer_inventory_id " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND lo.option_id = di.warehouse_id AND lo.activity = 1 " .
    "LEFT JOIN facility AS fac ON fac.id = lo.option_value " .
    "LEFT JOIN form_encounter AS fe ON fe.pid = s.pid AND fe.encounter = s.encounter " .
    "WHERE s.sale_date >= ? AND s.sale_date <= ? AND " .
    "( s.pid = 0 OR s.inventory_id != 0 ) ";

  if ($form_trans_type == 2) { // purchase/receipt
    $query .= "AND s.pid = 0 AND s.xfer_inventory_id = 0 AND s.trans_type = 2 ";
  }
  else if ($form_trans_type == 3) { // return
    $query .= "AND s.pid = 0 AND s.xfer_inventory_id = 0 AND s.trans_type = 3 ";
  }
  else if ($form_trans_type == 4) { // transfer
    $query .= "AND s.xfer_inventory_id != 0 ";
  }
  else if ($form_trans_type == 5) { // adjustment
    $query .= "AND s.pid = 0 AND s.xfer_inventory_id = 0 AND s.trans_type = 5 ";
  }
  else if ($form_trans_type == 7) { // consumption
    $query .= "AND s.pid = 0 AND s.xfer_inventory_id = 0 AND s.trans_type = 7 ";
  }
  else if ($form_trans_type == 1) { // sale
    $query .= "AND s.pid != 0 ";
  }

  if ($form_facility) {
    $query .= "AND ((lo.option_value IS NOT NULL AND lo.option_value = '$form_facility')) ";
  }

  if ($form_product) {
    $query .= "AND s.drug_id = '$form_product' ";
  }

  if ($form_consumable) {
    if ($form_consumable == 1) {
      $query .= "AND d.consumable = '1' ";
    } else {
      $query .= "AND d.consumable != '1' ";
    }
  }

  $query .= "ORDER BY $orderby";

  $res = sqlStatement($query, array($from_date, $to_date));
  while ($row = sqlFetchArray($res)) {
    // Skip this row if user is disallowed from its warehouse.
    if ($is_user_restricted && !isWarehouseAllowed($row['facid'], $row['warehouse_id'])) {
      continue;
    }
    if ($form_trans_type == 4) { // reporting transfers only
      if ($form_from_wh) {
        $dirow = sqlQuery("SELECT warehouse_id FROM drug_inventory WHERE " .
          "inventory_id = ? AND warehouse_id = ?",
          array($row['xfer_inventory_id'], $form_from_wh));
        if (empty($dirow)) {
          continue;
        }
      }
      if ($form_to_wh) {
        $dirow = sqlQuery("SELECT warehouse_id FROM drug_inventory WHERE " .
          "inventory_id = ? AND warehouse_id = ?",
          array($row['inventory_id'], $form_to_wh));
        if (empty($dirow)) {
          continue;
        }
      }
    }
    thisLineItem($row);
  }

  // Grand totals line.
  if ($form_action != 'export') { // if submit
?>

 <tr bgcolor="#dddddd">
  <td class="dehead" colspan="7">
   <?php echo htmlspecialchars(xl('Grand Total'), ENT_NOQUOTES); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo htmlspecialchars($grandqty, ENT_NOQUOTES); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo htmlspecialchars(bucks($grandtotal), ENT_NOQUOTES); ?>
  </td>
  <td class="dehead" colspan="2">

  </td>
 </tr>

<?php
  } // End if submit
} // end if submit or export

if ($form_action != 'export') {
  if ($form_action) {
?>
 </tbody>
</table>
</div>
<?php
  } // end if ($form_action)
?>

<input type="hidden" name="form_orderby" value="<?php echo $form_orderby ?>" />

</form>
</center>
</body>

<!-- stuff for the popup calendar -->
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>

</html>
<?php
} // End not export
?>
