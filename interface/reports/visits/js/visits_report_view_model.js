/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var visits_view_model=
{
    title: ko.observable(report_title),
    parameters: {},
    results: {
        headers: ko.observableArray(),
        data_rows: ko.observableArray(),
        report_type: ko.observable("Summary"),
        loading: ko.observable(false)
    }
};

function provider_display_name(data)
{
    var retval=data.lname;
    if((data.lname.length>0) && (data.fname.length>0))
    {
        retval+=",";
    }
    retval+=data.fname;
    return retval;
}
function manage_clinic_select_all(newValue)
{
    if(newValue)
    {
        for(var idx=1;idx<visits_view_model.parameters.clinics().length;idx++)
        {
            visits_view_model.parameters.clinics()[idx].selected(false);
        }
    }
    else
    {
        
    }
}

function manage_clinic_select_one(newValue)
{
    if(newValue)
    {
        visits_view_model.parameters.clinics()[0].selected(false);
    }
}

function manage_providers_select_all(newValue)
{
    if(newValue)
    {
        for(var idx=1;idx<visits_view_model.parameters.providers().length;idx++)
        {
            visits_view_model.parameters.providers()[idx].selected(false);
        }
    }
    else
    {
        
    }
}

function manage_providers_select_one(newValue)
{
    if(newValue)
    {
        visits_view_model.parameters.providers()[0].selected(false);
    }
}

function manage_categories_select_all(newValue)
{
    if(newValue)
    {
        for(var idx=1;idx<visits_view_model.parameters.categories().length;idx++)
        {
            visits_view_model.parameters.categories()[idx].selected(false);
        }
    }
    else
    {
        
    }
}

function manage_categories_select_one(newValue)
{
    if(newValue)
    {
        visits_view_model.parameters.categories()[0].selected(false);
    }
}
function setup_parameters()
{
    var parameters=visits_view_model.parameters;
    parameters.clinics=ko.observableArray();
    parameters.clinics_mode=ko.observable("summary");
    parameters.clinics_details=ko.observable(false);

    parameters.clinics_mode.subscribe(function(value)
    {
        if(value==="summary")
        {
            parameters.clinics_details(false);
        }
        else if(value==="details")
        {
            parameters.clinics_details(true);
        }
    });

    parameters.period_size=ko.observable(period_options[0]);
    for(var clinic_idx=0;clinic_idx<clinics.length;clinic_idx++)
    {
        
        var new_select= ko.observable(false);
        if(clinics[clinic_idx]=='All')
        {
            new_select.subscribe(manage_clinic_select_all);
            new_select(true);
        }
        else
        {
            new_select.subscribe(manage_clinic_select_one);
            
        }
        parameters.clinics.push(
                    {   name: clinics[clinic_idx]
                       ,selected: new_select
                       
                    }
                );
    }
    
    parameters.providers=ko.observableArray();
    parameters.providers_mode=ko.observable("summary");
    parameters.providers_details=ko.observable(false);
    parameters.providers_mode.subscribe(function(value)
    {
        if(value==="summary")
        {
            parameters.providers_details(false);
        }
        else if(value==="details")
        {
            parameters.providers_details(true);
        }
    });    
    
    for(var providers_idx=0;providers_idx<providers.length;providers_idx++)
    {
        
        providers[providers_idx].selected=ko.observable(false);
        if(providers_idx===0)
        {
            providers[providers_idx].selected.subscribe(manage_providers_select_all);
            providers[providers_idx].selected(true);
        }
        else
        {
            providers[providers_idx].selected.subscribe(manage_providers_select_one);
        }
        parameters.providers.push(providers[providers_idx]);
    }
    
    parameters.categorize_services=ko.observable(false);
    parameters.services_mode=ko.observable("summary");
    parameters.services_mode.subscribe(function(value)
    {
        if(value==="summary")
        {
            parameters.categorize_services(false);
        }
        else if(value==="details")
        {
            parameters.categorize_services(true);
        }
    });    
    
    parameters.categories=ko.observableArray();
    for(var categories_idx=0;categories_idx<service_categories.length;categories_idx++)
    {
        var category_select;
        if(categories_idx===0)
        {
            category_select=ko.observable(true);
            category_select.subscribe(manage_categories_select_all);
        }
        else
        {
            category_select=ko.observable(false);
            category_select.subscribe(manage_categories_select_one);
        }
        var category_info={name: service_categories[categories_idx]
            ,selected: category_select
        };
        parameters.categories.push(category_info);
    }
    
}
function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}

