<?php
/* Copyright (C) 2015-2016 Rod Roark <rod@sunsetsystems.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 */

// For DataTables documentation see: http://legacy.datatables.net/

$fake_register_globals = false;
$sanitize_all_escapes  = true;

require_once('../../globals.php');
require_once($GLOBALS['srcdir'] . '/patient.inc');
require_once($GLOBALS['srcdir'] . '/csv_like_join.php');
require_once($GLOBALS['fileroot'] . '/custom/code_types.inc.php');

$info_msg = "";
$codetype = empty($_REQUEST['codetype']) ? '' : $_REQUEST['codetype'];
if (!empty($codetype)) $allowed_codes = split_csv_line($codetype);

// This variable is used to store the html element of the target script where the selected code
// will be stored.
$target_element = empty($_GET['target_element']) ? '' : $_GET['target_element'];
?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo xlt('Code Finder'); ?></title>
<link rel="stylesheet" href='<?php echo attr($css_header) ?>' type='text/css'>

<style type="text/css">

@import "<?php echo $GLOBALS['web_root'] ?>/library/js/datatables/media/css/demo_page.css";
@import "<?php echo $GLOBALS['web_root'] ?>/library/js/datatables/media/css/demo_table.css";

</style>

<script type="text/javascript" src="../../../library/js/datatables/media/js/jquery.js"></script>
<script type="text/javascript" src="../../../library/js/datatables/media/js/jquery.dataTables.min.js"></script>

<script language="JavaScript">

var oTable;

$(document).ready(function() {

 // Initializing the DataTable.
 oTable = $('#my_data_table').dataTable({
  "bProcessing": true,
  // Next 2 lines invoke server side processing
  "bServerSide": true,
  "sAjaxSource": "find_code_dynamic_ajax.php",
  // Vertical length options and their default
  "aLengthMenu": [ 10, 25, 50, 100 ],
  "iDisplayLength": 10,
  // Specify a width for the first column.
  "aoColumns": [{"sWidth":"10%"}, null],
  // This callback function passes some form data on each call to the ajax handler.
  "fnServerParams": function (aoData) {
    aoData.push({"name": "codetype", "value": document.forms[0].form_code_type.value});
    aoData.push({"name": "inactive", "value": (document.forms[0].form_include_inactive.checked ? 1 : 0)});
  },
  // Language strings are included so we can translate them
  "oLanguage": {
   "sSearch"      : "<?php echo xla('Search for'); ?>:",
   "sLengthMenu"  : "<?php echo xla('Show') . ' _MENU_ ' . xla('entries'); ?>",
   "sZeroRecords" : "<?php echo xla('No matching records found'); ?>",
   "sInfo"        : "<?php echo xla('Showing') . ' _START_ ' . xla('to{{range}}') . ' _END_ ' . xla('of') . ' _TOTAL_ ' . xla('entries'); ?>",
   "sInfoEmpty"   : "<?php echo xla('Nothing to show'); ?>",
   "sInfoFiltered": "(<?php echo xla('filtered from') . ' _MAX_ ' . xla('total entries'); ?>)",
   "oPaginate"    : {
    "sFirst"      : "<?php echo xla('First'   ); ?>",
    "sPrevious"   : "<?php echo xla('Previous'); ?>",
    "sNext"       : "<?php echo xla('Next'    ); ?>",
    "sLast"       : "<?php echo xla('Last'    ); ?>"
   }
  }
 });

 // OnClick handler for the rows
 $('#my_data_table tbody tr').live('click', function () {
  // this.id is of the form "CID|codetype|code|selector".
  var a = this.id.split('|');
  selcode(a[1], a[2], a[3], a[4]);
 } );

 // Initialize the selector of codes that can be deleted.
 if (opener.get_related) {
  var acodes = opener.get_related();
  var sel = document.forms[0].form_delcodes;
  if (acodes.length > 1) {
   for (var i = 0; i < acodes.length; ++i) {
    sel.options[sel.options.length] = new Option(acodes[i], acodes[i]);
   }
  }
  else {
   sel.style.display = 'none';
  }
 }

});

// Pass info back to the opener and close this window.
function selcode(codetype, code, selector, codedesc) {
 if (opener.closed || ! opener.set_related) {
  alert('<?php echo xls('The destination form was closed; I cannot act on your selection.'); ?>');
 }
 else {
  var msg = opener.set_related(codetype, code, selector, codedesc);
  if (msg) alert(msg);
  window.close();
  return false;
 }
}

// Function to call the opener to delete all or one related code.
function delcode() {
 if (opener.closed || ! opener.del_related) {
  alert('<?php echo xls('The destination form was closed; I cannot act on your selection.'); ?>');
 }
 else {
  var sel = document.forms[0].form_delcodes;
  opener.del_related(sel.value);
  window.close();
  return false;
 }
}

</script>

</head>

<body class="body_top">

<?php
$string_target_element = empty($target_element) ? '?' : "?target_element=" . rawurlencode($target_element) . "&";
?>

<form method='post' name='theform'>

<p>
<?php
if (isset($allowed_codes)) {
  if (count($allowed_codes) == 1) {
    echo "<input type='text' name='form_code_type' value='" . attr($codetype) . "' size='5' readonly>\n";
  } else {
    echo "<select name='form_code_type' onchange='oTable.fnDraw()'>\n";
    foreach ($allowed_codes as $code) {
     	echo " <option value='" . attr($code) . "'>" . xlt($code_types[$code]['label']) . "</option>\n";
    }
    echo "</select>\n";
  }
}
else {
  echo "<select name='form_code_type' onchange='oTable.fnDraw()'>\n";
  foreach ($code_types as $key => $value) {
    echo " <option value='" . attr($key) . "'";
    echo ">" . xlt($value['label']) . "</option>\n";
  }
  echo " <option value='PROD'";
  echo ">" . xlt("Product") . "</option>\n";
  echo "   </select>\n";
}
echo "&nbsp;&nbsp;\n";
echo "<input type='checkbox' name='form_include_inactive' value='1' onclick='oTable.fnDraw()' />" .
  xlt('Include Inactive') . "\n";
echo "&nbsp;&nbsp;\n";
// echo "<input type='button' value='" . xla('Erase') . "' onclick=\"selcode('', '', '', '')\" />\n";
echo "<input type='button' value='" . xla('Delete') . "' onclick='delcode()' />\n";
echo "<select name='form_delcodes'>\n";
echo " <option value=''>" . xlt('All') . "</option>\n";
echo "</select>\n";
?>
</p>

<!-- Class "display" is defined in demo_table.css -->
<table cellpadding="0" cellspacing="0" border="0" class="display" id="my_data_table">
 <thead>
  <tr>
   <th><?php echo xlt('Code'); ?></th>
   <th><?php echo xlt('Description'); ?></th>
  </tr>
 </thead>
 <tbody>
  <tr>
   <!-- Class "dataTables_empty" is defined in jquery.dataTables.css -->
   <td colspan="2" class="dataTables_empty">...</td>
  </tr>
 </tbody>
</table>

</form>
</body>
</html>
