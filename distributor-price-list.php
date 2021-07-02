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
        <link href="assets/css/jquery-ui.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
        <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'>
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
                                            <h4 class="mt-0 header-title clearfix"><strong>Create Distributor Wise Price Report</strong></h4>
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
                                            <form id="add-form" name="add-form" method="post">
                                                <div class="row clearfix mb20">
                                                    <div class="col-md-4">
                                                        <span>Distributor Name <span class="text-danger">*</span> </span>
                                                        <select class="form-control distributor_id js-select2-multi" name="distributor_id" id="distributor_id">
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
                                                    <div class="col-md-4">
                                                        <span>Category Name <span class="text-danger">*</span> </span>
                                                        <select class="form-control category_id js-select1-multi selectpicker" name="category_id" id="category_id">
                                                            <?php
                                                                $a = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = 0 ORDER BY title ASC ");
                                                                $count = mysqli_num_rows($a);
                                                                ?>
                                                            <option value="0">--Select Category Name--</option>
                                                            <?php
                                                                $i=0; while($rowo = mysqli_fetch_array($a)) { 
                                                                $b = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = '".$rowo['id']."' ORDER BY title ASC");
                                                                $count = mysqli_num_rows($b);
                                                                ?> 
                                                            <option value="<?php echo $rowo['id']; ?>"><?php echo $rowo['title']; ?></option>
                                                            <?php if($count>0){ ?>
                                                            <?php
                                                                $i++;
                                                                while($rows = mysqli_fetch_array($b)) { ?>     
                                                            <option value="<?php echo $rows['id']; ?>">--<?php echo $rows['title']; ?></option>
                                                            <?php } ?>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3" style="margin-top: 20px;">
                                                        <!-- <input type="hidden" name="method" id="method" value="add-form"> -->
                                                        <button type="button" id="report-forms" class="btn btn-danger waves-effect waves-light report-form export_report"> <i  class="mdi mdi-check-circle-outline"></i> Search </button>
                                                        <input type="hidden" name="method" id="method_" value="add_distributor_price">
                                                        <span class="excel_view hide"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="table_lst hide" style="width: 100%;">
                                                            <div id="custom-student-list">
                                                            </div>
                                                        </div>
                                                        <div id="error" class="show alert alert-danger text-center" style="width: 100%;">
                                                            <b>No Record found...</b>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <span class="submit_view hide"></span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery-ui.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/js/select2.full.js"></script>
        <script src="assets/js/app.js"></script>
        <script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script>
        <script type="text/javascript">
            $(document).ready(function () {
            
                $('.dates').datepicker({
                    maxDate: 0,
                    dateFormat: 'dd-mm-yy',
                    ignoreReadonly: true,
                });
            
                $(".js-select1-multi").select2({
                    placeholder: "Select Category Name"
                });
            
                $(".js-select2-multi").select2({
                    placeholder: "Select Distributor Name"
                });
            
                $('.dates').datepicker({
                    maxDate: 0,
                    dateFormat: 'yy-mm-dd',
                    ignoreReadonly: true,
                });
            
                function gototop() {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);
                }
            
                function alertHelper()
                {
                    setTimeout(function ()
                    {
                        $('.successCls').removeClass('show').addClass('hide');
                        $('.errorCls').removeClass('show').addClass('hide');
                    }, 2000);
                }
            
                $('#report-forms').on('click', function (e) {
                    $('#preloader').show();
                    $('#status').show();

                    var distributor_id = $("#distributor_id").val();
                    var category_id    = $("#category_id").val();

                    e.preventDefault();
                    $.ajax({
                        url: 'inc/admin-login.php',
                        method: 'POST',
                        data: {
                            "distributor_id" : distributor_id,
                            "category_id"    : category_id,
                            "method"         : '_Sel_Distributors_Price'
                        },
                        dataType: 'json',
                    }).done(function(response){
                        console.log(response['status']);
                        $('#preloader').hide();
                        $('#status').hide();
                        if(response['status'] == true)
                        {
                            $('.table_lst').removeClass('hide').addClass('show');
                            $('#error').removeClass('show').addClass('hide');
            
                            $('.submit_view').removeClass('hide').addClass('show');
                            $('.submit_view').empty().html(response['button']);
            
                            $('#custom-student-list').empty();
                            $('#custom-student-list').html(response['result']);
                            alertHelper();
                        }   
                        else
                        {
                            $('.table_lst').removeClass('show').addClass('hide');
                            $('#error').removeClass('hide').addClass('show');
                            $('.errorCls').removeClass('hide').addClass('show');
                            $('.errormsg').empty().html(response['message']);
            
                            $('.submit_view').removeClass('show').addClass('hide');
                            $('.submit_view').empty();
            
                            alertHelper();
            
                        }
                    });
                });

                $('#add-form').on('submit', function (e) {
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: 'AjaxFunction/AjaxPrice.php',
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
                            alertHelper();
                            gototop();
                        } 
                        else
                        {
                            $('.errorCls').removeClass('hide');
                            $('.errorCls').addClass('show');
                            $('.successCls').addClass('hide');
                            $('.errormsg').html(response['message']);
                            alertHelper();
                            gototop();
                        }
                    });
                });
            });
        </script>
    </body>
</html>
