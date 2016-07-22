<?php
// Copyright (C) 2009-2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This provides enhancement functions for the referral (REF) form.
// It is invoked by interface/patient_file/transaction/add_transaction.php.

// The purpose of this function is to create JavaScript for the <head>
// section of the page.  This in turn defines desired javaScript
// functions.
//
function LBFref_javascript() {
  // This JavaScript function is to reload the "Refer To" options when
  // the "External Referral" selection changes.
  echo "// onChange handler for form_refer_external.
var poptions = new Array();
function external_changed(ischange) {
 var f = document.forms[0];
 var rtval = f.form_refer_external.value;
 //
 var pselt = f.form_refer_to;
 var i = pselt.selectedIndex < 0 ? 0 : pselt.selectedIndex;
 var pvaluet = pselt.options[i].value;
 //
 var pself = f.form_refer_from;
 i = pself.selectedIndex < 0 ? 0 : pself.selectedIndex;
 var pvaluef = pself.options[i].value;
 //
 if (poptions.length == 0) {
  for (i = 0; i < pselt.options.length; ++i) {
   poptions[i] = pselt.options[i];
  }
 }
 pselt.options.length = 1;
 pself.options.length = 1;
 var indext = 0;
 var indexf = 0;
 for (i = 1; i < poptions.length; ++i) {
  // title is set by options.inc.php for data type 14 and will be 'Local' or 'External'.
  var local = poptions[i].title == 'Local';
  // refer_to is nonlocal iff type is outgoing external
  if (rtval == '2' && !local || rtval != '2' && local) {
   if (poptions[i].value == pvaluet) indext = pselt.options.length;
   pselt.options[pselt.options.length] = poptions[i];
  }
  // refer_from is nonlocal iff type is incoming external.
  if (rtval == '4' && !local || rtval != '4' && local) {
   if (poptions[i].value == pvaluef) indexf = pself.options.length;
   // We create another copy of the Option object here because using the
   // same one in both lists causes strange browser behavior.
   pself.options[pself.options.length] = new Option(poptions[i].text, poptions[i].value, false, false);
  }
 }
 pselt.selectedIndex = indext;
 pself.selectedIndex = indexf;
 // Requested service allowed code type depends on the referral type.
 if (ischange && trimlen(f.form_refer_related_code.value) > 0) {
  f.form_refer_related_code.value = '';
  alert('" . xl('Please re-enter the requested service.') . "');
 }
}
";
}

// The purpose of this function is to create JavaScript that is run
// once when the page is loaded.
//
function LBFref_javascript_onload() {
  echo "
external_changed(false);
var f = document.forms[0];
f.form_refer_external.onchange = function () { external_changed(true); };
";
}

?>
