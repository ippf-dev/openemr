<?php
// Copyright (C) 2013-2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// See http://www.tcpdf.org/ for TCPDF documentation.
// Note that 5.0.002 is an older release used for compatibility with HTML2PDF.

require_once($GLOBALS['fileroot'] . '/library/html2pdf/_tcpdf_5.0.002/config/lang/eng.php');
require_once($GLOBALS['fileroot'] . '/library/html2pdf/_tcpdf_5.0.002/tcpdf.php');

// This receipt looks something like the following:
/**********************************************************************
  MM/DD/YYYY  Sales Receipt XXXXXXX-99

          Name of Organization
             Name of Clinic
          Clinic address, City
      Phone:XXX-XXXX  Fax:XXX-XXXX 
             www.grpagy.com

  Item Name                  Ext.Price
  ------------------------------------
  Contraceptive Oral      9,999,999.99
    9999 @ 99,999.9999
  Endometrial Biopsy      9,999,999.99
  ------------------------------------
               Subtotal: 99,999,999.99
               Discount:-99,999,999.99   (if applicable; could be multiple lines)
             XXXXXX Tax:    999,999.99   (if applicable; could be multiple lines)
                         -------------
          RECEIPT TOTAL: 99,999,999.99
                         -------------

          XXXXX Payment: 99,999,999.99   (XXXXX = Cash, Check or CC)
            Balance Due: 99,999,999.99

  <-- Message to client goes here --->
**********************************************************************/

// Global statement here because we may be included within a function.
global $GCR_PAGE_WIDTH, $GCR_PAGE_HEIGHT, $GCR_LINE_HEIGHT, $GCR_ITEMS_PER_PAGE;
global $DETAIL_WIDTH_1, $DETAIL_WIDTH_2, $DETAIL_WIDTH_3, $DETAIL_WIDTH_4, $DETAIL_WIDTH_5;
global $DETAIL_POS_1, $DETAIL_POS_2, $DETAIL_POS_3, $DETAIL_POS_4, $DETAIL_POS_5;

// Star Micronics SP500 printer

$GCR_FONTSIZE       =  10;
$GCR_LINE_HEIGHT    =  12; // 6 lines/inch = 12 points/line
$GCR_PAGE_WIDTH     = 210; // 63 mm = 178.58 points but printer's points are off
$GCR_PAGE_HEIGHT    = 792; // Irrelevant but need to specify something

$GCR_TOP_WIDTH_1 = 62;               // Top line, MM/DD/YYYY
$GCR_TOP_WIDTH_3 = $GCR_TOP_WIDTH_1; // Invoice number + suffix
$GCR_TOP_WIDTH_2 = $GCR_PAGE_WIDTH - $GCR_TOP_WIDTH_1 - $GCR_TOP_WIDTH_3;

$GCR_MONEY_WIDTH = 72;     // Points allocated for 99,999,999.99
$GCR_DESC_WIDTH  = $GCR_PAGE_WIDTH - $GCR_MONEY_WIDTH;

function gcrWriteCell(&$pdf, $pos, $width, $align, $text, $endofline=TRUE, $truncate=TRUE) {
  global $GCR_LINE_HEIGHT;

  // Truncate the string if necessary to fit the allotted width.
  while ($truncate && strlen($text) > 1 && $pdf->GetStringWidth($text) >= $width) {
    // Note ">=" above is necessary, ">" will not do.
    $text = substr($text, 0, -1);
  }

  $pdf->MultiCell(
    $width,               // width
    $GCR_LINE_HEIGHT,     // height
    $text,                // cell content
    0,                    // no border
    $align,               // alignment (L, C, R or J)
    0,                    // no background fill
    $endofline ? 1 : 0,   // next position: 0 = right, 1 = new line, 2 = below
    $pos,                 // x position
    '',                   // y position
    true,                 // if true reset the last cell height
    0,                    // stretch: 0 = disabled, 1 = optional scaling, 2 = forced scaling, 3 = optional spacing, 4 = forced spacing
    false,                // not html
    true,                 // auto padding to keep line width
    0                     // max height
  );
}

function gcrWriteLine(&$pdf, $xpos, $width) {
  global $GCR_LINE_HEIGHT;
  $ypos = $pdf->GetY() - ($GCR_LINE_HEIGHT / 2);
  $pdf->Line($xpos, $ypos, $xpos + $width, $ypos, array(
    'width' => 1,
    'cap'   => 'butt',
  ));
}

