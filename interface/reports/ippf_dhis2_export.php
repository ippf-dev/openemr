<?php
// Copyright (C) 2015 Rod Roark <rod@sunsetsystems.com>
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

if (!acl_check('admin', 'super')) die("Not authorized!");

$alertmsg = '';

// Utility function to get the value for a specified key from a string
// whose format is key:value|key:value|...
//
function getTextListValue($string, $key) {
  $tmp = explode('|', $string);
  foreach ($tmp as $value) {
    if (preg_match('/^(\w+?):(.*)$/', $value, $matches)) {
      if ($matches[1] == $key) return $matches[2];
    }
  }
  return '';
}

// Return the mapped list item ID if there is one, else the option_id.
// Or return 9 if the option_id is empty (unspecified).
//
function mappedOption($list_id, $option_id, $default='9') {
  if ($option_id === '') return $default;
  $row = sqlQuery("SELECT mapping FROM list_options WHERE " .
    "list_id = '$list_id' AND option_id = '$option_id' LIMIT 1");
  if (empty($row)) return $option_id; // should not happen
  // return ($row['mapping'] === '') ? $option_id : $row['mapping'];
  $maparr = explode(':', $row['mapping']);
  return ($maparr[0] === '') ? $option_id : $maparr[0];
}

// Like the above but given a layout item form and field name.
// Or return 9 for a list whose id is empty (unspecified).
//
function mappedFieldOption($form_id, $field_id, $option_id) {
  $row = sqlQuery("SELECT list_id FROM " .
    "layout_options WHERE " .
    "form_id = '$form_id' AND " .
    "field_id = '$field_id' " .
    "LIMIT 1");
  if (empty($row)) return $option_id; // should not happen
  $list_id = $row['list_id'];
  if ($list_id === '') return $option_id;
  if ($option_id === '') return '9';
  $row = sqlQuery("SELECT mapping FROM " .
    "list_options WHERE " .
    "list_id = '$list_id' AND " .
    "option_id = '$option_id' " .
    "LIMIT 1");
  if (empty($row)) return $option_id; // should not happen
  // return ($row['mapping'] === '') ? $option_id : $row['mapping'];
  $maparr = explode(':', $row['mapping']);
  return ($maparr[0] === '') ? $option_id : $maparr[0];
}

function recordStats($dataelement, $period, $orgunit, $categoryoptioncombo, $attributeoptioncombo, $quantity=1) {
  global $outarr;
  $key = "$period|$orgunit|$dataelement|$categoryoptioncombo|$attributeoptioncombo";
  if (!isset($outarr[$key])) $outarr[$key] = 0;
  if (empty($quantity)) $quantity = 1;
  $outarr[$key] += $quantity;
}

// Get the specified patient's new acceptor date.
//
function getNewAcceptorDate($pid) {
  $query = "SELECT " .
    "fe.date AS contrastart, d1.field_value AS contrameth " .
    "FROM forms AS f " .
    "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
    "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' " .
    "LEFT JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'pastmodern' " .
    "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 AND f.pid = ? AND " .
    "(d1.field_value LIKE 'IPPFCM:%' AND (d2.field_value IS NULL OR d2.field_value = '0')) " .
    "ORDER BY contrastart LIMIT 1";
  $contradate_row = sqlQuery($query, array($pid));
  return substr($contradate_row['contrastart'], 0, 10);
}

// Get the current contraceptive method. This is not necessarily the method
// on the start date.
//
function getCurrentMethod($pid) {
  $query = "SELECT " .
    "fe.date AS contrastart, d1.field_value AS contrameth " .
    "FROM forms AS f " .
    "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
    "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' " .
    "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 AND f.pid = ? " .
    "ORDER BY contrastart DESC LIMIT 1";
  $contrameth_row = sqlQuery($query, array($pid));
  return empty($contrameth_row['contrameth']) ? '' : substr($contrameth_row['contrameth'], 7);
}

