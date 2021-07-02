<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();

$id = isset($_GET['id'])?$_GET['id']:'0';

$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$id."' LIMIT 1"));

$pdtcount = mysqli_fetch_object(mysqli_query($conn, "SELECT id FROM `ss_items` ORDER BY id DESC LIMIT 1"));
 //echo "SELECT id FROM `ss_items` ORDER BY id DESC LIMIT 1";
function leadingZeros($num,$numDigits)
{
   return sprintf("%0".$numDigits."d",$num);
}
if($pdtcount)
{
    $dyid=$pdtcount->id + 1;
    $pcode ='YARAPDT-'.leadingZeros($dyid,4);
    // $bill_no = "NT-PUR".leadingZeros($value,6);
}
else
{
    $pcode ='YARAPDT-0001';
}
// echo $pcode;
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
        <!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'> -->
        <link href="assets/css/select2.min.css" rel="stylesheet" type="text/css">
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
                                            <h4 class="mt-0 header-title clearfix"><strong><?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> Item</strong> <a class="pull-right btn btn-pink" href="manage-items"><i class="fa fa-plus-circle"></i> Manage Item</a></h4>
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
                                                    <div class="col-md-4">
                                                        <span>Product Code <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="pcode" class="form-control" value="<?php echo isset($fetch->pcode) ? $fetch->pcode : $pcode ?>" readonly="">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>HSN Code <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="hsn" class="form-control" value="<?php echo isset($fetch->hsn) ? $fetch->hsn : '' ?>" placeholder="HSN Code">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Product Name <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="title" class="form-control" value="<?php echo isset($fetch->title) ? $fetch->title : '' ?>" placeholder="Product Name">
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-4">
                                                        <span>Description <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="description" class="form-control" value="<?php echo isset($fetch->description) ? $fetch->description : '' ?>" placeholder="Description">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Category <span class="text-danger">*</span> </span>
                                                        <select class="form-control category_id[] js-select3-multi" name="c_id" id="c_id">
                                                            <option value="">Select Category name</option>
                                                            <?php
                                                            $cateid =isset($fetch->cid)? $fetch->cid :'';
                                                                if($cateid !='')
                                                                {
                                                                    $catedetails=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_category` where  id='".$cateid."'"));
                                                                }
                                                            $a = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND  parentid = 0 ORDER BY title ASC ");
                                                            $i=0; while($rowo = mysqli_fetch_array($a)) { 
                                                            $b = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND  parentid = '".$rowo['id']."' ORDER BY title ASC");
                                                            $count = mysqli_num_rows($b);
                                                            ?>     
                                                            <option <?php echo isset($fetch->cid) && ($fetch->cid == $rowo['id']) ? 'selected' : ''; ?> value="<?php echo $rowo['id']; ?>"><?php echo $rowo['title']; ?></option>
                                                            <?php if($count>0){ ?>
                                                            <?php
                                                            $i++;
                                                            while($rows = mysqli_fetch_array($b)) { ?>     
                                                            <option <?php echo isset($fetch->cid) && ($fetch->cid == $rows['id']) ? 'selected' : ''; ?> value="<?php echo $rows['id']; ?>">--<?php echo $rows['title']; ?></option>
                                                            <?php } ?>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Barnd <span class="text-danger">*</span> </span>
                                                        <select class="form-control brand_id[] js-select5-multi" name="b_id" id="b_id">
                                                            <option value="">Select Barnd name</option>
                                                            <?php
                                                                $a = mysqli_query($conn, "SELECT * FROM ss_brands WHERE published = '1' AND status = '1' ORDER BY brand ASC ");
                                                                while($rowo = mysqli_fetch_array($a)) {
                                                                    $select='';
                                                                if($rowo['id'] == $fetch->brand) 
                                                                {
                                                                    $select ="selected";
                                                                }
                                                                ?>     
                                                                <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['brand']; ?></option>
                                                            <?php  } ?>
                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-4">
                                                        <span>Basic Allowance <span class="text-danger">*</span> </span>
                                                        <!-- <select class="form-control allowance_id[] js-select7-multi" name="allowance" id="allowance">
                                                            <option value="">Select Allowance name</option>
                                                            <?php
                                                                $a = mysqli_query($conn, "SELECT * FROM ss_allowance WHERE published = '1' AND status = '1' ORDER BY description ASC ");
                                                                while($rowo = mysqli_fetch_array($a)) {
                                                                    $select='';
                                                                if($rowo['id'] == $fetch->allowance) 
                                                                {
                                                                    $select ="selected";
                                                                }
                                                                ?>     
                                                                <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['description']; ?></option>
                                                            <?php  } ?>
                                                        </select> -->
                                                        <input  type="text" name="allowance" class="form-control allowance" placeholder="Basic Allowance" value="<?php echo isset($fetch->allowance) ? $fetch->allowance : '' ?>">
                                                        <code>Note: Enter Percentage Value</code>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>STA <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="sta" class="form-control sta" placeholder="STA" value="<?php echo isset($fetch->sta) ? $fetch->sta : '' ?>">
                                                        <code>Note: Enter Amount Value</code>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <span>GST <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="gst" class="form-control gst" placeholder="GST" value="<?php echo isset($fetch->gst) ? $fetch->gst : '' ?>">
                                                    </div>

                                                    <!-- <div class="col-md-3">
                                                        <span>MRP  <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="price" class="form-control" placeholder="MRP " value="<?php echo isset($fetch->price) ? $fetch->price : '' ?>">
                                                    </div> -->
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-4">
                                                        <span>MRP <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="price" class="form-control price" placeholder="Dealer Price" value="<?php echo isset($fetch->price) ? $fetch->price : '' ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Dealer Price <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="oprice" class="form-control oprice" placeholder="Dealer Price" value="<?php echo isset($fetch->oprice) ? $fetch->oprice : '' ?>">
                                                    </div>

                                                    <?php if(isset($_GET['id'])): ?>
                                                    <div class="col-md-4">
                                                        <span>Stock <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="stock" class="form-control stock" placeholder="Stock" value="<?php echo isset($fetch->extra) ? $fetch->extra : '' ?>">
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
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

                                                <input type="hidden" name="method" value="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-item">

                                                <button type="submit" id="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" class="btn btn-danger waves-effect waves-light <?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-item"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> Item </button>
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
        <script src="assets/Ajax/ajax-item.js"></script>
        <!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script> -->
        <script src="assets/js/select2.full.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".js-select3-multi").select2({
                    placeholder: "Select Category Name"
                });

                $(".js-select5-multi").select2({
                    placeholder: "Select Barnd Name"
                });

                $(".js-select7-multi").select2({
                    placeholder: "Select Allowance Name"
                });
            });
        </script>
    </body>
</html>