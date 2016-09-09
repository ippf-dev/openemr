<?php
// Copyright (C) 2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This script creates a DHIS2 export file and sends it to the users's
// browser for download.

$fake_register_globals = false;
$sanitize_all_escapes  = true;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");
include_once("../../custom/code_types.inc.php");

if (!acl_check('admin', 'super')) die("Not authorized!");

$alertmsg = '';

// Arrays to save code descriptions and facility names.
$arr_code_desc = array();
$arr_org_unit  = array();

// Save array item for a code description.
function noteCodeDescription($codetype, $code, $key) {
  global $code_types, $arr_code_desc;
  if (isset($arr_code_desc[$key])) return;
  $row = sqlQuery("SELECT code_text FROM codes WHERE " .
    "code_type = ? AND code = ? " .
    "ORDER BY active DESC, id LIMIT 1",
    array($code_types[$codetype]['id'], $code));
  if (isset($row['code_text'])) {
    $arr_code_desc[$key] = $row['code_text'];
  }
}

// Save array item for an org unit name.
function noteOrgUnitName($key, $name) {
  global $arr_org_unit;
  if (isset($arr_org_unit[$key])) return;
  $arr_org_unit[$key] = $name;
}

function recordStats($dataelement, $period, $orgunit, $sexagecategorycombo, $clientstatus, $complication, $quantity=1) {
  global $outarr;
  $key = "$period|$orgunit|$dataelement|$sexagecategorycombo|$clientstatus|$complication";
  if (!isset($outarr[$key])) $outarr[$key] = 0;
  if (empty($quantity)) $quantity = 1;
  $outarr[$key] += $quantity;
}

/**********************************************************************
// Get the specified patient's new acceptor date and method.
// Method will be returned as a bare IPPFCM code in the 2nd array element.
//
function getNewAcceptorInfo($pid) {
  $ret = array('', '', '');
  $query = "SELECT " .
    "fe.encounter, fe.date AS contrastart, d1.field_value AS contrameth, d2.field_value AS pastmodern " .
    "FROM forms AS f " .
    "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
    "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' AND d1.field_value != '' " .
    // Next line is a kludge to exclude emergency contraception pills.
    // This is not really adequate because a following New User event can be lost since the EC
    // visit will have forced its pastmodern value to true.
    "AND d1.field_value != 'IPPFCM:4620' " .
    "JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'pastmodern' AND d2.field_value = '0' " .
    "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 AND f.pid = ? AND " .
    "d1.field_value LIKE 'IPPFCM:%' " .
    "ORDER BY contrastart LIMIT 1";

  // error_log("getNewAcceptorInfo($pid): $query"); // debugging

  $row = sqlQuery($query, array($pid));

  // if (!empty($row['contrameth']) && empty($row['pastmodern'])) {
  if (!empty($row['contrameth'])) {
    $ret[0] = substr($row['contrastart'], 0, 10);
    $ret[1] = substr($row['contrameth'], 7);
    $ret[2] = $row['encounter'];
  }
  return $ret;
}
**********************************************************************/

