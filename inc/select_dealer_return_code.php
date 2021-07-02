<?php
include 'config.php';
	
	$customer_id = $_POST["customer_id"];
	$so_no       = $_POST["so_no"];
	$pid         = $_POST["pid"];
	
		if (isset($pid)) {		
			$resA = mysqli_query($conn,"SELECT * FROM ss_item_stk WHERE `product_id` = ".$pid." AND `delar_sales` = '".$so_no."' AND `delar_id` = '".$customer_id."' AND `delar_status` = 0 AND `published` = '1'");	
			
				$string = "";	
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {
					$string .= $A["code"] . "~" .$A["code"] . "#"; 
				}

			}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>