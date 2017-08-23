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
require_once("../translation/translation_utilities.php");

$translation_files_directory=$GLOBALS['webserver_root']."/ippf/translation/data";
$translation_file="english_to_english.csv";
echo "<b>Loading translations from:</b>".$translation_file."<br>";
verify_file("$translation_files_directory/english_to_english.csv",1);

if($new_database_setup) {
    $patches = array(
        // '3_2_0-to-3_3_0_upgrade.sql', // Removed by Rod
        'ippf_merge_changes.sql',
        'ippf2_categories.sql',
    );
}
else {
    $patches = array(
        'ippf_upgrade.sql',
        '3_2_0-to-4_0_0_upgrade.sql',
        '4_0_0-to-4_1_0_upgrade.sql',
        '4_1_0-to-4_1_1_upgrade.sql',
        '4_1_1-to-4_1_2_upgrade.sql',
        '4_1_2-to-4_1_3_upgrade.sql',
        '3_2_0-to-3_3_0_upgrade.sql',
        'ippf_merge_changes.sql',
        'ippf_3_3_0-to-4_1_3_upgrade.sql',
        'ippf2_categories.sql',
    ); 
}

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
$dem_fields_to_disable=array('vfc',
                             'mothersname',
                             'guardiansname',
                             'allow_imm_reg_use',
                             'allow_imm_info_share',
                             'allow_health_info_ex',
                             'allow_patient_portal',
                             'email_direct',
                             'deceased_date',
                             'deceased_reason',
                             'ref_providerID',
                             'ethnicity',
                             );
foreach($dem_fields_to_disable as $field)
{
    echo "Setting ".$field." as unused<br>";
    update_layout_option("DEM",$field,'uor','0');
}

// Array of global settings to confirm are updated
$global_settings = array("css_header"=>"style_metal.css",
                'gbl_min_max_months'=>'1',
                'concurrent_layout'=>'3',
                'esign_individual'=>'0',
                'lock_esign_individual'=>'0'
                );

function verify_global_settings($setting,$value)
{
    $sqlUpdate=" REPLACE INTO globals set gl_name=?, gl_index=0, gl_value=?";
    sqlStatement($sqlUpdate,array($setting,$value));
    echo "Verified Global:".$setting."=>".$value."<br>";
}

foreach($global_settings as $setting=>$value)
{
    verify_global_settings($setting,$value);
}

$user_settings = array(
        "global:concurrent_layout",
        "global:css_header",
    );
function reset_users_settings_to_default($setting)
{
    $sqlUpdate=" DELETE FROM user_settings WHERE setting_label=?";
    sqlStatement($sqlUpdate,array($setting));
    echo "Resetting ".$setting." to default for All Users"."<br>";
}

foreach($user_settings as $setting)
{
    reset_users_settings_to_default($setting);
}

  echo "<font color='green'>Updating global configuration defaults...</font><br />\n";
  require_once("../../library/globals.inc.php");
  foreach ($GLOBALS_METADATA as $grpname => $grparr) {
    foreach ($grparr as $fldid => $fldarr) {
      list($fldname, $fldtype, $flddef, $flddesc) = $fldarr;
      if (is_array($fldtype) || substr($fldtype, 0, 2) !== 'm_') {
        $row = sqlQuery("SELECT count(*) AS count FROM globals WHERE gl_name = '$fldid'");
        if (empty($row['count'])) {
          sqlStatement("INSERT INTO globals ( gl_name, gl_index, gl_value ) " .
            "VALUES ( '$fldid', '0', '$flddef' )");
        }
      }
    }
  }

  echo "<font color='green'>Updating Access Controls...</font><br />\n";
  require("../../acl_upgrade.php");
  echo "<br />\n";

  echo "<font color='green'>Updating version indicators...</font><br />\n";
  sqlStatement("UPDATE version SET v_major = '$v_major', v_minor = '$v_minor', " .
    "v_patch = '$v_patch', v_tag = '$v_tag', v_database = '$v_database'");
  
  echo "<span> Version $v_major.$v_minor.$v_patch$v_tag</span>";

  echo "<p><font color='green'>Database and Access Control upgrade finished.</font></p>\n";

  ?>

<font color='green'><b>All Settings successful</b></font>
<a name='end'></a>