function gcrHeader(&$aReceipt, &$pdf) {
  global $GCR_PAGE_WIDTH, $GCR_LINE_HEIGHT;
  global $GCR_TOP_WIDTH_1, $GCR_TOP_WIDTH_2, $GCR_TOP_WIDTH_3;
  global $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH;

  $HEADER_POS_1 =   0;
  $HEADER_POS_2 =  89;

  // Add a page.
  $pdf->AddPage();

  // Set internal cell padding and height ratio.
  $pdf->SetCellPadding(0);
  $pdf->setCellHeightRatio(1.00);

  // Write visit date in the top line left column.
  gcrWriteCell($pdf, 0, $GCR_TOP_WIDTH_1, 'L', oeFormatShortDate($aReceipt['encounter_date']), FALSE);

  // Write the "Sales Receipt" centered in the top line middle column.
  gcrWriteCell($pdf, $GCR_TOP_WIDTH_1, $GCR_TOP_WIDTH_2, 'C', xl('Sales Receipt'), FALSE);

  // Write the invoice reference number in the top line right column.
  // TBD: Append the checkout sequence number to this.
  gcrWriteCell($pdf, $GCR_TOP_WIDTH_1 + $GCR_TOP_WIDTH_2, $GCR_TOP_WIDTH_3, 'R', $aReceipt['invoice_refno']);
  $pdf->Ln($GCR_LINE_HEIGHT);

  // Write the organization name line.
  gcrWriteCell($pdf, 0, $GCR_PAGE_WIDTH, 'C', $aReceipt['organization_name']);

  // Write the clinic name line if it's different.
  if ($aReceipt['facility_name'] != $aReceipt['organization_name']) {
    gcrWriteCell($pdf, 0, $GCR_PAGE_WIDTH, 'C', $aReceipt['facility_name']);
  }

  // Write the clinic address/city line unless both are empty.
  $tmp = $aReceipt['facility_street'];
  if ($tmp && $aReceipt['facility_city']) $tmp .= ', ';
  $tmp .= $aReceipt['facility_city'];
  if ($tmp) {
    gcrWriteCell($pdf, 0, $GCR_PAGE_WIDTH, 'C', $tmp);
  }

  // Write the clinic phone/fax line unless both are empty.
  $tmp = '';
  if ($aReceipt['facility_phone']) {
    $tmp = xl('Phone') . ': ' . $aReceipt['facility_phone'];
  }
  if ($aReceipt['facility_fax']) {
    if ($tmp) $tmp .= '  ';
    $tmp = xl('Fax') . ': ' . $aReceipt['facility_fax'];
  }
  if ($tmp) {
    gcrWriteCell($pdf, 0, $GCR_PAGE_WIDTH, 'C', $tmp);
  }

  // Write the clnic URL line if there is one.
  if ($aReceipt['facility_url']) {
    gcrWriteCell($pdf, 0, $GCR_PAGE_WIDTH, 'C', $aReceipt['facility_url']);
  }

  // Blank line.
  $pdf->Ln($GCR_LINE_HEIGHT);

  // Write detail section header.
  gcrWriteCell($pdf, 0, $GCR_DESC_WIDTH, 'L', xl('Item Name'), FALSE);
  gcrWriteCell($pdf, $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH, 'R', xl('Ext.Price'));

  // Blank line and then horizontal rule.
  $pdf->Ln($GCR_LINE_HEIGHT);
  gcrWriteLine($pdf, 0, $GCR_PAGE_WIDTH);
}

function gcrLine(&$aReceipt, &$pdf, $code, $description, $quantity, $price, $total) {
  global $GCR_LINE_HEIGHT, $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH;

  // Write a line for item description and extended price.
  gcrWriteCell($pdf, 0, $GCR_DESC_WIDTH, 'L', $description, FALSE);
  gcrWriteCell($pdf, $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH, 'R', sprintf("%01.2f", $total));

  // If quantity > 1 then write another line to indicate that and unit price.
  if ($quantity > 1) {
    gcrWriteCell($pdf, 0, $GCR_DESC_WIDTH, 'L', "  $quantity @ $price");
  }
}

function gcrFootLine(&$aReceipt, &$pdf, $description, $total) {
  global $GCR_LINE_HEIGHT, $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH;
  // Write a line for item description and extended price.
  gcrWriteCell($pdf, 0, $GCR_DESC_WIDTH, 'R', $description . ':', FALSE);
  gcrWriteCell($pdf, $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH, 'R', sprintf("%01.2f", $total));
}

