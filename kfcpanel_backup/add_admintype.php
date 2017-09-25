<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));
// This is for dynamic title, bread crum, etc.
if(isset($_GET['pag']))
{
	$title = $_GET['pag'];
}
$path_parts   = pathinfo(__FILE__);
$filename 	  = $path_parts['filename'].".php";
$sql_feature = "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature = mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  = mysqli_fetch_row($result_feature);
$feature_name = $row_feature[1];
$home_name    = "Home";
$home_url 	  = "view_dashboard.php?pag=Dashboard";
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "addnewassigntype" || isset($_POST['assign_admin_type']))
{
	$error_msg = "";
	if(isset($_POST['atype']) && $_POST['atype'] != "" && isset($_POST['featurestatus']) && $_POST['featurestatus'] != "" && isset($_POST['smsstatus']) && $_POST['smsstatus'] != "")
	{
		$at_name 			= mysqli_real_escape_string($db_con,$_POST['atype']);
		$at_featurestatus 	= mysqli_real_escape_string($db_con,$_POST['featurestatus']);
		$at_smsstatus 		= mysqli_real_escape_string($db_con,$_POST['smsstatus']);
		$at_userstatus 		= mysqli_real_escape_string($db_con,$_POST['userstatus']);		
	}
	else
	{ $error_msg .= "Please enter User Level\n"; }
	
	if($error_msg == "")
	{
		$sql_admin_type 		= "SELECT `at_name` FROM `tbl_admin_type` WHERE `at_name`='".$at_name."'";
		$result_admin_type 		= mysqli_query($db_con,$sql_admin_type) or die(mysqli_error($db_con));
		$tot_at					= mysqli_num_rows($result_admin_type);
		if($tot_at > 0)
		{
			print "User Level name is already exist.\n";
			exit(0); 
		} 
		else 
		{
			$sql_insert 	= " INSERT INTO `tbl_admin_type`(`at_name`,`at_featurestatus`,`at_smsstatus`,`createddt`,`createdby`,`status`) ";
			$sql_insert   .= " VALUES ('".$at_name."','".$at_featurestatus."','".$at_smsstatus."', '".$datetime."', '".$_SESSION['panel_user']['fullname']."','".$at_userstatus."') ";
			$result_insert = mysqli_query($db_con,$sql_insert) or die(mysqli_error($db_con));
		}
		exit(0);
	} 
	else 
	{
		print $error_msg;
		exit(0);
	}
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
<!-- mysqlcoding --> 
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-bordered box-color">
                                <div class="box-title">
                                    <h3><i class="icon-th-list"></i><?php echo $feature_name; ?></h3>
                                </div>
                                <div class="box-content nopadding">
                                    <form enctype="multipart/form-data"  id="frm_add_new_admin_type" name="frm_add_new_admin_type" onSubmit="return add_assign_type();" method="POST" class='form-horizontal form-bordered form-validate'>				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">User Level name</label>
                                            <div class="controls">
                                                <input type="text" name="assign_admin_type" id="assign_admin_type"  class="input-xlarge" placeholder="User Level name" data-rule-required="true" >
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Feature status</label>
                                            <div class="controls">
                                                <input type="checkbox" id="featurestatus" name="featurestatus" class="css-checkbox">
                                                <label for="featurestatus" class="css-label"></label>
                                                <label name = "featuretext" ></label>                                                
                                            </div>
                                        </div>                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">SMS Status</label>
                                            <div class="controls">
                                                <input type="checkbox" id="smsstatus" name="smsstatus" class="css-checkbox">
                                                <label for="smsstatus" class="css-label"></label>   
                                                <label name = "smstext" ></label>
                                            </div>
                                        </div>
										<div class="control-group">
                                            <label for="radio" class="control-label">User Status</label>
                                            <div class="controls">
                                                <input type="radio" id="userstatus" name="userstatus" value="1" class="css-radio" data-rule-required="true">Active
                                                <input type="radio" id="userstatus" name="userstatus" value="0" class="css-radio" data-rule-required="true">Inactive
                                                <label for="radiotest" class="css-label"></label>   
                                                <label name = "radiotest" ></label>
                                            </div>
                                        </div>                                        
                                         <div class="form-actions">
                                            <button type="submit" name="reg_submit" class="btn-success" >Create</button>
                                            <button type="button" class="btn" onClick="window.history.back()">Cancel</button>
                                        </div> <!-- Save and cancel -->                                                                        
                                    </form>	
                                </div> <!--Main content is here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- main actual content-->                                
        </div>
 <?php getloder();?>
 <script> 	
//This is for creating sms package
	$('#frm_add_new_admin_type').on('submit', function(e) {
		e.preventDefault();
		if ($('#frm_add_new_admin_type').valid())
		{
			loading_show();
			if($('#featurestatus').prop('checked')) 
			{
				var featurestatus = 1;
			} 
			else 
			{
				var featurestatus = 0;
			}
			
			if($('#smsstatus').prop('checked')) 
			{
				var smsstatus = 1;
			} 
			else 
			{
				var smsstatus = 0;
			}
			if($('#userstatus').prop('checked')) 
			{
				var userstatus = 1;
			} 
			else 
			{
				var userstatus = 0;
			}			
		
			var atype = $.trim($('input[name="assign_admin_type"]').val());
			$('input[name="reg_submit"]').attr('disabled', 'true');
			$.post(location.href,{atype:atype,featurestatus:featurestatus,smsstatus:smsstatus,userstatus:userstatus,jsubmit:'addnewassigntype'},function(data){
				if (data.length > 0) 
				{
					loading_hide();
					$('input[name="reg_submit"]').removeAttr('disabled');
					alert(data);
				} 
				else 
				{
					loading_hide();
					alert("You have sucessfully created User Level.");
					loading_show();
					window.location.replace('view_admintype.php?pag=<?php echo $title; ?>','_self');
				}
			});
		}
		
	});  
    </script>
</body>
</html>