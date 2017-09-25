<?php
include("include/routines.php");
checkuser();

$title  		= "Owner";
$filename 		= "edit_owner.php";
$home_url 		= "view_dashboard.php?pag=Dashboard";
$home_name 		= "Home";
$feature_name 	= "Update Owner";

if(isset($_GET['owner_id']) && $_GET['owner_id'])
{
	$id 	= mysqli_real_escape_string($db_con,$_GET['owner_id']);
	$sql 	= "select * from `tbl_users_owner` where `id`='".$id."'";
	$result = mysqli_query($db_con,$sql);
	$row 	= mysqli_fetch_array($result);
}
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "editowner" || isset($_POST['ownername']))
{
	$clientname = $_POST['ownername'];	
	$shortcode  = $_POST['shortcode'];
	$promocode  = $_POST['promocode'];
	
	$tot_at = mysql_num_rows(mysql_query("SELECT `clientname` FROM `tbl_users_owner` WHERE `clientname`='".$clientname."' and `id` != '".$id."'"));
	echo mysql_error();
	if($tot_at > 0)
	{
		print "Owner name is already exist.\n";
		exit(0); 
	} 
	else 
	{
		mysql_query("UPDATE `tbl_users_owner` SET `clientname`='".$clientname."',`shortcode`='".$shortcode."',`promocode`='".$promocode."' where id = '".$id."'");
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
            <div id="main" style="margin-left:0px !important">
                <div class="container-fluid">
                
	<?php 
	/* this function used to add navigation menu to the page*/ 
	breadcrumbs($home_url,$home_name,$title,$filename,$feature_name); 
	/* this function used to add navigation menu to the page*/ 
	?><!-- mysqlcoding --> 
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-bordered box-color">
                                <div class="box-title">
                                    <h3><i class="icon-th-list"></i><?php echo $feature_name; ?></h3>
                                </div>
                                <div class="box-content nopadding">
                                    <form enctype="multipart/form-data"  id="frm_update_owner" name="frm_update_owner" onSubmit="return add_assign_type();" method="POST" class='form-horizontal form-bordered form-validate'>				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Client owner name</label>
                                            <div class="controls">
                                                <input type="text" name="ownername" id="ownername"  class="input-xlarge" placeholder="Client owner name" data-rule-required="true" value="<?php echo $row['clientname']; ?>" >
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Short code</label>
                                            <div class="controls">
                                                <input type="text" name="shortcode" id="shortcode"  class="input-xlarge" placeholder="Short code" data-rule-required="true" value="<?php echo $row['shortcode']; ?>" >
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Promo code</label>
                                            <div class="controls">
                                                <input type="text" name="promocode" id="promocode"  class="input-xlarge" placeholder="Promo code" data-rule-required="true" value="<?php echo $row['promocode']; ?>">
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
             <?php getloder();?>                       
        </div>
    
<script language="javascript" type="text/javascript">
//This is for creating sms package
	$('#frm_update_owner').on('submit', function(e) {
		e.preventDefault();
		if ($('#frm_update_owner').valid())
		{
			loading_show();
			var ownername = $.trim($('input[name="ownername"]').val());
			var shortcode = $.trim($('input[name="shortcode"]').val());
			var promocode = $.trim($('input[name="promocode"]').val());
			$('input[name="reg_submit"]').attr('disabled', 'true');
			$.post(location.href,{ownername:ownername,shortcode:shortcode,promocode:promocode,jsubmit:'editowner'},function(data){
				if (data.length > 0) 
				{
					loading_hide();
					$('input[name="reg_submit"]').removeAttr('disabled');
					alert(data);
				} 
				else 
				{
					alert("You have sucessfully updated Owner.");
					loading_show();
					window.location.replace('view_owner.php?pag=<?php echo $title; ?>','_self');
					loading_hide();
				}
			});
		}
		
	});
</script>
                    
</body>
</html>