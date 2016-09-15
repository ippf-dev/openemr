<?php
// Copyright (C) 2013, 2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is a report of past encounters within shift.

require_once("../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");

$alertmsg = ''; // not used yet but maybe later

// For each sorting option, specify the ORDER BY argument.
//
$ORDERHASH = array(
  'shift'   => 'shiftname, lower(u.lname), lower(u.fname), fe.date',
  'doctor'  => 'lower(u.lname), lower(u.fname), shiftname, fe.date',
  'patient' => 'lower(p.lname), lower(p.fname), fe.date',
  'pubpid'  => 'lower(p.pubpid), fe.date',
  'time'    => 'fe.date, shiftname, lower(u.lname), lower(u.fname)',
);

$oldshiftname = '';

function show_doc_total($shiftname, $docname, $encounters) {
  global $oldshiftname;
  if ($docname) {
    if ($shiftname == $oldshiftname) {
      $shiftname = '';
    }
    else {
      $oldshiftname = $shiftname;
    }
    echo " <tr>\n";
    echo "  <td class='detail'>" . ($shiftname ? htmlspecialchars($shiftname) : '&nbsp;') . "</td>\n";
    echo "  <td class='detail'>" . htmlspecialchars($docname) . "</td>\n";
    echo "  <td class='detail' align='right'>$encounters</td>\n";
    echo " </tr>\n";
  }
}

function show_shift_total($lastshiftname, $shift_encounters) {
  global $form_details;
  if ($lastshiftname) {
    echo " <tr>\n";
    echo "  <td class='bold' align='right' colspan='" . ($form_details ? 8 : 2) . "'>" .
         htmlspecialchars($lastshiftname) . " " . xl('Sub-Total') . "</td>\n";
    echo "  <td class='bold' align='right'>$shift_encounters</td>\n";
    echo " </tr>\n";
  }
}

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));
$form_provider  = $_POST['form_provider'];
$form_facility  = $_POST['form_facility'];
$form_details   = $_POST['form_details'] ? true : false;

$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'shift';
$orderby = $ORDERHASH[$form_orderby];

// Get the info.
//
$query = "SELECT " .
  "fe.encounter, fe.date, fe.reason, fe.provider_id, " .
  "f.formdir, f.form_name, " .
  "p.fname, p.mname, p.lname, p.pid, p.pubpid, " .
  "u.lname AS ulname, u.fname AS ufname, u.mname AS umname, " .
  "lo.title AS shiftname " .
  "FROM ( form_encounter AS fe, forms AS f ) " .
  "LEFT OUTER JOIN patient_data AS p ON p.pid = fe.pid " .
  "LEFT OUTER JOIN users AS u ON u.id = fe.provider_id " .
  "LEFT OUTER JOIN list_options AS lo ON lo.list_id = 'shift' AND lo.option_id = fe.shift AND lo.activity = 1 " .
  "WHERE f.encounter = fe.encounter AND f.formdir = 'newpatient' ";
if ($form_to_date) {
  $query .= "AND fe.date >= '$form_from_date 00:00:00' AND fe.date <= '$form_to_date 23:59:59' ";
} else {
  $query .= "AND fe.date >= '$form_from_date 00:00:00' AND fe.date <= '$form_from_date 23:59:59' ";
}
if ($form_provider) {
  $query .= "AND fe.provider_id = '$form_provider' ";
}
if ($form_facility) {
  $query .= "AND fe.facility_id = '$form_facility' ";
}
$query .= "ORDER BY $orderby";

$res = sqlStatement($query);
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Shifts Report','e'); ?></title>

<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>

<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<style type="text/css">

.provname  {font-size:10pt; font-weight:bold;}
.billcodes {font-size:8pt;}

/* specifically include & exclude from printing */
@media print {
    #encreport_parameters {
        visibility: hidden;
        display: none;
    }
    #encreport_parameters_daterange {
        visibility: visible;
        display: inline;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #encreport_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}

#encreport_parameters {
    width: 100%;
    background-color: #ddf;
}
#encreport_parameters table {
    border: none;
    border-collapse: collapse;
}
#encreport_parameters table td {
    padding: 3px;
}
#encreport_results {
    width: 100%;
    margin-top: 10px;
}
#encreport_results table {
   border: 1px solid black;
   width: 98%;
   border-collapse: collapse;
}
#encreport_results table thead {
    display: table-header-group;
    background-color: #ddd;
}
#encreport_results table th {
    border-bottom: 1px solid black;
}
#encreport_results table td {
    padding: 1px;
    margin: 2px;
    border-bottom: 1px solid #eee;
}
</style>

<script type="text/javascript" src="../../library/textformat.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/topdialog.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js?v=<?php echo $v_js_includes; ?>"></script>

<script LANGUAGE="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 function dosort(orderby) {
  var f = document.forms[0];
  f.form_orderby.value = orderby;
  f.submit();
  return false;
 }

 function refreshme() {
  document.forms[0].submit();
 }

$(document).ready(function() {
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
});

</script>

</head>

<body class="body_top">

<center>

<h2><?php xl('Shifts Report','e'); ?></h2>

