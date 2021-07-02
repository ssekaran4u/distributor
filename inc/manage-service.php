<?php 
session_start();
include 'config.php'; 
$branch = isAdminDetails($conn);
$num_rec_per_page = 25;
$page = isset($_POST["page"]) ? $_POST["page"] : 1;
$sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
$order = $_POST['order']==1 ? 'DESC' : 'ASC';
$search = isset($_POST['search']) ? $_POST['search'] : NULL;
$sorts = array('service_no','status');
$nsort = $sorts[$sort];
$start_from = ($page-1) * $num_rec_per_page;
$qry = "SELECT * FROM `ss_service_stk` WHERE `published` = '1' ";
if($search)
{
    $qry .= " AND `service_no` LIKE '%".$search."%' ";
}
$qry .= " ORDER BY `ss_service_stk`.`".$nsort."` ".$order." LIMIT $start_from, $num_rec_per_page";
// echo $qry; die;
$selectquery = mysqli_query($conn, $qry);
$numrows = mysqli_num_rows($selectquery);
$cats = "SELECT * FROM `ss_service_stk` WHERE `published` = '1'";
if(isset($search))
{
    $cats .= " AND `service_no` LIKE '%".$search."%' ";
}

$total = mysqli_query($conn, $cats);

$totals = mysqli_num_rows($total);

$total_pages = ceil($totals / $num_rec_per_page); 

if($numrows > 0) 

{

?>

    <?php

    $i = 1;

        while($row = mysqli_fetch_array($selectquery)) {

            $product = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$row['p_id']."' AND `published` = '1'"));

        ?>

        <tr id="<?php echo $row['id']; ?>">

            <td ><?php echo $row['service_no']; ?></td>

            <td class="text-center"><?php echo isset($product['title'])?$product['title']:'---'; ?></td>

            <td class="text-center"> 

                <span class="btn btn-sm btn-<?php echo $row['progress']==1 ? 'danger' : 'success'; ?>">

                <i class="fa fa-<?php echo $row['progress']==1 ? 'spinner' : 'check-square-o'; ?>"></i>

                <?php echo $row['progress']==1 ? 'Progress' : 'Completed'; ?>

                </span>

            </td>
            <td class="text-center">
                <span class="btn btn-sm btn-warning service_view" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#service_view"> <i class=" mdi mdi-monitor-multiple"></i>  View </span>

                <a href="<?php echo "create-service?id=".$row['id'];?>"><span class="btn btn-sm btn-info"> <i class="mdi mdi-pencil"></i>  Edit </span> </a>

                <!-- <a><span class="delete-service btn btn-sm btn-danger" data-id="<?php echo $row['id'];?>"> <i class="mdi mdi-window-close"></i> Delete </span> </a> -->



            </td>

        </tr>

    <?php } ?>

    <tr>

        <td>

            <?php if($page>=2): ?> 

            <span data-page="<?php echo $page-1; ?>" class="pages btn btn-outline-purple waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>

            <?php endif; ?>

        </td>

        <td class="text-left">Page(s) - <?php echo $page.'/'.$total_pages; ?></td>
        <td></td>
        <td class="text-right"> 

            <?php if($total_pages>$page): ?>

            <span data-page="<?php echo $page+1; ?>" class="pages btn btn-outline-purple waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>

            <?php endif; ?>

        </td>

    </tr>

 <?php

} 

else { echo '1'; }

?>   