function generateCheckoutReceipt($patient_id, $encounter_id, $billtime='') {
  global $GCR_PAGE_WIDTH, $GCR_PAGE_HEIGHT, $GCR_LINE_HEIGHT;
  global $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH;

  // Uncomment the next line if you want all activity and not just the last checkout.
  // $billtime = '';

  // This receipt version is for a particular checkout and so uses $billtime.
  $aReceipt = generateReceiptArray($patient_id, $encounter_id, $billtime);

  // $pdf = new TCPDF('P', 'pt', 'A4', true, 'UTF-8', false);
  $pdf = new TCPDF('P', 'pt', array($GCR_PAGE_WIDTH, $GCR_PAGE_HEIGHT), true, 'UTF-8', false);

  // Remove default header and footer.
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);

  // Set default monospaced font.
  $pdf->SetDefaultMonospacedFont('courier');

  // Set margins. Left, top, right.
  $pdf->SetMargins(0, 18, 0);

  // Disable auto page breaks.
  $pdf->SetAutoPageBreak(FALSE);

  // Set font. Might need something else like 'freeserif' for better utf8 support.
  $pdf->SetFont('courier', '', 10);
  // $pdf->SetFont('freeserif', '', 12);

  // Write page header section.
  gcrHeader($aReceipt, $pdf);

  $subtotal = 0;

  // Loop for detail lines.
  foreach ($aReceipt['items'] as $item) {
    if ($item['code_type'] == 'TAX') continue;
    // Insert a charge line unless this is only an adjustment.
    if ($item['charge'] != 0.00) {
      gcrLine($aReceipt, $pdf, $item['code'], $item['description'],
        $item['quantity'], $item['price'], $item['charge']);
      $subtotal += $item['charge'];
       // If there is an adjustment insert a line for it.
      if ($item['adjustment'] != 0.00) {
        $adjreason = $item['adjreason'] ? $item['adjreason'] : xl('Adjustment');
        gcrLine($aReceipt, $pdf, $item['code'], "  $adjreason",
          '', '', 0 - $item['adjustment']);
        $subtotal -= $item['adjustment'];
      }
    }
  }

  // Horizontal rule.
  $pdf->Ln($GCR_LINE_HEIGHT);
  gcrWriteLine($pdf, 0, $GCR_PAGE_WIDTH);

  // Subtotal line.
  gcrFootLine($aReceipt, $pdf, xl('Subtotal'), $subtotal);

  // A line for each chargeless adjustment in $aReceipt['items'].
  foreach ($aReceipt['items'] as $item) {
    if ($item['code_type'] == 'TAX') continue;
    // Insert a charge line unless this is only an adjustment.
    if ($item['charge'] == 0.00 && $item['adjustment'] != 0.00) {
      $adjreason = $item['adjreason'] ? $item['adjreason'] : xl('Adjustment');
      gcrFootLine($aReceipt, $pdf, $adjreason, 0 - $item['adjustment']);
      $subtotal -= $item['adjustment'];
    }
  }

  // A line for each tax in $aReceipt['items'].
  foreach ($aReceipt['items'] as $item) {
    if ($item['code_type'] != 'TAX') continue;
    gcrFootLine($aReceipt, $pdf, $item['description'], $item['charge']);
    $subtotal += $item['charge'];
  }

  // Short horizontal rule.
  $pdf->Ln($GCR_LINE_HEIGHT);
  gcrWriteLine($pdf, $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH);

  // Grand total line.
  gcrFootLine($aReceipt, $pdf, xl('RECEIPT TOTAL'), $subtotal);

  // Short horizontal rule and blank line.
  $pdf->Ln($GCR_LINE_HEIGHT);
  gcrWriteLine($pdf, $GCR_DESC_WIDTH, $GCR_MONEY_WIDTH);
  $pdf->Ln($GCR_LINE_HEIGHT);

  // TBD: Payment lines and balance due line.
  foreach ($aReceipt['payments'] as $item) {
    gcrFootLine($aReceipt, $pdf, $item['method'] . ' ' . xl('Payment'), $item['amount']);
    $subtotal -= $item['amount'];
  }

  // Balance Due line.
  gcrFootLine($aReceipt, $pdf, xl('Balance Due'), $subtotal);

  // Message to client, if there is one.
  if ($GLOBALS['gbl_checkout_receipt_note']) {
    $pdf->Ln($GCR_LINE_HEIGHT);
    gcrWriteCell($pdf, 0, $GCR_PAGE_WIDTH, 'C', $GLOBALS['gbl_checkout_receipt_note'], TRUE, FALSE);
  }

  // Advance the paper a bit more at the end.
  $pdf->Ln($GCR_LINE_HEIGHT * 2);

  // Reset pointer to last page.
  $pdf->lastPage();

  // Close and output the PDF document. I = inline to the browser.
  $pdf->Output('receipt.pdf', 'I');
}
?>
