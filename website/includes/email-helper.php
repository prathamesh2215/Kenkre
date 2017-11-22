<?php
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
			$mail->Host       	= 'mail.idb.com'; 
			$mail->Username   	= 'support@idb.com';
			$mail->Password   	= 'idbTEST1199';           
			$mail->From     	= 'support@idb.com';
			$mail->FromName 	= 'IDB';
			$mail->AddAddress($email,$email);
			$mail->AddReplyTo('support@idb.com', 'IDB');
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
?>