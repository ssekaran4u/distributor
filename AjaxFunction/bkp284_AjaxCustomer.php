<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-customer')
{

	$importtype=$_POST['tcs_type'];
	$error = FALSE;

	$errors = array();
    if($importtype == 1)
    {
    	$required = array('dist_name' ,'name', 'email', 'mobile', 'credit_lmt', 'cname', 'd_allowance', 'address', 'state_id', 'pincode', 'excutive');
    }
    else if($importtype == 2)
    {
    	$required = array('dist_name' ,'name', 'email', 'mobile', 'credit_lmt', 'cname', 'd_allowance', 'address', 'state_id', 'pincode', 'excutive', 'tcs_no');
    }
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error)
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Please Fill All Fields.";
        echo json_encode($response);
	}
	else
	{
		$userno     = mysqli_real_escape_string($conn, trim($_POST['userno']));
		$name       = mysqli_real_escape_string($conn, trim($_POST['name']));
		$address    = mysqli_real_escape_string($conn, trim($_POST['address']));
		$email      = mysqli_real_escape_string($conn, trim($_POST['email']));
		$mobile     = mysqli_real_escape_string($conn, trim($_POST['mobile']));
		$credit_lmt = mysqli_real_escape_string($conn, trim($_POST['credit_lmt']));
		$cname      = mysqli_real_escape_string($conn, trim($_POST['cname']));
		$gst_no     = mysqli_real_escape_string($conn, trim($_POST['gst_no']));
		$almobile   = mysqli_real_escape_string($conn, trim($_POST['almobile']));
		$d_allowa   = mysqli_real_escape_string($conn, trim($_POST['d_allowance']));
		$state_id   = mysqli_real_escape_string($conn, trim($_POST['state_id']));
		$city_id    = mysqli_real_escape_string($conn, trim($_POST['city_id']));
		$pincode    = mysqli_real_escape_string($conn, trim($_POST['pincode']));
		$dist_name    = mysqli_real_escape_string($conn, trim($_POST['dist_name']));
		$excutive    = mysqli_real_escape_string($conn, trim($_POST['excutive']));
		$tcs_type   = mysqli_real_escape_string($conn, trim($_POST['tcs_type']));
		$tcs_no     = mysqli_real_escape_string($conn, trim($_POST['tcs_no']));
		if($dist_name !='')
		{
			$uid=$dist_name;
		}
		else
		{
			$uid=$_SESSION['uid'];
		}
		//$subcat = (int)$_POST['subcat'];
		$exits = "SELECT * FROM `ss_customers` WHERE `published` = '1' AND `name` = '".$name."' AND email = '".$email."' AND mobile = '".$mobile."' ";
		$data = mysqli_query($conn, $exits);
		$rsrow = mysqli_num_rows($data);
		if($rsrow==0)
		{
			$qry = "INSERT INTO `ss_customers`(`userno`,`userid`,`usertype`,`name`, `address`, `state`, `city`,`pincode`, `email`, `cname`, `gst_no`, `d_allowance`, `excutive`,`phone`, `mobile`, `credit_lmt`, `pre_lmt`, `avl_lmt`,`status`, `tcs_type`, `tcs_no`, `createdate`) VALUES ('".$userno."','".$uid."','".$_SESSION['type']."','".$name."','".$address."','".$state_id."','".$city_id."','".$pincode."','".$email."','".$cname."','".$gst_no."', '".$d_allowa."', '".$excutive."','".$almobile."','".$mobile."', '".$credit_lmt."', '".$credit_lmt."', '".$credit_lmt."', '0', '".$tcs_type."', '".$tcs_no."', NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Customers Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Customers Can't Added.";
		        echo json_encode($response);
			}	
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Customer Already Exist.";
	        echo json_encode($response);
		}
	}
}
elseif($_POST['method'] =='edit-customer')
{	
	$importtype=$_POST['tcs_type'];
	$error = FALSE;

	$errors = array();
    if($importtype == 1)
    {
    	$required = array('dist_name' ,'name', 'email', 'mobile', 'credit_lmt', 'cname', 'd_allowance', 'address', 'state_id', 'pincode', 'excutive');
    }
    else if($importtype == 2)
    {
    	$required = array('dist_name' ,'name', 'email', 'mobile', 'credit_lmt', 'cname', 'd_allowance', 'address', 'state_id', 'pincode', 'excutive', 'tcs_no');
    }
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error)
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Please Fill All Fields.";
        echo json_encode($response);
	}
	else
	{
		$id=$_POST['cusid'];
	   	$name       = mysqli_real_escape_string($conn, trim($_POST['name']));
		$address    = mysqli_real_escape_string($conn, trim($_POST['address']));
		$email      = mysqli_real_escape_string($conn, trim($_POST['email']));
		$mobile     = mysqli_real_escape_string($conn, trim($_POST['mobile']));
		$credit_lmt = mysqli_real_escape_string($conn, trim($_POST['credit_lmt']));
		$pre_lmt    = mysqli_real_escape_string($conn, trim($_POST['pre_lmt']));
		$avl_lmt    = mysqli_real_escape_string($conn, trim($_POST['avl_lmt']));
		$cname      = mysqli_real_escape_string($conn, trim($_POST['cname']));
		$gst_no     = mysqli_real_escape_string($conn, trim($_POST['gst_no']));
		$almobile   = mysqli_real_escape_string($conn, trim($_POST['almobile']));
		$d_allowa   = mysqli_real_escape_string($conn, trim($_POST['d_allowance']));
		$state_id   = mysqli_real_escape_string($conn, trim($_POST['state_id']));
		$city_id    = mysqli_real_escape_string($conn, trim($_POST['city_id']));
		$pincode    = mysqli_real_escape_string($conn, trim($_POST['pincode']));
		$dist_name  = mysqli_real_escape_string($conn, trim($_POST['dist_name']));
		$excutive   = mysqli_real_escape_string($conn, trim($_POST['excutive']));
		$tcs_type   = mysqli_real_escape_string($conn, trim($_POST['tcs_type']));
		$tcs_no     = mysqli_real_escape_string($conn, trim($_POST['tcs_no']));
		if($dist_name !='')
		{
			$uid=$dist_name;
		}
		else
		{
			$uid=$_SESSION['uid'];
		}

		$exits = mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `published` = '1' AND `name` = '".$name."' AND email = '".$email."' AND mobile = '".$mobile."' AND id != '".$id."'");
	    $data = mysqli_num_rows($exits);
	    if($data == 0)
	    {
			$sum_lmt = $credit_lmt - $pre_lmt;

			$avi_lmt = $avl_lmt + $sum_lmt;

			$qry = "UPDATE `ss_customers` SET `userid`= '".$uid."',`name`= '".$name."',`address`= '".$address."',`state`= '".$state_id."',`city`= '".$city_id."',`pincode`= '".$pincode."',`email`= '".$email."',`cname`= '".$cname."',`gst_no`= '".$gst_no."',`d_allowance`= '".$d_allowa."', `excutive`= '".$excutive."', `phone`= '".$almobile."',`mobile`= '".$mobile."',`credit_lmt`='".$credit_lmt."', `pre_lmt` = '".$credit_lmt."', `avl_lmt` = '".$avi_lmt."', `status`  = '".$_POST['pstatus']."', `tcs_type`= '".$tcs_type."', `tcs_no`= '".$tcs_no."', `updatedate`= NOW() WHERE id = '".$id."'";
	    	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Customers Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Customers Can't Updated.";
		        echo json_encode($response);
			}
	    }
	    else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Data Already Exist.";
	    	echo json_encode($response);
		}
	}

}
elseif($_POST['method'] =='delete-bank')
{
	$cateid=$_POST['cateid'];
 	if($cateid !='')
 	{
 		$qry = mysqli_query($conn, "UPDATE `ss_bank` SET `published` = 2 WHERE  `id` = '".$cateid."' ");
 		if($qry)
		{
			$response['status']=true;
			$response['message']="<strong>Well done ! </strong>Your Customers Delete Successfully.";
	    	echo json_encode($response);
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Customers Can't Delete.";
        	echo json_encode($response);
		}	
 	}
 	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Customers Can't Delete.";
        echo json_encode($response);
	}	
}
?>