<?php
// Copyright (C) 2006-2017 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

$sanitize_all_escapes  = true;
$fake_register_globals = false;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("drugs.inc.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");

function QuotedOrNull($fld) {
  if ($fld) return "'".add_escape_custom($fld)."'";
  return "NULL";
}

function checkWarehouseUsed($warehouse_id) {
  global $drug_id;
  $row = sqlQuery("SELECT count(*) AS count FROM drug_inventory WHERE " .
    "drug_id = ? AND " .
    "destroy_date IS NULL AND warehouse_id = ?", array($drug_id,$warehouse_id) );
  return $row['count'];
}

function areVendorsUsed() {
  $row = sqlQuery("SELECT COUNT(*) AS count FROM users " .
      "WHERE active = 1 AND (info IS NULL OR info NOT LIKE '%Inactive%') " .
      "AND abook_type LIKE 'vendor%'");
  return $row['count'];
}

// Generate a <select> list of warehouses.
// If multiple lots are not allowed for this product, then restrict the
// list to warehouses that are unused for the product.
// Returns the number of warehouses allowed.
// For these purposes the "unassigned" option is considered a warehouse.
//
function genWarehouseList($tag_name, $currvalue, $title, $class='') {
  global $drug_id, $is_user_restricted;

  $drow = sqlQuery("SELECT allow_multiple FROM drugs WHERE drug_id = ?", array($drug_id));
  $allow_multiple = $drow['allow_multiple'];

  $lres = sqlStatement("SELECT * FROM list_options " .
    "WHERE list_id = 'warehouse' AND activity = 1 ORDER BY seq, title");

  echo "<select name='".attr($tag_name)."' id='".attr($tag_name)."'";
  if ($class) echo " class='".attr($class)."'";
  echo " title='".attr($title)."'>";

  $got_selected = FALSE;
  $count = 0;

  if ($allow_multiple /* || !checkWarehouseUsed('') */) {
    echo "<option value=''>" . xlt('Unassigned') . "</option>";
    ++$count;
  }

  while ($lrow = sqlFetchArray($lres)) {
    $whid = $lrow['option_id'];
    $facid = 0 + $lrow['option_value'];
    if ($whid != $currvalue) {
      if (!$allow_multiple && checkWarehouseUsed($whid)) continue;
      if ($is_user_restricted && !isWarehouseAllowed($facid, $whid)) continue;
    }
    // Value identifies both warehouse and facility to support validation.
    echo "<option value='$whid|$facid'";
    if ((strlen($currvalue) == 0 && $lrow['is_default']) ||
        (strlen($currvalue)  > 0 && $whid == $currvalue))
    {
      echo " selected";
      $got_selected = TRUE;
    }
    echo ">" . text($lrow['title']) . "</option>\n";
    ++$count;
  }

  if (!$got_selected && strlen($currvalue) > 0) {
    echo "<option value='".attr($currvalue)."' selected>* ".text($currvalue)." *</option>";
    echo "</select>";
    echo " <font color='red' title='" .
      xla('Please choose a valid selection from the list.') . "'>" .
      xlt('Fix this') . "!</font>";
  }
  else {
    echo "</select>";
  }

  return $count;
}

$drug_id = $_REQUEST['drug'] + 0;
$lot_id  = $_REQUEST['lot'] + 0;
$info_msg = "";

$form_trans_type = intval(isset($_POST['form_trans_type']) ? $_POST['form_trans_type'] : '0');

// Check authorizations.
$auth_admin = acl_check('admin', 'drugs');
$auth_lots  = $auth_admin               ||
  acl_check('inventory', 'lots'       ) ||
  acl_check('inventory', 'purchases'  ) ||
  acl_check('inventory', 'transfers'  ) ||
  acl_check('inventory', 'adjustments') ||
  acl_check('inventory', 'consumption') ||
  acl_check('inventory', 'destruction');
if (!$auth_lots) die(xlt('Not authorized'));
// Note if user is restricted to any facilities and/or warehouses.
$is_user_restricted = isUserRestricted();

if (!$drug_id) die(xlt('Drug ID missing!'));
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo $lot_id ? xlt("Edit") : xlt("Add New"); xlt('Lot','e',' '); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>
td { font-size:10pt; }
</style>

<style  type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>

