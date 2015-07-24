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

/**********************************************************************

//////////////////////////////////////////////////////////////////////
//                            XML Stuff                             //
//////////////////////////////////////////////////////////////////////

$out = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
$indent = 0;

// Add a string to output with some basic sanitizing.
function Add($tag, $text) {
  global $out, $indent;
  $text = trim(str_replace(array("\r", "\n", "\t"), " ", $text));
  $text = substr(htmlspecialchars($text, ENT_NOQUOTES), 0, 50);
  if (true) {
    if ($text === 'NULL') $text = '';
    for ($i = 0; $i < $indent; ++$i) $out .= "\t";
    $out .= "<$tag>$text</$tag>\n";
  }
}

function AddIfPresent($tag, $text) {
  if (isset($text) && $text !== '') Add($tag, $text);
}

function OpenTag($tag) {
  global $out, $indent;
  for ($i = 0; $i < $indent; ++$i) $out .= "\t";
  ++$indent;
  $out .= "<$tag>\n";
}

function CloseTag($tag) {
  global $out, $indent;
  --$indent;
  for ($i = 0; $i < $indent; ++$i) $out .= "\t";
  $out .= "</$tag>\n";
}

// Remove all non-digits from a string.
function Digits($field) {
  return preg_replace("/\D/", "", $field);
}

// Translate sex.
function Sex($field) {
  return mappedOption('sex', $field);
}

// Translate a date.
function LWDate($field) {
  return fixDate($field);
}

function xmlTime($str, $default='9999-12-31T23:59:59') {
  if (empty($default)) $default = '1800-01-01T00:00:00';
  if (strlen($str) < 10 || substr($str, 0, 4) == '0000')
    $str = $default;
  else if (strlen($str) > 10)
    $str = substr($str, 0, 10) . 'T' . substr($str, 11);
  else
    $str .= 'T00:00:00';
  // Per discussion with Daniel 2009-05-12, replace zero day or month with 01.
  $str = preg_replace('/-00/', '-01', $str);
  return $str;
}

//////////////////////////////////////////////////////////////////////

**********************************************************************/

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

function exportEncounter($pid, $encounter, $date) {
  // Starting a new visit (encounter).

  // Dump products.
  $query = "SELECT drug_id, quantity, fee FROM drug_sales WHERE " .
    "pid = '$pid' AND encounter = '$encounter' " .
    "ORDER BY drug_id, sale_id";
  $pres = sqlStatement($query);
  while ($prow = sqlFetchArray($pres)) {

    /******************************************************************
    OpenTag('IMS_eMRUpload_Service');
    Add('IppfServiceProductId', $prow['drug_id']);
    Add('Type'                , '1'); // 0=service, 1=product, 2=diagnosis, 3=referral
    Add('IppfQuantity'        , $prow['quantity']);
    Add('CurrID'              , "TBD"); // TBD: Currency e.g. USD
    Add('Amount'              , $prow['fee']);
    CloseTag('IMS_eMRUpload_Service');
    ******************************************************************/

  } // end while drug_sales row found

  // Export referrals.  Match by date.  Export code type 3 and
  // the Requested Service which should be an IPPF2 code.
  // Ignore inbound referrals (refer_external = 3 and 4) because the
  // services for those will appear in the tally sheet.
  $query = "SELECT refer_related_code FROM transactions WHERE " .
    "pid = '$pid' AND refer_date = '$date' AND " .
    "refer_related_code != '' AND refer_external < 4 " .
    "ORDER BY id";
  $tres = sqlStatement($query);
  while ($trow = sqlFetchArray($tres)) {
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

      /****************************************************************
      OpenTag('IMS_eMRUpload_Service');
      Add('IppfServiceProductId', $code);
      Add('Type'                , '3'); // 0=service, 1=product, 2=diagnosis, 3=referral
      Add('IppfQuantity'        , '1');
      Add('CurrID'              , "TBD"); // TBD: Currency e.g. USD
      Add('Amount'              , '0');
      CloseTag('IMS_eMRUpload_Service');
      ****************************************************************/

    } // end foreach
  } // end referral
}

