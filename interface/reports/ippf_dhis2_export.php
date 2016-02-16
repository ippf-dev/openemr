<?php
// Copyright (C) 2015-2016 Rod Roark <rod@sunsetsystems.com>
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

function recordStats($dataelement, $period, $orgunit, $categoryoptioncombo, $attributeoptioncombo, $quantity=1) {
  global $outarr;
  $key = "$period|$orgunit|$dataelement|$categoryoptioncombo|$attributeoptioncombo";
  if (!isset($outarr[$key])) $outarr[$key] = 0;
  if (empty($quantity)) $quantity = 1;
  $outarr[$key] += $quantity;
}

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

// Compute value to report for age and sex combination. Note age is relative to the visit.
//
function getCatCombo($sex, $dob, $asofdate) {
  $age = getPatientAge($dob, str_replace('-', '', $asofdate));
  if (empty($dob)) $age = 999;
  $coc = '';
  /********************************************************************
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
  ********************************************************************/
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
  /*******************************************************************/
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

$outarr = array();

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-01'));
$form_to_date   = fixDate($_POST['form_to_date'  ], date('Y-m-d'));
$form_facids    = is_array($_POST['form_facids']) ? $_POST['form_facids'] : array();
$form_channel   = empty($_POST['form_channel']) ? '0' : $_POST['form_channel'];

if (!empty($_POST['form_submit'])) {

  // This selects all encounters in the date range and (optionally) with the selected facilities.
  $query = "SELECT " .
    "fe.pid, fe.encounter, fe.date, f.domain_identifier, f.country_code, " .
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

    if ($row_pid != $last_pid) {
      // Get New Acceptor date, method and encounter ID for this client.
      $nainfo = getNewAcceptorInfo($row_pid);
    }
    // If this is the New User visit, record it.
    if ($nainfo[2] && $nainfo[2] == $row_encounter) {
      $code = method_to_ippf2_code($nainfo[1]);
      recordStats(
        getDataElement('NEW', $code, $row['country_code']),
        $period,
        $row['domain_identifier'],    // org unit
        $coc,                         // age and sex
        'X66r2y4EuwS'
      );
    }

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
            'X66r2y4EuwS',
            (empty($brow['units']) ? 1 : $brow['units'])
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
              // 'X66r2y4EuwS',             // See JG 2015-10-30 email for this request.
              '',                           // See JG 2016-02-15 email for this request.
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
    "p.regdate, p.date AS last_update, p.DOB, p.sex, p.home_facility " .
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
    $erow = sqlQuery("SELECT f.id, f.domain_identifier, f.country_code, f.pos_code " .
      "FROM form_encounter AS fe " .
      "JOIN facility AS f ON f.id = fe.facility_id " .
      "WHERE fe.pid = ? AND fe.date <= ? " .
      "ORDER BY fe.date DESC, fe.encounter DESC LIMIT 1",
      array($row_pid, "$row_date 23:59:59"));

    if (empty($erow)) {
      // No encounter found so default to home facility.
      $erow = sqlQuery("SELECT f.id, f.domain_identifier, f.country_code, f.pos_code " .
        "FROM facility AS f WHERE f.id = ?",
        array($trow['home_facility']));
    }

    $domain_identifier = empty($erow['domain_identifier']) ? '' : $erow['domain_identifier'];
    $country = empty($erow['country_code']) ? '' : $erow['country_code'];
    $channel = empty($erow['pos_code']) ? 0 : $erow['pos_code'];

    if ($domain_identifier === '') continue;
    if (!empty($form_facids) && !in_array($erow['id'], $form_facids)) continue;
    if ($form_channel && $form_channel != $channel) continue;

    if ($row_pid != $last_pid) {
      $sex = strtoupper(substr($trow['sex'], 0, 1)); // F or M
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
          // There can be both IPPF (obsolete) and IPPF2 related codes here.
          $relcodes2 = explode(';', $rrow['related_code']);
          foreach ($relcodes2 as $codestring2) {
            if ($codestring2 === '') continue;
            list($codetype, $code) = explode(':', $codestring2);
            if ($codetype !== 'IPPF2') continue;
            recordStats(
              getDataElement('REF', $code, $country),
              $period,
              $domain_identifier,           // org unit
              $coc,                         // age and sex
              'X66r2y4EuwS'
            );
          }
        }
      }
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
<form method='post' action='ippf_dhis2_export.php'>

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
