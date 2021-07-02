<?php 
    session_start();
    include 'inc/config.php';
    isAdmin();

    if(isset($_POST['export']))
    {
        $now         = date('d-M-Y');
        $fromdate    = date('Y-m-d', strtotime($_POST['fromdate']));
        $todate      = date('Y-m-d', strtotime($_POST['todate']));
        $category_id = $_POST['category_id'];
        $search_type = $_POST['search_type'];

        if($fromdate!='' && $todate!='')
        {
            if($fromdate <= $todate)
            {
                header('Content-Type: text/csv; charset=utf-8');  
                header('Content-Disposition: attachment; filename=serialnumber_wise_report_('.date('d-M-Y', strtotime($fromdate)).' - '.date('d-M-Y', strtotime($todate)).').csv');  
                $output = fopen("php://output", "w");   
                fputcsv($output, array('S.No','Sales No','Category', 'Product', 'Description', 'Serial No', 'Sales Date'));

                $sel_1 = "SELECT * FROM `ss_item_stk` WHERE `published` = '1' ";

                if(!empty($category_id))
                {
                    $sel_1 .= " AND `cid` = '".$category_id."'";
                }

                if($search_type == 1)
                {
                    $sel_1 .= " AND `sales_date` BETWEEN '".$fromdate."' AND '".$todate."' AND `status` = '0'";
                }

                if($search_type == 2)
                {
                    $sel_1 .= " AND `d_sales_date` BETWEEN '".$fromdate."' AND '".$todate."' AND `delar_sales` = '0'";
                }

                if($search_type == 3)
                {
                    $sel_1 .= " AND `service_date` BETWEEN '".$fromdate."' AND '".$todate."' AND `service_status` = '0'";
                }

                $qry_1 = mysqli_query($conn, $sel_1);
                $cou_1 = mysqli_num_rows($qry_1);

                if($cou_1 > 0)
                {
                    $no = 1;
                    while($res_1 = mysqli_fetch_object($qry_1))
                    {   
                        $cid          = !empty($res_1->cid)?$res_1->cid:'';
                        $product_id   = !empty($res_1->product_id)?$res_1->product_id:'';
                        $code         = !empty($res_1->code)?$res_1->code:'';
                        $sales_no     = !empty($res_1->sales_no)?$res_1->sales_no:'';
                        $sales_date   = !empty($res_1->sales_date)?$res_1->sales_date:'';
                        $delar_sales  = !empty($res_1->delar_sales)?$res_1->delar_sales:'';
                        $d_sales_date = !empty($res_1->d_sales_date)?$res_1->d_sales_date:'';
                        $service_no   = !empty($res_1->service_no)?$res_1->service_no:'';
                        $service_date = !empty($res_1->service_date)?$res_1->service_date:'';

                        $qry_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE `id` = '".$cid."' AND `published` = '1'"));

                        $cat_title   = isset($qry_2->title)?$qry_2->title:'';

                        $qry_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$product_id."' AND `published` = '1'"));

                        $pro_title   = isset($qry_3->title)?$qry_3->title:'';
                        $description = isset($qry_3->description)?$qry_3->description:'';

                        // Sales Date
                        if($search_type == 1)
                        {
                            $invoice_no   = $sales_no;
                            $invoice_date = date('d-M-Y', strtotime($sales_date));
                        }

                        if($search_type == 2)
                        {
                            $invoice_no   = $delar_sales;
                            $invoice_date = date('d-M-Y', strtotime($d_sales_date));
                        }

                        if($search_type == 3)
                        {
                            $invoice_no   = $service_no;
                            $invoice_date = date('d-M-Y', strtotime($service_date));
                        }

                        $num = array(
                            $no,
                            $invoice_no,
                            $cat_title,
                            $pro_title,
                            $description,
                            $code,
                            $invoice_date,
                        );

                        $no++;

                        fputcsv($output, $num);  
                    }
                        
                    fclose($output);
                    exit();
                }
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
                                            <h4 class="mt-0 header-title clearfix"><strong>Manage Serial No Wise Report</strong></h4>
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
                                                        <input  type="text" name="fromdate" id="fromdate" class="form-control dates" placeholder="dd-mm-yyyy">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <span>To Date <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="todate" id="todate" class="form-control dates" placeholder="dd-mm-yyyy">
                                                    </div>

                                                    <div class="col-md-3">
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
                                                        <input type="hidden" class="form-control" id="member_val" value="0">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <span>Type <span class="text-danger">*</span> </span>
                                                        <select class="form-control search_type js-select1-multi selectpicker" name="search_type" id="search_type">
                                                            <option value="0">--Select Type--</option>
                                                            <option value="1">Distributors</option>
                                                            <option value="2">Dealer</option>
                                                            <option value="3">Service</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3" style="margin-top: 20px;">
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
                    // $('#preloader').show();
                    // $('#status').show();
                    var fromdate    = $("#fromdate").val();
                    var todate      = $("#todate").val();
                    var category_id = $("#category_id").val();
                    var search_type = $("#search_type").val();

                    e.preventDefault();
                    $.ajax({
                        url: 'inc/admin-login.php',
                        method: 'POST',
                        data: {
                            "fromdate"    : fromdate,
                            "todate"      : todate,
                            "category_id" : category_id,
                            "search_type" : search_type,
                            "method"      : '_getSerialWiseReport'
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
            });
        </script>
    </body>
</html>