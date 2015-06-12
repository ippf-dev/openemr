<?php

// Copyright (C) 2015 Kevin Yeh <kevin.y@integralemr.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 3
// of the License, or (at your option) any later version.

require_once("../../../globals.php");
ini_set('display_errors',1);
require_once("$webserver_root/interface/reports/dbutils/nonpersistent_dbconnect.php");
require_once("$webserver_root/interface/reports/dbutils/sql_constants.php");
require_once("$webserver_root/interface/reports/dbutils/temporary_tables.php");
require_once("$webserver_root/interface/reports/visits/visits_queries.php");


//echo json_encode(query_visits("2015-01-01","2015-03-31","m",true,['All'],['All']));
echo json_encode(query_visits("2015-01-01","2015-03-31","m",true,null,['All']));
//echo json_encode(query_visits("2015-01-01","2015-03-31","m",true,['All'],null));
//echo json_encode(query_visits("2015-01-01","2015-03-31","m",true,null,null));