<?php
 // Copyright (C) 2006-2014 Rod Roark <rod@sunsetsystems.com>
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
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/formatting.inc.php");
 require_once("$srcdir/htmlspecialchars.inc.php");

 // Check authorization.
 $thisauth = acl_check('admin', 'drugs');
 if (!$thisauth) die(xlt('Not authorized'));

// For each sorting option, specify the ORDER BY argument.
//
$ORDERHASH = array(
  'prod' => 'd.name, d.drug_id, di.expiration, di.lot_number',
  'ndc'  => 'd.ndc_number, d.name, d.drug_id, di.expiration, di.lot_number',
  'form' => 'lof.title, d.name, d.drug_id, di.expiration, di.lot_number',
  'lot'  => 'di.lot_number, d.name, d.drug_id, di.expiration',
  'wh'   => 'lo.title, d.name, d.drug_id, di.expiration, di.lot_number',
  'fac'  => 'f.name, d.name, d.drug_id, di.expiration, di.lot_number',    
  'qoh'  => 'di.on_hand, d.name, d.drug_id, di.expiration, di.lot_number',
  'exp'  => 'di.expiration, d.name, d.drug_id, di.lot_number',
);

$form_facility = 0 + empty($_REQUEST['form_facility']) ? 0 : $_REQUEST['form_facility'];
$form_show_empty = empty($_REQUEST['form_show_empty']) ? 0 : 1;

// Incoming form_warehouse, if not empty is in the form "warehouse/facility".
// The facility part is an attribute used by JavaScript logic.
$form_warehouse = empty($_REQUEST['form_warehouse']) ? '' : $_REQUEST['form_warehouse'];
$tmp = explode('/', $form_warehouse);
$form_warehouse = $tmp[0];

// Get the order hash array value and key for this request.
$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'prod';
$orderby = $ORDERHASH[$form_orderby];

$where = "WHERE 1 = 1";
if ($form_facility ) $where .= " AND lo.option_value IS NOT NULL AND lo.option_value = '$form_facility'";
if ($form_warehouse) $where .= " AND di.warehouse_id IS NOT NULL AND di.warehouse_id = '$form_warehouse'";

$dion = $form_show_empty ? "" : "AND di.on_hand != 0";

 // get drugs
 $res = sqlStatement("SELECT d.*, " .
  "di.inventory_id, di.lot_number, di.expiration, di.manufacturer, " .
  "di.on_hand, lo.title, lo.option_value AS facid, f.name AS facname " .
  "FROM drugs AS d " .
  "LEFT JOIN drug_inventory AS di ON di.drug_id = d.drug_id " .
  "AND di.destroy_date IS NULL $dion " .
  "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
  "lo.option_id = di.warehouse_id " .
  "LEFT JOIN facility AS f ON f.id = lo.option_value " .         
  "LEFT JOIN list_options AS lof ON lof.list_id = 'drug_form' AND " .
  "lof.option_id = d.form " .
  "$where ORDER BY $orderby");
?>
<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title><?php echo xlt('Drug Inventory'); ?></title>

