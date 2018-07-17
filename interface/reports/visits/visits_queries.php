<?php

// Copyright (C) 2015 Kevin Yeh <kevin.y@integralemr.com>
// Modified by Rod Roark <rod@sunsetsystems.com> 2017
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 3
// of the License, or (at your option) any later version.


function create_encounters_temp($startDate,$endDate,$dimensions,$facility_filters,$provider_filters)
{
    
    $columns=array_merge($dimensions,
            array(COL_ENC_ID=>"int",
                                                 COL_ENC_DATE=>"date",
                                                 COL_PID=>"int",
                                                ));
    
    create_temporary_table(TMP_ENCOUNTERS, $columns);
    $insert_columns=array();
    foreach($dimensions as $key=>$value)
    {
        if($key===COL_PERIOD)
        {
            $insert_columns[]="'' as ".COL_PERIOD;
        }
        else if ($key == 'facility') {
            $insert_columns[] = "f.name AS facility";
        }
        else
        {
            $insert_columns[] = 'e.' . $key;
        }
    }
    
    $insert_columns[] = 'e.' . COL_ENCOUNTER . " as " . COL_ENC_ID;
    $insert_columns[] = 'e.' . COL_DATE . " as " . COL_ENC_DATE;
    $insert_columns[] = 'e.' . COL_PID;
    
    $select_columns=implode(",",$insert_columns);
    $query_parameters=array($startDate,$endDate);
    $populate_encounters = "INSERT INTO " . TMP_ENCOUNTERS . " SELECT " . $select_columns
            . " FROM " . TBL_ENCOUNTERS . " AS e"
            . " LEFT JOIN facility AS f ON f.id = e.facility_id"
            . " WHERE e.date >= ? and e.date <= ?";
    
    // Add facility filters
    if(!is_null($facility_filters))
    {
        if(count($facility_filters)>0)
        {
            $populate_encounters .= " AND f.name IN " . "(";
            $first=true;
            foreach($facility_filters as $facility)
            {
                array_push($query_parameters,$facility);
                if(!$first)
                {
                    $populate_encounters.=",";
                }
                $populate_encounters.="?";
                $first=false;
            }
            
            $populate_encounters .= ")";
                    
        }
    }
    
    // Add provider_filters
    if(!is_null($provider_filters))
    {
        if(count($provider_filters)>0)
        {
            $populate_encounters .= " AND e." . COL_PROVIDER_ID . " IN " . "(";
            $first=true;
            foreach($provider_filters as $provider)
            {
                array_push($query_parameters,$provider);
                if(!$first)
                {
                    $populate_encounters.=",";
                }
                $populate_encounters.="?";
                $first=false;
            }
            
            $populate_encounters .= ")";
                    
        }
    }    
    
    sqlStatement($populate_encounters,$query_parameters);    
}

function dimension_names($dimensions)
{
    foreach($dimensions as $key=>$value)
    {
        
    }
}

function setup_periods_data($dimensions)
{
    
    $columns=array_merge($dimensions,array(COL_ACTIVE_DAYS=>"int",
                                                  COL_NUMBER_CLIENTS=>"int",
                                                  COL_NUMBER_VISITS=>"int",
                                                  COL_NUMBER_SERVICES=>"int",
                                                  COL_DAILY_CLIENTS=>"float",
                                                  COL_DAILY_SERVICES=>"float",
                                                  COL_SERVICES_VISIT=>"float"));
    create_temporary_table(TMP_PERIODS_DATA,$columns);
    
    $select_values=array();
    foreach(array_keys($dimensions) as $column)
    {
        $select_values[]="distinct ".$column;
    }
    $populate_periods_data="INSERT INTO ".TMP_PERIODS_DATA
            . " (".implode(array_keys($dimensions),",").") "
            . " SELECT " . implode(array_keys($dimensions),",")
            . " FROM " . TMP_ENCOUNTERS
            . " GROUP BY ".implode(array_keys($dimensions),",");
    
    
    sqlStatement($populate_periods_data);
}

