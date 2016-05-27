<?php
// Copyright (C) 2010-2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This provides enhancement functions for the LBFVitals visit form,
// "Vitals".  It is invoked by interface/forms/LBF/new.php.

// The purpose of this function is to create JavaScript for the <head>
// section of the page.  This in turn defines desired javaScript
// functions.
//
function LBFVitals_javascript() {
  global $formid;

  echo "// Compute Body Mass Index.
function VitalsComputeBMI() {
 var f = document.forms[0];
 var bmi = 0;
 var stat = '';
 var height = parseFloat(f.form_VIT_Height_in.value);
 var weight = parseFloat(f.form_VIT_Weight_lb.value);
 if(isNaN(height) || isNaN(weight) || height <= 0 || weight <= 0) {
  bmi = '';
 }
 else {
  bmi = weight / height / height * 703;
  bmi = bmi.toFixed(1);
  if      (bmi > 42  ) stat = '" . xl('Obesity III') . "';
  else if (bmi > 34  ) stat = '" . xl('Obesity II' ) . "';
  else if (bmi > 30  ) stat = '" . xl('Obesity I'  ) . "';
  else if (bmi > 27  ) stat = '" . xl('Overweight' ) . "';
  else if (bmi > 18.5) stat = '" . xl('Normal'     ) . "';
  else                 stat = '" . xl('Underweight') . "';
 }
 if (f.form_VIT_BMI) f.form_VIT_BMI.value = bmi;
 if (form_VIT_BMI_status) f.form_VIT_BMI_status.value = stat;
}
";

/**********************************************************************
  echo "// Compute Waist Index.
function VitalsComputeWI() {
 var f = document.forms[0];
 var wi = 0;
 var height = parseFloat(f.form_VIT_Height_in.value);
 var waist  = parseFloat(f.form_VIT_Waist_circum_in.value);
 if(isNaN(height) || isNaN(waist) || height <= 0 || waist <= 0) {
  wi = '';
 }
 else {
  wi = waist / height;
  wi = wi.toFixed(1);
 if (f.form_VIT_Waist_index) f.form_VIT_Waist_index.value = bmi;
}
";
**********************************************************************/

 echo "// Some measurement in inches has changed.
function Vitals_in_changed(field) {
 var f = document.forms[0];
 var inch = f['form_' + field + '_in'].value;
 if (inch == parseFloat(inch)) {
  cm = inch * 2.54;
  f['form_' + field + '_cm'].value = cm.toFixed(2);
 }
 else {
  f['form_' + field + '_cm'].value = '';
 }
}
";

  echo "// Some measurement in cm has changed.
function Vitals_cm_changed(field) {
 var f = document.forms[0];
 var cm = f['form_' + field + '_cm'].value;
 if (cm == parseFloat(cm)) {
  inch = cm / 2.54;
  f['form_' + field + '_in'].value = inch.toFixed(2);
 }
 else {
  f['form_' + field + '_in'].value = '';
 }
}
";

  echo "// Height in cm has changed.
function Vitals_height_cm_changed() {
 Vitals_cm_changed('VIT_Height');
 VitalsComputeBMI();
}
";

  echo "// Height in inches has changed.
function Vitals_height_in_changed() {
 Vitals_in_changed('VIT_Height');
 VitalsComputeBMI();
}
";

  echo "// Weight in kg has changed.
function Vitals_weight_kg_changed() {
 var f = document.forms[0];
 var kg = f.form_VIT_Weight_kg.value;
 if (kg == parseFloat(kg)) {
  lbs = kg / 0.45359237;
  f.form_VIT_Weight_lb.value = lbs.toFixed(2);
 }
 else {
  f.form_VIT_Weight_lb.value = '';
 }
 VitalsComputeBMI();
}
";

  echo "// Weight in lbs has changed.
function Vitals_weight_lbs_changed() {
 var f = document.forms[0];
 var lbs = f.form_VIT_Weight_lb.value;
 if (lbs == parseFloat(lbs)) {
  kg = lbs * 0.45359237;
  f.form_VIT_Weight_kg.value = kg.toFixed(2);
 }
 else {
  f.form_VIT_Weight_kg.value = '';
 }
 VitalsComputeBMI();
}
";

  echo "// Temperature in centigrade has changed.
function Vitals_temperature_c_changed() {
 var f = document.forms[0];
 var tc = f.form_VIT_TempC.value;
 if (tc == parseFloat(tc)) {
  tf = tc * 9 / 5 + 32;
  f.form_VIT_TempF.value = tf.toFixed(2);
 }
 else {
  f.form_VIT_TempF.value = '';
 }
}
";

  echo "// Temperature in farenheit has changed.
function Vitals_temperature_f_changed() {
 var f = document.forms[0];
 var tf = f.form_VIT_TempF.value;
 if (tf == parseFloat(tf)) {
  tc = (tf - 32) * 5 / 9;
  f.form_VIT_TempC.value = tc.toFixed(2);
 }
 else {
  f.form_VIT_TempC.value = '';
 }
}
";

  echo "// Head circumference in cm has changed.
function Vitals_Head_circum_cm_changed() {
 Vitals_cm_changed('VIT_Head_circum');
}
";

  echo "// Head circumference in inches has changed.
function Vitals_Head_circum_in_changed() {
 Vitals_in_changed('VIT_Head_circum');
}
";

  echo "// Waist circumference in cm has changed.
function Vitals_Waist_circum_cm_changed() {
 Vitals_cm_changed('VIT_Waist_circum');
}
";

  echo "// Waist circumference in inches has changed.
function Vitals_Waist_circum_in_changed() {
 Vitals_in_changed('VIT_Waist_circum');
}
";

  echo "// Hip circumference in cm has changed.
function Vitals_Hip_circum_cm_changed() {
 Vitals_cm_changed('VIT_Hip_circum');
}
";

  echo "// Hip circumference in inches has changed.
function Vitals_Hip_circum_in_changed() {
 Vitals_in_changed('VIT_Hip_circum');
}
";

}

