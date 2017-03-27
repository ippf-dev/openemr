<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("../../library/acl.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/auth.inc");
require_once("$srcdir/formdata.inc.php");
require_once ($GLOBALS['srcdir'] . "/classes/postmaster.php");

$alertmsg = '';
$bg_msg = '';
$set_active_msg=0;
$show_message=0;

// This is called by usort().
// Sorts according to the value in $_POST['form_orderby'].
function userCmp($a, $b) {
  global $form_orderby;
  if ($a[$form_orderby] < $b[$form_orderby]) return -1;
  if ($a[$form_orderby] > $b[$form_orderby]) return  1;
  if ($a['username'] < $b['username']) return -1;
  if ($a['username'] > $b['username']) return  1;
  return 0;
}

// Update the users_facility table for this user from the POST variables.
//
function setUserFacilities($user_id) {
  if (empty($GLOBALS['restrict_user_facility'])) return;

  if (empty($_POST["schedule_facility"])) $_POST["schedule_facility"] = array();
  $tmpres = sqlStatement("SELECT * FROM users_facility WHERE " .
    "tablename = ? AND table_id = ?",
    array('users', $user_id));
  // $olduf will become an array of entries to delete.
  $olduf = array();
  while ($tmprow = sqlFetchArray($tmpres)) {
    $olduf[$tmprow['facility_id'] . '/' . $tmprow['warehouse_id']] = true;
  }
  // Now process the selection of facilities and warehouses.
  foreach($_POST["schedule_facility"] as $tqvar) {
    if (($i = strpos($tqvar, '/')) !== false) {
      $facid = substr($tqvar, 0, $i);
      $whid = substr($tqvar, $i + 1);
      // If there was also a facility-only selection for this warehouse then remove it.
      if (isset($olduf["$facid/"])) $olduf["$facid/"] = true;
    }
    else {
      $facid = $tqvar;
      $whid = '';
    }
    if (!isset($olduf["$facid/$whid"])) {
      sqlStatement("INSERT INTO users_facility SET tablename = ?, table_id = ?, " .
        "facility_id = ?, warehouse_id = ?",
        array('users', $user_id, $facid, $whid));
    }
    $olduf["$facid/$whid"] = false;
    if ($facid == $deffacid) $deffacid = 0;
  }
  // Now delete whatever is left over for this user.
  foreach ($olduf as $key => $value) {
    if ($value && ($i = strpos($key, '/')) !== false) {
      $facid = substr($key, 0, $i);
      $whid = substr($key, $i + 1);
      sqlStatement("DELETE FROM users_facility WHERE " .
        // The following screws up by matching all warehouse_id values when it's
        // an empty string. This needs debugging in sql.inc.
        /********************************************************
        "tablename = ? AND table_id = ? AND facility_id = ? AND warehouse_id = ?",
        array('users', $user_id, $facid, $whid));
        ********************************************************/
        "tablename = 'users' AND table_id = " . $user_id .
        " AND facility_id = '$facid' AND warehouse_id = '$whid'");
    }
  }
}

$form_orderby = empty($_POST['form_orderby']) ? 'username' : $_POST['form_orderby'];

/* Sending a mail to the admin when the breakglass user is activated only if $GLOBALS['Emergency_Login_email'] is set to 1 */
$bg_count=count($access_group);
$mail_id = explode(".",$SMTP_HOST);
for($i=0;$i<$bg_count;$i++){
if(($_GET['access_group'][$i] == "Emergency Login") && ($_GET['active'] == 'on') && ($_GET['pre_active'] == 0)){
  if(($_GET['get_admin_id'] == 1) && ($_GET['admin_id'] != "")){
	$res = sqlStatement("select username from users where id={$_GET["id"]}");
	$row = sqlFetchArray($res);
	$uname=$row['username'];
	$mail = new MyMailer();
        $mail->SetLanguage("en",$GLOBALS['fileroot'] . "/library/" );
        $mail->From = "admin@".$mail_id[1].".".$mail_id[2];     
        $mail->FromName = "Administrator OpenEMR";
        $text_body  = "Hello Security Admin,\n\n The Emergency Login user ".$uname.
                                                " was activated at ".date('l jS \of F Y h:i:s A')." \n\nThanks,\nAdmin OpenEMR.";
        $mail->Body = $text_body;
        $mail->Subject = "Emergency Login User Activated";
        $mail->AddAddress($_GET['admin_id']);
        $mail->Send();
}
}
}

