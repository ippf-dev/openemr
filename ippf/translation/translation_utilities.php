<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function find_or_create_constant($constant)
{
    $sqlFind = " SELECT cons_id , constant_name FROM lang_constants where constant_name = ?";
    $result = sqlStatement($sqlFind,array($constant));
    if($result)
    {
        $row_count=sqlNumRows($result);
        if($row_count==1)
        {
            $row=sqlFetchArray($result);
            return $row['cons_id'];
        }
        if($row_count>1)
        {
            error_log("Duplicate Entries for language constant:".$constant);
            return -1;
        }
        if($row_count==0)
        {
            $sqlInsert = " INSERT INTO lang_constants (constant_name) VALUES (?)";
            $new_index=sqlInsert($sqlInsert,$constant);
            return $new_index;
        }
    }
    
    
}

function verify_translation($constant,$definition,$language)
{
    $cons_id=find_or_create_constant($constant);
    $whereClause=" lang_id=? and cons_id=? ";
    $sqlFind = " SELECT def_id,definition FROM lang_definitions WHERE ".$whereClause;
    $result = sqlStatement($sqlFind,array($language,$cons_id));
    $infoText=$constant."|".$definition."|".$language;
    if($result)
    {
        $row_count=sqlNumRows($result);        
        if($row_count==1)
        {
            $row=sqlFetchArray($result);
            if($row['definition']==$definition)
            {
                return "Definition Exists:".$infoText;
            }
            else
            {
                $sqlUpdate=" UPDATE lang_definitions SET definition=? WHERE def_id=?";
                $result=sqlStatement($sqlUpdate,array($definition,$row['def_id']));
                return "Definition Updated from:".$row['definition']."|".$infoText;
            }
        }
        if($row_count>1)
        {
            // Too many definitions, delete then recreate.
            $sqlDelete = " DELETE FROM lang_definitions WHERE ".$whereClause;
            $sqlStatement($sqlDelete,array($language,$cons_id));
            $create=true;
            
        }
        if($row_count==0)
        {
            $create=true;
        }
        if($create)
        {
            $sqlInsert=" INSERT INTO lang_definitions (cons_id,lang_id,definition) VALUES (?,?,?) ";
            $id=sqlInsert($sqlInsert,array($cons_id,$language,$definition));
            return "Definition Created:".$infoText;
            
        }
    }
}
function verify_translations($definitions,$language)
{
    foreach($definitions as $constant=>$definition)
    {
        verify_translation($constant,$definition,$language);
    }
}
?>
