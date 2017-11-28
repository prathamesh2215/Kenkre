<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

if((isset($obj->load_competition)) == "1" && isset($obj->load_competition))
{
	$response_array = array();	
	$competition_id	    = $obj->competition_id;
	//$coach_id 		= $obj->coach_id;	
	$team_id		= $obj->team_id;
	$end_date	    = $obj->end_date;	
	$start_date	    = $obj->start_date;
	
	
	$sql_load_competition = " SELECT * FROM `tbl_competition` AS tc ";
	$sql_load_competition .=" WHERE 1=1 " ;	
	//===========Filter By area====================================//
	
	/*if($coach_id !="")
	{
		$sql_load_competition .=" AND tc.competition_id IN(SELECT DISTINCT(competition_id) FROM tbl_coach_competition WHERE coach_id='".$coach_id."') ";
	}*/
	
	if(strcmp($utype,'1') !== 0)
	{
		$sql_load_competition  .= " AND created_by='".$logged_uid."' ";
	}
	
	if($start_date!='')
	{
		
		$start_date          = explode('-',$start_date);// d/m/y
		$start_date          = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];//y/m/d
		$sql_load_competition  .= " AND start_date >='".$start_date."' ";
	}
	
	if($end_date!='')
	{
		$end_date            = explode('-',$end_date);// d/m/y
		$end_date            = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];//y/m/d
		$sql_load_competition  .= " AND end_date <='".$end_date."' ";
	}
	
	if($competition_id !='')
	{
		$sql_load_competition .=" AND competition_id ='".$competition_id."' ";
	}
	
	if($team_id !='')
	{
		$sql_load_competition .=" AND competition_id IN (SELECT DISTINCT(competition_id) FROM tbl_competition_team WHERE team_id ='".$team_id."') ";
	}	

	/*$sql_load_data  = " SELECT * FROM `tbl_students` AS ts ";
	$sql_load_data .=" WHERE 1=1 " ;	
	$sql_load_data .=" AND student_id IN (SELECT DISTINCT(student_id) FROM tbl_team_students WHERE team_id IN";
	$sql_load_data .=" ( SELECT DISTINCT team_id FROM tbl_competition_team WHERE competition_id IN (".$sql_load_competition.") )";
	$sql_load_data .=" ) ";
	
	if($team_id !='')
	{
		$sql_load_data .=" AND student_id IN (SELECT DISTINCT(student_id) FROM tbl_team_students WHERE team_id ='".$team_id."') ";
	}*/
	//quit($sql_load_data);
	$result_load_data = mysqli_query($db_con,$sql_load_competition) or die(mysqli_error($db_con));	
	$data_count       = mysqli_num_rows($result_load_data);
	
	if($data_count != 0)
	{		
		$student_data  = "";	
		$student_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
		$student_data .= '<thead>';
		
		$student_data .= '<tr>';
		$student_data .= '<th style="text-align:center">Sr No.</th>';
		$student_data .= '<th style="text-align:center">Competition Name</th>';
		$student_data .= '<th style="text-align:center">Start Date</th>';
		$student_data .= '<th style="text-align:center">End Date</th>';
		$student_data .= '<th style="text-align:center">Teams</th>';
		
		$student_data .= '</tr>';
		$student_data .= '</thead>';
		$student_data .= '<tbody>';
		while($row_load_data = mysqli_fetch_array($result_load_data))
		{
			$student_data .= '<tr>';				
			$student_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
		
			$student_data .= '<td style="text-align:center">
			<input value="'.ucwords($row_load_data['competition_name']).'" class="btn-link" id="'.$row_load_data['competition_id'].'" onclick="viewStudnets('.$row_load_data['competition_id'].');" type="button">
			</td>';
			
			$start_date    = explode('-',$row_load_data['start_date']);
			$student_data .= '<td style="text-align:center">'.$start_date[2].'-'.$start_date[1].'-'.$start_date[0].'</td>';
			
			$end_date    = explode('-',$row_load_data['end_date']);
			$student_data .= '<td style="text-align:center">'.$end_date[2].'-'.$end_date[1].'-'.$end_date[0].'</td>';
		   
			
			$sql_get_batch  =" SELECT team_name FROM tbl_team WHERE team_id IN";
			$sql_get_batch .="(SELECT DISTINCT team_id FROM tbl_competition_team WHERE competition_id='".$row_load_data['competition_id']."')";
			if($team_id !='')
			{
				$sql_get_batch .=" AND team_id='".$team_id."' ";
			}
			$res_get_batch  = mysqli_query($db_con,$sql_get_batch) or die(mysqli_error($db_con));
			$b_arr =array();
			while($row = mysqli_fetch_array($res_get_batch))
			{
				array_push($b_arr,ucwords($row['team_name']));
			}
			$student_data .= '<td style="text-align:center">'.implode(',',$b_arr).'</td>';
			//=================Team End=================================//
			$student_data .= '</tr>';															
		}	
		$student_data .= '</tbody>';
		$student_data .= '</table>';	
		
		$response_array = array("Success"=>"Success","resp"=>$student_data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Data Available");
	}
	
	echo json_encode($response_array);	
}


