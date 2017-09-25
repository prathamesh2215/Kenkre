<?php
	include("include/routines.php");
	$json 	= file_get_contents('php://input');
	$obj 	= json_decode($json);
	$utype	= $_SESSION['panel_user']['utype'];
	$uid	= $_SESSION['panel_user']['id'];
    $tbl_users_owner = $_SESSION['panel_user']['tbl_users_owner'];
	function newAddressId()
	{
		global $uid;
		global $db_con;
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
		global $uid;
		global $db_con, $datetime;
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

	function insertOrganisation($response_array, $org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $org_ship_addrs, $ship_state, $ship_city, $ship_pincode, $org_desc, $org_status,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name)
	{
		global $uid;
		global $db_con, $datetime;
		global $obj;
		
		//$sql_chk_org	= "SELECT * FROM tbl_oraganisation_master WHERE org_name='".$org_name."' ";
		$sql_chk_org	= "SELECT * FROM tbl_oraganisation_master WHERE org_name='".$org_name."' OR org_primary_email='".$org_primary_email."'";//done by monika
		$res_chk_org	= mysqli_query($db_con, $sql_chk_org) or die(mysqli_error($db_con));
		$num_chk_org	= mysqli_num_rows($res_chk_org);
		$row 			= mysqli_fetch_assoc($res_chk_org);//done by monika
		if(strcmp($num_chk_org,"0")===0)
		{
			$sql_last_org	= "SELECT * FROM tbl_oraganisation_master ORDER BY org_id DESC LIMIT 0,1";
			$res_last_org	= mysqli_query($db_con, $sql_last_org) or die(mysqli_error($db_con));
			$row_last_org	= mysqli_fetch_array($res_last_org);
			$num_last_org	= mysqli_num_rows($res_last_org);
			if(strcmp($num_last_org,"0")===0)
			{
				$org_id	= 1;	
			}
			else
			{
				$org_id	= $row_last_org['org_id'] + 1;
			}
			
			$sql_insert_org	= "INSERT INTO `tbl_oraganisation_master`(`org_id`, `org_name`, `org_primary_email`, `org_secondary_email`, `org_tertiary_email`, `org_primary_phone`, `org_secondary_phone`, `org_fax`, `org_website`, `org_indid`, `org_cst`, `org_vat`, `org_description`, `org_status`, `org_created`, `org_created_by`,`org_bank_ifsc_code`,`org_bank_account_number`,`org_bank_address`,`org_bank_name`,`org_beneficiary_name`) 
							   VALUES ('".$org_id."', '".$org_name."', '".$org_primary_email."', '".$org_secondary_email."', '".$org_tertiary_email."', '".$org_primary_phone."', '".$org_secondary_phone."', '".$org_fax."', '".$org_website."', '".$org_indid."', '".$org_cst."', '".$org_vat."', '".$org_desc."', '".$org_status."', '".$datetime."', '".$uid."','".$org_bank_ifsc_code."','".$org_bank_account_number."','".$org_bank_address."','".$org_bank_name."','".$org_beneficiary_name."')";
			$res_insert_org = mysqli_query($db_con, $sql_insert_org) or die(mysqli_error($db_con));
			
			if($res_insert_org)
			{
				$sql_users_owner_insert_org	= "INSERT INTO `tbl_users_owner`(`orgid`, `clientname`, `shortcode`, `promocode`, `created_by`, `created`, `status`) 
											   VALUES ('".$org_id."', '".$org_name."', '".strtolower($org_name)."',  '".strtolower($org_name)."', '".$uid."', '".$datetime."', '".$org_status."')";
				$res_users_owner_insert_org	= mysqli_query($db_con, $sql_users_owner_insert_org) or die(mysqli_error($db_con));	
				
				if($res_users_owner_insert_org)
				{
					if(isset($obj->error_id) && (isset($obj->insert_req_1)) != "")			
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
					
					if($org_bill_addrs != "" || $bill_city != "" || $bill_state != "" || $bill_pincode != "" || $org_ship_addrs !="" || $ship_city != "" || $ship_state != "" || $ship_pincode != "")
					{
						if($org_bill_addrs != $org_ship_addrs && $bill_pincode != $ship_pincode)
						{
							$add_user_id		= $org_id;
							$add_user_type 		= "organisation";
							$add_address_type	= "billing";
							$add_status			= 1;		
							$add_lat_long		= "empty";
							
							$org_bill_address_id = insertNewAddress($org_bill_addrs,$bill_state,$bill_city,$bill_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);			
							//$org_ship_address_id = insertNewAddress($org_ship_addrs,$ship_state,$ship_city,$ship_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							
							if($org_bill_address_id != "")				
							{
								$sql_org_bill_address = "UPDATE `tbl_oraganisation_master` SET `org_billing_address`='".$org_bill_address_id."',`org_modified_by`='".$uid."',`org_modified`='".$datetime."' WHERE `org_id` = '".$org_id."' ";
								$result_org_bill_address 	=  mysqli_query($db_con,$sql_org_bill_address) or die(mysqli_error($db_con));
								if($result_org_bill_address)
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
							
							$add_user_id		= $org_id;
							$add_user_type 		= "organisation";
							$add_address_type	= "shipping";
							$add_status			= 1;
							$add_lat_long		= "empty";					
							//$org_bill_address_id = insertNewAddress($org_bill_addrs,$bill_state,$bill_city,$bill_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							$org_ship_address_id = insertNewAddress($org_ship_addrs,$ship_state,$ship_city,$ship_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							if($org_ship_address_id != "")				
							{
								$sql_org_ship_address 	= "UPDATE `tbl_oraganisation_master` SET `org_shipping_address`='".$org_ship_address_id."',`org_modified_by`='".$uid."',`org_modified`='".$datetime."' WHERE `org_id` = '".$org_id."' ";
								$result_org_ship_address 	=  mysqli_query($db_con,$sql_org_ship_address) or die(mysqli_error($db_con));					
								if($result_org_ship_address)
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
						elseif($org_bill_addrs == $org_ship_addrs && $bill_pincode == $ship_pincode)
						{
							$add_user_id		= $org_id;
							$add_user_type 		= "organisation";
							$add_address_type	= "billing_shipping";
							$add_status			= 1;
							$add_lat_long		= "empty";						
							$org_address_id = insertNewAddress($org_bill_addrs,$bill_state,$bill_city,$bill_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
							
							if($org_address_id != "")				
							{
								$sql_org_ship_bill_address 	= "UPDATE `tbl_oraganisation_master` SET `org_shipping_address`='".$org_address_id."',`org_billing_address`='".$org_address_id."',`org_modified_by`='".$uid."',`org_modified`='".$datetime."' WHERE `org_id` = '".$org_id."' ";
								
								$result_org_ship_bill_address 	=  mysqli_query($db_con,$sql_org_ship_bill_address) or die(mysqli_error($db_con));					
								if($result_org_ship_bill_address)
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
					$response_array = array("Success"=>"fail","resp"=>"users_owner_insert fail");		
				}
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Org_insert fail");	
			}
		}
		/*done by monika start*/
		elseif($org_name == $row['org_name'] )
		{
			$response_array = array("Success"=>"fail","resp"=>"Organisation Name ".$org_name." is already Exist");
		}
		elseif($org_primary_email == $row['org_primary_email'] )
		{
			$response_array = array("Success"=>"fail","resp"=>"Email ".$org_primary_email." is already Exist");
		}
		/*done by monika end*/
		return $response_array;
	}
	
	function updateAddrs($org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $add_address_type, $add_id, $org_id, $last_condition)
	{
		global $uid;
		global $db_con, $datetime;
		//echo json_encode($add_id);exit();
		$sql_update_addrs = " UPDATE `tbl_address_master` 
								SET `add_details`='".$org_bill_addrs."',
									`add_state`='".$bill_state."',
									`add_city`='".$bill_city."',
									`add_pincode`='".$bill_pincode."',
									`add_address_type`='".$add_address_type."',
									`add_modified`='".$datetime."',
									`add_modified_by`='".$uid."'
							  WHERE `add_id`='".$add_id."'
								AND `add_user_id`='".$org_id."'
								AND `add_user_type`='organisation' ";
		
		if(strcmp($last_condition, "")!==0)
		{
			$sql_update_addrs .= " AND `add_address_type`='".$add_address_type."' ";
		}
		$res_update_addrs = mysqli_query($db_con, $sql_update_addrs) or die(mysqli_error($db_con));	
		
		if($res_update_addrs)
		{
			return $add_id;
		}
		else
		{
			return $add_id = "";
		}
	}

	function updateMainOrg($org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_desc, $org_id,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name)
	{//echo json_encode($org_beneficiary_name);exit();
		global $uid;
		global $db_con, $datetime;	
		//echo json_encode($org_beneficiary_name);exit();
		$sql_main_update_org = " UPDATE `tbl_oraganisation_master` 
									SET `org_name`='".$org_name."',
										`org_primary_email`='".$org_primary_email."',	
										`org_secondary_email`='".$org_secondary_email."',
										`org_tertiary_email`='".$org_tertiary_email."',
										`org_primary_phone`='".$org_primary_phone."',
										`org_secondary_phone`='".$org_secondary_phone."',
										`org_fax`='".$org_fax."',
										`org_website`='".$org_website."',
										`org_indid`='".$org_indid."',
										`org_cst`='".$org_cst."',
										`org_vat`='".$org_vat."',
										`org_description`='".$org_desc."',
										`org_modified`='".$datetime."',
										`org_modified_by`='".$uid."',
										`org_bank_ifsc_code`= '".$org_bank_ifsc_code."',
										`org_bank_account_number`= '".$org_bank_account_number."',
										`org_bank_address`= '".$org_bank_address."',
										`org_bank_name`= '".$org_bank_name."',
										`org_beneficiary_name`= '".$org_beneficiary_name."'																									
								  WHERE `org_id`='".$org_id."' ";
		$res_main_update_org = mysqli_query($db_con, $sql_main_update_org) or die(mysqli_error($db_con));
		if($res_main_update_org)
		{
			return $org_id;
		}
		else
		{
			return $org_id = "";
		}
	}

	function updateOrganisation($response_array, $org_id, $org_name, $org_desc, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $org_ship_addrs, $ship_state, $ship_city, $ship_pincode, $org_status,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name)
	{
		global $uid;
		global $db_con, $datetime;
		
		$change_flag 			= 0;
		$flag_branch 			= 1;
		$flag_addrs	 			= 1;
		$flag_userowner			= 1;
		$flag_employee			= 1;
		$flag_prod				= 1;
		$update_flag			= 0;
		
		//$sql_chk_org_update	= " SELECT * FROM tbl_oraganisation_master WHERE org_name ='".$org_name."' AND org_id!='".$org_id."' ";
		$sql_chk_org_update	= " SELECT * FROM tbl_oraganisation_master WHERE org_id!='".$org_id."' AND (org_name ='".$org_name."' OR org_primary_email='".$org_primary_email."') ";//done by monika
		$res_chk_org_update = mysqli_query($db_con, $sql_chk_org_update) or die(mysqli_error($db_con));
		$num_chk_org_update	= mysqli_num_rows($res_chk_org_update);
		$row 				= mysqli_fetch_assoc($res_chk_org_update);//done by monika

		if(strcmp($num_chk_org_update,"0")===0)
		{
			$sql_get_self_data	= " SELECT * FROM tbl_oraganisation_master WHERE org_id='".$org_id."' ";
			$res_get_self_data 	= mysqli_query($db_con, $sql_get_self_data) or die(mysqli_error($db_con));
			$row_get_self_data 	= mysqli_fetch_array($res_get_self_data);
			if(strcmp($row_get_self_data['org_status'], $org_status)!==0)
			{
				// For Branchs
				$sql_get_branchs 	= " SELECT * FROM `tbl_branch_master` WHERE `branch_orgid`='".$org_id."' ";
				$res_get_branchs	= mysqli_query($db_con, $sql_get_branchs) or die(mysqli_error($db_con));
				$num_get_bramchs	= mysqli_num_rows($res_get_branchs);
				if(strcmp($num_get_bramchs,"0")!==0)
				{
					while($row_get_branchs = mysqli_fetch_array($res_get_branchs))
					{
						$sql_update_branch_status	= "UPDATE `tbl_branch_master` 
															SET `branch_status`='".$org_status."',
																`branch_modified`='".$datetime."',
																`branch_modified_by`='".$uid."' 
														WHERE `branch_orgid`='".$org_id."'";
						$res_update_branch_status	= mysqli_query($db_con, $sql_update_branch_status) or die(mysqli_error($db_con));
						if($res_update_branch_status)
						{
							$flag_branch	= 1;	
						}
						else
						{
							$flag_branch	= 0;	
						}
					}
				}
				
				if($flag_branch	== 1)
				{
					// For Address
					$sql_get_addrs		= " SELECT * FROM `tbl_address_master` WHERE `add_user_id`='".$org_id."' AND `add_user_type`='organisation' ";
					$res_get_addrs		= mysqli_query($db_con, $sql_get_addrs) or die(mysqli_error($db_con));
					$num_get_addrs		= mysqli_num_rows($res_get_addrs);
					if(strcmp($num_get_addrs,"0")!==0)
					{
						while($row_get_addrs = mysqli_fetch_array($res_get_addrs))
						{
							$sql_update_addrs_status	= "UPDATE `tbl_address_master` 
																SET `add_status`='".$org_status."',
																	`add_modified`='".$datetime."',
																	`add_modified_by`='".$uid."'
															WHERE `add_user_id`='".$org_id."'
																AND `add_user_type`='organisation'";
							$res_update_addrs_status	= mysqli_query($db_con, $sql_update_addrs_status) or die(mysqli_error($db_con));
							
							if($res_update_addrs_status)
							{
								$flag_addrs	= 1;	
							}
							else
							{
								$flag_addrs	= 0;
							}
						}	
					}	
				}
				
				if($flag_addrs == 1)
				{
					// For User Owners
					$sql_get_userowner	= " SELECT * FROM `tbl_users_owner` WHERE `orgid`='".$org_id."' ";
					$res_get_userowner	= mysqli_query($db_con, $sql_get_userowner) or die(mysqli_error($db_con));
					$num_get_userowner	= mysqli_num_rows($res_get_userowner);
					if(strcmp($num_get_userowner,"0")!==0)
					{
						while($row_get_userowner = mysqli_fetch_array($res_get_userowner))
						{
							$sql_update_user_owner	= "UPDATE `tbl_users_owner` 
															SET `modified_by`='".$uid."',
																`modified`='".$datetime."',
																`status`='".$org_status."'
														WHERE `orgid`='".$org_id."'";
							$res_update_user_owner 	= mysqli_query($db_con, $sql_update_user_owner) or die(mysqli_error($db_con));
							
							if($res_update_user_owner)
							{
								$flag_userowner	= 1;
							}
							else
							{
								$flag_userowner	= 0;
							}
						}
					}	
				}
				
				if($flag_userowner	== 1)
				{
					// For Employee
					$sql_get_employee	= " SELECT * FROM `tbl_employee_master` WHERE `emp_orgid`='".$org_id."' ";
					$res_get_employee	= mysqli_query($db_con, $sql_get_employee) or die(mysqli_error($db_con));
					$num_get_employee 	= mysqli_num_rows($res_get_employee);
					if(strcmp($num_get_employee,"0")!==0)
					{
						while($row_get_employee = mysqli_fetch_array($res_get_employee))
						{
							$sql_update_employee	= "UPDATE `tbl_employee_master` 
															SET `emp_status`='".$org_status."',
																`emp_modified_by`='".$uid."',
																`emp_modified`='".$datetime."'
														WHERE `emp_orgid`='".$org_id."'";
							$res_update_employee	= mysqli_query($db_con, $sql_update_employee) or die(mysqli_error($db_con));
							
							if($res_update_employee)
							{
								$flag_employee	= 1;	
							}
							else
							{
								$flag_employee	= 0;
							}	
						}
					}		
				}
				
				if($flag_employee == 1)
				{
					// For products
					$sql_get_prod		= " SELECT * FROM `tbl_products_master` WHERE `prod_orgid`='".$org_id."' ";
					$res_get_prod		= mysqli_query($db_con, $sql_get_prod) or die(mysqli_error($db_con));
					$num_get_prod		= mysqli_num_rows($res_get_prod);
					if(strcmp($num_get_prod,"0")!==0)
					{
						while($row_get_prod = mysqli_fetch_array($res_get_prod))	
						{
							$sql_update_prod 	= "UPDATE `tbl_products_master` 
														SET `prod_modified_by`='".$uid."',
															`prod_modified`='".$datetime."',
															`prod_status`='".$org_status."' 
													WHERE `prod_orgid`='".$org_id."'";
							$res_update_prod	= mysqli_query($db_con, $sql_update_prod) or die(mysqli_error($db_con));
							
							if($res_update_prod)
							{
								$flag_prod	= 1;	
							}
							else
							{
								$flag_prod	= 0;
							}
						}
					}	
				}
		
				if($flag_prod == 1)
				{
					// Finally for Organization
					$sql_update_status 		= " UPDATE `tbl_oraganisation_master` 
													SET `org_status`='".$org_status."',
														`org_modified`='".$datetime."',
														`org_modified_by`='".$uid."' 
												WHERE `org_id`='".$org_id."' ";
					$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
					if($result_update_status)
					{
						$response_array = array("Success"=>"Success","resp"=>"Organisation Updated");
					}
					else
					{
						$response_array = array("Success"=>"Success","resp"=>"Organisation Not Updated");
					}	
				}	
			}
			
			// Get Count of rows from addrs tbl for that particular org_id
			$sql_get_records_of_addrs_tbl = " SELECT COUNT(*) AS record_count 
												FROM `tbl_address_master` 
												WHERE `add_user_id`='".$org_id."' AND `add_user_type`='organisation' ";
			$res_get_records_of_addrs_tbl = mysqli_query($db_con, $sql_get_records_of_addrs_tbl) or die(mysqli_error($db_con));
			$row_get_records_of_addrs_tbl = mysqli_fetch_array($res_get_records_of_addrs_tbl);

			if($org_bill_addrs != "" || $bill_city != "" || $bill_state != "" || $bill_pincode != "" || $org_ship_addrs !="" || $ship_city != "" || $ship_state != "" || $ship_pincode != "")
			{
				if(strcmp($row_get_records_of_addrs_tbl['record_count'],'1')===0 && strcmp($org_bill_addrs, $org_ship_addrs)===0)
				{
					$add_address_type 	= 'billing_shipping';
					$add_id				= $row_get_self_data['org_billing_address'];
					$last_condition		= '';
					
					$func_update_addrs	= updateAddrs($org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $add_address_type, $add_id, $org_id, $last_condition);
					
					if($func_update_addrs != '')
					{
						$update_flag	= 1;
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Updated [At 1st Condition]");	
					}
					/*$sql_update_addrs = " UPDATE `tbl_address_master` 
											SET `add_details`='".$org_bill_addrs."',
												`add_state`='".$bill_state."',
												`add_city`='".$bill_city."',
												`add_pincode`='".$bill_pincode."',
												`add_modified`='".$datetime."',
												`add_modified_by`='".$uid."'
										  WHERE `add_id`='".$row_get_self_data['org_billing_address']."'
											AND `add_user_id`='".$org_id."'
											AND `add_user_type`='organisation'
											AND `add_address_type`='billing_shipping' ";
					$res_update_addrs = mysqli_query($db_con, $sql_update_addrs) or die(mysqli_error($db_con));
					
					if($res_update_addrs)
					{
						$update_flag	= 1;	
					}*/
				}
				elseif(strcmp($row_get_records_of_addrs_tbl['record_count'],'1')===0 && strcmp($org_bill_addrs, $org_ship_addrs)!==0)
				{
					$add_address_type 	= 'billing';
					$add_id				= $row_get_self_data['org_billing_address'];
					$last_condition		= '';
					
					$func_update_addrs	= updateAddrs($org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $add_address_type, $add_id, $org_id, $last_condition);
					
					/*$sql_update_bill_addrs	= " UPDATE `tbl_address_master` 
													SET `add_details`='".$org_bill_addrs."',
														`add_state`='".$bill_state."',
														`add_city`='".$bill_city."',
														`add_pincode`='".$bill_pincode."',
														`add_address_type`='billing',
														`add_modified`='".$datetime."',
														`add_modified_by`='".$uid."'
												WHERE `add_id`='".$row_get_self_data['org_billing_address']."'
													AND `add_user_id`='".$org_id."'
													AND `add_user_type`='organisation' ";
					$res_update_bill_addrs	= mysqli_query($db_con, $sql_update_bill_addrs) or die(mysqli_error($db_con));*/
					
					if($func_update_addrs != '')
					{
						$add_user_id		= $org_id;
						$add_lat_long		= "empty";
						$add_user_type		= "organisation";
						$add_address_type	= "shipping";
						$add_status			= 1;
						
						$insert_new_addrs_id	= insertNewAddress($org_ship_addrs,$ship_state,$ship_city,$ship_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status); 
						
						// new record inserted for shipping address in the addrs tbl
						/*$sql_get_last_record	= "SELECT * FROM tbl_address_master ORDER BY add_id DESC LIMIT 0,1";
						$res_get_last_record 	= mysqli_query($db_con,$sql_get_last_record) or die(mysqli_error($db_con));
						$row_get_last_record 	= mysqli_fetch_array($res_get_last_record);
						$num_get_last_record 	= mysqli_num_rows($res_get_last_record);
						if(strcmp($num_get_last_record,"0")===0)
						{
							$add_id	= 1;	
						}
						else
						{
							$add_id	= $row_get_last_record['add_id'] + 1;	
						}
						
						$sql_insert_addrs2	= "INSERT INTO `tbl_address_master`(`add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`,  `add_user_id`, `add_user_type`, `add_address_type`, `add_status`, `add_created`, `add_created_by`) 
											   VALUES ('".$add_id."', '".$org_ship_addrs."', '".$ship_state."', '".$ship_city."', '".$ship_pincode."', '".$org_id."', 'organisation', 'shipping', '".$row_get_self_data['org_status']."', '".$datetime."', '".$uid."')";
						$res_insert_addrs2 	= mysqli_query($db_con,$sql_insert_addrs2) or die(mysqli_error($db_con));*/
						
						if($insert_new_addrs_id != '')
						{
							$sql_main_update_org = " UPDATE `tbl_oraganisation_master` 
														SET `org_name`='".$org_name."',
															`org_primary_email`='".$org_primary_email."',	
															`org_secondary_email`='".$org_secondary_email."',
															`org_tertiary_email`='".$org_tertiary_email."',
															`org_primary_phone`='".$org_primary_phone."',
															`org_secondary_phone`='".$org_secondary_phone."',
															`org_fax`='".$org_fax."',
															`org_website`='".$org_website."',
															`org_indid`='".$org_indid."',
															`org_cst`='".$org_cst."',
															`org_vat`='".$org_vat."',
															`org_shipping_address`='".$insert_new_addrs_id."',
															`org_description`='".$org_desc."',
															`org_modified`='".$datetime."',
															`org_modified_by`='".$uid."',
															`org_bank_ifsc_code`= '".$org_bank_ifsc_code."',
															`org_bank_account_number`= '".$org_bank_account_number."',
															`org_bank_address`= '".$org_bank_address."',
															`org_bank_name`= '".$org_bank_name."',
															`org_beneficiary_name`= '".$org_beneficiary_name."'
													  WHERE `org_id`='".$org_id."' ";
							$res_main_update_org = mysqli_query($db_con, $sql_main_update_org) or die(mysqli_error($db_con));
							
							if($res_main_update_org)
							{
								$response_array = array("Success"=>"Success","resp"=>"Organisation Updated");
							}
							else
							{
								$response_array = array("Success"=>"Success","resp"=>"Organisation Not Updated");	
							}
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Address not Inserted [At 2nd Condition]");	
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Updated [At 2nd Condition]");	
					}
				}
				elseif(strcmp($row_get_records_of_addrs_tbl['record_count'],'2')===0 && strcmp($org_bill_addrs, $org_ship_addrs)===0)
				{
					$add_address_type 	= 'billing_shipping';
					$add_id				= $row_get_self_data['org_billing_address'];
					$last_condition		= '';
					
					$func_update_addrs	= updateAddrs($org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $add_address_type, $add_id, $org_id, $last_condition);
					
					/*$sql_update_bill_addrs	= " UPDATE `tbl_address_master` 
													SET `add_details`='".$org_bill_addrs."',
														`add_state`='".$bill_state."',
														`add_city`='".$bill_city."',
														`add_pincode`='".$bill_pincode."',
														`add_address_type`='billing_shipping',
														`add_modified`='".$datetime."',
														`add_modified_by`='".$uid."'
												WHERE `add_id`='".$row_get_self_data['org_billing_address']."'
													AND `add_user_id`='".$org_id."'
													AND `add_user_type`='organisation' ";
					$res_update_bill_addrs 	= mysqli_query($db_con, $sql_update_bill_addrs) or die(mysqli_error($db_con));*/
					
					if($func_update_addrs != '')
					{
						// Record Deleted for shipping address from the addrs tbl\
						$sql_delete_ship_addrs	= " DELETE FROM `tbl_address_master` WHERE `add_id`='".$row_get_self_data['org_shipping_address']."' ";
						$res_delete_ship_addrs 	= mysqli_query($db_con, $sql_delete_ship_addrs) or die(mysqli_error($db_con));
						
						if($res_delete_ship_addrs)
						{//echo json_encode($org_name);exit();
							$sql_main_update_org = " UPDATE `tbl_oraganisation_master` 
														SET `org_name`='".$org_name."',
															`org_primary_email`='".$org_primary_email."',	
															`org_secondary_email`='".$org_secondary_email."',
															`org_tertiary_email`='".$org_tertiary_email."',
															`org_primary_phone`='".$org_primary_phone."',
															`org_secondary_phone`='".$org_secondary_phone."',
															`org_fax`='".$org_fax."',
															`org_website`='".$org_website."',
															`org_indid`='".$org_indid."',
															`org_cst`='".$org_cst."',
															`org_vat`='".$org_vat."',
															`org_shipping_address`='".$row_get_self_data['org_billing_address']."',
															`org_description`='".$org_desc."',
															`org_modified`='".$datetime."',
															`org_modified_by`='".$uid."',
															`org_bank_ifsc_code`= '".$org_bank_ifsc_code."',
															`org_bank_account_number`= '".$org_bank_account_number."',
															`org_bank_address`= '".$org_bank_address."',
															`org_bank_name`= '".$org_bank_name."',
															`org_beneficiary_name`= '".$org_beneficiary_name."'															
													  WHERE `org_id`='".$org_id."' ";
							$res_main_update_org = mysqli_query($db_con, $sql_main_update_org) or die(mysqli_error($db_con));
							
							if($res_main_update_org)
							{
								$response_array = array("Success"=>"Success","resp"=>"Organisation Updated");
							}
							else
							{
								$response_array = array("Success"=>"Success","resp"=>"Organisation Not Updated");	
							}
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Address not Deleted [At 3rd Condition]");	
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Updated [At 3rd Condition]");	
					}
					
					
				}
				elseif(strcmp($row_get_records_of_addrs_tbl['record_count'],'2')===0 && strcmp($org_bill_addrs, $org_ship_addrs)!==0)
				{
					$add_address_type 	= 'billing';
					$add_id				= $row_get_self_data['org_billing_address'];
					$last_condition		= $add_address_type;
					
					$func_update_addrs	= updateAddrs($org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $add_address_type, $add_id, $org_id, $last_condition);
					
					/*$sql_update_addrs1 = " UPDATE `tbl_address_master` 
											SET `add_details`='".$org_bill_addrs."',
												`add_state`='".$bill_state."',
												`add_city`='".$bill_city."',
												`add_pincode`='".$bill_pincode."',
												`add_modified`='".$datetime."',
												`add_modified_by`='".$uid."'
										  WHERE `add_id`='".$row_get_self_data['org_billing_address']."'
											AND `add_user_id`='".$org_id."'
											AND `add_user_type`='organisation'
											AND `add_address_type`='billing' ";
					$res_update_addrs1 = mysqli_query($db_con, $sql_update_addrs1) or die(mysqli_error($db_con));*/
					
					if($func_update_addrs != '')
					{
						$add_address_type1 	= 'shipping';
						$add_id1			= $row_get_self_data['org_shipping_address'];
						$last_condition1	= $add_address_type1;
						
						$func_update_addrs1	= updateAddrs($org_ship_addrs, $ship_state, $ship_city, $ship_pincode, $add_address_type1, $add_id1, $org_id, $last_condition1);
						
						/*$sql_update_addrs2 = " UPDATE `tbl_address_master` 
												SET `add_details`='".$org_ship_addrs."',
													`add_state`='".$ship_state."',
													`add_city`='".$ship_city."',
													`add_pincode`='".$ship_pincode."',
													`add_modified`='".$datetime."',
													`add_modified_by`='".$uid."'
											  WHERE `add_id`='".$row_get_self_data['org_shipping_address']."'
												AND `add_user_id`='".$org_id."'
												AND `add_user_type`='organisation'
												AND `add_address_type`='shipping' ";
						$res_update_addrs2 = mysqli_query($db_con, $sql_update_addrs2) or die(mysqli_error($db_con));*/
						
						if($func_update_addrs1 != '')
						{
							$update_flag	= 1;
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Shipping Address not Updated [At 4th Condition]");
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Billing Address not Updated [At 4th Condition]");	
					}
				}
				elseif(strcmp($row_get_records_of_addrs_tbl['record_count'],'0')===0)
				{
					if($org_bill_addrs != $org_ship_addrs && $bill_pincode != $ship_pincode)
					{//echo json_encode($org_bill_addrs);exit();
						$add_user_id		= $org_id;
						$add_user_type 		= "organisation";
						$add_address_type	= "billing";
						$add_status			= 1;		
						$add_lat_long		= "empty";
						
						$org_bill_address_id = insertNewAddress($org_bill_addrs,$bill_state,$bill_city,$bill_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);			
						//$org_ship_address_id = insertNewAddress($org_ship_addrs,$ship_state,$ship_city,$ship_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
						
						if($org_bill_address_id != "")				
						{
							$sql_org_bill_address 		= "UPDATE `tbl_oraganisation_master` SET `org_billing_address`='".$org_bill_address_id."',`org_modified_by`='".$uid."',`org_modified`='".$datetime."' WHERE `org_id` = '".$org_id."' ";
							$result_org_bill_address 	=  mysqli_query($db_con,$sql_org_bill_address) or die(mysqli_error($db_con));
							if($result_org_bill_address)
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
						
						$add_user_id		= $org_id;
						$add_user_type 		= "organisation";
						$add_address_type	= "shipping";
						$add_status			= 1;
						$add_lat_long		= "empty";					
						//$org_bill_address_id = insertNewAddress($org_bill_addrs,$bill_state,$bill_city,$bill_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
						$org_ship_address_id = insertNewAddress($org_ship_addrs,$ship_state,$ship_city,$ship_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
						if($org_ship_address_id != "")				
						{
							$sql_org_ship_address 	= "UPDATE `tbl_oraganisation_master` SET `org_shipping_address`='".$org_ship_address_id."',`org_modified_by`='".$uid."',`org_modified`='".$datetime."' WHERE `org_id` = '".$org_id."' ";
							$result_org_ship_address 	=  mysqli_query($db_con,$sql_org_ship_address) or die(mysqli_error($db_con));					
							if($result_org_ship_address)
							{
								$response_array = array("Success"=>"Success","resp"=>"Address Updated");
								//done by monika
								$update_flag = 1;
								if($update_flag == 1)
								{
									$func_main_update_org	= updateMainOrg($org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_desc, $org_id,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name);
									if($func_main_update_org != '')
									{
										$response_array = array("Success"=>"Success","resp"=>"Organisation Updated");
									}
									else
									{
										$response_array = array("Success"=>"Success","resp"=>"Organisation Not Updated");
									}
								}
								//done by monika
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
					elseif($org_bill_addrs == $org_ship_addrs && $bill_pincode == $ship_pincode)
					{
						$add_user_id		= $org_id;
						$add_user_type 		= "organisation";
						$add_address_type	= "billing_shipping";
						$add_status			= 1;
						$add_lat_long		= "empty";			
						$org_address_id = insertNewAddress($org_bill_addrs,$bill_state,$bill_city,$bill_pincode,$add_lat_long,$add_user_id,$add_user_type,$add_address_type,$add_status);
						
						if($org_address_id != "")				
						{		//echo json_encode($org_address_id);exit();	
							$sql_org_ship_bill_address 	= "UPDATE `tbl_oraganisation_master` SET `org_shipping_address`='".$org_address_id."',`org_billing_address`='".$org_address_id."',`org_modified_by`='".$uid."',`org_modified`='".$datetime."' WHERE `org_id` = '".$org_id."' ";
							$result_org_ship_bill_address 	=  mysqli_query($db_con,$sql_org_ship_bill_address) or die(mysqli_error($db_con));					
							if($result_org_ship_bill_address)
							{
								$response_array = array("Success"=>"Success","resp"=>"Address Updated");
								//done by monika
								$update_flag = 1;
								if($update_flag == 1)
								{
									$func_main_update_org	= updateMainOrg($org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_desc, $org_id,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name);
									if($func_main_update_org != '')
									{
										$response_array = array("Success"=>"Success","resp"=>"Organisation Updated");
									}
									else
									{
										$response_array = array("Success"=>"Success","resp"=>"Organisation Not Updated");
									}
								}
								//done by monika
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
					$update_flag = 1;
				}
				
				if($update_flag == 1)
				{
					$func_main_update_org	= updateMainOrg($org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_desc, $org_id,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name);
					
					if($func_main_update_org != '')
					{
						$response_array = array("Success"=>"Success","resp"=>"Organisation Updated");
					}
					else
					{
						$response_array = array("Success"=>"Success","resp"=>"Organisation Not Updated");
					}
				}
			}
			else
			{	//echo json_encode($org_name);exit();	
			//done by monika	
				$func_main_update_org	= updateMainOrg($org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_desc, $org_id,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name);
					
					if($func_main_update_org != '')
					{
						$response_array = array("Success"=>"Success","resp"=>"Organisation Updated");
					}
					else
					{
						$response_array = array("Success"=>"Success","resp"=>"Organisation Not Updated");
					}
					//done by monika
					
						
				if(strcmp($row_get_records_of_addrs_tbl['record_count'],'1')===0 && strcmp($org_bill_addrs, "")===0)
				{
					$add_address_type 	= 'billing_shipping';
					$add_id				= $row_get_self_data['org_billing_address'];
					$last_condition		= '';
					
					$func_update_addrs	= updateAddrs($org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $add_address_type, $add_id, $org_id, $last_condition);
					
					if($func_update_addrs != '')
					{
						$update_flag	= 1;
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Address not Updated [At 1st Condition]");	
					}	
				}
				elseif(strcmp($row_get_records_of_addrs_tbl['record_count'],'2')===0 && strcmp($org_bill_addrs, "")===0 && strcmp($org_ship_addrs, "")===0)
				{
					$add_address_type 	= 'billing';
					$add_id				= $row_get_self_data['org_billing_address'];
					$last_condition		= $add_address_type;
					
					$func_update_addrs	= updateAddrs($org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $add_address_type, $add_id, $org_id, $last_condition);
					
					/*$sql_update_addrs1 = " UPDATE `tbl_address_master` 
											SET `add_details`='".$org_bill_addrs."',
												`add_state`='".$bill_state."',
												`add_city`='".$bill_city."',
												`add_pincode`='".$bill_pincode."',
												`add_modified`='".$datetime."',
												`add_modified_by`='".$uid."'
										  WHERE `add_id`='".$row_get_self_data['org_billing_address']."'
											AND `add_user_id`='".$org_id."'
											AND `add_user_type`='organisation'
											AND `add_address_type`='billing' ";
					$res_update_addrs1 = mysqli_query($db_con, $sql_update_addrs1) or die(mysqli_error($db_con));*/
					
					if($func_update_addrs != '')
					{
						$add_address_type1 	= 'shipping';
						$add_id1			= $row_get_self_data['org_shipping_address'];
						$last_condition1	= $add_address_type1;
						
						$func_update_addrs1	= updateAddrs($org_ship_addrs, $ship_state, $ship_city, $ship_pincode, $add_address_type1, $add_id1, $org_id, $last_condition1);
						
						/*$sql_update_addrs2 = " UPDATE `tbl_address_master` 
												SET `add_details`='".$org_ship_addrs."',
													`add_state`='".$ship_state."',
													`add_city`='".$ship_city."',
													`add_pincode`='".$ship_pincode."',
													`add_modified`='".$datetime."',
													`add_modified_by`='".$uid."'
											  WHERE `add_id`='".$row_get_self_data['org_shipping_address']."'
												AND `add_user_id`='".$org_id."'
												AND `add_user_type`='organisation'
												AND `add_address_type`='shipping' ";
						$res_update_addrs2 = mysqli_query($db_con, $sql_update_addrs2) or die(mysqli_error($db_con));*/
						
						if($func_update_addrs1 != '')
						{
							$update_flag	= 1;
						}
						else
						{
							$response_array = array("Success"=>"Fail","resp"=>"Shipping Address not Updated [At 4th Condition]");
						}
					}
					else
					{
						$response_array = array("Success"=>"Fail","resp"=>"Billing Address not Updated [At 4th Condition]");	
					}	
				}
				
				if($update_flag == 1)
				{
					//echo json_encode($org_beneficiary_name);exit();
					//$func_main_update_org	= updateMainOrg($org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_desc, $org_id, $org_beneficiary_name);
					$func_main_update_org	= updateMainOrg($org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_desc, $org_id,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name);//done by monika
				
					if($func_main_update_org != '')
					{
						$response_array = array("Success"=>"Success","resp"=>"Organisation Updated");
					}
					else
					{
						$response_array = array("Success"=>"Success","resp"=>"Organisation Not Updated");
					}	
				}
			}
		}
		/*done by monika start*/
		elseif($org_name == $row['org_name'] )
		{
			$response_array = array("Success"=>"fail","resp"=>"Orgnisation ".$org_name." already Exists.");	
		}
		elseif($org_primary_email == $row['org_primary_email'] )
		{
			$response_array = array("Success"=>"fail","resp"=>"Email ".$org_primary_email." already Exists.");	
		}
		/*done by monika end*/
		return $response_array;	
	}

	if(isset($_FILES['file']))
	{
		$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
		$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
		move_uploaded_file($sourcePath,$inputFileName) ;
		
		set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
		include 'PHPExcel/IOFactory.php';
		$org_id 	= 0;
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
				$org_name 			= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"])),ENT_HTML5);
				$org_primary_email	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["B"]));
				$org_secondary_email= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["C"]));
				$org_tertiary_email	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["D"]));
				$org_primary_phone	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"]));
				$org_secondary_phone= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"]));
				$org_fax			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["G"]));
				$org_website		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["H"]));
				$org_industry		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["I"]));
				$org_cst			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["J"]));
				$org_vat			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["K"]));
				$org_billaddrs1		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["L"]));
				$org_billaddrs      = htmlspecialchars(str_replace("'","&#146;",$org_billaddrs1),ENT_HTML5);
				$billstate_name		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["M"]));					
				$billcity_name		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["N"]));
				$org_billpincode	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["O"]));
				$org_shipaddrs1		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["P"]));
				$org_shipaddrs      = htmlspecialchars(str_replace("'","&#146;",$org_shipaddrs1),ENT_HTML5);
				$shipstate_name		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["Q"]));				
				$shipcity_name		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["R"]));
				$org_shippincode	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["S"]));
				$org_description1	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["T"]));
				$org_description    = htmlspecialchars(str_replace("'","&#146;",$org_description1),ENT_HTML5);
				$org_status			= '1';
				
				if($org_name!='' && $org_primary_email!='' && $org_industry!='')
				{
					$query = " SELECT * FROM `tbl_oraganisation_master` 
								WHERE `org_name`='".$org_name."'
									AND `org_primary_email`='".$org_primary_email."'
									AND `org_primary_phone`='".$org_primary_phone."' " ;
									
					$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
					$recResult 	= mysqli_fetch_array($sql);
					
					// Get Industry ID
					$sql_get_ind_id		= " SELECT * FROM `tbl_industry` WHERE `ind_name`='".trim($org_industry)."' ";
					$res_get_ind_id		= mysqli_query($db_con, $sql_get_ind_id) or die(mysqli_error($db_con));
					$row_get_ind_id		= mysqli_fetch_array($res_get_ind_id);
					$num_get_ind_id		= mysqli_num_rows($res_get_ind_id);
					$org_indid			= $row_get_ind_id['ind_id'];
					
					// getting state and city
					// get State Code
					$sql_get_state_code1	= " SELECT `state_id`,`state` FROM `state` WHERE `state_name` = '".$billstate_name."' ";
					$res_get_state_code1	= mysqli_query($db_con, $sql_get_state_code1) or die(mysqli_error($db_con));
					$row_get_state_code1	= mysqli_fetch_array($res_get_state_code1);
					$add_state1				= $row_get_state_code1['state'];
					
					// get City ID
					$sql_get_city_id1		= " SELECT `city_id` FROM `city` WHERE `state_id`='".$add_state1."' AND `city_name` = '".$billcity_name."' ";
					$res_get_city_id1		= mysqli_query($db_con, $sql_get_city_id1) or die(mysqli_error($db_con));
					$row_get_city_id1		= mysqli_fetch_array($res_get_city_id1);
					$add_city1				= $row_get_city_id1['city_id'];
					
					// get State Code
					$sql_get_state_code2	= " SELECT `state_id`,`state` FROM `state` WHERE `state_name` = '".$shipstate_name."' ";
					$res_get_state_code2	= mysqli_query($db_con, $sql_get_state_code2) or die(mysqli_error($db_con));
					$row_get_state_code2	= mysqli_fetch_array($res_get_state_code2);
					$add_state2				= $row_get_state_code2['state'];
					
					// get City ID
					$sql_get_city_id2		= " SELECT `city_id` FROM `city` WHERE `state_id`='".$add_state2."' AND `city_name` = '".$shipcity_name."' ";
					$res_get_city_id2		= mysqli_query($db_con, $sql_get_city_id2) or die(mysqli_error($db_con));
					$row_get_city_id2		= mysqli_fetch_array($res_get_city_id2);
					$add_city2				= $row_get_city_id2['city_id'];
					
					$existPriEmail 		= $recResult["org_primary_email"];
					$existOrgName		= $recResult["org_name"];
					
					if($existPriEmail=="" && $existOrgName=="" && $num_get_ind_id!=0 && $org_primary_phone!='')
					{
						$response_array 	= insertOrganisation($response_array, $org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_billaddrs, $add_state1, $add_city1, $org_billpincode, $org_shipaddrs, $add_state2, $add_city2, $org_shippincode, $org_description, $org_status);
						
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
						$error_data = array("org_name"=>$org_name, "org_primary_email"=>$org_primary_email, "org_secondary_email"=>$org_secondary_email, "org_tertiary_email"=>$org_tertiary_email, "org_primary_phone"=>$org_primary_phone, "org_secondary_phone"=>$org_secondary_phone, "org_fax"=>$org_fax, "org_website"=>$org_website, "org_indid"=>$org_industry, "org_cst"=>$org_cst, "org_vat"=>$org_vat, "org_billaddrs"=>$org_billaddrs, "billstate_name"=>$billstate_name, "billcity_name"=>$billcity_name, "org_billpincode"=>$org_billpincode, "org_shipaddrs"=>$org_shipaddrs, "shipstate_name"=>$shipstate_name, "shipcity_name"=>$shipcity_name, "org_shippincode"=>$org_shippincode, "org_description"=>$org_description, "org_status"=>"0");	
						
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
						
						$error_module_name	= "organisation";
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
	
	if((isset($obj->load_org)) != "" && isset($obj->load_org))
	{
		$start_offset   = 0;
	
		$page 			= $obj->page;	
		$per_page		= $obj->row_limit;
		$search_text	= $obj->search_text;
		
		$response_array = array();
		
		if($page != "" && $per_page != "")
		{
			$cur_page 		= $page;
			$page 	   	   	= $page - 1;
			$start_offset += $page * $per_page;
			$start 			= $page * $per_page;
			
			$sql_load_data	= " SELECT `org_id`, `org_name`, `org_primary_email`, `org_secondary_email`, `org_tertiary_email`, "; 
			$sql_load_data	.= " `org_primary_phone`, `org_secondary_phone`, `org_fax`, `org_website`, `org_indid`, `org_cst`, ";
			$sql_load_data	.= " (SELECT ind_name FROM tbl_industry WHERE ind_id = org_indid) AS name_org_industry, "; 
			$sql_load_data	.= " `org_vat`, `org_billing_address`, `org_shipping_address`, `org_description`, `org_status`, `org_created`, ";
			$sql_load_data	.= " `org_created_by`, (SELECT fullname FROM tbl_cadmin_users WHERE id=org_created_by) AS name_created_by, ";
			$sql_load_data	.= " (SELECT fullname FROM tbl_cadmin_users WHERE id = org_modified_by) AS name_modified_by, "; 
			$sql_load_data	.= " `org_modified`, `org_modified_by` ,(SELECT count(`prod_id`) FROM `tbl_products_master` WHERE `prod_orgid` = org_id) as prod_count"; 
			$sql_load_data	.= " FROM `tbl_oraganisation_master` ";
			$sql_load_data	.= " WHERE 1=1 ";
			if($utype!=1)
			{
				//////////--------START : Done By satish 10022017---------////////////////////
			    $sql_get_user_owner = " SELECT orgid FROM tbl_users_owner WHERE id='".$tbl_users_owner."'";
				$res_get_user_owner = mysqli_query($db_con,$sql_get_user_owner) or die(mysqli_error($db_con));
				$owner_row          = mysqli_fetch_array($res_get_user_owner);	
				$tbl_users_owner    = $owner_row['orgid'];
				 //////////--------END : Done By satish 10022017---------////////////////////
				 
				$sql_load_data  .= " AND ( org_created_by='".$uid."' || org_id='".$tbl_users_owner."' ) ";
				
				
			}
			
			if($search_text != "")
			{
				$sql_load_data .= " AND (org_name like '%".$search_text."%' or org_description like '%".$search_text."%' ";
				$sql_load_data .= " or org_primary_email like '%".$search_text."%' or org_secondary_email like '%".$search_text."%' ";
				$sql_load_data .= " or org_tertiary_email like '%".$search_text."%' or org_primary_phone like '%".$search_text."%' ";	
				$sql_load_data .= " or org_secondary_phone like '%".$search_text."%' or org_website like '%".$search_text."%' ";	
				$sql_load_data .= " or org_created like '%".$search_text."%' or org_modified like '%".$search_text."%') ";	
			}
			
			$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
			$sql_load_data .=" ORDER BY org_id DESC LIMIT $start, $per_page ";
			$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
			
			if(strcmp($data_count,"0") !== 0)
			{
				$org_data = '';
				$org_data .= '<table id="tbl_org" class="table table-bordered dataTable" style="width:100%;text-align:center">';
				$org_data .= '<thead>';
				$org_data .= '<tr>';
				$org_data .= '<th style="text-align:center;vertical-align:middle;">Sr No.</th>';
				$org_data .= '<th style="text-align:center;vertical-align:middle;">Org. ID</th>';
				$org_data .= '<th style="text-align:center;vertical-align:middle;">Org. Name</th>';
				$org_data .= '<th style="text-align:center;vertical-align:middle;">Product Discount</th>';				
				$org_data .= '<th style="text-align:center;vertical-align:middle;">Product Payment Option <br>&<br> Product Return</th>';
				$org_data .= '<th style="text-align:center;vertical-align:middle;">Level and Filter<br>Assignment</th>';				
				$dis = checkFunctionalityRight("view_organisation.php",3);
				if($dis)
				{
					$org_data .= '<th style="text-align:center">Status</th>';
				}
				$edit = checkFunctionalityRight("view_organisation.php",1);
				if($edit)
				{
					$org_data .= '<th style="text-align:center">Edit</th>';
				}
				$del = checkFunctionalityRight("view_organisation.php",2);
				if($del)
				{
					$org_data .= '<th style="text-align:center">';
					$org_data .= '<div style="text-align:center">';
					$org_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
				}
				$org_data .= '</tr>';
				$org_data .= '</thead>';
				$org_data .= '<tbody>';
				while($row_load_data = mysqli_fetch_array($result_load_data))
				{
					$org_data .= '<tr>';
					$org_data .= '<td style="text-align:center;vertical-align:middle;">'.++$start_offset.'</td>';
					$org_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['org_id'].'</td>';
					$org_data .= '<td><div><button class="btn-link txtoverflow" id="'.$row_load_data['org_id'].'" onclick="addMoreOrganisation(this.id,\'view\');">'.ucwords($row_load_data['org_name']).'</button><br>';
					$org_data .= '<i class="icon-chevron-down" id="'.$row_load_data['org_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['org_id'].'org_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i></div>';
					$org_data .= '<div id="'.$row_load_data['org_id'].'org_div" style="display:none;">';
					if(trim($row_load_data['org_created']) == "")
					{
						$org_data .= '<div><b>Created:</b><span style="color:#f00;">Not Available<span></div>';						
					}
					else
					{
						$org_data .= '<div><b>Created:</b>'.$row_load_data['org_created'].'</div>';						
					}
					if(trim($row_load_data['name_created_by']) == "")
					{
						$org_data .= '<div><b>Created By:</b><span style="color:#f00;">Not Available<span></div>';						
					}
					else
					{
						$org_data .= '<div><b>Created By:</b>'.$row_load_data['name_created_by'].'</div>';						
					}
					if(trim($row_load_data['org_modified']) == "")
					{
						$org_data .= '<div><b>Modified:</b><span style="color:#f00;">Not Available<span></div>';						
					}
					else
					{
						$org_data .= '<div><b>Modified</b>'.$row_load_data['org_modified'].'</div>';						
					}					
					if(trim($row_load_data['name_modified_by']) == "")
					{
						$org_data .= '<div><b>Modified By:</b><span style="color:#f00;">Not Available<span></div>';						
					}
					else
					{
						$org_data .= '<div><b>Modified By:</b>'.$row_load_data['name_modified_by'].'</div>';
					}										
					$org_data .= '</div>';
					$org_data .= '</td>';
					$org_data .= '<td style="text-align:center;vertical-align:middle;">';
					$org_data .= '<div>';
					$org_data .= '<span><input type="radio" name="'.$row_load_data['org_id'].'discount" value="flat">Flat Discount</span>';
					$org_data .= '<span><input type="radio" name="'.$row_load_data['org_id'].'discount" value="percent">Percent(%) Discount</span>';
					$org_data .= '</div><br>';					
					$org_data .= '<div class="center-text">';
					$org_data .= '<input type="text" name="'.$row_load_data['org_id'].'discount_value" id="'.$row_load_data['org_id'].'discount_value">';					
					$org_data .= '</div>';															
					$org_data .= '<div class="center-text">';
					$org_data .= '<input type="button" onClick="productDiscount(this.id,1);" class="btn-success" id="'.$row_load_data['org_id'].'org_dis_btn" value="Apply to '.ucwords($row_load_data['org_name']).'" style="">';
					$org_data .= '</div>';
					$org_data .= '</td>';
					$org_data .= '<td>';					
					$org_data .= '<div>';
					$org_data .= '<Select class="select2-me input-large" id="'.$row_load_data['org_id'].'payment_mode" onchange="changeProductData(this.id,1)">';
					$org_data .= '<option value="">Select Payment Mode</option>';
					$org_data .= '<option value="1">Pay Online</option>';
					$org_data .= '<option value="2">Cash On Delivery</option>';															
					$org_data .= '<option value="3">Both Pay Online and Cash On Delivery</option>';
					$org_data .= '</Select><br>';
					$org_data .= '<input type="radio" value="0" id="'.$row_load_data['org_id'].'can_not_return" onchange="changeProductData(this.id,6);" name="'.$row_load_data['org_id'].'product_return">Can Not Return';
					$org_data .= '<input type="radio" value="1" id="'.$row_load_data['org_id'].'can_return" onchange="changeProductData(this.id,6);" name="'.$row_load_data['org_id'].'product_return" >Can Return';
					$org_data .= '</div>';										
					$org_data .= '</td>';
					$org_data .= '<script type="text/javascript">';
					$org_data .= '$("#payment_mode'.$row_load_data['org_id'].'").select2();';				
					$org_data .= '</script>';																				
					$org_data .= '<td style="text-align:center;vertical-align:middle;"><input type="button" value="Products &nbsp;('.$row_load_data['prod_count'].')" class="btn-warning" id="'.$row_load_data['org_id'].'" onclick="levelAssignment(this.id);"></td>';
					$dis = checkFunctionalityRight("view_organisation.php",3);
					if($dis)			
					{
						$org_data .= '<td style="text-align:center;vertical-align:middle;">';
						if($row_load_data['org_status'] == 1)
						{
							$org_data .= '<input type="button" value="Active" id="'.$row_load_data['org_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
						}
						else
						{
							$org_data .= '<input type="button" value="Inactive" id="'.$row_load_data['org_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
						}
						$org_data .= '</td>';	
					}
					$edit = checkFunctionalityRight("view_organisation.php",1);				
					if($edit)
					{
						$org_data .= '<td style="text-align:center;vertical-align:middle;">';
						$org_data .= '<input type="button" value="Edit" id="'.$row_load_data['org_id'].'" class="btn-warning" onclick="addMoreOrganisation(this.id,\'edit\');"></td>';
					}
					$del = checkFunctionalityRight("view_organisation.php",2);
					if($del)				
					{
						$org_data .= '<td style="text-align:center;vertical-align:middle;"><div class="controls" align="center">';
						$org_data .= '<input type="checkbox" value="'.$row_load_data['org_id'].'" id="batch'.$row_load_data['org_id'].'" name="batch'.$row_load_data['org_id'].'" class="css-checkbox batch">';
						$org_data .= '<label for="batch'.$row_load_data['org_id'].'" class="css-label"></label>';
						$org_data .= '</div></td>';
					}
					$org_data .= '</tr>';		
				}
				$org_data .= '</tbody>';
				$org_data .= '</table>';
				$org_data .= $data_count;
				$response_array = array("Success"=>"Success","resp"=>$org_data);
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"No Data Available".$sql_load_data);	
			}	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");	
		}
		
		echo json_encode($response_array);
	}
	
	if((isset($obj->load_add_org_part)) != "" && isset($obj->load_add_org_part))
	{
		$org_id 	= $obj->org_id;
		$req_type 	= $obj->req_type;
		
		if($org_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$org_id."' "; // this org id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_org_data	= json_decode($row_error_data['error_data']);
		}
		elseif(($org_id != "" && $req_type == "edit") || ($org_id != "" && $req_type == "view"))
		{
			$sql_org_data 		= " SELECT * FROM `tbl_oraganisation_master` WHERE `org_id`='".$org_id."' ";
			$result_org_data 	= mysqli_query($db_con,$sql_org_data) or die(mysqli_error($db_con));
			$row_org_data		= mysqli_fetch_array($result_org_data);		
		}
		
		if($req_type != "add")
		{
			if($req_type != "error")
			{
				// Getting Billing Addrs
				$sql_billing_address 	= "SELECT * FROM `tbl_address_master` WHERE `add_user_id` = ";
				$sql_billing_address .= "'".$org_id."'";
				$sql_billing_address 	.= " and `add_id` = '".$row_org_data['org_billing_address']."' ";
				$sql_billing_address 	.= " and `add_user_type` = 'organisation' ";
				$result_billing_address	= mysqli_query($db_con,$sql_billing_address) or die(mysqli_error($db_con));
				$row_billing_address	= mysqli_fetch_array($result_billing_address);
	
				// Getting Shipping Addrs
				$sql_shipping_address 	= "SELECT * FROM `tbl_address_master` WHERE `add_user_id` = ";
				$sql_shipping_address 	.= "'".$org_id."'";	
				$sql_shipping_address 	.= " and `add_id` = '".$row_org_data['org_shipping_address']."' ";
				$sql_shipping_address 	.= " and `add_user_type` = 'organisation' ";
				$result_shipping_address	= mysqli_query($db_con,$sql_shipping_address) or die(mysqli_error($db_con));
				$row_shipping_address		= mysqli_fetch_array($result_shipping_address);	
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>$req_type);	
			}
		}
		
		$data = '';
		if($org_id != "" && $req_type == "edit")
		{
			$data = '<input type="hidden" id="org_id" value="'.$row_org_data['org_id'].'">';
		}
		elseif($org_id != "" && $req_type == "error")
		{
			$data = '<input type="hidden" id="error_id" value="'.$org_id.'">';
		}
		$data .= '<h3 style="margin:0px;">';
		$data .= 'Organisation Details';
		$data .= '</h3>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Organisation Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="org_name" name="org_name" placeholder="Organisation Name" class="input-large" data-rule-required="true"'; 
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.ucwords($row_org_data->org_name).'"';
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.ucwords($row_org_data['org_name']).'" disabled';					
		}		
		else
		{
			$data .= 'value="'.ucwords($row_org_data['org_name']).'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Organisation Name -->';
		
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Primary Email-ID <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="pri_email" name="pri_email" placeholder="Primary Email-ID" class="input-large" data-rule-email="true" onBlur="EMail(this.id, this.value)" data-rule-required="true" ';
		$data .= '<input type="text" id="pri_email" name="pri_email" placeholder="Primary Email-ID" class="input-large" data-rule-email="true" data-rule-required="true" ';  //done by monika
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_primary_email.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_primary_email'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_primary_email'].'"';					
		}
		$data .= '/>';
		$data .= '<span id="comp_pri"></span>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Primary Phone <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="pri_phone" name="pri_phone" placeholder="Primary Phone" class="input-large" data-rule-required="true" maxlength="15" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)" ';
		$data .= '<input type="text" id="pri_phone" name="pri_phone" placeholder="Primary Phone" class="input-large" data-rule-required="true" minlength="10" maxlength="10" onkeypress="return numsonly(event);" ';//done by monika
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_primary_phone.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_primary_phone'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_primary_phone'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Secondary Email-ID </label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="sec_email" name="sec_email" placeholder="Secendory Email-ID" data-rule-email="true" onBlur="EMail(this.id, this.value)" class="input-large" ';
		$data .= '<input type="text" id="sec_email" name="sec_email" placeholder="Secondary Email-ID" data-rule-email="true" class="input-large" ';  //done by monika
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_secondary_email.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_secondary_email'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_secondary_email'].'"';					
		}
		$data .= '/>';
		$data .= '<span id="comp_sec"></span>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Alt Phone </label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="alt_phone" name="alt_phone" placeholder="Alt. Phone" class="input-large" maxlength="15" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)" ';
		$data .= '<input type="text" id="alt_phone" name="alt_phone" placeholder="Alt. Phone" class="input-large" minlength="10" maxlength="10" onkeypress="return numsonly(event);" ';//done by monika
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_secondary_phone.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_secondary_phone'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_secondary_phone'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Tertiary Email-ID </label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="ter_email" name="ter_email" placeholder="Tertiary Email-ID" data-rule-email="true" onBlur="EMail(this.id, this.value)" class="input-large" ';
		$data .= '<input type="text" id="ter_email" name="ter_email" placeholder="Tertiary Email-ID" data-rule-email="true" class="input-large" ';  //done by monika
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_tertiary_email.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_tertiary_email'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_tertiary_email'].'"';					
		}
		$data .= '/>';
		$data .= '<span id="comp_ter"></span>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Fax </label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="org_fax" name="org_fax" class="input-large" placeholder="Fax" maxlength="15" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)" ';
		$data .= '<input type="text" id="org_fax" name="org_fax" class="input-large" placeholder="Fax" minlength="10" maxlength="10" onkeypress="return numsonly(event);" ';//done by monika
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_fax.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_fax'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_fax'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Website </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="org_website" name="org_website" placeholder="Website" class="input-large" ';
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_website.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_website'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_website'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Industry <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select name="org_indid" id="org_indid" class="select2-me input-large" data-rule-required="true">';
			$data .= '<option value="">Select Industry</option>';
				$sql_get_parent = " SELECT distinct ind_id,ind_name FROM `tbl_industry` ";
				$result_get_parent = mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));														
				while($row_get_parent = mysqli_fetch_array($result_get_parent))
				{
					$data .= '<option value="'.$row_get_parent['ind_id'].'"';
					if($req_type == "edit")	
					{
						if($row_get_parent['ind_id'] == $row_org_data['org_indid'])
						{
							$data .= 'selected';
						}
					}
					$data .= '>'.ucwords($row_get_parent['ind_name']).'</option>';	
				}
			$data .= '</select>';	
		}
		elseif($req_type == "view")
		{
			$sql_get_ind 	= " SELECT * FROM `tbl_industry` where ind_status = '1' and ind_id = '".$row_org_data['org_indid']."' ";
			$result_get_ind = mysqli_query($db_con,$sql_get_ind) or die(mysqli_error($db_con));														
			$row_get_ind 	= mysqli_fetch_array($result_get_ind);
			$data 			.= ucwords($row_get_ind['ind_name']);
		}
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">CST </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="org_cst" name="org_cst" placeholder="CST" class="input-large" ';
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_cst.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_cst'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_cst'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group " style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">VAT </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="org_vat" name="org_vat" placeholder="VAT" class="input-large" ';
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_vat.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_vat'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_vat'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> ';
		$data .= '<div>';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Beneficiary Name </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="org_beneficiary_name" name="org_beneficiary_name" placeholder="Beneficiary Name" class="input-large" ';
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_beneficiary_name.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_beneficiary_name'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_beneficiary_name'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Bank Name </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="org_bank_name" name="org_bank_name" placeholder="Bank Name" class="input-large" ';
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_bank_name.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_bank_name'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_bank_name'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';		
		$data .= '</div>';
		$data .= '<div>';		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Bank Address </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="org_bank_address" name="org_bank_address" placeholder="Bank Address" class="input-large" ';
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_bank_address.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_bank_address'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_bank_address'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';		
		$data .= '</div> ';	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Bank Account Number </label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="org_bank_account_number" name="org_bank_account_number" placeholder="Bank Account Number" class="input-large" ';
		$data .= '<input type="text" onkeypress="return isNumberKey(event)" id="org_bank_account_number" name="org_bank_account_number" placeholder="Bank Account Number" class="input-large" ';//done by monika
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_bank_account_number.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_bank_account_number'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_bank_account_number'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';		
		$data .= '</div> ';	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Bank IFSC Code </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;" onpaste="return false;" id="org_bank_ifsc_code" name="org_bank_ifsc_code" placeholder="Bank IFSC Code" class="input-large" ';
		
		if($org_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_org_data->org_bank_ifsc_code.'"';					
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_org_data['org_bank_ifsc_code'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_org_data['org_bank_ifsc_code'].'"';					
		}
		$data .= '/>';
		$data .= '<span id="error" style="color: Red; display: none"></span>';
		$data .= '</div>';		
		$data .= '</div> ';						
		$data .= '<div style="float:left">';
		if($req_type != "view")		
		{
			$data .= '<h3 style="margin:0px;">Address Details</h3>';
			$data .= '</div>';
			$data .= '<div style="margin-left:640px;">';
			//$data .= '<input type="checkbox" id="address_check" name="address_check"  class="css-checkbox" value="CHK" ';
			$data .= '<input type="checkbox" id="address_check" name="address_check" onclick="same_as_bill();"  class="css-checkbox" value="CHK" ';//done by monika
			if($req_type != "error")
			{
				if($row_org_data['org_shipping_address'] == $row_org_data['org_billing_address'] && ($row_org_data['org_shipping_address'] != "" && $row_org_data['org_billing_address'] != ""))
				{
					$data .= ' checked';
				}	
			}
			$data .= '>';
			$data .= '<label for="address_check" class="css-label" style="margin:12px;font-size:15px;">Same As Billing Details</label>';
			$data .= '</div>';
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Billing Address </label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="org_bill_addrs" name="org_bill_addrs" >';
		if($org_id != "" && $req_type == "error")
		{
			$data .= $row_org_data->org_billaddrs;
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= $row_billing_address['add_details'];
		}		
		else
		{
			$data .= $row_billing_address['add_details'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Shipping Address </label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="org_ship_addrs" name="org_ship_addrs" > ';
		if($org_id != "" && $req_type == "error")
		{
			$data .= $row_org_data->org_shipaddrs;
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= $row_shipping_address['add_details'];
		}		
		else
		{
			$data .= $row_shipping_address['add_details'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Billing State </label>	';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			//$data .= '<select id="bill_state" name="bill_state" class="select2-me input-large" onChange="getCity(this.value,\'bill_city\')">';	
			$data .= '<select id="bill_state" name="bill_state" class="select2-me input-large" onChange="getCity(this.value,\'bill_city\')">';//done by monika	
			$data .= '<option value="">Select Billing State</option>';
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			while($row_get_state = mysqli_fetch_array($res_get_state))
			{
				$data		.= '<option value="'.$row_get_state['state'].'"';
				if($req_type == "edit")	
				{
					if($row_get_state['state'] == $row_billing_address['add_state'])
					{
						$data .= 'selected';	
					}	
				}
				$data	.= '>'.ucwords($row_get_state['state_name']).'</option>';
			}
			$data .= '</select>';
		}
		elseif($req_type == "view")		
		{
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' and state = '".$row_billing_address['add_state']."' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			$row_get_state = mysqli_fetch_array($res_get_state);
			$data 		  .= ucwords($row_get_state['state_name']);
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Shipping State </label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select id="ship_state" name="ship_state" class="select2-me input-large" onChange="getCity(this.value,\'ship_city\')">';
			$data .= '<option value="">Select Shipping State</option>';
				$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' ";
				$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
				while($row_get_state = mysqli_fetch_array($res_get_state))
				{
					$data		.= '<option value="'.$row_get_state['state'].'"';
					if($req_type == "edit")	
					{
						if($row_get_state['state'] == $row_shipping_address['add_state'])
						{
							$data .= 'selected';	
						}	
					}
					$data	.= '>'.ucwords($row_get_state['state_name']).'</option>';
				}
			$data .= '</select>';	
		}
		elseif($req_type == "view")		
		{
			$sql_get_state = "SELECT DISTINCT `state_id`, `state`, `state_name` FROM `state` where country_id = 'IN' and state = '".$row_shipping_address['add_state']."' ";
			$res_get_state = mysqli_query($db_con, $sql_get_state) or die(mysqli_error($db_con));																
			$row_get_state = mysqli_fetch_array($res_get_state);
			$data 		  .= ucwords($row_get_state['state_name']);
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Billing City </label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			//$data .= '<select id="bill_city" name="bill_city" class="select2-me input-large" >';
			$data .= '<select id="bill_city" name="bill_city" class="select2-me input-large" >';//done by monika
			$data .= '<option value="">Select Billing City</option>';
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` WHERE `state_id` = '".$row_billing_address['add_state']."' ";
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			while($row_get_city = mysqli_fetch_array($res_get_city))
			{
				$data 			.= '<option value="'.$row_get_city['city_id'].'"';
				if($req_type == "edit")												
				{
					if($row_get_city['city_id'] == $row_billing_address['add_city'])
					{
						$data .= 'selected';
					}
				}
				$data 			.= '>'.ucwords($row_get_city['city_name']).'</option>';
			}
			$data .= '</select>';	
		}
		elseif($req_type == "view")		
		{
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` WHERE `state_id` = '".$row_billing_address['add_state']."' AND city_id='".$row_billing_address['add_city']."' ";
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			$row_get_city = mysqli_fetch_array($res_get_city);
			$data 		  .= ucwords($row_get_city['city_name']);
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Shipping City </label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select id="ship_city" name="ship_city" class="select2-me input-large" >';
			$data .= '<option value="">Select Shipping City</option>';
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` WHERE `state_id` = '".$row_shipping_address['add_state']."'  ";
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			while($row_get_city = mysqli_fetch_array($res_get_city))
			{
				$data 			.= '<option value="'.$row_get_city['city_id'].'"';
				if($req_type == "edit")												
				{
					if($row_get_city['city_id'] == $row_shipping_address['add_city'])
					{
						$data .= 'selected';
					}
				}
				$data 			.= '>'.ucwords($row_get_city['city_name']).'</option>';
			}
			$data .= '</select>';
		}
		elseif($req_type == "view")		
		{
			$sql_get_city = "SELECT `city_id`,`city_name` FROM `city` WHERE `state_id` = '".$row_shipping_address['add_state']."' AND city_id='".$row_shipping_address['add_city']."' ";
			$res_get_city = mysqli_query($db_con, $sql_get_city) or die(mysqli_error($db_con));																
			$row_get_city = mysqli_fetch_array($res_get_city);
			$data 		  .= ucwords($row_get_city['city_name']);
		}
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Billing Pin Code </label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="bill_pincode" name="bill_pincode" placeholder="Billing Pin Code" class="input-large" maxlength="6" onKeyPress="return isNumberKey(event)" ';
		$data .= '<input type="text" id="bill_pincode" name="bill_pincode" placeholder="Billing Pin Code" class="input-large" minlength="6" maxlength="6" onkeypress="return numsonly(event);" ';//done by monika
		if($org_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_billing_address['add_pincode'].'"'; 
		}
		elseif($org_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_org_data->org_billpincode.'"'; 			
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_billing_address['add_pincode'].'" disabled'; 				
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Shipping Pin Code </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="ship_pincode" name="ship_pincode" placeholder="Shipping Pin Code" class="input-large" minlength="6" maxlength="6" onkeypress="return numsonly(event);" ';
		if($org_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_shipping_address['add_pincode'].'"'; 
		}
		elseif($org_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_org_data->org_shippincode.'"'; 			
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_shipping_address['add_pincode'].'" disabled'; 				
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Description</label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="org_desc" name="org_desc" >';
		if($org_id != "" && $req_type == "error")
		{
			$data .= $row_org_data->org_description;		
		}
		elseif($org_id != "" && $req_type == "view")
		{
			$data .= $row_org_data['org_description'];					
		}		
		else
		{
			$data .= $row_org_data['org_description'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!-- Organisation Description -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($org_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="org_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_organisation.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_org_data->org_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="org_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_org_data->org_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($org_id != "" && $req_type == "view")
		{
			if($row_org_data['org_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_org_data['org_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="org_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_organisation.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_org_data['org_status'] == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="org_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_org_data['org_status'] == 0)
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
		if($org_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Organisation</button>';			
		}
		elseif($org_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Organisation</button>';
		}
		else if($org_id != "" && $req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update</button>';			
		}
		$data .= '</div> <!-- Save and cancel -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#org_indid").select2();';
		$data .= '$("#bill_state").select2();';
		$data .= '$("#bill_city").select2();';
		$data .= '$("#ship_state").select2();';
		$data .= '$("#ship_city").select2();';
		$data .= 'CKEDITOR.replace( "org_bill_addrs",{height:"150", width:"100%"});';
		$data .= 'CKEDITOR.replace( "org_ship_addrs",{height:"150", width:"100%"});';
		$data .= 'CKEDITOR.replace( "org_desc",{height:"150", width:"100%"});';
		if($org_id != "" && $req_type == "view")
		{
			$data .= '$("#org_bill_addrs").prop("disabled","true");';
			$data .= '$("#org_ship_addrs").prop("disabled","true");';
			$data .= '$("#org_desc").prop("disabled","true");';			
		}	
		$data .= '</script>';
		$response_array = array("Success"=>"Success","resp"=>$data);
		echo json_encode($response_array);
	}
	
	if((isset($obj->val_email)) != "" && isset($obj->val_email))
	{
		$email_val	= $obj->comp1_val;
		$response_array = array();
		
		if(strcmp($email_val,"")!==0)
		{
			$sql_chk_email	= " SELECT `org_primary_email`, `org_secondary_email`, `org_tertiary_email`
								FROM `tbl_oraganisation_master` 
								WHERE org_primary_email='".$email_val."'
									OR org_secondary_email='".$email_val."'
									OR org_tertiary_email='".$email_val."' ";
			$res_chk_email 	= mysqli_query($db_con, $sql_chk_email) or die(mysqli_error($db_con));
			$num_chk_email 	= mysqli_num_rows($res_chk_email);
			if(strcmp($num_chk_email,"0")===0)
			{
				$response_array = array("Success"=>"Success","resp"=>$num_chk_email);
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Email-ID Already Exist");	
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Empty Email");	
		}
		echo json_encode($response_array);	
	}
	
	if((isset($obj->insert_req_1)) == "1" && isset($obj->insert_req_1))
	{
		$org_name				= strtolower(mysqli_real_escape_string($db_con,$obj->org_name));
		$org_desc				= mysqli_real_escape_string($db_con,$obj->org_desc);
		$org_primary_email		= mysqli_real_escape_string($db_con,$obj->pri_email);
		$org_secondary_email	= mysqli_real_escape_string($db_con,$obj->sec_email);
		$org_tertiary_email		= mysqli_real_escape_string($db_con,$obj->ter_email);
		$org_primary_phone		= mysqli_real_escape_string($db_con,$obj->pri_phone);
		$org_secondary_phone	= mysqli_real_escape_string($db_con,$obj->alt_phone);
		$org_fax				= mysqli_real_escape_string($db_con,$obj->org_fax);
		$org_website			= mysqli_real_escape_string($db_con,$obj->org_website);
		$org_indid				= mysqli_real_escape_string($db_con,$obj->org_indid);
		$org_cst				= mysqli_real_escape_string($db_con,$obj->org_cst);
		$org_vat				= mysqli_real_escape_string($db_con,$obj->org_vat);
		$org_bill_addrs			= mysqli_real_escape_string($db_con,$obj->org_bill_addrs);
		$bill_state				= mysqli_real_escape_string($db_con,$obj->bill_state);
		$bill_city				= mysqli_real_escape_string($db_con,$obj->bill_city);
		$bill_pincode			= mysqli_real_escape_string($db_con,$obj->bill_pincode);
		$org_ship_addrs			= mysqli_real_escape_string($db_con,$obj->org_ship_addrs);
		$ship_state				= mysqli_real_escape_string($db_con,$obj->ship_state);
		$ship_city				= mysqli_real_escape_string($db_con,$obj->ship_city);
		$ship_pincode			= mysqli_real_escape_string($db_con,$obj->ship_pincode);		
		$org_status				= mysqli_real_escape_string($db_con,$obj->org_status);
		$org_bank_ifsc_code		= mysqli_real_escape_string($db_con,$obj->org_bank_ifsc_code);
		$org_bank_account_number= mysqli_real_escape_string($db_con,$obj->org_bank_account_number);
		$org_bank_address		= mysqli_real_escape_string($db_con,$obj->org_bank_address);
		$org_bank_name			= mysqli_real_escape_string($db_con,$obj->org_bank_name);
		$org_beneficiary_name	= mysqli_real_escape_string($db_con,$obj->org_beneficiary_name);

		
		$response_array = array();
		
/*		$response_array = array("Success"=>"fail","resp"=>$org_name."=".$org_primary_email."=".$org_primary_phone."=".$org_indid."=".$org_status);
		echo json_encode($response_array);		
		exit(0);*/
		if($org_name != '' && $org_primary_email!='' && $org_primary_phone!='' && $org_indid!='' && $org_status != "")
		{
			$response_array = insertOrganisation($response_array, $org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $org_ship_addrs, $ship_state, $ship_city, $ship_pincode, $org_desc, $org_status,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Empty Data.");		
		}
		
		echo json_encode($response_array);
	}
	
	if((isset($obj->delete_org)) == "1" && isset($obj->delete_org))
	{
		$ar_org_id 		= $obj->batch;
		$response_array = array();
		
		$del_flag 		= 0; 
		$flag_branch 	= 1;
		$flag_addrs	 	= 1;
		$flag_userowner	= 1;
		$flag_employee	= 1;
		$flag_prod		= 1;
		
		foreach($ar_org_id as $org_id)
		{
			// For Branchs
			$sql_get_branchs 	= " SELECT * FROM `tbl_branch_master` WHERE `branch_orgid`='".$org_id."' ";
			$res_get_branchs	= mysqli_query($db_con, $sql_get_branchs) or die(mysqli_error($db_con));
			$num_get_bramchs	= mysqli_num_rows($res_get_branchs);
			if(strcmp($num_get_bramchs,"0")!==0)
			{
				while($row_get_branchs = mysqli_fetch_array($res_get_branchs))
				{
					$sql_delete_branch_status	= " DELETE FROM `tbl_branch_master` WHERE `branch_orgid`='".$org_id."' ";
					$res_delete_branch_status	= mysqli_query($db_con, $sql_delete_branch_status) or die(mysqli_error($db_con));
					if($res_delete_branch_status)
					{
						$flag_branch	= 1;	
					}
					else
					{
						$flag_branch	= 0;	
					}
				}
			}
			
			if($flag_branch	== 1)
			{
				// For Address
				$sql_get_addrs		= " SELECT * FROM `tbl_address_master` WHERE `add_user_id`='".$org_id."' AND `add_user_type`='organisation' ";
				
				
				$res_get_addrs		= mysqli_query($db_con, $sql_get_addrs) or die(mysqli_error($db_con));
				$num_get_addrs		= mysqli_num_rows($res_get_addrs);
				if(strcmp($num_get_addrs,"0")!==0)
				{
					while($row_get_addrs = mysqli_fetch_array($res_get_addrs))
					{
						$sql_delete_addrs_status	= " DELETE FROM `tbl_address_master` WHERE `add_user_id`='".$org_id."' AND `add_user_type`='organisation' ";
						$res_delete_addrs_status	= mysqli_query($db_con, $sql_delete_addrs_status) or die(mysqli_error($db_con));
						
						if($res_delete_addrs_status)
						{
							$flag_addrs	= 1;	
						}
						else
						{
							$flag_addrs	= 0;
						}
					}	
				}	
			}
			
			if($flag_addrs == 1)
			{
				// For User Owners
				$sql_get_userowner	= " SELECT * FROM `tbl_users_owner` WHERE `orgid`='".$org_id."' ";
				$res_get_userowner	= mysqli_query($db_con, $sql_get_userowner) or die(mysqli_error($db_con));
				$num_get_userowner	= mysqli_num_rows($res_get_userowner);
				if(strcmp($num_get_userowner,"0")!==0)
				{
					while($row_get_userowner = mysqli_fetch_array($res_get_userowner))
					{
						$sql_delete_user_owner	= " DELETE FROM `tbl_users_owner` WHERE `orgid`='".$org_id."' ";
						$res_delete_user_owner 	= mysqli_query($db_con, $sql_delete_user_owner) or die(mysqli_error($db_con));
						
						if($res_delete_user_owner)
						{
							$flag_userowner	= 1;
						}
						else
						{
							$flag_userowner	= 0;
						}
					}
				}	
			}
			
			if($flag_userowner	== 1)
			{
				// For Employee
				$sql_get_employee	= " SELECT * FROM `tbl_employee_master` WHERE `emp_orgid`='".$org_id."' ";
				$res_get_employee	= mysqli_query($db_con, $sql_get_employee) or die(mysqli_error($db_con));
				$num_get_employee 	= mysqli_num_rows($res_get_employee);
				if(strcmp($num_get_employee,"0")!==0)
				{
					while($row_get_employee = mysqli_fetch_array($res_get_employee))
					{
						$sql_delete_employee	= " DELETE FROM `tbl_employee_master` WHERE `emp_orgid`='".$org_id."' ";
						$res_delete_employee	= mysqli_query($db_con, $sql_delete_employee) or die(mysqli_error($db_con));
						
						if($res_delete_employee)
						{
							$flag_employee	= 1;	
						}
						else
						{
							$flag_employee	= 0;
						}	
					}
				}		
			}
			
			if($flag_employee == 1)
			{
				// For products
				$sql_get_prod		= " SELECT * FROM `tbl_products_master` WHERE `prod_orgid`='".$org_id."' ";
				$res_get_prod		= mysqli_query($db_con, $sql_get_prod) or die(mysqli_error($db_con));
				$num_get_prod		= mysqli_num_rows($res_get_prod);
				if(strcmp($num_get_prod,"0")!==0)
				{
					while($row_get_prod = mysqli_fetch_array($res_get_prod))	
					{
						$sql_delete_prod 	= " DELETE FROM `tbl_products_master` WHERE `prod_orgid`='".$org_id."' ";
						$res_delete_prod	= mysqli_query($db_con, $sql_delete_prod) or die(mysqli_error($db_con));
						
						if($res_delete_prod)
						{
							$flag_prod	= 1;	
						}
						else
						{
							$flag_prod	= 0;
						}
					}
				}	
			}
			
			if($flag_prod == 1)
			{
				// Finally for Organization
				$sql_delete_status 		= " DELETE FROM `tbl_oraganisation_master` WHERE `org_id`='".$org_id."' ";
				$result_delete_status 	= mysqli_query($db_con,$sql_delete_status) or die(mysqli_error($db_con));
				if($result_delete_status)
				{
					$del_flag = 1;								
				}
				else
				{
					$del_flag = 0;
				}	
			}	
		}	
		
		if($del_flag == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Organisation Deleted Successfully.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Deletion Failed.");
		}
		
		echo json_encode($response_array);	
	}
	
	if((isset($obj->change_status)) == "1" && isset($obj->change_status))
	{
		$org_id					= mysqli_real_escape_string($db_con,$obj->org_id);
		$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
		$response_array 		= array();
	
		$status_flag 	= 0;
		$flag_branch 	= 1;
		$flag_addrs	 	= 1;
		$flag_userowner	= 1;
		$flag_employee	= 1;
		$flag_prod		= 1;
		
		if($status_flag == 0)
		{
			// For Branchs
			$sql_get_branchs 	= " SELECT * FROM `tbl_branch_master` WHERE `branch_orgid`='".$org_id."' ";
			$res_get_branchs	= mysqli_query($db_con, $sql_get_branchs) or die(mysqli_error($db_con));
			$num_get_bramchs	= mysqli_num_rows($res_get_branchs);
			if(strcmp($num_get_bramchs,"0")!==0)
			{
				while($row_get_branchs = mysqli_fetch_array($res_get_branchs))
				{
					$sql_update_branch_status	= "UPDATE `tbl_branch_master` 
														SET `branch_status`='".$curr_status."',
															`branch_modified`='".$datetime."',
															`branch_modified_by`='".$uid."' 
													WHERE `branch_orgid`='".$org_id."'";
					$res_update_branch_status	= mysqli_query($db_con, $sql_update_branch_status) or die(mysqli_error($db_con));
					if($res_update_branch_status)
					{
						$flag_branch	= 1;	
					}
					else
					{
						$flag_branch	= 0;	
					}
				}
			}
			
			if($flag_branch	== 1)
			{
				// For Address
				$sql_get_addrs		= " SELECT * FROM `tbl_address_master` WHERE `add_user_id`='".$org_id."' AND `add_user_type`='organisation' ";
				$res_get_addrs		= mysqli_query($db_con, $sql_get_addrs) or die(mysqli_error($db_con));
				$num_get_addrs		= mysqli_num_rows($res_get_addrs);
				if(strcmp($num_get_addrs,"0")!==0)
				{
					while($row_get_addrs = mysqli_fetch_array($res_get_addrs))
					{
						$sql_update_addrs_status	= "UPDATE `tbl_address_master` 
															SET `add_status`='".$curr_status."',
																`add_modified`='".$datetime."',
																`add_modified_by`='".$uid."'
														WHERE `add_user_id`='".$org_id."'
															AND `add_user_type`='organisation'";
						$res_update_addrs_status	= mysqli_query($db_con, $sql_update_addrs_status) or die(mysqli_error($db_con));
						
						if($res_update_addrs_status)
						{
							$flag_addrs	= 1;	
						}
						else
						{
							$flag_addrs	= 0;
						}
					}	
				}	
			}
			
			if($flag_addrs == 1)
			{
				// For User Owners
				$sql_get_userowner	= " SELECT * FROM `tbl_users_owner` WHERE `orgid`='".$org_id."' ";
				$res_get_userowner	= mysqli_query($db_con, $sql_get_userowner) or die(mysqli_error($db_con));
				$num_get_userowner	= mysqli_num_rows($res_get_userowner);
				if(strcmp($num_get_userowner,"0")!==0)
				{
					while($row_get_userowner = mysqli_fetch_array($res_get_userowner))
					{
						$sql_update_user_owner	= "UPDATE `tbl_users_owner` 
														SET `modified_by`='".$uid."',
															`modified`='".$datetime."',
															`status`='".$curr_status."'
													WHERE `orgid`='".$org_id."'";
						$res_update_user_owner 	= mysqli_query($db_con, $sql_update_user_owner) or die(mysqli_error($db_con));
						
						if($res_update_user_owner)
						{
							$flag_userowner	= 1;
						}
						else
						{
							$flag_userowner	= 0;
						}
					}
				}	
			}
			
			if($flag_userowner	== 1)
			{
				// For Employee
				$sql_get_employee	= " SELECT * FROM `tbl_employee_master` WHERE `emp_orgid`='".$org_id."' ";
				$res_get_employee	= mysqli_query($db_con, $sql_get_employee) or die(mysqli_error($db_con));
				$num_get_employee 	= mysqli_num_rows($res_get_employee);
				if(strcmp($num_get_employee,"0")!==0)
				{
					while($row_get_employee = mysqli_fetch_array($res_get_employee))
					{
						$sql_update_employee	= "UPDATE `tbl_employee_master` 
														SET `emp_status`='".$curr_status."',
															`emp_modified_by`='".$uid."',
															`emp_modified`='".$datetime."'
													WHERE `emp_orgid`='".$org_id."'";
						$res_update_employee	= mysqli_query($db_con, $sql_update_employee) or die(mysqli_error($db_con));
						
						if($res_update_employee)
						{
							$flag_employee	= 1;	
						}
						else
						{
							$flag_employee	= 0;
						}	
					}
				}		
			}
			
			if($flag_employee == 1)
			{
				// For products
				$sql_get_prod		= " SELECT * FROM `tbl_products_master` WHERE `prod_orgid`='".$org_id."' ";
				$res_get_prod		= mysqli_query($db_con, $sql_get_prod) or die(mysqli_error($db_con));
				$num_get_prod		= mysqli_num_rows($res_get_prod);
				if(strcmp($num_get_prod,"0")!==0)
				{
					while($row_get_prod = mysqli_fetch_array($res_get_prod))	
					{
						$sql_update_prod 	= "UPDATE `tbl_products_master` 
													SET `prod_modified_by`='".$uid."',
														`prod_modified`='".$datetime."',
														`prod_status`='".$curr_status."' 
												WHERE `prod_orgid`='".$org_id."'";
						$res_update_prod	= mysqli_query($db_con, $sql_update_prod) or die(mysqli_error($db_con));
						
						if($res_update_prod)
						{
							$flag_prod	= 1;	
						}
						else
						{
							$flag_prod	= 0;
						}
					}
				}	
			}
			
			if($flag_prod == 1)
			{
				// Finally for Organization
				$sql_update_status 		= " UPDATE `tbl_oraganisation_master` SET `org_status`= '".$curr_status."' ,`org_modified` = '".$datetime."' ,`org_modified_by` = '".$uid."' WHERE `org_id` = '".$org_id."' ";
				$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
				if($result_update_status)
				{
					$status_flag = 1;								
				}
				else
				{
					$status_flag = 0;
				}	
			}
		}
		else
		{
			$status_flag = 0;			
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
	
	if((isset($obj->update_req_1)) == "1" && isset($obj->update_req_1))
	{
		$org_id					= mysqli_real_escape_string($db_con,$obj->org_id);
		$org_name				= mysqli_real_escape_string($db_con,strtolower($obj->org_name));
		$org_desc				= mysqli_real_escape_string($db_con,$obj->org_desc);
		$org_primary_email		= mysqli_real_escape_string($db_con,$obj->pri_email);
		$org_secondary_email	= mysqli_real_escape_string($db_con,$obj->sec_email);
		$org_tertiary_email		= mysqli_real_escape_string($db_con,$obj->ter_email);
		$org_primary_phone		= mysqli_real_escape_string($db_con,$obj->pri_phone);
		$org_secondary_phone	= mysqli_real_escape_string($db_con,$obj->alt_phone);
		$org_fax				= mysqli_real_escape_string($db_con,$obj->org_fax);
		$org_website			= mysqli_real_escape_string($db_con,$obj->org_website);
		$org_indid				= mysqli_real_escape_string($db_con,$obj->org_indid);
		$org_cst				= mysqli_real_escape_string($db_con,$obj->org_cst);
		$org_vat				= mysqli_real_escape_string($db_con,$obj->org_vat);
		$org_bill_addrs			= mysqli_real_escape_string($db_con,$obj->org_bill_addrs);
		$bill_state				= mysqli_real_escape_string($db_con,$obj->bill_state);
		$bill_city				= mysqli_real_escape_string($db_con,$obj->bill_city);
		$bill_pincode			= mysqli_real_escape_string($db_con,$obj->bill_pincode);
		$org_ship_addrs			= mysqli_real_escape_string($db_con,$obj->org_ship_addrs);
		$ship_state				= mysqli_real_escape_string($db_con,$obj->ship_state);
		$ship_city				= mysqli_real_escape_string($db_con,$obj->ship_city);
		$ship_pincode			= mysqli_real_escape_string($db_con,$obj->ship_pincode);
		$org_status				= mysqli_real_escape_string($db_con,$obj->org_status);

		$org_bank_ifsc_code		= mysqli_real_escape_string($db_con,$obj->org_bank_ifsc_code);
		$org_bank_account_number= mysqli_real_escape_string($db_con,$obj->org_bank_account_number);
		$org_bank_address		= mysqli_real_escape_string($db_con,$obj->org_bank_address);
		$org_bank_name			= mysqli_real_escape_string($db_con,$obj->org_bank_name);
		$org_beneficiary_name	= mysqli_real_escape_string($db_con,$obj->org_beneficiary_name);
		
		$response_array = array();
		//echo json_encode($org_bill_addrs);exit();
		if($org_name != '' && $org_primary_email!='' && $org_primary_phone!='' && $org_indid!='')
		{//echo json_encode($org_beneficiary_name);exit();
			$response_array = updateOrganisation($response_array, $org_id, $org_name, $org_desc, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_bill_addrs, $bill_state, $bill_city, $bill_pincode, $org_ship_addrs, $ship_state, $ship_city, $ship_pincode, $org_status,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
		}	
		
		echo json_encode($response_array);
	}
	
	if((isset($obj->change_product_returnable)) == "1" && isset($obj->change_product_returnable))
	{
		$org_id				= mysqli_real_escape_string($db_con,$obj->org_id);
		$prod_returnable 	= mysqli_real_escape_string($db_con,$obj->prod_returnable);
		$sql_update_data 	= " UPDATE `tbl_products_master` SET `prod_returnable`= '".$prod_returnable."' ";
		$sql_update_data 	.= "  ,`prod_modified` = '".$datetime."' ,`prod_modified_by` = '".$uid."' WHERE `	prod_orgid` = '".$org_id."' ";
		$result_update_data = mysqli_query($db_con,$sql_update_data) or die(mysqli_error($db_con));
		if($result_update_data)
		{
			$response_array = array("Success"=>"Success","resp"=>"Updated Successfully.");
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
		}		
		echo json_encode($response_array);		
	}
	// Showing Records From Error Logs Table For Organisation ========================
	if((isset($obj->load_error)) == "1" && isset($obj->load_error))
	{
		$start_offset   = 0;
		
		$page 			= mysqli_real_escape_string($db_con,$obj->page1);	
		$per_page		= mysqli_real_escape_string($db_con,$obj->row_limit1);
		$search_text	= mysqli_real_escape_string($db_con,$obj->search_text1);	
		$cat_parent		= mysqli_real_escape_string($db_con,$obj->cat_parent1);
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
			$sql_load_data  .= " WHERE error_module_name='organisation' ";
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
				$org_data = "";	
				$org_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
				$org_data .= '<thead>';
				$org_data .= '<tr>';
				$org_data .= '<th>Sr. No.</th>';
				$org_data .= '<th>Organisation Name</th>';
				$org_data .= '<th>Created</th>';
				$org_data .= '<th>Created By</th>';
				$org_data .= '<th>Modified</th>';
				$org_data .= '<th>Modified By</th>';
				$org_data .= '<th>Edit</th>';			
				$org_data .= '<th>';
				$org_data .= '<div style="text-align:center">';
				$org_data .= '<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>';
				$org_data .= '</div>';
				$org_data .= '</th>';
				$org_data .= '</tr>';
				$org_data .= '</thead>';
				$org_data .= '<tbody>';
				while($row_load_data = mysqli_fetch_array($result_load_data))
				{
					$get_org_rec	= json_decode($row_load_data['error_data']);					
					$er_org_name	= $get_org_rec->org_name;					
					$org_data .= '<tr>';				
					$org_data .= '<td>'.++$start_offset.'</td>';				
					$org_data .= '<td>';
					$sql_chk_name_already_exist	= " SELECT * FROM `tbl_oraganisation_master` WHERE `org_name`='".$er_org_name."' ";
					$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
					$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);
					
					if(strcmp($num_chk_name_already_exist,"0")===0)
					{
						$org_data .= $er_org_name;
					}
					else
					{
						$org_data .= '<span style="color:#E63A3A;">'.$er_org_name.' [Already Exist]</span>';
					}
					$org_data .= '</td>';
					$org_data .= '<td>'.$row_load_data['error_created'].'</td>';
					$org_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
					$org_data .= '<td>'.$row_load_data['error_modified'].'</td>';
					$org_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
					$org_data .= '<td style="text-align:center">';	
					$org_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreOrganisation(this.id,\'error\');"></td>';
					$org_data .= '<td><div class="controls" align="center">';
					$org_data .= '<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
					$org_data .= '<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
					$org_data .= '</div>';
					$org_data .= '</td>';										
					$org_data .= '</tr>';															
				}	
				$org_data .= '</tbody>';
				$org_data .= '</table>';	
				$org_data .= $data_count;
				$response_array = array("Success"=>"Success","resp"=>$org_data);					
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
	
	if((isset($obj->delete_org_error)) == "1" && isset($obj->delete_org_error))
	{
		$ar_org_id 		= $obj->batch;
		$response_array = array();	
		$del_flag_error = 0; 
		foreach($ar_org_id as $org_id)	
		{
			$sql_delete_org_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$org_id."' ";
			$result_delete_org_error= mysqli_query($db_con,$sql_delete_org_error) or die(mysqli_error($db_con));			
			
			if($result_delete_org_error)
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
	// ==========================================================================
	
?>