<?php
include("include/routines.php");
$json 	= file_get_contents('php://input');
$obj 	= json_decode($json);
$uid 	= $_SESSION['panel_user']['id'];
$utype	= $_SESSION['panel_user']['utype'];
$tbl_users_owner = $_SESSION['panel_user']['tbl_users_owner'];
//echo json_encode($utype);die;
/* Product Operations */
function getSubCatValue($cat_id, $userType,$exist_category)	// Parameters : Parent ID and userType [i.e. Add, Edit, View]
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
					if(!in_array($row_get_children_frm_cm['cat_id'],$exist_category)){ 
					$data .= '<option id="demo'.$row_get_children_frm_cm['cat_id'].'" value="'.$row_get_children_frm_cm['cat_id'].'">'.substr(ucwords($opt_value),0,-3).'</option>';
					}
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
	elseif($userType == 'error')
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
					 
					$data .= '<option id="demo'.$row_get_children_frm_cm['cat_id'].'" value="'.$row_get_children_frm_cm['cat_id'].'" ';
					if(in_array($row_get_children_frm_cm['cat_id'],$exist_category))
					{
					$data .=' selected ';	
					}
					$data .='>'.substr(ucwords($opt_value),0,-3).'</option>';
					
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
	else
	{
		return $data;	
	}
}

function insertError($error_module_name,$error_file,$error_status,$error_data_json,$prod_orgid)
{
	$uid 	= $_SESSION['panel_user']['id'];
	global $db_con, $datetime;
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
			
	$sql_insert_error_log	= " INSERT INTO `tbl_error_data`(`error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, ";
	$sql_insert_error_log	.= " `error_created`, `error_created_by`, `error_orgid`) VALUES ('".$error_id."', '".$error_module_name."', '".$error_file."',";
	$sql_insert_error_log	.= " '".$error_data_json."', '".$error_status."', '".$datetime."', '".$uid."', '".$prod_orgid."') ";
	$res_insert_error_log 	= mysqli_query($db_con, $sql_insert_error_log) or die(mysqli_error($db_con));	}
	
function addFilters($prodfilt_prodid,$prodfilt_filtid)
{
	global $uid;
	global $db_con, $datetime;
	global $obj;
	$filter 					= explode(":",$prodfilt_filtid);
	$prodfilt_filtid_parent		= $filter[0];
	$prodfilt_filtid_child		= $filter[1];
	$prodfilt_filtid_sub_child	= $filter[2];	
	$sql_add_filters 			= " INSERT INTO `tbl_product_filters`( `prodfilt_prodid`, `prodfilt_filtid_parent`,`prodfilt_filtid_child`,`prodfilt_filtid_sub_child`,`prodfilt_status`, `prodfilt_created`";
	$sql_add_filters 			.= " , `prodfilt_created_by`) VALUES ('".$prodfilt_prodid."','".$prodfilt_filtid_parent."','".$prodfilt_filtid_child."','".$prodfilt_filtid_sub_child."',1,'".$datetime."','".$uid."')";
	$result_add_filters 		= mysqli_query($db_con,$sql_add_filters) or die(mysqli_error($db_con));
	if($result_add_filters)
	{
		return true;
	}
	else
	{
		return false;
	}}
	
function updateFilters($prodfilt_prodid,$prodfilt_filtid,$prodfilt_status)
{
	global $uid;
	global $db_con, $datetime;
	global $obj;
	$filter 					= explode(":",$prodfilt_filtid);
	$prodfilt_filtid_parent		= $filter[0];
	$prodfilt_filtid_child		= $filter[1];		
	$prodfilt_filtid_sub_child	= $filter[2];			
	$sql_update_filters 		= " UPDATE `tbl_product_filters` SET `prodfilt_status`='".$prodfilt_status."',`prodfilt_modified`='".$datetime."',";
	$sql_update_filters 		.= " `prodfilt_modified_by`='".$uid."' WHERE `prodfilt_prodid` = '".$prodfilt_prodid."' and ";
	$sql_update_filters 		.= " `prodfilt_filtid_parent` = '".$prodfilt_filtid_parent."' and `prodfilt_filtid_child` = '".$prodfilt_filtid_child."' and `prodfilt_filtid_sub_child` = '".$prodfilt_filtid_sub_child."' ";
	$result_update_filters 		= mysqli_query($db_con,$sql_update_filters) or die(mysqli_error($db_con));
	if($result_update_filters)
	{
		return true;
	}
	else
	{
		return false;
	}
}
	
function addLevels($prodlevel_prodid,$prodlevel_levelid)
{
	global $uid;
	global $db_con, $datetime;
	global $obj;
	$level 						= explode(":",$prodlevel_levelid);
	$prodlevel_levelid_parent	= $level[0];
	$prodlevel_levelid_child	= $level[1];
	$sql_add_levels 	= " INSERT INTO `tbl_product_levels`( `prodlevel_prodid`, `prodlevel_levelid_parent`, `prodlevel_levelid_child`, `prodlevel_status`, `prodlevel_created`";
	$sql_add_levels 	.= " , `prodlevel_created_by`) VALUES ('".$prodlevel_prodid."','".$prodlevel_levelid_parent."','".$prodlevel_levelid_child."',1,'".$datetime."','".$uid."')";
	$result_add_levels = mysqli_query($db_con,$sql_add_levels) or die(mysqli_error($db_con));
	if($result_add_levels)
	{
		return true;
	}
	else
	{
		return false;
	}}
	
function updateLevels($prodlevel_prodid,$prodlevel_levelid,$prodlevel_status)
{
	global $uid;
	global $db_con, $datetime;
	global $obj;
	$level = explode(":",$prodlevel_levelid);
	$prodlevel_levelid_parent	= $level[0];
	$prodlevel_levelid_child	= $level[1];	
	$sql_update_levels = " UPDATE `tbl_product_levels` SET `prodlevel_status`='".$prodlevel_status."',`prodlevel_modified`='".$datetime."',";
	$sql_update_levels .= " `prodlevel_modified_by`='".$uid."' WHERE `prodlevel_prodid` = '".$prodlevel_prodid."' and `prodlevel_levelid_parent` = '".$prodlevel_levelid_parent."' and `prodlevel_levelid_child` = '".$prodlevel_levelid_child."' ";
	$result_update_levels = mysqli_query($db_con,$sql_update_levels) or die(mysqli_error($db_con));
	if($result_update_levels)
	{
		return true;
	}
	else
	{
		return false;
	}}
	
function addCategory($prod_id,$cat_id,$prod_status,$default_status)
{
	global $uid;
	global $db_con, $datetime;
	global $obj;
	
	$sql_add_cat 	= " INSERT INTO `tbl_product_cats`( `prodcat_prodid`, `prodcat_catid`, `prodcat_default`, `prodcat_prod_status`, `prodcat_created`";
	$sql_add_cat	.= " , `prodcat_created_by`) VALUES ('".$prod_id."','".$cat_id."','".$default_status."','".$prod_status."','".$datetime."','".$uid."')";
	$result_add_cat = mysqli_query($db_con,$sql_add_cat) or die(mysqli_error($db_con));
	if($result_add_cat)
	{
		return true;
	}
	else
	{
		return false;
	}
}
	

function insertProduct($prod_model_number,$prod_name,$prod_title,$prod_description,$prod_orgid,$prod_brandid,$prod_catid,$prod_google_product_category,$prod_min_quantity,$prod_quantity,$prod_max_quantity,$prod_content,$prod_list_price,$prod_recommended_price,$prod_meta_description,$prod_meta_tags,$prod_meta_title,$prod_status,$batch_specification,$error_specification,$batch_filters,$batch_levels)
{
	//echo json_encode($prod_orgid);exit();
	global $uid;
	global $db_con, $datetime;
	global $obj;
	$sql_check_product 		= " SELECT * from tbl_products_master where prod_model_number like '".$prod_model_number."' ";
	$result_check_product 	= mysqli_query($db_con,$sql_check_product) or die(mysqli_error($db_con));
	$num_rows_check_product = mysqli_num_rows($result_check_product);
	if($num_rows_check_product == 0)
	{
		$sql_last_rec 		= "Select * from tbl_products_master order by prod_id desc LIMIT 0,1";
		$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);

		if($num_rows_last_rec == 0)
		{
			$prod_id 		= 1;				
		}
		else
		{
			$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
			$prod_id 		= $row_last_rec['prod_id']+1;
		}
 
		$prod_slug =getSlug($prod_name);
		
		$sql_insert_product 	= "INSERT INTO `tbl_products_master`(prod_id,`prod_model_number`, `prod_name`,`prod_title`, ";
		$sql_insert_product 	.= " `prod_description`, `prod_orgid`, `prod_brandid`,`prod_google_product_category`,";
		$sql_insert_product 	.= "   `prod_content`,`prod_min_quantity`, `prod_quantity`, ";
		$sql_insert_product 	.= " `prod_max_quantity`,`prod_list_price`, `prod_recommended_price`,`prod_org_price`, `prod_meta_tags`,";
		$sql_insert_product 	.= " `prod_meta_description`, `prod_meta_title`, `prod_created_by`, `prod_created`,  `prod_status`,";
		$sql_insert_product 	.= " `prod_slug`) ";
			//echo json_encode($prod_orgid);exit();
		//$prod_id="7894562589630";
		$sql_insert_product 	.= " VALUES (".$prod_id.",'".$prod_model_number."','".strtolower($prod_name)."','".$prod_title."', ";
		$sql_insert_product 	.= " '".$prod_description."','".$prod_orgid."','".$prod_brandid."','".$prod_google_product_category."'";
		$sql_insert_product 	.= " ,'".$prod_content."','".$prod_min_quantity."','".$prod_quantity."', ";
		$sql_insert_product 	.= " '".$prod_max_quantity."','".$prod_list_price."','".$prod_recommended_price."',";
		$sql_insert_product 	.= " '".$prod_recommended_price."','".$prod_meta_tags."','".$prod_meta_description."','".$prod_meta_title."', ";
		$sql_insert_product 	.= " '".$uid."','".$datetime."','".$prod_status."','".$prod_slug."') ";
		
		$result_insert_product = mysqli_query($db_con,$sql_insert_product) or die(mysqli_error($db_con));
		
		
		
		//$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully");		
		//echo json_encode($response_array);
		if($result_insert_product)
		{
			
			if($prod_catid != "")
			{   $cats= explode(',',$prod_catid);
			    
				for($i=0;$i<sizeof($cats);$i++)
				{ $default_status=0;  
				 if($i==0)
				   { 
					   $default_status=1; 
				   }
				   addCategory($prod_id,$cats[$i],$prod_status,$default_status);
	
				}
			}
			if($batch_filters != "")
			{
				foreach($batch_filters as $prodfilt_filtid)
				{
					addFilters($prod_id,$prodfilt_filtid);
				}
			}
			if($batch_levels != "")
			{
				foreach($batch_levels as $prodlevel_levelid)
				{
					addLevels($prod_id,$prodlevel_levelid);
				}
			}
			
			if($prod_catid != "")
			{   $i=1;
			    
				foreach($prod_catid as $cat_id)
				{ $default_status=0;   
				   if($i==1)
				   {
					   $default_status=1; 
				   }
					addCategory($prod_id,$cat_id,$prod_status,$default_status);
					$i++;
				}
			}
			
			
			if($batch_specification != "")
			{
				foreach($batch_specification as $prodspec_id)
				{
					$specification 			= explode(":",$prodspec_id);
					$prod_spec_specid		= $specification[0];
					$prod_spec_value		= $specification[1];
					insertProductSpecification($prod_id,$prod_spec_specid,$prod_spec_value,1);
				}
			}
			if($error_specification != "")
			{							
				foreach($error_specification as $errorspec_id)
				{
					$error_spec_data	= explode(":",$errorspec_id);
					$spec_name			= trim($error_spec_data[0]);
					$spec_value			= trim($error_spec_data[1]);
					$error_data 		= array("prod_id"=>$prod_id,"spec_name"=>$spec_name,"spec_value"=>$spec_value);
					$error_module_name	= "Specification";
					$error_file			= "";//$inputFileName;
					$error_status		= '1';
					$error_data_json	= json_encode($error_data);
					if($spec_name != "" && $spec_value != "")
					{
						insertError($error_module_name,$error_file,$error_status,$error_data_json);
					}
				}
			}
			
			
			if(isset($obj->error_id) && (isset($obj->insert_product)) != "")			
			{
				$response_array = errorDataDelete($obj->error_id);
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
		$response_array = array("Success"=>"fail","resp"=>'Model Number <b> "'.$prod_model_number.'" </b> Already Exist');
	}
	return $response_array;
}
	
function exportToXlsx($sql_load_products)
{
	//$response_array	= array("Success"=>"fail", "resp"=>"No Data");	
	//return $response_array;exit();
	include_once("xlsxwriter.class.php");
	global 			$db_con;
	$header			= array();
	$data1 			= array();
	$main_data		= array();
	
	$result_load_products	= mysqli_query($db_con,$sql_load_products) or die(mysqli_error($db_con));
	$num_rows_load_products	= mysqli_num_rows($result_load_products);
	
	if($num_rows_load_products != 0)
	{
		$header = array(
			'Product Id'=>'integer',		
			'Google Product Category'=>'string',						
			'Model Number'=>'string',			
			'Name'=>'string',			
			'Title'=>'string',
			'url/slug'=>'string',			
			'Organisation'=>'string',
			'Brand'=>'string',
			'Category'=>'string',
			'Description'=>'string',			
			'Quantity'=>'string',
			'Content'=>'string',
			'Min quantity'=>'string',
			'Max quantity'=>'string',
			'List Price'=>'string',						
			'Recommended Price'=>'string',
			'Vendor Price'=>'string',
			'Meta Description'=>'string',			
			'Meta Tags'=>'string',						
			'Meta Title'=>'string',
			'Status'=>'string',
			'Uploaded/Created By'=>'string',
			'Uploaded/Created'=>'string',			
			'Last Modified By'=>'string',
			'Last Modified'=>'string',
			'Specification'=>'string',
			'Filters'=>'string',
			'Levels'=>'string',
		);
		while($row_load_products = mysqli_fetch_array($result_load_products))
		{	
			$prod_id  							= $row_load_products['prod_id'];
			$prod_google_product_category		= trim($row_load_products['prod_google_product_category']);
			if($prod_google_product_category 	== "")
			{
				$prod_google_product_category	= "Google category";					
			}
			$prod_model_number 		= $row_load_products['prod_model_number'];
			$prod_name 				= $row_load_products['prod_name'];
			$prod_title 			= $row_load_products['prod_title'];
			$prod_slug 				= $row_load_products['prod_slug'];			
			$prod_desc 				= strip_tags($row_load_products['prod_description']);
			$prod_org_name 			= $row_load_products['org_name'];
			$prod_brand_name 		= $row_load_products['brand_name'];
			
			$sql_cat_data 	= " SELECT * FROM `tbl_product_cats` WHERE `prodcat_prodid` = '".$prod_id."' GROUP BY prodcat_catid ORDER BY `prodcat_default` DESC "; // this ind_id is error id from error table
			$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
			$num_rows           =mysqli_num_rows($result_cat_data);
			$cat_names=array(); 
		    while($row_cat_data		= mysqli_fetch_array($result_cat_data))
			{       
			       $sql_get_cat	= " SELECT * FROM `tbl_category` ";
			       $sql_get_cat	.= " WHERE `cat_id`=".$row_cat_data['prodcat_catid']." ";
				   $res_get_cat	= mysqli_query($db_con, $sql_get_cat) or die(mysqli_error($db_con));
			       $row_cat	= mysqli_fetch_array($res_get_cat);
				   array_push($cat_names,$row_cat['cat_name']);
				   
			}
			
			$prod_category_name 	= implode(',',$cat_names).';';
			
			
			
			$prod_quantity 			= $row_load_products['prod_quantity'];
			if(trim($row_load_products['prod_content']) != "")
			{
				$prod_content 		= strip_tags($row_load_products['prod_content']);				
			}
			else
			{
				$prod_content 		= "Data Not Available";
			}
			$prod_min_quantity 		= $row_load_products['prod_min_quantity'];
			$prod_max_quantity 		= $row_load_products['prod_max_quantity'];
			$prod_list_price 		= $row_load_products['prod_list_price'];
			$prod_recommended_price = $row_load_products['prod_recommended_price'];
			$prod_vendor_price 		= $row_load_products['prod_org_price'];
			if(trim($row_load_products['prod_meta_description']) != "")
			{
				$prod_meta_desc 	= strip_tags($row_load_products['prod_meta_description']);
			}
			else
			{
				$prod_meta_desc		= "Data Not Available";
			}

			if(trim($row_load_products['prod_meta_tags']) != "")
			{
				$prod_meta_tags 	= strip_tags($row_load_products['prod_meta_tags']);				
			}
			else
			{
				$prod_meta_tags 	= "Data Not Available";
			}

			if(trim($row_load_products['prod_meta_title']) != "")
			{
				$prod_meta_title 		= strip_tags($row_load_products['prod_meta_title']);				
			}
			else
			{
				$prod_meta_title 		= "Data Not Available";
			}
			if($row_load_products['prod_status'] == 1)
			{
				$prod_status 		= "Active";
			}
			elseif($row_load_products['prod_status'] == 0)
			{
				$prod_status 		= "In-Active";
			}						
			$prod_created_by 		= $row_load_products['created_name'];
			$prod_created 			= $row_load_products['prod_created'];
			$prod_modified_by		= $row_load_products['prod_modified_name'];
			$prod_modified			= $row_load_products['prod_modified'];
			
			$sql_get_prod_specification 	= " SELECT spec_name,prod_spec_value FROM `tbl_specifications_master` tsm INNER JOIN `tbl_products_specifications` tps ";
			$sql_get_prod_specification 	.= " ON tps.prod_spec_specid = tsm.spec_id where prod_spec_prodid = '".$prod_id."' and prod_spec_value != '' ";
			$result_get_prod_specification 	= mysqli_query($db_con,$sql_get_prod_specification) or die(mysqli_error($db_con));
			$num_rows_get_prod_specification= mysqli_num_rows($result_get_prod_specification);
			if($num_rows_get_prod_specification != 0)
			{
				$prod_specification		= "";
				while($rows_get_prod_specification = mysqli_fetch_array($result_get_prod_specification))
				{
					$prod_specification .= $rows_get_prod_specification['spec_name'].":".$rows_get_prod_specification['prod_spec_value']."|";
				}
			}
			else
			{
				$prod_specification		= "Data Not Available";
			}
			
			$sql_get_prod_filters_parent		= " SELECT distinct prodfilt_filtid_parent,filt_name FROM `tbl_filters` tf INNER JOIN  ";
			$sql_get_prod_filters_parent		.= " `tbl_product_filters` tpf ON tpf.prodfilt_filtid_parent = tf.`filt_id` where prodfilt_prodid = '".$prod_id."' ";
			$result_get_prod_filters_parent		= mysqli_query($db_con,$sql_get_prod_filters_parent) or die(mysqli_error($db_con));
			$num_rows_get_prod_filters_parent 	= mysqli_num_rows($result_get_prod_filters_parent);
			if($num_rows_get_prod_filters_parent != 0)
			{
				$prod_filters			= "";
				while($row_get_prod_filters_parent = mysqli_fetch_array($result_get_prod_filters_parent))
				{
					$prod_filters						.= $row_get_prod_filters_parent['filt_name']."::";
					$sql_get_prod_filters_child			= " SELECT distinct prodfilt_filtid_child,filt_name as child_filt_name FROM `tbl_filters` tf INNER JOIN  ";
					$sql_get_prod_filters_child			.= " `tbl_product_filters` tpf ON tpf.prodfilt_filtid_child = tf.`filt_id`";
					$sql_get_prod_filters_child			.= " where prodfilt_prodid = '".$prod_id."' and prodfilt_filtid_parent = '".$row_get_prod_filters_parent['prodfilt_filtid_parent']."' ";
					$result_get_prod_filters_child		= mysqli_query($db_con,$sql_get_prod_filters_child) or die(mysqli_error($db_con));
					$num_rows_get_prod_filters_child 	= mysqli_num_rows($result_get_prod_filters_child);
					if($num_rows_get_prod_filters_child != 0)
					{
						while($row_get_prod_filters_child = mysqli_fetch_array($result_get_prod_filters_child))
						{
							$prod_filters							.= $row_get_prod_filters_child['child_filt_name'].":";
							$sql_get_prod_filters_sub_child			= " SELECT distinct prodfilt_filtid_sub_child,filt_name as parent_filt_name FROM `tbl_filters` tf INNER JOIN  ";
							$sql_get_prod_filters_sub_child			.= " `tbl_product_filters` tpf ON tpf.prodfilt_filtid_sub_child = tf.`filt_id` where prodfilt_filtid_parent = '".$row_get_prod_filters_parent['prodfilt_filtid_parent']."'";
							$sql_get_prod_filters_sub_child			.= "  and prodfilt_prodid = '".$prod_id."' and prodfilt_filtid_child = '".$row_get_prod_filters_child['prodfilt_filtid_child']."' ";
							$result_get_prod_filters_sub_child		= mysqli_query($db_con,$sql_get_prod_filters_sub_child) or die(mysqli_error($db_con));
							$num_rows_get_prod_filters_sub_child 	= mysqli_num_rows($result_get_prod_filters_sub_child);
							$row_fil_arr =array();
							if($num_rows_get_prod_filters_child != 0)
							{
								while($row_get_prod_filters_sub_child = mysqli_fetch_array($result_get_prod_filters_sub_child))
								{
									array_push($row_fil_arr,$row_get_prod_filters_sub_child['parent_filt_name']);
									//$prod_filters				.= $row_get_prod_filters_sub_child['parent_filt_name'].",";									
								}
								$prod_filters					.= implode(',',$row_fil_arr).";";
							}							
						}
					}
					$prod_filters					.= 	"|";						
				}
			}
			else
			{
				$prod_filters			= "Data Not Available";				
			}
			/* filters end */
			/*levels start*/
			$sql_get_prod_levels_parent		= " SELECT distinct prodlevel_levelid_parent,cat_name FROM `tbl_level` tf INNER JOIN  ";
			$sql_get_prod_levels_parent		.= " `tbl_product_levels` tpf ON tpf.prodlevel_levelid_parent = tf.`cat_id` where prodlevel_prodid = '".$prod_id."' ";
			$result_get_prod_levels_parent		= mysqli_query($db_con,$sql_get_prod_levels_parent) or die(mysqli_error($db_con));
			$num_rows_get_prod_levels_parent 	= mysqli_num_rows($result_get_prod_levels_parent);
			if($num_rows_get_prod_levels_parent != 0)
			{
				$prod_levels			= "";
				while($row_get_prod_levels_parent = mysqli_fetch_array($result_get_prod_levels_parent))
				{
					$prod_levels						.= $row_get_prod_levels_parent['cat_name'].":";
					$sql_get_prod_levels_child			= " SELECT distinct prodlevel_levelid_child,cat_name as child_level_name FROM `tbl_level` tf INNER JOIN  ";
					$sql_get_prod_levels_child			.= " `tbl_product_levels` tpf ON tpf.prodlevel_levelid_child = tf.`cat_id`";
					$sql_get_prod_levels_child			.= " where prodlevel_prodid = '".$prod_id."' and prodlevel_levelid_parent = '".$row_get_prod_levels_parent['prodlevel_levelid_parent']."' ";
					$result_get_prod_levels_child		= mysqli_query($db_con,$sql_get_prod_levels_child) or die(mysqli_error($db_con));
					$num_rows_get_prod_levels_child 	= mysqli_num_rows($result_get_prod_levels_child);
					if($num_rows_get_prod_levels_child != 0)
					{    $a=array();
						while($row_get_prod_levels_child = mysqli_fetch_array($result_get_prod_levels_child))
						{
							    array_push($a,$row_get_prod_levels_child['child_level_name']);
								//$prod_levels				.= $row_get_prod_levels_child['child_level_name'].",";	
								//$prod_levels					.= 	",";
														
						}
						$prod_levels .=implode(',',$a);
					}
					$prod_levels					.= 	";";						
				}//echo json_encode($prod_levels);exit();	
			}
			else
			{
				$prod_levels			= "Data Not Available";				
			}
			/*levels end*/
			
			//$prod_levels			= "";
			$data1 	= array($prod_id,$prod_google_product_category,$prod_model_number,$prod_name,$prod_title,$prod_slug,$prod_org_name,$prod_brand_name,$prod_category_name,$prod_desc,$prod_quantity,$prod_content,$prod_min_quantity,$prod_max_quantity,$prod_list_price,$prod_recommended_price,$prod_vendor_price,$prod_meta_desc,$prod_meta_tags,$prod_meta_title,$prod_status,$prod_created_by,$prod_created,$prod_modified_by,$prod_modified,$prod_specification,$prod_filters,$prod_levels);
			array_push($main_data, $data1);
		}
		$writer 			= new XLSXWriter();
		$writer->setAuthor('Prem Ambodkar');
		$writer->writeSheet($main_data,'Sheet1',$header);
		$timestamp			= date('mdYhis', time());
		if(!file_exists("excelDownload/product".$timestamp))
		{
			mkdir("excelDownload/product".$timestamp);
		}
		$writer->writeToFile('excelDownload/product'.$timestamp.'/product_sheet_'.$timestamp.'.xlsx');
		
		$response_array	= array("Success"=>"Success", "resp"=>'excelDownload/product'.$timestamp.'/product_sheet_'.$timestamp.'.xlsx');
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"No Data");	
	}
	return $response_array;}

/* Insert Product from add product form */
if((isset($obj->insert_product)) == "1" && isset($obj->insert_product))
{
	$prod_model_number				= mysqli_real_escape_string($db_con,$obj->prod_model_number);
	$prod_name						= htmlspecialchars(strtolower(mysqli_real_escape_string($db_con,$obj->prod_name)),ENT_HTML5);
	$prod_description				= mysqli_real_escape_string($db_con,$obj->prod_description);
	$prod_orgid						= mysqli_real_escape_string($db_con,$obj->prod_orgid);
	$prod_brandid					= mysqli_real_escape_string($db_con,$obj->prod_brandid);
	$prod_catid						= mysqli_real_escape_string($db_con,$obj->prod_catid);
	//$prod_subcatid					= mysqli_real_escape_string($db_con,$obj->prod_subcatid);
	$prod_google_product_category	= mysqli_real_escape_string($db_con,$obj->prod_google_product_category);
	//$prod_google_product_category 	= htmlspecialchars(str_replace("'","",mysqli_real_escape_string($db_con,$obj->prod_google_product_category)),ENT_HTML5);
	$prod_content					= mysqli_real_escape_string($db_con,$obj->prod_content);
	$prod_min_quantity				= mysqli_real_escape_string($db_con,$obj->prod_min_quantity);
	$prod_max_quantity				= mysqli_real_escape_string($db_con,$obj->prod_max_quantity);
	$prod_quantity					= mysqli_real_escape_string($db_con,$obj->prod_quantity);
	$prod_list_price				= mysqli_real_escape_string($db_con,$obj->prod_list_price);
	$prod_recommended_price			= mysqli_real_escape_string($db_con,$obj->prod_recommended_price);
	$prod_meta_tags					= mysqli_real_escape_string($db_con,$obj->prod_meta_tags);
	$prod_meta_description			= mysqli_real_escape_string($db_con,$obj->prod_meta_description);
	$prod_meta_title				= mysqli_real_escape_string($db_con,$obj->prod_meta_title);
	$prod_status					= mysqli_real_escape_string($db_con,$obj->prod_status);
	$batch_filters 					= $obj->batch_filters;	
	$batch_levels 					= $obj->batch_levels;
	$prod_title						= $prod_name;
	$prod_images					= '';
	$response_array = array();
	//echo json_encode($prod_min_quantity);exit();
	/*done by monika - start*/
	if($prod_min_quantity > $prod_max_quantity )
	{
					
		$response_array = array("Success"=>"Fail","resp"=>"Maximum quantity should be greater than Minimum quantity.");		
		echo json_encode($response_array);			
		exit();			
	}																		
	if($prod_recommended_price > $prod_list_price)
	{
		$response_array = array("Success"=>"Fail","resp"=>"List price should be greater than recommended price.");		
		echo json_encode($response_array);			
		exit();									
	}	
	/*done by monika - end*/
	if($prod_model_number != "" && $prod_name != "" && $prod_orgid != "" && $prod_catid != "" && $prod_status != "")
	{
		$batch_specification = "";
		$error_specification = "";
		$response_array = insertProduct($prod_model_number,$prod_name,$prod_title,$prod_description,$prod_orgid,$prod_brandid,$prod_catid,$prod_google_product_category,$prod_min_quantity,$prod_quantity,$prod_max_quantity,$prod_content,$prod_list_price,$prod_recommended_price,$prod_meta_description,$prod_meta_tags,$prod_meta_title,$prod_status,$batch_specification,$error_specification,$batch_filters,$batch_levels);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}

	echo json_encode($response_array);		}
