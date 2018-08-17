<?php
// Copyright (C) 2008-2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This module creates statistical reports related to family planning
// and sexual and reproductive health.

require_once("../globals.php");
require_once("../../library/patient.inc");
require_once("../../library/acl.inc");
require_once("../../library/options.inc.php");
require_once("../../library/formdata.inc.php");
require_once("../../custom/code_types.inc.php");

$alertmsg = '';

if (!acl_check('acct', 'rep_a')) die(xl("Unauthorized access."));

/**
 * 
 * @param type $post_variable
 * @return type an integer representing the parsed post variable, or null if non-existent or not numeric
 * if the return value is null, it will not be used as a filter.
 * 
 */
function validate_age_filter($post_variable)
{
    if(isset($_POST[$post_variable]))
    {
        $value=$_POST[$post_variable];
        if(is_numeric($value))
        {
            return intval($value);
        }
        else
        {
            return null;
        }
    }
    else
    {
        return null;
    }
}

/**
 * @global type $age_maximum
 * @global type $age_minimum
 * @param type $encdate  the field to compare the DOB to in order to determine the age at the time for the given record (typically fe.date)
 * @return string SQL clause to filter the age
 */
function sql_age_filter($encdate)
{
    // Age min/max is already validated to be an intval so no need to escape in SQL
    global $age_maximum,$age_minimum;
    $retval= "";
    if(!is_null($age_minimum))
    {
        $retval.= " AND (timestampdiff(YEAR,pd.DOB,$encdate) >= ".$age_minimum . " OR timestampdiff(YEAR,pd.DOB,$encdate) is null )"; // If DOB is null then age is very large, and hence will be included
    }
    
    if(!is_null($age_maximum))
    {
        $retval.= " AND (timestampdiff(YEAR,pd.DOB,$encdate) <= ".$age_maximum . " AND timestampdiff(YEAR,pd.DOB,$encdate) is NOT null   )"; // If DOB is null then age is very large then it's above the maximum and thus excluded
        
    }
    
    return $retval;
}

$report_type = empty($_GET['t']) ? 'i' : $_GET['t'];

$from_date     = fixDate($_POST['form_from_date']);
$to_date       = fixDate($_POST['form_to_date'], date('Y-m-d'));
$form_by_arr   = $_POST['form_by'];       // this is an array
$form_show     = $_POST['form_show'];     // this is an array
$form_fac_arr  = is_array($_POST['form_facility']) ? $_POST['form_facility'] : array();
$form_adjreason = isset($_POST['form_adjreason']) ? $_POST['form_adjreason'] : '';
$form_chargecat = isset($_POST['form_chargecat']) ? $_POST['form_chargecat'] : '';
$form_svccat   = isset($_POST['form_svccat']) ? $_POST['form_svccat'] : '';
$form_related_code = isset($_POST['form_related_code']) ? $_POST['form_related_code'] : '';
$form_sexes    = isset($_POST['form_sexes']) ? $_POST['form_sexes'] : '4';
$form_content  = isset($_POST['form_content']) ? $_POST['form_content'] : '1';
$form_sortby   = isset($_POST['form_sortby']) ? $_POST['form_sortby'] : '1';
$form_output   = isset($_POST['form_output']) ? 0 + $_POST['form_output'] : 1;

$age_minimum = validate_age_filter('age_minimum');
$age_maximum = validate_age_filter('age_maximum');

// Clinics: 0=Combined, 1=Detail
$form_clinics  = isset($_POST['form_clinics']) ? 0 + $_POST['form_clinics'] : 0;

// Periods: 0=None, 1=Months, 2=Quarters, 3=Years.
$form_periods  = isset($_POST['form_periods']) ? 0 + $_POST['form_periods'] : 0;

// One or more of these are chosen as the left column, or Y-axis, of the report.
//
if ($report_type == 'm') {
  $report_title = xl('Member Association Statistics Report');
  $report_name_prefix = 'MA';
  $arr_by = array(
    101 => xl('MA Category'),
    102 => xl('All Services'),
    106 => xl('Service by Category'),         // new on 3/2016
    6   => xl('Contraceptive Method'),        // reactivated 2/2012 same as in ippf report
    105 => xl('Contraceptive Products'),      // new on 2/2012
    17  => xl('Clients who had a visit'),
    9   => xl('Outbound Internal Referrals'),
    10  => xl('Outbound External Referrals'),
    14  => xl('Inbound Internal Referrals'),
    15  => xl('Inbound External Referrals'),
    103 => xl('Referral Source'),
    2   => xl('Total'),
  );
  $arr_content = array(
    1 => xl('Services'),
    2 => xl('Unique Clients'),
    4 => xl('Unique New Clients'),
    7 => xl('Unique Returning Clients'),
    6 => xl('New Users'),                             // new on 2/2012 (as "Acceptors New to Modern Contraception")
    5 => xl('Contraceptive Items Provided'),          // reactivated 2/2012
  );
  $arr_invalid = array(
    101 => array(5,6),
    102 => array(5,6),
    106 => array(5,6),
    6   => array(5),
    105 => array(1,2,4,7),
    17  => array(5,6),
    9   => array(5,6),
    10  => array(5,6),
    14  => array(5,6),
    15  => array(5,6),
    103 => array(5,6),
    2   => array(5,6),
  );
}
else if ($report_type == 'g') {
  $report_title = xl('GCAC Statistics Report');
  $report_name_prefix = 'GCAC';
  $arr_by = array(
    13 => xl('Abortion-Related Categories'),
    1  => xl('Total SRH & Family Planning'),
    12 => xl('Pre-Abortion Counseling'),
    5  => xl('Abortion Method'), // includes surgical and drug-induced
    8  => xl('Post-Abortion Followup'),
    7  => xl('Post-Abortion Contraception'),
    11 => xl('Complications of Abortion'),
    10  => xl('Outbound External Referrals'),
    20  => xl('External Referral Followups'),
  );
  $arr_content = array(
    1 => xl('Services'),
    2 => xl('Unique Clients'),
    4 => xl('Unique New Clients'),
    7 => xl('Unique Returning Clients'),
  );
}
else {
  $report_title = xl('IPPF Statistics Report');
  $report_name_prefix = 'IPPF';
  $arr_by = array(
    3  => xl('General Service Category'),
    4  => xl('All Services'),
    107 => xl('Service by Category'),         // new on 3/2016
    104 => xl('Specific Contraceptive Service'),
    6  => xl('Contraceptive Method'),
    9   => xl('Outbound Internal Referrals'),
    10  => xl('Outbound External Referrals'),
    14  => xl('Inbound Internal Referrals'),
    15  => xl('Inbound External Referrals'),
  );
  $arr_content = array(
    1 => xl('Services'),
    6 => xl('New Users'),                     // new for 2014
    5 => xl('Contraceptive Items Provided'),
  );
  $arr_invalid = array(                       // Per CV email 2014-01-30
    3   => array(5,6),
    4   => array(5,6),
    107 => array(5,6),
    104 => array(5),
    6   => array(6),
    9   => array(5,6),
    10  => array(5,6),
    14  => array(5,6),
    15  => array(5,6),
  );
}

$arr_sortby = array(
  1 => xl('Code'),
  2 => xl('Description'),
);

$arr_ippf2_cats = array(
  '1'   => xl('CONTRACEPTIVE SERVICES'),
  '211' => xl('SRH - ABORTION'),
  '212' => xl('SRH - HIV AND AIDS'),
  '213' => xl('SRH - STI/RTI'),
  '214' => xl('SRH - GYNECOLOGY'),
  '215' => xl('SRH - OBSTETRIC'),
  '216' => xl('SRH - UROLOGY'),
  '217' => xl('SRH - SUBFERTILITY'),
  '218' => xl('SRH- SPECIALISED SRH SERVICES'),
  '219' => xl('SRH - PEDIATRICS'),
  '220' => xl('SRH - OTHER'),
  '31'  => xl('NON-SRH - MEDICAL'),
  '4'   => xl('NON-CLINICAL - ADMINISTRATION'),
);

// Default Rows selection is just the first one in the list.
if (empty($form_by_arr)) {
  $tmp = array_keys($arr_by);
  $form_by_arr = array($tmp[0]);
}
// Default Columns selection, don't understand this, should be array('.total') instead?
// if (empty($form_show)) $form_show = array('1');
if (empty($form_show)) $form_show = array();

// This sets up the array of date periods for which detail is wanted.
// Key is the period start date, value is the column header.
// There is a final entry for totals with an empty string as its key,
// and that is the only entry if no period detail is wanted.
//
$arr_periods = array();
$arr_months = array(xl('Jan'),xl('Feb'),xl('Mar'),xl('Apr'),xl('May'),
  xl('Jun'),xl('Jul'),xl('Aug'),xl('Sep'),xl('Oct'),xl('Nov'),xl('Dec'));
$arr_quarters = array(xl('1st'),xl('2nd'),xl('3rd'),xl('4th'));
$i = 0;
if ($form_periods) {
  $date1 = $from_date;
  while ($date1 <= $to_date) {
    $date1yy = substr($date1, 0, 4);
    $date1mm = substr($date1, 5, 2);
    $date1dd = substr($date1, 8, 2);
    if ($form_periods == '1') { // Months
      $arr_periods[$date1] = $arr_months[$date1mm - 1] . ' ' . $date1yy;
      if (++$date1mm > 12) {
        ++$date1yy;
        $date1mm = '01';
      }
    }
    else if ($form_periods == '2') {        // Quarters
      $date1qq = (int)(($date1mm - 1) / 3); // Quarter number 0-3
      $arr_periods[$date1] = $arr_quarters[$date1qq] . ' ' . $date1yy;
      $date1mm = ($date1qq + 1) * 3 + 1;
      if ($date1mm > 12) {
        ++$date1yy;
        $date1mm = '01';
      }
    }
    else { // Years
      $arr_periods[$date1] = $date1yy;
      ++$date1yy;
      $date1mm = '01';
    }
    $date1dd = '01';
    $date1 = sprintf('%04d-%02d-%02d', $date1yy, $date1mm, $date1dd);
    if (++$i > 100) {
      $alertmsg = xl('More than 100 periods from') . " $from_date " . xl('to') .
        " $to_date, " . xl('stopping at') . " $date1";
      break;
    }
  }
}
$arr_periods[''] = xl('Total');

// Array of clinics. Key is clinic (facility) ID, value is clinic name.
//
$arr_clinics = array();

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

$cellcount = 0;

function genStartRow($att) {
  global $cellcount, $form_output;
  if ($form_output != 3) echo " <tr $att>\n";
  $cellcount = 0;
}

function genEndRow() {
  global $form_output;
  if ($form_output == 3) {
    echo "\n";
  }
  else {
    echo " </tr>\n";
  }
}

function getListTitle($list, $option) {
  $row = sqlQuery("SELECT title FROM list_options WHERE " .
    "list_id = '$list' AND activity = 1 AND option_id = '$option'");
  if (empty($row['title'])) return $option;
  return xl_list_label($row['title']);
}

// Usually this generates one cell, but allows for two or more.
//
function genAnyCell($data, $align='left', $class='', $colspan=1, $forcetext=false) {
  global $cellcount, $form_output;
  if (!is_array($data)) {
    $data = array(0 => $data);
  }
  foreach ($data as $datum) {
    if ($form_output == 3) {
      if ($cellcount) echo ',';
      // Next line is to force the spreadsheet app to recognize the column as
      // text and not a number.  We don't like IPPF2 codes shown in floating
      // point notation.  :)
      // if ($forcetext) echo '=';
      // The above freaked out LibreOffice when the field includes a comma. So now this:
      if (preg_match('/^[0-9]+$/', $datum)) {
        if ($forcetext) {
          echo '="' . $datum . '"';
        }
        else {
          echo $datum;
        }
      }
      else {
        echo '"' . $datum . '"';
      }
      //
      while ($colspan-- > 1) echo ',""';
    }
    else {
      echo "  <td";
      if ($class) echo " class='$class'";
      if ($colspan > 1) echo " colspan='$colspan'";
      if ($align) echo " align='$align'";
      echo ">$datum</td>\n";
    }
    ++$cellcount;
  }
}

function genHeadCell($data, $align='left', $colspan=1) {
  genAnyCell($data, $align, 'dehead', $colspan, true);
}

// Create an HTML table cell containing a numeric value, and track totals.
//
function genNumCell($num, $cnum, $clikey) {
  global $atotals, $asubtotals, $form_output;
  if (!$clikey) {
    $atotals[$cnum] += $num;
    $asubtotals[$cnum] += $num;
  }
  if (empty($num) && $form_output != 3) $num = '&nbsp;';
  genAnyCell($num, 'right', 'detail');
}