/* To refresh and save variables in mail frame */
if (isset($_POST["privatemode"]) && $_POST["privatemode"] =="user_admin") {
    if ($_POST["mode"] == "update") {
      if (isset($_POST["rumple"])) {
        // $tqvar = addslashes(trim($_POST["username"]));
        $tqvar = trim(formData('rumple','P'));
        $user_data = sqlQuery("select * from users where id={$_POST["id"]}");
        sqlStatement("update users set username='$tqvar' where id={$_POST["id"]}");
        sqlStatement("update groups set user='$tqvar' where user='". $user_data["username"]  ."'");
        //echo "query was: " ."update groups set user='$tqvar' where user='". $user_data["username"]  ."'" ;
      }
      if ($_POST["taxid"]) {
        $tqvar = formData('taxid','P');
        sqlStatement("update users set federaltaxid='$tqvar' where id={$_POST["id"]}");
      }
      if ($_POST["state_license_number"]) {
        $tqvar = formData('state_license_number','P');
        sqlStatement("update users set state_license_number='$tqvar' where id={$_POST["id"]}");
      }
      if ($_POST["drugid"]) {
        $tqvar = formData('drugid','P');
        sqlStatement("update users set federaldrugid='$tqvar' where id={$_POST["id"]}");
      }
      if ($_POST["upin"]) {
        $tqvar = formData('upin','P');
        sqlStatement("update users set upin='$tqvar' where id={$_POST["id"]}");
      }
      if ($_POST["npi"]) {
        $tqvar = formData('npi','P');
        sqlStatement("update users set npi='$tqvar' where id={$_POST["id"]}");
      }
      if ($_POST["taxonomy"]) {
        $tqvar = formData('taxonomy','P');
        sqlStatement("update users set taxonomy = '$tqvar' where id= {$_POST["id"]}");
      }
      if ($_POST["lname"]) {
        $tqvar = formData('lname','P');
        sqlStatement("update users set lname='$tqvar' where id={$_POST["id"]}");
      }
      if ($_POST["job"]) {
        $tqvar = formData('job','P');
        sqlStatement("update users set specialty='$tqvar' where id={$_POST["id"]}");
      }
      if ($_POST["mname"]) {
              $tqvar = formData('mname','P');
              sqlStatement("update users set mname='$tqvar' where id={$_POST["id"]}");
      }
      if ($_POST["facility_id"]) {
              $tqvar = formData('facility_id','P');
              sqlStatement("update users set facility_id = '$tqvar' where id = {$_POST["id"]}");
              //(CHEMED) Update facility name when changing the id
              sqlStatement("UPDATE users, facility SET users.facility = facility.name WHERE facility.id = '$tqvar' AND users.id = {$_POST["id"]}");
              //END (CHEMED)
      }

      setUserFacilities($_POST["id"]);

      if ($_POST["fname"]) {
              $tqvar = formData('fname','P');
              sqlStatement("update users set fname='$tqvar' where id={$_POST["id"]}");
      }

      //(CHEMED) Calendar UI preference
      if ($_POST["cal_ui"]) {
        $tqvar = formData('cal_ui','P');
        sqlStatement("update users set cal_ui = '$tqvar' where id = {$_POST["id"]}");

        // added by bgm to set this session variable if the current user has edited
        //   their own settings
        if ($_SESSION['authId'] == $_POST["id"]) {
          $_SESSION['cal_ui'] = $tqvar;
        }
      }
      //END (CHEMED) Calendar UI preference

      if (isset($_POST['default_warehouse'])) {
        sqlStatement("UPDATE users SET default_warehouse = '" .
          formData('default_warehouse','P') .
          "' WHERE id = '" . formData('id','P') . "'");
      }

      if (isset($_POST['irnpool'])) {
        sqlStatement("UPDATE users SET irnpool = '" .
          formData('irnpool','P') .
          "' WHERE id = '" . formData('id','P') . "'");
      }

     if ($_POST["adminPass"] && $_POST["stiltskin"]) { 
        require_once("$srcdir/authentication/password_change.php");
        $clearAdminPass=$_POST['adminPass'];
        $clearUserPass=$_POST['stiltskin'];
        $password_err_msg="";
        $success=update_password($_SESSION['authId'],$_POST['id'],$clearAdminPass,$clearUserPass,$password_err_msg);
        if(!$success)
        {
            error_log($password_err_msg);    
            $alertmsg.=$password_err_msg;
        }
     }

      // for relay health single sign-on
      if (isset($_POST["ssi_relayhealth"]) && $_POST["ssi_relayhealth"]) {
        $tqvar = formData('ssi_relayhealth','P');
        sqlStatement("update users set ssi_relayhealth = '$tqvar' where id = {$_POST["id"]}");
      }

      $tqvar  = $_POST["authorized"] ? 1 : 0;
      $actvar = $_POST["active"]     ? 1 : 0;
      $calvar = $_POST["calendar"]   ? 1 : 0;
  
      sqlStatement("UPDATE users SET authorized = $tqvar, active = $actvar, " .
        "calendar = $calvar, see_auth = '" . $_POST['see_auth'] . "' WHERE " .
        "id = {$_POST["id"]}");
      //Display message when Emergency Login user was activated 
      $bg_count=count($_POST['access_group']);
      for($i=0;$i<$bg_count;$i++){
        if(($_POST['access_group'][$i] == "Emergency Login") && ($_POST['pre_active'] == 0) && ($actvar == 1)){
         $show_message = 1;
        }
      }
      if(($_POST['access_group'])) {
        for($i=0;$i<$bg_count;$i++) {
          if(($_POST['access_group'][$i] == "Emergency Login") && ($_POST['user_type']) == "" && ($_POST['check_acl'] == 1) && ($_POST['active']) != ""){
            $set_active_msg=1;
          }
        }
      }	
      if ($_POST["comments"]) {
        $tqvar = formData('comments','P');
        sqlStatement("update users set info = '$tqvar' where id = {$_POST["id"]}");
      }
      $erxrole = formData('erxrole','P');
      sqlStatement("update users set newcrop_user_role = '$erxrole' where id = {$_POST["id"]}");

      if (isset($phpgacl_location) && acl_check('admin', 'acl')) {
        // Set the access control group of user
        $user_data = sqlQuery("select username from users where id={$_POST["id"]}");
        set_user_aro($_POST['access_group'], $user_data["username"],
          formData('fname','P'), formData('mname','P'), formData('lname','P'));
      }
    }
}

