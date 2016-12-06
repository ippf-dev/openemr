<?php
/* Copyright (C) 2016 Rod Roark <rod@sunsetsystems.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 */

$fake_register_globals = false;
$sanitize_all_escapes  = true;

require_once('../globals.php');
require_once("$srcdir/options.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");
?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo xlt('Void Dialog'); ?></title>
<link rel="stylesheet" href='<?php echo attr($css_header) ?>' type='text/css'>

<script language="JavaScript">

function DoSubmit() {
  if (opener.closed || !opener.voidwrap) {
    alert('The destination form was closed; I cannot act on your selection.');
  }
  else {
    var f = document.forms[0];
    opener.voidwrap(f.form_reason.value, f.form_notes.value);
  }
  window.close();
  return false;
};

</script>

</head>

<body class="body_top">

<form method='post'>

<center>

<table border='0'>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Void Reason'); ?>:</b></td>
  <td>
<?php
 generate_form_field(array('data_type'=>1,'field_id'=>'reason','list_id'=>'void_reasons','empty_title'=>'Select Reason'));
?>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Void Notes'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_notes' maxlength='80' value='<?php echo attr($row['notes']) ?>' style='width:100%' />
  </td>
 </tr>

</table>

<?php
echo "<p>\n";
echo "<input type='button' value='" . xla('Submit') . "' onclick='DoSubmit()' />&nbsp;\n";
echo "<input type='button' value='" . xla('Cancel') . "' onclick='window.close()' />\n";
echo "</p>\n";
?>

</center>

</form>
</body>
</html>
