<?php
include 'config.php';
$img 	  = (int)$_POST['image'];
$updateid = (int)$_POST['updateid'];
switch ($img) {
	case '1':
	$oimg = 'image1';
	break;
	case '2':
	$oimg = 'image2';
	break;
	case '3':
	$oimg = 'image3';
	break;
	case '4':
	$oimg = 'image4';
	break;
	default:
	$oimg = 'image5';
	break;
}
$qry = "SELECT `".$oimg."` AS `image` FROM `ss_items` WHERE `id` = '".$updateid."'";
$sc = mysqli_query($conn, $qry);
if(mysqli_num_rows($sc)==1)
{
	$dd=mysqli_fetch_object($sc);
	if($dd->image)
	{
		$dir = IPATH . '/images/products/';
		unlink($dir.$dd->image);
		mysqli_query($conn,"UPDATE `ss_items` SET `".$oimg."` = NULL");
	}
}