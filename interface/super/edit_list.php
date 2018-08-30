<?php
/**
 * Administration Lists Module.
 *
 * Copyright (C) 2007-2017 Rod Roark <rod@sunsetsystems.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @author  Teny <teny@zhservices.com> 
 * @link    http://www.open-emr.org
 */

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/log.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/lists.inc");
require_once("../../custom/code_types.inc.php");

$list_id = empty($_REQUEST['list_id']) ? 'language' : $_REQUEST['list_id'];

// Indicates if we were invoked by the layout editor to create a new layout.
$from_layout = empty($_REQUEST['from_layout']) ? 0 : 1;

// Check authorization.
$thisauth = acl_check('admin', 'super');
if (!$thisauth) die(xl('Not authorized'));

// Compute a current checksum of the data from the database for the given list.
//
function listChecksum($list_id) {
  if ($list_id == 'feesheet') {
    $row = sqlQuery("SELECT BIT_XOR(CRC32(CONCAT_WS(',', " .
      "fs_category, fs_option, fs_codes" .
      "))) AS checksum FROM fee_sheet_options");
  }
  else if ($list_id == 'code_types') {
    $row = sqlQuery("SELECT BIT_XOR(CRC32(CONCAT_WS(',', " .
      "ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_mask, ct_fee, ct_rel, ct_nofs, ct_diag" .
      "))) AS checksum FROM code_types");
  }
  else {
    $row = sqlQuery("SELECT BIT_XOR(CRC32(CONCAT_WS(',', " .
      "list_id, option_id, title, seq, is_default, option_value, mapping, notes" .
      "))) AS checksum FROM list_options WHERE " .
      "list_id = '$list_id'");
  }
  return (0 + $row['checksum']);
}

function csvtext($s) {
  return str_replace('"', '""', $s);
}

if ($_POST['formaction'] == 'csvexport') {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download; charset=utf-8");
  header("Content-Disposition: attachment; filename=$list_id.csv");
  header("Content-Description: File Transfer");
  // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  See:
  // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
  // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
  echo "\xEF\xBB\xBF";

  $tmplabel = genValueLabel($list_id);

  // CSV headers:
  echo '"' . xl('List'     ) . '",';
  echo '"' . xl('ID'       ) . '",';
  echo '"' . xl('Title'    ) . '",';
  echo '"' . xl('Translated') . '",';
  echo '"' . xl('Order'    ) . '",';
  echo '"' . xl('Default'  ) . '",';
  echo '"' . xl('Active'   ) . '",';
  if ($tmplabel !== '') {
    echo '"' . addslashes($tmplabel) . '",';
  }
  echo '"' . xl('Global ID') . '",';
  echo '"' . xl('Notes'    ) . '",';
  echo '"' . xl('Codes'    ) . '"';
  echo "\n";

  $res = sqlStatement("SELECT * FROM list_options WHERE list_id = ? ORDER BY seq, title",
    array($list_id));

  while ($row = sqlFetchArray($res)) {
    $xtitle = xl_list_label($row['title']);
    if ($xtitle === $row['title']) $xtitle = '';
    echo '"' . csvtext($row['list_id']) . '",';
    echo '"' . csvtext($row['option_id']) . '",';
    echo '"' . csvtext($row['title']) . '",';
    echo '"' . csvtext($xtitle) . '",';
    echo '"' . csvtext($row['seq']) . '",';
    echo '"' . csvtext($row['is_default']) . '",';
    echo '"' . csvtext($row['activity']) . '",';
    if ($tmplabel !== '') {
      echo '"' . csvtext($row['option_value']) . '",';
    }
    echo '"' . csvtext($row['mapping']) . '",';
    echo '"' . csvtext($row['notes']) . '",';
    echo '"' . csvtext($row['codes']) . '"';
    echo "\n";
  }

  exit(0);
}

$alertmsg = '';

$current_checksum = listChecksum($list_id);

if (isset($_POST['form_checksum']) && $_POST['formaction'] == 'save') {
  if ($_POST['form_checksum'] != $current_checksum) {
    $alertmsg = xl('Save rejected because someone else has changed this list. Please try again.');
  }
}

// This will be relevant if we happen to be saving the lbfnames list,
// and will retain the ID of the last layout item that was saved.
$last_list_item_id = '';

