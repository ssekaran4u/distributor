<?php
	include '../inc/config.php';
	if($_POST['method'] =='delete')
	{
		$id = $_POST['id'];

		$selt_1 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_inv_details` WHERE auto_id = '".$id."' LIMIT 0,1"));

		$updt_1 = mysqli_query($conn, "UPDATE `ss_distributor_inv_details` SET `status` = '0' WHERE `auto_id` = '".$id."'");

		$selt_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE id = '".$selt_1->pid."'"));

		$selt_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE deleted = '1' AND status = '1' AND `id` = '".$selt_1->customer_id."' LIMIT 0,1"));

		$extra  = $selt_2->extra + $selt_1->qty;

		$updt_2 = mysqli_query($conn, "UPDATE `ss_items` SET `extra` = '".$extra."' WHERE id = '".$selt_1->pid."'");

		$_b_dis = $selt_1->price * $selt_1->allowance / 100;

		$allowance_val  = $selt_1->qty * round($_b_dis);
		
		$_d_dis = $selt_1->price * $selt_1->d_allowance / 100;

		$d_allowance_val = $selt_1->qty * round($_d_dis);

		$discount = $selt_1->qty * round($selt_1->discount);

        $_sta = $selt_1->qty * round($selt_1->sta);

		$netdis = $allowance_val + $_sta + $d_allowance_val + $discount;

		$sub_to = $selt_1->qty * $selt_1->price;

        $total = $sub_to - $netdis;

        $cre_lt = $selt_3->avl_lmt + $total;

        $updt_3 = mysqli_query($conn, "UPDATE `ss_distributors` SET `avl_lmt` = '".$cre_lt."' WHERE `id` = '".$selt_1->customer_id."'");

        $selt_4 = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE cid = '".$selt_1->cid."' AND product_id = '".$selt_1->pid."' AND sales_no = '".$selt_1->so_no."' ");

        while($resu_4 = mysqli_fetch_object($selt_4))
        {	
        	// $updt_4 = mysqli_query($conn, "UPDATE ss_item_stk SET `sales_no` = '0' WHERE id = '".$resu_4->id."'");

        	$updt_5 = mysqli_query($conn, "UPDATE `ss_item_stk` SET `sales_no` = NULL, `sales_date` = NULL, `status` = '1' WHERE `id` = '".$resu_4->id."'");

        	// $updt_6 = mysqli_query($conn, "UPDATE `ss_item_stk` SET `status` = '1' WHERE `id` = '".$resu_4->id."'");
        }

        if($updt_3)
		{
			$response['status']=true;
			$response['message']="<strong>Well done ! </strong> Deleted Successfully.";
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong> Can't Deleted.";
		}
	}
	echo json_encode($response);
?>