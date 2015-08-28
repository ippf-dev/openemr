<?php
// Copyright (C) 2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

$sanitize_all_escapes  = true;
$fake_register_globals = false;

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/jsonwrapper/jsonwrapper.php");
require_once("$srcdir/options.inc.php");
require_once($GLOBALS['fileroot'] . '/custom/code_types.inc.php');

// Paging parameters.  -1 means not applicable.
//
$iDisplayStart  = isset($_GET['iDisplayStart' ]) ? 0 + $_GET['iDisplayStart' ] : -1;
$iDisplayLength = isset($_GET['iDisplayLength']) ? 0 + $_GET['iDisplayLength'] : -1;
$limit = '';
if ($iDisplayStart >= 0 && $iDisplayLength >= 0) {
  $limit = "LIMIT " . escape_limit($iDisplayStart) . ", " . escape_limit($iDisplayLength);
}

$codetype = $_GET['codetype'];
$prod = $codetype == 'PROD';
$ncodetype = $code_types[$codetype]['id'];

// Column sorting parameters.
//
$orderby = '';
if (isset($_GET['iSortCol_0'])) {
	for ($i = 0; $i < intval($_GET['iSortingCols']); ++$i) {
    $iSortCol = intval($_GET["iSortCol_$i"]);
		if ($_GET["bSortable_$iSortCol"] == "true" ) {
      $sSortDir = escape_sort_order($_GET["sSortDir_$i"]); // ASC or DESC
      // We are to sort on column # $iSortCol in direction $sSortDir.
      $orderby .= $orderby ? ', ' : 'ORDER BY ';
      if ($iSortCol == 0) {
        $orderby .= $prod ? "d.drug_id $sSortDir, t.selector $sSortDir" : "c.code $sSortDir";
      }
      else {
        $orderby .= $prod ? "d.name $sSortDir" : "c.code_text $sSortDir";
      }
		}
	}
}

$sellist = $prod ?
  "CONCAT(d.drug_id, '|', COALESCE(t.selector, '')) AS code, d.name AS description" :
  "CONCAT(c.code, '|') AS code, c.code_text AS description";

$where1 = '';
$where2 = '';
if ($prod) {
  $from = "drugs AS d LEFT JOIN drug_templates AS t ON t.drug_id = d.drug_id";
}
else {
  $from = "codes AS c";
  $where1 = "WHERE c.code_type = '$ncodetype' AND c.active = 1";
}

if (isset($_GET['sSearch']) && $_GET['sSearch'] !== "") {
  $sSearch = add_escape_custom($_GET['sSearch']);
  $where2 = empty($where1) ? "WHERE " : " AND ";
  $where2 .= ($prod ?
    "(d.name LIKE '%$sSearch%' OR t.selector LIKE '%$sSearch%')" :
    "(c.code LIKE '%$sSearch%' OR c.code_text LIKE '%$sSearch%')");
}

// Get total number of rows with no filtering.
//
$row = sqlQuery("SELECT COUNT(*) AS count FROM $from $where1");
$iTotal = $row['count'];

// Get total number of rows after filtering.
//
$row = sqlQuery("SELECT COUNT(*) AS count FROM $from $where1 $where2");
$iFilteredTotal = $row['count'];

// Build the output data array.
//
$out = array(
  "sEcho"                => intval($_GET['sEcho']),
  "iTotalRecords"        => $iTotal,
  "iTotalDisplayRecords" => $iFilteredTotal,
  "aaData"               => array()
);
$query = "SELECT $sellist FROM $from $where1 $where2 $orderby $limit";
$res = sqlStatement($query);
while ($row = sqlFetchArray($res)) {
  // Each <tr> will have an ID indicating codetype, code and selector.
  $arow = array('DT_RowId' => "CID|$codetype|" . $row['code']);
  $arow[] = str_replace('|', ':', rtrim($row['code'], '|'));
  $arow[] = $row['description'];
  $out['aaData'][] = $arow;
}

// error_log($query); // debugging

// Dump the output array as JSON.
//
echo json_encode($out);
