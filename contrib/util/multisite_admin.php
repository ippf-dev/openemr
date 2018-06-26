<?php
/**
* Administration tool for doing some things with multiple sites.
* Move this to the parent of the directory holding multiple OpenEMR installations.
*
* Copyright (C) 2016-2018 Rod Roark <rod@sunsetsystems.com>
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
function output_csv($s, $commabefore=true, $forcetext=false) {
  $out = $commabefore ? ',' : '';
  if (preg_match('/^[0-9]+$/', $s)) {
    if ($forcetext) {
      // Avoids IPPF2 and other long numeric codes showing as floating point.
      $out .= '="' . $s . '"';
    }
    else {
      $out .= $s;
    }
  }
  else {
    $out .= '"' . str_replace('"', '""', $s) . '"';
  }
  return $out;
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

function getSitesSubdir($subdir='', $opening=true) {
  global $base_directory, $form_sites;
  $mysiteslist = array();
  // Get a connection for each desired site.
  $this_directory = $base_directory;
  if ($subdir) $this_directory .= "/$subdir";
  $dh = opendir($this_directory);
  if (!$dh) die("Cannot read directory '$this_directory'.");
  while (false !== ($sfname = readdir($dh))) {
    if (!preg_match('/^[A-Za-z0-9]+$/', $sfname)) continue;
    // if (preg_match('/test/', $sfname)) continue;
    if (preg_match('/old/' , $sfname)) continue;
    if ($subdir) $sfname = "$subdir/$sfname";
    if ($form_sites && !in_array($sfname, $form_sites)) continue;
    $confname = "$base_directory/$sfname/sites/default/sqlconf.php";
    if (!is_file($confname)) continue;
    $link = false;
    if ($opening) {
      include($confname);
      $link = mysqli_connect($host, $login, $pass, $dbase, $port);
      if (empty($link)) continue;
    }
    $mysiteslist[$sfname] = $link;
  }
  closedir($dh);
  ksort($mysiteslist);
  return $mysiteslist;
}

function getSites($opening=true) {
  global $base_directory, $form_sites;

  /********************************************************************
  $siteslist = array();
  // Get a connection for each desired site.
  $dh = opendir($base_directory);
  if (!$dh) die("Cannot read directory '$base_directory'.");
  while (false !== ($sfname = readdir($dh))) {
    if (!preg_match('/^[A-Za-z0-9]+$/', $sfname)) continue;
    if (preg_match('/test/', $sfname)) continue;
    if (preg_match('/old/' , $sfname)) continue;
    if ($form_sites && !in_array($sfname, $form_sites)) continue;
    $confname = "$base_directory/$sfname/sites/default/sqlconf.php";
    if (!is_file($confname)) continue;
    $link = false;
    if ($opening) {
      include($confname);
      $link = mysqli_connect($host, $login, $pass, $dbase, $port);
      if (empty($link)) continue;
    }
    $siteslist[$sfname] = $link;
  }
  closedir($dh);
  ********************************************************************/
  $siteslist = getSitesSubdir('', $opening);
  if (is_dir("$base_directory/test")) {
    $siteslist = array_merge($siteslist, getSitesSubdir('test', $opening));
  }
  return $siteslist;
}

function get_auth_arr() {
  global $password_base;
  $dh = opendir($password_base);
  if (!$dh) die("Cannot read directory '$password_base'.");
  $autharr = array();
  while (false !== ($pfname = readdir($dh))) {
    if (!preg_match('/^[A-Za-z0-9]+$/', $pfname)) continue;
    $filepath = $password_base . '/' . $pfname;
    $autharr[$pfname] = array();
    $fh = fopen($filepath, 'r');
    while (false !== ($line = fgets($fh))) {
      $tmp = explode(':', rtrim($line), 2);
      $autharr[$pfname][$tmp[0]] = $tmp[1];
    }
    fclose($fh);
    ksort($autharr[$pfname]);
  }
  closedir($dh);
  ksort($autharr);
  return $autharr;
}

