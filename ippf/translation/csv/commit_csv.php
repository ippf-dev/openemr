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
$lang_id=$_REQUEST['lang_id'];
$translations=json_decode($_REQUEST['translations']);
foreach($translations as $translation)
{
    echo verify_translation($translation[0],$translation[1],$lang_id) . "<br>";
}
?>
