<?php
	// Database Connection
	include 'config.php';

	// JSON
	header('Content-Type: application/json');

	// Parameters
	$method         = isset($_POST['method'])?$_POST['method']:'';
	$_type          = isset($_POST['type'])?$_POST['type']:'';
	$distributor_id = isset($_POST['distributor_id'])?$_POST['distributor_id']:'';
	$dealer_id      = isset($_POST['dealer_id'])?$_POST['dealer_id']:'';
	$email_val      = isset($_POST['email_val'])?$_POST['email_val']:'';

	// Data Format
	$o_date   = date('Y-m-d H:i:s');
	$c_date   = date('Y-m-d');

	// Distributor List
	if($method == '_list')
	{
		// Distributor List
		if($_type == '1')
		{
			$sel_1 = mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `isAdmin` != '1' AND `deleted` = '1' AND `status` = '1' ORDER BY `id` DESC");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0)
			{
				$distributor_list = [];
				foreach ($sel_1 as $key => $value) {
					$dis_id      = !empty($value['id'])?$value['id']:'';
					$store       = !empty($value['store'])?$value['store']:'';
					$owner       = !empty($value['owner'])?$value['owner']:'';
					$address     = !empty($value['customeraddress'])?$value['customeraddress']:'';
					$gst         = !empty($value['gst'])?$value['gst']:'';
					$pan_no      = !empty($value['pan_no'])?$value['pan_no']:'';
					$email_id    = !empty($value['email_id'])?$value['email_id']:'';
					$mobile      = !empty($value['mobile'])?$value['mobile']:'';
					$credit_lmt  = !empty($value['credit_lmt'])?$value['credit_lmt']:'0';
					$pre_lmt     = !empty($value['pre_lmt'])?$value['pre_lmt']:'0';
					$avl_lmt     = !empty($value['avl_lmt'])?$value['avl_lmt']:'0';
					$d_allowance = !empty($value['d_allowance'])?$value['d_allowance']:'0';
					$excutive    = !empty($value['excutive'])?$value['excutive']:'';
					$tcs_type    = !empty($value['tcs_type'])?$value['tcs_type']:'';
					$tcs_no      = !empty($value['tcs_no'])?$value['tcs_no']:'';
					$permission  = !empty($value['permission'])?$value['permission']:'';

					$distributor_list[] = array(
	    				'distributor_id' => $dis_id,
	    				'store'          => $store,
	    				'owner'          => $owner,
	    				'address'        => $address,
	    				'gst'            => $gst,
	    				'pan_no'         => $pan_no,
	    				'email_id'       => $email_id,
	    				'mobile'         => $mobile,
	    				'credit_lmt'     => $credit_lmt,
	    				'pre_lmt'        => $pre_lmt,
	    				'avl_lmt'        => $avl_lmt,
	    				'd_allowance'    => $d_allowance,
	    				'excutive'       => $excutive,
	    				'tcs_type'       => $tcs_type,
	    				'tcs_no'         => $tcs_no,
	    				'permission'     => $permission,
	    			);
				}

				$response['code']    = 200;
				$response['message'] = "Success";
				$response['data']    = $distributor_list;
				echo json_encode($response);
        		return;
			}
			else
			{
				$response['code']    = 204;
				$response['message'] = "No Records";
				$response['data']    = [];
				echo json_encode($response);
        		return;
			}
		}

		// Distributor Data
		if($_type == '2')
		{
			if(!empty($distributor_id))
			{
				if($distributor_id != '0')
				{
					$sel_1 = mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `id` = '".$distributor_id."' AND `isAdmin` != '1' AND `deleted` = '1' AND `status` = '1' ORDER BY `id` DESC");
					$cou_1 = mysqli_num_rows($sel_1);

					if($cou_1 > 0)
					{
						$distributor_list = [];
						foreach ($sel_1 as $key => $value) {
							$dis_id      = !empty($value['id'])?$value['id']:'';
							$store       = !empty($value['store'])?$value['store']:'';
							$owner       = !empty($value['owner'])?$value['owner']:'';
							$address     = !empty($value['customeraddress'])?$value['customeraddress']:'';
							$gst         = !empty($value['gst'])?$value['gst']:'';
							$pan_no      = !empty($value['pan_no'])?$value['pan_no']:'';
							$email_id    = !empty($value['email_id'])?$value['email_id']:'';
							$mobile      = !empty($value['mobile'])?$value['mobile']:'';
							$credit_lmt  = !empty($value['credit_lmt'])?$value['credit_lmt']:'0';
							$pre_lmt     = !empty($value['pre_lmt'])?$value['pre_lmt']:'0';
							$avl_lmt     = !empty($value['avl_lmt'])?$value['avl_lmt']:'0';
							$d_allowance = !empty($value['d_allowance'])?$value['d_allowance']:'0';
							$excutive    = !empty($value['excutive'])?$value['excutive']:'';
							$tcs_type    = !empty($value['tcs_type'])?$value['tcs_type']:'';
							$tcs_no      = !empty($value['tcs_no'])?$value['tcs_no']:'';
							$permission  = !empty($value['permission'])?$value['permission']:'';

							$distributor_list[] = array(
			    				'distributor_id' => $dis_id,
			    				'store'          => $store,
			    				'owner'          => $owner,
			    				'address'        => $address,
			    				'gst'            => $gst,
			    				'pan_no'         => $pan_no,
			    				'email_id'       => $email_id,
			    				'mobile'         => $mobile,
			    				'credit_lmt'     => $credit_lmt,
			    				'pre_lmt'        => $pre_lmt,
			    				'avl_lmt'        => $avl_lmt,
			    				'd_allowance'    => $d_allowance,
			    				'excutive'       => $excutive,
			    				'tcs_type'       => $tcs_type,
			    				'tcs_no'         => $tcs_no,
			    				'permission'     => $permission,
			    			);
						}

						$response['code']    = 200;
						$response['message'] = "Success";
						$response['data']    = $distributor_list;
						echo json_encode($response);
		        		return;
					}
					else
					{
						$response['code']    = 204;
						$response['message'] = "No Records";
						$response['data']    = [];
						echo json_encode($response);
		        		return;
					}
				}
				else
				{
					$response['code']    = 422;
					$response['message'] = "Value is not valide";
					$response['data']    = [];
					echo json_encode($response);
	        		return;
				}
			}
			else
			{
				$response['code']    = 400;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Dealer List
		else if($_type == '3')
		{	
			$sel_1 = mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `userid` = '".$distributor_id."' AND `published` = '1' AND `status` = '1' ORDER BY `id` DESC");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0)
			{
				$dealer_list = [];
				foreach ($sel_1 as $key => $value) {
					$dealer_id   = !empty($value['id'])?$value['id']:'';
					$store       = !empty($value['cname'])?$value['cname']:'';
					$owner       = !empty($value['name'])?$value['name']:'';
					$address     = !empty($value['address'])?$value['address']:'';
					$gst         = !empty($value['gst_no'])?$value['gst_no']:'';
					$pan_no      = !empty($value['pan_no'])?$value['pan_no']:'';
					$email_id    = !empty($value['email'])?$value['email']:'';
					$mobile      = !empty($value['mobile'])?$value['mobile']:'';
					$credit_lmt  = !empty($value['credit_lmt'])?$value['credit_lmt']:'0';
					$pre_lmt     = !empty($value['pre_lmt'])?$value['pre_lmt']:'0';
					$avl_lmt     = !empty($value['avl_lmt'])?$value['avl_lmt']:'0';
					$d_allowance = !empty($value['d_allowance'])?$value['d_allowance']:'0';
					$excutive    = !empty($value['excutive'])?$value['excutive']:'';
					$tcs_type    = !empty($value['tcs_type'])?$value['tcs_type']:'';
					$tcs_no      = !empty($value['tcs_no'])?$value['tcs_no']:'';

					$dealer_list[] = array(
	    				'dealer_id'   => $dealer_id,
	    				'store'       => $store,
	    				'owner'       => $owner,
	    				'address'     => $address,
	    				'gst'         => $gst,
	    				'pan_no'      => $pan_no,
	    				'email_id'    => $email_id,
	    				'mobile'      => $mobile,
	    				'credit_lmt'  => $credit_lmt,
	    				'pre_lmt'     => $pre_lmt,
	    				'avl_lmt'     => $avl_lmt,
	    				'd_allowance' => $d_allowance,
	    				'excutive'    => $excutive,
	    				'tcs_type'    => $tcs_type,
	    				'tcs_no'      => $tcs_no,
	    			);
				}

				$response['code']    = 200;
				$response['message'] = "Success";
				$response['data']    = $dealer_list;
				echo json_encode($response);
        		return;
			}
			else
			{
				$response['code']    = 204;
				$response['message'] = "No Records";
				$response['data']    = [];
				echo json_encode($response);
        		return;
			}
		}

		// Dealer Data
		else if($_type == '4')
		{
			if(!empty($dealer_id))	
			{
				$sel_1 = mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `id` = '".$dealer_id."' AND `published` = '1' AND `status` = '1' ORDER BY `id` DESC");
				$cou_1 = mysqli_num_rows($sel_1);

				if($cou_1 > 0)
				{
					$dealer_list = [];
					foreach ($sel_1 as $key => $value) {
						$dealer_id   = !empty($value['id'])?$value['id']:'';
						$store       = !empty($value['cname'])?$value['cname']:'';
						$owner       = !empty($value['name'])?$value['name']:'';
						$address     = !empty($value['address'])?$value['address']:'';
						$gst         = !empty($value['gst_no'])?$value['gst_no']:'';
						$pan_no      = !empty($value['pan_no'])?$value['pan_no']:'';
						$email_id    = !empty($value['email'])?$value['email']:'';
						$mobile      = !empty($value['mobile'])?$value['mobile']:'';
						$credit_lmt  = !empty($value['credit_lmt'])?$value['credit_lmt']:'0';
						$pre_lmt     = !empty($value['pre_lmt'])?$value['pre_lmt']:'0';
						$avl_lmt     = !empty($value['avl_lmt'])?$value['avl_lmt']:'0';
						$d_allowance = !empty($value['d_allowance'])?$value['d_allowance']:'0';
						$excutive    = !empty($value['excutive'])?$value['excutive']:'';
						$tcs_type    = !empty($value['tcs_type'])?$value['tcs_type']:'';
						$tcs_no      = !empty($value['tcs_no'])?$value['tcs_no']:'';

						$dealer_list[] = array(
		    				'dealer_id'   => $dealer_id,
		    				'store'       => $store,
		    				'owner'       => $owner,
		    				'address'     => $address,
		    				'gst'         => $gst,
		    				'pan_no'      => $pan_no,
		    				'email_id'    => $email_id,
		    				'mobile'      => $mobile,
		    				'credit_lmt'  => $credit_lmt,
		    				'pre_lmt'     => $pre_lmt,
		    				'avl_lmt'     => $avl_lmt,
		    				'd_allowance' => $d_allowance,
		    				'excutive'    => $excutive,
		    				'tcs_type'    => $tcs_type,
		    				'tcs_no'      => $tcs_no,
		    			);
					}

					$response['code']    = 200;
					$response['message'] = "Success";
					$response['data']    = $dealer_list;
					echo json_encode($response);
	        		return;
				}
				else
				{
					$response['code']    = 204;
					$response['message'] = "No Records";
					$response['data']    = [];
					echo json_encode($response);
	        		return;
				}
			}
			else
			{
				$response['code']    = 400;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
		        echo json_encode($response);
		        return;
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