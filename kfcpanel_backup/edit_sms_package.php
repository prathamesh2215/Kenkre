<?php
include("include/routines.php");
checkuser();
$title  		= "SMS Package";
$filename 		= "edit_sms_package.php";
$home_url 		= "view_dashboard.php?pag=Dashboard";
$home_name 		= "Home";
$feature_name 	= "Update SMS Package";

//This is for view existing deatils of SMS Package
if(isset($_GET['sp_id']))
{
	$pk_id 						= mysqli_real_escape_string($db_con,$_GET['sp_id']);
	$sql_sms_package_details 	= "select * from tbl_sms_package where sp_id='".$pk_id."'";
	$result_sms_package_details	= mysqli_query($db_con,$sql_sms_package_details) or die(mysqli_error($db_con));
	$row_sms_package_details	= mysqli_fetch_array($result_sms_package_details);
	$pk_name 					= $row_sms_package_details['sp_package_name'];
	$pk_num_sms 				= $row_sms_package_details['sp_num_sms'];
	$pk_mrp 					= $row_sms_package_details['sp_mrp'];
	$pk_validity 				= $row_sms_package_details['sp_validity'];
}
//------------------------------------------------------------------
// Start : This is for updating SMS Package details
//------------------------------------------------------------------
if(isset($_POST['jsubmit']) && $_POST['jsubmit']== 'update_package')
{	
	$package_name 	= mysqli_real_escape_string($db_con,$_POST['package_name']);
	$no_of_sms 		= mysqli_real_escape_string($db_con,$_POST['num_of_sms']);
	$prize 			= mysqli_real_escape_string($db_con,$_POST['package_prize']);
	$validity		= mysqli_real_escape_string($db_con,$_POST['validity']);
	$sms_per_rupee 	= $prize / $no_of_sms;
	
	$sql_check_existing_package 	= "select * from tbl_sms_package where sp_num_sms ='".$no_of_sms."' and sp_id != '".$pk_id."'";
	$result_check_existing_package	= mysqli_query($db_con,$sql_check_existing_package) or die(mysqli_error($db_con));
	$row_check_existing_package 	= mysqli_fetch_array($result_check_existing_package);
	$num_rows_check_existing_package= mysqli_num_rows($result_check_existing_package);
	if($num_rows_check_existing_package == 0)
	{
		$sql_insert_package_detail 		= " update tbl_sms_package set `sp_package_name`='".$package_name."',`sp_num_sms`='".$no_of_sms."',`sp_mrp`='".$prize."',`sp_validity`='".$validity."',";
		$sql_insert_package_detail 		.= " `sp_sms_per_rupee`='".$sms_per_rupee."',`modifiedby`='".$_SESSION['panel_user']['fullname']."',`modifieddt`='".$datetime."' where sp_id='".$pk_id."'";
		$result_insert_package_detail 	= mysqli_query($db_con,$sql_insert_package_detail) or die(mysqli_error($db_con));
		if($result_insert_package_detail)
		{
			echo "Success";	
		}
		else
		{
			echo "Record Not Inserted";	
		}
		
	}
	else
	{
		print "Package already exist";
	}
	exit(0);
}
//------------------------------------------------------------------
// End : This is for updating SMS Package details
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
	?><!-- mysqlcoding --> 
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-bordered box-color">
                                <div class="box-title">
                                    <h3><i class="icon-th-list"></i> Update SMS Package</h3>
                                </div>
                                <div class="box-content nopadding">
                                    <form enctype="multipart/form-data"  id="frm_edit_new_smspkg" name="frm_edit_new_smspkg" method="POST" class='form-horizontal form-bordered form-validate' >				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Package Name</label>
                                            <div class="controls">
                                                <input type="text" name="package_name" id="package_name"  class="input-xlarge" placeholder="Package name" data-rule-required="true" value="<?php print $pk_name; ?>" >
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Number of SMS</label>
                                            <div class="controls">
                                                <input type="text" name="no_of_sms" id="no_of_sms"  class="input-mini" placeholder="" data-rule-required="true" onKeyPress="return isNumberKey(event)"  onKeyUp="calc_sms_per_rupee();" maxlength="5" value="<?php print $pk_num_sms; ?>" >
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Package Price</label>
                                            <div class="controls">
                                                <input type="text" name="price" id="price"  class="input-mini numbersOnly" placeholder="00.00" data-rule-required="true" onBlur="calc_sms_per_rupee();"  onKeyUp="calc_sms_per_rupee();" maxlength="7" value="<?php print $pk_mrp; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Package Validity</label>
                                            <div class="controls">
                                                <input type="text" name="validity" id="validity"  class="input-mini" placeholder="" data-rule-required="true" onKeyPress="return isNumberKey(event)" maxlength="3" value="<?php print $pk_validity; ?>"> Days
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">SMS Per Rupee</label>
                                            <div class="controls">
                                                <input type="text" name="sms_per_rupee" id="sms_per_rupee"  class="input-small" placeholder="" disabled readonly>
                                            </div>
                                        </div>
                                        
                                         <div class="form-actions">
                                                <button type="submit" name="reg_submit" class="btn-success" >Update</button>
                                                <button type="button" class="btn" onClick="window.history.back()">Cancel</button>
                                        </div> <!-- Save and cancel -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- main actual content-->
             <?php getloder();?>                     
        </div>                                
        <script type="text/javascript">
            //This is for creating sms package
                $('#frm_edit_new_smspkg').on('submit', function(e) {
                    e.preventDefault();
                    if ($('#frm_edit_new_smspkg').valid())
                    {
						loading_show();
                        var package_name 	= $('input[name="package_name"]').val();
                        var num_of_sms 		= $('input[name="no_of_sms"]').val();
                        var package_prize 	= $('input[name="price"]').val();
                        var validity 		= $('input[name="validity"]').val();
                        
                        $('input[name="reg_submit"]').attr('disabled', 'true');
                        $.post(location.href,{package_name:package_name,num_of_sms:num_of_sms,package_prize:package_prize,validity:validity,jsubmit:'update_package'},function(data){
                            //$('input[name="reg_submit"]').removeAttr('disabled');
                                if (data != "Success") 
                                {
									loading_hide();
                                    $('input[name="reg_submit"]').removeAttr('disabled');
                                    alert(data);
                                } 
                                else if (data == "Success")  
                                {
									loading_hide();
                                    alert("You have sucessfully updated package.");
									loading_show();
                                    window.open('view_sms_package.php?pag=<?php echo $title; ?>','_self');
                                }
                        });
                    }
                    
                });
                
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
            $(document).ready(function(e) {
             
                var num_of_sms2 		= $('input[name="no_of_sms"]').val();
                var prize_of_package 	= $('input[name="price"]').val();
                var sms_per_rupee 		= prize_of_package / num_of_sms2;
                
                $('input[name="sms_per_rupee"]').val(sms_per_rupee);
        
            });
            </script>
    </body>
</html>