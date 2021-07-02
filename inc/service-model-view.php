<?php 
include 'config.php';
if($_POST['method'] == 'view')
{
   $id=$_POST['id'];
   // $result=mysqli_fetch_object(mysqli_query($conn,"SELECT *  FROM `ss_customers` where `id`='$id'"));
   $result=mysqli_fetch_object(mysqli_query($conn,"SELECT *  FROM `ss_service_stk` where `id`='$id'"));
   $service_no=isset($result->service_no)?$result->service_no:'---';
   $return_code=isset($result->return_code)?$result->return_code:'---';
  
  $category = mysqli_fetch_object(mysqli_query($conn, "SELECT `title` FROM `ss_category` WHERE `id` = '".$result->c_id."' AND `published` = '1'"));

  $product = mysqli_fetch_object(mysqli_query($conn, "SELECT `title` FROM `ss_items` WHERE `id` = '".$result->p_id."' AND `published` = '1'"));

  $stock = mysqli_fetch_object(mysqli_query($conn, "SELECT `code` FROM `ss_item_stk` WHERE `id` = '".$result->service_code."' AND `published` = '1'"));


   $html ='';
   $html .='<div class="text-left">
              <p><strong>Service No :</strong> '.$service_no.'</p>
              <p><strong>Category :</strong> '.$category->title.'</p>
              <p><strong>Product :</strong> '.$product->title.'</p>
              <p><strong>Service Code :</strong> '.$stock->code.'</p>
              <p><strong>Return Code :</strong> '.$return_code.'</p>
            </div';
   $response['status'] ='true';
   $response['message']=$html;
   echo json_encode($response);
}
 
  ?>
  