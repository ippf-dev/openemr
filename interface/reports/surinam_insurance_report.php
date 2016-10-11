<?php
// Copyright (C) 2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is a report of services and items provided that are linked to certain
// adjustment types, where the adjustment types are taken to be insurance payers.
// The purpose is to get reimbursement from the payers.

$fake_register_globals = false;
$sanitize_all_escapes = true;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/options.inc.php");
require_once("../../custom/code_types.inc.php");

if (!empty($_POST['form_pdf'])) {
  require_once("$srcdir/tcpdf_min/tcpdf.php");

  // Things commonly customized. Sizes are in points, at 72 points/inch.
  // Reasonable font choices are courier, helvetica and times which are "core fonts" for tcpdf.
  $PDF_FONT_NAME      = 'helvetica';
  $PDF_FONT_SIZE      =  10;
  $PDF_LINE_HEIGHT    =  12; // 6 lines/inch = 12 points/line
  $PDF_PAGE_WIDTH     = 612; // 8.5 inches
  $PDF_PAGE_HEIGHT    = 792; // 11 inches
  $PDF_LRMARGIN       =  36; // left and right margins
}

// Attributes of the report columns.
// Width attribute only matters for PDF output.
$colheads = array(
  //        Heading Text          Width%  Align
  array(xl('Visit Date'           ), 15, 'left'),
  array(xl('Client Name'          ), 20, 'left'),
  array(xl('File/Insurance Number'), 15, 'left'),
  array(xl('Service or Product'   ), 35, 'left'),
  array(xl('Amount'               ), 15, 'right'),
);

// Get a POST value without logging a warning when not defined.
function getPost($name) {
  if (isset($_POST[$name])) return $_POST[$name];
  return '';
}

// This supports the "tbl" functions below.
$tblNewRow = true;

// This string will hold simple HTML to be written via $pdf->writeHTML().
// It starts and ends with <table> and </table> and represents all of the data
// for one insurer.
$pdf_data = '';

function tblPDF($s) {
  global $pdf, $pdf_data;
  // $pdf->writeHTML($s, true, false, false, false, '');
  $pdf_data .= $s;
}

function tblStartRow() {
  global $tblNewRow;
  $tblNewRow = true;
  if (getPost('form_refresh')) {
    echo " <tr>\n";
  }
  else if (getPost('form_pdf')) {
    tblPDF('<tr>');
  }
}

function tblCell($data, $colno, $isRepeated=false) {
  global $colheads, $tblNewRow, $PDF_PAGE_WIDTH, $PDF_LRMARGIN;
  if (getPost('form_csvexport')) {
    if (!$tblNewRow) echo ',';
    echo '"' . addslashes($data) . '"';
  }
  else if (getPost('form_pdf')) {
    $width = $PDF_PAGE_WIDTH - ($PDF_LRMARGIN * 2);
    $s = '<td';
    $s .= ' width="' . round($colheads[$colno][1] * $width * 0.01) . '"';
    $s .= ' align="' . $colheads[$colno][2] . '"';
    $s .= '>';
    if ($data === '' || $isRepeated) $s .= ' ';
    else $s .= text($data);
    $s .= '</td>';
    tblPDF($s);
  }
  else {
    echo '  <td class="detail"';
    echo ' align="' . $colheads[$colno][2] . '"';
    echo ">";
    if ($data === '' || $isRepeated) echo "&nbsp;";
    else echo htmlspecialchars($data);
    echo "</td>\n";
  }
  $tblNewRow = false;
}

function tblEndRow() {
  global $tblNewRow;
  if (!$tblNewRow) {
    if (getPost('form_csvexport')) {
      echo "\n";
    }
    else if (getPost('form_pdf')) {
      tblPDF('</tr>');
    }
    else {
      echo " </tr>\n";
    }
    $tblNewRow = true;
  }
}

