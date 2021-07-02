<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
isAdmin();
$_types =  $_SESSION['type'];
// echo  $_SESSION['uid'];
//print_r($_SESSION); exit();
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
        <!-- Basic Css files -->
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
                                <div class="col-md-4 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-info">
                                                <a href="distributor-invoice-list">
                                                <?php
                                                $result = mysqli_fetch_object(mysqli_query($conn,"SELECT COUNT(*) as count FROM `ss_distributor_invoice` where `published`='1'"));
                                                echo $result->count?$result->count:'0';
                                                ?>
                                            </a>
                                            </h3>
                                            All Distributors Orders
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-info">
                                                <a href="manage-sales">
                                                <?php
                                                $result = mysqli_fetch_object(mysqli_query($conn,"SELECT COUNT(*) as count FROM `ss_sales` where `published`='1'"));
                                                echo $result->count?$result->count:'0';
                                                ?>
                                            </a>
                                            </h3>
                                            All Dealer Orders
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-primary">
                                                <a href="manage-customer">
                                                    <?php
                                                    $result = mysqli_fetch_object(mysqli_query($conn,"SELECT COUNT(*) as count FROM `ss_distributors` WHERE `isAdmin`!='1' AND `deleted`='1'"));
                                                    echo $result->count?$result->count:'0';
                                                    ?>
                                                </a>
                                            </h3>
                                            Distributors
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-4 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-danger">
                                                <a href="manage-employee">
                                                    <?php
                                                    $result = mysqli_fetch_object(mysqli_query($conn,"SELECT COUNT(*) as count FROM `ss_employee` WHERE `name`!='' AND `status`='1'"));
                                                    echo $result->count?$result->count:'0';
                                                    ?>
                                                </a>
                                            </h3>
                                            Employees
                                        </div>
                                    </div>
                                </div> -->
                                
                                <div class="col-md-4 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-danger">
                                                <a href="<?php echo $_types==1 ? 'manage-items' : '#'; ?>">
                                                    <?php
                                                    $result = mysqli_fetch_object(mysqli_query($conn,"SELECT COUNT(*) as count FROM `ss_items` where `published`='1' "));
                                                    echo $result->count?$result->count:'0';
                                                    ?>
                                                </a>
                                            </h3>
                                            Products
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Serial No Details</h4>
                                            <form id="add-form" name="add-form" method="post">
                                                <div class="row clearfix">
                                                    <div class="col-md-6 ">
                                                        <input  type="text" name="serial_no" id="serial_no" class="form-control" placeholder="Serial No">
                                                    </div>
                                                    <div class="col-md-1 fl pdz">
                                                        <input type="hidden" name="method" id="method" value="add-form">
                                                        <button type="button" id="serial_button" class="btn btn-danger waves-effect waves-light add-form"> <i  class="mdi mdi-check-circle-outline"></i> Search </button>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow-div">
                                                        <table class="table hide">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Serial No</th>
                                                                    <th>Product Name</th>
                                                                    <th>Description</th>
                                                                    <th>Category </th>
                                                                    <th>Dealer Price</th>
                                                                    <th>Purchase Invice</th>
                                                                    <th>Sales Invice</th>
                                                                </tr>
                                                                <input id="status" type="hidden" name="status" value="0">
                                                            </thead>
                                                            <tbody id="getCategory">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div id="error" class="alert alert-danger text-center" style="width: 100%;">
                                                      <b>No items found...</b>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Latest Distributors Transactions</h4>
                                            <div class="table-responsive">
                                                <?php 
                                                $result =mysqli_query($conn,"SELECT * FROM ss_distributor_invoice WHERE status = '1' AND published = '1' ORDER BY id DESC LIMIT 5");
                                                ?>
                                                <table class="table m-t-20 mb-0 table-vertical">
                                                    <?php
                                                        $numrow=mysqli_num_rows($result);
                                                        if($numrow > 0) 
                                                        { ?>
                                                             <thead>
                                                            <tr>
                                                                <th class="sorting" data-sort="0">
                                                                    <i class="filter hide fa fa-sort"></i> Bill ID
                                                                </th>
                                                                <th class="sorting" data-sort="1">
                                                                    <i class="filter hide fa fa-sort"></i>  Distributors
                                                                </th>
                                                                <!-- <th class="sorting" data-sort="3">
                                                                    <i class="filter hide fa fa-sort"></i>Total
                                                                </th> -->
                                                                <th class="sorting" data-sort="4">
                                                                    <i class="filter hide fa fa-sort"></i>Order Created on
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                            <?php 
                                                        $tot_amt = 0;
                                                        $gst_amt = 0;
                                                        while($row = mysqli_fetch_array($result))   
                                                        {
                                                            $customer = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE id = '".$row['customer_id']."'"));

                                                            // echo "SELECT * FROM ss_sales_details WHERE so_id = '".$row['id']."'";
                                                            $amount = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_sales_details WHERE so_id = '".$row['id']."'"));

                                                            $gst_ = $amount->price * $amount->gst / 100;

                                                            $gst_amt += $gst_;

                                                            // $tot_amt += $amount->value_pri;

                                                            ?>
                                                         <tr>
                                                            <td><?php echo $row['so_no']; ?></td>
                                                            <td><?php echo $customer->store; ?></td>
                                                            <!-- <td><?php echo 'Rs. '.$gst_amt; ?></td> -->
                                                            <td><?php echo date("d-m-Y h:i A", strtotime($row['createdate'])); ?></td>
                                                        </tr>
                                                    <?php } 
                                                    } else {  ?>
                                                        <div class="alert alert-danger">
                                                            <strong>No order found..</strong>
                                                        </div>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Latest Dealer Transactions</h4>
                                            <div class="table-responsive">
                                                <?php 
                                                $result =mysqli_query($conn,"SELECT * FROM ss_sales WHERE status = '1' AND published = '1' ORDER BY id DESC LIMIT 5");
                                                ?>
                                                <table class="table m-t-20 mb-0 table-vertical">
                                                    <?php
                                                        $numrow=mysqli_num_rows($result);
                                                        if($numrow > 0) 
                                                        { ?>
                                                             <thead>
                                                            <tr>
                                                                <th class="sorting" data-sort="0">
                                                                    <i class="filter hide fa fa-sort"></i> Bill ID
                                                                </th>
                                                                <th class="sorting" data-sort="1">
                                                                    <i class="filter hide fa fa-sort"></i>  Customer
                                                                </th>
                                                                <!-- <th class="sorting" data-sort="3">
                                                                    <i class="filter hide fa fa-sort"></i>Total
                                                                </th> -->
                                                                <th class="sorting" data-sort="4">
                                                                    <i class="filter hide fa fa-sort"></i>Order Created on
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                            <?php 
                                                        $tot_amt = 0;
                                                        $gst_amt = 0;
                                                        while($row = mysqli_fetch_array($result))   
                                                        {
                                                            $customer = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE id = '".$row['customer_id']."'"));

                                                            // echo "SELECT * FROM ss_sales_details WHERE so_id = '".$row['id']."'";
                                                            $amount = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_sales_details WHERE so_id = '".$row['id']."'"));

                                                            $gst_ = $amount->price * $amount->gst / 100;

                                                            $gst_amt += $gst_;

                                                            // $tot_amt += $amount->value_pri;

                                                            ?>
                                                         <tr>
                                                            <td><?php echo $row['so_no']; ?></td>
                                                            <td><?php echo $customer->name; ?></td>
                                                            <!-- <td><?php echo 'Rs. '.$gst_amt; ?></td> -->
                                                            <td><?php echo date("d-m-Y h:i A", strtotime($row['createdate'])); ?></td>
                                                        </tr>
                                                    <?php } 
                                                    } else {  ?>
                                                        <div class="alert alert-danger">
                                                            <strong>No order found..</strong>
                                                        </div>
                                                    <?php } ?>
                                                </table>
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
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/pages/dashborad.js"></script>
        <script src="assets/js/app.js"></script>
        <script type="text/javascript">
            $('#serial_button').on('click',function(){
                var formval = $('#add-form').serialize();
                $.ajax({
                  type: 'POST',
                  url: 'inc/manage-serial-number.php',
                  data:formval,
                  // dataType: 'json',
                }).done(function (response)
                {
                    if(response == 1)
                    {
                        $('#error').removeClass('hide');
                        $('#error').addClass('show');
                        $('.table').addClass('hide');
                    }
                    else
                    { 
                        $('#error').removeClass('show');
                        $('#error').addClass('hide');
                        $('.table').removeClass('hide');
                        $('#getCategory').html(response);
                    }
                });
            });
        </script>
    </body>
</html>