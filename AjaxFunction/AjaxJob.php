<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-job')
{
	$name        = mysqli_real_escape_string($conn, trim($_POST['name']));
	$description = mysqli_real_escape_string($conn, trim($_POST['description']));
	//$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_job` WHERE `published` = '1' AND `name` = '".$name."' ";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($name!='' && $description!='')
		{
			$qry = "INSERT INTO `ss_job`(`name`, `description`, `createdate`) VALUES ('".$name."', '".$description."', NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Job Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Job Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Job Name";
	        echo json_encode($response);
		}

		elseif($description =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Job Description";
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
elseif($_POST['method'] =='edit-job')
{
	$id=$_POST['jobid'];
    $name        = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_job` WHERE `published` = '1' AND `name` = '".$name."' AND `description` = '".$description."' ";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($name!='' || $description!='')
    	{
    		$qry = "UPDATE `ss_job` SET `name` = '".$name."',
	        	`description` = '".$description."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Job Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Job Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	else
    	{
    		$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Job Name";
	        echo json_encode($response);
    	}
    }
}
elseif($_POST['method'] =='delete-job')
{
	$cateid=$_POST['cateid'];
 	if($cateid !='')
 	{
 		$qry = mysqli_query($conn, "UPDATE `ss_job` SET `published` = 2 WHERE  `id` = '".$cateid."' ");
 		if($qry)
		{
			$response['status']=true;
			$response['message']="<strong>Well done ! </strong>Your Category Job Successfully.";
	    	echo json_encode($response);
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Category Can't Job.";
        	echo json_encode($response);
		}	
 	}
 	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Category Can't Job.";
        echo json_encode($response);
	}	
}
?>