/* To refresh and save variables in mail frame  - Arb*/
if (isset($_POST["mode"])) {
  if ($_POST["mode"] == "new_user") {

    $calvar = $_POST["calendar"] ? 1 : 0;
    $actvar = $_POST["active"]   ? 1 : 0;

    $res = sqlStatement("select username from users where username = '" . trim(formData('rumple')) . "'");
    $doit = true;
    while ($row = sqlFetchArray($res)) {
      $doit = false;
    }

    if ($doit) {
      require_once("$srcdir/authentication/password_change.php");

      //if password expiration option is enabled,  calculate the expiration date of the password
      if($GLOBALS['password_expiration_days'] != 0){
        $exp_days = $GLOBALS['password_expiration_days'];
        $exp_date = date('Y-m-d', strtotime("+$exp_days days"));
      }

      $insertUserSQL=            
            "insert into users set " .
            "username = '"         . trim(formData('rumple'       )) .
            "', password = '"      . 'NoLongerUsed'                  .
            "', fname = '"         . trim(formData('fname'        )) .
            "', mname = '"         . trim(formData('mname'        )) .
            "', lname = '"         . trim(formData('lname'        )) .
            "', federaltaxid = '"  . trim(formData('taxid'        )) .
            "', state_license_number = '" . trim(formData('state_license_number' )) .
            "', newcrop_user_role = '"  . trim(formData('erxrole' )) .
            "', authorized = '"    . (empty($_POST['authorized']) ? 0 : 1) .
            "', info = '"          . trim(formData('comments'     )) .
            "', federaldrugid = '" . trim(formData('drugid'       )) .
            "', upin = '"          . trim(formData('upin'         )) .
            "', npi  = '"          . trim(formData('npi'          )).
            "', taxonomy = '"      . trim(formData('taxonomy'     )) .
            "', facility_id = '"   . trim(formData('facility_id'  )) .
            "', specialty = '"     . trim(formData('job'          )) .
            "', see_auth = '"      . trim(formData('see_auth'     )) .
            "', cal_ui = '"        . trim(formData('cal_ui'       )) .
            "', default_warehouse = '" . trim(formData('default_warehouse')) .
            "', irnpool = '"       . trim(formData('irnpool'      )) .
            "', calendar = '"      . $calvar                         .
            "', active = '"        . $actvar                         .
            "', pwd_expiration_date = '" . trim("$exp_date") .
            "'";
    
      $clearAdminPass=$_POST['adminPass'];
      $clearUserPass=$_POST['stiltskin'];
      $password_err_msg="";
      $prov_id="";
      $success = update_password($_SESSION['authId'], 0, $clearAdminPass, $clearUserPass,
        $password_err_msg, true, $insertUserSQL, trim(formData('rumple')), $prov_id);
      error_log($password_err_msg);
      $alertmsg .=$password_err_msg;
      if($success)
      {
        //set the facility name from the selected facility_id
        sqlStatement("UPDATE users, facility SET users.facility = facility.name WHERE facility.id = '" . trim(formData('facility_id')) . "' AND users.username = '" . trim(formData('rumple')) . "'");

        $groupname = trim(formData('groupname'));
        if (empty($groupname)) $groupname = 'Default';
        sqlStatement("insert into groups set name = '" . $groupname .
          "', user = '" . trim(formData('rumple')) . "'");

        if (isset($phpgacl_location) && acl_check('admin', 'acl') && trim(formData('rumple'))) {
          // Set the access control group of user
          set_user_aro($_POST['access_group'], trim(formData('rumple')),
            trim(formData('fname')), trim(formData('mname')), trim(formData('lname')));
        }

        $tmp = sqlQuery("SELECT id FROM users WHERE username = '" . trim(formData('rumple')) . "'");
        setUserFacilities($tmp['id']);
      }

    } else {
      $alertmsg .= xl('User','','',' ') . trim(formData('rumple')) . xl('already exists.','',' ');
    }
    if($_POST['access_group']) {
	    $bg_count=count($_POST['access_group']);
      for($i=0;$i<$bg_count;$i++) {
        if($_POST['access_group'][$i] == "Emergency Login") {
          $set_active_msg=1;
        }
      }
    }
  }

  else if ($_POST["mode"] == "new_group") {
    $res = sqlStatement("select distinct name, user from groups");
    for ($iter = 0; $row = sqlFetchArray($res); $iter++)
      $result[$iter] = $row;
    $doit = 1;
    foreach ($result as $iter) {
      if ($doit == 1 && $iter{"name"} == trim(formData('groupname')) && $iter{"user"} == trim(formData('rumple')))
        $doit--;
    }
    if ($doit == 1) {
      sqlStatement("insert into groups set name = '" . trim(formData('groupname')) .
        "', user = '" . trim(formData('rumple')) . "'");
    } else {
      $alertmsg .= "User " . trim(formData('rumple')) .
        " is already a member of group " . trim(formData('groupname')) . ". ";
    }
  }
}

