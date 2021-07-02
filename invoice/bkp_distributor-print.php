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
include('../../../inc/config.php');
require_once('tcpdf_include.php');
$order = $_GET['id'];

$addressdetail = mysqli_query($conn, "SELECT * FROM `ss_distributor_invoice`  WHERE `id` = '".$order."' LIMIT 1");

if(mysqli_num_rows($addressdetail)>0){
    $obj = mysqli_fetch_object($addressdetail);

    $customer_id = $obj->customer_id;

    $so_no = $obj->so_no;
    $order_date = date('d-m-Y', strtotime($obj->order_date));
    $delivery_nt = $obj->delivery_nt;
    $payment = $obj->payment;
    $supplier_ref = $obj->supplier_ref;
    $other_ref = $obj->other_ref;
    $order_no = $obj->order_no;
    $dated = date('d-m-Y', strtotime($obj->dated));
    $docu_no = $obj->docu_no;
    $delivery_date = date('d-m-Y', strtotime($obj->delivery_date));
    $despatched = $obj->despatched;
    $destination = $obj->destination;
    $terms = $obj->terms;


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
        
        $html = '<p style="color:black; font-size:12px; text-align: center;"><strong style="color:green; font-size:18px; padding-bottom:1000px;">Yara Electronics (A Div of Cheenu Amma Alloy P Ltd )</strong><br/>SF.No: 416/7, Thanneer Pandal,Vilankurichi Road, Peelamedu, Coimbatore - 641004<br>GSTIN\UIN : 33AADCC7429F1ZV<br><strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE</strong><br><br></p>';

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
$pdf->SetTitle('TCPDF Example 003');
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
            <td rowspan="6" style="font-size:12px; width: 55%;">Shipped To: <br>'.$name.'<br>'.$address.'<br>'.$mobile.'<br>'.$s_name.', Code:'.$state.'<br>GSTIN\UIN :'.$gst_no.'</td>
            <td style="font-size:12px; width: 20%;">Invoice No</td>
            <td style="font-size:12px; width: 25%;">'.$so_no.'</td>
        </tr>
        <tr>
            <td style="font-size:12px; width: 20%;">Buyer(s) Order No</td>
            <td style="font-size:12px; width: 25%;">'.$order_no.'</td>
        </tr>
        <tr>
            <td style="font-size:12px; width: 20%;">Dated</td>
            <td style="font-size:12px; width: 25%;">'.$dated.'</td>
        </tr>
        <tr>
            <td style="font-size:12px; width: 20%;">Eway Bill No</td>
            <td style="font-size:12px; width: 25%;">'.$docu_no.'</td>
        </tr>
        <tr>
            <td style="font-size:12px; width: 20%;">Vehicle No</td>
            <td style="font-size:12px; width: 25%;"></td>
        </tr>
        <tr>
            <td style="font-size:12px; width: 20%;">Others</td>
            <td style="font-size:12px; width: 25%;">'.$despatched.'</td>
        </tr>
    </table>
    ';


$pdf->writeHTML($html, true, false, true, false, '');

$html ='<br><br><table border= "1" cellpadding="1" top="100">
        <tr>
            <td rowspan="2" style="font-size:12px; width: 5%;">S.No</td>
            <td rowspan="2" style="font-size:12px; width: 12%;">Description</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">HSN</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">Price</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 5%;">Qty</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 6%;">B A</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 6%;">STA</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">D A</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">A D</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">Taxable Val</td>
            <td colspan="2" style="font-size:12px; text-align: center; width: 12%;">CGST</td>
            <td colspan="2" style="font-size:12px; text-align: center; width: 12%;">SGST</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 8%;">Amount</td> 
        </tr>
        <tr>
            <th style="font-size: 12px;">Rate</th>
            <th style="font-size: 12px;">Amt</th>
            <th style="font-size: 12px;">Rate</th>
            <th style="font-size: 12px;">Amt</th>
        </tr>';


        $_QRY = mysqli_query($conn, "SELECT sa.title AS c_title, sb.qty AS qty, sb.price AS price, sb.gst AS gst, sb.allowance AS allowance, sb.sta AS sta, sb.code AS code, sb.d_allowance AS d_allowance, sb.discount AS discount, sb.value_pri AS value_pri, sc.title AS p_title, sc.description AS description, sc.hsn AS hsn, sc.oprice AS oprice, sb.code AS code FROM ss_category sa JOIN ss_distributor_inv_details sb ON sa.id = sb.cid JOIN ss_items sc ON sb.pid = sc.id WHERE sb.so_id = '".$order."' AND sb.published = '1' AND sb.status = '1'");
        $_coun = mysqli_num_rows($_QRY);
        if($_coun > 0)
        {
            $sub_tot = 0;
            $sub_to = 0;
            $Sno = 0;
            $qty = 0;
            $price = 0;
            $allowance =0;
            $sta=0;
            $_sta=0;
            $gst_ = 0;
            $d_allowance=0;
            $_netdis=0;
            $discount=0;
            $netval=0;
            $netpay = 0;
            $netdis = 0;
            $gstper =0;
            $discount =0;
            $gst_amt=0;
            $overallgstprice=0;
            $allowance_val=0;
            $d_allowance_val=0;
            $_dp=0;
            $_total=0;
            $gst_val=0;
            $cal_gst=0; 
            $sub_to_=0;
            $total_val=0;
            $tax_val=0;
            $_qty=0;
            while($_RES = mysqli_fetch_object($_QRY))
            {
                $code  = mysqli_fetch_object(mysqli_query($conn, "SELECT code FROM ss_item_stk WHERE id = '".$_RES->code."' AND published = '1'"));

                // $gst_ = $_RES->price * $_RES->gst / 100;

                // $_dp = $_RES->price - $gst_;

                // $gstper = $_RES->qty * $gst_;

                // $overallgstprice += $gstper;

                // $amt_val = $_RES->qty * $_RES->price;

                // $allowance = $_RES->price * $_RES->allowance / 100;

                // $allowance_val = $_RES->qty * $allowance;

                // $d_allowance = $_RES->price * $_RES->d_allowance / 100;

                // $d_allowance_val = $_RES->qty * $d_allowance;

                // $price = $_RES->price;

                // $_discount = $_RES->discount;

                // $discount = $_RES->qty * $_RES->discount;

                // $_sta = $_RES->qty * $_RES->sta;

                // $netval = $_RES->price - $gst_;

                // $netdis = $allowance_val + $_sta + $d_allowance_val + $discount;

                // $_netdis = $_RES->qty * $_dp;

                // $netpay  = $_netdis - $netdis;

                // $sub_to += $netpay;

                // $gst_amt += $gst_;

                // $_total = $sub_to + $overallgstprice;

                $_qty += $_RES->qty;

                $gst_amt = $_RES->gst / 2;

                $allowance = $_RES->price * $_RES->allowance / 100;

                $allowance_val = $_RES->qty * round($allowance);

                $d_allowance = $_RES->price * $_RES->d_allowance / 100;

                $d_allowance_val = $_RES->qty * round($d_allowance);

                $discount = $_RES->qty * round($_RES->discount);

                $_sta = $_RES->qty * round($_RES->sta);

                $netdis = $allowance_val + $_sta + $d_allowance_val + $discount;

                $sub_to = $_RES->qty * $_RES->price;

                $sub_tot += $sub_to;

                $total = $sub_to - $netdis;

                $_total += $total;

                $_gst = "1.".$_RES->gst;

                $_val = $total / $_gst;

                $tax_val += $_val; 

                $gstper = $_val * $_RES->gst / 100;

                $total_val = $_val + $gstper;

                $cal_gst = $gstper / 2;

                $gst_ += $gstper;

                $gst_val = $gst_/2;

                $sub_to_ = $_val + $gstper;

                $netval += $sub_to_;

                $html .= '
                    <tr>
                        <td style="font-size:12px; width: 5%;">'.++$Sno.'</td>
                        <td style="font-size:12px; width: 12%;">'.$_RES->code.'</td>
                        <td style="font-size:12px; text-align: center; width: 7%;">'.$_RES->hsn.'</td>
                        <td style="font-size:12px; text-align: right; width: 7%;">'.$_RES->price.'</td>
                        <td style="font-size:12px; text-align: center; width: 5%;">'.$_RES->qty.'</td>
                        <td style="font-size:12px; text-align: center; width: 6%;">'.round($allowance_val).'</td>
                        <td style="font-size:12px; text-align: center; width: 6%;">'.round($_sta).'</td>
                        <td style="font-size:12px; text-align: center; width: 7%;">'.round($d_allowance_val).'</td>
                        <td style="font-size:12px; text-align: center; width: 7%;">'.round($discount).'</td>
                        <td style="font-size:12px; text-align: right; width: 7%;">'.number_format($_val, 2).'</td>
                        <td style="font-size:12px; text-align: right; width: 6%;">'.round($gst_amt).' %</td>
                        <td style="font-size:12px; text-align: right; width: 6%;">'.number_format($cal_gst, 2).'</td>
                        <td style="font-size:12px; text-align: right; width: 6%;">'.round($gst_amt).' %</td>
                        <td style="font-size:12px; text-align: right; width: 6%;">'.number_format($cal_gst, 2).'</td>
                        <td style="font-size:12px; text-align: right; width: 8%;">'.round($total_val).'</td>
                    </tr>
                ';

            }

            $html .= '
            <tr>
                <td class="no-line" colspan="4" style="font-size:12px; text-align:right;"> Total  </td>
                <td style="text-align: right; font-size:12px; text-align:center;" class="no-line">'.$_qty.'</td>
                <td style="text-align: right; font-size:12px;" class="no-line"></td>
                <td style="text-align: right; font-size:12px;" class="no-line"></td>
                <td style="text-align: right; font-size:12px;" class="no-line"></td>
                <td style="text-align: right; font-size:12px;" class="no-line"></td>
                <td style="text-align: right; font-size:12px;" class="no-line">Rs.'.round($tax_val).'</td>
                <td style="text-align: right; font-size:12px;" class="no-line"></td>
                <td style="text-align: right; font-size:12px;" class="no-line">Rs.'.round($gst_val).'</td>
                <td style="text-align: right; font-size:12px;" class="no-line"></td>
                <td style="text-align: right; font-size:12px;" class="no-line">Rs.'.round($gst_val).'</td>
                <td style="text-align: right; font-size:12px;" class="no-line">Rs.'.round($_total).'</td>
            </tr>
            <tr>
                <th colspan="15" style="font-size:12px;">Amount (in words) : '.numberTowords($_total).' RUPEES ONLY</th>
            </tr>';
        }
        else
        {
            $html .= '<tr><td colspan="8">Now Rows!..</td></tr>';
        }

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// <td rowspan ="9"  colspan="7">Amount Chargeable (in words) '.numberTowords(round($netpay)).'ONLY</td>

// if($state != 30)
// {
//     $html ='<br><br><table border= "1" cellpadding="1" top="100">
//             <tr>
//                 <td rowspan="2" style="text-align: center; width: 30%;">HSN/SAC</td>
//                 <td rowspan="2" style="text-align: center; width: 30%;">Taxable Value</td>
//                 <td colspan="2" style="text-align: center; width: 40%;">IGST</td>
//             </tr>
//             <tr>
//                 <td style="text-align: center;">Rate</td>
//                 <td style="text-align: center;">Amount</td>
//             </tr>';


//             $_QRY = mysqli_query($conn, "SELECT sa.title AS c_title, sb.qty AS qty, sb.price AS price, sb.gst AS gst, sb.allowance AS allowance, sb.discount AS discount, sb.value_pri AS value_pri, sc.title AS p_title, sc.description AS description, sc.hsn AS hsn FROM ss_category sa JOIN ss_sales_details sb ON sa.id = sb.cid JOIN ss_items sc ON sb.pid = sc.id WHERE sb.so_id = '".$order."'");
//             $_coun = mysqli_num_rows($_QRY);
//             if($_coun > 0)
//             {
//                 $Sno = 0;
//                 while($_RES = mysqli_fetch_object($_QRY))
//                 {
//                     $csgstpar = $_RES->gst / 2;

//                     $csgstamt = $_RES->price * $_RES->gst / 100;

//                     // $csgsttot = $csgstamt / 2;

//                     $html .= '
//                         <tr>
//                             <td style="width: 30%;">'.$_RES->hsn.'</td>
//                             <td style="text-align: center; width: 30%;">'.$_RES->value_pri.'</td>
//                             <td style="text-align: center; width: 20%;">'.$_RES->gst.' %</td>
//                             <td style="text-align: center; width: 20%;">'.$csgstamt.'</td>
//                         </tr>
//                     ';
//                 }

//                 $_TOTAL  = mysqli_fetch_object(mysqli_query($conn, "SELECT SUM(value_pri) AS value_pri FROM ss_sales_details WHERE so_id = '".$order."'"));

//                 $SUM_Total = "0"; $result = ""; $SQL = "";

//                 $SQL = "SELECT gst AS gst, price AS price FROM ss_sales_details WHERE so_id = '".$order."'";

//                 $result = mysqli_query($conn,$SQL);

//                 if (mysqli_num_rows($result) > 0){

//                     while ($obj = mysqli_fetch_object($result)){

//                         $csgstamt = $obj->price * $obj->gst / 100;

//                         $SUM_Total += $csgstamt / 2;

//                     }

//                 }

//                 $html .= '
//                     <tr>
//                         <td style="text-align:right;" class="no-line">Total</td>
//                         <td style="text-align:right;" class="no-line">Rs.'.$_TOTAL->value_pri.'</td>
//                         <td style="text-align:right;" class="no-line"></td>
//                         <td style="text-align:right;" class="no-line">Rs.'.$SUM_Total.'</td>
//                     </tr>
//                     <tr>
//                         <th colspan="2"><p>Company(s) PAN : AABFC2951P</p><br><span>Declaration:We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</span></th>
//                         <td colspan="2" class="no-line"><span>for Cheenu Enterprises(Appliances Division)</span> <br><br><br> <p style="text-align: right; ">Authorised Signatory</p></td>
//                     </tr>';
//             }
//             else
//             {
//                 $html .= '<tr><td colspan="8">Now Rows!..</td></tr>';
//             }
//     $html .= '</table>';
//     $pdf->writeHTML($html, true, false, true, false, '');   
// }

// else
// {
    $html ='<br><br>
            <table border= "1" cellpadding="1">
                <tr>
                    <th style="width:50%; font-size:12px;" colspan="3">
                        <span>Company(s) PAN : AADCC7428F </span><br><br><br>
                        <p> Declaration:We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p></th>

                    <td style="width:50%; font-size:12px;" colspan="3" class="no-line"><span> for Yara Electronics (A Div of Cheenu Amma Alloy P Ltd )</span> <br><br><br> <p style="text-align: right; "> Authorised Signatory</p></td>
                </tr>';
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
