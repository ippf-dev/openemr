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
    );
    verify_translations($definitions,1);
?>