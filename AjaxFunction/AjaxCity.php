<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-city')
{
	$state_name   = mysqli_real_escape_string($conn, trim($_POST['state_name']));
    $name         = mysqli_real_escape_string($conn, trim($_POST['name']));
	//$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_city` WHERE `city_name` = '".$name."' AND `published` = '1'";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($name!='')
		{
			$qry = "INSERT INTO `ss_city`(`state_id`, `city_name`, `createdate`) VALUES ('".$state_name."' ,'".$name."', NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your City Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your City Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($state_name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your State Name";
	        echo json_encode($response);
		}
		elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your City Name";
	        echo json_encode($response);
		}
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your City Already Exist.";
        echo json_encode($response);
	}
}
elseif($_POST['method'] =='edit-city')
{
	$id=$_POST['cityid'];
    $state_name   = mysqli_real_escape_string($conn, trim($_POST['state_name']));
    $name         = mysqli_real_escape_string($conn, trim($_POST['name']));
    $exits = "SELECT * FROM `ss_city` WHERE `city_name` = '".$name."' AND `published` = '1'";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($state_name!='' && $name!='')
    	{
    		$qry = "UPDATE `ss_city` SET `state_id` = '".$state_name."',
    			`city_name` = '".$name."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your City Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your City Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	elseif($state_name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your State Name";
	        echo json_encode($response);
		}
    	elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your City Name";
	        echo json_encode($response);
		}
    }
}
elseif($_POST['method'] =='delete-category')
{
	$cateid=$_POST['cateid'];
 	if($cateid !='')
 	{
 		$qry = mysqli_query($conn, "UPDATE `ss_category` SET `published` = 2 WHERE  `id` = '".$cateid."' ");
 		if($qry)
		{
			$response['status']=true;
			$response['message']="<strong>Well done ! </strong>Your Category Deleted Successfully.";
	    	echo json_encode($response);
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Category Can't Deleted.";
        	echo json_encode($response);
		}	
 	}
 	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Category Can't Deleted.";
        echo json_encode($response);
	}	
}
?>