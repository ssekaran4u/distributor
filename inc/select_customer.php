<?php
include 'config.php';

	$customer_id = $_POST["customer_id"];
	
		if (isset($customer_id)) {		
			
			$resA = mysqli_query($conn,"SELECT * FROM ss_customers WHERE published = 1 AND status = 1 AND id = ".$customer_id." ORDER BY id ASC");	
			
				$string = "";	
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {

					$d_allowance = isset($A["d_allowance"])?$A["d_allowance"]:'0';

					$string = $A["mobile"] . "~" .$A["address"] . "~" .$d_allowance ."~". $A['credit_lmt']. "~" .$A["tcs_type"]; 
				}

				}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>