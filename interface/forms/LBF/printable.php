<?php
// Copyright (C) 2009-2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once("../../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/patient.inc");
require_once($GLOBALS['fileroot'] . '/custom/code_types.inc.php');

// Font size in points for table cell data.
$FONTSIZE = 9;

// The form name is passed to us as a GET parameter.
$formname = isset($_GET['formname']) ? $_GET['formname'] : '';

$patientid = empty($_REQUEST['patientid']) ? 0 : (0 + $_REQUEST['patientid']);
if ($patientid < 0) $patientid = 0 + $pid; // -1 means current pid

$visitid = empty($_REQUEST['visitid']) ? 0 : (0 + $_REQUEST['visitid']);
if ($visitid < 0) $visitid = 0 + $encounter; // -1 means current encounter

$formid = empty($_REQUEST['formid']) ? 0 : (0 + $_REQUEST['formid']);

// True if to display as a form to complete, false to display as information.
$isform = empty($_REQUEST['isform']) ? 0 : 1;

$CPR = 4; // cells per row

// Collect some top-level information about this layout.
$tmp = sqlQuery("SELECT title, notes FROM list_options WHERE " .
  "list_id = 'lbfnames' AND option_id = ? AND activity = 1 LIMIT 1", array($formname) );
$formtitle = $tmp['title'];

$jobj = json_decode($tmp['notes'], true);
if (!empty($jobj['columns'])) $CPR = intval($jobj['columns']);
if (!empty($jobj['size'   ])) $FONTSIZE = intval($jobj['size']);
if (isset($jobj['services'])) $LBF_SERVICES_SECTION = $jobj['services'];
if (isset($jobj['products'])) $LBF_PRODUCTS_SECTION = $jobj['products'];
if (isset($jobj['diags'   ])) $LBF_DIAGS_SECTION = $jobj['diags'];

// Check access control.
if (!empty($jobj['aco'])) $LBF_ACO = explode('|', $jobj['aco']);
if (!acl_check('admin', 'super') && !empty($LBF_ACO)) {
  if (!acl_check($LBF_ACO[0], $LBF_ACO[1])) {
    die(xlt('Access denied'));
  }
}

// Referral form is a special case that has its own print script.
if ($formname == 'LBFref') {
  $transid = 0;
  if ($formid) {
    $trow = sqlQuery("SELECT id FROM forms WHERE form_id = ? AND formdir = ? AND deleted = 0",
      array($formid, $formname));
    if (!empty($trow['id'])) $transid = $trow['id'];
  }
  $_REQUEST['transid'   ] = $transid;
  $_REQUEST['patient_id'] = $patientid;
  include($GLOBALS['fileroot'] . '/interface/patient_file/transaction/print_referral.php');
  exit;
}

// Html2pdf fails to generate checked checkboxes properly, so write plain HTML
// if we are doing a visit-specific form to be completed.
$PDF_OUTPUT = ($formid && $isform) ? false : true;
// $PDF_OUTPUT = false; // debugging

if ($PDF_OUTPUT) {
  require_once("$srcdir/html2pdf/html2pdf.class.php");
  $pdf = new HTML2PDF('P', 'Letter', 'en');
  $pdf->setTestTdInOnePage(false); // Turn off error message for TD contents too big.
  $pdf->pdf->SetDisplayMode('real');
  ob_start();
}

if ($visitid && (isset($LBF_SERVICES_SECTION) || isset($LBF_DIAGS_SECTION) || isset($LBF_PRODUCTS_SECTION))) {
  require_once("$srcdir/FeeSheetHtml.class.php");
  $fs = new FeeSheetHtml($pid, $encounter);
}

$fres = sqlStatement("SELECT * FROM layout_options " .
  "WHERE form_id = ? AND uor > 0 " .
  "ORDER BY group_name, seq", array($formname) );
?>
<?php if (!$PDF_OUTPUT) { ?>
<html>
<head>
<?php html_header_show();?>
<?php } ?>

<style>

