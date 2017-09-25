<?php
include("include/routines.php");
$json 		= file_get_contents('php://input');
$obj 		= json_decode($json);
$uid		= $_SESSION['panel_user']['id'];
$utype		= $_SESSION['panel_user']['utype'];

function insertPageModule($response_array,$page_name,$page_status)
{
	global $db_con, $datetime;
	global $uid;
	
	$sql_check_page_module 		= " SELECT * FROM tbl_pages_module WHERE page_name = '".$page_name."' "; 
	$result_check_page_module 		= mysqli_query($db_con,$sql_check_page_module) or die(mysqli_error($db_con));
	$num_rows_check_page_module 	= mysqli_num_rows($result_check_page_module);
	if($num_rows_check_page_module == 0)
	{
		$sql_last_rec 		= "SELECT * FROM tbl_pages_module ORDER by page_id DESC LIMIT 0,1";
		$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$page_id 		= 1;				
		}
		else
		{
			$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
			$page_id 		= $row_last_rec['page_id']+1;
		}
		$sql_insert_page_module 	= " INSERT INTO `tbl_pages_module`(`page_id`, `page_name`, `page_created`, `page_created_by`,`page_status`) ";
		$sql_insert_page_module 	.= " VALUES ('".$page_id."', '".$page_name."', '".$datetime."', '".$uid."', '".$page_status."')";		
		$result_insert_page_module = mysqli_query($db_con,$sql_insert_page_module) or die(mysqli_error($db_con));
		if($sql_insert_page_module)
		{
			if(isset($obj->error_id) && (isset($obj->insert_req)) != "")			
			{
				$sql_delete_error_brand = "DELETE FROM `tbl_error_data` WHERE `error_id`='".$obj->error_id."'";
				$res_delete_error_brand = mysqli_query($db_con, $sql_delete_error_brand) or die(mysqli_error($db_con));				
				if($res_delete_error_brand)
				{
					$response_array = array("Success"=>"Success","resp"=>"Error Data Updated Successfully");
				}
				else
				{
					$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully but Error Data not deleted");												
				}
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully");					
			}	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
		}			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Page Module <b>".ucwords($page_name)."</b> already Exist");
	}
	return $response_array;	
}

