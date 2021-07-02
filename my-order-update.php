<?php 
session_start();
include 'inc/config.php';
isAdmin();
$order = $_GET['bno'];
if($order){
    $bill = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payments`  WHERE `status` = 2 AND `bno` = '".$order."' LIMIT 1"));
    $orderstatus = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payment_status`  WHERE `status` = 1 AND `order_id` = '".$order."' LIMIT 1"));
    $location = mysqli_fetch_array(mysqli_query($conn, "SELECT `city` FROM `2k18c_branches` WHERE `id` = '".$orderstatus->branch."' "));
    $addressdetail = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_delivery_address`  WHERE `bno` = '".$order."' LIMIT 1"));   
    mysqli_query($conn, "UPDATE `ss_payments`  SET `reading` = 2 WHERE `bno` = '".$order."' ");
    if($orderstatus->order_status=="Success")
    {
        $_content = array('Processing','Shipped','Cancel','Closed');
    }
    elseif($orderstatus->order_status=="Processing") 
    {
        $_content = array('Shipped','Cancel','Closed');   
    }
    elseif($orderstatus->order_status=="Shipped") 
    {
        $_content = array('Cancel','Closed');   
    }
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
        <meta content="Admin Dashboard" name="description">
        <meta content="Themesbrand" name="author">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
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
      </style>
    </head>
    <body class="fixed-left">
         <div id="wrapper">
        <?php include 'includes/side_menu.php';  ?>
            <div class="content-page">
                <div class="content">
                    <?php include 'includes/top_notification.php';  ?>
                    <div class="page-content-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-20">
                                        <div class="card-body">
                                            <?php if($orderstatus->order_status!="Closed"): ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="o-message"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label><strong>Current Status</strong></label>
                                                    <h5 id="_h5"><strong><?php echo $orderstatus->order_status=='Success' ? 'Order Confirm' : $orderstatus->order_status; ?></strong></h5>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><strong>Change to</strong> <span class="text-danger">*</span> </label>
                                                        <select class="form-control" id="_oType" name="otype">
                                                            <?php foreach ($_content as $key => $value) { 
                                                                $select = $value==$orderstatus->order_status ? 'selected' : '';
                                                                ?>
                                                                <option <?php echo $select; ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="_Reason">
                                                    <div class="form-group">
                                                        <label><strong>Reason </strong> <span class="text-danger">*</span></label>
                                                        <input type="text" id="_Reason_value" name="reason" class="form-control" placeholder="Reason">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group" style="margin-top: 25px;">
                                                        <input type="hidden" name="method" value="update">
                                                        <input type="hidden" id="_order_id" name="order_id" value="<?php echo $order; ?>">
                                                        <button type="button" id="_update_btn" class="col-md-12 btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                                <div class="col-12">
                                                    <div class="invoice-title">
                                                        <h4 class="pull-right font-16"><strong>Order# <?php echo isset($order)?$order:'--';?></strong></h4>
                                                        <h3 class="mt-0">
                                                            <img src="assets/images/logo_dark.png" alt="logo" height="26">
                                                        </h3>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <?php if($addressdetail !="")
                                                            {
                                                                ?>
                                                            
                                                            <address>
                                                                <strong>Billed To:</strong><br>
                                                                <div class="col-md-12 disp_addrs ">
                                                                    <span class="user_order_add"><?php echo $addressdetail->name;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs ">
                                
                                                                    <span class="user_order_add"><?php echo $addressdetail->address.' '.$addressdetail->city;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    
                                                                    <span class="user_order_add"><?php echo $addressdetail->landmark;?></span>
                                                                </div>
                                                               <div class="col-md-12 disp_addrs">
                                                                
                                                                    <span class="user_order_add"><?php echo $addressdetail->mobile;?></span>
                                                                </div>
                                                            </address>
                                                        <?php } ?>
                                                        </div>
                                                        <div class="col-6">
                                                             <?php if($addressdetail !="")
                                                            {
                                                                ?>
                                                            <address>
                                                                <strong>Shipped To:</strong><br>
                                                                 <div class="col-md-12 disp_addrs">
                                                                   <span class="user_order_add"><?php echo $addressdetail->name;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs ">
                                                                   
                                                                    <span class="user_order_add"><?php echo $addressdetail->address.' '.$addressdetail->city;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                   
                                                                    <span class="user_order_add"><?php echo $addressdetail->landmark;?></span>
                                                                </div>
                                                               <div class="col-md-12 disp_addrs">
                                                                
                                                                    <span class="user_order_add"><?php echo $addressdetail->mobile;?></span>
                                                                </div>
                                                            </address>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3 m-t-30">
                                                            <address>
                                                                <strong>Preferred location:</strong><br>
                                                                <p><?php echo $location['city']; ?></p><br>
                                                            </address>
                                                        </div>
                                                        <div class="col-3 m-t-30">
                                                            <address>
                                                                <strong>Delivery Method:</strong><br>
                                                                <p><?php echo $orderstatus->ordertype==1 ? 'Delivery' : 'Take Away';?></p><br>
                                                            </address>
                                                        </div>
                                                        <div class="col-3 m-t-30">
                                                            <address>
                                                                <strong>Payment Method:</strong><br>
                                                                <p><?php echo 'Online';?></p><br>
                                                                
                                                            </address>
                                                        </div>
                                                        <div class="col-3 m-t-30">
                                                            <address>
                                                                <strong>Order Date:</strong><br>
                                                                <p>
                                                                <?php 
                                                                if(isset($bill)) 
                                                                {
                                                                    echo date("d-m-Y h:i A", strtotime($bill->createdate.'+ 9 hour +30 minutes'));
                                                                }
                                                                ?></p><br><br>
                                                            </address>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="panel panel-default">
                                                        <div class="p-2">
                                                            <h3 class="panel-title font-20"><strong>Order summary</strong></h3>
                                                        </div>
                                                        <div class="">
                                                             <?php 
                                                                $paydetails = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_payment_status`  WHERE `order_id` = '".$order."' LIMIT 1"));
                                                                        if($paydetails !='')
                                                                        {
                                                                         ?>
                                                                   <div class="col-md-12 ">
                                                                        <div class="col-md-2" style="padding: 0px;margin-top: 6px;">
                                                                            <label>Payment Details :</label>
                                                                        </div>  
                                                                    </div>
                                                                <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow-div padd_lef_rgt">
                                                                    <table border="1" class="table borderclr">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Tracking Id</th>
                                                                                <th>Bank Ref No</th>
                                                                                <th>Pay mode</th>
                                                                                <th>Card Name</th>
                                                                                <th>Currency</th>
                                                                                <th>Amount</th>
                                                                                <th>status</th>
                                                                            </tr>
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
                                                                        </thead>
                                                                    </table>
                                                                
                                                                    </div>  
                                                                    <?php 
                                                                } ?> 
                                                        <?php 
                                                        $con = "SELECT pu.comments AS comments,pu.date AS dated, pu.time AS timng, pu.msg AS msg, pu.kg AS kg,it.id AS pid, pa.amount AS amount, pa.uid AS uid, pu.ptotal AS total, it.title AS title, ct.title AS category, pu.pqual AS pqual, ptotal AS ptotal, pu.extra AS extra FROM ss_payments pa JOIN ss_purchase pu ON pu.bno = pa.bno JOIN ss_items it ON it.id = pu.pid JOIN ss_category ct ON ct.id = it.cid WHERE pa.bno='".$order."' ";
                                                                $selectquery = mysqli_query($conn, $con);
                                                                $numrows = mysqli_num_rows($selectquery);
                                                                ?>
                                                                
                                                               
                                                                <div class="col-md-12">
                                                                    <div class="col-md-2" style="padding: 0px;margin-top: 6px;">
                                                                        <label>Product Details :</label>
                                                                    </div>  
                                                                </div>
                                                                <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow-div padd_lef_rgt">
                                                                <table border="1" class="table borderclr">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>S.No</th>
                                                                            <th>Product Name</th>
                                                                            <th>Category</th>
                                                                            <th>Quantity</th>
                                                                            <th>Kg</th>
                                                                            <th>Message on cake</th>
                                                                            <th>Comment</th>
                                                                            <th>Date</th>
                                                                            <th>Timing</th>
                                                                            <th>Total Price</th>   
                                                                        </tr>
                                                                    </thead>
                                                                    <?php
                                                                        if($numrows > 0) {
                                                                            $srlno = 1;
                                                                            $netpay = 0;
                                                                            $gstper =0;
                                                                            $overallgstprice=0;
                                                                            while($row = mysqli_fetch_array($selectquery)) 
                                                                                {
                                                                                $pqual=isset($row['pqual']) && ($row['pqual'] !='0')?$row['pqual'] :'';
                                                                                $netpay += $row['ptotal'];
                                                                                
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $srlno++; ?></td>
                                                                                    <td><?php echo htmlentities($row['title']);?> <?php if($row['extra']==1) { echo ' ( Eggless ) '; } ?> </td>
                                                                                    <td><?php echo $row['category'];?></td>
                                                                                    <td><?php echo $row['pqual'];?></td>
                                                                                    <td><?php echo $row['kg'];?></td>
                                                                                    <td><?php echo htmlentities($row['msg'] ? $row['msg'] : 'Nil');?></td>
                                                                                    <td><?php echo htmlentities($row['comments'] ? $row['comments'] : 'Nil');?></td>
                                                                                    <td><?php echo htmlentities($row['dated'] ? $row['dated'] : 'Nil');?></td>
                                                                                    <td><?php echo $row['timng'] ? $row['timng']  : 'Nil' ;?></td>
                                                                                    <td><?php echo round($row['ptotal']);?></td>
                                                                                    
                                                                                </tr>
                                                                                <?php
                                                                            }?>
                                                                            <tfoot>
                                                                                 <tr>
                                                                                    <?php  ?>
                                                                                    <th colspan="9" class="text-right">Delivery amount</th>
                                                                                    <th><?php echo $bill->delivery; ?></th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th colspan="9" class="text-right">TOTAL</th>
                                                                                    <th><?php echo $orderstatus->amount; ?></th>
                                                                                </tr>
                                                                            </tfoot>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                </table>
                                                            </div>
                                                            <div class="d-print-none">
                                                                <div class="pull-right">
                                                                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                                    <!-- <a class="btn btn-primary waves-effect waves-light" href="order_pdf.php?id=<?php echo $order;?>&action=download">Invoice PDF</a> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                </div>
                                            </div> <!-- end row -->
            
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
            

                        </div><!-- container -->
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
                $('#_Reason').hide();
                

                $(document).on('change', '#_oType', function() {
                    var category = this.value;
                    if(category=="Cancel")
                    {
                        $('#_Reason').show();
                    }
                    else
                    {
                        $('#_Reason').hide();
                    }
                });
                $(document).on('click', '#_update_btn', function() {
                    $.ajax({
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            'method': 'update',
                            'order_id': $('#_order_id').val(),
                            'reason': $('#_Reason_value').val(),
                            'otype': $('#_oType').val(),
                        },
                        url: 'inc/update-orders.php',
                        beforeSend: function(x) {
                            $('#status').show();
                            $('#preloader').show();
                        }
                    }).done(function(response) {
                        $('#status').hide();
                        $('#preloader').hide();
                        if (response['status'] == true)
                        {
                            $('#o-message').removeClass('alert alert-danger').addClass('alert alert-success');
                            $('#o-message').html(response['message']);
                            var _vs = $('#_oType').val();
                            $('#_h5').html('<strong>'+_vs+'</strong>');
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


                function gototop() {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);
                }

            });

            function alertHelper() {
                setTimeout(function() {
                    $('.successCls').removeClass('show').addClass('hide');
                    $('.errorCls').removeClass('show').addClass('hide');
                }, 2000);
            }
        </script>
    </body>

</html>