// This generates the output data corresponding to the end of the current insurer.
// Mostly this is the total line.
//
function tblEndInsurer($insname, $instotal) {
  global $pdf, $pdf_data;

  if (getPost('form_refresh')) {
    // Generate insurer total line.
    echo " <tr style='background-color:#DDDDDD;'>\n";
    echo "  <td colspan='4' class='dehead'>" . xl('Total for') . ' ' . $insname . "</td>\n";
    echo "  <td class='dehead' align='right'>" . oeFormatMoney($instotal) . "</td>\n";
    echo " </tr>\n";
    // Generate a blank row for separation between insurers.
    echo " <tr>\n";
    echo "  <td colspan='5' class='detail'>&nbsp;</td>\n";
    echo " </tr>\n";
  }

  else if (getPost('form_pdf')) {
    // Generate insurer total line.
    tblPDF('<tr style="background-color:#CCCCCC;">');
    tblPDF('<td colspan="4" align="left"><b>' . xl('Total for') . ' ' . text($insname) . '</b></td>');
    tblPDF('<td align="right"><b>' . oeFormatMoney($instotal) . '</b></td>');
    tblPDF('</tr>');
    // The PDF has a separate table per insurer, so end the table.
    tblPDF('</table>');
    $pdf->writeHTML($pdf_data, true, false, false, false, '');
  }
}

// This generates the output data corresponding to the beginning of a new insurer.
// Mostly this is header stuff. For the PDF case we also start a new page.
//
function tblStartInsurer($insname) {
  global $pdf, $pdf_data, $PDF_PAGE_WIDTH, $PDF_LRMARGIN, $colheads;

  if (getPost('form_refresh')) {
    echo " <tr style='background-color:#DDDDDD;'>\n";
    echo "  <td class='dehead' colspan='" . count($colheads) . "' align='center'>" . text($insname) . "</td>\n";
    echo " </tr>\n";
    echo " <tr style='background-color:#DDDDDD;'>\n";
    foreach ($colheads as $ch) {
      echo "  <td class='dehead' align='" . $ch[2] . "'>" . text($ch[0]) . "</td>\n";
    }
    echo " </tr>\n";
  } // end refresh

  else if (getPost('form_pdf')) {
    $width = $PDF_PAGE_WIDTH - ($PDF_LRMARGIN * 2);
    // Restart page numbers.
    $pdf->startPageGroup();
    $pdf->AddPage();
    // Each insurer has their own table.
    $pdf_data = '';
    tblPDF('<table cellspacing="0" cellpadding="1" border="0">');
    tblPDF('<thead>');
    // Table header line for the insurer name.
    tblPDF('<tr style="background-color:#AAAAAA;">');
    tblPDF('<td colspan="' . count($colheads) . '" align="center"><b>' . text($insname) . '</b></td>');
    tblPDF('</tr>');
    // Another header line for column labels.
    tblPDF('<tr style="background-color:#CCCCCC;">');
    foreach ($colheads as $ch) {
      tblPDF('<td width="' . round($width * $ch[1] / 100) . '" align="' . $ch[2] . '">' .
        text($ch[0]) . '</td>');
    }
    tblPDF('</tr>');
    tblPDF('</thead>');
  }
}

if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));

$form_from_date = fixDate(getPost('form_from_date'), date('Y-m-01'));
$form_to_date   = fixDate(getPost('form_to_date'), date('Y-m-d'));
$form_insurer   = getPost('form_insurer');
$form_facility  = getPost('form_facility');

$facility_name = 'All Facilities';
if ($form_facility) {
  $frow = sqlQuery("SELECT name FROM facility WHERE id = ?", array($form_facility));
  $facility_name = $frow['name'];
}

// CSV export initialization.
if (getPost('form_csvexport')) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=insurance_report.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";
  // CSV headers:
  $lastkey = count($colheads) - 1;
  foreach ($colheads as $chkey => $ch) {
    echo '"' . $ch[0] . '"' . ($chkey == $lastkey ? "\n" : ",");
  }
}

