<?php
session_start();
include '../inc/config.php';
// $branch = isAdminDetails($conn);
	
// print_r($_POST); exit();

if($_POST['method'] =='add-form')
{
	$importtype=$_POST['importtype'];
	$error = FALSE;

	$errors = array();
    $required = array('customer_id', 'bal_lmt', 'dated', 'importtype');
    if($importtype != 1)
    {
    	array_push($required, "cid","pid","code","price");
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
			$customer_id = mysqli_real_escape_string($conn, trim($_POST['customer_id']));
          	$tcs_type    = mysqli_real_escape_string($conn, trim($_POST['tcs_type']));
          	$cus_type    = mysqli_real_escape_string($conn, trim($_POST['cus_type']));
			$order_no    = mysqli_real_escape_string($conn, trim($_POST['order_no']));
			$dated       = mysqli_real_escape_string($conn, trim($_POST['dated']));
			$docu_no     = mysqli_real_escape_string($conn, trim($_POST['docu_no']));
			$despatched  = mysqli_real_escape_string($conn, trim($_POST['despatched']));
    		$filename = $_FILES["csvfile"]["name"];
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
					    // echo $code;
					    $sel_1 = mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE code = '".$code."' AND status = '1'");
						$row_1 = mysqli_num_rows($sel_1) > 0;
						if($row_1)
						{
							$res_1 = mysqli_fetch_object($sel_1);

							// $upt_1 = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."' , `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0' WHERE `code` = '".$code."' ");

							if($cus_type == '2')
							{	
								$upt_1 = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."' , `sales_date` = '".date('Y-m-d', strtotime($dated))."', `bill_status` = '0', `status` = '0' WHERE `code` = '".$code."' ");
							}
							else
							{	
								$upt_1 = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."' , `sales_date` = '".date('Y-m-d', strtotime($dated))."', `bill_status` = '0', `status` = '0', `delar_status` = '0' WHERE `code` = '".$code."' ");
							}

							// $sel_2 = mysqli_query($conn, "SELECT * FROM `ss_distributor_inv_details` WHERE `published` = '1' AND status = '1' AND `so_no` = '".$so_no."' AND pid = '".$res_1->product_id."'");

							// $row_2 = mysqli_num_rows($sel_2);
							// if($row_2==0)
							// {
								$sel_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `published` = '1' AND `id` = '".$res_1->product_id."'"));

								$sel_4 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `deleted` = '1' AND `id` = '".$customer_id."'"));

								$_b_dis = $sel_3->oprice * $sel_3->allowance / 100;
								
								$_d_dis = $sel_3->oprice * $sel_4->d_allowance / 100;

								$_disco = $_b_dis + $sel_3->sta + $_d_dis;

								$_total = $sel_3->oprice - $_disco;

								$_tot   = $qty *  $_total;
                          
                          		if($tcs_type == '2')
                                {
                                    $_tcs     = $_tot * 0.1 / 100;
                                    $last_tot = $_tot + $_tcs;
                                }
                                else
                                {
                                    $_tcs     = 0;
                                    $last_tot = $_tot + $_tcs;
                                }

								if($sel_4->avl_lmt > $last_tot)
								{
									$cre_lmt = $sel_4->avl_lmt - $last_tot;

									$upt_2 = mysqli_query($conn, "UPDATE `ss_distributors` SET `avl_lmt` = '".$cre_lmt."' WHERE id = '".$customer_id."'");

									$sel_5 = mysqli_query($conn, "SELECT `id` FROM `ss_distributor_invoice` WHERE `so_no` = '".$so_no."' AND `published` = '1'");

									$row_3 = mysqli_num_rows($sel_5);

									if($row_3 > 0)
									{

										$res_2 = mysqli_fetch_object($sel_5);
										$so_id = $res_2->id;

										$sel_7 = mysqli_query($conn, "SELECT `pid` FROM ss_distributor_inv_details WHERE `so_id` = '".$so_id."' AND `pid` = '".$sel_3->id."'");

										$row_7 = mysqli_num_rows($sel_7);
										if($row_7 > 0)
										{	
											$sel_8 = mysqli_fetch_object(mysqli_query($conn, "SELECT SUM(`qty`) AS `qty` FROM ss_distributor_inv_details WHERE `so_id` = '".$so_id."' AND `pid` = '".$sel_3->id."'"));

											$t_qty = $sel_8->qty + 1;
											$t_tot = $t_qty * $_total;
                                          	
                                          	if($tcs_type == '2')
                                            {
                                                $_tcs     = $_tot * 0.1 / 100;
                                                $last_tot = $_tot + $_tcs;
                                            }
                                            else
                                            {
                                                $_tcs     = 0;
                                                $last_tot = $_tot + $_tcs;
                                            }

											$ins_1 = mysqli_query($conn, "UPDATE `ss_distributor_inv_details` SET `qty` = '".$t_qty."', `total_cost` = '".$last_tot."' WHERE `so_id` = '".$so_re."' AND `pid` = '".$sel_3->id."'");
										}
										else
										{	
											$ins_1 = mysqli_query($conn, "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `total_cost`, `createdate`) VALUES ('".$so_re."','".$so_no."','".$customer_id."','".$sel_3->cid."','".$sel_3->id."', '".$sel_3->description."','".$sel_3->hsn."','".$qty."','".$sel_3->oprice."','".$sel_3->gst."','".$sel_3->allowance."','".$sel_3->sta."','".$sel_4->d_allowance."', '".$last_tot."',NOW())");
										}

										// $sel_6 = mysqli_fetch_object(mysqli_query($conn, "SELECT extra FROM `ss_items` WHERE id = '".$sel_3->id."' AND published = '1' AND status = '1'"));

										$stock_re = $sel_3->extra - $qty;

										$upt_3 = mysqli_query($conn, "UPDATE `ss_items` SET `extra`= '".$stock_re."' WHERE id = '".$sel_3->id."' ");
									}
									else
									{
										$ins_2 = mysqli_query($conn, "INSERT INTO `ss_distributor_invoice`(`userid`,`so_no`, `customer_id`, `tcs_type`, `order_date`, `order_no`, `dated`, `docu_no`, `despatched`, `acad_id`, `createdate`) VALUES ('".$_SESSION['uid']."','".$so_no."','".$customer_id."', '".$tcs_type."', '".date('Y-m-d')."','".$order_no."','".date('Y-m-d', strtotime($dated))."','".$docu_no."', '".$despatched."', '".$_SESSION['acad_year']."', NOW())");
										$so_re = mysqli_insert_id($conn);

										$ins_3 = mysqli_query($conn, "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `total_cost`, `createdate`) VALUES ('".$so_re."','".$so_no."','".$customer_id."','".$sel_3->cid."','".$sel_3->id."', '".$sel_3->description."','".$sel_3->hsn."','".$qty."','".$sel_3->oprice."','".$sel_3->gst."','".$sel_3->allowance."','".$sel_3->sta."','".$sel_4->d_allowance."', '".$last_tot."',NOW())");

										// $sel_6 = mysqli_fetch_object(mysqli_query($conn, "SELECT extra FROM `ss_items` WHERE id = '".$sel_3->id."' AND published = '1' AND status = '1'"));

										$stock_re = $sel_3->extra - $qty;

										$upt_3 = mysqli_query($conn, "UPDATE `ss_items` SET `extra`= '".$stock_re."' WHERE id = '".$sel_3->id."' ");
									}
								}
							// }
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
			$so_no         = mysqli_real_escape_string($conn, trim($_POST['so_no']));
			// $brand_id      = mysqli_real_escape_string($conn, trim($_POST['brand_id']));
			$customer_id   = mysqli_real_escape_string($conn, trim($_POST['customer_id']));
            $tcs_type      = mysqli_real_escape_string($conn, trim($_POST['tcs_type']));
            $cus_type      = mysqli_real_escape_string($conn, trim($_POST['cus_type']));
			// $delivery_nt   = mysqli_real_escape_string($conn, trim($_POST['delivery_nt']));
			// $payment       = mysqli_real_escape_string($conn, trim($_POST['payment']));
			// $supplier_ref  = mysqli_real_escape_string($conn, trim($_POST['supplier_ref']));
			// $other_ref     = mysqli_real_escape_string($conn, trim($_POST['other_ref']));
			$order_no      = mysqli_real_escape_string($conn, trim($_POST['order_no']));
			$dated         = mysqli_real_escape_string($conn, trim($_POST['dated']));
			$docu_no       = mysqli_real_escape_string($conn, trim($_POST['docu_no']));
			// $delivery_date = mysqli_real_escape_string($conn, trim($_POST['delivery_date']));
			$despatched    = mysqli_real_escape_string($conn, trim($_POST['despatched']));
			// $destination   = mysqli_real_escape_string($conn, trim($_POST['destination']));
			// $terms         = mysqli_real_escape_string($conn, trim($_POST['terms']));
			$cid           = mysqli_real_escape_string($conn, trim($_POST['cid']));
			$pid           = mysqli_real_escape_string($conn, trim($_POST['pid']));
			$code          = $_POST['code'];
			$description   = mysqli_real_escape_string($conn, trim($_POST['description']));
			// $qty           = mysqli_real_escape_string($conn, trim($_POST['qty']));
			$price         = mysqli_real_escape_string($conn, trim($_POST['price']));
			$allowance     = mysqli_real_escape_string($conn, trim($_POST['allowance']));
			$sta           = mysqli_real_escape_string($conn, trim($_POST['sta']));
			$d_allowance   = mysqli_real_escape_string($conn, trim($_POST['d_allowance']));
			$discount      = mysqli_real_escape_string($conn, trim($_POST['discount']));
			$gst           = mysqli_real_escape_string($conn, trim($_POST['gst']));
			// $price_val     = mysqli_real_escape_string($conn, trim($_POST['price_val']));
			$avl_lmt       = mysqli_real_escape_string($conn, trim($_POST['avl_lmt']));
			$hsn           = mysqli_real_escape_string($conn, trim($_POST['hsn']));

			$qty = count($code);
			// print_r($code); 

			// for($i = 0 ; $i < $qty ; $i++)
			// {
			// 	if($cus_type == '2')
			// 	{
			// 		echo "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0' WHERE `id` = '".$code[$i]."' ";
			// 		// $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0' WHERE `id` = '".$code[$i]."' ");
			// 	}
			// 	else
			// 	{	
			// 		echo "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0', `delar_status` = '0' WHERE `id` = '".$code[$i]."' ";
			// 		// $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0', `delar_status` = '0' WHERE `id` = '".$code[$i]."' ");
			// 	}
			// }

			// exit();

			$exits = "SELECT * FROM `ss_distributor_inv_details` WHERE `published` = '1' AND status = '1' AND `so_no` = '".$so_no."' AND pid = '".$pid."'";
			$data = mysqli_query($conn, $exits);
			$rsrow = mysqli_num_rows($data);
			if($rsrow==0)
			{
				$_b_dis = $price * $allowance / 100;

				$_d_dis = $price * $d_allowance / 100;

				$_disco = $_b_dis + $sta + $_d_dis + $discount;

				$_total = $price - $_disco;

				$_tot   = $qty *  $_total;
              
              	if($tcs_type == '2')
                {
                    $_tcs     = $_tot * 0.1 / 100;
                    $last_tot = $_tot + $_tcs;
                }
                else
                {
                    $_tcs     = 0;
                    $last_tot = $_tot + $_tcs;
                }

				if($avl_lmt > $last_tot)
				{
					$cre_lmt = $avl_lmt - $last_tot;

					$qry_2 = mysqli_query($conn, "UPDATE `ss_distributors` SET `avl_lmt` = '".$cre_lmt."' WHERE id = '".$customer_id."'");

					$qry_1 = mysqli_query($conn, "SELECT `id` FROM `ss_distributor_invoice` WHERE `so_no` = '".$so_no."' AND `published` = '1'");
					$num_1 = mysqli_num_rows($qry_1);
					if($num_1 > 0)
					{
						$res_1 = mysqli_fetch_object($qry_1);
						$so_id = $res_1->id;

						$ins_1 = "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `discount`, `total_cost`, `createdate`) VALUES ('".$so_id."','".$so_no."','".$customer_id."','".$cid."','".$pid."', '".$description."','".$hsn."','".$qty."','".$price."','".$gst."','".$allowance."','".$sta."','".$d_allowance."','".$discount."', '".$last_tot."',NOW())";
						if(mysqli_query($conn, $ins_1))
						{	

							for($i = 0 ; $i < $qty ; $i++)
							{
								// echo $string_version = implode(',', $code);
								// $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0' WHERE `id` = '".$code[$i]."' ");

								if($cus_type == '2')
								{
									$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0', `bill_status` = '0' WHERE `id` = '".$code[$i]."' ");
								}
								else
								{
									$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0', `bill_status` = '0', `delar_status` = '0' WHERE `id` = '".$code[$i]."' ");
								}
							}
							
							$stock_qy = mysqli_fetch_object(mysqli_query($conn, "SELECT extra FROM `ss_items` WHERE id = '".$pid."' AND published = '1' AND status = '1'"));

							$stock_re = $stock_qy->extra - $qty;

							$stock_up = mysqli_query($conn, "UPDATE `ss_items` SET `extra`= '".$stock_re."' WHERE id = '".$pid."' ");

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
						$ins_2 = mysqli_query($conn, "INSERT INTO `ss_distributor_invoice`(`userid`,`so_no`, `customer_id`, `tcs_type`, `order_date`, `order_no`, `dated`, `docu_no`, `despatched`, `acad_id`, `createdate`) VALUES ('".$_SESSION['uid']."','".$so_no."','".$customer_id."', '".$tcs_type."', '".date('Y-m-d')."','".$order_no."','".date('Y-m-d', strtotime($dated))."','".$docu_no."','".$despatched."', '".$_SESSION['acad_year']."', NOW())");
						$so_re = mysqli_insert_id($conn);

						$ins_3 = "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `discount`, `total_cost`, `createdate`) VALUES ('".$so_re."','".$so_no."','".$customer_id."','".$cid."','".$pid."', '".$description."','".$hsn."','".$qty."','".$price."','".$gst."','".$allowance."','".$sta."','".$d_allowance."','".$discount."', '".$last_tot."',NOW())";
						if(mysqli_query($conn, $ins_3))
						{
							for($i = 0 ; $i < $qty ; $i++)
							{
								// echo $string_version = implode(',', $code);
								// $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0' WHERE `id` = '".$code[$i]."' ");

								if($cus_type == '2')
								{
									$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0' WHERE `id` = '".$code[$i]."' ");
								}
								else
								{
									$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0', `delar_status` = '0' WHERE `id` = '".$code[$i]."' ");
								}
							}

							$stock_qy = mysqli_fetch_object(mysqli_query($conn, "SELECT extra FROM `ss_items` WHERE id = '".$pid."' AND published = '1' AND status = '1'"));

							$stock_re = $stock_qy->extra - $qty;

							$stock_up = mysqli_query($conn, "UPDATE `ss_items` SET `extra`= '".$stock_re."' WHERE id = '".$pid."' ");

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
				else
				{
					$response['status']=false;
					$response['message']="<strong>Oops ! </strong>Your Bill Amount Greater than Credit Limit.";
			        echo json_encode($response);	
				}
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Sales Details Already Exist.";
		        echo json_encode($response);
			}
		}
	}
}

elseif($_POST['method'] =='edit-form')
{
	$so_no         = mysqli_real_escape_string($conn, trim($_POST['so_no']));
	$customer_id   = mysqli_real_escape_string($conn, trim($_POST['customer_id']));
	$tcs_type      = mysqli_real_escape_string($conn, trim($_POST['tcs_type']));
	$cus_type      = mysqli_real_escape_string($conn, trim($_POST['cus_type']));
	$order_no      = mysqli_real_escape_string($conn, trim($_POST['order_no']));
	$dated         = mysqli_real_escape_string($conn, trim($_POST['dated']));
	$docu_no       = mysqli_real_escape_string($conn, trim($_POST['docu_no']));
	$despatched    = mysqli_real_escape_string($conn, trim($_POST['despatched']));
	$cid           = mysqli_real_escape_string($conn, trim($_POST['cid']));
	$pid           = mysqli_real_escape_string($conn, trim($_POST['pid']));
	$code          = $_POST['code'];
	$description   = mysqli_real_escape_string($conn, trim($_POST['description']));
	$price         = mysqli_real_escape_string($conn, trim($_POST['price']));
	$allowance     = mysqli_real_escape_string($conn, trim($_POST['allowance']));
	$sta           = mysqli_real_escape_string($conn, trim($_POST['sta']));
	$d_allowance   = mysqli_real_escape_string($conn, trim($_POST['d_allowance']));
	$discount      = mysqli_real_escape_string($conn, trim($_POST['discount']));
	$gst           = mysqli_real_escape_string($conn, trim($_POST['gst']));
	$avl_lmt       = mysqli_real_escape_string($conn, trim($_POST['avl_lmt']));
	$hsn           = mysqli_real_escape_string($conn, trim($_POST['hsn']));
	$auto_id       = mysqli_real_escape_string($conn, trim($_POST['auto_id']));

	$qty = count($code);

	if($customer_id!='' && $dated!='' && $cid!='' && $pid!='' && $code!='' && $qty!='')
	{

		$value = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_inv_details` WHERE auto_id = '".$auto_id."' AND published = '1' AND status = '1' LIMIT 0,1"));
      	
      	// Exist Value
		$e_tot = $value->total_cost;

		$e_avl_lmt = $avl_lmt + $e_tot;

		// Current Value
		$stk_re = $qty - $value->qty;

		$_b_dis = $price * $allowance / 100;
		
		$_d_dis = $price * $d_allowance / 100;

		$_disco = $_b_dis + $sta + $_d_dis + $discount;

		$_total = $price - $_disco;

		$_tot   = $qty *  $_total;
      
      	if($tcs_type == '2')
        {
          $_tcs     = $_tot * 0.1 / 100;
          $last_tot = $_tot + $_tcs;
        }
        else
        {
          $_tcs     = 0;
          $last_tot = $_tot + $_tcs;
      	}
      
		if($e_avl_lmt > $last_tot)
		{
			if($last_tot > $e_tot)
			{
				$cre_val = $e_tot - $last_tot;
				$cre_lmt = $avl_lmt + $cre_val;
			}
			else
			{
				$cre_val = $last_tot - $e_tot;
				$cre_lmt = $avl_lmt - $cre_val;
			}

			$qry_2 = mysqli_query($conn, "UPDATE `ss_distributors` SET `avl_lmt` = '".$cre_lmt."' WHERE id = '".$customer_id."'");


			$ins_1 = "UPDATE `ss_distributor_inv_details` SET `customer_id`= '".$customer_id."', `cid`= '".$cid."', `pid`= '".$pid."',`code`= '".$description."',`hsn`= '".$hsn."',`qty`= '".$qty."', `price`= '".$price."', `gst`= '".$gst."', `allowance`= '".$allowance."',`sta`= '".$sta."',`d_allowance`= '".$d_allowance."',`discount`= '".$discount."',`total_cost`= '".$last_tot."',`updatedate`= NOW() WHERE `auto_id` = '".$auto_id."'";

			$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`=NULL,`sales_no` = NULL, `sales_date` = NULL, `status` = '1' WHERE `sales_no` = '".$so_no."' AND `cid`= '".$cid."' AND `product_id`= '".$pid."'");
			if(mysqli_query($conn, $ins_1))
			{	

				for($i = 0 ; $i < $qty ; $i++)
				{
					// $up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0' WHERE `id` = '".$code[$i]."' ");

					if($cus_type == '2')
					{
						$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0', `bill_status` = '0' WHERE `id` = '".$code[$i]."' ");
					}
					else
					{
						$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."', `sales_date` = '".date('Y-m-d', strtotime($dated))."', `status` = '0', `bill_status` = '0', `delar_status` = '0' WHERE `id` = '".$code[$i]."' ");
					}
				}
				
				$stock_qy = mysqli_fetch_object(mysqli_query($conn, "SELECT extra FROM `ss_items` WHERE id = '".$pid."' AND published = '1' AND status = '1'"));

				$stock_re = $stock_qy->extra - $stk_re;

				$stock_up = mysqli_query($conn, "UPDATE `ss_items` SET `extra`= '".$stock_re."' WHERE id = '".$pid."' ");

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
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Bill Amount Greater than Credit Limit.";
	        echo json_encode($response);	
		}
	}
	elseif($customer_id =='')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Select Customer Name";
        echo json_encode($response);
	}
	elseif($dated =='')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Select Order Date";
        echo json_encode($response);
	}
	elseif($cid =='')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Select Categories Name";
        echo json_encode($response);
	}
	elseif($pid =='')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Select Product Name";
        echo json_encode($response);
	}

}

?>