<?php
// Copyright (C) 2015-2016 Rod Roark <rod@sunsetsystems.com>
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
require_once("$srcdir/options.inc.php");
require_once($GLOBALS['fileroot'] . '/custom/code_types.inc.php');

if (!acl_check('acct', 'rep_a')) die(xl("Unauthorized access."));

// For each sorting option, specify the ORDER BY argument.
$ORDERHASH = array(
  'edate'  => 'fe.date, pd.pubpid, f.name, v.void_id, l.id',
  'pubpid' => 'pd.pubpid, fe.date, f.name, v.void_id, l.id',
  'invno'  => 'v.other_info, fe.date, pd.pubpid, f.name, v.void_id, l.id',
  'user'   => 'l.user, fe.date, pd.pubpid, f.name, v.void_id, l.id',
  'fac'    => 'f.name, fe.date, pd.pubpid, v.void_id, l.id',
  'chg'    => 'l.comments, fe.date, pd.pubpid, f.name, v.void_id, l.id',
  'pdate'  => 'v.date_original, fe.date, pd.pubpid, f.name, v.void_id, l.id',
  'cdate'  => 'l.date, fe.date, pd.pubpid, f.name, v.void_id, l.id',
  'reason' => 'lo.title, fe.date, pd.pubpid, f.name, v.void_id, l.id',
);

$form_date_type  = empty($_POST['form_date_type']) ? 0 : $_POST['form_date_type'];
$form_from_date  = fixDate($_POST['form_from_date'], date('Y-01-01'));
$form_to_date    = fixDate($_POST['form_to_date']  , date('Y-m-d'));

// The selected facility ID, if any.
$form_facility = 0 + empty($_POST['form_facility']) ? 0 : $_POST['form_facility'];

$form_user = empty($_POST['form_user']) ? '' : $_POST['form_user'];
$form_patient_id = empty($_POST['form_patient_id']) ? '' : $_POST['form_patient_id'];

$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'edate';
$orderby = $ORDERHASH[$form_orderby];

$form_reason  = empty($_POST['form_reason']) ? '' : $_POST['form_reason'];

// Output a row.
function writeRow($row, $changedate, $comments, $iname='', $pricelevel='', $fee='', $units='', $newvalue='', $codedesc='') {
  global $bgcolor, $form_facility;

  $patient_id = $row['patient_id'];
  $encounter_id = $row['encounter_id'];
  $invnumber = $row['other_info'] ? $row['other_info'] : "$patient_id.$encounter_id";

  if ($iname) {
    if (!$units) $units = 1;
    $fee = oeFormatMoney($fee / $units);
  }

  if ($_POST['form_csvexport']) {
    echo '"'  . addslashes(oeFormatShortDate(substr($row['encdate'], 0, 10))) . '"';
    echo ',"' . addslashes($row['pubpid']                                   ) . '"';
    echo ',"' . addslashes($invnumber                                       ) . '"';
    echo ',"' . addslashes($row['facname']                                  ) . '"';
    echo ',"' . addslashes($row['user']                                     ) . '"';
    echo ',"' . addslashes(oeFormatShortDate(substr($row['date_original'], 0, 10)) . substr($row['date_original'], 10)) . '"';
    echo ',"' . addslashes(oeFormatShortDate(substr($changedate, 0, 10)) . substr($changedate, 10)) . '"';
    echo ',"' . addslashes(empty($row['title']) ? '' : $row['title']        ) . '"';
    echo ',"' . addslashes($row['notes']                                    ) . '"';
    echo ',"' . addslashes($comments                                        ) . '"';
    echo ',"' . addslashes($iname                                           ) . '"';
    echo ',"' . addslashes($codedesc                                        ) . '"';
    echo ',"' . addslashes($pricelevel                                      ) . '"';
    echo ',"' . addslashes($fee                                             ) . '"';
    echo ',"' . addslashes($units                                           ) . '"';
    echo ',"' . addslashes($newvalue                                        ) . '"' . "\n";
  }
  else {
?>
 <tr bgcolor="<?php echo $bgcolor; ?>">

  <td class='detail'>
   <?php echo oeFormatShortDate(substr($row['encdate'], 0, 10)) ?>
  </td>

  <td class='detail'>
   <?php echo text($row['pubpid']); ?>
  </td>

  <td class='delink' onclick='doinvopen(<?php echo "$patient_id,$encounter_id"; ?>)'>
   <?php echo text($invnumber); ?>
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
   <?php echo oeFormatShortDate(substr($row['date_original'], 0, 10)) . substr($row['date_original'], 10); ?>
  </td>

  <td class='detail'>
   <?php echo oeFormatShortDate(substr($changedate, 0, 10)) . substr($changedate, 10); ?>
  </td>

  <td class='detail'>
   <?php echo text(empty($row['title']) ? '' : $row['title']); ?>
  </td>

  <td class='detail'>
   <?php echo text($row['notes']); ?>
  </td>

  <td class='detail'>
   <?php echo text($comments); ?>
  </td>

  <td class='detail'>
   <?php echo text($iname); ?>
  </td>

  <td class='detail' title='<?php echo attr($codedesc); ?>'>
   <div class='truncate'>
    <?php echo text($codedesc); ?>
   </div>
  </td>

  <td class='detail'>
   <?php echo text($pricelevel); ?>
  </td>

  <td class='detail' align='right'>
   <?php echo text($fee); ?>
  </td>

  <td class='detail' align='right'>
   <?php echo text($units); ?>
  </td>

  <td class='detail' align='right'>
   <?php echo text($newvalue); ?>
  </td>

 </tr>
<?php
  } // end not export
}

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=reopened_visits_report.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";

  // CSV headers.
  echo '"' . xl('Visit Date'             ) . '",';
  echo '"' . xl('Client ID'              ) . '",';
  echo '"' . xl('Invoice #'              ) . '",';
  echo '"' . xl('Facility'               ) . '",';
  echo '"' . xl('User'                   ) . '",';
  echo '"' . xl('Pay Date'               ) . '",';
  echo '"' . xl('Change Date'            ) . '",';
  echo '"' . xl('Void Reason'            ) . '",';
  echo '"' . xl('Void Notes'             ) . '",';
  echo '"' . xl('Change'                 ) . '",';
  echo '"' . xl('Code'                   ) . '",';
  echo '"' . xl('Description'            ) . '",';
  echo '"' . xl('Price Level'            ) . '",';
  echo '"' . xl('Price'                  ) . '",';
  echo '"' . xl('Units'                  ) . '",';
  echo '"' . xl('New Value'              ) . '"' . "\n";

} // end export
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Destroyed Drugs','e'); ?></title>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style  type="text/css">@import url(../../library/dynarch_calendar.css);</style>