// Compute value to report for age and sex combination. Note age is relative to the visit.
//
function getCatCombo($sex, $dob, $asofdate) {
  $age = getPatientAge($dob, str_replace('-', '', $asofdate));
  if (empty($dob)) $age = 999;
  $coc = '';
  // The following per JG email 2016-02-15:
  if      ($age <  6) $coc = $sex == 'M' ? 'M0-5'    : ($sex == 'F' ? 'F0-5'    : 'U0-5'   );
  else if ($age < 11) $coc = $sex == 'M' ? 'M6-10'   : ($sex == 'F' ? 'F6-10'   : 'U6-10'  );
  else if ($age < 15) $coc = $sex == 'M' ? 'M11-14'  : ($sex == 'F' ? 'F11-14'  : 'U11-14' );
  else if ($age < 20) $coc = $sex == 'M' ? 'M15-19'  : ($sex == 'F' ? 'F15-19'  : 'U15-19' );
  else if ($age < 25) $coc = $sex == 'M' ? 'M20-24'  : ($sex == 'F' ? 'F20-24'  : 'U20-24' );
  else if ($age < 30) $coc = $sex == 'M' ? 'M25-29'  : ($sex == 'F' ? 'F25-29'  : 'U25-29' );
  else if ($age < 35) $coc = $sex == 'M' ? 'M30-34'  : ($sex == 'F' ? 'F30-34'  : 'U30-34' );
  else if ($age < 40) $coc = $sex == 'M' ? 'M35-39'  : ($sex == 'F' ? 'F35-39'  : 'U35-39' );
  else if ($age < 45) $coc = $sex == 'M' ? 'M40-44'  : ($sex == 'F' ? 'F40-44'  : 'U40-44' );
  else if ($age < 50) $coc = $sex == 'M' ? 'M45-49'  : ($sex == 'F' ? 'F45-49'  : 'U45-49' );
  else                $coc = $sex == 'M' ? 'M50plus' : ($sex == 'F' ? 'F50plus' : 'U50plus');
  return $coc;
}

// Period calculation. There are 6 different formats depending on the date range.
//
function getPeriod($encounter_date) {
  // Period is now always YYYYMM (requested by JG 2015-10-21).
  $period = substr($encounter_date, 0, 4) . substr($encounter_date, 5, 2);
  return $period;
}

function getDataElement($prefix, $code, $country) {
  $de = "$prefix-$code";
  if (
    $code == '1141130302102' ||
    $code == '1141130302103' ||
    $code == '1141130302104' ||
    $code == '1141130302105' ||
    $code == '1141130302106' ||
    $code == '1142030302201' ||
    $code == '1142030302202'
  ) {
    $country = strtolower(trim($country));
    if (
      $country == 'bangladesh' ||
      $country == 'india'      ||
      $country == 'nepal'
    ) {
      $de .= 'A';
    }
    else {
      $de .= 'G';
    }
  }
  // Save code description for final reporting.
  noteCodeDescription('IPPF2', $code, $de);
  return $de;
}

