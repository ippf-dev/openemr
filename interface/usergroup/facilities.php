<?php
require_once("../globals.php");
require_once("../../library/acl.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/classes/POSRef.class.php");

$alertmsg = '';

/*		Inserting New facility					*/
if (isset($_POST["mode"]) && $_POST["mode"] == "facility" && $_POST["newmode"] != "admin_facility") {
  $insert_id=sqlInsert("INSERT INTO facility SET " .
  "name = '"         . trim(formData('facility'    )) . "', " .
  "phone = '"        . trim(formData('phone'       )) . "', " .
  "fax = '"          . trim(formData('fax'         )) . "', " .
  "street = '"       . trim(formData('street'      )) . "', " .
  "city = '"         . trim(formData('city'        )) . "', " .
  "state = '"        . trim(formData('state'       )) . "', " .
  "postal_code = '"  . trim(formData('postal_code' )) . "', " .
  "country_code = '" . trim(formData('country_code')) . "', " .
  "federal_ein = '"  . trim(formData('federal_ein' )) . "', " .
  "website = '"      . trim(formData('website'     )) . "', " .
  "email = '"      	 . trim(formData('email'       )) . "', " .
  "color = '"  . trim(formData('ncolor' )) . "', " .
  "latitude = '" . trim(formData('latitude')) . "', ".
  "longitude = '" . trim(formData('longitude')) . "', ".
  "service_location = '"  . trim(formData('service_location' )) . "', " .
  "billing_location = '"  . trim(formData('billing_location' )) . "', " .
  "accepts_assignment = '"  . trim(formData('accepts_assignment' )) . "', " .
  "extra_validation = '" . trim(formData('extra_validation')) . "', ".
  "pos_code = '"  . trim(formData('pos_code' )) . "', " .
  "domain_identifier = '"  . trim(formData('domain_identifier' )) . "', " .
  "related_code = '"  . formData('form_related_code') . "', " .
  "related_code_2 = '"  . formData('form_related_code_2') . "', " .
  "attn = '"  . trim(formData('attn' )) . "', " .
  "tax_id_type = '"  . trim(formData('tax_id_type' )) . "', " .
  "primary_business_entity = '"  . trim(formData('primary_business_entity' )) . "', ".
  "facility_npi = '" . trim(formData('facility_npi')) . "'");
}

