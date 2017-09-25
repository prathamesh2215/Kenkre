<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

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
	
	/*done by monika for filter delete on 28-12-2016*/
	if($prodfilt_status != 0)
	{
		$sql_update_filters 		 = " UPDATE `tbl_product_filters` SET `prodfilt_status`='".$prodfilt_status."',`prodfilt_modified`=now(),";
		$sql_update_filters 		.= " `prodfilt_modified_by`='".$uid."' WHERE `prodfilt_prodid` = '".$prodfilt_prodid."' and ";
		$sql_update_filters 		.= " `prodfilt_filtid_parent` = '".$prodfilt_filtid_parent."' and `prodfilt_filtid_child` = '".$prodfilt_filtid_child."' and `prodfilt_filtid_sub_child` = '".$prodfilt_filtid_sub_child."' ";
			
	}
	elseif($prodfilt_status == 0)
	{
		$sql_update_filters 		 = " DELETE FROM `tbl_product_filters`";
		$sql_update_filters 		.= " WHERE `prodfilt_prodid` = '".$prodfilt_prodid."' and ";
		$sql_update_filters 		.= " `prodfilt_filtid_parent` = '".$prodfilt_filtid_parent."' and `prodfilt_filtid_child` = '".$prodfilt_filtid_child."' and `prodfilt_filtid_sub_child` = '".$prodfilt_filtid_sub_child."' ";
			
	}
	/*done by monika for filter delete on 28-12-2016*/
				
	/*$sql_update_filters 		= " UPDATE `tbl_product_filters` SET `prodfilt_status`='".$prodfilt_status."',`prodfilt_modified`='".$datetime."',";
	$sql_update_filters 		.= " `prodfilt_modified_by`='".$uid."' WHERE `prodfilt_prodid` = '".$prodfilt_prodid."' and ";
	$sql_update_filters 		.= " `prodfilt_filtid_parent` = '".$prodfilt_filtid_parent."' and `prodfilt_filtid_child` = '".$prodfilt_filtid_child."' and `prodfilt_filtid_sub_child` = '".$prodfilt_filtid_sub_child."' ";*/
	
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
	
	/*done by monika for level delete on 28-12-2016*/
	if($prodlevel_status != 0)
	{
		$sql_update_levels  = " UPDATE `tbl_product_levels` SET `prodlevel_status`='".$prodlevel_status."',`prodlevel_modified`=now(),";
		$sql_update_levels .= " `prodlevel_modified_by`='".$uid."' WHERE `prodlevel_prodid` = '".$prodlevel_prodid."' and `prodlevel_levelid_parent` = '".$prodlevel_levelid_parent."' and `prodlevel_levelid_child` = '".$prodlevel_levelid_child."' ";
	}
	elseif($prodlevel_status == 0)
	{
		$sql_update_levels  = " DELETE FROM  `tbl_product_levels`";
		$sql_update_levels .= " WHERE `prodlevel_prodid` = '".$prodlevel_prodid."' and `prodlevel_levelid_parent` = '".$prodlevel_levelid_parent."' and `prodlevel_levelid_child` = '".$prodlevel_levelid_child."' ";
	}
	/*done by monika for level delete on 28-12-2016*/
	
	/*$sql_update_levels = " UPDATE `tbl_product_levels` SET `prodlevel_status`='".$prodlevel_status."',`prodlevel_modified`='".$datetime."',";
	$sql_update_levels .= " `prodlevel_modified_by`='".$uid."' WHERE `prodlevel_prodid` = '".$prodlevel_prodid."' and `prodlevel_levelid_parent` = '".$prodlevel_levelid_parent."' and `prodlevel_levelid_child` = '".$prodlevel_levelid_child."' ";*/
	
	$result_update_levels = mysqli_query($db_con,$sql_update_levels) or die(mysqli_error($db_con));
	if($result_update_levels)
	{
		return true;
	}
	else
	{
		return false;
	}
}


