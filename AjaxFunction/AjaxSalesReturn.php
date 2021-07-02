<?php
    session_start();
    include '../inc/config.php';

    if($_POST['method'] =='add-form')
    {
    	$importtype=$_POST['importtype'];
        $error = FALSE;

        $errors = array();
        $required = array('customer_id', 'so_no' ,'re_dno', 'importtype');
        if($importtype != 1)
        {
            array_push($required, "cid","pid","code");
        }
        foreach ($required as $field) 
        {
            if(empty($_POST[$field]))
            {
                $error = TRUE;
            }
        }
         //print_r($required);
        // exit;
        
        if($error)
        {
            $response['status']=false;
            $response['message']="<strong>Oops ! </strong>Please Fill All Fields.";
            echo json_encode($response);
            return;
        }
        else
        {
        	if($importtype == 1)
            {
                $so_no       = mysqli_real_escape_string($conn, trim($_POST['so_no']));
                $re_dno      = mysqli_real_escape_string($conn, trim($_POST['re_dno']));
                $customer_id = mysqli_real_escape_string($conn, trim($_POST['customer_id']));   
                $filename    = $_FILES["csvfile"]["name"];
                $val = explode(".",$filename);
                $k   = 0;
                $qty = 1;
                if($val[1] =="csv" )
                {
                    $fileData = file_get_contents($_FILES['csvfile']['tmp_name']);
                    if($fileData)
                    {
                        $arrDetails = array();
                        $datas = str_getcsv($fileData, "\n"); 
                        unset($datas[0]);
                        // print_r($datas);die;
                        $countqty=!empty($datas)?count($datas):0;
                        // print_r($datas);
                        foreach($datas as $rawRow)
                        {
                            $row = str_getcsv($rawRow, ",");
                            $code = $row[0];

                            $sel_1 = mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE code = '".$code."' AND delar_id = '".$customer_id."' ");
                            $row_1 = mysqli_num_rows($sel_1) > 0;
                            if($row_1)
                            {
                                $res_1 = mysqli_fetch_object($sel_1);

                                $sel_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `published` = '1' AND `id` = '".$res_1->product_id."'"));

                                $sel_4 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `published` = '1' AND `id` = '".$customer_id."'"));

                                $_b_dis  = $sel_3->oprice * $sel_3->allowance / 100;
                                
                                $_d_dis  = $sel_3->oprice * $sel_4->d_allowance / 100;

                                $_disco  = $_b_dis + $sel_3->sta + $_d_dis;

                                $_total  = $sel_3->oprice - $_disco;

                                $_tot    = $qty *  $_total;

                                $cre_lmt = $sel_4->avl_lmt + $_tot;

                                $upt_2 = mysqli_query($conn, "UPDATE `ss_customers` SET `avl_lmt` = '".$cre_lmt."' WHERE id = '".$customer_id."'");

                                $so_num = mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `ss_sales` WHERE `so_no` = '".$so_no."' AND `status` = '1' AND `published` = '1'"));

                                $qry_1 = mysqli_query($conn, "SELECT `id` FROM `ss_sales_return` WHERE `re_no` = '".$re_dno."' AND `published` = '1'");
                                $num_1 = mysqli_num_rows($qry_1);
                                if($num_1 > 0)
                                {
                                    $res_1 = mysqli_fetch_object($qry_1);
                                    $re_id = $res_1->id; 

                                    $sel_7 = mysqli_query($conn, "SELECT `pid` FROM ss_sales_return_details WHERE `re_id` = '".$re_id."' AND `so_no` = '".$so_no."' AND `pid` = '".$sel_3->id."'");

                                    $row_7 = mysqli_num_rows($sel_7);

                                    if($row_7 > 0)
                                    {
                                        $sel_8 = mysqli_fetch_object(mysqli_query($conn, "SELECT SUM(`qty`) AS `qty`, code FROM ss_sales_return_details WHERE `re_id` = '".$re_id."' AND `so_no` = '".$so_no."' AND `pid` = '".$sel_3->id."'"));

                                        $t_qty = $sel_8->qty + 1;

                                        $t_cde = $sel_8->code .','. $code;
                                        

                                        $ins_1 = mysqli_query($conn, "UPDATE `ss_sales_return_details` SET `code` = '".$t_cde."', `qty` = '".$t_qty."' WHERE `re_id` = '".$re_id."' AND `so_no` = '".$so_no."' AND `pid` = '".$sel_3->id."'");

                                        if($ins_2)
                                        {
                                            $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `delar_id`= NULL ,`delar_sales` = NULL ,`delar_status` = '1' WHERE `code` = '".$code."' ");

                                            $stock_sal_qy = mysqli_fetch_object(mysqli_query($conn, "SELECT qty FROM `ss_sales_return_details` WHERE `so_no` = '".$so_no."' AND `pid` = '".$sel_3->id."'  AND published = '1' AND status = '1'"));

                                            $stock_sal_re = $stock_sal_qy->qty - $qty;

                                            $stock_sal_up = mysqli_query($conn, "UPDATE `ss_sales_details` SET `qty`= '".$stock_sal_re."' WHERE `so_id` = '".$so_num->id."' AND `pid` = '".$sel_3->id."' ");
                                        }
                                    }
                                    else
                                    {
                                        $ins_2 = mysqli_query($conn, "INSERT INTO `ss_sales_return_details`(`re_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `qty`, `createdate`) VALUES ('".$re_id."', '".$so_no."', '".$customer_id."', '".$sel_3->cid."', '".$sel_3->id."', '".$code."', '".$qty."', NOW())");

                                        if($ins_2)
                                        {
                                            $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `delar_id`= NULL ,`delar_sales` = NULL ,`delar_status` = '1' WHERE `code` = '".$code."' ");

                                            $stock_sal_qy = mysqli_fetch_object(mysqli_query($conn, "SELECT qty FROM `ss_sales_return_details` WHERE `so_no` = '".$so_no."' AND `pid` = '".$sel_3->id."'  AND published = '1' AND status = '1'"));

                                            $stock_sal_re = $stock_sal_qy->qty - $qty;

                                            $stock_sal_up = mysqli_query($conn, "UPDATE `ss_sales_details` SET `qty`= '".$stock_sal_re."' WHERE `so_id` = '".$so_num->id."' AND `pid` = '".$sel_3->id."' ");
                                        }
                                    }
                                }
                                else
                                {
                                    $ins_1 = mysqli_query($conn, "INSERT INTO `ss_sales_return`(`userid`, `re_no`, `customer_id`, `createdate`) VALUES ('".$_SESSION['uid']."' , '".$re_dno."', '".$customer_id."', NOW())");
                    
                                    $re_id = mysqli_insert_id($conn);

                                    $ins_2 = mysqli_query($conn, "INSERT INTO `ss_sales_return_details`(`re_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `qty`, `createdate`) VALUES ('".$re_id."', '".$so_no."', '".$customer_id."', '".$sel_3->cid."', '".$sel_3->id."', '".$code."', '".$qty."', NOW())");

                                    if($ins_2)
                                    {	
                                    	$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `delar_id`= NULL ,`delar_sales` = NULL ,`delar_status` = '1' WHERE `code` = '".$code."' ");

                                        $stock_sal_qy = mysqli_fetch_object(mysqli_query($conn, "SELECT qty FROM `ss_sales_return_details` WHERE `so_no` = '".$so_no."' AND `pid` = '".$sel_3->id."'  AND published = '1' AND status = '1'"));

                                        $stock_sal_re = $stock_sal_qy->qty - $qty;

                                        $stock_sal_up = mysqli_query($conn, "UPDATE `ss_sales_details` SET `qty`= '".$stock_sal_re."' WHERE `so_id` = '".$so_num->id."' AND `pid` = '".$sel_3->id."' ");
                                    }
                                }
                            }
                            $k++;
                        }
                    }
                    if($k)
                    {   

                        $response['status']=true;
                        $response['message']="<strong>Your Sales Added Successfully.";
                        echo json_encode($response);
                    }
                    else
                    {
                        $response['status']=false;
                        $response['message']="<strong>Oops ! </strong>Your Sales Can't Added.";
                        echo json_encode($response);
                    }
                }
            }
            else
            {
            	$so_no       = mysqli_real_escape_string($conn, trim($_POST['so_no']));
                $re_dno      = mysqli_real_escape_string($conn, trim($_POST['re_dno']));
                $customer_id = mysqli_real_escape_string($conn, trim($_POST['customer_id']));
                $cid         = mysqli_real_escape_string($conn, trim($_POST['cid']));
                $pid         = mysqli_real_escape_string($conn, trim($_POST['pid']));
                $code        = $_POST['code'];
                $qty = count($code);

                $sel_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `published` = '1' AND `id` = '".$pid."'"));

            	$sel_4 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `published` = '1' AND `id` = '".$customer_id."'"));

            	$_b_dis  = $sel_3->oprice * $sel_3->allowance / 100;
                
                $_d_dis  = $sel_3->oprice * $sel_4->d_allowance / 100;

                $_disco  = $_b_dis + $sel_3->sta + $_d_dis;

                $_total  = $sel_3->oprice - $_disco;

                $_tot    = $qty *  $_total;

                $cre_lmt = $sel_4->avl_lmt + $_tot;

                $upt_2 = mysqli_query($conn, "UPDATE `ss_customers` SET `avl_lmt` = '".$cre_lmt."' WHERE id = '".$customer_id."'");

                $so_num = mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `ss_sales` WHERE `so_no` = '".$so_no."' AND `status` = '1' AND `published` = '1'"));

                $qry_1 = mysqli_query($conn, "SELECT `id` FROM `ss_sales_return` WHERE `re_no` = '".$re_dno."' AND `published` = '1'");
                $num_1 = mysqli_num_rows($qry_1);
                if($num_1 > 0)
                { 
                	$res_1 = mysqli_fetch_object($qry_1);
                    $re_id = $res_1->id;

                    $ins_2 = mysqli_query($conn, "INSERT INTO `ss_sales_return_details`(`re_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `qty`, `createdate`) VALUES ('".$re_id."', '".$so_no."', '".$customer_id."', '".$cid."', '".$pid."', '".implode(",", $code)."', '".$qty."', NOW())");

                    if($ins_2)
                    {
                    	for($i = 0 ; $i < $qty ; $i++)
                        {
                            $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `delar_id`= NULL ,`delar_sales` = NULL ,`delar_status` = '1' WHERE `code` = '".$code[$i]."' ");
                        }

                        $stock_sal_qy = mysqli_fetch_object(mysqli_query($conn, "SELECT qty FROM `ss_sales_return_details` WHERE `so_no` = '".$so_no."' AND `pid` = '".$pid."'  AND published = '1' AND status = '1'"));

                        $stock_sal_re = $stock_sal_qy->qty - $qty;

                        $stock_sal_up = mysqli_query($conn, "UPDATE `ss_sales_details` SET `qty`= '".$stock_sal_re."' WHERE `so_id` = '".$so_num->id."' AND `pid` = '".$pid."' ");

                        $response['status']=true;
                        $response['message']="<strong>Well done ! </strong>Your Sales Added Successfully.";
                        echo json_encode($response);
                    }
                    else
                    {
                        $response['status']=false;
                        $response['message']="<strong>Oops ! </strong>Your Sales Can't Added.";
                        echo json_encode($response);
                    }
                }
                else
                {

                    $ins_1 = mysqli_query($conn, "INSERT INTO `ss_sales_return`(`userid`, `re_no`, `customer_id`, `createdate`) VALUES ('".$_SESSION['uid']."' , '".$re_dno."', '".$customer_id."', NOW())");
                    
                    $re_id = mysqli_insert_id($conn);

                    $ins_2 = mysqli_query($conn, "INSERT INTO `ss_sales_return_details`(`re_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `qty`, `createdate`) VALUES ('".$re_id."', '".$so_no."', '".$customer_id."', '".$cid."', '".$pid."', '".implode(",", $code)."', '".$qty."', NOW())");

                    if($ins_2)
                    {
                    	for($i = 0 ; $i < $qty ; $i++)
                        {
                            $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `delar_id`= NULL ,`delar_sales` = NULL ,`delar_status` = '1' WHERE `code` = '".$code[$i]."' ");
                        }

                        $stock_sal_qy = mysqli_fetch_object(mysqli_query($conn, "SELECT qty FROM `ss_sales_return_details` WHERE `so_no` = '".$so_no."' AND `pid` = '".$pid."'  AND published = '1' AND status = '1'"));

                        $stock_sal_re = $stock_sal_qy->qty - $qty;

                        $stock_sal_up = mysqli_query($conn, "UPDATE `ss_sales_details` SET `qty`= '".$stock_sal_re."' WHERE `so_id` = '".$so_num->id."' AND `pid` = '".$pid."' ");

                        $response['status']=true;
                        $response['message']="<strong>Well done ! </strong>Your Sales Added Successfully.";
                        echo json_encode($response);
                    }
                    else
                    {
                        $response['status']=false;
                        $response['message']="<strong>Oops ! </strong>Your Sales Can't Added.";
                        echo json_encode($response);
                    }
                }
            }
        }
    }
?>