<?php
/**
* Administration tool for doing some things with multiple sites.
* Move this to the parent of the directory holding multiple OpenEMR installations.
*
* Copyright (C) 2016 Rod Roark <rod@sunsetsystems.com>
*
* LICENSE: This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://opensource.org/licenses/gpl-license.php>.
*
* @package   OpenEMR
* @author    Rod Roark <rod@sunsetsystems.com>
*/

$GSDEBUG = false; // debugging

function sqlExec($link, $query) {
  if (!mysqli_query($link, $query)) die("Query failed: $query\n");
  return true;
}

function sqlSelect($link, $query) {
  $res = mysqli_query($link, $query);
  if (!$res) die("Query failed: $query\n");
  return $res;
}

function sqlSelectOne($link, $query) {
  $res = sqlSelect($link, $query);
  $row = mysqli_fetch_assoc($res);
  mysqli_free_result($res);
  return $row;
}

function getGlobalsArray($globinc) {
  global $GLOBALS_METADATA, $GSDEBUG;
  include($globinc);
  if ($GSDEBUG) print_r($GLOBALS_METADATA);
  return $GLOBALS_METADATA;
}

// Write a CSV item with its quotes, with embedded quotes escaped,
// and optionally preceded by a comma.
function output_csv($s, $commabefore=true) {
  return ($commabefore ? ',"' : '"') . str_replace('"', '""', $s) . '"';
}

// This changes list item IDs to their descriptive values.
function dispValue($value, $info) {
  if (is_array($info[1])) {
    if (isset($info[1][$value])) $value = $info[1][$value];
  }
  return $value;
}

// Dummy translation function so included things will work.
function xl($s) {
  return $s;
}

// $base_directory = dirname(dirname(dirname(dirname(__FILE__))));

// Assuming this script is in the parent of the OpenEMR directories.
$base_directory = dirname(__FILE__);
if (stripos(PHP_OS,'WIN') === 0) {
  $base_directory = str_replace("\\","/",$base_directory);
}

if (!empty($_POST['form_submit'])) {
  // Get a connection for each desired site.
  $dh = opendir($base_directory);
  if (!$dh) die("Cannot read directory '$base_directory'.");
  $siteslist = array();
  while (false !== ($sfname = readdir($dh))) {
    if (!preg_match('/^[A-Za-z0-9]+$/', $sfname)) continue;
    if (preg_match('/test/', $sfname)) continue;
    if (preg_match('/old/' , $sfname)) continue;
    $confname = "$base_directory/$sfname/sites/default/sqlconf.php";
    if (!is_file($confname)) continue;
    include($confname);
    $link = mysqli_connect($host, $login, $pass, $dbase, $port);
    $siteslist[$sfname] = $link;
  }
  closedir($dh);

  // Sort on site directory name.
  ksort($siteslist);

  if (!$GSDEBUG) {
    // Initialize CSV output.
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download; charset=utf-8");
    header("Content-Disposition: attachment; filename=multisite_admin.csv");
    header("Content-Description: File Transfer");
    // Prepend a BOM (Byte Order Mark) header to mark the data as UTF-8.  This is
    // said to work for Excel 2007 pl3 and up and perhaps also Excel 2003 pl3.  See:
    // http://stackoverflow.com/questions/155097/microsoft-excel-mangles-diacritics-in-csv-files
    // http://crashcoursing.blogspot.com/2011/05/exporting-csv-with-special-characters.html
    echo "\xEF\xBB\xBF";
  }

  if (!empty($_POST['form_globals'])) {
    // Get array of allowed global settings.
    $first_site = array_shift(array_keys($siteslist));
    $globals_arr = getGlobalsArray("$base_directory/$first_site/library/globals.inc.php");

    if (!$GSDEBUG) {
      // Write header row.
      echo output_csv('Tab', false);
      echo output_csv('Item');
      echo output_csv('Default Value/Setting');
      echo output_csv('Relevant to IPPF/WHR');
      foreach ($siteslist as $name => $dummy) {
        echo output_csv($name);
      }
      echo "\n";
    }

    // Write detail rows.
    foreach ($globals_arr as $group_name => $group_arr) {
      foreach ($group_arr as $item_key => $item_arr) {
        echo output_csv($group_name, false);
        echo output_csv($item_arr[0]);
        echo output_csv(dispValue($item_arr[2], $item_arr));
        echo output_csv('');
        foreach ($siteslist as $name => $link) {
          $value = '';
          $res = sqlSelect($link, "SELECT * FROM globals WHERE gl_name = '" . $item_key . "' ORDER BY gl_index");
          while ($row = mysqli_fetch_assoc($res)) {
            if ($value) $value .= '; ';
            $value .= dispValue($row['gl_value'], $item_arr);
          }
          mysqli_free_result($res);
          echo output_csv($value);
        }
        echo "\n";
      }
    }
  } // end form_globals

  if (!empty($_POST['form_history'])) {
    if (!$GSDEBUG) {
      // Write header row.
      echo output_csv('Site', false);
      echo output_csv('Pid');
      echo output_csv('ID');
      echo output_csv('Number of Saves');
      // echo output_csv('First');
      echo output_csv('Date of Last');
      echo "\n";
    }

    // Write detail rows.
    foreach ($siteslist as $name => $link) {
      $res = sqlSelect($link, "SELECT h.pid, p.pubpid, MIN(h.date) AS mindate, MAX(h.date) AS maxdate, " .
        "COUNT(h.id) AS count " .
        "FROM history_data AS h " .
        "JOIN patient_data AS p ON p.pid = h.pid " .
        "WHERE (SELECT COUNT(z.id) FROM history_data AS z WHERE z.pid = h.pid) > 1 " .
        "GROUP BY h.pid ORDER BY h.pid");
      while ($row = mysqli_fetch_assoc($res)) {
        echo output_csv($name, false);
        echo output_csv($row['pid']);
        echo output_csv($row['pubpid']);
        echo output_csv($row['count'] - 1);
        // echo output_csv($row['mindate']);
        echo output_csv($row['maxdate']);
        echo "\n";
      }
      mysqli_free_result($res);
    }
  } // end form_history

  foreach ($siteslist as $link) mysqli_close($link);
  exit;
}
?>
<html>
 <body>
  <form method='post' action='multisite_admin.php'>
   <center>
   <p>Multiple Sites Administration</p>
   <input type='submit' name='form_globals' value='Download Global Settings' />
   <input type='submit' name='form_history' value='Download History Usage' />
   <input type='hidden' name='form_submit' value='1' />
   </center>
  </form>
 </body>
</html>
