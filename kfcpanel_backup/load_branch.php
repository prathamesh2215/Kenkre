<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid	= $_SESSION['panel_user']['id'];
$utype	= $_SESSION['panel_user']['utype'];

function insertBranch($branch_name,$branch_orgid,$branch_detail_add,$branch_state,$branch_city,$branch_pincode,$branch_meta_description,$branch_meta_title,$branch_meta_tags,$branch_status,$response_array)
{
	global $uid;
	global $db_con, $datetime;
	
	$sql_check_branch 		= " select * from tbl_branch_master where branch_name = '".$branch_name."' and branch_orgid != 0 and branch_orgid = '".$branch_orgid."' "; 
	$result_check_branch 	= mysqli_query($db_con,$sql_check_branch) or die(mysqli_error($db_con));
	$num_rows_check_branch = mysqli_num_rows($result_check_branch);
	if($num_rows_check_branch == 0)
	{
		$sql_last_rec = "Select * from tbl_branch_master order by branch_id desc LIMIT 0,1";
		$result_last_rec = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec = mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$branch_id 		= 1;				
		}
		else
		{
			$row_last_rec = mysqli_fetch_array($result_last_rec);				
			$branch_id 		= $row_last_rec['branch_id']+1;
		}
		$sql_insert_branch = " INSERT INTO `tbl_branch_master`(`branch_id`,`branch_name`, `branch_orgid`,`branch_status`, `branch_created`, `branch_created_by`,`branch_meta_description`,`branch_meta_title`,`branch_meta_tags`) ";
		$sql_insert_branch .= " VALUES ('".$branch_id."','".$branch_name."','".$branch_orgid."','".$branch_status."','".$datetime."','".$uid."','".$branch_meta_description."', '".$branch_meta_title."','".$branch_meta_tags."')";
		$result_insert_branch = mysqli_query($db_con,$sql_insert_branch) or die(mysqli_error($db_con));
		if($result_insert_branch)
		{				
			if($branch_detail_add != "" || $branch_state != "" || $branch_city != "" || $branch_pincode != "")				  
			{
				$sql_last_rec 		= "Select * from tbl_address_master order by add_id desc LIMIT 0,1";
				$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
				$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
				if($num_rows_last_rec == 0)
				{
					$add_id 		= 1;				
				}
				else
				{
					$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
					$add_id 		= $row_last_rec['add_id']+1;
				}				
				$sql_insert_address  	= "INSERT INTO `tbl_address_master`(`add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`, `add_lat_long`, `add_user_id`, `add_user_type`, `add_address_type`, `add_status`, `add_created`, `add_created_by`)";
				$sql_insert_address    .= " VALUES ('".$add_id."','".$branch_detail_add."','".$branch_state."','".$branch_city."','".$branch_pincode."','".$branch_lat_long."','".$branch_id."','branch','billing/shipping','1','".$datetime."','".$uid."')";
				$result_insert_address  = mysqli_query($db_con,$sql_insert_address) or die(mysqli_error($db_con));
				if($result_insert_address)				
				{
					  $sql_update_add_id = "UPDATE `tbl_branch_master` SET `branch_address`= '".$add_id."',`branch_modified`='".$datetime."',`branch_modified_by`='".$uid."' WHERE `branch_id` = '".$branch_id."' ";
					  $result_update_add_id = mysqli_query($db_con,$sql_update_add_id) or die(mysqli_error($db_con));
					  if($result_update_add_id)
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
						  $response_array 		= array("Success"=>"fail","resp"=>"Branch and Address inserted but address not updated in branch");											
					  }			
				  }
				else
				{
					  $response_array 		= array("Success"=>"fail","resp"=>"Branch inserted but Address not inserted");					
				  }			
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>"Address not inserted");
			}		
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
		}				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Branch ".$branch_name." already Exist");
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
	$branch_id 	= 0;
	$msg		= '';
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
			$branch_name 				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"])), ENT_HTML5);
			$branch_orgname				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["B"])), ENT_HTML5);
			$branch_addrs				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["C"])), ENT_HTML5);
			$branch_state				= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["D"]));
			$branch_city				= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"]));
			$branch_pincode				= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"]));
			$branch_meta_tag			= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["G"])), ENT_HTML5);
			$branch_meta_title			= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["H"])), ENT_HTML5);
			$branch_meta_description	= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["I"])), ENT_HTML5);
			
			if($branch_name!='' && $branch_orgname!='')
			{
				// GET ORGANISATION ID
				$sql_get_orgid			= " SELECT * FROM `tbl_oraganisation_master` WHERE `org_name`='".$branch_orgname."' ";
				$res_get_orgid			= mysqli_query($db_con, $sql_get_orgid) or die(mysqli_error($db_con));
				$row_get_orgid			= mysqli_fetch_array($res_get_orgid);
				$num_get_orgid			= mysqli_num_rows($res_get_orgid);
				$branch_orgid			= $row_get_orgid['org_id'];
				
				$query = " SELECT * FROM `tbl_branch_master` WHERE `branch_name`='".$branch_name."' AND `branch_orgid`='".$branch_orgid."' " ;
								
				$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
				$recResult 	= mysqli_fetch_array($sql);
				
				// getting state and city
				// get State Code
				$sql_get_state_code1	= " SELECT `state_id`,`state` FROM `state` WHERE `state_name` = '".$branch_state."' ";
				$res_get_state_code1	= mysqli_query($db_con, $sql_get_state_code1) or die(mysqli_error($db_con));
				$row_get_state_code1	= mysqli_fetch_array($res_get_state_code1);
				$num_get_state_code1	= mysqli_num_rows($res_get_state_code1);
				$add_state				= $row_get_state_code1['state'];
				
				// get City ID
				$sql_get_city_id1		= " SELECT `city_id` FROM `city` WHERE `state_id`='".$add_state."' AND `city_name` = '".$branch_city."' ";
				$res_get_city_id1		= mysqli_query($db_con, $sql_get_city_id1) or die(mysqli_error($db_con));
				$row_get_city_id1		= mysqli_fetch_array($res_get_city_id1);
				$num_get_city_id1		= mysqli_num_rows($res_get_city_id1);
				$add_city				= $row_get_city_id1['city_id'];
				
				$existBranchName 		= $recResult["branch_name"];
				
				if($existBranchName=="" && $num_get_orgid != 0 && $num_get_state_code1 != 0 && $num_get_city_id1 != 0)
				{
					$response_array 	= insertBranch($branch_name, $branch_orgid, $branch_addrs, $add_state, $add_city, $branch_pincode, $branch_meta_description, $branch_meta_title, $branch_meta_tag,'1',$response_array);
					if($response_array)
					{
						$insertion_flag		= 1;	
					}
					else
					{
						$insertion_flag		= 0;
					}
				}
				else
				{
					// error data array
					$error_data = array("branch_name"=>$branch_name, "branch_orgid"=>$branch_orgname, "branch_address"=>$branch_addrs, "branch_state"=>$branch_state, "branch_city"=>$branch_city, "branch_pincode"=>$branch_pincode, "branch_meta_tags"=>$branch_meta_tag, "branch_meta_title"=>$branch_meta_title, "branch_meta_description"=>$branch_meta_description);	
					
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
					
					$error_module_name	= "branch";
					$error_file			= $inputFileName;
					$error_status		= '1';
					$error_data_json	= json_encode($error_data);
					
					$sql_insert_error_log	= " INSERT INTO `tbl_error_data`(`error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`) 
												VALUES ('".$error_id."', '".$error_module_name."', '".$error_file."', '".$error_data_json."', '".$error_status."', '".$datetime."', '".$uid."') ";
					$res_insert_error_log 	= mysqli_query($db_con, $sql_insert_error_log) or die(mysqli_error($db_con));
					
					$insertion_flag	= 1;
				}
			}
			else
			{
				$insertion_flag = 0;	
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
		$response_array = array("Success"=>"fail","resp"=>"Try to upload Different File");
	}
	echo json_encode($response_array);
}
if((isset($obj->insert_req)) == "1" && isset($obj->insert_req))
{
	$branch_name			= strtolower(mysqli_real_escape_string($db_con,$obj->branch_name));
	$branch_orgid			= $obj->branch_orgid;	
	$branch_detail_add		= mysqli_real_escape_string($db_con,$obj->branch_detail_add);	
	$branch_state			= mysqli_real_escape_string($db_con,$obj->branch_state);
	$branch_city			= mysqli_real_escape_string($db_con,$obj->branch_city);
	$branch_pincode			= mysqli_real_escape_string($db_con,$obj->branch_pincode);		
	$branch_meta_description= mysqli_real_escape_string($db_con,$obj->branch_meta_description);
	$branch_meta_title		= mysqli_real_escape_string($db_con,$obj->branch_meta_title);
	$branch_meta_tags		= mysqli_real_escape_string($db_con,$obj->branch_meta_tags);
	$branch_status			= mysqli_real_escape_string($db_con,$obj->branch_status);	
	$response_array 		= array();	

	if($branch_name != "" && $branch_orgid != "" && $branch_status != "")
	{
		$response_array 	= insertBranch($branch_name,$branch_orgid,$branch_detail_add,$branch_state,$branch_city,$branch_pincode,$branch_meta_description,$branch_meta_title,$branch_meta_tags,$branch_status,$response_array);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{
	$branch_id				= mysqli_real_escape_string($db_con,$obj->branch_id);
	$branch_name			= strtolower(mysqli_real_escape_string($db_con,$obj->branch_name));
	$branch_orgid			= $obj->branch_orgid;	
	$branch_detail_add		= mysqli_real_escape_string($db_con,$obj->branch_detail_add);	
	$branch_state			= mysqli_real_escape_string($db_con,$obj->branch_state);
	$branch_city			= mysqli_real_escape_string($db_con,$obj->branch_city);
	$branch_pincode			= mysqli_real_escape_string($db_con,$obj->branch_pincode);		
	$branch_meta_description= mysqli_real_escape_string($db_con,$obj->branch_meta_description);
	$branch_meta_title		= mysqli_real_escape_string($db_con,$obj->branch_meta_title);
	$branch_meta_tags		= mysqli_real_escape_string($db_con,$obj->branch_meta_tags);
	$branch_status			= mysqli_real_escape_string($db_con,$obj->branch_status);	
	$branch_lat_long		= "";
	$response_array 		= array();	
	
	if($branch_name != "" && $branch_orgid != "" && $branch_status != "" && $branch_id != "")
	{
		$sql_check_branch 		= " SELECT * FROM tbl_branch_master WHERE branch_name LIKE '".$branch_name."' AND branch_id != '".$branch_id."' AND `branch_orgid` = '".$branch_orgid."' ";
		$result_check_branch 	= mysqli_query($db_con,$sql_check_branch) or die(mysqli_error($db_con));
		$num_rows_check_branch  = mysqli_num_rows($result_check_branch);
		if($num_rows_check_branch == 0)
		{
			$sql_update_branch = " UPDATE `tbl_branch_master` SET `branch_name`='".$branch_name."',`branch_orgid`='".$branch_orgid."',`branch_status`='".$branch_status."',";
			$sql_update_branch .= " `branch_meta_tags` = '".$branch_meta_tags."',`branch_meta_description` = '".$branch_meta_description."',`branch_meta_title`='".$branch_meta_title."',";
			$sql_update_branch .= " `branch_modified`='".$datetime."',`branch_modified_by`='".$uid."' WHERE `branch_id` = '".$branch_id."' ";
			$result_update_branch = mysqli_query($db_con,$sql_update_branch) or die(mysqli_error($db_con));
			if($result_update_branch)
			{
				$sql_get_branch 		= " SELECT * FROM tbl_branch_master WHERE branch_id != '".$branch_id."'";
				$result_get_branch		= mysqli_query($db_con,$sql_get_branch) or die(mysqli_error($db_con));
				$row_get_branch		 	= mysqli_fetch_array($result_branch_address);
				if($row_get_branch['branch_address'] != "" || $row_get_branch['branch_address'] != NULL)
				{
					$sql_update_branch_add = " UPDATE `tbl_address_master` SET `add_details`= '".$branch_detail_add."',`add_state`= '".$branch_state."',";
					$sql_update_branch_add .= "	`add_city`='".$branch_city."',`add_pincode`='".$branch_pincode."',";
					$sql_update_branch_add .= "	`add_address_type`='".$branch_address_type."',`add_status`='".$branch_address_status."',`add_modified`='".$datetime."',`add_modified_by`='".$uid."' WHERE `add_user_id` like '".$branch_id."' and add_user_type = 'branch' ";
					$result_update_branch_add = mysqli_query($db_con,$sql_update_branch_add) or die(mysqli_error($db_con));
					if($result_update_branch_add)
					{
						$response_array = array("Success"=>"Success","resp"=>"Update Succesfull");													
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Update failed");								
					}
				}
				else
				{
					$sql_last_rec 		= "Select * from tbl_address_master order by add_id desc LIMIT 0,1";
					$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
					$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
					if($num_rows_last_rec == 0)
					{
						$add_id 		= 1;				
					}
					else
					{
						$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
						$add_id 		= $row_last_rec['add_id']+1;
					}				
					$sql_insert_address  	= "INSERT INTO `tbl_address_master`(`add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`, `add_lat_long`, `add_user_id`, `add_user_type`, `add_address_type`, `add_status`, `add_created`, `add_created_by`)";
					$sql_insert_address    .= " VALUES ('".$add_id."','".$branch_detail_add."','".$branch_state."','".$branch_city."','".$branch_pincode."','".$branch_lat_long."','".$branch_id."','branch','billing/shipping','1','".$datetime."','".$uid."')";
					$result_insert_address  = mysqli_query($db_con,$sql_insert_address) or die(mysqli_error($db_con));
					if($result_insert_address)				
					{
						$sql_update_add_id = "UPDATE `tbl_branch_master` SET `branch_address`= '".$add_id."',`branch_modified`='".$datetime."',`branch_modified_by`='".$uid."' WHERE `branch_id` = '".$branch_id."' ";
						$result_update_add_id = mysqli_query($db_con,$sql_update_add_id) or die(mysqli_error($db_con));
					  	if($result_update_add_id)
						{
							  $response_array 		= array("Success"=>"Success","resp"=>"Branch Updated and New Address inserted and updated in branch");
					  	}		
					  	else
					  	{
							  $response_array 		= array("Success"=>"Success","resp"=>"Branch Updated and New Address inserted but not updated in branch");
						}			
				  	}
					else
					{
						$response_array 		= array("Success"=>"Success","resp"=>"Branch Updated but Address not inserted");					
					}						
				}
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Update failed");					
			}					
		}		
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Branch <b>".ucwords($branch_name)."</b> already Exists.");	
		}		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->load_branch)) == "1" && isset($obj->load_branch))
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
		
		$sql_load_data  = " SELECT `branch_id`, `branch_name`, `branch_orgid`, `branch_status`, `branch_created`, `branch_modified`,";		
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tbm.branch_created_by) as branch_by_created, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tbm.branch_modified_by) as branch_by_modified, ";
		$sql_load_data  .= " (SELECT `org_name` FROM `tbl_oraganisation_master` WHERE `org_id` = tbm.branch_orgid) as org_name ";				
		$sql_load_data  .= " FROM `tbl_branch_master` tbm WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND branch_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (branch_name like '%".$search_text."%'or branch_created like '%".$search_text."%' or branch_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY branch_id ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$branch_data = "";	
			$branch_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$branch_data .= '<thead>';
    	  	$branch_data .= '<tr>';
         	$branch_data .= '<th style="text-align:center">Sr No.</th>';
			$branch_data .= '<th style="text-align:center">branch Id</th>';
			$branch_data .= '<th style="text-align:center">Branch Name</th>';
			$branch_data .= '<th style="width:6%;text-align:center">Organisation</th>';
			$branch_data .= '<th style="text-align:center">Created</th>';
			$branch_data .= '<th style="text-align:center">Created By</th>';
			$branch_data .= '<th style="text-align:center">Modified</th>';
			$branch_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_branch.php",3);
			if($dis)
			{			
				$branch_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_branch.php",1);
			if($edit)
			{			
				$branch_data .= '<th style="text-align:center">Edit</th>';			
			}
			$del = checkFunctionalityRight("view_branch.php",2);
			if($del)
			{			
				$branch_data .= '<th style="text-align:center">';
				$branch_data .= '<div style="text-align:center">';
				$branch_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/>';
				$branch_data .= '</div></th>';
			}
			$branch_data .= '</tr>';
      		$branch_data .= '</thead>';
      		$branch_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$branch_data .= '<tr>';				
				$branch_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$branch_data .= '<td style="text-align:center">'.$row_load_data['branch_id'].'</td>';
				$branch_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['branch_name']).'" class="btn-link" id="'.$row_load_data['branch_id'].'" onclick="addMoreBranch(this.id,\'view\');"></td>';
				$branch_data .= '<td style="text-align:center">'.ucwords($row_load_data['org_name']).'</td>';
				$branch_data .= '<td style="text-align:center">'.$row_load_data['branch_created'].'</td>';
				$branch_data .= '<td style="text-align:center">'.ucwords($row_load_data['branch_by_created']).'</td>';
				$branch_data .= '<td style="text-align:center">'.$row_load_data['branch_modified'].'</td>';
				$branch_data .= '<td style="text-align:center">'.ucwords($row_load_data['branch_by_modified']).'</td>';
				$dis = checkFunctionalityRight("view_branch.php",3);
				if($dis)
				{				
					$branch_data .= '<td style="text-align:center">';	
					if($row_load_data['branch_status'] == 1)
					{
						$branch_data .= '<input type="button" value="Active" id="'.$row_load_data['branch_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$branch_data .= '<input type="button" value="Inactive" id="'.$row_load_data['branch_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$branch_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_branch.php",1);
				if($edit)
				{				
					$branch_data .= '<td style="text-align:center">';
					$branch_data .= '<input type="button" value="Edit" id="'.$row_load_data['branch_id'].'" class="btn-warning" onclick="addMoreBranch(this.id,\'edit\');"></td>';				
				}
				$del = checkFunctionalityRight("view_branch.php",2);
				if($del)
				{					
					$branch_data .= '<td><div class="controls" align="center">';
					$branch_data .= '<input type="checkbox" value="'.$row_load_data['branch_id'].'" id="batch'.$row_load_data['branch_id'].'" name="batch'.$row_load_data['branch_id'].'" class="css-checkbox batch">';
					$branch_data .= '<label for="batch'.$row_load_data['branch_id'].'" class="css-label"></label>';
					$branch_data .= '</div></td>';										
				}
	          	$branch_data .= '</tr>';															
			}	
      		$branch_data .= '</tbody>';
      		$branch_data .= '</table>';	
			$branch_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$branch_data);					
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
if((isset($obj->load_add_branch_part)) == "1" && isset($obj->load_add_branch_part))
{
	$branch_id 	= $obj->branch_id;
	$req_type 	= $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($branch_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$branch_id."' "; // this ind_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_branch_data		= json_decode($row_error_data['error_data']);
		}
		else if(($branch_id != "" && $req_type == "edit") || ($branch_id != "" && $req_type == "view"))
		{
			$sql_branch_data 		= "Select * from tbl_branch_master where branch_id = '".$branch_id."' ";
			$result_branch_data 	= mysqli_query($db_con,$sql_branch_data) or die(mysqli_error($db_con));
			$row_branch_data		= mysqli_fetch_array($result_branch_data);		
		}	
		if($req_type != "add")
		{
			$sql_branch_address 	= "SELECT * FROM `tbl_address_master` WHERE `add_id` = ";
			if($req_type == "error")
			{
				$sql_branch_address .= "'".$row_branch_data->branch_address."'";
			}
			else
			{
				$sql_branch_address .= "'".$row_branch_data['branch_address']."'";				
			}
			$sql_branch_address 	.= " and `add_user_type` = 'branch' ";	
			$result_branch_address	= mysqli_query($db_con,$sql_branch_address) or die(mysqli_error($db_con));
			$row_branch_address		= mysqli_fetch_array($result_branch_address);
		}
		$data = '';
		if($branch_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="branch_id" value="'.$row_branch_data['branch_id'].'">';
		}
		elseif($branch_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$branch_id.'">';
		}	 	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Branch Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="branch_name" name="branch_name" class="input-large keyup-char" data-rule-required="true" ';
		if($branch_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_branch_data['branch_name']).'"'; 
		}
		elseif($branch_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_branch_data->branch_name).'"'; 			
		}
		elseif($branch_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_branch_data['branch_name']).'" disabled';
		}		
		$data .= '/>';
		$data .= '<span class="warning-char">characters only.</span>';
		$data .= '</div>';
		$data .= '</div> <!-- Branch Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Organisation/Company<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select name="branch_orgid" id="branch_orgid" class="select2-me input-large" data-rule-required="true">';
			$data .= '<option value="">Select Organisation/Company</option>';
			$sql_get_org = "SELECT distinct org_id,org_name FROM `tbl_oraganisation_master` where org_status = '1' ";
			$result_get_org = mysqli_query($db_con,$sql_get_org) or die(mysqli_error($db_con));														
			while($row_get_org = mysqli_fetch_array($result_get_org))
			{	
				$data .= '<option value="'.$row_get_org['org_id'].'"';		
				if($req_type == "edit")												
				{
					if($row_get_org['org_id'] == $row_branch_data['branch_orgid'])
					{
						$data .= 'selected';
					}
				}
				$data .= '>'.ucwords($row_get_org['org_name']).'</option>';
			}			
			$data .= '</select>';			
		}
		elseif($req_type == "view")		
		{
			$sql_get_org = "SELECT org_id,org_name FROM `tbl_oraganisation_master` where org_status = '1' and org_id = '".$row_branch_data['branch_orgid']."' ";
			$result_get_org = mysqli_query($db_con,$sql_get_org) or die(mysqli_error($db_con));														
			$row_get_org = mysqli_fetch_array($result_get_org);
			$data .= $row_get_org['org_name'];
		}
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Detail Address <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="branch_detail_add" name="branch_detail_add" >';
		if($branch_id != "" && $req_type == "error")
		{
			$data .= $row_branch_address->add_details;
		}
		elseif($branch_id != "" && $req_type == "view")
		{
			$data .= $row_branch_address['add_details'];
		}		
		else
		{
			$data .= $row_branch_address['add_details'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group " style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">State <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select id="branch_state" name="branch_state" class="select2-me input-large" onChange="getCity(this.value,\'branch_city\')">';
			$data .= '<option value="">Select State</option>';
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			while($row_get_state = mysqli_fetch_array($res_get_state))
			{
				$data 			.= '<option value="'.$row_get_state['state'].'"';
				if($req_type == "edit")												
				{
					if($row_get_state['state'] == $row_branch_address['add_state'])
					{
						$data .= 'selected';
					}
				}
				$data 			.= '>'.ucwords($row_get_state['state_name']).'</option>';
			}			
			$data 				.= '</select>';		
		}
		elseif($req_type == "view")		
		{
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' and state = '".$row_branch_address['add_state']."' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			$row_get_state = mysqli_fetch_array($res_get_state);
			$data 		  .= $row_get_state['state_name'];
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">City <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select id="branch_city" name="branch_city" class="select2-me input-large" >';
			$data .= '<option value="">Select City</option>';
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` WHERE `state_id` = '".$row_branch_address['add_state']."' ";
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			while($row_get_city = mysqli_fetch_array($res_get_city))
			{
				$data 			.= '<option value="'.$row_get_city['city_id'].'"';
				if($req_type == "edit")												
				{
					if($row_get_city['city_id'] == $row_branch_address['add_city'])
					{
						$data .= 'selected';
					}
				}
				$data 			.= '>'.ucwords($row_get_city['city_name']).'</option>';
			}			
			$data 				.= '</select>';		
		}
		elseif($req_type == "view")		
		{
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` WHERE `city_id` = '".$row_branch_address['add_city']."' ";
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			$row_get_city = mysqli_fetch_array($res_get_city);
			$data 		  .= $row_get_city['city_name'];
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Pin Code <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="branch_pincode" name="branch_pincode" placeholder="Pin Code" class="input-large" maxlength="6" onKeyPress="return isNumberKey(event)"  ';
		if($branch_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_branch_address['add_pincode'].'"'; 
		}
		elseif($branch_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_branch_address->add_pincode.'"'; 			
		}
		elseif($branch_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_branch_address['add_pincode'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Tags <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="branch_meta_tags" placeholder="Meta Tags" name="branch_meta_tags" class="input-xlarge" ';
		if($branch_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_branch_data['branch_meta_tags']).'"'; 
		}
		elseif($branch_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_branch_data->branch_meta_tags).'"'; 			
		}
		elseif($branch_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_branch_data['branch_meta_tags']).'" disabled';
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Tags-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Description <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="branch_meta_description" name="branch_meta_description" placeholder="Meta Description">';
		if($branch_id != "" && $req_type == "error")
		{
			$data .= $row_branch_data->branch_meta_description;		
		}
		elseif($branch_id != "" && $req_type == "view")
		{
			$data .= $row_branch_data['branch_meta_description'];					
		}		
		else
		{
			$data .= $row_branch_data['branch_meta_description'];
		}		
		$data .= '</textarea>';		
		$data .= '</div>';
		$data .= '</div> <!--Meta Description-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Title <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="branch_meta_title" placeholder="Meta Title" name="branch_meta_title" class="input-large" ';
		if($branch_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_branch_data['branch_meta_title']).'"'; 
		}
		elseif($branch_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_branch_data->branch_meta_title).'"';
		}
		elseif($branch_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_branch_data['branch_meta_title']).'" disabled';
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Title-->';		
		$data .= '<div class="control-group span12">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($branch_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="branch_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_branch.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_branch_data->branch_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="branch_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_branch_data->branch_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($branch_id != "" && $req_type == "view")
		{
			if($row_branch_data['branch_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_branch_data['branch_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="branch_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_branch.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_branch_data['branch_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="branch_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_branch_data['branch_status'] == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div> <!--Status-->';
		$data .= '<div class="form-actions">';
		if($req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Save Branch</button>';			
		}
		elseif($req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Branch</button>';			
		}
		elseif($req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update Branch</button>';			
		}		
		$data .= '</div> <!-- Save and cancel -->';			
		$data .= '<script type="text/javascript">';
		$data .= '$("#branch_orgid").select2();';
		$data .= '$("#branch_state").select2();';
		$data .= '$("#branch_city").select2();';
		$data .= 'CKEDITOR.replace("branch_detail_add",{height:"150", width:"100%"});';
		$data .= 'CKEDITOR.replace("branch_meta_description",{height:"150", width:"100%"});';		
		if($branch_id != "" && $req_type == "view")
		{
			$data .= '$("#branch_detail_add").prop("disabled","true");';
			$data .= '$("#branch_meta_description").prop("disabled","true");';			
		}	
		$data .= '</script>';
		$response_array = array("Success"=>"Success","resp"=>$data);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Not received");		
	}
	echo json_encode($response_array);
}
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$branch_id				= $obj->branch_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();
	$sql_update_status 		= " UPDATE `tbl_branch_master` SET `branch_status`= '".$curr_status."' ,`branch_modified` = '".$datetime."' ,`branch_modified_by` = '".$uid."' WHERE `branch_id` like '".$branch_id."' ";
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
if((isset($obj->change_sort_order)) == "1" && isset($obj->change_sort_order))
{
	$branch_id				= $obj->branch_id;
	$new_order				= $obj->new_order;
	$response_array 		= array();		
	
	$sql_check_self_order	= " SELECT * from tbl_branch_master WHERE branch_id LIKE '".$branch_id."' ";
	$result_check_self_order= mysqli_query($db_con,$sql_check_self_order) or die(mysqli_error($db_con));	
	$row_check_self_order	= mysqli_fetch_array($result_check_self_order);
	
	$self_parent			= $row_check_self_order['branch_orgid'];
	$self_order				= $row_check_self_order['branch_sort_order'];
	
	$sql_check_order 		= " SELECT * from tbl_branch_master WHERE branch_sort_order LIKE '".$new_order."' and branch_type like '".$self_parent."' ";
	$result_check_order		= mysqli_query($db_con,$sql_check_order) or die(mysqli_error($db_con));	
	$num_rows_check_order	= mysqli_num_rows($result_check_order);
	if($num_rows_check_order > 0)
	{
		$row_check_order 	= mysqli_fetch_array($result_check_order);
		$other_branch		= $row_check_order['branch_id'];		
		$other_parent		= $row_check_order['branch_orgid'];
		$other_order		= $row_check_order['branch_sort_order'];
		if($self_parent == $other_parent)
		{
			if($other_order == $new_order)
			{
				$sql_update_sort1 		= " UPDATE `tbl_branch_master` SET `branch_sort_order`= '".$self_order."' ,`branch_modified` = '".$datetime."' ,`branch_modified_by` = '".$uid."' WHERE `branch_id` like '".$other_branch."' ";	
				$result_update_sort2 	= mysqli_query($db_con,$sql_update_sort1) or die(mysqli_error($db_con));			
				$sql_update_sort2 		= " UPDATE `tbl_branch_master` SET `branch_sort_order`= '".$new_order."' ,`branch_modified` = '".$datetime."' ,`branch_modified_by` = '".$uid."' WHERE `branch_id` like '".$branch_id."' ";
				$result_update_sort2 	= mysqli_query($db_con,$sql_update_sort2) or die(mysqli_error($db_con));	
				$response_array 		= array("Success"=>"Success","resp"=>"Sort Order exchanged Successfully.");
			}
			else
			{					
				$sql_update_sort 	= " UPDATE `tbl_branch_master` SET `branch_sort_order`= '".$new_order."' ,`branch_modified` = '".$datetime."' ,`branch_modified_by` = '".$uid."' WHERE `branch_id` like '".$branch_id."' ";
				$result_update_sort = mysqli_query($db_con,$sql_update_sort) or die(mysqli_error($db_con));	
				$response_array 	= array("Success"=>"Success","resp"=>"Sort Order Updated Successfully.");
			}
		}
		else
		{
				$response_array = array("Success"=>"fail","resp"=>"Sort Order Update Failed.");
		}		
	}
	else
	{
		$sql_update_sort =	" UPDATE `tbl_branch_master` SET `branch_sort_order`= '".$new_order."' ,`branch_modified` = '".$datetime."' ,`branch_modified_by` = '".$uid."' WHERE `branch_id` like '".$branch_id."' ";
		$result_update_sort = mysqli_query($db_con,$sql_update_sort) or die(mysqli_error($db_con));	
		$response_array = array("Success"=>"Success","resp"=>"Sort Order Updated Successfully.");			
	}
	echo json_encode($response_array);	
}

if((isset($obj->delete_branch)) == "1" && isset($obj->delete_branch))
{
	$response_array = array();		
	$ar_branch_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_branch_id as $branch_id)	
	{
		$sql_delete_branch		= " DELETE FROM `tbl_branch_master` WHERE `branch_id` = '".$branch_id."' ";
		$result_delete_branch	= mysqli_query($db_con,$sql_delete_branch) or die(mysqli_error($db_con));			
		if($result_delete_branch)
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
		$sql_load_data  .= " WHERE error_module_name='branch' ";
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
			$branch_data = "";	
			$branch_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$branch_data .= '<thead>';
    	  	$branch_data .= '<tr>';
         	$branch_data .= '<th>Sr. No.</th>';
			$branch_data .= '<th>branch Name</th>';
			$branch_data .= '<th>Organisation</th>';
			$branch_data .= '<th>Created</th>';
			$branch_data .= '<th>Created By</th>';
			$branch_data .= '<th>Modified</th>';
			$branch_data .= '<th>Modified By</th>';
			$branch_data .= '<th>Edit</th>';			
			$branch_data .= '<th>
							<div style="text-align:center">
								<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>
							</div>
						</th>';
          	$branch_data .= '</tr>';
      		$branch_data .= '</thead>';
      		$branch_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_branch_rec	= json_decode($row_load_data['error_data']);
				
				$er_branch_name		= $get_branch_rec->branch_name;
				$er_branch_orgid	= $get_branch_rec->branch_orgid;
				
				
				
				$branch_data .= '<tr>';				
				$branch_data .= '<td>'.++$start_offset.'</td>';	
				$branch_data .= '<td>'.$er_branch_name.'</td>';				
				/*$branch_data .= '<td>';
					
					$sql_chk_name_already_exist	= " SELECT `branch_name` FROM `tbl_branch_master` WHERE `branch_name`='".$er_branch_name."' ";
					$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
					$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);
					
					if(strcmp($num_chk_name_already_exist,"0")===0)
					{
						$branch_data .= $er_branch_name;
					}
					else
					{
						$branch_data .= '<span style="color:#E63A3A;">'.$er_branch_name.' [Already Exist]</span>';
					}
				$branch_data .= '</td>';*/
				$branch_data .= '<td>';
				$sql_chk_orgname_already_exist	= " SELECT `org_name` FROM `tbl_oraganisation_master` WHERE `org_name`='".$er_branch_orgid."' ";
				$res_chk_orgname_already_exist = mysqli_query($db_con, $sql_chk_orgname_already_exist) or die(mysqli_error($db_con));
				$num_chk_orgname_already_exist = mysqli_num_rows($res_chk_orgname_already_exist);
				
				if(strcmp($num_chk_orgname_already_exist,"0")!==0)
				{
					$branch_data .= $er_branch_orgid;
				}
				else
				{
					$branch_data .= '<span style="color:#E63A3A;">'.$er_branch_orgid.' [Not Exist]</span>';
				}
				$branch_data .= '</td>';
				$branch_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$branch_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
				$branch_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$branch_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$branch_data .= '<td style="text-align:center">';
				$branch_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreBranch(this.id,\'error\');"></td>';								
				$branch_data .= '<td>
								<div class="controls" align="center">';
				$branch_data .= '		<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$branch_data .= '		<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$branch_data .= '	</div>
							  </td>';										
	          	$branch_data .= '</tr>';															
			}	
      		$branch_data .= '</tbody>';
      		$branch_data .= '</table>';	
			$branch_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$branch_data);					
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

if((isset($obj->delete_branch_error)) == "1" && isset($obj->delete_branch_error))
{
	$ar_branch_id 		= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_branch_id as $branch_id)	
	{		
		$sql_delete_branch_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$branch_id."' ";
		$result_delete_branch_error= mysqli_query($db_con,$sql_delete_branch_error) or die(mysqli_error($db_con));			
		
		if($result_delete_branch_error)
		{
			$del_flag_error = 1;	
		}			
	}
	if($del_flag_error == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");
	}
	
	echo json_encode($response_array);	
}

?>