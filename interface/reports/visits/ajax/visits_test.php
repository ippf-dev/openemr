<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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