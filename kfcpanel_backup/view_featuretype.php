<?php
include("include/routines.php");
//checkuser();
//chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
if(isset($_GET['pag']))
{
	$title = $_GET['pag'];
}
$path_parts   	= pathinfo(__FILE__);
$filename 	 	= $path_parts['filename'].".php";
$sql_feature 	= "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature = mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  	= mysqli_fetch_row($result_feature);
$feature_name 	= $row_feature[1];
$home_name    	= "Home";
$home_url 	  	= "view_dashboard.php?pag=Dashboard";

$ar_active_feature 		= array();
$ar_inactive_feature 	= array();
$start_offset   		= 0;
$start_offset_inactive	= 0;

if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'featurelist') 
{
	if(isset($_POST['page']) && $_POST['page']!="")
	{
		$page			= mysqli_real_escape_string($db_con,$_POST['page']);
		$cur_page 		= mysqli_real_escape_string($db_con,$_POST['page']);
	}
	else
	{
		$page			= mysqli_real_escape_string($db_con,$_POST['page']);		
		$cur_page 		= 1;
	}
	$page 	   	   -= 1;
	if(isset($_POST['row_limit']) && $_POST['row_limit']!="")
	{
		$per_page 		= mysqli_real_escape_string($db_con,$_POST['row_limit']);
	}
	else
	{
		$per_page 		= 10;
	}

	$start_offset += $page * $per_page;

	$start 			= $page * $per_page;
	$query_vlue 		= "SELECT * FROM tbl_admin_features where af_parent_type = 'Parent' ";
	if(isset($_POST['search_txt']) && $_POST['search_txt']!="")
	{
		$search_txt 		= trim(mysqli_real_escape_string($db_con,$_POST['search_txt']));
		$query_vlue 		.= " and (af_name like '%".$search_txt."%') ";
		$query_value		= $query_vlue. " and (af_name like '%".$search_txt."%') ";
	}
	else
	{
		$search_txt 		= "";							
	}
	//This is for active features
	$query_value		= $query_vlue." order by af_status desc,af_menu_order ASC  LIMIT $start, $per_page ";
	$res_active 		= mysqli_query($db_con,$query_value) or die(mysqli_error($db_con));
	$ar_ischild 		= array();	
	$val 				= "1";
	$count 				= dataPagination($query_vlue,$per_page,$start,$cur_page);	
	if(!$count=='0')
	{	
		?>
		<form method="post" id="assign_type_anyway_form" >
            <table id="tblactive" class="table table-bordered dataTable dataTable-scroll-x" width="98%">
				<thead>
              		<tr>
                		<th>Sr. No.</th>
                        <th>Feature Name</th>
                        <th>Parent</th>
                        <!--<th>Page URL</th>-->
                        <th>Order</th>
                        <th>Created Date</th>
                        <th>Created By</th>
                        <th>Modified Date</th>
                        <th>Modified By</th>
                        <th>Child</th>
                        <th>Edit</th>
                        <th>
                            <!--<input type="checkbox" id="selectall" />Select all<br>-->
                            <input type="button" class="btn-danger" value="In-Active" onClick="deactivate_feature()"/>
                        </th>
					</tr>
				</thead>
				<tbody>
			<?php 
				while($vals = mysqli_fetch_array($res_active))
              	{
					$start_offset++;  
					//To check whether child is available or not
					$af_name 			= $vals['af_name'];
					$ar_ischild 		= array();
					$sql_ischild 		= "select * from tbl_admin_features where af_parent_type = '".$af_name."' and af_status = '1'";
					$res_ischild  		= mysqli_query($db_con,$sql_ischild) or die(mysqli_error($db_con));
					while($row_ischild = mysqli_fetch_array($res_ischild))
					{
						$ar_ischild[] = $row_ischild['af_name'];
					}					
					$count_row_ischild = count($ar_ischild);		  
		  			?>
					<tr  <?php if($vals['af_status']==0){ echo 'style="background-color:#CCC"';} ?> > 
                    	<td><?php print $start_offset; ?>			</td>
                    	<td>
                    		<?php
                        	if($vals['af_status'] != 0)
							{
								?>
                            	<a href="#" title=""><?php echo $vals['af_name']; ?></a>
                            	<?php
							}
							else
							{
                            	echo $vals['af_name'];
							}
							?>
						</td>
                    	<td><?php echo $vals['af_parent_type']; ?>	</td>
                    	<td style="text-align:center">
                    	<?php 
                    		if($vals['af_status'] != 0)
							{  
								?>
                        		<input type="text" id="<?php echo $vals['af_id']; ?>" name="<?php echo $vals['af_id']; ?>" class="input-mini" onBlur="changeOrder(this.id,this.value)" value="<?php echo $vals['af_menu_order']; ?>" size="2" maxlength="2" style="text-align:center" />
                        		<?php 
							}
						?> 
                    	</td>
                    	<td style="text-align:center"><?php echo $vals['createddt']; ?></td>
                    	<td><?php echo $vals['createdby']; ?></td>
                    	<td style="text-align:center"><?php echo $vals['modifieddt']; ?></td>
                    	<td><?php echo $vals['modifiedby']; ?></td>
                    	<td>
                    	<?php 
                    		if($vals['af_status']==0)
							{ 
                    
                    		}
							else 
                    		{ 
								if($count_row_ischild != 0)
								{
									 ?>
                                     	<a href="view_childfeaturetype.php?af_name=<?php echo $vals['af_name'] ?>" title="View Child">View Child</a>
									 <?php 
								} 
							}
						?>
                        </td>
                    	<td>
                    	<?php 
                    	if($vals['af_status']==0)
						{ 
                    	
                    	}
						else 
                    	{ 
							?>
                    			<div align="center" style="margin-right:5px">
                                	<a href="edit_featuretype.php?af_id=<?php echo $vals['af_id'] ?>" title="Edit"  class="icon-edit">Edit</a>
								</div>
                   			<?php  
						} 
						?>
                        </td>                        
                    	<td>
                    	<?php 
							if($vals['af_status']==0)
							{ 
                     			?>
                                	<div align="center" style="margin-right:5px">
                       					<input type="button" class="btn-success" value="Active" onClick="activate_feature('<?php echo $vals['af_id'] ?>')"/>
                     				</div> 
								<?php
                    		}
							else 
                    		{ 
								?>
                        			<div class="controls" align="center">
                            			<input type="checkbox" value = "<?php print $vals['af_id']; ?>" id="batch<?php print $vals['af_id']; ?>" name="batch<?php print $vals['af_id']; ?>"  class="css-checkbox batch">
                            			<label for="batch<?php print $vals['af_id']; ?>" class="css-label"></label>
                        			</div> 
								<?php 
							} 
						?>
                    </td>			
                	</tr>
             		<?php 												  	
				} 
			?>
			</tbody>
            </table>
        </form>
		<?php echo $count;
	}
	else 
	{
		echo "Records not found!!!";	
	}
	exit(0);
}
//------------------------------------------------------------------------------------------------------------------
// Start : On menu order is changed
//------------------------------------------------------------------------------------------------------------------
if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'change_order') 
{
	$id 						= mysqli_real_escape_string($db_con,$_POST['id']);
	$newval 					= mysqli_real_escape_string($db_con,$_POST['new_order']);
	$sql_of_selected_feature 	= "select * from `tbl_admin_features` where `af_id`='".$id."'";
	$result_of_selected_feature	= mysqli_query($db_con,$sql_of_selected_feature) or die(mysqli_error($db_con));
	$row_of_selected_feature 	= mysqli_fetch_array($result_of_selected_feature);
	$parent  				 	= $row_of_selected_feature['af_parent_type'];
	
	//This is for only parent features
	if($parent == "Parent")
	{
		$sql_of_parent_with_same_order 		= "select * from tbl_admin_features where af_parent_type = 'Parent' and af_menu_order = '".$newval."' and af_status = '1'";
		$result_of_parent_with_same_order 	= mysqli_query($db_con,$sql_of_parent_with_same_order) or die(mysqli_error()); 
		$row_of_parent_with_same_order 		= mysqli_fetch_array($result_of_parent_with_same_order);
		$num_row_of_parent_with_same_order 	= mysqli_num_rows($result_of_parent_with_same_order);
		if($num_row_of_parent_with_same_order != 0)
		{
			$sql_update_admin_features1		= " Update tbl_admin_features set `af_menu_order` = '".$newval."', `modifieddt` = '".$datetime."',";
			$sql_update_admin_features1		.= " `modifiedby` = '".$_SESSION['panel_user']['fullname']."' where `af_id` = '".$id."'";
			$result_update_admin_features1 	= mysqli_query($db_con,$sql_update_admin_features1) or die(mysqli_error($db_con));
			$sql_update_admin_features2		= " Update tbl_admin_features set `af_menu_order` = '".$row_of_selected_feature['af_menu_order']."',";
			$sql_update_admin_features2		.= " `modifieddt` = '".$datetime."', `modifiedby` = '".$_SESSION['panel_user']['fullname']."' where `af_id` = '".$row_of_parent_with_same_order['af_id']."'";
			$result_update_admin_features2 	= mysqli_query($db_con,$sql_update_admin_features2) or die(mysqli_error($db_con));			
		}
		else
		{
			$sql_update_admin_features		= " Update tbl_admin_features set `af_menu_order` = '".$newval."', `modifieddt` = '".$datetime."',";
			$sql_update_admin_features		.= " `modifiedby` = '".$_SESSION['panel_user']['fullname']."' where `af_id` = '".$id."'";
			$result_update_admin_features 	= mysqli_query($db_con,$sql_update_admin_features) or die(mysqli_error($db_con));			
		}
	}	
	exit(0);
}
//------------------------------------------------------------------------------------------------------------------
// End : On menu order is changed
//------------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------------------------
// Start : On feature deactivate
//------------------------------------------------------------------------------------------------------------------
if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'deactivate') 
{
	$single_rights 	= array();
	//$ar_af_id		= array();
	if(isset($_POST['batch']) && $_POST['batch'] != "")
	{
		$ar_af_id 		= $_POST['batch'];
		
		
		foreach($ar_af_id as $af_id)
		{
			//To inactivate the feature
			$sql_update_admin_features 		= " update tbl_admin_features set af_status = '0',af_menu_order = '0', modifieddt = '".$datetime."', modifiedby = '".$_SESSION['panel_user']['fullname']."' where af_id = '".$af_id."'";			
			$result_update_admin_features 	= mysqli_query($db_con,$sql_update_admin_features) or die(mysqli_error($db_con));
			
			//To check whether deleted rights assigned to any user 
			$sql_chk_feature_assigned   	= "select * from tbl_assign_rights where find_in_set('$af_id',ar_current_rights)<> 0";
			$result_chk_feature_assigned 	= mysqli_query($db_con,$sql_chk_feature_assigned) or die(mysqli_error($db_con));
			if($result_chk_feature_assigned)//if feature is aleady assinged to any user
			{
				while($row_chk_feature_assigned = mysqli_fetch_array($result_chk_feature_assigned))
				{
					$ar_id 				= $row_chk_feature_assigned['ar_id'];
					$ar_current_rights 	= $row_chk_feature_assigned['ar_current_rights']; //Get current rights of user
					
					if($row_chk_feature_assigned['ar_history_rights'] != "")	//If history rights is not empty then append
					{
						$arr = explode(",",$row_chk_feature_assigned['ar_history_rights']);
						if(!(in_array($af_id,$arr)))
						{
							$ar_history_rights 	= $row_chk_feature_assigned['ar_history_rights'].",".$af_id;
						}
						else
						{
							$ar_history_rights 	= $row_chk_feature_assigned['ar_history_rights'];
						}						
					}
					else
					{
						$ar_history_rights 	= $af_id;
					}
					
					$single_rights 		= explode(",",$ar_current_rights);	//Save each right
					
					if($single_rights[0] == $af_id && $single_rights[1] == "")
					{
						$ar_current_rights = str_replace($af_id,"",$ar_current_rights);
					}
					else if($single_rights[0] == $af_id && $single_rights[1] != "")
					{
						$ar_current_rights = str_replace($af_id.",","",$ar_current_rights);
					}
					else
					{
						$ar_current_rights = str_replace(",".$af_id,"",$ar_current_rights);
					}
					$sql_update_assign_rights 		= "update tbl_assign_rights set ar_current_rights = '".$ar_current_rights."', ar_history_rights = '".$ar_history_rights."', modifieddt = '".$datetime."', modifiedby = '".$_SESSION['panel_user']['fullname']."' where ar_id = '".$ar_id."'";
					$result_update_assign_rights 	=  mysqli_query($db_con,$sql_update_assign_rights) or die(mysqli_error($db_con));
				}//while($row_chk_feature_assigned = mysql_fetch_array($res_chk_feature_assigned))
			}//if($res_chk_feature_assigned)
		}//foreach($ar_af_id as $af_id)
	}
	?>
	<?php
		exit(0);
}
//------------------------------------------------------------------------------------------------------------------
// End : On feature deactivate
//------------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------------------------
// Start : On feature Activate
//------------------------------------------------------------------------------------------------------------------
if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'activate') 
{
	if(isset($_POST['id']) && $_POST['id'] != "")
	{
		$af_id = $_POST['id'];	
		$sql_assign_rights 		= " SELECT ar_id,ar_current_rights,ar_history_rights FROM `tbl_assign_rights` ";
		$result_assign_rights 	= mysqli_query($db_con,$sql_assign_rights) or die(mysqli_error($db_con));
		while($row_assign_rights = mysqli_fetch_array($result_assign_rights))
		{
			$arr = explode(",",$row_assign_rights['ar_history_rights']);
			if(in_array($af_id,$arr))
			{
				$str = ",".$af_id.",";
				$ar_current_rights = $row_assign_rights['ar_current_rights'].",".$af_id;
				//$ar_history_rights = $row['ar_history_rights'];
				//str_replace($str,",",$ar_history_rights);
				
				$sql_update_assign_rights1  	= " UPDATE tbl_assign_rights SET `ar_current_rights`= '".$ar_current_rights."' ";
				$sql_update_assign_rights1 		.= ",`modifieddt`='".$datetime."', `modifiedby`='".$_SESSION['panel_user']['fullname']."' WHERE  ar_id = '".$row_assign_rights['ar_id']."' ";
				$result_update_assign_rights1	= mysqli_query($db_con,$sql_update_assign_rights1) or die(mysqli_error($db_con));
			}
		}	
		$sql_update_assign_rights 		= " UPDATE tbl_admin_features set af_status = '1', modifieddt = '".$datetime."', modifiedby = '".$_SESSION['panel_user']['fullname']."' where af_id = '".$af_id."'";
		$result_update_assign_rights 	= mysqli_query($db_con,$sql_update_assign_rights) or die(mysqli_error($db_con));
	}
	exit(0);
}
//------------------------------------------------------------------------------------------------------------------
// End : On feature Activate
//------------------------------------------------------------------------------------------------------------------
?>    
<!doctype html>
<html>
<head>
<?php 
	/* This function used to call all header data like css files and links */
	headerdata($feature_name);
	/* This function used to call all header data like css files and links */
	
	?>
    <script>
