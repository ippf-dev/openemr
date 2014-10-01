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
    $translations=array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $translations[]=array($data[1],$data[3]);
        }
}
if(count($translations)===0)
{
    echo "Unable to Parse file! Verify File encoding";
    die;
}

?>
<div id="header">
    <h1>
        Verify Contents to apply to <?php echo $lang_description; ?>
    </h1>
    <div>
        Please confirm that special characters were parsed and display correctly before commit!
    </div>
    <input type="button" id="commit" value="Commit"></input>
</div>

<div id="status"></div>
<div id="information"></div>
<script src="<?php echo $web_root; ?>/library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">


    var translations=<?php echo json_encode($translations); ?>;
    var table=$("<table></table>");
    var thead=$("<thead></thead>");
    var tbody=$("<tbody></tbody>");
    table.append(thead);
    table.append(tbody);

    var constant_idx=0;
    var definition_idx=1;
    var processed_translations=[];
   for(var idx=0;idx<translations.length;idx++)
    {
        var constant_text=translations[idx][constant_idx];
        var definition_text=translations[idx][definition_idx];
        if(constant_text==="constant_name")
        {
            var tr_header=$("<tr></tr>");
            var th_constant_label=$("<th></th>");
            th_constant_label.text(constant_text);
            var th_definition_label=$("<th></th>");
            th_definition_label.text(definition_text);
            
            tr_header.append(th_constant_label);
            tr_header.append(th_definition_label);
            thead.append(tr_header);
        }
        else
        {
            var tr=$("<tr></tr>");
            var td_constant=$("<td></td>");
            td_constant.text(constant_text);
            tr.append(td_constant);        
            var td_definition=$("<td></td>");
            td_definition.text(definition_text);
            tr.append(td_definition);
            tbody.append(tr);
            processed_translations.push(translations[idx]);
        }
    }
    $("#information").append(table);    
    function commit_translations()
    {
        var translation_data=JSON.stringify(processed_translations);
        $("#header").hide();        
        $("#status").text("Processing. Please Wait!");
        $.post("commit_csv.php",
        {
            lang_id: <?php echo json_encode($lang_id);?>
            ,translations:translation_data
        },
        function(data)
        {

            $("#status").html(data);
            $("#status").prepend($("<h1>Processing Complete</h1>"));
        }
        );
    }
    $("#commit").click(commit_translations);
</script>

