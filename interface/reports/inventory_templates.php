<?php
// Copyright (C) 2012-2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$include_root/drugs/drugs.inc.php");
require_once("$srcdir/formatting.inc.php");

// Check permission for this report.
$auth_drug_reports = $GLOBALS['inhouse_pharmacy'] && (
  acl_check('admin'    , 'drugs'      ) ||
  acl_check('inventory', 'reporting'  ));
if (!$auth_drug_reports) {
  die(xl("Unauthorized access."));
}

$form_inactive = empty($_REQUEST['form_inactive']) ? 0 : 1;

$mmtype = $GLOBALS['gbl_min_max_months'] ? xl('Months') : xl('Units');

// Query for the main loop.
$query = "SELECT d.*, dt.selector, dt.period, dt.quantity, dt.refills " .
  "FROM drugs AS d " .
  "LEFT JOIN drug_templates AS dt ON dt.drug_id = d.drug_id ";
if (empty($form_inactive)) $query .= "WHERE d.active = 1 ";
$query .= "ORDER BY d.name, d.drug_id, dt.selector";
$res = sqlStatement($query);
?>
<html>

<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title><?php  xl('Inventory Price List','e'); ?></title>

<style>
tr.head   { font-size:10pt; background-color:#cccccc; text-align:center; }
tr.detail { font-size:10pt; }
</style>

<script language="JavaScript">

</script>

</head>
<body>
<center>

<form method='post' action='inventory_templates.php' name='theform'>
<table border='0' cellpadding='5' cellspacing='0' width='98%'>
 <tr>
  <td class='title'>
   <?php xl('Inventory Price List','e'); ?>
  </td>
  <td class='text' align='right'>
   <input type='checkbox' name='form_inactive' value='1'<?php if ($form_inactive) echo " checked"; ?>
   /><?php xl('Include Inactive','e'); ?>&nbsp;
   <input type="submit" value="<?php xl('Refresh','e'); ?>" />&nbsp;
   <input type="button" value="<?php xl('Print','e'); ?>" onclick="window.print()" />
  </td>
 </tr>
</table>
</form>

<table width='98%' cellpadding='2' cellspacing='2'>
 <thead style='display:table-header-group'>
  <tr class='head'>
   <th><?php  xl('Name','e'); ?></th>
   <th><?php  xl('NDC','e'); ?></th>
   <th><?php  xl('Active','e'); ?></th>
   <th><?php  xl('Form','e'); ?></th>
   <th><?php  xl('Relate To','e'); ?></th>
   <th><?php  xl('Template','e'); ?></th>
   <th><?php  xl('Schedule','e'); ?></th>
   <th align='right'><?php  xl('Quantity','e'); ?></th>
   <th align='right'><?php  xl('Refills','e'); ?></th>
<?php
  // Show a heading for each price level.
  $numprices = 0;
  $pres = sqlStatement("SELECT option_id, title FROM list_options " .
    "WHERE list_id = 'pricelevel' ORDER BY seq, title");
  while ($prow = sqlFetchArray($pres)) {
    ++$numprices;
    echo "   <th align='right'>" .
      generate_display_field(array('data_type'=>'1','list_id'=>'pricelevel'), $prow['option_id']) .
      "</th>\n";
  }
?>
  </tr>
 </thead>
 <tbody>
<?php 
$encount = 0;
$last_drug_id = '';
while ($row = sqlFetchArray($res)) {
  $drug_id = 0 + $row['drug_id'];
  $selector = $row['selector'];

  if ($drug_id != $last_drug_id) ++$encount;
  $bgcolor = "#" . (($encount & 1) ? "ddddff" : "ffdddd");

  echo " <tr class='detail' bgcolor='$bgcolor'>\n";

  if ($drug_id == $last_drug_id) {
    echo "  <td colspan='5'>&nbsp;</td>\n";
  }
  else {
    echo "  <td>" . htmlentities($row['name']) . "</td>\n";
    echo "  <td>" . htmlentities($row['ndc_number']) . "</td>\n";
    echo "  <td>" . ($row['active'] ? xl('Yes') : xl('No')) . "</td>\n";
    echo "  <td>" . generate_display_field(array('data_type'=>'1',
      'list_id'=>'drug_form'), $row['form']) . "</td>\n";
    echo "  <td>" . htmlentities($row['related_code']) . "</td>\n";
  }
  echo "  <td>" . htmlentities($row['selector']) . "</td>\n";
  echo "  <td>" .
    generate_display_field(array('data_type'=>'1','list_id'=>'drug_interval'),
    $row['period']) . "</td>\n";
  echo "  <td align='right'>" . $row['quantity'] . "</td>\n";
  echo "  <td align='right'>" . $row['refills'] . "</td>\n";

  // Prices.
  $pres = sqlStatement("SELECT pr.pr_price FROM list_options AS lo " .
    "LEFT JOIN prices AS pr ON pr.pr_id = '$drug_id' AND " .
    "pr.pr_selector = '$selector' AND pr.pr_level = lo.option_id " .
    "WHERE lo.list_id = 'pricelevel' ORDER BY lo.seq, lo.title");
  while ($prow = sqlFetchArray($pres)) {
    echo "  <td align='right'>";
    echo empty($prow['pr_price']) ? "&nbsp;" : oeFormatMoney($prow['pr_price']);
    echo "</td>\n";
  }
  echo " </tr>\n";

  $last_drug_id = $drug_id;
}
?>
 </tbody>
</table>

</center>

<script language="JavaScript">
facchanged();
</script>

</body>
</html>
