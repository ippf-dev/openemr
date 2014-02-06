<?php
// Copyright (C) 2013 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.


// Assign a default pubpid value for this patient.
// This is intended to be called at save time for a new patient.
//
function assignNewPubpid($pid, $regdate, $lname, $facility=0) {
  $mask = $GLOBALS['gbl_mask_patient_id'];
  // If no suitable mask, default to the internal "pid" value.
  if (strpos($mask, '^') === FALSE) return $pid;
  //
  if (empty($regdate)) {
    $regdate = date('Y-m-d');
  }
  if (empty($facility)) {
    $tmp = sqlQuery("SELECT facility_id FROM users WHERE id = '" . $_SESSION['authUserID'] . "'");
    $facility = 0 + empty($tmp['facility_id']) ? 0 : $tmp['facility_id'];
  }
  $out = '';
  $matches = array();
  while (preg_match('/^(.*?)\^(.+?)\^(.*)$/', $mask, $matches)) {
    $out .= $matches[1];
    $op = substr($matches[2], 0, 1);
    $len = 0 + substr($matches[2], 1);
    $mask = $matches[3];
    if ($op == 'Y') { // Year YYYY
      if ($len == 2) {
        $out .= substr($regdate, 2, 2);
      }
      else {
        $out .= substr($regdate, 0, 4);
      }
    }
    else if ($op == 'M') { // Month MM
      $out .= substr($regdate, 5, 2);
    }
    else if ($op == 'D') { // Day of month DD
      $out .= substr($regdate, 8, 2);
    }
    else if ($op == 'L') { // Last name in upper case
      if ($len) {
        $out .= strtoupper(substr($lname, 0, $len));
      }
      else {
        $out .= strtoupper($lname);
      }
    }
    else if ($op == 'F') { // Facility Code
      $tmp = sqlQuery("SELECT federal_ein FROM facility WHERE id = '$facility'");
      $out .= $tmp['federal_ein'];
    }
    else if ($op == 'S') { // Sequence Number
      $tmp = sqlQuery("SELECT pubpid FROM patient_data WHERE " .
        "pubpid LIKE '$out%' ORDER BY pubpid DESC LIMIT 1");
      $seqno = 0;
      if (!empty($tmp['pubpid'])) {
        $seqno = 0 + substr($tmp['pubpid'], strlen($out), $len);
      }
      $out .= sprintf("%0{$len}d", ++$seqno);
    }
  }
  return $out;
}

?>