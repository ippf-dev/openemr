<?php
/**
 * Edit Layout Properties.
 *
 * Copyright (C) 2016 Rod Roark <rod@sunsetsystems.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @link    http://www.open-emr.org
 */

$sanitize_all_escapes  = true;
$fake_register_globals = false;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");

$info_msg = "";

// Check authorization.
$thisauth = acl_check('admin', 'super');
if (!$thisauth) die(xl('Not authorized'));

$opt_line_no = intval($_GET['lineno']);
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt("Edit Layout Properties"); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>
td { font-size:10pt; }
</style>

<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>
<script type="text/javascript" src="../../library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>

<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';
var target = opener.document.forms[0]['opt[<?php echo $opt_line_no; ?>][notes]'];

$(document).ready(function () {
  var f = document.forms[0];
  var jobj = {};
  if (target.value.length) {
    try {
      jobj = JSON.parse(target.value);
    }
    catch (e) {
      alert('<?php echo xls('Invalid data, will be ignored and replaced.'); ?>');
    }
  }
  if (jobj['size'    ]) f.form_size.value     = jobj['size'];
  if (jobj['columns' ]) f.form_columns.value  = jobj['columns'];
  if (jobj['issue'   ]) f.form_issue.value    = jobj['issue'];
  if (jobj.hasOwnProperty('services')) {
    f.form_services.checked = true;
    f.form_services_codes.value = jobj['services'];
  }
  if (jobj.hasOwnProperty('products')) {
    f.form_products.checked = true;
    f.form_products_codes.value = jobj['products'];
  }
  if (jobj.hasOwnProperty('diags')) {
    f.form_diags.checked = true;
    f.form_diags_codes.value = jobj['diags'];
  }
});

// The name of the input element to receive a found code.
var current_sel_name = '';

// This invokes the "dynamic" find-code popup.
function sel_related(elem, codetype) {
 current_sel_name = elem ? elem.name : '';
 var url = '<?php echo $rootdir ?>/patient_file/encounter/find_code_dynamic.php';
 if (codetype) url += '?codetype=' + codetype;
 dlgopen(url, '_blank', 800, 500);
}

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
 // frc will be the input element containing the codes.
 var frc = f[current_sel_name];
 var s = frc.value;
 if (code) {
  if (s.length > 0) {
   s  += ';';
  }
  s  += codetype + ':' + code;
 } else {
  s  = '';
 }
 frc.value = s;
 return '';
}

// This is for callback by the find-code popup.
// Deletes the specified codetype:code from the active input element.
function del_related(s) {
  var f = document.forms[0];
  my_del_related(s, f[current_sel_name], false);
}

// This is for callback by the find-code popup.
// Returns the array of currently selected codes with each element in codetype:code format.
function get_related() {
  var f = document.forms[0];
  if (current_sel_name) {
    return f[current_sel_name].value.split(';');
  }
  return new Array();
}

// Onclick handler for Submit button.
function submitProps() {
  var f = document.forms[0];
  var jobj = {};
  if (f.form_size.value          ) jobj['size'    ] = f.form_size.value;
  if (f.form_columns.value != '4') jobj['columns' ] = f.form_columns.value;
  if (f.form_issue.value         ) jobj['issue'   ] = f.form_issue.value;
  if (f.form_services.checked    ) jobj['services'] = f.form_services_codes.value;
  if (f.form_products.checked    ) jobj['products'] = f.form_products_codes.value;
  if (f.form_diags.checked       ) jobj['diags'   ] = f.form_diags_codes.value;
  target.value = JSON.stringify(jobj);
  window.close();
}

</script>

</head>

<body class="body_top">

<form method='post'>
<center>

<table border='0' width='100%'>

 <tr>
  <td valign='top' nowrap>
   <?php echo xlt('Layout Columns'); ?>
  </td>
  <td>
   <select name='form_columns'>
<?php
  for ($cols = 2; $cols <= 10; ++$cols) {
    echo "<option value='$cols'";
    if ($cols == 4) echo " selected";
    echo ">$cols</option>\n";
  }
?>
   </select>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap>
   <?php echo xlt('Font Size'); ?>
  </td>
  <td>
   <select name='form_size'>
<?php
  echo "<option value=''>" . xlt('Default') . "</option>\n";
  for ($size = 5; $size <= 15; ++$size) {
    echo "<option value='$size'";
    echo ">$size</option>\n";
  }
?>
   </select>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap>
   <?php echo xlt('Issue Type'); ?>
  </td>
  <td>
   <select name='form_issue'>
    <option value=''></option>
<?php
  $itres = sqlStatement("SELECT type, singular FROM issue_types " .
    "WHERE category = ? AND active = 1 ORDER BY singular",
    array($GLOBALS['ippf_specific'] ? 'ippf_specific' : 'default'));
  while ($itrow = sqlFetchArray($itres)) {
    echo "<option value='" . attr($itrow['type']) . "'";
    echo ">" . xls($itrow['singular']) . "</option>\n";
  }
?>
   </select>
  </td>
 </tr>

 <tr>
  <td valign='top' width='1%' nowrap>
   <input type='checkbox' name='form_services' />
   <?php echo xls('Show Services Section'); ?>
  </td>
  <td>
   <input type='text' size='40' name='form_services_codes' onclick='sel_related(this, "MA")' />
  </td>
 </tr>

 <tr>
  <td valign='top' width='1%' nowrap>
   <input type='checkbox' name='form_products' />
   <?php echo xls('Show Products Section'); ?>
  </td>
  <td>
   <input type='text' size='40' name='form_products_codes' onclick='sel_related(this, "PROD")' />
  </td>
 </tr>

 <tr>
  <td valign='top' width='1%' nowrap>
   <input type='checkbox' name='form_diags' />
   <?php echo xls('Show Diagnoses Section'); ?>
  </td>
  <td>
   <input type='text' size='40' name='form_diags_codes' onclick='sel_related(this, "ICD10")' />
  </td>
 </tr>

</table>

<p>
<input type='button' value='<?php echo xla('Submit'); ?>' onclick='submitProps()' />

&nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick='window.close()' />
</p>

</center>
</form>
<script language='JavaScript'>
<?php
if ($info_msg) {
  echo " alert('".addslashes($info_msg)."');\n";
  echo " window.close();\n";
}
?>
</script>
</body>
</html>
