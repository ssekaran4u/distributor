 <?php 
session_start();
include 'inc/config.php';
isAdmin();
$order = $_GET['id'];
if($order){
    // $bill = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payments`  WHERE `status` = 2 AND `bno` = '".$order."' LIMIT 1"));
    // $orderstatus = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payment_status`  WHERE `status` = 1  AND `order_id` = '".$order."' LIMIT 1"));
    $addressdetail = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_invoice`  WHERE `id` = '".$order."' LIMIT 1"));
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title><?php echo $config["site_name"]; ?> - Admin Dashboard</title>
        <link rel="apple-touch-icon" sizes="57x57" href="images/icon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="images/icon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="images/icon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="images/icon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="images/icon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="images/icon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="images/icon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="images/icon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="images/icon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="images/icon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="images/icon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="images/icon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="images/icon/favicon-16x16.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">

    </head>
<style type="text/css">
      .addre_del{
        padding: 9px;
    border: 1px solid #19151554;
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
      .padd_lef_rgt table th
      {
        padding:10px;
      }
      .borderclr
      {
        border:1px solid #80808047;
      }
      .pdf_btn
      {
        position: relative;
        left: 77%;
      }
      label {
        margin-bottom: 0;
      }
      #_h5 {
        margin: 0;
        padding-bottom: 10px;
        font-size: 20px;
      }
  </style>

    <body class="fixed-left">
         <div id="wrapper">
        <?php include 'includes/side_menu.php';  ?>
            <div class="content-page">
                <div class="content">
                    <?php include 'includes/top_notification.php';  ?>
                    <div class="page-content-wrapper">

                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="card m-b-20">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="invoice-title">
                                                        <h4 class="pull-right font-16"><strong>Order# <?php echo isset($addressdetail->so_no)?$addressdetail->so_no:'--';?></strong></h4>
                                                        <h3 class="mt-0">
                                                            <img src="assets/images/logo.jpg" alt="logo" height="26">
                                                        </h3>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                                            <?php if($addressdetail !="")
                                                            {
                                                                $qry_1 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE id = '".$addressdetail->customer_id."'"));
                                                            ?>
                                                            
                                                            <address>
                                                                <strong>Billed To:</strong><br>
                                                                <div class="col-md-12 disp_addrs ">
                                                                    <span class="user_order_add">Yara Electronics (A Div of Cheenu Amma Alloy P Ltd )</span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs ">
                                
                                                                    <!-- <span class="user_order_add"><?php echo $addressdetail->address.' '.$location['city'];?></span> -->
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    <span class="user_order_add">SF.No: 416/7, Thanneer Pandal,Vilankurichi Road, Peelamedu, Coimbatore - 641004</span>
                                                                </div>
                                                               <div class="col-md-12 disp_addrs">
                                                                
                                                                    <span class="user_order_add">GSTIN\UIN : 33AADCC7429F1ZV</span>
                                                                </div>
                                                                <!-- <div class="col-md-12 disp_addrs">
                                                                
                                                                    <span class="user_order_add">appliances@cheenugroup.com</span>
                                                                </div> -->
                                                            </address>
                                                        <?php } ?>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                                            <?php if($addressdetail !="")
                                                            {
                                                                $qry_1 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE id = '".$addressdetail->customer_id."'"));
                                                            ?>
                                                            
                                                            <address>
                                                                <strong>Shipped To:</strong><br>
                                                                <div class="col-md-12 disp_addrs ">
                                                                    <span class="user_order_add"><?php echo $qry_1->owner;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs ">
                                
                                                                    <!-- <span class="user_order_add"><?php echo $addressdetail->address.' '.$location['city'];?></span> -->
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    <span class="user_order_add"><?php echo $qry_1->customeraddress; ?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                
                                                                    <span class="user_order_add"><?php echo $qry_1->mobile;?></span>
                                                                </div>
                                                                 <div class="col-md-12 disp_addrs">
                                                                
                                                                    <span class="user_order_add"><?php echo $qry_1->gst ? $qry_1->gst : '---'; ?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                
                                                                    <span class="user_order_add"><?php echo $qry_1->email_id;?></span>
                                                                </div>
                                                            </address>
                                                        <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <!-- <div class="col-md-4 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Preferred location:</strong><br>
                                                                <?php echo $location['city']; ?>
                                                            </address>
                                                        </div> -->
                                                        <!-- <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Delivery Note :</strong><br>
                                                                <?php echo $addressdetail->delivery_nt ? $addressdetail->delivery_nt : '---';?>
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Mode/Terms of Payments:</strong><br>
                                                                <?php echo $addressdetail->payment ? $addressdetail->payment : '---';?>
                                                                
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Supplier's Ref:</strong><br>
                                                                <?php echo $addressdetail->supplier_ref ? $addressdetail->supplier_ref : '---';?>
                                                                
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Other Reference(s):</strong><br>
                                                                <?php echo $addressdetail->other_ref ? $addressdetail->other_ref : '---';?>
                                                                
                                                            </address>
                                                        </div> -->
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Buyer's Order No:</strong><br>
                                                                <?php echo $addressdetail->order_no ? $addressdetail->order_no : '---';?>
                                                                
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 text-center m-t-30">
                                                            <address>
                                                                <strong>Dated:</strong><br>
                                                                <?php 
                                                                    echo date("d-m-Y", strtotime($addressdetail->dated));
                                                                ?>
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Eway Bill No :</strong><br>
                                                                <?php echo $addressdetail->docu_no ? $addressdetail->docu_no : '---';?>
                                                                
                                                            </address>
                                                        </div>
                                                        <!-- <div class="col-md-3 col-sm-12 col-xs-12 text-center m-t-30">
                                                            <address>
                                                                <strong>Delivery Note Date :</strong><br>
                                                                <?php 
                                                                    echo date("d-m-Y", strtotime($addressdetail->delivery_date));
                                                                ?>
                                                            </address>
                                                        </div> -->
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Others:</strong><br>
                                                                <?php echo $addressdetail->despatched ? $addressdetail->despatched : '---';?>
                                                                
                                                            </address>
                                                        </div>
                                                        <!-- <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Destination:</strong><br>
                                                                <?php echo $addressdetail->destination ? $addressdetail->destination : '---';?>
                                                                
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Terms of Delivery:</strong><br>
                                                                <?php echo $addressdetail->terms ? $addressdetail->terms : '---';?>
                                                                
                                                            </address>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-default">
                                                        <div class="p-2">
                                                            <h3 class="panel-title font-20"><strong>Order summary</strong></h3>
                                                        </div>
                                                        <div class="">
                                                        <?php 

                                                        $con = "SELECT sa.title AS c_title, sb.so_no AS so_no, sb.pid AS pid, sb.qty AS qty, sb.price AS price, sb.gst AS gst, sb.allowance AS allowance, sb.sta AS sta, sb.code AS code, sb.d_allowance AS d_allowance, sb.discount AS discount, sb.value_pri AS value_pri, sc.title AS p_title, sc.description AS description, sc.hsn AS hsn, sc.oprice AS oprice, sb.code AS code FROM ss_category sa JOIN ss_distributor_inv_details sb ON sa.id = sb.cid JOIN ss_items sc ON sb.pid = sc.id WHERE sb.so_id = '".$order."' AND sb.qty != '0' AND sb.published = '1' AND sb.status = '1'";
                                                        // echo $con;
                                                                $selectquery = mysqli_query($conn, $con);
                                                                $numrows = mysqli_num_rows($selectquery);
                                                                ?>
                                                                
                                                               
                                                                <div class="col-md-12">
                                                                    <div class="col-md-2" style="padding: 0px;margin-top: 6px;">
                                                                        <label>Product Details :</label>
                                                                    </div>  
                                                                </div>
                                                                <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow-div padd_lef_rgt">
                                                                    <div class="table-responsive">
                                                                        <table border="1" class="table borderclr">
                                                                    <thead>
                                                                        <tr>
                                                                            <th rowspan="2">S.No</th>
                                                                            <th rowspan="2">Description</th>
                                                                            <th rowspan="2">HSN</th>
                                                                            <th rowspan="2">DP</th>
                                                                            <th rowspan="2">Qty</th>
                                                                            <th rowspan="2">Basic Allowance</th>
                                                                            <th rowspan="2">STA</th>
                                                                            <th rowspan="2">Dealer Allowance</th>
                                                                            <th rowspan="2">Additional discount</th>
                                                                            <th rowspan="2">Taxable Value</th>
                                                                            <th colspan="2" style="text-align: center;">CGST</th>
                                                                            <th colspan="2" style="text-align: center;">SGST</th>
                                                                            <th rowspan="2">Price</th>  
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Rate</th>
                                                                            <th>Amount</th>
                                                                            <th>Rate</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <?php
                                                                        if($numrows > 0) {
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
                                                                            $_qty=0;
                                                                            while($row = mysqli_fetch_array($selectquery)) 
                                                                                {
                                                                                    $code  = mysqli_fetch_object(mysqli_query($conn, "SELECT code FROM ss_item_stk WHERE id = '".$row['code']."' AND published = '1'"));

                                                                                    // $gst_ = $row['price'] * $row['gst'] / 100;

                                                                                    // $_dp = $row['price'] - $gst_;

                                                                                    // $gstper = $row['qty'] * $gst_;

                                                                                    // $overallgstprice += $gstper;

                                                                                    // $amt_val = $row['qty'] * $row['price'];

                                                                                    // $allowance = $row['price'] * $row['allowance'] / 100;

                                                                                    // $allowance_val = $row['qty'] * $allowance;

                                                                                    // $d_allowance = $row['price'] * $row['d_allowance'] / 100;

                                                                                    // $d_allowance_val = $row['qty'] * $d_allowance;

                                                                                    // $price = $row['price'];

                                                                                    // $_discount = $row['discount'];

                                                                                    // $discount = $row['qty'] * $row['discount'];

                                                                                    // $_sta = $row['qty'] * $row['sta'];

                                                                                    // $netval = $row['price'] - $gst_;

                                                                                    // $netdis = $allowance_val + $_sta + $d_allowance_val + $discount;

                                                                                    // $_netdis = $row['qty'] * $_dp;

                                                                                    // $netpay  = $_netdis - $netdis;

                                                                                    // $sub_to += $netpay;

                                                                                    // $gst_amt += $gst_;

                                                                                    // $_total = $sub_to + $overallgstprice;
                                                                              		
                                                                              		$serial_no = '';

                                                                                    $serial = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE `product_id` = '".$row['pid']."' AND `sales_no` = '".$row['so_no']."' AND published = '1'");

                                                                                    while($res = mysqli_fetch_object($serial))
                                                                                    {
                                                                                        $serial_no .= $res->code.' ,';
                                                                                    }

                                                                                    $serial_val = substr_replace($serial_no, "", -1);
                                                                              		
                                                                                    $_qty += $row['qty'];

                                                                                    $allowance = $row['price'] * $row['allowance'] / 100;

                                                                                    $allowance_val = $row['qty'] * round($allowance);

                                                                                    $d_allowance = $row['price'] * $row['d_allowance'] / 100;

                                                                                    $d_allowance_val = $row['qty'] * round($d_allowance);

                                                                                    $discount = $row['qty'] * round($row['discount']);

                                                                                    $_sta = $row['qty'] * round($row['sta']);

                                                                                    $netdis = $allowance_val + $_sta + $d_allowance_val + $discount;

                                                                                    $sub_to = $row['qty'] * $row['price'];

                                                                                    $sub_tot += $sub_to;

                                                                                    $total = $sub_to - $netdis;

                                                                                    $_total += $total;

                                                                                    $_gst = "1.".$row['gst'];

                                                                                    $_val = $total / $_gst; 

                                                                                    $tax_val += $_val; 

                                                                                    $gstper = $_val * $row['gst'] / 100;

                                                                                    $total_val = $_val + $gstper;

                                                                                    $cal_gst = $gstper / 2;

                                                                                    $gst_ += $gstper;

                                                                                    $gst_val = $gst_/2;

                                                                                    $sub_to_ = $_val + $gst_;

                                                                                    $netval += $sub_to_;

                                                                                    $gst_amt = $row['gst'] / 2;
                                                                                 
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $srlno++; ?></td>
                                                                                    <td><?php echo $row['code'];?> 
                                                                                    <td><?php echo $row['hsn'];?></td>
                                                                                    <td><?php echo $row['price'];?></td>
                                                                                    <td><?php echo $row['qty'];?></td>
                                                                                    <td><?php echo round($allowance_val);?></td>
                                                                                    <td><?php echo round($_sta);?></td>
                                                                                    <td><?php echo round($d_allowance_val);?></td>
                                                                                    <td><?php echo round($discount);?></td>
                                                                                    <td><?php echo number_format($_val, 2);?></td>
                                                                                    <td><?php echo round($gst_amt);?> %</td>
                                                                                    <td><?php echo number_format($cal_gst, 2);?></td>
                                                                                    <td><?php echo round($gst_amt);?> %</td>
                                                                                    <td><?php echo number_format($cal_gst, 2);?></td>
                                                                                    <td><?php echo round($total_val);?></td>
                                                                                </tr>
                                                                                <tr><td colspan="15">Serial No: <?php echo $serial_val; ?></td></tr>
                                                                                <?php
                                                                            }?>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <?php  ?>
                                                                                    <th colspan="4" class="text-right">Total</th>
                                                                                    <th><?php echo round($_qty); ?></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th><?php echo round($tax_val); ?></th>
                                                                                    <th></th>
                                                                                    <th><?php echo round($gst_val); ?></th>
                                                                                    <th></th>
                                                                                    <th><?php echo round($gst_val); ?></th>
                                                                                    <th><?php echo round($_total); ?></th>
                                                                                </tr>
                                                                                <!-- <tr>
                                                                                    <th colspan="14" class="text-right">CGST</th>
                                                                                    <th><?php echo round($gst_val); ?></th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th colspan="14" class="text-right">SGST</th>
                                                                                    <th><?php echo round($gst_val); ?></th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th colspan="14" class="text-right">Total</th>
                                                                                    <th><?php echo round($_total); ?></th> -->
                                                                                </tr>
                                                                            </tfoot>
                                                                            <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                                <tr>
                                                                                    <th colspan="15">No Records Founded.</th>
                                                                                </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                </table>
                                                                    </div>
                                                            </div>
                                                            <div class="d-print-none">
                                                                <div class="pull-right">
                                                                    <!-- <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a> -->
                                                                    <a  href="lib/TCPDF-master/examples/distributor-print.php?id=<?php echo $order;?>" target="_blank" class="btn btn-success waves-effect waves-light "></i>Print</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
            
                                        </div>
                                    </div>
                                </div> 
                            </div> 
            

                        </div>

                    </div> 

                </div> 

                 <footer class="footer">

                    <?php include 'includes/copyright.php'; ?>

                </footer>

            </div>

        </div>

        <script src="assets/js/jquery.min.js"></script>

        <script src="assets/js/bootstrap.min.js"></script>

        <script src="assets/js/modernizr.min.js"></script>

        <script src="assets/js/jquery.slimscroll.js"></script>

        <script src="assets/js/waves.js"></script>

        <script src="assets/js/jquery.nicescroll.js"></script>

        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/js/file-upload.js"></script>

        <script src="assets/js/app.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(document).on('click', '._calcel_', function () {
                    var queryString = $('#_csa_form').serialize();
                    $.ajax({
                        type: "POST",
                        url: 'inc/update-orders.php',
                        data: queryString,
                        dataType: 'JSON',
                        beforeSend: function(x) {
                            $('#status').show();
                            $('#preloader').show();
                        }
                    }).done(function (response)
                    {
                        console.log(response);
                        $('#status').hide();
                        $('#preloader').hide();
                        if (response['status'] == true)
                        {
                            window.location = window.location;
                        }
                        if (response['status'] == false)
                        {
                            if ($.isArray(response['message']))
                            {
                                $('#o-message').empty();
                                $('#o-message').removeClass('alert alert-success').addClass('alert alert-danger');
                                $.each(response['message'], function (index, value)
                                {
                                    $('#o-message').append(value + '<br>');
                                });
                            } 
                            else
                            {
                                $('#o-message').removeClass('alert alert-success').addClass('alert alert-danger');
                                $('#o-message').html(response['message']);
                            }
                        }
                    });
                });
                $(document).on('click', '.invoice_pdf', function () {
                    var orderid = $(this).attr("data-id");
                    $.ajax({
                        type: "POST",
                        url: 'lib/TCPDF-master/examples/example_006.php',
                        data: {'orderid' : orderid},
                        dataType: 'JSON',
                        beforeSend: function(x) {
                            $('#status').show();
                            $('#preloader').show();
                        }
                    }).done(function (response)
                    {
                        
                    });
                });
            });
        </script>
    </body>

</html></script>
    </body>

</html>