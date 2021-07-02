<?php
include 'config.php';

	$customer_id = $_POST["customer_id"];
	
		if (isset($customer_id)) {		
			
			$resA = mysqli_query($conn,"SELECT * FROM ss_sales WHERE published = 1 AND status = 1 AND customer_id = ".$customer_id." ORDER BY id DESC");	
			
				$string = "";	
				
				$string .= "" . "~" . "--Select Sales No--" . "#";
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {
					$string .= $A["so_no"] . "~" .$A["so_no"] . "#"; 
				}

				}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>