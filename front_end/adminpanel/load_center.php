<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


//------------------this is used for inserting records---------------------
if((isset($_POST['insert_center'])) == "1" && isset($_POST['insert_center']))
{
	
	$data['center_name']      = mysqli_real_escape_string($db_con,$_POST['center_name']);
	$center_types             = $_POST['type'];
	$data['center_status']    = mysqli_real_escape_string($db_con,$_POST['center_status']);
	
	
	$data['center_created']   = $datetime;
	$data['center_created_by'] = $logged_uid;
	
	if($data['center_name']=="" || count($center_types)==0 || $data['center_status']=="")
	{
		quit('All fileds are required...!');
	}
	
	$sql_check =" SELECT * FROM tbl_centers WHERE (center_name ='".$data['center_name']."') ";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		$insert_id = insert('tbl_centers',$data);
		
		$cdata['center_id']  =$insert_id;
		$cdata['type_status'] = $data['center_status'];
		$cdata['type_created']  =$datetime;
		$cdata['type_created_by'] = $uid;
		
		foreach($center_types as $type_id)
		{
			$cdata['type_id']     = $type_id;
			$insert_id = insert('tbl_center_types',$cdata);
		}
		
		quit('Center Added Successfully...!',1);
	}
	else
	{
		quit('Center Name already Exist...!');
	}
}

//------------------this is used for update records---------------------
if((isset($_POST['update_center'])) == "1" && isset($_POST['update_center']))
{	
    $data['center_name']      = mysqli_real_escape_string($db_con,$_POST['center_name']);
	$data['center_types']             = mysqli_real_escape_string($db_con,implode(', ',$_POST['type']));
	$data['center_status']    = mysqli_real_escape_string($db_con,$_POST['center_status']);
	
	$center_id                = mysqli_real_escape_string($db_con,$_POST['center_id']);

	$data['center_modified']   = $datetime;
	$data['center_modified_by'] = $logged_uid;
		
	if($data['center_name']=="" || $data['center_types']=="" || $data['center_status']=="")
	{
		quit('All fileds are required...!');
	}
	
	$sql_check =" SELECT * FROM tbl_centers WHERE center_name ='".$data['center_name']."' AND center_id !='".$center_id."'";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		update('tbl_centers',$data,array('center_id'=>$center_id));
		quit('Center Updated Successfully...!',1);
	}
	else
	{
		quit('Center already Exist...!'.$sql_check);
	}

}

