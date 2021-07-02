<?php
	session_start();
	include '../inc/config.php';

	if($_POST['method'] =='add_distributor_price')
	{
		$distributor_id = !empty($_POST['distributor_id'])?$_POST['distributor_id']:'';
		$category_id    = !empty($_POST['category_id'])?$_POST['category_id']:'';
		$product_id     = !empty($_POST['product_id'])?$_POST['product_id']:'';
		$product_price  = !empty($_POST['product_price'])?$_POST['product_price']:'';
		$nlc_value      = !empty($_POST['nlc_value'])?$_POST['nlc_value']:'';
		$product_count  = count($product_id);
		$gen_date       = date('Y-m-d');
		$gen_date_time  = date('Y-m-d H:i:s');

		$m = 1;
		for ($i=0; $i < $product_count; $i++) { 
			
			if($nlc_value[$i] != '0')	
			{
				$ins_1 = mysqli_query($conn, "INSERT INTO `ss_distributor_price`(`distributor_id`, `category_id`, `product_id`, `product_price`, `nlc_value`, `date`, `createdate`) VALUES ('".$distributor_id."', '".$category_id."', '".$product_id[$i]."', '".$product_price[$i]."', '".$nlc_value[$i]."', '".$gen_date."', '".$gen_date_time."')");
			}
			$m++;
		}

		if($product_count < $m)
		{
			$response['status']=true;
			$response['message']="<strong>Your Distributor Price Added Successfully.";
		    echo json_encode($response);	
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Distributor Price Can't Added.";
	        echo json_encode($response);
		}
	}

	else if($_POST['method'] =='add_dealer_price')
	{
		$distributor_id = !empty($_POST['distributor_id'])?$_POST['distributor_id']:'';
		$dealer_id      = !empty($_POST['dealer_id'])?$_POST['dealer_id']:'';
		$category_id    = !empty($_POST['category_id'])?$_POST['category_id']:'';
		$product_id     = !empty($_POST['product_id'])?$_POST['product_id']:'';
		$product_price  = !empty($_POST['product_price'])?$_POST['product_price']:'';
		$nlc_value      = !empty($_POST['nlc_value'])?$_POST['nlc_value']:'';
		$product_count  = count($product_id);
		$gen_date       = date('Y-m-d');
		$gen_date_time  = date('Y-m-d H:i:s');

		$m = 1;
		for ($i=0; $i < $product_count; $i++) { 
			
			if($nlc_value[$i] != '0')	
			{
				$ins_1 = mysqli_query($conn, "INSERT INTO `ss_dealer_price`(`distributor_id` ,`dealer_id`, `category_id`, `product_id`, `product_price`, `nlc_value`, `date`, `createdate`) VALUES ('".$distributor_id."', '".$dealer_id."', '".$category_id."', '".$product_id[$i]."', '".$product_price[$i]."', '".$nlc_value[$i]."', '".$gen_date."', '".$gen_date_time."')");
			}
			$m++;
		}

		if($product_count < $m)
		{
			$response['status']=true;
			$response['message']="<strong>Your Dealer Price Added Successfully.";
		    echo json_encode($response);	
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Dealer Price Can't Added.";
	        echo json_encode($response);
		}
	}
?>