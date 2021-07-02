<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='edit-stock')
{
	$id = $_POST['id'];
	$extra = mysqli_real_escape_string($conn, trim($_POST['extra']));
	$stk   = mysqli_real_escape_string($conn, trim($_POST['stk']));
	$date  = mysqli_real_escape_string($conn, trim($_POST['date']));

	$ins_date = date('Y-m-d', strtotime($date));

	if($extra !='' && $date != '')
	{
		$result = $stk + $extra;
		// update_stock
		$stk = mysqli_query($conn, "INSERT INTO `ss_item_stk`(`product_id`, `qty`, `date`, `create_date`) VALUES ('".$id."','".$extra."','".$ins_date."',NOW())");
		// update_item
		$qry = "UPDATE `ss_items` SET `extra` = '".$result."' WHERE id = '".$id."'";
    	$insert = mysqli_query($conn, $qry);
        if($insert)
		{
			$response['status']=true;
			$response['message']="<strong>Well done ! </strong>Your Stock Updated Successfully.";
		    echo json_encode($response);
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Stock Can't Updated.";
	        echo json_encode($response);
		}
	}
	elseif($extra == '')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Fill Your Stock Details";
        echo json_encode($response);
	}
	elseif($date == '')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Fill Your Date Field";
        echo json_encode($response);
	}
}
?>