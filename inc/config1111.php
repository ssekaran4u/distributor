<?php
// overwrite system time
date_default_timezone_set('Asia/Kolkata');
// site name
$config = array("site_name"=>"Yara");
$conn = mysqli_connect("db5001077457.hosting-data.io","dbu1344424","Qwerty!@#45","dbs926674");
//$conn = mysqli_connect("localhost","root","","yara");
// $conn = mysqli_connect("localhost","root","","dhana_yara");
if (mysqli_connect_errno()):
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
endif;

$development = 2;
if($development == 1)
{
    // $_base = "http://localhost/project/2019/jk_v2/";
    $_base = "http://localhost/project/php/2019/sparrow/admin_2/";
    define("IPATH", '../..');
}
else
{
    define("IPATH", '../..');
    // $_base = "http://localhost/project/2019/jk_v2/";
    $_base = "http://localhost/project/php/2019/sparrow/admin_2/";
    // $_base = "http://localhost/project/2019/Kuppanna/foodpicky/";
}

function isURL()
{
    $development = 2;
    if($development == 1)
    {
        // $_base = "http://localhost/project/2019/jk_v2/";
        $_base = "http://localhost/project/php/2019/sparrow/admin_2/";
    }
    else
    {
        // $_base = "http://localhost/project/2019/jk_v2/";
        $_base = "http://localhost/project/php/2019/sparrow/admin_2/";
        // $_base = "http://localhost/project/2019/Kuppanna/foodpicky/";
    }
    define("BASE_URL", $_base);
}
// create slug    
function create_slug($string)
{
    $filters = array(
        '/[^a-zA-Z0-9\s]+/',
        '/\s+/',
        '/^-+/',
        '/-+$/'
    );
    $replacements = array('', '-', '', '');
    return strtolower(preg_replace($filters, $replacements, $string));
}

function create_clean($string) {
    $filters = array(
        '<p><br></p>',
        '<p></p>'
    );
    $replacements = array('','');
    return str_replace($filters, $replacements, $string);
}

class Input 
{
    function get($name) {
        return isset($_GET[$name]) ? $_GET[$name] : null;
    }
    function post($name) {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }
    function get_post($name) {
        return $this->get($name) ? $this->get($name) : $this->post($name);
    }
    function values($name) {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }
}

function QRIInput($data) 
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// sms api
function sendSMS($mobile, $message)
{
    // var_dump($mobile);
    // var_dump($message);die;
    $senderid   = "JKOOTY";
    $api        = "oeYg3lRAr0ibzFljufm9NA";
    $message    = urlencode($message);
    $sms_url    = "http://login.bulksmsservice.net.in/api/mt/SendSMS?apikey=".$api."&senderid=".$senderid."&channel=Trans&DCS=0&flashsms=0&number=".$mobile."&text=".$message."";
    $ch = curl_init($sms_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);      
    curl_close($ch);
}

function post_img($fileName,$tempFile,$targetFolder)
{   
    if ($fileName!="")
    {
        if(!(is_dir($targetFolder)))
        mkdir($targetFolder);
        $counter=0;
        $NewFileName=$fileName;
        if(file_exists($targetFolder."/".$NewFileName))
        {
            do
            { 
                $counter=$counter+1;
                $NewFileName=$counter."".$fileName;
            }
            while(file_exists($targetFolder."/".$NewFileName));
        }
        $NewFileName=str_replace(",","-",$NewFileName);
        $NewFileName=str_replace(" ","_",$NewFileName); 
        copy($tempFile, $targetFolder."/".$NewFileName);    
        return $NewFileName;
    }
}

function isAdmin()
{
    if(!$_SESSION['admin'] =="admin")
    {
        header("Location:index.php");
    }
}

function isAdminDetails($conn) 
{
    $type = $_SESSION['type'];
    $val1 = $_SESSION['admin'];
    $val2 = $_SESSION['username'];

    if(isset($val1) && isset($val2))
    {
        if($type == 4)
        {
            $fetch=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_service_center_user` where email_id='".$_SESSION['username']."'"));
            $data = array($fetch->id,$fetch->location);
            return $data;
        }
        else
        {
            $fetch=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_distributors` where email_id='".$_SESSION['username']."'"));
            $data = array($fetch->id,$fetch->location);
            return $data;  
        }
       
    }
}
function iGST($amount,$percentage)
{
    // Remove GST Formula
    // GST Amount = 17000 - ( 17000 * ( 100 / ( 100 + 5 ) ) )
    // Net Price = 17000 - 809.52 == 16190.48
    $step = $amount - ( $amount * ( 100 / ( 100 + $percentage ) ) );
    return round($step,2);
}

function numberTowords($num)
    {

        $ones = array(
        0 =>"ZERO",
        1 => "ONE",
        2 => "TWO",
        3 => "THREE",
        4 => "FOUR",
        5 => "FIVE",
        6 => "SIX",
        7 => "SEVEN",
        8 => "EIGHT",
        9 => "NINE",
        10 => "TEN",
        11 => "ELEVEN",
        12 => "TWELVE",
        13 => "THIRTEEN",
        14 => "FOURTEEN",
        15 => "FIFTEEN",
        16 => "SIXTEEN",
        17 => "SEVENTEEN",
        18 => "EIGHTEEN",
        19 => "NINETEEN",
        "014" => "FOURTEEN"
        );
        $tens = array( 
        0 => "ZERO",
        1 => "TEN",
        2 => "TWENTY",
        3 => "THIRTY", 
        4 => "FORTY", 
        5 => "FIFTY", 
        6 => "SIXTY", 
        7 => "SEVENTY", 
        8 => "EIGHTY", 
        9 => "NINETY" 
        ); 
        $hundreds = array( 
        "HUNDRED", 
        "THOUSAND", 
        "MILLION", 
        "BILLION", 
        "TRILLION", 
        "QUARDRILLION" 
        ); /*limit t quadrillion */
        $num = number_format($num,2,".",","); 
        $num_arr = explode(".",$num); 
        $wholenum = $num_arr[0]; 
        $decnum = $num_arr[1]; 
        $whole_arr = array_reverse(explode(",",$wholenum)); 
        krsort($whole_arr,1); 
        $rettxt = ""; 
        foreach($whole_arr as $key => $i){
            
        while(substr($i,0,1)=="0")
                $i=substr($i,1,5);
        if($i < 20){ 
        /* echo "getting:".$i; */
        $rettxt .= $ones[$i]; 
        }elseif($i < 100){ 
        if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
        if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
        }else{ 
        if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
        if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
        } 
        if($key > 0){ 
        $rettxt .= " ".$hundreds[$key]." "; 
        }
        } 
        if($decnum > 0){
        $rettxt .= " and ";
        if($decnum < 20){
        $rettxt .= $ones[$decnum];
        }elseif($decnum < 100){
        $rettxt .= $tens[substr($decnum,0,1)];
        $rettxt .= " ".$ones[substr($decnum,1,1)];
        }
    }
    return $rettxt;
}