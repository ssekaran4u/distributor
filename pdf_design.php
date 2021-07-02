
<style type="text/css">
  .font_body1 td
  {
    font-size: 10px!important;
  }
  .font_body1 th
  {
    font-size: 12px!important;
  }
  .shipp_act
  {
    width:18%!important;
  }
  .po_no
  {
    width:13%!important;
  }
  .b_order{
    width:11%;
  }
  .s_order{
    width:10%;
  }
  .address_size
  {
    font-size:10px!important;
  }
  .font_body1 th
  {
    padding:6px!important; 
  }
  .padl
  {
    padding-left: 0px!important;

  }
  .padr
  {
    padding-right: 0px!important;
  }
   .user_order_add
      {
        font-size: 15px;
      }
      .shi_adr
      {
        width: 22%;
        margin:0px;
      }
      .disp_addrs
      {
        display: flex;
      }
</style> 
<?php 
include 'includes/error_log.php';
require 'includes/config.php';
include 'includes/avul.php';
$order_id ='B2';
 if($order_id != '')
 {
   $bill = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payments`  WHERE `status` = 2 AND `bno` = '".$order_id."' LIMIT 1"));
   $address_id=$bill->address_id;
   $addressdetail = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_delivery_address`  WHERE `status` = 1 AND `bno` = '".$order_id."' LIMIT 1"));
   $paydetails = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payment_status`  WHERE `order_id` = '".$order_id."' LIMIT 1"));
   $con = "SELECT it.id AS pid, pa.amount AS amount, pa.uid AS uid, pu.ptotal AS total,it.cpercent AS gst, it.title AS title, ct.title AS category, pu.pqual AS pqual, ptotal AS ptotal FROM ss_payments pa JOIN ss_purchase pu ON pu.bno = pa.bno JOIN ss_items it ON it.id = pu.pid JOIN ss_category ct ON ct.id = it.cid WHERE pa.bno='".$order_id."' ";
                        $selectquery = mysqli_query($conn, $con);
                        $numrows = mysqli_num_rows($selectquery);
 }
?>
 <div class="invoice">
                        <div class="row invoice-logo">
                            <div class="col-xs-2 invoice-logo-space">
                                <img src="images/suresh_traders.png" class="img-responsive inv-img" alt="" /> 
                                <div style="clear:both"></div>
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
                        <div class="col-xs-3 inv-header padr padl">
                            <p ><strong>Invoice</strong></p>
                            <table class="table table-bordered table-hover font_body1" >
                                <thead class="tab-head">
                                <tr>
                                  <td>Order Id #</td>
                                  <td><?php echo $order_id;?></td>
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
                                                    <h5 class="shi_adr">Name :</h5><span class="user_order_add"><?php echo $addressdetail->name;?></span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    <h5 class="shi_adr">Address :</h5>
                                                    <span class="user_order_add"><?php echo $addressdetail->address.' '.$addressdetail->city.' - '.$addressdetail->pincode;?></span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    <h5 class="shi_adr">Landmark :</h5>
                                                    <span class="user_order_add"><?php echo $addressdetail->landmark;?></span>
                                                </div>
                                               <div class="col-md-12 disp_addrs">
                                                <h5 class="shi_adr">Mobile No :</h5>
                                                    <span class="user_order_add"><?php echo $addressdetail->mobile_num;?></span>
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
                                                  <th>Delivary Address</th>
                                              </tr>
                                               </thead>
                                               <tbody>
                                              <tr>
                                                  <td>
                                                <div class="col-md-12 disp_addrs">
                                                    <h5 class="shi_adr">Name :</h5><span class="user_order_add"><?php echo $addressdetail->name;?></span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    <h5 class="shi_adr">Address :</h5>
                                                    <span class="user_order_add"><?php echo $addressdetail->address.' '.$addressdetail->city.' - '.$addressdetail->pincode;?></span>
                                                </div>
                                                <div class="col-md-12 disp_addrs">
                                                    <h5 class="shi_adr">Landmark :</h5>
                                                    <span class="user_order_add"><?php echo $addressdetail->landmark;?></span>
                                                </div>
                                               <div class="col-md-12 disp_addrs">
                                                <h5 class="shi_adr">Mobile No :</h5>
                                                    <span class="user_order_add"><?php echo $addressdetail->mobile_num;?></span>
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
                                        <th>Bank Ref No</th>
                                        <th>Pay mode</th>
                                        <th>Card Name</th>
                                        <th>Currency</th>
                                        <th>Amount</th>
                                        <th>status</th>
                                    </tr>
                                    
                                </thead>
                                    <tbody class="tab-tbody">
                                     <tr>
                                        <td><?php echo $paydetails->tracking_id; ?></td>
                                        <td><a target="_blank"><?php echo mb_substr($paydetails->bank_ref_no, 0, 60) ;?>...</a></td>
                                        <td><?php echo $paydetails->payment_mode;?></td>
                                        <td><?php echo $paydetails->card_name;?></td>
                                        <td><?php echo $paydetails->currency;?></td>
                                        <td><?php echo $paydetails->amount;?></td>
                                        <td><label class="label label-<?php 
                                            if($paydetails->order_status !="Success") 
                                            {
                                                echo "warning";
                                            }
                                            else
                                            {
                                                echo "success";
                                            }
                                            
                                             ?>">
                                       <?php echo $paydetails->order_status;?>
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
                                    <!-- <th>GST %</th>
                                    <th>GST Price</th> -->
                                    <th>Total Price</th>
                                    
                                </tr>
                                    </thead>
                                    <tbody>
                                     <?php
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
                                        //$gstprice = $gstper;
                                        $netpay += $row['ptotal'];
                                        //$overallgstprice +=$gstprice;
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $srlno++; ?></td>
                                            <td><a target="_blank"><?php echo mb_substr($row['title'], 0, 60) ;?>...</a></td>
                                            <td><?php echo $row['category'];?></td>
                                            <td><?php echo $row['pqual'];?></td>
                                            <!-- <td><?php echo $gst;?>%</td>
                                            <td><?php echo round($gstprice);?></td> -->
                                            <td><?php echo round($row['ptotal']);?></td>
                                            
                                        </tr>
                                        <?php
                                    }?>
                                    <tfoot>
                                        <!-- <tr>
                                            <th colspan="4" class="text-right">GST %</th>
                                            <th><?php echo round($overallgstprice);?></th>
                                        </tr> -->
                                        <tr>
                                            <th colspan="4" class="text-right">TOTAL</th>
                                            <th><?php echo round($netpay); ?></th>
                                        </tr>
                                    </tfoot>
                                    <?php
                                }
                                ?>
                                       
                                    
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                       
                    </div>
               
<!--Style -->

