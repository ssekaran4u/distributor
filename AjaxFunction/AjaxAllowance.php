<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-allowance')
{
	$description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $type         = mysqli_real_escape_string($conn, trim($_POST['type']));
    $value        = mysqli_real_escape_string($conn, trim($_POST['value']));
	//$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_allowance` WHERE `description` = '".$description."' AND `published` = '1'";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($description!='')
		{
			$qry = "INSERT INTO `ss_allowance`(`description`, `type`, `value`,`createdate`) VALUES ('".$description."' ,'".$type."', '".$value."', NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Allowance Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Allowance Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($description =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your description";
	        echo json_encode($response);
		}
		elseif($value =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Allowance Value";
	        echo json_encode($response);
		}
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Allowance Already Exist.";
        echo json_encode($response);
	}
}
elseif($_POST['method'] =='edit-allowance')
{
	$id = $_POST['allowid'];
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $type        = mysqli_real_escape_string($conn, trim($_POST['type']));
    $value       = mysqli_real_escape_string($conn, trim($_POST['value']));
    $exits = "SELECT * FROM `ss_allowance` WHERE `description` = '".$description."' AND `published` = '1'";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($description!='' && $value!='')
    	{
    		$qry = "UPDATE `ss_allowance` SET `description` = '".$description."',
    			`type` = '".$type."',
    			`value` = '".$value."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Allowance Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Allowance Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	elseif($description =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Description";
	        echo json_encode($response);
		}
    	elseif($value =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Allowance Value";
	        echo json_encode($response);
		}
    }
}

?>