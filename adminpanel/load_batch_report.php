<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

if((isset($obj->load_competition)) == "1" && isset($obj->load_competition))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= $obj->search_text;	
	
	
	$coach_id 		= $obj->coach_id;	
	$end_date	    = $obj->end_date;	
	$start_date	    = $obj->start_date;
	
	
		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
		
		
			
		$sql_load_data  = " SELECT * FROM `tbl_batches` AS tb ";
		$sql_load_data .=" WHERE 1=1 " ;	
		//===========Filter By area====================================//
		
		if($batch_id !="")
		{
			//$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_student_batches WHERE batch_id='".$batch_id."') ";
		}
		
		if($coach_id !="")
		{
			$sql_load_data .=" AND tb.batch_id IN(SELECT DISTINCT(batch_id) FROM tbl_coach_batches WHERE coach_id='".$coach_id."') ";
		}
		
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND created_by='".$logged_uid."' ";
		}
		
		if($start_date!='' )
		{
			
			$start_date          = explode('-',$start_date);// d/m/y
			$start_date          = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];//y/m/d
			$sql_load_data  .= " AND start_date >='".$start_date."'";
		}
		
		if($end_date!='')
		{
			$end_date            = explode('-',$end_date);// d/m/y
			$end_date            = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];//y/m/d
			$sql_load_data  .= " AND end_date <='".$end_date."' ";
		}
		
		if($search_text != "")
		{
			$sql_load_data .= " and (batch_name like '%".$search_text."%' or start_date = '".$search_text."' ";
			$sql_load_data .= " or end_date = '".$search_text."') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY batch_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$competition_data  = "";	
			$competition_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$competition_data .= '<thead>';
    	  	$competition_data .= '<tr>';
         	$competition_data .= '<th style="text-align:center">Sr No.</th>';
			$competition_data .= '<th style="text-align:center">Batch Name</th>';
			$competition_data .= '<th style="text-align:center">Strat Date</th>';
			$competition_data .= '<th style="text-align:center">End Date</th>';
			$competition_data .= '</tr>';
      		$competition_data .= '</thead>';
      		$competition_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$competition_data .= '<tr>';				
				$competition_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
			
				$competition_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['batch_name']).'" class="btn-link" id="'.$row_load_data['batch_id'].'" onclick="viewStudent(this.id,\'view\');"></td>';
					$competition_data .= '<td style="text-align:center">'.$row_load_data['start_date'].'</td>';
				$competition_data .= '<td style="text-align:center">'.$row_load_data['end_date'].'</td>';			
				$competition_data .= '</tr>';															
			}	
      		$competition_data .= '</tbody>';
      		$competition_data .= '</table>';	
			$competition_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$competition_data);				
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
if((isset($obj->load_student)) == "1" && isset($obj->load_student))
{
	$response_array = array();	
	$batch_id	    = $obj->batch_id;
	
		
	$sql_load_data  = " SELECT * FROM `tbl_students` AS ts ";
	$sql_load_data .=" WHERE 1=1 " ;	
	$sql_load_data .=" AND student_id IN (SELECT DISTINCT(student_id) FROM tbl_student_batches WHERE batch_id='".$batch_id."') ";
	$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
	
	$sql_get_competition  ="SELECT batch_name FROM tbl_batches WHERE batch_id='".$batch_id."'";
	$res_get_competition = mysqli_query($db_con,$sql_get_competition) or die(mysqli_error($db_con));
	$row_get_competition = mysqli_fetch_array($res_get_competition);
	if(strcmp($data_count,"0") !== 0)
	{		
		$student_data  = "";	
		$student_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
		$student_data .= '<thead>';
		$student_data .= '<tr>';
		$student_data .= '<td colspan="5"><h4>'.$row_get_competition['batch_name'].'</h4></td>';
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
		
			$student_data .= '<td style="text-align:center">'.ucwords($row_load_data['student_name']).'</td>';
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
