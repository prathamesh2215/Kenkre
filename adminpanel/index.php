<?php 
include("include/routines.php");
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
<body class="login theme-orange" data-theme="theme-orange" style="background-color:#fff !important;">
<div class="wrapper" style="margin-top:15%;">
	<div class="login-body" style="background-color:transparent">    
            <div align="center" style="padding:20px 0;"><a href="javascript:void(0);"><h1><strong>Kenkre Logo</strong></h1><!--<img src="images/logo.png" height="120" style="height:100px" />--></a></div>
			<form method='post' action="#" class='form-validate' id="frm_login">
				<div class="control-group">
					<div class="pw controls">
						<input type="text" name="userid" id="userid" placeholder="Email" class="input-block-level" data-rule-required="true" data-rule-email="true" style="border-right:inset;border-top:inset;border-color:#E7E7E7;">
					</div>
				</div>
				<div class="control-group">
					<div class="pw controls">
						<input type="password" name="password" id="password" placeholder="Password" class='input-block-level' data-rule-required="true" style="border-right:inset;border-top:inset;border-color:#E7E7E7;">
					</div>
				</div>
				<div class="submit">
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
					
	</script>
    </body>
</html>