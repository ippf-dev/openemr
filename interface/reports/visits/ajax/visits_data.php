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


if(!acl_check('acct', 'rep'))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Not authorized for billing";   
    return false;
}

if(isset($_REQUEST['parameters']))
{
    $parameters=json_decode($_REQUEST['parameters']);
}
else
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No parameters in request";   
    return false;
    
}

if($parameters->{'clinics_details'})
{
    $facility_filters=array();
    if($parameters->{'clinic_filter'})
    {
        $facility_filters=$parameters->{'clinic_filter'};
        if(count($facility_filters)>=1)
        {
            if($facility_filters[0]==xl('All'))
            {
               $facility_filters=array(); 
            }
        }
    }
}
else
{
    $facility_filters=null;
}

if($parameters->{'providers_details'})
{
    $providers_filters=array();
    if($parameters->{'provider_filter'})
    {
        $providers_filters=$parameters->{'provider_filter'};
        if(count($providers_filters)>=1)
        {
            if($providers_filters[0]==='ALL')
            {
                $providers_filters=array();
            }
        }
    }
}
else
{
    $providers_filters=null;
}

$category_filter=null;
if($parameters->{"categorize_services"})
{
    $categories_filter=array();
    if($parameters->{'category_filter'})
    {
        $category_filter=$parameters->{'category_filter'};
        if(count($category_filter)>=1)
        {
            if($category_filter[0]===xl("--All Service Categories--"))
            {
                $category_filter=null;
            }
        }
        
    }
}
echo json_encode(query_visits($parameters->{'from'}
                              ,$parameters->{'to'}
                              ,$parameters->{'period_size'}
                              ,$parameters->{"categorize_services"}
                              ,$facility_filters
                              ,$providers_filters
                              ,$category_filter));
                              
$database->Close();