/* Inser Product from add product form */
/* Load Add,edit view details and error update forms*/
if((isset($obj->load_add_prod_part)) == "1" && isset($obj->load_add_prod_part))
{	
	$prod_id 	= $obj->prod_id;
	$req_type 	= $obj->req_type;
	$response_array = array();
	
	if($req_type != "")
	{
		if($prod_id != "" && $req_type == "error")
		{   $error_filter_parent=array();
			$error_filter_chlid=array();
			$error_filter_subchild=array();
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$prod_id."' "; // this ind_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_prod_data		= json_decode($row_error_data['error_data']);
		}
		else if(($prod_id != "" && $req_type == "edit") || ($prod_id != "" && $req_type == "view"))
		{
			$sql_prod_data 				= "Select * from tbl_products_master where prod_id = '".$prod_id."' ";
			$result_prod_data 			= mysqli_query($db_con,$sql_prod_data) or die(mysqli_error($db_con));
			$row_prod_data				= mysqli_fetch_array($result_prod_data);	
			$row_get_filters_parent		= array();
			$row_get_filters_child		= array();
			$row_get_filters_sub_child	= array();
			$sql_get_filters			= "SELECT * FROM `tbl_product_filters` WHERE `prodfilt_prodid` = '".$prod_id."' and prodfilt_status = '1' ";
			$result_get_filters			= mysqli_query($db_con,$sql_get_filters) or die(mysqli_error($db_con));
			while($dump_row_get_filters	= mysqli_fetch_array($result_get_filters))
			{
				array_push($row_get_filters_parent,$dump_row_get_filters['prodfilt_filtid_parent']);
				array_push($row_get_filters_child,$dump_row_get_filters['prodfilt_filtid_child']);				
				array_push($row_get_filters_sub_child,$dump_row_get_filters['prodfilt_filtid_sub_child']);
			}
			$row_get_levels_parent		= array();
			$row_get_levels_child		= array();			
			$sql_get_levels				= "SELECT * FROM `tbl_product_levels` WHERE `prodlevel_prodid` = '".$prod_id."' and prodlevel_status = '1' ";
			$result_get_levels			= mysqli_query($db_con,$sql_get_levels) or die(mysqli_error($db_con));
			while($dump_row_get_levels	= mysqli_fetch_array($result_get_levels))
			{
				array_push($row_get_levels_parent,$dump_row_get_levels['prodlevel_levelid_parent']);
				array_push($row_get_levels_child,$dump_row_get_levels['prodlevel_levelid_child']);				
			}				
		}
		$data = '';
		
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="prod_id" value="'.$row_prod_data['prod_id'].'">';
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$prod_id.'">';
		}	 	
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Product Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="prod_name" name="prod_name" class="input-large keyup-char" data-rule-required="true" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_prod_data['prod_name']).'"';
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_prod_data->prod_name).'"';
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_prod_data['prod_name']).'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Model Number<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="prod_model_number" name="prod_model_number" class="input-large keyup-char" data-rule-required="true" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_prod_data['prod_model_number'].'"'; 
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_prod_data->prod_model_number.'"'; 			
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_prod_data['prod_model_number'].'" disabled'; 				
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';			
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Description<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="prod_description" name="prod_description" >';
		if($prod_id != "" && $req_type == "error")
		{
			$data .= $row_prod_data->prod_description;
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_description']) != "")
			{
				$data 	.= ucwords($row_prod_data['prod_description']);
			}
			else
			{
				$data 	.= '<span style="color:#E63A3A">Data Not Available!!!</span>';
			}									
		}		
		else
		{
			$data .= $row_prod_data['prod_description'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';						
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Organisation/Company<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		//$data .= '<br>Prathamesh : '.$utype.'<br>';
		if($req_type != "view" && $utype == "1")
		{
			$data .= '<select name="prod_orgid" id="prod_orgid" class="select2-me input-large" data-rule-required="true">';
			$data .= '<option value="">Select Organisation/Company</option>';
			$sql_get_org = "SELECT distinct org_id,org_name FROM `tbl_oraganisation_master` where org_status = '1' ";
			$result_get_org = mysqli_query($db_con,$sql_get_org) or die(mysqli_error($db_con));														
			while($row_get_org = mysqli_fetch_array($result_get_org))
			{	
				$data .= '<option value="'.$row_get_org['org_id'].'"';		
				if($req_type == "edit")												
				{
					if($row_get_org['org_id'] == $row_prod_data['prod_orgid'])
					{
						$data .= 'selected';
					}
				}
				elseif($req_type == "error")
				{
					if($row_get_org['org_name'] == $row_prod_data->prod_orgid)
					{
						$data .= 'selected';
					}					
				}
				$data .= '>'.ucwords($row_get_org['org_name']).'</option>';
			}			
			$data .= '</select>';			
		}
		elseif($req_type != "view" && $utype == "14")
		{
			$data .= '<select name="prod_orgid" id="prod_orgid" class="select2-me input-large" data-rule-required="true">';
			$sql_get_org2 = "SELECT * FROM `tbl_oraganisation_master` where org_id = '".$tbl_users_owner."' ";
			$result_get_org2 = mysqli_query($db_con,$sql_get_org2) or die(mysqli_error($db_con));														
			while($row_get_org2 = mysqli_fetch_array($result_get_org2))
			{	
			$data 			.= '<option value="'.$row_get_org2['org_id'].'"';	
			$data .= '>'.ucwords($row_get_org2['org_name']).'</option>';	
			}	
			$data .= '</select>';
		}
		elseif($req_type == "view")		
		{
			$sql_get_org = "SELECT org_id,org_name FROM `tbl_oraganisation_master` where org_id = '".$row_prod_data['prod_orgid']."' ";
			$result_get_org = mysqli_query($db_con,$sql_get_org) or die(mysqli_error($db_con));														
			$row_get_org = mysqli_fetch_array($result_get_org);
			if(trim($row_get_org['org_name']) != "")
			{
				$data 	.= ucwords($row_get_org['org_name']);
			}
			else
			{
				$data 	.= '<span style="color:#E63A3A">Data Not Available!!!</span>';
			}						
		}
		
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Brand<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .= '<select name="prod_brandid" id="prod_brandid" class="select2-me input-large" data-rule-required="true">';
			$data .= '<option value="">Select Brand</option>';			
			$sql_get_branch 	= " SELECT 	brand_id,brand_name FROM `tbl_brands_master` where brand_status = '1' ";
			$result_get_branch 	= mysqli_query($db_con,$sql_get_branch) or die(mysqli_error($db_con));														
			while($row_get_branch 	= mysqli_fetch_array($result_get_branch))
			{
				$data 			.= '<option value="'.$row_get_branch['brand_id'].'"';
				if($req_type == "error")
				{
					if($row_get_branch['brand_name'] == $row_prod_data->prod_brandid)
					{
						$data .= 'selected';
					}					
				}
				elseif($req_type == "edit")												
				{
					if($row_get_branch['brand_id'] == $row_prod_data['prod_brandid'])
					{
						$data .= 'selected';
					}
				}
				$data 			.= '>'.ucwords($row_get_branch['brand_name']).'</option>';
			}			
			$data 				.= '</select>';		
		}
		elseif($req_type == "view")		
		{
			$sql_get_branch 	= "SELECT brand_id,brand_name FROM `tbl_brands_master` where brand_status = '1' and brand_id = '".$row_prod_data['prod_brandid']."' ";
			$result_get_branch 	= mysqli_query($db_con,$sql_get_branch) or die(mysqli_error($db_con));														
			$row_get_branch 	= mysqli_fetch_array($result_get_branch);
			if(trim($row_get_branch['brand_name']) != "")
			{
				$data 	.= ucwords($row_get_branch['brand_name']);
			}
			else
			{
				$data 	.= '<span style="color:#E63A3A">Data Not Available!!!</span>';
			}			
		}				
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group ">';
		$data .= '<label for="tasktitel" class="control-label">Category<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<br><div class="controls"><br>';
		///////////////////////////////////////////////start done by satush 1 december 2016///////////////////////////
		if($req_type == "add")
		{
			$data 				.= '<br><select style=" width: 95%;margin-top:10px;" name="prdod_catid[]" multiple="multiple"  id="prod_catid" onChange="console.log($(this).children(":selected").length)" placeholder="Type Category" class="input-block-level "  data-rule-required="true">';
			$data 				.= '<option value="">Select Category</option>';
			
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
					$data .= '<option  id="demo'.$row_chk_isParent['cat_id'].'" value="'.$row_chk_isParent['cat_id'].'">'.ucwords($row_chk_isParent['cat_name']).'</option>';
					$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'add');
				}
			}
		    $data 		.= '</select>';		
		}
		
		if($req_type == "edit")
		{  
		
		    $exist_category =array();
		    $sql_cat_data 	= " SELECT * FROM `tbl_product_cats` WHERE `prodcat_prodid` = '".$prod_id."' "; // this ind_id is error id from error table
			$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
		    while($row_cat_data		= mysqli_fetch_array($result_cat_data))
			{
		   		array_push($exist_category,$row_cat_data['prodcat_catid']);
				
			}
		 
			
		    $data 				.= '<br><select style=" width: 95%;margin-top:10px;" name="prod_catid[]" multiple="multiple"  id="prod_catid" onChange="console.log($(this).children(":selected").length)" placeholder="Type Category" class="input-block-level " >';
			$data 				.= '<option value="">Select Category</option>';
			
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
				    if(!in_array($row_chk_isParent['cat_id'],$exist_category)){ 
					$data .= '<option  id="demo'.$row_chk_isParent['cat_id'].'" value="'.$row_chk_isParent['cat_id'].'">'.ucwords($row_chk_isParent['cat_name']).'</option>';
					}
					
					$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'add',$exist_category);
				}
			}
		    $data 		.= '</select><div style="padding:10px">';	
			
			$data .= '<table id="tbl_user" class="table table-bordered dataTable " style="height: 10px;overflow-y: scroll;width:100%;text-align:center">';
      		$data .= '<thead>';
    	  	$data .= '<tr>';
			
         	$data .= '<th style="text-align:center">Delete</th>';
			$data .= '<th style="text-align:center">Category  Name</th>';
			$data .= '<th style="text-align:center">Primary</th>';
			
			$data .= '</tr>';
      		$data .= '</thead>';
			$data .= '<tbody>';
			$data .='<tr>';
			$data .='<td>';
			$data .='</td>';
			$data .='<td>';
			$data .='<span style="">None</span>&nbsp;&nbsp;';
				    
			$data .='</td>';
			
			$data .='<td>';
			$data .='<input type="radio" name="primary_cat" value="0"';
			        $data .="/>";
			$data .='</td>';
			$dat .='</tr>';
			$sql_cat_data 	= " SELECT * FROM `tbl_product_cats` WHERE `prodcat_prodid` = '".$prod_id."' GROUP BY prodcat_catid ORDER BY `prodcat_default` DESC "; // this ind_id is error id from error table
			$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
			$num_rows           =mysqli_num_rows($result_cat_data); 
		    while($row_cat_data		= mysqli_fetch_array($result_cat_data))
			{       
			        
					
			     
			       $sql_get_cat	= " SELECT * FROM `tbl_category` ";
			       $sql_get_cat	.= " WHERE `cat_id`=".$row_cat_data['prodcat_catid']." ";
				   $res_get_cat	= mysqli_query($db_con, $sql_get_cat) or die(mysqli_error($db_con));
			       $row_cat	= mysqli_fetch_array($res_get_cat);
				   
				   $data .='<tr>';
				   $data  .='<td>';
				   $data  .='<input value="'.$row_cat_data['prodcat_catid'].'" id="delet_cat" class="delete_cat" name="delete_cat" title="Check to Remove Category" class="   " type="checkbox">';
				   $data  .='</td>';
				   $data  .='<td>';
				   $data .='<span style="">'.$row_cat['cat_name'].'</span>&nbsp;&nbsp;';
				   $data  .='</td>';
				   $data  .='<td>';
				   $data .='<input title="Click to Make Primary" type="radio" id="primary_cat" name="primary_cat" value="'.$row_cat_data['prodcat_catid'].'"';
				   
				   if($row_cat_data['prodcat_default'] == 1){
					   
					$data .=' checked ';   
				   }
				   $data .="/>";
				  $data  .='</td>';
				  $data .='</tr>';
			       
				  
				
			}
			$data .= '</tbody>';
			$data .= '</table>';
		
			$data .='<input type="hidden" name="cat_list_count" id="cat_list_count" value="'.$num_rows.'">';
			$data .='</div>';
			
			  	
		}
		
		elseif( $req_type == "error")
		{  
		
		
		 
		    $exist_category =$row_prod_data->prod_catid;
			
		    $data 				.= '<br><select style=" width: 95%;margin-top:10px;" name="prod_catid[]" multiple="multiple"  id="prod_catid" onChange="console.log($(this).children(":selected").length)" placeholder="Type Category" class="input-block-level " >';
			$data 				.= '<option value="">Select Category</option>';
			
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
				    
					$data .= '<option  id="demo'.$row_chk_isParent['cat_id'].'" value="'.$row_chk_isParent['cat_id'].'"';
					if(in_array($row_chk_isParent['cat_id'],$exist_category)){ 
					$data .=' selected ';
					}
					$data .='>'.ucwords($row_chk_isParent['cat_name']).'</option>';
					
					
					$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'error',$exist_category);
				}
			}
		    $data 		.= '</select><div style="padding:10px">';	
			
		
		
			$data .='<input type="hidden" name="cat_list_count" id="cat_list_count" value="'.$num_rows.'">';
			$data .='</div>';
			
			  	
		}	
		///////////////////////////////////////////////end done by satush 1 december 2016///////////////////////////
		elseif($req_type == "view")		
		{
			$data .= '<table id="tbl_user" class="table table-bordered  " style="padding: 10px;overflow-y: scroll;width:100%;text-align:center">';
      		$data .= '<thead>';
    	  	$data .= '<tr>';
			
         	$data .= '<th style="text-align:center">Sr No</th>';
			$data .= '<th style="text-align:center">Category  Name</th>';
			
			
			$data .= '</tr>';
      		$data .= '</thead>';
			$data .= '<tbody>';
			
			$sr_no=1;
			$sql_cat_data 	= " SELECT * FROM `tbl_product_cats` WHERE `prodcat_prodid` = '".$prod_id."' GROUP BY prodcat_catid ORDER BY `prodcat_default` DESC "; // this ind_id is error id from error table
			$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
		    while($row_cat_data		= mysqli_fetch_array($result_cat_data))
			{       
			        
					
			     
			       $sql_get_cat	= " SELECT * FROM `tbl_category` ";
			       $sql_get_cat	.= " WHERE `cat_id`=".$row_cat_data['prodcat_catid']." ";
				   $res_get_cat	= mysqli_query($db_con, $sql_get_cat) or die(mysqli_error($db_con));
			       $row_cat	= mysqli_fetch_array($res_get_cat);
				   
				   $data .='<tr>';
				   $data  .='<td>'.$sr_no++.'</td>';
				   $data  .='<td>';
				   $data .='<span style="">'.$row_cat['cat_name'].'</span>&nbsp;&nbsp;';
				   $data  .='</td>';
				   $data .='</tr>';
			       
				  
				
			}
			$data .= '</tbody>';
			$data .= '</table>';			
		}
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Product Content<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="prod_content" name="prod_content" >';
		if($prod_id != "" && $req_type == "error")
		{
			$data .= $row_prod_data->prod_content;
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_content']) != "")
			{
				$data 	.= ucwords($row_prod_data['prod_content']);
			}
			else
			{
				$data 	.= '<span style="color:#E63A3A">Data Not Available!!!</span>';
			}			
		}		
		else
		{
			$data .= $row_prod_data['prod_content'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';								
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Stock<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="prod_quantity" name="prod_quantity" placeholder="Quantity" class="input-large" maxlength="6" onKeyPress="return isNumberKey(event)" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_prod_data['prod_quantity'].'"'; 
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_prod_data->prod_quantity.'"'; 			
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_quantity']) != "")
			{
				$data .= ' value="'.$row_prod_data['prod_quantity'].'" disabled'; 				
			}
			else
			{
				$data .= ' value="<span style="color:#E63A3A">Data Not Available!!!</span>" disabled';
			}						
		}		
		$data .= '/>';
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';				
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Min Quantity<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" onkeypress="return isNumberKey(event)" id="prod_min_quantity" name="prod_min_quantity" class="input-large valid" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_prod_data['prod_min_quantity'].'"'; 
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_prod_data->prod_min_quantity.'"'; 			
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_min_quantity']) != "")
			{
				$data .= ' value="'.$row_prod_data['prod_min_quantity'].'" disabled'; 				
			}
			else
			{
				$data .= ' value="<span style="color:#E63A3A">Data Not Available!!!</span>" disabled';
			}							
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';	
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Max Quantity<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="prod_max_quantity" onkeypress="return isNumberKey(event)" name="prod_max_quantity" class="input-large valid" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_prod_data['prod_max_quantity'].'"'; 
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_prod_data->prod_max_quantity.'"'; 			
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_max_quantity']) != "")
			{
				$data .= ' value="'.$row_prod_data['prod_max_quantity'].'" disabled'; 				
			}
			else
			{
				$data .= ' value="<span style="color:#E63A3A">Data Not Available!!!</span>" disabled';
			}							
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';					
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">List Price<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" onkeypress="return isNumberKey(event)" id="prod_list_price" name="prod_list_price" placeholder="List Price" class="input-large" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_prod_data['prod_list_price'].'"'; 
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_prod_data->prod_list_price.'"'; 			
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_list_price']) != "")
			{
				$data .= ' value="'.$row_prod_data['prod_list_price'].'" disabled'; 				
			}
			else
			{
				$data .= ' value="<span style="color:#E63A3A">Data Not Available!!!</span>" disabled';
			}							
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Recommended Price<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" onkeypress="return isNumberKey(event)" id="prod_recommended_price" name="prod_recommended_price" placeholder="Recommended Price" class="input-large" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_prod_data['prod_recommended_price'].'"'; 
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_prod_data->prod_recommended_price.'"'; 			
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_recommended_price']) != "")
			{
				$data .= ' value="'.$row_prod_data['prod_recommended_price'].'" disabled'; 				
			}
			else
			{
				$data .= ' value="<span style="color:#E63A3A">Data Not Available!!!</span>" disabled';
			}										
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="prod_google_product_category" class="control-label">Google Product Category<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="prod_google_product_category" name="prod_google_product_category" placeholder="Google Product Category"';
		if($prod_id != "" && $req_type == "view")
		{
			$data .= ' readonly ';
		}
		
		$data .= '>';
		if($prod_id != "" && $req_type == "error")
		{
			$data .= $row_prod_data->prod_google_product_category;
		}
		else
		{
			$data .= $row_prod_data['prod_google_product_category'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';			
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Meta Tags<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="prod_meta_tags" name="prod_meta_tags" placeholder="Meta Tags" class="input-large" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_prod_data['prod_meta_tags'].'"'; 
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_prod_data->prod_meta_tags).'"'; 			
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_meta_tags']) != "")
			{
				$data .= ' value="'.$row_prod_data['prod_meta_tags'].'" disabled'; 				
			}
			else
			{
				$data .= ' value="Data Not Available" disabled';
			}								
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Meta Title<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="prod_meta_title" name="prod_meta_title" placeholder="Meta Title" class="input-large" ';
		if($prod_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_prod_data['prod_meta_title'].'"';
		}
		elseif($prod_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_prod_data->prod_meta_title.'"';
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_meta_title']) != "")
			{
				$data .= ' value="'.$row_prod_data['prod_meta_title'].'" disabled'; 				
			}
			else
			{
				$data .= ' value="Data Not Available" disabled';
			}			
		}		
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';			
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Meta Description<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="prod_meta_description" name="prod_meta_description" placeholder="Meta Description">';
		if($prod_id != "" && $req_type == "error")
		{
			$data .= $row_prod_data->prod_meta_description;
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if(trim($row_prod_data['prod_meta_description']) != "")
			{
				$data .= $row_prod_data['prod_meta_description'];
			}
			else
			{
				$data .= '<span style="color:#E63A3A">Data Not Available!!!</span>';
			}			
		}		
		else
		{
			$data .= $row_prod_data['prod_meta_description'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';	
		
		/*for wrong entries filters edit start*/
		if($req_type == "error")
		{
			$batch_filters=$row_prod_data->batch_filters;
			for($i=0;$i<sizeof($batch_filters);$i++)
			{
				$batch = explode(':',$batch_filters[$i]);
				array_push($error_filter_parent,$batch[0]);
				array_push($error_filter_chlid,$batch[1]);
				array_push($error_filter_subchild,$batch[2]);
			}
		}
		$error_filter_parent=array_unique($error_filter_parent);
		$error_filter_chlid=array_unique($error_filter_chlid);
		$error_filter_parent=array_unique($error_filter_parent);
		/*for wrong entries filters edit end*/
		
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Filters</label>';
		$data .= '<div class="controls">';
		$sql_get_parent_filters = " select * from tbl_filters where filt_type = 'parent' and filt_sub_child = 'parent' ";
		$result_get_parent_filters = mysqli_query($db_con,$sql_get_parent_filters) or die(mysqli_error($db_con));
		while($row_get_parent_filters = mysqli_fetch_array($result_get_parent_filters))
		{
			$data .= '<div style="border-bottom:1px solid #8f8f8f;padding:10px;">';
			$data .= '<input type="checkbox" value="'.$row_get_parent_filters['filt_id'].'" id="filter_parent'.$row_get_parent_filters['filt_id'].'" name="filter_parent'.$row_get_parent_filters['filt_id'].'" class="css-checkbox batch_filters filters_parent" ';
			if($req_type == "view")
			{
				$data .= 'disabled="disabled"';
			}
			if($req_type == "view" || $req_type == "edit")
			{
				if(in_array($row_get_parent_filters['filt_id'],$row_get_filters_parent))
				{
					$data .= 'checked';
				}
			}	
			
			if($req_type == "error")
			{
				if(in_array($row_get_parent_filters['filt_id'],$error_filter_parent))
				{
					$data .= 'checked';
				}
			}		
			$data .= '>';
			$data .= ucwords($row_get_parent_filters['filt_name']).'<label for="filter_parent'.$row_get_parent_filters['filt_id'].'" class="css-label" ></label>';
					
			$sql_get_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' and filt_sub_child = 'child' ";
			$result_get_child_filters = mysqli_query($db_con,$sql_get_child_filters) or die(mysqli_error($db_con));			
			$data .= '<div style="padding:10px;margin:3px;">';			
			while($row_get_child_filters = mysqli_fetch_array($result_get_child_filters))
			{
				$data .= '<div style="float:left;padding-right:5px;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid #8f8f8f;margin-right:10px;margin-top:10px;">';	
				$data .= '<input type="checkbox" value="'.$row_get_child_filters['filt_id'].'" id="filter_child'.$row_get_child_filters['filt_id'].'" name="filter_child'.$row_get_child_filters['filt_id'].'" class="css-checkbox batch_filters filters_child'.$row_get_parent_filters['filt_id'].' " ';
				if($req_type == "view")
				{
					$data .= 'disabled="disabled" ';
				}
				if($req_type == "view" || $req_type == "edit")
				{
					if(in_array($row_get_child_filters['filt_id'],$row_get_filters_child))					
					{
						$data .= ' checked';
					}
				}
				
				if($req_type == "error")
				{    
				    
					if(in_array($row_get_child_filters['filt_id'],$error_filter_chlid))					
					{
						$data .= ' checked';
					}
				}
		
				$data .= '>';
				
				$data .= ucwords($row_get_child_filters['filt_name']).'<label for="filter_child'.$row_get_child_filters['filt_id'].'" onchange="checkParent(\''.$row_get_parent_filters['filt_id'].'\',\''.$row_get_child_filters['filt_id'].'\');" class="css-label"></label>';
				
				$sql_get_sub_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' and filt_sub_child = '".$row_get_child_filters['filt_id']."' ";
				$result_get_sub_child_filters = mysqli_query($db_con,$sql_get_sub_child_filters) or die(mysqli_error($db_con));	
				$num_rows_get_sub_child_filters = mysqli_num_rows($result_get_sub_child_filters);		
				if($num_rows_get_sub_child_filters != 0)
				{
					$data .= '<div style="padding:10px;">';			
					while($row_get_sub_child_filters = mysqli_fetch_array($result_get_sub_child_filters))
					{
						$data .= '<input type="checkbox" value="'.$row_get_sub_child_filters['filt_id'].'" id="filters_sub_child'.$row_get_sub_child_filters['filt_id'].'" name="filters_sub_child'.$row_get_sub_child_filters['filt_id'].'" class="css-checkbox batch_filters filters_sub_child'.$row_get_child_filters['filt_id'].' " ';
						if($req_type == "view")
						{
							$data .= 'disabled="disabled" ';
						}
						if($req_type == "view" || $req_type == "edit")
						{
							if(in_array($row_get_sub_child_filters['filt_id'],$row_get_filters_sub_child))					
							{
								$data .= 'checked';
							}
						}
						if($req_type == "error")
						{   
							if(in_array($row_get_sub_child_filters['filt_id'],$error_filter_subchild))					
							{
								$data .= 'checked';
							}
						}
						$data .= '>';
						$data .= ucwords($row_get_sub_child_filters['filt_name']).'<label for="filters_sub_child'.$row_get_sub_child_filters['filt_id'].'" class="css-label"></label>';
					}
					$data .= '</div>';										
				}
				$data .= '</div>';
			}
			$data .= '<div style="clear:both;"></div>';			
			$data .= '</div>';			
			$data .= '</div>';			
		}
		$data .= '</div>';
		$data .= '</div>';	
		
		/*for wrong entries levels edit start*/
		$error_levels_parent=array();
		$error_levels_chlid=array();
		if($req_type == "error")
		{
			$batch_levels=$row_prod_data->batch_levels;
			for($i=0;$i<sizeof($batch_levels);$i++)
			{
				$levels = explode(':',$batch_levels[$i]);
				array_push($error_levels_parent,$levels[0]);
				array_push($error_levels_chlid,$levels[1]);
			}
		}
		
		$error_levels_parent=array_unique($error_levels_parent);
		$error_levels_chlid=array_unique($error_levels_chlid);
	    /*for wrong entries levels edit end*/	
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Level</label>';
		
		$data .= '<div class="controls">';
		$sql_get_parent_levels 			= " select * from tbl_level where cat_type = 'parent' and cat_name != 'none'";
		$result_get_parent_levels 		= mysqli_query($db_con,$sql_get_parent_levels) or die(mysqli_error($db_con));
		while($row_get_parent_levels 	= mysqli_fetch_array($result_get_parent_levels))
		{
			$data .= '<div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid #8f8f8f;margin-right:10px;margin-top:10px;">';
			$data .= '<input type="checkbox" value="'.$row_get_parent_levels['cat_id'].'" id="level_parent'.$row_get_parent_levels['cat_id'].'" name="level_parent'.$row_get_parent_levels['cat_id'].'" class="css-checkbox batch_levels levels_parent"';
			if($req_type == "view")
			{
				$data .= 'disabled="disabled"';
			}
			if($req_type == "view" || $req_type == "edit")
			{
				if(in_array($row_get_parent_levels['cat_id'],$row_get_levels_parent))
				{
					$data .= 'checked';
				}
			}
			if($req_type == "error")
			{
				if(in_array($row_get_parent_levels['cat_id'],$error_levels_parent))
				{
					$data .= 'checked';
				}
			}					
			$data .= '>';
			$data .= ucwords($row_get_parent_levels['cat_name']).'<label for="level_parent'.$row_get_parent_levels['cat_id'].'" class="css-label" ></label>';
					
			$sql_get_child_levels = " select * from tbl_level where cat_type = '".$row_get_parent_levels['cat_id']."' ";//and cat_name != 'none' ";
			$result_get_child_levels = mysqli_query($db_con,$sql_get_child_levels) or die(mysqli_error($db_con));			
			$data .= '<div style="margin:20px;">';			
			while($row_get_child_levels = mysqli_fetch_array($result_get_child_levels))
			{
				$data .= '<input type="checkbox" value="'.$row_get_child_levels['cat_id'].'" id="level_child'.$row_get_child_levels['cat_id'].'" name="level_child'.$row_get_child_levels['cat_id'].'" onchange="checkParent(\''.$row_get_parent_levels['cat_id'].'\',\''.$row_get_child_levels['cat_id'].'\');" class="css-checkbox batch_levels levels_child'.$row_get_parent_levels['cat_id'].' "';
				if($req_type == "view")
				{
					$data .= 'disabled="disabled"';
				}
				if($req_type == "view" || $req_type == "edit")
				{
					if(in_array($row_get_child_levels['cat_id'],$row_get_levels_child))					
					{
						$data .= 'checked';
					}
				}
				if($req_type == "error")
				{    
				    
					if(in_array($row_get_child_levels['cat_id'],$error_levels_chlid))					
					{
						$data .= ' checked';
					}
				}
				$data .= '>';
				$data .= ucwords($row_get_child_levels['cat_name']).'<label for="level_child'.$row_get_child_levels['cat_id'].'" class="css-label"></label>';
			}
			$data .= '</div>';			
			$data .= '</div>';			
		}
		$data .= '</div>';
		$data .= '</div>';		
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($prod_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="prod_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_products.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_prod_data->prod_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="prod_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_prod_data->prod_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($prod_id != "" && $req_type == "view")
		{
			if($row_prod_data['prod_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_prod_data['prod_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="prod_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_products.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_prod_data['prod_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="prod_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_prod_data['prod_status'] == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div> <!--Status-->';
		$data .= '<div class="form-actions">';
		if($req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Save Product</button>';			
		}
		else if($req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Product</button>';			
		}
		else if($req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update Product</button>';			
		}		
		$data .= '</div> <!-- Save and cancel -->';			
		$data .= '<script type="text/javascript">';
		$data .= '$("#prod_orgid").select2();';
		$data .= '$("#prod_brandid").select2();';
		$data .= '$("#prod_catid").select2();';
		$data .= '$("#prod_subcatid").select2();';
		$data .= 'CKEDITOR.replace("prod_description",{height:"150", width:"100%"});';
		$data .= 'CKEDITOR.replace("prod_content",{height:"150", width:"100%"});';			
		$data .= 'CKEDITOR.replace("prod_meta_description",{height:"150", width:"100%"});';	
		if($prod_id != "" && $req_type == "view")
		{
			$data .= '$("#prod_description").prop("disabled","true");';
			$data .= '$("#prod_content").prop("disabled","true");';
			$data .= '$("#prod_meta_description").prop("disabled","true");';
		}	
		$data .= '</script>';
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($data));
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Not received");		
	}
	echo json_encode($response_array);
}
/* Load Add,edit view details and error update forms*/
/* Load product table information with pagination*/
if((isset($obj->load_products)) == "1" && isset($obj->load_products))
{
	
	$response_array	 				= array();	
	$start_offset   				= 0;
	
	$page 							= $obj->page_no_prod;	
	$per_page						= $obj->row_limit_prod;
	$search_text					= $obj->search_text_prod;
		
	$prod_status					= $obj->prod_status;
	$prod_orgid 					= $obj->prod_orgid;		
	$prod_brandid 					= $obj->prod_brandid;	
	$prod_catid 					= $obj->prod_catid;	
	//$prod_subcatid 					= $obj->prod_subcatid;
	$prod_modified_by				= $obj->prod_modified_by;
	$prod_created_by				= $obj->prod_created_by;		
	$no_image 					= $obj->no_image;
	$no_stock 					= $obj->no_stock;
	$out_stock 					= $obj->out_stock;
	$prod_star_status 				= $obj->star_status;
	$excel							= $obj->excel;
	$prod_google_product_category 	= $obj->prod_google_product_category;
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT DISTINCT `prod_slug`,`prod_id`,`prod_min_quantity`,`prod_max_quantity`, `prod_orgid`,`prod_catid`,`prod_subcatid`,`prod_model_number`, `prod_name`,`prod_title`,`prod_star_status`, ";
		$sql_load_data   .= "(SELECT org_name FROM `tbl_oraganisation_master` where org_id =`prod_orgid` ) as org_name,prod_meta_description,prod_meta_tags,prod_meta_title,";
		$sql_load_data   .= "(SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_status` != 0 and `prod_img_type` = 'main' and `prod_img_prodid` = prod_id limit 1) as prod_img_file_name,";
		$sql_load_data  .= " (SELECT `brand_name` FROM `tbl_brands_master` WHERE `brand_id`=`prod_brandid`) as brand_name,prod_description,prod_google_product_category,";
		$sql_load_data  .= " (SELECT `cat_name` FROM `tbl_category` WHERE `cat_id`=prod_catid) as cat_name,prod_quantity,prod_content,prod_payment_mode,";
		$sql_load_data  .= " (SELECT `cat_name` FROM `tbl_category` WHERE `cat_id`=prod_subcatid) as sub_cat_name,prod_org_price,prod_returnable,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id =prod_created_by) as created_name,prod_list_price,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id =prod_modified_by) as prod_modified_name,prod_recommended_price,";
		$sql_load_data  .= " `prod_created`,  `prod_modified`, `prod_status` FROM `tbl_products_master` tpm ";
		if((isset($obj->prodlevel_levelid_parent) && trim($obj->prodlevel_levelid_parent) != "" )|| (isset($obj->prodlevel_levelid_child) && trim($obj->prodlevel_levelid_child) != ""))
		{
			$sql_load_data  .= " INNER JOIN tbl_product_levels tpl ON tpm.prod_id = tpl.prodlevel_prodid ";
		}
		if((isset($obj->prodfilt_filtid_parent) && trim($obj->prodfilt_filtid_parent) != "" ) || (isset($obj->prodfilt_filtid_child) && trim($obj->prodfilt_filtid_child) != "" ) || (isset($obj->prodfilt_filtid_sub_child) && trim($obj->prodfilt_filtid_sub_child) != "" ))
		{
			$sql_load_data  .= " INNER JOIN tbl_product_filters tpf ON tpm.prod_id = tpf.prodfilt_prodid ";
		}		
		$sql_load_data  .= " where 1=1 ";
		if((isset($obj->prodlevel_levelid_parent) && trim($obj->prodlevel_levelid_parent) != "" )|| (isset($obj->prodlevel_levelid_child) && trim($obj->prodlevel_levelid_child) != ""))
		{
			$sql_load_data  .= " and tpl.prodlevel_status = 1 ";						
		}		
		if((isset($obj->prodfilt_filtid_parent) && trim($obj->prodfilt_filtid_parent) != "" ) || (isset($obj->prodfilt_filtid_child) && trim($obj->prodfilt_filtid_child) != "" ) || (isset($obj->prodfilt_filtid_sub_child) && trim($obj->prodfilt_filtid_sub_child) != "" ))
		{
			$sql_load_data  .= " and tpf.prodfilt_status = 1 ";			
		}		
		if(isset($obj->prodlevel_levelid_parent) && trim($obj->prodlevel_levelid_parent) != "")
		{
			$prodlevel_levelid_parent			= mysqli_real_escape_string($db_con,$obj->prodlevel_levelid_parent);
			$sql_load_data  					.= " and prodlevel_levelid_parent = '".$prodlevel_levelid_parent."' ";
		}
		if(isset($obj->prodlevel_levelid_child) && trim($obj->prodlevel_levelid_child) != "")
		{
			$prodlevel_levelid_child			= mysqli_real_escape_string($db_con,$obj->prodlevel_levelid_child);
			$sql_load_data  					.= " and prodlevel_levelid_child = '".$prodlevel_levelid_child."' ";
		}
		if(isset($obj->prodfilt_filtid_parent) && trim($obj->prodfilt_filtid_parent) != "")
		{
			$prodfilt_filtid_parent				= mysqli_real_escape_string($db_con,$obj->prodfilt_filtid_parent);
			$sql_load_data  					.= " and prodfilt_filtid_parent = '".$prodfilt_filtid_parent."' ";
		}
		if(isset($obj->prodfilt_filtid_child) && trim($obj->prodfilt_filtid_child) != "")
		{
			$prodfilt_filtid_child_data			= mysqli_real_escape_string($db_con,$obj->prodfilt_filtid_child);
			$prodfilt_filtid_child				= explode(":",$prodfilt_filtid_child_data);
			$sql_load_data  					.= " and prodfilt_filtid_child = '".$prodfilt_filtid_child[1]."' ";
		}
		if(isset($obj->prodfilt_filtid_sub_child) && trim($obj->prodfilt_filtid_sub_child) != "")
		{
			$prodfilt_filtid_sub_child_data			= mysqli_real_escape_string($db_con,$obj->prodfilt_filtid_sub_child);
			$prodfilt_filtid_sub_child				= explode(":",$prodfilt_filtid_sub_child_data);			
			$sql_load_data  					.= " and prodfilt_filtid_sub_child = '".$prodfilt_filtid_sub_child[1]."' ";
		}						

		if(strcmp($utype,'1')!==0)
		{
			if($utype)
			{
				$sql_load_data  .= " AND prod_orgid='".$tbl_users_owner."' ";
			}
			else
			{
				$sql_load_data  .= " AND prod_created_by='".$uid."' ";
			}			
		}
		
		if($search_text != "")
		{
			$sql_load_data .= " AND (`prod_id` like '%".$search_text."%' or `prod_model_number` like '%".$search_text."%' ";
			$sql_load_data .= " or `prod_name` like '%".$search_text."%' or `prod_slug` like '%".$search_text."%' ";
			$sql_load_data .= "	or `prod_recommended_price` like '%".$search_text."%' or `prod_list_price` like '%".$search_text."%' ";
			$sql_load_data .= "	or `prod_created` like '%".$search_text."%' or `prod_modified` like '%".$search_text."%' )";
		}
		if(trim($prod_orgid) != "")
		{
			$sql_load_data  .= " AND prod_orgid = '".$prod_orgid."' ";
		}
		if(trim($prod_brandid) != "")
		{
			$sql_load_data  .= " AND prod_brandid = '".$prod_brandid."' ";
		}
		if($prod_catid !='')
		{   
		  
			 //$sql_load_data  .= " AND tpm.prod_id IN (".$prod.") ";
			// $sql_load_data  .= " AND tpm.prod_id IN (SELECT DISTINCT(prodcat_prodid) FROM tbl_product_cats WHERE prodcat_prod_status='1' AND prodcat_catid = ".$prod_catid.") ";
			
		     $sql_load_data  .= " AND tpm.prod_id IN (SELECT DISTINCT(prodcat_prodid) FROM tbl_product_cats WHERE prodcat_catid IN ";
			 $sql_load_data  .=" ( SELECT DISTINCT(cat_id) FROM `tbl_category` WHERE `cat_type` ='".$prod_catid."' or `cat_id` ='".$prod_catid."' )) ";	
		
			
		}
		
		/*if(trim($prod_catid) != "")
		{
			$sql_load_data  .= " AND prod_catid = '".$prod_catid."' ";
		}*/
		/*if(trim($prod_subcatid) != "")
		{
			$sql_load_data  .= " AND prod_subcatid = '".$prod_subcatid."' ";
		}*/
		if(trim($prod_created_by) != "")
		{
			$sql_load_data  .= " AND prod_created_by = '".$prod_created_by."' ";			
		}
		if(trim($prod_modified_by) != "")
		{
			$sql_load_data  .= " AND prod_modified_by = '".$prod_modified_by."' ";			
		}		
		if($prod_status != "")
		{
			$sql_load_data  .= " AND prod_status = '".$prod_status."' ";
		}
		if($no_image == "image")
		{
			$sql_load_data  .= " AND prod_id NOT IN (SELECT distinct(`prod_img_prodid`) FROM `tbl_products_images`) ";
		}
		//<!------------------------------Start - Done by Monika 10-NOV-2016--------------------------->
		if($no_stock == "stock")
		{
			$sql_load_data  .= " AND prod_quantity != '0'";
		}
		if($out_stock == "outstock")
		{
			$sql_load_data  .= " AND prod_quantity = '0'";
		}//echo $out_stock;die;
		//<!------------------------------End - Done by Monika 10-NOV-2016--------------------------->
		if($prod_google_product_category == "1")
		{
			$sql_load_data  .= " AND prod_google_product_category = '' ";
		}
		if($prod_star_status !=  "")
		{
			$sql_load_data  .= " AND prod_star_status = '".$prod_star_status."' ";			
		}

		//$response_array = array("Success"=>"fail","resp"=>$sql_load_data);
		//echo json_encode($response_array);
		//exit();		


		if($excel == 1)
		{
			
			$response_array	 = exportToXlsx($sql_load_data);
			//echo json_encode($response_array);exit();
		}
		else
		{	
			//$response_array = array("Success"=>"fail","resp"=>$sql_load_data);
			//echo json_encode($response_array);	
			//exit();
						
			$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
			$sql_load_data .=" LIMIT $start, $per_page ";//ORDER by prod_id DESC
			$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
			if(strcmp($data_count,"0") !== 0)
			{		
			$prod_data = "";	
			$prod_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$prod_data .= '<thead>';
    	  	$prod_data .= '<tr>';
         	$prod_data .= '<th class="center-text">Sr. No.</th>';
			$prod_data .= '<th class="center-text">Prod id</th>';
			$prod_data .= '<th class="center-text">Product Details</th>';			
			$prod_data .= '<th class="center-text" style="width:15%;">Title and Product URL/SLUG</th>';
			$prod_data .= '<th class="center-text" style="width:8%;">Discount</th>';
			$prod_data .= '<th class="center-text" style="width:8%;">Stock</th>';
			$prod_data .= '<th class="center-text">Product<br>Images & <br> Specification</th>';	
			//<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
				if($utype == "1")
				{
					$prod_data .= '<th class="center-text">Payment Mode <br>&<br> Product Return</th>';
				}
				//<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->			
			$prod_data .= '<th class="center-text">';
			$dis = checkFunctionalityRight("view_products.php",3);
			$edit = checkFunctionalityRight("view_products.php",1);
			$del = checkFunctionalityRight("view_products.php",2);
			$del = 1;
			if($del)
			{			
				$prod_data .= '<div class="center-text"><input type="button"  value="Delete" onclick="deleteProducts();" class="btn-danger"/><br>';
				$prod_data .= '<input type="checkbox" id="parent_chk" class="css-checkbox parent_batch_prod" onchange="childCheckUncheck(this.id,\'batch_prod\');">';
				$prod_data .= '<label for="parent_chk" class="css-label"></label>All</th>';			
				$prod_data .= '</div>';
			}			
          	$prod_data .= '</th>';			
          	$prod_data .= '</tr>';
      		$prod_data .= '</thead>';
      		$prod_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$prod_data .= '<tr>';				
				$prod_data .= '<td>'.++$start_offset.'</td>';				
				$prod_data .= '<td class="center-text">'.$row_load_data['prod_id'];
				//<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
				if($utype == "1"){
				//<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->
				$prod_data .= '<br><i id="'.$row_load_data['prod_id'].'star_status" ';
				if($row_load_data['prod_star_status'] == 1)
				{
					$prod_data .= ' onclick="changeStarStatus(this.id,\'0\');" class="icon-star" style="font-size:30px;cursor:pointer;color:#FFD700;padding:5px;margin-top:10px"></i>';
				}
				else
				{
					$prod_data .= ' onclick="changeStarStatus(this.id,\'1\');" class="icon-star-empty" style="font-size:30px;cursor:pointer;padding:5px;margin-top:10px"></i> ';					
				}
				$prod_data .= '</button>';
				$prod_data .= '</td>';	
				//<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
				}
				//<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->			
				$prod_data .= '<td style="width:300px;"><div>';
				$prod_data .= '<a href="javascript:void(0);" class="btn-link txtoverflow" id="'.$row_load_data['prod_id'].'" onclick="addMoreProduct(this.id,\'view\');">'.ucwords($row_load_data['prod_name']).'</a><br>';
				$prod_data .= '<i class="icon-chevron-down" id="'.$row_load_data['prod_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['prod_id'].'prod_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i></div>';
				$prod_data .= '<div id="'.$row_load_data['prod_id'].'prod_div" style="display:none;">';
				$prod_data .= '<b>Model Number:&nbsp;</b>'.$row_load_data['prod_model_number'].'<br>';
				if($row_load_data['org_name'] == "")
				{
					$prod_data .= '<b>Organisation:&nbsp;</b><span style="color:red">Not Available</span><br>';									
				}
				else
				{
					$prod_data .= '<b>Organisation:&nbsp;</b>'.ucwords($row_load_data['org_name']).'<br>';
				}
				if($row_load_data['brand_name'] == "")
				{
					$prod_data .= '<b>Brand:&nbsp;</b><span style="color:red">Not Available</span><br>';									
				}
				else
				{
					$prod_data .= '<b>Brand:&nbsp;</b>'.ucwords($row_load_data['brand_name']).'<br>';									
				}
				
				/*done by monika - start*/	
				$sql_cat_data 	= " SELECT * FROM `tbl_product_cats` WHERE `prodcat_prodid` = '".$row_load_data['prod_id']."' GROUP BY prodcat_catid ORDER BY `prodcat_default` DESC "; // this ind_id is error id from error table
				$result_cat_data 	= mysqli_query($db_con,$sql_cat_data) or die(mysqli_error($db_con));
				$prod_data .= '<b>Category:&nbsp;</b>';	
				$row_fil_arr =array();
		    	while($row_cat_data		= mysqli_fetch_array($result_cat_data))
				{       
			     	$sql_get_cat	= " SELECT * FROM `tbl_category` ";
			       $sql_get_cat	.= " WHERE `cat_id`=".$row_cat_data['prodcat_catid']." ";
				   $res_get_cat	= mysqli_query($db_con, $sql_get_cat) or die(mysqli_error($db_con));
			       $row_cat	= mysqli_fetch_array($res_get_cat);
				   array_push($row_fil_arr,ucwords($row_cat['cat_name']));	
				}
				$prod_data .= implode(',',$row_fil_arr);
				/*done by monika - end*/
					
				/*if($row_load_data['cat_name'] == "")
				{
					$prod_data .= '<b>Category:&nbsp;</b><span style="color:red">Not Available</span><br>';
				}
				else
				{
					$prod_data .= '<b>Category:&nbsp;</b>'.ucwords($row_load_data['cat_name']).'<br>';									
				}
				if($row_load_data['sub_cat_name'] == "")
				{
					$prod_data .= '<b>Sub-Category:&nbsp;</b><span style="color:red">Not Available</span><br>';									
				}
				else
				{
					$prod_data .= '<b>Sub-Category:&nbsp;</b>'.ucwords($row_load_data['sub_cat_name']).'<br>';									
				}*/
				$prod_data .= '<br><b>List Price:&nbsp;</b>Rs.'.$row_load_data['prod_list_price'].'<br>';
				$prod_data .= '<b>Recommended Price:&nbsp;</b>Rs.'.$row_load_data['prod_recommended_price'].'<br>';
				$percentage = (($row_load_data['prod_list_price'] - $row_load_data['prod_recommended_price']) / $row_load_data['prod_list_price']) * 100;			
				$prod_data .= '<b>Diccount On List Price:&nbsp;</b>'.round($percentage,2).' % OFF<br>';
				$prod_data .= '<b>Vendor Price:&nbsp;</b>Rs.'.$row_load_data['prod_org_price'].'<br>';
				$prod_data .= '<b>Payment Mode:&nbsp;</b>';
				if($row_load_data['prod_payment_mode'] == 1)
				{
					$prod_data .= 'Pay Online';
				}
				elseif($row_load_data['prod_payment_mode'] == 2)
				{
					$prod_data .= 'Cash On Delivery';					
				}
				elseif($row_load_data['prod_payment_mode'] == 3)
				{
					$prod_data .= 'Both Pay Online and Cash On Delivery';					
				}
				$prod_data .= '<br>';				
				$prod_data .= '<b>Created By:&nbsp;</b>'.ucwords($row_load_data['created_name']).'<br>';
				$prod_data .= '<b>Created:&nbsp;</b>'.$row_load_data['prod_created'].'<br>';
				if(trim($row_load_data['prod_modified_name']) == "")
				{
					$prod_data .= '<b>Modified By:&nbsp;</b><span style="font-style:italic;">Not Modified yet</span><br>';
				}
				elseif($row_load_data['prod_modified_name'] != "")
				{
					$prod_data .= '<b>Modified By:&nbsp;</b>'.ucwords($row_load_data['prod_modified_name']).'<br>';
				}
				if($row_load_data['prod_modified'] == "0000-00-00 00:00:00" || $row_load_data['prod_modified'] == "")
				{
					$prod_data .= '<b>Modified:&nbsp;</b><span style="font-style:italic;"><span style="font-style:italic;">Not Modified yet</span></span><br>';
				}
				else
				{
					$prod_data .= '<b>Modified:&nbsp;</b>'.$row_load_data['prod_modified'].'<br>';
				}				
				$prod_data .= '</div>';				
				$prod_data .= '</td>';
				$prod_data .= '<td class="center-text">';
				$prod_data .= '<span><a target="_blank" href="https://www.planeteducate.com/'.$row_load_data['prod_slug'].'-'.$row_load_data['prod_id'].'-a">Click Here</a></span><br>';
				$prod_data .= '<i class="icon-chevron-down" id="'.$row_load_data['prod_id'].'1_chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['prod_id'].'1_prod_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$prod_data .= '<div style="display:none;" id="'.$row_load_data['prod_id'].'1_prod_div">';				
				$prod_data .= '<span>Title</span><br>';				
				$prod_data .= '<textarea id="'.$row_load_data['prod_id'].'prod_tilte" onBlur="changeProductData(this.id,3);">'.$row_load_data['prod_title'].'</textarea><br>';
				$prod_data .= '<span>Product URL/Slug</span><br>';
				$prod_data .= '<textarea id="'.$row_load_data['prod_id'].'prod_slug" onBlur="changeProductData(this.id,4);">'.$row_load_data['prod_slug'].'</textarea>';
				$prod_data .= '<span>Google Product Category</span><br>';				
				$prod_data .= '<textarea id="'.$row_load_data['prod_id'].'prod_gpc" onBlur="changeProductData(this.id,5);">'.$row_load_data['prod_google_product_category'].'</textarea><br>';				
				$prod_data .= '</div>';				
				$prod_data .= '</td>';
				$prod_data .= '<td class="center-text" style="width:100px;">';
				$prod_data .= '<div>';
				$prod_data .= '<span><input type="radio" name="'.$row_load_data['prod_id'].'discount" value="flat">Flat </span>';
				$prod_data .= '<span><input type="radio" name="'.$row_load_data['prod_id'].'discount" value="percent">Percent(%) </span>';
				$prod_data .= '</div><br>';					
				$prod_data .= '<div class="center-text">';
				$prod_data .= '<input type="text" onkeypress="return isNumberKey(event)" name="'.$row_load_data['prod_id'].'discount_value" id="'.$row_load_data['prod_id'].'discount_value">';					
				$prod_data .= '</div>';															
				$prod_data .= '<div class="center-text">';
				$prod_data .= '<input type="button" onClick="productDiscount(this.id,2);" class="btn-success" id="'.$row_load_data['prod_id'].'dis_btn" value="Apply to product '.$row_load_data['prod_id'].'">';
				$prod_data .= '</div>';				
				$prod_data .= '</td>';	
							
				$prod_data .= '<td class="center-text" style="width:100px;">';
				$prod_data .= '<div>';
				$prod_data .= '<span>'.$row_load_data['prod_quantity'].'</span>';
				$prod_data .= '</div>';					
			
				$prod_data .= '</td>';				
											
				$prod_data .= '<td class="center-text">';
				if(trim($row_load_data['prod_img_file_name']) == "")
				{
					$prod_data .= '<input type="button" value="No Image" id="'.$row_load_data['prod_id'].'" class="btn-danger" onclick="showImages(this.id);"><br>';									
				}
				else
				{
					$prod_data .= '<input type="button" value="Images" id="'.$row_load_data['prod_id'].'" class="btn-warning" onclick="showImages(this.id);">';									
					$imagepath = "../images/planet/org".$row_load_data['prod_orgid']."/prod_id_".$row_load_data['prod_id']."/small/".$row_load_data['prod_img_file_name'];
					$prod_data .= '<br><img src="'.$imagepath.'?'.rand().'">';
				}
				$sql_check_prod_spec = " SELECT * FROM `tbl_products_specifications` WHERE `prod_spec_prodid` = '".$row_load_data['prod_id']."' AND prod_spec_status = 1";
				$result_check_prod_spec = mysqli_query($db_con,$sql_check_prod_spec) or die(mysqli_error($db_con));
				$num_rows_check_prod_spec = mysqli_num_rows($result_check_prod_spec);
				$row_load_dataspec = mysqli_fetch_array($result_check_prod_spec);
				if($num_rows_check_prod_spec == 0)
				{
					$prod_data .= '<br><input type="button" value="Specification" id="'.$row_load_data['prod_id'].'" class="btn-danger" onclick="showSpecification(this.id);">';					
				}
				else
				{
					$prod_data .= '<br><input type="button" value="Specification" id="'.$row_load_data['prod_id'].'" class="btn-warning" onclick="showSpecification(this.id);">';
				}
				$prod_data .= '</td>';		
				//<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
				if($utype == "1"){
				//<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->			
				$prod_data .= '<td class="text-center">';									
				$prod_data .= '<div>';
				$prod_data .= '<Select class="select2-me input-large" id="'.$row_load_data['prod_id'].'payment_mode" onchange="changeProductData(this.id,2)">';
				$prod_data .= '<option value="">Select Payment Mode</option>';
				$prod_data .= '<option value="1">Pay Online</option>';
				$prod_data .= '<option value="2">Cash On Delivery</option>';															
				$prod_data .= '<option value="3">Both Pay Online and Cash On Delivery</option>';
				$prod_data .= '</Select><br>';
				$prod_data .= '<input type="radio" value="0" id="'.$row_load_data['prod_id'].'can_not_return" onchange="changeProductData(this.name,7);" name="'.$row_load_data['prod_id'].'product_return"';
				if($row_load_data['prod_returnable'] == 0)
				{
					$prod_data .= '	checked';				
				}				
				$prod_data .= '	>Can Not Return';		
				$prod_data .= '<input type="radio" value="1" id="'.$row_load_data['prod_id'].'can_return" onchange="changeProductData(this.name,7);" name="'.$row_load_data['prod_id'].'product_return"';
				if($row_load_data['prod_returnable'] == 1)
				{
					$prod_data .= '	checked';				
				}					
				$prod_data .= '	>Can Return';
				$prod_data .= '</div>';														
				$prod_data .= '</td>';	
				//<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
				}
				//<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->
				$prod_data .= '<script type="text/javascript">';
				$prod_data .= '$("#payment_mode'.$row_load_data['prod_id'].'").select2();';				
				$prod_data .= '</script>';									
				//$dis = checkFunctionalityRight("view_products.php",3);
				$prod_data .= '<td class="center-text">';					
				if($dis)
				{
					$prod_data .= '<div style="padding:5px;">';
					if($row_load_data['prod_status'] == 1)
					{
						$prod_data .= '<input type="button" value="Active" id="'.$row_load_data['prod_id'].'" class="btn-success" onclick="changeProductStatus(this.id,0);">';
					}
					else
					{
						$prod_data .= '<input type="button" value="Inactive" id="'.$row_load_data['prod_id'].'" class="btn-danger" onclick="changeProductStatus(this.id,1);">';
					}
					$prod_data .= '</div>';					
				}
				//$edit = checkFunctionalityRight("view_products.php",1);
				if($edit)
				{
					$prod_data .= '<div style="padding:5px;">';					
					$prod_data .= '<input type="button" value="Edit" id="'.$row_load_data['prod_id'].'" class="btn-warning" onclick="addMoreProduct(this.id,\'edit\');">';
					$prod_data .= '</div>';					
				}
				if($del)
				{
					$prod_data .= '<div class="center-text" style="padding:5px;">';
					$prod_data .= '<input type="checkbox" value="'.$row_load_data['prod_id'].'" id="batch_prod'.$row_load_data['prod_id'].'" name="batch_prod'.$row_load_data['prod_id'].'" class="css-checkbox batch_prod">';
					$prod_data .= '<label for="batch_prod'.$row_load_data['prod_id'].'" class="css-label"></label>';
					$prod_data .= '</div>';
				}
				$prod_data .= '</td>';									
	          	$prod_data .= '</tr>';															
			}	
      		$prod_data .= '</tbody>';
      		$prod_data .= '</table>';	
			$prod_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>utf8_encode($prod_data),"query"=>$sql_load_data);					
		}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"No Data Available","Query"=>$sql_load_data);
			}
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified for Products");
	}
	echo json_encode($response_array);	}
/* Load product table information with pagination*/
if((isset($obj->update_product)) == "1" && isset($obj->update_product))
{
	$prod_id						= mysqli_real_escape_string($db_con,$obj->prod_id);
	$prod_model_number				= mysqli_real_escape_string($db_con,$obj->prod_model_number);
	$prod_name						= htmlspecialchars(strtolower(mysqli_real_escape_string($db_con,$obj->prod_name)),ENT_HTML5);
	$prod_description				= mysqli_real_escape_string($db_con,$obj->prod_description);
	$prod_orgid						= mysqli_real_escape_string($db_con,$obj->prod_orgid);
	$prod_brandid					= mysqli_real_escape_string($db_con,$obj->prod_brandid);
	$prod_catid						= mysqli_real_escape_string($db_con,$obj->prod_catid);
	$prod_subcatid					= mysqli_real_escape_string($db_con,$obj->prod_subcatid);
	$prod_google_product_category 	= htmlspecialchars(mysqli_real_escape_string($db_con,$obj->prod_google_product_category),ENT_HTML5);
	$prod_content					= mysqli_real_escape_string($db_con,$obj->prod_content);
	$prod_min_quantity				= mysqli_real_escape_string($db_con,$obj->prod_min_quantity);
	$prod_max_quantity				= mysqli_real_escape_string($db_con,$obj->prod_max_quantity);
	$prod_quantity					= mysqli_real_escape_string($db_con,$obj->prod_quantity);
	$prod_list_price				= mysqli_real_escape_string($db_con,$obj->prod_list_price);
	$prod_recommended_price			= mysqli_real_escape_string($db_con,$obj->prod_recommended_price);
	$prod_meta_tags					= mysqli_real_escape_string($db_con,$obj->prod_meta_tags);
	$prod_meta_description			= mysqli_real_escape_string($db_con,$obj->prod_meta_description);
	$prod_meta_title				= mysqli_real_escape_string($db_con,$obj->prod_meta_title);
	$prod_status					= mysqli_real_escape_string($db_con,$obj->prod_status);
	$batch_filters 					= $obj->batch_filters;	
	$batch_levels 					= $obj->batch_levels;
	$remove_cat 					= $obj->remove_cat;
	$primary_cat 					= $obj->primary_cat;
	
	$response_array = array();	
	//ecoho json_encode($prod_google_product_category);exit();
	/*done by monika - start*/
	if($prod_min_quantity > $prod_max_quantity )
	{			
		$response_array = array("Success"=>"Fail","resp"=>"Maximum quantity should be greater than Minimum quantity.");		
		echo json_encode($response_array);			
		exit();			
	}																		
	if($prod_recommended_price > $prod_list_price)
	{
		$response_array = array("Success"=>"Fail","resp"=>"List price should be greater than recommended price.");		
		echo json_encode($response_array);			
		exit();									
	}	
	
	/*$sql_check_productmodel 		= " SELECT * from tbl_products_master where prod_model_number like '".$prod_model_number."' and prod_id !='".$prod_id."' ";
	$result_check_productmodel 	= mysqli_query($db_con,$sql_check_productmodel) or die(mysqli_error($db_con));
	$num_rows_check_productmodel = mysqli_num_rows($result_check_productmodel);
    $rows_check_productmodel = mysqli_fetch_array($result_check_productmodel);
	
	if($num_rows_check_productmodel > 0)
	{
		$response_array = array("Success"=>"fail","resp"=>'Model Number <b> "'.$prod_model_number.'" </b> Already Exist');
		echo json_encode($response_array);			
		exit();
	}*/
	/*done by monika - end*/
	if($prod_model_number != "" && $prod_name != "" && $prod_orgid != ""  && $prod_status != "")
	{
		/*if($prod_min_quantity == 0)
		{
			$prod_min_quantity = 1;
		}
		if($prod_max_quantity == 0)
		{
			$prod_max_quantity = 100;
		}	*/	
		$slug =getSlug($prod_name);
		
		$sql_update_product 	= "	UPDATE `tbl_products_master` SET `prod_model_number`='".$prod_model_number."',`prod_name`='".$prod_name."',`prod_slug`='".$slug."',";
		$sql_update_product 	.= " `prod_description`='".$prod_description."',`prod_orgid`='".$prod_orgid."',`prod_brandid`='".$prod_brandid."',";
		$sql_update_product 	.= " `prod_content`='".$prod_content."',";
		$sql_update_product 	.= " `prod_quantity`='".$prod_quantity."',`prod_min_quantity`='".$prod_min_quantity."',`prod_max_quantity`='".$prod_max_quantity."',`prod_list_price`='".$prod_list_price."',";
		$sql_update_product 	.= " `prod_recommended_price`='".$prod_recommended_price."',`prod_org_price`='".$prod_recommended_price."',`prod_meta_tags`='".$prod_meta_tags."',`prod_google_product_category`='".$prod_google_product_category."',";
		$sql_update_product 	.= " `prod_meta_description`='".$prod_meta_description."',`prod_meta_title`='".$prod_meta_title."',";
		$sql_update_product 	.= " `prod_modified_by`='".$uid."',`prod_modified`='".$datetime."',`prod_status`='".$prod_status."' WHERE `prod_id` = '".$prod_id."' ";
		
		$result_update_product 	= mysqli_query($db_con,$sql_update_product) or die(mysqli_error($db_con));
		if($result_update_product)
		{	
			
		     // done by satish for add and remove category 
			 $sql_update_default_status 	= "	UPDATE `tbl_product_cats` ";
			 $sql_update_default_status 	.= " 	SET `prodcat_prod_status`='".$prod_status."' ";
			 $sql_update_default_status 	.= " WHERE `prodcat_prodid`='".$prod_id."'";
			 $res_update_default_status 	= mysqli_query($db_con,$sql_update_default_status) or die(mysqli_error($db_con));
			 
			 // Update Prod Status in prod-filter table
			 $sql_update_prod_filt	= " UPDATE `tbl_product_filters` ";
			 $sql_update_prod_filt 	.= " 	SET `prodfilt_status`='".$prod_status."' ";
			 $sql_update_prod_filt 	.= " WHERE `prodfilt_prodid`='".$prod_id."' ";
			 $res_update_prod_filt	= mysqli_query($db_con, $sql_update_prod_filt) or die(mysqli_error($db_con));
			 
			 // Update Prod Status in prod-level table
			 $sql_update_prod_level	= " UPDATE `tbl_product_levels` ";
			 $sql_update_prod_level 	.= " 	SET `prodlevel_status`='".$prod_status."' ";
			 $sql_update_prod_level 	.= " WHERE `prodlevel_prodid`='".$prod_id."' ";
			 $res_update_prod_level	= mysqli_query($db_con, $sql_update_prod_level) or die(mysqli_error($db_con));
			 
			 // Update Prod Status in prod-specification table
			 $sql_update_prod_spec	= " UPDATE `tbl_products_specifications` ";
			 $sql_update_prod_spec 	.= " 	SET `prod_spec_status`='".$prod_status."' ";
			 $sql_update_prod_spec 	.= " WHERE `prod_spec_prodid`='".$prod_id."' ";
			 $res_update_prod_spec	= mysqli_query($db_con, $sql_update_prod_spec) or die(mysqli_error($db_con));
			 
			 $new_primary=0;
			 if($primary_cat ==0)
			 {
				 $new_primary=1;
			 }
			 else
			 {
			 $sql_update_default_status 	= "	UPDATE `tbl_product_cats` SET `prodcat_default`='0' WHERE `prodcat_prodid`='".$prod_id."'"; 
			 $res_update_default_status 	= mysqli_query($db_con,$sql_update_default_status) or die(mysqli_error($db_con));
			 
			 $sql_update_default_status 	= "	UPDATE `tbl_product_cats` SET `prodcat_default`='1' WHERE`prodcat_prodid`='".$prod_id."' and prodcat_catid='".$primary_cat."'"; 
			 $res_update_default_status 	= mysqli_query($db_con,$sql_update_default_status) or die(mysqli_error($db_con));	
			 
			 	 
			 }
			 
			 if($prod_catid !=""){
			 
			 $prod_catid = explode(',',$prod_catid);
			 
			 for($i=0;$i<sizeof($prod_catid);$i++)
			 {   
			     $default_status=0;
			     if($new_primary==1)
				 {    
					 if($i==0)
					 {    $sql_update_default_status 	= "	UPDATE `tbl_product_cats` SET `prodcat_default`='0' WHERE `prodcat_prodid`='".$prod_id."'"; 
			              $res_update_default_status 	= mysqli_query($db_con,$sql_update_default_status) or die(mysqli_error($db_con));
						  $default_status=1;
					 }
				 }
				 addCategory($prod_id,$prod_catid[$i],$prod_status,$default_status);
				 
			 }
			 
			 }// if prodcat end
			 if(!empty($remove_cat)){
			 foreach($remove_cat as $delcat_id)
			 {
				 $sql_del_prod_cat	= " DELETE FROM `tbl_product_cats` WHERE `prodcat_prodid`='".$prod_id."' and prodcat_catid='".$delcat_id."' ";
		         $res_del_prod_cat	= mysqli_query($db_con, $sql_del_prod_cat) or die(mysqli_error($db_con));
				 
			 }
			 }// not empty end
			 
			 
			 // add category end
		     /* code to update product Filters*/		
			$prodfilt_filtid_list	= array();
			$sql_check_filter 		= " SELECT * FROM `tbl_product_filters` WHERE `prodfilt_prodid` = '".$prod_id."' ";
			
			$result_check_filter	= mysqli_query($db_con,$sql_check_filter) or die(mysqli_error($db_con));
			$num_rows_check_filter	= mysqli_num_rows($result_check_filter);
					
			if($num_rows_check_filter == 0)
			{
				foreach($batch_filters as $prodfilt_filtid)
				{
					addFilters($prod_id,$prodfilt_filtid);
				}	
			}
			else
			{
				while($row_check_filter = mysqli_fetch_array($result_check_filter))
				{
					$filter_data = $row_check_filter['prodfilt_filtid_parent'].":".$row_check_filter['prodfilt_filtid_child'].":".$row_check_filter['prodfilt_filtid_sub_child'];					
					array_push($prodfilt_filtid_list,$filter_data);	
				}
					
				foreach($batch_filters as $prodfilt_filtid_user)
				{	
					
					if(in_array($prodfilt_filtid_user,$prodfilt_filtid_list))
					{	
						$data  = updateFilters($prod_id,$prodfilt_filtid_user,"1");
					}
					else
					{
						addFilters($prod_id,$prodfilt_filtid_user);				
					}					
				}
				
				foreach($prodfilt_filtid_list as $prodfilt_filtid_db)
				{
					if(!(in_array($prodfilt_filtid_db,$batch_filters)))
					{
						updateFilters($prod_id,$prodfilt_filtid_db,"0");
					}
				}
			}
			
			/* code to update product Filters*/					
			/* code to update product Levels*/				
			$prodlevel_levelid_list	= array();
			$sql_check_levels 		= " SELECT * FROM `tbl_product_levels` WHERE `prodlevel_prodid` = '".$prod_id."' ";	
			$result_check_levels	= mysqli_query($db_con,$sql_check_levels) or die(mysqli_error($db_con));
			$num_rows_check_levels	= mysqli_num_rows($result_check_levels);	
			if($num_rows_check_levels == 0)
			{
				foreach($batch_levels as $prodlevel_levelid)
				{
					addLevels($prod_id,$prodlevel_levelid);
				}		
			}
			else
			{					
				while($row_check_level = mysqli_fetch_array($result_check_levels))
				{
					$level_data = $row_check_level['prodlevel_levelid_parent'].":".$row_check_level['prodlevel_levelid_child'];												
					array_push($prodlevel_levelid_list,$level_data);	
				}
																	
				foreach($batch_levels as $prodlevel_levelid_user)
				{
					if(in_array($prodlevel_levelid_user,$prodlevel_levelid_list))
					{
						updateLevels($prod_id,$prodlevel_levelid_user,"1");
					}
					else
					{
						
						addLevels($prod_id,$prodlevel_levelid_user);				
					}
				}
				
				foreach($prodlevel_levelid_list as $prodlevel_levelid_db)
				{
					if(!(in_array($prodlevel_levelid_db,$batch_levels)))
					{
						updateLevels($prod_id,$prodlevel_levelid_db,"0");
					}
				}
			}				
			/* code to update product Levels*/								
			$response_array = array("Success"=>"Success","resp"=>"Update Succesfull");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
		}	
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);			}
/* Load product table information with pagination*/
if((isset($obj->change_product_status)) == "1" && isset($obj->change_product_status))
{
	$prod_id				= mysqli_real_escape_string($db_con,$obj->prod_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();		
	$sql_update_status 		= " UPDATE `tbl_products_master` SET `prod_status`= '".$curr_status."' ,`prod_modified` = '".$datetime."' ,`prod_modified_by` = '".$uid."' WHERE `prod_id` = '".$prod_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	if($result_update_status)
	{
		$response_array = array("Success"=>"Success","resp"=>$sql_update_status."Status Updated Successfully.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
	}		
	echo json_encode($response_array);	}
/* Load product table information with pagination*/
if((isset($obj->change_product_star_status)) == "1" && isset($obj->change_product_star_status))
{
	$prod_id				= mysqli_real_escape_string($db_con,$obj->prod_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();		
//	$sql_get_prod_details	= " SELECT * FROM `tbl_products_master` WHERE prod_catid = (SELECT prod_catid FROM tbl_products_master WHERE `prod_id` = '".$prod_id."' )";
//	$result_get_prod_details= mysqli_query($db_con,$sql_get_prod_details) or die(mysqli_error($db_con));
//	$num_rows_get_prod_details= mysqli_num_rows($result_get_prod_details);
//	if($num_rows_get_prod_details < 16)
//	{
		$sql_update_star_status = " UPDATE `tbl_products_master` SET `prod_star_status`= '".$curr_status."' ,`prod_modified` = '".$datetime."' ,`prod_modified_by` = '".$uid."' WHERE `prod_id` = '".$prod_id."' ";
		$result_update_star_status= mysqli_query($db_con,$sql_update_star_status) or die(mysqli_error($db_con));
		if($result_update_star_status)
		{
			$response_array = array("Success"=>"Success","resp"=>"Star Status Updated Successfully.");
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Star Status Update Failed.");
		}				
//	}
//	else
//	{
//		$response_array = array("Success"=>"fail","resp"=>"Only 15 Products can be STAR for a Single Category");
//	}
	echo json_encode($response_array);	}
/*Update product Title Slug or Google Product Category*/
if((isset($obj->change_product_data)) == "1" && isset($obj->change_product_data))
{ 
	$response_array = array();
	$prod_data_to_change		= mysqli_real_escape_string($db_con,$obj->prod_data_to_change);
	$change_type				= mysqli_real_escape_string($db_con,$obj->change_type);	
			
	$sql_update_products 		= " UPDATE `tbl_products_master` SET `prod_modified` = '".$datetime."' ,`prod_modified_by` = '".$uid."' , ";
	if($change_type == 1) // update payment mode with org id
	{
		$org_id						= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
		$sql_update_products 		.= " `prod_payment_mode` = '".$prod_data_to_change."' where  `prod_orgid` = '".$org_id."' ";
	}
	elseif($change_type == 2) // update payment mode with prod id
	{
		$prod_id					= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
		$sql_update_products 		.= " `prod_payment_mode` = '".$prod_data_to_change."' where `prod_id` = '".$prod_id."' ";
	}
	elseif($change_type == 3)// update product title 
	{
		$prod_id					= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
		$sql_update_products 		.= " `prod_title`= '".$prod_data_to_change."' where `prod_id` = '".$prod_id."' ";						
	}
	elseif($change_type == 4)// update product slug 
	{
		$prod_id					= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
		$sql_update_products 		.= " `prod_slug`= '".$prod_data_to_change."' where `prod_id` = '".$prod_id."' ";
	}		
	elseif($change_type == 5)// update prod_google_product_category 
	{
		$prod_id					= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
		$sql_update_products 		.= " `prod_google_product_category` = '".$prod_data_to_change."' where `prod_id` = '".$prod_id."' ";						
	}	
	elseif($change_type == 6)// update product returnable for org id
	{
		$org_id						= mysqli_real_escape_string($db_con,$obj->org_prod_id);
		$sql_update_products 		.= " `prod_returnable`= '".$prod_data_to_change."' where `prod_orgid` = '".$org_id."' ";
	}	
	elseif($change_type == 7)// update product returnable for prod id
	{
		$prod_id					= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
		$sql_update_products 		.= "  `prod_returnable`= '".$prod_data_to_change."' where `prod_id` = '".$prod_id."' ";						
	}	
	elseif($change_type == 8)// update product cash on delivery status for org id
	{
		/*$response_array = array("Success"=>"fail","resp"=>$change_type);	
	echo json_encode($response_array);exit();*/	
		$org_id					= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
		$sql_update_products 		.= "  `prod_cod_status`= '".$prod_data_to_change."' where `prod_orgid` = '".$org_id."' ";						
	}	
	elseif($change_type == 9)// update product product quantity for org id
	{
							/*$response_array = array("Success"=>"fail","resp"=>$prod_data_to_change);	
						echo json_encode($response_array);exit();*/	
							if($prod_data_to_change==2)
							{
							   $prod_quentity=0;
							}
							else
							{
								$prod_quentity = '10';
						/*		$response_array = array("Success"=>"fail","resp"=>$prod_quentity.'HI THiS IS TESt');	
						echo json_encode($response_array);exit();*/
							}
							$org_id					= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
							$sql_update_products 		.= " `prod_quantity_status`= '".$prod_data_to_change."',`prod_quantity`= '".$prod_quentity."' where 		`prod_orgid` = '".$org_id."' ";						
						
	}							
	$result_get_update_org_products = mysqli_query($db_con,$sql_update_products) or die(mysqli_error($db_con));					
	if($result_get_update_org_products)
	{
				
		if($change_type == 9)
		{
		  $sql_update_oraganisation 	= " UPDATE `tbl_oraganisation_master` SET `org_modified` = '".$datetime."' ,`org_modified_by` = '".$uid."' , ";
		  $org_id						= mysqli_real_escape_string($db_con,$obj->org_prod_id);		
		  $sql_update_oraganisation   .= " `org_quantity_status`= '".$prod_data_to_change."' where `org_id` = '".$org_id."' ";
		  $result_get_update_oraganisation = mysqli_query($db_con,$sql_update_oraganisation) or die(mysqli_error($db_con));	
		}
		$response_array = array("Success"=>"Success","resp"=>"Product Updated Success.");						
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Product Update Fail");						
	}					
	echo json_encode($response_array);	
}/*Update product Title ,Slug,returnable,payment mode or Google Product Category*/
/*Update Bulk Google Product Category*/	
if(isset($_POST['excel_prod_google_product_category']) && $_POST['excel_prod_google_product_category'] == "1")
{
	$date 					= date_create();
	$path 					= 'uploadedExcel/Google_Product_Category/'.date_format($date, 'U');
	mkdir($path);	
	$sourcePath 			= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 			= $path."/".$_FILES['file']['name']; // Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;

	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
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
	//echo json_encode($arrayCount);exit();
	if(strcmp($inputFileName, "")!==0)
	{
		for($i=2;$i<=$arrayCount;$i++)
		{
			$prod_id						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"]));							
			$prod_google_product_category	= htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["B"]))), ENT_HTML5);
			$sql_update_data 				= " UPDATE `tbl_products_master` SET `prod_google_product_category`= '".$prod_google_product_category."' ";
			$sql_update_data 				.= ",`prod_modified` = '".$datetime."' ,`prod_modified_by` = '".$uid."' WHERE `prod_id` = '".$prod_id."' ";
			$result_update_data 			= mysqli_query($db_con,$sql_update_data) or die(mysqli_error($db_con));
		}
	}		
	$response_array = array("Success"=>"Success","resp"=>"Google product category successfully uploaded.");
	echo json_encode($response_array);		
}
/*Update Bulk Google Product Category*/		
if((isset($obj->delete_product)) == "1" && isset($obj->delete_product))
{
	$response_array = array();		
	$ar_prod_id 	= $obj->batch_prod;
	$del_flag 		= 0; 
	foreach($ar_prod_id as $prod_id)	
	{
		$sql_del_prod_spec		= " DELETE FROM `tbl_products_specifications` WHERE `prod_spec_prodid`='".$prod_id."' ";
		$res_del_prod_spec		= mysqli_query($db_con, $sql_del_prod_spec) or die(mysqli_error($db_con));
		
		 $sql_del_prod_cat	= " DELETE FROM `tbl_product_cats` WHERE `prodcat_prodid`='".$prod_id."'";
		 $res_del_prod_cat	= mysqli_query($db_con, $sql_del_prod_cat) or die(mysqli_error($db_con));

		$sql_del_prod_branch	= " DELETE FROM `tbl_products_branch` WHERE `branch_prod_productid`='".$prod_id."' ";
		$res_del_prod_branch	= mysqli_query($db_con, $sql_del_prod_branch) or die(mysqli_error($db_con));

		$sql_del_prod_image		= " DELETE FROM `tbl_products_images` WHERE `prod_img_prodid`='".$prod_id."' ";
		$res_del_prod_image		= mysqli_query($db_con, $sql_del_prod_image) or die(mysqli_error($db_con));

		$sql_del_prod_filters	= " DELETE FROM `tbl_product_filters` WHERE `prodfilt_prodid`='".$prod_id."' ";
		$res_del_prod_filters	= mysqli_query($db_con, $sql_del_prod_filters) or die(mysqli_error($db_con));

		$sql_del_prod_levels	= " DELETE FROM `tbl_product_levels` WHERE `prodlevel_prodid`='".$prod_id."' ";
		$res_del_prod_levels	= mysqli_query($db_con, $sql_del_prod_levels) or die(mysqli_error($db_con));
		
		$sql_delete_product		= " DELETE FROM `tbl_products_master` WHERE `prod_id` = '".$prod_id."' ";
		$result_delete_product	= mysqli_query($db_con,$sql_delete_product) or die(mysqli_error($db_con));			
		if($result_delete_product && $res_del_prod_spec)
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
	echo json_encode($response_array);	}
if((isset($obj->load_error)) == "1" && isset($obj->load_error))
{
	$start_offset   = 0;
	
	$page 			= $obj->page1;	
	$per_page		= $obj->row_limit1;
	$search_text	= $obj->search_text1;	
	$cat_parent		= $obj->cat_parent1;
	$response_array = array();	
	//echo json_encode("1");exit();
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, ";
		$sql_load_data  .= " `error_created_by`, `error_modified`, `error_modified_by`, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_created_by) as created_by_name, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_modified_by) as modified_by_name ";
		$sql_load_data  .= " FROM `tbl_error_data`  ";
		$sql_load_data  .= " WHERE error_module_name='product' ";
		//echo json_encode($uid);
		//$response_array = array("Success"=>"fail","resp"=>$sql_load_data);	
		//echo json_encode($response_array);
		//exit();
		
		if(strcmp($utype,'1')!==0)
		{
			if($utype)
			{
				$sql_load_data  .= " AND (error_orgid='".$tbl_users_owner."' OR error_created_by='".$uid."') ";
			}
			else
			{
				$sql_load_data  .= " AND error_created_by='".$uid."' ";
			}			
		}
		
		if($search_text != "")
		{
			$sql_load_data .= " AND (error_data LIKE '%".$search_text."%' or error_module_name LIKE '%".$search_text."%' ";
			//$sql_load_data .= " or name_created_by like '%".$search_text."%' or name_modified_by like '%".$search_text."%' ";	
			$sql_load_data .= " or error_created like '%".$search_text."%' or error_modified like '%".$search_text."%') ";	
		}
		
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
		if(strcmp($data_count,"0") !== 0)
		{		
			$prod_data = "";	
			$prod_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$prod_data .= '<thead>';
    	  	$prod_data .= '<tr>';
         	$prod_data .= '<th>Sr. No.</th>';
			$prod_data .= '<th>Product Model Number</th>';			
			$prod_data .= '<th>Product Name</th>';
			$prod_data .= '<th>Organisation</th>';
			$prod_data .= '<th>Brand</th>';
		//	$prod_data .= '<th>Category</th>';
			//$prod_data .= '<th>Sub-Category</th>';
			$prod_data .= '<th>Created</th>';
			$prod_data .= '<th>Created By</th>';
			$prod_data .= '<th>Modified</th>';
			$prod_data .= '<th>Modified By</th>';
			$prod_data .= '<th>Edit</th>';			
			$prod_data .= '<th>
							<div class="center-text">
								<input type="button"  value="Delete" onclick="multipleDelete_error();" class="btn-danger"/>
							</div>
						</th>';
          	$prod_data .= '</tr>';
      		$prod_data .= '</thead>';
      		$prod_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_prod_rec	= json_decode($row_load_data['error_data']);
				$er_prod_model_number	= $get_prod_rec->prod_model_number;		
				$er_prod_name	= $get_prod_rec->prod_name;
				$er_prod_org	= $get_prod_rec->prod_orgid;
				$er_prod_brand	= $get_prod_rec->prod_brandid;
				$er_prod_cat	= $get_prod_rec->prod_catid;
				$er_prod_subcat	= $get_prod_rec->prod_subcatid;
				
				$prod_data .= '<tr>';				
				$prod_data .= '<td>'.++$start_offset.'</td>';
				$prod_data .= '<td>'.$er_prod_model_number.'</td>';
				$prod_data .= '<td>';
				$sql_chk_name_already_exist	= " SELECT `ind_name` FROM `tbl_industry` WHERE `ind_name`='".$er_prod_name."' ";
				$res_chk_name_already_exist = mysqli_query($db_con, $sql_chk_name_already_exist) or die(mysqli_error($db_con));
				$num_chk_name_already_exist = mysqli_num_rows($res_chk_name_already_exist);
				//echo json_encode($er_prod_subcat);exit();	
				if(strcmp($num_chk_name_already_exist,"0")===0)
				{
					$prod_data .= $er_prod_name;
				}
				else
				{
					$prod_data .= '<span style="color:#E63A3A;">'.$er_prod_name.' [Already Exist]</span>';
				}
				$prod_data .= '</td>';
				$prod_data .= '<td>'.$er_prod_org.'</td>';
				$prod_data .= '<td>'.$er_prod_brand.'</td>';
				//$prod_data .= '<td>'.$er_prod_cat.'</td>';
				//$prod_data .= '<td>'.$er_prod_subcat.'</td>';
				$prod_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$prod_data .= '<td>'.$row_load_data['created_by_name'].'</td>';
				$prod_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$prod_data .= '<td>'.$row_load_data['modified_by_name'].'</td>';
				$prod_data .= '<td class="center-text">';
				$prod_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreProduct(this.id,\'error\');"></td>';				
				$prod_data .= '<td><div class="controls" align="center">';
				$prod_data .= '<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$prod_data .= '<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$prod_data .= '</div></td>';										
	          	$prod_data .= '</tr>';															
			}	
      		$prod_data .= '</tbody>';
      		$prod_data .= '</table>';	
			$prod_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$prod_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified for Products error");
	}
	
	echo json_encode($response_array);}
if((isset($obj->delete_prod_error)) == "1" && isset($obj->delete_prod_error))
{
	$ar_prod_id 		= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_prod_id as $prod_id)	
	{
		$sql_delete_prod_error	= " DELETE FROM `tbl_error_data` WHERE `error_id` = '".$prod_id."' ";
		
		$result_delete_prod_error= mysqli_query($db_con,$sql_delete_prod_error) or die(mysqli_error($db_con));			
		if($result_delete_prod_error)
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
	
	echo json_encode($response_array);	}
if((isset($obj->change_sub_section)) == "1" && isset($obj->change_sub_section))
{
	$section_id			= mysqli_real_escape_string($db_con,$obj->section_id);
	$section_type		= mysqli_real_escape_string($db_con,$obj->section_type);
	if($section_id != "" && $section_type != "")
	{
		$data			= "";
		if($section_type == "category")
		{
			$data		= '<option value="">Select Child-Category</option>';						
			$sql_get_child_category 		= "SELECT `cat_id`, `cat_name`, `cat_type` FROM `tbl_category` WHERE `cat_type` = '".$section_id."' ";
			$result_get_child_category 		= mysqli_query($db_con,$sql_get_child_category) or die(mysqli_error($db_con));			
			while($row_get_child_category 	= mysqli_fetch_array($result_get_child_category))
			{
				$data	.= '<option value="'.ucwords($row_get_child_category['cat_id']).'">'.ucwords($row_get_child_category['cat_name']).'</option>';
			}
		}
		elseif($section_type == "level")
		{
			$data		= '<option value="">Select Child-Level</option>';									
			$sql_get_child_level	 		= " SELECT `cat_id`, `cat_name`, `cat_type` FROM `tbl_level` WHERE `cat_type` = '".$section_id."' ";
			$result_get_child_level 		= mysqli_query($db_con,$sql_get_child_level) or die(mysqli_error($db_con));			
			while($row_get_child_level 		= mysqli_fetch_array($result_get_child_level))
			{
				$data	.= '<option value="'.ucwords($row_get_child_level['cat_id']).'">'.ucwords($row_get_child_level['cat_name']).'</option>';
			}			
		}
		elseif($section_type == "filter")
		{		
			$section_data		= explode(":",$section_id);
			$section_parenty_id = $section_data[0];
			$section_child_id 	= $section_data[1];
			if($section_child_id == 'child')
			{
				$data		= '<option value="">Select Child-Filter</option>';				
			}
			elseif($section_child_id != 'child')
			{
				$data		= '<option value="">Select Sub-Filter</option>';								
			}
			$sql_get_filter 	= " SELECT `filt_id`, `filt_name`, `filt_type`, `filt_sub_child` FROM `tbl_filters` WHERE `filt_type` = '".$section_parenty_id."' and `filt_sub_child` = '".$section_child_id."' ";
			$result_get_filter 	= mysqli_query($db_con,$sql_get_filter) or die(mysqli_error($db_con));
			while($row_get_filter = mysqli_fetch_array($result_get_filter))
			{
				$data	.= '<option value="'.$row_get_filter['filt_type'].':'.$row_get_filter['filt_id'].'">'.ucwords($row_get_filter['filt_name']).'</option>';				
			}			
		}
		else
		{
			$data	.= 'No proper Request parameter';
		}
		$response_array = array("Success"=>"Success","resp"=>$data);		
	}
	else
	{
		//$response_array = array("Success"=>"fail","resp"=>"No Request parameter");
	}
	echo json_encode($response_array);}
/* Product Operations */
/* Product Specification Operations */
function insertProductSpecification($prod_spec_prodid,$prod_spec_specid,$prod_spec_value,$prod_spec_status)
{
	global $db_con, $datetime;
	global $uid;
	global $obj;
	$sql_check_spec = "SELECT * FROM tbl_products_specifications WHERE prod_spec_prodid = '".$prod_spec_prodid."' AND prod_spec_specid = '".$prod_spec_specid."' ";
	$result_check_spec = mysqli_query($db_con,$sql_check_spec) or die(mysqli_error($db_con));
	$num_rows_check_spec = mysqli_num_rows($result_check_spec);			
	if($num_rows_check_spec == 0)
	{
		$sql_last_rec 		= "SELECT * FROM tbl_products_specifications ORDER by prod_spec_id desc LIMIT 0,1";
		$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$prod_spec_id 		= 1;				
		}
		else
		{
			$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
			$prod_spec_id 		= $row_last_rec['prod_spec_id']+1;
		}			
		$sql_spec_insert = " INSERT INTO `tbl_products_specifications`(`prod_spec_id`, `prod_spec_prodid`, `prod_spec_specid`, `prod_spec_value`, `prod_spec_status`, `prod_spec_created`, ";
		$sql_spec_insert .= " `prod_spec_created_by`) VALUES ('".$prod_spec_id."','".$prod_spec_prodid."','".$prod_spec_specid."','".$prod_spec_value."','".$prod_spec_status."','".$datetime."','".$uid."')";
		$result_spec_insert = mysqli_query($db_con,$sql_spec_insert) or die(mysqli_error($db_con));	
		if($result_spec_insert)
		{	
			if(isset($obj->error_id) && (isset($obj->insert_product)) != "")			
			{
				$response_array = errorDataDelete($obj->error_id);
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully");					
			}													
		}
		else
		{
			$response_array = array("Success"=>"Fail","resp"=>"Specifications Not Added.");			
		}								
	}
	else
	{	
		$response_array = array("Success"=>"Fail","resp"=>"Specifications already Exist.");			
	}
	return $response_array;}
if((isset($obj->insert_prod_specification)) == "1" && isset($obj->insert_prod_specification))
{
	$prod_spec_prodid		= mysqli_real_escape_string($db_con,$obj->prod_spec_prodid);
	$prod_spec_specid		= mysqli_real_escape_string($db_con,$obj->prod_spec_specid);
	$prod_spec_value		= mysqli_real_escape_string($db_con,$obj->prod_spec_value);	
	$prod_spec_status		= 1;//mysqli_real_escape_string($db_con,$obj->prod_spec_status);
	
	$response_array = array();	
	if($prod_spec_prodid != "" || $prod_spec_specid != "" || $prod_spec_value != "")
	{
		$response_array = insertProductSpecification($prod_spec_prodid,$prod_spec_specid,$prod_spec_value,$prod_spec_status);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		}
if((isset($obj->update_prod_specification)) == "1" && isset($obj->update_prod_specification))
{
	$prod_spec_id			= mysqli_real_escape_string($db_con,$obj->prod_spec_id);	
	$prod_spec_prodid		= mysqli_real_escape_string($db_con,$obj->prod_spec_prodid);
	$prod_spec_specid		= mysqli_real_escape_string($db_con,$obj->prod_spec_specid);
	$prod_spec_value		= mysqli_real_escape_string($db_con,$obj->prod_spec_value);	
	$prod_spec_status		= 1;//mysqli_real_escape_string($db_con,$obj->prod_spec_status);
	
	$response_array = array();	
	if($prod_spec_prodid != "" || $prod_spec_specid != "" || $prod_spec_value != "")
	{		
		$sql_update_prod_spec 		= " UPDATE `tbl_products_specifications` SET `prod_spec_specid`='".$prod_spec_specid."',`prod_spec_value`='".$prod_spec_value."',";
		$sql_update_prod_spec 		.= " `prod_spec_status`='".$prod_spec_status."',`prod_spec_modified`='".$datetime."',`prod_spec_modified_by`='".$uid."' WHERE prod_spec_id = '".$prod_spec_id."' ";
		$result_update_prod_spec 	= mysqli_query($db_con,$sql_update_prod_spec) or die(mysqli_error($db_con));
		if($result_update_prod_spec)
		{
			$response_array = array("Success"=>"Success","resp"=>"Data Updated");				
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Not Updated");				
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		}
if((isset($obj->load_add_prod_spec_part)) == "1" && isset($obj->load_add_prod_spec_part))
{
	$prod_id 	= $obj->prod_id;
	$req_type 	= $obj->req_type;
	$add_flag 	= 0;
	$response_array = array();
	if($req_type != "")
	{
		if($prod_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$prod_id."' "; // this ind_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_prod_data		= json_decode($row_error_data['error_data']);
		}
		else if(($prod_id != "" && $req_type == "edit") || ($prod_id != "" && $req_type == "view"))
		{
			$sql_prod_data 		= "Select * from tbl_products_specifications where prod_spec_id = '".$prod_id."' ";
			$result_prod_data 	= mysqli_query($db_con,$sql_prod_data) or die(mysqli_error($db_con));
			$row_prod_data		= mysqli_fetch_array($result_prod_data);		
		}	
		if($req_type == "add")
		{
			$sql_prod_data 		= "Select * from tbl_products_specifications where prod_spec_prodid = '".$prod_id."' ";
			$result_prod_data 	= mysqli_query($db_con,$sql_prod_data) or die(mysqli_error($db_con));
			$row_prod_data		= mysqli_fetch_array($result_prod_data);		
			$sql_get_spec 		= " SELECT DISTINCT `spec_id`, `spec_name` FROM `tbl_specifications_master` where `spec_id` NOT IN ";
			$sql_get_spec 		.= " (SELECT prod_spec_specid FROM tbl_products_specifications WHERE prod_spec_prodid = '".$prod_id."' ) ";				
		}
		else
		{
			$sql_get_spec		= " SELECT `spec_id`, `spec_name` FROM `tbl_specifications_master` ";
		}
		$result_get_spec		= mysqli_query($db_con,$sql_get_spec) or die(mysqli_error($db_con));
		$num_rows_get_spec 		= mysqli_num_rows($result_get_spec);	
		if($num_rows_get_spec == 0)
		{
			$add_flag = 1;	
		}
		else
		{
			$add_flag = 0;
		}	
		if($req_type == "add" && $add_flag == 1)			
		{
			$data	= '<div style="text-align:center;padding:2%;font-weight:bold;color:#F00;">';
			$data	.= '<h4>More Specification\'s are not Available for this product</h4></div>';
		}
		else
		{
			$data 	= '';
			if($prod_id != "" && $req_type == "edit")
			{
				$data .= '<input type="hidden" id="prod_spec_id" value="'.$row_prod_data['prod_spec_id'].'">';
			}
			elseif($prod_id != "" && $req_type == "error")
			{
				$data .= '<input type="hidden" id="error_id" value="'.$prod_id.'">';
			}				
			$data 	.= '<div class="control-group">';
			$data 	.= '<label class="control-label" >Select Specification</label>';						
			$data 	.= '<div class="controls">';
			$data	.= '<select name="prod_spec_specid" id="prod_spec_specid" class="select2-me input-large" data-rule-required="true">';
			$data	.= '<option value="">Select Specification</option>';													
			while($row_get_spec = mysqli_fetch_array($result_get_spec))
			{															
				$data.= '<option value="'.$row_get_spec['spec_id'].'" ';
				if($req_type == "edit")
				{
					if($row_prod_data['prod_spec_specid'] == $row_get_spec['spec_id'])
					{
						$data .= 'selected';
					}
				}
				$data .= '>'.$row_get_spec['spec_name'].'</option>';
			}
			$data .= '</select>';
			$data .= '</div>';
			$data .= '</div>';					
			$data .= '<div class="control-group">';
			$data .= '<label class="control-label" >Value</label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" name="prod_spec_value" class="input-medium" data-rule-required="true" ';
			
			
			if($prod_id != "" && $req_type == "add")
			{
				$data .= 'id="prod_spec_value"'; 
			}
			if($prod_id != "" && $req_type == "edit")
			{
				$data .= 'id="prod_spec_value2" value="'.$row_prod_data['prod_spec_value'].'"'; 
			}
			elseif($prod_id != "" && $req_type == "error")
			{
				$data .= ' value="'.$row_prod_data->prod_spec_value.'"'; 			
			}
			elseif($prod_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_prod_data['prod_spec_value'].'" disabled'; 				
			}		
			$data .= '/>';
			$data .= '</div>';
			$data .= '</div>';		
			$data .= '</div>';		
			$data .= '<div class="form-actions">';
			if($req_type == "add")
			{
				$data .= '<button type="submit" name="reg_submit_add_prod_spec" class="btn-success">Add</button>';				
			}
			elseif($req_type == "edit")
			{
				$data .= '<button type="submit" name="reg_submit_edit_prod_spec" class="btn-success">Update</button>';				
			}
			elseif($req_type == "error")
			{
				$data .= '<button type="submit" name="reg_submit_error_prod_spec" class="btn-success">Update</button>';				
			}			
			$data .= '</div>';
			$data .= '<script type="text/javascript">';
			$data .= '$("#prod_spec_specid").select2();';
			$data .= '</script>';
		}
		$response_array = array("Success"=>"Success","resp"=>$data);		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Not received");		
	}
	echo json_encode($response_array);	}
if((isset($obj->load_prod_specification)) == "1" && isset($obj->load_prod_specification))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= trim(mysqli_real_escape_string($db_con,$obj->page_no_prod_spec));	
	$per_page		= trim(mysqli_real_escape_string($db_con,$obj->row_limit_prod_spec));
	$search_text	= trim(mysqli_real_escape_string($db_con,$obj->search_text_prod_spec));
	$prod_id		= trim(mysqli_real_escape_string($db_con,$obj->prod_spec_prodid));
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
		
		$sql_load_data  = " SELECT tps.*, tsm.*,";
		$sql_load_data  .= " (SELECT prod_name FROM `tbl_products_master` WHERE prod_id =tps.prod_spec_prodid) as prod_name,";
		//$sql_load_data  .= " INNER JOIN tbl_oraganisation_master tom ON tpm.prod_orgid = tom.org_id,";		
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id =tps.prod_spec_created_by) as created_name,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id =tps.prod_spec_modified_by) as modified_name";
		$sql_load_data  .= "  FROM `tbl_products_specifications` as tps INNER JOIN tbl_specifications_master as tsm ON tps.prod_spec_specid = tsm.spec_id WHERE 1=1";
		if($search_text != "")
		{
			$sql_load_data .= " AND (tsm.spec_name like '%".$search_text."%' OR tps.prod_spec_id like '%".$search_text."%') ";
			//$sql_load_data .= " AND tps.prod_spec_id = '".$prod_spec_id."' ";	
		}
		if($prod_id != "")
		{
			$sql_load_data .= " AND prod_spec_prodid = '".$prod_id."' ";			
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$prod_data = "";	
			$prod_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$prod_data .= '<thead>';
    	  	$prod_data .= '<tr>';
         	$prod_data .= '<th>Sr. No.</th>';
         	$prod_data .= '<th>Id.</th>';						
			$prod_data .= '<th>Product Name</th>';
			$prod_data .= '<th>Specification</th>';			
			$prod_data .= '<th>Value</th>';			
			$prod_data .= '<th>Created By</th>';
			$prod_data .= '<th>Created</th>';
			$prod_data .= '<th>Modified By</th>';
			$prod_data .= '<th>Modified</th>';	
			$dis = checkFunctionalityRight("view_products.php",3);
			if($dis)
			{					
				$prod_data .= '<th>Status</th>';						
			}
			$edit = checkFunctionalityRight("view_products.php",1);
			$edit = 1;
			if($edit)
			{			
				$prod_data .= '<th>Edit</th>';			
			}
			$del = checkFunctionalityRight("view_products.php",2);
			$del = 1;
			if($del)
			{			
				$prod_data .= '<th><div class="center-text"><input type="button"  value="Delete" onclick="deleteProductSpecification();" class="btn-danger"/></div></th>';
			}
          	$prod_data .= '</tr>';
      		$prod_data .= '</thead>';
      		$prod_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$prod_data .= '<tr>';				
				$prod_data .= '<td>'.++$start_offset.'</td>';
				$prod_data .= '<td>'.$row_load_data['prod_spec_id'].'</td>';					
				$prod_data .= '<td>'.$row_load_data['prod_name'].'</td>';
				$prod_data .= '<td>'.$row_load_data['spec_name'].'</td>';
				$prod_data .= '<td>'.$row_load_data['prod_spec_value'].'</td>';									
				$prod_data .= '<td>'.$row_load_data['created_name'].'</td>';
				$prod_data .= '<td>'.$row_load_data['prod_spec_created'].'</td>';
				$prod_data .= '<td>'.$row_load_data['modified_name'].'</td>';
				$prod_data .= '<td>'.$row_load_data['prod_spec_modified'].'</td>';
				$dis = checkFunctionalityRight("view_products.php",3);
				if($dis)
				{					
					$prod_data .= '<td class="center-text">';	

					if($row_load_data['prod_spec_status'] == 1)
					{
						$prod_data .= '<input type="button" value="Active" id="'.$row_load_data['prod_spec_id'].'" class="btn-success" onclick="changeProductSpecificationStatus(this.id,0);">';
					}
					else
					{
						$prod_data .= '<input type="button" value="Inactive" id="'.$row_load_data['prod_spec_id'].'" class="btn-danger" onclick="changeProductSpecificationStatus(this.id,1);">';
					}
					$prod_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_products.php",1);
				$edit = 1;
				if($edit)
				{				
					$prod_data .= '<td class="center-text">';
					$prod_data .= '<input type="button" value="Edit" id="'.$row_load_data['prod_spec_id'].'" class="btn-warning" onclick="addMoreProductSpecification(this.id,\'edit\');"></td>';				
				}
				$del = checkFunctionalityRight("view_products.php",2);
				$del = 1;
				if($del)
				{					
					$prod_data .= '<td><div class="center-text">';
					$prod_data .= '<input type="checkbox" value="'.$row_load_data['prod_spec_id'].'" id="batch_prod_spec'.$row_load_data['prod_spec_id'].'" name="batch_prod_spec'.$row_load_data['prod_spec_id'].'" class="css-checkbox batch_prod_spec">';
					$prod_data .= '<label for="batch_prod_spec'.$row_load_data['prod_spec_id'].'" class="css-label"></label>';				
					$prod_data .= '</div></td>';										
				}
	          	$prod_data .= '</tr>';															
			}	
      		$prod_data .= '</tbody>';
      		$prod_data .= '</table>';	
			$prod_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$prod_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified for product Specification");
	}
	echo json_encode($response_array);	}
if((isset($obj->change_prod_specification_status)) == "1" && isset($obj->change_prod_specification_status))
{
	$prod_spec_id			= mysqli_real_escape_string($db_con,$obj->prod_spec_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();		
	$sql_update_status 		= " UPDATE `tbl_products_specifications` SET `prod_spec_status`= '".$curr_status."' ,`prod_spec_modified` = '".$datetime."' ,`prod_spec_modified_by` = '".$uid."' WHERE `prod_spec_id` = '".$prod_spec_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	if($result_update_status)
	{
		$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
	}		
	echo json_encode($response_array);	}
if((isset($obj->delete_product_specification)) == "1" && isset($obj->delete_product_specification))
{
	$response_array = array();		
	$ar_prod_id 	= $obj->batch_prod_spec;
	$del_flag 		= 0; 
	foreach($ar_prod_id as $prod_spec_id)	
	{
		$sql_del_prod_spec		= " DELETE FROM `tbl_products_specifications` WHERE `prod_spec_id`='".$prod_spec_id."' ";
		$res_del_prod_spec		= mysqli_query($db_con, $sql_del_prod_spec) or die(mysqli_error($db_con));		
		if($res_del_prod_spec)
		{
			$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");	
		}
	}
	echo json_encode($response_array);	}
/* Load Oreganisation wise Products on View Organisation Page/ levels and filters assignment */
if((isset($obj->loadOrgProduct)) == "1" && isset($obj->loadOrgProduct))
{
	$org_id			= mysqli_real_escape_string($db_con,$obj->org_id);
	$page 			= trim(mysqli_real_escape_string($db_con,$obj->page));	
	$row_limit		= trim(mysqli_real_escape_string($db_con,$obj->row_limit));	
	if($org_id == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Organisation Id Empty");
	}
	else
	{	
		$per_page		= $row_limit;
		if($page != "" && $per_page != "")	
		{
			$cur_page 		= $page;
			$page 	   	   	= $page - 1;
			$start_offset += $page * $per_page;
			$start 			= $page * $per_page;	
		}
		$sql_org_prod  		= " SELECT `prod_returnable`,`prod_payment_mode`,`prod_id`, `prod_name`, (SELECT org_name FROM `tbl_oraganisation_master` where org_id =`prod_orgid` ) as org_name,prod_org_price, ";
		$sql_org_prod  		.= " `prod_status` ,prod_orgid,prod_catid,prod_subcatid,prod_list_price,prod_recommended_price FROM `tbl_products_master` where `prod_orgid` = '".$org_id."' ";
		$data_count			= dataPagination($sql_org_prod,$per_page,$start,$cur_page);
		$sql_org_prod 		.=" LIMIT $start, $per_page ";
		$result_org_prod	= mysqli_query($db_con, $sql_org_prod) or die(mysqli_error($db_con));
		$num_rows_org_prod	= mysqli_num_rows($result_org_prod);
		if($num_rows_org_prod == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"Products not available for this Oraganisation".$sql_org_prod);
		}
		else
		{
			$data .= '<table id="tbl_user" class="table table-bordered dataTable">';
	     	$data .= '<thead>';
	      	$data .= '<tr>';
	       	$data .= '<th style="width:2%;text-align:center;">Sr. No.</th>';
			$data .= '<th style="width:10%;text-align:center;">Product Name';
			$data .= '<input type="checkbox" id="parent_chk" class="css-checkbox parent_org_prod_batch" onchange="childCheckUncheck(this.id,\'org_prod_batch\');">';
			$data .= '<label for="parent_chk" class="css-label"></label>All</th>';			
			$data .= '</th>';
			$data .= '<th style="width:20%;text-align:center;">Parent Level - Child Level</th>';						
			$data .= '<th style="width:20%;text-align:center;">Parent Filter - Child Filter</th>';									
			$data .= '<th style="width:2%;">&nbsp;</th>';
	    	$data .= '</thead>';
	    	$data .= '<tbody>';			
			while($row_org_prod = mysqli_fetch_array($result_org_prod))
			{
				/* Gathering Data for check and uncheck when previouslY ASSIGN*/						
				$row_get_filters_parent		= array();
				$row_get_filters_child		= array();	
				$row_get_filters_sub_child	= array();	
				$sql_get_filters	= "SELECT * FROM `tbl_product_filters` WHERE `prodfilt_prodid` = '".$row_org_prod['prod_id']."' and prodfilt_status = '1' ";
				$result_get_filters	= mysqli_query($db_con,$sql_get_filters) or die(mysqli_error($db_con));
				while($dump_row_get_filters	= mysqli_fetch_array($result_get_filters))
				{
					array_push($row_get_filters_parent,$dump_row_get_filters['prodfilt_filtid_parent']);
					array_push($row_get_filters_child,$dump_row_get_filters['prodfilt_filtid_child']);
					array_push($row_get_filters_sub_child,$dump_row_get_filters['prodfilt_filtid_sub_child']);									
				}
				$row_get_levels_parent	= array();
				$row_get_levels_child	= array();			
				$sql_get_levels		= "SELECT * FROM `tbl_product_levels` WHERE `prodlevel_prodid` = '".$row_org_prod['prod_id']."' and prodlevel_status = '1' ";
				$result_get_levels	= mysqli_query($db_con,$sql_get_levels) or die(mysqli_error($db_con));
				while($dump_row_get_levels	= mysqli_fetch_array($result_get_levels))
				{
					array_push($row_get_levels_parent,$dump_row_get_levels['prodlevel_levelid_parent']);
					array_push($row_get_levels_child,$dump_row_get_levels['prodlevel_levelid_child']);				
				}		
				/* Gathering Data for check and uncheck when previouslY ASSIGN*/						
	    	  	$data .= '<tr>';				
				$data .= '<td>'.++$start_offset.'</td>';				
				$data .= '<td>';
				$data .= '<div style="font-size:18px;padding:10px 0px;">'.ucwords($row_org_prod['prod_name']).'</div>';
				$data .= '<div><b>Prod id:</b>&nbsp;'.$row_org_prod['prod_id'].'</div>';				
				$data .= '<div><b>List price:</b>&nbsp;'.$row_org_prod['prod_list_price'].'</div>';
				$data .= '<div><b>Recommended price:</b>&nbsp;'.$row_org_prod['prod_recommended_price'].'</div>';
				$percentage 	= (($row_org_prod['prod_list_price'] - $row_org_prod['prod_recommended_price']) / $row_org_prod['prod_list_price']) * 100;
				$data .= '<div><b>Diccount On List Price:</b>&nbsp;'.round($percentage,2).'% OFF</div>';				
				$data .= '<div><b>Vendor price:</b>&nbsp;'.$row_org_prod['prod_org_price'].'</div>';								
				$data .= '<div><b>Payment Mode:</b>&nbsp;';
				if($row_org_prod['prod_payment_mode'] == 1)
				{
					$data .= 'Pay Online';
				}
				elseif($row_org_prod['prod_payment_mode'] == 2)
				{
					$data .= 'Cash On Delivery';					
				}
				elseif($row_org_prod['prod_payment_mode'] == 3)
				{
					$data .= 'Both Pay Online and Cash On Delivery';					
				}				
				$data .= '</div>';
				$data .= '<div><b>Product Returns:</b>&nbsp;';
				if($row_org_prod['prod_returnable'] == 1)
				{
					$data .= 'This product is returnable';
				}
				elseif($row_org_prod['prod_returnable'] == 0)
				{
					$data .= 'This product is non – returnable';					
				}
				$data .= '</div>';				
				$data .= '<div class="controls" align="center">';
				$data .= '<input type="checkbox" value="'.$row_org_prod['prod_id'].'" id="'.$row_org_prod['prod_id'].'" name="org_prod_batch" class="css-checkbox org_prod_batch">';
				$data .= '<label for="'.$row_org_prod['prod_id'].'" class="css-label">Assign Level and Filter</label></div></td>';				
				/* Organisation wise Level assignment*/									
				$data .= '<td>';
				//$data .= '<button value="prod_level_assign'.$row_org_prod['prod_id'].'" class="btn-warning" onclick="openCloseDiv(this.value);">Level Assign</button>';				
				$data .= '<div id="prod_level_assign'.$row_org_prod['prod_id'].'" style="max-height:266px;overflow:auto;width:100%;display:block;">';				
				$sql_get_parent_levels 			= " select * from tbl_level where cat_type = 'parent' and cat_name != 'none'";
				$result_get_parent_levels 		= mysqli_query($db_con,$sql_get_parent_levels) or die(mysqli_error($db_con));
				while($row_get_parent_levels 	= mysqli_fetch_array($result_get_parent_levels))
				{
					$data .= '<div style="float:left;border-bottom:1px solid #BBBBBB;margin:2px;padding:10px;width:100%;">';
					$data .= '<input type="checkbox" value="'.$row_get_parent_levels['cat_id'].'" id="'.$row_get_parent_levels['cat_id']."_".$row_org_prod['prod_id'].'level_parent" name="level_parent'."_".$row_org_prod['prod_id'].'" class="css-checkbox batch_levels levels_parent'."_".$row_org_prod['prod_id'].' "';
					//$data .= ' onchange="childCheckUncheck(this.id,\'levels_child'.$row_get_parent_levels['cat_id'].'\');"';
					if(in_array($row_get_parent_levels['cat_id'],$row_get_levels_parent))					
					{
						$data .= 'checked';
					}
					$data .= '>';					
					$data .= ucwords($row_get_parent_levels['cat_name']).'<label for="'.$row_get_parent_levels['cat_id']."_".$row_org_prod['prod_id'].'level_parent" class="css-label" ></label>';
					$sql_get_child_levels = " select * from tbl_level where cat_type = '".$row_get_parent_levels['cat_id']."' ";//and cat_name != 'none' ";
					$result_get_child_levels = mysqli_query($db_con,$sql_get_child_levels) or die(mysqli_error($db_con));			
					$data .= '<div style="margin:20px;">';			
					while($row_get_child_levels = mysqli_fetch_array($result_get_child_levels))
					{
						$data .= '<input type="checkbox" value="'.$row_get_child_levels['cat_id'].'" id="'.$row_get_child_levels['cat_id']."_".$row_org_prod['prod_id'].'level_child" name="'.$row_get_parent_levels['cat_id'].'level_child'."_".$row_org_prod['prod_id'].'" class="css-checkbox batch_levels levels_child'.$row_get_parent_levels['cat_id'].' "';
						if(in_array($row_get_child_levels['cat_id'],$row_get_levels_child))					
						{
							$data .= 'checked';
						}						
						$data .= '>';
						$data .= ucwords($row_get_child_levels['cat_name']).'<label for="'.$row_get_child_levels['cat_id']."_".$row_org_prod['prod_id'].'level_child" class="css-label"></label>';
					}
					$data .= '</div>';			
					$data .= '</div>';			
				}
				$data .= '</div></td>';	
				/* Organisation wise Level assignment*/					
				/* Organisation wise Filter assignment*/
				$data .= '<td><div style="max-height:266px;overflow:auto;">';
				$sql_get_parent_filters 		= " select * from tbl_filters where filt_type = 'parent' and filt_sub_child = 'parent' and filt_name != 'none' and filt_status = 1";
				$result_get_parent_filters		= mysqli_query($db_con,$sql_get_parent_filters) or die(mysqli_error($db_con));
				while($row_get_parent_filters 	= mysqli_fetch_array($result_get_parent_filters))
				{
					$data .= '<div style="float:left;border-bottom:1px solid #BBBBBB;margin:2px;padding:10px;width:100%;">';
					$data .= '<input type="checkbox" value="'.$row_get_parent_filters['filt_id'].'" id="'.$row_get_parent_filters['filt_id']."-".$row_org_prod['prod_id'].'filters_parent" name="filters_parent_'.$row_org_prod['prod_id'].'" class="css-checkbox batch_filters filters_parent"';		
					//$data .= ' onchange="childCheckUncheck(this.id,\'filters_child'.$row_get_parent_filters['filt_id'].'\');"';
					if(in_array($row_get_parent_filters['filt_id'],$row_get_filters_parent))					
					{
						$data .= 'checked';
					}
					$data .= '>';
					$data .= ucwords($row_get_parent_filters['filt_name']).'<label for="'.$row_get_parent_filters['filt_id']."-".$row_org_prod['prod_id'].'filters_parent" class="css-label" ></label>';						
					$sql_get_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' and  filt_sub_child = 'child' and filt_status = 1 and filt_name != 'none' ";//and cat_name != 'none' ";				
					$result_get_child_filters = mysqli_query($db_con,$sql_get_child_filters) or die(mysqli_error($db_con));			
					$data .= '<div style="padding:5px;">';			
					while($row_get_child_filters = mysqli_fetch_array($result_get_child_filters))
					{
						$data .= '<div style="float:left;border-bottom:1px solid #BBBBBB;margin:2px;padding:10px;width:100%;">';
						$data .= '<input type="checkbox" value="'.$row_get_child_filters['filt_id'].'" id="'.$row_get_child_filters['filt_id']."-".$row_org_prod['prod_id'].'filters_child" name="'.$row_get_parent_filters['filt_id'].'filter_child_'.$row_org_prod['prod_id'].'" class="css-checkbox batch_filters filters_child'.$row_get_parent_filters['filt_id'].'"';
						if(in_array($row_get_child_filters['filt_id'],$row_get_filters_child))					
						{
							$data .= 'checked';
						}						
						$data .= ' >';					
						$data .= ucwords($row_get_child_filters['filt_name']).'<label for="'.$row_get_child_filters['filt_id']."-".$row_org_prod['prod_id'].'filters_child" class="css-label"></label>';
						
						$sql_get_sub_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' and filt_sub_child = '".$row_get_child_filters['filt_id']."' and filt_status = 1 ";//and cat_name != 'none' ";				
						$result_get_sub_child_filters = mysqli_query($db_con,$sql_get_sub_child_filters) or die(mysqli_error($db_con));			
						$data .= '<div style="padding:5px;">';			
						while($row_get_sub_child_filters = mysqli_fetch_array($result_get_sub_child_filters))
						{
							$data .= '<div style="margin-left:5px;margin-bottom:5px;padding:5px;float:left;border-right:1px solid #BBBBBB;border-bottom:1px solid #BBBBBB;">';
							$data .= '<input type="checkbox" value="'.$row_get_sub_child_filters['filt_id'].'" id="'.$row_get_sub_child_filters['filt_id']."-".$row_org_prod['prod_id'].'filters_sub_child" name="'.$row_get_parent_filters['filt_id'].'_'.$row_get_child_filters['filt_id'].'filter_sub_child_'.$row_org_prod['prod_id'].'" class="css-checkbox batch_filters filters_sub_child'.$row_get_child_filters['filt_id'].'"';
							if(in_array($row_get_sub_child_filters['filt_id'],$row_get_filters_sub_child))					
							{
								$data .= 'checked';
							}						
							$data .= ' >';					
							$data .= ucwords($row_get_sub_child_filters['filt_name']).'<label for="'.$row_get_sub_child_filters['filt_id']."-".$row_org_prod['prod_id'].'filters_sub_child" class="css-label"></label>';
							$data .= '</div>';
						}						
						$data .= '<div style="clear:both;"></div>';
						$data .= '</div>';	
						$data .= '</div>';																	
					}
					//$data .= '<div style="clear:both;"></div>';
					$data .= '</div>';								
					$data .= '</div>';
					//$data .= '<div style="clear:both;"></div>';
				}
				$data .= '</div></td>';
				/* Organisation wise Filter assignment*/
				$data .= '<td>';
				$sql_select_image = " SELECT * FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$row_org_prod['prod_id']."' order by prod_img_type ";
				$result_select_image = mysqli_query($db_con,$sql_select_image) or die(mysqli_error($db_con));
				$num_rows_select_image = mysqli_num_rows($result_select_image);
				if($num_rows_select_image == 0)
				{
					$data .= 'No Images For This Product.';
				}
				else
				{					
					while($row_select_image = mysqli_fetch_array($result_select_image))
					{
						$imagepath = "../../images/planet/org".$row_org_prod['prod_orgid']."/prod_id_".$row_org_prod['prod_id']."/small/".$row_select_image['prod_img_file_name'];
						if($row_select_image['prod_img_type'] == "main")
						{
							$data .= '<img src="'.$imagepath.'">';
						}
						else
						{
							$data .= '<img src="'.$imagepath.'">';
						}
					}
				}
				$data .= '</td>';				
				$data .= '</div>';
			}			
	    	$data .= '</tbody>';
	    	$data .= '</table>';
			$data .= $data_count;	
			$response_array 	= array("Success"=>"Success","resp"=>utf8_encode($data));
		}
	}
	echo json_encode($response_array);}
if((isset($obj->loadOrgProducts)) == "1" && isset($obj->loadOrgProducts))
{
	$org_id			= mysqli_real_escape_string($db_con,$obj->org_id);
	if($org_id == "")
	{
		$response_array = array("Success"=>"fail","resp"=>"Organisation Id Empty");
	}
	else
	{
		$sql_org_prod  		= " SELECT * FROM `tbl_products_master` where `prod_orgid` = '".$org_id."' ";
		$result_org_prod	= mysqli_query($db_con, $sql_org_prod) or die(mysqli_error($db_con));
		$num_rows_org_prod	= mysqli_num_rows($result_org_prod);
		if($num_rows_org_prod == 0)
		{
			$response_array = array("Success"=>"fail","resp"=>"Products not available for this Oraganisation");
		}
		else
		{
			$data		= '';
			$data .= '<div class="control-group">';
			$data .= '<label class="control-label"><h3>Multi-Product Tagging (upto 2 level) and (upto 3 Filter)</h3></label>';
			$data .= 'Select Per Page &nbsp;&nbsp;<select name="prod_row_limit" id="prod_row_limit" onChange="loadProductData();"  class = "select2-me">';
			$data .= '<option value="5">5</option>';			
			$data .= '<option value="10" selected>10</option>';
			$data .= '<option value="25">25</option>';
			$data .= '<option value="50">50</option>';
			$data .= '<option value="75">75</option>';
			$data .= '<option value="100">100</option>';
			$data .= '<option value="200">200</option>';
			$data .= '<option value="300">300</option>';
			$data .= '<option value="400">400</option>';									
			$data .= '<option value="500">500</option>';
			$data .= '</select>';
			$data .= '<div class="controls data_container" style="overflow-y:scroll;margin-top:10px;" id="product_data">';
			$data .= '</div>';
			$data .= '</div>';					
			$data .= '<button class="btn-success" onClick="assignFilterNLevelAssignment();" style="margin:30px;width:70%;height:30px;">Assign Level or Filters to Products</button>';			
			$response_array 	= array("Success"=>"Success","resp"=>$data);				
		}
	}
	echo json_encode($response_array);	}
function prod_level($prod_id,$prod_levels_batch)
{
	$levels_batch			= explode(",",$prod_levels_batch);
	global $db_con;
	$sql_check_levels 		= " SELECT * FROM `tbl_product_levels` WHERE `prodlevel_prodid` = '".$prod_id."' ";	
	$result_check_levels	= mysqli_query($db_con,$sql_check_levels) or die(mysqli_error($db_con));
	$num_rows_check_levels	= mysqli_num_rows($result_check_levels);	
	if($num_rows_check_levels == 0)
	{
		foreach($levels_batch as $prodlevel_levelid)
		{					
			$resp = addLevels($prod_id,$prodlevel_levelid);
		}
		return true;
	}
	else
	{		
		$prodlevel_levelid_list	= array();						
		while($row_check_level = mysqli_fetch_array($result_check_levels))
		{
			$level_data = $row_check_level['prodlevel_levelid_parent'].":".$row_check_level['prodlevel_levelid_child'];												
			array_push($prodlevel_levelid_list,$level_data);	
		}																
		foreach($levels_batch as $prodlevel_levelid_user)
		{
			if(in_array($prodlevel_levelid_user,$prodlevel_levelid_list))
			{
				updateLevels($prod_id,$prodlevel_levelid_user,"1");
			}
			else
			{					
				addLevels($prod_id,$prodlevel_levelid_user);				
			}
		}			
		foreach($prodlevel_levelid_list as $prodlevel_levelid_db)
		{
			if(in_array($prodlevel_levelid_db,$levels_batch))
			{
			}
			else
			{
				updateLevels($prod_id,$prodlevel_levelid_db,"0");						
			}
		}
	}	}
function prod_filter($prod_id,$prod_filters_batch)
{
	$filters_batch			= explode(",",$prod_filters_batch);
	global $db_con;
	$sql_check_filters 		= " SELECT * FROM `tbl_product_filters` WHERE `prodfilt_prodid` = '".$prod_id."' ";	
	$result_check_filters	= mysqli_query($db_con,$sql_check_filters) or die(mysqli_error($db_con));
	$num_rows_check_filters	= mysqli_num_rows($result_check_filters);	
	if($num_rows_check_filters == 0)
	{
		foreach($filters_batch as $prodfilt_prodid)
		{
			$resp = addFilters($prod_id,$prodfilt_prodid);
		}
		return true;
	}
	else
	{		
		  $prodfilter_filterid_list	= array();						
		  while($row_check_filters = mysqli_fetch_array($result_check_filters))
		  {
			  $filters_data = $row_check_filters['prodfilt_filtid_parent'].":".$row_check_filters['prodfilt_filtid_child'].":".$row_check_filters['prodfilt_filtid_sub_child	'];												
			  array_push($prodfilter_filterid_list,$filters_data);	
		  }																
		  foreach($filters_batch as $prodfilter_filterid_user)
		  {
			  if(in_array($prodfilter_filterid_user,$prodfilter_filterid_list))
			  {
				  updateFilters($prod_id,$prodfilter_filterid_user,"1");
			  }
			  else
			  {					
				  addFilters($prod_id,$prodfilter_filterid_user);				
			  }
		  }			
		  foreach($prodfilter_filterid_list as $prodfilter_filterid_db)
		  {
			  if(in_array($prodfilter_filterid_db,$filters_batch))
			  {
			  }
			  else
			  {
				  updateFilters($prod_id,$prodfilter_filterid_db,"0");						
			  }
		  }
	  }}
if((isset($obj->assignFiltersNLevels)) == "1" && isset($obj->assignFiltersNLevels))
{
	$products_batch 	= $obj->products_batch;
	$products_str 		= implode(" ",$products_batch);
	$products_array		= explode("*",$products_str);
	$data				= "";
	foreach($products_array as $prod_data)
	{
		$prod_data_content	= explode(";",$prod_data);
		$prod_id			= $prod_data_content[0];
		if(trim($prod_id) != "")
		{
			$data				.= $prod_id."=>"; 	
			$prod_levels_batch	= $prod_data_content[1];
			if(trim($prod_levels_batch) != "")
			{
				$resp = prod_level($prod_id,$prod_levels_batch);
				//$data				.= $prod_levels_batch."<>";
			}
			
			$prod_filters_batch	= $prod_data_content[2];			
			if(trim($prod_filters_batch) != "")
			{
				$resp =  prod_filter($prod_id,$prod_filters_batch);
				//$data				.= $prod_filters_batch."<>";				
			}
		}
	}
	$response_array = array("Success"=>"fail","resp"=>"Updated");
	echo json_encode($response_array);		}
/* Load Oreganisation wise Products on View Organisation Page/ levels and filters assignment */
/* Product Specification Operations */
if((isset($obj->insert_prod_branch)) == "1" && isset($obj->insert_prod_branch))
{
	$ar_brand_id			= $obj->brand_id;	
	$response_array 		= array();
	$prod_id				= $obj->prod_id_hid;
	$branch_flag;	
	foreach($ar_brand_id as $branch)	
	{
		$brand_id 			= explode("-",$branch);
		if($brand_id[0] == 0 && $brand_id[1] == "undefined")
		{
			$branch_flag = 1;		

		}
		else
		{
			$sql_check_branch = "SELECT * FROM tbl_branch_product WHERE prod_id = '".$prod_id."' AND brand_id = '".$brand_id[0]."' ";
			$result_check_branch = mysqli_query($db_con,$sql_check_branch) or die(mysqli_error($db_con));
			$num_rows_check_branch = mysqli_num_rows($result_check_branch);
			if($num_rows_check_branch != 0)
			{
				$branch_flag = 1;					
			}
			else
			{	
				$sql_branch_insert = " INSERT INTO `tbl_branch_product`( `prod_id`, `brand_id`, `branch_prod_quantity`, `branch_prod_status`, `branch_prod_created`, ";
				$sql_branch_insert .= " `	branch_prod_created_by`) VALUES ('".$prod_id."','".$brand_id[0]."','".$brand_id[1]."','1','".$datetime."','".$uid."')";
				$result_branch_insert = mysqli_query($db_con,$sql_branch_insert) or die(mysqli_error($db_con));	
				if($result_branch_insert)
				{	
					$branch_flag = 0;														
				}
				else
				{
					$branch_flag = 1;						
				}				
			}
		}
	}
	if($branch_flag == 1)
	{
		$response_array = array("Success"=>"Fail","resp"=>"Content Not Added.");		
	}
	elseif($branch_flag == 0)
	{
		$response_array = array("Success"=>"Success","resp"=>"Content Added Successfully");		
	}
	echo json_encode($response_array);}
if((isset($obj->get_img)) == "1" && isset($obj->get_img))
{
	$response_array = array();
	$img_hid		= $obj->img_hid;	
	$data = '<div class="control-group" id="img_div'.$img_hid.'">';
	$data .= '<div class="controls">';
	$data .= '<input type="file" name="prod_file'.$img_hid.'" id="prod_file'.$img_hid.'" >';
	$data .= '</div>';
	$data .= '</div>';
	$response_array = array("Success"=>"Success","resp"=>$data);	
	echo json_encode($response_array);											 }
if((isset($obj->get_alloc)) == "1" && isset($obj->get_alloc))
{
	$response_array = array();
	$aloc_hid		= $obj->aloc_hid;	
	$prod_id		= $obj->prod_id;	
	$data			= "";	
	$sql_get_alloc 	= " SELECT DISTINCT `brand_id`, `brand_name` FROM `tbl_brands_master` where branch_orgid = (SELECT prod_orgid from tbl_products_master where prod_id = ".$prod_id.")";
	$result_get_alloc = mysqli_query($db_con,$sql_get_alloc) or die(mysqli_error($db_con));
	if($result_get_alloc)
	{		
		$num_rows_get_alloc = mysqli_num_rows($result_get_alloc);
		if($num_rows_get_alloc != 0)
		{
			$data			.= '<div id="alloc_div'.$alloc_hid.'">';
			$data 			.= '<div class="control-group">';
			$data 			.= '<label class="control-label" >Select Branch</label>';						
			$data 			.= '<div class="controls">';
			$data			.= '<select name="alloc_sel_'.$alloc_hid.'" id="alloc_sel_'.$alloc_hid.'" placeholder="Parent Name" class="select2-me input-large" data-rule-required="true">';
			$data			.= '<option value="">Select Branch</option>';													
			while($row_get_alloc = mysqli_fetch_array($result_get_alloc))
			{															
				$data			.= '<option value="'.$row_get_alloc['brand_id'].'">'.$row_get_alloc['brand_name'].'</option>';
			}
			$data			.= '</select>';
			$data 			.= '</div>';
			$data 			.= '</div>';					
			$data 			.= '<div class="control-group">';
			$data 			.= '<label class="control-label" >Product Quantity</label>';
			$data 			.= '<div class="controls">';			
			$data			.= '&nbsp&nbsp<input type="text" id="alloc_text_'.$alloc_hid.'" name="alloc_text_'.$alloc_hid.'" class="input-medium" data-rule-required="true" />';
			$data 			.= '</div>';
			$data 			.= '</div>';
			$data 			.= '</div>';
			$response_array = array("Success"=>"Success","resp"=>$data);			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>$sql_get_alloc."No Branch Not Registered.All Products registered to main Branch by Default.");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"can not get Branch.");
	}
	echo json_encode($response_array);		}
