<?php 
session_start();
include 'inc/config.php';
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
                                    <?php
                                    if(isset($_GET['id']))
                                    {
                                        $fetch=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_bank` where id='".$_GET['id']."'"));
                                        if($fetch !='')
                                        {
                                        ?>
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title clearfix"><strong>Create Bank</strong> <a class="pull-right btn btn-pink" href="manage-bank"><i class="fa fa-plus-circle"></i> Manage Bank</a></h4>
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
                                            <form id="eate-form" name="cate-form" method="post" enctype="multipart/form-data">
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Bank Name <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input required type="text" name="name" class="form-control" placeholder="Bank Name" value="<?php echo $fetch->name;?>">
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>IFSC <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input required type="text" name="ifsc" class="form-control" placeholder="IFSC" value="<?php echo $fetch->ifsc;?>">
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Account Number <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input required type="text" name="ac_num" class="form-control" placeholder="Account Number" value="<?php echo $fetch->ac_num;?>">
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Branch <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input required type="text" name="branch" class="form-control" placeholder="Branch" value="<?php echo $fetch->branch;?>">
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Status <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <label class="radio-inline"><input <?php echo $fetch->status==1 ? 'checked':''; ?> type="radio" name="pstatus" value="1">Enable</label>
                                                        <label class="radio-inline"><input <?php echo $fetch->status==2 ? 'checked':''; ?> type="radio" name="pstatus" value="2">Disable</label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <input type="hidden" name="bankid" value="<?php echo $_GET['id'];?>">
                                                <input type="hidden" name="method" value="edit-bank">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light edit-bank"> <i  class="mdi mdi-check-circle-outline"></i> Edit Bank </button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php 
                                    }
                                    else
                                    {
                                        ?>
                                         <div class="col-lg-12 alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <span><strong>Oops ! </strong> Error On this page.. Page is Not Found!</span>
                                        </div>
                                    <?php 
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <div class="col-lg-12 alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <span><strong>Oops ! </strong> Error On this page.. Page is Not Found!</span>
                                        </div>
                                    <?php 
                                    }
                                ?>
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
    <script src="assets/Ajax/ajax-bank.js"></script>
    </body>
</html>