function data_heading_to_content_type(data)
{
    if((data.indexOf("_")==-1) &&  (data[0] === data[0].toUpperCase()))
    {
        return "service";
    }
    else
    {
        return data;
    }
}

function cell_data(data,type)
{
    var self=this;
    this.data=(data===null)? 0 : data;
    this.type=type;
    this.content="";
    this.trend="none";
    return self;
}

function setup_results_array(rows,columns,label_columns)
{
    var retval=new Array(rows);
    for(var row_idx=0;row_idx<rows;row_idx++)
    {
        retval[row_idx]=new Array(columns);
        for(var col_idx=0;col_idx<columns;col_idx++)
        {
            retval[row_idx][col_idx]=new cell_data(col_idx >= label_columns ? 0 : "","placeholder");
        }
    }
    return retval;
}
function build_data_table_summary_only(data)
{
    var results_table=setup_results_array(visits_view_model.results.values_list.length,visits_view_model.results.periods.length+1,1);
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        var period_idx=visits_view_model.results.periods.indexOf(cur_data.period);
        
        for(var value in cur_data)
        {        
            var value_idx=visits_view_model.results.values_list.indexOf(value)
            if(value_idx!==-1)
            {
                results_table[value_idx][0]=new cell_data(value_tag_to_descriptions(value),"tag");
                results_table[value_idx][0].content=data_heading_to_content_type(value);
                results_table[value_idx][period_idx+1]=new cell_data(cur_data[value],"value");
            }
        }
    }
    visits_view_model.results.report_type("Summary");
    return results_table;
}


function build_data_table_providers(data)
{
    
    visits_view_model.results.report_type("Providers");
    var results_table=setup_results_array(visits_view_model.results.providers_list.length*visits_view_model.results.values_list.length,visits_view_model.results.periods.length+2,2);    
    var num_values=visits_view_model.results.values_list.length;
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        var provider_idx=visits_view_model.results.providers_list.indexOf(cur_data.provider_id);
        var period_idx=visits_view_model.results.periods.indexOf(cur_data.period);
        
        for(var value in cur_data)
        {        
            var value_idx=visits_view_model.results.values_list.indexOf(value)
            if((value_idx!==-1) && (provider_idx!==-1))
            {
                if(value_idx===0)
                {
                    results_table[provider_idx*num_values +value_idx][0]=new cell_data(cur_data.provider_id,"provider");
                }
                else
                {
                    results_table[provider_idx*num_values +value_idx][0]=new cell_data("","provider");
                }
                results_table[provider_idx*num_values +value_idx][1]=new cell_data(value_tag_to_descriptions(value),"tag");
                results_table[provider_idx*num_values +value_idx][0].content=data_heading_to_content_type(value);
                results_table[provider_idx*num_values +value_idx][period_idx+2]=new cell_data(cur_data[value],"value");
            }
        }
    }
    
    visits_view_model.results.headers.unshift("Provider");
    return results_table;
}

