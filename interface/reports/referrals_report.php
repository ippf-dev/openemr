<?php
// Copyright (C) 2008-2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This report lists referrals for a given date range.

$fake_register_globals = false;
$sanitize_all_escapes  = true;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/options.inc.php");
include_once("../../custom/code_types.inc.php");

// For each sorting option, specify the ORDER BY argument.
$ORDERHASH = array(
  'refby'   => 'referer_name, t.refer_date, t.id',
  'refto'   => 'ut.organization, referto_name, t.refer_date, t.id',
  'service' => 't.refer_related_code, t.refer_date, t.id',
  'refdate' => 't.refer_date, t.id',
  'expdate' => 't.refer_reply_date, t.id',
  'repdate' => 't.reply_date, t.id',
  'ptname'  => 'patient_name, t.id',
  'ptid'    => 'p.pubpid, t.id',
);

$from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));
$form_facility = isset($_POST['form_facility']) ? $_POST['form_facility'] : '';
$form_referral_type = isset($_POST['form_referral_type']) ? $_POST['form_referral_type'] : '';

$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'refto';
$orderby = $ORDERHASH[$form_orderby];

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=referrals_report.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";

  // CSV headers.
  echo '"' . xl('Refer By'               ) . '",';
  echo '"' . xl('Refer To (Org / Person)') . '",';
  echo '"' . xl('Refer Date'             ) . '",';
  echo '"' . xl('Expected Date'          ) . '",';
  echo '"' . xl('Reply Date'             ) . '",';
  echo '"' . xl('Patient'                ) . '",';
  echo '"' . xl('ID'                     ) . '",';
  echo '"' . xl('Reason'                 ) . '",';
  echo '"' . xl('Service'                ) . '"' . "\n";

} // end export
else {
?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo xlt('Referrals'); ?></title>

<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>

<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 // The OnClick handler for referral display.
 function show_referral(transid) {
  dlgopen('../patient_file/transaction/print_referral.php?transid=' + transid,
   '_blank', 550, 400);
  return false;
 }

</script>

<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>

<style type="text/css">

 body       { font-family:sans-serif; font-size:10pt; font-weight:normal }
 .dehead    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
 .delink    { color:#0000cc; font-family:sans-serif; font-size:10pt; font-weight:normal; cursor:pointer }

 #referreport_results table, #referreport_results td {
  border: 1px solid #aaaaaa;
  border-collapse: collapse;
 }
 #referreport_results td {
  padding: 1pt 4pt 1pt 4pt;
 }

</style>

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

<body class="body_top">

<center>

<h2><?php xl('Referrals','e'); ?></h2>

<div id="referreport_parameters">
<form name='theform' method='post' action='referrals_report.php'>
<table border='0' cellpadding='3'>
 <tr>
  <td>
<?php
 // Build a drop-down list of referral types.
 //
 echo generate_select_list('form_referral_type', 'reftype',
  $form_referral_type, 'Referral Type', 'All Types');
 echo '&nbsp;';
 // Build a drop-down list of facilities.
 //
 $query = "SELECT id, name FROM facility ORDER BY name";
 $fres = sqlStatement($query);
 echo "   <select name='form_facility'>\n";
 echo "    <option value=''>-- " . xlt('All Facilities') . " --\n";
 while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  echo "    <option value='$facid'";
  if ($facid == $form_facility) echo " selected";
  echo ">" . text($frow['name']) . "\n";
 }
 echo "    <option value='0'";
 if ($form_facility === '0') echo " selected";
 echo ">-- " . xlt('Unspecified') . " --\n";
 echo "   </select>\n";
?>
   &nbsp;<?php echo xlt('From'); ?>:
   <input type='text' size='10' name='form_from_date' id='form_from_date'
    value='<?php echo $from_date; ?>'
    title='<?php echo xla('yyyy-mm-dd'); ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php echo xla('Click here to choose a date'); ?>' />
   &nbsp;<?php echo xlt('To'); ?>:
   <input type='text' size='10' name='form_to_date' id='form_to_date'
    value='<?php echo $to_date; ?>'
    title='<?php echo xla('yyyy-mm-dd'); ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php echo xla('Click here to choose a date'); ?>' />
   &nbsp;
   <input type='submit' name='form_refresh' value='<?php echo xla('Refresh'); ?>' />
   &nbsp;
   <input type='submit' name='form_csvexport' value='<?php echo xla('Export to CSV') ?>' />
   &nbsp;
   <input type='button' value='<?php echo xla('Print'); ?>' onclick='window.print()' />
  </td>
 </tr>
</table>
</div> <!-- end of parameters -->

<div id="referreport_results">
<table width='98%'>
 <thead>
 <tr bgcolor="#cccccc">
  <td class='dehead'>
   <a href="#" onclick="return dosort('refby')"
   <?php if ($form_orderby == "refby") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Refer By'); ?> </a>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('refto')"
   <?php if ($form_orderby == "refto") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Refer To (Org / Person)'); ?> </a>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('refdate')"
   <?php if ($form_orderby == "refdate") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Refer Date'); ?> </a>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('expdate')"
   <?php if ($form_orderby == "expdate") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Expected Date'); ?> </a>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('repdate')"
   <?php if ($form_orderby == "repdate") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Reply Date'); ?> </a>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('ptname')"
   <?php if ($form_orderby == "ptname") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Patient'); ?> </a>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('ptid')"
   <?php if ($form_orderby == "ptid") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('ID'); ?> </a>
  </td>
  <td class='dehead'>
   <?php echo xlt('Reason'); ?>
  </td>
  <td class='dehead'>
   <a href="#" onclick="return dosort('service')"
   <?php if ($form_orderby == "service") echo " style=\"color:#00cc00\""; ?>>
   <?php echo xlt('Service'); ?> </a>
  </td>
 </thead>
 <tbody>
