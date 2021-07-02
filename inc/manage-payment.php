<?php 
session_start();
include 'config.php'; 
$branch = isAdminDetails($conn);
$num_rec_per_page = 25;
$page = isset($_POST["page"]) ? $_POST["page"] : 1;
$sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
$order = $_POST['order']==1 ? 'DESC' : 'ASC';
$search = isset($_POST['search']) ? $_POST['search'] : NULL;
$sorts = array('id','cname','status');
$nsort = $sorts[$sort];
$start_from = ($page-1) * $num_rec_per_page;
$qry = "SELECT * FROM `ss_customers` WHERE `published` = '1' ";
if($_SESSION['type'] =='2')
{
    $qry .='AND `userid`="'.$_SESSION['uid'].'"';
}
if($search)
{
    $qry .= " AND `cname` LIKE '%".$search."%' ";
}
$qry .= " ORDER BY `ss_customers`.`".$nsort."` ".$order." LIMIT $start_from, $num_rec_per_page";
// echo $qry; die;
$selectquery = mysqli_query($conn, $qry);
$numrows = mysqli_num_rows($selectquery);
$cats = "SELECT * FROM `ss_customers` WHERE `published` = '1'";
if(isset($search))
{
    $cats .= " AND `cname` LIKE '%".$search."%' ";
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
        $cid=$row['id'];
        $cname   = isset($row['name'])?$row['name']:'---';
        $cmobile = isset($row['mobile'])?$row['mobile']:'---';
        $cemail  = isset($row['email'])?$row['email']:'---';
        $cre_lmt = isset($row['credit_lmt'])?$row['credit_lmt']:'---';
        $aval_lmt = isset($row['avl_lmt'])?$row['avl_lmt']:'---';

        ?>

        <tr id="<?php echo $row['id']; ?>">

            <td><?php echo $cname; ?></td>

            <td><?php echo $cmobile; ?></td>

            <td><?php echo $cemail; ?></td>

            <td><?php echo $cre_lmt; ?></td>

            <td><?php echo $aval_lmt; ?></td>

            <td class="text-center">
               
                <a href="<?php echo "create-paymeny?id=".$row['id'];?>"><span class="btn btn-sm btn-info"> <i class="mdi mdi-pencil"></i>  Add </span> </a>

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