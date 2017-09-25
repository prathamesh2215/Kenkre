<?php
include("include/routines.php");
//checkuser();

// This is for dynamic title, bread crum, etc.
if(isset($_GET['pag']))
{
	$title = $_GET['pag'];
}
$path_parts   = pathinfo(__FILE__);
$feature_name = "Change Password";
$title 		  = "Change Password";
$filename 	  = $path_parts['filename'].".php";
$home_name    = "Home";
$home_url 	  = "view_dashboard.php?pag=Dashboard";
$tbl_users_owner 	= $_SESSION['panel_user']['tbl_users_owner'];
$utype 				= $_SESSION['panel_user']['utype'];
if (isset($_REQUEST['jsubmit']) && $_REQUEST['jsubmit'] == "Change_Pass" && isset($_REQUEST['opass']) && isset($_REQUEST['npass']) && isset($_REQUEST['cnpass']))
{
	$opass  = $_REQUEST['opass'];
	$npass  = $_REQUEST['npass'];
	$cnpass = $_REQUEST['cnpass'];
	
	$sql_passcheck 		= "select * from tbl_cadmin_users where id = '".$logged_uid."' ";
	$result_passcheck 	= mysqli_query($db_con,$sql_passcheck) or die(mysqli_error($db_con));
	$row_passcheck		= mysqli_fetch_array($result_passcheck);
		
	$db_old_pass 		= $row_passcheck['password'];
	$db_salt_value		= $row_passcheck['salt_value'];
	$db_password		= md5($opass.$db_salt_value);
	if(strcmp($db_old_pass,$db_password) == 0)
	{
		$new_salt_value			= generateRandomString(5);	
		$new_password			= md5($npass.$new_salt_value);		
		$sql_update_pass 	= " Update tbl_cadmin_users set password = '".$new_password."' , salt_value = '".$new_salt_value."' where id = '".$logged_uid."'";
		$result_update_pass 	= mysqli_query($db_con,$sql_update_pass) or die(mysqli_error($db_con));
		if(!$result_update_pass)
		{
			echo "Password not Updated";
		} 
		else 
		{ 
			$_SESSION['panel_user']['password'] = $password;
			$to = $_SESSION['panel_user']['email'];
			$subject = "Password Successfully Updated";
			$message = "Hi,\n You have successfully changed your Password.";
			$message .= "\n";
            //if(mail($to, $subject, $message, get_mail_headers()))
			{
				
			} 
			//else 
			{
				//print "Failure sending E-mail, please try after some time.\n";
			}
		}					
	}
	else
	{
		print "Please enter correct old password";
	}
	exit(0);
}
?>
<!doctype html>
<html>
<head>
<?php 
	/* This function used to call all header data like css files and links */
	headerdata($feature_name);
	/* This function used to call all header data like css files and links */	
?>
</head>
<body  class="theme-orange" data-theme="theme-orange" >
<?php 

/* this function used to add navigation menu to the page*/ 
	navigation_menu();
/* this function used to add navigation menu to the page*/  
?> <!-- Navigation Bar --> 
<div class="container-fluid" id="content">
    <div id="main" style="margin-left:0px">
    	<div class="container-fluid">
		<?php 
		/* this function used to add navigation menu to the page*/ 
			breadcrumbs($home_url,$home_name,$title,$filename,$feature_name); 
		/* this function used to add navigation menu to the page*/ 	
		?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box box-bordered box-color">
                            <div class="box-title">
                                <h3><i class="icon-th-list"></i>Change Password</h3>
                            </div>
                            <div class="box-content nopadding">
                            <form method="post" id="frm_change_pass" onSubmit="return validate_reg();" class='form-horizontal form-bordered form-validate'>
                                <div class="control-group">
                                    <label for="textarea" class="control-label">Old Password</label>
                                    <div class="controls fullname">
                                        <input type="password" name="opass" id="opass"  class="input-xlarge" placeholder="Old Password" data-rule-required="true">
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label for="textarea" class="control-label">New Password</label>
                                    <div class="controls fullname">
                                        <input type="password" name="npass" id="npass"  class="input-xlarge" placeholder="New Password" data-rule-required="true">
                                    	<br><em>(Password should be Alphanumeric &amp; 6-8 characters)</em>
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label for="textarea" class="control-label">Confirm New Password</label>
                                    <div class="controls fullname">
                                        <input type="password" name="cnpass" id="cnpass"  class="input-xlarge" placeholder="New Password" data-rule-required="true">
                                    </div>
                                </div>
                                
                                 <div class="form-actions">
                                        <button type="submit" name="submit" class="btn-success">Submit</button>
                                        <button type="button" class="btn" onClick="window.history.back()">Cancel</button>
                                </div> <!-- Save and cancel -->
                            </form>
                            </div>
                        </div> <!-- main actual content-->
                    </div>
                </div>
            </div>
        </div>   
    </div> 
<script>
function validate_reg()
{
	var err = '';
	var opass = $('#opass').val();
	var npass = $('#npass').val();
	var cnpass = $('#cnpass').val();

	if (opass == "" || opass == null)
	{
		err += "Enter old password\n";
	}

	if (npass == "" || npass == null)
	{
		err += "Enter new password\n";
	}

	if (cnpass == "" || cnpass == null)
	{
		err += "Enter confirm new password\n";
	}
	else if (npass != cnpass)
	{
		err += "New password and Confirm new password mismatch\n";
	}

	if (err.length > 0) 
	{
		//alert(err);		
		return false;
	}
	else
	{
		$.post(location.href,{opass:opass,npass:npass,cnpass:cnpass,jsubmit:"Change_Pass"},function(data)
		{
				if(data.length > 0)
				{
					alert(data);
					return false;
				}
				else
				{
					alert("Your password has been successfully changed. We advise you not to share your password with anyone and also encourage you to change your password regularly.\n\nClick OK to login with your new password.");
					var url = "logout.php";
					$(location).attr('href',url);
				}
		});
		return false; 
	}
}
</script>
</body>
</html>