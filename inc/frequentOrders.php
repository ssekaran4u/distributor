<?php
	include 'config.php';
	$_a = mysqli_query($conn, "SELECT * FROM `ss_payment_status` WHERE `order_status` != 'Failure' AND `_reading` = 1 ");
	$total = mysqli_num_rows($_a);
	$_b = mysqli_query($conn, "SELECT * FROM `ss_payment_status` WHERE `order_status` = 'Failure' AND `_reading` = 1");
	$fail = mysqli_num_rows($_b);
	if($total!=0 || $fail!=0)
	{
		echo '1#'.$total.'#'.$fail;
	}
	else
	{
		echo 0;
	}
?>