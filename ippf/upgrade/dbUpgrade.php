<?php

ini_set('max_execution_time', '0');

$ignoreAuth = true; // no login required

require_once('../../interface/globals.php');
require_once('../../library/sql.inc');
require_once('../../library/sql_upgrade_fx.php');

$patches=array('3_2_0-to-4_0_0_upgrade.sql','4_0_0-to-4_1_0_upgrade.sql','4_1_0-to-4_1_1_upgrade.sql','4_1_1-to-4_1_2_upgrade.sql',"3_2_0-to-3_3_0_upgrade.sql");
function applyUpgrade($string)
{
    echo "<B>Applying:".$string."<BR></B>";
    upgradeFromSqlFile($string);
}

foreach($patches as $patch)
{
    applyUpgrade($patch);
}