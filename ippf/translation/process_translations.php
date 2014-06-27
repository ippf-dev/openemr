<meta charset="utf-8"> 
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $ignore_auth=true;
    require_once("../../interface/globals.php");
    ini_set("display_errors","1");   
    require_once("translation_utilities.php");

    $translation_files_directory=$GLOBALS['webserver_root']."/ippf/translation/data";
    verify_file("$translation_files_directory/english_to_english.csv",1);
    
    $spanish_constant=4;
    verify_file("data/openemr_latin_american.csv",$spanish_constant,true,"");

    echo "Spanish_test";
    verify_file("$translation_files_directory/spanish_test.csv",$spanish_constant,true,"test");
    
//    echo "Spanish_haiti<BR>";
//    verify_file("data/spanish_haiti.csv",$spanish_constant,true,"haiti");       

    echo "Spanish_panama<BR>";
    verify_file("$translation_files_directory/spanish_panama.csv",$spanish_constant,true,"panama");    

    echo "Spanish_argentina<BR>";
    verify_file("$translation_files_directory/spanish_argentina.csv",$spanish_constant,true,"argentina");

    echo "Spanish_spanish<BR>";
    verify_file("$translation_files_directory/spanish_spanish.csv",$spanish_constant,true,"spanish");
    
    echo "Spanish_test";
    verify_file("$translation_files_directory/spanish_test.csv",$spanish_constant,true,"test");
    
   verify_file("$translation_files_directory/openemr_latin_american.csv",$spanish_constant,false,"openemr");    
   
   /*
        manual queries to complete processing
    
    update ippf_loop.ippf_lang_definitions set source='openemr' where source is null;
    insert into ippf_loop.ippf_lang_definitions (constant_name) select constant_name from ippf_loop.lang_constants where constant_name not in (select constant_name from ippf_loop.ippf_lang_definitions);
    update ippf_loop.ippf_lang_definitions set definition='' where definition is null;

    SELECT * FROM ippf_loop.ippf_lang_definitions order by constant_name limit 10000;

    */
   
 