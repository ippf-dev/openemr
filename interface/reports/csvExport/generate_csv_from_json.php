<?php
// Copyright (C) 2014 Kevin Yeh <kevin.y@integralemr.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

    $file_name="csvexport";
    if(isset($_REQUEST['filename']))
    {
        $file_name=$_REQUEST['filename'];
    }
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download; charset=utf-8");
    header("Content-Disposition: attachment; filename={$file_name}.csv");
    header("Content-Description: File Transfer");
    
    $csvData = json_decode($_REQUEST['jsonData']);
    $out = fopen('php://output', 'w');
    
    foreach($csvData as $row)
    {
        fputcsv($out,$row);
    }
    fclose($out);