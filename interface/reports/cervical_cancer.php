<?php
// Copyright (C) 2014-2016 Rod Roark <rod@sunsetsystems.com>
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

// Recursive function to look up the IPPF2 (or other type) code, if any,
// for a given related code field.
//
function get_related_code($related_code, $typewanted='IPPF2', $depth=0) {
  global $code_types;
  // echo "<!-- related_code = '$related_code' depth = '$depth' -->\n"; // debugging
  if (++$depth > 4) return false; // protects against relation loops
  if (empty($related_code)) return false;
  $relcodes = explode(';', $related_code);
  foreach ($relcodes as $codestring) {
    if ($codestring === '') continue;
    list($codetype, $code) = explode(':', $codestring);
    if ($codetype === $typewanted) {
      // echo "<!-- returning '$code' -->\n"; // debugging
      return $code;
    }
    $row = sqlQuery("SELECT related_code FROM codes WHERE " .
      "code_type = ? AND code = ? AND active = 1 " .
      "ORDER BY id LIMIT 1",
      array($code_types[$codetype]['id'], $code));
    $tmp = get_related_code($row['related_code'], $typewanted, $depth);
    if ($tmp !== false) {
      return $tmp;
    }
  }
  return false;
}

// Increment the specified counter for all ages and, where appropriate,
// the limited age range.
//
function increment_counter($which, $age) {
  global $counters;
  if (!isset($counters[$which])) die("No such counter '$which'!");
  ++$counters[$which][0];
  if ($age >= 30 && $age <= 49) ++$counters[$which][1];
}

// Prepare a data value for output as a CSV item.
//
function csvitem($s, $last=false) {
  $s = '"' . str_replace('"', '""', $s) . '"';
  $s .= $last ? "\n" : ",";
  return $s;
}

// Array of counter IDs and their values. The 2 numeric values are for all ages
// and for the limited age range, respectively.
//
$counters = array(
  'screenings'  => array(), // Number of screenings
  'firsttime'   => array(), // Number of first time screenings
  'hiv'         => array(), // Number of screenings of HIV+ clients
  'pos'         => array(), // Number of positive screenings
  'posecryo'    => array(), // Number of positive screenings eligible for cryo
  'cryoiref'    => array(), // Number of pos with cryo internal referral
  'cryoxref'    => array(), // Number of pos with cryo external referral
  'leepiref'    => array(), // Number of pos with leep internal referral
  'leep+xref'   => array(), // Number of pos with leep-or-higher external referral
  'thisorgcryo' => array(), // Number of pos with cryo local treatment or internal cryo counter-referral
  'thisorgleep' => array(), // Number of pos with leep local treatment or internal leep counter-referral
  'anycryo'     => array(), // Number of pos with cryo local treatment or any cryo counter-referral
  'anynoncryo'  => array(), // Number of pos with non-cryo local treatment or any non-cryo counter-referral
  'ecryoany'    => array(), // Number of pos eligible for cryo with any local or counter-referral treatment
  'xrefcryo'    => array(), // Number of pos with cryo external counter-referral treatment
  'xrefleep+'   => array(), // Number of pos with leep-or-higher external counter-referral treatment
  'xrefany'     => array(), // Number of pos with any external counter-referral treatment
);

// Headings to use for report line categories.
//
$categories = array(
  '2.2' => 'Screening Services',
  '2.3' => 'First Time Users and HIV+ Users',
  '2.4' => 'Clients with Positive Results',
  '2.5' => 'Referrals',
  '3.2' => 'Cryotherapy and LEEP',
  '3.3' => 'External Treatments',
  '3.4' => 'Total Clients Treated',
  '4'   => 'Timing of Treatment',
);

