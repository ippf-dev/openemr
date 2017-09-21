<?php
// Copyright (C) 2009-2017 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

$sanitize_all_escapes  = true;
$fake_register_globals = false;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once($GLOBALS['fileroot'] . '/custom/code_types.inc.php');

function formatcyp($amount) {
  if ($amount) return sprintf("%.2f", $amount);
  return '';
}

function display_desc($desc) {
  if (preg_match('/^\S*?:(.+)$/', $desc, $matches)) {
    $desc = $matches[1];
  }
  return $desc;
}

function thisLineItem($patient_id, $encounter_id, $description, $transdate, $qty, $related_code, $irnumber='') {
  global $product, $productcyp, $producttotal, $productqty, $grandtotal, $grandqty, $code_types;

  /********************************************************************
  // Look up the CYP factor for the related IPPF code.
  $cypfactor = 0;
  $relcodes = explode(';', $related_code);
  foreach ($relcodes as $relstring) {
    if ($relstring === '') continue;
    list($reltype, $relcode) = explode(':', $relstring);
    if ($reltype !== 'IPPF') continue;
    $tmprow = sqlQuery("SELECT cyp_factor FROM codes WHERE " .
      "code_type = '11' AND code = '$relcode' LIMIT 1");
    $cypfactor = empty($tmprow['cyp_factor']) ? 0 : (0 + $tmprow['cyp_factor']);
    if ($cypfactor) break;
  }
  ********************************************************************/
  // Look up the CYP factor from the related IPPFCM code.
  $cypfactor = 0;
  $relcodes = explode(';', $related_code);
  foreach ($relcodes as $relstring) {
    if ($relstring === '') continue;
    list($reltype, $relcode) = explode(':', $relstring);
    if ($reltype !== 'IPPFCM') continue;
    $tmprow = sqlQuery("SELECT cyp_factor FROM codes WHERE code_type = ? AND code = ? LIMIT 1",
      array($code_types['IPPFCM']['id'], $relcode));
    $cypfactor = empty($tmprow['cyp_factor']) ? 0 : (0 + $tmprow['cyp_factor']);
    if ($cypfactor) break;
  }
  /*******************************************************************/

  // If not for contraception, skip this item.
  if (!$cypfactor) return;

  $invnumber = empty($irnumber) ? "$patient_id.$encounter_id" : $irnumber;
  $rowcyp    = sprintf('%01.2f', $cypfactor);
  $rowresult = sprintf('%01.2f', $rowcyp * $qty);

  $rowproduct = $description;
  if (! $rowproduct) $rowproduct = 'Unknown';

  if ($product != $rowproduct) {
    if ($product) {
      // Print product total.
      if ($_POST['form_csvexport']) {
        if (! $_POST['form_details']) {
          echo '"' . display_desc($product) . '",';
          echo '"' . $productqty            . '",';
          echo '"' . formatcyp($productcyp) . '",';
          echo '"' . formatcyp($producttotal) . '"' . "\n";
        }
      }
      else {
?>

 <tr bgcolor="#ddddff">
  <td class="detail" colspan="<?php echo $_POST['form_details'] ? 3 : 1; ?>">
   <?php if ($_POST['form_details']) echo xl('Total for '); echo display_desc($product) ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $productqty; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo formatcyp($productcyp); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo formatcyp($producttotal); ?>
  </td>
 </tr>
<?php
      } // End not csv export
    }
    $producttotal = 0;
    $productqty = 0;
    $product = $rowproduct;
    $productleft = $product;
    $productcyp = $rowcyp;
  } // end new product

  if ($_POST['form_details']) {
    if ($_POST['form_csvexport']) {
      echo '"' . display_desc($product         ) . '",';
      echo '"' . oeFormatShortDate(display_desc($transdate)) . '",';
      echo '"' . display_desc($invnumber) . '",';
      echo '"' . display_desc($qty      ) . '",';
      echo '"' . formatcyp($rowcyp) . '",';
      echo '"' . formatcyp($rowresult) . '"' . "\n";
    }
    else {
?>

 <tr>
  <td class="detail">
   <?php echo display_desc($productleft); $productleft = "&nbsp;"; ?>
  </td>
  <td class="dehead">
   <?php echo oeFormatShortDate($transdate); ?>
  </td>
  <td class="detail">
   <?php echo $invnumber; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $qty; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo formatcyp($rowcyp); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo formatcyp($rowresult); ?>
  </td>
 </tr>
<?
    } // End not csv export
  } // end details
  $producttotal += $rowresult;
  $grandtotal   += $rowresult;
  $productqty   += $qty;
  $grandqty     += $qty;
} // end function

