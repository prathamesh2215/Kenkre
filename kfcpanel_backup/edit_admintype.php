<?php
include("include/routines.php");
//checkuser();
//chkRights(basename($_SERVER['PHP_SELF']));
$title  		= "Admin Type";
$filename 		= "edit_featuretype.php";
$home_url 		= "view_dashboard.php?pag=Dashboard";
$home_name 		= "Home";
$feature_name 	= "Edit Admin Type";
if(isset($_GET['at_id']) && $_GET['at_id'])
{
	$at_id				= mysqli_real_escape_string($db_con,$_GET['at_id']);
	$at_id 				= mysqli_real_escape_string($db_con,$_GET['at_id']);
	$sql_admin_type 	= "select * from `tbl_admin_type` where `at_id`='".$at_id."'";
	$result_admin_type 	= mysqli_query($db_con,$sql_admin_type) or die(mysqli_error($db_con));
	$row_admin_type  	= mysqli_fetch_array($result_admin_type);
}
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "editassigntype" || isset($_POST['assign_admin_type']))
{
	$error_msg = "";
	if(isset($_POST['atype']) && $_POST['atype'] != "" && isset($_POST['featurestatus']) && $_POST['featurestatus'] != "" && isset($_POST['smsstatus']) && $_POST['smsstatus'] != "")
	{
		$at_name 			= mysqli_real_escape_string($db_con,$_POST['atype']);
		$at_featurestatus 	= mysqli_real_escape_string($db_con,$_POST['featurestatus']);
		$at_smsstatus 		= mysqli_real_escape_string($db_con,$_POST['smsstatus']);
	}
	else
	{ 
		$error_msg .= "Please enter User Level\n"; 
	}	
	if($error_msg == "")
	{
		$sql_at_name 		= "SELECT `at_name` FROM `tbl_admin_type` WHERE `at_name`='".$at_name."' and `at_id` != '".$at_id."'";
		$result_at_name 	= mysqli_query($db_con,$sql_at_name) or die(mysqli_error($db_con));
		$num_rows_at_name	= mysqli_num_rows($result_at_name);
		if($num_rows_at_name > 0)
		{
			print "User Level name is already exist.\n";
			exit(0); 
		} 
		else 
		{
			$sql_update_admin_type 		= " UPDATE `tbl_admin_type` SET `at_name`='".$at_name."',`at_featurestatus`='".$at_featurestatus."',";
			$sql_update_admin_type 		.= " `at_smsstatus`='".$at_smsstatus."',`modifieddt`= '".$datetime."',`modifiedby`='".$_SESSION['mystudyc_admin']['fullname']."' where at_id = '".$at_id."'";
			$result_update_admin_type 	= mysqli_query($db_con,$sql_update_admin_type) or die(mysqli_error($db_con));
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
                                    <form enctype="multipart/form-data"  id="frm_edit_admin_type" name="frm_edit_admin_type" method="POST" class='form-horizontal form-bordered form-validate'>				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">User Level name</label>
                                            <div class="controls">
                                                <input type="text" name="assign_admin_type" id="assign_admin_type"  class="input-xlarge" placeholder="User Level name" data-rule-required="true" value="<?php echo $row_admin_type['at_name']; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Feature status</label>
                                            <div class="controls">
                                                <input type="checkbox" id="featurestatus" name="featurestatus" class="css-checkbox"   <?php if($row_admin_type['at_featurestatus']=='1'){echo "checked";} ?> >
                                                <label for="featurestatus" class="css-label"></label>
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">SMS Status</label>
                                            <div class="controls">
                                                <input type="checkbox" id="smsstatus" name="smsstatus" class="css-checkbox" <?php if($row_admin_type['at_smsstatus']=='1'){echo "checked";} ?> >
                                                <label for="smsstatus" class="css-label"></label>
                                            </div>
                                        </div>
                                         <div class="form-actions">
                                                <button type="submit" name="reg_submit" class="btn-success" >Update</button>
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
	$('#frm_edit_admin_type').on('submit', function(e) {
		e.preventDefault();
		if ($('#frm_edit_admin_type').valid())
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
			var atype = $.trim($('input[name="assign_admin_type"]').val());
			$('input[name="reg_submit"]').attr('disabled', 'true');
			$.post(location.href,{atype:atype,featurestatus:featurestatus,smsstatus:smsstatus,jsubmit:'editassigntype'},function(data){
				if (data.length > 0) 
				{
					loading_hide();
					$('input[name="reg_submit"]').removeAttr('disabled');
					alert(data);
				} 
				else 
				{
					loading_hide();
					alert("You have sucessfully updated User Level.");
					loading_show();
					window.location.replace('view_admintype.php?pag=<?php echo $title; ?>','_self');
				}
			});
		}		
	});
</script>               
</body>
</html>