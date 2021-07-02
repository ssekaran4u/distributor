<?php
include 'config.php';

	$state_id = $_POST["state_id"];
	
		if (isset($state_id)) {		
			
			$resA = mysqli_query($conn,"SELECT * FROM ss_city WHERE status = 1 AND state_id = ".$state_id." ORDER BY city_name");	
			
				$string = "";	
				
				$string .= "" . "~" . "--Select City--" . "#";
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {
					$string .= $A["id"] . "~" .$A["city_name"] . "#"; 
				}

				}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>