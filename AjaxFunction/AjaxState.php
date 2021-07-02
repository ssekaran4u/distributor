<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-state')
{
	$name         = mysqli_real_escape_string($conn, trim($_POST['name']));
	//$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_state` WHERE `published` = '1' AND `state_name` = '".$name."'";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($name!='')
		{
			$qry = "INSERT INTO `ss_state`(`state_name`, `createdate`) VALUES ('".$name."', NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your State Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your State Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your State Name";
	        echo json_encode($response);
		}
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your State Already Exist.";
        echo json_encode($response);
	}
}
elseif($_POST['method'] =='edit-state')
{
	$id=$_POST['stateid'];
    $name         = mysqli_real_escape_string($conn, trim($_POST['name']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_state` WHERE `state_name` = '".$name."' AND `published` = '1'";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($name!='')
    	{
    		$qry = "UPDATE `ss_state` SET `state_name` = '".$name."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your State Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your State Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	else
    	{
    		$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your State Name";
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