<?php if ($PDF_OUTPUT) { ?>
td {
 font-family: Arial;
 font-weight: normal;
 font-size: <?php echo $FONTSIZE; ?>pt;
}
<?php } else { ?>
body, td {
 font-family: Arial, Helvetica, sans-serif;
 font-weight: normal;
 font-size: <?php echo $FONTSIZE; ?>pt;
}
body {
 padding: 5pt 5pt 5pt 5pt;
}
<?php } ?>

p.grpheader {
 font-family: Arial;
 font-weight: bold;
 font-size: <?php echo round($FONTSIZE * 1.33); ?>pt;
 margin-bottom: <?php echo round($FONTSIZE * 0.44); ?>pt;
}

div.section {
 width: 98%;
<?php
  // html2pdf screws up the div borders when a div overflows to a second page.
  // Our temporary solution is to turn off the borders in the case where this
  // is likely to happen (i.e. where all form options are listed).
  if (!$isform) {
?>
 border-style: solid;
 border-width: 1px;
 border-color: #000000;
<?php } ?>
 padding: 2pt 5pt 5pt 5pt;
}
div.section table {
 width: 100%;
}
div.section td.stuff {
 vertical-align: bottom;
<?php if ($isform) { ?>
 height: 16pt;
<?php } ?>
}

<?php
// Generate widths for the various numbers of label columns and data columns.
for ($lcols = 1; $lcols < $CPR; ++$lcols) {
  $dcols = $CPR - $lcols;
  $lpct = intval(100 * $lcols / $CPR);
  $dpct = 100 - $lpct;
  echo "td.lcols$lcols { width: $lpct%; text-align: right; }\n";
  echo "td.dcols$dcols { width: $dpct%; }\n";
}
?>

.mainhead {
 font-weight: bold;
 font-size: <?php echo round($FONTSIZE * 1.56); ?>pt;
 text-align: center;
}

.subhead {
 font-weight: bold;
 font-size: <?php echo round($FONTSIZE * 0.89); ?>pt;
}

.under {
 border-style: solid;
 border-width: 0 0 1px 0;
 border-color: #999999;
}

.ftitletable {
 width: 100%;
 margin: 0 0 8pt 0;
}
.ftitlecell1 {
 width: 33%;
 vertical-align: top;
 text-align: left;
 font-size: <?php echo round($FONTSIZE * 1.56); ?>pt;
 font-weight: bold;
}
.ftitlecell2 {
 width: 33%;
 vertical-align: top;
 text-align: right;
 font-size: <?php echo $FONTSIZE; ?>pt;
}
.ftitlecellm {
 width: 34%;
 vertical-align: top;
 text-align: center;
 font-size: <?php echo round($FONTSIZE * 1.56); ?>pt;
 font-weight: bold;
}
</style>

<?php if (!$PDF_OUTPUT) { ?>
</head>
<body bgcolor='#ffffff'>
<?php } ?>

<form>

<?php
// Generate header with optional logo.
$logo = '';
$ma_logo_path = "sites/" . $_SESSION['site_id'] . "/images/ma_logo.png";
if (is_file("$webserver_root/$ma_logo_path")) {
  // Would use max-height here but html2pdf does not support it.
  $logo = "<img src='$web_root/$ma_logo_path' style='height:" . round($FONTSIZE * 5.14) . "pt' />";
}
else {
  $logo = "<!-- '$ma_logo_path' does not exist. -->";
}
echo genFacilityTitle($formtitle, -1, $logo);
?>

<?php if ($isform) { ?>
<span class='subhead'>
 <?php echo xlt('Patient') ?>: ________________________________________ &nbsp;
 <?php echo xlt('Clinic') ?>: ____________________ &nbsp;
 <?php echo xlt('Date') ?>: ____________________<br />&nbsp;<br />
</span>
<?php } ?>

<?php

function end_cell() {
  global $item_count, $cell_count;
  if ($item_count > 0) {
    echo "</td>";
    $item_count = 0;
  }
}

