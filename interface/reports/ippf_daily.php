<?php
// This module creates the Barbados Daily Record.

include_once("../globals.php");
include_once("../../library/patient.inc");
include_once("../../library/acl.inc");
include_once("../../custom/code_types.inc.php");

// Might want something different here.
//
if (! acl_check('acct', 'rep')) die("Unauthorized access.");

$from_date     = fixDate($_POST['form_from_date']);
$form_facility = isset($_POST['form_facility']) ? $_POST['form_facility'] : '';
$form_output   = isset($_POST['form_output']) ? 0 + $_POST['form_output'] : 1;

$report_title = xl('Clinic Daily Record');
$report_col_count = 12;

// This will become the array of reportable values.
$areport = array();

// This accumulates the bottom line totals.
$atotals = array();

$cellcount = 0;

function genStartRow($att) {
  global $cellcount, $form_output;
  if ($form_output != 3) echo " <tr $att>\n";
  $cellcount = 0;
}

function genEndRow() {
  global $form_output;
  if ($form_output == 3) {
    echo "\n";
  }
  else {
    echo " </tr>\n";
  }
}

// Usually this generates one cell, but allows for two or more.
//
function genAnyCell($data, $right=false, $class='') {
  global $cellcount, $form_output;
  if (!is_array($data)) {
    $data = array(0 => $data);
  }
  foreach ($data as $datum) {
    if ($form_output == 3) {
      if ($cellcount) echo ',';
      echo '"' . $datum . '"';
    }
    else {
      echo "  <td";
      if ($class) echo " class='$class'";
      if ($right) echo " align='right'";
      echo ">$datum</td>\n";
    }
    ++$cellcount;
  }
}

function genHeadCell($data, $right=false) {
  genAnyCell($data, $right, 'dehead');
}

// Create an HTML table cell containing a numeric value, and track totals.
//
function genNumCell($num, $cnum) {
  global $atotals, $form_output;
  $atotals[$cnum] += $num;
  if (empty($num) && $form_output != 3) $num = '&nbsp;';
  genAnyCell($num, true, 'detail');
}

// Recursive function to look up the IPPF2 (or other type) code, if any,
// for a given related code field.
//
function get_related_code($related_code, $typewanted='IPPF2', $depth=0) {
  global $code_types;
  // echo "<!-- related_code = '$related_code' depth = '$depth' -->\n"; // debugging
  if (++$depth > 4) return false; // protects against relation loops
  if (empty($related_code)) return false;
  $relcodes = explode(';', $related_code);
  foreach ($relcodes as $codestring) {
    if ($codestring === '') continue;
    list($codetype, $code) = explode(':', $codestring);
    if ($codetype === $typewanted) {
      // echo "<!-- returning '$code' -->\n"; // debugging
      return $code;
    }
    $row = sqlQuery("SELECT related_code FROM codes WHERE " .
      "code_type = ? AND code = ? AND active = 1 " .
      "ORDER BY id LIMIT 1",
      array($code_types[$codetype]['id'], $code));
    $tmp = get_related_code($row['related_code'], $typewanted, $depth);
    if ($tmp !== false) {
      return $tmp;
    }
  }
  return false;
}

// If we are doing the CSV export then generate the needed HTTP headers.
// Otherwise generate HTML.
//
if ($form_output == 3) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=service_statistics_report.csv");
  header("Content-Description: File Transfer");
}
else { // not export
?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo $report_title; ?></title>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<style type="text/css">
 body       { font-family:sans-serif; font-size:10pt; font-weight:normal }
 .dehead    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="JavaScript">
 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';
</script>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

<center>

<h2><?php echo $report_title; ?></h2>

<form name='theform' method='post'
 action='ippf_daily.php?t=<?php echo $report_type ?>'>

<table border='0' cellspacing='5' cellpadding='1'>
 <tr>
  <td valign='top' class='detail' nowrap>
   <?php xl('Facility','e'); ?>:
  </td>
  <td valign='top' class='detail'>
<?php
 // Build a drop-down list of facilities.
 //
 $query = "SELECT id, name FROM facility ORDER BY name";
 $fres = sqlStatement($query);
 echo "   <select name='form_facility'>\n";
 echo "    <option value=''>-- " . xl('All Facilities') . " --\n";
 while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  echo "    <option value='$facid'";
  if ($facid == $_POST['form_facility']) echo " selected";
  echo ">" . $frow['name'] . "\n";
 }
 echo "   </select>\n";
