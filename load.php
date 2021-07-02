<?php
	include 'inc/config.php';

	if(isset($_POST['submit']))
	{
		if($_POST['title'] == '1')
		{
			// Update GST Value
			$qry = mysqli_query($conn, "SELECT * FROM `ss_items` WHERE 	`published` = '1'");
			$num = mysqli_num_rows($qry);
			if($num > 0)
			{
				while($row = mysqli_fetch_object($qry))
				{
					if($row->cid == 'TELEV')
					{
						$upt = mysqli_query($conn, "UPDATE `ss_items` SET `cid` = '3' WHERE `id` = '".$row->id."'");
					}
					else if($row->cid == 'AUDIO')
					{
						$upt = mysqli_query($conn, "UPDATE `ss_items` SET `cid` = '2' WHERE `id` = '".$row->id."'");
					}
					else if($row->cid == 'AIR C')
					{
						$upt = mysqli_query($conn, "UPDATE `ss_items` SET `cid` = '1' WHERE `id` = '".$row->id."'");
					}
					else if($row->cid == 'WASHI')
					{
						$upt = mysqli_query($conn, "UPDATE `ss_items` SET `cid` = '4' WHERE `id` = '".$row->id."'");
					}
					else
					{
						echo "No Category in this Number".$row->id.'<br>';
					}
				}
			}
			else
			{
				echo "No data";
			}	
		}
		if($_POST['title'] == '2')
		{
			// Update GST Value
			$qry = mysqli_query($conn, "SELECT * FROM `ss_items` WHERE 	`published` = '1'");
			$num = mysqli_num_rows($qry);
			if($num > 0)
			{
				while($row = mysqli_fetch_object($qry))
				{
					if($row->gst == '0.18')
					{
						$upt = mysqli_query($conn, "UPDATE `ss_items` SET `gst` = '18' WHERE `id` = '".$row->id."'");
					}
					else if($row->gst == '0.28')
					{
						$upt = mysqli_query($conn, "UPDATE `ss_items` SET `gst` = '28' WHERE `id` = '".$row->id."'");
					}
					else
					{
						echo "No GSTIN in this Number".$row->id.'<br>';
					}
				}
			}
			else
			{
				echo "No data";
			}	
		}
		else
		{
			echo "exist";
		}

	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Form Load</title>
</head>
<body>
	<form class="clear-fix" method="post">
		<div class="form-group">
			<input type="text" class="form-control" name="title" placeholder="Enter the Title">
		</div>
		<input type="submit" name="submit" class="btn btn-primary" value="submit">
	</form>
</body>
</html>