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
if((isset($_POST['insert_competition'])) == "1" && isset($_POST['insert_competition']))
{
	$data['competition_name']      = strtolower(mysqli_real_escape_string($db_con,$_POST['competition_name']));
	$data['competition_place']     = mysqli_real_escape_string($db_con,$_POST['competition_place']);
	$data['competition_limit']     = mysqli_real_escape_string($db_con,$_POST['student_limit']);
	
	
	$start_date                    = mysqli_real_escape_string($db_con,$_POST['start_date']);
	$start_date                    = explode('-',$start_date);// d/m/y
	$data['start_date']            = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];
	
	$end_date                    = mysqli_real_escape_string($db_con,$_POST['end_date']);
	$end_date                    = explode('-',$end_date);// d/m/y
	$data['end_date']            = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];
	
	
	
	$data['competition_status']          = mysqli_real_escape_string($db_con,$_POST['competition_status']);
	
	$data['created_by']    = $logged_uid;
	$data['created_date']       = $datetime;
	
	$sql_check             = " SELECT * FROM tbl_competition WHERE competition_name like'".$data['competition_name']."' ";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = insert('tbl_competition',$data); 
		    
			if($insert_id)
			{
				quit('Competition Added Successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
			
		  
	}
	else
	{
		quit('Competition already Exist...!');
	}	
}


if((isset($_POST['update_competition'])) == "1" && isset($_POST['update_competition']))
{
	$data['competition_name']      = strtolower(mysqli_real_escape_string($db_con,$_POST['competition_name']));
	$data['competition_place']     = mysqli_real_escape_string($db_con,$_POST['competition_place']);
	$data['competition_limit']     = mysqli_real_escape_string($db_con,$_POST['student_limit']);
	$data['competition_status']    = mysqli_real_escape_string($db_con,$_POST['competition_status']);
	
	
	$start_date                    = mysqli_real_escape_string($db_con,$_POST['start_date']);
	$start_date                    = explode('-',$start_date);// d/m/y
	$data['start_date']            = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];
	
	$end_date                    = mysqli_real_escape_string($db_con,$_POST['end_date']);
	$end_date                    = explode('-',$end_date);// d/m/y
	$data['end_date']            = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];
	
	//quit($data['start_date']);
	
	$competition_id                = mysqli_real_escape_string($db_con,$_POST['competition_id']);
	
	$data['modified_by']            = $logged_uid;
	$data['modified_date']          = $datetime;
	
	$sql_check             = " SELECT * FROM tbl_competition WHERE competition_name like'".$data['competition_name']."' AND competition_id!='".$competition_id."' ";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = update('tbl_competition',$data,array('competition_id'=>$competition_id)); 
		    
			if($insert_id)
			{
				quit('Competition Updated Successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
			
		  
	}
	else
	{
		quit('Competition already Exist...!');
	}	
}