// Compute value to report for age and sex combination. Note age is relative to the visit.
//
function getCatCombo($sex, $dob, $asofdate) {
  $age = getPatientAge($dob, str_replace('-', '', $asofdate));
  if (empty($dob)) $age = 999;
  $coc = '';
  if      ($age <  6) $coc = $sex == 'M' ? 'FarKVV1ZD3V' : ($sex == 'F' ? 'tKsuEPYTRZL' : 'iRnq68mGW36');
  else if ($age < 11) $coc = $sex == 'M' ? 'jX0XhbOJDBA' : ($sex == 'F' ? 'qNr2sWtzAIh' : 'M467KejTGpY');
  else if ($age < 15) $coc = $sex == 'M' ? 'Wfhzt104TIg' : ($sex == 'F' ? 'vADYdyGrC50' : 'avlAHamZDo7');
  else if ($age < 20) $coc = $sex == 'M' ? 'HCSWJmuqFN0' : ($sex == 'F' ? 'L7p7X2PoEtj' : 'xOPRWHn2tJj');
  else if ($age < 25) $coc = $sex == 'M' ? 'ZbTOS44jDUw' : ($sex == 'F' ? 'rdZJ5c8QrIt' : 'R34h1nwqsfZ');
  else if ($age < 30) $coc = $sex == 'M' ? 'fVc9DEUr2Lj' : ($sex == 'F' ? 'hD5yqrFNUBC' : 'rr7Sn5rZQy6');
  else if ($age < 35) $coc = $sex == 'M' ? 'q1ZKPa39q8G' : ($sex == 'F' ? 'lh6ozNT9NTm' : 'FVsnXr9oMdu');
  else if ($age < 40) $coc = $sex == 'M' ? 'ryK5rlxHTqj' : ($sex == 'F' ? 'QXagFrSmLeS' : 'NnRAdbtPH01');
  else if ($age < 45) $coc = $sex == 'M' ? 'Sb8vSolIwNO' : ($sex == 'F' ? 'euO866oyyvR' : 'rpFcLQ4jcm3');
  else if ($age < 50) $coc = $sex == 'M' ? 'e0wl11kYHkk' : ($sex == 'F' ? 'qmHvuvsUH65' : 'Ty35QB5scY9');
  else                $coc = $sex == 'M' ? 'Qaly3cXcMTt' : ($sex == 'F' ? 'NZTCw0MlTnq' : 'mRMuOEwCUw1');
  return $coc;
}

// Period calculation. There are 6 different formats depending on the date range.
//
function getPeriod($encounter_date) {
  /********************************************************************
  global $form_from_date, $form_to_date;
  $from_date_arr  = getdate(strtotime($form_from_date));
  $to_date_arr    = getdate(strtotime($form_to_date  ));
  $to_date_p1_arr = getdate(strtotime($form_to_date  ) + 86400 + 3600); // day after to date
  $period = substr($encounter_date, 0, 4);
  if ($from_date_arr['year'] == $to_date_arr['year']) {
    if (substr($form_from_date, 5, 5) == '01-01' && substr($form_to_date, 5, 5) == '12-31') {
      // Period is year.
    }
    else if (substr($form_from_date, 5, 5) == '01-01' && substr($form_to_date, 5, 5) == '06-30') {
      // Period is six-month.
      $period .= 'S1';
    }
    else if (substr($form_from_date, 5, 5) == '07-01' && substr($form_to_date, 5, 5) == '12-31') {
      $period .= 'S2';
    }
    else if (substr($form_from_date, 5, 5) == '01-01' && substr($form_to_date, 5, 5) == '03-31') {
      // Period is first quarter.
      $period .= 'Q1';
    }
    else if (substr($form_from_date, 5, 5) == '04-01' && substr($form_to_date, 5, 5) == '06-30') {
      $period .= 'Q2';
    }
    else if (substr($form_from_date, 5, 5) == '07-01' && substr($form_to_date, 5, 5) == '09-30') {
      $period .= 'Q3';
    }
    else if (substr($form_from_date, 5, 5) == '10-01' && substr($form_to_date, 5, 5) == '12-31') {
      $period .= 'Q4';
    }
    else if (
      $from_date_arr['mon'] == $to_date_arr['mon'] &&
      $from_date_arr['mday'] == 1 &&
      $to_date_p1_arr['mday'] == 1
    ) {
      // Period is month.
      $period .= substr($encounter_date, 5, 2);
    }
    else if (
      ($from_date_arr['yday'] % 7) == 0 &&
      (
        ($from_date_arr['yday'] + 6) == $to_date_arr['yday'] ||
        (
          substr($form_to_date, 5, 5) == '12-31' &&
          ($to_date_arr['yday'] - $from_date_arr['yday']) < 7
        )
      )
    ) {
      // Period is week.
      // Must be 7 days starting on the same day of week that January 1 started.
      $period .= 'W' . intval($from_date_arr['yday'] / 7 + 1);
    }
    else {
      // For everything else use the encounter date.
      $period .= substr($encounter_date, 5, 2) . substr($encounter_date, 8, 2);
    }
  }
  else {
    $period .= substr($encounter_date, 5, 2) . substr($encounter_date, 8, 2);
  }
  ********************************************************************/

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
  return $de;
}

