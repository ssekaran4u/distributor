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
                                        $fetch=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_event` where id='".$_GET['id']."'"));
                                        if($fetch !='')
                                        {
                                        ?>
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title clearfix"><strong>Create Event</strong> <a class="pull-right btn btn-pink" href="manage-event"><i class="fa fa-plus-circle"></i> Manage Event</a></h4>
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
                                            <form id="eate-form" name="event-form" method="post" enctype="multipart/form-data">
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Event Name <span class="text-danger">*</span> </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input required type="text" name="title" class="form-control" placeholder="Event Name" value="<?php echo $fetch->title;?>">
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Description <span class="text-danger">*</span>  </span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control desc kudu" placeholder="Event Description" name="description"><?php echo $fetch->description; ?></textarea>
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
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-2">
                                                        <span>Event Image</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Image sholud be 500x500</label>
                                                        <?php if($fetch->image): ?>
                                                         <img src="../images/event/<?php echo $fetch->image; ?>" class="img-upload2">
                                                         <?php else: ?>
                                                            <img src="images/document-image.png" class="img-upload2">
                                                         <?php  endif; ?>
                                                        <label class="file-upload btn btn-warning">
                                                            Browse for file ... 
                                                            <input id="file-input2" name="image" type="file" class="file_input" data-id="2" />
                                                        </label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <input type="hidden" name="eventid" value="<?php echo $_GET['id'];?>">
                                                <input type="hidden" name="method" value="edit-event">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light edit-event"> <i  class="mdi mdi-check-circle-outline"></i> Edit Event </button>
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
    <script src="assets/Ajax/ajax-event.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/kudu/kudu.css">
    <script type="text/javascript" src="assets/kudu/kudu.js"></script>
    <script type="text/javascript" src="assets/kudu/kudu.init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    </body>
</html>