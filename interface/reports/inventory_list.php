<?php
 // Copyright (C) 2008-2013 Rod Roark <rod@sunsetsystems.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

 require_once("../globals.php");
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/options.inc.php");
 require_once("$include_root/drugs/drugs.inc.php");

 // Check authorization.
 $thisauth = acl_check('admin', 'drugs');
 if (!$thisauth) die(xl('Not authorized'));

function addWarning($msg) {
  global $warnings;
  if ($warnings) $warnings .= '<br />';
  $warnings .= $msg;
}

// Check if a product needs to be re-ordered, optionally for a given warehouse.
//
function checkReorder($drug_id, $min, $warehouse='') {
  if (!$min) return false;

  // echo "<!-- Drug/min/warehouse = '$drug_id' '$min' '$warehouse' -->\n"; // debugging

  $query = "SELECT " .
    "SUM(s.quantity) AS sale_quantity " .
    "FROM drug_sales AS s " .
    "LEFT JOIN drug_inventory AS di ON di.inventory_id = s.inventory_id " .
    "WHERE " .
    "s.drug_id = '$drug_id' AND " .
    "s.sale_date > DATE_SUB(NOW(), INTERVAL 90 DAY) " .
    "AND s.pid != 0";
  if ($warehouse !== '') {
    $query .= " AND di.warehouse_id = '$warehouse'";
  }
  $srow = sqlQuery($query);
  $sales = 0 + $srow['sale_quantity'];

  $query = "SELECT SUM(on_hand) AS on_hand " .
    "FROM drug_inventory AS di WHERE " .
    "di.drug_id = '$drug_id' AND " .
    "di.expiration > NOW() AND " .
    "di.destroy_date IS NULL";
  if ($warehouse !== '') {
    $query .= " AND di.warehouse_id = '$warehouse'";
  }
  $ohrow = sqlQuery($query);
  $onhand = intval($ohrow['on_hand']);

  if (empty($GLOBALS['gbl_min_max_months'])) {
    if ($onhand <= $min) {
      return true;
    }
  }
  else {
    if ($sales != 0) {
      $stock_months = sprintf('%0.1f', $onhand * 3 / $sales);
      if ($stock_months <= $min) {
        return true;
      }
    }
  }

  return false;
}

