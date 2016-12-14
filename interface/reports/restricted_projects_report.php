<?php
// Copyright (C) 2013-2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is a report of services linked to a particular adjustment type, where
// the adjustment types are taken to be funding projects.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/options.inc.php");

// Get total of taxes matching this line item.
function getItemTaxes($patient_id, $encounter_id, $id) {
  $total = 0;
  $taxres = sqlStatement("SELECT code, fee FROM billing WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter_id' AND " .
    "code_type = 'TAX' AND activity = 1 AND ndc_info = '$id' " .
    "ORDER BY id");
  while ($taxrow = sqlFetchArray($taxres)) {
    $total += $taxrow['fee'];
  }
  return $total;
}

// This supports the "tbl" functions below.
$tblNewRow = true;

function tblStartRow() {
  global $tblNewRow;
  $tblNewRow = true;
  if (!$_POST['form_csvexport']) {
    echo " <tr>\n";
  }
}

function tblCell($data, $isRepeated=false, $right=false) {
  global $tblNewRow;
  if ($_POST['form_csvexport']) {
    if (!$tblNewRow) echo ',';
    echo '"' . addslashes($data) . '"';
  }
  else {
    echo "  <td class='detail'";
    if ($right) echo " align='right'";
    echo ">";
    if ($data === '' || $isRepeated) echo "&nbsp;";
    else echo htmlspecialchars($data);
    echo "</td>\n";
  }
  $tblNewRow = false;
}

function tblEndRow() {
  global $tblNewRow;
  if (!$tblNewRow) {
    if ($_POST['form_csvexport']) {
      echo "\n";
    }
    else {
      echo " </tr>\n";
    }
    $tblNewRow = true;
  }
}

// Compute age in years given a DOB and "as of" date.
//
function getAge($dob, $asof='') {
  if (empty($asof)) $asof = date('Y-m-d');
  $a1 = explode('-', substr($dob , 0, 10));
  $a2 = explode('-', substr($asof, 0, 10));
  $age = $a2[0] - $a1[0];
  if ($a2[1] < $a1[1] || ($a2[1] == $a1[1] && $a2[2] < $a1[2])) --$age;
  // echo "<!-- $dob $asof $age -->\n"; // debugging
  return $age;
}

if (!acl_check('patients', 'med')) die(xl("Unauthorized access."));

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_adjreason = formData('form_adjreason');
$form_facility  = formData('form_facility');

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=restricted_projects.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";
  // CSV headers:
  echo '"' . xl('Visit Date'       ) . '",';
  echo '"' . xl('Client ID'        ) . '",';
  echo '"' . xl('Visit Category'   ) . '",';
  echo '"' . xl('UIC Code'         ) . '",';
  echo '"' . xl('Voucher'          ) . '",';
  echo '"' . xl('Target Population') . '",';
  echo '"' . xl('Sex'              ) . '",';
  echo '"' . xl('Age'              ) . '",';
  echo '"' . xl('Qty'              ) . '",';
  echo '"' . xl('S&RH Services'    ) . '",';
  echo '"' . xl('Receipt'          ) . '",';
  echo '"' . xl('Cost'             ) . '",';
  echo '"' . xl('Total'            ) . '"';
  echo "\n";
} // end export
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Restricted Projects Report','e') ?></title>

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

<h2><?php xl('Restricted Projects Report','e')?></h2>

<form method='post' action='restricted_projects_report.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td align='center'>
<?php
  // Build a drop-down list of adjustment types.
  //
  echo generate_select_list('form_adjreason', 'adjreason', $form_adjreason, '', '');
?>
   &nbsp;
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
  </td>
 </tr>
 <tr>
  <td align='center'>
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
  <td class="dehead">
   <?php echo xl('Visit Date'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Client ID'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Visit Category'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('UIC Code'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Voucher'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Target Population'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Sex'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Age'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Qty'); ?>
  </td>
  <td class="dehead">
   <?php echo htmlspecialchars(xl('S&RH Services')); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Receipt'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Cost'); ?>
  </td>
  <td class="dehead">
   <?php echo xl('Total'); ?>
  </td>
 </tr>
<?php
} // end not export

