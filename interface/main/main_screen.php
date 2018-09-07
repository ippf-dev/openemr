<?php
/**
 * The outside frame that holds all of the OpenEMR User Interface.
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
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

/* Include our required headers */
require_once('../globals.php');
require_once($GLOBALS['srcdir'] . '/formdata.inc.php');
require_once($GLOBALS['srcdir'] . '/U2F.php');

///////////////////////////////////////////////////////////////////////
// Shared functions to support MFA.
///////////////////////////////////////////////////////////////////////

function posted_to_hidden($name) {
  if (isset($_POST[$name])) {
    echo "<input type='hidden' name='" . attr($name) . "' value='" . attr($_POST[$name]) . "' />\n";
  }
}

function generate_html_start($title) {
  global $appId, $css_header;
?>
<html>
<head>
<link rel=stylesheet href="<?php echo $css_header; ?>" type="text/css">
<title><?php echo text($title); ?></title>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/u2f-api.js"></script>
<script>
function doAuth() {
  var f = document.forms[0];
  var requests = JSON.parse(f.form_requests.value);
  // The server's getAuthenticateData() repeats the same challenge in all requests.
  var challenge = requests[0].challenge;
  var registeredKeys = new Array();
  for (var i = 0; i < requests.length; ++i) {
    registeredKeys[i] = {"version": requests[i].version, "keyHandle": requests[i].keyHandle};
  }
  u2f.sign(
    '<?php echo addslashes($appId); ?>',
    challenge,
    registeredKeys,
    function(data) {
      if(data.errorCode && data.errorCode != 0) {
        alert('<?php echo xla("Key access failed with error"); ?> ' + data.errorCode);
        return;
      }
      f.form_response.value = JSON.stringify(data);
      f.submit();
    },
    60
  );
}
</script>
</head>
<body class='body_top'>
<center>
<h2><?php echo text($title); ?></h2>
<form method="post"
 action="main_screen.php?auth=login&site=<?php echo $_GET['site']; ?>"
 target="_top" name="challenge_form">
<?php
  posted_to_hidden('new_login_session_management');
  posted_to_hidden('authProvider');
  posted_to_hidden('languageChoice');
  posted_to_hidden('authUser');
  posted_to_hidden('clearPass');
}

function generate_html_end() {
  echo "</form></center></body></html>\n";
  session_unset();
  session_destroy();
  unset($_COOKIE[session_name()]);
  return 0;
}

function is_time_for_challenge() {
  if ($GLOBALS['gbl_days_between_challenges'] == 0) {
    return true;
  }
  else {
    $usrow = sqlQuery("SELECT last_challenge_response, NOW() AS curdate FROM users_secure WHERE id = ?",
      array($_SESSION['authId']));
    if (empty($usrow['last_challenge_response']) ||
      (strtotime($usrow['curdate']) - strtotime($usrow['last_challenge_response'])) >
      (86400 * $GLOBALS['gbl_days_between_challenges'])
    ) {
      return true;
    }
  }
  return false;
}

$errormsg = '';

///////////////////////////////////////////////////////////////////////
// Begin code to support U2F.
///////////////////////////////////////////////////////////////////////

$regs = array();          // for mapping device handles to their names
$registrations = array(); // the array of stored registration objects
$res1 = sqlStatement("SELECT a.name, a.var1 FROM login_mfa_registrations AS a " .
  "WHERE a.user_id = ? AND a.method = 'U2F' ORDER BY a.name",
  array($_SESSION['authId']));