// Get the IPPF2 code related to a given IPPFCM code.
//
function method_to_ippf2_code($ippfcm) {
  global $code_types;
  $ret = '';
  $rrow = sqlQuery("SELECT related_code FROM codes WHERE " .
    "code_type = '" . $code_types['IPPFCM']['id'] . "' AND " .
    "code = '$ippfcm' AND active = 1 " .
    "ORDER BY id LIMIT 1");
  $relcodes = explode(';', $rrow['related_code']);
  foreach ($relcodes as $codestring) {
    if ($codestring === '') continue;
    list($codetype, $code) = explode(':', $codestring);
    if ($codetype !== 'IPPF2') continue;
    $ret = $code;
    break;
  }
  return $ret;
}

// Translate an IPPFCM code to the corresponding descriptive name of its
// contraceptive method, or to an empty string if none applies.
//
function getContraceptiveMethod($code) {
  global $contra_group_name;
  $contra_group_name = '00000 ' . xl('No Group');
  $key = '';

  /*******************************************************************
  // Normalize contraception codes to the table values.
  if (preg_match('/^11/', $code)) {
    $code = substr($code, 0, 6) . '110';
    if ($code == '112152110') $code = '112152010';
  }
  else if (preg_match('/^12/', $code)) {
    if (preg_match('/999$/', $code))
      $code = substr($code, 0, 6) . '213';
    else
      $code = substr($code, 0, 7) . '13';
  }
  $row = sqlQuery("SELECT title, mapping FROM list_options WHERE " .
    "list_id = 'ippfconmeth' AND option_id = '$code'");
  if (!empty($row['title'])) {
    $key = xl_list_label($row['title']);
    if (!empty($row['mapping'])) {
      $contra_group_name = substr($code, 0, 5) . ' ' . $row['mapping'];
    }
  }
  else if (preg_match('/^145212/', $code)) {
    $key = xl('Emergency Contraception');
  }
  else if (preg_match('/^131191.10/', $code)) {
    $key = xl('Awareness-Based');
  }
  *******************************************************************/

  $row = sqlQuery("SELECT c.code_text, lo.title FROM codes AS c " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'contrameth' AND " .
    "lo.option_id = c.code_text_short AND lo.activity = 1 " .
    "WHERE c.code_type = '32' AND c.code = '$code'");
  if (!empty($row['code_text'])) {
    $key = $row['code_text'];
    if (!empty($row['title'])) {
      $contra_group_name = $row['title'];
    }
  }

  return $key;
}

// Helper function to find a contraception-related IPPFCM code from
// the related_code element of the given array.
//
function getRelatedContraceptiveCode($row) {
  if (!empty($row['related_code'])) {
    $relcodes = explode(';', $row['related_code']);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);
      if ($codetype !== 'IPPFCM') continue;
      // Check if the related code concerns contraception.
      $tmp = getContraceptiveMethod($code);
      if (!empty($tmp)) return $code;
    }
  }
  return '';
}

// Determine if the given IPPF2 code is related to contraception.
// This includes modern (11) and natural (12) methods.
function isIPPF2Contraceptive($code) {
  if (preg_match('/^1/', $code)) {
    return true;
  }
  return false;
}

// Helper function to find an abortion-method IPPF2 code from
// the related_code element of the given array.
//
function getRelatedAbortionMethod($row) {
  if (!empty($row['related_code'])) {
    $relcodes = explode(';', $row['related_code']);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);
      if ($codetype !== 'IPPF2') continue;
      // Check if the related code concerns contraception.
      $tmp = getAbortionMethod($code);
      if (!empty($tmp)) return $code;
    }
  }
  return '';
}

// Translate an IPPF2 code to the corresponding descriptive name of its
// abortion method, or to an empty string if none applies.
//
function getAbortionMethod($code) {
  $key = '';

  if (preg_match('/^2113230302301/', $code)) {
    $key = xl('D&C');
  }
  else if (preg_match('/^2113230302302/', $code)) {
    $key = xl('D&E');
  }
  else if (preg_match('/^2113230302304/', $code)) {
    $key = xl('MVA');
  }
  else if (preg_match('/^2113230302305/', $code)) {
    $key = xl('Other Surgical');
  }
  else if (preg_match('/^2113230302800/', $code)) {
    $key = xl('Other Surgical');
  }
  else if (preg_match('/^211313030110[123]/', $code)) {
    $key = xl('Medical');
  }
  else if (preg_match('/^2113130301800/', $code)) {
    $key = xl('Medical');
  }

  return $key;
}

// Generate a SQL condition that tests if the specified column includes an
// IPPF2 code for an abortion procedure.
//
function genAbortionSQL($col) {
  return
    "$col LIKE '%IPPF2:2113230302301%' OR " .
    "$col LIKE '%IPPF2:2113230302302%' OR " .
    "$col LIKE '%IPPF2:2113230302304%' OR " .
    "$col LIKE '%IPPF2:2113230302305%' OR " .
    "$col LIKE '%IPPF2:2113230302800%' OR " .
    "$col LIKE '%IPPF2:2113130301101%' OR " .
    "$col LIKE '%IPPF2:2113130301102%' OR " .
    "$col LIKE '%IPPF2:2113130301103%' OR " .
    "$col LIKE '%IPPF2:2113130301800%'";
}

// Determine if a recent gcac service was performed.
//
function hadRecentAbService($pid, $encdate, $includeIncomplete=false) {
  $query = "SELECT COUNT(*) AS count " .
    "FROM form_encounter AS fe, billing AS b, codes AS c WHERE " .
    "fe.pid = '$pid' AND " .
    "fe.date <= '$encdate' AND " .
    "DATE_ADD(fe.date, INTERVAL 14 DAY) > '$encdate' AND " .
    "b.pid = fe.pid AND " .
    "b.encounter = fe.encounter AND " .
    "b.activity = 1 AND " .
    "b.code_type = 'MA' AND " .
    "c.code_type = '12' AND " .
    "c.code = b.code AND c.modifier = b.modifier AND " .
    "( " . genAbortionSQL('c.related_code');
  if ($includeIncomplete) {
    // In this case we want to include treatment for incomplete.
    $query .= " OR c.related_code LIKE '%IPPF2:211403030%'";
  }
  $query .= " )";
  $tmp = sqlQuery($query);
  return !empty($tmp['count']);
}

// Get the "client status" as descriptive text.
//
function getGcacClientStatus($row) {
  $pid = $row['pid'];
  $encdate = $row['encdate'];

  if (hadRecentAbService($pid, $encdate))
    return xl('MA Client Accepting Abortion');

  // Check for a GCAC visit form.
  // This will the most recent GCAC visit form for visits within
  // the past 2 weeks, although there really should be such a form
  // attached to the visit associated with $row.
  $query = "SELECT lo.title " .
    "FROM forms AS f, form_encounter AS fe, lbf_data AS d, list_options AS lo " .
    "WHERE f.pid = '$pid' AND " .
    "f.formdir = 'LBFgcac' AND " .
    "f.deleted = 0 AND " .
    "fe.pid = f.pid AND fe.encounter = f.encounter AND " .
    "fe.date <= '$encdate' AND " .
    "DATE_ADD(fe.date, INTERVAL 14 DAY) > '$encdate' AND " .
    "d.form_id = f.form_id AND " .
    "d.field_id = 'client_status' AND " .
    "lo.list_id = 'clientstatus' AND " .
    "lo.option_id = d.field_value AND lo.activity = 1 " .
    "ORDER BY d.form_id DESC LIMIT 1";
  $irow = sqlQuery($query);
  if (!empty($irow['title'])) return xl_list_label($irow['title']);

  // Check for a referred-out abortion.
  $query = "SELECT COUNT(*) AS count " .
    "FROM forms AS f " .
    "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'refer_related_code' " .
    "JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'refer_external' " .
    "JOIN lbf_data AS d3 ON d3.form_id = f.form_id AND d3.field_id = 'refer_date' " .
    "LEFT JOIN codes AS c ON d1.field_value LIKE 'REF:%' AND " .
    "c.code_type = '16' AND " .
    "c.code = SUBSTRING(d1.field_value, 5) " .
    "WHERE " .
    "f.formdir = 'LBFref' AND f.deleted = 0 AND " .
    "d2.field_value < '4' AND " .
    "d3.field_value IS NOT NULL AND " .
    "d3.field_value <= '$encdate' AND " .
    "DATE_ADD(d3.field_value, INTERVAL 14 DAY) > '$encdate' AND " .
    "( " . genAbortionSQL('d1.field_value') . " OR " .
    "( c.related_code IS NOT NULL AND ( " .
    genAbortionSQL('c.related_code') . " )))";

  $tmp = sqlQuery($query);
  if (!empty($tmp['count'])) return xl('Outbound Referral');

  return xl('Indeterminate');
}

$age2G_column_count=2*3+2; // Three columns for each age group (M,F,T) + 2 columns for M/F total
$age9G_column_count=9*3+2; // Three columns for each age group (M,F,T) + 2 columns for M/F total
$age13G_column_count=13*3+2; // Three columns for each age group (M,F,T) + 2 columns for M/F total

// For a given clinic (or '' for clinic totals) this sets up the empty
// accumulators for each needed period and for the total of all periods.
// Or if that is already done for the clinic, no action is taken.
// Also the array to relate clinic IDs to names is built.
//
function needClinicArray($key, $clinicid) {
  global $areport, $arr_clinics, $arr_periods, $arr_show,$age2G_column_count,$age9G_column_count,$age13G_column_count;
  if (empty($arr_clinics[$clinicid])) {
    $row = sqlQuery("SELECT name FROM facility WHERE id = '$clinicid'");
    $name = empty($row['name']) ? (xl('Unnamed Clinic') . " #$clinicid") : $row['name'];
    $arr_clinics[$clinicid] = $name;
  }
  if (!empty($areport[$key]['.dtl'][$clinicid])) return;
  $areport[$key]['.dtl'][$clinicid] = array();
  foreach ($arr_periods as $pdate => $dummy) {
    $areport[$key]['.dtl'][$clinicid][$pdate] = array();
    $areport[$key]['.dtl'][$clinicid][$pdate]['.wom'] = 0;       // number of services for women
    $areport[$key]['.dtl'][$clinicid][$pdate]['.men'] = 0;       // number of services for men
    $areport[$key]['.dtl'][$clinicid][$pdate]['.age2'] = array(0,0);               // age array
    $areport[$key]['.dtl'][$clinicid][$pdate]['.age9'] = array(0,0,0,0,0,0,0,0,0); // age array
    $areport[$key]['.dtl'][$clinicid][$pdate]['.age13'] = array(0,0,0,0,0,0,0,0,0,0,0,0,0); // age array
    $areport[$key]['.dtl'][$clinicid][$pdate]['.age2'] = array(0,0);               // age array
    $areport[$key]['.dtl'][$clinicid][$pdate]['.age2G'] = array_fill(0,$age2G_column_count,0);               // age and gender array
    $areport[$key]['.dtl'][$clinicid][$pdate]['.age9G'] = array_fill(0,$age9G_column_count,0);               // age and gender array
    $areport[$key]['.dtl'][$clinicid][$pdate]['.age13G'] = array_fill(0,$age13G_column_count,0);             // age and gender array
    foreach ($arr_show as $askey => $dummy) {
      if (substr($askey, 0, 1) == '.') continue;
      $areport[$key]['.dtl'][$clinicid][$pdate][$askey] = array();
    }
  }
}

// For a given clinic (or '' for clinic totals) and period (or '' for period totals)
// increment the corresponding counters in the $areport array.  Make new clinic
// sub-arrays and $arr_titles entries as needed.
//
function accumClinicPeriod($key, $row, $quantity, $clikey, $perkey) {
  global $areport, $arr_titles, $arr_show;

  needClinicArray($key, $clikey);

  $gender_flag=0;
  // Increment the correct sex category.
  if (strtoupper(substr($row['sex'], 0, 1)) == 'M')
    {
        $areport[$key]['.dtl'][$clikey][$perkey]['.men'] += $quantity;
        $gender_flag=0;
    }
  else
  {
    $areport[$key]['.dtl'][$clikey][$perkey]['.wom'] += $quantity;
    $gender_flag=1;
  }
  // Increment the correct age categories.
  
  $age = getAge(fixDate($row['DOB']), $row['encdate']);
  
  $i = min(intval(($age - 5) / 5), 8);
  if ($age < 10) $i = 0;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age9'][$i] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age9G'][(10*$gender_flag)+$i] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age9G'][(10*$gender_flag)+9] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age9G'][20+$i] += $quantity;

  $i = min(intval(($age - 5) / 5), 12);
  if ($age < 10) $i = 0;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age13'][$i] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age13G'][(14*$gender_flag)+$i] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age13G'][(14*$gender_flag)+13] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age13G'][28+$i] += $quantity;

  $i = $age < 25 ? 0 : 1;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age2'][$i] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age2G'][(3*$gender_flag)+$i] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age2G'][(3*$gender_flag)+2] += $quantity;
  $areport[$key]['.dtl'][$clikey][$perkey]['.age2G'][6+$i] += $quantity;

  foreach ($arr_show as $askey => $dummy) {
    if (substr($askey, 0, 1) == '.') continue;
    $status = empty($row[$askey]) ? 'Unspecified' : $row[$askey];
    $areport[$key]['.dtl'][$clikey][$perkey][$askey][$status] += $quantity;
    $arr_titles[$askey][$status] = 1;
  }
}

