<?php
include 'config.php';
	
	// $brand_id = $_POST["brand_id"];
	$p_id = $_POST["p_id"];
		
		if(isset($p_id)) {		
			
			$resA = mysqli_query($conn,"SELECT * FROM `ss_item_stk` WHERE `status` = '1' AND `delar_status` = '1' AND `service_status` = '1' AND `product_id` = ".$p_id." AND `published` = '1' ORDER BY `id` ASC");	
			
				$string = "";	
				
				$string .= "" . "~" . "Select Serial No" . "#";
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {
					// $B = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `ss_brands` WHERE `id` = '".$A['brand']."' AND `published` = '1' AND `status` = '1'"));

					$string .= $A["id"] . '~'.$A["code"]. "#"; 
				}

			}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>