while ($row1 = sqlFetchArray($res1)) {
  $regobj = json_decode($row1['var1']);
  $regs[json_encode($regobj->keyHandle)] = $row1['name'];
  $registrations[] = $regobj;
}
if (!empty($registrations)) {
  // There is at least one U2F key registered so we have to request or verify key data.
  $scheme = isset($_SERVER['HTTPS']) ? "https://" : "http://";
  $appId = $scheme . $_SERVER['HTTP_HOST'];
  $u2f = new u2flib_server\U2F($appId);
  $userid = $_SESSION['authId'];
  $form_response = empty($_POST['form_response']) ? '' : $_POST['form_response'];
  if ($form_response) {
    // We have key data, check if it matches what was registered.
    $tmprow = sqlQuery("SELECT login_work_area FROM users_secure WHERE id = ?", array($userid));
    try {
      $registration = $u2f->doAuthenticate(
        json_decode($tmprow['login_work_area']), // these are the original challenge requests
        $registrations,
        json_decode($_POST['form_response'])
      );
      // error_log('u2f->doAuthenticate() returns ' . json_encode($registration)); // debugging
      // Stored registration data needs to be updated because the usage count has changed.
      // We have to use the matching registered key.
      $strhandle = json_encode($registration->keyHandle);
      if (isset($regs[$strhandle])) {
        sqlStatement("UPDATE login_mfa_registrations SET `var1` = ? WHERE " .
          "`user_id` = ? AND `method` = 'U2F' AND `name` = ?",
          array(json_encode($registration), $userid, $regs[$strhandle]));
      }
      else {
        error_log("Unexpected keyHandle returned from doAuthenticate(): '$strhandle'");
      }
      // Keep track of when questions were last answered correctly.
      sqlStatement("UPDATE users_secure SET last_challenge_response = NOW() WHERE id = ?",
        array($_SESSION['authId']));
    } catch(u2flib_server\Error $e) {
      // Authentication failed so build the U2F form again.
      $form_response = '';
      $errormsg = xl('Authentication error') . ": " . $e->getMessage();
    }
  }
  if (!$form_response && is_time_for_challenge()) {
    // There is no key data yet or authentication failed, so we need to solicit it.
    $requests = json_encode($u2f->getAuthenticateData($registrations));
    // Persist the challenge also in the database because the browser is untrusted.
    sqlStatement("UPDATE users_secure SET login_work_area = ? WHERE id = ?", array($requests, $userid));
    generate_html_start(xl('U2F Key Verification'));
    echo "<p>\n";
    echo xlt('Insert your key into a USB port and click the Authenticate button below.');
    echo " " . xlt('Then press the flashing button on your key within 1 minute.') . "</p>\n";
    echo "</p><p>\n";
    echo "<input type='button' value='" . xla('Authenticate') . "' onclick='doAuth()' />\n";
    echo "<input type='hidden' name='form_requests' value='" . attr($requests) . "' />\n";
    echo "<input type='hidden' name='form_response' value='' />\n";
    echo "</p>\n";
    if ($errormsg) {
      echo "<p style='color:red;font-weight:bold'>" . text($errormsg) . "</p>\n";
    }
    exit(generate_html_end());
  }
}

///////////////////////////////////////////////////////////////////////
// End of U2F logic.
///////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////
// Begin code to support challenge questions.
// When challenge questions are required after login, the idea is to
// build a form that asks the questions and also duplicates the data
// from the login form and thus causes login to be repeated.
///////////////////////////////////////////////////////////////////////

