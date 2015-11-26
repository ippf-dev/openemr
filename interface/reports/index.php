<?php

require_once (dirname(__FILE__) . "/../globals.php");
require_once (dirname(__FILE__) . "/../../library/sql.inc");
require_once (dirname(__FILE__) . "/../../library/Smarty.class.php");
require_once (dirname(__FILE__) . "/../../library/adodb/adodb-pager.inc.php");

//get db connection setup in sql.inc
$db = $GLOBALS['adodb']['db'];

//define smarty template directory
$template_dir = dirname(__FILE__) . "/../../templates/report/";

//initialize a smarty object
$smarty = new Smarty();

//tell smarty where it can compile the templates, this is the defacto becuases postcalendar's smarty already uses it
$smarty->compile_dir = dirname(__FILE__) . "/../main/calendar/modules/PostCalendar/pntemplates/compiled";

//assign the styles setup in globals.php
$smarty->assign("STYLE",$GLOBALS['style']);

// assign the style sheet theme as defined in globals.php -- JRM
$smarty->assign("css_header", $GLOBALS['css_header']);

//There is not real ALL, so for this purpose we say 20,000
$show_options = array ("10" => "10","20" => "20","50" => "50","100" => "100","20000" => "All");

$smarty->assign("show_options",$show_options);

//query to select all canned queries from the pma_bookmark table
$sql = "SELECT * FROM pma_bookmark ORDER BY id"; 
$res = $db->Execute($sql);

//array to hold id's and labels of canned queries
$queries = array();

//loop through results
while (!$res->EOF) {
  //populate the array with the id number and label of each canned query
  $queries[$res->fields['id']] = $res->fields['label'];
  $res->MoveNext();
}

//assign the array so the template can loop over it
$smarty->assign("queries",$queries);

//load the query id
$query_id = $_GET['query_id'];

//load the result per page number
$show = $_GET['show'];

//set a default show value
if (!is_numeric($show)) {
	$show = $show_options[0];
}

//assign the var to the template
$smarty->assign("show",$show);	

//conditional to see if a query has been selected and should be run
if (is_numeric($query_id)) {
	//there is a query so set a default so the dropdowns will show the running query
	$smarty->assign("query_id",$query_id);
	
	//get the actual query from the pma_bookmark database
	$sql = "SELECT query,label from pma_bookmark where id = " . $db->qstr($query_id);
	//clear current_query var
	$current_query = "";

	$res = $db->Execute($sql);
	if (!$res->EOF) {
	  $current_query = $res->fields['query'];
	  $smarty->assign("title", $res->fields['label']);
	}

	//current_query will now be empty or contain the query that was selected to be run

	//each query can have "customizable" pieces, first see if the user entered any values for the first piece
	if (!empty($_GET['var1'])) {

	  //escape the value the user supplied
	  $var1 = add_escape_custom($_GET['var1']);

	  //use a regex to replace the piece token with the user supplied value
	  $current_query = preg_replace('|/\*(.*)\[VARIABLE\](.*)\*/|imSU', '${1}' . $var1 . '${2}', $current_query);

	//set a default so the template will fill in the varr fields with what the user last supplied
	$smarty->assign("var1", $var1);
	}
	
	//repeat process if a second value was entered
	if (!empty($_GET['var2'])) {
	  $var2 = add_escape_custom($_GET['var2']);
	  $current_query = preg_replace('|/\*(.*)\[VARIABLE2\](.*)\*/|imSU', '${1}' . $var2 . '${2}', $current_query);
	  $current_query = preg_replace('|\[VARIABLE2\]|imU', '${1}' . $var2 . '${2}', $current_query);

	$smarty->assign("var2", $var2);
	}
//echo "<pre>" . $current_query . "<br>";

	//create a pager object that will handle all the dirty details of the display and pagination, had to add an argument to the constructor to pass along extra things that should appear in the query string for links generated by the pager
	$pager = new ADODB_Pager($db,$current_query,"report",false,"query_id=" . $query_id . "&var1=" . $var1 . "&var2=" . $var2 . "&show=" . $show);
	
	//hide links if in print view
	if ($_GET['print'] == 1) {
	  $pager->showPageLinks = false;	
	}
	
	//assign the pager object so the template can call it, need a more elegant way to do this or need to put error handling in the template, how can you capture the output from the pager?
	$smarty->assign("pager", $pager);
}

//generate and assign printable link to template
$smarty->assign("printable_link",$_SERVER['PHP_SELF'] . "?query_id=" . $query_id . "&var1=" . $var1 . "&var2=" . $var2 . "&show=20000&print=1");

if ($_GET['print'] == 1) {
  //load the printable template instead	
  $smarty->display($template_dir . "printable_default.html");
}
else {
  //tell smarty to execute the template
  $smarty->display($template_dir . "general_default.html");
}

?>
