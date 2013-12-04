<?php
// Copyright (C) 2012-2013 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This provides enhancement functions for the LBFccicon visit form.
// It is invoked by interface/forms/LBF/new.php.

require_once("../../../custom/code_types.inc.php");
require_once("../../../library/contraception_billing_scan.inc.php");

if (!$formid) {
  $row = sqlQuery("SELECT form_id FROM forms WHERE " .
    "pid = '$pid' AND encounter = '$encounter' AND formdir = '$formname' " .
    "AND deleted = 0 ORDER BY id DESC LIMIT 1");
  if (!empty($row)) {
    $GLOBALS['DUPLICATE_FORM_HANDLED'] = true;
    $formid = $row['form_id'];
  }
}

// The purpose of this function is to create JavaScript for the <head>
// section of the page.  This defines desired javaScript functions.
//
function LBFccicon_javascript() {
  global $formid;

  // Create an associative array of contrameth mapping values.  These are regex
  // patterns used to match ippfconmeth list items with contrameth list items.
  echo "var contraMapping = new Object();\n";
  $res = sqlStatement("SELECT option_id, option_value, mapping FROM list_options WHERE " .
    "list_id = 'contrameth' ORDER BY seq, title");
  while ($row = sqlFetchArray($res)) {
    $mapping = '';
    $i = strpos($row['mapping'], ':');
    if ($i !== FALSE) $mapping = substr($row['mapping'], $i + 1);
    if ($row['option_value']) { // if modern method
      $mapping = '1:' . $mapping;
    }
    else {                      // not a modern method
      $mapping = '0:' . $mapping;
    }
    echo "contraMapping['" . $row['option_id'] . "'] = '$mapping';\n";
  }

  echo "
var pastmodern_autoset = false;

// Respond to selection of a current or new method, or a new value for form_newmauser.
// If (newmauser is unassigned or yes) and (current method is unassigned or not modern)
// then Past Modern is enabled, otherwise is disabled and auto-set to Yes.
// If current is assigned and not No Method and new method != current then ask reason for method change.
function current_method_changed() {
 var f = document.forms[0];
 f.form_pastmodern.disabled = true;
 f.form_mcreason.disabled = true;

 if (f.form_newmauser.selectedIndex != 1 &&
     (f.form_curmethod.selectedIndex <= 0 ||
      contraMapping[f.form_curmethod.value].substring(0, 1) == '0') &&
     !pastmodern_autoset)
 {
  // Also do not enable the past-modern question if the global option is set to
  // record all acceptors new to modern contraception.
  f.form_pastmodern.disabled = " . ($GLOBALS['gbl_new_acceptor_policy'] == '3' ? 'false' : 'true') . ";
 }
 else {
  f.form_pastmodern.selectedIndex = 2;
 }
 if (f.form_curmethod.selectedIndex > 0) {
  // Here there is a selected current method.  Use its regex pattern to decide if
  // the new method is different.  If so, enable the reason for change selector.
  // A missing pattern indicates No Method and method change reason is disabled.
  var pattern = contraMapping[f.form_curmethod.value].substring(2);
  if (pattern && !f.form_newmethod.value.match('^' + pattern)) {
   f.form_mcreason.disabled = false;
  }
 }
}
";

  echo "
// Enable some form fields before submitting.
// This is because disabled fields do not submit their data, however
// we do want to save the default values that were set for them.
function mysubmit() {
 var f = document.forms[0];
 if (!validate(f)) return false;
 f.form_newmauser.disabled = false;
 f.form_pastmodern.disabled = false;
 f.form_newmethod.disabled = false;
 f.form_provider.disabled = false;
 f.form_mcreason.disabled = false;
 return true;
}
";
}

