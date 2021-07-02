<?php
// overwrite system time
date_default_timezone_set('Asia/Kolkata');
// site name
$config = array("site_name"=>"Yara");
 $conn = mysqli_connect("db5000256318.hosting-data.io","dbu326327","Multiple_@09.qwe","dbs250139");
//$conn = mysqli_connect("localhost","root","","beta_sales");
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
    $val1 = $_SESSION['admin'];
    $val2 = $_SESSION['username'];
    if(isset($val1) && isset($val2))
    {
        $fetch=mysqli_fetch_object(mysqli_query($conn, "select * from `ss_distributors` where email_id='".$_SESSION['username']."'"));
        $data = array($fetch->id,$fetch->location);
        return $data;
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

function numberTowords($number)
{
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
        }
        else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
    "." . $words[$point / 10] . " " . 
    $words[$point = $point % 10] : '';
    // return $result . "Rupees  " . $points . " Paise";
    return strtoupper($result);
}