// Display Error record for product-specification 
if((isset($obj->load_error1)) == "1" && isset($obj->load_error1))
{
	$start_offset   = 0;
	
	$page 			= $obj->page2;	
	$per_page		= $obj->row_limit2;
	$search_text	= $obj->search_text2;	
	$cat_parent		= $obj->cat_parent2;
	$response_array = array();	
		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset 	+= $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, ";
		$sql_load_data  .= " `error_created_by`, `error_modified`, `error_modified_by`, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_created_by) as created_by_name, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = error_modified_by) as modified_by_name ";
		$sql_load_data  .= " FROM `tbl_error_data`  ";
		$sql_load_data  .= " WHERE error_module_name='product_specification' ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND error_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " AND (error_data LIKE '%".$search_text."%' or error_module_name LIKE '%".$search_text."%' ";
			//$sql_load_data .= " or name_created_by like '%".$search_text."%' or name_modified_by like '%".$search_text."%' ";	
			$sql_load_data .= " or error_created like '%".$search_text."%' or error_modified like '%".$search_text."%') ";	
		}
		
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		
		$sql_load_data .=" LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$prod_data = "";	
			$prod_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$prod_data .= '<thead>';
    	  	$prod_data .= '<tr>';
         	$prod_data .= '<th>Sr. No.</th>';
			$prod_data .= '<th>Product ID</th>';
			$prod_data .= '<th>Product Name</th>';
			$prod_data .= '<th>Product Model Number</th>';
			$prod_data .= '<th>Specification Name</th>';
			$prod_data .= '<th>Created</th>';
			$prod_data .= '<th>Created By</th>';
			$prod_data .= '<th>Modified</th>';
			$prod_data .= '<th>Modified By</th>';
			$prod_data .= '<th>Edit</th>';			
			$prod_data .= '<th>
							<div class="center-text">
								<input type="button"  value="Delete" onclick="multipleDelete_error1();" class="btn-danger"/>
							</div>
						</th>';
          	$prod_data .= '</tr>';
      		$prod_data .= '</thead>';
      		$prod_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$get_prod_rec	= json_decode($row_load_data['error_data']);
				
				$er_prod_id				= $get_prod_rec->prod_id;
				$er_prod_name			= $get_prod_rec->prod_name;
				$er_prod_model_number	= $get_prod_rec->prod_model_number;
				$er_spec_name			= $get_prod_rec->spec_name;
				
				$prod_data .= '<tr>';				
				$prod_data .= '<td>'.++$start_offset.'</td>';				
				$prod_data .= '<td>'.$er_prod_id.'</td>';
				$prod_data .= '<td>'.$er_prod_name.'</td>';
				$prod_data .= '<td>'.$er_prod_model_number.'</td>';
				$prod_data .= '<td><span style="color:#E63A3A;">'.$er_spec_name.' [Not Exist]</span></td>';
				$prod_data .= '<td>'.$row_load_data['error_created'].'</td>';
				$prod_data .= '<td>'.$row_load_data['error_created_by'].'</td>';
				$prod_data .= '<td>'.$row_load_data['error_modified'].'</td>';
				$prod_data .= '<td>'.$row_load_data['error_modified_by'].'</td>';
				$prod_data .= '<td class="center-text">';
				$prod_data .= '<input type="button" value="Edit" id="'.$row_load_data['error_id'].'" class="btn-warning" onclick="addMoreProductSpecification(this.id,\'error\');"></td>';								
				$prod_data .= '<td>
								<div class="controls" align="center">';
				$prod_data .= '		<input type="checkbox" value="'.$row_load_data['error_id'].'" id="error_batch'.$row_load_data['error_id'].'" name="error_batch'.$row_load_data['error_id'].'" class="css-checkbox error_batch">';
				$prod_data .= '		<label for="error_batch'.$row_load_data['error_id'].'" class="css-label"></label>';
				$prod_data .= '	</div>
							  </td>';										
	          	$prod_data .= '</tr>';															
			}	
      		$prod_data .= '</tbody>';
      		$prod_data .= '</table>';	
			$prod_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>utf8_encode($prod_data));
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>utf8_encode("No Data Available"));
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>utf8_encode("No Row Limit and Page Number Specified"));
	}
	
	echo json_encode($response_array);}
