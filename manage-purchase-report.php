<?php 

session_start();

include 'inc/config.php';

isAdmin();

if(isset($_POST['export']))
{
    $now = date('d-M-Y');
    $from     = date('Y-m-d', strtotime($_POST['from']));
    $to       = date('Y-m-d', strtotime($_POST['to']));
    $product  = $_POST['product'];

    if($from!='' && $to!='')
    {
        header('Content-Type: text/csv; charset=utf-8');  
        header('Content-Disposition: attachment; filename=purchase_report_'.$now.'.csv');  
        $output = fopen("php://output", "w");   
        fputcsv($output, array('Invoice no', 'Serial No','Description', 'Product Name', 'Categories')); 

        $qry = "SELECT * FROM `ss_item_stk` WHERE `date` BETWEEN '".$from."' AND '".$to."' ";
        if($product != 0)
        {
            $qry .= " AND `product_id` = '".$product."' ";   
        }
        $qry .= "ORDER BY `invoice_no`";

        $selectquery = mysqli_query($conn, $qry);

        while($res = mysqli_fetch_object($selectquery))
        {
            $invoice_no = isset($res->invoice_no)?$res->invoice_no:'';
            $serial_no  = isset($res->code)?$res->code:'';
            $categ_id   = isset($res->cid)?$res->cid:'';
            $product_id = isset($res->product_id)?$res->product_id:'';

            $cat_qry = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE id = '".$categ_id."'"));

            $category_name = !empty($cat_qry->title)?$cat_qry->title:'';

            $pdt_qry = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE id = '".$product_id."'"));

            $product_name = !empty($pdt_qry->title)?$pdt_qry->title:'';
            $product_desc = !empty($pdt_qry->description)?$pdt_qry->description:'';

            $num = array(
              $invoice_no,
              $serial_no,
              $product_desc,
              $category_name,
              $product_name);

            fputcsv($output, $num); 
        }

        fclose($output);

        exit();
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

        <!-- Basic Css files -->

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

                                            <!-- <span class="loading"></span> -->

                                            <h4 class="mt-0 header-title clearfix"><strong>Manage Purchase Report</strong></h4>

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

                                            <form id="add-form" name="add-form" method="post" >

                                              <div class="row clearfix mb20">
                                                <div class="col-md-2 ">
                                                    <span>From Date  <span class="text-danger">*</span></span>
                                                    <input  type="text" name="from" id="from" class="form-control dates" placeholder="dd-mm-yyyy">
                                                </div>

                                                <div class="col-md-2 ">
                                                    <span>To Date</span>
                                                    <input  type="text" name="to" id="to" class="form-control dates" placeholder="dd-mm-yyyy" 
                                                    >

                                                </div>

                                                <div class="col-md-4 ">
                                                    <span>Product</span>
                                                    <select class="form-control product[] js-select1-multi" name="product" id="product">
                                                      <?php
                                                        $a = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = 0 ORDER BY title ASC ");
                                                        $count = mysqli_num_rows($a);
                                                      ?>
                                                      <option value="0">--Select Product Name--</option>
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

                                                <div class="col-md-1 " style="margin-top: 20px;">
                                                  <input type="hidden" name="method" id="method" value="add-form">
                                                    <button type="submit" id="add-forms" class="btn btn-danger waves-effect waves-light add-form"> <i  class="mdi mdi-check-circle-outline"></i> Search </button>
                                                </div>
                                                <div class="col-md-1 fl pdz" style="margin-left: 25px; margin-top: 20px;">
                                                    <input type="hidden" name="method" id="method_" value="export-form">
                                                      <button type="submit" name="export" id="form-export" class="btn btn-primary waves-effect waves-light form-export"> <i  class="mdi mdi-file-excel"></i> Export </button>
                                                  </div>
                                                </div>

                                              <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow-div">

                                                <table class="table hide">

                                                    <thead>

                                                        <tr>

                                                            <th>#</th>

                                                            <th>Invoice Number</th>

                                                            <th>Qty </th>

                                                            <th>Date</th>

                                                            <th>Status</th>

                                                        </tr>

                                                        <input id="status" type="hidden" name="status" value="0">

                                                    </thead>

                                                    <tbody id="getCategory">

                                                    </tbody>

                                                </table>
                                              </div>
                                              <div id="error" class="show alert alert-danger text-center" style="width: 100%;">
                                                  <b>No items found...</b>
                                              </div>
                                            </form>

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

        <script src="assets/js/jquery-ui.js"></script>

        <script src="assets/js/bootstrap.min.js"></script>

        <script src="assets/js/modernizr.min.js"></script>

        <script src="assets/js/jquery.slimscroll.js"></script>

        <script src="assets/js/waves.js"></script>

        <script src="assets/js/jquery.nicescroll.js"></script>

        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/js/app.js"></script>

        <script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>

        <script type="text/javascript">
            $('.dates').datepicker({
                maxDate: 0,
                dateFormat: 'dd-mm-yy',
                ignoreReadonly: true,
            });
            $(document).ready(function () {

              $(".js-select1-multi").select2({
                  placeholder: "Select Product Name"
              });

              function gototop() {

                $('html, body').animate({

                  scrollTop: 0

                }, 1000);

              }

              $('#add-forms').on('click', function (e) {

                  var from    = $("#from").val();
                  var to      = $("#to").val();
                  var product = $("#product").val();
                  var method  = $("#method").val();

                  e.preventDefault();
                    $.ajax({
                        url: 'inc/manage-purchase-report.php',
                        method: 'POST',
                        data: {
                            "from":from,
                            "to":to,
                            "product":product,
                            "method":method
                        },
                    }).done(function(response){
                      console.log(response);
                      if(response == 1)
                      {
                        $('#error').removeClass('hide');
                        $('#error').addClass('show');
                        $('.table').addClass('hide');
                      }
                      else
                      { 
                        $('#error').removeClass('show');
                        $('#error').addClass('hide');
                        $('.table').removeClass('hide');
                        $('#getCategory').html(response);
                      }
                    });
                });

            });

        </script>

    </body>

</html>