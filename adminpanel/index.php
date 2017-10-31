<?php 
include("include/db_con.php");
include("include/routines.php");
include("include/email-helper.php");
include("include/query-helper.php");
$sql_logged_user = "select * from tbl_cadmin_users where id = '".$logged_uid."'";
$result_logged_user = mysqli_query($db_con,$sql_logged_user) or die(mysqli_error($db_con));
$num_rows_logged_user = mysqli_fetch_array($result_logged_user);
if (!(isset($_POST['password'])) && $num_rows_logged_user > 0) 
{
	header("Location: view_dashboard.php?pag=Dashboard");
	exit(0);
}
elseif(isset($_POST['password']))
{
	$password = $_POST['password'];
	$sql_user = "select * from tbl_cadmin_users where id = '".$logged_uid."' and password = '".$password."'";
	$result_user = mysqli_query($db_con,$sql_logged_user) or die(mysqli_error($db_con));
	$num_rows_user = mysqli_query($db_con,$result_logged_user);	
	if ($num_rows_user > 0) 
	{
		header("Location: index.php");
		exit(0);
	}
	else
	{
		echo "Error";	
	}
}

if(isset($_POST['jsubmit']) && $_POST['jsubmit']=='forgotPassword' )
{
	$email 		= mysqli_real_escape_string($db_con,$_POST['emailid']);
	$sql_login = "select * from tbl_cadmin_users where `email` = '".addslashes($email)."' ";
	$result_login = mysqli_query($db_con,$sql_login) or die(mysqli_error($db_con));
	$num_rows_login = mysqli_num_rows($result_login);
	if ($num_rows_login != 0) 
	{
		$password              = 123456;//generateRandomString(8);
		$data['salt_value']    = generateRandomString(6);
		$data['password']      = md5($password.$data['salt_value']);
		$data['email_status']  = 1;
		$res                   = update('tbl_cadmin_users',$data,array('email'=>$_POST['emailid']));
		// =====================================================================================================
		// START : Sending the mail for Email Validation Dn By Prathamesh On 04092017 
		// =====================================================================================================
		
		{
		$subject		= 'Kenkre - Forgot Password';
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
																			$message_body .= '<td style="padding: 5px 5px; font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;" valign="middle" align="center">
																		Your Password is : '.$password.'
																			
																			
																			</td>';
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
		if(sendEmail($_POST['emailid'],$subject,$message))
		{
			$noti['type']			= 'Email_Verification_Mail';
			$noti['message']		= htmlspecialchars($message, ENT_QUOTES);
			$noti['user_email']		= $_POST['emailid'];
			$noti['user_mobile_num']= '';
			$noti['created_date']	= $datetime;
			
			$noti_data	= insert('tbl_notification',$noti);
		}
		else
		{
			quit('Email not sent please try after sometime');
		}
		quit('Password Changed Successfully...!',1);
		// =====================================================================================================
		// END : Sending the mail for Email Validation Dn By Prathamesh On 04092017 
		// =====================================================================================================
	} 
	elseif ($num_rows_login == 0) 
	{
		echo 'Email you entered does not exist.If problem persist contact admin to resolve your query.';
	}
	exit(0);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kenkre Football Club :: Admin Panel</title>
<!--<link rel="shortcut icon" href="img/logo.ico">-->
<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- Bootstrap responsive -->
<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
<!-- Theme CSS -->
<link rel="stylesheet" href="css/style.css">
<!-- Color CSS -->
<link rel="stylesheet" href="css/themes.css">
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- Nice Scroll -->
<script src="js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
<!-- Validation -->
<script src="js/plugins/validation/jquery.validate.min.js"></script>
<script src="js/plugins/validation/additional-methods.min.js"></script>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js"></script>
<script src="js/eakroko.js"></script>
<style type="text/css">
	.error
	{
		color:#FFF !important;
	}
</style>
</head>    
<body class="login theme-orange" data-theme="theme-orange" style="background-color:#8bc43f !important;">
<div class="wrapper" style="margin-top:10%;">
	<div class="login-body" style="background-color:transparent">    
            <div align="center" style="padding: 0;"><a href="javascript:void(0);"><h1><strong>
            <img  src="images/logo.png"  width="50%" /></strong></h1><!--<img src="images/logo.png" height="120" style="height:100px" />--></a></div>
			<form method='post' action="#" class='form-validate' id="frm_login">
				<div class="control-group">
					<div class="pw controls">
						<input type="text" name="userid" id="userid" placeholder="Email" class="input-block-level" data-rule-required="true" data-rule-email="true" style="border-color:#E7E7E7;">
					</div>
				</div>
				<div class="control-group">
					<div class="pw controls">
						<input type="password" name="password" id="password" placeholder="Password" class='input-block-level' data-rule-required="true" style="border-color:#E7E7E7;">
					</div>
				</div>
				<div class="submit">
                	<a type="submit" data-toggle="modal" data-target="#forgot_password"  class='' style="">Forgot Password<a>
					<input type="submit" value="Sign me in" class='btn btn-primary' style="border-bottom:outset;border-color:#208089">
				</div>
			</form>
            <!--<div class="forget">
				<a href="#"><span>Forgot password?</span></a>
			</div>-->
    </div>
</div>        
	<script type="text/javascript">
		
			$('#frm_login').on('submit', function(e) 
			{
				e.preventDefault();
				if ($('#frm_login').valid())
				{
					var emailid		= $.trim($('input[name="userid"]').val());
					var password 	= $.trim($('input[name="password"]').val());					
					$.post(location.href,{emailid:emailid,password:password,jsubmit:'SiteLogin'},function(data){
						if (data.length > 0) 
						{
							alert(data);
						} 
						else 
						{
							location.replace(location.href);
						}
						return false;
					});
				}				
			});
			
			
			function checkEmail()
			{
				var emailid		= $.trim($('input[name="femail"]').val());
									
				$.post(location.href,{emailid:emailid,jsubmit:'forgotPassword'},function(data){
					if (data.length > 0) 
					{
						alert(data);
					} 
					else 
					{
						location.replace(location.href);
					}
					return false;
				});
			}
	</script>
    
    
<div class="modal fade" id="forgot_password" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Forgot Password</h4>
      </div>
      <div class="modal-body">
        <p></p>
        <input type="text" value="" class="input-block-level" placeholder="Enter your email...!" name="femail" id="femail"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="checkEmail()">Get Password</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    </body>
</html>