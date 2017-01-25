<?php
/**
 * Patient selector screen.
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/options.inc.php");

$fstart = isset($_REQUEST['fstart']) ? $_REQUEST['fstart'] : 0;
$popup  = empty($_REQUEST['popup']) ? 0 : 1;
$message = isset($_GET['message']) ? $_GET['message'] : "";


// This matters only if home_facility is a mandatory demographics field:
$form_all_facilities = empty($_POST['form_all_facilities']) ? 0 : 1;

// These items apply to the alternate patient search results style.
$use_facility_checkbox = false;
if (!empty($GLOBALS['patient_search_results_style'])) {
  // Alternate patient search results style; this gets address plus other
  // fields that are mandatory, up to a limit of 5.
  $extracols = array();
  $tres = sqlStatement("SELECT * FROM layout_options " .
    "WHERE form_id = 'DEM' AND ( uor > 1 AND field_id != '' " .
    "OR uor > 0 AND field_id = 'street' ) AND " .
    "field_id NOT LIKE '_name' AND " .
    "field_id NOT LIKE 'phone%' AND " .
    "field_id NOT LIKE 'title' AND " .
    "field_id NOT LIKE 'ss' AND " .
    "field_id NOT LIKE 'DOB' AND " .
    "field_id NOT LIKE 'pubpid' " .
    "ORDER BY group_name, seq LIMIT 5");
  while ($trow = sqlFetchArray($tres)) {
    $extracols[$trow['field_id']] = $trow;
    if ($trow['field_id'] == 'home_facility') $use_facility_checkbox = true;
  }
}
?>

<html>
<head>
<?php html_header_show();?>

<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<style>
form {
    padding: 0px;
    margin: 0px;
}
#searchCriteria {
    text-align: center;
    width: 100%;
    font-size: 0.8em;
    background-color: #ddddff;
    font-weight: bold;
    padding: 3px;
}
#searchResultsHeader { 
    width: 100%;
    background-color: lightgrey;
}
#searchResultsHeader table { 
    width: 96%;  /* not 100% because the 'searchResults' table has a scrollbar */
    border-collapse: collapse;
}
#searchResultsHeader th {
    font-size: 0.7em;
}
#searchResults {
    width: 100%;
    height: 80%;
    overflow: auto;
}

.srName { width: 12%; }
.srPhone { width: 11%; }
.srSS { width: 11%; }
.srDOB { width: 7%; }
.srID { width: 12%; }
.srPID { width: 7%; }
.srNumEnc { width: 11%; }
.srNumDays { width: 11%; }
.srDateLast { width: 7%; }
.srDateNext { width: 7%; }
.srMisc { width: 10%; }
.srIsOpen { font-weight: bold; }

#searchResults table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
}
#searchResults tr {
    cursor: hand;
    cursor: pointer;
}
#searchResults td {
    font-size: 0.7em;
    border-bottom: 1px solid #eee;
}
.oneResult { }
.billing { color: red; font-weight: bold; }
.highlight { 
    background-color: #336699;
    color: white;
}
</style>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.2.2.min.js"></script>

<script language="JavaScript">

// This is called when forward or backward paging is done.
//
function submitList(offset) {
 var f = document.forms[0];
 var i = parseInt(f.fstart.value) + offset;
 if (i < 0) i = 0;
 f.fstart.value = i;
 top.restoreSession();
 f.submit();
}

</script>

</head>
<body class="body_top">

<form method='post' action='patient_select.php' name='theform' onsubmit='return top.restoreSession()'>
<input type='hidden' name='fstart'  value='<?php echo htmlspecialchars( $fstart, ENT_QUOTES); ?>' />

<?php
$MAXSHOW = 100; // maximum number of results to display at once

//the maximum number of patient records to display:
$sqllimit = $MAXSHOW;
$given = "*";
$orderby = "lname ASC, fname ASC";