// Helper function called after the reporting key is determined for a row.
//
function loadColumnData($key, $row, $quantity=1) {
  global $areport, $arr_titles, $form_content, $from_date, $to_date, $arr_show;
  global $form_clinics, $form_periods, $arr_periods;
  global $form_by, $form_sortby, $arr_keydesc, $report_type;

  // If we are counting new acceptors, then this must be a report of contraceptive
  // (methods or services or products), and a contraceptive start date is provided.
  if ($form_content == '6') {
    if (empty($row['contrastart'])) return;
  }

  // If we are counting new clients, then require a registration date
  // within the reporting period.
  if ($form_content == '4') {
    if (!$row['regdate'] || $row['regdate'] < $from_date ||
      $row['regdate'] > $to_date) return;
  }

  // If we are counting returning clients, then disallow a registration date
  // within the reporting period.
  if ($form_content == '7') {
    if ($row['regdate'] && $row['regdate'] >= $from_date &&
      $row['regdate'] <= $to_date) return;
  }

  // If first instance of this key, initialize its arrays.
  if (empty($areport[$key])) {
    $areport[$key] = array();
    $areport[$key]['.prp'] = 0;       // previous pid
    $areport[$key]['.dtl'] = array();
    // If there is a description column, save the description before sorting.
    if (uses_description($form_by)) {
      $arr_keydesc[$key] = '';
      // $sqltype = in_array($form_by, array('102', '106')) ? 'MA' : 'IPPF2';
      $sqltype = $report_type == 'm' ? 'MA' : 'IPPF2';
      $sqlcode = $key;
      // If key has a group name, get just the code part.
      if (preg_match('/^{(.*)}(.*)/', $sqlcode, $tmp)) {
        $sqlcode = $tmp[2];
      }
      // If key is of the form codetype:code, extract accordingly.
      if (preg_match('/^([A-Za-z0-9]+):(.+)/', $sqlcode, $tmp)) {
        $sqltype = $tmp[1];
        $sqlcode = $tmp[2];
      }
      $crow = sqlQuery("SELECT c.code_text FROM codes AS c, code_types AS ct WHERE " .
        "ct.ct_key = ? AND c.code_type = ct.ct_id AND c.code = ? " .
        "ORDER BY c.id LIMIT 1",
        array($sqltype, $sqlcode));
      if (!empty($crow['code_text'])) $arr_keydesc[$key] = $crow['code_text'];
    }
  }

  // If we are counting unique clients, new or returning clients, then
  // require a unique patient.
  if (in_array($form_content, array(2, 4, 6, 7))) {
    if ($row['pid'] == $areport[$key]['.prp']) return;
  }

  // Flag this patient as having been encountered for this report row.
  $areport[$key]['.prp'] = $row['pid'];

  // Increment the appropriate accumulators in the report array.
  $encdate = $row['encdate'];
  if (empty($encdate)) $encdate = $row['sale_date'];
  accumClinicPeriod($key, $row, $quantity, '', '');
  $perkey = '';
  if ($form_periods) {
    foreach ($arr_periods as $pdate => $dummy) {
      if (!$perkey || ($pdate && $pdate <= $row['encdate'])) {
        $perkey = $pdate;
      }
    }
    if ($perkey) accumClinicPeriod($key, $row, $quantity, '', $perkey);
  }
  if ($form_clinics) {
    // Clinic ID of -1 indicates no facility is associated.
    // That should not happen but undoubtedly will.
    $clikey = empty($row['facility_id']) ? '-1' : $row['facility_id'];
    accumClinicPeriod($key, $row, $quantity, $clikey, '');
    if ($form_periods) {
      accumClinicPeriod($key, $row, $quantity, $clikey, $perkey);
    }
  }
}

// This determines a key for the product with highest CYP in a visit.
//
function product_contraception_scan($pid, $encounter) {
  global $contra_group_name;
  $current_cyp = -1;
  $current_drug_id = 0;
  $current_name = '';
  $sql = "SELECT d.drug_id, d.name, d.related_code " .
    "FROM drug_sales AS ds, drugs AS d WHERE " .
    "ds.pid = '$pid' AND ds.encounter = '$encounter' AND " .
    "ds.quantity > 0 AND d.related_code != '' AND " .
    "d.drug_id = ds.drug_id";
  $dsres = sqlStatement($sql);
  while ($dsrow = sqlFetchArray($dsres)) {
    $relcodes = explode(';', $dsrow['related_code']);
    foreach ($relcodes as $relstring) {
      if ($relstring === '') continue;
      list($reltype, $relcode) = explode(':', $relstring);
      if ($reltype !== 'IPPFCM') continue;
      $tmprow = sqlQuery("SELECT cyp_factor FROM codes WHERE " .
        "code_type = '32' AND code = '$relcode' LIMIT 1");
      $cyp = 0 + $tmprow['cyp_factor'];
      if ($cyp > $current_cyp) {
        $current_cyp    = $cyp;
        $current_ippfcm = $relcode;
        $current_name   = $dsrow['name'];
      }
    }
  }
  $key = '{(' . xl('None') . ')}(' . xl('No Product') . ')';
  if ($current_cyp > 0) {
    getContraceptiveMethod($current_ippfcm);
    $key = '{' . $contra_group_name . '}' . $current_name;
  }
  return $key;
}

// This gets the IPPFCM contraception code of the service with highest CYP
// in a visit, similarly to the method that the Fee Sheet uses to assign a value
// for newmethod.  Also get the associated IPPF2 code to support reporting of
// specific IPPF2 service for new user content type.
//
function service_contraception_scan($pid, $encounter) {
  global $contraception_ippf2_code;

  $contraception_code = '';
  $contraception_ippf2_code = '';
  $contraception_cyp  = -1;
  $query = "SELECT " .
    "b.code_type, b.code, c.related_code " .
    "FROM billing AS b " .
    "JOIN codes AS c ON b.code_type = 'MA' AND c.code_type = '12' AND " .
    "c.code = b.code AND c.modifier = b.modifier AND c.related_code != '' " .
    "WHERE b.pid = '$pid' AND b.encounter = '$encounter' AND b.activity = 1 " .
    "AND b.code_type = 'MA'";
  $bres = sqlStatement($query);
  while ($brow = sqlFetchArray($bres)) {
    $relcodes = explode(';', $brow['related_code']);
    // Get the associated IPPF2 code for this service.
    $tmp_ippf2_code = '';
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $relcode) = explode(':', $codestring);
      if ($codetype === 'IPPF2') {
        $tmp_ippf2_code = $relcode;
      }
    }
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $relcode) = explode(':', $codestring);
      if ($codetype !== 'IPPFCM') continue;
      $tmprow = sqlQuery("SELECT cyp_factor FROM codes WHERE " .
        "code_type = '32' AND code = '$relcode' LIMIT 1");
      $cyp = 0 + $tmprow['cyp_factor'];
      if ($cyp > $contraception_cyp) {
        $contraception_cyp  = $cyp;
        $contraception_code = $relcode;
        $contraception_ippf2_code = $tmp_ippf2_code;
      }
    }
  } // end while
  return $contraception_code;
}

// Get the adjustment type, if any, associated with a service or product sale.
// Invoice-level adjustments are considered to match all items in the invoice.
//
function get_adjustment_type($patient_id, $encounter_id, $code_type, $code) {
  global $form_adjreason;

  $adjreason = '';
  $row = sqlQuery("SELECT memo FROM ar_activity WHERE " .
    "pid = '$patient_id' AND encounter = '$encounter_id' AND deleted IS NULL AND " .
    "(code_type = '' OR (code_type = '$code_type' AND code = '$code')) AND " .
    "(adj_amount != 0.00 OR pay_amount = 0.00) AND memo != '' " .
    "ORDER BY code DESC, adj_amount DESC LIMIT 1");
  if (isset($row['memo'])) $adjreason = $row['memo'];
  return $adjreason;
}

// This is called for each IPPF2 service code that is selected.
//
function process_ippf_code($row, $code, $quantity=1) {
  global $form_by, $form_content, $contra_group_name, $arr_ippf2_cats, $form_svccat, $form_related_code;

  // We did not do any IPPF2 code filtering in SQL, so do it here.
  if ($report_type != 'm') {
    // If one or more service codes were specified.
    if ($form_related_code) {
      $match = false;
      $arel = explode(';', $form_related_code);
      foreach ($arel as $tmp) {
        list($reltype, $relcode) = explode(':', $tmp);
        if (empty($relcode) || empty($reltype)) continue;
        if ($reltype == 'IPPF2' && $relcode == $code) $match = true;
      }
      if (!$match) return;
    }
    // It is not valid to specify a service category in addition to service codes, but if you
    // do the service codes will take precedence.  Thus the "else" here.
    else if (!empty($form_svccat)) {
      // For IPPF/GCAC Stats, service category should match the IPPF2 code's beginning.
      if (strpos($code, "$form_svccat") !== 0) {
        return;
      }
    }
  }

  $key = 'Unspecified';

  // SRH including Family Planning
  // This works for both IPPF2 and IPPF codes.
  //
  if ($form_by === '1') {
    if (preg_match('/^1/', $code)) {
      $key = xl('SRH - Family Planning');
    }
    else if (preg_match('/^2/', $code)) {
      $key = xl('SRH Non Family Planning');
    }
    else {
      if ($form_content != 5) return;
    }
  }

  // General Service Category
  // This works for both IPPF2 and IPPF codes.
  //
  else if ($form_by === '3') {
    if (preg_match('/^1/', $code)) {
      $key = xl('SRH - Family Planning');
    }
    else if (preg_match('/^2/', $code)) {
      $key = xl('SRH Non Family Planning');
    }
    else if (preg_match('/^3/', $code)) {
      $key = xl('Non-SRH Medical');
    }
    else if (preg_match('/^4/', $code)) {
      $key = xl('Non-SRH Non-Medical');
    }
    else {
      $key = xl('Invalid Service Codes');
    }
  }

  // Abortion-Related Category.
  // This follows the framework categories so the titles are somewhat different
  // from the old framework.
  //
  else if ($form_by === '13') {
    if (preg_match('/^2111/', $code)) {
      $key = xl('Abortion Counseling');
    }
    else if (preg_match('/^2112/', $code)) {
      $key = xl('Abortion Consultation');
    }
    else if (preg_match('/^21131/', $code)) {
      $key = xl('Medical Abortion Management');
    }
    else if (preg_match('/^21132/', $code)) {
      $key = xl('Surgical Abortion Management');
    }
    else if (preg_match('/^2114/', $code)) {
      $key = xl('Incomplete Abortion Management');
    }
    else {
      if ($form_content != 5) return;
    }
  }

  // All Services. One row for each IPPF2 code.
  //
  else if ($form_by === '4') {
    $key = $code;
  }

  // Service by Category. One row for each IPPF2 code with IPPF2 category in the key.
  //
  else if ($form_by === '107') {
    $tmp = xl('Uncategorized');
    foreach ($arr_ippf2_cats as $tmpkey => $tmpval) {
      // Quotes are necessary here so $tmpkey is treated as a string.
      if (strpos($code, "$tmpkey") === 0) {
        $tmp = $tmpval;
        break;
      }
    }
    $key = '{' . $tmp . '}' . $code;
    // echo "<!-- $key -->\n"; // debugging
  }

  // Specific Contraceptive Services. One row for each IPPF2 code.
  //
  else if ($form_by === '104') {
    if ($form_content != 5) {
      // Skip codes not for contraceptive services.
      if (!isIPPF2Contraceptive($code)) return;
    }
    $key = $code;
  }

  // Abortion Method.
  //
  else if ($form_by === '5') {
    $key = getAbortionMethod($code);
    if (empty($key)) {
      // If not an abortion service then skip unless counting Contraceptive Items Provided.
      if ($form_content != 5) return;
      $key = 'Unspecified';
    }
  }

  /*******************************************************************
  // Contraceptive Method.
  //
  else if ($form_by === '6') {
    $key = getContraceptiveMethod($code);
    if (empty($key)) {
      // If not a contraceptive service then skip unless counting Contraceptive Items Provided.
      if ($form_content != 5) return;
      $key = 'Unspecified';
    }
    $key = '{' . $contra_group_name . '}' . $key;
  }

  // Contraceptive method for new contraceptive adoption following abortion.
  // Get it from the IPPF code if there is a suitable recent abortion service
  // or GCAC form.
  //
  else if ($form_by === '7') {
    $key = getContraceptiveMethod($code);
    if (empty($key)) return;
    $key = '{' . $contra_group_name . '}' . $key;
    $patient_id = $row['pid'];
    $encdate = $row['encdate'];
    // Skip this if no recent gcac service nor gcac form with acceptance.
    // Include incomplete abortion treatment services per AM's discussion
    // with Dr. Celal on 2011-04-19.
    if (!hadRecentAbService($patient_id, $encdate, true)) {
      $query = "SELECT COUNT(*) AS count " .
        "FROM forms AS f, form_encounter AS fe, lbf_data AS d " .
        "WHERE f.pid = '$patient_id' AND " .
        "f.formdir = 'LBFgcac' AND " .
        "f.deleted = 0 AND " .
        "fe.pid = f.pid AND fe.encounter = f.encounter AND " .
        "fe.date <= '$encdate' AND " .
        "DATE_ADD(fe.date, INTERVAL 14 DAY) > '$encdate' AND " .
        "d.form_id = f.form_id AND " .
        "d.field_id = 'client_status' AND " .
        "( d.field_value = 'maaa' OR d.field_value = 'refout' )";
      $irow = sqlQuery($query);
      if (empty($irow['count'])) return;
    }
  }
  *******************************************************************/

  // Post-Abortion Care and Followup by Source.
  // Requirements just call for counting sessions, but this way the columns
  // can be anything - age category, religion, whatever.
  //
  else if ($form_by === '8') {
    if (in_array($code, array(
      '2111010122000', // Abortion - Counselling - Post-abortion
      '2112020202101', // Abortion - Consultation - Follow up consultation - Harm reduction model
      '2113130301104', // Abortion - Management - Medical - follow up
      '2113130301110', // Abortion - Management - Medical - Treatment of complications
      '2113230302307', // Abortion - Management - Surgical - follow up
      '2113230302310', // Abortion - Management - Surgical - Treatment of complications
    )) || preg_match('/^211403/', $code)) { // Incomplete abortion codes
      $key = getGcacClientStatus($row);
    } else {
      return;
    }
  }

  // Pre-Abortion Counseling.  Three possible situations:
  //   Provided abortion in the MA clinics
  //   Referred to other service providers (govt,private clinics)
  //   Decided not to have the abortion
  //
  else if ($form_by === '12') {
    /*****************************************************************
    if (in_array($code, array(
      '2111010121000', // Abortion - Counselling - Pre-abortion / Options Counselling
      '2112020200000', // Abortion - Consultation
      '2112020201101', // Abortion - Consultation - Initial consultation - Harm reduction model
    ))) {
    *****************************************************************/
    if (preg_match('/^2111010121/', $code)) { // pre-abortion counseling
      $key = getGcacClientStatus($row);
    } else {
      return;
    }
  }

  else {
    return; // no match, so do nothing
  }

  // OK we now have the reporting key for this issue.
  loadColumnData($key, $row, $quantity);

} // end function process_ippf_code()

