<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-item')
{
	$importtype=$_POST['importtype'];
	$error = FALSE;

	$errors = array();
    $required = array('invoice_no', 'importtype', 'date');
    if($importtype == 2)
    {
    	array_push($required, 'c_id', 'pid');
    }
    else if($importtype == 3)
    {
    	array_push($required, 'c_id', 'pid', 'qty', 'code');
    }
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }
     //print_r($required);
    // exit;
    
	if($error)
	{
		$response['status']=false;
		$response['message']="<strong>Oops ! </strong>Please Fill All Fields.";
        echo json_encode($response);
        return;
	}
    else
    {
    	//exit;
    	$invoice_no = mysqli_real_escape_string($conn, trim($_POST['invoice_no']));
		$date       = mysqli_real_escape_string($conn, trim($_POST['date']));

		if($importtype == 1)
		{
			$filename = $_FILES["csvfile"]["name"];
			$val=explode(".",$filename);
			$k = 1;
			if($val[1] =="csv")
			{
				$fileData = file_get_contents($_FILES['csvfile']['tmp_name']);
				if($fileData)
				{
					
					$arrDetails = array();
				    $datas = str_getcsv($fileData, "\n"); 
				    unset($datas[0]);
				    $countqty=!empty($datas)?count($datas):0;
					foreach($datas as $rawRow)
					{
						$row = str_getcsv($rawRow, ",");
						$code       = $row[0];
						$model_no   = $row[1];

						$pro_qry = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `description` = '".$model_no."' AND `published` = '1'"));

						$qry = mysqli_query($conn, "INSERT INTO `ss_item_stk`(`invoice_no`, `cid`, `product_id`, `qty`, `code`, `date`, `create_date`) VALUES ('".$invoice_no."', '".$pro_qry->cid."', '".$pro_qry->id."', '1', '".$code."', '".date('Y-m-d', strtotime($date))."', NOW())");
						$k++;
					}
				}
			}
			// else
			// {
			// 	$response['status']=false;
			// 	$response['message']="<strong>Oops ! </strong>Invalide File Type.";
		 //        echo json_encode($response);
			// }
		}
    	else if($importtype == 2)
    	{
    		$c_id       = mysqli_real_escape_string($conn, trim($_POST['c_id']));
			$pid        = mysqli_real_escape_string($conn, trim($_POST['pid']));

    		$filename = $_FILES["csvfile"]["name"];
			$val=explode(".",$filename);
			$k = 0;
			if($val[1] =="csv" )
			{
				$fileData = file_get_contents($_FILES['csvfile']['tmp_name']);
				if($fileData)
				{
					$arrDetails = array();
				    $datas = str_getcsv($fileData, "\n"); 
				    unset($datas[0]);
					//print_r($datas);die;
					$countqty=!empty($datas)?count($datas):0;
					foreach($datas as $rawRow)
					 {
						$row = str_getcsv($rawRow, ",");
						// if($k!=0)
						// {
							// print_r($row);
						    $code = $row[0];
						    // echo $code;
						    $select = mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE code = '".$code."'");
							if(mysqli_num_rows($select) == 0)
							{	
								$qry = mysqli_query($conn, "INSERT INTO `ss_item_stk`(`invoice_no` ,`cid`, `product_id`, `qty`, `code`, `date`, `create_date`) VALUES ('".$invoice_no."' ,'".$c_id."','".$pid."','".$countqty."','".$code."','".date('y-m-d', strtotime($date))."',NOW())");
							}
						// }
					$k++;
					}
					// echo $k;
					// die;
				}
			}
			// else
			// {
			// 	$response['status']=false;
			// 	$response['message']="<strong>Oops ! </strong>Invalide File Type.";
		 //        echo json_encode($response);
			// }
    	}
    	else
    	{
    		$c_id       = mysqli_real_escape_string($conn, trim($_POST['c_id']));
			$pid        = mysqli_real_escape_string($conn, trim($_POST['pid']));
			$qty        = mysqli_real_escape_string($conn, trim($_POST['qty']));

    		$code  = $_POST['code'];
	    	$items = array(); 
			$k = count($code);
			$m = 1;
			for($i = 0 ; $i < $k ; $i++)
			{
				$select = mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE code = '".$code[$i]."'");
				if(mysqli_num_rows($select) == 0)
				{
					$qry = mysqli_query($conn, "INSERT INTO `ss_item_stk`(`invoice_no` ,`cid`, `product_id`, `qty`, `code`, `date`, `create_date`) VALUES ('".$invoice_no."' ,'".$c_id."','".$pid."','".$qty."','".$code[$i]."','".date('y-m-d', strtotime($date))."',NOW())");
				}
				$m++;
			}
    	}
    	// echo $k;
    	// exit;
    	if($k)
		{	

			$qry = mysqli_query($conn, "SELECT COUNT(`id`) AS `id`, `product_id` FROM `ss_item_stk` WHERE `published` = '1' AND `invoice_no` = '".$invoice_no."' GROUP BY `product_id`");

			while($row = mysqli_fetch_object($qry))
			{
				$sele = mysqli_fetch_object(mysqli_query($conn, "SELECT `extra` FROM `ss_items` WHERE `id` = '".$row->product_id."'"));

				$result = $row->id + $sele->extra;

				$upt = mysqli_query($conn, "UPDATE `ss_items` SET  `extra` = '".$result."' WHERE `id` = '".$row->product_id."'");
			}


			$response['status']=true;
			$response['message']="<strong>Well done ! </strong>Your Inventory Added Successfully.";
		    echo json_encode($response);
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Inventory Can't Added.";
	        echo json_encode($response);
		}
    }	
}
?>