// The purpose of this function is to create JavaScript that is run
// once when the page is loaded.
//
function LBFVitals_javascript_onload() {

  echo "
var f = document.forms[0];
if (f.form_VIT_Weight_lb && f.form_VIT_Weight_kg) {
 // Set onchange handlers to convert kg to lbs and vice versa.
 f.form_VIT_Weight_lb.onchange = function () { Vitals_weight_lbs_changed(); };
 f.form_VIT_Weight_kg.onchange  = function () { Vitals_weight_kg_changed() ; };
}
if (f.form_VIT_Height_in && f.form_VIT_Height_cm) {
 // Set onchange handlers to convert centimeters to inches and vice versa.
 f.form_VIT_Height_in.onchange = function () { Vitals_height_in_changed(); };
 f.form_VIT_Height_cm.onchange = function () { Vitals_height_cm_changed(); };
}
if (f.form_VIT_TempF && f.form_VIT_TempC) {
 // Set onchange handlers to convert centigrade to farenheit and vice versa.
 f.form_VIT_TempF.onchange = function () { Vitals_temperature_f_changed(); };
 f.form_VIT_TempC.onchange = function () { Vitals_temperature_c_changed(); };
}
if (f.form_VIT_Head_circum_in && f.form_VIT_Head_circum_cm) {
 // Set onchange handlers to convert centimeters to inches and vice versa.
 f.form_VIT_Head_circum_in.onchange = function () { Vitals_Head_circum_in_changed(); };
 f.form_VIT_Head_circum_cm.onchange = function () { Vitals_Head_circum_cm_changed(); };
}
if (f.form_VIT_Waist_circum_in && f.form_VIT_Waist_circum_cm) {
 // Set onchange handlers to convert centimeters to inches and vice versa.
 f.form_VIT_Waist_circum_in.onchange = function () { Vitals_Waist_circum_in_changed(); };
 f.form_VIT_Waist_circum_cm.onchange = function () { Vitals_Waist_circum_cm_changed(); };
}
if (f.form_VIT_Hip_circum_in && f.form_VIT_Hip_circum_cm) {
 // Set onchange handlers to convert centimeters to inches and vice versa.
 f.form_VIT_Hip_circum_in.onchange = function () { Vitals_Hip_circum_in_changed(); };
 f.form_VIT_Hip_circum_cm.onchange = function () { Vitals_Hip_circum_cm_changed(); };
}
// Set computed fields to be readonly.
if (f.form_VIT_BMI) {
 f.form_VIT_BMI.readOnly = true;
}
if (f.form_VIT_BMI_status) {
 f.form_VIT_BMI_status.readOnly = true;
}
";

}
// PHP closing tag omitted.