/* excel file upload */
if(isset($_POST['excel_product_upload']) && $_POST['excel_product_upload'] == "1")
{
	$date 					= date_create();	
	$path 					= 'uploadedExcel/product/'.date_format($date, 'U');
	mkdir($path);
	$batch_filters 			= array();
	$batch_levels 			= array();
	$sourcePath 			= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 			= $path."/".$_FILES['file']['name']; 			// Target path where file is to be stored
	$excel_error_log_file 	= $path."/".date_format($date, 'U').".log";
	move_uploaded_file($sourcePath,$inputFileName) ;
	
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	
	/* Generate Error Check Excel */		
	include_once("xlsxwriter.class.php");	
	/* Generate Error Check Excel */		
	
	$prod_id 	= 0;
	$msg		= '';
	$insertion_flag		 = 0;
	$insertion_flag_spec = 0;
	$prod_level_flag	 = 0;
	$prod_filter_flag	 = 0;
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
		$excel_error_log = fopen($excel_error_log_file, "x+") or die("Unable to open file!");	
		$error_log_data = "";
		$data 			= "";
		$new_data 		= "";
		
		/* headers for error check excel */
		$product_data_sheet		= array();
		$header					= array();
		$product_data_sheet_row	= array();
				
		$header = array(
			'Model Number'=>'string',			
			'Name'=>'string',			
			'Title'=>'string',		
			'Description'=>'string',
			'Organisation'=>'string',
			'Brand'=>'string',
			'Category'=>'string',
			'Google Product Category'=>'string',
			'Content'=>'string',			
			'Quantity'=>'string',
			'List Price'=>'string',
			'Recommended Price'=>'string',
			'Meta Description'=>'string',			
			'Meta Tags'=>'string',						
			'Meta Title'=>'string',
			'Specification'=>'string',
			'Filters'=>'string',
			'Levels'=>'string',
		);		
		/* headers for error check excel */	
		
		$k=1;
			
		for($i=2;$i<=$arrayCount;$i++)
		{			
			/* Product Model Number */
			$prod_model_number		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"]));
			/* Product Model Number */			

			/* Product Name */			
			$prod_name1				= trim(str_replace("'","",trim($allDataInSheet[$i]["B"])));
			$prod_name				= htmlspecialchars($prod_name1, ENT_HTML5);			
			/* Product Name */			
			
			/* Product Title */			
			$prod_title_1			= trim(str_replace("'","",trim($allDataInSheet[$i]["C"])));
			$prod_title				= htmlspecialchars($prod_title_1, ENT_HTML5);			
			/* Product Title */						
			
			/* Product Description */						
			$prod_description1		= trim(str_replace("'","",$allDataInSheet[$i]["D"]));
			$prod_description		= htmlspecialchars($prod_description1, ENT_HTML5);
			/* Product Description */						
						
			/* Product Organisation Id */						
			$prod_org				= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"]));
			$prod_orgid				= 0;							
		
			$sql_get_orgid			= " SELECT * FROM `tbl_oraganisation_master` WHERE `org_name` like '".$prod_org."' ";
			$res_get_orgid			= mysqli_query($db_con, $sql_get_orgid) or die(mysqli_error($db_con));
			$num_rows_get_orgid		= mysqli_num_rows($res_get_orgid);
		//  echo json_encode($num_rows_get_orgid);exit();
			
				$row_get_orgid		= mysqli_fetch_array($res_get_orgid);
				if($row_get_orgid['org_id'] != "") // if org id is not empty then will give exact org id
				{
					$prod_orgid		= $row_get_orgid['org_id'];
					$prod_org      =  $row_get_orgid['org_name'];
				}
			
			/* Product Organisation Id */
			
			/* Product Brand Id */
			$prod_brand				= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"]));
			$prod_brandid			= 0;				
			
			$sql_get_brandid		= " SELECT * FROM `tbl_brands_master` WHERE `brand_name` like '".$prod_brand."' ";
			$res_get_brandid		= mysqli_query($db_con, $sql_get_brandid) or die(mysqli_error($db_con));
			$num_rows_brandid		= mysqli_num_rows($res_get_brandid);
			if($num_rows_brandid != 0)
			{
				$row_get_brandid 	= mysqli_fetch_array($res_get_brandid);		
				if($row_get_brandid['brand_id'] != "") // if BRAND id is not empty then will give exact BRAND id
				{
					$prod_brandid	= $row_get_brandid['brand_id'];
					$prod_brand     = $row_get_brandid['brand_name'];
				}
				elseif($row_get_brandid['brand_id'] == "") // BRAND id is empty 
				{
					$prod_brandid	= "";
				}				
			}
			else
			{
				$prod_brandid		= "";
			}			
			/* Product Brand Id */					
			
			/* Product Cat Id */		
			$products_catid         = array();	
			$prod_catid_txt			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["G"]));
			$product_cat_names      = explode(',',$prod_catid_txt);
			for($j=0;$j<sizeof($product_cat_names);$j++)
			{
			$sql_get_catid			= " SELECT * FROM `tbl_category` WHERE `cat_name` like '".$product_cat_names[$j]."' ";
			$res_get_catid			= mysqli_query($db_con, $sql_get_catid) or die(mysqli_error($db_con));
			$num_rows_get_catid		= mysqli_num_rows($res_get_catid);
			if($num_rows_get_catid != 0)
			{
				$row_get_catid			= mysqli_fetch_array($res_get_catid);
				array_push($products_catid,$row_get_catid['cat_id']);
			}
			
			}// for end
			if(count($products_catid) > 0)
			{
			$prod_catid = implode(',',$products_catid);
			}
			else
			{
				$prod_catid="";
			}
			
		
			
			/* Product Cat Id */		
			$prod_google_product_category = str_replace("'","",trim($allDataInSheet[$i]["H"]));
			//$prod_google_product_category = mysqli_real_escape_string($db_con,trim($allDataInSheet[$i]["I"]);
			/* Product Content */													
			$prod_content1		= str_replace("'","",trim($allDataInSheet[$i]["I"]));
			$prod_content		= htmlspecialchars($prod_content1, ENT_HTML5);
			/* Product Content */	
									
			/* Product Quantity */													
			$prod_quantity			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["J"]));
			if(trim($prod_quantity) == "" || $prod_quantity < 0)
			{
				$prod_quantity		= 0;
			}
			
									
			/* Product List price */										
			$prod_list_price		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["M"]));										
						
			
			
			/* Product recommended price */					
			$prod_recommended_price	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["N"]));
			
			
			
			/* Product Meta Description */												
			$prod_meta_description_1		= str_replace("'","",trim($allDataInSheet[$i]["O"]));
			$prod_meta_description		= htmlspecialchars($prod_meta_description_1, ENT_HTML5);			
			/* Product Meta Description */															
			
			/* Product Meta Title */
			$prod_meta_title		= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["P"])), ENT_HTML5);
			/* Product Meta Title */
			
			/* Product Meta Tags */						
			$prod_meta_tags			= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["Q"])), ENT_HTML5);
			/* Product Meta Tags */
						
			/* Product Specification */
			$batch_specification	= array();
			$error_specification	= array();
			
			$prod_specification_list	= htmlspecialchars(trim(mysqli_real_escape_string($db_con,str_replace("\n","",$allDataInSheet[$i]["R"]))), ENT_HTML5);
			if($prod_specification_list != "")
			{
				$spec_array 			= explode(";",$prod_specification_list);
				foreach($spec_array as $spec_content)
				{
					$spec_data 			= explode(":",$spec_content);
					$spec_name			= trim($spec_data[0]);
					$spec_value			= trim($spec_data[1]);
					$sql_get_spec_id 	= " SELECT * FROM `tbl_specifications_master` WHERE `spec_name` like '".$spec_name."' ";
					$result_get_spec_id	= mysqli_query($db_con,$sql_get_spec_id) or die(mysqli_error($db_con));
					$num_rows_get_spec_id= mysqli_num_rows($result_get_spec_id);
					if($num_rows_get_spec_id != 0)
					{
						$row_get_spec_id	= mysqli_fetch_array($result_get_spec_id);					
						$spec_id			= $row_get_spec_id['spec_id'];
						$spec_entry			= $spec_id.":".$spec_value;						
						array_push($batch_specification,$spec_entry);						
					}
					else
					{
						array_push($error_specification,$spec_content);
					}
					$error_specification = implode(" ",$error_specification);
				}				
			}
			else
			{
				$batch_specification	= "";
				$error_specification	= "";
			}
			/* Product Specification */		

			/* Product Filter */						
			$batch_filters;
			
			$prod_filters_list		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["S"]));
			if($prod_filters_list != "")
			{
				$batch_filters	= array();	
				$filters_array 	= explode("|",$prod_filters_list);
				foreach($filters_array as $filter_main_1)
				{
					$filter_main_2 		= explode("::",$filter_main_1);
					$parent_filter		= trim($filter_main_2[0]);
					$child_filter_str 	= trim($filter_main_2[1]);
					$sql_get_parent_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like 'parent' ";
					$sql_get_parent_filter 		.= "and `filt_sub_child` like 'parent' and `filt_name` like '".$parent_filter."' ";
					$result_get_parent_filter 	= mysqli_query($db_con,$sql_get_parent_filter) or die(mysqli_error($db_con));
					$num_rows_get_parent_filter = mysqli_num_rows($result_get_parent_filter);
					if($num_rows_get_parent_filter == 0)
					{
						$error_log_data .= "Filter not assigned due to parent id not found for ".$parent_filter."\n";
					}
					else
					{
						$row_get_parent_filter	= mysqli_fetch_array($result_get_parent_filter);
						$prodfilt_filtid_parent = $row_get_parent_filter['filt_id'];
						if(strpos($child_filter_str, ';') !== false)
						{
							$childs_filter	= explode(";",$child_filter_str);
							foreach($childs_filter as $childs_data)
							{
								if(strpos($childs_data, ':') !== false)
								{
									$filter_childs 				= explode(":",$childs_data);
									$child_filter				= trim($filter_childs[0]);
									$sub_child_filters			= trim($filter_childs[1]);
									$sql_get_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";
									$sql_get_child_filter 		.= "and `filt_sub_child` like 'child' and `filt_name` like '".strtolower($child_filter)."' ";
									$result_get_child_filter 	= mysqli_query($db_con,$sql_get_child_filter) or die(mysqli_error($db_con));
									$num_rows_get_child_filter 	= mysqli_num_rows($result_get_child_filter);							
									if($num_rows_get_child_filter != 0)
									{
										$row_get_child_filter	= mysqli_fetch_array($result_get_child_filter);
										$prodfilt_filtid_child = $row_get_child_filter['filt_id'];
										if(strpos($sub_child_filters, ',') !== false)
										{
											$sub_child_filters	= explode(",",$sub_child_filters);
											foreach($sub_child_filters as $sub_child_filt_name)
											{
												$sql_get_sub_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";	
												$sql_get_sub_child_filter 		.= "and `filt_sub_child` like '".$prodfilt_filtid_child."' and `filt_name` like '".strtolower($sub_child_filt_name)."' ";
												$result_get_sub_child_filter 	= mysqli_query($db_con,$sql_get_sub_child_filter) or die(mysqli_error($db_con));
												$num_rows_get_sub_child_filter 	= mysqli_num_rows($result_get_sub_child_filter);							
												if($num_rows_get_sub_child_filter != 0)
												{
													$row_get_sub_child_filter	= mysqli_fetch_array($result_get_sub_child_filter);
													$prodfilt_filtid_sub_child 	= $row_get_sub_child_filter['filt_id'];
													$filter_content 			= $prodfilt_filtid_parent.":".$prodfilt_filtid_child.":".$prodfilt_filtid_sub_child;
													array_push($batch_filters,$filter_content);
												}
												else
												{
													$error_log_data .= "Filter not assigned due to Sub child id not found for ".$sub_child_filt_name."\n";
												}
											}
										}
										else
										{
											$sql_get_sub_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";	
											$sql_get_sub_child_filter 		.= "and `filt_sub_child` like '".$prodfilt_filtid_child."' and `filt_name` like '".strtolower($sub_child_filters)."' ";
											$result_get_sub_child_filter 	= mysqli_query($db_con,$sql_get_sub_child_filter) or die(mysqli_error($db_con));
											$num_rows_get_sub_child_filter 	= mysqli_num_rows($result_get_sub_child_filter);							
											if($num_rows_get_sub_child_filter != 0)
											{
												$row_get_sub_child_filter	= mysqli_fetch_array($result_get_sub_child_filter);
												$prodfilt_filtid_sub_child 	= $row_get_sub_child_filter['filt_id'];
												$filter_content 			= $prodfilt_filtid_parent.":".$prodfilt_filtid_child.":".$prodfilt_filtid_sub_child;
												array_push($batch_filters,$filter_content);
											}
											else
											{
												$error_log_data .= "Filter not assigned due to Sub child id not found for ".$sub_child_filt_name."\n";
											}																	
										}
									}
									else
									{
										$error_log_data .= "Filter not assigned due to child id not found for ".$child_filter."\n";
									}
								}
								else
								{

									$filter_childs 				= explode(":",$child_filter_str);
									$child_filter				= trim($filter_childs[0]);
									$sub_child_filters			= trim($filter_childs[1]);
									$sql_get_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";
									$sql_get_child_filter 		.= "and `filt_sub_child` like 'child' and `filt_name` like '".strtolower($childs_data)."' ";
									$result_get_child_filter 	= mysqli_query($db_con,$sql_get_child_filter) or die(mysqli_error($db_con));
									$num_rows_get_child_filter 	= mysqli_num_rows($result_get_child_filter);							
									if($num_rows_get_child_filter != 0)
									{
										$row_get_child_filter	= mysqli_fetch_array($result_get_child_filter);
										$prodfilt_filtid_child = $row_get_child_filter['filt_id'];
		
										$sql_get_sub_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";	
										$sql_get_sub_child_filter 		.= "and `filt_sub_child` like '".$prodfilt_filtid_child."' and `filt_name` like 'none' ";
										$result_get_sub_child_filter 	= mysqli_query($db_con,$sql_get_sub_child_filter) or die(mysqli_error($db_con));
										$num_rows_get_sub_child_filter 	= mysqli_num_rows($result_get_sub_child_filter);							
										if($num_rows_get_sub_child_filter != 0)
										{
											$row_get_sub_child_filter	= mysqli_fetch_array($result_get_sub_child_filter);
											$prodfilt_filtid_sub_child 	= $row_get_sub_child_filter['filt_id'];
											$filter_content 			= $prodfilt_filtid_parent.":".$prodfilt_filtid_child.":".$prodfilt_filtid_sub_child;
											array_push($batch_filters,$filter_content);
										}
										else
										{
											$error_log_data .= "Filter not assigned due to Sub child id not found for ".$sub_child_filt_name."\n";
										}																									
									}
									else
									{
										$error_log_data .= "Filter not assigned due to child id not found for ".$child_filter."\n";
									}
								}															
							}
						}
						else
						{						
							if(strpos($child_filter_str, ':') !== false)
							{
							$filter_childs 				= explode(":",$child_filter_str);
							$child_filter				= trim($filter_childs[0]);
							$sub_child_filters			= trim($filter_childs[1]);
							$sql_get_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";
							$sql_get_child_filter 		.= "and `filt_sub_child` like 'child' and `filt_name` like '".strtolower($child_filter)."' ";
							$result_get_child_filter 	= mysqli_query($db_con,$sql_get_child_filter) or die(mysqli_error($db_con));
							$num_rows_get_child_filter 	= mysqli_num_rows($result_get_child_filter);							
							if($num_rows_get_child_filter != 0)
							{
								$row_get_child_filter	= mysqli_fetch_array($result_get_child_filter);
								$prodfilt_filtid_child = $row_get_child_filter['filt_id'];
								if(strpos($sub_child_filters, ',') !== false)
								{
									$sub_child_filters	= explode(",",$sub_child_filters);
									foreach($sub_child_filters as $sub_child_filt_name)
									{
										$sql_get_sub_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";	
										$sql_get_sub_child_filter 		.= "and `filt_sub_child` like '".$prodfilt_filtid_child."' and `filt_name` like '".strtolower($sub_child_filt_name)."' ";
										$result_get_sub_child_filter 	= mysqli_query($db_con,$sql_get_sub_child_filter) or die(mysqli_error($db_con));
										$num_rows_get_sub_child_filter 	= mysqli_num_rows($result_get_sub_child_filter);							
										if($num_rows_get_sub_child_filter != 0)
										{
											$row_get_sub_child_filter	= mysqli_fetch_array($result_get_sub_child_filter);
											$prodfilt_filtid_sub_child 	= $row_get_sub_child_filter['filt_id'];
											$filter_content 			= $prodfilt_filtid_parent.":".$prodfilt_filtid_child.":".$prodfilt_filtid_sub_child;
											array_push($batch_filters,$filter_content);
										}
										else
										{
											$error_log_data .= "Filter not assigned due to Sub child id not found for ".$sub_child_filt_name."\n";
										}
									}
								}
								else
								{
									$sql_get_sub_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";	
									$sql_get_sub_child_filter 		.= "and `filt_sub_child` like '".$prodfilt_filtid_child."' and `filt_name` like '".strtolower($sub_child_filters)."' ";
									$result_get_sub_child_filter 	= mysqli_query($db_con,$sql_get_sub_child_filter) or die(mysqli_error($db_con));
									$num_rows_get_sub_child_filter 	= mysqli_num_rows($result_get_sub_child_filter);							
									if($num_rows_get_sub_child_filter != 0)
									{
										$row_get_sub_child_filter	= mysqli_fetch_array($result_get_sub_child_filter);
										$prodfilt_filtid_sub_child 	= $row_get_sub_child_filter['filt_id'];
										$filter_content 			= $prodfilt_filtid_parent.":".$prodfilt_filtid_child.":".$prodfilt_filtid_sub_child;
										array_push($batch_filters,$filter_content);
									}
									else
									{
										$error_log_data .= "Filter not assigned due to Sub child id not found for ".$sub_child_filt_name."\n";
									}																	
								}
							}
							else
							{
								$error_log_data .= "Filter not assigned due to child id not found for ".$child_filter."\n";
							}
						}
							else
							{
							$filter_childs 				= explode(":",$child_filter_str);
							$child_filter				= trim($filter_childs[0]);
							$sub_child_filters			= trim($filter_childs[1]);
							$sql_get_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";
							$sql_get_child_filter 		.= "and `filt_sub_child` like 'child' and `filt_name` like '".strtolower($child_filter)."' ";
							$result_get_child_filter 	= mysqli_query($db_con,$sql_get_child_filter) or die(mysqli_error($db_con));
							$num_rows_get_child_filter 	= mysqli_num_rows($result_get_child_filter);							
							if($num_rows_get_child_filter != 0)
							{
								$row_get_child_filter	= mysqli_fetch_array($result_get_child_filter);
								$prodfilt_filtid_child = $row_get_child_filter['filt_id'];
								$sql_get_sub_child_filter 		= " SELECT `filt_id` FROM `tbl_filters` WHERE `filt_type` like '".$prodfilt_filtid_parent."' ";	
								$sql_get_sub_child_filter 		.= "and `filt_sub_child` like '".$prodfilt_filtid_child."' and `filt_name` like 'none' ";
								$result_get_sub_child_filter 	= mysqli_query($db_con,$sql_get_sub_child_filter) or die(mysqli_error($db_con));
								$num_rows_get_sub_child_filter 	= mysqli_num_rows($result_get_sub_child_filter);							
								if($num_rows_get_sub_child_filter != 0)
								{
									$row_get_sub_child_filter	= mysqli_fetch_array($result_get_sub_child_filter);
									$prodfilt_filtid_sub_child 	= $row_get_sub_child_filter['filt_id'];
									$filter_content 			= $prodfilt_filtid_parent.":".$prodfilt_filtid_child.":".$prodfilt_filtid_sub_child;
									array_push($batch_filters,$filter_content);
								}
								else
								{
									$error_log_data .= "Filter not assigned due to Sub child id not found for ".$sub_child_filt_name."\n";
								}																									
							}
							else
							{
								$error_log_data .= "Filter not assigned due to child id not found for ".$child_filter."\n";
							}
						}
						}
					}
				}	
			}
			else
			{
				$batch_filters	= "";
			}	//echo json_encode($batch_filters);exit();
			/* Product Filter */		
								
			/* Product Level */										
			$batch_levels;
			$prod_level				= htmlspecialchars(trim($allDataInSheet[$i]["T"]), ENT_HTML5);

			if($prod_level != "")
			{
				$batch_levels	= array();				
				if(strpos($prod_level, ';') !== false)
				{
					$levels_array    = explode(";",$prod_level);
					foreach($levels_array as $level_data)
					{
						if(strpos($level_data, ':') !== false)
						{
							$level_parent_child_data	= explode(":",$level_data);
							$parent_name 				= trim($level_parent_child_data[0]);
							$child_levels				= trim($level_parent_child_data[1]);
							
							$sql_get_level_parent_id 		= " SELECT * FROM `tbl_level` WHERE `cat_type` = 'parent' and `cat_name` like '".strtolower($parent_name)."' ";
							$result_get_level_parent_id 	= mysqli_query($db_con,$sql_get_level_parent_id) or die(mysqli_error($db_con));
							$num_rows_get_level_parent_id 	= mysqli_num_rows($result_get_level_parent_id);
							if($num_rows_get_level_parent_id == 0)
							{
								$error_log_data .= "Level not assigned due to parent id not found for ".$parent_name."\n";
							}
							else
							{
								$row_get_level_parent_id 	= mysqli_fetch_array($result_get_level_parent_id);
								$prodlevel_levelid_parent	= $row_get_level_parent_id['cat_id'];
									
								if(strpos($child_levels, ',') !== false)									
								{
									$level_child = explode(",",$child_levels);
									foreach($level_child as $level_child_name)
									{
										$sql_get_level_child_id 		= " SELECT * FROM `tbl_level` WHERE `cat_type` = '".$prodlevel_levelid_parent."' ";
										$sql_get_level_child_id 		.= " and `cat_name` like '".strtolower($level_child_name)."' ";
										$result_get_level_child_id 		= mysqli_query($db_con,$sql_get_level_child_id) or die(mysqli_error($db_con));
										$num_rows_get_level_child_id 	= mysqli_num_rows($result_get_level_child_id);		
										if($num_rows_get_level_child_id == 0)
										{
											$error_log_data .= "Level not assigned due to child id not found for ".$level_child_name."\n";
										}
										else
										{
											$row_get_level_child_id 	= mysqli_fetch_array($result_get_level_child_id);
											$prodlevel_levelid_child	= $row_get_level_child_id['cat_id'];
											$level_content				= $prodlevel_levelid_parent.":".$prodlevel_levelid_child;
											array_push($batch_levels,$level_content);
										}										
									}
								}
								else
								{
									$sql_get_level_child_id 		= " SELECT * FROM `tbl_level` WHERE `cat_type` = '".$prodlevel_levelid_parent."' ";
									$sql_get_level_child_id 		.= " and `cat_name` like '".strtolower($child_levels)."' ";
									$result_get_level_child_id 		= mysqli_query($db_con,$sql_get_level_child_id) or die(mysqli_error($db_con));
									$num_rows_get_level_child_id 	= mysqli_num_rows($result_get_level_child_id);		
									if($num_rows_get_level_child_id == 0)
									{
										$error_log_data .= "Level not assigned due to child id not found for strtolower($child_levels) \n";
									}
									else
									{
										$row_get_level_child_id 	= mysqli_fetch_array($result_get_level_child_id);
										$prodlevel_levelid_child	= $row_get_level_child_id['cat_id'];
										$level_content				= $prodlevel_levelid_parent.":".$prodlevel_levelid_child;
										array_push($batch_levels,$level_content);
									}									
								}
							}
						}
						else
						{
							$parent_name					= trim($level_data);
							$sql_get_level_parent_id 		= " SELECT * FROM `tbl_level` WHERE `cat_type` = 'parent' and `cat_name` like '".strtolower($parent_name)."' ";
							$result_get_level_parent_id 	= mysqli_query($db_con,$sql_get_level_parent_id) or die(mysqli_error($db_con));
							$num_rows_get_level_parent_id 	= mysqli_num_rows($result_get_level_parent_id);
							if($num_rows_get_level_parent_id == 0)
							{
								$error_log_data .= "Level not assigned due to parent id not found for ".$parent_name." \n";
							}
							else
							{
								$row_get_level_parent_id 	= mysqli_fetch_array($result_get_level_parent_id);
								$prodlevel_levelid_parent	= $row_get_level_parent_id['cat_id'];
								
								$sql_get_level_child_id 		= " SELECT * FROM `tbl_level` WHERE `cat_type` = '".$prodlevel_levelid_parent."' ";
								$sql_get_level_child_id 		.= " and `cat_name` like 'none' ";
								$result_get_level_child_id 		= mysqli_query($db_con,$sql_get_level_child_id) or die(mysqli_error($db_con));
								$num_rows_get_level_child_id 	= mysqli_num_rows($result_get_level_child_id);		
								if($num_rows_get_level_child_id == 0)
								{
									$error_log_data .= "Level not assigned due to child id not found for none \n";
								}
								else
								{
									$row_get_level_child_id 	= mysqli_fetch_array($result_get_level_child_id);
									$prodlevel_levelid_child	= $row_get_level_child_id['cat_id'];
									$level_content				= $prodlevel_levelid_parent.":".$prodlevel_levelid_child;
									array_push($batch_levels,$level_content);
								}
							}
						}
					}									
				}
				else
				{
					  $parent_name					= trim($level_data);
					  $sql_get_level_parent_id 		= " SELECT * FROM `tbl_level` WHERE `cat_type` = 'parent' and `cat_name` like '".strtolower($parent_name)."' ";
					  $result_get_level_parent_id 	= mysqli_query($db_con,$sql_get_level_parent_id) or die(mysqli_error($db_con));
					  $num_rows_get_level_parent_id = mysqli_num_rows($result_get_level_parent_id);
					  if($num_rows_get_level_parent_id == 0)
					  {
						  $error_log_data .= "Level not assigned due to parent id not found for ".$parent_name." \n";
					  }
					  else
					  {
						  $row_get_level_parent_id 	= mysqli_fetch_array($result_get_level_parent_id);
						  $prodlevel_levelid_parent	= $row_get_level_parent_id['cat_id'];
						  
						  $sql_get_level_child_id 		= " SELECT * FROM `tbl_level` WHERE `cat_type` = '".$prodlevel_levelid_parent."' ";
						  $sql_get_level_child_id 		.= " and `cat_name` like 'none' ";
						  $result_get_level_child_id 	= mysqli_query($db_con,$sql_get_level_child_id) or die(mysqli_error($db_con));
						  $num_rows_get_level_child_id 	= mysqli_num_rows($result_get_level_child_id);		
						  if($num_rows_get_level_child_id == 0)
						  {
							  $error_log_data .= "Level not assigned due to child id not found for none \n";
						  }
						  else
						  {
							  $row_get_level_child_id 	= mysqli_fetch_array($result_get_level_child_id);
							  $prodlevel_levelid_child	= $row_get_level_child_id['cat_id'];
							  $level_content			= $prodlevel_levelid_parent.":".$prodlevel_levelid_child;
							  array_push($batch_levels,$level_content);
						  }
					  }					
				}
			}
			else
			{
				$batch_levels	= "";
			}
			/* Product Level */										
						
			$prod_status			= 1;
			 
			/* Generate Error Check Excel */

			$product_data_sheet_row		= array($prod_model_number,$prod_name,$prod_title,$prod_description,$prod_orgid,$prod_brandid,$products_catid,$prod_content,$prod_quantity,$prod_list_price,$prod_recommended_price,$prod_meta_description,$prod_meta_tags,$prod_meta_title,$prod_status,$batch_specification,$error_specification,$error_log_data,$batch_filters,$batch_levels);
			array_push($product_data_sheet, $product_data_sheet_row);	

			/* Generate Error Check Excel */	
			/* Old Code*/

			$error_log_data .= "<br><br><br>".$i."===>".$prod_model_number."=".$prod_name."=".$prod_description."=".$prod_orgid."=".$prod_brandid."=".$prod_catid."=".$prod_subcatid."=".$prod_quantity."=".$prod_content."=".$prod_list_price."=".$prod_recommended_price."=".$prod_meta_description."=".$prod_meta_tags."=".$prod_meta_title."=".$prod_status."<br>";
			/* Old Code*/
			/*quantity validation*/
			$stock_check="";
			if(is_numeric($prod_quantity))
			{
				$stock_check=1;
			}	
			/*quantity validation*/
			
			/*max and min quantity validation*/
			/* Product Quantity */	
			/*product maximum qty and min qty*/
			
			$prod_min_quantity		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["K"]));
			$prod_max_quantity		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["L"]));
			if($prod_max_quantity == "")
			{
              $prod_max_quantity = 10;
			}
			if($prod_min_quantity == "")
			{
				$prod_min_quantity = 1;
			}
			
        	/*product maximum qty and min qty*/
			$qty_check="";
			if(is_numeric($prod_max_quantity) && $prod_max_quantity >= $prod_min_quantity && is_numeric($prod_min_quantity))
			{
				$qty_check=1;
			}
			/*max and min quantity validation*/
			
			/*list price and recommended price validation*/
			
			$price_check="";
			if(is_numeric($prod_list_price) && $prod_list_price >= $prod_recommended_price && is_numeric($prod_recommended_price) && $prod_list_price != ""  && $prod_recommended_price != "")
			{
				$price_check=1;
				
			} 
			/*list price and recommended price validation*///echo json_encode($qty_check);exit();
			$status123 = 0;
			//echo json_encode($prod_orgid);exit();
			if($prod_model_number != "" && $prod_name != "" && $prod_orgid != "" && $prod_brandid != "" && $prod_catid != "" && $qty_check == 1 && $price_check == 1 && $stock_check == 1)			
			{		
				
				$error_log_data .= "required data is correct";	
				$abc = insertProduct($prod_model_number,$prod_name,$prod_title,$prod_description,$prod_orgid,$prod_brandid,$prod_catid,$prod_google_product_category,$prod_min_quantity,$prod_quantity,$prod_max_quantity,$prod_content,$prod_list_price,$prod_recommended_price,$prod_meta_description,$prod_meta_tags,$prod_meta_title,$prod_status,$batch_specification,$error_specification,$batch_filters,$batch_levels);
				  if($abc){
					  $k++;
				  }
			   
			 }
			 else			
			{  
			    $prod_subcat="";
				$error_data = array("prod_model_number"=>$prod_model_number, "prod_name"=>$prod_name, "prod_description"=>$prod_description, "prod_orgid"=>$prod_org, "prod_brandid"=>$prod_brand, "prod_catid"=>$products_catid, "prod_subcatid"=>$prod_subcat, "prod_google_product_category"=>$prod_google_product_category, "prod_content"=>$prod_content, "prod_quantity"=>$prod_quantity, "prod_min_quantity"=>$prod_min_quantity, "prod_max_quantity"=>$prod_max_quantity, "prod_list_price"=>$prod_list_price, "prod_recommended_price"=>$prod_recommended_price,"prod_meta_tags"=>$prod_meta_tags, "prod_meta_description"=>$prod_meta_description, "prod_meta_title"=>$prod_meta_title, "batch_filters"=>$batch_filters, "batch_levels"=>$batch_levels);
				$error_data_json = json_encode($error_data);
				$error_log_data .= "Error Data".$error_data_json;				
				insertError("product",$inputFileName,1,$error_data_json,$prod_orgid);
			
			
				//$error_data = array("prod_model_number"=>$prod_model_number, "prod_name"=>$prod_name, "prod_description"=>$prod_description, "prod_orgid"=>$prod_org, "prod_brandid"=>$prod_brand, "prod_catid"=>$products_catid, "prod_google_product_category"=>$prod_google_product_category, "prod_content"=>$prod_content, "prod_quantity"=>$prod_quantity, "prod_min_quantity"=>$prod_min_quantity, "prod_max_quantity"=>$prod_max_quantity, "prod_list_price"=>$prod_list_price, "prod_recommended_price"=>$prod_recommended_price,"prod_meta_tags"=>$prod_meta_tags, "prod_meta_description"=>$prod_meta_description, "prod_meta_title"=>$prod_meta_title, "batch_filters"=>$batch_filters, "batch_levels"=>$batch_levels);
			//	echo json_encode($batch_filters);
				
				//$error_data_json = json_encode($error_data);
				//$error_log_data .= "Error Data".$error_data_json;				
			//	insertError("product",$inputFileName,1,$error_data_json);
				
			}	
			
			if($arrayCount == $k)
			{
				$status123 = 1;
			}
			else
			{
				$status123 = 0;
			}
			$new_data .= $prod_description."<br>";
		}		
		
		/* Generate Error Check Excel */		
		$writer 			= new XLSXWriter();
		$writer->setAuthor('Prem Ambodkar');
		$writer->writeSheet($product_data_sheet,'Error Check Excel',$header);
		$timestamp			= date('mdYhis', time());
		if(!file_exists("uploadedExcel/product/ErrorCheckExcel".$timestamp))
		{
			mkdir("uploadedExcel/product/ErrorCheckExcel".$timestamp);
		}
		$writer->writeToFile('uploadedExcel/product/ErrorCheckExcel'.$timestamp.'/ErrorCheckExcel_sheet'.$timestamp.'.xlsx');
		/* Generate Error Check Excel */
		
		/* Old Code*/						
		fwrite($excel_error_log,$error_log_data);
	    fclose($excel_error_log);
		/* Old Code*/			
		$response_array = array("Success"=>"Success","resp2"=>$status123,"resp"=>utf8_encode($new_data),"memory_use"=>memory_get_usage(),"error_excel"=>'uploadedExcel/product/ErrorCheckExcel'.$timestamp.'/ErrorCheckExcel_sheet'.$timestamp.'.xlsx');
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Try to upload Different File");	
	}
	echo json_encode($response_array);}
