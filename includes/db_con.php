<?php ob_start();
   session_start();  

	ini_set('session.cookie_domain', (strpos($_SERVER['HTTP_HOST'],'.') !== false) ? $_SERVER['HTTP_HOST'] : '');
	date_default_timezone_set('Asia/Kolkata');
	$date 				= new DateTime(null, new DateTimeZone('Asia/Kolkata'));
	$datetime 			= $date->format('Y-m-d H:i:s');
	$error_path 		= "images/no-image.jpg";
	if ($_SERVER['HTTP_HOST'] == "localhost" || preg_match("/^192\.168\.0.\d+$/",$_SERVER['HTTP_HOST']) || preg_match("/^prem$/",$_SERVER['HTTP_HOST']))
	{
		$server_set = 0;
		$dbname 	= "idb2017";
		$dbuser 	= "root";
		$dbpass 	= "";
		if($_SERVER['HTTP_HOST'] == "localhost")
		{
			$BaseFolder = "http://localhost/idb";		
		}
		else
		{
			$BaseFolder = "http://192.168.0.13/idb";	
		}
		
	}
	else
	{
		$server_set = 1;
		$dbname = "kumar7_idb2017";
		$dbuser = "kumar7_idb2017";
		$dbpass = "Tech@!2017";	
		$BaseFolder = "http://www.kumar7.com";	
	}
	$db_con = mysqli_connect("localhost",$dbuser, $dbpass) or die("Can not connect to Database");
	$cookie_name		= "indian_dava_cart"; // cookie will be set with this name on planet educate users when not logged in
	$min_order_value 	= 500; // this variable to set min order value for shipping charges
	$shipping_charge	= 49;
	if($db_con)
	{
		mysqli_select_db($db_con,$dbname) or die(mysqli_error($db_con));
		//$_SESSION['front_panel'] 	= "";
		$logged_uid 			= 0;
		define('BASE_FOLDER',$BaseFolder);		
	}
	
	$json 			= file_get_contents('php://input');
	$obj 			= json_decode($json);
	$response_array	= array();
?>