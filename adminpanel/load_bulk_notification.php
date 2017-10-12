<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

if((isset($obj->searchStudent)) == "1" && isset($obj->searchStudent))
{

  $search_key  = mysqli_real_escape_string($db_con,$obj->search_key);
  
  $sql_get_student  = "SELECT * FROM tbl_students WHERE student_status=1 ";
  $sql_get_student .= " and (student_name like '%".$search_key."%' or student_email = '".$search_key."' ";
  $sql_get_student .= " or student_mobile = '".$search_key."'  or student_gender = '".$search_key."') ";	
  $res_get_student  = mysqli_query($db_con,$sql_get_student) or die(mysqli_error($db_con));
  if(mysqli_num_rows($res_get_student)==0)
  {
	  quit('No Matches Found....!');
  }
  $data ='';
  while($row =mysqli_fetch_array($res_get_student))
  {
	 $data.= '<div style="float:left;width:33.33%;">';
		$data.= '<input type="radio" id="chk'.$row['student_id'].'" name="studentid" class="chkuserid" value="'.$row['student_id'].'" onClick="chkbox(this.value);" data-rule-required-"true">';
				$data.= '<b> '.ucwords(strtolower($row['student_name'])).'</b><br>';
				$data.= '<div style="font-size:12px;padding-left:15px;">';
				$data.= $row['student_mobile'];
					$data.= '<br>';
					if($row['student_email'] != '')
					{
						$data.= $row['student_email'];	
					}
					else
					{
						$data.= 'Not Found';
					}
				$data.= '</div>';
		$data.= '</div>';
  }
		
  quit($data,1);
}
// ==========================================================================

if(isset($_POST['notification_type']) && $_POST['notification_type']!="")
{
	$notification_type = $_POST['notification_type'];
	$message           = $_POST['msg1'];
	$sms               = 0;
	$email             = 0;
	$all               = 0;
    $success_count     = 0;
	
	if($notification_type=="All")
	{
		$sms   =1;
		$email =1;
		$all   =1;
		
	}elseif($notification_type=="SMS")
	{
		$sms   =1;
	}elseif($notification_type=="SMS")
	{
		$email =1;
	}
	
	$competion_id = $_POST['competion_id'];
	$batch_id     = $_POST['batch_id'];
	
	
	$sql_load_data  = " SELECT ts.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_modified_by) AS name_midified_by 
							FROM `tbl_students` AS ts";
	$sql_load_data  .= " INNER JOIN tbl_address_master as tam ON ts.student_id=tam.add_user_id ";			
							
	$sql_load_data .=" WHERE add_user_type='student'";	
	//===========Filter By area====================================//
	
	if($batch_id !="")
	{
		$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_student_batches WHERE batch_id='".$batch_id."') ";
	}
	
	if($competition_id !="")
	{
		$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_student_competition WHERE competition_id='".$competition_id."') ";
	}
	if(strcmp($utype,'1') !== 0)
	{
		$sql_load_data  .= " AND student_created_by='".$logged_uid."' ";
	}
	$res_get_count = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
	$num_get_count = mysqli_num_rows($res_get_count);
	if($num_get_count!=0)
	{
		while($student_row=mysqli_fetch_array($res_get_count))
		{
			$student_mobile  = $student_row['student_mobile'];
	        $student_email   = $student_row['student_email'];
			if($sms==1)
			{
				$noti['type']			= 'SMS Bulk Notification';
				$noti['message']		= htmlspecialchars($message, ENT_QUOTES);
				$noti['user_email']		= $student_email;
				$noti['user_mobile_num']= $student_mobile;
				$noti['created_date']	= $datetime;
				$noti['created_by']	= $uid;
				$noti_data	= insert('tbl_notification',$noti);
				
				$success_count++;
			}
			
			if($email==1)
			{
				$noti['type']			= 'Email Bulk Notification';
				$noti['message']		= htmlspecialchars($message, ENT_QUOTES);
				$noti['user_email']		= $student_email;
				$noti['user_mobile_num']= $student_mobile;
				$noti['created_date']	= $datetime;
				$noti_data	= insert('tbl_notification',$noti);
				$noti['created_by']	= $uid;
				mail($student_email,"My subject",$message);
				$success_count++;
			}
		}
	}
	
	if($success_count==0)
	{
		quit('Notification Sending failed...!');
	}
	else
	{
		quit('Notification Successfully Sent...!',1);
	}
	
	
}

if(isset($obj->getBatch) && $obj->getBatch==1)
{
	$competion_id = $obj->competion_id;
	
	$data ='<option value="">Select Batch</option>';
	
	$sql_get_batch = " SELECT * FROM tbl_batches WHERE competition_id='".$competion_id."'";
	$res_get_batch = mysqli_query($db_con,$sql_get_batch) or die(mysqli_error($db_con));
	while($row =mysqli_fetch_array($res_get_batch))
	{
		$data .='<option value="'.$row['batch_id'].'">'.$row['batch_name'].'</option>';
	}
	
	if($data !='')
	{
		quit($data,1);
	}
	else
	{
		$data ='<option value="">Select Batch</option>';
		quit($data,1);
	}
}


if(isset($obj->getArea) && $obj->getArea==1)
{
	$city_id = $obj->city_id;
	
	
	$data ='<option value="">Select Area</option>';
	$sql_get_batch = " SELECT * FROM tbl_area WHERE area_city='".$city_id."'";
	$res_get_batch = mysqli_query($db_con,$sql_get_batch) or die(mysqli_error($db_con));
	while($row =mysqli_fetch_array($res_get_batch))
	{
		$data .='<option value="'.$row['area_id'].'">'.$row['area_name'].'</option>';
	}
	
	if($data !='')
	{
		quit($data,1);
	}
	else
	{
		$data ='<option value="">Select Area</option>';
		quit($data,1);
	}
}

if(isset($obj->getCount) && $obj->getCount==1)
{
	$competion_id = $obj->competion_id;
	$batch_id     = $obj->batch_id;
	
	
	$sql_load_data  = " SELECT ts.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_modified_by) AS name_midified_by 
							FROM `tbl_students` AS ts";
		$sql_load_data  .= " INNER JOIN tbl_address_master as tam ON ts.student_id=tam.add_user_id ";			
							
		$sql_load_data .=" WHERE add_user_type='student'";	
		//===========Filter By area====================================//
		
		if($batch_id !="")
		{
			$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_student_batches WHERE batch_id='".$batch_id."') ";
		}
		
		if($competition_id !="")
		{
			$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_student_competition WHERE competition_id='".$competition_id."') ";
		}
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND student_created_by='".$logged_uid."' ";
		}
		
	
		 $res_get_count = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
		 $num_get_count = mysqli_num_rows($res_get_count);
		 quit($num_get_count,1);
}

?>
