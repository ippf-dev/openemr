<?php
// Copyright (C) 2008-2012 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This script creates an export file and sends it to the users's
// browser for download.

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");

// True for MSI, otherwise it's IPPF.
$msi_specific = !empty($GLOBALS['gbl_rapid_workflow']) &&
  $GLOBALS['gbl_rapid_workflow'] == 'LBFmsivd';

// Set this to:
// 0 = Select all visits and use one (Billing) facility
// 1 = Output a separate facility for each OpenEMR facility
// 2 = Output the facilities having a given SDP ID, merged into one facility
//
$MULTIPLE = $msi_specific ? 0 : 2;

if (!acl_check('admin', 'super')) die("Not authorized!");

$alertmsg = '';

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
  if (/* $text */ true) {
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

// Specific to MSI:
//
function describedOption($list_id, $option_id, $default='9') {
  if ($option_id === '') return $default;
  $row = sqlQuery("SELECT title FROM list_options WHERE " .
    "list_id = '$list_id' AND option_id = '$option_id' LIMIT 1");
  if (empty($row)) return $option_id; // should not happen
  $maparr = explode(':', $row['title']);
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
  global $msi_specific;

  // Starting a new visit (encounter).
  OpenTag('IMS_eMRUpload_Visit');
  Add('VisitDate' , xmlTime($date));
  Add('emrVisitId', $encounter);

  // Specific to MSI:
  if ($msi_specific) {
    // Get LBF_Data entries
    $lbfres = sqlStatement("SELECT f.form_id, ld.field_id, ld.field_value FROM forms f " .
      "INNER JOIN lbf_data ld ON f.form_id = ld.form_id " . 
      "WHERE " .
      "f.pid = '$pid' AND " .
      "f.encounter = '$encounter' AND " .
      "f.formdir = 'LBFmsivd' AND " .
      "f.deleted = 0 " .
      "ORDER BY f.form_id, f.id, ld.field_id");
    $last_form_id = '';
    while ($lbfrow = sqlFetchArray($lbfres)) {
      if ($last_form_id) {
        // Skip any duplicate LBFmsivd forms for this encounter.
        if ($lbfrow['form_id'] != $last_form_id) continue;
      }
      else {
        $last_form_id = $lbfrow['form_id'];
      }
      switch ($lbfrow['field_id']) {
        case "Child":
          Add('Child', mappedOption('Parity', $lbfrow['field_value'], ''));
          break;
        case "CurrentContraceptive":
          Add('CurrentMethod', describedOption('Current_form_of_contraception', $lbfrow['field_value'], ''));
          break;
        case "FollowUp":
          Add('FollowUp', describedOption('FollowUp', $lbfrow['field_value']), '');
          break;
        case "gestation":
          Add('GestationalAge', $lbfrow['field_value']);
          break;
        case "ReferredBy":
          Add('MedicalReferral', describedOption('Referred_by', $lbfrow['field_value'], ''));
          break;
      }
    }
    $fres = sqlStatement("SELECT f.form_id, fe.sensitivity, fe.referral_source, pc.pc_catname FROM forms f " .
      "INNER JOIN form_encounter fe on f.pid=fe.pid and f.encounter=fe.encounter " . 
      "LEFT JOIN openemr_postcalendar_categories pc on fe.pc_catid=pc.pc_catid " . 
      "WHERE " .
      "f.pid = '$pid' AND " .
      "f.encounter = '$encounter' AND " .
      "f.formdir = 'newpatient' AND " .
      "f.deleted = 0 " .
      "ORDER BY f.id LIMIT 1");
    // For each PE form in this encounter...
    while ($frow = sqlFetchArray($fres)) {
      $form_id = $frow['form_id'];
      Addifpresent('Sensitivity'   , $frow['sensitivity']);
      Addifpresent('MarketingReferral', $frow['referral_source']);
      Addifpresent('Category'      , $frow['pc_catname']);
    }
  } // end $msi_specific

  // Dump IPPF services.
  // This queries the MA codes from which we'll get the related IPPF codes.
  $query = "SELECT b.code_type, b.code, b.code_text, b.units, b.fee, " .
    "b.justify, c.related_code " .
    "FROM billing AS b, codes AS c WHERE " .
    "b.pid = '$pid' AND b.encounter = '$encounter' AND " .
    "b.activity = 1 AND " .
    "c.code_type = '12' AND c.code = b.code AND c.modifier = b.modifier ";
  $bres = sqlStatement($query);
  while ($brow = sqlFetchArray($bres)) {
    if (!empty($brow['related_code'])) {
      $relcodes = explode(';', $brow['related_code']);
      foreach ($relcodes as $codestring) {
        if ($codestring === '') continue;
        list($codetype, $code) = explode(':', $codestring);
        if ($codetype !== 'IPPF2') continue;
        // Starting a new service (IPPF2 code).
        OpenTag('IMS_eMRUpload_Service');
        Add('IppfServiceProductId', $code);
        Add('LocalServiceProductId'         , $brow['code']);
        Add('LocalServiceProductDescription', $brow['code_text']);
        Add('Type'                , '0'); // 0=service, 1=product, 2=diagnosis, 3=referral
        Add('IppfQuantity'        , $brow['units']);
        Add('CurrID'              , "TBD"); // TBD: Currency e.g. USD
        Add('Amount'              , $brow['fee']);
        // Dump related diagnoses, if any.
        $atmp = explode(':', $row['justify']);
        foreach ($atmp as $tmp) {
          if (!empty($tmp)) {
            OpenTag('IMS_eMRService_Diag');
            Add('DiagCode', $tmp);
            CloseTag('IMS_eMRService_Diag');
          }
        }
        CloseTag('IMS_eMRUpload_Service');
      } // end foreach
    } // end if related code
  } // end while billing row found

  // Dump products.
  $query = "SELECT drug_id, quantity, fee FROM drug_sales WHERE " .
    "pid = '$pid' AND encounter = '$encounter' " .
    "ORDER BY drug_id, sale_id";
  $pres = sqlStatement($query);
  while ($prow = sqlFetchArray($pres)) {
    OpenTag('IMS_eMRUpload_Service');
    Add('IppfServiceProductId', $prow['drug_id']);
    Add('Type'                , '1'); // 0=service, 1=product, 2=diagnosis, 3=referral
    Add('IppfQuantity'        , $prow['quantity']);
    Add('CurrID'              , "TBD"); // TBD: Currency e.g. USD
    Add('Amount'              , $prow['fee']);
    CloseTag('IMS_eMRUpload_Service');
  } // end while drug_sales row found

  // Dump diagnoses.
  $query = "SELECT code FROM billing WHERE " .
    "pid = '$pid' AND encounter = '$encounter' AND " .
    "code_type = 'ICD9' AND activity = 1 ORDER BY code, id";
  $dres = sqlStatement($query);
  while ($drow = sqlFetchArray($dres)) {
    OpenTag('IMS_eMRUpload_Service');
    Add('IppfServiceProductId', $drow['code']);
    Add('Type'                , '2'); // 0=service, 1=product, 2=diagnosis, 3=referral
    Add('IppfQuantity'        , '1');
    Add('CurrID'              , "TBD"); // TBD: Currency e.g. USD
    Add('Amount'              , '0');
    CloseTag('IMS_eMRUpload_Service');
  } // end while billing row found

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
      OpenTag('IMS_eMRUpload_Service');
      Add('IppfServiceProductId', $code);
      Add('Type'                , '3'); // 0=service, 1=product, 2=diagnosis, 3=referral
      Add('IppfQuantity'        , '1');
      Add('CurrID'              , "TBD"); // TBD: Currency e.g. USD
      Add('Amount'              , '0');
      CloseTag('IMS_eMRUpload_Service');
    } // end foreach
  } // end referral

  CloseTag('IMS_eMRUpload_Visit');
}