// This is called for each IPPFCM service code. These are the codes that tell
// us which contraceptive method is involved.
//
function process_ippfcm_code($row, $code, $quantity=1) {
  global $form_by, $form_content, $contra_group_name, $report_type;

  $key = 'Unspecified';

  // Contraceptive Method.
  //
  if ($form_by === '6') {
    $key = getContraceptiveMethod($code);
    if (empty($key)) {
      // If not a contraceptive service then skip unless counting Contraceptive Items Provided.
      if ($form_content != 5) return;
      $key = 'Unspecified';
    }
    if ($report_type == 'i') {
      // Per CV we want to sort methods by IPPFCM code for IPPF Stats.
      $key = "$code $key" . '{' . $contra_group_name . '}';
    }
    else {
      $key = '{' . $contra_group_name . '}' . $key;
    }
  }

  // Contraceptive method for new contraceptive adoption following abortion.
  // Get it from the IPPFCM code if there is a suitable recent abortion service
  // or GCAC form.
  //
  else if ($form_by === '7') {
    $key = getContraceptiveMethod($code);
    if (empty($key)) return;
    $key = '{' . $contra_group_name . '}' . $key;
    $patient_id = $row['pid'];
    $encdate = $row['encdate'];
    // Skip this if no recent gcac service nor gcac form with acceptance.
    // Include incomplete abortion treatment services per AM's discussion
    // with Dr. Celal on 2011-04-19.
    if (!hadRecentAbService($patient_id, $encdate, true)) {
      $query = "SELECT COUNT(*) AS count " .
        "FROM forms AS f, form_encounter AS fe, lbf_data AS d " .
        "WHERE f.pid = '$patient_id' AND " .
        "f.formdir = 'LBFgcac' AND " .
        "f.deleted = 0 AND " .
        "fe.pid = f.pid AND fe.encounter = f.encounter AND " .
        "fe.date <= '$encdate' AND " .
        "DATE_ADD(fe.date, INTERVAL 14 DAY) > '$encdate' AND " .
        "d.form_id = f.form_id AND " .
        "d.field_id = 'client_status' AND " .
        "( d.field_value = 'maaa' OR d.field_value = 'refout' )";
      $irow = sqlQuery($query);
      if (empty($irow['count'])) return;
    }
  }

  else {
    return; // no match, so do nothing
  }

  // OK we now have the reporting key for this issue.
  loadColumnData($key, $row, $quantity);

} // end function process_ippfcm_code()

// This is called for each MA service code that is selected.
//
function process_ma_code($row, $code='', $quantity=1) {
  global $form_by, $arr_content, $form_content, $form_adjreason;

  if ($code === '') $code = $row['code'];
  $key = 'Unspecified';

  // Filtering by adjustment type.
  //
  if ($form_adjreason) {
    if (!empty($row['drug_id'])) {
      $adjreason = get_adjustment_type($row['pid'], $row['encounter'], 'PROD', $row['drug_id']);
    }
    else {
      $adjreason = get_adjustment_type($row['pid'], $row['encounter'], 'MA', $code);
    }
    if ($adjreason != $form_adjreason) return;
  }

  // One row for each service category.
  //
  if ($form_by === '101') {
    if (!empty($row['lo_title'])) $key = xl($row['lo_title']);
  }

  // All Services. One row for each MA code.
  //
  else if ($form_by === '102') {
    $key = $code;
  }

  // Service by Category. One row for each MA code with service category in the key.
  //
  else if ($form_by === '106') {
    $key = '{' . (empty($row['lo_title']) ? xl('Uncategorized') : $row['lo_title']) . '}' . $code;
  }

  // One row for each referral source.
  //
  else if ($form_by === '103') {
    $key = getListTitle('refsource', $row['referral_source']);
  }

  // Just one row.
  //
  else if ($form_by === '2') {
    if ($form_content == '2') return; // total unique clients handled in process_visit().
    $key = $arr_content[$form_content];
  }

  // Patient Name.
  //
  else if ($form_by === '17') {
    $key = $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'];
  }

  else {
    return;
  }

  loadColumnData($key, $row, $quantity);
}

// This is called for each ADM service code that is selected.
//
function process_adm_code($row, $code='', $quantity=1) {
  global $form_by, $arr_content, $form_content, $form_adjreason;

  if ($code === '') $code = $row['code'];
  $key = 'ADM:Unspecified';

  // Filtering by adjustment type.
  //
  if ($form_adjreason) {
    $adjreason = get_adjustment_type($row['pid'], $row['encounter'], 'ADM', $code);
    if ($adjreason != $form_adjreason) return;
  }

  // One row for each service category.
  //
  if ($form_by === '101') {
    if (!empty($row['lo_title'])) $key = xl($row['lo_title']);
  }

  // All Services. One row for each ADM code.
  //
  else if ($form_by === '102') {
    $key = "ADM:$code";
  }

  else {
    return;
  }

  loadColumnData($key, $row, $quantity);
}

function LBFgcac_query($pid, $encounter, $name) {
  $query = "SELECT d.form_id, d.field_value " .
    "FROM forms AS f, form_encounter AS fe, lbf_data AS d " .
    "WHERE f.pid = '$pid' AND " .
    "f.encounter = '$encounter' AND " .
    "f.formdir = 'LBFgcac' AND " .
    "f.deleted = 0 AND " .
    "fe.pid = f.pid AND fe.encounter = f.encounter AND " .
    "d.form_id = f.form_id AND " .
    "d.field_id = '$name'";
  return sqlStatement($query);
}

function LBFgcac_title($form_id, $field_id, $list_id) {
  $query = "SELECT lo.title " .
    "FROM lbf_data AS d, list_options AS lo WHERE " .
    "d.form_id = '$form_id' AND " .
    "d.field_id = '$field_id' AND " .
    "lo.list_id = '$list_id' AND " .
    "lo.option_id = d.field_value AND lo.activity = 1 " .
    "LIMIT 1";
  $row = sqlQuery($query);
  return empty($row['title']) ? '' : xl_list_label($row['title']);
}

// This is called for each encounter that is selected.
//
function process_visit($row) {
  global $form_by, $form_content, $arr_content;

  // New contraceptive method following abortion.  These should only be
  // present for inbound referrals.
  //
  if ($form_by === '7') {
  }

  // Just one row for unique clients.
  //
  else if ($form_by == '2' && $form_content == '2') {
    $key = $arr_content[$form_content];
    loadColumnData($key, $row);
  }

  // Complications of abortion by abortion method and complication type.
  // These may be noted either during recovery or during a followup visit.
  // Note: If there are multiple complications, they will all be reported.
  //
  else if ($form_by === '11') {
    $dres = LBFgcac_query($row['pid'], $row['encounter'], 'complications');
    while ($drow = sqlFetchArray($dres)) {
      $a = explode('|', $drow['field_value']);
      foreach ($a as $complid) {
        if (empty($complid)) continue;
        $crow = sqlQuery("SELECT title FROM list_options WHERE " .
          "list_id = 'complication' AND option_id = '$complid' AND activity = 1");
        $abtype = LBFgcac_title($drow['form_id'], 'in_ab_proc', 'in_ab_proc');
        if (empty($abtype)) $abtype = xl('Indeterminate');
        $key = "$abtype / " . xl_list_label($crow['title']);
        loadColumnData($key, $row);
      }
    }
  }

  // Patient Name.
  //
  else if ($form_by === '17') {
    $key = $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'];
    // If content is services then quantity = 0 because a visit is not a service.
    loadColumnData($key, $row, $form_content == 1 ? 0 : 1);
  }
}

// This is called for each selected referral.
//
function process_referral($row) {
  global $form_by, $code_types, $report_type;

  // For followups we care about the actual service provided, otherwise
  // the requested service.
  $related_code = $form_by === '20' ?
    $row['reply_related_code'] : $row['refer_related_code'];

  if (!empty($related_code)) {
    $relcodes = explode(';', $related_code);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);

      if ($report_type == 'm' || $codetype == 'IPPF2') {
        if ($form_by === '1') {
          if (preg_match('/^[12]/', $code)) {
            loadColumnData(xl('SRH Referrals'), $row);
          }
        }
        else {
          // $form_by is 9/14 (internal) or 10/15/20 (external) referrals
          loadColumnData("$codetype:$code", $row);
        }
      }

      if ($report_type != 'm' && ($codetype == 'MA' || $codetype == 'REF')) {
        // In the case of a MA or REF code, look up the associated IPPF2 code.
        $rrow = sqlQuery("SELECT related_code FROM codes WHERE " .
          "code_type = '" . $code_types[$codetype]['id'] . "' AND " .
          "code = '$code' AND active = 1 " .
          "ORDER BY id LIMIT 1");
        $relcodes2 = explode(';', $rrow['related_code']);
        foreach ($relcodes2 as $codestring2) {
          if ($codestring2 === '') continue;
          list($codetype2, $code2) = explode(':', $codestring2);
          if ($codetype2 !== 'IPPF2') continue;
          if ($form_by === '1') {
            if (preg_match('/^[12]/', $code2)) {
              loadColumnData(xl('SRH Referrals'), $row);
            }
          }
          else {
            loadColumnData("$codetype2:$code2", $row);
          }
        } // end foreach
      }
    } // end foreach
  }
}

