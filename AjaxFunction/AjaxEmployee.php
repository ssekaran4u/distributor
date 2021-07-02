<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-employee')
{
	$name     = mysqli_real_escape_string($conn, trim($_POST['name']));
	$address  = mysqli_real_escape_string($conn, trim($_POST['address']));
	$email    = mysqli_real_escape_string($conn, trim($_POST['email']));
	$mobile   = mysqli_real_escape_string($conn, trim($_POST['mobile']));
	$d_id     = mysqli_real_escape_string($conn, trim($_POST['d_id']));

	//$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_employee` WHERE `published` = '1' AND `name` = '".$name."' AND `address` = '".$address."' AND email = '".$email."' AND mobile = '".$mobile."' ";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($name!='' && $address!='' && $email!='' && $mobile!='' && $d_id!='')
		{
			$qry = "INSERT INTO `ss_employee`(`name`, `address`, `email`, `mobile`, `d_id`, `password`,`createdate`) VALUES ('".$name."', '".$address."', '".$email."', '".$mobile."', '".$d_id."','".md5('123')."',NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Employee Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Employee Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Employee Name";
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
			$response['message']="<strong>Oops ! </strong>Fill Your Mobile NUmber";
	        echo json_encode($response);
		}

		elseif($d_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Shop";
	        echo json_encode($response);
		}

		elseif($address =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Address";
	        echo json_encode($response);
		}
		
			
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Area Already Exist.";
        echo json_encode($response);
	}
}
elseif($_POST['method'] =='edit-employee')
{
	$id=$_POST['bankid'];
   $name      = mysqli_real_escape_string($conn, trim($_POST['name']));
	$address  = mysqli_real_escape_string($conn, trim($_POST['address']));
	$email    = mysqli_real_escape_string($conn, trim($_POST['email']));
	$mobile   = mysqli_real_escape_string($conn, trim($_POST['mobile']));
	$d_id     = mysqli_real_escape_string($conn, trim($_POST['d_id']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_employee` WHERE `published` = '1' AND `name` = '".$name."' AND `address` = '".$address."' AND email = '".$email."' AND mobile = '".$mobile."' ";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($name!='' && $address!='' && $email!='' && $mobile!='' && $d_id!='')
    	{
    		$qry = "UPDATE `ss_employee` SET `name` = '".$name."',
	        	`address` = '".$address."',
	        	`email` = '".$email."',
	        	`mobile` = '".$mobile."',
	        	`d_id` = '".$d_id."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Employee Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Employee Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Employee Name";
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
			$response['message']="<strong>Oops ! </strong>Fill Your Mobile NUmber";
	        echo json_encode($response);
		}

		elseif($d_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Distributors";
	        echo json_encode($response);
		}

		elseif($address =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Address";
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
			$response['message']="<strong>Well done ! </strong>Your Category Bank Successfully.";
	    	echo json_encode($response);
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Category Can't Bank.";
        	echo json_encode($response);
		}	
 	}
 	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Category Can't Bank.";
        echo json_encode($response);
	}	
}
?>