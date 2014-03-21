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
        var link=$("<a class='css_button_small'><span>"+title+"</span></a>");
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
    function moveWidgetButton(idx,elem)
    {
        var button=$(elem);
        var td=button.parent();
        var tr=td.parent();
        td.css("text-align","right");
        button.css("float","right");
        tr.parent().parent().width("100%");
        tr.parent().parent().parent().width("100%");
        td.remove();
        tr.append(td);
    }
    function positionWidgetButtons(sections)
    {
        var buttons_selector=" tr > td > a.css_button_small:first";
        var sections=$(sections);
        sections.find(buttons_selector).each(moveWidgetButton)
    }    
    setup_links();
    positionWidgetButtons("div.section-header");
    positionWidgetButtons("div.section-header-dynamic");
</script>