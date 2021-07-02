<?php

include '../inc/config.php';
if($_POST['method'] !='delete-pdt')

{

$error = FALSE;

	$errors = array();

    $required = array('title','description');

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

			$title=mysqli_real_escape_string($conn,$_POST['title']);
			$description=mysqli_real_escape_string($conn,$_POST['description']);
			$ins = "INSERT INTO `ss_slider`(`title`,`description`,`createdate`) VALUES ('".$title."','".$description."',NOW())";
			// var_dump($ins); die();

	        if(mysqli_query($conn, $ins))

	        {

	        	$lastpdtid=mysqli_insert_id($conn);

	        	for($i=1; $i<=1; $i++)

				{
					//echo $lastpdtid;die;
					$exlastnewsid =$lastpdtid ;	

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

								$exnimfname =  $i.time().'.'.$exnimextension;

								$targetFolder = "../images/banner/";

								post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);

								$exnimfllink	= $exnimfname;

								mysqli_query($conn,'update ss_slider set '.$exfieldname.' = "'.$exnimfllink.'" where id="'.$exlastnewsid.'"');

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

	        	$response['message'] = "Successfully Banner Created";

	        }

	        else

	        {

	        	$response['status'] = FALSE;

	        	$response['message'] = "Getting error try again";

	        }

	    }

	    if($_POST['method']=='edit')

		{

			$id=$_POST['id'];

			$title=mysqli_real_escape_string($conn,$_POST['title']);
			$description=mysqli_real_escape_string($conn,$_POST['description']);
			$status=$_POST['pstatus'];

			$ins ="UPDATE `ss_slider` SET `title`='".$title."',`description`='".$description."',`status`='".$status."',`updatedate`= NOW() WHERE id ='".$id."'";

					

	        if(mysqli_query($conn, $ins))

	        {

	        	for($i=1; $i<=1; $i++)

				{

					$exlastnewsid =$id;	

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

								$exnimfname =  create_slug($_POST['name']).'-'.$i.time().'.'.$exnimextension;

								$targetFolder = "../image/banner/";

								$ims = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_slider` WHERE `id`  = '".$exlastnewsid."'"));



								if($ims->$exfieldname)

								{

									unlink($targetFolder.$ims->$exfieldname);

								}

								post_img($exnimfname,$_FILES[$exfieldname]['tmp_name'],$targetFolder);

								$exnimfllink	= $exnimfname;

								mysqli_query($conn,'update ss_slider set '.$exfieldname.' = "'.$exnimfllink.'" where id="'.$exlastnewsid.'"');

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

	if($_POST['method'] =='delete-banner')

		{

		 $id=$_POST['id'];

		 if($id !='')

		 {

		 	$qry = mysqli_query($conn, "UPDATE `ss_slider` SET `published` = 2 WHERE id = $id");

		 	if($qry)

			{

				$response['status']=true;

				$response['message']="<strong>Well done ! </strong>Your Banner Deleted Successfully.";

			}else

			{

				$response['status']=false;

				$response['message']="<strong>Oops ! </strong>Your Banner Can't Deleted.";

			}	

		 }

		}

    echo json_encode($response);
    ?>