if((isset($obj->insert_req)) == "1" && isset($obj->insert_req))
{
	$page_name				= strtolower(mysqli_real_escape_string($db_con,$obj->page_name));
	$page_content			= mysqli_real_escape_string($db_con,$obj->page_content);
	$page_meta_title		= mysqli_real_escape_string($db_con,$obj->page_meta_title);
	$page_meta_description	= mysqli_real_escape_string($db_con,$obj->page_meta_description);
	$page_meta_tags			= mysqli_real_escape_string($db_con,$obj->page_meta_tags);
	$page_status			= mysqli_real_escape_string($db_con,$obj->page_status);	
	
	$response_array = array();	
	
	if($page_name != "" && $page_status != "")
	{
		$sql_check_page_module 		= " SELECT * FROM tbl_pages_module WHERE page_name = '".$page_name."' "; 
		$result_check_page_module 		= mysqli_query($db_con,$sql_check_page_module) or die(mysqli_error($db_con));
		$num_rows_check_page_module 	= mysqli_num_rows($result_check_page_module);
		if($num_rows_check_page_module == 0)
		{
			$sql_last_rec 		= "SELECT * FROM tbl_pages_module ORDER by page_id DESC LIMIT 0,1";
			$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
			$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
			if($num_rows_last_rec == 0)
			{
				$page_id 		= 1;				
			}
			else
			{
				$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
				$page_id 		= $row_last_rec['page_id']+1;
			}
			$page_slug = getSlug($page_name);
			// query for insertion
			$sql_insert_page_module 	= " INSERT INTO `tbl_pages_module`(`page_id`, `page_name`, `page_slug`, `page_content`, `page_meta_title`,";
			$sql_insert_page_module 	.= "  `page_meta_description`, `page_meta_tags`,`page_created`, `page_created_by`,`page_status`) ";
			$sql_insert_page_module 	.= " VALUES ('".$page_id."', '".$page_name."','".$page_slug."','".$page_content."','".$page_meta_title."'";
			$sql_insert_page_module 	.= " ,'".$page_meta_description."','".$page_meta_tags."','".$datetime."', '".$uid."', '".$page_status."')";
			
			$result_insert_page_module = mysqli_query($db_con,$sql_insert_page_module) or die(mysqli_error($db_con));
			if($sql_insert_page_module)
			{
				
				/* Start: changes made by punit 09 Feb 2017*/
				// To Add the slug in .htaccess file
				$f = fopen("../.htaccess", "r+"); // opens for reading and writing 
				$newrule =	"RewriteRule ^".$page_slug."?$  page.php?page_slug=$1    [NC,L]"."\n"; // newrule which will be added
				$insertPos=0;  // variable for saving #staticurlstaticpages postion
				while (!feof($f)) 
				{ //Tests for end-of-file on a file pointer
					$line=fgets($f); //Gets line from file pointer
					if (strpos($line, '#staticurlstaticpages')!==false) 
					{ 
					$insertPos=ftell($f); // ftell will tell pos where pointer moved,here is new line after #staticurlstaticpages.
					$newline =  $newrule;
					}
					else 
					{
						$newline.=$line;   // append existing data with new data of user
					}
				}
				fseek($f,$insertPos);   // move pointer to the file position where we saved above 
				fwrite($f, $newline); // write the file
				fclose($f); // close the file
				/* End: changes made by punit 09 Feb 2017*/

				$response_array = array("Success"=>"Success","resp"=>$sql_insert_page_module);			
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
			}			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Page Module ".$page_name." already Exist");
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
	$page_id				= mysqli_real_escape_string($db_con,$obj->page_id);
	$page_name				= strtolower(mysqli_real_escape_string($db_con,$obj->page_name));
	$page_content			= mysqli_real_escape_string($db_con,$obj->page_content);
	$page_meta_title		= mysqli_real_escape_string($db_con,$obj->page_meta_title);
	$page_meta_description	= mysqli_real_escape_string($db_con,$obj->page_meta_description);
	$page_meta_tags			= mysqli_real_escape_string($db_con,$obj->page_meta_tags);
	$page_status			= mysqli_real_escape_string($db_con,$obj->page_status);	
	$response_array = array();		
	if($page_name != "" && $page_status != "")
	{
		$sql_check_page_module 		= " select * from tbl_pages_module where page_name like '".$page_name."' and page_id != '".$page_id."' "; 
		$result_check_page_module 	= mysqli_query($db_con,$sql_check_page_module) or die(mysqli_error($db_con));
		$num_rows_check_page_module = mysqli_num_rows($result_check_page_module);
		if($num_rows_check_page_module == 0)
		{
			$sql_update_page_module = " UPDATE `tbl_pages_module` SET `page_name`='".$page_name."', `page_status`='".$page_status."',";
			$sql_update_page_module .= " `page_slug`='".str_replace(" ","-",$page_name)."',`page_content`='".$page_content."',";
			$sql_update_page_module .= " `page_meta_title`='".$page_meta_title."',`page_meta_description`='".$page_meta_description."',`page_meta_tags`='".$page_meta_tags."',";
			$sql_update_page_module .= " `page_modified`='".$datetime."',`page_modified_by`='".$uid."' WHERE `page_id` = '".$page_id."' ";
			$result_update_page_module = mysqli_query($db_con,$sql_update_page_module) or die(mysqli_error($db_con));
			if($result_update_page_module)
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
			$response_array = array("Success"=>"fail","resp"=>"Page Module ".$page_name." already Exists.");	
		}		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->load_page_module_parts)) == "1" && isset($obj->load_page_module_parts))
{
	$page_id 		= mysqli_real_escape_string($db_con,$obj->page_id);
	$req_type 		= strtolower(mysqli_real_escape_string($db_con,$obj->req_type));
	$response_array = array();
	if($req_type != "")
	{
		if($page_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$page_id."' "; // this page_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_page_module_data		= json_decode($row_error_data['error_data']);
		}
		else if(($page_id != "" && $req_type == "edit") || ($page_id != "" && $req_type == "view"))
		{
			$sql_page_module_data 	= "Select * from tbl_pages_module where page_id = '".$page_id."' ";
			$result_page_module_data 	= mysqli_query($db_con,$sql_page_module_data) or die(mysqli_error($db_con));
			$row_page_module_data		= mysqli_fetch_array($result_page_module_data);		
		}	
		$data = '';
		if($page_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="page_id" value="'.$row_page_module_data['page_id'].'">';
		}
		elseif($page_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$page_id.'">';
		}	                                                         		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Page Module Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="page_name" name="page_name" class="input-large" data-rule-required="true" ';
		if($page_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_page_module_data['page_name']).'"'; 
		}
		elseif($page_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_page_module_data->page_name).'"'; 			
		}
		elseif($page_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_page_module_data['page_name']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Page Module Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Page Module Content<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="page_content" name="page_content">';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= $row_page_module_data->page_content;		
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= $row_page_module_data['page_content'];					
		}		
		else
		{
			$data .= $row_page_module_data['page_content'];
		}		
		$data .= '</textarea>';	
		$data .= '</div>';
		$data .= '</div> <!-- Page Module meta Tags -->';				
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Page Module Meta Title<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="page_meta_title" name="page_meta_title" class="input-large" ';
		if($page_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_page_module_data['page_meta_title']).'"'; 
		}
		elseif($page_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_page_module_data->page_meta_title).'"'; 			
		}
		elseif($page_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_page_module_data['page_meta_title']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Page Module Meta Title -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Page Module Meta Description<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="page_meta_description" name="page_meta_description">';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= $row_page_module_data->page_meta_description;		
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= $row_page_module_data['page_meta_description'];					
		}		
		else
		{
			$data .= $row_page_module_data['page_meta_description'];
		}		
		$data .= '</textarea>';				
		$data .= '</div>';
		$data .= '</div> <!-- Page Module meta Tags -->';		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Page Module Meta Tag<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="page_meta_tags" name="page_meta_tags" class="input-large" ';
		if($page_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_page_module_data['page_meta_tags']).'"'; 
		}
		elseif($page_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_page_module_data->page_meta_tags).'"'; 			
		}
		elseif($page_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_page_module_data['page_meta_tags']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Page Module meta Tags -->';				
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($page_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="page_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_page_module.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_page_module_data->page_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="page_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_page_module_data->page_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($page_id != "" && $req_type == "view")
		{
			if($row_page_module_data['page_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_page_module_data['page_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="page_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_page_module.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_page_module_data['page_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="page_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_page_module_data['page_status'] == 0)
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
		if($page_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($page_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
		}			
		elseif($page_id != "" && $req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update</button>';						
		}
		$data .= '</div> <!-- Save and cancel -->';	
		$data .= '<script type="text/javascript">';
		$data .= 'CKEDITOR.replace("page_content",{height:"150", width:"100%"});';
		$data .= 'CKEDITOR.replace("page_meta_description",{height:"150", width:"100%"});';
		$data .= '</script>';
		$response_array = array("Success"=>"Success","resp"=>$data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Type Not Defined");		
	}
	echo json_encode($response_array);
}

if((isset($obj->load_page_module)) == "1" && isset($obj->load_page_module))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->rowlimit_page_module;
	$search_text	= $obj->search_text_page_module;	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `page_id`, `page_name`, `page_created`, `page_created_by`, `page_modified`, `page_modified_by`, `page_status`,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.page_created_by) AS name_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = ti.page_modified_by) AS name_midified_by ";
		$sql_load_data  .= " FROM `tbl_pages_module` AS ti WHERE 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND page_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (page_name like '%".$search_text."%' or page_created like '%".$search_text."%' or page_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY page_name ASC LIMIT $start, $per_page ";
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
			$dis = checkFunctionalityRight("view_page_module.php",3);
			if($dis)
			{					
				$page_module_data .= '<th>Status</th>';											
			}
			$edit = checkFunctionalityRight("view_page_module.php",1);
			if($edit)
			{					
				$page_module_data .= '<th>Edit</th>';			
			}	
			$delete = checkFunctionalityRight("view_page_module.php",2);
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
				$page_module_data .= '<td style="text-align:center">'.$row_load_data['page_id'].'</td>';
				$page_module_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['page_name']).'" class="btn-link" id="'.$row_load_data['page_id'].'" onclick="addMorePageModule(this.id,\'view\');"></td>';				
				$page_module_data .= '<td style="text-align:center">';
				if($row_load_data['page_created'] == "0000-00-00 00:00:00" || $row_load_data['page_created'] == "")
				{
					$page_module_data .= '<span style="color:#F00">Not Available</span>';
				}
				else
				{
					$page_module_data .= $row_load_data['page_created'];					
				}
				$page_module_data .= '</td>';
				$page_module_data .= '<td style="text-align:center">';
				if(trim($row_load_data['name_created_by']) == "")
				{
					$page_module_data .= '<span style="#color:F00">Not Available</span>';
				}
				else
				{
					$page_module_data .= $row_load_data['name_created_by'];					
				}
				$page_module_data .= '</td>';
				$page_module_data .= '<td style="text-align:center">';
				if($row_load_data['page_modified'] == "0000-00-00 00:00:00" || $row_load_data['page_modified'] == "")
				{
					$page_module_data .= '<span style="color:#F00">Not Available</span>';
				}
				else
				{
					$page_module_data .= $row_load_data['page_modified'];					
				}
				
				$page_module_data .= '</td>';
				$page_module_data .= '<td style="text-align:center">';
				if(trim($row_load_data['name_midified_by']) == "")
				{
					$page_module_data .= '<span style="color:#F00">Not Available</span>';
				}
				else
				{
					$page_module_data .= $row_load_data['name_midified_by'];					
				}				
				$page_module_data .= '</td>';												
				$dis = checkFunctionalityRight("view_page_module.php",3);
				if($dis)
				{					
					$page_module_data .= '<td style="text-align:center">';					
					if($row_load_data['page_status'] == 1)
					{
						$page_module_data .= '<input type="button" value="Active" id="'.$row_load_data['page_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$page_module_data .= '<input type="button" value="Inactive" id="'.$row_load_data['page_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$page_module_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_page_module.php",1);
				if($edit)
				{				
						$page_module_data .= '<td style="text-align:center">';
						$page_module_data .= '<input type="button" value="Edit" id="'.$row_load_data['page_id'].'" class="btn-warning" onclick="addMorePageModule(this.id,\'edit\');"></td>';				
				}
				$delete = checkFunctionalityRight("view_page_module.php",2);
				if($delete)
				{					
					$page_module_data .= '<td><div class="controls" align="center">';
					$page_module_data .= '<input type="checkbox" value="'.$row_load_data['page_id'].'" id="batch'.$row_load_data['page_id'].'" name="batch'.$row_load_data['page_id'].'" class="css-checkbox batch">';
					$page_module_data .= '<label for="batch'.$row_load_data['page_id'].'" class="css-label"></label>';
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
			$response_array = array("Success"=>"fail","resp"=>"No Data Available in Page Module");
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
	$page_id				= $obj->page_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();

	$status_flag = 0;
	$sql_check_parent 		= "Select * from tbl_pages_module where `page_id` = '".$page_id."' ";
	$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
	$row_check_parent 		= mysqli_fetch_array($result_check_parent);
	
	$sql_update_status 		= " UPDATE `tbl_pages_module` SET `page_status`= '".$curr_status."' ,`page_modified` = '".$datetime."' ,`page_modified_by` = '".$uid."' WHERE `page_id`='".$page_id."' ";
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

if((isset($obj->delete_page_module)) == "1" && isset($obj->delete_page_module))
{
	$response_array = array();		
	$ar_page_module_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_page_module_id as $page_id)	
	{
		$sql_delete_page_module		= " DELETE FROM `tbl_pages_module` WHERE `page_id` = '".$page_id."' ";
		$result_delete_page_module	= mysqli_query($db_con,$sql_delete_page_module) or die(mysqli_error($db_con));			
		if($result_delete_page_module)
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

?>