<div id="encreport_parameters_daterange">
<?php echo date("d F Y", strtotime($form_from_date)) ." &nbsp; to &nbsp; ". date("d F Y", strtotime($form_to_date)); ?>
</div>

<div id="encreport_parameters">
<form method='post' name='theform' action='shifts_report.php'>
<table>

 <tr>
  <td>

   <?php xl('Facility','e'); ?>:
<?php
 // Build a drop-down list of facilities.
 //
 $query = "SELECT id, name FROM facility ORDER BY name";
 $fres = sqlStatement($query);
 echo "   <select name='form_facility'>\n";
 echo "    <option value=''>-- " . xl('All') . " --\n";
 while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  echo "    <option value='$facid'";
  if ($facid == $_POST['form_facility']) echo " selected";
  echo ">" . $frow['name'] . "\n";
 }
 echo "   </select>\n";
?>

   <?php xl('Provider','e'); ?>:
<?php
 // Build a drop-down list of providers.
 //
 $query = "SELECT id, lname, fname FROM users WHERE " .
  "active = 1 AND authorized = 1 ORDER BY lname, fname";
 $ures = sqlStatement($query);
 echo "   <select name='form_provider'>\n";
 echo "    <option value=''>-- " . xl('All') . " --\n";
 while ($urow = sqlFetchArray($ures)) {
  $provid = $urow['id'];
  echo "    <option value='$provid'";
  if ($provid == $_POST['form_provider']) echo " selected";
  echo ">" . $urow['lname'] . ", " . $urow['fname'] . "\n";
 }
 echo "   </select>\n";
?>

  </td>
 </tr>
 <tr>
  <td>

   <?php  xl('From','e'); ?>:
   <input type='text' name='form_from_date' id='form_from_date' size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='Start date yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>

   &nbsp;<?php  xl('To','e'); ?>:
   <input type='text' name='form_to_date' id='form_to_date' size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='End date yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>

   &nbsp;
   <input type='checkbox' name='form_details'<?php  if ($form_details) echo ' checked'; ?>>
   <?php  xl('Details','e'); ?>

   &nbsp;
   <input type='submit' name='form_refresh' value='<?php  xl('Refresh','e'); ?>'>
   &nbsp;
   <input type='button' value='<?php xl('Print','e'); ?>' onclick='window.print()' />

  </td>
 </tr>

 <tr>
  <td height="1">
  </td>
 </tr>

</table>
</div> <!-- end encreport_parameters -->

<div id="encreport_results">
<table id='mymaintable'>

 <thead>
 <tr>
<?php if ($form_details) { ?>
  <th>
   <a href="nojs.php" onclick="return dosort('shift')"
   <?php if ($form_orderby == "shift") echo " style=\"color:#00cc00\"" ?>><?php  xl('Shift','e'); ?> </a>
  </th>
  <th>
   <a href="nojs.php" onclick="return dosort('doctor')"
   <?php if ($form_orderby == "doctor") echo " style=\"color:#00cc00\"" ?>><?php  xl('Provider','e'); ?> </a>
  </th>
  <th>
   <a href="nojs.php" onclick="return dosort('time')"
   <?php if ($form_orderby == "time") echo " style=\"color:#00cc00\"" ?>><?php  xl('Date','e'); ?></a>
  </th>
  <th>
   <a href="nojs.php" onclick="return dosort('patient')"
   <?php if ($form_orderby == "patient") echo " style=\"color:#00cc00\"" ?>><?php  xl('Patient','e'); ?></a>
  </th>
  <th>
   <a href="nojs.php" onclick="return dosort('pubpid')"
   <?php if ($form_orderby == "pubpid") echo " style=\"color:#00cc00\"" ?>><?php  xl('ID','e'); ?></a>
  </th>
  <th>
   <?php  xl('Status','e'); ?>
  </th>
  <th>
   <?php  xl('Encounter','e'); ?>
  </th>
  <th>
   <?php  xl('Form','e'); ?>
  </th>
  <th>
   <?php  xl('Coding','e'); ?>
  </th>
<?php } else { ?>
  <th align='left'><?php  xl('Shift','e'); ?></td>
  <th align='left'><?php  xl('Provider','e'); ?></td>
  <th align='right'><?php  xl('Encounters','e'); ?></td>
<?php } ?>
 </tr>
 </thead>
 <tbody>
