<?php 
session_start();
include 'config.php'; 
$branch = isAdminDetails($conn);
$num_rec_per_page = 8;
$page = isset($_POST["page"]) ? $_POST["page"] : 1;
$sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
$order = $_POST['order']==1 ? 'DESC' : 'ASC';
$search = isset($_POST['search']) ? $_POST['search'] : NULL;
$sorts = array('city','cname','mobile','email','status');
$nsort = $sorts[$sort];
$start_from = ($page-1) * $num_rec_per_page;
$qry = "SELECT * FROM `2k18c_branches` WHERE `deleted` ='1' AND `username` = '".$_SESSION['username']."'";
if($search)
{
    $qry .= " AND `city` LIKE '%".$search."%' ";
}
$qry .= " ORDER BY `2k18c_branches`.`".$nsort."` ".$order." LIMIT $start_from, $num_rec_per_page";
$selectquery = mysqli_query($conn, $qry);
$numrows = mysqli_num_rows($selectquery);
$cats = "SELECT * FROM `2k18c_branches` WHERE `deleted` ='1' AND `username` = '".$_SESSION['username']."'";
if($search)
{
    $cats .= " AND `city` LIKE '%".$search."%' ";
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
        ?>
        <tr>
            <td><?php echo $row['city']; ?></td>
            <td><?php echo $row['cname']; ?></td>
            <td><?php echo $row['mobile']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <!-- <td class="text-center"> 
                <span class="btn btn-sm btn-<?php echo $row['status']==1 ? 'success' : 'danger'; ?>">
                <i class="mdi mdi-<?php echo $row['status']==1 ? 'eye' : 'eye-off'; ?>"></i>
                <?php echo $row['status']==1 ? 'Enable' : 'Disable'; ?>
                </span>
            </td> -->
            <td class="text-center">
                <a href="create-branch?view=edit&id=<?php echo $row['id']; ?>"><span class="btn btn-sm btn-info"> <i class="mdi mdi-pencil"></i>  Edit </span> </a>
                <!-- <a class="delete-btn" data-id="<?php echo $row['id'];?>"><span class="btn btn-sm btn-danger"> <i class="mdi mdi-window-close"></i> Delete </span> </a> -->
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td>
            <?php if($page>=2): ?> 
            <span data-page="<?php echo $page-1; ?>" class="pages btn btn-outline-purple waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>
            <?php endif; ?>
        </td>
        <td></td>
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