// Get the IPPF2 code related to a given IPPFCM code.
// Cloned from ippf_statistics_2.php.
//
function method_to_ippf2_code($ippfcm) {
  global $code_types;
  $ret = '';
  $rrow = sqlQuery("SELECT related_code FROM codes WHERE " .
    "code_type = ? AND code = ? AND active = 1 " .
    "ORDER BY id LIMIT 1",
    array($code_types['IPPFCM']['id'], $ippfcm));
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

// Generate a SQL condition that tests if the specified column includes an
// IPPF2 code for an abortion procedure.
// This is copied from ippf_statistics_2.php.
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
// This is copied from ippf_statistics_2.php.
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
// This is copied from ippf_statistics_2.php.
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
    "lo.option_id = d.field_value " .
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

// Returns client status if ippf2 code is for Pre-Abortion Counseling or
// Post-Abortion Followup services, otherwise empty string.
// This is adapted from non-function code in ippf_statistics_2.php.
//
function clientStatus($pid, $encdate, $code) {
  $key = '';
  if (preg_match('/^2111010121/', $code)) { // pre-abortion counseling
    $key = getGcacClientStatus(array('pid' => $pid, 'encdate' => $encdate));
  }
  else if (in_array($code, array(
    '2111010122000', // Abortion - Counselling - Post-abortion
    '2112020202101', // Abortion - Consultation - Follow up consultation - Harm reduction model
    '2113130301104', // Abortion - Management - Medical - follow up
    '2113130301110', // Abortion - Management - Medical - Treatment of complications
    '2113230302307', // Abortion - Management - Surgical - follow up
    '2113230302310', // Abortion - Management - Surgical - Treatment of complications
  )) || preg_match('/^211403/', $code)) { // Incomplete abortion codes
    $key = getGcacClientStatus(array('pid' => $pid, 'encdate' => $encdate));
  }
  return $key;
}

// Translate an IPPFCM code to the corresponding descriptive name of its
// contraceptive method, or to an empty string if none applies.
// This is copied from ippf_statistics_2.php.
//
function getContraceptiveMethod($code) {
  global $contra_group_name;
  $contra_group_name = '00000 ' . xl('No Group');
  $key = '';
  $row = sqlQuery("SELECT c.code_text, lo.title FROM codes AS c " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'contrameth' AND " .
    "lo.option_id = c.code_text_short " .
    "WHERE c.code_type = '32' AND c.code = '$code'");
  if (!empty($row['code_text'])) {
    $key = $row['code_text'];
    if (!empty($row['title'])) {
      $contra_group_name = $row['title'];
    }
  }
  return $key;
}

// Contraceptive method for new contraceptive adoption following abortion.
// Get it from the IPPFCM code if there is a suitable recent abortion service
// or GCAC form.
// This is adapted from ippf_statistics_2.php.
// Call this for each IPPFCM code in a query of LBFccicon forms; look for
// 'instances of "contraception starting"' in the stats report code.
//
function postAbortionMethod($pid, $encdate, $cmcode) {
  global $contra_group_name;
  $key = getContraceptiveMethod($cmcode);
  if (empty($key)) return '';
  $key = '{' . $contra_group_name . '}' . $key;
  // Skip this if no recent gcac service nor gcac form with acceptance.
  // Include incomplete abortion treatment services per AM's discussion
  // with Dr. Celal on 2011-04-19.
  if (!hadRecentAbService($pid, $encdate, true)) {
    $query = "SELECT COUNT(*) AS count " .
      "FROM forms AS f, form_encounter AS fe, lbf_data AS d " .
      "WHERE f.pid = '$pid' AND " .
      "f.formdir = 'LBFgcac' AND " .
      "f.deleted = 0 AND " .
      "fe.pid = f.pid AND fe.encounter = f.encounter AND " .
      "fe.date <= '$encdate' AND " .
      "DATE_ADD(fe.date, INTERVAL 14 DAY) > '$encdate' AND " .
      "d.form_id = f.form_id AND " .
      "d.field_id = 'client_status' AND " .
      "( d.field_value = 'maaa' OR d.field_value = 'refout' )";
    $irow = sqlQuery($query);
    if (empty($irow['count'])) return '';
  }
  return $key;
}

// Call this to generate REF or XRF rows.
//
function referralScan($reply=false) {
  global $form_from_date, $form_to_date, $form_facids, $form_channel;

  $datefld = $reply ? "d4.field_value" : "d1.field_value";

  $query = "SELECT " .
    "f.pid, fe.facility_id, fe.date, $datefld AS encdate, " .
    "d2.field_value AS refer_related_code, " .
    "d3.field_value AS reply_related_code, " .
    "pd.regdate, pd.date AS last_update, pd.referral_source, pd.home_facility, " .
    "pd.sex, pd.DOB, pd.lname, pd.fname, pd.mname " .
    "FROM forms AS f " .
    "LEFT JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'refer_date' " .
    "LEFT JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'refer_related_code' " .
    "LEFT JOIN lbf_data AS d3 ON d3.form_id = f.form_id AND d3.field_id = 'reply_related_code' " .
    "LEFT JOIN lbf_data AS d4 ON d4.form_id = f.form_id AND d4.field_id = 'reply_date' " .
    "JOIN lbf_data AS d5 ON d5.form_id = f.form_id AND d5.field_id = 'refer_external' " .
    "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
    "JOIN patient_data AS pd ON pd.pid = f.pid " .
    "WHERE f.formdir = 'LBFref' AND f.deleted = 0 AND $datefld IS NOT NULL AND " .
    "$datefld >= '$form_from_date' AND $datefld <= '$form_to_date' AND d5.field_value = '2' " .
    "ORDER BY f.pid, f.id";
  // echo "<!-- $query -->\n"; // debugging

  $tres = sqlStatement($query);
  $last_pid = 0;
  while ($trow = sqlFetchArray($tres)) {
    $row_pid = $trow['pid'];
    $row_date = $trow['encdate'];
    $encounter_date = substr($row['date'], 0, 10);
    if (empty($trow['facility_id'])) $trow['facility_id'] = $trow['home_facility'];

    $erow = sqlQuery("SELECT f.id, f.domain_identifier, f.country_code, f.pos_code, f.name " .
      "FROM facility AS f WHERE f.id = ?",
      array($trow['facility_id']));

    $domain_identifier = empty($erow['domain_identifier']) ? '' : $erow['domain_identifier'];
    $country = empty($erow['country_code']) ? '' : $erow['country_code'];
    $channel = empty($erow['pos_code']) ? 0 : $erow['pos_code'];

    if ($domain_identifier === '') continue;
    if (!empty($form_facids) && !in_array($erow['id'], $form_facids)) continue;
    if ($form_channel && $form_channel != $channel) continue;

    // Record org unit name for reporting.
    noteOrgUnitName($domain_identifier, $erow['name']);

    if ($row_pid != $last_pid) {
      $sex = strtoupper(substr($trow['sex'], 0, 1)); // F or M
    }
    $coc = getCatCombo($sex, $trow['DOB'], $row_date);
    $period = getPeriod($row_date);

    $relcodes = explode(';', $reply ? $trow['reply_related_code'] : $trow['refer_related_code']);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);
      if ($codetype == 'REF') {
        // This is the expected case; a direct IPPF code is obsolete.
        $query = "SELECT related_code FROM codes WHERE " .
          "code_type = '16' AND code = '$code' AND active = 1 " .
          "ORDER BY id LIMIT 1";
        // echo "<!-- $query -->\n"; // debugging
        $rrow = sqlQuery($query);
        if (!empty($rrow['related_code'])) {
          // There can be both IPPF (obsolete) and IPPF2 related codes here.
          $relcodes2 = explode(';', $rrow['related_code']);
          foreach ($relcodes2 as $codestring2) {
            if ($codestring2 === '') continue;
            list($codetype2, $code2) = explode(':', $codestring2);
            if ($codetype2 !== 'IPPF2') continue;
            // echo "<!-- $code2 -->\n"; // debugging
            recordStats(
              getDataElement(($reply ? 'XRF' :'REF'), $code2, $country),
              $period,
              $domain_identifier,           // org unit
              $coc,                         // age and sex
              clientStatus($row_pid, $encounter_date, $code2),
              ''                            // complication
            );
          }
        }
      }
    }
    $last_pid = $row_pid;
  }
}

