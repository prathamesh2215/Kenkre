<?php
include("include/routines.php");
$json 	= file_get_contents('php://input');
$obj 	= json_decode($json);
$uid	= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

function newAddressId()
{
	global $db_con;
	global $uid;	
	$add_id				= 0;
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
	return $add_id;
}
function insertNewAddress($add_details,$add_state,$add_city,$add_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status)
{
	global $db_con, $datetime;
	global $uid;
	$add_id 				= newAddressId();
	$sql_insert_address  	= "INSERT INTO `tbl_address_master`(`add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`, `add_lat_long`, `add_user_id`, `add_user_type`, `add_address_type`, `add_status`, `add_created`, `add_created_by`)";
	$sql_insert_address    .= " VALUES ('".$add_id."','".$add_details."','".$add_state."','".$add_city."','".$add_pincode."','".$add_lat_long."','".$add_user_id."','".$add_user_type."','".$add_address_type."','".$add_status."','".$datetime."','".$uid."')";
	$result_insert_address  = mysqli_query($db_con,$sql_insert_address) or die(mysqli_error($db_con));
	if($result_insert_address)
	{
		return $add_id;
	}
	else
	{
		return $add_id = "";
	}
}
function updateOldAddress($add_id,$add_details,$add_state,$add_city,$add_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status)
{
	global $db_con, $datetime;
	global $uid;
	$sql_update_address  	= " UPDATE `tbl_address_master` SET `add_details`= '".$add_details."',`add_state`='".$add_state."',`add_city`='".$add_city."'";
	$sql_update_address  	.= " ,`add_pincode`='".$add_pincode."',`add_lat_long`='".$add_lat_long."',`add_user_id`='".$add_user_id."',`add_user_type`='".$add_user_type."'";
	$sql_update_address  	.= " ,`add_address_type`='".$add_address_type."',`add_status`='".$add_status."',`add_modified`='".$datetime."',`add_modified_by`='".$uid."' WHERE `add_id`	='".$add_id."' ";
	$result_update_address  = mysqli_query($db_con,$sql_update_address) or die(mysqli_error($db_con));
	if($result_update_address)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function insertEmployee($emp_name,$emp_orgid,$emp_branchid,$emp_desg,$emp_office_email,$emp_primary_email,$emp_secondary_email,$emp_landline,$emp_primary_mobile,$emp_secondary_mobile,$emp_status,$perm_details_add,$perm_city,$perm_state,$perm_pincode,$corrs_details_add,$corrs_city,$corrs_state,$corrs_pincode)
{
	global $db_con, $datetime;
	global $uid;
	global $obj;
	$sql_check_emp 		= " select * from tbl_employee_master where emp_name = '".$emp_name."' and emp_orgid != 0 and emp_orgid = '".$emp_orgid."' "; 
	$result_check_emp 	= mysqli_query($db_con,$sql_check_emp) or die(mysqli_error($db_con));
	$num_rows_check_emp = mysqli_num_rows($result_check_emp);
	if($num_rows_check_emp == 0)
	{
		$sql_last_rec = "Select * from tbl_employee_master order by emp_id desc LIMIT 0,1";
		$result_last_rec = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec = mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$emp_id 		= 1;				
		}
		else
		{
			$row_last_rec = mysqli_fetch_array($result_last_rec);				
			$emp_id 		= $row_last_rec['emp_id']+1;
		}
		$sql_insert_emp = " INSERT INTO `tbl_employee_master`(`emp_id`,`emp_name`, `emp_orgid`,`emp_status`, `emp_created`, `emp_created_by`,`emp_desg`,`emp_office_email`,`emp_primary_email`,`emp_primary_mobile`, ";
		$sql_insert_emp .=" `emp_secondary_mobile`,`emp_landline`,`emp_secondary_email`,`emp_branchid`) values ('".$emp_id."','".$emp_name."','".$emp_orgid."','".$emp_status."','".$datetime."','".$uid."',";
		$sql_insert_emp .=" '".$emp_desg."','".$emp_office_email."','".$emp_primary_email."','".$emp_primary_mobile."','".$emp_secondary_mobile."','".$emp_landline."','".$emp_secondary_email."','".$emp_branchid."')";
		$result_insert_emp = mysqli_query($db_con,$sql_insert_emp) or die(mysqli_error($db_con));
		if($result_insert_emp)
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
			if($perm_details_add != "" || $perm_city != "" || $perm_state!= "" || $perm_pincode!= "" || $corrs_details_add !="" || $corrs_city!= "" ||$corrs_state != "" ||$corrs_pincode != "")
			{
				if(($perm_details_add != $corrs_details_add) || ($perm_pincode != $corrs_pincode))
				{
					$add_user_id		= $emp_id;
					$add_user_type 		= "employee";
					$add_address_type	= "correspondence";
					$add_status			= 1;		
					$add_lat_long		= "empty";			
					$emp_corrs_address_id = insertNewAddress($corrs_details_add,$corrs_state,$corrs_city,$corrs_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
					if($emp_corrs_address_id != "")				
					{
						$sql_emp_corrs_address = "UPDATE `tbl_employee_master` SET `emp_corrs_address`='".$emp_corrs_address_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
						$result_emp_corrs_address 	=  mysqli_query($db_con,$sql_emp_corrs_address) or die(mysqli_error($db_con));
						if($result_emp_corrs_address)
						{
							$response_array = array("Success"=>"Success","resp"=>"Address Updated");
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
					}
					$add_user_id		= $emp_id;
					$add_user_type 		= "employee";
					$add_address_type	= "permanent";					
					$add_status			= 1;
					$add_lat_long		= "empty";					
					$emp_perm_address_id = insertNewAddress($perm_details_add,$perm_state,$perm_city,$perm_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
					if($emp_perm_address_id != "")				
					{
						$sql_emp_perm_address 		= "UPDATE `tbl_employee_master` SET `emp_perm_address`='".$emp_perm_address_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
						$result_emp_perm_address 	=  mysqli_query($db_con,$sql_emp_perm_address) or die(mysqli_error($db_con));					
						if($result_emp_perm_address)
						{
							$response_array = array("Success"=>"Success","resp"=>"Address Updated");
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
					}													
				}
				elseif($perm_details_add == $corrs_details_add && $perm_state == $corrs_state && $perm_city == $corrs_city && $perm_pincode == $corrs_pincode)
				{
					$add_user_id		= $emp_id;
					$add_user_type 		= "employee";
					$add_address_type	= "permanent/correspondence";
					$add_status			= 1;
					$add_lat_long		= "empty";						
					$emp_address_id 	= insertNewAddress($perm_details_add,$perm_state,$perm_city,$perm_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
					if($emp_address_id != "")				
					{
						$sql_emp_corrs_perm_address 	= " UPDATE `tbl_employee_master` SET `emp_corrs_address`='".$emp_address_id."',`emp_perm_address`='".$emp_address_id."',";
						$sql_emp_corrs_perm_address 	.= " `emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
						$result_emp_corrs_perm_address 	=  mysqli_query($db_con,$sql_emp_corrs_perm_address) or die(mysqli_error($db_con));					
						if($result_emp_corrs_perm_address)
						{
							$response_array = array("Success"=>"Success","resp"=>"Address Updated");
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
					}						
				}
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>"Record Inserted.Address not Inserted");								
			}			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
		}			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>'Employee <b> "'.ucwords($emp_name).'" </b> Already Exist');
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
	$emp_id 	= 0;
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
			$emp_name 			= trim($allDataInSheet[$i]["A"]);
			$emp_org			= trim($allDataInSheet[$i]["B"]);
			$emp_branch			= trim($allDataInSheet[$i]["C"]);
			$emp_designation	= trim($allDataInSheet[$i]["D"]);
			$emp_offiemail		= trim($allDataInSheet[$i]["E"]);
			$emp_priemail		= trim($allDataInSheet[$i]["F"]);
			$emp_secemail		= trim($allDataInSheet[$i]["G"]);
			$emp_landline		= trim($allDataInSheet[$i]["H"]);
			$emp_primono		= trim($allDataInSheet[$i]["I"]);
			$emp_secmono		= trim($allDataInSheet[$i]["J"]);
			$emp_peraaddrs		= trim($allDataInSheet[$i]["K"]);
			$emp_perastate		= trim($allDataInSheet[$i]["L"]);
			$emp_peracity		= trim($allDataInSheet[$i]["M"]);
			$emp_perapincode	= trim($allDataInSheet[$i]["N"]);
			$emp_tempaddrs		= trim($allDataInSheet[$i]["O"]);
			$emp_tempstate		= trim($allDataInSheet[$i]["P"]);
			$emp_tempcity		= trim($allDataInSheet[$i]["Q"]);
			$emp_temppincode	= trim($allDataInSheet[$i]["R"]);
			
			if($emp_name!='' && $emp_org!='' && $emp_branch!='' && $emp_designation!='' && $emp_offiemail!='' && $emp_priemail!='' && $emp_primono!='' && $emp_peraaddrs!='' && $emp_perastate!='' && $emp_peracity!='' && $emp_perapincode!='')
			{
				$query = " SELECT * FROM `tbl_employee_master` 
							WHERE `emp_primary_email`='".$emp_priemail."'
								AND `emp_office_email`='".$emp_offiemail."'
								AND `emp_primary_mobile`='".$emp_primono."' " ;
								
				$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
				$recResult 	= mysqli_fetch_array($sql);
				
				// get designation id
				$sql_get_desig	= " SELECT desg_id FROM `tbl_designation_type` WHERE `desg_name` = '".$emp_designation."' ";
				$res_get_desig 	= mysqli_query($db_con, $sql_get_desig) or die(mysqli_error($db_con));
				$row_get_desig 	= mysqli_fetch_array($res_get_desig);
				$num_get_desig	= mysqli_num_rows($res_get_desig);
				$desg_id		=  $row_get_desig['desg_id'];
				
				// get org id
				$sql_get_orgid	= " SELECT org_id FROM `tbl_oraganisation_master` WHERE `org_name` = '".$emp_org."' ";
				$res_get_orgid	= mysqli_query($db_con, $sql_get_orgid) or die(mysqli_error($db_con));
				$row_get_orgid 	= mysqli_fetch_array($res_get_orgid);
				$num_get_orgid	= mysqli_num_rows($res_get_orgid);
				$org_id			=  $row_get_orgid['org_id'];
				
				// get branch id
				$sql_get_branch	= " SELECT `branch_id` FROM `tbl_branch_master` WHERE `branch_name` = '".$emp_branch."' ";
				$res_get_branch = mysqli_query($db_con, $sql_get_branch) or die(mysqli_error($db_con));
				$row_get_branch = mysqli_fetch_array($res_get_branch);
				$num_get_branch	= mysqli_num_rows($res_get_branch);
				$branch_id		=  $row_get_branch['branch_id'];
				
				// getting state and city
				// get State Code
				$sql_get_state_code1	= " SELECT `state_id`,`state` FROM `state` WHERE `state_name` = '".$emp_perastate."' ";
				$res_get_state_code1	= mysqli_query($db_con, $sql_get_state_code1) or die(mysqli_error($db_con));
				$row_get_state_code1	= mysqli_fetch_array($res_get_state_code1);
				$num_get_state_code1	= mysqli_num_rows($res_get_state_code1);
				$add_state1				= $row_get_state_code1['state'];
				
				// get City ID
				$sql_get_city_id1		= " SELECT `city_id` FROM `city` WHERE `state_id`='".$add_state1."' AND `city_name` = '".$emp_peracity."' ";
				$res_get_city_id1		= mysqli_query($db_con, $sql_get_city_id1) or die(mysqli_error($db_con));
				$row_get_city_id1		= mysqli_fetch_array($res_get_city_id1);
				$num_get_city_id1		= mysqli_num_rows($res_get_city_id1);
				$add_city1				= $row_get_city_id1['city_id'];
				
				// get State Code
				$sql_get_state_code2	= " SELECT `state_id`,`state` FROM `state` WHERE `state_name` = '".$emp_tempstate."' ";
				$res_get_state_code2	= mysqli_query($db_con, $sql_get_state_code2) or die(mysqli_error($db_con));
				$row_get_state_code2	= mysqli_fetch_array($res_get_state_code2);
				$num_get_state_code2	= mysqli_num_rows($res_get_state_code2);
				$add_state2				= $row_get_state_code2['state'];
				
				// get City ID
				$sql_get_city_id2		= " SELECT `city_id` FROM `city` WHERE `state_id`='".$add_state2."' AND `city_name` = '".$emp_tempcity."' ";
				$res_get_city_id2		= mysqli_query($db_con, $sql_get_city_id2) or die(mysqli_error($db_con));
				$row_get_city_id2		= mysqli_fetch_array($res_get_city_id2);
				$num_get_city_id2		= mysqli_num_rows($res_get_city_id2);
				$add_city2				= $row_get_city_id2['city_id'];
				
				$existPriEmail 		= $recResult["emp_primary_email"];
				
				if($existPriEmail=="" && $num_get_desig != 0 && $num_get_orgid != 0 && $num_get_branch != 0 && $num_get_state_code1 != 0 && $num_get_city_id1 != 0 && $num_get_state_code2 != 0 && $num_get_city_id2 != 0)
				{
					$response_array 	= insertEmployee($emp_name, $org_id, $branch_id, $desg_id, $emp_offiemail, $emp_priemail, $emp_secemail, $emp_landline, $emp_primono, $emp_secmono, $emp_peraaddrs, $add_state1, $add_city1, $emp_perapincode, $emp_tempaddrs, $add_state2, $add_city2, $emp_temppincode);
					$insertion_flag		= 1;
				}
				else
				{
					// error data array
					$error_data = array("emp_name"=>$emp_name, "emp_desg"=>$emp_designation, "emp_primary_email"=>$emp_priemail, "emp_secondary_email"=>$emp_secemail, "emp_office_email"=>$emp_offiemail, "emp_primary_mobile"=>$emp_primono, "emp_secondary_mobile"=>$emp_secmono, "emp_landline"=>$emp_landline, "emp_orgid"=>$emp_org, "emp_branchid"=>$emp_branch, "emp_peraaddrs"=>$emp_peraaddrs, "emp_perastate"=>$emp_perastate, "emp_peracity"=>$emp_peracity, "emp_perapincode"=>$emp_perapincode, "emp_tempaddrs"=>$emp_tempaddrs, "emp_tempstate"=>$emp_tempstate, "emp_tempcity"=>$emp_tempcity, "emp_temppincode"=>$emp_temppincode);	
					
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
					
					$error_module_name	= "employee";
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
	$emp_name 			= strtolower(mysqli_real_escape_string($db_con,$obj->emp_name));
	$emp_orgid			= mysqli_real_escape_string($db_con,$obj->emp_orgid);
	$emp_branchid		= mysqli_real_escape_string($db_con,$obj->emp_branchid);
	$emp_desg			= mysqli_real_escape_string($db_con,$obj->emp_desg);
	$emp_office_email	= strtolower(mysqli_real_escape_string($db_con,$obj->emp_office_email));
	$emp_primary_email	= strtolower(mysqli_real_escape_string($db_con,$obj->emp_primary_email));
	$emp_secondary_email= strtolower(mysqli_real_escape_string($db_con,$obj->emp_secondary_email));
	$emp_landline		= mysqli_real_escape_string($db_con,$obj->emp_landline);
	$emp_primary_mobile = mysqli_real_escape_string($db_con,$obj->emp_primary_mobile);
	$emp_secondary_mobile= mysqli_real_escape_string($db_con,$obj->emp_secondary_mobile);
	$emp_status			= mysqli_real_escape_string($db_con,$obj->emp_status);
	$perm_details_add	= mysqli_real_escape_string($db_con,$obj->perm_details_add);
	$perm_city			= mysqli_real_escape_string($db_con,$obj->perm_city);
	$perm_state			= mysqli_real_escape_string($db_con,$obj->perm_state);
	$perm_pincode		= mysqli_real_escape_string($db_con,$obj->perm_pincode);
	$corrs_details_add	= mysqli_real_escape_string($db_con,$obj->corrs_details_add);
	$corrs_city			= mysqli_real_escape_string($db_con,$obj->corrs_city);
	$corrs_state		= mysqli_real_escape_string($db_con,$obj->corrs_state);
	$corrs_pincode		= mysqli_real_escape_string($db_con,$obj->corrs_pincode);
		
	$response_array = array();	
/*	$response_array = array("Success"=>"fail","resp"=>$emp_name."emp_name".$emp_orgid."emp_orgid".$emp_branchid."emp_branchid".$emp_desg."emp_desg".$emp_office_email."emp_office_email".$emp_primary_email."emp_primary_email".$emp_secondary_email."emp_secondary_email".$emp_landline."		emp_landline".$emp_primary_mobile."emp_primary_mobile".$emp_secondary_mobile."emp_secondary_mobile".$emp_status."emp_status".$perm_details_add."perm_details_add".$perm_city."perm_city".$perm_state."perm_state".$perm_pincode."perm_pincode".$corrs_details_add."corrs_details_add".$corrs_city			."corrs_city".$corrs_state."corrs_state".$corrs_pincode."corrs_pincode");	
	echo json_encode($response_array);	
	exit();	
*/	if($emp_name != "" && $emp_orgid != "" && $emp_branchid != "" && $emp_desg != "" && $emp_primary_mobile != "" && $emp_primary_email != "" && $emp_status != "")
	{
		$response_array 	= insertEmployee($emp_name,$emp_orgid,$emp_branchid,$emp_desg,$emp_office_email,$emp_primary_email,$emp_secondary_email,$emp_landline,$emp_primary_mobile,$emp_secondary_mobile,$emp_status,$perm_details_add,$perm_city,$perm_state,$perm_pincode,$corrs_details_add,$corrs_city,$corrs_state,$corrs_pincode);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->load_add_emp_part)) == "1" && isset($obj->load_add_emp_part))
{
	$emp_id 	= $obj->emp_id;
	$req_type 	= $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($emp_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$emp_id."' "; // this ind_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_emp_data		= json_decode($row_error_data['error_data']);
		}
		else if(($emp_id != "" && $req_type == "edit") || ($emp_id != "" && $req_type == "view"))
		{
			$sql_emp_data 		= "Select * from tbl_employee_master where emp_id = '".$emp_id."' ";
			$result_emp_data 	= mysqli_query($db_con,$sql_emp_data) or die(mysqli_error($db_con));
			$row_emp_data		= mysqli_fetch_array($result_emp_data);		
		}	
		if($req_type != "add" & $req_type != "error")
		{
			$sql_permant_address 	= "SELECT * FROM `tbl_address_master` WHERE `add_user_id` = '".$row_emp_data['emp_id']."'";
			$sql_permant_address 	.= " and `add_user_type` = 'employee' and  add_id = '".$row_emp_data['emp_perm_address']."' ";
			$result_permant_address	= mysqli_query($db_con,$sql_permant_address) or die(mysqli_error($db_con));
			$row_permant_address	= mysqli_fetch_array($result_permant_address);

			$sql_corrs_address 		= "SELECT * FROM `tbl_address_master` WHERE `add_user_id` = '".$row_emp_data['emp_id']."'";
			$sql_corrs_address 		.= " and `add_user_type` = 'employee' and  add_id = '".$row_emp_data['emp_corrs_address']."' ";
			$result_corrs_address	= mysqli_query($db_con,$sql_corrs_address) or die(mysqli_error($db_con));
			$row_corrs_address		= mysqli_fetch_array($result_corrs_address);
		}		
		$data = '';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="emp_id" value="'.$row_emp_data['emp_id'].'">';
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$emp_id.'">';
		}	 	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Employee Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="emp_name" name="emp_name" class="input-large keyup-char" data-rule-required="true" ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_emp_data['emp_name']).'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_emp_data->emp_name).'"';
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_emp_data['emp_name']).'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '<span class="warning-char">characters only.</span>';
		$data .= '</div>';
		$data .= '</div> <!-- Employee Name -->';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Organisation/Company<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select name="emp_orgid" id="emp_orgid" class="select2-me input-large" onChange="getBranch(this.value,\'emp_branchid\')" data-rule-required="true">';
			$data .= '<option value="">Select Organisation/Company</option>';
			$sql_get_org = "SELECT distinct org_id,org_name FROM `tbl_oraganisation_master` where org_status = '1' ";
			$result_get_org = mysqli_query($db_con,$sql_get_org) or die(mysqli_error($db_con));														
			while($row_get_org = mysqli_fetch_array($result_get_org))
			{	
				$data .= '<option value="'.$row_get_org['org_id'].'"';		
				if($req_type == "edit")												
				{
					if($row_get_org['org_id'] == $row_emp_data['emp_orgid'])
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
			$sql_get_org = "SELECT org_id,org_name FROM `tbl_oraganisation_master` where org_id = '".$row_emp_data['emp_orgid']."' ";
			$result_get_org = mysqli_query($db_con,$sql_get_org) or die(mysqli_error($db_con));														
			$row_get_org = mysqli_fetch_array($result_get_org);
			$data .= ucwords($row_get_org['org_name']);
		}
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Branch<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select name="emp_branchid" id="emp_branchid" class="select2-me input-large" data-rule-required="true">';
			$data .= '<option value="">Select Branch</option>';			
			$sql_get_branch 	= " SELECT branch_id,branch_name FROM `tbl_branch_master` where branch_status = '1' ";
			if($req_type != "error")
			{
				$sql_get_branch .= " and branch_orgid = '".$row_emp_data['emp_orgid']."' ";
			}
			$result_get_branch 	= mysqli_query($db_con,$sql_get_branch) or die(mysqli_error($db_con));														
			while($row_get_branch 	= mysqli_fetch_array($result_get_branch))
			{
				$data 			.= '<option value="'.$row_get_branch['branch_id'].'"';
				if($req_type == "edit")												
				{
					if($row_get_branch['branch_id'] == $row_emp_data['emp_branchid'])
					{
						$data .= 'selected';
					}
				}
				$data 			.= '>'.ucwords($row_get_branch['branch_name']).'</option>';
			}			
			$data 				.= '</select>';		
		}
		elseif($req_type == "view")		
		{
			$sql_get_branch 	= "SELECT branch_id,branch_name FROM `tbl_branch_master` where branch_status = '1' and branch_id = '".$row_emp_data['emp_branchid']."' ";
			$result_get_branch 	= mysqli_query($db_con,$sql_get_branch) or die(mysqli_error($db_con));														
			$row_get_branch 	= mysqli_fetch_array($result_get_branch);
			$data 				.= ucwords($row_get_branch['branch_name']);
		}				
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group span12">';
		$data .= '<label for="tasktitel" class="control-label">Designation <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data 				.= '<select name="emp_desg" id="emp_desg" placeholder="Type" class="select2-me input-large" data-rule-required="true">';
			$data 				.= '<option value="">Select Designation</option>';
			$sql_get_desg 		= "Select desg_id,desg_name  from tbl_designation_type where desg_status != 0";
			$result_get_desg 	= mysqli_query($db_con,$sql_get_desg) or die(mysqli_error($db_con));												
			while($row_get_desg = mysqli_fetch_array($result_get_desg))
			{
				$data 			.= '<option value="'.$row_get_desg['desg_id'].'"';		
				if($req_type == "edit")												
				{
					if($row_get_desg['desg_id'] == $row_emp_data['emp_desg'])
					{
						$data .= 'selected';
					}
				}
				$data 			.= '>'.ucwords($row_get_desg['desg_name']).'</option>';
			}			
			$data 				.= '</select>';		
		}
		elseif($req_type == "view")		
		{
			$sql_get_desg 		= "Select desg_id,desg_name  from tbl_designation_type where desg_id = '".$row_emp_data['emp_desg']."' ";
			$result_get_desg	= mysqli_query($db_con,$sql_get_desg) or die(mysqli_error($db_con));														
			$row_get_desg 		= mysqli_fetch_array($result_get_desg);
			$data 				.= ucwords($row_get_desg['desg_name']);
		}
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Primary Email<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="email" id="emp_primary_email" name="emp_primary_email" class="input-large" data-rule-required="true"  ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_emp_data['emp_primary_email'].'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_emp_data->emp_primary_email.'"'; 			
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_emp_data['emp_primary_email'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Mobile Number<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="emp_primary_mobile" name="emp_primary_mobile" class="input-large" data-rule-required="true" data-rule-minlength="10" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)" ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_emp_data['emp_primary_mobile'].'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_emp_data->emp_primary_mobile.'"'; 			
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_emp_data['emp_primary_mobile'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>'; 	
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Secondary Email <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="email" id="emp_secondary_email" name="emp_secondary_email" class="input-large" ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_emp_data['emp_secondary_email'].'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_emp_data->emp_secondary_email.'"'; 			
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_emp_data['emp_secondary_email'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>'; 
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Secondary Mobile Number<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="emp_secondary_mobile" name="emp_secondary_mobile" class="input-large" data-rule-minlength="10" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)"  ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_emp_data['emp_secondary_mobile'].'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_emp_data->emp_secondary_mobile.'"'; 			
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_emp_data['emp_secondary_mobile'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>'; 
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Office Email <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="email" id="emp_office_email" name="emp_office_email" class="input-large" ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_emp_data['emp_office_email'].'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_emp_data->emp_office_email.'"'; 			
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_emp_data['emp_office_email'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';		
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Landline<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="emp_landline" name="emp_landline" class="input-large" data-rule-minlength="11" onKeyPress="return isNumberKey(event)" ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_emp_data['emp_landline'].'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_emp_data->emp_landline.'"'; 			
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_emp_data['emp_landline'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';	
		
		$data .= '<div style="float:left">';
		if($req_type != "view")		
		{
			$data .= '<h3 style="margin:0px;">Address Details</h3>';
			$data .= '</div>';
			$data .= '<div>';
			$data .= '<input type="checkbox" id="address_check" name="address_check"  class="css-checkbox" value="CHK" ';
			if($req_type != "add" && $req_type != "error")
			{
				if($row_emp_data['emp_corrs_address'] == $row_emp_data['emp_perm_address'] && ($row_emp_data['emp_corrs_address'] != "0" && $row_emp_data['emp_perm_address'] != "0"))
				{
					$data .= ' checked';
				}
			}
			$data .= '>';
			$data .= '<label for="address_check" class="css-label" style="margin:12px;">Same As Permanent</label>';
			$data .= '</div>';
		}
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Permanent Address <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="perm_details_add" name="perm_details_add" >';
		if($emp_id != "" && $req_type == "error")
		{
			$data .= $row_permant_address->add_details;
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= $row_permant_address['add_details'];
		}		
		else
		{
			$data .= $row_permant_address['add_details'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Correspondence Address <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="corrs_details_add" name="corrs_details_add" >';
		if($emp_id != "" && $req_type == "error")
		{
			$data .= $row_corrs_address->add_details;
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= $row_corrs_address['add_details'];
		}		
		else
		{
			$data .= $row_corrs_address['add_details'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Permanent State <sup class="validfield"></sup></label>	';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select id="perm_state" name="perm_state" class="select2-me input-large" onChange="getCity(this.value,\'perm_city\')">';
			$data .= '<option value="">Select Permanent State</option>';
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			while($row_get_state = mysqli_fetch_array($res_get_state))
			{
				$data 			.= '<option value="'.$row_get_state['state'].'"';
				if($req_type == "edit")												
				{
					if($row_get_state['state'] == $row_permant_address['add_state'])
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
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' and state = '".$row_permant_address['add_state']."' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			$row_get_state = mysqli_fetch_array($res_get_state);
			$data 		  .= ucwords($row_get_state['state_name']);
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Correspondence State <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select id="corrs_state" name="corrs_state" class="select2-me input-large" onChange="getCity(this.value,\'corrs_city\')">';
			$data .= '<option value="">Select Correspondence State</option>';
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			while($row_get_state = mysqli_fetch_array($res_get_state))
			{
				$data 			.= '<option value="'.$row_get_state['state'].'"';
				if($req_type == "edit")												
				{
					if($row_get_state['state'] == $row_corrs_address['add_state'])
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
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' and state = '".$row_corrs_address['add_state']."' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			$row_get_state = mysqli_fetch_array($res_get_state);
			$data 		  .= ucwords($row_get_state['state_name']);
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Permanent City <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select id="perm_city" name="perm_city" class="select2-me input-large" >';
			$data .= '<option value="">Select Permanent City</option>';
			$sql_get_city = " SELECT `city_id`,`city_name` FROM `city` ";
			if($req_type != "error")
			{
				$sql_get_city .= " WHERE `state_id` = '".$row_permant_address['add_state']."' ";
			}
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			while($row_get_city = mysqli_fetch_array($res_get_city))
			{
				$data 			.= '<option value="'.$row_get_city['city_id'].'"';
				if($req_type == "edit")												
				{
					if($row_get_city['city_id'] == $row_permant_address['add_city'])
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
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` WHERE `city_id` = '".$row_permant_address['add_city']."' ";
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			$row_get_city = mysqli_fetch_array($res_get_city);
			$data 		  .= ucwords($row_get_city['city_name']);
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Correspondence City <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select id="corrs_city" name="corrs_city" class="select2-me input-large" >';
			$data .= '<option value="">Select Correspondence City</option>';
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` ";
			if($req_type != "error")
			{
				$sql_get_city .= " WHERE `state_id` = '".$row_corrs_address['add_state']."' ";
			}
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			while($row_get_city = mysqli_fetch_array($res_get_city))
			{
				$data 			.= '<option value="'.$row_get_city['city_id'].'"';
				if($req_type == "edit")												
				{
					if($row_get_city['city_id'] == $row_corrs_address['add_city'])
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
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` WHERE `city_id` = '".$row_corrs_address['add_city']."' ";
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			$row_get_city = mysqli_fetch_array($res_get_city);
			$data 		  .= ucwords($row_get_city['city_name']);
		}		
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Permanent Pin Code <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="perm_pincode" name="perm_pincode" placeholder="Permanent Pin Code" class="input-large" maxlength="6" onKeyPress="return isNumberKey(event)"  ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_permant_address['add_pincode'].'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_permant_address->add_pincode.'"'; 			
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_permant_address['add_pincode'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Correspondence Pin Code <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="corrs_pincode" name="corrs_pincode" placeholder="Correspondence Pin Code" class="input-large" maxlength="6" onKeyPress="return isNumberKey(event)"   ';
		if($emp_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_corrs_address['add_pincode'].'"'; 
		}
		elseif($emp_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_corrs_address->add_pincode.'"'; 			
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_corrs_address['add_pincode'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<div class="control-group span12">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($emp_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="emp_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_employee.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_emp_data->emp_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="emp_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_emp_data->emp_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($emp_id != "" && $req_type == "view")
		{
			if($row_emp_data['emp_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_emp_data['emp_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="emp_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_employee.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_emp_data['emp_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="emp_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_emp_data['emp_status'] == 0)
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
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Save Employee</button>';			
		}
		else if($req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Employee</button>';			
		}
		else if($req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update Employee</button>';			
		}		
		$data .= '</div> <!-- Save and cancel -->';			
		$data .= '<script type="text/javascript">';
		$data .= '$("#emp_orgid").select2();';
		$data .= '$("#emp_branchid").select2();';
		$data .= '$("#emp_desg").select2();';
		$data .= '$("#perm_state").select2();';
		$data .= '$("#perm_city").select2();';
		$data .= '$("#corrs_state").select2();';
		$data .= '$("#corrs_city").select2();';		
		$data .= 'CKEDITOR.replace("perm_details_add",{height:"150", width:"100%"});';
		$data .= 'CKEDITOR.replace("corrs_details_add",{height:"150", width:"100%"});';	
		if($emp_id != "" && $req_type == "view")
		{
			$data .= '$("#perm_details_add").prop("disabled","true");';
			$data .= '$("#corrs_details_add").prop("disabled","true");';								
		}	
		if($req_type != "add" & $req_type != "error")
		{
			if(($row_emp_data['emp_corrs_address'] == $row_emp_data['emp_perm_address']) && ($row_emp_data['emp_corrs_address'] != "0" && $row_emp_data['emp_perm_address'] != "0")) 
			{
				$data .= '$("#corrs_details_add").prop("disabled","true");';
				$data .= '$("#corrs_state").prop("disabled","true");';
				$data .= '$("#corrs_state").select2();';
				$data .= '$("#corrs_city").prop("disabled","true");';
				$data .= '$("#corrs_city").select2();';			
				$data .= '$("#corrs_pincode").prop("disabled","true");';						
			}
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
if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{
	$emp_id 			= mysqli_real_escape_string($db_con,$obj->emp_id);
	$emp_name 			= strtolower(mysqli_real_escape_string($db_con,$obj->emp_name));
	$emp_orgid			= mysqli_real_escape_string($db_con,$obj->emp_orgid);
	$emp_branchid		= mysqli_real_escape_string($db_con,$obj->emp_branchid);
	$emp_desg			= mysqli_real_escape_string($db_con,$obj->emp_desg);
	$emp_office_email	= strtolower(mysqli_real_escape_string($db_con,$obj->emp_office_email));
	$emp_primary_email	= strtolower(mysqli_real_escape_string($db_con,$obj->emp_primary_email));
	$emp_secondary_email= strtolower(mysqli_real_escape_string($db_con,$obj->emp_secondary_email));
	$emp_landline		= mysqli_real_escape_string($db_con,$obj->emp_landline);
	$emp_primary_mobile = mysqli_real_escape_string($db_con,$obj->emp_primary_mobile);
	$emp_secondary_mobile= mysqli_real_escape_string($db_con,$obj->emp_secondary_mobile);
	$emp_status			= mysqli_real_escape_string($db_con,$obj->emp_status);
	$perm_details_add	= mysqli_real_escape_string($db_con,$obj->perm_details_add);
	$perm_city			= mysqli_real_escape_string($db_con,$obj->perm_city);
	$perm_state			= mysqli_real_escape_string($db_con,$obj->perm_state);
	$perm_pincode		= mysqli_real_escape_string($db_con,$obj->perm_pincode);
	$corrs_details_add	= mysqli_real_escape_string($db_con,$obj->corrs_details_add);
	$corrs_city			= mysqli_real_escape_string($db_con,$obj->corrs_city);
	$corrs_state		= mysqli_real_escape_string($db_con,$obj->corrs_state);
	$corrs_pincode		= mysqli_real_escape_string($db_con,$obj->corrs_pincode);
		
	$response_array = array();	
	if($emp_name != "" && $emp_orgid != "" && $emp_branchid != "" && $emp_desg != "" && $emp_primary_mobile != "" && $emp_primary_email != "" && $emp_status != "")
	{
		$sql_check_employee 		= " SELECT * FROM tbl_employee_master WHERE emp_name LIKE '".$emp_name."' AND emp_id != '".$emp_id."' ";
		$result_check_employee 		= mysqli_query($db_con,$sql_check_employee) or die(mysqli_error($db_con));
		$num_rows_check_employee  	= mysqli_num_rows($result_check_employee);
		if($num_rows_check_employee == 0)
		{
			
			$sql_update_emp		 	= " UPDATE `tbl_employee_master` SET `emp_name`= '".$emp_name."',`emp_desg`='".$emp_desg."',`emp_primary_email`='".$emp_primary_email."',";
			$sql_update_emp 		.= " `emp_secondary_email`='".$emp_secondary_email."',`emp_office_email`='".$emp_office_email."',`emp_primary_mobile`='".$emp_primary_mobile."',";
			$sql_update_emp 		.= " `emp_secondary_mobile`='".$emp_secondary_mobile."',`emp_landline`='".$emp_landline."',`emp_orgid`='".$emp_orgid."',`emp_branchid`='".$emp_branchid."',";
			$sql_update_emp 		.= " `emp_status`='".$emp_status."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
			$result_update_emp 		= mysqli_query($db_con,$sql_update_emp) or die(mysqli_error($db_con));
			if($result_update_emp)
			{
				$sql_get_emp 		= "select * from `tbl_employee_master` where `emp_id` = '".$emp_id."' ";
				$result_get_emp 	= mysqli_query($db_con,$sql_get_emp) or die(mysqli_error($db_con));
				$row_get_emp 		= mysqli_fetch_array($result_get_emp);

				if($row_get_emp['emp_corrs_address'] == 0 && $row_get_emp['emp_perm_address'] == 0)
				{
					$add_user_id			= $emp_id;
					$add_user_type 			= "employee";
					$add_address_type		= "correspondence";					
					$add_status				= 1;		
					$add_lat_long			= "empty";	
					$emp_corrs_address_id 	= insertNewAddress($corrs_details_add,$corrs_state,$corrs_city,$corrs_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
					if($emp_corrs_address_id != "")				
					{
						$sql_emp_corrs_address 		= "UPDATE `tbl_employee_master` SET `emp_corrs_address`='".$emp_corrs_address_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
						$result_emp_corrs_address 	=  mysqli_query($db_con,$sql_emp_corrs_address) or die(mysqli_error($db_con));
						if($result_emp_corrs_address)
						{
							$response_array = array("Success"=>"Success","resp"=>"Address Updated");
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
					}
					$add_user_id			= $emp_id;
					$add_user_type 			= "employee";
					$add_address_type		= "permanent";					
					$add_status				= 1;
					$add_lat_long			= "empty";					
					$emp_perm_address_id 	= insertNewAddress($perm_details_add,$perm_state,$perm_city,$perm_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
					if($emp_perm_address_id != "")				
					{
						$sql_emp_perm_address 		= "UPDATE `tbl_employee_master` SET `emp_perm_address`='".$emp_perm_address_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
						$result_emp_perm_address 	=  mysqli_query($db_con,$sql_emp_perm_address) or die(mysqli_error($db_con));					
						if($result_emp_perm_address)
						{
							$response_array = array("Success"=>"Success","resp"=>"Address Updated");
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
					}																		
				}
				else
				{
					if($row_get_emp['emp_corrs_address'] != $row_get_emp['emp_perm_address'] && ($row_get_emp['emp_corrs_address'] != "0" && $row_get_emp['emp_perm_address'] != "0"))
					{
						if(($perm_details_add == $corrs_details_add) || ($perm_pincode == $corrs_pincode))
						{
							$emp_perm_add_id		= $row_get_emp['emp_perm_address'];	
							$emp_corrs_add_id		= $row_get_emp['emp_corrs_address'];								
							$add_user_id			= $emp_id;
							$add_user_type 			= "employee";
							$add_address_type		= "permanent/correspondence";
							$add_status				= 1;
							$add_lat_long			= "empty";								
							$emp_perm_address_id 	= updateOldAddress($emp_perm_add_id,$perm_details_add,$perm_state,$perm_city,$perm_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							if($emp_perm_address_id != "")				
							{
								$sql_del_corrs_address	= " DELETE FROM `tbl_address_master` WHERE `add_id` = '".$emp_corrs_add_id."' ";
								$result_del_corrs_address = mysqli_query($db_con,$sql_del_corrs_address) or die(mysqli_error($db_con));
								if($result_del_corrs_address)
								{
									$sql_emp_perm_address 		= " UPDATE `tbl_employee_master` SET `emp_perm_address`='".$emp_perm_add_id."',`emp_corrs_address`='".$emp_perm_add_id."',";
									$sql_emp_perm_address 		.= " `emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
									$result_emp_perm_address 	=  mysqli_query($db_con,$sql_emp_perm_address) or die(mysqli_error($db_con));					
									if($result_emp_perm_address)
									{
										$response_array = array("Success"=>"Success","resp"=>"Address Updated");
									}
									else
									{
										$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
									}									
								}
								else
								{
									$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");									
								}								
							}
							else
							{
								$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
							}							
						}
						elseif(($perm_details_add != $corrs_details_add) || ($perm_pincode != $corrs_pincode))
						{
							$emp_corrs_add_id		= $row_get_emp['emp_corrs_address'];
							$add_user_id			= $emp_id;
							$add_user_type 			= "employee";
							$add_address_type		= "correspondence";					
							$add_status				= 1;		
							$add_lat_long			= "empty";						
							$emp_corrs_address_id 	= updateOldAddress($emp_corrs_add_id,$corrs_details_add,$corrs_state,$corrs_city,$corrs_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							if($emp_corrs_address_id != "")				
							{
								$sql_emp_corrs_address = "UPDATE `tbl_employee_master` SET `emp_corrs_address`='".$emp_corrs_add_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
								$result_emp_corrs_address 	=  mysqli_query($db_con,$sql_emp_corrs_address) or die(mysqli_error($db_con));
								if($result_emp_corrs_address)
								{
									$response_array = array("Success"=>"Success","resp"=>"Address Updated");
								}
								else
								{
									$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
								}
							}
							else
							{
								$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
							}
							$emp_perm_add_id		= $row_get_emp['emp_perm_address'];	
							$add_user_id			= $emp_id;
							$add_user_type 			= "employee";
							$add_address_type		= "permanent";
							$add_status				= 1;
							$add_lat_long			= "empty";								
							$emp_perm_address_id 	= updateOldAddress($emp_perm_add_id,$perm_details_add,$perm_state,$perm_city,$perm_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							if($emp_perm_address_id != "")				
							{
								$sql_emp_perm_address 		= "UPDATE `tbl_employee_master` SET `emp_perm_address`='".$emp_perm_add_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
								$result_emp_perm_address 	=  mysqli_query($db_con,$sql_emp_perm_address) or die(mysqli_error($db_con));					
								if($result_emp_perm_address)
								{
									$response_array = array("Success"=>"Success","resp"=>"Address Updated");
								}
								else
								{
									$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
								}
							}
							else
							{
								$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
							}								
						}												
					}
					elseif($row_get_emp['emp_corrs_address'] == $row_get_emp['emp_perm_address'] && ($row_get_emp['emp_corrs_address'] != "0" && $row_get_emp['emp_perm_address'] != "0"))
					{
						if(($perm_details_add == $corrs_details_add) || ($perm_pincode == $corrs_pincode))						
						{
							$emp_perm_add_id		= $row_get_emp['emp_perm_address'];	
							$emp_corrs_add_id		= $row_get_emp['emp_corrs_address'];									
							$add_user_id			= $emp_id;
							$add_user_type 			= "employee";
							$add_address_type		= "permanent/correspondence";
							$add_status				= 1;
							$add_lat_long			= "empty";								
							$emp_perm_address_id 	= updateOldAddress($emp_perm_add_id,$perm_details_add,$perm_state,$perm_city,$perm_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							if($emp_address_id != "")				
							{
								$sql_del_corrs_address	= " DELETE FROM `tbl_address_master` WHERE `add_id` = '".$emp_corrs_add_id."' ";
								$result_del_corrs_address = mysqli_query($db_con,$sql_del_corrs_address) or die(mysqli_error($db_con));
								if($result_del_corrs_address)
								{								
									$sql_emp_corrs_perm_address 	= "UPDATE `tbl_employee_master` SET `emp_corrs_address`='".$emp_perm_add_id."',`emp_perm_address`='".$emp_perm_add_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
									$result_emp_corrs_perm_address 	=  mysqli_query($db_con,$sql_emp_perm_address) or die(mysqli_error($db_con));					
									if($result_emp_corrs_perm_address)
									{
										$response_array = array("Success"=>"Success","resp"=>"Address Updated");
									}
									else
									{
										$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
									}
								}
								else
								{
									$response_array = array("Success"=>"Fail","resp"=>"Address not Deleted");
								}
							}
							else
							{
								$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
							}													
						}
						elseif(($perm_details_add != $corrs_details_add) || ($perm_pincode != $corrs_pincode))
						{
							$emp_perm_add_id		= $row_get_emp['emp_perm_address'];		
							$add_user_id			= $emp_id;
							$add_user_type 			= "employee";
							$add_address_type		= "permanent";
							$add_status				= 1;
							$add_lat_long			= "empty";								
							$emp_perm_address_id 	= updateOldAddress($emp_perm_add_id,$perm_details_add,$perm_state,$perm_city,$perm_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							if($emp_address_id != "")				
							{
								$sql_emp_corrs_perm_address 	= "UPDATE `tbl_employee_master` SET `emp_perm_address`='".$emp_perm_address_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
								$result_emp_corrs_perm_address 	=  mysqli_query($db_con,$sql_emp_perm_address) or die(mysqli_error($db_con));					
								if($result_emp_corrs_perm_address)
								{
									$response_array = array("Success"=>"Success","resp"=>"Address Updated");
								}
								else
								{
									$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
								}
							}
							else
							{
								$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
							}	
							$add_user_id		= $emp_id;
							$add_user_type 		= "employee";
							$add_address_type	= "correspondence";
							$add_status			= 1;		
							$add_lat_long		= "empty";			
							$emp_corrs_address_id = insertNewAddress($corrs_details_add,$corrs_state,$corrs_city,$corrs_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							if($emp_corrs_address_id != "")				
							{
								$sql_emp_corrs_address 		= "UPDATE `tbl_employee_master` SET `emp_corrs_address`='".$emp_corrs_address_id."',`emp_modified_by`='".$uid."',`emp_modified`='".$datetime."' WHERE `emp_id` = '".$emp_id."' ";
								$result_emp_corrs_address 	=  mysqli_query($db_con,$sql_emp_corrs_address) or die(mysqli_error($db_con));
								if($result_emp_corrs_address)
								{
									$response_array = array("Success"=>"Success","resp"=>"Address Updated");
								}
								else
								{
									$response_array = array("Success"=>"Fail","resp"=>"Address not Updated");
								}
							}
							else
							{
								$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted");
							}												
						}
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
			$response_array = array("Success"=>"fail","resp"=>"Employee <b>".ucwords($employee_name)."</b> already Exists.");	
		}		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->load_emp)) == "1" && isset($obj->load_emp))
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
		
		$sql_load_data  = " SELECT `emp_id`, `emp_name`, `emp_status`, `emp_created`, `emp_modified`,";		
		$sql_load_data  .= " (SELECT `branch_name` FROM `tbl_branch_master` WHERE `branch_id` = emp_branchid) as emp_branch_name, ";		
		$sql_load_data  .= " (SELECT `org_name` FROM `tbl_oraganisation_master` WHERE `org_id` = emp_orgid) as emp_org_name, ";				
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tbm.emp_created_by) as emp_by_created, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tbm.emp_modified_by) as emp_by_modified ";
		$sql_load_data  .= " FROM `tbl_employee_master` tbm WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND emp_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (emp_name like '%".$search_text."%'or emp_created like '%".$search_text."%' or emp_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY emp_id ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$emp_data = "";	
			$emp_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$emp_data .= '<thead>';
    	  	$emp_data .= '<tr>';
         	$emp_data .= '<th style="text-align:center">Sr No.</th>';
			$emp_data .= '<th style="text-align:center">Emp Id</th>';
			$emp_data .= '<th style="text-align:center">Emp Name</th>';
			$emp_data .= '<th style="width:6%;text-align:center">Employee</th>';
			$emp_data .= '<th style="width:6%;text-align:center">Branch</th>';			
			$emp_data .= '<th style="text-align:center">Created</th>';
			$emp_data .= '<th style="text-align:center">Created By</th>';
			$emp_data .= '<th style="text-align:center">Modified</th>';
			$emp_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_employee.php",3);
			if($dis)
			{			
				$emp_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_employee.php",1);
			if($edit)
			{			
				$emp_data .= '<th style="text-align:center">Edit</th>';			
			}
			$del = checkFunctionalityRight("view_employee.php",2);
			if($del)
			{			
				$emp_data .= '<th style="text-align:center">';
				$emp_data .= '<div style="text-align:center">';
				$emp_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/>';
				$emp_data .= '</div></th>';
			}
          	$emp_data .= '</tr>';
      		$emp_data .= '</thead>';
      		$emp_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$emp_data .= '<tr>';				
				$emp_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$emp_data .= '<td style="text-align:center">'.$row_load_data['emp_id'].'</td>';
				$emp_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['emp_name']).'" class="btn-link" id="'.$row_load_data['emp_id'].'" onclick="addMoreEmployee(this.id,\'view\');"></td>';								
				$emp_data .= '<td style="text-align:center">'.ucwords($row_load_data['emp_org_name']).'</td>';
				$emp_data .= '<td style="text-align:center">'.ucwords($row_load_data['emp_branch_name']).'</td>';
				$emp_data .= '<td style="text-align:center">'.$row_load_data['emp_created'].'</td>';								
				$emp_data .= '<td style="text-align:center">'.ucwords($row_load_data['emp_by_created']).'</td>';
				$emp_data .= '<td style="text-align:center">'.$row_load_data['emp_modified'].'</td>';
				$emp_data .= '<td style="text-align:center">'.ucwords($row_load_data['emp_by_modified']).'</td>';
				$dis = checkFunctionalityRight("view_employee.php",3);
				if($dis)
				{				
					$emp_data .= '<td style="text-align:center">';	
					if($row_load_data['emp_status'] == 1)
					{
						$emp_data .= '<input type="button" value="Active" id="'.$row_load_data['emp_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$emp_data .= '<input type="button" value="Inactive" id="'.$row_load_data['emp_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$emp_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_employee.php",1);
				if($edit)
				{			
					$emp_data .= '<td style="text-align:center">';
					$emp_data .= '<input type="button" value="Edit" id="'.$row_load_data['emp_id'].'" class="btn-warning" onclick="addMoreEmployee(this.id,\'edit\');"></td>';
				}
				$del = checkFunctionalityRight("view_employee.php",2);
				if($del)
				{	
					$emp_data .= '<td><div class="controls" align="center">';
					$emp_data .= '<input type="checkbox" value="'.$row_load_data['emp_id'].'" id="batch'.$row_load_data['emp_id'].'" name="batch'.$row_load_data['emp_id'].'" class="css-checkbox batch">';
					$emp_data .= '<label for="batch'.$row_load_data['emp_id'].'" class="css-label"></label>';
					$emp_data .= '</div></td>';										
				}
	          	$emp_data .= '</tr>';															
			}	
      		$emp_data .= '</tbody>';
      		$emp_data .= '</table>';	
			$emp_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$emp_data);					
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
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$emp_id					= $obj->emp_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();
	$sql_update_status 		= " UPDATE `tbl_employee_master` SET `emp_status`= '".$curr_status."' ,`emp_modified` = '".$datetime."' ,`emp_modified_by` = '".$uid."' WHERE `emp_id` like '".$emp_id."' ";
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
if((isset($obj->delete_emp)) == "1" && isset($obj->delete_emp))
{
	$response_array = array();		
	$ar_emp_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_emp_id as $emp_id)	
	{	
		$sql_del_addrs		= " DELETE FROM `tbl_address_master` WHERE `add_user_id`='".$emp_id."' AND `add_user_type`='employee' ";			
		$res_del_addrs		= mysqli_query($db_con, $sql_del_addrs) or die(mysqli_error($db_con));
		
		$sql_delete_emp		= " DELETE FROM `tbl_employee_master` WHERE `emp_id` = '".$emp_id."' ";
		$result_delete_emp	= mysqli_query($db_con,$sql_delete_emp) or die(mysqli_error($db_con));			
		if($result_delete_emp && $res_del_addrs)
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
if((isset($obj->get_addrs)) == "1" && isset($obj->get_addrs))
{
	$response_array = array();
	$data 			= "";
	$addrs_hid		= $obj->addrs_hid;
	$rbtn_is_same	= $obj->rbtn_is_same;
	if(strcmp($rbtn_is_same,"1")===0)
	{
		$data = addAddrsDiv($db_con,$addrs_hid,'permanent_correspondence');
	}
	elseif(strcmp($rbtn_is_same,"2")===0)
	{
		$data1 = addAddrsDiv($db_con,$addrs_hid,'permanent');
		$addrs_hid		 = $addrs_hid + 1;
		$data2 = addAddrsDiv($db_con,$addrs_hid,'correspondence');
		$data = $data1.$data2;
	}
	
	$response_array = array("Success"=>"Success","resp"=>$data);	
	echo json_encode($response_array);
}
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
		$sql_load_data  .= " WHERE error_module_name='employee' ";
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
			$emp_data = "";	
			$emp_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$emp_data .= '<thead>';
    	  	$emp_data .= '<tr>';
         	$emp_data .= '<th>Sr. No.</th>';
			$emp_data .= '<th>Employee Name</th>';
			$emp_data .= '<th>Employee</th>';
			$emp_data .= '<th>Branch</th>';
			$emp_data .= '<th>Created</th>';
			$emp_data .= '<th>Created By</th>';
			$emp_data .= '<th>Modified</th>';
			$emp_data .= '<th>Modified By</th>';
			$emp_data .= '<th>Edit</th>';			
			$emp_data .= '<th>
							<div style="text-align:center">
								<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>
							</div>
						</th>';
          	$emp_data .= '</tr>';
      		$emp_data .= '</thead>';
      		$emp_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_emp_rec	= json_decode($row_load_data['error_data']);
				
				$er_emp_name	= $get_emp_rec->emp_name;
				$er_emp_org		= $get_emp_rec->emp_orgid;
				$er_emp_branch	= $get_emp_rec->emp_branchid;
				
				$emp_data .= '<tr>';				
				$emp_data .= '<td>'.++$start_offset.'</td>';				
				$emp_data .= '<td>';
					$sql_chk_name_already_exist	= " SELECT `ind_name` FROM `tbl_industry` WHERE `ind_name`='".$er_emp_name."' ";
					$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
					$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);
					
					if(strcmp($num_chk_name_already_exist,"0")===0)
					{
						$emp_data .= $er_emp_name;
					}
					else
					{
						$emp_data .= '<span style="color:#E63A3A;">'.$er_emp_name.' [Already Exist]</span>';
					}
				$emp_data .= '</td>';
				$emp_data .= '<td>'.$er_emp_org.'</td>';
				$emp_data .= '<td>'.$er_emp_branch.'</td>';
				$emp_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$emp_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
				$emp_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$emp_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$emp_data .= '<td style="text-align:center">';
				$emp_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreEmployee(this.id,\'error\');"></td>';				
				$emp_data .= '<td><div class="controls" align="center">';
				$emp_data .= '<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$emp_data .= '<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$emp_data .= '</div></td>';										
	          	$emp_data .= '</tr>';															
			}	
      		$emp_data .= '</tbody>';
      		$emp_data .= '</table>';	
			$emp_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$emp_data);					
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
if((isset($obj->delete_emp_error)) == "1" && isset($obj->delete_emp_error))
{
	$ar_emp_id 		= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_emp_id as $emp_id)	
	{
		$sql_del_addrs			= " DELETE FROM `tbl_address_master` WHERE `add_user_id`='".$emp_id."' AND `add_user_type`='employee' ";			
		$res_del_addrs			= mysqli_query($db_con, $sql_del_addrs) or die(mysqli_error($db_con));
		
		$sql_delete_emp_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$emp_id."' ";
		$result_delete_cat_error= mysqli_query($db_con,$sql_delete_emp_error) or die(mysqli_error($db_con));			
		
		if($result_delete_cat_error && $res_del_addrs)
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