// This defines the report lines. Key is line title, value is an array of:
// 0 - Category ID
// 1 - Counter ID for numerator
// 2 - Counter ID for denominator if percents are to be computed
//
$rptlines = array(
  xl('Screenings')
    => array('2.2', 'screenings' , ''          ),

  xl('First time screenings')
    => array('2.3', 'firsttime'  , 'screenings'),
  xl('Screenings of HIV+ clients')
    => array('2.3', 'hiv'        , 'screenings'),

  xl('Screenings testing positive')
    => array('2.4', 'pos'        , 'screenings'),
  xl('Screenings testing positive and eligible for cryotherapy')
    => array('2.4', 'posecryo'   , 'pos'       ),

  xl('Treated  with cryotherapy by same network provider')
    => array('3.2', 'thisorgcryo', 'pos'       ),
  xl('Treated  with LEEP by same network provider')
    => array('3.2', 'thisorgleep', 'pos'       ),

  xl('Screenings testing positive referred to same organisation provider for cryotherapy')
    => array('2.5', 'cryoiref'   , 'pos'       ),
  xl('Screenings testing positive referred to external provider for cryotherapy')
    => array('2.5', 'cryoxref'   , 'pos'       ),
  xl('Screenings testing positive referred to same organisation provider for LEEP')
    => array('2.5', 'leepiref'   , 'pos'       ),
  xl('Screenings testing positive referred to external provider for LEEP or higher')
    => array('2.5', 'leep+xref'  , 'pos'       ),

  // 2016-06-27 CV requested removal of 3.3 and positioning 2.5 after 3.2.
  /********************************************************************
  xl('Referred to external provider and treated with cryotherapy')
    => array('3.3', 'xrefcryo'   , 'pos'       ),
  xl('Referred to external provider and treated with LEEP or higher')
    => array('3.3', 'xrefleep+'  , 'pos'       ),
  xl('Referred to external provider and treated with any method')
    => array('3.3', 'xrefany'    , 'pos'       ),
  ********************************************************************/

  xl('Treated with cryotherapy by any provider')
    => array('3.4', 'anycryo'    , 'pos'       ),
  xl('Treated with other methods by any provider')
    => array('3.4', 'anynoncryo' , 'pos'       ),

  // xl('Eligible for cryotherapy and treated by any provider')
  //   => array('4',   'ecryoany'   , 'posecryo'  ),

  // TBD: 4.x ?
);

// Initialize values for array of counters.
foreach ($counters as $key => $dummy) {
  $counters[$key] = array(0, 0);
}

// Tracks which referrals have already been used. Key is forms.id.
// Value is irrelevant. Avoids counting any referral more than once.
$refsused = array();

$from_date = fixDate($_POST['form_from_date']);
$to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

// Main query, LBFcercan form instances. If a visit has multiple LBFcercan
// forms we will take only the last one.
$query = "SELECT " .
  "fe.pid, fe.encounter, fe.date AS encdate, " .
  "pd.sex, pd.DOB, fcc.form_id AS cc_form_id, " .
  "d1.field_value AS isfirst, " .
  "d2.field_value AS ishivpos, " .
  "d3.field_value AS ccresult, " .
  "v1.field_value AS CC_PrevScreen, " .
  "v2.field_value AS HA_HIVStatus, " .
  "v3.field_value AS VIA_Results, " .
  "v4.field_value AS VIA_CryoEligible " .
  "FROM form_encounter AS fe " .
  "JOIN patient_data AS pd ON pd.pid = fe.pid " .
  "JOIN forms AS f ON f.pid = fe.pid AND f.encounter = fe.encounter AND " .
  "f.formdir = 'newpatient' AND f.form_id = fe.id AND f.deleted = 0 " .
  "JOIN forms AS fcc ON fcc.pid = fe.pid AND fcc.encounter = fe.encounter AND " .
  "fcc.formdir = 'LBFcercan' AND fcc.deleted = 0 " .
  "LEFT JOIN lbf_data AS d1 ON d1.form_id = fcc.form_id AND d1.field_id = 'isfirst' " .
  "LEFT JOIN lbf_data AS d2 ON d2.form_id = fcc.form_id AND d2.field_id = 'ishivpos' " .
  "LEFT JOIN lbf_data AS d3 ON d3.form_id = fcc.form_id AND d3.field_id = 'ccresult' " .
  "LEFT JOIN shared_attributes AS v1 ON v1.pid = fe.pid AND v1.encounter = fe.encounter AND v1.field_id = 'CC_PrevScreen' " .
  "LEFT JOIN shared_attributes AS v2 ON v1.pid = fe.pid AND v2.encounter = fe.encounter AND v2.field_id = 'HA_HIVStatus' " .
  "LEFT JOIN shared_attributes AS v3 ON v1.pid = fe.pid AND v3.encounter = fe.encounter AND v3.field_id = 'VIA_Results' " .
  "LEFT JOIN shared_attributes AS v4 ON v1.pid = fe.pid AND v4.encounter = fe.encounter AND v4.field_id = 'VIA_CryoEligible' " .
  "WHERE fe.date >= ? AND fe.date <= ? " .
  "ORDER BY fe.pid, fe.encounter, fcc.form_id DESC";
