<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();

$citid = isset($_GET['id'])?$_GET['id']:'0';

$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_city` WHERE `id` = '".$citid."' LIMIT 1"));


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
                                            <h4 class="mt-0 header-title clearfix"><strong><?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> City</strong> <a class="pull-right btn btn-pink" href="manage-city"><i class="fa fa-plus-circle"></i> Manage City</a></h4>
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
                                                    <div class="col-md-2">
                                                        <span>State Name <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                    <select class="form-control state_name" name="state_name">
                                                        <option value="">Select State Name</option>
                                                            <?php
                                                            $a = mysqli_query($conn, "SELECT * FROM ss_state WHERE country_id = '1' AND published = '1' AND status = '1' ORDER BY state_name ASC ");
                                                            while($rowo = mysqli_fetch_array($a)) {
                                                                $select='';
                                                            if($rowo['id'] == $fetch->state_id) 
                                                            {
                                                                $select ="selected";
                                                            }
                                                            ?>     
                                                            <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['state_name']; ?></option>
                                                            <?php  } ?>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>City Name <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" name="name" class="form-control" placeholder="City Name" value="<?php echo isset($fetch->city_name) ? $fetch->city_name : ''; ?>">
                                                    </div>
                                                </div>
                                                <?php if($citid): ?>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Status <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input <?php echo $fetch->status==1 ? 'checked' : ''; ?> type="radio" name="pstatus" value="1"> Enable
                                                        <input <?php echo $fetch->status==2 ? 'checked' : ''; ?> type="radio" name="pstatus" value="2"> Disable
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <hr>
                                                <!-- <input type="hidden" name="method" value="add-state">  -->
                                                <input type="hidden" name="cityid" value="<?php echo $citid; ?>">
                                                <input type="hidden" name="method" value="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-city">
                                                <button type="submit" id="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" class="btn btn-danger waves-effect waves-light <?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-city"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> City </button>
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
        <script src="assets/Ajax/ajax-city.js"></script>
    </body>
</html>