if((isset($obj->load_students)) == "1" && isset($obj->load_students))
{
	$response_array     = array();	
	$competition_id	    = $obj->competition_id;
	
		
	$sql_load_data  = " SELECT * FROM `tbl_students` AS ts ";
	$sql_load_data .="  WHERE 1=1 " ;	
	$sql_load_data .="  AND student_id IN (
									SELECT DISTINCT(student_id) FROM tbl_team_students WHERE team_id IN ";
	$sql_load_data .=" (SELECT DISTINCT(team_id) FROM tbl_competition_team WHERE competition_id='".$competition_id."') ";
    $sql_load_data .=" ) ";
	//quit($sql_load_data);
	$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
	
	$sql_get_competition  ="SELECT competition_name FROM tbl_competition WHERE competition_id='".$competition_id."'";
	$res_get_competition = mysqli_query($db_con,$sql_get_competition) or die(mysqli_error($db_con));
	$row_get_competition = mysqli_fetch_array($res_get_competition);
	
	if(mysqli_num_rows($res_get_competition) != 0)
	{		
		$student_data  = "";	
		$student_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
		$student_data .= '<thead>';
		$student_data .= '<tr>';
		$student_data .= '<td colspan="5"><h4>'.$row_get_competition['competition_name'].'</h4></td>';
		$student_data .= '</tr>';
		$student_data .= '<tr>';
		$student_data .= '<th style="text-align:center">Sr No.</th>';
		$student_data .= '<th style="text-align:center">Student Name</th>';
		$student_data .= '<th style="text-align:center">Gender</th>';
		$student_data .= '<th style="text-align:center">Email</th>';
		$student_data .= '<th style="text-align:center">Mobile</th>';
		$student_data .= '</tr>';
		$student_data .= '</thead>';
		$student_data .= '<tbody>';
		while($row_load_data = mysqli_fetch_array($result_load_data))
		{
			$student_data .= '<tr>';				
			$student_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
		
			$student_data .= '<td style="text-align:center">'.ucwords($row_load_data['student_fname']).' '.ucwords($row_load_data['student_mname']).' '.ucwords($row_load_data['student_lname']).'</td>';
				$student_data .= '<td style="text-align:center">'.$row_load_data['student_gender'].'</td>';
			
			$student_data .= '<td style="text-align:center">'.$row_load_data['student_email'].'</td>';	
			$student_data .= '<td style="text-align:center">'.$row_load_data['student_mobile'].'</td>';					
			
			$student_data .= '</tr>';															
		}	
		$student_data .= '</tbody>';
		$student_data .= '</table>';	
		$student_data .= $data_count;
		$response_array = array("Success"=>"Success","resp"=>$student_data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Data Available");
	}
	
	echo json_encode($response_array);	
}


?>