function endClient($pid, &$encarray) {
  global $beg_year, $beg_month, $end_year, $end_month, $msi_specific;

  if (!$msi_specific) {
    // Output issues.
    $ires = sqlStatement("SELECT " .
      "l.id, l.type, l.begdate, l.enddate, l.title, l.diagnosis, " .
      "c.prev_method, c.new_method, c.reason_chg, c.reason_term, " .
      "c.hor_history, c.hor_lmp, c.hor_flow, c.hor_bleeding, c.hor_contra, " .
      "c.iud_history, c.iud_lmp, c.iud_pain, c.iud_upos, c.iud_contra, " .
      "c.sur_screen, c.sur_anes, c.sur_type, c.sur_post_ins, c.sur_contra, " .
      "c.nat_reason, c.nat_method, c.emg_reason, c.emg_method, " .
      "g.client_status, g.in_ab_proc, g.ab_types, g.ab_location, g.pr_status, " .
      "g.gest_age_by, g.sti, g.prep_procs, g.reason, g.exp_p_i, g.ab_contraind, " .
      "g.screening, g.pre_op, g.anesthesia, g.side_eff, g.rec_compl, g.post_op, " .
      "g.qc_ind, g.contrameth, g.fol_compl " .
      "FROM lists AS l " .
      "LEFT JOIN lists_ippf_con  AS c ON l.type = 'contraceptive' AND c.id = l.id " .
      "LEFT JOIN lists_ippf_gcac AS g ON l.type = 'ippf_gcac' AND g.id = l.id " .
      "WHERE l.pid = '$pid' AND " .
      sprintf("l.begdate >= '%04u-%02u-01 00:00:00' AND ", $beg_year, $beg_month) .
      sprintf("l.begdate <  '%04u-%02u-01 00:00:00' AND ", $end_year, $end_month) .
      "l.type != 'ippf_gcac' " .
      "ORDER BY l.begdate");

    while ($irow = sqlFetchArray($ires)) {
      OpenTag('IMS_eMRUpload_Issue');
      Add('IssueType'     , substr($irow['type'], 0, 15)); // per email 2009-03-20
      Add('emrIssueId'    , $irow['id']);
      Add('IssueStartDate', xmlTime($irow['begdate'], 0));
      Add('IssueEndDate'  , xmlTime($irow['enddate']));
      Add('IssueTitle'    , $irow['title']);
      Add('IssueDiagnosis', $irow['diagnosis']);
      $form_id = ($irow['type'] == 'ippf_gcac') ? 'GCA' : 'CON';
      foreach ($irow AS $key => $value) {
        if (empty($value)) continue;
        if ($key == 'id' || $key == 'type' || $key == 'begdate' ||
          $key == 'enddate' || $key == 'title' || $key == 'diagnosis')
          continue;
        $avalues = explode('|', $value);
        foreach ($avalues as $tmp) {
          OpenTag('IMS_eMRUpload_IssueData');
          // TBD: Add IssueCodeGroup to identify the list, if any???
          Add('IssueCodeGroup', '?');
          Add('IssueCode', $key);
          Add('IssueCodeValue', mappedFieldOption($form_id, $key, $tmp));
          CloseTag('IMS_eMRUpload_IssueData');
        }
      }
      // List the encounters linked to this issue.  We include pid
      // to speed up the search, as it begins the primary key.
      $ieres = sqlStatement("SELECT encounter FROM issue_encounter " .
        "WHERE pid = '$pid' AND list_id = '" . $irow['id'] . "' " .
        "ORDER BY encounter");
      while ($ierow = sqlFetchArray($ieres)) {
        OpenTag('IMS_eMRUpload_VisitIssue');
        Add('emrIssueId', $irow['id']);
        Add('emrVisitId', $ierow['encounter']);
        CloseTag('IMS_eMRUpload_VisitIssue');
      }
      CloseTag('IMS_eMRUpload_Issue');
    }

    // Loop on $encarray and generate an "issue" for each GCAC visit form,
    // similarly to the above.
    foreach ($encarray as $erow) {
      $fres = sqlStatement("SELECT form_id FROM forms WHERE " .
        "pid = '$pid' AND " .
        "encounter = '" . $erow['encounter'] . "' AND " .
        "formdir = 'LBFgcac' AND " .
        "deleted = 0 " .
        "ORDER BY id");
      // For each GCAC form in this encounter...
      while ($frow = sqlFetchArray($fres)) {
        $form_id = $frow['form_id'];
        OpenTag('IMS_eMRUpload_Issue');
        Add('IssueType'     , 'ippf_gcac');
        Add('emrIssueId'    , 10000000 + $form_id);
        Add('IssueStartDate', xmlTime($erow['date'], 0));
        Add('IssueEndDate'  , xmlTime(''));
        Add('IssueTitle'    , 'GCAC Visit Form');
        Add('IssueDiagnosis', '');
        $gres = sqlStatement("SELECT field_id, field_value FROM lbf_data WHERE " .
          "form_id = '$form_id' ORDER BY field_id");
        // For each data item in the form...
        while ($grow = sqlFetchArray($gres)) {
          $key = $grow['field_id'];
          $value = $grow['field_value'];
          if (empty($value)) continue;
          $avalues = explode('|', $value);
          foreach ($avalues as $tmp) {
            OpenTag('IMS_eMRUpload_IssueData');
            Add('IssueCodeGroup', '?');
            Add('IssueCode', $key);
            Add('IssueCodeValue', mappedFieldOption('LBFgcac', $key, $tmp));
            CloseTag('IMS_eMRUpload_IssueData');
          }
        }
        OpenTag('IMS_eMRUpload_VisitIssue');
        Add('emrIssueId', 10000000 + $form_id);
        Add('emrVisitId', $erow['encounter']);
        CloseTag('IMS_eMRUpload_VisitIssue');
        CloseTag('IMS_eMRUpload_Issue');
      }
    }
  } // end not $msi_specific

  CloseTag('IMS_eMRUpload_Client');
}

