<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/email-helper.php");
include("include/routines.php");

$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


//------------------this is used for inserting records---------------------
if((isset($_POST['insert_coach'])) == "1" && isset($_POST['insert_coach']))
{
	$data['fullname']      = strtolower(mysqli_real_escape_string($db_con,$_POST['coach_name']));
	$data['email']         = mysqli_real_escape_string($db_con,$_POST['coach_email']);
	$data['mobile_num']    = mysqli_real_escape_string($db_con,$_POST['coach_mobile']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['area_status']);
	$data['state']         = mysqli_real_escape_string($db_con,$_POST['state_code']);
	$data['city']          = mysqli_real_escape_string($db_con,$_POST['city']);
	
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['area_status']);
	
	$data['created_by']    = $logged_uid;
	$data['created']       = $datetime;
	$password              = 123456;//generateRandomString(8);
	$data['salt_value']    = generateRandomString(6);
	$data['password']      = md5($password.$data['salt_value']);
	$data['sms_status']    = 1;
	$data['userid']        = $data['email'];
	$data['utype']         = 15;
	$data['email_status']  = 1;
	
	if($_FILES['coach_img']['name'] !="" && isset($_FILES['coach_img']['name']))
	{
		$imagedata = getimagesize($_FILES['coach_img']['tmp_name']);
		if($imagedata[0] <500)
		{
			quit('Coach Image width should be greater than 500 Pixel');
		}
		$coach_img                  = explode('.',$_FILES['coach_img']['name']);
		$coach_img                  = date('dhyhis').'.'.$coach_img[1];
		$data['image']          = $coach_img;
		
		$dir                         ='images/coach/';
		if(!move_uploaded_file($_FILES['coach_img']['tmp_name'],$dir.$coach_img))
		{
			quit('Coach Image not uploaded please try letter...!');
		}
	}
	
	
	$sql_check             = " SELECT * FROM tbl_cadmin_users WHERE email='".$data['email']."' or mobile_num='".$data['mobile_num']."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = insert('tbl_cadmin_users',$data); 
			
			//============Start : Coach Additional Informaion=======================//
			
			$addata['coach_id']            = $insert_id;
			$addata['coach_experience']    = mysqli_real_escape_string($db_con,$_POST['coach_experience']);
			$addata['coach_designation']   = mysqli_real_escape_string($db_con,$_POST['coach_designation']);
			
			$contract_start_date           = mysqli_real_escape_string($db_con,$_POST['contract_start_date']);
			$contract_start_date           = explode('-',$contract_start_date);// d/m/y
			$addata['contract_start_date']   = $contract_start_date[2].'-'.$contract_start_date[1].'-'.$contract_start_date[0];
			
			$contract_end_date             = mysqli_real_escape_string($db_con,$_POST['contract_end_date']);
			$contract_end_date             = explode('-',$contract_end_date);// d/m/y
			$addata['contract_end_date']     = $contract_end_date[2].'-'.$contract_end_date[1].'-'.$contract_end_date[0];
			
			$addata['coach_about']         = mysqli_real_escape_string($db_con,$_POST['coach_bio']);
		
			insert('coach_additional_info',$addata);
			//============End   :  Coach Additional Informaion=======================//
			
			
			//===========Start Assign Rights===================//
			$asdata['ar_user_owner_id']  = $insert_id;
			$asdata['ar_current_rights'] = '{1:1,1,1,1}';
			$asdata['ar_history_rights'] = '{1:1,1,1,1}';
			$asdata['createddt']         = $datetime;
			$asdata['createdby']         = $logged_uid;
		    insert('tbl_assign_rights',$asdata);
			//===========End Assign Rights===================//
			
			if($insert_id)
			{
				$adata['add_details']    = mysqli_real_escape_string($db_con,$_POST['address']);
				$adata['add_state']      = mysqli_real_escape_string($db_con,$_POST['state_code']);
				$adata['add_city']       = mysqli_real_escape_string($db_con,$_POST['city']);
				$adata['add_area']       = mysqli_real_escape_string($db_con,$_POST['area']);
				$adata['add_pincode']    = mysqli_real_escape_string($db_con,$_POST['area_pincode']);
				$adata['add_status']     = mysqli_real_escape_string($db_con,$_POST['area_status']);
				$adata['add_user_id']    = $insert_id;
				$adata['add_user_type']  = 'admin';
				$adata['add_created']    = $datetime;
				$adata['add_created_by'] = $logged_uid;
				$adata['add_id']         = getNewId('add_id','tbl_address_master');
				insert('tbl_address_master',$adata);
				
			}
			else
			{
				quit('Please try letter...!');
			}
			
		    // =====================================================================================================
			// START : Sending the mail for Email Validation Dn By Prathamesh On 04092017 
			// =====================================================================================================
			{
			$subject		= 'Kenkre - Email Verification';
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
																	$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="font-weight:600; letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> Dear '.ucwords($data['fullname']).', <br>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td>';
																		$message_body .= '<table data-bgcolor="Color Button 01" class="table-button230-center" style="border-radius: 900px;" height="36" width="230" align="center" bgcolor="#5bbc2e" border="0" cellpadding="0" cellspacing="0">';
																		
																		   $message_body .= '<tr>';
																	        $message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">Your Password is : '.$data['password'].' </td>';
																            $message_body .= '</tr>';
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
         	}
			/*if(sendEmail($data[$type.'email'],$subject,$message))
			{
				$noti['type']			= 'Email_Verification_Mail';
				$noti['message']		= htmlspecialchars($message, ENT_QUOTES);
				$noti['user_email']		= $data[$type.'email'];
				$noti['user_mobile_num']= $data[$type.'mobile'];
				$noti['created_date']	= $datetime;
				
				$noti_data	= insert('tbl_notification',$noti);
			}
			else
			{
				quit('Email not sent please try after sometime');
			}*/
			quit('Coach Added Successfully...!',1);
			// =====================================================================================================
			// END : Sending the mail for Email Validation Dn By Prathamesh On 04092017 
			// =====================================================================================================
		
	}
	else
	{
		quit('Email or Mobile Number already Exist...!');
	}	
}

