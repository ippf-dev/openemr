/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



function setup_csv_export_form()
{
    var form=$("<form method='post' action='../csvExport/generate_csv_from_json.php' id='generate_csv'></form>")
    var json_data=$("<input type='hidden' name='jsonData' id='jsonData'/>");
    var filename=$("<input type='hidden' name='filename' value='ServiceAndClientsReport'/>");
    form.append(json_data);
    form.append(filename);
    $("body").append(form);
}
function export_csv()
{
    var full_data=[];
    full_data.push(visits_view_model.results.headers());
//    full_data=full_data.concat(visits_view_model.results.data_rows());
    var table_data=visits_view_model.results.data_rows();
    for(var x_idx=0;x_idx<table_data.length;x_idx++)
    {
        var data_row=[];
        for(var y_idx=0;y_idx<table_data[x_idx].length;y_idx++)
        {
            data_row[y_idx]=table_data[x_idx][y_idx].data;
        }
        full_data.push(data_row);
    }
    
    $("#jsonData").val(JSON.stringify(full_data));
    $("#generate_csv").get(0).submit();
}
function print_window()
{
    window.print();

}

setup_csv_export_form();