function changeOrder(id,new_order)
{
	loading_show();
	$.post(location.href,{id:id,new_order:new_order,jsubmit:'change_order'},function(data)
		{
			loadData();
			loading_hide();
		});		
}
function loadData()
{
	loading_show();
	var search_txt 	= $('input[name="srch"]').val();
	var row_limit 	= $('select[name="rowlimit"]').val();
	var page 	= $('#hid_page').val();
	if(page == "")
	{
		page =1;	
	}
	$.post(location.href,{page:page,row_limit:row_limit,search_txt:search_txt,jsubmit:'featurelist'},function(data)
		{
			$("#container1").html(data);
			loading_hide();
		});	
} 
$( document ).ready(function() {
		$('#srch').keypress(function(e) {
		if(e.which == 13) 
		{		
       	   	loadData();
		}
	});
	loadData();	
	$('#container1 .pagination li.active').live('click',function(){
		var page 				= $(this).attr('p');
		$('#hid_page').val(page);
		loadData();
	});
});
	</script>
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
                                    <h3 style="margin-right:50px"><i class="icon-th-list"></i><?php echo $feature_name; ?></h3>
                                    <input type="hidden" name="hid_page" id="hid_page" value="">
                                </div>
                                <div class="box-content nopadding">
                                    <div style="padding:10px 15px 10px 15px !important">
                                        <select name="rowlimit" id="rowlimit" onChange="loadData();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page    
                                    <?php
