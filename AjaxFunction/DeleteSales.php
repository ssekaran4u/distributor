<?php
	include '../inc/config.php';
	if($_POST['method'] =='delete')
	{
		$id=$_POST['id'];
		if($_POST['target'] == 'sales')
		{	
			$sal_id  = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_sales` WHERE `id` = '".$id."' AND `published` = '1'"));

			$cu_amt  = mysqli_fetch_object(mysqli_query($conn, "SELECT `avl_lmt` FROM `ss_customers` WHERE `id` = '".$sal_id->customer_id."' AND `published` = '1'"));

			$sal_amt = mysqli_fetch_object(mysqli_query($conn, "SELECT SUM(`total_cost`) AS `total_cost` FROM `ss_sales_details` WHERE `so_id` = '".$id."' AND `published` = '1'"));

			$cus_val = $cu_amt->avl_lmt + $sal_amt->total_cost;

			$up_amt = mysqli_query($conn, "UPDATE `ss_customers` SET `avl_lmt` = '".$cus_val."' WHERE `id` = '".$sal_id->customer_id."'");

			$sal_lt = mysqli_query($conn, "UPDATE `ss_sales_details` SET `published` = '0' WHERE `so_id` = '".$id."'");

			$sal_up = mysqli_query($conn, "UPDATE `ss_sales` SET `published` = '0' WHERE `id` = '".$id."'");

			$upt_ite = mysqli_query($conn, "UPDATE `ss_item_stk` SET `delar_sales` = NULL, `d_sales_date` = NULL, `delar_id` = NULL, `delar_status` = '1' WHERE `delar_sales` = '".$sal_id->so_no."'");

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