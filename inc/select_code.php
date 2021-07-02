<?php
include 'config.php';

	$pid = $_POST["pid"];
	
		if (isset($pid)) {		
		
			$resA = mysqli_query($conn,"SELECT * FROM ss_item_stk WHERE `status` = '1' AND `delar_status` = '1' AND `service_status` = '1' AND product_id = ".$pid." AND published = '1'");	
			
				$string = "";	
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {
					$string .= $A["id"] . "~" .$A["code"] . "#"; 
				}

			}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>