//											$add = checkFunctionalityRight($filename,0);
                                    $add=1;
											if($add)
											{
												?>
                                                <a  href="add_featuretype.php?pag=FeatureType" class="btn-info" style="text-decoration:none;color:white;margin-top:20px;"> <i class="icon-plus"></i>&nbsp Add Feature</a>
                                                <?php		
											}
									?>                                                                                                                           
                                        <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Search here..."   style="float:right;margin-right:10px;margin-top:10px;" >
                                    </div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="center">
                                            <div id="loading"></div>
                                            <div id="container1" class="data_container">
                                                <div class="data"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- main actual content-->
                        </div>
                    </div>                    
               </div> <!--right side main content panel-->
            </div> <!--main content area-->
        </div>
	</div>  
       <?php getloder();?>
       <script type="text/javascript">
			
			//============================================
			//This is function for activating status
			//============================================
			function activate_feature(id)
			{		
				$.post(location.href, {id:id,jsubmit:'activate'}, function(data)
				{
					$('table#tblactive tbody').html(data);
					loadData();
					});
			}
		
			//============================================
			//This is function for de-activating status
			//============================================
			function deactivate_feature()
			{	
				var batch = []
				$(".batch:checked").each(function ()
				{
					batch.push(parseInt($(this).val()));
				});
		
				$.post(location.href, {batch:batch,jsubmit:'deactivate'}, function(data)
				{
					$('table#tblactive tbody').html(data);
					loadData();
				});
			}
		  //This is for changing menu order
			 $('.menuorder').on('blur',function(){
				var id 		= $(this).attr("id");
				var newval 	= $(this).attr("value");
				$.post(location.href, {id:id,newval:newval,jsubmit:'change_order'}, function(data)
				{
					$('table#tblactive tbody').html(data);
					loadData();
				});
			});
			
			//This is for disable enter key
			$('html').bind('keypress', function(e)
			{
			   if(e.keyCode == 13)
			   {
				  return false;
			   }
			});
			
			//============================================
			//This is function for activating status
			//============================================
	</script>
</body>
</html>