/* excel file upload */
function imageUpload($prod_img_type,$prod_img_prodid,$prod_img_file_name,$prod_img_name)
{	
	global $uid;
	global $db_con, $datetime;
	
	$sql_check_img					= " SELECT * FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$prod_img_prodid."' and `prod_img_type` = '".$prod_img_type."' ";
	$result_check_img 				= mysqli_query($db_con,$sql_check_img) or die(mysqli_error($db_con));
	$num_rows_check_img				= mysqli_num_rows($result_check_img);
	if($num_rows_check_img != 0)
	{
		//$row_check_img				= mysqli_fetch_array($result_check_img);
		//$sql_delete_img				= " DELETE FROM `tbl_products_images` WHERE `prod_img_id` = '".$row_check_img['prod_img_id']."' ";
		//$result_delete_img 			= mysqli_query($db_con,$sql_delete_img) or die(mysqli_error($db_con));		
	}
	if($prod_img_type == "main")
	{
		$prod_img_sort_order		= 1;
	}
	else
	{		
		$sql_check_sort_ava = " SELECT `prod_img_sort_order` FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$prod_img_prodid."' and prod_img_type = '".$prod_img_type."' ";
		$sql_check_sort_ava .= " ORDER by prod_img_id DESC LIMIT 0,1";
		$result_check_sort_ava = mysqli_query($db_con,$sql_check_sort_ava) or die(mysqli_error($db_con));
		$num_rows_check_sort_ava = mysqli_fetch_array($result_check_sort_ava);
		if($num_rows_check_sort_ava != 0)
		{
			$prod_img_sort_order		= $num_rows_check_sort_ava['prod_img_sort_order']+1;	
		}
		else
		{
			$prod_img_sort_order		= 1;
		}		
	}
	
	$sql_last_rec 		= "Select * from tbl_products_images order by prod_img_id desc LIMIT 0,1";
	$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
	$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
	if($num_rows_last_rec == 0)
	{
		$prod_img_id 		= 1;				
	}
	else
	{
		$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
		$prod_img_id 		= $row_last_rec['prod_img_id']+1;
	}
	$counter = 0;
	if($prod_img_type == "main")
	{
		$sql_check_main_img 		= " SELECT prod_img_id FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$prod_img_prodid."' and `prod_img_type` = '".$prod_img_type."' ";
		$result_check_main_img 		= mysqli_query($db_con,$sql_check_main_img) or die(mysqli_error($db_con));
		$num_rows_check_main_img 	= mysqli_num_rows($result_check_main_img);
		if($num_rows_check_main_img == 0)
		{
			$counter = 1;			
		}
		else
		{
			$counter = 0;			
		}
	}
	elseif($prod_img_type == "sub")
	{
		$counter = 1;
	}
	if($counter == 1)
	{
		$prod_img_status 	= 1;	
		$sql_insert_img_details 		= " INSERT INTO `tbl_products_images`(`prod_img_id`, `prod_img_prodid`, `prod_img_name`, `prod_img_type`,";
		$sql_insert_img_details 		.= " `prod_img_sort_order`, `prod_img_file_name`, `prod_img_status`,`prod_img_created_by`,`prod_img_created`) ";
		$sql_insert_img_details 		.= "  VALUES ('".$prod_img_id."','".$prod_img_prodid."','".$prod_img_name."','".$prod_img_type."',";
		$sql_insert_img_details 		.= "  '".$prod_img_sort_order."','".$prod_img_file_name."','".$prod_img_status."','".$uid."','".$datetime."') ";
		$result_insert_img_details		= mysqli_query($db_con,$sql_insert_img_details) or die(mysqli_error($db_con));
		if($result_insert_img_details)
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
	}}
