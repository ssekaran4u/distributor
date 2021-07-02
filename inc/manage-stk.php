<?php
session_start();
include '../inc/config.php';

if(isset($_REQUEST['product_id']))
{
	$product_id       = mysqli_real_escape_string($conn, trim($_POST['product_id']));

	$qry = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE product_id = '".$product_id."' ORDER BY `ss_item_stk`.`date` DESC LIMIT 0, 20");

	$con = mysqli_num_rows($qry);

	if($con > 0)
	{
		while($res = mysqli_fetch_object($qry))
		{
			$product  = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_items WHERE id = '".$res->product_id."' AND published = '1' AND status = '1'"));

			?>
				<tr id="<?php echo $row['id']; ?>">
					<th><?php echo $product->title; ?></th>
					<th><?php echo $product->hsn; ?></th>
					<th><?php echo $res->qty; ?></th>
					<th><?php echo date('d-m-Y', strtotime($res->date)); ?></th>
				</tr>	
			<?php
		}
	}
	else
	{
		echo "1";
	}

}
?>