/*		Editing existing facility					*/
if ($_POST["mode"] == "facility" && $_POST["newmode"] == "admin_facility")
{
	sqlStatement("update facility set
		name='" . trim(formData('facility')) . "',
		phone='" . trim(formData('phone')) . "',
		fax='" . trim(formData('fax')) . "',
		street='" . trim(formData('street')) . "',
		city='" . trim(formData('city')) . "',
		state='" . trim(formData('state')) . "',
		postal_code='" . trim(formData('postal_code')) . "',
		country_code='" . trim(formData('country_code')) . "',
		federal_ein='" . trim(formData('federal_ein')) . "',
		latitude='" . trim(formData('latitude')) . "',
		longitude='" . trim(formData('longitude')) . "',                    
		website='" . trim(formData('website')) . "',
		email='" . trim(formData('email')) . "',
		color='" . trim(formData('ncolor')) . "',
		service_location='" . trim(formData('service_location')) . "',
		billing_location='" . trim(formData('billing_location')) . "',
		accepts_assignment='" . trim(formData('accepts_assignment')) . "',
		extra_validation='" . trim(formData('extra_validation')) . "',                    
		pos_code='" . trim(formData('pos_code')) . "',
		domain_identifier='" . trim(formData('domain_identifier')) . "',
    related_code = '"  . formData('form_related_code') . "',
    related_code_2 = '"  . formData('form_related_code_2') . "',
		facility_npi='" . trim(formData('facility_npi')) . "',
		attn='" . trim(formData('attn')) . "' ,
		primary_business_entity='" . trim(formData('primary_business_entity')) . "' ,
		tax_id_type='" . trim(formData('tax_id_type')) . "' 
	where id='" . trim(formData('fid')) . "'" );
}

?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>

<script type="text/javascript">


$(document).ready(function(){

    // fancy box
    enable_modals();

    // special size for
	$(".addfac_modal").fancybox( {
		'overlayOpacity' : 0.0,
		'showCloseButton' : true,
		'frameHeight' : 600,
		'frameWidth' : 650
	});

    // special size for
	$(".medium_modal").fancybox( {
		'overlayOpacity' : 0.0,
		'showCloseButton' : true,
		'frameHeight' : 600,
		'frameWidth' : 650
	});

});

</script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<style>
</style>

</head>

<body class="body_top">

<div>
 <div>
	<table><tr><td>
        <b><?php xl('Facilities','e'); ?></b>&nbsp;</td><td>
		 <a href="facilities_add.php" class="iframe addfac_modal css_button"><span><?php xl('Add','e');?></span></a>
		 </td></tr>
	</table>
    </div>
    <div class="tabContainer" style="width:auto;">
        <div>
<table cellpadding="1" cellspacing="0" class="showborder" style="width:auto;">
  <tr class="showborder_head" height="22">
    <th style="border-style:1px solid #000"><?php echo xlt('Name'); ?></th>
    <th style="border-style:1px solid #000"><?php echo xlt('Address'); ?></th>
    <th style="border-style:1px solid #000"><?php echo xlt('Phone'); ?></th>
    <th style="border-style:1px solid #000"><?php echo xlt('CLIA Number'); ?></th>
    <th style="border-style:1px solid #000;width:1%"><?php echo xlt('Billing Location'); ?></th>
    <th style="border-style:1px solid #000;width:1%"><?php echo xlt('Service Location'); ?></th>
    <th style="border-style:1px solid #000"><?php echo xlt('Facility Code'); ?></th>
    <th style="border-style:1px solid #000"><?php echo xlt('POS Code'); ?></th>
  </tr>
     <?php
        $fres = 0;
        $fres = sqlStatement("select * from facility order by name");
        if ($fres) {
          $result2 = array();
          for ($iter3 = 0;$frow = sqlFetchArray($fres);$iter3++)
            $result2[$iter3] = $frow;
          foreach($result2 as $iter3) {
			$varstreet="";//these are assigned conditionally below,blank assignment is done so that old values doesn't get propagated to next level.
			$varcity="";
			$varstate="";
          $varstreet=$iter3{street };
          if ($iter3{street }!="")$varstreet=$iter3{street }.",";
          if ($iter3{city}!="")$varcity=$iter3{city}.",";
          if ($iter3{state}!="")$varstate=$iter3{state}.",";

      // Get the descriptive name of the POS code.
      $posref = new POSRef();
      $posval = $iter3['pos_code'];
      foreach ($posref->get_pos_ref() as $tmp) {
        if ($tmp['code'] == $posval) {
          $posval = $tmp['title'];
          break;
        }
      }
      if (empty($posval)) $posval = '';
    ?>
    <tr height="22">
       <td valign="top" class="text"><b><a href="facility_admin.php?fid=<?php echo $iter3{id};?>" class="iframe medium_modal"><span><?php echo htmlspecialchars($iter3{name});?></span></a></b></td>
       <td valign="top" class="text"><?php echo htmlspecialchars($varstreet.$varcity.$varstate.$iter3{country_code}." ".$iter3{postal_code}); ?>&nbsp;</td>
       <td><?php echo htmlspecialchars($iter3{phone});?>&nbsp;</td>
       <td><?php echo text($iter3['domain_identifier']); ?>&nbsp;</td>
       <td><?php echo $iter3['billing_location'] ? xlt('Yes') : xlt('No'); ?>&nbsp;</td>
       <td><?php echo $iter3['service_location'] ? xlt('Yes') : xlt('No'); ?>&nbsp;</td>
       <td><?php echo text($iter3['facility_npi']); ?>&nbsp;</td>
       <td><?php echo text($posval); ?>&nbsp;</td>
    </tr>
<?php
  }
}
 if (count($result2)<=0)
  {?>
  <tr height="25">
		<td colspan="3"  style="text-align:center;font-weight:bold;"> <?php echo xl( "Currently there are no facilities." ); ?></td>
	</tr>
  <?php }
?>
	</table>
        </div>
    </div>
</div>
<script language="JavaScript">
<?php
  if ($alertmsg = trim($alertmsg)) {
    echo "alert('$alertmsg');\n";
  }
?>
</script>

</body>
</html>