if((isset($_POST['bulk_image_upload'])) == "1" && isset($_POST['bulk_image_upload']))
{
	//ini_set('max_file_uploads',1000);
	$myFile 			= $_FILES['bulk_image'];
    $fileCount 			= count($myFile["name"]);	
	$limit              = $_POST['file_count'];
	/*done by monika start*/
	if($fileCount > 20)
	{
		$response_array = array("Success"=>"Fail","resp"=>"Image Upload Limit is 20.");		
		echo json_encode($response_array);			
		exit();	
	}
	/*done by monika end*/
	if($fileCount != 0)
	{		
		$data = "";
		$data .=  $fileCount."";
		$extension	= array("jpeg","jpg","png",'JPEG','JPG','PNG');
		for ($i = 0; $i < $fileCount; $i++) 
		{
			$data_file_name 	= explode(".",$myFile["name"][$i]);
			$file_tmp			= $myFile["tmp_name"][$i];
			$prod_img			= $myFile["name"][$i];
			$file_name 			= $data_file_name[0];
			$model_no 			= $data_file_name[0];// image file name is model number
			$file_ext	 		= $data_file_name[1];								
			if(in_array($file_ext,$extension) && file_exists($file_tmp))			
			{
				if(strpos($file_name, '_') !== false )
				{
					$new_file_name 	= explode("_",$file_name);
					$file_name		= $new_file_name[0];
					$prod_img_type	= 'sub';
				}
				else
				{
					$prod_img_type	= 'main';				
				}
				
				$sql_get_product_id = "  SELECT * FROM `tbl_products_master` WHERE `prod_model_number` like  '".$model_no."' ";
				$result_get_product_id = mysqli_query($db_con,$sql_get_product_id) or die(mysqli_error($db_con));
				$num_rows_get_product_id = mysqli_num_rows($result_get_product_id);
				if($num_rows_get_product_id == 1)
				{
					$row_get_product_id	= mysqli_fetch_array($result_get_product_id);
					$prod_img_prodid	= $row_get_product_id['prod_id'];
					$product_name		= $row_get_product_id['prod_name'];			
					$prod_orgid			= $row_get_product_id['prod_orgid'];
					$prod_catid			= $row_get_product_id['prod_catid'];
					$prod_subcatid		= $row_get_product_id['prod_subcatid'];
					$prod_id			= $row_get_product_id['prod_id'];	
					$prod_model_number	= htmlspecialchars($row_get_product_id['prod_model_number'], ENT_HTML5);
					if($prod_img_type == "main")
					{
						$prod_img_file_name	= $prod_model_number;
					}
					else
					{
						$sql_get_prod_img_count 	= " SELECT * FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$prod_id."' and prod_img_type != 'main' ";
						$result_get_prod_img_count 	= mysqli_query($db_con,$sql_get_prod_img_count) or die(mysqli_error($db_con));
						$num_rows_get_prod_img_count= mysqli_num_rows($result_get_prod_img_count);			
						$prod_img_count 			= $num_rows_get_prod_img_count+1;				
						$prod_img_file_name	= $prod_model_number."_".$prod_img_count;				
					}
					$dir 		= "../images/planet/org";
					$org_dir 	= $dir.$prod_orgid;
					if(is_dir($org_dir) === false)
					{
						mkdir($org_dir);
					}
					$cat_dir = $org_dir."/cat".$prod_catid;
					
					if(is_dir($cat_dir) === false)
					{
						mkdir($cat_dir);
					}
					$subcat_dir = $cat_dir."/subcat".$prod_subcatid;		
					if(is_dir($subcat_dir) === false)
					{
						mkdir($subcat_dir);
					}	
					$prod_dir = $org_dir."/prod_id_".$prod_id;		
					if(is_dir($prod_dir) === false)
					{
						mkdir($prod_dir);
					}	
					$prod_tmp_dir = $prod_dir."/".$prod_img;
					if(move_uploaded_file($file_tmp,$prod_tmp_dir))
					{
						$file_path_for_small	= $prod_dir.'/small/';					
						if(is_dir($file_path_for_small) === false)
						{
							mkdir($file_path_for_small);
						}
						
						make_thumb($prod_tmp_dir,$file_path_for_small.$prod_img_file_name.".".$file_ext,100,100);					
						
						$file_path_for_medium	= $prod_dir.'/medium/';
						if(is_dir($file_path_for_medium) === false)
						{
							mkdir($file_path_for_medium);
						}
						make_thumb($prod_tmp_dir,$file_path_for_medium.$prod_img_file_name.".".$file_ext,150,150);
						
						$file_path_for_large	= $prod_dir.'/large/';
						if(is_dir($file_path_for_large) === false)
						{
							mkdir($file_path_for_large);
						}
						make_thumb($prod_tmp_dir,$file_path_for_large.$prod_img_file_name.".".$file_ext,450,450);
						imageUpload($prod_img_type,$prod_img_prodid,$prod_img_file_name.".".$file_ext,$product_name);
					    unlink($prod_tmp_dir);
						$response_array	= array("Success"=>"Success","resp"=>utf8_encode("Success"));
					}
					else
					{
						$response_array	= array("Success"=>"fail","resp"=>utf8_encode($file_tmp."Can not move".$prod_tmp_dir));
					}
				}
				else
				{
					$error_log = "";
				}
			}
			else
			{
				$response_array	= array("Success"=>"fail","resp"=>utf8_encode("Extension ".$file_ext."not Allowed Not A valid File."));
			}
		}			
	}
	else
	{
		$response_array	= array("Success"=>"fail","resp"=>utf8_encode("Their's No Images.File Count is:".$fileCount));
	}
	echo json_encode($response_array);}
