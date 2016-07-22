<?php
/**
 * Copyright (C) 2009-2016 Rod Roark <rod@sunsetsystems.com>
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
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>.
 *
 * @package OpenEMR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @link    http://www.open-emr.org
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once("../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once($GLOBALS['fileroot'] . '/custom/code_types.inc.php');
require_once("$srcdir/FeeSheetHtml.class.php");

$CPR = 4; // cells per row

$pprow = array();

$alertmsg = '';

function end_cell() {
  global $item_count, $cell_count, $historical_ids;
  if ($item_count > 0) {
    // echo "&nbsp;</td>";
    echo "</td>";

    foreach ($historical_ids as $key => $dummy) {
      // $historical_ids[$key] .= "&nbsp;</td>";
      $historical_ids[$key] .= "</td>";
    }

    $item_count = 0;
  }
}

function end_row() {
  global $cell_count, $CPR, $historical_ids;
  end_cell();
  if ($cell_count > 0) {
    for (; $cell_count < $CPR; ++$cell_count) {
      echo "<td></td>";
      foreach ($historical_ids as $key => $dummy) {
        $historical_ids[$key] .= "<td></td>";
      }
    }

    foreach ($historical_ids as $key => $dummy) {
      echo $historical_ids[$key];
    }

    echo "</tr>\n";
    $cell_count = 0;
  }
}

// $is_lbf is defined in trend_form.php and indicates that we are being
// invoked from there; in that case the current encounter is irrelevant.
$from_trend_form = !empty($is_lbf);

// This is true if the page is loaded into an iframe in add_edit_issue.php.
$from_issue_form = !empty($_REQUEST['from_issue_form']);

$formname = isset($_GET['formname']) ? $_GET['formname'] : '';
$formid = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$visitid = intval(empty($_GET['visitid']) ? $encounter : $_GET['visitid']);

// If necessary get the encounter from the forms table entry for this form.
if ($formid && !$visitid) {
  $frow = sqlQuery("SELECT pid, encounter FROM forms WHERE " .
    "form_id = ? AND formdir = ? AND deleted = 0",
    array($formid, $formname));
  $visitid = intval($frow['encounter']);
  if ($frow['pid'] != $pid) die("Internal error: patient ID mismatch!");
}

if (!$from_trend_form && !$visitid) {
  die("Internal error: we do not seem to be in an encounter!");
}

// Get some info about this form.
$tmp = sqlQuery("SELECT title, option_value, notes FROM list_options WHERE " .
  "list_id = 'lbfnames' AND option_id = ?", array($formname) );
$formtitle = $tmp['title'];
$formhistory = 0 + $tmp['option_value'];
if (preg_match('/columns=([0-9]+)/', $tmp['notes'], $matches)) {
  if ($matches[1] > 0 && $matches[1] < 13) $CPR = intval($matches[1]);
}
if (preg_match('/\\bservices=([a-zA-Z0-9_-]*)/', $tmp['notes'], $matches)) {
  // Note if this is defined then we will make a Services section on the page.
  $LBF_SERVICES_SECTION = $matches[1];
}
if (preg_match('/\\bproducts=([a-zA-Z0-9_-]*)/', $tmp['notes'], $matches)) {
  // Note if this is defined then we will make a Products section on the page.
  $LBF_PRODUCTS_SECTION = $matches[1];
}
if (preg_match('/\\bdiags=([a-zA-Z0-9_-]*)/', $tmp['notes'], $matches)) {
  // Note if this is defined then we will make a Diagnoses section on the page.
  $LBF_DIAGS_SECTION = $matches[1];
}

if (preg_match('/\\bissue=([a-zA-Z0-9_-]*)/', $tmp['notes'], $matches)) {
  // Note if defined this is an issue type associated with the LBF.
  $LBF_ISSUE_TYPE = $matches[1];
}

if (isset($LBF_SERVICES_SECTION) || isset($LBF_PRODUCTS_SECTION) || isset($LBF_DIAGS_SECTION)) {
  $fs = new FeeSheetHtml($pid, $visitid);
}

if (!$from_trend_form) {
  $fname = $GLOBALS['OE_SITE_DIR'] . "/LBF/$formname.plugin.php";
  if (file_exists($fname)) include_once($fname);
}

// If Save was clicked, save the info.
//
if (!empty($_POST['bn_save']) || !empty($_POST['bn_save_print'])) {
  $newid = 0;
  if (!$formid) {
    // Creating a new form. Get the new form_id by inserting and deleting a dummy row.
    // This is necessary to create the form instance even if it has no native data.
    $newid = sqlInsert("INSERT INTO lbf_data " .
      "( field_id, field_value ) VALUES ( '', '' )");
    sqlStatement("DELETE FROM lbf_data WHERE form_id = ? AND " .
      "field_id = ''", array($newid));
    addForm($visitid, $formtitle, $newid, $formname, $pid, $userauthorized);
  }

  $my_form_id = $formid ? $formid : $newid;

  // If there is an issue ID, update it in the forms table entry.
  if (isset($_POST['form_issue_id'])) {
    sqlStatement("UPDATE forms SET issue_id = ? WHERE formdir = ? AND form_id = ? AND deleted = 0",
      array($_POST['form_issue_id'], $formname, $my_form_id));
  }

  // If there is a provider ID, update it in the forms table entry.
  if (isset($_POST['form_provider_id'])) {
    sqlStatement("UPDATE forms SET provider_id = ? WHERE formdir = ? AND form_id = ? AND deleted = 0",
      array($_POST['form_provider_id'], $formname, $my_form_id));
  }

  $sets = "";
  $fres = sqlStatement("SELECT * FROM layout_options " .
    "WHERE form_id = ? AND uor > 0 AND field_id != '' AND " .
    "edit_options != 'H' AND edit_options NOT LIKE '%0%' " .
    "ORDER BY group_name, seq", array($formname) );
  while ($frow = sqlFetchArray($fres)) {
    $field_id  = $frow['field_id'];
    $data_type = $frow['data_type'];
    // If the field was not in the web form, skip it.
    // Except if it's checkboxes, if unchecked they are not returned.
    if ($data_type != 21 && !isset($_POST["form_$field_id"])) continue;
    $value = get_layout_form_value($frow);
    // If edit option P or Q, save to the appropriate different table and skip the rest.
    $source = $frow['source'];
    if ($source == 'D' || $source == 'H') {
      // Save to patient_data, employer_data or history_data.
      if ($source == 'H') {
        $new = array($field_id => $value);
        updateHistoryData($pid, $new);
      }
      else if (strpos($field_id, 'em_') === 0) {
        $field_id = substr($field_id, 3);
        $new = array($field_id => $value);
        updateEmployerData($pid, $new);
      }
      else {
        sqlStatement("UPDATE patient_data SET `$field_id` = ? WHERE pid = ?",
          array($value, $pid));
      }
      continue;
    }
    else if ($source == 'E') {
      // Save to shared_attributes. Can't delete entries for empty fields because with the P option
      // it's important to know when a current empty value overrides a previous value.
      sqlStatement("REPLACE INTO shared_attributes SET " .
        "pid = ?, encounter = ?, field_id = ?, last_update = NOW(), " .
        "user_id = ?, field_value = ?",
        array($pid, $visitid, $field_id, $_SESSION['authUserID'], $value));
      continue;
    }
    else if ($source == 'V') {
      // Save to form_encounter.
      sqlStatement("UPDATE form_encounter SET `$field_id` = ? WHERE " .
        "pid = ? AND encounter = ?",
        array($value, $pid, $visitid));
      continue;
    }
    // It's a normal form field, save to lbf_data.
    if ($formid) { // existing form
      if ($value === '') {
        $query = "DELETE FROM lbf_data WHERE " .
          "form_id = ? AND field_id = ?";
        sqlStatement($query, array($formid, $field_id));
      }
      else {
        $query = "REPLACE INTO lbf_data SET field_value = ?, " .
          "form_id = ?, field_id = ?";
        sqlStatement($query,array($value, $formid, $field_id));
      }
    }
    else { // new form
      if ($value !== '') {
        sqlStatement("INSERT INTO lbf_data " .
          "( form_id, field_id, field_value ) VALUES ( ?, ?, ? )",
          array($newid, $field_id, $value));
      }
    }
  }

  if (isset($fs)) {
    $bill = is_array($_POST['form_fs_bill']) ? $_POST['form_fs_bill'] : null;
    $prod = is_array($_POST['form_fs_prod']) ? $_POST['form_fs_prod'] : null;
    $alertmsg = $fs->checkInventory($prod);
    // If there is an inventory error then no services or products will be saved, and
    // the form will be redisplayed with an error alert and everything else saved.
    if (!$alertmsg) {
      $fs->save($bill, $prod, NULL, NULL);
      $fs->updatePriceLevel($_POST['form_fs_pricelevel']);
    }
  }

  if (!$alertmsg && !$from_issue_form) {
    // Support custom behavior at save time, such as going to another form.
    if (function_exists($formname . '_save_exit')) {
      if (call_user_func($formname . '_save_exit')) exit;
    }
    formHeader("Redirecting....");
    // If Save and Print, write the JavaScript to open a window for printing.
    if (!$formid) $formid = $newid;
    if (!empty($_POST['bn_save_print'])) {
      echo "<script language='Javascript'>\n"            .
        "top.restoreSession();\n"                        .
        "window.open('$rootdir/forms/LBF/printable.php?" .
        "formname="   . urlencode($formname )            .
        "&formid="    . urlencode($formid   )            .
        "&visitid="   . urlencode($visitid)         .
        "&patientid=" . urlencode($pid      )            .
        "', '_blank');\n"                                .
        "</script>\n";
    }
    formJump();
    formFooter();
    exit;
  }
}

?>
<html>
<head>
<?php html_header_show();?>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<style>

td, input, select, textarea {
 font-family: Arial, Helvetica, sans-serif;
 font-size: 10pt;
}

div.section {
 border: solid;
 border-width: 1px;
 border-color: #0000ff;
 margin: 0 0 0 10pt;
 padding: 5pt;
}

</style>

<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>

<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.easydrag.handler.beta2.js"></script>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/options.js.php"); ?>

<!-- LiterallyCanvas support -->
<?php echo lbf_canvas_head(); ?>

<script language="JavaScript">

// Support for beforeunload handler.
var somethingChanged = false;

$(document).ready(function() {

  // fancy box
  if (window.enable_modals) {
    enable_modals();
  }
  if(window.tabbify){
    tabbify();
  }
  if (window.checkSkipConditions) {
    checkSkipConditions();
  }
  // special size for
  $(".iframe_medium").fancybox({
    'overlayOpacity' : 0.0,
    'showCloseButton' : true,
    'frameHeight' : 580,
    'frameWidth' : 900
  });
  $(function() {
    // add drag and drop functionality to fancybox
    $("#fancy_outer").easydrag();
  });

  // Support for beforeunload handler.
  $('.lbfdata input, .lbfdata select, .lbfdata textarea').change(function() {
    somethingChanged = true;
  });
  window.addEventListener("beforeunload", function (e) {
    if (somethingChanged && !top.timed_out) {
      var msg = "<?php echo xls('You have unsaved changes.'); ?>";
      e.returnValue = msg;     // Gecko, Trident, Chrome 34+
      return msg;              // Gecko, WebKit, Chrome <34
    }
  });

});

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

// Supports customizable forms.
function divclick(cb, divid) {
 var divstyle = document.getElementById(divid).style;
 if (cb.checked) {
  divstyle.display = 'block';
 } else {
  divstyle.display = 'none';
 }
 return true;
}

// The ID of the input element to receive a found code.
var current_sel_name = '';

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
<?php if (isset($fs)) { ?>
 // This is the case of selecting a code for the Fee Sheet:
 if (!current_sel_name) {
  if (code) {
   $.getScript('<?php echo $GLOBALS['web_root'] ?>/library/ajax/code_attributes_ajax.php' +
    '?codetype='   + encodeURIComponent(codetype) +
    '&code='       + encodeURIComponent(code) +
    '&selector='   + encodeURIComponent(selector) +
    '&pricelevel=' + encodeURIComponent(f.form_fs_pricelevel.value));
  }
  return '';
 }
<?php } ?>
 // frc will be the input element containing the codes.
 // frcd, if set, will be the input element containing their descriptions.
 var frc = f[current_sel_name];
 var frcd;
 var matches = current_sel_name.match(/^(.*)__desc$/);
 if (matches) {
  frcd = frc;
  frc  = f[matches[1]];
 }
 // For LBFs we will allow only one code in a field.
 var s = ''; // frc.value;
 var sd = ''; // frcd ? frcd.value : s;
 if (code) {
  if (codetype != 'PROD') {
   if (s.indexOf(codetype + ':') == 0 || s.indexOf(';' + codetype + ':') > 0) {
    return '<?php echo xl('A code of this type is already selected. Erase the field first if you need to replace it.') ?>';
   }
  }     
  if (s.length > 0) {
   s  += ';';
   sd += ';';
  }
  s  += codetype + ':' + code;
  sd += codedesc;
 } else {
  s  = '';
  sd = '';
 }
 frc.value = s;
 if (frcd) frcd.value = sd;
 return '';
}

// This invokes the "dynamic" find-code popup.
function sel_related(elem, codetype) {
 current_sel_name = elem ? elem.name : '';
 var url = '<?php echo $rootdir ?>/patient_file/encounter/find_code_dynamic.php';
 if (codetype) url += '?codetype=' + codetype;
 dlgopen(url, '_blank', 800, 500);
}

// Compute the length of a string without leading and trailing spaces.
function trimlen(s) {
 var i = 0;
 var j = s.length - 1;
 for (; i <= j && s.charAt(i) == ' '; ++i);
 for (; i <= j && s.charAt(j) == ' '; --j);
 if (i > j) return 0;
 return j + 1 - i;
}

// This capitalizes the first letter of each word in the passed input
// element.  It also strips out extraneous spaces.
function capitalizeMe(elem) {
 var a = elem.value.split(' ');
 var s = '';
 for(var i = 0; i < a.length; ++i) {
  if (a[i].length > 0) {
   if (s.length > 0) s += ' ';
   s += a[i].charAt(0).toUpperCase() + a[i].substring(1);
  }
 }
 elem.value = s;
}

// Validation logic for form submission.
function validate(f) {
<?php generate_layout_validation($formname); ?>

 // Validation for Fee Sheet stuff. Skipping this because CV decided (2015-11-03)
 // that these warning messages are not appropriate for layout based visit forms.
 //
 // if (window.jsLineItemValidation && !jsLineItemValidation(f)) return false;

 somethingChanged = false; // turn off "are you sure you want to leave"
 top.restoreSession();
 return true;
}

<?php
if (isset($fs)) { 
  // jsLineItemValidation() function for the fee sheet stuff.
  echo $fs->jsLineItemValidation('form_fs_bill', 'form_fs_prod');
?>

// Add a service line item.
function fs_append_service(code_type, code, desc, price) {
 var telem = document.getElementById('fs_services_table');
 var lino = telem.rows.length - 1;
 var trelem = telem.insertRow(telem.rows.length);
 trelem.innerHTML =
  "<td class='text'>" + code + "&nbsp;</td>" +
  "<td class='text'>" + desc + "&nbsp;</td>" +
  "<td class='text'>" +
  "<select name='form_fs_bill[" + lino + "][provid]'>" +
  "<?php echo addslashes($fs->genProviderOptionList('-- ' . xl('Default') . ' --')) ?>" +
  "</select>&nbsp;" +
  "</td>" +
  "<td class='text' align='right'>" + price + "&nbsp;</td>" +
  "<td class='text' align='right'>" +
  "<input type='checkbox' name='form_fs_bill[" + lino + "][del]' value='1' />" +
  "<input type='hidden' name='form_fs_bill[" + lino + "][code_type]' value='" + code_type + "' />" +
  "<input type='hidden' name='form_fs_bill[" + lino + "][code]'      value='" + code      + "' />" +
  "<input type='hidden' name='form_fs_bill[" + lino + "][price]'     value='" + price     + "' />" +
  "</td>";
}

// Add a product line item.
function fs_append_product(code_type, code, desc, price, warehouses) {
 var telem = document.getElementById('fs_products_table');
 var lino = telem.rows.length - 1;
 var trelem = telem.insertRow(telem.rows.length);
 trelem.innerHTML =
  "<td class='text'>" + desc + "&nbsp;</td>" +
  "<td class='text'>" +
  "<select name='form_fs_prod[" + lino + "][warehouse]'>" + warehouses + "</select>&nbsp;" +
  "</td>" +
  "<td class='text' align='right'>" +
  "<input type='text' name='form_fs_prod[" + lino + "][units]' size='3' value='1' />&nbsp;" +
  "</td>" +
  "<td class='text' align='right'>" + price + "&nbsp;</td>" +
  "<td class='text' align='right'>" +
  "<input type='checkbox' name='form_fs_prod[" + lino + "][del]'     value='1' />" +
  "<input type='hidden'   name='form_fs_prod[" + lino + "][drug_id]' value='" + code      + "' />" +
  "<input type='hidden'   name='form_fs_prod[" + lino + "][price]'   value='" + price     + "' />" +
  "</td>";
}

// Add a diagnosis line item.
function fs_append_diag(code_type, code, desc) {
 var telem = document.getElementById('fs_diags_table');
 // Adding 1000 because form_fs_bill[] is shared with services and we want to avoid collisions.
 var lino = telem.rows.length - 1 + 1000;
 var trelem = telem.insertRow(telem.rows.length);
 trelem.innerHTML =
  "<td class='text'>" + code + "&nbsp;</td>" +
  "<td class='text'>" + desc + "&nbsp;</td>" +
  "<td class='text' align='right'>" +
  "<input type='checkbox' name='form_fs_bill[" + lino + "][del]' value='1' />" +
  "<input type='hidden' name='form_fs_bill[" + lino + "][code_type]' value='" + code_type + "' />" +
  "<input type='hidden' name='form_fs_bill[" + lino + "][code]'      value='" + code      + "' />" +
  "<input type='hidden' name='form_fs_bill[" + lino + "][price]'     value='" + 0         + "' />" +
  "</td>";
}

// Respond to clicking a checkbox for adding (or removing) a specific service.
function fs_service_clicked(cb) {
  var f = cb.form;
  // The checkbox value is a JSON array containing the service's code type, code, description,
  // and price for each price level.
  var a = JSON.parse(cb.value);
  if (!cb.checked) {
    // The checkbox was UNchecked.
    // Find last row with a matching code_type and code and set its del flag.
    var telem = document.getElementById('fs_services_table');
    var lino = telem.rows.length - 2;
    for (; lino >= 0; --lino) {
      var pfx = "form_fs_bill[" + lino + "]";
      if (f[pfx + "[code_type]"].value == a[0] && f[pfx + "[code]"].value == a[1]) {
        f[pfx + "[del]"].checked = true;
        break;
      }
    }
    return;
  }
  $.getScript('<?php echo $GLOBALS['web_root'] ?>/library/ajax/code_attributes_ajax.php' +
    '?codetype='   + encodeURIComponent(a[0]) +
    '&code='       + encodeURIComponent(a[1]) +
    '&pricelevel=' + encodeURIComponent(f.form_fs_pricelevel.value));
}

// Respond to clicking a checkbox for adding (or removing) a specific product.
function fs_product_clicked(cb) {
  var f = cb.form;
  // The checkbox value is a JSON array containing the product's code type, code and selector.
  var a = JSON.parse(cb.value);
  if (!cb.checked) {
    // The checkbox was UNchecked.
    // Find last row with a matching product ID and set its del flag.
    var telem = document.getElementById('fs_products_table');
    var lino = telem.rows.length - 2;
    for (; lino >= 0; --lino) {
      var pfx = "form_fs_prod[" + lino + "]";
      if (f[pfx + "[code_type]"].value == a[0] && f[pfx + "[code]"].value == a[1]) {
        f[pfx + "[del]"].checked = true;
        break;
      }
    }
    return;
  }
  $.getScript('<?php echo $GLOBALS['web_root'] ?>/library/ajax/code_attributes_ajax.php' +
    '?codetype='   + encodeURIComponent(a[0]) +
    '&code='       + encodeURIComponent(a[1]) +
    '&selector='   + encodeURIComponent(a[2]) +
    '&pricelevel=' + encodeURIComponent(f.form_fs_pricelevel.value));
}

// Respond to clicking a checkbox for adding (or removing) a specific diagnosis.
function fs_diag_clicked(cb) {
  var f = cb.form;
  // The checkbox value is a JSON array containing the diagnosis's code type, code, description.
  var a = JSON.parse(cb.value);
  if (!cb.checked) {
    // The checkbox was UNchecked.
    // Find last row with a matching code_type and code and set its del flag.
    var telem = document.getElementById('fs_diags_table');
    var lino = telem.rows.length - 2 + 1000;
    for (; lino >= 0; --lino) {
      var pfx = "form_fs_bill[" + lino + "]";
      if (f[pfx + "[code_type]"].value == a[0] && f[pfx + "[code]"].value == a[1]) {
        f[pfx + "[del]"].checked = true;
        break;
      }
    }
    return;
  }
  $.getScript('<?php echo $GLOBALS['web_root'] ?>/library/ajax/code_attributes_ajax.php' +
    '?codetype='   + encodeURIComponent(a[0]) +
    '&code='       + encodeURIComponent(a[1]) +
    '&pricelevel=' + encodeURIComponent(f.form_fs_pricelevel.value));
}

// This is called back by code_attributes_ajax.php to complete the appending of a line item.
function code_attributes_handler(codetype, code, desc, price, warehouses) {
 if (codetype == 'PROD') {
  fs_append_product(codetype, code, desc, price, warehouses);
 }
 else if (codetype == 'ICD9' || codetype == 'ICD10') {
  fs_append_diag(codetype, code, desc);
 }
 else {
  fs_append_service(codetype, code, desc, price);
 }
}

function warehouse_changed(sel) {
 if (!confirm('<?php echo xl('Do you really want to change Warehouse?'); ?>')) {
  // They clicked Cancel so reset selection to its default state.
  for (var i = 0; i < sel.options.length; ++i) {
   sel.options[i].selected = sel.options[i].defaultSelected;
  }
 }
}

<?php } // end if (isset($fs)) ?>

<?php if (function_exists($formname . '_javascript')) call_user_func($formname . '_javascript'); ?>

</script>
</head>

<body <?php echo $top_bg_line; if ($from_issue_form) echo " style='background-color: #ffffff'"; ?>
 topmargin="0" rightmargin="0" leftmargin="2" bottommargin="0" marginwidth="2" marginheight="0">
<form method="post" action="<?php echo $rootdir ?>/forms/LBF/new.php?formname=<?php echo attr($formname) ?>&id=<?php echo attr($formid) ?>"
 onsubmit="return validate(this)">

<?php
  if (!$from_trend_form) {
    echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";

    echo " <td class='title' style='padding-top:8px;padding-bottom:8px;text-align:left'>\n";
    $enrow = sqlQuery("SELECT p.fname, p.mname, p.lname, fe.date FROM " .
      "form_encounter AS fe, forms AS f, patient_data AS p WHERE " .
      "p.pid = ? AND f.pid = ? AND f.encounter = ? AND " .
      "f.formdir = 'newpatient' AND f.deleted = 0 AND " .
      "fe.id = f.form_id LIMIT 1", array($pid, $pid, $visitid));
    // echo "<p class='title' style='margin-top:8px;margin-bottom:8px;text-align:center'>\n";
    echo text($formtitle) . " " . xlt('for') . ' ';
    echo text($enrow['fname']) . ' ' . text($enrow['mname']) . ' ' . text($enrow['lname']);
    echo ' ' . xlt('on') . ' ' . text(oeFormatShortDate(substr($enrow['date'], 0, 10)));
    // echo "</p>\n";
    echo " </td>\n";

    echo " <td class='title' style='padding-top:8px;padding-bottom:8px;text-align:right'>\n";

    $firow = sqlQuery("SELECT issue_id, provider_id FROM forms WHERE " .
      "formdir = ? AND form_id = ? AND deleted = 0",
      array($formname, $formid));
    $form_issue_id = empty($firow['issue_id']) ? 0 : intval($firow['issue_id']);
    $form_provider_id = empty($firow['provider_id']) ? 0 : intval($firow['provider_id']);

    // Provider selector.
    // TBD: Refactor this function out of the FeeSheetHTML class as that is not the best place for it.
    echo xlt('Provider') . ": ";
    echo FeeSheetHtml::genProviderSelect('form_provider_id', '-- ' . xl("Please Select") . ' --', $form_provider_id);

    // If appropriate build a drop-down selector of issues of this type for this patient.
    // We skip this if in an issue form tab because removing and adding visit form tabs is
    // beyond the current scope of that code.
    if (!empty($LBF_ISSUE_TYPE) && !$from_issue_form) {
      echo "&nbsp;&nbsp;";
      // echo "\n<!-- formname = '$formname' formid = '$formid' -->\n"; // debugging
      $query = "SELECT id, title, date, begdate FROM lists WHERE pid = ? AND type = ? " .
        "ORDER BY COALESCE(begdate, date) DESC, id DESC";
      $ires = sqlStatement($query, array($pid, $LBF_ISSUE_TYPE));
      echo "<select name='form_issue_id'>\n";
      echo " <option value='0'>-- " . xlt('Select Case') . " --</option>\n";
      while ($irow = sqlFetchArray($ires)) {
        $issueid = $irow['id'];
        $issuedate = oeFormatShortDate(empty($irow['begdate']) ? $irow['date'] : $irow['begdate']);
        echo " <option value='$issueid'";
        if ($issueid == $form_issue_id) echo " selected";
        echo ">$issuedate " . text($irow['title']) . "</option>\n";
      }
      echo "</select>\n";
      echo "&nbsp;&nbsp;";
    }
    echo " </td>\n";

    echo "</table>\n";
  } // end not from trend form
?>

<!-- This is where a chart might display. -->
<div id="chart"></div>

<?php
  $shrow = getHistoryData($pid);

  // Determine if this layout uses edit option "I" anywhere.
  // If not we default to only the first group being initially open.
  $tmprow = sqlQuery("SELECT form_id FROM layout_options " .
    "WHERE form_id = ? AND uor > 0 AND edit_options LIKE '%I%' " .
    "LIMIT 1", array($formname));
  $some_group_is_open = !empty($tmprow['form_id']);

  $fres = sqlStatement("SELECT * FROM layout_options " .
    "WHERE form_id = ? AND uor > 0 " .
    "ORDER BY group_name, seq", array($formname) );
  /********************************************************************
  $last_group = '';
  ********************************************************************/
  $cell_count = 0;
  $item_count = 0;
  $display_style = 'block';

  // This is an array of the active group levels. Each entry is a group or
  // subgroup name (with its order prefix) and represents a level of nesting.
  $group_levels = array();

  // This indicates if </table> will need to be written to end the fields in a group.
  $group_table_active = false;

  // This is an array keyed on forms.form_id for other occurrences of this
  // form type.  The maximum number of such other occurrences to display is
  // in list_options.option_value for this form's list item.  Values in this
  // array are work areas for building the ending HTML for each displayed row.
  //
  $historical_ids = array();

  // True if any data items in this form can be graphed.
  $form_is_graphable = false;

  $condition_str = '';

  while ($frow = sqlFetchArray($fres)) {
    $this_group = $frow['group_name'];
    $titlecols  = $frow['titlecols'];
    $datacols   = $frow['datacols'];
    $data_type  = $frow['data_type'];
    $field_id   = $frow['field_id'];
    $list_id    = $frow['list_id'];
    $edit_options = $frow['edit_options'];
    $source       = $frow['source'];

    $graphable  = strpos($edit_options, 'G') !== FALSE;
    if ($graphable) $form_is_graphable = true;

    // Accumulate skip conditions into a JavaScript string literal.
    $conditions = empty($frow['conditions']) ? array() : unserialize($frow['conditions']);
    foreach ($conditions as $condition) {
      if (empty($condition['id'])) continue;
      $andor = empty($condition['andor']) ? '' : $condition['andor'];
      if ($condition_str) $condition_str .= ",\n";
      $condition_str .= "{" .
        "target:'"   . addslashes($field_id)              . "', " .
        "id:'"       . addslashes($condition['id'])       . "', " .
        "itemid:'"   . addslashes($condition['itemid'])   . "', " .
        "operator:'" . addslashes($condition['operator']) . "', " .
        "value:'"    . addslashes($condition['value'])    . "', " .
        "andor:'"    . addslashes($andor)                 . "'}";
    }

    $currvalue  = '';

    if (strpos($edit_options, 'H') !== FALSE) {
      // This data comes from static history
      if (isset($shrow[$field_id])) $currvalue = $shrow[$field_id];
    } else {
      $currvalue = lbf_current_value($frow, $formid, $from_trend_form ? 0 : $visitid);
      if ($currvalue === FALSE) continue; // column does not exist, should not happen
      // Handle "P" edit option to default to the previous value of a form field.
      if (!$from_trend_form && empty($currvalue) && strpos($edit_options, 'P') !== FALSE) {
        if ($source == 'F' && !$formid) {
          // Form attribute for new form, get value from most recent form instance.
          // Form attributes of existing forms are expected to have existing values.
          $tmp = sqlQuery("SELECT encounter, form_id FROM forms WHERE " .
            "pid = ? AND formdir = ? AND deleted = 0 " .
            "ORDER BY date DESC LIMIT 1",
            array($pid, $formname));
          if (!empty($tmp['encounter'])) {
            $currvalue = lbf_current_value($frow, $tmp['form_id'], $tmp['encounter']);
          }
        }
        else if ($source == 'E') {
          // Visit attribute, get most recent value as of this visit.
          // Even if the form already exists for this visit it may have a readonly value that only
          // exists in a previous visit and was created from a different form.
          $tmp = sqlQuery("SELECT sa.field_value FROM form_encounter AS e1 " .
            "JOIN form_encounter AS e2 ON " .
            "e2.pid = e1.pid AND (e2.date < e1.date OR (e2.date = e1.date AND e2.encounter <= e1.encounter)) " .
            "JOIN shared_attributes AS sa ON " .
            "sa.pid = e2.pid AND sa.encounter = e2.encounter AND sa.field_id = ?" .
            "WHERE e1.pid = ? AND e1.encounter = ? " .
            "ORDER BY e2.date DESC, e2.encounter DESC LIMIT 1",
            array($field_id, $pid, $visitid));
          if (isset($tmp['field_value'])) $currvalue = $tmp['field_value'];
        }
      } // End "P" option logic.
    }

    /******************************************************************
    // Handle a data category (group) change.
    if (strcmp($this_group, $last_group) != 0) {
      end_group();
      $group_seq  = 'lbf' . substr($this_group, 0, 1);
      $group_name = substr($this_group, 1);
      $last_group = $this_group;
      if ($some_group_is_open) {
        // Must have edit option "I" in first item for its group to be initially open.
        $display_style = strpos($edit_options, 'I') === FALSE ? 'none' : 'block';
      }
      // If group name is blank, no checkbox or div.
      if (strlen($this_group) > 1) {
        echo "<br /><span class='bold'><input type='checkbox' name='form_cb_" . attr($group_seq) . "' value='1' " .
          "onclick='return divclick(this,\"div_" . attr(addslashes($group_seq)) . "\");'";
        if ($display_style == 'block') echo " checked";
        echo " /><b>" . text(xl_layout_label($group_name)) . "</b></span>\n";
        echo "<div id='div_" . attr($group_seq) . "' class='section' style='display:" . attr($display_style) . ";'>\n";
      }
      // echo " <table border='0' cellpadding='0' width='100%'>\n";
      echo " <table border='0' cellspacing='0' cellpadding='0'>\n";
      $display_style = 'none';
      // Initialize historical data array and write date headers.
      $historical_ids = array();
      if ($formhistory > 0) {
        echo " <tr>";
        echo "<td colspan='" . attr($CPR) . "' align='right' class='bold'>";
        if (empty($is_lbf)){
            // Including actual date per IPPF request 2012-08-23.
            echo oeFormatShortDate(substr($enrow['date'], 0, 10));
            echo ' (' . htmlspecialchars(xl('Current')) . ')';
        }
        echo "&nbsp;</td>\n";
        $hres = sqlStatement("SELECT f.form_id, fe.date " .
          "FROM forms AS f, form_encounter AS fe WHERE " .
          "f.pid = ? AND f.formdir = ? AND " .
          "f.form_id != ? AND f.deleted = 0 AND " .
          "fe.pid = f.pid AND fe.encounter = f.encounter " .
          "ORDER BY fe.date DESC, f.encounter DESC, f.date DESC " .
          "LIMIT ?",
          array($pid, $formname, $formid, $formhistory));
        // For some readings like vitals there may be multiple forms per encounter.
        // We sort these sensibly, however only the encounter date is shown here;
        // at some point we may wish to show also the data entry date/time.
        while ($hrow = sqlFetchArray($hres)) {
          echo "<td colspan='" . attr($CPR) . "' align='right' class='bold'>&nbsp;" .
            text(oeFormatShortDate(substr($hrow['date'], 0, 10))) . "</td>\n";
          $historical_ids[$hrow['form_id']] = '';
        }
        echo " </tr>";
      }
    }
    ******************************************************************/

    $this_levels = explode('|', $this_group);
    $i = 0;
    $mincount = min(count($this_levels), count($group_levels));
    while ($i < $mincount && $this_levels[$i] == $group_levels[$i]) ++$i;
    // $i is now the number of initial matching levels.

    // If ending a group or starting a subgroup, terminate the current row and its table.
    if ($group_table_active && ($i != count($group_levels) || $i != count($this_levels))) {
      end_row();
      echo " </table>\n";
      $group_table_active = false;
    }

    // Close any groups that we are done with.
    while (count($group_levels) > $i) {
      $gname = array_pop($group_levels);
      // No div for an empty group name.
      if (strlen($gname) > 1) echo "</div>\n";
    }

    // If there are any new groups, open them.
    while ($i < count($this_levels)) {
      end_row();
      if ($group_table_active) {
        echo " </table>\n";
        $group_table_active = false;
      }

      $gname = $this_levels[$i++];
      array_push($group_levels, $gname);

      // Compute a short unique identifier for this group.
      $group_seq  = 'lbf';
      foreach ($group_levels as $tmp) $group_seq .= substr($tmp, 0, 1);
      $group_name = substr($gname, 1);
      // $group_name does not include the order prefix character.

      if ($some_group_is_open) {
        // Must have edit option "I" in first item for its group to be initially open.
        $display_style = strpos($edit_options, 'I') === FALSE ? 'none' : 'block';
      }

      // If group name is blank, no checkbox or div.
      if (strlen($gname) > 1) {
        echo "<br /><span class='bold'><input type='checkbox' name='form_cb_" . attr($group_seq) . "' value='1' " .
          "onclick='return divclick(this,\"div_" . attr(addslashes($group_seq)) . "\");'";
        if ($display_style == 'block') echo " checked";
        echo " /><b>" . text(xl_layout_label($group_name)) . "</b></span>\n";
        echo "<div id='div_" . attr($group_seq) . "' class='section' style='display:" . attr($display_style) . ";'>\n";
      }

      $group_table_active = true;
      echo " <table border='0' cellspacing='0' cellpadding='0' class='lbfdata'>\n";
      $display_style = 'none';

      // Initialize historical data array and write date headers.
      $historical_ids = array();
      if ($formhistory > 0) {
        echo " <tr>";
        echo "<td colspan='" . attr($CPR) . "' align='right' class='bold'>";
        if (!$from_trend_form){
            // Including actual date per IPPF request 2012-08-23.
            echo oeFormatShortDate(substr($enrow['date'], 0, 10));
            echo ' (' . htmlspecialchars(xl('Current')) . ')';
        }
        echo "&nbsp;</td>\n";
        $hres = sqlStatement("SELECT f.form_id, fe.date " .
          "FROM forms AS f, form_encounter AS fe WHERE " .
          "f.pid = ? AND f.formdir = ? AND " .
          "f.form_id != ? AND f.deleted = 0 AND " .
          "fe.pid = f.pid AND fe.encounter = f.encounter " .
          "ORDER BY fe.date DESC, f.encounter DESC, f.date DESC " .
          "LIMIT ?",
          array($pid, $formname, $formid, $formhistory));
        // For some readings like vitals there may be multiple forms per encounter.
        // We sort these sensibly, however only the encounter date is shown here;
        // at some point we may wish to show also the data entry date/time.
        while ($hrow = sqlFetchArray($hres)) {
          echo "<td colspan='" . attr($CPR) . "' align='right' class='bold'>&nbsp;" .
            text(oeFormatShortDate(substr($hrow['date'], 0, 10))) . "</td>\n";
          $historical_ids[$hrow['form_id']] = '';
        }
        echo " </tr>";
      }
    }

    // Handle starting of a new row.
    if (($titlecols > 0 && $cell_count >= $CPR) || $cell_count == 0) {
      end_row();
      echo " <tr>";
      // Clear historical data string.
      foreach ($historical_ids as $key => $dummy) {
        $historical_ids[$key] = '';
      }
    }

    if ($item_count == 0 && $titlecols == 0) $titlecols = 1;

    // First item is on the "left-border"
    $leftborder = true;

    // Handle starting of a new label cell.
    if ($titlecols > 0) {
      end_cell();
      echo "<td valign='top' colspan='" . attr($titlecols) . "' nowrap";
      echo " class='";
      echo ($frow['uor'] == 2) ? "required" : "bold";
      if ($graphable) echo " graph";
      echo "'";
      if ($cell_count > 0) echo " style='padding-left:10pt'";
      // This ID is used by skip conditions and also show_graph().
      echo " id='label_id_" . attr($field_id) . "'";
      echo ">";

      foreach ($historical_ids as $key => $dummy) {
        $historical_ids[$key] .= "<td valign='top' colspan='" . attr($titlecols) . "' class='text' nowrap>";
      }

      $cell_count += $titlecols;
    }
    ++$item_count;

    echo "<b>";
    if ($frow['title']) {
      $tmp = xl_layout_label($frow['title']);
      echo text($tmp);
      // Append colon only if label does not end with punctuation.
      if (strpos('?!.,:-=', substr($tmp, -1, 1)) === FALSE) echo ':';
    }
    else {
      echo "&nbsp;";
    }
    echo "</b>";

    // Note the labels are not repeated in the history columns.

    // Handle starting of a new data cell.
    if ($datacols > 0) {
      end_cell();
      echo "<td valign='top' colspan='" . attr($datacols) . "' class='text'";
      // This ID is used by skip conditions.
      echo " id='value_id_" . attr($field_id) . "'";
      if ($cell_count > 0) echo " style='padding-left:5pt'";
      echo ">";

      foreach ($historical_ids as $key => $dummy) {
        $historical_ids[$key] .= "<td valign='top' align='right' colspan='" . attr($datacols) . "' class='text'>";
      }

      $cell_count += $datacols;
    }

    ++$item_count;

    // Skip current-value fields for the display-only case.
    if (!$from_trend_form) {
      if ($frow['edit_options'] == 'H')
        echo generate_display_field($frow, $currvalue);
      else
        generate_form_field($frow, $currvalue);
    }

    // Append to historical data of other dates for this item.
    foreach ($historical_ids as $key => $dummy) {
      $value = lbf_current_value($frow, $key, 0);
      $historical_ids[$key] .= generate_display_field($frow, $value);
    }

  }

  /********************************************************************
  end_group();
  ********************************************************************/
  // Close all open groups.
  if ($group_table_active) {
    end_row();
    echo " </table>\n";
    $group_table_active = false;
  }
  while (count($group_levels)) {
    $gname = array_pop($group_levels);
    // No div for an empty group name.
    if (strlen($gname) > 1) echo "</div>\n";
  }

  $display_style = 'none';

  if (isset($LBF_SERVICES_SECTION) || isset($LBF_DIAGS_SECTION)) {
    $fs->loadServiceItems();
  }

  if (isset($LBF_SERVICES_SECTION)) {

    // Create the checkbox and div for the Services Section.
    echo "<br /><span class='bold'><input type='checkbox' name='form_cb_fs_services' value='1' " .
      "onclick='return divclick(this, \"div_fs_services\");'";
    if ($display_style == 'block') echo " checked";
    echo " /><b>" . xlt('Services') . "</b></span>\n";
    echo "<div id='div_fs_services' class='section' style='display:" . attr($display_style) . ";'>\n";
    echo "<center>\n";
    $display_style = 'none';

    // If there is an associated list, generate a checkbox for each service in the list.
    if ($LBF_SERVICES_SECTION) {
      $lres = sqlStatement("SELECT * FROM list_options " .
        "WHERE list_id = ? ORDER BY seq, title", array($LBF_SERVICES_SECTION));
      echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
      $cols = 3;
      $tdpct = (int) (100 / $cols);
      for ($count = 0; $lrow = sqlFetchArray($lres); ++$count) {
        $codes = $lrow['codes'];
        $codes_esc = htmlspecialchars($codes, ENT_QUOTES);
        $cbval = $fs->genCodeSelectorValue($codes);
        if ($count % $cols == 0) {
          if ($count) echo " </tr>\n";
          echo " <tr>\n";
        }
        echo "  <td width='$tdpct%'>";
        echo "<input type='checkbox' id='form_fs_services[$codes_esc]' " .
          "onclick='fs_service_clicked(this)' value='" . attr($cbval) . "'";
        if ($fs->code_is_in_fee_sheet) echo " checked";
        $title = empty($lrow['title']) ? $lrow['option_id'] : xl_list_label($lrow['title']);
        echo " />" . htmlspecialchars($title, ENT_NOQUOTES);
        echo "</td>\n";
      }
      if ($count) echo " </tr>\n";
      echo "</table>\n";
    }

    // A row for Search, Main Provider, Price Level.
    $ctype = $GLOBALS['ippf_specific'] ? 'MA' : '';
    echo "<p class='bold'>";
    echo "<input type='button' value='" . xla('Search Services') . "' onclick='sel_related(null,\"$ctype\")' />&nbsp;&nbsp;";
    echo xlt('Main Provider') . ": ";
    echo $fs->genProviderSelect("form_fs_provid", ' ', $fs->provider_id);
    echo "\n";
    echo "</p>\n";

    // Generate a line for each service already in this FS.
    echo "<table cellpadding='0' cellspacing='2' id='fs_services_table'>\n";
    echo " <tr>\n";
    echo "  <td class='bold' colspan='2'>" . xlt('Services Provided') . "&nbsp;</td>\n";
    echo "  <td class='bold'>" . xlt('Provider') . "&nbsp;</td>\n";
    echo "  <td class='bold' align='right'>" . xlt('Price'   ) . "&nbsp;</td>\n";
    echo "  <td class='bold' align='right'>" . xlt('Delete'  ) . "</td>\n";
    echo " </tr>\n";
    foreach ($fs->serviceitems as $lino => $li) {
      // Skip diagnoses; those would be in the Diagnoses section below.
      if ($code_types[$li['codetype']]['diag']) continue;
      echo " <tr>\n";
      echo "  <td class='text'>" . text($li['code']) . "&nbsp;</td>\n";
      echo "  <td class='text'>" . text($li['code_text']) . "&nbsp;</td>\n";
      echo "  <td class='text'>" .
        $fs->genProviderSelect("form_fs_bill[$lino][provid]", '-- ' . xl("Default") . ' --', $li['provid']) .
        "  &nbsp;</td>\n";
      echo "  <td class='text' align='right'>" . oeFormatMoney($li['price']) . "&nbsp;</td>\n";
      echo "  <td class='text' align='right'>\n" .
        "   <input type='checkbox' name='form_fs_bill[$lino][del]' " .
        "value='1'" . ($li['del'] ? " checked" : "") . " />\n";
      foreach ($li['hidden'] as $hname => $hvalue) {
        echo "   <input type='hidden' name='form_fs_bill[$lino][$hname]' value='" . attr($hvalue) . "' />\n";
      }
      echo "  </td>\n";
      echo " </tr>\n";
    }
    echo "</table>\n";
    echo "</center>\n";
    echo "</div>\n";

  } // End Services Section

  if (isset($LBF_PRODUCTS_SECTION)) {

    // Create the checkbox and div for the Products Section.
    echo "<br /><span class='bold'><input type='checkbox' name='form_cb_fs_products' value='1' " .
      "onclick='return divclick(this, \"div_fs_products\");'";
    if ($display_style == 'block') echo " checked";
    echo " /><b>" . xlt('Products') . "</b></span>\n";
    echo "<div id='div_fs_products' class='section' style='display:" . attr($display_style) . ";'>\n";
    echo "<center>\n";
    $display_style = 'none';

    // If there is an associated list, generate a checkbox for each product in the list.
    if ($LBF_PRODUCTS_SECTION) {
      $lres = sqlStatement("SELECT * FROM list_options " .
        "WHERE list_id = ? ORDER BY seq, title", array($LBF_PRODUCTS_SECTION));
      echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
      $cols = 3;
      $tdpct = (int) (100 / $cols);
      for ($count = 0; $lrow = sqlFetchArray($lres); ++$count) {
        $codes = $lrow['codes'];
        $codes_esc = htmlspecialchars($codes, ENT_QUOTES);
        $cbval = $fs->genCodeSelectorValue($codes);
        if ($count % $cols == 0) {
          if ($count) echo " </tr>\n";
          echo " <tr>\n";
        }
        echo "  <td width='$tdpct%'>";
        echo "<input type='checkbox' id='form_fs_products[$codes_esc]' " .
          "onclick='fs_product_clicked(this)' value='" . attr($cbval) . "'";
        if ($fs->code_is_in_fee_sheet) echo " checked";
        $title = empty($lrow['title']) ? $lrow['option_id'] : xl_list_label($lrow['title']);
        echo " />" . htmlspecialchars($title, ENT_NOQUOTES);
        echo "</td>\n";
      }
      if ($count) echo " </tr>\n";
      echo "</table>\n";
    }

    // A row for Search
    $ctype = $GLOBALS['ippf_specific'] ? 'MA' : '';
    echo "<p class='bold'>";
    echo "<input type='button' value='" . xla('Search Products') . "' onclick='sel_related(null,\"PROD\")' />&nbsp;&nbsp;";
    echo "</p>\n";

    // Generate a line for each product already in this FS.
    echo "<table cellpadding='0' cellspacing='2' id='fs_products_table'>\n";
    echo " <tr>\n";
    echo "  <td class='bold'>" . xlt('Products Provided') . "&nbsp;</td>\n";
    echo "  <td class='bold'>" . xlt('Warehouse'        ) . "&nbsp;</td>\n";
    echo "  <td class='bold' align='right'>" . xlt('Quantity') . "&nbsp;</td>\n";
    echo "  <td class='bold' align='right'>" . xlt('Price'   ) . "&nbsp;</td>\n";
    echo "  <td class='bold' align='right'>" . xlt('Delete'  ) . "</td>\n";
    echo " </tr>\n";
    $fs->loadProductItems();
    foreach ($fs->productitems as $lino => $li) {
      echo " <tr>\n";
      echo "  <td class='text'>" . text($li['code_text']) . "&nbsp;</td>\n";
      echo "  <td class='text'>" .
        $fs->genWarehouseSelect("form_fs_prod[$lino][warehouse]", '', $li['warehouse'], false, $li['hidden']['drug_id'], true) .
        "  &nbsp;</td>\n";
      echo "  <td class='text' align='right'>" .
        "<input type='text' name='form_fs_prod[$lino][units]' size='3' value='" . $li['units'] . "' />" .
        "&nbsp;</td>\n";
      echo "  <td class='text' align='right'>" . oeFormatMoney($li['price']) . "&nbsp;</td>\n";
      echo "  <td class='text' align='right'>\n" .
        "   <input type='checkbox' name='form_fs_prod[$lino][del]' " .
        "value='1'" . ($li['del'] ? " checked" : "") . " />\n";
      foreach ($li['hidden'] as $hname => $hvalue) {
        echo "   <input type='hidden' name='form_fs_prod[$lino][$hname]' value='" . attr($hvalue) . "' />\n";
      }
      echo "  </td>\n";
      echo " </tr>\n";
    }
    echo "</table>\n";
    echo "</center>\n";
    echo "</div>\n";

  } // End Products Section

  if (isset($LBF_DIAGS_SECTION)) {

    // Create the checkbox and div for the Diagnoses Section.
    echo "<br /><span class='bold'><input type='checkbox' name='form_cb_fs_diags' value='1' " .
      "onclick='return divclick(this, \"div_fs_diags\");'";
    if ($display_style == 'block') echo " checked";
    echo " /><b>" . xlt('Diagnoses') . "</b></span>\n";
    echo "<div id='div_fs_diags' class='section' style='display:" . attr($display_style) . ";'>\n";
    echo "<center>\n";
    $display_style = 'none';

    // If there is an associated list, generate a checkbox for each diagnosis in the list.
    if ($LBF_DIAGS_SECTION) {
      $lres = sqlStatement("SELECT * FROM list_options " .
        "WHERE list_id = ? ORDER BY seq, title", array($LBF_DIAGS_SECTION));
      echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
      $cols = 3;
      $tdpct = (int) (100 / $cols);
      for ($count = 0; $lrow = sqlFetchArray($lres); ++$count) {
        $codes = $lrow['codes'];
        $codes_esc = htmlspecialchars($codes, ENT_QUOTES);
        $cbval = $fs->genCodeSelectorValue($codes);
        if ($count % $cols == 0) {
          if ($count) echo " </tr>\n";
          echo " <tr>\n";
        }
        echo "  <td width='$tdpct%'>";
        echo "<input type='checkbox' id='form_fs_diags[$codes_esc]' " .
          "onclick='fs_diag_clicked(this)' value='" . attr($cbval) . "'";
        if ($fs->code_is_in_fee_sheet) echo " checked";
        $title = empty($lrow['title']) ? $lrow['option_id'] : xl_list_label($lrow['title']);
        echo " />" . htmlspecialchars($title, ENT_NOQUOTES);
        echo "</td>\n";
      }
      if ($count) echo " </tr>\n";
      echo "</table>\n";
    }

    // A row for Search.
    $ctype = 'ICD9,ICD10';
    echo "<p class='bold'>";
    echo "<input type='button' value='" . xla('Search Diagnoses') . "' onclick='sel_related(null,\"$ctype\")' />";
    echo "</p>\n";

    // Generate a line for each diagnosis already in this FS.
    echo "<table cellpadding='0' cellspacing='2' id='fs_diags_table'>\n";
    echo " <tr>\n";
    echo "  <td class='bold' colspan='2'>" . xlt('Diagnosis') . "&nbsp;</td>\n";
    echo "  <td class='bold' align='right'>" . xlt('Delete'  ) . "</td>\n";
    echo " </tr>\n";
    foreach ($fs->serviceitems as $lino => $li) {
      // Skip anything that is not a diagnosis; those are in the Services section above.
      if (!$code_types[$li['codetype']]['diag']) continue;
      echo " <tr>\n";
      echo "  <td class='text'>" . text($li['code']) . "&nbsp;</td>\n";
      echo "  <td class='text'>" . text($li['code_text']) . "&nbsp;</td>\n";
      // The Diagnoses section shares the form_fs_bill array with the Services section.
      echo "  <td class='text' align='right'>\n" .
        "   <input type='checkbox' name='form_fs_bill[$lino][del]' " .
        "value='1'" . ($li['del'] ? " checked" : "") . " />\n";
      foreach ($li['hidden'] as $hname => $hvalue) {
        echo "   <input type='hidden' name='form_fs_bill[$lino][$hname]' value='" . attr($hvalue) . "' />\n";
      }
      echo "  </td>\n";
      echo " </tr>\n";
    }
    echo "</table>\n";
    echo "</center>\n";
    echo "</div>\n";

  } // End Services Section

