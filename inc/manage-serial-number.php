<?php 
	session_start();
	include 'config.php';
	if($_POST['method'] =='add-form')
	{
		$serial_no = $_POST['serial_no'];

	    $qry = mysqli_query($conn, "SELECT * FROM `ss_item_stk` WHERE `published` = '1' AND `code` LIKE '%".$serial_no."%'");
	    $num = mysqli_num_rows($qry);
	    if($num > 0) 
	    {
	    	$i = 1;
	    	while($res = mysqli_fetch_object($qry))
	    	{
	    		$item  = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$res->product_id."' AND `published` = '1' "));

	    		$categ = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE `id` = '".$res->cid."' AND `published` = '1' "));

	    		?>
	    			<tr>
	    				<th><?php echo $i++; ?></th>
	    				<th><?php echo isset($res->code)?$res->code:'---'; ?></th>
	    				<th><?php echo isset($item->title)?$item->title:'---'; ?></th>
	    				<th><?php echo isset($item->description)?$item->description:'---'; ?></th>
	    				<th><?php echo isset($categ->title)?$categ->title:'---'; ?></th>
	    				<th><?php echo isset($item->oprice)?$item->oprice:'---'; ?></th>
	    				<th><?php echo isset($res->invoice_no)?$res->invoice_no:'---'; ?></th>
	    				<th><?php echo isset($res->sales_no)?$res->sales_no:'---'; ?></th>
	    			</tr>
	    		<?php
	    	}
	    }
	    else
	    {
	    	echo "1";
	    }
	}
	else
	{
		echo "1";
	}
?>