if((isset($_POST['insert_product_Images'])) == "1" && isset($_POST['insert_product_Images']))
{
	$prod_img_type		= $_POST['prod_img_type'];
	$prod_img_prodid	= $_POST['prod_img_prodid'];
	if($prod_img_type != "" && $prod_img_prodid != "")
	{
		$sql_get_product		= " SELECT * FROM `tbl_products_master` WHERE `prod_id` = '".$prod_img_prodid."' ";
		$result_get_product		= mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
		$num_rows_get_product 	= mysqli_num_rows($result_get_product);
		if($num_rows_get_product == 0)
		{
			$response_array	= array("Success"=>"fail","resp"=>"Image Upload failed.");			
		}
		else
		{
			$row_get_product	= mysqli_fetch_array($result_get_product);
			$product_name		= $row_get_product['prod_name'];		
			$prod_orgid			= $row_get_product['prod_orgid'];
			$prod_catid			= $row_get_product['prod_catid'];
			$prod_subcatid		= $row_get_product['prod_subcatid'];
			$prod_id			= $row_get_product['prod_id'];
			$prod_model_number	= htmlspecialchars($row_get_product['prod_model_number'], ENT_HTML5);
			if($prod_img_type == "main")
			{
				$prod_img_file_name	= $prod_model_number;
			}
			else
			{
				$sql_get_prod_img_count 	= " SELECT * FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$prod_id."' and prod_img_type != 'main' ";
				$result_get_prod_img_count 	= mysqli_query($db_con,$sql_get_prod_img_count) or die(mysqli_error($db_con));
				$num_rows_get_prod_img_count= mysqli_num_rows($result_get_prod_img_count);			
				$prod_img_count 			= $num_rows_get_prod_img_count+1;				
				$prod_img_file_name	= $prod_model_number."_".$prod_img_count;				
			}			
			
			$dir 		= "../images/planet/org";
			$org_dir 	= $dir.$prod_orgid;
			if(is_dir($org_dir) === false)
			{
				mkdir($org_dir);
			}
			$cat_dir = $org_dir."/cat".$prod_catid;
			
			if(is_dir($cat_dir) === false)
			{
				mkdir($cat_dir);
			}
			$subcat_dir = $cat_dir."/subcat".$prod_subcatid;		
			if(is_dir($subcat_dir) === false)
			{
				mkdir($subcat_dir);
			}	
			$prod_dir = $org_dir."/prod_id_".$prod_id;		
			if(is_dir($prod_dir) === false)
			{
				mkdir($prod_dir);
			}			

			$extension	= array("jpeg","jpg","png","gif");
			if(trim($_FILES['prod_img_file_name']['name']) != "")
			{
				$prod_img	= $_FILES["prod_img_file_name"]["name"];
                $file_tmp	= $_FILES["prod_img_file_name"]["tmp_name"];
				$fileSize	= $_FILES['prod_img_file_name']['size'];
				$file_ext	= strtolower(end(explode('.',$prod_img)));
				//$m_prod= $prod_img;
				
				if(in_array($file_ext,$extension) && file_exists($file_tmp))
				{
					$prod_tmp_dir = $prod_dir."/".$prod_img;
					if(move_uploaded_file($file_tmp,$prod_tmp_dir))
					{
						$file_path_for_small	= $prod_dir.'/small/';					
						if(is_dir($file_path_for_small) === false)
						{
							mkdir($file_path_for_small);
						}
						//$test1=$file_path_for_small.$prod_img_file_name.".".$file_ext;
						
						make_thumb($prod_tmp_dir,$file_path_for_small.$prod_img_file_name.".".$file_ext,100,100);					
						
						$file_path_for_medium	= $prod_dir.'/medium/';
						if(is_dir($file_path_for_medium) === false)
						{
							mkdir($file_path_for_medium);
						}
						make_thumb($prod_tmp_dir,$file_path_for_medium.$prod_img_file_name.".".$file_ext,150,150);
						
						$file_path_for_large	= $prod_dir.'/large/';
						if(is_dir($file_path_for_large) === false)
						{
							mkdir($file_path_for_large);
						}
						make_thumb($prod_tmp_dir,$file_path_for_large.$prod_img_file_name.".".$file_ext,450,450);
/*						$imagepath_small = "../../images/planet/org".$row_get_product['prod_orgid']."/cat".$row_get_product['prod_catid']."/subcat".$row_get_product['prod_subcatid']."/prod".$row_get_product['prod_id']."/small/".$prod_img_file_name.".".$file_ext;
						$imagepath_medium = "../../images/planet/org".$row_get_product['prod_orgid']."/cat".$row_get_product['prod_catid']."/subcat".$row_get_product['prod_subcatid']."/prod".$row_get_product['prod_id']."/medium/".$prod_img_file_name.".".$file_ext;						
						$imagepath_large = "../../images/planet/org".$row_get_product['prod_orgid']."/cat".$row_get_product['prod_catid']."/subcat".$row_get_product['prod_subcatid']."/prod".$row_get_product['prod_id']."/large/".$prod_img_file_name.".".$file_ext;							
						if(file_exists($imagepath_small) && file_exists($imagepath_medium) && file_exists($imagepath_large))
						{*/
							imageUpload($prod_img_type,$prod_img_prodid,$prod_img_file_name.".".$file_ext,$product_name);
							unlink($prod_tmp_dir);
							$response_array	= array("Success"=>"Success","resp"=>utf8_encode("Success"));
						/*}
						else
						{							
							$response_array	= array("Success"=>"Success","resp"=>"fail".$imagepath_small."=".$imagepath_large);
						}*/
					}
					else
					{
						$response_array	= array("Success"=>"fail","resp"=>utf8_encode("Can not move"));
					}
				}
				else
				{
					$response_array	= array("Success"=>"fail","resp"=>utf8_encode("File not exists or extension not supported"));
				}
			}
			else
			{
				$response_array	= array("Success"=>"fail","resp"=>utf8_encode(" file not available"));
			}						
		}
	}
	else
	{
		$response_array	= array("Success"=>"fail","resp"=>utf8_encode("Something went wrong please try after some time."));
	}
	echo json_encode($response_array);}
