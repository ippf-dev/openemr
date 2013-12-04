<?php
// Copyright (C) 2011 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This provides enhancement functions for the LBFmsivd visit form.
// It is invoked by interface/forms/LBF/new.php.

// The purpose of this function is to create JavaScript that is run
// once when the page is loaded.
//
function LBFmsivd_javascript_onload() {
  echo "
var f = document.forms[0];
f.bn_save.style.backgroundColor = '#cc0000';
f.bn_save.style.color = 'ffffff';
";
}

// Custom logic to run at end of save handler.
// In this case we redirect to the Tally Sheet.
//
function LBFmsivd_save_exit() {
  // rde flag indicates Rapid Data Entry mode to the Fee Sheet, which will cause
  // it to have a red Save button and to load the Checkout form at save time.
  formJump("{$GLOBALS['rootdir']}/patient_file/encounter/load_form.php?formname=fee_sheet&rde=1");
  formFooter();
  return true;
}
?>
