<?php
include("include/db_con.php");
include("include/query-helper.php");
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
  $sql_get_student .= " and (student_mname like '%".$search_key."%' or student_lname like '%".$search_key."%' or student_fname like '%".$search_key."%' or student_email = '".$search_key."' ";
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
				$data.= '<b> '.ucwords(strtolower($row['student_fname'])).' '.ucwords(strtolower($row['student_mname'])).' '.ucwords(strtolower($row['student_lname'])).'</b><br>';
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
	$studentid         = $_POST['studentid'];
	
	if(!isset($_POST['studentid']))
	{
		quit('Please select student...!');
	}
	$message           = $_POST['msg1'];
	$sms               = 0;
	$email             = 0;
	$all               = 0;
	$sql_get_studnet = "SELECT * FROM tbl_students WHERE student_id='".$studentid."'";
	$res_get_student = mysqli_query($db_con,$sql_get_studnet) or die(mysqli_error($db_con));
	$row_get_student = mysqli_fetch_array($res_get_student);
	
	$student_mobile  = $row_get_student['student_mobile'];
	$student_email   = $row_get_student['student_email'];
	
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
	
	if($sms==1)
	{
		$noti['type']			= 'SMS Notification';
		$noti['message']		= htmlspecialchars($message, ENT_QUOTES);
		$noti['user_email']		= $student_email;
		$noti['user_mobile_num']= $student_mobile;
		$noti['created_date']	= $datetime;
		$noti['created_by']	= $uid;
		$noti_data	= insert('tbl_notification',$noti);
		
		if($all==0)
		{
			quit('SMS sent successfully...!',1);
		}
	}
	
	if($email==1)
	{
		$noti['type']			= 'Email Notification';
		$noti['message']		= htmlspecialchars($message, ENT_QUOTES);
		$noti['user_email']		= $student_email;
		$noti['user_mobile_num']= $student_mobile;
		$noti['created_date']	= $datetime;
		$noti_data	= insert('tbl_notification',$noti);
		$noti['created_by']	= $uid;
		mail($student_email,"My subject",$message);
		quit('Email sent successfully...!',1);
	}
	
	
}



?>
