<?php 
session_start();
include 'config.php';
$branch = isAdminDetails($conn);

$num_rec_per_page = 8;

$page = isset($_POST["page"]) ? $_POST["page"] : 1;

$sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;

$order = $_POST['order']==1 ? 'DESC' : 'ASC';

$search = isset($_POST['search']) ? $_POST['search'] : NULL;

$sorts = array('email','createdate');

$nsort = $sorts[$sort];

$start_from = ($page-1) * $num_rec_per_page;

$qry = "SELECT * FROM `ss_newsletter` WHERE `id` != ''";

if($search)

{

    $qry .= " AND `email` LIKE '%".$search."%' ";

}

$qry .= " ORDER BY `ss_newsletter`.`".$nsort."` ".$order." LIMIT $start_from, $num_rec_per_page";



$selectquery = mysqli_query($conn, $qry);

$numrows = mysqli_num_rows($selectquery);

$cats = "SELECT * FROM `ss_newsletter` WHERE `id` != ''";

if($search)

{

    $cats .= " AND `email` LIKE '%".$search."%' ";

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

            <td><?php echo $row['email']; ?></td>

            <td><?php echo date("d-m-Y h:i A", strtotime($row['createdate'])); ?></td>

        </tr>

    <?php } ?>

    <tr>

        <td>

            <?php if($page>=2): ?> 
            <span data-page="<?php echo $page-1; ?>" class="pages btn btn-outline-purple waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>
            <?php endif; ?>
        </td>
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