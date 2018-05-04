<?php
// Copyright (C) 2017-2018 Rod Roark <rod@sunsetsystems.com>
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

function thisLineItem($row)
{
  if ($_POST['form_csvexport']) {
    echo '"' . addslashes($row['col1']) . '",';
    echo '"' . addslashes($row['col2']) . '",';
    echo '"' . addslashes($row['proj_name']) . '",';
    echo '"' . addslashes($row['fund_name']) . '",';
    echo '"' . addslashes($row['dept_name']) . '",';
    echo '"' . addslashes($row['sobj_name']) . '"';
    echo "\n";
  }
  else {
?>
 <tr>
  <td class='detail'>
   <?php echo text($row['col1']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['col2']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['proj_name']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['fund_name']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['dept_name']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['sobj_name']); ?>
  </td>
 </tr>
<?php
  } // End not csv export
} // end function thisLineItem

if (!acl_check('acct', 'rep_a')) die(xl("Unauthorized access."));

// 1 = Services, 2 = Facilities, 3 = Both
$form_reptype = intval(empty($_REQUEST['form_reptype']) ? '1' : $_REQUEST['form_reptype']);

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=netsuite_export.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";
}

if (empty($_POST['form_csvexport'])) {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('NetSuite Export') ?></title>
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

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

$(document).ready(function() {
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
});

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php echo xlt('NetSuite Mapping Report')?></h2>

<form method='post' action='netsuite_mapping.php'>

<center>
<table border='0' cellpadding='3'>

 <tr>
  <td>
<?php
// Build a drop-down for report type.
echo "   <select name='form_reptype'>\n";
echo "    <option value='1'" . ($form_reptype == 1 ? ' selected' : '') . ">" . xl('Services'  ) . "\n";
echo "    <option value='2'" . ($form_reptype == 2 ? ' selected' : '') . ">" . xl('Facilities') . "\n";
echo "    <option value='3'" . ($form_reptype == 3 ? ' selected' : '') . ">" . xl('Both'      ) . "\n";
echo "   </select>&nbsp;\n";
?>
   <input type='submit' name='form_refresh' value="<?php echo xlt('Run') ?>">
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
</center>

<?php
} // end not exporting