function set_periods($type)
{
    if($type==='m')
    {
      $convert= "DATE_FORMAT(".COL_ENC_DATE.",'%Y-%m')";  
    }
    if($type==='y')
    {
      $convert= "CONCAT(YEAR(".COL_ENC_DATE."))";  
    }
    if($type==='q')
    {
      $convert= "CONCAT(YEAR(".COL_ENC_DATE."),'-','Q',((MONTH(".COL_ENC_DATE.") - 1) DIV 3) + 1)";  
    }

    $sql_months_period = "UPDATE ".TMP_ENCOUNTERS. " SET ".COL_PERIOD."="
            . $convert;
    sqlStatement($sql_months_period);
    
}

function update_results_from_encounters($source_column,$result_column,$dimensions)
{
    $on_phrases=array();
    foreach($dimensions as $column)
    {
        $on_phrases[]="results.".$column."=".TMP_PERIODS_DATA.".".$column;
    }
    $dimensions_list= implode($dimensions,",");
    $sql_update_result =
            " UPDATE ".TMP_PERIODS_DATA
            ." INNER JOIN ("
            ." SELECT count(".$source_column.") ".$result_column.", "
            . $dimensions_list
            ." FROM ".TMP_ENCOUNTERS
            ." GROUP BY ".$dimensions_list
            .") results "
            ." ON ".implode($on_phrases," AND ")
            ." SET ".TMP_PERIODS_DATA.".".$result_column. "=results.".$result_column;
    sqlStatement($sql_update_result);
            
}

function update_results_from_billing($source_column,$result_column,$dimensions)
{
    $on_phrases=array();
    foreach($dimensions as $column)
    {
        $on_phrases[]="results.".$column."=".TMP_PERIODS_DATA.".".$column;
    }
    $dimensions_list= implode($dimensions,",");

    $sql_update_result =
            " UPDATE ".TMP_PERIODS_DATA
            ." INNER JOIN (";
    if ($result_column == COL_NUMBER_SERVICES) {
      $sql_update_result .=
            " SELECT SUM(units) " . $result_column . ", ";
    }
    else {
      $sql_update_result .=
            " SELECT COUNT(" . $source_column . ") " . $result_column . ", ";
    }
    $sql_update_result .=
            $dimensions_list
            ." FROM ".TMP_BILLING_DATA
            ." GROUP BY ".$dimensions_list
            .") results "
            ." ON ".implode($on_phrases," AND ")
            ." SET ".TMP_PERIODS_DATA.".".$result_column. "=results.".$result_column;

    sqlStatement($sql_update_result);
    
}

function set_active_days()
{
    $update_active_days =

   sqlStatement($update_active_days);
}