$outarr = array();

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-01'));
$form_to_date   = fixDate($_POST['form_to_date'  ], date('Y-m-d'));
$form_facids    = is_array($_POST['form_facids']) ? $_POST['form_facids'] : array();
$form_channel   = empty($_POST['form_channel']) ? '0' : $_POST['form_channel'];

if (!empty($_POST['form_submit'])) {

  // This selects all encounters in the date range and (optionally) with the selected facilities.
  $query = "SELECT " .
    "fe.pid, fe.encounter, fe.date, f.domain_identifier, f.country_code, f.name, " .
    "p.regdate, p.date AS last_update, p.DOB, p.sex " .
    "FROM form_encounter AS fe " .
    "JOIN facility AS f ON f.id = fe.facility_id AND f.domain_identifier != '' ";
  if (!empty($form_facids)) {
    $query .= "AND ( 0 ";
    foreach ($form_facids AS $facid) {
      $query .= "OR f.id = '" . add_escape_custom($facid) . "' ";
    }
    $query .= ") ";
  }
  if ($form_channel) {
    $query .= "AND f.pos_code = '" . add_escape_custom($form_channel) . "' ";
  }
  $query .=
    "JOIN patient_data AS p ON p.pid = fe.pid WHERE " .
    "fe.date >= '$form_from_date 00:00:00' AND " .
    "fe.date <= '$form_to_date 23:59:59' " .
    "ORDER BY fe.pid, fe.encounter";

  $res = sqlStatement($query);

  $last_pid = 0;

  // Loop on the selected visits.
  while ($row = sqlFetchArray($res)) {
    $row_pid = $row['pid'];
    $row_encounter = $row['encounter'];
    $encounter_date = substr($row['date'], 0, 10);
    $sex = strtoupper(substr($row['sex'], 0, 1)); // F or M
    // Category Option Combo and Period.
    $coc = getCatCombo($sex, $row['DOB'], $encounter_date);
    $period = getPeriod($encounter_date);
    // Record org unit name for reporting.
    noteOrgUnitName($row['domain_identifier'], $row['name']);

    // This queries the MA codes from which we'll get the related IPPF codes.
    $query = "SELECT b.code_type, b.code, b.code_text, b.units, b.fee, " .
      "b.justify, c.related_code " .
      "FROM billing AS b, codes AS c WHERE " .
      "b.pid = '$row_pid' AND b.encounter = '$row_encounter' AND " .
      "b.activity = 1 AND b.code_type = 'MA' AND " .
      "c.code_type = '12' AND c.code = b.code AND c.modifier = b.modifier";
    $bres = sqlStatement($query);

    while ($brow = sqlFetchArray($bres)) {
      if (!empty($brow['related_code'])) {
        $relcodes = explode(';', $brow['related_code']);
        foreach ($relcodes as $codestring) {
          if ($codestring === '') continue;
          list($codetype, $code) = explode(':', $codestring);
          if ($codetype !== 'IPPF2') continue;
          recordStats(
            getDataElement('SRV', $code, $row['country_code']),
            $period,
            $row['domain_identifier'],    // org unit
            $coc,                         // age and sex
            clientStatus($row_pid, $encounter_date, $code),
            '',                           // complication
            (empty($brow['units']) ? 1 : $brow['units'])
          );
        }
      }
    }

    $last_pid = $row_pid;
  }

  // Referrals and counter referrals.
  referralScan(false); // Generate REF rows.
  referralScan(false); // Generate XRF rows.

  // This enumerates instances of "contraception starting" for the MA.
  // The fetch loop will do most of the filtering of what we don't want.
  //
  $query = "SELECT " .
    "d1.field_value AS ippfconmeth, " .
    "fe.pid, fe.encounter, fe.date AS encdate, fe.date AS contrastart, fe.facility_id, " .
    "f.user AS provider, " .
    "pd.regdate, pd.sex, pd.DOB, pd.lname, pd.fname, pd.mname, " .
    "pd.referral_source, pd.home_facility " .
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
  while ($row = sqlFetchArray($res)) {
    $row_pid = $row['pid'];
    $row_encounter = $row['encounter'];
    $encounter_date = substr($row['encdate'], 0, 10);
    $contrastart = substr($row['contrastart'], 0, 10);

    $sex = strtoupper(substr($row['sex'], 0, 1)); // F or M
    // Category Option Combo and Period.
    $coc = getCatCombo($sex, $row['DOB'], $encounter_date);
    $period = getPeriod($encounter_date);

    $ippfconmeth = '';
    if (!empty($row['ippfconmeth']) && substr($row['ippfconmeth'], 0, 7) == 'IPPFCM:') {
      $ippfconmeth = substr($row['ippfconmeth'], 7);
    }

    // Make sure "acceptor new to modern contraception" happens only once per client
    // regardless of the year.  Note we are sorting by ascending date within pid,
    // so only the first occurrence per client will be counted.
    if ($row_pid == $lastpid) {
      continue;
    }
    $lastpid = $row_pid;
    // Skip if not in report date range.
    if ($contrastart < $form_from_date) {
      continue;
    }

    // TBD: End date?

    // Facility filtering.
    if (!empty($form_facids) && !in_array($row['facility_id'], $form_facids)) {
      continue;
    }

    // Get facility info.
    if (empty($row['facility_id'])) $row['facility_id'] = $row['home_facility'];
    $frow = sqlQuery("SELECT f.id, f.domain_identifier, f.country_code, f.pos_code, f.name " .
      "FROM facility AS f WHERE f.id = ?",
      array($row['facility_id']));
    $domain_identifier = empty($frow['domain_identifier']) ? '' : $frow['domain_identifier'];
    $country = empty($frow['country_code']) ? '' : $frow['country_code'];
    $channel = empty($frow['pos_code']) ? 0 : $frow['pos_code'];

    if ($domain_identifier === '') {
      continue;
    }
    // Skip if channel filtering does not match.
    if ($form_channel && $form_channel != $channel) {
      continue;
    }

    // Record org unit name for reporting.
    noteOrgUnitName($domain_identifier, $frow['name']);

    $code = postAbortionMethod($row_pid, $encounter_date, $ippfconmeth);
    if (!$code) continue;

    recordStats(
      getDataElement('PAC', $code, $country),
      $period,
      $domain_identifier,           // org unit
      $coc,                         // age and sex
      clientStatus($row_pid, $encounter_date, $code),
      ''                            // complication
    );
  } // end while

  // Generate the output text.
  ksort($outarr);
  $out =  '"dataelement",'          .
          '"period",'               .
          '"orgunit",'              .
          '"sexagecategorycombo",'  .
       // '"attributeoptioncombo",' .
          '"clientstatus",'         . // new
       // '"method",'               . // new
          '"complication",'         . // new
          '"units",'                .
          '"storedby",'             .
          '"lastupdated",'          .
       // '"comment",'              .
       // '"followup",'             .
          '"code description",'     .
          '"org unit name"'         .
          "\n";
  foreach ($outarr as $key => $value) {
    list($period, $orgunit, $dataelement, $sexagecategorycombo, $clientstatus, $complication) =
      explode('|', $key);
    $out .= "$dataelement,$period,$orgunit,$sexagecategorycombo," .
      "$clientstatus,$complication," .
      "$value," . $_SESSION['authUser'] . "," .
      date('Y-m-d') .
      ',"' . addslashes($arr_code_desc[$dataelement]) . '"' .
      ',"' . addslashes($arr_org_unit[$orgunit]) . '"' .
      "\n";
  }

  // This is the "filename" for the Content-Disposition header.
  $filename = 'ippf_indicators_export.csv';

  // Do compression if requested.
  if (!empty($_POST['form_compress'])) {
    $zip = new ZipArchive();
    $zipname = tempnam($GLOBALS['temporary_files_dir'], 'OEZ');
    if ($zipname === FALSE) {
      die("tempnam('" . $GLOBALS['temporary_files_dir'] . "','OEZ') failed.\n");
    }
    if ($zip->open($zipname, ZIPARCHIVE::OVERWRITE) !== TRUE) {
      die(xl('Cannot create file') . " '$zipname'\n");
    }
    $zip->addFromString($filename, $out);
    $zip->close();
    $out = file_get_contents($zipname);
    unlink($zipname);
    $filename .= '.zip';
  }

  // Do encryption if requested.
  if (!empty($_POST['form_encrypt'])) {
    $filename .= '.aes';
    // This requires PHP 5.3.0 or later.  The 5th (iv) parameter is not supported until
    // PHP 5.3.3, so we specify ECB which does not use it.
    $key = '';
    // Key must be 32 bytes.  Truncation or '0'-padding otherwise occurs.
    if (!empty($GLOBALS['gbl_encryption_key'])) {
      // pack('H*') converts hex to binary.
      $key = substr(pack('H*', $GLOBALS['gbl_encryption_key']), 0, 32);
    }
    while (strlen($key) < 32) $key .= '0';
    //
    $method = 'aes-256-ecb'; // aes-256-cbc requires iv
    $out = openssl_encrypt($out, $method, $key, true);
    //
    // To decrypt at the command line specify the 32-byte key in hex, for example:
    // openssl aes-256-ecb -d -in export.xml.aes -K 3132333435363738313233343536373831323334353637383132333435363738
    //
  }

  if ($last_pid >= 0) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Length: " . strlen($out));
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Description: File Transfer");
    echo $out;
    exit(0);
  }
  else {
    // Whoops, there's no matching data.
    $alertmsg = xl("There is no data matching this period and SDP.");
  }
}

