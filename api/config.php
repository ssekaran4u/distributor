<?php
	// overwrite system time
    date_default_timezone_set('Asia/Kolkata');
    
    // Connect Database
    $conn = mysqli_connect("db5000256318.hosting-data.io","dbu326327","Multiple_@09.qwe","dbs250139");
    
    if (mysqli_connect_errno()):
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    endif;

    function leadingZeros($num,$numDigits) {
	   return sprintf("%0".$numDigits."d",$num);
	}
?>