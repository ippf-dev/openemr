<?php
/* Copyright (C) 2014 Kevin Yeh <kevin.y@integralemr.com> 
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @link    http://www.open-emr.org
 */
function find_or_create_constant($constant)
{
    $sqlFind = " SELECT cons_id , constant_name FROM lang_constants where BINARY constant_name = ?";
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
