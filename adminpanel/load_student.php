<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


//------------------this is used for inserting records---------------------
if((isset($_POST['insert_student'])) == "1" && isset($_POST['insert_student']))
{
	$data['student_fname']             = mysqli_real_escape_string($db_con,$_POST['student_fname']);
	$data['student_mname']             = mysqli_real_escape_string($db_con,$_POST['student_mname']);
	$data['student_lname']             = mysqli_real_escape_string($db_con,$_POST['student_lname']);
	
	$data['birth_country']     = mysqli_real_escape_string($db_con,$_POST['birth_country']);//new
	$data['domicile_state']    = mysqli_real_escape_string($db_con,$_POST['domicile_state']);//new
	$data['stud_cat']          = mysqli_real_escape_string($db_con,$_POST['stud_cat']);//new
    $stud_joinig_date          = mysqli_real_escape_string($db_con,$_POST['stud_joinig_date']);
	$stud_joinig_date          = explode('-',$stud_joinig_date);// d/m/y
	$data['stud_joinig_date']  = $stud_joinig_date[2].'-'.$stud_joinig_date[1].'-'.$stud_joinig_date[0];
	$data['stud_bio']          = mysqli_real_escape_string($db_con,$_POST['stud_bio']);//new
	
	$data['student_email']     = mysqli_real_escape_string($db_con,$_POST['student_email']);
	$data['student_mobile']    = mysqli_real_escape_string($db_con,$_POST['student_mobile']);
	$data['student_gender']    = mysqli_real_escape_string($db_con,$_POST['student_gender']);
	$data['student_dob']       = mysqli_real_escape_string($db_con,$_POST['student_dob']);
	$data['student_status']    = mysqli_real_escape_string($db_con,$_POST['student_status']);
	$data['student_institute'] =  mysqli_real_escape_string($db_con,$_POST['student_institute']);
	
	if($_FILES['student_file']['name'] !="" && isset($_FILES['student_file']['name']))
	{
		$stud_photo                  = explode('.',$_FILES['student_file']['name']);
		$stud_photo                  = date('dhyhis').'.'.$stud_photo[1];
		$data['profile_img']         = $stud_photo;
		
		$dir                         ='images/students_img/'.$stud_photo;
		if(!move_uploaded_file($_FILES['student_file']['tmp_name'],$dir))
		{
			quit('Profile photo not uploaded please try letter...!');
		}
	}
	else
	{
		quit('Profile photo is required...!');
	}
	
	if($_FILES['student_id']['name'] !="" && isset($_FILES['student_id']['name']))
	{
		$stud_doc                  = explode('.',$_FILES['student_id']['name']);
		$stud_doc                  = date('dhyhis').'.'.$stud_doc[1];
		$data['student_doc']       = $stud_doc;
		
		$dir                         ='images/students_doc/'.$stud_doc;
		if(!move_uploaded_file($_FILES['student_id']['tmp_name'],$dir))
		{
			quit('Student Document not uploaded please try letter...!');
		}
	}
	else
	{
		quit('Student Document is required...!');
	}
	
	$data['student_created_by']    = $logged_uid;
	$data['student_created']       = $datetime;
	
	$sql_check             = " SELECT * FROM tbl_students WHERE student_email='".$data['student_email']."' or student_mobile='".$data['student_mobile']."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = insert('tbl_students',$data); 
		    
			if($insert_id)
			{
				$adata['add_details']    = mysqli_real_escape_string($db_con,$_POST['address']);
				$adata['add_state']      = mysqli_real_escape_string($db_con,$_POST['state_code']);
				$adata['add_city']       = mysqli_real_escape_string($db_con,$_POST['city']);
				$adata['add_area']       = mysqli_real_escape_string($db_con,$_POST['area']);
				$adata['add_status']     = mysqli_real_escape_string($db_con,$_POST['student_status']);
				$adata['add_user_id']    = $insert_id;
				$adata['add_user_type']  = 'student';
				$adata['add_created']    = $datetime;
				$adata['add_created_by'] = $logged_uid;
				$adata['add_id']         = getNewId('add_id','tbl_address_master');
				insert('tbl_address_master',$adata);
				//================Start insertion of batch and competiton==============//
				$batches                 = $_POST['batch'];
				foreach($batches as $batch)
				{
					$bdata['batch_id'] = $batch;
					$bdata['student_id'] = $insert_id;
					insert('tbl_student_batches',$bdata);
				}
				
				$competitions            = $_POST['comp'];
				foreach($competitions as $competition)
				{
					$ccdata['competition_id']    = $competition;
					$ccdata['student_id']          = $insert_id;
					insert('tbl_student_competition',$ccdata);
				}
				//================End insertion of batch and competiton==============//
				
				quit('Student Added Successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
			
		    
		
	}
	else
	{
		quit('Email or Mobile Number already Exist...!');
	}	
}


if((isset($_POST['update_student'])) == "1" && isset($_POST['update_student']))
{
	$data['student_fname']             = mysqli_real_escape_string($db_con,$_POST['student_fname']);
	$data['student_mname']             = mysqli_real_escape_string($db_con,$_POST['student_mname']);
	$data['student_lname']             = mysqli_real_escape_string($db_con,$_POST['student_lname']);
	
	$data['student_email']     = mysqli_real_escape_string($db_con,$_POST['student_email']);
	$data['student_mobile']    = mysqli_real_escape_string($db_con,$_POST['student_mobile']);
	$data['student_gender']    = mysqli_real_escape_string($db_con,$_POST['student_gender']);
	$data['student_dob']       = mysqli_real_escape_string($db_con,$_POST['student_dob']);
	$data['student_status']    = mysqli_real_escape_string($db_con,$_POST['student_status']);
	$data['student_institute'] =  mysqli_real_escape_string($db_con,$_POST['student_institute']);
	$student_id                = mysqli_real_escape_string($db_con,$_POST['student_id']);
	
	//=======================End : Image and Doc Upload End=====================================//
	$sql_get_stud              = " SELECT * FROM tbl_students WHERE student_id ='".$student_id."' ";
	$res_get_stud              = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
	$row_get_stud              = mysqli_fetch_array($res_get_stud);
	
	if($_FILES['student_file']['name'] !="" && isset($_FILES['student_file']['name']))
	{
		$stud_photo                  = explode('.',$_FILES['student_file']['name']);
		$stud_photo                  = date('dhyhis').'.'.$stud_photo[1];
		$dir                         ='images/students_img/';
		
		if(move_uploaded_file($_FILES['student_file']['tmp_name'],$dir.$stud_photo))
		{
			$data['profile_img']         = $stud_photo;
			unlink($dir.$row_get_stud['profile_img']);
		}
	}
	
	
	if($_FILES['student_id']['name'] !="" && isset($_FILES['student_id']['name']))
	{
		$stud_doc                  = explode('.',$_FILES['student_id']['name']);
		$stud_doc                  = date('dhyhis').'.'.$stud_doc[1];
		$dir                         ='images/students_doc/';
		
		if(move_uploaded_file($_FILES['student_id']['tmp_name'],$dir.$stud_doc))
		{
			$data['student_doc']       = $stud_doc;
			unlink($dir.$row_get_stud['student_doc']);
		}
	}
	
	//=======================End : Image and Doc Upload End=====================================//
	
	
	
	$data['birth_country']     = mysqli_real_escape_string($db_con,$_POST['birth_country']);//new
	$data['domicile_state']    = mysqli_real_escape_string($db_con,$_POST['domicile_state']);//new
	$data['stud_cat']          = mysqli_real_escape_string($db_con,$_POST['stud_cat']);//new
	$stud_joinig_date          = mysqli_real_escape_string($db_con,$_POST['stud_joinig_date']);
	$stud_joinig_date          = explode('-',$stud_joinig_date);// d/m/y
	$data['stud_joinig_date']  = $stud_joinig_date[2].'-'.$stud_joinig_date[1].'-'.$stud_joinig_date[0];
	
	$data['stud_bio']          = mysqli_real_escape_string($db_con,$_POST['stud_bio']);//new
	
	$data['student_modified_by']    = $logged_uid;
	$data['student_modified']       = $datetime;
	
	$sql_check             = " SELECT * FROM tbl_students WHERE (student_email='".$data['student_email']."' or student_mobile='".$data['student_mobile']."')";
	$sql_check            .= " AND student_id !='".$student_id."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = update('tbl_students',$data,array('student_id'=>$student_id)); 
		    
			if($insert_id)
			{
				$adata['add_details']    = mysqli_real_escape_string($db_con,$_POST['address']);
				$adata['add_state']      = mysqli_real_escape_string($db_con,$_POST['state_code']);
				$adata['add_city']       = mysqli_real_escape_string($db_con,$_POST['city']);
				$adata['add_area']       = mysqli_real_escape_string($db_con,$_POST['area']);
				$adata['add_status']     = mysqli_real_escape_string($db_con,$_POST['student_status']);
				$adata['add_user_type']  = 'student';
				$adata['add_modified']    = $datetime;
				$adata['add_modified_by'] = $logged_uid;
				update('tbl_address_master',$adata,array('add_user_id'=>$student_id,'add_user_type'=>'student'));
				
			   //====================Start Insertion of batch and competiion for coach=====================//
				$batches                 = $_POST['batch'];
				$competitions            = $_POST['comp'];
				
				if(!empty($batches))
				{
					$sql_delete_batch ="DELETE FROM tbl_student_batches WHERE batch_id NOT IN (".implode($batches).")";
					mysqli_query($sql_delete_batch);
				}
				if(!empty($competitions))
				{
					$sql_delete_batch ="DELETE FROM tbl_student_competition WHERE competition_id NOT IN (".implode($competitions).")";
					mysqli_query($sql_delete_batch);
				}
				
				$batch_arr =  array();
				$comp_arr  =  array();
				
				$sql_get_batch ="SELECT * FROM tbl_student_batches WHERE student_id ='".$student_id."'";
				$res_get_batch = mysqli_query($db_con,$sql_get_batch) or die(mysqli_error($db_con));
				while($row_get_batch = mysqli_fetch_array($res_get_batch))
				{
					array_push($batch_arr,$row_get_batch['batch_id']);
				}
				
				$sql_get_comp ="SELECT * FROM tbl_student_competition WHERE student_id ='".$student_id."'";
				$res_get_comp = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
				while($row_get_comp = mysqli_fetch_array($res_get_comp))
				{
					array_push($comp_arr,$row_get_comp['competition_id']);
				}
				
				foreach($batches as $batch)
				{
					if(!in_array($batch,$batch_arr))
					{
						$cbdata['batch_id']    = $batch;
						$cbdata['student_id']    = $student_id;
						insert('tbl_student_batches',$cbdata);
					}
				}
				
				foreach($competitions as $competition)
				{
					if(!in_array($competition,$comp_arr))
					{
						$ccdata['competition_id']    = $competition;
						$ccdata['student_id']          = $student_id;
						insert('tbl_student_competition',$ccdata);
					}
				}
				
				//====================End : Insertion of batch and competiion for coach=====================//
				
				quit('Student Updated Successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
		
	}
	else
	{
		quit('Email or Mobile Number already Exist...!');
	}	
}


if((isset($obj->load_student_parts)) == "1" && isset($obj->load_student_parts))
{
	$student_id        = $obj->student_id;
	$req_type          = $obj->req_type;
	$response_array    = array();
	if($req_type != "")
	{
		$disabled='';
		if($student_id != "" && $req_type != "add")
		{
			$sql 	            = "Select * from tbl_students where student_id = '".$student_id."' ";
			$res    	        = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			$row_student_data	= mysqli_fetch_array($res);	
			if($req_type=="view")
			{
				$disabled           ='disabled';
			}
			
		}	
			
		$data = '';
		if($student_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" name="student_id" id="student_id" value="'.$student_id.'">';
			$data .= '<input type="hidden" name="update_student" id="update_student" value="1">';
		}        
		
		
		if($req_type=='add')
		{
				$data .= '<input type="hidden" name="insert_student" id="insert_student" value="1">';
		}
		//////=============================================Start : Student Name======================================
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Student Name';
		$data .= '<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return charsonly(event);" type="text" id="student_fname" name="student_fname" class="input-large keyup-char" ';
		$data .= ' '.$disabled.' placeholder="First Name" data-rule-required="true"  value="'.@$row_student_data['student_fname'].'"'; 
		$data .= '/>';
		$data .= '&nbsp;&nbsp;<input onkeypress="return charsonly(event);" type="text" id="student_mname" name="student_mname" class="input-large keyup-char" ';
		$data .= ' '.$disabled.' placeholder="Middle Name" data-rule-required="true"  value="'.@$row_student_data['student_mname'].'"'; 
		$data .= '/>';
		$data .= '&nbsp;&nbsp;<input onkeypress="return charsonly(event);" type="text" id="student_lname" name="student_lname" class="input-large keyup-char" ';
		$data .= ' '.$disabled.' placeholder="Last Name" data-rule-required="true"  value="'.@$row_student_data['student_lname'].'"'; 
		$data .= '/>';
		
		//=====Start : Profile Image Start===============//
		$data .= '<div class="" style="max-height:200px;width:200px;float:right">';
		$data .= '<div class="" style="max-height:150px;width:150px;">';
		$data .='<img id="blah" src="images/students_img/'.@$row_student_data['profile_img'].'" alt="Profile Photo" />';
		$data .='</div>';
		$data .= '&nbsp;&nbsp;<input style="float:right"  type="file" id="student_file" name="student_file" ';
		$data .= ' '.$disabled.' placeholder="Last Name" '; 
		if($req_type=='add')
		{
			$data .=' data-rule-required="true"  ';
		}
		$data .= '/>';
		$data .='</div>';
		//=====End : Profile Image Start===============//
		
		
		$data .= '</div>';
		$data .= '</div> <!-- Student Name -->';
		
		$data .="<script type=\"text/javascript\">	$('#student_file').change(function(){
		readURL(this);
	});
	  </script>";
		
		//////=============================================Start : Student Email======================================
		$data .= '<div class="control-group span6" >';
		$data .= '<label for="tasktitel" class="control-label">Email Id<sup class="validfield">';
		$data .= '<span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input  type="email" name="student_email" class="input-large keyup-char" placeholder="Email ID" data-rule-required="true" ';
		$data .= ' id="student_email" '.$disabled.'  value="'.@$row_student_data['student_email'].'" >'; 
		$data .= '</div>';
		$data .= '</div> <!-- Student Email -->';
		
		//////=============================================Start : Student Mobile Number======================================
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Mobile Number<sup class="validfield">
		<span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return numsonly(event);" minlength="10" maxlength="10"  type="text" id="student_mobile" ';
		$data .= ' name="student_mobile" class="input-large" placeholder="Mobile Number" data-rule-required="true" ';
		$data .= ' value="'.@$row_student_data['student_mobile'].'" '.$disabled.'><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Mobile Number -->';
		
		//////=============================================Start : Student Gender======================================
		$data .= '<div class="control-group span6" >';
		$data .= '<label for="tasktitel" class="control-label">Gender<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= ' <input name="student_gender" value="Male" class="css-radio" data-rule-required="true" ';
		if($req_type !='add')
		{
			if($row_student_data['student_gender']=='Male')
			{
				$data .=' checked="checked" ';
			}
		}
		$data .= ' type="radio"> Male ';  
		
		$data .= ' <input name="student_gender" value="Female" class="css-radio" data-rule-required="true" ';
		if($req_type !='add')
		{
			if($row_student_data['student_gender']=='Female')
			{
				$data .=' checked="checked" ';
			}
		}
		$data .=' type="radio"> Female
		         ';
		$data .= '</div>';
		$data .= '</div> <!--Student Gender -->';
		
		//////=============================================Start : Student DOB======================================
		$data .= '<div class="control-group span6" >';
		$data .= '<label for="tasktitel" class="control-label">DOB<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input   type="text" readonly id="student_dob" name="student_dob" class="input-large datepicker" placeholder="Date of Birth" ';
		$data .= 'data-rule-required="true"  value="'.@$row_student_data['student_dob'].'" '.$disabled.'/><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Student DOB-->';
		
		$data .="<script type=\"text/javascript\">	 $( '.datepicker' ).datepicker({
		changeMonth	: true,
		changeYear	: true,
		format: 'dd-mm-yyyy',
		//yearRange 	: 'c:c',//replaced 'c+0' with c (for showing years till current year)
		startDate: '',
		maxDate: new Date(),	
	   });
	  </script>";
		//////=============================================Start : Student DOB Country======================================
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Birth Country<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select  name="birth_country"  id="birth_country" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .='<option value="">Select Country</option>';
		$data.=getList('tbl_country','country_id','country_name',@$row_student_data['birth_country'],$where_arr=array('status'=>'1','country_id'=>'IN'));
		$data .= '</select> ';
		$data .= '</div> ';
		$data .= '</div> <!-- Student DOB Country -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#birth_country").select2();';
		$data .= '</script>';
		
		//////=============================================Start : Student Domicile State======================================
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Domicile State<sup class="validfield">
		<span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
       $data .= '<select name="domicile_state"  id="domicile_state" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .='<option value="">Select State</option>';
		$data.=getList('tbl_state','state','state_name',@$row_student_data['domicile_state'],$where_arr=array('country_id'=>'IN'));
		$data .= '</select> ';
		$data .= '</div>';
		$data .= '</div> <!-- Student Domicile State -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#domicile_state").select2();';
		$data .= '</script>';
		
		//////=============================================Start : Student Address======================================
		if($req_type !='add')
		{
			$sql_get_add =" SELECT * FROM tbl_address_master WHERE add_user_id='".$student_id."' AND add_user_type='student' ";
			$res_get_add = mysqli_query($db_con,$sql_get_add) or die(mysqli_error($db_con));
			$row_get_add = mysqli_fetch_array($res_get_add);
		}
		
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Address<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea '.$disabled.'  id="address" name="address" class="input-large" placeholder="Address" data-rule-required="true">';
		$data .= @$row_get_add['add_details']; 
		$data .='</textarea><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Student Address -->';
	
		//////=============================================Start : Student State======================================	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">State<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select name="state_code" onchange="getCityList(this.value,\'city\')" id="state_code" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .= '<option value="">Select State</option>';
		
		$data.=getList('tbl_state','state','state_name',@$row_get_add['add_state'],$where_arr=array('country_id'=>'IN'));
			
		$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- State -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#state_code").select2();';
		$data .= '</script>';
			
		
		//////=============================================Start : City======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">City<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select onchange="getArea(this.value,\'area\')" name="city"  id="city" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .= '<option value="">Select City</option>';
		if($req_type !='add')
		{
			$data.=getList('tbl_city','city_id','city_name',@$row_get_add['add_city'],$where_arr=array('state_id'=>$row_get_add['add_state']));
		}
		$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- City -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#city").select2();';
		$data .= '</script>';
		
		
		//////=============================================Start : Area======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Area<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select name="area"  id="area" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .= '<option value="">Select Area</option>';
		if($req_type !='add')
		{
			$data.=getList('tbl_area','area_id','area_name',@$row_get_add['add_area'],$where_arr=array('area_city'=>$row_get_add['add_city']));
		}
		$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- Area -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#area").select2();';
		$data .= '</script>';
	
		
		//////=============================================Start : Student Category======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Category<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select  name="stud_cat"  id="stud_cat" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .='<option value="">Select Category</option>';
		$data.=getList('tbl_category','cat_id','cat_name',@$row_student_data['stud_cat'],$where_arr=array('cat_status'=>'1'));
		$data .= '</select>';
		$data .= '</div>'; 
		$data .= '</div> <!-- Student Category -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#stud_cat").select2();';
		$data .= '</script>';
			//////=============================================Start : Student Joining Date======================================
		$data .= '<div class="control-group" >';
		$data .= '<label for="tasktitel" class="control-label">Joining Date
		<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input   type="text" readonly id="stud_joinig_date" name="stud_joinig_date" class="input-large datepicker" placeholder="Joinig Date" ';
		$data .= 'data-rule-required="true"  value="'.@$row_student_data['stud_joinig_date'].'" /><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Student Joining Date -->';
		
		$data .="<script type=\"text/javascript\">	 $( '#stud_joinig_date' ).datepicker({
		changeMonth	: true,
		changeYear	: true,
		format: 'dd-mm-yyyy',
		//yearRange 	: 'c:c',//replaced 'c+0' with c (for showing years till current year)
		startDate: '',
		endDate: new Date(),	
	   });
	  </script>";
	  
		//////=============================================Start : Student Bio======================================
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Bio<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea   id="stud_bio" name="stud_bio" class="input-large" placeholder="Bio" data-rule-required="true">';
		$data .= @$row_student_data['stud_bio']; 
		$data .='</textarea><br>';
		$data .= '</div>';
		$data .= '</div> <!--Student Bio -->';
		
		
		//////=============================================Start : Student Institute======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Institute / School Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="student_institute" placeholder="Institute" name="student_institute" class="input-large keyup-char" data-rule-required="true"  value="'.@$row_student_data['student_institute'].'" />';
		$data .= '</div>';
		$data .= '</div> <!-- Institute -->';
		
		
		//////=============================================Start : Student ID======================================
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Id Proof<br>Pan Card / Addhar Card <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="file" id="student_id" placeholder="" name="student_id" class="input-large keyup-char" ';
		if($req_type=='add')
		{
			$data .=' data-rule-required="true"';
		}
		$data .='/>';
		
		if(@$row_student_data['student_doc']!='')
		{
			$data .='<a target="_blank" href="images/students_doc/'.$row_student_data['student_doc'].'">'.$row_student_data['student_doc'].'</a>';
		}
		$data .= '</div>';
		$data .= '</div> <!-- Document -->';

         	
		/*//=====================================================================================================================//
		//================================= Start Batch and Competition Assignment Dn By satish  =============================================//
	   
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Competition And Batches<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .='<div class="controls">';
		
		if($req_type !='add')
		{
			$batch_arr =  array();
			$comp_arr  =  array();
			
			$sql_get_batch ="SELECT * FROM tbl_student_batches WHERE student_id ='".$student_id."'";
			$res_get_batch = mysqli_query($db_con,$sql_get_batch) or die(mysqli_error($db_con));
			while($row_get_batch = mysqli_fetch_array($res_get_batch))
			{
				array_push($batch_arr,$row_get_batch['batch_id']);
			}
			
			$sql_get_comp ="SELECT * FROM tbl_student_competition WHERE student_id ='".$student_id."'";
			$res_get_comp = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
			while($row_get_comp = mysqli_fetch_array($res_get_comp))
			{
				array_push($comp_arr,$row_get_comp['competition_id']);
			}
		}
		
		
		$sql_get_comp =" SELECT * FROM tbl_competition WHERE competition_status=1 ";
		if($utype!=1)
		{
			$sql_get_comp .=" AND created_by ='".$uid."'";
		}
		
		$res_get_comp = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
		
		
		foreach($res_get_comp as $row_comp)
		{
			
			$data .='  <div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid  ';
			$data .=' #8f8f8f;margin-right:10px;margin-top:10px;">
			
			<input value="'.$row_comp['competition_id'].'" id="comp'.$row_comp['competition_id'].'" ';
			$data .=' onclick="checkbatch('.$row_comp['competition_id'].')" name="comp[]" class="css-checkbox batch_levels levels_parent" ';
				  
			if(in_array($row_comp['competition_id'],$comp_arr))
			{
						$data .=' checked="checked" ';
			}
			$data .=' type="checkbox">'.$row_comp['competition_name'].'
			<label for="comp'.$row_comp['competition_id'].'" class="css-label"></label>';
				 
			$data .='<div style="margin:20px;">'; 
			$sql_get_comp =" SELECT * FROM tbl_batches WHERE competition_id='".$row_comp['competition_id']."' ";
			if($utype!=1)
			{
				$sql_get_comp .=" AND batch_created_by ='".$uid."'";
			}
			$res_get_comp =mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
			while($row_batch = mysqli_fetch_array($res_get_comp))
			{  
				   $data .=' <input value="'.$row_batch['batch_id'].'" id="cbatch'.$row_batch['batch_id'].'" name="batch[]" ';
				   $data .=' onchange="checkcompetition('.$row_comp['competition_id'].','.$row_batch['batch_id'].');" ';
				   $data .='class="css-checkbox batch'.$row_comp['competition_id'].'"';
				   
				   if(in_array($row_batch['batch_id'],$batch_arr))
				   {
						$data .=' checked="checked" ';
				   }
		           $data .= '  type="checkbox">'.$row_batch['batch_name'].'
				  <label for="cbatch'.$row_batch['batch_id'].'" class="css-label"></label>';
				  
			}
			$data .=' </div>';
	         $data .='</div>';
		}
		
	    $data .='</div>';// end control
	    $data .='</div>';// end control group
	
	//================================= End Batch and Competition Assignment   =============================================//
	//=====================================================================================================================//*/
	
	    $data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		if($student_id != "" && $req_type == "view")
		{
			if($row_get_add['student_status'] == 1)
			{
				$data .= ' <label class="control-label" style="color:#30DD00"> Active </label>';
			}
			if($row_get_add['student_status'] == 0)
			{
				$data .= ' <label class="control-label" style="color:#E63A3A"> Inactive </label>';
			}
		}
		else
		{  
          if($student_id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="student_status" value="1" class="css-radio" data-rule-required="true" ';
				$dis	= checkFunctionalityRight("view_student.php",3);
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_student_data['student_status'] == 1)
				{
					$data .= 'checked ';
				}
				$data .= '> Active ';
				$data .= '<input type="radio" name="student_status" value="0" class="css-radio" data-rule-required="true"';
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_student_data['student_status'] == 0  )
				{
					$data .= 'checked ';
				}
				$data .= '> Inactive';
			} 
			else  
			{
				$data .= ' <input type="radio" name="student_status" value="1" class="css-radio" data-rule-required="true" ';
				$data .= '> Active ';
				$data .= ' <input type="radio" name="student_status" value="0" class="css-radio" data-rule-required="true"';
			
		 		$data .= '> Inactive ';
			}
		}
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
	
		$data .= '<div class="form-actions">';
		if($student_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Student</button>';			
		}
		elseif($student_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Student</button>';			
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




if((isset($obj->load_student)) == "1" && isset($obj->load_student))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= $obj->search_text;	
	
	$area_id 		= $obj->area_id;	
	$batch_id		= $obj->batch_id;
	$coach_id	    = $obj->coach_id;	
	$competition_id	= $obj->competition_id;
	
		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
		
		
			
		$sql_load_data  = " SELECT ts.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = ts.student_modified_by) AS name_midified_by 
							FROM `tbl_students` AS ts";
		$sql_load_data  .= " INNER JOIN tbl_address_master as tam ON ts.student_id=tam.add_user_id ";			
							
		$sql_load_data .=" WHERE add_user_type='student'";	
		//===========Filter By area====================================//
		if($area_id!="")
		{
			$sql_load_data .=" AND tam.add_area='".$area_id."' ";
		}
		
		if($batch_id !="")
		{
			$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_student_batches WHERE batch_id='".$batch_id."') ";
		}
		
		if($competition_id !="")
		{
			$sql_load_data .=" AND ts.student_id IN(SELECT DISTINCT(student_id) FROM tbl_student_competition WHERE competition_id='".$competition_id."') ";
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
		if($search_text != "")
		{
			$sql_load_data .= " and (student_name like '%".$search_text."%' or student_email like '%".$search_text."%' ";
			$sql_load_data .= " or student_mobile like '%".$search_text."%'  or student_gender like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY student_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Student Name</th>';
			$area_data .= '<th style="text-align:center">Email</th>';
			$area_data .= '<th style="text-align:center">Mobile Number</th>';
			$area_data .= '<th style="text-align:center">Gender</th>';
			$area_data .= '<th style="text-align:center">Created Date</th>';
			$area_data .= '<th style="text-align:center">Created By</th>';
			$area_data .= '<th style="text-align:center">Modified Date</th>';
			$area_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_student.php",3);
			$dis = 1;
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_student.php",1);
			$edit = 1;
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_student.php",2);
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
			
				$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['student_name']).'" class="btn-link" id="'.$row_load_data['student_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
					$area_data .= '<td style="text-align:center">'.$row_load_data['student_email'].'</td>';
				
				$area_data .= '<td style="text-align:center">'.$row_load_data['student_mobile'].'</td>';			
				$area_data .= '<td style="text-align:center">'.$row_load_data['student_gender'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['student_created'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['student_modified'].'</td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_student.php",3);
				$dis = 1;
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['student_status'] == 1)
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['student_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['student_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_student.php",1);
				$edit = 1;
				if($edit)
				{				
					$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Edit" id="'.$row_load_data['student_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_student.php",2);
				$delete = 1;
				if($delete)
				{					
					$area_data .= '<td><div class="controls" align="center">';
					$area_data .= '<input type="checkbox" value="'.$row_load_data['student_id'].'" id="batch'.$row_load_data['student_id'].'" name="batch'.$row_load_data['student_id'].'" class="css-checkbox batch">';
					$area_data .= '<label for="batch'.$row_load_data['student_id'].'" class="css-label"></label>';
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
	foreach($student_ids as $student_id)	
	{
		$sql_delete_area	= " DELETE FROM `tbl_students` WHERE `student_id` = '".$student_id."' ";
		$result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));			
		if($result_delete_area)
		{
			$del_flag = 1;
			$sql_delete_area	= " DELETE FROM `tbl_address_master` WHERE `add_user_id` = '".$student_id."' AND add_user_type='student' ";
		    $result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));	
			
			$sql_delete_batch	= " DELETE FROM `tbl_student_batches` WHERE `student_id` = '".$student_id."' ";
		    $res_delete_batch	= mysqli_query($db_con,$sql_delete_batch) or die(mysqli_error($db_con));	
			
			$sql_delete_comp	= " DELETE FROM `tbl_student_competition` WHERE `student_id` = '".$student_id."' ";
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
		quit('Please Select State...!');
	}
	
	$data ='<option value="">Select City</option>';
	
	$sql =" SELECT * FROM tbl_area WHERE area_status =1 AND area_city='".$city_id."'";
	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	if(mysqli_num_rows($res)==0)
	{
		quit('city not found...!');
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
?>