if (empty($registrations) && !empty($GLOBALS['gbl_num_challenge_questions_stored'])) {
  $tmprow = sqlQuery("SELECT COUNT(*) AS count FROM login_mfa_registrations AS a " .
    "JOIN list_options AS l ON l.list_id = 'login_security_questions' AND " .
    "l.option_id = a.var1 " .
    "WHERE a.user_id = ? AND a.method = 'Q&A'", array($_SESSION['authId']));
  $num_answers = empty($tmprow['count']) ? 0 : intval($tmprow['count']);
  if ($num_answers) {
    $need_challenge = 0;
    if (is_array($_POST['form_answer'])) {
      // There are challenge answers, see if they are defined and correct.
      $tmppat = '/[^A-Za-z0-9]/';
      $count = 0;
      foreach ($_POST['form_answer'] as $question_id => $answer) {
        if (empty($question_id)) continue;
        $arow = sqlQuery("SELECT a.var2 FROM login_mfa_registrations AS a " .
          "JOIN list_options AS l ON l.list_id = 'login_security_questions' AND " .
          "l.option_id = a.var1 " .
          "WHERE a.user_id = ? AND a.var1 = ? AND a.method = 'Q&A'",
          array($_SESSION['authId'], $question_id));
        // echo ("User=" . $_SESSION['authId'] . " QID=$question_id Answer=$answer Stored=" . $arow['answer'] . "<br>\n"); // debugging
        if (empty($arow)) continue;
        if (strtolower(preg_replace($tmppat, '', $arow['var2'])) != strtolower(preg_replace($tmppat, '', $answer))) {
          $need_challenge = 2;
        }
        ++$count;
      }
      // die("User=" . $_SESSION['authId']); // debugging
      if ($count < min($GLOBALS['gbl_num_challenge_questions_asked'], $num_answers)) {
        $need_challenge = 2;
      }
      if (!$need_challenge) {
        // Keep track of when questions were last answered correctly.
        sqlStatement("UPDATE users_secure SET last_challenge_response = NOW() WHERE id = ?",
          array($_SESSION['authId']));
      }
    }
    else {
      // Check if it's time for challenge questions.
      if (!empty($GLOBALS['gbl_num_challenge_questions_stored'])) {
        if (is_time_for_challenge()) $need_challenge = 1;
      }
    }
    if ($need_challenge) {
      generate_html_start(xl('Login Security'));
      echo "<table>\n";
      $qres = sqlStatement("SELECT a.var1, l.title FROM login_mfa_registrations AS a " .
        "LEFT JOIN list_options AS l ON l.list_id = 'login_security_questions' AND " .
        "l.option_id = a.var1 " .
        "WHERE a.user_id = ? " .
        "ORDER BY a.last_challenge, (a.name + 0) " .
        "LIMIT " . intval($GLOBALS['gbl_num_challenge_questions_asked']),
        array($_SESSION['authId']));
      while ($qrow = sqlFetchArray($qres)) {
        $title = empty($qrow['title']) ? $qrow['var1'] : $qrow['title'];
        echo "<tr><td>" . text($title) . "&nbsp;</td>";
        echo "<td><input type='text' name='form_answer[" . attr($qrow['var1']) . "]' " .
          "value='" . attr($qrow['var2']) . "' /></td></tr>\n";
        // Update last_asked timestamp.
        sqlStatement("UPDATE login_mfa_registrations SET last_challenge = NOW() WHERE " .
          "user_id = ? AND var1 = ?",
          array($_SESSION['authId'], $qrow['var1']));
      }
      echo "</table>\n";
      echo "<p><input type='submit' value='" . xla('Finish Login') . "' /></p>\n";
      exit(generate_html_end());
    }
  }
}

///////////////////////////////////////////////////////////////////////
// End of challenge questions logic.
///////////////////////////////////////////////////////////////////////

// Creates a new session id when load this outer frame
// (allows creations of separate OpenEMR frames to view patients concurrently
//  on different browser frame/windows)
// This session id is used below in the restoreSession.php include to create a
// session cookie for this specific OpenEMR instance that is then maintained
// within the OpenEMR instance by calling top.restoreSession() whenever
// refreshing or starting a new script.
if (isset($_POST['new_login_session_management'])) {
  // This is a new login, so create a new session id and remove the old session
  session_regenerate_id(true);
}
else {
  // This is not a new login, so create a new session id and do NOT remove the old session
  session_regenerate_id(false);
}

$_SESSION["encounter"] = '';

// Fetch the password expiration date
$is_expired=false;
if($GLOBALS['password_expiration_days'] != 0){
  $is_expired=false;
  $q= (isset($_POST['authUser'])) ? $_POST['authUser'] : '';
  $result = sqlStatement("select pwd_expiration_date from users where username = ?", array($q));
  $current_date = date('Y-m-d');
  $pwd_expires_date = $current_date;
  if($row = sqlFetchArray($result)) {
    $pwd_expires_date = $row['pwd_expiration_date'];
  }

  // Display the password expiration message (starting from 7 days before the password gets expired)
  $pwd_alert_date = date('Y-m-d', strtotime($pwd_expires_date . '-7 days'));

  if (strtotime($pwd_alert_date) != '' &&
      strtotime($current_date) >= strtotime($pwd_alert_date) &&
      (!isset($_SESSION['expiration_msg'])
      or $_SESSION['expiration_msg'] == 0)) {
    $is_expired = true;
    $_SESSION['expiration_msg'] = 1; // only show the expired message once
  }
}

