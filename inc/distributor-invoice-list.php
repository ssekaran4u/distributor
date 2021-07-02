<?php 
session_start();
include 'config.php'; 
$branch = isAdminDetails($conn);
$num_rec_per_page = 25;
$page = isset($_POST["page"]) ? $_POST["page"] : 1;
$sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
$order = $_POST['order']==1 ? 'ASC' : 'DESC';
$search = isset($_POST['search']) ? $_POST['search'] : NULL;
$sorts = array('so_no','customer_id','status');
$nsort = $sorts[$sort];
$start_from = ($page-1) * $num_rec_per_page;
$qry = "SELECT * FROM `ss_distributor_invoice` WHERE `status` = '1' ";
if($_SESSION['type'] =='2')
{
    $qry .='AND `userid`="'.$_SESSION['uid'].'"';
}
if($search)
{
    $qry .= " AND `so_no` LIKE '%".$search."%' ";
}
$qry .= " ORDER BY `ss_distributor_invoice`.`".$nsort."` ".$order." LIMIT $start_from, $num_rec_per_page";
// echo $qry; die;
$selectquery = mysqli_query($conn, $qry);
$numrows = mysqli_num_rows($selectquery);
$cats = "SELECT * FROM `ss_distributor_invoice` WHERE `status` = '1'";
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

        $cid=$row['customer_id'];
        if($cid)
        {
            $customer = mysqli_fetch_object(mysqli_query($conn,"SELECT * FROM `ss_distributors` where `id`='".$cid."'"));
        }

        $cname   = isset($customer->store)?$customer->store:'---';
        $cmobile = isset($customer->mobile)?$customer->mobile:'---';

        if($row['published'] != 1)
        {
            $color = 'style="background-color: #dbdbdb;"';
        }
        else
        {
            $color = 'style="background-color: #fff;"';
        }

        ?>

        <tr id="<?php echo $row['id']; ?>" <?php echo $color; ?>>

            <td><?php echo $row['so_no']; ?></td>

            <td><?php echo $cname; ?></td>

            <td><?php echo $cmobile; ?></td>

            <td><?php echo date('d-m-Y', strtotime($row['dated'])); ?></td>

            <td class="text-center">
                <a href="<?php echo "view-bill-dist-inv?id=".$row['id'];?>"><span class="btn btn-sm btn-success"> <i class="mdi mdi-eye"></i>   </span> </a>

                <!-- <a><span class="delete-category btn btn-sm btn-danger" data-id="<?php echo $row['id'];?>"> <i class="mdi mdi-window-close"></i> Delete </span> </a> -->

                <?php
                    if($row['published'] == 1)
                    {
                        ?>
                        <a href="<?php echo "distributor-invoice?id=".$row['id'];?>"><span class="btn btn-sm btn-info"> <i class="mdi mdi-pencil"></i>   </span> </a>
                        
                        <a><span class="delete-btn btn btn-sm btn-danger" data-id="<?php echo $row['id'];?>"> <i class="mdi mdi-window-close"></i>  </span> </a>
                        <?php
                    }
                    else
                    {
                        ?>
                        <a><span class="btn btn-sm btn-info"> <i class="mdi mdi-pencil"></i>   </span> </a>

                        <a><span class="btn btn-sm btn-danger disabled"> <i class="mdi mdi-window-close"></i>  </span> </a>
                        <?php  
                    }
                ?>

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