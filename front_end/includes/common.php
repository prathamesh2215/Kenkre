<?php

include('db_con.php');
include('login-info-helper.php');
include('query-helper.php');
include('random-helper.php');
include('email-helper.php');

////////////////==Start : Registration Satish:21082017================//////
if(isset($_POST['txt_usergrp']) && $_POST['txt_usergrp'] !='')
{
	$response_array      = array();
	$txt_user_type       = mysqli_real_escape_string($db_con,$_POST['txt_user_type']);
	
	if($txt_user_type=='buyer')
	{  
		$type              	= "cust_";
		$table_name         = 'tbl_customer';
		$data['cust_type']  = mysqli_real_escape_string($db_con,$_POST['txt_usergrp']);
		//quit($table_name);
	}
	else
	{
		$type       = "vendor_";
		$table_name = 'tbl_vendor';
		$data['cust_type']  = mysqli_real_escape_string($db_con,$_POST['txt_usergrp']);
	}
	
	$data[$type.'name']     = mysqli_real_escape_string($db_con,$_POST['txt_name']);
	$data[$type.'email']    = mysqli_real_escape_string($db_con,$_POST['txt_email']);
	
	$random_string			= '';
	$cust_email_query		= " SELECT * FROM ".$table_name." WHERE 1=1 ";
	$cust_email_status		= randomString($cust_email_query, $type.'emailstatus', 5, 'email');
	$data[$type.'emailstatus']   = $cust_email_status;
	
	$data[$type.'mobile']   = mysqli_real_escape_string($db_con,$_POST['txt_mobile']);
	$data[$type.'password'] = mysqli_real_escape_string($db_con,$_POST['txt_password']);
	$confirm_password       = mysqli_real_escape_string($db_con,$_POST['txt_cpassword']);
	
	$data[$type.'created']  = $datetime;
	if($data[$type.'password']!=$confirm_password)
	{
		quit('Password and Confirm Password not matched...!');
	}
	
	$salt   = generateRandomString(5, 'salt');
	$data[$type.'salt']        	= trim($salt);
	$data[$type.'password']   	= trim(md5($data[$type.'password'].$data[$type.'salt']));
	
	//quit($data[$type.'password'].'<==>'.$data[$type.'salt'].'<==>'.$data[$type.'password']);
	
	$sql_check_user =" SELECT * FROM ".$table_name." WHERE ".$type."email='".$data[$type.'email']."' or ".$type."mobile='".$data[$type.'mobile']."'";
 	$res_check_user = mysqli_query($db_con,$sql_check_user) or die(mysqli_error($db_con));
	$num_check_user = mysqli_num_rows($res_check_user);
	if($num_check_user==0)
	{
		//$dir                          = '../'.$type.'document/'.$file_name;
		
		//if(move_uploaded_file($_FILES['file_license_pdf']['tmp_name'],$dir))
		//{
			$cust_id                          			= insert($table_name,$data);
			
			// =====================================================================================================
			// START : getting fields value for inserting into tbl_customer_login_info Dn By Prathamesh On 04092017 
			// =====================================================================================================
			$data_login_details['cli_custid']			= $cust_id;
			$data_login_details['cli_user_type']		= $data['cust_type'];
			$data_login_details['cli_ip_address']		= get_client_ip();
			$data_login_details['cli_browser_info']		= get_browser_info();
			$data_login_details['cli_session_id']		= session_id();
			$data_login_details['cli_session_status']	= '1';
			$data_login_details['cli_created']			= $datetime;
			
			$cust_login_info_id                       	= insert('tbl_customer_login_info',$data_login_details);
			// =====================================================================================================
			// END : getting fields value for inserting into tbl_customer_login_info Dn By Prathamesh On 04092017 
			// =====================================================================================================
			
			// =====================================================================================================
			// START : Sending the mail for Email Validation Dn By Prathamesh On 04092017 
			// =====================================================================================================
			$subject		= 'IDB - Email Verification';
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
																	$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">  Email Verification. </td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="font-weight:600; letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> Dear '.ucwords($data[$type.'name']).', <br>';
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
			//quit($message);
			
			/*if(sendEmail($data[$type.'email'],$subject,$message))
			{*/
				$noti['type']			= 'Email_Verification_Mail';
				$noti['message']		= htmlspecialchars($message, ENT_QUOTES);
				$noti['user_email']		= $data[$type.'email'];
				$noti['user_mobile_num']= $data[$type.'mobile'];
				$noti['created_date']	= $datetime;
				
				$noti_data	= insert('tbl_notification',$noti);
			/*}
			else
			{
				quit('Email not sent please try after sometime');
			}*/
			// =====================================================================================================
			// END : Sending the mail for Email Validation Dn By Prathamesh On 04092017 
			// =====================================================================================================
			if($cust_id && $cust_login_info_id)
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
		quit('Mobile Number or Email already registered...!');
	}
}
////////////////==End : Registration Satish:21082017================//////

////////////////==Start : Login Satish:21082017================//////
if((isset($obj->login_customer)) == "1" && isset($obj->login_customer))// user login
{
	$cust_email 		 = trim(mysqli_real_escape_string($db_con,$obj->txt_email));
	$cust_password_login = trim($obj->txt_password);
	$cli_browser_info	 = get_browser_info();
	$cli_ip_address 	 = get_client_ip();	
	
	if($cust_email == "" || $cust_password_login == "" )
	{
		quit("Please Provide Email and Password.");
	}
	else
	{
		$sql_get_user_login 	= "SELECT * FROM `tbl_customer` WHERE `cust_email` like '".$cust_email."' ";
		$result_get_user_login	= mysqli_query($db_con,$sql_get_user_login) or die(mysqli_error($db_con));
		$num_rows_get_user_login= mysqli_num_rows($result_get_user_login);
		
		if($num_rows_get_user_login == 1)
		{
			$row_get_user_login			= mysqli_fetch_array($result_get_user_login);
			
			if($row_get_user_login['cust_emailstatus'] != 1)
			{
				quit('verification_fail',0);	
			}
			
			if($row_get_user_login['cust_status'] == 0)
			{
				quit('Your account has not been activated');
			}
			/* data base password */
			$cust_password_db_login		= trim($row_get_user_login['cust_password']);
			/* data base password */
			/* database salt*/
			$cust_salt_value_db_login	= trim($row_get_user_login['cust_salt']);
			/* database salt*/
			/*md5(old pwd + salt )*/
			$cust_password_user_login	= trim(md5($cust_password_login.$cust_salt_value_db_login));
			/*md5(old pwd + salt )*/	
			
			if($cust_password_user_login == $cust_password_db_login)
			{
				$cust_id				= $row_get_user_login['cust_id'];
				
				$data['cli_custid']       = $cust_id;	
				$data['cli_browser_info'] = $cli_browser_info;	
				$data['cli_ip_address']   = $cli_ip_address;	
				$data['cli_created']      = $datetime;	
				
				insert('tbl_customer_login_info',$data);		
				
				$cust_mobile_status	= $row_get_user_login['cust_mobilestatus'];
				
				$_SESSION['front_panel'] = $row_get_user_login;
				quit('Success',1);
				if($cust_mobile_status != 1)
				{
					//quit('Mobile Number not Verified..');
				}
				
				quit('Success',1);
			}				
			else
			{
				quit("Incorrect Login Details.1");
			}
		}
		else
		{
			quit('Incorrect Login Details');
		}
	}
}
////////////////==End : Login Satish:21082017================//////

?>