function recordStats($dataelement, $period, $orgunit, $categoryoptioncombo, $attributeoptioncombo) {
  global $outarr;
  $key = "$period|$orgunit|$dataelement|$categoryoptioncombo|$attributeoptioncombo";
  if (!isset($outarr[$key])) $outarr[$key] = 0;
  ++$outarr[$key];
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
    "fe.pid, fe.encounter, fe.date, f.domain_identifier, " .
    "p.regdate, p.date AS last_update, p.DOB, p.sex, " .
    "p.city, p.state, p.occupation, p.status, p.ethnoracial, " .
    "p.interpretter, p.monthly_income, p.referral_source, p.pricelevel, " .
    "p.userlist1, p.userlist3, p.userlist4, p.userlist5, " .
    "p.usertext11, p.usertext12, p.usertext13, p.usertext14, p.usertext15, " .
    "p.usertext16, p.usertext17, p.usertext18, p.usertext19, p.usertext20, " .
    "p.userlist2 AS education " .
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
      $education = mappedOption('userlist2', $row['education']);
      $sex = strtoupper(substr($row['sex'], 0, 1)); // F or M

      // Get New Acceptor date, and also the method in case someone wants it later.
      $query = "SELECT " .
        "fe.date AS contrastart, d1.field_value AS contrameth " .
        "FROM forms AS f " .
        "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
        "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' " .
        "LEFT JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'pastmodern' " .
        "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 AND f.pid = '$row_pid' AND " .
        "(d1.field_value LIKE 'IPPFCM:%' AND (d2.field_value IS NULL OR d2.field_value = '0')) " .
        "ORDER BY contrastart LIMIT 1";
      $contradate_row = sqlQuery($query);
      $new_acceptor_date = substr($contradate_row['contrastart'], 0, 10);

      // Get the current contraceptive method. This is not necessarily the method
      // on the start date.
      $query = "SELECT " .
        "fe.date AS contrastart, d1.field_value AS contrameth " .
        "FROM forms AS f " .
        "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
        "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' " .
        "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 AND f.pid = '$last_pid' " .
        "ORDER BY contrastart DESC LIMIT 1";
      $contrameth_row = sqlQuery($query);
      $methodid = empty($contrameth_row['contrameth']) ? '' : substr($contrameth_row['contrameth'], 7);
    }

    $age = getPatientAge($row['DOB'], str_replace('-', '', $encounter_date));
    if (empty($row['DOB'])) $age = 999;

    // Compute value to report for age and sex combination. Note age is relative to the visit.
    if ($sex == 'F') {
      $categoryoptioncombo = $age < 25 ? 'EgcWdId977l' : 'EzKeiXz7heQ';
    }
    else if ($sex == 'M') {
      $categoryoptioncombo = $age < 25 ? 'xCJzywucCyK' : 'RJRiYZUpekm';
    }
    else {
      $categoryoptioncombo = $age < 25 ? 'Y2c16vWPBox' : 'CrE6h9KphAO';
    }

    // Set period to YYYY if this is one full calendar year, else YYYYMM.
    $period = substr($encounter_date, 0, 4);
    if (substr($form_from_date, 0, 4) != substr($form_to_date, 0, 4) ||
      substr($form_from_date, 5, 5) != '01-01' ||
      substr($form_to_date  , 5, 5) != '12-31'
    ) {
      $period .= substr($encounter_date, 5, 2);
    }

    // This queries the MA codes from which we'll get the related IPPF codes.
    $query = "SELECT b.code_type, b.code, b.code_text, b.units, b.fee, " .
      "b.justify, c.related_code " .
      "FROM billing AS b, codes AS c WHERE " .
      "b.pid = '$row_pid' AND b.encounter = '$row_encounter' AND " .
      "b.activity = 1 AND " .
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
          recordStats(
            ($new_acceptor_date == $encounter_date ? 'NEW-' : 'S-R-') . $code,
            $period,
            $row['domain_identifier'],    // org unit
            $categoryoptioncombo,         // age and sex
            'CrE6h9KphAO'                 // indicates service and not referral
          );
        }
      }
    }

    $last_pid = $row_pid;
  }

  // Generate the output text.
  ksort($outarr);
  $out = '';
  foreach ($outarr as $key => $value) {
    list($period, $orgunit, $dataelement, $categoryoptioncombo, $attributeoptioncombo) =
      explode('|', $key);
    $out .= "\"$dataelement\", \"$period\", \"$orgunit\", \"$categoryoptioncombo\", " .
      "\"$attributeoptioncombo\", \"$value\", \"dummy\", \"" . date('Y-m-d') . "\", " .
      "\"\", \"FALSE\"\n";
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
