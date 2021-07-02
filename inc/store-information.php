<?php
include 'config.php';
	$error = FALSE;
	$errors = array();
    $required = array('name', 'owner', 'state', 'city', 'address', 'email', 'mobile');
    $image = array('logo');
    
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

    if(count($errors)==0)
    {
		if(isset($_FILES['logo']['name']))
		{
			$img_name = $_FILES['logo']['name'];
            $targetFolder = $_SERVER["DOCUMENT_ROOT"]."/image/logo/";		
			// $targetFolder = "inc/";
			if(isset($_REQUEST["filename"]))
			{
				$file_name = $_REQUEST["filename"];
			}
			else
			{	
				$file_name = $_FILES['logo']['name'];
				$nimextension = @end(explode(".", $file_name)); 
			}	

			$newname = md5(time()).'.'.$nimextension;

			post_img($newname, $_FILES['logo']['tmp_name'],$location);
		}
		$ins ="UPDATE `ss_store_information` SET 
				`name` = '".$_POST['name']."',
				`owner` = '".$_POST['owner']."',
				`state` = '".$_POST['state']."',
				`city` = '".$_POST['city']."',
				`address` = '".$_POST['address']."',
				`email` = '".$_POST['email']."',
				`mobile` = '".$_POST['mobile']."' ";
				if(isset($_FILES['logo']['name']))
				{
					$ins .= " , `logo` = '".$newname."' ";
				}
			$ins .= " , `updatedate`=NOW() WHERE `id` = 1";
        if(mysqli_query($conn, $ins))
        {
        	$response['status'] = TRUE;
        	$response['type']	= 2;
        	$response['message'] = "Successfully updated";
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

    echo json_encode($response);
?>