<?php
// Copyright (C) 2018 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/invoice_summary.inc.php");
require_once("$srcdir/sl_eob.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";

if (!acl_check('acct', 'rep_a')) die(xl("Unauthorized access."));

$alertmsg = '';
$today = date("Y-m-d");

$form_from_date = fixDate($_POST['form_from_date'], "2010-01-01");
$form_to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));
?>
<html>
<head>
<?php if (function_exists('html_header_show')) html_header_show(); ?>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<title><?php echo xlt('Automatic Adjustments')?></title>
<style type="text/css">
</style>

<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>

<script language="JavaScript">
</script>

</head>

<body class="body_top">

<span class='title'><?php echo xlt('Automatic Adjustments'); ?></span>

<?php
if ($_POST['form_refresh']) {
  echo "<br />\n";
  $query = "SELECT f.id, f.date, f.pid, f.encounter, f.last_level_billed, " .
    "f.last_level_closed, f.last_stmt_date, f.stmt_count, f.invoice_refno, " .
    "p.fname, p.mname, p.lname, p.street, p.city, p.state, " .
    "p.postal_code, p.phone_home, p.ss, p.genericname2, p.genericval2, " .
    "p.pubpid, p.DOB, CONCAT(u.lname, ', ', u.fname) AS referrer, " .
    "( SELECT SUM(b.fee) FROM billing AS b WHERE " .
    "b.pid = f.pid AND b.encounter = f.encounter AND " .
    "b.activity = 1 AND b.code_type != 'COPAY' ) AS charges, " .
    "( SELECT SUM(b.fee) FROM billing AS b WHERE " .
    "b.pid = f.pid AND b.encounter = f.encounter AND " .
    "b.activity = 1 AND b.code_type = 'COPAY' ) AS copays, " .
    "( SELECT SUM(s.fee) FROM drug_sales AS s WHERE " .
    "s.pid = f.pid AND s.encounter = f.encounter ) AS sales, " .
    "( SELECT SUM(a.pay_amount) FROM ar_activity AS a WHERE " .
    "a.pid = f.pid AND a.encounter = f.encounter AND a.deleted IS NULL ) AS payments, " .
    "( SELECT SUM(a.adj_amount) FROM ar_activity AS a WHERE " .
    "a.pid = f.pid AND a.encounter = f.encounter AND a.deleted IS NULL ) AS adjustments " .
    "FROM form_encounter AS f " .
    "JOIN patient_data AS p ON p.pid = f.pid " .
    "LEFT OUTER JOIN users AS u ON u.id = p.ref_providerID " .
    "WHERE " .
    "f.date >= '$form_from_date 00:00:00' AND f.date <= '$form_to_date 23:59:59' " .
    "ORDER BY f.pid, f.encounter";
  $eres = sqlStatement($query);

  while ($erow = sqlFetchArray($eres)) {
    $patient_id = $erow['pid'];
    $encounter_id = $erow['encounter'];
    $pt_balance = $erow['charges'] + $erow['sales'] + $erow['copays'] - $erow['payments'] - $erow['adjustments'];
    $pt_balance = 0 + sprintf("%.2f", $pt_balance); // yes this seems to be necessary
    $svcdate = substr($erow['date'], 0, 10);

    if ($pt_balance == 0) continue;

    $query = "INSERT INTO ar_activity ( " .
      "pid, encounter, code_type, code, modifier, payer_type, " .
      "post_user, post_time, post_date, session_id, memo, adj_amount " .
      ") VALUES ( " .
      "'$patient_id', " .
      "'$encounter_id', " .
      "'', " .                                  // code_type
      "'', " .                                  // code
      "'', " .                                  // modifier
      "'0', " .                                 // payer_type
      "'" . $_SESSION['authUserID'] . "', " .
      "NOW(), " .
      "'$svcdate', " .
      "'0', " .
      "'" . add_escape_custom($_POST['form_adjreason']) . "', " .
      "'$pt_balance' " .
      ")";

    // echo "$query<br />\n"; // debugging
    sqlStatement($query);

    echo xlt('Updated client') . " '" . text($erow['pubpid']) . "' " . xlt('visit') .
      " " . oeFormatShortDate($erow['date']) . "<br />\n";;

  } // end while
} // end if form_refresh
else {
?>

<form method='post' action='auto_writeoff.php' enctype='multipart/form-data' id='theform'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

<table>
 <tr>
  <td>
    <table class='text'>
      <tr>
        <td align='left'>
           <?php echo xlt('Visits From'); ?>:
          <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
          onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
          <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
          id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
          title='<?php xl('Click here to choose a date','e'); ?>'>
          &nbsp;
          <?php echo xlt('To'); ?>:
          <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
          onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
          <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
          id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
          title='<?php xl('Click here to choose a date','e'); ?>'>
          &nbsp;
          <?php echo xlt('Reason') . ': ' . generate_select_list('form_adjreason', 'adjreason', '', '', ''); ?>
        </td>
      </tr>
    </table>
  </td>
  <td align='left' valign='middle' height="100%">
    <table style='border-left:1px solid;' >
      <tr>
        <td>
          <div style='margin-left:15px'>
            <a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
            <span>
              <?php echo xlt('Submit'); ?>
            </span>
            </a>

            <?php if ($_POST['form_refresh']) { ?>
            <a href='#' class='css_button' onclick='window.print()'>
              <span>
                <?php echo xlt('Print'); ?>
              </span>
            </a>
            <?php } ?>
          </div>
        </td>
      </tr>
    </table>
  </td>
 </tr>
</table>
</div>
</form>

<p>This tool creates an adjustment entry for each visit in the specified date range that has a non-zero balance.</p>
<p>These adjustments are at the visit level (not item-specific.) Posting date is set to the visit date,
the memo is the selected adjustment reason, and the amount writes off the balance for the visit.</p>
<p>Use this tool carefully and be sure your site is backed up daily!</p>

<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>
<?php
} // end form not submitted
?>
</body>
</html>
