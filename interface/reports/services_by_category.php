<?php
// Copyright (C) 2008-2016 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("../../custom/code_types.inc.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formatting.inc.php");

$sexarray = array(4 => xl('All'), 1 => xl('Women Only'), 2 => xl('Men Only'));

// Format dollars for display.
//
function bucks($amount) {
  if (empty($amount)) return '';
  return oeFormatMoney($amount);
}

// Given a numeric code type ID, return its alpha type string.
//
function codeTypeFromID($id) {
  global $code_types;
  foreach ($code_types as $key => $value) {
    if ($value['id'] == $id) return $key;
  }
  return ''; // should not happen
}

// Get array of code types that relate to or from other codes.
// This is used in the heading line.
//
$ctarr = array();
$ctres = sqlStatement("SELECT code_type, related_code FROM codes WHERE " .
  "related_code != '' AND active = 1");
while ($ctrow = sqlFetchArray($ctres)) {
  $ctarr[codeTypeFromID($ctrow['code_type'])] = 1;
  $arel = explode(';', $ctrow['related_code']);
  foreach ($arel as $tmp) {
    list($reltype, $dummy) = explode(':', $tmp);
    $ctarr[$reltype] = 1;
  }
}
ksort($ctarr);

// Determine if we are listing only active entries. Default is yes.
$activeonly = 1;
if (isset($_REQUEST['filter'])) {
  $activeonly = empty($_REQUEST['activeonly']) ? 0 : 1;
}
$where = "1 = 1";
if ($activeonly) $where .= " AND c.active = 1";

$filter = $_REQUEST['filter'] + 0;
if ($filter) $where .= " AND c.code_type = '$filter'";

if (empty($_REQUEST['include_uncat']))
  $where .= " AND c.superbill != '' AND c.superbill != '0'";

$sex = empty($_REQUEST['sex']) ? 4 : ($_REQUEST['sex'] + 0);
if ($sex != 4) $where .= " AND sex LIKE '$sex'";

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=services_by_category.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
  // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";
}
else { // not export

?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php xl('Services by Category','e'); ?></title>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
</head>
<body>
<center>

<form method='post' action='services_by_category.php' name='theform'>
<table border='0' cellpadding='5' cellspacing='0' width='98%'>
 <tr>
  <td class='title'>
   <?php xl('Services by Category','e'); ?>
  </td>
  <td class='text' align='right'>
   <?php echo xlt('Code Type'); ?>:
   <select name='filter'>
    <option value='0'><?php xl('All','e'); ?></option>
<?php
foreach ($code_types as $key => $value) {
  echo "<option value='" . $value['id'] . "'";
  if ($value['id'] == $filter) echo " selected";
  echo ">$key</option>\n";
}
?>
   </select>
   &nbsp;
   <?php echo xlt('Sex'); ?>:
   <select name='sex'>
<?php
foreach ($sexarray as $key => $value) {
  echo "<option value='" . $key . "'";
  if ($key == $sex) echo " selected";
  echo ">$value</option>\n";
}
?>
   </select>
   &nbsp;
   <input type='checkbox' name='activeonly' value='1'<?php if ($activeonly) echo " checked"; ?> />
   <?php echo xl('Active Only'); ?>
   &nbsp;
   <input type='checkbox' name='include_uncat' value='1'<?php if (!empty($_REQUEST['include_uncat'])) echo " checked"; ?> />
   <?php xl('Include Uncategorized','e'); ?>
   &nbsp;
   <input type="submit" name="form_submit" value="<?php echo xl('Refresh'); ?>">
   &nbsp;
   <input type="submit" name="form_csvexport" value="<?php echo xl('Export to CSV'); ?>">
   &nbsp;
   <input type="button" value="<?php echo xl('Print'); ?>" onclick="window.print()">
  </td>
 </tr>
</table>
</form>

<?php
} // end not export

