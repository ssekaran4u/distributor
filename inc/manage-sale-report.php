<?php 
session_start();
include 'config.php';
if($_POST['method'] =='add-form')
{
    // print_r($_POST);
    $from     = date('Y-m-d', strtotime($_POST['from']));
    $to       = date('Y-m-d', strtotime($_POST['to']));
    $customer  = $_POST['customer'];
    $product  = $_POST['product'];
    // $num_rec_per_page = 5;
    $page = isset($_POST["page"]) ? $_POST["page"] : 1;
    // $start_from = ($page-1) * $num_rec_per_page;

    if($from!='' && $to!='')
    {   
        $qry = "SELECT * FROM `ss_sales_details` WHERE `createdate` BETWEEN '".$from."' AND '".$to."'AND published = '1' AND status = '1'";
        if($customer != 0)
        {
            $qry .= " AND `customer_id` = '".$customer."' ";   
        }
        if($product != 0)
        {
            $qry .= " AND `pid` = '".$product."' ";
        }
        $qry .= "GROUP BY so_no";


        $selectquery = mysqli_query($conn, $qry);
        $con = mysqli_num_rows($selectquery);

        // $total_pages = ceil($con / $num_rec_per_page); 

        if($con > 0)
        {
            $_total=0;
            while($res = mysqli_fetch_object($selectquery))
            {
                $so_id = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_sales WHERE so_no = '".$res->so_no."' AND published = '1' AND status = '1'"));

                $product  = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM ss_items WHERE id = '".$res->pid."' AND published = '1' AND status = '1'"));

                $customer = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE id = '".$res->customer_id."' AND status = '1' AND published = '1'"));

                $_b_dis = $product->oprice * $product->allowance / 100;

                $allowance_val  = $res->qty * round($_b_dis);
                
                $_d_dis = $product->oprice * $customer->d_allowance / 100;

                $d_allowance_val = $res->qty * round($_d_dis);

                $discount = $res->qty * round($res->discount);

                $_sta = $res->qty * round($res->sta);

                $netdis = $allowance_val + $_sta + $d_allowance_val + $discount;

                $sub_to = $res->qty * $product->oprice;

                $total = $sub_to - $netdis;

                $_total += $total;

                ?>
                    <tr>
                        <th><?php echo $res->so_no; ?></th>
                        <th><?php echo $customer->name; ?></th>
                        <th><?php echo $_total; ?></th>
                        <th><?php echo date('d-m-Y', strtotime($res->createdate)); ?></th>
                        <th><a target="_blanck" href="<?php echo "view-bill-summery?id=".$so_id->id;?>"><span class="btn btn-sm btn-success"> <i class="mdi mdi-eye"></i>  View </span> </a></th>
                    </tr>
                <?php
            } 
            ?>
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