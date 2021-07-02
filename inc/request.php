<?php
	include 'config.php';

	// JSON
	// header('Content-Type: application/json');

	$request = !empty($_GET['request'])?$_GET['request']:'';

	if($request == '_excutiveUpload')
	{
		$sel_1 = mysqli_query($conn, "SELECT `id`, `customer_id` FROM `ss_distributor_invoice` WHERE `published` = '1'");

		$cou_1 =mysqli_num_rows($sel_1);

		if($cou_1 > 0)
		{
			foreach ($sel_1 as $key => $value) {
				
				$sales_id    = !empty($value['id'])?$value['id']:'';
				$customer_id = !empty($value['customer_id'])?$value['customer_id']:'';

				$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `id` = '".$customer_id."' AND `deleted` = '1' "));

				$excutive = !empty($sel_2->excutive)?$sel_2->excutive:'';

				$upt_1 = mysqli_query($conn, "UPDATE `ss_distributor_invoice` SET `excutive` = '".$excutive."' WHERE `id` = '".$sales_id."'");
			}

			if($cou_1)
			{
				$response['code']    = 1;
				$response['message'] = "Succss";
				$response['data']    = [];
		        echo json_encode($response);
		        return;	
			}
			else
			{
				$response['code']    = 0;
				$response['message'] = "Not Success";
				$response['data']    = [];
		        echo json_encode($response);
		        return;	
			}
		}
		else
		{
			$response['code']    = 0;
			$response['message'] = "No Records";
			$response['data']    = [];
	        echo json_encode($response);
	        return;		
		}
	}
	else
	{
		$response['code']    = 0;
		$response['message'] = "Error";
		$response['data']    = [];
        echo json_encode($response);
        return;
	}
?>