// If we are saving, then save.
//
if ($_POST['formaction']=='save' && $list_id && $alertmsg == '') {
    $opt = $_POST['opt'];
    if ($list_id == 'feesheet') {
        // special case for the feesheet list
        sqlStatement("DELETE FROM fee_sheet_options");
        for ($lino = 1; isset($opt["$lino"]['category']); ++$lino) {
            $iter = $opt["$lino"];
            $category = formTrim($iter['category']);
            $option   = formTrim($iter['option']);
            $codes    = formTrim($iter['codes']);
            if (strlen($category) > 0 && strlen($option) > 0) {
                sqlInsert("INSERT INTO fee_sheet_options ( " .
                            "fs_category, fs_option, fs_codes " .
                            ") VALUES ( "   .
                            "'$category', " .
                            "'$option', "   .
                            "'$codes' "     .
                        ")");
            }
        }
    }
    else if ($list_id == 'code_types') {
      // special case for code types
      sqlStatement("DELETE FROM code_types");
      for ($lino = 1; isset($opt["$lino"]['ct_key']); ++$lino) {
        $iter = $opt["$lino"];
        $ct_key  = formTrim($iter['ct_key']);
        $ct_id   = formTrim($iter['ct_id']) + 0;
        $ct_seq  = formTrim($iter['ct_seq']) + 0;
        $ct_mod  = formTrim($iter['ct_mod']) + 0;
        $ct_just = formTrim($iter['ct_just']);
        $ct_mask = formTrim($iter['ct_mask']);
        $ct_fee  = empty($iter['ct_fee' ]) ? 0 : 1;
        $ct_rel  = empty($iter['ct_rel' ]) ? 0 : 1;
        $ct_nofs = empty($iter['ct_nofs']) ? 0 : 1;
        $ct_diag = empty($iter['ct_diag']) ? 0 : 1;
        $ct_active = empty($iter['ct_active' ]) ? 0 : 1;
        $ct_label = formTrim($iter['ct_label']);
        $ct_external = formTrim($iter['ct_external']) + 0;
        $ct_claim = empty($iter['ct_claim']) ? 0 : 1;
        $ct_proc = empty($iter['ct_proc']) ? 0 : 1;
        $ct_term = empty($iter['ct_term']) ? 0 : 1;
        $ct_problem = empty($iter['ct_problem']) ? 0 : 1;
        if (strlen($ct_key) > 0 && $ct_id > 0) {
          sqlInsert("INSERT INTO code_types ( " .
            "ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_mask, ct_fee, ct_rel, ct_nofs, ct_diag, ct_active, ct_label, ct_external, ct_claim, ct_proc, ct_term, ct_problem " .
            ") VALUES ( "   .
            "'$ct_key' , " .
            "'$ct_id'  , " .
            "'$ct_seq' , " .
            "'$ct_mod' , " .
            "'$ct_just', " .
            "'$ct_mask', " .
            "'$ct_fee' , " .
            "'$ct_rel' , " .
            "'$ct_nofs', " .
            "'$ct_diag', " .
            "'$ct_active', " .
            "'$ct_label', " .
            "'$ct_external', " .
            "'$ct_claim', " .
            "'$ct_proc', " .
            "'$ct_term', " .
            "'$ct_problem' " .
            ")");
        }
      }
    }
    else if ($list_id == 'issue_types') {
      // special case for issue_types
      sqlStatement("DELETE FROM issue_types");
      for ($lino = 1; isset($opt["$lino"]['category']); ++$lino) {
        $iter        = $opt["$lino"];
        $it_active   = formTrim($iter['active']);
        $it_category = formTrim($iter['category']);
        $it_ordering = formTrim($iter['ordering']);
        $it_type     = formTrim($iter['type']);
        $it_plural   = formTrim($iter['plural']);
        $it_singular = formTrim($iter['singular']);
        $it_abbr     = formTrim($iter['abbreviation']);
        $it_style    = formTrim($iter['style']);
        $it_fshow    = formTrim($iter['force_show']);
        
        if ( (strlen($it_category) > 0) && (strlen($it_type) > 0) ) {
          sqlInsert("INSERT INTO issue_types ( " .
            "`active`,`category`,`ordering`, `type`, `plural`, `singular`, `abbreviation`, `style`, `force_show` " .
            ") VALUES ( "   .
            "'$it_active' , " .
            "'$it_category' , " .
            "'$it_ordering' , " .
            "'$it_type' , " .
            "'$it_plural'  , " .
            "'$it_singular' , " .
            "'$it_abbr' , " .
            "'$it_style', " .
            "'$it_fshow' " .
            ")");
        }
      }
    }    
    else {
        // all other lists
        // collect the option toggle if using the 'immunizations' list
        if ($list_id == 'immunizations') {
          $ok_map_cvx_codes = isset($_POST['ok_map_cvx_codes']) ? $_POST['ok_map_cvx_codes'] : 0;
        }
        $larray = array();
        $lres = sqlStatement("SELECT * FROM list_options WHERE list_id = '$list_id'");
        while ($lrow = sqlFetchArray($lres)) {
          $larray[strtolower(trim($lrow['option_id']))] = $lrow;
        }
        for ($lino = 1; isset($opt["$lino"]['id']); ++$lino) {
          $iter = $opt["$lino"];
          $value = empty($iter['value']) ? 0 : (trim($iter['value']) + 0);
          $id = strip_escape_custom($iter['id']);
          $idtrimmed = trim($id);
          if (strlen($idtrimmed) == 0) continue;
          // Special processing for the immunizations list
          // Map the entered cvx codes into the immunizations table cvx_code
          if ($list_id == 'immunizations' && is_int($value) && $value > 0 &&
            !empty($id) && $ok_map_cvx_codes == 1 ) {
            sqlStatement ("UPDATE `immunizations` " .
              "SET `cvx_code` = '$value' " .
              "WHERE `immunization_id` = '$id'");
          }
          // Force List Based Form names to start with LBF.
          if ($list_id == 'lbfnames' && substr($idtrimmed,0,3) != 'LBF') {
            $id = $idtrimmed = "LBF$idtrimmed";
          }
          $idtrimmedlc = strtolower($idtrimmed);

          // For the flow board.
          if ($list_id == 'apptstat') {
            $notes = strip_escape_custom($iter['apptstat_color']) .'|'. strip_escape_custom($iter['apptstat_timealert']);
          }
          else {
            $notes = strip_escape_custom($iter['notes']);
          }

          // Put the table keys and values into an array to simplify things.
          // Force numeric values to strings so that !== will work as desired.
          $lrow = array();
          $lrow['title'       ] = strip_escape_custom($iter['title'  ]);
          $lrow['seq'         ] = strip_escape_custom($iter['seq'    ]);
          $lrow['is_default'  ] = strval(0 + strip_escape_custom($iter['default']));
          $lrow['option_value'] = strval($value);
          $lrow['mapping'     ] = strip_escape_custom($iter['mapping']);
          $lrow['notes'       ] = $notes;
          $lrow['codes'       ] = strip_escape_custom($iter['codes'  ]);
          $lrow['activity'    ] = strip_escape_custom($iter['activity']);
          $lrow['toggle_setting_1'] = empty($iter['toggle_setting_1']) ? '0' : $iter['toggle_setting_1'];
          $lrow['toggle_setting_2'] = empty($iter['toggle_setting_2']) ? '0' : $iter['toggle_setting_2'];
          $sets = '';
          if (isset($larray[$idtrimmedlc])) {
            // If the list item was already in the database, update or ignore it as appropriate.
            // Only the fields that changed will be updated.
            $lrow['option_id'] = $idtrimmed; // catering to option_id case or trim mismatch
            foreach ($lrow as $key => $val) {
              if ($larray[$idtrimmedlc][$key] !== $val) {
                if ($sets) $sets .= ", ";
                $sets .= "`$key` = '" . add_escape_custom($val) . "'";
              }
            }
            if ($sets) {
              // A mysql oddity to keep in mind here is that string comparisons, including key
              // matching, are not sensitive to trailing spaces.
              sqlStatement("UPDATE list_options SET $sets WHERE list_id = '" .
                add_escape_custom($list_id) . "' AND option_id = '" .
                add_escape_custom($id) . "'");
            }
            // Delete $larray entries for table rows that match up with form rows.
            // Whatever remains will be the table rows that should be deleted from the database.
            unset($larray[$idtrimmedlc]);
          }
          else {
            // Not already in the database so insert it. 
            // IGNORE is used in case the trimmed id already exists (we allow updating/deleting
            // but not inserting of ids with leading/trailing spaces).
            foreach ($lrow as $key => $val) {
              if ($sets) $sets .= ", ";
              $sets .= "`$key` = '" . add_escape_custom($val) . "'";
            }
            sqlStatement("INSERT IGNORE INTO list_options SET `list_id` = '" .
              add_escape_custom($list_id) . "', `option_id` = '" . add_escape_custom($idtrimmed) .
              "', $sets");
          }
          $last_list_item_id = $id;
        }
        // Delete any list items from the database that are not in the form.
        foreach ($larray as $lrow) {
          sqlStatement("DELETE FROM list_options WHERE list_id = '" .
              add_escape_custom($list_id) . "' AND option_id = '" .
              add_escape_custom($lrow['option_id']) . "'");
        }
    }
    newEvent("edit_list", $_SESSION['authUser'], $_SESSION['authProvider'], 1, "List = $list_id");    
}
else if ($_POST['formaction']=='addlist') {
    // make a new list ID from the new list name
    $newlistID = $_POST['newlistname'];
    $newlistID = preg_replace("/\W/", "_", $newlistID);

    // determine the position of this new list
    $row = sqlQuery("SELECT max(seq) as maxseq FROM list_options WHERE list_id= 'lists'");

    // add the new list to the list-of-lists
    sqlInsert("INSERT INTO list_options ( " .
        "list_id, option_id, title, seq, is_default, option_value " .
        ") VALUES ( 'lists', ?, ?, ?, '1', '0')",
        array($newlistID, $_POST['newlistname'], ($row['maxseq'] + 1))
    );
    newEvent("add_list", $_SESSION['authUser'], $_SESSION['authProvider'], 1, "List = $newlistID");    
}
else if ($_POST['formaction']=='deletelist') {
    // delete the lists options
    sqlStatement("DELETE FROM list_options WHERE list_id = '".$_POST['list_id']."'");
    // delete the list from the master list-of-lists
    sqlStatement("DELETE FROM list_options WHERE list_id = 'lists' and option_id = ?",
      array($_POST['list_id']));
    newEvent("delete_list", $_SESSION['authUser'], $_SESSION['authProvider'], 1, "List = " . $_POST['list_id']);    
}