function build_data_table_clinic_only(data)
{
    
    visits_view_model.results.report_type("Providers");
    var results_table=setup_results_array(visits_view_model.results.clinics_list.length*visits_view_model.results.values_list.length,visits_view_model.results.periods.length+2,2);    
    var num_values=visits_view_model.results.values_list.length;
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        var facility_idx=visits_view_model.results.clinics_list.indexOf(cur_data.facility);
        var period_idx=visits_view_model.results.periods.indexOf(cur_data.period);
        
        for(var value in cur_data)
        {        
            var value_idx=visits_view_model.results.values_list.indexOf(value)
            if((value_idx!==-1) && (facility_idx!==-1))
            {
                if(value_idx===0)
                {
                    results_table[facility_idx*num_values +value_idx][0]=new cell_data(cur_data.facility,"clinic");
                }
                else
                {
                    results_table[facility_idx*num_values +value_idx][0]=new cell_data("","clinic");
                }
                results_table[facility_idx*num_values +value_idx][1]=new cell_data(value_tag_to_descriptions(value),"tag");
                results_table[facility_idx*num_values +value_idx][0].content=data_heading_to_content_type(value);
                results_table[facility_idx*num_values +value_idx][period_idx+2]=new cell_data(cur_data[value],"value");
            }
        }
    }
    
    visits_view_model.results.headers.unshift("Clinic");
    return results_table;
}


function build_data_table_clinics_and_providers(data)
{
    var clinic_list=[];
    for(var clinic in visits_view_model.results.clinics_map)
    {
        clinic_list.push(clinic);
    }
    clinic_list.sort;
    var number_clinic_providers=0;
    var clinic_position_map={};
    for(var clinic_idx=0;clinic_idx<clinic_list.length;clinic_idx++)
    {
        var clinic=clinic_list[clinic_idx];
        var cur_clinic=visits_view_model.results.clinics_map[clinic]
        cur_clinic.provider_list=[];
        for(var provider in cur_clinic.providers)
        {
            if(provider!== "undefined")
            {
                cur_clinic.provider_list.push(provider);              
            }
        }
        cur_clinic.provider_list.sort();
        clinic_position_map[clinic]=number_clinic_providers;
        number_clinic_providers+=cur_clinic.provider_list.length*visits_view_model.results.values_list.length;
    }
    var results_table=setup_results_array(number_clinic_providers,visits_view_model.results.periods.length+3,3);    
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        var facility_position=clinic_position_map[cur_data.facility];
        var cur_clinic=visits_view_model.results.clinics_map[cur_data.facility];
        var provider_position=cur_clinic.provider_list.indexOf(cur_data.provider_id)*visits_view_model.results.values_list.length;
        var base_position=facility_position+provider_position;
        var period_idx=visits_view_model.results.periods.indexOf(cur_data.period);
        if(provider_position<0)
        {
            // Not sure why provider would be undefined here
        }        
        for(var value in cur_data)
        {        

            var value_idx=visits_view_model.results.values_list.indexOf(value)

            if((value_idx!==-1) && base_position>=0)
            {
                if(value_idx===0)
                {
                    if(provider_position===0)
                    {
                        results_table[base_position +value_idx][0]=new cell_data(cur_data.facility,"clinic");                   
                    }
                    else
                    {
                        results_table[base_position +value_idx][0]=new cell_data("","clinic");
                    }
                    results_table[base_position +value_idx][1]=new cell_data(cur_data.provider_id,"provider");
                }
                else
                {
                    results_table[base_position +value_idx][0]=new cell_data("","clinic");
                    results_table[base_position +value_idx][1]=new cell_data("","provider");
                }
                results_table[base_position +value_idx][2]=new cell_data(value_tag_to_descriptions(value),"tag");
                results_table[base_position +value_idx][0].content=data_heading_to_content_type(value);
                results_table[base_position +value_idx][period_idx+3]=new cell_data(cur_data[value],"value");
            }
        }        
    }


    visits_view_model.results.headers.unshift("Provider");
    visits_view_model.results.headers.unshift("Clinic");
    return results_table;
}
function build_data_table(data)
{
    
    var clinics_details=!isEmpty(visits_view_model.results.clinics_map);
    var providers_details=!isEmpty(visits_view_model.results.providers_map);
    if(clinics_details)
    {
        visits_view_model.results.clinics_list=[];
        for(var clinic in visits_view_model.results.clinics_map)
        {
            visits_view_model.results.clinics_list.push(clinic);
        }
        visits_view_model.results.clinics_list.sort();
        
    }
    else if(providers_details)
    {
        visits_view_model.results.providers_list=[];
        for(var provider in visits_view_model.results.providers_map)
        {
            if(provider!=="undefined")
            {
                visits_view_model.results.providers_list.push(provider);            
            }
        }
        visits_view_model.results.providers_list.sort();
    }
    
    visits_view_model.results.headers.removeAll();
    

    var results_table;
    if(!clinics_details && !providers_details)
    {
        results_table=build_data_table_summary_only(data);
        visits_view_model.title(report_title);
    }
    else
    {
        if(providers_details)
        {
            results_table=build_data_table_providers(data);
            visits_view_model.title(title_by_provider);
        }
        else
        {
            if(clinics_details)
            {
                if(!data[0].hasOwnProperty("provider_id"))
                {
                    results_table=build_data_table_clinic_only(data);
                    visits_view_model.title(title_by_clinic);
                }
                else
                {
                    results_table=build_data_table_clinics_and_providers(data);
                    visits_view_model.title(title_by_clinic_and_provider);
                }
            }
        }
    }
    
   
    visits_view_model.results.headers.push("");
    for(var period_idx=0;period_idx<visits_view_model.results.periods.length;period_idx++)
    {
        visits_view_model.results.headers.push(visits_view_model.results.periods[period_idx]);
    }
    
    analyze_trends(results_table);
    
    visits_view_model.results.data_rows(results_table);
//    alert(JSON.stringify(results_table));
//    alert(JSON.stringify(visits_view_model.results.clinics_list));
//    alert(JSON.stringify(data));

}

