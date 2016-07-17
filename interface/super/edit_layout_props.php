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

$layout_id = $_GET['layout_id'];
$lrow = sqlQuery("SELECT notes FROM list_options " .
  "WHERE list_id = 'lbfnames' AND option_id = ?",
  array($layout_id));
if (empty($lrow)) die(xlt('Invalid layout ID!'));

$form_services = false;
$form_services_list = '';
if (preg_match('/\\bservices=([a-zA-Z0-9_-]*)/', $lrow['notes'], $matches)) {
  $form_services = true;
  $form_services_list = $matches[1];
}

$form_products = false;
$form_products_list = '';
if (preg_match('/\\bproducts=([a-zA-Z0-9_-]*)/', $lrow['notes'], $matches)) {
  $form_products = true;
  $form_products_list = $matches[1];
}

$form_diags = false;
$form_diags_list = '';
if (preg_match('/\\bdiags=([a-zA-Z0-9_-]*)/', $lrow['notes'], $matches)) {
  $form_diags = true;
  $form_diags_list = $matches[1];
}

$form_size = '';
if (preg_match('/\\bsize=([0-9]*)/', $lrow['notes'], $matches)) {
  $form_size = intval($matches[1]);
}

$form_columns = '4';
if (preg_match('/\\bcolumns=([0-9]*)/', $lrow['notes'], $matches)) {
  $form_columns = intval($matches[1]);
}

$form_issue = '';
if (preg_match('/\\bissue=([a-zA-Z0-9_-]*)/', $lrow['notes'], $matches)) {
  $form_issue = $matches[1];
}
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt("Edit Layout Properties"); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>
td { font-size:10pt; }
</style>

<style  type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

<script language="JavaScript">

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

// used when selecting a list-name for a field
var selectedfield;

// show the popup choice of lists
function ShowLists(btnObj) {
  window.open('../patient_file/encounter/find_code_dynamic.php?what=lists',
    'lists', 'width=600,height=600,scrollbars=yes');
  selectedfield = btnObj;
};

// Called back from find_code_dynamic.php to set the selected list.
function SetList(listid) {
  $(selectedfield).val(listid);
}

// Onclick handler for Submit button.
function submitProps() {
  var f = document.forms[0];
  var target = opener.document.forms[0]['opt[<?php echo $opt_line_no; ?>][notes]'];
  var s = '';
  if (f.form_size.value) {
    s += 'size=' + f.form_size.value + ' ';
  }
  if (f.form_columns.value) {
    s += 'columns=' + f.form_columns.value + ' ';
  }
  if (f.form_issue.value) {
    s += 'issue=' + f.form_issue.value + ' ';
  }
  if (f.form_services.checked) {
    s += 'services=' + f.form_services_list.value + ' ';
  }
  if (f.form_products.checked) {
    s += 'products=' + f.form_products_list.value + ' ';
  }
  if (f.form_diags.checked) {
    s += 'diags=' + f.form_diags_list.value + ' ';
  }
  if (s.length) s = s.substr(0, s.length - 1);
  target.value = s;
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
    if ($cols == $form_columns) echo " selected";
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
    if ($size == $form_size) echo " selected";
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
    if ($itrow['type'] == $form_issue) echo " selected";
    echo ">" . xls($itrow['singular']) . "</option>\n";
  }
?>
   </select>
  </td>
 </tr>

 <tr>
  <td valign='top' width='1%' nowrap>
   <input type='checkbox' name='form_services' <?php if ($form_services) echo 'checked'; ?> />
   <?php echo xls('Show Services Section'); ?>
  </td>
  <td>
   <input type='text' size='40' name='form_services_list' maxlength='30'
    value='<?php echo attr($form_services_list); ?>' onclick='ShowLists(this)' />
  </td>
 </tr>

 <tr>
  <td valign='top' width='1%' nowrap>
   <input type='checkbox' name='form_products' <?php if ($form_products) echo 'checked'; ?> />
   <?php echo xls('Show Products Section'); ?>
  </td>
  <td>
   <input type='text' size='40' name='form_products_list' maxlength='30'
    value='<?php echo attr($form_products_list); ?>' onclick='ShowLists(this)' />
  </td>
 </tr>

 <tr>
  <td valign='top' width='1%' nowrap>
   <input type='checkbox' name='form_diags' <?php if ($form_diags) echo 'checked'; ?> />
   <?php echo xls('Show Diagnoses Section'); ?>
  </td>
  <td>
   <input type='text' size='40' name='form_diags_list' maxlength='30'
    value='<?php echo attr($form_diags_list); ?>' onclick='ShowLists(this)' />
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