function write_report_line(&$row) {
  global $form_details, $wrl_last_drug_id, $warnings, $encount, $fwcond, $form_days;

  $drug_id = 0 + $row['drug_id'];
  $on_hand = 0 + $row['on_hand'];
  $inventory_id = 0 + (empty($row['inventory_id']) ? 0 : $row['inventory_id']);
  $warehouse_id = isset($row['warehouse_id']) ? $row['warehouse_id'] : '';
  $warnings = '';

  if ($drug_id != $wrl_last_drug_id) ++$encount;
  $bgcolor = "#" . (($encount & 1) ? "ddddff" : "ffdddd");

  // Get sales in the date range for this drug (and warehouse if details).
  if ($form_details) {
    $query = "SELECT " .
      "SUM(s.quantity) AS sale_quantity " .
      "FROM drug_sales AS s " .
      "LEFT JOIN drug_inventory AS di ON di.inventory_id = s.inventory_id " .
      "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
      "lo.option_id = di.warehouse_id " .
      "WHERE " .
      "s.drug_id = '$drug_id' AND " .
      "di.warehouse_id = '$warehouse_id' AND " .
      "s.sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
      "AND s.pid != 0 $fwcond";
    $srow = sqlQuery($query);
    // echo "\n<!-- " . $srow['sale_quantity'] . " $query -->\n"; // debugging
  }
  else {
    $srow = sqlQuery("SELECT " .
      "SUM(s.quantity) AS sale_quantity " .
      "FROM drug_sales AS s " .
      "LEFT JOIN drug_inventory AS di ON di.inventory_id = s.inventory_id " .
      "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
      "lo.option_id = di.warehouse_id " .
      "WHERE " .
      "s.drug_id = '$drug_id' AND " .
      "s.sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
      "AND s.pid != 0 $fwcond");
  }
  $sale_quantity = $srow['sale_quantity'];

  $months = $form_days / 30.5;

  $monthly = ($months && $sale_quantity) ?
    sprintf('%0.1f', $sale_quantity / $months) : '&nbsp;';

  $stock_months = '&nbsp;';
  if ($sale_quantity != 0) {
    $stock_months = sprintf('%0.1f', $on_hand * $months / $sale_quantity);
    if ($stock_months < 1.0) {
      addWarning(xl('QOH is less than monthly usage'));
    }
  }

  // Check for reorder point reached, once per product.
  if ($drug_id != $wrl_last_drug_id) {
    if (checkReorder($drug_id, $row['reorder_point'])) {
      addWarning(xl('Product-level reorder point has been reached'));
    }
    // Same check for each warehouse.
    $pwres = sqlStatement("SELECT " .
      "pw.pw_warehouse, pw.pw_min_level, lo.title " .
      "FROM product_warehouse AS pw " .
      "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
      "lo.option_id = pw.pw_warehouse " .
      "WHERE pw.pw_drug_id = '$drug_id' AND pw.pw_min_level != 0 " .
      "ORDER BY lo.title");
    while ($pwrow = sqlFetchArray($pwres)) {
      if (checkReorder($drug_id, $pwrow['pw_min_level'], $pwrow['pw_warehouse'])) {
        addWarning(xl("Reorder point has been reached for warehouse") .
          " '" . $pwrow['title'] . "'");
      }
    }
  }

  // Compute the smallest quantity that might be taken from ANY lot for this product
  // (and warehouse if details) based on the past 30 days of sales.  If lot combining
  // is allowed this is always 1.
  $extracond = $form_details ? "AND di.warehouse_id = '$warehouse_id'" : $fwcond;
  $min_sale = 1;
  if (!$row['allow_combining']) {
    $sminrow = sqlQuery("SELECT " .
      "MIN(s.quantity) AS min_sale " .
      "FROM drug_sales AS s " .
      "LEFT JOIN drug_inventory AS di ON di.drug_id = s.drug_id " .
      "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
      "lo.option_id = di.warehouse_id " .
      "WHERE " .
      "s.drug_id = '$drug_id' AND " .
      "s.sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
      "AND s.pid != 0 " .
      "AND s.quantity > 0 $extracond");
    $min_sale = 0 + $sminrow['min_sale'];
  }
  // Get all lots that we want to issue warnings about.  These are lots
  // expired, soon to expire, or with insufficient quantity for selling.
  $ires = sqlStatement("SELECT di.* " .
    "FROM drug_inventory AS di " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    "lo.option_id = di.warehouse_id " .
    "WHERE " .
    "di.drug_id = '$drug_id' AND " .
    "di.on_hand > 0 AND " .
    "di.destroy_date IS NULL AND ( " .
    "di.on_hand < '$min_sale' OR " .
    "di.expiration IS NOT NULL AND di.expiration < DATE_ADD(NOW(), INTERVAL 30 DAY) " .
    ") $extracond ORDER BY di.lot_number");
  // Generate warnings associated with individual lots.
  while ($irow = sqlFetchArray($ires)) {
    $lotno = $irow['lot_number'];
    if ($irow['on_hand'] < $min_sale) {
      addWarning(xl('Lot') . " '$lotno' " . xl('quantity seems unusable'));
    }
    if (!empty($irow['expiration'])) {
      $expdays = (int) ((strtotime($irow['expiration']) - time()) / (60 * 60 * 24));
      if ($expdays <= 0) {
        addWarning(xl('Lot') . " '$lotno' " . xl('has expired'));
      }
      else if ($expdays <= 30) {
        addWarning(xl('Lot') . " '$lotno' " . xl('expires in') . " $expdays " . xl('days'));
      }
    }
  }

  echo " <tr class='detail' bgcolor='$bgcolor'>\n";

  if ($drug_id == $wrl_last_drug_id) {
    echo "  <td colspan='5'>&nbsp;</td>\n";
  }
  else {
    echo "  <td>" . htmlspecialchars($row['name']) . "</td>\n";
    echo "  <td>" . htmlspecialchars($row['ndc_number']) . "</td>\n";
    echo "  <td>" . ($row['active'] ? xl('Yes') : xl('No')) . "</td>\n";
    echo "  <td>" .
         generate_display_field(array('data_type'=>'1','list_id'=>'drug_form'), $row['form']) .
         "</td>\n";
    echo "  <td align='right'>" . $row['reorder_point'] . "</td>\n";
  }
  if ($form_details) {
    echo "  <td>" . htmlspecialchars($row['title']) . "</td>\n";
    echo "  <td align='right'>" . htmlspecialchars($row['pw_min_level']) . "</td>\n";
    echo "  <td align='right'>" . htmlspecialchars($row['pw_max_level']) . "</td>\n";
  }
  echo "  <td align='right'>" . $row['on_hand'] . "</td>\n";
  echo "  <td align='right'>$monthly</td>\n";
  echo "  <td align='right'>$stock_months</td>\n";
  echo "  <td style='color:red'>$warnings</td>\n";
  echo " </tr>\n";

  $wrl_last_drug_id = $drug_id;
}

