<?php
include 'config.php';

	$so_no = $_POST["so_no"];
	$customer_id = $_POST["customer_id"];

	if (isset($so_no)) {


		$resA = mysqli_query($conn,"SELECT SUM(amount) AS amount FROM ss_payment WHERE status = 1 AND so_no = ".$so_no." AND customer_id = '".$customer_id."' AND published = '1' ORDER BY id");
			
			$string = "";	
			
			mysqli_num_rows($resA);
			
			if (mysqli_num_rows($resA) > 0 ) {
			
			$A = mysqli_fetch_array($resA);

			$resB = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(total_cost) AS total_cost FROM ss_sales_details WHERE status = 1 AND so_id = ".$so_no." AND customer_id = '".$customer_id."' AND published = '1' ORDER BY auto_id"));

			// print_r($resB);
			$balance = $resB['total_cost'] - $A["amount"];

			if($A["amount"] == '')
			{
				$string .= '0'.'~'.$balance.'#';
			}
			else
			{
				$string .= $A["amount"].'~'.$balance.'#';
			}

		}
		
		echo $string;

		mysqli_free_result($resA);
	}

	mysqli_close($conn);
?>