<?php
include 'config.php';
if($_POST['method']=='insert')
{
	$error = FALSE;
	$errors = array();
    $required = array('cname','city','mobile','email','address','username','password');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($_POST['mobile'])
    {
        if (preg_match('#[^0-9]#', $_POST['mobile']) || strlen($_POST['mobile'])!=10)
        {
            $errors[] = 'Mobile No. does not appear to be valid';
        }
    }
    if($_POST['email'])
    {
	    if (mb_strlen($_POST['email']) > 254 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	    {
	        $errors[] = 'E-mail address does not appear to be valid';
	    }
    }

    if($_POST['city'])
    {
    	$data = mysqli_query($conn, "SELECT `city` FROM `2k18c_branches` WHERE `city` = '".$_POST['city']."' AND `status` = 1 AND `deleted` = 1 ");
    	if(mysqli_num_rows($data)==1)
    	{
    		$errors[] = $_POST['city'] . " already exited. ";		
    	}
    }

    if($_POST['username'])
    {
    	$data = mysqli_query($conn, "SELECT `username` FROM `2k18c_branches` WHERE `username` = '".$_POST['city']."' AND  `status` = 1 AND `deleted` = 1 ");
    	if(mysqli_num_rows($data)==1)
    	{
    		$errors[] = $_POST['username'] . " already exited. ";		
    	}
    }

    if($error)
    {
        $errors[] = "Please fill all required fields";   
    }

    if(count($errors)==0)
    {
    	$ins = "INSERT INTO `2k18c_branches`(`cname`, `city`, `mobile`, `email`, `address`, `username`, `password`, `createdate`) VALUES ('".$_POST['cname']."', '".$_POST['city']."', '".$_POST['mobile']."', '".$_POST['email']."', '".$_POST['address']."', '".$_POST['username']."', '".md5($_POST['password'])."', NOW())";
        if(mysqli_query($conn, $ins))
        {
        	$response['status'] = TRUE;
        	$response['type']	= 1;
        	$response['message'] = "Successfully branch created";
        }
        else
        {
        	$response['status'] = FALSE;
        	$response['message'] = "Getting error try again";
        }
    }
    if($errors) 
    {
        $response['status'] = FALSE;
    	$response['message'] = $errors;
    }
}
if($_POST['method']=='update')
{
	$error = FALSE;
	$errors = array();
    $required = array('cname','mobile','email','address');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($_POST['mobile'])
    {
        if (preg_match('#[^0-9]#', $_POST['mobile']) || strlen($_POST['mobile'])!=10)
        {
            $errors[] = 'Mobile No. does not appear to be valid';
        }
    }
    if($_POST['email'])
    {
	    if (mb_strlen($_POST['email']) > 254 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	    {
	        $errors[] = 'E-mail address does not appear to be valid';
	    }
    }

    if($error)
    {
        $errors[] = "Please fill all required fields";   
    }

    if(count($errors)==0)
    {
    	$ins = "UPDATE `2k18c_branches` SET 
    	`cname`= '".$_POST['cname']."', 
    	`mobile`= '".$_POST['mobile']."', 
    	`email`= '".$_POST['email']."', 
    	`address`= '".$_POST['address']."' ";
    	if($_POST['password'])
    	{
    		$ins .= " , `password`= '".md5($_POST['password'])."' "; 
    	}
    	$ins .= " , `status`= '".$_POST['status']."', 
    	`updateate`= NOW()  WHERE `id` = '".$_POST['id']."' ";
    	// echo $ins; die();
        if(mysqli_query($conn, $ins))
        {
        	$response['status'] = TRUE;
        	$response['type']	= 1;
        	$response['message'] = "Successfully branch updated";
        }
        else
        {
        	$response['status'] = FALSE;
        	$response['message'] = "Getting error try again";
        }
    }
    if($errors) 
    {
        $response['status'] = FALSE;
    	$response['message'] = $errors;
    }
}
echo json_encode($response);
?>