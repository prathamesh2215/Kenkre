<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

function insertIndustry($ind_name,$response_array)
{
	global $obj;
	global $db_con, $datetime;
	global $uid;
	$sql_check_ind 		= " select * from tbl_industry where ind_name = '".$ind_name."' "; 
	$result_check_ind 	= mysqli_query($db_con,$sql_check_ind) or die(mysqli_error($db_con));
	$num_rows_check_ind = mysqli_num_rows($result_check_ind);
	if($num_rows_check_ind == 0)
	{
		$sql_last_rec = "Select * from tbl_industry order by ind_id desc LIMIT 0,1";
		$result_last_rec = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec = mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$ind_id 		= 1;				
		}
		else
		{
			$row_last_rec = mysqli_fetch_array($result_last_rec);				
			$ind_id 		= $row_last_rec['ind_id']+1;
		}
		$sql_insert_ind = " INSERT INTO `tbl_industry`(`ind_id`, `ind_name`, `ind_created_date`, `ind_created_by`,`ind_status`) ";
		$sql_insert_ind .= "VALUES ('".$ind_id."', '".$ind_name."', '".$datetime."', '".$uid."', '".$ind_status."')";			
		$result_insert_ind = mysqli_query($db_con,$sql_insert_ind) or die(mysqli_error($db_con));
		if($result_insert_ind)
		{
			if(isset($obj->error_id) && (isset($obj->insert_req)) != "")			
			{
				$response_array = errorDataDelete($obj->error_id);
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
		$response_array = array("Success"=>"fail","resp"=>"Industry <b>".ucwords($ind_name)."</b> already Exist");
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
			$ind_name 				= trim($allDataInSheet[$i]["A"]);
			
			$query = " SELECT `id`, `ind_id`, `ind_name`, `ind_created_date`, `ind_created_by`, `ind_modified_date`, `ind_modified_by`, `ind_status` 
						FROM `tbl_industry` 
						WHERE `ind_name`='".$ind_name."' " ;
							
			$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
			$recResult 	= mysqli_fetch_array($sql);
			
			$existName 		= $recResult["ind_name"];
			
			if($existName=="" )
			{
				$response_array 	= insertIndustry($ind_name,$response_array);
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
				$error_data = array("ind_name"=>$ind_name);	
				
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
				
				$error_module_name	= "industry";
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
	$ind_name			= strtolower(mysqli_real_escape_string($db_con,$obj->ind_name));
	$ind_status			= $obj->ind_status;
	$response_array = array();		
	if($ind_name != "" && $ind_status != "")
	{
		$response_array = insertIndustry($ind_name,$response_array);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}	
	echo json_encode($response_array);		
}

if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{
	$ind_id				= $obj->ind_id;
	$ind_name			= strtolower(mysqli_real_escape_string($db_con,$obj->ind_name));
	$ind_status			= $obj->ind_status;	
	$response_array = array();		
	if($ind_name != "" && $ind_status != "")
	{
		$sql_check_ind 		= " select * from tbl_industry where ind_name like '".$ind_name."' and ind_id != '".$ind_id."' "; 
		$result_check_ind 	= mysqli_query($db_con,$sql_check_ind) or die(mysqli_error($db_con));
		$num_rows_check_ind = mysqli_num_rows($result_check_ind);
		if($num_rows_check_ind == 0)
		{
			$sql_update_ind = " UPDATE `tbl_industry` SET `ind_name`='".$ind_name."',";
			$sql_update_ind .= " `ind_modified_date`='".$datetime."',`ind_modified_by`='".$uid."' WHERE `ind_id` = '".$ind_id."' ";
			$result_update_ind = mysqli_query($db_con,$sql_update_ind) or die(mysqli_error($db_con));
			if($result_update_ind)
			{
				if($ind_status == 0)
				{
					$sql_check_org_ind 		= "SELECT * FROM `tbl_oraganisation_master` WHERE org_indid  = '".$ind_id."' ";
					$result_check_org_ind 	= mysqli_query($db_con,$sql_check_org_ind) or die(mysqli_error($db_con));
					$num_rows_check_org_ind = mysqli_num_rows($result_check_org_ind);
					if($num_rows_check_org_ind == 0)
					{
						  $sql_update_status 		= " UPDATE `tbl_industry` SET `ind_status`= '0' ,`ind_modified_date` = '".$datetime."' ";
						  $sql_update_status 		.= " ,`ind_modified_by` = '".$uid."' WHERE `ind_id`='".$ind_id."' ";
						  $result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
						  if($result_update_status)
						  {				
							  $response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
						  }
						  else
						  {
							  $response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
						  }							
					}
					else
					{	
						$response_array = array("Success"=>"fail","resp"=>"Industry in Used.Can not Inactive");	
					}
				}
				elseif($ind_status == 1)
				{
					$sql_update_status 		= " UPDATE `tbl_industry` SET `ind_status`= '1' ,`ind_modified_date` = '".$datetime."' ";
					$sql_update_status 		.= " ,`ind_modified_by` = '".$uid."' WHERE `ind_id`='".$ind_id."' ";
					$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
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
			$response_array = array("Success"=>"fail","resp"=>"Industry <b>".ucwords($ind_name)."</b> already Exist");	
		}		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->load_ind)) == "1" && isset($obj->load_ind))
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
			
		$sql_load_data  = " SELECT `ind_id`, `ind_name`, `ind_created_date`, `ind_created_by`, `ind_modified_date`, `ind_modified_by`, `ind_status`,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.ind_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.ind_modified_by) AS name_midified_by 
							FROM `tbl_industry` AS ti WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND ind_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (ind_name like '%".$search_text."%' ";
			$sql_load_data .= " or ind_created_by like '%".$search_text."%' or ind_modified_by like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY ind_name ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$ind_data = "";	
			$ind_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$ind_data .= '<thead>';
    	  	$ind_data .= '<tr>';
         	$ind_data .= '<th style="text-align:center">Sr No.</th>';
			$ind_data .= '<th style="text-align:center">Industry ID</th>';
			$ind_data .= '<th style="text-align:center">Industry Name</th>';
			$ind_data .= '<th style="text-align:center">Created</th>';
			$ind_data .= '<th style="text-align:center">Created By</th>';
			$ind_data .= '<th style="text-align:center">Modified</th>';
			$ind_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_industry.php",3);
			if($dis)
			{			
				$ind_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_industry.php",1);
			if($edit)
			{			
				$ind_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_industry.php",2);
			if($delete)
			{			
				$ind_data .= '<th style="text-align:center"><div style="text-align:center">';
				$ind_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$ind_data .= '</tr>';
      		$ind_data .= '</thead>';
      		$ind_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$ind_data .= '<tr>';				
				$ind_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$ind_data .= '<td style="text-align:center">'.$row_load_data['ind_id'].'</td>';
				$ind_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['ind_name']).'" class="btn-link" id="'.$row_load_data['ind_id'].'" onclick="addMoreIndustry(this.id,\'view\');"></td>';				
				$ind_data .= '<td style="text-align:center">'.$row_load_data['ind_created_date'].'</td>';
				$ind_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$ind_data .= '<td style="text-align:center">'.$row_load_data['ind_modified_date'].'</td>';
				$ind_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_industry.php",3);
				if($dis)
				{					
					$ind_data .= '<td style="text-align:center">';					
					if($row_load_data['ind_status'] == 1)
					{
						$ind_data .= '<input type="button" value="Active" id="'.$row_load_data['ind_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$ind_data .= '<input type="button" value="Inactive" id="'.$row_load_data['ind_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$ind_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_industry.php",1);
				if($edit)
				{				
					$ind_data .= '<td style="text-align:center">';
					$ind_data .= '<input type="button" value="Edit" id="'.$row_load_data['ind_id'].'" class="btn-warning" onclick="addMoreIndustry(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_industry.php",2);
				if($delete)
				{					
					$ind_data .= '<td><div class="controls" align="center">';
					$ind_data .= '<input type="checkbox" value="'.$row_load_data['ind_id'].'" id="batch'.$row_load_data['ind_id'].'" name="batch'.$row_load_data['ind_id'].'" class="css-checkbox batch">';
					$ind_data .= '<label for="batch'.$row_load_data['ind_id'].'" class="css-label"></label>';
					$ind_data .= '</div></td>';										
				}
	          	$ind_data .= '</tr>';															
			}	
      		$ind_data .= '</tbody>';
      		$ind_data .= '</table>';	
			$ind_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$ind_data);					
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

if((isset($obj->load_ind_parts)) == "1" && isset($obj->load_ind_parts))
{
	$ind_id = $obj->ind_id;
	$req_type = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($ind_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$ind_id."' "; // this ind_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_ind_data		= json_decode($row_error_data['error_data']);
		}
		else if($ind_id != "" && $req_type == "edit")
		{
			$sql_ind_data 	= "Select * from tbl_industry where ind_id = '".$ind_id."' ";
			$result_ind_data 	= mysqli_query($db_con,$sql_ind_data) or die(mysqli_error($db_con));
			$row_ind_data		= mysqli_fetch_array($result_ind_data);		
		}	
		else if($ind_id != "" && $req_type == "view")
		{
			$sql_ind_data 	= "Select * from tbl_industry where ind_id = '".$ind_id."' ";
			$result_ind_data 	= mysqli_query($db_con,$sql_ind_data) or die(mysqli_error($db_con));
			$row_ind_data		= mysqli_fetch_array($result_ind_data);		
		}			
		$data = '';
		if($ind_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="ind_id" value="'.$row_ind_data['ind_id'].'">';
		}
		elseif($ind_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$ind_id.'">';
		}	                                                         		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Industry Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="ind_name" name="ind_name" class="input-large" data-rule-required="true" ';
		if($ind_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_ind_data['ind_name']).'"'; 
		}
		elseif($ind_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_ind_data->ind_name).'"'; 			
		}
		elseif($ind_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_ind_data['ind_name']).'" disabled'; 				
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Industry Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($ind_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="ind_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_industry.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_ind_data->ind_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="ind_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_ind_data->ind_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($ind_id != "" && $req_type == "view")
		{
			if($row_ind_data['ind_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_ind_data['ind_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="ind_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_industry.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_ind_data['ind_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="ind_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_ind_data['ind_status'] == 0)
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
		if($ind_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($ind_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
		}			
		elseif($ind_id != "" && $req_type == "error")
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

if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$ind_id					= $obj->ind_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	if($curr_status != 1)
	{
		$sql_check_org_ind 		= "SELECT * FROM `tbl_oraganisation_master` WHERE org_indid  = '".$ind_id."' ";
		$result_check_org_ind 	= mysqli_query($db_con,$sql_check_org_ind) or die(mysqli_error($db_con));
		$num_rows_check_org_ind = mysqli_num_rows($result_check_org_ind);		
		if($num_rows_check_org_ind == 0)
		{
			$sql_update_status 		= " UPDATE `tbl_industry` SET `ind_status`= '".$curr_status."' ,`ind_modified_date` = '".$datetime."' ";
			$sql_update_status 		.= " ,`ind_modified_by` = '".$uid."' WHERE `ind_id`='".$ind_id."' ";
			$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
			if($result_update_status)
			{				
				$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
			}											
		}	
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Industry in Used.Can not Inactive");	
		}
	}
	else
	{
		$sql_update_status 		= " UPDATE `tbl_industry` SET `ind_status`= '".$curr_status."' ,`ind_modified_date` = '".$datetime."' ";
		$sql_update_status 		.= " ,`ind_modified_by` = '".$uid."' WHERE `ind_id`='".$ind_id."' ";
		$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
		if($result_update_status)
		{				
			$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
		}													
	}
	echo json_encode($response_array);	
}

if((isset($obj->delete_ind)) == "1" && isset($obj->delete_ind))
{
	$response_array = array();		
	$ar_ind_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_ind_id as $ind_id)	
	{
		/* Delete permission check if industry exist in organisation table or not then delete 
		$sql_check_org_ind 		= "SELECT * FROM `tbl_oraganisation_master` WHERE org_indid  = '".$ind_id."' ";
		$result_check_org_ind 	= mysqli_query($db_con,$sql_check_org_ind) or die(mysqli_error($db_con));
		$num_rows_check_org_ind = mysqli_num_rows($result_check_org_ind);		
		if($num_rows_check_org_ind == 0)
		{		
			 Delete permission check if industry exist in organisation table or not then delete */
			$sql_delete_ind		= " DELETE FROM `tbl_industry` WHERE `ind_id` = '".$ind_id."' ";
			$result_delete_ind	= mysqli_query($db_con,$sql_delete_ind) or die(mysqli_error($db_con));			
			if($result_delete_ind)
			{
				$del_flag = 1;	
			}			
		/*}
		else
		{
			$del_flag = 0;				
		}*/
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
		$sql_load_data  .= " WHERE error_module_name='industry' ";
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
			$ind_data = "";	
			$ind_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$ind_data .= '<thead>';
    	  	$ind_data .= '<tr>';
         	$ind_data .= '<th>Sr. No.</th>';
			$ind_data .= '<th>Industry Name</th>';
			$ind_data .= '<th>Created</th>';
			$ind_data .= '<th>Created By</th>';
			$ind_data .= '<th>Modified</th>';
			$ind_data .= '<th>Modified By</th>';
			$ind_data .= '<th>Edit</th>';			
			$ind_data .= '<th><div style="text-align:center">';
			$ind_data .= '<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/></div></th>';
          	$ind_data .= '</tr>';
      		$ind_data .= '</thead>';
      		$ind_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_ind_rec	= json_decode($row_load_data['error_data']);				
				$er_ind_name	= $get_ind_rec->ind_name;				
				$ind_data 		.= '<tr>';				
				$ind_data 		.= '<td>'.++$start_offset.'</td>';				
				$ind_data 		.= '<td>';
				$sql_chk_name_already_exist	= " SELECT `ind_name` FROM `tbl_industry` WHERE `ind_name`='".$er_ind_name."' ";
				$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
				$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);
				
				if(strcmp($num_chk_name_already_exist,"0")===0)
				{
					$ind_data .= $er_ind_name;
				}
				else
				{
					$ind_data .= '<span style="color:#E63A3A;">'.$er_ind_name.' [Already Exist]</span>';
				}
				$ind_data 		.= '</td>';
				$ind_data 		.= '<td>'.$row_load_data['error_created'].'</td>';
				$ind_data 		.= '<td>'.$row_load_data['created_by_name'].'</td>';
				$ind_data 		.= '<td>'.$row_load_data['error_modified'].'</td>';
				$ind_data 		.= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$ind_data 		.= '<td style="text-align:center">';
				$ind_data 		.= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreIndustry(this.id,\'error\');"></td>';
				$ind_data 		.= '<td><div class="controls" align="center">';
				$ind_data 		.= '<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$ind_data 		.= '<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$ind_data 		.= '</div></td>';										
	          	$ind_data 		.= '</tr>';															
			}	
      		$ind_data .= '</tbody>';
      		$ind_data .= '</table>';	
			$ind_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$ind_data);					
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

if((isset($obj->delete_ind_error)) == "1" && isset($obj->delete_ind_error))
{
	$ar_ind_id 		= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_ind_id as $ind_id)	
	{
		$sql_delete_cat_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$ind_id."' ";
		
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