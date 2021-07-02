<?php 
session_start();
include 'config.php'; 
$branch = isAdminDetails($conn);
$num_rec_per_page = 25;
$page = isset($_POST["page"]) ? $_POST["page"] : 1;
$sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
$order = $_POST['order']==1 ? 'DESC' : 'ASC';
$search = isset($_POST['search']) ? $_POST['search'] : NULL;
$sorts = array('id','invoice_no','status');
$nsort = $sorts[$sort];
$start_from = ($page-1) * $num_rec_per_page;
$qry = "SELECT * FROM `ss_item_stk` WHERE `published` = '1' ";
if($search)
{
    $qry .= " AND `invoice_no` LIKE '%".$search."%' ";
}
$qry .= "GROUP BY `invoice_no` ORDER BY `ss_item_stk`.`".$nsort."` ".$order." LIMIT $start_from, $num_rec_per_page";

$selectquery = mysqli_query($conn, $qry);
$numrows = mysqli_num_rows($selectquery);
$cats = "SELECT * FROM `ss_item_stk` WHERE `published` = '1'";
if(isset($search))
{
    $cats .= " AND `invoice_no` LIKE '%".$search."%' ";
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

            <tr>

                <td><?php echo $i++; ?></td>

                <td><?php echo $row['invoice_no']; ?></td>

                <td><?php echo date('d-M-Y', strtotime($row['date'])); ?></td>


                <td class="text-center">

                    <a href="view-invoice-summery?id=<?php echo $row['invoice_no'];?>"><span class="btn btn-sm btn-primary"> <i class="fa fa-hdd-o"></i>  View Inventory </span> </a>

                </td>

            </tr>

    <?php } ?>

        <tr>

            <td></td>

            <td><?php if($page>=2): ?> 

                <span data-page="<?php echo $page-1; ?>" class="pages btn btn-outline-purple waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>

                <?php endif; ?>

            </td>

            <td>Page(s) - <?php echo $page.'/'.$total_pages; ?></td>

            <td><?php if($total_pages>$page): ?>

                <span data-page="<?php echo $page+1; ?>" class="pages btn btn-outline-purple waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>

                <?php endif; ?></td>

            <td></td>

        </tr>

     <?php

    } 

    else { echo '1'; }


if($_POST['method']==2)

{

  ?>

   <?php
    $a = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = 0 ORDER BY title ASC ");
    $i=0; while($rowo = mysqli_fetch_array($a)) { 
    $b = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = '".$rowo['id']."' ORDER BY title ASC");
    $count = mysqli_num_rows($b);
    ?>     
<option value="<?php echo $rowo['id']; ?>"><?php echo $rowo['title']; ?></option>
    <?php if($count>0){ ?>
    <?php
    $i++;
    while($rows = mysqli_fetch_array($b)) { ?>     
<option value="<?php echo $rows['id']; ?>">--<?php echo $rows['title']; ?></option>
        <?php } ?>
    <?php } } ?>


<?php } ?>   