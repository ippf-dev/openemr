<?php
// Copyright (C) 2012-2016 Rod Roark <rod@sunsetsystems.com>
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

  // Create a hash to map contrameth list IDs to an indicator of whether it is a modern method.
  echo "var contraMapping = new Object();\n";
  $res = sqlStatement("SELECT option_id, option_value FROM list_options WHERE " .
    "list_id = 'contrameth' AND activity = 1 ORDER BY seq, title");
  while ($row = sqlFetchArray($res)) {
    $mapping = $row['option_value'] ? '1' : '0';
    echo "contraMapping['" . $row['option_id'] . "'] = '$mapping';\n";
  }

  // Create a hash to map IPPFCM codes to contrameth list IDs.
  echo "var ippfcmMapping = new Object();\n";
  $res = sqlStatement("SELECT code, code_text_short FROM codes WHERE " .
    "code_type = '32' ORDER BY code");
  while ($row = sqlFetchArray($res)) {
    echo "ippfcmMapping['" . $row['code'] . "'] = '" . $row['code_text_short'] . "';\n";
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
      contraMapping[f.form_curmethod.value] == '0') &&
     !pastmodern_autoset)
 {
  f.form_pastmodern.disabled = false;
 }
 else {
  f.form_pastmodern.selectedIndex = 2;
 }
 if (f.form_curmethod.selectedIndex > 0) {
  // Here there is a selected current method.  Check if it is the same contrameth list type
  // that the new method relates to.  If not, enable the reason for change selector.
  // A missing related contrameth ID indicates No Method and method change reason is disabled.
  var ippfcm = f.form_newmethod.value.substring(7);
  if (ippfcmMapping[ippfcm] && f.form_curmethod.value != ippfcmMapping[ippfcm]) {
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
  global $code_types, $contraception_billing_desc;

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

  // Get the description of the contraceptive method.
  $contraception_billing_desc = '';
  if ($contraception_billing_code) {
    $tmp = sqlQuery("SELECT code_text FROM codes WHERE " .
      "code_type = ? AND code = ?",
      array($code_types['IPPFCM']['id'], $contraception_billing_code));
    if (!empty($tmp['code_text'])) {
      $contraception_billing_desc = addslashes($tmp['code_text']);
    }
  }

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

/*********************************************************************
sel = f.form_newmethod;
sel.disabled = $newdisabled;
for (var i = 0; i < sel.options.length; ++i) {
 var ippfcm = sel.options[i].value.substring(7);
 if (ippfcm == '$contraception_billing_code') {
  sel.selectedIndex = i;
  break;
 }
}
*********************************************************************/

f.form_newmethod.disabled = $newdisabled;
if (f.form_newmethod__desc) {
 f.form_newmethod__desc.disabled = $newdisabled;
}
if ('$contraception_billing_code') {
 f.form_newmethod.value = 'IPPFCM:$contraception_billing_code';
 if (f.form_newmethod__desc) {
  f.form_newmethod__desc.value = '$contraception_billing_desc';
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
// f.form_newmethod.onchange = function () { current_method_changed(); };
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
  if (!empty($csrow['field_value']) && substr($csrow['field_value'], 7) != $contraception_billing_code) {
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
