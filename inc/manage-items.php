<?php 
session_start();
include 'config.php';
$branch = isAdminDetails($conn);
if($_POST["method"]==1)
{
    $num_rec_per_page = 25;
    $page = isset($_POST["page"]) ? $_POST["page"] : 1;
    $sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
    $order = $_POST['order']==1 ? 'DESC' : 'ASC';
    $search = isset($_POST['search']) ? $_POST['search'] : NULL;
    $categories = isset($_POST['categories']) ? $_POST['categories'] : NULL; 
    $sorts = array('title','cid','oprice','status');
    $nsort = $sorts[$sort];
    $start_from = ($page-1) * $num_rec_per_page;
    $qry = "SELECT u.*, p.title AS category FROM ss_category p 
    JOIN ss_items u ON p.id = u.cid WHERE  u.published =1";
    if($categories)
    {

        $qry .= " AND `u`.`cid`  = '".$categories."' ";
    }

    if($search)

    {

        $qry .= " AND `u`.`title` LIKE '%".$search."%' ";

    }

    if($sort==0)

    {

        $qry .= " ORDER BY `u`.`".$nsort."` ".$order;

    }

    elseif($sort==1)

    {

        $qry .= " ORDER BY ABS(`u`.`".$nsort."`) ".$order;   

    }

    elseif($sort==2)

    {

        $qry .= " ORDER BY ABS(`u`.`".$nsort."`) ".$order;   

    }

    else

    {

        $qry .= " ORDER BY `u`.`".$nsort."` ".$order;     

    }



    $qry .= " LIMIT $start_from, $num_rec_per_page";

    //echo $qry; die();

    $selectquery = mysqli_query($conn, $qry);

    $numrows = mysqli_num_rows($selectquery);

    $cats = "SELECT * FROM `ss_items` WHERE `published` = '1' AND `status` = 1 ";

    if($search)

    {

        $cats .= " AND `title` LIKE '%".$search."%' ";

    }

    if($categories)

    {

        $cats .= " AND `cid`  = '".$categories."' ";

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

            $brand = mysqli_fetch_array(mysqli_query($conn, "SELECT `brand` FROM `ss_brands` WHERE `id` = '".$row['brand']."' AND `published` = '1' "));

            ?>

            <tr>

                <td><?php echo mb_strimwidth($row['title'], 0,30, '...'); ?></td>

                <td><?php echo $row['description']; ?></td>

                <td><?php echo $row['category']; ?></td>

                <td class="text-center"><?php echo $brand['brand']; ?></td>

                <td><?php echo $row['oprice']; ?></td>

                <td>
                    <a href="stock-item?id=<?php echo $row['id'];?>"><span class="btn btn-sm btn-<?php echo $row['extra']==0 ? 'danger' : 'primary'; ?>"> <i class="fa fa-hdd-o"></i>  <?php echo $row['extra']==0 ? 'Empty' : $row['extra'].'Number'; ?> </span> </a>
                        
                </td>

                <td class="text-center">

                    <!-- <a href="stock-item?id=<?php echo $row['id'];?>"><span class="btn btn-sm btn-warning"> <i class="fa fa-hdd-o"></i>  Stock </span> </a> -->
                    <span class="btn btn-sm btn-<?php echo $row['status']==1 ? 'success' : 'danger'; ?>">

                    <i class="mdi mdi-<?php echo $row['status']==1 ? 'eye' : 'eye-off'; ?>"></i>

                    <!-- <?php echo $row['status']==1 ? 'Enable' : 'Disable'; ?> -->

                    </span>

                    <a href="create-item?type=edit&id=<?php echo $row['id'];?>"><span class="btn btn-sm btn-info"> <i class="mdi mdi-pencil"></i>  </span> </a>

                    <a class="delete-pdt" data-id="<?php echo $row['id'];?>"><span class="btn btn-sm btn-danger"> <i class="mdi mdi-window-close"></i> </span> </a>

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

}

if($_POST['method']==2)

{

  ?>
    <?php
            $a = mysqli_query($conn, "SELECT * FROM ss_category WHERE published = 1 AND parentid = 0 ORDER BY title ASC ");
            $count = mysqli_num_rows($a);
            ?>
            <option value="">Select Category</option>
            <?php
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