// PDF initialization.
else if (getPost('form_pdf')) {
  $pdf = new TCPDF('P', 'pt', array($PDF_PAGE_WIDTH, $PDF_PAGE_HEIGHT), true, 'UTF-8', false);
  // Set header and footer data.
  $tmp = xl('From') . ' ' . oeFormatShortDate(substr($form_from_date, 0, 10)) . ' ' .
    xl('to') . ' ' . oeFormatShortDate(substr($form_to_date, 0, 10)) . ' ' .
    xl('for') . ' ' . $facility_name;
  $pdf->setHeaderData('', 0, xl('LOBI Insurers Report'), $tmp);
  $pdf->setFooterData(array(0,0,0), array(0,0,0));
  // Set header and footer fonts.
  $pdf->setHeaderFont(array($PDF_FONT_NAME, '', round($PDF_FONT_SIZE * 1.2)));
  $pdf->setFooterFont(array($PDF_FONT_NAME, '', round($PDF_FONT_SIZE * 1.0)));
  // Set default monospaced font.
  $pdf->SetDefaultMonospacedFont('courier');
  // Set margins.
  $pdf->SetMargins($PDF_LRMARGIN, 80, $PDF_LRMARGIN);
  $pdf->SetHeaderMargin(36);
  $pdf->SetFooterMargin(36);
  // Set auto page breaks.
  $pdf->SetAutoPageBreak(TRUE, 36);
  // Set initial font.
  $pdf->SetFont($PDF_FONT_NAME, '', $PDF_FONT_SIZE);
}

// HTML output initialization.
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('LOBI Insurers Report') ?></title>

<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
 .delink { color:#0000cc; font-family:sans-serif; font-size:10pt; font-weight:normal; cursor:pointer }
</style>

<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script language="JavaScript">
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php echo xlt('LOBI Insurers Report'); ?></h2>

<form method='post' action='surinam_insurance_report.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td align='center'>
<?php
  // Build a drop-down list of insurers.
  //
  echo generate_select_list('form_insurer', 'userlist4', $form_insurer, '',
    '-- ' . xl('All Insurers') . ' --');
?>
  &nbsp;
<?php
  // Build a drop-down list of facilities.
  //
  $query = "SELECT id, name FROM facility ORDER BY name";
  $fres = sqlStatement($query);
  echo "   <select name='form_facility'>\n";
  echo "    <option value=''>-- " . xlt('All Facilities') . " --</option>\n";
  while ($frow = sqlFetchArray($fres)) {
    $facid = $frow['id'];
    echo "    <option value='$facid'";
    if ($facid == $form_facility) echo " selected";
    echo ">" . text($frow['name']) . "</option>\n";
  }
  echo "   </select>\n";
?>
  </td>
 </tr>
 <tr>
  <td align='center' class='dehead'>
   &nbsp;<?php echo xlt('From'); ?>:
   <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer;vertical-align:middle;'
    title='<?php echo xla('Click here to choose a date'); ?>'>
   &nbsp;<?php echo xlt('To'); ?>:
   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer;vertical-align:middle;'
    title='<?php echo xla('Click here to choose a date'); ?>'>
   &nbsp;
   <input type='submit' name='form_refresh' value="<?php echo xla('Display'); ?>">
   &nbsp;
   <input type='submit' name='form_pdf' value="<?php echo xla('Generate PDF'); ?>">
   &nbsp;
   <input type='submit' name='form_csvexport' value="<?php echo xla('Export to CSV'); ?>">
   <!--
   &nbsp;
   <input type='button' value='<?php echo xla('Print'); ?>' onclick='window.print()' />
   -->
  </td>
 </tr>

 <tr>
  <td height="1">
  </td>
 </tr>

</table>
<?php
} // end not export nor pdf

// Initialization when we are generating the report in HTML format.
if (getPost('form_refresh')) {
  echo "<table border='0' cellpadding='1' cellspacing='2' width='98%'>\n";
} // end refresh