?>

<p style='text-align:center' class='bold'>
<?php if (!$from_trend_form) { ?>
<?php
  // Generate price level selector if we are doing services or products.
  if (isset($LBF_SERVICES_SECTION) || isset($LBF_PRODUCTS_SECTION)) {
    echo xlt('Price Level') . ": ";
    echo $fs->generatePriceLevelSelector('form_fs_pricelevel');
    echo "&nbsp;&nbsp;";
  }
?>
<input type='submit' name='bn_save' value='<?php echo xla('Save') ?>' />

<?php if (!$from_issue_form) { ?>

&nbsp;
<input type='submit' name='bn_save_print' value='<?php echo xla('Save and Print') ?>' />
<?php
if (function_exists($formname . '_additional_buttons')) {
  // Allow the plug-in to insert more action buttons here.
  call_user_func($formname . '_additional_buttons');
}
?>
&nbsp;
<input type='button' value='<?php echo xla('Cancel') ?>' onclick="top.restoreSession();location='<?php echo $GLOBALS['form_exit_url']; ?>'" />
<?php if ($form_is_graphable) { ?>
&nbsp;
<input type='button' value='<?php echo xla('Show Graph') ?>' onclick="top.restoreSession();location='../../patient_file/encounter/trend_form.php?formname=<?php echo attr($formname); ?>'" />
<?php } ?>

<?php } // end not from issue form ?>

<?php } else { // $from_trend_form is true ?>
<input type='button' value='<?php echo xla('Back') ?>' onclick='window.history.back();' />
<?php } ?>

<input type='hidden' name='from_issue_form' value='<?php echo text($from_issue_form); ?>' />
</p>

</form>

<!-- include support for the list-add selectbox feature -->
<?php include $GLOBALS['fileroot'] . "/library/options_listadd.inc"; ?>

<script language="JavaScript">

// Array of skip conditions for the checkSkipConditions() function.
var skipArray = [
<?php echo $condition_str; ?>
];

$(window).load(function() {
 // Unlike with $(document).ready(), this stuff is done after any images are loaded.
 // That's important because we might have code here that loads a canvas from an image.
<?php echo $date_init; ?>
});

<?php
if (function_exists($formname . '_javascript_onload')) {
  call_user_func($formname . '_javascript_onload');
}
if ($alertmsg) {
  echo "alert('" . addslashes($alertmsg) . "');\n";
}
?>
</script>

</body>
</html>
