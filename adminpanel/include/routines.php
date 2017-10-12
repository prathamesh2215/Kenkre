<?php
include("../includes/sess.php");

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
	$dbname = "";
	$dbuser = "";
	$dbpass = "";	
	//$BaseFolder = "http://www.planeteducate.com/edupanel/";	
	$BaseFolder = "http://www.kenkresports.com/adminpanel";	
	include("../PHPMailer/class.phpmailer.php");}
$db_con = mysqli_connect("localhost",$dbuser, $dbpass) or die("Can not connect to Database");
if($db_con)
{
	mysqli_select_db($db_con,$dbname) or die(mysqli_error($db_con));
	$_SESSION['backend_user'] 	= "";
	$logged_uid 			= 0;
	define('BASE_FOLDER',$BaseFolder);
		
}
$min_order_value 	= 500; // this variable to set min order value for shipping charges
$shipping_charge	= 49;
// Done By satish 28042017//
$shipping_charge	= 0;

$json 			= file_get_contents('php://input');
$obj 			= json_decode($json);
$response_array = array();

// Done By satish //
if (isset($_SESSION['panel_user']['email']) && strlen($_SESSION['panel_user']['email']) > 0) 
{
	$logged_uid = $_SESSION['panel_user']['id'];
}
if (isset($_POST['jsubmit']) && isset($_POST['emailid']) && isset($_POST['password']) && $_POST['jsubmit'] == "SiteLogin") 
{
	$email 		= $_POST['emailid'];
	$password 	= $_POST['password'];
	
	$sql_login = "select * from tbl_cadmin_users where `email` = '".addslashes($email)."' ";
	$result_login = mysqli_query($db_con,$sql_login) or die(mysqli_error($db_con));
	$num_rows_login = mysqli_num_rows($result_login);
	if ($num_rows_login != 0) 
	{
		$row_login = mysqli_fetch_array($result_login);
		$db_password = $row_login['password'];
		$salt_value = $row_login['salt_value'];
		$user_pass = md5($password.$salt_value);
		
		
		if(strcmp($db_password,$user_pass) == 0)
		{
			$_SESSION['panel_user'] = array();		
			$_SESSION['panel_user'] = $row_login;			
		}
		else
		{
			echo 'Password you entered does not match.If problem persist contact admin to resolve your query.';			
		}
	} 
	elseif ($num_rows_login == 0) 
	{
		echo 'Email you entered does not exist.If problem persist contact admin to resolve your query.';
	}
	exit(0);
}
else
{
	$email = "";
	$password = "";	
}

