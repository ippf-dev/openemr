<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/calendar.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/erx_javascript.inc.php");

function userAtt($key) {
  global $iter;
  if (isset($iter[$key])) return $iter[$key];
  return '';
}

$user_id = empty($_GET["id"]) ? 0 : intval($_GET["id"]);

if (!acl_check('admin', 'users')) exit();

$iter = array();
if ($user_id) {
  $res = sqlStatement("SELECT * FROM users WHERE id = ?", array($user_id));
  for ($i = 0; $row = sqlFetchArray($res); ++$i) $result[$i] = $row;
  $iter = $result[0];
}
?>
<html>
<head>

<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="../../library/js/common.js"></script>

<script src="checkpwd_validation.js" type="text/javascript"></script>

<script language="JavaScript">

function checkChange() {
  alert("<?php echo addslashes(xl('If you change e-RX Role for ePrescription, it may affect the ePrescription workflow. If you face any difficulty, contact your ePrescription vendor.'));?>");
}

function submitform() {
  top.restoreSession();
  var flag=0;

  function trimAll(sString) {
    while (sString.substring(0,1) == ' ') {
      sString = sString.substring(1, sString.length);
    }
    while (sString.substring(sString.length-1, sString.length) == ' ') {
      sString = sString.substring(0,sString.length-1);
    }
    return sString;
	}

  if(trimAll(document.getElementById('fname').value) == "") {
    alert("<?php xl('Required field missing: Please enter the First name','e');?>");
    document.getElementById('fname').style.backgroundColor = "red";
    document.getElementById('fname').focus();
    return false;
  }

  if(trimAll(document.getElementById('lname').value) == "") {
    alert("<?php xl('Required field missing: Please enter the Last name','e');?>");
    document.getElementById('lname').style.backgroundColor = "red";
    document.getElementById('lname').focus();
    return false;
  }

  if(document.forms[0].stiltskin.value != "") {
    //Checking for the strong password if the 'secure password' feature is enabled
    if(document.forms[0].secure_pwd.value == 1) {
      var pwdresult = passwordvalidate(document.forms[0].stiltskin.value);
      if(pwdresult == 0) {
        flag = 1;
        alert("<?php
          echo xls('The password must be at least eight characters, and should'); echo '\n';
          echo xls('contain at least three of the four following items:'); echo '\n';
          echo xls('A number'); echo '\n';
          echo xls('A lowercase letter'); echo '\n';
          echo xls('An uppercase letter'); echo '\n';
          echo xls('A special character'); echo '('; echo xls('not a letter or number'); echo ').'; echo '\n';
          echo xls('For example:'); echo ' healthCare@09';
        ?>");
        return false;
      }
    }
  }//If pwd null ends here

<?php if ($user_id) { ?>
  //Request to reset the user password if the user was deactived once the password expired.
  if (document.forms[0].pwd_expires.value != 0 && document.forms[0].stiltskin.value == "") {
    if (document.forms[0].user_type.value != "Emergency Login" &&
      document.forms[0].pre_active.value == 0 && document.forms[0].active.checked == 1 &&
      document.forms[0].grace_time.value != "" &&
      document.forms[0].current_date.value > document.forms[0].grace_time.value)
    {
      flag = 1;
      document.getElementById('error_message').innerHTML="<?php echo xls('Please reset the password.') ?>";
    }
  }
<?php } ?>

  if (document.forms[0].access_group_id) {
    var sel = getSelected(document.forms[0].access_group_id.options);
    for (var item in sel) {
      if (sel[item].value == "Emergency Login") {
        document.forms[0].check_acl.value = 1;
      }
    }
  }

<?php if($GLOBALS['erx_enable']){ ?>
  alertMsg = '';
  f = document.forms[0];
  for (i = 0; i < f.length; i++) {
    if (f[i].type == 'text' && f[i].value) {
<?php if (!$user_id) { ?>
      if(f[i].name == 'rumple') {
        alertMsg += checkLength(f[i].name, f[i].value, 35);
        alertMsg += checkUsername(f[i].name, f[i].value);
      }
<?php } ?>
      if (f[i].name == 'fname' || f[i].name == 'mname' || f[i].name == 'lname') {
        alertMsg += checkLength(f[i].name, f[i].value, 35);
        alertMsg += checkUsername(f[i].name, f[i].value);
      }
      else if(f[i].name == 'taxid') {
        alertMsg += checkLength(f[i].name, f[i].value, 10);
        alertMsg += checkFederalEin(f[i].name, f[i].value);
      }
      else if (f[i].name == 'state_license_number') {
        alertMsg += checkLength(f[i].name, f[i].value, 10);
        alertMsg += checkStateLicenseNumber(f[i].name, f[i].value);
      }
      else if(f[i].name == 'npi') {
        alertMsg += checkLength(f[i].name, f[i].value, 10);
        alertMsg += checkTaxNpiDea(f[i].name, f[i].value);
      }
      else if(f[i].name == 'drugid') {
        alertMsg += checkLength(f[i].name, f[i].value, 30);
        alertMsg += checkAlphaNumeric(f[i].name, f[i].value);
      }
    }
  }
  if(alertMsg) {
    alert(alertMsg);
    return false;
  }
<?php } ?>

  if (flag == 0) {
    document.forms[0].submit();
    parent.$.fn.fancybox.close(); 
  }
}

//Getting the list of selected item in ACL
function getSelected(opt) {
  var selected = new Array();
  var index = 0;
  for (var intLoop = 0; intLoop < opt.length; intLoop++) {
    if (opt[intLoop].selected || opt[intLoop].checked) {
      index = selected.length;
      selected[index] = new Object;
      selected[index].value = opt[intLoop].value;
      selected[index].index = intLoop;
    }
  }
  return selected;
}

function authorized_clicked() {
  var f = document.forms[0];
  f.calendar.disabled = !f.authorized.checked;
  f.calendar.checked  =  f.authorized.checked;
}

</script>

</head>

<body class="body_top">

<table>
 <tr>
  <td><span class="title"><?php echo $user_id ? xlt('Edit User') : xlt('Add User'); ?></span>&nbsp;</td>
  <td>
   <a class="css_button" name='form_save' id='form_save' href='#' onclick='return submitform()'>
    <span><?php echo xlt('Save');?></span> </a>
   <a class="css_button" id='cancel' href='#'><span><?php xl('Cancel','e');?></span></a>
  </td>
 </tr>
</table>

<br />

<FORM NAME="user_form" METHOD="POST" ACTION="usergroup_admin.php" target="_parent" onsubmit='return top.restoreSession()'>

<input type=hidden name="get_admin_id" value="<?php echo $GLOBALS['Emergency_Login_email']; ?>" />
<input type=hidden name="admin_id"     value="<?php echo $GLOBALS['Emergency_Login_email_id']; ?>" />
<input type=hidden name="check_acl"    value="" />

<?php if ($user_id) { ?>
<input type=hidden name="pwd_expires"  value="<?php echo $GLOBALS['password_expiration_days']; ?>" />
<input type=hidden name="pre_active"   value="<?php echo userAtt("active"); ?>" />
<input type=hidden name="exp_date"     value="<?php echo userAtt("pwd_expiration_date"); ?>" />
<?php } ?>

<?php
if ($user_id) {
  //Calculating the grace time 
  $current_date = date("Y-m-d");
  $password_exp = userAtt("pwd_expiration_date");
  if ($password_exp != "0000-00-00") {
    $grace_time1 = date("Y-m-d", strtotime($password_exp . "+" . $GLOBALS['password_grace_time'] . "days"));
  }
  echo "<input type='hidden' name='current_date' value='" . strtotime($current_date) . "' />\n";
  echo "<input type='hidden' name='grace_time'   value='" . strtotime($grace_time1)  . "' />\n";

  // Emergency Login indicator
  $acl_name = acl_get_group_titles(userAtt("username"));
  $bg_name = '';
  $bg_count = count($acl_name);
  for($i = 0; $i < $bg_count; $i++) {
    if($acl_name[$i] == "Emergency Login") $bg_name = $acl_name[$i];
  }
  echo "<input type='hidden' name='user_type' value='" . attr($bg_name) . "' />\n";
}
?>

<TABLE border="0" cellpadding="2" cellspacing="0">

 <!-- username, new password -->
 <TR>
  <TD class='text' style="width:200px;"><?php echo xlt('Username'); ?>: </TD>
  <TD class='text' style="width:280px;"><input type='entry' name='rumple' style="width:150px;"
   value="<?php echo userAtt("username"); ?>"<?php if ($user_id) echo ' disabled'; ?>
   /><?php if (!$user_id) { ?><font class="mandatory">&nbsp;*</font><?php } ?>
  </td>
  <TD class='text' style="width:200px;">
   <?php echo $user_id ? xlt('New Password') : xlt('Password'); ?>: 
  </TD>
  <TD class='text' style="width:280px;">
   <input type='text' name='stiltskin' style="width:150px;" value="" /><font class="mandatory">&nbsp;*</font>
  </td>
 </TR>

 <!-- checkboxes, admin password -->
 <TR>
  <td class='text' colspan="2" nowrap>
   <?php echo xlt('Provider'); ?>:<input type="checkbox" name="authorized"
   onclick="authorized_clicked()"<?php if (userAtt("authorized")) echo " checked"; ?> />
   <?php echo xlt('Calendar'); ?>:<input type="checkbox" name="calendar"<?php
    if (userAtt("calendar")) echo " checked";
    if (!userAtt("authorized")) echo " disabled"; ?> />
   <?php xl('Active','e'); ?>:<input type="checkbox" name="active"<?php
    if (userAtt("active") || !$user_id) echo " checked"; ?> />
  </td>
  <td class='text'><?php xl('Your Password','e'); ?>: </td>
  <td class='text'>
   <input type='password' name='adminPass' style="width:150px;" value="" autocomplete='off'
    /><font class="mandatory">&nbsp;*</font>
  </td>
 </TR>

<?php if (!$user_id && !$GLOBALS['disable_non_default_groups']) { ?>
 <tr>
  <td>
   <span class="text">
    <?php echo xlt('Groupname'); ?>:
   </span>
  </td>
  <td>
   <select name='groupname'>
<?php
  $res = sqlStatement("select distinct name from groups");
  $result2 = array();
  for ($iter = 0;$row = sqlFetchArray($res);$iter++) $result2[$iter] = $row;
  foreach ($result2 as $iter) {
    echo "<option value='" . attr($iter["name"]) . "'";
    echo ">" . text($iter["name"]) . "</option>\n";
  }
?>
   </select>
  </td>
  <td>
   &nbsp;
  </td>
  <td>
   &nbsp;
  </td>
 </tr>
<?php } ?>

 <!-- first name, middle name -->
 <TR>
  <td class='text'><?php echo xlt('First Name'); ?>: </td>
  <td class='text'><input type="entry" name="fname" id="fname" style="width:150px;" value="<?php echo userAtt("fname"); ?>" /><span class="mandatory">&nbsp;*</span></td>
  <td class='text'><?php echo xlt('Middle Name'); ?>: </td>
  <td class='text'><input type="entry" name="mname" style="width:150px;" value="<?php echo userAtt("mname"); ?>" /></td>
 </TR>

 <!-- last name; invoice refno pool optional -->
 <TR>
  <td class='text'><?php xl('Last Name','e'); ?>: </td>
  <td class='text'><input type="entry" name="lname" id="lname" style="width:150px;" value="<?php echo userAtt("lname"); ?>" /><span class="mandatory">&nbsp;*</span></td>
<?php if ($GLOBALS['inhouse_pharmacy']) { ?>
  <td class='text'><?php echo xlt('Invoice Refno Pool'); ?>: </td>
  <td class='text'>
<?php
  echo generate_select_list('irnpool', 'irnpool', userAtt('irnpool'),
    xl('Invoice reference number pool, if used'));
?>
  </td>
<?php } else { ?>
  <td class="text" colspan="2">&nbsp;</td>
<?php } ?>
 </tr>

<!-- default facility; warehouse optional -->
 <tr>
  <td><span class="text"><?php xl('Default Facility','e'); ?>: </span></td>
  <td>
   <select name="facility_id" style="width:150px;" >
<?php
$fres = sqlStatement("select * from facility where service_location != 0 order by name");
if ($fres) {
  for ($iter2 = 0; $frow = sqlFetchArray($fres); $iter2++) $result[$iter2] = $frow;
  foreach($result as $iter2) {
?>
    <option value="<?php echo $iter2['id']; ?>" <?php if (userAtt('facility_id') == $iter2['id']) echo "selected"; ?>><?php echo htmlspecialchars($iter2['name']); ?></option>
<?php
  }
}
?>
   </select>
  </td>
<?php if ($GLOBALS['inhouse_pharmacy']) { ?>
  <td class="text"><?php xl('Default Warehouse','e'); ?>: </td>
  <td class='text'>
<?php
  echo generate_select_list('default_warehouse', 'warehouse', userAtt('default_warehouse'), '');
?>
  </td>
<?php } else { ?>
  <td class="text" colspan="2">&nbsp;</td>
<?php } ?>
 </tr>

<!-- facility and warehouse restrictions, optional -->
<?php if ($GLOBALS['restrict_user_facility']) { ?>
 <tr title="<?php echo xla('If nothing is selected here then all are permitted.'); ?>">
  <td><span class="text"><?php echo $GLOBALS['inhouse_pharmacy'] ?
    xlt('Facility and warehouse permissions') : xlt('Facility permissions'); ?>:</td>
  <td colspan="3">
   <select name="schedule_facility[]" multiple style="width:490px;">
<?php
  $userFacilities = getUserFacilities($user_id);
  $ufid = array();
  foreach ($userFacilities as $uf) $ufid[] = $uf['id'];
  $fres = sqlStatement("select * from facility where service_location != 0 order by name");
  if ($fres) {
    while($frow = sqlFetchArray($fres)) {
      // Get the warehouses that are linked to this user and facility.
      $whids = getUserFacWH($user_id, $frow['id']);
      // Generate an option for just the facility with no warehouse restriction.
      echo "    <option";
      // if (empty($whids) && (in_array($frow['id'], $ufid) || $frow['id'] == $iter['facility_id'])) {
      if (empty($whids) && in_array($frow['id'], $ufid)) {
        echo ' selected';
      }
      echo " value='" . $frow['id'] . "'>" . text($frow['name']) . "</option>\n";
      // Then generate an option for each of the facility's warehouses.
      // Does not apply if the site does not use inventory.
      if ($GLOBALS['inhouse_pharmacy']) {
        $lres = sqlStatement("SELECT option_id, title FROM list_options WHERE " .
          "list_id = ? AND option_value = ? AND activity = 1 ORDER BY seq, title",
          array('warehouse', $frow['id']));
        while ($lrow = sqlFetchArray($lres)) {
          echo "    <option";
          if (in_array($lrow['option_id'], $whids)) echo ' selected';
          echo " value='" . $frow['id'] . "/" . attr($lrow['option_id']) . "'>&nbsp;&nbsp;&nbsp;" .
            text(xl_list_label($lrow['title'])) . "</option>\n";
        }
      }
    }
  }
?>
   </select>
  </td>
 </tr>
<?php } ?>

 <!-- tax id, drug id -->
 <TR>
  <TD><span class="text"><?php xl('Federal Tax ID','e'); ?>: </span></TD>
  <TD><input type="text" name="taxid" style="width:150px;"  value="<?php echo userAtt("federaltaxid") ?>" /></td>
  <TD><span class="text"><?php xl('Federal Drug ID','e'); ?>: </span></TD>
  <TD><input type="text" name="drugid" style="width:150px;"  value="<?php echo userAtt("federaldrugid") ?>" /></td>
 </TR>

 <!-- upin, see auth -->
 <tr>
  <td><span class="text"><?php xl('UPIN','e'); ?>: </span></td>
  <td><input type="text" name="upin" style="width:150px;" value="<?php echo userAtt("upin")?>" /></td>
  <td class='text'><?php xl('See Authorizations','e'); ?>: </td>
  <td>
   <select name="see_auth" style="width:150px;">
<?php
foreach (array(1 => xl('None'), 2 => xl('Only Mine'), 3 => xl('All')) as $key => $value) {
  echo " <option value='$key'";
  if ($key == userAtt('see_auth')) echo " selected";
  echo ">$value</option>\n";
}
?>
   </select>
  </td>
 </tr>

 <!-- npi, job description -->
 <tr>
  <td><span class="text"><?php xl('NPI','e'); ?>: </span></td>
  <td><input type="text" name="npi" style="width:150px;"  value="<?php echo userAtt("npi") ?>" /></td>
  <td><span class="text"><?php xl('Job Description','e'); ?>: </span></td>
  <td><input type="text" name="job" style="width:150px;"  value="<?php echo userAtt("specialty") ?>" /></td>
 </tr>

<?php if (!empty($GLOBALS['ssi']['rh'])) { ?>
 <!-- relay health id, optional -->
 <tr>
  <td><span class="text"><?php xl('Relay Health ID', 'e'); ?>: </span></td>
  <td><input type="password" name="ssi_relayhealth" style="width:150px;"  value="<?php echo userAtt("ssi_relayhealth"); ?>" /></td>
 </tr>
<?php } ?>

<!-- taxonomy, calendar ui -->
 <tr>
  <td><span class="text"><?php xl('Taxonomy','e'); ?>: </span></td>
  <td><input type="text" name="taxonomy" style="width:150px;"  value="<?php echo userAtt("taxonomy") ?>" /></td>
  <td><span class="text"><?php xl('Calendar UI','e'); ?>: </span></td>
  <td>
   <select name="cal_ui" style="width:150px;">
<?php
foreach (array(3 => xl('Outlook'), 1 => xl('Original'), 2 => xl('Fancy')) as $key => $value) {
  echo "    <option value='$key'";
  if ($key == userAtt('cal_ui')) echo " selected";
  echo ">$value</option>\n";
}
?>
   </select>
  </td>
 </tr>

 <!-- state license, newcrop role -->
 <tr>
  <td><span class="text"><?php xl('State License Number','e'); ?>: </span></td>
  <td><input type="text" name="state_license_number" style="width:150px;"  value="<?php echo userAtt("state_license_number") ?>" /></td>
  <td class='text'><?php xl('NewCrop eRX Role','e'); ?>:</td>
  <td>
   <?php echo generate_select_list("erxrole", "newcrop_erx_role", userAtt('newcrop_user_role'),'','--Select Role--','','','',array('style'=>'width:150px')); ?>
  </td>
 </tr>

<?php if (isset($phpgacl_location) && acl_check('admin', 'acl')) { ?>

 <!-- access control group, additional info, optional but not really -->
 <tr>
  <td class='text'><?php xl('Access Control','e'); ?>:</td>
  <td>
   <select id="access_group_id" name="access_group[]" multiple style="width:150px;">
<?php
  $list_acl_groups = acl_get_group_title_list();
  $username_acl_groups = acl_get_group_titles(userAtt("username"));
  foreach ($list_acl_groups as $value) {
    if ($username_acl_groups && in_array($value, $username_acl_groups)) {
      // Modified 6-2009 by BM - Translate group name if applicable
      echo " <option value='$value' selected>" . xl_gacl_group($value) . "</option>\n";
    }
    else {
      // Modified 6-2009 by BM - Translate group name if applicable
      echo " <option value='$value'>" . xl_gacl_group($value) . "</option>\n";
    }
  }
?>
   </select>
  </td>
  <td><span class=text><?php xl('Additional Info','e'); ?>:</span></td>
  <td><textarea style="width:150px;" name="comments" wrap=auto rows=4 cols=25><?php echo userAtt("info"); ?></textarea></td>
 </tr>

 <tr height="20" valign="bottom">
  <td colspan="4" class="text">
   <font class="mandatory">*</font> <?php xl('You must enter your own password to change user passwords. Leave blank to keep password unchanged.','e'); ?>
   <!--
   Display red alert if entered password matched one of last three passwords
   Display red alert if user password was expired and the user was inactivated previously
   -->
   <div class="redtext" id="error_message">&nbsp;</div>
  </td>
 </tr>

<?php } ?>

</table>

<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?php echo $user_id; ?>" />
<INPUT TYPE="HIDDEN" NAME="mode" VALUE="<?php echo $user_id ? 'update' : 'new_user'; ?>" />
<INPUT TYPE="HIDDEN" NAME="privatemode" VALUE="user_admin" />
<INPUT TYPE="HIDDEN" NAME="secure_pwd" VALUE="<?php echo $GLOBALS['secure_password']; ?>" />

</FORM>

<?php if (!$user_id) { ?>

<form name='new_group' method='post' action='usergroup_admin.php'
  onsubmit='return top.restoreSession()'>
<input type='hidden' name='mode' value='new_group' />
<table border='0' cellpadding='2' cellspacing='0'<?php
  if ($GLOBALS['disable_non_default_groups']) echo " style='display:none'"; ?>>
 <tr>
  <td valign='top'>
   <br />
   <span class='bold'><?php echo xlt('New Group'); ?>:</span>
  </td>
  <td>
   <span class='text'><?php echo xlt('Groupname'); ?>: </span>
   <input type='entry' name='groupname' size='10' />
   &nbsp;&nbsp;&nbsp;
   <span class='text'><?php echo xlt('Initial User'); ?>: </span>
   <select name='rumple'>
<?php
$res = sqlStatement("select distinct username from users where username != ''");
for ($iter = 0;$row = sqlFetchArray($res);$iter++) $result[$iter] = $row;
foreach ($result as $iter) {
  print "    <option value='" . $iter{"username"} . "'>" . $iter{"username"} . "</option>\n";
}
?>
   </select>
   &nbsp;&nbsp;&nbsp;
   <input type='submit' value='<?php echo xla('Save'); ?>' />
  </td>
 </tr>
</table>
</form>

<form name='new_group' method='post' action='usergroup_admin.php'
 onsubmit='return top.restoreSession()'>
<input type='hidden' name='mode' value='new_group'>
<table border='0' cellpadding='2' cellspacing='0'<?php
  if ($GLOBALS['disable_non_default_groups']) echo " style='display:none'"; ?>>
 <tr>
  <td valign='top'>
   <span class='bold'><?php echo xlt('Add User To Group'); ?>:</span>
  </td>
  <td>
   <span class='text'><?php echo xlt('User'); ?>: </span>
   <select name='rumple'>
<?php
$res = sqlStatement("select distinct username from users where username != ''");
for ($iter = 0; $row = sqlFetchArray($res); $iter++) $result3[$iter] = $row;
foreach ($result3 as $iter) {
  print "    <option value='" . $iter{"username"} . "'>" . $iter{"username"} . "</option>\n";
}
?>
   </select>
   &nbsp;&nbsp;&nbsp;
   <span class="text"><?php echo xlt('Groupname'); ?>: </span>
   <select name='groupname'>
<?php
$res = sqlStatement("select distinct name from groups");
$result2 = array();
for ($iter = 0; $row = sqlFetchArray($res); $iter++)
  $result2[$iter] = $row;
foreach ($result2 as $iter) {
  print "    <option value='" . $iter{"name"} . "'>" . $iter{"name"} . "</option>\n";
}
?>
   </select>
   &nbsp;&nbsp;&nbsp;
   <input type='submit' value='<?php echo xla('Add User To Group'); ?>' />
  </td>
 </tr>
</table>
</form>

<?php
if (empty($GLOBALS['disable_non_default_groups'])) {
  $res = sqlStatement("select * from groups order by name");
  for ($iter = 0; $row = sqlFetchArray($res); $iter++) $result5[$iter] = $row;
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

<?php } // end if (!$user_id) ?>

<script language="JavaScript">
$(document).ready(function(){
  $("#cancel").click(function() {
    parent.$.fn.fancybox.close();
  });
});
</script>

</BODY>
</HTML>
