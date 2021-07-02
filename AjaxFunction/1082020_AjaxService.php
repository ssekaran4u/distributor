<?php
session_start();
include '../inc/config.php';
$branch = isAdminDetails($conn);
if($_POST['method'] =='add-service')
{
	// $importtype=$_POST['importtype'];
	$error = FALSE;

	$errors = array();
    $required = array('service_no', 'b_id', 'c_id', 'p_id', 'service_code', 'date');
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
    	$service_no   = mysqli_real_escape_string($conn, trim($_POST['service_no']));
    	$b_id         = mysqli_real_escape_string($conn, trim($_POST['b_id']));
    	$c_id         = mysqli_real_escape_string($conn, trim($_POST['c_id']));
    	$p_id         = mysqli_real_escape_string($conn, trim($_POST['p_id']));
		$service_code = mysqli_real_escape_string($conn, trim($_POST['service_code']));
		$date         = mysqli_real_escape_string($conn, trim($_POST['date']));

		// $select = mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE code = '".$code[$i]."'");
		// if(mysqli_num_rows($select) == 0)
		// {
			$qry = mysqli_query($conn, "INSERT INTO `ss_service_stk`(`service_no`, `b_id`, `c_id`, `p_id`, `service_code`, `date`, `acad_id`, `create_date`) VALUES ('".$service_no."', '".$b_id."', '".$c_id."', '".$p_id."', '".$service_code."', '".date('Y-m-d', strtotime($date))."', '".$_SESSION['acad_year']."', '".date('Y-m-d H:i:s')."')");
		// }
    	// echo $k;
    	// exit;
    	if($qry)
		{	
			$upte = mysqli_query($conn, "UPDATE `ss_item_stk` SET `service_no` = '".$service_no."', `service_date` = '".date('Y-m-d', strtotime($date))."', `service_status` = '0' WHERE `id` = '".$service_code."'");

			$sele = mysqli_fetch_object(mysqli_query($conn, "SELECT `extra` FROM `ss_items` WHERE `id` = '".$p_id."'"));

			$result = $sele->extra - 1;

			$upt = mysqli_query($conn, "UPDATE `ss_items` SET  `extra` = '".$result."' WHERE `id` = '".$p_id."'");


			$response['status']=true;
			$response['message']="<strong>Well done ! </strong>Your Service Added Successfully.";
		    echo json_encode($response);
		}
		else
		{
			$response['status']=false;
			$response['message']="<strong>Oops ! </strong>Your Service Can't Added.";
	        echo json_encode($response);
		}
    }	
}
?>