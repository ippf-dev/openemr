<?php
// Copyright (C) 2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This reports on changes made to previously reopened visits.

$fake_register_globals = false;
$sanitize_all_escapes  = true;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");

// For each sorting option, specify the ORDER BY argument.
$ORDERHASH = array(
  'edate'  => 'fe.date, pd.pubpid, l.user, f.name, l.date, l.comments, v.void_id, l.id',
  'pubpid' => 'pd.pubpid, fe.date, l.user, f.name, l.date, l.comments, v.void_id, l.id',
  'user'   => 'l.user, fe.date, pd.pubpid, f.name, l.date, l.comments, v.void_id, l.id',
  'fac'    => 'f.name, fe.date, pd.pubpid, l.user, l.date, l.comments, v.void_id, l.id',
  'chg'    => 'l.comments, fe.date, pd.pubpid, l.user, f.name, l.date, v.void_id, l.id',
  'cdate'  => 'l.date, fe.date, pd.pubpid, l.user, f.name, l.comments, v.void_id, l.id',
);

$form_use_edate  = empty($_POST['form_use_edate']) ? 0 : 1;
$form_from_date  = fixDate($_POST['form_from_date'], date('Y-01-01'));
$form_to_date    = fixDate($_POST['form_to_date']  , date('Y-m-d'));

// The selected facility ID, if any.
$form_facility = 0 + empty($_POST['form_facility']) ? 0 : $_POST['form_facility'];

$form_user = empty($_POST['form_user']) ? '' : $_POST['form_user'];
$form_patient_id = empty($_POST['form_patient_id']) ? '' : $_POST['form_patient_id'];

$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'edate';
$orderby = $ORDERHASH[$form_orderby];
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Destroyed Drugs','e'); ?></title>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>

<style  type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>

<script language="JavaScript">
 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 function dosort(orderby) {
  var f = document.forms[0];
  f.form_orderby.value = orderby;
  top.restoreSession();
  f.submit();
  return false;
 }
</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

<center>

<h2><?php echo xlt('Changes to Re-Opened Visits'); ?></h2>

<form name='theform' method='post' action='reopened_visits_report.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td colspan='2' align='center'>
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
  echo ">" . $frow['name'] . "</option>\n";
}
echo "   </select>\n";
?>

&nbsp;

<?php
// Build a drop-down list of users.
//
$query = "SELECT username, lname, fname FROM users WHERE " .
  "active = 1 AND username != '' ORDER BY lname, fname";
$ures = sqlStatement($query);
echo "   <select name='form_user'>\n";
echo "    <option value=''>-- " . xlt('All Users') . " --\n";
while ($urow = sqlFetchArray($ures)) {
  $username = $urow['username'];
  echo "    <option value='" . attr($username) . "'";
  if ($username == $_POST['form_user']) echo " selected";
  echo ">" . text($urow['lname'] . ", " . $urow['fname'] . " (" . $urow['username'] . ")") . "\n";
}
echo "   </select>\n";
?>

   &nbsp;<?php echo xlt('Patient'); ?>:
   <input type='text' name='form_patient_id' size='10' value='<?php echo $form_patient_id; ?>'
    title='<?php echo xla('Optional external ID here; % matches anything.'); ?>'>

  </td>
 </tr>

 <tr>
  <td align='left'>

   <select name='form_use_edate'>
    <option value='0'><?php echo xlt('Change Date'); ?></option>
    <option value='1'<?php if ($form_use_edate) echo ' selected' ?>><?php echo xlt('Visit Date'); ?></option>
   </select>

   &nbsp;<?php xl('From','e'); ?>:
   <input type='text' name='form_from_date' id='form_from_date'
    size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title=<?php xl('yyyy-mm-dd','e','\'','\''); ?>>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title=<?php xl('Click here to choose a date','e','\'','\''); ?>>

   &nbsp;<?php xl('To','e'); ?>:
   <input type='text' name='form_to_date' id='form_to_date'
    size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title=<?php xl('yyyy-mm-dd','e','\'','\''); ?>>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title=<?php xl('Click here to choose a date','e','\'','\''); ?>>

  </td>
  <td align='right'>

   <input type='submit' name='form_refresh' value=<?php xl('Refresh','e'); ?>>
   &nbsp;
   <input type='button' value='<?php xl('Print','e'); ?>' onclick='window.print()' />

  </td>
 </tr>

 <tr>
  <td height="1">
  </td>
 </tr>

