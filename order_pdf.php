<?php

require 'inc/config.php';
//require_once('lib/Mpdf/vendor/autoload.php' );
if($_GET['action'] == "download")
{
	$order_id=$_GET['id'];
	//$order_id='B2';
include("lib/Mpdf/vendor/autoload.php");
 
$mpdf=new mPDF("en-GB-x","A4","","",10,10,10,10,6,3); 
 
$mpdf->SetDisplayMode('fullpage');
 
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
$stylesheet = '';

$stylesheet .= file_get_contents('assets/css/invoice.css');
$filename=time().'pdf';
$mpdf->WriteHTML($stylesheet, 1);

if($order_id != '')
 {
   $bill = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payments`  WHERE `status` = 2 AND `bno` = '".$order_id."' LIMIT 1"));
   $addressdetail = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_delivery_address`  WHERE `status` = 1 AND `bno` = '".$order_id."' LIMIT 1"));
   $paydetails = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payment_status`  WHERE `order_id` = '".$order_id."' LIMIT 1"));
   $con = "SELECT it.id AS pid, pa.amount AS amount, pa.uid AS uid, pu.ptotal AS total, it.title AS title, ct.title AS category, pu.pqual AS pqual, ptotal AS ptotal FROM ss_payments pa JOIN ss_purchase pu ON pu.bno = pa.bno JOIN ss_items it ON it.id = pu.pid JOIN ss_category ct ON ct.id = it.cid WHERE pa.bno='".$order_id."' ";
                        $selectquery = mysqli_query($conn, $con);
                        $numrows = mysqli_num_rows($selectquery);
        if($paydetails->order_status !="Success") 
                            {
                                $clr= "warning";
                            }
                            else
                            {
                                $clr= "success";
                            }
 }
$html='<div class="invoice">
                        <div class="row invoice-logo">
                            <div class="col-xs-3 invoice-logo-space">
                            <div style="clear:both"></div><br>
                                <img src="assets\images\logo_dark.png" class="logo_img inv-img" alt="" /> 
                                
                            </div>
                        <div class="col-xs-3 inv_header padr">                                
                                    <ul class="list-unstyled address_size">
                                        <li class="company_name">Suresh Traders</li>
                                        <li>1027, Sukrawarpet St, </li>
                                        <li> R.S. Puram, Coimbatore, </li>
                                        <li> Tamil Nadu 641001 </li>
                                    </ul>
                        </div>
                         <div class="col-xs-3 inv_header padl padr">                                
                                    <ul class="list-unstyled address_size">
                                        <li> 0422 247 4472 </li>
                                        <li> <u>info@electricalsonline.in</u> </li>
                                       
                                    </ul>
                        </div>
                        <div class="col-xs-2 inv-header padr padl">
                            <p ><strong>Invoice</strong></p>
                            <table class="table table-bordered table-hover font_body1" >
                                <thead class="tab-head">
                                <tr>
                                  <td class="orderid">Order #</td>
                                  <td>'.$order_id.'</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                     <div style="clear:both"></div>
                        <div class="row">
                       
                            <div class="col-xs-5 invoice-payment"  style="width:45.5% !important">
                                <table class="table table-bordered table-hover tab-address font_body1">
                                                <thead>
                                              <tr>
                                                  <th>Ship Address</th>
                                              </tr>
                                                 </thead>
                                                    <tbody>
                                              <tr>
                                                  <td>
                                                <div class="col-md-12 disp_addrs">
                                                <span class="user_order_add">
                                                    '.$addressdetail->name.'</span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    
                                                    <span class="user_order_add">'.$addressdetail->address.'</span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    
                                                    <span class="user_order_add">'.$addressdetail->city.' - '.$addressdetail->pincode.'.</span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    
                                                    <span class="user_order_add">'.$addressdetail->landmark.'.</span>
                                                </div>
                                               <div class="col-md-12 disp_addrs">
                                                
                                                    <span class="user_order_add">'.$addressdetail->mobile_num.'.</span>
                                                </div>
                                            </td>
                                              </tr>
                                              </tbody>
                                          </table>
                            </div>
                            
                            <div class="col-xs-5 invoice-payment" style="width:45.5% !important">
                               <table class="table table-bordered table-hover tab-address font_body1">
                                               <thead>
                                              <tr>
                                                  <th>Delivery Address</th>
                                              </tr>
                                               </thead>
                                               <tbody>
                                              <tr>
                                                  <td>
                                                <div class="col-md-12 disp_addrs">
                                                    <span class="user_order_add">'.$addressdetail->name.'</span>
                                                </div>
                                                 <div class="col-md-12 disp_addrs">
                                                    
                                                    <span class="user_order_add">'.$addressdetail->address.'</span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    
                                                    <span class="user_order_add">'.$addressdetail->city.' - '.$addressdetail->pincode.'.</span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    
                                                    <span class="user_order_add">'.$addressdetail->landmark.'.</span>
                                                </div>
                                               <div class="col-md-12 disp_addrs">
                                               
                                                    <span class="user_order_add">'.$addressdetail->mobile_num.'</span>
                                                </div>
                                            </td>
                                              </tr>
                                              </tbody>
                                          </table>
                            </div>
                          
                        </div>
                         <div class="row">
                            <div class="col-xs-12">
                             <table class="table table-bordered table-hover font_body1">
                                    <thead class="tab-head">
                                       <tr>
                                        <th>Tracking Id</th>
                                        <th class="b_num">Bank Ref No</th>
                                        <th>Pay mode</th>
                                        <th>Card Name</th>
                                        <th>Currency</th>
                                        <th>Amount</th>
                                        <th>status</th>
                                    </tr>
                                    
                                </thead>
                                    <tbody class="tab-tbody">
                                     <tr>
                                        <td>'.$paydetails->tracking_id.'</td>
                                        <td><a target="_blank">'.mb_substr($paydetails->bank_ref_no, 0, 20).'...</a></td>
                                        <td>'.$paydetails->payment_mode.'</td>
                                        <td>'.$paydetails->card_name.'</td>
                                        <td>'.$paydetails->currency.'</td>
                                        <td>'.$paydetails->amount.'</td>
                                        <td><label class="label label-'.$clr.'">
                                      '.$paydetails->order_status.'
                                        </label>  </td>   
                                    </tr>
                                    </tbody>
                                    </table>
                            </div>
                         </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-hover table-itemList inv-item font_body1">
                                    <thead class="tab-thead">
                                        <tr>
                                    <th>S.No</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    
                                </tr>
                                    </thead>
                                    <tbody>';

                                if($numrows > 0) {
                                    $srlno = 1;
                                    $netpay = 0;
                                    $gstper =0;
                                    $overallgstprice=0;
                                    while($row = mysqli_fetch_array($selectquery)) 
                                    {
                                        // if($row['gst'])
                                        // {
                                        //     $gst = $row['gst'];
                                        // }
                                        // else
                                        // {
                                        //     $gst = 0;   
                                        // }

                                        $pqual=isset($row['pqual']) && ($row['pqual'] !='0')?$row['pqual'] :'';

                                        // if($gst !='0')
                                        // {
                                        //     $gstper=$row['ptotal']/$gst;
                                        // }
                                        // $gstprice = $gstper;
                                        $netpay += $row['ptotal'];
                                       // $overallgstprice +=$gstprice;
                                        if($srlno%2 == 1){
                                            $clor = '#EBEBEB';
                                          }else{
                                             $clor = '#FFF';
                                          }
                                        
                                    $html .=' <tr>
                                            <td style="background-color:'.$clor.'">'.$srlno++.'</td>
                                            <td style="background-color:'.$clor.'"><a target="_blank">'.mb_substr($row['title'], 0, 60).'...</a></td>
                                            <td style="background-color:'.$clor.'">'.$row['category'].'</td>
                                            <td style="background-color:'.$clor.'">'.$row['pqual'].'</td>
                                         
                                            <td style="background-color:'.$clor.'">'.round($row['ptotal']).'</td>
                                            
                                        </tr>';   
                                    }
                                   $html .='<tfoot>
                                        
                                        <tr>
                                            <th colspan="4" class="text-right">TOTAL</th>
                                            <th>'.round($netpay).'</th>
                                        </tr>
                                    </tfoot>';
                                }
                                   $html .='</tbody>
                                </table>
                            </div>
                        </div>   
                    </div>';
//$html = file_get_contents('pdf_design.php');
// ob_start();
// include "pdf_design.php";
// $html=ob_get_contents();
// ob_get_clean();
//var_dump($html);die();
 //$mpdf->WriteHTML($html); 

$mpdf->Output($filename.'.pdf','d');
}
?>