function end_row() {
  global $cell_count, $CPR;
  end_cell();
  if ($cell_count > 0) {
    for (; $cell_count < $CPR; ++$cell_count) echo "<td></td>";
    echo "</tr>\n";
    $cell_count = 0;
  }
}

function getContent() {
  global $web_root, $webserver_root;
  $content = ob_get_clean();
  // Fix a nasty html2pdf bug - it ignores document root!
  $i = 0;
  $wrlen = strlen($web_root);
  $wsrlen = strlen($webserver_root);
  while (true) {
    $i = stripos($content, " src='/", $i + 1);
    if ($i === false) break;
    if (substr($content, $i+6, $wrlen) === $web_root &&
        substr($content, $i+6, $wsrlen) !== $webserver_root)
    {
      $content = substr($content, 0, $i + 6) . $webserver_root . substr($content, $i + 6 + $wrlen);
    }
  }
  return $content;
}

$cell_count = 0;
$item_count = 0;

// This is an array of the active group levels. Each entry is a group or
// subgroup name (with its order prefix) and represents a level of nesting.
$group_levels = array();

// This indicates if </table> will need to be written to end the fields in a group.
$group_table_active = false;

while ($frow = sqlFetchArray($fres)) {
  $this_group   = $frow['group_name'];
  $titlecols    = $frow['titlecols'];
  $datacols     = $frow['datacols'];
  $data_type    = $frow['data_type'];
  $field_id     = $frow['field_id'];
  $list_id      = $frow['list_id'];
  $edit_options = $frow['edit_options'];

  // Skip this field if its do-not-print option is set.
  if (strpos($edit_options, 'X') !== FALSE) continue;

  $currvalue = '';
  if ($formid || $visitid) {
    $currvalue = lbf_current_value($frow, $formid, $visitid);
    if ($currvalue === FALSE) continue; // should not happen
  }

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
    echo "</div>\n";
    // echo "</nobreak><br /><div><table><tr><td>&nbsp;</td></tr></table></div><br />\n";
    echo "</nobreak>\n";
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

    // This is also for html2pdf. Telling it that the following stuff should
    // start on a new page if there is not otherwise room for it on this page.
    echo "<nobreak>\n";

    echo "<p class='grpheader'>" . text(xl_layout_label(substr($gname, 1))) . "</p>\n";
    echo "<div class='section'>\n";
    echo " <table border='0' cellpadding='0'>\n";
    echo "  <tr>";
    for ($i = 1; $i <= $CPR; ++$i) {
      $tmp = $i % 2 ? 'lcols1' : 'dcols1';
      echo "<td class='$tmp'></td>";
    }
    echo "</tr>\n";
    $group_table_active = true;
  }

  // Handle starting of a new row.
  // if (($titlecols > 0 && $cell_count >= $CPR) || $cell_count == 0) {
  if (($cell_count + $titlecols + $datacols) > $CPR || $cell_count == 0) {
    end_row();
    // echo "  <tr style='height:30pt'>";
    echo "  <tr>";
  }

  if ($item_count == 0 && $titlecols == 0) $titlecols = 1;

  // Handle starting of a new label cell.
  if ($titlecols > 0) {
    end_cell();
    echo "<td colspan='$titlecols' ";
    echo "class='lcols$titlecols stuff " . (($frow['uor'] == 2) ? "required'" : "bold'");
    if ($cell_count == 2) echo " style='padding-left:10pt'";
    // echo " nowrap>"; // html2pdf misbehaves with this.
    echo ">";
    $cell_count += $titlecols;
  }
  ++$item_count;

  echo "<b>";
    
  if ($frow['title']) echo (text(xl_layout_label($frow['title'])) . ":"); else echo "&nbsp;";

  echo "</b>";

  // Handle starting of a new data cell.
  if ($datacols > 0) {
    end_cell();
    echo "<td colspan='$datacols' class='dcols$datacols stuff under' style='";

    if ($cell_count > 0) echo "padding-left:5pt;";
    if (in_array($data_type, array(21,27))) {
      // Omit underscore for checkboxes and radio buttons.
      echo "border-width:0 0 0 0;";
    }
    echo "'>";
    $cell_count += $datacols;
  }

  ++$item_count;

  if ($isform) {
    generate_print_field($frow, $currvalue);
  }
  else {
    $s = generate_display_field($frow, $currvalue);
    if ($s === '') $s = '&nbsp;';
    echo $s;
  }
}

