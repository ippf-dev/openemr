<?php
// Copyright (C) 2012 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("billing.inc");

// These variables are used to compute the service with highest CYP.
//
$contraception_billing_code = '';
$contraception_billing_cyp  = -1;
$contraception_billing_prov = 0;

// This is called for each service in the visit to determine the IPPF code
// of the service with highest CYP.
//
function _contraception_billing_check($code_type, $code, $provider) {
  global $code_types;
  global $contraception_billing_code, $contraception_billing_cyp, $contraception_billing_prov;

  $sql = "SELECT related_code FROM codes WHERE " .
    "code_type = '" . $code_types[$code_type]['id'] .
    "' AND code = '$code' LIMIT 1";
  $codesrow = sqlQuery($sql);

  if (!empty($codesrow['related_code']) && $code_type == 'MA') {
    $relcodes = explode(';', $codesrow['related_code']);
    foreach ($relcodes as $relstring) {
      if ($relstring === '') continue;
      list($reltype, $relcode) = explode(':', $relstring);
      if ($reltype !== 'IPPF') continue;
      if (
        // Instead of hard-coding it may be appropriate to use the ippfconmeth
        // list here.  Currently in stlucia the main difference is it does not
        // include EC codes nor those ending in 999.  Discussion needed.
        preg_match('/^11....110/'    , $relcode) ||
        preg_match('/^11...[1-5]999/', $relcode) ||
        preg_match('/^112152010/'    , $relcode) ||
        // preg_match('/^11317[1-2]111/', $relcode) || // removed per Christine 2012-07-27
        preg_match('/^12118[1-2].13/', $relcode) ||
        preg_match('/^121181999/'    , $relcode) ||
        preg_match('/^122182.13/'    , $relcode) ||
        preg_match('/^122182999/'    , $relcode) ||
        preg_match('/^145212.10/'    , $relcode) ||
        preg_match('/^14521.999/'    , $relcode)
      ) {
        $tmprow = sqlQuery("SELECT cyp_factor FROM codes WHERE " .
          "code_type = '11' AND code = '$relcode' LIMIT 1");
        $cyp = 0 + $tmprow['cyp_factor'];
        if ($cyp > $contraception_billing_cyp) {
          $contraception_billing_cyp  = $cyp;
          $contraception_billing_code = $relcode;
          $contraception_billing_prov = $provider;
        }
      }
    }
  }
}

// Get the normalized contraception code $contraception_billing_code, if any,
// indicated by an initial-consult contraception service in the visit.  If
// there is more than one then the code with highest CYP is selected.  This
// call returns TRUE if one is found, otherwise FALSE.  Also set is the
// provider of that service, $contraception_billing_prov, and the corresponding
// CYP value $contraception_billing_cyp.
//
function contraception_billing_scan($patient_id, $encounter_id, $provider_id=0) {
  global $contraception_billing_code, $contraception_billing_cyp, $contraception_billing_prov;

  $contraception_billing_code = '';
  $contraception_billing_cyp  = -1;
  $contraception_billing_prov = 0;

  $billresult = getBillingByEncounter($patient_id, $encounter_id, "*");
  if (is_array($billresult)) {
    foreach ($billresult as $iter) {
      _contraception_billing_check($iter["code_type"], trim($iter["code"]), $iter['provider_id']);
    }
  }

  // If no provider at the line level, use the encounter's default provider.
  if (empty($contraception_billing_prov)) {
    $contraception_billing_prov = 0 + $provider_id;
  }

  // Normalize the IPPF service code to what we use in our list of methods.

  if (!empty($contraception_billing_code)) {
    // A contraception service exists, so will not ask for it or the provider here.
    if (preg_match('/^12/', $contraception_billing_code)) { // surgical methods
      // Identify the method with the IPPF code for the corresponding surgical procedure.
      if ($contraception_billing_code == '121181999') $contraception_billing_code = '121181213';
      if ($contraception_billing_code == '122182999') $contraception_billing_code = '122182213';
      $contraception_billing_code = substr($contraception_billing_code, 0, 7) . '13';
    }
    else { // nonsurgical methods
      // Identify the method with its IPPF code for Initial Consultation.
      $contraception_billing_code = substr($contraception_billing_code, 0, 6) . '110';
      // Xavier confirms that the codes for Cervical Cap (112152010 and 112152011) are
      // an unintended change in pattern, but at this point we have to live with it.
      // -- Rod 2011-09-26
      if ($contraception_billing_code == '112152110') $contraception_billing_code = '112152010';
    }
    return true;
  }
  return false;
}

?>
