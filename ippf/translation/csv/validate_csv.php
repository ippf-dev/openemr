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

require_once("../../../interface/globals.php");
require_once("../translation_utilities.php");
if (!acl_check('admin', 'language'))
{
    die(xlt("Not authorized"));
}

header('Content-Type: text/html; charset=utf-8');
if(!isset($_FILES['language_file']))
{
    echo "No file specified";
    return;
}

if(!isset($_REQUEST['language_id']))
{
    echo "No Language ID specified";
    return;
}

$lang_id=$_REQUEST['language_id'];
$resLanguage = sqlStatement("select * from lang_languages where (lang_id=?)",array($lang_id));
$rowLanguage=sqlFetchArray($resLanguage);
$lang_description=$rowLanguage['lang_description'];


$handle=utf8_fopen_read($_FILES["language_file"]["tmp_name"]);
if($handle)
{
    $file_contents=array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $file_contents[]=$data;
        }
}
if(count($file_contents)===0)
{
    echo "Unable to Parse file! Verify File encoding";
    die;
}

?>
<link rel="stylesheet" type="text/css" href="translate_csv.css"/>

<div id="status"></div>
<div id="information"></div>
<div id="file-display" data-bind="template:{name: 'file-info', data: filedata}" ></div>
<script src="<?php echo $web_root; ?>/library/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo $web_root; ?>/library/js/knockout/knockout-2.2.1.js"></script>


