<?php

function update_global_metadata($section,$entry,$description,$type_or_options,$default,$long_desc,$after=null)
{
    $metadata_content=array(xl($description),
                             $type_or_options,
                             $default,
                             xl($long_desc));
    if($after==null)
    {
        $GLOBALS['GLOBALS_METADATA'][$section][$entry]=$metadata_content;
        
    }
    else {
        $pos=array_search($after, array_keys($GLOBALS['GLOBALS_METADATA'][$section]));
        if($pos===false)
        {
            // Can't find the entry so just put it at the end.
            $GLOBALS['GLOBALS_METADATA'][$section][$entry]=$metadata_content;        
        }
        else
        {
            $res=array_slice($GLOBALS['GLOBALS_METADATA'][$section],0,$pos+1,true)
                + array($entry=>$metadata_content)
                + array_slice($GLOBALS['GLOBALS_METADATA'][$section],$pos+1,null,true);
            $GLOBALS['GLOBALS_METADATA'][$section]=$res;
        }
    }
}

function change_default_global_metadata($section,$entry,$default)
{
    $GLOBALS['GLOBALS_METADATA'][$section][$entry][2]=$default;
}

change_default_global_metadata('Appearance','concurrent_layout','2');  // Use tree menu
change_default_global_metadata('Appearance','gbl_nav_area_width','130');  // Use tree menu

update_global_metadata('Appearance','patient_search_results_sort',
        'Patient Search Results Sorting',
        array(
            '0' => xl('Alphabetical'),
            '1' => xl('Open visits then alphabetical'),
            ),
        '0',                              // default
        'Type of columns displayed for patient search results'
        ,'patient_search_results_style' // Place after
        );

update_global_metadata('Calendar','gbl_auto_update_appt_status',
        'Auto-Update Appointment Status',
        'bool',                           // data type
        '1',                              // default
        'Set appointment status to < when fee sheet is updated, > when checkout is done.',
        'auto_create_new_encounters' // Place after
    );

update_global_metadata('Security','gbl_encryption_key',
      'Hex Encryption Key for Backup/Export',
      'text',                           // data type
      '',                               // default
      '32-byte key for AES-256-ECB encryption expressed as 64 hexadecimal characters.'
    );

require_once("menu_metadata.php");
require_once("features_metadata.php");

?>