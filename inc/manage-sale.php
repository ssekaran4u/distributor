<?php
session_start();
include '../inc/config.php';

if(isset($_REQUEST['so_no']))	
{
	$so_no       = mysqli_real_escape_string($conn, trim($_POST['so_no']));
	$customer_id = mysqli_real_escape_string($conn, trim($_POST['customer_id']));

	$qry = mysqli_query($conn, "SELECT * FROM `ss_sales_details` WHERE so_no = '".$so_no."' AND customer_id = '".$customer_id."' AND published = '1' AND status = '1'");

	$con = mysqli_num_rows($qry);

	if($con > 0)
	{	
		$sub_tot=0;
		$_total=0;
		while($res = mysqli_fetch_object($qry))
		{
			$product  = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_items WHERE id = '".$res->pid."' AND published = '1' AND status = '1'"));

			// $code     = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_item_stk WHERE id = '".$res->code."' AND published = '1'"));

			$customer = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_customers WHERE id = '".$customer_id."' AND published = '1' AND status = '1'"));		

			$_b_dis = $res->price * $res->allowance / 100;

			$allowance_val  = $res->qty * round($_b_dis);

			$_sta = $res->qty * round($res->sta);
			
			$_d_dis = $res->price * $res->d_allowance / 100;

			$d_allowance_val = $res->qty * round($_d_dis);

			$discount = $res->qty * round($res->discount);

			$netdis = $allowance_val + $_sta + $d_allowance_val + $discount;

			$sub_to = $res->qty * $res->price;

            $total = $sub_to - $netdis;

            $_total += $total;

			?>
				<tr>
					<th><?php echo $res->code; ?></th>
					<th><?php echo $product->hsn; ?></th>
					<th><?php echo $product->oprice.' /-'; ?></th>
					<th><?php echo $res->qty; ?></th>
					<th><?php echo isset($res->allowance)?$res->qty * $res->allowance:'0'.' %'; ?></th>
					<th><?php echo isset($res->sta)?$res->qty * $res->sta:'0'; ?></th>
					<th><?php echo isset($res->d_allowance)?$res->qty * $res->d_allowance:'0'.' %'; ?></th>
					<th><?php echo isset($res->discount)?$res->qty * $res->discount:'0'.' /-'; ?></th>
					<th>
						<?php
						if($res->invoice_status == 0)
						{
						?>
						<a href="<?php echo "create-sales?auto_id=".$res->auto_id."&id=".$res->so_id;?>"><span class="btn btn-sm btn-info"> <i class="mdi mdi-pencil"></i>  Edit </span> </a>

						<a><span class="delete-item btn btn-sm btn-danger" data-id="<?php echo $res->auto_id;?>"> <i class="mdi mdi-window-close"></i> Delete </span> </a>
						<?php
						}
						else
						{
							?>
							<span class="text-danger">Invoice Completed</span>
							<?php
						}
						?>
					</th>
				</tr>	
			<?php
		}
		?>
			<tr>
				<td  colspan="6"></td>
	            <td>Total</td>
	            <td><?php echo $_total; ?></td>

	        </tr>
		<?php
	}
	else
	{
		echo "1";
	}

}
?>