$today = date('Y-m-d');
if ($GLOBALS['patient_search_results_sort']) {
  // Here "open visits" are visits that have at least one unbilled service or product or that
  // have no line items at all, excluding certain visits that were manufactured artificially.
  $given .=
    ", (SELECT COUNT(fe1.id) " .
    "FROM form_encounter AS fe1 " .
    "LEFT JOIN billing AS b1 ON " .
    "b1.pid = fe1.pid AND b1.encounter = fe1.encounter AND b1.activity = 1 AND b1.billed = 0 " .
    "LEFT JOIN drug_sales AS s1 ON " .
    "s1.pid = fe1.pid AND s1.encounter = fe1.encounter AND s1.billed = 0 " .
    "WHERE fe1.pid = patient_data.pid AND fe1.reason != 'PreOpenEMR Data' AND " .
    "(b1.id IS NOT NULL OR s1.sale_id IS NOT NULL)) " .
    "AS unbilledvisits" .
    ", (SELECT COUNT(fe2.id) " .
    "FROM form_encounter AS fe2 " .
    "LEFT JOIN billing AS b2 ON " .
    "b2.pid = fe2.pid AND b2.encounter = fe2.encounter AND b2.activity = 1 " .
    "LEFT JOIN drug_sales AS s2 ON " .
    "s2.pid = fe2.pid AND s2.encounter = fe2.encounter " .
    "WHERE fe2.pid = patient_data.pid AND fe2.reason != 'PreOpenEMR Data' AND " .
    "b2.id IS NULL AND s2.sale_id IS NULL) " .
    "AS emptyvisits";
  // Open visits will sort first because for those the compound condition here will
  // evaluate as false, and in SQL false is 0 and true is 1.
  $orderby = "unbilledvisits = 0 AND emptyvisits = 0, $orderby";
}

$search_service_code = strip_escape_custom(trim($_POST['search_service_code']));
echo "<input type='hidden' name='search_service_code' value='" .
  htmlspecialchars($search_service_code, ENT_QUOTES) . "' />\n";

$condition = '';
if ($use_facility_checkbox && !$form_all_facilities) {
  $tmp = sqlQuery("SELECT facility_id FROM users WHERE id = '" . $_SESSION['authUserID'] . "'");
  if (!empty($tmp['facility_id'])) {
    $condition = "home_facility = '" . $tmp['facility_id'] . "'";
  }
  else {
    $use_facility_checkbox = false;
  }
}

if ($popup) {
  echo "<input type='hidden' name='popup' value='1' />\n";

  // Construct WHERE clause and save search parameters as form fields.
  $sqlBindArray = array();
  $where = "1 = 1";
  $fres = sqlStatement("SELECT * FROM layout_options " .
    "WHERE form_id = 'DEM' AND uor > 0 AND field_id != '' " .
    "ORDER BY group_name, seq");
  while ($frow = sqlFetchArray($fres)) {
    $field_id  = $frow['field_id'];
    if (strpos($field_id, 'em_') === 0) continue;
    $data_type = $frow['data_type'];
    if (!empty($_REQUEST[$field_id])) {
      $value = trim($_REQUEST[$field_id]);
      if ($field_id == 'pid') {
        $where .= " AND $field_id = ?";
        array_push($sqlBindArray,$value);
      }
      else if ($field_id == 'pubpid') {
        $where .= " AND $field_id LIKE ?";
        array_push($sqlBindArray,$value);
      }
      else {
        $where .= " AND $field_id LIKE ?";
        array_push($sqlBindArray,$value."%");
      }
      echo "<input type='hidden' name='" . htmlspecialchars( $field_id, ENT_QUOTES) .
        "' value='" . htmlspecialchars( $value, ENT_QUOTES) . "' />\n";
    }
  }

  // If a non-empty service code was given, then restrict to patients who
  // have been provided that service.  Since the code is used in a LIKE
  // clause, % and _ wildcards are supported.
  if ($search_service_code) {
    $where .=
      " AND ( SELECT COUNT(*) FROM billing AS b WHERE " .
      "b.pid = patient_data.pid AND " .
      "b.activity = 1 AND " .
      "b.code_type != 'COPAY' AND " .
      "b.code LIKE ? " .
      ") > 0";
    array_push($sqlBindArray, $search_service_code);
  }

  if ($condition) $where .= " AND $condition";
  
  $sql = "SELECT $given FROM patient_data " .
    "WHERE $where ORDER BY $orderby LIMIT $fstart, $sqllimit";
  $rez = sqlStatement($sql,$sqlBindArray);
  $result = array();
  while ($row = sqlFetchArray($rez)) $result[] = $row;
  _set_patient_inc_count($sqllimit, count($result), $where, $sqlBindArray);
}
else {
  $patient = $_REQUEST['patient'];
  $findBy  = $_REQUEST['findBy'];
  $searchFields = strip_escape_custom($_REQUEST['searchFields']);
  $exact = !empty($_REQUEST['find_exact']);

  echo "<input type='hidden' name='patient' value='" . htmlspecialchars( $patient, ENT_QUOTES) . "' />\n";
  echo "<input type='hidden' name='findBy'  value='" . htmlspecialchars( $findBy, ENT_QUOTES) . "' />\n";
  echo "<input type='hidden' name='searchFields' value='" .attr($searchFields) . "' />\n";  

  if ($findBy == "Last")
      $result = getPatientLnames("$patient", $given, $orderby, $sqllimit, $fstart, $exact, $condition);
  else if ($findBy == "ID")
      $result = getPatientId("$patient", $given, "id ASC, ".$orderby, $sqllimit, $fstart, $exact, $condition);
  else if ($findBy == "DOB")
      $result = getPatientDOB("$patient", $given, "DOB ASC, ".$orderby, $sqllimit, $fstart, $exact, $condition);
  else if ($findBy == "SSN")
      $result = getPatientSSN("$patient", $given, "ss ASC, ".$orderby, $sqllimit, $fstart, $exact, $condition);
  elseif ($findBy == "Phone")                  //(CHEMED) Search by phone number
      $result = getPatientPhone("$patient", $given, $orderby, $sqllimit, $fstart, $exact, $condition);
  else if ($findBy == "Any")
      $result = getByPatientDemographics("$patient", $given, $orderby, $sqllimit, $fstart, $exact, $condition);
  else if ($findBy == "Filter") {
    $result = getByPatientDemographicsFilter($searchFields, "$patient",
      $given, $orderby, $sqllimit, $fstart, $search_service_code, $exact, $condition);
  }
}
?>

