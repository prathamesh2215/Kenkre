<?php
 
     session_start();

error_reporting(1);
ini_set('display_errors','on');
ini_set('memory_limit','-1');
date_default_timezone_set('Asia/Kolkata');

	//include("PHPMailer/class.phpmailer.php");		

$date 		= new DateTime(null, new DateTimeZone('Asia/Kolkata'));
$datetime 	= $date->format('Y-m-d H:i:s');

$theme_name = "theme-orange";
if ($_SERVER['HTTP_HOST'] == "localhost" || preg_match("/^192\.168\.0.\d+$/",$_SERVER['HTTP_HOST']) || preg_match("/^praful$/",$_SERVER['HTTP_HOST'])) 
{
	$dbname = "db_kfc";
	$dbuser = "root";
	$dbpass = "";
	if($_SERVER['HTTP_HOST'] == "localhost")
	{
		$BaseFolder = "http://localhost/kenkre/adminpanel/";		
	}
	else
	{
		$BaseFolder = "http://192.168.0.13/kenkre/adminpanel/";	
	}
	
}
else
{
   include("../PHPMailer/class.phpmailer.php");
	$dbname = "kenkresp_2018";
	$dbuser = "kenkresp_2018";
	$dbpass = "Kenkre@!2018";	
	//$BaseFolder = "http://www.planeteducate.com/edupanel/";	
	$BaseFolder = "http://kenkresports.com/adminpanel/";	
}
if (isset($_SESSION['panel_user']['email']) && strlen($_SESSION['panel_user']['email']) > 0) 
{
    $logged_uid = $_SESSION['panel_user']['id'];

}
$db_con = mysqli_connect("localhost",$dbuser, $dbpass) or die("Can not connect to Database");
if($db_con)
{
	mysqli_select_db($db_con,$dbname) or die(mysqli_error($db_con));
//	$_SESSION['backend_user'] 	= "";
	$logged_uid 			= 0;
	define('BASE_FOLDER',$BaseFolder);
		
}



$json 			= file_get_contents('php://input');
$obj 			= json_decode($json);
$response_array = array();
?>