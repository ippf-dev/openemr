<?php
// Copyright (C) 2013-2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// See http://www.tcpdf.org/ for TCPDF documentation.
// Note that 5.0.002 is an older release used for compatibility with HTML2PDF.

// require_once($GLOBALS['fileroot'] . '/library/html2pdf/_tcpdf_5.0.002/config/lang/eng.php');
require_once($GLOBALS['fileroot'] . '/library/tcpdf_min/tcpdf.php');

// Global statement here because we're invoked within a function.
global $GCR_PAGE_WIDTH, $GCR_PAGE_HEIGHT, $GCR_LINE_HEIGHT, $GCR_ITEMS_PER_PAGE;
global $DETAIL_WIDTH_1, $DETAIL_WIDTH_2, $DETAIL_WIDTH_3, $DETAIL_WIDTH_4, $DETAIL_WIDTH_5;
global $DETAIL_POS_1, $DETAIL_POS_2, $DETAIL_POS_3, $DETAIL_POS_4, $DETAIL_POS_5;

$GCR_PAGE_WIDTH     = 504;
$GCR_PAGE_HEIGHT    = 396;
$GCR_LINE_HEIGHT    =  10;
$GCR_ITEMS_PER_PAGE =   8;

$DETAIL_POS_1 =  18;
$DETAIL_POS_2 =  99;
$DETAIL_POS_3 = 306;
$DETAIL_POS_4 = 368;
$DETAIL_POS_5 = 442;
$DETAIL_WIDTH_1 = $DETAIL_POS_2 - $DETAIL_POS_1 - 9;
$DETAIL_WIDTH_2 = $DETAIL_POS_3 - $DETAIL_POS_2 - 9;
$DETAIL_WIDTH_3 = $DETAIL_POS_4 - $DETAIL_POS_3 - 18;
$DETAIL_WIDTH_4 = $DETAIL_POS_5 - $DETAIL_POS_4 - 18;
$DETAIL_WIDTH_5 = $GCR_PAGE_WIDTH - $DETAIL_POS_5 - 9;

function gcrHeader(&$aReceipt, &$pdf) {
  global $GCR_PAGE_WIDTH, $GCR_LINE_HEIGHT;

  $HEADER_POS_1 =  57;
  $HEADER_POS_2 = 397;
  $HEADER_WIDTH_1 = $HEADER_POS_2 - $HEADER_POS_1 - 72;
  $HEADER_WIDTH_2 = $GCR_PAGE_WIDTH - $HEADER_POS_2;

  // Add a page.
  $pdf->AddPage();

  // Set internal cell padding and height ratio.
  $pdf->SetCellPadding(0);
  $pdf->setCellHeightRatio(1.00);

  // Write the invoice reference number centered in the right column.
	$pdf->MultiCell($HEADER_WIDTH_2, $GCR_LINE_HEIGHT,
    $aReceipt['invoice_refno'],
    0, 'C', 0, 1, $HEADER_POS_2, '', true, 0, false, true, 0);

  $pdf->Ln($GCR_LINE_HEIGHT);

  // Format the client name as "last, first middle".
  $ptname = $aReceipt['patient_lname'];
  $tmp = trim($aReceipt['patient_fname'] . ' ' . $aReceipt['patient_mname']);
  if ($tmp !== '') $ptname .= ", $tmp";

  // Write the client name.
  $pdf->MultiCell(
    $HEADER_WIDTH_1,      // width
    $GCR_LINE_HEIGHT,     // height
    $ptname,              // cell content
    0,                    // no border
    'L',                  // alignment (L, C, R or J)
    0,                    // no background fill
    1,                    // next position: 0 = right, 1 = new line, 2 = below
    $HEADER_POS_1,        // x position
    '',                   // y position
    true,                 // if true reset the last cell height
    0,                    // stretch: 0 = disabled, 1 = optional scaling, 2 = forced scaling, 3 = optional spacing, 4 = forced spacing
    false,                // not html
    true,                 // auto padding to keep line width
    0                     // max height
  );

  $pdf->Ln($GCR_LINE_HEIGHT);

  // Write (external) patient ID.
	$pdf->MultiCell($HEADER_WIDTH_1, $GCR_LINE_HEIGHT,
    $aReceipt['patient_pubpid'],
    0, 'L', 0, 0, $HEADER_POS_1, '', true, 0, false, true, 0);

  // Write visit date.
	$pdf->MultiCell($HEADER_WIDTH_2, $GCR_LINE_HEIGHT,
    $aReceipt['encounter_date'],
    0, 'L', 0, 1, $HEADER_POS_2, '', true, 0, false, true, 0);

  // Add some space before the line items.
  $pdf->Ln($GCR_LINE_HEIGHT * 4);
}

