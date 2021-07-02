<?php
	include '../inc/config.php';
	if($_POST['method'] =='delete')
	{
		$id=$_POST['id'];
		if($_POST['target'] == 'sales')
		{	
			$sal_id  = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_invoice` WHERE `id` = '".$id."' AND `published` = '1'"));

			$cu_amt  = mysqli_fetch_object(mysqli_query($conn, "SELECT `avl_lmt` FROM `ss_distributors` WHERE `id` = '".$sal_id->customer_id."' AND `deleted` = '1'"));

			$sal_amt = mysqli_fetch_object(mysqli_query($conn, "SELECT SUM(`total_cost`) AS `total_cost` FROM `ss_distributor_inv_details` WHERE `so_id` = '".$id."' AND `published` = '1'"));

			$cus_val = $cu_amt->avl_lmt + $sal_amt->total_cost;

			$up_amt = mysqli_query($conn, "UPDATE `ss_distributors` SET `avl_lmt` = '".$cus_val."' WHERE `id` = '".$sal_id->customer_id."'");

			$sal_lt = mysqli_query($conn, "UPDATE `ss_distributor_inv_details` SET `published` = '0' WHERE `so_id` = '".$id."'");

			$sal_up = mysqli_query($conn, "UPDATE `ss_distributor_invoice` SET `published` = '0' WHERE `id` = '".$id."'");

			$upt_ite = mysqli_query($conn, "UPDATE `ss_item_stk` SET `sales_no` = NULL, `sales_date` = NULL, `dist_id` = NULL, `status` = '1', `delar_status` = '1' WHERE `sales_no` = '".$sal_id->so_no."'");

			if($upt_ite)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong> Deleted Successfully.";
				echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong> Can't Deleted.";
				echo json_encode($response);
			}
		}
	}
?>