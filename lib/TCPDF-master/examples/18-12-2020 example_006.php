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
session_start();
include('../../../inc/config.php');
require_once('tcpdf_include.php');
$order = $_GET['id'];

$addressdetail = mysqli_query($conn, "SELECT * FROM `ss_sales`  WHERE `id` = '".$order."' LIMIT 1");

if(mysqli_num_rows($addressdetail)>0){
    $obj = mysqli_fetch_object($addressdetail);

    $customer_id = $obj->customer_id;
    $userid = $obj->userid;
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
    
$customer = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_customers WHERE id = '".$customer_id."' ")); 

$name     = isset($customer->name)?$customer->name:'---';
$cname    = isset($customer->cname)?$customer->cname:'----';
$pincode  = isset($customer->pincode)?$customer->pincode:'---';
$address  = isset($customer->address)?$customer->address:'---';
$gst_no   = isset($customer->gst_no)?$customer->gst_no:'---';
$mobile   = isset($customer->mobile)?$customer->mobile:'---';
$state    = isset($customer->state)?$customer->state:'---';
$tcs_type = isset($customer->tcs_type)?$customer->tcs_type:'---';
$tcs_no   = isset($customer->tcs_no)?$customer->tcs_no:'---';

$state_name = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_state WHERE id = '".$state."'"));
$s_name = $state_name->state_name; 
$distributor = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_distributors WHERE id = '".$userid."' "));
$_SESSION['dname']    = $distributor->owner;
$_SESSION['dcname']  = $distributor->store;
$_SESSION['daddress'] = $distributor->customeraddress;
$_SESSION['dgst_no'] = $distributor->gst;
$_SESSION['dmobile']  = $distributor->mobile;
$_SESSION['dstate']  = $distributor->state;
$_SESSION['email_id']   = $distributor->email_id;
$_SESSION['pan_no']   = $distributor->pan_no;
$_SESSION['dcity']   = $distributor->location;
$dstate_name = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_state WHERE id = '".$_SESSION['dstate']."'"));
$_SESSION['ds_name'] = $dstate_name->state_name; 
$dcity_name = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_city WHERE id = '".$_SESSION['dcity']."'"));
$_SESSION['dc_name'] = $dcity_name->city_name;

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    public function Header()
    {
        
        $html = '<p style="color:black; font-size:12px;"><strong style="color:green; font-size:18px; padding-bottom:1000px;">'.$_SESSION['dcname'].'</strong><br/>'.$_SESSION['daddress'].' '.$_SESSION['dc_name'].' '.$_SESSION['ds_name'].'<br>GSTIN\UIN : '.$_SESSION['dgst_no'].'<br><strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE</strong><br></p>';

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
            <td rowspan="6" style="font-size:12px; width: 55%;">Shipped To: <br>'.$cname.'<br>'.$name.'<br>'.$address.', '.$pincode.'<br>'.$mobile.'<br>'.$s_name.', Code:'.$state.'<br>GSTIN\UIN :'.$gst_no.'<br>TCS.No :'.$tcs_no.'</td>
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
            <td rowspan="2" style="font-size:12px; width: 4%;">S.No</td>
            <td rowspan="2" style="font-size:12px; width: 12%;">Description</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">HSN</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">Price</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 5%;">Qty</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 6%;">B A</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 6%;">STA</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">D A</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">A D</td>
            <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">Taxable Val</td>';
            if($state == 33)
            {
                $html .='<td colspan="2" style="font-size:12px; text-align: center; width: 12%;">CGST</td>
                    <td colspan="2" style="font-size:12px; text-align: center; width: 12%;">SGST</td>';
            }
            else
            {
                $html .='<td colspan="2" style="font-size:12px; text-align: center; width: 24%;">IGST</td>';
            }
            $html .='<td rowspan="2" style="font-size:12px; text-align: center; width: 8%;">Amount</td> 
        </tr>
        <tr>';
            if($state == 33)
            {
                $html .='<th style="font-size: 12px;">Rate</th>
                        <th style="font-size: 12px;">Amt</th>
                        <th style="font-size: 12px;">Rate</th>
                        <th style="font-size: 12px;">Amt</th>';
            }
            else
            {
                $html .='<th style="font-size: 12px;">Rate</th>
                        <th style="font-size: 12px;">Amt</th>';
            }
        $html .='</tr>';


        $_QRY = mysqli_query($conn, "SELECT sa.title AS c_title, sb.so_no AS so_no, sb.pid AS pid, sb.qty AS qty, sb.price AS price, sb.gst AS gst, sb.allowance AS allowance, sb.sta AS sta, sb.code AS code, sb.d_allowance AS d_allowance, sb.discount AS discount, sb.value_pri AS value_pri, sc.title AS p_title, sc.description AS description, sc.hsn AS hsn, sc.oprice AS oprice, sb.code AS code FROM ss_category sa JOIN ss_sales_details sb ON sa.id = sb.cid JOIN ss_items sc ON sb.pid = sc.id WHERE sb.so_id = '".$order."' AND sb.published = '1' AND sb.status = '1'");
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
            $tcs_val=0;
            $_qty=0;
            $net_total=0;
            $rond_tot=0;
            $lst_tot=0;

            while($_RES = mysqli_fetch_object($_QRY))
            {
                $code  = mysqli_fetch_object(mysqli_query($conn, "SELECT code FROM ss_item_stk WHERE id = '".$_RES->code."' AND published = '1'"));

                $serial_no = '';
                
                $serial = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE `product_id` = '".$_RES->pid."' AND `delar_sales` = '".$_RES->so_no."' AND published = '1'");
                    
                while($row = mysqli_fetch_object($serial))
                {
                    $serial_no .= $row->code.' ,';
                }

                $serial_val = substr_replace($serial_no, "", -1);

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

                // $_total += $total;

                $_gst = "1.".$_RES->gst;

                $_val = $total / $_gst;
              
              	$_val = $total / $_gst;

                if($tcs_type == '2')
                {
                    $_tcs = $_val * 0.1 / 100;
                }
                else
                {
                    $_tcs = 0;
                }

                if($tcs_type == '2')
                {
                    $tcs_val += $_tcs; 
                }
                else
                {
                    $tcs_val = 0;
                }

                $_total += $total;

                $net_total = $_total + $tcs_val;

                $lst_tot = round($net_total);

                $rond_tot = $lst_tot - $net_total;

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
                        <td style="font-size:12px; width: 4%;">'.++$Sno.'</td>
                        <td style="font-size:12px; width: 12%;">'.$_RES->code.'</td>
                        <td style="font-size:12px; text-align: center; width: 7%;">'.$_RES->hsn.'</td>
                        <td style="font-size:12px; text-align: right; width: 7%;">'.$_RES->price.'</td>
                        <td style="font-size:12px; text-align: center; width: 5%;">'.$_RES->qty.'</td>
                        <td style="font-size:12px; text-align: center; width: 6%;">'.round($allowance_val).'</td>
                        <td style="font-size:12px; text-align: center; width: 6%;">'.round($_sta).'</td>
                        <td style="font-size:12px; text-align: center; width: 7%;">'.round($d_allowance_val).'</td>
                        <td style="font-size:12px; text-align: center; width: 7%;">'.round($discount).'</td>
                        <td style="font-size:12px; text-align: right; width: 7%;">'.number_format($_val, 2).'</td>';
                        if($state == 33)
                        {
                            $html .='<td style="font-size:12px; text-align: right; width: 6%;">'.round($gst_amt).' %</td>
                                    <td style="font-size:12px; text-align: right; width: 6%;">'.number_format($cal_gst, 2).'</td>
                                    <td style="font-size:12px; text-align: right; width: 6%;">'.round($gst_amt).' %</td>
                                    <td style="font-size:12px; text-align: right; width: 6%;">'.number_format($cal_gst, 2).'</td>';
                        }
                        else
                        {
                            $html .='<td style="font-size:12px; text-align: right; width: 12%;">'.round($_RES->gst).' %</td>
                                    <td style="font-size:12px; text-align: right; width: 12%;">'.number_format($gstper, 2).'</td>';
                        }
                        
                        $html .='<td style="font-size:12px; text-align: right; width: 8%;">'.round($total_val).'</td>
                    </tr>
                    <tr><td colspan="15">Serial No: '.$serial_val.'</td></tr>
                ';
            }
            if($state == 33)
            {
                $colspan = '12';
            }
            else
            {
                $colspan = '10';
            }

            $html .= '
                <tr>
                    <th rowspan ="6"  colspan="'.$colspan.'"></th>
                    <th colspan="2" class="text-right">Qty</th>
                    <th>'.$_qty.'</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">Sub Total</th>
                    <th>'.number_format($tax_val, 2).'</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">GST %</th>
                    <th>'.number_format($gst_, 2).'</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">TCS Val</th>
                    <th>'.number_format($tcs_val, 2).'</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">Round off</th>
                    <th>'.number_format($rond_tot, 2).'</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">Net Total</th>
                    <th>'.number_format($lst_tot, 2).'</th>
                </tr>
                <tr>
                    <th colspan="15" class="text-right">Amount (in words) : '.numberTowords($lst_tot).' RUPEES ONLY</th>
                </tr>
            ';
        }
        else
        {
            $html .= '<tr><td colspan="15">Now Rows!..</td></tr>';
        }

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

    $html ='<br><br>
            <table border= "1" cellpadding="1">
                <tr>
                    <th style="width:50%; font-size:12px;" colspan="3">
                        <span>Company(s) PAN : '.$_SESSION['pan_no'].'</span><br><br><br>
                        <p> Declaration:We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p></th>

                    <td style="width:50%; font-size:12px;" colspan="3" class="no-line"><span> for '.$_SESSION['dcname'].'</span> <br><br><br> <p style="text-align: right; "> Authorised Signatory</p></td>
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
?>