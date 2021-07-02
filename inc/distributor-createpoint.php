<?php
session_start();
include '../inc/config.php';
if(isset($_REQUEST['customer_id']))
{
	$customer_id = mysqli_real_escape_string($conn, trim($_POST['customer_id']));

	$resA = mysqli_query($conn,"SELECT * FROM ss_distributors WHERE status = '1' AND deleted = '1' AND id = ".$customer_id." ORDER BY id");

	$string = "";

	if (mysqli_num_rows($resA) > 0 ) {
		while ($A = mysqli_fetch_array($resA)) {
			$string .= $A['avl_lmt'] ."#"; 
		}
	}
	echo $string;

	mysqli_free_result($resA);
}
mysqli_close($conn);
?>