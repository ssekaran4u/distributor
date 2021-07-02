<?php 
include 'config.php';
if($_POST['method'] == 'view')
{
   $id=$_POST['id'];
   // $result=mysqli_fetch_object(mysqli_query($conn,"SELECT *  FROM `ss_customers` where `id`='$id'"));
   $result=mysqli_fetch_object(mysqli_query($conn,"SELECT *  FROM `ss_employee` where `id`='$id'"));
   $name=isset($result->name)?$result->name:'';
   $email=isset($result->email)?$result->email:'';
   $googleID=isset($result->googleID)?$result->googleID:'';
   $facebbokID=isset($result->facebbokID)?$result->facebbokID:'';
   $address=isset($result->address)?$result->address:'';
   $state=isset($result->state)?$result->state:'';
   $city=isset($result->city)?$result->city:'';
   $landmark=isset($result->landmark)?$result->landmark:'';
   $pincode=isset($result->pincode)?$result->pincode:'';
   $mobile=isset($result->mobile)?$result->mobile:'';
   $d_id=isset($result->d_id)?$result->d_id:'';
   $phone=isset($result->phone)?$result->phone:'';
   $order=mysqli_fetch_object(mysqli_query($conn,"SELECT COUNT(*) as count  FROM `ss_payments` where `uid`='$id'"));
   $distri=mysqli_fetch_object(mysqli_query($conn,"SELECT name AS shop_name  FROM `ss_distributors` where `id`='$d_id'"));
   $count=isset($order->count)?$order->count:'0';
   $html ='';
   $html .='<div class="text-left">
              <p><strong>Name :</strong> '.$name.'</p>
              <p><strong>Email :</strong> '.$email.'</p>
              <p><strong>Address :</strong> '.$address.' </p>
              <p><strong>Shop Name :</strong> '.$distri->shop_name.' </p>
              <p><strong>Pincode :</strong> '.$pincode.' </p>
              <p><strong>Mobile :</strong> '.$mobile.' </p>
            </div';
   $response['status'] ='true';
   $response['message']=$html;
   echo json_encode($response);
}
 
  ?>
  