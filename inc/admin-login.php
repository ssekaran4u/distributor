<?php
session_start();
include 'config.php';

if($_POST['method']=='insert')
{
    $error = FALSE;
    $errors = array();
    $required = array('username','password');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error)
    {
        $errors[] = "Please fill all required fields";   
    }
    if(count($errors)==0)
    {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $userss = htmlspecialchars($user);
        $passs = htmlspecialchars(md5($pass));
       $SQL = "SELECT * FROM `ss_distributors` WHERE `email_id` = '".$userss."' AND `password` = '".$passs."' AND `permission` != '3' LIMIT 1 ";
        $result = mysqli_query($conn, $SQL);
        if(mysqli_num_rows($result)==1)
        {   
            $account_year = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_academic` WHERE `status` = '1'  ORDER BY `ss_academic`.`id` DESC"));

            $data = mysqli_fetch_object($result);
            $_SESSION['username']  = $user;
            $_SESSION['admin']     = "admin";
            $_SESSION['uid']       = $data->id;
            $_SESSION['type']      = $data->permission;
            $response['status']    = TRUE;
            $response['type']      = $data->permission;
            $_SESSION['acad_year'] = $account_year->id;
        }
        else
        {
            $response['status'] = FALSE;
            $response['message'] = "Invalid username and password";
        }
    }
    if($errors) 
    {
        $response['status'] = FALSE;
        $response['message'] = $errors;
    }
}

if($_POST['method']=='_UForget')
{
    isURL();
    $error = FALSE;
    $errors = array();
    $required = array('Femail');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error)
    {
        $errors[] = "Enter email id";   
    }

    if($_POST['Femail'])
    {
        if ($_POST['Femail'] && (mb_strlen($_POST['Femail']) > 254 || !filter_var($_POST['Femail'],FILTER_VALIDATE_EMAIL)))
        {
            $errors[] = 'E-mail address does not appear to be valid';
        }

        elseif($_POST['Femail'])
        {
            $sql_err="SELECT `email` FROM `2k18c_branches` WHERE email='".$_POST['Femail']."'";
            $rs=mysqli_query($conn, $sql_err);
            $rsrow=mysqli_num_rows($rs);
            if($rsrow==0)
            {
                $errors[] = $_POST['Femail']. " is does not exist";
            }
        }
    }

    if(count($errors)==0)
    {
        $token = md5(uniqid(mt_rand(), TRUE));
        $exdate = date('Y-m-d H:i:s');

        $qry = "INSERT INTO `ss_password_reset`(`email`,`token`, `expire`) VALUES (
            '".mysqli_real_escape_string($conn,$_POST['Femail'])."',
            '".$token."',
            '".$exdate."'
            )";
        if(mysqli_query($conn, $qry)) 
        {

            $buffer = "<p><a href='".BASE_URL."' target='_blank'><img src='".BASE_URL."images/logo.png' alt='Hotel Junior Kuppanna' border='0' /></a></p><p>A new password was requested for Hotel Junior Kuppanna Account.</p><p>To reset your password click on the link below:<br /><a href='".BASE_URL."reset-password-hash?token=$token'>".BASE_URL."reset-password-hash?token=$token</a></p><p>Best regards,<br />
                Hotel Junior Kuppanna";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: Hotel Junior Kuppanna<no-reply@juniorkuppannaooty.com>' . "\r\n";
            $subject  = 'Hotel Junior Kuppanna - Password reset request';
            mail($_POST['Femail'],$subject,$buffer,$headers);
            $response['status'] = TRUE;
            $response['message'] = "Congratulations! a password reset link has been sent to the provided e-mail address. This token will be expire one hour.";
            $response['type'] = 4;
        }
    }
    if($errors) 
    {
        $response['status'] = FALSE;
        $response['message'] = $errors;
    }
}

if($_POST['method']=='_URset')
{
    $error = FALSE;
    $errors = array();
    $required = array('password','cpassword','hash');
    $today =  date("Y-m-d H:i:s");
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error)
    {
        $errors[] = "Please fill all required fields";   
    }

    if($_POST['cpassword']!= $_POST['password'])
    {
        $errors[] =  "Password is mismatch.";
    }

    if(strlen($_POST['hash'])!=32)
    {
        $errors[] =  "Invalid Hash.";
    }

    if(count($errors)==0)
    {
        $fetch=mysqli_fetch_object(mysqli_query($conn, "SELECT status,email,token,expire FROM `ss_password_reset` WHERE `token` = '".$_POST['hash']."'"));
        if($fetch->status==1)
        {
            $datetime1 = strtotime("now");
            $datetime2 = strtotime($fetch->expire);
            $interval  = abs($datetime2 - $datetime1);
            $minutes   = round($interval / 60);
            if($minutes<=60)
            {
                $rde = "UPDATE `2k18c_branches` SET password = '".md5($_POST['cpassword'])."' WHERE email = '".$fetch->email."'";
                if(mysqli_query($conn, $rde))
                {
                    mysqli_query($conn, "UPDATE `ss_password_reset` SET `status` = 2 WHERE `token` = '".$_POST['hash']."'");
                    $response['type'] = 5;
                    $response['status'] = TRUE;
                    $response['message'] = "Congratulations..! Now you can log in by using your new password. Please wait 5 sec automatically redirect to login page. ";
                }
                else
                {
                    $response['status'] = FALSE;
                    $response['message'] = "Opps! Password reset error. Try again...!";
                }
            }
            else
            {
                $response['status'] = FALSE;
                $response['message'] = "Your token will be expired...";
            }
        }
        else
        {
            $response['status'] = FALSE;
            $response['message'] = "Opps! This token already used..!";
        }
    } 

    if($errors) 
    {
        $response['status'] = FALSE;
        $response['message'] = $errors;
    }
}

if($_POST['method']=='_Pupdate_User')
{
    $error = FALSE;
    $errors = array();
    $required = array('password','npassword','ncpassword');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }
    if($error)
    {
        $errors[] = "Please fill all required fields";   
    }

    if($_POST['password'])
    {
        $rPassword = mysqli_fetch_object(mysqli_query($conn, "SELECT `password` FROM `2k18c_branches` WHERE `username` = '".$_SESSION['username']."'"));
        if (md5($_POST['password'])!=$rPassword->password)
        {
            $errors[] = 'Current Password mismatch';
        }
    }


    if ($_POST['npassword']!=$_POST['ncpassword'])
    {
        $errors[] = 'Password mismatch';
    }


    if(count($errors)==0)
    {
        $rde = "UPDATE `2k18c_branches` SET 
            `password`= '".md5($_POST['ncpassword'])."' WHERE `username` = '".$_SESSION['username']."'";
            if(mysqli_query($conn, $rde))
            {
                $response['type'] = 2;
                $response['status'] = TRUE;
                $response['message'] = "Congratulations..! Password Changed Successfully. ";
            }
            else
            {
                $response['status'] = FALSE;
                $response['message'] = "Opps! Password update error. Try again...!";
            }
    } 

    if($errors) 
    {
        $response['status'] = FALSE;
        $response['message'] = $errors;
    }
}
    
if($_POST['method']=='_getDealer')
{
    $distributor_id = $_POST['distributor_id'];

    if($distributor_id != '')
    {
        $sel_1 = mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `userid` = '".$distributor_id."' AND `published` = '1'");
        $cou_1 = mysqli_num_rows($sel_1);

        $option ='<option value="0">--Select Dealer Name--</option>';
        if($cou_1 > 0)
        {
            while($res_1 = mysqli_fetch_object($sel_1))   
            {
                $dealer_id    = isset($res_1->id)?$res_1->id:'';
                $dealer_name  = isset($res_1->name)?$res_1->name:'';
                $dealer_sName = isset($res_1->cname)?$res_1->cname:'';

                $option .='<option value="'.$dealer_id.'">'.$dealer_name.' - '.$dealer_sName.'</option>';
            }

            $response['status'] = TRUE;
            $response['result'] = $option;
            echo json_encode($response);
            return false;
        }
        else
        {
            $response['status'] = FALSE;
            $response['result'] = $option;
            echo json_encode($response);
            return;
        }
    }
}

if($_POST['method']=='_getDisInvoice')
{
    $error = FALSE;
    $required = array('fromdate', 'todate', 'distributor_id');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error == TRUE)
    {
        $response['status']  = FALSE;
        $response['message'] = "Please fill all required fields"; ;
        echo json_encode($response);
        return;
    }
    else
    {
        $distributor_id = $_POST['distributor_id'];
        $fromdate       = date('Y-m-d', strtotime($_POST['fromdate']));
        $todate         = date('Y-m-d', strtotime($_POST['todate']));

        $sel_1 = mysqli_query($conn, "SELECT * FROM `ss_distributor_invoice` WHERE `dated` BETWEEN '".$fromdate."' AND '".$todate."' AND `customer_id` = '".$distributor_id."' AND `status` = '1' AND `published` = '1'");
        $cou_1 = mysqli_num_rows($sel_1);

        $option ='<option value="0">--Select Invoice--</option>';

        if($cou_1 > 0)
        {
            while($res_1 = mysqli_fetch_object($sel_1))   
            {
                $invoice_id = isset($res_1->id)?$res_1->id:'';
                $invoice_no = isset($res_1->so_no)?$res_1->so_no:'';

                $option .='<option value="'.$invoice_id.'">'.$invoice_no.'</option>';
            }

            $response['status'] = TRUE;
            $response['result'] = $option;
            echo json_encode($response);
            return false;
        }
        else
        {
            $response['status'] = FALSE;
            $response['result'] = $option;
            echo json_encode($response);
            return;
        }
    }
}

if($_POST['method']=='_getDeaInvoice')
{
    $dealer_id = $_POST['dealer_id'];

    if($dealer_id != '')
    {
        $sel_1 = mysqli_query($conn, "SELECT * FROM `ss_sales` WHERE `customer_id` = '".$dealer_id."' AND `published` = '1' AND `status` = '1' ");
        $cou_1 = mysqli_num_rows($sel_1);

        $option ='<option value="0">--Select Invoice--</option>';

        if($cou_1 > 0)
        {
            while($res_1 = mysqli_fetch_object($sel_1))   
            {
                $invoice_id = isset($res_1->id)?$res_1->id:'';
                $invoice_no = isset($res_1->so_no)?$res_1->so_no:'';

                $option .='<option value="'.$invoice_id.'">'.$invoice_no.'</option>';
            }

            $response['status'] = TRUE;
            $response['result'] = $option;
            echo json_encode($response);
            return false;
        }
        else
        {
            $response['status'] = FALSE;
            $response['result'] = $option;
            echo json_encode($response);
            return;
        }
    }
}

if($_POST['method']=='_getProduct')
{
    $category_id = $_POST['category_id'];

    if($category_id != '')
    {   
        $sel_1 = mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `cid` = '".$category_id."' AND `status` = '1' AND `published` = '1'");
        $cou_1 = mysqli_num_rows($sel_1);

        $option ='<option value="0">--Select Product--</option>';

        if($cou_1 > 0)
        {
            while($res_1 = mysqli_fetch_object($sel_1))
            {
                $item_id    = isset($res_1->id)?$res_1->id:'';
                $item_title = isset($res_1->title)?$res_1->title:'';
                $item_desc  = isset($res_1->description)?$res_1->description:'';

                $option .='<option value="'.$item_id.'">'.$item_title.' - '.$item_desc.'</option>';
            }

            $response['status'] = TRUE;
            $response['result'] = $option;
            echo json_encode($response);
            return false;
        }
        else
        {
            $response['status'] = FALSE;
            $response['result'] = $option;
            echo json_encode($response);
            return;   
        }

    }
}

if($_POST['method']=='_getDisSalesReport')
{
    $error=FALSE;
    $required = array('fromdate', 'todate');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error == TRUE)
    {
        $response['status']  = FALSE;
        $response['message'] = "Please fill all required fields"; ;
        echo json_encode($response);
        return;
    }
    else
    {

        $fromdate       = !empty($_POST['fromdate'])?date('Y-m-d', strtotime($_POST['fromdate'])):'';
        $todate         = !empty($_POST['todate'])?date('Y-m-d', strtotime($_POST['todate'])):'';
        $distributor_id = !empty($_POST['distributor_id'])?$_POST['distributor_id']:'';
        $invoice_id     = !empty($_POST['invoice_id'])?$_POST['invoice_id']:'';
        $category_id    = !empty($_POST['category_id'])?$_POST['category_id']:'';
        $product_id     = !empty($_POST['product_id'])?$_POST['product_id']:'';


        if($fromdate <= $todate)
        {
            $sel_1 = "SELECT * FROM `ss_distributor_inv_details` WHERE `dated` BETWEEN '".$fromdate."' AND '".$todate."' ";

            if(!empty($distributor_id))
            {
                $sel_1 .= " AND `customer_id` = '".$distributor_id."'";
            }
            if(!empty($invoice_id))
            {
                $sel_1 .= " AND `so_id` = '".$invoice_id."'";
            }
            if(!empty($category_id))
            {
                $sel_1 .= " AND `cid` = '".$category_id."'";
            }
            if(!empty($product_id))
            {
                $sel_1 .= " AND `pid` = '".$product_id."'";
            }

            $sel_1 .= " AND `published` = '1' AND `status` = '1' ";

            $qry_1 = mysqli_query($conn, $sel_1);
            $cou_1 = mysqli_num_rows($qry_1);

            if($cou_1 > 0)
            {

                $html = '
                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: #f7f7f7;">
                            <th>#</th>
                            <th>Sales No</th>
                            <th>Category</th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Discount</th>
                            <th>GST</th>
                            <th>Net Value</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $i = 1;
                        while($res_1 = mysqli_fetch_object($qry_1))
                        {
                            $auto_id     = !empty($res_1->auto_id)?$res_1->auto_id:'';
                            $so_id       = !empty($res_1->so_id)?$res_1->so_id:'';
                            $so_no       = !empty($res_1->so_no)?$res_1->so_no:'';
                            $cid         = !empty($res_1->cid)?$res_1->cid:'';
                            $pid         = !empty($res_1->pid)?$res_1->pid:'';
                            $code        = !empty($res_1->code)?$res_1->code:'';
                            $qty         = !empty($res_1->qty)?$res_1->qty:'';
                            $price       = !empty($res_1->price)?$res_1->price:'0';
                            $gst         = !empty($res_1->gst)?$res_1->gst:'0';
                            $allowance   = !empty($res_1->allowance)?$res_1->allowance:'0';
                            $sta         = !empty($res_1->sta)?$res_1->sta:'0';
                            $d_allowance = !empty($res_1->d_allowance)?$res_1->d_allowance:'0';
                            $discount    = !empty($res_1->discount)?$res_1->discount:'0';

                            // Discount Calculation
                            $allowance_res   = $price * $allowance / 100;
                            $allowance_val   = $qty * round($allowance_res);

                            $d_allowance     = $price * $d_allowance / 100;
                            $d_allowance_val = $qty * round($d_allowance);

                            $discount_value  = $qty * round($discount);

                            $_sta_value      = $qty * round($sta);

                            $netdiscount = $allowance_val + $_sta_value + $d_allowance_val + $discount_value;

                            $sub_to = $qty * $price;
                            $total  = $sub_to - $netdiscount;

                            // GST Calculation
                            $_gst = "1.".$gst;
                            $_val = $total / $_gst; 

                            $gstper    = $_val * $gst / 100;
                            $total_val = $_val + $gstper;

                            $qry_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE `id` = '".$cid."' AND `published` = '1'"));

                            $cat_title   = isset($qry_2->title)?$qry_2->title:'';

                            $qry_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$pid."' AND `published` = '1'"));

                            $pro_title   = isset($qry_3->title)?$qry_3->title:'';
                            $description = isset($qry_3->description)?$qry_3->description:'';

                            $html .= '
                                <tr>
                                    <th>'.$i.'</th>
                                    <th>'.$so_no.'</th>
                                    <th>'.mb_strimwidth($cat_title, 0, 20, '..').'</th>
                                    <th>'.mb_strimwidth($pro_title, 0, 10, '..').'</th>
                                    <th>'.$description.'</th>
                                    <th>'.number_format($price, 2).'</th>
                                    <th>'.$qty.'</th>
                                    <th>'.number_format($netdiscount, 2).'</th>
                                    <th>'.number_format($gstper, 2).'</th>
                                    <th>'.number_format($total_val, 2).'</th>
                                </tr>
                            ';

                            $i++;
                        }
                    $html .='
                    </tbody>
                </table>';

                $excel_btn = '<button type="submit" name="export" id="form-export" class="btn btn-primary waves-effect waves-light form-export"> <i  class="fa  fa-file-excel-o"></i> Excel </button>';

                $response['status']    = TRUE;
                $response['result']    = $html;
                $response['excel_btn'] = $excel_btn;
                echo json_encode($response);
                return false;
            }
            else
            {
                $response['status']  = FALSE;
                $response['message'] = "No Records";
                echo json_encode($response);
                return;
            }
        }
        else
        {
            $response['status']  = FALSE;
            $response['message'] = "Enter Correct Value"; ;
            echo json_encode($response);
            return; 
        }
    }
}

if($_POST['method']=='_getSerialWiseReport')
{
    $error=FALSE;
    $required = array('fromdate', 'todate', 'search_type');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error == TRUE)
    {
        $response['status']  = FALSE;
        $response['message'] = "Please fill all required fields"; ;
        echo json_encode($response);
        return;
    }
    else
    {

        $fromdate    = !empty($_POST['fromdate'])?date('Y-m-d', strtotime($_POST['fromdate'])):'';
        $todate      = !empty($_POST['todate'])?date('Y-m-d', strtotime($_POST['todate'])):'';
        $category_id = !empty($_POST['category_id'])?$_POST['category_id']:'';
        $search_type = !empty($_POST['search_type'])?$_POST['search_type']:'';

        if($fromdate <= $todate)
        {
            $sel_1 = "SELECT * FROM `ss_item_stk` WHERE `published` = '1' ";

            if(!empty($category_id))
            {
                $sel_1 .= " AND `cid` = '".$category_id."'";
            }

            if($search_type == 1)
            {
                $sel_1 .= " AND `sales_date` BETWEEN '".$fromdate."' AND '".$todate."' AND `status` = '0'";
            }

            if($search_type == 2)
            {
                $sel_1 .= " AND `d_sales_date` BETWEEN '".$fromdate."' AND '".$todate."' AND `delar_sales` = '0'";
            }

            if($search_type == 3)
            {
                $sel_1 .= " AND `service_date` BETWEEN '".$fromdate."' AND '".$todate."' AND `service_status` = '0'";
            }

            $qry_1 = mysqli_query($conn, $sel_1);
            $cou_1 = mysqli_num_rows($qry_1);

            if($cou_1 > 0)
            {
                $html = '
                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: #f7f7f7;">
                            <th>#</th>
                            <th>Sales No</th>
                            <th>Category</th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Serial No</th>
                            <th>Sales Date</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $i = 1;
                        while($res_1 = mysqli_fetch_object($qry_1))
                        {   
                            $cid          = !empty($res_1->cid)?$res_1->cid:'';
                            $product_id   = !empty($res_1->product_id)?$res_1->product_id:'';
                            $code         = !empty($res_1->code)?$res_1->code:'';
                            $sales_no     = !empty($res_1->sales_no)?$res_1->sales_no:'';
                            $sales_date   = !empty($res_1->sales_date)?$res_1->sales_date:'';
                            $delar_sales  = !empty($res_1->delar_sales)?$res_1->delar_sales:'';
                            $d_sales_date = !empty($res_1->d_sales_date)?$res_1->d_sales_date:'';
                            $service_no   = !empty($res_1->service_no)?$res_1->service_no:'';
                            $service_date = !empty($res_1->service_date)?$res_1->service_date:'';

                            $qry_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE `id` = '".$cid."' AND `published` = '1'"));

                            $cat_title   = isset($qry_2->title)?$qry_2->title:'';

                            $qry_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `id` = '".$product_id."' AND `published` = '1'"));

                            $pro_title   = isset($qry_3->title)?$qry_3->title:'';
                            $description = isset($qry_3->description)?$qry_3->description:'';

                            // Sales Date
                            if($search_type == 1)
                            {
                                $invoice_no   = $sales_no;
                                $invoice_date = date('d-M-Y', strtotime($sales_date));
                            }

                            if($search_type == 2)
                            {
                                $invoice_no   = $delar_sales;
                                $invoice_date = date('d-M-Y', strtotime($d_sales_date));
                            }

                            if($search_type == 3)
                            {
                                $invoice_no   = $service_no;
                                $invoice_date = date('d-M-Y', strtotime($service_date));
                            }

                            $html .= '    
                                <tr>
                                    <th>'.$i.'</th>
                                    <th>'.$invoice_no.'</th>
                                    <th>'.$cat_title.'</th>
                                    <th>'.$pro_title.'</th>
                                    <th>'.$description.'</th>
                                    <th>'.$code.'</th>
                                    <th>'.$invoice_date.'</th>
                                </tr>
                            ';

                            $i++;
                        }
                    $html .='
                    </tbody>
                </table>';

                $excel_btn = '<button type="submit" name="export" id="form-export" class="btn btn-primary waves-effect waves-light form-export"> <i  class="fa  fa-file-excel-o"></i> Excel </button>';

                $response['status']    = TRUE;
                $response['result']    = $html;
                $response['excel_btn'] = $excel_btn;
                echo json_encode($response);
                return false;
            }
            else
            {
                $response['status']  = FALSE;
                $response['message'] = "No Records";
                echo json_encode($response);
                return;
            }
        }
        else
        {
            $response['status']  = FALSE;
            $response['message'] = "Enter Correct Value"; ;
            echo json_encode($response);
            return; 
        }
    }
}

if($_POST['method']=='_Sel_Distributors_Price')
{
    $error=FALSE;
    $required = array('distributor_id', 'category_id');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error == TRUE)
    {
        $response['status']  = FALSE;
        $response['message'] = "Please fill all required fields"; ;
        echo json_encode($response);
        return;
    }
    else
    {
        $distributor_id = isset($_POST['distributor_id'])?$_POST['distributor_id']:'';
        $category_id    = isset($_POST['category_id'])?$_POST['category_id']:'';

        $sel_1 = mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `cid` = '".$category_id."' AND `status` = '1' AND `published` = '1' ORDER BY `id` ASC ");
        $cou_1 =  mysqli_num_rows($sel_1);

        if($cou_1 > 0)
        {
            $sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE `id` = '".$category_id."' AND `published` = '1' AND `status` = '1' "));

            $category_name = isset($sel_2->title)?$sel_2->title:'';

            $html = '
                <table class="table table-bordered">
                    <tr>
                        <th colspan="5" class="text-center">'.$category_name.'</th>
                    </tr>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Product Name</th>
                        <th width="10%">Model No</th>
                        <th width="10%">MRP</th>
                        <th width="10%">NLC</th>
                    </tr>';
                    if($cou_1 > 0)
                    {
                        $i=1;
                        while($res_1 = mysqli_fetch_object($sel_1))
                        {

                            $product_id    = isset($res_1->id)?$res_1->id:'';
                            $product_title = isset($res_1->title)?$res_1->title:'';
                            $product_desc  = isset($res_1->description)?$res_1->description:'';
                            $product_price = isset($res_1->oprice)?$res_1->oprice:'';

                            $sel_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_distributor_price` WHERE `distributor_id` = '".$distributor_id."' AND `category_id` = '".$category_id."' AND `product_id` = '".$product_id."' ORDER BY `id` DESC"));

                            $product_value = isset($sel_3->nlc_value)?$sel_3->nlc_value:'0';

                            $html .= '
                                <tr>
                                    <th>'.$i.'</th>
                                    <th>'.$product_title.'</th>
                                    <th>'.$product_desc.'</th>
                                    <th>'.$product_price.'</th>
                                    <th>
                                        <input  type="text" name="nlc_value[]" id="nlc_value" class="form-control" placeholder="Enter NLC Value"  value="'.$product_value.'"> 

                                        <input  type="hidden" name="product_id[]" id="product_id" class="form-control" placeholder="Enter NLC Value"  value="'.$product_id.'"> 

                                        <input  type="hidden" name="product_price[]" id="product_price" class="form-control" placeholder="Enter NLC Value"  value="'.$product_price.'"> 

                                    </th>
                                </tr>
                            ';
                            $i++;
                        }
                    }
                $html .= '
                </table>
            ';

            $button = '<button type="submit" name="price-form" id="price-form" class="btn btn-primary waves-effect waves-light form-export"> <i  class="mdi mdi-check-circle-outline"></i> Submit </button>';

            $response['status'] = TRUE;
            $response['result'] = $html;
            $response['button'] = $button;
            echo json_encode($response);
            return false;   
        }
        else
        {
            $response['status']  = FALSE;
            $response['message'] = "No Records";
            echo json_encode($response);
            return;
        }

    }
}

