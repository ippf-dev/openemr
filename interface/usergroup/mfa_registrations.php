<?php
/**
 * Multi Factor Authentication Registration Management.
 *
 * Copyright (C) 2018 Rod Roark <rod@sunsetsystems.com>
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
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @link    http://www.open-emr.org
 */

// Disable magic quotes and fake register globals.
$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once('../globals.php');
require_once($GLOBALS['srcdir'] . '/acl.inc');
require_once($GLOBALS['srcdir'] . '/htmlspecialchars.inc.php');
require_once($GLOBALS['srcdir'] . '/formdata.inc.php');

function writeRow($method, $name) {
  echo " <tr><td>";
  echo text($method);
  echo "</td><td>";
  echo text($name);
  echo "</td><td>";
  echo "<input type='button' onclick='delclick(\"" . attr($method) . "\", \"" .
    attr($name) . "\")' value='" . xla('Delete') . "' />";
  echo "</td></tr>\n";
}

$userid = $_SESSION['authId'];

$message = '';
if (!empty($_POST['form_delete_method'])) {
  // Delete the indicated MFA instance.
  if ($_POST['form_delete_method'] == 'Q&A') {
    sqlStatement("DELETE FROM login_mfa_registrations WHERE user_id = ? AND method = ?",
      array($userid, $_POST['form_delete_method']));
  }
  else {
    sqlStatement("DELETE FROM login_mfa_registrations WHERE user_id = ? AND method = ? AND name = ?",
      array($userid, $_POST['form_delete_method'], $_POST['form_delete_name']));
  }
  $message = xl('Delete successful.');
}
?>
<html>

<head>
<title><?php echo xlt('Manage Multi Factor Authentication'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
</style>

<script>

function delclick(mfamethod, mfaname) {
  var f = document.forms[0];
  if (mfamethod == 'Q&A') {
    if (!confirm('<?php echo xls('Delete all of your security questions?'); ?>')) {
      return;
    }
  }
  f.form_delete_method.value = mfamethod;
  f.form_delete_name.value = mfaname;
  top.restoreSession();
  f.submit();
}

function addclick(sel) {
  top.restoreSession();
  if (sel.value) {
    if (sel.value == 'Q&A') {
      window.location.href = 'challenge_questions.php';
    }
    else if (sel.value == 'U2F') {
      // alert('<?php echo xls('Not yet implemented.'); ?>');
      window.location.href = 'mfa_u2f.php?action=reg1';
    }
    else {
      alert('<?php echo xls('Not yet implemented.'); ?>');
    }
  }
  sel.selectedIndex = 0;
}

</script>

</head>

<body class="body_top">
<form method='post' action='mfa_registrations.php' onsubmit='return top.restoreSession()'>

<center>

<h2><?php echo xlt('Manage Multi Factor Authentication'); ?></h2>

<p>
<table border='1'>

<?php
$got_qna = false;
$res = sqlStatement("SELECT name, method FROM login_mfa_registrations WHERE " .
  "user_id = ? ORDER BY method, name", array($userid));
while ($row = sqlFetchArray($res)) {
  if ($row['method'] == 'Q&A') {
    $got_qna = true;
    continue;
  }
  writeRow($row['method'], $row['name']);
}
if ($got_qna) {
  writeRow('Q&A', xl('Security Questions'));
}
?>

</table>
</p>

<p>
<select name='form_add' onchange='addclick(this)'>
<option value=''><?php echo xlt('Add New...'); ?></option>
<option value='U2F' ><?php echo xlt('U2F USB Device'); ?></option>
<option value='Q&A' ><?php echo xlt('Security Questions'); ?></option>
<option value='TOTP' disabled><?php echo xlt('TOTP Key'); ?></option>
</select>
<input type='hidden' name='form_delete_method' value='' />
<input type='hidden' name='form_delete_name' value='' />
</p>

<p style='color:green'><?php echo text($message); ?></p>

</center>

</form>
</body>
</html>