$res = sqlStatement($query, array("$from_date 00:00:00", "$to_date 23:59:59"));

$encounter_id = 0;

while ($row = sqlFetchArray($res)) {
  if ($row['encounter'] == $encounter_id) continue;
  $encounter_id = $row['encounter'];
  $patient_id   = $row['pid'];
  $encdate      = substr($row['encdate'], 0, 10);
  $positive     = false;
  $local_treatment = false;

  // Translate new VIA Screening Form results, if present, to values from the old form.
  if ($row['CC_PrevScreen'   ] == 'NO' ) $row['isfirst' ] = 1;
  if ($row['HA_HIVStatus'    ] == 'Pos') $row['ishivpos'] = 1;
  if ($row['VIA_Results'     ] == 'Pos') $row['ccresult'] = 'pos';
  if ($row['VIA_CryoEligible'] == 'YES') $row['ccresult'] = 'poscryo';

  // Client age at screening.
  $age = getAge($row['DOB'], $encdate);

  // Increment counters related to just the screening.
  increment_counter('screenings', $age);
  if (!empty($row['isfirst'])) {
    increment_counter('firsttime', $age);
  }
  if (!empty($row['ishivpos'])) {
    increment_counter('hiv', $age);
  }
  if (!empty($row['ccresult'])) {
    if ($row['ccresult'] == 'pos' || $row['ccresult'] == 'poscryo') {
      $positive = true;
      increment_counter('pos', $age);
      if ($row['ccresult'] == 'poscryo') {
        increment_counter('posecryo', $age);
      }
    }
  }

  // echo "<!-- pid: '$patient_id' encdate: '$encdaate' positive: '$positive' -->\n"; // debugging

  if ($positive) {
    // Process services in this and subsequent encounters up to 90 days forward.
    $bres = sqlQuery("SELECT c.related_code " .
      "FROM form_encounter AS fe, billing AS b, codes AS c WHERE " .
      "fe.pid = ? AND " .
      "fe.date >= ? AND " .
      "DATE_SUB(fe.date, INTERVAL 90 DAY) < ? AND " .
      "b.pid = fe.pid AND " .
      "b.encounter = fe.encounter AND " .
      "b.activity = 1 AND " .
      "b.code_type = ? AND c.code_type = ? AND " .
      "c.code = b.code AND c.modifier = b.modifier " .
      "ORDER BY fe.encounter, b.id",
      array($patient_id, $encdate, $encdate, 'MA', '12'));
    while ($brow = sqlFetchArray($bres)) {
      // echo "<!-- from service: '{$brow['related_code']}' -->\n"; // debugging
      if (!empty($brow['related_code'])) {
        $relcodes = explode(';', $brow['related_code']);
        foreach ($relcodes as $codestring) {
          if ($codestring === '') continue;
          list($codetype, $code) = explode(':', $codestring);
          if ($codetype === 'IPPF2') {
            // process_ippf_code($code);
            if ($code === '2143230302401') {
              // Gynecology - Management - Surgical – Cryosurgery
              increment_counter('thisorgcryo', $age);
              increment_counter('anycryo'    , $age);
              $local_treatment = true;
            }
            else if ($code === '2143230302402') {
              // Gynaecology - Management - Surgical – Cauterisation
              // Per JG 2014-06-26 this means LEEP.
              increment_counter('thisorgleep', $age);
              increment_counter('anynoncryo' , $age);
              $local_treatment = true;
            }
            else if ($code === '2143230302999') {
              // Gynaecology - Management - Surgical - other
              // Per JG 2014-06-26 this means any other cervical cancer treatment.
              increment_counter('anynoncryo' , $age);
              $local_treatment = true;
            }
          }
          if ($local_treatment) break;
        }
      }
      if ($local_treatment) break;
    }

    if ($local_treatment) {
      if ($replycode && $row['ccresult'] == 'poscryo') {
        increment_counter('ecryoany', $age);
      }
    }
    else {
      // Process outgoing referrals starting from the encounter date up to 90 days forward.
      $query = "SELECT " .
        "f.id, " .
        "d2.field_value AS refer_external, " .
        "d3.field_value AS refer_related_code, " .
        "d4.field_value AS reply_related_code " .
        "FROM forms AS f " .
        "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'refer_date' " .
        "JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'refer_external' " .
        "LEFT JOIN lbf_data AS d3 ON d3.form_id = f.form_id AND d3.field_id = 'refer_related_code' " .
        "LEFT JOIN lbf_data AS d4 ON d4.form_id = f.form_id AND d4.field_id = 'reply_related_code' " .
        "WHERE " .
        "f.pid = ? AND " .
        "f.formdir = 'LBFref' AND f.deleted = 0 AND " .
        "d1.field_value IS NOT NULL AND " .
        "d1.field_value >= ? AND " .
        "DATE_SUB(d1.field_value, INTERVAL 90 DAY) < ? AND " .
        "(d2.field_value = ? OR d2.field_value = ?) " .
        "ORDER BY f.id";

      // echo "<!-- $patient_id $encdate $query -->\n"; // debugging

      $rres = sqlStatement($query, array($patient_id, $encdate, $encdate, '2', '3'));

      while ($rrow = sqlFetchArray($rres)) {
        // echo "<!-- from referral {$rrow['id']}: '{$rrow['refer_related_code']}' '{$rrow['reply_related_code']}' -->\n"; // debugging
        if (isset($refsused[$rrow['id']])) {
          // This referral has already been processed, so skip it.
          continue;
        }
        $refsused[$rrow['id']] = $encounter_id;
        $external = $rrow['refer_external'] == '2';
        $refercode = get_related_code($rrow['refer_related_code']);
        $replycode = get_related_code($rrow['reply_related_code']);
        if ($refercode === '2143230302401') {
          // Gynecology - Management - Surgical – Cryosurgery
          if ($external) {
            increment_counter('cryoxref', $age);
          }
          else {
            increment_counter('cryoiref', $age);
          }
        }
        else if ($refercode === '2143230302402') {
          // Gynaecology - Management - Surgical – Cauterisation
          // Per JG 2014-06-26 this means LEEP.
          if ($external) {
            increment_counter('leep+xref', $age);
          }
          else {
            increment_counter('leepiref', $age);
          }
        }
        else if ($refercode === '2143230302999') {
          // Gynaecology - Management - Surgical - other
          // Per JG 2014-06-26 this means any other cervical cancer treatment.
          if ($external) {
            increment_counter('leep+xref', $age);
          }
          else {
            // Other treatment referrals within the MA do not seem to be contemplated.
          }
        }
        else {
          $refercode = false;
        }
        if ($replycode === '2143230302401') {
          // Gynecology - Management - Surgical – Cryosurgery
          if ($external) {
            increment_counter('xrefcryo', $age);
            increment_counter('xrefany', $age);
          }
          else {
            increment_counter('thisorgcryo', $age);
          }
          increment_counter('anycryo', $age);
        }
        else if ($replycode === '2143230302402') {
          // Gynaecology - Management - Surgical – Cauterisation
          // Per JG 2014-06-26 this means LEEP.
          if ($external) {
            increment_counter('xrefleep+', $age);
            increment_counter('xrefany', $age);
          }
          else {
            increment_counter('thisorgleep', $age);
          }
          increment_counter('anynoncryo', $age);
        }
        else if ($replycode === '2143230302999') {
          // Gynaecology - Management - Surgical - other
          // Per JG 2014-06-26 this means any other cervical cancer treatment.
          if ($external) {
            increment_counter('xrefleep+', $age);
            increment_counter('xrefany', $age);
          }
          else {
            // Not contemplated.
          }
          increment_counter('anynoncryo', $age);
        }
        else {
          $replycode = false;
        }
        if ($replycode && $row['ccresult'] == 'poscryo') {
          increment_counter('ecryoany', $age);
        }
        if ($refercode || $replycode) break;
      }
    }
  }
} // end while

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

  // CSV headers:
  echo csvitem(xl('Measure'));
  echo csvitem(xl('All Ages Count'));
  echo csvitem(xl('All Ages Percent'));
  echo csvitem(xl('30-49 Count'));
  echo csvitem(xl('30-49 Percent'), true);
}
else { // not exporting

?>
<html>

<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title><?php  xl('Cervical Cancer Statistics','e'); ?></title>
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

<form method='post' action='cervical_cancer.php'>
<table border='0' cellpadding='5' cellspacing='0' width='98%'>
 <tr>
  <td class='title'>
   <?php xl('Cervical Cancer Statistics','e'); ?>
  </td>
  <td class='text' align='right'>
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
   <input type="submit" value="<?php xl('Refresh','e'); ?>" />&nbsp;
   <input type="submit" name="form_csvexport" value="<?php echo xl('Export to CSV'); ?>">&nbsp;
   <input type="button" value="<?php xl('Print','e'); ?>" onclick="window.print()" />
  </td>
 </tr>
</table>
</form>

<table width='98%' cellpadding='2' cellspacing='2'>
 <thead style='display:table-header-group'>
  <tr>
   <th rowspan='2' class='head' style='width:60%'><?php echo xlt('Measure'); ?></th>
   <th colspan='2' class='head'><?php echo xlt('All Ages'); ?></th>
   <th colspan='2' class='head'><?php echo xlt('Ages 30-49'); ?></th>
  </tr>
  <tr>
   <th class='head' style='width:10%'><?php echo xlt('Count'); ?></th>
   <th class='head' style='width:10%'><?php echo xlt('Percent'); ?></th>
   <th class='head' style='width:10%'><?php echo xlt('Count'); ?></th>
   <th class='head' style='width:10%'><?php echo xlt('Percent'); ?></th>
  </tr>
 </thead>
 <tbody>

<?php
} // end not exporting