if($_POST['method'] == '_Sel_Dealer')
{
    $distributor_id = !empty($_POST['distributor_id'])?$_POST['distributor_id']:'0';

    if($distributor_id != '0')
    {
        $sel_1 = mysqli_query($conn, "SELECT * FROM `ss_customers` WHERE `userid` = '".$distributor_id."' AND `published` = '1' AND `status` = '1' ");

        $cou_1 = mysqli_num_rows($sel_1);

        $option = '<option value="0">--Select Distributors Name--</option>';

        if($cou_1 > 0)
        {
            while($res_1 = mysqli_fetch_object($sel_1))
            {
                $cus_id   = isset($res_1->id)?$res_1->id:'';
                $cus_name = isset($res_1->name)?$res_1->name:'';
                $cus_shop = isset($res_1->cname)?$res_1->cname:'';

                $option .= '<option value="'.$cus_id.'">'.$cus_shop.' - '.$cus_name.'</option>';
            }

            $response['status'] = TRUE;
            $response['result'] = $option;
            echo json_encode($response);
            return false;
        }
        else
        {
            $response['status'] = TRUE;
            $response['result'] = $option;
            echo json_encode($response);
            return false;
        }
    }
}

if($_POST['method']=='_Sel_Dealer_Price')
{
    $error=FALSE;
    $required = array('distributor_id', 'dealer_id', 'category_id');
    foreach ($required as $field) 
    {
        if(empty($_POST[$field]))
        {
            $error = TRUE;
        }
    }

    if($error == TRUE)
    {
        $response['status']  = FALSE;
        $response['message'] = "Please fill all required fields"; ;
        echo json_encode($response);
        return;
    }
    else
    {
        $distributor_id = isset($_POST['distributor_id'])?$_POST['distributor_id']:'';
        $dealer_id      = isset($_POST['dealer_id'])?$_POST['dealer_id']:'';
        $category_id    = isset($_POST['category_id'])?$_POST['category_id']:'';

        $sel_1 = mysqli_query($conn, "SELECT * FROM `ss_items` WHERE `cid` = '".$category_id."' AND `status` = '1' AND `published` = '1' ORDER BY `id` ASC ");
        $cou_1 =  mysqli_num_rows($sel_1);

        if($cou_1 > 0)
        {
            $sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_category` WHERE `id` = '".$category_id."' AND `published` = '1' AND `status` = '1' "));

            $category_name = isset($sel_2->title)?$sel_2->title:'';

            $html = '
                <table class="table table-bordered">
                    <tr>
                        <th colspan="5" class="text-center">'.$category_name.'</th>
                    </tr>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Product Name</th>
                        <th width="10%">Model No</th>
                        <th width="10%">MRP</th>
                        <th width="10%">NLC</th>
                    </tr>';
                    if($cou_1 > 0)
                    {
                        $i=1;
                        while($res_1 = mysqli_fetch_object($sel_1))
                        {

                            $product_id    = isset($res_1->id)?$res_1->id:'';
                            $product_title = isset($res_1->title)?$res_1->title:'';
                            $product_desc  = isset($res_1->description)?$res_1->description:'';
                            $product_price = isset($res_1->oprice)?$res_1->oprice:'';

                            $sel_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM `ss_dealer_price` WHERE `distributor_id` = '".$distributor_id."' AND `dealer_id` = '".$dealer_id."' AND `category_id` = '".$category_id."' AND `product_id` = '".$product_id."' ORDER BY `id` DESC"));

                            $product_value = isset($sel_3->nlc_value)?$sel_3->nlc_value:'0';

                            $html .= '
                                <tr>
                                    <th>'.$i.'</th>
                                    <th>'.$product_title.'</th>
                                    <th>'.$product_desc.'</th>
                                    <th>'.$product_price.'</th>
                                    <th>
                                        <input  type="text" name="nlc_value[]" id="nlc_value" class="form-control" placeholder="Enter NLC Value"  value="'.$product_value.'"> 

                                        <input  type="hidden" name="product_id[]" id="product_id" class="form-control" placeholder="Enter NLC Value"  value="'.$product_id.'"> 

                                        <input  type="hidden" name="product_price[]" id="product_price" class="form-control" placeholder="Enter NLC Value"  value="'.$product_price.'"> 

                                    </th>
                                </tr>
                            ';
                            $i++;
                        }
                    }
                $html .= '
                </table>
            ';

            $button = '<button type="submit" name="price-form" id="price-form" class="btn btn-primary waves-effect waves-light form-export"> <i  class="mdi mdi-check-circle-outline"></i> Submit </button>';

            $response['status'] = TRUE;
            $response['result'] = $html;
            $response['button'] = $button;
            echo json_encode($response);
            return false;   
        }
        else
        {
            $response['status']  = FALSE;
            $response['message'] = "No Records";
            echo json_encode($response);
            return;
        }

    }
}

echo json_encode($response);
?>