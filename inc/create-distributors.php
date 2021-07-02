<?php

include 'config.php';



	$error = FALSE;

	$errors = array();

    $required = array('latitude','longitude','address','territory','name','contact','mobile');

    

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



    if(count($errors)==0)

    {

    	if($_POST['method']=='insert')

		{

	        $ins = "INSERT INTO `ss_distributors`(`address`, `territory`, `name`, `contact`, `mobile`, `latitude`, `longitude`, `createdate`) VALUES ('".$_POST['address']."','".$_POST['territory']."','".$_POST['name']."','".$_POST['contact']."','".$_POST['mobile']."','".$_POST['latitude']."','".$_POST['longitude']."',NOW())";

	        if(mysqli_query($conn, $ins))

	        {

	        	$response['status'] = TRUE;

	        	$response['type']	= 1;

	        	$response['message'] = "Successfully distributor created";

	        }

	        else

	        {

	        	$response['status'] = FALSE;

	        	$response['message'] = "Getting error try again";

	        }

	    }

	    if($_POST['method']=='edit')

		{

			$ins ="UPDATE `ss_distributors` SET 

					`address`='".$_POST['address']."',

					`territory`='".$_POST['territory']."',

					`name`='".$_POST['name']."',

					`contact`='".$_POST['contact']."',

					`mobile`='".$_POST['mobile']."',

					`latitude`='".$_POST['latitude']."',

					`longitude`='".$_POST['longitude']."',

					`status`='".$_POST['status']."',

					`updatedate`=NOW() WHERE `id` = '".$_POST['id']."'";

					

	        if(mysqli_query($conn, $ins))

	        {

	        	$response['status'] = TRUE;

	        	$response['type']	= 2;

	        	$response['message'] = "Successfully distributor updated";

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