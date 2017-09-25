<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];
/*function addFilterLevel($filter_data,$level_data)
{
	global $db_con;
	global $uid;
	if($filter_data == "" && $level_data == "")
	{
		return false;
	}
	else
	{
		$filterid 					= explode(":",$filter_data);
		$filtlevel_filt_parent_id	= $filterid[0];
		$filtlevel_filt_child_id	= $filterid[1];
		
		$levelid 					= explode(":",$level_data);	
		$filtlevel_level_parent_id	= $levelid[0];
		$filtlevel_level_child_id	= $levelid[1];
		$filtlevel_status			= 1;
		
		if($filtlevel_filt_parent_id == "" && $filtlevel_filt_child_id == "" && $filtlevel_level_parent_id == "" && $filtlevel_level_child_id == "")
		{
			return false;
		}
		else
		{
			$sql_check_exists 		= " SELECT * FROM `tbl_filt_level` WHERE  ";
			$sql_check_exists 		.= " `filtlevel_filt_parent_id` = '".$filtlevel_filt_parent_id."' and `filtlevel_filt_child_id` = '".$filtlevel_filt_child_id."' ";
			$sql_check_exists 		.= " `filtlevel_level_parent_id` = '".$filtlevel_level_parent_id."' and `filtlevel_level_child_id` = '".$filtlevel_level_child_id."' ";
			$result_check_exists 	= mysqli_query($db_con,$sql_check_exists) or die(mysqli_error($db_con));
			$num_rows_check_exists 	= mysqli_num_rows($result_check_exists);
			if($num_rows_check_exists == 0)
			{
				$new_id				= "filtlevel_id";
				$table_name			= "tbl_filt_level";
				$filtlevel_id 		= getNewId($new_id,$table_name);
				$sql_add_filt_level = " INSERT INTO `tbl_filt_level`(`filtlevel_id`, `filtlevel_filt_parent_id`, `filtlevel_filt_child_id`, `filtlevel_level_parent_id`, ";
				$sql_add_filt_level .= " `filtlevel_level_child_id`, `filtlevel_status`, `filtlevel_created`, `filtlevel_created_by`) VALUES ('".$filtlevel_id."', ";
				$sql_add_filt_level .= " ,'".$filtlevel_filt_parent_id."','".$filtlevel_filt_child_id."','".$filtlevel_level_parent_id."','".$filtlevel_level_child_id."',";
				$sql_add_filt_level .= " ,'".$filtlevel_status."',now(),'".$uid."')";
				$result_add_filt_level = mysqli_query($db_con,$sql_add_filt_level) or die(mysqli_error($db_con));
				if($result_add_filt_level)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
	}
}
function updateFilterLevel($filter_data,$level_data,$filtlevel_status)
{
	global $db_con;
	global $uid;
	if($filter_data == "" && $level_data == "")
	{
		return false;
	}
	else
	{
		$filterid 					= explode(":",$filter_data);
		$filtlevel_filt_parent_id	= $filterid[0];
		$filtlevel_filt_child_id	= $filterid[1];
		
		$levelid 					= explode(":",$level_data);	
		$filtlevel_level_parent_id	= $levelid[0];
		$filtlevel_level_child_id	= $levelid[1];
		if($filtlevel_filt_parent_id == "" && $filtlevel_filt_child_id == "" && $filtlevel_level_parent_id == "" && $filtlevel_level_child_id == "")
		{
			return false;
		}
		else
		{
			$sql_update_filt_level 		= " UPDATE `tbl_filt_level` SET `filtlevel_status`= '".$filtlevel_status."',`filtlevel_modified`=now(),`filtlevel_modified_by`='".$uid."' WHERE ";
			$sql_update_filt_level 		.= " `filtlevel_filt_parent_id`='".$filtlevel_filt_parent_id."' and `filtlevel_filt_child_id`= '".$filtlevel_filt_child_id."' and ";
			$sql_update_filt_level 		.= " `filtlevel_level_parent_id`='".$filtlevel_level_parent_id."' and `filtlevel_level_child_id`= '".$filtlevel_level_child_id."' ";
			$result_update_filt_level 	= mysqli_query($db_con,$sql_update_filt_level) or die(mysqli_error($db_con));
			if($result_update_filt_level)
			{
				return true;
			}
			else
			{
				return false;
			}
		}		
	}
}*/
function insertFilter($uid,$db_con,$cat_name,$cat_description,$cat_parent,$filt_sub_child,$cat_status,$filt_meta_description,$filt_meta_tags,$filt_meta_title,$response_array)
{
	
	global $obj, $datetime;
	$cat_slug 			= strtolower(str_replace(" ","-",$cat_name)); 
	if($filt_sub_child == "parent")
	{
		$sql_check_sort_ava = " SELECT filt_sort_order FROM tbl_filters WHERE filt_type = 'parent' and filt_sub_child = 'parent' ORDER by filt_id DESC LIMIT 0,1 ";			
		$result_check_sort_ava = mysqli_query($db_con,$sql_check_sort_ava) or die(mysqli_error($db_con));
		$num_rows_check_sort_ava = mysqli_fetch_array($result_check_sort_ava);
		if($num_rows_check_sort_ava != 0)
		{
			$cat_sort_order		= $num_rows_check_sort_ava['filt_sort_order']+1;	
		}
		else
		{
			$cat_sort_order		= 1;
		}			
	}
	elseif($filt_sub_child == "child")
	{
		$sql_sort_order			= "SELECT filt_sort_order FROM tbl_filters WHERE filt_type = '".$cat_parent."' and filt_sub_child = 'child' ORDER by filt_sort_order DESC";
		$result_sort_order 		= mysqli_query($db_con,$sql_sort_order) or die(mysqli_error($db_con)); 
		$row_sort_order			= mysqli_fetch_array($result_sort_order);
		$num_rows_sort_order 	= mysqli_num_rows($result_sort_order);
		if($num_rows_sort_order == 0)
		{
			$cat_sort_order		= 1;
		}
		else
		{
			$cat_sort_order		= $row_sort_order['filt_sort_order']+1;
		}
	}
	else
	{
		$sql_sort_order			= "SELECT filt_sort_order FROM tbl_filters WHERE filt_type = '".$cat_parent."' and filt_sub_child = '".$filt_sub_child."' ORDER by filt_sort_order DESC";
		$result_sort_order 		= mysqli_query($db_con,$sql_sort_order) or die(mysqli_error($db_con)); 
		$row_sort_order			= mysqli_fetch_array($result_sort_order);
		$num_rows_sort_order 	= mysqli_num_rows($result_sort_order);
		if($num_rows_sort_order == 0)
		{
			$cat_sort_order		= 1;
		}
		else
		{
			$cat_sort_order		= $row_sort_order['filt_sort_order']+1;
		}		
	}		
	$sql_check_cat 		= " select * from tbl_filters where filt_name = '".$cat_name."' and filt_type = '".$cat_parent."' and filt_sub_child = '".$filt_sub_child."' "; 
	$result_check_cat 	= mysqli_query($db_con,$sql_check_cat) or die(mysqli_error($db_con));
	$num_rows_check_cat = mysqli_num_rows($result_check_cat);
	if($num_rows_check_cat == 0)
	{

		$sql_last_rec = "Select * from tbl_filters order by filt_id desc LIMIT 0,1";
		$result_last_rec = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec = mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$cat_id 		= 1;				
		}
		else
		{
			$row_last_rec = mysqli_fetch_array($result_last_rec);				
			$cat_id 		= $row_last_rec['filt_id']+1;
		}
		$sql_insert_category = " INSERT INTO `tbl_filters`(`filt_id`,`filt_name`, `filt_description`, `filt_type`,`filt_sub_child`, `filt_sort_order`, `filt_status`,`filt_meta_title`,`filt_meta_description`,`filt_meta_keywords`, ";
		$sql_insert_category .= " `filt_created`, `filt_createdby`) VALUES ('".$cat_id."','".$cat_name."','".$cat_description."',";
		$sql_insert_category .= " '".$cat_parent."','".$filt_sub_child."','".$cat_sort_order."','".$cat_status."','".$filt_meta_title."','".$filt_meta_description."','".$filt_meta_tags."','".$datetime."','".$uid."') ";
		
/*		$response_array = array("Success"=>"fail","resp"=>$sql_insert_category);	
	echo json_encode($response_array);
	exit();
		*/
		$result_insert_category = mysqli_query($db_con,$sql_insert_category) or die(mysqli_error($db_con));
		if($sql_insert_category)
		{
			$response_array = array("Success"=>"Success","resp"=>"Filter Added Successfully.");					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
		}			
	}
	else
	{
		$msg = 'Filter <b> "'.ucwords($cat_name).'" </b> Already Exist';
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
			$cat_status			= 1;
			
			
			$query = "SELECT `id`,`filt_id`, `filt_name`, `filt_description`, `filt_type`, `filt_sort_order`,`filt_status`,";
			$query .= "`filt_created`,`filt_createdby`, `filt_modified`, `filt_modifiedby`	FROM `tbl_filters` ";
			$query .= "	WHERE `filt_name`='".$cat_name."' AND `filt_description`='".$cat_description."'	AND `filt_type`='".$cat_parent_type."'";
										
			$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
			$recResult 	= mysqli_fetch_array($sql);
			
			$existName 		= $recResult["filt_name"];
			$existCatType	= $recResult['filt_type'];
			if($existName=="" )
			{
				if(strcmp($cat_parent_type,"parent")===0)
				{
					$cat_parent_type 	= "parent";
					$response_array 	= insertFilter($uid,$db_con,$cat_name,$cat_description,$cat_parent_type,$cat_status,$filtat_meta_description,$filt_meta_tags,$filt_meta_title,$response_array);
				}
				elseif(strcmp($cat_parent_type,"parent") !== 0)
				{
					$sql_get_cat_type	= "SELECT filt_id, filt_type FROM tbl_filters WHERE filt_name='".$cat_parent_type."'";
					$res_get_cat_type 	= mysqli_query($db_con, $sql_get_cat_type) or die(mysqli_error($db_con));
					$row_get_cat_type 	= mysqli_fetch_array($res_get_cat_type);
					$num_get_cat_type 	= mysqli_num_rows($res_get_cat_type);
					
					if(strcmp($num_get_cat_type,"0")!==0)
					{
						$cat_parent_type	= $row_get_cat_type['filt_id'];
						$response_array 	= insertFilter($uid,$db_con, $cat_name,$cat_description,$cat_parent_type,$cat_status,$filt_meta_description,$filt_meta_tags,$filt_meta_title,$response_array);
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
						$error_data = array("filt_name"=>$cat_name, "filt_description"=>$cat_description, "filt_type"=>$cat_parent_type);	
						
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
						
						$error_module_name	= "fliters";
						$error_file			= $inputFileName;
						$error_status		= '1';
						$error_data_json	= json_encode($error_data);
						
						$sql_insert_error_log	= " INSERT INTO `tbl_error_data`(`error_id`, `error_module_name`, `error_file`, `error_data`,";
						$sql_insert_error_log	.= " `error_status`, `error_created`, `error_created_by`) VALUES ('".$error_id."', '".$error_module_name."', '".$error_file."', '".$error_data_json."', '".$error_status."', '".$datetime."', '".$uid."') ";
						$res_insert_error_log 	= mysqli_query($db_con, $sql_insert_error_log) or die(mysqli_error($db_con));	
						
						$insertion_flag	= 1;
					}
				}
			}
			else
			{
				// error data array
				$error_data = array("filt_name"=>$cat_name, "filt_description"=>$cat_description, "filt_type"=>$cat_parent_type);	
				
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
				
				$error_module_name	= "fliters";
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
if((isset($obj->load_filt_level)) != "" && isset($obj->load_filt_level))
{	
	$filt_id			= mysqli_real_escape_string($db_con,$obj->filt_id);
	$filt_parent_type 	= mysqli_real_escape_string($db_con,$obj->filt_parent_type);
	if($filt_id == "" && $filt_parent_type == "" )
	{
		$response_array = array("Success"=>"fail","resp"=>"Failed.");
	}
	else
	{
		$data = "";		
		if($filt_parent_type == 'parent')
		{
			$sql_filt_level = " SELECT * FROM `tbl_filt_level` WHERE `filtlevel_status` = 1 and filtlevel_filt_parent_id = '".$filt_id."' ";
		}
		else
		{
			$sql_filt_level = " SELECT * FROM `tbl_filt_level` WHERE `filtlevel_status` = 1 and filtlevel_filt_parent_id = '".$filt_parent_type."' and filtlevel_filt_child_id = '".$filt_id."' ";
		}
		$result_filt_level 		= mysqli_query($db_con,$sql_filt_level) or die(mysqli_error($db_con));
		$num_rows_filt_level	= mysqli_num_rows($result_filt_level);
		if($num_rows_filt_level == 0)
		{
		}
		else
		{
			$filtlevel_filt_child	= array();
			$filtlevel_level_parent = array();
			$filtlevel_level_child	= array();
			while($row_filt_level = mysqli_fetch_array($result_filt_level))
			{
				if($filt_parent_type == 'parent')
				{
					array_push($filtlevel_filt_child,$row_filt_level['filtlevel_filt_child_id']);
				}			
				array_push($filtlevel_level_parent,$row_filt_level['filtlevel_level_parent_id']);
				array_push($filtlevel_level_child,$row_filt_level['filtlevel_level_child_id']);
			}
		}
		/*Filter Part Start here */				
		$data .= '<div class="text-center" style="width:100%;">';
		$data .= '<h3>Filters</h3>';
		$data .= '<div>';
		$sql_get_parent_filters = " SELECT * FROM tbl_filters WHERE ";
		if($filt_parent_type == 'parent')
		{
			$sql_get_parent_filters .= " filt_type = 'parent' and filt_id = '".$filt_id."' ";
		}
		else
		{
			$sql_get_parent_filters .= " filt_id ='".$filt_parent_type."'";
		}
		$sql_get_parent_filters .= " order by filt_sort_order ASC ";
		$result_get_parent_filters = mysqli_query($db_con,$sql_get_parent_filters) or die(mysqli_error($db_con));
		while($row_get_parent_filters = mysqli_fetch_array($result_get_parent_filters))
		{
			$data .= '<div style="float:left;border:1px solid #BBBBBB;margin:2px;padding:10px;">';
			$data .= '<input type="checkbox" id="'.$row_get_parent_filters['filt_id'].'filt_parent" name="filt_parent" class="css-checkbox"';
			$data .= ' disabled="disabled" ';
			$data .= ' checked';
			$data .= '>';
			$data .= ucwords($row_get_parent_filters['filt_name']).'<label for="'.$row_get_parent_filters['filt_id'].'filt_parent" class="css-label" ></label>';
			
			$sql_get_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' ";
			if($filt_parent_type != 'parent')
			{
				$sql_get_child_filters .= "	and filt_id = '".$filt_id."' ";			
			}
			$sql_get_child_filters .= " order by filt_sort_order ASC ";
			$result_get_child_filters = mysqli_query($db_con,$sql_get_child_filters) or die(mysqli_error($db_con));			
			$data .= '<div style="margin:20px;">';			
			while($row_get_child_filters = mysqli_fetch_array($result_get_child_filters))
			{
				$data .= '<input type="checkbox" id="'.$row_get_child_filters['filt_id'].'filt_child" name="'.$row_get_parent_filters['filt_id'].'filt_child" class="css-checkbox"';
				if($filt_parent_type != 'parent')
				{
					$data .= ' disabled="disabled" checked>';
				}	
				else
				{
					if(in_array($row_get_child_filters['filt_id'],$filtlevel_filt_child))
					{
						$data .= ' checked';
					}
					$data .= '>';
				}			
				$data .= ucwords($row_get_child_filters['filt_name']).'<label for="'.$row_get_child_filters['filt_id'].'filt_child" class="css-label"></label>';
			}
			$data .= '</div>';			
			$data .= '</div>';			
		}
		$data .= '</div>';
		$data .= '</div>';		
		/*Filter Part End here */		
		$data .= '<div style="clear:both"></div>';		
		/*Level Part Starts here */
		$data .= '<div class="text-center" style="width:100%;">';
		$data .= '<h3>Select Level</h3>';
		$data .= '<div>';
		$sql_get_parent_levels 			= " select * from tbl_level where cat_type = 'parent' and cat_status != 0 ";
		$result_get_parent_levels 		= mysqli_query($db_con,$sql_get_parent_levels) or die(mysqli_error($db_con));
		while($row_get_parent_levels 	= mysqli_fetch_array($result_get_parent_levels))
		{
			$data .= '<div style="float:left;border:1px solid #BBBBBB;margin:2px;padding:10px;">';
			$data .= '<input type="checkbox" id="'.$row_get_parent_levels['cat_id'].'level_parent" name="level_parent" class="css-checkbox" ';
			if(in_array($row_get_parent_levels['cat_id'],$filtlevel_level_parent))
			{
				$data .= ' checked';
			}
			$data .= '>';
			$data .= ucwords($row_get_parent_levels['cat_name']).'<label for="'.$row_get_parent_levels['cat_id'].'level_parent" class="css-label" ></label>';
					
			$sql_get_child_levels = " select * from tbl_level where cat_type = '".$row_get_parent_levels['cat_id']."' ";//and cat_name != 'none' ";
			$result_get_child_levels = mysqli_query($db_con,$sql_get_child_levels) or die(mysqli_error($db_con));			
			$data .= '<div style="margin:20px;">';			
			while($row_get_child_levels = mysqli_fetch_array($result_get_child_levels))
			{
				$data .= '<input type="checkbox" id="'.$row_get_child_levels['cat_id'].'level_child" name="'.$row_get_parent_levels['cat_id'].'level_child" class="css-checkbox"';
				if(in_array($row_get_child_levels['cat_id'],$filtlevel_level_child))
				{
					$data .= ' checked';
				}
				$data .= '>';
				$data .= ucwords($row_get_child_levels['cat_name']).'<label for="'.$row_get_child_levels['cat_id'].'level_child" class="css-label"></label>';
			}
			$data .= '</div>';			
			$data .= '</div>';			
		}
		$data .= '</div>';
		$data .= '</div>';	
		$data .= '<button class="btn-success text-center" onClick="assignFiltLevel();" style="margin:30px;width:70%;height:30px;">Assign Level to Filters</button>';
		$response_array = array("Success"=>"Success","resp"=>$data);			
	}	
	echo json_encode($response_array);	
}
if((isset($obj->assign_filt_level)) != "" && isset($obj->assign_filt_level))
{
	$filters_data 	= $obj->filters_data;
	$levels_data	= $obj->levels_data;
	if(sizeof($filters_data) == 0 && sizeof($levels_data) == 0)
	{
		$response_array = array("Success"=>"fail","resp"=>"Please Select Filters And Levels");
	}
	else
	{
		foreach($filters_data as $filter_id) 
		{
			$filter 					= explode(":",$filter_id);
			$filtlevel_filt_parent_id	= $filter[0];
			$filtlevel_filt_child_id	= $filter[1];
			if($filtlevel_filt_parent_id == "" && $filtlevel_filt_child_id == "")
			{
				
			}
			else
			{
				 // iterate each level from array to add and activate 				 
				foreach($levels_data as $levels_id)
				{
					$levels 					= explode(":",$levels_id);
					$filtlevel_level_parent_id	= $levels[0];
					$filtlevel_level_child_id	= $levels[1];
					if($filtlevel_level_parent_id == "" && $filtlevel_level_child_id == "")
					{
						
					}
					else
					{	
						$sql_check_exists 		= " SELECT * FROM `tbl_filt_level` WHERE  ";
						$sql_check_exists 		.= " `filtlevel_filt_parent_id` = '".$filtlevel_filt_parent_id."' and `filtlevel_filt_child_id` = '".$filtlevel_filt_child_id."' ";
						$sql_check_exists 		.= " and `filtlevel_level_parent_id` = '".$filtlevel_level_parent_id."' and `filtlevel_level_child_id` = '".$filtlevel_level_child_id."' ";
						$result_check_exists 	= mysqli_query($db_con,$sql_check_exists) or die(mysqli_error($db_con));
						$num_rows_check_exists 	= mysqli_num_rows($result_check_exists);
						if($num_rows_check_exists == 0)
						{
							$new_id				= "filtlevel_id";
							$table_name			= "tbl_filt_level";
							$filtlevel_id 		= getNewId($new_id,$table_name);
							$sql_add_filt_level = " INSERT INTO `tbl_filt_level`(`filtlevel_id`, `filtlevel_filt_parent_id`, `filtlevel_filt_child_id`, `filtlevel_level_parent_id`, ";
							$sql_add_filt_level .= " `filtlevel_level_child_id`, `filtlevel_status`, `filtlevel_created`, `filtlevel_created_by`) VALUES ('".$filtlevel_id."'";
							$sql_add_filt_level .= " ,'".$filtlevel_filt_parent_id."','".$filtlevel_filt_child_id."','".$filtlevel_level_parent_id."','".$filtlevel_level_child_id."'";
							$sql_add_filt_level .= " ,'1','".$datetime."','".$uid."')";
							$result_add_filt_level = mysqli_query($db_con,$sql_add_filt_level) or die(mysqli_error($db_con));
							if($result_add_filt_level)
							{
							}
							else
							{
							}
						}
						else
						{
							$sql_update_filt_level 		= " UPDATE `tbl_filt_level` SET `filtlevel_status`= '1',`filtlevel_modified`='".$datetime."',`filtlevel_modified_by`='".$uid."' WHERE ";
							$sql_update_filt_level 		.= " `filtlevel_filt_parent_id`='".$filtlevel_filt_parent_id."' and `filtlevel_filt_child_id`= '".$filtlevel_filt_child_id."' and ";
							$sql_update_filt_level 		.= " `filtlevel_level_parent_id`='".$filtlevel_level_parent_id."' and `filtlevel_level_child_id`= '".$filtlevel_level_child_id."' ";
							$result_update_filt_level 	= mysqli_query($db_con,$sql_update_filt_level) or die(mysqli_error($db_con));
							if($result_update_filt_level)
							{
							}
							else
							{

							}							
						}						
					}
				}
				 // iterate each level from array to add and activate 
			}
		}
		
		// find all levels and deactivate 			
		$filter_id 					= explode(":",$filters_data[0]);
		$filtlevel_filt_parent_id 	= $filter_id[0];	
		$sql_get_levels 	= " SELECT * FROM `tbl_filt_level` WHERE `filtlevel_filt_parent_id` = '".$filtlevel_filt_parent_id."' ";
		$result_get_levels  = mysqli_query($db_con,$sql_get_levels) or die(mysqli_error($db_con));
		$num_rows_get_levels = mysqli_num_rows($result_get_levels);
		if($num_rows_get_levels == 0)
		{
				
		}
		else
		{
			$filt_levels_list_db= array();
			while($row_get_levels = mysqli_fetch_array($result_get_levels))
			{
				$filter_parent 	= $row_get_levels['filtlevel_filt_parent_id'];				
				$filter_child 	= $row_get_levels['filtlevel_filt_child_id'];				
				$level_parent 	= $row_get_levels['filtlevel_level_parent_id'];
				$level_child 	= $row_get_levels['filtlevel_level_child_id'];
				$level_cmbine_id= $filter_parent.":".$filter_child.":".$level_parent.":".$level_child;
				array_push($filt_levels_list_db,$level_cmbine_id);
			}
			$filt_level_user 	= array();
			foreach($filters_data as $filter_id) // iterate each filter from array
			{
				$filter 					= explode(":",$filter_id);
				$filtlevel_filt_parent_id	= $filter[0];
				$filtlevel_filt_child_id	= $filter[1];
				foreach($levels_data as $levels_id)
				{
					$levels 					= explode(":",$levels_id);
					$filtlevel_level_parent_id	= $levels[0];
					$filtlevel_level_child_id	= $levels[1];
					$filt_level_cmbine_id = $filtlevel_filt_parent_id.":".$filtlevel_filt_child_id.":".$filtlevel_level_parent_id.":".$filtlevel_level_child_id;
					array_push($filt_level_user,$filt_level_cmbine_id);
				}
			}
			foreach($filt_levels_list_db as $filt_level_db)
			{
				if(in_array($filt_level_db,$filt_level_user))
				{
				}
				else
				{
					$filt_level				= explode(":",$filt_level_db);
					$filt_parent_id			= $filt_level[0];
					$filt_child_id			= $filt_level[1];
					$level_parent_id		= $filt_level[2];
					$level_child_id			= $filt_level[3];							
					$sql_update_filt_level 	= " UPDATE `tbl_filt_level` SET `filtlevel_status`= '0',`filtlevel_modified`='".$datetime."',`filtlevel_modified_by`='".$uid."' WHERE ";
					$sql_update_filt_level 	.= " `filtlevel_filt_parent_id`='".$filt_parent_id."' and `filtlevel_filt_child_id`= '".$filt_child_id."' and ";
					$sql_update_filt_level 	.= " `filtlevel_level_parent_id`='".$level_parent_id."' and `filtlevel_level_child_id`= '".$level_child_id."' ";
					$result_update_filt_level= mysqli_query($db_con,$sql_update_filt_level) or die(mysqli_error($db_con));
					if($result_update_filt_level)
					{
						
					}
					else
					{
					}
				}
			}					
		}
		// find all levels and deactivate 				
		$response_array = array("Success"=>"Success","resp"=>"Filters and Levels Assignment Succesfull");
	}
	echo json_encode($response_array);	
}
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
		$sql_cat_data 		= " Select `filt_id`, `filt_name`, `filt_description`, `filt_type`,`filt_sub_child`, `filt_sort_order`, `filt_status`,";
		$sql_cat_data 		.= " (SELECT filt_name FROM `tbl_filters` tc WHERE tc.filt_id = tcp.filt_type) as filt_type_name,";
		$sql_cat_data 		.= " (SELECT filt_name FROM `tbl_filters` tc WHERE tc.filt_id = tcp.filt_sub_child) as filt_sub_child_name FROM `tbl_filters` tcp WHERE `filt_id` = '".$cat_id."' ";
		$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
		$row_cat_data		= mysqli_fetch_array($result_cat_data);		
	}	
	else if($cat_id != "" && $req_type == "view")
	{
		$sql_cat_data 		= " SELECT `id`, `filt_id`, `filt_name`, `filt_description`, `filt_type`,`filt_sub_child` ,`filt_sort_order`, ";
		$sql_cat_data 		.= " `filt_status`, `filt_created`, `filt_createdby`, `filt_modified`, `filt_modifiedby`,";
		$sql_cat_data 		.= " (SELECT filt_name FROM `tbl_filters` tc WHERE tc.filt_id = tcp.filt_type) as filt_type_name,";
		$sql_cat_data 		.= " (SELECT filt_name FROM `tbl_filters` tc WHERE tc.filt_id = tcp.filt_sub_child) as filt_sub_child_name FROM `tbl_filters` tcp WHERE `filt_id` = '".$cat_id."' ";
		$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
		$row_cat_data		= mysqli_fetch_array($result_cat_data);		
	}
	$filtlevel_level_parent = array();
	$filtlevel_level_child	= array();	
	if($cat_id != "" && $req_type != "add")
	{
		$sql_filt_level 		= " SELECT * FROM `tbl_filter_level` WHERE `filterlevel_filt_child_id` =  '".$cat_id."' ";
		$result_filt_level 		= mysqli_query($db_con,$sql_filt_level) or die(mysqli_error($db_con));
		$num_rows_filt_level	= mysqli_num_rows($result_filt_level);
		if($num_rows_filt_level == 0)
		{
		}
		else
		{
			while($row_filt_level = mysqli_fetch_array($result_filt_level))
			{
				array_push($filtlevel_level_parent,$row_filt_level['filterlevel_level_parent_id']);
				$child_list = explode(",",$row_filt_level['filterlevel_level_child_id']);
				foreach($child_list as $child_id)
				{
					array_push($filtlevel_level_child,$child_id);
				}
			}			
		}
	}
	$data = '';
	if($cat_id != "" && $req_type == "edit")
	{
		$data = '<input type="hidden" id="cat_id" value="'.$row_cat_data['filt_id'].'">';
	}		
	elseif($cat_id != "" && $req_type == "error")
	{
		$data = '<input type="hidden" id="error_id" value="'.$cat_id.'">';
	}		
	$data .= '<div class="control-group">';
	$data .= '<label for="tasktitel" class="control-label">Filter Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
	$data .= '<div class="controls">';
	$data .= '<input type="text" id="cat_name" name="cat_name" class="input-large" data-rule-required="true"';
	if($cat_id != "" && $req_type == "error")
	{
		$data .= 'value="'.$row_cat_data->filt_name.'"';					
	}
	elseif($cat_id != "" && $req_type == "view")
	{
		$data .= 'value="'.$row_cat_data['filt_name'].'" disabled';					
	}		
	else
	{
		$data .= 'value="'.$row_cat_data['filt_name'].'"';					
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
		$data .= $row_cat_data->filt_description;		
	}
	elseif($cat_id != "" && $req_type == "view")
	{
		$data .= $row_cat_data['filt_description'];					
	}		
	else
	{
		$data .= $row_cat_data['filt_description'];
	}		
	$data .= '</textarea>';				
	$data .= '</div>';
	$data .= '</div> <!--Description-->';
	$data .= '<div class="control-group">';
	$data .= '<div class="control-group span6">';
	$data .= '<label for="tasktitel" class="control-label">Type(GrandFather) <sup class="validfield"></sup></label>';
	$data .= '<div class="controls">';
	if($req_type != "view")
	{
		$data .= '<select name="cat_parent" id="cat_parent" placeholder="Type" class="select2-me input-large" onchange="getFilterChild(this.id);">';
		$data .= '<option value="">Select Type</option>';						
	}
	if(($cat_id == "" && $req_type == "add") || ($cat_id != "" && $req_type == "error"))
	{
			$data .= '<option value="parent">Parent</option>';			
			$sql_get_parent = "SELECT distinct 	filt_id,filt_name FROM `tbl_filters` where filt_type = 'Parent' ";
			$result_get_parent = mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));														
			while($row_get_parent = mysqli_fetch_array($result_get_parent))
			{															
				$data .= '<option value="'.$row_get_parent['filt_id'].'">'.ucwords($row_get_parent['filt_name']).'</option>';
			}
	}
	elseif($cat_id != "" && $req_type == "view")
	{
		if(strcmp(trim($row_cat_data['filt_sub_child']),"parent") == 0)
		{
			$data .= '<label class="control-label" >'.ucwords($row_cat_data['filt_type']).'</label>';
		}
		elseif(strcmp(trim($row_cat_data['filt_sub_child']),"child") == 0)
		{
			$data .= '<label class="control-label" >'.ucwords($row_cat_data['filt_type']).'</label>';
		}		
		else
		{
			$data .= '<label class="control-label" >'.ucwords($row_get_parent['filt_type_name']).'</label>';
		}
	}		
	elseif($cat_id != "" && $req_type == "edit")
	{
			if(strcmp(trim($row_cat_data['filt_type']),"parent") == 0)
			{
				$data .= '<option value="parent" selected="selected">Parent</option>';			
			}
			else
			{
				$data .= '<option value="parent" >Parent</option>';							
			}
			$sql_get_parent 		= " SELECT distinct filt_id,filt_name FROM `tbl_filters` where filt_sub_child = 'parent' and `filt_name` != '".$row_cat_data['filt_name']."' ";
			$result_get_parent 		= mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));														
			while($row_get_parent 	= mysqli_fetch_array($result_get_parent))
			{		
				if($row_get_parent['filt_id'] == $row_cat_data['filt_type'])
				{
					$data .= '<option value="'.$row_get_parent['filt_id'].'" selected>'.ucwords($row_get_parent['filt_name']).'</option>';
				}
				//elseif(strcmp(trim($row_cat_data['filt_name']),$row_get_parent['filt_name']) == 0){}
				else
				{
					$data .= '<option value="'.$row_get_parent['filt_id'].'">'.ucwords($row_get_parent['filt_name']).'</option>';
				}
			}			
		}
		$data .= '</select>';
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div> <!--Parent-->';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Type(Father)<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select name="filt_sub_child" id="filt_sub_child" placeholder="Type" class="select2-me input-large" data-rule-required="true" >';
		}
		if(($cat_id == "" && $req_type == "add") || ($cat_id != "" && $req_type == "error"))
		{
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			if(strcmp(trim($row_cat_data['filt_sub_child']),"parent") == 0)
			{
				$data .= '<label class="control-label" >'.ucwords($row_cat_data['filt_sub_child']).'</label>';
			}
			elseif(strcmp(trim($row_cat_data['filt_sub_child']),"child") == 0)
			{
				$data .= '<label class="control-label" >'.ucwords($row_cat_data['filt_sub_child']).'</label>';
			}					
			else
			{
				$data .= '<label class="control-label" >'.ucwords($row_get_parent['filt_sub_child_name']).'</label>';
			}
		}		
		elseif($cat_id != "" && $req_type == "edit")
		{
				if(strcmp(trim($row_cat_data['filt_sub_child']),"parent") == 0)
			{
				$data .= '<option value="parent" selected="selected">Parent</option>';			
			}
			elseif(strcmp(trim($row_cat_data['filt_sub_child']),"child") == 0)
			{
				$data .= '<option value="child" selected="selected">Child</option>';
			}
			else
			{
				$data .= '<option value="child" >Child</option>';				
			}			
			$sql_get_parent 		= " SELECT distinct filt_id,filt_name FROM `tbl_filters` where filt_type != 'parent' and filt_sub_child = 'child' and filt_name != 'none' ";
			$result_get_parent 		= mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));														
			while($row_get_parent 	= mysqli_fetch_array($result_get_parent))
			{		
				if($row_get_parent['filt_id'] == $row_cat_data['filt_sub_child'])
				{
					$data .= '<option value="'.$row_get_parent['filt_id'].'" selected>'.ucwords($row_get_parent['filt_name']).'</option>';
				}
				//elseif(strcmp(trim($row_cat_data['cat_name']),$row_get_parent['cat_name']) == 0){}
				else
				{
					$data .= '<option value="'.$row_get_parent['filt_id'].'">'.ucwords($row_get_parent['filt_name']).'</option>';
				}
			}			
		}
		$data .= '</select>';
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div></div> <!--Parent-->';	
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Tags <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="filt_meta_tags" name="filt_meta_tags" class="input-large"';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_cat_data->cat_meta_tags.'"';
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_cat_data['filt_meta_tags'].'" disabled';
		}		
		else
		{
			$data .= 'value="'.$row_cat_data['filt_meta_tags'].'"';
		}		
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Tags-->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Description <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="filt_meta_description" name="filt_meta_description" data-rule-required="false">';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= $row_cat_data->filt_meta_description;
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= $row_cat_data['filt_meta_description'];
		}		
		else
		{
			$data .= $row_cat_data['filt_meta_description'];
		}		
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Description-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Title <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="filt_meta_title" name="filt_meta_title" class="input-large"';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_cat_data->cat_meta_title.'"';
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_cat_data['filt_meta_title'].'" disabled';
		}		
		else
		{
			$data .= 'value="'.$row_cat_data['filt_meta_title'].'"';
		}		
		$data .= '/>';		
		$data .= '</div>';
		$data .= '</div> <!--Meta Title-->';
		
			
		$data .= '<div class="control-group span12">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($cat_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="cat_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_filters.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_cat_data->filt_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="cat_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_cat_data->filt_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($cat_id != "" && $req_type == "view")
		{
			if($row_cat_data['filt_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_cat_data['filt_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="cat_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_filters.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_cat_data['filt_status'] == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="cat_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_cat_data['filt_status'] == 0)
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
		$data .= 'CKEDITOR.replace("cat_description",{height:"150", width:"100%"});';
		if($cat_id != "" && $req_type == "view")
		{
			$data .= '$("#cat_description").prop("disabled","true");';
			$data .= '$("#cat_meta_description").prop("disabled","true");';								
		}	
		elseif($cat_id != "" && $req_type == "edit")
		{
			$data .= '$("#cat_parent").prop(\'disabled\', true);';
			$data .= '$("#filt_sub_child").prop("disabled","disabled");';
		}			
		$data .= '$("#cat_parent").select2();';
		$data .= '$("#filt_sub_child").select2();';
		$data .= '</script>';
	$response_array = array("Success"=>"Success","resp"=>$data);
	echo json_encode($response_array);
}
if((isset($obj->insert_req)) == "1" && isset($obj->insert_req))
{
	
	$cat_name				= strtolower(mysqli_real_escape_string($db_con,$obj->cat_name));
	$cat_description		= strtolower(mysqli_real_escape_string($db_con,$obj->cat_description));	
	$cat_parent				= strtolower(mysqli_real_escape_string($db_con,$obj->cat_parent));	
	$filt_sub_child			= trim(mysqli_real_escape_string($db_con,$obj->filt_sub_child));
	$cat_status				= mysqli_real_escape_string($db_con,$obj->cat_status);
	$filt_meta_description	= strtolower(mysqli_real_escape_string($db_con,$obj->filt_meta_description));
	$filt_meta_title			= strtolower(mysqli_real_escape_string($db_con,$obj->filt_meta_title));
	$filt_meta_tags			= strtolower(mysqli_real_escape_string($db_con,$obj->filt_meta_tags));

	$response_array = array();	
	
	if($cat_name != "" && $cat_parent != "" && $cat_status != "" && $filt_sub_child != "")
	{
	$response_array = insertFilter($uid,$db_con,$cat_name,$cat_description,$cat_parent,$filt_sub_child,$cat_status,$filt_meta_description,  $filt_meta_tags,$filt_meta_title,$response_array);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{
	$cat_id					= mysqli_real_escape_string($db_con,$obj->cat_id);
	$cat_name				= mysqli_real_escape_string($db_con,$obj->cat_name);
	$cat_description		= mysqli_real_escape_string($db_con,$obj->cat_description);	
	$cat_parent				= mysqli_real_escape_string($db_con,$obj->cat_parent);	
	$filt_sub_child			= mysqli_real_escape_string($db_con,$obj->filt_sub_child);		
	$cat_status				= mysqli_real_escape_string($db_con,$obj->cat_status);
	$filt_meta_description	= mysqli_real_escape_string($db_con,$obj->filt_meta_description);
	$filt_meta_title			= mysqli_real_escape_string($db_con,$obj->filt_meta_title);
	$filt_meta_tags			= mysqli_real_escape_string($db_con,$obj->filt_meta_tags);
	$cat_slug 				= strtolower(str_replace(" ","-",$cat_name));

	$change_flag			= 1;
	$response_array = array();	
	if($cat_name != "" && $cat_parent != "" && $cat_status != "" && $filt_sub_child != "")
	{		
		$sql_check_cat 		= " SELECT * FROM tbl_filters WHERE filt_name LIKE '".$cat_name."' AND `filt_id` != '".$cat_id."' ";
		$sql_check_cat 		.= " AND `filt_type` != '".$cat_parent."' AND `filt_sub_child` != '".$filt_sub_child."' ";
		$result_check_cat 	= mysqli_query($db_con,$sql_check_cat) or die(mysqli_error($db_con));
		$num_rows_check_cat = mysqli_num_rows($result_check_cat);
		if($num_rows_check_cat == 0)
		{
			$sql_get_self_data 		= "Select * from tbl_filters where `filt_id` = '".$cat_id."' ";
			
			$result_get_self_data 	= mysqli_query($db_con,$sql_get_self_data) or die(mysqli_error($db_con));
			$rows_get_self_data 	= mysqli_fetch_array($result_get_self_data);	
			if($change_flag == 1)
			{
				
				
				$sql_update_category = " UPDATE `tbl_filters` SET `filt_name`='".$cat_name."',`filt_description`='".$cat_description."', ";	
				$sql_update_category .= " `filt_modified`='".$datetime."',`filt_modifiedby`='".$uid."' WHERE `filt_id` = '".$cat_id."' ";
				
				$result_update_category = mysqli_query($db_con,$sql_update_category) or die(mysqli_error($db_con));
				if($result_update_category)
				{
					$sql_update_filt_status = " UPDATE `tbl_filters` SET `filt_name`='".$cat_name."',`filt_description`='".$cat_description."', ";	
					if($rows_get_self_data['filt_type'] == "parent" && $rows_get_self_data['filt_sub_child'] == "parent")
					{
						/*update self status*/						
						$sql_update_filt_status .= " filt_status = '".$cat_status."' , ";
						/*update self status*/
						/* update child status according to parent status*/
						$sql_update_chid_status = " UPDATE `tbl_filters` SET filt_status = '".$curr_status."' ,`filt_modified`='".$datetime."',`filt_modifiedby`='".$uid."' WHERE `filt_type` = '".$cat_id."' ";
						
						
						$result_update_chid_status = mysqli_query($db_con,$sql_update_chid_status) or die(mysqli_error($db_con));
						/* update child status according to parent status*/						
					}
					elseif($rows_get_self_data['filt_type'] != "parent" && $rows_get_self_data['filt_sub_child'] == "child")
					{
						$sql_get_self_parent 	= " Select * from tbl_filters where filt_id = '".$rows_get_self_data['filt_type']."' ";
						$result_get_self_parent = mysqli_query($db_con,$sql_get_self_parent) or die(mysqli_error($db_con));
						$row_self_parent		= mysqli_fetch_array($result_get_self_parent); 
						if($row_self_parent['filt_status'] == 0)//if parent status is inactive(0) make self inactive
						{
							/* update child status according to parent status*/
							$sql_update_chid_status = " UPDATE `tbl_filters` SET filt_status = '".$curr_status."' ,`filt_modified`='".$datetime."',`filt_modifiedby`='".$uid."' WHERE `filt_sub_child` = '".$cat_id."' ";
							$result_update_chid_status = mysqli_query($db_con,$sql_update_chid_status) or die(mysqli_error($db_con));
							/* update child status according to parent status*/														
							$sql_update_filt_status .= " filt_status = 0 ";
						}						
						else
						{
							/* update child status according to parent status*/
							$sql_update_chid_status = " UPDATE `tbl_filters` SET filt_status = '".$curr_status."' ,`filt_modified`='".$datetime."',`filt_modifiedby`='".$uid."' WHERE `filt_sub_child` = '".$cat_id."' ";
							$result_update_chid_status = mysqli_query($db_con,$sql_update_chid_status) or die(mysqli_error($db_con));
							/* update child status according to parent status*/							
							$sql_update_filt_status .= " filt_status = '".$curr_status."', ";
						}
					}
					elseif($rows_get_self_data['filt_type'] != "parent" && $rows_get_self_data['filt_sub_child'] != "child")
					{
						$parent_status_flag 	= 0;
						$sql_get_self_parent 	= " Select * from tbl_filters where filt_id = '".$rows_get_self_data['filt_sub_child']."' ";
						$sql_get_self_parent 	.= "  and filt_id = '".$rows_get_self_data['filt_type']."' ";
						$result_get_self_parent = mysqli_query($db_con,$sql_get_self_parent) or die(mysqli_error($db_con));
						while($row_self_parent	= mysqli_fetch_array($result_get_self_parent))
						{
							if($row_self_parent['filt_status'] == 0)
							{
								$parent_status_flag = 1;
							}
						}
						if($parent_status_flag == 1)
						{
							$sql_update_filt_status .= " filt_status = 0 , ";
						}						
						else
						{
							$sql_update_filt_status .= " filt_status = '".$curr_status."' , ";
						}
					}
					
					$sql_update_filt_status .= " `filt_modified`='".$datetime."',`filt_modifiedby`='".$uid."' WHERE `filt_id` = '".$cat_id."' ";	
					//echo json_encode(array("Success"=>'fail',"resp"=>$sql_update_filt_status));exit();				
					$result_update_filt_status = mysqli_query($db_con,$sql_update_filt_status) or die(mysqli_error($db_con));
					if($result_update_filt_status)
					{			
							
					}
					$change_flag = 1;
				}
				else
				{
					$change_flag = 0;					
					$response_array = array("Success"=>"fail","resp"=>"Filters Not Updated");								
				}																	
			}
			else
			{
				$change_flag = 0;				
				$response_array = array("Success"=>"fail","resp"=>"Filters Flag Not Assigned");				
			}
							
			$parent_list	= array();
		}		
		else
		{
			$change_flag = 0;			
			$response_array = array("Success"=>"fail","resp"=>"Filters ".$cat_name." already Exists.");	
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	
	if($change_flag == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Update Succesfull".$sql_delete_old);
	}
	elseif($change_flag == 0)
	{
		
	}
	echo json_encode($response_array);		
}
if((isset($obj->load_cat)) == "1" && isset($obj->load_cat))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 				= mysqli_real_escape_string($db_con,$obj->page);	
	$per_page			= mysqli_real_escape_string($db_con,$obj->row_limit);
	$search_text		= mysqli_real_escape_string($db_con,$obj->search_text);	
	$filt_type_val		= mysqli_real_escape_string($db_con,$obj->filt_type_val);
	$filt_sub_child_val	= mysqli_real_escape_string($db_con,$obj->filt_sub_child_val);
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;			
		$sql_load_data  = " SELECT filt_id,filt_slug,filt_name,filt_description,filt_sub_child,filt_sort_order,filt_created,filt_modified,filt_status, filt_type,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.filt_createdby) as filt_createdby, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.filt_modifiedby) as filt_modifiedby, ";
		$sql_load_data  .= " (SELECT filt_name FROM `tbl_filters` WHERE filt_id = tc.filt_type) as parent_name ";				
		$sql_load_data  .= " FROM tbl_filters tc WHERE filt_sub_child like '".$filt_sub_child_val."' and filt_type = '".$filt_type_val."' ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND filt_createdby='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (filt_name like '%".$search_text."%' or filt_description like '%".$search_text."%' ";
			$sql_load_data .= " or filt_created like '%".$search_text."%' or filt_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .= " ORDER BY filt_sort_order LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$cat_data = "";			
			if($filt_sub_child_val == "parent")
			{
				$cat_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Parent List</label><br>';
			}
			elseif($filt_sub_child_val == "child")
			{
				$cat_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Child List</label><br>';
			}
			else
			{
				$cat_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Sub Child List</label><br>';
			}			
			$cat_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$cat_data .= '<thead>';
    	  	$cat_data .= '<tr>';
         	$cat_data .= '<th style="text-align:center">Sr No.</th>';
			$cat_data .= '<th style="text-align:center">Fit Id</th>';
			$cat_data .= '<th style="text-align:center">Filter Name</th>';
			$cat_data .= '<th style="width:6%;text-align:center">Sort Order</th>';
                        $cat_data .= '<th style="width:15%;text-align:center">Slug/Url</th>';/*Code By Tariq -16-09-2016*/
			$cat_data .= '<th style="text-align:center">Created</th>';
			$cat_data .= '<th style="text-align:center">Created By</th>';
			$cat_data .= '<th style="text-align:center">Modified</th>';
			$cat_data .= '<th style="text-align:center">Modified By</th>';
			$cat_data .= '<th style="text-align:center">View</th>';
			$dis = checkFunctionalityRight("view_filters.php",3);
			if($dis)
			{
				$cat_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_filters.php",1);
			if($edit)
			{
				$cat_data .= '<th style="text-align:center">Edit</th>';
			}
			$del = checkFunctionalityRight("view_filters.php",2);
			if($del)
			{
				$cat_data .= '<th style="text-align:center">';
				$cat_data .= '<div style="text-align:center">';
				$cat_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$cat_data .= '</tr>';
      		$cat_data .= '</thead>';
      		$cat_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$cat_data .= '<tr>';				
				$cat_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$cat_data .= '<td style="text-align:center">'.$row_load_data['filt_id'].'</td>';
				$cat_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['filt_name']).'" class="btn-link" id="'.$row_load_data['filt_id'].'" onclick="addMoreFilters(this.id,\'view\');"></td>';				
				$cat_data .= '<td style="text-align:center">';
				$cat_data .= '<input type="text" style="text-align:center;width:50%" onblur="changeOrder(this.id,this.value)" id="'.$row_load_data['filt_id'].'" value="'.$row_load_data['filt_sort_order'].'">';
				$cat_data .= '</td>';
                                /*Code By Tariq -16-09-2016*/
                                $cat_data .= '<td class="center-text">';
				$cat_data .= '<textarea style="text-align:center;width:50%" onblur="changeFiltSlug(this.id)" id="'.$row_load_data['filt_id'].'filt_slug" >'.$row_load_data['filt_slug'].'</textarea>';
				$cat_data .= '</td>';	
                                /*Code End By Tariq -16-09-2016*/
				$cat_data .= '<td style="text-align:center">'.$row_load_data['filt_created'].'</td>';
				$cat_data .= '<td style="text-align:center">'.$row_load_data['filt_createdby'].'</td>';
				$cat_data .= '<td style="text-align:center">'.$row_load_data['filt_modified'].'</td>';
				$cat_data .= '<td style="text-align:center">'.$row_load_data['filt_modifiedby'].'</td>';
				if($row_load_data['filt_sub_child'] == "parent")
				{
					$cat_data .= '<td style="text-align:center">';					
					$sql_check_parent 		= "SELECT * FROM `tbl_filters` WHERE `filt_type` like '".$row_load_data['filt_id']."'";
					$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
					$num_rows_check_parent 	= mysqli_num_rows($result_check_parent);
					if($num_rows_check_parent != 0)
					{
						$cat_data .= '<input type="button" class="btn-success"  value="View Child" id="'.$row_load_data['filt_id'].'" onclick="viewChild(this.id,\'child\')"></td>';
					}
				}
				elseif($row_load_data['filt_sub_child'] == "child")
				{
					$cat_data .= '<td style="text-align:center">';
					$cat_data .= '<br><input type="button" class="btn-success"  value="View Parent" id="parent" onclick="viewChild(\'parent\',\'parent\')">';					
					$cat_data .= '<br><br><input type="button" class="btn-success"  value="View Sub Child" 	id="'.$row_load_data['filt_id'].'" onclick="viewChild(\''.$row_load_data['filt_type'].'\',this.id)">';
					$cat_data .= '<br></td>';
				}
				else
				{
					$cat_data .= '<td style="text-align:center">';
					$cat_data .= '<br><input type="button" class="btn-success"  value="View Grand Parent" id="parent" onclick="viewChild(\'parent\',\'parent\')">';					
					$cat_data .= '<br><br><input type="button" class="btn-success"  value="View Parent" id="child" onclick="viewChild(\''.$row_load_data['filt_type'].'\',this.id)">';					
					$cat_data .= '<br></td>';
				}
				$cat_data .= '</td>';
				$dis = checkFunctionalityRight("view_filters.php",3);
				if($dis)			
				{
					$cat_data .= '<td style="text-align:center">';	
					if($row_load_data['filt_status'] == 1)
					{
						$cat_data .= '<input type="button" value="Active" id="'.$row_load_data['filt_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$cat_data .= '<input type="button" value="Inactive" id="'.$row_load_data['filt_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$cat_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_filters.php",1);				
				if($edit)
				{
					$cat_data .= '<td style="text-align:center">';
					$cat_data .= '<input type="button" value="Edit" id="'.$row_load_data['filt_id'].'" class="btn-warning" onclick="addMoreFilters(this.id,\'edit\');"></td>';
				}
				$del = checkFunctionalityRight("view_filters.php",2);
				if($del)				
				{
					$cat_data .= '<td><div class="controls" align="center">';					
					$cat_data .= '<input type="checkbox" value="'.$row_load_data['filt_id'].'" id="batch'.$row_load_data['filt_id'].'" name="batch'.$row_load_data['filt_id'].'" class="css-checkbox batch">';
					$cat_data .= '<label for="batch'.$row_load_data['filt_id'].'" class="css-label"></label>';
					$cat_data .= '</div></td>';										
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
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$cat_id					= mysqli_real_escape_string($db_con,$obj->cat_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();

	global $status_flag;
	$status_flag  			= 0;
	$sql_check_parent 		= "Select * from tbl_filters where `filt_id` = '".$cat_id."' ";
	$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
	$row_check_parent 		= mysqli_fetch_array($result_check_parent);
	if($row_check_parent['filt_sub_child'] == "parent")
	{
		$sql_all_sub_cat 		= " select * from tbl_filters where filt_type = '".$row_check_parent['filt_id']."' ";
		$result_all_sub_cat 	= mysqli_query($db_con,$sql_all_sub_cat) or die(mysqli_error($db_con));
		while($row_all_sub_cat 	= mysqli_fetch_array($result_all_sub_cat))
		{
			$sql_update_status 	= " UPDATE `tbl_filters` SET `filt_status`= '".$curr_status."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$row_all_sub_cat['filt_id']."' ";
			$result_update_status= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
			if($result_update_status)
			{
				$status_flag = 1;	
			}								
		}
		$sql_update_status 		= " UPDATE `tbl_filters` SET `filt_status`= '".$curr_status."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$cat_id."' ";
		$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
		if($result_update_status)
		{
			$status_flag = 1;	
		}
	}
	elseif($row_check_parent['filt_sub_child'] == "child")
	{
		$sql_get_grand_parent		= " SELECT * from tbl_filters where `filt_id` = '".$row_check_parent['filt_type']."' ";
		$result_get_grand_parent	= mysqli_query($db_con,$sql_get_grand_parent) or die(mysqli_error($db_con));
		$num_rows_get_grand_parent= mysqli_num_rows($result_get_grand_parent);			
		if($num_rows_get_grand_parent == 0)
		{
			$status_flag = 1;			
		}
		else
		{
			$row_get_grand_parent = mysqli_fetch_array($result_get_grand_parent);
			if($row_get_grand_parent['filt_status'] == 0 && $curr_status == 1)
			{
				$status_flag = 1;
			}
			else
			{
				$sql_get_parent		= " SELECT * from tbl_filters where `filt_sub_child` = '".$row_check_parent['filt_id']."' ";
				$result_get_parent	= mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));
				$num_rows_get_parent= mysqli_num_rows($result_get_parent);	
				if($num_rows_get_parent == 0)
				{
					$sql_update_status 		= " UPDATE `tbl_filters` SET `filt_status`= '".$curr_status."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$cat_id."' ";
					$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
					if($result_update_status)
					{
						$status_flag = 1;								
					}
					else
					{
						$status_flag = 0;
					}
				}
				else
				{
					while($row_get_parent 	= mysqli_fetch_array($result_get_parent))
					{
						$sql_update_status 		= " UPDATE `tbl_filters` SET `filt_status`= '".$curr_status."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$row_get_parent['filt_id']."' ";
						$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
						if($result_update_status)
						{
							$status_flag = 1;								
						}
						else
						{
							$status_flag = 0;
						}
					}
					$sql_update_status 		= " UPDATE `tbl_filters` SET `filt_status`= '".$curr_status."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$cat_id."' ";
					$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
					if($result_update_status)
					{
						$status_flag = 1;								
					}
					else
					{
						$status_flag = 0;
					}			
				}
			}
		}
	}
	else	
	{
		$sql_get_grand_parent		= " SELECT * from tbl_filters where `filt_id` = '".$row_check_parent['filt_type']."' ";
		$result_get_grand_parent	= mysqli_query($db_con,$sql_get_grand_parent) or die(mysqli_error($db_con));
		$num_rows_get_grand_parent= mysqli_num_rows($result_get_grand_parent);			
		if($num_rows_get_grand_parent == 0)
		{
			$status_flag = 0;			
		}
		else		
		{
			$row_get_grand_parent = mysqli_fetch_array($result_get_grand_parent);
			if($row_get_grand_parent['filt_status'] == 0 && $curr_status == 1)
			{
				$status_flag = 0;
			}
			else
			{
				$sql_get_parent		= " SELECT * from tbl_filters where `filt_id` = '".$row_check_parent['filt_sub_child']."' ";
				$result_get_parent	= mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));
				$num_rows_get_parent= mysqli_num_rows($result_get_parent);	
				if($num_rows_get_parent == 0)
				{
					$status_flag = 0;
				}
				else
				{
					$row_get_parent = mysqli_fetch_array($result_get_parent);
					if($row_get_parent['filt_status'] == 0 && $curr_status == 1)
					{
						$status_flag = 0;
					}
					else
					{	
					  	$sql_update_status 		= " UPDATE `tbl_filters` SET `filt_status`= '".$curr_status."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$cat_id."' ";
					  	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
					  	if($result_update_status)
					  	{
							  $status_flag = 1;								
					  	}	
					  	else
					  	{
							  $status_flag = 0;
					 	}												
					}
				}				
			}
		}
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
/*Code By Tariq -16-09-2016*/
if((isset($obj->change_filt_slug)) == "1" && isset($obj->change_filt_slug))
{
	$filt_id				= mysqli_real_escape_string($db_con,$obj->filt_id);
	$filt_slug			= mysqli_real_escape_string($db_con,$obj->filt_slug);
	if($filt_id != "" && $filt_slug != "")
	{
		$sql_update_slug 	= " UPDATE `tbl_filters` SET `filt_slug`= '".$filt_slug."',`filt_modified`= '".$datetime."',";
		$sql_update_slug 	.= " `filt_modifiedby`= '".$uid."' WHERE `filt_id` = '".$filt_id."' ";
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
 /*Code End By Tariq -16-09-2016*/
if((isset($obj->change_sort_order)) == "1" && isset($obj->change_sort_order))
{
	$cat_id					= mysqli_real_escape_string($db_con,$obj->cat_id);
	$new_order				= mysqli_real_escape_string($db_con,$obj->new_order);
	$response_array 		= array();		
	
	$sql_check_self_order	= " SELECT * from tbl_filters WHERE filt_id LIKE '".$cat_id."' ";
	$result_check_self_order= mysqli_query($db_con,$sql_check_self_order) or die(mysqli_error($db_con));	
	$row_check_self_order	= mysqli_fetch_array($result_check_self_order);
	
	$self_parent			= $row_check_self_order['filt_type'];
	$self_order				= $row_check_self_order['filt_sort_order'];
	
	$sql_check_order 		= " SELECT * from tbl_filters WHERE filt_sort_order LIKE '".$new_order."' and filt_type like '".$self_parent."' ";
	$result_check_order		= mysqli_query($db_con,$sql_check_order) or die(mysqli_error($db_con));	
	$num_rows_check_order	= mysqli_num_rows($result_check_order);
	if($num_rows_check_order > 0)
	{
		$row_check_order 	= mysqli_fetch_array($result_check_order);
		$other_cat			= $row_check_order['filt_id'];		
		$other_parent		= $row_check_order['filt_type'];
		$other_order		= $row_check_order['filt_sort_order'];
		if(($self_parent == "parent" && $other_parent == "parent" )|| ($self_parent == $other_parent ))
		{
			if($other_order == $new_order)
			{
				$sql_update_sort1 		= " UPDATE `tbl_filters` SET `filt_sort_order`= '".$self_order."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$other_cat."' ";	
				$result_update_sort2 	= mysqli_query($db_con,$sql_update_sort1) or die(mysqli_error($db_con));			
				$sql_update_sort2 		= " UPDATE `tbl_filters` SET `filt_sort_order`= '".$new_order."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$cat_id."' ";
				$result_update_sort2 	= mysqli_query($db_con,$sql_update_sort2) or die(mysqli_error($db_con));	
				$response_array 		= array("Success"=>"Success","resp"=>"Sort Order exchanged Successfully.");
			}
			else
			{					
				$sql_update_sort 	= " UPDATE `tbl_filters` SET `filt_sort_order`= '".$new_order."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$cat_id."' ";
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
		$sql_update_sort =	" UPDATE `tbl_filters` SET `filt_sort_order`= '".$new_order."' ,`filt_modified` = '".$datetime."' ,`filt_modifiedby` = '".$uid."' WHERE `filt_id` like '".$cat_id."' ";
		$result_update_sort = mysqli_query($db_con,$sql_update_sort) or die(mysqli_error($db_con));	
		$response_array = array("Success"=>"Success","resp"=>"Sort Order Updated Successfully.");			
	}
	echo json_encode($response_array);	
}
if((isset($obj->delete_category)) == "1" && isset($obj->delete_category))
{
	$response_array = array();		
	$ar_cat_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_cat_id as $cat_id)	
	{
		$sql_check_parent 	= "Select * from tbl_filters where `filt_id` = '".$cat_id."' ";
		$result_check_parent = mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
		$row_check_parent = mysqli_fetch_array($result_check_parent);
		if(strcmp(trim($row_check_parent['filt_sub_child']),"parent") == 0)
		{
			$sql_all_sub_cat 		= " select * from tbl_filters where filt_type = '".$row_check_parent['filt_id']."' ";
			$result_all_sub_cat 	= mysqli_query($db_con,$sql_all_sub_cat) or die(mysqli_error($db_con));
			while($row_all_sub_cat 	= mysqli_fetch_array($result_all_sub_cat))
			{
				$sql_delete_cat		= " DELETE FROM `tbl_filters` WHERE `filt_id` = '".$row_all_sub_cat['filt_id']."' ";
				$result_delete_cat	= mysqli_query($db_con,$sql_delete_cat) or die(mysqli_error($db_con));					
				if($result_delete_cat)
				{
					$del_flag = 1;	
				}	
				else
				{
					$del_flag = 0;
				}							
			}
			$sql_delete_cat		= " DELETE FROM `tbl_filters` WHERE `filt_id` = '".$cat_id."' ";
			$result_delete_cat	= mysqli_query($db_con,$sql_delete_cat) or die(mysqli_error($db_con));
			if($result_delete_cat)
			{
				$del_flag = 1;
			}
			else
			{
				$del_flag = 0;
			}			
		}
		elseif(strcmp(trim($row_check_parent['filt_sub_child']),"child") == 0)
		{			
			$sql_all_sub_cat 		= " select * from tbl_filters where filt_sub_child = '".$row_check_parent['filt_id']."' ";
			$result_all_sub_cat 	= mysqli_query($db_con,$sql_all_sub_cat) or die(mysqli_error($db_con));
			while($row_all_sub_cat 	= mysqli_fetch_array($result_all_sub_cat))
			{
				$sql_delete_cat		= " DELETE FROM `tbl_filters` WHERE `filt_id` = '".$row_all_sub_cat['filt_id']."' ";
				$result_delete_cat	= mysqli_query($db_con,$sql_delete_cat) or die(mysqli_error($db_con));					
				if($result_delete_cat)
				{
					$del_flag = 1;	
				}	
				else
				{
					$del_flag = 0;
				}							
			}
			$sql_delete_cat		= " DELETE FROM `tbl_filters` WHERE `filt_id` = '".$cat_id."' ";
			$result_delete_cat	= mysqli_query($db_con,$sql_delete_cat) or die(mysqli_error($db_con));
			if($result_delete_cat)
			{
				$del_flag = 1;
			}
			else
			{
				$del_flag = 0;
			}					
		}		
		else
		{
			$sql_delete_cat		= " DELETE FROM `tbl_filters` WHERE `filt_id` = '".$cat_id."' ";
			$result_delete_cat	= mysqli_query($db_con,$sql_delete_cat) or die(mysqli_error($db_con));			
			if($result_delete_cat)
			{
				$del_flag = 1;	
			}
			else
			{
				$del_flag = 0;
			}			
		}	
	}
	if($del_flag == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");			
	}
	elseif($del_flag == 0)
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");
	}		
	echo json_encode($response_array);	
}
/* sub child list*/
if((isset($obj->get_child_list)) == "1" && isset($obj->get_child_list))
{
	$child_id 		= mysqli_real_escape_string($db_con,$obj->child_id);	
	if($child_id != "")
	{
		$data 				= "";
		$sql_get_child 		= " SELECT * FROM `tbl_filters` WHERE `filt_type` != 'parent' and `filt_sub_child` = 'child' and `filt_type`= '".$child_id."' ";
		$result_get_child 	= mysqli_query($db_con,$sql_get_child) or die(mysqli_error($db_con));
		$num_rows_get_child = mysqli_num_rows($result_get_child);
		if($num_rows_get_child == 0)
		{
			$data = '<option value="child">Child</option>';
		}
		else
		{
			$data .= '<option value="child">Child</option>';
			while($row_get_child = mysqli_fetch_array($result_get_child))
			{
				$data .= '<option value="'.$row_get_child['filt_id'].'">'.ucwords($row_get_child['filt_name']).'</option>';
			}
		}
		$response_array = array("Success"=>"Success","resp"=>$data);		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Child id empty");
	}
	echo json_encode($response_array);
}
/* sub child list*/
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
		$sql_load_data  .= " WHERE error_module_name='fliters'  ";
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
			$cat_data .= '<th>Fliter Name</th>';
			$cat_data .= '<th>Parent</th>';
			$cat_data .= '<th>Created</th>';
			$cat_data .= '<th>Created By</th>';
			$cat_data .= '<th>Modified</th>';
			$cat_data .= '<th>Modified By</th>';
			$cat_data .= '<th>Edit</th>';			
			$cat_data .= '<th>
							<div style="text-align:center">
								<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>
							</div>
						</th>';
          	$cat_data .= '</tr>';
      		$cat_data .= '</thead>';
      		$cat_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_cat_rec	= json_decode($row_load_data['error_data']);
				$er_cat_name	= $get_cat_rec->filt_name;
				$er_cat_type	= $get_cat_rec->filt_type;
				$cat_data 		.= '<tr>';				
				$cat_data 		.= '<td>'.++$start_offset.'</td>';				
				$cat_data 		.= '<td>';
				$sql_chk_name_already_exist	= "SELECT filt_name FROM tbl_filters WHERE filt_name='".$er_cat_name."'";
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
					$sql_chk_parent_already_exist	= "SELECT filt_name FROM tbl_filters WHERE filt_name='".$er_cat_type."'";
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
				$cat_data .= '<td style="text-align:center">';				
				$cat_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreFilters(this.id,\'error\');"></td>';
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