function update_services($dimensions)
{
    // Create billing temp table
    $columns=array_merge($dimensions,array(
        COL_CODE=>"VARCHAR(20)",
        COL_CODE_TYPE=>"VARCHAR(15)",
        'units' => "INT(11)",
        COL_ENC_ID=>"int",
        COL_PID=>"int",
        COL_ENC_DATE=>"date",
        COL_EXCLUDE=>"BIT"
    ));

    $columns[COL_CODE_TYPE_ID]="int";
    $columns[COL_RELATED_CODE]="varchar(255)";
    $columns[COL_CATEGORY]="varchar(255)";
    
    // Subset of columns only needed to insert billing data into TMP_BILLING_DATA
    $insert_columns=array_merge(
                       array_keys($dimensions),
                       array(
                       COL_CODE,
                       COL_CODE_TYPE,
                       "units",
                       COL_ENC_ID,
                       COL_PID,
                       COL_ENC_DATE));
    
    create_temporary_table(TMP_BILLING_DATA,$columns);

    $select_columns_encounters = array();
    foreach(array_keys($dimensions) as $dimension)
    {
        if($dimension===COL_PROVIDER_ID)
        {
            $select_columns_encounters[]= " IF(".TBL_BILLING.".".COL_PROVIDER_ID . " = 0,"
               . TMP_ENCOUNTERS . ".". COL_PROVIDER_ID. ",". TBL_BILLING.".".COL_PROVIDER_ID . ")";
        }
        else
        {
            $select_columns_encounters[]=TMP_ENCOUNTERS.".".$dimension;            
        }
    }
    $select_columns_encounters[]=TBL_BILLING.".".COL_CODE;
    $select_columns_encounters[]=TBL_BILLING.".".COL_CODE_TYPE;
    $select_columns_encounters[]=TBL_BILLING . ".units";
    $select_columns_encounters[]=TMP_ENCOUNTERS.".".COL_ENC_ID;
    $select_columns_encounters[]=TMP_ENCOUNTERS.".".COL_PID;
    $select_columns_encounters[]=TMP_ENCOUNTERS.".".COL_ENC_DATE;
    
    $populate_billing_data=
            " INSERT INTO ".TMP_BILLING_DATA
            . "(".implode($insert_columns,",") . ")"
            . " SELECT ".implode($select_columns_encounters, ",")
            . " FROM " . TMP_ENCOUNTERS.",".TBL_BILLING
            . " WHERE " . TMP_ENCOUNTERS.".".COL_ENC_ID."=".TBL_BILLING.".".COL_ENCOUNTER
            . " AND ".TMP_ENCOUNTERS.".".COL_PID . " = " . TBL_BILLING.".".COL_PID
            . " AND " .TBL_BILLING.".".COL_ACTIVITY."=1";
    sqlStatement($populate_billing_data);
    
    // convert the character based code type to the numeric id
    $update_code_type_id= " UPDATE ".TMP_BILLING_DATA . "," . TBL_CODE_TYPES 
            . " SET ".TMP_BILLING_DATA.".".COL_CODE_TYPE_ID." = ". TBL_CODE_TYPES.".".COL_CT_ID
            . " WHERE ".TMP_BILLING_DATA.".".COL_CODE_TYPE." = ". TBL_CODE_TYPES.".".COL_CT_KEY;

    sqlStatement($update_code_type_id);

    // Find the IPPF2 code
    $update_related_codes = " UPDATE ". TMP_BILLING_DATA 
            . " INNER JOIN ". TBL_CODES
            . " ON " . TMP_BILLING_DATA.".".COL_CODE . " = " . TBL_CODES.".".COL_CODE
            . " AND " . TMP_BILLING_DATA.".".COL_CODE_TYPE_ID . " = " . TBL_CODES.".".COL_CODE_TYPE
            . " SET " . TMP_BILLING_DATA.".".COL_RELATED_CODE. " = " . TBL_CODES.".".COL_RELATED_CODE;


    sqlStatement($update_related_codes);

    // Strip IPPF2 Code from related codes

    $update_IPPF2 = " UPDATE ". TMP_BILLING_DATA
            . " SET ". COL_RELATED_CODE . "=" 
            . " SUBSTRING_INDEX(SUBSTRING_INDEX(" . COL_RELATED_CODE . "," ."'IPPF2:',-1),';',1)"; 

    sqlStatement($update_IPPF2);

    $update_IPPF2_category = " UPDATE " . TMP_BILLING_DATA
            . " INNER JOIN " . TBL_IPPF2_CATEGORIES 
            . " ON " .  TMP_BILLING_DATA.".". COL_RELATED_CODE ." LIKE " 
            . " CONCAT(".TBL_IPPF2_CATEGORIES . ".". COL_CATEGORY_HEADER .  ",'%')"
            . " SET " . TMP_BILLING_DATA . ". ". COL_CATEGORY . "=" . TBL_IPPF2_CATEGORIES . "." . COL_CATEGORY_NAME
            . ",". TMP_BILLING_DATA . ". ". COL_EXCLUDE . "=" . TBL_IPPF2_CATEGORIES . "." . COL_EXCLUDE ;

    sqlStatement($update_IPPF2_category);

    $remove_administrative_services = " DELETE FROM " . TMP_BILLING_DATA
            . " " . " WHERE ". COL_EXCLUDE;

    sqlStatement($remove_administrative_services);
}