// The purpose of this function is to create JavaScript that is run
// once when the page is loaded.
//
function LBFccicon_javascript_onload() {
  global $formid, $pid, $encounter;
  global $contraception_billing_code, $contraception_billing_prov;

  $encrow = sqlQuery("SELECT date, provider_id FROM form_encounter " .
    "WHERE pid = '$pid' AND encounter = '$encounter' " .
    "ORDER BY id DESC LIMIT 1");
  $encdate = $encrow['date'];

  // Get the normalized contraception code "$contraception_billing_code",
  // if any, indicated by the services in the visit.  This call returns
  // TRUE if there is one, otherwise FALSE.  Also set is the provider
  // of that service, $contraception_billing_prov.
  //
  $newdisabled = contraception_billing_scan($pid, $encounter, $encrow['provider_id']) ? 'true' : 'false';

  /*******************************************************************
  // Get details of the last previous instance of this form, if any.
  // * "First contraception at this clinic" should be auto-set to NO
  //   and disabled if that question was answered previously.
  // * "Previous modern contraceptive use" should auto-set to YES
  //   and disabled it was YES previously.
  //
  $js_extra = "";
  $prvrow = sqlQuery("SELECT " .
    "d1.field_value AS newmauser, " .
    "d2.field_value AS pastmodern " .
    "FROM forms AS f " .
    "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
    "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmauser' " .
    "LEFT JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'pastmodern' " .
    "WHERE f.pid = '$pid' AND " .
    "f.formdir = 'LBFccicon' AND " .
    "f.deleted = 0 AND " .
    "f.form_id != '$formid' AND " .
    "fe.date < '$encdate' " .
    "ORDER BY fe.date DESC, f.form_id DESC LIMIT 1");
  if (!empty($prvrow)) {
    $js_extra .=
      "// There was a previous instance of this form so we know they are not a new MA user.\n" .
      "f.form_newmauser.selectedIndex = 1;\n" .
      "f.form_newmauser.disabled = true;\n";
    if (!empty($prvrow['pastmodern'])) {
      $js_extra .=
        "\n// Past Modern was previously true so must also be true now.\n" .
        "f.form_pastmodern.selectedIndex = 2;\n" .
        "f.form_pastmodern.disabled = true;\n" .
        "pastmodern_autoset = true;\n";
    }
  }
  *******************************************************************/
  // Get details of previous instances of this form.
  // * "First contraception at this clinic" should be auto-set to NO
  //   and disabled if that question was answered previously.
  // * "Previous modern contraceptive use" should auto-set to YES
  //   and disabled it was YES previously, or if a modern method was
  //   indicated in a previous instance of the form.
  //
  $newmauser_autoset = false;
  $pastmodern_autoset = false;
  $js_extra = "";
  $prvres = sqlStatement("SELECT " .
    "d1.field_value AS newmauser, " .
    "d2.field_value AS pastmodern, " .
    "d3.field_value AS newmethod " .
    "FROM forms AS f " .
    "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
    "     JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmauser' " .
    "LEFT JOIN lbf_data AS d2 ON d2.form_id = f.form_id AND d2.field_id = 'pastmodern' " .
    "LEFT JOIN lbf_data AS d3 ON d3.form_id = f.form_id AND d3.field_id = 'newmethod' " .
    "WHERE f.pid = '$pid' AND " .
    "f.formdir = 'LBFccicon' AND " .
    "f.deleted = 0 AND " .
    "f.form_id != '$formid' AND " .
    "fe.date < '$encdate' " .
    "ORDER BY fe.date DESC, f.form_id DESC");
  while ($prvrow = sqlFetchArray($prvres)) {
    $newmauser_autoset = true;
    if (!empty($prvrow['pastmodern']) || !empty($prvrow['newmethod'])) {
      $pastmodern_autoset = true;
    }
  }
  if ($newmauser_autoset) {
    $js_extra .=
      "// There was a previous instance of this form so we know they are not a new MA user.\n" .
      "f.form_newmauser.selectedIndex = 1;\n" .
      "f.form_newmauser.disabled = true;\n";
    if ($pastmodern_autoset) {
      $js_extra .=
        "\n// Past Modern must also be true now.\n" .
        "f.form_pastmodern.selectedIndex = 2;\n" .
        "f.form_pastmodern.disabled = true;\n" .
        "pastmodern_autoset = true;\n";
    }
  }

  echo "
var f = document.forms[0];
var sel;

$js_extra

current_method_changed();

sel = f.form_newmethod;
sel.disabled = $newdisabled;
for (var i = 0; i < sel.options.length; ++i) {
 if (sel.options[i].value == '$contraception_billing_code') {
  sel.selectedIndex = i;
  break;
 }
}

sel = f.form_provider;
sel.disabled = $newdisabled;
for (var i = 0; i < sel.options.length; ++i) {
 if (sel.options[i].value == '$contraception_billing_prov') {
  sel.selectedIndex = i;
  break;
 }
}

f.form_newmauser.onchange = function () { current_method_changed(); };
f.form_curmethod.onchange = function () { current_method_changed(); };
f.form_newmethod.onchange = function () { current_method_changed(); };
f.onsubmit = function () { return mysubmit(); };
";

  if (!empty($GLOBALS['DUPLICATE_FORM_HANDLED'])) {
    echo "
alert('" . xl('A Contraception form already exists for this visit and has been opened here.') . "');
";
  }

// Generate alert if method from services is different from a non-empty method in this form.
  $csrow = sqlQuery("SELECT field_value FROM lbf_data WHERE " .
    "form_id = '$formid' AND field_id = 'newmethod'");
  if (!empty($csrow['field_value']) && $csrow['field_value'] != $contraception_billing_code) {
    echo "
alert('" . xl('Contraceptive method selected on Tally Sheet does not match Adopted Method on Contraceptive form. Please resave Contraceptive form with correct information.') . "');
";
  }
}

// This function generates HTML to go after the Save button.
//
function LBFccicon_additional_buttons() {
  global $formid, $pid, $encounter;
  echo "<input type='submit' name='bn_save' " .
    "value='" . xl('Save and Tally Sheet') . "' />\n";
}

// Custom logic to run at end of save handler.
// In this case we redirect to the Tally Sheet.
//
function LBFccicon_save_exit() {
  if ($_POST['bn_save'] == xl('Save and Tally Sheet')) {
    formJump("{$GLOBALS['rootdir']}/patient_file/encounter/load_form.php?formname=fee_sheet");
    formFooter();
    return true;
  }
  return false;
}

?>
