 <?php 
session_start();
include 'inc/config.php';
isAdmin();
$order = $_GET['id'];
if($order){
    $addressdetail = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_item_stk`  WHERE `invoice_no` = '".$order."' LIMIT 1"));
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
                                                        <h4 class="pull-right font-16"><strong>Order# <?php echo isset($addressdetail->invoice_no)?$addressdetail->invoice_no:'--';?></strong></h4>
                                                        <h3 class="mt-0">
                                                            <img src="assets/images/logo.png" alt="logo" height="26">
                                                        </h3>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Invoice Number:</strong><br>
                                                                <?php echo $addressdetail->invoice_no ? $addressdetail->invoice_no : '---';?>
                                                                
                                                            </address>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 col-xs-12 text-center m-t-30">
                                                            <address>
                                                                <strong>Dated:</strong><br>
                                                                <?php 
                                                                    echo date("d-m-Y", strtotime($addressdetail->date));
                                                                ?>
                                                            </address>
                                                        </div>
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

                                                        $con = "SELECT * FROM `ss_item_stk` WHERE invoice_no = '".$order."' GROUP BY product_id";
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
                                                                            <th >S.No</th>
                                                                            <th >Description</th>
                                                                            <th >Product Name</th>
                                                                            <th >Categories</th>
                                                                            <th >Brand</th>
                                                                            <th >Qty</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <?php
                                                                        if($numrows > 0) {
                                                                            $srlno = 1;
                                                                            $_total=0;
                                                                            while($row = mysqli_fetch_array($selectquery)) 
                                                                                {

                                                                                    $product = mysqli_fetch_object(mysqli_query($conn, "SELECT description, title, brand FROM ss_items WHERE id = '".$row['product_id']."'"));

                                                                                    $category = mysqli_fetch_object(mysqli_query($conn, "SELECT title FROM `ss_category` WHERE id = '".$row['cid']."' "));

                                                                                    $brand = mysqli_fetch_object(mysqli_query($conn, "SELECT brand FROM `ss_brands` WHERE id = '".$product->brand."' "));
                                                                                    
                                                                                    $qty = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(`id`) AS qty FROM ss_item_stk WHERE `invoice_no` = '".$order."' AND product_id = '".$row['product_id']."'"));

                                                                                    $total = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(`id`) AS qty FROM ss_item_stk WHERE `invoice_no` = '".$order."'"));

                                                                                    $_total = $total->qty;
                                                                                 
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $srlno++; ?></td>
                                                                                    <td><?php echo $product->description;?> 
                                                                                    <td><?php echo $product->title;?></td>
                                                                                    <td><?php echo $category->title;?></td>
                                                                                    <td><?php echo $brand->brand;?></td>
                                                                                    <td><?php echo $qty->qty;?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }?>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <?php  ?>
                                                                                    <th colspan="5" class="text-right">Total</th>
                                                                                    <th><?php echo round($_total); ?></th>
                                                                                </tr>
                                                                                </tr>
                                                                            </tfoot>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                </table>
                                                                    </div>
                                                            </div>
                                                            <div class="d-print-none">
                                                                <div class="pull-right">
                                                                    <!-- <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a> -->
                                                                    <!-- <a  href="lib/TCPDF-master/examples/example_006.php?id=<?php echo $order;?>" target="_blank" class="btn btn-success waves-effect waves-light "></i>Print</a> -->
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