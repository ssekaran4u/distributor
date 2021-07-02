<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['process'] =='distributor')
{
	$importtype=$_POST['tcs_type'];

	$error = FALSE;
	$errors = array();
    $required = array('store','owner','state_id','city_id','address','email','mobile','credit_lmt','avl_lmt','gst_no','excutive','permission');

	if($importtype == 2)
    {
    	array_push($required, 'tcs_no');
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
		$response['message']="Required fields are empty";
	    echo json_encode($response);
	    exit();	
    }
    if($_POST['email'])
    {
	    if (mb_strlen($_POST['email']) > 254 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	    {
	        $response['status']=false;
			$response['message']="Email does not apear to be valid.";
		    echo json_encode($response);
		    exit();	
	    }
	    if($_POST['method']=="add-customer")
	    {
	    	// array_push($required, 'password');	
	    	$eMl = "SELECT * FROM `ss_distributors` WHERE `email_id` = '".$_POST['email']."' AND `status` = 1 AND `deleted` = 1";
		    if(mysqli_num_rows(mysqli_query($conn, $eMl))==1)
		    {
		    	$response['status']=false;
				$response['message']="This Email already exists.";
			    echo json_encode($response);
			    exit();	
		    }
	    }
    }

    if($_POST['mobile'])
	{
	    if (preg_match('#[^0-9]#', $_POST['mobile']) || strlen($_POST['mobile'])!=10)
	    {
	        $response['status']=false;
			$response['message']="Mobile No. does not appear to be valid";
		    echo json_encode($response);
		    exit();	
	    }
	}

    if($_POST['amobile'])
    {
	    if (preg_match('#[^0-9]#', $_POST['mobile']) || strlen($_POST['mobile'])!=10)
	    {
	        $response['status']=false;
			$response['message']="Alternative Mobile No. does not appear to be valid";
		    echo json_encode($response);
		    exit();	
	    }
    }

    $numeric = FALSE;
    foreach (array('credit_lmt','pre_lmt','avl_lmt','d_allowance') as $field) 
    {
        if(!is_numeric($_POST[$field]))
        {
            $numeric = TRUE;
        }
    }

    if($numeric)
    {
	    $response['status']=false;
		$response['message']="Out of range values. Check and confirm";
	    echo json_encode($response);
	    exit();	
    }

    $disno       = mysqli_real_escape_string($conn, trim($_POST['disno']));
    $store       = mysqli_real_escape_string($conn, trim($_POST['store']));
	$owner       = mysqli_real_escape_string($conn, trim($_POST['owner']));
	$state_id    = mysqli_real_escape_string($conn, trim($_POST['state_id']));
	$city_id     = mysqli_real_escape_string($conn, trim($_POST['city_id']));
	$address     = mysqli_real_escape_string($conn, trim($_POST['address']));
	$mobile      = mysqli_real_escape_string($conn, trim($_POST['mobile']));
	$amobile     = mysqli_real_escape_string($conn, trim($_POST['amobile']));
	$credit_lmt  = mysqli_real_escape_string($conn, trim($_POST['credit_lmt']));
	$pre_lmt     = mysqli_real_escape_string($conn, trim($_POST['pre_lmt']));
	$avl_lmt     = mysqli_real_escape_string($conn, trim($_POST['avl_lmt']));
	$gst_no      = mysqli_real_escape_string($conn, trim($_POST['gst_no']));
	$pan_no      = mysqli_real_escape_string($conn, trim($_POST['pan_no']));
	$d_allowance = mysqli_real_escape_string($conn, trim($_POST['d_allowance']));
	$excutive    = mysqli_real_escape_string($conn, trim($_POST['excutive']));
  	$permission  = mysqli_real_escape_string($conn, trim($_POST['permission']));
  	$tcs_type    = mysqli_real_escape_string($conn, trim($_POST['tcs_type']));
	$tcs_no      = mysqli_real_escape_string($conn, trim($_POST['tcs_no']));
	$password    = md5('123');

    if($_POST['method']=="add-customer")
    {
    	$email      = mysqli_real_escape_string($conn, trim($_POST['email']));
    	$qry1 = "INSERT INTO `ss_distributors`(`disno`,`store`, `owner`, `state`, `location`, `customeraddress`, `gst`,`pan_no`, `email_id`, `mobile`, `amobile`, `credit_lmt`, `pre_lmt`, `avl_lmt`, `d_allowance`, `excutive`, `password`, `permission`, `tcs_type`, `tcs_no`, `createdate`) VALUES ('".$disno."','".$store."','".$owner."','".$state_id."','".$city_id."','".$address."','".$gst_no."','".$pan_no."','".$email."','".$mobile."','".$amobile."','".$credit_lmt."','".$credit_lmt."','".$credit_lmt."','".$d_allowance."', '".$excutive."','".$password."', '".$permission."', '".$tcs_type."', '".$tcs_no."', NOW())";
    	if(mysqli_query($conn, $qry1))
    	{
    		$response['status']=true;
    		$response['type']=1;
			$response['message']="Distributor added Successfully";
		    echo json_encode($response);
		    exit();	
    	}
    	else
    	{
    		$response['status']=false;
			$response['message']="Distributor added error";
		    echo json_encode($response);
		    exit();	
    	}
    }
    else
    {
    	$editid = $_POST['editid'];
    	$status = $_POST['status'];
      	
      	$values = $pre_lmt - $avl_lmt;
    	$result = $credit_lmt - $values;
      
    	$qry2 = "UPDATE `ss_distributors` SET `store`='".$store."',`owner`='".$owner."',`state`='".$state_id."',`location`='".$city_id."',`customeraddress`='".$address."',`gst`='".$gst_no."',`pan_no`='".$pan_no."',`mobile`='".$mobile."',`amobile`='".$amobile."',`credit_lmt`='".$credit_lmt."',`pre_lmt`='".$credit_lmt."',`avl_lmt`='".$result."',`d_allowance`='".$d_allowance."',`excutive`='".$excutive."',  `status`='".$status."', `permission`='".$permission."', `tcs_type`= '".$tcs_type."', `tcs_no`= '".$tcs_no."', `updatedate`=NOW() WHERE `id` = '".$editid."'";
    	if(mysqli_query($conn, $qry2))
    	{
    		$response['status']=true;
			$response['message']="Distributor update Successfully";
		    echo json_encode($response);
		    exit();	
    	}
    	else
    	{
    		$response['status']=false;
			$response['message']="Distributor update error";
		    echo json_encode($response);
		    exit();	
    	}
    }


    

	die();
	
	//$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_customers` WHERE `published` = '1' AND `name` = '".$name."' AND email = '".$email."' AND mobile = '".$mobile."' ";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($name!='' && $address!='' && $email!='' && $mobile && $credit_lmt!='' && $cname!='' && $state_id!='' && $city_id!='' && $pincode!= '')
		{
			$qry = "INSERT INTO `ss_customers`(`name`, `address`, `state`, `city`,`pincode`, `email`, `cname`, `gst_no`, `d_allowance`,`phone`, `mobile`, `credit_lmt`, `pre_lmt`, `avl_lmt`,`createdate`) VALUES ('".$name."','".$address."','".$state_id."','".$city_id."','".$pincode."','".$email."','".$cname."','".$gst_no."', '".$d_allowa."','".$almobile."','".$mobile."', '".$credit_lmt."', '".$credit_lmt."', '".$credit_lmt."', NOW())"; 
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

	$importtype=$_POST['tcs_type'];

	$error = FALSE;
	$errors = array();
    $required = array('store','owner','state_id','city_id','address','email','mobile','credit_lmt','avl_lmt','gst_no','excutive','permission');

	if($importtype == 2)
    {
    	array_push($required, 'tcs_no');
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
		$response['message']="Required fields are empty";
	    echo json_encode($response);
	    exit();	
    }

    if($_POST['email'])
    {
	    if (mb_strlen($_POST['email']) > 254 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	    {
	        $response['status']=false;
			$response['message']="Email does not apear to be valid.";
		    echo json_encode($response);
		    exit();	
	    }
	    if($_POST['method']=="add-customer")
	    {
	    	// array_push($required, 'password');	
	    	$eMl = "SELECT * FROM `ss_distributors` WHERE `email_id` = '".$_POST['email']."' AND `status` = 1 AND `deleted` = 1";
		    if(mysqli_num_rows(mysqli_query($conn, $eMl))==1)
		    {
		    	$response['status']=false;
				$response['message']="This Email already exists.";
			    echo json_encode($response);
			    exit();	
		    }
	    }
    }

    if($_POST['mobile'])
	{
	    if (preg_match('#[^0-9]#', $_POST['mobile']) || strlen($_POST['mobile'])!=10)
	    {
	        $response['status']=false;
			$response['message']="Mobile No. does not appear to be valid";
		    echo json_encode($response);
		    exit();	
	    }
	}

    if($_POST['amobile'])
    {
	    if (preg_match('#[^0-9]#', $_POST['mobile']) || strlen($_POST['mobile'])!=10)
	    {
	        $response['status']=false;
			$response['message']="Alternative Mobile No. does not appear to be valid";
		    echo json_encode($response);
		    exit();	
	    }
    }

    $numeric = FALSE;
    foreach (array('credit_lmt','pre_lmt','avl_lmt','d_allowance') as $field) 
    {
        if(!is_numeric($_POST[$field]))
        {
            $numeric = TRUE;
        }
    }

    if($numeric)
    {
	    $response['status']=false;
		$response['message']="Out of range values. Check and confirm";
	    echo json_encode($response);
	    exit();	
    }

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
	$tcs_type    = mysqli_real_escape_string($conn, trim($_POST['tcs_type']));
	$tcs_no      = mysqli_real_escape_string($conn, trim($_POST['tcs_no']));

    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_customers` WHERE `published` = '1' AND `name` = '".$name."' AND email = '".$email."' AND mobile = '".$mobile."' AND `id` != '".$id."' ";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
		$sum_lmt = $credit_lmt - $pre_lmt;

		$avi_lmt = $avl_lmt + $sum_lmt;

		$qry = "UPDATE `ss_customers` SET `name`= '".$name."',`address`= '".$address."',`state`= '".$state_id."',`city`= '".$city_id."',`pincode`= '".$pincode."',`email`= '".$email."',`cname`= '".$cname."',`gst_no`= '".$gst_no."',`d_allowance`= '".$d_allowa."',`phone`= '".$almobile."',`mobile`= '".$mobile."',`credit_lmt`='".$credit_lmt."', `pre_lmt` = '".$credit_lmt."', `avl_lmt` = '".$avi_lmt."', `tcs_type`= '".$tcs_type."', `tcs_no`= '".$tcs_no."', `updatedate`= NOW() WHERE id = '".$id."'";
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