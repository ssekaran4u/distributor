<?php
session_start();
include '../inc/config.php';

if(isset($_REQUEST['so_no']))	
{
	$so_no       = mysqli_real_escape_string($conn, trim($_POST['so_no']));
	$customer_id = mysqli_real_escape_string($conn, trim($_POST['customer_id']));
	$qry2 = "UPDATE `ss_sales_details` SET `invoice_status`='1' WHERE `so_no` = '".$so_no."' AND `customer_id` = '".$customer_id."'";
    	if(mysqli_query($conn, $qry2))
    	{
    		$response['status']=true;
		    echo json_encode($response);
		    exit();	
    	}
    	else
    	{
    		$response['status']=false;
		    echo json_encode($response);
		    exit();	
    	}
}
else
{
	$response['status']=false;
    echo json_encode($response);
    exit();	
}