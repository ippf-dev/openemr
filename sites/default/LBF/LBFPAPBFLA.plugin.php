<?php
// Copyright (C) 2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This provides enhancement functions for the LBFPAPBFLA visit form.
// It is invoked by interface/forms/LBF/new.php.

// Private function to query for the most recent value and date of a Visit field
// as of the current encounter.
//
function _LBFPAPBFLA_query_previous($field_id) {
  global $pid, $encounter, $formname, $formid;
  // Visit attribute, get most recent value as of this visit.
  $sql = "SELECT sa.field_value, e2.date " .
    "FROM form_encounter AS e1 " .
    "JOIN form_encounter AS e2 ON " .
    "e2.pid = e1.pid AND (e2.date < e1.date OR (e2.date = e1.date AND e2.encounter <= e1.encounter)) " .
    "JOIN shared_attributes AS sa ON " .
    "sa.pid = e2.pid AND sa.encounter = e2.encounter AND sa.field_id = ?" .
    "WHERE e1.pid = ? AND e1.encounter = ? " .
    "ORDER BY e2.date DESC, e2.encounter DESC LIMIT 1";
  // echo "\n<!-- $sql $field_id $pid $encounter -->\n"; // debugging
  $row = sqlQuery($sql, array($field_id, $pid, $encounter));
  return $row;
}

// Generate default for date of previous PAP_Results Visit field.
//
function LBFPAPBFLA_default_PAP_PrevSmearDate() {
  $row = _LBFPAPBFLA_query_previous('PAP_Results');
  if (empty($row)) return '';
  return substr($row['date'], 0, 10);
}

// Generate default for value of previous PAP_Results Visit field.
//
function LBFPAPBFLA_default_PAP_PrevSmearResults() {
  $row = _LBFPAPBFLA_query_previous('PAP_Results');
  if (empty($row)) return '';
  return $row['field_value'];
}

// PHP end tag omitted.