if (!empty($_POST['formaction'])) {
  $current_checksum = listChecksum($list_id);
}

// If we came from the layout editor and added a layout, then go back there.
if ($from_layout && $last_list_item_id) {
  echo "<html><head><script language='JavaScript'>\n";
  echo "top.restoreSession();\n";
  echo "location = 'edit_layout.php?layout_id=" . urlencode($last_list_item_id) . "';\n";
  echo "</script></head></html>\n";
  exit;
}

$opt_line_no = 0;

// Given a string of multiple instances of code_type|code|selector,
// make a description for each.
// @TODO Instead should use a function from custom/code_types.inc.php and need to remove casing functions
function getCodeDescriptions($codes) {
  global $code_types;
  $arrcodes = explode('~', $codes);
  $s = '';
  foreach ($arrcodes as $codestring) {
    if ($codestring === '') continue;
    $arrcode = explode('|', $codestring);
    $code_type = $arrcode[0];
    $code      = $arrcode[1];
    $selector  = $arrcode[2];
    $desc = '';
    if ($code_type == 'PROD') {
      $row = sqlQuery("SELECT name FROM drugs WHERE drug_id = '$code' ");
      $desc = "$code:$selector " . $row['name'];
    }
    else {
      $row = sqlQuery("SELECT code_text FROM codes WHERE " .
        "code_type = '" . $code_types[$code_type]['id'] . "' AND " .
        "code = '$code' ORDER BY modifier LIMIT 1");
      $desc = "$code_type:$code " . ucfirst(strtolower($row['code_text']));
    }
    $desc = str_replace('~', ' ', $desc);
    if ($s) $s .= '~';
    $s .= $desc;
  }
  return $s;
}

// Write one option line to the form.
//
function writeOptionLine($option_id, $title, $seq, $default, $value, $mapping='', $notes='', $codes='', $active='1', $tog1='', $tog2='') {
  global $opt_line_no, $list_id;
  ++$opt_line_no;
  $bgcolor = "#" . (($opt_line_no & 1) ? "ddddff" : "ffdddd");
  $checked = $default ? " checked" : "";
  $checked_active = $active ? " checked" : "";
  $checked_tog1 = $tog1 ? " checked" : "";
  $checked_tog2 = $tog2 ? " checked" : "";  

  echo " <tr bgcolor='$bgcolor'>\n";

  echo "  <td align='center' class='optcell'>";
  echo "<input type='text' name='opt[$opt_line_no][id]' value='" .
       htmlspecialchars($option_id, ENT_QUOTES) . "' size='12' maxlength='63' class='optin' />";
  echo "</td>\n";

  echo "  <td align='center' class='optcell'>";
  echo "<input type='text' name='opt[$opt_line_no][title]' value='" .
       htmlspecialchars($title, ENT_QUOTES) . "' size='30' maxlength='63' class='optin' />";
  echo "</td>\n";

  // if not english and translating lists then show the translation
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
       echo "  <td align='center' class='translation'>" . (htmlspecialchars( xl($title), ENT_QUOTES)) . "</td>\n";
  }
    
  echo "  <td align='center' class='optcell'>";
  echo "<input type='text' name='opt[$opt_line_no][seq]' value='" .
       htmlspecialchars($seq, ENT_QUOTES) . "' size='4' maxlength='10' class='optin' />";
  echo "</td>\n";

  echo "  <td align='center' class='optcell'>";
  echo "<input type='checkbox' name='opt[$opt_line_no][default]' value='1' " .
    "onclick='defClicked($opt_line_no)' class='optin'$checked />";
  echo "</td>\n";

  echo "  <td align='center' class='optcell'>";
  echo "<input type='checkbox' name='opt[$opt_line_no][activity]' value='1' " .
       " class='optin'$checked_active />";
  echo "</td>\n";

  // Tax rates, form names, contraceptive methods, adjustment reasons and facilities
  // have an additional attribute.
  //
  if ($list_id == 'taxrate' || $list_id == 'contrameth' || $list_id == 'lbfnames' || $list_id == 'transactions') {
    echo "  <td align='center' class='optcell'>";
    echo "<input type='text' name='opt[$opt_line_no][value]' value='" .
        htmlspecialchars($value, ENT_QUOTES) . "' size='8' maxlength='15' class='optin' />";
    echo "</td>\n";
  }
  else if ($list_id == 'contrameth' || $list_id == 'adjreason') {
    $tmp = $value ? " checked" : "";
    echo " <td align='center' class='optcell'>";
    echo "<input type='checkbox' name='opt[$opt_line_no][value]' value='1' class='optin'$tmp />";
    echo "</td>\n";
  }
  else if ($list_id == 'warehouse') {
    echo " <td align='center' class='optcell'>\n";
    // Build a drop-down list of facilities.
    $query = "SELECT id, name FROM facility ORDER BY name";
    $fres = sqlStatement($query);
    echo " <select name='opt[$opt_line_no][value]'>\n";
    echo " <option value='0'>-- " . xl('Unassigned') . " --\n";
    while ($frow = sqlFetchArray($fres)) {
    $facid = $frow['id'];
    echo " <option value='$facid'";
    if ($facid == $value) echo " selected";
      echo ">" . $frow['name'] . "\n";
    }
    echo " </select>\n";
    echo " </td>\n";
  }
  
  // Address book categories use option_value to flag category as a
  // person-centric vs company-centric vs indifferent.
  //
  else if ($list_id == 'abook_type') {
    echo "  <td align='center' class='optcell'>";
    echo "<select name='opt[$opt_line_no][value]' class='optin'>";
    foreach (array(
      1 => xl('Unassigned'),
      2 => xl('Person'),
      3 => xl('Company'),
    ) as $key => $desc) {
      echo "<option value='$key'";
      if ($key == $value) echo " selected";
      echo ">" . htmlspecialchars($desc) . "</option>";
    }
    echo "</select>";
    echo "</td>\n";
  }

  // Immunization categories use option_value to map list items
  // to CVX codes.
  //
  else if ($list_id == 'immunizations') {
  echo "  <td align='center' class='optcell'>";
  echo "<input type='text' size='10' name='opt[$opt_line_no][value]' " .
       "value='" . htmlspecialchars($value,ENT_QUOTES) . "' onclick='sel_cvxcode(this)' " .
       "title='" . htmlspecialchars( xl('Click to select or change CVX code'), ENT_QUOTES) . "'/>";
  echo "</td>\n";
  }

  if ($list_id == 'apptstat') {
    list($apptstat_color, $apptstat_timealert) = explode("|", $notes);
    echo "  <td align='center' class='optcell'>";
    echo "<input type='text' class='color' name='opt[$opt_line_no][apptstat_color]' value='" .
        htmlspecialchars($apptstat_color, ENT_QUOTES) . "' size='6' maxlength='6' class='optin' />";
    echo "</td>\n";
    echo "  <td align='center' class='optcell'>";
    echo "<input type='text' name='opt[$opt_line_no][apptstat_timealert]' value='" .
        htmlspecialchars($apptstat_timealert, ENT_QUOTES) . "' size='2' maxlength='2' class='optin' />";
    echo "</td>\n";
  } else {
    // IPPF includes the ability to map each list item to a "master" identifier.
    // Sports teams use this for some extra info for fitness levels.
    //
    if ($GLOBALS['ippf_specific'] || $list_id == 'fitness' || $list_id == 'lbfnames') {
      echo "  <td align='center' class='optcell'>";
      echo "<input type='text' name='opt[$opt_line_no][mapping]' value='" .
          htmlspecialchars($mapping, ENT_QUOTES) . 
              "' size='12' maxlength='31' class='optin' />";
      echo "</td>\n";
    }
    echo "  <td align='center' class='optcell'>";
    echo "<input type='text' name='opt[$opt_line_no][notes]' value='" .
        htmlspecialchars($notes, ENT_QUOTES) . "' size='25' maxlength='255' class='optin' ";
    // if ($list_id == 'lbfnames') {
    //   echo "onclick='edit_layout_props($opt_line_no)' ";
    // }
    echo "/>";
    echo "</td>\n";
  }
  if ($list_id == 'apptstat') {
    echo "  <td align='center' class='optcell'>";
    echo "<input type='checkbox' name='opt[$opt_line_no][toggle_setting_1]' value='1' " .
      "onclick='defClicked($opt_line_no)' class='optin'$checked_tog1 />";
    echo "</td>\n";
    echo "  <td align='center' class='optcell'>";
    echo "<input type='checkbox' name='opt[$opt_line_no][toggle_setting_2]' value='1' " .
      "onclick='defClicked($opt_line_no)' class='optin'$checked_tog2 />";
    echo "</td>\n";
  }
  echo "  <td align='center' class='optcell'>";
  echo "<input type='text' name='opt[$opt_line_no][codes]' title='" .
      xla('Clinical Term Code(s)') ."' value='" .
      htmlspecialchars($codes, ENT_QUOTES) . "' onclick='select_clin_term_code(this)' size='25' maxlength='255' class='optin' />";
  echo "</td>\n";
  echo " </tr>\n";
}

