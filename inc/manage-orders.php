<?php 
session_start();
include 'config.php';
$branch = isAdminDetails($conn);
if($_POST["method"]==1)
{
    $type = isset($_POST['ordertype']) ? $_POST['ordertype'] : NULL;
    $num_rec_per_page = 25;
    $page = isset($_POST["page"]) ? $_POST["page"] : 1;
    $sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
    $order = $_POST['order']==1 ? 'ASC' : 'DESC';
    $search = isset($_POST['search']) ? $_POST['search'] : NULL;
    $categories = isset($_POST['categories']) ? $_POST['categories'] : NULL; 
    $sorts = array('id','name','city','amount','createdate','payment_mode','order_status');
    $nsort = $sorts[$sort];
    $start_from = ($page-1) * $num_rec_per_page;
    $uid = isset($_POST['uid'])? $_POST['uid'] : '0';
    if($uid != '0')
    {
        $default = "SELECT ps._reading AS reading, pa.delivery, pa.amount,da.name, ps.order_id,ps.order_status,ps.payment_mode,ps.createdate, ps._delivery FROM `ss_payment_status` ps JOIN `ss_delivery_address` da ON da.bno = ps.order_id JOIN `ss_payments` pa ON pa.bno = da.bno WHERE pa.uid='".$uid."' AND ps.status ='1' ";
    }
    else
    {
        $default = "SELECT ps._reading AS reading, pa.delivery, pa.amount,da.name, ps.order_id,ps.order_status,ps.payment_mode,ps.createdate, ps._delivery FROM `ss_payment_status` ps JOIN `ss_delivery_address` da ON da.bno = ps.order_id JOIN `ss_payments` pa ON pa.bno = da.bno WHERE ps.status ='1' ";
    }
    if($search)
    {
        $default .= " AND `pa`.`bno` LIKE '%".$search."%' ";
    }
    // if($branch)
    // {
    //     $default .= " AND `ps`.`branch` =  '".$branch."'";
    // }
    if($type)
    {
        if($categories==1)
        {
            if($uid==0)
            {
                $default .= " AND `ps`.`order_status` =  'Success' OR `ps`.`order_status` =  'Failure' OR `ps`.`order_status` =  'Processing' ";
            }
        }
        if($categories==2)
        {
            $default .= " AND `ps`.`order_status` =  'Processing'";
        }
        if($categories==3)
        {
           $default .= " AND `ps`.`order_status` =  'Failure'";
        }
        if($categories==4)
        {
            $default .= " AND `ps`.`order_status` =  'Canceled'";
        }
        if($categories==5)
        {
            $default .= " AND `ps`.`order_status` =  'Closed'";
        }
    }
    if($uid==0)
    {
        if($type==1)
        {
            $default .= " AND `ps`.`order_status` =  'Success' OR `ps`.`failure_status` =  '1' OR `ps`.`order_status` =  'Processing' ";
        }
        if($type==2)
        {
            $default .= " AND `ps`.`order_status` =  'Processing'";
        }
        if($type==3)
        {
            $default .= " AND `ps`.`order_status` =  'Failure'";
        }
        if($type==4)
        {
            $default .= " AND `ps`.`order_status` =  'Canceled'";
        }
        if($type==5)
        {
            $default .= " AND `ps`.`order_status` =  'Closed'";
        }
    }

    $qry = $default;
    $qry .= " GROUP BY pa.bno";
    if($sort==0)
    {
        $qry .= " ORDER BY `ps`.`".$nsort."` ".$order;
    }
    elseif($sort==1 || $sort== 2)
    {
        $qry .= " ORDER BY `da`.`".$nsort."` ".$order;
    }
    elseif($sort==3)
    {
        $qry .= " ORDER BY ABS(`pa`.`".$nsort."`) ".$order;
    }
    elseif($sort==4 || $sort==5 || $sort== 6)
    {
        $qry .= " ORDER BY `ps`.`".$nsort."` ".$order;
    }
    else
    {
        $qry .= " ORDER BY `ps`.`".$nsort."` ".$order;
    }
    $qry .= "  LIMIT $start_from, $num_rec_per_page";
    //echo "<!-- ". $qry . "-->";
    $selectquery = mysqli_query($conn, $qry);
    $numrows = mysqli_num_rows($selectquery);
    $cats = $default;
    if($search)
    {
        $cats .= " AND `pa`.`bno` LIKE '%".$search."%' ";
    }
    $total = mysqli_query($conn, $cats);
    $totals = mysqli_num_rows($total);
    $total_pages = ceil($totals / $num_rec_per_page); 
    if($numrows > 0) 
    {
    ?>
        <?php
            while($row = mysqli_fetch_array($selectquery)) 
            {
                if($row['order_status']=='Success')
                {
                    $color = 'primary';
                    $icon = 'check-all';
                    $view = ' Order Confirm';
                    $ocolor = '#5cbd8a';
                    $class="";
                    $pstyle = 'background:#6ed387;color:#ffffff;border: 1px solid #fff;';
                    $dstyle = 'border: 1px solid #fff;';
                }
                elseif($row['order_status']=='Processing')
                {
                    $color = 'success';
                    $icon = 'check-alld';
                    $view = 'Processing';
                    $class="";
                    $ocolor = 'white';
                    $pstyle = 'background:#f6890a;color:#ffffff;border: 1px solid #f6890a;';
                    $dstyle = 'border: 1px solid #fff;';
                }
                elseif($row['order_status']=='Failure')
                {
                    $color = 'danger';
                    $icon = 'check-allf';
                    $view = 'Failure';
                    $class="faliureview";
                    $ocolor = 'white';
                    $pstyle = 'background:#f6890a;color:#ffffff;border: 1px solid #f6890a;';
                    $dstyle = 'border: 1px solid #fff;';
                }
                elseif($row['order_status']=='Invalid')
                {
                    $color = 'danger';
                    $icon = 'check-allf';
                    $view = 'Invalid';
                    $class="";
                    $ocolor = 'white';
                    $pstyle = 'background:#f6890a;color:#ffffff;border: 1px solid #f6890a;';
                    $dstyle = 'border: 1px solid #fff;';
                }
                elseif($row['order_status']=='Canceled')
                {
                    $color = 'danger';
                    $icon = 'check-allf';
                    $view = ' Canceled';
                    $class="";
                    $ocolor = 'white';
                    $pstyle = 'background:#f6890a;color:#ffffff;border: 1px solid #f6890a;';
                    $dstyle = 'border: 1px solid #fff;';
                }
                elseif($row['order_status']=='Closed')
                {
                    $color = 'success';
                    $icon = 'emoticon-happyf';
                    $view = 'Delivered';
                    $class="";
                    $ocolor = 'white';
                    $pstyle = 'background:#5bbe88;color:#ffffff;border: 1px solid #f6890a;';
                    $dstyle = 'border: 1px solid #fff;';
                }
                else
                {
                    $color = 'danger';
                    $icon = 'alert-circle-outline';
                    $view = 'Error';
                    $ocolor = 'white';
                    $class="";
                    $pstyle = 'background:#f6890a;color:#ffffff;border: 1px solid #f6890a;';
                    $dstyle = 'border: 1px solid #fff;';
                }
            ?>
            <tr id="row_<?php echo $row['order_id'];?>" style="background: <?php echo $ocolor; ?>">
                <td data-order="<?php echo $row['order_id'];?>" class="clickable"> <span><i class="fa fa-<?php echo $row['reading']==1 ? 'envelope': 'envelope-open'; ?>" aria-hidden="true"></i></span> <?php echo $row['order_id']; ?></td>
                <td data-order="<?php echo $row['order_id'];?>" class="clickable"> <?php echo $row['name']; ?></td>
                <td data-order="<?php echo $row['order_id'];?>" class="clickable"> <?php echo $row['amount'] + $row['delivery']; ?></td>
                <td data-order="<?php echo $row['order_id'];?>" class="clickable"> <?php echo date("d-m-Y h:i A", strtotime($row['createdate'])); ?></td>
                <td> 
                    <span data-order="<?php echo $row['order_id'];?>" id="_proce_txt_<?php echo $row['order_id'];?>"  class="btn btn-sm btn-<?php echo $color; ?> <?php echo $class; ?>">
                        <i class="mdi mdi-<?php echo $icon; ?>"></i><?php echo $view; ?>
                    </span>
                </td>
                <td>
                    <?php if($row['order_status']=="Closed"): ?>
                        <?php echo date("d-m-Y h:i A", strtotime($row['_delivery'])); ?>
                    <?php else: ?>
                        <button id="_proce_<?php echo $row['order_id'];?>"  type="button" <?php echo $row['order_status']=='Success' ? '' : 'disabled'; ?> data-id="<?php echo $row['order_id'];?>" data-process="Processing" type="button" class="br20 newWay btn btn-sm btn-info" style="<?php echo $pstyle; ?>" > Processing </button>
                        <button id="_dele_<?php echo $row['order_id'];?>" type="button" <?php echo $row['order_status']=='Processing' ? '' : 'disabled'; ?> data-id="<?php echo $row['order_id'];?>" data-process="Closed" type="button" class="br20 newWay btn btn-sm btn-purple" style="<?php echo $dstyle; ?>"> Delivered </button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td><?php if($page>=2): ?> 
                <span data-page="<?php echo $page-1; ?>" class="pages btn btn-outline-purple waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>
                <?php endif; ?>
            </td>
            <td></td>
            <td></td>
            <td>Page(s) - <?php echo $page.'/'.$total_pages; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php if($total_pages>$page): ?>
                <span data-page="<?php echo $page+1; ?>" class="pages btn btn-outline-purple waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
                <?php endif; ?></td>
        </tr>
     <?php
    } 
    else { echo '1'; }
}
if($_POST['method']==2)
{

    $type = isset($_POST['ordertype']) ? $_POST['ordertype'] : 1;
    $order = array('All Orders ','Processing Orders','Failure Orders','Canceled Orders','Closed Orders');
    array_unshift($order,"");
    unset($order[0]);
    if($type==1)
    {
        foreach ($order as $key => $value) 
        {
            $select = $type==$key ? 'selected' : '';
            echo "<option $select value='$key'>$value</option>";
        }
    }
    else
    {
        echo "<option selected value='$type'>$order[$type]</option>";
    }
}
if($_POST['method']==3)
{
    $qry = mysqli_query($conn,"SELECT * FROM `2k18c_branches` WHERE `status` = 1 AND `deleted` = 1 AND `type` = 1");
    if(mysqli_num_rows($qry)>0)
    {
        while($row=mysqli_fetch_object($qry))
        {
            echo "<option value='".$row->id."'> ".$row->city." </option>";  
        }
    }
}
if($_POST['method']==4)
{
    date_default_timezone_set('Asia/Kolkata');
    $now =  date("d-m-Y H:i:s");
    $_id = $_POST['id'];
    $_process = $_POST['_process'];
    $qry_ = "UPDATE `ss_payment_status` SET `order_status` = '".$_process."' ";
    if($_POST['_process']=="Processing")
    {
        $qry_ .= " , `_processing` = '".$now."' ";
    }
    if($_POST['_process']=="Closed")
    {
        $qry_ .= " , `_delivery` = '".$now."' ";
    }
    $qry_ .= " WHERE `order_id` ='".$_id."' ";
    // echo $qry_ ;die();
    $qry = mysqli_query($conn,$qry_);
    echo $qry ? 1 : 0;
}
?>