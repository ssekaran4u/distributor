<?php
session_start();
include '../inc/config.php';
// $branch = isAdminDetails($conn);
if($_POST['method'] =='edit-payment')
{
	// $so_id       = mysqli_real_escape_string($conn, trim($_POST['so_id']));
	// $so_no       = mysqli_real_escape_string($conn, trim($_POST['so_no']));
	$customer_id = mysqli_real_escape_string($conn, trim($_POST['customer_id']));
	$balance     = mysqli_real_escape_string($conn, trim($_POST['balance']));
	$amount      = mysqli_real_escape_string($conn, trim($_POST['amount']));
	$type        = mysqli_real_escape_string($conn, trim($_POST['type']));
	$description = mysqli_real_escape_string($conn, trim($_POST['description']));
	$date        = mysqli_real_escape_string($conn, trim($_POST['date']));

	if($amount!='' && $date!='')
	{
		if($balance >= $amount)
		{
			$cre_lmt = mysqli_fetch_object(mysqli_query($conn, "SELECT `avl_lmt` FROM `ss_customers` WHERE `id` = '".$customer_id."' AND published = '1' AND status = '1'"));

			$up_valu = $cre_lmt->avl_lmt + $amount;

			$qry_2 = mysqli_query($conn, "UPDATE `ss_customers` SET `avl_lmt` = '".$up_valu."' WHERE id = '".$customer_id."'");

			$qry = mysqli_query($conn, "INSERT INTO `ss_payment`(`customer_id`, `amount`, `type`, `description`, `date`, `createdate`) VALUES ('".$customer_id."','".$amount."','".$type."','".$description."','".$date."', NOW())");
			if($qry)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Sales Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Sales Can't Added.";
		        echo json_encode($response);
			}
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Enter Amount Greater than Bill Amount.";
	        echo json_encode($response);
		}
	}
	elseif($amount =='')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Fill Your Amount";
        echo json_encode($response);
	}
	elseif($description =='')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Fill Your Description";
        echo json_encode($response);
	}
	elseif($date =='')
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Select Your Payment Date";
        echo json_encode($response);
	}
}
?>