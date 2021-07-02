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
                                <div class="col-lg-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <span class="loading"></span>
                                            <h4 class="mt-0 header-title clearfix"><strong>Manage State</strong></h4>
                                            <hr>
                                            <div class="col-md-6 pdz">
                                                <input id="search" name="dSuggest" type="text" class="form-control"  placeholder="Search by Name..." value="">
                                            </div>
                                            <div class="col-md-2 pdz">
                                                <button class="btn btn-warning" id="dSuggest">
                                                    <i class="mdi mdi-autorenew"></i> Search
                                                </button>
                                            </div>
                                            <div class="col-md-4">

                                                <h4 class="mt-0 header-title clearfix"> <a class="pull-right btn btn-pink" href="create-state"><i class="fa fa-plus-circle"></i>  Add New State</a> </h4>

                                            </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow-div">
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
                                                            <!-- <th class="sorting" data-sort="0">
                                                                <i class="filter hide fa fa-sort show"></i> Through
                                                            </th> -->
                                                            <th class="sorting" data-sort="1">
                                                                <i class="filter hide fa fa-sort show"></i> State Name
                                                            </th>
                                                            <th class="text-center">
                                                                <!-- <i class="filter hide fa fa-sort show"></i> -->
                                                               Country
                                                            </th>
                                                            <th class="text-center">
                                                                View
                                                            </th>
                                                            <th class="text-center">
                                                                Actions
                                                            </th>
                                                        </tr>
                                                        <input id="status" type="hidden" name="status" value="0">
                                                    </thead>
                                                    <tbody id="getCategory">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div id="error" class="hide alert alert-danger text-center">
                                                <b>No items found...</b>
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
        <div class="modal fade" id="customer_view" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title text-left">Employee Info</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/js/app.js"></script>
        <script type="text/javascript">
           $(document).ready(function () {
              function gototop() {
                $('html, body').animate({
                  scrollTop: 0
                }, 1000);
              }
              function loadInCartitems(page, sort, order, search) {
                var npage,
                nsort;
                if (page)
                {
                  npage = page;
                } 
                else
                {
                  npage = 1;
                }
                if (sort)
                {
                  nsort = sort;
                } 
                else
                {
                  nsort = 0;
                }
                $.ajax({
                  method: 'POST',
                  data: {
                    'page': npage,
                    'sort': nsort,
                    'order': order,
                    'search': search
                  },
                  url: 'inc/manage-state.php',
                  beforeSend: function (x) {
                    $('.loading').css('visibility', 'visible');
                  }
                }).done(function (response) {
                  $('.loading').css('visibility', 'hidden');
                  if (response == 1)
                  {
                    $('#error').removeClass('hide');
                    $('.table').addClass('hide');
                  } 
                  else
                  {
                    $('#error').addClass('hide');
                    $('.table').removeClass('hide');
                    $('#getCategory').html(response);
                  }
                });
              }
              $(document).on('click', '.sorting', function () {
                var sort = $(this).data('sort');
                var status = $('#status').val();
                if (status == 0)
                {
                  $('#status').val(1);
                } 
                else
                {
                  $('#status').val(0);
                }
                var search = $('#search').val();
                var order = $('#status').val();
                loadInCartitems(0, sort, order, search);
                $('.sorting i').removeClass('show');
                $(this).find('i').addClass('show');
              });
              $(document).on('click', '.pages', function () {
                var search = $('#search').val();
                var page = $(this).data('page');
                var sorts = $('.table .show').parent('th').data('sort');
                var order = $('#status').val();
                loadInCartitems(page, sorts, order, search);
                gototop();
              });
              $(document).on('click', '#dSuggest', function () {
                var search = $('#search').val();
                var sorts = $('.table .show').parent('th').data('sort');
                var order = $('#status').val();
                loadInCartitems(1, sorts, order, search);
              });
              var search = $('#search').val();
              var sorts = $('.table .show').parent('th').data('sort');
              var order = $('#status').val();
              loadInCartitems(1, sorts, order, search);
            });
            function alertHelper()
            {
              setTimeout(function ()
              {
                $('.successCls').removeClass('show').addClass('hide');
                $('.errorCls').removeClass('show').addClass('hide');
              }, 2000);
            }
            $(document).on('click', '.delete-btn', function () {
              //alert('dshgfdhg');
              $(this).parent().parent().remove();
              var id = $(this).data('id');
              $.ajax({
                type: 'POST',
                url: 'AjaxFunction/DeleteFunction.php',
                data: {
                  'id': id,
                  'method': 'delete',
                  'target': 'state'
                },
                dataType: 'json',
              }).done(function (response)
              {
                if (response['status'] == true)
                {
                  $('.successCls').removeClass('hide');
                  $('.successCls').addClass('show');
                  $('.errorCls').addClass('hide');
                  $('.successmsg').html(response['message']);
                  alertHelper();
                } 
                else
                {
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