// Things to do when we are generating the report in any format.
if (getPost('form_refresh') || getPost('form_csvexport') || getPost('form_pdf')) {
  $instotal = 0;
  $last_insid = '';
  $last_insname = '';

  // Main loop is on insurer.
  $query = "SELECT DISTINCT lo.title AS insname, pd.userlist4 AS insid " .
    "FROM form_encounter AS fe " .
    "JOIN patient_data AS pd ON pd.pid = fe.pid " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'userlist4' AND lo.option_id = pd.userlist4 AND lo.activity = 1 " .
    "WHERE fe.date >= ? AND fe.date <= ?";
  $qparms = array("$form_from_date 00:00:00", "$form_to_date 23:59:59");
  // If an insurer was specified.
  if ($form_insurer) {
    $query .= " AND pd.userlist4 = ?";
    $qparms[] = $form_insurer;
  }
  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = ?";
    $qparms[] = $form_facility;
  }
  // Sort by insurer, client and visit date.
  $query .= " ORDER BY insname";

  $res = sqlStatement($query, $qparms);
  while ($row = sqlFetchArray($res)) {

    $insid      = $row['insid'];
    $insname    = empty($row['insname']) ? "($insid)" : $row['insname'];

    // echo "<!-- ins id = '$insid', name = '$insname' -->\n"; // debugging

    // Get service items.
    $query = "SELECT " .
      "fe.pid, fe.encounter, fe.date, " .
      "pd.lname, pd.fname, pd.mname, pd.pubpid, pd.usertext8, pd.userlist4, " .
      "f.name AS facname, " .
      "b.id, b.code_text, b.units, b.fee, c.code_text_short " .
      "FROM form_encounter AS fe " .
      "JOIN patient_data AS pd ON pd.pid = fe.pid AND pd.userlist4 = ? " .
      "JOIN billing AS b ON b.pid = fe.pid AND b.encounter = fe.encounter AND b.activity = 1 AND b.fee != 0.00 " .
      "JOIN ar_activity AS a ON a.pid = b.pid AND a.encounter = b.encounter AND " .
      "  ( a.pay_amount = 0 OR a.adj_amount != 0 ) AND " .
      "  ( a.code_type = '' OR ( a.code_type = b.code_type AND a.code = b.code ) ) " .
      "JOIN list_options AS lo ON lo.list_id = 'adjreason' AND lo.option_id = a.memo AND lo.activity = 1 AND lo.notes LIKE '%=Ins%' " .
      "LEFT JOIN facility AS f ON f.id = fe.facility_id " .
      "LEFT JOIN code_types AS ct ON ct.ct_key = b.code_type " .
      "LEFT JOIN codes AS c ON c.code_type = ct.ct_id AND c.code = b.code AND c.modifier = b.modifier " .
      "WHERE " .
      "fe.date >= ? AND fe.date <= ?";
    $qparms = array($insid, "$form_from_date 00:00:00", "$form_to_date 23:59:59");
    // If a facility was specified.
    if ($form_facility) {
      $query .= " AND fe.facility_id IS NOT NULL AND fe.facility_id = ?";
      $qparms[] = $form_facility;
    }
    $query .= " ORDER BY b.code_text, b.id, pd.lname, pd.fname, fe.pid, fe.date, fe.encounter";

    $bres = sqlStatement($query, $qparms);
    $last_billing_id = 0;

    while ($brow = sqlFetchArray($bres)) {
      if ($insid != $last_insid) {
        if ($last_insid !== '') {
          tblEndInsurer($last_insname, $instotal);
        }
        $last_insid = $insid;
        $last_insname = $insname;
        $instotal = 0;
        tblStartInsurer($last_insname);
      }

      $ptname = $brow['lname'];
      if ($brow['fname'] || $brow['mname']) {
        $ptname .= ', ' . $brow['fname'];
        if ($brow['mname']) $ptname .= ' ' . $brow['mname'];
      }

      // Skip any extra adjustment matches for the same line item.
      if ($brow['id'] == $last_billing_id) continue;

      // Client wants to use the short code description. We do that when there is one, otherwise
      // it's possible a code has no short description, or perhaps was used and then deleted.
      $code_text = empty($brow['code_text_short']) ? $brow['code_text'] : $brow['code_text_short'];

      tblStartRow();
      tblCell(oeFormatShortDate(substr($brow['date'], 0, 10)), 0);
      tblCell($ptname                                        , 1);
      tblCell($brow['usertext8']                             , 2);
      tblCell($code_text                                     , 3);
      tblCell(oeFormatMoney($brow['fee'])                    , 4);
      tblEndRow();

      $instotal += $brow['fee'];
      $last_billing_id = 0 + $brow['id'];
    }

    // Products.
    $query = "SELECT " .
      "fe.pid, fe.encounter, fe.date, " .
      "pd.lname, pd.fname, pd.mname, pd.pubpid, pd.usertext8, pd.userlist4, " .
      "f.name AS facname, " .
      "s.fee, s.quantity, s.sale_id, d.name " .
      "FROM form_encounter AS fe " .
      "JOIN patient_data AS pd ON pd.pid = fe.pid AND pd.userlist4 = ? " .
      "JOIN drug_sales AS s ON s.pid = fe.pid AND s.encounter = fe.encounter AND s.fee != 0.00 " .
      "JOIN ar_activity AS a ON a.pid = s.pid AND a.encounter = s.encounter AND " .
      "  ( a.pay_amount = 0 OR a.adj_amount != 0 ) AND " .
      "  ( a.code_type = '' OR ( a.code_type = 'PROD' AND a.code = s.drug_id ) ) " .
      "JOIN list_options AS lo ON lo.list_id = 'adjreason' AND lo.option_id = a.memo AND lo.activity = 1 AND lo.notes LIKE '%=Ins%' " .
      "LEFT JOIN drugs AS d ON d.drug_id = s.drug_id " .
      "LEFT JOIN facility AS f ON f.id = fe.facility_id " .
      "WHERE " .
      "fe.date >= ? AND fe.date <= ?";
    $qparms = array($insid, "$form_from_date 00:00:00", "$form_to_date 23:59:59");
    // If a facility was specified.
    if ($form_facility) {
      $query .= " AND fe.facility_id IS NOT NULL AND fe.facility_id = ?";
      $qparms[] = $form_facility;
    }
    $query .= " ORDER BY d.name, s.drug_id, pd.lname, pd.fname, fe.pid, fe.date, fe.encounter";

    $sres = sqlStatement($query, $qparms);
    $last_sale_id = 0;

    while ($srow = sqlFetchArray($sres)) {
      if ($insid != $last_insid) {
        if ($last_insid !== '') {
          tblEndInsurer($last_insname, $instotal);
        }
        $last_insid = $insid;
        $last_insname = $insname;
        $instotal = 0;
        tblStartInsurer($last_insname);
      }

      // Skip any extra adjustment matches for the same line item.
      if ($srow['sale_id'] == $last_sale_id) continue;

      $ptname = $srow['lname'];
      if ($srow['fname'] || $srow['mname']) {
        $ptname .= ', ' . $srow['fname'];
        if ($srow['mname']) $ptname .= ' ' . $srow['mname'];
      }

      tblStartRow();
      tblCell(oeFormatShortDate(substr($srow['date'], 0, 10)), 0);
      tblCell($ptname                                        , 1);
      tblCell($srow['usertext8']                             , 2);
      tblCell($srow['name']                                  , 3);
      tblCell(oeFormatMoney($srow['fee'])                    , 4);
      tblEndRow();

      $instotal += $srow['fee'];
      $last_sale_id = 0 + $srow['sale_id'];
    }
  }

  if ($last_insid !== '') {
    tblEndInsurer($last_insname, $instotal);
  }

  if (getPost('form_pdf')) {
    // Close and output the PDF document. I = inline to the browser.
    $pdf->Output('insurers_report.pdf', 'I');
  }

} // End refresh or export or pdf

if (getPost('form_refresh')) {
?>

</table>
</form>
</center>
</body>

<?php
}
if (!getPost('form_csvexport') && !getPost('form_pdf')) {
?>

<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>

</html>
<?php
} // End not csv export nor pdf

// php end tag omitted.