function aggregate_categories($dimension_columns)
{
    
    $query_category_totals= " SELECT "
            . implode($dimension_columns, ",")
            . ",". COL_CATEGORY
            . ", SUM(units) as number FROM "
            . TMP_BILLING_DATA
            . " GROUP BY "
            . implode($dimension_columns, ","). "," . COL_CATEGORY;
    
    $res=sqlStatement($query_category_totals);
    
    $retval=array();
    while($row=sqlFetchArray($res))
    {
        $array_at_depth=&$retval;
        foreach($dimension_columns as $column)
        {
            $array_key=$row[$column];
            if(!isset($array_at_depth[$array_key]))
            {
                $array_at_depth[$array_key]=array();
                $array_at_depth=&$array_at_depth[$array_key];
            }
            else
            {
                $array_at_depth=&$array_at_depth[$array_key];
            }            
        }
        $array_at_depth[$row[COL_CATEGORY]]=$row['number'];
    }
    return $retval;

    
}

function update_averages()
{
    $update_query=  " UPDATE ". TMP_PERIODS_DATA
                    ." SET " . COL_DAILY_CLIENTS . " = " . "ROUND(".COL_NUMBER_VISITS . "/" . COL_ACTIVE_DAYS . ",1)"
                    . ", " . COL_DAILY_SERVICES . " = " . "ROUND(". COL_NUMBER_SERVICES . "/" . COL_ACTIVE_DAYS. ",1)"
                    . ", " . COL_SERVICES_VISIT . " = " . "ROUND(".COL_NUMBER_SERVICES . "/" . COL_NUMBER_VISITS. ",1)";
    
    sqlStatement($update_query);
}

function build_category_list($filters=null)
{
    $parameters=array();
    $select_categories_query = " SELECT ". COL_CATEGORY_NAME . " FROM " . TBL_IPPF2_CATEGORIES . " WHERE NOT " . COL_EXCLUDE ;
    
    if($filters!==null)
    {
        if(count($filters)>0)
        {
            $in="(";
            $first=true;
            foreach($filters as $filter)
            {
                array_push($parameters,$filter);
                if(!$first)
                {
                    $in .= ",";
                }
                $in.="?";
                $first=false;
            }
            $in.=")";
            $select_categories_query .= " AND " . COL_CATEGORY_NAME . " IN " . $in;
        }
    }
    $select_categories_query .= " ORDER BY " . COL_CATEGORY_HEADER . " ASC";
    $res=sqlStatement($select_categories_query,$parameters);
    $retval=array();
    while($row= sqlFetchArray($res))
    {
        $retval[] = $row[COL_CATEGORY_NAME];
    }
    return $retval;
}

