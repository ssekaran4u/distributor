<?php
include 'config.php';

	$product_id  = isset($_POST["product_id"])?$_POST["product_id"]:'';
	$customer_id = isset($_POST["customer_id"])?$_POST["customer_id"]:'';
	$method      = isset($_POST["method"])?$_POST["method"]:'';
	$error       = FALSE;
	
	$required = array('product_id', 'customer_id', 'method');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error == TRUE)
    {
        $response['status']  = FALSE;
        $response['message'] = "Please fill all required fields"; ;
        echo json_encode($response);
        return;
    }
    else
    {
    	$sel_1 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$product_id."' AND `status` = '1' AND `published` = '1' "));

    	$description = isset($sel_1->description)?$sel_1->description:'-';
		$oprice      = isset($sel_1->oprice)?$sel_1->oprice:'0';
		$gst         = isset($sel_1->gst)?$sel_1->gst:'0';
		$allowance   = isset($sel_1->allowance)?$sel_1->allowance:'0';
		$sta         = isset($sel_1->sta)?$sel_1->sta:'0';
		$hsn         = isset($sel_1->hsn)?$sel_1->hsn:'0';
		$extra       = isset($sel_1->extra)?$sel_1->extra:'0';

    	if($method == 'distri_invoice')
    	{
    		$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_price` WHERE `distributor_id` = '".$customer_id."' AND `product_id` = '".$product_id."' AND `status` = '1' AND `published` = '1' ORDER BY `id` DESC "));

    		$nlc_value = isset($sel_2->nlc_value)?$sel_2->nlc_value:'';

    		if(!empty($nlc_value))
    		{
    			$price_val = $nlc_value;
    		}
    		else
    		{
    			$price_val = $oprice;
    		}
    	}

    	else if($method == 'dealer_invoice')
    	{
    		$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_dealer_price` WHERE `dealer_id` = '".$customer_id."' AND `product_id` = '".$product_id."' AND `status` = '1' AND `published` = '1' ORDER BY `id` DESC "));

            $nlc_value = isset($sel_2->nlc_value)?$sel_2->nlc_value:'';

            if(!empty($nlc_value))
            {
                $price_val = $nlc_value;
            }
            else
            {
                $price_val = $oprice;
            }
    	}

    	$response['status']      = TRUE;
        $response['description'] = $description;
        $response['price_val']   = $price_val;
        $response['gst']         = $gst;
        $response['allowance']   = $allowance;
        $response['sta']         = $sta;
        $response['hsn']         = $hsn;
        $response['extra']       = $extra;
        echo json_encode($response);
        return false;
    }
?>