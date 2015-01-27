<?php
 // Copyright (C) 2006, 2010 Rod Roark <rod@sunsetsystems.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

 // This report lists destroyed drug lots within a specified date
 // range.

 require_once("../globals.php");
 require_once("$srcdir/patient.inc");
 require_once("../drugs/drugs.inc.php");
 require_once("$srcdir/formatting.inc.php");

// For each sorting option, specify the ORDER BY argument.
$ORDERHASH = array(
  'drug' => 'd.name, i.drug_id, i.destroy_date, i.lot_number',
  'fac'  => 'facility, i.destroy_date, d.name, i.drug_id, i.lot_number',
  'wh'   => 'warehouse, i.destroy_date, d.name, i.drug_id, i.lot_number',
  'date' => 'i.destroy_date, d.name, i.drug_id, i.lot_number',
);

$form_from_date  = fixDate($_POST['form_from_date'], date('Y-01-01'));
$form_to_date    = fixDate($_POST['form_to_date']  , date('Y-m-d'));

// The selected facility ID, if any.
$form_facility = 0 + empty($_REQUEST['form_facility']) ? 0 : $_REQUEST['form_facility'];

$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'drug';
$orderby = $ORDERHASH[$form_orderby];
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Destroyed Drugs','e'); ?></title>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>

<style  type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>

<script language="JavaScript">
 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 function dosort(orderby) {
  var f = document.forms[0];
  f.form_orderby.value = orderby;
  top.restoreSession();
  f.submit();
  return false;
 }
</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

<center>

<h2><?php xl('Destroyed Drugs','e'); ?></h2>

<form name='theform' method='post' action='destroyed_drugs_report.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td>
<?php
// Build a drop-down list of facilities.
//
$query = "SELECT id, name FROM facility ORDER BY name";
$fres = sqlStatement($query);
echo "   <select name='form_facility'>\n";
echo "    <option value=''>-- " . xlt('All Facilities') . " --</option>\n";
while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  echo "    <option value='$facid'";
  if ($facid == $form_facility) echo " selected";
  echo ">" . $frow['name'] . "</option>\n";
}
echo "   </select>&nbsp;\n";
?>

   &nbsp;<?php xl('From','e'); ?>:
   <input type='text' name='form_from_date' id='form_from_date'
    size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title=<?php xl('yyyy-mm-dd','e','\'','\''); ?>>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title=<?php xl('Click here to choose a date','e','\'','\''); ?>>

   &nbsp;<?php xl('To','e'); ?>:
   <input type='text' name='form_to_date' id='form_to_date'
    size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title=<?php xl('yyyy-mm-dd','e','\'','\''); ?>>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title=<?php xl('Click here to choose a date','e','\'','\''); ?>>

   &nbsp;
   <input type='submit' name='form_refresh' value=<?php xl('Refresh','e'); ?>>
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
  <td class='dehead'>
   <a href="#" onclick="return dosort('drug')"
   <?php if ($form_orderby == "drug") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Drug Name'); ?> </a>
  </td>
  <td class='dehead'>
   <?php echo xlt('NDC'); ?>
  </td>
  <td class='dehead'>
   <?php echo xlt('Lot'); ?>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('fac')"
   <?php if ($form_orderby == "fac") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Facility'); ?> </a>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('wh')"
   <?php if ($form_orderby == "wh") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Warehouse'); ?> </a>
  </td>
  <td class='dehead'>
   <?php echo xlt('Qty'); ?>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('date')"
   <?php if ($form_orderby == "date") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Date Destroyed'); ?> </a>
  </td>
  <td class='dehead'>
   <?php echo xlt('Method'); ?>
  </td>
  <td class='dehead'>
   <?php echo xlt('Witness'); ?>
  </td>
  <td class='dehead'>
   <?php echo xlt('Notes'); ?>
  </td>
 </tr>
<?
 if ($_POST['form_orderby']) {
  $where = "i.destroy_date IS NOT NULL AND i.destroy_date >= '$form_from_date' AND " .
   "i.destroy_date <= '$form_to_date'";

  if ($form_facility) {
    $where .= " AND lo.option_value IS NOT NULL AND lo.option_value = '$form_facility'";
  }

  $query = "SELECT i.inventory_id, i.lot_number, i.on_hand, i.drug_id, " .
   "i.destroy_date, i.destroy_method, i.destroy_witness, i.destroy_notes, " .
   "d.name, d.ndc_number, lo.title AS warehouse, f.name AS facility " .
   "FROM drug_inventory AS i " .
   "LEFT JOIN drugs AS d ON d.drug_id = i.drug_id " .
   "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
   "lo.option_id = i.warehouse_id " .
   "LEFT JOIN facility AS f ON f.id = lo.option_value " .
   "WHERE $where " .
   "ORDER BY $orderby";

  // echo "<!-- $query -->\n"; // debugging
  $res = sqlStatement($query);

  $last_drug_id = 0;
  $encount = 0;
  while ($row = sqlFetchArray($res)) {
   $drug_name       = text($row['name']);
   $ndc_number      = text($row['ndc_number']);
   if ($row['drug_id'] == $last_drug_id) {
    $drug_name  = '&nbsp;';
    $ndc_number = '&nbsp;';
   }
   $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
?>
 <tr bgcolor="<?php echo $bgcolor; ?>">
  <td class='detail'>
   <?php echo $drug_name; ?>
  </td>
  <td class='detail'>
   <?php echo $ndc_number; ?>
  </td>
  <td class='detail'>
   <a href='../drugs/destroy_lot.php?drug=<?php echo $row['drug_id'] ?>&lot=<?php echo $row['inventory_id'] ?>'
    style='color:#0000ff' target='_blank'>
   <?php echo text($row['lot_number']); ?>
   </a>
  </td>
  <td class='detail'>
   <?php echo text($row['facility']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['warehouse']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['on_hand']); ?>
  </td>
  <td class='detail'>
   <?php echo oeFormatShortDate($row['destroy_date']) ?>
  </td>
  <td class='detail'>
   <?php echo text($row['destroy_method']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['destroy_witness']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['destroy_notes']); ?>
  </td>
 </tr>
<?php
   $last_drug_id = $row['drug_id'];
  } // end while
 } // end if
?>

</table>

<input type="hidden" name="form_orderby" value="<?php echo $form_orderby ?>" />

</form>
</center>
<script language='JavaScript'>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>
</body>
</html>
