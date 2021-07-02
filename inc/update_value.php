<?php
	
	// $conn = mysqli_connect("localhost","root","","yara_distributors");
	$conn = mysqli_connect("db5000256318.hosting-data.io","dbu326327","Multiple_@09.qwe","dbs250139");

	$type = !empty($_GET['type'])?$_GET['type']:'';

	if(!empty($type))
	{
		if($type == 'sales_order')
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `so_no`, `dated` FROM `ss_distributor_invoice`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0)
			{
				while($res_1 = mysqli_fetch_object($sel_1))
				{
					$sales_id   = isset($res_1->id)?$res_1->id:'';
					$sales_no   = isset($res_1->so_no)?$res_1->so_no:'';
					$sales_date = isset($res_1->dated)?$res_1->dated:'';

					$upt_1 = mysqli_query($conn, "UPDATE `ss_distributor_inv_details` SET `dated` = '".$sales_date."' WHERE `so_id` = '".$sales_id."'" );

					if($upt_1)
					{
						$response['code']    = 1;
						$response['message'] = "Success";
						$response['data']    = [];
				        echo json_encode($response);
					}
					else
					{
						$response['code']    = 0;
						$response['message'] = "No Records";
						$response['data']    = [];
				        echo json_encode($response);
					}
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