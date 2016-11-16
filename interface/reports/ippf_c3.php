<?php
// This module creates the Barbados Daily Record.

include_once("../globals.php");
include_once("../../library/patient.inc");
include_once("../../library/acl.inc");

if (!acl_check('acct', 'rep_a')) die(xl("Unauthorized access."));

$from_date     = fixDate($_POST['form_from_date'], date('Y-m-d'));
$to_date       = fixDate($_POST['form_to_date'], $from_date);
$form_facility = isset($_POST['form_facility']) ? $_POST['form_facility'] : '';
$form_output   = isset($_POST['form_output']) ? 0 + $_POST['form_output'] : 1;

$report_title = xl('Clinic Contacts');
$report_col_count = 28;

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

// Get attributes of a single value from the C3 form for this encounter.
// This won't work very well for fields that occur multiple times in one form.
//
function c3_query($pid, $encounter, $fldname, $listname='') {
  $query = "SELECT d.form_id, d.field_value, l1.title, l1.notes " .
    "FROM forms AS f " .
    "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
    "JOIN lbf_data AS d ON d.form_id = f.form_id AND d.field_id = '$fldname' " .
    "LEFT JOIN list_options AS l1 ON l1.list_id = '$listname' AND l1.option_id = d.field_value AND l1.activity = 1 " .
    "WHERE f.pid = '$pid' AND " .
    "f.encounter = '$encounter' AND " .
    "f.formdir = 'LBFc3' AND " .
    "f.deleted = 0 " .
    "ORDER BY d.form_id, d.field_value LIMIT 1";
  return sqlQuery($query);
}

// If we are doing the CSV export then generate the needed HTTP headers.
// Otherwise generate HTML.
//
if ($form_output == 3) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=c3_report.csv");
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
 action='ippf_c3.php'>

<table border='0' cellspacing='5' cellpadding='1'>
 <tr>
  <td class='detail' nowrap>
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
  <td class='detail' nowrap>
   <?php xl('From','e'); ?>:
   <input type='text' name='form_from_date' id='form_from_date' size='10' value='<?php echo $from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Report start date yyyy-mm-dd' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>' />
  </td>
  <td class='detail' nowrap>
   <?php xl('To','e'); ?>:
   <input type='text' name='form_to_date' id='form_to_date' size='10' value='<?php echo $to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Report end date yyyy-mm-dd' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>' />
  </td>
  <td class='detail' nowrap>
   &nbsp;<?php xl('Output','e'); ?>:
  </td>
  <td valign='top' class='detail' nowrap>
<?php
foreach (array(1 => xl('Screen'), 2 => xl('Printer'), 3 => xl('Export')) as $key => $value) {
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
  <td colspan='7' height="1">
  </td>
 </tr>
</table>
<?php
} // end not export

