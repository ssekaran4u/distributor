<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();

$id = isset($_GET['id'])?$_GET['id']:'0';

$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_service_stk` WHERE `id` = '".$id."' LIMIT 1"));

if(!empty($id))
{
    $result_1 = 'disabled="disabled"';
}
else
{
    $result_1 = '';
}

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
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>
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
                                            <h4 class="mt-0 header-title clearfix"><strong><?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> Serice</strong> <a class="pull-right btn btn-pink" href="manage-service"><i class="fa fa-plus-circle"></i> Manage Service</a></h4>
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

                                            <form id="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" name="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" method="post" enctype="multipart/form-data">
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-3">
                                                        <span>Service Number <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="service_no" class="form-control service_no" id="service_no" placeholder="Service Number" value="<?php echo isset($fetch->service_no) ? $fetch->service_no : '' ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Brand Name<span class="text-danger">*</span> </span>
                                                        <select class="form-control brand_name[] js-select9-multi" name="b_id" id="b_id" <?php echo $result_1; ?>>
                                                            <option value="">Select Brand name</option>
                                                            <?php
                                                            if(isset($id))
                                                            {
                                                                $a = mysqli_query($conn, "SELECT * FROM ss_brands WHERE published = 1 AND status = 1 ORDER BY id ASC ");
                                                                while($rowo = mysqli_fetch_array($a)) {
                                                                $select='';
                                                                if($rowo['id'] == $fetch->b_id) 
                                                                {
                                                                    $select ="selected";
                                                                }
                                                                ?>
                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['brand']; ?></option>
                                                                <?php
                                                                }
                                                            }
                                                            ?>     
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span>Category <span class="text-danger">*</span> </span>
                                                        <select class="form-control category_id[] js-select3-multi" name="c_id" id="c_id" <?php echo $result_1; ?>>
                                                            <option value="">Select Category name</option>
                                                            <?php
                                                            if(isset($id)) {
                                                            $cateid =isset($fetch->c_id)? $fetch->c_id :'';
                                                                if($cateid !='')
                                                                {
                                                                    $catedetails=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_category` where  id='".$cateid."'"));
                                                                }
                                                            $a = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND  parentid = 0 ORDER BY title ASC ");
                                                            $i=0; while($rowo = mysqli_fetch_array($a)) { 
                                                            $b = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND  parentid = '".$rowo['id']."' ORDER BY title ASC");
                                                            $count = mysqli_num_rows($b);
                                                            ?>     
                                                            <option <?php echo isset($fetch->c_id) && ($fetch->c_id == $rowo['id']) ? 'selected' : ''; ?> value="<?php echo $rowo['id']; ?>"><?php echo $rowo['title']; ?></option>
                                                            <?php if($count>0){ ?>
                                                            <?php
                                                            $i++;
                                                            while($rows = mysqli_fetch_array($b)) { ?>     
                                                            <option <?php echo isset($fetch->c_id) && ($fetch->c_id == $rows['id']) ? 'selected' : ''; ?> value="<?php echo $rows['id']; ?>">--<?php echo $rows['title']; ?></option>
                                                            <?php } ?>
                                                            <?php } } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-5">
                                                        <span>Product Name <span class="text-danger">*</span> </span>
                                                        <select class="form-control product_id[] js-select5-multi" name="p_id" id="p_id" <?php echo $result_1; ?>>
                                                            <option value="">Select product name</option>
                                                            <?php
                                                            if(isset($id)) {
                                                            $a = mysqli_query($conn, "SELECT * FROM ss_items WHERE published = '1' AND status = '1' ORDER BY id ASC ");
                                                            while($rowo = mysqli_fetch_array($a)) {
                                                                $select='';
                                                                if($rowo['id'] == $fetch->p_id) 
                                                                {
                                                                    $select ="selected";
                                                                }
                                                            ?>     
                                                            <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['title']; ?></option>
                                                            <?php  } } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Serial No <span class="text-danger">*</span> </span>
                                                        <select class="form-control service_code[] js-select7-multi" name="service_code" id="service_code">
                                                            <option value="">Select product name</option>
                                                            <?php
                                                            if(isset($id)) {
                                                            $a = mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE `product_id` = '".$fetch->p_id."' AND published = '1' AND status = '1' ORDER BY id ASC ");
                                                            while($rowo = mysqli_fetch_array($a)) {
                                                                $select='';
                                                                if($rowo['id'] == $fetch->service_code) 
                                                                {
                                                                    $select ="selected";
                                                                }
                                                            ?>     
                                                            <option value="<?php echo $rowo['id']; ?>" <?php echo $select; ?>><?php echo $rowo['code']; ?></option>
                                                            <?php  } } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Date <span class="text-danger">*</span> </span>
                                                        <input  type="date" name="date" class="form-control date" placeholder="Date" value="<?php echo isset($fetch->date) ? $fetch->date : '' ?>">
                                                    </div>
                                                </div>
                                                <?php if(isset($_GET['id'])): ?>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-4">
                                                        <span>Return Code <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="return_code" class="form-control return_code" placeholder="Return Code" value="<?php echo isset($fetch->return_code) ? $fetch->return_code : '' ?>">
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if(isset($_GET['id'])): ?>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-6">
                                                        <span>Status <span class="text-danger">*</span> </span><br>
                                                        <input <?php echo $fetch->status==1 ? 'checked' : ''; ?> type="radio" name="pstatus" value="1"> Enable
                                                        <input <?php echo $fetch->status==2 ? 'checked' : ''; ?> type="radio" name="pstatus" value="2"> Disable
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <hr>
                                                <input type="hidden" class="id" name="id" value="<?php echo isset($_GET['id'])?$_GET['id']:'';?>">

                                                <input type="hidden" name="method" value="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-service">

                                                <button type="submit" id="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" class="btn btn-danger waves-effect waves-light <?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-item"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> Service </button>
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
        <script src="assets/Ajax/ajax-service.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".js-select3-multi").select2({
                    placeholder: "Select Category Name"
                });

                $(".js-select5-multi").select2({
                    placeholder: "Select Product Name"
                });

                $(".js-select7-multi").select2({
                    placeholder: "Select Serail No"
                });

                $(".js-select9-multi").select2({
                    placeholder: "Select Brand Number",
                    allowClear: true
                });

                var service_no = $('#service_no').val();
                if(service_no =='')
                {
                   loadinv(); 
                }

                function loadinv()
                {
                    $.ajax({
                        type: "POST",
                        url: "inc/select_service_no.php",
                        data: {}
                        }).done(function( msg ) {
                            $('#service_no').val(msg);
                            // alert(msg);
                        });
                }

                $(document).on('change','#c_id', function () {
                    var brand_id = $('#b_id').val();  
                    var c_id = $('#c_id').val();  
                    if(brand_id != '' && c_id != '')    
                    {
                        $.ajax({
                        type: "POST",
                        url: "inc/select_category.php",
                        data: {"brand_id":brand_id ,"c_id":c_id}
                        }).done(function( msg ) {
                            var dataArr = msg.split('#');
                            $('#p_id option').remove();

                            $.each(dataArr, function(i,element){
                                if(dataArr[i]!=""){
                                    var dataArr2 = dataArr[i].split('~');
                                    $('#p_id').append("<option value='"+dataArr2[0]+"'>"+dataArr2[1]+"</option>");
                                }
                            });
                            loadOrderBox();
                            loadCreatePoint();
                        });
                    }
                });

                $(document).on('change','#p_id', function () {
                    var p_id = $('#p_id').val();  
                    if(p_id != '')    
                    {
                        $.ajax({
                        type: "POST",
                        url: "inc/select_service_code.php",
                        data: {"p_id":p_id}
                        }).done(function( msg ) {
                            var dataArr = msg.split('#');
                            $('#service_code option').remove();

                            $.each(dataArr, function(i,element){
                                if(dataArr[i]!=""){
                                    var dataArr2 = dataArr[i].split('~');
                                    $('#service_code').append("<option value='"+dataArr2[0]+"'>"+dataArr2[1]+"</option>");
                                }
                            });
                            loadOrderBox();
                            loadCreatePoint();
                        });
                    }
                });
            });
        </script>
    </body>
</html>