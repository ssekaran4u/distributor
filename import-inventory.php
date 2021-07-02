<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();

$id = isset($_GET['id'])?$_GET['id']:'0';

$fetch = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$id."' LIMIT 1"));

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
        <style type="text/css">
            .hide{
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
                                            <h4 class="mt-0 header-title clearfix"><strong><?php echo isset($fetch->id) ? 'Edit' : 'Add'; ?> Inventory</strong> <a class="pull-right btn btn-pink" href="manage-items"><i class="fa fa-plus-circle"></i> Manage Inventory</a></h4>
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
                                                        <span>Invoice Number <span class="text-danger">*</span> </span>
                                                        <input  type="text" name="invoice_no" class="form-control invoice_no" placeholder="Invoice Number" value="<?php echo isset($fetch->sta) ? $fetch->sta : '' ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span>Date <span class="text-danger">*</span> </span>
                                                        <input  type="date" name="date" class="form-control date" placeholder="Date" value="<?php echo isset($fetch->extra) ? $fetch->extra : '' ?>">
                                                    </div>
                                                    <div class="col-md-5 csvval">
                                                        <span>Csv File <span class="text-danger">*</span> </span>
                                                        <input  type="file" name="csvfile" class="form-control csvfile" id="csvfile"><br>
                                                        Format : <a href="assets/group_format.csv">Download CSV</a>
                                                        <input type="hidden" class="importtype" name="importtype" value="1" checked>
                                                    </div>
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

                                                <button type="submit" id="<?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-form" class="btn btn-danger waves-effect waves-light <?php echo isset($fetch->id) ? 'edit' : 'add'; ?>-item"> <i  class="mdi mdi-check-circle-outline"></i> <?php echo isset($fetch->id) ? 'Edit' : 'Import'; ?> Inventory </button>
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
        <script src="assets/Ajax/ajax-inventory.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".js-select3-multi").select2({
                    placeholder: "Select Category Name"
                });

                $(".js-select5-multi").select2({
                    placeholder: "Select Product Name"
                });

                $(".js-select9-multi").select2({
                    placeholder: "Select Brand Number",
                    allowClear: true
                });

                $('#b_id').change(function(){   
                    var b_id = $('#b_id').val();  
                    if(b_id != '')    
                    {
                        $.ajax({
                        type: "POST",
                        url: "inc/select_category.php",
                        data: {"b_id":b_id}
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
                        url: "inc/select_category_type.php",
                        data: {"p_id":pid}
                        }).done(function( msg ) {
                            var dataArr = msg.split('#');
                            $('#c_id option').remove();

                            $.each(dataArr, function(i,element){
                                if(dataArr[i]!=""){
                                    var dataArr2 = dataArr[i].split('~');
                                    $('#c_id').append("<option value='"+dataArr2[0]+"'>"+dataArr2[1]+"</option>");
                                }
                            });
                            loadOrderBox();
                            loadCreatePoint();
                        });
                    }
                });

                $('#qty').keyup(function(){     
                    var quantity = $('#qty').val();
                    if(quantity)
                    { 
                        $('#item_table').html('');  
                        var html = '';
                        for(i=1; i <= quantity; i++)
                        {
                            // console.log(i);
                            html += '<div class="col-md-3 form-group" style="float: left;"><input type="text" data-te='+i+' name="code[]" style="max-width: 285px; min-width: 285px;" class="datqul inputs form-control code quantity'+i+'" id="code'+i+'" value="" /></div>';
                        }
                        $('#item_table').append(html);
                    }
                    else
                    {
                        $('#item_table').html('');    
                    }
                });

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
            });
        </script>
    </body>
</html>