function endFacility() {
  global $beg_year, $beg_month;
  OpenTag('IMS_eMRUpload_Version');
  Add('XMLversionNumber', '1');
  Add('Period', sprintf('%04u-%02u-01T00:00:00', $beg_year, $beg_month));
  CloseTag('IMS_eMRUpload_Version');
  CloseTag('IMS_eMRUpload_Point');
}

if (!empty($form_submit)) {
  $sdpid     = $_POST['form_sdp'];
  $beg_year  = $_POST['form_year'];
  $beg_month = $_POST['form_month'];
  $end_year  = $beg_year;
  $end_month = $beg_month + 1;
  if ($end_month > 12) {
    $end_month = 1;
    ++$end_year;
  }

  if ($MULTIPLE == 2) {
    $facrow = sqlQuery("SELECT id AS facility_id, name, street, city AS fac_city, " .
      "state AS fac_state, postal_code, country_code, federal_ein, " .
      "domain_identifier, pos_code, latitude, longitude FROM facility " .
      "WHERE domain_identifier = '$sdpid' " .
      "ORDER BY billing_location DESC, id ASC LIMIT 1");
    $query = "SELECT DISTINCT " .
      "fe.pid, " .
      "p.regdate, p.date AS last_update, p.DOB, p.sex, " .
      "p.city, p.state, p.occupation, p.status, p.ethnoracial, " .
      "p.interpretter, p.monthly_income, p.referral_source, p.pricelevel, " .
      "p.userlist1, p.userlist3, p.userlist4, p.userlist5, " .
      "p.usertext11, p.usertext12, p.usertext13, p.usertext14, p.usertext15, " .
      "p.usertext16, p.usertext17, p.usertext18, p.usertext19, p.usertext20, " .
      "p.userlist2 AS education " .
      "FROM form_encounter AS fe " .
      "JOIN facility AS f ON f.id = fe.facility_id AND f.domain_identifier = '$sdpid' " .
      "JOIN patient_data AS p ON p.pid = fe.pid WHERE " .
      sprintf("fe.date >= '%04u-%02u-01 00:00:00' AND ", $beg_year, $beg_month) .
      sprintf("fe.date <  '%04u-%02u-01 00:00:00'     ", $end_year, $end_month) .
      "ORDER BY fe.pid";
  }
  else if ($MULTIPLE) {
    $query = "SELECT DISTINCT " .
      "fe.facility_id, fe.pid, " .
      "f.name, f.street, f.city AS fac_city, f.state AS fac_state, f.postal_code, " .
      "f.country_code, f.federal_ein, f.domain_identifier, f.pos_code, f.latitude, f.longitude, " .
      "p.regdate, p.date AS last_update, p.DOB, p.sex, " .
      "p.city, p.state, p.occupation, p.status, p.ethnoracial, " .
      "p.interpretter, p.monthly_income, p.referral_source, p.pricelevel, " .
      "p.userlist1, p.userlist3, p.userlist4, p.userlist5, " .
      "p.usertext11, p.usertext12, p.usertext13, p.usertext14, p.usertext15, " .
      "p.usertext16, p.usertext17, p.usertext18, p.usertext19, p.usertext20, " .
      "p.userlist2 AS education " .
      "FROM form_encounter AS fe " .
      "LEFT OUTER JOIN facility AS f ON f.id = fe.facility_id " .
      "JOIN patient_data AS p ON p.pid = fe.pid WHERE " .
      sprintf("fe.date >= '%04u-%02u-01 00:00:00' AND ", $beg_year, $beg_month) .
      sprintf("fe.date <  '%04u-%02u-01 00:00:00'     ", $end_year, $end_month) .
      "ORDER BY fe.facility_id, fe.pid";
  }
  else {
    $facrow = sqlQuery("SELECT id AS facility_id, name, street, city AS fac_city, " .
      "state AS fac_state, postal_code, country_code, federal_ein, " .
      "domain_identifier, pos_code, latitude, longitude FROM facility " .
      "ORDER BY billing_location DESC, id ASC LIMIT 1");
    $query = "SELECT DISTINCT " .
      "fe.pid, " .
      "p.regdate, p.date AS last_update, p.DOB, p.sex, " .
      "p.city, p.state, p.occupation, p.status, p.ethnoracial, " .
      "p.interpretter, p.monthly_income, p.referral_source, p.pricelevel, " .
      "p.userlist1, p.userlist3, p.userlist4, p.userlist5, " .
      "p.usertext11, p.usertext12, p.usertext13, p.usertext14, p.usertext15, " .
      "p.usertext16, p.usertext17, p.usertext18, p.usertext19, p.usertext20, " .
      "p.userlist2 AS education " .
      "FROM form_encounter AS fe " .
      "JOIN patient_data AS p ON p.pid = fe.pid WHERE " .
      sprintf("fe.date >= '%04u-%02u-01 00:00:00' AND ", $beg_year, $beg_month) .
      sprintf("fe.date < '%04u-%02u-01 00:00:00' ", $end_year, $end_month) .
      "ORDER BY fe.pid";
  }

  $last_pid = -1;
  $last_facility = -1;

  $res = sqlStatement($query);

  while ($row = sqlFetchArray($res)) {
    if ($MULTIPLE == 1) $facrow =& $row;

    if (($MULTIPLE == 2 && $last_facility < 0) ||
        ($MULTIPLE != 2 && $facrow['facility_id'] != $last_facility))
    {
      if ($last_facility >= 0) {
        endFacility();
      }
      $last_facility = $facrow['facility_id'];
      // Starting a new facility.
      OpenTag('IMS_eMRUpload_Point');
      Add('ServiceDeliveryPointName' , $facrow['name']);
      // Add('EmrServiceDeliveryPointId', $facrow['facility_id']);
      Add('EmrServiceDeliveryPointId', $facrow['domain_identifier']);
      Add('Channel'                  , '01');
      // Add('Channel'                  , $facrow['pos_code']);
      Add('Latitude'                 , $facrow['latitude']);
      Add('Longitude'                , $facrow['longitude']);
      Add('Address'                  , $facrow['street']);
      Add('Address2'                 , '');
      Add('City'                     , $facrow['fac_city']);
      Add('PostCode'                 , $facrow['postal_code']);
    }

    $last_pid = 0 + $row['pid'];

    $education = mappedOption('userlist2', $row['education']);

    /*****************************************************************
    // Get most recent contraceptive issue.
    $crow = sqlQuery("SELECT l.begdate, c.new_method " .
      "FROM lists AS l, lists_ippf_con AS c WHERE " .
      "l.pid = '$last_pid' AND c.id = l.id " .
      "ORDER BY l.begdate DESC LIMIT 1");
    *****************************************************************/

    // Get obstetric and abortion data from most recent static history.
    $hrow = sqlQuery("SELECT date, " .
      "usertext16 AS genobshist, " .
      "usertext17 AS genabohist " .
      "FROM history_data WHERE pid = '$last_pid' " .
      "ORDER BY date DESC LIMIT 1");

    // Starting a new client (patient).
    OpenTag('IMS_eMRUpload_Client');
    Add('emrClientId'     , $row['pid']);
    Add('RegisteredOn'    , xmlTime($row['regdate']));
    Add('LastUpdated'     , xmlTime($row['last_update']));

    /*****************************************************************
    Add('NewAcceptorDate' , xmlTime($row['contrastart']));

    if (!$msi_specific) {
      // Get the current contraceptive method with greatest effectiveness.
      $methodid = '';
      $methodvalue = -999;
      if (!empty($crow['new_method'])) {
        $methods = explode('|', $crow['new_method']);
        $methodid = mappedOption('contrameth', $methods[0]);
      }
      Add('CurrentMethod', $methodid);
    }
    *****************************************************************/

    if (!$msi_specific) {
      // Get New Acceptor date, and also the method in case someone wants it later.
      $query = "SELECT " .
        "fe.date AS contrastart, d1.field_value AS contrameth " .
        "FROM forms AS f " .
        "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
        "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' " .
        /*************************************************************
        "LEFT JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'newmauser' " .
        "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 AND f.pid = '$last_pid' AND " .
        "(d1.field_value LIKE '12%' OR (d2.field_value IS NOT NULL AND d2.field_value = '1')) " .
        *************************************************************/
        "LEFT JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'pastmodern' " .
        "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 AND f.pid = '$last_pid' AND " .
        "(d1.field_value LIKE 'IPPFCM:%' AND (d2.field_value IS NULL OR d2.field_value = '0')) " .
        /************************************************************/
        "ORDER BY contrastart DESC LIMIT 1";
      $contradate_row = sqlQuery($query);

      Add('NewAcceptorDate' , xmlTime($contradate_row['contrastart']));

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

      $methodid = '';
      if (!empty($contrameth_row['contrameth'])) {
        // $methodid = mappedOption('ippfconmeth', $contrameth_row['contrameth']);
        // $methodid = $contrameth_row['contrameth'];
        $methodid = substr($contrameth_row['contrameth'], 7);
      }
      Add('CurrentMethod', $methodid);
    }

    Add('Dob'        , xmlTime($row['DOB']));
    Add('DobType'    , "rel"); // rel=real, est=estimated

    if ($msi_specific) {
      // Add('Education', describedOption('Education', $education, ''));
      // Add('Sex'      , Sex($row['sex']));
      // AddIfPresent('WarSubCity', $row['state']);
      Add('Education', $education);
      Add('Demo5'    , Sex($row['sex']));
      AddIfPresent('State', $row['state']);
    }
    else {
      Add('Pregnancies', 0 + getTextListValue($hrow['genobshist'],'npreg')); // number of pregnancies
      Add('Children'   , 0 + getTextListValue($hrow['genobshist'],'nlc'));   // number of living children
      Add('Abortions'  , 0 + getTextListValue($hrow['genabohist'],'nia'));   // number of induced abortions
      Add('Education'  , $education);
      Add('Demo5'      , Sex($row['sex']));
      // Things included if they are present (July 2010)
      AddIfPresent('City', $row['city']);
      AddIfPresent('State', mappedOption('state', $row['state'], ''));
      AddIfPresent('Occupation', mappedOption('occupations', $row['occupation'], ''));
      AddIfPresent('MaritalStatus', mappedOption('marital', $row['status'], ''));
      AddIfPresent('Ethnoracial', mappedOption('ethrace', $row['ethnoracial'], ''));
      AddIfPresent('Interpreter', $row['interpretter']);
      AddIfPresent('MonthlyIncome', $row['monthly_income']);
      AddIfPresent('ReferralSource', mappedOption('refsource', $row['referral_source'], ''));
      AddIfPresent('PriceLevel', mappedOption('pricelevel', $row['pricelevel'], ''));
      AddIfPresent('UserList1', mappedOption('userlist1', $row['userlist1'], ''));
      AddIfPresent('UserList3', mappedOption('userlist3', $row['userlist3'], ''));
      AddIfPresent('UserList4', mappedOption('userlist4', $row['userlist4'], ''));
      AddIfPresent('UserList5', mappedOption('userlist5', $row['userlist5'], ''));
      AddIfPresent('UserText11', $row['usertext11']);
      AddIfPresent('UserText12', $row['usertext12']);
      AddIfPresent('UserText13', $row['usertext13']);
      AddIfPresent('UserText14', $row['usertext14']);
      AddIfPresent('UserText15', $row['usertext15']);
      AddIfPresent('UserText16', $row['usertext16']);
      AddIfPresent('UserText17', $row['usertext17']);
      AddIfPresent('UserText18', $row['usertext18']);
      AddIfPresent('UserText19', $row['usertext19']);
      AddIfPresent('UserText20', $row['usertext20']);
    }

    // Dump the visits for this patient.
    if ($MULTIPLE == 2) {
      $query = "SELECT " .
        "fe.encounter, fe.date " .
        "FROM form_encounter AS fe, facility AS f WHERE " .
        "fe.pid = '$last_pid' AND f.id = fe.facility_id AND " .
        "f.domain_identifier = '$sdpid' ";
    }
    else if ($MULTIPLE == 1) {
      $query = "SELECT " .
        "fe.encounter, fe.date " .
        "FROM form_encounter AS fe WHERE " .
        "fe.pid = '$last_pid' AND fe.facility_id = '$last_facility' ";
    }
    else {
      $query = "SELECT " .
        "fe.encounter, fe.date " .
        "FROM form_encounter AS fe WHERE " .
        "fe.pid = '$last_pid' ";
    }

    if (true) {
      // The new logic here is to restrict to the given date range.
      // Set the above to false if all visits are wanted.
      $query .= "AND " .
      sprintf("fe.date >= '%04u-%02u-01 00:00:00' AND ", $beg_year, $beg_month) .
      sprintf("fe.date <  '%04u-%02u-01 00:00:00'     ", $end_year, $end_month);
    }

    /*****************************************************************
    $query .= "ORDER BY fe.encounter";
    $eres = sqlStatement($query);
    $encarray = array();
    while ($erow = sqlFetchArray($eres)) {
      exportEncounter($last_pid, $erow['encounter'], $erow['date']);
      $encarray[] = $erow;
    }
    *****************************************************************/
    // Logic revised to skip duplicate encounters which should not happen!
    $query .= "ORDER BY fe.encounter, fe.id";
    $eres = sqlStatement($query);
    $encarray = array();
    $last_encounter = '';
    while ($erow = sqlFetchArray($eres)) {
      if ($erow['encounter'] == $last_encounter) continue;
      $last_encounter = $erow['encounter'];
      exportEncounter($last_pid, $erow['encounter'], $erow['date']);
      $encarray[] = $erow;
    }
    /****************************************************************/

    endClient($last_pid, $encarray);
  }

  if ($last_facility >= 0) endFacility();
  // endFacility();

  // This is the "filename" for the Content-Disposition header.
  $filename = 'export.xml';

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

$months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
  5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September',
 10 => 'October', 11 => 'November', 12 => 'December');

