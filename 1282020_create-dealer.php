<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();

function leadingZeros($num,$numDigits) {
   return sprintf("%0".$numDigits."d",$num);
}

$custid = isset($_GET['id'])?$_GET['id']:'0';

$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `id` = '".$custid."' LIMIT 1"));

$cusid = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(id) AS id FROM `ss_customers` WHERE `published` = '1' ORDER BY id DESC LIMIT 1 "));

if($cusid)
{
    $val = $cusid->id + 1;
    $unique   = "DD".leadingZeros($val, 3);
}
else
{
    $val = 1;
    $unique   = "DD".leadingZeros($val, 3);
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
        <!-- <link href="assets/css/bootstrap-select.css" rel="stylesheet" type="text/css"> -->
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
                                            <h4 class="mt-0 header-title clearfix"><strong><?php echo isset($fetch->id) ? 'Edit' : 'Create'; ?> Dealer</strong> <a class="pull-right btn btn-pink" href="manage-dealer"><i class="fa fa-plus-circle"></i> Manage Dealer</a></h4>
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
                                            <form id="<?php echo isset($fetch->id) ? 'edit' : 'customer'; ?>-form" name="<?php echo isset($fetch->id) ? 'edit' : 'Customer'; ?>-form" method="post">
                                                
                                                <div class="row clearfix mb20">
                                                    <?php 
                                                        if($_SESSION['type'] ==1)
                                                        {
                                                            $len='4';
                                                    ?>
                                                    <div class="col-md-<?php echo $len;?>">
                                                        <span>Distributor<span class="text-danger">*</span> </span>
                                                        <select class="form-control dist_name js-select3-multi" name="dist_name" id="dist_name">
                                                            <option value="">Select Distributor</option>
                                                            <?php
                                                            
                                                            $a = mysqli_query($conn, "SELECT * FROM ss_distributors WHERE  deleted = 1  AND status = 1 ORDER BY id ASC");
                                                            while($rowo = mysqli_fetch_array($a)) {
                                                            $select='';
                                                            if($rowo['id'] == $fetch->userid) 
                                                            {
                                                                $select ="selected";
                                                            }

                                                            ?>     
                                                            <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['store'].' - '.$rowo['owner']; ?></option>
                                                            <?php  } ?>
                                                        </select>
                                                    </div>
                                                    <?php 
                                                        }
                                                        else
                                                        {
                                                            $len='3';
                                                            ?>
                                                            <input type="hidden" name="dist_name" class="form-control" value="">
                                                            <?php
                                                        }
                                                    ?>
                                                    <div class="col-md-<?php echo $len;?>">
                                                        <span>Name <span class="text-danger">*</span> </span>
                                                        <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo isset($fetch->name)?$fetch->name:''; ?>">
                                                        <input  type="hidden" name="userno" class="form-control" id="userno" style="background-color: #fff;" readonly="readonly" placeholder="Customer NO" value="<?php echo isset($fetch->userno) ? $fetch->userno : $unique; ?>">
                                                    </div>
                                                    <div class="col-md-<?php echo $len;?>">
                                                        <span>Mail <span class="text-danger">*</span> </span>
                                                        <input type="text" name="email" class="form-control" placeholder="Mail" value="<?php echo isset($fetch->email)?$fetch->email:''; ?>">
                                                    </div>
                                                    <div class="col-md-<?php echo $len;?>">
                                                        <span>Mobile Number <span class="text-danger">*</span> </span>
                                                        <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" value="<?php echo isset($fetch->mobile)?$fetch->mobile:''; ?>">
                                                    </div>
                                                    <div class="col-md-<?php echo $len;?>">
                                                        <span>Credit Limit <span class="text-danger">*</span> </span>
                                                        <input type="text" name="credit_lmt" class="form-control" placeholder="Credit Limit" value="<?php echo isset($fetch->credit_lmt)?$fetch->credit_lmt:''; ?>">
                                                        <input type="hidden" name="pre_lmt" class="form-control" placeholder="Credit Limit" value="<?php echo isset($fetch->pre_lmt)?$fetch->pre_lmt:''; ?>">
                                                        <input type="hidden" name="avl_lmt" class="form-control" placeholder="Credit Limit" value="<?php echo isset($fetch->avl_lmt)?$fetch->avl_lmt:''; ?>">
                                                    </div>
                                                </div><hr>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-3">
                                                        <span>Company Name <span class="text-danger">*</span> </span>
                                                        <input type="text" name="cname" class="form-control" placeholder="Company Name" value="<?php echo isset($fetch->cname)?$fetch->cname:''; ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Company GST No </span>
                                                        <input type="text" name="gst_no" class="form-control" placeholder="Company GST No" value="<?php echo isset($fetch->gst_no)?$fetch->gst_no:''; ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Alternate Mobile Number </span>
                                                        <input type="text" name="almobile" class="form-control" placeholder="Alternate Mobile Number" value="<?php echo isset($fetch->phone)?$fetch->phone:''; ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Dealer Allowance <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="d_allowance" class="form-control d_allowance" placeholder="Dealer Allowance" value="<?php echo isset($fetch->d_allowance) ? $fetch->d_allowance : '' ?>">
                                                        <code>Note: Enter Percentage Value</code>
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-12">
                                                        <span>Address <span class="text-danger">*</span> </span>
                                                        <textarea type="text" name="address" class="form-control" placeholder="Address"><?php echo isset($fetch->address)?$fetch->address:''; ?></textarea>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-3">
                                                        <span>State Name <span class="text-danger">*</span> </span>
                                                        <select class="form-control state_name[] js-select1-multi" name="state_id" id="state_name">
                                                            <option value="">Select State Name</option>
                                                                <?php
                                                                    $a = mysqli_query($conn, "SELECT * FROM ss_state WHERE country_id = '1' AND published = '1' AND status = '1' ORDER BY state_name ASC ");
                                                                    while($rowo = mysqli_fetch_array($a)) {
                                                                        $select='';
                                                                    if($rowo['id'] == $fetch->state) 
                                                                    {
                                                                        $select ="selected";
                                                                    }
                                                                    ?>     
                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['state_name']; ?></option>
                                                                <?php  } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>City Name </span>
                                                        <select class="form-control city_name[] js-select2-multi" name="city_id" id="city_name">
                                                            <option value="">Select City Name</option>
                                                                <?php
                                                                    $a = mysqli_query($conn, "SELECT * FROM ss_city WHERE published = '1' AND status = '1' ORDER BY city_name ASC ");
                                                                    while($rowo = mysqli_fetch_array($a)) {
                                                                        $select='';
                                                                    if($rowo['id'] == $fetch->city) 
                                                                    {
                                                                        $select ="selected";
                                                                    }
                                                                    ?>     
                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['city_name']; ?></option>
                                                                <?php  } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Pincode <span class="text-danger">*</span> </span>
                                                        <input type="text" name="pincode" class="form-control" placeholder="Pincode" maxlength="6" value="<?php echo isset($fetch->pincode)?$fetch->pincode:''; ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Sales Excutive <span class="text-danger">*</span></label>
                                                            <select class="form-control js-select4-multi" name="excutive" id="excutive">
                                                                <option value="">Select Sales Excutive</option>
                                                                    <?php
                                                                    $a = mysqli_query($conn, "SELECT * FROM ss_sales_executive WHERE  published = '1' AND status = '1' ORDER BY name ASC ");
                                                                    while($rowo = mysqli_fetch_array($a)) {
                                                                        $select='';
                                                                    if($rowo['id'] == isset($fetch->excutive)) 
                                                                    {
                                                                        $select ="selected";
                                                                    }
                                                                    ?>     
                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['name']; ?></option>
                                                                    <?php  } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php 
                                                    if($_SESSION['type'] ==1):
                                                        if($custid):?>
                                                    <div class="row clearfix mb20">

                                                        <div class="col-md-2">

                                                            <span>Status <span class="text-danger">*</span> </span>

                                                        </div>

                                                        <div class="col-md-10">

                                                            <label class="radio-inline"><input <?php echo $fetch->status==1 ? 'checked':''; ?> type="radio" name="pstatus" value="1">Enable</label>

                                                            <label class="radio-inline"><input <?php echo $fetch->status==0 ? 'checked':''; ?> type="radio" name="pstatus" value="0">Disable</label>

                                                        </div>

                                                    </div>
                                                <?php 
                                                        endif;
                                                    endif; 
                                                ?>
                                                <hr>
                                                <input type="hidden" name="cusid" value="<?php echo isset($fetch->id)?$fetch->id:''; ?>">
                                                <input type="hidden" name="method" value="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-customer"> 
                                                <button type="submit" id="<?php echo isset($fetch->id) ? 'edit' : 'customer'; ?>-form" class="btn btn-danger waves-effect waves-light <?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-customer"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> Dealer </button>
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
        <script src="assets/js/bootstrap.js"></script>
        <!-- <script src="assets/js/bootstrap-select.js"></script> -->
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/js/file-upload.js"></script>
        <script src="assets/js/app.js"></script>
        <script src="assets/Ajax/ajax-customer.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".js-select1-multi").select2({
                    placeholder: "Select State Name"
                });

                $(".js-select2-multi").select2({
                    placeholder: "Select City Name"
                });
                $(".js-select3-multi").select2({
                    placeholder: "Select Distributor Name"
                });
                $(".js-select4-multi").select2({
                    placeholder: "Select Sales Excutive"
                });

                $('#state_name').change(function(){         
                    var state_id = $('#state_name').val();  
                    $.ajax({
                    type: "POST",
                    url: "inc/select_city.php",
                    data: {"state_id":state_id}
                    }).done(function( msg ) {
                        var dataArr = msg.split('#');
                        $('#city_name option').remove();

                        $.each(dataArr, function(i,element){
                            if(dataArr[i]!=""){
                                var dataArr2 = dataArr[i].split('~');
                                $('#city_name').append("<option value='"+dataArr2[0]+"'>"+dataArr2[1]+"</option>");
                            }
                        });
                    });
                });
                
            });
        </script>
    </body>
</html>