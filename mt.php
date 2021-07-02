<?php
date_default_timezone_set('Asia/Kolkata');
$conn = mysqli_connect('127.0.0.1:3306','2l50_write_user','7N)Hu%mfN22T','2k50_write_school1');
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else
{
    echo "success";
}
?>

