<?php
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
	$data['competition_id']       = mysqli_real_escape_string($db_con,$_POST['competition']);
	
	$data['batch_created_by']    = $logged_uid;
	$data['batch_created']       = $datetime;
	
	$start_date                    = mysqli_real_escape_string($db_con,$_POST['start_date']);
	$start_date                    = explode('-',$start_date);// d/m/y
	$data['start_date']            = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];
	
	$end_date                    = mysqli_real_escape_string($db_con,$_POST['end_date']);
	$end_date                    = explode('-',$end_date);// d/m/y
	$data['end_date']            = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];
	
	$sql_check             = " SELECT * FROM tbl_batches  as tb WHERE batch_name='".$data['batch_name']."' AND competition_id='".$data['competition_id']."'";
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
	$data['competition_id']       = mysqli_real_escape_string($db_con,$_POST['competition']);
	
	$sql_check                   = " SELECT * FROM tbl_batches WHERE batch_name='".$data['batch_name']."' AND batch_id!='".$batch_id."' AND competition_id='".$data['competition_id']."'";
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
	$search_text	= $obj->search_text;	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT tb.*,tc.competition_name,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tb.batch_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tb.batch_modified_by) AS name_midified_by 
							FROM `tbl_batches` AS tb ";
		$sql_load_data  .= " INNER JOIN tbl_competition as tc ON tb.competition_id = tc.competition_id ";
		$sql_load_data  .= "					 WHERE 1=1";
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND batch_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (batch_name like '%".$search_text."%' or batch_limit like '%".$search_text."%' ";
			$sql_load_data .= " or start_date like '%".$search_text."%'  or end_date like '%".$search_text."%' or competition_name like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY batch_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Competition Name</th>';
			$area_data .= '<th style="text-align:center">Batch Name</th>';
			$area_data .= '<th style="text-align:center">Limit</th>';
			$area_data .= '<th style="text-align:center">Start Date</th>';
			$area_data .= '<th style="text-align:center">End Date</th>';
			$area_data .= '<th style="text-align:center">Created Date</th>';
			$area_data .= '<th style="text-align:center">Created By</th>';
			$area_data .= '<th style="text-align:center">Modified Date</th>';
			$area_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_batch.php",3);
			$dis = 1;
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_batch.php",1);
			$edit = 1;
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_batch.php",2);
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
			
				$area_data .= '<td style="text-align:center">'.ucwords($row_load_data['competition_name']).'</td>';
				$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['batch_name']).'" class="btn-link" id="'.$row_load_data['batch_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['batch_limit'].'</td>';
				
				$sdate = explode('-',$row_load_data['start_date']);
			    $sdate = $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
				$area_data .= '<td style="text-align:center">'.$sdate.'</td>';	
				
				$edate = explode('-',$row_load_data['end_date']);
			    $edate = $edate[2].'-'.$edate[1].'-'.$edate[0];
						
				$area_data .= '<td style="text-align:center">'.$edate.'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['batch_created'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['batch_modified'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_batch.php",3);
				$dis = 1;
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['batch_status'] == 1)
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['batch_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['batch_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_batch.php",1);
				$edit = 1;
				if($edit)
				{				
					$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Edit" id="'.$row_load_data['batch_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_batch.php",2);
				$delete = 1;
				if($delete)
				{					
					$area_data .= '<td><div class="controls" align="center">';
					$area_data .= '<input type="checkbox" value="'.$row_load_data['batch_id'].'" id="batch'.$row_load_data['batch_id'].'" name="batch'.$row_load_data['batch_id'].'" class="css-checkbox batch">';
					$area_data .= '<label for="batch'.$row_load_data['batch_id'].'" class="css-label"></label>';
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
		if($batch_id != "" && $req_type != "add")
		{
			$sql_area_data 	    = "Select * from tbl_batches where batch_id = '".$batch_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_batch_data		= mysqli_fetch_array($result_area_data);		
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
		
		//////=============================================Start : Competition======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Competition<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<select  name="competition"  id="competition" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
			$data .= '<option value="">Select Competition</option>';
			$sql   =' SELECT * FROM tbl_competition WHERE competition_status =1 ORDER BY competition_name ASC ';
			
			$res   =  mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($res))
			{
				$data .='<option value="'.$row['competition_id'].'" ';
				if($req_type !='add')
				{
					if($row['competition_id']==$row_batch_data['competition_id'])
					{
						$data .=' selected ';
					}
				}
				$data .='>'.ucwords($row['competition_name']).'</option> ';
			}
			
			$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- Country -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#competition").select2();';
		$data .= '</script>';
		
		
		//////=============================================Start : Coach Name======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Batch Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input  type="text" id="batch_name" name="batch_name" class="input-large keyup-char" placeholder="Batch Name" data-rule-required="true" ';
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
		$data .= '<input  type="text" id="batch_limit" name="batch_limit" class="input-large" onkeypress="return isNumberKey(event);" placeholder="Student Limit" data-rule-required="true" ';
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
					  <td><input name="Monday_form" id="Monday_from" class="form_time timepicker" type="text"></td>
					</tr>
					<tr>
					  <td><input class="cdays" name="days[]" id="Tuesday" value="Tuesday" type="checkbox"> Tue</td>
					  <td><input name="Tuesday_form" id="Tuesday_from" class="form_time timepicker" type="text"></td>
					 
					</tr>
					<tr>
					 <td><input class="cdays" name="days[]" id="Wednesday" value="Wednesday" type="checkbox"> Wed</td>
					  <td><input name="Wednesday_form" id="Wednesday_from" class="form_time timepicker" type="text"></td>
					 
					</tr>
					<tr>
					<td><input class="cdays" name="days[]" id="Thursday" value="Thursday" type="checkbox"> Thu</td>
					  <td><input name="Thursday_form" id="Thursday_from" class="form_time timepicker" type="text"></td>
					 
					</tr>
					<tr>
					<td><input class="cdays" name="days[]" id="Friday" value="Friday" type="checkbox"> Fri</td>
					  <td><input name="Friday_form" id="Friday_from" class="form_time timepicker" type="text"></td>
					
					</tr>
					<tr>
					 <td><input class="cdays" name="days[]" id="Saturday" value="Saturday" type="checkbox"> Sat</td>
					  <td><input name="Saturday_form" id="Saturday_from" class="form_time bootstrap-timepicker timepicker timepicker1" type="text"></td>
					 
					</tr> 
					<tr>
					 <td><input class="cdays" name="days[]" id="Sunday" value="Sunday" type="checkbox"> Sun</td>
					  <td><input name="Sunday_form" id="Sunday_from" class="form_time timepicker" type="text"></td>
					  
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
		$data .= 'Start Date : <input readonly="readonly"  type="text" id="start_date" name="start_date" class="input-large datepicker" placeholder="Start Date" data-rule-required="true" ';
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
		
		$data .= 'End Date : &nbsp;&nbsp;<input readonly="readonly"  type="text" id="end_date" name="end_date" class="input-large datepicker" placeholder="End Date" data-rule-required="true" ';
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

if((isset($obj->delete_area)) == "1" && isset($obj->delete_area))
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

?>