// From https://www.virendrachandak.com/techtalk/using-php-create-passwords-for-htpasswd-file/
// APR1-MD5 encryption method (windows compatible)
function crypt_apr1_md5($plainpasswd) {
    $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
    $len = strlen($plainpasswd);
    $text = $plainpasswd . '$apr1$' . $salt;
    $bin = pack("H32", md5($plainpasswd . $salt . $plainpasswd));
    for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
    for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
    $bin = pack("H32", md5($text));
    for($i = 0; $i < 1000; $i++) {
        $new = ($i & 1) ? $plainpasswd : $bin;
        if ($i % 3) $new .= $salt;
        if ($i % 7) $new .= $plainpasswd;
        $new .= ($i & 1) ? $bin : $plainpasswd;
        $bin = pack("H32", md5($new));
    }
    for ($i = 0; $i < 5; $i++) {
        $k = $i + 6;
        $j = $i + 12;
        if ($j == 16) $j = 5;
        $tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
    }
    $tmp = chr(0) . chr(0) . $bin[11] . $tmp;
    $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
    return "$" . "apr1" . "$" . $salt . "$" . $tmp;
}

function writeDetail($name, $type1, $code1, $desc1, $type2, $code2, $desc2) {
  global $form_output, $GSDEBUG;
  if ($form_output == 'csv') {
    if (!$GSDEBUG) {
      echo output_csv($name, false);
      echo output_csv($type1);
      echo output_csv($code1, true, true);
      echo output_csv($desc1);
      echo output_csv($type2);
      echo output_csv($code2, true, true);
      echo output_csv($desc2);
      echo "\n";
    }
  }
  else {
    echo "   <tr>\n";
    echo "    <td>" . htmlspecialchars($name ) . "</td>\n";
    echo "    <td>" . htmlspecialchars($type1) . "</td>\n";
    echo "    <td>" . htmlspecialchars($code1) . "</td>\n";
    echo "    <td>" . htmlspecialchars($desc1) . "</td>\n";
    echo "    <td>" . htmlspecialchars($type2) . "</td>\n";
    echo "    <td>" . htmlspecialchars($code2) . "</td>\n";
    echo "    <td>" . htmlspecialchars($desc2) . "</td>\n";
    echo "   </tr>\n";
  }
}

function writeMapping($name, $link, $type1txt, $type1num, $type2txt, $type2num) {
  if ($type1txt == 'PROD') {
    $res = sqlSelect($link, "SELECT drug_id AS code, name AS code_text, related_code FROM drugs WHERE " .
      "active = 1 ORDER BY code");
  }
  else {
    $res = sqlSelect($link, "SELECT code, code_text, related_code FROM codes WHERE " .
      "code_type = $type1num AND active = 1 ORDER BY code");
  }
  while ($row = mysqli_fetch_assoc($res)) {
    $trgcode = '';
    $trgdesc = '';
    $relcodes = explode(';', $row['related_code']);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);
      if ($codetype !== $type2txt) continue;
      $trgcode = $code;
      $res2 = sqlSelect($link, "SELECT code_text FROM codes WHERE " .
        "code_type = $type2num AND code = '$trgcode' AND active = 1 LIMIT 1");
      $row2 = mysqli_fetch_assoc($res2);
      if (isset($row2['code_text'])) {
        $trgdesc = $row2['code_text'];
      }
      mysqli_free_result($res2);
      break;
    }
    writeDetail($name, $type1txt, $row['code'], $row['code_text'], $type2txt, $trgcode, $trgdesc);
  }
  mysqli_free_result($res);
}

