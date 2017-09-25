<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

if((isset($_REQUEST['insert_req'])) == "1" && isset($_REQUEST['insert_req']))
{
	$cust_fname				= strtolower(mysqli_real_escape_string($db_con,$_REQUEST['cust_fname']));
	$cust_lname				= strtolower(mysqli_real_escape_string($db_con,$_REQUEST['cust_lname']));
	$cust_email				= mysqli_real_escape_string($db_con,$_REQUEST['cust_email']);
	$cust_mobile_num		= mysqli_real_escape_string($db_con,$_REQUEST['cust_mobile_num']);
	$cust_password			= mysqli_real_escape_string($db_con,$_REQUEST['cust_password']);
	$cust_status			= mysqli_real_escape_string($db_con,$_REQUEST['cust_status']);
	$response_array = array();
	if($cust_fname != "" && $cust_lname != "" && $cust_email != "" && $cust_mobile_num != "" && $cust_password != "" && $cust_status != "")
	{	
		$sql_check_customers 		= " select * from tbl_customer where cust_email like '".$cust_email."' or cust_mobile_num like '".$cust_mobile_num."'  "; 
		$result_check_customers 	= mysqli_query($db_con,$sql_check_customers) or die(mysqli_error($db_con));
		$num_rows_check_customers 	= mysqli_num_rows($result_check_customers);
		if($num_rows_check_customers == 0)
		{
			$sql_last_rec 		= "SELECT * FROM tbl_customer ORDER by cust_id DESC LIMIT 0,1";
			$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
			$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
			if($num_rows_last_rec == 0)
			{
				$cust_id 		= 1;				
			}
			else
			{
				$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
				$cust_id 		= $row_last_rec['cust_id']+1;
			}
			$sql_check 				= " SELECT `id` FROM `tbl_customer` WHERE 1=1 and ";
			$row_get_existing_cust 	= mysqli_fetch_array($result_get_existing_cust);
			
			if($row_get_existing_cust['cust_email'] != $cust_email)
			{
				$cust_email_query		= $sql_check." `cust_email_status` = '".$random_string."' ";
				$cust_email_status		= randomString($cust_email_query,5);					
			}
			if($row_get_existing_cust['cust_mobile_num'] != $cust_mobile_num)
			{
				$cust_mobile_query		= $sql_check." `cust_mobile_status` = '".$random_string."' ";
				$cust_mobile_status		= randomString($cust_mobile_query,5);				
			}				
			if($row_get_existing_cust['cust_password'] != $cust_password)
			{
				$cust_salt_value_query 	= $sql_check." `cust_salt_value` = '".$random_string."' ";
				$cust_salt_value		= randomString($cust_salt_value_query,5);
				$new_cust_password		=  md5($cust_salt_value.$cust_password);
			}						
			
			// query for insertion
			$sql_insert_customers 	= " INSERT INTO `tbl_customer`(`cust_id`, `cust_fname`, `cust_lname`, `cust_email`, `cust_mobile_num`,`cust_password`,`cust_salt_value`";
			$sql_insert_customers 	.= "  , `cust_email_status`,`cust_mobile_status`,`cust_created`, `cust_created_by`,`cust_status`) ";
			$sql_insert_customers 	.= " VALUES ('".$cust_id."', '".$cust_fname."','".$cust_lname."','".$cust_email."','".$cust_mobile_num."','".$cust_password."','".$cust_salt_value."'";
			$sql_insert_customers 	.= " ,'".$cust_email_status."','".$cust_mobile_status."','".$datetime."', '".$uid."', '".$cust_status."')";			
			$result_insert_customers = mysqli_query($db_con,$sql_insert_customers) or die(mysqli_error($db_con));
			if($sql_insert_customers)
			{
				$response_array = array("Success"=>"Success","resp"=>"Record Inserted.");			
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
			}			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Customers ".$cust_fname." already Exist");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	
	echo json_encode($response_array);		
}

if((isset($_REQUEST['update_req'])) == "1" && isset($_REQUEST['update_req']))
{
	$cust_id				= mysqli_real_escape_string($db_con,$_REQUEST['cust_id']);
	$cust_fname				= strtolower(mysqli_real_escape_string($db_con,$_REQUEST['cust_fname']));
	$cust_lname				= strtolower(mysqli_real_escape_string($db_con,$_REQUEST['cust_lname']));
	$cust_email				= mysqli_real_escape_string($db_con,$_REQUEST['cust_email']);
	$cust_mobile_num		= mysqli_real_escape_string($db_con,$_REQUEST['cust_mobile_num']);
	$cust_password			= mysqli_real_escape_string($db_con,$_REQUEST['cust_password']);
	$cust_status			= mysqli_real_escape_string($db_con,$_REQUEST['cust_status']);
	$response_array = array();
	if($cust_fname != "" && $cust_lname != "" && $cust_email != "" && $cust_mobile_num != "" && $cust_password != "" && $cust_status != "")
	{
		$sql_check_customers 		= " select * from tbl_customer where (cust_email like '".$cust_email."' or cust_mobile_num like '".$cust_mobile_num."')and cust_id != '".$cust_id."' "; 
		$result_check_customers 	= mysqli_query($db_con,$sql_check_customers) or die(mysqli_error($db_con));
		$num_rows_check_customers = mysqli_num_rows($result_check_customers);
		if($num_rows_check_customers == 0)
		{
			$sql_get_existing_cust 		= " select * from tbl_customer where cust_id = '".$cust_id."' ";
			$result_get_existing_cust	= mysqli_query($db_con,$sql_get_existing_cust) or die(mysqli_error($db_con));
			$num_rows_get_existing_cust = mysqli_num_rows($result_get_existing_cust);
			if($num_rows_get_existing_cust != 0 )
			{
				$sql_check 				= " SELECT `id` FROM `tbl_customer` WHERE 1=1 and ";
				$row_get_existing_cust 	= mysqli_fetch_array($result_get_existing_cust);
				
				$sql_update_customers = " UPDATE `tbl_customer` SET `cust_fname`='".$cust_fname."', `cust_lname`='".$cust_lname."',";
				$sql_update_customers .= " `cust_email`='".$cust_email."',`cust_mobile_num`='".$cust_mobile_num."',";
				
				if($row_get_existing_cust['cust_email'] != $cust_email)
				{
					$cust_email_query		= $sql_check." `cust_email_status` = '".$random_string."' ";
					$cust_email_status		= randomString($cust_email_query,5);					
					$sql_update_customers .= " `cust_email_status`='".$cust_email_status."',";
				}
				if($row_get_existing_cust['cust_mobile_num'] != $cust_mobile_num)
				{
					$cust_mobile_query		= $sql_check." `cust_mobile_status` = '".$random_string."' ";
					$cust_mobile_status		= randomString($cust_mobile_query,5);				
					$sql_update_customers .= " `cust_mobile_status`='".$cust_mobile_status."',";
				}				
				if($row_get_existing_cust['cust_password'] != $cust_password)
				{
					$cust_salt_value_query 	= $sql_check." `cust_salt_value` = '".$random_string."' ";
					$cust_salt_value		= randomString($cust_salt_value_query,5);
					$new_cust_password		=  md5($cust_salt_value.$cust_password);
					$sql_update_customers .= " `cust_password`='".$new_cust_password."',`cust_salt_value`='".$cust_salt_value."',";										
				}						
				$sql_update_customers .= " `cust_modified`='".$datetime."',`cust_modified_by`='".$uid."' WHERE `cust_id` = '".$cust_id."' ";
				$result_update_customers = mysqli_query($db_con,$sql_update_customers) or die(mysqli_error($db_con));
				if($result_update_customers)
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
				$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");
			}
		}		
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Customers ".$page_name." already Exists.");	
		}		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->load_customers_parts)) == "1" && isset($obj->load_customers_parts))
{
	$cust_id 		= mysqli_real_escape_string($db_con,$obj->cust_id);
	$req_type 		= strtolower(mysqli_real_escape_string($db_con,$obj->req_type));
	$response_array = array();
	if($req_type != "")
	{
		if(($cust_id != "" && $req_type == "edit") || ($cust_id != "" && $req_type == "view"))
		{
			$sql_customers_data 	= "Select * from tbl_customer where cust_id = '".$cust_id."' ";
			$result_customers_data 	= mysqli_query($db_con,$sql_customers_data) or die(mysqli_error($db_con));
			$row_customers_data		= mysqli_fetch_array($result_customers_data);		
		}	
		$data = '';
		if($cust_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="cust_id" name="cust_id" value="'.$row_customers_data['cust_id'].'">';
			$data .= '<input type="hidden" id="update_req" name="update_req" value="1">';
		}
		elseif($cust_id == "" && $req_type == "add")
		{
			$data .= '<input type="hidden" id="insert_req" name="insert_req" value="1">';
		}
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Customers Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="cust_fname" name="cust_fname" class="input-large" data-rule-required="true" ';
		if($cust_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_customers_data['cust_fname']).'"'; 
		}
		elseif($cust_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_customers_data['cust_fname']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';		
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="cust_lname" name="cust_lname" class="input-large" data-rule-required="true" ';
		if($cust_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_customers_data['cust_lname']).'"'; 
		}
		elseif($cust_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_customers_data['cust_lname']).'" disabled';
		}
		$data .= '/>';		
		$data .= '</div>';
		$data .= '</div>';		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Email<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="email" id="cust_email" name="cust_email" class="input-large" data-rule-required="true" ';
		if($cust_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.($row_customers_data['cust_email']).'"'; 
		}
		elseif($cust_id != "" && $req_type == "view")
		{
			$data .= ' value="'.($row_customers_data['cust_email']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Mobile Number<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="cust_mobile_num" name="cust_mobile_num" class="input-large" data-rule-required="true" data-rule-minlength="10" data-rule-maxlength="10"';
		if($cust_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.($row_customers_data['cust_mobile_num']).'"'; 
		}
		elseif($cust_id != "" && $req_type == "view")
		{
			$data .= ' value="'.($row_customers_data['cust_mobile_num']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';					
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Password<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="password" id="cust_password" name="cust_password" class="input-large" data-rule-required="true" autocomplete="off"';
		if($cust_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.($row_customers_data['cust_password']).'"'; 
		}
		elseif($cust_id != "" && $req_type == "view")
		{
			$data .= ' value="'.($row_customers_data['cust_password']).'" disabled';
		}
		else
		{
			$data .= ' value="" ';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($cust_id != "" && $req_type == "view")
		{
			if($row_customers_data['cust_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_customers_data['cust_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="cust_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_customers.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_customers_data['cust_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="cust_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_customers_data['cust_status'] == 0)
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
		if($cust_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($cust_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
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

if((isset($obj->load_customers)) == "1" && isset($obj->load_customers))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit_customers;
	$search_text	= $obj->search_text_customers;
	$star_status    = $obj->star_status;	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT  `cust_id`, `cust_fname`,cust_comment,cust_star, `cust_lname`, `cust_email`, `cust_email_status`, `cust_mobile_num`,";
		$sql_load_data  .= " `cust_mobile_status`,`cust_status`, `cust_created`, `cust_created_by`, `cust_modified`, `cust_modified_by`,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.cust_created_by) AS name_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.cust_modified_by) AS name_midified_by ";
		$sql_load_data  .= " FROM `tbl_customer` AS ti WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND cust_created_by='".$uid."' ";
		}
		if($star_status==1)
		{
			$sql_load_data  .= " AND cust_star='".$star_status."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (cust_fname like '%".$search_text."%' or cust_lname like '%".$search_text."%' or cust_email like '%".$search_text."%'";
			$sql_load_data .= " or cust_mobile_num like '%".$search_text."%' or cust_mobile_status like '%".$search_text."%' or cust_email_status like '%".$search_text."%'";
			$sql_load_data .= "	or cust_created like '%".$search_text."%' or cust_modified like '%".$search_text."%' or cust_comment like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY cust_created DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$customers_data = "";	
			$customers_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$customers_data .= '<thead>';
    	  	$customers_data .= '<tr>';
         	$customers_data .= '<th style="text-align:center">Sr No.</th>';
			$edit = checkFunctionalityRight("view_customers.php",1);
			if($edit)
			{
			$customers_data .= '<th style="text-align:center">Star</th>';
			}
			$customers_data .= '<th style="text-align:center">Cust ID</th>';
			$customers_data .= '<th style="text-align:center">Customer Info</th>';
			$customers_data .= '<th style="text-align:center">Created Date</th>';
			$customers_data .= '<th style="text-align:center">Address</th>';			
			$dis = checkFunctionalityRight("view_customers.php",3);
			if($dis)
			{					
				$customers_data .= '<th style="text-align:center">Status</th>';											
			}
			$edit = checkFunctionalityRight("view_customers.php",1);
			if($edit)
			{					
				$customers_data .= '<th style="text-align:center">Edit</th>';			
			}	
			$delete = checkFunctionalityRight("view_customers.php",2);
			
			if($delete)
			{					
				$customers_data .= '<th style="text-align:center"><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
			if($delete && $edit)
			{
				$customers_data .= '<th style="text-align:center">Reset Password</th>';			
			}	
			if($edit)
		    {
			$customers_data .= '<th style="text-align:center">Comments</th>';	
			}
          	$customers_data .= '</tr>';
      		$customers_data .= '</thead>';
      		$customers_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$customers_data .= '<tr>';				
				$customers_data .= '<td style="text-align:center">'.++$start_offset.'</td>';
				$edit = checkFunctionalityRight("view_customers.php",1);
				if($edit)
				{
					$customers_data .= '<td style="text-align:center"><i id="'.$row_load_data['cust_id'].'star_status" ';
					if($row_load_data['cust_star'] == 1)
					{
						$customers_data .= ' onclick="changeStarStatus(this.id,\'0\');" class="icon-star" style="font-size:30px;cursor:pointer;color:#FFD700;padding:5px;margin-top:10px"></i>';
					}
					else
					{
						$customers_data .= ' onclick="changeStarStatus(this.id,\'1\');" class="icon-star-empty" style="font-size:30px;cursor:pointer;padding:5px;margin-top:10px"></i> ';					
					}
					$customers_data .='</td>';
				}				
				$customers_data .= '<td style="text-align:center">'.$row_load_data['cust_id'].'</td>';
				$customers_data .= '<td><input type="button" value="'.ucwords($row_load_data['cust_fname']).' '.ucwords($row_load_data['cust_lname']).'" class="btn-link" id="'.$row_load_data['cust_id'].'" onclick="addMoreCustomers(this.id,\'view\');">';
				$customers_data .= '<i class="icon-chevron-down" id="'.$row_load_data['cust_id'].'chevron" onclick="toggleMyDiv(this.id,\'cust_info'.$row_load_data['cust_id'].'\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$customers_data .= '<div id="cust_info'.$row_load_data['cust_id'].'" style="display:none;">';				
				$customers_data .= '<div><b>Email:</b>&nbsp;'.$row_load_data['cust_email'].'</div>';
				$customers_data .= '<div><b>Mobile Number:</b>&nbsp;'.$row_load_data['cust_mobile_num'].'</div>';								
				$customers_data .= '<div><b>Created:</b>&nbsp;';
				if(trim($row_load_data['cust_created']) == "")
				{
					$customers_data .= '<span style="color:#F00">Not Available</span>';
				}
				else
				{
					$customers_data .= $row_load_data['cust_created'];			
				}
				$customers_data .= '</div>';
				$customers_data .= '<div><b>Created By:</b>&nbsp;';
				if(trim($row_load_data['cust_modified']) == "")
				{
					$customers_data .= '<span style="color:#F00">Not Available</span>';
				}
				else
				{
					$customers_data .= $row_load_data['cust_modified'];					
				}				
				$customers_data .= '</div>';	
				$customers_data .= '<div><b>Modified By:</b>';
				if(trim($row_load_data['name_midified_by']) == "")
				{
					$customers_data .= '<span style="color:#F00">Not Available</span>';
				}
				else
				{
					$customers_data .= $row_load_data['name_midified_by'];					
				}				
				$customers_data .= '</div>';
				$customers_data .= '</div>';				
				$customers_data .= '</td>';
				$date = strtotime($row_load_data['cust_created']);
	           
				$customers_data .= '<td style="text-align:center">'.date(' j F, Y',$date).'</td>';
				
				$sql_get_cust_address 		= " SELECT `add_id`, `add_details`, `add_state`, `add_city`, `add_pincode`, `add_lat_long`,`add_address_type`,";
				$sql_get_cust_address 		.= " (SELECT `state_name` FROM `state` WHERE `state` = add_state) as cust_state,";
				$sql_get_cust_address 		.= " (SELECT `city_name` FROM `city` WHERE `city_id`= add_city) as cust_city";
				$sql_get_cust_address 		.= " FROM `tbl_address_master` WHERE `add_user_id` = '".$row_load_data['cust_id']."' and `add_user_type` = 'customer' ";
				$result_get_cust_address 	= mysqli_query($db_con,$sql_get_cust_address) or die(mysqli_error($db_con));
				$num_rows_get_cust_address 	= mysqli_num_rows($result_get_cust_address);
				
				if($num_rows_get_cust_address==0)
				{
					$btn_class =" btn-danger ";
				}
				else
				{
					$btn_class =" btn-success ";
				}
				$customers_data .= '<td><div style="text-align:center"><button class="'.$btn_class.'" style="width:60%" onclick="toggleMyDiv(\''.$row_load_data['cust_id'].'chevron_address'.'\',\'cust_address'.$row_load_data['cust_id'].'\');">'.ucwords($row_load_data['cust_fname']).'\'s Address';
				$customers_data .= '<i class="icon-chevron-down" id="'.$row_load_data['cust_id'].'chevron_address" style="float:right:"></i></button></div>';
				$customers_data .= '<div id="cust_address'.$row_load_data['cust_id'].'" style="display:none;">';				
				
				if($num_rows_get_cust_address != 0)
				{
					while($row_get_cust_address = mysqli_fetch_array($result_get_cust_address))
					{
						$customers_data .= '<div><b>'.ucwords($row_get_cust_address['add_address_type']).':</b></div>';
						$customers_data .= '<div><b>Detail Address:</b>&nbsp;'.$row_get_cust_address['add_details'].'</div>';
						$customers_data .= '<div>'.$row_get_cust_address['add_pincode'].','.$row_get_cust_address['cust_city'].',</div>';
						$customers_data .= '<div>'.$row_get_cust_address['cust_state'].',</div><br>';					
					}
				}
				else
				{
					$customers_data .= '<span style="color:#F00">Not Available</span>';
				}
				$customers_data .= '</div>';
				$customers_data .= '</td>';
				$dis = checkFunctionalityRight("view_customers.php",3);
				if($dis)
				{					
					$customers_data .= '<td style="text-align:center">';					
					if($row_load_data['cust_status'] == 1)
					{
						$customers_data .= '<input type="button" value="Active" id="'.$row_load_data['cust_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$customers_data .= '<input type="button" value="Inactive" id="'.$row_load_data['cust_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$customers_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_customers.php",1);
				if($edit)
				{				
						$customers_data .= '<td style="text-align:center">';
						$customers_data .= '<input type="button" value="Edit" id="'.$row_load_data['cust_id'].'" class="btn-warning" onclick="addMoreCustomers(this.id,\'edit\');"></td>';				
				}
				$delete = checkFunctionalityRight("view_customers.php",2);
				if($delete)
				{					
					$customers_data .= '<td><div class="controls" align="center">';
					$customers_data .= '<input type="checkbox" value="'.$row_load_data['cust_id'].'" id="customers'.$row_load_data['cust_id'].'" name="customers'.$row_load_data['cust_id'].'" class="css-checkbox customers">';
					$customers_data .= '<label for="customers'.$row_load_data['cust_id'].'" class="css-label"></label>';
					$customers_data .= '</div></td>';										
				}
				
				if($edit && $delete)
			    {
				   $customers_data .= '<td><input type="button" value="Reset Password" id="'.$row_load_data['cust_email'].'" class="btn-warning" onclick="resetpassword(this.id);"></td>';							
			    }
				if($edit)
			    {
				$customers_data .= '<td>
				<textarea name="comment_'.$row_load_data['cust_id'].'" id="comment_'.$row_load_data['cust_id'].'" onchange="comments('.$row_load_data['cust_id'].');">'.$row_load_data['cust_comment'].'</textarea><br>
				
</td>';							
				}
	          	$customers_data .= '</tr>';															
			}	
      		$customers_data .= '</tbody>';
      		$customers_data .= '</table>';	
			$customers_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$customers_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available in Customers");
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
	$cust_id				= $obj->cust_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();

	$status_flag = 0;
	$sql_check_parent 		= "Select * from tbl_customer where `cust_id` = '".$cust_id."' ";
	$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
	$row_check_parent 		= mysqli_fetch_array($result_check_parent);
	
	$sql_update_status 		= " UPDATE `tbl_customer` SET `cust_status`= '".$curr_status."' ,`cust_modified` = '".$datetime."' ,`cust_modified_by` = '".$uid."' WHERE `cust_id`='".$cust_id."' ";
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

if((isset($obj->delete_customers)) == "1" && isset($obj->delete_customers))
{
	$response_array = array();		
	$ar_customers_id 		= $obj->customers;
	$del_flag 		= 0; 
	foreach($ar_customers_id as $cust_id)	
	{
		$sql_delete_customers		= " DELETE FROM `tbl_customer` WHERE `cust_id` = '".$cust_id."' ";
		$result_delete_customers	= mysqli_query($db_con,$sql_delete_customers) or die(mysqli_error($db_con));			
		if($result_delete_customers)
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

if((isset($obj->reset_pass)) == "1" && isset($obj->reset_pass))
{   
    $cust_email 		= $obj->cust_email;
	if($cust_email != "")
	{
		$sql_check_user 		= " SELECT * FROM `tbl_customer` tc WHERE tc.`cust_email` = '".$cust_email."' ";
		$result_check_user		= mysqli_query($db_con,$sql_check_user) or die(mysqli_error($db_con));
		$num_rows_check_user 	= mysqli_num_rows($result_check_user); 
		if($num_rows_check_user == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"<b>".$cust_email."</b> this is not registered email id .");
		}
		else
		{
			$row_check_user 	= mysqli_fetch_array($result_check_user);
			$cust_mobile_num	= $row_check_user['cust_mobile_num'];
			$cust_email			= $row_check_user['cust_email'];
			$cust_fname			= $row_check_user['cust_fname'];
			$subject			= "Forgot Password mail";
			$cust_id			= $row_check_user['cust_id'];
			$cust_salt_value	= $row_check_user['cust_salt_value'];			
			$cust_created		= $row_check_user['cust_created'];				
			$cust_modified		= $row_check_user['cust_modified'];					
			$token				= md5($cust_id.$cust_salt_value.$cust_email.$cust_created.$cust_modified);
			$forget_password_url= $BaseFolder."/page-reset-password.php?userid=".$cust_id."&token=".$token;	
			
			$message_body	= '';
			$message_body .= '<table class="" data-module="main Content" height="100" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
				$message_body .= '<tr>';
					$message_body .= '<td>';
						$message_body .= '<table data-bgcolor="BG Color" height="100" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
							$message_body .= '<tr>';
								$message_body .= '<td>';
									$message_body .= '<table data-bgcolor="BG Color 01" height="100" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
										$message_body .= '<tr>';
											$message_body .= '<td>';
												$message_body .= '<table height="100" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
													$message_body .= '<tr>';
														$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
													$message_body .= '</tr>';
													$message_body .= '<tr>';
														$message_body .= '<td height="100" width="520">';
															$message_body .= '<table align="center" width="520" border="0" cellpadding="0" cellspacing="0">';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center">  Password Recovery <br></td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-size:14px; color:#494949; font-family:\'Open Sans\', sans-serif; align="left"><br>Dear '.ucwords($cust_fname).',<br></td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td>';
																		$message_body .= '<table data-bgcolor="Color Button 01" class="table-button230-center" style="border-radius: 900px; " height="36" width="230" align="center" bgcolor="#5bbc2e" border="0" cellpadding="0" cellspacing="0">';
																			$message_body .= '<tr>';
																				$message_body .= '<td style="padding: 5px 5px; margin-bottom:20px;font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;" valign="middle" align="center"><a data-color="Text Button 01" data-size="Text Button 01" data-min="6" data-max="20" href="'.$forget_password_url.'" style="font-weight:bold; font-size:15px; color:#ffffff; letter-spacing: 0.005em; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly; text-decoration: none;">Reset Password</a></td>';//Here\'s the link to change your password as per your request.</a></td>';
																			$message_body .= '</tr>';
																		$message_body .= '</table>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
																$message_body .= '<tr>';
																	$message_body .= '<td data-color="Name" data-size="Name" align="left"> <br>We have received your request for a new password.<br>';
																	$message_body .= '</td>';
																$message_body .= '</tr>';
															$message_body .= '</table>';
														$message_body .= '</td>';
													$message_body .= '</tr>';
													$message_body .= '<tr>';
														$message_body .= '<td data-color="Name" data-size="Name" align="left" style="color:#ccc;"><br> You didn\'t request for a new password? Please write to <a href="mailto:support@planeteducate.com">support@planeteducate.com</a> immediately. <br>';
														$message_body .= '</td>';
													$message_body .= '</tr>';			
													$message_body .= '<tr>';
														$message_body .= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
													$message_body .= '</tr>';			
												$message_body .= '</table>';
											$message_body .= '</td>';
										$message_body .= '</tr>';
									$message_body .= '</table>';
								$message_body .= '</td>';
							$message_body .= '</tr>';
						$message_body .= '</table>';
					$message_body .= '</td>';
				$message_body .= '</tr>';
			$message_body .= '</table>';			
					
			$message = mail_template_header()."".$message_body."".mail_template_footer();
			//$message 					= mail_template_header()."".$message_body."".mail_template_footer();
			// sendEmail($cust_email,$subject,$message);
			//$email_message		.= "Please <a href='".$forget_password_url."'>click here</a> to reset password.";
			//if(sendEmail($cust_email,$subject,$message))
			if($cust_email)
			{	
				$res_insert_into_tbl_notification	= '';
				//$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_forgot_password', $message, $cust_email, $cust_mobile_num);
				
				//sendEmail('support@planeteducate.com',$subject,$message);
			//	$res_insert_into_tbl_notification	= insertEmailSmsEntryIntoNotification('Email_forgot_password', $message, 'support@planeteducate.com', '02261572611');
				
				$response_array = array("Success"=>"Success","resp"=>"<div style='color:green;' align='center'><h4>Please Check your email.</h4></div>");
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Email not sent please try after sometime");
			}
		}
	}
	else
	{			
		$response_array = array("Success"=>"fail","resp"=>"Email Id Blank");
	}
	$response_array = array("Success"=>"Success","resp"=>"Password Changed Successfully");			
	echo json_encode($response_array);	
}

if((isset($obj->get_comments1)) == "1" && isset($obj->get_comments1))
{   

    $start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit_customers;
	$search_text	= $obj->search_text_customers;	
	
	$cur_page 		= $page;
	$page 	   	   	= $page - 1;
	$start_offset += $page * $per_page;
	$start 			= $page * $per_page;
    $cust_id 		= $obj->cust_id;
	
	
	$comments_data  = "";
	$cust_id =54;
	if($cust_id != "")
	{
		$sql_get_comments  = ' SELECT * FROM tbl_review_master as trm ';
		$sql_get_comments .= ' INNER JOIN tbl_products_master as tpm ON trm.review_prod_id = tpm.prod_id  ';
		$sql_get_comments .= ' INNER JOIN tbl_products_images AS tpi ON tpm.prod_id = tpi.prod_img_prodid  ';
		$sql_get_comments .= ' WHERE review_cust_id=\''.$cust_id.'\' AND tpi.prod_img_type = \'main\'  LIMIT '.$start.','. $per_page ;
		
		$result_get_comments = mysqli_query($db_con,$sql_get_comments) or die(mysqli_error($db_con));	
		$num_get_comments  =mysqli_num_rows($result_get_comments);
		$data_count		= 	dataPagination($sql_get_comments,$per_page,$start,$cur_page);	
	    	
			
		if($num_get_comments > 0)
		{
			$comments_data				= '<table class="table table-bordered dataTable" style="width:100%;">';
			$comments_data				.= '<thead>';
			$comments_data				.= '<tr>';
			$comments_data				.= '<th align="center"  style="width="30%">Cust Name</th>';
			$comments_data				.= '<th align="center"  style="width="30%">Product</th>';
			$comments_data				.= '<th align="center" style="width="30%">Image</th>';	
			$comments_data				.= '<th align="center"  style="width="15%">Comment</th>';
			$comments_data				.= '<th align="center"  style="width="10%">Date</th>';
			$comments_data				.= '<th align="center"  style="width="15%">Reply</th>';	
			$comments_data				.= '<tr>';
			$comments_data				.= '</thead>';
			$comments_data				.= '<tbody>';
			
			while($cmt_row = mysqli_fetch_array($result_get_comments))
			{
				$comments_data				.= '<tr>';
				$comments_data				.= '<td align="center"  style="width="30%">'.$cmt_row['prod_name'].'</td>';
				$comments_data				.= '<td align="center"  style="width="30%">'.$cmt_row['prod_name'].'</td>';
				$comments_data				.= '<td align="center"  style="width="30%">';
				$imagepath 		= '../images/planet/org'.$cmt_row['prod_orgid'].'/prod_id_'.$cmt_row['prod_id'].'/medium/'.$cmt_row['prod_img_file_name'];
				$comments_data				.= '<img style="width:100px; height="100px" src="'.$imagepath.'"';
				$comments_data				.= '</td>';	
				$comments_data				.= '<td align="center"  style="width="15%">'.$cmt_row['review_content'].'</td>';
				$comments_data				.= '<td align="center" style="width="10%">'.$cmt_row['review_created'].'</td>';
				$comments_data				.= '<td align="center" style="width="15%">Reply</td>';	
				$comments_data				.= '<tr>';
			}
			$comments_data				.= '</tbody>';
			$comments_data				.= '</table>';
		}
		$comments_data .=$data_count;
		
		
		$response_array = array("Success"=>"Success","resp"=>$comments_data);
		
	}
	else
	{			
		$response_array = array("Success"=>"fail","resp"=>"Cust Id Blank");
	}
			
	echo json_encode($response_array);	
}
if((isset($obj->get_comments)) == "1" && isset($obj->get_comments))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit_customers;
	$search_text	= $obj->search_text_customers;	
	
	if($page != "" && $per_page != "")	
	{
		
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$comments_data  = "";
	$cust_id =54;
	if($cust_id != "")
	{  
		$sql_get_comments  = ' SELECT * FROM tbl_review_master as trm ';
		$sql_get_comments .= ' INNER JOIN tbl_products_master as tpm ON trm.review_prod_id = tpm.prod_id  ';
		$sql_get_comments .= ' INNER JOIN tbl_customer as tc ON trm.review_cust_id = tc.cust_id  ';
		$sql_get_comments .= ' INNER JOIN tbl_products_images AS tpi ON tpm.prod_id = tpi.prod_img_prodid  ';
		$sql_get_comments .= ' WHERE review_cust_id=\''.$cust_id.'\' AND tpi.prod_img_type = \'main\' ';
		$data_count		   = 	dataPagination($sql_get_comments,$per_page,$start,$cur_page);
		$sql_get_comments .='  LIMIT '.$start.','. $per_page ;
		
		$result_get_comments = mysqli_query($db_con,$sql_get_comments) or die(mysqli_error($db_con));	
		$num_get_comments  =mysqli_num_rows($result_get_comments);
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$comments_data			    .= '<table class="table table-bordered dataTable" style="width:100%;">';
			$comments_data				.= '<thead>';
			$comments_data				.= '<tr>';
			$comments_data				.= '<th align="center"  style="width="30%">Cust Name</th>';
			$comments_data				.= '<th align="center"  style="width="30%">Product</th>';
			$comments_data				.= '<th align="center" style="width="30%">Image</th>';	
			$comments_data				.= '<th align="center"  style="width="15%">Comment</th>';
			$comments_data				.= '<th align="center"  style="width="10%">Date</th>';
			$comments_data				.= '<th align="center"  style="width="15%">Reply</th>';	
			$comments_data				.= '<tr>';
			$comments_data				.= '</thead>';
			$comments_data				.= '<tbody>';
			while($cmt_row = mysqli_fetch_array($result_get_comments))
			{
	    	  	$comments_data				.= '<tr>';
				$comments_data				.= '<td align="center"   align="center">'.$cmt_row['cust_fname'].' '.$cmt_row['cust_lname'].'</td>';
				$comments_data				.= '<td align="center" style="width="30%" >'.$cmt_row['prod_name'].'</td>';
				$comments_data				.= '<td align="center"  style="width="30%">';
				$imagepath 		= '../images/planet/org'.$cmt_row['prod_orgid'].'/prod_id_'.$cmt_row['prod_id'].'/medium/'.$cmt_row['prod_img_file_name'];
				$comments_data				.= '<img style="width:100px; height="100px" src="'.$imagepath.'"';
				$comments_data				.= '</td>';	
				$comments_data				.= '<td align="center"  style="width:15%">'.$cmt_row['review_content'].'</td>';
				$comments_data				.= '<td align="center" style="width="10%">'.$cmt_row['review_created'].'</td>';
				$comments_data				.= '<td align="center" style="width="15%">Reply</td>';	
				$comments_data				.= '<tr>';														
			}	
      		$comments_data .= '</tbody>';
      		$comments_data .= '</table>';	
			$comments_data .= $data_count;
			
			$response_array = array("Success"=>"Success","resp"=>$comments_data);					
		}
		else
		{ 
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
		}
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
if((isset($obj->update_comments)) == "1" && isset($obj->update_comments))
{
    $comment  = $obj->comment;
	$cust_id  = $obj->cust_id;
	$response_array = array();
	$sql_update_comment = " UPDATE `tbl_customer` SET `cust_comment`='".$comment."' WHERE cust_id ='".$cust_id."' ";
	$res_update_comment = mysqli_query($db_con,$sql_update_comment) or die(mysqli_error($db_con));
	if($res_update_comment)
	{
		$response_array = array("Success"=>"Success","resp"=>$sql_update_comment);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"");
	}
	echo json_encode($response_array);
}
if((isset($obj->update_starstatus)) == "1" && isset($obj->update_starstatus))
{
    $status  = $obj->status;
	$cust_id  = $obj->cust_id;
	$response_array = array();
	$sql_update_star = " UPDATE `tbl_customer` SET `cust_star`='".$status."' WHERE cust_id ='".$cust_id."' ";
	$res_update_star = mysqli_query($db_con,$sql_update_star) or die(mysqli_error($db_con));
	if($res_update_star)
	{
		$response_array = array("Success"=>"Success","resp"=>$sql_update_comment);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"");
	}
	echo json_encode($response_array);
}
?>