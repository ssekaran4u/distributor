<?php 
session_start();
include 'config.php';
$branch = isAdminDetails($conn);

 $order_id=$_POST['order_id'];
 if($_POST['method'] =='update')
 {
 	if($order_id)
	{
		$update = mysqli_query($conn,"UPDATE `ss_payment_status` SET `failure_status`='2' WHERE `order_id` ='".$order_id."'");
	    if($update)
	    {
	    	 echo "success";
	    }
	    else
	    {
	    	echo "failure";
	    }
	   
	}else
	{
		echo "failure";
	}
 }
 if($_POST['method'] =='total')
 {

 }