//=================Start : Update Coach===================================//
if((isset($_POST['update_coach'])) == "1" && isset($_POST['update_coach']))
{
	$data['fullname']      = strtolower(mysqli_real_escape_string($db_con,$_POST['coach_name']));
	$data['email']         = mysqli_real_escape_string($db_con,$_POST['coach_email']);
	$data['mobile_num']    = mysqli_real_escape_string($db_con,$_POST['coach_mobile']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['area_status']);
	$data['state']         = mysqli_real_escape_string($db_con,$_POST['state_code']);
	$data['city']          = mysqli_real_escape_string($db_con,$_POST['city']);
	$data['mobile_num']    = mysqli_real_escape_string($db_con,$_POST['coach_mobile']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['area_status']);
	
	$id                    = mysqli_real_escape_string($db_con,$_POST['id']);
	
	$data['modified_by']   = $logged_uid;
	$data['modified']      = $datetime;
	$password              = generateRandomString(8);
	$data['salt_value']    = generateRandomString(6);
	$data['password']      = md5($password.$data['salt_value']);
	$data['sms_status']    = 1;
	$data['userid']        = $data['email'];
	$data['utype']         = 15;
	
	if($_FILES['coach_img']['name'] !="" && isset($_FILES['coach_img']['name']))
	{
		$sql_get_coach     = " SELECT image FROM tbl_cadmin_users WHERE  id='".$id."'";
        $res_get_coach     = mysqli_query($db_con,$sql_get_coach) or die(mysqli_error($db_con));
		$row_get_coach     = mysqli_fetch_array($res_get_coach);      
		  
		$imagedata = getimagesize($_FILES['coach_img']['tmp_name']);
		if($imagedata[0] <500)
		{
			quit('Coach Image width should be greater than 500 Pixel');
		}
		$team_logo                  = explode('.',$_FILES['coach_img']['name']);
		$team_logo                  = date('dhyhis').'.'.$team_logo[1];
		$dir                         ='images/coach/';
		
		if(move_uploaded_file($_FILES['coach_img']['tmp_name'],$dir.$team_logo))
		{
			$data['image']         = $team_logo;
			unlink($dir.$row_get_coach['image']);
		}
	}
	
	$sql_check             = " SELECT * FROM tbl_cadmin_users WHERE (email='".$data['email']."' or mobile_num='".$data['mobile_num']."') AND id!='".$id."'";
    $res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	
	
	
	if($num_check==0)
	{
		//============Start : Coach Additional Informaion=======================//
			
			$addata['coach_experience']    = mysqli_real_escape_string($db_con,$_POST['coach_experience']);
			$addata['coach_designation']   = mysqli_real_escape_string($db_con,$_POST['coach_designation']);
			
			$contract_start_date           = mysqli_real_escape_string($db_con,$_POST['contract_start_date']);
			$contract_start_date           = explode('-',$contract_start_date);// d/m/y
			$addata['contract_start_date'] = $contract_start_date[2].'-'.$contract_start_date[1].'-'.$contract_start_date[0];
			
			$contract_end_date             = mysqli_real_escape_string($db_con,$_POST['contract_end_date']);
			$contract_end_date             = explode('-',$contract_end_date);// d/m/y
			$addata['contract_end_date']   = $contract_end_date[2].'-'.$contract_end_date[1].'-'.$contract_end_date[0];
			
			$addata['coach_about']         = mysqli_real_escape_string($db_con,$_POST['coach_bio']);
		    
			if(isExist('coach_additional_info' ,array('coach_id'=>$id)))
			{
				update('coach_additional_info',$addata,array('coach_id'=>$id));
			}
			else
			{
			   $addata['coach_id'] = $id;
			   insert('coach_additional_info',$addata);
			}
			
			
			//============End   :  Coach Additional Informaion=======================//
		
		$insert_id = update('tbl_cadmin_users',$data,array('id'=>$id)); 
		
		if($insert_id)
		{
			
			$adata['add_details']    = mysqli_real_escape_string($db_con,$_POST['address']);
			$adata['add_state']      = mysqli_real_escape_string($db_con,$_POST['state_code']);
			$adata['add_city']       = mysqli_real_escape_string($db_con,$_POST['city']);
			$adata['add_area']       = mysqli_real_escape_string($db_con,$_POST['area']);
			$adata['add_pincode']    = mysqli_real_escape_string($db_con,$_POST['area_pincode']);
			$adata['add_status']     = mysqli_real_escape_string($db_con,$_POST['area_status']);
			$adata['add_user_type']  = 'admin';
			
			
			$num = isExist('tbl_address_master' ,array('add_user_id'=>$id,'add_user_type'=>'admin'));
			
			if($num!=0)
			{
				$adata['add_modified']    = $datetime;
				$adata['add_modified_by'] = $logged_uid;
				update('tbl_address_master',$adata,array('add_user_id'=>$id,'add_user_type'=>'admin'));
			}
			else
			{
				$adata['add_id']         = getNewId('add_id','tbl_address_master');
				$adata['add_created']    = $datetime;
				$adata['add_created_by'] = $logged_uid;
				$adata['add_user_id']    = $id;
				insert('tbl_address_master',$adata);
			}
			
		}
		else
		{
			quit('Please try letter...!');
		}
		
		
		{
		// =====================================================================================================
		// START : Sending the mail for Email Validation Dn By Prathamesh On 04092017 
		// =====================================================================================================
		$subject		= 'Kenkre - Email Verification';
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
																$message_body .= '<td data-color="Name" data-size="Name" data-min="8" data-max="30" class="td-pad10" style="font-weight:600; letter-spacing: 0.000em; line-height:20px; font-size:14px; color:#7f7f7f; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="left"> Dear '.ucwords($data['fullname']).', <br>';
																$message_body .= '</td>';
															$message_body .= '</tr>';
															$message_body .= '<tr>';
																$message_body .= '<td>';
																	$message_body .= '<table data-bgcolor="Color Button 01" class="table-button230-center" style="border-radius: 900px;" height="36" width="230" align="center" bgcolor="#5bbc2e" border="0" cellpadding="0" cellspacing="0">';
																	
																	   $message_body .= '<tr>';
																		$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">Your Password is : '.$data['password'].' </td>';
																		$message_body .= '</tr>';
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
		{
			$noti['type']			= 'Email_Verification_Mail';
			$noti['message']		= htmlspecialchars($message, ENT_QUOTES);
			$noti['user_email']		= $data[$type.'email'];
			$noti['user_mobile_num']= $data[$type.'mobile'];
			$noti['created_date']	= $datetime;
			
			$noti_data	= insert('tbl_notification',$noti);
		}
		else
		{
			quit('Email not sent please try after sometime');
		}*/
		// =====================================================================================================
		// END : Sending the mail for Email Validation Dn By Prathamesh On 04092017 
		// =====================================================================================================
		
		quit('Coach Updated Successfully...!',1);
		}
	}
	else
	{
		quit('Email or Mobile Number already Exist...!');
	}	
}


