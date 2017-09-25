<?php
include("include/routines.php");
//checkuser();
//chkRights(basename($_SERVER['PHP_SELF']));
$add = checkFunctionalityRight("view_user.php",0);
$add = 1;
if($add)
{
}
else
{
	?>
    	<script type="text/javascript">
			window.history.back();
		</script>
    <?php
}
// This is for dynamic title, bread crum, etc.
if(isset($_GET['pag']))
{
	$title = $_GET['pag'];
}
else
{
	$title	= "Add User";
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


//-----------------------------------------------------
// Fetching admin types 
//-----------------------------------------------------
$ar_type_of_admin 	= array();
$sql_admin_type		= "select * from tbl_admin_type";
if($utype != 1)	//Only super-admin can view all user
{
	$sql_admin_type	.= " where at_id > '".$utype."'";	
}
$result_admin_type 	= mysqli_query($db_con,$sql_admin_type) or die(mysqli_error($db_con));
while($row_admin_type = mysqli_fetch_array($result_admin_type))
{
	$ar_type_of_admin[$row_admin_type['at_id']] = $row_admin_type;
}
//-----------------------------------------------------
// Fetching all user owner 
//-----------------------------------------------------
$ar_owner_name 	= array();
$sql_owner 		= "select * from tbl_users_owner where status != 'deactive'";
$result_owner 	= mysqli_query($db_con,$sql_owner) or die(mysqli_error($db_con));
while($row_owner= mysqli_fetch_array($result_owner))
{
	$ar_owner_name[$row_owner['clientname']] = $row_owner;
}
//-----------------------------------------------------
// Fetching all feature types ------
//-----------------------------------------------------
$ar_features_type = array();
$sql_feature_type = "select * from tbl_admin_features where af_status = '1' and af_parent_type = 'Parent' order by af_menu_order";
$result_feature_type = mysqli_query($db_con,$sql_feature_type) or die(mysqli_error($db_con));
while($row_features_type = mysqli_fetch_array($result_feature_type))
{
	$ar_features_type[] = $row_features_type;
}
//-----------------------------------------------------
// Fetching all sms packages
//-----------------------------------------------------
$ar_sms_package  		= array();
$sql_sms_package 		= "select * from tbl_sms_package";
$result_sms_package 	= mysqli_query($db_con,$sql_sms_package) or die(mysqli_error($db_con));
while($row_sms_package 	= mysqli_fetch_array($result_sms_package))
{
	$ar_sms_package[] = $row_sms_package;
}

//------------------------------------------------------------------------------------------------
// Start : This is for hide/show sms and feature status according to type of user
//------------------------------------------------------------------------------------------------
if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'categorywise' && isset($_POST['user_type'])) 
{
	$at_id 				= mysqli_real_escape_string($db_con,$_POST['user_type']);
	$sql_admin_type 	= "select * from tbl_admin_type where at_id='".$at_id."'";
	$result_admin_type  = mysqli_query($db_con,$sql_admin_type) or die($db_con);
	$row_admin_type		= mysqli_fetch_array($result_admin_type);
	
	if($row_admin_type['at_smsstatus']=='1')//If sms status of user type is 1 then SMS/Email facilities available
	{
		?>
        <script>
			$("#divsmspackage").hide();
			$("#divsmspackagesettingname").hide();
			$("#divsmspackagesettingvalidity").hide();
			$("#divsmspackagedaterange").hide();
			$('#start_date').val("");
			$('#end_date').val("");
		</script>

        <div class="box-title">
            <h3><i class="icon-th-list"></i> Facilities</h3>
        </div>

        <table width="100%" >
          <tr>
            <td width="49%">
                <div class="control-group">
                    <label for="textarea" class="control-label">SMS</label>
                    <div class="controls">
                        <input type="checkbox" id="smsstatus" name="smsstatus" value="1" class="css-checkbox"  onChange="show_sms_div();">
                        <label for="smsstatus" class="css-label"></label></em>
                    </div>
                </div>  <!-- SMS checkbox -->
                <div class="control-group">
                    <label for="textarea" class="control-label">Email</label>
                    <div class="controls">
                        <input type="checkbox" id="emailstatus" name="emailstatus" value="1" class="css-checkbox"  onChange="show_email();">
                        <label for="emailstatus" class="css-label"></label></em>
                    </div>
                </div>  <!-- Email checkbox -->
            </td>
            <td width="49%">
            	<div id="divsmspackage" style="width:100%;vertical-align:top;">
                    <table width="100%">
                      <tr>
                        <td width="49%">
                            <table width="49%" id="maintable" bgcolor="#F22871" >
                                <tr>
                                    <td bgcolor="#3EBBC8" width="100%" style="color:#FFF">SMS Package</td>
                                </tr>
                                <?php foreach($ar_sms_package as $vals) { ?>
                                <tr id="<?php echo $vals['sp_id'];?>"  onclick="packageselected('<?php echo $vals['sp_package_name'].",".$vals['sp_validity'];?>',this.id);">
                                    <td bgcolor="#FFFFFF"><?php echo $vals['sp_package_name'];?></td>
                                </tr>
                                <?php } ?>
                                <input type="hidden" name="pkgid" id="pkgid" value="">
                            </table>
                        </td>
                        <td width="49%"><div id="divsmspackagesettingname"></div><div id="divsmspackagesettingvalidity"></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" >
                            <table width="98%">
                              <tr>
                                <td width="64%"><b>From Date : </b><input type="text" name="start_date" id="start_date" class="form-control datepicker input-small"  data-rule-required="true"></td>
                                <td width="36%"><input type="text" name="end_date" id="end_date" size="10" class="form-control datepicker input-small"   data-rule-required="true" hidden disabled="disabled"><!--<div id="end_date" style="float:left"></div>--></td>
                              </tr>
                            </table>
                        </td>
                      </tr>
                    </table>  <!-- SMS Package -->
                </div>
                <div id="divsmspackagedaterange"></div>
            </td>
          </tr>
        </table>

		<?php
	}
	else
	{
		echo "";
	}
	
	if($row_admin_type['at_featurestatus']=='1')//If feature status is 1 then rights will be visible
	{
		?>
        <div class="box-title">
            <h3><i class="icon-th-list"></i> Specify Rights</h3>
        </div>
        <?php
	    $ar_af_at_id = array();
		foreach($ar_features_type as $vals) 
		{
	    	$ar_af_child = array();
			$sql_parent_feature = "select * from `tbl_admin_features` where `af_parent_type`='".$vals['af_name']."'";			
			$result_parent_feature  = mysqli_query($db_con,$sql_parent_feature) or die(mysqli_error($db_con));
			while($row_parent_feature	= mysqli_fetch_array($result_parent_feature))	
			{
				$ar_af_child[] =  $row_parent_feature;
			}
			$ar_af_child_count = count($ar_af_child);
		?>
            <div style="width:100%">
                <div class="control-group" style="background-color:#329EA9" >
                    <label for="textarea" class="control-label" style="color:#FFF"><?php echo $vals['af_name']; ?></label>
                    <div class="controls">
             <?php
				$ar_af_at_id = explode(",",$vals['af_at_id']);
			 ?>
                <input type="checkbox" id="<?php print $vals['af_id']; ?>_par" 
                name="featuretype[]" 
                value="<?php print $vals['af_id']; ?>_par" 
				<?php if(in_array($at_id,$ar_af_at_id)){ ?>checked <?php } ?> 
                onChange="parent_checked(this.id,'<?php echo str_replace(' ', '_', $vals['af_name']); ?>');accessTypeChecked(this.id);" >
                <?php echo str_replace(' ', '_', $vals['af_name']); ?>
                <br>
                    <div id="access<?php echo $vals['af_id'];?>_par" style="display:<?php if(in_array($at_id,$ar_af_at_id)){ echo "block"; }else{echo "none";} ?>" class="<?php echo str_replace(' ', '_', $vals['af_name']);?>">
   						<input type="checkbox" id="add<?php echo $vals['af_id'];?>_par" value="Add" onChange="accessChildCked('<?php echo $vals['af_id'];?>',this.id)"/>Add
						<input type="checkbox" id="edit<?php echo $vals['af_id'];?>_par" value="Edit" onChange="accessChildCked('<?php echo $vals['af_id'];?>',this.id)"/>Edit
						<input type="checkbox" id="del<?php echo $vals['af_id'];?>_par" value="Delete" onChange="accessChildCked('<?php echo $vals['af_id'];?>',this.id)"/>Delete                                             
						<input type="checkbox" id="dis<?php echo $vals['af_id'];?>_par" value="Disable" onChange="accessChildCked('<?php echo $vals['af_id'];?>',this.id)"/>Disable           
                    </div>  
                    </div>                   
                    </div>
                  
				<div style="width:100%">
       <?php 
	   		foreach($ar_af_child as $child)
			{
				?>
				<div style="width:25%;float:left;height:60px;background-color:#F6F6F6;border:#000;padding:20px 0px;">
                    <div style="text-align:center">
                        <div style="text-align:center"><?php echo $child['af_name']; ?></div>
					 <?php
						$ar_af_at_id = explode(",",$vals['af_at_id']);
					 ?>
                        <input type="checkbox"	id="<?php echo $child['af_id'];?>_att" 	
                        class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>_child" 
                        name="childfeaturetype[]" value="<?php print $child['af_id']; ?>_att" 
						<?php if(in_array($at_id,$ar_af_at_id)){ ?>checked <?php } ?> 
                        onChange="child_checked(this.id,'<?php echo $vals['af_id']; ?>','<?php echo str_replace(' ', '_', $child['af_parent_type']);?>','<?php echo $ar_af_child_count; ?>');accessTypeChecked(this.id);"  >
                        
                    </div>
                    <div id="access<?php echo $child['af_id'];?>_att" style="display:<?php if(in_array($at_id,$ar_af_at_id)){ echo "block"; }else{echo "none";} ?>"  class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>">
						<input type="checkbox" class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>" id="add<?php echo $child['af_id'];?>_att" value="Add" onChange="accessChildCked('<?php echo $child['af_id'];?>',this.id)"/>Add
						<input type="checkbox" class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>" id="edit<?php echo $child['af_id'];?>_att" value="Edit" onChange="accessChildCked('<?php echo $child['af_id'];?>',this.id)"/>Edit
						<input type="checkbox" class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>" id="del<?php echo $child['af_id'];?>_att" value="Delete" onChange="accessChildCked('<?php echo $child['af_id'];?>',this.id)"/>Delete                                                
						<input type="checkbox" class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>" id="dis<?php echo $child['af_id'];?>_att" value="Disable" onChange="accessChildCked('<?php echo $child['af_id'];?>',this.id)"/>Disable                        
					</div>
                </div>
			   <?php 
			}
		?>
			</div>
		</div><!-- Width 100% -->
        <div style="clear:both"></div>
        <?php
	   }
		?>
        <div style="clear:both"></div>
        <script language="javascript" type="text/javascript">
			   		function accessTypeChecked(feature_id)
					{
						var feature = $('#'+feature_id+':checkbox:checked').length > 0;					
						if(feature)
						{
							$("#access"+feature_id).slideDown();
							$('#add'+feature_id).prop('checked', true);
							$('#edit'+feature_id).prop('checked', true);
							$('#del'+feature_id).prop('checked', true);
							$('#dis'+feature_id).prop('checked', true);																												
						}
						else
						{
							$("#access"+feature_id).slideUp();							
							$('#add'+feature_id).prop('checked', false);
							$('#edit'+feature_id).prop('checked', false);
							$('#del'+feature_id).prop('checked', false);
							$('#dis'+feature_id).prop('checked', false);								
						}
					}
					function accessChildCked(parent_id,child_id)
					{
						var child = $('#'+child_id+':checkbox:checked').length > 0;
						if(child)
						{
							$('#'+parent_id).prop('checked', true);							
						}
						else
						{

							$("#access"+parent_id).slideUp();							
							$('#'+parent_id).prop('checked', false);
							$('#add'+parent_id).prop('checked', false);
							$('#edit'+parent_id).prop('checked', false);
							$('#del'+parent_id).prop('checked', false);
							$('#dis'+parent_id).prop('checked', false);														
						}								
					}
					function child_checked(childid,parentid,parenttype,childcount)
					{
						var alluncheckcount = $('.'+parenttype).not(':checked').length;
						//alert("parenttype :"+parenttype+"  alluncheckcount :"+alluncheckcount+"  childcount :"+childcount);
						if(alluncheckcount != childcount)
						{
							$('#'+parentid).prop('checked', true);
							$('#add'+parentid).prop('checked', true);
							$('#edit'+parentid).prop('checked', true);
							$('#del'+parentid).prop('checked', true);
							$('#dis'+parentid).prop('checked', true);								
						}
						else if(alluncheckcount == childcount)
						{	
							$('#'+parentid).prop('checked', false);
							$('#add'+parentid).prop('checked', false);
							$('#edit'+parentid).prop('checked', false);
							$('#del'+parentid).prop('checked', false);
							$('#dis'+parentid).prop('checked', false);							
						}
					}
					function parent_checked(id,parentname)
					{
						if(document.getElementById(id).checked)
						{
							var inputs = document.getElementsByClassName(parentname);
							var checkboxes = [];
							for (var i = 0; i < inputs.length; i++) 
							{
								$("."+parentname).slideDown();							  
							}
							var inputs = document.getElementsByClassName(parentname+"_child");
							var checkboxes = [];
							for (var i = 0; i < inputs.length; i++) 
							{
							  if (inputs[i].type == 'checkbox') 
							  {
								inputs[i].checked =true; 
							  }
							}							
						}
						else
						{
							var inputs = document.getElementsByClassName(parentname);
							var checkboxes = [];
							for (var i = 0; i < inputs.length; i++) 
							{
								$("."+parentname).slideUp();								
							}
							var inputs = document.getElementsByClassName(parentname+"_child");
							var checkboxes = [];
							for (var i = 0; i < inputs.length; i++) 
							{
							  if (inputs[i].type == 'checkbox') 
							  {
								inputs[i].checked =false; 		
							  }
							}							
						}
					}
				</script>
       <?php
	}
	else
	{
		echo "";
	}
	exit(0);
}
//------------------------------------------------------------------------------------------------
// End : This is for hide/show sms and feature status
//------------------------------------------------------------------------------------------------

