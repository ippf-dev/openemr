/* 
 * Copyright (C) 2014 Kevin Yeh <kevin.y@integralemr.com>
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

/**
 * Moves the description column from the end to the position before "price"
 */

function arrange_billing_columns()
{
    var billing_table_marker=$("table td.billcell:first");
    var billing_table=billing_table_marker.parent().parent().parent("table");
    var header_row = billing_table.find("tr:first");
    var header_cells=header_row.find("td > b");
    var new_column_index=-1;
    header_cells.each(function (idx,elem)
        {
            // Needs the translated text for the price header for comparison
            if($(elem).text()===translated_price_header)
            {
                new_column_index=idx;
            }
        }
    );
    if(new_column_index===-1)
    {
        // If we couldn't find the price column, then just abort without changes.
        return;
    }
    var rows=billing_table.find("tr");
    rows.each(function(idx,elem){
        var current_row=rows.eq(idx);
        var current_cells=current_row.find("td");
        var description_idx=current_cells.length-1;        
        var description_cell=current_cells.eq(description_idx);
        current_cells.eq(new_column_index).before(description_cell);
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