function writeRevMapping($name, $link, $type1txt, $type1num, $type2txt, $type2num) {
  $res = sqlSelect($link, "SELECT code, code_text, related_code FROM codes WHERE " .
    "code_type = $type1num AND active = 1 ORDER BY code");
  while ($row = mysqli_fetch_assoc($res)) {
    $trgcode = $row['code'];
    $res2 = sqlSelect($link, "SELECT code, code_text FROM codes WHERE " .
      "code_type = $type2num AND active = 1 AND " .
      "(related_code LIKE '$type1txt:$trgcode' OR related_code LIKE '$type1txt:$trgcode;%' OR " .
      "related_code LIKE '%;$type1txt:$trgcode;%' OR related_code LIKE '%;$type1txt:$trgcode') " .
      "ORDER BY code");
    $count = 0;
    while ($row2 = mysqli_fetch_assoc($res2)) {
      writeDetail($name, $type1txt, $trgcode, $row['code_text'], $type2txt, $row2['code'], $row2['code_text']);
      ++$count;
    }
    if (!$count) {
      writeDetail($name, $type1txt, $trgcode, $row['code_text'], $type2txt, '', '');
    }
    mysqli_free_result($res2);
  }
  mysqli_free_result($res);
}

function writeFacilities($name, $link) {
  $res = sqlSelect($link,
    "SELECT f.id, f.name, f.domain_identifier, f.pos_code, l.title " .
    "FROM facility AS f " .
    "LEFT JOIN list_options AS l on l.list_id = 'posref' AND l.option_id = f.pos_code " .
    "ORDER BY f.name, f.id");
  while ($row = mysqli_fetch_assoc($res)) {
    writeDetail($name, xl('Facility'), $row['id'], $row['name'], xl('Org Unit'),
      $row['domain_identifier'],
      $row['pos_code'] . ': ' . $row['title']);
  }
  mysqli_free_result($res);
}

$password_base = "/etc/apache2/passwords";

// Assuming this script is in the parent of the OpenEMR directories.
$base_directory = dirname(__FILE__);
if (stripos(PHP_OS,'WIN') === 0) {
  $base_directory = str_replace("\\","/",$base_directory);
}

// If specific sites are selected, get them.
$form_sites  = empty($_POST['form_sites']) ? array() : $_POST['form_sites'];

// Output type selection, csv or html.
$form_output = empty($_POST['form_output']) ? 'csv' : $_POST['form_output'];

if (!empty($_POST['form_auth_start'])) {
?>
<html>
 <body>
  <form method='post' action='multisite_admin.php'>
  <center>
  <h2>Apache Authentication Management</h2>
  <p align='left'>Here you can change existing passwords, add new login names and passwords, and
  delete existing logins. Existing passwords are stored only as a hash and not shown; leave
  New Password empty to leave it unchanged. Note these passwords are an additional layer of
  security and have no relation to OpenEMR passwords.</p>
  <p align='left' style='color:red'>Currently the web server is configured to protect only the
  "admin" resource which is this application.</p>
  <table>
   <tr>
    <th align='left'>Resource</th>
    <th>Login</th>
    <th>New Password</th>
    <th align='right'>Delete</th>
   </tr>
<?php
  $autharr = get_auth_arr();
  foreach ($autharr as $pfname => $pfarr) {
    // Rows for existing logins
    foreach ($pfarr as $logname => $loghash) {
      echo "   <tr>\n";
      echo "    <td>$pfname</td>\n";
      echo "    <td>$logname</td>\n";
      echo "    <td><input type='text'     name='form_pwd[$pfname][$logname]' value=''</td>\n";
      echo "    <td align='right'><input type='checkbox' name='form_del[$pfname][$logname]' value='1'</td>\n";
      echo "   </tr>\n";
    }
    // Rows for adding logins
    for ($i = 0; $i < 1; ++$i) {
      echo "   <tr>\n";
      echo "    <td>$pfname</td>\n";
      echo "    <td><input type='text' name='form_new_name[$pfname][$i]' value=''</td>\n";
      echo "    <td><input type='text' name='form_new_pwd[$pfname][$i]' value=''</td>\n";
      echo "    <td>&nbsp;</td>\n";
      echo "   </tr>\n";
    }
  }
?>
  </table>
  <p><input type='submit' name='form_auth_submit' value='Update' />
  &nbsp;<input type='submit' name='form_cancel' value='Cancel' /></p>
  </center>
  </form>
 </body>
</html>
<?php
  exit();
}

