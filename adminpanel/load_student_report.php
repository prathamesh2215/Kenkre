<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

//==========================================================================//
//------------------this is used for inserting records---------------------

function exportToXlsx($main_data)
{
	//$response_array	= array("Success"=>"fail", "resp"=>"No Data");	
	//return $response_array;exit();
	include_once("xlsxwriter.class.php");
	global 			$db_con;
	$header			= array();
	
	
	$header = array(
			'Student Id'=>'integer',		
			'Name'=>'string',						
			'Date of Birth'=>'string',			
			'Email'=>'string',
			'Mobile'=>'string',			
			'Gender'=>'string',
			'Center'=>'string'
		);
		
	
		$writer 			= new XLSXWriter();
		$writer->setAuthor('Satish Dhere');
		$writer->writeSheet($main_data,'Sheet1',$header);
		$timestamp			= date('mdYhis', time());
		if(!file_exists("download/student"))
		{
			mkdir("download/student");
		}
		$writer->writeToFile('download/student/student'.$timestamp.'.xlsx');
		
		$response_array	= array("Success"=>"Success", "resp"=>'download/student/student'.$timestamp.'.xlsx');
	
	
	quit('download/student/student'.$timestamp.'.xlsx',1);

}


//==========================================================================//
//------------------Strat load Student---------------------------------------
if((isset($obj->load_student)) == "1" && isset($obj->load_student))
{
	$response_array = array();	
	$start_offset   = 0;
	
	
	$center_id 		= $obj->ft_center;	
	$team_id		= $obj->ft_team;
	$type	        = $obj->ft_type;	
	$age_group      = $obj->ft_age;
	
	
	if($age_group !="")
	{
	    date_default_timezone_set('Asia/Kolkata'); //required if not set
		$date = date('m/d/Y');
		
		$date = new DateTime($date);
		$date->modify('-'.$age_group.' year');
		$age_group = $date->format('Y-m-d');
	//quit($a);
	}
	
		
	
			
		$sql_load_data  = " SELECT ts.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_modified_by) AS name_midified_by 
							FROM `tbl_students` AS ts";
		$sql_load_data  .= " INNER JOIN tbl_address_master as tam ON ts.student_id=tam.add_user_id ";			
							
		$sql_load_data .=" WHERE add_user_type='student'";	
		//===========Filter By area====================================//
		if($center_id!="")
		{
			$sql_load_data .=" AND ts.batch_center ='".$center_id."' ";
		}
		
		if($team_id !="")
		{
			$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_team_students WHERE team_id='".$team_id."') ";
		}
		
		if($type !="")
		{
			$sql_load_data .=" AND ts.student_type ='".$type."' ";
		}
		
		
		if($age_group!="")
		{
			$sql_load_data .=" AND ts.student_dob > '".$age_group."' ";
		}
		
		
		if($coach_id !='')
		{
			$sql_load_data .=" AND ts.student_id IN (SELECT DISTINCT(student_id) FROM tbl_student_competition WHERE  ";
			$sql_load_data .=" competition_id IN (SELECT DISTINCT(competition_id) FROM tbl_coach_competition WHERE  ";
			$sql_load_data .=" coach_id='".$coach_id."'))";
		}
		
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND student_created_by='".$logged_uid."' ";
		}
		
		$sql_load_data   .= " ORDER BY student_id DESC  ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$student_data  = '<a href="javascript:void(0);" id="a_export" onclick="downld1();" style="float:right;margin-bottom:10px"><img src="images/Excelicon.png" width="20" height="20"></a>';	
			$student_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$student_data .= '<thead>';
    	  	$student_data .= '<tr>';
         	$student_data .= '<th style="text-align:center">Sr No.</th>';
			$student_data .= '<th style="text-align:center">Image</th>';
			$student_data .= '<th style="text-align:center">Student Name</th>';
			$student_data .= '<th style="text-align:center">Date of Birth</th>';
			$student_data .= '<th style="text-align:center">Email</th>';
			$student_data .= '<th style="text-align:center">Mobile Number</th>';
			$student_data .= '<th style="text-align:center">Gender</th>';
			
      		$student_data .= '</thead>';
      		$student_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$student_data .= '<tr>';				
				$student_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
		     	$student_data .= '<td style="text-align:center">';
				
				if($row_load_data['profile_img']!="")
				{
					$student_data .=' <img src="images/students_img/small/'.$row_load_data['profile_img'].'" alt="No Image" width="50px">';
				}
				else
				{
					$student_data .='<img style="width:60px" src="img/person.jpg" alt="">';
				}
				$student_data .='</td>';	
				$student_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['student_fname']).' '.ucwords($row_load_data['student_lname']).'" class="btn-link" id="'.$row_load_data['student_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				
				$date = strtotime($row_load_data['student_dob']);
	            $student_data .= '<td style="text-align:center">'.date(' j M, Y',$date).'</td>';
				//$student_data .= '<td style="text-align:center">'.$row_load_data['student_dob'].'</td>';
				$student_data .= '<td style="text-align:center">'.$row_load_data['student_email'].'</td>';
				
				$student_data .= '<td style="text-align:center">'.$row_load_data['student_mobile'].'</td>';			
				$student_data .= '<td style="text-align:center">'.$row_load_data['student_gender'].'</td>';
																			
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

//---------------This is used for status change--------------------------------
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$id				= $obj->id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$data['student_status']      = $curr_status;
	$data['student_modified_by'] = $logged_uid;
	$data['student_modified']    = $datetime;
	$res = update('tbl_students',$data,array('student_id'=>$id));
	
	if($res)
	{
		$adata['add_status']      = $curr_status;
		$adata['add_modified_by'] = $logged_uid;
		$adata['add_modified']    = $datetime;
		$res = update('tbl_address_master',$adata,array('add_user_id'=>$id,'add_user_type'=>'student'));
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
if((isset($obj->delete_student)) == "1" && isset($obj->delete_student))
{
	$response_array   = array();		
	$student_ids 	  = $obj->batch;
	$del_flag 		  = 0; 
	
	$row = checkExist('tbl_students' ,array('student_id'=>$student_id));
	
	foreach($student_ids as $student_id)	
	{
		$sql_delete_area	= " DELETE FROM `tbl_students` WHERE `student_id` = '".$student_id."' ";
		$result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));			
		if($result_delete_area)
		{
			
			
			$del_flag = 1;
			$sql_delete_area	= " DELETE FROM `tbl_address_master` WHERE `add_user_id` = '".$student_id."' AND add_user_type='student' ";
		    $result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));
				
			//=================Start to delete from batch table=====================================//
			$sql_delete_batch	= " DELETE FROM `tbl_batch_students` WHERE `student_id` = '".$student_id."' ";
		    $res_delete_batch	= mysqli_query($db_con,$sql_delete_batch) or die(mysqli_error($db_con));	
			
			//=================Start to delete from team table=====================================//
			$sql_delete_comp	= " DELETE FROM `tbl_team_students` WHERE `student_id` = '".$student_id."' ";
		    $res_delete_comp	= mysqli_query($db_con,$sql_delete_comp) or die(mysqli_error($db_con));
			
			unlink('images/students_img/'.$row['profile_img']);
			unlink('images/students_img/small/'.$row['profile_img']);
			unlink('images/students_doc/'.$row['students_doc']);
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
		quit('Please Select City...!');
	}
	
	$data ='<option value="">Select City</option>';
	
	$sql =" SELECT * FROM tbl_area WHERE area_status =1 AND area_city='".$city_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
		quit('Area not found...!');
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

