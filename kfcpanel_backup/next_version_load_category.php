<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

function insertCatogery($uid,$db_con,$cat_name,$cat_description,$cat_parent,$cat_status,$cat_meta_description,$cat_meta_tags,$cat_meta_title,$response_array)
{
	global $obj, $datetime;
	$cat_slug 			= strtolower(str_replace(" ","-",$cat_name)); 
	if($cat_parent == "parent")
	{
			$sql_check_sort_ava = "SELECT cat_sort_order FROM tbl_category WHERE cat_type = 'parent' ORDER by cat_id DESC LIMIT 0,1 ";			
			$result_check_sort_ava = mysqli_query($db_con,$sql_check_sort_ava) or die(mysqli_error($db_con));
			$num_rows_check_sort_ava = mysqli_fetch_array($result_check_sort_ava);
			if($num_rows_check_sort_ava != 0)
			{
				$cat_sort_order		= $num_rows_check_sort_ava['cat_sort_order']+1;	
			}
			else
			{
				$cat_sort_order		= 1;
			}			
	}
	else
	{
			$sql_sort_order			= "SELECT cat_sort_order FROM tbl_category WHERE cat_type = '".$cat_parent."' ORDER by cat_sort_order DESC";
			$result_sort_order 		= mysqli_query($db_con,$sql_sort_order) or die(mysqli_error($db_con)); 
			$row_sort_order			= mysqli_fetch_array($result_sort_order);
			$num_rows_sort_order 	= mysqli_num_rows($result_sort_order);
			if($num_rows_sort_order == 0)
			{
				$cat_sort_order		= 1;
			}
			else
			{
				$cat_sort_order		= $row_sort_order['cat_sort_order']+1;
			}
	}
		
	$sql_check_cat 		= " select * from tbl_category where cat_name = '".$cat_name."' and cat_type = '".$cat_parent."' "; 
	$result_check_cat 	= mysqli_query($db_con,$sql_check_cat) or die(mysqli_error($db_con));
	$num_rows_check_cat = mysqli_num_rows($result_check_cat);
	if($num_rows_check_cat == 0)
	{
		$sql_last_rec = "Select * from tbl_category order by cat_id desc LIMIT 0,1";
		$result_last_rec = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec = mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$cat_id 		= 1;				
		}
		else
		{
			$row_last_rec = mysqli_fetch_array($result_last_rec);				
			$cat_id 		= $row_last_rec['cat_id']+1;
		}
		$sql_insert_category = " INSERT INTO `tbl_category`(`cat_id`, `cat_name`, `cat_description`, `cat_type`, ";
		$sql_insert_category .= " `cat_slug`, `cat_sort_order`, `cat_status`, ";
		$sql_insert_category .= " `cat_created`, `cat_createdby`, `cat_meta_description`, ";
		$sql_insert_category .= " `cat_meta_title`, `cat_meta_tags`) ";
		$sql_insert_category .= " VALUES ('".$cat_id."','".$cat_name."','".$cat_description."', '".$cat_parent."', ";
		$sql_insert_category .= " '".$cat_slug."','".$cat_sort_order."','".$cat_status."', ";
		$sql_insert_category .= " '".$datetime."', '".$uid."', '".$cat_meta_description."', ";
		$sql_insert_category .= " '".$cat_meta_title."','".$cat_meta_tags."')";			
		$result_insert_category = mysqli_query($db_con,$sql_insert_category) or die(mysqli_error($db_con));
		if($result_insert_category)
		{
			// =============================================================================================================
			# START : Category Path Table
			// =============================================================================================================
			$level	= 0;
			// Find Count of Parent ID [From Category Table] of current Cat_ID [From Category Path Table]
			$sql_get_count_cat_and_cat_path	= " SELECT * ";
			$sql_get_count_cat_and_cat_path	.= " FROM tbl_category AS tc INNER JOIN tbl_category_path_master AS tcpm ";
			$sql_get_count_cat_and_cat_path	.= " 	ON tc.cat_type=tcpm.cat_id ";
			$sql_get_count_cat_and_cat_path	.= " WHERE tc.cat_id='".$cat_id."' ";
			$sql_get_count_cat_and_cat_path	.= " 	AND tcpm.cat_id='".$cat_parent."' ";
			$sql_get_count_cat_and_cat_path	.= " ORDER BY tcpm.level ASC ";
			$res_get_count_cat_and_cat_path	= mysqli_query($db_con, $sql_get_count_cat_and_cat_path) or die(mysqli_error($db_con));
			$num_get_count_cat_and_cat_path	= mysqli_num_rows($res_get_count_cat_and_cat_path);
			
			// Inserting entries in the category path table except the parent entry and level will be increase
			while($row_get_count_cat_and_cat_path = mysqli_fetch_array($res_get_count_cat_and_cat_path))
			{
				$sql_insert_into_category_path	= " INSERT INTO `tbl_category_path_master`(`cat_id`, `path_id`, `level`) ";
				$sql_insert_into_category_path	.= " VALUES ('".$cat_id."', '".$row_get_count_cat_and_cat_path['path_id']."', '".$level."') ";
				$res_insert_into_category_path	= mysqli_query($db_con, $sql_insert_into_category_path) or die(mysqli_error($db_con));
				
				$level++;
			}
			
			// Inserting entries in the category path table [Parent Entry]
			$sql_insert_into_category_path	= " INSERT INTO `tbl_category_path_master`(`cat_id`, `path_id`, `level`) ";
			$sql_insert_into_category_path	.= " VALUES ('".$cat_id."', '".$cat_id."', '".$level."') ";
			$res_insert_into_category_path	= mysqli_query($db_con, $sql_insert_into_category_path) or die(mysqli_error($db_con));
			// =============================================================================================================
			# END : Category Path Table
			// =============================================================================================================
			
			if($cat_parent == "parent")
			{
				// Change by punit 10 august 2017
				
				/*$response_array = insertCatogery($uid,$db_con,"none",$cat_description,$cat_id,$cat_status,$cat_meta_description,$cat_meta_tags,$cat_meta_title,$response_array);*/			
			}			
			if(isset($obj->error_id) && (isset($obj->insert_req)) != "")			
			{
				$sql_delete_error_cat = "DELETE FROM `tbl_error_data` WHERE `error_id`='".$obj->error_id."'";
				$res_delete_error_cat = mysqli_query($db_con, $sql_delete_error_cat) or die(mysqli_error($db_con));				
				if($res_delete_error_cat)
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
		$msg = 'Category <b> "'.ucwords($cat_name).'" </b> Already Exist';
		$response_array = array("Success"=>"fail","resp"=>($msg));
	}
	return $response_array;
}


if(isset($_FILES['file']))
{
	$sourcePath 		= $_FILES['file']['tmp_name'];       // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; // Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$cat_id 	= 0;
	$product_id	= 0;
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
			$cat_name 			= trim($allDataInSheet[$i]["A"]);
			$cat_description 	= trim($allDataInSheet[$i]["B"]);
			$cat_parent_type	= strtolower(trim($allDataInSheet[$i]["C"]));
			$cat_meta_title			= trim($allDataInSheet[$i]["D"]);
			$cat_meta_tags			= trim($allDataInSheet[$i]["E"]);
			$cat_meta_description	= trim($allDataInSheet[$i]["F"]);
			$cat_status			= 1;
			
			
			$query = "SELECT `id`,`cat_id`, `cat_name`, `cat_description`, `cat_type`, `cat_slug`, `cat_sort_order`,
							`cat_image`, `cat_meta_title`, `cat_meta_tags`, `cat_meta_description`, `cat_status`, `cat_created`,
							`cat_createdby`, `cat_modified`, `cat_modifiedby` 
						FROM `tbl_category` 
						WHERE `cat_name`='".$cat_name."'
							AND `cat_description`='".$cat_description."'
							AND `cat_type`='".$cat_parent_type."'
							AND `cat_meta_title`='".$cat_meta_title."'
							AND `cat_meta_tags`='".$cat_meta_tags."'
							AND `cat_meta_description`='".$cat_meta_description."'" ;
							
			$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
			$recResult 	= mysqli_fetch_array($sql);
			
			$existName 		= $recResult["cat_name"];
			$existCatType	= $recResult['cat_type'];
			if($existName=="" )
			{
				if(strcmp($cat_parent_type,"parent")===0)
				{
					$cat_parent_type 	= "parent";
					$response_array 	= insertCatogery($uid,$db_con,$cat_name,$cat_description,$cat_parent_type,$cat_status,$cat_meta_description,$cat_meta_tags,$cat_meta_title,$response_array);
				}
				elseif(strcmp($cat_parent_type,"parent")!==0)
				{
					$sql_get_cat_type	= "SELECT cat_id, cat_type FROM tbl_category WHERE cat_name='".$cat_parent_type."'";
					$res_get_cat_type 	= mysqli_query($db_con, $sql_get_cat_type) or die(mysqli_error($db_con));
					$row_get_cat_type 	= mysqli_fetch_array($res_get_cat_type);
					$num_get_cat_type 	= mysqli_num_rows($res_get_cat_type);
					
					if(strcmp($num_get_cat_type,"0")!==0)
					{
						$cat_parent_type	= $row_get_cat_type['cat_id'];
						$response_array 	= insertCatogery($uid,$db_con, $cat_name,$cat_description,$cat_parent_type,$cat_status,$cat_meta_description,$cat_meta_tags,$cat_meta_title,$response_array);
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
						$error_data = array("cat_name"=>$cat_name, "cat_description"=>$cat_description, "cat_type"=>$cat_parent_type, "cat_meta_title"=>$cat_meta_title, "cat_meta_tags"=>$cat_meta_tags, "cat_meta_description"=>$cat_meta_description);	
						
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
						
						$error_module_name	= "category";
						$error_file			= $inputFileName;
						$error_status		= '1';
						$error_data_json	= json_encode($error_data);
						
						$sql_insert_error_log	= " INSERT INTO `tbl_error_data`(`error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`) 
													VALUES ('".$error_id."', '".$error_module_name."', '".$error_file."', '".$error_data_json."', '".$error_status."', '".$datetime."', '".$uid."') ";
						$res_insert_error_log 	= mysqli_query($db_con, $sql_insert_error_log) or die(mysqli_error($db_con));	
						
						$insertion_flag	= 1;
					}
				}
			}
			else
			{
				// error data array
				$error_data = array("cat_name"=>$cat_name, "cat_description"=>$cat_description, "cat_type"=>$cat_parent_type, "cat_meta_title"=>$cat_meta_title, "cat_meta_tags"=>$cat_meta_tags, "cat_meta_description"=>$cat_meta_description);	
				
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
				
				$error_module_name	= "category";
				$error_file			= $inputFileName;
				$error_status		= '1';
				$error_data_json	= json_encode($error_data);
				
				$sql_insert_error_log	= " INSERT INTO `tbl_error_data`(`error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`) 
											VALUES ('".$error_id."', '".$error_module_name."', '".$error_file."', '".$error_data_json."', '".$error_status."', '".$datetime."', '".$uid."') ";
				$res_insert_error_log 	= mysqli_query($db_con, $sql_insert_error_log) or die(mysqli_error($db_con));
				
				$insertion_flag	= 1;			}
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

#===========================================================================
# START : Function For finding the Sub Cat Value [ Hierarchical Structure ]
#===========================================================================
function getSubCatValue($cat_id, $userType)	// Parameters : Parent ID and userType [i.e. Add, Edit, View]
{
	global $db_con;
	$data	= '';
	
	if($userType == 'add')
	{
		// Get The children of this Cat ID from Category Master Table
		$sql_get_children_frm_cm	= " SELECT * FROM `tbl_category` WHERE `cat_type`='".$cat_id."' AND `cat_name`!='none' AND `cat_status`='1' ";
		$res_get_children_frm_cm	= mysqli_query($db_con, $sql_get_children_frm_cm) or die(mysqli_error($db_con));
		$num_get_children_frm_cm	= mysqli_num_rows($res_get_children_frm_cm);
		
		if($num_get_children_frm_cm != 0)
		{
			while($row_get_children_frm_cm = mysqli_fetch_array($res_get_children_frm_cm))
			{
				// Get the count and the related parent ids of this category from the Category Path table
				$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` ";
				$sql_get_count_and_all_parent	.= " WHERE `cat_id`='".$row_get_children_frm_cm['cat_id']."' ";
				$sql_get_count_and_all_parent	.= " ORDER BY level ASC ";
				$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
				$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
				
				if($num_get_count_and_all_parent != 0)
				{
					$opt_value	= '';
					while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
					{
						// Find the name of the category
						$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` ";
						$sql_get_name_of_cat	.= " FROM `tbl_category` ";
						$sql_get_name_of_cat	.= " WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
						$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
						$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
						
						$parent_cat_name		= $row_get_name_of_cat['cat_name'];
						
						$opt_value	.= $parent_cat_name.' > ';
					}
					$data .= '<option value="'.$row_get_children_frm_cm['cat_id'].'">'.substr(ucwords($opt_value),0,-3).'</option>';
					$data1	= getSubCatValue($row_get_children_frm_cm['cat_id'],'add');
					if($data1 != '')
					{
						$data	.= $data1;
					}
				}
			}
			return $data;	
		}
		else
		{
			return $data;	
		}
	}
	elseif($userType == 'view')
	{
		// Get the count and the related parent ids of this category from the Category Path table
		$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` ";
		$sql_get_count_and_all_parent	.= " WHERE `cat_id`='".$cat_id."' ";
		$sql_get_count_and_all_parent	.= " ORDER BY level ASC ";
		$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
		$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
		
		if($num_get_count_and_all_parent != 0)
		{
			$opt_value	= '';
			while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
			{
				// Find the name of the category
				$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` ";
				$sql_get_name_of_cat	.= " FROM `tbl_category` ";
				$sql_get_name_of_cat	.= " WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
				$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
				$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
				
				$parent_cat_name		= $row_get_name_of_cat['cat_name'];
				
				$opt_value	.= $parent_cat_name.' > ';
			}
			$data .= '<label class="control-label" >'.substr(ucwords($opt_value),0,-3).'</label>';
		}
		
		return $data;		
	}
	else
	{
		return $data;	
	}
}
#===========================================================================
# END : Function For finding the Sub Cat Value [ Hierarchical Structure ]
#===========================================================================

