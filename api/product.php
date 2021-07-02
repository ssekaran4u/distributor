<?php
	// Database Connection
	include 'config.php';

	// JSON
	header('Content-Type: application/json');

	// Parameters
	$method         = isset($_POST['method'])?$_POST['method']:'';
	$_type          = isset($_POST['type'])?$_POST['type']:'';
	$category_id    = isset($_POST['category_id'])?$_POST['category_id']:'';
	$product_id     = isset($_POST['product_id'])?$_POST['product_id']:'';
	$distributor_id = isset($_POST['distributor_id'])?$_POST['distributor_id']:'';

	// Data Format
	$o_date   = date('Y-m-d H:i:s');
	$c_date   = date('Y-m-d');

	// Product List
	if($method == '_list')
	{
		// Categories List
		if($_type == '1')
		{
			$sel_1 = mysqli_query($conn, "SELECT * FROM `ss_category` WHERE `published` = '1' AND `status` = '1' ORDER BY `id` DESC");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0)
			{
				$category_list = [];
				foreach ($sel_1 as $key => $value)
				{
					$cat_id     = !empty($value['id'])?$value['id']:'';
					$cat_title  = !empty($value['title'])?$value['title']:'';
					$cat_status = !empty($value['status'])?$value['status']:'';

					$category_list[] = array(
	    				'category_id'     => $cat_id,
	    				'category_title'  => $cat_title,
	    				'category_status' => $cat_status
	    			);
				}

				$response['code']    = 200;
				$response['message'] = "Success";
				$response['data']    = $category_list;
				echo json_encode($response);
        		return;
			}
			else
			{
				$response['code']    = 202;
				$response['message'] = "No Records";
				$response['data']    = [];
				echo json_encode($response);
        		return;
			}
		}

		// Product List
		else if($_type == '2')
		{
			if(!empty($category_id))
			{
				if($category_id != '0')
				{
					$sel_1 = mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `cid` = '".$category_id."' AND `published` = '1' AND `status` = '1' ORDER BY `id` DESC");
					$cou_1 = mysqli_num_rows($sel_1);

					if($cou_1 > 0)
					{
						$product_list = [];
						foreach ($sel_1 as $key => $value) {
							$pro_id        = !empty($value['id'])?$value['id']:'';
							$pcode         = !empty($value['pcode'])?$value['pcode']:'';
							$title         = !empty($value['title'])?$value['title']:'';
							$pro_hsn       = !empty($value['hsn'])?$value['hsn']:'';
							$pro_cid       = !empty($value['cid'])?$value['cid']:'';
							$description   = !empty($value['description'])?$value['description']:'';
							$pro_gst       = !empty($value['gst'])?$value['gst']:'';
							$pro_price     = !empty($value['price'])?$value['price']:'';
							$pro_oprice    = !empty($value['oprice'])?$value['oprice']:'';
							$pro_extra     = !empty($value['extra'])?$value['extra']:'0';
							$pro_allowance = !empty($value['allowance'])?$value['allowance']:'0';
							$pro_sta       = !empty($value['sta'])?$value['sta']:'0';
							$pro_discount  = !empty($value['discount'])?$value['discount']:'0';
							$published     = !empty($value['published'])?$value['published']:'';

							$product_list[] = array(
								'product_id'   => $pro_id,
								'product_code' => $pcode,
								'title'        => $title,
								'product_hsn'  => $pro_hsn,
								'category_id'  => $pro_cid,
								'description'  => $description,
								'product_gst'  => $pro_gst,
								'price'        => $pro_oprice,
								'stock'        => $pro_extra,
								'allowance'    => $pro_allowance,
								'product_sta'  => $pro_sta,
								'discount'     => $pro_discount,
								'published'    => $published,
							);
						}

						$response['code']    = 200;
						$response['message'] = "Success";
						$response['data']    = $product_list;
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

		// Serial No List
		else if($_type == '3')
		{
			if(!empty($product_id))
			{
				if($product_id != '0')
				{
					$sel_1 = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE `status` = '1' AND `delar_status` = '1' AND `service_status` = '1' AND `product_id` = ".$product_id." AND `published` = '1'");
					$cou_1 = mysqli_num_rows($sel_1);

					if($cou_1 > 0)
					{
						$serial_list = [];
						foreach ($sel_1 as $key => $value) {
							$serial_id   = !empty($value['id'])?$value['id']:'';
							$category_id = !empty($value['cid'])?$value['cid']:'';
							$product_id  = !empty($value['product_id'])?$value['product_id']:'';
							$serial_code = !empty($value['code'])?$value['code']:'';
							$published   = !empty($value['published'])?$value['published']:'';

							$serial_list[] = array(
			    				'serial_id'   => $serial_id,
			    				'category_id' => $category_id,
			    				'product_id'  => $product_id,
			    				'serial_code' => $serial_code,
			    				'published'   => $published,
			    			);
						}

						$response['code']    = 200;
						$response['message'] = "Success";
						$response['data']    = $serial_list;
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

		// Distributor Wise Serial No List
		else if($_type == '4')
		{
			$error=FALSE;
			$required = array('product_id', 'distributor_id');
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
		    	$sel_1 = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE `status` = '1' AND `delar_status` = '1' AND `service_status` = '1' AND `dist_id` = '".$distributor_id."' AND `product_id` = ".$product_id." AND `published` = '1'");
				$cou_1 = mysqli_num_rows($sel_1);

				if($cou_1 > 0)
				{
					$serial_list = [];
					foreach ($sel_1 as $key => $value) {
						$serial_id   = !empty($value['id'])?$value['id']:'';
						$category_id = !empty($value['cid'])?$value['cid']:'';
						$product_id  = !empty($value['product_id'])?$value['product_id']:'';
						$serial_code = !empty($value['code'])?$value['code']:'';
						$published   = !empty($value['published'])?$value['published']:'';

						$serial_list[] = array(
		    				'serial_id'   => $serial_id,
		    				'category_id' => $category_id,
		    				'product_id'  => $product_id,
		    				'serial_code' => $serial_code,
		    				'published'   => $published,
		    			);
					}

					$response['code']    = 200;
					$response['message'] = "Success";
					$response['data']    = $serial_list;
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