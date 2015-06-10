<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$database = NewADOConnection("mysql_log"); // Use the subclassed driver which logs execute events
// Below clientFlags flag is telling the mysql connection to allow local_infile setting,
// which is needed to import data in the Administration->Other->External Data Loads feature.
// Note this is a specific bug to work in Ubuntu 12.04, of which the Data Load feature does not
// work and is suspicious for a bug in PHP of that OS; Setting this clientFlags fixes this bug
// and appears to not cause problems in other operating systems.
$database->clientFlags = 128;
$database->Connect($host.":".$port, $login, $pass, $dbase);
$GLOBALS['adodb']['db'] = $database;
$GLOBALS['dbh'] = $database->_connectionID;