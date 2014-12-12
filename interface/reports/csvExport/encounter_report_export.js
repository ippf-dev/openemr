// Copyright (C) 2014 Kevin Yeh <kevin.y@integralemr.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.


function export_csv()
{
    var table=$("#encreport_results table");
    var header=table.find("thead tr");
    var data=table.find("tbody tr");
    var header_data=[];
    header.find("th").each(function(idx,elem){
        header_data.push($(elem).text().trim());
    });
    var table_data=[];
    table_data.push(header_data);
    data.each(function(idx,row)
    {
       var row_data=[];
       $(row).find("td").each(function(idx,cell)
       {
           row_data.push($(cell).text().trim());
       });
       table_data.push(row_data);
    });
    $("#jsonData").val(JSON.stringify(table_data));
    $("#generate_csv").get(0).submit();
    
}

function setup_csv_export_form()
{
    var form=$("<form method='post' action='csvExport/generate_csv_from_json.php' id='generate_csv'></form>")
    var json_data=$("<input type='hidden' name='jsonData' id='jsonData'/>");
    var filename=$("<input type='hidden' name='filename' value='visits'/>");
    form.append(json_data);
    form.append(filename);
    $("body").append(form);
}

function setup_export_control()
{
    var export_button=$("<input type='button' value='Save as CSV'/>");
    var print_button=$("#encreport_parameters input:last");
    print_button.after(export_button);
    export_button.on({click: export_csv});
}
function setup_export()
{
    setup_csv_export_form();
    setup_export_control();
}

setup_export();