function build_provider_integer_map()
{
    provider_integer_map={};
    for(var idx=0;idx<providers.length;idx++)
    {
        var cur_provider=providers[idx];
        provider_integer_map[cur_provider.id]=provider_display_name(cur_provider);
    }
//    provider_integer_map['0']="~~Unknown~~";
    provider_integer_map[-1]="~~~Clinic Total~~~";
    provider_integer_map[0]="~~Unknown~~";
}
function provider_integer_to_name(id)
{
    if(provider_integer_map.hasOwnProperty(id))
    {
        return provider_integer_map[id];
        
    }
    else
    {
        return "~~Unknown~~";
    }
}

function isNumeric(obj)
{
    return !jQuery.isArray( obj ) && (obj - parseFloat( obj ) + 1) >= 0;
}

function parseTrendValue(obj)
{
    return isNumeric(obj) ? parseFloat(obj) : null;
}
function analyze_trends(data)
{
    for(var row_idx=0;row_idx<data.length;row_idx++)
    {
        var row=data[row_idx];
        var prev_value=parseTrendValue(row[0].data);
        for(var col_idx=1;col_idx<row.length;col_idx++)
        {
            var cur_value=parseTrendValue(row[col_idx].data);
            if((prev_value!==null) && (cur_value!==null))
            {
                if((prev_value*0.8)>cur_value)
                {
                    row[col_idx].trend="decline";
                }
            }
            prev_value=cur_value;
        }
    }
}

