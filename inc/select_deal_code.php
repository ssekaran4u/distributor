<?php
session_start();
include 'config.php';

	$pid = $_POST["pid"];
	$dist_name = $_POST["dist_name"];
	if($dist_name)
	{
		$dist_id=$dist_name;
	}
	else
	{
		$dist_id=$_SESSION['uid'];
	}
		if (isset($pid)) {		
			
			$resA = mysqli_query($conn,"SELECT * FROM ss_item_stk WHERE dist_id = ".$dist_id." AND delar_status = 1 AND status = 0 AND product_id = ".$pid." AND published = '1'");	
			
				$string = "";	
				
				$count=mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {
					$string .= $A["id"] . "~" .$A["code"] . "#"; 
				}

			}
			
			echo $string."^".$count;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>