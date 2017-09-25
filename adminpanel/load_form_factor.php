<?php
include("include/routines.php");
$json 	= file_get_contents('php://input');
$obj 	= json_decode($json);
$uid	= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

function insertFormFactor($spec_name,$cat_id,$status,$response_array)
{
	global $db_con, $datetime;
	global $uid;
	global $obj;
	$sql_check_spec 	 = " select * from tbl_form_factor where form_factor_name like '".$spec_name."' AND cat_id = '".$cat_id."' ";
	$result_check_spec 	 = mysqli_query($db_con,$sql_check_spec) or die(mysqli_error($db_con));
	$num_rows_check_spec = mysqli_num_rows($result_check_spec);
	if($num_rows_check_spec == 0)
	{	
		$sql_insert_spec	= " INSERT INTO `tbl_form_factor`(`cat_id`, `form_factor_name`, `status`, `created_date`, `created_by`) ";
		$sql_insert_spec	.=" VALUES ('".$cat_id."', '".$spec_name."', '".$status."', '".$datetime."', '".$uid."') ";
		$result_insert_spec = mysqli_query($db_con,$sql_insert_spec) or die(mysqli_error($db_con));
		if($result_insert_spec)
		{
			if(isset($obj->error_id) && (isset($obj->insert_req)) != "")
			{
				$sql_delete_error_cat = "DELETE FROM `tbl_error_data` WHERE `error_id`='".$obj->error_id."'";
				$res_delete_error_cat = mysqli_query($db_con, $sql_delete_error_cat) or die(mysqli_error($db_con));
				if($res_delete_error_cat)
				{
					$response_array = array("Success"=>"Success","resp"=>"Error Data Updated Successfully");
				}
				else
				{
					$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully but Error Data not deleted");
				}
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully");
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Form Factor <b>".ucwords($spec_name)."</b> already Exist");
	}
	return $response_array;
}

if((isset($obj->insert_req)) == "1" && isset($obj->insert_req))
{
	$spec_name			= strtolower(mysqli_real_escape_string($db_con,$obj->spec_name));
	$spec_status		= $obj->spec_status;
	$ddl_parent_cat		= $obj->ddl_parent_cat;
	$response_array 	= array();
	
	if($spec_name != "" && $spec_status != "")
	{
		$response_array = insertFormFactor($spec_name,$ddl_parent_cat,$spec_status,$response_array);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");
	}
	echo json_encode($response_array);
}

if((isset($obj->load_spec_parts)) == "1" && isset($obj->load_spec_parts))
{
	$spec_id = $obj->spec_id;
	$req_type = $obj->req_type;
	$response_array = array();

	// $response_array = array("Success"=>"fail","resp"=>$req_type);
	// echo json_encode($response_array);
	// exit();

	if($req_type != "")
	{
		if($spec_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$spec_id."' "; // this spec_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);
			$row_spec_data		= json_decode($row_error_data['error_data']);
		}
		else if($spec_id != "" && $req_type == "edit")
		{
			$sql_spec_data 	= "Select * from tbl_form_factor where id = '".$spec_id."' ";
			$result_spec_data 	= mysqli_query($db_con,$sql_spec_data) or die(mysqli_error($db_con));
			$row_spec_data		= mysqli_fetch_array($result_spec_data);
		}
		else if($spec_id != "" && $req_type == "view")
		{
			$sql_spec_data 	= "Select * from tbl_form_factor where id = '".$spec_id."' ";
			$result_spec_data 	= mysqli_query($db_con,$sql_spec_data) or die(mysqli_error($db_con));
			$row_spec_data		= mysqli_fetch_array($result_spec_data);
		}
		$data = '';
		if($spec_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="spec_id" value="'.$row_spec_data['id'].'">';
		}
		elseif($spec_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$spec_id.'">';
		}

		$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Category <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			if($req_type != "view")
			{
				$data .= '<select name="ddl_parent_cat" id="ddl_parent_cat" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
					$data .= '<option value="">Select Category</option>';
			}

			if(($req_type == "add") || ($req_type == "error"))	
			{
				$sql_get_parent_cats	= " SELECT * FROM `tbl_category` WHERE `cat_type` = 'parent' AND `cat_status` = '1' ";
				$res_get_parent_cats	= mysqli_query($db_con, $sql_get_parent_cats) or die(mysql_error($db_con));
				$num_get_parent_cats	= mysqli_num_rows($res_get_parent_cats);

				if($num_get_parent_cats != 0)
				{
					while($row_get_parent_cats = mysqli_fetch_array($res_get_parent_cats))
					{
						$data .= '<option value="'.$row_get_parent_cats['cat_id'].'">'.ucwords($row_get_parent_cats['cat_name']).'</option>';
					}
				}
				else
				{
					$data .= '<option value="">No Match Found</option>';
				}
			}
			elseif ($req_type == "view") 
			{
				$sql_get_parent_cats	= " SELECT * FROM `tbl_category` ";
				$sql_get_parent_cats	.= " WHERE `cat_type` = 'parent' ";
				$sql_get_parent_cats	.= " 	AND `cat_status` = '1' ";
				$sql_get_parent_cats	.= " 	AND `cat_id`= '".$row_spec_data['cat_id']."' ";
				$res_get_parent_cats	= mysqli_query($db_con, $sql_get_parent_cats) or die(mysql_error($db_con));
				$num_get_parent_cats	= mysqli_num_rows($res_get_parent_cats);

				if($num_get_parent_cats != 0)
				{
					$row_get_parent_cats	= mysqli_fetch_array($res_get_parent_cats);
					$data .= '<label class="control-label" >'.ucwords($row_get_parent_cats['cat_name']).'</label>';
				}
				else
				{
					$data .= '<label class="control-label" >No Match Found</label>';
				}		
			}
			elseif ($req_type == "edit") 
			{
				$sql_get_parent_cats	= " SELECT * FROM `tbl_category` ";
				$sql_get_parent_cats	.= " WHERE `cat_type` = 'parent' ";
				$sql_get_parent_cats	.= " 	AND `cat_status` = '1' ";
				$sql_get_parent_cats	.= " 	AND `cat_id`='".$row_spec_data['cat_id']."' ";
				$res_get_parent_cats	= mysqli_query($db_con, $sql_get_parent_cats) or die(mysql_error($db_con));
				$num_get_parent_cats	= mysqli_num_rows($res_get_parent_cats);

				if($num_get_parent_cats != 0)
				{
					while($row_get_parent_cats = mysqli_fetch_array($res_get_parent_cats))
					{
						$data .= '<option value="'.$row_get_parent_cats['cat_id'].'" ';
						if($row_get_parent_cats['cat_id'] == $row_spec_data['cat_id'])
						{
							$data .= ' selected ';
						}
						$data .= '>';
							$data .= ucwords($row_get_parent_cats['cat_name']);
						$data .= '</option>';
					}
				}
				else
				{
					$data .= '<option value="">No Match Found</option>';
				}
			}

				
				$data .= '</select>';
			$data .= '</div>';	
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Form Factor Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="form_factor_name" name="form_factor_name" class="input-large" data-rule-required="true" ';
		if($spec_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_spec_data['form_factor_name']).'"';
		}
		elseif($spec_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_spec_data->spec_name).'"';
		}
		elseif($spec_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_spec_data['form_factor_name']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Specification Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($spec_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="spec_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_form_factor.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_spec_data->spec_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="spec_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_spec_data->spec_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($spec_id != "" && $req_type == "view")
		{
			if($row_spec_data['status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_spec_data['status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}
		else
		{
			$data .= '<input type="radio" name="spec_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_form_factor.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_spec_data['status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="spec_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_spec_data['status'] == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		$data .= '<div class="form-actions">';
		if($spec_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';
		}
		elseif($spec_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';
		}
		elseif($spec_id != "" && $req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update</button>';
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

if((isset($obj->load_spec)) == "1" && isset($obj->load_spec))
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

		$sql_load_data  = " SELECT tbm.*,(SELECT fullname FROM tbl_cadmin_users WHERE id=tbm.created_by) AS name_form_factor_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM tbl_cadmin_users WHERE id=tbm.modified_by) AS name_form_factor_modified_by, tc.cat_name ";
		$sql_load_data  .= " FROM `tbl_form_factor` AS tbm INNER JOIN tbl_category AS tc ";
		$sql_load_data  .= " 	ON tbm.cat_id = tc.cat_id ";
		$sql_load_data  .= " WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND tbm.created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " AND (tbm.form_factor_name like '%".$search_text."%' or tbm.created_date like '%".$search_text."%' or tbm.modified_date like '%".$search_text."%')";
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);
		$sql_load_data .=" ORDER BY tbm.form_factor_name ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
		if($result_load_data)
		{
			if(strcmp($data_count,"0") !== 0)
			{
				$spec_data = "";
				$spec_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 		$spec_data .= '<thead>';
    	  		$spec_data .= '<tr>';
         		$spec_data .= '<th>Sr. No.</th>';
				$spec_data .= '<th>Id</th>';
				$spec_data .= '<th>Category Name</th>';
				$spec_data .= '<th>Form Factor Name</th>';
				$spec_data .= '<th>Created By</th>';
				$spec_data .= '<th>Created Date</th>';
				$spec_data .= '<th>Modified By</th>';
				$spec_data .= '<th>Modified Date</th>';
				$dis = checkFunctionalityRight("view_form_factor.php",3);
				if($dis)
				{
					$spec_data .= '<th>Status</th>';
				}
				$edit = checkFunctionalityRight("view_form_factor.php",1);
				if($edit)
				{
					$spec_data .= '<th>Edit</th>';
				}
				$delete = checkFunctionalityRight("view_form_factor.php",2);
				if($delete)
				{
					$spec_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
				}
          		$spec_data .= '</tr>';
      			$spec_data .= '</thead>';
      			$spec_data .= '<tbody>';
				while($row_load_data = mysqli_fetch_array($result_load_data))
				{
	    		  	$spec_data .= '<tr>';
					$spec_data .= '<td>'.++$start_offset.'</td>';
					$spec_data .= '<td>'.$row_load_data['id'].'</td>';
					$spec_data .= '<td>'.$row_load_data['cat_name'].'</td>';
					$spec_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['form_factor_name']).'" class="btn-link" id="'.$row_load_data['id'].'" onclick="addMoreSpec(this.id,\'view\');"></td>';
					$spec_data .= '<td>'.$row_load_data['name_form_factor_created_by'].'</td>';
					$spec_data .= '<td>'.$row_load_data['created_date'].'</td>';
					if($row_load_data['modified_by'] != '')
					{
						$spec_data .= '<td>'.$row_load_data['name_form_factor_modified_by'].'</td>';
					}
					else
					{
						$spec_data .= '<td>Not Yet Modified</td>';	
					}

					if($row_load_data['modified_date'] != '')
					{
						$spec_data .= '<td>'.$row_load_data['modified_date'].'</td>';
					}
					else
					{
						$spec_data .= '<td>Not Yet Modified</td>';		
					}
					
					$dis = checkFunctionalityRight("view_form_factor.php",3);
					if($dis)
					{
						$spec_data .= '<td style="text-align:center">';
						if($row_load_data['status'] == 1)
						{
							$spec_data .= '<input type="button" value="Active" id="'.$row_load_data['id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
						}
						else
						{
							$spec_data .= '<input type="button" value="Inactive" id="'.$row_load_data['id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
						}
						$spec_data .= '</td>';
					}
					$edit = checkFunctionalityRight("view_form_factor.php",1);
					if($edit)
					{
						$spec_data .= '<td style="text-align:center">';
						$spec_data .= '<input type="button" value="Edit" id="'.$row_load_data['id'].'" class="btn-warning" onclick="addMoreSpec(this.id,\'edit\');"></td>';
					}
					$delete = checkFunctionalityRight("view_form_factor.php",2);
					if($delete)
					{
						$spec_data .= '<td><div class="controls" align="center">';
						$spec_data .= '<input type="checkbox"  id="batch'.$row_load_data['id'].'" name="batch'.$row_load_data['id'].'" value="'.$row_load_data['id'].'"  class="css-checkbox batch">';
						$spec_data .= '<label for="batch'.$row_load_data['id'].'" class="css-label" style="color:#FFF"></label>';
						$spec_data .= '</div></td>';
					}
	        	  	$spec_data .= '</tr>';
				}
      			$spec_data .= '</tbody>';
      			$spec_data .= '</table>';
				$spec_data .= $data_count;
				$response_array = array("Success"=>"Success","resp"=>$spec_data);
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"No Data Available");
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Data not available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);
}

if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$spec_id				= $obj->spec_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();
	$sql_update_status 		= " UPDATE `tbl_form_factor` SET `status`= '".$curr_status."' ,`modified_date` = '".$datetime."' ,`modified_by` = '".$uid."' WHERE `id` like '".$spec_id."' ";
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

if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{
	$spec_id			= $obj->spec_id;
	$ddl_parent_cat		= $obj->ddl_parent_cat;
	$spec_name			= strtolower(mysqli_real_escape_string($db_con,$obj->spec_name));
	$spec_status		= $obj->spec_status;
	$response_array 	= array();
	if($spec_name != "" && $spec_id != "" && $spec_status != "")
	{
		$sql_check_spec 		= " select * from tbl_form_factor where form_factor_name like '".$spec_name."' and `id` != '".$spec_id."' ";
		$result_check_spec 		= mysqli_query($db_con,$sql_check_spec) or die(mysqli_error($db_con));
		$num_rows_check_spec 	= mysqli_num_rows($result_check_spec);
		if($num_rows_check_spec == 0)
		{
			$sql_update_spec	= " UPDATE `tbl_form_factor` ";
			$sql_update_spec  	.= " 	SET `form_factor_name`='".$spec_name."', ";
			$sql_update_spec  	.= " 		`cat_id`='".$ddl_parent_cat."', ";
			$sql_update_spec  	.= " 		`status`='".$spec_status."', ";
			$sql_update_spec  	.= " 		`modified_date`='".$datetime."', ";
			$sql_update_spec  	.= " 		`modified_by`='".$uid."' ";
			$sql_update_spec  	.= " WHERE `id` = '".$spec_id."' ";
			$result_update_spec = mysqli_query($db_con,$sql_update_spec) or die(mysqli_error($db_con));
			if($result_update_spec)
			{
				$response_array = array("Success"=>"Success","resp"=>"Update Succesfull");
			}
			else
			{
			$response_array 	= array("Success"=>"fail","resp"=>"Record Not Inserted.");
			}
		}
		else
		{
			$response_array 	= array("Success"=>"fail","resp"=>"Form Factor ".$spec_name." already Exist");
		}
	}
	else
	{
		$response_array 		= array("Success"=>"fail","resp"=>"Empty Data.");
	}
	echo json_encode($response_array);
}

if((isset($obj->delete_spec)) == "1" && isset($obj->delete_spec))
{
	$response_array = array();
	$ar_spec_id 	= $obj->batch;
	$del_flag 		= 0;
	foreach($ar_spec_id as $spec_id)
	{
		$sql_delete_spec		= " DELETE FROM `tbl_form_factor` WHERE `id` = '".$spec_id."' ";
		$result_delete_spec	= mysqli_query($db_con,$sql_delete_spec) or die(mysqli_error($db_con));
		if($result_delete_spec)
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

// Showing Records From Error Logs Table For category========================
if((isset($obj->load_error)) == "1" && isset($obj->load_error))
{
	$start_offset   = 0;

	$page 			= $obj->page1;
	$per_page		= $obj->row_limit1;
	$search_text	= $obj->search_text1;
	$cat_parent		= $obj->cat_parent1;
	$response_array = array();

	if($page != "" && $per_page != "")
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;

		$sql_load_data  = " SELECT `error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`, `error_modified`, `error_modified_by`, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_created_by) as created_by_name, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_modified_by) as modified_by_name ";
		$sql_load_data  .= " FROM `tbl_error_data`  ";
		$sql_load_data  .= " WHERE error_module_name='form_factor' ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND error_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " AND (error_data LIKE '%".$search_text."%' or error_module_name LIKE '%".$search_text."%' ";
			//$sql_load_data .= " or name_created_by like '%".$search_text."%' or name_modified_by like '%".$search_text."%' ";
			$sql_load_data .= " or error_created like '%".$search_text."%' or error_modified like '%".$search_text."%') ";
		}

		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);

		$sql_load_data .=" LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));

		if(strcmp($data_count,"0") !== 0)
		{
			$spec_data = "";
			$spec_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$spec_data .= '<thead>';
    	  	$spec_data .= '<tr>';
         	$spec_data .= '<th>Sr. No.</th>';
			$spec_data .= '<th>Form Factor Name</th>';
			//$spec_data .= '<th>Description</th>';
			//$spec_data .= '<th>Parent</th>';
			$spec_data .= '<th>Created</th>';
			$spec_data .= '<th>Created By</th>';
			$spec_data .= '<th>Modified</th>';
			$spec_data .= '<th>Modified By</th>';
			$spec_data .= '<th>Edit</th>';
			$spec_data .= '<th>
							<div style="text-align:center">
								<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>
							</div>
						</th>';
          	$spec_data .= '</tr>';
      		$spec_data .= '</thead>';
      		$spec_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_spec_rec	= json_decode($row_load_data['error_data']);

				$er_spec_name	= $get_spec_rec->spec_name;

				$spec_data .= '<tr>';
				$spec_data .= '<td>'.++$start_offset.'</td>';
				$spec_data .= '<td>';
					$sql_chk_name_already_exist	= " SELECT `form_factor_name` FROM `tbl_form_factor` WHERE `form_factor_name`='".$er_spec_name."' ";
					$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
					$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);

					if(strcmp($num_chk_name_already_exist,"0")===0)
					{
						$spec_data .= $er_spec_name;
					}
					else
					{
						$spec_data .= '<span style="color:#E63A3A;">'.$er_spec_name.' [Already Exist]</span>';
					}
				$spec_data .= '</td>';
				//$spec_data .= '<td>'.$row_load_data['cat_description'].'</td>';

				$spec_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$spec_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
				$spec_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$spec_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$spec_data .= '<td style="text-align:center">';
				$spec_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreSpec(this.id,\'error\');"></td>';
				$spec_data .= '<td>
								<div class="controls" align="center">';
				$spec_data .= '		<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$spec_data .= '		<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$spec_data .= '	</div>
							  </td>';
	          	$spec_data .= '</tr>';
			}
      		$spec_data .= '</tbody>';
      		$spec_data .= '</table>';
			$spec_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$spec_data);
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

if((isset($obj->delete_spec_error)) == "1" && isset($obj->delete_spec_error))
{
	$ar_spec_id 		= $obj->batch;
	$response_array = array();
	$del_flag_error = 0;
	foreach($ar_spec_id as $spec_id)
	{
		$sql_delete_cat_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$spec_id."' ";

		$result_delete_cat_error= mysqli_query($db_con,$sql_delete_cat_error) or die(mysqli_error($db_con));
		if($result_delete_cat_error)
		{
			$$del_flag_error = 1;
		}
	}
	if($$del_flag_error == 1)
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
