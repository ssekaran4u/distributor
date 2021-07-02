<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-bank')
{
	$name     = mysqli_real_escape_string($conn, trim($_POST['name']));
	$ifsc     = mysqli_real_escape_string($conn, trim($_POST['ifsc']));
	$ac_num   = mysqli_real_escape_string($conn, trim($_POST['ac_num']));
	$branch   = mysqli_real_escape_string($conn, trim($_POST['branch']));
	//$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_bank` WHERE `published` = '1' AND `name` = '".$name."' AND `ifsc` = '".$ifsc."' AND ac_num = '".$ac_num."' AND branch = '".$branch."' ";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($name!='' && $ifsc!='' && $ac_num!='' && $branch)
		{
			$qry = "INSERT INTO `ss_bank`(`name`, `ifsc`, `ac_num`, `branch`, `createdate`) VALUES ('".$name."', '".$ifsc."', '".$ac_num."', '".$branch."', NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Bank Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Bank Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Bank Name";
	        echo json_encode($response);
		}

		elseif($ifsc =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your IFSC";
	        echo json_encode($response);
		}

		elseif($ac_num =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Account Number";
	        echo json_encode($response);
		}

		elseif($branch =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Branch";
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
elseif($_POST['method'] =='edit-bank')
{
	$id=$_POST['bankid'];
    $name      = mysqli_real_escape_string($conn, trim($_POST['name']));
    $ifsc      = mysqli_real_escape_string($conn, trim($_POST['ifsc']));
    $ac_num    = mysqli_real_escape_string($conn, trim($_POST['ac_num']));
    $branch    = mysqli_real_escape_string($conn, trim($_POST['branch']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_bank` WHERE `published` = '1' AND `name` = '".$name."' AND `ifsc` = '".$ifsc."' AND ac_num = '".$ac_num."' AND branch = '".$branch."' ";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($name!='' || $ifsc!='' || $ac_num!='' || $branch != '')
    	{
    		$qry = "UPDATE `ss_bank` SET `name` = '".$name."',
	        	`ifsc` = '".$ifsc."',
	        	`ac_num` = '".$ac_num."',
	        	`branch` = '".$branch."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Bank Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Bank Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	else
    	{
    		$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Bank Name";
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