else if (!empty($_POST['form_auth_submit'])) {
  $autharr = get_auth_arr();
  // Update existing passwords.
  foreach ($_POST['form_pwd'] as $pfname => $pfarr) {
    foreach ($pfarr as $logname => $logpwd) {
      if (!empty($_POST['form_del'][$pfname][$logname])) {
        unset($autharr[$pfname][$logname]);
      }
      else {
        $logpwd = trim($logpwd);
        if ($logpwd !== '') {
          $autharr[$pfname][$logname] = crypt_apr1_md5($logpwd);
        }
      }
    }
  }
  // Add new logins.
  foreach ($_POST['form_new_name'] as $pfname => $pfarr) {
    foreach ($pfarr as $i => $logname) {
      $logpwd = trim($_POST['form_new_pwd'][$pfname][$i]);
      if ($logpwd !== '') {
        $autharr[$pfname][$logname] = crypt_apr1_md5($logpwd);
      }
    }
  }
  // Rewrite all of the password files with the updated data.
  foreach ($autharr as $pfname => $pfarr) {
    $filepath = $password_base . '/' . $pfname;
    $fh = fopen($filepath, 'w');
    foreach ($pfarr as $logname => $loghash) {
      fwrite($fh, "$logname:$loghash\n");
    }
    fclose($fh);
  }
}

else if (!empty($_POST['form_cancel'])) {
  // Nothing to do.
}

