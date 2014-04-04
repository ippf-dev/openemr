function arrange_billing_columns()
{
    var billing_table_marker=$("table td.billcell:first");
    var billing_table=billing_table_marker.parent().parent().parent("table");
    var header_row = billing_table.find("tr:first");
    var header_cells=header_row.find("td");

    var new_column_index=2;
    var rows=billing_table.find("tr");
    rows.each(function(idx,elem){
        var current_row=rows.eq(idx);
        var current_cells=current_row.find("td");
        var description_idx=current_cells.length-1;        
        var description_cell=current_cells.eq(description_idx);
        current_cells.eq(new_column_index).after(description_cell).hide();
    });
    
}
function search_select_to_radio()
{
    var search_select=$("select[name='search_type']");
    var search_radios=$("<span></span>");
    var options=search_select.find("option");
    options.each(function(idx,elem)
    {
       var option=$(elem);
       var new_radio=$("<input/>");
       var label=$("<label>"+option.val()+"</label>")
       new_radio.attr("type","radio");
       new_radio.val(option.val());
       var id_name=option.val()+"_search_type";
       new_radio.attr("id",id_name);
       label.attr("for",id_name);
       new_radio.attr("name","search_type");
       if(idx===0)
       {
           new_radio.prop("checked",true);
       }
       search_radios.append(new_radio);
       search_radios.append(label);
      
    });
    search_select.after(search_radios);
    search_select.remove();
}

arrange_billing_columns();
search_select_to_radio();