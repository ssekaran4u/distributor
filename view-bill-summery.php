<?php 
    session_start();
    include 'inc/config.php';
    isAdmin();
    $order = !empty($_GET['id'])?$_GET['id']:'';

    $addressdetail = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_sales`  WHERE `id` = '".$order."' LIMIT 1"));

    $so_no      = !empty($addressdetail->so_no)?$addressdetail->so_no:'---';
    $userid     = !empty($addressdetail->userid)?$addressdetail->userid:'---';
    $cus_id     = !empty($addressdetail->customer_id)?$addressdetail->customer_id:'---';
    $order_no   = !empty($addressdetail->order_no)?$addressdetail->order_no:'---';
    $dated      = !empty($addressdetail->dated)?date('d-m-Y', strtotime($addressdetail->dated)):'---';
    $docu_no    = !empty($addressdetail->docu_no)?$addressdetail->docu_no:'---';
    $despatched = !empty($addressdetail->despatched)?$addressdetail->despatched:'---';

    $qry_1 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE id = '".$userid."'"));

    $dis_owner      = !empty($qry_1->owner)?$qry_1->owner:'---';
    $dis_store      = !empty($qry_1->store)?$qry_1->store:'---';
    $dis_address    = !empty($qry_1->customeraddress)?$qry_1->customeraddress:'---';
    $dis_mobile     = !empty($qry_1->mobile)?$qry_1->mobile:'---';
    $dis_gst_number = !empty($qry_1->gst)?$qry_1->gst:'---';
    $dis_tcs_type   = !empty($qry_1->tcs_type)?$qry_1->tcs_type:'---';
    $dis_tcs_no     = !empty($qry_1->tcs_no)?$qry_1->tcs_no:'---';
    $dis_state      = !empty($qry_1->state)?$qry_1->state:'---';

    $qry_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE id = '".$cus_id."'"));

    $name       = !empty($qry_2->name)?$qry_2->name:'---';
    $pincode    = !empty($qry_2->pincode)?$qry_2->pincode:'---';
    $store      = !empty($qry_2->store)?$qry_2->store:'---';
    $address    = !empty($qry_2->address)?$qry_2->address:'---';
    $mobile     = !empty($qry_2->mobile)?$qry_2->mobile:'---';
    $gst_number = !empty($qry_2->gst_no)?$qry_2->gst_no:'---';
    $email      = !empty($qry_2->email)?$qry_2->email:'---';
    $tcs_type   = !empty($qry_2->tcs_type)?$qry_2->tcs_type:'---';
    $tcs_no     = !empty($qry_2->tcs_no)?$qry_2->tcs_no:'---';
    $state      = !empty($qry_2->state)?$qry_2->state:'---';
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
            .addre_del{
                padding: 9px;
                border: 1px solid #19151554;
            }
            .user_order_add
            {
                font-size: 15px;
            }
            .shi_adr
            {
                width: 22%;
                margin:0px;
            }
            .disp_addrs
            {
                display: flex;
            }
            .padd_lef_rgt table th
            {
                padding:10px;
            }
            .borderclr
            {
                border:1px solid #80808047;
            }
            .pdf_btn
            {
                position: relative;
                left: 77%;
            }
            label {
                margin-bottom: 0;
            }
            #_h5 {
                margin: 0;
                padding-bottom: 10px;
                font-size: 20px;
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
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="card m-b-20">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="invoice-title">
                                                        <h4 class="pull-right font-16"><strong>Order# <?php echo $so_no; ?></strong></h4>
                                                        <h3 class="mt-0">
                                                            <img src="assets/images/logo.jpg" alt="logo" height="26">
                                                        </h3>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                                            <address>
                                                                <strong>Shipped To:</strong><br>
                                                                <div class="col-md-12 disp_addrs ">
                                                                    <span class="user_order_add"><?php echo $dis_owner;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs ">
                                                                    <span class="user_order_add"><?php echo $dis_store;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    <span class="user_order_add"><?php echo $dis_address; ?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    <span class="user_order_add"><?php echo $dis_mobile;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    <span class="user_order_add">GSTIN\UIN: <?php echo $dis_gst_number; ?></span>
                                                                </div>
                                                                <?php
                                                                    if($dis_tcs_type == 2)
                                                                    {
                                                                ?>
                                                                    <div class="col-md-12 disp_addrs">
                                                                        <span class="user_order_add">TCS No: <?php echo $dis_tcs_no; ?></span>
                                                                    </div>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </address>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                                            <address>
                                                                <strong>Shipped To:</strong><br>
                                                                <div class="col-md-12 disp_addrs ">
                                                                    <span class="user_order_add"><?php echo $name;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    <span class="user_order_add"><?php echo $address; ?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    <span class="user_order_add"><?php echo $mobile;?></span>
                                                                </div>
                                                                <div class="col-md-12 disp_addrs">
                                                                    <span class="user_order_add">GSTIN\UIN: <?php echo $gst_number; ?></span>
                                                                </div>
                                                                <?php
                                                                    if($tcs_type == 2)
                                                                    {
                                                                ?>
                                                                    <div class="col-md-12 disp_addrs">
                                                                        <span class="user_order_add">TCS No: <?php echo $tcs_no; ?></span>
                                                                    </div>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </address>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Buyer's Order No:</strong><br>
                                                                <?php echo $order_no; ?>
                                                                
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 text-center m-t-30">
                                                            <address>
                                                                <strong>Dated:</strong><br>
                                                                <?php echo $dated; ?>
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Eway Bill No :</strong><br>
                                                                <?php echo $docu_no; ?>
                                                            </address>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12 m-t-30 text-center">
                                                            <address>
                                                                <strong>Others:</strong><br>
                                                                <?php echo $despatched; ?>
                                                            </address>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-default">
                                                                <div class="p-2">
                                                                    <h3 class="panel-title font-20"><strong>Order Summary</strong></h3>
                                                                </div>
                                                                <?php
    $qry_3 = mysqli_query($conn, "SELECT sa.title AS c_title, sb.so_no AS so_no, sb.pid AS pid, sb.qty AS qty, sb.price AS price, sb.gst AS gst, sb.allowance AS allowance, sb.sta AS sta, sb.code AS code, sb.d_allowance AS d_allowance, sb.discount AS discount, sb.value_pri AS value_pri, sc.title AS p_title, sc.description AS description, sc.hsn AS hsn, sc.oprice AS oprice, sb.code AS code, sb.billstatus AS billstatus  FROM ss_category sa JOIN ss_sales_details sb ON sa.id = sb.cid JOIN ss_items sc ON sb.pid = sc.id WHERE sb.so_id = '".$order."' AND sb.status = '1'");

    $cou_3 = mysqli_num_rows($qry_3);

    ?>
    <div class="col-md-12">
            <div class="col-md-2" style="padding: 0px;margin-top: 6px;">
                <label>Product Details :</label>
            </div>  
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 overflow-div padd_lef_rgt">
            <div class="table-responsive">
                <table border="1" class="table borderclr table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">S.No</th>
                            <th rowspan="2">Description</th>
                            <th rowspan="2">HSN</th>
                            <th rowspan="2">DP</th>
                            <th rowspan="2">Qty</th>
                            <th rowspan="2">Additional discount</th>
                            <th rowspan="2">Taxable Value</th>
                            <?php
                                if($state == 33)
                                {
                                    ?>
                                        <th colspan="2" style="text-align: center;">CGST</th>
                                        <th colspan="2" style="text-align: center;">SGST</th>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <th colspan="2" style="text-align: center;">IGST</th>
                                    <?php
                                }
                            ?>
                            <th rowspan="2">Price</th>  
                        </tr>
                        <tr>
                            <?php
                                if($state == 33)
                                {
                                    ?>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    <?php
                                }
                            ?>
                        </tr>
                    </thead>
                    <?php
                        if($cou_3 > 0)
                        {
                            ?>
                            <tbody>
                                <?php
                                    $sub_tot = 0;
                                    $sub_to = 0;
                                    $srlno = 1;
                                    $qty = 0;
                                    $price = 0;
                                    $allowance =0;
                                    $sta=0;
                                    $_sta=0;
                                    $gst_ = 0;
                                    $d_allowance=0;
                                    $_netdis=0;
                                    $discount=0;
                                    $netval=0;
                                    $netpay = 0;
                                    $netdis = 0;
                                    $gstper =0;
                                    $discount =0;
                                    $gst_amt=0;
                                    $overallgstprice=0;
                                    $allowance_val=0;
                                    $d_allowance_val=0;
                                    $_dp=0;
                                    $_total=0;
                                    $gst_val=0;
                                    $cal_gst=0;
                                    $_val=0;
                                    $sub_to_=0;
                                    $total_val=0;
                                    $tax_val=0;
                                    $tcs_val=0;
                                    $_qty=0;
                                    $net_total=0;
                                    $rond_tot=0;
                                    $lst_tot=0;

                                    while($row_3 = mysqli_fetch_array($qry_3)) 
                                    {
                                        $qty_val      = !empty($row_3['qty'])?$row_3['qty']:'0';
                                        $price_val    = !empty($row_3['price'])?$row_3['price']:'0';
                                        $e_allowance  = !empty($row_3['allowance'])?$row_3['allowance']:'0';
                                        $de_allowance = !empty($row_3['d_allowance'])?$row_3['d_allowance']:'0';
                                        $sta_val      = !empty($row_3['sta'])?$row_3['sta']:'0';
                                        $discount     = !empty($row_3['discount'])?$row_3['discount']:'0';
                                        $billstatus   = !empty($row_3['billstatus'])?$row_3['billstatus']:'0';
                                        $gst_val      = !empty($row_3['gst'])?$row_3['gst']:'0';
                                        $code_val     = !empty($row_3['code'])?$row_3['code']:'0';
                                        $product_id   = !empty($row_3['pid'])?$row_3['pid']:'0';
                                        $so_no        = !empty($row_3['so_no'])?$row_3['so_no']:'0';

                                        $code  = mysqli_fetch_object(mysqli_query($conn, "SELECT code FROM ss_item_stk WHERE id = '".$code_val."' AND published = '1'"));

                                        $serial_no = '';

                                        $serial = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE `product_id` = '".$product_id."' AND `delar_sales` = '".$so_no."' AND published = '1'");
                                            
                                        while($res = mysqli_fetch_object($serial))
                                        {
                                            $serial_no .= $res->code.' ,';
                                        }

                                        $serial_val = substr_replace($serial_no, "", -1);

                                        $_qty += $qty_val;

                                        $allowance = $price_val * $e_allowance / 100;

                                        $allowance_val = $qty_val * round($allowance);

                                        $d_allowance = $price_val * $de_allowance / 100;

                                        $d_allowance_val = $qty_val * round($d_allowance);

                                        $discount_val = $qty_val * round($discount);

                                        $_sta = $qty_val * round($sta_val);

                                        $netdis = $allowance_val + $_sta + $d_allowance_val + $discount_val;

                                        $sub_to = $qty_val * $price_val;

                                        $sub_tot += $sub_to;

                                        $total = $sub_to - $netdis;

                                        $_gst = "1.".$gst_val;

                                        $state_gst = $gst_val / 2;

                                        $_val = $total / $_gst; 

                                        if($billstatus == 1)
                                        {
                                            $tax_val += $_val; 
                                            
                                            $gstper = $_val * $gst_val / 100;

                                            $total_val = $_val + $gstper;
                                            
                                            $_total += $total;

                                            $cal_gst = $gstper / 2;

                                            $gst_ += $gstper;

                                            $gst_val = $gst_/2;

                                            $sub_to_ = $_val + $gst_;

                                            $netval += $sub_to_;

                                            $gst_amt = $gst_val / 2;

                                            if($tcs_type == '2')
                                            {
                                                $_tcs = $total_val * 0.1 / 100;
                                            }
                                            else
                                            {
                                                $_tcs = 0;
                                            }

                                             if($tcs_type == '2')
                                            {
                                                $tcs_val += $_tcs; 
                                            }
                                            else
                                            {
                                                $tcs_val = 0;
                                            }

                                            $net_total = $tax_val + $gst_ + $tcs_val;
                                            
                                            $lst_tot = round($net_total);

                                            $rond_tot = $lst_tot - $net_total;
                                        }
                                        else
                                        {
                                            if($tcs_type == '2')
                                            {
                                                $_tcs = $_val * 0.1 / 100;
                                            }
                                            else
                                            {
                                                $_tcs = 0;
                                            }

                                            $tax_val += $_val;
                                      
                                            if($tcs_type == '2')
                                            {
                                                $tcs_val += $_tcs; 
                                            }
                                            else
                                            {
                                                $tcs_val = 0;
                                            }

                                            $gstper = $_val * $gst_val / 100;
                                      
                                            $total_val = $_val + $gstper;

                                            $_total += $total;

                                            $net_total = $_total + $tcs_val;

                                            $lst_tot = round($net_total);

                                            $rond_tot = $lst_tot - $net_total;

                                            $total_val = $_val + $gstper;

                                            $cal_gst = $gstper / 2;

                                            $gst_ += $gstper;

                                            $gst_val = $gst_/2;

                                            $sub_to_ = $_val + $gst_;

                                            $netval += $sub_to_;

                                            $gst_amt = $gst_val / 2;
                                        }

                                        ?>
                                        <tr>
                                            <td><?php echo $srlno++; ?></td>
                                            <td><?php echo !empty($row_3['code'])?$row_3['code']:'';?> 
                                            <td><?php echo !empty($row_3['hsn'])?$row_3['hsn']:'';?></td>
                                            <td><?php echo !empty($row_3['price'])?$row_3['price']:'';?></td>
                                            <td><?php echo !empty($row_3['qty'])?$row_3['qty']:'';?></td>
                                            <td><?php echo round($discount);?></td>
                                            <td><?php echo number_format($_val, 2);?></td>
                                            <?php
                                                if($state == 33)
                                                {
                                                    ?>
                                                        <td><?php echo round($state_gst);?> %</td>
                                                        <td><?php echo number_format($cal_gst, 2);?></td>
                                                        <td><?php echo round($state_gst);?> %</td>
                                                        <td><?php echo number_format($cal_gst, 2);?></td>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                        <td><?php echo round($gst_val);?> %</td>
                                                        <td><?php echo number_format($gstper, 2);?></td>
                                                    <?php
                                                }
                                            ?>
                                            <td><?php echo round($total_val);?></td>
                                        </tr>
                                        <tr><td colspan="15">Serial No: <?php echo $serial_val; ?></td></tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <?php
                                    if($state == 33)
                                    {
                                        $colspan = '9';
                                    }
                                    else
                                    {
                                        $colspan = '7';
                                    }
                                ?>
                                <tr>
                                    <th rowspan ="6"  colspan="<?php echo $colspan; ?>"></th>
                                    <th colspan="2" class="text-right">Qty</th>
                                    <th class="text-right"><?php echo $_qty; ?></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Sub Total</th>
                                    <th class="text-right"><?php echo number_format($tax_val, 2); ?></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">GST %</th>
                                    <th class="text-right"><?php echo number_format($gst_, 2); ?></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">TCS Value</th>
                                    <th class="text-right"><?php echo number_format($tcs_val, 2); ?></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Round off</th>
                                    <th class="text-right"><?php echo number_format($rond_tot, 2); ?></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Net Total</th>
                                    <th class="text-right"><?php echo number_format($lst_tot, 2); ?></th>
                                </tr>
                            </tfoot>
                            <?php
                        }
                        else
                        {
                            ?>
                            <tbody>
                                <tr>
                                    <th colspan="15">No Records Founded.</th>
                                </tr>
                            </tbody>
                            <?php
                        }
                    ?>
                </table>
            </div>
        </div>
    <?php
                                                                ?>

                                                                <?php
                                                                    if($cou_3 > 0)
                                                                    {
                                                                        ?>
                                                                            <div class="d-print-none">
                                                                                <div class="pull-right">
                                                                                    <a  href="invoice/example_006.php?id=<?php echo $order;?>" target="_blank" class="btn btn-success waves-effect waves-light "></i>Print</a>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        <script src="assets/js/file-upload.js"></script>
        <script src="assets/js/app.js"></script>
    </body>
</html>
