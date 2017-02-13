<?php
// Copyright (C) 2017 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/options.inc.php");
require_once("../../custom/code_types.inc.php");

// For each sorting option, specify the ORDER BY argument.
$ORDERHASH = array(
  'item'     => 'itemcode, svcdate, invoiceno, pid, encounter',
  'svcdate'  => 'svcdate, invoiceno, pid, encounter, itemcode',
  'invoice'  => 'invoiceno, pid, encounter, svcdate, itemcode',
  'qty'      => 'units, svcdate, invoiceno, pid, encounter, itemcode',
  'provider' => 'provname, svcdate, invoiceno, pid, encounter, itemcode',
  'user'     => 'username, svcdate, invoiceno, pid, encounter, itemcode',
  'pubpid'   => 'pubpid, svcdate, invoiceno, pid, encounter, itemcode',
  'ptname'   => 'ptname, svcdate, invoiceno, pid, encounter, itemcode',
);

function bucks($amount) {
  if ($amount) echo oeFormatMoney($amount);
}

function display_desc($desc) {
  if (preg_match('/^\S*?:(.+)$/', $desc, $matches)) {
    $desc = $matches[1];
  }
  return $desc;
}

$previous_invno = array();

function thisLineItem($patient_id, $encounter_id, $code_type, $code, $description,
  $svcdate, $qty, $irnumber, $pubpid, $ptname, $provname, $username)
{
  global $form_products;

  if (empty($qty)) $qty = 1;
  $invnumber = $irnumber ? $irnumber : "$patient_id.$encounter_id";

  if ($code_type == 'PROD' && !$form_products) return;

  if ($_POST['form_csvexport']) {
    echo '"' . display_desc($pubpid) . '",';
    echo '"' . display_desc($ptname) . '",';
    echo '"' . oeFormatShortDate(display_desc($svcdate)) . '",';
    echo '"' . display_desc($invnumber) . '",';
    echo '"' . display_desc($code) . '",';
    echo '"' . display_desc($description) . '",';
    echo '"' . display_desc($qty      ) . '",';
    echo '"' . display_desc($provname) . '",';
    echo '"' . display_desc($username) . '"';
    echo "\n";
  }
  else {
?>

 <tr>
  <td class="detail">
   <?php echo display_desc($pubpid); ?>
  </td>
  <td class="detail">
   <?php echo display_desc($ptname); ?>
  </td>
  <td class="detail">
   <?php echo oeFormatShortDate($svcdate); ?>
  </td>
  <td class='delink' onclick='doinvopen(<?php echo "$patient_id,$encounter_id"; ?>)'>
   <?php echo $invnumber; ?>
  </td>
  <td class="detail">
   <?php echo display_desc($code); ?>
  </td>
  <td class="detail">
   <?php echo display_desc($description); ?>
  </td>
  <td class="detail" align="right">
   <?php echo $qty; ?>
  </td>
  <td class="detail">
   <?php echo display_desc($provname); ?>
  </td>
  <td class="detail">
   <?php echo display_desc($username); ?>
  </td>
 </tr>
<?php
  } // End not csv export
} // end function thisLineItem

if (!acl_check('acct', 'rep_a')) die(xl("Unauthorized access."));

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-01'));
$form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_facility  = isset($_POST['form_facility']) ? $_POST['form_facility'] : '';
$form_ug1       = !empty($_POST['form_ug1']);
$form_products  = !empty($_POST['form_products']);

$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'svcdate';
$orderby = $ORDERHASH[$form_orderby];

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=services_provided.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";
  // CSV headers:
  echo '"' . xl('Client ID'       ) . '",';
  echo '"' . xl('Client Name'     ) . '",';
  echo '"' . xl('Service Date'    ) . '",';
  echo '"' . xl('Invoice'         ) . '",';
  echo '"' . xl('Item'            ) . '",';
  echo '"' . xl('Description'     ) . '",';
  echo '"' . xl('Units'           ) . '",';
  echo '"' . xl('Provider'        ) . '",';
  echo '"' . xl('User'            ) . '"';
  echo "\n";
} // end export
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('Services Provided') ?></title>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>