if (!empty($_POST['form_days'])) {
  $form_days = $_POST['form_days'] + 0;
}
else {
  $form_days = sprintf('%d', (strtotime(date('Y-m-d')) - strtotime(date('Y-01-01'))) / (60 * 60 * 24) + 1);
}

$form_inactive = empty($_REQUEST['form_inactive']) ? 0 : 1;

$form_details = empty($_REQUEST['form_details']) ? 0 : 1;

$form_facility = 0 + empty($_REQUEST['form_facility']) ? 0 : $_REQUEST['form_facility'];

// Incoming form_warehouse, if not empty is in the form "warehouse/facility".
// The facility part is an attribute used by JavaScript logic.
$form_warehouse = empty($_REQUEST['form_warehouse']) ? '' : $_REQUEST['form_warehouse'];
$tmp = explode('/', $form_warehouse);
$form_warehouse = $tmp[0];

$mmtype = $GLOBALS['gbl_min_max_months'] ? xl('Months') : xl('Units');

// Compute WHERE condition for filtering on facility/warehouse.
$fwcond = '';
if ($form_facility) $fwcond .=
  " AND lo.option_value IS NOT NULL AND lo.option_value = '$form_facility'";
if ($form_warehouse) $fwcond .=
  " AND di.warehouse_id IS NOT NULL AND di.warehouse_id = '$form_warehouse'";

// Compute WHERE condition for filtering on activity.
$actcond = '';
if (!$form_inactive) $actcond .=
  " AND d.active = 1";

if ($form_details) {
  // Query for the main loop if lot details are wanted.
  $query = "SELECT d.*, di.on_hand, di.inventory_id, di.lot_number, " .
    "di.expiration, di.warehouse_id, lo.title, " .
    "pw.pw_min_level, pw.pw_max_level " .
    "FROM drugs AS d " .
    "LEFT JOIN drug_inventory AS di ON di.drug_id = d.drug_id " .
    "AND di.on_hand != 0 AND di.destroy_date IS NULL " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    "lo.option_id = di.warehouse_id " .
    "LEFT JOIN product_warehouse AS pw ON pw.pw_drug_id = d.drug_id AND " .
    "pw.pw_warehouse = di.warehouse_id " .
    "WHERE 1 = 1 $fwcond$actcond " .
    "ORDER BY d.name, d.drug_id, lo.title, di.warehouse_id, di.lot_number, di.inventory_id";
}
else {
  // Query for the main loop if summary report.
  $query = "SELECT d.*, SUM(di.on_hand) AS on_hand " .
    "FROM drugs AS d " .
    "LEFT JOIN drug_inventory AS di ON di.drug_id = d.drug_id " .
    "AND di.on_hand != 0 AND di.destroy_date IS NULL " .
    // Join with list_options needed to support facility filter ($fwcond).
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    "lo.option_id = di.warehouse_id " .
    "WHERE 1 = 1 $fwcond$actcond " .
    "GROUP BY d.name, d.drug_id ORDER BY d.name, d.drug_id";
}

$res = sqlStatement($query);
?>
<html>

<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title><?php  xl('Inventory List','e'); ?></title>

