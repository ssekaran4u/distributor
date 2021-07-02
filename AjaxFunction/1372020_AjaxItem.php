<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-item')
{
	$hsn         = mysqli_real_escape_string($conn, trim($_POST['hsn']));
	$title       = mysqli_real_escape_string($conn, trim($_POST['title']));
	$pcode       = mysqli_real_escape_string($conn, trim($_POST['pcode']));
	$c_id        = mysqli_real_escape_string($conn, trim($_POST['c_id']));
	$b_id        = mysqli_real_escape_string($conn, trim($_POST['b_id']));
	$allowance   = mysqli_real_escape_string($conn, trim($_POST['allowance']));
	$sta         = mysqli_real_escape_string($conn, trim($_POST['sta']));
	$description = mysqli_real_escape_string($conn, trim($_POST['description']));
	$gst         = mysqli_real_escape_string($conn, trim($_POST['gst']));
	// $stock       = mysqli_real_escape_string($conn, trim($_POST['stock']));
	$oprice      = mysqli_real_escape_string($conn, trim($_POST['oprice']));
	// $price       = mysqli_real_escape_string($conn, trim($_POST['price']));
	$exits       = "SELECT * FROM `ss_items` WHERE `brand` = '".$b_id."' AND `description` = '".$description."' AND `published` = '1'";
	$data  = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($hsn!='' && $title!='' && $c_id!='' && $b_id!='' && $description!='' && $oprice!='')
		{
			$qry = "INSERT INTO `ss_items`(`pcode`,`title`, `hsn`, `brand`, `cid`, `description`, `gst`, `oprice`, `allowance`, `sta`,`createdate`) VALUES ('".$pcode."','".$title."', '".$hsn."', '".$b_id."', '".$c_id."', '".$description."', '".$gst."', '".$oprice."', '".$allowance."', '".$sta."',NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				$pdtid = mysqli_insert_id($conn);
				
				// $stk = mysqli_query($conn, "INSERT INTO `ss_item_stk`(`product_id`, `qty`, `date`, `create_date`) VALUES ('".$pdtid."','".$stock."',NOW(),NOW())");

				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Product Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Product Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($hsn =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your HSN Code";
	        echo json_encode($response);
		}
		elseif($title =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Product Name";
	        echo json_encode($response);
		}
		elseif($c_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Category Name";
	        echo json_encode($response);
		}
		elseif($b_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Brand Name";
	        echo json_encode($response);
		}
		elseif($allowance =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Allowance";
	        echo json_encode($response);
		}
		elseif($sta =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your STA";
	        echo json_encode($response);
		}
		elseif($description =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Description";
	        echo json_encode($response);
		}
		elseif($gst =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your GST";
	        echo json_encode($response);
		}
		// elseif($stock =='')
		// {
		// 	$response['status']=false;
		// 	$response['message']="<strong>Oops ! </strong>Fill Your Stock";
	 //        echo json_encode($response);
		// }
		elseif($oprice =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Dealer Price";
	        echo json_encode($response);
		}
		// elseif($price =='')
		// {
		// 	$response['status']=false;
		// 	$response['message']="<strong>Oops ! </strong>Fill Your State";
	 //        echo json_encode($response);
		// }
			
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Item Already Exist.";
        echo json_encode($response);
	}
}
elseif($_POST['method'] =='edit-item')
{
	$id=$_POST['id'];
    $hsn         = mysqli_real_escape_string($conn, trim($_POST['hsn']));
    $pcode       = mysqli_real_escape_string($conn, trim($_POST['pcode']));
	$title       = mysqli_real_escape_string($conn, trim($_POST['title']));
	$c_id        = mysqli_real_escape_string($conn, trim($_POST['c_id']));
	$b_id        = mysqli_real_escape_string($conn, trim($_POST['b_id']));
	$allowance   = mysqli_real_escape_string($conn, trim($_POST['allowance']));
	$sta         = mysqli_real_escape_string($conn, trim($_POST['sta']));
	$description = mysqli_real_escape_string($conn, trim($_POST['description']));
	$gst         = mysqli_real_escape_string($conn, trim($_POST['gst']));	
	$stock       = mysqli_real_escape_string($conn, trim($_POST['stock']));
	$oprice      = mysqli_real_escape_string($conn, trim($_POST['oprice']));
	// $price       = mysqli_real_escape_string($conn, trim($_POST['price']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_items` WHERE `title` = '".$title."' AND `published` = '1'";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($hsn!='' && $title!='' && $c_id!='' && $b_id!='' && $description && $oprice!='')
    	{

    		$qry = "UPDATE `ss_items` SET 
    			`hsn` = '".$hsn."',
    			`pcode` = '".$pcode."',
    			`title` = '".$title."',
    			`cid` = '".$c_id."',
    			`brand` = '".$b_id."',
    			`description` = '".$description."',
    			`gst` = '".$gst."',
    			`oprice` = '".$oprice."',
    			`extra` = '".$stock."',
    			`allowance` = '".$allowance."',
    			`sta` = '".$sta."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Product Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Product Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	elseif($hsn =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your HSN Code";
	        echo json_encode($response);
		}
		elseif($title =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Product Name";
	        echo json_encode($response);
		}
		elseif($c_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Category Name";
	        echo json_encode($response);
		}
		elseif($b_id =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Brand Name";
	        echo json_encode($response);
		}
		elseif($allowance =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Allowance";
	        echo json_encode($response);
		}
		elseif($sta =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your STA";
	        echo json_encode($response);
		}
		elseif($description =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Description";
	        echo json_encode($response);
		}
		elseif($gst =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your GST";
	        echo json_encode($response);
		}
		elseif($stock =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Stock";
	        echo json_encode($response);
		}
		elseif($oprice =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Dealer Price";
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