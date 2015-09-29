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
    function position_widgets()
    {
        right_column.children("div:first").remove();
        position_billing();
        position_patient_reminders();
        position_left_side();
        position_right_side();
    }

    function find_widget(translated_name)
    {
        var heading=$("span.text > b:contains('"+translated_name+"')");
        var section_heading=heading.parents("div.section-header");
        var widget=section_heading.parent().parent("tr");
        return widget;
    }    
    function find_widget_right(translated_name)
    {
        var heading=$("span.text > b:contains('"+translated_name+"')");
        var section_heading=heading.parents("div.section-header-dynamic");
        return section_heading;
    }
    function prep_left_widget_for_right_side(elem)
    {
        var header=elem.find(".section-header").removeClass("section-header").addClass("section-header-dynamic").css("width","100%");
        var new_div=$("<div></div>");
        elem.children("td").children().each(function(idx,child)
        {
            new_div.append(child);
        });
        return new_div;
    }
    function position_billing()
    {
        var billing_widget=find_widget("<?php echo xl("Billing");?>");
        var billing_div=prep_left_widget_for_right_side(billing_widget);
        right_column.prepend(billing_div);
        style_billing(billing_div);
    }
    function position_patient_reminders()
    {
        var reminders_widget=prep_left_widget_for_right_side(find_widget("<?php echo xl("Patient Reminders");?>"));
        right_column.children("div:nth-child(2)").children().eq(1).after(reminders_widget);
    }
    function position_left_side()
    {
        // Move Messages and Disclosures to the bottom of their table.
        var notes = find_widget("<?php echo xl("Messages"); ?>");
        notes.parent().append(notes);
        notes.parent().append(find_widget("<?php echo xl("Disclosures"); ?>"));
    }
    function group_right_widget(header)
    {
        var header=find_widget_right(header);
        var content=header.next();
        var group=$("<span></span>");
        group.append(header);
        group.append(content);
        return group;
        
    }
    function position_right_side()
    {
        var reminders_heading = find_widget_right("<?php echo xls("Clinical Reminders"); ?>");
        if (reminders_heading.length == 0) return;
        var appointment = group_right_widget("<?php echo xls("Appointments"); ?>");
        reminders_heading.before(appointment);
    }
    function style_billing(elem)
    {
        elem.find("br").remove();
        // Hide insurance balance due and total balance due
        // Should consider displaying just total balance due
        elem.find("tbody tbody > tr:nth-child(2)").hide();
        elem.find("tbody tbody > tr:nth-child(3)").hide();
    }
    var columns=$("body > div > table > tbody > tr > td > div");
    var right_column=columns.eq(1).children("table").children("tbody").children("tr").children("td")
    setup_links();
    positionWidgetButtons("div.section-header");
    positionWidgetButtons("div.section-header-dynamic");
    position_widgets();
</script>