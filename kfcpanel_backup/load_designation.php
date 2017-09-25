<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

function insertDesignation($response_array,$desg_name,$desg_status)
{
	global $db_con, $datetime;
	global $uid;
	
	$sql_check_desg 		= " SELECT * FROM tbl_designation_type WHERE desg_name = '".$desg_name."' "; 
	$result_check_desg 		= mysqli_query($db_con,$sql_check_desg) or die(mysqli_error($db_con));
	$num_rows_check_desg 	= mysqli_num_rows($result_check_desg);
	if($num_rows_check_desg == 0)
	{
		$sql_last_rec 		= "SELECT * FROM tbl_designation_type ORDER by desg_id DESC LIMIT 0,1";
		$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$desg_id 		= 1;				
		}
		else
		{
			$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
			$desg_id 		= $row_last_rec['desg_id']+1;
		}
		$sql_insert_desg 	= " INSERT INTO `tbl_designation_type`(`desg_id`, `desg_name`, `desg_created`, `desg_created_by`,`desg_status`) ";
		$sql_insert_desg 	.= " VALUES ('".$desg_id."', '".$desg_name."', '".$datetime."', '".$uid."', '".$desg_status."')";		
		$result_insert_desg = mysqli_query($db_con,$sql_insert_desg) or die(mysqli_error($db_con));
		if($sql_insert_desg)
		{
			if(isset($obj->error_id) && (isset($obj->insert_req)) != "")			
			{
				$sql_delete_error_brand = "DELETE FROM `tbl_error_data` WHERE `error_id`='".$obj->error_id."'";
				$res_delete_error_brand = mysqli_query($db_con, $sql_delete_error_brand) or die(mysqli_error($db_con));				
				if($res_delete_error_brand)
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
		$response_array = array("Success"=>"fail","resp"=>"Designation <b>".ucwords($desg_name)."</b> already Exist");
	}
	return $response_array;	
}

if(isset($_FILES['file']))
{
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$ind_id 	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$response_array = array();
	
	try 
	{
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	} 
	catch(Exception $e) 
	{
		die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	
	$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
	
	if(strcmp($inputFileName, "")!==0)
	{
		for($i=2;$i<=$arrayCount;$i++)
		{
			$desg_name 		= trim($allDataInSheet[$i]["A"]);
			$desg_status	= '1';
			
			$query 		= " SELECT * FROM `tbl_designation_type` WHERE `desg_name`='".$desg_name."' " ;
							
			$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
			$recResult 	= mysqli_fetch_array($sql);
			
			$existName 	= $recResult["desg_name"];
			
			if($existName=="")
			{						  
				$response_array 	= insertDesignation($response_array, $desg_name, $desg_status);
				if($response_array)
				{
					$insertion_flag	= 1;	
				}
				else
				{
					$insertion_flag	= 0;	
				}
			}
			else
			{
				// error data array
				$error_data = array("desg_name"=>$desg_name, "desg_status"=>"0");	
				
				$sql_get_last_record	= " SELECT * FROM `tbl_error_data` ORDER by `error_id` DESC LIMIT 0,1 ";
				$res_get_last_record	= mysqli_query($db_con, $sql_get_last_record) or die(mysqli_error($db_con));
				$row_get_last_record	= mysqli_fetch_array($res_get_last_record);
				$num_get_last_record	= mysqli_num_rows($res_get_last_record);
				if(strcmp($num_get_last_record,0)===0)
				{
					$error_id	= 1;
				}
				else
				{
					$error_id	= $row_get_last_record['error_id']+1;
				}
				
				$error_module_name	= "designation";
				$error_file			= $inputFileName;
				$error_status		= '1';
				$error_data_json	= json_encode($error_data);
				
				$sql_insert_error_log	= " INSERT INTO `tbl_error_data`(`error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`) 
											VALUES ('".$error_id."', '".$error_module_name."', '".$error_file."', '".$error_data_json."', '".$error_status."', '".$datetime."', '".$uid."') ";
				$res_insert_error_log 	= mysqli_query($db_con, $sql_insert_error_log) or die(mysqli_error($db_con));
				
				$insertion_flag	= 1;
			}
		}
		
		if($insertion_flag == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Insertion Successfully");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Insertion Error");			
		}
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}

if((isset($obj->insert_req)) == "1" && isset($obj->insert_req))
{
	$desg_name			= strtolower(mysqli_real_escape_string($db_con,$obj->desg_name));
	$desg_status		= mysqli_real_escape_string($db_con,$obj->desg_status);
	
	$response_array = array();	
	
	if($desg_name != "" && $desg_status != "")
	{
		$sql_check_desg 		= " SELECT * FROM tbl_designation_type WHERE desg_name = '".$desg_name."' "; 
		$result_check_desg 		= mysqli_query($db_con,$sql_check_desg) or die(mysqli_error($db_con));
		$num_rows_check_desg 	= mysqli_num_rows($result_check_desg);
		if($num_rows_check_desg == 0)
		{
			$sql_last_rec 		= "SELECT * FROM tbl_designation_type ORDER by desg_id DESC LIMIT 0,1";
			$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
			$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
			if($num_rows_last_rec == 0)
			{
				$desg_id 		= 1;				
			}
			else
			{
				$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
				$desg_id 		= $row_last_rec['desg_id']+1;
			}
			// query for insertion
			$sql_insert_desg 	= " INSERT INTO `tbl_designation_type`(`desg_id`, `desg_name`, `desg_created`, `desg_created_by`,`desg_status`) ";
			$sql_insert_desg 	.= " VALUES ('".$desg_id."', '".$desg_name."', '".$datetime."', '".$uid."', '".$desg_status."')";
			
			$result_insert_desg = mysqli_query($db_con,$sql_insert_desg) or die(mysqli_error($db_con));
			if($sql_insert_desg)
			{
				$response_array = array("Success"=>"Success","resp"=>$sql_insert_desg);			
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
			}			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Designation ".$desg_name." already Exist");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	
	echo json_encode($response_array);		
}

if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{
	$desg_id			= mysqli_real_escape_string($db_con,$obj->desg_id);
	$desg_name			= strtolower(mysqli_real_escape_string($db_con,$obj->desg_name));
	$desg_status		= mysqli_real_escape_string($db_con,$obj->desg_status);
	$response_array = array();		
	if($desg_name != "" && $desg_status != "")
	{
		$sql_check_desg 		= " select * from tbl_designation_type where desg_name like '".$desg_name."' and desg_id != '".$desg_id."' "; 
		$result_check_desg 	= mysqli_query($db_con,$sql_check_desg) or die(mysqli_error($db_con));
		$num_rows_check_desg = mysqli_num_rows($result_check_desg);
		if($num_rows_check_desg == 0)
		{
			$sql_update_desg = " UPDATE `tbl_designation_type` SET `desg_name`='".$desg_name."', `desg_status`='".$desg_status."',";
			$sql_update_desg .= " `desg_modified`='".$datetime."',`desg_modified_by`='".$uid."' WHERE `desg_id` = '".$desg_id."' ";
			$result_update_desg = mysqli_query($db_con,$sql_update_desg) or die(mysqli_error($db_con));
			if($result_update_desg)
			{
				$response_array = array("Success"=>"Success","resp"=>"Update Succesfull");			
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
			}					
		}		
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Designation ".$desg_name." already Exists.");	
		}		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->load_desg_parts)) == "1" && isset($obj->load_desg_parts))
{
	$desg_id 		= mysqli_real_escape_string($db_con,$obj->desg_id);
	$req_type 		= strtolower(mysqli_real_escape_string($db_con,$obj->req_type));
	$response_array = array();
	if($req_type != "")
	{
		if($desg_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$desg_id."' "; // this desg_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_desg_data		= json_decode($row_error_data['error_data']);
		}
		else if(($desg_id != "" && $req_type == "edit") || ($desg_id != "" && $req_type == "view"))
		{
			$sql_desg_data 	= "Select * from tbl_designation_type where desg_id = '".$desg_id."' ";
			$result_desg_data 	= mysqli_query($db_con,$sql_desg_data) or die(mysqli_error($db_con));
			$row_desg_data		= mysqli_fetch_array($result_desg_data);		
		}	
		$data = '';
		if($desg_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="desg_id" value="'.$row_desg_data['desg_id'].'">';
		}
		elseif($desg_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$desg_id.'">';
		}	                                                         		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Designation Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="desg_name" name="desg_name" class="input-large" data-rule-required="true" ';
		if($desg_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_desg_data['desg_name']).'"'; 
		}
		elseif($desg_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_desg_data->desg_name).'"'; 			
		}
		elseif($desg_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_desg_data['desg_name']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Designation Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($desg_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="desg_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_designation.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_desg_data->desg_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="desg_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_desg_data->desg_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($desg_id != "" && $req_type == "view")
		{
			if($row_desg_data['desg_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_desg_data['desg_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="desg_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_designation.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_desg_data['desg_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="desg_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_desg_data['desg_status'] == 0)
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
		if($desg_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($desg_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
		}			
		elseif($desg_id != "" && $req_type == "error")
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

if((isset($obj->load_desg)) == "1" && isset($obj->load_desg))
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
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `desg_id`, `desg_name`, `desg_created`, `desg_created_by`, `desg_modified`, `desg_modified_by`, `desg_status`,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.desg_created_by) AS name_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.desg_modified_by) AS name_midified_by ";
		$sql_load_data  .= " FROM `tbl_designation_type` AS ti WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND desg_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (desg_name like '%".$search_text."%' or desg_created like '%".$search_text."%' or desg_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY desg_name ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$desg_data = "";	
			$desg_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$desg_data .= '<thead>';
    	  	$desg_data .= '<tr>';
         	$desg_data .= '<th style="text-align:center">Sr No.</th>';
			$desg_data .= '<th style="text-align:center">Desg ID</th>';
			$desg_data .= '<th style="text-align:center">Desg Name</th>';
			$desg_data .= '<th style="text-align:center">Created</th>';
			$desg_data .= '<th style="text-align:center">Created By</th>';
			$desg_data .= '<th style="text-align:center">Modified</th>';
			$desg_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_designation.php",3);
			if($dis)
			{					
				$desg_data .= '<th>Status</th>';											
			}
			$edit = checkFunctionalityRight("view_designation.php",1);
			if($edit)
			{					
				$desg_data .= '<th>Edit</th>';			
			}	
			$delete = checkFunctionalityRight("view_designation.php",2);
			if($delete)
			{					
				$desg_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}					
          	$desg_data .= '</tr>';
      		$desg_data .= '</thead>';
      		$desg_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$desg_data .= '<tr>';				
				$desg_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$desg_data .= '<td style="text-align:center">'.$row_load_data['desg_id'].'</td>';
				$desg_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['desg_name']).'" class="btn-link" id="'.$row_load_data['desg_id'].'" onclick="addMoreDesg(this.id,\'view\');"></td>';				
				$desg_data .= '<td style="text-align:center">'.$row_load_data['desg_created'].'</td>';
				$desg_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$desg_data .= '<td style="text-align:center">'.$row_load_data['desg_modified'].'</td>';
				$desg_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_designation.php",3);
				if($dis)
				{					
					$desg_data .= '<td style="text-align:center">';					
					if($row_load_data['desg_status'] == 1)
					{
						$desg_data .= '<input type="button" value="Active" id="'.$row_load_data['desg_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$desg_data .= '<input type="button" value="Inactive" id="'.$row_load_data['desg_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$desg_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_designation.php",1);
				if($edit)
				{				
						$desg_data .= '<td style="text-align:center">';
						$desg_data .= '<input type="button" value="Edit" id="'.$row_load_data['desg_id'].'" class="btn-warning" onclick="addMoreDesg(this.id,\'edit\');"></td>';				
				}
				$delete = checkFunctionalityRight("view_designation.php",2);
				if($delete)
				{					
					$desg_data .= '<td><div class="controls" align="center">';
					$desg_data .= '<input type="checkbox" value="'.$row_load_data['desg_id'].'" id="batch'.$row_load_data['desg_id'].'" name="batch'.$row_load_data['desg_id'].'" class="css-checkbox batch">';
					$desg_data .= '<label for="batch'.$row_load_data['desg_id'].'" class="css-label"></label>';
					$desg_data .= '	</div></td>';										
				}
	          	$desg_data .= '</tr>';															
			}	
      		$desg_data .= '</tbody>';
      		$desg_data .= '</table>';	
			$desg_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$desg_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available in Designation");
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
	$desg_id				= $obj->desg_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();

	$status_flag = 0;
	$sql_check_parent 		= "Select * from tbl_designation_type where `desg_id` = '".$desg_id."' ";
	$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
	$row_check_parent 		= mysqli_fetch_array($result_check_parent);
	
	$sql_update_status 		= " UPDATE `tbl_designation_type` SET `desg_status`= '".$curr_status."' ,`desg_modified` = '".$datetime."' ,`desg_modified_by` = '".$uid."' WHERE `desg_id`='".$desg_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	if($result_update_status)
	{
		$status_flag = 1;				
		$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
	}				
	
	if($status_flag == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
	}	
	echo json_encode($response_array);	
}

if((isset($obj->delete_desg)) == "1" && isset($obj->delete_desg))
{
	$response_array = array();		
	$ar_desg_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_desg_id as $desg_id)	
	{
		$sql_delete_desg		= " DELETE FROM `tbl_designation_type` WHERE `desg_id` = '".$desg_id."' ";
		$result_delete_desg	= mysqli_query($db_con,$sql_delete_desg) or die(mysqli_error($db_con));			
		if($result_delete_desg)
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

// Showing Records From Error Logs Table For Designation========================
if((isset($obj->load_error)) == "1" && isset($obj->load_error))
{
	$start_offset   = 0;
	
	$page 			= $obj->page1;	
	$per_page		= $obj->row_limit1;
	$search_text	= $obj->search_text1;	
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
		$sql_load_data  .= " WHERE error_module_name='designation' ";
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
			$desg_data = "";	
			$desg_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$desg_data .= '<thead>';
    	  	$desg_data .= '<tr>';
         	$desg_data .= '<th>Sr. No.</th>';
			$desg_data .= '<th>Designation Name</th>';
			//$desg_data .= '<th>Description</th>';
			//$desg_data .= '<th>Parent</th>';
			$desg_data .= '<th>Created</th>';
			$desg_data .= '<th>Created By</th>';
			$desg_data .= '<th>Modified</th>';
			$desg_data .= '<th>Modified By</th>';
			$desg_data .= '<th>Edit</th>';			
			$desg_data .= '<th>
							<div style="text-align:center">
								<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>
							</div>
						</th>';
          	$desg_data .= '</tr>';
      		$desg_data .= '</thead>';
      		$desg_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_desg_rec	= json_decode($row_load_data['error_data']);
				
				$er_desg_name	= $get_desg_rec->desg_name;
				
				$desg_data .= '<tr>';				
				$desg_data .= '<td>'.++$start_offset.'</td>';				
				$desg_data .= '<td>';
					$sql_chk_name_already_exist	= " SELECT * FROM `tbl_designation_type` WHERE `desg_name`='".$er_desg_name."' ";
					$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
					$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);
					
					if(strcmp($num_chk_name_already_exist,"0")===0)
					{
						$desg_data .= $er_desg_name;
					}
					else
					{
						$desg_data .= '<span style="color:#E63A3A;">'.$er_desg_name.' [Already Exist]</span>';
					}
				$desg_data .= '</td>';
				//$desg_data .= '<td>'.$row_load_data['cat_description'].'</td>';
				
				$desg_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$desg_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
				$desg_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$desg_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$desg_data .= '<td style="text-align:center"><a href="add_category.php?errorcat='.$row_load_data['error_id'].'">Edit</a></td>';	
				$desg_data .= '<td>
								<div class="controls" align="center">';
				$desg_data .= '		<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$desg_data .= '		<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$desg_data .= '	</div>
							  </td>';										
	          	$desg_data .= '</tr>';															
			}	
      		$desg_data .= '</tbody>';
      		$desg_data .= '</table>';	
			$desg_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$desg_data);					
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

if((isset($obj->delete_desg_error)) == "1" && isset($obj->delete_desg_error))
{
	$ar_desg_id 		= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_desg_id as $desg_id)	
	{
		$sql_delete_desg_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$desg_id."' ";
		
		$result_delete_desg_error= mysqli_query($db_con,$sql_delete_desg_error) or die(mysqli_error($db_con));			
		if($result_delete_desg_error)
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