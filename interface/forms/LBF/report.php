<?php
// Copyright (C) 2009-2017 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

include_once("../../globals.php");
include_once($GLOBALS["srcdir"] . "/api.inc");

// This function is invoked from printPatientForms in report.inc
// when viewing a "comprehensive patient report".  Also from
// interface/patient_file/encounter/forms.php.
//
function lbf_report($pid, $encounter, $cols, $id, $formname) {
  global $CPR;
  require_once($GLOBALS["srcdir"] . "/options.inc.php");

  $grparr = array();
  getLayoutProperties($formname, $grparr, '*');
  // Check access control.
  if (!empty($grparr['']['grp_aco_spec'])) $LBF_ACO = explode('|', $grparr['']['grp_aco_spec']);
  if (!acl_check('admin', 'super') && !empty($LBF_ACO)) {
    if (!acl_check($LBF_ACO[0], $LBF_ACO[1])) {
      die(xlt('Access denied'));
    }
  }

  $arr = array();
  $shrow = getHistoryData($pid);
  $fres = sqlStatement("SELECT * FROM layout_options " .
    "WHERE form_id = ? AND uor > 0 " .
    "ORDER BY group_id, seq", array($formname));
  while ($frow = sqlFetchArray($fres)) {
    $field_id  = $frow['field_id'];
    $currvalue = '';
    if ($frow['edit_options'] == 'H') {
      if (isset($shrow[$field_id])) $currvalue = $shrow[$field_id];
    } else {
      $currvalue = lbf_current_value($frow, $id, $encounter);
      if ($currvalue === FALSE) continue; // should not happen
    }
    // For brevity, skip fields without a value.
    if ($currvalue === '') continue;
    $arr[$field_id] = $currvalue;
  }
  echo "<table>\n";
  display_layout_rows($formname, $arr);
  echo "</table>\n";
}
?>
