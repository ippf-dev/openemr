<?php


?>
<script>
 // Process click on Print link.
    function print_demographics(isform) {
        dlgopen('demographics_print.php?patientid=<?php echo $pid ?>&isform=' + (isform ? 1 : 0), '_blank', 600, 500);
        return false;
    }
    
    function setup_link(title,click)
    {
        var link=$("<a><span>"+title+"</span></a>");
        link.addClass("small")
        link.attr("onclick",click);
        var td=$("<td></td>");
        td.append(link);
        return td;
    }
    function setup_links()
    {
        var dem_data=$("#demographics_ps_expand");
        var dem_header=dem_data.siblings(".section-header");
        var dem_header_row=dem_header.find("tr");

        dem_header_row.append(setup_link("<?php echo xlt("Print Record");?>","print_demographics(false)"));
        dem_header_row.append(setup_link("<?php echo xlt("Print Record (All Values)");?>","print_demographics(true)"));
    }
    setup_links();
</script>