<script language="JavaScript">

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 function validate() {
  var f = document.forms[0];
  var trans_type = f.form_trans_type.value;

  if (trans_type > '0') {
   // Transaction date validation. Must not be later than today or before 2000.
   if (f.form_sale_date.value > '<?php echo date('Y-m-d') ?>' || f.form_sale_date.value < '2000-01-01') {
    alert('<?php echo xls('Transaction date must not be in the future or before 2000'); ?>');
    return false;
   }
   // Quantity validations.
   var qty = parseInt(f.form_quantity.value);
   if (!qty) {
    alert('<?php echo xls('A quantity is required'); ?>');
    return false;
   }
   if (f.form_trans_type.value != '5' && qty < 0) {
    alert('<?php echo xls('Quantity cannot be negative for this transaction type'); ?>');
    return false;
   }
  }

  // Get source and target facility IDs.
  var facfrom = 0;
  var facto = 0;
  var a = f.form_source_lot.value.split('|', 2);
  var lotfrom = parseInt(a[0]);
  if (a.length > 1) facfrom = parseInt(a[1]);
  a = f.form_warehouse_id.value.split('|', 2);
  whid = a[0];
  if (a.length > 1) facto = parseInt(a[1]);

  if (lotfrom == '0' && f.form_lot_number.value.search(/\S/) < 0) {
   alert('<?php echo xls('A lot number is required'); ?>');
   return false;
  }

  // Require warehouse selection.
  if (whid == '') {
   alert('<?php echo xls('A warehouse is required'); ?>');
   return false;
  }

  // Require comments for all transactions.
  if (f.form_trans_type.value > '0' && f.form_notes.value.search(/\S/) < 0) {
   alert('<?php echo xls('Comments are required'); ?>');
   return false;
  }

  if (f.form_trans_type.value == '4') {
   // Transfers require a source lot.
   if (!lotfrom) {
    alert('<?php echo xls('A source lot is required'); ?>');
    return false;
   }
   // Check the case of a transfer between different facilities.
   if (facto != facfrom) {
    if (!confirm('<?php echo xls('Warning: Source and target facilities differ. Continue anyway?'); ?>'))
     return false;
   }
  }

  // Check for missing expiration date on a purchase or simple update.
  if (f.form_expiration.value == '' && f.form_trans_type.value <= '2') {
   if (!confirm('<?php echo xls('Warning: Most lots should have an expiration date. Continue anyway?'); ?>'))
    return false;
  }

  return true;
 }

 function trans_type_changed() {
  var f = document.forms[0];
  var sel = f.form_trans_type;
  var type = sel.options[sel.selectedIndex].value;

  // display attributes
  var showQuantity     = true;
  var showOnHand       = true;
  var showSaleDate     = true;
  var showCost         = true;
  var showSourceLot    = true;
  var showNotes        = true;
  var showManufacturer = true;
  var showLotNumber    = true;
  var showWarehouse    = true;
  var showExpiration   = true;
  var showVendor       = <?php echo areVendorsUsed() ? 'true' : 'false'; ?>;

  // readonly attributes
  var roManufacturer   = true;
  var roLotNumber      = true;
  var roExpiration     = true;

  labelWarehouse       = '<?php echo xlt('Warehouse'); ?>';

  if (type == '2') { // purchase
    showSourceLot  = false;
    roManufacturer = false;
    roLotNumber    = false;
    roExpiration   = false;
<?php if (!$lot_id) { // target lot is not known yet ?>
    showOnHand     = false;
<?php } ?>
  }
  else if (type == '3') { // return
    showSourceLot    = false;
    showManufacturer = false;
    showVendor       = false;
  }
  else if (type == '4') { // transfer
    showCost         = false;
    showManufacturer = false;
    showVendor       = false;
    showLotNumber    = false;
    showExpiration   = false;
<?php if ($lot_id) { // disallow warehouse change on xfer to existing lot ?>
    showWarehouse    = false;
<?php } else { // target lot is not known yet ?>
    showOnHand       = false;
<?php } ?>
    labelWarehouse = '<?php echo xlt('Destination Warehouse'); ?>';
  }
  else if (type == '5') { // adjustment
    showCost         = false;
    showSourceLot    = false;
    showManufacturer = false;
    showVendor       = false;
  }
  else if (type == '7') { // consumption
    showCost         = false;
    showSourceLot    = false;
    showManufacturer = false;
    showVendor       = false;
  }
  else {                  // Edit Only
    showQuantity   = false;
    showSaleDate   = false;
    showCost       = false;
    showSourceLot  = false;
    showNotes      = false;
    roManufacturer = false;
    roLotNumber    = false;
    roExpiration   = false;
  }
  document.getElementById('row_quantity'    ).style.display = showQuantity     ? '' : 'none';
  document.getElementById('row_on_hand'     ).style.display = showOnHand       ? '' : 'none';
  document.getElementById('row_sale_date'   ).style.display = showSaleDate     ? '' : 'none';
  document.getElementById('row_cost'        ).style.display = showCost         ? '' : 'none';
  document.getElementById('row_source_lot'  ).style.display = showSourceLot    ? '' : 'none';
  document.getElementById('row_notes'       ).style.display = showNotes        ? '' : 'none';
  document.getElementById('row_manufacturer').style.display = showManufacturer ? '' : 'none';
  document.getElementById('row_vendor'      ).style.display = showVendor       ? '' : 'none';
  document.getElementById('row_lot_number'  ).style.display = showLotNumber    ? '' : 'none';
  document.getElementById('row_warehouse'   ).style.display = showWarehouse    ? '' : 'none';
  document.getElementById('row_expiration'  ).style.display = showExpiration   ? '' : 'none';

  f.form_manufacturer.readOnly = roManufacturer;
  f.form_lot_number.readOnly   = roLotNumber;
  f.form_expiration.readOnly   = roExpiration;
  document.getElementById('img_expiration').style.display = roExpiration ? 'none' : '';

  document.getElementById('label_warehouse').innerHTML = labelWarehouse;
 }

