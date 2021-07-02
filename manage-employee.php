<?php 
session_start();
include 'inc/config.php';
isAdmin();

if(isset($_POST['export']))
{
  $now = date('d-M-Y');
  header('Content-Type: text/csv; charset=utf-8');  
  header('Content-Disposition: attachment; filename=employee_list'.$now.'.csv');  
  $output = fopen("php://output", "w");   
  fputcsv($output, array('Name', 'Email', 'Mobile No', 'Employee Type', 'Shop Name', 'Address')); 
  $qry = "SELECT * FROM `ss_employee` WHERE `published` = '1' AND status = '1'";
  $result = mysqli_query($conn, $qry);  
  while($row = mysqli_fetch_object($result))
  {

    if($row->emp_type == 1)
    {
      $emp_type = 'Sales Man';
    }
    else
    {
      $emp_type = 'Demonstrator';
    }

    $store_qry = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE id = '".$row->d_id."'"));

    $num = array($row->name, $row->email, $row->mobile, $emp_type, $store_qry->name, $row->address);
    fputcsv($output, $num);  
  }
  fclose($output);
  exit();
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
                                            <h4 class="mt-0 header-title clearfix"><strong>Manage Employee</strong></h4>
                                            <hr>
                                            <div class="col-md-4 pdz">
                                                <input id="search" name="dSuggest" type="text" class="form-control"  placeholder="Search by Name..." value="">
                                            </div>
                                            <div class="col-md-2 fl pdz">

                                                <select class="form-control" id="emp_type">
                                                  <option value="">Select type</option>
                                                  <option value="1">Salesman</option>
                                                  <option value="2">Demonstrator</option>
                                                </select>

                                            </div>
                                            <div class="col-md-4">
                                              <div class="col-md-4">
                                                  <button class="btn btn-warning" id="dSuggest">
                                                    <i class="mdi mdi-autorenew"></i> Search
                                                </button>
                                              </div>
                                              <div class="col-md-6">
                                                  <form id="add-form" name="add-form" method="post" >
                                                    <input type="hidden" name="method" id="method_" value="export-form">
                                                    <button type="submit" name="export" id="form-export" class="btn btn-primary waves-effect waves-light form-export"> <i  class="mdi mdi-file-excel"></i> Excel </button>
                                                  </form>
                                                </div>
                                            </div>
                                            <div class="col-md-2">

                                                <h4 class="mt-0 header-title clearfix"> <a class="pull-right btn btn-pink" href="create-employee"><i class="fa fa-plus-circle"></i>  Add New Employee</a> </h4>

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
                                                            <th class="sorting" data-sort="0">
                                                                <i class="filter hide fa fa-sort show"></i> Type
                                                            </th>
                                                            <th class="sorting" data-sort="1">
                                                                <i class="filter hide fa fa-sort show"></i> Name
                                                            </th>
                                                            <th class="text-left sorting" data-sort="2">
                                                                <i class="filter hide fa fa-sort show"></i>
                                                                Email
                                                            </th>
                                                            <th class="text-center sorting" data-sort="3">
                                                                <i class="filter hide fa fa-sort show"></i>
                                                               Mobile
                                                            </th>
                                                            <th class="text-center">
                                                                <i class="filter hide fa fa-sort show"></i>
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
        <script src="assets/js/bootstrap.min.js"></script>
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
              function loadInCartitems(page, sort, order, search,emp_type) {
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
                    'search': search,
                    'emp_type':emp_type
                  },
                  url: 'inc/manage-employee.php',
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
                var emp_type = $('#emp_type').val();
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
                loadInCartitems(0, sort, order, search,emp_type);
                $('.sorting i').removeClass('show');
                $(this).find('i').addClass('show');
              });
              $(document).on('click', '.pages', function () {
                var emp_type = $('#emp_type').val();
                var search = $('#search').val();
                var page = $(this).data('page');
                var sorts = $('.table .show').parent('th').data('sort');
                var order = $('#status').val();
                loadInCartitems(page, sorts, order, search,emp_type);
                gototop();
              });
              $(document).on('click', '#dSuggest', function () {
                var emp_type = $('#emp_type').val();
                var search = $('#search').val();
                var sorts = $('.table .show').parent('th').data('sort');
                var order = $('#status').val();
                loadInCartitems(1, sorts, order, search,emp_type);
              });
               $(document).on('change', '#emp_type', function () {

                search = $('#search').val();

                sorts = $('.table .show').parent('th').data('sort');

                order = $('#status').val();

                var fd = $(this).val();
                var emp_type;

                if (fd)

                {

                  emp_type = fd;

                } 

                else

                {

                  emp_type = 0;

                }

                loadInCartitems(1, sorts, order, search, emp_type);

              });
              var emp_type = $('#emp_type').val();
              var search = $('#search').val();
              var sorts = $('.table .show').parent('th').data('sort');
              var order = $('#status').val();
              loadInCartitems(1, sorts, order, search,emp_type);
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
                  'target': 'employee'
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
            $(document).on('click', '.customer_view', function () {
              var id = $(this).data('id');
              $.ajax({
                type: 'POST',
                url: 'inc/customer-model-view.php',
                data: {
                  'id': id,
                  'method': 'view'
                },
                dataType: 'json',
              }).done(function (response)
              {
                if (response['status'] == 'true')
                {
                  $('.modal-body').html(response['message']);
                } 
                else
                {
                  $('.errorCls').removeClass('hide');
                  $('.errorCls').addClass('show');
                  $('.errormsg').html('Oops ! Can\'t View');
                  alertHelper();
                }
              });
            });
            $('#customer_view').on('hidden.bs.model', function () {
              alert('sdhjhg');
            });

        </script>
    </body>
</html>