if((isset($obj->load_competition)) == "1" && isset($obj->load_competition))
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
			
		$sql_load_data  = " SELECT tc.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.modified_by) AS name_midified_by 
							FROM `tbl_competition` AS tc WHERE 1=1";
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (competition_name like '%".$search_text."%' or competition_place like '%".$search_text."%' ";
			$sql_load_data .= " or start_date like '%".$search_text."%' or 	end_date like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY competition_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Competition Name</th>';
			$area_data .= '<th style="text-align:center">Place</th>';
			$area_data .= '<th style="text-align:center">Team Limit</th>';
			$area_data .= '<th style="text-align:center">Duration</th>';
			$area_data .= '<th style="text-align:center">Created Date</th>';
			$area_data .= '<th style="text-align:center">Created By</th>';
			$area_data .= '<th style="text-align:center">Modified Date</th>';
			$area_data .= '<th style="text-align:center">Modified By</th>';
			$area_data .= '<th style="text-align:center">Teams</th>';
			$dis = checkFunctionalityRight("view_competition.php",3);
			
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_competition.php",1);
			
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_competition.php",2);
			
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
			
				$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['competition_name']).'" class="btn-link" id="'.$row_load_data['competition_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				
				$area_data .= '<td style="text-align:center">'.ucwords($row_load_data['competition_place']).'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['competition_limit'].'</td>';		
				$area_data .= '<td style="text-align:center">'.$row_load_data['start_date'].'<br> to <br>'.$row_load_data['end_date'].'</td>';		
				$area_data .= '<td style="text-align:center">'.$row_load_data['created_date'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['modified_date'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Teams" id="'.$row_load_data['competition_id'].'" class="btn-warning" onclick="viewTeams(this.id);"></td>';	
				$dis = checkFunctionalityRight("view_competition.php",3);
				
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['competition_status'] == 1)
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['competition_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['competition_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_competition.php",1);
				
				if($edit)
				{				
					$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Edit" id="'.$row_load_data['competition_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_competition.php",2);
			
				if($delete)
				{					
					$area_data .= '<td><div class="controls" align="center">';
					$area_data .= '<input type="checkbox" value="'.$row_load_data['competition_id'].'" id="batch'.$row_load_data['competition_id'].'" name="batch'.$row_load_data['competition_id'].'" class="css-checkbox batch">';
					$area_data .= '<label for="batch'.$row_load_data['competition_id'].'" class="css-label"></label>';
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

if((isset($obj->load_competition_parts)) == "1" && isset($obj->load_competition_parts))
{
	$competition_id = $obj->competition_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($competition_id != "" && $req_type != "add")
		{
			$sql_area_data 	    = "Select * from tbl_competition where competition_id = '".$competition_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_comp_data		= mysqli_fetch_array($result_area_data);		
		}	
			
		$data = '';
		if($competition_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" name="competition_id" id="competition_id" value="'.$competition_id.'">';
			$data .= '<input type="hidden" name="update_competition" id="update_competition" value="1">';
		}        
		
		
		if($req_type=='add')
		{
				$data .= '<input type="hidden" name="insert_competition" id="insert_batch" value="1">';
		}
		
		if($req_type !="view")
		{
			
			//////=============================================Start : Competition Name======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Competition Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" id="competition_name" name="competition_name" class="input-large keyup-char" placeholder="Competition Name" data-rule-required="true" ';
			if($competition_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_comp_data['competition_name'].'"'; 
			}
			elseif($competition_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_comp_data['competition_name'].'" disabled'; 				
			}
			$data .= '/><br>';
			$data .= '</div>';
			$data .= '</div> <!-- Competition Name -->';
			
			//////=============================================Start : Place Name======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Place<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input onkeypress="return charsonly(event);" type="text" id="competition_place" name="competition_place" class="input-large keyup-char" placeholder="Competition Place" data-rule-required="true" ';
			if($competition_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_comp_data['competition_place'].'"'; 
			}
			elseif($competition_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_comp_data['competition_place'].'" disabled'; 				
			}
			$data .= '/><br>';
			$data .= '</div>';
			$data .= '</div> <!-- Competition Place -->';
			
			
			//////=============================================Start :Student Limit======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Team Limit<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input data-rule-number="true"  type="text" id="student_limit" name="student_limit" class="input-large" placeholder="Team Limit" data-rule-required="true" ';
			if($competition_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_comp_data['competition_limit'].'"'; 
			}
			elseif($competition_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_comp_data['competition_limit'].'" disabled'; 				
			}
			$data .= '/><br>';
			$data .= '</div>';
			$data .= '</div> <!-- Student Limit -->';
			
			
			//////=============================================Start : Duration======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Duration<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= 'Start Date : <input  type="text" id="start_date" readonly="readonly" name="start_date" class="input-large datepicker" placeholder="Start Date" data-rule-required="true" ';
			
			if($competition_id != "" && $req_type == "edit")
			{
				$sdate = explode('-',$row_comp_data['start_date']);
				$sdate = $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
				$data .= ' value="'.$sdate.'"'; 
			}
			elseif($competition_id != "" && $req_type == "view")
			{
				$sdate = explode('-',$row_comp_data['start_date']);
				$sdate = $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
				$data .= ' value="'.$sdate.'" disabled'; 				
			}
			$data .= '/><br><br>';
			
			$data .= 'End Date : &nbsp;&nbsp;<input readonly="readonly"  type="text" id="end_date" name="end_date" class="input-large datepicker" placeholder="End Date" data-rule-required="true" ';
			if($competition_id != "" && $req_type == "edit")
			{
				$edate = explode('-',$row_comp_data['end_date']);
				$edate = $edate[2].'-'.$edate[1].'-'.$edate[0];
				$data .= ' value="'.$edate.'"'; 
			}
			elseif($competition_id != "" && $req_type == "view")
			{
				$edate = explode('-',$row_comp_data['end_date']);
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
				
		   });</script>";
			
			
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
			$data .= '<div class="controls">';
			
			if($competition_id != "" && $req_type == "view")
			{
				if($row_comp_data['competition_status'] == 1)
				{
					$data .= ' <label class="control-label" style="color:#30DD00"> Active </label>';
				}
				if($row_comp_data['competition_status'] == 0)
				{
					$data .= ' <label class="control-label" style="color:#E63A3A"> Inactive </label>';
				}
			}
			else
			{  
			  if($competition_id != "" && $req_type == "edit")
			  {
					$data  .= '<input type="radio" name="competition_status" value="1" class="css-radio" data-rule-required="true" ';
					$dis	= checkFunctionalityRight("view_competition.php",3);
					if(!$dis)
					{
					//$data .= ' disabled="disabled" ';
					}
					if($row_comp_data['competition_status'] == 1)
					{
						$data .= 'checked ';
					}
					$data .= '> Active ';
					$data .= '<input type="radio" name="competition_status" value="0" class="css-radio" data-rule-required="true"';
					if(!$dis)
					{
					//$data .= ' disabled="disabled" ';
					}
					if($row_comp_data['competition_status'] == 0  )
					{
						$data .= 'checked ';
					}
					$data .= '> Inactive';
				} 
				else  
				{
					$data .= ' <input type="radio" name="competition_status" value="1" class="css-radio" data-rule-required="true" ';
					$data .= '> Active ';
					$data .= ' <input type="radio" name="competition_status" value="0" class="css-radio" data-rule-required="true"';
				
					$data .= '> Inactive ';
				}
			}
						
			$data .= '<label for="radiotest" class="css-label"></label>';
			$data .= '<label name = "radiotest" ></label>';
			$data .= '</div>';
			$data .= '</div><!--Status-->';
			
			$data .= '<div class="form-actions">';
			if($competition_id == "" && $req_type == "add")
			{
				$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Competition</button>';			
			}
			elseif($competition_id != "" && $req_type == "edit")
			{
				$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Competition</button>';			
			}			
			$data .= '</div> <!-- Save and cancel -->';	
			
		}
		else
		{
			if($row_comp_data['competition_status']==1)
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
				
			$data .='</div>';
			
			$data .='<div class="span8">';
			    $data .='<div style="padding-bottom:20px;">';
					$data .='<h3 class=""  style="color:White">'.ucwords($row_comp_data['competition_name']).'</h3>';
					
					$data .='<div class="control-group" style="background-color:'.$bgcolor.'">';
					    $start_date    = explode('-',$row_comp_data['start_date']);
						$data .='<div class="span6">
						<span class="head2" style="color:White"> Place: '.ucwords($row_comp_data['competition_place']).'</span><br>
						<span class="head2" style="color:White">Strat Date: '.@$start_date[2].' / '.@$start_date[1].' / '.@$start_date[0].'</span><br>
						
						';
						$data .='</div>';
						
						$end_date    = explode('-',$row_comp_data['end_date']);
						$data .='<div class="span6">
						<span class="head2" style="color:White">Team Limit :'.ucwords($row_comp_data['competition_limit']).'</span><br>
						<span class="head2" style="color:White">End Date: '.@$end_date[2].' / '.@$end_date[1].' / '.@$end_date[0].'</span><br>
						';
						$data .='</div>';
						
					$data .='</div>';
				$data .='</div>';
			$data .='</div>';
			
			$data .='<div class="span2">';
				
			$data .='</div>';
			
			$data .='</div>';// control-group end
			
			//==================End : Heading  ===========================================//
			
			
			
			//==================Start : Coaches  ===========================================//
			$sql_get_stud  = "SELECT * FROM tbl_competition_team  as tct ";
			$sql_get_stud .= " INNER JOIN tbl_team as  tm ON tct.team_id =tm.team_id ";
			$sql_get_stud .= " WHERE competition_id='".$competition_id."'";
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
						$data .='<h5>Teams : </h5>';
					$data .='</div>';
				$data .='</div>';
				
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px">';
						$data .='<ul style="list-style:none">';
						for($i=0;$i<$num_get_stud1;$i++)
						{
							$data .='<li class="head2" style="padding:10px"><img src="images/team/small/'.$res_array[$i]['team_logo'].'" style="width:100px; height:100px"  alt="">
							&nbsp;&nbsp;<a href="view_team.php?pag=Teams&team_id='.$res_array[$i]['team_id'].'" target="_blank" >'.ucwords($res_array[$i]['team_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px;">';
						$data .='<ul style="list-style:none">';
						for($i=$i;$i<$num_get_stud;$i++)
						{
							$data .='<li class="head2">
							<img src="images/team/small/'.$res_array[$i]['team_logo'].'"  style="width:100px; height:100px" > 
							&nbsp;&nbsp;<a href="view_team.php?pag=Teams&team_id='.$res_array[$i]['team_id'].'" target="_blank" > '.ucwords($res_array[$i]['team_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='</div>';// control-group
				//==================End : Coaches  ===========================================//
			}
			
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
	$competition_id			= $obj->competition_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$data['competition_status']      = $curr_status;
	$data['modified_by']             = $logged_uid;
	$data['modified_date']           = $datetime;
	$res = update('tbl_competition',$data,array('competition_id'=>$competition_id));
	
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
if((isset($obj->delete_competition)) == "1" && isset($obj->delete_competition))
{
	$response_array   = array();		
	$ids 	  = $obj->batch;
	$del_flag 		  = 0; 
	foreach($ids as $id)	
	{
		$sql_delete_comp	= " DELETE FROM `tbl_competition` WHERE `competition_id` = '".$id."' ";
		$res_delete_comp	= mysqli_query($db_con,$sql_delete_comp) or die(mysqli_error($db_con));			
		if($res_delete_comp)
		{
			$sql_delete_team	= " DELETE FROM `tbl_competition_team` WHERE `competition_id` = '".$id."' ";
		    $res_delete_team	= mysqli_query($db_con,$sql_delete_team) or die(mysqli_error($db_con));			
			
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

if((isset($obj->getTeam)) == "1" && isset($obj->getTeam))
{
	$competition_id   =       $obj->competition;
	$data ='';
	$data .='<input type="hidden" name="competition_id" id="competition_id" value="'.$competition_id.'">';
	$data .='<input type="hidden" name="addTeam" id="addTeam" value="1">';
	$data .='<div style="padding:15px;text-align:center">';
	
	$sql_get_team    = " SELECT * FROM tbl_competition WHERE competition_id ='".$competition_id."' ";
	$res_get_team    = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
	$row_get_team    = mysqli_fetch_array($res_get_team);
	
	$sql_get_student   = " SELECT * FROM tbl_team WHERE team_status = 1";
	$sql_get_student  .= " AND team_id NOT IN(SELECT DISTINCT(team_id) FROM tbl_competition_team WHERE competition_id='".$competition_id."')";
	$res_get_student   = mysqli_query($db_con,$sql_get_student) or die(mysqli_error($db_con));
	$data .= '<select multiple="multiple"  onChange="console.log($(this).children(":selected").length)" placeholder="Select Team"  style="width:70%"  name="team_id[]"  id="team_id" class="select2-me input-large" data-rule-required="true">';

	foreach($res_get_student as $row)
	{
		$data .='<option value="'.$row['team_id'].'">'.ucwords($row['team_name']).'</option>';
	}
	$data .= '</select> ';
	
	$data .= '<input value="Add Team" id="" class="btn-success"  type="submit">';
	
	$data .= '</div> ';
	
	$data .= '<script type="text/javascript">';
	$data .= '$("#team_id").select2();';
	$data .= '</script>';
	
	
	$sql_get_student  = " SELECT tm.team_name,tm.team_id as team,tct.* FROM tbl_competition_team  as tct  ";
	$sql_get_student .= " INNER JOIN tbl_team as tm ON tct.team_id = tm.team_id ";
	$sql_get_student .= " WHERE   ";
	$sql_get_student .= "  competition_id='".$competition_id."'";
	$res_get_student  = mysqli_query($db_con,$sql_get_student) or die(mysqli_error($db_con));
	
	if(mysqli_num_rows($res_get_student)!=0)
	{
	
		$data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
		$data .= '<thead>';
		$data .= '<tr>';
		$data .= '<th style="text-align:center">Sr No.</th>';
		$data .= '<th style="text-align:center">Team Name</th>';
		$delete = checkFunctionalityRight("view_competition.php",2);
		if($delete)
		{			
			$data .= '<th style="text-align:center">
			<div style="text-align:center">';
			$data .= '<input type="button"  value="Delete" onclick="multipleTeamDelete('.$competition_id.');" class="btn-danger"/>
			</div></th>';
		}
		$data .= '</tr>';
		$data .= '</thead>';
		$data .= '<tbody>';
		
		while($row_load_data = mysqli_fetch_array($res_get_student))
		{
			$data .= '<tr>';				
			$data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
			$data .= '<td style="text-align:center"><a target="_blank" href="view_team.php?pag=Teams&team_id='.$row_load_data['team'].'"  >'.ucwords($row_load_data['team_name']).'</a></td>';
			$delete = checkFunctionalityRight("view_competition.php",2);
			if($delete)
			{					
				$data .= '<td><div class="controls" align="center">';
				$data .= '<input type="checkbox" value="'.$row_load_data['id'].'" id="team_batch'.$row_load_data['id'].'" name="team_batch'.$row_load_data['id'].'" class="css-checkbox team_batch">';
				$data .= '<label for="team_batch'.$row_load_data['id'].'" class="css-label"></label>';
				$data .= '</div></td>';										
			}
			$data .= '</tr>';															
		}	
		$data .= '</tbody>';
		$data .= '</table>';
	}
	
	quit(array($data,$row_get_team['team_name']),1);
}



if((isset($_POST['addTeam'])) == "1" && isset($_POST['addTeam']))
{
	$competition_id       =  $_POST['competition_id'];  
	$team_ids             =  $_POST['team_id'];
	$add_flag             =  0;
	
	$row       = checkExist('tbl_competition',array('competition_id'=>$competition_id));
	
	
	
	foreach($team_ids as $team_id)
	{
		$teamCount = isExist('tbl_competition_team',array('competition_id'=>$competition_id));
		if($row['competition_limit'] <= $teamCount)
		{
			quit(array('Team limit exceed...!',$competition_id),1);
		}
		$data['competition_id']          = $competition_id;
		$data['team_id']                 = $team_id;
		$data['competition_created']     = $datetime;
		$data['competition_created_by']  = $uid;
		insert('tbl_competition_team',$data);
		$add_flag         =  1;
	}
	
	if($add_flag == 1)
	{
		quit(array('Team added successfully',$competition_id),1);
	}
	else
	{
		quit('Something went wrong...');
	}
}


if((isset($obj->remove_team)) == "1" && isset($obj->remove_team))
{
	$team_batch   = $obj->team_batch;
	$delete_flag   = 0;
	foreach($team_batch as $id)
	{
		$sql_delete = " DELETE FROM tbl_competition_team WHERE id='".$id."' ";
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