/* function to get new id i.e last+1 while inserting records */
function getNewId($new_id,$table_name)
{
	global $db_con;
	$sql_get_last_reco	= " SELECT `".$new_id."` FROM `".$table_name."` ORDER BY `".$new_id."` DESC LIMIT 0,1 ";
	$res_get_last_reco 	= mysqli_query($db_con, $sql_get_last_reco) or die(mysqli_error($db_con));
	$num_get_last_reco	= mysqli_num_rows($res_get_last_reco);	
	if($num_get_last_reco == 0)
	{
		$new_id = '1';
	}
	else
	{
		$row_get_last_reco	= mysqli_fetch_array($res_get_last_reco);
		$new_id 			= $row_get_last_reco["".$new_id.""] + 1;
	}
	return $new_id;
}
/* function to get new id i.e last+1 while inserting records */
/* send email with a mail function using get_mail_headers */
function sendEmail($email,$subject,$message,$type='')
{	
	if($email != "" && $subject != "" || $message !="")
	{
		$mail 				= new PHPMailer;			
		$mail->IsSMTP();                           
		$mail->Port       	= 25;                    
		$mail->Host       	= 'mail.kumar7.com'; 
		$mail->Username   	= 'support@kumar7.com';
		$mail->Password   	= 'planetTEST1199';           
		$mail->From     	= 'support@kumar7.com';
		$mail->FromName 	= 'Planet Educate';
		$mail->AddAddress($email,$email);
		$mail->AddReplyTo('test@kumar7.com', 'Planet Educate');
		$mail->WordWrap 	= 50;                             
		$mail->IsHTML(true);                              
		$mail->Subject  	= $subject;
		$body 				= $message;
		$mail->Body			= $body;	
						 
		if(!$mail->Send())
		{ 
			return $mail->ErrorInfo;//false;
		}  
		else
		{       insertEmailSmsEntryIntoNotification($type,$message,$email,"");
			return true;
		}				
	}
	else
	{
		return false;
	}
}
/* send email with a mail function using get_mail_headers */
////////////////////////////////////////////////////////////////////////////////////
///////////////--- DONE BY satish 09022017---------//////////////////////////////////
function insertEmailSmsEntryIntoNotification($type, $message, $email, $mobile_num)
{
	global $db_con, $datetime;
	
	// Insert Data into tbl_notification table [Email and SMS]
	$sql_insert_into_tbl_notification	= " INSERT INTO `tbl_notification`(`type`, `message`, `user_email`, ";
	$sql_insert_into_tbl_notification	.= " `user_mobile_num`, `created_date`) ";
	$sql_insert_into_tbl_notification	.= " VALUES ('".$type."', '".htmlspecialchars($message, ENT_QUOTES)."', '".$email."', ";
	$sql_insert_into_tbl_notification	.= " '".$mobile_num."', '".$datetime."') ";
	$res_insert_into_tbl_notification	= mysqli_query($db_con, $sql_insert_into_tbl_notification) or die(mysqli_error($db_con));
	
	return $res_insert_into_tbl_notification;
}
/////////----------Done By satish--------------////////////////
////////////////////////////////////////////////////////////////
function modelPopUp()
{
	?>
    <div class="modal fade" id="error_model" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body text-center" id="model_body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>
    <?php
}
/*Mail template headers and footers */
function mail_template_header()
{
	$mail_message .= '<div marginwidth="0" marginheight="0" style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" offset="0" topmargin="0" leftmargin="0">';
	$mail_message .= '<style style="" class="" type="text/css">';
	$mail_message .= 'body,html{width:100%!important}body{margin:0;padding:0}.ExternalClass,.ReadMsgBody{width:100%;background-color:#EAEAEA}@media only screen and (max-width:839px){body td[class=td-display-block-blog],body td[class="td-display-block-blog-height-100%"]{display:block!important;padding:0!important;width:100%!important}body table table{width:100%!important}body td[class=header-center-pad5]{display:block!important;width:100%!important;text-align:center!important;padding:5px 0!important}body td[class=td-pad10]{padding:10px!important}body td[class=td-pad10-center]{padding:10px!important;text-align:center!important}body table[class=table-pad20],body td[class=td-pad20]{padding:20px!important}body td[class=td-pad10-line-height30]{padding:10px!important;line-height:30px!important}body td[class=td-pad10-line-height40]{padding:10px!important;line-height:40px!important}body td[class=td-hidden]{display:none!important}body td[class=td-display-block-blog-center]{display:block!important;width:100%!important;padding:5px 0!important;text-align:center!important}body td[class="td-display-block-blog-height-100%"]{height:100%!important}body td[class=td-width20]{width:20px!important}body td[class=td-valign-middle]{vertical-align:middle!important}body table[class=table-button140]{width:140px!important}body table[class=table-button140-center]{width:140px!important;margin:auto!important}body table[class=table-button230-center]{width:230px!important;margin:auto!important}body table[class=table-button110-center]{width:110px!important;margin:auto!important}body table[class=table-button120]{width:120px!important}body table[class=table-button190]{width:190px!important}body table[class=table-button179]{width:179px!important}body table[class=table-button142]{width:142px!important}body table[class=table-button142-center]{width:142px!important;margin:auto!important}body table[class=table-button160-center]{width:160px!important;margin:auto!important}body table[class=table-button158-center]{width:158px!important;margin:auto!important}body table[class=table-button150]{width:150px!important}body table[class=table-line54]{width:54px!important}body table[class=table-line66]{width:66px!important}body table[class=table-line19]{width:19px!important}body table[class=table-line73]{width:73px!important}body table[class=blog-width580]{padding:20px 0!important;width:280px!important}body img[class=img-full]{width:100%!important;height:100%!important}}
</style>';
	/*Mail Header*/
	$mail_message .= '<table class="" data-module="Pre-Header" height="80" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table data-bgcolor="BG Color" height="80" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table height="80" width="600" align="center" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '</table></td>';
	$mail_message .= '</tr>';
	$mail_message .= '</table></td>';
	$mail_message .= '</tr>';
	$mail_message .= '</table>';
	$mail_message .= '<table class="" data-module="Header"  height="80" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table data-bgcolor="BG Color" height="80" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table data-bgcolor="BG Color 01" height="80" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table height="80" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td data-color="Logo 01" data-size="Logo 01" data-min="30" data-max="50" class="td-display-block-blog-center" style="font-weight:900; letter-spacing: -0.050em; font-size:40px; color:#5bbc2e; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" width="260" align="left">';
	$mail_message .= '<img src="http://www.kumar7.com/img/pe_logo.png" height="50" width="150">';
	$mail_message .= '</td>';
	$mail_message .= '<td class="td-display-block-blog" width="260" align="right"><!-- START button -->';
	$mail_message .= '</tr>';
	$mail_message .= '</table></td>';
	$mail_message .= '</tr>';
	$mail_message .= '</table></td>';
	$mail_message .= '</tr>';
	$mail_message .= '</table></td>';
	$mail_message .= '</tr>';
	$mail_message .= '</table>';
	/*Mail Header*/
	return $mail_message;
}
function mail_template_footer()
{
	/*Mail Footer*/
	$mail_message =	"";
	$mail_message .= '<table class="" data-module="Footer" height="100" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table height="100" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table height="100" width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff">';
	$mail_message .= '<tr>';
	$mail_message .= '<td data-color="Link" data-size="Link" data-min="8" data-max="20" class="td-pad10" style="font-weight:regular; letter-spacing: 0.000em; line-height:21px; font-size:12px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"><a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="#"> About Us  </a> | <a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="#"> FAQ </a> | <a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="#"> Terms & conditions </a> | <a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="#"> Disclaimer  </a> | <a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="#"> Privacy Policy  </a></td>';
	$mail_message .= '</tr>';
	$mail_message .= '<tr>';
	$mail_message .= '<td class="td-pad10" align="center">';
	$mail_message .= '<a href="javascript.void(0);"><img src="http://www.kumar7.com/img/footer-fb.png"></a> &nbsp; ';
	$mail_message .= '<a href="javascript.void(0);"><img src="http://www.kumar7.com/img/footer-tw.png"></a> &nbsp; ';
	$mail_message .= '<a href="javascript.void(0);"><img src="http://www.kumar7.com/img/footer-in.png"></a> &nbsp; ';
	$mail_message .= '<a href="javascript.void(0);"><img src="http://www.kumar7.com/img/footer-gl.png"></a></td>';
	$mail_message .= '</tr>';
	$mail_message .= '</table></td>';
	$mail_message .= '</tr>';
	$mail_message .= '</table></td>';
	$mail_message .= '</tr>';
	$mail_message .= '</table>';
	$mail_message .= '</div>';
	/*Mail Footer*/
	return $mail_message;
}
/*Mail template headers and footers */
// For Generating Random Salt Value
function generateRandomString($length) 
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) 
	{
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}
function randomString($query,$length)
{
	global $db_con;
	$random_string = generateRandomString($length);
	if($random_string != "")
	{
		$sql_check_string		= $query;
		$result_check_string 	= mysqli_query($db_con,$sql_check_string) or die(mysqli_error($db_con));
		$num_rows_check_string 	= mysqli_num_rows($result_check_string); 
		if($num_rows_check_string == 0)
		{
			return $random_string;
		}
		else
		{
			randomString($query,$length);
		}
	}
	else
	{
		randomString($query,$length);
	}
}
function checkFunctionalityRight($filename,$pos)
{
   	global $db_con;
	$sql_file_name 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
    
	$result_file_name 	= mysqli_query($db_con,$sql_file_name) or die(mysqli_error($db_con));
	$row_file_name		= mysqli_fetch_array($result_file_name);
	$af_id 		        = $row_file_name['af_id'];
		
	$sql_parent_menu 	= "select * from tbl_assign_rights where ar_user_owner_id = '".$_SESSION['panel_user']['id']."'";
	
	$res_parent_menu 	= mysqli_query($db_con,$sql_parent_menu);
	$row_parent_menu 	= mysqli_fetch_array($res_parent_menu);
 	$part_main			= explode("{",$row_parent_menu['ar_current_rights']);
	$ar_parent_menu  	= array();
	foreach($part_main as $part)
	{
		$part_sub_1			= explode(":",$part);
        
		if($part_sub_1[0] == $af_id)
		{
            $part	= explode(",",$part_sub_1[1]);
			if($part[$pos] == 1) // Commented By Prathamesh
			//echo $part[$pos]; 
			//if($part[$pos] != 0)
			{
				return 1;
				exit();	
			}
			else
			{
                            
				return 0;
				exit();
			}
		}
	}	
}
function chkRights($filename)
{
	global $db_con;
	$sql_file_name 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
        
	$result_file_name 	= mysqli_query($db_con,$sql_file_name) or die(mysqli_error($db_con));
	$row_file_name		= mysqli_fetch_array($result_file_name);
       
	$af_id 		        = $row_file_name['af_id'];
       
	$sql_check_auth 	= "select * from tbl_assign_rights where ar_user_owner_id = '".$_SESSION['panel_user']['id']."' and ar_current_rights like '%{".$af_id.":%' ";
//	echo $sql_check_auth;die;
        $result_chk_auth 	= mysqli_query($db_con,$sql_check_auth) or die(mysqli_error($db_con));
	$num_rows_check_auth= mysqli_num_rows($result_chk_auth);
	//echo $sql_check_auth;die;
	if($num_rows_check_auth == 1)
	{
		//$row_check_auth = mysqli_fetch_array($result_chk_auth);
		//$check_rights	= $row_check_auth['ar_current_rights'];
	}
	elseif($num_rows_check_auth != 1)
	{
		header("Location: view_dashboard.php?pag=Dashboard");
		exit(0);
	}
}
function redirectPage($page) 
{
	if ($page == "home") 
	{
		header("Location: /");
	}
	exit(0);
}
function time_stamp($ymd)
{
	$parts   = explode(' ',$ymd);
	$numdate = explode('-',$parts[0]);
	$time 	 = explode(':',$parts[1]);
	$month   = date("F", mktime(null, null, null, $numdate[1]));
	return $numdate[2]." ".substr($month,0,3)." ".$numdate[0].' ('.$time[0].':'.$time[1].':'.$time[2].')';
}
function year_month_date($ymd)
{
	$numdate = explode('-',$ymd);
	$month   = date("F", mktime(null, null, null, $numdate[1]));
	return $numdate[2]." ".substr($month,0,3)." ".$numdate[0];
}
function logoutUser()
{
	session_destroy(); 
	header("Location: ".$BaseFolder);
	exit(0);		
}
function checkuser()
{
	global $BaseFolder;
	global $db_con;
	$sql_logout = "SELECT `status` from tbl_cadmin_users WHERE email = '".$_SESSION['panel_user']['email']."' and password = '".$_SESSION['panel_user']['password']."' AND `status`='1'";
	$result_logout = mysqli_query($db_con,$sql_logout) or die(mysqli_error($db_con));
        	$num_rows_logout = mysqli_num_rows($result_logout);
	if ((!isset($_SESSION['panel_user']['email']) || strlen($_SESSION['panel_user']['email']) < 1 || !isset($_SESSION['panel_user']['password']) || strlen($_SESSION['panel_user']['password']) < 1)) 
	{
		echo "1";
		header("Location: ".$BaseFolder."");
		exit(0);
	} 
	else if($num_rows_logout == 0)
	{
		echo "2";
		logoutUser();
	}
	  
}
function errorDataDelete($error_id)
{
	global $db_con;	
	$sql_delete_error_cat = "DELETE FROM `tbl_error_data` WHERE `error_id`= '".$error_id."' ";
	$res_delete_error_cat = mysqli_query($db_con, $sql_delete_error_cat) or die(mysqli_error($db_con));				
	if($res_delete_error_cat)
	{
		$response_array = array("Success"=>"Success","resp"=>"Error Data Updated Successfully");
	}
	else
	{
		$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully, error Data not deleted");												
	}	
	return $response_array;
}
function spamcheck($field)
{
	$field	=	filter_var($field, FILTER_SANITIZE_EMAIL);
	if(filter_var($field, FILTER_VALIDATE_EMAIL))
	{
		return TRUE;
	} 
	else 
	{
		return FALSE;
	}
}
function get_mail_headers()
{
	$headers = "From: \"indiandavabazar\" <support@indiandavabazar.com>\n";
	$headers .= "Return-Path: <support@indiandavabazar.com>\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/HTML; charset=ISO-8859-1\n"; 
	return $headers;
}
function getRealIpAddr()
{
	$ip2 = '';
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip2 = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip2=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ip2=$_SERVER['REMOTE_ADDR'];
	}
	return $ip2;
}
function pagination($total_No_Record, $page, $limit, $targetpage)
{
	$adjacents = 2;
	$total_pages = $total_No_Record;
					
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;			
					
					
   /* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		//previous button
		if ($page > 1) 
		{
			$pagination.= "<a class=\"next_prev\"  href=\"".$targetpage."page=$prev\" style=\"float:left;margin-left:10px;\"><img src=\"images/left-icon.png\" /></a>&nbsp;&nbsp;"; //next_prev_new
		}
		else
		{
			//$pagination.= "<a class=\"next_prev current\">[previous]&nbsp;&nbsp;</a>";	 //next_prev_new
			
		}	
		
		//pages	
		
		//next button
		if ($page < $lastpage) 
		{
			$pagination.= "<a class=\"next_prev\"  href=\"".$targetpage."page=".$next."\" style=\"float:left;margin-left:10px;\"><img src=\"images/right-icon.png\" /></a>";  //next_prev_new
		}else{
			//$pagination.= "<a class=\"next_prev current\">[next]</a>";   //next_prev_new
			
		}
	}
	return $pagination;
}
function get_auto_next_id($table_name)
{
	global $dbname;
	global $db_con;
	$next_insert_id = 0;
	$table_schema = $dbname;
	$sql_last_insert = "select AUTO_INCREMENT from information_schema.Tables where TABLE_SCHEMA = '".$table_schema."' and TABLE_NAME='".$table_name."'";
	$result_last_insert = mysqli_query($db_con) or die(mysqli_error($db_con));	
	if (isset($result_last_insert['AUTO_INCREMENT'])) 
	{
		$next_insert_id = $result_last_insert['AUTO_INCREMENT'];
	}
	return $next_insert_id;
}
function sendSMS($mob,$data_msg)
{
	$data_msg = str_replace("<p>","",$data_msg);
	$data_msg = str_replace("</p>","",$data_msg);
	$data = '<?xml version="1.0" encoding="UTF-8"?>';
	$data .=<<<EOF
<xmlapi>
<auth>
<apikey>9422pzc28y9ud2ul3y5e</apikey>
</auth>
<sendSMS>
<to></to>
<text></text>
<msgid>0</msgid>
<sender>MYSTDY</sender>
</sendSMS>
<response>Y</response>
</xmlapi>
EOF;
	 if (preg_match("/^\d{10}$/",$mob)) 
	 {
		$data = str_replace("<to></to>","<to>".$mob."</to>",$data);
		$data = str_replace("<text></text>","<text>".$data_msg."</text>",$data);
		$url = "http://alerts.sinfini.com/api/xmlapi.php?data=".urlencode($data);
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output=curl_exec($ch);
		curl_close($ch);
		//return $output;
		return 1;
	}
	else
	{
		return 0;
	}
}
function load_session_timeout($uid)
{
	global $BaseFolder;
	if ($uid < 1) 
	{
		echo '<script>location.assign("'.$BaseFolder.'logout.php");</script>';
		exit(0);
	} 
}
function getloder()
{	
	?>
 		<div id="lodermodal"></div>
    	<div id="loderfade"></div>
	<?php
}
function headerdata($feature_name)
{
	?>
		<meta charset="utf8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<!-- Apple devices fullscreen -->
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<!-- Apple devices fullscreen -->
		<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />	
		<title><?php echo $feature_name; ?></title>
	    <!--<link rel="shortcut icon" href="img/logo.ico">-->
		<!-- Bootstrap -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Bootstrap responsive -->
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<!-- jQuery UI -->
		<link rel="stylesheet" href="css/plugins/jquery-ui/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="css/plugins/jquery-ui/smoothness/jquery.ui.theme.css">
		<!-- PageGuide -->
		<link rel="stylesheet" href="css/plugins/pageguide/pageguide.css">
	   	<!-- dataTables -->
		<link rel="stylesheet" href="css/plugins/datatable/TableTools.css">
		<!-- Fullcalendar -->
		<link rel="stylesheet" href="css/plugins/fullcalendar/fullcalendar.css">
		<link rel="stylesheet" href="css/plugins/fullcalendar/fullcalendar.print.css" media="print">
		<!-- chosen -->
		<link rel="stylesheet" href="css/plugins/chosen/chosen.css">
		<!-- select2 -->
		<link rel="stylesheet" href="css/plugins/select2/select2.css">
		<!-- icheck -->
		<link rel="stylesheet" href="css/plugins/icheck/all.css">	
		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/style.css">
		<!-- Color CSS -->
		<link rel="stylesheet" href="css/themes.css">		
	    <!-- this will include main css file -->
	    <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">
	    <!-- this will include main css file -->      
		<script src="js/jquery-1.8.3.js"></script>
	 	<!-- jQuery -->
		<!-- Nice Scroll -->
		<script src="js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
		<!-- jQuery UI -->
		<script src="js/plugins/jquery-ui/jquery.ui.core.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.widget.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.mouse.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.draggable.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.resizable.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.sortable.min.js"></script>	
		<!-- Touch enable for jquery UI -->
		<script src="js/plugins/touch-punch/jquery.touch-punch.min.js"></script>
		<!-- slimScroll -->
		<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<!-- Bootstrap -->
		<script src="js/bootstrap.min.js"></script>
    	<!-- dataTables -->
		<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
		<script src="js/plugins/datatable/TableTools.min.js"></script>
		<script src="js/plugins/datatable/ColReorder.min.js"></script>
		<script src="js/plugins/datatable/ColVis.min.js"></script>
		<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
		<!-- vmap -->
		<script src="js/plugins/vmap/jquery.vmap.min.js"></script>
		<script src="js/plugins/vmap/jquery.vmap.world.js"></script>
		<script src="js/plugins/vmap/jquery.vmap.sampledata.js"></script>
		<!-- Bootbox -->
		<script src="js/plugins/bootbox/jquery.bootbox.js"></script>
		<!-- Flot -->
		<script src="js/plugins/flot/jquery.flot.min.js"></script>
		<script src="js/plugins/flot/jquery.flot.bar.order.min.js"></script>
		<script src="js/plugins/flot/jquery.flot.pie.min.js"></script>
		<script src="js/plugins/flot/jquery.flot.resize.min.js"></script>
		<!-- imagesLoaded -->
		<script src="js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>
		<!-- PageGuide -->
		<script src="js/plugins/pageguide/jquery.pageguide.js"></script>
		<!-- FullCalendar -->
		<script src="js/plugins/fullcalendar/fullcalendar.min.js"></script>
		<!-- Chosen -->
		<script src="js/plugins/chosen/chosen.jquery.min.js"></script>
		<!-- select2 -->
		<script src="js/plugins/select2/select2.min.js"></script>
		<!-- icheck -->
		<script src="js/plugins/icheck/jquery.icheck.min.js"></script>
		<!-- Validation -->
		<script src="js/plugins/validation/jquery.validate.min.js"></script>
		<script src="js/plugins/validation/additional-methods.min.js"></script>
		<!-- Theme framework -->
		<script src="js/eakroko.min.js"></script>
		<!-- Theme scripts -->
		<script src="js/application.min.js"></script>
		<!-- Just for demonstration -->
		<script src="js/demonstration.min.js"></script>
	    <script src="js/jquery-ui.js"></script>
		<!-- for date picker and validation-->
		 <!-- for date picker and validation-->
    	<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>	
		<script type="text/javascript" src="js/ckeditor/plugins/ckeditor_wiris/plugin.js"></script>
    	<link rel="stylesheet" href="css/jquery-ui.css" />
		<!-- Favicon -->
		<link rel="shortcut icon" href="img/favicon.ico" />
		<!-- Apple devices Homescreen icon -->
		<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png" />
		<!-- This is for multiple dropdown list with checkbox of page tagging -->
   		<link href="css/sumoselect.css" type="text/css" rel="stylesheet" />
    	<script src="js/jquery.sumoselect.js"></script>
		<!-- This is for multiple dropdown list with checkbox of page tagging -->
        <script type="text/javascript">
			$(document).ready(function () 
			{
				$("#selectall").click(function()
				{
					$(".case").attr("checked",this.checked);
				});		
				$(".case").click(function()
				{
					if($(".case").length==$(".case:checked").length)
					{
						$("#selectall").attr("checked","checked");
					}
					else
					{
						$("#selectall").removeAttr("checked");
					}
				});					
    			var charReg = /^\s*[a-zA-Z,\s]+\s*$/;
    			$('.keyup-char').keyup(function () 
				{
        			$('warning-char').hide();
        			var inputVal = $(this).val();
	        		if (!charReg.test(inputVal)) 
					{
            			$(this).parent().find(".warning-char").show();
						$('.keyup-char').val("");
        			} 
					else 
					{
            			$(this).parent().find(".warning-char").hide();
        			}
    			});
    			var numberReg = /^\s*[0-9,\s]+\s*$/;
    			$('.keyup-number').keyup(function () 
				{
        			$('warning-number').hide();
        			var inputVal = $(this).val();
	        		if (!numberReg.test(inputVal)) 
					{
            			$(this).parent().find(".warning-number").show();
						$('.keyup-number').val("");
        			} 
					else 
					{
            			$(this).parent().find(".warning-number").hide();
        			}
    			});				
			});
			$(function()
			{
  				$('ul.tabs li:first').addClass('active');
  				$('.block article').hide();
  				$('.block article:first').show();
  				$('ul.tabs li').on('click',function()
				{
	    			$('ul.tabs li').removeClass('active');
	    			$(this).addClass('active')
	    			$('.block article').hide();
	    			var activeTab = $(this).find('a').attr('href');
	    			$(activeTab).show();
	    			return false;
  				});
			})
		    $(window).load(function() 
			{
    	    	$(".loader").fadeOut("slow");
    		})
    		$(function() 
			{
        		$(".preload").fadeOut(2000, function() 
				{
            		$(".content").fadeIn(1000);        
        		});
    		});
			function loading_show()
			{
				document.getElementById('lodermodal').style.display = 'block';
				document.getElementById('loderfade').style.display = 'block';
			}
			function loading_hide()
			{
				document.getElementById('lodermodal').style.display = 'none';
				document.getElementById('loderfade').style.display = 'none';
			}
			function childCheckUncheck(parentId,childClass)
			{
				if($('#'+parentId).attr('checked')) 
				{
					//alert(parentId+":"+childClass);
					$("."+childClass).prop("checked",true);
				} 
				else 
				{
					//alert(parentId+":"+childClass);					
					$("."+childClass).prop("checked",false);
				}				
			}			
			function toggleMyDiv(chevron_id,div_id)
			{
				if($("#"+chevron_id).is('.icon-chevron-up'))
				{
				   $("#"+chevron_id).addClass('icon-chevron-down').removeClass('icon-chevron-up');
				   $("#"+div_id).slideUp();
				}		
				else if($("#"+chevron_id).is('.icon-chevron-down'))
				{				
					$("#"+chevron_id).addClass('icon-chevron-up').removeClass('icon-chevron-down');
				   $("#"+div_id).slideDown();				
				}
			}		
			function getCity(state_id,city_select_id)
			{
			if((state_id != "") || (typeof state_id != "undefined"))
			{				
				$.ajax({
					url: "include/routines.php",
					type: "POST",
					data: "state_id="+state_id+"&change_city=1",	
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success")				
						{
							$("#"+city_select_id).html(data.resp);					
						}
						if(data.Success == "fail")		
						{
							$("#"+city_select_id).html(data.resp);
						}
					},
					error: function (error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">Error Occured :(</span>');
						$('#error_model').modal('toggle');							
					}
				});			
			}
			else
			{
				alert("State id not available");	
			}
		}
			function ValidateMobile(mobileid) //This is for validating mobile number
			{
			var mobilenum = $("#"+mobileid).val();
			var firstdigit = mobilenum.charAt(0);
		
			if(firstdigit==0)
			{
				$("#"+mobileid).attr('maxlength','11');
			}
			else
			{
				$("#"+mobileid).attr('maxlength','10');
			}
		}
			function isNumberKey(evt) //This is for only numeric value
			{
			var charCode = (evt.which) ? evt.which : event.keyCode
			
			if (charCode > 31 && (charCode < 48 || charCode > 57))
			{
				return false;
			}
			return true;
		}	
		
			function closeMe(myId)
			{
			$("#"+myId).slideUp();
		}	
			function openMe(myId)
			{
			$("#"+myId).slideDown();
		}
			function divSwap(open_div,closeDiv)
			{
			$("#"+closeDiv).slideUp();
			$("#"+open_div).slideDown();
		}				
			function productDiscount(dis_btn,fun_for)
			{
				loading_show();
				var sent_id			= parseInt(dis_btn);
				dis_name			= sent_id+"discount";
				var discount_type 	= $('input[name="'+dis_name+'"]:checked').val();
				var discount_value	= $("#"+sent_id+"discount_value").val();
				//alert(discount_value);
				if(typeof discount_type == "undefined")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please select type of Discount.</span>');							
					$('#error_model').modal('toggle');						
					loading_hide();	
					return false;			
				}
				if(discount_value == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please enter discount value.</span>');							
					$('#error_model').modal('toggle');						
					loading_hide();	
					return false;			
				}
				else
				{   discount_value	= $.trim(parseInt($("#"+sent_id+"discount_value").val()));
					var sendInfo 		= {"discount_value":discount_value, "sent_id":sent_id, "discount_type":discount_type,"fun_for":fun_for, "apply_product_discount":1};
					var discount_data 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_products.php",
						type: "POST",
						data: discount_data,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{	
								loading_hide();																						
								if(fun_for == 1)
								{
									loadData();
								}
								else if(fun_for == 2)
								{
									loadProducts();
								}
								else if(fun_for == 3)
								{
									loadData();
								}
								else if(fun_for == 4)
								{
									loadData();
								}
							} 
							else 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								loading_hide();						
							}
						},
						error: function (request, status, error) 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();							
						},
						complete: function()
						{
						}
					});					
				}				
			}
			
			
			//===========Start : For Go to page Select Box 16052017 by satish====================//
			
			function get_option(page_no_prod,no_of_product_page)
			{
				var k=1;
				var l=100;
				if(page_no_prod < 100)
				{
					 k=1
					 l=parseInt(page_no_prod)+100;
				}
				else
				{
					k=page_no_prod-100;
					l=parseInt(page_no_prod)+100;
				}
				if(l>no_of_product_page)
				{
					l =parseInt(no_of_product_page);
				}
			
			
				$('#go_to_page').html('<option value="">Select Page</option');
				for(var i=k;i<=l;i++)
				{
					  var opt ='<option value="'+i+'" ';
					  if(i==parseInt(page_no_prod))
					  {
						   opt =opt + ' selected ';
					  }
					  opt =opt + ' >'+i+'</option>';
					  $('#go_to_page').append(opt);
				}
			}
			
			function go_to_page(val,input_id)
		    {
			
				$("#"+input_id).val(val);
				loadProducts();	
		}
			
			//===========End : For Go to page Select Box 16052017 by satish====================//
		</script>
		<style>
.wrapper {
  background: white;
  margin: auto;
  padding: 0.5em;
  width: 100%;
}
h1 {
  text-align: center;
}
ul.tabs {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
ul.tabs li {
  border: gray solid 1px;
  border-bottom: none;
  float: left;
  margin: 0 .25em 0 0;
  padding: .25em .5em;
}
ul.tabs li a {
  color: gray;
  font-weight: bold;
  text-decoration: none;
}
ul.tabs li.active {
  background: #1C93A0;
}
ul.tabs li.active a {
  color: white;
}
.clr {
  clear: both;
}
article {
  border-top: gray solid 1px;
  padding: 0 1em;
}
</style>    
    <?php
}

/* Generate Slug */
function getSlug($name)
{
	//	1.	(  = Remove and space before ( will be  -
	$name_1 = str_replace("(","-",$name);
	//	2.	)  = Remove and space after ) will be -
	$name_2 = str_replace(")","-",$name_1);
	//	3.	*  = Remove
	$name_3 = str_replace("*","",$name_2);	
	//	4.	"  = Remove and space before " will be -
	$name_4 = str_replace('"','-',$name_3);	
	//	5.	,  = Remove and space after , will be -
	$name_5 = str_replace(',','-',$name_4);		
	//	6.	.  = Remove
	$name_6 = str_replace(".","",$name_5);
	//	7.	$  = Don't Remove
	//	8.	#  = Don't Remove
	//	9.	@ = Removed (Done)
	//	10.	!  = Remove and space after ! will be -
	$name_7 = str_replace("!","-",$name_6);	
	//	11.	)( = Remove and keep -
	$name_8 = str_replace(")(","-",$name_7);
	// lower case all string
	$name_9	= strtolower($name_8);
	// replace all whitespace
	$name_10 = str_replace(" ","-",$name_9);	
	// replace & with and
	$name_11 = str_replace("&","and",$name_10);
	// replace + with -
	$name_12 = str_replace("+","-",$name_11);
	// remove :
	$name_13 = str_replace(":","",$name_12);
	// remove ;	
	$name_14 = str_replace(";","",$name_13);		
	// remove '
	$name_15 = str_replace("'","",$name_14);	
	// rtrim
	$name_16 = rtrim($name_15, '-');
	// ltrim	
	$name_17 = ltrim($name_16, '-');
	// replace / with -
	$name_18 = str_replace("/","-",$name_17);	
	// replace ? with -	
	$name_19 = str_replace("?","-",$name_18);		
	// replace | with -	
	$name_20 = str_replace("|","-",$name_19);			
	// replace | with -	
	$name_21 = str_replace("|","-",$name_20);
	// replace < with -	
	$name_22 = str_replace("<","-",$name_21);				
	// replace > with -		
	$name_23 = str_replace(">","-",$name_22);					
	// replace [ with -
	$name_24 = str_replace("[","-",$name_23);					
	// replace ] with -		
	$name_25 = str_replace("]","-",$name_24);					
	// replace { with -
	$name_26 = str_replace("{","-",$name_25);
	// replace } with -		
	$name_27 = str_replace("}","-",$name_26);					
	// replace % with -
	$name_28 = str_replace("%","-",$name_27);					
	// replace ` with -		
	$name_29 = str_replace("~","-",$name_28);					
	// replace -- with -	
	$name_30 = str_replace("--","-",$name_29);
	// replace --- with -	
	$name_31 = str_replace("---","-",$name_30);
	// replace whitespace
	$name_32 = preg_replace('/\s+/', '',$name_31);	
	$name_33 = str_replace(" ","-",$name_32);
	return $name_33;
}
/* Generate Slug*/

function navigation_menu()
{
	?>
    	<div id="navigation">
			<div class="container-fluid" >
				<a href="view_dashboard.php?pag=Dashboard" id="brand">Kenkre Logo<!--<img src="img/logo.png" style="height:40px;">--></a>
				<!-- main menu -->
            	<?php 
					$filepath = "include/admin_menu.php";
					include($filepath);
				?>
            	<!-- main menu -->
			</div>
		</div>	
	<?php
}
function breadcrumbs($home_url,$home_name,$title,$filename,$feature_name)
{
	global $db_con;
	$sql_add_feature = "select * from tbl_admin_features where af_parent_type = '".$title."' and af_name = 'Add ".$feature_name."' and af_status = 1";
	$result_add_feature = mysqli_query($db_con,$sql_add_feature) or die(mysqli_error($db));
	$num_rows_add_feature = mysqli_num_rows($result_add_feature);
	if($num_rows_add_feature != 0)
	{
	}
	?>
		<div class="page-header">
			<div class="pull-left">
				<h1><?php print $feature_name ?></h1>
			</div>
			<?php 
				date_default_timezone_set("Asia/Calcutta");
				$dt=date('F d, Y');
				$week=date('l');
			?>
			<div class="pull-right" style="margin-left:5px;">
				<ul class="stats">
					<li class='lightred'>
						<i class="icon-calendar"></i>
						<div class="details">
							<span class="big"><?php echo $dt; ?></span>
							<span><?php echo $week; ?></span>
						</div>
					</li>
				</ul>
			</div>
		</div> <!-- date BOX on right side-->
		<div class="breadcrumbs">
			<ul>
				<li>
					<a href="<?php echo $home_url; ?>"><?php echo $home_name; ?></a>
					<i class="icon-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $filename; ?>?pag=<?php echo $feature_name; ?>"><?php echo $feature_name; ?></a>
					<i class="icon-angle-right"></i>
				</li>
				<li>
					<a href="#"><?php echo $title; ?></a>
				</li>
			</ul>
			<?php /*?><div class="close-bread">
				<a href="#"><i class="icon-remove"></i></a>
			</div><?php */?>
		</div> <!--breadcrumb-->
	<?php
}
function dataPagination($query,$per_page,$start,$cur_page)
{
	global $db_con;
	
	$start_offset1  	= 1;	// Start Point
	$start_offset2  	= 1;	// End of the Limit
	$previous_btn 		= true;
	$next_btn 			= true;
	$first_btn 			= true;
	$last_btn 			= true;
	$msg 				= "";
	$result_pag_num 	= mysqli_query($db_con,$query) or die(mysqli_error($db_con));;
	$record_count		= mysqli_num_rows($result_pag_num);	// Total Count of the Record
	$no_of_paginations 	= ceil($record_count / $per_page);	// Getting the total number of pages
	
	/*Edit Count Query*/
	/* ---------------Calculating the starting and endign values for the loop----------------------------------- */
	
	if($cur_page >= 7) 
	{
		$start_loop = $cur_page - 3;
		if ($no_of_paginations > $cur_page + 3)
		{
			$end_loop = $cur_page + 3;			
		}
		elseif($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) 
		{
			$start_loop = $no_of_paginations - 6;
			$end_loop = $no_of_paginations;
		}
		else
		{
			$end_loop = $no_of_paginations;
		}
	} 
	else 
	{		
		$start_loop = 1;
		if ($no_of_paginations > 7)
		{
			$end_loop = 7;			
		}
		else
		{
			$end_loop = $no_of_paginations;			
		}
	}
	/* ----------------------------------------------------------------------------------------------------------- */
	$msg .= "<br><div class='pagination'><ul style='margin-right:20px'>";
	// FOR ENABLING THE FIRST BUTTON
	if ($first_btn && $cur_page > 1) 
	{
		$msg .= "<li p='1' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>First</li>";
	} 
	else if ($first_btn) 
	{
		$msg .= "<li p='1' class='inactive'  style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>First</li>";
	}
	// FOR ENABLING THE PREVIOUS BUTTON
	if ($previous_btn && $cur_page > 1) 
	{
		$pre = $cur_page - 1;
		$msg .= "<li p='$pre' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Previous</li>";
	} 
	else if ($previous_btn) 
	{
		$msg .= "<li class='inactive' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Previous</li>";
	}
	
	for ($i = $start_loop; $i <= $end_loop; $i++) 
	{
		if ($cur_page == $i)
			$msg .= "<li p='$i' value='$i' name='li_current' style='background: none repeat scroll 0 0 #F8A31F;color: #ffffff;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none' class='active selected'>{$i}</li>";
		else
			$msg .= "<li p='$i' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>{$i}</li>";
	}
	
	// TO ENABLE THE NEXT BUTTON
	if ($next_btn && $cur_page < $no_of_paginations) 
	{
		$nex = $cur_page + 1;
		$msg .= "<li p='$nex' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Next</li>";
	} 
	else if ($next_btn) 
	{
		$msg .= "<li class='inactive' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Next</li>";
	}
	// TO ENABLE THE END BUTTON
	if ($last_btn && $cur_page < $no_of_paginations) 
	{
		$msg .= "<li p='$no_of_paginations' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Last</li>";
	} 
	else if ($last_btn) 
	{
		$msg .= "<li p='$no_of_paginations' class='inactive' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Last</li>";
	}
	$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;width:25px !important;'/>";
	$goto .= "<input type='button' id='go_btn' class='go_button' value='Go'/>";
	
	$start_offset1 = $cur_page * $per_page + 1 - $per_page;
	if($end_loop == $cur_page)
	{
		$start_offset2 = $record_count;
	}
	else
	{
		$start_offset2 = $cur_page * $per_page;
	}
	
	// $total_string [Is the actual string i.e. 1 to 20 of 4000 entries only, there is no any pagination include in it]
	$total_string = "</ul>";
	$total_string .= "<div class='total' a='$no_of_paginations' style='color:#333333;font-family: Open Sans,sans-serif;font-size: 13px !important;'>Showing <b>".$start_offset1."</b> to <b>$start_offset2</b> of <b>$record_count</b> entries</div>";
	
	// $msg	[Is the actual string that contain the pagination part first-prev-1-2-3-4-5-6-7-next-last only]
	
	$msg1 = $msg . $total_string ;  // Content for pagination
	if(!$record_count=='0')
	{
		return $msg1;
	}
	else
	{
		return 0;	
	}
}
function getSMSCredits()
{
	$url = "http://alerts.sinfini.com/api/v3/index.php?method=account.credits&api_key=9422pzc28y9ud2ul3y5e";
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output=curl_exec($ch);
	curl_close($ch);
	$output1 = json_decode($output,true);
	//print_r($output1);	
	//return($output1['data']['credits']);
}
if(isset($_POST['change_city']) == "1" && isset($_POST['change_city']))
{
	$state_id	  	= $_POST['state_id'];
	$response_array = array();	
	$data 			= '<option value="">Select City</option>';		
	$sql_get_city 	= " SELECT DISTINCT `city_id`, `city_name` FROM `city` where state_id = '".$state_id."' ";
	$res_get_city 	= mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));	
	if($res_get_city)
	{	
		while($row_get_city = mysqli_fetch_array($res_get_city))
		{
			$data 	.= '<option value="'.$row_get_city['city_id'].'">'.ucwords($row_get_city['city_name']).'</option>';			
		}	
		$response_array = array("Success"=>"Success","resp"=>$data);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>$data);
	}	
	echo json_encode($response_array);
}
function branch($db_con)
{
	?>
    	<script>
		function getBranch(org_id,branch_select_id)
		{
			if((org_id != "") || (typeof org_id != "undefined"))
			{				
				loading_show();
				$.ajax({
					url: "include/routines.php",
					type: "POST",
					data: "org_id="+org_id+"&change_branch=1",	
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success")				
						{
							$("#"+branch_select_id).html(data.resp);	
							loading_hide();
						}
						if(data.Success == "fail")		
						{
							$("#"+branch_select_id).html(data.resp);
							loading_hide();							
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//alert("complete");
					}
				});			
			}
			else
			{
				$("#model_body").html('<span style="style="color:#F00;">Organisation not available</span>');
				$('#error_model').modal('toggle');	
				loading_hide();		
			}
		}		
		</script>
    <?php	
}
function subCategory()
{
	?>
    	<script>
		function getSubCategory(cat_id,cat_select_id)
		{
			if((cat_id != "") || (typeof cat_id != "undefined"))
			{				
				loading_show();
				$.ajax({
					url: "include/routines.php",
					type: "POST",
					data: "cat_id="+cat_id+"&change_cat=1",	
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success")				
						{
							$("#"+cat_select_id).html(data.resp);	
							loading_hide();
						}
						if(data.Success == "fail")		
						{
							$("#"+cat_select_id).html(data.resp);
							loading_hide();							
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//alert("complete");
					}
				});			
			}
			else
			{
				$("#model_body").html('<span style="style="color:#F00;">Category not available</span>');
				$('#error_model').modal('toggle');	
				loading_hide();		
			}
		}		
		</script>
    <?php	
}
if(isset($_POST['change_branch']) == "1" && isset($_POST['change_branch']))
{
	$branch_orgid	  	= mysqli_real_escape_string($db_con,$_POST['org_id']);
	$response_array 	= array();	
	$data 				= '<option value="">Select Branch</option>';		
	$sql_get_branch 	= "SELECT distinct branch_id,branch_name FROM `tbl_branch_master` where branch_orgid = '".$branch_orgid."' AND branch_status = '1' ";
	$result_get_branch 	= mysqli_query($db_con,$sql_get_branch) or die(mysqli_error($db_con));	
	$num_rows_get_branch= mysqli_num_rows($result_get_branch);		
	if($num_rows_get_branch != 0)											
	{
		while($row_get_branch = mysqli_fetch_array($result_get_branch))
		{															
			$data		.= '<option value="'.$row_get_branch['branch_id'].'">'.$row_get_branch['branch_name'].'</option>';
		}
		$response_array = array("Success"=>"Success","resp"=>$data);		
	}	
	else
	{
		$response_array = array("Success"=>"Success","resp"=>$data);
	}	
	echo json_encode($response_array);	
}
if(isset($_POST['change_cat']) == "1" && isset($_POST['change_cat']))
{
	$cat_type	  	= mysqli_real_escape_string($db_con,$_POST['cat_id']);
	$response_array 	= array();	
	$data 				= '<option value="">Select Sub Category</option>';		
	$sql_get_cat 	= "SELECT distinct cat_id,cat_name FROM `tbl_category` where cat_type = '".$cat_type."' AND cat_status = '1' ";
	$result_get_cat 	= mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));	
	$num_rows_get_cat= mysqli_num_rows($result_get_cat);		
	if($num_rows_get_cat != 0)											
	{
		while($row_get_cat = mysqli_fetch_array($result_get_cat))
		{															
			$data		.= '<option value="'.$row_get_cat['cat_id'].'">'.$row_get_cat['cat_name'].'</option>';
		}
		$response_array = array("Success"=>"Success","resp"=>$data);		
	}	
	else
	{
		$response_array = array("Success"=>"Success","resp"=>$data);
	}	
	echo json_encode($response_array);	
}
function getExtension($str) 
{
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = strtolower(substr($str,$i+1,$l));
	return $ext;
}
function make_thumb($img_name,$filename,$new_w,$new_h)
{
//	$img_name="Desert.jpg";
	//get image extension.
	$ext=getExtension($img_name);
	
	
	//creates the new image using the appropriate function from gd library
	if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))
	{
		$t = "jpeg";
		$src_img = imagecreatefromjpeg($img_name);
	}
	
	if(!strcmp("png",$ext))
	{
		$t = "png";
		$src_img=imagecreatefrompng($img_name);
	}
		
	if(!strcmp("gif",$ext))
	{
		$t = "gif";
		$src_img=imagecreatefromgif($img_name);
	}
	
	//return $src_img;
	
	//gets the dimmensions of the image
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);

	// next we will calculate the new dimmensions for the thumbnail image

	// the next steps will be taken:

	// 1. calculate the ratio by dividing the old dimmensions with the new ones

	// 2. if the ratio for the width is higher, the width will remain the one define in WIDTH variable

	// and the height will be calculated so the image ratio will not change

	// 3. otherwise we will use the height ratio for the image

	// as a result, only one of the dimmensions will be from the fixed ones

	$ratio1=$old_x/$new_w;
	$ratio2=$old_y/$new_h;
	if($ratio1>$ratio2) 
	{
		$thumb_w=$new_w;
		$thumb_h=$old_y/$ratio1;
	}
	else 
	{
		$thumb_h=$new_h;
		$thumb_w=$old_x/$ratio2;
	}

	// we create a new image with the new dimmensions
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	// resize the big image to the new created one
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	// output the created image to the file. Now we will have the thumbnail into the file named by $filename
	if(!strcmp("png",$ext))
		imagepng($dst_img,$filename);
	elseif(!strcmp("gif",$ext))
		imagegif($dst_img,$filename);
	else
		imagejpeg($dst_img,$filename);
	//destroys source and destination images.
	imagedestroy($dst_img);
	imagedestroy($src_img);
}

