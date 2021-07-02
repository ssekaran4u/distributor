<?php
	session_start();
	include 'config.php';

	function leadingZeros($num,$numDigits) {
	   return sprintf("%0".$numDigits."d",$num);
	}
		$sale_no = mysqli_fetch_object(mysqli_query($conn, "SELECT id, so_no FROM `ss_sales` WHERE `published` = '1' AND `status` = '1' AND `acad_id` = '".$_SESSION['acad_year']."' ORDER BY id DESC limit 1"));
		if($sale_no =='')
		{
			if($_SESSION['acad_year'] == 2)
			{
				$sale_re = 230;
			}
			else
			{
				$sale_re = 1;
			}
		}
		else
		{
			$code = explode('YARA', $sale_no->so_no);
			$sale_re = $code[1] + 1;	
			// $sale_re = $sale_no->id + 1;
		}
		$so_no   = 'YARA'.leadingZeros($sale_re, 5);

		$string = "";

		$string .= $so_no; 	

		echo $string;

	mysqli_close($conn);

?>