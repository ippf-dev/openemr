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
require_once("../../../interface/globals.php");
require_once("../translation_utilities.php");
if (!acl_check('admin', 'language'))
{
    die(xlt("Not authorized"));
}

if(!isset($_REQUEST['translations']))
{
    echo "No translations!";
    return;
}

if(!isset($_REQUEST['lang_id']))
{
    echo "No Language ID specified!";
    return;
}


if(!isset($_REQUEST['preview']))
{
    $preview=true;
}
else
{
    $preview==$_REQUEST['preview'];
    if($preview==="false")
    {
        $preview=false;
    }
}
$lang_id=$_REQUEST['lang_id'];
$translations=json_decode($_REQUEST['translations']);
$unchanged=0;
$empty=0;
$changed=array();
$created=array();
$updated=array();
foreach($translations as $translation)
{
    $result = verify_translation(
        str_replace("\r\n", "\n", $translation[0]),
        str_replace("\r\n", "\n", $translation[1]),
        $lang_id,
        true,
        "",
        false,
        $preview
    );
    if(strstr($result,"Definition Exists:")===false)
    {
        if(strstr($result,"Empty Definition")===false)
        {
            if($result)
            {
                array_push($changed,$result);
                if(strstr($result,"Update From:")!==false)
                {
                    array_push($updated,$result);
                } 
                else if(strstr($result,"Create:")!==false)
                {
                    array_push($created,$result);                  
                }             
            }
        }
        else {
            $empty++;
        }
    }
    else
    {
        $unchanged++;
    }
}
$retval=array();
$retval['changed']=$changed;
$retval['unchanged']=$unchanged;
$retval['empty']=$empty;
$retval['updated']=$updated;
$retval['created']=$created;
$changes_html="";
foreach($changed as $change)
{
    $changes_html.=$change."<br>";
}
$retval['html_changes']=$changes_html;
echo json_encode($retval);
?>