</script>

</head>

<body class="body_top">
<?php
if ($lot_id) {
  $row = sqlQuery("SELECT * FROM drug_inventory WHERE drug_id = ? " .
    "AND inventory_id = ?", array($drug_id,$lot_id));
}

// If we are saving, then save and close the window.
//
if ($_POST['form_save']) {

  $form_quantity = $_POST['form_quantity'] + 0;
  $form_cost = sprintf('%0.2f', $_POST['form_cost']);
  // $form_source_lot = $_POST['form_source_lot'] + 0;

  list($form_source_lot, $form_source_facility) = explode('|', $_POST['form_source_lot']);
  $form_source_lot = intval($form_source_lot);

  list($form_warehouse_id) = explode('|', $_POST['form_warehouse_id']);

  $form_expiration   = $_POST['form_expiration'];
  $form_lot_number   = $_POST['form_lot_number'];
  $form_manufacturer = $_POST['form_manufacturer'];
  $form_vendor_id    = $_POST['form_vendor_id'];

  if ($form_trans_type < 0 || $form_trans_type > 7) die(text('Internal error!'));

  if (!$auth_admin && (
    $form_trans_type == 2 && !acl_check('inventory', 'purchases'  ) ||
    $form_trans_type == 3 && !acl_check('inventory', 'purchases'  ) ||
    $form_trans_type == 4 && !acl_check('inventory', 'transfers'  ) ||
    $form_trans_type == 5 && !acl_check('inventory', 'adjustments') ||
    $form_trans_type == 7 && !acl_check('inventory', 'consumption')
  )) {
    die(xlt('Not authorized'));
  }

  // Some fixups depending on transaction type.
  if ($form_trans_type == 3) { // return
    $form_quantity = 0 - $form_quantity;
    $form_cost = 0 - $form_cost;
  }
  else if ($form_trans_type == 5) { // adjustment
    $form_cost = 0;
  }
  else if ($form_trans_type == 7) { // consumption
    $form_quantity = 0 - $form_quantity;
    $form_cost = 0;
  }
  else if ($form_trans_type == 0) { // no transaction
    $form_quantity = 0;
    $form_cost = 0;
  }
  if ($form_trans_type != 4) { // not transfer
    $form_source_lot = 0;
  }

  // If a transfer, make sure there is sufficient quantity in the source lot
  // and apply some default values from it.
  if ($form_source_lot) {
    $srow = sqlQuery("SELECT lot_number, expiration, manufacturer, vendor_id, on_hand " .
      "FROM drug_inventory WHERE drug_id = ? AND inventory_id = ?",
      array($drug_id, $form_source_lot));

    if (empty($form_lot_number  )) $form_lot_number   = $srow['lot_number'  ];
    if (empty($form_expiration  )) $form_expiration   = $srow['expiration'  ];
    if (empty($form_manufacturer)) $form_manufacturer = $srow['manufacturer'];
    if (empty($form_vendor_id   )) $form_vendor_id    = $srow['vendor_id'   ];

    if ($form_quantity && $srow['on_hand'] < $form_quantity) {
      $info_msg = xl('Transfer failed, insufficient quantity in source lot');
    }
  }

  if (!$info_msg) {

    // If purchase or transfer with no destination lot specified, see if one already exists.
    if (!$lot_id && $form_lot_number && ($form_trans_type == 2 || $form_trans_type == 4)) {
      $erow = sqlQuery("SELECT * FROM drug_inventory WHERE " .
        "drug_id = ? AND warehouse_id = ? AND lot_number = ? AND destroy_date IS NULL " .
        "ORDER BY inventory_id DESC LIMIT 1",
        array($drug_id, $form_warehouse_id, $form_lot_number));
      if (!empty($erow['inventory_id'])) {
        // Yes a matching lot exists, use it and its values.
        $lot_id = $erow['inventory_id'];
        if (empty($form_expiration  )) $form_expiration   = $erow['expiration'  ];
        if (empty($form_manufacturer)) $form_manufacturer = $erow['manufacturer'];
        if (empty($form_vendor_id   )) $form_vendor_id    = $erow['vendor_id'   ];
      }
    }

    // Destination lot already exists.
    if ($lot_id) {
      if ($_POST['form_save']) {
        // Make sure the destination quantity will not end up negative.
        if (($row['on_hand'] + $form_quantity) < 0) {
          $info_msg = xl('Transaction failed, insufficient quantity in destination lot');
        }
        else {
          sqlStatement("UPDATE drug_inventory SET " .
            "lot_number = '"   . add_escape_custom($form_lot_number)    . "', " .
            "manufacturer = '" . add_escape_custom($form_manufacturer)  . "', " .
            "expiration = "    . QuotedOrNull($form_expiration)         . ", "  .
            "vendor_id = '"    . add_escape_custom($form_vendor_id)     . "', " .
            "warehouse_id = '" . add_escape_custom($form_warehouse_id)  . "', " .
            "on_hand = on_hand + '" . add_escape_custom($form_quantity) . "' "  .
            "WHERE drug_id = ? AND inventory_id = ?", array($drug_id,$lot_id) );
        }
      }
      else {
        sqlStatement("DELETE FROM drug_inventory WHERE drug_id = ? " .
          "AND inventory_id = ?", array($drug_id,$lot_id) );
      }
    }
    // Destination lot will be created.
    else {
      if ($form_quantity < 0) {
        $info_msg = xl('Transaction failed, quantity is less than zero');
      }
      else {
        $exptest = $form_expiration ?
          ("expiration = '" . add_escape_custom($form_expiration) . "'") : "expiration IS NULL";
        $crow = sqlQuery("SELECT count(*) AS count from drug_inventory " .
          "WHERE lot_number = '" . add_escape_custom($form_lot_number) . "' " .
          "AND drug_id = '"      . $drug_id                    . "' " .
          "AND warehouse_id = '" . $form_warehouse_id          . "' " .
          "AND $exptest " .
          "AND destroy_date IS NULL");
        if ($crow['count']) {
          $info_msg = xl('Transaction failed, duplicate lot');
        }
        else {
          $lot_id = sqlInsert("INSERT INTO drug_inventory ( " .
            "drug_id, lot_number, manufacturer, expiration, " .
            "vendor_id, warehouse_id, on_hand " .
            ") VALUES ( " .
            "'$drug_id', "                              .
            "'" . add_escape_custom($form_lot_number)   . "', " .
            "'" . add_escape_custom($form_manufacturer) . "', " .
            QuotedOrNull($form_expiration)              . ", "  .
            "'" . add_escape_custom($form_vendor_id)    . "', " .
            "'" . add_escape_custom($form_warehouse_id) . "', " .
            "'" . add_escape_custom($form_quantity)     . "' "  .
            ")");
        }
      }
    }

    // Create the corresponding drug_sales transaction.
    if ($_POST['form_save'] && $form_quantity && !$info_msg) {
      $form_notes = $_POST['form_notes'];
      $form_sale_date = $_POST['form_sale_date'];
      if (empty($form_sale_date)) $form_sale_date = date('Y-m-d');
      sqlInsert("INSERT INTO drug_sales ( " .
        "drug_id, inventory_id, prescription_id, pid, encounter, user, sale_date, " .
        "quantity, fee, xfer_inventory_id, distributor_id, notes, trans_type " .
        ") VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', " .
        "'" . add_escape_custom($lot_id) . "', '0', '0', '0', " .
        "'" . add_escape_custom($_SESSION['authUser']) . "', " .
        "'" . add_escape_custom($form_sale_date) . "', " .
        "'" . add_escape_custom(0 - $form_quantity)  . "', " .
        "'" . add_escape_custom(0 - $form_cost)      . "', " .
        "'" . add_escape_custom($form_source_lot) . "', " .
        "'0', " .
        "'" . add_escape_custom($form_notes) ."', " .
        "'" . add_escape_custom($form_trans_type)."' )");

      // If this is a transfer then reduce source QOH.
      if ($form_source_lot) {
        sqlStatement("UPDATE drug_inventory SET " .
          "on_hand = on_hand - ? " .
          "WHERE inventory_id = ?", array($form_quantity,$form_source_lot) );
      }
    }
  } // end if not $info_msg

  // Close this window and redisplay the updated list of drugs.
  //
  echo "<script language='JavaScript'>\n";
  if ($info_msg) echo " alert('".addslashes($info_msg)."');\n";
  echo " window.close();\n";
  echo " if (opener.refreshme) opener.refreshme();\n";
  echo "</script></body></html>\n";
  exit();
}
?>

