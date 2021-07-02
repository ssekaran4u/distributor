<?php 

session_start();

include 'inc/config.php';

isAdmin();

$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_store_information` WHERE `id` = 1 LIMIT 1"));

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

                                            <h4 class="mt-0 header-title clearfix"><strong>Store Informations</strong></h4>

                                            <hr>

                                            <div id="o-message"></div>

                                            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">

                                                <li class="nav-item">

                                                    <a class="nav-link active" data-toggle="tab" href="#ds1" role="tab">General</a>

                                                </li>

                                            </ul>

                                            <form id="form" method="post" enctype="multipart/form-data">

                                                <div class="tab-content">

                                                    <div class="tab-pane active p-3 col-md-12" id="ds1" role="tabpanel">

                                                        <div class="row clearfix mb20">

                                                            <div class="col-md-2">

                                                                <span>Store Name <span class="text-danger">*</span> </span>

                                                            </div>

                                                            <div class="col-md-10">

                                                                <input type="text" name="name" placeholder="Store Name" class="form-control" value="<?php echo isset($fetch->name) ? $fetch->name : ''; ?>">

                                                            </div>

                                                        </div>

                                                        <div class="row clearfix mb20">

                                                            <div class="col-md-2">

                                                                <span>Store Owner <span class="text-danger">*</span> </span>

                                                            </div>

                                                            <div class="col-md-10">

                                                                <input type="text" name="owner" placeholder="Store Owner" class="form-control" value="<?php echo isset($fetch->owner) ? $fetch->owner : ''; ?>">

                                                            </div>

                                                        </div>

                                                        <div class="row clearfix mb20">

                                                            <div class="col-md-2">

                                                                <span>State <span class="text-danger">*</span> </span>

                                                            </div>

                                                            <div class="col-md-10">

                                                                <input type="text" name="state" placeholder="State" class="form-control" value="<?php echo isset($fetch->state) ? $fetch->state : ''; ?>">

                                                            </div>

                                                        </div>

                                                        <div class="row clearfix mb20">

                                                            <div class="col-md-2">

                                                                <span>City <span class="text-danger">*</span> </span>

                                                            </div>

                                                            <div class="col-md-10">

                                                                <input type="text" name="city" placeholder="City" class="form-control" value="<?php echo isset($fetch->city) ? $fetch->city : ''; ?>">

                                                            </div>

                                                        </div>

                                                        <div class="row clearfix mb20">

                                                            <div class="col-md-2">

                                                                <span>Address <span class="text-danger">*</span> </span>

                                                            </div>

                                                            <div class="col-md-10">

                                                                <textarea name="address" class="form-control" placeholder="Address"><?php echo isset($fetch->address) ? $fetch->address : ''; ?></textarea>

                                                            </div>

                                                        </div>

                                                        <div class="row clearfix mb20">

                                                            <div class="col-md-2">

                                                                <span>Email <span class="text-danger">*</span> </span>

                                                            </div>

                                                            <div class="col-md-10">

                                                                <input type="text" name="email" placeholder="Email" class="form-control" value="<?php echo isset($fetch->email) ? $fetch->email : ''; ?>">

                                                            </div>

                                                        </div>

                                                        <div class="row clearfix mb20">

                                                            <div class="col-md-2">

                                                                <span>Telephone Number <span class="text-danger">*</span> </span>

                                                            </div>

                                                            <div class="col-md-10">

                                                                <input type="text" name="mobile" placeholder="Telephone Number" class="form-control" value="<?php echo isset($fetch->mobile) ? $fetch->mobile : ''; ?>">

                                                            </div>

                                                        </div>

                                                        <div class="row clearfix mb20">

                                                            <div class="col-md-2">

                                                                <span>Store Logo <span class="text-danger">*</span> </span>

                                                            </div>

                                                            <div class="col-md-8">

                                                                <?php if(isset($fetch->logo)) : ?>

                                                                <label class="file-upload btn btn-warning">

                                                                    Browse for file ... 

                                                                    <input name="logo" type="file" />

                                                                </label>

                                                                <br>

                                                                <img src="https://images.pexels.com/photos/257840/pexels-photo-257840.jpeg" alt class="img-responsive" style="max-width: 200px;">

                                                                <?php else: ?>

                                                                <label class="file-upload btn btn-warning">

                                                                    Browse for file ... 

                                                                    <input name="logo" type="file" /> 

                                                                </label>

                                                                <?php endif; ?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <hr>

                                                <button id="store"  type="submit" class="btn btn-danger waves-effect waves-light"> <i  class="mdi mdi-check-circle-outline"></i> Edit Settings</button>

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

                function gototop() {

                    $('html, body').animate({

                      scrollTop: 0

                    }, 1000);

                }

                $(document).ready(function (e) {

                  $('#form').on('submit', (function (e) {

                    e.preventDefault();

                    $.ajax({

                      url: 'inc/store-information.php',

                      type: 'POST',

                      data: new FormData(this),

                      contentType: false,

                      cache: false,

                      processData: false,

                      dataType: 'JSON',

                    }).done(function (response)

                    {

                        if(response['status']== true)

                        {

                            $('#o-message').removeClass('alert alert-danger').addClass('alert alert-success');

                            $('#o-message').html(response['message']);

                            gototop();

                        }

                        if(response['status']== false)

                        {

                            if($.isArray(response['message']))

                            {

                                $("#o-message").empty();

                                $('#o-message').removeClass('alert alert-success').addClass('alert alert-danger');

                                $.each(response['message'], function(index, value)

                                {

                                    $("#o-message").append(value + '<br>');

                                });

                                gototop();

                            }

                            else

                            {

                                $('#o-message').removeClass('alert alert-success').addClass('alert alert-danger');

                                $("#o-message").html(response['message']);

                                gototop();

                            }

                        }

                    });

                  }));

                });

            });

        </script>

    </body>

</html>