// Close all open groups.
if ($group_table_active) {
  end_row();
  echo " </table>\n";
  $group_table_active = false;
}
while (count($group_levels)) {
  $gname = array_pop($group_levels);
  echo "</div>\n";
  echo "</nobreak>\n";
}

$fs = false;
if ($fs && (isset($LBF_SERVICES_SECTION) || isset($LBF_DIAGS_SECTION))) {
  $fs->loadServiceItems();
}

if ($fs && isset($LBF_SERVICES_SECTION)) {
  $s = '';
  foreach ($fs->serviceitems as $lino => $li) {
    // Skip diagnoses; those would be in the Diagnoses section below.
    if ($code_types[$li['codetype']]['diag']) continue;
    $s .= "  <tr>\n";
    $s .= "   <td class='text'>" . text($li['code']) . "&nbsp;</td>\n";
    $s .= "   <td class='text'>" . text($li['code_text']) . "&nbsp;</td>\n";
    $s .= "  </tr>\n";
  }
  if ($s) {
    echo "<nobreak>\n";
    echo "<p class='grpheader'>" . xlt('Services') . "</p>\n";
    echo "<div class='section'>\n";
    echo " <table border='0' cellpadding='0' style='width:'>\n";
    echo $s;
    echo " </table>\n";
    echo "</div>\n";
    echo "</nobreak>\n";
  }
} // End Services Section

if ($fs && isset($LBF_PRODUCTS_SECTION)) {
  $s = '';
  $fs->loadProductItems();
  foreach ($fs->productitems as $lino => $li) {
    $s .= "  <tr>\n";
    $s .= "   <td class='text'>" . text($li['code_text']) . "&nbsp;</td>\n";
    $s .= "   <td class='text' align='right'>" . text($li['units']) . "&nbsp;</td>\n";
    $s .= "  </tr>\n";
  }
  if ($s) {
    echo "<nobreak>\n";
    echo "<p class='grpheader'>" . xlt('Products') . "</p>\n";
    echo "<div class='section'>\n";
    echo " <table border='0' cellpadding='0' style='width:'>\n";
    echo $s;
    echo " </table>\n";
    echo "</div>\n";
    echo "</nobreak>\n";
  }
} // End Products Section

if ($fs && isset($LBF_DIAGS_SECTION)) {
  $s = '';
  foreach ($fs->serviceitems as $lino => $li) {
    // Skip anything that is not a diagnosis; those are in the Services section above.
    if (!$code_types[$li['codetype']]['diag']) continue;
    $s .= "  <tr>\n";
    $s .= "   <td class='text'>" . text($li['code']) . "&nbsp;</td>\n";
    $s .= "   <td class='text'>" . text($li['code_text']) . "&nbsp;</td>\n";
    $s .= "  </tr>\n";
  }
  if ($s) {
    echo "<nobreak>\n";
    echo "<p class='grpheader'>" . xlt('Diagnoses') . "</p>\n";
    echo "<div class='section'>\n";
    echo " <table border='0' cellpadding='0' style='width:'>\n";
    echo $s;
    echo " </table>\n";
    echo "</div>\n";
    echo "</nobreak>\n";
  }
} // End Services Section

?>

</form>
<?php
if ($PDF_OUTPUT) {
  $content = getContent();
  $pdf->writeHTML($content, false);
  $pdf->Output('form.pdf', 'I'); // D = Download, I = Inline
}
else {
?>
<script language='JavaScript'>
window.print();
</script>
</body>
</html>
<?php } ?>
