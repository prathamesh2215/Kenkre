<?php
include("include/routines.php");
//checkuser();
//chkRights(basename($_SERVER['PHP_SELF']));
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
//This is for fetching all parent name (list box)
$arr_test_topic	= array();
$sql 			= "select af_name from tbl_admin_features where af_parent_type = 'Parent'";
$result 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
while ($row = mysqli_fetch_array($result)) 
{
	$arr_test_topic[] = $row;
}
//This is for fetching all admin type (Checkbox)
$ar_admin_type 		= array();
$sql_admin_type 	= "select distinct * from tbl_admin_type";
$result_admin_type 	= mysqli_query($db_con,$sql_admin_type) or die(mysqli_error($db_con));
while($row_admin_type = mysqli_fetch_array($result_admin_type))
{
	$ar_admin_type[$row_admin_type['at_id']] = $row_admin_type;
}
//----------------------------------------------------------------------
// Start : This is for adding new feature
//----------------------------------------------------------------------
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "addnewassigntype" )
{
	$error_msg = "";
	if(isset($_POST['featurename']) && $_POST['featurename'] != "" && isset($_POST['url']) && $_POST['url'] != "" && isset($_POST['parent']) && $_POST['parent'] != "0" )
	{
		$af_name = mysqli_real_escape_string($db_con,$_POST['featurename']);
		
		if($_POST['parent']=="parent")
		{
			$sql_af_menu_order 			= "select max(af_menu_order) from tbl_admin_features where af_parent_type = 'Parent'";
			$result_af_menu_order 		= mysqli_query($db_con,$sql_af_menu_order) or die(mysqli_error($db_con));
			$row_af_menu_order 			= mysqli_fetch_array($result_af_menu_order);
			$af_menu_order 				= $row_af_menu_order['max(af_menu_order)']+1;
			$af_parent_type 			= "Parent";
		}
		else
		{
			$sql_af_parent_type			= "select max(af_menu_order) from tbl_admin_features where af_parent_type = '".$_POST['parent']."'";
			$result_af_parent_type 		= mysqli_query($db_con,$sql_af_parent_type) or die($db_con);
			
			$row_af_parent_type 		= mysqli_fetch_array($result_af_parent_type);
			$af_menu_order 				= $row_af_parent_type['max(af_menu_order)']+1;
			
			$sql_af_parent_type1 		= "select af_menu_order from tbl_admin_features where af_parent_type = '".$_POST['parent']."'";
			$result_af_parent_type1 	= mysqli_query($db_con,$sql_af_parent_type1) or die(mysqli_error($db_con));
			$af_parent_type  			= mysqli_real_escape_string($db_con,$_POST['parent']);
		}
		$af_page_url 					= mysqli_real_escape_string($db_con,$_POST['url']);
		$af_status	 					= mysqli_real_escape_string($db_con,$_POST['status']);
	}
	else
	{ 
		print "Please select parent type";
		exit(0);
	}
	
	$adtype 			= array();
	$ar_adtype 			= array();
	$ar_existing_user 	= array();
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
		$sql_af_name 				= "SELECT `af_name` FROM `tbl_admin_features` WHERE `af_name`='".$af_name."'";
		$result_af_name 			= mysqli_query($db_con,$sql_af_name) or die(mysqli_error($db_con));
		$num_rows_af_name			= mysqli_num_rows($result_af_name);
		if($num_rows_af_name > 0)
		{
			print "Feature name is already exist.\n";
			exit(0); 
		} 
		else 
		{
			foreach($ar_adtype as $vals)
			{
				$af_at_type .= $vals.",";
			}
			$af_at_type = rtrim($af_at_type,",");
			$sql_admin_features 	= " INSERT INTO `tbl_admin_features`(`af_name`, `af_parent_type`, `af_page_url`, `af_status`, `af_menu_order`, ";
			$sql_admin_features    .= " `af_at_id`, `createddt`, `createdby`) VALUES ('".$af_name."', '".$af_parent_type."', '".$af_page_url."', ";
			$sql_admin_features    .= " '".$af_status."', '".$af_menu_order."', '".$af_at_type."', '".$datetime."', '".$_SESSION['panel_user']['fullname']."') ";
			$result_admin_features = mysqli_query($db_con,$sql_admin_features) or die(mysqli_error($db_con));
			
			//updating existing ownser rights info
			$ar_current_rights 	= "";
			$new_feature_id 	= "{".mysqli_insert_id($db_con).":0,0,0,0}";
			//--------------------------------------------------------
			
			foreach($adtype as $adtype)
			{
				//updating existing owner rights info
				$sql_existing_user_type 	  = "select * from tbl_cadmin_users where utype = '".$adtype."'";
				$res_existing_user_type1 	  = mysqli_query($db_con,$sql_existing_user_type) or die(mysqli_error($db_con));
				while($res_existing_user_type = mysqli_fetch_array($res_existing_user_type1))
				{
					$ar_user_owner_id 					= $res_existing_user_type['id'];
					$sql_fetch_current_rights			= "select * from tbl_assign_rights where ar_user_owner_id = '".$ar_user_owner_id."'";
					$result_fetch_current_rights 		= mysqli_query($db_con,$sql_fetch_current_rights);
					$row_fetch_current_rights 			= mysqli_fetch_array($result_fetch_current_rights);
					if($row_fetch_current_rights['ar_current_rights'] != "")
					{
						$ar_current_rights 				= $row_fetch_current_rights['ar_current_rights'].",".$new_feature_id;
						$sql_update_current_rights 		= "update tbl_assign_rights set ar_current_rights = '".$ar_current_rights."' where ar_user_owner_id = '".$ar_user_owner_id."'";
						$result_update_current_rights 	= mysqli_query($db_con,$sql_update_current_rights) or die(mysqli_error($db_con));
					}
					else
					{
						$sql_assign_rights 				= "update tbl_assign_rights set ar_current_rights = '".$new_feature_id."' where ar_user_owner_id = '".$ar_user_owner_id."'";
						$result_assign_rights 			= mysqli_query($db_con,$sql_assign_rights) or die(mysqli_error($db_con));
					}
				}
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
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'parentwise')
{
	$sql_default_rights   	= "select * from tbl_admin_features where af_name = '".$_POST['parent']."' and af_parent_type = 'Parent' and af_status = '1'";
	$result_default_rights 	= mysqli_query($db_con,$sql_default_rights) or die(mysqli_error($db_con));
	$row_default_rights   	= mysqli_fetch_array($result_default_rights);	
	echo $row_default_rights['af_at_id'];
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
                                    <h3>
                                        <i class="icon-table"></i>
                                        <?php echo $feature_name; ?>
                                    </h3>
                                </div> <!-- header title-->
                                <div class="box-content nopadding">

                                    <form enctype="multipart/form-data"  id="addnewfeaturetype" name="addnewfeaturetype" method="POST" class='form-horizontal form-bordered form-validate'>				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Feature Name</label>
                                            <div class="controls">
                                                <input type="text" name="feature_name" id="feature_name"  class="input-xlarge" placeholder="Feature name" data-rule-required="true">
                                            </div>
                                        </div>
                                     
                                        <div class="control-group">
                                                <label for="textfield" class="control-label">Select Parent</label>
                                                <div class="controls">
                                                    <select name="parent" id="parent" placeholder="Parent Name"  onChange="show_parentwise_feature();"  class="select2-me input-large" data-rule-required="true">
                                                        <option value="">Select Parent</option>
                                                        <option value="parent">Parent</option>
                                                        <?php if($arr_test_topic != ""){ foreach ($arr_test_topic as $val) { ?><option value="<?php print $val['af_name']; ?>" ><?php print $val['af_name']; ?></option><?php } } ?>
                                                    </select><div id="divparent" style="font-size:10px;color:#F00"></div>
                                                </div>
                                        </div> 
                                         
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Page URL</label>
                                            <div class="controls">
                                                <input type="text" name="page_url" id="page_url"  class="input-xlarge" placeholder="Page URL" data-rule-required="false">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Status<br>(uncheck to deactivate)</label>
                                            <div class="controls">
                                                <input type="checkbox" id="featurestatus" name="featurestatus" value="1" class="css-checkbox" checked="checked">
                                                <label for="featurestatus" class="css-label"></label>
                                            </div>
                                        </div>
                                <div class="box-title">
                                    <h3><i class="icon-th-list"></i> This feature will by default allocated to following Admin type</h3>
                                </div>
                                <div id="div_feature">        
                                         <?php foreach($ar_admin_type as $key=>$vals) 
                                                { 
                                        ?>
                                        <div class="control-group">
                                                <label for="textarea" class="control-label"><?php print $vals['at_name']; ?></label>
                                            <div class="controls">
                                                <input type="checkbox" id="<?php print $vals['at_id']; ?>" name="adtype[]" disabled value="<?php print $vals['at_id']; ?>" class="css-checkbox cls_admin">
                                                <label for="<?php print $vals['at_id']; ?>" class="css-label"></label>
                                            </div>
                                        </div>
                                         <?php } ?>
                                 </div>       
                                     <div class="form-actions">
                                            <button type="submit" name="reg_submit" value="Create" class="btn-success">Create</button>
                                            <button type="button" class="btn" onClick="window.history.back()">Cancel</button>
                                    </div> <!-- Save and cancel -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- main actual content-->
                                
        </div>
    </div>
<script language="javascript" type="text/javascript">
	//This is for creating new feature type
	$('#addnewfeaturetype').on('submit', function(e) {
	e.preventDefault();
	if ($('#addnewfeaturetype').valid())
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
			$.post(location.href,{featurename:featurename,parent:parent,url:url,status:status,adtype:adtype,jsubmit:'addnewassigntype'},function(data){
				if (data.length > 0) 
				{
					$('input[name="reg_submit"]').removeAttr('disabled');
					alert(data);
				} 
				else 
				{
					alert("You have sucessfully added new feature.");
					window.location.replace('view_featuretype.php?pag=<?php echo $title; ?>','_self');
				}
				return false;
				});
		}
	}
	});
	function show_parentwise_feature()
	{
		$(".cls_admin").attr("disabled", true);
		$(".cls_admin").attr("checked", false);
		
		var parent	= $.trim($('select[name="parent"]').val());
		$.post(location.href, {parent:parent,jsubmit:'parentwise'}, function(data)
		{
			if(data != "")
			{
				
				var array = data.split(',');
				for (i = 0; i <= array.length; i++) 
				{
					$("#"+array[i]).attr("disabled", false);
					$("#"+array[i]).attr("checked", true);
            	}				
			}
			else
			{
				$(".cls_admin").attr("disabled", false);
			}
		});
	}
</script>
                    
</body>
</html>