<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


//------------------This is used for inserting records---------------------
if((isset($_POST['insert_team'])) == "1" && isset($_POST['insert_team']))
{
	$data['team_name']                   = strtolower(mysqli_real_escape_string($db_con,$_POST['team_name']));
	$data['short_color']                 = strtolower(mysqli_real_escape_string($db_con,$_POST['short_color']));
	$data['socks_color']                 = strtolower(mysqli_real_escape_string($db_con,$_POST['socks_color']));
	$data['team_created']                = $datetime;
	$data['team_created_by']             = $uid;
	$data['team_status']                 = mysqli_real_escape_string($db_con,$_POST['team_status']);
	$data['team_limit']                  = mysqli_real_escape_string($db_con,$_POST['team_limit']);
	
	
	if($_FILES['team_logo']['name'] !="" && isset($_FILES['team_logo']['name']))
	{
		$imagedata = getimagesize($_FILES['team_logo']['tmp_name']);
		if($imagedata[0] <500 )
		{
			quit('Logo width should be greater than 500');
		}
		$team_logo                  = explode('.',$_FILES['team_logo']['name']);
		$team_logo                  = 'l'.date('dhyhis').'.'.$team_logo[1];
		$data['team_logo']          = $team_logo;
		
		$dir                         ='images/team/';
		if(!move_uploaded_file($_FILES['team_logo']['tmp_name'],$dir.$team_logo))
		{
			quit('Team Logo not uploaded please try letter...!');
		}
		make_thumb($dir.$team_logo,$dir.'small/'.$team_logo,100,100);
		make_thumb($dir.$team_logo,$dir.'medium/'.$team_logo,300,300);
	}
	else
	{
		quit('Team Logo is required...!');
	}
	
	if($_FILES['team_jercy']['name'] !="" && isset($_FILES['team_jercy']['name']))
	{
		$imagedata = getimagesize($_FILES['team_jercy']['tmp_name']);
		if($imagedata[0] <500 )
		{
			quit('Jercy width should be greater than 500');
		}
		$team_jercy                  = explode('.',$_FILES['team_jercy']['name']);
		$team_jercy                  = date('dhyhis').'.'.$team_jercy[1];
		$data['team_jercy']          = $team_jercy;
		
		$dir                         ='images/team/';
		if(!move_uploaded_file($_FILES['team_jercy']['tmp_name'],$dir.$team_jercy))
		{
			quit('Team Jercy not uploaded please try letter...!');
		}
		make_thumb($dir.$team_jercy,$dir.'small/'.$team_jercy,100,100);
		make_thumb($dir.$team_jercy,$dir.'medium/'.$team_jercy,300,300);
	}
	else
	{
		quit('Team Jercy is required...!');
	}
	
	$sql_check             = " SELECT * FROM tbl_team WHERE team_name='".$data['team_name']."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = insert('tbl_team',$data); 
			if($insert_id)
			{
				quit('Team added successfully...!',1);
			}
			else
			{
				quit('Team not added...!');
			}
		    
	}
	else
	{
		quit('Team Name already Exist...!');
	}	
}


