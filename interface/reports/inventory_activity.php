<?php
// Copyright (C) 2010-2017 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// Report columns:
// Product Name (blank where repeated)
// Warehouse Name (blank where repeated) or Total for Product
// Starting Inventory (detail lines: date)
// Ending Inventory   (detail lines: invoice ID)
// Sales
// Distributions (removed)
// Purchases
// Transfers

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/sql-ledger.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("../drugs/drugs.inc.php");

function display_desc($desc) {
  if (preg_match('/^\S*?:(.+)$/', $desc, $matches)) {
    $desc = $matches[1];
  }
  return $desc;
}

// Specify if product or warehouse is the first column.
$product_first = (!empty($_POST['form_by']) && $_POST['form_by'] == 'w') ? 0 : 1;

// The selected facility ID, if any.
$form_facility = 0 + empty($_REQUEST['form_facility']) ? 0 : $_REQUEST['form_facility'];

// The selected warehouse ID, if any.
$form_warehouse = empty($_REQUEST['form_warehouse']) ? '' : $_REQUEST['form_warehouse'];
$tmp = explode('/', $form_warehouse);
$form_warehouse = $tmp[0];

$last_warehouse_id = '~';
$last_product_id = 0;

// Get ending inventory for the report's end date.
// Optionally restricts by product ID and/or warehouse ID.
function getEndInventory($product_id = 0, $warehouse_id = '~') {
  global $form_from_date, $form_to_date, $form_product, $form_facility;

  $whidcond = '';
  if ($warehouse_id !== '~') {
    $whidcond = $warehouse_id === '' ?
      "AND ( di.warehouse_id IS NULL OR di.warehouse_id = '' )" :
      "AND di.warehouse_id = '$warehouse_id'";
  }

  $prodcond = '';
  if ($form_product) $product_id = $form_product;
  if ($product_id) {
    $prodcond = "AND di.drug_id = '$product_id'";
  }

  $faccond = '';
  if ($form_facility) {
    $faccond = "AND lo.option_value IS NOT NULL AND lo.option_value = '$form_facility'";
  }

  // Get sum of current inventory quantities + destructions done after the
  // report end date (which is effectively a type of transaction).
  $eirow = sqlQuery("SELECT sum(di.on_hand) AS on_hand " .
    "FROM drug_inventory AS di " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    "lo.option_id = di.warehouse_id AND lo.activity = 1 " .
    "WHERE " .
    "( di.destroy_date IS NULL OR di.destroy_date > '$form_to_date' ) " .
    "$prodcond $whidcond $faccond");

  // Get sum of sales/adjustments/consumptions/purchases after the report end date.
  $sarow = sqlQuery("SELECT sum(ds.quantity) AS quantity " .
    "FROM drug_sales AS ds, drug_inventory AS di " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    "lo.option_id = di.warehouse_id AND lo.activity = 1 " .
    "WHERE " .
    "ds.sale_date > '$form_to_date' AND " .
    "di.inventory_id = ds.inventory_id " .
    "$prodcond $whidcond $faccond");

  // Get sum of transfers out after the report end date.
  $xfrow = sqlQuery("SELECT sum(ds.quantity) AS quantity " .
    "FROM drug_sales AS ds, drug_inventory AS di " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    "lo.option_id = di.warehouse_id AND lo.activity = 1 " .
    "WHERE " .
    "ds.sale_date > '$form_to_date' AND " .
    "di.inventory_id = ds.xfer_inventory_id " .
    "$prodcond $whidcond $faccond");

  return $eirow['on_hand'] + $sarow['quantity'] - $xfrow['quantity'];
}

