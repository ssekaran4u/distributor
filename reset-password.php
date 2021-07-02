<?php 
include 'inc/config.php';
$token = $_GET['token'];
if(!$token)  {
    header("location:index.php");
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
                            <img src="assets/images/logo_dark.png" alt="logo">
                        </a>
                    </h3>
                    <div class="p-3">
                        <h4 class="text-muted font-18 m-b-5 text-center">Welcome Back !</h4>
                        <p class="text-muted text-center">Reset Admin Password.</p>
                        <div id="o-message"></div>
                        <form name="myform" id="_URset" role="form" method="POST" onclick="return false;">
                            <fieldset>
                                <div class="form-group">
                                    <input id="pwd" class="form-control" placeholder="Password" name="password" type="password">
                                </div>
                                <div class="form-group">
                                    <input id="cpwd" class="form-control" placeholder="Confirm Password" name="cpassword" type="password">
                                </div>
                                <div class="form-group text-center">
                                    <input id="hash" name="hash" type="hidden" value="<?php echo $token; ?>">
                                    <input type="hidden" name="method" value="_URset">
                                    <button id="_URset_Btn" class="btn btn-info">Reset</button>
                                </div>
                            </fieldset>
                        </form>
                        <div class="form-group m-t-10 mb-0 row">
                            <div class="col-12 m-t-20 text-center">
                                <a href="index" class="text-muted"><i class="mdi mdi-lock"></i>Signin.?</a>
                            </div>
                        </div>
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
            $(document).on('click', '#_URset_Btn', function () {
          var datastring = $('#_URset').serialize();
          $.ajax({
            method: 'POST',
            data: datastring,
            url: 'inc/admin-login.php',
            dataType: 'JSON',
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
              $('#o-message').removeClass('alert alert-danger').addClass('alert alert-success');
              $('#o-message').html(response['message']);
              if(response['type'] == 5)
              {
                setTimeout(function() {
                    window.location.href= 'index';
                }, 5000);
              }
              $('#_URset').remove();
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
              AlertClose_();
            }
          });
        });
        });
    </script>
    </body>
</html>