if((isset($_POST['update_team'])) == "1" && isset($_POST['update_team']))
{
	$data['team_name']                   = strtolower(mysqli_real_escape_string($db_con,$_POST['team_name']));
	$data['short_color']                 = strtolower(mysqli_real_escape_string($db_con,$_POST['short_color']));
	$data['socks_color']                 = strtolower(mysqli_real_escape_string($db_con,$_POST['socks_color']));
	$data['team_modified']               = $datetime;
	$data['team_modified_by']            = $uid;
	$data['team_status']                 = mysqli_real_escape_string($db_con,$_POST['team_status']);
	$data['team_limit']                  = mysqli_real_escape_string($db_con,$_POST['team_limit']);
	$team_id                             = mysqli_real_escape_string($db_con,$_POST['team_id']);
	
	
	//=======================End : Image and Doc Upload End=====================================//
	$sql_get_team             = " SELECT * FROM tbl_team WHERE team_id ='".$team_id."' ";
	$res_get_team              = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
	$row_get_team              = mysqli_fetch_array($res_get_team);
	
	if($_FILES['team_logo']['name'] !="" && isset($_FILES['team_logo']['name']))
	{
		$imagedata = getimagesize($_FILES['team_logo']['tmp_name']);
		if($imagedata[0]<500 )
		{
			quit('Logo width should be greater than 500');
		}
		$team_logo                  = explode('.',$_FILES['team_logo']['name']);
		$team_logo                  = 'l'.date('dhyhis').'.'.$team_logo[1];
		$dir                         ='images/team/';
		
		if(move_uploaded_file($_FILES['team_logo']['tmp_name'],$dir.$team_logo))
		{
			$data['team_logo']         = $team_logo;
			
			$res = make_thumb($dir.$team_logo,$dir.'small/'.$team_logo,100,100);
			$res = make_thumb($dir.$team_logo,$dir.'medium/'.$team_logo,200,200);
			
			unlink($dir.$team_logo);
			
			
			unlink($dir.'small/'.$row_get_team['team_logo']);
			unlink($dir.'medium/'.$row_get_team['team_logo']);
		}
	}
	
	
	if($_FILES['team_jercy']['name'] !="" && isset($_FILES['team_jercy']['name']))
	{
		$imagedata = getimagesize($_FILES['team_jercy']['tmp_name']);
		if($imagedata[0]<500 )
		{
			quit('Jercy width should be greater than 500');
		}
		$team_jercy                  = explode('.',$_FILES['team_jercy']['name']);
		$team_jercy                  = date('dhyhis').'.'.$team_jercy[1];
		$dir                         ='images/team/';
		
		if(move_uploaded_file($_FILES['team_jercy']['tmp_name'],$dir.$team_jercy))
		{
			$res = make_thumb($dir.$team_jercy,$dir.'small/'.$team_jercy,100,100);
			$res = make_thumb($dir.$team_jercy,$dir.'medium/'.$team_jercy,200,200);
			$data['team_jercy']       = $team_jercy;
			unlink($dir.$team_jercy);
			
			
		}
	}
	
	//=======================End : Image and Doc Upload End=====================================//
	
	$sql_check             = " SELECT * FROM tbl_team WHERE team_name like '".$data['team_name']."' AND team_id !='".$team_id."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = update('tbl_team',$data,array('team_id'=>$team_id)); 
		    
			if($insert_id)
			{
				quit('Team updated successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
		
	}
	else
	{
		quit('Team Name already Exist...!');
	}	
}


if((isset($obj->load_team_parts)) == "1" && isset($obj->load_team_parts))
{
	$team_id         = $obj->team_id;
	$req_type        = $obj->req_type;
	$response_array  = array();
	if($req_type != "")
	{
		$disabled='';
		if($team_id != "" && $req_type != "add")
		{
			$sql 	            = "Select * from tbl_team where team_id = '".$team_id."' ";
			$res    	        = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			$row_student_data	= mysqli_fetch_array($res);	
			if($req_type=="view")
			{
				$disabled           ='disabled';
			}
		}	
			
		$data = '';
		
		if($team_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" name="team_id" id="team_id" value="'.$team_id.'">';
			$data .= '<input type="hidden" name="update_team" id="update_team" value="1">';
		}        
		
		
		if($req_type=='add')
		{
				$data .= '<input type="hidden" name="insert_team" id="insert_team" value="1">';
		}
		//////=============================================Start : Team Name======================================
		
		if($req_type !='view')
		{
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Team Name';
			$data .= '<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input onkeypress="return charsonly(event);" type="text" id="team_name" name="team_name" class="input-large keyup-char" ';
			$data .= ' '.$disabled.' placeholder="Team Name"   value="'.@$row_student_data['team_name'].'"'; 
			
			$data .= '/>';
			$data .= '</div>';
			$data .= '</div> <!-- Team Name -->';
			
			//////=============================================Start : Team Logo======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Team Logo';
			$data .= '<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<img src="images/team/medium/'.@$row_student_data['team_logo'].'" width="200px"  id="team_logo" name="team_jercy_p" class="" alt=""><br>';
			
					
			$data .= '<input accept="image/jpeg,image/jpg,image/png"  type="file" id="team_logo" name="team_logo" class="input-large keyup-char team_logo" ';
			if($req_type=='add')
			{
				$data .=' data-rule-required="true" ';
			}
			$data .= '/>';
			$data .='<ul class="css-ul-list" style="color:red">
						   <li>Only "jpg" , "png" or "jpeg" image will be accepted.</li>
						   <li>Image width should be  greater than \'500\'   pixel.</li>
						</ul>';
			$data .= '</div>';
			$data .= '</div> <!-- Team Logo -->';
			$data .="<script type=\"text/javascript\">	$('.team_logo').change(function(){
			readURL(this);
			});
		  </script>";
			//////=============================================Start :  Team Jercy======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Team Jercy';
			$data .= '<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<img src="images/team/medium/'.@$row_student_data['team_jercy'].'" width="200px" id="team_jercy" name="team_jercy_p" class="" alt=""><br>';
			
			$data .= '<input accept="image/jpeg,image/jpg,image/png"  type="file" id="team_jercy" name="team_jercy" class="input-large keyup-char team_jercy" ';
			if($req_type=='add')
			{
				$data .=' data-rule-required="true" ';
			}
			$data .= '/>';
			$data .='<ul class="css-ul-list" style="color:red">
						   <li>Only "jpg" , "png" or "jpeg" image will be accepted.</li>
						   <li>Image width should be   greater than \'500\'   pixel.</li>
						</ul>';
			$data .= '</div>';
			$data .= '</div> <!-- Team Jercy -->';
			$data .="<script type=\"text/javascript\">	$('.team_jercy').change(function(){
			readURL(this);
			});
		  </script>";
		  
		    //////=============================================Start :  Team Limit======================================
		    $data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Student Limit';
			$data .= '<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input  type="number" id="team_limit" min=1 name="team_limit" class="input-large keyup-char" ';
			$data .= ' '.$disabled.' placeholder="Student Limit"   value="'.@$row_student_data['team_limit'].'"'; 
			
			$data .= '/>';
			$data .= '</div>';
			$data .= '</div> <!-- Team Name -->';
			
			//////=============================================Start :  Shorts======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Shorts Color';
			$data .= '<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input onkeypress="return charsonly(event);" type="text" id="short_color" name="short_color" class="input-large keyup-char" ';
			$data .= ' '.$disabled.' placeholder="Enter Short Color e.g. Blue" data-rule-required="true"  value="'.@$row_student_data['short_color'].'"'; 
			$data .= '/>';
			$data .= '</div>';
			$data .= '</div> <!-- Short Color -->';
			
			//////=============================================Start :  Socks======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Socks Color';
			$data .= '<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input onkeypress="return charsonly(event);" type="text" id="socks_color" name="socks_color" class="input-large keyup-char" ';
			$data .= ' '.$disabled.' placeholder="Enter Socks Color e.g. White " data-rule-required="true"  value="'.@$row_student_data['socks_color'].'"'; 
			$data .= '/>';
			$data .= '</div>';
			$data .= '</div> <!-- Socks Color -->';
			
			
			//////=============================================Start :  Status======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
			$data .= '<div class="controls">';
			
			if($team_id != "" && $req_type == "view")
			{
				if($row_get_add['team_status'] == 1)
				{
					$data .= ' <label class="control-label" style="color:#30DD00"> Active </label>';
				}
				if($row_get_add['team_status'] == 0)
				{
					$data .= ' <label class="control-label" style="color:#E63A3A"> Inactive </label>';
				}
			}
			else
			{  
			  if($team_id != "" && $req_type == "edit")
			  {
					$data  .= '<input type="radio" name="team_status" value="1" class="css-radio" data-rule-required="true" ';
					$dis	= checkFunctionalityRight("view_team.php",3);
					if(!$dis)
					{
					//$data .= ' disabled="disabled" ';
					}
					if($row_student_data['team_status'] == 1)
					{
						$data .= 'checked ';
					}
					$data .= '> Active ';
					$data .= '<input type="radio" name="team_status" value="0" class="css-radio" data-rule-required="true"';
					if(!$dis)
					{
					//$data .= ' disabled="disabled" ';
					}
					if($row_student_data['team_status'] == 0  )
					{
						$data .= 'checked ';
					}
					$data .= '> Inactive';
				} 
				else  
				{
					$data .= ' <input type="radio" name="team_status" value="1" class="css-radio" data-rule-required="true" ';
					$data .= '> Active ';
					$data .= ' <input type="radio" name="team_status" value="0" class="css-radio" data-rule-required="true"';
				
					$data .= '> Inactive ';
				}
			}
			$data .= '<label for="radiotest" class="css-label"></label>';
			$data .= '<label name = "radiotest" ></label>';
			$data .= '</div>';
			$data .= '</div><!--Status-->';
		
			$data .= '<div class="form-actions">';
			if($team_id == "" && $req_type == "add")
			{
				$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Team</button>';			
			}
			elseif($team_id != "" && $req_type == "edit")
			{
				$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Team</button>';			
			}			
			$data .= '</div> <!-- Save and cancel -->';
		}
		else
		{
			//==================Start :  Heading  ===========================================//
			if($row_student_data['team_status']==1)
			{
				$color = '#18BB7C';
			}
			else
			{
				$color = '#da4f49';
			}
			
			$data .='<div class="control-group" style="background-color:'.$color.'">';
			
			$data .='<div class="span4">';
				$data .='<div style="padding:20px">';
					$data .='<img style="width:200px;height:200px" src="images/team/medium/'.$row_student_data['team_logo'].'">';
				$data .='</div>';
			$data .='</div>';
			
			$data .='<div class="span4">';
			    $data .='<div style="padding:20px;">';
					$data .='<h3  style="text-align:center;color:white">'.ucwords($row_student_data['team_name']).'</h3>';
					$data .='<div class="control-group" style="background-color:'.$color.'">';
						$data .='<div class="span4 text-center"><span class="head2" style="color:white">Team Limit<br>'.ucwords($row_student_data['team_limit']).'</span>';
						$data .='</div>';
						$data .='<div class="span4 text-center"><span class="head2" style="color:white">Short Color<br>'.ucwords($row_student_data['short_color']).'</span>';
						$data .='</div>';
						$data .='<div class="span4 text-center"><span class="head2" style="color:white">Socks Color<br>'.ucwords($row_student_data['socks_color']).'</span>';
						$data .='</div>';
					$data .='</div>';
				$data .='</div>';
			$data .='</div>';
			
			$data .='<div class="span4" >';
				$data .='<div style="padding:20px; float:right">';
					$data .='<img style="width:200px ;height:200px" src="images/team/medium/'.$row_student_data['team_jercy'].'" alt="">';
				$data .='</div>';
			$data .='</div>';
			
			$data .='</div>';// control-group end
			
			//==================End : Heading  ===========================================//
			
			
			
			//==================Start : Coaches  ===========================================//
			 $sql_get_stud  = "SELECT * FROM tbl_team_coach  as ttc ";
			$sql_get_stud .= " INNER JOIN tbl_cadmin_users as  tcu ON ttc.coach_id =tcu.id ";
			$sql_get_stud .= " WHERE team_id='".$team_id."'";
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
			
			$sql_get_stud  = "SELECT * FROM tbl_team_students  as tts ";
			$sql_get_stud .= " INNER JOIN tbl_students as  ts ON tts.student_id =ts.student_id ";
			$sql_get_stud .= " WHERE team_id='".$team_id."'";
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
						$data .='<h5> Students ('.$num_get_stud.')</h5>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='<div class="span4" style="clear:both">';
					$data .='<div style="padding:20px">';
						$data .='<ul>';
						for($i=0;$i<$num_get_stud1;$i++)
						{
							$data .='<li class="head2"><a href="view_student.php?pag=Students&student_id='.$res_array[$i]['student_id'].'" target="_blank" >'.ucwords($res_array[$i]['student_fname']).' '.ucwords($res_array[$i]['student_lname']).'';
							
							$num = isExist('tbl_team_students',array('team_id'=>$team_id,"student_id"=>$res_array[$i]['student_id'],"isCaptain"=>1));
							if($num!=0)
							{
								$data .=' ( Captain )';
							}
							
							$data .='</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px;">';
						$data .='<ul>';
						for($i=$i;$i<$num_get_stud;$i++)
						{
							$data .='<li class="head2"><a href="view_student.php?pag=Students&student_id='.$res_array[$i]['student_id'].'" target="_blank" >'.ucwords($res_array[$i]['student_fname'].' '.$res_array[$i]['student_lname']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='</div>';// row end
			}
			//==================End : Student  ===========================================//
			
			//==================Start : Participation  ===========================================//
			
			$sql_get_comp  = "SELECT * FROM tbl_competition_team  as tct ";
			$sql_get_comp .= " INNER JOIN tbl_competition as  tc ON tct.competition_id =tc.competition_id ";
			$sql_get_comp .= " WHERE team_id='".$team_id."'";
			$res_get_comp  = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
			$num_get_comp  = mysqli_num_rows($res_get_comp);
			$comp_array     = array();
			while($comp_row = mysqli_fetch_array($res_get_comp))
			{
				array_push($comp_array,$comp_row);
			}
			$num_get_comp1 = round(($num_get_comp)/2);
			$num_get_comp2 = $num_get_comp - $num_get_comp1;
			
			if(!empty($comp_array))
			{
				$data .='<div class="control-group">';
			
				$data .='<div class="span12">';
					$data .='<div style="padding:20px">';
						$data .='<h5> Competition ('.$num_get_comp.')</h5>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='<div class="span4" style="clear:both">';
					$data .='<div style="padding:20px">';
						$data .='<ul>';
						for($i=0;$i<$num_get_comp1;$i++)
						{
							$data .='<li class="head2"><a href="view_competition.php?pag=Competitions&competition_id='.$comp_array[$i]['competition_id'].'" target="_blank" >'.ucwords($comp_array[$i]['competition_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px;">';
						$data .='<ul>';
						for($i=$i;$i<$num_get_comp;$i++)
						{
							$data .='<li class="head2"><a href="view_competition.php?pag=Competitions&competition_id='.$comp_array[$i]['competition_id'].'" target="_blank" >'.ucwords($comp_array[$i]['competition_name']).' </a></li>';
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

if((isset($obj->load_team)) == "1" && isset($obj->load_team))
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
		
		
			
		$sql_load_data  = " SELECT tm.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tm.team_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tm.team_modified_by) AS name_midified_by 
							FROM `tbl_team` AS tm";
			
							
		$sql_load_data .=" WHERE 1=1";	
		//===========Filter By area====================================//
		
		
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND team_created_by='".$logged_uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (team_name like '%".$search_text."%' or short_color like '%".$search_text."%' ";
			$sql_load_data .= " or socks_color like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY team_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$team_data  = "";	
			$team_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$team_data .= '<thead>';
    	  	$team_data .= '<tr>';
         	$team_data .= '<th style="text-align:center">Sr No.</th>';
			$team_data .= '<th style="text-align:center">Team Name</th>';
			$team_data .= '<th style="text-align:center">Team Logo</th>';
			$team_data .= '<th style="text-align:center">Coaches</th>';
			$team_data .= '<th style="text-align:center">Students</th>';
			$team_data .= '<th style="text-align:center">Created Date</th>';
			$team_data .= '<th style="text-align:center">Created By</th>';
			$team_data .= '<th style="text-align:center">Modified Date</th>';
			$team_data .= '<th style="text-align:center">Modified By</th>';
			
			$dis = checkFunctionalityRight("view_team.php",3);
			if($dis)
			{			
				$team_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_team.php",1);
		    if($edit)
			{			
				$team_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_team.php",2);
			if($delete)
			{			
				$team_data .= '<th style="text-align:center"><div style="text-align:center">';
				$team_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$team_data .= '</tr>';
      		$team_data .= '</thead>';
      		$team_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$team_data .= '<tr>';				
				$team_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
			
				$team_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['team_name']).'" ';
				$team_data .= 'class="btn-link" id="'.$row_load_data['team_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				
				$team_data .= '<td style="text-align:center">';
				if($row_load_data['team_logo']!='')
				{
					$team_data .='
					<img src="images/team/small/'.$row_load_data['team_logo'].'" style="width:50px;height:50px">
					';
				}
				$team_data .=$row_load_data['student_email'];
				$team_data .=	'</td>';
				
				$coach_num = isExist('tbl_team_coach',array('team_id'=>$row_load_data['team_id']));
				$team_data .= '<td style="text-align:center">';
				$team_data .= '<input type="button" value=" '.$coach_num.' Coaches" id="'.$row_load_data['team_id'].'" class="btn-warning" onclick="viewCoach(this.id);"></td>';		
				
				$stud_num   = isExist('tbl_team_students',array('team_id'=>$row_load_data['team_id']));
				$team_data .= '<td style="text-align:center">';
				$team_data .= '<input type="button" value="'.$stud_num.' Students" id="'.$row_load_data['team_id'].'" class="btn-warning" onclick="viewStudent(this.id);">  </td>';		
				$team_created = strtotime($row_load_data['team_created']);
	            $team_data .= '<td style="text-align:center">'.date(' j M, Y, g : i a',$team_created).'</td>';	
				//$team_data .= '<td style="text-align:center">'.$row_load_data['team_created'].'</td>';
				$team_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$team_modified = strtotime($row_load_data['team_modified']);
	            $team_data .= '<td style="text-align:center">'.date(' j M, Y, g : i a',$team_modified).'</td>';	
				//$team_data .= '<td style="text-align:center">'.$row_load_data['team_modified'].'</td>';
				$team_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_team.php",3);
				
			
				if($dis)
				{					
					$team_data .= '<td style="text-align:center">';					
					if($row_load_data['team_status'] == 1)
					{
						$team_data .= '<input type="button" value="Active" id="'.$row_load_data['team_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$team_data .= '<input type="button" value="Inactive" id="'.$row_load_data['team_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$team_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_team.php",1);
				
				if($edit)
				{				
					$team_data .= '<td style="text-align:center">';
					$team_data .= '<input type="button" value="Edit" id="'.$row_load_data['team_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_team.php",2);
				
				if($delete)
				{					
					$team_data .= '<td><div class="controls" align="center">';
					$team_data .= '<input type="checkbox" value="'.$row_load_data['team_id'].'" id="batch'.$row_load_data['team_id'].'" name="batch'.$row_load_data['team_id'].'" class="css-checkbox batch">';
					$team_data .= '<label for="batch'.$row_load_data['team_id'].'" class="css-label"></label>';
					$team_data .= '</div></td>';										
				}
	          	$team_data .= '</tr>';															
			}	
      		$team_data .= '</tbody>';
      		$team_data .= '</table>';	
			$team_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$team_data);				
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

//---------------This is used for status change--------------------------------
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{ 
	$team_id		        = $obj->id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$data['team_status']      = $curr_status;
	$data['team_modified_by'] = $logged_uid;
	$data['team_modified']    = $datetime;
	$res = update('tbl_team',$data,array('team_id'=>$team_id));
	
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
if((isset($obj->delete_team)) == "1" && isset($obj->delete_team))
{
	$response_array   = array();		
	$team_ids 	      = $obj->batch;
	$del_flag 		  = 0; 
	foreach($team_ids as $team_id)	
	{
		$sql_get_team       = " SELECT * FROM tbl_team WHERE team_id='".$team_id."' ";
		$res_get_team       = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
		$row_get_team       = mysqli_fetch_array($res_get_team);
		
		$sql_delete_team	= " DELETE FROM `tbl_team` WHERE `team_id` = '".$team_id."' ";
		$res_delete_team	= mysqli_query($db_con,$sql_delete_team) or die(mysqli_error($db_con));			
		if($res_delete_team)
		{
			
			unlink('images/team/small/'.$row_get_team['team_logo']);
			unlink('images/team/medium/'.$row_get_team['team_logo']);
			unlink('images/team/small/'.$row_get_team['team_jercy']);
			unlink('images/team/medium/'.$row_get_team['team_jercy']);
			
			$del_flag = 1;
			$sql_delete_stud	= " DELETE FROM `tbl_team_students` WHERE `team_id` = '".$team_id."' ";
		    $res_delete_stud	= mysqli_query($db_con,$sql_delete_stud) or die(mysqli_error($db_con));	
			
			$sql_delete_coach	= " DELETE FROM `tbl_team_coach` WHERE `team_id` = '".$team_id."' ";
		    $res_delete_coach	= mysqli_query($db_con,$sql_delete_coach) or die(mysqli_error($db_con));
			
			$sql_delete_comp	= " DELETE FROM `tbl_competition_team` WHERE `team_id` = '".$team_id."' ";
		    $res_delete_comp	= mysqli_query($db_con,$sql_delete_comp) or die(mysqli_error($db_con));
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

if((isset($obj->getCoach)) == "1" && isset($obj->getCoach))
{
	$team_id   =       $obj->team_id;
	$data ='';
	$data .='<input type="hidden" name="team_id" id="team_id" value="'.$team_id.'">';
	$data .='<input type="hidden" name="addCoach" id="addCoach" value="1">';
	$data .='<div style="padding:15px;text-align:center">';
	
	$sql_get_team    = " SELECT * FROM tbl_team WHERE team_id ='".$team_id."' ";
	$res_get_team    = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
	$row_get_team    = mysqli_fetch_array($res_get_team);
	
	$sql_get_coach   = " SELECT * FROM tbl_cadmin_users WHERE utype=15 ";
	$sql_get_coach  .= " AND id NOT IN(SELECT DISTINCT(coach_id) FROM tbl_team_coach WHERE team_id='".$team_id."') AND status=1";
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
	
	
	$sql_get_coach  = " SELECT tcu.fullname,tcu.id as coach_id,ttc.* FROM tbl_team_coach  as ttc  ";
	$sql_get_coach .= " INNER JOIN tbl_cadmin_users as tcu ON ttc.coach_id = tcu.id ";
	$sql_get_coach .= " WHERE   ";
	$sql_get_coach .= "  team_id='".$team_id."'";
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
			$data .= '<input type="button"  value="Delete" onclick="multipleCoachDelete('.$team_id.');" class="btn-danger"/>
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
	
	quit(array($data,ucwords($row_get_team['team_name'])),1);
}

if((isset($obj->getStudent)) == "1" && isset($obj->getStudent))
{
	$team_id   =       $obj->team_id;
	$data ='';
	$data .='<input type="hidden" name="team_id" id="team_id" value="'.$team_id.'">';
	$data .='<input type="hidden" name="addStudent" id="addStudent" value="1">';
	$data .='<div style="padding:15px;text-align:center">';
	
	$sql_get_team    = " SELECT * FROM tbl_team WHERE team_id ='".$team_id."' ";
	$res_get_team    = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
	$row_get_team    = mysqli_fetch_array($res_get_team);
	
	$sql_get_student   = " SELECT * FROM tbl_students WHERE student_status=1";
	$sql_get_student  .= " AND student_id NOT IN(SELECT DISTINCT(student_id) FROM tbl_team_students WHERE team_id='".$team_id."')";
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
	
	
	$sql_get_student  = " SELECT ts.student_fname,ts.student_mname,ts.student_lname,ts.student_id,tts.* FROM tbl_team_students  as tts  ";
	$sql_get_student .= " INNER JOIN tbl_students as ts ON tts.student_id = ts.student_id ";
	$sql_get_student .= " WHERE   ";
	$sql_get_student .= "  team_id='".$team_id."'";
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
			$data .= '<input type="button"  value="Delete" onclick="multipleStudentDelete('.$team_id.');" class="btn-danger"/>
			</div></th>';
			$data .= '<th style="text-align:center">Captain</th>';
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
				$data .= '<td style="text-align:center">
				<input type="radio" name="captain" value="'.$row_load_data['id'].'" ';
				if($row_load_data['isCaptain']==1)
				{
					$data .=' checked="checked" ';
				}
				$data .=' onclick="selectCaptain('.$row_load_data['id'].','.$row_load_data['team_id'].')" />
				</td>';								
			}
			$data .= '</tr>';															
		}	
		$data .= '</tbody>';
		$data .= '</table>';
	}
	
	quit(array($data,ucwords($row_get_team['team_name'])),1);
}

if((isset($_POST['addCoach'])) == "1" && isset($_POST['addCoach']))
{
	$data['team_id']  =  $_POST['team_id'];  
	$coach_ids        =  $_POST['coach_id'];
	$add_flag         =  0;
	foreach($coach_ids as $coach_id)
	{
		$data['coach_id']          = $coach_id;
		$data['coach_created']     = $datetime;
		$data['coach_created_by']  = $uid;
		$data['team_coach_status'] = 1;
		insert('tbl_team_coach',$data);
		$add_flag         =  1;
	}
	
	if($add_flag == 1)
	{
		quit($data['team_id'],1);
	}
	else
	{
		quit('something went wrong...');
	}
}

if((isset($_POST['addStudent'])) == "1" && isset($_POST['addStudent']))
{
	$team_id          =  $_POST['team_id'];  
	$student_ids      =  $_POST['student_id'];
	$add_flag         =  0;
	
	$row       = checkExist('tbl_team',array('team_id'=>$team_id));
	
	$ar = array();
	foreach($student_ids as $student_id)
	{
		$studCount = isExist('tbl_team_students',array('team_id'=>$team_id));
		array_push($ar,$studCount);
		if($row['team_limit'] <= $studCount)
		{
			quit(array('Student limit exceed...!',$team_id),1);
		}
		else
		{
			$data['team_id']			 = $team_id;
			$data['student_id']          = $student_id;
			$data['student_created']     = $datetime;
			$data['student_created_by']  = $uid;
			$data['team_student_status'] = 1;
			insert('tbl_team_students',$data);
			$add_flag         =  1;
		}
		
	}
	
	if($add_flag == 1)
	{
		
		quit(array('Student added successfully',$data['team_id']),1);
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
		$sql_delete = " DELETE FROM tbl_team_coach WHERE id='".$id."' ";
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
		$sql_delete = " DELETE FROM tbl_team_students WHERE id='".$id."' ";
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



if((isset($obj->selectCaptain)) == "1" && isset($obj->selectCaptain))
{
	$id      = $obj->id;
	$team_id = $obj->team_id;
	
	update('tbl_team_students',array("isCaptain"=>0),array("team_id"=>$team_id));
	update('tbl_team_students',array("isCaptain"=>1),array("id"=>$id,"team_id"=>$team_id));
	quit('',1);
}
?>
