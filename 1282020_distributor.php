<?php 
session_start();
include 'inc/config.php';
// $branch = isAdminDetails($conn);
isAdmin();

function leadingZeros($num,$numDigits) {
   return sprintf("%0".$numDigits."d",$num);
}

$input = new Input();
$custid =  $input->get('id');
$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `id` = '".$custid."' LIMIT 1"));

$disid = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(id) AS id FROM `ss_distributors` WHERE `deleted` = '1' AND `isAdmin` != '1' ORDER BY id DESC LIMIT 1 "));

if($disid)
{
    $val = $disid->id + 1;
    $unique   = "DI".leadingZeros($val, 3);
}
else
{
    $val = 1;
    $unique   = "DI".leadingZeros($val, 3);
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
                                            <h4 class="mt-0 header-title clearfix"><strong><?php echo isset($fetch->id) ? 'Edit' : 'Create'; ?> Distributor</strong> <a class="pull-right btn btn-pink" href="manage-distributors"><i class="fa fa-plus-circle"></i> Manage Distributor</a></h4>
                                            <hr>
                                            <div id="o-message"></div>
                                            <form id="form-data" name="form-data" method="post">
                                                <?php if($input->get("view")=="edit" && $input->get("id")!=''): ?>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-6">
                                                        <span>Status <span class="text-danger">*</span> </span><br>
                                                        <input <?php echo $fetch->status==1 ? 'checked' : ''; ?> type="radio" name="status" value="1"> Enable
                                                        <input <?php echo $fetch->status==2 ? 'checked' : ''; ?> type="radio" name="status" value="2"> Disable
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Company / Store Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="store" class="form-control" placeholder="Company Name" value="<?php echo isset($fetch->store)?$fetch->store:''; ?>">

                                                            <input  type="hidden" name="disno" class="form-control" id="disno" style="background-color: #fff;" readonly="readonly" placeholder="Customer NO" value="<?php echo isset($fetch->disno) ? $fetch->disno : $unique; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Contact / Owner Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="owner" class="form-control" placeholder="Contact / Owner Name" value="<?php echo isset($fetch->owner)?$fetch->owner:''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>State Name <span class="text-danger">*</span></label>
                                                            <select class="form-control js-select1-multi" name="state_id" id="state_name">
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
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>City Name</label>
                                                            <select class="form-control city_name[] js-select2-multi" name="city_id" id="city_name">
                                                                <option value="">Select City Name</option>
                                                                <?php
                                                                if(isset($fetch))
                                                                {
                                                                    $a = mysqli_query($conn, "SELECT * FROM ss_city WHERE  `state_id` = '".$fetch->state."' AND `published` = '1' AND `status` = '1' ORDER BY city_name ASC ");
                                                                    while($rowo = mysqli_fetch_array($a)) {
                                                                        $select='';
                                                                    if($rowo['id'] == $fetch->location) 
                                                                    {
                                                                        $select ="selected";
                                                                    }
                                                                    ?>     
                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['city_name']; ?></option>
                                                                <?php  } } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Address <span class="text-danger">*</span></label>
                                                            <textarea type="text" name="address" class="form-control" placeholder="Address"><?php echo isset($fetch->customeraddress)?$fetch->customeraddress:''; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Email ID as Login ID  <span class="text-danger">*</span></label>
                                                            <input type="text" <?php echo isset($fetch->email_id)?'readonly':''; ?> name="email" class="form-control" placeholder="Email ID" value="<?php echo isset($fetch->email_id)?$fetch->email_id:''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Mobile Number <span class="text-danger">*</span></label>
                                                            <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" value="<?php echo isset($fetch->mobile)?$fetch->mobile:''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Alternate Mobile Number </label>
                                                            <input type="text" name="amobile" class="form-control" placeholder="Alternate Mobile Number" value="<?php echo isset($fetch->amobile)?$fetch->amobile:''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Credit Limit <span class="text-danger">*</span></label>
                                                            <input type="text" name="credit_lmt" class="form-control" placeholder="Credit Limit" value="<?php echo isset($fetch->credit_lmt)?$fetch->credit_lmt:''; ?>">
                                                            <input type="hidden" name="pre_lmt" class="form-control" placeholder="Credit Limit" value="<?php echo isset($fetch->pre_lmt)?$fetch->pre_lmt:'1'; ?>">
                                                            <input type="hidden" name="avl_lmt" class="form-control" placeholder="Credit Limit" value="<?php echo isset($fetch->avl_lmt)?$fetch->avl_lmt:'1'; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Company GST No <span class="text-danger">*</span></label>
                                                            <input type="text" name="gst_no" class="form-control" placeholder="Company GST No" value="<?php echo isset($fetch->gst)?$fetch->gst:''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>PAN No</label>
                                                            <input type="text" name="pan_no" class="form-control" placeholder="PAN No" value="<?php echo isset($fetch->pan_no)?$fetch->pan_no:''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Dealer Allowance</label>
                                                            <input  type="text" name="d_allowance" class="form-control d_allowance" placeholder="Note: Enter Percentage Value" value="<?php echo isset($fetch->d_allowance) ? $fetch->d_allowance : '0' ?>">
                                                          	<code>Note: Enter Percentage Value</code>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Login Password <?php if($input->get("view")!="edit" && $input->get("id")==''): ?> <span class="text-danger">*</span> <?php endif; ?></label>
                                                            <input type="password" name="password" class="form-control" placeholder="Login Password" value="">
                                                        </div>
                                                    </div> -->

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Sales Excutive <span class="text-danger">*</span></label>
                                                            <select class="form-control js-select3-multi" name="excutive" id="excutive">
                                                                <option value="">Select Sales Excutive</option>
                                                                    <?php
                                                                    $a = mysqli_query($conn, "SELECT * FROM ss_sales_executive WHERE  published = '1' AND status = '1' ORDER BY name ASC ");
                                                                    while($rowo = mysqli_fetch_array($a)) {
                                                                        $select='';
                                                                    if($rowo['id'] == $fetch->excutive)
                                                                    {
                                                                        $select ="selected";
                                                                    }
                                                                    ?>     
                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['name']; ?></option>
                                                                    <?php  } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                  	<div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Type <span class="text-danger">*</span></label>
                                                            <select class="form-control js-select4-multi" name="permission" id="permission">
                                                                <option value="0">Select Type</option>
                                                                <option value="2">Distributor</option>  
                                                                <option value="3">Dealer</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="editid" value="<?php echo isset($fetch->id)?$fetch->id:''; ?>">
                                                <input type="hidden" name="method" value="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-customer"> 
                                                <input type="hidden" name="process" value="distributor"> 
                                                <button type="button" id="distributor-form" class="btn btn-danger waves-effect waves-light"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> Distributor </button>
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
        <script src="assets/Ajax/ajax-distributor.js"></script>
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
                    placeholder: "Select Sales Excutive Name"
                });
                
                $(".js-select4-multi").select2({
                    placeholder: "Select Type"
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