?>
<html>

<head>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>
<title><?php xl('Backup','e'); ?></title>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="JavaScript">

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

function channel_changed() {
 var f = document.forms[0];
 var chan = parseInt(f.form_channel.value);
 // alert('Channel is "' + chan + '".'); // debugging
 var optarr = f['form_facids[]'].options;
 for (var i = 0; i < optarr.length; ++i) {
  optarr[i].disabled = (chan != 0 && parseInt(optarr[i].id.substring(4)) != chan);
 }
}

</script>
</head>

<body class="body_top">
<center>
&nbsp;<br />
<form method='post' action='ippf_indicators_export.php'>

<h2><?php echo xlt('Export GCACI and CCSPT Statistics'); ?></h2>

<table>
 <tr>
  <td align='center' colspan='3'>

   <?php echo xlt('From'); ?>:
   <input type='text' name='form_from_date' id='form_from_date' size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Start date yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer;vertical-align:middle;'
    title='<?php xl('Click here to choose a date','e'); ?>'>

   &nbsp;<?php echo xlt('To'); ?>:
   <input type='text' name='form_to_date' id='form_to_date' size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='End date yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer;vertical-align:middle;'
    title='<?php xl('Click here to choose a date','e'); ?>'>

   &nbsp;
   <input type='checkbox' name='form_compress'
    title='<?php echo xl('To compress in ZIP archive format'); ?>'
    /><?php echo xl('Compress'); ?>

