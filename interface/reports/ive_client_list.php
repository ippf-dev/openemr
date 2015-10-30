<?php
// Copyright (C) 2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is a report of IPPF IVE activity within a specified period.

$fake_register_globals = false;
$sanitize_all_escapes  = true;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");

function display_csv($s) {
  return addslashes($s);
}

function display_html($s) {
  $s = trim($s);
  if ($s === '') return '&nbsp';
  return htmlspecialchars($s);
}

// Get a list item's title, translated if appropriate.
function getListTitle($list, $option) {
  $row = sqlQuery("SELECT title FROM list_options WHERE " .
    "list_id = '$list' AND option_id = '$option'");
  if (empty($row['title'])) return $option;
  return xl_list_label($row['title']);
}

if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_facility  = $_POST['form_facility'];

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=ive_client_list.csv");
  header("Content-Description: File Transfer");

  // CSV headers:
  echo '"' . display_csv(xl('Client ID'           )) . '",';
  echo '"' . display_csv(xl('Name'                )) . '",';
  echo '"' . display_csv(xl('Age'                 )) . '",';
  echo '"' . display_csv(xl('Place of Residence'  )) . '",';
  echo '"' . display_csv(xl('IVE 1 Date'          )) . '",';
  echo '"' . display_csv(xl('IVE 1 Facility'      )) . '",';
  echo '"' . display_csv(xl('IVE 2 Date'          )) . '",';
  echo '"' . display_csv(xl('IVE 2 Facility'      )) . '",';
  echo '"' . display_csv(xl('IVE 3 Date'          )) . '",';
  echo '"' . display_csv(xl('IVE 3 Facility'      )) . '",';
  echo '"' . display_csv(xl('IVE 4 Date'          )) . '",';
  echo '"' . display_csv(xl('IVE 4 Facility'      )) . '",';
  echo '"' . display_csv(xl('Contraceptive Method')) . '",';
  // New:
  echo '"' . display_csv(xl('FP method counseling IVE3')) . '",'; // IVE_FPcouns
  echo '"' . display_csv(xl('FP method counseling IVE4')) . '",'; // IVE_meth_in_use
  echo '"' . display_csv(xl('FP Method'                )) . '",'; // IVE_contmetcounsel
  //
  echo '"' . display_csv(xl('IVE Reason'          )) . '",';
  echo '"' . display_csv(xl('Gest Age from LMP'   )) . '",';
  echo '"' . display_csv(xl('Gest Age from ECO'   )) . '",';
  echo '"' . display_csv(xl('IVE Procedure Type'  )) . '",';
  echo '"' . display_csv(xl('Causes of unwanted pregnancy')) . '",';
  echo '"' . display_csv(xl('Support network'             )) . '",';
  echo '"' . display_csv(xl('Women resolution about IVE'  )) . '",';
  echo '"' . display_csv(xl('Law applicable'              )) . '",';
  // New:
  echo '"' . display_csv(xl('Hospitalization Indication'  )) . '"' . "\n";// IVE_indication
  //

} // end export
else {
?>
<html>
<head>
<style>
td.dehead { font-size:10pt; text-align:center; }
td.detail { font-size:10pt; }
td.delink { color:#0000cc; font-size:10pt; cursor:pointer }
</style>

<script language="JavaScript">
</script>

<?php if (function_exists('html_header_show')) html_header_show(); ?>
<title><?php echo xlt('IVE Client List') ?></title>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php echo xlt('IVE Client List') ?></h2>

<form method='post' action='ive_client_list.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td align='center'>
<?php
/**********************************************************************
// Build a drop-down list of facilities.
//
$query = "SELECT id, name FROM facility ORDER BY name";
$fres = sqlStatement($query);
echo "   <select name='form_facility'>\n";
echo "    <option value=''>-- " . xla('All Facilities') . " --\n";
while ($frow = sqlFetchArray($fres)) {
  $facid = $frow['id'];
  echo "    <option value='$facid'";
  if ($facid == $form_facility) echo " selected";
  echo ">" . text($frow['name']) . "\n";
}
echo "   </select>&nbsp;\n";
**********************************************************************/
?>
   <?php echo xlt('From')?>:
   <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
   &nbsp;<?php echo xlt('To'); ?>:
   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
    id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
    title='<?php xl('Click here to choose a date','e'); ?>'>
   &nbsp;
   <input type='submit' name='form_refresh' value="<?php echo xla('Refresh') ?>">
   &nbsp;
   <input type='submit' name='form_csvexport' value="<?php echo xla('Export to CSV') ?>">
   &nbsp;
   <input type='button' value='<?php echo xla('Print'); ?>' onclick='window.print()' />
  </td>
 </tr>

 <tr>
  <td height="1">
  </td>
 </tr>
</table>

<table border='0' cellpadding='1' cellspacing='2' width='98%'>
 <tr bgcolor="#dddddd" style="font-weight:bold">
  <td class="dehead"><?php echo xlt('Client ID'           ); ?></td>
  <td class="dehead"><?php echo xlt('Name'                ); ?></td>
  <td class="dehead"><?php echo xlt('Age'                 ); ?></td>
  <td class="dehead"><?php echo xlt('Place of Residence'  ); ?></td>
  <td class="dehead"><?php echo xlt('IVE 1 Date'          ); ?></td>
  <td class="dehead"><?php echo xlt('IVE 1 Facility'      ); ?></td>
  <td class="dehead"><?php echo xlt('IVE 2 Date'          ); ?></td>
  <td class="dehead"><?php echo xlt('IVE 2 Facility'      ); ?></td>
  <td class="dehead"><?php echo xlt('IVE 3 Date'          ); ?></td>
  <td class="dehead"><?php echo xlt('IVE 3 Facility'      ); ?></td>
  <td class="dehead"><?php echo xlt('IVE 4 Date'          ); ?></td>
  <td class="dehead"><?php echo xlt('IVE 4 Facility'      ); ?></td>
  <td class="dehead"><?php echo xlt('Contraceptive Method'); ?></td>
  <td class="dehead"><?php echo xlt('FP method counseling IVE3'   ); ?></td>
  <td class="dehead"><?php echo xlt('FP method counseling IVE4'   ); ?></td>
  <td class="dehead"><?php echo xlt('FP Method'                   ); ?></td>
  <td class="dehead"><?php echo xlt('IVE Reason'          ); ?></td>
  <td class="dehead"><?php echo xlt('Gest Age from LMP'   ); ?></td>
  <td class="dehead"><?php echo xlt('Gest Age from ECO'   ); ?></td>
  <td class="dehead"><?php echo xlt('IVE Procedure Type'  ); ?></td>
  <td class="dehead"><?php echo xlt('Causes of unwanted pregnancy'); ?></td>
  <td class="dehead"><?php echo xlt('Support network'             ); ?></td>
  <td class="dehead"><?php echo xlt('Women resolution about IVE'  ); ?></td>
  <td class="dehead"><?php echo xlt('Law applicable'              ); ?></td>
  <td class="dehead"><?php echo xlt('Hospitalization Indication'  ); ?></td>
 </tr>

<?php
} // end not export

if (isset($_POST['form_refresh']) || isset($_POST['form_csvexport'])) {
  $reparr = array();

  // These are the desired LBF visit fields. Values are in the shared_attributes table.
  $vfields = array(
    'IVE_facility',
    'IVE_gestage_LMP',
    'IVE_gestage_ECO',
    'IVE_mainIVEreason',
    'IVE_method',
    'IVE_hosp_proc',
    'IVE_currmethFP',
    'IVE_reasunwantedp',
    'IVE_suppnet',
    'IVE_womenres',
    'IVE_lawf1',
    'IVE_FPcouns',
    'IVE_meth_in_use',
    'IVE_contmetcounsel',
    'IVE_indication',
  );
  $varr = array();
  // Note this gives us one row per IVE form instance.
  $query = "SELECT f.formdir, f.pid, f.encounter, fe.date, fe.facility_id, " .
    "p.pubpid, p.fname, p.mname, p.lname, p.state, p.DOB ";
  foreach ($vfields as $vkey => $vfield) {
    $query .= ", s$vkey.field_value AS `$vfield`";
  }
  $query .= " FROM forms AS f " .
    "JOIN form_encounter AS fe ON fe.pid = f.pid AND fe.encounter = f.encounter " .
    "JOIN patient_data AS p ON p.pid = f.pid ";
  foreach ($vfields as $vkey => $vfield) {
    $query .= "LEFT JOIN shared_attributes AS s$vkey ON s$vkey.pid = f.pid AND " .
      "s$vkey.encounter = f.encounter AND s$vkey.field_id = ? ";
    $varr[] = $vfield;
  }
  $query .= "WHERE f.formdir LIKE 'LBFIve%' AND f.deleted = 0 AND " .
    "fe.date >= '$form_from_date 00:00:00' AND fe.date <= '$form_to_date 23:59:59' " .
    "ORDER BY p.pubpid, f.pid, f.encounter, f.form_id";
  $res = sqlStatement($query, $varr);

  while ($row = sqlFetchArray($res)) {
    $rowpid = intval($row['pid']);
    if (!isset($reparr[$rowpid])) {
      $reparr[$rowpid] = $row;
      /****************************************************************
      $reparr[$rowpid]['date1'] = '';
      $reparr[$rowpid]['date2'] = '';
      $reparr[$rowpid]['date3'] = '';
      $reparr[$rowpid]['date4'] = '';
      $reparr[$rowpid]['fac1' ] = 0;
      $reparr[$rowpid]['fac2' ] = 0;
      $reparr[$rowpid]['fac3' ] = 0;
      $reparr[$rowpid]['fac4' ] = 0;
      ****************************************************************/
    }
    $date = substr($row['date'], 0, 10);
    $facility = intval($row['facility_id']);
    if ($row['formdir'] == 'LBFIve2') {
      $reparr[$rowpid]['date2'] = $date;
      $reparr[$rowpid]['fac2' ] = $facility;
    }
    else if ($row['formdir'] == 'LBFIve3') {
      $reparr[$rowpid]['date3'] = $date;
      $reparr[$rowpid]['fac3' ] = $facility;
    }
    else if ($row['formdir'] == 'LBFIve4') {
      $reparr[$rowpid]['date4'] = $date;
      $reparr[$rowpid]['fac4' ] = $facility;
    }
    else {
      $reparr[$rowpid]['date1'] = $date;
      $reparr[$rowpid]['fac1' ] = $facility;
    }
    foreach ($vfields as $vfield) {
      if (!empty($row['$vfield'])) {
        $reparr[$rowpid][$vfield] = $row[$vfield];
      }
    }
  }

  foreach ($reparr as $row) {
    // Patient name string.
    $name = $row['fname'];
    if ($row['mname']) {
      if ($name) $name .= ' ';
      $name .= $row['mname'];
    }
    if ($row['lname']) {
      if ($name) $name .= ' ';
      $name .= $row['lname'];
    }
    // Patient age as of the initial encounter date.
    $age = getPatientAge($row['DOB'], preg_replace("/-/", "", substr($row['date'], 0, 10)));
    // Patient place of residence, just the state.
    $state = getListTitle('state', $row['state']);
    // Get date and facility name for each IVE form.
    for ($i = 1; $i <= 4; ++$i) {
      $GLOBALS["date$i"] = empty($row["date$i"]) ? '' : oeFormatShortDate($row["date$i"]);
      $GLOBALS["fac$i"] = '';
      if (!empty($row["fac$i"])) {
        $tmp = getFacility($row["fac$i"]);
        if (isset($tmp['name'])) $GLOBALS["fac$i"] = $tmp['name'];
      }
    }
    // Contraceptive method.
    $contrameth = empty($row['IVE_currmethFP']) ? '' : getListTitle('IVE_contrameth', $row['IVE_currmethFP']);
    // IVE reason.
    $reason = empty($row['IVE_mainIVEreason']) ? '' : getListTitle('IVE_main', $row['IVE_mainIVEreason']);
    // Gestational ages.
    $gestlmp = empty($row['IVE_gestage_LMP']) ? '' : $row['IVE_gestage_LMP'];
    $gesteco = empty($row['IVE_gestage_ECO']) ? '' : $row['IVE_gestage_ECO'];
    // IVE procedure type. Could be ambulatory or inpatient hospital.
    $ivemethod = '';
    if (!empty($row['IVE_hosp_proc'])) {
      $ivemethod = getListTitle('IVE_hosp_procedd', $row['IVE_hosp_proc']);
    }
    else if (!empty($row['IVE_method'])) {
      $ivemethod = getListTitle('IVE_meth', $row['IVE_method']);
    }
    // Causes of unwanted pregnancy
    $ivecauses = empty($row['IVE_reasunwantedp']) ? '' : getListTitle('IVE_unw_preg', $row['IVE_reasunwantedp']);
    // Support network
    $ivesuppnet = empty($row['IVE_suppnet']) ? '' : getListTitle('IVE_support', $row['IVE_suppnet']);
    // Women resolution about IVE
    $ivewomres = empty($row['IVE_womenres']) ? '' : getListTitle('IVE_womres', $row['IVE_womenres']);
    // Law applicable 
    $ivelaw = empty($row['IVE_lawf1']) ? '' : getListTitle('IVE_law', $row['IVE_lawf1']);
    // FP method counseling IVE3.
    $ive3couns = empty($row['IVE_FPcouns']) ? '' : getListTitle('yesno', $row['IVE_FPcouns']);
    // FP method counseling IVE4.
    $ive4couns = empty($row['IVE_meth_in_use']) ? '' : getListTitle('yesno', $row['IVE_meth_in_use']);
    // FP Method.
    $ivecmethod = empty($row['IVE_contmetcounsel']) ? '' : getListTitle('IVE_contrameth' , $row['IVE_contmetcounsel']);
    // Hospitalization Indication.
    $iveind = empty($row['IVE_indication']) ? '' : getListTitle('IVE_indications', $row['IVE_indication']);

    if ($_POST['form_csvexport']) {
      echo '"'  . display_csv($row['pubpid']) . '"';
      echo ',"' . display_csv($name         ) . '"';
      echo ',"' . display_csv($age          ) . '"';
      echo ',"' . display_csv($state        ) . '"';
      echo ',"' . display_csv($date1        ) . '"';
      echo ',"' . display_csv($fac1         ) . '"';
      echo ',"' . display_csv($date2        ) . '"';
      echo ',"' . display_csv($fac2         ) . '"';
      echo ',"' . display_csv($date3        ) . '"';
      echo ',"' . display_csv($fac3         ) . '"';
      echo ',"' . display_csv($date4        ) . '"';
      echo ',"' . display_csv($fac4         ) . '"';
      echo ',"' . display_csv($contrameth   ) . '"';
      echo ',"' . display_csv($ive3couns    ) . '"';
      echo ',"' . display_csv($ive4couns    ) . '"';
      echo ',"' . display_csv($ivecmethod   ) . '"';
      echo ',"' . display_csv($reason       ) . '"';
      echo ',"' . display_csv($gestlmp      ) . '"';
      echo ',"' . display_csv($gesteco      ) . '"';
      echo ',"' . display_csv($ivemethod    ) . '"';
      echo ',"' . display_csv($ivecauses    ) . '"';
      echo ',"' . display_csv($ivesuppnet   ) . '"';
      echo ',"' . display_csv($ivewomres    ) . '"';
      echo ',"' . display_csv($ivelaw       ) . '"';
      echo ',"' . display_csv($iveind       ) . '"' . "\n";
    }
    else {
      ++$encount;
      $bgcolor = "#" . (($encount & 1) ? "ddddff" : "ffdddd");
      echo " <tr bgcolor='$bgcolor'>\n";
      echo "  <td class='detail'>" . display_html($row['pubpid']) . "</td>\n";
      echo "  <td class='detail'>" . display_html($name      ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($age       ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($state     ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($date1     ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($fac1      ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($date2     ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($fac2      ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($date3     ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($fac3      ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($date4     ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($fac4      ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($contrameth) . "</td>\n";
      echo "  <td class='detail'>" . display_html($ive3couns ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($ive4couns ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($ivecmethod) . "</td>\n";
      echo "  <td class='detail'>" . display_html($reason    ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($gestlmp   ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($gesteco   ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($ivemethod ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($ivecauses ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($ivesuppnet) . "</td>\n";
      echo "  <td class='detail'>" . display_html($ivewomres ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($ivelaw    ) . "</td>\n";
      echo "  <td class='detail'>" . display_html($iveind    ) . "</td>\n";
      echo " </tr>\n";
    } // end not export
  } // end reporting loop
} // end form refresh

if (!$_POST['form_csvexport']) {
?>

</table>
</form>
</center>
</body>

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
} // end not export
?>
