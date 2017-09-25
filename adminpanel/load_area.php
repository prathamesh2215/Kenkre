<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

function insertArea($area_name,$area_direction,$area_pincode,$area_status,$response_array)
{
	global $obj;
	global $db_con, $datetime;
	global $uid;
	$sql_check_area 	 = " select * from tbl_area where area_name = '".$area_name."' "; 
	$result_check_area 	 = mysqli_query($db_con,$sql_check_area) or die(mysqli_error($db_con));
	$num_rows_check_area = mysqli_num_rows($result_check_area);
	
	$sql_last_rec      = "Select * from tbl_area order by area_id desc LIMIT 0,1";
	$result_last_rec   = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
	$num_rows_last_rec = mysqli_num_rows($result_last_rec);
	
	if($num_rows_last_rec == 0)
	{
		$area_id 		= 1;				
	}
	else
	{
		$row_last_rec = mysqli_fetch_array($result_last_rec);				
		$area_id 	  = $row_last_rec['area_id']+1;
	}
	$sql_insert_area    = " INSERT INTO `tbl_area`(`area_id`, `area_name`, `area_direction`, `area_pincode`, `area_created`, `area_created_by`,`area_status`) ";
	$sql_insert_area   .= "VALUES ('".$area_id."', '".$area_name."', '".$area_direction."', '".$area_pincode."', '".$datetime."', '".$uid."', '".$area_status."')";			
	$result_insert_area = mysqli_query($db_con,$sql_insert_area) or die(mysqli_error($db_con));
	if($result_insert_area)
	{
		$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully");					
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
	}			
	return $response_array;
}

