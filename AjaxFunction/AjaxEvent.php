<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-event')
{
	$title        = mysqli_real_escape_string($conn, trim($_POST['title']));
	$description = mysqli_real_escape_string($conn, trim($_POST['description']));

	$exits = "SELECT * FROM `ss_event` WHERE `published` = '1' AND `title` = '".$title."' ";
	$data = mysqli_query($conn, $exits);
	$rsrow = mysqli_num_rows($data);
	if($rsrow==0)
	{
		if($title!='' && $description != '' && $_FILES['image']['name']!='')
		{
			$qry = "INSERT INTO `ss_event`(`title`, `description`, `createdate`) VALUES ('".$title."', '".$description."', NOW())"; 
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
							$exnimfname =  create_slug($_POST['title']).'-'.$branch[0].'.'.$exnimextension;
							$targetFolder =  "../../images/event";
							post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);
							$exnimfllink	= $exnimfname;
							$up_im = 'UPDATE `ss_event` SET  '.$exfieldname.' = "'.$exnimfllink.'" WHERE  `id` = "'.$lastpdtid.'"';
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
				$response['message']="<strong>Well done ! </strong>Your Event Added Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Event Can't Added.";
		        echo json_encode($response);
			}	
		}
		elseif($title =='')
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Event Name";
	        echo json_encode($response);
		}
		elseif(empty($_FILES['image']['name']))
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Choose Event Image";
	        echo json_encode($response);	
		}
			
	}
	else
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Your Event Already Exist.";
        echo json_encode($response);
	}
}
elseif($_POST['method'] =='edit-event')
{
	$id=$_POST['eventid'];
    $title        = mysqli_real_escape_string($conn, trim($_POST['title']));
	$description = mysqli_real_escape_string($conn, trim($_POST['description']));
    // $subcat = isset($_POST['subcat']) ? $_POST['subcat'] : '0' ;
    $exits = "SELECT * FROM `ss_event` WHERE `title` = '".$title."' AND `published` = '1'";
    $data = mysqli_query($conn, $exits);
    if($data)
    {
    	if($title !='')
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
						$gd = mysqli_query($conn,"SELECT `image` FROM `ss_event` WHERE `id` = '".$id."' LIMIT 1");
						$dimg = mysqli_fetch_object($gd);
						$exnimfname =  create_slug($_POST['title']).'.'.$exnimextension;

						$targetFolder =  "../../images/event";
						if($dimg->image)
						{
							unlink($targetFolder.$dimg->image);
						}
						post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);
						$exnimfllink = $exnimfname;
						mysqli_query($conn,'UPDATE `ss_event` SET  '.$exfieldname.' = "'.$exnimfllink.'" WHERE  `id` = "'.$id.'"');
					}
				} 
				else 
				{
					$response['status'] = FALSE;
    				$response['message'] = "Invalid file";
				}		
			}

    		$qry = "UPDATE `ss_event` SET `title` = '".$title."',
	        	`description` = '".$description."',
	        	`status`  = '".$_POST['pstatus']."',
	        	`updatedate`  = NOW()  WHERE id = '".$id."'";
	        	// echo $qry; die();
        	$insert = mysqli_query($conn, $qry);
	        if($insert)
			{
				$response['status']=true;
				$response['message']="<strong>Well done ! </strong>Your Event Updated Successfully.";
			    echo json_encode($response);
			}
			else
			{
				$response['status']=false;
				$response['message']="<strong>Oops ! </strong>Your Event Can't Updated.";
		        echo json_encode($response);
			}
    	}
    	else
    	{
    		$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Fill Your Event Name";
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