if((isset($obj->load_add_cat_part)) != "" && isset($obj->load_add_cat_part))
{
	$cat_id 	= $obj->cat_id;
	$req_type 	= $obj->req_type;		
	if($cat_id != "" && $req_type == "error")
	{
		$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$cat_id."' "; // this cat id is error id from error table
		$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
		$row_error_data		= mysqli_fetch_array($result_error_data);	
		$row_cat_data		= json_decode($row_error_data['error_data']);
	}
	else if($cat_id != "" && $req_type == "edit")
	{
		$sql_cat_data 		= " Select `cat_id`, `cat_name`, `cat_description`, `cat_type`, `cat_slug`, `cat_sort_order`, `cat_image`, `cat_meta_title`, ";
		$sql_cat_data 		.= " `cat_meta_tags`, `cat_meta_description`, `cat_status` from tbl_category where cat_id = '".$cat_id."' ";
		$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
		$row_cat_data		= mysqli_fetch_array($result_cat_data);		
	}	
	else if($cat_id != "" && $req_type == "view")
	{
		$sql_cat_data 		= " SELECT `id`, `cat_id`, `cat_name`, `cat_description`, `cat_type`, `cat_slug`, `cat_sort_order`, ";
		$sql_cat_data 		.= " `cat_image`, `cat_meta_title`, `cat_meta_tags`, `cat_meta_description`, `cat_status`, `cat_created`, ";
		$sql_cat_data 		.= " `cat_createdby`, `cat_modified`, `cat_modifiedby`,(SELECT cat_name FROM `tbl_category` tc WHERE cat_id = tc.cat_type) as parent_name FROM `tbl_category` WHERE `cat_id` = '".$cat_id."' ";
		$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
		$row_cat_data		= mysqli_fetch_array($result_cat_data);		
	}		
	$data = '';
		if($cat_id != "" && $req_type == "edit")
		{
			$data = '<input type="hidden" id="cat_id" value="'.$row_cat_data['cat_id'].'">';
		}		
		elseif($cat_id != "" && $req_type == "error")
		{
			$data = '<input type="hidden" id="error_id" value="'.$cat_id.'">';
		}		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Category Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="cat_name" name="cat_name" class="input-large" data-rule-required="true"';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_cat_data->cat_name.'"';					
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_cat_data['cat_name'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_cat_data['cat_name'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Category Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Description <sup class="validfield"><span style="color:#F00;font-size:20px;"></span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="cat_description" name="cat_description">';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= $row_cat_data->cat_description;		
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= $row_cat_data['cat_description'];					
		}		
		else
		{
			$data .= $row_cat_data['cat_description'];
		}		
		$data .= '</textarea>';				
		$data .= '</div>';
		$data .= '</div> <!--Description-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Type <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select name="cat_type" id="cat_type" placeholder="Type" class="select2-me input-large" data-rule-required="true" >';
			$data .= '<option value="">Select Type</option>';						
		}
		
		//$data .= '<br>Prathamesh : '.$req_type.'<br>';
		
		if(($cat_id == "" && $req_type == "add") || ($cat_id != "" && $req_type == "error"))
		{
			$data .= '<option value="parent">Parent</option>';			
			/*$sql_get_parent = " SELECT DISTINCT cat_id,cat_name FROM `tbl_category` ";
			$result_get_parent = mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));														
			while($row_get_parent = mysqli_fetch_array($result_get_parent))
			{															
				$data .= '<option value="'.$row_get_parent['cat_id'].'">'.ucwords($row_get_parent['cat_name']).'</option>';
			}*/
			
			#========================================================================================================
			# Hierarchical DDL
			#	Like:
			#		cat_1
			#		cat_1 > cat_2
			#		cat_1 > cat_2 > cat_3
			#		cat_4 
			#		cat_4 > cat_5
			#		cat_4 > cat_5 > cat_6
			#========================================================================================================
			
			// Query for checking Category is not empty
			$sql_chk_isParent	= " SELECT * FROM `tbl_category` ";
			$sql_chk_isParent	.= " WHERE `cat_status`='1' ";
			$sql_chk_isParent	.= " 	AND `cat_name`!='none' ";
			$sql_chk_isParent	.= " 	AND `cat_type`='parent' ";
			$sql_chk_isParent	.= " ORDER BY `cat_name` ASC ";
			$res_chk_isParent	= mysqli_query($db_con, $sql_chk_isParent) or die(mysqli_error($db_con));
			$num_chk_isParent	= mysqli_num_rows($res_chk_isParent);
			
			if($num_chk_isParent != 0)
			{
				while($row_chk_isParent = mysqli_fetch_array($res_chk_isParent))
				{
					$data .= '<option value="'.$row_chk_isParent['cat_id'].'">'.ucwords($row_chk_isParent['cat_name']).'</option>';
					$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'add');
				}
			}
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			if(strcmp(trim($row_cat_data['cat_type']),"parent") == 0)
			{
				$data .= '<label class="control-label" >Parent</label>';		
			}
			else
			{
				$data	.= getSubCatValue($row_cat_data['cat_id'], 'view');
				//$data .= '<label class="control-label" >'.ucwords($row_get_parent['parent_name']).'</label>';
			}
		}		
		elseif($cat_id != "" && $req_type == "edit")
		{
			/*if(strcmp(trim($row_cat_data['cat_type']),"parent") == 0)
			{
				$data .= '<option value="parent" selected="selected">Parent</option>';			
			}
			else
			{
				$data .= '<option value="parent" >Parent</option>';							
				//$data	.= getSubCatValue($row_cat_data['cat_id'], 'edit');
			}
			$sql_get_parent 		= "SELECT distinct cat_id,cat_name FROM `tbl_category` where cat_type = 'parent' ";
			$result_get_parent 		= mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));														
			while($row_get_parent 	= mysqli_fetch_array($result_get_parent))
			{		
				if($row_get_parent['cat_id'] == $row_cat_data['cat_type'])
				{
					$data .= '<option value="'.$row_get_parent['cat_id'].'" selected>'.ucwords($row_get_parent['cat_name']).'</option>';
				}
				elseif(strcmp(trim($row_cat_data['cat_name']),$row_get_parent['cat_name']) == 0){}
				else
				{
					$data .= '<option value="'.$row_get_parent['cat_id'].'">'.ucwords($row_get_parent['cat_name']).'</option>';
				}
			}*/
			
			if(strcmp(trim($row_cat_data['cat_type']),"parent") == 0)
			{
				$data .= '<option value="parent" selected="selected">Parent</option>';
			}
			else
			{
				$data .= '<option value="parent" >Parent</option>';		
			}
			
			// Query for checking Category is not empty
			$sql_chk_isParent	= " SELECT * FROM `tbl_category` WHERE `cat_status`='1' AND `cat_name`!='none' ORDER BY `cat_type` DESC, `cat_type` ASC  ";
			$res_chk_isParent	= mysqli_query($db_con, $sql_chk_isParent) or die(mysqli_error($db_con));
			$num_chk_isParent	= mysqli_num_rows($res_chk_isParent);
			
			if($num_chk_isParent != 0)
			{
				while($row_chk_isParent = mysqli_fetch_array($res_chk_isParent))
				{
					// Get the count and the related parent ids of this category from the Category Path table
					$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` WHERE `cat_id`='".$row_chk_isParent['cat_id']."' ORDER BY level ASC ";
					$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
					$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
					
					if($num_get_count_and_all_parent != 0)
					{
						$opt_value	= '';
						while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
						{
							// Find the name of the category
							$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` FROM `tbl_category` WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
							$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
							$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
							
							$parent_cat_name		= $row_get_name_of_cat['cat_name'];
							
							$opt_value	.= $parent_cat_name.' > ';
						}
						
						if($row_chk_isParent['cat_id'] == $row_cat_data['cat_type'])
						{
							$data .= '<option value="'.$row_chk_isParent['cat_id'].'" selected="selected">'.substr(ucwords($opt_value),0,-3).'</option>';
						}
						else
						{
							$data .= '<option value="'.$row_chk_isParent['cat_id'].'">'.substr(ucwords($opt_value),0,-3).'</option>';	
						}
							
					}
				}	
			}
		}
		$data .= '</select>';
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div> <!--Parent-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Image<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		if($cat_id != "" && $req_type == "view")
		{
			if($row_cat_data['cat_image'] == "")
			{
				$data .= '<label class="control-label" style="color:#E63A3A">No Image</label>';
			}
			else
			{
				$data .= $row_cat_data['cat_image'];				
			}
		}
		else
		{
			$data .= '<input type="file" name="cat_file" id="cat_file" >';
		}		
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Tags <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="cat_meta_tags" name="cat_meta_tags" class="input-large"';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_cat_data->cat_meta_tags.'"';
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_cat_data['cat_meta_tags'].'" disabled';
		}		
		else
		{
			$data .= 'value="'.$row_cat_data['cat_meta_tags'].'"';
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Tags-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Description <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="cat_meta_description" name="cat_meta_description" data-rule-required="false">';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= $row_cat_data->cat_meta_description;
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= $row_cat_data['cat_meta_description'];
		}		
		else
		{
			$data .= $row_cat_data['cat_meta_description'];
		}		
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Description-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Title <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="cat_meta_title" name="cat_meta_title" class="input-large"';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_cat_data->cat_meta_title.'"';
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_cat_data['cat_meta_title'].'" disabled';
		}		
		else
		{
			$data .= 'value="'.$row_cat_data['cat_meta_title'].'"';
		}		
		$data .= '/>';		
		$data .= '</div>';
		$data .= '</div> <!--Meta Title-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="cat_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_category.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_cat_data->cat_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="cat_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_cat_data->cat_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			if($row_cat_data['cat_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_cat_data['cat_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="cat_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_category.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_cat_data['cat_status'] == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="cat_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_cat_data['cat_status'] == 0)
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
		if($cat_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($cat_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';
		}
		else if($cat_id != "" && $req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update</button>';			
		}
		$data .= '</div> <!-- Save and cancel -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#cat_type").select2();';		
		$data .= 'CKEDITOR.replace("cat_description",{height:"150", width:"100%"});';
		if($cat_id != "" && $req_type == "view")
		{
			$data .= '$("#cat_description").prop("disabled","true");';
		}		
		$data .= '</script>';
	$response_array = array("Success"=>"Success","resp"=>$data);
	echo json_encode($response_array);
}
if((isset($obj->insert_req)) == "1" && isset($obj->insert_req))
{
	$cat_name				= strtolower(mysqli_real_escape_string($db_con,$obj->cat_name));
	$cat_description		= mysqli_real_escape_string($db_con,$obj->cat_description);
	$cat_parent				= mysqli_real_escape_string($db_con,$obj->cat_type);
	$cat_status				= mysqli_real_escape_string($db_con,$obj->cat_status);
	$cat_meta_description	= mysqli_real_escape_string($db_con,$obj->cat_meta_description);
	$cat_meta_title			= strtolower(mysqli_real_escape_string($db_con,$obj->cat_meta_title));
	$cat_meta_tags			= strtolower(mysqli_real_escape_string($db_con,$obj->cat_meta_tags));

	$response_array = array();	
	if($cat_name != "" && $cat_parent != "" && $cat_status != "")
	{
		$response_array = insertCatogery($uid,$db_con,$cat_name,$cat_description,$cat_parent,$cat_status,$cat_meta_description,$cat_meta_tags,$cat_meta_title,$response_array);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}



//==========================================================================================================
//====================Start : Done by satish 15032017 for update_category=====================================
//==========================================================================================================



if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{
	$cat_id				= mysqli_real_escape_string($db_con,$obj->cat_id);
	$cat_name			= mysqli_real_escape_string($db_con,$obj->cat_name);
	$cat_description	= mysqli_real_escape_string($db_con,$obj->cat_description);	
	$cat_parent			= mysqli_real_escape_string($db_con,$obj->cat_type);	
	$cat_status			= mysqli_real_escape_string($db_con,$obj->cat_status);
	$cat_meta_description= mysqli_real_escape_string($db_con,$obj->cat_meta_description);
	$cat_meta_title		= mysqli_real_escape_string($db_con,$obj->cat_meta_title);
	$cat_meta_tags		= mysqli_real_escape_string($db_con,$obj->cat_meta_tags);
	$cat_slug 			= strtolower(str_replace(" ","-",$cat_name)); 
	$change_flag		= 1;
	$response_array = array();	
	if($cat_name != ""  && $cat_parent != "" && $cat_status != "")
	{
		$sql_check_cat 		= " select * from tbl_category where cat_name like '".$cat_name."' and `cat_id` != '".$cat_id."' "; 
		$result_check_cat 	= mysqli_query($db_con,$sql_check_cat) or die(mysqli_error($db_con));
		$num_rows_check_cat = mysqli_num_rows($result_check_cat);
		if($num_rows_check_cat == 0)
		{
			$sql_get_self_data 		= "Select * from tbl_category where `cat_id` = '".$cat_id."' ";
			$result_get_self_data 	= mysqli_query($db_con,$sql_get_self_data) or die(mysqli_error($db_con));
			$rows_get_self_data 	= mysqli_fetch_array($result_get_self_data);
			
			////////////// Strat : Category Table Update ---//////////////////////	
			if($change_flag == 1)
			{
				$cat_type            =$rows_get_self_data['cat_type'];
			
			    $sql_update_category = " UPDATE `tbl_category` ";
				$sql_update_category .= " 	SET `cat_name`='".$cat_name."', ";
				$sql_update_category .= " 		`cat_description`='".$cat_description."', ";
				$sql_update_category .= " 		`cat_slug` = '".$cat_slug."', ";	
				$sql_update_category .= " 		`cat_meta_title`='".$cat_meta_title."', ";
				$sql_update_category .= " 		`cat_type`='".$cat_parent."', ";
				$sql_update_category .= " 		`cat_meta_tags`='".$cat_meta_tags."', ";
				$sql_update_category .= " 		`cat_meta_description`='".$cat_meta_description."', ";
				$sql_update_category .= " 		`cat_status`='".$cat_status."' ,";
				$sql_update_category .= " 		`cat_modified`='".$datetime."', ";
				$sql_update_category .= " 		`cat_modifiedby`='".$uid."' ";
				$sql_update_category .= " WHERE `cat_id` = '".$cat_id."' ";
				$result_update_category = mysqli_query($db_con,$sql_update_category) or die(mysqli_error($db_con));
				if($result_update_category)
				{
					$change_flag = 1;
				}
				else
				{
					$change_flag = 0;					
					$response_array = array("Success"=>"fail","resp"=>"Category Not Updated");								
				}	
				////////////Start : update sub category Status Done by satish 10032017-------
					if($change_flag==1)
					{
						change_cat_status($cat_id,$cat_status);
						
						
						if($rows_get_self_data['cat_type']!=$cat_parent)
						{
							
							
							//// Start : Delete From tbl_category path master //////
							delete_path_master($cat_id);
							
							if($cat_type=='parent')
							{
								mysqli_query($db_con,"DELETE FROM tbl_category WHERE cat_type='".$cat_id."' AND cat_name like 'none' ");
								
							}
							//// End   : Delete From tbl_category path master //////
							
							if($cat_parent !='parent')
							{
								//=====Start : Done By satish Update Sort Order ========//
								
								$sql_get_sort_order = " SELECT cat_sort_order FROM tbl_category
								                          WHERE cat_type='".$cat_parent."'
														  ORDER BY cat_sort_order DESC LIMIT 1";
														  
					            $res_get_sort_order = mysqli_query($db_con,$sql_get_sort_order) or die(mysqli_error($db_con));
								$row_get_sort_order =mysqli_fetch_array($res_get_sort_order); 
								$cat_sort_order     =$row_get_sort_order['cat_sort_order']+1;
								
								$sql_update_sort_order = " UPDATE tbl_category SET cat_sort_order='".$cat_sort_order."' WHERE cat_id ='".$cat_id."'";
							    mysqli_query($db_con,$sql_update_sort_order) or die(mysqli_error($db_con));
							
							    //=====End : Done By satish Update Sort Order ========//
								
								$array = get_parents($cat_parent,$array=array());
							    $array =array_reverse($array);
								array_push($array,$cat_id);
								
								for($i=0;$i<sizeof($array);$i++)
								{    
									$path_level =$i;
									$path_id    =$array[$i];
									$sql_insert_into_category_path	= " INSERT INTO `tbl_category_path_master`(`cat_id`, `path_id`, `level`) ";
									$sql_insert_into_category_path	.= " VALUES ('".$cat_id."', '".$path_id."', '".$path_level."') ";
									$res_insert_into_category_path	= mysqli_query($db_con, $sql_insert_into_category_path) or die(mysqli_error($db_con));
								}
							
							}
							else
							{  
							    $sql_last_rec = "Select * from tbl_category order by cat_id desc LIMIT 0,1";
								$result_last_rec = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
								$num_rows_last_rec = mysqli_num_rows($result_last_rec);
								if($num_rows_last_rec == 0)
								{
									$new_cat_id 		= 1;				
								}
								else
								{
									$row_last_rec = mysqli_fetch_array($result_last_rec);				
									$new_cat_id 		= $row_last_rec['cat_id']+1;
								}
							     
								$sql_insert_category = " INSERT INTO `tbl_category`(`cat_id`, `cat_name`, `cat_description`, `cat_type`, ";
								$sql_insert_category .= " `cat_slug`, `cat_sort_order`, `cat_status`, ";
								$sql_insert_category .= " `cat_created`, `cat_createdby`) ";
								$sql_insert_category .= " VALUES ('".$new_cat_id."','none','".$cat_description."', '".$cat_id."', ";
								$sql_insert_category .= " 'none','1','".$cat_status."', ";
								$sql_insert_category .= " '".$datetime."', '".$uid."')";			
								$result_insert_category = mysqli_query($db_con,$sql_insert_category) or die(mysqli_error($db_con));
								$sql_insert_into_category_path	= " INSERT INTO `tbl_category_path_master`(`cat_id`, `path_id`, `level`) ";
							    $sql_insert_into_category_path	.= " VALUES ('".$cat_id."', '".$cat_id."', '0') ";
								$res_insert_into_category_path	= mysqli_query($db_con, $sql_insert_into_category_path) or die(mysqli_error($db_con));
						   }
							/////Start :   Insert New Path ///////////////////////
							
							insert_childs_path($cat_id);
							/////End :   Insert New Path ////////////////////////
							
						}
					}
			   ////////////End : update sub category Status Done by satish 10032017-------	
			   
			   $response_array =array("Success"=>"Success","resp"=>"Category Successfully Updated");
			}
			else
			{
				$change_flag = 0;				
				$response_array = array("Success"=>"fail","resp"=>"Category Flag Not Assigned");				
			}
			////////////// End : Category Table Update ---//////////////////////
		}		
		else
		{
			$change_flag = 0;			
			$response_array = array("Success"=>"fail","resp"=>"Category ".$cat_name." already Exists.");	
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	
	echo json_encode($response_array);		
}


/////////////// Start : Done by satish 10032017 for change sub categories status  ////////////////////

function insert_childs_path($cat_id)
{
	global $db_con;
	$sql_get_child    = " SELECT * FROM tbl_category WHERE cat_type= '".$cat_id."' ";
    $res_get_child	  = mysqli_query($db_con, $sql_get_child) or die(mysqli_error($db_con));
	while($row_get_child =mysqli_fetch_array($res_get_child))
	{
		$array = get_parents($cat_id,$array=array());
							
		$array =array_reverse($array);
		array_push($array,$row_get_child['cat_id']);
		
		for($j=0;$j<sizeof($array);$j++)
		{    
			$path_level  =$j;
			$path_id     =$array[$j];
			$childcat_id =$row_get_child['cat_id'];
			
			$sql_insert_into_category_path	= " INSERT INTO `tbl_category_path_master`(`cat_id`, `path_id`, `level`) ";
			$sql_insert_into_category_path	.= " VALUES ('".$childcat_id."', '".$path_id."', '".$path_level."') ";
			$res_insert_into_category_path	= mysqli_query($db_con, $sql_insert_into_category_path) or die(mysqli_error($db_con));
		}
		
		$sql_check_child   = " SELECT * FROM tbl_category WHERE cat_type= '".$row_get_child['cat_id']."' ";
		$res_check_child   = mysqli_query($db_con, $sql_check_child) or die(mysqli_error($db_con));
	    $num_check_child   = mysqli_num_rows($res_check_child);
		if($num_check_child >0)
		{
			insert_childs_path($row_get_child['cat_id']);
		}
	}
	
	
}

/////////////// Strat : get_parents //////

function get_parents($cat_id,$array)
{
	global $db_con;
	
	$sql_get_type =" SELECT cat_type,cat_id FROM tbl_category WHERE cat_id ='".$cat_id."' ";
	$res_get_type = mysqli_query($db_con,$sql_get_type)or die(mysqli_error($db_con));
	$row          = mysqli_fetch_array($res_get_type);
	
	array_push($array,$row['cat_id']);
	if($row['cat_type']!='parent')
	{
		$array = get_parents($row['cat_type'],$array);
	}
	return $array;
}

///////////////  End get_parents////////////
function change_cat_status($cat_type ,$cat_status)
{
	global $db_con;
	$sql_get_cat = " SELECT * FROM tbl_category WHERE cat_type ='".$cat_type."'";
	$res_get_cat = mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
	$num_get_cat = mysqli_num_rows($res_get_cat);
	if($num_get_cat > 0)
	{
		while($row_get_cat = mysqli_fetch_array($res_get_cat))
		{
			$sql_update_cat_status =" UPDATE tbl_category SET cat_status ='".$cat_status."' WHERE cat_id ='".$row_get_cat['cat_id']."'";
			$res_update_cat_status = mysqli_query($db_con,$sql_update_cat_status) or die(mysqli_error($db_con));
			change_cat_status($row_get_cat['cat_id'],$cat_status);
		}
	}
}

/////////////// End : Done by satish 10032017 for change sub categories status  ////////////////////

/////////////// Start : Delete From category Path master /////////////////////

function delete_path_master($cat_id)
{
	global $db_con;
	
	$sql_delete_path  = " DELETE FROM tbl_category_path_master WHERE cat_id ='".$cat_id."' or path_id ='".$cat_id."'";
	$res_delete_path  = mysqli_query($db_con,$sql_delete_path) or die(mysqli_error($db_con));

    $sql_get_cats     =  " SELECT * FROM tbl_category WHERE cat_type ='".$cat_id."' ";
	$res_get_cats     =  mysqli_query($db_con,$sql_get_cats) or die(mysqli_error($db_con));
	$num_get_cats     =  mysqli_num_rows($res_get_cats);
	if($num_get_cats >0)
	{
		while($row_get_cats = mysqli_fetch_array($res_get_cats))
		{
			delete_path_master($row_get_cats['cat_id']);
		}
	}
}


function delete_category($cat_id)
{
	global $db_con;
	
	$sql_delete_cat		= " DELETE FROM `tbl_category` WHERE `cat_id` = '".$cat_id."' ";
	$result_delete_cat	= mysqli_query($db_con,$sql_delete_cat) or die(mysqli_error($db_con));
	if($result_delete_cat)
	{
		$sql_delete_product_cat	= " DELETE FROM `tbl_product_cats` WHERE `prodcat_catid` = '".$cat_id."' ";
	    $res_delete_product_cat	= mysqli_query($db_con,$sql_delete_product_cat) or die(mysqli_error($db_con));
		
	    $sql_check_child   =" SELECT cat_id FROM `tbl_category` WHERE `cat_type` = '".$cat_id."' ";
		$res_check_child   = mysqli_query($db_con,$sql_check_child) or die(mysqli_error($db_con));
		$num_check_child   = mysqli_num_rows($res_check_child);
		
		if($num_check_child !=0)
		{
			while($row_check_child= mysqli_fetch_array($res_check_child))
			{
			   delete_category($row_check_child['cat_id']);
			}
		}
		
		delete_path_master($cat_id);
	}
}
/////////////// End : Delete From category Path master ///////////////////////


//================================Strat :  Modified BY satish 16032017 update status==========//

if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$cat_id					= mysqli_real_escape_string($db_con,$obj->cat_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();

	$status_flag = 0;
	$sql_check_cat 		= "Select * from tbl_category where `cat_id` = '".$cat_id."' ";
	$result_check_cat 	= mysqli_query($db_con,$sql_check_cat) or die(mysqli_error($db_con));
	$num_check_cat          = mysqli_num_rows($result_check_cat);
	
	
	if($num_check_cat !=0)
	{
		    change_cat_status($cat_id,$curr_status);
		   
			$sql_update_status 		= " UPDATE `tbl_category` SET `cat_status`= '".$curr_status."' ,`cat_modified` = '".$datetime."' , ";
			$sql_update_status    .= "`cat_modifiedby` = '".$uid."' WHERE `cat_id` like '".$cat_id."' ";
			$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
			
			if($result_update_status)
			{
				$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");			
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
			}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Category Not Found.");
	}
	echo json_encode($response_array);	
}

//================================End :  Modified BY satish 16032017 update status==========//



if((isset($obj->delete_category)) == "1" && isset($obj->delete_category))
{
	$response_array = array();		
	$ar_cat_id 		= $obj->batch;
	$del_flag 		= 0; 
	if(!empty($ar_cat_id))
	{
		foreach($ar_cat_id as $cat_id)	
		{
				delete_category($cat_id);
		}
		
		$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");
	}		
	echo json_encode($response_array);	
}

//==========================================================================================================
//====================End : Done by satish 15032017 for update_category=====================================
//==========================================================================================================





if((isset($obj->load_cat)) == "1" && isset($obj->load_cat))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= mysqli_real_escape_string($db_con,$obj->page);	
	$per_page		= mysqli_real_escape_string($db_con,$obj->row_limit);
	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);	
	$cat_parent		= mysqli_real_escape_string($db_con,$obj->cat_parent);		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT cat_id,cat_name,cat_description,cat_slug,cat_sort_order,cat_created,cat_modified,cat_status, cat_type,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.cat_createdby) as cat_by_created,cat_slug,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.cat_modifiedby) as cat_by_modified, ";
		$sql_load_data  .= " (SELECT cat_name FROM `tbl_category` WHERE cat_id = tc.cat_type) as parent_name ";				
		$sql_load_data  .= " FROM tbl_category tc WHERE 1=1 and cat_type like '".$cat_parent."' ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND cat_createdby='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (cat_name like '%".$search_text."%' or cat_description like '%".$search_text."%' ";
			$sql_load_data .= " or cat_created like '%".$search_text."%' or cat_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY cat_sort_order LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$cat_data = "";			
			if($cat_parent == "parent")
			{
				$cat_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Parent List</label><br>';
			}
			elseif($cat_parent != "parent")
			{
				$cat_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Child List</label><br>';
			}
			$cat_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$cat_data .= '<thead>';
    	  	$cat_data .= '<tr>';
         	$cat_data .= '<th class="center-text">Sr No.</th>';
			$cat_data .= '<th class="center-text">Id</th>';
			$cat_data .= '<th>Category Name</th>';
			$cat_data .= '<th style="width:6%;text-align:center">Sort Order</th>';
			$cat_data .= '<th style="width:15%;text-align:center">Slug/Url</th>';			
			$cat_data .= '<th class="center-text">Product Discount</th>';
			$cat_data .= '<th class="center-text">View</th>';
			$dis = checkFunctionalityRight("view_category.php",3);
			if($dis)
			{
				$cat_data .= '<th class="center-text">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_category.php",1);
			if($edit)
			{
				$cat_data .= '<th class="center-text">Edit</th>';
			}
			$del = checkFunctionalityRight("view_category.php",2);
			if($del)
			{
				$cat_data .= '<th class="center-text">';
				$cat_data .= '<div class="center-text">';
				$cat_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$cat_data .= '</tr>';
      		$cat_data .= '</thead>';
      		$cat_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$cat_data .= '<tr>';				
				$cat_data .= '<td class="center-text">'.++$start_offset.'</td>';				
				$cat_data .= '<td class="center-text">'.$row_load_data['cat_id'].'</td>';
				$cat_data .= '<td>';
				$cat_data .= '<div class="center-text">';				
				$cat_data .= '<input type="button" value="'.ucwords($row_load_data['cat_name']).'" class="btn-link" id="'.$row_load_data['cat_id'].'" onclick="addMoreCategory(this.id,\'view\');">';
				$cat_data .= '<i class="icon-chevron-down" id="'.$row_load_data['cat_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['cat_id'].'cat_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$cat_data .= '</div>';				
				$cat_data .= '<div style="display:none" id="'.$row_load_data['cat_id'].'cat_div">';
				if($row_load_data['cat_by_created'] == "")
				{
					$cat_data .= '<b>Created By:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$cat_data .= '<span><b>Created By:</b>'.$row_load_data['cat_by_created'].'</span><br>';
				}
				if($row_load_data['cat_created'] == "" || $row_load_data['cat_created'] == "0000-00-00 00:00:00")
				{
					$cat_data .= '<b>Created :</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$cat_data .= '<span><b>Created :</b>'.$row_load_data['cat_created'].'</span><br>';
				}				
				if($row_load_data['cat_by_modified'] == "")
				{
					$cat_data .= '<b>Modified By:</b><span style="color:#f00;">Not Available</span><br>';					
				}
				else
				{
					$cat_data .= '<span><b>Modified By:</b>'.$row_load_data['cat_by_modified'].'</span><br>';
				}
				if($row_load_data['cat_modified'] == "" || $row_load_data['cat_modified'] == "0000-00-00 00:00:00")
				{
					$cat_data .= '<b>Modified:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$cat_data .= '<span><b>Modified:</b>'.$row_load_data['cat_modified'].'</span><br>';								
				}
				$cat_data .= '</div>';
				$cat_data .= '</td>';								
				$cat_data .= '<td class="center-text">';
				$cat_data .= '<input type="text" style="text-align:center;width:50%" onblur="changeOrder(this.id,this.value)" id="'.$row_load_data['cat_id'].'" value="'.$row_load_data['cat_sort_order'].'">';
				$cat_data .= '</td>';
				$cat_data .= '<td class="center-text">';
				$cat_data .= '<textarea style="text-align:center;width:50%" onblur="changeCatSlug(this.id)" id="'.$row_load_data['cat_id'].'cat_slug" >'.$row_load_data['cat_slug'].'</textarea>';
				$cat_data .= '</td>';				
				$cat_data .= '<td class="center-text">';				
				$cat_data .= '<div>';
				$cat_data .= '<span><input type="radio" name="'.$row_load_data['cat_id'].'discount" value="flat">Flat </span>';
				$cat_data .= '<span><input type="radio" name="'.$row_load_data['cat_id'].'discount" value="percent">Percent(%) </span>';
				$cat_data .= '</div><br>';					
				$cat_data .= '<div class="center-text">';
				$cat_data .= '<input type="text" name="'.$row_load_data['cat_id'].'discount_value" id="'.$row_load_data['cat_id'].'discount_value">';					
				$cat_data .= '</div>';															
				$cat_data .= '<div class="center-text">';
				$cat_data .= '<input type="button" onClick="productDiscount(this.id,3);" class="btn-success" id="'.$row_load_data['cat_id'].'dis_btn" value="Apply to '.ucwords($row_load_data['cat_name']).'">';
				$cat_data .= '</div>';				
				$cat_data .= '</td>';
				$cat_data .= '<td class="center-text">';
				
				if($cat_parent == "parent")
				{
					$sql_check_parent 	= "SELECT * FROM `tbl_category` WHERE `cat_type` like '".$row_load_data['cat_id']."'";
					$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
					$num_rows_check_parent 	= mysqli_num_rows($result_check_parent);
					if($num_rows_check_parent != 0)
					{
						$cat_data .= '<input type="button" class="btn-success"  value="View Child" id="'.$row_load_data['cat_id'].'" onclick="viewChild(this.id)"></td>';						
					}
					
				}
				else
				{   
				
				    $sql_check_parent 	= "SELECT * FROM `tbl_category` WHERE `cat_id` like '".$row_load_data['cat_type']."'";
					$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
					$row_check_parent 	= mysqli_fetch_array($result_check_parent);
				    if($row_check_parent['cat_name']!="none")
					{
						$parent_id =$row_check_parent['cat_type'];
					}
					else
					{
						$parent_id ="parent";
					}
				     
					//$cat_data .= '<input type="button" class="btn-success"  value="View Parent" id="parent" onclick="viewChild(this.id)"></td>';
					$sql_check_parent 	= "SELECT * FROM `tbl_category` WHERE `cat_type` like '".$row_load_data['cat_id']."'";
					$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
					$num_rows_check_parent 	= mysqli_num_rows($result_check_parent);
					if($num_rows_check_parent != 0)
					{
						$cat_data .= '<input type="button" class="btn-success"  value="View Child" id="'.$row_load_data['cat_id'].'" onclick="viewChild(this.id)"><br><br>';
						$cat_data .=' <input type="button" class="btn-success"  value="View Parent" id="parent" onclick="viewChild(\''.$parent_id.'\')"></td>';						
					}
					else
					{
						$cat_data .= '<input type="button" class="btn-success"  value="View Parent" id="parent" onclick="viewChild(\''.$parent_id.'\')"></td>';
					}
				}
				
				$cat_data .= '</td>';
				$dis = checkFunctionalityRight("view_category.php",3);
				if($dis)			
				{
					$cat_data .= '<td class="center-text">';	
					if($row_load_data['cat_status'] == 1)
					{
						$cat_data .= '<input type="button" value="Active" id="'.$row_load_data['cat_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$cat_data .= '<input type="button" value="Inactive" id="'.$row_load_data['cat_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$cat_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_category.php",1);				
				if($edit)
				{
					$cat_data .= '<td class="center-text">';
					if($row_load_data['cat_name'] !="none")
					{
					$cat_data .= '<input type="button" value="Edit" id="'.$row_load_data['cat_id'].'" class="btn-warning" onclick="addMoreCategory(this.id,\'edit\');">';
					}
					else 
					{
							$cat_data .='-';
					}
					$cat_data .='</td>';
				}
				$del = checkFunctionalityRight("view_category.php",2);
				if($del)				
				{    
				  
					$cat_data .= '<td>';
					if($row_load_data['cat_name'] !="none")
					{
					$cat_data .=' <div class="controls" align="center">';					
					$cat_data .= '		<input type="checkbox" value="'.$row_load_data['cat_id'].'" id="batch'.$row_load_data['cat_id'].'" name="batch'.$row_load_data['cat_id'].'" class="css-checkbox batch">';
					$cat_data .= '		<label for="batch'.$row_load_data['cat_id'].'" class="css-label"></label>';
					$cat_data .= '	</div>';
					}
					else 
					{
							$cat_data .='-';
					}
					$cat_data .= '	</td>';										
				}
	          	$cat_data .= '</tr>';															
			}	
      		$cat_data .= '</tbody>';
      		$cat_data .= '</table>';	
			$cat_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$cat_data);					
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

if((isset($obj->load_cat_16032017)) == "1" && isset($obj->load_cat_16032017))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= mysqli_real_escape_string($db_con,$obj->page);	
	$per_page		= mysqli_real_escape_string($db_con,$obj->row_limit);
	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);	
	$cat_parent		= mysqli_real_escape_string($db_con,$obj->cat_parent);		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT cat_id,cat_name,cat_description,cat_slug,cat_sort_order,cat_created,cat_modified,cat_status, cat_type,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.cat_createdby) as cat_by_created,cat_slug,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.cat_modifiedby) as cat_by_modified, ";
		$sql_load_data  .= " (SELECT cat_name FROM `tbl_category` WHERE cat_id = tc.cat_type) as parent_name ";				
		$sql_load_data  .= " FROM tbl_category tc WHERE 1=1 and cat_type like '".$cat_parent."' ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND cat_createdby='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (cat_name like '%".$search_text."%' or cat_description like '%".$search_text."%' ";
			$sql_load_data .= " or cat_created like '%".$search_text."%' or cat_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY cat_sort_order LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$cat_data = "";			
			if($cat_parent == "parent")
			{
				$cat_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Parent List</label><br>';
			}
			elseif($cat_parent != "parent")
			{
				$cat_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Child List</label><br>';
			}
			$cat_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$cat_data .= '<thead>';
    	  	$cat_data .= '<tr>';
         	$cat_data .= '<th class="center-text">Sr No.</th>';
			$cat_data .= '<th class="center-text">Id</th>';
			$cat_data .= '<th>Category Name</th>';
			$cat_data .= '<th style="width:6%;text-align:center">Sort Order</th>';
			$cat_data .= '<th style="width:15%;text-align:center">Slug/Url</th>';			
			$cat_data .= '<th class="center-text">Product Discount</th>';
			$cat_data .= '<th class="center-text">View</th>';
			$dis = checkFunctionalityRight("view_category.php",3);
			if($dis)
			{
				$cat_data .= '<th class="center-text">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_category.php",1);
			if($edit)
			{
				$cat_data .= '<th class="center-text">Edit</th>';
			}
			$del = checkFunctionalityRight("view_category.php",2);
			if($del)
			{
				$cat_data .= '<th class="center-text">';
				$cat_data .= '<div class="center-text">';
				$cat_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$cat_data .= '</tr>';
      		$cat_data .= '</thead>';
      		$cat_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$cat_data .= '<tr>';				
				$cat_data .= '<td class="center-text">'.++$start_offset.'</td>';				
				$cat_data .= '<td class="center-text">'.$row_load_data['cat_id'].'</td>';
				$cat_data .= '<td>';
				$cat_data .= '<div class="center-text">';				
				$cat_data .= '<input type="button" value="'.ucwords($row_load_data['cat_name']).'" class="btn-link" id="'.$row_load_data['cat_id'].'" onclick="addMoreCategory(this.id,\'view\');">';
				$cat_data .= '<i class="icon-chevron-down" id="'.$row_load_data['cat_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['cat_id'].'cat_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$cat_data .= '</div>';				
				$cat_data .= '<div style="display:none" id="'.$row_load_data['cat_id'].'cat_div">';
				if($row_load_data['cat_by_created'] == "")
				{
					$cat_data .= '<b>Created By:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$cat_data .= '<span><b>Created By:</b>'.$row_load_data['cat_by_created'].'</span><br>';
				}
				if($row_load_data['cat_created'] == "" || $row_load_data['cat_created'] == "0000-00-00 00:00:00")
				{
					$cat_data .= '<b>Created :</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$cat_data .= '<span><b>Created :</b>'.$row_load_data['cat_created'].'</span><br>';
				}				
				if($row_load_data['cat_by_modified'] == "")
				{
					$cat_data .= '<b>Modified By:</b><span style="color:#f00;">Not Available</span><br>';					
				}
				else
				{
					$cat_data .= '<span><b>Modified By:</b>'.$row_load_data['cat_by_modified'].'</span><br>';
				}
				if($row_load_data['cat_modified'] == "" || $row_load_data['cat_modified'] == "0000-00-00 00:00:00")
				{
					$cat_data .= '<b>Modified:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$cat_data .= '<span><b>Modified:</b>'.$row_load_data['cat_modified'].'</span><br>';								
				}
				$cat_data .= '</div>';
				$cat_data .= '</td>';								
				$cat_data .= '<td class="center-text">';
				$cat_data .= '<input type="text" style="text-align:center;width:50%" onblur="changeOrder(this.id,this.value)" id="'.$row_load_data['cat_id'].'" value="'.$row_load_data['cat_sort_order'].'">';
				$cat_data .= '</td>';
				$cat_data .= '<td class="center-text">';
				$cat_data .= '<textarea style="text-align:center;width:50%" onblur="changeCatSlug(this.id)" id="'.$row_load_data['cat_id'].'cat_slug" >'.$row_load_data['cat_slug'].'</textarea>';
				$cat_data .= '</td>';				
				$cat_data .= '<td class="center-text">';				
				$cat_data .= '<div>';
				$cat_data .= '<span><input type="radio" name="'.$row_load_data['cat_id'].'discount" value="flat">Flat </span>';
				$cat_data .= '<span><input type="radio" name="'.$row_load_data['cat_id'].'discount" value="percent">Percent(%) </span>';
				$cat_data .= '</div><br>';					
				$cat_data .= '<div class="center-text">';
				$cat_data .= '<input type="text" name="'.$row_load_data['cat_id'].'discount_value" id="'.$row_load_data['cat_id'].'discount_value">';					
				$cat_data .= '</div>';															
				$cat_data .= '<div class="center-text">';
				$cat_data .= '<input type="button" onClick="productDiscount(this.id,3);" class="btn-success" id="'.$row_load_data['cat_id'].'dis_btn" value="Apply to '.ucwords($row_load_data['cat_name']).'">';
				$cat_data .= '</div>';				
				$cat_data .= '</td>';
				$cat_data .= '<td class="center-text">';
				
				if($cat_parent == "parent")
				{
					$sql_check_parent 	= "SELECT * FROM `tbl_category` WHERE `cat_type` like '".$row_load_data['cat_id']."'";
					$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
					$num_rows_check_parent 	= mysqli_num_rows($result_check_parent);
					if($num_rows_check_parent != 0)
					{
						$cat_data .= '<input type="button" class="btn-success"  value="View Child" id="'.$row_load_data['cat_id'].'" onclick="viewChild(this.id)"></td>';						
					}
				}
				else
				{
					//$cat_data .= '<input type="button" class="btn-success"  value="View Parent" id="parent" onclick="viewChild(this.id)"></td>';
					$sql_check_parent 	= "SELECT * FROM `tbl_category` WHERE `cat_type` like '".$row_load_data['cat_id']."'";
					$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
					$num_rows_check_parent 	= mysqli_num_rows($result_check_parent);
					if($num_rows_check_parent != 0)
					{
						$cat_data .= '<input type="button" class="btn-success"  value="View Child" id="'.$row_load_data['cat_id'].'" onclick="viewChild(this.id)"></td>';						
					}
					else
					{
						$cat_data .= '<input type="button" class="btn-success"  value="View Parent" id="parent" onclick="viewChild(this.id)"></td>';
					}
				}
				$cat_data .= '</td>';
				$dis = checkFunctionalityRight("view_category.php",3);
				if($dis)			
				{
					$cat_data .= '<td class="center-text">';	
					if($row_load_data['cat_status'] == 1)
					{
						$cat_data .= '<input type="button" value="Active" id="'.$row_load_data['cat_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$cat_data .= '<input type="button" value="Inactive" id="'.$row_load_data['cat_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$cat_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_category.php",1);				
				if($edit)
				{
					$cat_data .= '<td class="center-text">';
					$cat_data .= '<input type="button" value="Edit" id="'.$row_load_data['cat_id'].'" class="btn-warning" onclick="addMoreCategory(this.id,\'edit\');"></td>';
				}
				$del = checkFunctionalityRight("view_category.php",2);
				if($del)				
				{
					$cat_data .= '<td><div class="controls" align="center">';					
					$cat_data .= '		<input type="checkbox" value="'.$row_load_data['cat_id'].'" id="batch'.$row_load_data['cat_id'].'" name="batch'.$row_load_data['cat_id'].'" class="css-checkbox batch">';
					$cat_data .= '		<label for="batch'.$row_load_data['cat_id'].'" class="css-label"></label>';
					$cat_data .= '	</div></td>';										
				}
	          	$cat_data .= '</tr>';															
			}	
      		$cat_data .= '</tbody>';
      		$cat_data .= '</table>';	
			$cat_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$cat_data);					
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







if((isset($obj->change_cat_slug)) == "1" && isset($obj->change_cat_slug))
{
	$cat_id				= mysqli_real_escape_string($db_con,$obj->cat_id);
	$cat_slug			= mysqli_real_escape_string($db_con,$obj->cat_slug);
	if($cat_id != "" && $cat_slug != "")
	{
		$sql_update_slug 	= " UPDATE `tbl_category` SET `cat_slug`= '".$cat_slug."',`cat_modified`= '".$datetime."',";
		$sql_update_slug 	.= " `cat_modifiedby`= '".$uid."' WHERE `cat_id` = '".$cat_id."' ";
		$result_update_slug = mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
		if($result_update_slug)
		{
			$response_array = array("Success"=>"Success","resp"=>"Slug Updated Successfully.");						
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Slug Update Failed.");			
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Slug Update Failed.");
	}	
	echo json_encode($response_array);	
}



if((isset($obj->change_sort_order)) == "1" && isset($obj->change_sort_order))
{
	$cat_id					= mysqli_real_escape_string($db_con,$obj->cat_id);
	$new_order				= mysqli_real_escape_string($db_con,$obj->new_order);
	$response_array 		= array();		
	
	$sql_check_self_order	= " SELECT * from tbl_category WHERE cat_id LIKE '".$cat_id."' ";
	$result_check_self_order= mysqli_query($db_con,$sql_check_self_order) or die(mysqli_error($db_con));	
	$row_check_self_order	= mysqli_fetch_array($result_check_self_order);
	
	$self_parent			= $row_check_self_order['cat_type'];
	$self_order				= $row_check_self_order['cat_sort_order'];
	
	$sql_check_order 		= " SELECT * from tbl_category WHERE cat_sort_order LIKE '".$new_order."' and cat_type like '".$self_parent."' ";
	$result_check_order		= mysqli_query($db_con,$sql_check_order) or die(mysqli_error($db_con));	
	$num_rows_check_order	= mysqli_num_rows($result_check_order);
	if($num_rows_check_order > 0)
	{
		$row_check_order 	= mysqli_fetch_array($result_check_order);
		$other_cat			= $row_check_order['cat_id'];		
		$other_parent		= $row_check_order['cat_type'];
		$other_order		= $row_check_order['cat_sort_order'];
		if(($self_parent == "parent" && $other_parent == "parent" )|| ($self_parent == $other_parent ))
		{
			if($other_order == $new_order)
			{
				$sql_update_sort1 		= " UPDATE `tbl_category` SET `cat_sort_order`= '".$self_order."' ,`cat_modified` = '".$datetime."' ,`cat_modifiedby` = '".$uid."' WHERE `cat_id` like '".$other_cat."' ";	
				$result_update_sort2 	= mysqli_query($db_con,$sql_update_sort1) or die(mysqli_error($db_con));			
				$sql_update_sort2 		= " UPDATE `tbl_category` SET `cat_sort_order`= '".$new_order."' ,`cat_modified` = '".$datetime."' ,`cat_modifiedby` = '".$uid."' WHERE `cat_id` like '".$cat_id."' ";
				$result_update_sort2 	= mysqli_query($db_con,$sql_update_sort2) or die(mysqli_error($db_con));	
				$response_array 		= array("Success"=>"Success","resp"=>"Sort Order exchanged Successfully.");
			}
			else
			{					
				$sql_update_sort 	= " UPDATE `tbl_category` SET `cat_sort_order`= '".$new_order."' ,`cat_modified` = '".$datetime."' ,`cat_modifiedby` = '".$uid."' WHERE `cat_id` like '".$cat_id."' ";
				$result_update_sort = mysqli_query($db_con,$sql_update_sort) or die(mysqli_error($db_con));	
				$response_array 	= array("Success"=>"Success","resp"=>"Sort Order Updated Successfully.");
			}
		}
		else
		{
				$response_array = array("Success"=>"fail","resp"=>"Sort Order Update Failed.");
		}		
	}
	else
	{
		$sql_update_sort =	" UPDATE `tbl_category` SET `cat_sort_order`= '".$new_order."' ,`cat_modified` = '".$datetime."' ,`cat_modifiedby` = '".$uid."' WHERE `cat_id` like '".$cat_id."' ";
		$result_update_sort = mysqli_query($db_con,$sql_update_sort) or die(mysqli_error($db_con));	
		$response_array = array("Success"=>"Success","resp"=>"Sort Order Updated Successfully.");			
	}
	echo json_encode($response_array);	
}


// Showing Records From Error Logs Table For category========================
if((isset($obj->load_error_cat)) == "1" && isset($obj->load_error_cat))
{
	$start_offset   = 0;
	
	$page 			= $obj->page1;	
	$per_page		= $obj->row_limit1;
	$search_text	= $obj->search_text1;	
	$cat_parent		= $obj->cat_parent1;
	$response_array = array();	
		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`, `error_modified`, `error_modified_by`, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_created_by) as created_by_name, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_modified_by) as modified_by_name ";
		$sql_load_data  .= " FROM `tbl_error_data`  ";
		$sql_load_data  .= " WHERE error_module_name='category'  ";
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
			$cat_data = "";	
			$cat_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$cat_data .= '<thead>';
    	  	$cat_data .= '<tr>';
         	$cat_data .= '<th>Sr. No.</th>';
			$cat_data .= '<th>Catogery Name</th>';
			$cat_data .= '<th>Parent</th>';
			$cat_data .= '<th>Created</th>';
			$cat_data .= '<th>Created By</th>';
			$cat_data .= '<th>Modified</th>';
			$cat_data .= '<th>Modified By</th>';
			$cat_data .= '<th>Edit</th>';			
			$cat_data .= '<th><div class="center-text">';
			$cat_data .= '<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>';
			$cat_data .= '</div></th>';
          	$cat_data .= '</tr>';
      		$cat_data .= '</thead>';
      		$cat_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_cat_rec	= json_decode($row_load_data['error_data']);
				$er_cat_name	= $get_cat_rec->cat_name;
				$er_cat_type	= $get_cat_rec->cat_type;
				$cat_data 		.= '<tr>';				
				$cat_data 		.= '<td>'.++$start_offset.'</td>';				
				$cat_data 		.= '<td>';
				$sql_chk_name_already_exist	= "SELECT cat_name FROM tbl_category WHERE cat_name='".$er_cat_name."'";
				$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
				$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);

				if(strcmp($num_chk_name_already_exist,"0")===0)
				{
					$cat_data .= $er_cat_name;
				}
				else
				{
					$cat_data .= '<span style="color:#E63A3A;">'.$er_cat_name.' [Already Exist]</span>';
				}
				$cat_data .= '</td>';
				//$cat_data .= '<td>'.$row_load_data['cat_description'].'</td>';
				$cat_data .= '<td>';
					$sql_chk_parent_already_exist	= "SELECT cat_name FROM tbl_category WHERE cat_name='".$er_cat_type."'";
					$res_chk_parent_already_exist	= mysqli_query($db_con, $sql_chk_parent_already_exist) or die(mysqli_error($db_con));
					$row_chk_parent_already_exist 	= mysqli_fetch_array($res_chk_parent_already_exist);
					$num_chk_parent_already_exist	= mysqli_num_rows($res_chk_parent_already_exist);
					
					if(strcmp($num_chk_parent_already_exist,"0")===0 && strcmp($er_cat_type,"parent")!==0)
					{
						$cat_data .= '<span style="color:#E63A3A;">'.$er_cat_type.' [Parent Not Exist]</span>';
					}
					else
					{
						$cat_data .= $er_cat_type;	
					}
				$cat_data .= '</td>';
				$cat_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$cat_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
				$cat_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$cat_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$cat_data .= '<td class="center-text">';				
				$cat_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreCategory(this.id,\'error\');"></td>';
				$cat_data .= '<td><div class="controls" align="center">';
				$cat_data .= '<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$cat_data .= '<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$cat_data .= '</div></td>';										
	          	$cat_data .= '</tr>';															
			}	
      		$cat_data .= '</tbody>';
      		$cat_data .= '</table>';	
			$cat_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$cat_data);					
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
if((isset($obj->delete_catogery_error)) == "1" && isset($obj->delete_catogery_error))
{
	$ar_cat_id 		= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_cat_id as $cat_id)	
	{
		$sql_delete_cat_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$cat_id."' ";
		
		$result_delete_cat_error= mysqli_query($db_con,$sql_delete_cat_error) or die(mysqli_error($db_con));			
		if($result_delete_cat_error)
		{
			$$del_flag_error = 1;	
		}			
	}
	if($$del_flag_error == 1)
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