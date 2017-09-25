<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

if(isset($_FILES['file']))
{
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$ind_id 	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$response_array = array();
	
	try 
	{
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	} 
	catch(Exception $e) 
	{
		die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	
	$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
	
	if(strcmp($inputFileName, "")!==0)
	{
		for($i=2;$i<=$arrayCount;$i++)
		{
			$slider_name 		= trim($allDataInSheet[$i]["A"]);
			$slider_status	= '1';
			
			$query 		= " SELECT * FROM `tbl_slider` WHERE `slider_name`='".$slider_name."' " ;
							
			$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
			$recResult 	= mysqli_fetch_array($sql);
			
			$existName 	= $recResult["slider_name"];
			
			if($existName=="")
			{						  
				$response_array 	= insertSlider($response_array, $slider_name, $slider_status);
				if($response_array)
				{
					$insertion_flag	= 1;	
				}
				else
				{
					$insertion_flag	= 0;	
				}
			}
			else
			{
				// error data array
				$error_data = array("slider_name"=>$slider_name, "slider_status"=>"0");	
				
				$sql_get_last_record	= " SELECT * FROM `tbl_error_data` ORDER by `error_id` DESC LIMIT 0,1 ";
				$res_get_last_record	= mysqli_query($db_con, $sql_get_last_record) or die(mysqli_error($db_con));
				$row_get_last_record	= mysqli_fetch_array($res_get_last_record);
				$num_get_last_record	= mysqli_num_rows($res_get_last_record);
				if(strcmp($num_get_last_record,0)===0)
				{
					$error_id	= 1;
				}
				else
				{
					$error_id	= $row_get_last_record['error_id']+1;
				}
				
				$error_module_name	= "Slider";
				$error_file			= $inputFileName;
				$error_status		= '1';
				$error_data_json	= json_encode($error_data);
				
				$sql_insert_error_log	= " INSERT INTO `tbl_error_data`(`error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`) 
											VALUES ('".$error_id."', '".$error_module_name."', '".$error_file."', '".$error_data_json."', '".$error_status."', '".$datetime."', '".$uid."') ";
				$res_insert_error_log 	= mysqli_query($db_con, $sql_insert_error_log) or die(mysqli_error($db_con));
				
				$insertion_flag	= 1;
			}
		}
		
		if($insertion_flag == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Insertion Successfully");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Insertion Error");			
		}
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}
if((isset($_POST['insert_req'])) == "1" && isset($_POST['insert_req']))
{
	$slider_name			= strtolower(mysqli_real_escape_string($db_con,$_POST['slider_name']));
	$slider_content			= mysqli_real_escape_string($db_con,$_POST['slider_content']);
	$slider_title			= mysqli_real_escape_string($db_con,$_POST['slider_title']);
	$slider_status			= mysqli_real_escape_string($db_con,$_POST['slider_status']);	
	$slider_button_title	= mysqli_real_escape_string($db_con,$_POST['slider_button_title']);	
	$slider_button_action	= mysqli_real_escape_string($db_con,$_POST['slider_button_action']);			
	
	$response_array = array();		
	if($slider_name != "" && $slider_status != "")
	{
		$sql_check_slider 		= " SELECT * FROM tbl_slider WHERE slider_name = '".$slider_name."' "; 
		$result_check_slider 	= mysqli_query($db_con,$sql_check_slider) or die(mysqli_error($db_con));
		$num_rows_check_slider 	= mysqli_num_rows($result_check_slider);
		if($num_rows_check_slider == 0)
		{
			$sql_last_rec 		= "SELECT * FROM tbl_slider ORDER by slider_id DESC LIMIT 0,1";
			$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
			$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
			if($num_rows_last_rec == 0)
			{
				$slider_id 		= 1;				
			}
			else
			{
				$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
				$slider_id 		= $row_last_rec['slider_id']+1;
			}
			$extension			= array("jpeg","jpg");
			if(trim($_FILES['slider_image']['name']) != "")
			{
				$slider_image_name		= $_FILES["slider_image"]["name"];
                $file_tmp				= $_FILES["slider_image"]["tmp_name"];
				$fileSize				= $_FILES['slider_image']['size'];
				$file_ext				= strtolower(end(explode('.',$slider_image_name)));	
				$sql_slider_file_name 	= " SELECT `slider_image` FROM `tbl_slider` ORDER BY id DESC LIMIT 1";
				$result_slider_file_name= mysqli_query($db_con,$sql_slider_file_name) or die(mysqli_error($db_con));
				$num_rows_slider_file_name = mysqli_num_rows($result_slider_file_name);
				if($num_rows_slider_file_name == 0)
				{
					$slider_image_file_name	= "slider_1.".$file_ext;
				}
				else
				{
					$row_slider_file_name	= mysqli_fetch_array($result_slider_file_name);
					$slider_file_name		= (int)end(explode($row_slider_file_name['slider_image']));
					$slider_image_file_name	= "slider_".($slider_file_name + 1 ).".".$file_ext;
				}
				if(in_array($file_ext,$extension) && file_exists($file_tmp))
				{
					$slider_image_path = "../images/slider/".$slider_image_file_name;
					if(move_uploaded_file($file_tmp,$slider_image_path))
					{
						$sql_insert_slider 	= " INSERT INTO `tbl_slider`(`slider_id`, `slider_name`, `slider_title`, `slider_content`,`slider_image`";
						$sql_insert_slider 	.= " ,`slider_created`, `slider_created_by`,`slider_status`,`slider_button_action`,`slider_button_title`) ";
						$sql_insert_slider 	.= " VALUES ('".$slider_id."', '".$slider_name."','".$slider_title."','".$slider_content."','".$slider_image_file_name."',";
						$sql_insert_slider 	.= " '".$datetime."', '".$uid."', '".$slider_status."','".$slider_button_action."','".$slider_button_title."')";
					
						$result_insert_slider = mysqli_query($db_con,$sql_insert_slider) or die(mysqli_error($db_con));
						if($result_insert_slider)
						{
							$response_array = array("Success"=>"Success","resp"=>"Record Inserted.");			
						}
						else
						{
							$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
						}						
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>$file_tmp."Upload Failed.File not Uploaded to server.".$slider_image_path);	
					}					
				}	
				else
				{
					$response_array = array("Success"=>"fail","resp"=>"Upload Failed.Please Upload Only JPEG or JPG Files");	
				}										
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Upload Failed.File Name not Available");	
			}								
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Slider ".$slider_name." already Exist");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	
	echo json_encode($response_array);		
}

if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{
	$slider_id				= mysqli_real_escape_string($db_con,$_POST['slider_id']);	
	$slider_name			= strtolower(mysqli_real_escape_string($db_con,$_POST['slider_name']));
	$slider_content			= mysqli_real_escape_string($db_con,$_POST['slider_content']);
	$slider_title			= mysqli_real_escape_string($db_con,$_POST['slider_title']);
	$slider_status			= mysqli_real_escape_string($db_con,$_POST['slider_status']);	
	$slider_button_title	= mysqli_real_escape_string($db_con,$_POST['slider_button_title']);	
	$slider_button_action	= mysqli_real_escape_string($db_con,$_POST['slider_button_action']);			
	$response_array = array();		
	if($slider_name != "" && $slider_status != "")
	{
		$sql_check_slider 		= " select * from tbl_slider where slider_name like '".$slider_name."' and slider_id != '".$slider_id."' "; 
		$result_check_slider 	= mysqli_query($db_con,$sql_check_slider) or die(mysqli_error($db_con));
		$num_rows_check_slider = mysqli_num_rows($result_check_slider);
		if($num_rows_check_slider == 0)
		{
			$sql_update_slider = " UPDATE `tbl_slider` SET `slider_name`='".$slider_name."', `slider_status`='".$slider_status."',";
			$sql_update_slider .= " ,`slider_title`='".$slider_title."',`slider_content	`='".$slider_content."',slider_image = '".$slider_image."' ";
			$sql_update_slider .= " `slider_modified`='".$datetime."',`slider_modified_by	`='".$uid."' WHERE `slider_id` = '".$slider_id."' ";
			$result_update_slider = mysqli_query($db_con,$sql_update_slider) or die(mysqli_error($db_con));
			if($result_update_slider)
			{
				$response_array = array("Success"=>"Success","resp"=>"Update Succesfull");			
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
			}					
		}		
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Slider ".$slider_name." already Exists.");	
		}		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->load_slider_parts)) == "1" && isset($obj->load_slider_parts))
{
	$slider_id 		= mysqli_real_escape_string($db_con,$obj->slider_id);
	$req_type 		= strtolower(mysqli_real_escape_string($db_con,$obj->req_type));
	$response_array = array();
	if($req_type != "")
	{
		if($slider_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$slider_id."' "; // this slider_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_slider_data		= json_decode($row_error_data['error_data']);
		}
		else if(($slider_id != "" && $req_type == "edit") || ($slider_id != "" && $req_type == "view"))
		{
			$sql_slider_data 	= "Select * from tbl_slider where slider_id = '".$slider_id."' ";
			$result_slider_data 	= mysqli_query($db_con,$sql_slider_data) or die(mysqli_error($db_con));
			$row_slider_data		= mysqli_fetch_array($result_slider_data);		
		}	
		$data = '';
		if($slider_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="slider_id" value="'.$row_slider_data['slider_id'].'">';
			$data .= '<input type="hidden" id="update_req" name="update_req" value="1">';			
		}
		elseif($slider_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$slider_id.'">';
			$data .= '<input type="hidden" id="insert_req" name="insert_req" value="1">';
		}	
		elseif($slider_id == "" && $req_type == "add")
		{
			$data .= '<input type="hidden" id="insert_req" name="insert_req" value="1">';			
		}                                                         		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Slider Name <sup class="validfield"><span style="color:#F00;font-size:15px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="slider_name" name="slider_name" class="input-large" data-rule-required="true" ';
		if($slider_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_slider_data['slider_name']).'"'; 
		}
		elseif($slider_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_slider_data->slider_name).'"'; 			
		}
		elseif($slider_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_slider_data['slider_name']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Slider Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Slider Content<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="slider_content" name="slider_content">';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= $row_slider_data->slider_content;		
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= $row_slider_data['slider_content'];					
		}		
		else
		{
			$data .= $row_slider_data['slider_content'];
		}		
		$data .= '</textarea>';	
		$data .= '</div>';
		$data .= '</div> <!-- Slider meta Tags -->';				
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Slider Title<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="slider_title" name="slider_title" class="input-large" ';
		if($slider_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_slider_data['slider_title']).'"'; 
		}
		elseif($slider_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_slider_data->slider_title).'"'; 			
		}
		elseif($slider_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_slider_data['slider_title']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Slider Title -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Slider Button Text<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="slider_button_title" name="slider_button_title" class="input-large" ';
		if($slider_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_slider_data['slider_button_title']).'"'; 
		}
		elseif($slider_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_slider_data->slider_button_title).'"'; 			
		}
		elseif($slider_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_slider_data['slider_button_title']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Slider Title -->';				
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Slider Button Link<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= 'http://planeteducate.com/<input type="text" id="slider_button_action" name="slider_button_action" class="input-large" ';
		if($slider_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_slider_data['slider_button_action']).'"'; 
		}
		elseif($slider_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_slider_data->slider_button_action).'"'; 			
		}
		elseif($slider_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_slider_data['slider_button_action']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Slider Title -->';		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Slider Image<sup class="validfield"><span style="color:#F00;font-size:15px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($slider_id != "" && $req_type == "edit")
		{
			$data .= '<input type="file" id="slider_image" name="slider_image" class="input-large" data-rule-required="true" /> ';			
			$data .= ' <img src="../images/slider/'.$row_slider_data['slider_image'].'" style="width:10%" />'; 
		}
		elseif($slider_id != "" && $req_type == "error")
		{
			$data .= '<input type="file" id="slider_image" name="slider_image" class="input-large" data-rule-required="true" /> ';			
			$data .= ' <img src="../images/slider/'.$row_slider_data->slider_image.'" style="width:10%"  />';
		}
		elseif($slider_id != "" && $req_type == "view")
		{
			$data .= ' <img src="../images/slider/'.$row_slider_data['slider_image'].'" style="width:10%"  />';
		}
		elseif($slider_id == "" && $req_type == "add")
		{
			$data .= '<input type="file" id="slider_image" name="slider_image" class="input-large" data-rule-required="true" /> ';
		}
		$data .= '</div>';
		$data .= '</div> <!-- Slider meta Tags -->';				
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($slider_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="slider_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_slider.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_slider_data->slider_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="slider_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_slider_data->slider_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($slider_id != "" && $req_type == "view")
		{
			if($row_slider_data['slider_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_slider_data['slider_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="slider_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_slider.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_slider_data['slider_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="slider_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_slider_data['slider_status'] == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		$data .= '<div class="form-actions">';
		if($slider_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($slider_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
		}			
		elseif($slider_id != "" && $req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update</button>';						
		}
		$data .= '</div> <!-- Save and cancel -->';	
		$data .= '<script type="text/javascript">';
		$data .= 'CKEDITOR.replace("slider_content",{height:"150", width:"100%"});';
		$data .= '</script>';
		$response_array = array("Success"=>"Success","resp"=>$data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Type Not Defined");		
	}
	echo json_encode($response_array);
}

if((isset($obj->load_slider)) == "1" && isset($obj->load_slider))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit_slider_error;
	$search_text	= $obj->search_text_slider;	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `slider_id`, `slider_name`, `slider_created`, `slider_created_by`, `slider_modified`, `slider_modified_by`, `slider_status`,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.slider_created_by) AS name_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.slider_modified_by	) AS name_modified_by ";
		$sql_load_data  .= " FROM `tbl_slider` AS ti WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND slider_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (slider_name like '%".$search_text."%' or slider_created like '%".$search_text."%' or slider_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY slider_name ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$page_module_data = "";	
			$page_module_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$page_module_data .= '<thead>';
    	  	$page_module_data .= '<tr>';
         	$page_module_data .= '<th style="text-align:center">Sr No.</th>';
			$page_module_data .= '<th style="text-align:center">Page ID</th>';
			$page_module_data .= '<th style="text-align:center">Page Name</th>';
			$page_module_data .= '<th style="text-align:center">Created</th>';
			$page_module_data .= '<th style="text-align:center">Created By</th>';
			$page_module_data .= '<th style="text-align:center">Modified</th>';
			$page_module_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_slider.php",3);
			if($dis)
			{					
				$page_module_data .= '<th>Status</th>';											
			}
			$edit = checkFunctionalityRight("view_slider.php",1);
			if($edit)
			{					
				$page_module_data .= '<th>Edit</th>';			
			}	
			$delete = checkFunctionalityRight("view_slider.php",2);
			if($delete)
			{					
				$page_module_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}					
          	$page_module_data .= '</tr>';
      		$page_module_data .= '</thead>';
      		$page_module_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$page_module_data .= '<tr>';				
				$page_module_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$page_module_data .= '<td style="text-align:center">'.$row_load_data['slider_id'].'</td>';
				$page_module_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['slider_name']).'" class="btn-link" id="'.$row_load_data['slider_id'].'" onclick="addMoreSlider(this.id,\'view\');"></td>';				
				$page_module_data .= '<td style="text-align:center">'.$row_load_data['slider_created'].'</td>';
				$page_module_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$page_module_data .= '<td style="text-align:center">'.$row_load_data['slider_modified'].'</td>';
				$page_module_data .= '<td style="text-align:center">'.$row_load_data['name_modified_by'].'</td>';
				$dis = checkFunctionalityRight("view_slider.php",3);
				if($dis)
				{					
					$page_module_data .= '<td style="text-align:center">';					
					if($row_load_data['slider_status'] == 1)
					{
						$page_module_data .= '<input type="button" value="Active" id="'.$row_load_data['slider_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$page_module_data .= '<input type="button" value="Inactive" id="'.$row_load_data['slider_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$page_module_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_slider.php",1);
				if($edit)
				{				
						$page_module_data .= '<td style="text-align:center">';
						$page_module_data .= '<input type="button" value="Edit" id="'.$row_load_data['slider_id'].'" class="btn-warning" onclick="addMoreSlider(this.id,\'edit\');"></td>';				
				}
				$delete = checkFunctionalityRight("view_slider.php",2);
				if($delete)
				{					
					$page_module_data .= '<td><div class="controls" align="center">';
					$page_module_data .= '<input type="checkbox" value="'.$row_load_data['slider_id'].'" id="batch'.$row_load_data['slider_id'].'" name="batch'.$row_load_data['slider_id'].'" class="css-checkbox batch">';
					$page_module_data .= '<label for="batch'.$row_load_data['slider_id'].'" class="css-label"></label>';
					$page_module_data .= '	</div></td>';										
				}
	          	$page_module_data .= '</tr>';															
			}	
      		$page_module_data .= '</tbody>';
      		$page_module_data .= '</table>';	
			$page_module_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$page_module_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available in Slider");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}

if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$slider_id				= $obj->slider_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();

	$status_flag = 0;
	$sql_check_parent 		= "Select * from tbl_slider where `slider_id` = '".$slider_id."' ";
	$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
	$row_check_parent 		= mysqli_fetch_array($result_check_parent);
	
	$sql_update_status 		= " UPDATE `tbl_slider` SET `slider_status`= '".$curr_status."' ,`slider_modified` = '".$datetime."' ,`slider_modified_by` = '".$uid."' WHERE `slider_id`='".$slider_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	if($result_update_status)
	{
		$status_flag = 1;				
		$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
	}				
	
	if($status_flag == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
	}	
	echo json_encode($response_array);	
}

if((isset($obj->delete_slider)) == "1" && isset($obj->delete_slider))
{
	$response_array = array();		
	$ar_slider_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_slider_id as $slider_id)	
	{
		$sql_delete_slider		= " DELETE FROM `tbl_slider` WHERE `slider_id` = '".$slider_id."' ";
		$result_delete_slider	= mysqli_query($db_con,$sql_delete_slider) or die(mysqli_error($db_con));			
		if($result_delete_slider)
		{
			$del_flag = 1;	
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

// Showing Records From Error Logs Table For Slider========================
if((isset($obj->load_error)) == "1" && isset($obj->load_error))
{
	$start_offset   = 0;
	
	$page 			= $obj->page1;	
	$per_page		= $obj->row_limit1;
	$search_text	= $obj->search_text1;	
	$response_array = array();	
		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `id`, `error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`, `error_modified`, `error_modified_by`, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_created_by) as created_by_name, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_modified_by) as modified_by_name ";
		$sql_load_data  .= " FROM `tbl_error_data`  ";
		$sql_load_data  .= " WHERE error_module_name='Slider' ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND error_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " AND (error_data LIKE '%".$search_text."%' or error_module_name LIKE '%".$search_text."%' ";
			$sql_load_data .= " or error_created like '%".$search_text."%' or error_modified like '%".$search_text."%') ";	
		}
		
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		
		$sql_load_data .=" LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));					
		if(strcmp($data_count,"0") !== 0)
		{		
			$page_module_data = "";	
			$page_module_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$page_module_data .= '<thead>';
    	  	$page_module_data .= '<tr>';
         	$page_module_data .= '<th>Sr. No.</th>';
			$page_module_data .= '<th>Slider Name</th>';
			$page_module_data .= '<th>Created</th>';
			$page_module_data .= '<th>Created By</th>';
			$page_module_data .= '<th>Modified</th>';
			$page_module_data .= '<th>Modified By</th>';
			$page_module_data .= '<th>Edit</th>';			
			$page_module_data .= '<th><div style="text-align:center">';
			$page_module_data .= '<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/></div></th>';
          	$page_module_data .= '</tr>';
      		$page_module_data .= '</thead>';
      		$page_module_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_slider_rec	= json_decode($row_load_data['error_data']);
				
				$er_slider_name	= $get_slider_rec->slider_name;
				
				$page_module_data .= '<tr>';				
				$page_module_data .= '<td>'.++$start_offset.'</td>';				
				$page_module_data .= '<td>';
				$sql_chk_name_already_exist	= " SELECT * FROM `tbl_slider` WHERE `slider_name`='".$er_slider_name."' ";
				$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
				$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);
				
				if(strcmp($num_chk_name_already_exist,"0")===0)
				{
					$page_module_data .= $er_slider_name;
				}
				else
				{
					$page_module_data .= '<span style="color:#E63A3A;">'.$er_slider_name.' [Already Exist]</span>';
				}
				$page_module_data .= '</td>';
				$page_module_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$page_module_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
				$page_module_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$page_module_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$page_module_data .= '<td style="text-align:center"><a href="add_category.php?errorcat='.$row_load_data['error_id'].'">Edit</a></td>';	
				$page_module_data .= '<td><div class="controls" align="center">';
				$page_module_data .= '<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$page_module_data .= '<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$page_module_data .= '</div></td>';										
	          	$page_module_data .= '</tr>';															
			}	
      		$page_module_data .= '</tbody>';
      		$page_module_data .= '</table>';	
			$page_module_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$page_module_data);					
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

if((isset($obj->delete_slider_error)) == "1" && isset($obj->delete_slider_error))
{
	$ar_slider_id 		= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_slider_id as $slider_id)	
	{
		$sql_delete_slider_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$slider_id."' ";
		
		$result_delete_slider_error= mysqli_query($db_con,$sql_delete_slider_error) or die(mysqli_error($db_con));			
		if($result_delete_slider_error)
		{
			$del_flag_error = 1;	
		}			
	}
	if($del_flag_error == 1)
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

?>