// Write a form line as above but for the special case of the Fee Sheet.
//
function writeFSLine($category, $option, $codes) {
  global $opt_line_no;

  ++$opt_line_no;
  $bgcolor = "#" . (($opt_line_no & 1) ? "ddddff" : "ffdddd");

  $descs = getCodeDescriptions($codes);

  echo " <tr bgcolor='$bgcolor'>\n";

  echo "  <td align='center' class='optcell'>";
  echo "<input type='text' name='opt[$opt_line_no][category]' value='" .
       htmlspecialchars($category, ENT_QUOTES) . "' size='20' maxlength='63' class='optin' />";
  echo "</td>\n";

  echo "  <td align='center' class='optcell'>";
  echo "<input type='text' name='opt[$opt_line_no][option]' value='" .
       htmlspecialchars($option, ENT_QUOTES) . "' size='20' maxlength='63' class='optin' />";
  echo "</td>\n";

  echo "  <td align='left' class='optcell'>";
  echo "   <div id='codelist_$opt_line_no'>";
  if (strlen($descs)) {
    $arrdescs = explode('~', $descs);
    $i = 0;
    foreach ($arrdescs as $desc) {
      echo "<a href='' onclick='return delete_code($opt_line_no,$i)' title='" . xl('Delete') . "'>";
      echo "[x]&nbsp;</a>$desc<br />";
      ++$i;
    }
  }
  echo "</div>";
  echo "<a href='' onclick='return select_code($opt_line_no)'>";
  echo "[" . xl('Add') . "]</a>";

  echo "<input type='hidden' name='opt[$opt_line_no][codes]' value='" .
       htmlspecialchars($codes, ENT_QUOTES) . "' />";
  echo "<input type='hidden' name='opt[$opt_line_no][descs]' value='" .
       htmlspecialchars($descs, ENT_QUOTES) . "' />";
  echo "</td>\n";

  echo " </tr>\n";
}


/**
 * Helper functions for writeITLine() and writeCTLine().
 */
function ctGenCell($opt_line_no, $data_array, $name, $size, $maxlength, $title='') {
  $value = isset($data_array[$name]) ? $data_array[$name] : '';
  $s = "  <td align='center' class='optcell'";
  if ($title) $s .= " title='" . attr($title) . "'";
  $s .= ">";
  $s .= "<input type='text' name='opt[$opt_line_no][$name]' value='";
  $s .= attr($value);
  $s .= "' size='$size' maxlength='$maxlength' class='optin' />";
  $s .= "</td>\n";
  return $s;
}

function ctGenCbox($opt_line_no, $data_array, $name, $title='') {
  $checked = empty($data_array[$name]) ? '' : 'checked ';
  $s = "  <td align='center' class='optcell'";
  if ($title) $s .= " title='" . attr($title) . "'";
  $s .= ">";
  $s .= "<input type='checkbox' name='opt[$opt_line_no][$name]' value='1' ";
  $s .= "$checked/>";
  $s .= "</td>\n";
  return $s;
}

function ctSelector($opt_line_no, $data_array, $name, $option_array, $title='') {
  $value = isset($data_array[$name]) ? $data_array[$name] : '';
  $s = "  <td title='" . attr($title) . "' align='center' class='optcell'>";
  $s .= "<select name='opt[$opt_line_no][$name]' class='optin'>";
  foreach ( $option_array as $key => $desc) {
    $s .= "<option value='" . attr($key) . "'";
    if ($key == $value) $s .= " selected";
    $s .= ">" . text($desc) . "</option>";
  }
  $s .= "</select>";
  $s .= "</td>\n";
  return $s;
}