if((isset($obj->getCenter)) == "1" && isset($obj->getCenter))
{
	$type_id 	      = $obj->type_id;
	$data  ='<option value="">Select Center</option>';
	$data .= getList('tbl_centers','center_id','center_name','',array('center_status'=>1));
	quit($data,1);
}

if(isset($obj->excelDownload) && $obj->excelDownload==1)
{
	$center_id 		= $obj->ft_center;	
	$team_id		= $obj->ft_team;
	$type	        = $obj->ft_type;	
	$age_group      = $obj->ft_age;
	
	
	if($age_group !="")
	{
	    date_default_timezone_set('Asia/Kolkata'); //required if not set
		$date = date('m/d/Y');
		
		$date = new DateTime($date);
		$date->modify('-'.$age_group.' year');
		$age_group = $date->format('Y-m-d');
	//quit($a);
	}
	
	$sql_load_data  = " SELECT ts.*,tc.center_name,
							(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_created_by) AS name_created_by, 
							(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_modified_by) AS name_midified_by 
						FROM `tbl_students` AS ts";
	$sql_load_data  .= " INNER JOIN tbl_address_master as tam ON ts.student_id=tam.add_user_id ";			
	$sql_load_data .=" INNER JOIN tbl_centers as tc ON ts.batch_center =tc.center_id ";
	
					
	$sql_load_data .=" WHERE add_user_type='student'";	
	//===========Filter By area====================================//
	if($center_id!="")
	{
		$sql_load_data .=" AND ts.batch_center ='".$center_id."' ";
	}
	
	if($team_id !="")
	{
		$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_team_students WHERE team_id='".$team_id."') ";
	}
	
	if($type !="")
	{
		$sql_load_data .=" AND ts.student_type ='".$type."' ";
	}
	
	
	if($age_group!="")
	{
		$sql_load_data .=" AND ts.student_dob > '".$age_group."' ";
	}
	
	
	if($coach_id !='')
	{
		$sql_load_data .=" AND ts.student_id IN (SELECT DISTINCT(student_id) FROM tbl_student_competition WHERE  ";
		$sql_load_data .=" competition_id IN (SELECT DISTINCT(competition_id) FROM tbl_coach_competition WHERE  ";
		$sql_load_data .=" coach_id='".$coach_id."'))";
	}
	
	if(strcmp($utype,'1') !== 0)
	{
		$sql_load_data  .= " AND student_created_by='".$logged_uid."' ";
	}
	
	$sql_load_data   .= " ORDER BY student_id DESC  ";
	$result           = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
	
	$main_data        = array();
	
	while($row = mysqli_fetch_array($result))
	{	
		$name  = ucwords($row['student_fname']).' '.ucwords($row['student_lname']);
		$date  = strtotime($row_load_data['student_dob']);
		$date  = date(' j M, Y',$date);
		
		$data1 = array($row['student_id'],$name,$date,$row['student_email'],$row['student_mobile'],$row['student_gender'],$row['center_name']);
		
		array_push($main_data, $data1);
		
	}
		
	exportToXlsx($main_data);
	

}
?>