if (!acl_check('acct', 'rep_a')) die(xl("Unauthorized access."));

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_facility  = $_POST['form_facility'];

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=ippf_cyp_report.csv");
  header("Content-Description: File Transfer");
  // CSV headers:
  if ($_POST['form_details']) {
    echo '"Item",';
    echo '"Date",';
    echo '"Invoice",';
    echo '"Qty",';
    echo '"CYP",';
    echo '"Result"' . "\n";
  }
  else {
    echo '"Item",';
    echo '"Qty",';
    echo '"CYP",';
    echo '"Result"' . "\n";
  }
}
else { // not export
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('CYP Report','e') ?></title>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php xl('CYP Report','e')?></h2>

<form method='post' action='ippf_cyp_report.php'>

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
   &nbsp;<?xl('From:','e')?>
   <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
   &nbsp;To:
   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
   &nbsp;
   <input type='checkbox' name='form_details' value='1'<?php if ($_POST['form_details']) echo " checked"; ?>><?php xl('Details','e') ?>
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
   <?xl('Item','e')?>
  </td>
<?php if ($_POST['form_details']) { ?>
  <td class="dehead">
   <?xl('Date','e')?>
  </td>
  <td class="dehead">
   <?xl('Invoice','e')?>
  </td>
<?php } ?>
  <td class="dehead" align="right">
   <?xl('Qty','e')?>
  </td>
  <td class="dehead" align="right">
   <?xl('CYP','e')?>
  </td>
  <td class="dehead" align="right">
   <?xl('Result','e')?>
  </td>
 </tr>
<?php
} // end not export

// If generating a report.
//
if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
  $from_date = $form_from_date;
  $to_date   = $form_to_date;

  $product = "";
  $productleft = "";
  $productcyp = 0;
  $producttotal = 0; // total of results for product
  $grandtotal = 0;   // grand total of results
  $productqty = 0;
  $grandqty = 0;

  $query = "SELECT b.pid, b.encounter, b.code_type, b.code, b.units, " .
    "b.code_text, c.related_code, fe.date, fe.facility_id, fe.invoice_refno " .
    "FROM billing AS b " .
    "JOIN codes AS c ON c.code_type = '12' AND c.code = b.code AND c.modifier = b.modifier AND " .
    // Only surgical contraception counts for services:
    "(c.related_code LIKE '%IPPFCM:456%' OR c.related_code LIKE '%IPPFCM:457%') " .
    "JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter " .
    "WHERE b.code_type = 'MA' AND b.activity = 1 AND " .
    "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59'";
  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = '$form_facility'";
  }
  $query .= " ORDER BY b.code, fe.date, fe.id";
  //
  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res)) {
    thisLineItem($row['pid'], $row['encounter'],
      $row['code'] . ' ' . $row['code_text'],
      substr($row['date'], 0, 10), $row['units'], $row['related_code'],
      $row['invoice_refno']);
  }
  //
  $query = "SELECT s.sale_date, s.quantity, s.pid, s.encounter, " .
    "d.name, d.related_code, t.pkgqty, fe.date, fe.facility_id, fe.invoice_refno " .
    "FROM drug_sales AS s " .
    "JOIN drugs AS d ON d.drug_id = s.drug_id AND d.related_code != '' " .
    "LEFT JOIN drug_templates AS t ON t.drug_id = s.drug_id AND t.selector = s.selector " .
    "JOIN form_encounter AS fe ON " .
    "fe.pid = s.pid AND fe.encounter = s.encounter AND " .
    "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59' " .
    "WHERE s.fee != 0";
  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = '$form_facility'";
  }
  $query .= " ORDER BY d.name, fe.date, fe.id";
  //
  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res)) {
    $quantity = $row['quantity'];
    // if (strpos($row['related_code'], 'IPPFCM:4450') !== false) {
    // Package quantity was designed to affect CYP quantity for male condoms, but make it work for anything.
    // if (!empty($row['pkgqty'])) $quantity *= floatval($row['pkgqty']);
    // }
    thisLineItem($row['pid'], $row['encounter'], $row['name'],
      substr($row['date'], 0, 10), $quantity, $row['related_code'],
      $row['invoice_refno']);
  }

  if ($_POST['form_csvexport']) {
    if (! $_POST['form_details']) {
      echo '"' . display_desc($product) . '",';
      echo '"' . $productqty            . '",';
      echo '"' . formatcyp($productcyp) . '",';
      echo '"' . formatcyp($producttotal) . '"' . "\n";
    }
  }
  else {
?>

 <tr bgcolor="#ddddff">
  <td class="detail" colspan="<?php echo $_POST['form_details'] ? 3 : 1; ?>">
   <?php if ($_POST['form_details']) echo xl('Total for '); echo display_desc($product) ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $productqty; ?>
  </td>
  <td class="dehead" align="right">
   <?php echo formatcyp($productcyp); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo formatcyp($producttotal); ?>
  </td>
 </tr>

 <tr bgcolor="#ffdddd">
  <td class="detail" colspan="<?php echo $_POST['form_details'] ? 3 : 1; ?>">
   <?php xl('Grand Total','e'); ?>
  </td>
  <td class="dehead" align="right">
   <?php echo $grandqty; ?>
  </td>
  <td class="dehead" align="right">
   &nbsp;
  </td>
  <td class="dehead" align="right">
   <?php echo formatcyp($grandtotal); ?>
  </td>
 </tr>

<?
  } // End not csv export
} // end report generation

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