if (!empty($_REQUEST['form_reptype'])) { // If generating any reports

  // Lengths of PROJ, DEPT, FUND and SOBJ codes. Yes this is a bit lame.
  $projcodelen = 10;
  $deptcodelen = 3;
  $fundcodelen = 3;
  $sobjcodelen = 3;

  // Numeric IDs for these code types.
  $projid = empty($code_types['PROJ']) ? 0 : $code_types['PROJ']['id'];
  $fundid = empty($code_types['FUND']) ? 0 : $code_types['FUND']['id'];
  $deptid = empty($code_types['DEPT']) ? 0 : $code_types['DEPT']['id'];
  $sobjid = empty($code_types['SOBJ']) ? 0 : $code_types['SOBJ']['id'];

  // Joins that work for both services and facilities.
  $morejoins =
    "LEFT JOIN codes AS cf ON cf.code_type = ? AND cp.related_code IS NOT NULL AND " .
    "cp.related_code LIKE '%FUND:%' AND " .
    "cf.code = SUBSTR(cp.related_code, LOCATE('FUND:', cp.related_code) + 5, $fundcodelen) " .
    "LEFT JOIN codes AS cd ON cd.code_type = ? AND cp.related_code IS NOT NULL AND " .
    "cp.related_code LIKE '%DEPT:%' AND " .
    "cd.code = SUBSTR(cp.related_code, LOCATE('DEPT:', cp.related_code) + 5, $deptcodelen) " .
    "LEFT JOIN codes AS cs ON cs.code_type = ? AND cp.related_code IS NOT NULL AND " .
    "cp.related_code LIKE '%SOBJ:%' AND " .
    "cs.code = SUBSTR(cp.related_code, LOCATE('SOBJ:', cp.related_code) + 5, $sobjcodelen) ";

  if (empty($_POST['form_csvexport'])) { // if HTML
    echo "<table width='98%' id='mymaintable' class='mymaintable'>\n";
  }

  if ($form_reptype & 1) { // If generating Services report
    if (!empty($_POST['form_csvexport'])) {
      // CSV headers for Services report
      echo '"' . xl('Service Code'       ) . '",';
      echo '"' . xl('Service Description') . '",';
      echo '"' . xl('Project'            ) . '",';
      echo '"' . xl('Fund'               ) . '",';
      echo '"' . xl('Department'         ) . '",';
      echo '"' . xl('Strategic Objective') . '"';
      echo "\n";
    }
    else {
      // HTML headers for Services report
?>
 <thead>
 <tr bgcolor="#dddddd">
  <td class="dehead">
   <?php echo xlt('Service Code'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Service Description'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Project'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Fund'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Department'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Strategic Objective'); ?>
  </td>
 </tr>
 </thead>
 <tbody>

<?php 
    } // End not export
    // Continuing generation of Services report.

    $query = "SELECT " .
      "c.code AS col1, c.code_text AS col2, " .
      "cp.code_text AS proj_name, cf.code_text AS fund_name, cd.code_text AS dept_name, cs.code_text AS sobj_name " .
      "FROM codes AS c " .
      "LEFT JOIN codes AS cp ON cp.code_type = ? AND c.related_code LIKE '%PROJ:%' AND " .
      "cp.code = SUBSTR(c.related_code, LOCATE('PROJ:', c.related_code) + 5, $projcodelen) " .
      "$morejoins WHERE " .
      "c.code_type = '12' AND c.active = 1 ORDER BY c.code";

    $res = sqlStatement($query, array($projid, $fundid, $deptid, $sobjid));

    while ($row = sqlFetchArray($res)) {
      thisLineItem($row);
    }

    if (empty($_POST['form_csvexport'])) {
      echo " </tbody>\n";
    }

  } // End Services Report

  if ($form_reptype & 2) { // If generating Facilities report

    if (!empty($_POST['form_csvexport'])) {
      // CSV headers for Services report
      echo '"' . xl('Facility Name'      ) . '",';
      echo '"' . xl('Site ID'            ) . '",';
      echo '"' . xl('Project'            ) . '",';
      echo '"' . xl('Fund'               ) . '",';
      echo '"' . xl('Department'         ) . '",';
      echo '"' . xl('Strategic Objective') . '"';
      echo "\n";
    }
    else {
      // HTML headers for Services report
?>
 <thead>
 <tr bgcolor="#dddddd">
  <td class="dehead">
   <?php echo xlt('Facility Name'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Site ID'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Project'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Fund'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Department'); ?>
  </td>
  <td class="dehead">
   <?php echo xlt('Strategic Objective'); ?>
  </td>
 </tr>
 </thead>
 <tbody>

<?php 
    } // End not export
    // Continuing generation of Facilities report.

    $query = "SELECT " .
      "f.name AS col1, f.facility_npi AS col2, " .
      "cp.code_text AS proj_name, cf.code_text AS fund_name, cd.code_text AS dept_name, cs.code_text AS sobj_name " .
      "FROM facility AS f " .
      "LEFT JOIN codes AS cp ON cp.code_type = ? AND f.related_code LIKE '%PROJ:%' AND " .
      "cp.code = SUBSTR(f.related_code, LOCATE('PROJ:', f.related_code) + 5, $projcodelen) " .
      "$morejoins " .
      "ORDER BY f.name";

    $res = sqlStatement($query, array($projid, $fundid, $deptid, $sobjid));

    while ($row = sqlFetchArray($res)) {
      thisLineItem($row);
    }

    if (empty($_POST['form_csvexport'])) {
      echo " </tbody>\n";
    }

  } // End Facilities Report

  if (empty($_POST['form_csvexport'])) {
    echo " </table>\n";
  }
} // End report generation

if (! $_POST['form_csvexport']) {
?>
</form>
</center>
</body>
</html>
<?php
} // End not csv export

// PHP end tag omitted.