// Returns true if this report type has separate columns for code and description.
//
function uses_description($form_by) {
  return in_array($form_by, array('4', '102', '106', '107', '9', '10', '14', '15', '20', '104'));
}

function uses_group_names($form_by) {
  global $form_output;
  if ($form_output != 3) return false;
  return in_array($form_by, array('6', '7', '105', '106', '107'));
}

function writeSubtotals($last_group, &$asubtotals, $form_by) {
  if ($last_group) {
    if (preg_match('/^(\d\d\d\d\d) (.*)/', $last_group, $tmp)) {
      $last_group = $tmp[2] . ' - ' . $tmp[1];
    }
    genStartRow("bgcolor='#dddddd'");

    /******************************************************************
    if (uses_description($form_by)) {
      genHeadCell(array(xl('Subtotals for') . " $last_group", ''));
    } else {
      genHeadCell(xl('Subtotals for') . " $last_group", 'right', 2);
    }
    ******************************************************************/
    genHeadCell(xl('Subtotals for') . " $last_group", 'right', 2);

    for ($cnum = 0; $cnum < count($asubtotals); ++$cnum) {
      genHeadCell($asubtotals[$cnum], 'right');
    }
    genEndRow();
  }
}

// This supports uksort() on clinic IDs.
//
function clinic_compare($a, $b) {
  global $arr_clinics;
  if ($a == $b) return  0; // they are the same
  if (!$a     ) return  1; // a is for totals
  if (!$b     ) return -1; // b is for totals
  if ($a == -1) return  1; // a is unassigned clinic
  if ($b == -1) return -1; // b is unassigned clinic
  return $arr_clinics[$a] < $arr_clinics[$b] ? -1 : ($arr_clinics[$a] > $arr_clinics[$b] ? 1 : 0);
}

// This supports uksort() on key descriptions.
//
function keydesc_compare($a, $b) {
  global $arr_keydesc, $form_sortby;
  $x = $a;
  $y = $b;
  if ($form_sortby == '2' && isset($arr_keydesc[$a]) && $arr_keydesc[$a] != $arr_keydesc[$b]) {
    $x = $arr_keydesc[$a];
    $y = $arr_keydesc[$b];
  }
  return $x < $y ? -1 : ($x > $y ? 1 : 0);
}

$arr_show   = array(
  // '.total' => array('title' => xl('Total')),
  '.age2'  => array('title' => xl('Age Category') . ' (2)'),
  '.age9'  => array('title' => xl('Age Category') . ' (9)'),
  '.age13'  => array('title' => xl('Age Category') . ' (13)'),
  '.age2G'  => array('title' => xl('Age Category and Sex (2)')),
  '.age9G'  => array('title' => xl('Age Category and Sex (9)')),
  '.age13G' => array('title' => xl('Age Category and Sex (13)')),
); // info about selectable columns

// This holds 2 levels of column headers. The first level of keys are the
// field names, and each key's value is an array whose keys are the field
// values, and whose values are meaningless positive numbers.
//
$arr_titles = array(); // will contain column headers

// Query layout_options table to generate the $arr_show table.
// Table key is the field ID.
//
$lres = sqlStatement("SELECT field_id, title, data_type, list_id, description " .
  "FROM layout_options WHERE " .
  "form_id = 'DEM' AND uor > 0 AND field_id NOT LIKE 'em%' " .
  "ORDER BY group_id, seq, title");
while ($lrow = sqlFetchArray($lres)) {
  $fid = $lrow['field_id'];
  if ($fid == 'fname' || $fid == 'mname' || $fid == 'lname') continue;
  if (!empty($lrow['title'])) $lrow['title'] = xl_layout_label($lrow['title']);
  $arr_show[$fid] = $lrow;
  $arr_titles[$fid] = array();
}

  // If we are doing the CSV export then generate the needed HTTP headers.
  // Otherwise generate HTML.
  //
  if ($form_output == 3) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download; charset=utf-8");
    header("Content-Disposition: attachment; filename={$report_name_prefix}_Statistics_Report.csv");
    header("Content-Description: File Transfer");
    // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
    // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
    // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
    // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
    echo "\xEF\xBB\xBF";
  }
  else {
?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo $report_title; ?></title>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>

<style type="text/css">

 body       { font-family:sans-serif; font-size:10pt; font-weight:normal }
 .dehead    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }

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
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js?v=<?php echo $v_js_includes; ?>"></script>

<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
 var s = f.form_related_code.value;
 if (code) {
  if (s.length > 0) {
   s += ';';
  }
  s += codetype + ':' + code;
 } else {
  s = '';
 }
 f.form_related_code.value = s;
}

// This invokes the find-code popup.
function sel_related() {
 dlgopen('../patient_file/encounter/find_code_dynamic.php?codetype=<?php
  echo $report_type == 'm' ? 'MA' : 'IPPF2'; ?>', '_blank', 900, 600);
}

// This is for callback by the find-code popup.
// Returns the array of currently selected codes with each element in codetype:code format.
function get_related() {
 return document.forms[0].form_related_code.value.split(';');
}

// This is for callback by the find-code popup.
// Deletes the specified codetype:code from the currently selected list.
function del_related(s) {
 my_del_related(s, document.forms[0].form_related_code, false);
}

<?php if ($form_output != 3 && count($form_by_arr) == 1) { ?>
$(document).ready(function() {
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
});
<?php } ?>

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

<center>

<h2><?php echo $report_title; ?></h2>

<form name='theform' method='post'
 action='ippf_statistics_2.php?t=<?php echo $report_type ?>'>

<table border='0' cellspacing='5' cellpadding='1'>

 <tr>
  <td valign='top' class='dehead' nowrap>
   <?php echo xlt('Content'); ?>:
  </td>
  <td valign='top' class='detail'>
   <select name='form_content' title='<?php xl('What is to be counted?','e'); ?>'>
<?php
  foreach ($arr_content as $key => $value) {
    echo "    <option value='$key'";
    if ($key == $form_content) echo " selected";
    echo ">$value</option>\n";
  }
?>
   </select>
  </td>
  <td valign='top' class='dehead' nowrap>
   <?php echo xlt('Filters'); ?>:
  </td>
  <td colspan='2' rowspan='4' class='detail' style='border-style:solid;border-width:1px;border-color:#cccccc'>
   <table>
    <tr>
     <td valign='top' class='detail' nowrap>
      <?php echo xlt('Sex'); ?>:
     </td>
     <td class='detail' valign='top'>
      <select name='form_sexes' title='<?php echo xlt('To filter by sex'); ?>'>
<?php
  foreach (array(4 => xl('All'), 1 => xl('Women Only'), 2 => xl('Men Only'), 3 => xl('Other Only')) as $key => $value) {
    echo "       <option value='$key'";
    if ($key == $form_sexes) echo " selected";
    echo ">$value</option>\n";
  }
?>
      </select>
     </td>
    </tr>
    <tr>
     <td valign='top' class='detail' nowrap>
      <?php echo xlt('Facility'); ?>:
     </td>
     <td valign='top' class='detail'>
<?php
 // Build a multi-select list of facilities.
 //
 $query = "SELECT id, name FROM facility ORDER BY name";
 $fres = sqlStatement($query);
 echo "      <select name='form_facility[]' size='5' multiple title='" .
  xlt('Hold down Ctrl to select multiple clinics.') . "\n" .
  xlt('If no selection then all are included.') . "'>\n";
 while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  echo "       <option value='$facid'";
  if (in_array($facid, $form_fac_arr)) echo " selected";
  echo ">" . $frow['name'] . "\n";
 }
 echo "      </select>\n";
?>
     </td>
    </tr>

<?php if ($report_type == 'm' && $GLOBALS['gbl_checkout_line_adjustments']) { ?>
    <tr>
     <td valign='top' class='detail' nowrap>
      <?php echo xlt('Adj Type'); ?>:
     </td>
     <td valign='top' class='detail'>
<?php
  echo generate_select_list('form_adjreason', 'adjreason', $form_adjreason);
?>
     </td>
    </tr>
<?php } ?>

<?php if (!empty($GLOBALS['gbl_charge_categories'])) { ?>
    <tr>
     <td valign='top' class='detail' nowrap>
      <?php echo xlt('Customer'); ?>:
     </td>
     <td valign='top' class='detail'>
<?php
  echo generate_select_list('form_chargecat', 'chargecats', $form_chargecat);
?>
     </td>
    </tr>
<?php } ?>

<?php if ($report_type == 'm' || $report_type == 'i') { ?>
    <tr>
     <td valign='top' class='detail' nowrap>
      <?php echo xlt('Svc Cat'); ?>:
     </td>
     <td valign='top' class='detail'>
<?php
  // Generate the service category selector.
  if ($report_type == 'm') {
    // For MA Stats it's the Service Categories list.
    echo generate_select_list('form_svccat', 'superbill', $form_svccat);
  }
  else {
    // For IPPF Stats it's the hard coded categories.
    echo "<select name='form_svccat' id='form_svccat' title=''>\n";
    echo " <option value=''> </option>\n";
    foreach ($arr_ippf2_cats AS $key => $value) {
      echo " <option value='$key'";
      if ($key == $form_svccat) echo " selected";
      echo ">" . text($value) . "</option>\n";
    }
    echo "</select>\n";
  }
?>
     </td>
    </tr>
<?php } ?>

<?php if ($report_type == 'm' || $report_type == 'i') { ?>
    <tr>
     <td valign='top' class='detail' nowrap>
      <?php echo xlt('Services'); ?>:
     </td>
     <td valign='top' class='detail'>
      <input type='text' size='30' name='form_related_code'
       value='<?php echo $form_related_code ?>' onclick="sel_related()"
       title='<?php echo xla('Click to select a code for filtering'); ?>' readonly />
     </td>
    </tr>
<?php } ?>

    <tr>
     <td colspan='2' class='detail' nowrap>
      <?php echo xlt('From'); ?>
      <input type='text' name='form_from_date' id='form_from_date' size='10' value='<?php echo $from_date ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Start date yyyy-mm-dd'>
      <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
       id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
       title='<?php echo xlt('Click here to choose a date'); ?>'>
      <?php echo xlt('To'); ?>
      <input type='text' name='form_to_date' id='form_to_date' size='10' value='<?php echo $to_date ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='End date yyyy-mm-dd'>
      <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
       id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
       title='<?php echo xlt('Click here to choose a date'); ?>'>
     </td>
    </tr>
    <tr>
        <td colspan="2" class='detail' nowrap>
            <?php echo xlt("Age >="); ?>
                <input type='text' name='age_minimum' size='4'
                       value='<?php echo $age_minimum; ?>'
                       title='<?php echo xlt("Do not include clients younger this value in results");?>'/>
            <?php echo xlt("Age <="); ?>
                <input type='text' name='age_maximum' size='4'
                       value='<?php echo $age_maximum; ?>'
                       title='<?php echo xlt("Do not include clients older this value in results");?>'/>
        </td>
    </tr>
   </table>
  </td>
 </tr>

 <tr>
  <td valign='top' class='dehead' nowrap>
   <?php echo xlt('Rows'); ?>:
  </td>
  <td valign='top' class='detail'>
   <select name='form_by[]' size='4' multiple
    title='<?php echo xlt('Hold down Ctrl to select multiple items'); ?>'>
<?php
  foreach ($arr_by as $key => $value) {
    echo "    <option value='$key'";
    if (is_array($form_by_arr) && in_array($key, $form_by_arr)) echo " selected";
    echo ">" . $value . "</option>\n";
  }
?>
   </select>
  </td>
  <td valign='top' class='detail'>
   &nbsp;
  </td>
  <!-- td omitted due to rowspan -->
  <td valign='top' class='detail'>
   &nbsp;
  </td>
 </tr>

 <tr>
  <td valign='top' class='dehead' nowrap>
   <?php xl('Columns','e'); ?>:
  </td>
  <td valign='top' class='detail'>
   <select name='form_show[]' size='4' multiple
    title='<?php echo xlt('Hold down Ctrl to select multiple items'); ?>'>
<?php
  foreach ($arr_show as $key => $value) {
    $title = $value['title'];
    if (empty($title) || $key == 'title') $title = $value['description'];
    echo "    <option value='$key'";
    if (is_array($form_show) && in_array($key, $form_show)) echo " selected";
    echo ">$title</option>\n";
  }
?>
   </select>
  </td>
  <td valign='top' class='detail'>
   &nbsp;
  </td>
  <!-- td omitted due to rowspan -->
  <td valign='top' class='detail'>
   &nbsp;
  </td>
 </tr>

 <tr>
  <td valign='top' class='dehead' nowrap>
   <?php echo xlt('Sort By'); ?>:
  </td>
  <td valign='top' class='dehead' nowrap>
   <select name='form_sortby' title='<?php echo xlt('What to sort on'); ?>'>
