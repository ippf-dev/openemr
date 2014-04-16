<?php

ini_set('max_execution_time', '0');

$ignoreAuth = true; // no login required
// Clear out the any existing user to prevent querying of user_settings table in globals.php.  (user_settings may not exist yet.)
unset($_SESSION['authUserID']);

require_once('../../interface/globals.php');
require_once('../../library/sql.inc');
require_once('../../library/sql_upgrade_fx.php');

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