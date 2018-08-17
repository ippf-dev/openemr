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
 
<?php if (!empty($GLOBALS['ippf_specific'])) { ?>
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
    function add_blank_forms()
    {
        var fee_sheet_link=find_link("return repPopup('../patient_file/printed_fee_sheet.php')").parent();
        var blank_forms=fee_sheet_link.parent();
        var first = blank_forms.find("li:first");
        var demo = create_link("report","../patient_file/summary/demographics_print.php?isform=0","<?php echo xlt("Demographics");?>");
        first.after(demo);
        first.hide();
        var demo_all=create_link("report","../patient_file/summary/demographics_print.php?isform=1&patientid=-1","<?php echo xlt("Demographics (All Values)");?>");
        demo.after(demo_all);
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
        // Put the new buttons at the end of the form
        var form=$("body > form");
        

        var user_guide=create_button("<?php echo xlt('Online User Guide') ?>","http://open-emr.org/wiki/index.php/OpenEMR_4.1.2_Users_Guide","_blank","help_link");
        form.append(user_guide);
        user_guide.css("clear","left");

        var logout=create_button("<?php echo xlt('Logout') ?>","../logout.php","_top","logout_link");
        form.append(logout);
        logout.css("clear","left");
}
    function setup_ippf_custom()
    {
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
