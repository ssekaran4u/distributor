<?php 
session_start();
include 'inc/config.php';
$branch = isAdminDetails($conn);
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
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    </head>

<style type="text/css">
    .disabled
    {
        cursor: not-allowed;
        filter: alpha(opacity=65);
        -webkit-box-shadow: none;
        box-shadow: none;
        opacity: .65;
        pointer-events: none;
    }
    .img-upload1, .img-upload2, .img-upload3, .img-upload4, .img-upload5
    {
        width: 143px;
        height: 100px;
        padding: 7px;
    }
</style>
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
                                            <h4 class="mt-0 header-title clearfix"><strong>Manage Products</strong> <a class="pull-right btn btn-pink" href="manage-items"><i class="fa fa-plus-circle"></i> Manage Products</a></h4>
                                            <hr>
                                            <div id="o-message"></div>
                                            <div class="alert alert-danger hide errormsg">
                                            </div>
                                            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                <li class="nav-item ">
                                                    <a class="nav-link active" data-toggle="tab" href="#ds1" role="tab">General</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link menus cate-tab disabled" data-toggle="tab" href="#ds2" role="tab">Categories</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link menus price-tab disabled" data-toggle="tab" href="#ds3" role="tab">Prices and Discounts</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link menus spci-tab disabled" data-toggle="tab" href="#ds4" role="tab">Specifications</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link menus img-tab disabled" data-toggle="tab" href="#ds5" role="tab">Images</a>
                                                </li>
                                            </ul>
                                            <?php 
                                            if(isset($_GET['id']))
                                            {
                                                $fetch=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_items` where  id='".$_GET['id']."'"));
                                             }
                                            ?>
                                            <form id="pdt-form" name="pdt-form" method="post" enctype="multipart/form-data">
                                                <div class="tab-content">
                                                    
                                                    <div class="tab-pane active p-3 col-md-12" id="ds1" role="tabpanel">
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Product Name <span class="text-danger">*</span> </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="name" placeholder="Product Name" class="form-control pdt-name" value="<?php echo isset($fetch->title)?$fetch->title:'';?>">
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Meta Tag Title </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="title" placeholder="Meta Tag Title" class="form-control" value="<?php echo isset($fetch->meta_title)?$fetch->meta_title:'';?>">
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Meta Tag Description </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <textarea class="form-control" placeholder="Meta Tag Description" name="meta_description"><?php echo isset($fetch->meta_desc)?$fetch->meta_desc:'';?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Meta Tag Keywords </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <textarea class="form-control" placeholder="Meta Tag Keywords" name="keywords"><?php echo isset($fetch->meta_key)?$fetch->meta_key:'';?></textarea>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <button type="button" class="btn btn-danger waves-effect waves-light disabled pdt-continue1"> <i  class="mdi mdi-check-circle-outline"></i> Continue</button>
                                                    </div>
                                                    <div class="tab-pane cate-tab p-3 col-md-12" id="ds2" role="tabpanel">
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Barnd Name <span class="text-danger">*</span> </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                            <select class="form-control brand-name" name="brand">
                                                                <option value="">Select brand</option>
                                                                    <?php
                                                                    $a = mysqli_query($conn, "SELECT * FROM ss_brands ORDER BY brand ASC ");
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
                                                            <div class="col-md-2">
                                                                <span>Category Name <span class="text-danger">*</span> </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                            <select class="form-control cate-name" name="category">
                                                                <option value="">Select category</option>
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
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>GST Percentage  </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                 <input type="text" name="gst" placeholder="GST Percentage" class="form-control gst" value="<?php echo isset($fetch->gst)?$fetch->gst:'';?>">
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <button type="button" class="btn btn-danger waves-effect waves-light disabled pdt-continue2"> <i  class="mdi mdi-check-circle-outline"></i> Continue</button>
                                                    </div>
                                                    <div class="tab-pane price-tab p-3 col-md-12" id="ds3" role="tabpanel">
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Offer Price <span class="text-danger">*</span> </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="offerprice" placeholder="Offer Price" class="form-control oprice" value="<?php echo isset($fetch->oprice)?$fetch->oprice:'';?>">
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Min Qty <span class="text-danger">*</span> </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="min-qty" placeholder="Minimum Quantity" class="form-control mi-qty" value="<?php echo isset($fetch->min_qty)?$fetch->min_qty:'1';?>">
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Max Qty <span class="text-danger">*</span> </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="max-qty" placeholder="Maximum Quantity" class="form-control ma-qty" value="<?php echo isset($fetch->max_qty)?$fetch->max_qty:'';?>">
                                                            </div>
                                                        </div>
                                                       <!--  <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Extra Price (Egg) </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="extra" placeholder="Extra price (Egg)" class="form-control extra" value="<?php echo isset($fetch->extra)?$fetch->extra:'';?>">
                                                            </div>
                                                        </div> -->
                                                        <!-- <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Preparation Time (MIN) <span class="text-danger">*</span></span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" name="time" placeholder="Preparation Time (Enter Minutes)" class="form-control time" value="<?php echo isset($fetch->time)?$fetch->time:'';?>">
                                                            </div>
                                                        </div> -->
                                                        <hr>
                                                        <button type="button" class="btn btn-danger waves-effect waves-light disabled pdt-continue3"> <i  class="mdi mdi-check-circle-outline"></i> Continue</button>
                                                    </div>
                                                    <div class="tab-pane p-3 spci-tab col-md-12" id="ds4" role="tabpanel">
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Description <span class="text-danger">*</span>  </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <textarea class="form-control desc kudu" placeholder="Product Description" name="description"><?php echo isset($fetch->description)?$fetch->description:'';?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-2">
                                                                <span>Specifications <span class="text-danger">*</span> </span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <textarea class="form-control specifi" placeholder="Product Specifications" name="specifications"><?php echo isset($fetch->specifications)?$fetch->specifications:'';?></textarea>
                                                            </div>
                                                        </div>
                                                         <hr>
                                                        <button type="button" class="btn btn-danger waves-effect waves-light disabled pdt-continue4"> <i  class="mdi mdi-check-circle-outline"></i> Continue</button>
                                                    </div>
                                                    <div class="tab-pane p-3 img-tab col-md-12" id="ds5" role="tabpanel">
                                                        <div class="row clearfix mb20">
                                                            <div class="col-md-12"><strong>Images sholud be 500x500</strong></div>
                                                            <div class="col-md-2">
                                                                <?php $img1= isset($fetch->image1)?'../images/products/'.$fetch->image1:'images/document-image.png';?>
                                                                 <img  src="<?php echo $img1;?>" class="img-upload1">
                                                                <label class="file-upload btn btn-warning">
                                                                    Browse for file ... 
                                                                    <input id="file-input1" name="image1" type="file" class="file_input" data-id="1" />
                                                                </label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <?php $img2= isset($fetch->image2)?'../images/products/'.$fetch->image2:'images/document-image.png';?>
                                                                 <img src="<?php echo $img2;?>" class="img-upload2">
                                                                <label class="file-upload btn btn-warning">
                                                                    Browse for file ... 
                                                                    <input id="file-input2" name="image2" type="file" class="file_input" data-id="2" />
                                                                </label>
                                                                <?php if(isset($fetch->image2)): ?>
                                                                <div class="clearfix"></div>
                                                                <p id="deleteid2" data-img="2" class="deleteimg btn btn-danger col-md-12">Delete</p>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <?php $img3= isset($fetch->image3)?'../images/products/'.$fetch->image3:'images/document-image.png';?>
                                                                 <img src="<?php echo $img3;?>" class="img-upload3">
                                                                <label class="file-upload btn btn-warning">
                                                                    Browse for file ... 
                                                                    <input id="file-input3" name="image3" type="file" class="file_input" data-id="3"/>
                                                                </label>
                                                                <?php if(isset($fetch->image3)): ?>
                                                                <div class="clearfix"></div>
                                                                <p id="deleteid3" data-img="3" class="deleteimg btn btn-danger col-md-12">Delete</p>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <?php $img4= isset($fetch->image4)?'../images/products/'.$fetch->image4:'images/document-image.png';?>
                                                                <img  src="<?php echo $img4;?>" class="img-upload4">
                                                                <label class="file-upload btn btn-warning">
                                                                    Browse for file ... 
                                                                    <input id="file-input4" name="image4" type="file" class="file_input" data-id="4"/>
                                                                </label>
                                                                <?php if(isset($fetch->image4)): ?>
                                                                <div class="clearfix"></div>
                                                                <p id="deleteid4" data-img="4" class="deleteimg btn btn-danger col-md-12">Delete</p>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <?php $img5= isset($fetch->image5)?'../images/products/'.$fetch->image5:'images/document-image.png';?>
                                                                <img  src="<?php echo $img5;?>" class="img-upload5">
                                                                <label class="file-upload btn btn-warning">
                                                                    Browse for file ... 
                                                                    <input id="file-input5" name="image5" type="file" class="file_input" data-id="5"/>
                                                                </label>
                                                                <?php if(isset($fetch->image5)): ?>
                                                                <div class="clearfix"></div>
                                                                <p id="deleteid5" data-img="5" class="deleteimg btn btn-danger col-md-12">Delete</p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" class="method" name="method" value="<?php echo isset($_GET['type'])?$_GET['type']:'insert';?>">
                                                        <input type="hidden" class="pdtid" name="pdtid" value="<?php echo isset($_GET['id'])?$_GET['id']:'';?>">
                                                        <input type="hidden" id="updateid" name="updateid" value="<?php echo isset($_GET['id'])?$_GET['id']:'';?>">
                                                        <button type="submit" class="btn btn-danger waves-effect waves-light add-product"> <i  class="mdi mdi-check-circle-outline"></i><?php echo isset($_GET['type'])?'Update':'Add';?> Product</button>
                                                    </div>
                                                </div>
                                                <hr>
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
        <script src="assets/Ajax/ajax-product.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/kudu/kudu.css">
        <script type="text/javascript" src="assets/kudu/kudu.js"></script>
        <script type="text/javascript" src="assets/kudu/kudu.init.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/js/file-upload.js"></script>
        <script src="assets/js/app.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.deleteimg').click(function() 
                {
                    if (confirm('Are you sure...?')) 
                    {
                        var img= $(this).attr('data-img');
                        var updateid = $('#updateid').val();
                        $.ajax({
                            type: "POST",
                            beforeSend: function (x) {
                                $('.loading').css('visibility', 'visible');
                            },
                            url: "inc/image-delete.php",
                            data: {
                                "image" : img,
                                "updateid": updateid
                            },
                        }).done(function( response, status ) 
                        {
                            $('.img-upload'+img).attr('src', 'images/document-image.png');
                            $('.loading').css('visibility', 'hidden');
                            $('#deleteid'+img).remove();
                        });
                    }
                });
            });
        </script>
    </body>
</html>