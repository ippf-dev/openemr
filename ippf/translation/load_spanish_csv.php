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
    require_once("../../interface/globals.php");
    ini_set("display_errors","1");   
    require_once("translation_utilities.php");
?>
Translation Tool
<br>
<?php 
    $find_lang="SELECT * FROM lang_languages where lang_description like 'Spanish%'";
    
    $lang_id=3;
    $translation_files_directory=$GLOBALS['webserver_root']."/ippf/translation/data";
    $translation_file=$translation_files_directory."/"."spanish_ippf.csv";
    echo "<b>Loading translations from:</b>".$translation_file."<br>";
    verify_file($translation_file,$lang_id,true,"",1,3);
?>