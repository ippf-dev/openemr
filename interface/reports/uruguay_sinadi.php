<?php
// Copyright (C) 2018 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/patient.inc");
require_once("../../custom/code_types.inc.php");

// Check authorization.
$thisauth = acl_check('acct', 'rep');
if (!$thisauth) die(xl('Not authorized'));

$form_facility = isset($_POST['form_facility']) ? $_POST['form_facility'] : '';

// Compute age in years given a DOB and "as of" date.
//
function getAge($dob, $asof='') {
  if (empty($asof)) $asof = date('Y-m-d');
  $a1 = explode('-', substr($dob , 0, 10));
  $a2 = explode('-', substr($asof, 0, 10));
  $age = $a2[0] - $a1[0];
  if ($a2[1] < $a1[1] || ($a2[1] == $a1[1] && $a2[2] < $a1[2])) --$age;
  return $age;
}

// Increment the specified counter for all ages and, where appropriate,
// the limited age range.
//
function increment_counter($which, $age) {
  global $counters;
  if (!isset($counters[$which])) die("No such counter '$which'!");
  if (!is_numeric($age) || $age < 1 || $age > 120) {
    ++$counters[$which][4];
  }
  else if ($age < 15) {
    ++$counters[$which][0];
  }
  else if ($age < 20) {
    ++$counters[$which][1];
  }
  else if ($age < 45) {
    ++$counters[$which][2];
  }
  else {
    ++$counters[$which][3];
  }
}

// Prepare a data value for output as a CSV item.
//
function csvitem($s, $last=false) {
  $s = '"' . str_replace('"', '""', $s) . '"';
  $s .= $last ? "\n" : ",";
  return $s;
}

function write_category_headers($catid, $firsttime=true) {
  global $categories;
  if (!empty($_POST['form_csvexport'])) {
    // CSV headers:
    echo csvitem($categories[$catid]);
    echo csvitem(xl('< 15'));
    echo csvitem(xl('15-19'));
    echo csvitem(xl('20-44'));
    echo csvitem(xl('45+'));
    echo csvitem(xl('S/D'));
    echo csvitem(xl('TOTAL'), true);
  }
  else { // not exporting
    if (!$firsttime) {
      echo " </tbody>\n";
    }
?>
 <thead style='display:table-header-group'>
  <tr>
   <th rowspan='2' class='head' style='width:40%'><?php echo text($categories[$catid]); ?></th>
   <th colspan='5' class='head'><?php echo xlt('EDAD DE LA MUJER'); ?></th>
   <th rowspan='2' class='head' style='width:10%'><?php echo xlt('TOTAL'); ?></th>
  </tr>
  <tr>
   <th class='head' style='width:10%'><?php echo xlt('< 15'); ?></th>
   <th class='head' style='width:10%'><?php echo xlt('15-19'); ?></th>
   <th class='head' style='width:10%'><?php echo xlt('20-44'); ?></th>
   <th class='head' style='width:10%'><?php echo xlt('45+'); ?></th>
   <th class='head' style='width:10%'><?php echo xlt('S/D'); ?></th>
  </tr>
 </thead>
 <tbody>
<?php
  }
}

function write_category_totals($cattotals) {
  $linetotal = 0;
  if (empty($_POST['form_csvexport'])) {
    echo "  <tr>\n";
    echo "   <td class='detail'>" . xlt('TOTAL') . "</td>\n";
    foreach ($cattotals as $total) {
      echo "   <td class='detail' align='right'>" . $total . "</td>\n";
      $linetotal += $total;
    }
    echo "   <td class='detail' align='right'>" . text($linetotal) . "</td>\n";
    echo "  </tr>\n";
  }
  else {
    echo csvitem(xl('TOTAL'));
    foreach ($cattotals as $total) {
      echo csvitem($total);
      $linetotal += $total;
    }
    echo csvitem($linetotal, true);
  }
}

// Array of counter IDs and their values. The 5 numeric values are for
// the age categories.
//
$counters = array(
  'ive2'  => array(),
  'ive3'  => array(),
  'cde'   => array(),
  'ive4'  => array(),
  'ive4c' => array(),
  'ive4s' => array(),
  'ndi'   => array(),
  'pdv'   => array(),
  'tsh'   => array(),
  're'    => array(),
  'ntp'   => array(),
  'sdm'   => array(),
  'mf'    => array(),
  'other' => array(),
);