function catid_for_upload_prod($cat_name,$chk_parent_array)
{
	    global $db_con;
		
		$sql_get_catid			= " SELECT * FROM `tbl_category` WHERE `cat_name` like '".$cat_name."' ";
		if(!empty($chk_parent_array))
		{
			$array_index        = sizeof($chk_parent_array)-1;
			$sql_get_catid	   .= " AND  cat_type ='".$chk_parent_array[$array_index]."' ";
		}
		
		$res_get_catid			= mysqli_query($db_con, $sql_get_catid) or die(mysqli_error($db_con));
		$num_rows_get_catid		= mysqli_num_rows($res_get_catid);
		if($num_rows_get_catid !=0)
		{
			 $row_get_catid			= mysqli_fetch_array($res_get_catid);
		     return $row_get_catid['cat_id'];
		}
		else
		{
			return 0;
		}
}

function UpdateData($prod_model_number,$prod_recommended_price, $prod_list_price, $prod_org_price, $product_name, $product_desc, $prod_qty, $prod_max_qty, $response_array)
{
	global $obj;
	global $db_con, $datetime;
	global $uid;
		
	$sqlfetch = "SELECT * FROM `tbl_products_master` WHERE `prod_model_number`='".$prod_model_number."' ";	
	$resultfetch = mysqli_query($db_con,$sqlfetch) or die(mysqli_error($db_con));
	$rowfetch = mysqli_fetch_array($resultfetch);
    if(is_numeric($prod_list_price) && $prod_list_price >= 0 && $prod_list_price >= is_numeric($prod_recommended_price))
	{

        $prod_list_price = $prod_list_price;
	}
    else 
	{  
         $prod_list_price = $rowfetch['prod_list_price'];
    }
        
    if(is_numeric($prod_recommended_price) && $prod_recommended_price >= 0 && $prod_recommended_price <= is_numeric($prod_list_price)){

         $prod_recommended_price = $prod_recommended_price;
	}
    else 
	{  
          $prod_recommended_price = $rowfetch['prod_recommended_price'];
    }

	$sql_insert_ind  =" UPDATE `tbl_products_master` ";
	$sql_insert_ind .= " 	SET ";
	if($prod_list_price != '' && $prod_list_price >= $prod_recommended_price && is_numeric($prod_list_price) && $prod_list_price >= 0 )
	{
		$sql_insert_ind .= " 		`prod_list_price`='".$prod_list_price."' ,"; 
	}
	
	if($prod_recommended_price != '' && $prod_list_price >= $prod_recommended_price && is_numeric($prod_recommended_price) && $prod_recommended_price >= 0 )
	{
		$sql_insert_ind .= " 		`prod_recommended_price`='".$prod_recommended_price."' ,";
		$sql_insert_ind .= " 		`prod_org_price`='".$prod_recommended_price."' ,";
	}

	if($prod_model_number != '')
	{
		$sql_insert_ind .= " 		`prod_model_number`='".$prod_model_number."' ,";
	}

	if($product_name != '')	
	{
		$sql_insert_ind	.= "		`prod_name`='".$product_name."', ";
	}

	if($product_desc != '')
	{
		$sql_insert_ind	.= "		`prod_description`='".$product_desc."', ";
	}

	if($prod_qty != '' && is_numeric($prod_qty) && $prod_qty >= 0)
	{
		$sql_insert_ind .= " 		`prod_quantity`='".$prod_qty."', ";
		//$sql_insert_ind .= " 		`prod_max_quantity`='".$prod_qty."', ";
	}

	$sql_insert_ind .= " 		`prod_modified`='".$datetime."' ";

	$sql_insert_ind .= " WHERE `prod_model_number`='".$prod_model_number."' ";
	//$response_array = array("Success"=>"Success","resp"=>$sql_insert_ind);
			
	//echo json_encode($response_array);exit();
	$result_insert_ind = mysqli_query($db_con,$sql_insert_ind) or die(mysqli_error($db_con));
	if($result_insert_ind)
	{
		if(isset($obj->error_id) && (isset($obj->insert_req)) != "")
		
		{
			$response_array = errorDataDelete($obj->error_id);
		}
		else
		{
			$response_array = array("Success"=>"Success","resp"=>"Data Updated Successfully");					
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Not Updated.");					
	}			
	return $response_array;
}
	
	
if(isset($_FILES['file']))
{       
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$prod_id	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$counter= 0;
	$response_array = array("ghv");
	
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
	
	/* Generate Error Check Excel */		
	include_once("xlsxwriter.class.php");	
	/* Generate Error Check Excel */	
	
	/////////=====================Start : Done By satish ==========================/////////////////
	
	
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
			'Min quantity'=>'string',
			'Max quantity'=>'string',
		    'List Price'=>'string',						
			'Recommended Price'=>'string',
			'Meta Description'=>'string',
			'Meta Title'=>'string',
			'Meta Tags'=>'string',	
			'Specification'=>'string',
			'Filters'=>'string',
			'Levels'=>'string',
			'Errors'=>'Errors',
		);	
	
	
	///////======================End : Done By satish ============================/////////////////
	$error_arr    =  array();
	
	if(strcmp($inputFileName, "")!==0)
	{
           
		for($i=2;$i<=$arrayCount;$i++)
		{	
		    $flag         =1;
			$update_error ='';		
			/* Product Model Number */
			
			
			
			$prod_model_number		= mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"]);
			
			
			$prod_name1				= htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["B"]))), ENT_HTML5);
			$prod_name1             = str_replace(" - ","-",$prod_name1);
			$prod_name1             = str_replace(" -","-",$prod_name1);
			$prod_name1             = str_replace("- ","-",$prod_name1);
			
			
			$prod_title_1			=htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["C"]))), ENT_HTML5);
			$prod_description1		= htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["D"])), ENT_HTML5);
			$prod_org				= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"]));
			$prod_brand				= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"]));
			$prod_catid_txt			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["G"]));
			$prod_google_product_category = str_replace("'","",trim($allDataInSheet[$i]["H"]));
			$prod_content1		= str_replace("'","",trim($allDataInSheet[$i]["I"]));
			$prod_quantity			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["J"]));
			$prod_min_quantity		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["K"]));
			$prod_max_quantity		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["L"]));
			$prod_list_price		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["M"]));										
			$prod_recommended_price	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["N"]));
			$prod_meta_description_1		= htmlspecialchars(str_replace("'","",trim($allDataInSheet[$i]["O"])), ENT_HTML5);
			$prod_meta_title		= htmlspecialchars(trim(mysqli_real_escape_string($db_con,trim($allDataInSheet[$i]["P"]))), ENT_HTML5);
			$prod_meta_tags			= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["Q"])), ENT_HTML5);
			$prod_specification_list	= htmlspecialchars(trim(mysqli_real_escape_string($db_con,str_replace("\n","",$allDataInSheet[$i]["R"]))), ENT_HTML5);
			$prod_filters_list		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["S"]));
			$prod_level				= htmlspecialchars(trim($allDataInSheet[$i]["T"]), ENT_HTML5);
			
			$sql_check_model_num    =" SELECT * FROM tbl_products_master WHERE prod_model_number='".$prod_model_number."'";
		
			$res                    = mysqli_query($db_con,$sql_check_model_num) or die(mysqli_error($db_con));	
				
			if(mysqli_num_rows($res)==1)// res check if start
			{
				//echo json_encode(array("Success"=>'fail','resp'=>mysqli_num_rows($res)));exit();
				
				$prod_row           = mysqli_fetch_array($res);
				$prod_id            = $prod_row['prod_id'];
				///==========================Name====================//
				$prod_name				= mysqli_real_escape_string($db_con,htmlspecialchars(trim($prod_name1), ENT_HTML5));
				if($prod_name=="")
				{
					$prod_name = mysqli_real_escape_string($db_con,$prod_row['prod_name']);
				}
				
				//===========================Title====================//
				$prod_title				= mysqli_real_escape_string($db_con,htmlspecialchars(trim($prod_title_1), ENT_HTML5));
				if($prod_title=="")
				{
					$prod_title = mysqli_real_escape_string($db_con,$prod_row['prod_title']);
				}
				
				//===========================Description=================//
				$prod_description		=mysqli_real_escape_string($db_con, htmlspecialchars($prod_description1, ENT_HTML5));
				if($prod_description=="")
				{
					$prod_description =mysqli_real_escape_string($db_con, $prod_row['prod_description']);
				}
				
				//============================Orgnisation =================//
				if($prod_org !="")
				{
					$sql_get_orgid			= " SELECT * FROM `tbl_oraganisation_master` WHERE `org_name` like '".$prod_org."' ";
					$res_get_orgid			= mysqli_query($db_con, $sql_get_orgid) or die(mysqli_error($db_con));
					$num_rows_get_orgid		= mysqli_num_rows($res_get_orgid);
					$row_get_orgid		= mysqli_fetch_array($res_get_orgid);
					if($row_get_orgid['org_id'] != "") // if org id is not empty then will give exact org id
					{
						$prod_orgid		= $row_get_orgid['org_id'];
						$prod_org      =  $row_get_orgid['org_name'];
					}
					else
					{
						$update_error .=' Organisation Not found ,';
						$flag=0;
					}
				}
				else
				{
					$prod_orgid =$prod_row['prod_orgid'];
				}
				
				//=============================Brand===========================//
				if($prod_brand !="")
				{
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
							$update_error .=' Brand Not found ,';
							$flag =0;
						}				
					}
					else
					{
						$prod_brandid		=$prod_row['prod_brandid'];
						$update_error .=' Brand Not found ,';
						$flag =0;
					}
				}
				else
				{
					$prod_brandid		=$prod_row['prod_brandid'];
				}
				
				$products_catid         = array();	
				
				$product_cat_names      = explode(',',$prod_catid_txt);
				
				$data ='';
				for($j=0;$j<sizeof($product_cat_names);$j++)
				{
						$pr_cat_name            =	explode('>',$product_cat_names[$j]);
						$chk_parent_array       = array();
						for($c=0;$c<sizeof($pr_cat_name);$c++)
						{
							$cat_name = trim(mysqli_real_escape_string($db_con,$pr_cat_name[$c]));
							$cat_id =catid_for_upload_prod($cat_name,$chk_parent_array);
							array_push($chk_parent_array,$cat_id);
						}
						
						  if($cat_id !=0)
						  {
							  array_push($products_catid,$cat_id);
						  }
					  $data .=' // ';
					}// for end
					
				//============Category ===============//	
				$data .=implode(',',$products_catid);
				if(count($products_catid) == 0)
				{
				    $prod_catid="";
				}
				
				//===========Google Product Category============//
				
				if($prod_google_product_category=="")
				{
					$prod_google_product_category =$prod_row['prod_google_product_category'];
				}
				
			    //===========Content============//
				
				$prod_content		= mysqli_real_escape_string($db_con,htmlspecialchars($prod_content1, ENT_HTML5));
				if($prod_content=="")
				{
					$prod_content =mysqli_real_escape_string($db_con,$prod_row['prod_content']);
				}
				
				//================Spaecification=================//
				
				$batch_specification	= array();
				$error_specification	= array();
				
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
				
				//===================Filters=======================//
				
				$batch_filters;
			
				
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
					$batch_filters	= array();
				}	
				
				
				///================Levels ========================//
				$batch_levels;
				
	
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
			 
			     //================================Quantity====================================//
				 
				
				 
				if(trim($prod_quantity) == "")
				{
					$prod_quantity		=$prod_row['prod_quantity'];
				}
				else
				{  
				   if($prod_quantity < 0 && !is_numeric($prod_quantity))
				   {
					   $update_error .=' Product Quantity Should Not be less than zero ,';
						$flag =0;
				   }
				}
				
				if($prod_max_quantity == "")
				{
				  $prod_max_quantity =$prod_row['prod_max_quantity'];;
				}
				else
				{
					if($prod_min_quantity == "")
					{
						$prod_min_quantity =$prod_row['prod_min_quantity'];
					}
					if($prod_max_quantity < $prod_min_quantity)
					{
						$update_error .=' Max quantity less than min quantity ';
						$flag          =0;
					}
				}
				if($prod_min_quantity == "")
				{
					$prod_min_quantity =$prod_row['prod_min_quantity'];
				}
				else
				{
					if($prod_max_quantity == "")
					{
						$prod_max_quantity =$prod_row['prod_max_quantity'];
					}
					if($prod_max_quantity < $prod_min_quantity)
					{
						$update_error .=' Min quantity less than max quantity';
						$flag          =0;
					}
				}
			    
			
			    //  ==============================Price=======================================//
				
				if($prod_list_price !="" && $prod_recommended_price !="")
				{
					if(is_numeric($prod_list_price) && is_numeric($prod_recommended_price))
					{
						if($prod_list_price < $prod_recommended_price)
						{
							$update_error .=' List Price can not be less than recommended price ';
							$flag          =0;
						}
					}
					else
					{
						$update_error .=' Please Check Price ';
						$flag          =0;
					}
				}
				else
				{
				    $prod_list_price        = $prod_row['prod_list_price'];
				    $prod_recommended_price = $prod_row['prod_recommended_price'];
				}
				
				//=================================Meta Description ============================//
			
				$prod_meta_description		    = htmlspecialchars($prod_meta_description_1, ENT_HTML5);	
				if($prod_meta_description=="")
				{
					$prod_meta_description = $prod_row['prod_meta_description'];
				}
				//==================================Meta Titlt====================================//	
				
				$prod_meta_title		=mysqli_real_escape_string($db_con,trim($prod_meta_title));
				if($prod_meta_title=="")
				{
					$prod_meta_title =mysqli_real_escape_string($db_con, $prod_row['prod_meta_title']);
				}
				//=================================Meata Tags=====================================//					
				
				if($prod_meta_tags=="")
				{
					$prod_meta_tags   =mysqli_real_escape_string($db_con,$prod_row['prod_meta_tags']);
				}
				
			   if($flag==1)
				{
					$slug =getSlug($prod_name);
		           // echo json_encode(array("Success"=>"fail","resp"=>$slug));exit();
					$sql_update_product 	= "	UPDATE `tbl_products_master` SET `prod_name`='".$prod_name."',`prod_slug`='".$slug."',";
					$sql_update_product 	.= " prod_title='".$prod_title."',`prod_description`='".$prod_description."',`prod_orgid`='".$prod_orgid."',`prod_brandid`='".$prod_brandid."',";
					$sql_update_product 	.= " `prod_content`='".$prod_content."',";
					$sql_update_product 	.= " `prod_quantity`='".$prod_quantity."',`prod_min_quantity`='".$prod_min_quantity."',`prod_max_quantity`='".$prod_max_quantity."',`prod_list_price`='".$prod_list_price."',";
					$sql_update_product 	.= " `prod_recommended_price`='".$prod_recommended_price."',`prod_meta_tags`='".$prod_meta_tags."',`prod_google_product_category`='".$prod_google_product_category."',";
					$sql_update_product 	.= " `prod_meta_description`='".$prod_meta_description."',`prod_meta_title`='".$prod_meta_title."',";
					$sql_update_product 	.= " `prod_modified_by`='".$uid."',prod_status='".$prod_status."',`prod_modified`='".$datetime."' WHERE `prod_model_number`='".$prod_model_number."' ";
					
					//echo json_encode(array('Success'=>'fail','resp'=>$sql_update_product));exit();
					$result_update_product 	= mysqli_query($db_con,$sql_update_product) or die(mysqli_error($db_con));
						
						if($result_update_product)
						{
							//=============Update Status =======================//
							 // done by satish for add and remove category 
							 $sql_update_default_status 	= "	UPDATE `tbl_product_cats` ";
							 $sql_update_default_status 	.= " 	SET `prodcat_prod_status`='".$prod_status."' ";
							 $sql_update_default_status 	.= " WHERE `prodcat_prodid`='".$prod_id."'";
							 $res_update_default_status 	= mysqli_query($db_con,$sql_update_default_status) or die(mysqli_error($db_con));
							 
							 // Update Prod Status in prod-filter table
							 $sql_update_prod_filt	 = " UPDATE `tbl_product_filters` ";
							 $sql_update_prod_filt 	.= " 	SET `prodfilt_status`='".$prod_status."' ";
							 $sql_update_prod_filt 	.= " WHERE `prodfilt_prodid`='".$prod_id."' ";
							 $res_update_prod_filt	 = mysqli_query($db_con, $sql_update_prod_filt) or die(mysqli_error($db_con));
							 
							 // Update Prod Status in prod-specification table
							 $sql_update_prod_spec	= " UPDATE `tbl_products_specifications` ";
							 $sql_update_prod_spec 	.= " 	SET `prod_spec_status`='".$prod_status."' ";
							 $sql_update_prod_spec 	.= " WHERE `prod_spec_prodid`='".$prod_id."' ";
							 $res_update_prod_spec	= mysqli_query($db_con, $sql_update_prod_spec) or die(mysqli_error($db_con));
							
							//============Satrt : Add Category ==============================//
							if(count($products_catid) > 0)
							{
								foreach($products_catid as $p_catid)
								{
									$sql_checl_cat ="SELECT * FROM tbl_product_cats WHERE prodcat_prodid='".$prod_id."' AND prodcat_catid='".$p_catid."'";
									$res_checl_cat =mysqli_query($db_con,$sql_checl_cat) or die(mysqli_error($db_con));
									if(mysqli_num_rows($res_checl_cat)==0)
									{
										addCategory($prod_id,$p_catid,$prod_status,0);
									}
								}
							}
						
							//===========End : Add Category ========================//
							
							
							
							//===========Update Filter=======================//
							
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
							
							/* code to update product Filters*/	
								
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
				
							//===========Update Level ============================//
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
				
						}
				}
				else
				{
					$error_array = array($prod_model_number,$prod_name,$prod_title,$prod_description1,$prod_org,$prod_brand,$prod_catid_txt,$prod_google_product_category,$prod_content1,$prod_quantity,$prod_min_quantity,$prod_max_quantity,$prod_list_price,$prod_recommended_price,$prod_meta_description_1,$prod_meta_title,$prod_meta_tags,$prod_specification_list,$prod_filters_list,$prod_level,$update_error);
					array_push($error_arr,$error_array);
				}
				
				
				
				
			}//res check if end
			else
			{ 
			  if($prod_model_number !="")
			  {
				   $update_error .='Model Number Not Found';
			$error_array = array($prod_model_number,$prod_name,$prod_title,$prod_description1,$prod_org,$prod_brand,$prod_catid_txt,$prod_google_product_category,$prod_content1,$prod_quantity,$prod_min_quantity,$prod_max_quantity,$prod_list_price,$prod_recommended_price,$prod_meta_description_1,$prod_meta_title,$prod_meta_tags,$prod_specification_list,$prod_filters_list,$prod_level,$update_error);
				array_push($error_arr,$error_array);
			  }
			 
			}//res check else end

			
		}// for end  
		
		if(count($error_arr) > 0)
		{
		    $writer 			= new XLSXWriter();
			$writer->setAuthor('Prem Ambodkar');
			$writer->writeSheet($error_arr,'Sheet1',$header);
			$timestamp			= date('mdYhis', time());
			if(!file_exists("excelDownload/product".$timestamp))
			{
				mkdir("excelDownload/product".$timestamp);
			}
			$writer->writeToFile('excelDownload/product'.$timestamp.'/product_sheet_'.$timestamp.'.xlsx');
			
			$message ='Some records not updated <br> <a href="'.$BaseFolder.'excelDownload/product'.$timestamp.'/product_sheet_'.$timestamp.'.xlsx">Click Here</a> to see Problem\'s';
			
			$response_array	= array("Success"=>"fail","resp"=>$message, "file"=>'excelDownload/product'.$timestamp.'/product_sheet_'.$timestamp.'.xlsx');
				
		}
		else
		{
			$response_array = array("Success"=>"Success","resp"=>"Product Updated Succsessfully");			
		}
		//echo json_encode(array("Success"=>"fail",'resp'=>$inputFileName));exit();
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}
?>