function thisLineItem($product_id, $warehouse_id, $patient_id, $encounter_id,
  $rowprod, $rowwh, $transdate, $qtys, $irnumber='')
{
  global $warehouse, $product, $secqtys, $priqtys, $grandqtys;
  global $whleft, $prodleft; // left 2 columns, blank where repeated
  global $last_warehouse_id, $last_product_id, $product_first;

  $invnumber = empty($irnumber) ? ($patient_id ? "$patient_id.$encounter_id" : "") : $irnumber;

  // Product name for this detail line item.
  if (empty($rowprod)) $rowprod = xl('Unnamed Product');

  // Warehouse name for this line item.
  if (empty($rowwh)) $rowwh = xl('None');

  // If new warehouse or product...
  if ($warehouse_id != $last_warehouse_id || $product_id != $last_product_id) {

    // If there was anything to total...
    if (($product_first && $last_warehouse_id != '~') || (!$product_first && $last_product_id)) {

      $secei = getEndInventory($last_product_id, $last_warehouse_id);

      // Print second-column totals.
      if ($_POST['form_csvexport']) {
        // Export:
        if (! $_POST['form_details']) {
          if ($product_first) {
            echo '"'  . display_desc($product)   . '"';
            echo ',"' . display_desc($warehouse) . '"';
          } else {
            echo '"'  . display_desc($warehouse) . '"';
            echo ',"' . display_desc($product)   . '"';
          }
          echo ',"' . ($secei - $secqtys[0] - $secqtys[1] - $secqtys[2] - $secqtys[6] - $secqtys[3] - $secqtys[4] - $secqtys[5]) . '"'; // start inventory
          echo ',"' . $secqtys[0] . '"'; // sales
          // echo ',"' . $secqtys[1] . '"'; // distributions
          echo ',"' . $secqtys[2] . '"'; // purchases
          echo ',"' . $secqtys[6] . '"'; // returns
          echo ',"' . $secqtys[3] . '"'; // transfers
          echo ',"' . $secqtys[4] . '"'; // adjustments
          echo ',"' . $secqtys[5] . '"'; // consumptions
          echo ',"' . $secei      . '"'; // end inventory
          echo "\n";
        }
      }
      else {
        // Not export:
?>
 <tr bgcolor="#ddddff">
<?php if ($product_first) { ?>
  <td class="detail">
   <?php echo display_desc($prodleft); $prodleft = "&nbsp;"; ?>
  </td>
  <td class="detail" colspan='3'>
   <?php if ($_POST['form_details']) echo xl('Total for') . ' '; echo display_desc($warehouse); ?>
  </td>
<?php } else { ?>
  <td class="detail">
   <?php echo display_desc($whleft); $whleft = "&nbsp;"; ?>
  </td>
  <td class="detail" colspan='3'>
   <?php if ($_POST['form_details']) echo xl('Total for') . ' '; echo display_desc($product); ?>
  </td>
<?php } ?>
  <td class="detail" align="right">
   <?php echo $secei - $secqtys[0] - $secqtys[1] - $secqtys[2] - $secqtys[6] - $secqtys[3] - $secqtys[4] - $secqtys[5]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $secqtys[0]; ?>
  </td>
  <!--
  <td class="detail" align="right">
   <?php echo $secqtys[1]; ?>
  </td>
  -->
  <td class="detail" align="right">
   <?php echo $secqtys[2]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $secqtys[6]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $secqtys[3]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $secqtys[4]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $secqtys[5]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $secei; ?>
  </td>
 </tr>
<?php
      } // End not csv export
    }
    $secqtys = array(0, 0, 0, 0, 0, 0, 0);
    if ($product_first ) {
      $whleft = $warehouse = $rowwh;
      $last_warehouse_id = $warehouse_id;
    } else {
      $prodleft = $product = $rowprod;
      $last_product_id = $product_id;
    }
  }

  // If first column is changing, time for its totals.
  if (($product_first && $product_id != $last_product_id) ||
      (!$product_first && $warehouse_id != $last_warehouse_id))
  {
    if (($product_first && $last_product_id) ||
        (!$product_first && $last_warehouse_id != '~'))
    {
      $priei = $product_first ? getEndInventory($last_product_id) :
        getEndInventory(0, $last_warehouse_id);
      // Print first column total.
      if (!$_POST['form_csvexport']) {
?>

 <tr bgcolor="#ffdddd">
  <td class="detail">
   &nbsp;
  </td>
  <td class="dehead" colspan="3">
   <?php echo xl('Total for') . ' '; echo display_desc($product_first ? $product : $warehouse); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $priei - $priqtys[0] - $priqtys[1] - $priqtys[2] - $priqtys[6] - $priqtys[3] - $priqtys[4] - $priqtys[5]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $priqtys[0]; ?>
  </td>
  <!--
  <td class="dehead" align="right">
   <?php echo $priqtys[1]; ?>
  </td>
  -->
  <td class="dehead" align="right">
   <?php echo $priqtys[2]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $priqtys[6]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $priqtys[3]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $priqtys[4]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $priqtys[5]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $priei; ?>
  </td>
 </tr>
<?php
      } // End not csv export
    }
    $priqtys = array(0, 0, 0, 0, 0, 0, 0);
    if ($product_first) {
      $prodleft = $product = $rowprod;
      $last_product_id = $product_id;
    } else {
      $whleft = $warehouse = $rowwh;
      $last_warehouse_id = $warehouse_id;
    }
  }

  // Detail line.
  if ($_POST['form_details'] && $product_id && ($qtys[0] + $qtys[1] + $qtys[2] + $qtys[6] + $qtys[3] + $qtys[4] + $qtys[5])) {
    if ($_POST['form_csvexport']) {
      if ($product_first) {
        echo '"'  . display_desc($product )  . '"';
        echo ',"' . display_desc($warehouse) . '"';
      } else {
        echo '"'  . display_desc($warehouse) . '"';
        echo ',"' . display_desc($product)   . '"';
      }
      echo ',"' . oeFormatShortDate(display_desc($transdate)) . '"';
      echo ',"' . display_desc($invnumber) . '"';
      echo ',"' . $qtys[0]             . '"'; // sales
      // echo ',"' . $qtys[1]             . '"'; // distributions
      echo ',"' . $qtys[2]             . '"'; // purchases
      echo ',"' . $qtys[6]             . '"'; // returns
      echo ',"' . $qtys[3]             . '"'; // transfers
      echo ',"' . $qtys[4]             . '"'; // adjustments
      echo ',"' . $qtys[5]             . '"'; // consumptions
      echo "\n";
    }
    else {
?>
 <tr>
<?php if ($product_first) { ?>
  <td class="detail">
   <?php echo display_desc($prodleft); $prodleft = "&nbsp;"; ?>
  </td>
  <td class="detail">
   <?php echo display_desc($whleft); $whleft = "&nbsp;"; ?>
  </td>
<?php } else { ?>
  <td class="detail">
   <?php echo display_desc($whleft); $whleft = "&nbsp;"; ?>
  </td>
  <td class="detail">
   <?php echo display_desc($prodleft); $prodleft = "&nbsp;"; ?>
  </td>
<?php } ?>
  <td class="detail">
   <?php echo oeFormatShortDate($transdate); ?>
  </td>
<?php
  if ($patient_id) {
    echo "  <td class='delink' onclick='doinvopen($patient_id,$encounter_id)'>\n";
  }
  else {
    echo "  <td class='detail'>\n";
  }
  echo "   $invnumber\n  </td>\n";
?>
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail" align="right">
   <?php echo $qtys[0]; ?>
  </td>
  <!--
  <td class="detail" align="right">
   <?php echo $qtys[1]; ?>
  </td>
  -->
  <td class="detail" align="right">
   <?php echo $qtys[2]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $qtys[6]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $qtys[3]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $qtys[4]; ?>
  </td>
  <td class="detail" align="right">
   <?php echo $qtys[5]; ?>
  </td>
  <td class="detail">
   &nbsp;
  </td>
 </tr>
<?php
    } // End not csv export
  } // end details
  for ($i = 0; $i < 7; ++$i) {
    $secqtys[$i]   += $qtys[$i];
    $priqtys[$i]   += $qtys[$i];
    $grandqtys[$i] += $qtys[$i];
  }
} // end function