// Headings to use for report line categories.
//
$categories = array(
  '1' => 'NUMERO DE CONSULTAS IVE',
  '2' => 'Motivo de IVE',
);

// This defines the report lines. Key is line title, value is an array of:
// 0 - Category ID
// 1 - Counter ID
//
$rptlines = array(
  xl('Número de usuarias IVE 2'                    ) => array('1', 'ive2' ),
  xl('Número de usuarias IVE 3 - Ratifica'         ) => array('1', 'ive3' ),
  xl('Continuación del embarazo'                   ) => array('1', 'cde'  ),
  xl('Número de usuarias IVE 4'                    ) => array('1', 'ive4' ),
  xl('  con anticoncepción'                        ) => array('1', 'ive4c'),
  xl('  sin anticoncepción'                        ) => array('1', 'ive4s'),
  xl('Número de interrupción espontánea post IVE-2') => array('1', 'ndi'  ),
  xl('Proyecto de vida'                            ) => array('2', 'pdv'  ),
  xl('Tiene suficientes hijos'                     ) => array('2', 'tsh'  ),
  xl('Razones economicas'                          ) => array('2', 're'   ),
  xl('No tiene pareja'                             ) => array('2', 'ntp'  ),
  xl('Salud de la mujer'                           ) => array('2', 'sdm'  ),
  xl('Malformacion fetal'                          ) => array('2', 'mf'  ),
  xl('Otros'                                       ) => array('2', 'other'),
);

// Initialize values for array of counters.
foreach ($counters as $key => $dummy) {
  $counters[$key] = array(0, 0, 0, 0, 0);
}

$from_date = date('Y-m-01');
$to_date   = date('Y-m-d');
if (isset($_POST['form_from_date'])) {
  $from_date = fixDate($_POST['form_from_date'], $from_date);
  $to_date   = fixDate($_POST['form_to_date'], $to_date);
}

// The selected facility IDs, if any.
$form_facility  = empty($_POST['form_facility']) ? array() : $_POST['form_facility'];

// if (!empty($_POST['form_from_date'])) {

// Now, what sort of query?
// We want:
//   Visits with MA service IVE2-ASR-01
//   Data items from IVE forms
//     IVE2: IVE_mainIVEreason
//     IVE3: IVE_confirmation
//     IVE3: IVE_V2CntrMismoV3
//     IVE3: IVE_method
//     IVE3: IVE_rejection
//     IVE4: IVE_contmetcounsel
// I think we are counting abortion cases within a date range.
// For each client we need to gather some data from those visits.
// 

// If facilities are specified.
$factest = "";
if (!empty($form_facility)) {
  $factest = "AND (1 = 2";
  foreach ($form_facility as $fac) {
    $factest .= " OR fe.facility_id = '" . add_escape_custom($fac) . "'";
  }
  $factest .= ")";
}

$query = "SELECT " .
  "fe.pid, fe.encounter, SUBSTR(fe.date, 1, 10) AS encdate, " .
  "v1.field_value AS IVE_mainIVEreason, " .           // IVE2 IVE_main
  "v2.field_value AS IVE_confirmation, " .            // IVE3 yesno
  "v3.field_value AS IVE_V2CntrMismoV3, " .           // IVE3 yesno
  "v4.field_value AS IVE_method, " .                  // IVE3 IVE_meth
  "v5.field_value AS IVE_rejection, " .               // IVE3 yesno
  "v6.field_value AS IVE_contmetcounsel, " .          // IVE4 IVE_contrameth
  "(SELECT COUNT(b.id) FROM billing AS b WHERE b.pid = fe.pid and b.encounter = fe.encounter AND b.code_type = 'MA' AND b.code = 'IVE2-ASR-01' AND b.activity = 1) AS asrcount, " .
  "(SELECT COUNT(f.id) FROM forms AS f WHERE f.pid = fe.pid and f.encounter = fe.encounter AND f.formdir = 'LBFIve' AND f.deleted = 0) AS ive1count, " .
  "pd.DOB " .
  "FROM form_encounter AS fe " .
  "JOIN patient_data AS pd ON pd.pid = fe.pid " .
  "JOIN forms AS f ON f.pid = fe.pid AND f.encounter = fe.encounter AND " .
  "f.formdir = 'newpatient' AND f.form_id = fe.id AND f.deleted = 0 " .
  "LEFT JOIN shared_attributes AS v1 ON v1.pid = fe.pid AND v1.encounter = fe.encounter AND v1.field_id = 'IVE_mainIVEreason' " .
  "LEFT JOIN shared_attributes AS v2 ON v2.pid = fe.pid AND v2.encounter = fe.encounter AND v2.field_id = 'IVE_confirmation' " .
  "LEFT JOIN shared_attributes AS v3 ON v3.pid = fe.pid AND v3.encounter = fe.encounter AND v3.field_id = 'IVE_V2CntrMismoV3' " .
  "LEFT JOIN shared_attributes AS v4 ON v4.pid = fe.pid AND v4.encounter = fe.encounter AND v4.field_id = 'IVE_method' " .
  "LEFT JOIN shared_attributes AS v5 ON v5.pid = fe.pid AND v5.encounter = fe.encounter AND v5.field_id = 'IVE_rejection' " .
  "LEFT JOIN shared_attributes AS v6 ON v6.pid = fe.pid AND v6.encounter = fe.encounter AND v6.field_id = 'IVE_contmetcounsel' " .
  "WHERE fe.date >= DATE_SUB(?, INTERVAL 2 MONTH) AND fe.date <= ? $factest " .
  "ORDER BY fe.pid, encdate, fe.encounter";

