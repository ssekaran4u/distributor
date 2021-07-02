<?php 
session_start();
include 'inc/config.php';
isAdmin();
$input = new Input;
$get = $input->get('view');
$did = $input->get('id');
$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_coupon_code` WHERE `id` = '".$did."' LIMIT 1"));

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
                                            <h4 class="mt-0 header-title clearfix"><strong> <?php echo $input->get('view')=='edit' ? 'Update ' : 'Create'; ?> Coupon</strong> <a class="pull-right btn btn-pink" href="manage-coupons"><i class="fa fa-plus-circle"></i> Manage Coupons</a></h4>
                                            <hr>
                                            <div id="o-message"></div>
                                            <form method="POST" id="dis" class="clearfix">
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Code<span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input id="code" type="text" name="code" placeholder="Enter code" class="form-control" value="<?php echo isset($fetch->coupon_code) ? $fetch->coupon_code : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Discount Percentage <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" id="percentage" name="percentage" placeholder="Discount Percentage" class="form-control" value="<?php echo isset($fetch->percentage) ? $fetch->percentage : ''; ?>">
                                                    </div>
                                                </div>
                                                <?php if(!$input->get('id')): ?>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Validity Period <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" name="validity" id="validity" placeholder="Validity Period in Days" class="form-control" value="<?php echo isset($fetch->validity) ? $fetch->validity : ''; ?>">
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($input->get('id')): ?>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Validity Expired on <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php echo date("d/m/Y", strtotime($fetch->validity)); ?>
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Status <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input <?php echo $fetch->status==1 ? 'checked' : ''; ?> type="radio" name="status" value="1"> Enable
                                                        <input <?php echo $fetch->status==2 ? 'checked' : ''; ?> type="radio" name="status" value="2"> Disable
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <hr>                                             
                                                <input type="hidden" name="method" value="<?php echo isset($fetch->id) ? 'update' : 'insert'; ?>">
                                                <input type="hidden" name="id" value="<?php echo isset($fetch->id) ? $fetch->id : ''; ?>">
                                                <button id="coupons" type="button" class="btn btn-danger waves-effect waves-light"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo $input->get('view')=='edit' ? 'Update ' : 'Create'; ?> Coupon Code </button>
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
        <script type="text/javascript">
            $(document).ready(function () {
              function alertHelper()
              {
                setTimeout(function () {
                  $('.successCls').removeClass('show').addClass('hide');
                  $('.errorCls').removeClass('show').addClass('hide');
                }, 2000);
              }
              $(document).on('click', '#coupons', function () {
                var datastring = $('#dis').serialize();
                $.ajax({
                  method: 'POST',
                  data: datastring,
                  url: 'inc/create-coupons.php',
                  dataType: 'JSON',
                }).done(function (response)
                {
                  if (response['status'] == true)
                  {
                    $('#o-message').removeClass('alert alert-danger').addClass('alert alert-success');
                    $('#o-message').html(response['message']);
                    if (response['type'] == 1)
                    {
                      $('#dis') [0].reset();
                    }
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
            });
        </script>
    </body>
</html>