//------------------this is used for inserting records---------------------
if((isset($obj->insert_area)) == "1" && isset($obj->insert_area))
{
	$area_name			= mysqli_real_escape_string($db_con,$obj->area_name);
	$area_direction		= mysqli_real_escape_string($db_con,$obj->area_direction);

	$area_pincode		= mysqli_real_escape_string($db_con,$obj->area_pincode);
	$area_status		= $obj->area_status;
	$response_array     = array();
	if($area_name != "" && $area_direction != "" && $area_pincode != "" && $area_status != "")
	{
		if($area_direction=="not available")
		{
			$area_direction="";
		}
		$sql_check_area 	 = " select * from tbl_area where area_name like '".$area_name."' and area_direction like '".$area_direction."' and area_id != '".$area_id."' "; 
		$result_check_area 	 = mysqli_query($db_con,$sql_check_area) or die(mysqli_error($db_con));
		$num_rows_check_area = mysqli_num_rows($result_check_area);
		if($num_rows_check_area == 0)
		{
			$response_array = insertArea($area_name,$area_direction,$area_pincode,$area_status,$response_array);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Area <b>".ucwords($area_name)."-".ucwords($area_direction)."</b> already Exist");	
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}	
	echo json_encode($response_array);		
}

//------------------this is used for update records---------------------
if((isset($obj->update_area)) == "1" && isset($obj->update_area))
{
	$area_id			= $obj->area_id;
	$area_name			= mysqli_real_escape_string($db_con,$obj->area_name);
	$area_pincode		= mysqli_real_escape_string($db_con,$obj->area_pincode);
	$area_direction		= $obj->area_direction;
	$area_status		= $obj->area_status;
	$response_array     = array();
	if($area_name != "" && $area_direction != "" && $area_pincode != "" && $area_status != "")
	{
		$sql_check_area 	 = " select * from tbl_area where area_name like '".$area_name."' and area_direction like '".$area_direction."' and area_id != '".$area_id."' "; 
		$result_check_area 	 = mysqli_query($db_con,$sql_check_area) or die(mysqli_error($db_con));
		$num_rows_check_area = mysqli_num_rows($result_check_area);
		if($num_rows_check_area == 0)
		{
			$sql_update_area    = " UPDATE `tbl_area` SET `area_name`='".$area_name."',`area_direction`='".$area_direction."',`area_pincode`='".$area_pincode."',";
			$sql_update_area   .= " `area_modified`='".$datetime."',`area_modified_by`='".$uid."' WHERE `area_id` = '".$area_id."' ";
			$result_update_area = mysqli_query($db_con,$sql_update_area) or die(mysqli_error($db_con));
			if($result_update_area)
			{
				if($area_status == 0)
				{
					$sql_update_status 		 = " UPDATE `tbl_area` SET `area_status`= '0' ,`area_modified` = '".$datetime."' ";
					$sql_update_status 		.= " ,`area_name` = '".$area_name."',`area_direction` = '".$area_direction."',`area_pincode`='".$area_pincode."' ";
					$sql_update_status 		.= " ,`area_modified_by` = '".$uid."' WHERE `area_id`='".$area_id."' ";
					$result_update_status 	 = mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
					if($result_update_status)
					{	
						$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
					}							
				}
				elseif($area_status == 1)
				{
					$sql_update_status 		 = " UPDATE `tbl_area` SET `area_status`= '1' ,`area_modified` = '".$datetime."' ";
					$sql_update_status 		.= " ,`area_name` = '".$area_name."',`area_direction` = '".$area_direction."',`area_pincode`='".$area_pincode."' ";
					$sql_update_status 		.= " ,`area_modified_by` = '".$uid."' WHERE `area_id`='".$area_id."' ";
					$result_update_status 	 = mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
					if($result_update_status)
					{				
						$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
					}					
				}
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
			}					
		}		
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Area <b>".ucwords($area_name)."-".ucwords($area_direction)."</b> already Exist");	
		}		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

//----------------------This is used for viewing records---------------------------------
if((isset($obj->load_area)) == "1" && isset($obj->load_area))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= $obj->search_text;	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `area_id`, `area_name`, `area_direction`, `area_pincode`, `area_created`, `area_created_by`, `area_modified`, `area_modified_by`, `area_status`,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.area_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.area_modified_by) AS name_midified_by 
							FROM `tbl_area` AS ti WHERE 1=1";
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND area_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (area_name like '%".$search_text."%' or area_pincode = '".$search_text."' ";
			$sql_load_data .= " or area_id = '".$search_text."') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY area_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Area ID</th>';
			$area_data .= '<th style="text-align:center">Area Name</th>';
			$area_data .= '<th style="text-align:center">Pin Code</th>';
			$area_data .= '<th style="text-align:center">Created Date</th>';
			$area_data .= '<th style="text-align:center">Created By</th>';
			$area_data .= '<th style="text-align:center">Modified Date</th>';
			$area_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_area.php",3);
			$dis = 1;
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_area.php",1);
			$edit = 1;
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_area.php",2);
			$delete = 1;
			if($delete)
			{			
				$area_data .= '<th style="text-align:center"><div style="text-align:center">';
				$area_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$area_data .= '</tr>';
      		$area_data .= '</thead>';
      		$area_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$area_data .= '<tr>';				
				$area_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$area_data .= '<td style="text-align:center">'.$row_load_data['area_id'].'</td>';
				
				if($row_load_data['area_direction'] == '')
				{
					$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['area_name']).'" class="btn-link" id="'.$row_load_data['area_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				}
				else
				{
					$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['area_name']).'-'.ucwords($row_load_data['area_direction']).'" class="btn-link" id="'.$row_load_data['area_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				}
				
				$area_data .= '<td style="text-align:center">'.$row_load_data['area_pincode'].'</td>';			
				$area_data .= '<td style="text-align:center">'.$row_load_data['area_created'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['area_modified'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_area.php",3);
				$dis = 1;
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['area_status'] == 1)
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['area_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['area_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_area.php",1);
				$edit = 1;
				if($edit)
				{				
					$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Edit" id="'.$row_load_data['area_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_area.php",2);
				$delete = 1;
				if($delete)
				{					
					$area_data .= '<td><div class="controls" align="center">';
					$area_data .= '<input type="checkbox" value="'.$row_load_data['area_id'].'" id="batch'.$row_load_data['area_id'].'" name="batch'.$row_load_data['area_id'].'" class="css-checkbox batch">';
					$area_data .= '<label for="batch'.$row_load_data['area_id'].'" class="css-label"></label>';
					$area_data .= '</div></td>';										
				}
	          	$area_data .= '</tr>';															
			}	
      		$area_data .= '</tbody>';
      		$area_data .= '</table>';	
			$area_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$area_data);				
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}

if((isset($obj->load_area_parts)) == "1" && isset($obj->load_area_parts))
{
	$area_id        = $obj->area_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($area_id != "" && $req_type == "edit")
		{
			$sql_area_data 	    = "Select * from tbl_area where area_id = '".$area_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}	
		else if($area_id != "" && $req_type == "view")
		{
			$sql_area_data 	    = "Select * from tbl_area where area_id = '".$area_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}			
		$data = '';
		if($area_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="area_id" value="'.$row_area_data['area_id'].'">';
		}                                                         		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Area Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="area_name" name="area_name" class="input-large keyup-char" data-rule-required="true" ';
		if($area_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_area_data['area_name'].'"'; 
		}
		elseif($area_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_area_data['area_name'].'" disabled'; 				
		}
		$data .= '/><br>';
		
		if($area_id != "" && $req_type == "view")
		{
			if($row_area_data['area_direction'] == 'not available')
			{
				$data .= '<label class="control-label">Not Avilable</label>';
			}
			if($row_area_data['area_direction'] == 'east')
			{
				$data .= '<label class="control-label">East</label>';
			}
			if($row_area_data['area_direction'] == 'west')
			{
				$data .= '<label class="control-label">West</label>';
			}
		}
		else
		{  
          if($area_id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="area_direction" value="not available" class="css-radio" data-rule-required="true" ';
				if($row_area_data['area_direction'] == 'not available')
				{
					$data .= 'checked ';
				}
				$data .= '>Not Available&nbsp;&nbsp;';
				$data .= '<input type="radio" name="area_direction" value="east" class="css-radio" data-rule-required="true"';
				if($row_area_data['area_direction'] == 'east')
				{
					$data .= 'checked ';
				}
				$data .= '>East&nbsp;&nbsp;';
				$data .= '<input type="radio" name="area_direction" value="west" class="css-radio" data-rule-required="true"';
				if($row_area_data['area_direction'] == 'west')
				{
					$data .= 'checked ';
				}
				$data .= '>West';
			} 
			else  
			{
				$data .= '<input type="radio" name="area_direction" value="not available" class="css-radio" data-rule-required="true" ';
				$data .= '>Not Available&nbsp;&nbsp;';
				$data .= '<input type="radio" name="area_direction" value="east" class="css-radio" data-rule-required="true"';
				$data .= '>East&nbsp;&nbsp;';
				$data .= '<input type="radio" name="area_direction" value="west" class="css-radio" data-rule-required="true"';
				$data .= '>West';
			}
		}				
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		
		$data .= '</div>';
		$data .= '</div> <!-- Area Name -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Pin Code<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="area_pincode" name="area_pincode" maxlength="6" minlength="6" onKeyPress="return isNumberKey(event)" class="input-large keyup-char" data-rule-required="true" ';
		if($area_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_area_data['area_pincode'].'"'; 
		}
		elseif($area_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_area_data['area_pincode'].'" disabled'; 				
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Pincode -->';

		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		if($area_id != "" && $req_type == "view")
		{
			if($row_area_data['area_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_area_data['area_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}
		else
		{  
          if($area_id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="area_status" value="1" class="css-radio" data-rule-required="true" ';
				$dis	= checkFunctionalityRight("view_area.php",3);
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['area_status'] == 1)
				{
					$data .= 'checked ';
				}
				$data .= '>Active';
				$data .= '<input type="radio" name="area_status" value="0" class="css-radio" data-rule-required="true"';
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['area_status'] == 0  )
				{
					$data .= 'checked ';
				}
				$data .= '>Inactive';
			} 
			else  
			{
				$data .= '<input type="radio" name="area_status" value="1" class="css-radio" data-rule-required="true" ';
				$data .= '>Active';
				$data .= '<input type="radio" name="area_status" value="0" class="css-radio" data-rule-required="true"';
			
		 		$data .= '>Inactive';
			}
		}					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		
		$data .= '<div class="form-actions">';
		if($area_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create Area</button>';			
		}
		elseif($area_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Area</button>';			
		}			
		$data .= '</div> <!-- Save and cancel -->';	
			
		$response_array = array("Success"=>"Success","resp"=>$data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Type Not Defined");		
	}
	echo json_encode($response_array);
}

//---------------This is used for status change--------------------------------
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$area_id				= $obj->area_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$sql_update_status 		= " UPDATE `tbl_area` SET `area_status`= '".$curr_status."' ,`area_modified` = '".$datetime."' ";
	$sql_update_status 	   .= " ,`area_modified_by` = '".$uid."' WHERE `area_id`='".$area_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	if($result_update_status)
	{				
		$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
	}											
	echo json_encode($response_array);	
}

//------------------This is used for delete data---------------------------------
if((isset($obj->delete_area)) == "1" && isset($obj->delete_area))
{
	$response_array   = array();		
	$ar_area_id 	  = $obj->batch;
	$del_flag 		  = 0; 
	foreach($ar_area_id as $area_id)	
	{
		$sql_delete_area	= " DELETE FROM `tbl_area` WHERE `area_id` = '".$area_id."' ";
		$result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));			
		if($result_delete_area)
		{
			$del_flag = 1;	
		}			
	}	
	if($del_flag == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");
	}		
	echo json_encode($response_array);	
}

// ==========================================================================

?>
