<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-area')
{
	$name         = mysqli_real_escape_string($conn, trim($_POST['name']));
	$postcode     = mysqli_real_escape_string($conn, trim($_POST['postcode']));
	$delivery_amt = mysqli_real_escape_string($conn, trim($_POST['delivery_amt']));
	//$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_area` WHERE `published` = '1' AND `name` = '".$name."'";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($name!='' && $postcode!='' && $delivery_amt!='')
		{
			$qry = "INSERT INTO `ss_area`(`name`, `postcode`, `delivery_amt`, `createdate`) VALUES ('".$name."', '".$postcode."', '".$delivery_amt."', NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Area Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Area Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($name =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Area Name";
	        echo json_encode($response);
		}

		elseif($postcode =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Area Post Code";
	        echo json_encode($response);
		}

		elseif($delivery_amt =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Area Delivery Amount";
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
elseif($_POST['method'] =='edit-area')
{
	$id=$_POST['areaid'];
    $name         = mysqli_real_escape_string($conn, trim($_POST['name']));
	$postcode     = mysqli_real_escape_string($conn, trim($_POST['postcode']));
	$delivery_amt = mysqli_real_escape_string($conn, trim($_POST['delivery_amt']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_area` WHERE `name` = '".$name."' AND `published` = '1'";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($name!='' || $postcode!='' || $delivery_amt!='')
    	{
    		$qry = "UPDATE `ss_area` SET `name` = '".$name."',
	        	`postcode` = '".$postcode."',
	        	`delivery_amt` = '".$delivery_amt."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Category Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Category Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	else
    	{
    		$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Category Name";
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