?>
  </td>
  <td colspan='2' class='detail' nowrap>
   <?php xl('Date','e'); ?>
   <input type='text' name='form_from_date' id='form_from_date' size='10' value='<?php echo $from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Report date yyyy-mm-dd' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>' />
  </td>
  <td valign='top' class='dehead' nowrap>
   <?php xl('To','e'); ?>:
  </td>
  <td colspan='3' valign='top' class='detail' nowrap>
<?php
foreach (array(1 => xl('Screen'), 2 => xl('Printer'), 3 => xl('Export File')) as $key => $value) {
  echo "   <input type='radio' name='form_output' value='$key'";
  if ($key == $form_output) echo ' checked';
  echo " />$value &nbsp;";
}
?>
  </td>
  <td align='right' valign='top' class='detail' nowrap>
   <input type='submit' name='form_submit' value='<?php xl('Submit','e'); ?>'
    title='<?php xl('Click to generate the report','e'); ?>' />
  </td>
 </tr>
 <tr>
  <td colspan='5' height="1">
  </td>
 </tr>
</table>
<?php
} // end not export

if ($_POST['form_submit']) {

  $lores = sqlStatement("SELECT option_id, title FROM list_options WHERE " .
    "list_id = 'contrameth' AND activity = 1 ORDER BY title");
  while ($lorow = sqlFetchArray($lores)) {
    $areport[$lorow['option_id']] = array($lorow['title'],
      0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
  }
  $areport['zzz'] = array('Unknown', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

  // This gets us all MA codes, with encounter and patient
  // info attached and grouped by patient and encounter.
  $query = "SELECT " .
    "fe.pid, fe.encounter, fe.date AS encdate, fe.pc_catid, " .
    "pd.regdate, b.code_type, b.code " .
    "FROM form_encounter AS fe " .
    "JOIN patient_data AS pd ON pd.pid = fe.pid " .
    "LEFT JOIN billing AS b ON " .
    "b.pid = fe.pid AND b.encounter = fe.encounter AND b.activity = 1 " .
    "AND b.code_type = 'MA' " .
    "WHERE fe.date >= '$from_date 00:00:00' AND " .
    "fe.date <= '$from_date 23:59:59' ";

  if ($form_facility) {
    $query .= "AND fe.facility_id = '$form_facility' ";
  }
  $query .= "ORDER BY fe.pid, fe.encounter, b.code";
  $res = sqlStatement($query);

  $last_pid = '0';
  $last_contra_pid = '0';
  $last_encounter = '0';
  $method = '';

  while ($row = sqlFetchArray($res)) {
    if ($row['code_type'] === 'MA') {

      // Logic for individual patients.
      //
      if ($row['pid'] != $last_pid) { // new patient
        $last_pid = $row['pid'];

        // Get the current contraceptive method as of the report date.
        // This is an IPPFCM code, or nothing.
        $method = '';
        $contrameth_row = sqlQuery("SELECT " .
          "fe.date AS contrastart, d1.field_value AS contrameth FROM forms AS f " .
          "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
          "JOIN lbf_data AS d1 ON d1.form_id = f.form_id AND d1.field_id = 'newmethod' " .
          "WHERE f.formdir = 'LBFccicon' AND f.deleted = 0 AND f.pid = ? " .
          "AND fe.date <= ? ORDER BY contrastart DESC LIMIT 1",
          array($last_pid, "$from_date 23:59:59"));
        if (!empty($contrameth_row['contrameth'])) {
          $methodid = substr($contrameth_row['contrameth'], 7);
          // Get the method category name.
          $crow = sqlQuery("SELECT lo.option_id, lo.title " .
            "FROM codes AS c " .
            "JOIN list_options AS lo ON lo.list_id = 'contrameth' AND " .
            "lo.option_id = c.code_text_short AND lo.activity = 1 " .
            "WHERE c.code_type = '32' AND c.code = ?",
            array($methodid));
          if (!empty($crow['option_id'])) {
            $method = $crow['option_id'];
            // $methodcat = $crow['title'];
          }
        }

        if (empty($areport[$method])) {
          // This should not happen.
          $areport[$method] = array("Unlisted method '$method'",
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        }

        // Count total clients.
        ++$areport[$method][3];

        // Count as new or old client.
        if ($row['regdate'] == $from_date) {
          ++$areport[$method][1];
        } else {
          ++$areport[$method][2];
        }

        //// Maybe count as old Client First Visit this year.
        // $regyear = substr($row['regdate'], 0, 4);
        // $thisyear = substr($from_date, 0, 4);
        // if ($regyear && $regyear < $thisyear) {
        //   $trow = sqlQuery("SELECT count(*) AS count FROM form_encounter " .
        //     "WHERE date >= '$thisyear-01-01 00:00:00' AND " .
        //     "date < '" . $row['encdate'] . " 00:00:00'");
        //   if (empty($trow['count'])) ++$areport[$method][5];
        // }

      } // end new patient

      // Logic for visits.
      //
      if ($row['encounter'] != $last_encounter) { // new visit
        $last_encounter = $row['encounter'];
        // Count visits with any contraceptive service, but only once per visit.
        $has_contra_svc = false;
      }
      if (!$has_contra_svc && get_related_code("MA:$code", 'IPPFCM')) {
        ++$areport[$method][4];
        $has_contra_svc = true;
      }

      // Logic for specific services.
      //
      $code = $row['code'];
      $icode = get_related_code("MA:$code", 'IPPF2');

      if ($icode == '2144040402102') ++$areport[$method][5];  // pap smear
      if ($icode == '2155050503301') ++$areport[$method][6];  // preg test
      if ($icode == '2142020200000') ++$areport[$method][7];  // dr's check
      if ($icode == '3120020000000') ++$areport[$method][8];  // dr's visit (was 375014)
      if ($icode == '2184100000800') ++$areport[$method][9];  // advice
      if ($icode == '1110010900000') ++$areport[$method][10]; // couns by method
      if ($icode == '2171110000000') ++$areport[$method][11]; // infert couns
      if ($icode == '2131010111000') ++$areport[$method][12]; // std/aids couns
      if ($icode == '2131010112000') ++$areport[$method][12]; // std/aids couns
      if ($icode == '2131010123000') ++$areport[$method][12]; // std/aids couns
      if ($icode == '2121010112000') ++$areport[$method][12]; // std/aids couns
      if ($icode == '2121010123000') ++$areport[$method][12]; // std/aids couns
      if ($icode == '2121010124000') ++$areport[$method][12]; // std/aids couns

    }
  } // end while

  if ($form_output != 3) {
    echo "<table border='0' cellpadding='1' cellspacing='2' width='98%'>\n";
  } // end not csv export

  // Generate headings.
  genStartRow("bgcolor='#dddddd'");
  genHeadCell(xl('Method'         ));
  genHeadCell(xl('New Clients'    ), true);
  genHeadCell(xl('Old Clients'    ), true);
  genHeadCell(xl('Total Clients'  ), true);
  genHeadCell(xl('Contra Clients' ), true);
  // genHeadCell(xl('O.A.F.V.'       ), true);
  genHeadCell(xl('Pap Smear'      ), true);
  genHeadCell(xl('Preg Test'      ), true);
  genHeadCell(xl('Dr Check'       ), true);
  genHeadCell(xl('Dr Visit'       ), true);
  genHeadCell(xl('Advice'         ), true);
  genHeadCell(xl('Couns by Method'), true);
  genHeadCell(xl('Infert Couns'   ), true);
  genHeadCell(xl('STD/AIDS Couns' ), true);
  genEndRow();

  $encount = 0;

  foreach ($areport as $key => $varr) {
    $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
    genStartRow("bgcolor='$bgcolor'");
    genAnyCell($varr[0], false, 'detail');
    // Generate data and accumulate totals for this row.
    for ($cnum = 0; $cnum < $report_col_count; ++$cnum) {
      genNumCell($varr[$cnum + 1], $cnum);
    }
    genEndRow();
  } // end foreach

  if ($form_output != 3) {
    // Generate the line of totals.
    genStartRow("bgcolor='#dddddd'");
    genHeadCell(xl('Totals'));
    for ($cnum = 0; $cnum < $report_col_count; ++$cnum) {
      genHeadCell($atotals[$cnum], true);
    }
    genEndRow();
    // End of table.
    echo "</table>\n";
  }

} // end if submit

if ($form_output != 3) {
?>
</form>
</center>

<script language='JavaScript'>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
<?php if ($form_output == 2) { ?>
 window.print();
<?php } ?>
</script>

</body>
</html>
<?php
} // end not export
?>