if ($is_expired) {
  //display the php file containing the password expiration message.
  $frame1url = "pwd_expires_alert.php";
}
else if (!empty($_POST['patientID'])) {
  $patientID = 0 + $_POST['patientID'];
  if (empty($_POST['encounterID'])) {
    // Open patient summary screen (without a specific encounter)
    $frame1url = "../patient_file/summary/demographics.php?set_pid=".attr($patientID);
  }
  else {
    // Open patient summary screen with a specific encounter
    $encounterID = 0 + $_POST['encounterID'];
    $frame1url = "../patient_file/summary/demographics.php?set_pid=".attr($patientID)."&set_encounterid=".attr($encounterID);
  }
}
else if ($GLOBALS['athletic_team']) {
  $frame1url = "../reports/players_report.php?embed=1";
}
else if (isset($_GET['mode']) && $_GET['mode'] == "loadcalendar") {
  $frame1url = "calendar/index.php?pid=" . attr($_GET['pid']);
  if (isset($_GET['date'])) $frame1url .= "&date=" . attr($_GET['date']);
}
else if ($GLOBALS['concurrent_layout']) {
  // new layout
  if (!empty($_POST['authUser'])) {
    // globals.php did not merge in user settings because the session was cleared upon login,
    // so do that for the one that matters here.
    $tmp = sqlQuery("SELECT us.setting_value FROM users, user_settings AS us WHERE " .
      "users.username = ? AND us.setting_user = users.id AND us.setting_label = ?",
      array($_POST['authUser'], 'global:default_top_pane'));
    if (!empty($tmp['setting_value'])) {
      $GLOBALS['default_top_pane'] = $tmp['setting_value'];
    }
  }
  if (!empty($GLOBALS['default_top_pane'])) {
    $frame1url = attr($GLOBALS['default_top_pane']);
  } else {
    $frame1url = "main_info.php";
  }
}
else {
  // old layout
  $frame1url = "main.php?mode=" . attr($_GET['mode']);
}

$nav_area_width = $GLOBALS['athletic_team'] ? '230' : '130';
if (!empty($GLOBALS['gbl_nav_area_width'])) $nav_area_width = $GLOBALS['gbl_nav_area_width'];
?>
<html>
<head>
<title>
<?php echo text($openemr_name) ?>
</title>
<script type="text/javascript" src="../../library/topdialog.js"></script>

<script language='JavaScript'>
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

// This flag indicates if another window or frame is trying to reload the login
// page to this top-level window.  It is set by javascript returned by auth.inc
// and is checked by handlers of beforeunload events.
var timed_out = false;

// This counts the number of frames that have reported themselves as loaded.
// Currently only left_nav and Title do this, so the maximum will be 2.
// This is used to determine when those frames are all loaded.
var loadedFrameCount = 0;

function allFramesLoaded() {
 // Change this number if more frames participate in reporting.
 return loadedFrameCount >= 2;
}

// Call this from a .js file for string translation.
function errorMessage(key) {
  if (key == 'date bad char'  ) return '<?php echo xla('Invalid character in date!'); ?>';
  if (key == 'date incomplete') return '<?php echo xla('Date entry is incomplete! Try again?'); ?>';
  if (key == 'date invalid'   ) return '<?php echo xla('Year, month or day is not valid! Try again?'); ?>';
  return key;
}

</script>

</head>

<?php
/*
 * for RTL layout we need to change order of frames in framesets
 */
$lang_dir = $_SESSION['language_direction'];

