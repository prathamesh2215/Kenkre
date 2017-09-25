<?php
include("include/routines.php");
checkuser();
$title  		= "User";
$filename 		= "edit_user.php";
$home_url 		= "view_dashboard.php?pag=Dashboard";
$home_name 		= "Home";
$feature_name 	= "Update User";
$id		= $_GET['id'];
$utype  = $_SESSION['panel_user']['utype'];
//-----------------------------------------------------
// Fetching all admin types -------- yogesh 10 jan 2015
//-----------------------------------------------------
$type_of_admin = array();
$sql_admin_type 	= "select * from tbl_admin_type where ";
if($utype = '1')
{
	$sql_admin_type 	.= " at_id >= '".$utype."' ";
}
$result_admin_type 		= mysqli_query($db_con,$sql_admin_type) or die(mysqli_error($db_con));
while($row_admin_type 	= mysqli_fetch_array($result_admin_type))
{
	$type_of_admin[$row_admin_type['at_id']] = $row_admin_type;
}
//-----------------------------------------------------
// Fetching all user owner 
//-----------------------------------------------------
$owner_name 			= array();
$ar_history_rights 		= array();
$ar_history_db_rights 	= array();
$sql_owner				= "select * from tbl_users_owner";
$result_owner 			= mysqli_query($db_con,$sql_owner) or die(mysqli_error($db_con));
while($row_owner = mysqli_fetch_array($result_owner))
{
	$owner_name[$row_owner['id']] = $row_owner;
}
//-----------------------------------------------------
// Fetching all feature types ------yogesh 10 jan 2015
//-----------------------------------------------------
$ar_features_type 		= array();
$sql_feature_type 		= "select * from tbl_admin_features where af_status = '1' and af_parent_type = 'Parent' order by af_menu_order";
$result_feature_type 	= mysqli_query($db_con,$sql_feature_type) or die(mysqli_error($db_con));
while($row_features_type = mysqli_fetch_array($result_feature_type))
{
	$ar_features_type[] = $row_features_type;
}
//-----------------------------------------------------
// Fetching current assigned rights
//----------------------------------------------------
$sql_rights 			= "select * from tbl_assign_rights where ar_user_owner_id = '".$id."'";
$result_rights 			= mysqli_query($db_con,$sql_rights) or die(mysqli_error($db_con));
$row_rights 			= mysqli_fetch_array($result_rights);
$ar_current_rights		= $row_rights['ar_current_rights'];
$seperate_1 			= explode("{",$ar_current_rights);
//print_r($parent_rights);
$parent_1 = array();
$child_2 = array();
foreach($seperate_1 as $part)
{
	$parent_rights 			= explode(":",$part);
	array_push($parent_1,$parent_rights[0]);
	array_push($child_2,$parent_rights[1]);
}
//-----------------------------------------------------
// Start : This is for showing existing data for update
//-----------------------------------------------------
if(isset($_GET['id']) && $_GET['id'])
{
	$id 				= mysqli_real_escape_string($db_con,$_GET['id']);
	$sql_cadmin 		= "select * from `tbl_cadmin_users` where `id`='".$id."'"; 
	$result_cadmin 		= mysqli_query($db_con,$sql_cadmin) or die(mysqli_error($db_con));
	$row_cadmin 		= mysqli_fetch_array($result_cadmin);	
}
//---------------------------------------------------
// End : This is for showing existing data for update
//---------------------------------------------------
//-----------------------------------------------------
// Fetching all sms packages
//-----------------------------------------------------
$ar_sms_package  		= array();
$sql_sms_pac 			= "select * from tbl_sms_package";
$result_sms_package 	= mysqli_query($db_con,$sql_sms_pac) or die(mysqli_error($db_con));
while($row_sms_package 	= mysqli_fetch_array($result_sms_package))
{
	$ar_sms_package[] = $row_sms_package;
}
//------------------------------------------------------------------------------------------------
// Start : This is for hide/show sms and feature status
//------------------------------------------------------------------------------------------------
$ar_owners_sms_pkg = array();
$ar_owners_sms_pkg_start_date = array();
if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'categorywise' && isset($_POST['user_type'])) 
{
	$at_id 			= mysqli_real_escape_string($db_con,$_POST['user_type']);
	$sqladmintype	= "select * from tbl_admin_type where at_id='".$at_id."'";
	$resadmintype   = mysqli_query($db_con,$sqladmintype) or die(mysqli_error($db_con));
	$rowadmintype	= mysqli_fetch_array($resadmintype);
	
	if($rowadmintype['at_smsstatus']=='1') 
	{
		$sod_user_owner_id 		= $id;
		$sql_owners_sms_pkg		= " select sod.sod_startdt,sod.sod_enddt,sod.sod_num_of_sms,sp.sp_package_name from tbl_sms_owner_details as sod join ";
		$sql_owners_sms_pkg		.= " tbl_sms_package as sp on sp.sp_id = sod.sod_package_id where sod.sod_user_owner_id = '".$sod_user_owner_id."' order by sod.sod_id desc limit 1";
		$result_owners_sms_pkg 	= mysqli_query($db_con,$sql_owners_sms_pkg) or die(mysqli_error($db_con));
		$row_owners_sms_pkg 	= mysqli_fetch_array($result_owners_sms_pkg);
		
		if(isset($row_owners_sms_pkg['sod_enddt']))
		{
			$ar_owners_sms_pkg 				= explode("-",$row_owners_sms_pkg['sod_enddt']);//yyyy-mm-dd
			$owner_sms_date    				= $ar_owners_sms_pkg[0]."-".$ar_owners_sms_pkg[1]."-".$ar_owners_sms_pkg[2];
			$ar_owners_sms_pkg_start_date 	= explode("-",$row_owners_sms_pkg['sod_startdt']);//yyyy-mm-dd
			$owner_sms_start_date    		= $ar_owners_sms_pkg_start_date[0]."-".$ar_owners_sms_pkg_start_date[1]."-".$ar_owners_sms_pkg_start_date[2];
		}
		else
		{
			$owner_sms_date = "";
		}
		if(date("Y-m-d") >= $owner_sms_date) //If SMS Package expired
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
                        <input type='checkbox' name='smsstatus' class="class_facilities" 	id='smsstatus' 	 value='1' onChange="show_sms_div();" />
                    </div>
                </div>
                <div class="control-group">
                    <label for="textarea" class="control-label">Email</label>
                    <div class="controls">
                        <input type='checkbox' class="class_facilities" name='emailstatus' 	id='emailstatus' <?php if($row_cadmin['sms_status'] == '1'){ ?>checked<?php } ?> value='1' onChange="emailfeature();" />
                    </div>
                </div>
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
                        <td colspan="2">
                            <table width="98%">
                              <tr>
                                <td width="64%"><b>From Date : </b><input type="text" name="start_date" id="start_date" class="form-control datepicker input-small" readonly ></td>
                                <td width="36%"><input type="text" name="end_date" id="end_date" size="10" class="form-control datepicker input-small" readonly hidden disabled="disabled"><!--<div id="end_date" style="float:left"></div>--></td>
                              </tr>
                            </table>
                        </td>
                      </tr>
                    </table>
                </div>
                <div id="divsmspackagedaterange"></div>
            </td>
          </tr>
        </table> <!-- Facilities -->
		<?php
		}
		else	//If SMS Package days remaining Renewal 
		{
			$date			= strtotime($owner_sms_date);
			if(strtotime($owner_sms_start_date) <= time())
			{
				$diff			= $date-time();
			}
			else
			{
				$diff			= $date-strtotime($owner_sms_start_date);
			}
			$daysRemaining	= floor($diff/(60*60*24));
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
            <h3><i class="icon-th-list"></i>Facilities</h3>
            <span style="font-size:14px;color:#fff;font-style:italic;padding-left:50px;vertical-align:middle">
			<?php if($daysRemaining < '0'){echo "";}else{echo "(SMS Pack will expire in <span style='color:#000000'>".$daysRemaining."</span> days)"; }?>
            </span>
        </div>  <!-- Facilities Title -->
        <table width="100%">
          <tr>
            <td width="20%">
                <div class="control-group">
                    <label for="textarea" class="control-label">SMS</label>
                    <div class="controls">
                        
			<?php
                    echo "<input type='checkbox' class='class_facilities' name='smsstatus' id='smsstatus' value='1' ";
                    if($row_cadmin['sms_status'] == '1')
                    {echo "checked disabled='disabled'"; } 
                    echo " onchange='show_sms_div();'/>"; 
					$sql_sms_ren   	= "select * from tbl_sms_owner_details where sod_user_owner_id = '".$_REQUEST['id']."' and `sod_startdt` <= DATE('".$datetime."') and `sod_enddt` >= DATE('".$datetime."')";
					$result_sms_ren = mysqli_query($db_con,$sql_sms_ren) or die(mysqli_error($db_con));
					$num_rows_sms_ren = mysqli_num_rows($result_sms_ren);
					if($num_rows_sms_ren != 0)
					{
						$row_sms_ren = mysqli_fetch_array($result_sms_ren);
					}
            ?>
                    </div>
                </div> <!-- SMS status -->
                 
                <?php //if($tot_sms_ren == 0)
						{  ?>    
                <div class="control-group">
                    <label for="textarea" class="control-label">Renewal</label>
                    <div class="controls">
                        <input type='checkbox'  class="class_facilities" name='renewal' id='renewal'  value='1' onChange="show_sms_renwal_div();" /> 
                    </div>
                </div> <!-- Renewal status -->
                <?php }
					  //else
					  {  
					  ?>
                        <div class="control-group">
                            <label for="textarea" class="control-label">Topup</label>
                            <input type="hidden" id="hid_rem_sms" name="hid_rem_sms" value="<?php echo $row_sms_ren['sod_num_of_sms']; ?>" >
                            <div class="controls">
                            	<table>
                                  <tr>
                                    <td rowspan="2">+</td>
                                    <td align="right"><?php echo $row_sms_ren['sod_num_of_sms']; ?></td>
                                  </tr>
                                  <tr>
                                    <td><input type="text" id="topup" name="topup" class="input-mini" onKeyUp="showcount(this.value);" onKeyPress="return isNumberKey(event);" maxlength="5" placeholder="0" value="0" style="text-align:right;"  autocomplete="off"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" colspan="2" style="background-color:#99FF99"><span id="total_sms"><?php echo $row_sms_ren['sod_num_of_sms']; ?></span></td>
                                  </tr>
                                </table>
                            </div>
                        </div> <!-- Topup -->
                      
               <?php  } ?>
                    
                <div class="control-group">
                            <label for="textarea" class="control-label">Email</label>
                            <div class="controls">
                <?php
                            echo "<input type='checkbox'  class='class_facilities' name='emailstatus' id='emailstatus' value='1' ";
                            if($row_cadmin['email_status'] == '1')
                            {echo "checked"; }
                            echo " onChange='emailfeature();' />";
                ?>
                            </div>
                </div> <!-- Email status -->
           </td>
           <td width="25%">
                <table width="100%" border="1">
                  <thead>
                        <tr><td colspan="2" align="center" bgcolor="#329EA9" style="color:#FFF">Existing Package Details</td></tr>
                    </thead>
                  <tr>
                    <td width="40%">Package</td>
                    <td bgcolor="#FFFFFF" style="padding-left:10px;color:#999"><?php echo $row_owners_sms_pkg['sp_package_name'];?></td>
                  </tr>
                  <tr>
                    <td>From Date</td>
                    <td bgcolor="#FFFFFF" style="padding-left:10px"><?php echo $row_owners_sms_pkg['sod_startdt'];?></td>
                  </tr>
                  <tr>
                    <td>To Date</td>
                    <td bgcolor="#FFFFFF" style="padding-left:10px">
                    <?php echo $row_owners_sms_pkg['sod_enddt'];?>
                    <input type="hidden" id="prev_enddate" name="prev_enddate" value="<?php echo $row_owners_sms_pkg['sod_enddt'];?>"></td>
                  </tr>
                  <tr>
                    <td>Number OF SMS</td>
                    <td bgcolor="#FFFFFF" style="padding-left:10px"><?php echo $row_owners_sms_pkg['sod_num_of_sms'];?></td>
                  </tr>
                </table> <!-- Existing package details -->
           </td>
           <td>
                <table width="100%">
                  <tr>
                    <td style="vertical-align:top" width="50%">
                        <div id="divsmspackage" style="width:99%;vertical-align:top;float:right">
                            <table width="80%" id="maintable" bgcolor="#F22871">
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
                        </div>
                    </td>
                    <td width="50%">
                        <div id="divsmspackagesettingname"></div>
                        <div id="divsmspackagesettingvalidity"></div><br />
                        <div id="divsmspackagedaterange">
                            <table width="98%">
                              <tr>
                                <td><b>From Date : </b><input type="text" name="start_date" id="start_date" size="10" class="form-control datepicker input-small" readonly  ></td>
                                <td style="vertical-align:bottom">
                                    <input type="text" name="end_date" id="end_date" size="10" class="form-control datepicker input-small" disabled="disabled" readonly hidden ><!--<div id="end_date" style="float:left"></div>-->
                                </td>
                              </tr>
                            </table>
                        </div>
                    </td>
                  </tr>
                </table> <!-- Renewal of package -->
           </td>
         </tr>
        </table>  <!-- Facilities -->
        <?php
		}
	}
	else
	{
		echo "";
	}
	
	if($rowadmintype['at_featurestatus']=='1')
	{
		?>
        <div class="box-title">
            <h3><i class="icon-th-list"></i>Specify Rights</h3>
        </div>
		<?php
			foreach($ar_features_type as $vals) 
			{
				$ar_af_child 			= array();
				$sql_parent_feature 	= "select * from `tbl_admin_features` where `af_parent_type`='".$vals['af_name']."'";
				$result_parent_feature 	= mysqli_query($db_con,$sql_parent_feature) or die(mysqli_error($db_con));
				while($row_parent_feature	= mysqli_fetch_array($result_parent_feature))	
				{
					$ar_af_child[] =  $row_parent_feature;
				}
				$ar_af_child_count = count($ar_af_child);
				?>		
					<div style="width:100%">
                    	<div class="control-group" style="background-color:#329EA9" >
                        	<label for="textarea" class="control-label"><?php echo $vals['af_name']; ?></label>
                    	<div class="controls">
                       		<input type="checkbox" id="<?php echo $vals['af_id']; ?>_par" class="css-checkbox" value="<?php echo $vals['af_id']; ?>_par"        
                       		<?php 
					   		if(in_array($vals['af_id'],$parent_1))
							{
								echo "checked";
							}
							?> 
                       		name="featuretype[]" onChange="parent_checked(this.id,'<?php echo str_replace(' ', '_', $vals['af_name']); ?>');accessTypeChecked(this.id)" >
                        	<label for="<?php echo $vals['af_id']; ?>_par" class="css-label"></label>
                        	<br>
                            <?php 
					   		if(in_array($vals['af_id'],$parent_1))
							{
								$key = array_search($vals['af_id'],$parent_1);
							}
							$child_main = explode(",",$child_2[$key]);
							?>                    
	                    <div id="access<?php echo $vals['af_id'];?>_par" style="display:<?php if(in_array($vals['af_id'],$parent_1)){ echo "block"; }else{echo "none";} ?>" class="<?php echo str_replace(' ', '_', $vals['af_name']);?>">
							<input type="checkbox"	id="add<?php echo $vals['af_id'];?>_par" 	class="<?php echo str_replace(' ', '_', $vals['af_parent_type']);?>"	<?php if($child_main[0]== 1){echo "checked";}?> value="Add" />Add
							<input type="checkbox" 	id="edit<?php echo $vals['af_id'];?>_par" 	class="<?php echo str_replace(' ', '_', $vals['af_parent_type']);?>"	<?php if($child_main[1]== 1){echo "checked";}?> value="Edit" />Edit
							<input type="checkbox" 	id="del<?php echo $vals['af_id'];?>_par" 	class="<?php echo str_replace(' ', '_', $vals['af_parent_type']);?>"	<?php if($child_main[2]== 1){echo "checked";}?> value="Delete" />Delete                                             
							<input type="checkbox" 	id="dis<?php echo $vals['af_id'];?>_par" 	class="<?php echo str_replace(' ', '_', $vals['af_parent_type']);?>"	<?php if($child_main[3]== 1){echo "checked";}?> value="Disable" /> Disable 
                    </div>   
                    </div>                 
                    </div>
                   
				<div style="width:100%">
       <?php 
	   		foreach($ar_af_child as $child)
			{
				$ar_af_at_id = array();
				?>
				<div style="width:25%;float:left;height:60px;background-color:#F6F6F6;border:#000;padding:20px 0px;">
					 <?php
						$ar_af_at_id = explode(",",$row_rights['ar_current_rights']);
					 ?>
                    <div style="text-align:center">
                        <div style="text-align:center"><?php echo $child['af_name']; ?></div>
                        <input type='checkbox' id="<?php echo $child['af_id'];?>_att" class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>_child" name='childfeaturetype[]' value='<?php echo $child['af_id'];?>_att'
                       <?php 
					   $key;
					   	if(in_array($child['af_id'],$parent_1))
						{
							echo "checked";
						}
						?> 
                         onChange="child_checked(this.id,'<?php echo $vals['af_id']; ?>','<?php echo str_replace(' ', '_', $child['af_parent_type']);?>','<?php echo $ar_af_child_count; ?>');accessTypeChecked(this.id);"  />
                    </div>
                        <br>
                                           <?php 
					   	if(in_array($child['af_id'],$child))
						{
							$key = array_search($child['af_id'],$parent_1);	
													
						}
						$child_main = explode(",",$child_2[$key]);					
						?>            
                    <div id="access<?php echo $child['af_id'];?>_att" style="display:<?php if(in_array($vals['af_id'],$parent_1)){ echo "block"; }else{echo "none";} ?>" 	class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>">
						<input type="checkbox" 	id="add<?php echo $child['af_id'];?>_att" 	class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>" <?php  	if(in_array($child['af_id'],$child) && strcmp($child_main[0],"1") == 0) {	echo "checked";	}	?>	value="Add"		/>Add
						<input type="checkbox"  id="edit<?php echo $child['af_id'];?>_att" 	class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>" <?php  	if(in_array($child['af_id'],$child) && $child_main[1]== 1) {	echo "checked";	} 	?> 	value="Edit"	/>Edit
						<input type="checkbox"  id="del<?php echo $child['af_id'];?>_att" 	class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>" <?php  	if(in_array($child['af_id'],$child) && $child_main[2]== 1) {	echo "checked";	} 	?> 	value="Delete"	/>Delete                                             
						<input type="checkbox"  id="dis<?php echo $child['af_id'];?>_att" 	class="<?php echo str_replace(' ', '_', $child['af_parent_type']);?>" <?php 	if(in_array($child['af_id'],$child) && $child_main[3]== 1) {	echo "checked";	} 	?> 	value="Disable"	/> Disable                       
                    </div>
                   </div>
				</div>
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
		?>
			</div>
		</div><!-- Width 100% -->
        <div style="clear:both"></div>
        <?php
		}
		?>
        <div style="clear:both"></div>
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
// For updating existing entry of user
//-----------------------------------------------------
$new_ar_history_rights = "";
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "updateuser" && isset($_POST['fullname']) && isset($_POST['emailid']) && isset($_POST['mobile']))
{
	$error_msg = "";
	if($_POST['fullname'] != "" && $_POST['emailid'] != "" /*&& $_POST['password'] != ""*/ && $_POST['mobile'] != "")
	{
		$fullname 	= mysqli_real_escape_string($db_con,$_POST['fullname']);
		$email 		= mysqli_real_escape_string($db_con,$_POST['emailid']);
		//$password 	= mysqli_real_escape_string($db_con,$_POST['password']);
		$mobile_num = mysqli_real_escape_string($db_con,$_POST['mobile']);
	}
	else
	{ 
		$error_msg .= "Please fill all details \n"; 
	}
	
	$featuretype = array();
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
	
	if($error_msg == "" && $_POST['user_type'] != "Student" && $_POST['user_type'] != "0" && $_POST['user_owner'] != "0")
	{
		$sql_cadmin_data		= "SELECT * FROM `tbl_cadmin_users` WHERE `email`='".$email."' and `id`!='".$id."'";
		$result_cadmin_data 	= mysqli_query($db_con,$sql_cadmin_data) or die(mysqli_error($db_con));
		$num_rows_cadmin_data 	= mysqli_num_rows($result_cadmin_data);
		if($num_rows_cadmin_data > 1)
		{
			print "User ID (Emailid) is already exist.\n";
		} 
		else
		{	
			$utype 			 = mysqli_real_escape_string($db_con,$_POST['user_type']);
			$tbl_users_owner = mysqli_real_escape_string($db_con,$_POST['user_owner']);
			$sms_status 	 = mysqli_real_escape_string($db_con,$_POST['smsstatus']);
                        //$sms_status 	 = 1;

                        
			$email_status 	 = mysqli_real_escape_string($db_con,$_POST['emailstatus']);
			if(isset($_POST['renewal']))
			{
				$renewal = mysqli_real_escape_string($db_con,$_POST['renewal']);
			}
			else
			{
				$renewal = "";
			}
                      
			if((isset($_POST['start_date']) && $_POST['start_date'] != "" && isset($_POST['end_date']) && $_POST['end_date'] != "" && isset($_POST['pkgid']) && $_POST['pkgid'] != "" && $renewal == '1' && $sms_status == '1' && $_POST['isDisabled'] == 'true')||
			(isset($_POST['start_date']) && $_POST['start_date'] != "" && isset($_POST['end_date']) && $_POST['end_date'] != "" && isset($_POST['pkgid']) && $_POST['pkgid'] != "" && $sms_status == '1' && $_POST['isDisabled'] == 'false'))
			{
				$sod_user_owner_id 	= $id;
				$sod_package_id  	= mysqli_real_escape_string($db_con,$_POST['pkgid']);
				$org_sod_startdt 	= explode("/",$_POST['start_date']);
				$sod_startdt 	 	= $org_sod_startdt[2]."-".$org_sod_startdt[1]."-".$org_sod_startdt[0];
				$org_sod_enddt		= explode("/",$_POST['end_date']);
				$sod_enddt 		 	= $org_sod_enddt[2]."-".$org_sod_enddt[1]."-".$org_sod_enddt[0];				
				
				$sql_selected_sms_pkg 	= "select * from tbl_sms_package where sp_id = '".$sod_package_id."'";
				$result_selected_sms_pkg= mysqli_query($db_con,$sql_selected_sms_pkg) or die(mysqli_error($db_con));
				$row_selected_sms_pkg 	= mysqli_fetch_array($result_selected_sms_pkg);
				
				$sql_previous_sms_pkg 	= "select * from tbl_sms_owner_details where sod_user_owner_id = '".$sod_user_owner_id."' order by sod_id desc limit 1";
				$result_previous_sms_pkg= mysqli_query($db_con,$sql_previous_sms_pkg) or die(mysqli_error($db_con));
				$row_previous_sms_pkg 	= mysqli_fetch_array($sql_previous_sms_pkg);
								
				$date					= strtotime($sod_enddt);
				$diff					= $date-time();
				$daysRemaining1			= floor($diff/(60*60*24));
				
				$sod_num_of_sms	 	  	= $row_selected_sms_pkg['sp_num_sms']+$row_previous_sms_pkg['sod_num_of_sms'];
				$sod_expiry_days 	  	= $daysRemaining1+1;
				
				$sql_sms_user   		= "INSERT INTO `tbl_sms_owner_details`";
				$sql_sms_user  			.= " (`sod_tracking_order_id`,`sod_user_owner_id`,`sod_package_id`,`sod_startdt`,`sod_enddt`,`sod_num_of_sms`,`sod_expiry_days`,`sod_createddt`,`sod_createdby`) VALUES";
				$sql_sms_user  			.= " ('offline','".$sod_user_owner_id."','".$sod_package_id."','".$sod_startdt."','".$sod_enddt."','".$sod_num_of_sms."','".$sod_expiry_days."','".$datetime."','".$_SESSION['panel_user']['fullname']."')";
				$result_sms_owner 		= mysqli_query($db_con,$sql_sms_user) or die(mysqli_error($db_con));
				//-----------------------------------
				// Start : To update user information
				//-----------------------------------
				$sql_update_cadmin		= " UPDATE `tbl_cadmin_users` SET `fullname`='".$fullname."',`userid`='".$email."',`email`='".$email."',";
				$sql_update_cadmin		.= " `sms_status`='".$sms_status."',`email_status`='".$email_status."',`utype`='".$utype."',";
				$sql_update_cadmin		.= " `sms_status`='1',`mobile_num`='".$mobile_num."',`modified`='".$datetime."',`tbl_users_owner`='".$tbl_users_owner."' where `id`='".$id."'";
				$result_update_cadmin 	= mysqli_query($db_con,$sql_update_cadmin) or die(mysqli_error($db_con));				
				//-----------------------------------
				// End : To update user information
				//-----------------------------------
				
				//-------------------------------------------
				// Start : To update user rights information
				//-------------------------------------------
				$ar_existing_db_rights 		= explode("*",$row_rights['ar_current_rights']); 	//current rights according to database
				$ar_history_db_rights 		= explode("*",$row_rights['ar_history_rights']);	//history rights according to database
				$ar_current_page_rights 	= explode("*",$ar_current_rights);					//rights seleted on page array																							
				
				$diff1 = array_diff($ar_existing_db_rights, $ar_current_page_rights);	//rights present in database but unchecked on page
				
				foreach($diff1 as $vals) //To check each unchecked element present inside history or not
				{  
					if(in_array($vals,$ar_history_db_rights))		//If present inside history
					{
						$new_ar_history_rights = "";
						//echo $vals."in history<br>";
					}
					else											//If not present inside history
					{
						if($vals != "")								//If number is there then only append comma 
						{
							$new_ar_history_rights .= $vals."*";
						}
						else
						{
							$new_ar_history_rights .= "";
						}
						//echo $vals."not in history<br>";
					}
				}
				$old_plus_new_ar_history_rights = $row_rights['ar_history_rights'].",".$new_ar_history_rights;
				$sql_update_rights	= "UPDATE tbl_assign_rights SET ar_history_rights ='".trim(rtrim($old_plus_new_ar_history_rights,','),',')."', ar_current_rights ='".$ar_current_rights."',`modifieddt`= '".$datetime."',`modifiedby`='".$_SESSION['panel_user']['fullname']."' where `ar_user_owner_id`='".$id."'";									
				$result_update_rights	= mysqli_query($db_con,$sql_update_rights) or die(mysqli_error($db_con));
				//-------------------------------------------
				// End : To update user rights information
				//-------------------------------------------
			}
			elseif($sms_status != '1' || ($sms_status == '1' && $renewal != '1' && $_POST['isDisabled'] == 'true'))
			{	
				$sql_update_cadmin 		= " UPDATE `tbl_cadmin_users` SET `fullname`='".$fullname."',`userid`='".$email."',`email`='".$email."',`sms_status`='".$sms_status."',`email_status`='".$email_status."',";
				$sql_update_cadmin 		.= "`utype`='".$utype."',`sms_status`='1',`mobile_num`='".$mobile_num."',`modified`='".$datetime."',`tbl_users_owner`='".$tbl_users_owner."' where `id`='".$id."'";
				$result_update_cadmin	= mysqli_query($db_con,$sql_update_cadmin) or die(mysqli_error($db_con));
				
				$ar_existing_db_rights 		= explode("*",$row_rights['ar_current_rights']); 	//current rights according to database
				$ar_history_db_rights 		= explode("*",$row_rights['ar_history_rights']);	//history rights according to database
				$ar_current_page_rights 	= explode("*",$ar_current_rights);					//rights seleted on page array																							
				
				$diff1 = array_diff($ar_existing_db_rights, $ar_current_page_rights);	//rights present in database but unchecked on page
				
				foreach($diff1 as $vals) //To check each unchecked element present inside history or not
				{  
					if(in_array($vals,$ar_history_db_rights))		//If present inside history
					{
						$new_ar_history_rights = "";
						//echo $vals."in history<br>";
					}
					else											//If not present inside history
					{
						if($vals != "")								//If number is there then only append comma 
						{
							$new_ar_history_rights .= $vals."*";
						}
						else
						{
							$new_ar_history_rights .= "";
						}
						//echo $vals."not in history<br>";
					}
				}
				$old_plus_new_ar_history_rights = $row_rights['ar_history_rights'].",".$new_ar_history_rights;
				$sql_update_assign_rights 		= " UPDATE `tbl_assign_rights` SET `ar_history_rights`='".trim(rtrim($old_plus_new_ar_history_rights,','),',')."',";
				$sql_update_assign_rights 		.= " `ar_current_rights`='".$ar_current_rights."',`modifieddt`= '".$datetime."',`modifiedby`='".$_SESSION['panel_user']['fullname']."' where `ar_user_owner_id`='".$id."'";
				$result_update_assign_rights 	= mysqli_query($db_con,$sql_update_assign_rights) or die(mysqli_error($db_con));
			}
			else
			{
				echo "Please fill all sms details";
			}
			
			if(isset($_POST['topup']) && $_POST['topup'] != "")
			{
				$sql_update_sms_owner 		= "update tbl_sms_owner_details set sod_num_of_sms = '".$_POST['topup']."' where sod_user_owner_id = '".$_REQUEST['id']."' and `sod_startdt` <= DATE('".$datetime."') and `sod_enddt` >= DATE('".$datetime."')";
				$result_update_sms_owner 	= mysqli_query($db_con,$sql_update_sms_owner) or die(mysqli_error($db_con));
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
<body  class="theme-orange" data-theme="theme-orange" onLoad="show_other_options();">
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
                                    <h3><i class="icon-th-list"></i><?php echo $feature_name; ?></h3>
                                </div>
                                <div class="box-content nopadding">
                                <?php
								?>
                                    <form enctype="multipart/form-data"  id="frm_update_user" name="frm_update_user" method="POST" class='form-horizontal form-bordered form-validate'>				                                    
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Full Name</label>
                                            <div class="controls">
                                                <input type="text" name="fullname" id="fullname"  class="input-xlarge" placeholder="Full name" data-rule-required="true" value="<?php echo $row_cadmin['fullname'];?>">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Email ID</label>
                                            <div class="controls">
                                                <input type="text" name="emailid" id="emailid"  class="input-xlarge" placeholder="Email ID" data-rule-required="true" data-rule-email="true" value="<?php echo $row_cadmin['email'];?>">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Password</label>
                                            <div class="controls">
                                                <input type="password" name="password" id="password"  class="input-xlarge" placeholder="Password" value="<?php echo $row_cadmin['password'];?>">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">Mobile Number</label>
                                            <div class="controls">
                                                <input type="text" name="mobile" id="mobile"  class="input-xlarge" placeholder="Mobile Number" data-rule-required="true" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)" value="<?php echo $row_cadmin['mobile_num'];?>" >
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">User Type</label>
                                            <div class="controls">
                                                <select name="user_type" style="width:150px;" onChange="show_other_options();" data-rule-required="true" id="user_type">
                                                    <option value="">Select User Type</option>
                                                    <?php
														foreach($type_of_admin as $key=>$vals)
														{
															?>
                                                            	<option value="<?php print $vals['at_id']; ?>" <?php if(strcmp($vals['at_id'],$row_cadmin['utype']) == 0){echo "Selected";}?>><?php print $vals['at_name']; ?></option>
                                                            <?php
														}														
													?>
                                                </select>
                                            </div>
                                        </div>                                        
                                        
                                        <div class="control-group">
                                            <label for="textarea" class="control-label">User Owner</label>
                                            <div class="controls">
                                                <select name="user_owner" style="width:150px;" data-rule-required="true" id="user_owner">
                                                    <option value="0">Select Owner</option>
                                                        <?php foreach($owner_name as $key=>$vals1) { ?>
                                                            <option value="<?php print $vals1['id']; ?>" <?php if($vals1['id']==$row_cadmin['tbl_users_owner']){echo "selected"; } ?> ><?php print $vals1['clientname']; ?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div id="divcategory"></div>
                                        
                                         <div class="form-actions">
                                                <button type="submit" name="reg_submit" class="btn-success">Update</button>
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
<script>
	function show_other_options() //This is for show/hide according to category of user i.e. for student hide sms
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
	
	function show_sms_renwal_div()//This function for show/hide sms package names on sms and renewal
	{		
		if($("#renewal").is(':checked') && $("#smsstatus").is(':disabled'))
		{
			validityrange();			
			//alert("renewal");
			$("#divsmspackage").slideDown("slow");
			$("#divsmspackagesettingname").slideDown("slow");
			$("#divsmspackagesettingvalidity").slideDown("slow");
			$("#divsmspackagedaterange").slideDown("slow");
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
		if($("#renewal").is(':checked'))
		{
			$('#16').prop('checked', true);  //This is for setting notification checked by default
			$('#33').prop('checked', true);
		}
		else if(!($("#emailstatus").is(':checked')) && !($("#smsstatus").is(':checked')) && !($("#renewal").is(':checked')))
		{
			$('#16').prop('checked', false);  //This is for setting notification checked by default
			$('#33').prop('checked', false);
		}
	}
	function show_sms_div()
	{
		if($("#smsstatus").is(':checked') && !($("#smsstatus").is(':disabled')))
		{
			var dateToday = new Date();
			 $(function() {
			$( "#start_date, #end_date" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				changeYear: true,
				dateFormat: 'dd/mm/yy',
				yearRange : 'c:c+10',	//this is for selecting only next years
				numberOfMonths: 1,
				minDate: dateToday,		//this is for disable previous date
				onSelect: function( selectedDate ) {
					
				var validity = $( "#divsmspackagesettingvalidity" ).text();
				days		 =	parseInt(validity);
					
					if(this.id == 'start_date'){
					  var dateMin = $('#start_date').datepicker("getDate");
					  var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); 
					  var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + days); 
					  //$('#to').datepicker("option","minDate",rMin);
					  //$('#to').datepicker("option","maxDate",rMax);  
					  $('#end_date').val($.datepicker.formatDate('dd/mm/yy', new Date(rMax)));                    
					}
					
					}
				});
			});
			$("#divsmspackage").slideDown("slow");
			$("#divsmspackagesettingname").slideDown("slow");
			$("#divsmspackagesettingvalidity").slideDown("slow");
			$("#divsmspackagedaterange").slideDown("slow");
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
		if($("#smsstatus").is(':checked'))
		{
			$('#16').prop('checked', true);  //This is for setting notification checked by default
			$('#33').prop('checked', true);
		}
		else if(!($("#emailstatus").is(':checked')) && !($("#smsstatus").is(':checked')) && !($("#renewal").is(':checked')))
		{
			$('#16').prop('checked', false);  //This is for setting notification checked by default
			$('#33').prop('checked', false);
		}
	}
	function emailfeature()
	{
		if($("#emailstatus").is(':checked'))
		{
			$('#16').prop('checked', true);  //This is for setting notification checked by default
			$('#33').prop('checked', true);
		}
		else if(!($("#emailstatus").is(':checked')) && !($("#smsstatus").is(':checked')) && !($("#renewal").is(':checked')))
		{
			$('#16').prop('checked', false);  //This is for setting notification checked by default
			$('#33').prop('checked', false);
		}
	}
	function validityrange()
	{
		var dateToday 		= new Date();
		var daysremaining 	= $('input[name="prev_enddate"]').val();
		var ar_newstartdate = daysremaining.split("-");
		var months 			= ar_newstartdate[1]-1;
		var startDateFrom 	= new Date(ar_newstartdate[0],months,ar_newstartdate[2]);
		$(function() {
		$( "#start_date, #end_date" ).datepicker({
			//defaultDate		: "+1w",
			changeMonth		: true,
			changeYear		: true,
			dateFormat		: 'dd/mm/yy',
			yearRange 		: 'c:c+10',	//this is for selecting only next years
			numberOfMonths	: 1,
			minDate			: startDateFrom,
			onSelect		: function( selectedDate ) 
			{					
				var validity = $( "#divsmspackagesettingvalidity" ).text();
				days		 =	parseInt(validity);
				
				if(this.id == 'start_date')
				{
				  var dateMin = $('#start_date').datepicker("getDate");
				  var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); 
				  var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + days); 
				  //$('#to').datepicker("option","minDate",rMin);
				  //$('#to').datepicker("option","maxDate",rMax);  
				  $('#end_date').val($.datepicker.formatDate('dd/mm/yy', new Date(rMax)));                    
				}
			}
			});
		});
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
</script>
<script>
	//This is for (If mobile number starts with non-zero digit then 10 else 11 digit mobile number)
	function ValidateMobile(mobileid)
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
	//This is for only numbers
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		{
			return false;
		}
		return true;
	}
	$('#frm_update_user').on('submit', function(e) {
	e.preventDefault();
	if ($('#frm_update_user').valid())
	{
		var fullname 	= $.trim($('input[name="fullname"]').val());
		var emailid 	= $.trim($('input[name="emailid"]').val());
		var password 	= $.trim($('input[name="password"]').val());
		var mobile 		= $.trim($('input[name="mobile"]').val());
		var user_type	= $.trim($('select[name="user_type"]').val());
		var user_owner	= $.trim($('select[name="user_owner"]').val());
                
		var smsstatus	= $.trim($("input[name='smsstatus']:checked").val());
		var emailstatus	= $.trim($("input[name='emailstatus']:checked").val());		
		var renewal		= $.trim($("input[name='renewal']:checked").val());
		var start_date	= $('#start_date').val();
		var end_date	= $('#end_date').val();
		var pkgid 		= $("#pkgid").attr("value");
		var isDisabled  = $("#smsstatus").is(':disabled');
		var schoolid    = '';
		
		var topup 	= $.trim($('#total_sms').html());//TOpup
		
		if(user_type == 12)
		{
			var schoolid	= $.trim($('select[name="schoolwise"]').val());
			if(schoolid == '')
			{
				alert("Please select school");
				return false;
			}
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
		if(user_type == "0")
		{
			alert("Please enter user type");
			return false;	
		}
		else if(user_owner == "0")
		{
			alert("Please enter owner type");	
			return false;	
		}
	
		$('input[name="reg_submit"]').attr('disabled', 'true');
		$.post(location.href,{topup:topup,fullname:fullname,emailid:emailid,password:password,mobile:mobile,user_type:user_type,user_owner:user_owner,combinedfeature:combinedfeature,smsstatus:smsstatus,emailstatus:emailstatus,renewal:renewal,start_date:start_date,end_date:end_date,pkgid:pkgid,isDisabled:isDisabled,schoolid:schoolid,jsubmit:'updateuser'},function(response)
		{
			if (response.length > 0) 
			{
				$('input[name="reg_submit"]').removeAttr('disabled');
				alert(response);
			} 
			else 
			{
				alert("Congratulations...You have sucessfully updated user.");
				window.location.replace('view_user.php?pag=<?php echo $title; ?>','_self');
			}
		});
		}
	});
	
	function showcount(smscount)
	{
		if(smscount == '')
		{
			var smscount = 0;
		}
		var remsms = $('#hid_rem_sms').val();
		var total  = parseInt(remsms) + parseInt(smscount);
		$('#total_sms').html(total);
	}
</script>
</body>
</html>