<?php
/**
 * Document Template Management Module.
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

$userid = $_SESSION['authId'];

$message = '';
if (!empty($_POST['bn_save'])) {
  // Save changes and display success message.
  $i = -1;
  foreach ($_POST['form_question'] as $i => $qid) {
    $ans = $_POST['form_answer'][$i];
    $row = sqlQuery("SELECT question_id, answer FROM login_security_answers WHERE " .
      "user_id = ? AND seq = ?", array($userid, $i));
    if (isset($row['question_id'])) {
      if ($row['question_id'] !== $qid || $row['answer'] !== $ans) {
        sqlStatement("UPDATE login_security_answers SET question_id = ?, answer = ? " .
          "WHERE user_id = ? AND seq = ?", array($qid, $ans, $userid, $i));
      }
    }
    else {
      sqlStatement("INSERT INTO login_security_answers " .
        "(user_id, seq, question_id, answer) VALUES " .
        "(?, ?, ?, ?)", array($userid, $i, $qid, $ans));
    }
  }
  sqlStatement("DELETE FROM login_security_answers WHERE " .
    "user_id = ? AND seq > ?", array($userid, $i));
  $message = xl('Save successful.');
}
?>
<html>

<head>
<title><?php echo xlt('Manage Personal Security Questions'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
</style>

</head>

<body class="body_top">
<form method='post' action='challenge_questions.php' onsubmit='return top.restoreSession()'>

<center>

<h2><?php echo xlt('Manage Personal Security Questions'); ?></h2>

<p>
<table border='1'>

<?php
for ($i = 0; $i < $GLOBALS['gbl_num_challenge_questions_stored']; ++$i) {
  $row = sqlQuery("SELECT question_id, answer FROM login_security_answers WHERE " .
    "user_id = ? AND seq = ?", array($userid, $i));
  $currq = isset($row['question_id']) ? $row['question_id'] : '';
  $curra = isset($row['answer'     ]) ? $row['answer'     ] : '';
  echo " <tr><td>";
  echo generate_select_list("form_question[$i]", 'login_security_questions', $currq);
  echo "</td><td>";
  echo "<input type'text' name='form_answer[$i]' size='30' value='" . attr($curra) . "' />";
  echo "</td></tr>\n";
}
?>

</table>
</p>

<p><input type='submit' name='bn_save' value='<?php echo xla('Save') ?>' /></p>
<p style='color:green'><?php echo text($message); ?></p>

</center>

</form>
</body>
</html>