$lastcat = '';
foreach ($rptlines as $key => $value) {
  $num1 = $counters[$value[1]][0]; // count for all ages
  $num2 = $counters[$value[1]][1]; // count for 30-49
  $pct1 = '';
  $pct2 = '';
  if ($value[2]) {
    // A denominator is defined so we can compute percents.
    $den1 = $counters[$value[2]][0];
    $den2 = $counters[$value[2]][1];
    if ($den1) $pct1 = sprintf('%0.1f', $num1 * 100 / $den1);
    if ($den2) $pct2 = sprintf('%0.1f', $num2 * 100 / $den2);
  }
  if (empty($_POST['form_csvexport'])) {
    if ($lastcat != $value[0]) {
      $lastcat = $value[0];
      echo "  <tr><td class='subhead' colspan='5'>" . text($categories[$lastcat]) . "</td></tr>\n";
    }
    echo "  <tr>\n";
    echo "   <td class='detail'>" . text($key) . "</td>\n";
    echo "   <td class='detail' align='right'>" . text($num1) . "</td>\n";
    echo "   <td class='detail' align='right'>" . text($pct1) . "</td>\n";
    echo "   <td class='detail' align='right'>" . text($num2) . "</td>\n";
    echo "   <td class='detail' align='right'>" . text($pct2) . "</td>\n";
    echo "  </tr>\n";
  }
  else {
    echo csvitem($key);
    echo csvitem($num1);
    echo csvitem($pct1);
    echo csvitem($num2);
    echo csvitem($pct2, true);
  }
}

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