<?php
} // end not export

if ($_POST['form_orderby']) {
  $query = "SELECT " .
    "t.id, t.refer_date, t.refer_reply_date, t.reply_date, t.body, t.pid, " .
    "t.refer_external, t.refer_related_code, " .
    "ut.organization, uf.facility_id, p.pubpid, p.home_facility, " .
    "CONCAT(uf.fname,' ', uf.lname) AS referer_name, " .
    "CONCAT(ut.fname,' ', ut.lname) AS referto_name, " .
    "CONCAT(p.fname,' ', p.lname) AS patient_name " .
    "FROM transactions AS t " .
    "LEFT OUTER JOIN patient_data AS p ON p.pid = t.pid " .
    "LEFT OUTER JOIN users AS ut ON ut.id = t.refer_to " .
    "LEFT OUTER JOIN users AS uf ON uf.id = t.refer_from " .
    "WHERE t.title = 'Referral' AND " .
    "t.refer_date >= ? AND t.refer_date <= ? ";
  $sqlarr = array($from_date, $to_date);
  if ($form_referral_type) {
    $query .= "AND t.refer_external = ? ";
    $sqlarr[] = $form_referral_type;
  }
  $query .= "ORDER BY $orderby";

  // echo "<!-- $query -->\n"; // debugging
  $res = sqlStatement($query, $sqlarr);

  $encount = 0;
  $svccount = 0;

  while ($row = sqlFetchArray($res)) {
    // If a facility is specified, ignore rows that do not match.
    if ($form_facility !== '') {
      $facility_id = $row['home_facility'];
      if ($row['refer_external'] <= 3) {
        // Outbound referrals, look for last visit on or before referral date.
        $tmp = sqlQuery("SELECT facility_id FROM form_encounter WHERE " .
          "pid = ? AND date <= ? ORDER BY date DESC LIMIT 1",
          array($row['pid'], $row['refer_date']));
      }
      else {
        // Inbound referrals, look for first visit on or after referral date.
        $tmp = sqlQuery("SELECT facility_id FROM form_encounter WHERE " .
          "pid = ? AND date >= ? ORDER BY date ASC LIMIT 1",
          array($row['pid'], $row['refer_date']));
      }
      if (!empty($tmp['facility_id'])) $facility_id = $tmp['facility_id'];
      if ($form_facility) {
        if ($facility_id != $form_facility) continue;
      }
      else {
        // "0" means only cases where facility could not be determined.
        if (!$facility_id) continue;
      }
    }

    // Get referred services.
    $svcstring = '';
    $relcodes = explode(';', $row['refer_related_code']);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      ++$svccount;
      list($codetype, $code) = explode(':', $codestring);
      $rrow = sqlQuery("SELECT code_text FROM codes WHERE " .
        "code_type = ? AND code = ? " .
        "ORDER BY active DESC, id ASC LIMIT 1",
        array($code_types[$codetype]['id'], $code));
      $code_text = empty($rrow['code_text']) ? '' : $rrow['code_text'];
      if ($_POST['form_csvexport']) {
        if ($svcstring) $svcstring .= '; ';
        $svcstring .= addslashes("$code: $code_text");
      }
      else {
        if ($svcstring) $svcstring .= '<br />';
        $svcstring .= text("$code: $code_text");
      }
    }

    if ($_POST['form_csvexport']) {
      echo '"'  . addslashes($row['referer_name']                               )   . '"';
      echo ',"' . addslashes($row['organization'] . ' / ' . $row['referto_name']) . '"';
      echo ',"' . addslashes($row['refer_date']                                 ) . '"';
      echo ',"' . addslashes($row['refer_reply_date']                           ) . '"';
      echo ',"' . addslashes($row['reply_date']                                 ) . '"';
      echo ',"' . addslashes($row['patient_name']                               ) . '"';
      echo ',"' . addslashes($row['pubpid']                                     ) . '"';
      echo ',"' . addslashes($row['body']                                       ) . '"';
      echo ',"' . $svcstring                                                      . '"' . "\n";
    }
    else {
      // Count referrals and alternate row color.
      $bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
?>
 <tr bgcolor='<?php echo $bgcolor; ?>'>
  <td class='detail'>
   <?php echo text($row['referer_name']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['organization'] . ' / ' . $row['referto_name']); ?>
  </td>
  <td class='detail'>
   <a href='#' onclick="return show_referral(<?php echo $row['id']; ?>)">
   <?php echo text(oeFormatShortDate($row['refer_date'])); ?>&nbsp;
   </a>
  </td>
  <td class='detail'>
   <?php echo text(oeFormatShortDate($row['refer_reply_date'])); ?>
  </td>
  <td class='detail'>
   <?php echo text(oeFormatShortDate($row['reply_date'])); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['patient_name']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['pubpid']); ?>
  </td>
  <td class='detail'>
   <?php echo text($row['body']); ?>
  </td>
  <td class='detail'>
   <?php echo $svcstring; ?>
  </td>
 </tr>
<?php
    } // end not export
  }
}
if (!$_POST['form_csvexport']) {
  if ($_POST['form_orderby']) {
    echo " <tr bgcolor='#cccccc'>\n";
    echo "  <td class='dehead' colspan='9'>" . xlt('Total Referrals') . ": $encount" .
      "&nbsp;&nbsp;" . xlt('Services') . ": $svccount</td>\n";
    echo " </tr>\n";
  }
?>
</tbody>
</table>
</div> <!-- end of results -->

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