if ($_POST['form_submit']) {

  if ($form_output != 3) {
    echo "<table border='0' cellpadding='1' cellspacing='2' width='98%'>\n";
  } // end not csv export

  // Generate headings.
  genStartRow("bgcolor='#dddddd'");
  genHeadCell(xl('Clinic Number'       ));
  genHeadCell(xl('Order Number'        ));
  genHeadCell(xl('Client Name'         ));
  genHeadCell(xl('Date of Visit'       ));
  genHeadCell(xl('Frequent of Visit'   ));
  genHeadCell(xl('Age'                 ));
  genHeadCell(xl('Sex'                 ));
  genHeadCell(xl('Marital Status'      ));
  genHeadCell(xl('Number of Children'  ));
  genHeadCell(xl('Religion'            ));
  genHeadCell(xl('Residence'           ));
  genHeadCell(xl('Education'           ));
  genHeadCell(xl('Title'               ));
  genHeadCell(xl('Referred By'         ));
  genHeadCell(xl('How Arrived'         ));
  genHeadCell(xl('Services Requested'  ));
  genHeadCell(xl('Services Provided'   ));
  genHeadCell(xl('Menstrual Reg Only'  ));
  genHeadCell(xl('Prev Method bef MR'  ));
  genHeadCell(xl('Method after MR'     ));
  genHeadCell(xl('Status of Services'  ));
  genHeadCell(xl('Referral Status'     ));
  genHeadCell(xl('Result of USG/Preg'  ));
  genHeadCell(xl('Reason Rej/Defer'    ));
  genHeadCell(xl('Reason Asking MR'    ));
  genHeadCell(xl('Condition of Abort'  ));
  genHeadCell(xl('Other Client Efforts'));
  genHeadCell(xl('Client Complaint'    ));
  genHeadCell(xl('Charge of Service'   ));
  genEndRow();

  $encount = 0;

  // This gets us all encounters on the specified date, with patient
  // info attached and grouped by patient and encounter.
  $query = "SELECT " .
    "fe.pid, fe.encounter, fe.date AS encdate, fe.facility_id, " .
    "pd.pubpid, pd.fname, pd.mname, pd.lname, pd.DOB, pd.sex, pd.status, " .
    "pd.userlist5 AS religion, pd.country_code AS country, pd.userlist2 AS education, " .
    "pd.occupation, pd.referral_source AS refsource, " .
    "fy.facility_npi, " .
    "l1.notes AS religion_notes, " .
    "l2.notes AS country_notes, " .
    "l3.notes AS education_notes, " .
    "l4.notes AS occupation_notes, " .
    "l5.notes AS refsource_notes " .
    "FROM form_encounter AS fe " .
    "JOIN patient_data AS pd ON pd.pid = fe.pid " .
    "LEFT JOIN facility AS fy ON fy.id = fe.facility_id " .
    "LEFT JOIN list_options AS l1 ON l1.activity = 1 AND l1.list_id = 'userlist5'    AND l1.option_id = pd.userlist5 " .
    "LEFT JOIN list_options AS l2 ON l2.activity = 1 AND l2.list_id = 'country'      AND l2.option_id = pd.country_code " .
    "LEFT JOIN list_options AS l3 ON l3.activity = 1 AND l3.list_id = 'userlist2'    AND l3.option_id = pd.userlist2 " .
    "LEFT JOIN list_options AS l4 ON l4.activity = 1 AND l4.list_id = 'occupations'  AND l4.option_id = pd.occupation " .
    "LEFT JOIN list_options AS l5 ON l5.activity = 1 AND l5.list_id = 'refsource'    AND l5.option_id = pd.referral_source " .
    "WHERE fe.date >= '$from_date 00:00:00' AND " .
    "fe.date <= '$to_date 23:59:59' ";

  if ($form_facility) {
    $query .= "AND fe.facility_id = '$form_facility' ";
  }
  $query .= "ORDER BY fy.facility_npi, pd.pubpid, fe.date, fe.id";
  $res = sqlStatement($query);

  while ($row = sqlFetchArray($res)) {
    $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
    genStartRow("bgcolor='$bgcolor'");

    $thispid = 0 + $row['pid'];
    $thisenc = 0 + $row['encounter'];
    $encdate = $row['encdate'];

    // Get the most recent history row for this patient.
    $hrow = sqlQuery("SELECT history_offspring " .
      "FROM history_data WHERE pid = '$thispid' ORDER BY date DESC LIMIT 1");

    // 01 Clinic Number: facility id
    genAnyCell($row['facility_npi']);

    // 02 Order Number: client id (likely wrong)
    genAnyCell($row['pubpid']);

    // 03 Name of Client
    $tmp = $row['lname'];
    if ($tmp && $row['fname']) $tmp .= ', ';
    if ($row['fname']) $tmp .= $row['fname'];
    if ($tmp && $row['mname']) $tmp .= ' ';
    if ($row['mname']) $tmp .= $row['mname'];
    genAnyCell($tmp);

    // 04 Date of Visit: DD-MM-YY
    genAnyCell(substr($encdate,8,2) . '-' . substr($encdate,5,2) . '-' . substr($encdate,2,2));

    // 05 Frequent of Visit: 1, 2, >2, 9 (count number of visits thru this date)
    $trow = sqlQuery("SELECT COUNT(*) AS count FROM form_encounter WHERE " .
      "pid = '$thispid' AND date <= '$encdate'");
    $tmp = $trow['count'];
    if ($tmp > 2) $tmp = '>2';
    genAnyCell($tmp);

    // 06 Age: in years
    $age = '';
    $dob = $row['DOB'];
    if ($dob && $encdate) {
      $age = substr($encdate, 0, 4) - substr($dob, 0, 4);
      if (strcmp(substr($encdate, 5), substr($dob, 5)) < 0) --$age;
    }
    genAnyCell($age);

    // 07 Sex: L = male, P = female
    $sex = strtoupper(substr(trim($row['sex']), 0, 1));
    if ($sex == 'F') $sex = 'P';
    else if ($sex == 'M') $sex = 'L';
    genAnyCell($sex);

    // 08 Marital Status: K=married, B=unmarried, CC=divorced, L=other
    $tmp = strtolower(substr($row['status'], 0, 2));
    if ($tmp == 'ma'                     ) $tmp = 'K';
    else if ($tmp == 'si' || $tmp == 'un') $tmp = 'B';
    else if ($tmp == 'di'                ) $tmp = 'CC';
    else                                   $tmp = 'L';
    genAnyCell($tmp);

    // 09 Number of Children: 1, 2, >2, 9=none
    $tmp = '';
    if (is_int($hrow['history_offspring'])) {
      $tmp = 0 + $hrow['history_offspring'];
      if ($tmp < 1) $tmp = '9';
      else if ($tmp > 2) $tmp = '>2';
    }
    genAnyCell($tmp);

    // 10 Religion: IS, KR, KT, HD, BD, LA=other
    // This comes from the "Notes" column of the "userlist5" list.
    genAnyCell(empty($row['religion_notes']) ? $row['religion'] : $row['religion_notes']);

    // 11 Residence: DKO, LKO, LPRO, LN
    // This comes from the "Notes" column of the "country" list.
    genAnyCell(empty($row['country_notes']) ? $row['country'] : $row['country_notes']);

    // 12 Education: TS=no school, SD=elementary, SLP=junior high, SLA=senior high, PT=college
    // This comes from the "Notes" column of the "userlist2" list.
    genAnyCell(empty($row['education_notes']) ? $row['education'] : $row['education_notes']);

    // 13 Title: K=work, TK=unemployed, SK=in school
    // This comes from the "Notes" column of the "occupations" list.
    genAnyCell(empty($row['occupation_notes']) ? $row['occupation'] : $row['occupation_notes']);

    // 14 Referred By: TMN=friend, KEL=family, DOK=doctor/midwife, LA=other
    // This comes from the "Notes" column of the "refsource" list.
    genAnyCell(empty($row['refsource_notes']) ? $row['refsource'] : $row['refsource_notes']);

    // 15 How Arrived: SEN=alone, DS=w/husband, DK=w/relative, DT=w/friend, DP=w/boyfriend
    $tmp = c3_query($thispid, $thisenc, 'c3_howarrived', 'c3_howarrived');
    genAnyCell($tmp['field_value']); // or 'notes'?

    // 16 Services Requested: A 2-digit code
    $tmp = c3_query($thispid, $thisenc, 'c3_rtypeserv', 'c3_typeserv');
    genAnyCell($tmp['field_value']);

    /*****************************************************************
    // 17 Services Provided: A 2-digit code
    // This might be mapped from MA codes, but that won't work for so we might
    // as well keep it consistent.
    $tmp = c3_query($thispid, $thisenc, 'c3_rtypeserv', 'c3_typeserv');
    genAnyCell($tmp['field_value']);
    *****************************************************************/
    // 17 Services Provided: A list of MA codes
    $bres = sqlStatement("SELECT code FROM billing WHERE " .
      "pid = '$thispid' AND encounter = '$thisenc' AND " .
      "code_type = 'MA' AND activity = 1 ORDER BY code");
    $tmp = '';
    while ($brow = sqlFetchArray($bres)) {
      if ($tmp) $tmp .= ' ';
      $tmp .= $brow['code'];
    }
    genAnyCell($tmp);

    // 18 Menstrual Reg Only: A 1-digit code, 1-7, indicating if pre/post
    // counseling or FP services were provided along with MR
    $tmp = c3_query($thispid, $thisenc, 'c3_menreg', 'c3_menreg');
    genAnyCell($tmp['field_value']);

    // 19 Prev Method bef MR: Another 1-digit code
    // Could use lists/lists_ippf_con.prev_method + "contrameth" list,
    // if they are always using contraceptive issues.
    $tmp = c3_query($thispid, $thisenc, 'c3_methods', 'c3_methods');
    genAnyCell($tmp['field_value']);

    // Method after MR: Another 1-digit code
    // Same comments as above.
    $tmp = c3_query($thispid, $thisenc, 'c3_methodsaft', 'c3_methods');
    genAnyCell($tmp['field_value']);

    // 20 Status of Services: DILA=served, DITO=rejected, DITU=postponed, DIRU=referred
    $tmp = c3_query($thispid, $thisenc, 'c3_servstat', 'c3_servstat');
    genAnyCell($tmp['field_value']);

    // 21 Referral Status: 1=IPPA, 2=beyond IPPA, 3=others/not referred
    $tmp = c3_query($thispid, $thisenc, 'c3_refstat', 'c3_refstat');
    genAnyCell($tmp['field_value']);

    // 22 Result of USG/Preg: Number of weeks of pregnancy
    $tmp = c3_query($thispid, $thisenc, 'c3_pregweeks');
    genAnyCell(0 + $tmp['field_value']);

    // 23 Reason Rej/Defer: A 1-digit code
    $tmp = c3_query($thispid, $thisenc, 'c3_rreason', 'c3_rreason');
    genAnyCell($tmp['field_value']);

    // 24 Reason Asking MR: A 1-digit code
    $tmp = c3_query($thispid, $thisenc, 'c3_reason', 'c3_reason');
    genAnyCell($tmp['field_value']);

    // 25 Condition of Abort: A 1-digit code
    $tmp = c3_query($thispid, $thisenc, 'c3_condition', 'c3_condition');
    genAnyCell($tmp['field_value']);

    // 26 Other Client Efforts: A 1-digit code
    $tmp = c3_query($thispid, $thisenc, 'c3_efforts', 'c3_efforts');
    genAnyCell($tmp['field_value']);

    // 27 Client Complaint: A 1-digit code
    $tmp = c3_query($thispid, $thisenc, 'c3_complaint', 'c3_complaint');
    genAnyCell($tmp['field_value']);

    // 28 Charge of Service: A 1-digit code indicating a range
    // Assuming this only concerns MA services and not products or adjustments.
    $brow = sqlQuery("SELECT SUM(fee) AS fee FROM billing WHERE " .
      "pid = '$thispid' AND encounter = '$thisenc' AND " .
      "code_type = 'MA' AND activity = 1");
    $tmp = $brow['fee'];
    if      ($tmp < 0.01) $tmp = '1';
    else if ($tmp <  100) $tmp = '2';
    else if ($tmp <  500) $tmp = '3';
    else if ($tmp < 1000) $tmp = '4';
    else                  $tmp = '5';
    genAnyCell($tmp);

    genEndRow();
  } // end while

  if ($form_output != 3) {
    // There is nothing to total, but if there were we would write a totals line here.
    echo "</table>\n";
  }

} // end if submit

if ($form_output != 3) {
?>
</form>
</center>

<script language='JavaScript'>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date"  , ifFormat:"%Y-%m-%d", button:"img_to_date"  });
<?php if ($form_output == 2) { ?>
 window.print();
<?php } ?>
</script>

</body>
</html>
<?php
} // end not export
?>