<style>
tr.head   { font-size:10pt; background-color:#cccccc; text-align:center; }
tr.detail { font-size:10pt; }
a, a:visited, a:hover { color:#0000cc; }
</style>

<script type="text/javascript" src="../../library/dialog.js"></script>

<script language="JavaScript">

// callback from add_edit_drug.php or add_edit_drug_inventory.php:
function refreshme() {
 location.reload();
}

// Process click on drug title.
function dodclick(id) {
 dlgopen('add_edit_drug.php?drug=' + id, '_blank', 725, 475);
}

// Process click on drug QOO or lot.
function doiclick(id, lot) {
 dlgopen('add_edit_lot.php?drug=' + id + '&lot=' + lot, '_blank', 600, 475);
}

// Process click on a column header for sorting.
function dosort(orderby) {
 var f = document.forms[0];
 f.form_orderby.value = orderby;
 top.restoreSession();
 f.submit();
 return false;
}

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

<body class="body_top">
<form method='post' action='drug_inventory.php' onsubmit='return top.restoreSession()'>

    <table border='0' cellpadding='3' width='100%'>
 <tr>
  <td>
   <b><?php echo xlt('Inventory Management'); ?></b>
  </td>
  <td align='right'>
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
  echo "   </select>\n";

  echo "&nbsp;";
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
  echo "   </select>\n";
?>
   &nbsp;
   <input type='checkbox' name='form_show_empty' value='1'<?php if ($form_show_empty) echo " checked"; ?> />
   <?php echo xl('Show empty lots'); ?>
   &nbsp;
   <input type='submit' name='form_refresh' value="<?php xl('Refresh','e') ?>" />
  </td>
 </tr>
 <tr>
  <td height="1">
  </td>
 </tr>
</table>
    
<table width='100%' cellpadding='1' cellspacing='2'>
 <tr class='head'>
  <td title='<?php echo xla('Click to edit'); ?>'>
   <a href="#" onclick="return dosort('prod')"
   <?php if ($form_orderby == "prod") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Name'); ?> </a>
  </td>
  <td>
   <?php echo xlt('Act'); ?>
  </td>
  <td>
   <a href="#" onclick="return dosort('ndc')"
   <?php if ($form_orderby == "ndc") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('NDC'); ?> </a>
  </td>
  <td>
   <a href="#" onclick="return dosort('form')"
   <?php if ($form_orderby == "form") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Form'); ?> </a>
  </td>
  <td>
   <?php echo xlt('Size'); ?>
  </td>
  <td>
   <?php echo xlt('Unit'); ?>
  </td>
  <td title='<?php echo xla('Click to receive (add) new lot'); ?>'>
   <?php echo xlt('New'); ?>
  </td>
  <td title='<?php echo xla('Click to edit'); ?>'>
   <a href="#" onclick="return dosort('lot')"
   <?php if ($form_orderby == "lot") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Lot'); ?> </a>
  </td>
  <td>
   <a href="#" onclick="return dosort('fac')"
   <?php if ($form_orderby == "fac") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Facility'); ?> </a>
  </td>
  <td>
   <a href="#" onclick="return dosort('wh')"
   <?php if ($form_orderby == "wh") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Warehouse'); ?> </a>
  </td>
  <td>
   <a href="#" onclick="return dosort('qoh')"
   <?php if ($form_orderby == "qoh") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('QOH'); ?> </a>
  </td>
  <td>
   <a href="#" onclick="return dosort('exp')"
   <?php if ($form_orderby == "exp") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Expires'); ?> </a>
  </td>
 </tr>
<?php 
 $lastid = "";
 $encount = 0;
 $today = date('Y-m-d'); 
 while ($row = sqlFetchArray($res)) {
  if ($lastid != $row['drug_id']) {
   ++$encount;
   $bgcolor = "#" . (($encount & 1) ? "ddddff" : "ffdddd");
   $lastid = $row['drug_id'];
   echo " <tr class='detail' bgcolor='$bgcolor'>\n";
   echo "  <td onclick='dodclick(".attr($lastid).")'>" .
    "<a href='' onclick='return false'>" .
    text($row['name']) . "</a></td>\n";
   echo "  <td>" . ($row['active'] ? xlt('Yes') : xlt('No')) . "</td>\n";
   echo "  <td>" . text($row['ndc_number']) . "</td>\n";
   echo "  <td>" . 
	generate_display_field(array('data_type'=>'1','list_id'=>'drug_form'), $row['form']) .
	"</td>\n";
   echo "  <td>" . text($row['size']) . "</td>\n";
   echo "  <td>" .
	generate_display_field(array('data_type'=>'1','list_id'=>'drug_units'), $row['unit']) .
	"</td>\n";
   if ($row['dispensable']) {
    echo "  <td onclick='doiclick($lastid,0)' title='" . xla('Add new lot and transaction') . "'>" .
     "<a href='' onclick='return false'>" . xlt('New') . "</a></td>\n";
   }
   else {
    echo "  <td title='" . xlt('Inventory not enabled for this product') . "'>&nbsp;</td>\n";
   }
  } else {
   echo " <tr class='detail' bgcolor='$bgcolor'>\n";
   echo "  <td colspan='7'>&nbsp;</td>\n";
  }
  if (!empty($row['inventory_id'])) {
   $lot_number = htmlspecialchars($row['lot_number']);
   $expired = !empty($row['expiration']) && strcmp($row['expiration'], $today) <= 0;      
   echo "  <td onclick='doiclick(" . attr($lastid) . "," . attr($row['inventory_id']) . ")'>" .
    "<a href='' onclick='return false'>" . text($row['lot_number']) . "</a></td>\n";
   echo "  <td>" . ($row['facid'] ? text($row['facname']) : ('(' . xlt('Unassigned') . ')')) . "</td>\n";   
   echo "  <td>" . text($row['title']) . "</td>\n";
   echo "  <td>" . text($row['on_hand']) . "</td>\n";
   echo "  <td>";
   if ($expired) echo "<font color='red'>";
   echo oeFormatShortDate($row['expiration']);
   if ($expired) echo "</font>";
   echo "</td>\n";

  } else {
   echo "  <td colspan='5'>&nbsp;</td>\n";
  }
  echo " </tr>\n";
 } // end while
?>
</table>

<center><p>
 <input type='button' value='<?php echo xla('Add Drug'); ?>' onclick='dodclick(0)' style='background-color:transparent' />
</p></center>

<input type="hidden" name="form_orderby" value="<?php echo attr($form_orderby) ?>" />

</form>

<script language="JavaScript">
facchanged();
</script>

</body>
</html>