<style>
tr.head   { font-size:10pt; background-color:#cccccc; text-align:center; }
tr.detail { font-size:10pt; }
a, a:visited, a:hover { color:#0000cc; }
</style>

<script type="text/javascript" src="../../library/dialog.js"></script>

<script language="JavaScript">

// Enable/disable warehouse options depending on current facility.
function facchanged() {
 var f = document.forms[0];
 var facid = f.form_facility.value;
 var theopts = f.form_warehouse.options;
 for (var i = 1; i < theopts.length; ++i) {
  var tmp = theopts[i].value.split('/');
  var dis = facid && (tmp.length < 2 || tmp[1] != facid);
  theopts[i].disabled = dis;
  if (dis) theopts[i].selected = false;
 }
}

</script>

</head>

<body>
<center>

<form method='post' action='inventory_list.php' name='theform'>
<table border='0' cellpadding='5' cellspacing='0' width='98%'>
 <tr>
  <td class='title'>
   <?php xl('Inventory List','e'); ?>
  </td>
  <td class='text' align='right'>
<?php
  // Build a drop-down list of facilities.
  //
  $query = "SELECT id, name FROM facility ORDER BY name";
  $fres = sqlStatement($query);
  echo "   <select name='form_facility' onchange='facchanged()'>\n";
  echo "    <option value=''>-- " . xl('All Facilities') . " --\n";
  while ($frow = sqlFetchArray($fres)) {
    $facid = $frow['id'];
    echo "    <option value='$facid'";
    if ($facid == $form_facility) echo " selected";
    echo ">" . $frow['name'] . "\n";
  }
  echo "   </select>&nbsp;\n";

  echo "   <select name='form_warehouse'>\n";
  echo "    <option value=''>" . xl('All Warehouses') . "</option>\n";
  $lres = sqlStatement("SELECT * FROM list_options " .
    "WHERE list_id = 'warehouse' ORDER BY seq, title");
  while ($lrow = sqlFetchArray($lres)) {
    echo "    <option value='" . $lrow['option_id'] . "/" . $lrow['option_value'] . "'";
    echo " id='fac" . $lrow['option_value'] . "'";
    if (strlen($form_warehouse)  > 0 && $lrow['option_id'] == $form_warehouse) {
      echo " selected";
    }
    echo ">" . xl_list_label($lrow['title']) . "</option>\n";
  }
  echo "   </select>&nbsp;\n";
?>
   <?php xl('For the past','e'); ?>
   <input type="input" name="form_days" size='3' value="<?php echo $form_days; ?>" />
   <?php xl('days','e'); ?>&nbsp;
   <input type='checkbox' name='form_inactive' value='1'<?php if ($form_inactive) echo " checked"; ?>
   /><?php xl('Include Inactive','e'); ?>&nbsp;
   <input type='checkbox' name='form_details' value='1'<?php if ($form_details) echo " checked"; ?>
   /><?php xl('Details','e'); ?>&nbsp;
   <input type="submit" value="<?php xl('Refresh','e'); ?>" />&nbsp;
   <input type="button" value="<?php xl('Print','e'); ?>" onclick="window.print()" />
  </td>
 </tr>
</table>
</form>

<table width='98%' cellpadding='2' cellspacing='2'>
 <thead style='display:table-header-group'>
  <tr class='head'>
   <th><?php  xl('Name','e'); ?></th>
   <th><?php  xl('NDC','e'); ?></th>
   <th><?php  xl('Active','e'); ?></th>
   <th><?php  xl('Form','e'); ?></th>
   <th align='right'><?php  xl('Reorder','e'); ?></th>
<?php if ($form_details) { ?>
   <th><?php  xl('Warehouse','e'); ?></th>
   <th align='right'><?php echo "$mmtype " . xl('Min'); ?></th>
   <th align='right'><?php echo "$mmtype " . xl('Max'); ?></th>
<?php } ?>
   <th align='right'><?php  xl('QOH','e'); ?></th>
   <th align='right'><?php  xl('Avg Monthly','e'); ?></th>
   <th align='right'><?php  xl('Stock Months','e'); ?></th>
   <th><?php xl('Warnings','e'); ?></th>
  </tr>
 </thead>
 <tbody>

<?php 
$encount = 0;
$last_drug_id = '';
$wrl_last_drug_id = '';
$warehouse_row = array('drug_id' => 0, 'warehouse_id' => '');

while ($row = sqlFetchArray($res)) {
  $drug_id = 0 + $row['drug_id'];

  if ($form_details) {
    if ($drug_id != $last_drug_id || $row['warehouse_id'] != $warehouse_row['warehouse_id']) {
      if (!empty($warehouse_row['drug_id'])) {
        write_report_line($warehouse_row);
      }
      $warehouse_row = $row;
      $warehouse_row['on_hand'] = 0;
    }
    $warehouse_row['on_hand'] += $row['on_hand'];
  }
  else {
    write_report_line($row);
  }

  /*******************************************************************
  $on_hand = 0 + $row['on_hand'];
  $inventory_id = 0 + empty($row['inventory_id']) ? 0 : $row['inventory_id'];
  $warnings = '';

  // Get sales in the date range for this lot (if details) or drug.
  if ($form_details) {
    $srow = sqlQuery("SELECT " .
      "SUM(s.quantity) AS sale_quantity " .
      "FROM drug_sales AS s " .
      "WHERE " .
      "s.drug_id = '$drug_id' AND " .
      "s.inventory_id = '$inventory_id' AND " .
      "s.sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
      "AND s.pid != 0");
  }
  else {
    $srow = sqlQuery("SELECT " .
      "SUM(s.quantity) AS sale_quantity " .
      "FROM drug_sales AS s " .
      "LEFT JOIN drug_inventory AS di ON di.drug_id = s.drug_id " .
      "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
      "lo.option_id = di.warehouse_id " .
      "WHERE " .
      "s.drug_id = '$drug_id' AND " .
      "s.sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
      "AND s.pid != 0 $fwcond");
  }
  $sale_quantity = $srow['sale_quantity'];

  $months = $form_days / 30.5;

  $monthly = ($months && $sale_quantity) ?
    sprintf('%0.1f', $sale_quantity / $months) : '&nbsp;';

  $stock_months = '&nbsp;';
  if ($sale_quantity != 0) {
    $stock_months = sprintf('%0.1f', $on_hand * $months / $sale_quantity);
    if ($stock_months < 1.0) {
      addWarning(xl('QOH is less than monthly usage'));
    }
  }

  // Check for reorder point reached, once per product.
  if ($drug_id != $last_drug_id) {
    if (checkReorder($drug_id, $row['reorder_point'])) {
      addWarning(xl('Product-level reorder point has been reached'));
    }
    // Same check for each warehouse.
    $pwres = sqlStatement("SELECT " .
      "pw.pw_warehouse, pw.pw_min_level, lo.title " .
      "FROM product_warehouse AS pw " .
      "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
      "lo.option_id = pw.pw_warehouse " .
      "WHERE pw.pw_drug_id = '$drug_id' AND pw.pw_min_level != 0 " .
      "ORDER BY lo.title");
    while ($pwrow = sqlFetchArray($pwres)) {
      if (checkReorder($drug_id, $pwrow['pw_min_level'], $pwrow['pw_warehouse'])) {
        addWarning(xl("Reorder point has been reached for warehouse") .
          " '" . $pwrow['title'] . "'");
      }
    }
  }

  if ($form_details) {
    // Compute the smallest quantity that might be taken from THIS lot based on the
    // past 30 days of sales.  If lot combining is allowed this is always 1.
    $min_sale = 1;
    if (!$row['allow_combining']) {
      $sminrow = sqlQuery("SELECT " .
        "MIN(s.quantity) AS min_sale " .
        "FROM drug_sales AS s " .
        "WHERE " .
        "s.drug_id = '$drug_id' AND " .
        "s.inventory_id = '$inventory_id' AND " .
        "s.sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
        "AND s.pid != 0 " .
        "AND s.quantity > 0");
      $min_sale = 0 + $sminrow['min_sale'];
    }
    $lotno = $row['lot_number'];
    if ($row['on_hand'] < $min_sale) {
      addWarning(xl('Lot') . " '$lotno' " . xl('quantity seems unusable'));
    }
    if (!empty($row['expiration'])) {
      $expdays = (int) ((strtotime($row['expiration']) - time()) / (60 * 60 * 24));
      if ($expdays <= 0) {
        addWarning(xl('Lot') . " '$lotno' " . xl('has expired'));
      }
      else if ($expdays <= 30) {
        addWarning(xl('Lot') . " '$lotno' " . xl('expires in') . " $expdays " . xl('days'));
      }
    }
  }
  else {
    // Compute the smallest quantity that might be taken from ANY lot for this product
    // based on the past 30 days of sales.  If lot combining is allowed this is always 1.
    $min_sale = 1;
    if (!$row['allow_combining']) {
      $sminrow = sqlQuery("SELECT " .
        "MIN(s.quantity) AS min_sale " .
        "FROM drug_sales AS s " .
        "LEFT JOIN drug_inventory AS di ON di.drug_id = s.drug_id " .
        "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
        "lo.option_id = di.warehouse_id " .
        "WHERE " .
        "s.drug_id = '$drug_id' AND " .
        "s.sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
        "AND s.pid != 0 " .
        "AND s.quantity > 0 $fwcond");
      $min_sale = 0 + $sminrow['min_sale'];
    }
    // Get all lots that we want to issue warnings about.  These are lots
    // expired, soon to expire, or with insufficient quantity for selling.
    $ires = sqlStatement("SELECT di.* " .
      "FROM drug_inventory AS di " .
      "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
      "lo.option_id = di.warehouse_id " .
      "WHERE " .
      "di.drug_id = '$drug_id' AND " .
      "di.on_hand > 0 AND " .
      "di.destroy_date IS NULL AND ( " .
      "di.on_hand < '$min_sale' OR " .
      "di.expiration IS NOT NULL AND di.expiration < DATE_ADD(NOW(), INTERVAL 30 DAY) " .
      ") $fwcond ORDER BY di.lot_number");
    // Generate warnings associated with individual lots.
    while ($irow = sqlFetchArray($ires)) {
      $lotno = $irow['lot_number'];
      if ($irow['on_hand'] < $min_sale) {
        addWarning(xl('Lot') . " '$lotno' " . xl('quantity seems unusable'));
      }
      if (!empty($irow['expiration'])) {
        $expdays = (int) ((strtotime($irow['expiration']) - time()) / (60 * 60 * 24));
        if ($expdays <= 0) {
          addWarning(xl('Lot') . " '$lotno' " . xl('has expired'));
        }
        else if ($expdays <= 30) {
          addWarning(xl('Lot') . " '$lotno' " . xl('expires in') . " $expdays " . xl('days'));
        }
      }
    }
  } // end not details
  *******************************************************************/

  /*******************************************************************
  echo " <tr class='detail' bgcolor='$bgcolor'>\n";
  if ($drug_id == $last_drug_id) {
    echo "  <td colspan='5'>&nbsp;</td>\n";
  }
  else {
    echo "  <td>" . htmlentities($row['name']) . "</td>\n";
    echo "  <td>" . htmlentities($row['ndc_number']) . "</td>\n";
    echo "  <td>" . ($row['active'] ? xl('Yes') : xl('No')) . "</td>\n";
    echo "  <td>" .
         generate_display_field(array('data_type'=>'1','list_id'=>'drug_form'), $row['form']) .
         "</td>\n";
    echo "  <td align='right'>" . $row['reorder_point'] . "</td>\n";
  }
  if ($form_details) {
    echo "  <td>" . htmlentities($row['title']) . "</td>\n";
    echo "  <td align='right'>" . htmlentities($row['pw_min_level']) . "</td>\n";
    echo "  <td align='right'>" . htmlentities($row['pw_max_level']) . "</td>\n";
  }
  echo "  <td align='right'>" . $row['on_hand'] . "</td>\n";
  echo "  <td align='right'>$monthly</td>\n";
  echo "  <td align='right'>$stock_months</td>\n";
  echo "  <td style='color:red'>$warnings</td>\n";
  echo " </tr>\n";
  *******************************************************************/

  $last_drug_id = $drug_id;
}

if ($form_details) {
  if (!empty($warehouse_row['drug_id'])) {
    write_report_line($warehouse_row);
  }
}
?>
 </tbody>
</table>

</center>

<script language="JavaScript">
facchanged();
</script>

</body>
</html>
