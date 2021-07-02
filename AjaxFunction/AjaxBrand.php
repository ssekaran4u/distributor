<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-brand')
{
	$brand = mysqli_real_escape_string($conn, trim($_POST['brand']));
	$code = mysqli_real_escape_string($conn, trim($_POST['code']));

	$exits = "SELECT * FROM `ss_brands` WHERE `published` = '1' AND `brand` = '".$brand."' AND `code` = '".$code."'";

	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($brand!='' && $code!='' && $_FILES['image']['name']!='')
		{
			$qry = "INSERT INTO `ss_brands`(`brand`, `code`, `createdate`) VALUES ('".$brand."', '".$code."', NOW())"; 

			if(mysqli_query($conn, $qry))
			{
				$exfieldname = 'image';
				$lastpdtid=mysqli_insert_id($conn);
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
							$exnimfname =  create_slug($_POST['brand']).'-'.$branch[0].'.'.$exnimextension;
							$targetFolder =  "../images/brand/";
							post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);
							$exnimfllink	= $exnimfname;
							$up_im = 'UPDATE `ss_brands` SET  '.$exfieldname.' = "'.$exnimfllink.'" WHERE  `id` = "'.$lastpdtid.'"';
							mysqli_query($conn, $up_im);
						}
					} 
					else 
					{
						$response['status'] = FALSE;
        				$response['message'] = "Invalid file";
					}		
				}

				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Brand Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Brand Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($brand =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Brand Name";
	        echo json_encode($response);
		}
		elseif(empty($_FILES['image']['name']))
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Choose Brand Image";
	        echo json_encode($response);	
		}
			
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Brand Already Exist.";
        echo json_encode($response);
	}
}
elseif($_POST['method'] =='edit-brand')
{
	$id=$_POST['cateid'];
    $brand = mysqli_real_escape_string($conn, trim($_POST['brand']));
    $code  = mysqli_real_escape_string($conn, trim($_POST['code']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_brands` WHERE `published` = '1' AND `brand` = '".$brand."' AND `code` = '".$code."'";

    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($brand!='')
    	{
    		$exfieldname = 'image';
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
						$gd = mysqli_query($conn,"SELECT `image` FROM `ss_brands` WHERE `id` = '".$id."' LIMIT 1");
						$dimg = mysqli_fetch_object($gd);
						$exnimfname =  create_slug($_POST['brand']).'.'.$exnimextension;

						$targetFolder =  "../images/brand/";
						if($dimg->image)
						{
							unlink($targetFolder.$dimg->image);
						}
						post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);
						$exnimfllink = $exnimfname;
						mysqli_query($conn,'UPDATE `ss_brands` SET  '.$exfieldname.' = "'.$exnimfllink.'" WHERE  `id` = "'.$id.'"');
					}
				} 
				else 
				{
					$response['status'] = FALSE;
    				$response['message'] = "Invalid file";
				}		
			}

    		$qry = "UPDATE `ss_brands` SET  `brand` = '".$brand."', `code` = '".$code."', `status`  = '".$_POST['pstatus']."', `updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Brand Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Brand Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	else
    	{
    		$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Brand Name";
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