<?php
  
 
    require_once("../../interface/globals.php");
    ini_set("display_errors","1");   
    require_once("translation_utilities.php");
    
?>

Translation Tool
<br>
<?php 
    $definitions=array(
        "Patients"=>"Client List",
        "Codes"=>"Services",
        "Encounter History"=>"Visit History",
        "New Encounter"=>"New Visit",
        "Past Encounter List"=>"Past Visit List",
        "CLEAR ACTIVE PATIENT"=>"Clear Active Client",
        "Transactions{{Patient}}"=>"Referrals",
        "Online Support"=>"IPPF Process Guides"
    );
    verify_translations($definitions,1);
?>