// Write a form line as above but for the special case of Code Types.
//
function writeCTLine($ct_array) {
  global $opt_line_no,$cd_external_options;

  ++$opt_line_no;
  $bgcolor = "#" . (($opt_line_no & 1) ? "ddddff" : "ffdddd");

  echo " <tr bgcolor='$bgcolor'>\n";

  echo ctGenCBox($opt_line_no, $ct_array, 'ct_active',
    xl('Is this code type active?'));
  echo ctGenCell($opt_line_no, $ct_array, 'ct_key' , 6, 15,
    xl('Unique human-readable identifier for this type'));
  echo ctGenCell($opt_line_no, $ct_array, 'ct_id'  , 2, 11,
    xl('Unique numeric identifier for this type'));
  echo ctGenCell($opt_line_no, $ct_array, 'ct_label' , 6, 30,
    xl('Label for this type'));
  // if not english and translating lists then show the translation
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
       echo "  <td align='center' class='translation'>" . xlt($ct_array['ct_label']) . "</td>\n";
  }
  echo ctGenCell($opt_line_no, $ct_array, 'ct_seq' , 2,  3,
    xl('Numeric display order'));
  echo ctGenCell($opt_line_no, $ct_array, 'ct_mod' , 1,  2,
    xl('Length of modifier, 0 if none'));
  echo ctGenCell($opt_line_no, $ct_array, 'ct_just', 4, 15,
    xl('If billing justification is used enter the name of the diagnosis code type.'));
  echo ctGenCell($opt_line_no, $ct_array, 'ct_mask', 6,  9,
    xl('Specifies formatting for codes. # = digit, @ = alpha, * = any character. Empty if not used.'));
  echo ctGenCBox($opt_line_no, $ct_array, 'ct_claim',
    xl('Is this code type used in claims?'));
  echo ctGenCBox($opt_line_no, $ct_array, 'ct_fee',
    xl('Are fees charged for this type?'));
  echo ctGenCBox($opt_line_no, $ct_array, 'ct_rel',
    xl('Does this type allow related codes?'));
  echo ctGenCBox($opt_line_no, $ct_array, 'ct_nofs',
    xl('Is this type hidden in the fee sheet?'));
  echo ctGenCBox($opt_line_no, $ct_array, 'ct_proc',
    xl('Is this a procedure/service type?'));
  echo ctGenCBox($opt_line_no, $ct_array, 'ct_diag',
    xl('Is this a diagnosis type?'));
  echo ctGenCBox($opt_line_no, $ct_array, 'ct_term',
    xl('Is this a Clinical Term code type?'));
  echo ctGenCBox($opt_line_no, $ct_array, 'ct_problem',
    xl('Is this a Medical Problem code type?'));
  echo ctSelector($opt_line_no, $ct_array, 'ct_external',
    $cd_external_options, xl('Is this using external sql tables? If it is, then choose the format.'));
  echo " </tr>\n";
}

/**
 * Special case of Issue Types
 */
function writeITLine($it_array) {        
  global $opt_line_no,$ISSUE_TYPE_CATEGORIES,$ISSUE_TYPE_STYLES;
  ++$opt_line_no;
  $bgcolor = "#" . (($opt_line_no & 1) ? "ddddff" : "ffdddd");
  echo " <tr bgcolor='$bgcolor'>\n";
  echo ctSelector($opt_line_no, $it_array, 'category', $ISSUE_TYPE_CATEGORIES, xl('OpenEMR Application Category'));
  echo ctGenCBox($opt_line_no, $it_array, 'active', xl('Is this active?'));
  echo ctGenCell($opt_line_no, $it_array, 'ordering' , 10, 10, xl('Order'));
  echo ctGenCell($opt_line_no, $it_array, 'type' , 20, 75, xl('Issue Type'));
  echo ctGenCell($opt_line_no, $it_array, 'plural' , 20, 75, xl('Plural'));
  // if not english and translating lists then show the translation
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
       echo "  <td align='center' class='translation'>" . xlt($it_array['plural']) . "</td>\n";
  }
  echo ctGenCell($opt_line_no, $it_array, 'singular' , 20,  75, xl('Singular'));
  // if not english and translating lists then show the translation
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
       echo "  <td align='center' class='translation'>" . xlt($it_array['singular']) . "</td>\n";
  }
  echo ctGenCell($opt_line_no, $it_array, 'abbreviation' , 10,  10, xl('Abbreviation'));
  // if not english and translating lists then show the translation
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
       echo "  <td align='center' class='translation'>" . xlt($it_array['abbreviation']) . "</td>\n";
  }
  echo ctSelector($opt_line_no, $it_array, 'style', $ISSUE_TYPE_STYLES, xl('Standard; Simplified: only title, start date, comments and an Active checkbox;no diagnosis, occurrence, end date, referred-by or sports fields. ; Football Injury'));
  echo ctGenCBox($opt_line_no, $it_array, 'force_show', xl('Show this category on the patient summary screen even if no issues have been entered for this category.'));
  echo " </tr>\n";
}

function genValueLabel($list_id) {
  $s = '';
  if      ($list_id == 'taxrate'      ) $s = xl('Rate');
  else if ($list_id == 'contrameth'   ) $s = xl('Modern');
  else if ($list_id == 'adjreason'    ) $s = xl('After Taxes');
  else if ($list_id == 'warehouse'    ) $s = xl('Facility');
  else if ($list_id == 'lbfnames' || $list_id == 'transactions') $s = xl('Repeats');
  else if ($list_id == 'abook_type'   ) $s = xl('Type');
  else if ($list_id == 'immunizations') $s = xl('CVX Code Mapping'); 
  return $s;
}

?>
<html>

<head>
<?php html_header_show();?>

<!-- supporting javascript code -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title><?php  xl('List Editor','e'); ?></title>