if (isset($_GET["mode"])) {

  /*******************************************************************
  // This is the code to delete a user.  Note that the link which invokes
  // this is commented out.  Somebody must have figured it was too dangerous.
  //
  if ($_GET["mode"] == "delete") {
    $res = sqlStatement("select distinct username, id from users where id = '" .
      $_GET["id"] . "'");
    for ($iter = 0; $row = sqlFetchArray($res); $iter++)
      $result[$iter] = $row;

    // TBD: Before deleting the user, we should check all tables that
    // reference users to make sure this user is not referenced!

    foreach($result as $iter) {
      sqlStatement("delete from groups where user = '" . $iter{"username"} . "'");
    }
    sqlStatement("delete from users where id = '" . $_GET["id"] . "'");
  }
  *******************************************************************/

  if ($_GET["mode"] == "delete_group") {
    $res = sqlStatement("select distinct user from groups where id = '" .
      $_GET["id"] . "'");
    for ($iter = 0; $row = sqlFetchArray($res); $iter++)
      $result[$iter] = $row;
    foreach($result as $iter)
      $un = $iter{"user"};
    $res = sqlStatement("select name, user from groups where user = '$un' " .
      "and id != '" . $_GET["id"] . "'");

    // Remove the user only if they are also in some other group.  I.e. every
    // user must be a member of at least one group.
    if (sqlFetchArray($res) != FALSE) {
      sqlStatement("delete from groups where id = '" . $_GET["id"] . "'");
    } else {
      $alertmsg .= "You must add this user to some other group before " .
        "removing them from this group. ";
    }
  }
}

