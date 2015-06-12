<?php
// Copyright (C) 2015 Kevin Yeh <kevin.y@integralemr.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 3
// of the License, or (at your option) any later version.

// This module establishes a non-persistent connection to the OpenEMR database, useful when using temporary tables



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

$database->Execute("SET NAMES 'utf8'");