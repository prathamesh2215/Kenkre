<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


//------------------this is used for inserting records---------------------
if((isset($_POST['insert_coach'])) == "1" && isset($_POST['insert_coach']))
{
	$data['fullname']      = mysqli_real_escape_string($db_con,$_POST['coach_name']);
	$data['email']         = mysqli_real_escape_string($db_con,$_POST['coach_email']);
	$data['mobile_num']    = mysqli_real_escape_string($db_con,$_POST['coach_mobile']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['area_status']);
	$data['state']         = mysqli_real_escape_string($db_con,$_POST['state_code']);
	$data['city']          = mysqli_real_escape_string($db_con,$_POST['city']);
	$data['mobile_num']    = mysqli_real_escape_string($db_con,$_POST['coach_mobile']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['area_status']);
	
	$data['created_by']    = $logged_uid;
	$data['created']       = $datetime;
	$password              = generateRandomString(8);
	$data['salt_value']    = generateRandomString(6);
	$data['password']      = md5($password.$data['salt_value']);
	$data['sms_status']    = 1;
	$data['userid']        = $data['email'];
	$data['utype']         = 15;
	
	$sql_check             = " SELECT * FROM tbl_cadmin_users WHERE email='".$data['email']."' or mobile_num='".$data['mobile_num']."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = insert('tbl_cadmin_users',$data); 
		    
			if($insert_id)
			{
				$adata['add_details']    = mysqli_real_escape_string($db_con,$_POST['address']);
				$adata['add_state']      = mysqli_real_escape_string($db_con,$_POST['state_code']);
				$adata['add_city']       = mysqli_real_escape_string($db_con,$_POST['city']);
				$adata['add_area']       = mysqli_real_escape_string($db_con,$_POST['area']);
				$adata['add_pincode']    = mysqli_real_escape_string($db_con,$_POST['area_pincode']);
				$adata['add_status']     = mysqli_real_escape_string($db_con,$_POST['area_status']);
				$adata['add_user_id']    = $insert_id;
				$adata['add_user_type']  = 'admin';
				$adata['add_created']    = $datetime;
				$adata['add_created_by'] = $logged_uid;
				$adata['add_id']         = getNewId('add_id','tbl_address_master');
				insert('tbl_address_master',$adata);
				quit('Success',1);
			}
			else
			{
				quit('Please try letter...!');
			}
			
		  
	}
	else
	{
		quit('Email or Mobile Number already Exist...!');
	}	
}


if((isset($_POST['update_coach'])) == "1" && isset($_POST['update_coach']))
{
	$data['fullname']      = mysqli_real_escape_string($db_con,$_POST['coach_name']);
	$data['email']         = mysqli_real_escape_string($db_con,$_POST['coach_email']);
	$data['mobile_num']    = mysqli_real_escape_string($db_con,$_POST['coach_mobile']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['area_status']);
	$data['state']         = mysqli_real_escape_string($db_con,$_POST['state_code']);
	$data['city']          = mysqli_real_escape_string($db_con,$_POST['city']);
	$data['mobile_num']    = mysqli_real_escape_string($db_con,$_POST['coach_mobile']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['area_status']);
	
	$id                    = mysqli_real_escape_string($db_con,$_POST['id']);
	
	$data['created_by']    = $logged_uid;
	$data['created']       = $datetime;
	$password              = generateRandomString(8);
	$data['salt_value']    = generateRandomString(6);
	$data['password']      = md5($password.$data['salt_value']);
	$data['sms_status']    = 1;
	$data['userid']        = $data['email'];
	$data['utype']         = 15;
	
	$sql_check             = " SELECT * FROM tbl_cadmin_users WHERE email='".$data['email']."' or mobile_num='".$data['mobile_num']."' AND id='".$id."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = update('tbl_cadmin_users',$data,array('id'=>$id)); 
		    
			if($insert_id)
			{
				$adata['add_details']    = mysqli_real_escape_string($db_con,$_POST['address']);
				$adata['add_state']      = mysqli_real_escape_string($db_con,$_POST['state_code']);
				$adata['add_city']       = mysqli_real_escape_string($db_con,$_POST['city']);
				$adata['add_area']       = mysqli_real_escape_string($db_con,$_POST['area']);
				$adata['add_pincode']    = mysqli_real_escape_string($db_con,$_POST['area_pincode']);
				$adata['add_status']     = mysqli_real_escape_string($db_con,$_POST['area_status']);
				$adata['add_user_id']    = $insert_id;
				$adata['add_user_type']  = 'admin';
				$adata['add_created']    = $datetime;
				$adata['add_created_by'] = $logged_uid;
				$adata['add_id']         = getNewId('add_id','tbl_address_master');
				update('tbl_address_master',$adata,array('add_user_id'=>$id));
			}
			else
			{
				quit('Please try letter...!');
			}
			
		   
			
			quit('Success',1);
		
	}
	else
	{
		quit('Email or Mobile Number already Exist...!');
	}	
}