<style type="text/css">

 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
 .delink { color:#0000cc; font-family:sans-serif; font-size:10pt; font-weight:normal; cursor:pointer }

table.mymaintable, table.mymaintable td {
 border: 1px solid #aaaaaa;
 border-collapse: collapse;
}
table.mymaintable td {
 padding: 1pt 4pt 1pt 4pt;
}

</style>

<script type="text/javascript" src="../../library/textformat.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/topdialog.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js?v=<?php echo $v_js_includes; ?>"></script>

<script language="JavaScript">

<?php // require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

function dosort(orderby) {
 var f = document.forms[0];
 f.form_orderby.value = orderby;
 top.restoreSession();
 f.submit();
 return false;
}

// Process click to pop up the add/edit window.
function doinvopen(ptid,encid) {
 dlgopen('../patient_file/pos_checkout.php?ptid=' + ptid + '&enc=' + encid, '_blank', 750, 550);
}

$(document).ready(function() {
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
});

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php echo xlt('Services Provided')?></h2>

<form method='post' action='ad_hoc_services.php'>

<table border='0' cellpadding='3'>
 <tr>
  <td align='center'>
   <input type='checkbox' name='form_ug1'      value='1' <?php if ($form_ug1     ) echo 'checked' ?> /><?php echo xlt('Units Not 1') ?>&nbsp;
   <input type='checkbox' name='form_products' value='1' <?php if ($form_products) echo 'checked' ?> /><?php echo xlt('Include Products') ?>
  </td>
 </tr>
 <tr>
  <td align='center'>
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
  &nbsp;
   <?php echo xlt('From'); ?>:
   <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php echo xlt('Click here to choose a date'); ?>'>
   &nbsp;<?php echo xlt('To'); ?>:
   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php echo xlt('Click here to choose a date'); ?>'>
   &nbsp;
   <input type='submit' name='form_refresh' value="<?php echo xlt('Refresh') ?>">
   &nbsp;
   <input type='submit' name='form_csvexport' value="<?php echo xlt('Export to CSV') ?>">
   &nbsp;
   <input type='button' value='<?php echo xlt('Print'); ?>' onclick='window.print()' />
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
   <a href="#" onclick="return dosort('pubpid')"
   <?php if ($form_orderby == "pubpid") echo " style=\"color:#00cc00\""; ?>
   ><?php echo xlt('Client ID'); ?></a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('ptname')"
   <?php if ($form_orderby == "ptname") echo " style=\"color:#00cc00\""; ?>
   ><?php echo xlt('Client Name'); ?></a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('svcdate')"
   <?php if ($form_orderby == "svcdate") echo " style=\"color:#00cc00\""; ?>
   ><?php echo xlt('Service Date'); ?></a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('invoice')"
   <?php if ($form_orderby == "invoice") echo " style=\"color:#00cc00\""; ?>
   ><?php echo xlt('Invoice'); ?></a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('item')"
   <?php if ($form_orderby == "item") echo " style=\"color:#00cc00\""; ?>
   ><?php echo xlt('Item'); ?></a>
  </td>
  <td class="dehead">
   <?php echo xlt('Description'); ?>
  </td>
  <td class="dehead" align="right">
   <a href="#" onclick="return dosort('qty')"
   <?php if ($form_orderby == "qty") echo " style=\"color:#00cc00\""; ?>
   ><?php echo xlt('Units'); ?></a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('provider')"
   <?php if ($form_orderby == "provider") echo " style=\"color:#00cc00\""; ?>
   ><?php echo xlt('Provider'); ?></a>
  </td>
  <td class="dehead">
   <a href="#" onclick="return dosort('user')"
   <?php if ($form_orderby == "user") echo " style=\"color:#00cc00\""; ?>
   ><?php echo xlt('User'); ?></a>
  </td>
 </tr>
 </thead>
 <tbody>
<?php
} // end not export

if ($_POST['form_orderby']) {
  $from_date = $form_from_date;
  $to_date   = $form_to_date;

  // If a facility was specified.
  $factest = $form_facility ? "AND fe.facility_id = '$form_facility'" : "";

  $query = "( " .
    "SELECT " .
    "b.pid, b.encounter, b.code_type, b.code AS itemcode, b.code_text AS description, b.units, b.fee, " .
    "fe.date AS svcdate, fe.invoice_refno AS invoiceno, " .
    "pd.pubpid, CONCAT(pd.lname, ', ', pd.fname) AS ptname, " .
    "uu.username, up.username AS provname " .
    "FROM billing AS b " .
    "JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter AND " .
    "fe.date >= ? AND fe.date <= ? $factest " .
    "JOIN patient_data AS pd ON pd.pid = fe.pid " .
    "LEFT JOIN facility AS f ON f.id = fe.facility_id " .
    "LEFT JOIN users AS uu ON uu.id = b.user " .
    "LEFT JOIN users AS up ON up.id = IF(b.provider_id, b.provider_id, fe.provider_id) " .
    "WHERE b.code_type != 'COPAY' AND b.activity = 1" .
    ($form_ug1 ? " AND b.units != 1" : "") .
    " AND (b.code_type != 'TAX' OR b.ndc_info = '') " . // why the ndc_info test?
    ") UNION ALL ( " .
    "SELECT " .
    "s.pid, s.encounter, 'PROD' AS code_type, s.drug_id AS itemcode, d.name AS description, " .
    "s.quantity AS units, s.fee, " .
    "fe.date AS svcdate, fe.invoice_refno AS invoiceno, " .
    "pd.pubpid, CONCAT(pd.lname, ', ', pd.fname) AS ptname, " .
    "uu.username, up.username AS provname " .
    "FROM drug_sales AS s " .
    "JOIN form_encounter AS fe ON fe.pid = s.pid AND fe.encounter = s.encounter AND " .
    "fe.date >= ? AND fe.date <= ? $factest " .
    "JOIN patient_data AS pd ON pd.pid = fe.pid " .
    "LEFT JOIN facility AS f ON f.id = fe.facility_id " .
    "LEFT JOIN users AS uu ON uu.id = s.user " .
    "LEFT JOIN users AS up ON up.id = fe.provider_id " .
    "LEFT JOIN drugs AS d ON d.drug_id = s.drug_id " .
    "WHERE " . ($form_ug1 ? "s.quantity != 1" : "1 = 1") .
    " ) ORDER BY $orderby";

  $dt1 = "$from_date 00:00:00";
  $dt2 = "$to_date 23:59:59";

  // if (! $_POST['form_csvexport']) echo "<!-- $query\n $dt1 $dt2 $tmp -->\n"; // debugging

  $res = sqlStatement($query, array($dt1, $dt2, $dt1, $dt2));

  while ($row = sqlFetchArray($res)) {
    thisLineItem($row['pid'], $row['encounter'], $row['code_type'], $row['itemcode'],
      $row['description'], substr($row['svcdate'], 0, 10), $row['units'], $row['invoiceno'],
      $row['pubpid'], $row['ptname'], $row['provname'], $row['username']);
  }

} // end refresh or export

if (! $_POST['form_csvexport']) {
?>

</tbody>
</table>
<input type="hidden" name="form_orderby" value="<?php echo $form_orderby ?>" />
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
