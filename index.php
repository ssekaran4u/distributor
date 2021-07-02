<?php 
session_start();
include 'inc/config.php';
if(!empty($_SESSION['username']))
{
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title><?php echo $config["site_name"]; ?> - Admin Login</title>
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
    <body>
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
        <div class="accountbg"></div>
        <div class="wrapper-page">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center m-0">
                        <a href="index.php" class="logo logo-admin">
                            <img src="assets/images/logo.png" alt="logo" style="width: 190px;">
                        </a>
                    </h3>
                    <div class="p-3">
                        <h4 class="text-muted font-18 m-b-5 text-center">Welcome Back !</h4>
                        <p class="text-muted text-center">Sign in to continue to Admin Panel.</p>
                        <div id="o-message"></div>
                        <form id="log-form" class="form-horizontal m-t-30" method="post" onsubmit="return false;">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" placeholder="Enter username">
                            </div>
                            <div class="form-group">
                                <label for="userpassword">Password</label>
                                <input type="password" name="password" class="form-control" id="userpassword" placeholder="Enter password">
                            </div>
                            <div class="form-group row m-t-20">
                                <div class="col-sm-12 text-center">
                                    <button id="butf2" class="btn btn-primary w-md waves-effect waves-light log_btn">Log In</button>
                                </div>
                            </div>
                            <!-- <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <a href="forget" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                </div>
                            </div> -->
                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p><?php include 'includes/copyright.php'; ?></p>
            </div>
        </div>
        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <!-- App js -->
        <script src="assets/js/app.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            $('#butf2').on('click', function () {
            var datastring = 'method=insert&' + $("#log-form").serialize();
            $.ajax({
              type: 'POST',
              url: "inc/admin-login.php",
              dataType: 'JSON',
              data: datastring,
              beforeSend: function (x) {
                $('#status').show();
                $('#preloader').show();
              }
            }).done(function (response) {
                $('#status').hide();
                $('#preloader').hide();
                if (response['status'] == true)
                {
                    $('#o-message').removeClass('alert alert-danger').addClass('alert alert-success');
                    $('#o-message').html(response['message']);
                    if(response['type'] == 1 || response['type'] == 2)
                    {
                        $('#o-message').remove();
                        window.location = 'dashboard';
                        $('#o-message').empty();
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