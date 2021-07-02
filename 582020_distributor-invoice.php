<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();

function leadingZeros($num,$numDigits) {
   return sprintf("%0".$numDigits."d",$num);
}

$id = isset($_GET['id'])?$_GET['id']:'0';

$auto_id = isset($_GET['auto_id'])?$_GET['auto_id']:'0';

$result = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_inv_details` WHERE `auto_id` = '".$auto_id."' LIMIT 1"));

if($auto_id)
{
    $product = mysqli_fetch_object(mysqli_query($conn, "SELECT extra FROM `ss_items` WHERE `id` = '".$result->pid."' LIMIT 1"));
}

$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_invoice` WHERE `id` = '".$id."' LIMIT 1"));

$inv_no = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(id) AS id FROM `ss_distributor_invoice` WHERE published = '1' AND status = '1'"));

$inv_re = $inv_no->id + 1;

$so_no   = "HS".leadingZeros($inv_re, 5);

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
        <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>
        <style type="text/css">
            .tbl_hide
            {
                display: none;
            }
        </style>
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
                                            <h4 class="mt-0 header-title clearfix"><strong>Create Invoice</strong> <a class="pull-right btn btn-pink" href="distributor-invoice-list"><i class="fa fa-plus-circle"></i> Manage Invoice</a></h4>
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
                                            <form id="add-form" name="add-form" method="post" enctype="multipart/form-data">
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-3">
                                                        <span>Invoice No <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="so_no" class="form-control" id="so_no" style="background-color: #fff;" readonly="readonly" placeholder="Invoice No" value="<?php echo isset($fetch->so_no) ? $fetch->so_no : ''; ?>">

                                                         <!--<input  type="text" name="so_no" class="form-control" id="so_no" style="background-color: #fff;" readonly="readonly" placeholder="Invoice No" value="YARA-DIST-00016"> -->

                                                        <input  type="hidden" name="invno" class="form-control" id="invno" value="<?php echo isset($fetch->so_no) ? $fetch->so_no : ''; ?>">
                                                        
                                                        <input  type="text" name="brand_id" class="form-control" id="brand_id" style="background-color: #fff; display: none;" readonly="readonly" placeholder="Brand No" value="<?php echo isset($fetch->brand_id) ? $fetch->brand_id : ''; ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Distributor Name <span class="text-danger">*</span> </span>
                                                        <select class="form-control customer_name[] js-select1-multi" name="customer_id" id="customer_id">
                                                            <option value="">Select Distributor name</option>
                                                            <?php
                                                            
                                                            $a = mysqli_query($conn, "SELECT * FROM ss_distributors WHERE permission !=1 AND deleted = 1  AND status = 1 ORDER BY id ASC");
                                                            while($rowo = mysqli_fetch_array($a)) {
                                                            $select='';
                                                            if($rowo['id'] == $fetch->customer_id) 
                                                            {
                                                                $select ="selected";
                                                            }

                                                            ?>     
                                                            <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['store'] .' - '. $rowo['owner']; ?></option>
                                                            <?php  } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Mobile Number <span class="text-danger">*</span> </span>
                                                        <input  type="text" style="background-color: #fff;" readonly="readonly" name="mobile" id="mobile" class="form-control" placeholder="Mobile NUmber">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Credit limit<span class="text-danger">*</span> </span>
                                                        <input  type="text" style="background-color: #fff;" readonly="readonly" name="bal_lmt" id="bal_lmt" class="form-control bal_lmt" placeholder="Credit limit">
                                                    </div>
                                                </div>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-12">
                                                        <span>Address <span class="text-danger">*</span> </span>
                                                        <textarea class="form-control"  type="text" id="address" style="background-color: #fff;" readonly="readonly" name="address" placeholder="Address"></textarea>
                                                    </div>
                                                </div><hr>
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-3">
                                                        <span>Buyer's Order No  </span>
                                                        <input  type="text" name="order_no" id="order_no" class="form-control" placeholder="Buyer's Order No" value="<?php echo isset($fetch->order_no) ? $fetch->order_no : '' ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Dated  <span class="text-danger">*</span></span>
                                                        <input  type="date" name="dated" id="dated" class="form-control" placeholder="Dated" value="<?php echo isset($fetch->dated) ? $fetch->dated : '' ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Eway Bill No  </span>
                                                        <input  type="text" name="docu_no" id="docu_no" class="form-control" placeholder="Eway Bill No" value="<?php echo isset($fetch->docu_no) ? $fetch->docu_no : '' ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>Others  </span>
                                                        <input  type="text" name="despatched" id="despatched" class="form-control" placeholder="Others" value="<?php echo isset($fetch->despatched) ? $fetch->despatched : '' ?>">
                                                    </div>
                                                </div><hr>
                                                <div id="sal_form">
                                                    <div class="row clearfix mb20">
                                                        <div class="col-md-4">
                                                            <span>Type <span class="text-danger">*</span> </span>
                                                            <br>
                                                            <div class="typesimpo" style="margin-top: 12px;">
                                                                <input type="radio" class="importtype" name="importtype" value="1" checked> Import
                                                                <input type="radio" class="importtype" name="importtype" value="2"> Entry
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 csvval">
                                                            <span>Csv File <span class="text-danger">*</span> </span>
                                                            <input  type="file" name="csvfile" class="form-control csvfile" id="csvfile"><br>
                                                            Format : <a href="assets/dis_sales_format.csv">Download CSV</a>
                                                        </div>
                                                    </div>
                                                    <div class="qtyval hide">
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-6">
                                                                <span>Category <span class="text-danger">*</span> </span>
                                                                <select class="selectCat form-control category_id[] js-select3-multi" name="cid" id="c_id">
                                                                    <option value="">Select category</option>
                                                                    <?php
                                                                        $a = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = 0 ORDER BY title ASC ");
                                                                        $i=0; while($rowo = mysqli_fetch_array($a)) { 
                                                                        $select_2='';
                                                                        if($rowo['id'] == $result->cid) 
                                                                        {
                                                                            $select_2 ="selected";
                                                                        }
                                                                        $b = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = '".$rowo['id']."' ORDER BY title ASC");
                                                                        $count = mysqli_num_rows($b);
                                                                        ?>     
                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select_2;?>><?php echo $rowo['title']; ?></option>
                                                                        <?php if($count>0){ ?>
                                                                        <?php
                                                                        $i++;
                                                                        while($rows = mysqli_fetch_array($b)) { 
                                                                        $select='';
                                                                        if($rows['id'] == $result->cid) 
                                                                        {
                                                                            $select ="selected";
                                                                        }
                                                                        ?>     
                                                                    <option value="<?php echo $rows['id']; ?>" <?php echo $select;?>>--<?php echo $rows['title']; ?></option>
                                                                            <?php } ?>
                                                                    <?php } } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <span>Product<span class="text-danger">*</span> </span>
                                                                <select class="form-control product_id[] js-select5-multi" name="pid" id="pid">
                                                                    <option value="">Select product name</option>
                                                                    <?php
                                                                        if($auto_id){
                                                                            $a = mysqli_query($conn, "SELECT * FROM ss_items WHERE published = '1' AND status = '1' ORDER BY id ASC ");
                                                                            while($rowo = mysqli_fetch_array($a)) {
                                                                                $select='';
                                                                                if($rowo['id'] == $result->pid) 
                                                                                {
                                                                                    $select ="selected";
                                                                                }
                                                                                ?>
                                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['title']; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-9">
                                                                <span>Serial Number <span class="text-danger">*</span> </span>
                                                                <select class="form-control code[] js-select7-multi" name="code[]" id="code" multiple="multiple">
                                                                    <option value="">Select Serial Number</option>
                                                                    <?php
                                                                        if($auto_id){
                                                                            $a = mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE cid = '".$result->cid."' AND product_id = '".$result->pid."' AND status = '1' UNION SELECT * FROM ss_item_stk WHERE cid = '".$result->cid."' AND product_id = '".$result->pid."' AND sales_no = '".$result->so_no."' ORDER BY id ASC ");
                                                                            while($rowo = mysqli_fetch_array($a)) {
                                                                                $select='';
                                                                                if($rowo['sales_no'] == $result->so_no) 
                                                                                {
                                                                                    $select ="selected";
                                                                                }
                                                                                ?>
                                                                                    <option value="<?php echo $rowo['id']; ?>" <?php echo $select;?>><?php echo $rowo['code']; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <span>Stock </span>
                                                                <input  type="text" name="stock" id="stock" class="form-control" style="background-color: #fff;" readonly="readonly" placeholder="Stock" value="<?php echo isset($product->extra)?$product->extra:'0'; ?>">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <span>Rate <span class="text-danger">*</span> </span>
                                                                <input  type="text" name="price" id="price" class="form-control" placeholder="Rate" value="<?php echo isset($result->price)?$result->price:''; ?>">
                                                            </div>
                                                            <!-- <div class="col-md-3">
                                                                <span>Quantity <span class="text-danger">*</span> </span>
                                                                <input  type="text" name="qty" id="qty" class="form-control" placeholder="Quantity">
                                                            </div> -->
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-3">
                                                                <span>Basic Allowance <span class="text-danger">*</span> </span>
                                                                <input  type="text" name="allowance" id="allowance" class="form-control" placeholder="Basic Allowance" style="background-color: #fff;" value="<?php echo isset($result->allowance)?$result->allowance:'0'; ?>">
                                                                <!-- <code>Note: Enter Percentage Value</code> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <span>STA <span class="text-danger">*</span> </span>
                                                                <input  type="text" name="sta" id="sta" class="form-control" placeholder="STA" style="background-color: #fff;" value="<?php echo isset($result->sta)?$result->sta:'0'; ?>">
                                                                <!-- <code>Note: Enter Amount Value</code> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <span>Delar Allowance <span class="text-danger">*</span> </span>
                                                                <input  type="text" name="d_allowance" id="d_allowance" class="form-control" placeholder="Delar Allowance" style="background-color: #fff;" value="<?php echo isset($result->d_allowance)?$result->d_allowance:'0'; ?>">
                                                                <!-- <code>Note: Enter Percentage Value</code> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <span>Additional discount <span class="text-danger">*</span> </span>
                                                                <input  type="text" name="discount" id="discount" class="form-control" placeholder="Additional discount"  value="<?php echo isset($result->discount)?$result->discount:'0'; ?>">

                                                                <input  type="hidden" name="gst" id="gst" class="form-control" placeholder="GST" value="<?php echo isset($result->gst)?$result->gst:'0'; ?>">

                                                                <input  type="hidden" name="hsn" id="hsn" class="form-control" placeholder="hsn" value="<?php echo isset($result->hsn)?$result->hsn:''; ?>">

                                                                <input  type="hidden" name="description" id="description" class="form-control" placeholder="description" value="<?php echo isset($result->code)?$result->code:''; ?>">

                                                                <input  type="hidden" name="avl_lmt" id="avl_lmt" class="form-control" placeholder="avl_lmt " value="<?php echo isset($result->avl_lmt)?$result->avl_lmt:''; ?>">

                                                                <input  type="hidden" name="auto_id" id="auto_id" class="form-control" placeholder="auto_id " value="<?php echo isset($result->auto_id)?$result->auto_id:'0'; ?>">

                                                                <code>Note: Enter Amount Value</code>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>    
                                                <hr>
                                                <input type="hidden" name="method" value="<?php echo isset($result->auto_id)?'edit':'add'; ?>-form"> 
                                                <button type="submit" id="add-forms" class="btn btn-danger waves-effect waves-light add-form"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo isset($result->auto_id)?'Update':'Add'; ?> Invoice </button>

                                                <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow">
                                                    <table class="table">

                                                        <thead>

                                                            <tr>

                                                                <th>Description</th>

                                                                <th>HSN</th>

                                                                <th>DP</th>

                                                                <th>Qty</th>

                                                                <th>Basic Allowance</th>

                                                                <th>STA</th>

                                                                <th>Delar Allowance</th>

                                                                <th>Additional discount</th>

                                                                <th>Option</th>

                                                            </tr>

                                                            <input id="status" type="hidden" name="status" value="0">

                                                        </thead>

                                                        <tbody id="getCategory" class="row_position">

                                                        </tbody>
                                                    </table>
                                                </div><br>
                                                <div class="col-md-12">
                                                    <div id="error" class="hide alert alert-danger text-center">
                                                        <b>No items found...</b>
                                                    </div>
                                                </div>
                                                <div class="complete_but hide">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light complete_inv"> <i  class="mdi mdi-check-circle-outline"></i> Compelte Invoice </button>
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
        <!-- <script src="assets/Ajax/ajax-brand.js"></script> -->
        <script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>
        <script type="text/javascript">
            $(document).ready(function(){

                $(document).on('change','.importtype', function () {
                    var importtype = $(this).val();  
                    if(importtype ==1)
                    {
                        $('.csvval').removeClass('hide');
                        $('.qtyval').addClass('hide');
                        $('.qty').val('');
                        $('.qty').trigger('keyup');
                    }
                    else
                    {
                        $('.csvval').addClass('hide');
                        $('.qtyval').removeClass('hide');
                    }
                });

                var so_no=$('#invno').val();
                if(so_no =='')
                {
                   loadinv(); 
                }
                
                loadOrderBox();
                loadCreatePoint();
                loadDealer();
                function loadinv()
                {
                    $.ajax({
                        type: "POST",
                        url: "inc/distri_invoice.php",
                        data: {}
                        }).done(function( msg ) {
                            $('#so_no').val(msg);
                            // alert(msg);
                        });
                }
                $(".js-select1-multi").select2({
                    placeholder: "Select Distributor Name"
                });

                $(".js-select3-multi").select2({
                    placeholder: "Select Category Name",
                    allowClear: true
                });

                $(".js-select5-multi").select2({
                    placeholder: "Select Product Name",
                    allowClear: true
                });

                $(".js-select7-multi").select2({
                    placeholder: "Select Serial Number",
                    allowClear: true
                });

                $(".js-select9-multi").select2({
                    placeholder: "Select Brand Number",
                    allowClear: true
                });

               

                $('#customer_id').change(function(){   
                    var customer_id = $('#customer_id').val();  
                    $.ajax({
                    type: "POST",
                    url: "inc/select_distributor.php",
                    data: {"customer_id":customer_id}
                    }).done(function( msg ) {
                        var dataArr = msg.split('#');
                        $.each(dataArr, function(i,element){
                            if(dataArr[i]!=""){
                                var dataArr2 = dataArr[i].split('~');
                                $('#mobile').val(dataArr2[0]);  
                                $('#address').val(dataArr2[1]); 
                                $('#d_allowance').val(dataArr2[2]); 
                            }
                        });
                        loadOrderBox();
                        loadCreatePoint();
                    });
                });

                $('#c_id').change(function(){         
                   // var brand_id = $('#brand_id').val();  
                    var c_id = $('#c_id').val();  
                    if(brand_id != '' && c_id != '')    
                    {
                        $.ajax({
                        type: "POST",
                        url: "inc/select_category.php",
                        //data: {"brand_id":brand_id ,"c_id":c_id}
                        data: {"c_id":c_id}
                        }).done(function( msg ) {
                            var dataArr = msg.split('#');
                            $('#pid option').remove();

                            $.each(dataArr, function(i,element){
                                if(dataArr[i]!=""){
                                    var dataArr2 = dataArr[i].split('~');
                                    $('#pid').append("<option value='"+dataArr2[0]+"'>"+dataArr2[1]+"</option>");
                                }
                            });
                            loadOrderBox();
                            loadCreatePoint();
                        });
                    }
                });

                $('#pid').change(function(){   
                    var pid = $('#pid').val();  
                    if(pid != '')    
                    {
                        $.ajax({
                        type: "POST",
                        url: "inc/select_product.php",
                        data: {"pid":pid}
                        }).done(function( msg ) {
                            var dataArr = msg.split('#');
                            $.each(dataArr, function(i,element){
                                if(dataArr[i]!=""){
                                    var dataArr2 = dataArr[i].split('~');
                                    $('#description').val(dataArr2[0]); 
                                    $('#price').val(dataArr2[1]); 
                                    $('#gst').val(dataArr2[2]);  
                                    $('#allowance').val(dataArr2[3]);  
                                    $('#sta').val(dataArr2[4]);  
                                    $('#hsn').val(dataArr2[5]);  
                                    $('#stock').val(dataArr2[6]);  
                                }
                            });
                        });
                    }
                });

                $('#pid').change(function(){   
                    var pid = $('#pid').val();  
                    if(pid != '')    
                    {
                        $.ajax({
                        type: "POST",
                        url: "inc/select_code.php",
                        data: {"pid":pid}
                        }).done(function( msg ) {
                            var dataArr = msg.split('#');
                            $('#code option').remove();

                            $.each(dataArr, function(i,element){
                                if(dataArr[i]!=""){
                                    var dataArr2 = dataArr[i].split('~');
                                    $('#code').append("<option value='"+dataArr2[0]+"'>"+dataArr2[1]+"</option>");
                                }
                            });
                            loadOrderBox();
                            loadCreatePoint();
                        });
                    }
                });

                $(document).on('click', '.delete-item', function () {
                    // alert('123');
                    $(this).parent().parent().remove();
                    var id = $(this).data('id');
                    $.ajax({
                      type: 'POST',
                      url: 'AjaxFunction/DeleteItem.php',
                      data: {
                        'id': id,
                        'method': 'delete',
                        'target': 'item'
                      },
                      dataType: 'JSON',
                      beforeSend: function (x) {
                        $('#status').show();
                        $('#preloader').show();
                      }
                    }).done(function (response)
                    {
                        $('#status').hide();
                      $('#preloader').hide();
                      $('.loading').css('visibility', 'hidden');
                      if (response['status'] == true)
                      {
                        $('.successCls').removeClass('hide');
                        $('.successCls').addClass('show');
                        $('.errorCls').addClass('hide');
                        $('.successmsg').html(response['message']);
                        alertHelper();
                        setInterval('reloadPage()', 5000);
                        // setInterval( function () {
                        //     table.ajax.reload();
                        // }, 30 );
                      } 
                      else
                      {
                        $('.errorCls').removeClass('hide');
                        $('.errorCls').addClass('show');
                        $('.successCls').addClass('hide');
                        $('.errormsg').html(response['message']);
                        alertHelper();
                        setInterval('reloadPage()', 5000);
                        // setInterval( function () {
                        //     table.ajax.reload();
                        // }, 30 );
                      }
                    });
                });
            });
            $(document).ready(function (e) {
                $('#add-form').on('submit', function (e) {
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: 'AjaxFunction/AjaxDistInv.php',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'json',
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
                            $('.successCls').removeClass('hide');
                            $('.successCls').addClass('show');
                            $('.errorCls').addClass('hide');
                            $('.successmsg').html(response['message']);
                            $('#mobile').prop("disabled",true);
                            $('#address').prop("disabled",true);
                            $('#order_no').addClass('readonly');
                            $('#dated').addClass('readonly');
                            $('#docu_no').addClass('readonly');
                            $('#despatched').addClass('readonly');
                            $('#price').val('');
                            $('#allowance').val('0');
                            $('#sta').val('0');
                            $('#discount').val('0');
                            $('#gst').val('0');
                            $('#price_val').val('');
                            $('#stock').val('');
                            $("#c_id").val('').trigger('change');
                            $("#pid").val('').trigger('change');
                            $("#code").val('').trigger('change');
                            alertHelper();
                            loadOrderBox();
                            loadCreatePoint();
                        } 
                        else
                        {
                            $('.errorCls').removeClass('hide');
                            $('.errorCls').addClass('show');
                            $('.successCls').addClass('hide');
                            $('.errormsg').html(response['message']);
                            alertHelper();
                            loadOrderBox();
                            loadCreatePoint();
                        }
                    });
                });
                $(document).on('click', '.complete_inv', function () {
                    var so_no = $("#so_no").val();
                    var customer_id = $("#customer_id").val();
                    $.ajax({
                        method: 'POST',
                        data: {
                            "so_no":so_no,
                            "customer_id":customer_id
                        },
                        url:"inc/complete-inv.php",
                        dataType: 'json',
                    }).done(function (response) {
                        $('#status').hide();
                        $('#preloader').hide();
                        location.reload();
                    });
                });
            });
            function alertHelper()
            {
                setTimeout(function ()
                {
                    $('.successCls').removeClass('show').addClass('hide');
                    $('.errorCls').removeClass('show').addClass('hide');
                }, 5000);
            }

            function loadOrderBox() {
                var so_no = $("#so_no").val();
                var customer_id = $("#customer_id").val();
                // alert(customer_id);
                $.ajax({
                    method: 'POST',
                    data: {
                        "so_no":so_no,
                        "customer_id":customer_id
                    },
                    url:"inc/manage-dist-inv.php",
                }).done(function (response) {
                    $('#status').hide();
                    $('#preloader').hide();
                    if (response == 1)
                    {
                        $('#error').removeClass('hide');
                        $('.table').addClass('hide');
                        $('.complete_but').addClass('hide');
                    } 
                    else
                    {
                        $('#error').addClass('hide');
                        $('.table').removeClass('hide');
                        $('.complete_but').removeClass('hide');
                        $('#getCategory').html(response);
                    }
                });
            }

            function loadDealer() {
                var customer_id = $('#customer_id').val();
                if(customer_id != '')  
                {
                    $.ajax({
                    type: "POST",
                    url: "inc/select_distributor.php",
                    data: {"customer_id":customer_id}
                    }).done(function( msg ) {
                        var dataArr = msg.split('#');
                        $.each(dataArr, function(i,element){
                            if(dataArr[i]!=""){
                                var dataArr2 = dataArr[i].split('~');
                                $('#mobile').val(dataArr2[0]);  
                                $('#address').val(dataArr2[1]); 
                                $('#d_allowance').val(dataArr2[2]); 
                            }
                        });
                        loadOrderBox();
                        loadCreatePoint();
                    });
                }  
            }
            
            function loadCreatePoint() {
                var customer_id = $('#customer_id').val();  
                if(customer_id != '')    
                {
                    $.ajax({
                    type: "POST",
                    url: "inc/distributor-createpoint.php",
                    data: {"customer_id":customer_id}
                    }).done(function( msg ) {
                        var dataArr = msg.split('#');
                        $.each(dataArr, function(i,element){
                            if(dataArr[i]!=""){
                                var dataArr2 = dataArr[i].split('~');
                                $('#avl_lmt').val(dataArr2[0]);
                                $('#bal_lmt').val(dataArr2[0]); 
                            }
                        });
                    });
                }
            }
            function reloadPage(){
                location.reload();
            }
        </script>
    </body>
</html>