if ($_POST['form_submit'] || $_POST['form_csvexport']) {

  $pres = sqlStatement("SELECT title FROM list_options " . 
    "WHERE list_id = 'pricelevel' ORDER BY seq");

  if ($_POST['form_csvexport']) {
    // CSV headers:
    echo '"' . xl('Category'       ) . '",';
    echo '"' . xl('Type'           ) . '",';
    echo '"' . xl('Code'           ) . '",';
    echo '"' . xl('Mod'            ) . '",';
    echo '"' . xl('Active'         ) . '",';
    if ($GLOBALS['ippf_specific']) {
      echo '"' . xl('Initial Consult') . '",';
    }
    echo '"' . xl('Description'    ) . '",';
    echo '"' . xl('Short Description') . '",';
    echo '"' . xl('Sex') . '"';
    foreach ($ctarr as $ctkey => $dummy) {
      echo ',"' . addslashes(xl('Related') . ' ' . $ctkey) . '"';
      echo ',"' . addslashes(xl('Description')) . '"';
    }
    while ($prow = sqlFetchArray($pres)) {
      echo ',"' . xl_list_label($prow['title']) . '"';
    }
    echo "\n";
  }
  else { // not export
?>

<table border='0' cellpadding='1' cellspacing='2' width='98%'>
 <thead style='display:table-header-group'>
  <tr bgcolor="#dddddd">
   <th class='bold'><?php xl('Category'   ,'e'); ?></th>
   <th class='bold'><?php xl('Type'       ,'e'); ?></th>
   <th class='bold'><?php xl('Code'       ,'e'); ?></th>
   <th class='bold'><?php xl('Mod'        ,'e'); ?></th>
   <th class='bold' title='<?php echo xl('Active'); ?>'><?php echo xl('Act'); ?></th>
<?php if ($GLOBALS['ippf_specific']) { ?>
   <th class='bold' title='<?php echo xl('Initial Consult'); ?>'><?php echo xl('IC'); ?></th>
<?php } ?>
   <th class='bold'><?php xl('Description','e'); ?></th>
   <th class='bold'><?php echo xlt('Short Description'); ?></th>
   <th class='bold'><?php echo xlt('Sex'); ?></th>
<?php
    foreach ($ctarr as $ctkey => $dummy) {
      echo "   <th class='bold' align='right' nowrap>" . htmlspecialchars(xl('Related') . " $ctkey") . "</th>\n";
    }
    while ($prow = sqlFetchArray($pres)) {
      echo "   <th class='bold' align='right' nowrap>" . xl_list_label($prow['title']) . "</th>\n";
    }
?>
  </tr>
 </thead>
 <tbody>
<?php
  } // end not export

  $res = sqlStatement("SELECT c.*, lo.title FROM codes AS c " .
    "LEFT OUTER JOIN list_options AS lo ON lo.list_id = 'superbill' " .
    "AND lo.option_id = c.superbill " .
    "WHERE $where ORDER BY lo.title, c.code_type, c.code, c.modifier");

  $last_category = '';
  $irow = 0;
  while ($row = sqlFetchArray($res)) {
    $category = $row['title'] ? $row['title'] : 'Uncategorized';
    $disp_category = '&nbsp';

    if ($category !== $last_category) {
      $last_category = $category;
      $disp_category = $category;
      ++$irow;
    }

    $key = codeTypeFromID($row['code_type']);

    // This section fills in the array of related codes, which also includes
    // codes that relate to this one.
    //
    $relarr = array();
    // Get related codes.
    if (!empty($row['related_code'])) {
      $arel = explode(';', $row['related_code']);
      foreach ($arel as $tmp) {
        list($reltype, $relcode) = explode(':', $tmp);
        $reltypen = $code_types[$reltype]['id'];
        $relrow = sqlQuery("SELECT code_text FROM codes WHERE " .
          "code_type = '$reltypen' AND code = '$relcode' LIMIT 1");
        $relarr[] = array($reltype, $relcode, trim($relrow['code_text']));
      }
    }
    // Get codes that relate to this one.
    $tmp = $key . ':' . $row['code'];
    $relres = sqlStatement("SELECT code_type, code, code_text FROM codes WHERE " .
      "(related_code LIKE '$tmp' OR related_code LIKE '$tmp;%' OR related_code LIKE '%;$tmp' OR related_code LIKE '%;$tmp;%') " .
      "AND active = 1 ORDER BY code_type, code");
    while ($relrow = sqlFetchArray($relres)) {
      $reltype = '?';
      foreach ($code_types as $reltype => $relval) {
        if ($relval['id'] == $relrow['code_type']) {
          break;
        }
      }
      $relarr[] = array($reltype, $relrow['code'], trim($relrow['code_text']));
    }

    $pres = sqlStatement("SELECT p.pr_price " .
      "FROM list_options AS lo LEFT OUTER JOIN prices AS p ON " .
      "p.pr_id = '" . $row['id'] . "' AND p.pr_selector = '' " .
      "AND p.pr_level = lo.option_id " .
      "WHERE list_id = 'pricelevel' ORDER BY lo.seq");

    if ($_POST['form_csvexport']) {
      echo '"' . addslashes(xl_list_label($category)) . '",';
      echo '"' . addslashes($key             ) . '",';
      if ($row['code'] !== '') {
        // Prefix "=" prevents Excel from showing an IPPF2 code as floating point.
        echo '="' . addslashes($row['code']) . '",';
      }
      else {
        echo '"",';
      }
      echo '"' . addslashes($row['modifier'] ) . '",';
      echo '"' . addslashes($row['active'] ? xl('Yes') : xl('No')) . '",';
      if ($GLOBALS['ippf_specific']) {
        // IC (Initial Consult) column. Yes, No, or blank if not applicable.
        echo '"';
        if ('12' == $row['code_type']) {
          echo addslashes($row['cyp_factor'] == 0.00 ? xl('No') : xl('Yes'));
        }
        echo '",';
      }
      echo '"' . addslashes($row['code_text']) . '",';
      echo '"' . addslashes($row['code_text_short']) . '",';
      echo '"' . addslashes($sexarray[$row['sex']]) . '"';
      foreach ($ctarr as $ctkey => $dummy) {
        $tmp1 = '';
        $tmp2 = '';
        foreach ($relarr as $rkey => $rval) {
          if ($rval[0] == $ctkey) {
            if ($tmp1) {
              $tmp1 .= ', ';
              $tmp2 .= ', ';
            }
            $tmp1 .= $rval[1];
            $tmp2 .= $rval[2];
          }
        }
        // echo ',"' . addslashes($tmp1) . '"';
        if ($tmp1 !== '') {
          echo ',="' . addslashes($tmp1) . '"';
        }
        else {
          echo ',""';
        }
        echo ',"' . addslashes($tmp2) . '"';
      }

      while ($prow = sqlFetchArray($pres)) {
        echo ',"' . bucks($prow['pr_price']) . '"';
      }
      echo "\n";
    }
    else { // not export
      $bgcolor = (($irow & 1) ? "#ffdddd" : "#ddddff");
      echo "  <tr bgcolor='$bgcolor'>\n";
      // Added 5-09 by BM - Translate label if applicable
      echo "   <td class='text'>" . xl_list_label($disp_category) . "</td>\n";
      echo "   <td class='text'>$key</td>\n";
      echo "   <td class='text'>" . $row['code'] . "</td>\n";
      echo "   <td class='text'>" . $row['modifier'] . "</td>\n";
      echo "   <td class='text'>" . ($row["active"] ? xl('Yes') : xl('No')) . "</td>\n";
      if ($GLOBALS['ippf_specific']) {
        // IC (Initial Consult) column. Yes, No, or blank if not applicable.
        echo "  <td class='text'>";
        if ('12' == $row['code_type']) {
          echo $row['cyp_factor'] == 0.00 ? xl('No') : xl('Yes');
        }
        else {
          echo '&nbsp;';
        }
        echo "</td>\n";
      }
      echo "   <td class='text'>" . text($row['code_text']) . "</td>\n";
      echo "   <td class='text'>" . text($row['code_text_short']) . "</td>\n";
      echo "   <td class='text'>" . text($sexarray[$row['sex']]) . "</td>\n";

      foreach ($ctarr as $ctkey => $dummy) {
        $tmp = '';
        foreach ($relarr as $rkey => $rval) {
          if ($rval[0] == $ctkey) {
            if ($tmp) $tmp .= ', ';
            $tmp .= $rval[1] . ' ' . $rval[2];
          }
        }
        echo "   <td class='text'>" . ($tmp ? htmlspecialchars($tmp) : '&nbsp;') . "</td>\n";
      }

      while ($prow = sqlFetchArray($pres)) {
        echo "   <td class='text' align='right'>" . bucks($prow['pr_price']) . "</td>\n";
      }
      echo "  </tr>\n";
    } // end not export
  } // end while

  if (! $_POST['form_csvexport']) {
?>
 </tbody>
</table>
<?php
  } // End not csv export
} // end of submit logic

if (! $_POST['form_csvexport']) {
?>

</center>

</body>
</html>
<?php
} // End not csv export
?>
