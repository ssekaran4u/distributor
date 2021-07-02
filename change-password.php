<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();
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
                                            <h4 class="mt-0 header-title clearfix"><strong>Change Password</strong> </h4>
                                            <hr>
                                            <div id="o-message"></div>
                                            <form id="_Pupdate" method="POST" onsubmit=" return false;">
                                                <div class="form-group">
                                                    <label>Current Password: <span class="text-danger">*</span> </label>
                                                    <input type="password" class="form-control" name="password" placeholder="Your Current Password">
                                                </div>
                                                <div class="form-group">
                                                    <label>New Password: <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" name="npassword" placeholder="New Password">
                                                </div>
                                                <div class="form-group">
                                                    <label>Confirm Password: <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" name="ncpassword" placeholder="Confirm Password">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="method" value="_Pupdate_User">
                                                      <button id="_Pupdate_Btn" type="submit" class="btn btn-default">Change Password</button>
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
        <script type="text/javascript">
            $(document).on('click', '#_Pupdate_Btn', function () {
          var datastring = $('#_Pupdate').serialize();
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
              $('#_Pupdate').remove();
              if(response['type'] == 2)
              {
                setTimeout(function(){
                    window.location.href='logout';
                }, 5000);
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
              AlertClose_();
            }
          });
        });
        </script>
    </body>
</html>