<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-customer')
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
		if($name!='' && $address!='' && $email!='' && $mobile && $credit_lmt!='' && $cname!='' && $state_id!='' && $city_id!='' && $pincode!= '')
		{
			$qry = "INSERT INTO `ss_customers`(`userno`,`userid`,`usertype`,`name`, `address`, `state`, `city`,`pincode`, `email`, `cname`, `gst_no`, `d_allowance`,`phone`, `mobile`, `credit_lmt`, `pre_lmt`, `avl_lmt`,`status`,`createdate`) VALUES ('".$userno."','".$uid."','".$_SESSION['type']."','".$name."','".$address."','".$state_id."','".$city_id."','".$pincode."','".$email."','".$cname."','".$gst_no."', '".$d_allowa."','".$almobile."','".$mobile."', '".$credit_lmt."', '".$credit_lmt."', '".$credit_lmt."', '0', NOW())"; 
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
		elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Customers Name";
	        echo json_encode($response);
		}

		elseif($email =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Email";
	        echo json_encode($response);
		}

		elseif($mobile =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Mobile NUmber";
	        echo json_encode($response);
		}
		elseif($credit_lmt =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Credit Limit";
	        echo json_encode($response);
		}
		elseif($cname =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Company Name";
	        echo json_encode($response);
		}

		elseif($address =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Address";
	        echo json_encode($response);
		}

		elseif($d_allowa =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Delar Allowance";
	        echo json_encode($response);
		}

		elseif($state_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your State";
	        echo json_encode($response);
		}
		elseif($city_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your City";
	        echo json_encode($response);
		}
		elseif($pincode =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Post Code";
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
elseif($_POST['method'] =='edit-customer')
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
	$dist_name    = mysqli_real_escape_string($conn, trim($_POST['dist_name']));
	if($dist_name !='')
	{
		$uid=$dist_name;
	}
	else
	{
		$uid=$_SESSION['uid'];
	}

    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_customers` WHERE `published` = '1' AND `name` = '".$name."' AND email = '".$email."' AND mobile = '".$mobile."' ";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($name!='' && $address!='' && $email!='' && $mobile && $credit_lmt!='' && $cname!='' && $state_id!='' && $city_id!='' && $pincode!= '')if($name!='' || $address!='' || $email!='' || $mobile != '')
    	{

    		$sum_lmt = $credit_lmt - $pre_lmt;

    		$avi_lmt = $avl_lmt + $sum_lmt;

    		$qry = "UPDATE `ss_customers` SET `userid`= '".$uid."',`name`= '".$name."',`address`= '".$address."',`state`= '".$state_id."',`city`= '".$city_id."',`pincode`= '".$pincode."',`email`= '".$email."',`cname`= '".$cname."',`gst_no`= '".$gst_no."',`d_allowance`= '".$d_allowa."',`phone`= '".$almobile."',`mobile`= '".$mobile."',`credit_lmt`='".$credit_lmt."', `pre_lmt` = '".$credit_lmt."', `avl_lmt` = '".$avi_lmt."', `status`  = '".$_POST['pstatus']."', `updatedate`= NOW() WHERE id = '".$id."'";
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
    	elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Customers Name";
	        echo json_encode($response);
		}

		elseif($email =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Email";
	        echo json_encode($response);
		}

		elseif($mobile =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Mobile NUmber";
	        echo json_encode($response);
		}
		elseif($credit_lmt =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Credit Limit";
	        echo json_encode($response);
		}
		elseif($cname =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Company Name";
	        echo json_encode($response);
		}

		elseif($d_allowa =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Delar Allowance";
	        echo json_encode($response);
		}

		elseif($address =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Address";
	        echo json_encode($response);
		}

		elseif($state_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your State";
	        echo json_encode($response);
		}
		elseif($city_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your City";
	        echo json_encode($response);
		}
		elseif($pincode =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Post Code";
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