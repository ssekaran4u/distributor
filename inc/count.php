<?php
	include 'config.php';

	$cou = mysqli_query($conn, "UPDATE `ss_items` SET  `extra` = '0'");

	$qry = mysqli_query($conn, "SELECT COUNT(`id`) AS `id`, `product_id` FROM `ss_item_stk` WHERE `delar_status` = '1' AND `service_status` = '1' AND `status` = '1' AND `published` = '1' GROUP BY `product_id`");

	while($row = mysqli_fetch_object($qry))
	{
		$upt = mysqli_query($conn, "UPDATE `ss_items` SET  `extra` = '".$row->id."' WHERE `id` = '".$row->product_id."'");
	}

	if($cou)
	{
		echo "Success";
	}
	else
	{
		echo "Not Success";
	}

	
?>