<form method='post' name='theform' action='add_edit_lot.php?drug=<?php echo attr($drug_id) ?>&lot=<?php echo attr($lot_id) ?>'
 onsubmit='return validate()'>
<center>

<table border='0' width='100%'>

 <tr id='row_sale_date'>
  <td valign='top' nowrap><b><?php echo xlt('Date'); ?>:</b></td>
  <td>
   <input type='text' size='10' name='form_sale_date' id='form_sale_date'
    value='<?php echo attr(date('Y-m-d')) ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'
    title='<?php echo xla('yyyy-mm-dd date of purchase or transfer'); ?>' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_sale_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php echo xla('Click here to choose a date'); ?>'>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Transaction Type'); ?>:</b></td>
  <td>
   <select name='form_trans_type' onchange='trans_type_changed()'>
<?php
foreach (array(
  '2' => xl('Purchase/Receipt'),
  '3' => xl('Return'),
  '4' => xl('Transfer'),
  '5' => xl('Adjustment'),
  '7' => xl('Consumption'),
  '0' => xl('Edit Only'),
) as $key => $value)
{
  echo "<option value='" . attr($key) . "'";
  if (!$auth_admin && (
    $key == 2 && !acl_check('inventory', 'purchases'  ) ||
    $key == 3 && !acl_check('inventory', 'purchases'  ) ||
    $key == 4 && !acl_check('inventory', 'transfers'  ) ||
    $key == 5 && !acl_check('inventory', 'adjustments') ||
    $key == 7 && !acl_check('inventory', 'consumption')
  )) {
    echo " disabled";
  }
  else if (
    $lot_id  && in_array($key, array('2', '4')) ||
    // $lot_id  && in_array($key, array('2')) ||
    !$lot_id && in_array($key, array('0', '3', '5', '7'))
  ) {
    echo " disabled";
  }
  else {
    if (isset($_POST['form_trans_type']) && $key == $form_trans_type) echo " selected";
  }
  echo ">" . text($value) . "</option>\n";
}
?>
   </select>
  </td>
 </tr>

 <tr id='row_lot_number'>
  <td valign='top' width='1%' nowrap><b><?php echo xlt('Lot Number'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_lot_number' maxlength='40' value='<?php echo attr($row['lot_number']) ?>' style='width:100%' />
  </td>
 </tr>

 <tr id='row_manufacturer'>
  <td valign='top' nowrap><b><?php echo xlt('Manufacturer'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_manufacturer' maxlength='250' value='<?php echo attr($row['manufacturer']) ?>' style='width:100%' />
  </td>
 </tr>

 <tr id='row_expiration'>
  <td valign='top' nowrap><b><?php echo xlt('Expiration'); ?>:</b></td>
  <td>
   <input type='text' size='10' name='form_expiration' id='form_expiration'
    value='<?php echo attr($row['expiration']) ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'
    title='<?php echo xla('yyyy-mm-dd date of expiration'); ?>' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_expiration' border='0' alt='[?]' style='cursor:pointer'
    title='<?php echo xla('Click here to choose a date'); ?>'>
  </td>
 </tr>

 <tr id='row_source_lot'>
  <td valign='top' nowrap><b><?php echo xlt('Source Lot'); ?>:</b></td>
  <td>
   <select name='form_source_lot'>
    <option value='0'> </option>
<?php
$lres = sqlStatement("SELECT " .
  "di.inventory_id, di.lot_number, di.on_hand, lo.title, lo.option_value, di.warehouse_id " .
  "FROM drug_inventory AS di " .
  "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
  "lo.option_id = di.warehouse_id AND lo.activity = 1 " .
  "WHERE di.drug_id = ? AND di.inventory_id != ? AND " .
  "di.on_hand > 0 AND di.destroy_date IS NULL " .
  "ORDER BY di.lot_number, lo.title, di.inventory_id", array ($drug_id,$lot_id));
while ($lrow = sqlFetchArray($lres)) {
  // TBD: For transfer to an existing lot do we want to force the same lot number?
  // Check clinic/wh permissions.
  $facid = 0 + $lrow['option_value'];
  if ($is_user_restricted && !isWarehouseAllowed($facid, $lrow['warehouse_id'])) continue;
  echo "<option value='" . attr($lrow['inventory_id']) . '|' . attr($facid)  . "'>";
  echo text($lrow['lot_number']);
  if (!empty($lrow['title'])) echo " / " . text($lrow['title']);
  echo " (" . text($lrow['on_hand']) . ")";
  echo "</option>\n";
}
?>
   </select>
  </td>
 </tr>

 <tr id='row_vendor'>
  <td valign='top' nowrap><b><?php echo xlt('Vendor'); ?>:</b></td>
  <td>
<?php
// Address book entries for vendors.
generate_form_field(array('data_type' => 14, 'field_id' => 'vendor_id',
  'list_id' => '', 'edit_options' => 'V',
  'description' => xl('Address book entry for the vendor')),
  $row['vendor_id']);
?>
  </td>
 </tr>

 <tr id='row_warehouse'>
  <td valign='top' nowrap><b id='label_warehouse'><?php echo xlt('Warehouse'); ?>:</b></td>
  <td>
<?php
  if (!genWarehouseList("form_warehouse_id", $row['warehouse_id'],
    xl('Location of this lot')))
  {
    $info_msg = xl('This product allows only one lot per warehouse.');
  }
?>
  </td>
 </tr>

 <tr id='row_on_hand'>
  <td valign='top' nowrap><b><?php echo xlt('On Hand'); ?>:</b></td>
  <td>
   <?php echo text($row['on_hand'] + 0); ?>
  </td>
 </tr>

 <tr id='row_quantity'>
  <td valign='top' nowrap><b><?php echo xlt('Quantity'); ?>:</b></td>
  <td>
   <input type='text' size='5' name='form_quantity' maxlength='7' />
  </td>
 </tr>

 <tr id='row_cost'>
  <td valign='top' nowrap><b><?php echo xlt('Total Cost'); ?>:</b></td>
  <td>
   <input type='text' size='7' name='form_cost' maxlength='12' />
  </td>
 </tr>

 <tr id='row_notes' title='<?php echo xla('Include your initials and details of reason for transaction.'); ?>'>
  <td valign='top' nowrap><b><?php echo xlt('Comments'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_notes' maxlength='255' style='width:100%' />
  </td>
 </tr>

</table>

<p>
<input type='submit' name='form_save' value='<?php echo xla('Save'); ?>' />

<?php if ($lot_id && ($auth_admin || acl_check('inventory', 'destruction'))) { ?>
&nbsp;
<input type='button' value='<?php echo xla('Destroy...'); ?>'
 onclick="window.location.href='destroy_lot.php?drug=<?php echo attr($drug_id) ?>&lot=<?php echo attr($lot_id) ?>'" />
<?php } ?>

&nbsp;
<input type='button' value='<?php echo xla('Print'); ?>' onclick='window.print()' />

&nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick='window.close()' />
</p>

</center>
</form>
<script language='JavaScript'>
 Calendar.setup({inputField:"form_expiration", ifFormat:"%Y-%m-%d", button:"img_expiration"});
 Calendar.setup({inputField:"form_sale_date", ifFormat:"%Y-%m-%d", button:"img_sale_date"});
<?php
if ($info_msg) {
  echo " alert('".addslashes($info_msg)."');\n";
  echo " window.close();\n";
}
?>
trans_type_changed();
</script>
</body>
</html>