//=================Start : Load Coach===================================//
if((isset($obj->load_coach)) == "1" && isset($obj->load_coach))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT tc.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.modified_by) AS name_midified_by 
							FROM `tbl_cadmin_users` AS tc WHERE utype=15";
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND created='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (fullname like '%".$search_text."%' or email like '%".$search_text."%' ";
			$sql_load_data .= " or mobile_num like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$coach_data  = "";	
			$coach_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$coach_data .= '<thead>';
    	  	$coach_data .= '<tr>';
         	$coach_data .= '<th style="text-align:center">Sr No.</th>';
			$coach_data .= '<th style="text-align:center">Image</th>';
			$coach_data .= '<th style="text-align:center">Coach Name</th>';
			$coach_data .= '<th style="text-align:center">Email</th>';
			$coach_data .= '<th style="text-align:center">Mobile Number</th>';
			$coach_data .= '<th style="text-align:center">Created Date</th>';
			$coach_data .= '<th style="text-align:center">Created By</th>';
			$coach_data .= '<th style="text-align:center">Modified Date</th>';
			$coach_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_coach.php",3);
			
			if($dis)
			{			
				$coach_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_coach.php",1);
			
			if($edit)
			{			
				$coach_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_coach.php",2);
			
			if($delete)
			{			
				$coach_data .= '<th style="text-align:center"><div style="text-align:center">';
				$coach_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$coach_data .= '</tr>';
      		$coach_data .= '</thead>';
      		$coach_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$coach_data .= '<tr>';				
				$coach_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
			    $coach_data .= '<td style="text-align:center">';
				if($row_load_data['image']!="")
				{
					$coach_data .='<img src="images/coach/'.$row_load_data['image'].'" width="60px" alt="No Image">';
				}
				else
				{
					$coach_data .='<img style="width:60px" src="img/person.jpg" alt="">';
				}
				
				
				$coach_data .='</td>';			
				$coach_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['fullname']).'" class="btn-link" id="'.$row_load_data['id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				
				if($row_load_data['email']!='')
				{
					$coach_data .= '<td style="text-align:center">'.$row_load_data['email'].'</td>';
				}
				else
				{
					$coach_data .= '<td style="text-align:center;color:red">Not available</td>';
				}
				
				
				$coach_data .= '<td style="text-align:center">'.$row_load_data['mobile_num'].'</td>';			
				$coach_data .= '<td style="text-align:center">'.$row_load_data['created'].'</td>';
				$coach_data .= '<td style="text-align:center">'.ucwords($row_load_data['name_created_by']).'</td>';
				$coach_data .= '<td style="text-align:center">'.$row_load_data['modified'].'</td>';
				$coach_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_coach.php",3);
				
				if($dis)
				{					
					$coach_data .= '<td style="text-align:center">';					
					if($row_load_data['status'] == 1)
					{
						$coach_data .= '<input type="button" value="Active" id="'.$row_load_data['id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$coach_data .= '<input type="button" value="Inactive" id="'.$row_load_data['id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$coach_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_coach.php",1);
				
				if($edit)
				{				
					$coach_data .= '<td style="text-align:center">';
					$coach_data .= '<input type="button" value="Edit" id="'.$row_load_data['id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_coach.php",2);
				if($delete)
				{					
					$coach_data .= '<td><div class="controls" align="center">';
					$coach_data .= '<input type="checkbox" value="'.$row_load_data['id'].'" id="batch'.$row_load_data['id'].'" name="batch'.$row_load_data['id'].'" class="css-checkbox batch">';
					$coach_data .= '<label for="batch'.$row_load_data['id'].'" class="css-label"></label>';
					$coach_data .= '</div></td>';										
				}
	          	$coach_data .= '</tr>';															
			}	
      		$coach_data .= '</tbody>';
      		$coach_data .= '</table>';	
			$coach_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$coach_data);				
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}


//=================Start : Load Coach Part===================================//
if((isset($obj->load_coach_parts)) == "1" && isset($obj->load_coach_parts))
{
	$id             = $obj->area_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		$disabled ='';
		if($id != "" && $req_type != "add")
		{
			$sql_coach_data 	    = "Select * from tbl_cadmin_users where id = '".$id."' ";
			$result_coach_data 	= mysqli_query($db_con,$sql_coach_data) or die(mysqli_error($db_con));
			$row_coach_data		= mysqli_fetch_array($result_coach_data);		
		}	
			
		$data = '';
		if($id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" name="id" id="id" value="'.$id.'">';
			$data .= '<input type="hidden" name="update_coach" id="update_coach" value="1">';
			$disabled = 'disabled';
		}        
		
		
		if($req_type=='add')
		{
				$data .= '<input type="hidden" name="insert_coach" id="insert_area" value="1">';
		}
		
		if($req_type !='view')
		{
			//////=============================================Start : Coach Name======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Coach Name<sup class="validfield">
			<span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input onkeypress="return charsonly(event);" type="text" id="coach_name" name="coach_name" class="input-large keyup-char" ';
			$data .= ' placeholder="Enter Coach Name" data-rule-required="true"  value="'.@$row_coach_data['fullname'].'"/><br>';
			$data .= '</div>';
			$data .= '</div> <!-- Coach Name -->';
			
			//////=============================================Start : Coach Image======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Coach Image</label>';
			$data .= '<div class="controls">';
			if(!isset($row_coach_data['image']) || $row_coach_data['image']=="")
			{
				$data .= '<img src="images/person.png" width="200px"  id="coach_img_p" name="coach_img_p" class="" alt="Coach Image"><br>';
			}
			else
			{
				$data .= '<img src="images/coach/'.@$row_coach_data['image'].'" width="200px"  id="coach_img_p" name="coach_img_p" class="" alt="Coach Image"><br>';
			}
			
				
				
			$data .= '<input  type="file" id="coach_img" name="coach_img" class="input-large keyup-char coach_img" ';
			if($req_type=='add')
			{
				//$data .=' data-rule-required="true"  ';
			}
			$data .= '"/><br>';
			$data .='<ul class="css-ul-list" style="color:red">
						   <li>Only "jpg" , "png" or "jpeg" image will be accepted.</li>
						   <li>Image width should be greater than  \'500\'   pixel.</li>
						</ul>';
			$data .= '</div>';
			$data .= '</div> <!-- Coach Name -->';
			$data .="<script type=\"text/javascript\">	$('.coach_img').change(function(){
				readURL(this);
				});
			  </script>";
			
			//////=============================================Start : Coach Email======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Email Id<sup class="validfield">
			<span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input  type="email" id="coach_email" name="coach_email" class="input-large keyup-char" placeholder="Enter Email ID" ';
			$data .= ' data-rule-required="true"  value="'.@$row_coach_data['email'].'"/><br>';
			$data .= '</div>';
			$data .= '</div> <!-- Coach Name -->';
			
			
			//////=============================================Start : Coach Mobile Number======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Mobile Number<sup class="validfield">
			<span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input onkeypress="return numsonly(event);" minlength="10" maxlength="10"  type="text" id="coach_mobile" ';
			$data .= 'name="coach_mobile" class="input-large" placeholder="Enter 10 Digit Mobile Number" data-rule-required="true"  value="'.$row_coach_data['mobile_num'].'"/>';
			$data .= '</div>';
			$data .= '</div> <!-- Coach Name -->';
			
			//////=============================================Start : Coach Address======================================
			if($req_type!='add')
			{
				$sql_get_add =" SELECT * FROM tbl_address_master WHERE add_user_id='".$id."' AND add_user_type='admin'";
				$res_get_add = mysqli_query($db_con,$sql_get_add) or die(mysqli_error($db_con));
				$row_get_add = mysqli_fetch_array($res_get_add);
			}
			
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Address<sup class="validfield">
			<span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<textarea  id="address" name="address" class="input-large" placeholder="Enter Address" data-rule-required="true">';
			$data .= @$row_get_add['add_details']; 
			$data .='</textarea><br>';
			$data .= '</div>';
			$data .= '</div> <!-- Coach Address -->';
		
			//////=============================================Start : State======================================
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">State<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<select name="state_code" onchange="getCityList(this.value,\'city\')" id="state_code" class="select2-me input-large "';
			$data.=' data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select State</option>';
			$data.=getList('tbl_state','state','state_name',@$row_get_add['add_state'],$where_arr=array('country_id'=>'IN'));
			$data .= '</select>';
			$data .= '</div>';
			$data .= '</div> <!-- Country -->';
			$data .= '<script type="text/javascript">';
			$data .= '$("#state_code").select2();';
			$data .= '</script>';
				
			
			//////=============================================Start : City======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">City<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<select onchange="getArea(this.value,\'area\')" name="city"  id="city" class="select2-me input-large" data-rule-required="true" ';
			$data .=' tabindex="-1">';
			$data .= '<option value="">Select City</option>';
			if($req_type !='add')
			{
				$data.=getList('tbl_city','city_id','city_name',@$row_get_add['add_city'],$where_arr=array('state_id'=>$row_get_add['add_state']));
			}
			$data .= '</select>';
			$data .= '</div>';
			$data .= '</div> <!-- Country -->';
			$data .= '<script type="text/javascript">';
			$data .= '$("#city").select2();';
			$data .= '</script>';
			
			
			//////=============================================Start : Area======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Area<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<select name="area"  id="area" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select Area</option>';
			if($req_type !='add')
			{
				$data.=getList('tbl_area','area_id','area_name',@$row_get_add['add_area'],$where_arr=array('area_city'=>$row_get_add['add_city']));
			}
			$data .= '</select>';
			$data .= '</div>';
			$data .= '</div> <!-- Country -->';
			$data .= '<script type="text/javascript">';
			$data .= '$("#area").select2();';
			$data .= '</script>';
		
			//////=============================================Start : Pincode======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Pin Code<sup class="validfield">
			<span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" id="area_pincode" placeholder="Enter 6 Digit Pincode" name="area_pincode" maxlength="6" minlength="6" ';
			$data .=' onKeyPress="return isNumberKey(event)" class="input-large keyup-char" data-rule-required="true"  value="'.$row_get_add['add_pincode'].'"/>';
			$data .= '</div>';
			$data .= '</div> <!-- Pincode -->';
	
			if($req_type !='')
			{
				$sql_get_info = " SELECT * FROM coach_additional_info WHERE coach_id='".$id."' ";
				$res_get_info = mysqli_query($db_con,$sql_get_info) or die(mysqli_error($db_con));
				$row_get_info = mysqli_fetch_array($res_get_info);
			}
			
			//////=============================================Start : Experience======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Experience<sup class="validfield">
			<span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" id="coach_experience" placeholder="Enter Experience in years " name="coach_experience"  ';
			$data .='  class="input-large " onkeypress="return isNumberKey(event);" maxlength="2" data-rule-required="true"  value="'.@$row_get_info['coach_experience'].'"/>';
			$data .= '</div>';
			$data .= '</div> <!-- Experience -->';
			
			
			//////=============================================Start : Designation======================================
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Designation<sup class="validfield">
			<span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" id="coach_designation" placeholder="Enter Designation" name="coach_designation"  ';
			$data .='  class="input-large" data-rule-required="true"  value="'.@$row_get_info['coach_designation'].'"/>';
			$data .= '</div>';
			$data .= '</div> <!-- Experience -->';
			
			//////=============================================Start : Contract Start Date======================================
			
			$contract_start_date ='';
			if($req_type!='add')
			{
				
				$contract_start_date             = explode('-',@$row_get_info['contract_start_date']);// d/m/y
				$contract_start_date             = $contract_start_date[2].'-'.$contract_start_date[1].'-'.$contract_start_date[0];
			}
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Contract Start Date</label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" id="contract_start_date" placeholder="Enter Contract Start Date" name="contract_start_date"  ';
			$data .='  class="input-large keyup-char datepicker"   value="'.@$contract_start_date.'"/>';
			$data .= '</div>';
			$data .= '</div> <!-- Experience -->';
			$data .="<script type=\"text/javascript\">	 $( '#contract_start_date' ).datepicker({
			changeMonth	: true,
			changeYear	: true,
			format: 'dd-mm-yyyy',
			//yearRange 	: 'c:c',//replaced 'c+0' with c (for showing years till current year)
			startDate: '',
			maxDate: new Date(),	
		   });
		  </script>";
			
			//////=============================================Start : Contract End Date======================================
			$contract_start_date ='';
			if($req_type!='add')
			{
				$contract_end_date             = explode('-',@$row_get_info['contract_end_date']);// d/m/y
				$contract_end_date             = $contract_end_date[2].'-'.$contract_end_date[1].'-'.$contract_end_date[0];
			}
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Enter  Contract End Date</label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" id="contract_end_date" placeholder="Enter Contract End Date" name="contract_end_date"  ';
			$data .='  class="input-large datepicker"   value="'.@$contract_end_date.'"/>';
			$data .= '</div>';
			$data .= '</div> <!-- Experience -->';
			$data .="<script type=\"text/javascript\">	 $( '#contract_end_date' ).datepicker({
			changeMonth	: true,
			changeYear	: true,
			format: 'dd-mm-yyyy',
			//yearRange 	: 'c:c',//replaced 'c+0' with c (for showing years till current year)
			startDate: '',
			maxDate: new Date(),	
		   });
		  </script>";
	
			//////=============================================Start : Bio======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">About yourself</label>';
			$data .= '<div class="controls">';
			$data .= '<textarea  id="coach_bio" name="coach_bio" class="input-large" placeholder="Enter About yourself">';
			$data .= @$row_get_info['coach_about']; 
			$data .='</textarea><br>';
			$data .= '</div>';
			$data .= '</div> <!-- Coach Address -->';
			
		
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
			$data .= '<div class="controls">';
			
			if($id != "" && $req_type == "view")
			{
				if($row_get_add['status'] == 1)
				{
					$data .= ' <label class="control-label" style="color:#30DD00"> Active </label>';
				}
				if($row_get_add['status'] == 0)
				{
					$data .= ' <label class="control-label" style="color:#E63A3A"> Inactive </label>';
				}
			}
			else
			{  
			  if($id != "" && $req_type == "edit")
			  {
					$data  .= '<input type="radio" name="area_status" value="1" class="css-radio" data-rule-required="true" ';
					$dis	= checkFunctionalityRight("view_coach.php",3);
					if(!$dis)
					{
					//$data .= ' disabled="disabled" ';
					}
					if($row_coach_data['status'] == 1)
					{
						$data .= 'checked ';
					}
					$data .= '> Active ';
					$data .= '<input type="radio" name="area_status" value="0" class="css-radio" data-rule-required="true"';
					if(!$dis)
					{
					//$data .= ' disabled="disabled" ';
					}
					if($row_coach_data['status'] == 0  )
					{
						$data .= 'checked ';
					}
					$data .= '> Inactive';
				} 
				else  
				{
					$data .= ' <input type="radio" name="area_status" value="1" class="css-radio" data-rule-required="true" ';
					$data .= '> Active ';
					$data .= ' <input type="radio" name="area_status" value="0" class="css-radio" data-rule-required="true"';
				
					$data .= '> Inactive ';
				}
			}
						
			$data .= '<label for="radiotest" class="css-label"></label>';
			$data .= '<label name = "radiotest" ></label>';
			$data .= '</div>';
			$data .= '</div><!--Status-->';
		
			
			$data .= '<div class="form-actions">';
			if($id == "" && $req_type == "add")
			{
				$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Coach</button>';			
			}
			elseif($id != "" && $req_type == "edit")
			{
				$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Coach</button>';			
			}			
			$data .= '</div> <!-- Save and cancel -->';	
		}
		else
		{
			$sql_get_info = " SELECT * FROM coach_additional_info WHERE coach_id='".$id."' ";
			$res_get_info = mysqli_query($db_con,$sql_get_info) or die(mysqli_error($db_con));
			$row_get_info = mysqli_fetch_array($res_get_info);
			
			$sql_get_add  = " SELECT * FROM tbl_address_master as tam ";
			$sql_get_add .= " INNER JOIN tbl_state as ts ON tam.add_state = ts.state ";
			$sql_get_add .= " INNER JOIN tbl_area as ta ON tam.add_area = ta.area_id ";
			$sql_get_add .= " INNER JOIN tbl_city as tc ON tam.add_city = tc.city_id ";
			$sql_get_add .= " WHERE add_user_type='admin' AND add_user_id='".$id."'";
			
			$res_get_add  = mysqli_query($db_con,$sql_get_add) or die(mysqli_error($db_con));
			$row_get_add  = mysqli_fetch_array($res_get_add);
			
			$state        = $row_get_add['state_name'];
			$area         = $row_get_add['area_name'];
			$city         = $row_get_add['city_name'];
			$add_detail   = $row_get_add['add_details'];
			//==================Start :  Heading  ===========================================//
			
			if($row_coach_data['status']==1)
			{
				$bgcolor = '#18BB7C';
				$color   = 'white';
			}
			else
			{
				$bgcolor = '#da4f49';
				$color    ='';
			}
			
			
			$data .='<div class="control-group" style="background-color:'.$bgcolor.';color:'.$color.' important">';
			
			$data .='<div class="span4">';
				$data .='<div style="padding:20px">';
				if($row_coach_data['image']!='')
				{
					$data .='<img style="width:200px" src="images/coach/'.$row_coach_data['image'].'" alt="">';
				}
				else
				{
					$data .='<img style="width:200px" src="img/person.jpg" alt="">';
				}
				  
				$data .='</div>';
			$data .='</div>';
			
			$data .='<div class="span8">';
			    $data .='<div style="padding:20px;">';
					$data .='<h3  style="color:white"> '.ucwords($row_coach_data['fullname']).'</h3>';
					$data .='<div class="control-group" style="background-color:'.$bgcolor.'">';
						$data .='<div class="span6">
						<span class="head2" style="color:white">Email: </span> <span class="head2" style="color:white">'.$row_coach_data['email'].'</span><br>
						<span class="head2" style="color:white">Designation: </span> <span class="head2" style="color:white">'.@$row_get_info['coach_designation'].'</span><br>
						
						';
						if($state !='')
						{
							$data .='<br><span class="head2" style="color:white">State: </span>
							<span class="head2" style="color:white"> '.@$state.'</span>';
						}
						if($area !='')
						{
							$data .='<br><span class="head2" style="color:white">Area: </span>
							<span class="head2" style="color:white"> '.@$area.'</span>';
						}
						$data .='</div>';
						$data .='<div class="span6"><span class="head2" style="color:white">Mobile : </span>
						<span class="head2"style="color:white">'.ucwords($row_coach_data['mobile_num']).'</span><br>
						<span class="head2" style="color:white">Experience: </span> <span class="head2" style="color:white">'.@$row_get_info['coach_experience'].' years</span><br>
						';
						if($city !='')
						{
							$data .='<br><span class="head2" style="color:white">City: </span>
							<span class="head2" style="color:white"> '.@ucwords($city).'</span>';
						}
						
						if($add_detail !='')
						{
							$data .='<br><span class="head2" style="color:white">Address: </span>
							<span class="head2" style="color:white"> '.@ucwords($add_detail).'</span>';
						}
						$data .='</div>';
						
						if($row_get_info['coach_about']!='')
						{
							$data .='<div class="span12" style="text-align:justify;padding-top:45px" >';
							$data .='<span class="head2" style="color:white">About : '.$row_get_info['coach_about'].'</span>';
							$data .='</div>';
						}
						
					$data .='</div>';
				$data .='</div>';
			$data .='</div>';
			
			/*$data .='<div class="span4">';
				$data .='<div style="padding:20px">';
					
				$data .='</div>';
			$data .='</div>';*/
			
			$data .='</div>';// control-group end
			
			//==================End : Heading  ===========================================//
			
			
			
			//==================Start : Coaches  ===========================================//
			
			$sql_get_stud  = "SELECT * FROM tbl_batch_coach  as tbc ";
			$sql_get_stud .= " INNER JOIN tbl_batches as  tb ON tbc.batch_id =tb.batch_id ";
			$sql_get_stud .= " WHERE tbc.coach_id='".$id."'";
			$res_get_stud  = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
			$num_get_stud  = mysqli_num_rows($res_get_stud);
			$res_array     = array();
			while($row = mysqli_fetch_array($res_get_stud))
			{
				array_push($res_array,$row);
			}
			if(!empty($res_array))
			{
				
				$num_get_stud1 = round(($num_get_stud)/2);
				$num_get_stud2 = $num_get_stud - $num_get_stud1;
				$data .='<div class="control-group">';
			
				$data .='<div class="span12">';
					$data .='<div style="padding:20px">';
						$data .='<h5>Training Batches ('.$num_get_stud.')</h5>';
					$data .='</div>';
				$data .='</div>';
				
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px">';
						$data .='<ul>';
						for($i=0;$i<$num_get_stud1;$i++)
						{
							$data .='<li class="head2"><a href="view_batch.php?pag=Training Batches&batch_id='.$res_array[$i]['batch_id'].'" target="_blank" >'.ucwords($res_array[$i]['batch_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px;">';
						$data .='<ul>';
						for($i=$i;$i<$num_get_stud;$i++)
						{
							$data .='<li class="head2"><a href="view_batch.php?pag=Training Batches&batch_id='.$res_array[$i]['batch_id'].'" target="_blank" > '.ucwords($res_array[$i]['batch_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='</div>';// control-group
			}
			//==================End : Coaches  ===========================================//
			
			//==================Start : Teams  ===========================================//
			
			
			$sql_get_stud  = "SELECT * FROM tbl_team_coach  as ttc ";
			$sql_get_stud .= " INNER JOIN tbl_team as  tm ON ttc.team_id =tm.team_id ";
			$sql_get_stud .= " WHERE coach_id='".$id."'";
			$res_get_stud  = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
			$num_get_stud  = mysqli_num_rows($res_get_stud);
			$res_array     = array();
			while($row = mysqli_fetch_array($res_get_stud))
			{
				array_push($res_array,$row);
			}
			$num_get_stud1 = round(($num_get_stud)/2);
			$num_get_stud2 = $num_get_stud - $num_get_stud1;
			if(!empty($res_array))
			{
			
				$data .='<div class="control-group">';
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px">';
						$data .='<h5> Teams ('.$num_get_stud.')</h5>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='<div class="span4" style="clear:both">';
					$data .='<div style="padding:20px">';
						$data .='<ul>';
						for($i=0;$i<$num_get_stud1;$i++)
						{
							$data .='<li class="head2"><a href="view_team.php?pag=Teams&team_id='.$res_array[$i]['team_id'].'" target="_blank" >'.$res_array[$i]['student_fname'].' '.ucwords($res_array[$i]['team_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px;">';
						$data .='<ul>';
						for($i=$i;$i<$num_get_stud;$i++)
						{
							$data .='<li class="head2"><a href="view_team.php?pag=Teams&team_id='.$res_array[$i]['team_id'].'" target="_blank" >'.$res_array[$i]['student_fname'].' '.ucwords($res_array[$i]['team_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='</div>';// row end
		     }
		}
		$response_array = array("Success"=>"Success","resp"=>$data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Type Not Defined");		
	}
	echo json_encode($response_array);
}

//---------------This is used for status change--------------------------------
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$id				= $obj->id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$data['status']      = $curr_status;
	$data['modified_by'] = $logged_uid;
	$data['modified']    = $datetime;
	$res = update('tbl_cadmin_users',$data,array('id'=>$id));
	
	if($res)
	{
		$adata['add_status']      = $curr_status;
		$adata['add_modified_by'] = $logged_uid;
		$adata['add_modified']    = $datetime;
		$res = update('tbl_address_master',$adata,array('add_user_id'=>$id,'add_user_type'=>'admin'));
		if($res)
		{
			quit('Success',1);
		}
		else
		{
			quit('Please try lettre...!');
		}
	}
	else
	{
		quit('Please try lettre...!');
	}
}

//------------------This is used for delete data---------------------------------
if((isset($obj->delete_coach)) == "1" && isset($obj->delete_coach))
{
	$response_array   = array();		
	$ids 	  = $obj->batch;
	$del_flag 		  = 0; 
	
	$row                  = checkExist('tbl_cadmin_users' ,array('id'=>$id));
	
	foreach($ids as $id)	
	{
		$sql_delete_coach	= " DELETE FROM `tbl_cadmin_users` WHERE `id` = '".$id."' ";
		$result_delete_coach	= mysqli_query($db_con,$sql_delete_coach) or die(mysqli_error($db_con));			
		if($result_delete_coach)
		{
			$del_flag = 1;
			$sql_delete_coach	= " DELETE FROM `tbl_address_master` WHERE `add_user_id` = '".$id."' AND add_user_type='admin'";
		    $result_delete_coach	= mysqli_query($db_con,$sql_delete_coach) or die(mysqli_error($db_con));	
			
			$sql_delete_team	= " DELETE FROM `tbl_team_coach` WHERE `coach_id` = '".$id."'";
		    $res_delete_team	= mysqli_query($db_con,$sql_delete_team) or die(mysqli_error($db_con));	
			
			$sql_delete_batch	= " DELETE FROM `tbl_batch_coach` WHERE `coach_id` = '".$id."'";
		    $res_delete_batch	= mysqli_query($db_con,$sql_delete_batch) or die(mysqli_error($db_con));	
			
			if($row['image']!='')
			{
				unlink('images/coach/'.$row['image']);
			}
			
		}			
	}	
	if($del_flag == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");
	}		
	echo json_encode($response_array);	
}

// ==========================================================================
// =====================Get City===================================================
if((isset($obj->getState)) == "1" && isset($obj->getState))
{
	$response_array   = array();		
	$state_id 	  = $obj->state_id;
	
	if($state_id=="")
	{
		quit('Please Select Country...!');
	}
	
	$data ='<option value="">Select City</option>';
	
	$sql =" SELECT * FROM tbl_city WHERE status =1 AND state_id='".$state_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
	}
	else
	{
		while($row = mysqli_fetch_array($res))
		{
			$data .='<option value="'.$row['city_id'].'">'.$row['city_name'].'</option>';
		}
		quit($data,1);
	}
	
}

if((isset($obj->getCity)) == "1" && isset($obj->getCity))
{
	$response_array   = array();		
	$state_id 	      = $obj->state_id;
	
	if($state_id=="")
	{
		quit('Please Select State...!');
	}
	
	$data ='<option value="">Select City</option>';
	
	$sql =" SELECT * FROM tbl_city WHERE status =1 AND state_id='".$state_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
	}
	else
	{
		while($row = mysqli_fetch_array($res))
		{
			$data .='<option value="'.$row['city_id'].'">'.$row['city_name'].'</option>';
		}
		quit($data,1);
	}
}

if((isset($obj->getArea)) == "1" && isset($obj->getArea))
{
	$response_array   = array();		
	$city_id 	      = $obj->city_id;
	
	if($city_id=="")
	{
		quit('Please Select State...!');
	}
	
	$data ='<option value="">Select Area</option>';
	
	$sql =" SELECT * FROM tbl_area WHERE area_status =1 AND area_city='".$city_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
		quit('Area not found...!');
	}
	else
	{
		while($row = mysqli_fetch_array($res))
		{
			$data .='<option value="'.$row['area_id'].'">'.ucwords($row['area_name']).'</option>';
		}
		quit($data,1);
	}
}
?>
