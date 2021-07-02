<?php
session_start();
include 'config.php';

if($_POST['method'] == 'update')
{
	$order=$_POST['order'];
	if($order)
	{
	$type = $_POST['type'];
    if($type == 2)
    {
        $status ="Delivered";
    }
    else
    {
        $status ="Success";
    }
    mysqli_query($conn, "UPDATE `ss_delivery_details` SET `status`='".$type."' WHERE `bno`= '".$order."'");
    $qry = "UPDATE `ss_payment_status` SET `order_status`='".$status."' WHERE `order_id`= '".$order."'";
    if(mysqli_query($conn, $qry))
    {
        $response['status']='true';
    }
    else
    {
    	$response['status']='false';	
    }
    echo json_encode($response);
	}  
}
?>