<?php
	session_start();
	include 'config.php';
	$branch = isAdminDetails($conn);
	$error = FALSE;
	$errors = array();
	if($_POST['method']=='insert')
	{
    	$required = array('code','percentage','validity');
    }

    else
    {
    	$required = array('code','percentage');	
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
        $errors[] = "Please fill all required fields";   
    }

    if(count($errors)==0)
    {
    	if($_POST['method']=='insert')
		{
			$count = (int)$_POST['validity'];
			$validity =  date('Y-m-d', strtotime(date("Y-m-d"). " + $count days"));
	        $ins = "INSERT INTO `ss_coupon_code`(`coupon_code`, `percentage`, `branch`, `validity`, `created`) VALUES ('".$_POST['code']."','".$_POST['percentage']."', '".$branch[0]."' ,'".$validity."',NOW())";
	        if(mysqli_query($conn, $ins))
	        {
	        	$response['status'] = TRUE;
	        	$response['type']	= 1;
	        	$response['message'] = "Successfully coupon code created";
	        }
	        else
	        {
	        	$response['status'] = FALSE;
	        	$response['message'] = "Getting error try again";
	        }
	    }
	    if($_POST['method']=='update')
		{
			$ins ="UPDATE `ss_coupon_code` SET 
					`coupon_code`='".$_POST['code']."',
					`percentage`='".$_POST['percentage']."',
					`status`='".(int)$_POST['status']."',
					`branch` = '".$branch[0]."',
					`updated`=NOW() WHERE `id` = '".$_POST['id']."'";
	        if(mysqli_query($conn, $ins))
	        {
	        	$response['status'] = TRUE;
	        	$response['type']	= 2;
	        	$response['message'] = "Successfully coupon code updated";
	        }
	        else
	        {
	        	$response['status'] = FALSE;
	        	$response['message'] = "Getting error try again";
	        }
	    }
    }
    if($errors) 
    {
        $response['status'] = FALSE;
    	$response['message'] = $errors;
    }
    echo json_encode($response);
?>