// Check permission for this report.
$auth_drug_reports = $GLOBALS['inhouse_pharmacy'] && (
  acl_check('admin'    , 'drugs'      ) ||
  acl_check('inventory', 'reporting'  ));
if (!$auth_drug_reports) {
  die(xl("Unauthorized access."));
}

// Note if user is restricted to any facilities and/or warehouses.
$is_user_restricted = isUserRestricted();

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_product  = $_POST['form_product'];

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=inventory_activity.csv");
  header("Content-Description: File Transfer");
  // CSV headers:
  if ($product_first) {
    echo '"' . xl('Product'  ) . '",';
    echo '"' . xl('Warehouse') . '",';
  } else {
    echo '"' . xl('Warehouse') . '",';
    echo '"' . xl('Product'  ) . '",';
  }
  if ($_POST['form_details']) {
    echo '"' . xl('Date'        ) . '",';
    echo '"' . xl('Invoice'     ) . '",';
    echo '"' . xl('Issues/Sales') . '",';
    // echo '"' . xl('Distributions') . '",';
    echo '"' . xl('Receipts'    ) . '",';
    echo '"' . xl('Returns'     ) . '",';
    echo '"' . xl('Transfers'   ) . '",';
    echo '"' . xl('Adjustments' ) . '",';
    echo '"' . xl('Consumptions') . '"' . "\n";
  }
  else {
    echo '"' . xl('Opening Balance') . '",';
    echo '"' . xl('Issues/Sales'   ) . '",';
    // echo '"' . xl('Distributions'  ) . '",';
    echo '"' . xl('Receipts'       ) . '",';
    echo '"' . xl('Returns'        ) . '",';
    echo '"' . xl('Transfers'      ) . '",';
    echo '"' . xl('Adjustments'    ) . '",';
    echo '"' . xl('Consumptions'   ) . '",';
    echo '"' . xl('Closing Balance') . '"' . "\n";
  }
} // end export
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Inventory Activity','e') ?></title>