<style>
tr.head   { font-size:10pt; background-color:#cccccc; text-align:center; }
tr.detail { font-size:10pt; }
td        { font-size:10pt; }
input     { font-size:10pt; }
a, a:visited, a:hover { color:#0000cc; }
.optcell  { }
.optin    { background-color:transparent; }
.help     { cursor:help; }
.translation { color:green; }
</style>

<script type="text/javascript" src="../../library/dialog.js?v=<?php echo $v_js_includes; ?>"></script>
<script type="text/javascript" src="../../library/js/jscolor/jscolor.js"></script>

<script language="JavaScript">

// Keeping track of code picker requests.
var current_lino = 0;
var current_sel_name = '';
var current_sel_clin_term = '';

// Helper function to set the contents of a div.
// This is for Fee Sheet administration.
function setDivContent(id, content) {
 if (document.getElementById) {
  var x = document.getElementById(id);
  x.innerHTML = '';
  x.innerHTML = content;
 }
 else if (document.all) {
  var x = document.all[id];
  x.innerHTML = content;
 }
}

// Given a line number, redisplay its descriptive list of codes.
// This is for Fee Sheet administration.
function displayCodes(lino) {
 var f = document.forms[0];
 var s = '';
 var descs = f['opt[' + lino + '][descs]'].value;
 if (descs.length) {
  var arrdescs = descs.split('~');
  for (var i = 0; i < arrdescs.length; ++i) {
   s += "<a href='' onclick='return delete_code(" + lino + "," + i + ")' title='<?php xl('Delete','e'); ?>'>";
   s += "[x]&nbsp;</a>" + arrdescs[i] + "<br />";
  }
 }
 setDivContent('codelist_' + lino, s);
}

// Helper function to remove a Fee Sheet code.
function dc_substring(s, i) {
 var r = '';
 var j = s.indexOf('~', i);
 if (j < 0) { // deleting last segment
  if (i > 0) r = s.substring(0, i-1); // omits trailing ~
 }
 else { // not last segment
  r = s.substring(0, i) + s.substring(j + 1);
 }
 return r;
}

// Remove a generated Fee Sheet code.
function delete_code(lino, seqno) {
 var f = document.forms[0];
 var celem = f['opt[' + lino + '][codes]'];
 var delem = f['opt[' + lino + '][descs]'];
 var ci = 0;
 var di = 0;
 for (var i = 0; i < seqno; ++i) {
  ci = celem.value.indexOf('~', ci) + 1;
  di = delem.value.indexOf('~', di) + 1;
 }
 celem.value = dc_substring(celem.value, ci);
 delem.value = dc_substring(delem.value, di);
 displayCodes(lino);
 return false;
}

// This invokes the find-code popup.
// For Fee Sheet administration.
function select_code(lino) {
 current_sel_name = '';
 current_sel_clin_term = '';
 current_lino = lino;
 dlgopen('../patient_file/encounter/find_code_dynamic.php', '_blank', 900, 600);
 return false;
}

// This invokes the find-code popup.
// For CVX/immunization code administration.
function sel_cvxcode(e) {
 current_sel_clin_term = '';
 current_sel_name = e.name;
 dlgopen('../patient_file/encounter/find_code_dynamic.php?codetype=CVX', '_blank', 900, 600);
}

// This invokes the find-code popup.
// For CVX/immunization code administration.
function select_clin_term_code(e) {
 current_sel_name = '';
 current_sel_clin_term = e.name;
 dlgopen('../patient_file/encounter/find_code_dynamic.php?codetype=<?php echo attr(collect_codetypes("clinical_term","csv")) ?>', '_blank', 900, 600);
}

// This invokes the popup to edit properties in the "notes" column.
// function edit_layout_props(lineno) {
//  var layoutid = document.forms[0]['opt[' + lineno + '][id]'].value;
//  dlgopen('edit_layout_props.php?layout_id=' + layoutid + '&lineno=' + lineno, '_blank', 600, 300);
// }

// This is for callback by the find-code popup.
function set_related(codetype, code, selector, codedesc) {
  var f = document.forms[0];
  if (current_sel_clin_term) {
    // Coming from the Clinical Terms Code(s) edit
    var e = f[current_sel_clin_term];
    var s = e.value;
    if (code) {
      if (s.length > 0) s += ';';
      s += codetype + ':' + code;
    }
    else {
      s = '';
    }
    e.value = s;
  }
  else if (current_sel_name) {
    // Coming from Immunizations edit
    var e = f[current_sel_name];
    var s = e.value;
    if (code) {
      s = code;
    }
    else {
      s = '0';
    }
    e.value = s;
  }
  else {
    // Coming from Fee Sheet edit
    var celem = f['opt[' + current_lino + '][codes]'];
    var delem = f['opt[' + current_lino + '][descs]'];
    var i = 0;
    while ((i = codedesc.indexOf('~')) >= 0) {
      codedesc = codedesc.substring(0, i) + ' ' + codedesc.substring(i+1);
    }
    if (code) {
      if (celem.value) {
        celem.value += '~';
        delem.value += '~';
      }
      celem.value += codetype + '|' + code + '|' + selector;
      if (codetype == 'PROD') delem.value += code + ':' + selector + ' ' + codedesc;
      else delem.value += codetype + ':' + code + ' ' + codedesc;
    } else {
      celem.value = '';
      delem.value = '';
    }
    displayCodes(current_lino);
  }
}

// This is for callback by the find-code popup.
// Deletes the specified codetype:code from the currently selected list.
function del_related(s) {
  var f = document.forms[0];
  if (current_sel_clin_term) {
    // Coming from the Clinical Terms Code(s) edit
    my_del_related(s, f[current_sel_clin_term], false);
  }
  else if (current_sel_name) {
    // Coming from Immunizations edit
    f[current_sel_name].value = '0';
  }
  else {
    // Coming from Fee Sheet edit
    f['opt[' + current_lino + '][codes]'].value = '';
    f['opt[' + current_lino + '][descs]'].value = '';
    displayCodes(current_lino);
  }
}

// This is for callback by the find-code popup.
// Returns the array of currently selected codes with each element in codetype:code format.
function get_related() {
  var f = document.forms[0];
  if (current_sel_clin_term) {
    return f[current_sel_clin_term].value.split(';');
  }
  return new Array();
}

// Called when a "default" checkbox is clicked.  Clears all the others.
function defClicked(lino) {
 var f = document.forms[0];
 for (var i = 1; f['opt[' + i + '][default]']; ++i) {
  if (i != lino) f['opt[' + i + '][default]'].checked = false;
 }
}

// Form validation and submission.
// This needs more validation.
function mysubmit() {
  var f = document.forms[0];
  if (f.list_id.value == 'code_types') {
    for (var i = 1; f['opt[' + i + '][ct_key]'].value; ++i) {
      var ikey = 'opt[' + i + ']';
      for (var j = i+1; f['opt[' + j + '][ct_key]'].value; ++j) {
        var jkey = 'opt[' + j + ']';
        if (f[ikey+'[ct_key]'].value == f[jkey+'[ct_key]'].value) {
          alert('<?php echo xl('Error: duplicated name on line') ?>' + ' ' + j);
          return;
        }
        if (parseInt(f[ikey+'[ct_id]'].value) == parseInt(f[jkey+'[ct_id]'].value)) {
          alert('<?php echo xl('Error: duplicated ID on line') ?>' + ' ' + j);
          return;
        }
      }
    }
  }
  else if (f['opt[1][id]']) {
    // Check for duplicate IDs.
    for (var i = 1; f['opt[' + i + '][id]']; ++i) {
      var ikey = 'opt[' + i + '][id]';
      if (f[ikey].value == '') continue;
      for (var j = i+1; f['opt[' + j + '][id]']; ++j) {
        var jkey = 'opt[' + j + '][id]';
        if (f[ikey].value.toUpperCase() == f[jkey].value.toUpperCase()) {
          alert('<?php echo xls('Error: duplicated ID') ?>' + ': ' + f[jkey].value);
          f[jkey].scrollIntoView();
          f[jkey].focus();
          f[jkey].select();
          return;
        }
      }
    }
  }

 <?php if ($GLOBALS['ippf_specific']) { ?>
    // This case requires the mapping for education to be numeric.
    if (f.list_id.value == 'userlist2') {
        for (var i = 1; f['opt[' + i + '][mapping]']; ++i) {
            if (f['opt[' + i + '][id]'].value) {
                var m = f['opt[' + i + '][mapping]'].value;
                if (m.length != 1 || m < '0' || m > '9') {
                    alert('<?php echo xl('Error: Global ID must be a digit on line') ?>' + ' ' + i);
                    return;
                }
            }
        }
    }
 <?php } ?> 
 f.submit();
}

// This is invoked when a new list is chosen.
// Disables all buttons and actions so certain bad things cannot happen.
function listSelected() {
 var f = document.forms[0];
 // For jQuery 1.6 and later, change ".attr" to ".prop".
 $(":button").attr("disabled", true);
 f.formaction.value = '';
 f.submit();
}

</script>

</head>

<body class="body_top">

<form method='post' name='theform' id='theform' action='edit_list.php'>
<input type="hidden" name="formaction" id="formaction">
<input type='hidden' name='form_checksum' value='<?php echo $current_checksum; ?>' />
<input type='hidden' name='from_layout' value='<?php echo $from_layout; ?>' />

<p><b><?php xl('Edit list','e'); ?>:</b>&nbsp;
<select name='list_id' id="list_id">
<?php

// List order depends on language translation options.
$lang_id = empty($_SESSION['language_choice']) ? '1' : $_SESSION['language_choice'];

if (($lang_id == '1' && !empty($GLOBALS['skip_english_translation'])) ||
  !$GLOBALS['translate_lists'])
{
  $res = sqlStatement("SELECT option_id, title FROM list_options WHERE " .
    "list_id = 'lists' ORDER BY title, seq");
}
else {
  // Use and sort by the translated list name.
  $res = sqlStatement("SELECT lo.option_id, " .
    "IF(LENGTH(ld.definition),ld.definition,lo.title) AS title " .
    "FROM list_options AS lo " .
    "LEFT JOIN lang_constants AS lc ON lc.constant_name = lo.title " .
    "LEFT JOIN lang_definitions AS ld ON ld.cons_id = lc.cons_id AND " .
    "ld.lang_id = '$lang_id' " .
    "WHERE lo.list_id = 'lists' " .
    "ORDER BY IF(LENGTH(ld.definition),ld.definition,lo.title), lo.seq");
}

$lastkey = '';
while ($row = sqlFetchArray($res)) {
  $key = $row['option_id'];
  // The left joins could produce duplicate rows, so skip those.
  if ($key === $lastkey) continue;
  $lastkey = $key;
  echo "<option value='$key'";
  if ($key == $list_id) echo " selected";
  echo " title='" . attr($key) . "'";
  echo ">" . $row['title'] . "</option>\n";
}

?>
</select>
<input type="button" id="<?php echo $list_id; ?>" class="deletelist" value=<?php xl('Delete List','e','\'','\''); ?>>
<input type="button" id="newlist" class="newlist" value=<?php xl('New List','e','\'','\''); ?>>

<?php if ($list_id && $list_id != 'feesheet' && $list_id != 'code_types' && $list_id != 'issue_types') { ?>
<input type="button" id="form_csvexport" value='<?php echo xla('Export to CSV'); ?>' />
<?php } ?>

</p>

<center>

<table cellpadding='2' cellspacing='0'>
 <tr class='head'>
<?php if ($list_id == 'feesheet') { ?>
  <td><b><?php xl('Group'    ,'e'); ?></b></td>
  <td><b><?php xl('Option'   ,'e'); ?></b></td>
  <td><b><?php xl('Generates','e'); ?></b></td>
<?php } else if ($list_id == 'code_types') { ?>
  <td><b><?php xl('Active'      ,'e'); ?></b></td>
  <td><b><?php xl('Key'        ,'e'); ?></b></td>
  <td><b><?php xl('ID'          ,'e'); ?></b></td>
  <td><b><?php xl('Label'       ,'e'); ?></b></td>
  <?php //show translation column if not english and the translation lists flag is set
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
    echo "<td><b>".xl('Translation')."</b><span class='help' title='".xl('The translated Title that will appear in current language')."'> (?)</span></td>";
  } ?>
  <td><b><?php xl('Seq'         ,'e'); ?></b></td>
  <td><b><?php xl('ModLength'   ,'e'); ?></b></td>
  <td><b><?php xl('Justify'     ,'e'); ?></b></td>
  <td><b><?php xl('Mask'        ,'e'); ?></b></td>
  <td><b><?php xl('Claims'      ,'e'); ?></b></td>
  <td><b><?php xl('Fees'        ,'e'); ?></b></td>
  <td><b><?php xl('Relations'   ,'e'); ?></b></td>
  <td><b><?php xl('Hide'        ,'e'); ?></b></td>
  <td><b><?php xl('Procedure'   ,'e'); ?></b></td>
  <td><b><?php xl('Diagnosis'   ,'e'); ?></b></td>
  <td><b><?php xl('Clinical Term','e'); ?></b></td>
  <td><b><?php xl('Medical Problem'     ,'e'); ?></b></td>
  <td><b><?php xl('External'    ,'e'); ?></b></td>
<?php } else if ($list_id == 'apptstat') { ?> 
  <td><b><?php  xl('ID'       ,'e'); ?></b></td>
  <td><b><?php xl('Title'     ,'e'); ?></b></td>   
  <td><b><?php xl('Order'     ,'e'); ?></b></td>
  <td><b><?php xl('Default'   ,'e'); ?></b></td>
  <td><b><?php echo xlt('Active'); ?></b></td>
  <td><b><?php xl('Color'     ,'e'); ?></b></td> 
  <td><b><?php xl('Alert Time','e'); ?></b></td> 
  <td><b><?php xl('Check In'  ,'e');?>&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
  <td><b><?php xl('Check Out' ,'e'); ?></b></td>
  <td><b><?php xl('Code(s)'   ,'e');?></b></td>
<?php } else if ($list_id == 'issue_types') { ?>
  <td><b><?php echo xlt('OpenEMR Application Category'); ?></b></td>
  <td><b><?php echo xlt('Active'); ?></b></td>
  <td><b><?php echo xlt('Order'); ?></b></td>
  <td><b><?php echo xlt('Type'); ?></b></td>
  <td><b><?php echo xlt('Plural'); ?></b></td>
  <?php //show translation column if not english and the translation lists flag is set
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
    echo "<td><b>".xl('Translation')."</b><span class='help' title='".xl('The translated Title that will appear in current language')."'> (?)</span></td>";
  } ?>
  <td><b><?php echo xlt('Singular'); ?></b></td>
  <?php //show translation column if not english and the translation lists flag is set
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
    echo "<td><b>".xl('Translation')."</b><span class='help' title='".xl('The translated Title that will appear in current language')."'> (?)</span></td>";
  } ?>
  <td><b><?php echo xlt('Abbreviation'); ?></b></td>
  <?php //show translation column if not english and the translation lists flag is set
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
    echo "<td><b>".xl('Translation')."</b><span class='help' title='".xl('The translated Title that will appear in current language')."'> (?)</span></td>";
  } ?>
  <td><b><?php echo xlt('Style'); ?></b></td>
  <td><b><?php echo xlt('Force Show'); ?></b></td>
