<?php 
session_start();
include 'config.php'; 
$branch = isAdminDetails($conn);
$num_rec_per_page = 25;
$customer_id = isset($_POST["customer_id"]) ? $_POST["customer_id"] : '';
$page = isset($_POST["page"]) ? $_POST["page"] : 1;
$sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
$search = isset($_POST['search']) ? $_POST['search'] : NULL;
$sorts = array('so_no','customer_id','status');
$nsort = $sorts[$sort];
$start_from = ($page-1) * $num_rec_per_page;
$qry = "SELECT * FROM `ss_payment` WHERE `published` = '1' AND customer_id = '".$customer_id."' ";
if($search)
{
    $qry .= " AND `so_no` LIKE '%".$search."%' ";
}
// $qry .= " ORDER BY `ss_payment`.`".$nsort."` ".$order." LIMIT $start_from, $num_rec_per_page";
// echo $qry; die;
$selectquery = mysqli_query($conn, $qry);
$numrows = mysqli_num_rows($selectquery);
$cats = "SELECT * FROM `ss_payment` WHERE `published` = '1' AND customer_id = '".$customer_id."'";
if(isset($search))
{
    $cats .= " AND `so_no` LIKE '%".$search."%' ";
}
// echo $cats; die();
$total = mysqli_query($conn, $cats);

$totals = mysqli_num_rows($total);

$total_pages = ceil($totals / $num_rec_per_page); 

if($numrows > 0) 

{

?>

    <?php

    $i = 1;

        while($row = mysqli_fetch_array($selectquery)) {


        ?>

        <tr id="<?php echo $row['id']; ?>">


            <td><?php echo $row['amount']; ?></td>

            <td><?php echo $row['type']; ?></td>
            
            <td><?php echo $row['description']; ?></td>

            <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>


            <td class="text-center">
                <!-- <a href="<?php echo "view-bill-summery?id=".$row['id'];?>"><span class="btn btn-sm btn-warning"> <i class="mdi mdi-eye"></i>  View </span> </a> -->


            </td>

        </tr>

    <?php } ?>


 <?php

} 

else { echo '1'; }

?>   