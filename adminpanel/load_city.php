<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


//------------------this is used for inserting records---------------------
if((isset($_POST['insert_city'])) == "1" && isset($_POST['insert_city']))
{
	
	$data['city_name']      = mysqli_real_escape_string($db_con,$_POST['city_name']);
	$data['state_id']     = mysqli_real_escape_string($db_con,$_POST['state_code']);
	$data['country_id']   = mysqli_real_escape_string($db_con,$_POST['country_code']);
	$data['status'] = mysqli_real_escape_string($db_con,$_POST['country_status']);
	
	$data['created_date']  = $datetime;
	$data['created_by']    = $logged_uid;
	
	if($data['city_name']=="" || $data['state_id']=="" || $data['country_id']=="")
	{
		quit('All fileds are required...!');
	}
	
	$sql_check =" SELECT * FROM tbl_state WHERE (state ='".$data['state']."') ";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		insert('tbl_city',$data);
		quit('City Added Successfully...!',1);
	}
	else
	{
		quit('City Name already Exist...!');
	}
}

//------------------this is used for update records---------------------
if((isset($_POST['update_city'])) == "1" && isset($_POST['update_city']))
{	
    $data['city_name']      = mysqli_real_escape_string($db_con,$_POST['city_name']);
	$data['state_id']     = mysqli_real_escape_string($db_con,$_POST['state_code']);
	$data['country_id']   = mysqli_real_escape_string($db_con,$_POST['country_code']);
	$data['status'] = mysqli_real_escape_string($db_con,$_POST['country_status']);
	
	$city_id              = mysqli_real_escape_string($db_con,$_POST['city_id']);

	$data['modified_date']  = $datetime;
	$data['modified_by']    = $logged_uid;
		
	if($data['city_name']=="" || $data['state_id']=="" || $data['country_id']=="")
	{
		quit('All fileds are required...!');
	}
	
	$sql_check =" SELECT * FROM tbl_state WHERE state_name ='".$data['state_name']."' ";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		update('tbl_city',$data,array('city_id'=>$city_id));
		quit('City Updated Successfully...!',1);
	}
	else
	{
		quit('City Name already Exist...!');
	}

}

