<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

if((isset($obj->load_notification)) == "1" && isset($obj->load_notification))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$type 		    = $obj->type;	
	$start_date 	= $obj->start_date;	
	$end_date 		= $obj->end_date;	
	
	$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
		
		$sql_load_data  = " SELECT * FROM `tbl_notification` AS tn ";
		$sql_load_data .= " INNER JOIN tbl_students as ts ON  tn.user_email = ts.student_email";
		$sql_load_data .=" WHERE 1=1 " ;	
		//===========Filter By area====================================//
		
		if($start_date!='')
		{
			
			$start_date          = explode('-',$start_date);// d/m/y
			$start_date          = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];//y/m/d
			$sql_load_data  .= " AND created_date >='".$start_date." 00:00:00' ";
		}
		
		if($end_date!='')
		{
			$end_date            = explode('-',$end_date);// d/m/y
			$end_date            = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];//y/m/d
			$sql_load_data      .= " AND created_date <='".$end_date." 29:59:59' ";
		}
		
		if($type=="")
		{
			$sql_load_data      .= " AND (type like 'SMS Notification' or type like 'Email Notification' ";
			$sql_load_data      .= " or type like 'Email Bulk Notification' or type like 'SMS Bulk Notification' ) ";
		}
		else
		{
			$sql_load_data      .= " AND (type like '".$type." Notification' or type like'".$type." Bulk Notification' )";
		}
		
		if($search_text != "")
		{
			$sql_load_data .= " and (user_email like '%".$search_text."%' or user_mobile_num like '%".$search_text."% ' ";
			$sql_load_data .= " or message like '%".$search_text."%' student_name like '%".$search_text."%' ) ";	
		}
		//quit($sql_load_data);
	//	$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY id DESC  ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$notification_data  = "";	
			$notification_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$notification_data .= '<thead>';
    	  	$notification_data .= '<tr>';
         	$notification_data .= '<th style="text-align:center">Sr No.</th>';
			$notification_data .= '<th style="text-align:center">Student Name</th>';
			$notification_data .= '<th style="text-align:center">Mobile Number</th>';
			$notification_data .= '<th style="text-align:center">Message</th>';
			$notification_data .= '<th style="text-align:center">Type</th>';
			
			$notification_data .= '<th style="text-align:center">Date</th>';
			$notification_data .= '</tr>';
      		$notification_data .= '</thead>';
      		$notification_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$notification_data .= '<tr>';				
				$notification_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
			
				$notification_data .= '<td style="text-align:center">'.ucwords($row_load_data['student_fname']).' '.ucwords($row_load_data['student_mname']).' '.ucwords($row_load_data['student_lname']).'</td>';
					$notification_data .= '<td style="text-align:center">'.$row_load_data['student_mobile'].'</td>';
				$notification_data .= '<td style="text-align:center">'.$row_load_data['message'].'</td>';		
				if($type !="")
				{
					$notification_data .= '<td style="text-align:center">'.$type.'</td>';		
				}
				elseif($row_load_data['type']=='SMS Notification' || $row_load_data['type']=='SMS Bulk Notification')
				{
					$notification_data .= '<td style="text-align:center">SMS</td>';		
				}
				else
				{
					$notification_data .= '<td style="text-align:center">Email</td>';		
				}
				
				$notification_data .= '<td style="text-align:center">'.$row_load_data['created_date'].'</td>';		
				$notification_data .= '</tr>';															
			}	
      		$notification_data .= '</tbody>';
      		$notification_data .= '</table>';	
			$notification_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$notification_data);				
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
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