$selmonth = date('m') - 1;
$selyear  = date('Y') + 0;
if ($selmonth < 1) {
  $selmonth = 12;
  --$selyear;
}
?>
<html>

<head>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>
<title><?php xl('Backup','e'); ?></title>
</head>

<body class="body_top">
<center>
&nbsp;<br />
<form method='post' action='ippf_export.php'>

<table style='width:95%'>
 <tr>
  <td align='center'>
   <?php echo xl('Month'); ?>:
   <select name='form_month'>
<?php
foreach ($months as $key => $value) {
  echo "    <option value='$key'";
  if ($key == $selmonth) echo " selected";
  echo ">" . xl($value) . "</option>\n";
}
?>
   </select>
   <input type='text' name='form_year' size='4' value='<?php echo $selyear; ?>' />

<?php if ($MULTIPLE == 2) { ?>
   &nbsp;
   <?php echo xl('SDP ID'); ?>:
   <select name='form_sdp'>
<?php
$fres = sqlStatement("SELECT DISTINCT domain_identifier FROM facility ORDER BY domain_identifier");
while ($frow = sqlFetchArray($fres)) {
  $sdpid = trim($frow['domain_identifier']);
  if (strlen($sdpid) < 1 || strspn($sdpid, '0123456789-') < strlen($sdpid)) {
    $alertmsg = xl('ERROR') . ': ' . xl('One or more SDP IDs are empty or contain invalid characters');
  }
  echo "    <option value='$sdpid'";
  echo ">$sdpid</option>\n";
}
?>
   </select>
<?php } ?>

   &nbsp;
   <input type='checkbox' name='form_compress'
    title='<?php echo xl('To compress in ZIP archive format'); ?>'
    /><?php echo xl('Compress'); ?>

<?php if (function_exists('openssl_encrypt') && ($msi_specific || !empty($GLOBALS['gbl_encryption_key']))) { ?>
   &nbsp;
   <input type='checkbox' name='form_encrypt'
    title='<?php echo xl('If AES encryption is desired'); ?>'
    /><?php echo xl('Encypt'); ?>
<?php } ?>

   &nbsp;
   <input type='submit' name='form_submit' value='Generate XML' />
  </td>
 </tr>
</table>

</form>

</center>

<script language="JavaScript">
<?php
  if ($alertmsg) {
    echo "alert('" . htmlentities($alertmsg) . "');\n";
  }
?>
</script>

</body>
</html>