<?php
if ($res) {
  $lastshiftname = "";
  $shift_encounters = 0;
  $lastdocname = "";
  $doc_encounters = 0;
  $total_encounters = 0;

  while ($row = sqlFetchArray($res)) {
    $patient_id = $row['pid'];
    $def_provider_id = 0 + $row['provider_id'];
    $errmsg = '';

    $shiftname = empty($row['shiftname']) ?
      ('--' . xl('Unassigned') . '--') : $row['shiftname'];

    $docname = '--' . xl('Unassigned') . '--';
    if (!empty($row['ulname']) || !empty($row['ufname'])) {
      $docname = $row['ulname'];
      if (!empty($row['ufname']) || !empty($row['umname']))
        $docname .= ', ' . $row['ufname'] . ' ' . $row['umname'];
    }

    if ($shiftname != $lastshiftname || $docname != $lastdocname) {
      if (!$form_details) {
        show_doc_total($lastshiftname, $lastdocname, $doc_encounters);
      }
      $doc_encounters = 0;
    }
    ++$doc_encounters;

    if ($shiftname != $lastshiftname) {
      if (!$form_details || $form_orderby == 'shift') {
        show_shift_total($lastshiftname, $shift_encounters);
      }
      $shift_encounters = 0;
    }
    ++$shift_encounters;

    if ($form_details) {
      // Fetch all other forms for this encounter.
      $encnames = '';
      $encarr = getFormByEncounter($patient_id, $row['encounter'],
        "formdir, user, form_name, form_id");
      foreach ($encarr as $enc) {
        if ($enc['formdir'] == 'newpatient') continue;
        if ($encnames) $encnames .= '<br />';
        $encnames .= $enc['form_name'];
      }

      // Fetch coding and compute billing status.
      $coded = "";
      $billed_count = 0;
      $unbilled_count = 0;
      $last_provider_id = -1;

      $billres = sqlStatement("SELECT " .
        "b.code_type, b.code, b.code_text, b.billed, u.id, " .
        "u.lname, u.fname, u.username " .
        "FROM billing AS b " .
        "LEFT JOIN users AS u ON u.id = IF(b.provider_id, b.provider_id, '$def_provider_id') " .
        "WHERE " .
        "b.pid = '" . $row['pid'] . "' AND " .
        "b.encounter = '" . $row['encounter'] . "' AND " .
        "b.activity = 1 " .
        "ORDER BY u.lname, u.fname, u.id, b.code_type, b.code");

      while ($billrow = sqlFetchArray($billres)) {
        if ($billrow['code_type'] != 'COPAY' && $billrow['code_type'] != 'TAX') {
          $provider_id = empty($billrow['id']) ? 0 : 0 + $billrow['id'];
          if ($provider_id != $last_provider_id) {
            $last_provider_id = $provider_id;
            $provname = 'Unknown';
            if ($provider_id) {
              if (empty($billrow['lname'])) {
                $provname = '(' . $billrow['username'] . ')';
              }
              else {
                $provname = $billrow['lname'];
                if ($billrow['fname']) $provname .= ',' . substr($billrow['fname'], 0, 1);
              }
            }
            if (!empty($coded)) $coded .= '<br />';
            $coded .= "<span class='provname'>$provname:</span> ";
          }
          else {
            $coded .= ", ";
          }
          $coded .= $billrow['code'];
          if ($billrow['billed']) ++$billed_count; else ++$unbilled_count;
        }
      }

      // Figure product sales into billing status.
      $sres = sqlStatement("SELECT billed FROM drug_sales " .
        "WHERE pid = '{$row['pid']}' AND encounter = '{$row['encounter']}'");
      while ($srow = sqlFetchArray($sres)) {
        if ($srow['billed']) ++$billed_count; else ++$unbilled_count;
      }

      // Compute billing status.
      if ($billed_count && $unbilled_count) $status = xl('Mixed' );
      else if ($billed_count              ) $status = xl('Closed');
      else if ($unbilled_count            ) $status = xl('Open'  );
      else                                  $status = xl('Empty' );
?>
 <tr bgcolor='<?php echo $bgcolor ?>'>
  <td>
   <?php echo ($shiftname == $lastshiftname) ? "" : $shiftname ?>&nbsp;
  </td>
  <td>
   <?php echo ($shiftname == $lastshiftname && $docname == $lastdocname) ? "" : $docname ?>&nbsp;
  </td>
  <td>
   <?php echo oeFormatShortDate(substr($row['date'], 0, 10)) ?>&nbsp;
  </td>
  <td>
   <?php echo $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname']; ?>&nbsp;
  </td>
  <td>
   <?php echo $row['pubpid']; ?>&nbsp;
  </td>
  <td>
   <?php echo $status; ?>&nbsp;
  </td>
  <td>
   <?php echo $row['reason']; ?>&nbsp;
  </td>
  <td>
   <?php echo $encnames; ?>&nbsp;
  </td>
  <td class='billcodes'>
   <?php echo $coded; ?>
  </td>
 </tr>
<?php
    }
    ++$total_encounters;
    $lastshiftname = $shiftname;
    $lastdocname = $docname;
  }

  if (!$form_details) {
    show_doc_total($lastshiftname, $lastdocname, $doc_encounters);
  }

  if (!$form_details || $form_orderby == 'shift') {
    show_shift_total($lastshiftname, $shift_encounters);
  }

  echo " <tr>\n";
  echo "  <td class='detail' colspan='" . ($form_details ? 8 : 2) . "'>-- " . xl('Total') . " --</td>\n";
  echo "  <td class='detail' align='right'>$total_encounters</td>\n";
  echo " </tr>\n";
}
?>
</tbody>
</table>
</div>  <!-- end encresults -->

<input type="hidden" name="form_orderby" value="<?php echo $form_orderby ?>" />

</form>
</center>
</body>

<script language='JavaScript'>
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});

<?php if ($alertmsg) { echo " alert('$alertmsg');\n"; } ?>

</script>

</html>
