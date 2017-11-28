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
if((isset($_POST['insert_batch'])) == "1" && isset($_POST['insert_batch']))
{
	$data['batch_name']     = mysqli_real_escape_string($db_con,$_POST['batch_name']);
	$data['batch_limit']    = mysqli_real_escape_string($db_con,$_POST['batch_limit']);
	$data['start_date']     = mysqli_real_escape_string($db_con,$_POST['start_date']);
	$data['end_date']       = mysqli_real_escape_string($db_con,$_POST['end_date']);
	$data['batch_status']   = mysqli_real_escape_string($db_con,$_POST['batch_status']);
	
	$data['batch_created_by']    = $logged_uid;
	$data['batch_created']       = $datetime;
	
	$start_date                    = mysqli_real_escape_string($db_con,$_POST['start_date']);
	$start_date                    = explode('-',$start_date);// d/m/y
	$data['start_date']            = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];
	
	$end_date                    = mysqli_real_escape_string($db_con,$_POST['end_date']);
	$end_date                    = explode('-',$end_date);// d/m/y
	$data['end_date']            = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];
	
	$sql_check             = " SELECT * FROM tbl_batches  as tb WHERE batch_name='".$data['batch_name']."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = insert('tbl_batches',$data); 
		    
			if($insert_id)
			{
				$days  = $_POST['days'];
				foreach($days as $day)
				{
					$tdata['batch_id']   = $insert_id;
					$tdata['batch_day']  = $day;
					$tdata['batch_time'] = mysqli_real_escape_string($db_con,$_POST[$day.'_form']);
					$tdata['created']    = $datetime;
					$tdata['created_by'] = $logged_uid;
					insert('tbl_batch_time',$tdata);
				}
				quit('Batch Added Successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
	}
	else
	{
		quit('Batch Name already Exist...!');
	}	
}


if((isset($_POST['update_batch'])) == "1" && isset($_POST['update_batch']))
{
	$data['batch_name']          = mysqli_real_escape_string($db_con,$_POST['batch_name']);
	$data['batch_limit']         = mysqli_real_escape_string($db_con,$_POST['batch_limit']);
	$data['start_date']          = mysqli_real_escape_string($db_con,$_POST['start_date']);
	$data['end_date']            = mysqli_real_escape_string($db_con,$_POST['end_date']);
	$data['batch_status']        = mysqli_real_escape_string($db_con,$_POST['batch_status']);
	$batch_id                    = mysqli_real_escape_string($db_con,$_POST['batch_id']);
	
	$start_date                    = mysqli_real_escape_string($db_con,$_POST['start_date']);
	$start_date                    = explode('-',$start_date);// d/m/y
	$data['start_date']            = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];
	
	$end_date                    = mysqli_real_escape_string($db_con,$_POST['end_date']);
	$end_date                    = explode('-',$end_date);// d/m/y
	$data['end_date']            = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];
	
	$data['batch_created_by']    = $logged_uid;
	$data['batch_created']       = $datetime;
	
	
	$sql_check                   = " SELECT * FROM tbl_batches WHERE batch_name='".$data['batch_name']."' AND batch_id!='".$batch_id."'";
	$res_check                   = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check                   = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $res = update('tbl_batches',$data,array('batch_id'=>$batch_id)); 
		    
			if($res)
			{
				$sql_delete	= " DELETE FROM `tbl_batch_time` WHERE `batch_id` = '".$batch_id."' ";
		        $res_delete	= mysqli_query($db_con,$sql_delete) or die(mysqli_error($db_con));			
				$days  = $_POST['days'];
				foreach($days as $day)
				{
					$tdata['batch_id']   = $batch_id;
					$tdata['batch_day']  = $day;
					$tdata['batch_time'] = mysqli_real_escape_string($db_con,$_POST[$day.'_form']);
					$tdata['created']    = $datetime;
					$tdata['created_by'] = $logged_uid;
					insert('tbl_batch_time',$tdata);
				}
				quit('Batch Updated Successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
	}
	else
	{
		quit('Batch Name already Exist...!');
	}	
}