if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
  $last_encounter = 0;

  // Get service items.
  $query = "SELECT " .
    "fe.pid, fe.encounter, fe.date, fe.invoice_refno, fe.voucher_number, " .
    "pd.pubpid, pd.usertext6, pd.usertext8, pd.DOB, lo.title AS sex, " .
    "opc.pc_catname " .
    "FROM form_encounter AS fe " .
    "JOIN patient_data AS pd ON pd.pid = fe.pid " .
    "LEFT JOIN openemr_postcalendar_categories AS opc ON opc.pc_catid = fe.pc_catid " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'sex' AND lo.option_id = pd.sex AND lo.activity = 1 " .
    "WHERE fe.date >= '$form_from_date 00:00:00' AND fe.date <= '$form_to_date 23:59:59'";
  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = '$form_facility'";
  }
  $query .= " ORDER BY fe.date, pd.pubpid, fe.pid, fe.encounter";

  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res)) {
    $item_count = 0; // number of line items displayed for this invoice
    $enctotal = 0;   // total dollars of selected items for this invoice

    $age = getAge(fixDate($row['DOB']), $row['date']);

    // We were originally to show age category as computed below, but
    // this was changed to just show age in years.
    /*****************************************************************
    if      ($age < 10) $agetext = '0-9';
    else if ($age < 15) $agetext = '10-14';
    else if ($age < 20) $agetext = '15-19';
    else if ($age < 25) $agetext = '20-24';
    else if ($age < 30) $agetext = '25-29';
    else if ($age < 35) $agetext = '30-34';
    else if ($age < 40) $agetext = '35-39';
    else if ($age < 45) $agetext = '40-44';
    else                $agetext = '45+';
    *****************************************************************/

    // Get service items.
    $query = "SELECT " .
      "b.id, b.code_text, b.units, b.fee " .
      "FROM billing AS b " .
      "JOIN ar_activity AS a ON a.pid = b.pid AND a.encounter = b.encounter AND a.deleted IS NULL AND " .
      "  ( a.pay_amount = 0 OR a.adj_amount != 0 ) AND " .
      "  ( a.code_type = '' OR ( a.code_type = b.code_type AND a.code = b.code ) ) AND " .
      "  a.memo = '" . $form_adjreason . "' WHERE " .
      "b.pid = '" . $row['pid'] . "' AND b.encounter = '" . $row['encounter'] .
      "' AND b.activity = 1 " .
      "ORDER BY b.code_text, b.id";

    $last_billing_id = 0;
    $bres = sqlStatement($query);

    while ($brow = sqlFetchArray($bres)) {
      if ($brow['id'] == $last_billing_id) continue;

      if ($last_billing_id) {
        // Write empty last cell for previous row and terminate it.
        tblCell('');
        tblEndRow();
      }

      // This gets taxes that are associated directly with this service item.
      $taxes = getItemTaxes($row['pid'], $row['encounter'], 'S:' . $brow['id']);

      tblStartRow();
      tblCell(oeFormatShortDate(substr($row['date'], 0, 10)), $item_count);
      tblCell($row['pubpid'        ], $item_count);
      tblCell($row['pc_catname'    ], $item_count);
      tblCell($row['usertext6'     ], $item_count);
      tblCell($row['voucher_number'], $item_count);
      tblCell($row['usertext8'     ], $item_count);
      tblCell($row['sex'           ], $item_count);
      tblCell($age, $item_count, true);
      tblCell($brow['units'], false, true);
      tblCell($brow['code_text']);
      tblCell($row['invoice_refno'], $item_count);
      tblCell(oeFormatMoney($brow['fee'] + $taxes), false, true);

      $enctotal += $brow['fee'] + $taxes;
      $last_encounter  = 0 + $row['encounter'];
      $last_billing_id = 0 + $brow['id'];
      ++$item_count;
    }

    // Products.
    $query = "SELECT " .
      "s.fee, s.quantity, s.sale_id, d.name " .
      "FROM drug_sales AS s " .
      "JOIN ar_activity AS a ON a.pid = s.pid AND a.encounter = s.encounter AND a.deleted IS NULL AND " .
      "  ( a.pay_amount = 0 OR a.adj_amount != 0 ) AND " .
      "  ( a.code_type = '' OR ( a.code_type = 'PROD' AND a.code = s.drug_id ) ) AND " .
      "  a.memo = '" . $form_adjreason . "' " .
      "LEFT JOIN drugs AS d ON d.drug_id = s.drug_id " .
      "WHERE s.pid = '" . $row['pid'] . "' AND s.encounter = '" . $row['encounter'] . "' " .
      "ORDER BY d.name, s.sale_id";

    $last_sale_id = 0;
    $sres = sqlStatement($query);

    while ($srow = sqlFetchArray($sres)) {
      if ($srow['sale_id'] == $last_sale_id) continue;

      if ($last_sale_id || $last_billing_id) {
        // Write empty last cell for previous row and terminate it.
        tblCell('');
        tblEndRow();
      }

      // This gets taxes that are associated directly with this service item.
      $taxes = getItemTaxes($row['pid'], $row['encounter'], 'P:' . $srow['sale_id']);

      tblStartRow();
      tblCell(oeFormatShortDate(substr($row['date'], 0, 10)), $item_count);
      tblCell($row['pubpid'        ], $item_count);
      tblCell($row['pc_catname'    ], $item_count);
      tblCell($row['usertext6'     ], $item_count);
      tblCell($row['voucher_number'], $item_count);
      tblCell($row['usertext8'     ], $item_count);
      tblCell($row['sex'           ], $item_count);
      tblCell($age, $item_count, true);
      tblCell($srow['quantity'], false, true);
      tblCell($srow['name']);
      tblCell($row['invoice_refno'], $item_count);
      tblCell(oeFormatMoney($srow['fee'] + $taxes), false, true);

      $enctotal += $srow['fee'] + $taxes;
      $last_encounter  = 0 + $row['encounter'];
      $last_sale_id = 0 + $srow['sale_id'];
      ++$item_count;
    }

    if ($item_count) {
      tblCell(oeFormatMoney($enctotal), false, true);
      tblEndRow();
    }
  }

} // End refresh or export

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
