<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-category')
{
	$title = mysqli_real_escape_string($conn, trim($_POST['title']));
	$subcat = (int)$_POST['subcat'];
	$exits = "SELECT * FROM `ss_category` WHERE `published` = '1' AND `title` = '".$title."' AND `parentid` = '".$subcat."'";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		// if($title!='' && $_FILES['image']['name']!='')
		if($title!='')
		{
			$qry = "INSERT INTO `ss_category`(`parentid`, `title`, `createdate`) VALUES ('".$subcat."', '".$title."', NOW())"; 
			if(mysqli_query($conn, $qry))
			{
				// $exfieldname = 'image';
				// $lastpdtid=mysqli_insert_id($conn);
				// if(isset($_FILES[$exfieldname]["name"]) && $_FILES[$exfieldname]["name"] != '') 
				// {
				// 	$exnimallowedExts = array("gif", "jpeg", "jpg", "png");
				// 	$exnimtemp = explode(".", $_FILES[$exfieldname]["name"]);
				// 	$exnimextension = end($exnimtemp); 
				// 	if (($_FILES[$exfieldname]["size"] < 10000000) && in_array($exnimextension, $exnimallowedExts)) 
				// 	{
			 //  			if ($_FILES[$exfieldname]["error"] > 0) 
			 //  			{
				// 			echo "Return Code: " . $_FILES[$exfieldname]["error"] . "<br>";
				// 		} 
				// 		else 
				// 		{
				// 			$exnimfname =  create_slug($_POST['title']).'-'.$branch[0].'.'.$exnimextension;
				// 			$targetFolder =  "../../images/categories";
				// 			post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);
				// 			$exnimfllink	= $exnimfname;
				// 			$up_im = 'UPDATE `ss_category` SET  '.$exfieldname.' = "'.$exnimfllink.'" WHERE  `id` = "'.$lastpdtid.'"';
				// 			mysqli_query($conn, $up_im);
				// 		}
				// 	} 
				// 	else 
				// 	{
				// 		$response['status'] = FALSE;
    //     				$response['message'] = "Invalid file";
				// 	}		
				// }

				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Category Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Category Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($title =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Category Name";
	        echo json_encode($response);
		}
		elseif(empty($_FILES['image']['name']))
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Choose Category Image";
	        echo json_encode($response);	
		}
			
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Category Already Exist.";
        echo json_encode($response);
	}
}
elseif($_POST['method'] =='edit-category')
{
	$id=$_POST['cateid'];
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $subcat = mysqli_real_escape_string($conn, trim($_POST['subcat']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_category` WHERE `title` = '".$title."' AND `published` = '1'";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($title !='')
    	{
   //  		$exfieldname = 'image';
			// if(isset($_FILES[$exfieldname]["name"]) && $_FILES[$exfieldname]["name"] != '') 
			// {
			// 	$exnimallowedExts = array("gif", "jpeg", "jpg", "png");
			// 	$exnimtemp = explode(".", $_FILES[$exfieldname]["name"]);
			// 	$exnimextension = end($exnimtemp); 
			// 	if (($_FILES[$exfieldname]["size"] < 10000000) && in_array($exnimextension, $exnimallowedExts)) 
			// 	{
		 //  			if ($_FILES[$exfieldname]["error"] > 0) 
		 //  			{
			// 			echo "Return Code: " . $_FILES[$exfieldname]["error"] . "<br>";
			// 		} 
			// 		else 
			// 		{
			// 			$gd = mysqli_query($conn,"SELECT `image` FROM `ss_category` WHERE `id` = '".$id."' LIMIT 1");
			// 			$dimg = mysqli_fetch_object($gd);
			// 			$exnimfname =  create_slug($_POST['title']).'.'.$exnimextension;

			// 			$targetFolder =  "../../images/categories";
			// 			if($dimg->image)
			// 			{
			// 				unlink($targetFolder.$dimg->image);
			// 			}
			// 			post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);
			// 			$exnimfllink = $exnimfname;
			// 			mysqli_query($conn,'UPDATE `ss_category` SET  '.$exfieldname.' = "'.$exnimfllink.'" WHERE  `id` = "'.$id.'"');
			// 		}
			// 	} 
			// 	else 
			// 	{
			// 		$response['status'] = FALSE;
   //  				$response['message'] = "Invalid file";
			// 	}		
			// }

    		$qry = "UPDATE `ss_category` SET `title` = '".$title."',
	        	`parentid` = '".$subcat."',
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