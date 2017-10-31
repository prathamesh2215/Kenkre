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
	$type              = $_POST['type'];
	if(isset($_POST['batch']))
	{
		$batch             = $_POST['batch'];
	}
	if(isset($_POST['team']))
	{
		$team             = $_POST['team'];
	}
	if(isset($_POST['comp']))
	{
		$comp             = $_POST['comp'];
	}
	
	
	if($type=='all')
	{
		$sql= " SELECT * FROM tbl_students WHERE student_status = 1";
	}
	if($type=='batch')
	{
		$sql  = " SELECT * FROM tbl_students WHERE student_status = 1 AND student_id IN ( ";
		$sql .= " SELECT DISTINCT(student_id) FROM tbl_batch_students WHERE 1=1 ";
		if(!empty($batch))
		{
			$sql .= " batch_id IN(".implode(',',$batch).")";
		}
		
		$sql .= " )";
	}
	
	
	if($type=='competition' || $type=='team')
	{
		$sql  = " SELECT * FROM tbl_students WHERE 1=1 ";
		if(!empty($comp))
		{
			$sql .=" AND student_id IN (SELECT DISTINCT(student_id) FROM tbl_team_students WHERE 1= 1  ";
			if(!empty($team))
			{
				$sql .=" AND team_id IN(".implode(',',$team).")";
			}
			$sql .=" AND  team_id IN( ";
		    $sql .=" SELECT DISTINCT(team_id) FROM tbl_competition_team WHERE competition_id IN (".implode(',',$comp).")))";
		}
		
		if(!empty($team) && empty($comp))
		{
			$sql .=" AND student_id IN (SELECT DISTINCT(student_id) FROM tbl_team_students WHERE 1= 1 AND team_id IN(".implode(',',$team)."))";
		}
		
	}
	
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
	
	
	$res_get_count = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
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
				//mail($student_email,"My subject",$message);
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