//-----------------------------------------------------
// For inserting new entry of user
//-----------------------------------------------------
$ar_current_rights = "";
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "addnewuser" && isset($_POST['fullname']) && isset($_POST['emailid']) && isset($_POST['password']) && isset($_POST['mobile']))
{
	$error_msg = "";
	if($_POST['fullname'] != "" && $_POST['emailid'] != "" && $_POST['password'] != "" && $_POST['mobile'] != "")
	{
		$fullname 		= mysqli_real_escape_string($db_con,$_POST['fullname']);
		$email 			= mysqli_real_escape_string($db_con,$_POST['emailid']);
		$password 		= mysqli_real_escape_string($db_con,$_POST['password']);
		$mobile_num 	= mysqli_real_escape_string($db_con,$_POST['mobile']);
		$sms_status 	= mysqli_real_escape_string($db_con,$_POST['smsstatus']);
		$email_status 	= mysqli_real_escape_string($db_con,$_POST['emailstatus']);
		$schoolid	 	= mysqli_real_escape_string($db_con,$_POST['schoolid']);
	}
	else
	{ $error_msg .= "Please fill all details \n"; }
	
	$ar_current_rights = "";
	if(isset($_POST['combinedfeature']) && $_POST['combinedfeature'] != "")
	{
		$ar_current_rights = $_POST['combinedfeature'];
		$new_rights			= $ar_current_rights;
	}
	else
	{
		$ar_current_rights = "";
	}	
	$ar_featuretype 	= array();
	
/*	if(isset($_POST['combinedfeature']) && $_POST['combinedfeature'] != "")//If features are checked for new user
	{
		//$ar_featuretype = $_POST['combinedfeature'];
		foreach($_POST['combinedfeature'] as $val)
		{
			$ar_current_rights .= $val.",";
		}
		$ar_current_rights = rtrim($ar_current_rights,",");
	}
	else
	{
		$ar_current_rights = "";
	}*/
	
	if($error_msg == "" && $_POST['user_type'] != "Student" && $_POST['user_type'] != "0" && $_POST['user_owner'] != "0")//Other than student entry will be made in tbl_cadmin_user
	{
		$logged_user 			= $_SESSION['panel_user']['id'];
		$salt_value				= generateRandomString(5);	
		$password				= md5($password.$salt_value);				
		$sql_users_cadmin_users	= "SELECT * FROM `tbl_cadmin_users` WHERE `email`='".$email."'";
		$result_cadmin_users 	= mysqli_query($db_con,$sql_users_cadmin_users) or die(mysqli_error($db_con));
		$num_rows_cadmin_users	= mysqli_num_rows($result_cadmin_users);		
		if($num_rows_cadmin_users > 0)		//If client email already exists
		{
			print "Client name is already exist.\n";
		} 
		else 
		{	
			$utype 			 = mysqli_real_escape_string($db_con,$_POST['user_type']);
			$tbl_users_owner = mysqli_real_escape_string($db_con,$_POST['user_owner']);
			$city 			 = mysqli_real_escape_string($db_con,$_POST['city']);
			$state 			 = mysqli_real_escape_string($db_con,$_POST['state']);
			//If SMS pack is selected and all details filled
			if($sms_status == '1' && isset($_POST['start_date']) && $_POST['start_date'] != "" && isset($_POST['end_date']) && $_POST['end_date'] != "" && isset($_POST['pkgid']) && $_POST['pkgid'] != "")
			{
				$org_sod_startdt 		= array();
				$org_sod_enddt 			= array();				
				$sod_package_id  		= mysqli_real_escape_string($db_con,$_POST['pkgid']);
				$org_sod_startdt 		= explode("/",mysqli_real_escape_string($db_con,$_POST['start_date']));
				$sod_startdt 	 		= $org_sod_startdt[2]."-".$org_sod_startdt[1]."-".$org_sod_startdt[0];
				$org_sod_enddt			= explode("/",mysqli_real_escape_string($db_con,$_POST['end_date']));
				$sod_enddt 		 		= $org_sod_enddt[2]."-".$org_sod_enddt[1]."-".$org_sod_enddt[0];
	
				$sql_selected_sms_pkg 	= "select * from tbl_sms_package where sp_id = '".$sod_package_id."'";
				$result_selected_sms_pkg= mysqli_query($db_con,$sql_selected_sms_pkg) or die(mysqli_error($db_con));
				$row_selected_sms_pkg	= mysqli_fetch_array($result_selected_sms_pkg);
				$sod_num_of_sms	 	  	= $row_selected_sms_pkg['sp_num_sms'];
				$sod_expiry_days 	  	= $row_selected_sms_pkg['sp_validity'];
				
				
				$sql_inser_cadmin		= " INSERT INTO `tbl_cadmin_users`(`fullname`,`userid`,`email`,`email_status`,`password`,`salt_value`,`sms_status`,`utype`, ";
				$sql_inser_cadmin		.= " `mobile_num`,`created`,`created_by`,`tbl_users_owner`,`city`,`state`,`status`) VALUES ('".$fullname."',";
				$sql_inser_cadmin		.= " '".$email."','".$email."','".$email_status."','".$password."','".$salt_value."','".$sms_status."','".$utype."'";
				$sql_inser_cadmin		.= " ,'".$mobile_num."', '".$datetime."','".$logged_user."', '".$tbl_users_owner."', '".$city."', '".$state."','1') ";
				$result_inser_cadmin	= mysqli_query($db_con,$sql_inser_cadmin) or die(mysqli_error($db_con));
				$ar_user_owner_id 		= mysqli_insert_id($db_con);
				
				if($result_inser_cadmin)
				{
					$sql_inser_rights 			= " INSERT INTO `tbl_assign_rights`(`ar_user_owner_id`,`ar_current_rights`,`createddt`,`createdby`) VALUES ";
					$sql_inser_rights 			.= " ('".$ar_user_owner_id."','".$ar_current_rights."','".$datetime."','".$_SESSION['panel_user']['fullname']."')";
					$result_inser_rights		= mysqli_query($db_con,$sql_inser_rights) or die(mysqli_error($db_con));	
					if($result_inser_rights)					
					{
						$sql_sms_user   			= "INSERT INTO `tbl_sms_owner_details`";
						$sql_sms_user  				.= " (`sod_tracking_order_id`,`sod_user_owner_id`,`sod_package_id`,`sod_startdt`,`sod_enddt`,`sod_num_of_sms`,`sod_expiry_days`,`sod_createddt`,`sod_createdby`) VALUES";
						$sql_sms_user  				.= " ('offline','".$ar_user_owner_id."','".$sod_package_id."','".$sod_startdt."','".$sod_enddt."','".$sod_num_of_sms."','".$sod_expiry_days."','".$datetime."','".$_SESSION['panel_user']['fullname']."')";
						$result_sms_user 			= mysqli_query($db_con,$sql_sms_user) or die(mysqli_query($db_con));
						if($result_sms_user)
						{
							echo "Sucess";	
						}
						else
						{
							echo "SMS not updated";	
						}
					}
					else
					{
						echo "Rights not assigned";
					}					
				}
				else
				{
					echo "user not Inserted";					
				}
			}
			else if($sms_status == "0")//If SMS pack is not selected
			{
				$sql_inser_cadmin		= " INSERT INTO `tbl_cadmin_users`(`fullname`,`userid`,`email`,`email_status`,`password`,`salt_value`,`sms_status`,`utype`, ";
				$sql_inser_cadmin		.= " `mobile_num`,`created`,`created_by`,`tbl_users_owner`,`city`,`state`,`status`) VALUES ('".$fullname."',";
				$sql_inser_cadmin		.= " '".$email."','".$email."','".$email_status."','".$password."','".$salt_value."','".$sms_status."','".$utype."'";
				$sql_inser_cadmin		.= " ,'".$mobile_num."', '".$datetime."','".$logged_user."', '".$tbl_users_owner."', '".$city."', '".$state."','1') ";
				$result_inser_cadmin	= mysqli_query($db_con,$sql_inser_cadmin) or die(mysqli_error($db_con));				
				$ar_user_owner_id 		= mysqli_insert_id($db_con);
				if($result_inser_cadmin)
				{
					$sql_inser_rights 	= " INSERT INTO `tbl_assign_rights`(`ar_user_owner_id`,`ar_current_rights`,`createddt`,`createdby`) VALUES ";
					$sql_inser_rights 	.= "('".$ar_user_owner_id."','".$ar_current_rights."','".$datetime."','".$_SESSION['panel_user']['fullname']."')";
					$result_inser_rights= mysqli_query($db_con,$sql_inser_rights) or die(mysqli_error($db_con));
					if($result_inser_rights)
					{
						echo "Sucess";
					}
				}
				else
				{
					echo "user not Inserted";		
				}
			}
			else //If all SMS details are not filled
			{
				echo "Please fill sms details";
			}
		}
		exit(0);
	} 
	else 
	{
		print $error_msg;
		exit(0);
	}
}
if(isset($_POST['jsubmit']) && $_POST['jsubmit']=='check_email' && $_POST['comp1']!="")
{
	$comp1	=	strtolower(mysqli_real_escape_string($db_con,$_REQUEST['comp1']));
	if($comp1!="")
	{		
		$sql_email_cadmin		= "select email from tbl_cadmin_users where email='$comp1'";
		$result_email_cadmin	= mysqli_query($db_con,$sql_email_cadmin) or die(mysqli_error($db_con));
		$num_rows_email_cadmin	= mysqli_num_rows($result_email_cadmin);
		
		if($num_rows_email_cadmin == 0)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	}
	exit(0);
}