/////=====================Start : Added By Satish 21082017==================//

function insert($table, $variables = array() )
{
			//Make sure the array isn't empty
	global $db_con;
	if( empty( $variables ) )
	{
		return false;
		exit;
	}
	
	$sql = "INSERT INTO ". $table;
	$fields = array();
	$values = array();
	foreach( $variables as $field => $value )
	{
		$fields[] = $field;
		$values[] = "'".$value."'";
	}
	$fields = ' (' . implode(', ', $fields) . ')';
	$values = '('. implode(', ', $values) .')';
	
	$sql .= $fields .' VALUES '. $values;

	$result		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	
	if($result)
	{
		return mysqli_insert_id($db_con);
	}
	else
	{
		return false;
	}
}

function update($table, $variables = array(), $where,$not_where_array=array(),$and_like_array=array(),$or_like_array=array())
{
	//Make sure the array isn't empty
	global $db_con;
	if( empty( $variables ) )
	{
		return false;
		exit;
	}
	
	$sql = "UPDATE ". $table .' SET ';
	$fields = array();
	$values = array();
	
	foreach($variables as $field => $value )
	{   
		$sql  .= $field ."='".$value."' ,";
	}
	$sql   =chop($sql,',');
	
	$sql .=" WHERE 1 = 1 ";
	//==Check Where Condtions=====//
	if(!empty($where))
	{
		foreach($where as $field1 => $value1 )
		{   
			$sql  .= " AND ".$field1 ."='".$value1."' ";
		}
	}

	$result 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	
	if($result)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function quit($msg,$Success="")
{
	if($Success ==1)
	{
		$Success="Success";
	}
	else
	{
		$Success="fail";
	}
	echo json_encode(array("Success"=>$Success,"resp"=>$msg));
	exit();
}


// Select Query For getting the Record count
	function isExist($table ,$where, $not_where_array=array(), $and_like_array=array(), $or_like_array=array())
	{
		global $db_con;
		if($table=="")
		{
			quit('Table name can not be blank');
		}
		$sql = " SELECT * FROM ". $table ;
		$fields = array();
		$values = array();
		
		
		$sql .=" WHERE 1 = 1 ";
		
		//==Check Where Condtions=====//
		if(!empty($where))
		{
			foreach($where as $field1 => $value1 )
			{   
				$sql  .= " AND ".$field1 ."='".$value1."' ";
			}
		}
		
		//==Check Not Where Condtions=====//
		if(!empty($not_where_array))
		{
			foreach($not_where_array as $field2 => $value2)
			{   
				$sql  .= " AND ".$field2 ."!='".$value2."' ";
			}
		}
		
		$result 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		$num            = mysqli_num_rows($result);
		if($num > 0)
		{
			
			return $num;
		}
		else
		{
			return false;
		}
	}
	
	
	
	function checkExist($table ,$where, $not_where_array=array(), $and_like_array=array(), $or_like_array=array())
	{
		global $db_con;
		if($table=="")
		{
			quit('Table name can not be blank');
		}
		$sql = " SELECT * FROM ". $table ;
		$fields = array();
		$values = array();
		
		
		$sql .=" WHERE 1 = 1 ";
		
		//==Check Where Condtions=====//
		if(!empty($where))
		{
			foreach($where as $field1 => $value1 )
			{   
				$sql  .= " AND ".$field1 ."='".$value1."' ";
			}
		}
		
		//==Check Not Where Condtions=====//
		if(!empty($not_where_array))
		{
			foreach($not_where_array as $field2 => $value2)
			{   
				$sql  .= " AND ".$field2 ."!='".$value2."' ";
			}
		}
		
		$result 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		$num            = mysqli_num_rows($result);
		if($num > 0)
		{
			$row = mysqli_fetch_array($result);
			return $row;
		}
		else
		{
			return false;
		}
	}

/////=====================End : Added By Satish 21082017==================//

// ===============================================================================================================
// START : function for getting the sub categories for dropdown list [Dn By Prathamesh on 11 Sept 2017]
// ===============================================================================================================
function getSubCatValue($cat_id, $userType)	// Parameters : Parent ID and userType [i.e. Add, Edit, View]
{
	global $db_con;
	$data	= '';
	
	if($userType == 'add')
	{
		// Get The children of this Cat ID from Category Master Table
		$sql_get_children_frm_cm	= " SELECT * FROM `tbl_category` WHERE `cat_type`='".$cat_id."' AND `cat_name`!='none' AND `cat_status`='1' ";
		$res_get_children_frm_cm	= mysqli_query($db_con, $sql_get_children_frm_cm) or die(mysqli_error($db_con));
		$num_get_children_frm_cm	= mysqli_num_rows($res_get_children_frm_cm);
		
		if($num_get_children_frm_cm != 0)
		{
			while($row_get_children_frm_cm = mysqli_fetch_array($res_get_children_frm_cm))
			{
				// Get the count and the related parent ids of this category from the Category Path table
				$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` ";
				$sql_get_count_and_all_parent	.= " WHERE `cat_id`='".$row_get_children_frm_cm['cat_id']."' ";
				$sql_get_count_and_all_parent	.= " ORDER BY level ASC ";
				$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
				$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
				
				if($num_get_count_and_all_parent != 0)
				{
					$opt_value	= '';
					while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
					{
						// Find the name of the category
						$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` ";
						$sql_get_name_of_cat	.= " FROM `tbl_category` ";
						$sql_get_name_of_cat	.= " WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
						$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
						$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
						
						$parent_cat_name		= $row_get_name_of_cat['cat_name'];
						
						$opt_value	.= $parent_cat_name.' > ';
					}
					$data .= '<option id="cat'.$row_get_children_frm_cm['cat_id'].'sec'.$count.'" value="'.$row_get_children_frm_cm['cat_id'].'">'.substr(ucwords($opt_value),0,-3).'</option>';
					$data1	= getSubCatValue($row_get_children_frm_cm['cat_id'],'add');
					if($data1 != '')
					{
						$data	.= $data1;
					}
				}
			}
			return $data;	
		}
		else
		{
			return $data;	
		}
	}
	elseif($userType == 'view')
	{
		// Get the count and the related parent ids of this category from the Category Path table
		$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` ";
		$sql_get_count_and_all_parent	.= " WHERE `cat_id`='".$cat_id."' ";
		$sql_get_count_and_all_parent	.= " ORDER BY level ASC ";
		$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
		$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
		
		if($num_get_count_and_all_parent != 0)
		{
			$opt_value	= '';
			while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
			{
				// Find the name of the category
				$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` ";
				$sql_get_name_of_cat	.= " FROM `tbl_category` ";
				$sql_get_name_of_cat	.= " WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
				$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
				$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
				
				$parent_cat_name		= $row_get_name_of_cat['cat_name'];
				
				$opt_value	.= $parent_cat_name.' > ';
			}
			$data .= '<label class="control-label" >'.substr(ucwords($opt_value),0,-3).'</label>';
		}
		
		return $data;		
	}
	else
	{
		return $data;	
	}
}
// ===============================================================================================================
// END : function for getting the sub categories for dropdown list [Dn By Prathamesh on 11 Sept 2017]
// ===============================================================================================================


