<?php

    /**
     * Creates an example PDF TEST document using TCPDF
     * @package com.tecnick.tcpdf
     * @abstract TCPDF - Example: Custom Header and Footer
     * @author Nicola Asuni
     * @since 2008-03-04
     */

    // Include the main TCPDF library (search for installation path).
    session_start();
    include('../inc/config.php');
    require_once('lib/TCPDF/tcpdf.php');
    require_once('lib/TCPDF/config/tcpdf_config.php');
    $order = $_GET['id'];

    $addressdetail = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_sales`  WHERE `id` = '".$order."' LIMIT 1"));

    $so_no      = !empty($addressdetail->so_no)?$addressdetail->so_no:'---';
    $cus_id     = !empty($addressdetail->customer_id)?$addressdetail->customer_id:'---';
    $order_no   = !empty($addressdetail->order_no)?$addressdetail->order_no:'---';
    $dated      = !empty($addressdetail->dated)?date('d-m-Y', strtotime($addressdetail->dated)):'---';
    $docu_no    = !empty($addressdetail->docu_no)?$addressdetail->docu_no:'---';
    $despatched = !empty($addressdetail->despatched)?$addressdetail->despatched:'---';

    $qry_1 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE id = '".$cus_id."'"));

    $name       = !empty($qry_1->name)?$qry_1->name:'---';
    $pincode    = !empty($qry_1->pincode)?$qry_1->pincode:'---';
    $cname      = !empty($qry_1->cname)?$qry_1->cname:'---';
    $address    = !empty($qry_1->address)?$qry_1->address:'---';
    $mobile     = !empty($qry_1->mobile)?$qry_1->mobile:'---';
    $gst_number = !empty($qry_1->gst_no)?$qry_1->gst_no:'---';
    $email      = !empty($qry_1->email)?$qry_1->email:'---';
    $tcs_type   = !empty($qry_1->tcs_type)?$qry_1->tcs_type:'---';
    $tcs_no     = !empty($qry_1->tcs_no)?$qry_1->tcs_no:'---';
    $state      = !empty($qry_1->state)?$qry_1->state:'---';

    // Extend the TCPDF class to create custom Header and Footer
    class MYPDF extends TCPDF {

        // Page Header    
        public function Header()
        {
            
        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(0);
            // Set font
            $this->SetFont('times', '', 11);
            // Page number
            // Logo
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

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

    $html ='
    <p style="color:black; font-size:12px; text-align: center;">
        <strong style="color:green; font-size:18px; padding-bottom:1000px;">'.$_SESSION['dcname'].'</strong><br/>
        <span>'.$_SESSION['daddress'].' '.$_SESSION['dc_name'].' '.$_SESSION['ds_name'].'<br>GSTIN\UIN : '.$_SESSION['dgst_no'].'<br></span>
        <strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE</strong>
    </p>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<br><br>
    <table border= "1" cellpadding="1" top="100">
        <tr>
            <td rowspan="6" style="font-size:12px; width: 55%;">Shipped To: <br> '.$name.'<br> '.$cname.'<br> '.$address.'<br> '.$mobile.' Code:'.$state.'<br> GSTIN\UIN :'.$gst_number.'<br> TCS.No :'.$tcs_no.'</td>
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

    $html ='<br><br>
    <table border= "1" cellpadding="1" top="100">
        <thead>
            <tr>
                <td rowspan="2" style="font-size:12px; width: 4%;">S.No</td>
                <td rowspan="2" style="font-size:12px; width: 25%;">Description</td>
                <td rowspan="2" style="font-size:12px; text-align: center; width: 8%;">HSN</td>
                <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">Price</td>
                <td rowspan="2" style="font-size:12px; text-align: center; width: 7%;">Qty</td>
                <td rowspan="2" style="font-size:12px; text-align: center; width: 8%;">Discount</td>
                <td rowspan="2" style="font-size:12px; text-align: center; width: 8%;">Taxable Val</td>';
                if($state == 33)
                {
                    $html .='<td colspan="2" style="font-size:12px; text-align: center; width: 12%;">CGST</td>
                        <td colspan="2" style="font-size:12px; text-align: center; width: 12%;">SGST</td>';
                }
                else
                {
                    $html .='<td colspan="2" style="font-size:12px; text-align: center; width: 24%;">IGST</td>';
                }
                $html .='<td rowspan="2" style="font-size:12px; text-align: center; width: 9%;">Amount</td> 
            </tr>
            <tr>';
                if($state == 33)
                {
                    $html .='<th style="font-size: 12px;"> Rate</th>
                            <th style="font-size: 12px;"> Amt</th>
                            <th style="font-size: 12px;"> Rate</th>
                            <th style="font-size: 12px;"> Amt</th>';
                }
                else
                {
                    $html .='<th style="font-size: 12px;"> Rate</th>
                            <th style="font-size: 12px;"> Amt</th>';
                }
            $html .='</tr>
        </thead>';

        $qry_2 = mysqli_query($conn, "SELECT sa.title AS c_title, sb.so_no AS so_no, sb.pid AS pid, sb.qty AS qty, sb.price AS price, sb.gst AS gst, sb.allowance AS allowance, sb.sta AS sta, sb.code AS code, sb.d_allowance AS d_allowance, sb.discount AS discount, sb.value_pri AS value_pri, sc.title AS p_title, sc.description AS description, sc.hsn AS hsn, sc.oprice AS oprice, sb.code AS code, sb.billstatus AS billstatus FROM ss_category sa JOIN ss_sales_details sb ON sa.id = sb.cid JOIN ss_items sc ON sb.pid = sc.id WHERE sb.so_id = '".$order."' AND sb.published = '1' AND sb.status = '1'");

        $cou_2 = mysqli_num_rows($qry_2);

        if($cou_2 > 0)
        {
            $html .='
            <tbody>';
                $sub_tot = 0;
                $sub_to = 0;
                $srlno = 1;
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
                $_val=0;
                $sub_to_=0;
                $total_val=0;
                $tax_val=0;
                $tcs_val=0;
                $_qty=0;
                $net_total=0;
                $rond_tot=0;
                $lst_tot=0;
                $Sno = 1;

                while($row_2 = mysqli_fetch_array($qry_2)) 
                {
                    $qty_val      = !empty($row_2['qty'])?$row_2['qty']:'0';
                    $price_val    = !empty($row_2['price'])?$row_2['price']:'0';
                    $e_allowance  = !empty($row_2['allowance'])?$row_2['allowance']:'0';
                    $de_allowance = !empty($row_2['d_allowance'])?$row_2['d_allowance']:'0';
                    $sta_val      = !empty($row_2['sta'])?$row_2['sta']:'0';
                    $discount     = !empty($row_2['discount'])?$row_2['discount']:'0';
                    $billstatus   = !empty($row_2['billstatus'])?$row_2['billstatus']:'0';
                    $gst_val      = !empty($row_2['gst'])?$row_2['gst']:'0';
                    $code_val     = !empty($row_2['code'])?$row_2['code']:'0';
                    $product_id   = !empty($row_2['pid'])?$row_2['pid']:'0';
                    $so_no        = !empty($row_2['so_no'])?$row_2['so_no']:'0';
                    $hsn_value    = !empty($row_2['hsn'])?$row_2['hsn']:'0';

                    $code  = mysqli_fetch_object(mysqli_query($conn, "SELECT code FROM ss_item_stk WHERE id = '".$code_val."' AND published = '1'"));

                    $serial_no = '';

                    $serial = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE `product_id` = '".$product_id."' AND `delar_sales` = '".$so_no."' AND published = '1'");
                        
                    while($res = mysqli_fetch_object($serial))
                    {
                        $serial_no .= $res->code.' ,';
                    }

                    $serial_val = substr_replace($serial_no, "", -1);

                    $_qty += $qty_val;

                    $allowance = $price_val * $e_allowance / 100;

                    $allowance_val = $qty_val * round($allowance);

                    $d_allowance = $price_val * $de_allowance / 100;

                    $d_allowance_val = $qty_val * round($d_allowance);

                    $discount_val = $qty_val * round($discount);

                    $_sta = $qty_val * round($sta_val);

                    $netdis = $allowance_val + $_sta + $d_allowance_val + $discount_val;

                    $sub_to = $qty_val * $price_val;

                    $sub_tot += $sub_to;

                    $total = $sub_to - $netdis;

                    $_gst = "1.".$gst_val;

                    $state_gst = $gst_val / 2;

                    $_val = $total / $_gst; 

                    if($billstatus == 1)
                    {
                        $tax_val += $_val; 
                        
                        $gstper = $_val * $gst_val / 100;

                        $total_val = $_val + $gstper;
                        
                        $_total += $total;

                        $cal_gst = $gstper / 2;

                        $gst_ += $gstper;

                        $gst_val = $gst_/2;

                        $sub_to_ = $_val + $gst_;

                        $netval += $sub_to_;

                        $gst_amt = $gst_val / 2;

                        if($tcs_type == '2')
                        {
                            $_tcs = $total_val * 0.1 / 100;
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

                        $net_total = $tax_val + $gst_ + $tcs_val;
                        
                        $lst_tot = round($net_total);

                        $rond_tot = $lst_tot - $net_total;
                    }
                    else
                    {
                        if($tcs_type == '2')
                        {
                            $_tcs = $_val * 0.1 / 100;
                        }
                        else
                        {
                            $_tcs = 0;
                        }

                        $tax_val += $_val;
                  
                        if($tcs_type == '2')
                        {
                            $tcs_val += $_tcs; 
                        }
                        else
                        {
                            $tcs_val = 0;
                        }

                        $gstper = $_val * $gst_val / 100;
                  
                        $total_val = $_val + $gstper;

                        $_total += $total;

                        $net_total = $_total + $tcs_val;

                        $lst_tot = round($net_total);

                        $rond_tot = $lst_tot - $net_total;

                        $total_val = $_val + $gstper;

                        $cal_gst = $gstper / 2;

                        $gst_ += $gstper;

                        $gst_val = $gst_/2;

                        $sub_to_ = $_val + $gst_;

                        $netval += $sub_to_;

                        $gst_amt = $gst_val / 2;
                    }

                    $html .= '
                        <tr>
                            <td style="font-size:12px; width: 4%;">'.$Sno++.'</td>
                            <td style="font-size:12px; width: 25%;">'.$code_val.'</td>
                            <td style="font-size:12px; text-align: center; width: 8%;">'.$hsn_value.'</td>
                            <td style="font-size:12px; text-align: right; width: 7%;">'.$price_val.'</td>
                            <td style="font-size:12px; text-align: center; width: 7%;">'.$qty_val.'</td>
                            <td style="font-size:12px; text-align: center; width: 8%;">'.round($discount).'</td>
                            <td style="font-size:12px; text-align: right; width: 8%;">'.number_format($_val, 2).'</td>';
                            if($state == 33)
                            {
                                $html .='<td style="font-size:12px; text-align: right; width: 6%;">'.round($state_gst).' %</td>
                                        <td style="font-size:12px; text-align: right; width: 6%;">'.number_format($cal_gst, 2).'</td>
                                        <td style="font-size:12px; text-align: right; width: 6%;">'.round($state_gst).' %</td>
                                        <td style="font-size:12px; text-align: right; width: 6%;">'.number_format($cal_gst, 2).'</td>';
                            }
                            else
                            {
                                $html .='<td style="font-size:12px; text-align: right; width: 12%;">'.round($gst_val).' %</td>
                                        <td style="font-size:12px; text-align: right; width: 12%;">'.number_format($gstper, 2).'</td>';
                            }
                            
                            $html .='<td style="font-size:12px; text-align: right; width: 9%;">'.round($total_val).'</td>
                        </tr>
                        <tr><td colspan="15">Serial No: '.$serial_val.'</td></tr>
                    ';
                }
            $html .='
            </tbody>';
            if($state == 33)
            {
                $colspan = '9';
            }
            else
            {
                $colspan = '7';
            }
            $html .= '
                <tfoot>
                    <tr>  
                        <th rowspan ="6"  colspan="'.$colspan.'"></th>
                        <th colspan="2" class="text-right">Qty</th>
                        <th class="text-left">'.$_qty.'</th>
                    </tr>  
                    <tr>
                        <th colspan="2" class="text-right">Sub Total</th>
                        <th class="text-left">'.number_format($tax_val, 2).'</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">GST %</th>
                        <th class="text-left">'.number_format($gst_, 2).'</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">TCS Value</th>
                        <th class="text-left">'.number_format($tcs_val, 2).'</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Round off</th>
                        <th class="text-left">'.number_format($rond_tot, 2).'</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Net Total</th>
                        <th class="text-left">'.number_format($lst_tot, 2).'</th>
                    </tr>
                    <tr>
                    <th colspan="15" class="text-right">Amount (in words) : '.numberTowords($lst_tot).' RUPEES ONLY</th>
                    </tr>
                </tfoot>
            ';
        }
        else
        {
            $html .= '
                <tr>
                    <th colspan="15">No Records Founded.</th>
                </tr>
            ';
        }

    $html .='</table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $html ='<br><br><br>
        <table border= "1" cellpadding="1">
            <tr>
                <th style="width:50%; font-size:12px;" colspan="3">
                    <span>Company(s) PAN : AADCC7428F </span>
                    <p> Declaration: We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.<br> We had received the goods as per the ordered quantity & in Good Condition. We accept / agree to pay the company for this invoice on a credit of 30 days without any interest and/or credit period between 30 days to 90 days with an additional 2% interest per Month. I/We unconditionally agree to pay for the invoice in the above terms.</p></th>

                <td style="width:50%; font-size:12px;" colspan="3" class="no-line"><span> for Yara Electronics (A Div of Cheenu Amma Alloy P Ltd )</span> <br><br><br> <p style="text-align: right; "> Authorised Signatory</p></td>
            </tr>';
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');


    $pdf->Output('example_003.pdf', 'I');
?>