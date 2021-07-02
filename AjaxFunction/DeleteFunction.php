<?php
include '../inc/config.php';
if($_POST['method'] =='delete')
{
	$id=$_POST['id'];
	$target = $_POST['target'];
	switch ($target) {
		case 'coupon':
		$tabname = 'ss_coupon_code';
		$whrfield = 'published';
		break;

		case 'customer':
		$tabname = 'ss_customers';
		$whrfield = 'published';
		break;

		case 'city':
		$tabname = 'ss_citys';
		$whrfield = 'published';
		break;

		case 'distributors':
		$tabname = 'ss_distributors';
		$whrfield = 'published';
		break;

		case 'employee':
		$tabname = 'ss_sales_executive';
		$whrfield = 'published';
		break;

		case 'state':
		$tabname = 'ss_state';
		$whrfield = 'published';
		break;

		case 'delarallowance':
		$tabname = 'ss_delar_allowance';
		$whrfield = 'published';
		break;

		case 'allowance':
		$tabname = 'ss_allowance';
		$whrfield = 'published';
		break;

		case 'order':
		$tabname = 'ss_payment_status';
		$whrfield = 'status';
		$fetch=mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `ss_payment_status` WHERE `order_id`='".$id."'"));
		$id = $fetch->id;
		break;

		case 'category':
		$tabname = 'ss_category';
		$whrfield = 'published';
		break;

		case 'brand':
		$tabname = 'ss_brands';
		$whrfield = 'published';
		break;

		default:
		$tabname = 'ss_items';
		$whrfield = 'published';
		break;
	}

	$qry =  "UPDATE `".$tabname."` SET `".$whrfield."` = 2 WHERE `id` = '$id'";
	$qry = mysqli_query($conn, $qry);
	if($qry)
	{
		$response['status']=true;
		$response['message']="<strong>Well done ! </strong> Deleted Successfully.";
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong> Can't Deleted.";
	}	
}
echo json_encode($response);
?>