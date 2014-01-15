<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

$special_timeout = 3600;
include_once("../../globals.php");
if (substr($_GET["formname"], 0, 3) === 'LBF') {
  // Use the List Based Forms engine for all LBFxxxxx forms.
  include_once("$incdir/forms/LBF/new.php");
}
else {
	if( (!empty($_GET['pid'])) && ($_GET['pid'] > 0) )
	 {
		$pid = $_GET['pid'];
		$encounter = $_GET['encounter'];
	 }
         if($_GET["formname"] != "newpatient" ){
            include_once("$incdir/patient_file/encounter/new_form.php");
         }

  // ensure the path variable has no illegal characters
  check_file_dir_name($_GET["formname"]);

  include_once("$incdir/forms/" . $_GET["formname"] . "/new.php");
}

if (empty($GLOBALS['DUPLICATE_FORM_HANDLED'])) {
  // Determine if an instance of this form already exists in this encounter.
  // If it does, issue a warning message.
  //
  require_once("$srcdir/formdata.inc.php");
  $formname = formData('formname', 'G');
  $row = sqlQuery("SELECT id FROM forms WHERE " .
    "pid = ? AND encounter = ? AND formdir = ? AND " .
    "deleted = 0 ORDER BY id DESC LIMIT 1",array($pid,$encounter,$formname));
  if (!empty($row)) {
    // Yes this comes after the closing </html> tag.  Sorry, but it works.
    echo "<script>alert('" .
      xl('There is already an instance of this form in this visit. Cancel if you do not want another!') .
      "');</script>\n";
  }
}
?>
