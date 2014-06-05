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
            a.attr("href","");
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
        fin_section.find("li:first").next().after(create_link("report",'services_by_provider.php',"<?php echo xlt('Svcs by Prov'); ?>"))
                .next().next().after(create_link("report",'receipts_by_method_report.php',"<?php echo xlt('Rec Summary'); ?>")); 
        
        fin_section.append(create_link("report",'export_accounting_transactions.php',"<?php echo xlt('Acct Export'); ?>")); 
        <?php if ($GLOBALS['gbl_menu_projects']) {?> 
                fin_section.append(create_link("report",'restricted_projects_report.php',"<?php echo xlt('Projects'); ?>")); 
        <?php } ?>

            
    }
    
    function add_blank_forms()
    {
        var fee_sheet_link=find_link("return repPopup('../patient_file/printed_fee_sheet.php')").parent();
        var blank_forms=fee_sheet_link.parent();
        var first = blank_forms.find("li:first");
        var demo = create_link("report","../patient_file/summary/demographics_print.php?isform=0","<?php echo xlt("Demographics");?>");
        first.after(demo);
        first.hide();
        var demo_all=create_link("report","../patient_file/summary/demographics_print.php?isform=1","<?php echo xlt("Demographics (All Values)");?>");
        var patient=create_link("report","../patient_file/summary/demographics_print.php?patientid=-1&isform=0","<?php echo xlt('Patient'); ?>");
        demo.after(demo_all);
        demo_all.after(patient);
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
    function remove_fee_menus()
    {
        var fees=$("li span:contains('Fees')").parent().parent();
        fees.find("#npa0").parent().remove();
        fees.find("#edi0").parent().remove();
    }
    function remove_eligibility_reports()
    {
        var edi_270=find_link("edi_270.php").parent().remove();
        var edi_271=find_link("edi_271.php").parent().remove();
    }
    
    function create_button(title,targetURL,targetWindow,id)
    {
        var retval=$("<a onclick='top.restoreSession()' id='"+id+"'></a>");
        var caption=$("<span>"+title+"</span>");
        retval.append(caption);
        retval.attr("target",targetWindow);
        retval.attr("href",targetURL);
        retval.addClass("css_button");
        return retval;
        
    }
    function add_ippf_buttons()
    {
        var support_link=$("#support_link");
        
        var logout=create_button("<?php echo xlt('Logout') ?>","../logout.php","_top","logout_link");
        support_link.after(logout);
        logout.css("clear","left");

        var user_guide=create_button("<?php echo xlt('Online User Guide') ?>","http://open-emr.org/wiki/index.php/OpenEMR_4.1.2_Users_Guid","_blank","help_link");
        support_link.after(user_guide);
    }
    function setup_ippf_custom()
    {
        add_finanical_reports();
        add_blank_forms();
        remove_records_menu();
        remove_fee_menus();
        remove_eligibility_reports();
        add_ippf_buttons();
    }
    
    function newEncounterForNewPatient()
    {
        var f = document.forms[0];
        if ( f.cb_top.checked && f.cb_bot.checked ) {
            var encounter_frame = getEncounterTargetFrame('enc');
            if ( encounter_frame != undefined )  {
                loadFrame('nen0',encounter_frame, '<?php echo $primary_docs['nen'][2]; ?>');
                setRadio(encounter_frame, 'ens');
            }
        }        
    }
</script>
<script>
<?php } ?>