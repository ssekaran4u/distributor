<?php
session_start();
include '../inc/config.php';
$datas = array('55SUH180242','55SUH180036');
foreach($datas as $rawRow)
{
	$row = str_getcsv($rawRow, ",");
    $code = $row[0];
    $sel_1 = mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE code = '".$code."' AND status = '1'");
	$row_1 = mysqli_num_rows($sel_1) > 0;
	if($row_1)
	{
		echo "string";
		$res_1 = mysqli_fetch_object($sel_1);
		$upt_1 = mysqli_query($conn, "UPDATE `ss_item_stk` SET `dist_id`='".$customer_id."',`sales_no` = '".$so_no."' ,`status` = '0' WHERE `code` = '".$code."' ");

		$sel_2 = mysqli_query($conn, "SELECT * FROM `ss_distributor_inv_details` WHERE `published` = '1' AND status = '1' AND `so_no` = '".$so_no."' AND pid = '".$res_1->product_id."'");

		$row_2 = mysqli_num_rows($sel_2);
		if($row_2==0)
		{
			$sel_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `published` = '1' AND `id` = '".$res_1->product_id."'"));

			$sel_4 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributors` WHERE `deleted` = '1' AND `id` = '".$customer_id."'"));
			$_b_dis = $sel_3->oprice * $sel_3->allowance / 100;		
			$_d_dis = $sel_3->oprice * $sel_4->d_allowance / 100;
			$_disco = $_b_dis + $sel_3->sta + $_d_dis;
			$_total = $sel_3->oprice - $_disco;
			$_tot   = $qty *  $_total;
			if($sel_4->avl_lmt > $_tot)
			{
				$cre_lmt = $sel_4->avl_lmt - $_tot;

				$upt_2 = mysqli_query($conn, "UPDATE `ss_distributors` SET `avl_lmt` = '".$cre_lmt."' WHERE id = '".$customer_id."'");

				$sel_5 = mysqli_query($conn, "SELECT `id` FROM `ss_distributor_invoice` WHERE `so_no` = '".$so_no."' AND `published` = '1'");

				$row_3 = mysqli_num_rows($sel_5);

				if($row_3 > 0)
				{
					$res_2 = mysqli_fetch_object($sel_5);
					$so_id = $res_2->id;

					echo "SELECT `pid` FROM ss_distributor_inv_details WHERE `so_id` = '".$so_id."' AND `pid` = '".$sel_3->id."'";
					exit();

					// $sel_7 = mysqli_query($conn, "SELECT `pid` FROM ss_distributor_inv_details WHERE `so_id` = '".$so_id."' AND `pid` = '".$sel_3->id."'");

					// $row_7 = mysqli_num_rows($sel_7);
					// if($row_7 > 0)
					// {	
					// 	echo "SELECT SUM(`qty`) AS `qty` FROM ss_distributor_inv_details WHERE `so_id` = '".$so_id."' AND `pid` = '".$sel_3->id."'";

					// 	// $sel_8 = mysqli_query(mysqli_query($conn, "SELECT SUM(`qty`) AS `qty` FROM ss_distributor_inv_details WHERE `so_id` = '".$so_id."' AND `pid` = '".$sel_3->id."'"));

					// 	$t_qty = $sel_8->qty + 1;

					// 	echo "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `total_cost`, `createdate`) VALUES ('".$so_re."','".$so_no."','".$customer_id."','".$sel_3->cid."','".$sel_3->id."', '".$sel_3->description."','".$sel_3->hsn."','".$t_qty."','".$sel_3->oprice."','".$sel_3->gst."','".$sel_3->allowance."','".$sel_3->sta."','".$sel_4->d_allowance."', '".$_tot."',NOW())";

					// 	// $ins_1 = mysqli_query($conn, "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `total_cost`, `createdate`) VALUES ('".$so_re."','".$so_no."','".$customer_id."','".$sel_3->cid."','".$sel_3->id."', '".$sel_3->description."','".$sel_3->hsn."','".$t_qty."','".$sel_3->oprice."','".$sel_3->gst."','".$sel_3->allowance."','".$sel_3->sta."','".$sel_4->d_allowance."', '".$_tot."',NOW())");
					// }
					// else
					// {	
					// 	$ins_1 = mysqli_query($conn, "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `total_cost`, `createdate`) VALUES ('".$so_re."','".$so_no."','".$customer_id."','".$sel_3->cid."','".$sel_3->id."', '".$sel_3->description."','".$sel_3->hsn."','".$qty."','".$sel_3->oprice."','".$sel_3->gst."','".$sel_3->allowance."','".$sel_3->sta."','".$sel_4->d_allowance."', '".$_tot."',NOW())");
					// }

					// $sel_6 = mysqli_fetch_object(mysqli_query($conn, "SELECT extra FROM `ss_items` WHERE id = '".$sel_3->id."' AND published = '1' AND status = '1'"));

					// $stock_re = $sel_6->extra - $qty;

					// $upt_3 = mysqli_query($conn, "UPDATE `ss_items` SET `extra`= '".$stock_re."' WHERE id = '".$sel_3->id."' ");

					exit();
				}
				else
				{
					$ins_2 = mysqli_query($conn, "INSERT INTO `ss_distributor_invoice`(`userid`,`so_no`, `customer_id`,  `order_date`, `order_no`, `dated`, `docu_no`, `despatched`, `createdate`) VALUES ('".$_SESSION['uid']."','".$so_no."','".$customer_id."', '".date('Y-m-d')."','".$order_no."','".date('Y-m-d', strtotime($dated))."','".$docu_no."','".$despatched."',NOW())");
					$so_re = mysqli_insert_id($conn);

					$ins_3 = mysqli_query($conn, "INSERT INTO `ss_distributor_inv_details`(`so_id`, `so_no`, `customer_id`, `cid`, `pid`, `code`, `hsn`, `qty`, `price`, `gst`, `allowance`, `sta`, `d_allowance`, `total_cost`, `createdate`) VALUES ('".$so_re."','".$so_no."','".$customer_id."','".$sel_3->cid."','".$sel_3->id."', '".$sel_3->description."','".$sel_3->hsn."','".$qty."','".$sel_3->oprice."','".$sel_3->gst."','".$sel_3->allowance."','".$sel_3->sta."','".$sel_4->d_allowance."', '".$_tot."',NOW())");

					$sel_6 = mysqli_fetch_object(mysqli_query($conn, "SELECT extra FROM `ss_items` WHERE id = '".$sel_3->id."' AND published = '1' AND status = '1'"));

					$stock_re = $sel_6->extra - $qty;

					$upt_3 = mysqli_query($conn, "UPDATE `ss_items` SET `extra`= '".$stock_re."' WHERE id = '".$sel_3->id."' ");
				}
			}
		}
	}
	// $k++;
}