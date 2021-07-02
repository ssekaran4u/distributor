
<?php

	// Database Connection
	include 'config.php';

	// JSON
	header('Content-Type: application/json');

	// Parameters
	$method     = isset($_POST['method'])?$_POST['method']:'';
	$cus_id     = isset($_POST['customer_id'])?$_POST['customer_id']:'';
	$order_no   = isset($_POST['order_no'])?$_POST['order_no']:'';
	$dated      = isset($_POST['dated'])?date('Y-m-d', strtotime($_POST['dated'])):'';
	$docu_no    = isset($_POST['docu_no'])?$_POST['docu_no']:'';
	$despatched = isset($_POST['despatched'])?$_POST['despatched']:'';
	$filter     = isset($_POST['sales'])?$_POST['sales']:'';
	$json_res   = json_decode($filter);

	// Data Format
	$o_date   = date('Y-m-d H:i:s');
	$c_date   = date('Y-m-d');

	// Add Distributor Invoice
	if($method == 'distributor_invoice')
	{
		$error=FALSE;
		$required = array('customer_id', 'dated');
		foreach ($required as $field) 
		{
			if(empty($_POST[$field]))
	        {
	            $error = TRUE;
	        }
		}

		if($error == TRUE)
	    {
	    	$response['code']    = 400;
			$response['message'] = "Please fill all required fields";
			$response['data']    = [];
	        echo json_encode($response);
	        return;
	    }
	    else
	    {
	    	$sel_1 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_academic` WHERE `status` = '1'  ORDER BY `ss_academic`.`id` DESC"));

	    	$acad = isset($sel_1->id)?$sel_1->id:'';

	    	$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `so_no`, `acad_id` FROM `ss_distributor_invoice` WHERE `published` = '1' AND `status` = '1' AND `acad_id` = '".$acad."' ORDER BY id DESC LIMIT 1"));

	    	$sales_no = isset($sel_2->so_no)?$sel_2->so_no:'';

	    	$sel_3 = mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `id` = '".$cus_id."' AND `status` = '1' AND `deleted` = '1'");

	    	$cou_3 = mysqli_num_rows($sel_3);
			$res_3 = mysqli_fetch_object($sel_3);

			if($cou_3 > 0)
			{	
				$excutive   = isset($res_3->excutive)?$res_3->excutive:'';
				$cre_lmt    = isset($res_3->credit_lmt)?$res_3->credit_lmt:'';
		    	$pre_lmt    = isset($res_3->pre_lmt)?$res_3->pre_lmt:'';
		    	$avl_lmt    = isset($res_3->avl_lmt)?$res_3->avl_lmt:'';
		    	$permission = isset($res_3->permission)?$res_3->permission:'';
		    	$tcs_type   = isset($res_3->tcs_type)?$res_3->tcs_type:'';

		    	if($sales_no =='')
		    	{
		    		if($acad_id == 2)
		    		{
		    			$sale_re = 230;
		    		}
		    		else
		    		{
		    			$sale_re = 1;
		    		}
		    	}
		    	else
		    	{
		    		$code = explode('YB', $sales_no);
					$sale_re = $code[1] + 1;
		    	}

		    	$so_no   = 'YB'.leadingZeros($sale_re, 5);

		    	$lstTot = 0;
		    	foreach ($json_res as $key => $result) 
		    	{
		    		$price_val    = isset($result->price)?$result->price:'';
		    		$allowance    = isset($result->allowance)?$result->allowance:'';
		    		$sta_val      = isset($result->sta)?$result->sta:'';
		    		$d_allowance  = isset($result->d_allowance)?$result->d_allowance:'';
		    		$discount     = isset($result->discount)?$result->discount:'';
		    		$productCount = count($result->serialnumber_details);

		    		$_b_dis  = $price_val * $allowance / 100;
									
					$_d_dis  = $price_val * $d_allowance / 100;

					$_disco  = $_b_dis + $sta_val + $_d_dis + $discount;

					$_total  = $price_val - $_disco;

					$_tot    = $productCount *  $_total;

					$lstTot += $_tot;
		    	}

		    	if($avl_lmt > $lstTot)
		    	{
		    		$credit_lmt = $avl_lmt - $lstTot;
		    		$count = 1;
		    		foreach ($json_res as $key => $value)
		    		{
		    			$sel_4 = mysqli_query($conn, "SELECT `id`, `so_no` FROM `ss_distributor_invoice` WHERE `so_no` = '".$so_no."' AND `published` = '1'");
						$cou_4 = mysqli_num_rows($sel_4);
						$res_4 = mysqli_fetch_object($sel_4);

			    		$cat_id   = isset($value->category_id)?$value->category_id:'';
			    		$pro_id   = isset($value->product_id)?$value->product_id:'';
			    		$price    = isset($value->price)?$value->price:'';
			    		$allow    = isset($value->allowance)?$value->allowance:'';
			    		$sta_val  = isset($value->sta)?$value->sta:'';
			    		$d_allow  = isset($value->d_allowance)?$value->d_allowance:'';
			    		$discount = isset($value->discount)?$value->discount:'';
			    		$gst_val  = isset($value->gst)?$value->gst:'';
			    		$hsn_val  = isset($value->hsn)?$value->hsn:'';
			    		$desc     = isset($value->description)?$value->description:'';
			    		$proCount = count($value->serialnumber_details);
			    		$serialNo = $value->serialnumber_details;

			    		$_b_dis = $price_val * $allowance / 100;
										
						$_d_dis = $price_val * $d_allowance / 100;

						$_disco = $_b_dis + $sta_val + $_d_dis;

						$_total = $price_val - $_disco;

						$_tot   = $proCount *  $_total;

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

						if($cou_4 > 0)
						{
							$so_re = isset($res_3->id)?$res_3->id:'';
							$so_no = isset($res_3->so_no)?$res_3->so_no:'';

							$ins_2 = mysqli_query($conn, "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `discount`, `total_cost`, `createdate`) VALUES ('".$so_re."', '".$so_no."', '".$cus_id."', '".$cat_id."', '".$pro_id."', '".$desc."', '".$hsn_val."', '".$proCount."', '".$price."', '".$gst_val."', '".$allow."', '".$sta_val."', '".$d_allow."', '".$discount."', '".$last_tot."', '".$o_date."')");

							foreach ($serialNo as $key => $val) {
				    			if($permission == '2')
				    			{
				    				$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$cus_id."',`sales_no` = '".$so_no."', `sales_date` = '".$dated."', `status` = '0', `bill_status` = '0' WHERE `id` = '".$val->serial_id."' ");
				    			}
				    			else
				    			{
				    				$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$cus_id."',`sales_no` = '".$so_no."', `sales_date` = '".$dated."', `status` = '0', `bill_status` = '0', `delar_status` = '0' WHERE `id` = '".$val->serial_id."' ");
				    			}
				    		}
						}
						else
						{	
							$ins_1 = mysqli_query($conn, "INSERT INTO `ss_distributor_invoice`(`userid`, `so_no`, `customer_id`, `excutive`, `tcs_type`, `order_date`, `order_no`, `dated`, `docu_no`, `despatched`, `acad_id`, `createdate`) VALUES ('1', '".$so_no."', '".$cus_id."', '".$excutive."', '".$tcs_type."', '".$dated."', '".$order_no."', '".$dated."', '".$docu_no."', '".$despatched."', '".$acad."', '".$o_date."')");

							$so_re = mysqli_insert_id($conn);

							$ins_2 = mysqli_query($conn, "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `discount`, `total_cost`, `createdate`) VALUES ('".$so_re."', '".$so_no."', '".$cus_id."', '".$cat_id."', '".$pro_id."', '".$desc."', '".$hsn_val."', '".$proCount."', '".$price."', '".$gst_val."', '".$allow."', '".$sta_val."', '".$d_allow."', '".$discount."', '".$last_tot."', '".$o_date."')");

							foreach ($serialNo as $key => $val) {
				    			if($permission == '2')
				    			{
				    				$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$cus_id."',`sales_no` = '".$so_no."', `sales_date` = '".$dated."', `status` = '0' WHERE `id` = '".$val->serial_id."' ");
				    			}
				    			else
				    			{
				    				$up_stk = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$cus_id."',`sales_no` = '".$so_no."', `sales_date` = '".$dated."', `status` = '0', `delar_status` = '0' WHERE `id` = '".$val->serial_id."' ");
				    			}
				    		}
						}

						$count++;
			    	}


			    	if($count != '0')
			    	{
			    		$upt_1 = mysqli_query($conn, "UPDATE `ss_distributors` SET `avl_lmt` = '".$credit_lmt."' WHERE `id` = '".$cus_id."'");

			    		$sel_5 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_invoice` WHERE `id` = '".$so_re."' AND `published` = '1'"));
			    		
			    		$sales_id   = !empty($sel_5->id)?$sel_5->id:'';
			    		$sales_no   = !empty($sel_5->so_no)?$sel_5->so_no:'';
			    		$cus_id     = !empty($sel_5->customer_id)?$sel_5->customer_id:'';
			    		$order_no   = !empty($sel_5->order_no)?$sel_5->order_no:'---';
					    $dated      = !empty($sel_5->dated)?date('d-m-Y', strtotime($sel_5->dated)):'---';
					    $docu_no    = !empty($sel_5->docu_no)?$sel_5->docu_no:'---';
					    $despatched = !empty($sel_5->despatched)?$sel_5->despatched:'---';

			    		$sel_6 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `id` = '".$cus_id."' AND `deleted` = '1' "));

			    		$store    = !empty($sel_6->store)?$sel_6->store:'';
			    		$owner    = !empty($sel_6->owner)?$sel_6->owner:'';
			    		$adrs     = !empty($sel_6->customeraddress)?$sel_6->customeraddress:'';
			    		$gst_no   = !empty($sel_6->gst)?$sel_6->gst:'';
			    		$pan_no   = !empty($sel_6->pan_no)?$sel_6->pan_no:'';
			    		$email_id = !empty($sel_6->email_id)?$sel_6->email_id:'';
			    		$mobile   = !empty($sel_6->mobile)?$sel_6->mobile:'';
			    		$tcs_type = !empty($sel_6->tcs_type)?$sel_6->tcs_type:'';
			    		$tcs_no   = !empty($sel_6->tcs_no)?$sel_6->tcs_no:'';
			    		$state    = !empty($sel_6->state)?$sel_6->state:'';

			    		$distributor_details[] = array(
							'store'    => $store,
							'owner'    => $owner,
							'address'  => $adrs,
							'gst_no'   => $gst_no,
							'pan_no'   => $pan_no,
							'email_id' => $email_id,
							'mobile'   => $mobile,
							'tcs_type' => $tcs_type,
							'tcs_no'   => $tcs_no,
						);

			    		$sel_7 = mysqli_query($conn, "SELECT sa.title AS c_title, sb.so_no AS so_no, sb.pid AS pid, sb.qty AS qty, sb.price AS price, sb.gst AS gst, sb.allowance AS allowance, sb.sta AS sta, sb.code AS code, sb.d_allowance AS d_allowance, sb.discount AS discount, sb.value_pri AS value_pri, sc.title AS p_title, sc.description AS description, sc.hsn AS hsn, sc.oprice AS oprice, sb.code AS code, sb.billstatus AS billstatus FROM ss_category sa JOIN ss_distributor_inv_details sb ON sa.id = sb.cid JOIN ss_items sc ON sb.pid = sc.id WHERE sb.so_id = '".$so_re."' AND sb.qty != '0' AND sb.status = '1'"); 

			    		$cou_7 = mysqli_num_rows($sel_7);

			    		if($cou_7 > 0)
			    		{
			    			$i=1;
			    			$sales_list   = [];
			    			$_qty     = 0;
			    			$tax_val  = 0;
			    			$gst_     = 0;
			    			$tcs_val  = 0;
			    			$rond_tot = 0;
			    			$lst_tot  = 0;
			    			foreach ($sel_7 as $key => $value_1) {
			    				$qty_val     = !empty($value_1['qty'])?$value_1['qty']:'0';
                                $price_val   = !empty($value_1['price'])?$value_1['price']:'0';
                                $e_allowance = !empty($value_1['allowance'])?$value_1['allowance']:'0';
                                $de_allowan  = !empty($value_1['d_allowance'])?$value_1['d_allowance']:'0';
                                $sta_val     = !empty($value_1['sta'])?$value_1['sta']:'0';
                                $discount    = !empty($value_1['discount'])?$value_1['discount']:'0';
                                $billstatus  = !empty($value_1['billstatus'])?$value_1['billstatus']:'0';
                                $gst_val     = !empty($value_1['gst'])?$value_1['gst']:'0';
                                $code_val    = !empty($value_1['code'])?$value_1['code']:'0';
                                $product_id  = !empty($value_1['pid'])?$value_1['pid']:'0';
                                $so_no       = !empty($value_1['so_no'])?$value_1['so_no']:'0';
                                $hsn_code    = !empty($value_1['hsn'])?$value_1['hsn']:'0';

                                $serial_no = '';

                                $serial = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE `product_id` = '".$product_id."' AND `sales_no` = '".$so_no."' AND published = '1'");
                                    
                                while($res = mysqli_fetch_object($serial))
                                {
                                    $serial_no .= $res->code.', ';
                                }

                                $serial_val = substr_replace($serial_no, "", -2);

                                $_qty += $qty_val;

                                $allowance = $price_val * $e_allowance / 100;

                                $allowance_val = $qty_val * round($allowance);

                                $d_allowance = $price_val * $de_allowan / 100;

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

                                if($state == 33)
                                {
                                	$gst_details[] = array(
										'sgst_rate'  => strval(round($state_gst)),
										'sgst_value' => number_format($cal_gst, 2),
										'cgst_rate'  => strval(round($state_gst)),
										'cgst_value' => number_format($cal_gst, 2),
									);
                                }
                                else
                                {
                                	$gst_details[] = array(
										'igst_rate'  => strval(round($gst_val)),
										'igst_value' => number_format($gstper, 2),
									);	
                                }

                                $sales_list[] = array(
									's_no'                => $i,
									'code'                => $code_val,
									'hsn_code'            => $hsn_code,
									'price'               => $price,
									'quantity'            => $qty_val,
									'additional_discount' => strval(round($discount)),
									'taxable_value'       => number_format($_val, 2),
									'gst_details'         => $gst_details,
									'total_price'         => strval(round($total_val)),
									'serial_no'           => $serial_val,
								);

                                $i++;
			    			}

			    			$invoice_details[] = array(
								'total_quantity' => strval($_qty),
								'sub_total'      => strval(number_format($tax_val, 2)),
								'gst_value'      => strval(number_format($gst_, 2)),
								'tcs_value'      => strval(number_format($tcs_val, 2)),
								'round_off'      => strval(number_format($rond_tot, 2)),
								'net_value'      => strval(number_format($lst_tot, 2)),
								'sales_details'  => $sales_list,
							);
			    		}
			    		else
			    		{
			    			$invoice_details[] = array(
								'total_quantity' => "",
								'sub_total'      => "",
								'gst_value'      => "",
								'tcs_value'      => "",
								'round_off'      => "",
								'net_value'      => "",
								'sales_details' => [],
							);
			    		}

			    		$sales_details[] = array(
							'sales_id'            => $sales_id,
							'sales_no'            => $sales_no,
							'distributor_details' => $distributor_details,
							'invoice_details'     => $invoice_details,
							'invoice_url'         => "https://www.yaraelectronics.com/distributor/invoice/distributor-print.php?id=".$so_re."",
						);

			    		$response['code']    = 200;
						$response['message'] = "Success";
						$response['data']    = $sales_details;
				        echo json_encode($response);
				        return;
			    	}
			    	else
			    	{
			    		$response['code']    = 204;
						$response['message'] = "Not Success";
						$response['data']    = [];
				        echo json_encode($response);
				        return;
			    	}
		    	}
		    	else
		    	{
		    		$response['code']    = 503;
					$response['message'] = "Your Bill Amount Greater than Credit Limit";
					$response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}
			else
			{
				$response['code']    = 403;
				$response['message'] = "User Not Exist";
				$response['data']    = [];
		        echo json_encode($response);
		        return;
			}
	    }
	}

	else if($method == 'dealer_invoice')
	{
		$error=FALSE;
		$required = array('customer_id', 'dated');
		foreach ($required as $field) 
		{
			if(empty($_POST[$field]))
	        {
	            $error = TRUE;
	        }
		}

		if($error == TRUE)
	    {
	    	$response['code']    = 400;
			$response['message'] = "Please fill all required fields";
			$response['data']    = [];
	        echo json_encode($response);
	        return;
	    }
	    else
	    {
	    	$sel_1 = mysqli_fetch_object(mysqli_query($conn, "SELECT `so_no`, `acad_id` FROM `ss_sales` WHERE `published` = '1' AND `status` = '1' ORDER BY id DESC limit 1"));

	    	$sales_no = isset($sel_1->so_no)?$sel_1->so_no:'';
	    	$acad_id  = isset($sel_1->acad_id)?$sel_1->acad_id:'';

	    	$sel_2 = mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `id` = '".$cus_id."' AND `status` = '1' AND `published` = '1'");

	    	$cou_2 = mysqli_num_rows($sel_2);
			$res_2 = mysqli_fetch_object($sel_2);

			if($cou_2 > 0)
			{
		    	$user_id  = isset($res_2->userid)?$res_2->userid:'';
				$cre_lmt  = isset($res_2->credit_lmt)?$res_2->credit_lmt:'';
		    	$pre_lmt  = isset($res_2->pre_lmt)?$res_2->pre_lmt:'';
		    	$avl_lmt  = isset($res_2->avl_lmt)?$res_2->avl_lmt:'';
		    	$tcs_type = isset($res_2->tcs_type)?$res_2->tcs_type:'';

		    	if($sales_no =='')
		    	{
		    		if($acad_id == 2)
		    		{
		    			$sale_re = 230;
		    		}
		    		else
		    		{
		    			$sale_re = 1;
		    		}
		    	}
		    	else
		    	{
		    		$code = explode('YB', $sales_no);
					$sale_re = $code[1] + 1;
		    	}

		    	$so_no   = 'YB'.leadingZeros($sale_re, 5);

		    	$lstTot = 0;
		    	foreach ($json_res as $key => $result) 
		    	{
		    		$price_val    = isset($result->price)?$result->price:'';
		    		$allowance    = isset($result->allowance)?$result->allowance:'';
		    		$sta_val      = isset($result->sta)?$result->sta:'';
		    		$d_allowance  = isset($result->d_allowance)?$result->d_allowance:'';
		    		$discount     = isset($result->discount)?$result->discount:'';
		    		$productCount = count($result->serialnumber_details);

		    		$_b_dis  = $price_val * $allowance / 100;
									
					$_d_dis  = $price_val * $d_allowance / 100;

					$_disco  = $_b_dis + $sta_val + $_d_dis + $discount;

					$_total  = $price_val - $_disco;

					$_tot    = $productCount *  $_total;

					$lstTot += $_tot;
		    	}

		    	if($avl_lmt > $lstTot)
		    	{
		    		$credit_lmt = $avl_lmt - $lstTot;
	    			$count = 1;
	    			foreach ($json_res as $key => $value)
	    			{
	    				$sel_3 = mysqli_query($conn, "SELECT `id`, `so_no` FROM `ss_sales` WHERE `so_no` = '".$so_no."' AND `published` = '1'");
						$cou_3 = mysqli_num_rows($sel_3);
						$res_3 = mysqli_fetch_object($sel_3);

						$sel_4 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_academic` WHERE `published` = '1' ORDER BY `id` DESC LIMIT 0,1"));

						$acad  = isset($sel_4->id)?$sel_4->id:'';

						$cat_id   = isset($value->category_id)?$value->category_id:'';
			    		$pro_id   = isset($value->product_id)?$value->product_id:'';
			    		$price    = isset($value->price)?$value->price:'';
			    		$allow    = isset($value->allowance)?$value->allowance:'';
			    		$sta_val  = isset($value->sta)?$value->sta:'';
			    		$d_allow  = isset($value->d_allowance)?$value->d_allowance:'';
			    		$discount = isset($value->discount)?$value->discount:'';
			    		$gst_val  = isset($value->gst)?$value->gst:'';
			    		$hsn_val  = isset($value->hsn)?$value->hsn:'';
			    		$desc     = isset($value->description)?$value->description:'';
			    		$proCount = count($value->serialnumber_details);
			    		$serialNo = $value->serialnumber_details;

			    		$_b_dis = $price_val * $allowance / 100;
										
						$_d_dis = $price_val * $d_allowance / 100;

						$_disco = $_b_dis + $sta_val + $_d_dis;

						$_total = $price_val - $_disco;

						$_tot   = $proCount *  $_total;

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

	                    if($cou_3 > 0)
						{
							$so_re = isset($res_3->id)?$res_3->id:'';
							$so_no = isset($res_3->so_no)?$res_3->so_no:'';

							$ins_2 = mysqli_query($conn, "INSERT INTO `ss_sales_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `discount`, `total_cost`, `createdate`) VALUES ('".$so_re."', '".$so_no."', '".$cus_id."', '".$cat_id."', '".$pro_id."', '".$desc."', '".$hsn_val."', '".$proCount."', '".$price."', '".$gst_val."', '".$allow."', '".$sta_val."', '".$d_allow."', '".$discount."', '".$last_tot."', '".$o_date."')");

							foreach ($serialNo as $key => $val) {

				    			$upt_2 = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$cus_id."',`sales_no` = '".$so_no."', `sales_date` = '".$dated."', `delar_status` = '0' WHERE `id` = '".$val->serial_id."' ");
				    		}
						}
						else
						{
							$ins_1 = mysqli_query($conn, "INSERT INTO `ss_sales`(`userid`, `so_no`, `customer_id`, `tcs_type`, `order_date`, `order_no`, `dated`, `docu_no`, `despatched`, `acad_id`, `createdate`) VALUES ('".$user_id."', '".$so_no."', '".$cus_id."', '".$tcs_type."', '".$dated."', '".$order_no."', '".$dated."', '".$docu_no."', '".$despatched."', '".$acad."', '".$o_date."')");

							$so_re = mysqli_insert_id($conn);

							$ins_2 = mysqli_query($conn, "INSERT INTO `ss_sales_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `discount`, `total_cost`, `createdate`) VALUES ('".$so_re."', '".$so_no."', '".$cus_id."', '".$cat_id."', '".$pro_id."', '".$desc."', '".$hsn_val."', '".$proCount."', '".$price."', '".$gst_val."', '".$allow."', '".$sta_val."', '".$d_allow."', '".$discount."', '".$last_tot."', '".$o_date."')");

							foreach ($serialNo as $key => $val) {

				    			$upt_1 = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$cus_id."',`sales_no` = '".$so_no."', `sales_date` = '".$dated."', `delar_status` = '0' WHERE `id` = '".$val->serial_id."' ");
				    		}
						}
	    			}

	    			if($count)
			    	{
			    		$upt_1 = mysqli_query($conn, "UPDATE `ss_customers` SET `avl_lmt` = '".$credit_lmt."' WHERE `id` = '".$cus_id."'");

			    		$response['code']    = 200;
						$response['message'] = "Success";
						$response['data']    = [];
				        echo json_encode($response);
				        return;
			    	}
			    	else
			    	{
			    		$response['code']    = 204;
						$response['message'] = "Not Success";
						$response['data']    = [];
				        echo json_encode($response);
				        return;
			    	}
		    	}
		    	else
		    	{
		    		$response['code']    = 503;
					$response['message'] = "Your Bill Amount Greater than Credit Limit";
					$response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}
			else
			{
				$response['code']    = 403;
				$response['message'] = "Dealer Not Exist";
				$response['data']    = [];
		        echo json_encode($response);
		        return;
			}
	    }
	}

	// Error
	else
	{	
		$response['code']    = 404;
		$response['message'] = "Not found";
		$response['data']    = [];
        echo json_encode($response);
        return;
	}
?>