<?php if (function_exists('openssl_encrypt') && !empty($GLOBALS['gbl_encryption_key'])) { ?>
   &nbsp;
   <input type='checkbox' name='form_encrypt'
    title='<?php echo xl('If AES encryption is desired'); ?>'
    /><?php echo xl('Encypt'); ?>
<?php } ?>

  </td>
 </tr>
 <tr>

<?php
echo "  <td align='center' valign='top'>\n";
echo "   " . xlt('Channel') . ":\n";
echo "   <select name='form_channel' onchange='channel_changed()'>\n";
echo "    <option value='0'>-- " . xlt('All') . " --</option>\n";
$lres = sqlStatement("SELECT option_id, title FROM list_options WHERE " .
  "list_id = 'posref' ORDER BY seq, option_id, title");
while ($lrow = sqlFetchArray($lres)) {
  $key = $lrow['option_id'];
  echo "    <option value='" . attr($key) . "'";
  if ($key == $form_channel) echo " selected";
  echo ">" . text($lrow['option_id'] . ': ' . xl_list_label($lrow['title'])) . "</option>\n";
}
echo "   </select>\n";
echo "   &nbsp;" . xlt('SDP ID') . ":\n";
echo "  </td>\n";

echo "  <td align='center' valign='top'>\n";
echo "   <select multiple name='form_facids[]' size='20' title='" . xla('Default is all') . "'>\n";
$fres = sqlStatement("SELECT id, domain_identifier, name, pos_code FROM facility " .
  "ORDER BY domain_identifier, name, id");
while ($frow = sqlFetchArray($fres)) {
  $facid = intval($frow['id']);
  $sdpid = trim($frow['domain_identifier']);
  $channel = intval($frow['pos_code']);
  if (strlen($sdpid) == 0) continue;
  // id identifies the channel so javascript can hide options based on this.
  echo "    <option value='$facid' id='cod_$channel'";
  if (in_array($facid, $form_facids)) echo " selected";
  echo ">" . text($sdpid . ' (' . $frow['name'] . ')') . "</option>\n";
}
echo "   </select>\n\n";
echo "  </td>\n";

echo "  <td align='center' valign='top'>\n";
echo "   &nbsp;\n";
echo "   <input type='submit' name='form_submit' value='Generate' />\n";
echo "  </td>\n";
?>

 </tr>
</table>

</form>

</center>

<script language="JavaScript">
Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
<?php
  if ($alertmsg) {
    echo "alert('" . htmlentities($alertmsg) . "');\n";
  }
?>
channel_changed();
</script>

</body>
</html>
