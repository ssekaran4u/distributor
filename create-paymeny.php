<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();

$id = isset($_GET['id'])?$_GET['id']:'0';

$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `id` = '".$id."' LIMIT 1"));

$balance = $fetch->credit_lmt - $fetch->avl_lmt;

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
                                            <h4 class="mt-0 header-title clearfix"><strong><?php echo isset($fetch->id) ? 'Add' : 'Add'; ?> Payment</strong> <a class="pull-right btn btn-pink" href="manage-payment"><i class="fa fa-plus-circle"></i> Manage Payment</a></h4>
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
                                            <form id="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" name="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" method="post">
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-4">
                                                        <span>Amount <span class="text-danger">*</span> </span>
                                                        <input type="text" name="amount" class="form-control" placeholder="Enter the Amount" >   
                                                        <input type="hidden" name="customer_id" id="customer_id" class="form-control" value="<?php echo isset($fetch->id) ? $fetch->id : ''; ?>" >
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Balance <span class="text-danger">*</span> </span>
                                                        <input type="text" readonly="readonly" style="background-color: #fff;" name="balance" class="form-control" value="<?php echo isset($balance) ? $balance : ''; ?>" >
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Date <span class="text-danger">*</span> </span>
                                                        <input type="date" name="date" class="form-control" >
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-6">
                                                        <span>Type <span class="text-danger">*</span> </span>
                                                        <select class="form-control type[] js-select3-multi" name="type" id="type">
                                                            <option value="Cash">Cash</option>
                                                            <option value="Cheque">Cheque</option>
                                                            <option value="DD">DD</option>
                                                            <option value="RTGS">RTGS</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span>Description </span>
                                                        <input type="text" name="description" class="form-control" placeholder="Enter the Description" >
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- <input type="hidden" name="method" value="add-state">  -->
                                                <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                                                <input type="hidden" name="method" value="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-payment">
                                                <button type="submit" id="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" class="btn btn-danger waves-effect waves-light <?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-payment"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo isset($fetch->id) ? 'Add' : 'Add'; ?> Payment </button>

                                                <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow" style="margin-top: 10px;">
                                                    <table class="table">

                                                        <thead>

                                                            <tr>

                                                                <th>Amount</th>

                                                                <th>Type</th>

                                                                <th>Description</th>

                                                                <th>Date</th>

                                                            </tr>

                                                            <input id="status" type="hidden" name="status" value="0">

                                                        </thead>

                                                        <tbody id="getCategory" class="row_position">

                                                        </tbody>
                                                    </table>
                                                </div><br>
                                                <div class="col-md-12">
                                                    <div id="error" class="hide alert alert-danger text-center">
                                                        <b>No items found...</b>
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
        <script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>
        <script type="text/javascript">
            $(document).ready(function (e) {
                loadOrderBox();

                $(".js-select3-multi").select2({
                    placeholder: "Select Payment Type"
                });

                $('#edit-form').on('submit', function (e) {
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: 'AjaxFunction/AjaxPayment.php',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'json',
                        beforeSend: function (x) {
                          $('#status').show();
                          $('#preloader').show();
                        }
                    }).done(function (response)
                    {
                      $('#status').hide();
                      $('#preloader').hide();
                      if (response['status'] == true)
                      {
                        $('.successCls').removeClass('hide');
                        $('.successCls').addClass('show');
                        $('.errorCls').addClass('hide');
                        $('.successmsg').html(response['message']);
                        $('#edit-form') [0].reset();
                        alertHelper();
                        loadOrderBox();
                      } 
                      else
                      {
                        $('.errorCls').removeClass('hide');
                        $('.errorCls').addClass('show');
                        $('.successCls').addClass('hide');
                        $('.errormsg').html(response['message']);
                        alertHelper();
                        loadOrderBox();
                      }
                    });
                  });

                function alertHelper()
                {
                    setTimeout(function ()
                    {
                        $('.successCls').removeClass('show').addClass('hide');
                        $('.errorCls').removeClass('show').addClass('hide');
                    }, 5000);
                }
                function loadOrderBox() {
                    var customer_id = $("#customer_id").val();
                    $.ajax({
                        method: 'POST',
                        data: {
                            "customer_id":customer_id
                        },
                        url:"inc/manage-balance.php",
                    }).done(function (response) {
                        $('#status').hide();
                        $('#preloader').hide();
                        if (response == 1)
                        {
                            $('#error').removeClass('hide');
                            $('.table').addClass('hide');
                            $('.complete_but').addClass('hide');
                        } 
                        else
                        {
                            $('#error').addClass('hide');
                            $('.table').removeClass('hide');
                            $('.complete_but').removeClass('hide');
                            $('#getCategory').html(response);
                        }
                    });
                }
            });
        </script>

    </body>
</html>