<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
 .delink { color:#0000cc; font-family:sans-serif; font-size:10pt; font-weight:normal; cursor:pointer }
 .truncate {
  width: 8em;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
 }
</style>

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

 function doinvopen(ptid,encid) {
  dlgopen('../patient_file/pos_checkout.php?ptid=' + ptid + '&enc=' + encid, '_blank', 750, 550);
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

// Build a drop-down list of void reasons.
//
echo "&nbsp;";
echo generate_select_list('form_reason', 'void_reasons', $form_reason, '', '-- ' . xl('All Reasons') . ' --');
?>

   &nbsp;<?php echo xlt('Patient'); ?>:
   <input type='text' name='form_patient_id' size='10' value='<?php echo $form_patient_id; ?>'
    title='<?php echo xla('Optional external ID here; % matches anything.'); ?>'>

  </td>
 </tr>

 <tr>
  <td align='left'>

   <select name='form_date_type'>
    <option value='0'><?php echo xlt('Change Date'); ?></option>
    <option value='1'<?php if ($form_date_type == 1) echo ' selected' ?>><?php echo xlt('Visit Date'); ?></option>
    <option value='2'<?php if ($form_date_type == 2) echo ' selected' ?>><?php echo xlt('Reopen/Void Date'); ?></option>
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
   <input type='submit' name='form_csvexport' value='<?php echo xla('Export to CSV') ?>' />
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

  <td class='dehead'>
   <a href="#" onclick="return dosort('invno')"
   <?php if ($form_orderby == "invno") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Invoice #'); ?> </a>
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
   <a href="#" onclick="return dosort('pdate')"
   <?php if ($form_orderby == "pdate") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Pay Date'); ?> </a>
  </td>

  <td class='dehead'>
   <a href="#" onclick="return dosort('cdate')"
   <?php if ($form_orderby == "cdate") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Change Date'); ?> </a>
  </td>

  <td class='dehead'>
   <a href="#" onclick="return dosort('reason')"
   <?php if ($form_orderby == "reason") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Void Reason'); ?> </a>
  </td>

  <td class='dehead'>
   <?php echo xlt('Void Notes'); ?>
  </td>

  <td class='dehead'>
   <a href="#" onclick="return dosort('chg')"
   <?php if ($form_orderby == "chg") echo " style='color:#00cc00'"; ?>>
   <?php echo xlt('Change'); ?> </a>
  </td>

  <td class='dehead'>
   <?php echo xlt('Code'); ?>
  </td>

  <td class='dehead'>
   <?php echo xlt('Description'); ?>
  </td>

  <td class='dehead'>
   <?php echo xlt('Price Level'); ?>
  </td>

  <td class='dehead'>
   <?php echo xlt('Price'); ?>
  </td>

  <td class='dehead'>
   <?php echo xlt('Units'); ?>
  </td>

  <td class='dehead'>
   <?php echo xlt('New Value'); ?>
  </td>

 </tr>

<?php
} // end not export

if (!empty($_POST['form_orderby'])) {
  if ($form_date_type == 1) {
    $where = "fe.date >= ? AND fe.date <= ?";
  }
  else if ($form_date_type == 2) {
    $where = "v.date_voided >= ? AND v.date_voided <= ?";
  }
  else {
    $where = "l.date IS NOT NULL AND l.date >= ? AND l.date <= ?";
  }
  $sqlargs = array("$form_from_date 00:00:00", "$form_to_date 23:59:59");

  if ($form_facility) {
    $where .= " AND fe.facility_id IS NOT NULL AND fe.facility_id = ?";
    $sqlargs[] = $form_facility;
  }

  if ($form_user) {
    $where .= " AND (v.user_id = ? OR l.user IS NOT NULL AND l.user = ?)";
    $sqlargs[] = $form_user;
    $sqlargs[] = $form_user;
  }

  if ($form_patient_id) {
    $where .= " AND pd.pubpid IS NOT NULL AND pd.pubpid LIKE ?";
    $sqlargs[] = $form_patient_id;
  }

  if ($form_reason) {
    $where .= " AND v.reason LIKE ?";
    $sqlargs[] = $form_reason;
  }

  $query = "SELECT " .
    "v.void_id, v.date_voided, v.date_original, v.other_info, v.reason, v.notes, " .
    "v.patient_id, v.encounter_id, " .
    "l.id, l.date, l.patient_id, l.user_notes, l.user, l.comments, " .
    "fe.date AS encdate, f.name AS facname, pd.pubpid, lo.title " .
    "FROM voids AS v " .
    "JOIN form_encounter AS fe ON fe.pid = v.patient_id AND fe.encounter = v.encounter_id " .
    "JOIN patient_data AS pd ON pd.pid = v.patient_id " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'void_reasons' AND " .
    "lo.option_id = v.reason AND lo.activity = 1 " .
    "LEFT JOIN facility AS f ON f.id = fe.facility_id " .
    "LEFT JOIN log AS l ON l.event = 'fee-sheet' AND l.patient_id = v.patient_id AND " .
    "SUBSTRING_INDEX(l.user_notes, '|', 1) = v.encounter_id AND " .
    "l.date > v.date_voided " .
    "WHERE $where ORDER BY $orderby";

  // echo "<!-- $query -->\n"; // debugging
  $res = sqlStatement($query, $sqlargs);
  $arr_logid = array();
  $arr_voidid = array();
  $last_void_id = 0;
  $encount = 0;

  while ($row = sqlFetchArray($res)) {

    // Alternate background colors for each new void.
    if ($last_void_id != $row['void_id']) {
      $last_void_id = $row['void_id'];
      // If first appearance of this void, generate a row for it.
      if (empty($arr_voidid[$row['void_id']])) {
        $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
        $arr_voidid[$row['void_id']] = $row['void_id'];
        writeRow($row, $row['date_voided'], xl('Voided'));
      }
      else if (!empty($row['id'])) {
        $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
      }
    }

    // Skip if no logged change events for this void.
    if (empty($row['id'])) continue;

    // Skipping duplicate change events due to multiple voids for the same encounter.
    if (!empty($arr_logid[$row['id']])) continue;
    $arr_logid[$row['id']] = $row['id'];

    $iname = '';
    $codedesc = '';
    $item = explode('|', $row['user_notes']);
    if (empty($item[2])) {
      $item = array($item[0], '', '', '', '', '', '', '');
    }
    else {
      $iname = $item[2] . ':' . $item[3];
      if ($item[4] !== '') $iname .= ':' . $item[4];

      if ($item[2] == 'PROD') {
        $tmp = sqlQuery("SELECT name AS code_text FROM drugs WHERE drug_id = ?",
          array($item[3]));
      }
      else {
        $tmp = sqlQuery("SELECT code_text FROM codes WHERE code_type = ? AND code = ? " .
          "ORDER BY id LIMIT 1",
          array($code_types[$item[2]]['id'], $item[3]));
      }
      $codedesc = empty($tmp['code_text']) ? '' : $tmp['code_text'];
    }

    writeRow($row, $row['date'], $row['comments'], $iname, $item[5], $item[6], $item[7], $item[1], $codedesc);

  } // end while
} // end if
if (!$_POST['form_csvexport']) {
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
<?php
} // end not export

// PHP end tag omitted.
