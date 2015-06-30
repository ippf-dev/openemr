<?php

// Copyright (C) 2015 Kevin Yeh <kevin.y@integralemr.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 3
// of the License, or (at your option) any later version.
?>
<script type="text/html" id="visits-parameters">
    <table class="parameters">
        <tbody>
            <tr>
                <td>
                    <div class='label'>Clinics&nbsp;<!-- <span data-bind="visible:(!clinics_details())">Summary Only</span>--></div> 
                    <div> <input type="radio" name="clinicMode" value="summary" data-bind="checked: clinics_mode"/><?php echo xlt("All Clinics - Summary"); ?></div>
                    <div> <input type="radio" name="clinicMode" value="details" data-bind="checked: clinics_mode"/><?php echo xlt("Clinic Details - Select multiple clinics:"); ?></div>
                    <div data-bind="foreach: clinics, visible:clinics_details()">
                        <div>
                            <input type="checkbox" data-bind="checked: selected"/>
                            <span data-bind="text: name"></span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class='label'>Providers&nbsp;<!-- <span data-bind="visible:(!providers_details())">Summary Only</span> --></div>
                    <div> <input type="radio" name="providersMode" value="summary" data-bind="checked: providers_mode"/><?php echo xlt("All Providers - Summary"); ?></div>
                    <div> <input type="radio" name="providersMode" value="details" data-bind="checked: providers_mode"/><?php echo xlt("Provider Details - Select multiple providers:"); ?></div>
                    <div data-bind="foreach: providers, visible:providers_details()">
                        <!--ko if: active!=0 -->
                            <div>
                                <input type="checkbox" data-bind="checked: selected"/>
                                <span data-bind="text: provider_display_name($data)"></span>
                            </div>
                        <!-- /ko -->
                    </div>
                </td>
                <td>
                    <div class='label'>Services</div>
                    <div> <input type="radio" name="servicesMode" value="summary" data-bind="checked: services_mode"/><?php echo xlt("All Service Categories - Summary"); ?></div>
                    <div> <input type="radio" name="servicesMode" value="details" data-bind="checked: services_mode"/><?php echo xlt("Service Category Details - Select multiple categories:"); ?></div>
                    <div data-bind="foreach: categories, visible:categorize_services()">
                        <div>
                            <input type="checkbox" data-bind="checked: selected"/>
                            <span data-bind="text: name"></span>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</script>
<script type="text/html" id="visits-periods">
    <span class='label'><?php echo xlt("Periods:"); ?></span>
    <select data-bind="options: period_options,optionsText: 'description', value: period_size"></select>
</script>
<script type="text/html" id="visits-execute">
    <button data-bind="click: search_visits"><?php echo xlt("Submit"); ?></button>
    <button data-bind="click: export_csv"><?php echo xlt("Export to CSV"); ?></button>
    <button data-bind="click: print_window"><?php echo xlt("Print"); ?></button>

</script>