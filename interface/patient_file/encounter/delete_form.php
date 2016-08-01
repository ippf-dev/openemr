<?php
include_once("../../globals.php");

// allow a custom 'delete' form
$deleteform = $incdir . "/forms/" . $_REQUEST["formname"]."/delete.php";

check_file_dir_name($_REQUEST["formname"]);

if (file_exists($deleteform)) {
    include_once($deleteform);
    exit;
}

// if no custom 'delete' form, then use a generic one

// when the Cancel button is pressed, where do we go?
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

if ($_POST['confirm']) {
    if ($_POST['id'] != "*" && $_POST['id'] != '') {
      // set the deleted flag of the indicated form
      $sql = "update forms set deleted=1 where id=".$_POST['id'];
      sqlInsert($sql);
      // Delete the visit's "source=visit" attributes that are not used by any other form.
      sqlStatement("DELETE FROM shared_attributes WHERE " .
        "pid = '$pid' AND encounter = '$encounter' AND field_id NOT IN (" .
        "SELECT lo.field_id FROM forms AS f, layout_options AS lo WHERE " .
        "f.pid = '$pid' AND f.encounter = '$encounter' AND f.formdir LIKE 'LBF%' AND " .
        "f.deleted = 0 AND " .
        "lo.form_id = f.formdir AND lo.source = 'E' AND lo.uor > 0)");
    }
    // log the event   
    newEvent("delete", $_SESSION['authUser'], $_SESSION['authProvider'], 1, "Form ".$_POST['formname']." deleted from Encounter ".$_POST['encounter']);

    // redirect back to the encounter
    $address = "{$GLOBALS['rootdir']}/patient_file/encounter/$returnurl";
    echo "\n<script language='Javascript'>top.restoreSession();window.location='$address';</script>\n";
    exit;
}
?>
<html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<!-- supporting javascript code -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

</head>

<body class="body_top">

<span class="title">Delete Encounter Form</span>

<form method="post" action="<?php echo $rootdir;?>/patient_file/encounter/delete_form.php" name="my_form" id="my_form">
<?php
// output each GET variable as a hidden form input
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="' . attr($key) . '" name="' . attr($key) . '" value="' . attr($value) . '"/>' . "\n";
}
?>
<input type="hidden" id="confirm" name="confirm" value="1"/>
<p>
<?php
$tmp = empty($_GET['formdesc']) ? $_GET['formname'] : $_GET['formdesc'];
echo xls("You are about to delete the form") . " '$tmp' " . xls('from this visit.');
?>
</p>
<input type="button" id="confirmbtn" name="confirmbtn" value="<?php echo xla('Yes, Delete this form'); ?>">
<input type="button" id="cancel" name="cancel" value="<?php echo xla('Cancel'); ?>">
</form>

</body>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#confirmbtn").click(function() { return ConfirmDelete(); });
    $("#cancel").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl";?>'; });
});

function ConfirmDelete() {
    if (confirm("<?php echo xla('This action cannot be undone. Are you sure you wish to delete this form?'); ?>")) {
        top.restoreSession();
        $("#my_form").submit();
        return true;
    }
    return false;
}

</script>

</html>
