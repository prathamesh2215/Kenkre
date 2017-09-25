<?php
include("include/routines.php");
$json 	= file_get_contents('php://input');
$obj 	= json_decode($json);
$uid	= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

function insertSpecification($tax_name,$tax_status,$response_array)
{
	global $db_con, $datetime;
	global $uid;
	global $obj;
	$sql_check_spec 	 = " select * from tbl_tax_master where tax_name like '".$tax_name."' "; 
	$result_check_spec 	 = mysqli_query($db_con,$sql_check_spec) or die(mysqli_error($db_con));
	$num_rows_check_spec = mysqli_num_rows($result_check_spec);
	if($num_rows_check_spec == 0)
	{
<<<<<<< HEAD
		$sql_insert_spec 	= " INSERT INTO `tbl_taxmanagement_master`(`tax_name`,`tax_created_by`, `tax_created`,`tax_status`) VALUES ('".$tax_name."','".$uid."','".$datetime."','".$tax_status."') ";
=======
		$sql_insert_spec 	= " INSERT INTO `tbl_tax_master`(`tax_name`,`tax_created_by`, `tax_created`,`tax_status`) VALUES ('".$tax_name."','".$uid."','".$datetime."','".$tax_status."') ";
>>>>>>> 1abf58b3b3b0d23b5faa704fe78843c42aaf59a0
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
		$response_array = array("Success"=>"fail","resp"=>"Specification <b>".ucwords($tax_name)."</b> already Exist");
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
	$tax_id 	= 0;
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
			$tax_name 				= trim($allDataInSheet[$i]["A"]);
			
			$query = " SELECT `id`, `tax_id`, `tax_name`, `tax_status`, `tax_created_by`, `tax_created`, `tax_modified_by`, `tax_modified` 
						FROM `tbl_tax_master` 
						WHERE `tax_name`='".$tax_name."' " ;
							
			$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
			$recResult 	= mysqli_fetch_array($sql);
			
			$existName 		= $recResult["tax_name"];
			
			if($existName=="" )
			{
				$response_array 	= insertSpecification($tax_name,$tax_status,$response_array);
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
				$error_data = array("tax_name"=>$tax_name);	
				
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
				
				$error_module_name	= "specification";
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
	$tax_name			= strtolower(mysqli_real_escape_string($db_con,$obj->tax_name));
	$tax_status		= $obj->tax_status;
	$response_array 	= array();	
	if($tax_name != "" && $tax_status != "")
	{
		$response_array = insertSpecification($tax_name,$tax_status,$response_array);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->load_tax_parts)) == "1" && isset($obj->load_tax_parts))
{
	$tax_id = $obj->tax_id;
	$req_type = $obj->req_type;
	$response_array = array(); 
	if($req_type != "")
	{
		if($tax_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$tax_id."' "; // this tax_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_tax_data		= json_decode($row_error_data['error_data']);
		}
		else if($tax_id != "" && $req_type == "edit")
		{
			$sql_tax_data 	= "Select * from tbl_tax_master where tax_id = '".$tax_id."' ";
			$result_tax_data 	= mysqli_query($db_con,$sql_tax_data) or die(mysqli_error($db_con));
			$row_tax_data		= mysqli_fetch_array($result_tax_data);		
		}	
		else if($tax_id != "" && $req_type == "view")
		{
			$sql_tax_data 	= "Select * from tbl_tax_master where tax_id = '".$tax_id."' ";
			$result_tax_data 	= mysqli_query($db_con,$sql_tax_data) or die(mysqli_error($db_con));
			$row_tax_data		= mysqli_fetch_array($result_tax_data);		
		}			
		$data = '';
		if($tax_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="tax_id" value="'.$row_tax_data['tax_id'].'">';
		}
		elseif($tax_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$tax_id.'">';
		}	                                                         		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Tax Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="number" step="0.01" id="tax_name" name="tax_name" class="input-large" data-rule-required="true" ';
		if($tax_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_tax_data['tax_name']).'"'; 
		}
		elseif($tax_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_tax_data->tax_name).'"'; 			
		}
		elseif($tax_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_tax_data['tax_name']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Specification Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($tax_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="tax_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_tax_management.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_tax_data->tax_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="tax_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_tax_data->tax_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($tax_id != "" && $req_type == "view")
		{
			if($row_tax_data['tax_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_tax_data['tax_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="tax_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_tax_management.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_tax_data['tax_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="tax_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_tax_data['tax_status'] == 0)
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
		if($tax_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($tax_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
		}			
		elseif($tax_id != "" && $req_type == "error")
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
			
		$sql_load_data  = " SELECT *,(SELECT fullname FROM tbl_cadmin_users WHERE id=tbm.tax_created_by) AS name_tax_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM tbl_cadmin_users WHERE id=tbm.tax_modified_by) AS name_tax_modified_by FROM `tbl_tax_master` AS tbm WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND tax_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " AND (tax_id like '%".$search_text."%' or tax_name like '%".$search_text."%' or tax_created like '%".$search_text."%' or  tax_modified like '%".$search_text."%')";
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY tax_name ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
		if($result_load_data)		
		{
			if(strcmp($data_count,"0") !== 0)
			{		
				$tax_data = "";	
				$tax_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 		$tax_data .= '<thead>';
    	  		$tax_data .= '<tr>';
         		$tax_data .= '<th>Sr. No.</th>';
				$tax_data .= '<th>Id</th>';
				$tax_data .= '<th>Tax Name</th>';
				$tax_data .= '<th>Created By</th>';
				$tax_data .= '<th>Created</th>';
				$tax_data .= '<th>Modified By</th>';
				$tax_data .= '<th>Modified</th>';	
				$dis = checkFunctionalityRight("view_tax_management.php",3);
				if($dis)
				{					
					$tax_data .= '<th>Status</th>';											
				}
				$edit = checkFunctionalityRight("view_tax_management.php",1);
				if($edit)
				{					
					$tax_data .= '<th>Edit</th>';			
				}	
				$delete = checkFunctionalityRight("view_tax_management.php",2);
				if($delete)
				{					
					$tax_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
				}					
          		$tax_data .= '</tr>';
      			$tax_data .= '</thead>';
      			$tax_data .= '<tbody>';
				while($row_load_data = mysqli_fetch_array($result_load_data))
				{
	    		  	$tax_data .= '<tr>';				
					$tax_data .= '<td>'.++$start_offset.'</td>';				
					$tax_data .= '<td>'.$row_load_data['tax_id'].'</td>';
					$tax_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['tax_name']).'" class="btn-link" id="'.$row_load_data['tax_id'].'" onclick="addMoreSpec(this.id,\'view\');"></td>';
					$tax_data .= '<td>'.$row_load_data['name_tax_created_by'].'</td>';
					$tax_data .= '<td>'.$row_load_data['tax_created'].'</td>';
					$tax_data .= '<td>'.$row_load_data['name_tax_modified_by'].'</td>';
					$tax_data .= '<td>'.$row_load_data['tax_modified'].'</td>';
					$dis = checkFunctionalityRight("view_tax_management.php",3);
					if($dis)
					{					
						$tax_data .= '<td style="text-align:center">';	
						if($row_load_data['tax_status'] == 1)
						{
							$tax_data .= '<input type="button" value="Active" id="'.$row_load_data['tax_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
						}
						else
						{
							$tax_data .= '<input type="button" value="Inactive" id="'.$row_load_data['tax_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
						}
						$tax_data .= '</td>';	
					}
					$edit = checkFunctionalityRight("view_tax_management.php",1);
					if($edit)
					{						
						$tax_data .= '<td style="text-align:center">';
						$tax_data .= '<input type="button" value="Edit" id="'.$row_load_data['tax_id'].'" class="btn-warning" onclick="addMoreSpec(this.id,\'edit\');"></td>';						
					}
					$delete = checkFunctionalityRight("view_tax_management.php",2);
					if($delete)
					{					
						$tax_data .= '<td><div class="controls" align="center">';
						$tax_data .= '<input type="checkbox"  id="batch'.$row_load_data['tax_id'].'" name="batch'.$row_load_data['tax_id'].'" value="'.$row_load_data['tax_id'].'"  class="css-checkbox batch">';
						$tax_data .= '<label for="batch'.$row_load_data['tax_id'].'" class="css-label" style="color:#FFF"></label>';
						$tax_data .= '</div></td>';										
					}
	        	  	$tax_data .= '</tr>';															
				}	
      			$tax_data .= '</tbody>';
      			$tax_data .= '</table>';	
				$tax_data .= $data_count;
				$response_array = array("Success"=>"Success","resp"=>$tax_data);
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
	$tax_id				= $obj->tax_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();		
	$sql_update_status 		= " UPDATE `tbl_tax_master` SET `tax_status`= '".$curr_status."' ,`tax_modified` = '".$datetime."' ,`tax_modified_by` = '".$uid."' WHERE `tax_id` like '".$tax_id."' ";
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
	$tax_id			= $obj->tax_id;
	$tax_name			= strtolower(mysqli_real_escape_string($db_con,$obj->tax_name));
	$tax_status		= $obj->tax_status;
	$response_array 	= array();
	if($tax_name != "" && $tax_id != "" && $tax_status != "")
	{
		$sql_check_spec 		= " select * from tbl_tax_master where tax_name like '".$tax_name."' and `tax_id` != '".$tax_id."' "; 
		$result_check_spec 		= mysqli_query($db_con,$sql_check_spec) or die(mysqli_error($db_con));
		$num_rows_check_spec 	= mysqli_num_rows($result_check_spec);
		if($num_rows_check_spec == 0)
		{		
			$sql_update_spec 	= " UPDATE `tbl_tax_master` SET `tax_name`='".$tax_name."',`tax_status`='".$tax_status."',";
			$sql_update_spec  .= " `tax_modified`='".$datetime."',`tax_modified_by`='".$uid."' WHERE `tax_id` = '".$tax_id."' ";		
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
			$response_array 	= array("Success"=>"fail","resp"=>"Specification ".$tax_name." already Exist");			
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
	$ar_tax_id 	= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_tax_id as $tax_id)	
	{
		$sql_delete_spec		= " DELETE FROM `tbl_tax_master` WHERE `tax_id` = '".$tax_id."' ";
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
		$sql_load_data  .= " WHERE error_module_name='specification' ";
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
			$tax_data = "";	
			$tax_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$tax_data .= '<thead>';
    	  	$tax_data .= '<tr>';
         	$tax_data .= '<th>Sr. No.</th>';
			$tax_data .= '<th>Specification Name</th>';
			//$tax_data .= '<th>Description</th>';
			//$tax_data .= '<th>Parent</th>';
			$tax_data .= '<th>Created</th>';
			$tax_data .= '<th>Created By</th>';
			$tax_data .= '<th>Modified</th>';
			$tax_data .= '<th>Modified By</th>';
			$tax_data .= '<th>Edit</th>';			
			$tax_data .= '<th>
							<div style="text-align:center">
								<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>
							</div>
						</th>';
          	$tax_data .= '</tr>';
      		$tax_data .= '</thead>';
      		$tax_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_tax_rec	= json_decode($row_load_data['error_data']);
				
				$er_tax_name	= $get_tax_rec->tax_name;
				
				$tax_data .= '<tr>';				
				$tax_data .= '<td>'.++$start_offset.'</td>';				
				$tax_data .= '<td>';
					$sql_chk_name_already_exist	= " SELECT `tax_name` FROM `tbl_tax_master` WHERE `tax_name`='".$er_tax_name."' ";
					$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
					$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);
					
					if(strcmp($num_chk_name_already_exist,"0")===0)
					{
						$tax_data .= $er_tax_name;
					}
					else
					{
						$tax_data .= '<span style="color:#E63A3A;">'.$er_tax_name.' [Already Exist]</span>';
					}
				$tax_data .= '</td>';
				//$tax_data .= '<td>'.$row_load_data['cat_description'].'</td>';
				
				$tax_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$tax_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
				$tax_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$tax_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$tax_data .= '<td style="text-align:center">';
				$tax_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreSpec(this.id,\'error\');"></td>';						
				$tax_data .= '<td>
								<div class="controls" align="center">';
				$tax_data .= '		<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$tax_data .= '		<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$tax_data .= '	</div>
							  </td>';										
	          	$tax_data .= '</tr>';															
			}	
      		$tax_data .= '</tbody>';
      		$tax_data .= '</table>';	
			$tax_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$tax_data);					
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
if((isset($obj->delete_tax_error)) == "1" && isset($obj->delete_tax_error))
{
	$ar_tax_id 		= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_tax_id as $tax_id)	
	{
		$sql_delete_cat_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$tax_id."' ";
		
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