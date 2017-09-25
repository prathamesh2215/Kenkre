<?php
include("include/routines.php");

$title  		= "Feature";
$filename 		= "edit_featuretype.php";
$home_url 		= "view_dashboard.php?pag=Dashboard";
$home_name 		= "Home";
$feature_name 	= "Update Feature";

$af_id			=	$_GET['af_id'];

//-----------------------------------------------------
// Start : This is for showing existing data for update
//-----------------------------------------------------
if(isset($_GET['af_id']) && $_GET['af_id'])
{
	$af_id 	= mysqli_real_escape_string($db_con,$_GET['af_id']);
	$sql = "select * from `tbl_admin_features` where `af_id`='".$af_id."'";
	$result = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	$row 	= mysqli_fetch_array($result);
}
$features_type = array();
$sql_feature_type = "select distinct * from tbl_admin_type";
$result_feature_type = mysqli_query($db_con,$sql_feature_type) or die(mysqli_error($db_con));
while($row_features_type = mysqli_fetch_array($result_feature_type))
{
	$features_type[$row_features_type['at_id']] = $row_features_type;
}
//-----------------------------------------------------
// Start : This is for filling listbox
//-----------------------------------------------------
$arr_test_topic	= array();
$sql_af_name = "select af_name from tbl_admin_features where af_parent_type = 'Parent' and af_name != '".$row['af_name']."'";
$result_af_name = mysqli_query($db_con,$sql_af_name) or die(mysqli_error($db_con));
while ($row_af_name = mysqli_fetch_array($result_af_name)) 
{
	$arr_test_topic[] = $row_af_name;
}
//-----------------------------------------------------
// End : This is for filling listbox
//-----------------------------------------------------


