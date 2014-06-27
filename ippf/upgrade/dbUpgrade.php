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
ini_set('max_execution_time', '0');

$ignoreAuth = true; // no login required
// Clear out the any existing user to prevent querying of user_settings table in globals.php.  (user_settings may not exist yet.)
unset($_SESSION['authUserID']);

require_once('../../interface/globals.php');
require_once('../../library/sql.inc');
require_once('../../library/sql_upgrade_fx.php');
require_once("../translation/english_to_english_definitions.php");
require_once("../translation/translation_utilities.php");

require_once("../translation/translation_utilities.php");

$translation_files_directory=$GLOBALS['webserver_root']."/ippf/translation/data";
$translation_file="english_to_english.csv";
echo "<b>Loading translations from:</b>".$translation_file."<br>";
verify_file("$translation_files_directory/english_to_english.csv",1);

$patches=array('3_2_0-to-4_0_0_upgrade.sql','4_0_0-to-4_1_0_upgrade.sql','4_1_0-to-4_1_1_upgrade.sql','4_1_1-to-4_1_2_upgrade.sql',"4_1_2-to-4_1_3_upgrade.sql","3_2_0-to-3_3_0_upgrade.sql","ippf_merge_changes.sql");
function applyUpgrade($string)
{
    echo "<B>Applying:".$string."<BR></B>";
    upgradeFromSqlFile($string);
}

foreach($patches as $patch)
{
    applyUpgrade($patch);
}

function update_layout_option($form_id,$field_id,$option,$value)
{
    $sqlQuery="UPDATE layout_options set ".$option."=? WHERE form_id=? and field_id=?";
    sqlStatement($sqlQuery,array($value,$form_id,$field_id));
}

echo "<b>Updating Layout Fields</b><br>";

// Add additional demographics field identifiers to this array to have them flagged as "unused" in layout options
$dem_fields_to_disable=array('vfc','mothersname','guardiansname','allow_imm_reg_use','allow_imm_info_share','allow_health_info_ex','email_direct');
foreach($dem_fields_to_disable as $field)
{
    echo "Setting ".$field." as unused<br>";
    update_layout_option("DEM",$field,'uor','0');
}


