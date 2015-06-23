<?php

// Copyright (C) 2015 Kevin Yeh <kevin.y@integralemr.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 3
// of the License, or (at your option) any later version.
?>
<script type="text/javascript">
function value_tag_to_descriptions(tag)
{
    if(tag==='active_days')
    {
        return '<?php echo xlt('Active Days'); ?>';
    }
    else if(tag==='number_clients')
    {
        return '<?php echo xlt('Number of Unique Clients'); ?>';
    }
    else if(tag==='number_visits')
    {
        return '<?php echo xlt('Number of Visits'); ?>';
    }
    else if(tag==='number_services')
    {
        return '<?php echo xlt('Number of Services'); ?>';
    }
    else if(tag==='daily_clients')
    {
        return '<?php echo xlt('Average Clients (Daily)'); ?>';
    }
    else if(tag==='daily_services')
    {
        return '<?php echo xlt('Average Services (Daily)'); ?>';
    }
    else if(tag==='services_per_visit')
    {
        return '<?php echo xlt('Average Services/Visit'); ?>';        
    }
    else
    {
        return tag;
    }    
}
    var year_month_date=/\d\d\d\d.\d\d/
    var quarters_date=/\d\d\d\d.[Q]\d/
    var month_names=
    <?php
        $month_names=array(xl("Jan"),xl("Feb"),xl("Mar"),xl("Apr"),xl("May"),xl("Jun"),xl("Jul"),xl("Aug"),xl("Sep"),xl("Oct"),xl("Nov"),xl("Dec"));
        echo json_encode($month_names);
    ?>;
    function format_header(header)
    {
       if(year_month_date.test(header))
       {
           var year=header.substr(0,4);
           var month=month_names[parseInt(header.substr(5,7))-1];
           return month + " " + year;
       }
       if(quarters_date.test(header))
       {
           var year=header.substr(0,4);
           var quarter=header.substr(5,7);
           return quarter + " " + year;
       }
       return header
    }

</script>
<script type="text/html" id="visits-results">
    <!-- ko if: loading() -->
    <div><img src='<?php echo $web_root;?>/interface/pic/ajax-loader.gif'/> Loading</div>
    <!-- /ko -->
    <!-- ko if: !loading() -->
        <table class="results">
            <thead>
                <tr data-bind="foreach: headers">
                    <th data-bind="text: format_header($data)"></th>
                </tr>
            </thead>
            <tbody data-bind="foreach: data_rows">
                <tr data-bind="foreach: $data, attr: {content: $data[0].content}">
                    <td data-bind="text: $data.data, attr: {type: $data.type, trend: $data.trend}"></td>

                </tr>
            </tbody>
        </table>
    <!-- /ko -->
</script>