if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "editfeaturetype" && isset($_POST['featurename']) && $_POST['featurename'] != "" && isset($_POST['url']) && $_POST['url'] != "" && isset($_POST['parent']) && $_POST['parent'] != "0")
{
	$error_msg = "";
	if(isset($_POST['featurename']) && $_POST['featurename']!="")
	{
		$af_name = mysqli_real_escape_string($db_con,$_POST['featurename']);
	}
	if($_POST['parent']=="parent")
	{
		$sql_max_af_order	 	= "select max(af_menu_order) from tbl_admin_features where af_parent_type = 'Parent' and af_id != '".$af_id."'";
		$result_max_af_order 	= mysqli_query($db_con,$sql_max_af_order) or die(mysqli_error($db_con));
		$row_max_af_order 		= mysqli_fetch_array($result_max_af_order);
		$af_parent_type  	= "Parent";
	}
	else
	{
		$sql_max_af_order 	 	= "select max(af_menu_order) from tbl_admin_features where af_parent_type = '".$_POST['parent']."' and af_id != '".$af_id."'";
		$result_max_af_order 	= mysqli_query($db_con,$sql_max_af_order) or die(mysqli_error($db_con));
		$row_max_af_order 		= mysqli_fetch_array($result_max_af_order);
		
		$af_parent_type 	= mysqli_real_escape_string($db_con,$_POST['parent']);
	}
	if(isset($_POST['url']) && $_POST['url']!="")
	{
		$af_page_url 		= mysqli_real_escape_string($db_con,$_POST['url']);
	}
	
	$af_status	 = mysqli_real_escape_string($db_con,$_POST['status']);
	
	$adtype 			= array();
	$ar_adtype 			= array();
	$ar_user_type 		= array();
	$ar_db_user_type	= array();
	$af_at_type = "";
	if(isset($_POST['adtype']) && $_POST['adtype'] != "")
	{
		$adtype 	= $_POST['adtype'];
		$ar_adtype  = $_POST['adtype'];
	}
	else
	{
		$af_at_type = "";
	}
	
	if($error_msg == "")
	{
		$sql_af_name	= "SELECT `af_name` FROM `tbl_admin_features` WHERE `af_name`='".$af_name."' and `af_id`!='".$af_id."'";
		$result_af_name = mysqli_query($db_con,$sql_af_name) or die(mysqli_error($db_con));
		$num_rows_af_name = mysqli_num_rows($result_af_name);
		if($num_rows_af_name > 0)
		{
			print "Feature name is already exist.\n";
		} 
		else 
		{
			foreach($ar_adtype as $vals)
			{
				$af_at_type .= $vals.",";
			}
			$af_at_type = rtrim($af_at_type,",");
			
			$sql_admin_features 	= "select * from tbl_admin_features where `af_id`='".$af_id."'";
			$result_admin_features 	= mysqli_query($db_con,$sql_admin_features) or die(mysqli_error($db_con));
			$row_admin_features 	= mysqli_fetch_array($result_admin_features);
			$ar_db_user_type = $row_admin_features['af_at_id'];

			$ar_user_type 	 = explode(',',$af_at_type);							//rights seleted on page
			$ar_db_user_type = explode(',',$ar_db_user_type);		//rights seleted on page
			$diff_user_type	 = array_diff($ar_db_user_type, $ar_user_type);			//rights present in database but unchecked on page

			$ar_rem_existing_db_rights = array();
			
			if($diff_user_type != "")
			{
				foreach($diff_user_type as $vals)							//To check each unchecked element present inside history or not
				{  
					//-------------------------unchecked user type------------------------------
					//updating existing owner rights info
					$sql_rem_existing_user_type 	  = "select * from tbl_cadmin_users where utype = '".$vals."'";
					$res_rem_existing_user_type 	  = mysqli_query($db_con,$sql_rem_existing_user_type) or die(mysqli_error($db_con));
					while($row_rem_existing_user_type = mysqli_fetch_array($res_rem_existing_user_type))
					{
						$ar_user_owner_id 				= $row_rem_existing_user_type['id'];
						$sql_rem_fetch_current_rights = "select * from tbl_assign_rights where ar_user_owner_id = '".$ar_user_owner_id."'";
						$res_rem_fetch_current_rights 	= mysqli_query($db_con,$sql_rem_fetch_current_rights) or die(mysqli_error($db_con));
						$row_rem_fetch_current_rights 	= mysqli_fetch_array($res_rem_fetch_current_rights);
						
						$ar_rem_existing_db_rights 		= explode(',',$row_rem_fetch_current_rights['ar_current_rights']); 	//current rights according to database
						$ar_rem_history_db_rights 		= explode(',',$row_rem_fetch_current_rights['ar_history_rights']); 	//history rights according to database
						$history_db_rights 				= $row_rem_fetch_current_rights['ar_history_rights']; 	//history rights according to database
						
						if(in_array($af_id,$ar_rem_existing_db_rights))//To check whether feature id is in current rights
						{
							if($row_rem_fetch_current_rights['ar_current_rights'] != "")
							{
								$single_rights 		= explode(",",$row_rem_fetch_current_rights['ar_current_rights']);
								if($single_rights[0] == $af_id && $single_rights[1] == "")
								{
									$ar_current_rights = str_replace($af_id,"",$row_rem_fetch_current_rights['ar_current_rights']);
								}
								else if($single_rights[0] == $af_id && $single_rights[1] != "")
								{
									$ar_current_rights = str_replace($af_id.",","",$row_rem_fetch_current_rights['ar_current_rights']);
								}
								else
								{
									$ar_current_rights = str_replace(",".$af_id,"",$row_rem_fetch_current_rights['ar_current_rights']);
								}
								if(!in_array($af_id,$ar_rem_history_db_rights))
								{
									if($row_rem_fetch_current_rights['ar_history_rights'] != "")
									{
										$ar_history_rights = $history_db_rights.",".$af_id;
									}
									else
									{
										$ar_history_rights = $af_id;
									}
								}
								else
								{
										$ar_history_rights = $history_db_rights;
								}
								$sql_update_rights 		= "update tbl_assign_rights set ar_current_rights = '".$ar_current_rights."', ar_history_rights = '".$ar_history_rights."' where ar_user_owner_id = '".$ar_user_owner_id."'";
								$result_update_rights 	=  mysqli_query($db_con,$sql_update_rights) or die(mysqli_error($db_con));
							}
						}
					}//while($res_existing_user_type = mysql_fetch_array($res_existing_user_type1))
					//-------------------------------------------------------
					//$af_at_type .= $adtype.",";
					//-------------------------unchecked user type------------------------------
				}
			}
			$sql_update_features1 = " UPDATE `tbl_admin_features` SET `af_name`='".$af_name."', `af_parent_type`='".$af_parent_type."', ";
			$sql_update_features1 .= " `af_page_url`='".$af_page_url."', `af_status`='".$af_status."', `af_at_id`='".$af_at_type."', ";
			$sql_update_features1 .= " `modifieddt`='".$datetime."', `modifiedby`='".$_SESSION['panel_user']['fullname']."' where `af_id`='".$af_id."'";
			$result_update_features1 = mysqli_query($db_con,$sql_update_features1) or die(mysqli_error($db_con));
			
			$sql_update_features2 = " UPDATE `tbl_admin_features` SET `af_parent_type`='".$af_name."',`modifieddt`='".$datetime."', ";
			$sql_update_features2 .= " `modifiedby`='".$_SESSION['panel_user']['fullname']."' where `af_parent_type`='".$row['af_name']."' ";
			$result_update_features2 = mysqli_query($db_con,$sql_update_features2) or die(mysqli_error($db_con));
			
			if($_POST['parent'] == "parent")
			{
				$sql_update_features3 = " UPDATE `tbl_admin_features` SET `af_at_id`='".$af_at_type."', `modifieddt`='".$datetime."', ";
				$sql_update_features3 .= " `modifiedby`='".$_SESSION['panel_user']['fullname']."' where `af_parent_type`='".$row['af_name']."' ";
				$result_update_features3 = mysqli_query($db_con,$sql_update_features3) or die(mysqli_error($db_con));				
			}

			foreach($adtype as $adtype)
			{
				//updating existing owner rights info
				$sql_existing_user_type 	  		= "select * from tbl_cadmin_users where utype = '".$adtype."'";
				$res_existing_user_type1 	  		= mysqli_query($db_con,$sql_existing_user_type) or die(mysqli_error($db_con));
				while($row_existing_user_type = mysqli_fetch_array($res_existing_user_type1))
				{
					$ar_user_owner_id 				= $row_existing_user_type['id'];
					$sql_fetch_current_rights 		= "select * from tbl_assign_rights where ar_user_owner_id = '".$ar_user_owner_id."'";
					$result_fetch_current_rights 	= mysqli_query($db_con,$sql_fetch_current_rights) or die(mysqli_error($db_con));
					$row_fetch_current_rights 		= mysqli_fetch_array($result_fetch_current_rights);
					
					$ar_existing_db_rights 		= explode(',',$row_fetch_current_rights['ar_current_rights']); 	//current rights according to database
					$ar_history_db_rights 		= explode(',',$row_fetch_current_rights['ar_history_rights']); 	//history rights according to database
					
					if(!in_array($af_id,$ar_existing_db_rights))//To check whether feature id is in current rights
					{
						if($row_fetch_current_rights['ar_current_rights'] != "")
						{
							$ar_current_rights 		= $row_fetch_current_rights['ar_current_rights'].",".$af_id;
							$sql_update_rights 		= " Update tbl_assign_rights set ar_current_rights = '".$ar_current_rights."' where ar_user_owner_id = '".$ar_user_owner_id."'";
							$result_update_rights 	= mysqli_query($db_con,$sql_update_rights) or die(mysqli_error($db_con));
						}
						else
						{
							$sql_update_rights 		= "update tbl_assign_rights set ar_current_rights = '".$af_id."' where ar_user_owner_id = '".$ar_user_owner_id."'";
							$result_update_rights 	= mysqli_query($db_con,$sql_update_rights) or die(mysqli_error($db_con));
						}
					}
				}//while($res_existing_user_type = mysql_fetch_array($res_existing_user_type1))
				//-------------------------------------------------------
				//$af_at_type .= $adtype.",";
			}//foreach($adtype as $adtype)
		
		}
		exit(0);
	} 
	else 
	{
		print $error_msg;
		exit(0);
	}
}
else if(isset($_POST['featurename']) && $_POST['featurename'] == "")
{
	print "Please enter feature name";
	exit(0);
}
else if(isset($_POST['parent']) && $_POST['parent'] == "0")
{
	print "Please select parent";
	exit(0);
}
else if(isset($_POST['url']) && $_POST['url'] == "")
{
	print "Please enter page url";
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
                                    <form enctype="multipart/form-data"  id="editfeaturetype" name="editfeaturetype" method="POST" class='form-horizontal form-bordered form-validate'>				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Feature Name</label>
                                            <div class="controls">
                                                <input type="text" name="feature_name" id="feature_name"  class="input-xlarge" placeholder="Feature name" data-rule-required="true" value="<?php echo $row['af_name']; ?>">
                                            </div>
                                        </div>
                                     
                                        <div class="control-group">
                                            <label for="textfield" class="control-label">Select Parent</label>
                                            <div class="controls">
                                                <select name="parent" id="parent" placeholder="Parent Name" class="select2-me input-large" data-rule-required="true">
                                                    <option value="">Select Parent</option>
                                                    <option value="parent" <?php if($row['af_parent_type']=="Parent"){ ?> selected <?php } ?> >Parent</option>
                                                    <?php if($arr_test_topic != ""){ foreach ($arr_test_topic as $val) { ?><option value="<?php print $val['af_name']; ?>" <?php if($val['af_name']==$row['af_parent_type']){ ?> selected <?php } ?> ><?php print $val['af_name']; ?></option><?php } } ?>
                                                </select>
                                            </div>
                                        </div> 
                                         
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Page URL</label>
                                            <div class="controls">
                                                <input type="text" name="page_url" id="page_url"  class="input-xlarge" placeholder="Page URL" data-rule-required="true"  value="<?php echo $row['af_page_url']; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Status<br>(uncheck to deactivate)</label>
                                            <div class="controls">
                                                <input type="checkbox" name="featurestatus" id="featurestatus"  value="1" <?php if($row['af_status']=='1'){ ?> checked <?php } ?>/> 
                                            </div>
                                        </div>
                                        
                                        <div class="box-title">
                                            <h3><i class="icon-th-list"></i> This feature will by default allocated to following Admin type</h3>
                                        </div>
                                        
                                         <?php foreach($features_type as $key=>$vals) 
                                                { 
                                        ?>
                                                    <div class="control-group">
                                                            <label for="textarea" class="control-label"><?php print $vals['at_name']; ?></label>
                                                        <div class="controls">
                                                			<input type="checkbox" id="<?php print $vals['at_id']; ?>" name="adtype[]" value="<?php print $vals['at_id']; ?>" class="css-checkbox cls_admin" <?php $af_at_id = explode(',',$row['af_at_id']);  if (in_array($vals['at_id'], $af_at_id)) { echo "checked"; } ?> >
                                                			<label for="<?php print $vals['at_id']; ?>" class="css-label"></label>                                                        
                                                        </div>
                                                    </div>
                                         <?php } ?>
                                        
                                         <div class="form-actions">
                                                <button type="submit" name="reg_submit" value="Update" class="btn-success">Update</button>
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
    </div>
<script src="js/jquery-ui.js"></script>
<script language="javascript" type="text/javascript">
	$('#editfeaturetype').on('submit', function(e) {
		e.preventDefault();
		if ($('#editfeaturetype').valid())
		{
			var featurename = $.trim($('input[name="feature_name"]').val());
			var parent		= $.trim($('select[name="parent"]').val());
			var url			= $.trim($('input[name="page_url"]').val());
		
			var adtype = []
			$("input[name='adtype[]']:checked").each(function ()
			{
				adtype.push(parseInt($(this).val()));
			});
			
			if($('#featurestatus').prop('checked')) 
			{
				var status = 1;
			} 
			else 
			{
				var status = 0;
			}
	
			if(parent == 1)
			{
				alert("Please select parent type");
			}
			else
			{
				$('input[name="reg_submit"]').attr('disabled', 'true');
				$.post(location.href,{featurename:featurename,parent:parent,url:url,status:status,adtype:adtype,jsubmit:'editfeaturetype'},function(data){
				if (data.length > 0) 
				{
					$('input[name="reg_submit"]').removeAttr('disabled');
					alert(data);
				} 
				else 
				{
					alert("You have sucessfully updated feature.");
					window.history.go(-1);
					//window.location.replace('view_featuretype.php?pag=<?php echo $title; ?>','_self');
				}
				return false;
				});
			}
		}
	});
</script>
                    
</body>
</html>