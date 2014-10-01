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

if (!acl_check('admin', 'language'))
{
    die(xlt("Not authorized"));
}
header('Content-Type: text/html; charset=utf-8');
$res2 = sqlStatement("select * from lang_languages where (lang_description = ?) OR (lang_description = ?) ",array("Spanish (Latin American)","Spanish"));
for ($iter = 0;$row = sqlFetchArray($res2);$iter++)
          $result2[$iter] = $row;
if (count($result2) == 1) {
          $defaultLangID = $result2[0]{"lang_id"};
          $defaultLangName = $result2[0]{"lang_description"};
}
else {
          //default to language ID 3 (should be spanish)
          $defaultLangID = 3;
    }
    $sqlLanguages = "SELECT *,lang_description as trans_lang_description FROM lang_languages ORDER BY lang_id";
    $resLanguages=SqlStatement($sqlLanguages);
    $languages=array();
    while($row=sqlFetchArray($resLanguages))
    {
        array_push($languages,$row);
    }
    
?>
<form method="POST" enctype="multipart/form-data" name="process_csv" action="validate_csv.php" accept-charset="utf-8">
    <select name="language_id">
        <?php
            foreach($languages as $language)
            {
                echo "<option value=".$language{"lang_id"};
                if($language{"lang_id"}==$defaultLangID)
                {
                    echo " selected=true ";
                }
                echo ">".$language{"lang_description"}."</option>";
            }
        ?>
    </select>
    <input type="file" id="language_file" name="language_file"></input>
    <input type="submit" value="Submit"></input>
</form>
