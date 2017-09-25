<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
if(isset($_GET['pag']))
{
	$title = $_GET['pag'];
}
$path_parts   		= pathinfo(__FILE__);
$filename 	  		= $path_parts['filename'].".php";
$sql_feature 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature 	= mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  		= mysqli_fetch_row($result_feature);
$feature_name 		= $row_feature[1];
$home_name    		= "Home";
$home_url 	  		= "view_dashboard.php?pag=Dashboard";
$utype				= $_SESSION['panel_user']['utype'];
$tbl_users_owner 	= $_SESSION['panel_user']['tbl_users_owner'];
//------------------------------------------------------------------
// Start : This is for creating New SMS Package
//------------------------------------------------------------------
if(isset($_POST['jsubmit']) && $_POST['jsubmit']== 'create_package')
{	
	$package_name 	= mysqli_real_escape_string($db_con,$_POST['package_name']);
	$no_of_sms 		= mysqli_real_escape_string($db_con,$_POST['num_of_sms']);
	$prize 			= mysqli_real_escape_string($db_con,$_POST['package_prize']);
	$validity 		= mysqli_real_escape_string($db_con,$_POST['validity']);
	$sms_per_rupee 	= $prize / $no_of_sms;
	
	$err_msg = "";
	
	if($package_name == "" || $no_of_sms =="" || $prize == "" || $validity =="")
	{
		$err_msg .= "Please fill all fileds\n";
	}
	if(trim($err_msg) == "")
	{
		$sql_check_existing_package 		= "select * from tbl_sms_package where sp_num_sms ='".$no_of_sms."'";
		$result_check_existing_package 		= mysqli_query($db_con,$sql_check_existing_package) or die(mysqli_error($db_con));
		$row_check_existing_package 		= mysqli_fetch_array($result_check_existing_package);
		$num_rows_check_existing_package	= mysqli_num_rows($result_check_existing_package);
		
		if($num_rows_check_existing_package == 0)
		{
			$sql_insert_package_detail 		= " Insert into tbl_sms_package(`sp_package_name`,`sp_num_sms`,`sp_mrp`,`sp_validity`,`sp_sms_per_rupee`,`createdby`,";
			$sql_insert_package_detail 		.= " `createddt`) values('".$package_name."','".$no_of_sms."','".$prize."','".$validity."','".$sms_per_rupee."','".$_SESSION['panel_user']['fullname']."','".$datetime."')";
			$result_insert_package_detail	= mysqli_query($db_con,$sql_insert_package_detail) or die(mysqli_error($db_con));
			if($result_insert_package_detail)
			{
				echo "Success";	
			}
		}
		else
		{
			print "Package already exist";
		}
	}
	else
	{
		print $err_msg;	
	}
	exit(0);
}
//------------------------------------------------------------------
// End : This is for creating New SMS Package
//------------------------------------------------------------------
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
	?>          
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-bordered box-color">
                                <div class="box-title">
	                                <h3 style="margin-right:50px"><i class="icon-th-list"></i><?php echo $feature_name; ?></h3>
                                </div>
                                <div class="box-content nopadding">                                    
                                    <form enctype="multipart/form-data"  id="frm_add_new_smspkg" name="frm_add_new_smspkg" method="POST" class='form-horizontal form-bordered form-validate' >				                                    
                                    <div class="control-group">
                                        <label for="textarea" class="control-label">Package Name</label>
                                        <div class="controls">
                                            <input type="text" name="package_name" id="package_name"  class="input-xlarge" placeholder="Package name" data-rule-required="true">
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label for="textarea" class="control-label">Number of SMS</label>
                                        <div class="controls">
                                            <input type="text" name="no_of_sms" id="no_of_sms"  class="input-mini" placeholder="" data-rule-required="true"  onKeyUp="calc_sms_per_rupee();" onKeyPress="return isNumberKey(event)" maxlength="5" >
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label for="textarea" class="control-label">Package Price</label>
                                        <div class="controls">
                                            <input type="text" name="price" id="price"  class="input-mini numbersOnly" placeholder="00.00" data-rule-required="true" onBlur="calc_sms_per_rupee();" onKeyUp="calc_sms_per_rupee();" maxlength="7">
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label for="textarea" class="control-label">Package Validity</label>
                                        <div class="controls">
                                            <input type="text" name="validity" id="validity"  class="input-mini" placeholder="" data-rule-required="true" onKeyPress="return isNumberKey(event)" maxlength="3" > Days
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label for="textarea" class="control-label">SMS Per Rupee</label>
                                        <div class="controls">
                                            <input type="text" name="sms_per_rupee" id="sms_per_rupee"  class="input-small" placeholder="" disabled readonly>
                                        </div>
                                    </div>
                                    
                                     <div class="form-actions">
                                            <button type="submit" name="reg_submit" value="Create" class="btn-success" >Create</button>
                                            <button type="button" class="btn" onClick="window.history.back()">Cancel</button>
                                    </div> <!-- Save and cancel -->
                                </form>
                                </div>
                            </div> <!-- main actual content-->
                        </div>
                    </div>
                 </div>   
            </div> <!--right side main content panel-->
        </div> <!--main content area-->
	</div>
    <?php getloder();?>
</body>
<script type="text/javascript">
//This is for calculating sms per rupee
function calc_sms_per_rupee() 
{
	var num_of_sms2 		= $('input[name="no_of_sms"]').val();
	var prize_of_package 	= $('input[name="price"]').val();
	var sms_per_rupee 		= prize_of_package / num_of_sms2;
	$('input[name="sms_per_rupee"]').val(sms_per_rupee);
}
//This is for disable char key press
function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	{
		return false;
	}
	return true;
} 
$(document).ready(function(e) {
$("#selectall").click(function(){
	$(".case").attr("checked",this.checked);
});

$(".case").click(function(){
	if($(".case").length==$(".case:checked").length){
		$("#selectall").attr("checked","checked");
	}
	else{
		$("#selectall").removeAttr("checked");
	}
});

});
//This is for creating sms package
$('#frm_add_new_smspkg').on('submit', function(e) {
	
	e.preventDefault();
	if ($('#frm_add_new_smspkg').valid())
	{
		var package_name 	= $('input[name="package_name"]').val();
		var num_of_sms 		= $('input[name="no_of_sms"]').val();
		var package_prize 	= $('input[name="price"]').val();
		var validity 		= $('input[name="validity"]').val();
		$('input[name="reg_submit"]').attr('disabled', 'true');
		$.post(location.href,{package_name:package_name,num_of_sms:num_of_sms,package_prize:package_prize,validity:validity,jsubmit:'create_package'},function(data){
			if(data != "Success") 
			{
				$('input[name="reg_submit"]').removeAttr('disabled');
				alert(data);
			} 
			else if(data == "Success") 
			{
				alert("You have sucessfully created SMS package.");
				window.open('view_sms_package.php?pag=<?php echo $title; ?>','_self');
			}
		});
	}
	
});
//This is for price only
jQuery('.numbersOnly').keyup(function (e) { 
if(($(this).val().split(".")[0]).indexOf("00")>-1){
	//$(this).val($(this).val().replace("00","0"));
} else {
	$(this).val($(this).val().replace(/[^0-9\.]/g,''));
}

if($(this).val().split(".")[2] != null || ($(this).val().split(".")[2]).length ){
	$(this).val($(this).val().substring(0, $(this).val().lastIndexOf(".")));
}    
});
</script>
</html>