<?php } else { ?>
  <td title=<?php xl('Click to edit','e','\'','\''); ?>><b><?php  xl('ID','e'); ?></b></td>
  <td><b><?php xl('Title'  ,'e'); ?></b></td>   
  <?php //show translation column if not english and the translation lists flag is set 
  if ($GLOBALS['translate_lists'] && $_SESSION['language_choice'] > 1) {
    echo "<td><b>".xl('Translation')."</b><span class='help' title='".xl('The translated Title that will appear in current language')."'> (?)</span></td>";    
  } ?>  
  <td><b><?php xl('Order'  ,'e'); ?></b></td>
  <td><b><?php xl('Default','e'); ?></b></td>
  <td><b><?php echo xlt('Active'); ?></b></td>
<?php
// Some lists have another attribute for option_value here.
$tmplabel = genValueLabel($list_id);
if ($tmplabel !== '') {
  echo "  <td";
  if ($list_id == 'lbfnames' || $list_id == 'transactions') {
    echo " title='" . xla('Number of past history columns') . "'";
  }
  echo "><b>" . text($tmplabel) . "</b></td>\n";
}
?>
<?php if ($list_id == 'lbfnames') { ?>
  <td><b><?php xl('Category','e'); ?></b></td>  
<?php } else if ($list_id == 'fitness') { ?>
  <td><b><?php xl('Color:Abbr','e'); ?></b></td>
<?php } else if ($GLOBALS['ippf_specific']) { ?>
<td><b><?php xl('Global ID','e'); ?></b></td>
<?php } ?>
<td><b><?php xl('Notes','e'); ?></b></td>        
<td><b><?php xl('Code(s)','e'); ?></b></td>
<?php } // end not feesheet nor code_types ?>
 </tr>