<?php
  foreach ($arr_sortby as $key => $value) {
    echo "    <option value='$key'";
    if ($key == $form_sortby) echo " selected";
    echo ">$value</option>\n";
  }
?>
   </select>
  </td>
  <td valign='top' class='detail'>
   &nbsp;
  </td>
  <!-- td omitted due to rowspan -->
  <td valign='top' class='detail'>
   &nbsp;
  </td>
 </tr>

 <tr>
  <td valign='top' class='dehead' nowrap>
   <?php xl('Periods','e'); ?>:
  </td>
  <td valign='top' class='detail'>
   <select name='form_periods'
    title='<?php xl('To generate a column for each period','e'); ?>'>
<?php
  foreach (array(0 => xl('Total - All'), 1 => xl('Months'), 2 => xl('Quarters'), 3 => xl('Years')) as $key => $value) {
    echo "    <option value='$key'";
    if ($key == $form_periods) echo " selected";
    echo ">$value</option>\n";
  }
?>
   </select>
  </td>
  <td valign='top' class='dehead' nowrap>
   <?php xl('Clinics','e'); ?>:
  </td>
  <td valign='top' class='detail'>
   <select name='form_clinics'
    title='<?php xl('Details by clinic?','e'); ?>'>
<?php
  foreach (array(0 => xl('All Clinics - Summary'), 1 => xl('All Clinics - Detail')) as $key => $value) {
    echo "    <option value='$key'";
    if ($key == $form_clinics) echo " selected";
    echo ">$value</option>\n";
  }
?>
   </select>
  </td>
  <td valign='top' class='detail'>
   &nbsp;
  </td>
 </tr>

 <tr>
  <td valign='top' class='dehead' nowrap>
   <?php xl('To','e'); ?>:
  </td>
  <td colspan='3' valign='top' class='detail' nowrap>
<?php
foreach (array(1 => xl('Screen'), 2 => xl('Printer'), 3 => xl('Export File')) as $key => $value) {
  echo "   <input type='radio' name='form_output' value='$key'";
  if ($key == $form_output) echo ' checked';
  echo " />$value &nbsp;";
}
?>
  </td>
  <td align='right' valign='top' class='detail' nowrap>
   <input type='submit' name='form_submit' value='<?php xl('Submit','e'); ?>'
    title='<?php xl('Click to generate the report','e'); ?>' />
  </td>
 </tr>
 <tr>
  <td colspan='5' height="1">
  </td>
 </tr>

</table>

<?php
  } // end not export