</table>

<table border='0' cellpadding='1' cellspacing='2' width='98%'>
 <tr bgcolor="#dddddd">

  <td class='dehead'>
   <a href="#" onclick="return dosort('edate')"
   <?php if ($form_orderby == "edate") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Visit Date'); ?> </a>
  </td>

  <td class='dehead'>
   <a href="#" onclick="return dosort('pubpid')"
   <?php if ($form_orderby == "pubpid") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Client ID'); ?> </a>
  </td>

<?php if (!$form_facility) { ?>
  <td class='dehead'>
   <a href="#" onclick="return dosort('fac')"
   <?php if ($form_orderby == "fac") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Facility'); ?> </a>
  </td>
<?php } ?>

  <td class='dehead'>
   <a href="#" onclick="return dosort('user')"
   <?php if ($form_orderby == "user") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('User'); ?> </a>
  </td>

  <td class='dehead'>
   <a href="#" onclick="return dosort('cdate')"
   <?php if ($form_orderby == "cdate") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Change Date'); ?> </a>
  </td>

  <td class='dehead'>
   <a href="#" onclick="return dosort('chg')"
   <?php if ($form_orderby == "chg") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Change'); ?> </a>
  </td>

 </tr>
<?php
if (!empty($_POST['form_orderby'])) {
  $where = "l.event = 'fee-sheet' AND ";

  if ($form_use_edate) {
    $where .= "fe.date >= ? AND fe.date <= ?";
  }
  else {
    $where .= "l.date >= ? AND l.date <= ?";
  }
  $sqlargs = array("$form_from_date 00:00:00", "$form_to_date 23:59:59");

  if ($form_facility) {
    $where .= " AND fe.facility_id IS NOT NULL AND fe.facility_id = ?";
    $sqlargs[] = $form_facility;
  }

  if ($form_user) {
    $where .= " AND l.user IS NOT NULL AND l.user = ?";
    $sqlargs[] = $form_user;
  }

  if ($form_patient_id) {
    $where .= " AND pd.pubpid IS NOT NULL AND pd.pubpid LIKE ?";
    $sqlargs[] = $form_patient_id;
  }

  $query = "SELECT l.id, l.date, l.patient_id, l.user_notes, l.user, l.comments, " .
    "v.void_id, fe.date AS encdate, f.name AS facname, pd.pubpid " .
    "FROM log AS l " .
    "JOIN voids AS v ON v.patient_id = l.patient_id AND v.encounter_id = l.user_notes AND v.date_voided < l.date " .
    "JOIN form_encounter AS fe ON fe.pid = l.patient_id AND fe.encounter = l.user_notes " .
    "JOIN patient_data AS pd ON pd.pid = l.patient_id " .
    "LEFT JOIN facility AS f ON f.id = fe.facility_id " .
    "WHERE $where ORDER BY $orderby";

  // echo "<!-- $query -->\n"; // debugging
  $res = sqlStatement($query, $sqlargs);

  $last_log_id = 0;
  $last_void_id = 0;
  $encount = 0;
  while ($row = sqlFetchArray($res)) {
    // Skipping duplicates due to multiple voids for the same encounter.
    if ($last_log_id == $row['id']) continue;
    $last_log_id = $row['id'];
    // Alternate background colors for each unique visit.
    if ($last_void_id != $row['void_id']) {
      $last_void_id = $row['void_id'];
      $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
    }
?>
 <tr bgcolor="<?php echo $bgcolor; ?>">

  <td class='detail'>
   <?php echo oeFormatShortDate(substr($row['encdate'], 0, 10)) ?>
  </td>

  <td class='detail'>
   <?php echo text($row['pubpid']); ?>
  </td>

<?php if (!$form_facility) { ?>
  <td class='detail'>
   <?php echo text($row['facname']); ?>
  </td>
<?php } ?>

  <td class='detail'>
   <?php echo text($row['user']); ?>
  </td>

  <td class='detail'>
   <?php echo oeFormatShortDate(substr($row['date'], 0, 10)) . substr($row['date'], 10); ?>
  </td>

  <td class='detail'>
   <?php echo text($row['comments']); ?>
  </td>

 </tr>
<?php
   $last_drug_id = $row['drug_id'];
  } // end while
 } // end if
?>

</table>

<input type="hidden" name="form_orderby" value="<?php echo $form_orderby ?>" />

</form>
</center>
<script language='JavaScript'>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>
</body>
</html>
