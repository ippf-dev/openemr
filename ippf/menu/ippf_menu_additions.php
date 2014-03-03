<?php if (!empty($GLOBALS['code_types']['IPPF'])) { 
?>
</script>
<script>
    function find_link(target)
    {
        var ret=$("a[onclick*='"+target+"']");
        return ret;
    }
    function create_link(type,url,title)
    {
        var ret=$("<li></li>");
        var a=$("<a>"+title+"</a>");
        if(type=='report')
        {
            a.attr("onclick","return repPopup('"+url+"')");
        }
        ret.append(a);
        return ret;
    }
    
    function add_statistics_reports()
    {
        var last_report=find_link("ippf_daily.php").parent();
        var stats_section=last_report.parent();
        stats_section.append(create_link("report","ippf_c3.php","<?php echo xlt("C3");?>")); 
    }
    function add_finanical_reports()
    {
        var last_report=find_link('sales_by_item.php').parent();
        var fin_section=last_report.parent();        
        <?php if ($GLOBALS['gbl_menu_projects']) {?> 
                fin_section.append(create_link("report",'restricted_projects_report.php',"<?php echo xlt('Projects'); ?>")); 
        <?php } ?>               
    }
    
    function remove_records_menu()
    {
        var records=$("li span:contains('Records')");
        var root=records.parent().parent();
        if(root.find(":contains('<?php echo xla('Patient Record Request');?>')").length);
        {
            root.remove();
        }
    }
    function setup_ippf_custom()
    {
        add_statistics_reports();
        add_finanical_reports();
        remove_records_menu();
    } 
</script>
<script>
<?php } ?>