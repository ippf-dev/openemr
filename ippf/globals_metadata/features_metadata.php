<?php
/**
 * 
 */
$GLOBALS['GLOBALS_METADATA']['IPPF Features']=
array(
    'gbl_rapid_workflow' => array(
      xl('Rapid Workflow Option'),
      array(
        '0'        => xl('None'),
        'LBFmsivd' => xl('MSI (requires LBFmsivd form)'),
        'fee_sheet' => xl('Fee Sheet and Checkout'),
      ),
      '0',                              // default
      xl('Activates custom work flow logic')
    ),
    
    'gbl_new_acceptor_policy' => array(
      xl('New Acceptor Policy'),
      array(
        '0' => xl('Not applicable'),
        '1' => xl('Simplified; Contraceptive Start Date on Tally Sheet'),
        /*************************************************************
        '2' => xl('Contraception Form; New Users to IPPF/Association'),
        *************************************************************/
        '3' => xl('Contraception Form; Acceptors New to Modern Contraception'),
      ),
      '1',                              // default
      xl('Applicable only for family planning clinics')
    ),
    
    'gbl_min_max_months' => array(
      xl('Min/Max Inventory as Months'),
      'bool',                           // data type
      '1',                              // default = true
      xl('Min/max inventory is expressed as months of supply instead of units')
    ),
    
    'gbl_restrict_provider_facility' => array(
      xl('Restrict Providers by Facility'),
      'bool',                           // data type
      '0',                              // default
      xl('Limit service provider selection according to the facility of the logged-in user.')
    ),
    
    'gbl_checkout_line_adjustments' => array(
      xl('Checkout Adjustments at Line Level'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Discounts at checkout time may be entered per line item.')
    ),

    'gbl_auto_create_rx' => array(
      xl('Automatically Create Prescriptions'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Prescriptions may be created from the Fee Sheet.')
    ),
    
    'gbl_checkout_receipt_note' => array(
      xl('Checkout Receipt Note'),
      'text',                           // data type
      '',
      xl('This note goes on the bottom of every checkout receipt.')
    ),    
    
    'gbl_custom_receipt' => array(
      xl('Custom Checkout Receipt'),
      array(
        '0'                                => xl('None'),
        'checkout_receipt_general.inc.php' => xl('Guyana'),
        'checkout_receipt_panama.inc.php'  => xl('Panama'),
      ),
      '0',                              // default
      xl('Present an additional PDF custom receipt after checkout.')
    ),
    'gbl_ma_ippf_code_restriction' => array(
      xl('Allow More than one MA/IPPF code mapping'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Disable the restriction of only one IPPF code per MA code in superbill')
    ),    
);
?>