//----------------------This is used for viewing records---------------------------------
if((isset($obj->load_center)) == "1" && isset($obj->load_center))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT * FROM `tbl_centers` AS tc WHERE 1=1";
		
		if($search_text != "")
		{
			$sql_load_data .= " and (center_id like '%".$search_text."%' or center_name like '%".$search_text."%' ";
			$sql_load_data .= " or center_created like '%".$search_text."%' or center_types like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY center_name  ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">ID</th>';
			$area_data .= '<th style="text-align:center">Center Name</th>';
			$area_data .= '<th style="text-align:center">Type</th>';
			$area_data .= '<th style="text-align:center">Schedule</th>';
			$dis = checkFunctionalityRight("view_center.php",3);
		
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_center.php",1);
			
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_center.php",2);
			
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
				$area_data .= '<td style="text-align:center">'.$row_load_data['center_id'].'</td>';
				
				$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['center_name']).'" class="btn-link" id="'.$row_load_data['center_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				
				$sq_get_type	 = "SELECT * FROM tbl_batch_types WHERE id IN(SELECT DISTINCT(type_id) FROM tbl_center_types WHERE center_id='".$row_load_data['center_id']."')";
				$res_get_type    = mysqli_query($db_con,$sq_get_type) or die(mysqli_error($db_con));
				$center_arr      = array();
				while($center_row = mysqli_fetch_array($res_get_type))
				{
					array_push($center_arr,ucwords($center_row['type']));
				}
				
				
				$area_data .= '<td style="text-align:center">'.implode(', ',$center_arr).'</td>';
				$area_data .= '<td style="text-align:center">';					
				$area_data .= '<input type="button" value="Schedule" id="'.$row_load_data['center_id'].'" class="btn-warning" onclick="viewSchedule(this.id);">';
				
				$area_data .= '</td>';
				$dis = checkFunctionalityRight("view_center.php",3);
				
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['center_status']!='0')
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['center_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['center_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_center.php",1);
			
				if($edit)
				{				
					$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Edit" id="'.$row_load_data['center_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_center.php",2);
				
				if($delete)
				{			
				
					if($row_load_data['center_status']==0)
					{
						$area_data .= '<td style="text-align:center">';					
					    $area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['center_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					    $area_data .= '</td>';
					}
					else
					{
						$area_data .= '<td><div class="controls" align="center">';
						$area_data .= '<input type="checkbox" value="'.$row_load_data['center_id'].'" id="batch'.$row_load_data['center_id'].'" name="batch'.$row_load_data['center_id'].'" class="css-checkbox batch">';
						$area_data .= '<label for="batch'.$row_load_data['center_id'].'" class="css-label"></label>';
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

if((isset($obj->load_center_parts)) == "1" && isset($obj->load_center_parts))
{
	$cat_id     = $obj->area_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($cat_id != "" && $req_type == "edit")
		{
			$sql_area_data 	    = "Select * from tbl_centers where center_id = '".$cat_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}	
		else if($cat_id != "" && $req_type == "view")
		{
			$sql_area_data 	    = "Select * from tbl_centers where center_id = '".$cat_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}			
		$data = '';
		if($cat_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="center_id" name="center_id" value="'.$row_area_data['center_id'].'">';
			$data .= '<input type="hidden" name="update_center" id="update_center" value="1">';
		}  
		
		
		if($req_type == "add")
		{
			$data .= '<input type="hidden" name="insert_center" id="insert_center" value="1">';
		} 
		 
		//====================Start : Category Name =================================================                                                      		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Center Name
		<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return charsonly(event);" type="text" id="center_name" name="center_name" ';
		$data .= 'class="input-large keyup-char" data-rule-required="true" placeholder="Center Name"  value="'.@$row_area_data['center_name'].'"/><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Category Name -->';
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Type
		<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		
		$result = getRecord('tbl_batch_types',array('status'=>1));
		$type_ar = array();
		
		
		$sq_get_type	 = "SELECT * FROM tbl_batch_types WHERE id IN(SELECT DISTINCT(type_id) FROM tbl_center_types WHERE center_id='".$cat_id."')";
		$res_get_type    = mysqli_query($db_con,$sq_get_type) or die(mysqli_error($db_con));
		$type_ar       = array();
		while($center_row = mysqli_fetch_array($res_get_type))
		{
			array_push($type_ar,ucwords($center_row['id']));
		}
		
		while($row = mysqli_fetch_array($result))
		{
			$data .= ' <input  type="checkbox"  id="'.@$row['id'].'" name="type[]" ';
		    $data .= 'class="input-large keyup-char" data-rule-required="true"  ';
			
			if(in_array($row['id'],$type_ar))
			{
				$data .=' checked ';
			}
			$data .='  value="'.@$row['id'].'"/> &nbsp; '.@ucwords($row['type']).'&nbsp;&nbsp;';
		}
		
		
		$data .= '<br></div>';
		$data .= '</div> <!-- Category Fee -->';
		
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		if($cat_id != "" && $req_type == "view")
		{
			if($row_area_data['center_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_area_data['center_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}
		else
		{  
          if($cat_id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="center_status" value="1" class="css-radio" data-rule-required="true" ';
				$dis	= checkFunctionalityRight("view_center.php",3);
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['center_status'] == 1)
				{
					$data .= 'checked ';
				}
				$data .= '> Active ';
				$data .= '<input type="radio" name="center_status" value="0" class="css-radio" data-rule-required="true"';
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['center_status'] == 0  )
				{
					$data .= 'checked ';
				}
				$data .= '> Inactive ';
			} 
			else  
			{
				$data .= '<input type="radio" name="center_status" value="1" class="css-radio" data-rule-required="true" ';
				$data .= '> Active ';
				$data .= ' <input type="radio" name="center_status" value="0" class="css-radio" data-rule-required="true"';
			
		 		$data .= '> Inactive';
			}
		}					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		
		$data .= '<div class="form-actions">';
		if($cat_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Center</button>';			
		}
		elseif($cat_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Center</button>';			
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
	$cat_id				 = $obj->cat_id;
	$curr_status		 = $obj->curr_status;
	$response_array 	 = array();	
	
	$sql_update_status 		= " UPDATE `tbl_centers` SET `center_status`= '".$curr_status."' WHERE `center_id`='".$cat_id."' ";
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

//---------------This is used for status change--------------------------------
if((isset($obj->delete_category)) == "1" && isset($obj->delete_category))
{
	$center_ids				 = $obj->batch;
	$curr_status		 = $obj->curr_status;
	$response_array 	 = array();	
	
	foreach($center_ids as $center_id)
	{
		$sql_update_status 		= " UPDATE `tbl_centers` SET `center_status`= '0' WHERE `center_id`='".$center_id."' ";
		$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	}
	
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


if((isset($obj->getSchedule)) == "1" && isset($obj->getSchedule))
{
	$center_id = $obj->center_id;
    $add = checkFunctionalityRight('view_center.php',0);
	$data  ='';
	if($add)
	{
		$data ='<button type="button" class="btn-info" onClick="addMoreSchedule(\'add\','.$center_id.',\'\')" ><i class="icon-plus"></i>&nbspAdd Schedule</button>';
		
    }
                                                                          
    $data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
	$data .= '<thead>';
	$data .= '<tr>';
	$data .= '<th style="text-align:center">Sr No.</th>';
	$data .= '<th style="text-align:center">Type</th>';
	$data .= '<th style="text-align:center">Days</th>';
	
	$edit = checkFunctionalityRight("view_center.php",1);
	
	if($edit)
	{			
		$data .= '<th style="text-align:center">Edit</th>';			
	}
	$delete = checkFunctionalityRight("view_center.php",2);
	
	if($delete)
	{			
		$data .= '<th style="text-align:center">Status</th>';
	}
	$data .= '</tr>';
	$data .= '</thead>';
	$data .= '<tbody>';
	
	
	
	$sql_get_type	 = " SELECT * FROM tbl_batch_types WHERE id ";
	$sql_get_type	.= " IN(SELECT DISTINCT(center_type) FROM tbl_center_schedule WHERE center_id='".$center_id."')";
	$res_get_type    = mysqli_query($db_con,$sql_get_type) or die(mysqli_error($db_con));
	
	while($row_load_data = mysqli_fetch_array($res_get_type))
	{
		$data .= '<tr>';				
		$data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
		$data .= '<td style="text-align:center">'.ucwords($row_load_data['type']).'</td>';
		
		$data .= '<td style="text-align:center">';
		
		$res_days = getRecord('tbl_center_schedule',$where=array('center_id'=>$center_id,"center_type"=>$row_load_data['id']));
		$days     = array();
	    while($row_days = mysqli_fetch_array($res_days))
		{
			array_push($days,ucwords(substr($row_days['day'],0,3)));  
			$status  = $row_days['status'];
		}
		 
		 $data .=implode(', ',$days);
		$data .='</td>';
		$edit = checkFunctionalityRight("view_center.php",1);
	
		if($edit)
		{				
			$data .= '<td>';
			$data .= '<div class="controls" align="center">
			<input type="button" value="Edit" id="'.$row_load_data['id'].'" class="btn-warning" onclick="addMoreSchedule(\'edit\','.$center_id.','.$row_load_data['id'].');">
			</div>
			</td>';												
		}
		
		$delete = checkFunctionalityRight("view_center.php",2);
		
		if($delete)
		{			
		
			$data .= '<td><div class="controls" align="center">';
			if($status==1)
			{
				$data .= '<input type="button" value="Active" name="batch'.$row_load_data['id'].'" class="btn-success" onclick="changeTypeStatus(\''.$row_load_data['id'].'\',\''.$center_id.'\',0);" />';
				
			}else
			{
				$data .= '<input type="button" value="Inactive" id="batch'.$row_load_data['id'].'" name="batch'.$row_load_data['id'].'" class="btn-danger" onclick="changeTypeStatus(\''.$row_load_data['id'].'\',\''.$center_id.'\',1)" />';
			}
			
			$data .= '</div></td>';	
			
												
		}
		$data .= '</tr>';															
	}	
	$data .= '</tbody>';
	$data .= '</table>';	
										
										
										
	quit($data,1);
}


if((isset($obj->load_schedule_parts)) == "1" && isset($obj->load_schedule_parts))
{
	$center_id     = $obj->center_id;
	$req_type       = $obj->req_type;
	$type_id       = $obj->type_id;
	$response_array = array();
	
	if($req_type != "")
	{
		$data  ='<input type="hidden" name="center_id" value="'.$center_id.'"/>';
		
		
		
		if($req_type=='edit')
		{
			$data  .='<input type="hidden" name="type_id" value="'.$type_id.'"/>';
			$data  .='<input type="hidden" name="edit_schedule" value="1"/>';
		}
		
		if($req_type !="add")
		{
			 $row = checkExist('tbl_centers',array('center_id'=>$center_id));
		}
		
		//====================Start : Type Name =================================================  
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select Type<sup class="validfield">
		<span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type=='add')
		{
			$data  .='<input type="hidden" name="add_schedule" value="1"/>';
			$data .= '<select name="batch_type"  id="batch_type" class="select2-me input-large "';
			$data.=' data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select Type</option>';
			
			
			
			
			$sq_get_type	 = "SELECT * FROM tbl_batch_types WHERE id NOT  IN(SELECT DISTINCT(center_type) FROM tbl_center_schedule WHERE center_id='".$center_id."')";
			$res_get_type    = mysqli_query($db_con,$sq_get_type) or die(mysqli_error($db_con));
			$center_arr      = array();
			while($center_row = mysqli_fetch_array($res_get_type))
			{
				$data .=' <option value="'.$center_row['id'].'" ';
				if($req_type !='add')
				{
					/*if($center_row['id']==)
					{
					}*/
					
				}
				$data .=' >'.ucwords($center_row['type']).'</option>';
			}
	
			
			
			$data .= '</select>';
		}
		else
		{
			$sq_get_type	 = "SELECT * FROM tbl_batch_types WHERE id ='".$type_id."'";
			$res_get_type    = mysqli_query($db_con,$sq_get_type) or die(mysqli_error($db_con));
			$row_get_type    = mysqli_fetch_array($res_get_type);
			$data .=ucwords($row_get_type['type']);
		}
		$data .= '</div>';
		$data .= '</div> <!-- Country -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#batch_type").select2();';
		$data .= '</script>';
		
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
					  <th>Time</th>
				
					</tr></thead>  
					
					<tbody>
					<tr>
					<td><input class="cdays" name="days[]" id="Monday" value="Monday" type="checkbox"> Mon</td>
					  <td><input name="Monday_form" id="Monday_from" placeholder="Time" class="form_time timepicker" type="text"></td>
					</tr>
					<tr>
					  <td><input class="cdays" name="days[]" id="Tuesday" value="Tuesday" type="checkbox"> Tue</td>
					  <td><input name="Tuesday_form" placeholder="Time"  id="Tuesday_from" class="form_time timepicker" type="text"></td>
					 
					</tr>
					<tr>
					 <td><input class="cdays" name="days[]" id="Wednesday" value="Wednesday" type="checkbox"> Wed</td>
					  <td><input name="Wednesday_form" placeholder="Time"  id="Wednesday_from" class="form_time timepicker" type="text"></td>
					 
					</tr>
					<tr>
					<td><input class="cdays" name="days[]" id="Thursday" value="Thursday" type="checkbox"> Thu</td>
					  <td><input name="Thursday_form" placeholder="Time"  id="Thursday_from" class="form_time timepicker" type="text"></td>
					 
					</tr>
					<tr>
					<td><input class="cdays" name="days[]" id="Friday" value="Friday" type="checkbox"> Fri</td>
					  <td><input name="Friday_form" placeholder="Time"  id="Friday_from" class="form_time timepicker" type="text"></td>
					
					</tr>
					<tr>
					 <td><input class="cdays" name="days[]" id="Saturday" value="Saturday" type="checkbox"> Sat</td>
					  <td><input name="Saturday_form" placeholder="Time"  id="Saturday_from" class="form_time bootstrap-timepicker timepicker timepicker1" type="text"></td>
					 
					</tr> 
					<tr>
					 <td><input class="cdays" name="days[]" id="Sunday" value="Sunday" type="checkbox"> Sun</td>
					  <td><input name="Sunday_form" placeholder="Time"  id="Sunday_from" class="form_time timepicker" type="text"></td>
					  
					</tr>
					</tbody>
					</table>
					
					</div>';
		$data .= '</div> <!-- Coach Name -->';
		
		if($req_type != "add")
		{
			$data .='<script type="application/javascript">';
			$sql_get_time = " SELECT * FROM tbl_center_schedule WHERE center_type='".$type_id."' AND center_id='".$center_id."'";
			$res_get_time = mysqli_query($db_con,$sql_get_time) or die(mysqli_error($db_con));
		    while($row_get_time = mysqli_fetch_array($res_get_time))
			{
				$data .="$('#".$row_get_time['day']."_from').val('".$row_get_time['time']."');";
				
				$data .="$('#".$row_get_time['day']."').prop('checked',true);";
			}
			
			$data .='</script>';
		}
		$data .='<script type="application/javascript">';
		
		$data .="
		
		
$('.timepicker').timepicker({
		        showInputs: false,
		        showMeridian : true,
		        defaultTime : false,
		        showSeconds: false
		    }); ";
			
			$data .='$(".timepicker").click(function(){
                  $(".bootstrap-timepicker-hour").html("'.date('h').'");
	              $(".bootstrap-timepicker-minute").html("'.date('i').'");
	              $(".bootstrap-timepicker-meridian").html("'.date('A').'");
			}); ';
			
			$data .='</script>';
	    //////=============================================Start : Duration======================================
		
		
		
		
		$data .= '<div class="form-actions">';
		if($type_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Schedule</button>';			
		}
		elseif($type_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Schedule</button>';			
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



if(isset($_POST['add_schedule']) && $_POST['add_schedule']==1)
{
	$data['center_id']   = mysqli_real_escape_string($db_con,$_POST['center_id']);
	$data['center_type'] = mysqli_real_escape_string($db_con,$_POST['batch_type']);
	$days  = $_POST['days'];
	foreach($days as $day)
	{
		$data['day']  			= $day;
		$data['time'] 			= mysqli_real_escape_string($db_con,$_POST[$day.'_form']);
		$data['created_date']   = $datetime;
		$data['created_by'] 	= $logged_uid;
		insert('tbl_center_schedule',$data);
	}
	quit($data['center_id'],1);
}

if(isset($_POST['edit_schedule']) && $_POST['edit_schedule']==1)
{
	$data['center_id']   = mysqli_real_escape_string($db_con,$_POST['center_id']);
	$data['center_type']     = mysqli_real_escape_string($db_con,$_POST['type_id']);
	
	$sql_delete	= " DELETE FROM `tbl_center_schedule` WHERE `center_id` = '".$data['center_id']."' AND center_type='".$data['center_type']."' ";
	$res_delete	= mysqli_query($db_con,$sql_delete) or die(mysqli_error($db_con));			
	
	$days  = $_POST['days'];
	foreach($days as $day)
	{
		$data['day']  			= $day;
		$data['time'] 			= mysqli_real_escape_string($db_con,$_POST[$day.'_form']);
		$data['created_date']   = $datetime;
		$data['created_by'] 	= $logged_uid;
		insert('tbl_center_schedule',$data);
	}
	quit($data['center_id'],1);
}


//---------------This is used for status change--------------------------------
if((isset($obj->change_type_status)) == "1" && isset($obj->change_type_status))
{
	$type_id		= $obj->type_id;
	$status		    = $obj->status;
	$center_id 	 	= $obj->center_id;
	
	$data['status'] = $status;
	$data['modified_by'] = $uid;
	$data['modified_date'] = $datetime; 
	$result         = update('tbl_center_schedule',$data,array("center_id"=>$center_id,"center_type"=>$type_id));
	if($result)
	{				
		quit("Status Updated Successfully.",1);
	}
	else
	{
		quit("Status Update Failed.");
	}											

}
?>
