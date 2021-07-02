<?php
include 'config.php';

	$pid = $_POST["pid"];
	
		if (isset($pid)) {		
				
			$resA = mysqli_query($conn,"SELECT * FROM ss_items WHERE status = 1 AND id = ".$pid." AND published = '1' AND status = '1' ORDER BY id");	
			
				$string = "";	
				
				mysqli_num_rows($resA);
				
				if (mysqli_num_rows($resA) > 0 ) {
				
				while ($A = mysqli_fetch_array($resA)) {

					$description = isset($A['description'])?$A['description']:'-';
					$oprice      = isset($A['oprice'])?$A['oprice']:'0';
					$gst         = isset($A['gst'])?$A['gst']:'0';
					$allowance   = isset($A['allowance'])?$A['allowance']:'0';
					$sta         = isset($A['sta'])?$A['sta']:'0';
					$hsn         = isset($A['hsn'])?$A['hsn']:'0';
					$extra       = isset($A['extra'])?$A['extra']:'0';

					$string .= $description ."~" .$oprice . "~" . $gst . "~" . $allowance. "~" . $sta. "~" . $hsn ."~". $extra."#"; 
				}

			}
			
			echo $string;

			mysqli_free_result($resA);
			
		}	
		

mysqli_close($conn);
?>