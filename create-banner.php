<?php 
session_start();

include 'inc/config.php';

// isAdmin();

?>

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

        <title><?php echo $config["site_name"]; ?> - Admin Dashboard</title>

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">

        <link href="assets/css/style.css" rel="stylesheet" type="text/css">

        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>

    </head>

    <body class="fixed-left">
        <div id="wrapper">
        <?php include 'includes/side_menu.php';  ?>

        
            <!-- <span class="loading"></span> -->
            <div class="content-page">

                <div class="content">

                    <?php include 'includes/top_notification.php';  ?>

                    <div class="page-content-wrapper">

                        <div class="container-fluid">

                            <div class="row">

                                <div class="col-lg-12">

                                    <div class="card m-b-30">

                                        <div class="card-body">

                                            <h4 class="mt-0 header-title clearfix"><strong>Create Banner</strong> <a class="pull-right btn btn-pink" href="manage-banner"><i class="fa fa-plus-circle"></i> Manage Banner</a></h4>
                                            <div id="o-message"></div>
                                            <div class="alert alert-danger hide errormsg">
                                            </div>
                                            <hr>
                                             <?php 
                                            if(isset($_GET['id']))
                                            {
                                                $fetch=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_slider` where id='".$_GET['id']."'"));
                                                //var_dump($fetch);die;
                                             }
                                            ?>
                                            <form id="banner-form" name="banner-form" method="post" enctype="multipart/form-data">
                                            <div class="row clearfix mb20">

                                                <div class="col-md-2">

                                                    <span>Title</span>

                                                </div>

                                                <div class="col-md-10">

                                                    <input type="text" name="title" value="<?php echo isset($fetch->title)?$fetch->title:'';?>" placeholder="Title" class="form-control">

                                                </div>

                                            </div>

                                            <div class="row clearfix mb20">

                                                <div class="col-md-2">

                                                    <span>Description</span>

                                                </div>

                                                <div class="col-md-10">

                                                    <textarea class="form-control" name="description" placeholder="Description"><?php echo isset($fetch->description)?$fetch->description:'';?></textarea>

                                                </div>

                                            </div>


                                            <div class="row clearfix mb20">

                                                <div class="col-md-2">

                                                    <span>Image <span class="text-danger">*</span> </span>

                                                </div>
                                                <style type="text/css">
                                                .img-upload
                                                {
                                                width: 143px;
                                                height: 100px;
                                                padding: 7px;
                                                }
                                            </style>
                                                <div class="col-md-2">

                                                            <?php $img= isset($fetch->image)?'images/banner/'.$fetch->image:'images/document-image.png';?>
                                                            <img  src="<?php echo $img;?>" class="img-upload">
                                                            <label class="file-upload btn btn-warning">
                                                                Browse for file ... 
                                                                <input id="file-input" name="image" type="file" class="file_input" data-id="1"/>
                                                            </label>
                                                        </div>

                                            </div>
                                            <?php 
                                            if(isset($_GET['type']))
                                            {
                                            ?>
                                            <div class="row clearfix mb20">

                                                <div class="col-md-2">

                                                    <span>Status <span class="text-danger">*</span> </span>

                                                </div>

                                                <div class="col-md-10">

                                                    <label class="radio-inline"><input <?php echo $fetch->status==1 ? 'checked':''; ?> type="radio" name="pstatus" value="1">Enable</label>

                                                    <label class="radio-inline"><input <?php echo $fetch->status==0 ? 'checked':''; ?> type="radio" name="pstatus" value="0">Disable</label>

                                                </div>

                                            </div>

                                        <?php } ?>
                                             <input type="hidden" class="method" name="method" value="<?php echo isset($_GET['type'])?$_GET['type']:'insert';?>">
                                            <hr>
                                            <input type="hidden" class="id" name="id" value="<?php echo isset($_GET['id'])?$_GET['id']:'';?>">
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light banner-form"> <i  class="mdi mdi-check-circle-outline"></i><?php echo isset($_GET['type'])?'Update':'Add';?> Banner</button>
                                            <!-- <button type="submit" class="btn btn-danger waves-effect waves-light "> <i  class="mdi mdi-check-circle-outline"></i> Add Banner </button> -->
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

        <script src="assets/Ajax/ajax-user.js"></script>

        <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>

        <script type="text/javascript">
            $(document).ready(function() 
            {  
                $(".js-select3-multi").select2({
                    placeholder: "Select Zone"
                });
            });
        </script>

    </body>

</html>