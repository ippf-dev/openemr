<?php
/**
 * FIDO U2F Support Module
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
require_once($GLOBALS['srcdir'] . '/options.inc.php');
require_once($GLOBALS['srcdir'] . '/U2F.php');

$scheme = isset($_SERVER['HTTPS']) ? "https://" : "http://";
$appId = $scheme . $_SERVER['HTTP_HOST'];
$u2f = new u2flib_server\U2F($appId);

$userid = $_SESSION['authId'];
$action = $_REQUEST['action'];
?>
<html>
<head>
<title><?php echo xlt('U2F'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
</style>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/u2f-api.js"></script>
<script>

function doregister() {
  var f = document.forms[0];
  if (f.form_name.value.trim() == '') {
    alert('<?php echo xla("Please enter a name for this key."); ?>');
    return;
  }
  var request = JSON.parse(f.form_request.value);
  u2f.register(
    '<?php echo addslashes($appId); ?>',
    [request],
    [],
    function(data) {
      if(data.errorCode && data.errorCode != 0) {
        alert('<?php echo xla("Registration failed with error"); ?> ' + data.errorCode);
        return;
      }
      f.form_registration.value = JSON.stringify(data);
      f.action.value = 'reg2';
      top.restoreSession();
      f.submit();
    },
    60
  );
}

function docancel() {
  window.location.href = 'mfa_registrations.php';
}

</script>
</head>
<body class="body_top">
<form method='post' action='mfa_u2f.php' onsubmit='return top.restoreSession()'>

<?php

///////////////////////////////////////////////////////////////////////

if ($action == 'reg1') {
  list ($request, $signs) = $u2f->getRegisterData();
  echo "<p>\n";
  echo xlt('This will register a new U2F USB key. A suitable web browser is required.');
  echo " " . xlt('Insert your key into a USB port and click the Register button below.');
  echo " " . xlt('Then press the flashing button on your key within 1 minute to complete registration.') . "</p>\n";
  echo "</p>\n";
  echo "<center><p>\n";
  echo xlt('Please give this key a name') . ': ';
  echo "<input type='text' name='form_name' value='' size='16' />\n";
  echo "<input type='button' value='" . xla('Register') . "' onclick='doregister()' />\n";
  echo "<input type='button' value='" . xla('Cancel'  ) . "' onclick='docancel()'   />\n";
  echo "<input type='hidden' name='form_request' value='" . attr(json_encode($request)) . "' />\n";
  echo "<input type='hidden' name='form_signs'   value='" . attr(json_encode($signs  )) . "' />\n";
  echo "<input type='hidden' name='form_registration' value='' />\n";
  echo "</p></center>\n";
}

///////////////////////////////////////////////////////////////////////

else if ($action == 'reg2') {
  try {
    $data = $u2f->doRegister(json_decode($_POST['form_request']), json_decode($_POST['form_registration']));
  } catch(u2flib_server\Error $e) {
    die(xlt('Registration error') . ': ' . $e->getMessage());
  }

  // echo "form_request: "       . $_POST['form_request']      . "<br />\n"; // debugging
  // echo "form_registration: "  . $_POST['form_registration'] . "<br />\n"; // debugging
  // echo "doRegister returns: " . json_encode($data)          . "<br />\n"; // debugging

  sqlStatement("INSERT INTO login_mfa_registrations " .
    "(`user_id`, `method`, `name`, `var1`, `var2`) VALUES " .
    "(?, 'U2F', ?, ?, ?)",
    array($userid, $_POST['form_name'], json_encode($data), ''));

  echo "<script>window.location.href = 'mfa_registrations.php';</script>";
}

///////////////////////////////////////////////////////////////////////

?>

<input type='hidden' name='action' value='' />
</form>
</body>
</html>