function getList($table,$option_val,$option_name,$selected_id='',$where_arr=array(),$not_where=array())
{
	global $db_con;
	if(!isset($table))
	{
		return 'Please provide table name';
	}
	$sql=" SELECT * FROM ".$table." WHERE 1=1 "; 
	//==Check Where Condtions=====//
	if(!empty($where_arr))
	{
		foreach($where_arr as $field => $value )
		{   
			$sql  .= " AND ".$field ."='".$value."' ";
		}
	}
	if(!empty($not_where))
	{
		foreach($not_where as $field1 => $value1 )
		{   
			$sql  .= " AND ".$field1 ."!='".$value1."' ";
		}
	}
	$sql  .= " ORDER BY ".$option_name." ASC  ";
	$res   = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	$num   = mysqli_num_rows($res);
	if($num !=0)
	{
		$data ='';
		while($row = mysqli_fetch_array($res))
		{
			$data .='<option value="'.$row[$option_val].'" ';
			if($row[$option_val]==$selected_id)
			{
				$data .=' selected="selected" ';
			}
			$data .='>'.ucwords($row[$option_name]).'</option>';
		}
		return $data;
	}
	else
	{
	return '<option value="">NO item\'s found..! </option>';
	}
}


function query($sql)
{
	global $db_con;
	return mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
}
function fetch($res)
{
	return mysqli_fetch_array($res);
}
?>