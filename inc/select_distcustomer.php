<?php
include 'config.php';

	$dist_name = $_POST["dist_name"];
	
		if (isset($dist_name)) {		
			
			$resA = mysqli_query($conn,"SELECT * FROM ss_customers WHERE status = 1 AND userid = ".$dist_name." ORDER BY name");	
			
				$string = "";	
				
				$string .= "" . "~" . "--Select Customer--" . "#";
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {
					$string .= $A["id"] . "~" .$A["cname"].' - ' .$A['name']. "#"; 
				}

				}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>