$outarr = array();

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-01'));
$form_to_date   = fixDate($_POST['form_to_date'  ], date('Y-m-d'));
$form_sdp       = empty($_POST['form_sdp']) ? '' : $_POST['form_sdp'];

if (!empty($_POST['form_submit'])) {
  $query = "SELECT id AS facility_id, name, street, city AS fac_city, " .
    "state AS fac_state, postal_code, country_code, federal_ein, " .
    "domain_identifier, pos_code, latitude, longitude FROM facility ";
  if ($form_sdp !== '') $query .=
    "WHERE domain_identifier = '" . add_escape_custom($form_sdp) . "' ";
  $query .=
    "ORDER BY billing_location DESC, id ASC LIMIT 1";
  $facrow = sqlQuery($query);

  // This selects all encounters in the date range and (optionally) with the selected facilities.
  $query = "SELECT " .
    "fe.pid, fe.encounter, fe.date, f.domain_identifier, f.country_code, " .
    "p.regdate, p.date AS last_update, p.DOB, p.sex " .
    "FROM form_encounter AS fe " .
    "JOIN facility AS f ON f.id = fe.facility_id ";
  if ($form_sdp !== '') $query .=
    "AND f.domain_identifier = '" . add_escape_custom($form_sdp) . "' ";
  $query .=
    "JOIN patient_data AS p ON p.pid = fe.pid WHERE " .
    "fe.date >= '$form_from_date 00:00:00' AND " .
    "fe.date <= '$form_to_date 23:59:59' " .
    "ORDER BY fe.pid, fe.encounter";

  // echo "<!-- $query -->\n"; // debugging

  $res = sqlStatement($query);

  $last_pid = 0;

  // Loop on the selected visits.
  while ($row = sqlFetchArray($res)) {
    $row_pid = $row['pid'];
    $row_encounter = $row['encounter'];
    $encounter_date = substr($row['date'], 0, 10);

    if ($row_pid != $last_pid) {
      $sex = strtoupper(substr($row['sex'], 0, 1)); // F or M
      // Get New Acceptor date and current method.
      $new_acceptor_date = getNewAcceptorDate($row_pid);
      $methodid = getCurrentMethod($row_pid);
    }

    // Category Option Combo and Period.
    $coc = getCatCombo($sex, $row['DOB'], $encounter_date);
    $period = getPeriod($encounter_date);

    // This queries the MA codes from which we'll get the related IPPF codes.
    $query = "SELECT b.code_type, b.code, b.code_text, b.units, b.fee, " .
      "b.justify, c.related_code " .
      "FROM billing AS b, codes AS c WHERE " .
      "b.pid = '$row_pid' AND b.encounter = '$row_encounter' AND " .
      "b.activity = 1 AND b.code_type = 'MA' AND " .
      "c.code_type = '12' AND c.code = b.code AND c.modifier = b.modifier";
    $bres = sqlStatement($query);
    // echo "<!-- $query -->\n"; // debugging
    while ($brow = sqlFetchArray($bres)) {
      if (!empty($brow['related_code'])) {
        $relcodes = explode(';', $brow['related_code']);
        foreach ($relcodes as $codestring) {
          if ($codestring === '') continue;
          list($codetype, $code) = explode(':', $codestring);
          if ($codetype !== 'IPPF2') continue;
          $prefix = $new_acceptor_date == $encounter_date ? 'NEW' : 'SRV';
          recordStats(
            getDataElement($prefix, $code, $row['country_code']),
            $period,
            $row['domain_identifier'],    // org unit
            $coc,                         // age and sex
            'X66r2y4EuwS'
          );
        }
      }
    }

    // Similarly for products.
    $query = "SELECT s.quantity, d.related_code " .
      "FROM drug_sales AS s " .
      "JOIN drugs AS d ON d.drug_id = s.drug_id " .
      "WHERE s.pid = ? AND s.encounter = ? " .
      "ORDER BY s.drug_id, s.sale_id";
    $pres = sqlStatement($query, array($row_pid, $row_encounter));
    while ($prow = sqlFetchArray($pres)) {
      if (!empty($prow['related_code'])) {
        $relcodes = explode(';', $prow['related_code']);
        foreach ($relcodes as $codestring) {
          if ($codestring === '') continue;
          list($codetype, $code) = explode(':', $codestring);
          if ($codetype !== 'IPPFCM') continue;
          $delt = '';
          if ('4360' == $code) $delt = 'IT101'; else // Oral contraceptives (Combined)
          if ('4361' == $code) $delt = 'IT102'; else // Oral contraceptives (progestogen only)
          if ('????' == $code) $delt = 'IT103'; else // Oral contraceptives (Unable to Categorise)
          if ('4370' == $code) $delt = 'IT151'; else // Injectables (1 month)
          if ('4380' == $code) $delt = 'IT152'; else // Injectables (2 month)
          if ('4390' == $code) $delt = 'IT153'; else // Injectables (3 month)
          if ('4400' == $code) $delt = 'IT201'; else // Implant (3 year)
          if ('4410' == $code) $delt = 'IT202'; else // Implant (4 year)
          if ('4420' == $code) $delt = 'IT203'; else // Implant (5 year)
          if ('4540' == $code) $delt = 'IT251'; else // IUD (5 year)
          if ('4550' == $code) $delt = 'IT255'; else // IUD (10 year)
          if ('4430' == $code) $delt = 'IT301'; else // Patch
          if ('4440' == $code) $delt = 'IT351'; else // Ring
          if ('4470' == $code) $delt = 'IT401'; else // Diaphragm
          if ('4480' == $code) $delt = 'IT451'; else // Cervical cap
          if ('4490' == $code) $delt = 'IT501'; else // Spermicides
          if ('4450' == $code) $delt = 'IT551'; else // Condoms (male)
          if ('4460' == $code) $delt = 'IT552'; else // Condoms (female)
          if ('4620' == $code) $delt = 'IT601'; else // EC (progestogen only pills)
          if ('????' == $code) $delt = 'IT602'; else // EC (combined pills - Yuzpe)
          if ('????' == $code) $delt = 'IT603';      // EC (10 year IUD)
          if ($delt) {
            recordStats(
              $delt,
              $period,
              $row['domain_identifier'],    // org unit
              'X66r2y4EuwS',                // See JG 2015-10-30 email for this request.
              'X66r2y4EuwS',
              $prow['quantity']
            );
          }
        }
      }
    }

    $last_pid = $row_pid;
  }

  // Now do all outbound external referrals in the date range.
  $query = "SELECT t.pid, t.refer_date, t.refer_related_code, " .
    "p.regdate, p.date AS last_update, p.DOB, p.sex " .
    "FROM transactions AS t " .
    "JOIN patient_data AS p ON p.pid = t.pid WHERE " .
    "t.refer_date >= '$form_from_date' AND " .
    "t.refer_date <= '$form_to_date' AND " .
    "t.refer_related_code != '' AND t.refer_external = 2 " .
    "ORDER BY t.pid, t.id";
  $tres = sqlStatement($query);
  $last_pid = 0;
  while ($trow = sqlFetchArray($tres)) {
    $row_pid = $trow['pid'];
    $row_date = $trow['refer_date'];
    $erow = sqlQuery("SELECT f.domain_identifier, f.country_code " .
      "FROM form_encounter AS fe " .
      "JOIN facility AS f ON f.id = fe.facility_id " .
      "WHERE fe.pid = ? AND fe.date <= ? " .
      "ORDER BY fe.date DESC, fe.encounter DESC LIMIT 1",
      array($row_pid, "$row_date 23:59:59"));
    $domain_identifier = empty($erow['domain_identifier']) ? '' : $erow['domain_identifier'];
    $country = empty($erow['country_code']) ? '' : $erow['country_code'];

    if ($form_sdp !== '' && $form_sdp != $domain_identifier) continue;

    if ($row_pid != $last_pid) {
      $sex = strtoupper(substr($trow['sex'], 0, 1)); // F or M
      $new_acceptor_date = getNewAcceptorDate($row_pid);
      $methodid = getCurrentMethod($row_pid);
    }
    $coc = getCatCombo($sex, $trow['DOB'], $row_date);
    $period = getPeriod($row_date);

    $relcodes = explode(';', $trow['refer_related_code']);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);
      if ($codetype == 'REF') {
        // This is the expected case; a direct IPPF code is obsolete.
        $rrow = sqlQuery("SELECT related_code FROM codes WHERE " .
          "code_type = '16' AND code = '$code' AND active = 1 " .
          "ORDER BY id LIMIT 1");
        if (!empty($rrow['related_code'])) {
          list($codetype, $code) = explode(':', $rrow['related_code']);
        }
      }
      if ($codetype !== 'IPPF2') continue;
      recordStats(
        getDataElement('REF', $code, $country),
        $period,
        $domain_identifier,           // org unit
        $coc,                         // age and sex
        'X66r2y4EuwS'
      );
    }

    $last_pid = $row_pid;
  } // end referral

  // Generate the output text.
  ksort($outarr);
  $out = '';
  foreach ($outarr as $key => $value) {
    list($period, $orgunit, $dataelement, $categoryoptioncombo, $attributeoptioncombo) =
      explode('|', $key);
    $out .= "$dataelement,$period,$orgunit,$categoryoptioncombo," .
      "$attributeoptioncombo,$value," . $_SESSION['authUser'] . "," .
      date('Y-m-d') . ",,FALSE\n";
  }

  // This is the "filename" for the Content-Disposition header.
  $filename = 'ippf_dhis2_export.csv';

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
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
</head>