if((isset($obj->load_add_prod_img_part)) == "1" && isset($obj->load_add_prod_img_part))
{
	$prod_id	= $obj->prod_id;
	if($prod_id != "")
	{
		$data .= '<input type="hidden" name="prod_img_prodid" id="prod_img_prodid" value="'.$prod_id.'">';
		$data .= '<input type="hidden" name="insert_product_Images" id="insert_product_Images" value="1">';
		$sql_check_prod_main_img = " SELECT * FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$prod_id."' and `prod_img_type` = 'main' ";
		$result_check_main_img = mysqli_query($db_con,$sql_check_prod_main_img) or die(mysqli_error($db_con));
		$num_rows_check_main_img = mysqli_num_rows($result_check_main_img);
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Image Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select name="prod_img_type" id="prod_img_type" class="select2-me input-large" data-rule-required="true">';
		if($num_rows_check_main_img == 0)
		{
			$data .= '<option value="">Select Type</option>';			
			$data .= '<option value="main">Main Image</option>';
			$data .= '<option value="sub">Sub Image</option>';			
		}
		else
		{
			$data .= '<option value="sub" selected="selected">Sub Image</option>';
		}				
		$data .= '</select>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Image<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="file" id="prod_img_file_name" name="prod_img_file_name" class="input-large" data-rule-required="true" multiple />';
		$data .= '</div>';
		$data .= '</div>';	
		$data .= '<div class="form-actions">';
		$data .= '<button type="submit" name="reg_submit_add_prod_img" class="btn-success">Save Image</button>';			
		$data .= '</div> <!-- Save and cancel -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#prod_img_type").select2();';
		$data .= '</script>';
		$response_array	= array("Success"=>"Success","resp"=>utf8_encode($data));
	}
	else
	{
		$response_array	= array("Success"=>"Success","resp"=>utf8_encode("Image Upload Fail."));
	}
	echo json_encode($response_array);}
if((isset($obj->load_prod_Images)) == "1" && isset($obj->load_prod_Images))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= trim(mysqli_real_escape_string($db_con,$obj->page_no_prod_img));	
	$per_page		= trim(mysqli_real_escape_string($db_con,$obj->row_limit_prod_img));
	$search_text	= trim(mysqli_real_escape_string($db_con,$obj->search_text_prod_img));
	$prod_id		= trim(mysqli_real_escape_string($db_con,$obj->prod_img_prodid));
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
		
		$sql_load_data  = " SELECT `prod_img_id`, `prod_img_name`,`prod_img_type`,`prod_img_prodid`, `prod_img_file_name`, `prod_img_status`, `prod_img_created`, `prod_img_modified`,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id =prod_img_created_by) as created_name,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id =prod_img_modified_by) as modified_name";
		$sql_load_data  .= "  FROM `tbl_products_images` WHERE 1=1";
		if($search_text != "")
		{
			$sql_load_data .= " AND `prod_img_id` like '%".$search_text."%' ";
		}
		if($prod_id != "")
		{
			$sql_load_data .= " AND prod_img_prodid = '".$prod_id."' ";			
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$prod_data = "";	
			$prod_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$prod_data .= '<thead>';
    	  	$prod_data .= '<tr>';
         	$prod_data .= '<th>Sr. No.</th>';
         	$prod_data .= '<th>Img Id.</th>';						
			$prod_data .= '<th>Product Name</th>';
			$prod_data .= '<th>Image Type</th>';			
			$prod_data .= '<th>Image</th>';			
			$prod_data .= '<th>Image</th>';
			$prod_data .= '<th>Created By</th>';
			$prod_data .= '<th>Created</th>';
			$prod_data .= '<th>Modified By</th>';
			$prod_data .= '<th>Modified</th>';	
			$dis = checkFunctionalityRight("view_products.php",3);
			if($dis)
			{					
				$prod_data .= '<th>Status</th>';						
			}
			$del = checkFunctionalityRight("view_products.php",2);
			$del = 1;
			if($del)
			{			
				$prod_data .= '<th><div class="center-text"><input type="button"  value="Delete" onclick="deleteProductImages();" class="btn-danger"/></div></th>';
			}
          	$prod_data .= '</tr>';
      		$prod_data .= '</thead>';
      		$prod_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{				
	    	  	$prod_data .= '<tr>';				
				$prod_data .= '<td>'.++$start_offset.'</td>';
				$prod_data .= '<td>'.$row_load_data['prod_img_id'].'</td>';					
				$prod_data .= '<td>'.$row_load_data['prod_img_name'].'</td>';
				$prod_data .= '<td>'.ucwords($row_load_data['prod_img_type']).'</td>';				
				$prod_data .= '<td>'.$row_load_data['prod_img_file_name'].'</td>';				
				$sql_get_product		= " SELECT * FROM `tbl_products_master` WHERE `prod_id` = '".$row_load_data['prod_img_prodid']."' ";
				$result_get_product		= mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
				$num_rows_get_product 	= mysqli_num_rows($result_get_product);
				$row_get_product		= mysqli_fetch_array($result_get_product);			
				$prod_orgid				= $row_get_product['prod_orgid'];
				$prod_catid				= $row_get_product['prod_catid'];
				$prod_subcatid			= $row_get_product['prod_subcatid'];
				$prod_id				= $row_get_product['prod_id'];				
				$dir 		= "../images/planet/org";
				$org_dir 	= $dir.$prod_orgid;
				//$cat_dir 	= $org_dir."/cat".$prod_catid;
				//$subcat_dir = $cat_dir."/subcat".$prod_subcatid;		
				$prod_dir 	= $org_dir."/prod_id_".$prod_id;					
				$imagepath	= $prod_dir."/small/".$row_load_data['prod_img_file_name'];
				$prod_data .= '<td><img src="'.$imagepath.'?'.rand().'"/></td>';
				$prod_data .= '<td>'.$row_load_data['created_name'].'</td>';
				$prod_data .= '<td>'.$row_load_data['prod_img_created'].'</td>';
				$prod_data .= '<td>';
				if($row_load_data['modified_name'] == "" || $row_load_data['modified_name'] > 1)
				{
					$prod_data .='<span style="font-style:italic;">Not Modified yet</span>';
				}
				else
				{
					$prod_data .= $row_load_data['modified_name'];
				}
				$prod_data .= '</td>';
				$prod_data .= '<td>';
				if($row_load_data['prod_img_modified'] == "0000-00-00 00:00:00")
				{
					$prod_data .= '<span style="font-style:italic;">Not Modified yet</span>';
				}
				else
				{
					$prod_data .= $row_load_data['prod_img_modified'];
				}
				$prod_data .= '</td>';
				$dis = checkFunctionalityRight("view_products.php",3);
				if($dis)
				{					
					$prod_data .= '<td class="center-text">';	
					if($row_load_data['prod_img_status'] == 1)
					{
						$prod_data .= '<input type="button" value="Active" id="'.$row_load_data['prod_img_id'].'" class="btn-success" onclick="changeProductImagesStatus(this.id,0);">';
					}
					else
					{
						$prod_data .= '<input type="button" value="Inactive" id="'.$row_load_data['prod_img_id'].'" class="btn-danger" onclick="changeProductImagesStatus(this.id,1);">';
					}
					$prod_data .= '</td>';	
				}
				$del = checkFunctionalityRight("view_products.php",2);
				$del = 1;
				if($del)
				{					
					$prod_data .= '<td><div class="center-text">';
					$prod_data .= '<input type="checkbox" value="'.$row_load_data['prod_img_id'].'" id="batch_prod_img'.$row_load_data['prod_img_id'].'" name="batch_prod_img'.$row_load_data['prod_img_id'].'" class="css-checkbox batch_prod_img">';
					$prod_data .= '<label for="batch_prod_img'.$row_load_data['prod_img_id'].'" class="css-label"></label>';				
					$prod_data .= '</div></td>';										
				}
	          	$prod_data .= '</tr>';															
			}	
      		$prod_data .= '</tbody>';
      		$prod_data .= '</table>';	
			$prod_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>utf8_encode($prod_data));
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>utf8_encode("No Data Available"));
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>utf8_encode("No Row Limit and Page Number Specified for product Images"));
	}
	echo json_encode($response_array);	}
if((isset($obj->change_prod_Images_status)) == "1" && isset($obj->change_prod_Images_status))
{
	$prod_img_id			= mysqli_real_escape_string($db_con,$obj->prod_img_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();		
	$sql_update_status 		= " UPDATE `tbl_products_images` SET `prod_img_status`= '".$curr_status."' ,`prod_img_modified` = '".$datetime."' ,`prod_img_modified_by` = '".$uid."' WHERE `prod_img_id` = '".$prod_img_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	if($result_update_status)
	{
		$response_array = array("Success"=>"Success","resp"=>utf8_encode("Status Updated Successfully."));
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>utf8_encode("Status Update Failed."));
	}		
	echo json_encode($response_array);	}
if((isset($obj->delete_product_Images)) == "1" && isset($obj->delete_product_Images))
{
	$response_array = array();		
	$ar_prod_id 	= $obj->batch_prod_img;
	$del_flag 		= 0; 
	foreach($ar_prod_id as $prod_img_id)	
	{
		$sql_prod_image_info	= " SELECT prod_orgid,prod_catid,prod_subcatid,prod_id,prod_model_number ";
		$sql_prod_image_info	.= " FROM tbl_products_master where prod_id = (SELECT prod_img_prodid from tbl_products_images where prod_img_id = '".$prod_img_id."' )";
		$result_prod_image_info	= mysqli_query($db_con,$sql_prod_image_info) or die(mysqli_error($db_con));
		$row_prod_image_info	= mysqli_fetch_array($result_prod_image_info);
		$prod_orgid				= $row_prod_image_info['prod_orgid'];
		$prod_catid				= $row_prod_image_info['prod_catid'];
		$prod_subcatid			= $row_prod_image_info['prod_subcatid'];
		$prod_id				= $row_prod_image_info['prod_id'];	
		$prod_model_number		= $row_prod_image_info['prod_model_number'];			
		$dir 					= "../images/planet/org";
		$org_dir 				= $dir.$prod_orgid;
		$cat_dir 				= $org_dir."/cat".$prod_catid;
		$subcat_dir 			= $cat_dir."/subcat".$prod_subcatid;		
		$prod_dir 				= $subcat_dir."/prod".$prod_id;

		$sql_prod_img			= " SELECT prod_img_file_name FROM `tbl_products_images` WHERE `prod_img_id`='".$prod_img_id."' ";
		$result_prod_img		= mysqli_query($db_con,$sql_prod_img) or die(mysqli_error($db_con));
		$row_prod_img			= mysqli_fetch_array($result_prod_img);
		if(file_exists($prod_dir."/small/".$row_prod_img['prod_img_file_name']))
		{
			unlink($prod_dir."/small/".$row_prod_img['prod_img_file_name']);
		}
		if(file_exists($prod_dir."/medium/".$row_prod_img['prod_img_file_name']))
		{
			unlink($prod_dir."/medium/".$row_prod_img['prod_img_file_name']);
		}
		if(file_exists($prod_dir."/large/".$row_prod_img['prod_img_file_name']))
		{
			unlink($prod_dir."/large/".$row_prod_img['prod_img_file_name']);
		}
		
		$sql_del_prod_spec		= " DELETE FROM `tbl_products_images` WHERE `prod_img_id`='".$prod_img_id."' ";
		$res_del_prod_spec		= mysqli_query($db_con, $sql_del_prod_spec) or die(mysqli_error($db_con));		
		if($res_del_prod_spec)
		{			
			$response_array = array("Success"=>"Success","resp"=>utf8_encode("Record Deletion Success."));
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>utf8_encode("Record Deletion failed."));
		}
	}
	echo json_encode($response_array);	}
function updatePrice($prod_id,$prod_list_price,$prod_recommended_price,$discount_type,$discount_value)
{
	global $db_con;
	$new_price				= $prod_recommended_price;
	if($discount_type == "flat" && $discount_value <= $prod_list_price)
	{
		$new_price			= round($prod_list_price - $discount_value);
	}
	if($discount_type == "flat" && $discount_value > $prod_list_price)
	{
		$response_array = array("Success"=>"fail","resp"=>utf8_encode("Discount value should be less than or equal to list price."));
		echo json_encode($response_array);
		exit();
	}
	if($discount_type == "percent"  && $discount_value <= 100 || $discount_value == "")
	{
		$discount_amount 	=  (($discount_value/100)* $prod_list_price);
		$new_price			= round($prod_list_price - $discount_amount);
	}
	if($discount_type == "percent" && $discount_value > 100)
	{
		$response_array = array("Success"=>"fail","resp"=>utf8_encode("Discount value should be less than or equal to 100%"));
		echo json_encode($response_array);
		exit();
	}
	if($new_price >= 0)
	{
		$sql_update_product_price 		= " UPDATE `tbl_products_master` SET `prod_recommended_price`= '".$new_price."', `prod_org_price`= '".$new_price."' WHERE prod_id = '".$prod_id."' ";
		$result_update_product_price 	= mysqli_query($db_con,$sql_update_product_price) or die(mysqli_error($db_con));
		if($result_updaprod_list_pricete_product_price)
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
if((isset($obj->apply_product_discount)) == "1" && isset($obj->apply_product_discount))
{
	$discount_value			= mysqli_real_escape_string($db_con,$obj->discount_value);
	$discount_type			= mysqli_real_escape_string($db_con,$obj->discount_type);
	
	$sql_get_products 		= " SELECT distinct `prod_id`, `prod_list_price`, `prod_recommended_price`, `prod_org_price` from tbl_products_master tpm ";
	if($obj->fun_for == 1) // 1 = organisation
	{
		$org_id				= mysqli_real_escape_string($db_con,$obj->sent_id);	
		$sql_get_products 	.= " where tpm.prod_orgid = '".$org_id."' ";
	}
	elseif($obj->fun_for == 2) // 2 = product
	{
		$prod_id			= mysqli_real_escape_string($db_con,$obj->sent_id);
		$sql_get_products 	.= " where tpm.prod_id = '".$prod_id."' ";		
	}
	elseif($obj->fun_for == 3) // 3 = category
	{
		$cat_id				= mysqli_real_escape_string($db_con,$obj->sent_id);
		$sql_get_products 	.= " where tpm.prod_catid = '".$cat_id."' or tpm.prod_subcatid = '".$cat_id."' ";
	}	
	elseif($obj->fun_for == 4) // 4 = level
	{
		$cat_id				= mysqli_real_escape_string($db_con,$obj->sent_id);
		$sql_get_products 	.= " INNER JOIN tbl_product_levels tpl ON tpm.prod_id = tpl.prodlevel_prodid ";
		$sql_get_products 	.= " where prodlevel_levelid_parent = '".$level_id."' or prodlevel_levelid_child = '".$level_id."' ";
	}
	elseif($obj->fun_for == 5) // 4 = level
	{
		$brand_id				= mysqli_real_escape_string($db_con,$obj->sent_id);
		$sql_get_products 		.= " where tpm.prod_brandid = '".$brand_id."' ";		
	}	
	$result_get_products	= mysqli_query($db_con,$sql_get_products) or die(mysqli_error($db_con));
	$num_rows_get_products	= mysqli_num_rows($result_get_products);
	if($num_rows_get_products != 0)
	{
		$prod_details 		= "";
		while($row_get_products = mysqli_fetch_array($result_get_products))
		{
			$prod_id				= $row_get_products['prod_id'];
			$prod_list_price		= $row_get_products['prod_list_price'];
			$prod_recommended_price	= $row_get_products['prod_recommended_price'];
			if(!updatePrice($prod_id,$prod_list_price,$prod_recommended_price,$discount_type,$discount_value))
			{
				$prod_details .= "Product id:".$prod_id;
			}
		}
		$response_array 	= array("Success"=>"Success","resp"=>"Products Price Updated!!!","prod"=>$prod_details);
	}
	else
	{
		$response_array 	= array("Success"=>"fail","resp"=>"No Products Available!!!");
	}
	echo json_encode($response_array);}
/* Product Images Operations */
?>
