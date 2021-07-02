<?php
session_start();
include 'config.php';
$count = count($_POST["position"]);
$page_id = $_POST["position"];
for($i=0; $i<$count; $i++)
{
    mysqli_query($conn, "UPDATE `ss_category`  SET `sort` = '".$i."' WHERE `id` = '".$_POST["position"][$i]."'");
}
?>