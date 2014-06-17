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

verify_translations($definitions,1);
echo "English to English constants applied ";
var_dump($definitions);
echo "<BR>";

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