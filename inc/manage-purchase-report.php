<?php 
session_start();
include 'config.php';
if($_POST['method'] =='add-form')
{
    // print_r($_POST);
    $from     = date('Y-m-d', strtotime($_POST['from']));
    $to       = date('Y-m-d', strtotime($_POST['to']));
    $product  = $_POST['product'];
    // $num_rec_per_page = 25;
    $page = isset($_POST["page"]) ? $_POST["page"] : 1;
    // $start_from = ($page-1) * $num_rec_per_page;

    if($from!='' && $to!='')
    {   
        $qry = "SELECT * FROM `ss_item_stk` WHERE `date` BETWEEN '".$from."' AND '".$to."' ";
        if($product != 0)
        {
            $qry .= " AND `product_id` = '".$product."' ";   
        }
        $qry .= "GROUP BY `invoice_no`";

        $selectquery = mysqli_query($conn, $qry);

        $con = mysqli_num_rows($selectquery);
     

        if($con > 0)
        {   
            $Sno = 1;
            while($res = mysqli_fetch_object($selectquery))
            {
                $count = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(qty) AS t_qty FROM `ss_item_stk` WHERE invoice_no = '".$res->invoice_no."'"));

                if($product != 0)
                {
                    $product = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE id = '".$res->product_id."' AND status = '1' AND published = '1'"));
                }

                ?>
                    <tr>
                        <th><?php echo $Sno; ?></th>
                        <th><?php echo $res->invoice_no; ?></th>
                        <th><?php echo isset($product->qty)?$product->qty:$count->t_qty; ?></th>
                        <th><?php echo date('d-m-Y', strtotime($res->date)); ?></th>
                        <th><a target="_blank" href="<?php echo "view-invoice-summery?id=".$res->invoice_no;?>"><span class="btn btn-sm btn-info"> <i class="mdi mdi-eye"></i>  View </span> </a></th>
                    </tr>
                <?php
            $Sno++;
            } 
            ?>
                <!-- <tr>

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

                </tr> -->
            <?php  
        }
        else
        {
            echo "1";  
        }
    }
    else
    {
        echo "1";
    }
}
?>   