<script type="text/html" id="file-info">
    
    <div id="verify" data-bind="visible: mode()=='verify' ">
        <h1>
            Verify Contents to apply to <?php echo $lang_description; ?>
        </h1>
        <span>Choose constant column</span> <select data-bind="options: header,optionsText: 'text', value: constant_choice" ></select><br>

        <span>Choose definition column</span> <select data-bind="options: header,optionsText: 'text', value: definition_choice" ></select><br>

        <input type="button" id="preview" value="Preview Changes" data-bind="click: previewChanges"></input>


        <table>
            <thead>
                <tr data-bind="foreach: header">
                        <th data-bind="text:$data.text, attr: { index: $index}"></th>                
                </tr>
            </thead>
            <tbody>
                <?php
                    for($idx=1;$idx<count($file_contents);$idx++)
                    {
                        $row=$file_contents[$idx];
                        echo "<tr>";
                        foreach($row as $cell)
                        {
                            echo "<td>".$cell."</td>";
                        }
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- ko if: mode()=='preview' -->
    <div id="preview" data-bind="with: preview_data">
        <h1>Preview Changes</h1>
        <input type="button" id="preview" value="Commit Changes" data-bind="click: commitChanges"></input>        
        <div>
            <span>Unchanged Entries verified:</span><span data-bind="text:unchanged()"></span>
        </div>
        <div>
            <span>Empty Definitions:</span><span data-bind="text:empty()"></span>
        </div>
        <div>
            <span>Changed Definitions:</span><span data-bind="text:changed().length"></span>
            <div data-bind="html: changed_html()"></div>
        </div>
    </div>
    <!-- /ko -->
    <!-- ko if: mode()=='committed' -->
    <div id="committed" data-bind="with: review_data">
        <h1>Committed Changes</h1> 
        <div>
            <span>Unchanged Entries verified:</span><span data-bind="text:unchanged()"></span>
        </div>
        <div>
            <span>Empty Definitions:</span><span data-bind="text:empty()"></span>
        </div>
        <div>
            <span>Changed Definitions:</span><span data-bind="text:changed().length"></span>
            <div data-bind="html: changed_html()"></div>
        </div>
    </div>
    <!-- /ko -->
    <!-- ko if:loading() -->
        <span data-bind="text:processingStatus"></span><img src='<?php echo $webroot."/interface/pic/ajax-loader.gif";?>'/>
    <!-- /ko -->
</script>
<script type="text/javascript">
    var file_contents=<?php echo json_encode($file_contents); ?>;
    var header_data=ko.observableArray();
    for(var h_index=0;h_index<file_contents[0].length;h_index++)
    {
        var entry=
                {
                    idx: h_index,
                    text: file_contents[0][h_index]
        }
        header_data.push(entry);
    }

    var vm_file_display={
        filedata: {
            
            start: ko.observable(1),
            end: ko.observable(20),
            total:ko.observable(file_contents.length),
            header: header_data,            
            constant_choice: ko.observable(),
            definition_choice: ko.observable(),            
            mode: ko.observable("verify"),
            preview_data:
                    {
                        changed: ko.observableArray(),
                        unchanged: ko.observable(0),
                        empty: ko.observable(0),
                        changed_html:ko.observable()
                    },
            loading: ko.observable(false),
            display_contents: ko.observableArray(),
            review_data:
                        {
                            changed: ko.observableArray(),
                            unchanged: ko.observable(0),
                            empty: ko.observable(0),
                            changed_html:ko.observable()
                        },     
            processingStatus: ko.observable("Please wait")
            },

        select_display_contents: function()
        {
            this.filedata.display_contents.removeAll();
            if(this.filedata.end()>=file_contents.length)
            {
                this.filedata.end(file_contents.length);
            }
            for(var idx=this.filedata.start();(idx<=this.filedata.end());idx++)
            {
                this.filedata.display_contents.push(file_contents[idx]);
            }
        }
    };
    vm_file_display.filedata.constant_choice(vm_file_display.filedata.header()[1]);
    vm_file_display.filedata.definition_choice(vm_file_display.filedata.header()[3]);
    vm_file_display.select_display_contents(1,20);
    ko.applyBindings(vm_file_display);
    var translations=[];    
    function previewChanges()
    {
        translations=[];
        var constant_index=vm_file_display.filedata.constant_choice().idx;
        var definition_index=vm_file_display.filedata.definition_choice().idx;

        for(var idx=1;idx<file_contents.length;idx++)
        {
            var entry=file_contents[idx];
            var translation=[];
            translation[0]=entry[constant_index];
            translation[1]=entry[definition_index];
            translations[idx-1]=translation;
        }
        vm_file_display.filedata.mode("processing");
        vm_file_display.filedata.processingStatus("Generating preview data. Please Wait");
        vm_file_display.filedata.loading(true);
        
        $.post("commit_csv.php",
            {
                lang_id: <?php echo json_encode($lang_id);?>
                ,translations:JSON.stringify(translations)
                ,preview: true
            },
        function(data)
        {
            vm_file_display.filedata.mode("preview");
            vm_file_display.filedata.loading(false);
            vm_file_display.filedata.preview_data.unchanged(data.unchanged);
            vm_file_display.filedata.preview_data.empty(data.empty);
            vm_file_display.filedata.preview_data.changed.removeAll();
            vm_file_display.filedata.preview_data.changed(data.changed);
            vm_file_display.filedata.preview_data.changed_html(data.html_changes);   
        }
        ,"json"
        );
    }
    
    function commitChanges()
    {
        vm_file_display.filedata.mode("processing");
        vm_file_display.filedata.processingStatus("Committing changes. Please Wait");
        vm_file_display.filedata.loading(true);    
        $.post("commit_csv.php",
            {
                lang_id: <?php echo json_encode($lang_id);?>
                ,translations:JSON.stringify(translations)
                ,preview: false
            },
            function(data)
            {
                vm_file_display.filedata.mode("committed");
                vm_file_display.filedata.loading(false);
                vm_file_display.filedata.review_data.unchanged(data.unchanged);
                vm_file_display.filedata.review_data.empty(data.empty);
                vm_file_display.filedata.review_data.changed.removeAll();
                vm_file_display.filedata.review_data.changed(data.changed);                
                vm_file_display.filedata.review_data.changed_html(data.html_changes);                
                
            }
            ,"json"
        );
}    
</script>

