<?php
include 'config.php';
if($_POST['method']=='update')
{
    $error = FALSE;
    $errors = array();
    $required = array('_order','reason');
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
        date_default_timezone_set('Asia/Kolkata');
        $now =  date("d-m-Y H:i:s");
        $_pop = mysqli_query($conn, "SELECT `mobile` FROM `ss_delivery_address` WHERE `bno` = '".$_POST['_order']."' ");
        $PUser = mysqli_fetch_object($_pop);
        $message = " Your Order ( ".$_POST['_order']." ) was Canceled on " .$now. ". For any queries please call 9043882000 / 9962072666. Thank your for interested with Hotel Junior Kuppanna.";
        $qry = "UPDATE `ss_payment_status` SET `status_message` = '".$_POST['reason']."' , `order_status` = 'Canceled', `_canceled` = '".$now."' WHERE `order_id` = '".$_POST['_order']."' ";
        sendSMS($PUser->mobile, $message);
        $result = mysqli_query($conn, $qry);
        if($result)
        {
            $response['status'] = TRUE;
            $response['type']   = 2;
            $response['message'] = "Successfully order updated";
        }
        else
        {
            $response['status'] = FALSE;
            $response['message'] = "Getting error try again";
        }
    }
    if($errors) 
    {
        $response['status'] = FALSE;
        $response['message'] = $errors;
    }
    echo json_encode($response);
}
?>