<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
 .delink { color:#0000cc; font-family:sans-serif; font-size:10pt; font-weight:normal; cursor:pointer }

table.mymaintable, table.mymaintable td, table.mymaintable th {
 border: 1px solid #aaaaaa;
 border-collapse: collapse;
}
table.mymaintable td, table.mymaintable th {
 padding: 1pt 4pt 1pt 4pt;
}
</style>

<script type="text/javascript" src="../../library/textformat.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/topdialog.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js?v=<?php echo $v_js_includes; ?>"></script>

<script language='JavaScript'>

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

function doinvopen(ptid,encid) {
 dlgopen('../patient_file/pos_checkout.php?ptid=' + ptid + '&enc=' + encid, '_blank', 750, 550);
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

$(document).ready(function() {
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
  facchanged();
});

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php xl('Inventory Activity','e')?></h2>

<form method='post' action='inventory_activity.php?product=<?php echo $product_first; ?>'>

<table border='0' cellpadding='3'>

 <tr>
  <td>
<?php
// Build a drop-down list of facilities.
//
$query = "SELECT id, name FROM facility ORDER BY name";
$fres = sqlStatement($query);
echo "   <select name='form_facility' onchange='facchanged()'>\n";
echo "    <option value=''>" . xl('All Facilities') . "</option>\n";
while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  if ($is_user_restricted && !isFacilityAllowed($facid)) continue;
  echo "    <option value='$facid'";
  if ($facid == $form_facility) echo " selected";
  echo ">" . $frow['name'] . "\n";
}
echo "   </select>&nbsp;\n";
?>

   <?php xl('By','e'); ?>:
   <select name='form_by'>
    <option value='p'><?php xl('Product','e'); ?></option>
    <option value='w'<?php if (!$product_first) echo ' selected'; ?>><?php xl('Warehouse','e'); ?></option>
   </select>&nbsp;

<?php
// Build a drop-down list of warehouses.
//
echo "   <select name='form_warehouse'>\n";
echo "    <option value=''>" . xl('All Warehouses') . "</option>\n";
$lres = sqlStatement("SELECT * FROM list_options " .
  "WHERE list_id = 'warehouse' AND activity = 1 ORDER BY seq, title");
while ($lrow = sqlFetchArray($lres)) {
  echo "    <option value='" . $lrow['option_id'] . "/" . $lrow['option_value'] . "'";
  echo " id='fac" . $lrow['option_value'] . "'";
  if (strlen($form_warehouse) > 0 && $lrow['option_id'] == $form_warehouse) {
    echo " selected";
  }
  echo ">" . xl_list_label($lrow['title']) . "</option>\n";
}
echo "   </select>&nbsp;\n";

// Build a drop-down list of products.
//
$query = "SELECT drug_id, name FROM drugs ORDER BY name, drug_id";
$pres = sqlStatement($query);
echo "   <select name='form_product'>\n";
echo "    <option value=''>-- " . xl('All Products') . " --\n";
while ($prow = sqlFetchArray($pres)) {
  $drug_id = $prow['drug_id'];
  echo "    <option value='$drug_id'";
  if ($drug_id == $form_product) echo " selected";
  echo ">" . $prow['name'] . "\n";
}
echo "   </select>&nbsp;\n";
?>

   <input type='checkbox' name='form_details' value='1'<?php if ($_POST['form_details']) echo " checked"; ?> /><?php xl('Details','e') ?>

  </td>
 </tr>

 <tr>
  <td>

   <?php xl('From','e'); ?>:
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

<table width='98%' id='mymaintable' class='mymaintable'>

 <thead>
 <tr bgcolor="#dddddd">
  <td class="dehead">
   <?php echo $product_first ? xl('Product') : xl('Warehouse'); ?>
  </td>
<?php if ($_POST['form_details']) { ?>
  <td class="dehead">
   <?php echo $product_first ? xl('Warehouse') : xl('Product'); ?>
  </td>
  <td class="dehead">
   <?php xl('Date','e'); ?>
  </td>
  <td class="dehead">
   <?php xl('Invoice','e'); ?>
  </td>
<?php } else { ?>
  <td class="dehead" colspan="3">
   <?php echo $product_first ? xl('Warehouse') : xl('Product'); ?>
  </td>
<?php } ?>
  <td class="dehead" align="right" width="8%">
   <?php xl('Opening Balance','e'); ?>
  </td>
  <td class="dehead" align="right" width="8%">
   <?php xl('Issues/Sales','e'); ?>
  </td>
  <!--
  <td class="dehead" align="right" width="8%">
   <?php xl('Distributions','e'); ?>
  </td>
  -->
  <td class="dehead" align="right" width="8%">
   <?php xl('Receipts','e'); ?>
  </td>
  <td class="dehead" align="right" width="8%">
   <?php echo xlt('Returns'); ?>
  </td>
  <td class="dehead" align="right" width="8%">
   <?php xl('Transfers','e'); ?>
  </td>
  <td class="dehead" align="right" width="8%">
   <?php xl('Adjustments','e'); ?>
  </td>
  <td class="dehead" align="right" width="8%">
   <?php echo xlt('Consumptions'); ?>
  </td>
  <td class="dehead" align="right" width="8%">
   <?php xl('Closing Balance','e'); ?>
  </td>
 </tr>
 </thead>
 <tbody>
<?php
} // end not export

