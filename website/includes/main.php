<?php include('db_con.php');
//========================================================
// START : elastic search [ init.php file ] on 07-11-2016
//========================================================
require_once 'init.php';
//========================================================
// END : elastic search [ init.php file ] on 07-11-2016
//========================================================

if(file_exists("../PHPMailer/class.phpmailer.php"))
{
	include("../PHPMailer/class.phpmailer.php");		
}
elseif(file_exists("PHPMailer/class.phpmailer.php"))
{
	include("PHPMailer/class.phpmailer.php");		
}
$json 				= file_get_contents('php://input'); // get json request
$obj 				= json_decode($json); // general object which converts json request to php object
function convert($string)
{
	$persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
	$num = range(0, 9);
	return str_replace($persian, $num, $string);
}
/* loader on front end*/
function passwordChecking()
{
	?>
    	<script type="text/javascript">
		function CheckPassword(password_field,error_span)
		{   
			var decimal=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;  
			if(password_field.value.match(decimal))   
			{    
			}  
			else  
			{   
				$("#"+error_span).html("");
			}  
		}   
		</script>
    <?php
}
/*banners inside catogries on front page */
function insideBanners($cat_name)
{
	if($cat_name == "Books")
	{
		?>
        	<div class="col-sm-12" style="margin:30px 0 0;">
            	<img src="images/banner1.jpg" style="width:100%">
            </div>
	    <?php	
	}
	elseif($cat_name == "Digital Content")
	{
		?>
        	<div class="col-sm-12" style="margin:30px 0 0;">
            	<img src="images/banner2.jpg" style="width:100%">
            </div>
	    <?php	
	}
}
/*Mail template headers and footers */
function mail_template_header()
{
	$mail_message = '<div marginwidth="0" marginheight="0" style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" offset="0" topmargin="0" leftmargin="0">';
	$mail_message .= '<style style="" class="" type="text/css">';
	$mail_message .= 'body,html{width:100%!important}body{margin:0;padding:0}.ExternalClass,.ReadMsgBody{width:100%;background-color:#EAEAEA}@media only screen and (max-width:839px){body td[class=td-display-block-blog],body td[class="td-display-block-blog-height-100%"]{display:block!important;padding:0!important;width:100%!important}body table table{width:100%!important}body td[class=header-center-pad5]{display:block!important;width:100%!important;text-align:center!important;padding:5px 0!important}body td[class=td-pad10]{padding:10px!important}body td[class=td-pad10-center]{padding:10px!important;text-align:center!important}body table[class=table-pad20],body td[class=td-pad20]{padding:20px!important}body td[class=td-pad10-line-height30]{padding:10px!important;line-height:30px!important}body td[class=td-pad10-line-height40]{padding:10px!important;line-height:40px!important}body td[class=td-hidden]{display:none!important}body td[class=td-display-block-blog-center]{display:block!important;width:100%!important;padding:5px 0!important;text-align:center!important}body td[class="td-display-block-blog-height-100%"]{height:100%!important}body td[class=td-width20]{width:20px!important}body td[class=td-valign-middle]{vertical-align:middle!important}body table[class=table-button140]{width:140px!important}body table[class=table-button140-center]{width:140px!important;margin:auto!important}body table[class=table-button230-center]{width:230px!important;margin:auto!important}body table[class=table-button110-center]{width:110px!important;margin:auto!important}body table[class=table-button120]{width:120px!important}body table[class=table-button190]{width:190px!important}body table[class=table-button179]{width:179px!important}body table[class=table-button142]{width:142px!important}body table[class=table-button142-center]{width:142px!important;margin:auto!important}body table[class=table-button160-center]{width:160px!important;margin:auto!important}body table[class=table-button158-center]{width:158px!important;margin:auto!important}body table[class=table-button150]{width:150px!important}body table[class=table-line54]{width:54px!important}body table[class=table-line66]{width:66px!important}body table[class=table-line19]{width:19px!important}body table[class=table-line73]{width:73px!important}body table[class=blog-width580]{padding:20px 0!important;width:280px!important}body img[class=img-full]{width:100%!important;height:100%!important}}
</style>';
	/*Mail Header*/
	$mail_message .= '<table class="" data-module="Pre-Header" height="80" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table data-bgcolor="BG Color" height="80" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
	$mail_message .= '<tr>';
	$mail_message .= '<td><table height="80" width="600" align="center" border="0" cellpadding="0" cellspacing="0">';
/*	$mail_message .= '<tr>';
    $mail_message .= '<td data-color="Pre-Header 01" data-size="Pre-Header 01" data-min="8" data-max="20" class="td-pad10" style="font-weight:400; letter-spacing: 0.005em; font-size:12px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> Hello from Planet Educate! If you cannot read this email, <a data-color="Pre-Header 02" data-size="Pre-Header 02" data-min="8" data-max="20" style="font-weight:bold; color:#2362c0; text-decoration:none;" href="#">click here</a></td>';
	$mail_message .= '</tr>';
*/	$mail_message .= '</table></td>';
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
	$mail_message .= '<img src="http://www.planeteducate.com/img/pe_logo.png" height="50" width="150">';
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
	$mail_message .= '<td data-color="Link" data-size="Link" data-min="8" data-max="20" class="td-pad10" style="font-weight:regular; letter-spacing: 0.000em; line-height:21px; font-size:12px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"><a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="https://www.planeteducate.com/about-us"> About Us  </a> |  <a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="https://www.planeteducate.com/terms-and-conditions"> Terms & conditions </a> | <a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="https://www.planeteducate.com/disclaimer"> Disclaimer  </a> | <a data-color="Link" data-size="Link" data-min="8" data-max="20" style="color:#494949; text-decoration:none;" href="https://www.planeteducate.com/privacy-policy"> Privacy Policy  </a></td>';
	$mail_message .= '</tr>';
	$mail_message .= '<tr>';
	$mail_message .= '<td class="td-pad10" align="center">';
	$mail_message .= '<a href="https://www.facebook.com/Planet-Educate-796488243821161/"><img src="http://www.planeteducate.com/img/footer-fb.png"></a> &nbsp; ';
	$mail_message .= '<a href="https://twitter.com/planet_educate"><img src="http://www.planeteducate.com/img/footer-tw.png"></a> &nbsp; ';
	//$mail_message .= '<a href="javascript.void(0);"><img src="http://www.planeteducate.com/img/footer-in.png"></a> &nbsp; ';
	$mail_message .= '<a href="https://plus.google.com/u/0/b/112673679462635743960/"><img src="http://www.planeteducate.com/img/footer-gl.png"></a></td>';
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

/* START : SMS Gateway [By Prathamesh on 04-10-2016] */
function send_sms_msg($mob, $data_msg)
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
<sender>PLEMKT</sender>
</sendSMS>
<response>Y</response>
</xmlapi>
EOF;

	 if (preg_match("/^\d{10}$/",$mob)) 
	 {
		//$count_sms++;
		$data = str_replace("<to></to>","<to>".$mob."</to>",$data);
		$data = str_replace("<text></text>","<text>".$data_msg."</text>",$data);
		//print sprintf("%04d",$count_sms)." => ".$mobile_num." => ".$data."<br/><hr/>";

		$url = "http://alerts.sinfini.com/api/xmlapi.php?data=".urlencode($data);
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output=curl_exec($ch);
		curl_close($ch);
		//return $output;
	}
}
/* END : SMS Gateway [By Prathamesh on 04-10-2016] */

/*banners inside catogries on front page */
/* get mail headers form server */
function get_mail_headers()
{
	/*$headers = "From: \"Planet Educate\" <test@planeteducate.com>\n";
	$headers .= "Return-Path: <test@planeteducate.com>\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/HTML; charset=ISO-8859-1\n"; */
}
/* get mail headers form server */
/* send email with a mail function using get_mail_headers */
function sendEmail($email,$subject,$message)
{	
	global $server_set;
	if($email != "" && $subject != "" || $message !="")
	{
		if($server_set == 1)
		{
			$mail 				= new PHPMailer;			
			$mail->IsSMTP();                           
			$mail->Port       	= 25;                    
			$mail->Host       	= 'mail.planeteducate.com'; 
			$mail->Username   	= 'support@planeteducate.com';
			$mail->Password   	= 'planetTEST1199';           
			$mail->From     	= 'support@planeteducate.com';
			$mail->FromName 	= 'Planet Educate';
			$mail->AddAddress($email,$email);
			$mail->AddReplyTo('support@planeteducate.com', 'Planet Educate');
			$mail->WordWrap 	= 50;                             
			$mail->IsHTML(true);                              
			$mail->Subject  	= $subject;
			$body 				= $message;
			$mail->Body			= $body;							 
			if(!$mail->Send())
			{ 
				return false;//$mail->ErrorInfo;
			}  
			else
			{
				return true;
			}				
		}
		else
		{
			return true;			
		}
	}
	else
	{
		return true;
	}}
/* send email with a mail function using get_mail_headers */
/* get client ip address */
function get_client_ip() 
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
/* get client ip address */
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
/* static banners on index page */
function banner()
{
	?>
	<div class="banner large text-center wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
      <div class="container">
          <div class="row">
             <!-- <h1 class="no-margin">PLANET EDUCATE. <span class="yellow-text">ALL YOU NEED.</span></h1>-->
              <div class="col-md-3 col-sm-1"><img src="img/banner-1.jpg" alt=""  title="" /></div>
              <div class="col-md-3 col-sm-1"><img src="img/banner-2.jpg" alt=""  title="" /></div>
              <div class="col-md-3 col-sm-1"><img src="img/banner-3.jpg" alt=""  title="" /></div>
              <div class="col-md-3 col-sm-1"><img src="img/banner-4.jpg" alt=""  title="" /></div>
          </div>
      </div>
      </div>
	<?php
}
/* static banners on index page */
// For testimonial
function testimonial()
{
	?>
		<div style="background-image: url('img/testimonial-bg.jpg')" class="testimonial with-bg-image">
                    <div class="container">
                        <div class="row">
                            <div class="testimonial-wrap text-center wow fadeIn" style="visibility: hidden; animation-name: none;">
                                <div class="testimonial-item flexslider">
                                    
                                <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 800%; transition-duration: 0s; transform: translate3d(-1170px, 0px, 0px);">
                                        <li style="width: 1170px; float: left; display: block;" class="flex-active-slide">
                                            <div class="review">
                                                <p class="text">Single destination platform for all your education needs as a Student, Parent, Teacher, Institution, Education Business or Corporation.</p>
                                              
                                            </div>
                                        </li>
                                        </ul></div><ol class="flex-control-nav flex-control-paging"><li><a class="flex-active">1</a></li><li><a class="">2</a></li></ol></div>
                            </div>
                        </div>
                    </div>
                    <div class="overlay dark"></div>
                </div>
	<?php
}

function otp_futureDate()
{
	global $datetime;	
	
	$otp_date = $datetime;
	$otp_currentDate = strtotime($otp_date);
	$otp_futureDate = $otp_currentDate+(60*3);
	$_SESSION['otp_validation']	= date("Y/m/d H:i:s", $otp_futureDate);		
}

/* every time user login or register this will add information of user to the login info page*/
function custLoginInfo($cust_id,$cust_email,$cli_browser_info,$cli_ip_address)
{
	global $db_con,$datetime;	
	if($cust_id == "" || $cust_email == "" || $cli_browser_info == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Customer details empty");		
	}
	else
	{
		//=======================================================================================
		// START : Code DN by Prathamesh on 07-10-2016 [Insert all fileds into the Session]
		//=======================================================================================
		
		$sql_get_cust_info	= " SELECT * FROM `tbl_customer` WHERE `cust_email` like '".$cust_email."' ";
		$res_get_cust_info	= mysqli_query($db_con, $sql_get_cust_info) or die(mysqli_error($db_con));
		$row_get_cust_info	= mysqli_fetch_array($res_get_cust_info);
		
		$_SESSION['front_panel']	= $row_get_cust_info;
		
		//=======================================================================================
		// END : Code DN by Prathamesh on 07-10-2016 [Insert all fileds into the Session]
		//=======================================================================================		
		
		if($cli_ip_address == "")
		{
			$cli_ip_address = get_client_ip();
		}		
		$sql_get_last_reco	= " SELECT `cli_id` FROM `tbl_customer_login_info` ORDER BY `cli_id` DESC LIMIT 0,1 ";
		$res_get_last_reco 	= mysqli_query($db_con, $sql_get_last_reco) or die(mysqli_error($db_con));
		$num_get_last_reco	= mysqli_num_rows($res_get_last_reco);
		
		if($num_get_last_reco == 0)
		{
			$cli_id = '1';
		}
		else
		{
			$row_get_last_reco	= mysqli_fetch_array($res_get_last_reco);
			$cli_id 			= $row_get_last_reco['cli_id'] + 1;
		}
		$_SESSION['front_panel']['name'] 	= $cust_email;
		$cli_session_id						= session_id();
		
		$sql_login_info		= "INSERT INTO `tbl_customer_login_info`(`cli_id`, `cli_custid`, `cli_ip_address`, `cli_browser_info`, `cli_session_id`,";
		$sql_login_info		.= " `cli_session_status`, `cli_created`) VALUES ('".$cli_id."','".$cust_id."','".$cli_ip_address."',";
		$sql_login_info		.= " '".$cli_browser_info."','".$cli_session_id."','1','".$datetime."')";
		$result_login_info 	= mysqli_query($db_con,$sql_login_info) or die(mysqli_error($db_con));
		if($result_login_info)
		{
			$response_array 	= array("Success"=>"Success","resp"=>"Login Successful");
		}
		else
		{
			$response_array 	= array("Success"=>"fail","resp"=>"Login info not added");
		}	
	}
	return $response_array;
}
/* every time user login or register this will add information of user to the login info page*/
/* refresh cart data on every add/edit delete functions*/
function refreshCart($cart_custid,$page_id)
{
	global $db_con, $BaseFolder;
	global $min_order_value;
	global $shipping_charge;
	global $response_array;
	
	$response_array	= array();
	
	$sql_sum_cart_prod_discount	= " SELECT SUM(cart_discount) AS cart_discount FROM tbl_cart where cart_status = 0 and cart_custid = '".$cart_custid."' ";
	$res_sum_cart_prod_discount	= mysqli_query($db_con, $sql_sum_cart_prod_discount) or die(mysqli_error($db_con));
	$row_sum_cart_prod_discount	= mysqli_fetch_array($res_sum_cart_prod_discount);
	
	$sql_get_cart_items 	= " SELECT * FROM tbl_cart where cart_status = 0 and cart_custid = '".$cart_custid."' ";
	$result_get_cart_items 	= mysqli_query($db_con,$sql_get_cart_items) or die(mysqli_error($db_con));
	$num_rows_get_cart_items= mysqli_num_rows($result_get_cart_items);
	if($num_rows_get_cart_items == 0)
	{
		$response_str = '<div align="center" style="font-size:24px;color:F00;margin:30px 0;" >Cart is empty</div>';
		$response_array = array("Success"=>"fail","resp"=>$response_str,"count"=>"(0)","checkout"=>"0");
	}
	else
	{	
		
		$start 		= 0;
		$cart_data	= '<form action="#" method="post" class="cart_div">';
			$cart_data	.= '<div>';
				$cart_data	.= '<table class="shop_table cart">';
					$cart_data	.= '<thead>';
						$cart_data	.= '<tr>';
						$cart_data	.= '<th class="product-thumbnail" style="width:15%">Image</th>';
						$cart_data	.= '<th class="product-model" style="width:15%">Model no.</th>';
						$cart_data	.= '<th class="product-name" style="width:30%">Product Name</th>';
						$cart_data	.= '<th class="product-price" style="width:15%">Price</th>';
						$cart_data	.= '<th class="product-quantity" style="width:10%">Quantity</th>';
						$cart_data	.= '<th class="product-subtotal" style="width:15%">Total</th>';
						if($page_id != 0)		
						{
							$cart_data	.= '<th class="product-remove">&nbsp;</th>';
						}
						$cart_data	.= '</tr>';
					$cart_data	.= '</thead>';
		$cart_data	.= '<tbody>';
		$cart_count			= 0;
		$cart_total			= 0;
		$final_total		= 0;	
		$discount_total		= 0;
		$cart_coupon_code	= "";
		while($row_get_cart_item = mysqli_fetch_array($result_get_cart_items))
		{			
			$sql_prod_data 		= " SELECT * FROM tbl_products_master WHERE prod_id = '".$row_get_cart_item['cart_prodid']."' ";
			$result_prod_data 	= mysqli_query($db_con,$sql_prod_data) or die(mysqli_error($db_con));
			$row_prod_data		= mysqli_fetch_array($result_prod_data);
			
			$sql_image_data 	= " SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_status` != 0 and `prod_img_type` = 'main' and `prod_img_prodid` = '".$row_get_cart_item['cart_prodid']."' ";
			$result_image_data 	= mysqli_query($db_con,$sql_image_data) or die(mysqli_error($db_con));
			$row_image_data		= mysqli_fetch_array($result_image_data);
			$cart_data	.= '<tr class="cart_item">';
			$cart_data	.= '<td class="product-thumbnail">';
			$cart_data	.= '<a href="'.$BaseFolder."/".$row_prod_data['prod_slug']."-".$row_prod_data['prod_id']."-a".'">';
			if(trim($row_image_data['prod_img_file_name']) != "")
			{
				$imagepath 	= "images/planet/org".$row_prod_data['prod_orgid']."/prod_id_".$row_prod_data['prod_id']."/small/".$row_image_data['prod_img_file_name'];				
				if(file_exists("../".$imagepath))
				{
					$cart_data	.= '<img src="'.$imagepath.'" data-at2x="'.$imagepath.'" class="attachment-shop_thumbnail wp-post-image" alt="'.wordwrap(trim($row_prod_data['prod_title']), 20, "<br />\n").'" >';
				}
				else
				{
					$cart_data	.= '<img src="images/no-image.jpg" data-at2x="'.$BaseFolder.'/images/no-image.jpg" class="attachment-shop_thumbnail wp-post-image" alt="" >';
				}
			}		
			else
			{
				$cart_data	.= '<img src="images/no-image.jpg" data-at2x="'.$BaseFolder.'/images/no-image.jpg" class="attachment-shop_thumbnail wp-post-image" alt="" >';
			}
			$cart_data	.= '</a>';
			$cart_data	.= '</td>';	// Product Image
			$cart_data	.= '<td class="product-model">';
			$cart_data	.= '<span>'.$row_prod_data['prod_model_number'].'</span>';
			$cart_data	.= '</td>';	// Model Number
			$cart_data	.= '<td class="product-name" style="padding:2px;">';
			$cart_data	.= '<a href="'.$BaseFolder."/".$row_prod_data['prod_slug']."-".$row_prod_data['prod_id']."-a".'">'.trim($row_prod_data['prod_title']).'</a>';
			$cart_data	.= '</td>';	// Product Name
			$cart_data	.= '<td class="product-price">';
			$cart_data	.= '<span class="amount"><i class="fa fa-rupee"></i> '.$row_prod_data['prod_recommended_price'].'</span>';
			$cart_data	.= '</td>';	// Price
			$cart_data	.= '<td class="product-quantity">';
			$cart_data	.= '<div class="quantity buttons_added columns-row" style="width:200px">';
			//if($page_id != 0)
			{
				$cart_data	.= '<div class="columns-col columns-col-4">';
					$cart_data	.= '<div class="cws-button" id="'.$row_get_cart_item['cart_id'].'minus" ';
					if($row_get_cart_item['cart_prodquantity'] <= 10 && $row_get_cart_item['cart_prodquantity'] !=1)
					{
						$cart_data	.= ' style="min-width:0px !important;font-size:10px !important;" onClick="changeQuantity(this.id,0);" ';	
					}
					else
					{
						$cart_data	.= ' style="min-width:0px !important;font-size:10px !important;pointer-events: none;opacity: 0.5;" ';
					}
					
					$cart_data	.= '>';
						$cart_data	.= '<i class="fa fa-minus" aria-hidden="true" style="font-size:14px;line-height: 10px;"></i>';
					$cart_data	.= '</div>';
				$cart_data	.= '</div>';
				
				$cart_data	.= '<div class="columns-col columns-col-4">';
					$cart_data	.= '<input type="number" step="'.$row_get_cart_item['cart_prodquantity'].'" min="0" onkeypress="return isNumberKey(event)" name="cart" value="'.$row_get_cart_item['cart_prodquantity'].'" class="input-text qty text" disabled>';	// onblur="changeQuantity(this.value,2);" 
				$cart_data	.= '</div>';				
				
				$cart_data	.= '<div class="columns-col columns-col-4">';
					$cart_data	.= '<div class="cws-button" id="'.$row_get_cart_item['cart_id'].'plus"  ';
				   if($row_get_cart_item['cart_prodquantity'] < 10)
					{
						$cart_data	.= ' style="min-width:0px !important;font-size:10px !important;" onClick="changeQuantity(this.id,1);" ';
					}
					else
					{
						$cart_data	.= ' style="min-width:0px !important;font-size:10px !important;pointer-events: none;opacity: 0.5;" ';
					}
					$cart_data	.= '>';
						$cart_data	.= '<i class="fa fa-plus" aria-hidden="true" style="font-size:14px;line-height: 10px;"></i>';
					$cart_data	.= '</div>';
				$cart_data	.= '</div>';
			}
			//else
			{
				//$cart_data	.= '<div class="columns-col columns-col-12">';
				//$cart_data	.= '<input type="number" step="'.$row_get_cart_item['cart_prodquantity'].'" value="'.$row_get_cart_item['cart_prodquantity'].'" class="input-text qty text" disabled="disabled">';
				//$cart_data	.= '</div>';
			}
			//$cart_data	.= '<div style="clear:both;"></div>';
			$cart_data	.= '</div>';
			$cart_data	.= '</td>';	// Quantity
			$cart_data	.= '<td class="product-subtotal">';
			$cart_data	.= '<span class="amount"><i class="fa fa-rupee"></i> '.$row_get_cart_item['cart_price'].'</span>';
			$cart_data	.= '</td>';	// Total
			if($page_id != 0)
			{			
				$cart_data	.= '<td class="product-remove">';
				$cart_data	.= '<a href="javascript:void(0);" class="remove" title="Remove this item" id="'.$row_get_cart_item['cart_id'].'" onClick="removeItem(this.id);"></a>';
				$cart_data	.= '</td>';
			}
			$cart_data	.= '</tr>';
			$cart_data	.= '<tr>';	
			$cart_total	+= $row_get_cart_item['cart_price']; 
			++$cart_count;	
			//$discount_total	= $row_get_cart_item['cart_discount'];		// Commented By Prathamesh
			$discount_total		= $row_sum_cart_prod_discount['cart_discount'];
			//$cart_coupon_code	= $row_get_cart_item['cart_coupon_code'];
			
			// get coupon code
			$sql_get_coup_code	= " SELECT * FROM `tbl_coupons` WHERE `coup_id`='".$row_get_cart_item['cart_coupon_code']."' ";
			$res_get_coup_code	= mysqli_query($db_con,$sql_get_coup_code) or die(mysqli_error($db_con));
			$row_get_coup_code	= mysqli_fetch_array($res_get_coup_code);
			
			$cart_coupon_code	= $row_get_coup_code['coup_code'];
		}
		$cart_data	.= '</tr>';			
		$cart_data	.= '</tbody>';
		$cart_data	.= '</table>';
		$cart_data	.= '</div>';
		$cart_data	.= '</form>';		
		$cart_data	.= '<div class="cart-collaterals">';
		if($page_id != 0)
		{		
			#=============================================================================
			# START : Commented By Prathamesh [on 03-11-2016]
			#=============================================================================
			/*if(trim($cart_coupon_code) == "")
			{
				$cart_data	.= '<div class="coupon">';
				//$cart_data	.= '<label for="coupon_code">Coupon:</label>';
				$cart_data	.= '<input type="text" name="coupon_code" class="input-text corner-radius-top" id="coupon_code" value="" placeholder="Coupon code / Gift voucher">';
				$cart_data	.= '<button class="cws-button corner-radius-bottom" name="apply_coupon" onclick="updateCoupon();">Apply Coupon</button>';
				$cart_data	.= '</div>';
			}		
			else
			{
				$cart_data	.= '<div class="coupon">';
				$cart_data	.= '<label for="coupon_code">Coupon:</label>';
				$cart_data	.= '<input type="text" name="coupon_code" class="input-text corner-radius-top" id="coupon_code" value="'.$cart_coupon_code.'" disabled="disabled">';
				$cart_data	.= '<input type="button" class="cws-button corner-radius-bottom" value="Applied">';			
				$cart_data	.= '</div>';				
			}*/
			#=============================================================================
			# END : Commented By Prathamesh [on 03-11-2016]
			#=============================================================================
		}
		$cart_data		.= '<form method="post" action="#" class="shipping_calculator"></form>'; 
		$cart_data		.= '<div class="cart_totals ">';
		$cart_data		.= '<table>';
		$cart_data		.= '<tbody>';
		$cart_data		.= '<tr class="cart-subtotal">';
		$cart_data		.= '<th>Cart Subtotal</th>';
		$cart_data		.= '<td><span class="amount"><i class="fa fa-rupee"></i>'.$cart_total.'</span></td>';
		$cart_data		.= '</tr>';
		$cart_data		.= '<tr class="shipping">';
		$cart_data		.= '<th>Shipping</th>';
		$cart_data		.= '<td>';
		#=============================================================================================================
		# START : Different shipping chargers for different total amount [DN by Prathamesh on 01-11-2016]
		#=============================================================================================================
		
		// Query For getting all records from tbl_free_shipping table
		$sql_get_fs_data	= " SELECT * FROM `tbl_free_shipping` WHERE fs_status='1' ";
		$res_get_fs_data	= mysqli_query($db_con, $sql_get_fs_data) or die(mysqli_error($db_con));
		
		// If the customer is logged-in
		if((isset($_SESSION['front_panel']['name'])))
		{
			while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
			{
				if($row_get_fs_data['fs_type_value'] == 0)
				{
					if($cart_total >= $row_get_fs_data['fs_start_price'])
					{
						//$cart_data		.= $row_get_fs_data['fs_type_value'];
						$cart_data		.= 'Free Shipping';
						$shipping_charge = $row_get_fs_data['fs_type_value'];	
					}
				}
				elseif($row_get_fs_data['fs_type_value'] != 0)
				{
					if(($row_get_fs_data['fs_start_price'] <= $cart_total) && ($cart_total <= $row_get_fs_data['fs_end_price']))
					{
						// checking the occurrrence
						/*if($row_get_fs_data['fs_occurrence'] == 'first time')
						{
							// check order details of the customer
							$sql_chk_order_details	= " SELECT * FROM `tbl_order` WHERE `ord_custid`='".$_SESSION['front_panel']['cust_id']."' ";
							$res_chk_order_details	= mysqli_query($db_con, $sql_chk_order_details) or die(mysqli_error($db_con));
							$num_chk_order_details	= mysqli_num_rows($res_chk_order_details);
							
							if($num_chk_order_details != 0)
							{
								$cart_data		.= $row_get_fs_data['fs_type_value'];
								$shipping_charge = $row_get_fs_data['fs_type_value'];	
							}
							else
							{
								$cart_data		.= 'Free Shipping';
								$shipping_charge = 0;
							}
						}
						elseif($row_get_fs_data['fs_occurrence'] == 'other')
						{*/
							$cart_data			.= $row_get_fs_data['fs_type_value'];
							$shipping_charge 	= $row_get_fs_data['fs_type_value'];	
						/*}*/
					}
				}
			}
		}
		else	// If the customer is not logged-in
		{
			while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
			{
				if($row_get_fs_data['fs_type_value'] == 0)
				{
					if($cart_total >= $row_get_fs_data['fs_start_price'])
					{
						//$cart_data		.= $row_get_fs_data['fs_type_value'];
						$cart_data		.= 'Free Shipping';
						$shipping_charge = $row_get_fs_data['fs_type_value'];	
					}
				}
				elseif($row_get_fs_data['fs_type_value'] != 0)
				{
					if(($row_get_fs_data['fs_start_price'] <= $cart_total) && ($cart_total <= $row_get_fs_data['fs_end_price']))
					{
						// checking the occurrrence
						if($row_get_fs_data['fs_occurrence'] == 'first time')
						{
							$cart_data			.= $row_get_fs_data['fs_type_value'];
							$shipping_charge 	= $row_get_fs_data['fs_type_value'];
						}
						elseif($row_get_fs_data['fs_occurrence'] == 'other')
						{
							$cart_data			.= $row_get_fs_data['fs_type_value'];
							$shipping_charge 	= $row_get_fs_data['fs_type_value'];	
						}
					}
				}
			}
		}
		#=============================================================================================================				
		# END : Different shipping chargers for different total amount [DN by Prathamesh on 01-11-2016]
		#=============================================================================================================		
		
		/*if($cart_total > $min_order_value)
		{
			$cart_data		.= 'Free Shipping';
			$shipping_charge = 0;			
		}
		else
		{
			$cart_data		.= $shipping_charge;
			$shipping_charge = $shipping_charge;
		}*/
		$cart_data		.= '</td>';
		$cart_data		.= '</tr>';
		#===========================================================================================
		# START : Commented By Prathamesh [on 03-11-2016]
		#===========================================================================================
		if($discount_total == 0)
		{
			/*$cart_data		.= '<tr class="shipping">';
			$cart_data		.= '<th>Discount</th>';
			$cart_data		.= '<td>';
			$cart_data		.= 'No Discount';
			$cart_data		.= '</td>';
			$cart_data		.= '</tr>';	*/		
		}
		else
		{
			$cart_data		.= '<tr class="shipping">';
			$cart_data		.= '<th>Discount</th>';
			$cart_data		.= '<td>';
			$cart_data		.= $discount_total;
			$cart_data		.= '</td>';
			$cart_data		.= '</tr>';			
		}
		#===========================================================================================
		# END : Commented By Prathamesh [on 03-11-2016]
		#===========================================================================================
		$final_total	= $cart_total + $shipping_charge - $discount_total;		
		$cart_data		.= '<tr class="order-total">';
		$cart_data		.= '<th>Order Total</th>';
		$cart_data		.= '<td><span class="amount"><i class="fa fa-rupee"></i>'.$final_total.'</span></td>';
		$cart_data		.= '</tr>';
		$cart_data		.= '</tbody>';
		$cart_data		.= '</table>';
		$cart_data		.= '</div>';
		$cart_data		.= '</div>';
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($cart_data),"count"=>"(".$cart_count.")","checkout"=>"1");
	}
	return $response_array;
}
/* refresh cart data on every add/edit delete functions*/
/**/
function updateCoupon($cust_id,$coupon_code)
{
	global $db_con, $datetime;
	$no_of_time_coupon_apply 				= 1;
	$sql_check_coupon_applied_before 		= " SELECT * FROM `tbl_cart` ";
	$sql_check_coupon_applied_before 		.= " WHERE `cart_coupon_code` like '".$coupon_code."' ";
	$sql_check_coupon_applied_before 		.= " 	and `cart_custid` = '".$cust_id."' ";
	$result_check_coupon_applied_before 	= mysqli_query($db_con,$sql_check_coupon_applied_before) or die(mysqli_error($db_con));
	$num_rows_check_coupon_applied_before	= mysqli_num_rows($result_check_coupon_applied_before);
	if($num_rows_check_coupon_applied_before >= $no_of_time_coupon_apply)
	{
		return "Coupon Code Already Applied";			
		exit(0);
	}
	else
	{
		// get coupon data from coupon 
		$sql_get_coup_data	= " SELECT * FROM `tbl_coupons` WHERE `coup_code`='".$coupon_code."' ";
		$res_get_coup_data	= mysqli_query($db_con, $sql_get_coup_data) or die(mysqli_error($db_con));
		$row_get_coup_data	= mysqli_fetch_array($res_get_coup_data);
		
		$min_purch				= $row_get_coup_data['coup_min_purch'];
		$coup_discount_type		= $row_get_coup_data['coup_discount_type'];
		$coup_id				= $row_get_coup_data['coup_id'];
		$coup_discount_amount	= $row_get_coup_data['coup_discount_amount'];
		
		// get all records of that logged in user from "tbl_cart" table
		$sql_get_cart_cust_data	= " SELECT * FROM `tbl_cart` WHERE `cart_custid`='".$cust_id."' AND `cart_status`='0' ";
		$res_get_cart_cust_data = mysqli_query($db_con, $sql_get_cart_cust_data) or die(mysqli_error($db_con));
		$num_get_cart_cust_data	= mysqli_num_rows($res_get_cart_cust_data);
		
		// Sum of all prod price
		$sql_get_sum_prod_price	= " SELECT SUM( `cart_price` ) AS cart_price FROM `tbl_cart` WHERE `cart_custid`='".$cust_id."' AND `cart_status`='0' ";
		$res_get_sum_prod_price	= mysqli_query($db_con,$sql_get_sum_prod_price) or die(mysqli_error($db_con));
		$row_get_sum_prod_price	= mysqli_fetch_array($res_get_sum_prod_price);
		
		if($row_get_sum_prod_price['cart_price'] >= $min_purch)
		{
			$sum_prod_prie			= $row_get_sum_prod_price['cart_price'];
			
			if($num_get_cart_cust_data != 0)
			{
				while($row_get_cart_cust_data = mysqli_fetch_array($res_get_cart_cust_data))
				{
					$cart_discount_per_prod	= '';
					if($coup_discount_type == 'percent')
					{
						$cart_discount_per_prod	= round($row_get_cart_cust_data['cart_price']*($coup_discount_amount/100));
					}
					elseif($coup_discount_type == 'price')
					{
						$cart_discount_per_prod	= round(($row_get_cart_cust_data['cart_price']/$sum_prod_prie)*$coup_discount_amount);
					}
					
					// Update cart records with the coupon code id and its discount per prod
					$sql_update_each_cart_prod	= " UPDATE `tbl_cart` ";
					$sql_update_each_cart_prod	.= " 	SET `cart_coupon_code`='".$coup_id."', ";
					$sql_update_each_cart_prod	.= " 		`cart_discount`='".$cart_discount_per_prod."', ";
					$sql_update_each_cart_prod	.= " 		`cart_modified`='".$datetime."' ";
					$sql_update_each_cart_prod	.= " WHERE `cart_id`='".$row_get_cart_cust_data['cart_id']."' ";
					$res_update_each_cart_prod	= mysqli_query($db_con, $sql_update_each_cart_prod) or die(mysqli_error($db_con));
				}
				if($res_update_each_cart_prod)
				{
					// Get the Sum of the Cart Discount for the particular customer
					$sum_cart_discount	= getSumCartDiscount($cust_id);	// 106
					
					if($coup_discount_type == 'percent')
					{
						// Get SUM of the Cart Price and then its percent
						$sql_get_sum_of_cart	= " SELECT SUM( `cart_price` ) AS cart_price FROM `tbl_cart` WHERE `cart_custid`='".$cust_id."' AND `cart_status`='0' ";
						$res_get_sum_of_cart	= mysqli_query($db_con, $sql_get_sum_of_cart) or die(mysqli_error($db_con));
						$row_get_sum_of_cart	= mysqli_fetch_array($res_get_sum_of_cart);
						
						$sum_cart_price			= $row_get_sum_of_cart['cart_price'];
						
						$sum_cart_price_percent	= round($sum_cart_price*($coup_discount_amount/100));	// 105
						
						if($sum_cart_discount > $sum_cart_price_percent)	//if(106 > 105)
						{
							$diff_of_discount	= round($sum_cart_discount - $sum_cart_price_percent);
							
							// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
							$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
							$sql_get_max_discount_amount	.= " WHERE `cart_discount` > '".$diff_of_discount."' ";
							$sql_get_max_discount_amount	.= " 	AND `cart_custid`='".$cust_id."' ";
							$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
							$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` DESC ";
							$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
							$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
							$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
							
							$max_discount_amount			= $row_get_max_discount_amount['cart_discount'];
							$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
							
							$final_discounted_amount		= round($max_discount_amount - $diff_of_discount);
							
							// Update the current record of the Cart Table
							$sql_update_cart_record			= " UPDATE `tbl_cart` ";
							$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
							$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
							$sql_update_cart_record			.= " 	AND `cart_custid`='".$cust_id."' ";
							$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
						}
						elseif($sum_cart_discount < $sum_cart_price_percent)	//if(106 > 107)
						{
							$diff_of_discount	= round($sum_cart_price_percent - $sum_cart_discount);
							
							// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
							$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
							$sql_get_max_discount_amount	.= " WHERE `cart_custid`='".$cust_id."' ";
							$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
							$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` ASC ";
							$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
							$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
							$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
							
							$min_discount_amount			= $row_get_max_discount_amount['cart_discount'];
							$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
							
							$final_discounted_amount		= round($min_discount_amount + $diff_of_discount);
							
							// Update the current record of the Cart Table
							$sql_update_cart_record			= " UPDATE `tbl_cart` ";
							$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
							$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
							$sql_update_cart_record			.= " 	AND `cart_custid`='".$cust_id."' ";
							$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
						}
					}
					elseif($coup_discount_type == 'price')
					{
						if($sum_cart_discount > $coup_discount_amount)	// 301 out of 300
						{
							$diff_of_discount	= round($sum_cart_discount - $coup_discount_amount);
							
							// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
							$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
							$sql_get_max_discount_amount	.= " WHERE `cart_discount` > '".$diff_of_discount."' ";
							$sql_get_max_discount_amount	.= " 	AND `cart_custid`='".$cust_id."' ";
							$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
							$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` DESC ";
							$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
							$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
							$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
							
							$max_discount_amount			= $row_get_max_discount_amount['cart_discount'];
							$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
							
							$final_discounted_amount		= round($max_discount_amount - $diff_of_discount);
							
							// Update the current record of the Cart Table
							$sql_update_cart_record			= " UPDATE `tbl_cart` ";
							$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
							$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
							$sql_update_cart_record			.= " 	AND `cart_custid`='".$cust_id."' ";
							$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
						}
						elseif($sum_cart_discount < $coup_discount_amount)	// 299 out of 300
						{
							$diff_of_discount	= round($coup_discount_amount - $sum_cart_discount);
							
							// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
							$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
							$sql_get_max_discount_amount	.= " WHERE `cart_custid`='".$cust_id."' ";
							$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
							$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` ASC ";
							$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
							$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
							$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
							
							$min_discount_amount			= $row_get_max_discount_amount['cart_discount'];
							$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
							
							$final_discounted_amount		= round($min_discount_amount + $diff_of_discount);
							
							// Update the current record of the Cart Table
							$sql_update_cart_record			= " UPDATE `tbl_cart` ";
							$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
							$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
							$sql_update_cart_record			.= " 	AND `cart_custid`='".$cust_id."' ";
							$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
						}
					}
					
					return "Coupon Code Applied";
				}
				else
				{
					return "Coupon Code Not Applied";
				}
			}
			else
			{
				return "Cart is empty.";	
			}
		}
		else
		{
			return "NOT APPLIED";			
		}
	}
}
/* on user login update cart products*/
function updateCartProductLogin($cust_id,$cookie_value)
{
	global $db_con, $datetime;
	global $cookie_name;
	$get_cookie_entry			= array();
	$sql_get_cookie_entry 		= " SELECT `cart_prodid` FROM `tbl_cart` where `cart_custid` =  '".$cust_id."' ";
	$result_get_cookie_entry 	= mysqli_query($db_con,$sql_get_cookie_entry) or die(mysqli_error($db_con));
	while($row_get_cookie_entry = mysqli_fetch_array($result_get_cookie_entry))
	{
		$sql_delete_prod		= " DELETE FROM `tbl_cart` WHERE `cart_prodid` = '".$row_get_cookie_entry['cart_prodid']."' and `cart_custid` = '".$cookie_value."' ";
		$result_delete_prod 	= mysqli_query($db_con,$sql_delete_prod) or die(mysqli_error($db_con));										
	}								
	$sql_update_cart 	= " UPDATE `tbl_cart` SET `cart_custid`='".$cust_id."',`cart_modified`= '".$datetime."' WHERE `cart_custid` = '".$cookie_value."'";
	$result_update_cart = mysqli_query($db_con,$sql_update_cart) or die(mysqli_error($db_con));
	if($result_update_cart)
	{
		setcookie($cookie_name, NULL, -1, '/');
	}	
}
/* on user login update cart products*/
/* add product to cart */
function insertIntoCartTable($cart_id, $cart_custid, $cart_prodid, $cart_prodquantity, $cart_price, $cart_coupon_code, $discount_amount, $cart_unit, $response_array, $coup_get_discount_type)
{
	global $db_con, $datetime;
	
	$sql_insert_cart	= " INSERT INTO `tbl_cart`(`cart_id`, `cart_custid`, `cart_prodid`, `cart_prodquantity`, ";
	$sql_insert_cart	.= " `cart_price`, `cart_coupon_code`, `cart_discount`,`cart_type`,`cart_status`, `cart_created`, `cart_unit`) ";
	$sql_insert_cart	.= " VALUES ('".$cart_id."','".$cart_custid."','".$cart_prodid."','".$cart_prodquantity."', ";
	$sql_insert_cart	.= " '".$cart_price."', '".$cart_coupon_code."','".$discount_amount."','abundant','0', '".$datetime."', '".$cart_unit."')";
	//echo json_encode($sql_insert_cart);exit();
	
	$res_insert_cart	= mysqli_query($db_con, $sql_insert_cart) or die(mysqli_error($db_con));
	
	
	if($res_insert_cart)
	{
		// Check coup_get_discount_type, Is it blank or not
		if($coup_get_discount_type != '' && $coup_get_discount_type == 'percent')
		{
			// Get the Sum of the Cart Discount for the particular customer
			$sum_cart_discount	= getSumCartDiscount($cart_custid);	// 106
			
			// Get SUM of the Cart Price and then its percent
			$sql_get_sum_of_cart	= " SELECT SUM( `cart_price` ) AS cart_price FROM `tbl_cart` WHERE `cart_custid`='".$cart_custid."' AND `cart_status`='0' ";
			$res_get_sum_of_cart	= mysqli_query($db_con, $sql_get_sum_of_cart) or die(mysqli_error($db_con));
			$row_get_sum_of_cart	= mysqli_fetch_array($res_get_sum_of_cart);
			$sum_cart_price			= $row_get_sum_of_cart['cart_price'];
			/// done by satish //
			$sql_coup_per	= " SELECT * FROM `tbl_coupons` WHERE `coup_id`='".$cart_coupon_code."'  ";
			$res_coup_per	= mysqli_query($db_con, $sql_coup_per) or die(mysqli_error($db_con));
			$row_coup_per	= mysqli_fetch_array($res_coup_per);
			$coup_discount_amount = $row_coup_per['coup_discount_amount'];
			// end done by satish--//
			$sum_cart_price_percent	= round($sum_cart_price*($coup_discount_amount/100));	// 105 //
		//	$response_array =array("sum_cart_discount"=>$sum_cart_discount,"sum_cart_price"=>$sum_cart_price,"sum_cart_price_percent"=>$sum_cart_price_percent,"discount_amount"=>$discount_amount);
			//echo json_encode($response_array);exit();
			if($sum_cart_discount > $sum_cart_price_percent)	//if(106 > 105)
			{
				$diff_of_discount	= round($sum_cart_discount - $sum_cart_price_percent);
				
				// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
				$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
				$sql_get_max_discount_amount	.= " WHERE `cart_discount` > '".$diff_of_discount."' ";
				$sql_get_max_discount_amount	.= " 	AND `cart_custid`='".$cart_custid."' ";
				$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
				$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` DESC ";
				$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
				$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
				$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
				
				$max_discount_amount			= $row_get_max_discount_amount['cart_discount'];
				$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
				
				$final_discounted_amount		= round($max_discount_amount - $diff_of_discount);
				
				// Update the current record of the Cart Table
				$sql_update_cart_record			= " UPDATE `tbl_cart` ";
				$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
				$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
				$sql_update_cart_record			.= " 	AND `cart_custid`='".$cart_custid."' ";
				$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
			}
			elseif($sum_cart_discount < $sum_cart_price_percent)	//if(106 > 107)
			{
				$diff_of_discount	= round($sum_cart_price_percent - $sum_cart_discount);
				
				// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
				$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
				$sql_get_max_discount_amount	.= " WHERE `cart_custid`='".$cart_custid."' ";
				$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
				$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` ASC ";
				$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
				$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
				$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
				
				$min_discount_amount			= $row_get_max_discount_amount['cart_discount'];
				$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
				
				$final_discounted_amount		= round($min_discount_amount + $diff_of_discount);
				
				// Update the current record of the Cart Table
				$sql_update_cart_record			= " UPDATE `tbl_cart` ";
				$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
				$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
				$sql_update_cart_record			.= " 	AND `cart_custid`='".$cart_custid."' ";
				$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
			}
				
		}
		
		$response_array = refreshCart($cart_custid,1);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Not Added in Cart");				
	}
	
	return $response_array;
}

function getPriceDiscountOverAllProds($cart_id, $cart_custid, $cart_prodid, $cart_prodquantity, $cart_price, $cart_coupon_code, $cart_unit, $response_array, $coup_discount_amount,$coup_get_discount_type)
{
	global $db_con, $datetime;
	
	$sql_insert_into_cart	= " INSERT INTO `tbl_cart`(`cart_id`, `cart_custid`, `cart_prodid`, `cart_prodquantity`, ";
	$sql_insert_into_cart	.= " `cart_price`, `cart_coupon_code`, `cart_type`, ";
	$sql_insert_into_cart	.= " `cart_status`, `cart_created`, `cart_unit`) ";
	$sql_insert_into_cart	.= " VALUES ('".$cart_id."','".$cart_custid."','".$cart_prodid."','".$cart_prodquantity."', ";
	$sql_insert_into_cart	.= " '".$cart_price."', '".$cart_coupon_code."', 'abundant' , ";
	$sql_insert_into_cart	.= " '0', '".$datetime."', '".$cart_unit."') ";
	$res_insert_into_cart	= mysqli_query($db_con, $sql_insert_into_cart) or die(mysqli_error($db_con));
	
	if($res_insert_into_cart)
	{
		$sql_get_cart_records_sum	= " SELECT SUM(cart_price) AS cart_price FROM tbl_cart ";
		$sql_get_cart_records_sum	.= " WHERE cart_custid = '".$cart_custid."' AND cart_status = '0' ";
		$res_get_cart_records_sum	= mysqli_query($db_con, $sql_get_cart_records_sum) or die(mysqli_error($db_con));
		$row_get_cart_records_sum	= mysqli_fetch_array($res_get_cart_records_sum);
		
		$sum_cart_price				= $row_get_cart_records_sum['cart_price'];
		
		// Get all record from the cart table
		$sql_get_cart_records	= " SELECT * FROM tbl_cart WHERE cart_custid = '".$cart_custid."' and cart_status = '0' ";
		$res_get_cart_records	= mysqli_query($db_con, $sql_get_cart_records) or die(mysqli_error($db_con));
		
		while($row_get_cart_records = mysqli_fetch_array($res_get_cart_records))
		{
			$updated_discounted_amount	= round(($row_get_cart_records['cart_price']/$sum_cart_price)*$coup_discount_amount);
			
			// Update the cart record
			$sql_update_cart_records	= " UPDATE `tbl_cart` ";
			$sql_update_cart_records	.= " 	SET `cart_discount`='".$updated_discounted_amount."', ";
			$sql_update_cart_records	.= " 		`cart_modified`='".$datetime."' ";
			$sql_update_cart_records	.= " WHERE `cart_id`='".$row_get_cart_records['cart_id']."' "; 
			$res_update_cart_records	= mysqli_query($db_con, $sql_update_cart_records) or die(mysqli_query($db_con));
		}
		
		if($res_update_cart_records)
		{
			// Get the Sum of the Cart Discount for the particular customer
			$sum_cart_discount	= getSumCartDiscount($cart_custid);	// 106
			
			if($coup_get_discount_type == 'price')
			{
				// get coupon data from coupon 
				$sql_get_coup_data	= " SELECT * FROM `tbl_coupons` WHERE `coup_code`='".$cart_coupon_code."' ";
				$res_get_coup_data	= mysqli_query($db_con, $sql_get_coup_data) or die(mysqli_error($db_con));
				$row_get_coup_data	= mysqli_fetch_array($res_get_coup_data);
				
				$coup_discount_amount	= $row_get_coup_data['coup_discount_amount'];
				
				if($sum_cart_discount > $coup_discount_amount)	// 301 out of 300
				{
					$diff_of_discount	= round($sum_cart_discount - $coup_discount_amount);
					
					// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
					$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
					$sql_get_max_discount_amount	.= " WHERE `cart_discount` > '".$diff_of_discount."' ";
					$sql_get_max_discount_amount	.= " 	AND `cart_custid`='".$cart_custid."' ";
					$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
					$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` DESC ";
					$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
					$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
					$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
					
					$max_discount_amount			= $row_get_max_discount_amount['cart_discount'];
					$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
					
					$final_discounted_amount		= round($max_discount_amount - $diff_of_discount);
					
					// Update the current record of the Cart Table
					$sql_update_cart_record			= " UPDATE `tbl_cart` ";
					$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
					$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
					$sql_update_cart_record			.= " 	AND `cart_custid`='".$cart_custid."' ";
					$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
				}
				elseif($sum_cart_discount < $coup_discount_amount)	// 299 out of 300
				{
					$diff_of_discount	= round($coup_discount_amount - $sum_cart_discount);
					
					// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
					$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
					$sql_get_max_discount_amount	.= " WHERE `cart_custid`='".$cart_custid."' ";
					$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
					$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` ASC ";
					$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
					$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
					$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
					
					$min_discount_amount			= $row_get_max_discount_amount['cart_discount'];
					$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
					
					$final_discounted_amount		= round($min_discount_amount + $diff_of_discount);
					
					// Update the current record of the Cart Table
					$sql_update_cart_record			= " UPDATE `tbl_cart` ";
					$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
					$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
					$sql_update_cart_record			.= " 	AND `cart_custid`='".$cart_custid."' ";
					$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
				}	
			}
			
			$response_array = refreshCart($cart_custid,1);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Not Added in Cart");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Not Added in Cart");	
	}
	
	return $response_array;
}

function addProductToCart($cart_custid,$cart_prodid,$cart_prodquantity,$cart_coupon_code,$response_array)
{
	global $db_con, $datetime;
	
	/*$response_array = array("Success"=>"fail","resp"=>$cart_custid.'<==>'.$cart_prodid.'<==>'.$cart_prodquantity);
	return $response_array;
	exit();*/
	
	if($cart_custid == "" || $cart_prodid == "" || $cart_prodquantity == "" )
	{
		$response_array = array("Success"=>"fail","resp"=>"Required fields are empty");
	}
	else
	{
		// Query for getting the prod details from prod_master table
		$sql_get_product 		= " SELECT * FROM `tbl_products_master` WHERE `prod_status` != 0 AND prod_id = '".$cart_prodid."' ";
		$result_get_product		= mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
		$num_rows_get_product 	= mysqli_num_rows($result_get_product);
		
		/*$response_array = array("Success"=>"fail","resp"=>$sql_get_product);
		return $response_array;
		exit();*/
		
		if($num_rows_get_product != 0)
		{
			$row_get_product	= mysqli_fetch_array($result_get_product);
			$cart_price			= ($row_get_product['prod_recommended_price'])*($cart_prodquantity);
			$cart_unit			= $row_get_product['prod_recommended_price'];	// DN by Prathamesh on 27-10-2016 [Unit Price of the Product]
				
			$table_name			= "tbl_cart";
			$new_id				= "cart_id";
			$cart_id 			= getNewId($new_id,$table_name);
			
			$sql_chk_prod_in_cart		= " SELECT * FROM tbl_cart WHERE cart_custid = '".$cart_custid."' and cart_status = 0 ";
			$result_chk_prod_in_cart 	= mysqli_query($db_con,$sql_chk_prod_in_cart) or die(mysqli_error($db_con));
			$prod_check					= 0;
			$cart_discount				= 0;
			$num_chk_prod_in_cart		= mysqli_num_rows($result_chk_prod_in_cart);
			
			if($num_chk_prod_in_cart != 0)
			{
				while($row_chk_prod_in_cart = mysqli_fetch_array($result_chk_prod_in_cart))
				{
					if($row_chk_prod_in_cart['cart_prodid'] == $cart_prodid)
					{
						$prod_check 		= 1;
					}
				}
			}
			
			if($prod_check == 0)
			{
				/*$response_array = array("Success"=>"fail","resp"=>$cart_coupon_code);
				return $response_array;
				exit();*/
				
				// Find the coupon_code is blanck or not
				if($cart_coupon_code == '')
				{
					/*$sql_insert_cart	= " INSERT INTO `tbl_cart`(`cart_id`, `cart_custid`, `cart_prodid`, `cart_prodquantity`, ";
					$sql_insert_cart	.= " `cart_price`, `cart_coupon_code`, `cart_discount`,`cart_status`, `cart_created`, `cart_unit`) ";
					$sql_insert_cart	.= " VALUES ('".$cart_id."','".$cart_custid."','".$cart_prodid."','".$cart_prodquantity."', ";
					$sql_insert_cart	.= " '".$cart_price."', '".$cart_coupon_code."','".$cart_discount."','0','".$datetime."', '".$cart_unit."')";
					$result_insert_cart	= mysqli_query($db_con,$sql_insert_cart) or die(mysqli_error($db_con));
					if($result_insert_cart)
					{
						$response_array = refreshCart($cart_custid,1);
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Not Added in Cart");				
					}*/
					$discount_amount		= '';
					$coup_get_discount_type	= '';
					
					/*$response_array = array("Success"=>"fail","resp"=>$cart_id.'<==>'.$cart_custid.'<==>'.$cart_prodid.'<==>'.$cart_prodquantity.'<==>'.$cart_price.'<==>'.$cart_coupon_code.'<==>'.$discount_amount.'<==>'.$cart_unit.'<==>'.$response_array.'<==>'.$coup_get_discount_type);
					return $response_array;
					exit();*/
					
					$response_array			= insertIntoCartTable($cart_id, $cart_custid, $cart_prodid, $cart_prodquantity, $cart_price, $cart_coupon_code, $discount_amount, $cart_unit, $response_array,$coup_get_discount_type);
				}
				elseif($cart_coupon_code != '')
				{
					// get coupon discount type from the tbl_coupon
					$sql_get_coup_details	= " SELECT * FROM `tbl_coupons` WHERE `coup_code`='".$cart_coupon_code."' ";
					$res_get_coup_details	= mysqli_query($db_con, $sql_get_coup_details) or die(mysqli_error($db_con));
					$row_get_coup_details	= mysqli_fetch_array($res_get_coup_details);
					
					$coup_get_discount_type		= $row_get_coup_details['coup_discount_type'];
					$coup_discount_amount		= $row_get_coup_details['coup_discount_amount'];
					$coup_id					= $row_get_coup_details['coup_id'];
					
					if(strcmp($coup_get_discount_type, 'price')===0)
					{
						// Function that required = 1] Cust ID
						
						$response_array	= getPriceDiscountOverAllProds($cart_id, $cart_custid, $cart_prodid, $cart_prodquantity, $cart_price, $coup_id, $cart_unit, $response_array, $coup_discount_amount,$coup_get_discount_type);
					
					}
					elseif(strcmp($coup_get_discount_type, 'percent')===0)
					{
						$discount_amount	= round($cart_price*($coup_discount_amount/100));	
						
						$response_array		= insertIntoCartTable($cart_id, $cart_custid, $cart_prodid, $cart_prodquantity, $cart_price, $coup_id, $discount_amount, $cart_unit, $response_array,$coup_get_discount_type);
					}
				}
			}
			elseif($prod_check == 1)
			{
				$response_array = array("Success"=>"fail","resp"=>"Already in Cart");	
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Not Added in Cart");	
		}
	}
	return $response_array;
}
/* add product to cart */
/* Update Price */
function getPrice($products)
{
	global $db_con;
	$sql_get_max_prod 		= " SELECT max(prod_recommended_price) as max_price,min(prod_recommended_price) as min_price FROM `tbl_products_master` ";
	$sql_get_max_prod 		.= " where prod_recommended_price != 0 and prod_recommended_price != ''  and prod_id IN (".implode(",",$products).") ";														
	$result_get_max_prod	= mysqli_query($db_con,$sql_get_max_prod) or die(mysqli_error($db_con));
	$num_rows_get_max_prod	= mysqli_num_rows($result_get_max_prod);
	if($num_rows_get_max_prod == 0)
	{
		$price = "0:0";		
	}
	else
	{
		$row_get_max_prod 	= mysqli_fetch_array($result_get_max_prod);
		$max_price 			= trim($row_get_max_prod['max_price']);
		if($max_price != 0 && $max_price != "")
		{															
			$max_price = round($max_price);
		}
		else
		{
			$max_price = 6000;
		}
		$min_price 			= trim($row_get_max_prod['min_price']);														
		if($min_price != 0 && $min_price != "")
		{
			$min_price = round($min_price);															
		}
		else
		{
			$min_price = 0;
		}	
		$price = $min_price.":".$max_price;
	}
	return 	$price;
}
/* Update Price */
/* Mail On Order */
function sendOrderEmailVendor($ord_id)
{
	global $db_con;
	global $BaseFolder;
	global $date;
	global $min_order_value;
	global $shipping_charge;
	$mydate					= getdate(date("U"));
	$today_date				= "$mydate[weekday], $mydate[month] $mydate[mday], $mydate[year]";
	/*Select last Order and Details*/	
	$sql_get_vendor_info		= " SELECT distinct org_id,org_name,org_primary_email,org_secondary_email,org_primary_phone,org_secondary_phone,org_tertiary_email FROM `tbl_oraganisation_master` tom,`tbl_products_master` tpm,`tbl_cart` tc,`tbl_order` tord ";
	$sql_get_vendor_info		.= " where tom.org_id = tpm.prod_orgid and tpm.prod_id = tc.cart_prodid and tc.cart_orderid = tord.ord_id  and tord.ord_id = '".$ord_id."' ";
	$result_get_vendor_info		= mysqli_query($db_con,$sql_get_vendor_info) or die(mysqli_error($db_con));
	$num_rows_get_vendor_info 	= mysqli_num_rows($result_get_vendor_info);
	if($num_rows_get_vendor_info != 0)
	{			
		while($row_get_vendor_info	= mysqli_fetch_array($result_get_vendor_info))
		{
			$vendor_id				= $row_get_vendor_info['org_id'];
			$vendor_name			= $row_get_vendor_info['org_name'];
			$vendor_primary_email 	= $row_get_vendor_info['org_primary_email'];
			$vendor_secondary_email = $row_get_vendor_info['org_secondary_email'];
			$vendor_tertiary_email 	= $row_get_vendor_info['org_tertiary_email'];
						
			$sql_order_details 		= " SELECT * FROM `tbl_order` WHERE `ord_id` =  '".$ord_id."' ";
			$result_order_details 	= mysqli_query($db_con,$sql_order_details) or die(mysqli_error($db_con));
			$row_order_details		= mysqli_fetch_array($result_order_details);
			$order_cust_id			= $row_order_details['ord_custid'];
			$order_add_id			= $row_order_details['ord_addid'];
			$ord_pay_type			= $row_order_details['ord_pay_type'];
			$ord_id_show			= $row_order_details['ord_id_show'];
				
			$sql_get_cart_details 	= " SELECT * FROM `tbl_products_master` tpm,`tbl_cart` tc,`tbl_order` tord where ";
			$sql_get_cart_details 	.= " tpm.prod_id = tc.cart_prodid and tc.cart_orderid = tord.ord_id and ";
			$sql_get_cart_details 	.= " tord.ord_id  = '".$ord_id."' and tpm.prod_orgid = '".$vendor_id."' "; 
			$result_get_cart_details = mysqli_query($db_con,$sql_get_cart_details) or die(mysqli_error($db_con));
			$order_data				='<style>th, td { border-bottom: 1px solid #ddd;}</style>';
			$order_data				.= '<table style="width:100%;">';
			$order_data				.= '<thead>';
			$order_data				.= '<th align="center" valign="top" style="width="30%">Product Model Number</th>';
			$order_data				.= '<th align="center" valign="top" style="width="30%">Product</th>';
			$order_data				.= '<th align="center" valign="top" style="width="30%">Name</th>';	
			$order_data				.= '<th align="center" valign="top" style="width="15%">Price</th>';
			$order_data				.= '<th align="center" valign="top" style="width="10%">Quantity</th>';
			$order_data				.= '<th align="center" valign="top" style="width="15%">Total</th>';	
			$order_data				.= '</thead>';
			$order_data				.= '<tbody>';
			$cart_total				= 0;			
			while($row_get_cart_details = mysqli_fetch_array($result_get_cart_details))
			{
				$sql_get_product_details 	= " SELECT `prod_id`, `prod_model_number`, `prod_name`,`prod_title`,`prod_slug`, `prod_orgid`, `prod_brandid`, `prod_catid`,`prod_subcatid`,`prod_status`, ";
				$sql_get_product_details 	.= " (SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_prodid` = tpm.prod_id and prod_img_type = 'main') as prod_img_file_name  FROM `tbl_products_master` tpm WHERE prod_id = '".$row_get_cart_details['cart_prodid']."' ";
				$result_get_product_details = mysqli_query($db_con,$sql_get_product_details) or die(mysqli_error($db_con));
				$row_get_product_details 	= mysqli_fetch_array($result_get_product_details);
				$prod_imagepath				= $BaseFolder."/images/planet/org".$row_get_product_details['prod_orgid']."/prod_id_".$row_get_product_details['prod_id']."/small/".$row_get_product_details['prod_img_file_name'];
				$product_name				= $row_get_product_details['prod_title'];		
				$order_data				.= '<tr>';
				$order_data				.= '<td align="center" valign="top" width="30%">';
				$order_data				.= $row_get_product_details['prod_model_number'];
				$order_data				.= '</td>';						
				$order_data				.= '<td align="center" valign="top" width="30%">';
				$order_data				.= '<img src="'.$prod_imagepath.'" alt="'.$product_name.'">';
				$order_data				.= '</td>';		
				$order_data				.= '<td align="center" valign="top" width="30%">';		
				$order_data 			.= '<span><a href="'.$BaseFolder.'/'.$row_get_product_details['prod_slug'].'-'.$row_get_product_details['prod_id'].'-a'.'">'.$product_name.'</a></span>';
				$order_data				.= '</td>';
				$order_data				.= '<td align="center" valign="top" width="15%">';
				$order_data				.= $row_get_cart_details['cart_unit'];
				$order_data				.= '</td>';		
				$order_data				.= '<td align="center" valign="top" width="10%">';
				$order_data				.= $row_get_cart_details['cart_prodquantity'];
				$order_data				.= '</td>';		
				$order_data				.= '<td align="center" valign="top" width="15%">';
				$order_data				.= $row_get_cart_details['cart_price'];
				$order_data				.= '</td>';				
				$order_data				.= '</tr>';
				$cart_total 			+= (int)$row_get_cart_details['cart_price'];
			}
			$order_data					.= '<tr><td>&nbsp;</td>';	
			$order_data					.= '<td>&nbsp;</td>';	
			$order_data					.= '<td colspan="3">';			
			$order_data					.= '<table>';
			$order_data					.= '<tbody>';
			$order_data					.= '<tr><td><b>Item Subtotal:</b></td>';
			$order_data					.= '<td>:'.$cart_total.'</td></tr>';
			$order_data					.= '<tr><td><b>Shipping & Handling:</b></td>';
			/*if($cart_total > $min_order_value)
			{
				$order_data				.= '<td><span style="color:f00;">Free Shipping</span></td></tr>';		
				$shipping_charge 		= 0;		
			}
			else
			{
				$order_data				.= $shipping_charge;
				$shipping_charge 		= $shipping_charge;
			}*/
			
			
			/////////////////////---------start  added by satish 14022017------------------////////
	                $sql_get_fs_data	= " SELECT * FROM `tbl_free_shipping` WHERE fs_status='1' ";
					$res_get_fs_data	= mysqli_query($db_con, $sql_get_fs_data) or die(mysqli_error($db_con));
					while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
					{
							if($row_get_fs_data['fs_type_value'] == 0)
							{
								if($cart_total >= $row_get_fs_data['fs_start_price'])
								{
									$order_data		.= 'Free Shipping';
									$shipping_charge =$row_get_fs_data['fs_type_value'];
								}
							}
							elseif($row_get_fs_data['fs_type_value'] != 0)
							{
								if(($row_get_fs_data['fs_start_price'] <= $cart_total) && ($cart_total <= $row_get_fs_data['fs_end_price']))
								{
									$order_data				.= $row_get_fs_data['fs_type_value'];
				                    $shipping_charge 		= $row_get_fs_data['fs_type_value'];	
								}
							}
						}	
	       /////////////////////---------end added by satish 14022017------------------////////
				
			
			
			
			$order_total				= $cart_total+$shipping_charge;
			$order_data					.= '<tr><td><b>Order Total</b></td>';
			$order_data					.= '<td>:'.$order_total.'</td>';
			$order_data					.= '</tr>';
			$order_data					.= '<tr><td><b>Payment Status</b></td>';
			if(trim($ord_pay_type) == "Pay Online")
			{
				$order_data					.= '<td>:Prepaid</td>';				
			}
			else
			{
				$order_data					.= '<td>:'.$ord_pay_type.'</td>';								
			}
			$order_data					.= '</tr>';				
			$order_data					.= '</tbody>';
			$order_data					.= '</table>';	
			$order_data					.= '<td></tr>';
			$order_data					.= '</tbody>';
			$order_data					.= '</table>';
			$sql_get_cust_details  		= " SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_email`, `cust_mobile_num`,`add_details`,`add_pincode`,";
			$sql_get_cust_details  		.= " (SELECT `state_name` FROM `state` WHERE `state`=tam.`add_state`) as state_name,";
			$sql_get_cust_details  		.= " (SELECT `city_name` FROM `city` WHERE `city_id`=tam.`add_city`) as city_name";
			$sql_get_cust_details  		.= " FROM `tbl_customer` tc,`tbl_address_master` tam WHERE tc.`cust_id` = tam.add_user_id and ";
			$sql_get_cust_details  		.= " tam.add_user_type = 'customer' and tc.`cust_id` = '".$order_cust_id."' and tam.add_id = '".$order_add_id."' ";
			$result_get_cust_details	= mysqli_query($db_con,$sql_get_cust_details) or die(mysqli_error($db_con));
			$row_get_cust_details		= mysqli_fetch_array($result_get_cust_details);
			$cust_email					= $row_get_cust_details['cust_email'];
			$cust_fname					= $row_get_cust_details['cust_fname'];
			$cust_lname					= $row_get_cust_details['cust_lname'];
			$cust_details_address		= $row_get_cust_details['add_details'];
			$cust_city					= $row_get_cust_details['city_name'];
			$cust_pincode				= $row_get_cust_details['add_pincode'];
			$cust_state					= $row_get_cust_details['state_name'];
			$cust_mobile_number			= $row_get_cust_details['cust_mobile_num'];
				
			$subject					= "Order request : ".$ord_id_show;
			$message_body				= "";
			$message_body 				.= '<table class="" data-module="main Content" height="100" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td><table data-bgcolor="BG Color" height="100" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td><table data-bgcolor="BG Color 01" height="100" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td><table height="100" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td height="100" width="520"><table align="center" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> Order Id : '.$ord_id_show.' </td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-size:14px; color:#494949; font-family:\'Open Sans\', sans-serif; align="left">Hello '.ucwords($vendor_name).'<br><br></td>';		
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left">This is to inform you that a new order has been placed from your storefront on <a href="'.$BaseFolder.'"><b>Planet Educate</b></a>.<br><br>';
			$message_body 				.= '</td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left"> <b>Order Summary:</b><br>Order ID : '.$ord_id_show.'<br>Purchased on  : '.$today_date.'<br><br> ';
			$message_body 				.= '</td>';
			$message_body 				.= '</tr>';			
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left"> <b>Order will be shipped to:</b><br> '.ucwords($cust_fname." ".$cust_lname).':<br><b>Contact details:</b><br>'.$cust_mobile_number.'<br>'.$cust_details_address.',<br>'.$cust_pincode.'<br>'.$cust_city.'<br>'.$cust_state.'<br><br> ';
			$message_body 				.= '</td>';
			$message_body 				.= '</tr>';				
			$message_body 				.= '<tr><td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td></tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left">'.$order_data;
			$message_body 				.= '<br></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr><td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td></tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left" style="color:#545252;"><br><br>';
			$message_body 				.= '<br>If you have any questions, please get in touch with us at <a href="mailto:support@planeteducate.com">support@planeteducate.com</a>';
			$message_body 				.= '</td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '</table></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
			$message_body 				.= '</tr>';			
			$message_body 				.= '</table></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '</table></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '</table></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '</table>';				
			$message 					= mail_template_header()."".$message_body."".mail_template_footer();
			sendEmail($vendor_primary_email,$subject,$message);
			if(trim($vendor_secondary_email) != "")
			{
				sendEmail($vendor_secondary_email,$subject,$message);
			}
			if(trim($vendor_tertiary_email) != "")
			{
				sendEmail($vendor_tertiary_email,$subject,$message);
			}
			sendEmail('cynthia@planeteducate.com',$subject,$message);
			sendEmail('prog3.php@edmission.in',$subject,$message);			
			sendEmail('support@planeteducate.com',$subject,$message);
		}
	}
	return true;
	/*Select last Order and Details*/	
}

// this email entry will be stored in tbl_notification table to track our all emails and smss. [Dn by Prathamesh on 04-10-2016]
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

/* Mail On Order */
function sendOrderMail($ord_id)
{
	global $db_con;
	global $BaseFolder;
	global $date;
	global $min_order_value;
	global $shipping_charge;
	$mydate					= getdate(date("U"));
	$today_date				= "$mydate[weekday], $mydate[month] $mydate[mday], $mydate[year]";
	$date_expected_delivery	= getdate(date("U", strtotime('+7 days')));
	$expected_delivery_date	= "$date_expected_delivery[weekday], $date_expected_delivery[month] $date_expected_delivery[mday], $date_expected_delivery[year]";	
	/*Select last Order and Details*/
	$sql_order_details 		= " SELECT * FROM `tbl_order` WHERE `ord_id` =  '".$ord_id."' ";
	$result_order_details 	= mysqli_query($db_con,$sql_order_details) or die(mysqli_error($db_con));
	$row_order_details		= mysqli_fetch_array($result_order_details);
	$order_cust_id			= $row_order_details['ord_custid'];
	$order_add_id			= $row_order_details['ord_addid'];
	$ord_id_show			= $row_order_details['ord_id_show'];
	$ord_discount			= $row_order_details['ord_discount'];
		
	$sql_get_cart_details 	= " Select * from tbl_cart where cart_orderid = '".$ord_id."' "; 
	$result_get_cart_details = mysqli_query($db_con,$sql_get_cart_details) or die(mysqli_error($db_con));
	$order_data				='<style>th, td { border: 1px solid #ddd;}</style>';
	$order_data				.= '<table style="width:100%;border-collapse: collapse;">';
	$order_data				.= '<thead>';
	$order_data				.= '<th align="center" valign="top" style="width:15%;border: 1px solid black;">Image</th>';
	$order_data             .= '<th align="center" valign="top" style="width:15%;border: 1px solid black;">Model no.</th>';
	$order_data				.= '<th align="center" valign="top" style="width:30%;border: 1px solid black;">Product Name</th>';	
	$order_data				.= '<th align="center" valign="top" style="width:15%;border: 1px solid black;">Price</th>';
	$order_data				.= '<th align="center" valign="top" style="width:10%;border: 1px solid black;">Quantity</th>';
	$order_data				.= '<th align="center" valign="top" style="width:15%;border: 1px solid black;">Total</th>';	
	$order_data				.= '</thead>';
	$order_data				.= '<tbody>';
	$cart_total				= 0;			
	while($row_get_cart_details = mysqli_fetch_array($result_get_cart_details))
	{
		$sql_get_product_details 	= " SELECT `prod_id`, prod_title, `prod_model_number`, `prod_name`, `prod_slug`, `prod_orgid`, `prod_brandid`, `prod_catid`,`prod_subcatid`,`prod_status`, ";
		$sql_get_product_details 	.= " (SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_prodid` = tpm.prod_id and prod_img_type = 'main') as prod_img_file_name  FROM `tbl_products_master` tpm WHERE prod_id = '".$row_get_cart_details['cart_prodid']."' ";
		$result_get_product_details = mysqli_query($db_con,$sql_get_product_details) or die(mysqli_error($db_con));
		$row_get_product_details 	= mysqli_fetch_array($result_get_product_details);
		$prod_imagepath				= $BaseFolder."/images/planet/org".$row_get_product_details['prod_orgid']."/prod_id_".$row_get_product_details['prod_id']."/small/".$row_get_product_details['prod_img_file_name'];
		$product_name				= $row_get_product_details['prod_title'];		
		$order_data					.= '<tr>';
		$order_data					.= '<td align="center" valign="top" style="width:15%;border: 1px solid black;">';
		$order_data					.= '<img src="'.$prod_imagepath.'" alt="'.$product_name.'">';
		$order_data					.= '</td>';	
		$order_data					.= '<td align="center" valign="top" style="width:15%;border: 1px solid black;">';
		$order_data					.=  $row_get_product_details['prod_model_number'];
		$order_data					.= '</td>';		
		$order_data					.= '<td align="center" valign="top" style="width:30%;border: 1px solid black;">';		
		$order_data 				.= '<a href="'.$BaseFolder.'/'.$row_get_product_details['prod_slug'].'-'.$row_get_product_details['prod_id'].'-a'.'">'.$product_name.'</a>';
		$order_data					.= '</td>';
		$order_data					.= '<td align="center" valign="top" style="width:15%;border: 1px solid black;">';
		$order_data					.= $row_get_cart_details['cart_price']/$row_get_cart_details['cart_prodquantity'];
		$order_data					.= '</td>';		
		$order_data					.= '<td align="center" valign="top" style="width:10%;border: 1px solid black;">';
		$order_data					.= $row_get_cart_details['cart_prodquantity'];
		$order_data					.= '</td>';		
		$order_data					.= '<td align="center" valign="top" style="width:15%;border: 1px solid black;">';
		$order_data					.= $row_get_cart_details['cart_price'];
		$order_data					.= '</td>';				
		$order_data					.= '</tr>';
		$cart_total += (int)$row_get_cart_details['cart_price'];
	}
	$order_data	.= '<tr><td>&nbsp;</td>';	
	$order_data	.= '<td>&nbsp;</td><td>&nbsp;</td>';	
	$order_data	.= '<td colspan="2" style="border: 1px solid black;"><b>Sub-Total: </b></td><td align="center" style="border: 1px solid black;">'.$cart_total.'</td><tr>';
	$order_data	.= '<tr><td>&nbsp;</td>';	
	$order_data	.= '<td>&nbsp;</td><td>&nbsp;</td>';	
	$order_data	.= '<td colspan="2" style="border: 1px solid black;"><b>Shipping & Handling: </b></td><td align="center" style="border: 1px solid black;">';			
	/*if($cart_total > $min_order_value)
	{
		$order_data	.= '<span style="color:f00;">Free Shipping</span>';		
		$shipping_charge = 0;		
	}
	else
	{
		$order_data	.= $shipping_charge;
		$shipping_charge = $shipping_charge;
	}*/
	       /////////////////////---------start  added by satish 09032017------------------////////
	                $sql_get_fs_data	= " SELECT * FROM `tbl_free_shipping` WHERE fs_status='1' ";
					$res_get_fs_data	= mysqli_query($db_con, $sql_get_fs_data) or die(mysqli_error($db_con));
					while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
					{
							if($row_get_fs_data['fs_type_value'] == 0)
							{
								if($cart_total >= $row_get_fs_data['fs_start_price'])
								{
									$order_data		.= 'Free Shipping';
									$shipping_charge =$row_get_fs_data['fs_type_value'];
								}
							}
							elseif($row_get_fs_data['fs_type_value'] != 0)
							{
								if(($row_get_fs_data['fs_start_price'] <= $cart_total) && ($cart_total <= $row_get_fs_data['fs_end_price']))
								{
									$order_data				.= $row_get_fs_data['fs_type_value'];
				                    $shipping_charge 		= $row_get_fs_data['fs_type_value'];	
								}
							}
						}	
	       /////////////////////---------end added by satish 09032017------------------////////
	
	
	$order_total	= $cart_total+$shipping_charge;
	$order_total    = $order_total - $ord_discount;
	$order_data	.= '</td></tr>';
	$order_data	.= '<tr><td>&nbsp;</td>';	
	$order_data	.= '<td>&nbsp;</td><td>&nbsp;</td>';	
	$order_data	.= '<td colspan="2" style="border: 1px solid black;"><b>Discount: </b></td><td align="center" style="border: 1px solid black;">'.$ord_discount.'</td><tr>';
	$order_data	.= '<tr><td>&nbsp;</td>';	
	$order_data	.= '<td>&nbsp;</td><td>&nbsp;</td>';	
	$order_data	.= '<td colspan="2" style="border: 1px solid black;"><b>Grand Total: </b></td><td align="center" style="border: 1px solid black;">'.$order_total.'</td><tr>';
	$order_data	.= '</tbody>';
	$order_data	.= '</table>';	
	$sql_get_cust_details   	= " SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_email`, `cust_mobile_num`,`add_details`,`add_pincode`,";
	$sql_get_cust_details   	.= " (SELECT `state_name` FROM `state` WHERE `state`=tam.`add_state`) as state_name,";
	$sql_get_cust_details   	.= " (SELECT `city_name` FROM `city` WHERE `city_id`=tam.`add_city`) as city_name";
	$sql_get_cust_details   	.= " FROM `tbl_customer` tc,`tbl_address_master` tam WHERE tc.`cust_id` = tam.add_user_id and ";
	$sql_get_cust_details   	.= " tam.add_user_type = 'customer' and tc.`cust_id` = '".$order_cust_id."' and tam.add_id = '".$order_add_id."' ";
	$result_get_cust_details 	= mysqli_query($db_con,$sql_get_cust_details) or die(mysqli_error($db_con));
	$row_get_cust_details		= mysqli_fetch_array($result_get_cust_details);
	$cust_email					= $row_get_cust_details['cust_email'];
	$cust_fname					= $row_get_cust_details['cust_fname'];
	$cust_lname					= $row_get_cust_details['cust_lname'];
	$cust_details_address		= $row_get_cust_details['add_details'];
	$cust_city					= $row_get_cust_details['city_name'];
	$cust_pincode				= $row_get_cust_details['add_pincode'];
	$cust_state					= $row_get_cust_details['state_name'];
	$cust_mobile_num			= $row_get_cust_details['cust_mobile_num'];
	
	$subject					= "Order Confimation for Order : ".$ord_id_show;
	$message_body				= "";
	$message_body 				.= '<table class="" data-module="main Content" height="100" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
	$message_body 				.= '<tr>';
	$message_body 				.= '<td><table data-bgcolor="BG Color" height="100" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
	$message_body 				.= '<tr>';
	$message_body 				.= '<td><table data-bgcolor="BG Color 01" height="100" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
	$message_body 				.= '<tr>';
	$message_body 				.= '<td><table height="100" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
	$message_body 				.= '<tr>';
	$message_body 				.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
	$message_body 				.= '</tr>';
	$message_body 				.= '<tr>';
	$message_body 				.= '<td height="100" width="520"><table align="center" border="0" cellpadding="0" cellspacing="0">';
	$message_body 				.= '<tr>';
	$message_body 				.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> Order Id : '.$ord_id_show.' </td>';
	$message_body 				.= '</tr>';
	$message_body 				.= '<tr>';
	$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-size:14px; color:#494949; font-family:\'Open Sans\', sans-serif; align="left">Dear '.ucwords($cust_fname).',<br><br></td>';		
	$message_body .= '</tr>';
	$message_body .= '<tr>';
	$message_body .= '<td data-color="Name" data-size="Name" align="left"> Thank you for shopping at <a href="'.$BaseFolder.'"><b>Planet Educate</b></a>. Your order has been placed successfully.  <a href="'.$BaseFolder.'"><b>Planet Educate</b></a> is preparing your order for shipment.<br><br>';
	$message_body .= '</td>';
	$message_body .= '</tr>';
	$message_body .= '<tr>';
	$message_body .= '<td data-color="Name" data-size="Name" align="left"> Your order can be expected to arrive by: <br>'.$expected_delivery_date.'<br><br> ';
	$message_body .= '</td>';
	$message_body .= '</tr>';
	$message_body .= '<tr>';
	$message_body .= '<td data-color="Name" data-size="Name" align="left"> <b>Order Summary:</b><br>Your Order ID : '.$ord_id_show.'<br>Purchased on  : '.$today_date.'<br><br> ';
	$message_body .= '</td>';
	$message_body .= '</tr>';			
	$message_body .= '<tr>';
	$message_body .= '<td data-color="Name" data-size="Name" align="left"> <b>Your order will be shipped to:</b><br> '.ucwords($cust_fname." ".$cust_lname).':<br> '.$cust_details_address.',<br>'.$cust_pincode.'<br>'.$cust_city.'<br>'.$cust_state.'<br><br> ';
	$message_body .= '</td>';
	$message_body .= '</tr>';	
	$message_body .= '<tr><td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td></tr>';									
	$message_body .= '<tr>';
	$message_body .= '<td data-color="Name" data-size="Name" align="left">'.$order_data;
	$message_body .= '<br></td>';
	$message_body .= '</tr>';
	$message_body .= '<tr><td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td></tr>';											
	$message_body .= '<tr>';
	$message_body .= '<td data-color="Name" data-size="Name" align="left" style="color:#545252;"><br><br> You will receive a shipment confirmation mail once your order has shipped. ';
	$message_body .= '<br>If you have any questions, please get in touch with us at <a href="mailto:support@planeteducate.com">support@planeteducate.com</a>';
	$message_body .= '<br>We hope to see you again!!!<br><br>';
	$message_body .= '</td>';
	$message_body .= '</tr>';
	$message_body .= '</table></td>';
	$message_body .= '</tr>';
	$message_body .= '<tr>';
	$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
	$message_body .= '</tr>';			
	$message_body .= '</table></td>';
	$message_body .= '</tr>';
	$message_body .= '</table></td>';
	$message_body .= '</tr>';
	$message_body .= '</table></td>';
	$message_body .= '</tr>';
	$message_body .= '</table>';			
	
	$message 		= mail_template_header()."".$message_body."".mail_template_footer();	
	$mail_response 	= sendEmail($cust_email,$subject,$message);
	
	$res_insert_into_tbl_notification	= '';
	
	$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_order_details', $message, $cust_email, $cust_mobile_num);
	
	sendEmail('support@planeteducate.com',$subject,$message);
	$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_order_details', $message, 'support@planeteducate.com', '02261572611');
	
	// Send SMS of Order and Details to the Customer
	$sms_text	= '';
	$sms_text	.= 'Thank you for shopping at <a href="'.$BaseFolder.'"><b>Planet Educate</b></a>. Your order has been placed successfully.';
	$sms_text	.= '<br>Your Order ID : '.$ord_id_show.'<br>Purchased on  : '.$today_date;
	
	send_sms_msg($cust_mobile_num, $sms_text);
	$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('SMS_order_details', $sms_text, $cust_email, $cust_mobile_num);
	
	return $mail_response;
	/*Select last Order and Details*/	
}
/* Mail On Order*/

/*Get File Extension*/
function getExtension($str) 
{
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = strtolower(substr($str,$i+1,$l));
	return $ext;
}
/*Get File Extension*/
/*Get File Extension*/
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
	{
		imagepng($dst_img,$filename);
	}
	elseif(!strcmp("gif",$ext))
	{
		imagegif($dst_img,$filename);
	}
	else
	{
		imagejpeg($dst_img,$filename);
	}
	//destroys source and destination images.
	imagedestroy($dst_img);
	imagedestroy($src_img);
}
/*Get File Extension*/
/* Send Offer Email To Customer */
function offerEmailTOCustomer($cust_id)
{
	global $db_con;	
	global $BaseFolder;
	/* check alredy use coupon */
	$first_time_offer_coupon_code	= "EDUCATE10";
	$sql_get_cust_orders 			= " SELECT * FROM `tbl_cart` WHERE `cart_custid` = '".$cust_id."' and `cart_coupon_code` = '".$first_time_offer_coupon_code."' ";
	$result_get_cust_orders			= mysqli_query($db_con,$sql_get_cust_orders) or die($db_con);
	$num_rows_get_cust_orders 		= mysqli_num_rows($result_get_cust_orders);
	if($num_rows_get_cust_orders == 0)
	{	
		$sql_get_customer 			= " SELECT * FROM `tbl_customer` WHERE `cust_id` = '".$cust_id."' ";
		$result_get_customer 		= mysqli_query($db_con,$sql_get_customer) or die(mysqli_error($db_con));
	
		$row_get_customer			= mysqli_fetch_array($result_get_customer);
		$cust_fname					= $row_get_customer['cust_fname'];
		$cust_email					= $row_get_customer['cust_email'];
		$cust_mobile_num			= $row_get_customer['cust_mobile_num'];	
		
		$subject		= " Welcome to Planet Educate.";
		$message_body	= "";
		$message_body 	.= '<table class="" data-module="main Content" height="100" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
			$message_body 	.= '<tr>';
				$message_body 	.= '<td>';
					$message_body 	.= '<table data-bgcolor="BG Color" height="100" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
						$message_body 	.= '<tr>';
							$message_body 	.= '<td>';
								$message_body 	.= '<table data-bgcolor="BG Color 01" height="100" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
									$message_body 	.= '<tr>';
										$message_body 	.= '<td>';
											$message_body 	.= '<table height="100" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
												$message_body 	.= '<tr>';
													$message_body 	.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
												$message_body 	.= '</tr>';
												$message_body 	.= '<tr>';
													$message_body 	.= '<td height="100" width="520">';
														$message_body 	.= '<table align="center" border="0" cellpadding="0" cellspacing="0">';
															$message_body 	.= '<tr>';
																$message_body 	.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-size:14px; color:#494949; font-family:\'Open Sans\', sans-serif; align="left">Dear, '.ucwords($cust_fname).'<br><br></td>';		
															$message_body 	.= '</tr>';
															$message_body 	.= '<tr>';
																$message_body 	.= '<td data-color="Name" data-size="Name" align="center"> Thank you for Verifying your Details at <a href="'.$BaseFolder.'"><b>Planet Educate</b></a>. We are offering you flat 10% Discount on your first purchase.<br>';
																$message_body	.= 'Use Coupon Code';	
																$message_body	.= '</td>';
															$message_body 	.= '</tr>';
															$message_body 	.= '<tr>';
																$message_body 	.= '<td>';
																	$message_body 	.= '<table data-bgcolor="Color Button 01" class="table-button230-center" style="border-radius: 900px;" height="36" width="230" align="center" bgcolor="#5bbc2e" border="0" cellpadding="0" cellspacing="0">';
																		$message_body 	.= '<tr>';
																			$message_body 	.= '<td style="padding: 5px 5px; font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;" valign="middle" align="center"><a align="center" data-color="Text Button 01" data-size="Text Button 01" data-min="6" data-max="20" href="'.$BaseFolder.'" style="font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;">'.$first_time_offer_coupon_code.'</a></td>';
																		$message_body 	.= '</tr>';
																	$message_body 	.= '</table>';
																$message_body 	.= '</td>';
															$message_body 	.= '</tr>';
															$message_body 	.= '<tr>';
																$message_body 	.= '<td data-color="Name" data-size="Name" align="center"> ';
																$message_body	.= 'to avail your discount';
																$message_body	.= '</td>';
															$message_body 	.= '</tr>';				
															$message_body 	.= '<tr>';
																$message_body 	.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
															$message_body 	.= '</tr>';
															$message_body 	.= '<tr>';
																$message_body 	.= '<td data-color="Name" data-size="Name" align="left" style="color:#545252;">';
																$message_body 	.= '<br>If you have any questions, please get in touch with us at <a href="mailto:support@planeteducate.com">support@planeteducate.com</a>';
																$message_body 	.= '<br>We hope to see you again!!!<br><br>';
																$message_body 	.= '</td>';
															$message_body 	.= '</tr>';
														$message_body 	.= '</table>';
													$message_body 	.= '</td>';
												$message_body 	.= '</tr>';
												$message_body 	.= '<tr>';
													$message_body 	.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
												$message_body 	.= '</tr>';			
											$message_body 	.= '</table>';
										$message_body 	.= '</td>';
									$message_body 	.= '</tr>';
								$message_body 	.= '</table>';
							$message_body 	.= '</td>';
						$message_body 	.= '</tr>';
					$message_body 	.= '</table>';
				$message_body 	.= '</td>';
			$message_body 	.= '</tr>';
		$message_body 	.= '</table>';				
		$message 		= mail_template_header()."".$message_body."".mail_template_footer();	
		$mail_response 	= sendEmail($cust_email,$subject,$message);

		$res_insert_into_tbl_notification	= '';
		$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_offers_details', $message, $cust_email, $cust_mobile_num);

		sendEmail('support@planeteducate.com',$subject,$message);
		$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_offers_details', $message, 'support@planeteducate.com', '02261572611');
		
		// Send SMS of Offer SMS to the Customer
		$sms_text	= '';
		$sms_text	.= 'Thank you for Verifying your Details at <a href="'.$BaseFolder.'"><b>Planet Educate</b></a>. We are offering you flat 10% Discount on your first purchase.';
		
		send_sms_msg($cust_mobile_num, $sms_text);
		$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('SMS_offers_details', $sms_text, $cust_email, $cust_mobile_num);
		
		return $mail_response;	
	}
	else
	{
		return false;
	}
	/* check alredy use coupon */
}
/* Send Offer Email To Customer */
function getWordPermutaions($inStr) 
{
	$outArr   = Array();
	$tokenArr = explode(" ", $inStr);
	$pointer  = 0;
	for ($i=0; $i<count($tokenArr); $i++) 
	{
    	$outArr[$pointer] = $tokenArr[$i];
    	$tokenString = $tokenArr[$i];
    	$pointer++; 
    	for ($j=$i+1; $j<count($tokenArr); $j++) 
		{
			$tokenString .= " " . $tokenArr[$j];
			$outArr[$pointer] = $tokenString;
			$pointer++;
		}
	}
	return $outArr;
}

function searchAlgorithm($search_term)
{
	global $db_con;
	$possible_combinations	= getWordPermutaions($search_term);
	$sql_search_term 		= " SELECT prod_id FROM `tbl_search_terms` WHERE ( ";
	$cnt 					= 0;
	foreach($possible_combinations as $word)
	{
		$sql_search_term 	.= " `search_keywords` LIKE '%=>".$word." %' ";
		$cnt++;		
		if($cnt != sizeof($possible_combinations))
		{
			$sql_search_term 	.= " or  ";				
		}		
	}	
	$sql_search_term 	.= " ) and 1=1 ";	
	$result_search_term 	= mysqli_query($db_con,$sql_search_term) or die(mysqli_error($db_con));
	$num_rows_search_term 	= mysqli_num_rows($result_search_term);
	if($num_rows_search_term != 0)
	{
		$product_set			= array();
		while($row_search_term = mysqli_fetch_array($result_search_term))
		{
			array_push($product_set,$row_search_term['prod_id']);
		}
		return $product_set;
	}
	else
	{
		return 0;
	}
}
#================================================================================================================================
# START : Function For Getting the Sum of Cart Discount for the respective customer [DN by Prathamesh on 11-11-2016]
#================================================================================================================================
function getSumCartDiscount($cust_id)
{
	global $db_con;
	
	$sql_get_sum_of_cart_discount	= " SELECT SUM(cart_discount) AS cart_discount FROM tbl_cart WHERE cart_custid='".$cust_id."' AND cart_status='0' ";
	$res_get_sum_of_cart_discount	= mysqli_query($db_con, $sql_get_sum_of_cart_discount) or die(mysqli_error($db_con));
	$row_get_sum_of_cart_discount	= mysqli_fetch_array($res_get_sum_of_cart_discount);
	
	$sum_cart_discount				= $row_get_sum_of_cart_discount['cart_discount'];
	
	return $sum_cart_discount;
}
#================================================================================================================================
# END : Function For Getting the Sum of Cart Discount for the respective customer [DN by Prathamesh on 11-11-2016]
#================================================================================================================================

#================================================================================================================================
# START : Update Product Quentity DN by Prathamesh
#================================================================================================================================
function proceedToUpdateCouponCart($cust_id,$coupon_code)
{
	global $db_con, $datetime;
	
	// get coupon data from coupon 
	$sql_get_coup_data	= " SELECT * FROM `tbl_coupons` WHERE `coup_code`='".$coupon_code."' ";
	$res_get_coup_data	= mysqli_query($db_con, $sql_get_coup_data) or die(mysqli_error($db_con));
	$row_get_coup_data	= mysqli_fetch_array($res_get_coup_data);
	
	$min_purch				= $row_get_coup_data['coup_min_purch'];
	$coup_discount_type		= $row_get_coup_data['coup_discount_type'];
	$coup_id				= $row_get_coup_data['coup_id'];
	$coup_discount_amount	= $row_get_coup_data['coup_discount_amount'];
	
	// get all records of that logged in user from "tbl_cart" table
	$sql_get_cart_cust_data	= " SELECT * FROM `tbl_cart` WHERE `cart_custid`='".$cust_id."' AND `cart_status`='0' ";
	$res_get_cart_cust_data = mysqli_query($db_con, $sql_get_cart_cust_data) or die(mysqli_error($db_con));
	$num_get_cart_cust_data	= mysqli_num_rows($res_get_cart_cust_data);
	
	// Sum of all prod price
	$sql_get_sum_prod_price	= " SELECT SUM( `cart_price` ) AS cart_price FROM `tbl_cart` WHERE `cart_custid`='".$cust_id."' AND `cart_status`='0' ";
	$res_get_sum_prod_price	= mysqli_query($db_con,$sql_get_sum_prod_price) or die(mysqli_error($db_con));
	$row_get_sum_prod_price	= mysqli_fetch_array($res_get_sum_prod_price);
	
	if($row_get_sum_prod_price['cart_price'] >= $min_purch)
	{
		$sum_prod_prie			= $row_get_sum_prod_price['cart_price'];
		
		if($num_get_cart_cust_data != 0)
		{
			while($row_get_cart_cust_data = mysqli_fetch_array($res_get_cart_cust_data))
			{
				$cart_discount_per_prod	= '';
				if($coup_discount_type == 'percent')
				{
					$cart_discount_per_prod	= round($row_get_cart_cust_data['cart_price']*($coup_discount_amount/100));
				}
				elseif($coup_discount_type == 'price')
				{
					$cart_discount_per_prod	= round(($row_get_cart_cust_data['cart_price']/$sum_prod_prie)*$coup_discount_amount);
				}
				
				// Update cart records with the coupon code id and its discount per prod
				$sql_update_each_cart_prod	= " UPDATE `tbl_cart` ";
				$sql_update_each_cart_prod	.= " 	SET `cart_coupon_code`='".$coup_id."', ";
				$sql_update_each_cart_prod	.= " 		`cart_discount`='".$cart_discount_per_prod."', ";
				$sql_update_each_cart_prod	.= " 		`cart_modified`='".$datetime."' ";
				$sql_update_each_cart_prod	.= " WHERE `cart_id`='".$row_get_cart_cust_data['cart_id']."' ";
				$res_update_each_cart_prod	= mysqli_query($db_con, $sql_update_each_cart_prod) or die(mysqli_error($db_con));
			}
			if($res_update_each_cart_prod)
			{
				// Get the Sum of the Cart Discount for the particular customer
				$sum_cart_discount	= getSumCartDiscount($cust_id);	// 106
				
				if($coup_discount_type == 'percent')
				{
					// Get SUM of the Cart Price and then its percent
					$sql_get_sum_of_cart	= " SELECT SUM( `cart_price` ) AS cart_price FROM `tbl_cart` WHERE `cart_custid`='".$cust_id."' AND `cart_status`='0' ";
					$res_get_sum_of_cart	= mysqli_query($db_con, $sql_get_sum_of_cart) or die(mysqli_error($db_con));
					$row_get_sum_of_cart	= mysqli_fetch_array($res_get_sum_of_cart);
					
					$sum_cart_price			= $row_get_sum_of_cart['cart_price'];
					
					$sum_cart_price_percent	= round($sum_cart_price*($coup_discount_amount/100));	// 105
					
					if($sum_cart_discount > $sum_cart_price_percent)	//if(106 > 105)
					{
						$diff_of_discount	= round($sum_cart_discount - $sum_cart_price_percent);
						
						// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
						$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
						$sql_get_max_discount_amount	.= " WHERE `cart_discount` > '".$diff_of_discount."' ";
						$sql_get_max_discount_amount	.= " 	AND `cart_custid`='".$cust_id."' ";
						$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
						$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` DESC ";
						$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
						$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
						$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
						
						$max_discount_amount			= $row_get_max_discount_amount['cart_discount'];
						$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
						
						$final_discounted_amount		= round($max_discount_amount - $diff_of_discount);
						
						// Update the current record of the Cart Table
						$sql_update_cart_record			= " UPDATE `tbl_cart` ";
						$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
						$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
						$sql_update_cart_record			.= " 	AND `cart_custid`='".$cust_id."' ";
						$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
					}
					elseif($sum_cart_discount < $sum_cart_price_percent)	//if(106 > 107)
					{
						$diff_of_discount	= round($sum_cart_price_percent - $sum_cart_discount);
						
						// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
						$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
						$sql_get_max_discount_amount	.= " WHERE `cart_custid`='".$cust_id."' ";
						$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
						$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` ASC ";
						$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
						$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
						$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
						
						$min_discount_amount			= $row_get_max_discount_amount['cart_discount'];
						$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
						
						$final_discounted_amount		= round($min_discount_amount + $diff_of_discount);
						
						// Update the current record of the Cart Table
						$sql_update_cart_record			= " UPDATE `tbl_cart` ";
						$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
						$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
						$sql_update_cart_record			.= " 	AND `cart_custid`='".$cust_id."' ";
						$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
					}
				}
				elseif($coup_discount_type == 'price')
				{
					if($sum_cart_discount > $coup_discount_amount)	// 301 out of 300
					{
						$diff_of_discount	= round($sum_cart_discount - $coup_discount_amount);
						
						// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
						$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
						$sql_get_max_discount_amount	.= " WHERE `cart_discount` > '".$diff_of_discount."' ";
						$sql_get_max_discount_amount	.= " 	AND `cart_custid`='".$cust_id."' ";
						$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
						$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` DESC ";
						$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
						$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
						$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
						
						$max_discount_amount			= $row_get_max_discount_amount['cart_discount'];
						$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
						
						$final_discounted_amount		= round($max_discount_amount - $diff_of_discount);
						
						// Update the current record of the Cart Table
						$sql_update_cart_record			= " UPDATE `tbl_cart` ";
						$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
						$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
						$sql_update_cart_record			.= " 	AND `cart_custid`='".$cust_id."' ";
						$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
					}
					elseif($sum_cart_discount < $coup_discount_amount)	// 299 out of 300
					{
						$diff_of_discount	= round($coup_discount_amount - $sum_cart_discount);
						
						// Diff discount amount have to deduct from the max discount amount in cart for that respective customer
						$sql_get_max_discount_amount	= " SELECT * FROM `tbl_cart` ";
						$sql_get_max_discount_amount	.= " WHERE `cart_custid`='".$cust_id."' ";
						$sql_get_max_discount_amount	.= " 	AND `cart_status`='0' ";
						$sql_get_max_discount_amount	.= " ORDER BY `cart_discount` ASC ";
						$sql_get_max_discount_amount	.= " LIMIT 0 , 1 ";
						$res_get_max_discount_amount 	= mysqli_query($db_con, $sql_get_max_discount_amount) or die(mysqli_error($db_con));
						$row_get_max_discount_amount	= mysqli_fetch_array($res_get_max_discount_amount);
						
						$min_discount_amount			= $row_get_max_discount_amount['cart_discount'];
						$cur_cart_id					= $row_get_max_discount_amount['cart_id'];
						
						$final_discounted_amount		= round($min_discount_amount + $diff_of_discount);
						
						// Update the current record of the Cart Table
						$sql_update_cart_record			= " UPDATE `tbl_cart` ";
						$sql_update_cart_record			.= " 	SET `cart_discount`='".$final_discounted_amount."' ";
						$sql_update_cart_record			.= " WHERE `cart_id`='".$cur_cart_id."' ";
						$sql_update_cart_record			.= " 	AND `cart_custid`='".$cust_id."' ";
						$res_update_cart_record			= mysqli_query($db_con, $sql_update_cart_record) or die(mysqli_error($db_con));
					}
				}
				
				//return "Coupon Code Applied";
				$response_array = array("Success"=>"Success","resp"=>"");
			}
			else
			{
				//return "Coupon Code Not Applied";
				$response_array = array("Success"=>"fail","resp"=>"");
			}
		}
		else
		{
			//return "Cart is empty.";	
			$response_array = array("Success"=>"fail","resp"=>"Cart is empty.");
		}
	}
	else
	{
		//return "Coupon Code Not Applied On Less than 500 Purchase.";			
		$response_array = array("Success"=>"fail","resp"=>"Coupon expired");
	}
	
	return $response_array;
}
#================================================================================================================================
# END : Update Product Quentity DN by Prathamesh
#================================================================================================================================

//=========================================================================================================================
// START : Function for Getting Cart Updated when Cart Coupon Code is not Empty [DN by prathamesh on 09-11-2016]
//=========================================================================================================================
function getCartUpdatedData($cart_custid,$cart_coupon_code)
{
	global $db_con, $datetime;
	
	// Get the details of the User from the cart_custid of the cart table
	$sql_get_user_details	= " SELECT * FROM `tbl_customer` WHERE `cust_id`='".$cart_custid."' ";
	$res_get_user_details	= mysqli_query($db_con, $sql_get_user_details) or die(mysqli_error($db_con));
	$row_get_user_details	= mysqli_fetch_array($res_get_user_details);
	
	$user_email_id			= $row_get_user_details['cust_email'];
	
	// Check such coupon code is exist or not
	$sql_chk_coup_code	= " SELECT * FROM `tbl_coupons` WHERE `coup_id`='".$cart_coupon_code."' AND coup_status='1' ";
	$res_chk_coup_code	= mysqli_query($db_con, $sql_chk_coup_code) or die(mysqli_error($db_con));
	$num_chk_coup_code	= mysqli_num_rows($res_chk_coup_code);
	
	if($num_chk_coup_code != 0)
	{
		// Here we have to check the date range [i.e. start date and end date of the coupon/g.c.]
		$row_chk_coup_code	= mysqli_fetch_array($res_chk_coup_code);
		
		$coup_start_date	= strtotime($row_chk_coup_code['coup_start_date']);
		$coup_end_date		= strtotime($row_chk_coup_code['coup_end_date']);
		$actual_coupon_code	= $row_chk_coup_code['coup_code'];
		
		$remaining_user		= $row_chk_coup_code['coup_left_users'];
		$tot_no_of_user		= $row_chk_coup_code['coup_no_of_users'];
		$type_of_time_used	= $row_chk_coup_code['type_times_use'];
		
		$min_purch_value	= $row_chk_coup_code['coup_min_purch'];
		
		// Check SUM of the Cart Price
		$sql_get_sum_of_cart_price	= " SELECT SUM(`cart_price`) AS cart_price FROM `tbl_cart` WHERE `cart_custid`='".$cart_custid."' AND `cart_status`='0' ";
		$res_get_sum_of_cart_price	= mysqli_query($db_con, $sql_get_sum_of_cart_price) or die(mysqli_error($db_con));
		$row_get_sum_of_cart_price	= mysqli_fetch_array($res_get_sum_of_cart_price);
		
		$sum_of_cart_price			= $row_get_sum_of_cart_price['cart_price'];
		
		if($sum_of_cart_price < $min_purch_value)
		{
			$sql_update_cart_discount 		= " UPDATE `tbl_cart` ";
			$sql_update_cart_discount 		.= " 	SET `cart_coupon_code`= '', ";
			$sql_update_cart_discount 		.= " 		`cart_discount`= '', ";
			$sql_update_cart_discount 		.= " 		`cart_modified`= '".$datetime."' ";
			$sql_update_cart_discount 		.= " WHERE `cart_custid` = '".$cart_custid."' ";
			$sql_update_cart_discount 		.= " 	AND cart_status = 0 ";
			$result_update_cart_discount	= mysqli_query($db_con,$sql_update_cart_discount) or die(mysqli_error($db_con));		
			
			// remove coupon code if applied and cart value become less than 500				
			$response_array = array("Success"=>"Success","resp"=>"Cart Subtotal is less than the discounted amount");								
		}
		else
		{
			if($coup_start_date != '' && $coup_end_date != '')
			{
				$current_date		= strtotime(date("Y-m-d H:i:s"));
			
				if($current_date >= $coup_start_date && $current_date <= $coup_end_date)
				{
					//$response_array	= chkTypeOfTimesUse($row_chk_coup_code['type_times_use'],$row_chk_coup_code['coup_no_of_users'],$row_chk_coup_code['coup_left_users'],$user_email_id,$actual_coupon_code,$response_array);
					$response_array	= chkTypeOfTimesUse_next_time($type_of_time_used,$tot_no_of_user,$remaining_user,$user_email_id,$actual_coupon_code,$cart_custid);
				}
				else
				{
					if($row_chk_coup_code['coup_status'] == 1)
					{
						// Update the status of the coupon
						$sql_update_coup_status	= " UPDATE `tbl_coupons` ";
						$sql_update_coup_status	.= " 	SET `coup_status`='0', ";
						$sql_update_coup_status	.= " 		`coup_modified_date`='".$datetime."' ";
						$sql_update_coup_status	.= " WHERE `coup_id`='".$cart_coupon_code."' ";
						$res_update_coup_status	= mysqli_query($db_con, $sql_update_coup_status) or die(mysqli_error($db_con));
						
						$response_array = array("Success"=>"fail","resp"=>"Coupon expired");
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Coupon expired");		
					}			
				}
			}
			else
			{
				//$response_array	= chkTypeOfTimesUse($row_chk_coup_code['type_times_use'],$row_chk_coup_code['coup_no_of_users'],$row_chk_coup_code['coup_left_users'],$user_email_id,$actual_coupon_code,$response_array);		
				$response_array	= chkTypeOfTimesUse_next_time($type_of_time_used,$tot_no_of_user,$remaining_user,$user_email_id,$actual_coupon_code,$cart_custid);
			}	
		}
	}
	else
	{
		//$response_array = array("Success"=>"fail","resp"=>"Such coupon code is not exist");
		$response_array = array("Success"=>"fail","resp"=>"Coupon expired");
	}
	
	return $response_array;
}
//=========================================================================================================================
// END : Function for Getting Cart Updated when Cart Coupon Code is not Empty [DN by prathamesh on 09-11-2016]
//=========================================================================================================================

//=========================================================================================================================
// START : Function for Checking the type_times_use of the coupon [DN by prathamesh on 09-11-2016]
//=========================================================================================================================
function chkTypeOfTimesUse_next_time($type_of_time_used,$tot_no_of_user,$remaining_user,$user_email_id,$actual_coupon_code,$cart_custid)
{
	global $db_con;
	
	//$response_array	= array();
	
	// Checking type of how many times it will use
	if(strcmp($type_of_time_used, 'one_time_use')===0)			// One Time Use Coupon/g.c.
	{
		if($remaining_user != 0 && $tot_no_of_user > 0 )
		{
			$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
		}
	}
	elseif(strcmp($type_of_time_used, 'unlimited_use')===0)	// Multiple Time Use Coupon/g.c.
	{
		if($remaining_user == 0 && $tot_no_of_user == 0)
		{
			$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
		}	
	}
	elseif(strcmp($type_of_time_used, 'limited_use')===0)		// Limited Time of Use Coupon/g.c.
	{
		if($remaining_user != 0 && $remaining_user >= 1)
		{
			$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
		}	
	}
	
	return $response_array;
}
//=========================================================================================================================
// END : Function for Checking the type_times_use of the coupon [DN by prathamesh on 09-11-2016]
//=========================================================================================================================

//=========================================================================================================================
// START : Function for Updating the Product Quantity [ With its Discount or not ] [DN by prathamesh on 09-11-2016]
//=========================================================================================================================
function updateProdQuantityWithDiscount($prod_quentity,$cart_price,$cart_id,$cart_coupon_code,$cart_custid)
{
	global $db_con;
	
	$sql_update_prod_quantity = " UPDATE `tbl_cart` ";
	$sql_update_prod_quantity .= " 		SET `cart_prodquantity` = '".$prod_quentity."', ";
	$sql_update_prod_quantity .= " 			`cart_price` = '".$cart_price."' ";
	$sql_update_prod_quantity .= " WHERE `cart_id` = '".$cart_id."' ";
	$result_update_prod_quantity = mysqli_query($db_con,$sql_update_prod_quantity) or die(mysqli_error($db_con));
	
	if($cart_coupon_code != '')
	{
		$response_array	= getCartUpdatedData($cart_custid,$cart_coupon_code);
	}
	elseif($cart_coupon_code == '')
	{
		if($result_update_prod_quantity)
		{
			$response_array = array("Success"=>"Success","resp"=>"");
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"");	
		}		
	}
	
	return $response_array;	
}
//=========================================================================================================================
// END : Function for Updating the Product Quantity [ With its Discount or not ] [DN by prathamesh on 09-11-2016]
//=========================================================================================================================
if((isset($obj->update_prod_quentity)) == "1" && isset($obj->update_prod_quentity))// Update Product Quantity
{
	$cart_id 	= trim(mysqli_real_escape_string($db_con,$obj->cart_id));
	$cart_flag	= trim(mysqli_real_escape_string($db_con,$obj->flag));
	
	$response_array	= array();
	
	if($cart_flag != "" && $cart_id != "")
	{	
		// Query for getting the prod details such as Prod Quantity in the Cart, Min-Quantity in the Prod Master, Max-Quantity in the Prod Master
		$sql_get_cart_quantity	= " SELECT tc.cart_prodquantity, tc.cart_unit AS cart_prod_price, tc.cart_coupon_code, tc.cart_custid, ";
		$sql_get_cart_quantity	.= " tpm.prod_min_quantity AS prod_min_quantity, tpm.prod_max_quantity AS prod_max_quantity ";
		$sql_get_cart_quantity	.= " FROM tbl_cart AS tc INNER JOIN tbl_products_master AS tpm ";
		$sql_get_cart_quantity	.= "	ON tc.cart_prodid=tpm.prod_id ";
		$sql_get_cart_quantity	.= " WHERE tc.cart_id='".$cart_id."' ";
		
		$result_get_cart_quentity 	= mysqli_query($db_con,$sql_get_cart_quantity) or die(mysqli_error($db_con));
		$num_rows_get_cart_quentity = mysqli_num_rows($result_get_cart_quentity);
			
		if($num_rows_get_cart_quentity == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"Cart Id not found");
		}
		else
		{		
			$row_get_cart_quantity	= mysqli_fetch_array($result_get_cart_quentity);
			$prod_quentity 			= $row_get_cart_quantity['cart_prodquantity'];	// Cart Prod Quantity
			$prod_price 			= $row_get_cart_quantity['cart_prod_price'];	// Cart Prod Unit Price
			$prod_min_quantity 		= $row_get_cart_quantity['prod_min_quantity'];	// Minimum Quantity From Prod Master						
			$prod_max_quantity		= $row_get_cart_quantity['prod_max_quantity'];	// Maximum Quantity From Prod Master
			$cart_coupon_code		= $row_get_cart_quantity['cart_coupon_code'];	// Coup Code ID [ Not the actual coupon code ]
			$cart_custid			= $row_get_cart_quantity['cart_custid'];		// Cust ID
			
			if($cart_flag == 1)
			{
				$prod_quentity 	= (int)$prod_quentity + 1;	
				$cart_price		= (int)$prod_price * $prod_quentity;
				
				if($prod_quentity <= $prod_max_quantity)
				{
					$response_array	= updateProdQuantityWithDiscount($prod_quentity,$cart_price,$cart_id,$cart_coupon_code,$cart_custid);
				}
				else
				{
					$response_array = array("Success"=>"fail","resp"=>"Maximum quantity exceed");	
				}
			}
			elseif($cart_flag == 0)
			{
				$prod_quentity = (int)$prod_quentity - 1;	
				$cart_price		= (int)$prod_price * $prod_quentity;
										
				if($prod_quentity >= $prod_min_quantity)
				{
					$response_array	= updateProdQuantityWithDiscount($prod_quentity,$cart_price,$cart_id,$cart_coupon_code,$cart_custid);
					
					/*$sql_update_prod_quantity = " UPDATE `tbl_cart` ";
					$sql_update_prod_quantity .= " 		SET `cart_prodquantity` = '".$prod_quentity."', ";
					$sql_update_prod_quantity .= " 			`cart_price` = '".$cart_price."' ";
					$sql_update_prod_quantity .= " WHERE `cart_id` = '".$cart_id."' ";
					$result_update_prod_quantity = mysqli_query($db_con,$sql_update_prod_quantity) or die(mysqli_error($db_con));
					
					if($cart_coupon_code != '')
					{
						// Get the details of the User from the cart_custid of the cart table
						$sql_get_user_details	= " SELECT * FROM `tbl_customer` WHERE `cust_id`='".$cart_custid."' ";
						$res_get_user_details	= mysqli_query($db_con, $sql_get_user_details) or die(mysqli_error($db_con));
						$row_get_user_details	= mysqli_fetch_array($res_get_user_details);
						
						$user_email_id			= $row_get_user_details['cust_email'];
						
						// Check such coupon code is exist or not
						$sql_chk_coup_code	= " SELECT * FROM `tbl_coupons` WHERE `coup_id`='".$cart_coupon_code."' AND coup_status='1' ";
						$res_chk_coup_code	= mysqli_query($db_con, $sql_chk_coup_code) or die(mysqli_error($db_con));
						$num_chk_coup_code	= mysqli_num_rows($res_chk_coup_code);
						
						if($num_chk_coup_code != 0)
						{
							// Here we have to check the date range [i.e. start date and end date of the coupon/g.c.]
							$row_chk_coup_code	= mysqli_fetch_array($res_chk_coup_code);
							
							$coup_start_date	= strtotime($row_chk_coup_code['coup_start_date']);
							$coup_end_date		= strtotime($row_chk_coup_code['coup_end_date']);
							$actual_coupon_code	= $row_chk_coup_code['coup_code'];
							
							$remaining_user		= $row_chk_coup_code['coup_left_users'];
							$tot_no_of_user		= $row_chk_coup_code['coup_no_of_users'];
							
							$min_purch_value	= $row_chk_coup_code['coup_min_purch'];
						
							// Check SUM of the Cart Price
							$sql_get_sum_of_cart_price	= " SELECT SUM(`cart_price`) AS cart_price FROM `tbl_cart` WHERE `cart_custid`='".$cart_custid."' AND `cart_status`='0' ";
							$res_get_sum_of_cart_price	= mysqli_query($db_con, $sql_get_sum_of_cart_price) or die(mysqli_error($db_con));
							$row_get_sum_of_cart_price	= mysqli_fetch_array($res_get_sum_of_cart_price);
							
							$sum_of_cart_price			= $row_get_sum_of_cart_price['cart_price'];
							
							if($sum_of_cart_price < $min_purch_value)
							{
								$sql_update_cart_discount 		= " UPDATE `tbl_cart` ";
								$sql_update_cart_discount 		.= " 	SET `cart_coupon_code`= '', ";
								$sql_update_cart_discount 		.= " 		`cart_discount`= '', ";
								$sql_update_cart_discount 		.= " 		`cart_modified`= '".$datetime."' ";
								$sql_update_cart_discount 		.= " WHERE `cart_custid` = '".$cart_custid."' ";
								$sql_update_cart_discount 		.= " 	AND cart_status = 0 ";
								$result_update_cart_discount	= mysqli_query($db_con,$sql_update_cart_discount) or die(mysqli_error($db_con));		
								
								// remove coupon code if applied and cart value become less than 500				
								$response_array = array("Success"=>"Success","resp"=>"Cart Subtotal is less than the discounted amount");								
							}
							else
							{
								if($coup_start_date != '' && $coup_end_date != '')
								{
									$current_date		= strtotime(date("Y-m-d H:i:s"));
								
									if($current_date >= $coup_start_date && $current_date <= $coup_end_date)
									{
										//$response_array	= chkTypeOfTimesUse($row_chk_coup_code['type_times_use'],$row_chk_coup_code['coup_no_of_users'],$row_chk_coup_code['coup_left_users'],$cust_session,$user_coupon_code,$response_array);
										// Checking type of how many times it will use
										if(strcmp($row_chk_coup_code['type_times_use'], 'one_time_use')===0)		// One Time Use Coupon/g.c.
										{
											if($remaining_user != 0 && $tot_no_of_user > 1)
											{
												$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code,$response_array);
											}
											else
											{
												$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
											}
										}
										elseif(strcmp($row_chk_coup_code['type_times_use'], 'unlimited_use')===0)	// Multiple Time Use Coupon/g.c.
										{
											if($remaining_user == 0 && $tot_no_of_user == 0)
											{
												$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code,$response_array);
											}
											else
											{
												$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
											}	
										}
										elseif(strcmp($row_chk_coup_code['type_times_use'], 'limited_use')===0)		// Limited Time of Use Coupon/g.c.
										{
											if($remaining_user != 0 && $remaining_user >= 1)
											{
												$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code,$response_array);
											}
											else
											{
												$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
											}	
										}
									}
									else
									{
										if($row_chk_coup_code['coup_status'] == 1)
										{
											// Update the status of the coupon
											$sql_update_coup_status	= " UPDATE `tbl_coupons` ";
											$sql_update_coup_status	.= " 	SET `coup_status`='0', ";
											$sql_update_coup_status	.= " 		`coup_modified_date`='".$datetime."' ";
											$sql_update_coup_status	.= " WHERE `coup_id`='".$cart_coupon_code."' ";
											//$sql_update_coup_status	.= " WHERE `coup_code`='".$user_coupon_code."' ";
											$res_update_coup_status	= mysqli_query($db_con, $sql_update_coup_status) or die(mysqli_error($db_con));
											
											$response_array = array("Success"=>"fail","resp"=>"Coupon expired");
										}
										else
										{
											$response_array = array("Success"=>"fail","resp"=>"Coupon expired");		
										}			
									}
								}
								else
								{
									//$response_array	= chkTypeOfTimesUse($row_chk_coup_code['type_times_use'],$row_chk_coup_code['coup_no_of_users'],$row_chk_coup_code['coup_left_users'],$cust_session,$user_coupon_code,$response_array);		
									// Checking type of how many times it will use
									if(strcmp($row_chk_coup_code['type_times_use'], 'one_time_use')===0)			// One Time Use Coupon/g.c.
									{
										if($remaining_user != 0 && $tot_no_of_user >1)
										{
											$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code,$response_array);
										}
										else
										{
											$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
										}
									}
									elseif(strcmp($row_chk_coup_code['type_times_use'], 'unlimited_use')===0)	// Multiple Time Use Coupon/g.c.
									{
										if($remaining_user == 0 && $tot_no_of_user == 0)
										{
											$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code,$response_array);
										}
										else
										{
											$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
										}	
									}
									elseif(strcmp($row_chk_coup_code['type_times_use'], 'limited_use')===0)		// Limited Time of Use Coupon/g.c.
									{
										if($remaining_user != 0 && $remaining_user >= 1)
										{
											$response_array	= proceedToUpdateCouponCart($cart_custid,$actual_coupon_code,$response_array);
										}
										else
										{
											$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
										}	
									}
								}
							}
						}
						else
						{
							//$response_array = array("Success"=>"fail","resp"=>"Such coupon code is not exist");
							$response_array = array("Success"=>"fail","resp"=>"Coupon expired");
						}
					}
					else
					{
						if($result_update_prod_quantity)
						{
							$response_array = array("Success"=>"Success","resp"=>"");	
						}
						else
						{
							$response_array = array("Success"=>"fail","resp"=>"");	
						}	
					}*/
				}
				else
				{
					$response_array = array("Success"=>"Success","resp"=>"");
				}					
			}
		}
		
		/*if($num_rows_get_cart_quentity == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"Cart Id not found");
		}
		else
		{		
			$row_get_cart_quantity 		= mysqli_fetch_array($result_get_cart_quentity);
			$prod_quentity 				= $row_get_cart_quantity['cart_prodquantity'];
			$prod_price 				= $row_get_cart_quantity['cart_prod_price'];	
			$prod_min_quantity 			= $row_get_cart_quantity['prod_min_quantity'];						
			$prod_max_quantity			= $row_get_cart_quantity['prod_max_quantity'];				
			
			if($cart_flag == 1)
			{
				$prod_quentity 	= (int)$prod_quentity + 1;	
				$cart_price		= (int)$prod_price * $prod_quentity;
				if($prod_quentity <= $prod_max_quantity)
				{
					$sql_update_prod_quantity = " UPDATE `tbl_cart` ";
					$sql_update_prod_quantity .= " 		SET `cart_prodquantity` = '".$prod_quentity."', ";
					$sql_update_prod_quantity .= " 			`cart_price` = '".$cart_price."' ";
					$sql_update_prod_quantity .= " WHERE `cart_id` = '".$cart_id."' ";
					$result_update_prod_quantity = mysqli_query($db_con,$sql_update_prod_quantity) or die(mysqli_error($db_con));
					
					if($result_update_prod_quantity)
					{
						$response_array = array("Success"=>"Success","resp"=>"");
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"");	
					}					
				}
				else
				{
					$response_array = array("Success"=>"fail","resp"=>"Maximum quantity exceed");	
				}
			}
			elseif($cart_flag == 0)
			{
				$prod_quentity = (int)$prod_quentity - 1;	
				$cart_price		= (int)$prod_price * $prod_quentity;
											
				if($prod_quentity >= $prod_min_quantity)
				{
					$sql_update_prod_quantity = " UPDATE `tbl_cart` ";
					$sql_update_prod_quantity .= " 		SET `cart_prodquantity` = '".$prod_quentity."', ";
					$sql_update_prod_quantity .= " 			`cart_price` = '".$cart_price."' ";
					$sql_update_prod_quantity .= " WHERE `cart_id` = '".$cart_id."' ";
					$result_update_prod_quantity = mysqli_query($db_con,$sql_update_prod_quantity) or die(mysqli_error($db_con));
					
					if($result_update_prod_quantity)
					{
						$response_array = array("Success"=>"Success","resp"=>"");	
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"");	
					}
				}
				else
				{
					$response_array = array("Success"=>"Success","resp"=>"");
				}					
			}
		}*/
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Parameters");
	}
	echo json_encode($response_array);
}
/* Update Product Quentity*/
/* add data from contact us page */
if((isset($obj->user_contact_us)) == "1" && isset($obj->user_contact_us))// contact us
{
	$conct_name			= mysqli_real_escape_string($db_con,$obj->conct_name);
	$conct_email 		= mysqli_real_escape_string($db_con,$obj->conct_email);
	$conct_mobile_num	= mysqli_real_escape_string($db_con,$obj->conct_mobile_num);
	$conct_sub			= mysqli_real_escape_string($db_con,$obj->conct_sub);
	$conct_msg			= mysqli_real_escape_string($db_con,$obj->conct_msg);	
	$conct_user_ip		= mysqli_real_escape_string($db_con,$obj->conct_user_ip);
	$conct_web_info		= mysqli_real_escape_string($db_con,$obj->conct_web_info);	
	$contact_full_msg   ='<p><b>Contact Person Name: </b>'.$conct_name.'</p>';
	$contact_full_msg  .='<p><b>Contact Email : </b>'.$conct_email.'</p>';
	$contact_full_msg  .='<p><b>Contact Mobile Number : </b>'.$conct_mobile_num.'</p>';
	$contact_full_msg  .='<p><b>Comment: </b>'.$conct_msg.'</p>';
	
	if($conct_name == "" || $conct_email == "" || $conct_mobile_num == "" || $conct_sub == "" || $conct_user_ip == "" || $conct_web_info == "" || $conct_msg == "")
	{
		$table_name			= "tbl_contact_us";
		$new_id				= "conct_id";
		$conct_id 			= getNewId($new_id,$table_name);
				
		$sql_contact_us = " INSERT INTO `tbl_contact_us`(`conct_id`, `conct_name`, `conct_email`, `conct_mobile_num`,";
		$sql_contact_us .= " `conct_sub`, `conct_msg`, `conct_user_ip`, `conct_web_info`, `conct_created`) VALUES ";
		$sql_contact_us .= " ('".$conct_id."','".$conct_name."','".$conct_email."','".$conct_mobile_num."',";
		$sql_contact_us .= " '".$conct_sub."','".$conct_msg."','".$conct_user_ip."','".$conct_web_info."','".$datetime."')";
		$result_contact_us = mysqli_query($db_con,$sql_contact_us) or die(mysqli_error($db_con));
		if($result_contact_us)
		{
			if(sendEmail("support@planeteducate.com",$conct_sub,$contact_full_msg))// mail to planet educate users
			{
				$response_array = array("Success"=>"Success","resp"=>"Thank you.We will shortyly contact you");							
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>"Thank you.We will shortyly contact you");								
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"");		
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Data not received");
	}
	echo json_encode($response_array);
}
/* add data from contact us page */
/* add address from user panel */
if((isset($obj->add_new_address)) == "1" && isset($obj->add_new_address))// add Address
{
	$cust_session 		= mysqli_real_escape_string($db_con,$obj->cust_session);
	$add_address_type 	= mysqli_real_escape_string($db_con,$obj->add_address_type);
	$cust_address 	 	= mysqli_real_escape_string($db_con,$obj->cust_address);
	$cust_country 		= mysqli_real_escape_string($db_con,$obj->cust_country);
	$cust_state 		= mysqli_real_escape_string($db_con,$obj->cust_state);
	$cust_city 	 		= mysqli_real_escape_string($db_con,$obj->cust_city);
	$cust_pincode  		= mysqli_real_escape_string($db_con,$obj->cust_pincode);
	if($cust_session == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Session expired.");
	}
	else
	{
		$sql_get_custid 		= "SELECT * FROM `tbl_customer` where cust_email like '".$cust_session."' ";		
		$result_get_custid 		= mysqli_query($db_con,$sql_get_custid) or die(mysqli_query($db_con));
		$num_rows_get_custid 	= mysqli_num_rows($result_get_custid);
		if($num_rows_get_custid == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"User does not exists.");
		}
		else
		{
			if($add_address_type == "" || $cust_address == "" || $cust_country == "" || $cust_state == "" || $cust_city == "" || $cust_pincode == "")
			{
				$response_array = array("Success"=>"fail","resp"=>"Empty Data.");
			}
			else
			{
				$row_get_custid 	= mysqli_fetch_array($result_get_custid);			
				$cust_id = $row_get_custid['cust_id'];
				if($cust_id == 0)
				{
					$response_array = array("Success"=>"fail","resp"=>"cust id empty");		
				}
				else
				{
					$table_name				= "tbl_address_master";
					$new_id					= "add_id";
					$add_id 				= getNewId($new_id,$table_name);
					$add_lat_long			= "010";
					$sql_insert_new_add 	= " INSERT INTO `tbl_address_master`(`add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`, `add_lat_long`,";
					$sql_insert_new_add 	.= "  `add_user_id`, `add_user_type`, `add_address_type`, `add_status`, `add_created`, `add_created_by`) VALUES ";
					$sql_insert_new_add 	.= "  ('".$add_id."','".$cust_address."','".$cust_state."','".$cust_city."','".$cust_pincode."','".$add_lat_long."',";
					$sql_insert_new_add 	.= "  '".$cust_id."','customer','".$add_address_type."',1,'".$datetime."','".$cust_id."')";
					$result_insert_new_add 	= mysqli_query($db_con,$sql_insert_new_add) or die(mysqli_query($db_con));
					if($result_insert_new_add)
					{
						$response_array = array("Success"=>"Success","resp"=>"Address inserted succesfully");	
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Address insertion Fail");							
					}
				}				
			}
		}
	}
	echo json_encode($response_array);	
}
/* add address from user panel */
/* Load user address */
if((isset($obj->loadAddress)) == "1" && isset($obj->loadAddress))// load Address
{
	$cust_session 	= mysqli_real_escape_string($db_con,$obj->cust_session);
	$page_type 		= mysqli_real_escape_string($db_con,$obj->page_type);//page_type = 0 checkout page
	$address_data	= '';
	if($cust_session == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Session expired.");
	}
	else
	{
		$sql_get_custid 		= "SELECT * FROM `tbl_customer` where cust_email like '".$cust_session."' ";		
		$result_get_custid 		= mysqli_query($db_con,$sql_get_custid) or die(mysqli_query($db_con));
		$num_rows_get_custid 	= mysqli_num_rows($result_get_custid);
		if($num_rows_get_custid == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"User does not exists.");
		}
		else
		{
			$row_get_custid 	= mysqli_fetch_array($result_get_custid);			
			$cust_id = $row_get_custid['cust_id'];
			if($cust_id == 0)
			{
				$response_array = array("Success"=>"fail","resp"=>"cust id empty");		
			}
			else
			{
				$sql_get_address 		= " SELECT `add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`,add_address_type,";
				$sql_get_address 		.= " (SELECT `state_name` FROM `state` WHERE `state` = add_state) as add_state_name, ";		
				$sql_get_address 		.= " (SELECT city_name FROM `city`  WHERE `city_id` = add_city) as add_city_name ";				
				$sql_get_address 		.= " FROM `tbl_address_master` where add_user_id = '".$cust_id."' and add_user_type = 'customer' ";
				$result_get_address 	= mysqli_query($db_con,$sql_get_address) or die(mysqli_error($db_con));
				$num_rows_get_address 	= mysqli_num_rows($result_get_address);
				if($num_rows_get_address == 0)
				{
					$response_array 	= array("Success"=>"fail","address"=>0,"resp"=>"No address");
				}
				else
				{
					//Start the address
					
						
					//$address_data .= '<aside class="widget-flickr">';
					$address_data .= '<hr class="divider-big margin-bottom" />';
					$address_data .= '<div class="columns-col-12">';
					while($row_get_address 	= mysqli_fetch_array($result_get_address))
					{
						//$address_data .= '<li>';
						//$address_data .= '<div class="flickr-container">';
						//$address_data .= '<span>';
						$address_data .= '<div class="columns-col columns-col-3" style="border:1px solid #cfcfcf;border-bottom-right-radius:10px;border-top-left-radius:10px;padding:10px;margin:10px 5px;height:340px">';
						$address_data .= '<div style="height:230px"><div align="center" style="padding:1px 0;font-size:18px;"><b>'.ucwords($row_get_address['add_address_type']).'</b></div>';
						$address_data .= '<div align="left" style="padding:1px 0;overflow-wrap:break-word;"><b>Details: </b>'.$row_get_address['add_details'].'</div>';
						$address_data .= '<div align="left" style="padding:1px 0;"><b>Pincode: </b>'.$row_get_address['add_pincode'].'</div>';							
						$address_data .= '<div align="left" style="padding:1px 0;"><b>City: </b>'.$row_get_address['add_city_name'].'</div>';
						$address_data .= '<div align="left" style="padding:1px 0;"><b>State: </b>'.$row_get_address['add_state_name'].'</div></div>';
						if($page_type == 0)
						{
							$address_data .= '<br><div align="center"><button style="padding:5px 5px;" class="cws-button bt-color-3 border-radius alt my-address-box" id="'.$row_get_address['add_id'].'" onclick="addressSelect(this.id,'.$row_get_address['add_city'].');"><i class="fa fa-square-o"></i>&nbsp;Deliver to this address</button></div>';							
						}
							$address_data .= '<div class="columns-col columns-col-6 col-edit" align="right">';
								$address_data .= '<button style="padding:5px 5px;" title="Edit Address" id="edit_'.$row_get_address['add_id'].'" onclick="editAddress('.$row_get_address['add_id'].', \''.$cust_session.'\');">';	
									$address_data .= '<i class="fa fa-pencil-square-o" style="color:#F27C66;font-size:30px;" aria-hidden="true">';
									$address_data .= '</i>';
								$address_data .= '</button>';
							$address_data .= '</div>';
							$address_data .= '<div class="columns-col columns-col-6 col-edit" align="left">';
								$address_data .= '<button style="padding:5px 5px;" title="Delete Address" id="close_'.$row_get_address['add_id'].'" onclick="removeAddress('.$row_get_address['add_id'].', \''.$cust_session.'\');">';	
									$address_data .= '<i class="fa fa-times-circle-o" style="color:#F27C66;font-size:30px;" aria-hidden="true">';
									$address_data .= '</i>';
								$address_data .= '</button>';
							$address_data .= '</div>';						
						$address_data .= '<div class="clear-fix"></div>';
						$address_data .= '</div>';												
						//$address_data .= '</span>';
						//$address_data .= '</div>';
						//$address_data .= '</li>';				
					}
					$address_data .= '</div>';
					//$address_data .= '</aside>';
					$response_array 	= array("Success"=>"Success","resp"=>utf8_encode($address_data));
				}	// end the address	
			}			
		}		
	}
	echo json_encode($response_array);
}
/* Load user address */

function getUpdatedBrand($prodQuery)
{
	global $db_con;
	
	$sql_updatedquery	= '';
	$sql_updatedquery	.= $prodQuery;
	$sql_updatedquery	.= 'GROUP BY prod_brandid';
	
	$res_updatedquery	= mysqli_query($db_con, $sql_updatedquery) or die(mysqli_error($db_con));
	
	$get_brand_data_list			= '';
	
	while($row_updatedquery = mysqli_fetch_array($res_updatedquery))
	{
		$sql_get_brand	= " SELECT  brand_id, brand_name ";
		$sql_get_brand	.= " FROM tbl_brands_master ";
		$sql_get_brand	.= " where brand_status != 0 ";
		$sql_get_brand	.= " 	AND brand_id = '".$row_updatedquery['prod_brandid']."' ";
		
		$res_get_brand	= mysqli_query($db_con, $sql_get_brand) or die(mysqli_error($db_con));
		$row_get_brand	= mysqli_fetch_array($res_get_brand);
		
		$get_brand_data_list	.= '<div>';
			$get_brand_data_list	.= '<div class="checkbox">';
				$get_brand_data_list	.= '<input type="checkbox" id="'.$row_updatedquery['prod_brandid'].'brands" value="None" name="'.$row_updatedquery['prod_brandid'].'" class="brands">';
				$get_brand_data_list	.= '<label for="'.$row_updatedquery['prod_brandid'].'brands"></label>';
			$get_brand_data_list	.= '</div>';
			$get_brand_data_list	.= '<span style="font-size:15px;">'.ucwords($row_get_brand['brand_name']).'</span>';
		$get_brand_data_list	.= '</div>';
	}
	
	return $get_brand_data_list;
}

//=============================================================================//
//  Start : Done By Satish  15032017 for to get all child category id's
//=============================================================================//

	function get_catid($cat_id,$array)
	{
		global $db_con;
		$sql_all_sub_cat 		= " select * from tbl_category where cat_type = '".$cat_id."' AND cat_name !='none' AND cat_status = 1 ";
		$result_all_sub_cat 	= mysqli_query($db_con,$sql_all_sub_cat) or die(mysqli_error($db_con));
		$num                    =mysqli_num_rows($result_all_sub_cat);
		if($num!=0)
		{
			while($row_all_sub_cat 	= mysqli_fetch_array($result_all_sub_cat))
			{
				array_push($array,$row_all_sub_cat['cat_id']);
				$array = get_catid($row_all_sub_cat['cat_id'],$array);
			}
		}
		return $array;
	}
	
//=============================================================================//
 //  END : Done By Satish  15032017 for to get all child category id's
//=============================================================================//


// ===========================================================================
// START : Original Load Products Commented By Prathamesh On 28112016
// ===========================================================================
if((isset($obj->loadProducts_backup)) == "1" && isset($obj->loadProducts_backup))// load Products levelwise
{	
	$page					= trim(mysqli_real_escape_string($db_con,$obj->page));
	$order_by				= trim(mysqli_real_escape_string($db_con,$obj->order_by));
	
	$sort_by				= array(0=>" prod_id ",1=>" prod_created_by desc ",2=>" prod_recommended_price asc ",3=>" prod_recommended_price desc ");

	/* Exclude out of Stock*/	
	if(isset($obj->out_of_stock))
	{
		$out_of_stock		= trim(mysqli_real_escape_string($db_con,$obj->out_of_stock));		
	}	
	/* Exclude out of Stock*/	
	
	/* Price Filter checked*/
	if(isset($obj->product_price_range))
	{
		$product_price_range= $obj->product_price_range;	
	}
	/* Price Filter checked*/
	
	/* category checked*/	
	if(isset($obj->categoryData))
	{
		$categoryData		= $obj->categoryData;
	}				
	/* category checked*/			
	
	/* level checked*/									
	if(isset($obj->levelData))
	{
		$levelData			= $obj->levelData;
	}								
	/* level checked*/										
	
	/* Filters checked*/										
	if(isset($obj->filters_data))	
	{
		$filters_data		= $obj->filters_data; // don't use trim(mysqli_real_escape_string($db_con,));		
	}
	/* Filters checked*/									

	/* Brands checked*/											
	if(isset($obj->brands))		
	{
		$brands				= $obj->brands; // don't use trim(mysqli_real_escape_string($db_con,));	
	}
	/* Brands checked*/									
	
	/*$response_array = array("Success"=>"fail","resp"=>$categoryData);
	echo json_encode($response_array);
	exit(0);*/
	
	
	$per_page				= 20;
	$start 					= $page * $per_page;
	
	$sql_products			= " SELECT DISTINCT `prod_id`, prod_description, `prod_model_number`, `prod_name`, `prod_title`, `prod_orgid`,";
	$sql_products			.= " `prod_brandid`, `prod_catid`, `prod_subcatid`, `prod_content`, ";
	$sql_products			.= " `prod_quantity`, `prod_list_price`, `prod_recommended_price`, prod_img_file_name, ";
	$sql_products			.= " (SELECT `org_name` FROM `tbl_oraganisation_master` WHERE `org_id`=prod_orgid) as prod_org_name,";
	$sql_products			.= " (SELECT avg(review_star_rating)*20 FROM tbl_review_master WHERE review_status = 1 and review_prod_id = prod_id) as prod_avg_review ";
	$sql_products			.= " FROM `tbl_products_master` AS tpm INNER JOIN `tbl_products_images` AS tpi ";
	$sql_products			.= " 	ON tpm.prod_id = tpi.prod_img_prodid ";
	
	if((isset($obj->levelData))  && (!empty($levelData)))
	{
		$sql_products		.= " INNER JOIN `tbl_product_levels` AS tpl ";
		$sql_products		.= " 	ON tpm.prod_id = tpl.prodlevel_prodid ";
	}
	
	if((isset($obj->filters_data)) && (!empty($filters_data)))
	{
		$sql_products 		.= " INNER JOIN `tbl_product_filters` AS tpf ";
		$sql_products 		.= " 	ON tpm.prod_id = tpf.prodfilt_prodid ";
	}
			
	$sql_products 			.= " WHERE prod_img_type = 'main' and prod_status = 1 ";	
		
	if((isset($obj->filters_data)) && (!empty($filters_data)) && sizeof($filters_data) != 0)
	{
		$cnt 							= 0;			
		$sql_products 					.= " and prod_id = prodfilt_prodid ";
		$sql_products 					.= " and ( ";
		$prodfilt_filtid_parent	 		= "";
		$prodfilt_filtid_child 	 		= "";
		$prodfilt_filtid_sub_child 		= "";
		foreach($filters_data as $filt_id)
		{
			$filt_id_data			 	= explode(":",$filt_id);
			$prodfilt_filtid_parent	 	= $filt_id_data[0];
			$prodfilt_filtid_child 	 	= $filt_id_data[1];
			$prodfilt_filtid_sub_child 	= $filt_id_data[2];
			$sql_products 				.= " ( ";
			if($prodfilt_filtid_parent != 0)
			{
				$sql_products 				.= " prodfilt_filtid_parent = '".$prodfilt_filtid_parent."' ";
			}
			if($prodfilt_filtid_parent != 0 && $prodfilt_filtid_child != 0)
			{
				$sql_products 				.= " and ";
			}
			if($prodfilt_filtid_child != 0)
			{
				$sql_products 				.= " prodfilt_filtid_child = '".$prodfilt_filtid_child."' ";
			}
			if($prodfilt_filtid_parent != 0 && $prodfilt_filtid_sub_child != 0 || $prodfilt_filtid_child != 0 && $prodfilt_filtid_sub_child != 0)
			{
				$sql_products 				.= " and ";				
			}
			if($prodfilt_filtid_sub_child != 0)
			{
				$sql_products 				.= " prodfilt_filtid_sub_child = '".$prodfilt_filtid_sub_child."' ";
			}
			$sql_products 				.= " ) ";					
			++$cnt;					
			if($cnt != sizeof($filters_data))
			{
				$sql_products 			.= " or ";												
			}							
		}			
		$sql_products 					.= " and prodfilt_status = 1 ";				
		$sql_products 					.= " )";
	}
	
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$sql_products	.= " AND  ";
		}
	}
	else
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$i = 1;
			$sql_products 		.= " and ( ";					
			foreach($categoryData as $cat_id)
			{
				$category = explode(":",$cat_id);
				$sql_products 		.= " ( ";			
				$sql_products 		.= " tpm.prod_catid = '".$category[0]."' ";
				if(trim($category[1]) != 0)
				{
					$sql_products 		.= " and tpm.prod_subcatid = '".$category[1]."' ";				
				}
				$sql_products 		.= " ) ";			
				if(sizeof($categoryData) != $i)
				{
					$sql_products 	.= " or ";
				}
				$i++;
			}
			$sql_products 		.= " ) ";			
		}	
	}
	
	if((isset($obj->levelData)) && (!empty($levelData)))
	{
		$i = 1;
		$sql_products 		.= " and (";				
		foreach($levelData as $level_id)
		{
			$level = explode(":",$level_id);
			$sql_products 		.= " ( ";				
			$sql_products 		.= " tpl.prodlevel_levelid_parent = '".$level[0]."' ";
			if(trim($level[1]) != 0)
			{
				$sql_products 		.= " and tpl.prodlevel_levelid_child = '".$level[1]."' ";				
			}
			$sql_products 		.= " )";				
			if(sizeof($levelData) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 			.= " ) and tpl.prodlevel_status = 1 ";				
	}
	
	if($out_of_stock != "" && $out_of_stock != "0")
	{
		$sql_products 					.= " and (tpm.prod_quantity != '' or tpm.prod_quantity != 0) ";
	}
	
	if((isset($obj->brands)) && (!empty($brands)))
	{
		$i = 1;
		$sql_products 		.= " and ( ";		
		foreach($brands as $brand_id)
		{
			$sql_products 		.= " tpm.prod_brandid = ".$brand_id." ";
			if(sizeof($brands) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 		.= " ) ";
	}
	
	if($out_of_stock == "true")
	{
		$sql_products 					.= " and  tpm.prod_recommended_price != '' and tpm.prod_list_price != '' ";
	}
	
	if(isset($obj->product_price_range))
	{
		$arr_size 						= sizeof($product_price_range);
		if($arr_size != 0)			
		{
			$sql_products 				.= " and (";
			$cnt 						= 0;
			foreach($product_price_range as $price_range)
			{ 
				$price_array			= explode("-",$price_range);				
				$min_price				= $price_array[0];
				$max_price				= $price_array[1];
				
				$sql_products 			.= "(prod_recommended_price BETWEEN ".$min_price." AND ".$max_price.") ";
				++$cnt;					
				if($cnt != $arr_size)
				{
					$sql_products 		.= " or ";												
				}
			}	
			$sql_products 				.= ")";
		}
	}
	/* for earching products */
	if(isset($obj->search_term) && $obj->search_term != "")
	{
		$search_term 		= mysqli_real_escape_string($db_con,$obj->search_term);
		$search_result		= searchAlgorithm($search_term);	
		if(sizeof($search_result) != 0)
		{
			$sql_products 	.= " and prod_id IN (";			
			$i = 0;
			foreach($search_result as $keyword)
			{
				$sql_products 	.= "'".$keyword."'";
				$i++;
				if(sizeof($search_result) != $i)
				{
					$sql_products 	.= ",";
				}
			}					
			$sql_products 	.= ")";			
		}
		else
		{
			$sql_products 	.= "and prod_id = 0 ";			
		}				
	}
	/* for earching products */
	
	$brand_data_p	= '';
	if((empty($brands)))
	{
		$brand_data_p 	.= getUpdatedBrand($sql_products);
	}
	
	if($order_by != "")
	{
		foreach($sort_by as $id => $condition)
		{
			if($id == $order_by)
			{
				$sql_products	.= " order by ".$condition;					
			}
		}
	}
	
	$sql_products		.= " LIMIT $start, $per_page ";
	
	$result_get_product	= mysqli_query($db_con,$sql_products) or die(mysqli_error($db_con));
	$num_rows_products	= mysqli_num_rows($result_get_product);
	if($num_rows_products == 0)
	{
		$response_array = array("Success"=>"fail","resp"=>"","query"=>$sql_products,"brand_data_list"=>$brand_data_p);
	}
	else
	{	
		$products_data		= '';
		while($row_get_products = mysqli_fetch_array($result_get_product))
		{			
			$products_data .= '<li class="product" id="prod_id'.$row_get_products['prod_id'].'">';
			$products_data .= '<div class="picture" onclick="location.href=\''.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'\'" style="cursor:pointer">';
			$products_data .= '<div class="ribbon ribbon-blue">';
			$products_data .= '<div class="banner">';
			if($row_get_products['prod_quantity']==0)
				{
			$products_data .= '<div class="text" style="font-size:10px;">Out Of Stock</div>';
				}
		    else
				{
				$products_data .= '<div class="text">New</div>';	
				}		
			$products_data .= '</div>';
			$products_data .= '</div>';
			$products_data .= '<div  align="center" style="width:200px;height:200px;">';
			if(trim($row_get_products['prod_img_file_name']) != "")
			{
				$imagepath 		= '/images/planet/org'.$row_get_products['prod_orgid'].'/prod_id_'.$row_get_products['prod_id'].'/medium/'.$row_get_products['prod_img_file_name'];
				if(file_exists("../".$imagepath))
				{
					$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder."/".$imagepath.'" alt="" src="'.$BaseFolder."/".$imagepath.'" >';
				}
				else
				{
					$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideDown();</script>';
					$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';							
				}						
			}
			else
			{
				$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideUp();</script>';						
				$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';						
			}
			$products_data .= '</div>';
			$products_data .= '<span class="hover-effect"></span>';
			$products_data .= '<div class="link-cont">';
			$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'">View</a>';
/*					$products_data .= '<a href="'.$imagepath.'" class="cws-right fancy cws-slide-left "><i class="fa fa-search"></i></a>';
					$products_data .= '<a href="page-product.php?prod_id='.$row_get_products['prod_id'].'" class=" cws-left cws-slide-right"><i class="fa fa-link"></i></a>';
*/			$products_data .= '</div>';
			$products_data .= '</div>';
			$products_data .= '<div class="product-name" style="height:60px">';
			$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'">'.short($row_get_products['prod_title'],50).'</a>';
			$products_data .= '</div>';
			$products_data .= '<div class="star-rating" title="Rated 4.00 out of 5">';					
			$variable = substr($row_get_products['prod_avg_review'], 0, strpos($row_get_products['prod_avg_review'], ".")); 
			if(trim($variable) != "")
			{
				$products_data .= '<span style="width:'.$variable.'%"><strong class="rating">4.00</strong> out of 5</span>';
			}						
			else
			{
				$products_data .= '<span style="width:50%"><strong class="rating">4.00</strong> out of 5</span>';						
			}
			$products_data .= '</div>';											
			$products_data .= '<span class="price">';
			$products_data .= '<span class="amount">'.current(explode('.', $row_get_products['prod_recommended_price'])).'<sup><i class="fa fa-rupee"></i></sup></span>';
					$products_data .= '</span>';
					$products_data .= '<!--<div class="product-description">';
					$products_data .= '<div class="short-description">';
					$products_data .= '<p>'.short($row_get_products['prod_description'],100).'</p>';
					$products_data .= '</div>-->';
					$products_data .= '<!-- <div class="full-description">';
					$products_data .= '<p>In blandit ultricies euismod.Lobortis erat, sed ullamcorper erat interdum et. Cras volutpat felis id enim vehicula, eu facilisis dui lacinia. Vivamus sollicitudin tristique tellus.</p>';
					$products_data .= '</div> -->';
					$products_data .= '</div>';							
						if($row_get_products['prod_quantity']!=0)
					{					
					$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button border-radius alt smaller" onClick="addToCart(\''.$row_get_products['prod_id'].'\')"> <i class="fa fa-shopping-cart"></i> </a>';
						
					}
					else
					{
					$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button" > Out Of Stock </a>';
						
					}
					$products_data .= '</li>';	
			}
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($products_data),"query"=>$sql_products, "brand_data_list"=>$brand_data_p);//,"data"=>$sql_products);//.$sql_products);
	}							
	echo json_encode($response_array);	
}
// ===========================================================================
// END : Original Load Products Commented By Prathamesh On 28112016
// ===========================================================================

if((isset($obj->loadProducts_16122016)) == "1" && isset($obj->loadProducts_16122016))// load Products levelwise
{	
	$page					= trim(mysqli_real_escape_string($db_con,$obj->page));
	$order_by				= trim(mysqli_real_escape_string($db_con,$obj->order_by));
	
	$sort_by				= array(0=>" prod_id ",1=>" prod_created_by desc ",2=>" prod_recommended_price asc ",3=>" prod_recommended_price desc ");

	/* Exclude out of Stock*/	
	if(isset($obj->out_of_stock))
	{
		$out_of_stock		= trim(mysqli_real_escape_string($db_con,$obj->out_of_stock));		
	}	
	/* Exclude out of Stock*/	
	
	/* Price Filter checked*/
	if(isset($obj->product_price_range))
	{
		$product_price_range= $obj->product_price_range;	
	}
	/* Price Filter checked*/
	
	/* category checked*/	
	if(isset($obj->categoryData))
	{
		$categoryData		= $obj->categoryData;
	}				
	/* category checked*/			
	
	/* level checked*/									
	if(isset($obj->levelData))
	{
		$levelData			= $obj->levelData;
	}								
	/* level checked*/										
	
	/* Filters checked*/										
	if(isset($obj->filters_data))	
	{
		$filters_data		= $obj->filters_data; // don't use trim(mysqli_real_escape_string($db_con,));		
	}
	/* Filters checked*/									

	/* Brands checked*/											
	if(isset($obj->brands))		
	{
		$brands				= $obj->brands; // don't use trim(mysqli_real_escape_string($db_con,));	
	}
	/* Brands checked*/									
	
	/*$response_array = array("Success"=>"fail","resp"=>$categoryData);
	echo json_encode($response_array);
	exit(0);*/
	
	
	//$per_page				= 20;					||__ Commented By Prathamesh On 29-11-2016
	//$start 				= $page * $per_page;	||
	
	$sql_products	= " SELECT DISTINCT tpm.`prod_id`, tpm.`prod_model_number`, tpm.`prod_name`, tpm.`prod_slug`, tpm.`prod_title`, ";
	$sql_products	.= " tpm.`prod_payment_mode`, tpm.`prod_cod_status`, tpm.`prod_description`, tpm.`prod_orgid`, tpm.`prod_brandid`, ";
	$sql_products	.= " tpm.`prod_catid`, tpm.`prod_subcatid`, tpm.`prod_returnable`, tpm.`prod_content`, tpm.`prod_quantity`, ";
	$sql_products	.= " tpm.`prod_min_quantity`, tpm.`prod_max_quantity`, tpm.`prod_list_price`, tpm.`prod_recommended_price`, ";
	$sql_products	.= " tpm.`prod_org_price`, tpm.prod_status, tpi.prod_img_file_name, tom.org_name, ";
	$sql_products	.= " (SELECT avg(trm.review_star_rating)*20 FROM tbl_review_master AS trm WHERE trm.review_status = 1 and trm.review_prod_id = tpm.prod_id) as prod_avg_review ";
	$sql_products	.= " FROM tbl_products_master AS tpm INNER JOIN tbl_products_images AS tpi ";
	$sql_products	.= "	ON tpm.prod_id=tpi.prod_img_prodid INNER JOIN tbl_oraganisation_master AS tom ";
	$sql_products	.= "	ON tpm.prod_orgid=tom.org_id ";
	
	if((isset($obj->levelData))  && (!empty($levelData)))
	{
		$sql_products	.= " INNER JOIN `tbl_product_levels` AS tpl ";
		$sql_products	.= " 	ON tpm.prod_id = tpl.prodlevel_prodid ";
	}
	
	if((isset($obj->filters_data)) && (!empty($filters_data)))
	{
		$sql_products 	.= " INNER JOIN `tbl_product_filters` AS tpf ";
		$sql_products 	.= " 	ON tpm.prod_id = tpf.prodfilt_prodid ";
	}
		
	// START : Added By Prathamesh For comming From Category
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$sql_products	.= " INNER JOIN tbl_product_cats AS tpc ";
			$sql_products	.= " 	ON tpm.prod_id=tpc.prodcat_prodid ";	
		}
	}
	// END : Added By Prathamesh For comming From Category
			
	$sql_products 			.= " WHERE tpi.prod_img_type = 'main' and tpm.prod_status = '1' ";	
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	if((isset($obj->filters_data)) && (!empty($filters_data)) && sizeof($filters_data) != 0)
	{
		$cnt 							= 0;			
		$sql_products 					.= " and prod_id = prodfilt_prodid ";
		$sql_products 					.= " and ( ";
		$prodfilt_filtid_parent	 		= "";
		$prodfilt_filtid_child 	 		= "";
		$prodfilt_filtid_sub_child 		= "";
		foreach($filters_data as $filt_id)
		{
			$filt_id_data			 	= explode(":",$filt_id);
			$prodfilt_filtid_parent	 	= $filt_id_data[0];
			$prodfilt_filtid_child 	 	= $filt_id_data[1];
			$prodfilt_filtid_sub_child 	= $filt_id_data[2];
			$sql_products 				.= " ( ";
			if($prodfilt_filtid_parent != 0)
			{
				$sql_products 				.= " prodfilt_filtid_parent = '".$prodfilt_filtid_parent."' ";
			}
			if($prodfilt_filtid_parent != 0 && $prodfilt_filtid_child != 0)
			{
				$sql_products 				.= " and ";
			}
			if($prodfilt_filtid_child != 0)
			{
				$sql_products 				.= " prodfilt_filtid_child = '".$prodfilt_filtid_child."' ";
			}
			if($prodfilt_filtid_parent != 0 && $prodfilt_filtid_sub_child != 0 || $prodfilt_filtid_child != 0 && $prodfilt_filtid_sub_child != 0)
			{
				$sql_products 				.= " and ";				
			}
			if($prodfilt_filtid_sub_child != 0)
			{
				$sql_products 				.= " prodfilt_filtid_sub_child = '".$prodfilt_filtid_sub_child."' ";
			}
			$sql_products 				.= " ) ";					
			++$cnt;					
			if($cnt != sizeof($filters_data))
			{
				$sql_products 			.= " or ";
				//$sql_products 			.= " AND ";
			}							
		}			
		$sql_products 					.= " and prodfilt_status = 1 ";				
		$sql_products 					.= " )";
	}
	
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$sql_products	.= " AND tpc.prodcat_catid IN (SELECT cat_id ";
			$sql_products	.= " 						   FROM tbl_category ";
			$sql_products	.= " 						   WHERE (cat_type='".$categoryData."' OR cat_id='".$categoryData."') ";
			$sql_products	.= " 								AND cat_name!='none' ";
			$sql_products	.= " 								AND cat_status='1') ";
		}
	}
	else
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$i = 1;
			$sql_products 		.= " and ( ";					
			foreach($categoryData as $cat_id)
			{
				$category = explode(":",$cat_id);
				$sql_products 		.= " ( ";			
				$sql_products 		.= " tpm.prod_catid = '".$category[0]."' ";
				if(trim($category[1]) != 0)
				{
					$sql_products 		.= " and tpm.prod_subcatid = '".$category[1]."' ";				
				}
				$sql_products 		.= " ) ";			
				if(sizeof($categoryData) != $i)
				{
					$sql_products 	.= " or ";
				}
				$i++;
			}
			$sql_products 		.= " ) ";			
		}	
	}
	
	if((isset($obj->levelData)) && (!empty($levelData)))
	{
		$i = 1;
		$sql_products 		.= " and (";				
		foreach($levelData as $level_id)
		{
			$level = explode(":",$level_id);
			$sql_products 		.= " ( ";				
			$sql_products 		.= " tpl.prodlevel_levelid_parent = '".$level[0]."' ";
			if(trim($level[1]) != 0)
			{
				$sql_products 		.= " and tpl.prodlevel_levelid_child = '".$level[1]."' ";				
			}
			$sql_products 		.= " )";				
			if(sizeof($levelData) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 			.= " ) and tpl.prodlevel_status = 1 ";				
	}
	
	if($out_of_stock != "" && $out_of_stock != "0")
	{
		$sql_products 					.= " and (tpm.prod_quantity != '' or tpm.prod_quantity != 0) ";
	}
	
	if((isset($obj->brands)) && (!empty($brands)))
	{
		$i = 1;
		$sql_products 		.= " and ( ";		
		foreach($brands as $brand_id)
		{
			$sql_products 		.= " tpm.prod_brandid = ".$brand_id." ";
			if(sizeof($brands) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 		.= " ) ";
	}
	
	if($out_of_stock == "true")
	{
		$sql_products 					.= " and  tpm.prod_recommended_price != '' and tpm.prod_list_price != '' ";
	}
	
	if(isset($obj->product_price_range))
	{
		$arr_size 						= sizeof($product_price_range);
		if($arr_size != 0)			
		{
			$sql_products 				.= " and (";
			$cnt 						= 0;
			foreach($product_price_range as $price_range)
			{ 
				$price_array			= explode("-",$price_range);				
				$min_price				= $price_array[0];
				$max_price				= $price_array[1];
				
				$sql_products 			.= "(prod_recommended_price BETWEEN ".$min_price." AND ".$max_price.") ";
				++$cnt;					
				if($cnt != $arr_size)
				{
					$sql_products 		.= " or ";												
				}
			}	
			$sql_products 				.= ")";
		}
	}
	/* for earching products */
	if(isset($obj->search_term) && $obj->search_term != "")
	{
		$search_term 		= mysqli_real_escape_string($db_con,$obj->search_term);
		$search_result		= searchAlgorithm($search_term);	
		if(sizeof($search_result) != 0)
		{
			$sql_products 	.= " and prod_id IN (";			
			$i = 0;
			foreach($search_result as $keyword)
			{
				$sql_products 	.= "'".$keyword."'";
				$i++;
				if(sizeof($search_result) != $i)
				{
					$sql_products 	.= ",";
				}
			}					
			$sql_products 	.= ")";			
		}
		else
		{
			$sql_products 	.= "and prod_id = 0 ";			
		}				
	}
	/* for earching products */
	
	$brand_data_p	= '';
	if((empty($brands)))
	{
		$brand_data_p 	.= getUpdatedBrand($sql_products);
	}
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	// =============================================================================================================
	// START : New Pagination [By Prathamesh 28-11-2016]
	// =============================================================================================================
	$per_page		= 20;
	$start_offset   = 0;
	$data_pagination	= '';
	$data_count			= '';
	if($page != "" && $per_page != "")
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset 	+= $page * $per_page;
		$start 			= $page * $per_page;
		
		$data_pagination		= dataPagination($sql_products,$per_page,$start,$cur_page);
		$data_count				= dataCount($sql_products,$per_page,$start,$cur_page);
		/*$response_array = array("Success"=>"fail","resp"=>$data_pagination);
		echo json_encode($response_array);
		exit();*/	
	}
	// =============================================================================================================	
	// END : New Pagination [By Prathamesh 28-11-2016]
	// =============================================================================================================	
	
	if($order_by != "")
	{
		foreach($sort_by as $id => $condition)
		{
			if($id == $order_by)
			{
				$sql_products	.= " order by ".$condition;					
			}
		}
	}
	
	$sql_products		.= " LIMIT $start, $per_page ";
	
	$result_get_product	= mysqli_query($db_con,$sql_products) or die(mysqli_error($db_con));
	$num_rows_products	= mysqli_num_rows($result_get_product);
	if($num_rows_products == 0)
	{
		$data_count	= '<div id="no_more_prod">';
			$data_count	.= '<span>No More Products</span>';
        $data_count	.= '</div>';
		
		$response_array = array("Success"=>"fail","resp"=>"","query"=>$sql_products,"brand_data_list"=>$brand_data_p,"data_count"=>$data_count);
	}
	else
	{	
		$products_data		= '';
		while($row_get_products = mysqli_fetch_array($result_get_product))
		{			
			$products_data .= '<li class="product" id="prod_id'.$row_get_products['prod_id'].'">';
			$products_data .= '<div class="picture" onclick="location.href=\''.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'\'" style="cursor:pointer">';
			$products_data .= '<div class="ribbon ribbon-blue">';
			$products_data .= '<div class="banner">';
			if($row_get_products['prod_quantity']==0)
			{
				$products_data .= '<div class="text" style="font-size:10px;">Out Of Stock</div>';
			}
		    else
			{
				$products_data .= '<div class="text">New</div>';	
			}		
			$products_data .= '</div>';
			$products_data .= '</div>';
			$products_data .= '<div  align="center" style="width:200px;height:200px;">';
			if(trim($row_get_products['prod_img_file_name']) != "")
			{
				$imagepath 		= '/images/planet/org'.$row_get_products['prod_orgid'].'/prod_id_'.$row_get_products['prod_id'].'/medium/'.$row_get_products['prod_img_file_name'];
				if(file_exists("../".$imagepath))
				{
					$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder."/".$imagepath.'" alt="" src="'.$BaseFolder."/".$imagepath.'" >';
				}
				else
				{
					$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideDown();</script>';
					$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';							
				}						
			}
			else
			{
				$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideUp();</script>';						
				$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';						
			}
			$products_data .= '</div>';
			$products_data .= '<span class="hover-effect"></span>';
			$products_data .= '<div class="link-cont">';
			$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'">View</a>';
/*					$products_data .= '<a href="'.$imagepath.'" class="cws-right fancy cws-slide-left "><i class="fa fa-search"></i></a>';
					$products_data .= '<a href="page-product.php?prod_id='.$row_get_products['prod_id'].'" class=" cws-left cws-slide-right"><i class="fa fa-link"></i></a>';
*/			$products_data .= '</div>';
			$products_data .= '</div>';
			$products_data .= '<div class="product-name" style="height:60px">';
			$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'">'.short($row_get_products['prod_title'],50).'</a>';
			$products_data .= '</div>';
			$products_data .= '<div class="star-rating" title="Rated 4.00 out of 5">';					
			$variable = substr($row_get_products['prod_avg_review'], 0, strpos($row_get_products['prod_avg_review'], ".")); 
			if(trim($variable) != "")
			{
				$products_data .= '<span style="width:'.$variable.'%"><strong class="rating">4.00</strong> out of 5</span>';
			}						
			else
			{
				$products_data .= '<span style="width:50%"><strong class="rating">4.00</strong> out of 5</span>';						
			}
			$products_data .= '</div>';											
			$products_data .= '<span class="price">';
			$products_data .= '<span class="amount">'.current(explode('.', $row_get_products['prod_recommended_price'])).'<sup><i class="fa fa-rupee"></i></sup></span>';
			$products_data .= '</span>';
			$products_data .= '<!--<div class="product-description">';
			$products_data .= '<div class="short-description">';
			$products_data .= '<p>'.short($row_get_products['prod_description'],100).'</p>';
			$products_data .= '</div>-->';
			$products_data .= '<!-- <div class="full-description">';
			$products_data .= '<p>In blandit ultricies euismod.Lobortis erat, sed ullamcorper erat interdum et. Cras volutpat felis id enim vehicula, eu facilisis dui lacinia. Vivamus sollicitudin tristique tellus.</p>';
			$products_data .= '</div> -->';
			$products_data .= '</div>';							
			if($row_get_products['prod_quantity']!=0)
			{					
				$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button border-radius alt smaller" onClick="addToCart(\''.$row_get_products['prod_id'].'\')"> <i class="fa fa-shopping-cart"></i> </a>';
			}
			else
			{
				$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button" > Out Of Stock </a>';
			}
			$products_data .= '</li>';	
		}
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($products_data),"query"=>$sql_products, "brand_data_list"=>$brand_data_p, "data_pagination"=>$data_pagination, "data_count"=>$data_count);//,"data"=>$sql_products);//.$sql_products);
	}							
	echo json_encode($response_array);	
}

if((isset($obj->loadProducts)) == "1" && isset($obj->loadProducts))// load Products levelwise
{	
    $chk_time =microtime(true);
   
	$page					= trim(mysqli_real_escape_string($db_con,$obj->page));
	$order_by				= trim(mysqli_real_escape_string($db_con,$obj->order_by));
	$filter_div_data		= '';
	$sort_by				= array(0=>" prod_id ",1=>" prod_created_by desc ",2=>" prod_recommended_price asc ",3=>" prod_recommended_price desc ");

	/* Exclude out of Stock*/	
	if(isset($obj->out_of_stock))
	{
		$out_of_stock		= trim(mysqli_real_escape_string($db_con,$obj->out_of_stock));		
	}	
	/* Exclude out of Stock*/	
	
	/* Price Filter checked*/
	if(isset($obj->product_price_range))
	{
		$product_price_range= $obj->product_price_range;	
	}
	/* Price Filter checked*/
	
	/* category checked*/	
	if(isset($obj->categoryData))
	{
		$categoryData		= $obj->categoryData;
	}				
	/* category checked*/			
	
	/* level checked*/									
	if(isset($obj->levelData))
	{
		$levelData			= $obj->levelData;
	}								
	/* level checked*/										
	
	/* Filters checked*/										
	if(isset($obj->filters_data))	
	{
		$filters_data		= $obj->filters_data; // don't use trim(mysqli_real_escape_string($db_con,));		
	}
	/* Filters checked*/									

	/* Brands checked*/											
	if(isset($obj->brands))		
	{
		$brands				= $obj->brands; // don't use trim(mysqli_real_escape_string($db_con,));	
	}
	/* Brands checked*/									
	
	/*$response_array = array("Success"=>"fail","resp"=>$filters_data);
	echo json_encode($response_array);
	exit();*/
	
	
	//$per_page				= 20;					||__ Commented By Prathamesh On 29-11-2016
	//$start 				= $page * $per_page;	||
	
	$sql_products	= " SELECT DISTINCT tpm.`prod_id`, tpm.`prod_model_number`, tpm.`prod_name`, tpm.`prod_slug`, tpm.`prod_title`, ";
	$sql_products	.= " tpm.`prod_payment_mode`, tpm.`prod_cod_status`, tpm.`prod_description`, tpm.`prod_orgid`, tpm.`prod_brandid`, ";
	$sql_products	.= " tpm.`prod_catid`, tpm.`prod_subcatid`, tpm.`prod_returnable`, tpm.`prod_content`, tpm.`prod_quantity`, ";
	$sql_products	.= " tpm.`prod_min_quantity`, tpm.`prod_max_quantity`, tpm.`prod_list_price`, tpm.`prod_recommended_price`, ";
	$sql_products	.= " tpm.`prod_org_price`, tpm.prod_status, tpi.prod_img_file_name, ";
	$sql_products	.= " (SELECT avg(trm.review_star_rating)*20 FROM tbl_review_master AS trm WHERE trm.review_status = 1 and trm.review_prod_id = tpm.prod_id) as prod_avg_review ";
	$sql_products	.= " FROM tbl_products_master AS tpm INNER JOIN tbl_products_images AS tpi ";
	$sql_products	.= "	ON tpm.prod_id=tpi.prod_img_prodid ";
	
	//$sql_products	.= " INNER JOIN tbl_oraganisation_master AS tom ON tpm.prod_orgid=tom.org_id ";
	
	if((isset($obj->levelData))  && (!empty($levelData)))
	{
		$sql_products	.= " INNER JOIN `tbl_product_levels` AS tpl ";
		$sql_products	.= " 	ON tpm.prod_id = tpl.prodlevel_prodid ";
	}
	
	if((isset($obj->filters_data)) && (!empty($filters_data)))
	{
		$sql_products 	.= " INNER JOIN `tbl_product_filters` AS tpf ";
		$sql_products 	.= " 	ON tpm.prod_id = tpf.prodfilt_prodid ";
	}
		
	// START : Added By Prathamesh For comming From Category
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$sql_products	.= " INNER JOIN tbl_product_cats AS tpc ";
			$sql_products	.= " 	ON tpm.prod_id=tpc.prodcat_prodid ";	
		}
	}
	// END : Added By Prathamesh For comming From Category
			
	$sql_products 			.= " WHERE tpi.prod_img_type = 'main' ";
	$sql_products 			.= " 	AND tpm.prod_status = '1' ";
	$sql_products 			.= " 	AND tpm.prod_recommended_price!='0' ";
	
	/*$response_array = array("Success"=>"fail","resp"=>$obj->filters_data);
	echo json_encode($response_array);
	exit();*/
	
	if((isset($obj->filters_data)) && (!empty($filters_data)) && sizeof($filters_data) != 0)
	{
		$cnt 							= 0;			
		$sql_products 					.= " and tpm.prod_id = tpf.prodfilt_prodid ";
		$sql_products 					.= " and tpf.prodfilt_status = 1 ";
		$sql_products 					.= " and tpf.prodfilt_prodid IN ( ";
		$prodfilt_filtid_parent	 		= "";
		$prodfilt_filtid_child 	 		= "";
		$prodfilt_filtid_sub_child 		= "";
		
		$count			= 1;
		$chkParentFilt	= 0;
		$arr_filt_parent	= array();
		//$sql_products 				.= " ( ";
		foreach($filters_data as $filt_id)
		{
			/*$response_array = array("Success"=>"fail","resp"=>$filt_id);
			echo json_encode($response_array);
			exit();*/
			
			$filt_id_data			 	= explode(":",$filt_id);
			$filt_id_data1				= str_replace(':',',',$filt_id);	// (131:134:135) converts into (131,134,135)
			
			/*$response_array = array("Success"=>"fail","resp"=>$filt_id_data1);
			echo json_encode($response_array);
			exit();*/
			
			$prodfilt_filtid_parent	 	= $filt_id_data[0];
			$prodfilt_filtid_child 	 	= $filt_id_data[1];
			$prodfilt_filtid_sub_child 	= $filt_id_data[2];
			
			// ===============================================================================================================
			// START : Get Parent, Child and Sub-Child Filter Name For Filter Div Data
			// ===============================================================================================================
			// Query For Getting The Parent Filter Name
			$sql_get_parent_filter_name	= " SELECT * FROM `tbl_filters` WHERE `filt_name`!='none' AND `filt_type`='parent' AND `filt_sub_child`='parent' AND `filt_status`='1' AND `filt_id`='".$prodfilt_filtid_parent."' ";
			$res_get_parent_filter_name	= mysqli_query($db_con, $sql_get_parent_filter_name) or die(mysqli_error($db_con));
			$num_get_parent_filter_name	= mysqli_num_rows($res_get_parent_filter_name);
			
			// Query For Getting The Child Filter Name
			$sql_get_child_filter_name	= " SELECT * FROM `tbl_filters` WHERE `filt_name`!='none' AND `filt_sub_child`='child' AND `filt_status`='1' AND `filt_id`='".$prodfilt_filtid_child."' ";
			$res_get_child_filter_name	= mysqli_query($db_con, $sql_get_child_filter_name) or die(mysqli_error($db_con));
			$num_get_child_filter_name	= mysqli_num_rows($res_get_child_filter_name);
			
			// Query For Getting The Sub-Child Filter Name
			$sql_get_sub_child_filter_name	= " SELECT * FROM `tbl_filters` WHERE `filt_status`='1' AND `filt_id`='".$prodfilt_filtid_sub_child."' ";
			$res_get_sub_child_filter_name	= mysqli_query($db_con, $sql_get_sub_child_filter_name) or die(mysqli_error($db_con));
			$num_get_sub_child_filter_name	= mysqli_num_rows($res_get_sub_child_filter_name);
			
			if($num_get_parent_filter_name != 0 && $num_get_child_filter_name != 0)
			{
				$row_get_parent_filter_name		= mysqli_fetch_array($res_get_parent_filter_name);
				$row_get_child_filter_name		= mysqli_fetch_array($res_get_child_filter_name);
				$row_get_sub_child_filter_name	= mysqli_fetch_array($res_get_sub_child_filter_name);
				
				if($row_get_sub_child_filter_name['filt_name'] != 'none')
				{
					$str_filter_div_data		= ucwords($row_get_parent_filter_name['filt_name'].' > '.$row_get_child_filter_name['filt_name'].' > '.$row_get_sub_child_filter_name['filt_name']);
					$sendFromFunction_id		= $prodfilt_filtid_sub_child.'filt';
					$sendFromFunction_cls		= $prodfilt_filtid_child.'filt:'.$prodfilt_filtid_child.'_filter_check_subchild';
					$filterIsChild				= 'sub_child';
				}
				else
				{
					$str_filter_div_data		= ucwords($row_get_parent_filter_name['filt_name'].' > '.$row_get_child_filter_name['filt_name']);
					$sendFromFunction_id		= $prodfilt_filtid_child.'filt';
					$sendFromFunction_cls		= 'filter_check_child';
					$filterIsChild				= 'child';
				}
				
				$filter_div_data	.= '<a href="javascript:void(0);" onClick="removeSelectedFilters(\''.$sendFromFunction_id.'\',\''.$sendFromFunction_cls.'\',\''.$filterIsChild.'\');">';
					$filter_div_data	.= '<div class="cls_div_strikethrough cls_filter_div">';
						$filter_div_data	.= '<div class="cls_filter_inner_div">';	//text-overflow: ellipsis;
							$filter_div_data	.= ucwords('<sup>X</sup>  '.$str_filter_div_data);
						$filter_div_data	.= '</div>';
					$filter_div_data	.= '</div>';
				$filter_div_data	.= '</a>';
			}
			// ===============================================================================================================
			// END : Get Parent, Child and Sub-Child Filter Name For Filter Div Data
			// ===============================================================================================================
			
			if($count == 1)
			{
				$chkParentFilt	= $prodfilt_filtid_parent;
				
				array_push($arr_filt_parent,$prodfilt_filtid_parent);
				
				$sql_products	.= " SELECT DISTINCT(prodfilt_prodid) ";
				$sql_products	.= " FROM `tbl_product_filters` ";
				$sql_products	.= " WHERE (`prodfilt_filtid_parent`,`prodfilt_filtid_child`,`prodfilt_filtid_sub_child`) IN ((".$filt_id_data1.") ";
				
				$count++;
			}
			else
			{
				if($chkParentFilt == $prodfilt_filtid_parent)
				{
					$sql_products	.= " ,(".$filt_id_data1.") ";
					
					$count++;
				}
				else
				{
					$chkParentFilt	= $prodfilt_filtid_parent;
					
					array_push($arr_filt_parent,$prodfilt_filtid_parent);
					
					$sql_products	.= " ) AND prodfilt_prodid IN (SELECT DISTINCT(prodfilt_prodid) ";
					$sql_products	.= " 						   FROM `tbl_product_filters` ";
					$sql_products	.= " 						   WHERE (`prodfilt_filtid_parent`,`prodfilt_filtid_child`,`prodfilt_filtid_sub_child`) IN ((".$filt_id_data1.") ";
					
					$count++;	
				}
			}
		}
		
		/*$response_array = array("Success"=>"fail","resp"=>count($arr_filt_parent));
		echo json_encode($response_array);
		exit();*/
		
		if(count($arr_filt_parent) > 1)
		{
			$sql_products 	.= " ) ";
			for($ii=1; $ii<count($arr_filt_parent);$ii++)
			{
				$sql_products 	.= " ) ";	
			}	
		}
		else
		{
			$sql_products 	.= " ) ";	
		}
		$sql_products 					.= " )";
	}
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			
			
			$sql_products	.= " AND tpc.prodcat_catid IN (SELECT cat_id ";
			$sql_products	.= " 						   FROM tbl_category ";
			$sql_products	.= " 						   WHERE (cat_type='".$categoryData."' OR cat_id='".$categoryData."') ";
			$sql_products	.= " 								AND cat_name!='none' ";
			$sql_products	.= " 								AND cat_status='1') "; 
           
		    
      //=== Strat : Done BY satish ==/////////
           // $array = get_catid($categoryData,$array=array());
		  //array_push($array,$categoryData);
      // ====End :  Done BY satish ===////
            $sql_products	.= " AND tpc.prodcat_catid IN (SELECT tcpm1.`cat_id` ";
			$sql_products	.= " 						   FROM tbl_category AS tc1 INNER JOIN      tbl_category_path_master AS tcpm1 ";
			$sql_products	.= " 								ON tc1.cat_id=tcpm1.cat_id ";
			$sql_products	.= " 						   WHERE tcpm1.path_id='".$categoryData."' ";
			$sql_products	.= " 								AND tc1.cat_name!='none' ";
			$sql_products	.= " 								AND tc1.cat_status='1') ";
			
			   //=== Strat : Done BY satish ==/////////
			//sql_products	.= " AND tpm.prod_id IN 
			//(SELECT DISTINCT prodcat_prodid FROM tbl_product_cats WHERE prodcat_catid IN (".implode(',',$array).") ) ";
			// ====End :  Done BY satish ===////
		}
	}
	else
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$i = 1;
			$sql_products 		.= " and ( ";					
			foreach($categoryData as $cat_id)
			{
				$category = explode(":",$cat_id);
				$sql_products 		.= " ( ";			
				$sql_products 		.= " tpm.prod_catid = '".$category[0]."' ";
				if(trim($category[1]) != 0)
				{
					$sql_products 		.= " and tpm.prod_subcatid = '".$category[1]."' ";				
				}
				$sql_products 		.= " ) ";			
				if(sizeof($categoryData) != $i)
				{
					$sql_products 	.= " or ";
				}
				$i++;
			}
			$sql_products 		.= " ) ";			
		}	
	}
	
	if((isset($obj->levelData)) && (!empty($levelData)))
	{
		$i = 1;
		$sql_products 		.= " and (";				
		foreach($levelData as $level_id)
		{
			$level = explode(":",$level_id);
			
			// ===============================================================================================================
			// START : Get Parent and Child Level Name For Filter Div Data
			// ===============================================================================================================
			/*// Query For Getting the Parent Level Details
			$sql_get_parent_level_details	= " SELECT * FROM `tbl_level` ";
			$sql_get_parent_level_details	.= " WHERE `cat_name`!='none' ";
			$sql_get_parent_level_details	.= " 	AND `cat_status`='1' ";
			$sql_get_parent_level_details	.= " 	AND `cat_type`='parent' ";
			$sql_get_parent_level_details	.= " 	AND `cat_id`='".$level[0]."' ";
			$res_get_parent_level_details	= mysqli_query($db_con, $sql_get_parent_level_details) or die(mysqli_error($db_con));
			$num_get_parent_level_details	= mysqli_num_rows($res_get_parent_level_details);
			
			// Query For Getting the Child Level Details
			$sql_get_child_level_details	= " SELECT * FROM `tbl_level` ";
			$sql_get_child_level_details	.= " WHERE `cat_name`!='none' ";
			$sql_get_child_level_details	.= " 	AND `cat_status`='1' ";
			$sql_get_child_level_details	.= " 	AND `cat_id`='".$level[1]."' ";

			$res_get_child_level_details	= mysqli_query($db_con, $sql_get_child_level_details) or die(mysqli_error($db_con));
			$num_get_child_level_details	= mysqli_num_rows($res_get_child_level_details);
			
			if($num_get_parent_level_details != 0 && $num_get_child_level_details != 0)
			{
				$row_get_parent_level_details	= mysqli_fetch_array($res_get_parent_level_details);
				$row_get_child_level_details	= mysqli_fetch_array($res_get_child_level_details);
				
				$parent_level_name	= $row_get_parent_level_details['cat_name'];
				$child_level_name	= $row_get_child_level_details['cat_name'];
				
				$filter_div_data	.= '<div class="cls_div_strikethrough cls_filter_div">';
					$filter_div_data	.= '<div class="cls_filter_inner_div">';
						$filter_div_data	.= ucwords('<sup>X</sup>  '.$parent_level_name.' > '.$child_level_name);
					$filter_div_data	.= '</div>';
				$filter_div_data	.= '</div>';
			}*/
			// ===============================================================================================================
			// END : Get Brand Name For Filter Div Data
			// ===============================================================================================================
			
			$sql_products 		.= " ( ";				
			$sql_products 		.= " tpl.prodlevel_levelid_parent = '".$level[0]."' ";
			if(trim($level[1]) != 0)
			{
				$sql_products 		.= " and tpl.prodlevel_levelid_child = '".$level[1]."' ";				
			}
			$sql_products 		.= " )";				
			if(sizeof($levelData) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 			.= " ) and tpl.prodlevel_status = 1 ";				
	}
	
	if($out_of_stock != "" && $out_of_stock != "0")
	{
		$sql_products 					.= " and (tpm.prod_quantity != '' or tpm.prod_quantity != 0) ";
	}
	
	if((isset($obj->brands)) && (!empty($brands)))
	{
		$i = 1;
		$sql_products 		.= " and ( ";		
		foreach($brands as $brand_id)
		{
			// ===============================================================================================================
			// START : Get Brand Name For Filter Div Data
			// ===============================================================================================================
			$sql_get_brand_details	= " SELECT * FROM `tbl_brands_master` WHERE `brand_id`='".$brand_id."' AND `brand_status`='1' ";
			$res_get_brand_details	= mysqli_query($db_con, $sql_get_brand_details) or die(mysqli_error($db_con));
			$num_get_brand_details	= mysqli_num_rows($res_get_brand_details);
			
			if($num_get_brand_details != 0)
			{
				$row_get_brand_details	= mysqli_fetch_array($res_get_brand_details);
				
				$brand_name				= $row_get_brand_details['brand_name'];
				
				$filter_div_data	.= '<a href="javascript:void(0);" onClick="removeSelectedBrandPrice(\''.$brand_id.'brands\');">';
					$filter_div_data	.= '<div class="cls_div_strikethrough cls_filter_div" >';
						$filter_div_data	.= '<div class="cls_filter_inner_div">';
							$filter_div_data	.= ucwords('<sup>X</sup>  '.$brand_name);
						$filter_div_data	.= '</div>';
					$filter_div_data	.= '</div>';
				$filter_div_data	.= '</a>';
			}
			// ===============================================================================================================
			// END : Get Brand Name For Filter Div Data
			// ===============================================================================================================			
			
			$sql_products 		.= " tpm.prod_brandid = ".$brand_id." ";
			if(sizeof($brands) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 		.= " ) ";
	}
	
	if($out_of_stock == "true")
	{
		$sql_products 					.= " and  tpm.prod_recommended_price != '' and tpm.prod_list_price != '' ";
	}
	
	if(isset($obj->product_price_range))
	{
		$arr_size 						= sizeof($product_price_range);
		if($arr_size != 0)			
		{
			$sql_products 				.= " and (";
			$cnt 						= 0;
			foreach($product_price_range as $price_range)
			{ 
				// ===============================================================================================================
				// START : Get Price Range For Filter Div Data
				// ===============================================================================================================
				$filter_div_data	.= '<a href="javascript:void(0);" onClick="removeSelectedBrandPrice(\''.$price_range.'price\');">';
					$filter_div_data	.= '<div class="cls_div_strikethrough cls_filter_div">';
						$filter_div_data	.= '<div class="cls_filter_inner_div">';
							$filter_div_data	.= ucwords('<sup>X</sup>  Price Range > '.$price_range);
						$filter_div_data	.= '</div>';
					$filter_div_data	.= '</div>';
				$filter_div_data	.= '</a>';
				// ===============================================================================================================
				// END : Get Price Range For Filter Div Data
				// ===============================================================================================================
			
				$price_array			= explode("-",$price_range);				
				$min_price				= $price_array[0];
				$max_price				= $price_array[1];
				
				$sql_products 			.= "(prod_recommended_price BETWEEN ".$min_price." AND ".$max_price.") ";
				++$cnt;					
				if($cnt != $arr_size)
				{
					$sql_products 		.= " or ";												
				}
			}	
			$sql_products 				.= ")";
		}
	}
	/* for earching products */
	if(isset($obj->search_term) && $obj->search_term != "")
	{
		$search_term 		= mysqli_real_escape_string($db_con,$obj->search_term);
		$search_result		= searchAlgorithm($search_term);	
		if(sizeof($search_result) != 0)
		{
			$sql_products 	.= " and prod_id IN (";			
			$i = 0;
			foreach($search_result as $keyword)
			{
				$sql_products 	.= "'".$keyword."'";
				$i++;
				if(sizeof($search_result) != $i)
				{
					$sql_products 	.= ",";
				}
			}					
			$sql_products 	.= ")";			
		}
		else
		{
			$sql_products 	.= "and prod_id = 0 ";			
		}				
	}
	/* for earching products */
	
	$brand_data_p	= '';
	if((empty($brands)))
	{
		$brand_data_p 	.= getUpdatedBrand($sql_products);
	}
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	// =============================================================================================================
	// START : New Pagination [By Prathamesh 28-11-2016]
	// =============================================================================================================
	$per_page		= 20;
	$start_offset   = 0;
	$data_pagination	= '';
	$data_count			= '';
	if($page != "" && $per_page != "")
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset 	+= $page * $per_page;
		$start 			= $page * $per_page;
		
		$data_pagination		= dataPagination($sql_products,$per_page,$start,$cur_page);
		$data_count				= dataCount($sql_products,$per_page,$start,$cur_page);
		/*$response_array = array("Success"=>"fail","resp"=>$data_pagination);
		echo json_encode($response_array);
		exit();*/	
	}
	// =============================================================================================================	
	// END : New Pagination [By Prathamesh 28-11-2016]
	// =============================================================================================================	
	
	if($order_by != "")
	{
		foreach($sort_by as $id => $condition)
		{
			if($id == $order_by)
			{
				$sql_products	.= " order by ".$condition;					
			}
		}
	}
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	$sql_products		.= " LIMIT $start, $per_page ";
	
	$result_get_product	= mysqli_query($db_con,$sql_products) or die(mysqli_error($db_con));
	$num_rows_products	= mysqli_num_rows($result_get_product);
	if($num_rows_products == 0)
	{
		$data_count	= '<div id="no_more_prod">';
			$data_count	.= '<span>No More Products</span>';
        $data_count	.= '</div>';
		
		$response_array = array("Success"=>"fail","resp"=>"","query"=>$sql_products,"brand_data_list"=>$brand_data_p,"data_count"=>$data_count, "filter_div_data"=>$filter_div_data);
	}
	else
	{	
		$products_data		= '';
		while($row_get_products = mysqli_fetch_array($result_get_product))
		{			
			$products_data .= '<li class="product" id="prod_id'.$row_get_products['prod_id'].'">';
				$products_data .= '<div class="picture" onclick="window.open(\''.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'\',\'_new\');"  style="cursor:pointer">';	// onclick="location.href=\''.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'\'"
					$products_data .= '<div class="ribbon ribbon-blue">';
						$products_data .= '<div class="banner">';
							if($row_get_products['prod_quantity']==0)
							{
								$products_data .= '<div class="text" style="font-size:10px;">Out Of Stock</div>';
							}
							else
							{
								$products_data .= '<div class="text">New</div>';	
							}		
						$products_data .= '</div>';
					$products_data .= '</div>';
					$products_data .= '<div  align="center" style="width:200px;height:200px;">';
						if(trim($row_get_products['prod_img_file_name']) != "")
						{
							//$imagepath 		= '/images/planet/org'.$row_get_products['prod_orgid'].'/prod_id_'.$row_get_products['prod_id'].'/medium/'.$row_get_products['prod_img_file_name'];
							$imagepath 		= 'images/planet/org'.$row_get_products['prod_orgid'].'/prod_id_'.$row_get_products['prod_id'].'/medium/'.$row_get_products['prod_img_file_name'];
							if(file_exists("../".$imagepath))
							{
								$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder."/".$imagepath.'" alt="" src="'.$BaseFolder."/".$imagepath.'" >';
							}
							else
							{
								$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideDown();</script>';
								$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';							
							}						
						}
						else
						{
							$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideUp();</script>';						
							$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';						
						}
					$products_data .= '</div>';
					$products_data .= '<span class="hover-effect"></span>';
					$products_data .= '<div class="link-cont">';
						$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'" target="_new">View</a>';
	/*					$products_data .= '<a href="'.$imagepath.'" class="cws-right fancy cws-slide-left "><i class="fa fa-search"></i></a>';
						$products_data .= '<a href="page-product.php?prod_id='.$row_get_products['prod_id'].'" class=" cws-left cws-slide-right"><i class="fa fa-link"></i></a>';
	*/				$products_data .= '</div>';
				$products_data .= '</div>';
			$products_data .= '<div class="product-name" style="height:60px">';
				$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'" target="_blank">'.short($row_get_products['prod_title'],50).'</a>';
			$products_data .= '</div>';
			$products_data .= '<div class="star-rating" title="Rated 4.00 out of 5">';					
			$variable = substr($row_get_products['prod_avg_review'], 0, strpos($row_get_products['prod_avg_review'], ".")); 
			if(trim($variable) != "")
			{
				$products_data .= '<span style="width:'.$variable.'%"><strong class="rating">4.00</strong> out of 5</span>';
			}						
			else
			{
				$products_data .= '<span style="width:50%"><strong class="rating">4.00</strong> out of 5</span>';						
			}
			$products_data .= '</div>';											
			$products_data .= '<span class="price">';
			$products_data .= '<span class="amount">'.current(explode('.', $row_get_products['prod_recommended_price'])).'<sup><i class="fa fa-rupee"></i></sup></span>';
			$products_data .= '</span>';
			$products_data .= '<!--<div class="product-description">';
			$products_data .= '<div class="short-description">';
			$products_data .= '<p>'.short($row_get_products['prod_description'],100).'</p>';
			$products_data .= '</div>-->';
			$products_data .= '<!-- <div class="full-description">';
			$products_data .= '<p>In blandit ultricies euismod.Lobortis erat, sed ullamcorper erat interdum et. Cras volutpat felis id enim vehicula, eu facilisis dui lacinia. Vivamus sollicitudin tristique tellus.</p>';
			$products_data .= '</div> -->';
			$products_data .= '</div>';							
			if($row_get_products['prod_quantity']!=0)
			{					
				$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button border-radius alt smaller" onClick="addToCart(\''.$row_get_products['prod_id'].'\')"> <i class="fa fa-shopping-cart"></i> </a>';
			}
			else
			{
				$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button" > Out Of Stock </a>';
			}
			$products_data .= '</li>';	
		}
		//$products_data .= microtime(true)-$chk_time;
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($products_data),"query"=>$sql_products, "brand_data_list"=>$brand_data_p, "data_pagination"=>$data_pagination, "data_count"=>$data_count, "filter_div_data"=>$filter_div_data);//,"data"=>$sql_products);//.$sql_products);
	}
	ob_start();	
	echo json_encode($response_array);
						
}

/* Load products according to Brand */
/* user check out */
function checkout_backup_07122016_prathamesh($cust_session)
{
	global $db_con, $datetime;	
	global $min_order_value;
	global $shipping_charge;	
	$sql_get_cust_id 		= " SELECT * from tbl_customer where cust_email = '".$cust_session."' ";
	$result_get_cust_id		= mysqli_query($db_con,$sql_get_cust_id) or die(mysqli_error($db_con));
	$row_get_cust_id		= mysqli_fetch_array($result_get_cust_id);
	$cust_id				= $row_get_cust_id['cust_id'];
	
	$sql_get_cart_data		= " Select sum(cart_price) as ord_total,cart_discount as ord_discount,cart_orderid from tbl_cart where cart_custid = '".$cust_id."' and cart_status != 1 ";
	$result_get_cart_data	= mysqli_query($db_con,$sql_get_cart_data) or die(mysqli_error($db_con));
	$row_get_cart_data		= mysqli_fetch_array($result_get_cart_data);
	$ord_total				= $row_get_cart_data['ord_total'];	
	if($ord_total >= $min_order_value)
	{
		$ord_shipping_charges = 0;
	}
	else
	{
		$ord_shipping_charges = $shipping_charge;
	}	
	$ord_discount			= (int)$row_get_cart_data['ord_discount'];	
	$ord_total				= (int)$ord_total + (int)$ord_shipping_charges - (int)$ord_discount ;
	
	$cart_orderid			= $row_get_cart_data['cart_orderid'];
				
	$table_name				= "tbl_order";
	$new_id					= "ord_id";
	$ord_id 				= getNewId($new_id,$table_name);
	$ord_id_show			= "PEOR".date('dhis').$ord_id;
	
	if($cart_orderid == "")
	{				
		$sql_insert_order 		= " INSERT INTO `tbl_order` (`ord_id`, `ord_id_show`,`ord_custid`, `ord_total`,`ord_shipping_charges`, `ord_discount`, `ord_created`) VALUES ";
		$sql_insert_order 		.= "('".$ord_id."','".$ord_id_show."','".$cust_id."','".$ord_total."','".$ord_shipping_charges."','".$ord_discount."','".$datetime."')";
		$result_insert_order 	= mysqli_query($db_con,$sql_insert_order) or die(mysqli_error($db_con));
		if($result_insert_order)
		{
			$sql_update_cart	= "UPDATE `tbl_cart` SET `cart_orderid`='".$ord_id."',`cart_modified`= '".$datetime."' where cart_custid = '".$cust_id."' and cart_status != 1";
			$result_update_cart	= mysqli_query($db_con,$sql_update_cart) or die(mysqli_error($db_con));	
			if($result_update_cart)			
			{
				return $ord_id;
			}
			else
			{
				return "";
			}
		}	
		else
		{
		return "";
	}					
	}
	else
	{
		return $cart_orderid;
	}
}

function checkout($cust_session)
{
	global $db_con, $datetime;	
	global $min_order_value;
	global $shipping_charge;	
	$sql_get_cust_id 		= " SELECT * from tbl_customer where cust_email = '".$cust_session."' ";
	$result_get_cust_id		= mysqli_query($db_con,$sql_get_cust_id) or die(mysqli_error($db_con));
	$row_get_cust_id		= mysqli_fetch_array($result_get_cust_id);
	$cust_id				= $row_get_cust_id['cust_id'];
	
	/////////////////////---------start added by satish 06012017------------------////////
	$sql_update_cart 		 = " UPDATE `tbl_cart` SET `cart_orderid`='' ";
	$sql_update_cart 		.= " WHERE cart_custid = '".$cust_id."' AND cart_status = '0' ";
	$result_update_cart 	 = mysqli_query($db_con,$sql_update_cart) or die(mysqli_error($db_con));
	
	$sql_delete_ord 		 = " DELETE FROM tbl_order WHERE ord_custid = '".$cust_id."' AND ord_pay_status=0";
	//$sql_delete_ord 		.= " WHERE cart_custid = '".$cust_id."' AND cart_status = '0' ";
	$result_delete_ord 	 = mysqli_query($db_con,$sql_delete_ord) or die(mysqli_error($db_con));
	/////////////////////---------end added by satish 06012017------------------////////
	
	$sql_get_cart_data		= " Select sum(cart_price) as ord_total,SUM(cart_discount) AS ord_discount,cart_orderid from tbl_cart where cart_custid = '".$cust_id."' and cart_status =0 ";
	$result_get_cart_data	= mysqli_query($db_con,$sql_get_cart_data) or die(mysqli_error($db_con));
	$row_get_cart_data		= mysqli_fetch_array($result_get_cart_data);
	$ord_total				= $row_get_cart_data['ord_total'];	
	if($ord_total >= $min_order_value)
	{
		$ord_shipping_charges = 0;
	}
	else
	{
		$ord_shipping_charges = $shipping_charge;
	}	
	
	/////////////////////---------start  added by satish 06012017------------------////////
	$sql_get_fs_data	= " SELECT * FROM `tbl_free_shipping` WHERE fs_status='1' ";
	$res_get_fs_data	= mysqli_query($db_con, $sql_get_fs_data) or die(mysqli_error($db_con));
	
	
	while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
	{
		if($row_get_fs_data['fs_type_value'] == 0)
		{
			if($ord_total >= $row_get_fs_data['fs_start_price'])
			{
				$ord_shipping_charges =$row_get_fs_data['fs_type_value'];
			}
		}
		elseif($row_get_fs_data['fs_type_value'] != 0)
		{
			if(($row_get_fs_data['fs_start_price'] <= $ord_total) && ($ord_total <= $row_get_fs_data['fs_end_price']))
			{
				$ord_shipping_charges =$row_get_fs_data['fs_type_value'];
			}
		}
	}	
	/////////////////////---------end added by satish 06012017------------------////////
	
	$ord_discount			= (int)$row_get_cart_data['ord_discount'];	
	$ord_total				= (int)$ord_total + (int)$ord_shipping_charges - (int)$ord_discount ;
	
	$cart_orderid			= $row_get_cart_data['cart_orderid'];
				
	$table_name				= "tbl_order";
	$new_id					= "ord_id";
	$ord_id 				= getNewId($new_id,$table_name);
       ///////////////------------------start done by satish -----------------------////
	
	$sql_get_todays_order ="SELECT * FROM tbl_order WHERE ord_id_show like Concat('%','PEOR".date('dmy')."','%') ";
	$res_get_todays_order	= mysqli_query($db_con,$sql_get_todays_order) or die(mysqli_error($db_con));
	$num_get_todays_order   = mysqli_num_rows($res_get_todays_order);
	$num_get_todays_order   =$num_get_todays_order+1;
	
	$sql_check_todays_order ="SELECT * FROM tbl_order WHERE ord_id_show like Concat('%','PEOR".date('dmy')."','%') AND ord_custid='".$cust_id."' AND ord_pay_status=0";
	$res_check_todays_order	= mysqli_query($db_con,$sql_check_todays_order) or die(mysqli_error($db_con));
	$num_check_todays_order = mysqli_num_rows($res_check_todays_order);
	if($num_check_todays_order > 0)
	{   
	    $row = mysqli_fetch_array($res_check_todays_order);
	    $old_orderid = explode('-',$row['ord_id_show']);
		$num_get_todays_order = $old_orderid[1];
	}

	
	$ord_id_show			= "PEOR".date('dmyhis').$ord_id;
	$ord_id_show            =$ord_id_show.'-'.sprintf("%04s",$num_get_todays_order);
	//////////////----------end done by satish-----------------/////////////////////
	//$ord_id_show			= "PEOR".date('dmYhis').$ord_id;
	$carttype;
	
	//if($cart_orderid == "")
	if($cart_orderid == "")
	{				
		$sql_insert_order 		= " INSERT INTO `tbl_order` (`ord_id`, `ord_id_show`,`ord_custid`, `ord_total`,`ord_shipping_charges`, `ord_discount`, `ord_created`) VALUES ";
		$sql_insert_order 		.= "('".$ord_id."','".$ord_id_show."','".$cust_id."','".$ord_total."','".$ord_shipping_charges."','".$ord_discount."','".$datetime."')";
		$result_insert_order 	= mysqli_query($db_con,$sql_insert_order) or die(mysqli_error($db_con));
		if($result_insert_order)
		{
			$sql_update_cart	= "UPDATE `tbl_cart` SET `cart_orderid`='".$ord_id."',`cart_type`='incomplete',`cart_modified`= '".$datetime."' where cart_custid = '".$cust_id."' and cart_status = 0";
			$result_update_cart	= mysqli_query($db_con,$sql_update_cart) or die(mysqli_error($db_con));	
			if($result_update_cart)			
			{
				return $ord_id;
			}
			else
			{
				return "";
			}
		}	
		else
		{
		return "";
		}					
	}
	else
	{
		//$sql_insert_order 		= " INSERT INTO `tbl_order` (`ord_id`, `ord_id_show`,`ord_custid`, `ord_total`,`ord_shipping_charges`, `ord_discount`, `ord_created`) VALUES ";
		//$sql_insert_order 		.= "('".$ord_id."','".$ord_id_show."','".$cust_id."','".$ord_total."','".$ord_shipping_charges."','".$ord_discount."','".$datetime."')";
		//$result_insert_order 	= mysqli_query($db_con,$sql_insert_order) or die(mysqli_error($db_con));
		$sql_update_order 		 = " UPDATE `tbl_order` SET `ord_id_show`='".$ord_id_show."',";
		$sql_update_order 		.= "  `ord_total`='".$ord_total."',`ord_shipping_charges`='".$ord_shipping_charges."',";
		$sql_update_order 		.= " `ord_discount`='".$ord_discount."',`ord_modified`='".$datetime."' WHERE ord_id = '".$cart_orderid."' ";
		$result_update_order 	 = mysqli_query($db_con,$sql_update_order) or die(mysqli_error($db_con));
		if($result_update_order)
		{
			$sql_update_cart	= "UPDATE `tbl_cart` SET `cart_orderid`='".$cart_orderid."',`cart_type`='incomplete',`cart_modified`= '".$datetime."' where cart_custid = '".$cust_id."' and cart_status != 1";
			$result_update_cart	= mysqli_query($db_con,$sql_update_cart) or die(mysqli_error($db_con));	
			if($result_update_cart)			
			{
				return $cart_orderid;
			}
			else
			{
				return "";
			}
		}	
		else
		{
		return "";
		}			
		//return $cart_orderid;
	}
}
/* update user coupon code */

function proceedToUpdateCoupon($cust_session,$user_coupon_code)
{
	global $db_con,$cookie_name;
	
	if($cust_session == "")
	{			
		if(isset($_COOKIE[$cookie_name])) 
		{
			$cookie_value	= $_COOKIE[$cookie_name];
			$resp_msg 		= updateCoupon($cookie_value,$user_coupon_code);				
		}
	}
	else
	{
		$sql_get_cust_id 	= " SELECT * from tbl_customer where cust_email = '".$cust_session."' ";
		$result_get_cust_id	= mysqli_query($db_con,$sql_get_cust_id) or die(mysqli_error($db_con));
		$row_get_cust_id	= mysqli_fetch_array($result_get_cust_id);
		$cust_id			= $row_get_cust_id['cust_id'];	
		$resp_msg 			= updateCoupon($cust_id,$user_coupon_code);				
	}
	
	if($resp_msg !="NOT APPLIED")
	{
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($resp_msg));
	}
	else
	{
		$sql_get_coup_data	= " SELECT * FROM `tbl_coupons` WHERE `coup_code`='".$user_coupon_code."' ";
		$res_get_coup_data	= mysqli_query($db_con, $sql_get_coup_data) or die(mysqli_error($db_con));
		$row_get_coup_data	= mysqli_fetch_array($res_get_coup_data);
		$min_purch				= $row_get_coup_data['coup_min_purch'];
		$response_array = array("Success"=>"fail","resp"=>"Coupon Code Not Applied On Less than ".$min_purch." Purchase.");
     }
//	$response_array = array("Success"=>"Success","resp"=>utf8_encode($resp_msg));
	
	return $response_array;
}

function chkTypeOfTimesUse($type_times_use,$tot_no_of_user,$remaining_user,$cust_session,$user_coupon_code,$response_array)
{
	global $db_con;
	
	// Checking type of how many times it will use
	if(strcmp($type_times_use, 'one_time_use')===0)			// One Time Use Coupon/g.c.
	{
		if($remaining_user != 0 && $tot_no_of_user > 1)
		{
			$response_array	= proceedToUpdateCoupon($cust_session,$user_coupon_code,$response_array);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
		}
	}
	elseif(strcmp($type_times_use, 'unlimited_use')===0)	// Multiple Time Use Coupon/g.c.
	{
		if($remaining_user == 0 && $tot_no_of_user == 0)
		{
			$response_array	= proceedToUpdateCoupon($cust_session,$user_coupon_code,$response_array);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
		}	
	}
	elseif(strcmp($type_times_use, 'limited_use')===0)		// Limited Time of Use Coupon/g.c.
	{
		if($remaining_user != 0 && $remaining_user >= 1)
		{
			$response_array	= proceedToUpdateCoupon($cust_session,$user_coupon_code,$response_array);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Coupon expired");	// Limit Over
		}	
	}	
	
	return $response_array;
}

if((isset($obj->update_coupon)) == "1" && isset($obj->update_coupon))// Update Coupon
{
	$response_array				= array();
	$cust_session				= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$user_coupon_code			= trim(mysqli_real_escape_string($db_con,$obj->user_coupon_code));
	
	if($user_coupon_code != "")
	{
		// Check such coupon code is exist or not
		$sql_chk_coup_code	= " SELECT * FROM `tbl_coupons` WHERE `coup_code`='".$user_coupon_code."' AND coup_status='1' ";
		$res_chk_coup_code	= mysqli_query($db_con, $sql_chk_coup_code) or die(mysqli_error($db_con));
		$num_chk_coup_code	= mysqli_num_rows($res_chk_coup_code);
		
		if($num_chk_coup_code != 0)
		{
			// Here we have to check the date range [i.e. start date and end date of the coupon/g.c.]
			$row_chk_coup_code	= mysqli_fetch_array($res_chk_coup_code);
			
			$coup_start_date	= strtotime($row_chk_coup_code['coup_start_date']);
			$coup_end_date		= strtotime($row_chk_coup_code['coup_end_date']);$type_time_use      =$row_chk_coup_code['type_times_use'];
			
			//////-------------START : Done by satish 31012017-------////
			$sql_get_order	= " SELECT * FROM `tbl_cart` WHERE `cart_custid`='".$_SESSION['front_panel']['cust_id']."' AND cart_coupon_code = '".$row_chk_coup_code['coup_id']."' AND cart_status !=0";
		    $res_get_order	= mysqli_query($db_con, $sql_get_order) or die(mysqli_error($db_con));
			$num_get_order	= mysqli_num_rows($res_get_order);
			
			if($type_time_use =="one_time_use")
			{
				 if($num_get_order > 0)
				  {
					  $response_array = array("Success"=>"fail","resp"=>"You have already used this coupon code ");
					  echo json_encode($response_array);
					  exit();
				  }
			}
			if($type_time_use =="unlimited_use" || $type_time_use =="limited_use")
			{
				  if($num_get_order >= $row_chk_coup_code['coup_limit_per_user'] && $type_time_use =="limited_use" )
				  {
					  $response_array = array("Success"=>"fail","resp"=>"You have already used this coupon code ");
					  echo json_encode($response_array);
					  exit();
				  }
			}
			//////-------------END : Done by satish 31012017-------////
			

			
			/*$response_array = array("Success"=>"fail","resp"=>$coup_start_date.'<==>'.$coup_end_date);
			echo json_encode($response_array);
			exit();*/
			
			if($coup_start_date != '' && $coup_end_date != '')
			{
				$current_date		= strtotime(date("Y-m-d H:i:s"));
			
				if($current_date >= $coup_start_date && $current_date <= $coup_end_date)
				{
					$response_array	= chkTypeOfTimesUse($row_chk_coup_code['type_times_use'],$row_chk_coup_code['coup_no_of_users'],$row_chk_coup_code['coup_left_users'],$cust_session,$user_coupon_code,$response_array);
				}
				else
				{
					if($row_chk_coup_code['coup_status'] == 1)
					{
						// Update the status of the coupon
						$sql_update_coup_status	= " UPDATE `tbl_coupons` ";
						$sql_update_coup_status	.= " 	SET `coup_status`='0', ";
						$sql_update_coup_status	.= " 		`coup_modified_date`='".$datetime."' ";
						$sql_update_coup_status	.= " WHERE `coup_code`='".$user_coupon_code."' ";
						$res_update_coup_status	= mysqli_query($db_con, $sql_update_coup_status) or die(mysqli_error($db_con));
						
						$response_array = array("Success"=>"fail","resp"=>"Coupon expired");
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Coupon expired");		
					}			
				}
			}
			else
			{
				$response_array	= chkTypeOfTimesUse($row_chk_coup_code['type_times_use'],$row_chk_coup_code['coup_no_of_users'],$row_chk_coup_code['coup_left_users'],$cust_session,$user_coupon_code,$response_array);		
			}
		}
		else
		{
			//$response_array = array("Success"=>"fail","resp"=>"Such coupon code is not exist");
			$response_array = array("Success"=>"fail","resp"=>"Not a valid coupon");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data");					
	}
	echo json_encode($response_array);
}
/* update user coupon code */
/*  place an order */
if((isset($obj->placeOrder)) == "1" && isset($obj->placeOrder))// add item in cart
{
	$cust_session	= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$add_id			= trim(mysqli_real_escape_string($db_con,$obj->add_id));
	if((isset($obj->payment_mode)))
	{
		$payment_mode	= trim(mysqli_real_escape_string($db_con,$obj->payment_mode));	
	}
	else
	{
		$payment_mode	= '';
	}
	if((isset($obj->pay_online_mode)))
	{
		$pay_online_mode	= trim(mysqli_real_escape_string($db_con,$obj->pay_online_mode));	
	}
	else
	{
		$pay_online_mode	= '';	
	}	
	$ord_comment	= trim(mysqli_real_escape_string($db_con,$obj->ord_comment));
	
	$response_array	= array();
	
	/*$response_array = array("Success"=>"fail","resp"=>'Prathamesh : '.$payment_mode);					
	echo json_encode($response_array);
	exit();*/
	
	if($payment_mode != '' && $pay_online_mode!= '')
	{
		if($cust_session != "" || $ord_id != "" || $add_id != "" ||  $payment_mode != "" )
		{
			$ord_id = checkout($cust_session);	// Fetching order id from the Session
			
			if($ord_id != "")	// If order id exist else order generation fail
			{
				// Fetch order details using order id 
				$sql_get_order = " SELECT * FROM `tbl_order` WHERE `ord_id` = '".$ord_id."' ";
				$result_get_order = mysqli_query($db_con,$sql_get_order) or die(mysqli_error($db_con));
				$num_rows_get_order = mysqli_num_rows($result_get_order);
				
				if($num_rows_get_order == 1)	// if order detail count equals to 1 else order generation fail
				{
					$row_get_order 		= mysqli_fetch_array($result_get_order);
					$ord_total			= $row_get_order['ord_total'];
					$ord_cust_id		= $row_get_order['ord_custid'];
					$ord_id_show		= $row_get_order['ord_id_show'];
					
					/*$response_array = array("Success"=>"fail","resp"=>$ord_total.'<==>'.$ord_cust_id.'<==>'.$ord_id_show);					
					echo json_encode($response_array);
					exit();*/
					
					// =============================================================================================================
					// START : Check the cart table for coupon value [is expire or not] [Dn By Prathamesh 19-12-2016]
					// =============================================================================================================
					$current_date					= strtotime(date("Y-m-d H:i:s"));				
					
					$sql_check_cart_coupon_value	= " SELECT DISTINCT tcou.coup_id, tcou.coup_start_date, tcou.coup_end_date, tcou.coup_status ";
					$sql_check_cart_coupon_value	.= " FROM tbl_cart AS tc INNER JOIN tbl_coupons AS tcou ";
					$sql_check_cart_coupon_value	.= " 	ON tc.cart_coupon_code=tcou.coup_id ";
					$sql_check_cart_coupon_value	.= " WHERE tc.cart_custid='".$ord_cust_id."' ";
					$sql_check_cart_coupon_value	.= " 	AND tc.cart_status='0' ";
					$sql_check_cart_coupon_value	.= " 	AND tc.cart_type='incomplete' ";
					//$sql_check_cart_coupon_value	.= " 	AND tcou.coup_status='1' ";
					$sql_check_cart_coupon_value	.= " 	AND tc.cart_orderid='".$ord_id."' ";
					$res_check_cart_coupon_value	= mysqli_query($db_con, $sql_check_cart_coupon_value) or die(mysqli_error($db_con));
					$num_check_cart_coupon_value	= mysqli_num_rows($res_check_cart_coupon_value);
					
					/*$response_array = array("Success"=>"couponExpired","resp"=>$sql_check_cart_coupon_value);
					echo json_encode($response_array);
					exit();*/
					
					if($num_check_cart_coupon_value != 0)
					{
						$row_check_cart_coupon_value	= mysqli_fetch_array($res_check_cart_coupon_value);
						
						$coupon_id						= $row_check_cart_coupon_value['coup_id'];
						$coup_start_date1				= strtotime($row_check_cart_coupon_value['coup_start_date']);
						$coup_end_date1					= strtotime($row_check_cart_coupon_value['coup_end_date']);
						$coup_status1					= $row_check_cart_coupon_value['coup_status'];
						
						if($coup_status1 == 0)
						{
							//if($current_date <= $coup_start_date1 && $current_date >= $coup_end_date1)
							//{
								// Update The Cart Table for this user
								$sql_update_cart_user_coupon_entry	= " UPDATE `tbl_cart` ";
								$sql_update_cart_user_coupon_entry	.= " 	SET `cart_coupon_code`='', ";
								$sql_update_cart_user_coupon_entry	.= " 		`cart_discount`='0', ";
								$sql_update_cart_user_coupon_entry	.= " 		`cart_modified`='".$datetime."', ";
								$sql_update_cart_user_coupon_entry	.= " 		`cart_type`='abundant', ";
								$sql_update_cart_user_coupon_entry	.= " 		cart_orderid='' ";
								$sql_update_cart_user_coupon_entry	.= " WHERE `cart_custid`='".$ord_cust_id."' ";
								$sql_update_cart_user_coupon_entry	.= " 	AND `cart_status`='0' ";
								$sql_update_cart_user_coupon_entry	.= " 	AND `cart_type`='incomplete' "; 
								$sql_update_cart_user_coupon_entry	.= " 	AND cart_orderid='".$ord_id."' ";
								$res_update_cart_user_coupon_entry	= mysqli_query($db_con, $sql_update_cart_user_coupon_entry) or die(mysqli_error($db_con));
								
								if($res_update_cart_user_coupon_entry)
								{
									$response_array = array("Success"=>"couponExpired","resp"=>"Your coupon has been expired");
									echo json_encode($response_array);
									exit();
								}
							//}
						}
					}
					// =============================================================================================================
					// END : Check the cart table for coupon value [is expire or not] [Dn By Prathamesh 19-12-2016]
					// =============================================================================================================
									
					if($ord_total > $min_order_value)	// if total order is greater than 500 then shipping will be free 
					{
						$shipping_charge = 0;
					}		
					if($payment_mode == "Pay Online")	
					{
						// Updating Payment Mode [i.e. Pay Online OR COD], Order Payment Status, Order Status, Order Add ID, 
						// Order Shipping Charges, Order Comment
						
						$sql_update_order 			= " UPDATE `tbl_order` ";
						$sql_update_order 			.= " 	SET `ord_pay_type`='".$payment_mode."', ";
						$sql_update_order 			.= " 		`ord_pay_status`='0', ";
						$sql_update_order 			.= " 		`ord_status`='1', ";
						$sql_update_order 			.= " 		`ord_addid`='".$add_id."',";
						//$sql_update_order 			.= " 		ord_shipping_charges = '".$shipping_charge."', ";
						$sql_update_order 			.= " 		`ord_modified`='".$datetime."', ";
						$sql_update_order 			.= " 		`ord_comment` = '".$ord_comment."' ";
						$sql_update_order 			.= " WHERE ord_id = '".$ord_id."' ";
						$result_update_order 		= mysqli_query($db_con,$sql_update_order) or die(mysqli_error($db_con));
						if($result_update_order)
						{					
							
						}
						else
						{
							$response_array = array("Success"=>"fail","resp"=>"Order Generation Failed.Please try after some time");							
						}	
						
						// Fetching All the details like First Name, Last Name, Email, Mobile Number								
						$sql_get_customer_details 	= " SELECT `cust_fname`, `cust_lname`, `cust_email`,`cust_mobile_num` FROM `tbl_customer` WHERE `cust_id` = '".$ord_cust_id."' ";
						$result_get_customer_details= mysqli_query($db_con,$sql_get_customer_details) or die(mysqli_error($db_con));
						$row_get_customer_details 	= mysqli_fetch_array($result_get_customer_details);	
						$buyer_name					= ucwords($row_get_customer_details['cust_fname']." ".$row_get_customer_details['cust_lname']);
						$email						= $row_get_customer_details['cust_email'];
						$phone						= $row_get_customer_details['cust_mobile_num'];	
						if($pay_online_mode == 1) //1 = Payu
						{
							// Merchant key here as provided by Payu
							$MERCHANT_KEY 	= "lpBnWPld";
	
							// Merchant Salt as provided by Payu
							$SALT 			= "FJBoYtAoXp";
	
							// End point - change to https://secure.payu.in for LIVE mode						
							
							$PAYU_BASE_URL = "https://payu.in";
							$hash_string = $MERCHANT_KEY.'|'.$ord_id.'|'.$ord_total.'|test|'.$buyer_name.'|'.$email.'|||||||||||'.$SALT;
							$hash = hash('sha512', $hash_string);	
							
							$success_url = $BaseFolder.'/payment/page-order-success.php';
							$failure_url = $BaseFolder.'/payment/page-order-wait.php';
									
							$payudata = '<form method="post" action="https://secure.payu.in/_payment" id="payuPayment">';
							$payudata .= '<input type="hidden" name="key" value="'.$MERCHANT_KEY.'" />';
							$payudata .= '<input type="hidden" name="hash" value="'.$hash.'"/>';
							//$payudata .= '<input type="hidden" name="hashstring" value="'.$hash_string.'"/>';
							$payudata .= '<input type="hidden" name="txnid" value="'.$ord_id.'" />';
							$payudata .= '<input type="hidden" name="amount" value="'.$ord_total.'" />';
							$payudata .= '<input type="hidden" name="firstname" value="'.$buyer_name.'" />';
							$payudata .= '<input type="hidden" name="email" value="'.$email.'" />';
							$payudata .= '<input type="hidden" name="phone" value="'.$phone.'" />';
							$payudata .= '<input type="hidden" name="productinfo" value="test" />';
							$payudata .= '<input type="hidden" name="service_provider" value="payu_paisa" size="64" />';
							$payudata .= '<input type="hidden" name="surl" value="'.$success_url.'" />';
							$payudata .= '<input type="hidden" name="furl" value="'.$failure_url.'" />';
							$payudata .= '</form>';
							
							$response_array = array("Success"=>"Success","resp"=>"Order process","url"=>"payu","paymentData"=>"".utf8_encode($payudata));
							
						}
						elseif($pay_online_mode == 2) //2 = instamojo
						{
							require "../instamojo/instamojo.php";
							$api 				= new Instamojo('e6dfc8393942bf9809efca5c20e615fd','fc22cf6dfdfe00e62b443b1edd1d115e');					
							//$api 				= new Instamojo('df6e22f6929e51e2e04a6d51be6b29e8','d648543bf67309b8116162a0a1bbdfbc');					
							//$api 				= new Instamojo('df6e22f6929e51e2e04a6d51be6b29e8','d648543bf67309b8116162a0a1bbdfbc', 'https://test.instamojo.com/api/1.1/');
							$order_id			= "PEOR".$ord_id;
							$order_title 		= "Payment Link for ".$buyer_name." (Order Id:".$order_id.")";
							$gateway_error 		= "";
							$gateway_url 		= "";
							try 
							{
								$response = $api->linkCreate(array(
								'id'=>''.$order_id.'',
								'title'=>''.$order_title.'',
								'purpose'=>''.$order_id.'',
								'description'=>'Payment Link for Order on Planet Educate product',
								'base_price'=>''.$ord_total.'',
								'buyer_name'=>''.$buyer_name,
								'email'=>''.$email,
								'phone'=>''.$phone,
								'send_email'=>true,
								"allow_repeated_payments"=>false,
								'cover_image'=>$BaseFolder.'/img/pe_logo.png',
								'redirect_url'=>$BaseFolder.'/payment/page-order-success.php',
								'webhook_url'=>$BaseFolder.'/payment/page-order-wait.php'
								));
								$gateway_url = $response['url'];
								//print_r($response);
							}
							catch (Exception $e) 
							{
								$gateway_error = $e->getMessage();
								//print('Error: ' . $e->getMessage());
							}	
							$response_array = array("Success"=>"Success","resp"=>"Order process","url"=>"instamojo","paymentData"=>"".utf8_encode($gateway_url),"url_error"=>"".$gateway_error,"");											
						}	
						////////////////////////////////////////----------------START PAYTM Done By satish ( 31-01-2017)--------------/////////////////////////////	
						elseif($pay_online_mode == 3) //3 = Paytm
						{
							// Merchant key here as provided by Payu
							
							require_once("./lib/config_paytm.php");
						    require_once("./lib/encdec_paytm.php");
							
							$sql_get_order_id_show  = "SELECT ord_id_show FROM tbl_order WHERE ord_id ='".$ord_id."' ";
							$res_get_order_id_show= mysqli_query($db_con,$sql_get_order_id_show) or die(mysqli_error($db_con));
						    $row_get_order_id_show 	= mysqli_fetch_array($res_get_order_id_show);
							
							// Create an array having all required parameters for creating checksum.
							$paramList["MID"] = PAYTM_MERCHANT_MID;
							$paramList["ORDER_ID"] =$row_get_order_id_show['ord_id_show'];
							$paramList["CUST_ID"] = $ord_cust_id;
							$paramList["INDUSTRY_TYPE_ID"] = "Retail109";
							$paramList["CHANNEL_ID"] = "WEB";
							$paramList["TXN_AMOUNT"] = $ord_total;
							$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
                                                        $paramList["CALLBACK_URL"] ="https://www.planeteducate.com/payment/page-order-success.php";                   
//$paramList["CALLBACK_URL"] ="planeteducate.com/payment/page-order-success.php";    
							$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
						
							$payudata = '<form method="post" action="'.PAYTM_TXN_URL.'" id="frm_paytm">';
							foreach($paramList as $name => $value)
						    {
							$payudata .='<input type="hidden" name="' . $name .'" value="' . $value . '">';
							}
			
							$payudata .='<input type="hidden" name="CHECKSUMHASH" value="'.$checkSum.'">';
							$payudata .= '</form>';
							
							$response_array = array("Success"=>"Success","resp"=>"Order process","url"=>"paytm","paymentData"=>"".utf8_encode($payudata));
							
						}			
						
		////////////////////////////////////////----------------END PAYTM Done By satish ( 31-01-2017)--------------/////////////////////////////
																					
					}
					elseif($payment_mode == "Cash on Delivery")
					{
						$sql_update_order 			= " UPDATE `tbl_order` SET `ord_pay_type`='".$payment_mode."',`ord_pay_status`='1',`ord_status`='1',`ord_addid`='".$add_id."',";
						$sql_update_order 			.= " ord_shipping_charges = '".$shipping_charge."',`ord_modified`='".$datetime."',`ord_comment` = '".$ord_comment."' WHERE ord_id = '".$ord_id."' ";
						$result_update_order 		= mysqli_query($db_con,$sql_update_order) or die(mysqli_error($db_con));
						if($result_update_order)
						{					
		
						}
						else
						{
							$response_array = array("Success"=>"fail","resp"=>"Order Generation Failed.Please try after some time");							
						}					
						$sql_update_cart	= "UPDATE `tbl_cart` SET `cart_status`='1',`cart_modified`='".$datetime."' WHERE cart_orderid = '".$ord_id."'";
						$result_update_cart = mysqli_query($db_con,$sql_update_cart) or die(mysqli_error($db_con));
						if($result_update_cart)
						{				
							if(sendOrderMail($ord_id))
							{						
								sendOrderEmailVendor($ord_id);// vendor mail will send when we go live
								/*$response_array = array("Success"=>"Success","resp"=>"Order Generated Successfully.Please check the mail for order details","dataemail"=>"Success","url"=>$BaseFolder."/order-placed/".$ord_id_show);Comment By Tariq-22-09-2016*/ 
							}
							else
							{
								$response_array = array("Success"=>"Success","resp"=>"Order Generated Successfully.Please check the mail for order details","dataemail"=>"fail");
							}
	
						}
						else
						{
							$response_array = array("Success"=>"fail","resp"=>"Order Generation Failed.Please try after some time");				
						}						
					}								
				}
				else
				{
					$response_array = array("Success"=>"fail","resp"=>"Order Generation Failed.Please try after some time");				
				}
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Order Generation Failed.Please try after some time");
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Empty Data");					
		}	
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Please select Payment Mode");
	}
	
	echo json_encode($response_array);
}
/* place an order */
/* delete an order */
if((isset($obj->deleteFromCart)) == "1" && isset($obj->deleteFromCart))// add item in cart
{
	$cart_id 		= trim(mysqli_real_escape_string($db_con,$obj->cart_id));
	
	$response_array	= array();
	
	if($cart_id == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Cart id Empty");
	}
	else
	{
		$sql_get_cust_id 	= " SELECT `cart_custid`, cart_coupon_code FROM `tbl_cart` tc WHERE tc.`cart_id` = '".$cart_id."' "; 
		$result_get_cust_id = mysqli_query($db_con,$sql_get_cust_id) or die(mysqli_error($db_con));
		$num_rows_get_cust_id = mysqli_num_rows($result_get_cust_id);
		
		if($num_rows_get_cust_id != 0)
		{
			$row_get_cust_id	= mysqli_fetch_array($result_get_cust_id);
			$cust_id			= $row_get_cust_id['cart_custid'];
			$cart_coupon_code	= $row_get_cust_id['cart_coupon_code'];	// Coupon ID [Its not the coupon code]
			
			$sql_delete_item 	= " DELETE FROM `tbl_cart` WHERE `cart_id` = '".$cart_id."' ";	
			$result_delete_item = mysqli_query($db_con,$sql_delete_item) or die(mysqli_error($db_con));
			
			if($result_delete_item)
			{
				if($cart_coupon_code != '')
				{
					$response_array	= getCartUpdatedData($cust_id,$cart_coupon_code);
				}
				elseif($cart_coupon_code == '')
				{
					// remove coupon code if applied and cart value become less than 500				
					$response_array = array("Success"=>"Success","resp"=>"Product removed");
				}	
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Product Not removed");
			}
			
			/*$sql_delete_item 	= " DELETE FROM `tbl_cart` WHERE `cart_id` = '".$cart_id."' ";	
			$result_delete_item = mysqli_query($db_con,$sql_delete_item) or die(mysqli_error($db_con));
			if($result_delete_item)
			{
				// remove coupon code if applied and cart value become less than 500
				$sql_select_all_cart_price 		= " SELECT * FROM `tbl_cart` WHERE `cart_custid` = '".$cust_id."' and cart_status = 0 ";
				$result_select_all_cart_price 	= mysqli_query($db_con,$sql_select_all_cart_price) or die(mysqli_error($db_con));
				$num_rows_select_all_cart_price	= mysqli_num_rows($result_select_all_cart_price);
				if($num_rows_select_all_cart_price != 0)
				{
					$cart_total_price				= 0;					
					while($row_select_all_cart_price = mysqli_fetch_array($result_select_all_cart_price))
					{
						$cart_total_price			+= $row_select_all_cart_price['cart_price'];						
					}				
					if($cart_total_price < 500)
					{
						$sql_update_cart_discount 		= " UPDATE `tbl_cart` ";
						$sql_update_cart_discount 		.= " 	SET `cart_coupon_code`= '', ";
						$sql_update_cart_discount 		.= " 		`cart_discount`= '', ";
						$sql_update_cart_discount 		.= " 		`cart_modified`= '".$datetime."' ";
						$sql_update_cart_discount 		.= " WHERE `cart_custid` = '".$cust_id."' ";
						$sql_update_cart_discount 		.= " 	AND cart_status = 0 ";
						$result_update_cart_discount	= mysqli_query($db_con,$sql_update_cart_discount) or die(mysqli_error($db_con));
					}							
				}
				// remove coupon code if applied and cart value become less than 500				
				$response_array = array("Success"=>"Success","resp"=>"Product removed");
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Product Not removed");
			}*/		
		}
		else
		{
			//$response_array = array("Success"=>"fail","resp"=>"Customer Not Exists.");Product removed
			$response_array = array("Success"=>"fail","resp"=>"Product removed.");
		}
	}
	echo json_encode($response_array);	
}
/* delete an order */
/* add product to cart */

function getCustDetails($cust_session)
{
	global $db_con;
	
	$sql_get_cust_id 	= " SELECT * from tbl_customer where cust_email = '".$cust_session."' ";
	$result_get_cust_id	= mysqli_query($db_con,$sql_get_cust_id) or die(mysqli_error($db_con));
	$row_get_cust_id	= mysqli_fetch_array($result_get_cust_id);
	
	return $row_get_cust_id;
}

function getCouponCode($cust_id)
{
	global $db_con;
	$user_coupon_code	= '';
	
	// find the all record from the tbl_cart table for this particular cust_id
	$sql_get_coupon_code_id	= " SELECT * FROM `tbl_cart` WHERE `cart_custid`='".$cust_id."' ";

///----------------modified by satish 20012017---------------//////////////////
	$sql_get_coupon_code_id .=" AND cart_status  = 0 AND (cart_type='incomplete' or cart_type='abundant') ";
	///----------------modified by satish 20012017---------------//////////////////


	$res_get_coupon_code_id	= mysqli_query($db_con, $sql_get_coupon_code_id) or die(mysqli_error($db_con));
	$num_get_coupon_code_id	= mysqli_num_rows($res_get_coupon_code_id);
	
	if($num_get_coupon_code_id != 0)
	{
		$row_get_coupon_code_id	= mysqli_fetch_array($res_get_coupon_code_id);
		
		$coup_id				= $row_get_coupon_code_id['cart_coupon_code'];
		
		if($coup_id != '')
		{
			// find the coupon code from the tbl_coupon table
			$sql_get_coupon_code	= " SELECT * FROM `tbl_coupons` WHERE `coup_id`='".$coup_id."' ";
			$res_get_coupon_code	= mysqli_query($db_con, $sql_get_coupon_code) or die(mysqli_error($db_con));
			$num_get_coupon_code	= mysqli_num_rows($res_get_coupon_code);
			
			if($num_get_coupon_code != 0)
			{
				$row_get_coupon_code	= mysqli_fetch_array($res_get_coupon_code);
			
				$user_coupon_code		= $row_get_coupon_code['coup_code'];		
			}
			else
			{
				$user_coupon_code	= '';
			}
		}
		else
		{
			$user_coupon_code	= '';	
		}	
	}
	else
	{
		$user_coupon_code	= '';
	}
	
	return $user_coupon_code;
}

if((isset($obj->addToCart)) == "1" && isset($obj->addToCart))// add item in cart
{
	$response_array 	= array();
	$prod_id 			= trim(mysqli_real_escape_string($db_con,$obj->prod_id));
	$cust_session 		= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$user_prod_quentity	= trim(mysqli_real_escape_string($db_con,$obj->user_prod_quentity));
	
	if($user_prod_quentity == "" || $user_prod_quentity == 0)
	{
		$user_prod_quentity = 1;
	}
	
	// Query For getting Prod Quantity
	$sql_get_prod_quant	= " SELECT * FROM `tbl_products_master` WHERE `prod_id`='".$prod_id."' ";
	$res_get_prod_quant	= mysqli_query($db_con, $sql_get_prod_quant) or die(mysqli_error($db_con));
	$row_get_prod_quant	= mysqli_fetch_array($res_get_prod_quant);
	
	$prod_actual_quant	= $row_get_prod_quant['prod_max_quantity'];
	
	if($prod_actual_quant < $user_prod_quentity)
	{
		$user_prod_quentity	= $prod_actual_quant;	
	}
	
	/*if(isset($obj->user_coupon_code))
	{
		$user_coupon_code	= trim(mysqli_real_escape_string($db_con,$obj->user_coupon_code));
	}
	else
	{
		$user_coupon_code	= "";
	}*/
	
	#=================================================================================
	# START : find the value of coupon if it applied [DN by Prathamesh on 03112016]
	#=================================================================================
	// Here I have to find that coupon is appied or not
	if($cust_session == '')
	{
		if(isset($_COOKIE[$cookie_name]))
		{
			$cookie_value	= $_COOKIE[$cookie_name];
			$user_coupon_code	= getCouponCode($cookie_value);
		}
		else
		{
			//$response_array =  array("Success"=>"fail","resp"=>'Please enable cookie on your system or <a href="'.$BaseFolder.'/page-login" style="text-decoration:underline;font-weight:bold;color:#4BBCD7;">login</a> to Planet Educate.');	
		}
	}
	else
	{
		$row_get_cust_id	= getCustDetails($cust_session);
		$cust_id			= $row_get_cust_id['cust_id'];
		
		$user_coupon_code	= getCouponCode($cust_id);	
	}
	
	#=================================================================================
	# END : find the value of coupon if it applied [DN by Prathamesh on 03112016]
	#=================================================================================
	
	/*$response_array	= array("Success"=>"fail","resp"=>'Prathamesh : '.$user_coupon_code);
	echo json_encode($response_array);
	exit();*/

	if($prod_id == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Products Not Available");		
	}	
	else
	{
		if($cust_session == "")
		{
			if(isset($_COOKIE[$cookie_name])) 
			{
				$cookie_value	= $_COOKIE[$cookie_name];
				$response_array = addProductToCart($cookie_value,$prod_id,$user_prod_quentity,$user_coupon_code,$response_array);
			} 
			else 
			{   
				$response_array =  array("Success"=>"fail","resp"=>'Please enable cookie on your system or <a href="'.$BaseFolder.'/page-login" style="text-decoration:underline;font-weight:bold;color:#4BBCD7;">login</a> to Planet Educate.');
			}									
		}
		else
		{	
			$row_get_cust_id	= getCustDetails($cust_session);
			
			$cust_id			= $row_get_cust_id['cust_id'];			
			if(isset($_COOKIE[$cookie_name])) 
			{	
				$cookie_value	= $_COOKIE[$cookie_name];
				updateCartProductLogin($cust_id,$cookie_value);// update the product according to session and cookie value
			}
			
			/*$response_array	= array("Success"=>"fail","resp"=>'Prathamesh : '.$cust_id.'<==>'.$prod_id.'<==>'.$user_prod_quentity.'<==>'.$user_coupon_code.'<==>'.$response_array);
			echo json_encode($response_array);
			exit();*/
			
			$response_array 	= addProductToCart($cust_id,$prod_id,$user_prod_quentity,$user_coupon_code,$response_array);
			echo json_encode($response_array);
			exit();
			//echo json_encode($response_array);exit();			
		}
	}
	echo json_encode($response_array);	
}
/* add product to cart */
/* get cart with product*/
if((isset($obj->getCart)) == "1" && isset($obj->getCart))// get Cart items
{
	$cust_session	= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$cart_id		= trim(mysqli_real_escape_string($db_con,$obj->cart_id));
	$response_array = array();	
	if($cust_session == "")
	{
		if(isset($_COOKIE[$cookie_name])) 
		{
			if($cart_id == "checkout-page")
			{
				$response_array	= refreshCart($_COOKIE[$cookie_name],0);
			}
			else
			{
				$response_array	= refreshCart($_COOKIE[$cookie_name],1);					
			}			
		} 	
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"There are no items in this cart.");
		}
	}
	else
	{
		if(isset($_SESSION['front_panel']))
		{
			$cust_email			= $cust_session;
			$sql_get_user 		= "SELECT * FROM `tbl_customer` WHERE `cust_email` like '".$cust_email."' ";
			$result_get_user	= mysqli_query($db_con,$sql_get_user) or die(mysqli_error($db_con));
			$row_get_user		= mysqli_fetch_array($result_get_user);
			$cust_id			= $row_get_user['cust_id'];
			if($cart_id == "checkout-page")
			{
				$response_array	= refreshCart($cust_id,0);
			}
			else
			{
				$response_array	= refreshCart($cust_id,1);					
			}					
		}		
	}	
	echo json_encode($response_array);	
}
/* get cart with product*/
/* list users orders */
if((isset($obj->get_orders)) == "1" && isset($obj->get_orders))// show users orders
{
	$cust_session	= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$response_array = array();	
	if($cust_session == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Session expired.");
	}
	else
	{
		if(isset($_SESSION['front_panel']))
		{
			$cust_email			= $cust_session;
			$sql_get_user 		= "SELECT * FROM `tbl_customer` WHERE `cust_email` like '".$cust_email."' ";
			$result_get_user	= mysqli_query($db_con,$sql_get_user) or die(mysqli_error($db_con));
			$num_rows_get_user = mysqli_num_rows($result_get_user);
			if($num_rows_get_user != 0)
			{
				$row_get_user		= mysqli_fetch_array($result_get_user);
				$cust_id			= $row_get_user['cust_id'];
				$sql_get_orders		= " SELECT `ord_id`,`ord_id_show` from tbl_order WHERE `ord_custid` = '".$cust_id."' and `ord_pay_status` != '0' ";
				$result_get_orders	= mysqli_query($db_con,$sql_get_orders) or die(mysqli_error($db_con));
				$num_rows_get_orders = mysqli_num_rows($result_get_orders);
				if($num_rows_get_orders == 0)
				{
					$response_array = array("Success"=>"Fail","resp"=>'<span style="color:red;text-align:center;">No Orders yet!!!</span>');
				}
				else
				{
					$order_data = '';	
					while($row_get_orders = mysqli_fetch_array($result_get_orders))
					{
						$ord_id 	= $row_get_orders['ord_id'];	
						$ord_id_show= $row_get_orders['ord_id_show'];						
						$order_data .= '<div style="float:left; margin:10px 10px;">';
						$order_data .= '<button class="cws-button bt-color-3 border-radius icon-left alt order_btns" id="'.$ord_id.'" onClick="showOrders(\''.$ord_id.'\'),orderSelect(this.id);"><i class="fa fa-shopping-bag"></i>&nbsp;'.$ord_id_show.'</button>';
						$order_data .= '</div>';
					}
					$order_data .= '<div class="clear-fix">';
					$order_data .= '</div>';					
					$response_array = array("Success"=>"Success","resp"=>utf8_encode($order_data));																	
				}				
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"No Orders Available.");
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Your Session expired.");
		}		
	}	
	echo json_encode($response_array);		
}
/* This function will show single order details*/
function showSingleOrderDetails($cust_email,$order_id)
{
	global $db_con;
	global $BaseFolder;
	$sql_get_user 		= "SELECT * FROM `tbl_customer` WHERE `cust_email` like '".$cust_email."' ";
	$result_get_user	= mysqli_query($db_con,$sql_get_user) or die(mysqli_error($db_con));
	$num_rows_get_user = mysqli_num_rows($result_get_user);
	if($num_rows_get_user != 0)
	{
		$row_get_user		= mysqli_fetch_array($result_get_user);
		$cust_id			= $row_get_user['cust_id'];
		$sql_get_orders		= " SELECT `ord_id`,`ord_id_show`, `ord_custid`, `ord_total`, `ord_discount`, `ord_shipping_charges`, `ord_status`,";
		$sql_get_orders		.= " `ord_pay_type`, `ord_pay_status`, `ord_addid`, `ord_comment`,(SELECT `orstat_name` FROM `tbl_order_status` WHERE `orstat_id` = ord_status ) as  order_status";
		$sql_get_orders		.= " FROM `tbl_order` WHERE `ord_custid` = '".$cust_id."' and `ord_pay_status` != 0 ";
		if($order_id != "")
		{
			$sql_get_orders	.= " and ord_id = '".$order_id."' ";
		}
		$sql_get_orders	.= " order by ord_id desc LIMIT 0,1";				
		$result_get_orders	= mysqli_query($db_con,$sql_get_orders) or die(mysqli_error($db_con));
		$num_rows_get_orders = mysqli_num_rows($result_get_orders);
		if($num_rows_get_orders == 0)
		{
			return '<span style="color:red;text-align:center;">No Orders yet!!!</span>';
		}
		else
		{
			$order_data = '';
			$row_get_orders = mysqli_fetch_array($result_get_orders);
			$sql_get_cart_products		= " SELECT `cart_id`, `cart_custid`, `cart_prodid`, `cart_prodquantity`, `cart_price`, `cart_coupon_code`, `cart_discount`, `cart_orderid`, `cart_status`, ";
			$sql_get_cart_products		.= " (SELECT `prod_title` FROM `tbl_products_master` WHERE `prod_id`=cart_prodid) as cart_prod_name";					
			$sql_get_cart_products		.= " FROM `tbl_cart` WHERE `cart_orderid` = '".$row_get_orders['ord_id']."' ";
			$result_get_cart_products	= mysqli_query($db_con,$sql_get_cart_products) or die(mysqli_error($db_con));
			$num_rows_get_cart_products = mysqli_num_rows($result_get_cart_products);
			if($num_rows_get_cart_products != 0)
			{			
				$order_data = '<div class="page-content woocommerce">';
				$order_data .= '<div class="container_orders clear-fix">';
				$order_data .= '<div class="title clear-fix">';
				$order_data .= '<h2 class="title-main">Order Id : '.$row_get_orders['ord_id_show'].'</h2>';
				$order_data .= '</div>';
				$order_data .= '<div id="content" role="main">';							
				$order_data .= '<form action="#" method="post">';
				$order_data .= '<table class="shop_table cart">';
				$order_data .= '<thead>';
				$order_data .= '<tr>';
				$order_data .= '<th class="product-thumbnail" style="width:15%">Image</th>';
				$order_data .= '<th class="product-model" style="width:15%">Model no.</th>';
				$order_data .= '<th class="product-name" style="width:30%">Product Name</th>';
				$order_data .= '<th class="product-price" style="width:15%">Price</th>';
				$order_data .= '<th class="product-quantity" style="width:10%">Quantity</th>';
				$order_data .= '<th class="product-subtotal" style="width:15%">Total</th>';
				$order_data .= '</tr>';
				$order_data .= '</thead>';
				$order_data .= '<tbody>';
				$start 		= 0; // to initialise product count
				while($row_get_cart_products = mysqli_fetch_array($result_get_cart_products))				
				{					
					$sql_get_products		= " SELECT `prod_orgid`, `prod_brandid`, `prod_catid`, `prod_subcatid`,`prod_status`,prod_id,`prod_slug`,prod_model_number,";
					$sql_get_products		.= " (SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_status` != 0 and `prod_img_type` = 'main' and `prod_img_prodid` = prod_id) as prod_image_name ";
					$sql_get_products		.= "  FROM `tbl_products_master` WHERE `prod_id` = '".$row_get_cart_products['cart_prodid']."' ";
					$result_get_products	= mysqli_query($db_con,$sql_get_products) or die(mysqli_error($db_con));
					$num_rows_get_products = mysqli_num_rows($result_get_products);
					if($num_rows_get_products != 0)
					{
						$row_get_products = mysqli_fetch_array($result_get_products);
					}						
					$order_data .= '<tr class="cart_item">';
					$order_data .= '<td class="product-thumbnail">';
					$order_data .= '<a href="'.$BaseFolder."/".$row_get_products['prod_slug'].'-'.$row_get_products['prod_id'].'-a">';
					if(trim($row_get_products['prod_image_name']) != "")
					{
						$imagepath 	= "images/planet/org".$row_get_products['prod_orgid']."/prod_id_".$row_get_products['prod_id']."/small/".$row_get_products['prod_image_name'];
						//if(file_exists("../".$imagepath))
						{
							$order_data .= '<img src="'.$BaseFolder."/".$imagepath.'" data-at2x="'.$BaseFolder."/".$imagepath.'" class="attachment-shop_thumbnail wp-post-image" alt="'.$row_get_cart_products['cart_prod_name'].'" on>';
						}
						/*else
						{
							$order_data .= '<img src="'.$BaseFolder.'/images/no-image.jpg" data-at2x="'.$BaseFolder.'/images/no-image.jpg" class="attachment-shop_thumbnail wp-post-image" alt="">';
						}*/
					}
					else
					{
						$order_data .= '<img src="images/no-image.jpg" data-at2x="images/no-image.jpg" class="attachment-shop_thumbnail wp-post-image" alt="">';
					}														
					$order_data .= '</a>';
					$order_data .= '</td>';
					$order_data .= '<td class="product-model">';
					$order_data .= '<div>';
					$order_data	.= $row_get_products['prod_model_number'];
					$order_data .= '</div>';
					$order_data .= '</td>';								
					$order_data .= '<td class="product-name" style="padding:2px;">';
					$order_data .= '<a href="'.$BaseFolder."/".$row_get_products['prod_slug'].'-'.$row_get_products['prod_id'].'-a">'.$row_get_cart_products['cart_prod_name'].'</a>';
					$order_data .= '</td>';
					$order_data .= '<td class="product-price">';
					$order_data .= '<span class="amount"><i class="fa fa-rupee"></i> '.$row_get_cart_products['cart_price']/$row_get_cart_products['cart_prodquantity'].'</span>';
					$order_data .= '</td>';
					$order_data .= '<td class="product-quantity">';
					$order_data .= '<div class="quantity buttons_added">';
					$order_data	.= $row_get_cart_products['cart_prodquantity'];
					$order_data .= '</div>';
					$order_data .= '</td>';								
					$order_data .= '<td class="product-subtotal">';
					$order_data .= '<span class="amount"><i class="fa fa-rupee"></i> '.$row_get_cart_products['cart_price'].'</span>';
					$order_data .= '</td>';
					$order_data .= '</tr>';
					$order_data	.= '<td></td>';
					$order_data .= '</tr>';								
				}
				$order_data .= '</tbody>';
				$order_data .= '</table>';
				$order_data .= '</form>';
				$order_data .= '<div class="cart-collaterals">';	
				$sql_get_address = " SELECT `add_id`, `add_details`, ( SELECT state_name FROM `state` where state = add_state) as add_state_name , ";
				$sql_get_address .= " ( SELECT `city_name` FROM `city` WHERE `city_id` = add_city) as add_city_name , `add_pincode` ";
				$sql_get_address .= " FROM `tbl_address_master` WHERE `add_id` = '".$row_get_orders['ord_addid']."' and ";
				$sql_get_address .= " `add_user_type` =  'customer' and `add_user_id` = '".$row_get_orders['ord_custid']."' ";
				$result_get_address = mysqli_query($db_con,$sql_get_address) or die(mysqli_error($db_con));
				$num_rows_get_address = mysqli_num_rows($result_get_address);
				if($num_rows_get_address == 0)
				{
					$order_data .= '<span style="color:f00;">Record Not Available.</span>';
				}
				else
				{
					$row_get_address = mysqli_fetch_array($result_get_address);								
					$order_data .= '<div class="cart_totals">';
					$order_data .= '<h3><b>Order Address</b></h3>';								
					$order_data .= '<table>';
					$order_data .= '<tbody>';
					$order_data .= '<tr class="cart-subtotal">';
					$order_data .= '<td><b>Address Details</b></td>';
					if($row_get_address['add_details'] == "")
					{
						$order_data .= '<td><span class="amount">:&nbsp;&nbsp;Not Available</span></td>';
					}
					else
					{
						$order_data .= '<td><span class="amount">:&nbsp;&nbsp;'.ucwords($row_get_address['add_details']).'</span></td>';
					}						
					$order_data .= '</tr>';
					$order_data .= '<tr class="order-total">';
					$order_data .= '<td><b>City</b></td>';
					if($row_get_address['add_city_name'] == "")
					{
						$order_data .= '<td><span class="amount">:&nbsp;&nbsp;Not Available</span></td>';
					}
					else
					{
						$order_data .= '<td><span class="amount">:&nbsp;&nbsp;'.$row_get_address['add_city_name'].'</span></td>';
					}							
					$order_data .= '</tr>';
					$order_data .= '<tr class="order-total">';
					$order_data .= '<td><b>State</b></td>';
					if($row_get_address['add_state_name'] == "")
					{
						$order_data .= '<span style="color:f00;">:&nbsp;&nbsp;Not Available</span></td></tr>';
					}
					else
					{
						$order_data .= '<td><span class="amount">:&nbsp;&nbsp;'.$row_get_address['add_state_name'].'</span></td>';
					}																
					$order_data .= '</tr>';																
					$order_data .= '<tr class="shipping">';
					$order_data .= '<td><b>Pincode</b></td>';
					if($row_get_address['add_pincode'] == 0)
					{
						$order_data .= '<td>:&nbsp;&nbsp;Not Available</td>';
					}
					else
					{
						$order_data .= '<td>:&nbsp;&nbsp;'.$row_get_address['add_pincode'].'</td>';
					}
					$order_data .= '</tr>';
					$order_data .= '</tbody>';
					$order_data .= '</table>';
					$order_data .= '</div>';								
				}
				$order_data .= '<div class="cart_totals ">';
				$order_data .= '<h3><b>Cart Totals</b></h3>';
				$order_data .= '<table>';
				$order_data .= '<tbody>';
				$order_data .= '<tr><td><b>Shipping Charges</b></td><td>:&nbsp;&nbsp;';
				if($row_get_orders['ord_shipping_charges'] == 0 || $row_get_orders['ord_shipping_charges'] == "")
				{
					$order_data .= 'Free Shipping</td></tr>';
				}
				else
				{
					$order_data .= $row_get_orders['ord_shipping_charges'].'</td></tr>';
				}								
				$order_data .= '<tr><td><b>Total Discount</b></td><td>:&nbsp;&nbsp;';
				if($row_get_orders['ord_discount'] == 0)
				{
					$order_data .= 'No Discount</td></tr>';
				}
				else
				{
					$order_data .= $row_get_orders['ord_discount'].'</td></tr>';
				}	
				$order_data .= '<tr><td><b>Total Amount</b></td><td>:&nbsp;&nbsp;';
				$order_data .= $row_get_orders['ord_total'].'</td></tr>';
				$order_data .= '</tbody>';
				$order_data .= '</table>';
				$order_data .= '</div>';
				$order_data .= '<div class="cart_totals">';
				$order_data .= '<h3>&nbsp;</h3>';							
				$order_data .= '<table>';
				$order_data .= '<tbody>';
				$order_data .= '<tr class="cart-subtotal">';
				$order_data .= '<td><b>Order Status</b></td>';
				if($row_get_orders['order_status'] == "")
				{
					$order_data .= '<td><span class="amount">:&nbsp;&nbsp;Not Available</span></td>';
				}
				else
				{
					$order_data .= '<td><span class="amount">:&nbsp;&nbsp;'.ucwords($row_get_orders['order_status']).'</span></td>';
				}									
				$order_data .= '</tr>';
				$order_data .= '<tr class="order-total">';
				$order_data .= '<td><b>Payment Mode</b></td>';
				if($row_get_orders['ord_pay_type'] == "")
				{
					$order_data .= '<td><span class="amount">:&nbsp;&nbsp;Not Available</span></td>';
				}
				else
				{
					$order_data .= '<td><span class="amount">:&nbsp;&nbsp;'.ucwords($row_get_orders['ord_pay_type']).'</span></td>';
				}
				$order_data .= '<tr class="shipping">';
				$order_data .= '<td><b>Order Comment</b></td>';
				if($row_get_orders['ord_comment'] == 0)
				{
					$order_data .= '<td><span class="amount">:&nbsp;&nbsp;Not Available</span></td>';
				}
				else
				{
					$order_data .= '<td><span class="amount">:&nbsp;&nbsp;'.ucwords($row_get_orders['ord_comment']).'</span></td>';								
				}
				$order_data .= '</tr>';
				$order_data .= '</tbody>';
				$order_data .= '</table>';							
				$order_data .= '</div>';																											
				$order_data .= '</div>';														
				$order_data .= '</div>';
				$order_data .= '</div>';
				$order_data .= '</div>';
				$order_data .= '</div>';																		
			}						
			$order_data .= '</div>';								
			return utf8_encode($order_data);
		}				
	}
	else
	{
		return "Your account not exists.";
	}	}
/* This function will show single order details*/
/* This function Called from my account order page to display an order details*/
if((isset($obj->show_orders)) == "1" && isset($obj->show_orders))// show users orders
{
	$cust_session	= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$order_id		= trim(mysqli_real_escape_string($db_con,$obj->order_id));	
	$response_array = array();	
	if($cust_session == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Session expired.");
	}
	else
	{
		if(isset($_SESSION['front_panel']))
		{
			$cust_email			= $cust_session;
			$response_array = array("Success"=>"fail","resp"=>showSingleOrderDetails($cust_email,$order_id));
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Your Session expired.");
		}		
	}	
	echo json_encode($response_array);		
}
/* This function Called from my account order page to display an order details*/
/* change password from frontend*/
if((isset($obj->change_password)) == "1" && isset($obj->change_password))
{
	$cust_session			= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$cust_password_old_cp	= trim($obj->cust_password_old);
	$cust_password_new_cp	= trim($obj->cust_password_new);
	
	$response_array = array();	
	if($cust_session == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Session expired.");
	}
	else
	{
		if(isset($_SESSION['front_panel']))
		{
			$cust_email				= $cust_session;
			$sql_get_user_cp		= "SELECT * FROM `tbl_customer` WHERE `cust_email` like '".$cust_email."' ";
			$result_get_user_cp		= mysqli_query($db_con,$sql_get_user_cp) or die(mysqli_error($db_con));
			$num_rows_get_user_cp 	= mysqli_num_rows($result_get_user_cp);
			if($num_rows_get_user_cp == 1)
			{
				$row_get_user_cp			= mysqli_fetch_array($result_get_user_cp);
				$cust_salt_value_db_cp		= $row_get_user_cp['cust_salt_value'];
				$cust_password_user_cp		= md5($cust_salt_value_db_cp.$cust_password_old_cp);
				$cust_password_db_cp		= $row_get_user_cp['cust_password'];							
				if($cust_password_user_cp == $cust_password_db_cp)
				{
					$cust_id 				= $row_get_user_cp['cust_id'];
					$random_string			= "";
					$cust_salt_value_query	= " SELECT * FROM `tbl_customer` WHERE `cust_salt_value` = '".$random_string."' ";
					$new_cust_salt_value_cp	= randomString($cust_salt_value_query,5);
					
					$new_cust_password_cp	= md5($new_cust_salt_value_cp.$cust_password_new_cp);
						
					$sql_update_pass 		= " UPDATE `tbl_customer` SET `cust_password` = '".$new_cust_password_cp."',`cust_salt_value`='".$new_cust_salt_value_cp."', ";
					$sql_update_pass 		.= " `cust_modified`='".$datetime."' WHERE `cust_id` = '".$cust_id."' ";
					$result_update_pass 	= mysqli_query($db_con,$sql_update_pass) or die(mysqli_error($db_con));
					if($result_update_pass)
					{
						$response_array = array("Success"=>"Success","resp"=>"Password Updated Successfully");
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Password Update Failed.");						
					}
				}
				else
				{
					$response_array = array("Success"=>"fail","resp"=>"Old Password Not Matched.");
				}
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Old Password Not Updated...");
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Session expired.");
		}
	}
	echo json_encode($response_array);}
/* change password from frontend*/
/* get Product Count*/
if((isset($obj->getCount)) == "1" && isset($obj->getCount))// get Product Count
{
	$common_id 		= trim(mysqli_real_escape_string($db_con,$obj->common_id)); 
	$common_parent 	= trim(mysqli_real_escape_string($db_con,$obj->common_parent)); 
	$page_type 		= trim(mysqli_real_escape_string($db_con,$obj->PageType));
	if($common_id == "" && $common_parent == "")
	{
		$response_array = array("Success"=>"Success","resp"=>"");		
	}
	else
	{
		if($page_type == "Level")
		{
			$cat_filt_btn_data = '';		
        	$sql_sub_cat_name		= " select * from tbl_category where cat_type = 'parent' and cat_status = 1 and cat_name != 'none' ";
			$result_sub_cat_name	= mysqli_query($db_con,$sql_sub_cat_name) or die(mysqli_error($db_con));
			$num_rows_sub_cat_name= mysqli_num_rows($result_sub_cat_name);
			if($num_rows_sub_cat_name = 0)
			{
			}
			else
			{
				$sql_prod_count_cat 		= " SELECT distinct(prod_id) FROM `tbl_products_master` as tpm,`tbl_product_levels` as tpl ";
				$sql_prod_count_cat 		.= " where tpm.prod_id = tpl.prodlevel_prodid and prodlevel_status = 1 and prod_status = 1 and prod_recommended_price != '' and prod_list_price != '' ";
				if($common_parent != 0 && $common_parent != "0" && $common_parent != "")
				{
					$sql_prod_count_cat 	.= " and prodlevel_levelid_parent = '".$common_parent."' ";
				}				
				if($common_id != 0 && $common_id != "0" && $common_id != "")
				{
					$sql_prod_count_cat 	.= " and prodlevel_levelid_child = '".$common_id."' ";
				}
				$result_prod_count_cat 		= mysqli_query($db_con,$sql_prod_count_cat) or die(mysqli_error($db_con));
				$num_rows_prod_count_cat 	= mysqli_num_rows($result_prod_count_cat);
				if($num_rows_prod_count_cat == 0)
				{
					// no data because product count is 0
				}
				else
				{
					$products	= array();
					while($rows_prod_count_cat = mysqli_fetch_array($result_prod_count_cat))
					{
						array_push($products,$rows_prod_count_cat['prod_id']);
					}								
					$cat_filt_btn_data 			.= '<button class="cws-button bt-color-1 border-radius active category" id="cat0" value="0" onClick="getActive(this.id);getSubCat(\'0\');">All Category&nbsp;('.$num_rows_prod_count_cat.')</button>';
					while($row_sub_cat_name = mysqli_fetch_array($result_sub_cat_name))
					{	
						$sql_prod_count_cat 		= " SELECT distinct(prod_id) FROM `tbl_products_master` as tpm,`tbl_product_levels` as tpl ";
						$sql_prod_count_cat 		.= " where tpm.prod_id = tpl.prodlevel_prodid and prodlevel_status = 1 and prod_status = 1 and prod_recommended_price != '' and prod_list_price != '' ";
						if($common_parent != 0 && $common_parent != "0" && $common_parent != "")
						{
							$sql_prod_count_cat 	.= " and prodlevel_levelid_parent = '".$common_parent."' ";
						}				
						if($common_id != 0 && $common_id != "0" && $common_id != "")
						{
							$sql_prod_count_cat 	.= " and prodlevel_levelid_child = '".$common_id."' ";
						}
						$sql_prod_count_cat			.= " and tpm.`prod_catid` = '".$row_sub_cat_name['cat_id']."' ";
						$result_prod_count_cat 		= mysqli_query($db_con,$sql_prod_count_cat) or die(mysqli_error($db_con));
						$num_rows_prod_count_cat 	= mysqli_num_rows($result_prod_count_cat);
						if($num_rows_prod_count_cat == 0)
						{
							$cat_filt_btn_data 			.= '';							
						}
						else
						{
							$cat_filt_btn_data 			.= '<button class="cws-button bt-color-1 border-radius alt category" id="cat_btn'.$row_sub_cat_name['cat_id'].'" value="'.$row_sub_cat_name['cat_id'].'" onClick="getActive(this.id);getSubCat(\''.$row_sub_cat_name['cat_id'].'\');">'.ucwords($row_sub_cat_name['cat_name']).'('.$num_rows_prod_count_cat.')</button>';
						}						
					}					
				}
			}
			$price 		= getPrice($products);
			$main_price = explode(":",$price);
			$response_array = array("Success"=>"Success","resp"=>utf8_encode($cat_filt_btn_data),"min_price"=>$main_price[1],"max_price"=>$main_price[0]);
		}
		elseif($page_type == "Catogery")
		{
			$level_filt_btn_data 	= '';		
        	$sql_sub_level_name		= " SELECT * FROM `tbl_level` WHERE `cat_status` = 1 and cat_type='parent' and cat_name != 'none'";
			$result_sub_level_name	= mysqli_query($db_con,$sql_sub_level_name) or die(mysqli_error($db_con));
			$num_rows_sub_level_name= mysqli_num_rows($result_sub_level_name);
			if($num_rows_sub_level_name = 0)
			{
				
			}
			else
			{
				$sql_prod_count_cat 		= " SELECT distinct(prod_id) FROM `tbl_products_master` as tpm,`tbl_product_levels` as tpl ";
				$sql_prod_count_cat 		.= " where tpm.prod_id = tpl.prodlevel_prodid and prodlevel_status = 1 and prod_status = 1 and prod_recommended_price != '' and prod_list_price != '' ";
				if($common_parent != 0 && $common_parent != "0" && $common_parent != "")
				{
					$sql_prod_count_cat		.= " and tpm.`prod_catid` = '".$common_parent."' ";
				}					
				if($common_id != 0 && $common_id != '0' && $common_id != "")
				{
					$sql_prod_count_cat		.= " and tpm.`prod_subcatid` = '".$common_id."' ";
				}				
				$result_prod_count_cat 		= mysqli_query($db_con,$sql_prod_count_cat) or die(mysqli_error($db_con));
				$num_rows_prod_count_cat 	= mysqli_num_rows($result_prod_count_cat);	
				if($num_rows_prod_count_cat == 0)
				{
					// do not show becoz product count is zero					
				}
				else
				{
					$products	= array();
					while($rows_prod_count_cat = mysqli_fetch_array($result_prod_count_cat))
					{
						array_push($products,$rows_prod_count_cat['prod_id']);
					}
					$price 		= getPrice($products);
					$main_price = explode(":",$price);				
	                $level_filt_btn_data .= '<button class="cws-button bt-color-1 border-radius level active" id="level0" value="0" onClick="getActive(this.id);getSubLevel(\'0\');">All Level &nbsp;('.$num_rows_prod_count_cat.')</button>';
					while($row_sub_level_name = mysqli_fetch_array($result_sub_level_name))
					{	
						$sql_prod_count_cat 		= " SELECT distinct(prod_id) FROM `tbl_products_master` as tpm,`tbl_product_levels` as tpl ";
						$sql_prod_count_cat 		.= " where tpm.prod_id = tpl.prodlevel_prodid and prodlevel_status = 1 and prod_status = 1 and prod_recommended_price != '' and prod_list_price != '' ";
						if($common_parent != 0 && $common_parent != "0" && $common_parent != "")
						{
							$sql_prod_count_cat		.= " and tpm.`prod_catid` = '".$common_parent."' ";
						}								
						if($common_id != 0 && $common_id != "0" && $common_id != "")
						{
							$sql_prod_count_cat		.= " and tpm.`prod_subcatid` = '".$common_id."' ";
						}
						$sql_prod_count_cat 		.= " and prodlevel_levelid_parent = '".$row_sub_level_name['cat_id']."' ";
						$result_prod_count_cat 		= mysqli_query($db_con,$sql_prod_count_cat) or die(mysqli_error($db_con));
						$num_rows_prod_count_cat 	= mysqli_num_rows($result_prod_count_cat);	
						if($num_rows_prod_count_cat == 0)
						{
		                	$level_filt_btn_data 		.= '';							 
						}
						else
						{
		                	$level_filt_btn_data 		.= '<button class="cws-button bt-color-1 border-radius level alt" id="level'.$row_sub_level_name['cat_id'].'" value="'.$row_sub_level_name['cat_id'].'" onClick="getActive(this.id);getSubLevel(\''.$row_sub_level_name['cat_id'].'\');">'.ucwords($row_sub_level_name['cat_name']).'&nbsp;('.$num_rows_prod_count_cat.')'.'</button>';							
						}
					}					
				}				
			}
			$response_array = array("Success"=>"Success","resp"=>utf8_encode($level_filt_btn_data),"min_price"=>$main_price[1],"max_price"=>$main_price[0]);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Error Page");			
		}
	}
	echo json_encode($response_array);						}
/* get Product Count*/
/* update state function */
if((isset($obj->update_state)) == "1" && isset($obj->update_state))// get State list
{
	$country_val 		= mysqli_real_escape_string($db_con,$obj->country_val);
	$response_array 	= array();
	if($country_val!= "")
	{
		//and state = 'IN-MM' change by punit
		$sql_get_state_list 	= " SELECT * FROM state WHERE country_id = '".$country_val."'  order by state_name ASC ";
		$result_get_state_list 	= mysqli_query($db_con,$sql_get_state_list) or die(mysqli_error($db_con));
		$state_list				= '<option value="">Select State</option>';
		while($row_get_state_list = mysqli_fetch_array($result_get_state_list))
		{
			$state_list			.= '<option value="'.$row_get_state_list['state'].'">'.ucwords($row_get_state_list['state_name']).'</option>';
		}
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($state_list));
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Country id Not available");
	}
	echo json_encode($response_array);}
/* update state function */
/* update City function */
if((isset($obj->update_city)) == "1" && isset($obj->update_city))// get City list 
{
	$state_val 			= mysqli_real_escape_string($db_con,$obj->state_val);
	$response_array 	= array();
	if($state_val!= "")
	{
		//and city_id = '805' change by punit
		$sql_get_city_list 		= " SELECT * FROM city WHERE state_id = '".$state_val."' order by city_name ASC";
		$result_get_city_list 	= mysqli_query($db_con,$sql_get_city_list) or die(mysqli_error($db_con));
		$city_list				= '<option value="">Select City</option>';
		while($row_get_city_list= mysqli_fetch_array($result_get_city_list))
		{
			$city_list			.= '<option value="'.$row_get_city_list['city_id'].'">'.ucwords($row_get_city_list['city_name']).'</option>';
		}
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($city_list));
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"State id Not available");
	}
	echo json_encode($response_array);			
}
/* update City function */
/* update Logout function */
if((isset($obj->logout_this)) == "1" && isset($obj->logout_this))// expire session for the user
{	
	$cli_session_id = session_id();
	$cust_email		= $_SESSION['front_panel']['name'];
	//remove all session variables	
	session_unset();
	// destroy the session
	session_destroy(); 
	//unset($_SESSION['front_panel']);
	if(isset($_SESSION['front_panel']))
	{
		$response_array = array("Success"=>"fail","resp"=>"Session Not Expired");		
	}
	else
	{		
		$sql_get_cust_id 		= " SELECT cust_id FROM `tbl_customer` where cust_email like '".$cust_email."' ";
		$result_get_cust_id 	= mysqli_query($db_con,$sql_get_cust_id) or die(mysqli_error($db_con));
		$row_get_cust_id		= mysqli_fetch_array($result_get_cust_id);
		$cust_id 				= $row_get_cust_id['cust_id'];
		
		$sql_get_cli_id 		= " SELECT cli_id FROM `tbl_customer_login_info` ";
		$sql_get_cli_id 		.= " where cli_custid = '".$cust_id."' ";
		$sql_get_cli_id 		.= " ORDER BY `cli_id` DESC LIMIT 0,1";
		$result_get_cli_id 		= mysqli_query($db_con,$sql_get_cli_id) or die(mysqli_error($db_con));
		$row_get_cli_id			= mysqli_fetch_array($result_get_cli_id);
		$cli_id 				= $row_get_cli_id['cli_id'];
		
		// ====================================================================================
		// START : 
		// Check the User is present in cart or not, 
		// if exist then check coupon is applied or not, 
		// if coupon is applied and order is not yet proceed then redet the cart entries 
		//[i.e. remove the coupon code from the current products]
		// ====================================================================================
		$sql_check_isExistInCart	= " SELECT * FROM `tbl_cart` ";
		$sql_check_isExistInCart	.= " WHERE `cart_custid`='".$cust_id."' ";
		$sql_check_isExistInCart	.= " 	AND `cart_status`='0' ";
		$sql_check_isExistInCart	.= " 	AND `cart_coupon_code`!='' ";
		$sql_check_isExistInCart	.= " 	AND `cart_type`='abundant' ";
		$res_check_isExistInCart	= mysqli_query($db_con, $sql_check_isExistInCart) or die(mysqli_error($db_con));
		$num_check_isExistInCart	= mysqli_num_rows($res_check_isExistInCart);
		
		if($num_check_isExistInCart != 0)
		{
			// Update The cart for this paricular User
			$sql_update_cart_for_cur_user	= " UPDATE `tbl_cart` ";
			$sql_update_cart_for_cur_user	.= " SET `cart_coupon_code`='', ";
			$sql_update_cart_for_cur_user	.= " 		`cart_discount`='0', ";
			$sql_update_cart_for_cur_user	.= " 		`cart_modified`='".$datetime."' ";
			$sql_update_cart_for_cur_user	.= " WHERE `cart_custid`='".$cust_id."' ";
			$sql_update_cart_for_cur_user	.= " 	AND `cart_type`='abundant' ";
			$sql_update_cart_for_cur_user	.= " 	AND `cart_status`='0' ";
			$sql_update_cart_for_cur_user	.= " 	AND `cart_coupon_code`!='' ";
			$res_update_cart_for_cur_user	= mysqli_query($db_con, $sql_update_cart_for_cur_user) or die(mysqli_error($db_con));
		}
		// ====================================================================================
		// END : 		
		// ====================================================================================
		
		$sql_update_cli_info 	= " UPDATE `tbl_customer_login_info` ";
		$sql_update_cli_info 	.= " 	SET `cli_session_status`= '0', ";
		$sql_update_cli_info 	.= " 		`cli_modified`='".$datetime."' ";
		$sql_update_cli_info 	.= " WHERE `cli_id` = '".$cli_id."'";
		
		$result_update_cli_info = mysqli_query($db_con,$sql_update_cli_info) or die(mysqli_error($db_con));
		
		if($result_update_cli_info)
		{
			ob_end_clean();
			$response_array = array("Success"=>"Success","resp"=>"Session Expired.");
		}
		else
		{
			$response_array = array("Success"=>"Success","resp"=>"Session Expired.Session Variables not cleared...");				
		}		
	}
	echo json_encode($response_array);	
}
/* update Logout function */
/* update Login function */
if((isset($obj->user_login)) == "1" && isset($obj->user_login))// user login
{
	$cust_email 		= trim(mysqli_real_escape_string($db_con,$obj->cust_email));
	$cust_password_login= trim($obj->cust_password);
	$cli_browser_info	= trim(mysqli_real_escape_string($db_con,$obj->cli_browser_info));
	$cli_ip_address 	= trim(mysqli_real_escape_string($db_con,$obj->cli_ip_address));	
	if($cust_email == "" || $cust_password_login == "" )
	{
		$response_array = array("Success"=>"fail","resp"=>"Please Provide Email and Password.");
	}
	else
	{
		$sql_get_user_login 	= "SELECT * FROM `tbl_customer` WHERE `cust_email` like '".$cust_email."' ";
		$result_get_user_login	= mysqli_query($db_con,$sql_get_user_login) or die(mysqli_error($db_con));
		$num_rows_get_user_login= mysqli_num_rows($result_get_user_login);
		if($num_rows_get_user_login == 1)
		{
			$row_get_user_login			= mysqli_fetch_array($result_get_user_login);
			/* data base password */
			$cust_password_db_login		= trim($row_get_user_login['cust_password']);
			/* data base password */
			/* database salt*/
			$cust_salt_value_db_login	= trim($row_get_user_login['cust_salt_value']);
			/* database salt*/
			/*md5(old pwd + salt )*/
			$cust_password_user_login	= trim(md5($cust_salt_value_db_login.$cust_password_login));
			/*md5(old pwd + salt )*/	
			
			if($cust_password_user_login == $cust_password_db_login)
			{
				$cust_id				= $row_get_user_login['cust_id'];
				if(isset($_COOKIE[$cookie_name]))
				{
					$cookie_value		= $_COOKIE[$cookie_name];	
					updateCartProductLogin($cust_id,$cookie_value);// update the product according to session and cookie value
				}
				
				$response_array = custLoginInfo($cust_id,$cust_email,$cli_browser_info,$cli_ip_address);			
				
				$cust_mobile_status	= $row_get_user_login['cust_mobile_status'];
				
				if($cust_mobile_status != 1)
				{
					$cust_mobile_num	= $row_get_user_login['cust_mobile_num'];
					$cust_email			= $row_get_user_login['cust_email'];
					
					$random_string		= "";
					$cust_mobile_query	= " SELECT * FROM `tbl_customer` WHERE 1=1 and `cust_mobile_status` = '".$random_string."' ";
					$cust_mob_status	= randomStringMobileVerification($cust_mobile_query,6);
					
					// Update the otp code for this particular user in tbl_customer table
					$sql_update_mobile_status	= " UPDATE `tbl_customer` ";
					$sql_update_mobile_status	.= " 	SET `cust_mobile_status`='".$cust_mob_status."' ";
					$sql_update_mobile_status	.= " WHERE `cust_email`='".$cust_email."' ";
					$sql_update_mobile_status	.= "	AND `cust_mobile_num`='".$cust_mobile_num."' ";
					$res_update_mobile_status	= mysqli_query($db_con,$sql_update_mobile_status) or die(mysqli_error($db_con));
					
					if($res_update_mobile_status)
					{
						$res_insert_into_tbl_notification	= '';
						$sms_text	= '';
						$sms_text	.= 'Your unique verification code for Planet Educate is '.$cust_mob_status.'. Thank you';
						
						send_sms_msg($cust_mobile_num, $sms_text);
						$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('SMS_register_verify', $sms_text, $cust_email, $cust_mobile_num);
						
						otp_futureDate();		
					}
				}
			}				
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Incorrect Login Details.");
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Incorrect Login Details.");
		}
	}
	echo json_encode($response_array);			
}
/* update Login function */
/* update Forget Password function */
if((isset($obj->forget_pass_mail_send)) == "1" && isset($obj->forget_pass_mail_send))// forget password
{
	$cust_email 	= strtolower(trim(mysqli_real_escape_string($db_con,$obj->cust_email)));
	if($cust_email != "")
	{
		$sql_check_user 		= " SELECT * FROM `tbl_customer` tc WHERE tc.`cust_email` = '".$cust_email."' ";
		$result_check_user		= mysqli_query($db_con,$sql_check_user) or die(mysqli_error($db_con));
		$num_rows_check_user 	= mysqli_num_rows($result_check_user); 
		if($num_rows_check_user == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"<b>".$cust_email."</b> this is not registered email id .");
		}
		else
		{
			$row_check_user 	= mysqli_fetch_array($result_check_user);
			$cust_mobile_num	= $row_check_user['cust_mobile_num'];
			$cust_email			= $row_check_user['cust_email'];
			$cust_fname			= $row_check_user['cust_fname'];
			$subject			= "Forgot Password mail";
			$cust_id			= $row_check_user['cust_id'];
			$cust_salt_value	= $row_check_user['cust_salt_value'];			
			$cust_created		= $row_check_user['cust_created'];				
			$cust_modified		= $row_check_user['cust_modified'];					
			$token				= md5($cust_id.$cust_salt_value.$cust_email.$cust_created.$cust_modified);
			$forget_password_url= $BaseFolder."/page-reset-password.php?userid=".$cust_id."&token=".$token;	
			
			$message_body	= '';
			$message_body .= '<table class="" data-module="main Content" height="100" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
				$message_body .= '<tr>';
					$message_body .= '<td>';
						$message_body .= '<table data-bgcolor="BG Color" height="100" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
							$message_body .= '<tr>';
								$message_body .= '<td>';
									$message_body .= '<table data-bgcolor="BG Color 01" height="100" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
										$message_body .= '<tr>';
											$message_body .= '<td>';
												$message_body .= '<table height="100" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
													$message_body .= '<tr>';
														$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
													$message_body .= '</tr>';
													$message_body .= '<tr>';
														$message_body .= '<td height="100" width="520">';
															$message_body .= '<table align="center" width="520" border="0" cellpadding="0" cellspacing="0">';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">  Password Recovery <br></td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-size:14px; color:#494949; font-family:\'Open Sans\', sans-serif; align="left"><br>Dear '.ucwords($cust_fname).',<br></td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td>';
																		$message_body .= '<table data-bgcolor="Color Button 01" class="table-button230-center" style="border-radius: 900px; " height="36" width="230" align="center" bgcolor="#5bbc2e" border="0" cellpadding="0" cellspacing="0">';
																			$message_body .= '<tr>';
																				$message_body .= '<td style="padding: 5px 5px; margin-bottom:20px;font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;" valign="middle" align="center"><a data-color="Text Button 01" data-size="Text Button 01" data-min="6" data-max="20" href="'.$forget_password_url.'" style="font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;">Reset Password</a></td>';//Here\'s the link to change your password as per your request.</a></td>';
																			$message_body .= '</tr>';
																		$message_body .= '</table>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Name" data-size="Name" align="left"> <br>We have received your request for a new password.<br>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
															$message_body .= '</table>';
														$message_body .= '</td>';
													$message_body .= '</tr>';
													$message_body .= '<tr>';
														$message_body .= '<td data-color="Name" data-size="Name" align="left" style="color:#ccc;"><br> You didn\'t request for a new password? Please write to <a href="mailto:support@planeteducate.com">support@planeteducate.com</a> immediately. <br>';
														$message_body .= '</td>';
													$message_body .= '</tr>';			
													$message_body .= '<tr>';
														$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
													$message_body .= '</tr>';			
												$message_body .= '</table>';
											$message_body .= '</td>';
										$message_body .= '</tr>';
									$message_body .= '</table>';
								$message_body .= '</td>';
							$message_body .= '</tr>';
						$message_body .= '</table>';
					$message_body .= '</td>';
				$message_body .= '</tr>';
			$message_body .= '</table>';			
					
			$message = mail_template_header()."".$message_body."".mail_template_footer();
			//$email_message		.= "Please <a href='".$forget_password_url."'>click here</a> to reset password.";
			if(sendEmail($cust_email,$subject,$message))
			{	
				$res_insert_into_tbl_notification	= '';
				$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_forgot_password', $message, $cust_email, $cust_mobile_num);
				
				sendEmail('support@planeteducate.com',$subject,$message);
				$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_forgot_password', $message, 'support@planeteducate.com', '02261572611');
				
				$response_array = array("Success"=>"Success","resp"=>"<div style='color:green;' align='center'><h4>Please Check your email.</h4></div>");
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Email not sent please try after sometime");
			}
		}
	}
	else
	{			
		$response_array = array("Success"=>"fail","resp"=>"Email Id Blank");
	}
	echo json_encode($response_array);	
}
/* update Forget Password function */
/* update Password through email/mobile function */
if((isset($obj->reset_password)) == "1" && isset($obj->reset_password))// update Password through email/moobile
{
	$cust_session 				= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$cust_password_new_reset	= trim($obj->cust_password_new);
	if($cust_session == "" && $cust_password_new_reset == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Please provide password....");
	}
	else
	{
		$random_string			= "";
		$cust_salt_value_query	= " SELECT * FROM `tbl_customer` WHERE `cust_salt_value` = '".$random_string."'";		
		$cust_salt_value_reset	= randomString($cust_salt_value_query,5);
		$new_cust_password_reset= md5($cust_salt_value_reset.$cust_password_new_reset);
		
		$sql_update_user_reset 		= " UPDATE `tbl_customer` SET `cust_password`= '".$new_cust_password_reset."',`cust_salt_value`='".$cust_salt_value_reset."', ";
		$sql_update_user_reset 		.= "`cust_modified`= '".$datetime."' WHERE cust_email like '".$cust_session."' ";
		$result_update_user_reset 	= mysqli_query($db_con,$sql_update_user_reset) or die(mysqli_error($db_con));
		if($result_update_user_reset)
		{
			$response_array = array("Success"=>"Success","resp"=>'Password updated please <a href="'.$BaseFolder.'/page-login" style="text-decoration:underline;color:#4BBCD7;">Login here</a>...');
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Please try after sometime password not updated...");
		}		
	}
	echo json_encode($response_array);	
}
/* update Password through email function */
/* update customer info */
if((isset($_POST['update_customer'])) == "1" && isset($_POST['update_customer']))// update_customer
{
	$cust_fname_update		= trim(strtolower(mysqli_real_escape_string($db_con,$_POST['cust_fname_update'])));
	$cust_lname_update		= trim(strtolower(mysqli_real_escape_string($db_con,$_POST['cust_lname_update'])));
	$cust_email_update		= trim(mysqli_real_escape_string($db_con,$_POST['cust_email_update']));
	$cust_mobile_num_update	= trim(mysqli_real_escape_string($db_con,$_POST['cust_mobile_num_update']));
	$cust_session			= trim(mysqli_real_escape_string($db_con,$_POST['cust_session']));
	
	/*$response_array = array("Success"=>"fail","resp"=>$_FILES['cust_profile_img']['name']);
	echo json_encode($response_array);	
	exit();*/
	
	if($cust_fname_update == "" || $cust_lname_update == "" || $cust_email_update == "" || $cust_mobile_num_update == "" || $cust_session == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Please provide information to update.");
	}
	else
	{		
		$sql_user_check 		= "select * from tbl_customer where cust_email like '".$cust_session."' ";
		$result_user_check 		= mysqli_query($db_con,$sql_user_check) or die(mysqli_error());
		$num_rows_user_check 	= mysqli_num_rows($result_user_check);
		if($num_rows_user_check == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"User does not exists.");			
		}
		elseif($num_rows_user_check == 1)
		{	
			$row_user_check 	= mysqli_fetch_array($result_user_check);
			$sql_update_user 	= " UPDATE `tbl_customer` SET ";
			if($row_user_check['cust_fname'] != $cust_fname_update)
			{
				$sql_update_user 		.= " `cust_fname`='".$cust_fname_update."', ";
			}
			if($row_user_check['cust_lname'] != $cust_lname_update)			
			{
				$sql_update_user 		.= " `cust_lname`='".$cust_lname_update."', ";				
			}
			if($row_user_check['cust_email'] != $cust_email_update)
			{				
				$random_string			= "";
				$cust_email_query		= " SELECT * FROM `tbl_customer` WHERE 1=1 and `cust_email_status` = '".$random_string."' ";
				$cust_email_status		= randomString($cust_email_query,5);
				
				$sql_update_user 		.= " `cust_email`='".$cust_email_update."', `cust_email_status`='".$cust_email_status."', ";
				
				#======================================================================================
				# START : DN by Prathamesh on 14-10-2016 For Sending Mail on updating the Email-ID
				#======================================================================================
				$subject		= 'Planet Educate - Email Update';
				/* create body for Update mail message */			
				$message_body = '<table class="" data-module="main Content" height="347" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
					$message_body .= '<tr>';
						$message_body .= '<td>';
							$message_body .= '<table data-bgcolor="BG Color" height="347" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
								$message_body .= '<tr>';
									$message_body .= '<td>';
										$message_body .= '<table data-bgcolor="BG Color 01" height="347" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
											$message_body .= '<tr>';
												$message_body .= '<td>';
													$message_body .= '<table height="347" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
														$message_body .= '<tr>';
															$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
														$message_body .= '</tr>';
														$message_body .= '<tr>';
															$message_body .= '<td height="345" width="520">';
																$message_body .= '<table height="300" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
																	$message_body .= '<tr>';
																		$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">  Email Update. </td>';
																	$message_body .= '</tr>';
																	/*$message_body .= '<tr>';
																		$message_body .= '<td data-color="Name" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="letter-spacing: 0.025em; font-size:15px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left">  Greetings!!! </td>';
																	$message_body .= '</tr>';*/
																	/*$message_body .= '<tr>';
																	$message_body .= '<td class="td-pad10" height="50" align="center"><img style="display:block;width:70px !important;height:150px !important;" src="http://www.planeteducate.com/images/customer/default/user_display.png"></td>';
																	$message_body .= '</tr>';*/			
																	$message_body .= '<tr>';
																		$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="font-weight:600; letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> Dear '.ucwords($cust_fname_update).', <br>';
																		$message_body .= '</td>';
																	$message_body .= '</tr>';
																	$message_body .= '<tr>';
																		$message_body .= '<td>';
																			$message_body .= '<table data-bgcolor="Color Button 01" class="table-button230-center" style="border-radius: 900px;" height="36" width="230" align="center" bgcolor="#5bbc2e" border="0" cellpadding="0" cellspacing="0">';
																				$message_body .= '<tr>';
																					$message_body .= '<td style="padding: 5px 5px; font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;" valign="middle" align="center"><a align="center" data-color="Text Button 01" data-size="Text Button 01" data-min="6" data-max="20" href="'.$BaseFolder.'/verify/'.$cust_email_status.'" style="font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;">Verify your Email</a></td>';
																				$message_body .= '</tr>';
																			$message_body .= '</table>';
																		$message_body .= '</td>';
																	$message_body .= '</tr>';
																	$message_body .= '<tr>';
																		$message_body .= '<td data-color="Text" data-size="Text" data-min="8" data-max="30" class="td-pad10" style="font-weight:regular; letter-spacing: 0.000em; line-height:22px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> ';
																		$message_body .= 'Click on the above link to update your email.<br>';
																		$message_body .= '<br><b>Your Planet Educate Account Details:</b> ';
																		$message_body .= '<br>Email ID for login:&nbsp;'.$cust_email_update;
																		$message_body .= '</td>';
																	$message_body .= '</tr>';
																$message_body .= '</table>';
															$message_body .= '</td>';
														$message_body .= '</tr>';
													$message_body .= '</table>';
												$message_body .= '</td>';
											$message_body .= '</tr>';			
											$message_body .= '<tr style="padding-top:10px;">';
												$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> We look forward to make your online shopping a wonderful experience';
												$message_body .= '<br>Please contact us should you have any questions or need further assistance.';
												$message_body .= '</td>';
											$message_body .= '</tr>';
											$message_body .= '<tr>';
												$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
											$message_body .= '</tr>';						
										$message_body .= '</table>';
									$message_body .= '</td>';
								$message_body .= '</tr>';
							$message_body .= '</table>';
						$message_body .= '</td>';
					$message_body .= '</tr>';
				$message_body .= '</table>';
				/* create body for Update mail message */
				/* create a mail template message*/
				$message = mail_template_header()."".$message_body."".mail_template_footer();
				
				if(sendEmail($cust_email_update,$subject,$message))
				{
					$res_insert_into_tbl_notification	= '';
					$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_register_verify', $message, $cust_email_update, $cust_mobile_num_update);
					
					sendEmail('support@planeteducate.com',$subject,$message);
					$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_register_verify', $message, 'support@planeteducate.com', '02261572611');	
				}
				#======================================================================================
				# END : DN by Prathamesh on 14-10-2016 For Sending Mail on updating the Email-ID
				#======================================================================================
			}						
			if($row_user_check['cust_mobile_num'] != $cust_mobile_num_update)
			{
				$random_string			= "";
				$cust_mobile_query		= " SELECT * FROM `tbl_customer` WHERE 1=1 and `cust_mobile_status` = '".$random_string."' ";
				//$cust_mobile_status		= randomString($cust_mobile_query,5);
				$cust_mobile_status		= randomStringMobileVerification($cust_mobile_query,6);
				
				$sql_update_user 		.= " `cust_mobile_num`='".$cust_mobile_num_update."', `cust_mobile_status`='".$cust_mobile_status."', ";				
				
				#======================================================================================
				# START : DN by Prathamesh on 10-10-2016 For Sending SMS on updating the mobile number
				#======================================================================================
				// Send SMS of register verify
				$res_insert_into_tbl_notification	= '';
				$sms_text	= '';
				$sms_text	.= 'Your unique verification code for Planet Educate is '.$cust_mobile_status.'. Thank you';
				
				send_sms_msg($cust_mobile_num_update, $sms_text);
				$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('SMS_register_verify', $sms_text, $cust_email_update, $cust_mobile_num_update);
				
				otp_futureDate();				
				#====================================================================================
				# END : DN by Prathamesh on 10-10-2016 For Sending SMS on updating the mobile number
				#====================================================================================
			}			
			$sql_update_user 	.= "`cust_modified`='".$datetime."' WHERE cust_email like '".$cust_session."' ";
			$result_update_user = mysqli_query($db_con,$sql_update_user) or die(mysqli_error());			
			if($result_update_user)
			{
				$data	= '';
				$data1  = '';
				
				/* Upload Image*/
				if(isset($_FILES['cust_profile_img']['name']) && $_FILES['cust_profile_img']['name'] != "")
				{
					$cust_image							= $_FILES["cust_profile_img"]["name"];
                	$file_tmp							= $_FILES["cust_profile_img"]["tmp_name"];
					$fileSize							= $_FILES['cust_profile_img']['size'];
					$file_ext							= getExtension($cust_image);
					$extension							= array("jpeg","jpg","png");
					if(in_array($file_ext,$extension) && file_exists($file_tmp))
					{
						$cust_temp_dir 					= "../images/customer/".$cust_image;
						if(move_uploaded_file($file_tmp,$cust_temp_dir))
						{
							$file_path_for_image 		= "../images/customer/".md5($row_user_check['cust_created']);
							if(is_dir($file_path_for_image) === false)
							{
								mkdir($file_path_for_image);
							}			
							$cust_image_file_name		= md5($row_user_check['cust_created']).".".$file_ext;
							make_thumb($cust_temp_dir,$file_path_for_image."/".$cust_image_file_name,450,450);
							$sql_update_cust_image 		= " UPDATE `tbl_customer` SET `cust_profile_img` = '".$cust_image_file_name."',`cust_modified`='".$datetime."' ";
							$sql_update_cust_image 		.= " WHERE  cust_id like '".$row_user_check['cust_id']."' ";
							$result_update_cust_image 	= mysqli_query($db_con,$sql_update_cust_image) or die(mysqli_error($db_con));
							if($result_update_cust_image)
							{
								//$response_array = array("Success"=>"Success","resp"=>"User Details & Image Updated...");
								$data	.= 'Image, ';
							}
							else
							{
								//$response_array = array("Success"=>"Success","resp"=>"User Details & Image Updated...");
								$data1	.= 'Image not updated';
							}
						}
						else
						{
							//$response_array = array("Success"=>"Success","resp"=>"User updated image not uploaded...");							
							$data1	.= 'Image not uploaded';
						}
					}
					else
					{
						//response_array = array("Success"=>"Success","resp"=>"User Updated Image Not Updated...");
						$data1	.= 'Incorrect image extension';
					}
				}
				/* Upload Image*/
				
				if($row_user_check['cust_fname'] != $cust_fname_update)
				{
					$data	.= 'First Name, ';
				}
				
				if($row_user_check['cust_lname'] != $cust_lname_update)
				{
					$data	.= 'Last Name, ';
				}	
				
				/* if email id updted then unset old session and create new session for new email id*/
				if($row_user_check['cust_email'] != $cust_email_update)
				{					
					unset($_SESSION['front_panel']);
					if(isset($_SESSION['front_panel']))
					{
						$response_array = array("Success"=>"fail","resp"=>"error404");
					}
					else
					{
						// Get the entire cust info into the session
						$sql_get_cust_info	= " SELECT * FROM `tbl_customer` WHERE `cust_email`='".$cust_email_update."' ";
						$res_get_cust_info	= mysqli_query($db_con, $sql_get_cust_info) or die(mysqli_error($db_con));
						$row_get_cust_info	= mysqli_fetch_array($res_get_cust_info);
						
						$_SESSION['front_panel']			= $row_get_cust_info;
						$_SESSION['front_panel']['name'] 	= $cust_email_update;
						
						if(isset($_SESSION['front_panel']))
						{
							//$response_array = array("Success"=>"Success","resp"=>"User Updated.");
							$data	.= 'Email, ';
						}
						else
						{
							$response_array = array("Success"=>"fail","resp"=>"error404");
						}
					}
				}
				/* if email id updted then unset old session and create new session for new email id*/
				
				/* if Mobile Number updted then unset old session and create new session for new Mobile Number*/
				if($row_user_check['cust_mobile_num'] != $cust_mobile_num_update)
				{					
					unset($_SESSION['front_panel']);
					if(isset($_SESSION['front_panel']))
					{
						$response_array = array("Success"=>"fail","resp"=>"error404");
					}
					else
					{
						// Get the entire cust info into the session
						$sql_get_cust_info	= " SELECT * FROM `tbl_customer` WHERE `cust_email`='".$cust_email_update."' ";
						$res_get_cust_info	= mysqli_query($db_con, $sql_get_cust_info) or die(mysqli_error($db_con));
						$row_get_cust_info	= mysqli_fetch_array($res_get_cust_info);
						
						$_SESSION['front_panel']			= $row_get_cust_info;
						$_SESSION['front_panel']['name'] 	= $cust_email_update;
						
						if(isset($_SESSION['front_panel']))
						{
							//$response_array = array("Success"=>"Success","resp"=>"User Updated.");
							$data	.= 'Mobile Number, ';
						}
						else
						{
							$response_array = array("Success"=>"fail","resp"=>"error404");
						}
					}
				}
				/* if email id updted then unset old session and create new session for new email id*/
				
				if($data=="")
				{
					$data ='Profile ,';
				}
				
				$data	= substr($data, 0, -2);
				
				if($data1 == "")
				{ 
					$response_array	= array("Success"=>"Success", "resp"=>"Your ".$data." has been successfully updated");
				}
				else
				{
					if($data=="")
					{
						$response_array	= array("Success"=>"Success", "resp"=>$data1);
					}
					else
					{
						$response_array	= array("Success"=>"Success", "resp"=>"Your ".$data." has been successfully updated and ".$data1);
					}
				}
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>" Update Failed.Please try after sometime.");
			}
		}
	}
	echo json_encode($response_array);	
}
/* update customer info */
/* check mobile number entered exists or not function */
if((isset($obj->mobile_check)) == "1" && isset($obj->mobile_check))// mobile number check
{
	$cust_mobile_num 		= trim(mysqli_real_escape_string($db_con,$obj->cust_mobile_num));
	$req_page 				= trim(mysqli_real_escape_string($db_con,$obj->req_page));		
	$cust_session			= trim(mysqli_real_escape_string($db_con,$obj->cust_session));		
	if($cust_mobile_num == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty data");					
	}
	else
	{
		if(strlen($cust_mobile_num) != 10)
		{
			// change by punit not require to send message like this $response_array = array("Success"=>"fail","resp"=>"<label style='color:#f00;font-weight:normal'>Mobile Number not less than 10 digits.</label>");
			$response_array = array("Success"=>"fail","resp"=>"<label style='color:#f00;font-weight:normal'></label>");
		}
		else
		{
			$mobile_check_sql 		= "select * from tbl_customer tc where cust_mobile_num like '".$cust_mobile_num."' ";
			if($cust_session != "")
			{
				$mobile_check_sql 	.= " and tc.cust_email != '".$cust_session."' ";
			}		
			$res_mobile_check 		= mysqli_query($db_con,$mobile_check_sql) or die(mysqli_error());
			$num_rows_mobile_check 	= mysqli_num_rows($res_mobile_check);
			if($num_rows_mobile_check == 0)
			{
			if($req_page == "register")
			{
				$mobile_check_msg = "<label style='color:green;font-weight:normal'><b>'".$cust_mobile_num."'</b> valid</label>";
				$response_array = array("Success"=>"Success","resp"=>$mobile_check_msg);							
			}
			elseif($req_page == "login" || $req_page == "forget_pass")
			{
				$mobile_check_msg = "<label style='color:#f00;font-weight:normal'><b>User for '".$cust_mobile_num."'</b> does not exists.</label>";
				$response_array = array("Success"=>"fail","resp"=>$mobile_check_msg);
			}
			elseif($req_page == "myaccount" )	
			{
				$mobile_check_msg = "";
				$response_array = array("Success"=>"Success","resp"=>$mobile_check_msg);				
			}
		}	
			else if($num_rows_mobile_check >= 1)
			{
				if($req_page == "register")
				{
					$mobile_check_msg = "<label style='color:#f00;font-weight:normal'>You are already register with <b>'".$cust_mobile_num."'</b></label>";
					$response_array = array("Success"=>"fail","resp"=>$mobile_check_msg);								
				}
				elseif($req_page == "login" || $req_page == "forget_pass")
				{
					$mobile_check_msg = "<label style='color:green;font-weight:normal'>Valid Mobile Number: <b>'".$cust_mobile_num."'</b></label>";
					$response_array = array("Success"=>"Success","resp"=>$mobile_check_msg);				
				}
				elseif($req_page == "myaccount" )
				{
					$mobile_check_msg = "<label style='color:#f00;font-weight:normal'>Another user already register with <b>'".$cust_mobile_num."'</b></label>";
					$response_array = array("Success"=>"fail","resp"=>$mobile_check_msg);								
				}
			}			
		}
	}
	echo json_encode($response_array);		
}
/* check mobile number entered exists or not function */
/* check email id entered exists or not function */
if((isset($obj->email_check)) == "1" && isset($obj->email_check))// email id check
{
	$cust_email 			= trim(mysqli_real_escape_string($db_con,$obj->cust_email));
	$req_page 				= trim(mysqli_real_escape_string($db_con,$obj->req_page));
	$cust_session			= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	if($cust_email == "" || $req_page == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty data");					
	}
	else
	{
		$email_check_sql 		= "select * from tbl_customer tc where cust_email like '".$cust_email."' ";
		if($cust_session != "")
		{
			$email_check_sql 	.= " and tc.cust_email != '".$cust_session."' ";
		}			
		$result_email_check 	= mysqli_query($db_con,$email_check_sql) or die(mysqli_error());
		$num_rows_email_check 	= mysqli_num_rows($result_email_check);
		if($num_rows_email_check == 0)
		{
			if($req_page == "register")
			{
				$email_check_msg = "<label style='color:green;font-weight:normal'><b>'".$cust_email."'</b> valid</label>";
				$response_array = array("Success"=>"Success","resp"=>$email_check_msg);							
			}
			elseif($req_page == "login" || $req_page == "forget_pass")
			{
				$email_check_msg = "<label style='color:#f00;font-weight:normal'><b>User for '".$cust_email."'</b> does not exists.</label>";
				$response_array = array("Success"=>"fail","resp"=>$email_check_msg);
			}
			elseif($req_page == "myaccount" )	
			{
				$email_check_msg = "";
				$response_array = array("Success"=>"Success","resp"=>$email_check_msg);				
			}
		}	
		else
		{
			if($req_page == "register")
			{
				$email_check_msg = "<label style='color:#f00;font-weight:normal'>You are already register with <b>'".$cust_email."'</b></label>";
				$response_array = array("Success"=>"fail","resp"=>$email_check_msg);								
			}
			elseif($req_page == "login" || $req_page == "forget_pass")
			{
				$email_check_msg = "<label style='color:green;font-weight:normal'>Valid email id: <b>'".$cust_email."'</b></label>";
				$response_array = array("Success"=>"Success","resp"=>$email_check_msg);				
			}
			elseif($req_page == "myaccount" )
			{
				$email_check_msg = "<label style='color:#f00;font-weight:normal'>Another user already register with <b>'".$cust_email."'</b></label>";
				$response_array = array("Success"=>"fail","resp"=>$email_check_msg);								
			}			
		}
	}
	echo json_encode($response_array);	
}
/* check email id entered exists or not function */

/* mobile verify*/
/*if((isset($obj->cust_mobile_verify)) == "1" && isset($obj->cust_mobile_verify))// user check
{
	$cust_session 					= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$cust_mobile_status				= trim(mysqli_real_escape_string($db_con,$obj->cust_mobile_status));
	if($cust_session == "" && $cust_mobile_status == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Please fill the details.");
	}
	else
	{
		$sql_verify_mobile 			= " select * from `tbl_customer` where cust_mobile_status = '".$cust_mobile_status."' and cust_email = '".$cust_session."' ";
		$result_verify_mobile 		= mysqli_query($db_con,$sql_verify_mobile) or die(mysqli_error($db_con));
		$num_rows_verify_mobile 	= mysqli_num_rows($result_verify_mobile);
		if($num_rows_verify_mobile == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"Wrong Verification Code.");
		}
		elseif($num_rows_verify_mobile == 1)
		{
			$row_verify_mobile		= mysqli_fetch_array($result_verify_mobile);
			$cust_id	 			= $row_verify_mobile['cust_id'];	
			$sql_update_mobile 		= " UPDATE `tbl_customer` SET `cust_mobile_status`='1',`cust_modified`='".$datetime."' WHERE `cust_id` = '".$cust_id."' and cust_email = '".$cust_session."'  ";
			$result_update_mobile	= mysqli_query($db_con,$sql_update_mobile) or die(mysqli_error($db_con));		
			if($result_update_mobile)
			{
				offerEmailTOCustomer($cust_id);// this function Will send Current Offer on Registration and Verification of Email or Mobile.
				$response_array = array("Success"=>"Success","resp"=>"Verification Success");						
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Please try after sometime.");
			}			
		}
	}
	echo json_encode($response_array);		
}*/
/* mobile verify*/
/* check User registered entered exists or not function */
if((isset($obj->user_register)) == "1" && isset($obj->user_register))// user check
{
	$cust_fname 			= strtolower(trim(mysqli_real_escape_string($db_con,$obj->cust_fname)));
	$cust_lname 			= strtolower(trim(mysqli_real_escape_string($db_con,$obj->cust_lname)));
	$cust_email 			= strtolower(trim(mysqli_real_escape_string($db_con,$obj->cust_email)));
	$cust_mobile_num		= trim(mysqli_real_escape_string($db_con,$obj->cust_mobile_num));
	$cust_address 			= trim(mysqli_real_escape_string($db_con,$obj->cust_address));
	$cust_country  			= trim(mysqli_real_escape_string($db_con,$obj->cust_country));
	$cust_state 			= trim(mysqli_real_escape_string($db_con,$obj->cust_state));
	$cust_city 				= trim(mysqli_real_escape_string($db_con,$obj->cust_city));
	$cust_pincode 			= trim(mysqli_real_escape_string($db_con,$obj->cust_pincode));
	$cust_password 			= trim($obj->cust_password);
	
	$cli_browser_info		= trim(mysqli_real_escape_string($db_con,$obj->cli_browser_info));
	$cli_ip_address 		= trim(mysqli_real_escape_string($db_con,$obj->cli_ip_address));
	
	$cust_type				= trim(mysqli_real_escape_string($db_con,$obj->cust_type));	
	
	$cust_status 			= 1;// 1 for active ,0 for Inactive
	$random_string			= "";
	$sql_check 				= " SELECT * FROM `tbl_customer` WHERE 1=1 and ";
	
	$cust_email_query		= $sql_check." `cust_email_status` = '".$random_string."' ";
	$cust_email_status		= randomString($cust_email_query,5);
	
	$cust_mobile_query		= $sql_check." `cust_mobile_status` = '".$random_string."' ";
	//$cust_mobile_status		= randomString($cust_mobile_query,5);
	$cust_mobile_status		= randomStringMobileVerification($cust_mobile_query,6);
	
	$cust_salt_value_query 	= $sql_check." `cust_salt_value` = '".$random_string."' ";
	$cust_salt_value		= randomString($cust_salt_value_query,5);
	
	$response_array 		= array();	
		
	$sql_chkcust 			= " SELECT * FROM `tbl_customer` WHERE `cust_email`='".$cust_email."' OR `cust_mobile_num` LIKE '".$cust_mobile_num."' ";
	$res_chkcust 			= mysqli_query($db_con,$sql_chkcust);
	$num_rows_checkust		= mysqli_num_rows($res_chkcust);
	if($num_rows_checkust  != 0)
	{
		$response_array = array("Success"=>"fail", "resp"=>"User Already Exist.");
	}
	else
	{
		$table_name			= "tbl_customer";
		$new_id				= "cust_id";
		$cust_id 			= getNewId($new_id,$table_name);
		
		$final_password		= md5($cust_salt_value.$cust_password);
		
		$sql_insert_cust	= " INSERT INTO `tbl_customer`(`cust_id`, `cust_fname`, `cust_lname`, `cust_email`, `cust_email_status`,`cust_type_id`, ";
		$sql_insert_cust	.= " `cust_mobile_num`, `cust_mobile_status`, `cust_password`, `cust_salt_value`, `cust_status`, `cust_created`) ";
		$sql_insert_cust	.= " VALUES ('".$cust_id."', '".$cust_fname."', '".$cust_lname."', '".$cust_email."', '".$cust_email_status."','".$cust_type."', ";
		$sql_insert_cust	.= " '".$cust_mobile_num."', '".$cust_mobile_status."', '".$final_password."', '".$cust_salt_value."', '".$cust_status."', '".$datetime."') ";
		$res_insert_cust 	= mysqli_query($db_con,$sql_insert_cust) or die(mysqli_error($db_con));		
		
		if($res_insert_cust)
		{
			$email			= $cust_email;
			$subject		= 'Welcome to Planet Educate - Your one-stop shop for all your educational needs. ';
			/* create body for register mail message */			
			$message_body = '<table class="" data-module="main Content" height="347" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
				$message_body .= '<tr>';
					$message_body .= '<td>';
						$message_body .= '<table data-bgcolor="BG Color" height="347" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
							$message_body .= '<tr>';
								$message_body .= '<td>';
									$message_body .= '<table data-bgcolor="BG Color 01" height="347" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
										$message_body .= '<tr>';
											$message_body .= '<td>';
												$message_body .= '<table height="347" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
													$message_body .= '<tr>';
														$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
													$message_body .= '</tr>';
													$message_body .= '<tr>';
														$message_body .= '<td height="345" width="520">';
															$message_body .= '<table height="300" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">  Welcome to Planet Educate &ndash; Your one-stop shop for all your educational needs. </td>';
																$message_body .= '</tr>';
																/*$message_body .= '<tr>';
																	$message_body .= '<td data-color="Name" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="letter-spacing: 0.025em; font-size:15px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left">  Greetings!!! </td>';
																$message_body .= '</tr>';*/
																/*$message_body .= '<tr>';
																$message_body .= '<td class="td-pad10" height="50" align="center"><img style="display:block;width:70px !important;height:150px !important;" src="http://www.planeteducate.com/images/customer/default/user_display.png"></td>';
																$message_body .= '</tr>';*/			
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="font-weight:600; letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> Dear '.ucwords($cust_fname).', <br>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td>';
																		$message_body .= '<table data-bgcolor="Color Button 01" class="table-button230-center" style="border-radius: 900px;" height="36" width="230" align="center" bgcolor="#5bbc2e" border="0" cellpadding="0" cellspacing="0">';
																			$message_body .= '<tr>';
																				$message_body .= '<td style="padding: 5px 5px; font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;" valign="middle" align="center"><a align="center" data-color="Text Button 01" data-size="Text Button 01" data-min="6" data-max="20" href="'.$BaseFolder.'/verify/'.$cust_email_status.'" style="font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;">Verify your Email</a></td>';
																			$message_body .= '</tr>';
																		$message_body .= '</table>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Text" data-size="Text" data-min="8" data-max="30" class="td-pad10" style="font-weight:regular; letter-spacing: 0.000em; line-height:22px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> ';
																	$message_body .= 'Click on the above link to complete the registration process.<br>';
																	$message_body .= '<br><b>Your Planet Educate Account Details:</b> ';
																	$message_body .= '<br>Email ID for login:&nbsp;'.$cust_email;
																	$message_body .= '<br>Thank you for signing up on <a href="www.planeteducate.com">www.planeteducate.com</a><br>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
															$message_body .= '</table>';
														$message_body .= '</td>';
													$message_body .= '</tr>';
													
												$message_body .= '</table>';
											$message_body .= '</td>';
										$message_body .= '</tr>';			
										$message_body .= '<tr style="padding-top:10px;">';
											$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> We look forward to make your online shopping a wonderful experience';
											$message_body .= '<br>Please contact us should you have any questions or need further assistance.';
											$message_body .= '</td>';
										$message_body .= '</tr>';
										$message_body .= '<tr>';
											$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
										$message_body .= '</tr>';						
									$message_body .= '</table>';
								$message_body .= '</td>';
							$message_body .= '</tr>';
						$message_body .= '</table>';
					$message_body .= '</td>';
				$message_body .= '</tr>';
			$message_body .= '</table>';
			/* create body for register mail message */
			/* create a mail template message*/
			$message = mail_template_header()."".$message_body."".mail_template_footer();
			if(sendEmail($email,$subject,$message))
			{	
				$res_insert_into_tbl_notification	= '';
				$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_register_verify', $message, $cust_email, $cust_mobile_num);
				
				sendEmail('support@planeteducate.com',$subject,$message);
				$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_register_verify', $message, 'support@planeteducate.com', '02261572611');
				
				// Send SMS of register verify
				$sms_text	= '';
				$sms_text	.= 'Your unique verification code for Planet Educate is '.$cust_mobile_status.'. Thank you';
				
				send_sms_msg($cust_mobile_num, $sms_text);
				$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('SMS_register_verify', $sms_text, $cust_email, $cust_mobile_num);
				
				otp_futureDate();
				
				$response_array = array("Success"=>"Success","resp"=>"Please check your email inbox or spam box and verify your email address.");
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Email not sent please try after sometime");
			}			
			if((strcmp($cust_address, "") !== 0) && (strcmp($cust_country, "") !== 0) && (strcmp($cust_state, "") !== 0) && (strcmp($cust_city, "") !== 0) && (strcmp($cust_pincode, "") !== 0 ))
			{
				$add_status	= '1';
				
				$sql_getlastrec	= " SELECT `add_id` FROM `tbl_address_master` ORDER BY `add_id` DESC LIMIT 0,1 ";
				$res_getlastrec	= mysqli_query($db_con, $sql_getlastrec) or die(mysqli_error($db_con));
				$num_getlastrec = mysqli_num_rows($res_getlastrec);
				
				if(strcmp($num_getlastrec, "0")===0)
				{
					$add_id = '1';
				}
				else
				{
					$row_getlastrec	= mysqli_fetch_array($res_getlastrec);
					$add_id			= $row_getlastrec['add_id'] + 1;
				}
				
				$sql_insertaddrs	= " INSERT INTO `tbl_address_master`(`add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`, ";
				$sql_insertaddrs	.= "`add_user_id`, `add_user_type`, `add_address_type`, `add_status`, `add_created`) ";
				$sql_insertaddrs	.= " VALUES ('".$add_id."', '".$cust_address."', '".$cust_state."', '".$cust_city."', '".$cust_pincode."', ";
				$sql_insertaddrs	.= " '".$cust_id."', 'customer', 'default', '".$add_status."', '".$datetime."') ";
				$res_insertaddrs	= mysqli_query($db_con, $sql_insertaddrs) or die(mysqli_error($db_con));
				
				if($res_insertaddrs)
				{
					$response_array = array("Success"=>"Success","resp"=>"Address Inserted Successfully");
				}
				else
				{
					$response_array = array("Success"=>"fail","resp"=>"Address Insertion Error");	
				}
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>"Address Is Empty And Customer Inserted Successfully");
			}			
			custLoginInfo($cust_id,$cust_email,$cli_browser_info,$cli_ip_address);
			if(isset($_COOKIE[$cookie_name]))
			{
				$cookie_value				= $_COOKIE[$cookie_name];	
				updateCartProductLogin($cust_id,$cookie_value);// update the product according to session and cookie value
			}							
			$response_array = array("Success"=>"Success","resp"=>"Please check your email inbox or spam box and verify your email address.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"User Not Registered...");
		}
	}
	echo json_encode($response_array);	
}
/* check User registered entered exists or not function 
if((isset($obj->search_on_front)) == "1" && isset($obj->search_on_front))// Search Front
{
	$search_text				= trim(mysqli_real_escape_string($db_con,$obj->search_text));
	if($search_text != "")
	{
		$search_data					= "";
		$search_data					= "<ul>";		
		$sql_get_search_data_1 			= " SELECT `cat_id`,`cat_name`,`cat_type`,`cat_slug` FROM `tbl_level` tlc WHERE `cat_name` like '%".$search_text."%' ";
		$result_get_search_data_1 		= mysqli_query($db_con,$sql_get_search_data_1) or die(mysqli_error($db_con));
		$num_rows_get_search_data_1		= mysqli_num_rows($result_get_search_data_1);
		if($num_rows_get_search_data_1 != 0)
		{
			while($row_get_search_data_1 	= mysqli_fetch_array($result_get_search_data_1))
			{
				if($row_get_search_data_1['cat_type'] == 'parent')
				{					
					$search_data	.= '<li><a href="'.$BaseFolder.'/'.$row_get_search_data_1['cat_slug'].'-'.$row_get_search_data_1['cat_id'].'-d">'.$row_get_search_data_1['cat_name'].'</a></li>';
				}
				else
				{
					$sql_get_parent_search_data		= " SELECT `cat_id`,`cat_slug` FROM `tbl_level` WHERE `cat_id` like '%".$row_get_search_data_1['cat_type']."%' ";
					$result_get_parent_search_data	= mysqli_query($db_con,$sql_get_parent_search_data) or die(mysqli_error($db_con));
					$row_get_parent_search_data 	= mysqli_fetch_array($result_get_parent_search_data);
					$search_data					.= '<li><a href="'.$BaseFolder.'/'.$row_get_parent_search_data['cat_slug'].'-'.$row_get_parent_search_data['cat_id'].'/'.$row_get_search_data_1['cat_slug'].'-'.$row_get_search_data_1['cat_id'].'-e">'.$row_get_search_data_1['cat_name'].'</a></li>';
				}
			}			
		}
		
		$sql_get_search_data_2 			= " SELECT `cat_id`,`cat_name`,`cat_type`,`cat_slug` FROM `tbl_category` tcc WHERE `cat_name` like '%".$search_text."%' ";
		$result_get_search_data_2 		= mysqli_query($db_con,$sql_get_search_data_2) or die(mysqli_error($db_con));
		$num_rows_get_search_data_2		= mysqli_num_rows($result_get_search_data_2);
		if($num_rows_get_search_data_2 != 0)
		{
			while($row_get_search_data_2 	= mysqli_fetch_array($result_get_search_data_2))
			{
				if($row_get_search_data_1['cat_type'] == 'parent')
				{
					$search_data	.= '<li><a href="'.$BaseFolder.'/'.$row_get_search_data_2['cat_slug'].'-'.$row_get_search_data_2['cat_id'].'-b">'.$row_get_search_data_2['cat_name'].'</a></li>';
				}
				else
				{
					$sql_get_parent_search_data		= " SELECT `cat_id`,`cat_slug` FROM `tbl_category` WHERE `cat_id` like '%".$row_get_search_data_2['cat_type']."%' ";
					$result_get_parent_search_data	= mysqli_query($db_con,$sql_get_parent_search_data) or die(mysqli_error($db_con));
					$row_get_parent_search_data 	= mysqli_fetch_array($result_get_parent_search_data);					
					$search_data	.= '<li><a href="'.$BaseFolder.'/'.$row_get_parent_search_data['cat_slug'].'-'.$row_get_parent_search_data['cat_id'].'/'.$row_get_search_data_2['cat_slug'].'-'.$row_get_search_data_2['cat_id'].'-c">'.$row_get_search_data_2['cat_name'].'</a></li>';
				}
			}
		}
		
		$prod_array 					= array();
		$sql_get_search_data_3 			= " SELECT `prod_id`, `prod_model_number`, `prod_title`,`prod_slug` FROM `tbl_products_master` WHERE ";
		$sql_get_search_data_3 			.= " `prod_model_number` like '%".$search_text."%' or `prod_name` like '%".$search_text."%' LIMIT 0,20 ";
		$result_get_search_data_3 		= mysqli_query($db_con,$sql_get_search_data_3) or die(mysqli_error($db_con));
		$num_rows_get_search_data_3		= mysqli_num_rows($result_get_search_data_3);
		if($num_rows_get_search_data_3 != 0)
		{
			while($row_get_search_data_3 	= mysqli_fetch_array($result_get_search_data_3))
			{
				array_push($prod_array,$row_get_search_data_3['prod_id']);
				$search_data				.= '<li><a href="'.$BaseFolder.'/'.$row_get_search_data_3['prod_slug'].'-'.$row_get_search_data_3['prod_id'].'-a">'.$row_get_search_data_3['prod_title'].'('.$row_get_search_data_3['prod_model_number'].')</a></li>';
			}
		}
		
		$sql_get_search_data_4 			= " SELECT prod_slug,prod_id,prod_title,prod_model_number FROM `tbl_products_master` tpm INNER JOIN `tbl_product_filters` tpf ON tpm.prod_id = tpf.prodfilt_prodid INNER JOIN `tbl_filters` tf ON ";
		$sql_get_search_data_4 			.= " tpf.prodfilt_prodid  = tf.filt_id where filt_name like '%".$search_text."%' ";
		if(sizeof($prod_array) != 0)		
		{
			//$sql_get_search_data_4 			.= " and prod_id NOT IN (".$prod_array.") ";
		}
		$sql_get_search_data_4 			.= " LIMIT 0,20 ";
		$result_get_search_data_4 		= mysqli_query($db_con,$sql_get_search_data_4) or die(mysqli_error($db_con));
		$num_rows_get_search_data_4		= mysqli_num_rows($result_get_search_data_4);
		if($num_rows_get_search_data_4 != 0)
		{
			while($row_get_search_data_4 	= mysqli_fetch_array($result_get_search_data_4))
			{
				array_push($prod_array,$row_get_search_data_4['prod_id']);				
				$search_data				.= '<li><a href="'.$BaseFolder.'/'.$row_get_search_data_4['prod_slug'].'-'.$row_get_search_data_4['prod_id'].'-a">'.$row_get_search_data_4['prod_title'].'('.$row_get_search_data_4['prod_model_number'].')</a></li>';
			}			
		}
		
		$sql_get_search_data_5 			= " SELECT prod_slug,prod_id,prod_title,prod_model_number FROM `tbl_products_master` tpm INNER JOIN `tbl_products_specifications` tps ON tpm.prod_id = tps.prod_spec_prodid INNER JOIN `tbl_specifications_master` tsm ON ";
		$sql_get_search_data_5 			.= " tps.prod_spec_prodid  = tsm.spec_id where spec_name like '%".$search_text."%' ";
		if(sizeof($prod_array) != 0)		
		{
			//$sql_get_search_data_5 		.= " and prod_id NOT IN (".$prod_array.") ";
		}
		$sql_get_search_data_5 			.= " LIMIT 0,20 ";
		$result_get_search_data_5 		= mysqli_query($db_con,$sql_get_search_data_5) or die(mysqli_error($db_con));
		$num_rows_get_search_data_5		= mysqli_num_rows($result_get_search_data_5);
		if($num_rows_get_search_data_5 != 0)
		{
			while($row_get_search_data_5 	= mysqli_fetch_array($result_get_search_data_5))
			{
				array_push($prod_array,$row_get_search_data_5['prod_id']);				
				$search_data				.= '<li><a href="'.$BaseFolder.'/'.$row_get_search_data_5['prod_slug'].'-'.$row_get_search_data_5['prod_id'].'-a">'.$row_get_search_data_5['prod_title'].'('.$row_get_search_data_5['prod_model_number'].')</a></li>';
			}			
		}
		$search_data					.= "</ul>";		
		if(trim($search_data) == "")
		{
			$search_data = "No data Found...";
		}
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($search_data));
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"");
	}
	echo json_encode($response_array);
} search old type with suggestion */
/* search new shows suggestions */
if((isset($obj->search_on_front)) == "1" && isset($obj->search_on_front))// Search Front
{
	$search_term				= trim(mysqli_real_escape_string($db_con,$obj->search_text));
	if($search_term != "")
	{
		$sql_get_prod_id 		= " SELECT tst.prod_id,(SELECT `prod_slug` FROM `tbl_products_master` tpm WHERE tpm.`prod_id` = tst.prod_id) as prod_slug,";
		$sql_get_prod_id 		.= " (SELECT `prod_title` FROM `tbl_products_master` tpm WHERE tpm.`prod_id` = tst.prod_id) as prod_title, ";
		$sql_get_prod_id 		.= " (SELECT `prod_model_number` FROM `tbl_products_master` tpm WHERE tpm.`prod_id` = tst.prod_id) as prod_model_number ";		
		$sql_get_prod_id 		.= " FROM `tbl_search_terms` tst where `search_keywords` like '%=>".$search_term."%' LIMIT 0,20 ";
		$result_get_prod_id		= mysqli_query($db_con,$sql_get_prod_id) OR die(mysqli_error($db_con));
		$num_rows_get_prod_id 	= mysqli_num_rows($result_get_prod_id);
		if($num_rows_get_prod_id != 0)
		{
			$search_data		= "<ul>";				
			while($row_get_prod_id = mysqli_fetch_array($result_get_prod_id))
			{
				$search_data	.= '<li><a href="'.$BaseFolder.'/'.$row_get_prod_id['prod_slug'].'-'.$row_get_prod_id['prod_id'].'-a">'.$row_get_prod_id['prod_title'].'('.$row_get_prod_id['prod_model_number'].')</a></li>';				
			}						
			$search_data		.= "</ul>";				
			$response_array = array("Success"=>"Success","resp"=>utf8_encode($search_data));
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"");		
	}
	echo json_encode($response_array);
}
/* search new shows suggestions */
if((isset($obj->insert_user_review)) == "1" && isset($obj->insert_user_review))// Search Front
{
	$cust_session			= mysqli_real_escape_string($db_con,$obj->cust_session);
	$rating					= mysqli_real_escape_string($db_con,$obj->rating);
	$review_content			= mysqli_real_escape_string($db_con,$obj->review_content);
	$review_prod_id			= mysqli_real_escape_string($db_con,$obj->review_prod_id);
	$review_title			= mysqli_real_escape_string($db_con,$obj->review_title);

	$sql_user_check 		= "select * from tbl_customer where cust_email like '".$cust_session."' ";

	$result_user_check 		= mysqli_query($db_con,$sql_user_check) or die(mysqli_error($db_con));
	$num_rows_user_check 	= mysqli_num_rows($result_user_check);
	if($num_rows_user_check == 0)
	{
		$response_array = array("Success"=>"fail","resp"=>"User does not exists.");			
	}
	else if( $rating == "" || $review_content == "" || $review_title == "" )
	{
		$response_array = array("Success"=>"fail","resp"=>"Please fill all the details !");	
	}
	else if($num_rows_user_check == 1)
	{	
		$row_user_check 			= mysqli_fetch_array($result_user_check);
		$review_cust_id				= $row_user_check['cust_id'];
		
		$sql_insert_new_review 		= " INSERT INTO `tbl_review_master`(`review_title`, `review_content`, `review_cust_id`, `review_prod_id`, `review_star_rating`,";
		$sql_insert_new_review		.= "  `review_status`, `review_created`, `review_created_by`,`review_thread_id`) VALUES ";
		$sql_insert_new_review 		.= "  ('".$review_title."','".$review_content."','".$review_cust_id."','".$review_prod_id."','".$rating."',";
		$sql_insert_new_review 		.= "  '2','".$datetime."','0','1')";
		$result_insert_new_review 	= mysqli_query($db_con,$sql_insert_new_review) or die(mysqli_query($db_con));		
		
		$sql_get_product_name		= "select * from tbl_products_master where prod_id='".$review_prod_id."'";
		$result_get_product_name	= mysqli_query($db_con,$sql_get_product_name);
		$get_product_name			= mysqli_fetch_array($result_get_product_name);
		
		$sql_get_customer_name		= "select * from tbl_customer where cust_id='".$review_cust_id."'";
		$result_get_customer_name	= mysqli_query($db_con,$sql_get_customer_name);
		$get_customer_name			= mysqli_fetch_array($result_get_customer_name);
							
		if($result_insert_new_review)
		{			
			$email					= $cust_email;
			$subject				= '  Planet Educate ';
			
			/* create body for register mail message */			
			$message_body = '<table class="" data-module="main Content" height="347" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
				$message_body .= '<tr>';
					$message_body .= '<td>';
						$message_body .= '<table data-bgcolor="BG Color" height="347" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
							$message_body .= '<tr>';
								$message_body .= '<td>';
									$message_body .= '<table data-bgcolor="BG Color 01" height="347" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
										$message_body .= '<tr>';
											$message_body .= '<td>';
												$message_body .= '<table height="347" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
													$message_body .= '<tr>';
														$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
													$message_body .= '</tr>';
													$message_body .= '<tr>';
														$message_body .= '<td height="345" width="520">';
															$message_body .= '<table height="300" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">  Welcome to Planet Educate </td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td class="td-pad10" height="50" align="center"><img style="display:block;width:70px !important;height:150px !important;" src="http://www.planeteducate.com/images/customer/default/user_display.png"></td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="font-weight:600; letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#262626; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> '.ucwords($cust_fname).' '.ucwords($cust_lname).' <br>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Text" data-size="Text" data-min="8" data-max="30" class="td-pad10" style="font-weight:regular; letter-spacing: 0.000em; line-height:22px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">  <a href="www.planeteducate.com">www.planeteducate.com</a>';
																	$message_body .= '<br>Following review has been submitted by  '.ucwords($get_customer_name['cust_fname']).' ' .ucwords($get_customer_name['cust_lname']);
																	$message_body .= '<br>Poduct Title:'.$get_product_name['prod_title'];
																	$message_body .= '<br>Product Name:'.$get_product_name['prod_name'];
																	$message_body .= '</td>';
																$message_body .= '</tr>';
															$message_body .= '</table>';
														$message_body .= '</td>';
													$message_body .= '</tr>';
												$message_body .= '</table>';
											$message_body .= '</td>';
										$message_body .= '</tr>';
									$message_body .= '</table>';
								$message_body .= '</td>';
							$message_body .= '</tr>';
						$message_body .= '</table>';
					$message_body .= '</td>';
				$message_body .= '</tr>';
			$message_body .= '</table>';
			/* create body for register mail message */
			
			/* create a mail template message*/
			$message = mail_template_header()."".$message_body."".mail_template_footer();
			if(sendEmail($email,$subject,$message))
			{	
				sendEmail('support@planeteducate.com',$subject,$message);
			}			
			$response_array = array("Success"=>"Success","resp"=>"Review Submitted Successfully");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Review Submission Failed");							
		}		
	}
	echo json_encode($response_array);	
}
if((isset($obj->insert_thread_review)) == "1" && isset($obj->insert_thread_review))// Search Front
{	
	$thread_reply					= mysqli_real_escape_string($db_con,$obj->thread_reply);
	$review_id						= mysqli_real_escape_string($db_con,$obj->review_id);
	$cust_session					= mysqli_real_escape_string($db_con,$obj->cust_session);
	
	$sql_get_thread_cust_id 		= "select * from tbl_customer where cust_email like '".$cust_session."' ";
	$result_get_thread_cust_id 		= mysqli_query($db_con,$sql_get_thread_cust_id) or die(mysqli_error($db_con));
	$row_get_thread_cust_id 		= mysqli_fetch_array($result_get_thread_cust_id);

	$sql_get_all_threads			="Select * from tbl_review_master where review_id='".$review_id."'";
	$result_get_all_threads			=mysqli_query($db_con,$sql_get_all_threads);
	$get_all_threads				=mysqli_fetch_array($result_get_all_threads);
			
	$sql_get_thread_cust_id 		= "select * from tbl_review_master where review_cust_id ='".$get_all_threads['review_cust_id']."' and review_prod_id ='".$get_all_threads['review_prod_id']."' and review_title ='".$get_all_threads['review_title']."'";	

	$result_get_thread_cust_id		= mysqli_query($db_con,$sql_get_thread_cust_id) or die(mysqli_error($db_con));
	$num_rows_thread_check 	= mysqli_num_rows($result_get_thread_cust_id);
	if($num_rows_thread_check == 0)
	{
		$response_array = array("Success"=>"fail","resp"=>"Thread Does Not exist !.");			
	}
	else
	{	
		$row_thread	= mysqli_fetch_array($result_get_thread_cust_id);
		
		$sql_insert_thread_reply 	= " INSERT INTO `tbl_review_master`(`review_title`, `review_content`, `review_cust_id`, `review_prod_id`, `review_star_rating`,";
		$sql_insert_thread_reply	.= "  `review_status`, `review_created`, `review_created_by`,`review_thread_id`) VALUES ";
		$sql_insert_thread_reply 	.= "  ('".$row_thread['review_title']."','".$thread_reply."','".$row_get_thread_cust_id['cust_id']."','".$row_thread['review_prod_id']."','".$row_thread['review_star_rating']."',";
		$sql_insert_thread_reply 	.= "  '0','".$datetime."','0','".++$num_rows_thread_check."')";
		$result_insert_thread_reply 	= mysqli_query($db_con,$sql_insert_thread_reply) or die(mysqli_query($db_con));					
		if($result_insert_thread_reply)
		{
			$response_array = array("Success"=>"Success","resp"=>"Review submitted succesfully");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Review Submission Failed");							
		}
		
	}
	echo json_encode($response_array);	
}

# =======================================================================================================
# START : Mobile Verification Code [By Prathamesh on 06102016] 
# =======================================================================================================
if((isset($obj->user_verify_mobile)) == '1' && (isset($obj->user_verify_mobile)))
{
	$response_array			= array();
	$cust_mobile_verify		= $obj->cust_mobile_verify;
	$hid_cust_mobile_num	= $obj->hid_cust_mobile_num;
	$hid_cust_email			= $obj->hid_cust_email;
	$hid_cust_mobile_status	= $obj->hid_cust_mobile_status;
	
	if($cust_mobile_verify	!= '' && $hid_cust_email != '' && $hid_cust_mobile_status != '')
	{
		// First Check the otp is active or expire
		$sql_check_otp	= " SELECT * FROM `tbl_customer` ";
		$sql_check_otp	.= " WHERE `cust_email`='".$hid_cust_email."' ";
		$sql_check_otp	.= "	AND `cust_mobile_num`='".$hid_cust_mobile_num."' ";
		$res_check_otp	= mysqli_query($db_con, $sql_check_otp) or die(mysqli_error($db_con));
		$row_check_otp	= mysqli_fetch_array($res_check_otp);
		
		$chk_mobile_status	= $row_check_otp['cust_mobile_status'];
		
		if($chk_mobile_status != 2)
		{
			// Here we check user entered verification code and the real verification code
			if(strcmp($cust_mobile_verify, $hid_cust_mobile_status) === 0)
			{
				if($_SESSION['front_panel']['cust_mobile_status'] == '1')
				{
					$response_array	= array("Success"=>"fail", "resp"=>"You have already verified!!");	
				}
				else
				{
					
					
					// Query for Update the mobile Verification code to "1"
					$sql_update_mobile_verification_code	= " UPDATE `tbl_customer` ";
					$sql_update_mobile_verification_code	.= "	SET `cust_mobile_status`='1', ";
					$sql_update_mobile_verification_code	.= "		`cust_modified`='".$datetime."' ";
					$sql_update_mobile_verification_code	.= " WHERE `cust_email`='".$hid_cust_email."' ";
					$sql_update_mobile_verification_code	.= "	AND `cust_mobile_num`='".$hid_cust_mobile_num."' ";
					$res_update_mobile_verification_code	= mysqli_query($db_con, $sql_update_mobile_verification_code) or die(mysqli_error($db_con));
					
					if($res_update_mobile_verification_code)
					{
						$_SESSION['front_panel']['cust_mobile_status']	= '1';
						$response_array	= array("Success"=>"Success", "resp"=>"Thank you for verifying your mobile number.");		
					}
					else
					{
						$response_array	= array("Success"=>"fail", "resp"=>"Oops, something went wrong, can you try after sometime!!");	
					}
				}
			}
			else
			{
				$response_array	= array("Success"=>"fail", "resp"=>"Sorry, Your verification code not matched");	
			}
		}
		else
		{
			$response_array	= array("Success"=>"fail", "resp"=>"OTP is expired, please click on resend code link");	
		}
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"Sorry, Your verification code not matched");
	}
	echo json_encode($response_array);
}
# =======================================================================================================
# END : Mobile Verification Code [By Prathamesh on 06102016]
# =======================================================================================================

# =============================================================================================================
# START : SEND VERIFICATION CODE FOR MOBILE AND EMAIL VERIFICATION LINK FOR EMAIL [By Prathamesh on 06102016]
# =============================================================================================================
if((isset($obj->sendVerificationCode)) == '1' && (isset($obj->sendVerificationCode)))
{
	$response_array	= array();
	$userEmail		= $obj->userEmail;
	$verifyType		= $obj->verifyType;
	
	if($userEmail != '' && $verifyType != '')
	{
		// Getting both the verification code of that respective Email ID
		$sql_get_both_verifi_code	= " SELECT `cust_fname`, `cust_email`, `cust_mobile_num`, `cust_email_status`, `cust_mobile_status` ";
		$sql_get_both_verifi_code	.= " FROM `tbl_customer` ";
		$sql_get_both_verifi_code	.= " WHERE `cust_email`='".$userEmail."' ";
		$res_get_both_verifi_code	= mysqli_query($db_con, $sql_get_both_verifi_code) or die(mysqli_error($db_con));
		$row_get_both_verifi_code	= mysqli_fetch_array($res_get_both_verifi_code);
		
		//$email_code				= $row_get_both_verifi_code['cust_email_status'];
		//$mobile_code				= $row_get_both_verifi_code['cust_mobile_status'];
		
		$random_string			= "";
		$sql_check 				= " SELECT * FROM `tbl_customer` WHERE ";
		
		$email_code				= '';
		$mobile_code			= '';	
		
		if(strcmp($verifyType, 'email') === 0)		// 1 for email
		{
			$sql_check			.= " `cust_email_status` = '".$random_string."' ";
			$email_code			= randomString($sql_check,5);			
		}
		elseif(strcmp($verifyType, 'mobile') === 0)	// 2 for mobile
		{
			$sql_check			.= " `cust_mobile_status` = '".$random_string."' ";
			$mobile_code		= randomStringMobileVerification($sql_check,6);
		}
		
		$cust_mobile_num			= $row_get_both_verifi_code['cust_mobile_num'];
		$cust_fname					= $row_get_both_verifi_code['cust_fname'];
		
		
		if(strcmp($verifyType, 'email') === 0)
		{
		// Update The Email Verification Code on the click of resend email verification link on Page Profile
		$sql_update_email_veri_code	= " UPDATE `tbl_customer` ";
		$sql_update_email_veri_code	.= " 	SET `cust_email_status`='".$email_code."', ";
		$sql_update_email_veri_code	.= " 		`cust_modified`='".$datetime."' ";
		$sql_update_email_veri_code	.= " WHERE `cust_email`='".$userEmail."' ";
		$res_update_email_veri_code	= mysqli_query($db_con, $sql_update_email_veri_code) or die(mysqli_error($db_con));
			if($res_update_email_veri_code)
			{
				$subject		= 'Planet Educate - Email Update';
				/* create body for update mail message */			
				$message_body = '<table class="" data-module="main Content" height="347" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
					$message_body .= '<tr>';
						$message_body .= '<td>';
							$message_body .= '<table data-bgcolor="BG Color" height="347" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
								$message_body .= '<tr>';
									$message_body .= '<td>';
										$message_body .= '<table data-bgcolor="BG Color 01" height="347" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
											$message_body .= '<tr>';
												$message_body .= '<td>';
													$message_body .= '<table height="347" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
														$message_body .= '<tr>';
															$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
														$message_body .= '</tr>';
														$message_body .= '<tr>';
															$message_body .= '<td height="345" width="520">';
																$message_body .= '<table height="300" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
																	$message_body .= '<tr>';
																		$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">  Email Update. </td>';
																	$message_body .= '</tr>';
																	/*$message_body .= '<tr>';
																		$message_body .= '<td data-color="Name" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="letter-spacing: 0.025em; font-size:15px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left">  Greetings!!! </td>';
																	$message_body .= '</tr>';*/
																	/*$message_body .= '<tr>';
																	$message_body .= '<td class="td-pad10" height="50" align="center"><img style="display:block;width:70px !important;height:150px !important;" src="http://www.planeteducate.com/images/customer/default/user_display.png"></td>';
																	$message_body .= '</tr>';*/			
																	$message_body .= '<tr>';
																		$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="font-weight:600; letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> Dear '.ucwords($cust_fname).', <br>';
																		$message_body .= '</td>';
																	$message_body .= '</tr>';
																	$message_body .= '<tr>';
																		$message_body .= '<td>';
																			$message_body .= '<table data-bgcolor="Color Button 01" class="table-button230-center" style="border-radius: 900px;" height="36" width="230" align="center" bgcolor="#5bbc2e" border="0" cellpadding="0" cellspacing="0">';
																				$message_body .= '<tr>';
																					$message_body .= '<td style="padding: 5px 5px; font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;" valign="middle" align="center"><a align="center" data-color="Text Button 01" data-size="Text Button 01" data-min="6" data-max="20" href="'.$BaseFolder.'/verify/'.$email_code.'" style="font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;">Verify your Email</a></td>';
																				$message_body .= '</tr>';
																			$message_body .= '</table>';
																		$message_body .= '</td>';
																	$message_body .= '</tr>';
																	$message_body .= '<tr>';
																		$message_body .= '<td data-color="Text" data-size="Text" data-min="8" data-max="30" class="td-pad10" style="font-weight:regular; letter-spacing: 0.000em; line-height:22px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> ';
																		$message_body .= 'Click on the above link to update your email.<br>';
																		$message_body .= '<br><b>Your Planet Educate Account Details:</b> ';
																		$message_body .= '<br>Email ID for login:&nbsp;'.$userEmail;
																		$message_body .= '</td>';
																	$message_body .= '</tr>';
																$message_body .= '</table>';
															$message_body .= '</td>';
														$message_body .= '</tr>';
													$message_body .= '</table>';
												$message_body .= '</td>';
											$message_body .= '</tr>';			
											$message_body .= '<tr style="padding-top:10px;">';
												$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> We look forward to make your online shopping a wonderful experience';
												$message_body .= '<br>Please contact us should you have any questions or need further assistance.';
												$message_body .= '</td>';
											$message_body .= '</tr>';
											$message_body .= '<tr>';
												$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
											$message_body .= '</tr>';						
										$message_body .= '</table>';
									$message_body .= '</td>';
								$message_body .= '</tr>';
							$message_body .= '</table>';
						$message_body .= '</td>';
					$message_body .= '</tr>';
				$message_body .= '</table>';
				/* create body for update mail message */	
				$message = mail_template_header()."".$message_body."".mail_template_footer();
			
				if(sendEmail($userEmail,$subject,$message))
				{	
					$res_insert_into_tbl_notification	= '';
					$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_register_verify', $message, $userEmail, $cust_mobile_num);
					
					sendEmail('support@planeteducate.com',$subject,$message);
					$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_register_verify', $message, 'support@planeteducate.com', '02261572611');
					
				}
				$response_array = array("Success"=>"Success","resp"=>"Please Check your email.");
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>"Oops, something went wrong, please try after sometime.");
			}
		}
		elseif(strcmp($verifyType, 'mobile') === 0)
		{
			// Update the Customer table with the updated mobile verification code
			$sql_update_mobile_verification_code	= " UPDATE `tbl_customer` ";
			$sql_update_mobile_verification_code	.= " 	SET `cust_mobile_status`='".$mobile_code."', ";
			$sql_update_mobile_verification_code	.= " 		`cust_modified`='".$datetime."' ";
			$sql_update_mobile_verification_code	.= " WHERE `cust_email`='".$userEmail."' ";
			$sql_update_mobile_verification_code	.= " 	AND `cust_mobile_num`='".$cust_mobile_num."' ";	
			$res_update_mobile_verification_code	= mysqli_query($db_con, $sql_update_mobile_verification_code) or die(mysqli_error($db_con));
			
			// Send SMS of register verify
			$sms_text	= '';
			$sms_text	.= 'Your unique verification code for Planet Educate is '.$mobile_code.'. Thank you';
			
			send_sms_msg($cust_mobile_num, $sms_text);
			$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('SMS_register_verify', $sms_text, $userEmail, $cust_mobile_num);
			
			$response_array = array("Success"=>"Mobile","resp"=>"Verification code sent to ".$cust_mobile_num);
			
			otp_futureDate();
		}
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"Oops, something went wrong, please try after sometime.");
	}
	echo json_encode($response_array);
}
# =============================================================================================================
# END : SEND VERIFICATION CODE FOR MOBILE AND EMAIL VERIFICATION LINK FOR EMAIL [By Prathamesh on 06102016]
# =============================================================================================================

# =============================================================================================================
# START : Update Mobile Status to 2 [2 for mobile status expire] [By Prathamesh on 12-10-2016]
# =============================================================================================================
if((isset($obj->update_mobile_stauts)) == '1' && (isset($obj->update_mobile_stauts)))
{
	$response_array		= array();
	$user_email			= $obj->user_email;
	$cust_mobile_number	= $obj->cust_mobile_number;
	
	if($user_email != '' && $cust_mobile_number != '')
	{
		// chk mobile status is not 1
		$sql_chk_ms	= " SELECT cust_mobile_status FROM tbl_customer WHERE cust_email='".$user_email."' AND cust_mobile_status='1' ";
		$res_chk_ms	= mysqli_query($db_con, $sql_chk_ms) or die(mysqli_error($db_con));
		$num_chk_ms	= mysqli_num_rows($res_chk_ms);
		
		if($num_chk_ms != 1)
		{
			// Update Mobile Status to 2 [2 for mobile status expire]
			$sql_update_mobile_status	= " UPDATE `tbl_customer` ";
			$sql_update_mobile_status	.= " 	SET `cust_mobile_status`='2' ";
			$sql_update_mobile_status	.= " WHERE `cust_email`='".$user_email."' ";
			$sql_update_mobile_status	.= "	AND `cust_mobile_num`='".$cust_mobile_number."' ";
			$res_update_mobile_status	= mysqli_query($db_con,$sql_update_mobile_status) or die(mysqli_error($db_con));
			
			unset($_SESSION['otp_validation']);
			
			$response_array	= array("Success"=>"Success", "resp"=>"Success");	
		}
		else
		{
			$response_array	= array("Success"=>"Success", "resp"=>"Success");
		}
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"Oops, something went wrong, please try after sometime.");
	}
	echo json_encode($response_array);	
}
# =============================================================================================================
# END : Update Mobile Status to 2 [2 for mobile status expire] [By Prathamesh on 12-10-2016]
# =============================================================================================================

# =============================================================================================================
# START : Delete Address [By Prathamesh on 24-10-2016]
# =============================================================================================================
if((isset($obj->deleteAddress)) == '1' && (isset($obj->deleteAddress)))
{
	$addressID		= $obj->addressID;
	$custSession	= $obj->custSession;
	$page_type		= 0;
	$address_data_info	= '';	
	$response_array	= array();
	
	if($addressID != '' && $custSession != '')
	{
		// Delete Address from table
		$sql_delete_address	= " DELETE FROM `tbl_address_master` WHERE `add_id`='".$addressID."' ";
		$res_delete_address	= mysqli_query($db_con, $sql_delete_address) or die(mysqli_error($db_con));
		if($res_delete_address)
		{
			$sql_get_custid_info		= "SELECT * FROM `tbl_customer` where cust_email like '".$custSession."' ";		
			$res_get_custid_info		= mysqli_query($db_con,$sql_get_custid_info) or die(mysqli_query($db_con));
			$num_rows_get_custid_info 	= mysqli_num_rows($res_get_custid_info);
			if($num_rows_get_custid_info == 0)
			{
				$response_array = array("Success"=>"fail","resp"=>"User does not exists.");
			}
			else
			{
				$row_get_custid_info 	= mysqli_fetch_array($res_get_custid_info);			
				$cust_id_info			= $row_get_custid_info['cust_id'];
				if($cust_id_info == 0)
				{
					$response_array = array("Success"=>"fail","resp"=>"cust id empty");		
				}
				else
				{
					$sql_get_address_info	= " SELECT `add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`,add_address_type,";
					$sql_get_address_info	.= " (SELECT `state_name` FROM `state` WHERE `state` = add_state) as add_state_name, ";		
					$sql_get_address_info	.= " (SELECT city_name FROM `city`  WHERE `city_id` = add_city) as add_city_name ";				
					$sql_get_address_info	.= " FROM `tbl_address_master` where add_user_id = '".$cust_id_info."' and add_user_type = 'customer' ";
					$result_get_address_info = mysqli_query($db_con,$sql_get_address_info) or die(mysqli_error($db_con));
					$num_rows_get_address_info = mysqli_num_rows($result_get_address_info);
					if($num_rows_get_address_info == 0)
					{
						$response_array 	= array("Success"=>"Success","address"=>0,"resp"=>"");
					}
					else
					{	
						$address_data_info .= '<aside class="widget-flickr">';
						//$address_data_info .= '<h2>My Address</h2>';
						$address_data_info .= '<hr class="divider-big margin-bottom" />';
						$address_data_info .= '<ul id="flickr-badge" class="clear-fix" style="max-height:500px;overflow:auto;">';
						while($row_get_address_info	= mysqli_fetch_array($result_get_address_info))
						{
							$address_data_info .= '<li>';
							$address_data_info .= '<div class="flickr-container">';
							//$address_data_info .= '<a href="javascript:void(0);">';
							$address_data_info .= '<span>';
							$address_data_info .= '<div>';
							$address_data_info .= '<div align="center" style="padding:1px 0;"><b>'.ucwords($row_get_address_info['add_address_type']).'</b></div>';
							$address_data_info .= '<div align="left" style="padding:1px 0;"><b>Details:</b>'.$row_get_address_info['add_details'].'</div>';
							$address_data_info .= '<div align="left" style="padding:1px 0;"><b>Pincode:</b>'.$row_get_address_info['add_pincode'].'</div>';							
							$address_data_info .= '<div align="left" style="padding:1px 0;"><b>City:</b>'.$row_get_address_info['add_city_name'].'</div>';
							$address_data_info .= '<div align="left" style="padding:1px 0;"><b>State:</b>'.$row_get_address_info['add_state_name'].'</div>';
							if($page_type == 0)
							{
								$address_data_info .= '<button style="padding:5px 5px;" class="cws-button bt-color-3 border-radius alt my-address-box" id="'.$row_get_address_info['add_id'].'" onclick="addressSelect(this.id);"><i class="fa fa-square-o"></i>&nbsp;Deliver to this address</button>';							
							}
								$address_data_info .= '<div class="columns-col columns-col-6" align="right">';
									$address_data_info .= '<button style="padding:5px 5px;" title="Edit Address" id="edit_'.$row_get_address_info['add_id'].'" onclick="editAddress('.$row_get_address_info['add_id'].', \''.$custSession.'\');">';	
										$address_data_info .= '<i class="fa fa-pencil-square-o" style="color:#F27C66;font-size:30px;margin-top: -15px;" aria-hidden="true">';
										$address_data_info .= '</i>';
									$address_data_info .= '</button>';
								$address_data_info .= '</div>';
								$address_data_info .= '<div class="columns-col columns-col-6" align="left">';
									$address_data_info .= '<button style="padding:5px 5px;" title="Delete Address" id="close_'.$row_get_address_info['add_id'].'" onclick="removeAddress('.$row_get_address_info['add_id'].', \''.$custSession.'\');">';	
										$address_data_info .= '<i class="fa fa-times-circle-o" style="color:#F27C66;font-size:30px;margin-top: -15px;" aria-hidden="true">';
										$address_data_info .= '</i>';
									$address_data_info .= '</button>';
								$address_data_info .= '</div>';					
							$address_data_info .= '<div class="clear-fix"></div>';
							$address_data_info .= '</div>';		
							$address_data_info .= '</span>';
							//$address_data_info .= '</a>';
							$address_data_info .= '</div>';
							$address_data_info .= '</li>';				
						}
						$address_data_info .= '</ul>';
						$address_data_info .= '</aside>';
						$response_array 	= array("Success"=>"Success","resp"=>utf8_encode($address_data_info));
					}		
				}			
			}
		}
		else
		{
			$response_array	= array("Success"=>"fail", "resp"=>"fail");	
		}
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"fail");
	}
	echo json_encode($response_array);
}
# =============================================================================================================
# END : Delete Address [By Prathamesh on 24-10-2016]
# =============================================================================================================

# =============================================================================================================
# START : Edit Address [By Prathamesh on 25-10-2016]
# =============================================================================================================
if((isset($obj->editAddress)) == '1' && (isset($obj->editAddress)))
{
	$addrsID		= $obj->addrsID;
	$custSession	= $obj->custSession;
	$response_array	= array();
	
	if($addrsID != '' && $custSession != '')
	{
		// Query For Getting all details releted to address
		$sql_get_addrs_details	= " SELECT tc.cust_id, tc.cust_fname, tc.cust_lname, tc.cust_email, tc.cust_mobile_num, ";
		$sql_get_addrs_details	.= " tam.add_id, tam.add_details, tam.add_state, tam.add_city, tam.add_pincode, tam.add_user_id, ";
		$sql_get_addrs_details	.= " tam.add_user_type, tam.add_address_type, tam.add_status ";
		$sql_get_addrs_details	.= " FROM tbl_customer AS tc INNER JOIN tbl_address_master AS tam ";
		$sql_get_addrs_details	.= " 	ON tc.cust_id=tam.add_user_id ";
		$sql_get_addrs_details	.= " WHERE tam.add_id='".$addrsID."' ";
		$sql_get_addrs_details	.= " 	AND tc.cust_email='".$custSession."' ";
		$res_get_addrs_details	= mysqli_query($db_con, $sql_get_addrs_details) or die(mysqli_error($db_con));
		$row_get_addrs_details	= mysqli_fetch_array($res_get_addrs_details);
		
		$data_edit_addrs	= '';
		
		$data_edit_addrs	.= '<input type="hidden" id="hid_edit_addrs" name="hid_edit_addrs" value="'.$row_get_addrs_details['add_id'].'">';
		$data_edit_addrs	.= '<div class="form-group control-group">';
			$data_edit_addrs	.= '<div class="controls">';
				$data_edit_addrs	.= '<input type="text" class="form-control tooltipped" value="'.$row_get_addrs_details['add_address_type'].'" name="edit_address_type_edit_address" id="edit_address_type_edit_address" data-rule-maxlength="25" minlength="3" maxlength="25" placeholder="Name Your Address" data-rule-required="true" data-toggle="tooltip" data-container="body"  title="Name Your Address(e.g Home,School,Office,Dad\'s Office etc.)">';
				$data_edit_addrs	.= '<span class="input-icon"><i class="fa fa-comment"></i></span>';
			$data_edit_addrs	.= '</div>';
		$data_edit_addrs	.= '</div>';	// Name of the Address
		$data_edit_addrs	.= '<div class="form-group control-group">';
			$data_edit_addrs	.= '<div class="controls">';
				$data_edit_addrs	.= '<textarea name="cust_address_edit_address" id="cust_address_edit_address" placeholder="Address Details" class="form-control tooltipped" data-rule-required="true" data-rule-maxlength="100" minlength="10" maxlength="100" data-toggle="tooltip" data-container="body" title="Maximum 100 characters only.">'.$row_get_addrs_details['add_details'].'</textarea>';
				$data_edit_addrs	.= '<span class="input-icon"><i class="fa fa-location-arrow"></i></span>';
			$data_edit_addrs	.= '</div>';	
		$data_edit_addrs	.= '</div>';	// Detail Address
		$data_edit_addrs	.= '<div class="form-group control-group">';
			$data_edit_addrs	.= '<div class="controls">';
				$data_edit_addrs	.= '<select id="cust_country_edit_address" data-rule-required="true" class="select2-me input-xlarge" name="cust_country_edit_address" onChange="updateState(this.id,\'cust_state_edit_address\');" style="width:100%">';
					$data_edit_addrs	.= '<option value="IN" selected >India</option>';	// Country ID of India is 100 so value for this DDL is "100"			
				$data_edit_addrs	.= '</select>';
			$data_edit_addrs	.= '</div>';
		$data_edit_addrs	.= '</div>';	// Country
		$data_edit_addrs	.= '<div class="form-group control-group">';
			$data_edit_addrs	.= '<div class="controls">';
				$data_edit_addrs	.= '<select id="cust_state_edit_address" data-rule-required="true" class="select2-me input-xlarge dropdown_width" name="cust_state_edit_address" onChange="updateCity(this.id,\'cust_city_edit_address\');" placeholder="Select State" style="width:100%" >';
					$data_edit_addrs	.= '<option value="">Select State</option>';
					// Query For Getting all States from the table
					$sql_get_state_details	= " SELECT * FROM `state` WHERE `country_id`='IN' ";
					$res_get_state_details	= mysqli_query($db_con, $sql_get_state_details) or die(mysqli_error());
					while($row_get_state_details = mysqli_fetch_array($res_get_state_details))
					{
						$data_edit_addrs	.= '<option value="'.$row_get_state_details['state'].'"';
						if($row_get_state_details['state'] == $row_get_addrs_details['add_state'])
						{
							$data_edit_addrs	.= 'selected';
						}
						$data_edit_addrs	.= '>';
						$data_edit_addrs	.= $row_get_state_details['state_name'];
						$data_edit_addrs	.= '</option>';
					}
				$data_edit_addrs	.= '</select>';
			$data_edit_addrs	.= '</div>';
		$data_edit_addrs	.= '</div>';	// State
		$data_edit_addrs	.= '<div class="form-group control-group">';
			$data_edit_addrs	.= '<div class="controls">';
				$data_edit_addrs	.= '<select id="cust_city_edit_address"  data-rule-required="true" name="cust_city_edit_address" class="dropdown_width" placeholder="Select City" style="width:100%">';
					$data_edit_addrs	.= '<option value="" selected>Select City</option>';
					// Query For getting all cities from the table
					$sql_get_city_details	= " SELECT * FROM `city` WHERE  state_id ='".$row_get_addrs_details['add_state']."'  ";
					$res_get_city_details	= mysqli_query($db_con, $sql_get_city_details) or die(mysqli_error($db_con));
					while($row_get_city_details = mysqli_fetch_array($res_get_city_details))
					{
						$data_edit_addrs	.= '<option value="'.$row_get_city_details['city_id'].'"';
						if($row_get_city_details['city_id'] == $row_get_addrs_details['add_city'])
						{
							$data_edit_addrs	.= 'selected';
						}
						$data_edit_addrs	.= '>';
						$data_edit_addrs	.= $row_get_city_details['city_name'];
						$data_edit_addrs	.= '</option>';		
					}
				$data_edit_addrs	.= '</select>';
			$data_edit_addrs	.= '</div>';
		$data_edit_addrs	.= '</div>';	// City
		$data_edit_addrs	.= '<div class="form-group control-group">';
			$data_edit_addrs	.= '<div class="controls">';
				$data_edit_addrs	.= '<input type="text" 	class="form-control tooltipped" value="'.$row_get_addrs_details['add_pincode'].'" name="cust_pincode_edit_address" id="cust_pincode_edit_address" placeholder="Pincode" data-rule-required="true" data-rule-number="true" maxlength="6" minlength="6" data-toggle="tooltip" data-container="body"  title=" only number with 6 digits ">';
				$data_edit_addrs	.= '<span class="input-icon">';
					$data_edit_addrs	.= '<i class="fa fa-map-marker"></i>';
				$data_edit_addrs	.= '</span>';
			$data_edit_addrs	.= '</div>';
		$data_edit_addrs	.= '</div>'; 	// pincode
		$data_edit_addrs	.= '<button id="reg_submit_edit_address" name="reg_submit_edit_address" type="submit" class="button-fullwidth cws-button bt-color-3 border-radius" >Update Address</button>';
		
		
		$response_array	= array("Success"=>"Success", "resp"=>utf8_encode($data_edit_addrs));
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"Updation Error");
	}
	echo json_encode($response_array);	
}

if((isset($obj->edit_new_address)) == '1' && (isset($obj->edit_new_address)))
{
	$response_array		= array();
	$hid_edit_addrs		= $obj->hid_edit_addrs;
	$cust_session 		= mysqli_real_escape_string($db_con,$obj->cust_session);
	$add_address_type 	= mysqli_real_escape_string($db_con,$obj->add_address_type);
	$cust_address 	 	= mysqli_real_escape_string($db_con,$obj->cust_address);
	$cust_country 		= mysqli_real_escape_string($db_con,$obj->cust_country);
	$cust_state 		= mysqli_real_escape_string($db_con,$obj->cust_state);
	$cust_city 	 		= mysqli_real_escape_string($db_con,$obj->cust_city);
	$cust_pincode  		= mysqli_real_escape_string($db_con,$obj->cust_pincode);
	if($cust_session == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Session expired.");
	}
	else
	{
		$sql_get_custid 		= "SELECT * FROM `tbl_customer` where cust_email like '".$cust_session."' ";		
		$result_get_custid 		= mysqli_query($db_con,$sql_get_custid) or die(mysqli_query($db_con));
		$num_rows_get_custid 	= mysqli_num_rows($result_get_custid);
		if($num_rows_get_custid == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"User does not exists.");
		}
		else
		{
			if($add_address_type == "" || $cust_address == "" || $cust_country == "" || $cust_state == "" || $cust_city == "" || $cust_pincode == "")
			{
				$response_array = array("Success"=>"fail","resp"=>"Empty Data.");
			}
			else
			{
				$row_get_custid 	= mysqli_fetch_array($result_get_custid);			
				$cust_id = $row_get_custid['cust_id'];
				if($cust_id == 0)
				{
					$response_array = array("Success"=>"fail","resp"=>"cust id empty");		
				}
				else
				{
					$table_name				= "tbl_address_master";
					$new_id					= "add_id";
					$add_id 				= getNewId($new_id,$table_name);
					$add_lat_long			= "010";
					$sql_update_new_add 	= " UPDATE `tbl_address_master` ";
					$sql_update_new_add 	.= " 	SET `add_details`='".$cust_address."', ";
					$sql_update_new_add 	.= " 		`add_state`='".$cust_state."', ";
					$sql_update_new_add 	.= " 		`add_city`='".$cust_city."', ";
					$sql_update_new_add 	.= " 		`add_pincode`='".$cust_pincode."', ";
					$sql_update_new_add 	.= " 		`add_address_type`='".$add_address_type."', ";
					$sql_update_new_add 	.= " 		`add_modified`='".$datetime."' ";
					$sql_update_new_add 	.= " WHERE `add_id`='".$hid_edit_addrs."' ";
					$result_update_new_add 	= mysqli_query($db_con,$sql_update_new_add) or die(mysqli_query($db_con));
					if($result_update_new_add)
					{
						$response_array = array("Success"=>"Success","resp"=>"Address Updated succesfully");	
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Address Updatedtion Fail");							
					}
				}				
			}
		}
	}
	echo json_encode($response_array);
}
# =============================================================================================================
# END : Edit Address [By Prathamesh on 25-10-2016]
# =============================================================================================================


# =============================================================================================================
# START : Elastic Search [By Prathamesh on 08-10-2016]
# =============================================================================================================
if((isset($obj->rus_search)) == '1' && (isset($obj->rus_search)))
{
	$st = $obj->searchterm;
	$products_data = '';

	$query9 = $es->search([
		'body' => [
			'size' => 20,
			'query' => [
				'multi_match' => [
					'query' => $st,
					'fields' => ['prod_name', 'prod_description', 'prod_content', 'prod_org_name']
								]
						]
				]    
	]);
	
	if($query9['hits']['total']==0)
	{
		$products_data .= '<p>Sorry, we could not match any of the keywords you entered.</p>';
		$response_array = array("Success"=>"fail","resp"=>utf8_encode($products_data));
	}
	else
	{
		$results = $query9['hits']['hits'];
		//$ooo = 1;
		$products_data .= '<ul style="margin: 30px; -webkit-column-count: 4; -moz-column-count: 4; column-count: 4; -webkit-column-gap: 150px; -moz-column-gap: 150px; column-gap: 150px;">';       
		foreach($results as $r)
		{
			$products_data .= '<li id="prod_id'.$r['_source']['prod_id'].'" style="-webkit-column-break-inside: avoid; page-break-inside: avoid;  break-inside: avoid-column; display:table; ">';
				// START : class="picture"
				$products_data .= '<div class="picture" onclick="location.href=\''.$BaseFolder.'/page-product.php?prod_id='.$r['_source']['prod_id'].'\'" style="cursor:pointer; height:200px; width:190px; position:relative; left:10px; ">';
					$products_data .= '<div class="ribbon ribbon-blue">';
						$products_data .= '<div class="banner">';
							$products_data .= '<div class="text">New</div>';
						$products_data .= '</div>';
					$products_data .= '</div>';
					$products_data .= '<div>';
						if(trim($r['_source']['prod_img_file_name']) != "")
						{
							$imagepath 		= '/images/planet/org'.$r['_source']['prod_orgid'].'/prod_id_'.$r['_source']['prod_id'].'/medium/'.$r['_source']['prod_img_file_name'];
							if(file_exists("../".$imagepath))
							{
								$products_data .= '<img style="max-width:250%; height:200px; position:relative; left:10px;" data-at2x="'.$BaseFolder."/".$imagepath.'" alt="" src="'.$BaseFolder."/".$imagepath.'" >';
							}
							else
							{
								$products_data .= '<script type="text/javascript">$("#prod_id'.$r['_source']['prod_id'].'").slideDown();</script>';
								$products_data .= '<img style="max-width:250%; height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';							
							}						
						}
						else
						{
							$products_data .= '<script type="text/javascript">$("#prod_id'.$r['_source']['prod_id'].'").slideUp();</script>';						
							$products_data .= '<!--<img style="max-width:250%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >--><p>haha</p>';						
						}
					$products_data .= '</div>';//END OF IMAGE
					$products_data .= '<span class="hover-effect"></span>';
					$products_data .= '<div class="link-cont">';
						$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$r['_source']['prod_id'].'">View</a>';
					$products_data .= '</div>';
					$products_data .= '<div class="product-name" style="height:20px; max-width:27%; position:relative; left:15px;">';
					$products_data .= '</div>';//TITLE
				$products_data .= '</div>';
				// END : class="picture"
				
				//$products_data .= '<a style="position:relative; right:10px;" href="'.$BaseFolder.'/page-product.php?prod_id='.$r['_source']['prod_id'].'">'.$ooo."/".short($r['_source']['prod_title'],50).'</a>';
				
				
				$products_data .= '<div style="position:relative; right:10px;">';							
					$products_data .= '<span class="price">';
						$products_data .= '<span class="amount">'.current(explode('.', $r['_source']['prod_recommended_price'])).'<sup><i class="fa fa-rupee"></i></sup></span>';
					$products_data .= '</span>';
				$products_data .= '</div>';//RUPEES ICON AND PRICE
				
				$products_data .= '<div  style="position:relative; right:10px; ">';							
					$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button border-radius alt smaller" onClick="addToCart(\''.$r['_source']['prod_id'].'\')"> <i class="fa fa-shopping-cart"></i> </a>';
				$products_data .= '</div>';//CART ICON
			$products_data .= '</li>';
			//$ooo++;
		}
		$products_data .= '</ul>';
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($products_data));
	}
	echo json_encode($response_array);

}
# =============================================================================================================
# END : Elastic Search [By Prathamesh on 08-10-2016]
# =============================================================================================================

# =============================================================================================================
# Start : Validate Package Address [By Satish on 21-11-2016]
# =============================================================================================================
if((isset($_POST['validate_package'])) == '1' && (isset($_POST['validate_package'])))
{
	$response_array		= array();
	$school_id		    = $_POST['school_id'];
	$parent_name		= $_POST['parent_name'];
    $stud_name		= $_POST['stud_name'];
	$stud_mobile_no	    = $_POST['stud_mobile_no'];
	$cust_email     = $_SESSION['front_panel']['cust_email'];
	$grade          = $_POST['grade'];
	$sql_validate_package 		= "SELECT * FROM tbl_student_package  where stud_package_parent_name like '".$parent_name."' ";
	$sql_validate_package 	   .= " AND  stud_package_school ='".$school_id."' AND  stud_package_grade ='".$grade."' AND stud_package_student_id ='".$_SESSION['front_panel']['cust_id']."' AND  stud_pkg_stud_name like '".$stud_name."'";			
	$result_validate_package 		= mysqli_query($db_con,$sql_validate_package) or die(mysqli_query($db_con));
	$num_rows_get_custid 	= mysqli_num_rows($result_validate_package);
	if($num_rows_get_custid ==0)
	{   
	    $sql_add_info		= "INSERT INTO `tbl_student_package`(`stud_package_parent_name`, `stud_package_school`, `stud_package_grade`, `stud_package_student_id`, `stud_pkg_stud_name`,stud_package_created) VALUES ('".$parent_name."','".$school_id."','".$grade."',";
		$sql_add_info		.= " '".$_SESSION['front_panel']['cust_id']."','".$stud_name."','".$datetime."')";
		$res_add_info 	= mysqli_query($db_con,$sql_add_info) or die(mysqli_error($db_con));
	    if($res_add_info)
		{
		  $response_array = array("Success"=>"Success","resp"=>"Student Package Successfully Activated");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Package Activation Failed");
		}
	}
	else 
	{
		$response_array = array("Success"=>"fail","resp"=>"Already Activated for this Information");	
	}
	echo json_encode($response_array);exit();
}
# =============================================================================================================
# end : Validate Package Address [By satish on 21-11-2016]
# =============================================================================================================

# =============================================================================================================
# Start : Validate Package Address [By Satish on 21-11-2016]
# =============================================================================================================
if((isset($_POST['validate_package'])) == '3' && (isset($_POST['validate_package'])))
{
	$response_array		= array();
	$school_id		    = $_POST['school_id'];
	$parent_name		= $_POST['parent_name'];
        $stud_name		= $_POST['stud_name'];
	$stud_mobile_no	    = $_POST['stud_mobile_no'];
	$cust_email     = $_SESSION['front_panel']['cust_email'];
	$grade          = $_POST['grade'];
	$sql_validate_package 		= "SELECT * FROM tbl_student_package  where stud_package_parent_name like '".$parent_name."' ";
	$sql_validate_package 	   .= " AND  stud_package_mobile_no ='".$stud_mobile_no."' AND  stud_package_school ='".$school_id."' AND  stud_package_grade ='".$grade."' AND stud_package_student_id ='0' ";			
	$result_validate_package 		= mysqli_query($db_con,$sql_validate_package) or die(mysqli_query($db_con));
	$num_rows_get_custid 	= mysqli_num_rows($result_validate_package);
	if($num_rows_get_custid !=0)
	{   
	   $row = mysqli_fetch_array($result_validate_package);
		
		$random_string			= "";
	    $sql_check 				= " SELECT * FROM `tbl_customer` WHERE 1=1 and ";
		$cust_email_query		= $sql_check." `cust_email_status` = '".$random_string."' ";
	    $cust_email_status		= randomString($cust_email_query,5);
	    $cust_mobile_query		= $sql_check." `cust_mobile_status` = '".$random_string."' ";
	    //$cust_mobile_status		= randomString($cust_mobile_query,5);
	    $cust_mobile_status		= randomStringMobileVerification($cust_mobile_query,6);
	   // Send SMS of register verify
		$sms_text	= '';
		$sms_text	.= 'Your unique verification code for Student Package is '.$cust_mobile_status.'. Thank you';
		
				
		send_sms_msg($stud_mobile_no, $sms_text);
		$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('SMS_package_verify', $sms_text,$cust_email,$stud_mobile_no);
		
		$sql_update_status="UPDATE tbl_student_package SET stud_pkg_otp = '".$cust_mobile_status."', stud_package_student_id =".$_SESSION['front_panel']['cust_id'].",stud_pkg_stud_name='".$stud_name."' WHERE  stud_package_id ='".$row['stud_package_id']."'   ";
		$result_update_status 		= mysqli_query($db_con,$sql_update_status) or die(mysqli_query($db_con));	
	
		otp_futureDate();
		$response_array = array("Success"=>"Success","resp"=>"Your student package OTP has been sent on ".$stud_mobile_no,"pkg_id"=>$row['stud_package_id']);	
	}
	else 
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Not Found");	
	}
	echo json_encode($response_array);
}
# =============================================================================================================
# end : Validate Package Address [By satish on 21-11-2016]
# =============================================================================================================


# =======================================================================================================
# START : student Verification Code [By satish ] 
# =======================================================================================================
if((isset($obj->stud_verify_mobile)) == '1' && (isset($obj->stud_verify_mobile)))
{
	$response_array			= array();
	$cust_mobile_verify		= $obj->cust_mobile_verify;
	$hid_stud_mobile_num	= $obj->hid_cust_mobile_num;
	$hid_cust_email			= $obj->hid_cust_email;
	$hid_cust_mobile_status	= $obj->hid_cust_mobile_status;
	$package_id	= $obj->package_id;
	
	$sql_get_data ="SELECT * FROM tbl_student_package WHERE stud_package_id =".$package_id." ";
	$res_get_data 		= mysqli_query($db_con,$sql_get_data) or die(mysqli_query($db_con));
	$row = mysqli_fetch_array($res_get_data);
	if(strtotime($_SESSION['otp_validation']) < strtotime(date('Y/m/d H:i:s')))
	{
		$response_array	= array("Success"=>"fail", "resp"=>"Sorry, Your OTP expired");
		echo json_encode($response_array);
		exit();
	}
	if($cust_mobile_verify	== $row['stud_pkg_otp'])
	{
	$sql_update_status="UPDATE tbl_student_package SET student_package_mobile_verify = 1 WHERE stud_package_id =".$package_id." ";
	$result_update_status 		= mysqli_query($db_con,$sql_update_status) or die(mysqli_query($db_con));	
	$response_array	= array("Success"=>"Success", "resp"=>"Your Student Package OTP Successfully Verified","pkg_id"=>$row['stud_package_id']);	
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"Sorry, Your verification code not matched");
	}
	echo json_encode($response_array);
}
# =======================================================================================================
# END : Mobile Verification Code [By satish on ]
# =======================================================================================================

// ====================================================================================================================
// START : Pagination Dn By Prathamesh on 28-11-2016
// ====================================================================================================================
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
	
	$msg .= "<br>";
	$msg .= '<div style="clear:both;"></div>';
	$msg .= "<div id='data_pagination' class='page-pagination clear-fix' style='margin-top:20px;'>";
		$msg	.= '<ul>';		
		// FOR ENABLING THE PREVIOUS BUTTON
		if ($previous_btn && $cur_page > 1)
		{
			$pre = $cur_page - 1;
			$msg .= "<a p='".$pre."' class='active'>";
				$msg .= "<i class='fa fa-angle-double-left '></i>";
			$msg .= "</a>";
		}
		else
		{
			$msg .= "<a class='inactive' disabled>";
				$msg .= "<i class='fa fa-angle-double-left'></i>";
			$msg .= "</a>";
		}
		
		for ($i = $start_loop; $i <= $end_loop; $i++) 
		{
			$msg .= "<a p='".$i."' ";
			if ($cur_page == $i)
			{
				$msg .= "class='active'";
			}
			$msg .= ">".$i."</a>";
		}
		
		// TO ENABLE THE NEXT BUTTON
		if ($next_btn && $cur_page < $no_of_paginations)
		{
			$nex = $cur_page + 1;
			$msg .= "<a p='".$nex."' class='active'>";
				$msg .= "<i class='fa fa-angle-double-right'></i>";
			$msg .= "</a>";	
		}
		else
		{
			$msg .= "<a class='inactive'>";
				$msg .= "<i class='fa fa-angle-double-right'></i>";
			$msg .= "</a>";			
		}
		$start_offset1 = $cur_page * $per_page + 1 - $per_page;
		if($end_loop == $cur_page)
		{
			$start_offset2 = $record_count;
		}
		else
		{
			$start_offset2 = $cur_page * $per_page;
		}
		$msg	.= '</ul>';
	$msg .= "</div>";
	//$total_string = "<div class='total' a='$no_of_paginations' style='color:#333333;font-family: Open Sans,sans-serif;font-size: 13px !important;'>Showing <b>".$start_offset1."</b> to <b>".$start_offset2."</b> of <b>".$record_count."</b> entries</div>";
	//$msg1 = $msg . $total_string ;  // Content for pagination	
	$msg1 = $msg;
	if(!$record_count=='0')
	{
		return $msg1;
	}
	else
	{
		return 0;	
	}
}

function dataCount($query,$per_page,$start,$cur_page)
{
	global $db_con;
	$start_offset1  	= 1;	// Start Point
	$start_offset2  	= 1;	// End of the Limit
	$result_pag_num 	= mysqli_query($db_con,$query) or die(mysqli_error($db_con));;
	$record_count		= mysqli_num_rows($result_pag_num);	// Total Count of the Record
	
	$no_of_paginations 	= ceil($record_count / $per_page);	// Getting the total number of pages
	
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
	
	$start_offset1 = $cur_page * $per_page + 1 - $per_page;
	if($end_loop == $cur_page)
	{
		$start_offset2 = $record_count;
	}
	else
	{
		$start_offset2 = $cur_page * $per_page;
	}
	
	$total_string = "<div class='total' a='".$no_of_paginations."' style='color:#333333;font-family: Open Sans,sans-serif;font-size: 13px !important;'><h4>(Showing <b>".$start_offset1."</b> to <b>".$start_offset2."</b> of <b>".$record_count."</b> entries)</h4></div>";
	
	if(!$total_string=='')
	{
		return $total_string;
	}
	else
	{
		return 0;	
	}
}
// ====================================================================================================================
// END : Pagination Dn By Prathamesh on 28-11-2016
// ====================================================================================================================

# =======================================================================================================
# start : Load Marketing Products [By satish on 28112016]
# =======================================================================================================
if((isset($obj->loadMProducts)) == "1" && isset($obj->loadMProducts))// load Products levelwise
{	
	$page					= trim(mysqli_real_escape_string($db_con,$obj->page));
	$order_by				= trim(mysqli_real_escape_string($db_con,$obj->order_by));
	
	$sort_by				= array(0=>" prod_id ",1=>" prod_created_by desc ",2=>" prod_recommended_price asc ",3=>" prod_recommended_price desc ");
    $cmp_info_id            =$obj->cmp_info_id;
	
	$sql_get_camp_wise_products = " SELECT * FROM `tbl_campaign_info` WHERE `cmp_info_id` ='".$cmp_info_id."' ";
    $result_get_camp_wise_products = mysqli_query($db_con,$sql_get_camp_wise_products) or die(mysqli_error($db_con));
    $row_section_data = mysqli_fetch_array($result_get_camp_wise_products);
	$exist_product =$row_section_data['cmp_info_products'];
	
	
	
	/* Exclude out of Stock*/	
	if(isset($obj->out_of_stock))
	{
		$out_of_stock		= trim(mysqli_real_escape_string($db_con,$obj->out_of_stock));		
	}	
	/* Exclude out of Stock*/	
	
	/* Price Filter checked*/
	if(isset($obj->product_price_range))
	{
		$product_price_range= $obj->product_price_range;	
	}
	/* Price Filter checked*/
	
	/* category checked*/	
	if(isset($obj->categoryData))
	{
		$categoryData		= $obj->categoryData;
	}				
	/* category checked*/			
	
	/* level checked*/									
	if(isset($obj->levelData))
	{
		$levelData			= $obj->levelData;
	}								
	/* level checked*/										
	
	/* Filters checked*/										
	if(isset($obj->filters_data))	
	{
		$filters_data		= $obj->filters_data; // don't use trim(mysqli_real_escape_string($db_con,));		
	}
	/* Filters checked*/									

	/* Brands checked*/											
	if(isset($obj->brands))		
	{
		$brands				= $obj->brands; // don't use trim(mysqli_real_escape_string($db_con,));	
	}
	
	
	$sql_products	= " SELECT DISTINCT tpm.`prod_id`, tpm.`prod_model_number`, tpm.`prod_name`, tpm.`prod_slug`, tpm.`prod_title`, ";
	$sql_products	.= " tpm.`prod_payment_mode`, tpm.`prod_cod_status`, tpm.`prod_description`, tpm.`prod_orgid`, tpm.`prod_brandid`, ";
	$sql_products	.= " tpm.`prod_catid`, tpm.`prod_subcatid`, tpm.`prod_returnable`, tpm.`prod_content`, tpm.`prod_quantity`, ";
	$sql_products	.= " tpm.`prod_min_quantity`, tpm.`prod_max_quantity`, tpm.`prod_list_price`, tpm.`prod_recommended_price`, ";
	$sql_products	.= " tpm.`prod_org_price`, tpm.prod_status, tpi.prod_img_file_name, tom.org_name, ";
	$sql_products	.= " (SELECT avg(trm.review_star_rating)*20 FROM tbl_review_master AS trm WHERE trm.review_status = 1 and trm.review_prod_id = tpm.prod_id) as prod_avg_review ";
	$sql_products	.= " FROM tbl_products_master AS tpm INNER JOIN tbl_products_images AS tpi ";
	$sql_products	.= "	ON tpm.prod_id=tpi.prod_img_prodid INNER JOIN tbl_oraganisation_master AS tom ";
	$sql_products	.= "	ON tpm.prod_orgid=tom.org_id ";
	
	if((isset($obj->levelData))  && (!empty($levelData)))
	{
		$sql_products	.= " INNER JOIN `tbl_product_levels` AS tpl ";
		$sql_products	.= " 	ON tpm.prod_id = tpl.prodlevel_prodid ";
	}
	
	if((isset($obj->filters_data)) && (!empty($filters_data)))
	{
		$sql_products 	.= " INNER JOIN `tbl_product_filters` AS tpf ";
		$sql_products 	.= " 	ON tpm.prod_id = tpf.prodfilt_prodid ";
	}
		
	// START : Added By Prathamesh For comming From Category
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$sql_products	.= " INNER JOIN tbl_product_cats AS tpc ";
			$sql_products	.= " 	ON tpm.prod_id=tpc.prodcat_prodid ";	
		}
	}
	// END : Added By Prathamesh For comming From Category
			
	$sql_products 			.= " WHERE tpi.prod_img_type = 'main' and tpm.prod_status = '1' and tpm.prod_id IN (".$exist_product.") ";	
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	if((isset($obj->filters_data)) && (!empty($filters_data)) && sizeof($filters_data) != 0)
	{
		$cnt 							= 0;			
		$sql_products 					.= " and prod_id = prodfilt_prodid ";
		$sql_products 					.= " and ( ";
		$prodfilt_filtid_parent	 		= "";
		$prodfilt_filtid_child 	 		= "";
		$prodfilt_filtid_sub_child 		= "";
		foreach($filters_data as $filt_id)
		{
			$filt_id_data			 	= explode(":",$filt_id);
			$prodfilt_filtid_parent	 	= $filt_id_data[0];
			$prodfilt_filtid_child 	 	= $filt_id_data[1];
			$prodfilt_filtid_sub_child 	= $filt_id_data[2];
			$sql_products 				.= " ( ";
			if($prodfilt_filtid_parent != 0)
			{
				$sql_products 				.= " prodfilt_filtid_parent = '".$prodfilt_filtid_parent."' ";
			}
			if($prodfilt_filtid_parent != 0 && $prodfilt_filtid_child != 0)
			{
				$sql_products 				.= " and ";
			}
			if($prodfilt_filtid_child != 0)
			{
				$sql_products 				.= " prodfilt_filtid_child = '".$prodfilt_filtid_child."' ";
			}
			if($prodfilt_filtid_parent != 0 && $prodfilt_filtid_sub_child != 0 || $prodfilt_filtid_child != 0 && $prodfilt_filtid_sub_child != 0)
			{
				$sql_products 				.= " and ";				
			}
			if($prodfilt_filtid_sub_child != 0)
			{
				$sql_products 				.= " prodfilt_filtid_sub_child = '".$prodfilt_filtid_sub_child."' ";
			}
			$sql_products 				.= " ) ";					
			++$cnt;					
			if($cnt != sizeof($filters_data))
			{
				$sql_products 			.= " or ";
				//$sql_products 			.= " AND ";
			}							
		}			
		$sql_products 					.= " and prodfilt_status = 1 ";				
		$sql_products 					.= " )";
	}
	
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$sql_products	.= " AND tpc.prodcat_catid IN (SELECT cat_id ";
			$sql_products	.= " 						   FROM tbl_category ";
			$sql_products	.= " 						   WHERE (cat_type='".$categoryData."' OR cat_id='".$categoryData."') ";
			$sql_products	.= " 								AND cat_name!='none' ";
			$sql_products	.= " 								AND cat_status='1') ";
		}
	}
	else
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$i = 1;
			$sql_products 		.= " and ( ";					
			foreach($categoryData as $cat_id)
			{
				$category = explode(":",$cat_id);
				$sql_products 		.= " ( ";			
				$sql_products 		.= " tpm.prod_catid = '".$category[0]."' ";
				if(trim($category[1]) != 0)
				{
					$sql_products 		.= " and tpm.prod_subcatid = '".$category[1]."' ";				
				}
				$sql_products 		.= " ) ";			
				if(sizeof($categoryData) != $i)
				{
					$sql_products 	.= " or ";
				}
				$i++;
			}
			$sql_products 		.= " ) ";			
		}	
	}
	
	if((isset($obj->levelData)) && (!empty($levelData)))
	{
		$i = 1;
		$sql_products 		.= " and (";				
		foreach($levelData as $level_id)
		{
			$level = explode(":",$level_id);
			$sql_products 		.= " ( ";				
			$sql_products 		.= " tpl.prodlevel_levelid_parent = '".$level[0]."' ";
			if(trim($level[1]) != 0)
			{
				$sql_products 		.= " and tpl.prodlevel_levelid_child = '".$level[1]."' ";				
			}
			$sql_products 		.= " )";				
			if(sizeof($levelData) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 			.= " ) and tpl.prodlevel_status = 1 ";				
	}
	
	if($out_of_stock != "" && $out_of_stock != "0")
	{
		$sql_products 					.= " and (tpm.prod_quantity != '' or tpm.prod_quantity != 0) ";
	}
	
	if((isset($obj->brands)) && (!empty($brands)))
	{
		$i = 1;
		$sql_products 		.= " and ( ";		
		foreach($brands as $brand_id)
		{
			$sql_products 		.= " tpm.prod_brandid = ".$brand_id." ";
			if(sizeof($brands) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 		.= " ) ";
	}
	
	if($out_of_stock == "true")
	{
		$sql_products 					.= " and  tpm.prod_recommended_price != '' and tpm.prod_list_price != '' ";
	}
	
	if(isset($obj->product_price_range))
	{
		$arr_size 						= sizeof($product_price_range);
		if($arr_size != 0)			
		{
			$sql_products 				.= " and (";
			$cnt 						= 0;
			foreach($product_price_range as $price_range)
			{ 
				$price_array			= explode("-",$price_range);				
				$min_price				= $price_array[0];
				$max_price				= $price_array[1];
				
				$sql_products 			.= "(prod_recommended_price BETWEEN ".$min_price." AND ".$max_price.") ";
				++$cnt;					
				if($cnt != $arr_size)
				{
					$sql_products 		.= " or ";												
				}
			}	
			$sql_products 				.= ")";
		}
	}
	/* for earching products */
	if(isset($obj->search_term) && $obj->search_term != "")
	{
		$search_term 		= mysqli_real_escape_string($db_con,$obj->search_term);
		$search_result		= searchAlgorithm($search_term);	
		if(sizeof($search_result) != 0)
		{
			$sql_products 	.= " and prod_id IN (";			
			$i = 0;
			foreach($search_result as $keyword)
			{
				$sql_products 	.= "'".$keyword."'";
				$i++;
				if(sizeof($search_result) != $i)
				{
					$sql_products 	.= ",";
				}
			}					
			$sql_products 	.= ")";			
		}
		else
		{
			$sql_products 	.= "and prod_id = 0 ";			
		}				
	}
	/* for earching products */
	
	$brand_data_p	= '';
	if((empty($brands)))
	{
		$brand_data_p 	.= getUpdatedBrand($sql_products);
	}
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	// =============================================================================================================
	// START : New Pagination [By Prathamesh 28-11-2016]
	// =============================================================================================================
	$per_page		= 20;
	$start_offset   = 0;
	$data_pagination	= '';
	$data_count			= '';
	if($page != "" && $per_page != "")
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset 	+= $page * $per_page;
		$start 			= $page * $per_page;
		
		$data_pagination		= dataPagination($sql_products,$per_page,$start,$cur_page);
		$data_count				= dataCount($sql_products,$per_page,$start,$cur_page);
		/*$response_array = array("Success"=>"fail","resp"=>$data_pagination);
		echo json_encode($response_array);
		exit();*/	
	}
	// =============================================================================================================	
	// END : New Pagination [By Prathamesh 28-11-2016]
	// =============================================================================================================	
	
	if($order_by != "")
	{
		foreach($sort_by as $id => $condition)
		{
			if($id == $order_by)
			{
				$sql_products	.= " order by ".$condition;					
			}
		}
	}
	
	$sql_products		.= " LIMIT $start, $per_page ";
	
	$result_get_product	= mysqli_query($db_con,$sql_products) or die(mysqli_error($db_con));
	$num_rows_products	= mysqli_num_rows($result_get_product);
	if($num_rows_products == 0)
	{
		$data_count	= '<div id="no_more_prod">';
			$data_count	.= '<span>No More Products</span>';
        $data_count	.= '</div>';
		
		$response_array = array("Success"=>"fail","resp"=>"","query"=>$sql_products,"brand_data_list"=>$brand_data_p,"data_count"=>$data_count);
	}
	else
	{	
		$products_data		= '';
		while($row_get_products = mysqli_fetch_array($result_get_product))
		{			
			$products_data .= '<li class="product" id="prod_id'.$row_get_products['prod_id'].'">';
			$products_data .= '<div class="picture" onclick="location.href=\''.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'\'" style="cursor:pointer">';
			$products_data .= '<div class="ribbon ribbon-blue">';
			$products_data .= '<div class="banner">';
			if($row_get_products['prod_quantity']==0)
			{
				$products_data .= '<div class="text" style="font-size:10px;">Out Of Stock</div>';
			}
		    else
			{
				$products_data .= '<div class="text">New</div>';	
			}		
			$products_data .= '</div>';
			$products_data .= '</div>';
			$products_data .= '<div  align="center" style="width:200px;height:200px;">';
			if(trim($row_get_products['prod_img_file_name']) != "")
			{
				$imagepath 		= '/images/planet/org'.$row_get_products['prod_orgid'].'/prod_id_'.$row_get_products['prod_id'].'/medium/'.$row_get_products['prod_img_file_name'];
				if(file_exists("../".$imagepath))
				{
					$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder."/".$imagepath.'" alt="" src="'.$BaseFolder."/".$imagepath.'" >';
				}
				else
				{
					$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideDown();</script>';
					$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';							
				}						
			}
			else
			{
				$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideUp();</script>';						
				$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';						
			}
			$products_data .= '</div>';
			$products_data .= '<span class="hover-effect"></span>';
			$products_data .= '<div class="link-cont">';
			$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'">View</a>';
/*					$products_data .= '<a href="'.$imagepath.'" class="cws-right fancy cws-slide-left "><i class="fa fa-search"></i></a>';
					$products_data .= '<a href="page-product.php?prod_id='.$row_get_products['prod_id'].'" class=" cws-left cws-slide-right"><i class="fa fa-link"></i></a>';
*/			$products_data .= '</div>';
			$products_data .= '</div>';
			$products_data .= '<div class="product-name" style="height:60px">';
			$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'">'.short($row_get_products['prod_title'],50).'</a>';
			$products_data .= '</div>';
			$products_data .= '<div class="star-rating" title="Rated 4.00 out of 5">';					
			$variable = substr($row_get_products['prod_avg_review'], 0, strpos($row_get_products['prod_avg_review'], ".")); 
			if(trim($variable) != "")
			{
				$products_data .= '<span style="width:'.$variable.'%"><strong class="rating">4.00</strong> out of 5</span>';
			}						
			else
			{
				$products_data .= '<span style="width:50%"><strong class="rating">4.00</strong> out of 5</span>';						
			}
			$products_data .= '</div>';											
			$products_data .= '<span class="price">';
			$products_data .= '<span class="amount">'.current(explode('.', $row_get_products['prod_recommended_price'])).'<sup><i class="fa fa-rupee"></i></sup></span>';
			$products_data .= '</span>';
			$products_data .= '<!--<div class="product-description">';
			$products_data .= '<div class="short-description">';
			$products_data .= '<p>'.short($row_get_products['prod_description'],100).'</p>';
			$products_data .= '</div>-->';
			$products_data .= '<!-- <div class="full-description">';
			$products_data .= '<p>In blandit ultricies euismod.Lobortis erat, sed ullamcorper erat interdum et. Cras volutpat felis id enim vehicula, eu facilisis dui lacinia. Vivamus sollicitudin tristique tellus.</p>';
			$products_data .= '</div> -->';
			$products_data .= '</div>';							
			if($row_get_products['prod_quantity']!=0)
			{					
				$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button border-radius alt smaller" onClick="addToCart(\''.$row_get_products['prod_id'].'\')"> <i class="fa fa-shopping-cart"></i> </a>';
			}
			else
			{
				$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button" > Out Of Stock </a>';
			}
			$products_data .= '</li>';	
		}
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($products_data),"query"=>$sql_products, "brand_data_list"=>$brand_data_p, "data_pagination"=>$data_pagination, "data_count"=>$data_count);//,"data"=>$sql_products);//.$sql_products);
	}							
	echo json_encode($response_array);	
}

if((isset($obj->get_cart_detail)) == '1' && (isset($obj->get_cart_detail)))
{   
    
	if((isset($_SESSION['front_panel']['id'])))
	{
		$cust_id=$_SESSION['front_panel']['cust_id'];
		$response_array = getCartDetail($cust_id);
	}
	else
	{
		echo json_encode(0);	
	}
	echo json_encode($response_array);
}
# =======================================================================================================
# start : GET cart deTAIL [By satish on 28112016]
# =======================================================================================================
function getCartDetail($cart_custid)
{
	global $db_con, $BaseFolder;
	global $min_order_value;
	global $shipping_charge;
	global $response_array;
	$cart_data="";
	$cart_count=0;
	$response_array	= array();
	
	$sql_sum_cart_prod_discount	= " SELECT SUM(cart_discount) AS cart_discount FROM tbl_cart where cart_status = 0 and cart_custid = '".$cart_custid."' ";
	$res_sum_cart_prod_discount	= mysqli_query($db_con, $sql_sum_cart_prod_discount) or die(mysqli_error($db_con));
	$row_sum_cart_prod_discount	= mysqli_fetch_array($res_sum_cart_prod_discount);
	
	$sql_get_cart_items 	= " SELECT * FROM tbl_cart where cart_status = 0 and cart_custid = '".$cart_custid."' ";
	$result_get_cart_items 	= mysqli_query($db_con,$sql_get_cart_items) or die(mysqli_error($db_con));
	$num_rows_get_cart_items= mysqli_num_rows($result_get_cart_items);
	//return $sql_get_cart_items;
	if($num_rows_get_cart_items == 0)
	{
		$response_str = '<div align="center" style="font-size:24px;color:F00;margin:30px 0;" >Cart is empty</div>';
		$response_array = array("Success"=>"fail","resp"=>$response_str,"count"=>"(0)","checkout"=>"0");
	}
	else
	{	
	    $cart_total=0;
		$discount_total=0;
		while($row=mysqli_fetch_array($result_get_cart_items))
		{
		 $cart_total +=$row['cart_price'];	
		 $discount_total +=$row['cart_discount'];
		}
		
		$cart_data		.= '<table>';
		$cart_data		.= '<tbody>';
		$cart_data		.= '<tr class="cart-subtotal">';
		$cart_data		.= '<th>Cart Subtotal</th>';
		$cart_data		.= '<td><span class="amount"><i class="fa fa-rupee"></i>'.$cart_total.'</span></td>';
		$cart_data		.= '</tr>';
		$cart_data		.= '<tr class="shipping">';
		$cart_data		.= '<th>Shipping</th>';
		$cart_data		.= '<td>';
		#=============================================================================================================
		# START : Different shipping chargers for different total amount [DN by Prathamesh on 01-11-2016]
		#=============================================================================================================
		
		// Query For getting all records from tbl_free_shipping table
		$sql_get_fs_data	= " SELECT * FROM `tbl_free_shipping` WHERE fs_status='1' ";
		$res_get_fs_data	= mysqli_query($db_con, $sql_get_fs_data) or die(mysqli_error($db_con));
		
		// If the customer is logged-in
		if((isset($_SESSION['front_panel']['name'])))
		{
			while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
			{
				if($row_get_fs_data['fs_type_value'] == 0)
				{
					if($cart_total >= $row_get_fs_data['fs_start_price'])
					{
						//$cart_data		.= $row_get_fs_data['fs_type_value'];
						$cart_data		.= 'Free Shipping';
						$shipping_charge = $row_get_fs_data['fs_type_value'];	
					}
				}
				elseif($row_get_fs_data['fs_type_value'] != 0)
				{
					if(($row_get_fs_data['fs_start_price'] <= $cart_total) && ($cart_total <= $row_get_fs_data['fs_end_price']))
					{
						// checking the occurrrence
						/*if($row_get_fs_data['fs_occurrence'] == 'first time')
						{
							// check order details of the customer
							$sql_chk_order_details	= " SELECT * FROM `tbl_order` WHERE `ord_custid`='".$_SESSION['front_panel']['cust_id']."' ";
							$res_chk_order_details	= mysqli_query($db_con, $sql_chk_order_details) or die(mysqli_error($db_con));
							$num_chk_order_details	= mysqli_num_rows($res_chk_order_details);
							
							if($num_chk_order_details != 0)
							{
								$cart_data		.= $row_get_fs_data['fs_type_value'];
								$shipping_charge = $row_get_fs_data['fs_type_value'];	
							}
							else
							{
								$cart_data		.= 'Free Shipping';
								$shipping_charge = 0;
							}
						}
						elseif($row_get_fs_data['fs_occurrence'] == 'other')
						{*/
							$cart_data			.= $row_get_fs_data['fs_type_value'];
							$shipping_charge 	= $row_get_fs_data['fs_type_value'];	
						/*}*/
					}
				}
			}
		}
		else	// If the customer is not logged-in
		{
			while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
			{
				if($row_get_fs_data['fs_type_value'] == 0)
				{
					if($cart_total >= $row_get_fs_data['fs_start_price'])
					{
						//$cart_data		.= $row_get_fs_data['fs_type_value'];
						$cart_data		.= 'Free Shipping';
						$shipping_charge = $row_get_fs_data['fs_type_value'];	
					}
				}
				elseif($row_get_fs_data['fs_type_value'] != 0)
				{
					if(($row_get_fs_data['fs_start_price'] <= $cart_total) && ($cart_total <= $row_get_fs_data['fs_end_price']))
					{
						// checking the occurrrence
						if($row_get_fs_data['fs_occurrence'] == 'first time')
						{
							$cart_data			.= $row_get_fs_data['fs_type_value'];
							$shipping_charge 	= $row_get_fs_data['fs_type_value'];
						}
						elseif($row_get_fs_data['fs_occurrence'] == 'other')
						{
							$cart_data			.= $row_get_fs_data['fs_type_value'];
							$shipping_charge 	= $row_get_fs_data['fs_type_value'];	
						}
					}
				}
			}
		}
		#=============================================================================================================				
		# END : Different shipping chargers for different total amount [DN by Prathamesh on 01-11-2016]
		#=============================================================================================================		
		
	
		$cart_data		.= '</td>';
		$cart_data		.= '</tr>';
		#===========================================================================================
		# START : Commented By Prathamesh [on 03-11-2016]
		#===========================================================================================
		if($discount_total == 0)
		{
			/*$cart_data		.= '<tr class="shipping">';
			$cart_data		.= '<th>Discount</th>';
			$cart_data		.= '<td>';
			$cart_data		.= 'No Discount';
			$cart_data		.= '</td>';
			$cart_data		.= '</tr>';	*/		
		}
		else
		{
			$cart_data		.= '<tr class="shipping">';
			$cart_data		.= '<th>Discount</th>';
			$cart_data		.= '<td>';
			$cart_data		.= $discount_total;
			$cart_data		.= '</td>';
			$cart_data		.= '</tr>';			
		}
		#===========================================================================================
		# END : Commented By Prathamesh [on 03-11-2016]
		#===========================================================================================
		$final_total	= $cart_total + $shipping_charge - $discount_total;		
		$cart_data		.= '<tr class="order-total">';
		$cart_data		.= '<th>Order Total</th>';
		$cart_data		.= '<td><span class="amount"><i class="fa fa-rupee"></i>'.$final_total.'</span></td>';
		$cart_data		.= '</tr>';
		$cart_data		.= '</tbody>';
		$cart_data		.= '</table>';
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($cart_data),"count"=>"(".$cart_count.")","checkout"=>"1");
	}
	return $response_array;
}
# =======================================================================================================
# end : GET cart deTAIL [By satish on 28112016]
# =======================================================================================================

# =======================================================================================================
# START : Resend OTP [By satish on 19122016]
# =======================================================================================================
if((isset($obj->package_resend_otp)) == "1" && isset($obj->package_resend_otp))
{
    $package_id		= $obj->package_id;
	$mobile_no	    = $obj->mobile_no;
	$cust_email     = $_SESSION['front_panel']['cust_email'];
		$random_string			= "";
	    $sql_check 				= " SELECT * FROM `tbl_customer` WHERE 1=1 and ";
		$cust_email_query		= $sql_check." `cust_email_status` = '".$random_string."' ";
	    $cust_email_status		= randomString($cust_email_query,5);
	    $cust_mobile_query		= $sql_check." `cust_mobile_status` = '".$random_string."' ";
		
		
	    //$cust_mobile_status		= randomString($cust_mobile_query,5);
	    $cust_mobile_status		= randomStringMobileVerification($cust_mobile_query,6);
	   // Send SMS of register verify
		$sms_text	= '';
		$sms_text	.= 'Your unique verification code for Student Package is '.$cust_mobile_status.'. Thank you';
		
				
		send_sms_msg($mobile_no, $sms_text);
		$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('SMS_package_verify', $sms_text, $cust_email, $mobile_no);
		
		$sql_update_status="UPDATE tbl_student_package SET stud_pkg_otp = '".$cust_mobile_status."' WHERE stud_package_id ='".$package_id."'";
		$result_update_status 		= mysqli_query($db_con,$sql_update_status) or die(mysqli_query($db_con));	
	
		otp_futureDate();
		$response_array = array("Success"=>"Success","resp"=>"Your student package OTP has been sent on ".$mobile_no,"pkg_id"=>$package_id);
		echo json_encode($response_array);
	
}
# =======================================================================================================
# END: Resend OTP [By satish on 19122016]
# =======================================================================================================

# =======================================================================================================
# START : Remove Avatar [By satish on 21122016]
# =======================================================================================================
if((isset($obj->remove_avatar)) == "1" && isset($obj->remove_avatar))
{
    $cust_id		= $obj->cust_id;
	$response_array = array();
	
		
		$sql_update_avatar ="UPDATE tbl_customer SET cust_profile_img = '' WHERE cust_id ='".$cust_id."'";
		$res_update_avatar 		= mysqli_query($db_con,$sql_update_avatar) or die(mysqli_query($db_con));	
	
		if($res_update_avatar)
		{
			$response_array = array("Success"=>"Success","resp"=>"Avatar removed successfully");
		}
		else
		{
			$response_array = array("Success"=>"Success","resp"=>"Avatar deletion failed");
		}
		
		echo json_encode($response_array);
	
}
# =======================================================================================================
# END: Remove Avatar [By satish on 21122016]
# =======================================================================================================
# =============================================================================================================
# Start : Service Contact Form [By Satish on 13-01-2017]
# =============================================================================================================
if((isset($_POST['service_req'])) == '1' && (isset($_POST['service_req'])))
{
	$response_array		= array();
	$comp_name		    = $_POST['comp_name'];
	$cust_name		= $_POST['cust_name'];
	$comp_email	    = $_POST['comp_email'];
	$contact_no          = $_POST['contact_no'];
	$about_comp		= $_POST['about_comp'];
	$requi_type	    = $_POST['requi_type'];
	$requirement	    = $_POST['requirement'];
	
   		$sql_add_service		= "INSERT INTO `tbl_service_contact`(`comp_name`, `sc_custname`, `comp_email`, `contact_no`, `about_company`,";
		$sql_add_service		.= " `requirement_type`, `requirement`,added_date) VALUES ('".$comp_name."','".$cust_name."','".$comp_email."',";
		$sql_add_service		.= " '".$contact_no."','".$about_comp."','".$requi_type."','".$requirement."','".$datetime."')";
		$res_add_service 	= mysqli_query($db_con,$sql_add_service) or die(mysqli_error($db_con));
		if($res_add_service)
		{
			$response_array 	= array("Success"=>"Success","resp"=>"Thank you for your request");
		}
		else
		{
			$response_array 	= array("Success"=>"fail","resp"=>"Your request not submitted. Please try letter....");
		}
	$subject = "PlanetEducate Requirement";
	$message_body ='<span style="margin-left:12%">Dear Support</span>';	
	$message_body .='';
	$message_body .= '<table class="" data-module="main Content" height="347" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
	$message_body .= '<tr>';
	$message_body .= '<th>Company Name :</th>';
	$message_body .= '<td>'.$comp_name.'</td>';
	$message_body .= '<tr>';
	$message_body .= '<tr>';
	$message_body .= '<th>Name :</th>';
	$message_body .= '<td>'.$cust_name.'</td>';
	$message_body .= '<tr>';
	$message_body .= '<tr>';
	$message_body .= '<th>Email :</th>';
	$message_body .= '<td>'.$comp_email.'</td>';
	$message_body .= '<tr>';
	$message_body .= '<tr>';
	$message_body .= '<th>Mobile No :</th>';
	$message_body .= '<td>'.$contact_no.'</td>';
	$message_body .= '<tr>';
	
	$message_body .= '<tr>';
	$message_body .= '<th>About Company :</th>';
	$message_body .= '<td>'.$about_comp.'</td>';
	$message_body .= '<tr>';
	
	$message_body .= '<tr>';
	$message_body .= '<th>Req. Type :</th>';
	$message_body .= '<td>'.$requi_type.'</td>';
	$message_body .= '<tr>';
	
	$message_body .= '<tr>';
	$message_body .= '<th>Requirement :</th>';
	$message_body .= '<td>'.$requirement.'</td>';
	$message_body .= '<tr>';
	$message_body .= '<tr>';
	$message_body .= '<th>Date :</th>';
	$message_body .= '<td>'.$datetime.'</td>';
	$message_body .= '<tr>';
	$message_body .='</table>';
	$message=mail_template_header()."".$message_body."".mail_template_footer();
	sendEmail('support@planeteducate.com',$subject,$message);

	insertEmailSmsEntryIntoNotification('Requirements', $message,'support@planeteducate.com',' ');
	echo json_encode($response_array);
}
# =============================================================================================================
# end :Service Contact Form [By Satish on 13-01-2017]
# =============================================================================================================

// =========================================================================================================================
// START : Set session for address
// =========================================================================================================================
if((isset($obj->losd_setSession)) == '1' && (isset($obj->losd_setSession)))
{
	$response_array	= array();
	$address_id		= $obj->address_id;
	$city_id		= $obj->city_id;
	
	if($address_id != '')
	{
		$data	= '';
		
		// Query For getting the all city ids for COD
		$sql_get_city_id	= " SELECT DISTINCT(`cod_city_city_id`) FROM `tbl_cod_cities` WHERE `cod_city_status`='1' ";
		$res_get_city_id	= mysqli_query($db_con, $sql_get_city_id) or die(mysqli_error($db_con));
		$num_get_city_id	= mysqli_num_rows($res_get_city_id);
		
		if($num_get_city_id != 0)
		{
			while($row_get_city_id = mysqli_fetch_array($res_get_city_id))
			{
				$arr_city[]	= $row_get_city_id['cod_city_city_id'];
			}
			
			//$arr_city= array(805,815,1418);
			//if($city_id == '805' || $city_id == '815' || $city_id == '1418')
			if(in_array($city_id,$arr_city)) // change by punit 16-01-2017
			{
				$data	.= '<div style="float:left;padding:20px;">	<!-- // display:none; dn by prathamesh according to Arshi and Cynthia On 09-12-2016 ?> -->';
					$data	.= '<div class="radio">';
						$data	.= '<input id="cash-on-delivery" type="radio"  name="payment_mode" value="Cash on Delivery" onChange="TogglpayWay(\'hide\');">';
						$data	.= '<label for="cash-on-delivery"></label>';
					$data	.= '</div>';
					$data	.= '&nbsp;Cash On Delivery';
				$data	.= '</div>';
			}
			
			$response_array	= array("Success"=>"Success", "resp"=>utf8_encode($data));
		}
		else
		{
			$response_array	= array("Success"=>"Success", "resp"=>utf8_encode($data));
		}
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"Oops, something went wrong, please select address again");
	}
	echo json_encode($response_array);
}
// =========================================================================================================================
// END : Set session for address
// =========================================================================================================================


////////////////--------------------------- strat added by satish 18012017 ---------------------------------------------//////////////////////////
if((isset($obj->remove_coupon)) == "1" && isset($obj->remove_coupon))// Update Coupon
{
	$response_array				= array();
	$cust_session				= trim(mysqli_real_escape_string($db_con,$obj->cust_session));
	$user_coupon_code			= trim(mysqli_real_escape_string($db_con,$obj->user_coupon_code));
	$cust_id				    =$_SESSION['front_panel']['cust_id'];
	
	
	$sql_update_cart		   ="UPDATE tbl_cart SET cart_discount='',cart_coupon_code='' WHERE cart_custid ='".$cust_id."' AND cart_status !=1";
	$res_update_cart	= mysqli_query($db_con, $sql_update_cart) or die(mysqli_error($db_con));
	
	if($res_update_cart)
	{
		$response_array = array("Success"=>"Success","resp"=>"Coupon Code Removed Successfully");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data");					
	}
	echo json_encode($response_array);
}
////////////////--------------------------- end added by satish  18012017---------------------------------------------//////////////////////////

///////////////////  added by satish 13022017//
if((isset($obj->search_data)) == "1" && isset($obj->search_data))// load Products levelwise
{	
	$page					= trim(mysqli_real_escape_string($db_con,$obj->page));
	$order_by				= trim(mysqli_real_escape_string($db_con,$obj->order_by));
	$search_term				= trim(mysqli_real_escape_string($db_con,$obj->search_term));
	$search_drop				= trim(mysqli_real_escape_string($db_con,$obj->search_drop));
	
	$sort_by				= array(0=>" prod_id ",1=>" prod_created_by desc ",2=>" prod_recommended_price asc ",3=>" prod_recommended_price desc ");
   
	
	
	
	
	
	/* Exclude out of Stock*/	
	if(isset($obj->out_of_stock))
	{
		$out_of_stock		= trim(mysqli_real_escape_string($db_con,$obj->out_of_stock));		
	}	
	/* Exclude out of Stock*/	
	
	/* Price Filter checked*/
	if(isset($obj->product_price_range))
	{
		$product_price_range= $obj->product_price_range;	
	}
	/* Price Filter checked*/
	
	/* category checked*/	
	if(isset($obj->categoryData))
	{
		$categoryData		= $obj->categoryData;
	}				
	/* category checked*/			
	
	/* level checked*/									
	if(isset($obj->levelData))
	{
		$levelData			= $obj->levelData;
	}								
	/* level checked*/										
	
	/* Filters checked*/										
	if(isset($obj->filters_data))	
	{
		$filters_data		= $obj->filters_data; // don't use trim(mysqli_real_escape_string($db_con,));		
	}
	/* Filters checked*/									

	/* Brands checked*/											
	if(isset($obj->brands))		
	{
		$brands				= $obj->brands; // don't use trim(mysqli_real_escape_string($db_con,));	
	}
	
	
	$sql_products	= " SELECT DISTINCT tpm.`prod_id`, tpm.`prod_model_number`, tpm.`prod_name`, tpm.`prod_slug`, tpm.`prod_title`, ";
	$sql_products	.= " tpm.`prod_payment_mode`, tpm.`prod_cod_status`, tpm.`prod_description`, tpm.`prod_orgid`, tpm.`prod_brandid`, ";
	$sql_products	.= " tpm.`prod_catid`, tpm.`prod_subcatid`, tpm.`prod_returnable`, tpm.`prod_content`, tpm.`prod_quantity`, ";
	$sql_products	.= " tpm.`prod_min_quantity`, tpm.`prod_max_quantity`, tpm.`prod_list_price`, tpm.`prod_recommended_price`, ";
	$sql_products	.= " tpm.`prod_org_price`, tpm.prod_status, tpi.prod_img_file_name, tom.org_name, ";
	$sql_products	.= " (SELECT avg(trm.review_star_rating)*20 FROM tbl_review_master AS trm WHERE trm.review_status = 1 and trm.review_prod_id = tpm.prod_id) as prod_avg_review ";
	$sql_products	.= " FROM tbl_products_master AS tpm INNER JOIN tbl_products_images AS tpi ";
	$sql_products	.= "	ON tpm.prod_id=tpi.prod_img_prodid";
	$sql_products 	.= " JOIN tbl_brands_master AS tbm ON tbm.brand_id = tpm.prod_brandid ";
	$sql_products 	.= " JOIN tbl_category AS tc ON tc.cat_id = tpm.prod_catid ";
	
	$sql_products   .= " INNER JOIN tbl_oraganisation_master AS tom ";
	$sql_products	.= "	ON tpm.prod_orgid=tom.org_id ";
	
	if((isset($obj->levelData))  && (!empty($levelData)))
	{
		$sql_products	.= " INNER JOIN `tbl_product_levels` AS tpl ";
		$sql_products	.= " 	ON tpm.prod_id = tpl.prodlevel_prodid ";
	}
	
	if((isset($obj->filters_data)) && (!empty($filters_data)))
	{
		$sql_products 	.= " INNER JOIN `tbl_product_filters` AS tpf ";
		$sql_products 	.= " 	ON tpm.prod_id = tpf.prodfilt_prodid ";
	}
		
	// START : Added By Prathamesh For comming From Category
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$sql_products	.= " INNER JOIN tbl_product_cats AS tpc ";
			$sql_products	.= " 	ON tpm.prod_id=tpc.prodcat_prodid ";	
		}
	}
	// END : Added By Prathamesh For comming From Category
			
	$sql_products 			.= " WHERE tpi.prod_img_type = 'main' and tpm.prod_status = '1' ";	
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	if((isset($obj->filters_data)) && (!empty($filters_data)) && sizeof($filters_data) != 0)
	{
		$cnt 							= 0;			
		$sql_products 					.= " and prod_id = prodfilt_prodid ";
		$sql_products 					.= " and ( ";
		$prodfilt_filtid_parent	 		= "";
		$prodfilt_filtid_child 	 		= "";
		$prodfilt_filtid_sub_child 		= "";
		foreach($filters_data as $filt_id)
		{
			$filt_id_data			 	= explode(":",$filt_id);
			$prodfilt_filtid_parent	 	= $filt_id_data[0];
			$prodfilt_filtid_child 	 	= $filt_id_data[1];
			$prodfilt_filtid_sub_child 	= $filt_id_data[2];
			$sql_products 				.= " ( ";
			if($prodfilt_filtid_parent != 0)
			{
				$sql_products 				.= " prodfilt_filtid_parent = '".$prodfilt_filtid_parent."' ";
			}
			if($prodfilt_filtid_parent != 0 && $prodfilt_filtid_child != 0)
			{
				$sql_products 				.= " and ";
			}
			if($prodfilt_filtid_child != 0)
			{
				$sql_products 				.= " prodfilt_filtid_child = '".$prodfilt_filtid_child."' ";
			}
			if($prodfilt_filtid_parent != 0 && $prodfilt_filtid_sub_child != 0 || $prodfilt_filtid_child != 0 && $prodfilt_filtid_sub_child != 0)
			{
				$sql_products 				.= " and ";				
			}
			if($prodfilt_filtid_sub_child != 0)
			{
				$sql_products 				.= " prodfilt_filtid_sub_child = '".$prodfilt_filtid_sub_child."' ";
			}
			$sql_products 				.= " ) ";					
			++$cnt;					
			if($cnt != sizeof($filters_data))
			{
				$sql_products 			.= " or ";
				//$sql_products 			.= " AND ";
			}							
		}			
		$sql_products 					.= " and prodfilt_status = 1 ";				
		$sql_products 					.= " )";
	}
	
	if((isset($obj->throughCat)) == '1' && (isset($obj->throughCat)))
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$sql_products	.= " AND tpc.prodcat_catid IN (SELECT cat_id ";
			$sql_products	.= " 						   FROM tbl_category ";
			$sql_products	.= " 						   WHERE (cat_type='".$categoryData."' OR cat_id='".$categoryData."') ";
			$sql_products	.= " 								AND cat_name!='none' ";
			$sql_products	.= " 								AND cat_status='1') ";
		}
	}
	else
	{
		if((isset($obj->categoryData)) && (!empty($categoryData)))
		{
			$i = 1;
			$sql_products 		.= " and ( ";					
			foreach($categoryData as $cat_id)
			{
				$category = explode(":",$cat_id);
				$sql_products 		.= " ( ";			
				$sql_products 		.= " tpm.prod_catid = '".$category[0]."' ";
				if(trim($category[1]) != 0)
				{
					$sql_products 		.= " and tpm.prod_subcatid = '".$category[1]."' ";				
				}
				$sql_products 		.= " ) ";			
				if(sizeof($categoryData) != $i)
				{
					$sql_products 	.= " or ";
				}
				$i++;
			}
			$sql_products 		.= " ) ";			
		}	
	}
	
	if((isset($obj->levelData)) && (!empty($levelData)))
	{
		$i = 1;
		$sql_products 		.= " and (";				
		foreach($levelData as $level_id)
		{
			$level = explode(":",$level_id);
			$sql_products 		.= " ( ";				
			$sql_products 		.= " tpl.prodlevel_levelid_parent = '".$level[0]."' ";
			if(trim($level[1]) != 0)
			{
				$sql_products 		.= " and tpl.prodlevel_levelid_child = '".$level[1]."' ";				
			}
			$sql_products 		.= " )";				
			if(sizeof($levelData) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 			.= " ) and tpl.prodlevel_status = 1 ";				
	}
	
	if($out_of_stock != "" && $out_of_stock != "0")
	{
		$sql_products 					.= " and (tpm.prod_quantity != '' or tpm.prod_quantity != 0) ";
	}
	
	if((isset($obj->brands)) && (!empty($brands)))
	{
		$i = 1;
		$sql_products 		.= " and ( ";		
		foreach($brands as $brand_id)
		{
			$sql_products 		.= " tpm.prod_brandid = ".$brand_id." ";
			if(sizeof($brands) != $i)
			{
				$sql_products 	.= " or ";
			}
			$i++;			
		}
		$sql_products 		.= " ) ";
	}
	
	if($out_of_stock == "true")
	{
		$sql_products 					.= " and  tpm.prod_recommended_price != '' and tpm.prod_list_price != '' ";
	}
	
	if(isset($obj->product_price_range))
	{
		$arr_size 						= sizeof($product_price_range);
		if($arr_size != 0)			
		{
			$sql_products 				.= " and (";
			$cnt 						= 0;
			foreach($product_price_range as $price_range)
			{ 
				$price_array			= explode("-",$price_range);				
				$min_price				= $price_array[0];
				$max_price				= $price_array[1];
				
				$sql_products 			.= "(prod_recommended_price BETWEEN ".$min_price." AND ".$max_price.") ";
				++$cnt;					
				if($cnt != $arr_size)
				{
					$sql_products 		.= " or ";												
				}
			}	
			$sql_products 				.= ")";
		}
	}
	if($search_drop !=0)
			{
				$sql_products 		.= " AND tpm.prod_catid = '".$search_drop."' ";
		    }
			
			$sql_products 		.= "  AND  (tpm.prod_name LIKE '%".$search_term."%' OR tpm.prod_model_number LIKE '%".$search_term."%' OR tom.org_name LIKE '%".$search_term."%' OR  ";
			$sql_products 		.= " tbm.brand_name LIKE '%".$search_term."%' OR tc.cat_name LIKE '%".$search_term."%')  ";
	/* for earching products */
	/*if(isset($obj->search_term) && $obj->search_term != "")
	{
		$search_term 		= mysqli_real_escape_string($db_con,$obj->search_term);
		$search_result		= searchAlgorithm($search_term);	
		if(sizeof($search_result) != 0)
		{
			$sql_products 	.= " and prod_id IN (";			
			$i = 0;
			foreach($search_result as $keyword)
			{
				$sql_products 	.= "'".$keyword."'";
				$i++;
				if(sizeof($search_result) != $i)
				{
					$sql_products 	.= ",";
				}
			}					
			$sql_products 	.= ")";			
		}
		else
		{
			$sql_products 	.= "and prod_id = 0 ";			
		}				
	}*/
	/* for earching products */
	
	$brand_data_p	= '';
	//if((empty($brands)))
	//{
		//$brand_data_p 	.= getUpdatedBrand($sql_products);
	//}
	
	/*$response_array = array("Success"=>"fail","resp"=>$sql_products);
	echo json_encode($response_array);
	exit();*/
	
	// =============================================================================================================
	// START : New Pagination [By Prathamesh 28-11-2016]
	// =============================================================================================================
	$per_page		= 20;
	$start_offset   = 0;
	$data_pagination	= '';
	$data_count			= '';
	if($page != "" && $per_page != "")
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset 	+= $page * $per_page;
		$start 			= $page * $per_page;
		
		$data_pagination		= dataPagination($sql_products,$per_page,$start,$cur_page);
		$data_count				= dataCount($sql_products,$per_page,$start,$cur_page);
		//echo json_encode(array("Success"=>"Success","resp"=>$data_count));exit();
		/*$response_array = array("Success"=>"fail","resp"=>$data_pagination);
		echo json_encode($response_array);
		exit();*/	
	}
	// =============================================================================================================	
	// END : New Pagination [By Prathamesh 28-11-2016]
	// =============================================================================================================	
	
	if($order_by != "")
	{
		foreach($sort_by as $id => $condition)
		{
			if($id == $order_by)
			{
				$sql_products	.= " order by ".$condition;					
			}
		}
	}
	
	$sql_products		.= " LIMIT $start, $per_page ";
	
	$result_get_product	= mysqli_query($db_con,$sql_products) or die(mysqli_error($db_con));
	$num_rows_products	= mysqli_num_rows($result_get_product);
	if($num_rows_products == 0)
	{
		$data_count	= '<div id="no_more_prod">';
			$data_count	.= '<span>No More Products</span>';
        $data_count	.= '</div>';
		
		$response_array = array("Success"=>"fail","resp"=>"","query"=>$sql_products,"brand_data_list"=>$brand_data_p,"data_count"=>$data_count);
	}
	else
	{	
		$products_data		= '';
		while($row_get_products = mysqli_fetch_array($result_get_product))
		{			
			$products_data .= '<li class="product" id="prod_id'.$row_get_products['prod_id'].'">';
			$products_data .= '<div class="picture" onclick="location.href=\''.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'\'" style="cursor:pointer">';
			$products_data .= '<div class="ribbon ribbon-blue">';
			$products_data .= '<div class="banner">';
			if($row_get_products['prod_quantity']==0)
			{
				$products_data .= '<div class="text" style="font-size:10px;">Out Of Stock</div>';
			}
		    else
			{
				$products_data .= '<div class="text">New</div>';	
			}		
			$products_data .= '</div>';
			$products_data .= '</div>';
			$products_data .= '<div  align="center" style="width:200px;height:200px;">';
			if(trim($row_get_products['prod_img_file_name']) != "")
			{
				$imagepath 		= '/images/planet/org'.$row_get_products['prod_orgid'].'/prod_id_'.$row_get_products['prod_id'].'/medium/'.$row_get_products['prod_img_file_name'];
				if(file_exists("../".$imagepath))
				{
					$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder."/".$imagepath.'" alt="" src="'.$BaseFolder."/".$imagepath.'" >';
				}
				else
				{
					$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideDown();</script>';
					$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';							
				}						
			}
			else
			{
				$products_data .= '<script type="text/javascript">$("#prod_id'.$row_get_products['prod_id'].'").slideUp();</script>';						
				$products_data .= '<img style="max-width:100%;height:200px" data-at2x="'.$BaseFolder.'/images/no-image.jpg" alt="" src="'.$BaseFolder.'/images/no-image.jpg" >';						
			}
			$products_data .= '</div>';
			$products_data .= '<span class="hover-effect"></span>';
			$products_data .= '<div class="link-cont">';
			$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'">View</a>';
/*					$products_data .= '<a href="'.$imagepath.'" class="cws-right fancy cws-slide-left "><i class="fa fa-search"></i></a>';
					$products_data .= '<a href="page-product.php?prod_id='.$row_get_products['prod_id'].'" class=" cws-left cws-slide-right"><i class="fa fa-link"></i></a>';
*/			$products_data .= '</div>';
			$products_data .= '</div>';
			$products_data .= '<div class="product-name" style="height:60px">';
			$products_data .= '<a href="'.$BaseFolder.'/page-product.php?prod_id='.$row_get_products['prod_id'].'">'.short($row_get_products['prod_title'],50).'</a>';
			$products_data .= '</div>';
			$products_data .= '<div class="star-rating" title="Rated 4.00 out of 5">';					
			$variable = substr($row_get_products['prod_avg_review'], 0, strpos($row_get_products['prod_avg_review'], ".")); 
			if(trim($variable) != "")
			{
				$products_data .= '<span style="width:'.$variable.'%"><strong class="rating">4.00</strong> out of 5</span>';
			}						
			else
			{
				$products_data .= '<span style="width:50%"><strong class="rating">4.00</strong> out of 5</span>';						
			}
			$products_data .= '</div>';											
			$products_data .= '<span class="price">';
			$products_data .= '<span class="amount">'.current(explode('.', $row_get_products['prod_recommended_price'])).'<sup><i class="fa fa-rupee"></i></sup></span>';
			$products_data .= '</span>';
			$products_data .= '<!--<div class="product-description">';
			$products_data .= '<div class="short-description">';
			$products_data .= '<p>'.short($row_get_products['prod_description'],100).'</p>';
			$products_data .= '</div>-->';
			$products_data .= '<!-- <div class="full-description">';
			$products_data .= '<p>In blandit ultricies euismod.Lobortis erat, sed ullamcorper erat interdum et. Cras volutpat felis id enim vehicula, eu facilisis dui lacinia. Vivamus sollicitudin tristique tellus.</p>';
			$products_data .= '</div> -->';
			$products_data .= '</div>';							
			if($row_get_products['prod_quantity']!=0)
			{					
				$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button border-radius alt smaller" onClick="addToCart(\''.$row_get_products['prod_id'].'\')"> <i class="fa fa-shopping-cart"></i> </a>';
			}
			else
			{
				$products_data .= '<a href="javascript:void(0);" rel="nofollow" data-product_id="70" data-product_sku="" class="cws-button" > Out Of Stock </a>';
			}
			$products_data .= '</li>';	
		}
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($products_data),"query"=>$sql_products, "brand_data_list"=>$brand_data_p, "data_pagination"=>$data_pagination, "data_count"=>$data_count);//,"data"=>$sql_products);//.$sql_products);
	}							
	echo json_encode($response_array);	
}

# =============================================================================================================
# Start : Virtual REality  Contact Form [By Satish on 17-02-2017]
# =============================================================================================================
if((isset($_POST['virtual_reality_req'])) == '1' && (isset($_POST['virtual_reality_req'])))
{
	$response_array		= array();
	$comp_name		    = $_POST['comp_name'];
	$cust_name		= $_POST['cust_name'];
	$comp_email	    = $_POST['comp_email'];
	$contact_no          = $_POST['contact_no'];
	$about_comp		= $_POST['about_comp'];
	
	
   		$sql_add_service		= "INSERT INTO `tbl_list_with_us`(`comp_name`, `sc_custname`, `comp_email`, `contact_no`, `about_company`,";
		$sql_add_service		.= " added_date) VALUES ('".$comp_name."','".$cust_name."','".$comp_email."',";
		$sql_add_service		.= " '".$contact_no."','".$about_comp."','".$datetime."')";
		$res_add_service 	= mysqli_query($db_con,$sql_add_service) or die(mysqli_error($db_con));
		if($res_add_service)
		{
			$response_array 	= array("Success"=>"Success","resp"=>"Thank you for contacting us. We will get back to your shortly !");
		}
		else
		{
			$response_array 	= array("Success"=>"fail","resp"=>"Your request not submitted. Please try letter....");
		}
	$subject = "PlanetEducate List with us";
	$message_body  ='Dear Support';	
	$message_body .='';
	$message_body = '<table class="" data-module="main Content" height="347" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
	$message_body .= '<tr>';
	$message_body .= '<th>Company Name :</th>';
	$message_body .= '<td>'.$comp_name.'</td>';
	$message_body .= '</tr>';
	$message_body .= '<tr>';
	$message_body .= '<th>Name :</th>';
	$message_body .= '<td>'.$cust_name.'</td>';
	$message_body .= '</tr>';
	$message_body .= '<tr>';
	$message_body .= '<th>Email :</th>';
	$message_body .= '<td>'.$comp_email.'</td>';
	$message_body .= '</tr>';
	$message_body .= '<tr>';
	$message_body .= '<th>Mobile No :</th>';
	$message_body .= '<td>'.$contact_no.'</td>';
	$message_body .= '</tr>';
	
	$message_body .= '<tr>';
	$message_body .= '<th>About Company :</th>';
	$message_body .= '<td>'.$about_comp.'</td>';
	$message_body .= '</tr>';
	
	
	
	
	$message_body .= '<tr>';
	$message_body .= '<th>Date :</th>';
	$message_body .= '<td>'.$datetime.'</td>';
	$message_body .= '</tr>';
	$message_body .='</table>';
	$message=mail_template_header()."".$message_body."".mail_template_footer();
	sendEmail('support@planeteducate.com',$subject,$message);
	
	insertEmailSmsEntryIntoNotification('List With Us', $message,'support@planeteducate.com',$contact_no );
	echo json_encode($response_array);
}


if(isset($_POST['registration']) && $_POST['registration']==1)
{
	$response_array  = array('Image'=>$_FILES['file']['name']);
	$cust_type       = mysqli_real_escape_string($db_con,$_POST['cust_type']);
	$cust_email       = mysqli_real_escape_string($db_con,$_POST['cust_email']);
	$cust_mobile_num       = mysqli_real_escape_string($db_con,$_POST['cust_mobile_num']);
	
	if($cust_type=='Buyer')
	{
		$type       = "cust_";
		$table_name = 'tbl_customer';
	}
	else
	{
		$type       ="vendor_";
		$table_name = 'tbl_vendor';
	}
	
	$sql_check_user =" SELECT * FROM ".$table_name." WHERE ".$type."email='".$cust_email."' or ".$type."mobile='".$cust_mobile_num."'";
 	//$sql_check_user =" SELECT * FROM tbl_vendor WHERE vendor_email='satishdhere007@gmail.com' or vendor_mobile='9889889898'";
 	//($sql_check_user);
	$res_check_user = mysqli_query($db_con,$sql_check_user) or die(mysqli_error($db_con));
	//quit($sql_check_user);
	$num_check_user = mysqli_num_rows($res_check_user);
	if($num_check_user==0)
	{
		$file_size =$_FILES['file']['size'];
        if($file_size > 5242880 &&  $file_size !=0) // file size
		{
			quit('Image size should be less than 5 MB');
		}
		
		$file_name                    = explode('.',$_FILES['file']['name']);
		$file_name                    = date('dhyhis').'.'.$file_name[1];
		$dir                          = '../'.$type.'document/'.$file_name;
		
		if(move_uploaded_file($_FILES['file']['tmp_name'],$dir))
		{
			$data[$type.'name']           = mysqli_real_escape_string($db_con,$_POST['cust_name']);
			$data[$type.'email']          = mysqli_real_escape_string($db_con,$_POST['cust_email']);
			$data[$type.'mobile']         = mysqli_real_escape_string($db_con,$_POST['cust_mobile_num']);
			$data[$type.'document']       = $file_name;
			$data[$type.'created']        = $datetime;
			$res = insert($table_name,$data);
			
			if($res)
			{
				quit('Success',1);
			}
			else
			{
				quit('fail');
			}
		}
		else
		{
			quit('Please try letter...!');
		}
	}
	else
	{
		quit('Mobile Number or Email already registered...!');
	}
}
# =============================================================================================================
# end :Virtual Reality Form [By Satish on 17-02-2017]
# =============================================================================================================
?>