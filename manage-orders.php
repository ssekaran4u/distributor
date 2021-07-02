<?php 
// echo phpinfo(); die();
session_start();
include 'inc/config.php';
isAdmin();
$branch = isAdminDetails($conn);
$id = $_GET['id'];
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
        <style type="text/css">
            .table > tbody > tr > td, .table > tfoot > tr > td, .table > thead > tr > td {
                padding: 7px 5px;
            }
            .table {
                text-align: center;
            }
            .btn-sm {
                padding: 2px 10px;
            }
            .disabled {
                cursor: no-drop;
            }
            .br20 {
            }
            .clickable {
                cursor: pointer;
            }
            .padd0
            {
                padding: 0px;
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
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <span class="loading"></span>
                                        <h4 class="mt-0 header-title clearfix">
                                        </h4>
                                        <hr>
                                        <div class="col-md-4 fl pdz">
                                            <input id="search" name="dSuggest" type="text" class="form-control"  placeholder="Search Order ID..." value="">
                                        </div>
                                        <div class="col-md-3 fl pdz">
                                            <select class="form-control" id="orderType">
                                            </select>
                                        </div>
                                        <!-- <div class="col-md-2 fl pdz">
                                            <select class="form-control" id="orderBranch">
                                            </select>
                                        </div> -->
                                        <div class="col-md-2 col-sm-12 col-xs-12 fl pdz">
                                            <button class="btn btn-warning col-xs-12 " id="dSuggest">
                                                <i class="mdi mdi-autorenew"></i> Search
                                            </button>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 overflow-div padd0">
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
                                          <table class="table">
                                              <thead>
                                                  <tr>
                                                      <!-- <th>Query</th> -->
                                                      <th class="sorting" data-sort="0">
                                                          <i class="filter hide fa fa-sort show"></i> Order ID
                                                      </th>
                                                      <th class="sorting" data-sort="1">
                                                          <i class="filter hide fa fa-sort"></i>  Customer
                                                      </th>
                                                      <!-- <th class="sorting" data-sort="2">
                                                          <i class="filter hide fa fa-sort"></i>Shiped to
                                                      </th> -->
                                                      <th class="sorting" data-sort="3">
                                                          <i class="filter hide fa fa-sort"></i>Total
                                                      </th>
                                                      <th class="sorting" data-sort="4">
                                                          <i class="filter hide fa fa-sort"></i>Order Placed on
                                                      </th>
                                                      <!-- <th class="sorting" data-sort="5">
                                                          <i class="filter hide fa fa-sort"></i>
                                                          Location
                                                      </th> -->
                                                      <th class="sorting" data-sort="6">
                                                          <i class="filter hide fa fa-sort"></i>
                                                          Status
                                                      </th>
                                                      <th class="text-center">
                                                          Actions
                                                      </th>
                                                  </tr>
                                              </thead>
                                              <tbody id="getCategory">
                                              </tbody>
                                          </table>
                                          
                                        </div>
                                        <input id="status" type="hidden" name="status" value="0">
                                        <input id="type" type="hidden" name="type" value="<?php echo $type; ?>">
                                         <input id="type" type="hidden" class="uid" name="uid" value="<?php echo isset($_GET['id'])?$_GET['id']:'0'; ?>">
                                        <div id="error" class="hide alert alert-danger text-center">
                                            <b>No items found...</b>
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
        <script src="assets/js/app.js"></script>
        <script type="text/javascript">

            function alertHelper() {
                setTimeout(function() {
                    $('.successCls').removeClass('show').addClass('hide');
                    $('.errorCls').removeClass('show').addClass('hide');
                }, 2000);
            }
            $(document).on('click', '.delete-btn', function() {
                //alert('dshgfdhg');
                $(this).parent().parent().remove();
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'AjaxFunction/DeleteFunction.php',
                    data: {
                        'id': id,
                        'method': 'delete',
                        'target': 'order'
                    },
                    dataType: 'JSON',
                }).done(function(response) {
                    if (response['status'] == true) {
                        $('.successCls').removeClass('hide');
                        $('.successCls').addClass('show');
                        $('.errorCls').addClass('hide');
                        $('.successmsg').html(response['message']);
                        alertHelper();
                    } else {
                        $('.errorCls').removeClass('hide');
                        $('.errorCls').addClass('show');
                        $('.successCls').addClass('hide');
                        $('.errormsg').html(response['message']);
                        alertHelper();
                    }
                });
            });
        </script>
    </body>
</html>