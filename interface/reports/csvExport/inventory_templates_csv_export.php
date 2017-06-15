<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script src='<?php echo $web_root; ?>/library/js/jquery-1.9.1.min.js'></script>
<script type="text/javascript">
    function export_to_csv(elem)
    {
        var data_table = $("#mymaintable");
        var json_data=[];
        var header=data_table.find("thead > tr > th");
        var header_data=[];
        header.each(function(idx,elem)
        {
            header_data.push($(elem).text());
        });
        json_data.push(header_data)
        
        var content=data_table.find("tbody > tr");
        content.each(function(idx,elem)
        {
            var row_data=[];
            $(elem).find("td").each(
                    function(idx,elem)
                    {
                        var colspan=parseInt($(elem).attr("colspan"));
                        row_data.push(elem.innerHTML==='&nbsp;' ? '' : elem.innerHTML);
                        for(var idx=1;idx<colspan;idx++)
                        {                        
                            row_data.push("");
                        }
                    });
            json_data.push(row_data);
        });
        $("#jsonData").val(JSON.stringify(json_data));
        $("#generate_csv").get(0).submit();
    }
    var print_button = $("#the_print_button");
    var export_button=$("<input type='button'/>");
    export_button.val("<?php echo xla("Export to CSV"); ?>");
    export_button.click(export_to_csv);
    print_button.after(export_button);
</script>

<form method='post' action='csvExport/generate_csv_from_json.php' id='generate_csv'>
    <input type='hidden' name='jsonData' id='jsonData'/>
    <input type='hidden' name='filename' value='InventoryPriceList'/>
</form>