$sidebar_tpl = "<frameset rows='*,0' frameborder='0' border='0' framespacing='0'>
   <frame src='left_nav.php' name='left_nav' />
   <frame src='daemon_frame.php' name='Daemon' scrolling='no' frameborder='0'
    border='0' framespacing='0' />
  </frameset>";
        
$main_tpl = empty($GLOBALS['athletic_team']) ? "<frameset rows='60%,*' id='fsright' bordercolor='#999999' frameborder='1'>" : "<frameset rows='100%,*' id='fsright' bordercolor='#999999' frameborder='1'>";
$main_tpl .= "<frame src='". $frame1url ."' name='RTop' scrolling='auto' />
   <frame src='messages/messages.php?form_active=1' name='RBot' scrolling='auto' /></frameset>";
// Please keep in mind that border (mozilla) and framespacing (ie) are the
// same thing. use both.
// frameborder specifies a 3d look, not whether there are borders.

if ($GLOBALS['concurrent_layout']) {
  // start new layout
  if (empty($GLOBALS['gbl_tall_nav_area'])) {
    // not tall nav area ?>
<frameset rows='<?php echo attr($GLOBALS['titleBarHeight']) + 5 ?>,*' frameborder='1' border='1' framespacing='1' onunload='imclosing()'>
 <frame src='main_title.php' name='Title' scrolling='no' frameborder='1' noresize />
 <?php if($lang_dir != 'rtl'){ ?>
 
     <frameset cols='<?php echo attr($nav_area_width) . ',*'; ?>' id='fsbody' frameborder='1' border='4' framespacing='4'>
     <?php echo $sidebar_tpl ?>
     <?php echo $main_tpl ?>
     </frameset>
 
 <?php }else{ ?>
 
     <frameset cols='<?php echo  '*,' . attr($nav_area_width); ?>' id='fsbody' frameborder='1' border='4' framespacing='4'>
     <?php echo $main_tpl ?>
     <?php echo $sidebar_tpl ?>
     </frameset>
 
 <?php }?>
   
 </frameset>
</frameset>

<?php } else { // use tall nav area ?>

<frameset cols='<?php echo attr($nav_area_width); ?>,*' id='fsbody' frameborder='1' border='4' framespacing='4' onunload='imclosing()'>
 <frameset rows='*,0' frameborder='0' border='0' framespacing='0'>
  <frame src='left_nav.php' name='left_nav' />
  <frame src='daemon_frame.php' name='Daemon' scrolling='no' frameborder='0'
   border='0' framespacing='0' />
 </frameset>
 <frameset rows='<?php echo attr($GLOBALS['titleBarHeight']) + 5 ?>,*' frameborder='1' border='1' framespacing='1'>
  <frame src='main_title.php' name='Title' scrolling='no' frameborder='1' />
<?php if (empty($GLOBALS['athletic_team'])) { ?>
  <frameset rows='60%,*' id='fsright' bordercolor='#999999' frameborder='1' border='4' framespacing='4'>
<?php } else { ?>
  <frameset rows='100%,*' id='fsright' bordercolor='#999999' frameborder='1' border='4' framespacing='4'>
<?php } ?>
   <frame src='<?php echo $frame1url ?>' name='RTop' scrolling='auto' />
   <frame src='messages/messages.php?form_active=1' name='RBot' scrolling='auto' />
  </frameset>
 </frameset>
</frameset>

<?php } // end tall nav area ?>

<?php } else { // start old layout ?>

</head>
<frameset rows="<?php echo attr($GLOBALS[navBarHeight]).",".attr($GLOBALS[titleBarHeight]) ?>,*"
  cols="*" frameborder="no" border="0" framespacing="0"
  onunload="imclosing()">
  <frame src="main_navigation.php" name="Navigation" scrolling="no" noresize frameborder="no">
  <frame src="main_title.php" name="Title" scrolling="no" noresize frameborder="no">
  <frame src='<?php echo $frame1url ?>' name='Main' scrolling='auto' noresize frameborder='no'>
</frameset>
<noframes><body bgcolor="#FFFFFF">
<?php echo xlt('Frame support required'); ?>
</body></noframes>

<?php } // end old layout ?>

</html>
