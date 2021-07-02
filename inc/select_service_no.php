<?php
	session_start();
	include 'config.php';

	function leadingZeros($num,$numDigits) {
	   return sprintf("%0".$numDigits."d",$num);
	}

	$service_qry = mysqli_query($conn, "SELECT `id`, `service_no` FROM `ss_service_stk` WHERE `published` = '1' AND `status` = '1' AND `acad_id` = '".$_SESSION['acad_year']."' ORDER BY `id` DESC LIMIT 1");

	$service_cou = mysqli_num_rows($service_qry);
	$service_res = mysqli_fetch_object($service_qry);

	if($service_cou > 0)
	{
		$code = explode('YARA/SER/', $service_res->service_no);
		$service_re = $code[1] + 1;	
	}
	else
	{
		$service_re = 1;
	}

	$so_no   = 'YARA/SER/'.leadingZeros($service_re, 5);

	$string = "";

	$string .= $so_no; 	

	echo $string;

	mysqli_close($conn);

?>