$form_inactive = empty($_REQUEST['form_inactive']) ? false : true;

?>
<html>
<head>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.easydrag.handler.beta2.js"></script>
<script type="text/javascript">

$(document).ready(function(){

    // fancy box
    enable_modals();

    tabbify();

    // special size for
	$(".iframe_medium").fancybox( {
		'overlayOpacity' : 0.0,
		'showCloseButton' : true,
		'frameHeight' : 480,
		'frameWidth' : 660
	});
	
	$(function(){
		// add drag and drop functionality to fancybox
		$("#fancy_outer").easydrag();
	});
});

</script>
<script language="JavaScript">
function authorized_clicked() {
 var f = document.forms[0];
 f.calendar.disabled = !f.authorized.checked;
 f.calendar.checked  =  f.authorized.checked;
}

function dosort(orderby) {
 var f = document.userlist;
 f.form_orderby.value = orderby;
 top.restoreSession();
 f.submit();
 return false;
}
</script>

</head>
<body class="body_top">

<div>
    <div>
       <table>
	  <tr >
		<td><b><?php xl('User / Groups','e'); ?></b></td>
		<td><a href="usergroup_admin_add.php" class="iframe_medium css_button"><span><?php xl('Add User','e'); ?></span></a>
		</td>
		<td><a href="facility_user.php" class="css_button"><span><?php xl('View Facility Specific User Information','e'); ?></span></a>
		</td>
	  </tr>
	</table>
    </div>
    <div>
        <div>

<form name='userlist' method='post' action='usergroup_admin.php' onsubmit='return top.restoreSession()'>
    <input type='checkbox' name='form_inactive' value='1' onclick='submit()' <?php if ($form_inactive) echo 'checked '; ?>/>
    <span class='text' style = "margin-left:-3px"> <?php xl('Include inactive users','e'); ?> </span>
    <input type="hidden" name="form_orderby" value="<?php echo $form_orderby ?>" />
</form>
<?php
if($set_active_msg == 1){
echo "<font class='alert'>".xl('Emergency Login ACL is chosen. The user is still in active state, please de-activate the user and activate the same when required during emergency situations. Visit Administration->Users for activation or de-activation.')."</font><br>";
}
if ($show_message == 1){
 echo "<font class='alert'>".xl('The following Emergency Login User is activated:')." "."<b>".$_GET['fname']."</b>"."</font><br>";
 echo "<font class='alert'>".xl('Emergency Login activation email will be circulated only if following settings in the interface/globals.php file are configured:')." \$GLOBALS['Emergency_Login_email'], \$GLOBALS['Emergency_Login_email_id']</font>";
}
function create_sortable_header($text,$field,$width)
{
  global $form_orderby;
?>
    <th class="bold" valign="top">
        <a href="#" onclick="return dosort('<?php echo $field; ?>')"
        <?php if ($form_orderby == $field) echo " style='color:#00cc00'"; ?>>
        <?php echo $text; ?></a>
    </th>  
<?php
}
?>
<table cellpadding="1" cellspacing="0" class="showborder" style="width:auto; max-width:100%">
	<tbody><tr height="22" class="showborder_head">
                <?php 
                    create_sortable_header(xlt("Username"),"username","12%"); 
                    create_sortable_header(xlt("Real Name"),"realnamelf","12%"); 
                    create_sortable_header(xlt("Job Description"),"specialty","16%"); 
                    create_sortable_header(xlt("Provider"),"authorized","12%"); 
                    create_sortable_header(xlt("Facility"),"facname","12%"); 
                    create_sortable_header(xlt("Warehouse"),"whname","12%"); 
                    create_sortable_header(xlt("Invoice Pool"),"irnpname","12%"); 
                    create_sortable_header(xlt("Access Groups"),"acl_groups","12%"); 
                ?>

