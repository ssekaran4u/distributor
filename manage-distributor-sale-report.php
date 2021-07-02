<?php 
    session_start();
    include 'inc/config.php';
    isAdmin();

    if(isset($_POST['export']))
    {
        $fromdate       = !empty($_POST['fromdate'])?date('Y-m-d', strtotime($_POST['fromdate'])):'';
        $todate         = !empty($_POST['todate'])?date('Y-m-d', strtotime($_POST['todate'])):'';
        $distributor_id = !empty($_POST['distributor_id'])?$_POST['distributor_id']:'';
        $invoice_id     = !empty($_POST['invoice_id'])?$_POST['invoice_id']:'';
        $category_id    = !empty($_POST['category_id'])?$_POST['category_id']:'';
        $product_id     = !empty($_POST['product_id'])?$_POST['product_id']:'';
        $now            = date('d-M-Y H:i:s');

        if($fromdate != '' && $todate != '')
        {
            $sel_1 = "SELECT * FROM `ss_distributor_inv_details` WHERE `dated` BETWEEN '".$fromdate."' AND '".$todate."' ";
            
            if(!empty($distributor_id))
            {
                $sel_1 .= " AND `customer_id` = '".$distributor_id."'";
            }
            if(!empty($invoice_id))
            {
                $sel_1 .= " AND `so_id` = '".$invoice_id."'";
            }
            if(!empty($category_id))
            {
                $sel_1 .= " AND `cid` = '".$category_id."'";
            }
            if(!empty($product_id))
            {
                $sel_1 .= " AND `pid` = '".$product_id."'";
            }

            $sel_1 .= " AND `published` = '1' AND `status` = '1' ";

            $qry_1 = mysqli_query($conn, $sel_1);
            $cou_1 = mysqli_num_rows($qry_1);

            header('Content-Type: text/csv; charset=utf-8');  
            header('Content-Disposition: attachment; filename=Sales Report ('.date('d-M-Y', strtotime($fromdate)).' to '.date('d-M-Y', strtotime($todate)).').csv');  
            $output = fopen("php://output", "w"); 
            fputcsv($output, array('S.No', 'Invoice No', 'Date', 'Customer Name', 'Store Name', 'Address', 'GSTIN', 'TCS No', 'Category Name', 'Product Name', 'HSN No', 'Value', 'Qty', 'Serial No', 'Basic Allowance', 'STA', 'Dealer Allowance', 'Discount', 'Taxable Value', 'GST Val', 'CGST', 'SGST', 'IGST', 'price', 'TCS Value', 'Net Value', 'Round off', 'Net Total' )); 

            if($cou_1 > 0)
            {
                $no = 1;
                while($res_1 = mysqli_fetch_object($qry_1))
                {
                    $auto_id     = !empty($res_1->auto_id)?$res_1->auto_id:'';
                    $so_id       = !empty($res_1->so_id)?$res_1->so_id:'';
                    $so_no       = !empty($res_1->so_no)?$res_1->so_no:'';
                    $customer_id = !empty($res_1->customer_id)?$res_1->customer_id:'';
                    $cid         = !empty($res_1->cid)?$res_1->cid:'';
                    $pid         = !empty($res_1->pid)?$res_1->pid:'';
                    $code        = !empty($res_1->code)?$res_1->code:'';
                    $qty         = !empty($res_1->qty)?$res_1->qty:'';
                    $price       = !empty($res_1->price)?$res_1->price:'0';
                    $gst         = !empty($res_1->gst)?$res_1->gst:'0';
                    $allowance   = !empty($res_1->allowance)?$res_1->allowance:'0';
                    $sta         = !empty($res_1->sta)?$res_1->sta:'0';
                    $d_allowance = !empty($res_1->d_allowance)?$res_1->d_allowance:'0';
                    $discount    = !empty($res_1->discount)?$res_1->discount:'0';
                    $dated       = !empty($res_1->dated)?$res_1->dated:'0';
                    $billstatus  = !empty($res_1->billstatus)?$res_1->billstatus:'0';

                    $qry_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE `id` = '".$cid."' AND `published` = '1'"));

                    $cat_title   = !empty($qry_2->title)?$qry_2->title:'';

                    $qry_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$pid."' AND `published` = '1'"));

                    $pro_title   = !empty($qry_3->title)?$qry_3->title:'';
                    $description = !empty($qry_3->description)?$qry_3->description:'';
                    $hsn_code    = !empty($qry_3->hsn)?$qry_3->hsn:'';

                    $qry_4 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `id` = '".$customer_id."' AND `deleted` = '1'"));

                    $store   = !empty($qry_4->store)?$qry_4->store:'';
                    $owner   = !empty($qry_4->owner)?$qry_4->owner:'';
                    $address = !empty($qry_4->customeraddress)?$qry_4->customeraddress:'';
                    $gst_no  = !empty($qry_4->gst)?$qry_4->gst:'';
                    $tcs_no  = !empty($qry_4->tcs_no)?$qry_4->tcs_no:'';
                    $state   = !empty($qry_4->state)?$qry_4->state:'';

                    $qry_5 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_invoice` WHERE `id` = '".$so_id."' AND `published` = '1'"));

                    $tcs_val = !empty($qry_5->tcs_type)?$qry_5->tcs_type:'';

                    // Discount Calculation
                    $allowance_res   = $price * $allowance / 100;
                    $allowance_val   = $qty * round($allowance_res);

                    $d_allowance     = $price * $d_allowance / 100;
                    $d_allowance_val = $qty * round($d_allowance);

                    $discount_value  = $qty * round($discount);

                    $_sta_value      = $qty * round($sta);

                    $netdiscount = $allowance_val + $_sta_value + $d_allowance_val + $discount_value;

                    $sub_to = $qty * $price;
                    $total  = $sub_to - $netdiscount;

                    // GST Calculation
                    $_gst = "1.".$gst;
                    $_val = $total / $_gst; 

                    $gstper    = $_val * $gst / 100;
                    $cal_gst   = $gstper / 2;
                    $total_val = $_val + $gstper;

                    if($state == '33')
                    {
                        $sGst = $cal_gst;
                        $cGst = $cal_gst;
                        $iGst = 0;
                    }
                    else
                    {
                        $sGst = 0;
                        $cGst = 0;
                        $iGst = $gstper;
                    }

                    // TCS Calculation
                    if($billstatus == 1)
                    {
                        if($tcs_val == '2')
                        {
                            $tcs_val = $total_val * 0.1 / 100;
                        }
                        else
                        {
                            $tcs_val = 0;
                        }
                    }
                    else
                    {
                        if($tcs_val == '2')
                        {
                            $tcs_val = $total_val * 0.1 / 100;
                        }
                        else
                        {
                            $tcs_val = 0;
                        }
                    }

                    $net_value = $total_val + $tcs_val;
                    $lst_tot   = round($net_value);
                    $rond_tot  = $lst_tot - $net_value;

                    $serial_no = '';
                    $qry_5 = mysqli_query($conn, "SELECT `code` FROM `ss_item_stk` WHERE `cid` = '".$cid."' AND `product_id` = '".$pid."' AND `sales_no` = '".$so_no."' AND `status` = '0' AND `published` = '1'");

                    while($res_5 = mysqli_fetch_object($qry_5))
                    {
                        $serial_val = !empty($res_5->code)?$res_5->code:'';
                        $serial_no .= $serial_val.',';
                    }

                    $num = array(
                        $no,
                        $so_no,
                        date('d-M-Y', strtotime($dated)),
                        $owner,
                        $store,
                        $address,
                        $gst_no,
                        $tcs_no,
                        $cat_title,
                        $pro_title,
                        $hsn_code,
                        $price,
                        $qty,
                        $serial_no,
                        $allowance_val,
                        $_sta_value,
                        $d_allowance_val,
                        $discount_value,
                        number_format($_val, 2),
                        $gst,
                        number_format($sGst, 2),
                        number_format($cGst, 2),
                        number_format($iGst, 2),
                        number_format($total, 2),
                        number_format($tcs_val, 2),
                        number_format($net_value, 2),
                        number_format($rond_tot, 2),
                        number_format($lst_tot, 2),
                    );

                    fputcsv($output, $num); 

                    $no++;  
                }
                fclose($output);
                exit();
            }
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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/jquery-ui.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
        <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>
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
                                <div class="col-lg-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title clearfix"><strong>Manage Distributor Sales Wise Report</strong></h4>
                                            <hr>
                                            <div class="col-lg-12 successCls alert alert-success alert-dismissible fade hide" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                                <span class="successmsg"></span>
                                            </div>
                                            <div class="col-lg-12 errorCls alert alert-danger alert-dismissible fade hide" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                                <span class="errormsg"></span>
                                            </div>
                                            <form id="add-form" name="add-form" method="post">
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>From Date <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="fromdate" id="fromdate" class="form-control dates">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <span>To Date <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="todate" id="todate" class="form-control dates">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Distributor Name <span class="text-danger">*</span> </span>
                                                        <select class="form-control distributor_id js-select1-multi selectpicker" name="distributor_id" id="distributor_id">
                                                            <?php
                                                                $a = mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `deleted` = '1' ORDER BY `id` ASC ");
                                                                $count = mysqli_num_rows($a);
                                                                ?>
                                                            <option value="0">--Select Distributor Name--</option>
                                                            <?php
                                                                if($count > 0)
                                                                {
                                                                    while($res = mysqli_fetch_object($a))
                                                                    {
                                                                        ?>
                                                                        <option value="<?php echo $res->id; ?>"><?php echo $res->store; ?></option>
                                                                        <?php   
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                        <input type="hidden" class="form-control" id="member_val" value="0">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Invoice <span class="text-danger">*</span> </span>
                                                        <select class="form-control invoice_id js-select1-multi selectpicker" name="invoice_id" id="invoice_id">
                                                            <option value="0">--Select Invoice--</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-4">
                                                        <span>Category Name <span class="text-danger">*</span> </span>
                                                        <select class="form-control category_id js-select1-multi selectpicker" name="category_id" id="category_id">
                                                            <?php
                                                                $a = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = 0 ORDER BY title ASC ");
                                                                $count = mysqli_num_rows($a);
                                                                ?>
                                                            <option value="0">--Select Category Name--</option>
                                                            <?php
                                                                $i=0; while($rowo = mysqli_fetch_array($a)) { 
                                                                $b = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = '".$rowo['id']."' ORDER BY title ASC");
                                                                $count = mysqli_num_rows($b);
                                                                ?> 
                                                            <option value="<?php echo $rowo['id']; ?>"><?php echo $rowo['title']; ?></option>
                                                            <?php if($count>0){ ?>
                                                            <?php
                                                                $i++;
                                                                while($rows = mysqli_fetch_array($b)) { ?>     
                                                            <option value="<?php echo $rows['id']; ?>">--<?php echo $rows['title']; ?></option>
                                                            <?php } ?>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Product Name <span class="text-danger">*</span> </span>
                                                        <select class="form-control product_id js-select1-multi selectpicker" name="product_id" id="product_id">
                                                            <option value="0">--Select Product Name--</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4" style="margin-top: 20px;">
                                                        <!-- <input type="hidden" name="method" id="method" value="add-form"> -->
                                                        <button type="button" id="report-forms" class="btn btn-danger waves-effect waves-light report-form export_report"> <i  class="mdi mdi-check-circle-outline"></i> Search </button>
                                                        <input type="hidden" name="method" id="method_" value="export-form">
                                                        <span class="excel_view hide"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="table_lst hide" style="width: 100%;">
                                                            <div id="custom-student-list">
                                                            </div>
                                                        </div>
                                                        <div id="error" class="show alert alert-danger text-center" style="width: 100%;">
                                                            <b>No Record found...</b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery-ui.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/js/select2.full.js"></script>
        <script src="assets/js/app.js"></script>
        <script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>
        <script type="text/javascript">
            $(document).ready(function () {

                $('.dates').datepicker({ 
                    maxDate: 0,
                    dateFormat: 'dd-mm-yy',
                    ignoreReadonly: true,
                }).datepicker("setDate", new Date());
            
                $(".js-select1-multi").select2({
                    placeholder: "Select Category Name"
                });
            
                $(".js-select2-multi").select2({
                    placeholder: "Select Member Name"
                });
            
                $('.dates').datepicker({
                    maxDate: 0,
                    dateFormat: 'yy-mm-dd',
                    ignoreReadonly: true,
                });
            
                function gototop() {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);
                }
            
                function alertHelper()
                {
                    setTimeout(function ()
                    {
                        $('.successCls').removeClass('show').addClass('hide');
                        $('.errorCls').removeClass('show').addClass('hide');
                    }, 2000);
                }
            
                $('#report-forms').on('click', function (e) {
                    $('#preloader').show();
                    $('#status').show();
                    var fromdate       = $("#fromdate").val();
                    var todate         = $("#todate").val();
                    var distributor_id = $("#distributor_id").val();
                    var invoice_id     = $("#invoice_id").val();
                    var category_id    = $("#category_id").val();
                    var product_id     = $("#product_id").val();
            
                    e.preventDefault();
                    $.ajax({
                        url: 'inc/admin-login.php',
                        method: 'POST',
                        data: {
                            "fromdate"       : fromdate,
                            "todate"         : todate,
                            "distributor_id" : distributor_id,
                            "invoice_id"     : invoice_id,
                            "category_id"    : category_id,
                            "product_id"     : product_id,
                            "method"         : '_getDisSalesReport'
                        },
                        dataType: 'json',
                    }).done(function(response){
                        console.log(response['status']);
                        $('#preloader').hide();
                        $('#status').hide();
                        if(response['status'] == true)
                        {
                            $('.table_lst').removeClass('hide').addClass('show');
                            $('#error').removeClass('show').addClass('hide');
            
                            $('.excel_view').removeClass('hide').addClass('show');
                            $('.excel_view').empty().html(response['excel_btn']);
            
                            $('#custom-student-list').empty();
                            $('#custom-student-list').html(response['result']);
                            alertHelper();
                        }   
                        else
                        {
                            $('.table_lst').removeClass('show').addClass('hide');
                            $('#error').removeClass('hide').addClass('show');
                            $('.errorCls').removeClass('hide').addClass('show');
                            $('.errormsg').empty().html(response['message']);
            
                            $('.excel_view').removeClass('show').addClass('hide');
                            $('.excel_view').empty();
            
                            alertHelper();
            
                        }
                    });
                });

                $(document).on('change','#distributor_id', function () {
                    $('#preloader').show();
                    $('#status').show();
                    var fromdate       = $("#fromdate").val();
                    var todate         = $("#todate").val();
                    var distributor_id = $('#distributor_id').val();  
                    $.ajax({
                    type : "POST",
                    url  : "inc/admin-login.php",
                    data : {
                        "fromdate"       : fromdate, 
                        "todate"         : todate, 
                        "distributor_id" : distributor_id, 
                        "method"         : "_getDisInvoice"
                    },
                    dataType: 'json',
                    }).done(function(response) {
                        console.log(response['result']);
                        $('#preloader').hide();
                        $('#status').hide();
                        if(response['status'] == true)
                        {
                            $('.invoice_id').empty().html(response['result']);
                        } 
                        else
                        {
                            $('.invoice_id').empty().html(response['result']);
                        } 
                    });
                });

                $(document).on('change','#category_id', function () {
                    $('#preloader').show();
                    $('#status').show();
                    var category_id = $('#category_id').val();  
                    $.ajax({
                    type : "POST",
                    url  : "inc/admin-login.php",
                    data : {"category_id":category_id, "method":"_getProduct"},
                    dataType: 'json',
                    }).done(function(response) {
                        console.log(response['result']);
                        $('#preloader').hide();
                        $('#status').hide();
                        if(response['status'] == true)
                        {
                            $('.product_id').empty().html(response['result']);
                        } 
                        else
                        {
                            $('.product_id').empty().html(response['result']);
                        } 
                    });
                });
            });
        </script>
    </body>
</html>