function process_results(data,status, jqXHR)
{
    visits_view_model.results.periods_map={};
    visits_view_model.results.clinics_map={};
    visits_view_model.results.providers_map={};
    visits_view_model.results.values_map={};
    visits_view_model.results.values_list=[];
    
    
    for(var data_idx=0;data_idx<data.length;data_idx++)
    {
        var cur_data=data[data_idx];
        // Convert Provider IDs to names
        if(cur_data.hasOwnProperty("provider_id"))
        {
            cur_data.provider_id=provider_integer_to_name(cur_data.provider_id);
        }
        
        
        visits_view_model.results.periods_map[cur_data.period]=cur_data.period;
        for(var value in cur_data)
        {
            if((value!=="provider_id" )&& (value!=="facility") && (value!=="period"))
            if(!visits_view_model.results.values_map.hasOwnProperty(value))
            {
                visits_view_model.results.values_map[value]=value;
                visits_view_model.results.values_list.push(value);
            }
        }
        if(cur_data.hasOwnProperty("facility"))
        {
            // Handle facility first, then provider id if present
            var facility_name=cur_data.facility;
            var cur_facility_data={};
            if(visits_view_model.results.clinics_map.hasOwnProperty(facility_name))
            {
                cur_facility_data=visits_view_model.results.clinics_map[facility_name];
            }
            else
            {
                visits_view_model.results.clinics_map[facility_name]=cur_facility_data;
                cur_facility_data.facility=facility_name;
            }
            if(cur_data.hasOwnProperty("provider_id"))
            {
                var cur_providers={};
                if(cur_facility_data.hasOwnProperty("providers"))
                {
                    cur_providers=cur_facility_data.providers;
                }
                else
                {
                    cur_providers=cur_facility_data.providers={};
                }
                if(!cur_providers.hasOwnProperty(cur_data["provider_id"]))
                {
                    cur_providers[cur_data["provider_id"]]=cur_data["provider_id"];
                }
            }
        }
        else
        {
            // Handle provider_id separately if no facilities are specified.
            if(cur_data.hasOwnProperty("provider_id"))
            {
                var cur_provider;
                if(visits_view_model.results.providers_map.hasOwnProperty(cur_data.provider_id))
                {
                    cur_provider=visits_view_model.results.providers_map[cur_data.provider_id];
                }
                else
                {
                    visits_view_model.results.providers_map[cur_data.provider_id]=
                            {
                                provider_id: cur_data.provider_id
                            };
                }
            }
            
        }
    }
    
    // Generate an ordered list of the periods
    visits_view_model.results.periods=[]
    for(var period in visits_view_model.results.periods_map)
    {
        visits_view_model.results.periods.push(period);
    }
    visits_view_model.results.periods.sort();
    
    build_data_table(data);
    visits_view_model.results.loading(false);
}

function search_visits()
{
    var search_parameters={};
    search_parameters.from=$("#form_from_date").val();
    search_parameters.to=$("#form_to_date").val();
    
    search_parameters.clinics_details=visits_view_model.parameters.clinics_details();
    
    search_parameters.providers_details=visits_view_model.parameters.providers_details();
    
    search_parameters.period_size=visits_view_model.parameters.period_size().id;
    
    search_parameters.categorize_services=visits_view_model.parameters.categorize_services();
    
    if(search_parameters.clinics_details)
    {
        search_parameters.clinic_filter=[];
        for(var clinic_idx=0;clinic_idx<visits_view_model.parameters.clinics().length;clinic_idx++)
        {
            var cur_clinic=visits_view_model.parameters.clinics()[clinic_idx];
            
            if(cur_clinic.selected())
            {
                search_parameters.clinic_filter.push(cur_clinic.name)
                if(cur_clinic.name==="All")
                {
                    clinic_idx+=visits_view_model.parameters.clinics().length;
                }
            }
        }
    }
    
    if(search_parameters.providers_details)
    {
        search_parameters.provider_filter=[];
        for(var provider_idx=0;provider_idx<visits_view_model.parameters.providers().length;provider_idx++)
        {
            var cur_provider=visits_view_model.parameters.providers()[provider_idx];
            
            if(cur_provider.selected())
            {
                search_parameters.provider_filter.push(cur_provider.id)
                if(cur_provider.id==="ALL")
                {
                    provider_idx+=visits_view_model.parameters.clinics().length;
                }
            }
        }
    }
    
    if(search_parameters.categorize_services)
    {
        search_parameters.category_filter=[];
        for(var category_idx=0;category_idx<visits_view_model.parameters.categories().length;category_idx++)
        {
            var cur_category=visits_view_model.parameters.categories()[category_idx];
            if(cur_category.selected())
            {
                search_parameters.category_filter.push(cur_category.name);
                if(category_idx===0)
                {
                    category_idx+=visits_view_model.parameters.categories().length;
                }
            }
        }
    }
    visits_view_model.results.loading(true);
    
    $.ajax(query_ajax,
    {
        data: {parameters: JSON.stringify(search_parameters) }
        ,dataType: "json"
        ,method: "POST"
        ,success: process_results
    });
}


setup_parameters();
build_provider_integer_map();
ko.applyBindings(visits_view_model);