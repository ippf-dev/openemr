<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function create_temporary_table($name, $fields)
{
    $first=true;
    $createSQL = " CREATE TEMPORARY TABLE " .$name 
                 ."(";
            foreach($fields as $fieldName=>$fieldType)
            {
                if(!$first)
                {
                    $createSQL.=",";
                }
                $createSQL.= $fieldName ."  ". $fieldType;
                $first=false;
            }
    $createSQL.=")";
    
    sqlStatement($createSQL);
                     
}