<body class="body_top">
<center>
&nbsp;<br />
<form method='post' action='ippf_dhis2_export.php'>

<table style='width:95%'>
 <tr>
  <td align='center'>

   <?php  xl('From','e'); ?>:
   <input type='text' name='form_from_date' id='form_from_date' size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Start date yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>

   &nbsp;<?php  xl('To','e'); ?>:
   <input type='text' name='form_to_date' id='form_to_date' size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='End date yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>

  </td>
 </tr>
 <tr>
  <td align='center'>

   <?php echo xl('SDP ID'); ?>:
   <select name='form_sdp'>
<?php
echo "    <option value=''>-- " . xlt('All') . " --</option>\n";
$fres = sqlStatement("SELECT DISTINCT domain_identifier FROM facility ORDER BY domain_identifier");
while ($frow = sqlFetchArray($fres)) {
  $sdpid = trim($frow['domain_identifier']);
  /********************************************************************
  if (strlen($sdpid) < 1 || strspn($sdpid, '0123456789-') < strlen($sdpid)) {
    $alertmsg = xl('ERROR') . ': ' . xl('One or more SDP IDs are empty or contain invalid characters');
  }
  ********************************************************************/
  if (strlen($sdpid) == 0) continue;
  echo "    <option value='$sdpid'";
  if ($sdpid == $form_sdpid) echo " selected";
  echo ">$sdpid</option>\n";
}
?>
   </select>
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

   &nbsp;
   <input type='submit' name='form_submit' value='Generate' />
  </td>
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
</script>

</body>
</html>
