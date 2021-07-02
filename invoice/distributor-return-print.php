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
$order = $_GET['id'];

$addressdetail = mysqli_query($conn, "SELECT * FROM `ss_distributor_return`  WHERE `id` = '".$order."' LIMIT 1");

if(mysqli_num_rows($addressdetail)>0){
    $obj = mysqli_fetch_object($addressdetail);

    $customer_id = $obj->customer_id;

    $re_no = $obj->re_no;
    $order_date = date('d-m-Y', strtotime($obj->createdate));
    $dated = date('d-m-Y', strtotime($obj->createdate));


}
    
$customer = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_distributors WHERE id = '".$customer_id."' ")); 

$name    = $customer->owner;
$address = $customer->customeraddress;
$gst_no  = $customer->gst;
$mobile  = $customer->mobile;
$state   = $customer->state;
$pan   = $customer->pan_no;
$state_name = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_state WHERE id = '".$state."'"));
$s_name = $state_name->state_name; 

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    public function Header()
    {
        
        $html = '<p style="color:black; font-size:12px; text-align: center;"><strong style="color:green; font-size:18px; padding-bottom:1000px;">Yara Electronics (A Div of Cheenu Amma Alloy P Ltd )</strong><br/>SF.No: 416/7, Thanneer Pandal,Vilankurichi Road, Peelamedu, Coimbatore - 641004<br>GSTIN\UIN : 33AADCC7429F1ZV<br><strong style="color:black; text-align:center; font-size:17px;"> RETURN INVOICE</strong><br><br></p>';

        $this->writeHTMLCell(
            $w=0,
            $h=0,
            $x=15,
            $y=10,
            $html,
            $border=0,
            $ln=1,
            $fill=false,
            $reseth=false,
            $align='C'
        );


        //$this->writeHTML("<br><br>", true, false, false, false, '');
    }
    
        // $center = K_PATH_IMAGES.'logo.png';
     //    $this->SetAlpha(0.3);
     //    $this->Image($center, 80, 100, 40, 40, '', '', '', true, 2, '', false, false, 500);
    

    // Page footer
 public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(0);
        // Set font
        $this->SetFont('times', '', 11);
        // Page number
        // Logo
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        // $footertext = '<strong>SRI MURUGAN FOUNDRY EQUIPMENT</strong>';
        // $this->writeHTML($footertext,80, 100, 40, 40, '', '', '', false, 300, '', false, false, 0);
        // $image_file = K_PATH_IMAGES."TUV-Logo.png";
        // $this->Image($image_file, 0, 275, 20, "", "PNG", "", "T", false, 300, "R", false, false, 0, false, false, false);
        $this->writeHTML("<hr>", true, false, false, false, '');
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

// set font
$pdf->SetFont('times', '', 11);

// add a page
$pdf->AddPage("L");



$html ='<br><br><br>
<table border= "1" cellpadding="1" top="100">
        <tr>
            <td rowspan="3" style="font-size:12px; width: 55%;">Shipped To: <br>'.$name.'<br>'.$address.'<br>'.$mobile.'<br>'.$s_name.', Code:'.$state.'<br>GSTIN\UIN :'.$gst_no.'</td>
            <td style="font-size:12px; width: 20%;">Invoice No</td>
            <td style="font-size:12px; width: 25%;">'.$re_no.'</td>
        </tr>
        <tr>
            <td style="font-size:12px; width: 20%;">Dated</td>
            <td style="font-size:12px; width: 25%;">'.$dated.'</td>
        </tr>
        <tr>
            <td style="font-size:12px; width: 20%;">Others</td>
            <td style="font-size:12px; width: 25%;"></td>
        </tr>
    </table>
    ';


$pdf->writeHTML($html, true, false, true, false, '');

$html ='<br><br><table border= "1" cellpadding="1" top="100">
        <tr>
            <td style="font-size:12px; width: 10%;">S.No</td>
            <td style="font-size:12px; width: 30%;">Description</td>
            <td style="font-size:12px; text-align: center; width: 20%;">HSN</td>
            <td style="font-size:12px; text-align: center; width: 20%;">Price</td>
            <td style="font-size:12px; text-align: center; width: 20%;">Qty</td>
        </tr>';


        $_QRY = mysqli_query($conn, "SELECT sa.title AS c_title, sb.qty AS qty, sc.title AS p_title, sc.description AS description, sc.hsn AS hsn, sc.oprice AS oprice, sb.code AS code FROM ss_category sa JOIN ss_distributor_return_details sb ON sa.id = sb.cid JOIN ss_items sc ON sb.pid = sc.id WHERE sb.re_id = '".$order."' AND sb.qty != '0' AND sb.published = '1' AND sb.status = '1'");
        $_coun = mysqli_num_rows($_QRY);
        if($_coun > 0)
        {
            $Sno=0;
            $_qty=0;
            while($_RES = mysqli_fetch_object($_QRY))
            {
                $description = isset($_RES->description)?$_RES->description:'';
                $hsn_value   = isset($_RES->hsn)?$_RES->hsn:'';
                $oprice      = isset($_RES->oprice)?$_RES->oprice:'';
                $qty_value   = isset($_RES->qty)?$_RES->qty:'';

                $code  = mysqli_fetch_object(mysqli_query($conn, "SELECT code FROM ss_item_stk WHERE id = '".$_RES->code."' AND published = '1'"));

                $_qty += $_RES->qty;


                $html .= '
                    <tr>
                        <td style="font-size:12px; width: 10%;">'.++$Sno.'</td>
                        <td style="font-size:12px; width: 30%;">'.$description.'</td>
                        <td style="font-size:12px; text-align: center; width: 20%;">'.$hsn_value.'</td>
                        <td style="font-size:12px; text-align: right; width: 20%;">'.$oprice.'</td>
                        <td style="font-size:12px; text-align: center; width: 20%;">'.$qty_value.'</td>
                    </tr>
                ';

            }

            $html .= '
            <tr>
                <td class="no-line" colspan="4" style="font-size:12px; text-align:right;"> Total  </td>
                <td style="text-align: right; font-size:12px; text-align:center;" class="no-line">'.$_qty.'</td>
            </tr>';
        }
        else
        {
            $html .= '<tr><td colspan="8">Now Rows!..</td></tr>';
        }

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$html ='<br><br>
        <table border= "1" cellpadding="1">
            <tr>
                <th style="width:50%; font-size:12px;" colspan="3">
                    <span>Company(s) PAN : AADCC7428F </span>
                    <p> Declaration: We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct. <br> We had received the goods as per the ordered quantity & in Good Condition. We accept / agree to pay the company for this invoice on a credit of 30 days without any interest and/or credit period between 30 days to 90 days with an additional 2% interest per Month. I/We unconditionally agree to pay for the invoice in the above terms.</p></th>

                <td style="width:50%; font-size:12px;" colspan="3" class="no-line"><span> for Yara Electronics (A Div of Cheenu Amma Alloy P Ltd )</span> <br><br><br> <p style="text-align: right; "> Authorised Signatory</p></td>
            </tr>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->setAutoPageBreak(true, 10);



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