<table border='0' cellpadding='5' cellspacing='0' width='100%'>
 <tr>
  <td class='text'>
   <a href="./patient_select_help.php" target=_new onclick='top.restoreSession()'>[<?php echo htmlspecialchars( xl('Help'), ENT_NOQUOTES); ?>]&nbsp</a>
  </td>
  <td class='text' align='center'>
<?php

if ($use_facility_checkbox) {
  // Checkbox to include all facilities. Resubmit when clicked.
  echo "<input type='checkbox' name='form_all_facilities' value='1' onclick='submitList(0);'";
  if ($form_all_facilities) echo " checked";
  echo " />" . xlt('All Facilities') . '&nbsp;';
}

if ($message) echo "<font color='red'><b>".text($message)."</b></font>\n";
?>

  </td>
  <td class='text' align='right'>
<?php
// Show start and end row number, and number of rows, with paging links.
//
// $count = $fstart + $GLOBALS['PATIENT_INC_COUNT']; // Why did I do that???
$count = $GLOBALS['PATIENT_INC_COUNT'];
$fend = $fstart + $MAXSHOW;
if ($fend > $count) $fend = $count;
?>
<?php if ($fstart) { ?>
   <a href="javascript:submitList(-<?php echo $MAXSHOW ?>)">
    &lt;&lt;
   </a>
   &nbsp;&nbsp;
<?php } ?>
   <?php echo ($fstart + 1) . htmlspecialchars( " - $fend of $count", ENT_NOQUOTES); ?>
<?php if ($count > $fend) { ?>
   &nbsp;&nbsp;
   <a href="javascript:submitList(<?php echo $MAXSHOW ?>)">
    &gt;&gt;
   </a>
<?php } ?>
  </td>
 </tr>
</table>

</form>

<div id="searchResultsHeader">
<table>
<tr>
<th class="srName"><?php echo htmlspecialchars( xl('Name'), ENT_NOQUOTES);?></th>
<th class="srPhone"><?php echo htmlspecialchars( xl('Phone'), ENT_NOQUOTES);?></th>
<th class="srSS"><?php echo htmlspecialchars( xl('SS'), ENT_NOQUOTES);?></th>
<th class="srDOB"><?php echo htmlspecialchars( xl('DOB'), ENT_NOQUOTES);?></th>
<th class="srID"><?php echo htmlspecialchars( xl('ID'), ENT_NOQUOTES);?></th>
<th class="srDateLast"><?php echo xlt('Last Visit');?></th>

<?php if (empty($GLOBALS['patient_search_results_style'])) { ?>
<th class="srPID"><?php echo htmlspecialchars( xl('PID'), ENT_NOQUOTES);?></th>
<th class="srNumEnc"><?php echo htmlspecialchars( xl('[Number Of Encounters]'), ENT_NOQUOTES);?></th>
<th class="srNumDays"><?php echo htmlspecialchars( xl('[Days Since Last Encounter]'), ENT_NOQUOTES);?></th>
<!-- <th class="srDateLast"><?php echo htmlspecialchars( xl('[Date of Last Encounter]'), ENT_NOQUOTES);?></th> -->
<th class="srDateNext">
<?php
$add_days = 90;
if (!$popup && preg_match('/^(\d+)\s*(.*)/',$patient,$matches) > 0) {
  $add_days = $matches[1];
  $patient = $matches[2];
}
?>
[<?php echo htmlspecialchars( $add_days, ENT_NOQUOTES);?> <?php echo htmlspecialchars( xl('Days From Last Encounter'), ENT_NOQUOTES); ?>]
</th>

<?php
}
else {
  // Alternate patient search results style.
  foreach ($extracols as $trow) {
    echo "<th class='srMisc'>" . xl($trow['title']) . "</th>\n";
  }
}
?>