if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
  $from_date = $form_from_date;
  $to_date   = $form_to_date;

  $product   = "";
  $prodleft  = "";
  $warehouse = "";
  $whleft    = "";
  $grandqtys = array(0, 0, 0, 0, 0, 0, 0);
  $priqtys   = array(0, 0, 0, 0, 0, 0, 0);
  $secqtys   = array(0, 0, 0, 0, 0, 0, 0);
  $last_inventory_id = 0;

  /*******************************************************************
  $query = "SELECT s.sale_date, s.quantity, s.pid, s.encounter, " .
    "s.xfer_inventory_id, d.name, lo.title, di.drug_id, di.warehouse_id " .
    "FROM drug_sales AS s " .
    "JOIN drug_inventory AS di ON di.drug_id = s.drug_id AND di.inventory_id = s.inventory_id " .
    "JOIN drugs AS d ON d.drug_id = s.drug_id " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    "lo.option_id = di.warehouse_id " .
    "WHERE s.sale_date >= '$from_date' AND s.sale_date <= '$to_date'";
  *******************************************************************/
  $query = "SELECT s.sale_id, s.sale_date, s.quantity, s.fee, s.pid, s.encounter, " .
    "s.xfer_inventory_id, s.distributor_id, s.trans_type, d.name, " .
    "lo.title, lo.option_value AS facid, " .
    "di.drug_id, di.warehouse_id, di.inventory_id, di.destroy_date, di.on_hand, " .
    "fe.invoice_refno " .
    "FROM drug_inventory AS di " .
    "JOIN drugs AS d ON d.drug_id = di.drug_id " .
    "LEFT JOIN drug_sales AS s ON " .
    "s.sale_date >= '$from_date' AND s.sale_date <= '$to_date' AND " .
    "s.drug_id = di.drug_id AND " .
    "( s.inventory_id = di.inventory_id OR s.xfer_inventory_id = di.inventory_id ) " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    "lo.option_id = di.warehouse_id AND lo.activity = 1 " .
    "LEFT JOIN form_encounter AS fe ON fe.pid = s.pid AND fe.encounter = s.encounter " .
    "WHERE ( di.destroy_date IS NULL OR di.destroy_date >= '$form_from_date' )";

  // If a product was specified.
  if ($form_product) {
    $query .= " AND di.drug_id = '$form_product'";
  }

  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND lo.option_value IS NOT NULL AND lo.option_value = '$form_facility'";
  }

  if ($form_warehouse) {
    $query .= " AND di.warehouse_id IS NOT NULL AND di.warehouse_id = '$form_warehouse'";
  }

  if ($product_first) {
    $query .= " ORDER BY d.name, d.drug_id, lo.title, di.warehouse_id, " .
      "di.inventory_id, s.sale_date, s.sale_id";
  } else {
    $query .= " ORDER BY lo.title, di.warehouse_id, d.name, d.drug_id, " .
      "di.inventory_id, s.sale_date, s.sale_id";
  }

  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res)) {

    // Skip this row if user is disallowed from its warehouse.
    if ($is_user_restricted && !isWarehouseAllowed($row['facid'], $row['warehouse_id'])) {
      continue;
    }

    // If new lot and it was destroyed during the reporting period,
    // generate a pseudo-adjustment for that.
    if ($row['inventory_id'] != $last_inventory_id) {
      $last_inventory_id = $row['inventory_id'];
      if (!empty($row['destroy_date']) && $row['on_hand'] != 0
        && $row['destroy_date'] <= $form_to_date)
      {
        thisLineItem($row['drug_id'], $row['warehouse_id'], 0,
          0, $row['name'], $row['title'], $row['destroy_date'],
          array(0, 0, 0, 0, 0 - $row['on_hand'], 0),
          xl('Destroyed'));
      }
    }

    $qtys = array(0, 0, 0, 0, 0, 0, 0);
    if ($row['sale_id']) {
      if ($row['xfer_inventory_id']) {   // trans_type 4 is transfer
        // A transfer sale item will appear twice, once with each lot.
        if ($row['inventory_id'] == $row['xfer_inventory_id'])
          $qtys[3] = $row['quantity'];
        else
          $qtys[3] = 0 - $row['quantity'];
      }
      else if ($row['pid'])
        $qtys[0] = 0 - $row['quantity']; // sale
      /***************************************************************
      else if ($row['distributor_id'])
        $qtys[1] = 0 - $row['quantity'];
      ***************************************************************/
      else if ($row['trans_type'] == 2)
        $qtys[2] = 0 - $row['quantity']; // purchase
      else if ($row['trans_type'] == 3)
        $qtys[6] = 0 - $row['quantity']; // return
      else if ($row['trans_type'] == 5)
        $qtys[4] = 0 - $row['quantity']; // adjustment
      else
        $qtys[5] = 0 - $row['quantity']; // consumption
    }

    thisLineItem($row['drug_id'], $row['warehouse_id'], $row['pid'] + 0,
      $row['encounter'] + 0, $row['name'], $row['title'], $row['sale_date'],
      $qtys, $row['invoice_refno']);
  }

  // Generate totals for last product and warehouse.
  thisLineItem(0, '~', 0, 0, '', '', '0000-00-00', array(0, 0, 0, 0, 0, 0, 0));

  // Grand totals line.
  if (!$_POST['form_csvexport']) {
    $grei = getEndInventory();
?>
 <tr bgcolor="#dddddd">
  <td class="dehead" colspan="4">
   <?php xl('Grand Total','e'); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grei - $grandqtys[0] - $grandqtys[1] - $grandqtys[2] - $grandqtys[6] - $grandqtys[3] - $grandqtys[4] - $grandqtys[5]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grandqtys[0]; ?>
  </td>
  <!--
  <td class="dehead" align="right">
   <?php echo $grandqtys[1]; ?>
  </td>
  -->
  <td class="dehead" align="right">
   <?php echo $grandqtys[2]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grandqtys[6]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grandqtys[3]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grandqtys[4]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grandqtys[5]; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grei; ?>
  </td>
 </tr>
<?php
  } // End not csv export
} // end refresh or export

if (!$_POST['form_csvexport']) {
?>
 </tbody>
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
} // End not export
?>
