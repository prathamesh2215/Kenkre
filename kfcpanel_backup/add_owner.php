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
$logged_id	  = $_SESSION['panel_user']['id'];
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "addnewclientowner" || isset($_POST['ownername']))
{
	$clientname = mysqli_real_escape_string($db_con,$_POST['ownername']);
	$shortcode  = mysqli_real_escape_string($db_con,$_POST['shortcode']);
	$promocode  = mysqli_real_escape_string($db_con,$_POST['promocode']);
	
	$sql_view_client = "SELECT `clientname` FROM `tbl_users_owner` WHERE `clientname`='".$clientname."'";
	$result_view_client = mysqli_query($db_con,$sql_view_client) or die(mysqli_error($db_con));
	$num_rows_view_client = mysqli_num_rows($result_view_client);
	if($num_rows_view_client > 0)
	{
		print "Client owner name is already exist.\n";
		exit(0);
	} 
	else 
	{
		$sql_insert_client = " INSERT INTO `tbl_users_owner`(`clientname`,`shortcode`,`promocode`,`created`,`created_by`,`status`) VALUES ";
		$sql_insert_client .= " ('".$clientname."','".$shortcode."','".$promocode."','".$logged_id."','".$datetime."','1') ";
		$result_inser_client = mysqli_query($db_con,$sql_insert_client) or die(mysqli_error($db_con));
		if($result_inser_client)
		{
			echo "Success";
		}
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
<!-- mysqlcoding --> 
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-bordered box-color">
                                <div class="box-title">
                                    <h3><i class="icon-th-list"></i><?php echo $feature_name; ?></h3>
                                </div>
                                <div class="box-content nopadding">
                                    <form enctype="multipart/form-data"  id="frm_add_new_owner" name="frm_add_new_owner" onSubmit="return add_assign_type();" method="POST" class='form-horizontal form-bordered form-validate'>				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Client owner name</label>
                                            <div class="controls">
                                                <input type="text" name="ownername" id="ownername"  class="input-xlarge" placeholder="Client owner name" data-rule-required="true" >
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Short code</label>
                                            <div class="controls">
                                                <input type="text" name="shortcode" id="shortcode"  class="input-xlarge" placeholder="Short code" data-rule-required="true" >
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Promo code</label>
                                            <div class="controls">
                                                <input type="text" name="promocode" id="promocode"  class="input-xlarge" placeholder="Promo code" data-rule-required="true" >
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
 <?php getloder();?>                                 
        </div>
<script>
//This is for creating sms package
	$('#frm_add_new_owner').on('submit', function(e) {
		e.preventDefault();
		if ($('#frm_add_new_owner').valid())
		{
			loading_show();
			var ownername = $.trim($('input[name="ownername"]').val());
			var shortcode = $.trim($('input[name="shortcode"]').val());
			var promocode = $.trim($('input[name="promocode"]').val());
			$('input[name="reg_submit"]').attr('disabled', 'true');
			$.post(location.href,{ownername:ownername,shortcode:shortcode,promocode:promocode,jsubmit:'addnewclientowner'},function(data){
				if (data != "Success") 
				{
					loading_hide();
					$('input[name="reg_submit"]').removeAttr('disabled');
					alert(data);
				} 
				else if(data == "Success") 
				{
					loading_hide();
					alert(data);
					alert("You have sucessfully created owner.");
					loading_show();
					window.location.replace('view_owner.php?pag=<?php echo $title; ?>','_self');
				}
			});
		}
		
	});

</script>
</body>
</html>