else if (!empty($_POST['form_submit'])) {
  // Get sorted array of sites and open the database for each.
  $siteslist = getSites();

  if ($form_output == 'csv') {
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
  }

  if (!empty($_POST['form_globals'])) {
    // Get array of allowed global settings.
    $first_site = array_shift(array_keys($siteslist));
    $globals_arr = getGlobalsArray("$base_directory/$first_site/library/globals.inc.php");

    if ($form_output == 'csv') {
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
    }
    else {

      echo "Screen output not yet implemented.";
      // TBD: html output

    }
  } // end form_globals

  else if (!empty($_POST['form_history'])) {
    if ($form_output == 'csv') {
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
    }
    else {

      echo "Screen output not yet implemented.";
      // TBD: html output

    }
  } // end form_history

  else if (!empty($_POST['form_forms'])) {
    if ($form_output == 'csv') {
      if (!$GSDEBUG) {
        // Write header row.
        echo output_csv('Site', false);
        echo output_csv('Form Name');
        echo output_csv('Form ID');
        echo output_csv('Count');
        echo output_csv('Date of Last');
        echo "\n";
      }
      // Write detail rows.
      $begdate = date('Y-m-d 00:00:00', time() - 60 * 60 * 24 * 365); // 1 year ago
      foreach ($siteslist as $name => $link) {
        /****************************************************************
        $res = sqlSelect($link, "SELECT p.grp_title, f.formdir, MAX(f.date) AS maxdate, " .
          "COUNT(f.id) AS count " .
          "FROM forms AS f " .
          "LEFT JOIN layout_group_properties AS p ON p.grp_form_id = f.formdir AND p.grp_group_id = '' " .
          "WHERE f.deleted = 0 AND f.date > '$begdate' " .
          "GROUP BY p.grp_title, f.formdir ORDER BY p.grp_title, f.formdir");
        while ($row = mysqli_fetch_assoc($res)) {
          echo output_csv($name, false);
          echo output_csv($row['grp_title']);
          echo output_csv($row['formdir']);
          echo output_csv($row['count']);
          echo output_csv($row['maxdate']);
          echo "\n";
        }
        ****************************************************************/
        $res = sqlSelect($link, "SELECT f.form_name, f.formdir, MAX(f.date) AS maxdate, " .
          "COUNT(f.id) AS count " .
          "FROM forms AS f " .
          "WHERE f.deleted = 0 AND f.date > '$begdate' " .
          "GROUP BY f.form_name, f.formdir ORDER BY f.form_name, f.formdir");
        while ($row = mysqli_fetch_assoc($res)) {
          echo output_csv($name, false);
          echo output_csv($row['form_name']);
          echo output_csv($row['formdir']);
          echo output_csv($row['count']);
          echo output_csv($row['maxdate']);
          echo "\n";
        }
        mysqli_free_result($res);
      }
    }
    else {

      echo "Screen output not yet implemented.";
      // TBD: html output

    }
  } // end form_forms

  if (!empty($_POST['form_mapping'])) {
    if ($form_output == 'csv') {
      if (!$GSDEBUG) {
        // Write header row.
        echo output_csv('Site ID', false);
        echo output_csv('Code Type');
        echo output_csv('Code');
        echo output_csv('Code Description');
        echo output_csv('Mapped Code Type');
        echo output_csv('Mapped Code');
        echo output_csv('Mapped Code Description');
        echo "\n";
      }
    }
    else {
?>
<html>
 <body>
  <form method='post' action='multisite_admin.php'>
  <center>
  <h2>Mapping Report</h2>
  <table>
   <tr>
    <th align='left'>Site ID</th>
    <th align='left'>Code Type</th>
    <th align='left'>Code</th>
    <th align='left'>Code Description</th>
    <th align='left'>Mapped Code Type</th>
    <th align='left'>Mapped Code</th>
    <th align='left'>Mapped Code Description</th>
   </tr>
<?php
    }
    // Write detail rows.
    foreach ($siteslist as $name => $link) {
      writeMapping($name, $link, 'MA', 12, 'IPPF2', 31);
      writeRevMapping($name, $link, 'IPPF2', 31, 'MA', 12);
      writeMapping($name, $link, 'PROD', 0, 'IPPFCM', 32);
      writeFacilities($name, $link);
    } // end this site
    if ($form_output != 'csv') {
?>
  </table>
  <input type='submit' name='form_cancel' value='Back' /></p>
  </center>
  </form>
 </body>
</html>
<?php
    }
  } // end form_mapping

  foreach ($siteslist as $link) mysqli_close($link);
  exit();
}
?>
<html>
 <body>
  <form method='post' action='multisite_admin.php'>
   <center>
   <p>Multiple Sites Administration</p>
   <table cellpadding='8'>
    <tr>
     <td valign='top'>&nbsp;</td>
     <td valign='top'>
      <input type='submit' name='form_auth_start' value='Manage Apache Authentication' /><br />
     </td>
    </tr>
    <tr>
     <td valign='top'>
<?php
// Build a drop-down list of sites.
$siteslist = getSites(false);
echo "   <select name='form_sites[]' size='10' multiple='multiple' " .
  "title='Select one or more sites, or none for all sites.'>\n";
foreach ($siteslist as $siteid => $dummy) {
  echo "       <option value='$siteid'";
  echo ">$siteid</option>\n";
}
echo "      </select>\n";
?>
      <br />&nbsp;<br />
      <select name='form_output'>
       <option value='csv'>CSV</option>
       <option value='html'>Screen</option>
      </select>
     </td>
     <td valign='top'>
      <input type='submit' name='form_globals'    value='Global Settings' /><br />
      <input type='submit' name='form_history'    value='History Usage' /><br />
      <input type='submit' name='form_forms'      value='Form Usage in Past 12 Months' /><br />
      <input type='submit' name='form_mapping'      value='Consolidated Code Mapping' /><br />
      <input type='hidden' name='form_submit'     value='1' />
     </td>
    </tr>
   </table>
   </center>
  </form>
 </body>
</html>
