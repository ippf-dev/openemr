<?php
//function returns the next most recent appointment given a pid
function getNextAppointment ($pid) {
$query = "select * from calendar where pid=$pid and time>now() order by time limit 1";
$rez = sqlStatement($query);
echo "<!-- getNextAppointment $pid -$query- -->";
	return sqlFetchArray($rez);
}


//this function returns calendar data given date and provider
function getCalendarDay($datetime,$owner,$groupname) {
	$query = "select * from calendar where time like '$datetime%' and owner like '$owner' and groupname like '$groupname' order by time";
	$rez = sqlStatement($query);
	for($iter=0; $row=sqlFetchArray($rez); $iter++)
		$returnval[$iter]=$row;

echo "<!-- getCalendarDay -$query- $datetime $owner $groupname -->";
	return $returnval;
}

//toggle the status of a noshow calendar entry
function toggleNoShow( $cid) {
	$rez = sqlStatement("select noshow from calendar where id=$cid");
	$row=sqlFetchArray($rez);
	$oldnoshowval = $row{noshow};
	$newnoshowval = !($oldnoshowval);
	$query = "update calendar set noshow='$newnoshowval' where id=$cid";
	sqlStatement($query);
}


//return array of provider usernames
function getProviderUsernames() {
	$rez = sqlStatement("select distinct username, lname, fname from users " .
		"where authorized = 1 and username != ''");
	for($iter=0; $row=sqlFetchArray($rez); $iter++)
		$returnval[$iter]=$row;

	return $returnval;
}


function getIDfromUser($name) {
	$query = "select id from users where username=\"" . add_escape_custom($name) . "\" limit 1";
	$rez = sqlStatement($query);
	$row = sqlFetchArray($rez);
	if (!is_numeric($row['id']))
		return -1;
	else
		return $row['id'];
}
function getAuthorizedIDs() {
	$query = "select id, username from users where authorized=1 and username != ''";
	$rez = sqlStatement($query);
	for($iter=0; $row=sqlFetchArray($rez); $iter++)
		$returnval[$iter]=$row;
	return $returnval;
}


function getUserInfo() {
	$rez = sqlStatement("select distinct id, username, lname, fname, authorized, " .
		"info, facility from users where username != ''");
	for($iter=0; $row=sqlFetchArray($rez); $iter++)
		$returnval[$iter]=$row;

	return $returnval;
}
function getUsername($uID) {
	$pi = getProviderInfo($uID,false);
	if (strlen($pi["username"]) > 0) {
		return $pi['username'];
	}

	return "";
}

// Returns an array of the facility ids and names that the user is allowed to access.
// Access might be for inventory purposes ($inventory=true) or calendar purposes.
//
function getUserFacilities($uID, $orderby='id', $inventory=false) {
  $restrict = $inventory ? $GLOBALS['gbl_fac_warehouse_restrictions'] : $GLOBALS['restrict_user_facility'];
  if ($restrict) {
    // No entries in this table means the user is not restricted.
    $countrow = sqlQuery("SELECT count(*) AS count FROM users_facility WHERE " .
      "tablename = 'users' AND table_id = ?", array($uID));
  }
  if (!$restrict || empty($countrow['count'])) {
    $rez = sqlStatement("SELECT id, name, color FROM facility " .
      // "WHERE service_location != 0 ORDER BY $orderby");
      "ORDER BY $orderby");
  }
  else {
    // This query gets facilities that the user is authorized to access.
	  $rez = sqlStatement("SELECT f.id, f.name, f.color " .
      "FROM facility AS f " .
      "JOIN users AS u ON u.id = ? " .
      "WHERE f.id = u.facility_id OR f.id IN " .
      "(SELECT DISTINCT uf.facility_id FROM users_facility AS uf WHERE uf.tablename = 'users' AND uf.table_id = u.id) " .
      "ORDER BY f.$orderby", array($uID));
  }
  $returnVal = array();
  while ($row = sqlFetchArray($rez)) 
	  $returnVal[] = $row;
  return $returnVal;
}

// Returns an array of warehouse IDs for the given user and facility.
function getUserFacWH($uID, $fID) {
  $res = sqlStatement("SELECT warehouse_id FROM users_facility WHERE tablename = ? " .
    "AND table_id = ? AND facility_id = ?", array('users', $uID, $fID));
  $returnVal = array();
  while ($row = sqlFetchArray($res)) {
    if ($row['warehouse_id'] === '') continue;
    $returnVal[] = $row['warehouse_id'];
  }
  return $returnVal;
}

//retrieve the name based on the username
function getNamefromUsername($username) {
	$query = "select * from users where username like '$username' and username != ''";
	$res = sqlQuery($query);
	return $res;
}

//retrieve calendar information from calendar id
function getCalendarfromID ($calid) {
	
	//this table is deprecated and has been removed from new versions
	//return sqlQuery("select * from calendar where id='$calid'");
}

//get calendar dates in a range of dates
function getCalendarRanges ($fromdate, $todate, $username) {
	$rez = sqlStatement("select * from calendar where time>='$fromdate' and time<'$todate' and owner like '$username' order by time");
	for($iter=0; $row=sqlFetchArray($rez); $iter++)
		$returnval[$iter]=$row;

	return $returnval;
	
}


//get calendar dates in a range of dates
function getCalendarbyTimeandRange ($time, $fromdate, $todate, $username) {
	$query = "select * from calendar where time like '% $time%' and time>='$fromdate' and time<'$todate' and owner like '$username' order by time";
	$rez = sqlStatement($query);
	for($iter=0; $row=sqlFetchArray($rez); $iter++)
		$returnval[$iter]=$row;
echo "<!-- getcalendarbytimeandrange -$query- -->";
	return $returnval;
	
}


//add new calendar entry item
function newCalendarItem (
$pid,
$time,
$reason,
$owner,
$groupname


) {
	
	sqlQuery("insert into calendar set pid=?,time=?,reason=?,owner=?,groupname=?,date=now()", array($pid, $time, $reason, $owner, $groupname) );
}

//delete a calendar entry item
function deleteCalendarItem( $calid,$pid) {
	sqlQuery("delete from calendar where id=? and pid=?", array($calid, $pid) );
	
}


//save an edited calendar entry item
function saveCalendarUpdate (
$calid,
$pid,
$time,
$reason,
$owner,
$groupname


) {
	
	sqlQuery("update calendar set pid=?,time=?,reason=?,owner=?,groupname=?,date=now() where id=?", array($pid, $time, $reason, $owner, $groupname, $calid) );
}


//replicated functionality of postcalendar_getDate
function pc_getDate($format='%Y%m%d')  {
//    list($Date,$jumpday,$jumpmonth,$jumpyear) = pnVarCleanFromInput('Date','jumpday','jumpmonth','jumpyear');
	if(!isset($Date)) {
        // if we still don't have a date then calculate it
        $time = time();
		// check the jump menu
        if(!isset($jumpday))   $jumpday = strftime('%d',$time);
        if(!isset($jumpmonth)) $jumpmonth = strftime('%m',$time);
        if(!isset($jumpyear))  $jumpyear = strftime('%Y',$time);
		// create the correct date string
        $Date = (int) "$jumpyear$jumpmonth$jumpday";
    }
	$y = substr($Date,0,4);
	$m = substr($Date,4,2);
	$d = substr($Date,6,2);
    return strftime($format,mktime(0,0,0,$m,$d,$y));
}

?>