function process_results($dimension_columns,$categorize=false)
{
    $process_function="update_results_from_encounters";
    if($categorize)
    {
        $process_function="update_results_from_billing";
    }
    // compute active days in each period
    $process_function("distinct ".COL_ENC_DATE,COL_ACTIVE_DAYS,$dimension_columns);
    
    // find unique patients
    $process_function("distinct ".COL_PID,COL_NUMBER_CLIENTS,$dimension_columns);

    // find visits
    $process_function("distinct ".COL_ENC_ID,COL_NUMBER_VISITS,$dimension_columns);
    
    // aggregate service data.
    update_results_from_billing("*",COL_NUMBER_SERVICES,$dimension_columns);

}
function filter_categories($categories_filter)
{
    if($categories_filter!==null)
    {
        $parameters=array();
        $deleteCategories= " DELETE FROM ". TMP_BILLING_DATA;
        if(count($categories_filter)>0)
        {
            $in="(";
            $first=true;
            foreach($categories_filter as $filter)
            {
                array_push($parameters,$filter);
                if(!$first)
                {
                    $in .= ",";
                }
                $in.="?";
                $first=false;
            }
            $in.=")";
            $deleteCategories .= " WHERE " . COL_CATEGORY . " NOT IN " . $in . " OR ".COL_CATEGORY . " IS NULL " ;
            sqlStatement($deleteCategories,$parameters);
        }
    }
    
}
function query_visits($enc_from,$enc_to,$period_size,$categorize,$facility_filters=null,$provider_filters=null,$category_filter=null)
{
    $providers_details=false;
    $facilities_details=false;
    
    if(!is_null($provider_filters))
    {
        $providers_details=true;
        $dimensions[COL_PROVIDER_ID]="int NOT NULL default 0";
    }
    if(!is_null($facility_filters))
    {
        $facilities_details=true;
        $dimensions[COL_FACILITY]="VARCHAR(255)";
        
    }
    $dimensions[COL_PERIOD]="VARCHAR(15)";
    $dimension_columns=array_keys($dimensions);    
    
    // create encounters temp table
    create_encounters_temp($enc_from,$enc_to,$dimensions,$facility_filters,$provider_filters);
    
    
    // define periods
    set_periods($period_size);
    
    setup_periods_data($dimensions);
    
    // join with billing to find services
    update_services($dimensions);

    
    if($categorize)
    {
        $category_data=aggregate_categories($dimension_columns);
        $category_list = build_category_list($category_filter);
        filter_categories($category_filter);
    }

    process_results($dimension_columns,$categorize);
    
    update_averages();
    
    $select_results="SELECT * FROM ".TMP_PERIODS_DATA;
//    $select_results="SELECT * FROM ".TMP_BILLING_DATA;
    $res=sqlStatement($select_results);
    $retval=array();
    while($row=sqlFetchArray($res))
    {
        if($categorize)
        {
            $return_row=array();
            $data_traversal=&$category_data;
            foreach($dimension_columns as $column)
            {
                $data_traversal=&$data_traversal[$row[$column]];
                $return_row[$column]=$row[$column];
            }
            foreach($category_list as $category)
            {
                $category_count=0;
                if(!is_null($data_traversal))
                {
                    if(isset($data_traversal[$category]))
                    {
                        $category_count=$data_traversal[$category];                        
                    }
                }
                $return_row[$category]=$category_count;
            }
            foreach($row as $key=>$value)
            {
                $return_row[$key]=$value;
            }
            $retval[]=$return_row;
        }
        else
        {
            $retval[]=$row;            
        }
    }
    
    
    // Re-run analysis for clinic totals if clinic AND provider details are required
    if($providers_details && $facilities_details)
    {
        $sqlDropPeriods_data = "DROP TABLE ".TMP_PERIODS_DATA;
        sqlStatement($sqlDropPeriods_data);
        
        unset($dimensions[COL_PROVIDER_ID]);
        setup_periods_data($dimensions);
        $dimension_columns=array_keys($dimensions);
        process_results($dimension_columns,$categorize);
        update_averages();
        
        if($categorize)
        {
            $category_data=aggregate_categories($dimension_columns);
            $category_list = build_category_list($category_filter);
        }
    $select_results="SELECT * FROM ".TMP_PERIODS_DATA;
//    $select_results="SELECT * FROM ".TMP_BILLING_DATA;
    $res=sqlStatement($select_results);        
    while($row=sqlFetchArray($res))
    {
        $row[COL_PROVIDER_ID]=-1;
        if($categorize)
        {
            $return_row=array();
            $data_traversal=&$category_data;
            foreach($dimension_columns as $column)
            {
                $data_traversal=&$data_traversal[$row[$column]];
                $return_row[$column]=$row[$column];
            }
            foreach($category_list as $category)
            {
                $category_count=0;
                if(!is_null($data_traversal))
                {
                    if(isset($data_traversal[$category]))
                    {
                        $category_count=$data_traversal[$category];                        
                    }
                }
                $return_row[$category]=$category_count;
            }
            foreach($row as $key=>$value)
            {
                $return_row[$key]=$value;
            }
            $retval[]=$return_row;
        }
        else
        {
            $retval[]=$row;            
        }
    }        
    }
    return $retval;
}