// If refresh or export...
//
if ($_POST['form_submit']) {

  // Start table.
  if ($form_output != 3) {
    echo "<table width='98%' id='mymaintable' class='mymaintable'>\n";
  } // end not csv export

  $report_col_count = 0;

  // Start report loop.  This loops once for each report selected from the
  // "Rows" list.
  //
  foreach ($form_by_arr as $form_by) {

    if (!empty($arr_invalid[$form_by]) && in_array($form_content, $arr_invalid[$form_by])) {
      $alertmsg = xl('For Content') . ' = ' . addslashes($arr_content[$form_content]) . ', ' .
      xl('valid Row selections are') . ':';
      foreach ($arr_by as $bykey => $bydesc) {
        if (!in_array($form_content, $arr_invalid[$bykey])) {
          $alertmsg .= '\\n     * ' . addslashes($bydesc);
        }
      }
      continue;
    }

    // This will become the array of reportable values.
    $areport = array();

    // This accumulates the bottom line totals.
    $atotals = array();

    // This saves descriptions of the reporting keys.
    $arr_keydesc = array();

    $pd_fields = '';
    foreach ($arr_show as $askey => $asval) {
      if (substr($askey, 0, 1) == '.') continue;
      if ($askey == 'regdate' || $askey == 'sex' || $askey == 'DOB' ||
        $askey == 'lname' || $askey == 'fname' || $askey == 'mname' ||
        $askey == 'contrastart' || $askey == 'ippfconmeth' ||
        $askey == 'referral_source') continue;
      $pd_fields .= ', pd.' . $askey;
    }

    $sexcond = '';
    if ($form_sexes == '1') $sexcond = "AND pd.sex LIKE 'F%' ";
    else if ($form_sexes == '2') $sexcond = "AND pd.sex LIKE 'M%' ";
    else if ($form_sexes == '3') $sexcond = "AND pd.sex NOT LIKE 'M%' AND pd.sex NOT LIKE 'F%' ";

    if ($form_by == '105' && !in_array($form_content, array(5, 6))) {
      $alertmsg = xl("Contraceptive Products report requires Contraceptive Items Provided or New Users content type.");
    }

    // In the case where content is contraceptive item sales, we
    // scan product sales at the top level because it is important to
    // account for each of them only once.  For each sale we use
    // the IPPF2 code attached to the product.
    //
    if ($form_content == 5) { // sales of contraceptive items
      $query = "SELECT " .
        "ds.pid, ds.encounter, ds.sale_date, ds.quantity, ds.drug_id, " .
        "d.name, d.cyp_factor, d.related_code, t.pkgqty, " .
        "pd.regdate, pd.sex, pd.DOB, pd.lname, pd.fname, pd.mname, " .
        "pd.referral_source$pd_fields, " .
        "fe.date AS encdate, fe.provider_id, fe.facility_id " .
        "FROM drug_sales AS ds " .
        "JOIN drugs AS d ON d.drug_id = ds.drug_id " .
        "LEFT JOIN drug_templates AS t ON t.drug_id = ds.drug_id AND t.selector = ds.selector " .
        "JOIN patient_data AS pd ON pd.pid = ds.pid $sexcond" . sql_age_filter("fe.date") .
        "JOIN form_encounter AS fe ON fe.pid = ds.pid AND fe.encounter = ds.encounter " .
        "WHERE fe.date >= '$from_date 00:00:00' AND " .
        "fe.date <= '$to_date 23:59:59' AND " .
        "ds.pid > 0 AND ds.quantity != 0";

      if (!empty($form_fac_arr)) {
        $query .= " AND ( 1 = 2";
        foreach ($form_fac_arr as $form_facility) {
          $query .= " OR fe.facility_id = '$form_facility'";
        }
        $query .= " )";
      }

      // If any charge category was specified.
      if (!empty($form_chargecat)) {
        $query .= " AND ds.chargecat = '" . add_escape_custom($form_chargecat) . "'";
      }

      $query .= " ORDER BY ds.pid, ds.encounter, ds.drug_id";
      $res = sqlStatement($query);

      while ($row = sqlFetchArray($res)) {
        $desired = false;
        $prodcode = '';

        // TBD: I think this is obsolete.
        if ($row['cyp_factor'] > 0) {
          $desired = true;
        }

        $tmp = getRelatedContraceptiveCode($row); // Returns an IPPFCM code or ''
        $my_group_name = $contra_group_name;
        if (!empty($tmp)) {
          $desired = true;
          $prodcode = $tmp;
        }
        if (!$desired) continue; // skip if not a contraceptive product

        /**************************************************************
        // Handle MA report types.
        if ($report_type == 'm' && !empty($row['encounter'])) {
          $query = "SELECT " .
            "b.code_type, b.code, b.units, c.related_code " .
            "FROM billing AS b " .
            "LEFT OUTER JOIN codes AS c ON c.code_type = '12' AND " .
            "c.code = b.code AND c.modifier = b.modifier " .
            "WHERE b.pid = " . (0 + $row['pid']) . " AND " .
            "b.encounter = " . (0 + $row['encounter']) . " AND " .
            "b.activity = 1 AND b.code_type = 'MA' " .
            "ORDER BY b.code";
          $bres = sqlStatement($query);
          while ($brow = sqlFetchArray($bres)) {
            $tmp = getRelatedContraceptiveCode($brow);
            if (!empty($tmp)) {
              $units = empty($brow['units']) ? 1 : $brow['units'];
              process_ma_code($row, $brow['code'], $row['quantity'] * $units);
              break;
            }
          }
        }
        **************************************************************/
        // The above commented out because there are no MA reports for services where
        // $form_content is 5.

        // Product quantities are multiplied by "Basic Units" for contraception stats reporting.
        $quantity = $row['quantity'];
        // if (!empty($row['pkgqty'])) $quantity *= floatval($row['pkgqty']);

        // At this point $prodcode is the desired IPPFCM code, or empty if none.
        process_ippfcm_code($row, $prodcode, $quantity);

        // This is for the Contraceptive Products report (105).
        if ($form_by === '105') {
          $key = '{' . $my_group_name . '}' . $row['name'];
          loadColumnData($key, $row, $quantity);
        }
      }
    }

    // Get referrals and related patient data.
    if ($form_content != 5 && ($form_by === '9' || $form_by === '10' ||
      $form_by === '14' || $form_by === '15' || $form_by === '20' ||
      $form_by === '1'))
    {
      $exttest = "d5.field_value = '2'";   // outbound external
      $datefld = "d1.field_value";

      if ($form_by === '9') {
        $exttest = "d5.field_value = '3'"; // outbound internal
      }
      else if ($form_by === '14') {
        $exttest = "d5.field_value = '5'"; // inbound internal
      }
      else if ($form_by === '15') {
        $exttest = "d5.field_value = '4'"; // inbound external
      }
      else if ($form_by === '20') {        // external referral followups
        $datefld = "d4.field_value";
      }
      $query = "SELECT " .
        "f.pid, fe.facility_id, $datefld AS encdate, " .
        "d2.field_value AS refer_related_code, " .
        "d3.field_value AS reply_related_code, " .
        "pd.regdate, pd.referral_source, pd.home_facility, " .
        "pd.sex, pd.DOB, pd.lname, pd.fname, pd.mname $pd_fields " .
        "FROM forms AS f " .
        "LEFT JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'refer_date' " .
        "LEFT JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'refer_related_code' " .
        "LEFT JOIN lbf_data AS d3 ON d3.form_id = f.form_id AND d3.field_id = 'reply_related_code' " .
        "LEFT JOIN lbf_data AS d4 ON d4.form_id = f.form_id AND d4.field_id = 'reply_date' " .
        "JOIN lbf_data AS d5 ON d5.form_id = f.form_id AND d5.field_id = 'refer_external' " .
        "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
        "JOIN patient_data AS pd ON pd.pid = f.pid $sexcond" . sql_age_filter($datefld) .
        "WHERE f.formdir = 'LBFref' AND f.deleted = 0 AND $datefld IS NOT NULL AND " .
        "$datefld >= '$from_date' AND $datefld <= '$to_date' AND $exttest " .
        "ORDER BY f.pid, f.id";
      $res = sqlStatement($query);

      while ($row = sqlFetchArray($res)) {
        if (empty($row['facility_id'])) $row['facility_id'] = $row['home_facility'];
        if ($form_clinics || !empty($form_fac_arr)) {
          // Do facility filtering if applicable.
          if (!empty($form_fac_arr) && !in_array($row['facility_id'], $form_fac_arr)) continue;
        }
        process_referral($row);
      }
    }

    // Reporting New Acceptors by contraceptive method (or method after abortion),
    // service or product is a special case that gets one method, service or product
    // on each contraceptive start date.
    //
    if ($form_content == 6) {

     /****************************************************************
     // Per CV 2012-10-12 re the IPPF Stats report:
     // "the report should be modified so that the report can run when Content = New User and
     // Row = Contraceptive Service [...] There should be a warning when it’s run for Contraceptive
     // Product or Contraceptive Method that New User content type is valid only with Contraceptive
     // Service reporting."
     if ($report_type == 'i' && $form_by !== '104') { // content is new acceptors but incompatible report type
      $alertmsg = xl("New Acceptors content type is valid only for contraceptive service reporting.");
     }
     else
     if (in_array($form_by, array('6', '7', '104', '105'))) {
     ****************************************************************/

      /****************************************************************
      // This enumerates instances of "contraception starting" for the MA.  Note that a
      // client could be counted twice, once for nonsurgical and once for surgical.
      // Note also that we filter based on start date which is the same as encounter
      // date.
      $query = "SELECT " .
        "d1.field_value AS ippfconmeth, " .
        "fe.pid, fe.encounter, fe.date AS encdate, fe.date AS contrastart, fe.facility_id, " .
        "f.user AS provider, " .
        "pd.regdate, pd.sex, pd.DOB, pd.lname, pd.fname, pd.mname, " .
        "pd.referral_source$pd_fields " .
        "FROM forms AS f " .
        "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter AND " .
        "fe.date IS NOT NULL AND fe.date >= '$from_date' AND fe.date <= '$to_date' ";
      if ($form_facility) {
        $query .= "AND fe.facility_id = '$form_facility' ";
      }
      // Content type 6, acceptors new to modern contraception
      $query .=
        "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' AND d1.field_value != '' " .
        "JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'pastmodern' AND d2.field_value = '0' " .
        "JOIN patient_data AS pd ON pd.pid = f.pid $sexcond " . sql_age_filter("fe.date") .
        "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 ";
      $query .=
        "ORDER BY fe.pid, encdate DESC, fe.encounter DESC, f.form_id";
      ****************************************************************/

      // This enumerates instances of "contraception starting" for the MA.
      // The fetch loop will do most of the filtering of what we don't want.
      //
      $query = "SELECT " .
        "d1.field_value AS ippfconmeth, " .
        "fe.pid, fe.encounter, fe.date AS encdate, fe.date AS contrastart, fe.facility_id, " .
        "f.user AS provider, " .
        "pd.regdate, pd.sex, pd.DOB, pd.lname, pd.fname, pd.mname, " .
        "pd.referral_source$pd_fields " .
        "FROM forms AS f " .
        "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter AND " .
        "fe.date IS NOT NULL AND fe.date <= '$to_date' " .
        "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' AND d1.field_value != '' " .
        "JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'pastmodern' AND d2.field_value = '0' " .
        "JOIN patient_data AS pd ON pd.pid = f.pid $sexcond " .
        "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 " .
        "ORDER BY fe.pid, fe.encounter, f.form_id";

      $res = sqlStatement($query);
      $lastpid = 0;
      $lastyear = '0000';
      //
      while ($row = sqlFetchArray($res)) {
        $contrastart = substr($row['contrastart'], 0, 10);
        $thispid     = $row['pid'];
        $thisenc     = $row['encounter'];
        $thisyear    = substr($contrastart, 0, 4);
        $ippfconmeth = '';
        if (!empty($row['ippfconmeth']) && substr($row['ippfconmeth'], 0, 7) == 'IPPFCM:') {
          $ippfconmeth = substr($row['ippfconmeth'], 7);
        }

        /*************************************************************
        // Leslie on 2012-03-12 says IPPF New Users may only be reported once per calendar year.
        // While on this, we'll also make sure "acceptors new to modern contraception" happen
        // only once regardless of the year.  Note we are sorting by descending date within
        // pid, so only the last occurrence per client will be reported.
        if ($thispid == $lastpid && ($thisyear == $lastyear || $form_content != 3)) {
        *************************************************************/

        // Make sure "acceptor new to modern contraception" happens only once per client
        // regardless of the year.  Note we are sorting by ascending date within pid,
        // so only the first occurrence per client will be counted.
        if ($thispid == $lastpid) {
          continue;
        }
        // Skip if not in report date range.
        if ($contrastart < $from_date) {
          continue;
        }
        // Skip if facility filter (if used) is not satisfied.
        if (!empty($form_fac_arr) && !in_array($row['facility_id'], $form_fac_arr)) {
          continue;
        }
        // Skip if age filters are not satisfied.
        $age = getAge($row['DOB'], $contrastart);
        if (!is_null($age_minimum)) {
          if ($age < $age_minimum) continue;
        }
        if (!is_null($age_maximum)) {
          if ($age > $age_maximum) continue;
        }

        $lastpid = $thispid;
        $lastyear = $thisyear;

        if ($form_by == '104') {
          // Specific contraceptive service.
          $ippf2code = method_to_ippf2_code($ippfconmeth);
          // If the new method is missing, try to get it from the billing table.
          // That should happen only for old data from sites upgraded from release 3.2.0.7.
          if (empty($ippf2code)) {
            service_contraception_scan($row['pid'], $row['encounter']);
            $ippf2code = $contraception_ippf2_code;
          }
          process_ippf_code($row, $ippf2code);
        }
        else if ($form_by == '105') {
          // For contraceptive product reporting we build a key containing
          // the group and product names of the associated product sale with
          // highest CYP.
          loadColumnData(product_contraception_scan($thispid, $thisenc), $row);
        }
        else {
          /***********************************************************
          // If the new method is missing, try to get it from the billing table.
          // That should happen only for old data from sites upgraded from release 3.2.0.7.
          if (empty($ippfconmeth)) {
            $ippfconmeth = service_contraception_scan($row['pid'], $row['encounter']);
          }
          ***********************************************************/
          process_ippfcm_code($row, $ippfconmeth);
        }
      } // end while

    } // end if

    else if ($form_content != 5 && !in_array($form_by, array(9, 10, 14, 15, 20))) {
      // This gets us all MA and ADM codes, with encounter and patient
      // info attached and grouped by patient and encounter.
      $query = "SELECT " .
        "fe.pid, fe.encounter, fe.date AS encdate, fe.facility_id, " .
        "f.user AS provider, " .
        "pd.sex, pd.DOB, pd.lname, pd.fname, pd.mname, pd.regdate, " .
        "pd.referral_source$pd_fields, " .
        "b.code_type, b.code, b.units, c.related_code, lo.title AS lo_title " .
        "FROM form_encounter AS fe " .
        "LEFT JOIN forms AS f ON f.pid = fe.pid AND f.encounter = fe.encounter AND " .
        "f.formdir = 'newpatient' AND f.form_id = fe.id AND f.deleted = 0 " .
        "JOIN patient_data AS pd ON pd.pid = fe.pid $sexcond" . sql_age_filter("fe.date") .
        "LEFT OUTER JOIN billing AS b ON " .
        "b.pid = fe.pid AND b.encounter = fe.encounter AND b.activity = 1 " .
        "AND ( b.code_type = 'MA' OR b.code_type = 'ADM' ) " .
        "LEFT OUTER JOIN code_types AS ct ON ct.ct_key = b.code_type " .
        "LEFT OUTER JOIN codes AS c ON c.code_type = ct.ct_id AND " .
        "c.code = b.code AND c.modifier = b.modifier " .
        "LEFT OUTER JOIN list_options AS lo ON " .
        "lo.list_id = 'superbill' AND lo.option_id = c.superbill AND lo.activity = 1 " .
        "WHERE fe.date >= '$from_date 00:00:00' AND " .
        "fe.date <= '$to_date 23:59:59' ";

      if ($report_type == 'm') {
        // If one or more service codes were specified.
        if ($form_related_code) {
          $qsvc = "";
          $arel = explode(';', $form_related_code);
          foreach ($arel as $tmp) {
            list($reltype, $relcode) = explode(':', $tmp);
            if (empty($relcode) || empty($reltype)) continue;
            if ($qsvc) $qsvc .= " OR ";
            $qsvc .= "( b.code_type = '$reltype' AND b.code = '$relcode' )";
          }
          if ($qsvc) $query .= "AND ( $qsvc )";
        }
        // It is not valid to specify a service category in addition to service codes, but if you
        // do the service codes will take precedence.  Thus the "else" here.
        else if (!empty($form_svccat)) {
          $query .= "AND lo.option_id IS NOT NULL AND lo.option_id = '" . add_escape_custom($form_svccat) . "' ";
        }
      }

      if (!empty($form_fac_arr)) {
        $query .= "AND ( 1 = 2";
        foreach ($form_fac_arr as $form_facility) {
          $query .= " OR fe.facility_id = '$form_facility'";
        }
        $query .= " ) ";
      }

      // If any charge category was specified.
      if (!empty($form_chargecat)) {
        $query .= " AND b.chargecat = '" . add_escape_custom($form_chargecat) . "'";
      }

      $query .= "ORDER BY fe.pid, fe.encounter, b.code";
      $res = sqlStatement($query);

      $prev_encounter = 0;

      while ($row = sqlFetchArray($res)) {
        if ($row['encounter'] != $prev_encounter) {
          $prev_encounter = $row['encounter'];
          process_visit($row);
        }
        $units = empty($row['units']) ? 1 : $row['units'];
        if ($row['code_type'] === 'MA') {
          process_ma_code($row, '', $units);
          if (!empty($row['related_code'])) {
            $relcodes = explode(';', $row['related_code']);
            foreach ($relcodes as $codestring) {
              if ($codestring === '') continue;
              list($codetype, $code) = explode(':', $codestring);
              if ($codetype === 'IPPF2') {
                process_ippf_code($row, $code, $units);
              }
              else if ($codetype === 'IPPFCM') {
                process_ippfcm_code($row, $code, $units);
              }
            }
          }
        }
        else if ($row['code_type'] === 'ADM') {
          process_adm_code($row, '', $units);
        }
      } // end while
    } // end if

    // Sort everything for reporting.
    uksort($areport, 'keydesc_compare');
    foreach ($arr_titles as $atkey => $dummy) ksort($arr_titles[$atkey]);

    // If writing a single report as HTML then start thead section.
    if ($form_output != 3 && count($form_by_arr) == 1) echo " <thead>\n";

    // Generate a blank row to separate from the previous report.
    if ($report_col_count && $form_output != 3) {
      genStartRow("bgcolor='#ffffff'");
      genHeadCell('&nbsp;', 'left', $report_col_count);
      genEndRow();
    }

    // Compute number of columns per period.
    //
    $period_col_count = 1;          // 1 for the ending totals column
    foreach ($form_show as $value) {
      if ($value == '.total') {     // Total Services
        // .total is obsolete but keeping this code just in case.
        ++$period_col_count;
      }
      else if ($value == '.age2') { // Age
        $period_col_count += 2;
      }
      else if ($value == '.age9') { // Age
        $period_col_count += 9;
      }
      else if ($value == '.age13') { // Age
        $period_col_count += 13;
      }
      else if ($value == '.age2G') { // Age
        $period_col_count += $age2G_column_count;
      }
      else if ($value == '.age9G') { // Age
        $period_col_count += $age9G_column_count;
      }
      else if ($value == '.age13G') { // Age
        $period_col_count += $age13G_column_count;
      }
      else if ($arr_show[$value]['list_id'] || !empty($arr_titles[$value])) {
        $period_col_count += count($arr_titles[$value]);
      }
    }
    // Columns per line includes the first 2 for the key and key description.
    $report_col_count = 2 + $period_col_count * count($arr_periods);

    // Heading line for periods, if applicable.
    //
    if ($form_periods) {
      genStartRow("bgcolor='#dddddd'");
      genHeadCell('', 'left', 2);
      foreach ($arr_periods as $pdate => $ptitle) {
        genHeadCell($ptitle, 'center', $period_col_count);
      }
      genEndRow();
    }

    // Generate field name headings line, i.e. category titles.
    //
    genStartRow("bgcolor='#dddddd'");

    // Field name heading starts with report name spanning 2 columns.
    //
    genHeadCell($arr_by[$form_by], 'left', 2);

    // Generate remaining field name headings.
    // There is an identical set of these for each period.
    //
    foreach ($arr_periods as $dummy) {
      foreach ($form_show as $value) {
        if ($value == '.total') { // Total Services
          genHeadCell('');
        }
        else if ($value == '.age2') { // Age
          genHeadCell($arr_show[$value]['title'], 'center', 2);
        }
        else if ($value == '.age9') { // Age
          genHeadCell($arr_show[$value]['title'], 'center', 9);
        }
        else if ($value == '.age13') { // Age
          genHeadCell($arr_show[$value]['title'], 'center', 13);
        }
        else if ($value == '.age2G') { // Age
            genHeadCell("Age Category (2) Male", 'center', 3 );
            genHeadCell("Age Category (2) Female", 'center', 3 );
            genHeadCell("Age Category (2) Total", 'center', 2 );
        }
        else if ($value == '.age9G') { // Age
            genHeadCell("Age Category (9) Male", 'center', 10 );
            genHeadCell("Age Category (9) Female", 'center', 10 );
            genHeadCell("Age Category (9) Total", 'center', 9 );
        }
        else if ($value == '.age13G') { // Age
            genHeadCell("Age Category (13) Male"  , 'center', 14);
            genHeadCell("Age Category (13) Female", 'center', 14);
            genHeadCell("Age Category (13) Total" , 'center', 13);
        }
        else if ($arr_show[$value]['list_id']) {
          genHeadCell($arr_show[$value]['title'], 'center', count($arr_titles[$value]));
        }
        else if (!empty($arr_titles[$value])) {
          genHeadCell($arr_show[$value]['title'], 'center', count($arr_titles[$value]));
        }
      }
      genHeadCell(''); // "Total" is on the next heading line below.
    }

    genEndRow();

    // Generate second column headings line, with individual titles.
    //
    genStartRow("bgcolor='#dddddd'");
    // If the key is an MA or IPPF2 code, then add a column for its description.
    if (uses_description($form_by)) {
      genHeadCell(array(xl('Code'), xl('Description')));
    }
    else if (uses_group_names($form_by)) {
      genHeadCell(array(xl('Group'), xl('Method')));
    }
    else {
      genHeadCell('', 'left', 2);
    }
    // Generate headings for values to be shown.
    // There is an identical set of these for each period.
    foreach ($arr_periods as $dummy) {
      foreach ($form_show as $value) {
        if ($value == '.total') { // Total Services
          genHeadCell(xl('Total'));
        }
        else if ($value == '.age2') { // Age
          genHeadCell(xl('0-24' ), 'right');
          genHeadCell(xl('25+'  ), 'right');
        }
        else if ($value == '.age9') { // Age
          genHeadCell(xl('0-9'  ), 'right');
          genHeadCell(xl('10-14'), 'right');
          genHeadCell(xl('15-19'), 'right');
          genHeadCell(xl('20-24'), 'right');
          genHeadCell(xl('25-29'), 'right');
          genHeadCell(xl('30-34'), 'right');
          genHeadCell(xl('35-39'), 'right');
          genHeadCell(xl('40-44'), 'right');
          genHeadCell(xl('45+'  ), 'right');
        }
        else if ($value == '.age13') { // Age
          genHeadCell(xl('0-9'  ), 'right');
          genHeadCell(xl('10-14'), 'right');
          genHeadCell(xl('15-19'), 'right');
          genHeadCell(xl('20-24'), 'right');
          genHeadCell(xl('25-29'), 'right');
          genHeadCell(xl('30-34'), 'right');
          genHeadCell(xl('35-39'), 'right');
          genHeadCell(xl('40-44'), 'right');
          genHeadCell(xl('45-49'), 'right');
          genHeadCell(xl('50-54'), 'right');
          genHeadCell(xl('55-59'), 'right');
          genHeadCell(xl('60-64'), 'right');
          genHeadCell(xl('65+'  ), 'right');
        }
        else if ($value == '.age2G') { // Age
          genHeadCell(xl('M 0-24' ), 'right');
          genHeadCell(xl('M 25+'  ), 'right');
          genHeadCell(xl('M Total'  ), 'right');
          genHeadCell(xl('F 0-24' ), 'right');
          genHeadCell(xl('F 25+'  ), 'right');
          genHeadCell(xl('F Total'  ), 'right');
          genHeadCell(xl('Tot 0-24' ), 'right');
          genHeadCell(xl('Tot 25+'  ), 'right');
        }
        else if ($value == '.age9G') { // Age
          genHeadCell(xl('M 0-9'  ), 'right');
          genHeadCell(xl('M 10-14'), 'right');
          genHeadCell(xl('M 15-19'), 'right');
          genHeadCell(xl('M 20-24'), 'right');
          genHeadCell(xl('M 25-29'), 'right');
          genHeadCell(xl('M 30-34'), 'right');
          genHeadCell(xl('M 35-39'), 'right');
          genHeadCell(xl('M 40-44'), 'right');
          genHeadCell(xl('M 45+'  ), 'right');
          genHeadCell(xl('M Total'  ), 'right');
          genHeadCell(xl('F 0-9'  ), 'right');
          genHeadCell(xl('F 10-14'), 'right');
          genHeadCell(xl('F 15-19'), 'right');
          genHeadCell(xl('F 20-24'), 'right');
          genHeadCell(xl('F 25-29'), 'right');
          genHeadCell(xl('F 30-34'), 'right');
          genHeadCell(xl('F 35-39'), 'right');
          genHeadCell(xl('F 40-44'), 'right');
          genHeadCell(xl('F 45+'  ), 'right');
          genHeadCell(xl('F Total'  ), 'right');
          genHeadCell(xl('Tot 0-9'  ), 'right');
          genHeadCell(xl('Tot 10-14'), 'right');
          genHeadCell(xl('Tot 15-19'), 'right');
          genHeadCell(xl('Tot 20-24'), 'right');
          genHeadCell(xl('Tot 25-29'), 'right');
          genHeadCell(xl('Tot 30-34'), 'right');
          genHeadCell(xl('Tot 35-39'), 'right');
          genHeadCell(xl('Tot 40-44'), 'right');
          genHeadCell(xl('Tot 45+'  ), 'right');
        }
        else if ($value == '.age13G') { // Age
          foreach (array('M', 'F', 'Tot') as $tmp1) {
            foreach (array('0-9','10-14','15-19','20-24','25-29','30-34','35-39','40-44',
              '45-49','50-54','55-59','60-64','65+','Total') as $tmp2)
            {
              if ($tmp1 == 'Tot' && $tmp2 == 'Total') continue;
              genHeadCell(xl("$tmp1 $tmp2"), 'right');
            }
          }
        }
        else if ($arr_show[$value]['list_id']) {
          foreach ($arr_titles[$value] as $key => $dummy) {
            genHeadCell(getListTitle($arr_show[$value]['list_id'],$key), 'right');
          }
        }
        else if (!empty($arr_titles[$value])) {
          foreach ($arr_titles[$value] as $key => $dummy) {
            genHeadCell($key, 'right');
          }
        }
      }
      genHeadCell(xl('Total'), 'right');
    }
    genEndRow();

    // If writing a single report as HTML then end thead section.
    if ($form_output != 3 && count($form_by_arr) == 1) echo " </thead>\n";

    $encount = 0;

    // These support group subtotals.
    $last_group = '';
    $last_group_count = 0;
    $asubtotals = array();

    foreach ($areport as $key => $varr) {
      $display_key = $key;
      // Get group name, if any, for this key.
      $this_group = '';
      if (preg_match('/^{(.*)}(.*)/', $key, $tmp)) {
        $this_group = $tmp[1];
        $display_key = $tmp[2];
      }
      // This key pattern is used for contraceptive methods for IPPF Stats only.
      else if (preg_match('/^([\S]*) (.*){(.*)}$/', $key, $tmp)) {
        $this_group = $tmp[3];
        $display_key = $tmp[2];
      }
      if ($form_output != 3 && !in_array($form_content, array(2, 4, 7))) {
        // If it is a group change and there is a non-empty $last_group,
        // generate a subtotals line and clear subtotals array.
        // Set $last_group to the current group name.
        if ($this_group != $last_group) {
          if ($last_group_count > 0) {
            // Write subtotals only if more than one row in the group.
            writeSubtotals($last_group, $asubtotals, $form_by);  
          }
          if ($last_group !== '' && !$form_clinics) {
            // Add some space before the next group.
            genStartRow("bgcolor='#dddddd'");
            genHeadCell('&nbsp;', 'left', $report_col_count);
            genEndRow();
          }
          $last_group = $this_group;
          $last_group_count = 0;
          $asubtotals = array();
        }
      }
      ++$last_group_count;

      $dispkey = $display_key;
      $dispspan = 2;

      // If the key is an MA or REF or IPPF2 code, then add a column for its description.
      if (uses_description($form_by)) {
        $dispkey = array($display_key, '');
        $dispspan = 1;
        if (isset($arr_keydesc[$key])) {
          $dispkey[1] = $arr_keydesc[$key];
        }
      }

      // Or if a separate column for group name is needed, do that.
      else if (uses_group_names($form_by)) {
        $dispkey = array($this_group, $display_key);
        $dispspan = 1;
      }

      // If writing clinic-specific rows then write a title line for the key.
      if ($form_clinics) {
        genStartRow("bgcolor='#dddddd'");
        genAnyCell($dispkey, 'left', 'detail', $dispspan, true);
        genAnyCell('', 'left', 'detail', $report_col_count - 2);
        genEndRow();
        $encount = 0;
      }

      // Sort by clinic name with totals last.
      uksort($varr['.dtl'], 'clinic_compare');

      foreach ($varr['.dtl'] as $clikey => $cliarr) {
        $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
        if ($form_clinics) $bgcolor = '#ddddff';
        genStartRow("bgcolor='$bgcolor'");
        if ($form_clinics) {
          $cliname = xl('All Clinics');
          if ($clikey == '-1') {
            $cliname = xl('Unassigned');
          }
          else if ($clikey) {
            $tmp = sqlQuery("SELECT name FROM facility WHERE id = '$clikey'");
            $cliname = $tmp['name'];
          }
          // genAnyCell($cliname, $clikey ? 'right' : 'left', 'detail', 2);
          genAnyCell($cliname, 'right', 'detail', 2);
        }
        else {
          genAnyCell($dispkey, 'left', 'detail', $dispspan, true);
        }

        // This is the column index for accumulating column totals.
        $cnum = 0;

        // Loop on periods to write the numeric data.
        foreach ($arr_periods as $perkey => $dummy) {
          $totalsvcs = $areport[$key]['.dtl'][$clikey][$perkey]['.wom'] +
                       $areport[$key]['.dtl'][$clikey][$perkey]['.men'];
          foreach ($form_show as $value) {
            if ($value == '.total') { // Total Services
              genNumCell($totalsvcs, $cnum++, $clikey);
            }
            else if ($value == '.age2') { // Age
              for ($i = 0; $i < 2; ++$i) {
                genNumCell($areport[$key]['.dtl'][$clikey][$perkey]['.age2'][$i], $cnum++, $clikey);
              }
            }
            else if ($value == '.age9') { // Age
              for ($i = 0; $i < 9; ++$i) {
                genNumCell($areport[$key]['.dtl'][$clikey][$perkey]['.age9'][$i], $cnum++, $clikey);
              }
            }
            else if ($value == '.age13') { // Age
              for ($i = 0; $i < 13; ++$i) {
                genNumCell($areport[$key]['.dtl'][$clikey][$perkey]['.age13'][$i], $cnum++, $clikey);
              }
            }
            else if ($value == '.age2G') { // Age
              for ($i = 0; $i < $age2G_column_count; ++$i) {
                genNumCell($areport[$key]['.dtl'][$clikey][$perkey]['.age2G'][$i], $cnum++, $clikey);
              }
            }
            else if ($value == '.age9G') { // Age
              for ($i = 0; $i < $age9G_column_count; ++$i) {
                genNumCell($areport[$key]['.dtl'][$clikey][$perkey]['.age9G'][$i], $cnum++, $clikey);
              }
            }
            else if ($value == '.age13G') { // Age
              for ($i = 0; $i < $age13G_column_count; ++$i) {
                genNumCell($areport[$key]['.dtl'][$clikey][$perkey]['.age13G'][$i], $cnum++, $clikey);
              }
            }
            else if (!empty($arr_titles[$value])) {
              foreach ($arr_titles[$value] as $title => $dummy) {
                genNumCell($areport[$key]['.dtl'][$clikey][$perkey][$value][$title], $cnum++, $clikey);
              }
            }
          }

          // Write this period's Total column data.
          if (!$clikey) {
            $atotals[$cnum]    += $totalsvcs;
            $asubtotals[$cnum] += $totalsvcs;
          }
          ++$cnum;
          genAnyCell($totalsvcs, 'right', 'dehead');

        } // end foreach period

        genEndRow();

      } // end foreach clinic

    } // end foreach reporting key

    // If we are exporting or counting unique clients, new acceptors, new or returning clients,
    // then the totals line is skipped.
    //
    if ($form_output != 3 && !in_array($form_content, array(2, 4, 7))) {

      // if ($report_type !== 'i') {
        // If there is a non-empty $last_group, generate a subtotals line.
        if ($last_group_count > 0) {
          writeSubtotals($last_group, $asubtotals, $form_by);
        }
      // }

      if ($last_group !== '' && !$form_clinics) {
        // Add some space before the totals line.
        genStartRow("bgcolor='#dddddd'");
        genHeadCell('&nbsp;', 'left', $report_col_count);
        genEndRow();
      }

      // Generate the line of totals.
      genStartRow("bgcolor='#dddddd'");

      // If the key is an MA or IPPF2 code, then add a column for its description.
      $tmp = xl('Total') . ' ' . $arr_content[$form_content];
      if (uses_description($form_by)) {
        genHeadCell(array($tmp, ''));
      } else {
        genHeadCell($tmp, 'left', 2);
      }

      for ($cnum = 0; $cnum < count($atotals); ++$cnum) {
        genHeadCell($atotals[$cnum], 'right');
      }
      genEndRow();
    }

  } // end foreach $form_by_arr

  // End of table.
  if ($form_output != 3) {
    echo "</table>\n";
  }

} // end of if refresh or export

  if ($form_output != 3) {
?>
</form>
</center>

<script language='JavaScript'>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});

<?php
  if ($alertmsg) {
    echo "alert('" . $alertmsg . "');\n";
  }
?>

<?php if ($form_output == 2) { ?>
 window.print();
<?php } ?>
</script>

</body>
</html>
<?php
  } // end not export
?>