function gcrFooter(&$aReceipt, &$pdf, $xpos, $width) {
  global $GCR_PAGE_WIDTH, $GCR_LINE_HEIGHT, $GCR_ITEMS_PER_PAGE;
  global $DETAIL_POS_1;

  $ypos = 316;

  $pdf->MultiCell($width, $GCR_LINE_HEIGHT,
    sprintf("%01.2f", $aReceipt['total_totals']),
    0, 'R', 0, 1, $xpos, $ypos, true, 0, false, true, 0);

  $sigwidth = intval($GCR_PAGE_WIDTH * 0.4);
  $ypos += $GCR_LINE_HEIGHT * 3;

  // Write line for default provider name.
  $pdf->MultiCell($sigwidth, $GCR_LINE_HEIGHT,
    $aReceipt['docname'],
    0, 'L', 0, 1, $DETAIL_POS_1, $ypos, true, 0, false, true, 0);

  // Write line for cashier (user) name.
  $xpos = $GCR_PAGE_WIDTH - $sigwidth;
  $pdf->MultiCell($sigwidth, $GCR_LINE_HEIGHT,
    $aReceipt['username'],
    0, 'L', 0, 1, $xpos, $ypos, true, 0, false, true, 0);
}

function gcrLine(&$aReceipt, &$pdf, $code, $description, $quantity, $price, $total) {
  global $lino, $GCR_LINE_HEIGHT, $GCR_ITEMS_PER_PAGE;
  global $DETAIL_WIDTH_1, $DETAIL_WIDTH_2, $DETAIL_WIDTH_3, $DETAIL_WIDTH_4, $DETAIL_WIDTH_5;
  global $DETAIL_POS_1, $DETAIL_POS_2, $DETAIL_POS_3, $DETAIL_POS_4, $DETAIL_POS_5;

  // If overflow, start a new page.
  if ((($lino % $GCR_ITEMS_PER_PAGE) == 0) && $lino) {
    gcrHeader($aReceipt, $pdf);
  }

  // Write product/service code.
  $pdf->MultiCell($DETAIL_WIDTH_1, $GCR_LINE_HEIGHT, $code,
    0, 'L', 0, 0, $DETAIL_POS_1, '', true, 0, false, true, 0);

  // Write product/service description, truncating if needed.
  $pdf->MultiCell($DETAIL_WIDTH_2, $GCR_LINE_HEIGHT,
    substr($description, 0, 32),
    0, 'L', 0, 0, $DETAIL_POS_2, '', true, 0, false, true, 0);

  // Write unit price.
  $pdf->MultiCell($DETAIL_WIDTH_3, $GCR_LINE_HEIGHT, $quantity,
    0, 'R', 0, 0, $DETAIL_POS_3, '', true, 0, false, true, 0);

  // Write number of units.
  $pdf->MultiCell($DETAIL_WIDTH_4, $GCR_LINE_HEIGHT, $price,
    0, 'R', 0, 0, $DETAIL_POS_4, '', true, 0, false, true, 0);

  // Write extended amount.
  $pdf->MultiCell($DETAIL_WIDTH_5, $GCR_LINE_HEIGHT, $total,
    0, 'R', 0, 1, $DETAIL_POS_5, '', true, 0, false, true, 0);

  ++$lino;
}

function generateCheckoutReceipt($patient_id, $encounter_id, $billtime='') {
  global $GCR_PAGE_WIDTH, $GCR_PAGE_HEIGHT, $GCR_LINE_HEIGHT;
  global $DETAIL_WIDTH_5, $DETAIL_POS_5;

  // This receipt version covers the whole invoice and so ignores $billtime.
  $aReceipt = generateReceiptArray($patient_id, $encounter_id, '');

  // $pdf = new TCPDF('P', 'pt', 'A4', true, 'UTF-8', false);
  $pdf = new TCPDF('L', 'pt', array($GCR_PAGE_WIDTH, $GCR_PAGE_HEIGHT), true, 'UTF-8', false);

  // Remove default header and footer.
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);

  // Set default monospaced font.
  $pdf->SetDefaultMonospacedFont('courier');

  // Set margins. Left, top, right.
  $pdf->SetMargins(0, 108, 0);

  // Set auto page breaks and the bottom trigger value.
  $pdf->SetAutoPageBreak(TRUE, 1);

  // Set some language-dependent strings.
  $pdf->setLanguageArray($l);

  // Set font. Might need something else like 'freeserif' for better utf8 support.
  $pdf->SetFont('courier', '', $GCR_LINE_HEIGHT);

  // Write page header section.
  gcrHeader($aReceipt, $pdf);

  // Loop for detail lines.
  $lino = 0;
  foreach ($aReceipt['items'] as $item) {
    // Insert a charge line unless this is only an adjustment.
    if ($item['adjustment'] == 0.00 || $item['charge'] != 0.00) {
      gcrLine($aReceipt, $pdf, $item['code'], $item['description'],
        $item['quantity'], $item['price'], $item['charge']);
    }
    // If there is an adjustment insert a line for it.
    if ($item['adjustment'] != 0.00) {
      $adjreason = $item['adjreason'] ? $item['adjreason'] : xl('Adjustment');
      gcrLine($aReceipt, $pdf, $item['code'], $adjreason,
        '', '', $item['adjustment']);
    }
  }

  gcrFooter($aReceipt, $pdf, $DETAIL_POS_5, $DETAIL_WIDTH_5);

  // Reset pointer to last page.
  $pdf->lastPage();

  // Close and output the PDF document. I = inline to the browser.
  $pdf->Output('invoice.pdf', 'I');
}
?>