if((isset($obj->load_coach)) == "1" && isset($obj->load_coach))
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
			
		$sql_load_data  = " SELECT tc.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.modified_by) AS name_midified_by 
							FROM `tbl_cadmin_users` AS tc WHERE utype=15";
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND created='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (fullname like '%".$search_text."%' or email = '".$search_text."' ";
			$sql_load_data .= " or mobile_num = '".$search_text."') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Coach Name</th>';
			$area_data .= '<th style="text-align:center">Email</th>';
			$area_data .= '<th style="text-align:center">Mobile Number</th>';
			$area_data .= '<th style="text-align:center">Created Date</th>';
			$area_data .= '<th style="text-align:center">Created By</th>';
			$area_data .= '<th style="text-align:center">Modified Date</th>';
			$area_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_coach.php",3);
			$dis = 1;
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_coach.php",1);
			$edit = 1;
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_coach.php",2);
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
			
				$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['fullname']).'" class="btn-link" id="'.$row_load_data['id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
					$area_data .= '<td style="text-align:center">'.$row_load_data['email'].'</td>';
				
				$area_data .= '<td style="text-align:center">'.$row_load_data['mobile_num'].'</td>';			
				$area_data .= '<td style="text-align:center">'.$row_load_data['created'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['modified'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_coach.php",3);
				$dis = 1;
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['status'] == 1)
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_coach.php",1);
				$edit = 1;
				if($edit)
				{				
					$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Edit" id="'.$row_load_data['id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_coach.php",2);
				$delete = 1;
				if($delete)
				{					
					$area_data .= '<td><div class="controls" align="center">';
					$area_data .= '<input type="checkbox" value="'.$row_load_data['id'].'" id="batch'.$row_load_data['id'].'" name="batch'.$row_load_data['id'].'" class="css-checkbox batch">';
					$area_data .= '<label for="batch'.$row_load_data['id'].'" class="css-label"></label>';
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

if((isset($obj->load_batch_parts)) == "1" && isset($obj->load_batch_parts))
{
	$batch_id        = $obj->batch_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($id != "" && $req_type != "add")
		{
			$sql_area_data 	    = "Select * from tbl_cadmin_users where id = '".$batch_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}	
			
		$data = '';
		if($id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" name="batch_id" id="batch_id" value="'.$id.'">';
			$data .= '<input type="hidden" name="update_batch" id="update_batch" value="1">';
		}        
		
		
		if($req_type=='add')
		{
				$data .= '<input type="hidden" name="insert_batch" id="insert_batch" value="1">';
		}
		
		//////=============================================Start : Coach Name======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Batch Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return charsonly(event);" type="text" id="batch_name" name="batch_name" class="input-large keyup-char" placeholder="Batch Name" data-rule-required="true" ';
		if($id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_area_data['batch_name'].'"'; 
		}
		elseif($id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_area_data['batch_name'].'" disabled'; 				
		}
		$data .= '/><br>';
		$data .= '</div>';
		$data .= '</div> <!-- batch_name Name -->';
		
		
		//////=============================================Start : Coach Email======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Student Limit<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input  type="email" id="batch_limit" name="batch_limit" class="input-large keyup-char" placeholder="Student Limit" data-rule-required="true" ';
		if($id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_area_data['batch_limit'].'"'; 
		}
		elseif($id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_area_data['batch_limit'].'" disabled'; 				
		}
		$data .= '/><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Batch Limit -->';
		
		
		//////=============================================Start : Coach Mobile Number======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Schedule<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">
					<table class="table table-bordered dataTable" style="width:50%;text-align:center">
					<thead>
					<tr>
					  <th>
					     Days<br>
					     <input name="checkall" id="checkall" onclick="allDays()" type="checkbox"> All
					  </th>
					  <th>From</th>
					  <th>To</th>
					</tr></thead>  
					
					<tbody>
					<tr>
					<td><input class="cdays" name="days[]" id="Monday" value="Monday" type="checkbox"> Mon</td>
					  <td><input name="Monday_form" id="Monday_form" class="form_time timepicker" type="text"></td>
					  <td><input name="Monday_to" id="Monday_to" class="to_time timepicker" type="text"></td>
					</tr>
					<tr>
					  <td><input class="cdays" name="days[]" id="Tuesday" value="Tuesday" type="checkbox"> Tue</td>
					  <td><input name="Tuesday_form" id="Tuesday_form" class="form_time timepicker" type="text"></td>
					  <td><input name="Tuesday_to" id="Tuesday_to" class="to_time timepicker" type="text"></td>
					</tr>
					<tr>
					 <td><input class="cdays" name="days[]" id="Wednesday" value="Wednesday" type="checkbox"> Wed</td>
					  <td><input name="Wednesday_form" id="Wednesday_form" class="form_time timepicker" type="text"></td>
					  <td><input name="Wednesday_to" id="Wednesday_to" class="to_time timepicker" type="text"></td>
					</tr>
					<tr>
					<td><input class="cdays" name="days[]" id="Thursday" value="Thursday" type="checkbox"> Thu</td>
					  <td><input name="Thursday_form" id="Thursday_form" class="form_time timepicker" type="text"></td>
					  <td><input name="Thursday_to" id="Thursday_to" class="to_time timepicker" type="text"></td>
					</tr>
					<tr>
					<td><input class="cdays" name="days[]" id="Friday" value="Friday" type="checkbox"> Fri</td>
					  <td><input name="Friday_form" id="Friday_form" class="form_time timepicker" type="text"></td>
					  <td><input name="Friday_to" id="Friday_to" class="to_time timepicker" type="text"></td>
					</tr>
					<tr>
					 <td><input class="cdays" name="days[]" id="Saturday" value="Saturday" type="checkbox"> Sat</td>
					  <td><input name="Saturday_form" id="Saturday_form" class="form_time timepicker" type="text"></td>
					  <td><input name="Saturday_to" id="Saturday_to" class="to_time timepicker" type="text"></td>
					</tr> 
					<tr>
					 <td><input class="cdays" name="days[]" id="Sunday" value="Sunday" type="checkbox"> Sun</td>
					  <td><input name="Sunday_form" id="Sunday_form" class="form_time timepicker" type="text"></td>
					  <td><input name="Sunday_to" id="Sunday_to" class="to_time timepicker" type="text"></td>
					</tr>
					</tbody>
					</table>
					
					</div>';
		$data .= '</div> <!-- Coach Name -->';
		
		
			//////=============================================Start : Coach Address======================================
			
		$sql_get_add =" SELECT * FROM tbl_address_master WHERE add_user_id='".$id."' ";
		$res_get_add = mysqli_query($db_con,$sql_get_add) or die(mysqli_error($db_con));
		$row_get_add = mysqli_fetch_array($res_get_add);
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Address<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea  id="address" name="address" class="input-large" placeholder="Address" data-rule-required="true" ';
		
		$data .= '>';
		if($id != "" && $req_type == "edit")
		{
			$data .= $row_get_add['add_details']; 
		}
		elseif($id != "" && $req_type == "view")
		{
			$data .= $row_get_add['add_details']; 
		}
		$data .='</textarea><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Coach Address -->';
	
		//////=============================================Start : Country======================================
		/*
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Country<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		
			$data .= '<select onchange="getState(this.value)" name="country_code" id="country_code" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select State</option>';
			$sql   =' SELECT * FROM tbl_country WHERE status =1';
			$res   =  mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($res))
			{
				$data .='<option value="'.$row['country_id'].'" ';
				if($req_type !='add')
				{
					if($row['country_id']==$row_area_data['area_country'])
					{
						$data .=' selected ';
					}
				}
				$data .='>'.$row['country_name'].'</option> ';
			}
			$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- Country -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#country_code").select2();';
		$data .= '</script>';*/
		
		//////=============================================Start : State======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">State<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
			$data .= '<select name="state_code" onchange="getCityList(this.value,\'city\')" id="state_code" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select State</option>';
			$sql   =" SELECT * FROM tbl_state WHERE status =1 AND country_id='IN' ";
			if($req_type !='add')
			{
				//$sql .=" AND country_id ='".$row_get_add['area_country']."' ";
			}
			$res   =  mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($res))
			{
				$data .='<option value="'.$row['state'].'" ';
				if($req_type !='add')
				{
					if($row['state']==$row_get_add['add_state'])
					{
						$data .=' selected ';
					}
				}
				$data .='>'.$row['state_name'].'</option> ';
			}
			
			$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- Country -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#state_code").select2();';
		$data .= '</script>';
			
		
		//////=============================================Start : City======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">City<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
			$data .= '<select onchange="getArea(this.value,\'area\')" name="city"  id="city" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select State</option>';
			$sql   =' SELECT * FROM tbl_city WHERE status =1';
			if($req_type !='add')
			{
				$sql .=" AND state_id ='".$row_get_add['add_state']."' ";
			}
			$res   =  mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($res))
			{
				$data .='<option value="'.$row['city_id'].'" ';
				if($req_type !='add')
				{
					if($row['city_id']==$row_get_add['add_city'])
					{
						$data .=' selected ';
					}
				}
				$data .='>'.$row['city_name'].'</option> ';
			}
			
			$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- Country -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#city").select2();';
		$data .= '</script>';
		
		
		//////=============================================Start : Area======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Area<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
			$data .= '<select name="area"  id="area" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select Area</option>';
			$sql   =' SELECT * FROM tbl_area WHERE 1=1';
			if($req_type !='add')
			{
				$sql .=" AND area_city ='".$row_get_add['add_city']."' ";
			}
			$res   =  mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($res))
			{
				$data .='<option value="'.$row['area_id'].'" ';
				if($req_type !='add')
				{
					if($row['area_id']== $row_get_add['add_area'])
					{
						$data .=' selected ';
					}
				}
				$data .='>'.ucwords($row['area_name']).'</option> ';
			}
			
			$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- Country -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#area").select2();';
		$data .= '</script>';
	
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Pin Code<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="area_pincode" placeholder="Pincode" name="area_pincode" maxlength="6" minlength="6" onKeyPress="return isNumberKey(event)" class="input-large keyup-char" data-rule-required="true" ';
		if($id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_get_add['add_pincode'].'"'; 
		}
		elseif($id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_get_add['add_pincode'].'" disabled'; 				
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Pincode -->';

         	

		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		if($id != "" && $req_type == "view")
		{
			if($row_get_add['add_status'] == 1)
			{
				$data .= ' <label class="control-label" style="color:#30DD00"> Active </label>';
			}
			if($row_get_add['add_status'] == 0)
			{
				$data .= ' <label class="control-label" style="color:#E63A3A"> Inactive </label>';
			}
		}
		else
		{  
          if($id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="area_status" value="1" class="css-radio" data-rule-required="true" ';
				$dis	= checkFunctionalityRight("view_coach.php",3);
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['add_status'] == 1)
				{
					$data .= 'checked ';
				}
				$data .= '> Active ';
				$data .= '<input type="radio" name="area_status" value="0" class="css-radio" data-rule-required="true"';
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['add_status'] == 0  )
				{
					$data .= 'checked ';
				}
				$data .= '> Inactive';
			} 
			else  
			{
				$data .= ' <input type="radio" name="area_status" value="1" class="css-radio" data-rule-required="true" ';
				$data .= '> Active ';
				$data .= ' <input type="radio" name="area_status" value="0" class="css-radio" data-rule-required="true"';
			
		 		$data .= '> Inactive ';
			}
		}
					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		
		$data .= '<div class="form-actions">';
		if($id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Coach</button>';			
		}
		elseif($id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Coach</button>';			
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
	$id				= $obj->id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$data['status']      = $curr_status;
	$data['modified_by'] = $logged_uid;
	$data['modified']    = $datetime;
	$res = update('tbl_cadmin_users',$data,array('id'=>$id));
	
	if($res)
	{
		$adata['add_status']      = $curr_status;
		$adata['add_modified_by'] = $logged_uid;
		$adata['add_modified']    = $datetime;
		$res = update('tbl_address_master',$adata,array('add_user_id'=>$id));
		if($res)
		{
			quit('Success',1);
		}
		else
		{
			quit('Please try lettre...!');
		}
	}
	else
	{
		quit('Please try lettre...!');
	}
}

//------------------This is used for delete data---------------------------------
if((isset($obj->delete_area)) == "1" && isset($obj->delete_area))
{
	$response_array   = array();		
	$ids 	  = $obj->batch;
	$del_flag 		  = 0; 
	foreach($ids as $id)	
	{
		$sql_delete_area	= " DELETE FROM `tbl_cadmin_users` WHERE `id` = '".$id."' ";
		$result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));			
		if($result_delete_area)
		{
			$del_flag = 1;
			$sql_delete_area	= " DELETE FROM `tbl_address_master` WHERE `add_user_id` = '".$id."' ";
		    $result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));	
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
// =====================Get City===================================================
if((isset($obj->getState)) == "1" && isset($obj->getState))
{
	$response_array   = array();		
	$state_id 	  = $obj->state_id;
	
	if($state_id=="")
	{
		quit('Please Select Country...!');
	}
	
	$data ='<option value="">Select City</option>';
	
	$sql =" SELECT * FROM tbl_city WHERE status =1 AND state_id='".$state_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
	}
	else
	{
		while($row = mysqli_fetch_array($res))
		{
			$data .='<option value="'.$row['city_id'].'">'.$row['city_name'].'</option>';
		}
		quit($data,1);
	}
	
}

if((isset($obj->getCity)) == "1" && isset($obj->getCity))
{
	$response_array   = array();		
	$state_id 	      = $obj->state_id;
	
	if($state_id=="")
	{
		quit('Please Select State...!');
	}
	
	$data ='<option value="">Select City</option>';
	
	$sql =" SELECT * FROM tbl_city WHERE status =1 AND state_id='".$state_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
	}
	else
	{
		while($row = mysqli_fetch_array($res))
		{
			$data .='<option value="'.$row['city_id'].'">'.$row['city_name'].'</option>';
		}
		quit($data,1);
	}
}

if((isset($obj->getArea)) == "1" && isset($obj->getArea))
{
	$response_array   = array();		
	$city_id 	      = $obj->city_id;
	
	if($city_id=="")
	{
		quit('Please Select State...!');
	}
	
	$data ='<option value="">Select City</option>';
	
	$sql =" SELECT * FROM tbl_area WHERE area_status =1 AND area_city='".$city_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
		quit('city not found...!');
	}
	else
	{
		while($row = mysqli_fetch_array($res))
		{
			$data .='<option value="'.$row['area_id'].'">'.ucwords($row['area_name']).'</option>';
		}
		quit($data,1);
	}
}
?>