$res = sqlStatement($query, array("$from_date 00:00:00", "$to_date 23:59:59"));

// Array index 2-4 indicates if got form IVE2-IVE4 for this case, respectively.
$got_ive = array(false, false, false, false, false);
$last_pid = '';
$last_encdate = '0000-00-00';

while ($row = sqlFetchArray($res)) {

  //  If new client or IVE1 form or visit date much > last IVE date,
  //  Clear "encountered" flags.
  $days = (strtotime($row['encdate']) - strtotime($last_encdate)) / (60 * 60 * 24);
  if ($row['pid'] != $last_pid || $row['ive1count'] || $days > 90) {
    $got_ive = array(false, false, false, false, false);
  }

  // Compute age category.
  $age = getAge($row['DOB'], $row['encdate']);

  // IVE2 stuff.
  if ($row['asrcount']) {
    if (!$got_ive[2]) {
      $got_ive[2] = true;
      $last_encdate = $row['encdate'];
      if ($row['encdate'] >= $from_date) {
        increment_counter('ive2', $age);
        // Count reason for abortion.
        // This assumes the IVE2 form is in the same visit with the IVE2-ASR-01 service. True?
        // If not and one of these is missing, what do we do?
        $reason = 'other';
        if (!empty($row['IVE_mainIVEreason'])) {
          if ($row['IVE_mainIVEreason'] == 'Ive_main_1') {
            $reason = 'pdv';
          }
          if ($row['IVE_mainIVEreason'] == 'Ive_main_2') {
            $reason = 're';
          }
          if ($row['IVE_mainIVEreason'] == 'Ive_main_3') {
            $reason = 'ntp';
          }
          if ($row['IVE_mainIVEreason'] == 'Ive_main_4') {
            $reason = 'tsh';
          }
          if ($row['IVE_mainIVEreason'] == 'Ive_main_5') {
            $reason = 'sdm';
          }
          if ($row['IVE_mainIVEreason'] == 'Ive_main_6') {
            $reason = 'mf';
          }
          increment_counter($reason, $age);
        }
      }
    }
  }

  // IVE3 stuff.
  if (isset($row['IVE_method'])) {
    if (!$got_ive[3]) {
      $got_ive[3] = true;
      $last_encdate = $row['encdate'];
      if ($row['encdate'] >= $from_date) {
        if (
          !empty($row['IVE_confirmation']) && $row['IVE_confirmation'] == 'YES' &&
          !empty($row['IVE_V2CntrMismoV3']) && $row['IVE_V2CntrMismoV3'] == 'YES' &&
          $row['IVE_method'] != 'None'
        ) {
          increment_counter('ive3', $age);
        }
        if (
          (empty($row['IVE_confirmation']) || $row['IVE_confirmation'] != 'YES') &&
          !empty($row['IVE_rejection']) && $row['IVE_rejection'] == 'YES' &&
          $row['IVE_method'] == 'None'
        ) {
          increment_counter('cde', $age);
        }
      }
    }
  }

  // IVE4 stuff.
  if (isset($row['IVE_contmetcounsel'])) {
    if (!$got_ive[4]) {
      $got_ive[4] = true;
      $last_encdate = $row['encdate'];
      if ($row['encdate'] >= $from_date) {
        increment_counter('ive4', $age);
        if ($row['IVE_contmetcounsel'] != 'Non' && $row['IVE_contmetcounsel'] != 'Nuse') {
          increment_counter('ive4c', $age);
        }
        else {
          increment_counter('ive4s', $age);
        }
      }
    }
  }

}