<?php 
// Get the selected list's elements.
if ($list_id) {
  if ($list_id == 'feesheet') {
    $res = sqlStatement("SELECT * FROM fee_sheet_options " .
      "ORDER BY fs_category, fs_option");
    while ($row = sqlFetchArray($res)) {
      writeFSLine($row['fs_category'], $row['fs_option'], $row['fs_codes']);
    }
    for ($i = 0; $i < 3; ++$i) {
      writeFSLine('', '', '');
    }
  }
  else if ($list_id == 'code_types') {
    $res = sqlStatement("SELECT * FROM code_types " .
      "ORDER BY ct_seq, ct_key");
    while ($row = sqlFetchArray($res)) {
      writeCTLine($row);
    }
    for ($i = 0; $i < 3; ++$i) {
      writeCTLine(array());
    }
  }
  else if ($list_id == 'issue_types') {
    $res = sqlStatement("SELECT * FROM issue_types " .
      "ORDER BY category, ordering ASC");  
    while ($row = sqlFetchArray($res)) {
      writeITLine($row);
    }
    for ($i = 0; $i < 3; ++$i) {
      writeITLine(array());
    }
  }  
  else {
    // lbfnames only is sorted by group name.
    $res = sqlStatement("SELECT * FROM list_options WHERE " .
      "list_id = '$list_id' ORDER BY " .
      ($list_id == 'lbfnames' ? "mapping, seq, title" : "seq, title"));
    while ($row = sqlFetchArray($res)) {
      writeOptionLine($row['option_id'], $row['title'], $row['seq'],
        $row['is_default'], $row['option_value'], $row['mapping'],
        $row['notes'],$row['codes'],$row['activity'],$row['toggle_setting_1'],$row['toggle_setting_2']);
    }
    for ($i = 0; $i < 3; ++$i) {
      writeOptionLine('', '', '', '', 0);
    }
  }
}
?>

</table>

<?php if ($list_id == 'immunizations') { ?>
 <p> <?php echo xlt('Is it ok to map these CVX codes to already existent immunizations?') ?>
  <input type='checkbox' name='ok_map_cvx_codes' id='ok_map_cvx_codes' value='1' />
 </p>
<?php } // end if($list_id == 'immunizations') ?>

<p>
 <input type='button' name='form_save' id='form_save' value='<?php xl('Save','e'); ?>' />
</p>
</center>

</form>

<!-- template DIV that appears when user chooses to make a new list -->
<div id="newlistdetail" style="border: 1px solid black; padding: 3px; display: none; visibility: hidden; background-color: lightgrey;">
<?php xl('List Name','e'); ?>: <input type="textbox" size="20" maxlength="30" name="newlistname" id="newlistname">
<br>
<input type="button" class="savenewlist" value=<?php xl('Save New List','e','\'','\''); ?>>
<input type="button" class="cancelnewlist" value=<?php xl('Cancel','e','\'','\''); ?>>
</div>
</body>
<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#form_save").click(function() { SaveChanges(); });
    $("#list_id").change(function() { listSelected(); });

    $(".newlist").click(function() { NewList(this); });
    $(".savenewlist").click(function() { SaveNewList(this); });
    $(".deletelist").click(function() { DeleteList(this); });
    $(".cancelnewlist").click(function() { CancelNewList(this); });

    $("#form_csvexport").click(function() {
      $('#formaction').val('csvexport');
      $('#theform').submit();
    });

    var SaveChanges = function() {
        $("#formaction").val("save");
        // $('#theform').submit();
        mysubmit();
    }

    // show the DIV to create a new list
    var NewList = function(btnObj) {
        // show the field details DIV
        $('#newlistdetail').css('visibility', 'visible');
        $('#newlistdetail').css('display', 'block');
        $(btnObj).parent().append($("#newlistdetail"));
        $('#newlistdetail > #newlistname').focus();
    }
    // save the new list
    var SaveNewList = function() {
        // the list name can only have letters, numbers, spaces and underscores
        // AND it cannot start with a number
        if ($("#newlistname").val().match(/^\d+/)) {
            alert("<?php xl('List names cannot start with numbers.','e'); ?>");
            return false;
        }
        var validname = $("#newlistname").val().replace(/[^A-za-z0-9 -]/g, "_"); // match any non-word characters and replace them
        if (validname != $("#newlistname").val()) {
            if (! confirm("<?php xl('Your list name has been changed to meet naming requirements.','e','','\n') . xl('Please compare the new name','e','',', \''); ?>"+validname+"<?php xl('with the old name','e','\' ',', \''); ?>"+$("#newlistname").val()+"<?php xl('Do you wish to continue with the new name?','e','\'.\n',''); ?>"))
            {
                return false;
            }
        }
        $("#newlistname").val(validname);
    
        // submit the form to add a new field to a specific group
        $("#formaction").val("addlist");
        $("#theform").submit();
    }
    // actually delete an entire list from the database
    var DeleteList = function(btnObj) {
        var listid = $(btnObj).attr("id");
        if (confirm("<?php xl('WARNING','e','',' - ') . xl('This action cannot be undone.','e','','\n') . xl('Are you sure you wish to delete the entire list','e',' ','('); ?>"+listid+")?")) {
            // submit the form to add a new field to a specific group
            $("#formaction").val("deletelist");
            $("#deletelistname").val(listid);
            $("#theform").submit();
        }
    };
    
    // just hide the new list DIV
    var CancelNewList = function(btnObj) {
        // hide the list details DIV
        $('#newlistdetail').css('visibility', 'hidden');
        $('#newlistdetail').css('display', 'none');
        // reset the new group values to a default
        $('#newlistdetail > #newlistname').val("");
    };

<?php
if ($alertmsg) {
  echo "    alert('" . addslashes($alertmsg) . "');\n";
}
?>    
});

</script>

</html>