</tr>
</table>
</div>

<div id="searchResults">

<table>
<tr>
<?php
if ($result) {
    foreach ($result as $iter) {
        $extcls = (!empty($iter['unbilledvisits']) || !empty($iter['emptyvisits'])) ? ' srIsOpen' : '';
        echo "<tr class='oneresult' id='".htmlspecialchars( $iter['pid'], ENT_QUOTES)."'>";
        echo  "<td class='srName$extcls'>" . htmlspecialchars($iter['lname'] . ", " . $iter['fname']) . "</td>\n";
        //other phone number display setup for tooltip
        $phone_biz = '';
        if ($iter{"phone_biz"} != "") {
            $phone_biz = " [business phone ".$iter{"phone_biz"}."] ";
        }
        $phone_contact = '';
        if ($iter{"phone_contact"} != "") {
            $phone_contact = " [contact phone ".$iter{"phone_contact"}."] ";
        }
        $phone_cell = '';
        if ($iter{"phone_cell"} != "") {
            $phone_cell = " [cell phone ".$iter{"phone_cell"}."] ";
        }
        $all_other_phones = $phone_biz.$phone_contact.$phone_cell;
        if ($all_other_phones == '') {$all_other_phones = xl('No other phone numbers listed');}
        //end of phone number display setup, now display the phone number(s)
        echo "<td class='srPhone$extcls' title='".htmlspecialchars( $all_other_phones, ENT_QUOTES)."'>" .
	    htmlspecialchars( $iter['phone_home'], ENT_NOQUOTES) . "</td>\n";
        
        echo "<td class='srSS$extcls'>" . htmlspecialchars( $iter['ss'], ENT_NOQUOTES) . "</td>";
        echo "<td class='srDOB$extcls'>";
        if ($iter{"DOB"} != "0000-00-00 00:00:00") {
            echo text(oeFormatShortDate($iter['DOB']));
        } else {
            echo "&nbsp;";
        }
        echo "</td>";
        echo "<td class='srID$extcls'>" . htmlspecialchars( $iter['pubpid'], ENT_NOQUOTES) . "&nbsp;</td>";

        // Calculate date differences based on date of last encounter.
        $day_diff = ''; 
        $last_date_seen = ''; 
        $next_appt_date = ''; 
        $query = "SELECT max(form_encounter.date) as mydate, " .
          "(to_days(current_date()) - to_days(max(form_encounter.date))) as day_diff, " .
          "max(form_encounter.date) + interval ? day as next_appt, " .
          "dayname(max(form_encounter.date) + interval ? day) as next_appt_day " .
          "FROM form_encounter " .
          "WHERE form_encounter.pid = ?";
        $results = sqlQuery($query, array($add_days, $add_days, $iter["pid"]));
        if ($results) {
          $last_date_seen = $results['mydate']; 
          $day_diff       = $results['day_diff'];
          $next_appt_date = $results['next_appt_day'].', '.$results['next_appt'];
        }

        // Last Visit date.
        echo "<td class='srDateLast$extcls'>" . text(oeFormatShortDate($last_date_seen)) . "</td>\n";

        if (empty($GLOBALS['patient_search_results_style'])) {

          echo "<td class='srPID$extcls'>" . htmlspecialchars( $iter['pid'], ENT_NOQUOTES) . "</td>";

          /************************************************************
          //setup for display of encounter date info
          $encounter_count = 0;
          $day_diff = ''; 
          $last_date_seen = ''; 
          $next_appt_date= ''; 
          $pid = '';

          // calculate date differences based on date of last encounter with billing entries
          $query = "select DATE_FORMAT(max(form_encounter.date),'%m/%d/%y') as mydate," .
                  " (to_days(current_date())-to_days(max(form_encounter.date))) as day_diff," .
                  " DATE_FORMAT(max(form_encounter.date) + interval " .
	          add_escape_custom($add_days) .
                  " day,'%m/%d/%y') as next_appt, dayname(max(form_encounter.date) + interval " .
                  add_escape_custom($add_days) .
	          " day) as next_appt_day from form_encounter " .
                  "join billing on billing.encounter = form_encounter.encounter and " .
                  "billing.pid = form_encounter.pid and billing.activity = 1 and " .
                  "billing.code_type not like 'COPAY' where ".
                  "form_encounter.pid = ?";
          $statement= sqlStatement($query, array($iter{"pid"}) );
          if ($results = sqlFetchArray($statement)) {
              $last_date_seen = $results['mydate']; 
              $day_diff = $results['day_diff'];
              $next_appt_date= $results['next_appt_day'].', '.$results['next_appt'];
          }
          // calculate date differences based on date of last encounter regardless of billing
          $query = "select DATE_FORMAT(max(form_encounter.date),'%m/%d/%y') as mydate," .
                  " (to_days(current_date())-to_days(max(form_encounter.date))) as day_diff," .
                  " DATE_FORMAT(max(form_encounter.date) + interval " .
	          add_escape_custom($add_days) .
                  " day,'%m/%d/%y') as next_appt, dayname(max(form_encounter.date) + interval " .
                  add_escape_custom($add_days) .
	          " day) as next_appt_day from form_encounter " .
                  " where form_encounter.pid = ?";
          $statement= sqlStatement($query, array($iter{"pid"}) );
          if ($results = sqlFetchArray($statement)) {
              $last_date_seen = $results['mydate']; 
              $day_diff = $results['day_diff'];
              $next_appt_date= $results['next_appt_day'].', '.$results['next_appt'];
          }
          ************************************************************/

          $encounter_count = 0;

          //calculate count of encounters by distinct billing dates with cpt4
          //entries
          $query = "select count(distinct date) as encounter_count " .
                   " from billing ".
                   " where code_type not like 'COPAY' and activity = 1 " .
                   " and pid = ?";
          $statement= sqlStatement($query, array($iter{"pid"}) );
          if ($results = sqlFetchArray($statement)) {
              $encounter_count_billed = $results['encounter_count'];
          }
          // calculate count of encounters, regardless of billing
          $query = "select count(date) as encounter_count ".
                      " from form_encounter where ".
                      " pid = ?";
          $statement= sqlStatement($query, array($iter{"pid"}) );
          if ($results = sqlFetchArray($statement)) {
              $encounter_count = $results['encounter_count'];
          }
          echo "<td class='srNumEnc$extcls'>" . htmlspecialchars( $encounter_count, ENT_NOQUOTES) . "</td>\n";
          echo "<td class='srNumDay$extcls'>" . htmlspecialchars( $day_diff, ENT_NOQUOTES) . "</td>\n";
          // echo "<td class='srDateLast$extcls'>" . htmlspecialchars( $last_date_seen, ENT_NOQUOTES) . "</td>\n";
          echo "<td class='srDateNext$extcls'>" . htmlspecialchars( $next_appt_date, ENT_NOQUOTES) . "</td>\n";
        }

        else { // alternate search results style
          foreach ($extracols as $field_id => $frow) {
            echo "<td class='srMisc$extcls'>";
            echo generate_display_field($frow, $iter[$field_id]);

            echo"</td>\n";
          }
        }
    }
}
?>
</table>
</div>  <!-- end searchResults DIV -->

<script language="javascript">

// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    // $("#searchparm").focus();
    $(".oneresult").mouseover(function() { $(this).addClass("highlight"); });
    $(".oneresult").mouseout(function() { $(this).removeClass("highlight"); });
    $(".oneresult").click(function() { SelectPatient(this); });
    // $(".event").dblclick(function() { EditEvent(this); });
});

var SelectPatient = function (eObj) {
<?php 
// For the old layout we load a frameset that also sets up the new pid.
// The new layout loads just the demographics frame here, which in turn
// will set the pid and load all the other frames.
if ($GLOBALS['concurrent_layout']) {
    $newPage = "../../patient_file/summary/demographics.php?set_pid=";
    $target = "document";
}
else {
    $newPage = "../../patient_file/patient_file.php?set_pid=";
    $target = "top";
}
?>
    objID = eObj.id;
    var parts = objID.split("~");
    <?php if (!$popup) echo "top.restoreSession();\n"; ?>
    <?php if ($popup) echo "opener."; echo $target; ?>.location.href = '<?php echo $newPage; ?>' + parts[0];
    <?php if ($popup) echo "window.close();\n"; ?>
    return true;
}

</script>

</body>
</html>