// } // end if (!empty($_POST['form_from_date']))

if (!empty($_POST['form_csvexport'])) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=cervical_cancer_statistics.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";
}
else { // not exporting

?>
<html>

<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title><?php echo xlt('Sinadi Statistics'); ?></title>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<style>
th.head, td.head { font-size:10pt; font-weight:bold; background-color:#cccccc; text-align:center; }
td.subhead { font-size:10pt; font-weight:bold; background-color:#8888cc; text-align:left; padding-left:5pt; padding-right:5pt; }
td.detail { font-size:10pt; background-color:#ccccff; padding-left:5pt; padding-right:5pt; }
a, a:visited, a:hover { color:#0000cc; }
</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="JavaScript">
 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';
</script>
</head>

<body>
<center>

<form method='post' action='uruguay_sinadi.php'>
<table border='0' cellpadding='5' cellspacing='0' width='98%'>
 <tr>
  <td class='title' rowspan='2'>
   <?php xl('Sinadi Statistics','e'); ?>
  </td>
  <td class='text' rowspan='2'>
<?php
// Build a drop-down list of facilities.
//
$query = "SELECT id, name FROM facility ORDER BY name";
$fres = sqlStatement($query);
echo "   <select name='form_facility[]' multiple='multiple' " .
  "title='" . xla('Select one or more clinics, or none for all clinics.') . "'>\n";
while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  echo "    <option value='$facid'";
  if (in_array($facid, $form_facility)) echo " selected";
  echo ">" . text($frow['name']) . "</option>\n";
}
echo "   </select>\n";
?>
  </td>
  <td class='text' align='right' nowrap>
   <?php echo xlt('From'); ?>
   <input type='text' name='form_from_date' id='form_from_date' size='10' value='<?php echo $from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Start date yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
   <?php echo xlt('To'); ?>
   <input type='text' name='form_to_date' id='form_to_date' size='10' value='<?php echo $to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='End date yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>&nbsp;
  </td>
 </tr>
 <tr>
  <td class='text' align='right' nowrap>
   <input type="submit" name="form_refresh" value="<?php xl('Refresh','e'); ?>" />&nbsp;
   <input type="submit" name="form_csvexport" value="<?php echo xl('Export to CSV'); ?>">&nbsp;
   <input type="button" value="<?php xl('Print','e'); ?>" onclick="window.print()" />
  </td>
 </tr>
</table>
</form>

<?php // if (!empty($_POST['form_from_date'])) { ?>

<table width='98%' cellpadding='2' cellspacing='2'>
<?php

// } // end if (!empty($_POST['form_from_date']))

} // end not exporting

// if (!empty($_POST['form_from_date'])) {

// Statistics are collected, now output them.

$lastcat = '';
$cattotals = array(0, 0, 0, 0, 0);
foreach ($rptlines as $key => $value) {
  $linetotal = 0;
  if ($lastcat != $value[0]) {
    if ($lastcat) {
      write_category_totals($cattotals);
      $cattotals = array(0, 0, 0, 0, 0);
    }
    write_category_headers($value[0], !$lastcat);
  }
  if (empty($_POST['form_csvexport'])) {
    echo "  <tr>\n";
    echo "   <td class='detail'>" . text($key) . "</td>\n";
    for ($i = 0; $i < 5; ++$i) {
      echo "   <td class='detail' align='right'>" . text($counters[$value[1]][$i]) . "</td>\n";
      $linetotal += $counters[$value[1]][$i];
      $cattotals[$i] += $counters[$value[1]][$i];
    }
    echo "   <td class='detail' align='right'>" . text($linetotal) . "</td>\n";
    echo "  </tr>\n";
  }
  else {
    echo csvitem($key);
    for ($i = 0; $i < 5; ++$i) {
      echo csvitem($counters[$value[1]][$i]);
      $linetotal += $counters[$value[1]][$i];
      $cattotals[$i] += $counters[$value[1]][$i];
    }
    echo csvitem($linetotal, true);
  }
  $lastcat = $value[0];
}
write_category_totals($cattotals);

// } // end if (!empty($_POST['form_from_date']))

if (empty($_POST['form_csvexport'])) {
?>
 </tbody>
</table>

</center>

<script language='JavaScript'>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>

</body>
</html>
<?php
} // end not exporting