if(isset($obj->getType) && $obj->getType==1)
{
	$type    = mysqli_real_escape_string($db_con,$obj->type);
	$data    ='';
	if($type =='competition')
	{
		$sql_get_comp  =" SELECT * FROM tbl_competition WHERE competition_id IN (SELECT DISTINCT(competition_id) FROM tbl_competition_team WHERE team_id IN( ";
		$sql_get_comp .=" SELECT DISTINCT(team_id) FROM tbl_team_students ))";
		
		$res_get_comp  = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
		if(mysqli_num_rows($res_get_comp)!=0)
		{
			
			$data .='<div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid  #8f8f8f;margin-right:10px;margin-top:10px;">';
			$data .='<h6>Competitions</h6>';
			$data .='	  <input value="" id="acomp" onclick="checkCheckBoxes(\'acomp\');getData(\'team\')" name="acomp" class="css-checkbox "  type="checkbox">All
			<label for="acomp" class="css-label"></label>';
			
			while($row_comp = mysqli_fetch_array($res_get_comp))
			{
				$data .='	  <input value="'.$row_comp['competition_id'].'" id="comp'.$row_comp['competition_id'].'" onclick="getData(\'team\');unCheck(\'team'.$row_comp['competition_id'].'\',\'acomp\')" name="comp[]" class="css-checkbox acomp"   type="checkbox">'.ucwords($row_comp['competition_name']).'
			<label for="comp'.$row_comp['competition_id'].'" class="css-label"></label>';
			}
			
				
	     $data .=' </div>';
		 quit($data,1);
		}
		else
		{
			quit('');
		}
		
	}
	
	if($type =='team')
	{
		$sql_get_comp  =" SELECT * FROM tbl_team WHERE team_id IN (SELECT DISTINCT(team_id) FROM tbl_competition_team ) AND team_status=1 ";
		
		$res_get_comp  = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
		if(mysqli_num_rows($res_get_comp)!=0)
		{
			
			$data .='<div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid  #8f8f8f;margin-right:10px;margin-top:10px;">';
			$data .='<h6>Teams</h6>';
			$data .='	  <input value="" id="ateam" onclick="checkCheckBoxes(\'ateam\');" name="ateam" class="css-checkbox"  type="checkbox">All
			<label for="ateam" class="css-label"></label>';
			
			while($row_comp = mysqli_fetch_array($res_get_comp))
			{
				$data .='	  <input value="'.$row_comp['team_id'].'" id="team'.$row_comp['team_id'].'" onclick="unCheck(\'team'.$row_comp['team_id'].'\',\'ateam\')"  name="team[]" class="css-checkbox ateam"  type="checkbox">'.ucwords($row_comp['team_name']).'
			<label for="team'.$row_comp['team_id'].'" class="css-label"></label>';
			}
			
		 $data .=' </div>';
		 quit($data,1);
		}
		else
		{
			quit('dsdsf');
		}
		
	}
	
	if($type =='batch')
	{
		$sql_get_comp  =" SELECT * FROM tbl_batches WHERE batch_id IN (SELECT DISTINCT(batch_id) FROM tbl_batch_students ) AND batch_status =1 ";
		
		$res_get_comp  = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
		if(mysqli_num_rows($res_get_comp)!=0)
		{
			
			$data .='<div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid  #8f8f8f;margin-right:10px;margin-top:10px;">';
			$data .='<h6>Training Batches</h6>';
			$data .='	  <input value="" id="abatch" onclick="checkCheckBoxes(\'abatch\');" name="ateam" class="css-checkbox"  type="checkbox">All
			<label for="abatch" class="css-label"></label>';
			
			while($row_comp = mysqli_fetch_array($res_get_comp))
			{
				$data .='	  <input value="'.$row_comp['batch_id'].'" onclick="unCheck(\'team'.$row_comp['batch_id'].'\',\'abatch\')"  id="batch'.$row_comp['batch_id'].'"  name="batch[]" class="css-checkbox abatch"  type="checkbox">'.ucwords($row_comp['batch_name']).'
			<label for="batch'.$row_comp['batch_id'].'" class="css-label"></label>';
			}
			
		 $data .=' </div>';
		 quit($data,1);
		}
		else
		{
			quit('dsdsf');
		}
		
	}
	
	quit(' ',1);
}

if(isset($obj->getData) && $obj->getData==1)
{
	$type    = mysqli_real_escape_string($db_con,$obj->type);
	$comp    = $obj->comp;
	$data    ='';
	
	if($type =='team')
	{
		$sql_get_comp  =" SELECT * FROM tbl_team WHERE team_id IN (SELECT DISTINCT(team_id) FROM tbl_competition_team WHERE 1=1 ";
		if(!empty($comp))
		{
		 $sql_get_comp  .=" AND	competition_id IN(".implode(',',$comp)." ";
		 $sql_get_comp .=" )";
		
		}
		$sql_get_comp .=" )  AND team_status=1 "; 
		$res_get_comp  = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
		if(mysqli_num_rows($res_get_comp)!=0)
		{
			
			$data .='<div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid  #8f8f8f;margin-right:10px;margin-top:10px;">';
			$data .='<h6>Teams</h6>';
			$data .='	  <input value="" id="ateam" onclick="checkCheckBoxes(\'ateam\');" name="ateam" class="css-checkbox batch_levels levels_parent"  type="checkbox">All
			<label for="ateam" class="css-label"></label>';
			
			while($row_comp = mysqli_fetch_array($res_get_comp))
			{
				$data .='	  <input value="'.$row_comp['team_id'].'" onclick="unCheck(\'team'.$row_comp['team_id'].'\',\'ateam\')" id="team'.$row_comp['team_id'].'"  name="team[]" class="css-checkbox ateam"  type="checkbox">'.ucwords($row_comp['team_name']).'
			<label for="team'.$row_comp['team_id'].'" class="css-label"></label>';
			}
			
				
	     $data .=' </div>';
		 quit($data,1);
		}
		else
		{
			quit('');
		}
		
	}
	
	quit('');
}
?>
