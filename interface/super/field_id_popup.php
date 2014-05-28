<?php
/**
 * This popup is called when choosing a foreign field ID for a form layout.
 *
 * Copyright (C) 2014 Rod Roark <rod@sunsetsystems.com>
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
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>.
 *
 * @package OpenEMR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @link    http://www.open-emr.org
 */

include_once("../globals.php");

$source = empty($_REQUEST['source']) ? 'D' : $_REQUEST['source'];
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('List lists','e'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.2.2.min.js"></script>

<script language="javascript">

function setAFieldID(fieldid) {
  if (opener.closed || ! opener.SetField) {
    alert('The destination form was closed; I cannot act on your selection.');
  }
  else {
    opener.SetField(fieldid);
  }
  window.close();
  return false;
}

function setANewID() {
  return setAFieldID(document.forms[0].new_field_id.value);
}

$(document).ready(function(){

  $('.oneresult').mouseover(function() { $(this).toggleClass('highlight'); });
  $('.oneresult').mouseout(function()  { $(this).toggleClass('highlight'); });
  $('.oneresult').click(function()     { SelectField(this); });

  var SelectField = function(obj) {
    return setAFieldID($(obj).attr('id'));
  };

});

</script>

<style>
h1 {
    font-size: 120%;
    padding: 3px;
    margin: 3px;
}
ul {
    list-style: none;
    padding: 3px;
    margin: 3px;
}
li {
    cursor: pointer;
    border-bottom: 1px solid #ccc;
    background-color: white;
}
.highlight {
    background-color: #336699;
    color: white;
}    
</style>

</head>

<body class="body_top text">
<div id="lists">

<h1>
<?php
// F should never happen, but just in case.
if ($source == 'F') echo xlt('Fields in This Form' ); else
if ($source == 'D') echo xlt('Demographics Fields' ); else
if ($source == 'H') echo xlt('History Fields'      ); else
if ($source == 'E') echo xlt('Visit Attributes'    );
?>
</h1>

<ul>
<?php
if ($source == 'D' || $source == 'H') {
  $res = sqlStatement("SELECT COLUMN_NAME FROM information_schema.COLUMNS " .
    "WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY COLUMN_NAME",
    array($dbase, $source == 'D' ? 'patient_data' : 'history_data'));
  while ($row = sqlFetchArray($res)) {
    echo "<li id='" . $row['COLUMN_NAME'] . "' class='oneresult'>" . text($row['COLUMN_NAME']) . "</li>";
  }
}
else if ($source == 'E') {
  $res = sqlStatement("SELECT DISTINCT field_id FROM shared_attributes ORDER BY field_id");
  while ($row = sqlFetchArray($res)) {
    echo "<li id='" . $row['field_id'] . "' class='oneresult'>" . text($row['field_id']) . "</li>";
  }
}
?>
</ul>

<?php if ($source == 'E') { ?>
<p>
<form>
<center>
<input type='text' name='new_field_id' size='20' />&nbsp;
<input type='button' value='<?php echo xla('Or create this new field ID') ?>' onclick='setANewID()' />
</center>
</form>
</p>
<?php } ?>

</div>
</body>
<script language="javascript">


</script>
</html>
