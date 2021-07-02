<?php
include 'config.php';
	
	// $brand_id = $_POST["brand_id"];
	$cid = $_POST["c_id"];
		
		if(isset($cid)) {		
			
			$resA = mysqli_query($conn,"SELECT * FROM ss_items WHERE status = 1 AND cid = ".$cid." AND published = '1' ORDER BY id");	
			
				$string = "";	
				
				$string .= "" . "~" . "Select Product Name" . "#";
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {
					// $B = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `ss_brands` WHERE `id` = '".$A['brand']."' AND `published` = '1' AND `status` = '1'"));

					$string .= $A["id"] . '~'.$A["title"].' - '.$A['description']. "#"; 
				}

			}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>