<?php
$query = "SELECT u.*, f.name AS facname, l1.title AS whname, l2.title AS irnpname " .
  "FROM users AS u " .
  "LEFT JOIN facility AS f ON f.id = u.facility_id " .
  "LEFT JOIN list_options AS l1 ON l1.list_id = 'warehouse' AND l1.option_id = u.default_warehouse AND l1.activity = 1 " .
  "LEFT JOIN list_options AS l2 ON l2.list_id = 'irnpool'   AND l2.option_id = u.irnpool AND l2.activity = 1 " .
  "WHERE username != '' ";
if (!$form_inactive) $query .= "AND u.active = '1' ";
$query .= "ORDER BY u.username";
$res = sqlStatement($query);

$result4 = array();
for ($iter = 0; $row = sqlFetchArray($res); $iter++) {
  $acl_groups = '';
  if (isset($phpgacl_location)) {
    $username_acl_groups = acl_get_group_titles($row['username']);
    if (is_array($username_acl_groups)) foreach ($username_acl_groups AS $uagname) {
      if ($acl_groups !== '') $acl_groups .= '<br />';
      $acl_groups .= htmlspecialchars(xl_gacl_group($uagname));
    }
  }
  $row['acl_groups'] = $acl_groups;
  $row['realname'] = $row['fname'] . ' ' . $row['lname'];
  $row['realnamelf'] = $row['lname'] . ', ' . $row['fname'];
  $result4[$iter] = $row;
}

usort($result4, 'userCmp');

function attr_nbsp($val)
{
    return empty($val) ? "&nbsp;" : text($val);
}
foreach ($result4 as $iter) {
  if ($iter{"authorized"}) {
    $iter{"authorized"} = xl('yes');
  } else {
      $iter{"authorized"} = "";
  }
  echo "<tr style='border-bottom: 1px dashed;'>" .
    "<td class='text' valign='top'>" . htmlspecialchars($iter["username"]) .
    "<a href='user_admin.php?id=" . $iter["id"] .
    "' class='iframe_medium' onclick='top.restoreSession()'>(" . xl('Edit') . ")</a></td>" .
    "<td class='text' valign='top' title='" . attr($iter['realnamelf']) . "'>" . attr_nbsp($iter['realname']) . "</td>" .
    "<td class='text' valign='top'>" . attr_nbsp($iter["specialty"]) . "</td>" .
    "<td class='text' valign='top'>" . ($iter["authorized"] ? xl('Yes') : '&nbsp;') . "</td>" .
    "<td class='text' valign='top'>" . attr_nbsp($iter['facname']) . "&nbsp;</td>" .
    "<td class='text' valign='top' nowrap>" . attr_nbsp($iter['whname']) . "&nbsp;</td>" .
    "<td class='text' valign='top' nowrap>" . attr_nbsp($iter['irnpname']) . "&nbsp;</td>" .
    "<td class='text' valign='top' nowrap>" . $iter['acl_groups'] . "</td>";
  // print "<td><!--<a href='usergroup_admin.php?mode=delete&id=" . $iter{"id"} .
  //   "' class='link_submit'>[Delete]</a>--></td>";
  print "</tr>\n";
}
?>
	</tbody></table>
<?php
if (empty($GLOBALS['disable_non_default_groups'])) {
  $res = sqlStatement("select * from groups order by name");
  for ($iter = 0;$row = sqlFetchArray($res);$iter++)
    $result5[$iter] = $row;

  foreach ($result5 as $iter) {
    $grouplist{$iter{"name"}} .= $iter{"user"} .
      "(<a class='link_submit' href='usergroup_admin.php?mode=delete_group&id=" .
      $iter{"id"} . "' onclick='top.restoreSession()'>Remove</a>), ";
  }

  foreach ($grouplist as $groupname => $list) {
    print "<span class='bold'>" . $groupname . "</span><br>\n<span class='text'>" .
      substr($list,0,strlen($list)-2) . "</span><br>\n";
  }
}
?>
        </div>
    </div>
</div>


<script language="JavaScript">
<?php
  if ($alertmsg = trim($alertmsg)) {
    echo "alert('$alertmsg');\n";
  }
?>
</script>

</body>
</html>
