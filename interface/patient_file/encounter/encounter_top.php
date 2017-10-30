<?php
include_once("../../globals.php");
include_once("$srcdir/pid.inc");
include_once("$srcdir/encounter.inc");
include_once("$srcdir/TabsWrapper.class.php");

if (isset($_GET["set_encounter"])) {
 // The billing page might also be setting a new pid.
 if(isset($_GET["set_pid"]))
 {
     $set_pid=$_GET["set_pid"];
 }
 else if(isset($_GET["pid"]))
 {
     $set_pid=$_GET["pid"];
 }
 else
 {
     $set_pid=false;
 }
 if ($set_pid && $set_pid != $_SESSION["pid"]) {
  setpid($set_pid);
 }
 setencounter($_GET["set_encounter"]);
}

$tabset = new TabsWrapper('enctabs');
$tabset->declareInitialTab(
    xl('Summary'),
    "<iframe frameborder='0' style='height:100%;width:100%;' src='forms.php'>Oops</iframe>"
);
// We might have been invoked to load a particular encounter form.
// In that case it will be the second tab, and removable.
if (!empty($_GET['formname'])) {
    $url = $rootdir . "/patient_file/encounter/load_form.php?formname=" . urlencode($_GET['formname']);
    $tabset->declareInitialTab(
        $_GET['formdesc'],
        "<iframe frameborder='0' style='height:100%;width:100%;' src='$url'>Oops</iframe>",
        true
    );
}
?>
<html>
<head>
<?php html_header_show(); ?>
<?php echo $tabset->genCss(); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.9.1.min.js"></script>
<?php echo $tabset->genJavaScript(); ?>
<script>

$(document).ready(function() {
  // Initialize support for the tab set.
  twSetup('enctabs');
});

// This is called to refresh encounter display data after something has changed it.
// Currently only the encounter summary tab will be refreshed.
function refreshVisitDisplay() {
  for (var i = 0; i < window.frames.length; ++i) {
    if (window.frames[i].refreshVisitDisplay) {
      window.frames[i].refreshVisitDisplay();
    }
  }
}

// Called from the individual iframes when their forms want to close.
// The iframe window name is passed and identifies which tab it is.
// The "refresh" argument indicates if encounter data may have changed.
function closeTab(winname, refresh) {
  twCloseTab('enctabs', winname);
  if (refresh) {
    refreshVisitDisplay();
  }
}

</script>
</head>
<body>
<?php echo $tabset->genHtml(); ?>
</body>
</html>
