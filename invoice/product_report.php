<?php
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
include('../inc/config.php');
require_once('lib/TCPDF/tcpdf.php');
require_once('lib/TCPDF/config/tcpdf_config.php');
 

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    public function Header()
    {
        
        // $html = '<p style="color:black; font-size:12px;"><strong style="color:green; font-size:18px; padding-bottom:1000px;">BLACK PEARL MARKETING</strong><br/>No.214, SURAMANGALAM MAIN ROAD, UDAYAR MILLS, LEIGH BAZAAR ,SALEM - 636 009<br>GSTIN\UIN : 33AAUFB9418M1ZT<br><strong style="color:black; text-align:center; font-size:17px;"> PRODUCT STOCK REPORT</strong><br><br></p>';

        // $this->writeHTMLCell(
        //     $w=0,
        //     $h=0,
        //     $x=5,
        //     $y=0,
        //     // $html,
        //     $border=0,
        //     $ln=1,
        //     $fill=false,
        //     $reseth=false,
        //     $align='C'
        // );


        //$this->writeHTML("<br><br>", true, false, false, false, '');
    }
    
    

    // Page footer
 public function Footer() {
        // Position at 15 mm from bottom
        // $this->SetY(0);
        // Set font
        $this->SetFont('times', '', 11);
        // Page number
        // Logo
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        // $footertext = '<strong>SRI MURUGAN FOUNDRY EQUIPMENT</strong>';
        // $this->writeHTML($footertext,80, 100, 40, 40, '', '', '', false, 300, '', false, false, 0);
        // $image_file = K_PATH_IMAGES."TUV-Logo.png";
        // $this->Image($image_file, 0, 275, 20, "", "PNG", "", "T", false, 300, "R", false, false, 0, false, false, false);
        $this->writeHTML(true, false, false, false, '');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Yara Electronics (A Div of Cheenu Amma Alloy P Ltd )');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
$pdf->AddPage("L");

$html = '<p style="color:black; font-size:12px; text-align: center;"><strong style="color:green; font-size:18px; padding-bottom:1000px;">Yara Electronics (A Div of Cheenu Amma Alloy P Ltd )</strong><br/>SF.No: 416/7, Thanneer Pandal,Vilankurichi Road, Peelamedu, Coimbatore - 641004<br>GSTIN\UIN : 33AADCC7429F1ZV<br><strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE</strong><br><br></p>';

$pdf->writeHTML($html, true, false, true, false, '');

// set font
$pdf->SetFont('times', '', 11);

// add a page



$html ='<br><br><table border= "1" cellpadding="1" top="100">
        <thead>
            <tr>
                <td style="font-size:12px; width: 5%; text-align: center;">S.No</td>
                <td style="font-size:12px; text-align: center; width: 30%;">Product Name</td>
                <td style="font-size:12px; text-align: center; width: 20%;">Category Name</td>
                <td style="font-size:12px; text-align: center; width: 10%;">Brand Name</td>
                <td style="font-size:12px; text-align: center; width: 15%;">Description</td>
                <td style="font-size:12px; text-align: center; width: 10%;">Stock</td>
                <td style="font-size:12px; text-align: center; width: 10%;">Price</td>
            </tr>
        </thead>';


        $_QRY = mysqli_query($conn, "SELECT * FROM `ss_items` WHERE extra != 0 AND `published` = '1' AND status = '1'");
        $_coun = mysqli_num_rows($_QRY);
        if($_coun > 0)
        {
            $Sno = 0;
            while($_RES = mysqli_fetch_object($_QRY))
            {

                $title  = isset($_RES->title)?$_RES->title:'';
                $desc   = isset($_RES->description)?$_RES->description:'';
                $extra  = isset($_RES->extra)?$_RES->extra:'';
                $oprice = isset($_RES->oprice)?$_RES->oprice:'';

                $code  = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE id = '".$_RES->cid."'"));

                $c_title = isset($code->title)?$code->title:'';

                $brand = mysqli_fetch_object(mysqli_query($conn, "SELECT `brand` FROM `ss_brands` WHERE `id` = '".$_RES->brand."' AND `published` = '1' "));

                $b_title = isset($brand->brand)?$brand->brand:'';

                $html .= '
                    <tbody>
                        <tr>
                            <td style="font-size:12px; width: 5%; text-align: center;">'.++$Sno.'</td>
                            <td style="font-size:12px; width: 30%;"> '.$title.'</td>
                            <td style="font-size:12px; width: 20%;"> '.$c_title.'</td>
                            <td style="font-size:12px; width: 10%; text-align: center;">'.$b_title.'</td>
                            <td style="font-size:12px; width: 15%;"> '.$desc.'</td>
                            <td style="font-size:12px; text-align: center; width: 10%;">'.$extra.'</td>
                            <td style="font-size:12px; text-align: center; width: 10%;">'.$oprice.'</td>
                        </tr>
                    </tbody>
                ';

            }
        }
        else
        {
            $html .= '<tr><td colspan="7"> No Records Found!..</td></tr>';
        }

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->setAutoPageBreak(true, 10);
// }

// $html = '<table border="1"><tr>
//  <th>1111</th>
// <tr></table>';
// $pdf->writeHTML($html, true, false, true, false, '');
// set some text to print
// $txt = <<<EOD
// TCPDF Example 003

// Custom page header and footer are defined by extending the TCPDF class and overriding the Header() and Footer() methods.
// EOD;

// print a block of text using Write()
// $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