if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "countryd" && isset($_POST['countryid']) && $_POST['countryid'] != "")
{
	$country_id 		= mysqli_real_escape_string($db_con,$_POST['countryid']);	
	$sql_get_state 		= "SELECT * FROM `state` where country_id = '".$country_id."'";
	$result_get_state 	= mysqli_query($db_con,$sql_get_state) or die(mysqli_error($db_con));
	?>
    	<option value="">Select State</option>  	
	<?php       
	while($row_get_state = mysqli_fetch_array($result_get_state))
	{
		?>
        
            <option value="<?php print $row_get_state['state']; ?>"><?php print $row_get_state['state_name']; ?></option>
		<?php 
	} 
	exit(0);
}

if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "stated" && isset($_POST['stateid']) && $_POST['stateid'] != "")
{
	$state_id 			= mysqli_real_escape_string($db_con,$_POST['stateid']);
	$sql_get_city		= "SELECT * FROM `city` where state_id = '".$state_id."'";
	$result_get_city 	= mysqli_query($db_con,$sql_get_city) or die(mysqli_error($db_con));
	$num_rows_get_city 	= mysqli_num_rows($result_get_city); 	
	if($num_rows_get_city != 0)
	{
		?>
	    	<option value="">Select City</option>  	
		<?php       	
		while($row_get_city = mysqli_fetch_array($result_get_city))
		{
			?>
	       	    <option value="<?php print $row_get_city['city_id']; ?>"><?php print $row_get_city['city_name']; ?></option>
			<?php 
		} 		
	}
	else
	{
				
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
<body class="theme-orange" data-theme="theme-orange" >
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
	?> <!-- mysqlcoding --> 
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-bordered box-color">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        <?php echo $feature_name; ?>
                                    </h3>
                                </div> <!-- header title-->
                                <div class="box-content nopadding">
                                    <form enctype="multipart/form-data"  id="frm_add_new_user" name="frm_add_new_user" method="POST" class='form-horizontal form-bordered form-validate'>				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Full Name</label>
                                            <div class="controls fullname">
                                                <input type="text" name="fullname" id="fullname"  class="input-xlarge" placeholder="Full name" data-rule-required="true">
                                            </div>
                                        </div> <!-- Full Name -->
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Email ID</label>
                                            <div class="controls">
                                                <input type="text" name="emailid" id="emailid"  class="input-xlarge" placeholder="Email ID"  data-rule-required="true" data-rule-email="true" onBlur="EMail(this.value)">
                                                <div id="comp_2"></div>
                                            </div>
                                        </div> <!-- Email id -->
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Password</label>
                                            <div class="controls">
                                                <input type="password" name="password" id="password"  class="input-xlarge" placeholder="Password" data-rule-required="true">
                                            </div>
                                        </div> <!-- Password -->
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Mobile Number</label>
                                            <div class="controls">
                                                <input type="text" name="mobile" id="mobile"  class="input-xlarge" placeholder="Mobile Number" data-rule-required="true" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)">
                                            </div>
                                        </div> <!-- Mobile number --> 
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">State<span style="color:#F00">*</span></label>
                                            <div class="controls" id="country_wise_state">
                                                <select name="state" id="state"  class="select2-me input-xlarge" data-rule-required="true" onChange="on_state_select(this.value);" >
                                                <option value="">Select State</option>                                                
                                                </select>
                                            </div>
                                       </div>    
                                        
                                        <div class="control-group">
                                                <label for="textarea" class="control-label">City<span style="color:#F00">*</span></label>
                                                <div class="controls" id="state_wise_city">
                                                    <select name="city" id="city"  class="select2-me input-xlarge" data-rule-required="true" >
                                                    <option value="">Select City</option>
                                                    </select>
                                                </div>
                                            </div> 
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">User Type</label>
                                            <div class="controls">
                                                <select name="user_type" style="width:150px;" class="select2-me input-xlarge" onChange="show_other_options();" data-rule-required="true" id="user_type">
                                                    <option value="">Select User Type</option>
                                                        <?php foreach($ar_type_of_admin as $key=>$vals) { ?>
                                                            <option value="<?php print $vals['at_id']; ?>"><?php print $vals['at_name']; ?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div> <!-- User Type -->                                        
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">User Owner</label>
                                            <div class="controls">
                                                <select name="user_owner" style="width:150px;" class="select2-me input-xlarge"  data-rule-required="true" id="user_owner">
                                                    <option value="">Select Owner</option>
                                                        <?php foreach($ar_owner_name as $key=>$vals) { ?>
                                                            <option value="<?php print $vals['id']; ?>"><?php print $vals['clientname']; ?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div> <!-- User Owner --> 
                                        
                                        <div id="divcategory"></div>
                                        
                                        <div class="form-actions">
                                            <button type="submit" name="reg_submit" class="btn-success">Create</button>
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
    </div>
	<script>
		$(document).ready(function(e) { //This is for hidding sms package setting at loading time
			
			var countryid = 'IN';
		$.post(location.href,{countryid:countryid,jsubmit:'countryd'},function(data){
			$("#state").html(data);		
			});
			
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
						
			if($("#smsstatus").is(':checked'))
			{
				$("#divsmspackage").show();
				$("#divsmspackagedaterange").show();
			}
			else
			{
				$("#divsmspackage").hide();
				$("#divsmspackagedaterange").hide();
			}		
				$("#divsmspackagedaterange").hide();
			
		});
//	$(document).ready(function() {
//		var countryid = 'IN';
//		$.post(location.href,{countryid:countryid,jsubmit:'countryd'},function(data){
//			$("#country_wise_state").html(data);
//			$("#state").select2();
//			//alert(data);
//		});
//	});
	
	function show_other_options() //This is for changing content of page according to user type selection
	{
		var user_type	= $.trim($('select[name="user_type"]').val());
		if(user_type == 12)
		{
			$('#div_schoolwise').css('display','block');
		}
		else
		{
			$('#div_schoolwise').css('display','none');
		}
		$.post(location.href, {user_type:user_type,jsubmit:'categorywise'}, function(data)
		{
			$('#divcategory').html(data);
		});		
		$("#divsmspackage").hide();
		$("#divsmspackagesettingname").hide();
		$("#divsmspackagesettingvalidity").hide();
		$("#divsmspackagedaterange").hide();
		$('#start_date').val("");
		$('#end_date').val("");
	}
	function show_email()//This function for show/hide sms package names
	{
		if($("#emailstatus").is(':checked'))
		{
			$('#16').prop('checked', true);  //This is for setting notification checked by default
			$('#33').prop('checked', true);
		}
		else if(!($("#emailstatus").is(':checked')) && !($("#smsstatus").is(':checked')))
		{
			$('#16').prop('checked', false);  //This is for setting notification checked by default
			$('#33').prop('checked', false);
		}
	}
	function show_sms_div()//This function for show/hide sms package names
	{
		if($("#smsstatus").is(':checked'))
		{
			//$('.parentNotifications').prop('checked', true);  //This is for setting notification checked by default
			//$('.Notifications').prop('checked', true);
			$('#16').prop('checked', true);  //This is for setting notification checked by default
			$('#33').prop('checked', true);
			$("#divsmspackage").slideDown("slow");
			$("#divsmspackagesettingname").slideDown("slow");
			$("#divsmspackagesettingvalidity").slideDown("slow");
			$("#divsmspackagedaterange").slideDown("slow");
			
			var dateToday = new Date();
			 $(function() {
			$( "#start_date, #end_date" ).datepicker({
				defaultDate		: "+1w",
				changeMonth		: true,
				changeYear		: true,
				dateFormat		: 'dd/mm/yy',
				yearRange 		: 'c:c+10',	//this is for selecting only next years
				numberOfMonths	: 1,
				minDate			: dateToday,		//this is for disable previous date
				onSelect		: function( selectedDate ) {
					
					var validity = $( "#divsmspackagesettingvalidity" ).text();
					days		 =	parseInt(validity);
					
					if(this.id == 'start_date'){
					  var dateMin 	= $('#start_date').datepicker("getDate");
					  var rMin 		= new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); 
					  var rMax 		= new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + days); 
					  //$('#to').datepicker("option","minDate",rMin);
					  //$('#to').datepicker("option","maxDate",rMax);  
					  $('#end_date').val($.datepicker.formatDate('dd/mm/yy', new Date(rMax)));                    
					}
					
					}
				});
			});
			
		}
		else
		{
			$("#divsmspackage").slideUp("slow");
			$("#divsmspackagesettingname").slideUp("slow");
			$("#divsmspackagesettingvalidity").slideUp("slow");
			$("#divsmspackagedaterange").slideUp("slow");
			$('#start_date').val("");
			$('#end_date').val("");
		}
		if(!($("#emailstatus").is(':checked')) && !($("#smsstatus").is(':checked')))
		{
			$('#16').prop('checked', false);  //This is for setting notification checked by default
			$('#33').prop('checked', false);
		}

	}
	
	function packageselected(pkgname,id)	//This function is for show/hide selected sms package information
	{
		var arr = pkgname.split(',');
		$("#end_date").show();
		$("#divsmspackagesettingname").html("<b>"+arr[0]+"</b> is selected");	
		$("#divsmspackagesettingvalidity").html("<b>"+arr[1]+"days</b> of validity ");	
		$("#divsmspackagedaterange").show();
		if ($("#"+id).hasClass("selected")) //Only one sms package can be selected at time
		{
			$("#"+id).removeClass("selected");
		}
		else
		{
			$("#"+id).addClass("selected").siblings().removeClass("selected");
			$('#start_date').val("");
			$('#end_date').val("");
			$("#pkgid").val(id);
		}
	}
	function EMail(comp1) 
	{		
		$.post(location.href,{comp1:comp1,jsubmit:'check_email'},function(data){
			if (data=="fail") 
			{
				$('#comp_2').html('<span style="color:#F00;margin-left:15px;">Emalid already exist.Try with another</span>').show().fadeOut(5000);
				$('input[name="emailid"]').val('');
			} 
			else if(data=="success")
			{
				$('#comp_2').html('<span style="color:#0C0;margin-left:15px;">Emalid available</span>').show().fadeOut(5000);
			}
		});
	}
	function ValidateMobile(mobileid) //This is for validating mobile number
	{
		var mobilenum = document.getElementById(mobileid).value;
		var firstdigit = mobilenum.charAt(0);
	
		if(firstdigit==0)
		{
			$("#"+mobileid).attr('maxlength','11');
		}
		else
		{
			$("#"+mobileid).attr('maxlength','10');
		}
	}
	function isNumberKey(evt) //This is for only numeric value
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		{
			return false;
		}
		return true;
	}

	//This is for creating new feature type	
	$('#frm_add_new_user').on('submit', function(e) {
	e.preventDefault();
	if ($('#frm_add_new_user').valid())
	{
		var fullname 	= $.trim($('input[name="fullname"]').val());
		var emailid 	= $.trim($('input[name="emailid"]').val());
		var password 	= $.trim($('input[name="password"]').val());
		var mobile 		= $.trim($('input[name="mobile"]').val());
		var user_type	= $.trim($('select[name="user_type"]').val());
		var user_owner	= $.trim($('select[name="user_owner"]').val());
		var state		= $.trim($('select[name="state"]').val());
		var city		= $.trim($('select[name="city"]').val());
		//var smsstatus	= $.trim($("input[name='smsstatus']:checked").val());
		var emailstatus	= $.trim($("input[name='emailstatus']:checked").val());
		var start_date	= $('#start_date').val();
		var end_date	= $('#end_date').val();
		var pkgid 		= $("#pkgid").attr("value");
		var schoolid    = '';
		
		if(user_type == 12)
		{
			var schoolid	= $.trim($('select[name="schoolwise"]').val());
			if(schoolid == '')
			{
				alert("Please select school");
				return false;
			}
		}
		if($('#smsstatus').prop('checked')) 
		{
			var smsstatus = 1;
		} 
		else 
		{
			var smsstatus = 0;
		}
		
		var featuretype = "";
		$("input[name='featuretype[]']:checked").each(function ()
		{			
			var feature_id = $.trim(($(this).val()));		
			var featuretype_access = [] 
			var add = $('#add'+feature_id+':checkbox:checked').length > 0;
    		if(add)
			{		
				featuretype_access.push(1);
 		   	} 
			else 
			{
				featuretype_access.push(0);				
		    }
			var edit = $('#edit'+feature_id+':checkbox:checked').length > 0;
    		if(edit)
			{		
				featuretype_access.push(1);
 		   	} 
			else 
			{
				featuretype_access.push(0);				
		    }			
			var del = $('#del'+feature_id+':checkbox:checked').length > 0;
    		if(del)
			{		
				featuretype_access.push(1);
 		   	} 
			else 
			{
				featuretype_access.push(0);				
		    }			
			var dis = $('#dis'+feature_id+':checkbox:checked').length > 0;
    		if(dis)
			{		
				featuretype_access.push(1);
 		   	} 
			else 
			{
				featuretype_access.push(0);				
		    }	
			featuretype = featuretype+"{"+parseInt(feature_id)+":"+featuretype_access+"}*";
		});
		var childfeaturetype = "";
		$("input[name='childfeaturetype[]']:checked").each(function ()
		{
			var child_feature_id = $.trim(($(this).val()));
			var child_featuretype_access = [] 
			var add = $('#add'+child_feature_id+':checkbox:checked').length > 0;
    		if(add)
			{		
				child_featuretype_access.push(1);
 		   	} 
			else 
			{
				child_featuretype_access.push(0);				
		    }
			var edit = $('#edit'+child_feature_id+':checkbox:checked').length > 0;
    		if(edit)
			{		
				child_featuretype_access.push(1);
 		   	} 
			else 
			{
				child_featuretype_access.push(0);				
		    }			
			var del = $('#del'+child_feature_id+':checkbox:checked').length > 0;
    		if(del)
			{		
				child_featuretype_access.push(1);
 		   	} 
			else 
			{
				child_featuretype_access.push(0);				
		    }			
			var dis = $('#dis'+child_feature_id+':checkbox:checked').length > 0;
    		if(dis)
			{		
				child_featuretype_access.push(1);
 		   	} 
			else 
			{
				child_featuretype_access.push(0);				
		    }		
			childfeaturetype = childfeaturetype+"{"+parseInt(child_feature_id)+":"+child_featuretype_access+"}*";
		});
		var combinedfeature = featuretype + childfeaturetype;
		if(user_type == 0)
		{
			alert("Please select user type");
			return false;
		}
		else if(user_owner == 0)
		{
			alert("Please select user owner");
			return false;
		}
		else if(smsstatus == 1 && start_date == "" && end_date == "" && pkgid == "")
		{
			alert("Please enter SMS details");
			return false;
		}
		else
		{
		$('input[name="reg_submit"]').attr('disabled', 'true');
		$.post(location.href,{fullname:fullname,emailid:emailid,password:password,mobile:mobile,user_type:user_type,user_owner:user_owner,combinedfeature:combinedfeature,smsstatus:smsstatus,emailstatus:emailstatus,start_date:start_date,end_date:end_date,pkgid:pkgid,state:state,city:city,schoolid:schoolid,jsubmit:'addnewuser'},function(data)
		{
			if (data != "Sucess") 
			{
				$('input[name="reg_submit"]').removeAttr('disabled');
				alert(data);
			} 
			else if(data == "Sucess") 
			{
				alert("Congratulations...You have sucessfully created user.");
				window.location.replace('view_user.php?pag=<?php echo $title; ?>','_self');
			}
			return false;
		});
		}
	}
	});
	
	function on_state_select(stateid)
	{
		$.post(location.href,{stateid:stateid,jsubmit:'stated'},function(data)
		{
			$("#city").html(data);
		});
	}
</script>

</body>
</html>