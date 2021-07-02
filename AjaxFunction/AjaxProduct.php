<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] !='delete-pdt')
{
	$error = FALSE;
	$errors = array();
    $required = array('name', 'brand','category','offerprice','min-qty','max-qty','description','specifications');
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
    if(count($errors)==0)
    {
    	if($_POST['method']=='insert')
		{
			$name=mysqli_real_escape_string($conn,$_POST['name']);
			$title=mysqli_real_escape_string($conn,$_POST['title']);
			$meta_description=mysqli_real_escape_string($conn,$_POST['meta_description']);
			$keywords=mysqli_real_escape_string($conn,$_POST['keywords']);
			$brand=mysqli_real_escape_string($conn,$_POST['brand']);
			$category=mysqli_real_escape_string($conn,$_POST['category']);
			$gst=$_POST['gst'];
			$mrp=100;
			$discount=100;
			$offerprice=(int)$_POST['offerprice'];
			$minqty=(int)$_POST['min-qty'];
			$maxqty=(int)$_POST['max-qty'];
			// $extra=(int)$_POST['extra'];
			// $time =(int)$_POST['time'];
			$description=mysqli_real_escape_string($conn,$_POST['description']);
			$specifications=mysqli_real_escape_string($conn,$_POST['specifications']);
	        $ins = "INSERT INTO `ss_items`(`title`, `meta_title`, `meta_desc`, `meta_key`, `brand`,`cid`, `description`, `specifications`,  `gst`, `min_qty`, `max_qty`, `price`, `oprice`,`discount`, `branch`,`createdate`) VALUES ('".$name."','".$title."','".$meta_description."','".$keywords."', '".$brand."','".$category."','".$description."','".$specifications."','".$gst."','".$minqty."','".$maxqty."','".$mrp."','".$offerprice."','".$discount."','".$branch[0]."',NOW())";
	        // echo $ins;
	        if(mysqli_query($conn, $ins))
	        {
	        	$lastpdtid=mysqli_insert_id($conn);
	        	for($i=1; $i<=5; $i++)
				{
					$exlastnewsid =$lastpdtid ;	
					$exfieldname = 'image'.$i;
					if(isset($_FILES[$exfieldname]["name"]) && $_FILES[$exfieldname]["name"] != '') 
					{
						$exnimallowedExts = array("gif", "jpeg", "jpg", "png");
						$exnimtemp = explode(".", $_FILES[$exfieldname]["name"]);
						$exnimextension = end($exnimtemp); 
						if (($_FILES[$exfieldname]["size"] < 10000000) && in_array($exnimextension, $exnimallowedExts)) 
						{
				  			if ($_FILES[$exfieldname]["error"] > 0) 
				  			{
								//echo "Return Code: " . $_FILES[$exfieldname]["error"] . "<br>";
							} 
							else 
							{
								$exnimfname =  create_slug($_POST['name']).'-'.$i.'.'.$exnimextension;
								$targetFolder =  "../../images/products";
								post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);
								$exnimfllink	= $exnimfname;
								mysqli_query($conn,'update ss_items set '.$exfieldname.' = "'.$exnimfllink.'" where id="'.$exlastnewsid.'"');
							}
						} 
						else 
						{
							$response['status'] = FALSE;
	        				$response['message'] = "Invalid file";
						}		
					}
				}
	        	$response['status'] = TRUE;
	        	$response['type']	= 1;
	        	$response['message'] = "Successfully Item Created";
	        }
	        else
	        {
	        	$response['status'] = FALSE;
	        	$response['message'] = "Getting error try again";
	        }
	    }
	    if($_POST['method']=='edit')
		{
			$pdtid=$_POST['pdtid'];
			$name=mysqli_real_escape_string($conn,$_POST['name']);
			$title=mysqli_real_escape_string($conn,$_POST['title']);
			$meta_description=mysqli_real_escape_string($conn,$_POST['meta_description']);
			$keywords=mysqli_real_escape_string($conn,$_POST['keywords']);
			$brand=mysqli_real_escape_string($conn,$_POST['brand']);
			$category=mysqli_real_escape_string($conn,$_POST['category']);
			$gst=$_POST['gst'];
			$mrp=100;
			$discount=100;
			$offerprice=(int)$_POST['offerprice'];
			$minqty=(int)$_POST['min-qty'];
			$maxqty=(int)$_POST['max-qty'];
			// $extra=(int)$_POST['extra'];
			// $time =(int)$_POST['time'];
			$description=mysqli_real_escape_string($conn,$_POST['description']);
			$specifications=mysqli_real_escape_string($conn,$_POST['specifications']);
			$ins ="UPDATE `ss_items` SET `title`='".$name."',`meta_title`='".$title."',`meta_desc`='".$meta_description."',`meta_key`='".$keywords."',`brand`='".$brand."',`cid`='".$category."',`description`='".$description."',`specifications`='".$specifications."',`gst`='".$gst."',`min_qty`='".$minqty."',`max_qty`='".$maxqty."',`price`='".$mrp."',`oprice`='".$offerprice."',`discount`='".$discount."',`branch`='".$branch[0]."',`updatedate`= NOW() WHERE id ='".$pdtid."'";
	        if(mysqli_query($conn, $ins))
	        {
	        	for($i=1; $i<=5; $i++)
				{
					$exlastnewsid = $pdtid;	
					$exfieldname = 'image'.$i;
					if(isset($_FILES[$exfieldname]["name"]) && $_FILES[$exfieldname]["name"] != '') 
					{
						$exnimallowedExts = array("gif", "jpeg", "jpg", "png");
						$exnimtemp = explode(".", $_FILES[$exfieldname]["name"]);
						$exnimextension = end($exnimtemp); 
						if (($_FILES[$exfieldname]["size"] < 10000000) && in_array($exnimextension, $exnimallowedExts)) 
						{
				  			if ($_FILES[$exfieldname]["error"] > 0) 
				  			{
								//echo "Return Code: " . $_FILES[$exfieldname]["error"] . "<br>";
							} 
							else 
							{
								$exnimfname =  create_slug($_POST['name']).'-'.$i.'.'.$exnimextension;
								$targetFolder =  "../../images/products";
								$ims = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id`  = '".$exlastnewsid."'"));
								if($ims->$exfieldname)
								{
									unlink($targetFolder.$ims->$exfieldname);
								}
								post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);
								$exnimfllink	= $exnimfname;
								mysqli_query($conn,'update ss_items set '.$exfieldname.' = "'.$exnimfllink.'" where id="'.$exlastnewsid.'"');
							}
						} 
						else 
						{
							$response['status'] = FALSE;
	        				$response['message'] = "Invalid file";
						}		
					}
				}
	        	$response['status'] = TRUE;
	        	$response['type']	= 2;
	        	$response['message'] = "Successfully Item Updated";
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
}
if($_POST['method'] =='delete-pdt')
{
	$pdtid=$_POST['pdtid'];
	if($pdtid !='')
	{
	 	$qry = mysqli_query($conn, "UPDATE `ss_items` SET `published` = 2 WHERE `branch` = '".$branch[0]."' AND  `id` = '".$pdtid."' ");
	 	if($qry)
		{
			$response['status']=true;
			$response['message']="<strong>Well done ! </strong>Your Product Deleted Successfully.";
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Product Can't Deleted.";
		}	
	}
}
echo json_encode($response);
?>