if((isset($obj->load_batch)) == "1" && isset($obj->load_batch))
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
			
		$sql_load_data  = " SELECT tb.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tb.batch_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tb.batch_modified_by) AS name_midified_by 
							FROM `tbl_batches` AS tb ";
	
		$sql_load_data  .= "					 WHERE 1=1";
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND batch_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (batch_name like '%".$search_text."%' or batch_limit like '%".$search_text."%' ";
			$sql_load_data .= " or start_date like '%".$search_text."%'  or end_date like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY batch_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$batch_data  = "";	
			$batch_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$batch_data .= '<thead>';
    	  	$batch_data .= '<tr>';
         	$batch_data .= '<th style="text-align:center">Sr No.</th>';
			$batch_data .= '<th style="text-align:center">Batch Name</th>';
			$batch_data .= '<th style="text-align:center">Coaches</th>';
			$batch_data .= '<th style="text-align:center">Students</th>';
			$batch_data .= '<th style="text-align:center">Limit</th>';
			$batch_data .= '<th style="text-align:center">Start Date</th>';
			$batch_data .= '<th style="text-align:center">End Date</th>';
			$batch_data .= '<th style="text-align:center">Created Date</th>';
			$batch_data .= '<th style="text-align:center">Created By</th>';
			$batch_data .= '<th style="text-align:center">Modified Date</th>';
			$batch_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_batch.php",3);
			
			if($dis)
			{			
				$batch_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_batch.php",1);
			
			if($edit)
			{			
				$batch_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_batch.php",2);
			
			if($delete)
			{			
				$batch_data .= '<th style="text-align:center"><div style="text-align:center">';
				$batch_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$batch_data .= '</tr>';
      		$batch_data .= '</thead>';
      		$batch_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$batch_data .= '<tr>';				
				$batch_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
			    $batch_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['batch_name']).'" class="btn-link" id="'.$row_load_data['batch_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				
				$coach_num = isExist('tbl_batch_coach',array('batch_id'=>$row_load_data['batch_id']));
				$batch_data .= '<td style="text-align:center">';
				$batch_data .= '<input type="button" value=" '.$coach_num.' Coach" id="'.$row_load_data['batch_id'].'" class="btn-warning" onclick="viewCoach(this.id);"></td>';		
				
				$stud_num   = isExist('tbl_batch_students',array('batch_id'=>$row_load_data['batch_id']));
				$batch_data .= '<td style="text-align:center">';
				$batch_data .= '<input type="button" value="'.$stud_num.' Students" id="'.$row_load_data['batch_id'].'" class="btn-warning" onclick="viewStudent(this.id);">  </td>';	
				
				$batch_data .= '<td style="text-align:center">'.$row_load_data['batch_limit'].'</td>';
				
				$sdate = explode('-',$row_load_data['start_date']);
			    $sdate = $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
				$batch_data .= '<td style="text-align:center">'.$sdate.'</td>';	
				
				$edate = explode('-',$row_load_data['end_date']);
			    $edate = $edate[2].'-'.$edate[1].'-'.$edate[0];
						
				$batch_data .= '<td style="text-align:center">'.$edate.'</td>';
				$batch_data .= '<td style="text-align:center">'.$row_load_data['batch_created'].'</td>';
				$batch_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$batch_data .= '<td style="text-align:center">'.$row_load_data['batch_modified'].'</td>';
				$batch_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_batch.php",3);
				
				if($dis)
				{					
					$batch_data .= '<td style="text-align:center">';					
					if($row_load_data['batch_status'] == 1)
					{
						$batch_data .= '<input type="button" value="Active" id="'.$row_load_data['batch_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$batch_data .= '<input type="button" value="Inactive" id="'.$row_load_data['batch_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$batch_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_batch.php",1);
				
				if($edit)
				{				
					$batch_data .= '<td style="text-align:center">';
					$batch_data .= '<input type="button" value="Edit" id="'.$row_load_data['batch_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_batch.php",2);
				
				if($delete)
				{					
					$batch_data .= '<td><div class="controls" align="center">';
					$batch_data .= '<input type="checkbox" value="'.$row_load_data['batch_id'].'" id="batch'.$row_load_data['batch_id'].'" name="batch'.$row_load_data['batch_id'].'" class="css-checkbox batch">';
					$batch_data .= '<label for="batch'.$row_load_data['batch_id'].'" class="css-label"></label>';
					$batch_data .= '</div></td>';										
				}
	          	$batch_data .= '</tr>';															
			}	
      		$batch_data .= '</tbody>';
      		$batch_data .= '</table>';	
			$batch_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$batch_data);				
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
		if($batch_id != "" && $req_type != "add")
		{
			$sql_batch_data 	    = "Select * from tbl_batches where batch_id = '".$batch_id."' ";
			$result_batch_data 	= mysqli_query($db_con,$sql_batch_data) or die(mysqli_error($db_con));
			$row_batch_data		= mysqli_fetch_array($result_batch_data);		
		}	
			
		$data = '';
		if($batch_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" name="batch_id" id="batch_id" value="'.$batch_id.'">';
			$data .= '<input type="hidden" name="update_batch" id="update_batch" value="1">';
		}        
		
		
		if($req_type=='add')
		{
				$data .= '<input type="hidden" name="insert_batch" id="insert_batch" value="1">';
		}
		
		if($req_type !='view')
		{
		
		
		
		//////=============================================Start : Coach Name======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Batch Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input  type="text" id="batch_name" name="batch_name" class="input-large keyup-char" placeholder="Enter Batch Name" data-rule-required="true" ';
		if($batch_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_batch_data['batch_name'].'"'; 
		}
		elseif($batch_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_batch_data['batch_name'].'" disabled'; 				
		}
		$data .= '/><br>';
		$data .= '</div>';
		$data .= '</div> <!-- batch_name Name -->';
		
		
		//////=============================================Start : Coach Email======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Student / Batch Limit<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input  type="text" id="batch_limit" placeholder="Enter Student Limit" name="batch_limit" class="input-large" onkeypress="return isNumberKey(event);"  data-rule-required="true" ';
		if($batch_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_batch_data['batch_limit'].'"'; 
		}
		elseif($batch_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_batch_data['batch_limit'].'" disabled'; 				
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
			$sql_get_time = " SELECT * FROM tbl_batch_time WHERE batch_id='".$batch_id."'";
			$res_get_time = mysqli_query($db_con,$sql_get_time) or die(mysqli_error($db_con));
		    while($row_get_time = mysqli_fetch_array($res_get_time))
			{
				$data .="$('#".$row_get_time['batch_day']."_from').val('".$row_get_time['batch_time']."');";
				
				$data .="$('#".$row_get_time['batch_day']."').prop('checked',true);";
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
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Duration<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= 'Start Date : <input readonly="readonly"  type="text" id="start_date" name="start_date" class="input-large datepicker" placeholder="Enter Start Date" data-rule-required="true" ';
		if($batch_id != "" && $req_type == "edit")
		{
			$sdate = explode('-',$row_batch_data['start_date']);
			$sdate = $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
			$data .= ' value="'.$sdate.'"'; 
		}
		elseif($batch_id != "" && $req_type == "view")
		{
			$sdate = explode('-',$row_batch_data['start_date']);
			$sdate = $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
			$data .= ' value="'.$sdate.'" disabled'; 				
		}
		$data .= '/><br><br>';
		
		$data .= 'End Date : &nbsp;&nbsp;<input readonly="readonly"  type="text" id="end_date" name="end_date" class="input-large datepicker" placeholder="Enter End Date" data-rule-required="true" ';
		if($batch_id != "" && $req_type == "edit")
		{
			$edate = explode('-',$row_batch_data['end_date']);
			$edate = $edate[2].'-'.$edate[1].'-'.$edate[0];
			$data .= ' value="'.$edate.'"'; 
		}
		elseif($batch_id != "" && $req_type == "view")
		{
			$edate = explode('-',$row_batch_data['end_date']);
			$edate = $edate[2].'-'.$edate[1].'-'.$edate[0];
			$data .= ' value="'.$edate.'" disabled'; 				
		}
		$data .= '/><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Coach Name -->';
        $data .="<script type=\"text/javascript\">	 $( '.datepicker' ).datepicker({
		changeMonth	: true,
		changeYear	: true,
		format: 'dd-mm-yyyy',
		yearRange 	: 'c:c',//replaced 'c+0' with c (for showing years till current year)
		startDate: '+d',
			
	   });
	  
	   </script>";
         	

		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		if($batch_id != "" && $req_type == "view")
		{
			if($row_batch_data['batch_status'] == 1)
			{
				$data .= ' <label class="control-label" style="color:#30DD00"> Active </label>';
			}
			if($row_batch_data['batch_status'] == 0)
			{
				$data .= ' <label class="control-label" style="color:#E63A3A"> Inactive </label>';
			}
		}
		else
		{  
          if($batch_id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="batch_status" value="1" class="css-radio" data-rule-required="true" ';
				$dis	= checkFunctionalityRight("view_batch.php",3);
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_batch_data['batch_status'] == 1)
				{
					$data .= 'checked ';
				}
				$data .= '> Active ';
				$data .= '<input type="radio" name="batch_status" value="0" class="css-radio" data-rule-required="true"';
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_batch_data['batch_status'] == 0  )
				{
					$data .= 'checked ';
				}
				$data .= '> Inactive';
			} 
			else  
			{
				$data .= ' <input type="radio" name="batch_status" value="1" class="css-radio" data-rule-required="true" ';
				$data .= '> Active ';
				$data .= ' <input type="radio" name="batch_status" value="0" class="css-radio" data-rule-required="true"';
			
		 		$data .= '> Inactive ';
			}
		}
					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		
		$data .= '<div class="form-actions">';
		if($batch_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Batch</button>';			
		}
		elseif($batch_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Batch</button>';			
		}			
		$data .= '</div> <!-- Save and cancel -->';	
	 }
	    else
		{
			if($row_batch_data['batch_status']==1)
			{
				$bgcolor = '#18BB7C';
				$color   = 'white';
			}
			else
			{
				$bgcolor = '#da4f49';
				$color    ='';
			}
			//==================Start :  Heading  ===========================================//
			$data .='<div class="control-group" style="background-color:'.$bgcolor.'">';
			
			$data .='<div class="span2">';
				$data .='<div style="padding:20px">';
					
				$data .='</div>';
			$data .='</div>';
			
			$data .='<div class="span8">';
			    $data .='<div style="padding-bottom:0px;">';
					$data .='<h3  style="text-align:center; color:white">'.ucwords($row_batch_data['batch_name']).'</h3>';
				$data .='</div>';
				$data .='<div class="control-group" style="background-color:'.$bgcolor.';padding-bottom:20px;">';
					$data .='<div class="span4">';
						$data .='<div style="">';
							$start_date = explode('-',$row_batch_data['start_date']);
							$data .='<span class="head2" style="color:white">Star Date : ';
							$data .=$start_date[2].'/'.$start_date[1].'/'.$start_date[0].'</span>';
						$data .='</div>';
					$data .='</div>';
					$data .='<div class="span4">';
						$data .='<div style="">';
							$start_date = explode('-',$row_batch_data['end_date']);
							$data .='<span class="head2" style="color:white">End Date : ';
							$data .=$start_date[2].'/'.$start_date[1].'/'.$start_date[0].'</span>';
						$data .='</div>';
					$data .='</div>';
					$data .='<div class="span4">';
						$data .='<div style="">';
							$data .='<span class="head2" style="color:white">Student Limit : ';
							$data .=$row_batch_data['batch_limit'].'</span>';
						$data .='</div>';
					$data .='</div>';// student limit
					$data .='<div class="span12" style="clear:both">';
						$data .='<div style="">';
							$data .='<span class="head2" style="color:white">Days : ';
							$sql_get_days =" SELECT DISTINCT(batch_day) FROM tbl_batch_time WHERE batch_id='".$batch_id."'";
							$res_get_days = mysqli_query($db_con,$sql_get_days) or die(mysqli_error($db_con));
							$day_array    =  array(); 
							while($row = mysqli_fetch_array($res_get_days))
							{
								array_push($day_array,$row['batch_day']);
							}
							$days         = implode(' / ',$day_array);
							$data 		 .= $days.'</span>';
						$data .='</div>';
					$data .='</div>';// student limit
					
				$data .='</div>';
				
			$data .='</div>';
			
			$data .='<div class="span2">';
				$data .='<div style="padding:20px">';
					
				$data .='</div>';
			$data .='</div>';
			
			
			
			$data .='</div>';// control-group end
			
			//==================End : Heading  ===========================================//
			
			
			
			//==================Start : Coaches  ===========================================//
			
		
		    $sql_get_stud  = "SELECT * FROM tbl_batch_coach  as ttc ";
			$sql_get_stud .= " INNER JOIN tbl_cadmin_users as  tcu ON ttc.coach_id =tcu.id ";
			$sql_get_stud .= " WHERE batch_id='".$batch_id."'";
			$res_get_stud  = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
			$num_get_stud  = mysqli_num_rows($res_get_stud);
			$res_array     = array();
			while($row = mysqli_fetch_array($res_get_stud))
			{
				array_push($res_array,$row);
			}
			$num_get_stud1 = round(($num_get_stud)/2);
			$num_get_stud2 = $num_get_stud - $num_get_stud1;
			
			if(!empty($res_array))
			{
			
			
				$data .='<div class="control-group">';
				$data .='<div class="span12">';
					$data .='<div style="padding:20px">';
						$data .='<h5>Coaches ('.$num_get_stud.')</h5>';
					$data .='</div>';
				$data .='</div>';
				
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px">';
						$data .='<ul>';
						for($i=0;$i<$num_get_stud1;$i++)
						{
							$data .='<li class="head2"><a href="view_coach.php?pag=Coaches&coach_id='.$res_array[$i]['id'].'" target="_blank" >'.ucwords($res_array[$i]['fullname']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px;">';
						$data .='<ul>';
						for($i=$i;$i<$num_get_stud;$i++)
						{
							$data .='<li class="head2"><a href="view_coach.php?pag=Coaches&coach_id='.$res_array[$i]['id'].'" target="_blank" > '.ucwords($res_array[$i]['fullname']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='</div>';// control-group
		    }
			//==================End : Coaches  ===========================================//
			
			
			//==================Start : Student  ===========================================//
			
			
			$sql_get_stud  = "SELECT * FROM tbl_batch_students  as tts ";
			$sql_get_stud .= " INNER JOIN tbl_students as  ts ON tts.student_id =ts.student_id ";
			$sql_get_stud .= " WHERE batch_id='".$batch_id."'";
			$res_get_stud  = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
			$num_get_stud  = mysqli_num_rows($res_get_stud);
			$res_array     = array();
			while($row = mysqli_fetch_array($res_get_stud))
			{
				array_push($res_array,$row);
			}
			$num_get_stud1 = round(($num_get_stud)/2);
			$num_get_stud2 = $num_get_stud - $num_get_stud1;
			
			if(!empty($res_array))
			{
				$data .='<div class="control-group">';
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px">';
						$data .='<h5> Students ('.$num_get_stud.')</h5>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='<div class="span4" style="clear:both">';
					$data .='<div style="padding:20px">';
						$data .='<ul>';
						for($i=0;$i<$num_get_stud1;$i++)
						{
							$data .='<li class="head2"><a href="view_student.php?pag=Students&student_id='.$res_array[$i]['student_id'].'" target="_blank" >'.ucwords($res_array[$i]['student_fname']).' '.ucwords($res_array[$i]['student_lname']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px;">';
						$data .='<ul>';
						for($i=$i;$i<$num_get_stud;$i++)
						{
							$data .='<li class="head2"><a href="view_student.php?pag=Students&student_id='.$res_array[$i]['student_id'].'" target="_blank" >'.ucwords($res_array[$i]['student_fname']).' '.ucwords($res_array[$i]['student_lname']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='</div>';// row end
			}
			//==================End : Student  ===========================================//
			
		}
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
	
	$data['batch_status']            = $curr_status;
	$data['batch_modified_by'] = $logged_uid;
	$data['batch_modified']    = $datetime;
	$res = update('tbl_batches',$data,array('batch_id'=>$id));
	if($res)
	{
		quit('Success',1);
	}
	else
	{
		quit('Please try lettre...!');
	}
}

//------------------This is used for delete data---------------------------------

if((isset($obj->delete_batch)) == "1" && isset($obj->delete_batch))
{
	$response_array   = array();		
	$ids 	  = $obj->batch;
	$del_flag 		  = 0; 
	foreach($ids as $id)	
	{
		$sql_delete_area	= " DELETE FROM `tbl_batches` WHERE `batch_id` = '".$id."' ";
		$result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));			
		if($result_delete_area)
		{
			$del_flag = 1;
			$sql_delete_batch	= " DELETE FROM `tbl_batch_coach` WHERE `batch_id` = '".$id."'";
		    $res_delete_batch	= mysqli_query($db_con,$sql_delete_batch) or die(mysqli_error($db_con));
			
			$sql_delete_stud	= " DELETE FROM `tbl_batch_students` WHERE `batch_id` = '".$id."'";
		    $res_delete_stud	= mysqli_query($db_con,$sql_delete_stud) or die(mysqli_error($db_con));
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

if((isset($obj->getCoach)) == "1" && isset($obj->getCoach))
{
	$batch_id   =       $obj->batch_id;
	
	$data  = '';
	$data .='<input type="hidden" name="batch_id" id="batch_id" value="'.$batch_id.'" />';
	
	$data .='<input type="hidden" name="addCoach" id="addCoach" value="1" />';
	$data .='<div style="padding:15px;text-align:center">';
	
	$sql_get_team    = " SELECT * FROM tbl_batches WHERE batch_id ='".$batch_id."' ";
	$res_get_team    = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
	$row_get_team    = mysqli_fetch_array($res_get_team);
	
	$sql_get_coach   = " SELECT * FROM tbl_cadmin_users WHERE utype=15 ";
	$sql_get_coach  .= " AND id NOT IN(SELECT DISTINCT(coach_id) FROM tbl_batch_coach WHERE batch_id='".$batch_id."') AND status=1";
	$res_get_coach   = mysqli_query($db_con,$sql_get_coach) or die(mysqli_error($db_con));
	$data .= '<select multiple="multiple"  onChange="console.log($(this).children(":selected").length)" placeholder="Select Coach"  style="width:70%"  name="coach_id[]"  id="coach_id" class="select2-me input-large" data-rule-required="true">';

	foreach($res_get_coach as $row)
	{
		$data .='<option value="'.$row['id'].'">'.ucwords($row['fullname']).'</option>';
	}
	$data .= '</select> ';
	
	$data .= '<input value="Add Coach" id="" class="btn-success"  type="submit">';
	
	$data .= '</div> ';
	
	$data .= '<script type="text/javascript">';
	$data .= '$("#coach_id").select2();';
	$data .= '</script>';
	
	
	$sql_get_coach  = " SELECT tcu.fullname,tcu.id as coach_id,tbc.* FROM tbl_batch_coach  as tbc  ";
	$sql_get_coach .= " INNER JOIN tbl_cadmin_users as tcu ON tbc.coach_id = tcu.id ";
	$sql_get_coach .= " WHERE   ";
	$sql_get_coach .= "  batch_id='".$batch_id."'";
	$res_get_coach  = mysqli_query($db_con,$sql_get_coach) or die(mysqli_error($db_con));
	
	if(mysqli_num_rows($res_get_coach)!=0)
	{
	
		$data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
		$data .= '<thead>';
		$data .= '<tr>';
		$data .= '<th style="text-align:center">Sr No.</th>';
		$data .= '<th style="text-align:center">Coach Name</th>';
		$delete = checkFunctionalityRight("view_team.php",2);
		if($delete)
		{			
			$data .= '<th style="text-align:center">
			<div style="text-align:center">';
			$data .= '<input type="button"  value="Delete" onclick="multipleCoachDelete('.$batch_id.');" class="btn-danger"/>
			</div></th>';
		}
		$data .= '</tr>';
		$data .= '</thead>';
		$data .= '<tbody>';
		
		while($row_load_data = mysqli_fetch_array($res_get_coach))
		{
			$data .= '<tr>';				
			$data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
			$data .= '<td style="text-align:center"><a target="_blank" href="view_coach.php?pag=Coach&coach_id='.$row_load_data['coach_id'].'"  >'.ucwords($row_load_data['fullname']).'</a></td>';
			$delete = checkFunctionalityRight("view_team.php",2);
			if($delete)
			{					
				$data .= '<td><div class="controls" align="center">';
				$data .= '<input type="checkbox" value="'.$row_load_data['id'].'" id="coach_batch'.$row_load_data['id'].'" name="coach_batch'.$row_load_data['id'].'" class="css-checkbox coach_batch">';
				$data .= '<label for="coach_batch'.$row_load_data['id'].'" class="css-label"></label>';
				$data .= '</div></td>';										
			}
			$data .= '</tr>';															
		}	
		$data .= '</tbody>';
		$data .= '</table>';
	}
	
	quit(array($data,ucwords($row_get_team['batch_name'])),1);
}

if((isset($obj->getStudent)) == "1" && isset($obj->getStudent))
{
	$batch_id   =       $obj->batch_id;
	$data       ='';
	$data  	   .='<input type="hidden" name="batch_id" id="batch_id" value="'.$batch_id.'">';
	$data .='<input type="hidden" name="addStudent" id="addStudent" value="1">';
	$data .='<div style="padding:15px;text-align:center">';
	
	$sql_get_team    = " SELECT * FROM tbl_batches WHERE batch_id ='".$batch_id."' ";
	$res_get_team    = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
	$row_get_team    = mysqli_fetch_array($res_get_team);
	
	$sql_get_student   = " SELECT * FROM tbl_students WHERE student_status=1";
	$sql_get_student  .= " AND student_id NOT IN(SELECT DISTINCT(student_id) FROM tbl_batch_students WHERE batch_id='".$batch_id."')";
	$res_get_student   = mysqli_query($db_con,$sql_get_student) or die(mysqli_error($db_con));
	$data .= '<select multiple="multiple"  onChange="console.log($(this).children(":selected").length)" placeholder="Select Student"  style="width:70%"  name="student_id[]"  id="student_id" class="select2-me input-large" data-rule-required="true">';

	foreach($res_get_student as $row)
	{
		$data .='<option value="'.$row['student_id'].'">'.ucwords($row['student_fname']).' '.ucwords($row['student_lname']).'</option>';
	}
	$data .= '</select> ';
	
	$data .= '<input value="Add Student" id="" class="btn-success"  type="submit">';
	
	$data .= '</div> ';
	
	$data .= '<script type="text/javascript">';
	$data .= '$("#student_id").select2();';
	$data .= '</script>';
	
	
	$sql_get_student  = " SELECT ts.student_fname,ts.student_mname,ts.student_lname,ts.student_id,tts.* FROM tbl_batch_students  as tts  ";
	$sql_get_student .= " INNER JOIN tbl_students as ts ON tts.student_id = ts.student_id ";
	$sql_get_student .= " WHERE   ";
	$sql_get_student .= "  batch_id='".$batch_id."'";
	$res_get_student  = mysqli_query($db_con,$sql_get_student) or die(mysqli_error($db_con));
	
	if(mysqli_num_rows($res_get_student)!=0)
	{
	
		$data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
		$data .= '<thead>';
		$data .= '<tr>';
		$data .= '<th style="text-align:center">Sr No.</th>';
		$data .= '<th style="text-align:center">Coach Name</th>';
		$delete = checkFunctionalityRight("view_team.php",2);
		if($delete)
		{			
			$data .= '<th style="text-align:center">
			<div style="text-align:center">';
			$data .= '<input type="button"  value="Delete" onclick="multipleStudentDelete('.$batch_id.');" class="btn-danger"/>
			</div></th>';
		
		}
		
		$data .= '</tr>';
		$data .= '</thead>';
		$data .= '<tbody>';
		
		while($row_load_data = mysqli_fetch_array($res_get_student))
		{
			$data .= '<tr>';				
			$data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
			$data .= '<td style="text-align:center"><a target="_blank" href="view_student.php?pag=Students&student_id='.$row_load_data['student_id'].'"  >'.ucwords($row_load_data['student_fname']).' '.ucwords($row_load_data['student_mname']).' '.ucwords($row_load_data['student_lname']).'</a></td>';
			$delete = checkFunctionalityRight("view_team.php",2);
			if($delete)
			{					
				$data .= '<td><div class="controls" align="center">';
				$data .= '<input type="checkbox" value="'.$row_load_data['id'].'" id="student_batch'.$row_load_data['id'].'" name="student_batch'.$row_load_data['id'].'" class="css-checkbox student_batch">';
				$data .= '<label for="student_batch'.$row_load_data['id'].'" class="css-label"></label>';
				$data .= '</div></td>';		
										
			}
			$data .= '</tr>';															
		}	
		$data .= '</tbody>';
		$data .= '</table>';
	}
	
	quit(array($data,ucwords($row_get_team['batch_name'])),1);
}



if((isset($_POST['addCoach'])) == "1" && isset($_POST['addCoach']))
{
	$data['batch_id']  =  $_POST['batch_id'];  
	$coach_ids        =  $_POST['coach_id'];
	$add_flag         =  0;
	foreach($coach_ids as $coach_id)
	{
		$data['coach_id']          = $coach_id;
		$data['coach_created']     = $datetime;
		$data['coach_created_by']  = $uid;
		$data['team_coach_status'] = 1;
		insert('tbl_batch_coach',$data);
		$add_flag         =  1;
	}
	
	if($add_flag == 1)
	{
		quit($data['batch_id'],1);
	}
	else
	{
		quit('something went wrong...');
	}
}


if((isset($_POST['addStudent'])) == "1" && isset($_POST['addStudent']))
{
	
	$batch_id          = $_POST['batch_id'];  
	$student_ids      =  $_POST['student_id'];
	$add_flag         =  0;
	$row       = checkExist('tbl_batches',array('batch_id'=>$batch_id));
	
	
	
	foreach($student_ids as $student_id)
	{
		$studCount = isExist('tbl_batch_students',array('batch_id'=>$batch_id));
		if($row['batch_limit'] <= $studCount)
		{
			quit(array('Student limit exceed...!',$batch_id),1);
		}
		else
		{
			$data['batch_id']            = $batch_id;
			$data['student_id']          = $student_id;
			$data['student_created']     = $datetime;
			$data['student_created_by']  = $uid;
			$data['team_student_status'] = 1;
			insert('tbl_batch_students',$data);
			$add_flag         =  1;
		}
	}
	
	if($add_flag == 1)
	{
		quit(array('Student added successfully',$batch_id),1);
	}
	else
	{
		quit('Something went wrong...');
	}
}



if((isset($obj->remove_coach)) == "1" && isset($obj->remove_coach))
{
	$coach_batch   = $obj->coach_batch;
	$delete_flag   = 0;
	foreach($coach_batch as $id)
	{
		$sql_delete = " DELETE FROM tbl_batch_coach WHERE id='".$id."' ";
		$res_delete = mysqli_query($db_con,$sql_delete) or die(mysqli_error($db_con));
		if($res_delete)
		{
			$delete_flag = 1;
		}
	}
	if($delete_flag==1)
	{
		quit('',1);
	}
	quit('Something went wrong..!');
}


if((isset($obj->remove_student)) == "1" && isset($obj->remove_student))
{
	$student_batch   = $obj->student_batch;
	$delete_flag   = 0;
	foreach($student_batch as $id)
	{
		$sql_delete = " DELETE FROM tbl_batch_students WHERE id='".$id."' ";
		$res_delete = mysqli_query($db_con,$sql_delete) or die(mysqli_error($db_con));
		if($res_delete)
		{
			$delete_flag = 1;
		}
	}
	if($delete_flag==1)
	{
		quit('',1);
	}
	quit('Something went wrong..!');
}

?>