//----------------------This is used for viewing records---------------------------------
if((isset($obj->load_country)) == "1" && isset($obj->load_country))
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
			
		$sql_load_data  = " SELECT * FROM `tbl_city` AS tc WHERE 1=1";
		
		if($search_text != "")
		{
			$sql_load_data .= " and (state_id like '%".$search_text."%' or country_id like '%".$search_text."%' ";
			$sql_load_data .= " or city_name like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY city_name  ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Country ID</th>';
			$area_data .= '<th style="text-align:center">State ID</th>';
			$area_data .= '<th style="text-align:center">City  Name</th>';
			$dis = checkFunctionalityRight("view_city.php",3);
		
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_city.php",1);
			
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_city.php",2);
			
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
				$area_data .= '<td style="text-align:center">'.$row_load_data['country_id'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['state_id'].'</td>';
				$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['city_name']).'" class="btn-link" id="'.$row_load_data['city_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				
				
				$dis = checkFunctionalityRight("view_city.php",3);
				
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['status']!='0')
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['city_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['city_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_city.php",1);
			
				if($edit)
				{				
					$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Edit" id="'.$row_load_data['city_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_city.php",2);
				
				if($delete)
				{			
				
					if($row_load_data['status']==0)
					{
						$area_data .= '<td style="text-align:center">';					
					    $area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['city_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					    $area_data .= '</td>';
					}
					else
					{
						$area_data .= '<td><div class="controls" align="center">';
						$area_data .= '<input type="checkbox" value="'.$row_load_data['city_id'].'" id="batch'.$row_load_data['city_id'].'" name="batch'.$row_load_data['city_id'].'" class="css-checkbox batch">';
						$area_data .= '<label for="batch'.$row_load_data['city_id'].'" class="css-label"></label>';
						$area_data .= '</div></td>';	
					}
														
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
	$country_id        = $obj->area_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($country_id != "" && $req_type == "edit")
		{
			$sql_area_data 	    = "Select * from tbl_city where city_id = '".$country_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}	
		else if($country_id != "" && $req_type == "view")
		{
			$sql_area_data 	    = "Select * from tbl_city where city_id = '".$country_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}			
		$data = '';
		if($country_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="city_id" name="city_id" value="'.$row_area_data['city_id'].'">';
			$data .= '<input type="hidden" name="update_city" id="update_city" value="1">';
		}  
		
		
		if($req_type == "add")
		{
			$data .= '<input type="hidden" name="insert_city" id="insert_city" value="1">';
		} 
		                                                       		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">City Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return charsonly(event);" type="text" id="city_name" name="city_name" class="input-large keyup-char" data-rule-required="true" placeholder="City Name" ';
		if($country_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_area_data['city_name'].'"'; 
		}
		elseif($country_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_area_data['city_name'].'" disabled'; 				
		}
		$data .= '/><br>';
		
						
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		
		$data .= '</div>';
		$data .= '</div> <!-- country_name Name -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Country<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		
			$data .= '<select onchange="getState(this.value)" name="country_code" id="country_code" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select Country</option>';
			
				$sql   =' SELECT * FROM tbl_country WHERE status =1';
				$res   =  mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
				while($row = mysqli_fetch_array($res))
				{
					$data .='<option value="'.$row['country_id'].'" ';
					if($req_type !='add')
					{
						if($row['country_id']==$row_area_data['country_id'])
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
		$data .= '</script>';
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">State<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select name="state_code" id="state_code" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .= '<option value="">Select State</option>';
		if($req_type !='add')
		{
			$sql   =' SELECT * FROM tbl_state WHERE status =1';
			if($req_type !='add')
			{
				$sql .=" AND country_id ='".$row_area_data['country_id']."' ";
			}
			$res   =  mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($res))
			{
				$data .='<option value="'.$row['state'].'" ';
				if($req_type !='add')
				{
					if($row['state']==$row_area_data['state_id'])
					{
						$data .=' selected ';
					}
				}
				$data .='>'.$row['state_name'].'</option> ';
			}
		}
			$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- Country -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#state_code").select2();';
		$data .= '</script>';

		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		if($country_id != "" && $req_type == "view")
		{
			if($row_area_data['status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_area_data['status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}
		else
		{  
          if($country_id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="status" value="1" class="css-radio" data-rule-required="true" ';
				$dis	= checkFunctionalityRight("view_city.php",3);
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['status'] == 1)
				{
					$data .= 'checked ';
				}
				$data .= '>Active';
				$data .= '<input type="radio" name="status" value="0" class="css-radio" data-rule-required="true"';
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['status'] == 0  )
				{
					$data .= 'checked ';
				}
				$data .= '>Inactive';
			} 
			else  
			{
				$data .= '<input type="radio" name="country_status" value="1" class="css-radio" data-rule-required="true" ';
				$data .= '> Active ';
				$data .= ' <input type="radio" name="country_status" value="0" class="css-radio" data-rule-required="true"';
			
		 		$data .= '> Inactive';
			}
		}					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		
		$data .= '<div class="form-actions">';
		if($country_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add City</button>';			
		}
		elseif($country_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update City</button>';			
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
	
	$sql_update_status 		= " UPDATE `tbl_city` SET `status`= '".$curr_status."' WHERE `city_id`='".$area_id."' ";
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
	{/*
		$sql_delete_area	= " DELETE FROM `tbl_city` WHERE `city_id` = '".$area_id."' ";
		$result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));			*/
		$sql_update_status 		= " UPDATE `tbl_city` SET `status`= '0' WHERE `city_id`='".$area_id."' ";
	    $result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	
		if($result_update_status)
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
if((isset($obj->getState)) == "1" && isset($obj->getState))
{
	$response_array   = array();		
	$country_id 	  = $obj->country_id;
	
	if($country_id=="")
	{
		quit('Please Select Country...!');
	}
	
	$data ='<option value="">Select State</option>';
	
	$sql =" SELECT * FROM tbl_state WHERE status =1 AND country_id='".$country_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
	}
	else
	{
		while($row = mysqli_fetch_array($res))
		{
			$data .='<option value="'.$row['state'].'">'.$row['state_name'].'</option>';
		}
		quit($data,1);
	}
	
}
?>
