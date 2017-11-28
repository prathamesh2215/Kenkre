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

if((isset($_POST['insert_student'])) == "1" && isset($_POST['insert_student']))
{
	$data['student_fname']             = strtolower(mysqli_real_escape_string($db_con,$_POST['student_fname']));
	$data['student_mname']             =strtolower( mysqli_real_escape_string($db_con,$_POST['student_mname']));
	$data['student_lname']             = strtolower(mysqli_real_escape_string($db_con,$_POST['student_lname']));
	
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
	$data['batch_type']        = mysqli_real_escape_string($db_con,$_POST['batch_type']);
	$data['batch_center']      = mysqli_real_escape_string($db_con,$_POST['batch_center']);
	$data['student_type']      = mysqli_real_escape_string($db_con,$_POST['student_type']);
	
	$student_dob                    = mysqli_real_escape_string($db_con,$_POST['student_dob']);
	$student_dob                    = explode('-',$student_dob);// d/m/y
	$data['student_dob']            = $student_dob[2].'-'.$student_dob[1].'-'.$student_dob[0];
	//$data['student_dob']       = mysqli_real_escape_string($db_con,$_POST['student_dob']);
	
	
	$data['student_status']    = mysqli_real_escape_string($db_con,$_POST['student_status']);
	$data['student_institute'] =  strtolower(mysqli_real_escape_string($db_con,$_POST['student_institute']));
	
	if($_FILES['student_file']['name'] !="" && isset($_FILES['student_file']['name']))
	{
		$imagedata = getimagesize($_FILES['student_file']['tmp_name']);
		
		if($imagedata[0] <500 )
		{
			quit('Student Image width should be greater than 500 Pixel');
		}
		$stud_photo                  = explode('.',$_FILES['student_file']['name']);
		$stud_photo                  = date('dhyhis').'.'.$stud_photo[1];
		$data['profile_img']         = $stud_photo;
		
		$dir                         ='images/students_img/';
		if(!move_uploaded_file($_FILES['student_file']['tmp_name'],$dir.$stud_photo))
		{
			quit('Profile photo not uploaded please try letter...!');
			
		}
		else
		{
			make_thumb($dir.$stud_photo,$dir.'small/'.$stud_photo,100,100);
			make_thumb($dir.$stud_photo,$dir.'/'.$stud_photo,400,400);	
			unlink($dir.$stud_photo);
		}
	}
	else
	{
		quit('Profile photo is required...!');
	}
	
	if($_FILES['student_id']['name'] !="" && isset($_FILES['student_id']['name']))
	{
		$imagedata = getimagesize($_FILES['student_id']['tmp_name']);
		
		if($imagedata[0] <400 || $imagedata[0] >400 )
		{
			quit('Document Image size should be 400 Pixel');
		}
		$stud_doc                  = explode('.',$_FILES['student_id']['name']);
		$stud_doc                  = date('dhyhis').'.'.$stud_doc[1];
		$data['student_doc']       = $stud_doc;
		
		$dir                         ='images/students_doc/'.$stud_doc;
		if(!move_uploaded_file($_FILES['student_id']['tmp_name'],$dir))
		{
			
			quit('Student Document not uploaded please try letter...!');
		}
		make_thumb($dir,$dir.'small_'.$stud_photo,100,100);
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
				$adata['add_details']    = strtolower(mysqli_real_escape_string($db_con,$_POST['address']));
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


//==========================================================================//
//------------------this is used for Update records---------------------
if((isset($_POST['update_student'])) == "1" && isset($_POST['update_student']))
{
	$data['student_fname']             = strtolower(mysqli_real_escape_string($db_con,$_POST['student_fname']));
	$data['student_mname']             = strtolower(mysqli_real_escape_string($db_con,$_POST['student_mname']));
	$data['student_lname']             = strtolower(mysqli_real_escape_string($db_con,$_POST['student_lname']));
	
	$data['batch_type']        = mysqli_real_escape_string($db_con,$_POST['batch_type']);
	$data['batch_center']      = mysqli_real_escape_string($db_con,$_POST['batch_center']);
	
	$data['student_email']     = mysqli_real_escape_string($db_con,$_POST['student_email']);
	$data['student_mobile']    = mysqli_real_escape_string($db_con,$_POST['student_mobile']);
	$data['student_gender']    = mysqli_real_escape_string($db_con,$_POST['student_gender']);
	$student_dob                    = mysqli_real_escape_string($db_con,$_POST['student_dob']);
	$student_dob                    = explode('-',$student_dob);// d/m/y
	$data['student_dob']            = $student_dob[2].'-'.$student_dob[1].'-'.$student_dob[0];
	$data['student_status']    = mysqli_real_escape_string($db_con,$_POST['student_status']);
	$data['student_institute'] =  strtolower(mysqli_real_escape_string($db_con,$_POST['student_institute']));
	$data['student_type']      = mysqli_real_escape_string($db_con,$_POST['student_type']);
	$student_id                = mysqli_real_escape_string($db_con,$_POST['student_id']);
	
	//=======================End : Image and Doc Upload End=====================================//
	$sql_get_stud              = " SELECT * FROM tbl_students WHERE student_id ='".$student_id."' ";
	$res_get_stud              = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
	$row_get_stud              = mysqli_fetch_array($res_get_stud);
	
	if($_FILES['student_file']['name'] !="" && isset($_FILES['student_file']['name']))
	{
		$imagedata = getimagesize($_FILES['student_file']['tmp_name']);
		
		if($imagedata[0] <500 )
		{
			quit('Student Image width should be greater than 500 Pixel');
		}
		$stud_photo                  = explode('.',$_FILES['student_file']['name']);
		$stud_photo                  = date('dhyhis').'.'.$stud_photo[1];
		$dir                         ='images/students_img/';
		
		if(move_uploaded_file($_FILES['student_file']['tmp_name'],$dir.$stud_photo))
		{
			$data['profile_img']         = $stud_photo;
			make_thumb($dir.$stud_photo,$dir.'small/'.$stud_photo,100,100);
			make_thumb($dir.$stud_photo,$dir.'/'.$stud_photo,400,400);	
			unlink($dir.$row_get_stud['profile_img']);
			unlink($dir.'small_'.$row_get_stud['profile_img']);
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
				$adata['add_details']    = strtolower(mysqli_real_escape_string($db_con,$_POST['address']));
				$adata['add_state']      = mysqli_real_escape_string($db_con,$_POST['state_code']);
				$adata['add_city']       = mysqli_real_escape_string($db_con,$_POST['city']);
				$adata['add_area']       = mysqli_real_escape_string($db_con,$_POST['area']);
				$adata['add_status']     = mysqli_real_escape_string($db_con,$_POST['student_status']);
				$adata['add_user_type']  = 'student';
				$num = isExist('tbl_address_master' ,array('add_user_id'=>$student_id,'add_user_type'=>'student'));
			
				if($num!=0)
				{
					$adata['add_modified']    = $datetime;
					$adata['add_modified_by'] = $logged_uid;
					update('tbl_address_master',$adata,array('add_user_id'=>$student_id,'add_user_type'=>'student'));
				}
				else
				{
					$adata['add_id']         = getNewId('add_id','tbl_address_master');
					$adata['add_created']    = $datetime;
					$adata['add_created_by'] = $logged_uid;
					$adata['add_user_id']    = $student_id;
				}
				
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


//==========================================================================//
//------------------Strat load Student Part--------------------
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
		if($req_type!='view')
		{
		
		//////=============================================Start : Student Type======================================
		
		$type_ar  = array('Kenkre','Other');
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Student Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select name="student_type"  id="student_type" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .= '<option value="">Select Type</option>';
		foreach($type_ar as $type)
		{
			$data .='<option  value="'.$type.'" ';
			if($type==@$row_student_data['student_type'])
			{
				$data .=' selected ';
			}
			$data .='>'.$type.'</option>';
		}
		$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!-- City -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#student_type").select2();';
		$data .= '</script>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Student Name';
		$data .= '<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return charsonly(event);" type="text" id="student_fname" name="student_fname" class="input-large keyup-char" ';
		$data .= ' '.$disabled.' placeholder="First Name" data-rule-required="true"  value="'.@$row_student_data['student_fname'].'"'; 
		$data .= '/>';
		$data .= '&nbsp;&nbsp;<input onkeypress="return charsonly(event);" type="text" id="student_mname" name="student_mname" class="input-large keyup-char" ';
		$data .= ' '.$disabled.' placeholder="Middle Name" value="'.@$row_student_data['student_mname'].'"'; 
		$data .= '/>';
		$data .= '&nbsp;&nbsp;<input onkeypress="return charsonly(event);" type="text" id="student_lname" name="student_lname" class="input-large keyup-char" ';
		$data .= ' '.$disabled.' placeholder="Last Name" data-rule-required="true"  value="'.@$row_student_data['student_lname'].'"'; 
		$data .= '/>';
		
		//=====Start : Profile Image Start===============//
		$data .= '<div class="" style="max-height:200px;width:200px;float:right">';
		$data .= '<div class="" style="max-height:150px;width:250px;">';
		if(isset($row_student_data['profile_img']) && $row_student_data['profile_img']!="")
		{
			$data .='<img id="blah" src="images/students_img/'.@$row_student_data['profile_img'].'" alt="Profile Photo" style="width:90px;height:90px" />';
		}
		else
		{
			$data .='<img id="blah" src="images/person.png" alt="Profile Photo" style="width:90px;height:90px"  />';
		}
		$data .='<ul class="css-ul-list" style="color:red">
						   <li>Only "jpg" , "png" or "jpeg" <br>image will be accepted.</li>
						   <li>Image Width should be greater <br> than \'500\'   pixel.<br></li>
						</ul><br><br><br>';
		$data .='</div>';
		
		$data .= '&nbsp;&nbsp;<br><input style="float:right;"  type="file" id="student_file" name="student_file" ';
		$data .= ' '.$disabled.' '; 
		if($req_type=='add')
		{
			//$data .=' data-rule-required="true"  ';
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
		
		
		
		
		//////=============================================Start : Student Mobile Number======================================
		
		
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
		$student_dob = explode('-',$row_student_data['student_dob']);
		$data .= '<div class="control-group span6" >';
		$data .= '<label for="tasktitel" class="control-label">DOB<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input   type="text" readonly id="student_dob" name="student_dob" class="input-large datepicker" placeholder="Date of Birth" ';
		$data .= 'data-rule-required="true"  value="'.@$student_dob[2].'-'.@$student_dob[1].'-'.@$student_dob[0].'" '.$disabled.'/><br>';
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
		$data .= '<label for="tasktitel" class="control-label">Fee<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
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
		
		///////////////=========================Start : Batch Type==================================//
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Batch Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select  name="batch_type"  onchange="getCenter(this.value)" id="batch_type" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .='<option value="">Select Type</option>';
		
		$sql_get_type    =  " SELECT * FROM tbl_batch_types WHERE id IN(SELECT DISTINCT type_id FROM tbl_center_types)";
		$res_get_type    = mysqli_query($db_con,$sql_get_type) or die(mysqli_error($db_con));
		while($row_get_type = mysqli_fetch_array($res_get_type))
		{
			$data .='<option value="'.$row_get_type['id'].'" ';
			if($row_get_type['id']==@$row_student_data['batch_type'])
			{
				$data .=' selected="selected" ';
			}
			$data .='>'.ucwords($row_get_type['type']).'</option>';
		}
		$data .= '</select>';
		$data .= '</div>'; 
		$data .= '</div> <!-- Student Category -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#batch_type").select2();';
		$data .= '</script>';
		
		///////////////=========================Start : Center==================================//
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Center<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select  name="batch_center" id="batch_center" class="select2-me input-large" data-rule-required="true" tabindex="-1">';
		$data .='<option value="">Select Center</option>';
		
		if($req_type!='add')
		{
			$sql_get_center    =  " SELECT * FROM tbl_centers WHERE center_status = 1";
			$res_get_center    = mysqli_query($db_con,$sql_get_center) or die(mysqli_error($db_con));
			while($row_get_center = mysqli_fetch_array($res_get_center))
			{
				$data .='<option value="'.$row_get_center['center_id'].'" ';
				if($row_get_center['center_id']==@$row_student_data['batch_center'])
				{
					$data .=' selected="selected" ';
				}
				$data .='>'.ucwords($row_get_center['center_name']).'</option>';
			}
		}
		
		$data .= '</select>';
		$data .= '</div>'; 
		$data .= '</div> <!-- Student Category -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#batch_center").select2();';
		$data .= '</script>';
		
		
			//////=============================================Start : Student Joining Date======================================
		$stud_joinig_date   = explode('-',@$row_student_data['stud_joinig_date']);
		$data .= '<div class="control-group" >';
		$data .= '<label for="tasktitel" class="control-label">Joining Date
		<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input   type="text" readonly id="stud_joinig_date" name="stud_joinig_date" class="input-large datepicker" placeholder="Joinig Date" ';
		$data .= 'data-rule-required="true"  value="'.@$stud_joinig_date[2].'-'.@$stud_joinig_date[1].'-'.@$stud_joinig_date[0].'" /><br>';
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
		$data .= '<label for="tasktitel" class="control-label">About yourself</label>';
		$data .= '<div class="controls">';
		$data .= '<textarea   id="stud_bio" name="stud_bio" class="input-large" placeholder="About yourself">';
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
		$data .= '<label for="tasktitel" class="control-label">Id Proof<br>Pan Card / Addhar Card </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="file" id="student_id" accept="image/png,image/jpeg,image/jpg,application/pdf" placeholder="" name="student_id" class="input-large keyup-char" ';
		if($req_type=='add')
		{
			//$data .=' data-rule-required="true"';
		}
		$data .='/>';
		
		
		if(@$row_student_data['student_doc']!='')
		{
			$data .='<a target="_blank" href="images/students_doc/'.$row_student_data['student_doc'].'">'.$row_student_data['student_doc'].'</a>';
		}
		$data .='<ul class="css-ul-list" style="color:red">
						   <li>Only "jpg" , "png" ,"pdf" or "jpeg" image will be accepted.</li>
						   <li>Image width should be  \'400\'   pixel.</li>
						</ul>';
		$data .= '</div>';
		$data .= '</div> <!-- Document -->';

	    $data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
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
	}
		else
		{
			if($row_student_data['student_status']==1)
			{
				$bgcolor = '#18BB7C';
				//$color   = 'white';
			}
			else
			{
				$bgcolor = '#da4f49';
			}
			
			$sql_get_add  = " SELECT * FROM tbl_address_master as tam ";
			$sql_get_add .= " INNER JOIN tbl_state as ts ON tam.add_state = ts.state ";
			$sql_get_add .= " INNER JOIN tbl_area as ta ON tam.add_area = ta.area_id ";
			$sql_get_add .= " INNER JOIN tbl_city as tc ON tam.add_city = tc.city_id ";
			$sql_get_add .= " WHERE add_user_type='student' AND add_user_id='".$student_id."'";
			
			$res_get_add  = mysqli_query($db_con,$sql_get_add) or die(mysqli_error($db_con));
			$row_get_add  = mysqli_fetch_array($res_get_add);
			
			$state        = $row_get_add['state_name'];
			$area         = $row_get_add['area_name'];
			$city         = $row_get_add['city_name'];
			$add_detail   = $row_get_add['add_details'];
			
			//==================Start :  Heading  ===========================================//
			$data .='<div class="control-group" style="background-color:'.$bgcolor.'; padding-bottom:25px">';
			
			$data .='<div class="span4">';
				$data .='<div style="padding:20px">';
				if($row_student_data['profile_img']!="")
				{
					$data .='<img style="width:200px" src="images/students_img/'.$row_student_data['profile_img'].'" alt="">';
				}
				else
				{
					$data .='<img style="width:200px" src="img/person.jpg" alt="">';
				}
					
				$data .='</div>';
		   $data .='</div>';
			
	       $data .='<div class="span8">';
		   $data .='<div style="">';
					$data .='<h3 class="head1" style="color:white">'.ucwords($row_student_data['student_fname']).'
					 '.ucwords($row_student_data['student_lname']).'
					 </h3>';
		   $data .='</div>';
				
		   $year  = explode('-',$row_student_data['student_dob']);
				//date('Y',strtotime($year[2]))).date('Y',strtotime($year[2]));
				
			$data .='<div class="control-group" style="background-color:'.$bgcolor.'">';
					$data .='<div class="span6">
								<span class="head2" style="color:white">Email: </span> <span class="head2" style="color:white">
								'.$row_student_data['student_email'].'</span><br>
								<span class="head2" style="color:white">Date of Birth:  
								'.@$year[2].' / '.@$year[1].' / '.@$year[0].'</span><br>
								<span class="head2" style="color:white">Institute: </span>
								<span class="head2" style="color:white"> '.@ucwords($row_student_data['student_institute']).'</span>';
								
								if($state !='')
								{
									$data .='<br><br><span class="head2" style="color:white">State: </span>
									<span class="head2" style="color:white"> '.@$state.'</span>';
								}
								if($area !='')
								{
									$data .='<br><span class="head2" style="color:white">Area: </span>
									<span class="head2" style="color:white"> '.@$area.'</span>';
								}
								if($row_student_data['student_doc']!="")
								{
									$data .='<br><span class="head2" style="color:white">Id Proof: </span>
									<span class="head2" style="color:white"><a style="color:white" target="_blank" href="images/students_doc/'.$row_student_data['student_doc'].'">'.$row_student_data['student_doc'].'</a></span>';
								}
								
					
					$data .='</div>';
					$age   = (date('Y'))-$year[0];
					$stud_joinig_date = explode('-',$row_student_data['stud_joinig_date']);
					$data .='<div class="span6">
									<span class="head2" style="color:white">Mobile : </span>
									<span class="head2" style="color:white">'.ucwords($row_student_data['student_mobile']).'</span><br>
									<span  class="head2" style="color:white">Age: </span> <span class="head2" style="color:white">'.@$age.' years </span><br>
									<span class="head2" style="color:white">Joining Date: </span> 
									<span class="head2" style="color:white">'.@$stud_joinig_date[2].' / '.@$stud_joinig_date[1].' / '.@$stud_joinig_date[0].'</span>					<br>
							';
								if($city !='')
								{
									$data .='<br><span class="head2" style="color:white">City: </span>
									<span class="head2" style="color:white"> '.@ucwords($city).'</span>';
								}
							    
								if($add_detail !='')
								{
									$data .='<br><span class="head2" style="color:white">Address: </span>
									<span class="head2" style="color:white"> '.@ucwords($add_detail).'</span>';
								}
						$data .='</div>';
						
							
							
							if(@$row_student_data['stud_bio']!="")
							{
								$data .='<div class="span12" style="text-align:justify;padding-top:45px" >';
									$data .='<span class="head2" style="color:white">About :</span>
											 <span class="head2" style="color:white"> '.@$row_student_data['stud_bio'].'</span>';
								$data .='</div>';
							}
				$data .='<br><br></div>';
			$data .='</div><br><br>';
			$data .='</div>';// control-group end
		
		//==================End : Heading  ===========================================//
		
		
		
		//==================Start : Coaches  ===========================================//
		
		$sql_get_stud  = " SELECT * FROM tbl_team_students  as tts ";
		$sql_get_stud .= " INNER JOIN tbl_team as  tm ON tts.team_id =tm.team_id ";
		$sql_get_stud .= " WHERE tts.student_id='".$student_id."'";
		$res_get_stud  = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
		$num_get_stud  = mysqli_num_rows($res_get_stud);
		$res_array     = array();
		while($row = mysqli_fetch_array($res_get_stud))
		{
			array_push($res_array,$row);
		}
		$num_get_stud1 = round(($num_get_stud)/2);
		$num_get_stud2 = $num_get_stud - $num_get_stud1;
		if($res_array)
		{
			$data .='<div class="control-group">';
			$data .='<div class="span12">';
				$data .='<div style="padding:20px">';
					$data .='<h5>Teams</h5>';
				$data .='</div>';
			$data .='</div>';
			
			
			$data .='<div class="span4">';
				$data .='<div style="padding:20px">';
					$data .='<ul>';
					for($i=0;$i<$num_get_stud1;$i++)
					{
						$data .='<li class="head2"><a href="view_team.php?pag=Teams&team_id='.$res_array[$i]['team_id'].'" target="_blank" >'.ucwords($res_array[$i]['team_name']).'</a>';
						if($res_array[$i]['isCaptain']==1)
						{
							$data .=' ( Captain )';
						}
						
						$data .='</li>';
					}
					$data .='</ul>';
				$data .='</div>';
			$data .='</div>';
			
			
			
			$data .='<div class="span4">';
				$data .='<div style="padding:20px;">';
					$data .='<ul>';
					for($i=$i;$i<$num_get_stud;$i++)
					{
						$data .='<li class="head2"><a href="view_team.php?pag=Teams&team_id='.$res_array[$i]['team_id'].'" target="_blank" > '.ucwords($res_array[$i]['team_name']).'</a>';
						if($res_array[$i]['isCaptain']==1)
						{
							$data .=' ( Captain )';
						}
						$data .='</li>';
					}
					$data .='</ul>';
				$data .='</div>';
			$data .='</div>';
			
			$data .='</div>';// control-group
		}
		//==================End : Coaches  ===========================================//
		
		
		//==================Start : Competition  ===========================================//
		$sql_get_stud  = " SELECT competition_name,tc.competition_id FROM tbl_competition  as tc ";
		$sql_get_stud .= " WHERE competition_id IN (SELECT DISTINCT(competition_id) FROM tbl_competition_team WHERE team_id IN ( ";
		$sql_get_stud .= " SELECT DISTINCT(team_id) FROM tbl_team_students WHERE student_id='".$student_id."' ) ";
		$sql_get_stud .= " ) ";
		$res_get_stud  = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
		$num_get_stud  = mysqli_num_rows($res_get_stud);
		$res_array     = array();
		while($row = mysqli_fetch_array($res_get_stud))
		{
			array_push($res_array,$row);
		}
		$num_get_stud1 = round(($num_get_stud)/2);
		$num_get_stud2 = $num_get_stud - $num_get_stud1;
		if($res_array)
		{
			$data .='<div class="control-group">';
			
			$data .='<div class="span12">';
				$data .='<div style="padding:20px">';
					$data .='<h5> Competition </h5>';
				$data .='</div>';
			$data .='</div>';
			
			
			
			
			$data .='<div class="span4" style="clear:both">';
				$data .='<div style="padding:20px">';
					$data .='<ul>';
					for($i=0;$i<$num_get_stud1;$i++)
					{
						$data .='<li class="head2"><a href="view_competition.php?pag=Competitions&competition_id='.$res_array[$i]['competition_id'].'" target="_blank" >'.ucwords($res_array[$i]['competition_name']).' </a></li>';
					}
					$data .='</ul>';
				$data .='</div>';
			$data .='</div>';
			
			$data .='<div class="span4">';
				$data .='<div style="padding:20px;">';
					$data .='<ul>';
					for($i=$i;$i<$num_get_stud;$i++)
					{
						$data .='<li class="head2"><a href="view_competition.php?pag=Competitions&competition_id='.$res_array[$i]['competition_id'].'" target="_blank" >'.ucwords($res_array[$i]['competition_name']).' </a></li>';
					}
					$data .='</ul>';
				$data .='</div>';
			$data .='</div>';
			
			$data .='</div>';// row end
		}
		//==================End : Competition  ===========================================//
		
		//==================Start : Participation  ===========================================//
		$sql_get_comp  = "SELECT * FROM tbl_batch_students  as tct ";
		$sql_get_comp .= " INNER JOIN tbl_batches as  tc ON tct.batch_id =tc.batch_id ";
		$sql_get_comp .= " WHERE student_id='".$student_id."'";
		$res_get_comp  = mysqli_query($db_con,$sql_get_comp) or die(mysqli_error($db_con));
		$num_get_comp  = mysqli_num_rows($res_get_comp);
		$comp_array     = array();
		while($comp_row = mysqli_fetch_array($res_get_comp))
		{
			array_push($comp_array,$comp_row);
		}
		$num_get_comp1 = round(($num_get_comp)/2);
		$num_get_comp2 = $num_get_comp - $num_get_comp1;
		
		if($comp_array)
		{
			$data .='<div class="control-group">';
			
			$data .='<div class="span12">';
				$data .='<div style="padding:20px">';
					$data .='<h5> Training Batches </h5>';
				$data .='</div>';
			$data .='</div>';
			
			
			
			
			$data .='<div class="span4" style="clear:both">';
				$data .='<div style="padding:20px">';
					$data .='<ul>';
					for($i=0;$i<$num_get_comp1;$i++)
					{
						$data .='<li class="head2"><a href="view_batch.php?pag=Training Batches&batch_id='.$comp_array[$i]['batch_id'].'" target="_blank" >'.ucwords($comp_array[$i]['batch_name']).'</a></li>';
					}
					$data .='</ul>';
				$data .='</div>';
			$data .='</div>';
			
			$data .='<div class="span4">';
				$data .='<div style="padding:20px;">';
					$data .='<ul>';
					for($i=$i;$i<$num_get_comp;$i++)
					{
						$data .='<li class="head2"><a href="view_batch.php?pag=Training Batches&batch_id='.$comp_array[$i]['batch_id'].'" target="_blank" >'.ucwords($comp_array[$i]['batch_name']).' </a></li>';
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


//==========================================================================//
//------------------Strat load Student---------------------------------------
if((isset($obj->load_student)) == "1" && isset($obj->load_student))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);	
	
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
		if($search_text != "")
		{
			$sql_load_data .= " and (student_fname like '%".$search_text."%' or student_lname like '%".$search_text."%' or student_mname like '%".$search_text."%' or  student_email like '%".$search_text."%' ";
			$sql_load_data .= " or student_mobile like '%".$search_text."%'  or student_gender like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY student_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$student_data  = "";	
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
			$student_data .= '<th style="text-align:center">Created Date</th>';
			$student_data .= '<th style="text-align:center">Created By</th>';
			$student_data .= '<th style="text-align:center">Modified Date</th>';
			$student_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_student.php",3);
			$dis = 1;
			if($dis)
			{			
				$student_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_student.php",1);
			$edit = 1;
			if($edit)
			{			
				$student_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_student.php",2);
			$delete = 1;
			if($delete)
			{			
				$student_data .= '<th style="text-align:center"><div style="text-align:center">';
				$student_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$student_data .= '</tr>';
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
				$student_created = strtotime($row_load_data['student_created']);
	            $student_data .= '<td style="text-align:center">'.date(' j M, Y, g : i a',$student_created).'</td>';
				//$student_data .= '<td style="text-align:center">'.$row_load_data['student_created'].'</td>';
				$student_data .= '<td style="text-align:center">'.ucwords($row_load_data['name_created_by']).'</td>';
				//$student_data .= '<td style="text-align:center">'.$row_load_data['student_modified'].'</td>';
				$student_modified = strtotime($row_load_data['student_modified']);
	            $student_data .= '<td style="text-align:center">'.date(' j M, Y, g : i a',$student_modified).'</td>';
				$student_data .= '<td style="text-align:center">'.ucwords($row_load_data['name_midified_by']).'</td>';
				$dis = checkFunctionalityRight("view_student.php",3);
				$dis = 1;
				if($dis)
				{					
					$student_data .= '<td style="text-align:center">';					
					if($row_load_data['student_status'] == 1)
					{
						$student_data .= '<input type="button" value="Active" id="'.$row_load_data['student_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$student_data .= '<input type="button" value="Inactive" id="'.$row_load_data['student_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$student_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_student.php",1);
				$edit = 1;
				if($edit)
				{				
					$student_data .= '<td style="text-align:center">';
					$student_data .= '<input type="button" value="Edit" id="'.$row_load_data['student_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_student.php",2);
				$delete = 1;
				if($delete)
				{					
					$student_data .= '<td><div class="controls" align="center">';
					$student_data .= '<input type="checkbox" value="'.$row_load_data['student_id'].'" id="batch'.$row_load_data['student_id'].'" name="batch'.$row_load_data['student_id'].'" class="css-checkbox batch">';
					$student_data .= '<label for="batch'.$row_load_data['student_id'].'" class="css-label"></label>';
					$student_data .= '</div></td>';										
				}
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
?>
