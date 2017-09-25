<?php
	include("include/routines.php");
	$json 	= file_get_contents('php://input');
	$obj 	= json_decode($json);
	$utype	= $_SESSION['panel_user']['utype'];
	$uid	= $_SESSION['panel_user']['id'];

	function newAddressId()
	{
		global $uid;
		global $db_con;
		$add_id				= 0;
		$sql_last_rec 		= "Select * from tbl_address_master order by add_id desc LIMIT 0,1";
		$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$add_id 		= 1;				
		}
		else
		{
			$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
			$add_id 		= $row_last_rec['add_id']+1;
		}
		return $add_id;
	}
	
	function insert_values($table ,$variables=array())
	{   
	    global $uid;
		global $db_con, $datetime;
		
		if( empty( $variables ) )
        {
            return false;
            exit;
        }
        
        $sql = "INSERT INTO ". $table;
        $fields = array();
        $values = array();
        foreach( $variables as $field => $value )
        {
            $fields[] = $field;
            $values[] = "'".$value."'";
        }
        $fields = ' (' . implode(', ', $fields) . ')';
        $values = '('. implode(', ', $values) .')';
        
        $sql .= $fields .' VALUES '. $values;
		$result  = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		$last_id = $db_con->insert_id;
		if($result)
		{
			return $last_id;
		}
		else
		{
			return false;
		}
	}
	
	function show_full_path($cat_id,$array)
	{
		global $db_con;
		$sql_get_cat = "SELECT * FROM tbl_coaching_category WHERE cat_id ='".$cat_id."' AND cat_name !='none' AND cat_status =1 ";
		$res_get_cat = mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
		$num_rows    = mysqli_num_rows($res_get_cat);
		if($num_rows !=0)
		{   $row =mysqli_fetch_array($res_get_cat);
			array_push($array,ucwords($row['cat_name']));
			if($row['cat_type']!="parent")
			{
			  $array = show_full_path($row['cat_type'],$array);
			}
		}
		return $array;
	}
	
	function insert_offering($class_id,$branch_id,$off_type,$offering,$fee,$seat,$status,$duration,$duration_type,$sessions,$ts_ratio)
	{ 
	
		global $db_con, $datetime,$uid;
		
		    $sql_insert_offering	 = "INSERT INTO `tbl_class_branchoffering`(`branchoff_classid`,`branchoff_branchid`, `branchoff_offid`,branchoff_type, `branchoff_seats`, `branchoff_fee`,branchoff_duration, ";
			$sql_insert_offering .=" branchoff_durationtype,branchoff_sessions , `branchoff_status`, `branchoff_created`, `branchoff_created_by`,ts_ratio)";
			$sql_insert_offering .=" VALUES ('".$class_id."', '".$branch_id."', '".$offering."','".$off_type."', '".$seat."','".$fee."','".$duration."','".$duration_type."','".$sessions."', '".$status."',";
			$sql_insert_offering .="   '".$datetime."', '".$uid."','".$ts_ratio."')";
			$res_insert_offering = mysqli_query($db_con, $sql_insert_offering) or die(mysqli_error($db_con));
			$last_id =$db_con->insert_id;
			
			if($res_insert_offering)
			{
			  return $last_id;	
			}
			else
			{
				return 0;
			}
	}

	function insert_offlevel($class_id,$branch_id,$off_id,$level)
	{ 
	
		    global $db_con, $datetime,$uid;
		
		    $sql_insert_level	 = "INSERT INTO `tbl_class_offlevel`(`offlevel_offid`,`offlevel_class_id`, `offlevel_branch_id`, `offlevel_level_id`)";
			$sql_insert_level .=" VALUES ('".$off_id."', '".$class_id."', '".$branch_id."', '".$level."')";
			$res_insert_level = mysqli_query($db_con, $sql_insert_level) or die(mysqli_error($db_con));
			
			if($res_insert_level)
			{
			  return true;	
			}
			else
			{
				return false;
			}
	}
	
	function insert_offfilter($class_id,$branch_id,$off_id,$filt_id)
	{ 
	
		    global $db_con, $datetime,$uid;
		
		    $sql_insert_filter	 = "INSERT INTO `tbl_class_offfilter`(`offfilt_offid`,`offfilt_classid`, `offfilt_barnchid`, `offfilt_fildid`)";
			$sql_insert_filter .=" VALUES ('".$off_id."', '".$class_id."', '".$branch_id."', '".$filt_id."')";
		//	echo json_encode($insert_filter);exit();
			$res_insert_filter = mysqli_query($db_con, $sql_insert_filter) or die(mysqli_error($db_con));
			
			if($res_insert_filter)
			{
			  return true;	
			}
			else
			{
				return false;
			}
	}
    
	function insert_offeringdays($offering_id,$class_id,$branch_id,$day,$from,$to)
	{
		 
	        global $db_con, $datetime,$uid;
		
		    $sql_insert_day	 = "INSERT INTO `tbl_offeringday`(day_offeringid,`day_classid`,`day_branchid`, `day`, `from_time`,  `to_time`,offeringday_created,offeringday_created_by)";
			$sql_insert_day .=" VALUES ('".$offering_id."','".$class_id."', '".$branch_id."', '".$day."', '".$from."','".$to."','".$datetime."','".$uid."')";
			$res_insert_day = mysqli_query($db_con, $sql_insert_day) or die(mysqli_error($db_con));
			
			if($res_insert_day)
			{
			 return 1;		
			}
			else
			{
				return 0;
			}
	
	}
    
	
	
	function insertClass($response_array,$class_name,$class_description,$class_primary_email,$class_secondary_email,$class_primary_phone,$class_secondary_phone,$class_website,$class_status,$class_bank_ifsc_code,$class_bank_account_number,$class_bank_address,$class_bank_name,$class_beneficiary_name,$class_owner,$classtype,$method,$logo,$fb_link,$tgline)
	{
		global $uid;
		global $db_con, $datetime;
		global $obj;
		
		/*$sql_chk_class	= "SELECT * FROM tbl_class WHERE class_name='".$class_name."'";
		$res_chk_class	= mysqli_query($db_con, $sql_chk_class) or die(mysqli_error($db_con));
		$num_chk_class	= mysqli_num_rows($res_chk_class);
		if(strcmp($num_chk_class,"0")===0)
		{*/
			$sql_last_class	= "SELECT * FROM tbl_class ORDER BY class_id DESC LIMIT 0,1";
			$res_last_class	= mysqli_query($db_con, $sql_last_class) or die(mysqli_error($db_con));
			$row_last_class	= mysqli_fetch_array($res_last_class);
			$num_last_class	= mysqli_num_rows($res_last_class);
			if($num_last_class==0)
			{
				$class_id	= 1;	
			}
			else
			{
				$class_id	= $row_last_class['class_id'] + 1;
			}
			
			$classtype = implode(',',$classtype);
			$method    = implode(',',$method);
			
			$sql_insert_class	 = "INSERT INTO `tbl_class`(`class_id`, `class_name`, `class_primary_email`, `class_secondary_email`,  `class_primary_phone`, ";
			$sql_insert_class .=" `class_secondary_phone`,class_logo, `class_website`, `class_description`,class_type,class_method, `class_status`, `class_created`, class_created_by,";
			$sql_insert_class .=" `class_bank_ifsc_code`,`class_bank_account_number`, `class_bank_address`,`class_bank_name`,`class_beneficiary_name`,class_owner,fb_link,tgline)";
			$sql_insert_class .=" VALUES ('".$class_id."', '".$class_name."', '".$class_primary_email."', '".$class_secondary_email."','".$class_primary_phone."', '".$class_secondary_phone."',";
			$sql_insert_class .="  '".$logo."','".$class_website."', '".$class_description."','".$classtype."','".$method."', '".$class_status."', '".$datetime."', '".$uid."','".$class_bank_ifsc_code."', ";
			$sql_insert_class .=" '".$class_bank_account_number."','".$class_bank_address."','".$class_bank_name."','".$class_beneficiary_name."','".$class_owner."','".$fb_link."','".$tgline."')";
			$res_insert_class = mysqli_query($db_con, $sql_insert_class) or die(mysqli_error($db_con));
			
			if($res_insert_class)
			{
			/*	if(!empty($class_catids))
				{  
					$sql_get_catid =" SELECT DISTINCT cat_id,cat_type FROM tbl_coaching_category WHERE cat_id IN (".implode(',',$class_catids).")";
					$res_get_catid = mysqli_query($db_con,$sql_get_catid) or die(mysqli_error($db_con));
					while($row_cat_id = mysqli_fetch_array($res_get_catid))
					{  
					    $parent = 0;
						if($row_cat_id['cat_type']=="parent")
						{
							$parent = 1;
						}
						$sql_insert  ="INSERT INTO `tbl_class_cats`(`cat_id`, `class_id`, `parent`, `status`) ";
						$sql_insert .=" VALUES ('".$row_cat_id['cat_id']."','".$class_id."','".$parent."','".$row_cat_id['cat_status']."')";
						$result = mysqli_query($db_con,$sql_insert) or die(mysqli_error($db_con));
					}
				}*/
				
				return $class_id;
			}
			else
			{
				return ;	
			}
		/*}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Organisation Name ".$org_name." is already Exist");
		}*/
		
		
	}
	
	

	function updateClass($response_array,$class_id,$class_name,$class_description,$class_primary_email,$class_secondary_email,$class_primary_phone,$class_secondary_phone,$class_website,$class_status,$class_bank_ifsc_code,$class_bank_account_number,$class_bank_address,$class_bank_name,$class_beneficiary_name,$class_owner,$logo,$classtype,$method,$fb_link,$tgline)
	{
		global $uid;
		global $db_con, $datetime;
		
		$classtype = mysqli_real_escape_string($db_con,implode(',',$classtype));
		$method    = mysqli_real_escape_string($db_con,implode(',',$method));
	//	$class_catids    = mysqli_real_escape_string($db_con,implode(',',$class_catids));
		
			

		$sql_update_class  = " UPDATE `tbl_class` SET `class_name`='".$class_name."',`class_primary_email`='".$class_primary_email."',";
		$sql_update_class .= "`class_secondary_email`='".$class_secondary_email."',class_description ='".$class_description."',`class_primary_phone`='".$class_primary_phone."',";
		$sql_update_class .= " `class_logo`='".$logo."',`class_secondary_phone`='".$class_secondary_phone."',`class_website`='".$class_website."',class_type ='".$classtype."',class_method ='".$method."',`class_modified`='".$datetime."',";
		$sql_update_class .= "`class_modified_by`='".$uid."',`class_bank_ifsc_code`= '".$class_bank_ifsc_code."',`class_bank_account_number`= '".$class_bank_account_number."',class_status='".$class_status."',";
		$sql_update_class .= "`class_bank_address`= '".$class_bank_address."',`class_bank_name`= '".$class_bank_name."',`class_beneficiary_name`= '".$class_beneficiary_name."',fb_link='".$fb_link."',tgline='".$tgline."'";
		$sql_update_class .= " WHERE `class_id`='".$class_id."' ";
						$res_update_class = mysqli_query($db_con, $sql_update_class) or die(mysqli_error($db_con));
							
						
							if($res_update_class)
							{
							/*if(!empty($class_catids))
							{
								
							 $sql_delete_cats ="DELETE FROM tbl_class_cats WHERE class_id ='".$class_id."' AND cat_id NOT IN (".implode(',',$class_catids).")";
							 mysqli_query($db_con,$sql_delete_cats) or die(mysqli_error($db_con));
							 
							    $sql_get_catid =" SELECT DISTINCT cat_id,cat_type FROM tbl_coaching_category WHERE cat_id IN (".implode(',',$class_catids).")";
							    $res_get_catid = mysqli_query($db_con,$sql_get_catid) or die(mysqli_error($db_con));
								while($row_cat_id = mysqli_fetch_array($res_get_catid))
								{  
									$parent = 0;
									if($row_cat_id['cat_type']=="parent")
									{
										$parent = 1;
									}
									$sql_check_cat =" SELECT * FROM tbl_class_cats WHERE cat_id ='".$row_cat_id['cat_id']."' AND  class_id='".$class_id."'";
									if(mysqli_num_rows(mysqli_query($sql_check_cat))==0)
									{
										$sql_insert  ="INSERT INTO `tbl_class_cats`(`cat_id`, `class_id`, `parent`, `status`) ";
										$sql_insert .=" VALUES ('".$row_cat_id['cat_id']."','".$class_id."','".$parent."','".$row_cat_id['cat_status']."')";
										$result = mysqli_query($db_con,$sql_insert) or die(mysqli_error($db_con));
									}
									
									$response_array = array("Success"=>"fail","resp"=>$sql_get_catid);	
			//echo json_encode($response_array);exit();
								}
							}*/
								
								
								$response_array = array("Success"=>"Success","resp"=>"Class Updated");
							}
							else
							{
								$response_array = array("Success"=>"fail","resp"=>"Class Not Updated");	
							}
		
		
		return $response_array;	
	}
	
	function  update_offering($offering_id,$off_type,$offering,$duration,$fee,$seat,$status,$duration_type,$sessions,$ts_ratio)
	{
		global $uid;
		global $db_con, $datetime;	
		
		$sql_update_offering = " UPDATE `tbl_class_branchoffering` 
									SET `branchoff_offid`='".$offering."',
										`branchoff_type`='".$off_type."',	
										`branchoff_seats`='".$seat."',
										`branchoff_fee`='".$fee."',
										`branchoff_duration`='".$duration."',
										`branchoff_durationtype`='".$duration_type."',
										`branchoff_sessions`='".$sessions."',
										`branchoff_status`='".$status."',
										`branchoff_modified`='".$datetime."',
										`branchoff_modified_by`='".$uid."',
										`ts_ratio`='".$ts_ratio."'																									
								  WHERE `branchoff_id`='".$offering_id."' ";
		$res_update_offering = mysqli_query($db_con, $sql_update_offering) or die(mysqli_error($db_con));
		
		if($res_update_offering)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function updateMainOrg($org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_desc, $class_id,$org_bank_ifsc_code,$org_bank_account_number,$org_bank_address,$org_bank_name,$org_beneficiary_name)
	{
		global $uid;
		global $db_con, $datetime;	
		
		$sql_main_update_org = " UPDATE `tbl_oraganisation_master` 
									SET `org_name`='".$org_name."',
										`org_primary_email`='".$org_primary_email."',	
										`org_secondary_email`='".$org_secondary_email."',
										`org_tertiary_email`='".$org_tertiary_email."',
										`org_primary_phone`='".$org_primary_phone."',
										`org_secondary_phone`='".$org_secondary_phone."',
										`org_fax`='".$org_fax."',
										`org_website`='".$org_website."',
										`org_indid`='".$org_indid."',
										`org_cst`='".$org_cst."',
										`org_vat`='".$org_vat."',
										`org_description`='".$org_desc."',
										`org_modified`='".$datetime."',
										`org_modified_by`='".$uid."',
										`org_bank_ifsc_code`= '".$org_bank_ifsc_code."',
										`org_bank_account_number`= '".$org_bank_account_number."',
										`org_bank_address`= '".$org_bank_address."',
										`org_bank_name`= '".$org_bank_name."',
										`org_beneficiary_name`= '".$org_beneficiary_name."'																									
								  WHERE `class_id`='".$class_id."' ";
		$res_main_update_org = mysqli_query($db_con, $sql_main_update_org) or die(mysqli_error($db_con));
		
		if($res_main_update_org)
		{
			return $class_id;
		}
		else
		{
			return $class_id = "";
		}
	}

	if(isset($_FILES['file']))
	{
		$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
		$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
		move_uploaded_file($sourcePath,$inputFileName) ;
		
		set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
		include 'PHPExcel/IOFactory.php';
		$class_id 	= 0;
		$msg		= '';
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
				$org_name 			= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"])),ENT_HTML5);
				$org_primary_email	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["B"]));
				$org_secondary_email= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["C"]));
				$org_tertiary_email	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["D"]));
				$org_primary_phone	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"]));
				$org_secondary_phone= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"]));
				$org_fax			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["G"]));
				$org_website		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["H"]));
				$org_industry		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["I"]));
				$org_cst			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["J"]));
				$org_vat			= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["K"]));
				$org_billaddrs1		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["L"]));
				$org_billaddrs      = htmlspecialchars(str_replace("'","&#146;",$org_billaddrs1),ENT_HTML5);
				$billstate_name		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["M"]));					
				$billcity_name		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["N"]));
				$org_billpincode	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["O"]));
				$org_shipaddrs1		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["P"]));
				$org_shipaddrs      = htmlspecialchars(str_replace("'","&#146;",$org_shipaddrs1),ENT_HTML5);
				$shipstate_name		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["Q"]));				
				$shipcity_name		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["R"]));
				$org_shippincode	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["S"]));
				$org_description1	= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["T"]));
				$org_description    = htmlspecialchars(str_replace("'","&#146;",$org_description1),ENT_HTML5);
				$org_status			= '1';
				
				if($org_name!='' && $org_primary_email!='' && $org_industry!='')
				{
					$query = " SELECT * FROM `tbl_oraganisation_master` 
								WHERE `org_name`='".$org_name."'
									AND `org_primary_email`='".$org_primary_email."'
									AND `org_primary_phone`='".$org_primary_phone."' " ;
									
					$sql 		= mysqli_query($db_con, $query) or die(mysqli_error($db_con));
					$recResult 	= mysqli_fetch_array($sql);
					
					// Get Industry ID
					$sql_get_ind_id		= " SELECT * FROM `tbl_industry` WHERE `ind_name`='".trim($org_industry)."' ";
					$res_get_ind_id		= mysqli_query($db_con, $sql_get_ind_id) or die(mysqli_error($db_con));
					$row_get_ind_id		= mysqli_fetch_array($res_get_ind_id);
					$num_get_ind_id		= mysqli_num_rows($res_get_ind_id);
					$org_indid			= $row_get_ind_id['ind_id'];
					
					// getting state and city
					// get State Code
					$sql_get_state_code1	= " SELECT `state_id`,`state` FROM `state` WHERE `state_name` = '".$billstate_name."' ";
					$res_get_state_code1	= mysqli_query($db_con, $sql_get_state_code1) or die(mysqli_error($db_con));
					$row_get_state_code1	= mysqli_fetch_array($res_get_state_code1);
					$add_state1				= $row_get_state_code1['state'];
					
					// get City ID
					$sql_get_city_id1		= " SELECT `city_id` FROM `city` WHERE `state_id`='".$add_state1."' AND `city_name` = '".$billcity_name."' ";
					$res_get_city_id1		= mysqli_query($db_con, $sql_get_city_id1) or die(mysqli_error($db_con));
					$row_get_city_id1		= mysqli_fetch_array($res_get_city_id1);
					$add_city1				= $row_get_city_id1['city_id'];
					
					// get State Code
					$sql_get_state_code2	= " SELECT `state_id`,`state` FROM `state` WHERE `state_name` = '".$shipstate_name."' ";
					$res_get_state_code2	= mysqli_query($db_con, $sql_get_state_code2) or die(mysqli_error($db_con));
					$row_get_state_code2	= mysqli_fetch_array($res_get_state_code2);
					$add_state2				= $row_get_state_code2['state'];
					
					// get City ID
					$sql_get_city_id2		= " SELECT `city_id` FROM `city` WHERE `state_id`='".$add_state2."' AND `city_name` = '".$shipcity_name."' ";
					$res_get_city_id2		= mysqli_query($db_con, $sql_get_city_id2) or die(mysqli_error($db_con));
					$row_get_city_id2		= mysqli_fetch_array($res_get_city_id2);
					$add_city2				= $row_get_city_id2['city_id'];
					
					$existPriEmail 		= $recResult["org_primary_email"];
					$existOrgName		= $recResult["org_name"];
					
					if($existPriEmail=="" && $existOrgName=="" && $num_get_ind_id!=0 && $org_primary_phone!='')
					{
						$response_array 	= insertClass($response_array, $org_name, $org_primary_email, $org_secondary_email, $org_tertiary_email, $org_primary_phone, $org_secondary_phone, $org_fax, $org_website, $org_indid, $org_cst, $org_vat, $org_billaddrs, $add_state1, $add_city1, $org_billpincode, $org_shipaddrs, $add_state2, $add_city2, $org_shippincode, $org_description, $org_status);
						
						if($response_array)
						{
							$insertion_flag		= 1;	
						}
						else
						{
							$insertion_flag		= 0;
						}
					}
					else
					{
						// error data array
						$error_data = array("org_name"=>$org_name, "org_primary_email"=>$org_primary_email, "org_secondary_email"=>$org_secondary_email, "org_tertiary_email"=>$org_tertiary_email, "org_primary_phone"=>$org_primary_phone, "org_secondary_phone"=>$org_secondary_phone, "org_fax"=>$org_fax, "org_website"=>$org_website, "org_indid"=>$org_industry, "org_cst"=>$org_cst, "org_vat"=>$org_vat, "org_billaddrs"=>$org_billaddrs, "billstate_name"=>$billstate_name, "billcity_name"=>$billcity_name, "org_billpincode"=>$org_billpincode, "org_shipaddrs"=>$org_shipaddrs, "shipstate_name"=>$shipstate_name, "shipcity_name"=>$shipcity_name, "org_shippincode"=>$org_shippincode, "org_description"=>$org_description, "org_status"=>"0");	
						
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
						
						$error_module_name	= "organisation";
						$error_file			= $inputFileName;
						$error_status		= '1';
						$error_data_json	= json_encode($error_data);
						
						$sql_insert_error_log	= " INSERT INTO `tbl_error_data`(`error_id`, `error_module_name`, `error_file`, `error_data`, `error_status`, `error_created`, `error_created_by`) 
													VALUES ('".$error_id."', '".$error_module_name."', '".$error_file."', '".$error_data_json."', '".$error_status."', '".$datetime."', '".$uid."') ";
						$res_insert_error_log 	= mysqli_query($db_con, $sql_insert_error_log) or die(mysqli_error($db_con));
						
						$insertion_flag	= 1;
					}
				}
				else
				{
					$insertion_flag = 0;	
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
			$response_array = array("Success"=>"fail","resp"=>"Try to upload Different File");
		}
		echo json_encode($response_array);
	}
	
	if((isset($obj->load_class)) != "" && isset($obj->load_class))
	{
		$start_offset   = 0;
	
		$page 			= $obj->page;	
		$per_page		= $obj->row_limit;
		$search_text	= $obj->search_text;
		
		$response_array = array();
		
		if($page != "" && $per_page != "")
		{
			$cur_page 		= $page;
			$page 	   	   	= $page - 1;
			$start_offset += $page * $per_page;
			$start 			= $page * $per_page;
			
			$sql_load_data	= " SELECT `class_id`,class_status,class_primary_phone, `class_name`,class_primary_email, `class_primary_email`, `class_secondary_email`, ";
			$sql_load_data	.= " `class_created_by`,class_created, (SELECT fullname FROM tbl_cadmin_users WHERE id=class_created_by) AS name_created_by, ";
			$sql_load_data	.= " (SELECT fullname FROM tbl_cadmin_users WHERE id = class_modified_by) AS name_modified_by, "; 
			$sql_load_data	.= " `class_modified`, `class_modified_by` "; 
			$sql_load_data	.= " FROM `tbl_class`";
			$sql_load_data	.= " WHERE 1=1 ";
			if(strcmp($utype,'1')!==0)
			{
				$sql_load_data  .= " AND class_created_by='".$uid."' ";
			}
			if($search_text != "")
			{
				$sql_load_data .= " AND (class_name like '%".$search_text."%' or class_primary_email like '%".$search_text."%' ";
				$sql_load_data .= " or class_primary_phone like '%".$search_text."%' )  ";	
			}
			
			$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
			$sql_load_data .=" ORDER BY class_id DESC LIMIT $start, $per_page ";
			$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
			
			if(strcmp($data_count,"0") !== 0)
			{
				$class_data = '';
				$class_data .= '<table id="tbl_org" class="table table-bordered dataTable" style="width:100%;text-align:center">';
				$class_data .= '<thead>';
				$class_data .= '<tr>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Sr No.</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Class ID</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Class Name</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Primary Email</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Primary Phone</th>';
				//$class_data .= '<th style="text-align:center;vertical-align:middle;">Details</th>';
				/*$class_data .= '<th style="text-align:center;vertical-align:middle;">Created By</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Modified</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Modified</th>';*/
						
				$dis = checkFunctionalityRight("view_coaching_class.php",3);
				if($dis)
				{
					$class_data .= '<th style="text-align:center">Status</th>';
				}
				$edit = checkFunctionalityRight("view_coaching_class.php",1);
				if($edit)
				{
					$class_data .= '<th style="text-align:center">Edit</th>';
					$class_data .= '<th style="text-align:center">Branch</th>';
				}
				$del = checkFunctionalityRight("view_coaching_class.php",2);
				if($del)
				{
					$class_data .= '<th style="text-align:center">';
					$class_data .= '<div style="text-align:center">';
					$class_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
				}
				$class_data .= '</tr>';
				$class_data .= '</thead>';
				$class_data .= '<tbody>';
				while($row_load_data = mysqli_fetch_array($result_load_data))
				{
					$class_data .= '<tr>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.++$start_offset.'</td>';
					$class_data .= '<td style="text-align:center">'.$row_load_data['class_id'].'</td>';
					$class_data .= '<td><div>';
				    $class_data .='<div><button class="btn-link txtoverflow" id="'.$row_load_data['class_id'].'" onclick="addMoreClass(this.id,\'view\');">'.ucwords($row_load_data['class_name']).'</button><i class="icon-chevron-down" id="'.$row_load_data['class_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['class_id'].'org_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i></div>';
					$class_data .=' <div style="display:none" id="'.$row_load_data['class_id'].'org_div" style="">
									<div><b>Created:</b>'.$row_load_data['class_created'].'</div>
									<div><b>Created By:</b>'.$row_load_data['name_created_by'].'</div>
									<div><b>Modified</b>'.$row_load_data['class_modified'].'</div>
									<div><b>Modified By:</b>'.$row_load_data['name_modified_by'].'</div>
									</div>';
					$class_data .= '</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['class_primary_email'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['class_primary_phone'].'</td>';
					//$class_data .='<td></td>';
					//$class_data .= '<td style="text-align:center">'.$row_load_data['class_created'].'</td>';
					/*$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['name_created_by'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['class_modified'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['name_modified_by'].'</td>';*/
																								
					$dis = checkFunctionalityRight("view_coaching_class.php",3);
					if($dis)			
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;">';
						if($row_load_data['class_status'] == 1)
						{
							$class_data .= '<input type="button" value="Active" id="'.$row_load_data['class_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
						}
						else
						{
							$class_data .= '<input type="button" value="Inactive" id="'.$row_load_data['class_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
						}
						$class_data .= '</td>';	
					}
					$edit = checkFunctionalityRight("view_coaching_class.php",1);				
					if($edit)
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;">';
						$class_data .= '<input type="button" value="Edit" id="'.$row_load_data['class_id'].'" class="btn-warning" onclick="addMoreClass(this.id,\'edit\');"></td>';
					    $class_data .= '<td style="text-align:center;vertical-align:middle;">';
						$class_data .= '<input type="button" value="Branch" id="'.$row_load_data['class_id'].'" class="btn-warning" onclick="branch(this.id);"></td>';
					
					}
					$del = checkFunctionalityRight("view_coaching_class.php",2);
					if($del)				
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;"><div class="controls" align="center">';
						$class_data .= '<input type="checkbox" value="'.$row_load_data['class_id'].'" id="batch'.$row_load_data['class_id'].'" name="batch'.$row_load_data['class_id'].'" class="css-checkbox batch">';
						$class_data .= '<label for="batch'.$row_load_data['class_id'].'" class="css-label"></label>';
						$class_data .= '</div></td>';
					}
					$class_data .= '</tr>';	
					$class_data .= '<script type="text/javascript"> ';
					//$class_data .=' $("'.$row_load_data['class_id'].'org_div").slideUp();';
					$class_data .= '</script>';	
				}
				$class_data .= '</tbody>';
				$class_data .= '</table>';
				$class_data .= $data_count;
				$response_array = array("Success"=>"Success","resp"=>$class_data);
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////----------------Start :  Branch Part---------------------------////////////////////////////////////////////////////////////////	

	if((isset($obj->load_branch)) != "" && isset($obj->load_branch))
	{
		$start_offset   = 0;
	
		$page 			= $obj->page;	
		$class_id 		= $obj->class_id;
		$per_page		= $obj->row_limit;
		$search_text	= $obj->search_text;
		
		$response_array = array();
		
		if($page != "" && $per_page != "")
		{
			$cur_page 		= $page;
			$page 	   	   	= $page - 1;
			$start_offset += $page * $per_page;
			$start 			= $page * $per_page;
			
			$sql_load_data	= " SELECT tcb.*,ta.*,tc.class_name,(SELECT fullname FROM tbl_cadmin_users WHERE id=	class_branch_created_by) AS name_created_by, ";
			$sql_load_data	.= " (SELECT fullname FROM tbl_cadmin_users WHERE id = class_branch_modified_by) AS name_modified_by "; 
		    $sql_load_data	.= " FROM `tbl_class_branch` as tcb INNER JOIN tbl_area as ta ON tcb.class_branch_areaid=ta.area_id";
			$sql_load_data .= " INNER JOIN tbl_class as tc ON tcb.class_branch_classid=tc.class_id ";
			$sql_load_data	.= " WHERE tcb.class_branch_classid='".$class_id."' ";
			if(strcmp($utype,'1')!==0)
			{ 
				$sql_load_data  .= " AND class_branch_created_by='".$uid."' ";
			}
			if($search_text != "")
			{
				$sql_load_data .= " AND (class_branch_phone like '%".$search_text."%' or class_branch_email like '%".$search_text."%' ";
				$sql_load_data .= " or class_branch_address like '%".$search_text."%' or area_name like '%".$search_text."%' or area_direction like '%".$search_text."%' )  ";	
			}
			
			$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
			$sql_load_data .=" ORDER BY class_branch_id DESC LIMIT $start, $per_page ";
			$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
			
			if(strcmp($data_count,"0") !== 0)
			{
				$class_data = '';
				$class_data .= '<table id="tbl_org" class="table table-bordered dataTable" style="width:100%;text-align:center">';
				$class_data .= '<thead>';
				$class_data .= '<tr>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Sr No.</th>';
				//$class_data .= '<th style="text-align:center;vertical-align:middle;">Class ID</th>';
				//$class_data .= '<th style="text-align:center;vertical-align:middle;">Class Name</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Branch Name</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Address</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Mobile No.</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Email</th>';
				/*$class_data .= '<th style="text-align:center;vertical-align:middle;">Created</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Created By</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Modified</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Modified By</th>';*/
						
				$dis = checkFunctionalityRight("view_coaching_class.php",3);
				if($dis)
				{
					$class_data .= '<th style="text-align:center">Status</th>';
				}
				$edit = checkFunctionalityRight("view_coaching_class.php",1);
				if($edit)
				{
					$class_data .= '<th style="text-align:center">Edit</th>';
					$class_data .= '<th style="text-align:center">Branch</th>';
				}
				$del = checkFunctionalityRight("view_coaching_class.php",2);
				if($del)
				{
					$class_data .= '<th style="text-align:center">';
					$class_data .= '<div style="text-align:center">';
					$class_data .= '<input type="button"  value="Delete" onclick="multipleBranchDelete();" class="btn-danger"/></div></th>';
				}
				$class_data .= '</tr>';
				$class_data .= '</thead>';
				$class_data .= '<tbody>';
				while($row_load_data = mysqli_fetch_array($result_load_data))
				{
					$class_data .= '<tr>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.++$start_offset.'</td>';
					//$class_data .= '<td style="text-align:center">'.$row_load_data['class_branch_classid'].'</td>';
					//$class_data .= '<td><div><button class="btn-link txtoverflow" id="'.$row_load_data['class_branch_id'].'" onclick="addMoreBranch(this.id,\'view\');">'.ucwords($row_load_data['area_name']).'-'.ucwords($row_load_data['area_direction']).'</button><br>';
					//$class_data .= '</td>';
				    $class_data .='<td><div><button class="btn-link txtoverflow" id="'.$row_load_data['class_branch_id'].'" onclick="addMoreBranch(this.id,\'view\');">'.ucwords($row_load_data['area_name']).'-'.ucwords($row_load_data['area_direction']).'</button><i class="icon-chevron-down" id="'.$row_load_data['class_branch_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['class_branch_id'].'branch_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i></div>';
					$class_data .=' <div style="display:none" id="'.$row_load_data['class_branch_id'].'branch_div" style="">
									<div><b>Created:</b>'.$row_load_data['class_branch_created'].'</div>
									<div><b>Created By:</b>'.$row_load_data['name_created_by'].'</div>
									<div><b>Modified</b>'.$row_load_data['class_branch_modified'].'</div>
									<div><b>Modified By:</b>'.$row_load_data['name_modified_by'].'</div>
									</div>';
					$class_data .= '</td>';
				
					//$class_data .= '<td style="text-align:center;vertical-align:middle;">'.ucwords($row_load_data['area_name']).' ( '.ucwords($row_load_data['area_direction']).' )</td>';
					
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.ucwords($row_load_data['class_branch_address']).'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['class_branch_phone'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['class_branch_email'].'</td>';
					
					/*$class_data .= '<td style="text-align:center">'.$row_load_data['class_branch_created'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['name_created_by'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['class_branch_modified'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['name_modified_by'].'</td>';*/
																								
					$dis = checkFunctionalityRight("view_coaching_class.php",3);
					if($dis)			
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;">';
						if($row_load_data['class_branch_status'] == 1)
						{
							$class_data .= '<input type="button" value="Active" id="'.$row_load_data['class_branch_id'].'" class="btn-success" onclick="changeBranchStatus(this.id,0);">';
						}
						else
						{
							$class_data .= '<input type="button" value="Inactive" id="'.$row_load_data['class_branch_id'].'" class="btn-danger" onclick="changeBranchStatus(this.id,1);">';
						}
						$class_data .= '</td>';	
					}
					$edit = checkFunctionalityRight("view_coaching_class.php",1);				
					if($edit)
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;">';
						$class_data .= '<input type="button" value="Edit" id="'.$row_load_data['class_branch_id'].'" class="btn-warning" onclick="addMoreBranch(this.id,\'edit\');"></td>';
					    $class_data .= '<td style="text-align:center;vertical-align:middle;">';
						$class_data .= '<input type="button" value="Offerings" id="'.$row_load_data['class_branch_id'].'" class="btn-success" onclick="Offering(this.id);"></td>';
					
					}
					$del = checkFunctionalityRight("view_coaching_class.php",2);
					if($del)				
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;"><div class="controls" align="center">';
						$class_data .= '<input type="checkbox" value="'.$row_load_data['class_branch_id'].'" id="branch'.$row_load_data['class_branch_id'].'" name="branch'.$row_load_data['class_branch_id'].'" class="css-checkbox branch">';
						$class_data .= '<label for="branch'.$row_load_data['class_branch_id'].'" class="css-label"></label>';
						$class_data .= '</div></td>';
					}
					$class_data .= '</tr>';		
					$class_name ='&nbsp;&nbsp;'.ucwords($row_load_data['class_name']).' ('.$class_id.')';
				}
				$class_data .= '</tbody>';
				$class_data .= '</table>';
				$class_data .= $data_count;
				
				$response_array = array("Success"=>"Success","resp"=>$class_data,'class_name'=>$class_name);
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"No Data Available",'class_name'=>ucwords($row_load_data['class_name']));	
			}	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified",'class_name'=>ucwords($row_load_data['class_name']));	
		}
		
		echo json_encode($response_array);
	}
	
	if((isset($obj->load_branch_part)) != "" && isset($obj->load_branch_part))
	{
	  $class_id 		= $obj->class_id;
	  $branch_id 		= $obj->branch_id;
	  $req_type 		= $obj->req_type;
	  if($req_type !="")
	  {
		$sql_get_class 	= " SELECT * FROM tbl_class WHERE class_id='".$class_id."'";
		$res_get_class 	= mysqli_query($db_con, $sql_get_class) or die(mysqli_error($db_con));
		$class_row 		=mysqli_fetch_array($res_get_class);
		  
		if($req_type =="view" || $req_type =="edit")
		{
			$sql_get_branch 	= " SELECT * FROM tbl_class_branch WHERE class_branch_id='".$branch_id."'";
			$res_get_branch 	= mysqli_query($db_con, $sql_get_branch) or die(mysqli_error($db_con));
			$branch_row 		=mysqli_fetch_array($res_get_branch);
		}
	  	if($req_type =="add")
	  	{
	  		$data = '';

		
		$data .='<input type="hidden" name="add_branch_req" value="1">';
        $data .='<input type="hidden" name="class_id" id="class_id" value="'.$class_id.'" >';
        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select Area<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select onchange="getcity(this.value);" style="width:30%" id ="area" name="area" data-rule-required="true" class ="select2-me" onChange="coupon(this.value);">';
		$data .= '<option value="">Select Area</option>';
		
		$sql_get_coupon ="SELECT * FROM tbl_area WHERE area_status ='1' ";
		$res_get_coupon	= mysqli_query($db_con,$sql_get_coupon) or die(mysqli_error($db_con));
		while($city_row     =mysqli_fetch_array($res_get_coupon))
		{
			$data .= '<option value="'.$city_row['area_id'].'" >'.ucwords($city_row['area_name']).' ( '.ucwords($city_row['area_direction']).' )</option>';
		}
		
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';

       
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Area Address<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="address" rows="4" name="address" class="input-xlarge" data-rule-required="true" >';
		$data .= '</textarea>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Mobile Number<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" minlength=10 maxlength="10" id="mob_no" onKeyPress="return numsonly(event);" placeholder="Enter Mobile Number" Name="mob_no" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Landline Number</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" minlength=10 maxlength="10" id="land_no" onKeyPress="return numsonly(event);" placeholder="Enter Landline Number" Name="land_no" class="input-xlarge"  />';
		$data .= '</div>';
		$data .= '</div>';
		
			
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Email<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="email" id="email"  placeholder="Enter Email" Name="email" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
        
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Branch Location</label>';
		$data .= '<div class="controls">';
		$data .= '<input  value="" type="text" id="branch_location"  placeholder="Lat ,Lan" Name="branch_location" class="input-xlarge"  />';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Category<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';      $data .= '<div class="controls">';
		$data .= '<select style=" width: 95%;margin-top:10px;" name="catid[]" onchange="getsubcat(\''.$req_type.'\')" multiple="multiple"  id="parent_catid" onChange="console.log($(this).children(":selected").length)" required placeholder="Type Category" class="input-block-level ">';
		$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND cat_type='parent'";
		$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
		while($row_cat = mysqli_fetch_array($res_get_cat))
		{
			if($req_type=="add")
			{
				    if($row_cat['cat_type']=="parent")
				   {
					   $cat_path=$row_cat['cat_name'];
				   }
				   else
				   {  
				      $demo =show_full_path($row_cat['cat_type'],$array=array());
                      $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
				   }
				   
					$data .='<option value="'.$row_cat['cat_id'].'">'.$cat_path.'</option>';
					
			}
			elseif($req_type=="view" || $req_type=="edit")
			{
				$cat_ids = explode(',',$row_class_data['class_catids']);
				$cat_array = array();
				$sql ="SELECT DISTINCT cat_id FROM tbl_class_cats WHERE class_id ='".$class_id."' AND parent = 1 AND  ";
				$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
				while($parent_ids      = mysqli_fetch_array($res))
				{
					array_push($cat_array,$parent_ids['cat_id']);
				}
				
			  	if($row_cat['cat_type']=="parent")
			   {
				   $cat_path=$row_cat['cat_name'];
			   }
			   else
			   {  
				  $demo =show_full_path($row_cat['cat_type'],$array=array());
				  $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
			   }
				
				$data .='<option value="'.$row_cat['cat_id'].'" ';
				if(in_array($row_cat['cat_id'],$cat_array))
				{
					$data .=' selected="selected" ';
				}
				$data .=' >'.ucwords($cat_path).'</option>';
				
			}
			
		}
		$data .='</select>';
		$data .='</div>';
		$data .='</div>';
		//$data .=implode(',',$parent_ids);
		//$data .=$sql;
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Category<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';      $data .= '<div class="controls">';
		$data .= '<select style=" width: 95%;margin-top:10px;" name="catid[]" multiple="multiple"  id="catid" onChange="console.log($(this).children(":selected").length)" placeholder="Type Category" class="input-block-level ">';
		if($req_type=="view" || $req_type=="edit")
		{
			$cat_array =array();
			$sql ="SELECT DISTINCT cat_id FROM tbl_class_cats WHERE class_id ='".$class_id."' AND parent != 1 ";
			$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			
			while($parent_ids      = mysqli_fetch_array($res))
			{
				array_push($cat_array,$parent_ids['cat_id']);
			}
			
			if(!empty($cat_array))
			{
				$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND cat_type !='parent'";
				$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
				while($row_cat = mysqli_fetch_array($res_get_cat))
				{
					   if($row_cat['cat_type']=="parent")
					   {
						   $cat_path=$row_cat['cat_name'];
					   }
					   else
					   {  
						  $demo =show_full_path($row_cat['cat_type'],$array=array());
						  $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
					   }
					
					$data .='<option value="'.$row_cat['cat_id'].'" ';
					if(in_array($row_cat['cat_id'],$cat_array))
					{
						$data .=' selected="selected" ';
					}
					$data .=' >'.ucwords($cat_path).'</option>';
				}
			}
			
			
		}
		$data .='</select>';
		$data .='</div>';
		$data .='</div>';
		
        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="radio" name="status" value="1"  data-rule-required="true">Active';
		$data .= '<input type="radio" style="margin:10px;" name="status" value="0"  data-rule-required="true">Inactive<br>';
		$data .= '</div>';
		$data .= '</div>';
  
		$data .= '<br><button class="btn-info" type="submit" onclick="">Add Branch</button>';
		$data .= '<script type="text/javascript">';
		$data .= '$("#area").select2();';
		$data .= '$("#catid").select2();';
		$data .= '$("#parent_catid").select2();';
	//	$data .= 'CKEDITOR.replace( "address",{height:"150", width:"100%"});';
		$data .= '</script>';
        $response_array = array("Success"=>"Success","resp"=>$data,"class_name"=>'&nbsp;&nbsp;'.ucwords($class_row['class_name']));				
	    echo json_encode($response_array);exit();

	  	}
		if($req_type =="edit")
	  	{
	  		$data = '';

		$data .= '<script type="text/javascript">
        $(document).ready(function() {
          $(".js-example-basic-single").select2();
        });
        </script>';
		$data .='<input type="hidden" name="update_branch_req" value="1">';
        $data .='<input type="hidden" name="branch_id" id="branch_id" value="'.$branch_row['class_branch_id'].'" >';
		$data .='<input type="hidden" name="class_id" id="class_id" value="'.$class_id.'" >';
        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select Area<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select  style="width:30%" id ="area1" name="area" data-rule-required="true" class = "select2-me">';
		$data .= '<option value="">Select Area</option>';
		
		$sql_get_area ="SELECT * FROM tbl_area WHERE area_status ='1' ";
		$res_get_area	= mysqli_query($db_con,$sql_get_area) or die(mysqli_error($db_con));
		while($area     =mysqli_fetch_array($res_get_area))
		{
			$data .= '<option value="'.$area['area_id'].'" ';
			if($area['area_id']==$branch_row['class_branch_areaid'])
			{
			 $data .= ' selected ';	
			}
			$data .=' >'.ucwords($area['area_name']).' ( '.ucwords($area['area_direction']).' )</option>';
		}
		
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';

       
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Area Address<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="edit_address" rows="4" name="address" class="input-xlarge" data-rule-required="true" >'.$branch_row['class_branch_address'];
		$data .= '</textarea>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Mobile Number<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" value="'.$branch_row['class_branch_phone'].'" minlength=10 maxlength="10" id="mob_no" onKeyPress="return numsonly(event);" placeholder="Enter Mobile Number" Name="mob_no" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Landline Number</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" value="'.$branch_row['branch_landno'].'" minlength=10 maxlength="10" id="land_no" onKeyPress="return numsonly(event);" placeholder="Enter Landline Number" Name="land_no" class="input-xlarge"  />';
		$data .= '</div>';
		$data .= '</div>';
			
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Email<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input value="'.$branch_row['class_branch_email'].'" type="email" id="email"  placeholder="Enter Email" Name="email" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
        
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Branch Location</label>';
		$data .= '<div class="controls">';
		$data .= '<input  value="'.$branch_row['branch_location'].'" type="text" id="branch_location"  placeholder="Lat ,Lan" Name="branch_location" class="input-xlarge"  />';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Category<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';      $data .= '<div class="controls">';
		$data .= '<select style=" width: 95%;margin-top:10px;" name="catid[]" onchange="getsubcat(\''.$req_type.'\')" multiple="multiple"  id="parent_catid" onChange="console.log($(this).children(":selected").length)" required placeholder="Type Category" class="input-block-level ">';
		$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND cat_type='parent'";
		$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
		while($row_cat = mysqli_fetch_array($res_get_cat))
		{
			if($req_type=="view" || $req_type=="edit")
			{
				$cat_ids = explode(',',$row_class_data['class_catids']);
				$cat_array = array();
				$sql ="SELECT DISTINCT cat_id FROM tbl_class_cats WHERE class_id ='".$class_id."' AND parent = 1 AND branch_id ='".$branch_row['class_branch_id']."'  ";
				$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
				while($parent_ids      = mysqli_fetch_array($res))
				{
					array_push($cat_array,$parent_ids['cat_id']);
				}
				
			  	if($row_cat['cat_type']=="parent")
			   {
				   $cat_path=$row_cat['cat_name'];
			   }
			   else
			   {  
				  $demo =show_full_path($row_cat['cat_type'],$array=array());
				  $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
			   }
				
				$data .='<option value="'.$row_cat['cat_id'].'" ';
				if(in_array($row_cat['cat_id'],$cat_array))
				{
					$data .=' selected="selected" ';
				}
				$data .=' >'.ucwords($cat_path).'</option>';
				
			}
			
		}
		$data .='</select>';
		$data .='</div>';
		$data .='</div>';
		//$data .=implode(',',$parent_ids);
		//$data .=$sql;
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Sub Category</label>';      $data .= '<div class="controls">';
		$data .= '<select style=" width: 95%;margin-top:10px;" name="catid[]" multiple="multiple"  id="catid" onChange="console.log($(this).children(":selected").length)" placeholder="Type Category" class="input-block-level ">';
		if($req_type=="view" || $req_type=="edit")
		{
			$cat_array =array();
			$sql ="SELECT DISTINCT cat_id FROM tbl_class_cats WHERE class_id ='".$class_id."' AND parent != 1 AND branch_id ='".$branch_row['class_branch_id']."'";
			$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			
			while($parent_ids      = mysqli_fetch_array($res))
			{
				array_push($cat_array,$parent_ids['cat_id']);
			}
			
			if(!empty($cat_array))
			{
				$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND cat_type !='parent'";
				$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
				while($row_cat = mysqli_fetch_array($res_get_cat))
				{
					   if($row_cat['cat_type']=="parent")
					   {
						   $cat_path=$row_cat['cat_name'];
					   }
					   else
					   {  
						  $demo =show_full_path($row_cat['cat_type'],$array=array());
						  $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
					   }
					
					$data .='<option value="'.$row_cat['cat_id'].'" ';
					if(in_array($row_cat['cat_id'],$cat_array))
					{
						$data .=' selected="selected" ';
					}
					$data .=' >'.ucwords($cat_path).'</option>';
				}
			}
			
			
		}
		$data .='</select>';
		$data .='</div>';
		$data .='</div>';
		
        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="radio" name="status" value="1" ';
		 if($branch_row['class_branch_status']==1)
		 {
			 $data .=' checked ';
		 }
		$data .= ' data-rule-required="true">Active';
		$data .= '<input type="radio" style="margin:10px;" name="status" value="0" ';
		if($branch_row['class_branch_status']==0)
		 {
			 $data .=' checked ';
		 }
		$data .=' data-rule-required="true">Inactive<br>';
		$data .= '</div>';
		$data .= '</div>';
  		$data .= '<script type="text/javascript">';
		$data .= '$("#catid").select2();';
		$data .= '$("#parent_catid").select2();';
		$data .= '$("#area1").select2();';
		
		//$data .= 'CKEDITOR.replace("edit_address",{height:"150", width:"100%"});';
        $data .='</script>';
		
		$data .= '<br><button class="btn-info" type="submit" onclick="">Update Branch</button>';
        $response_array = array("Success"=>"Success","resp"=>$data,"class_name"=>ucwords($class_row['class_name']));				
	    echo json_encode($response_array);exit();
        }
		
		
		if($req_type =="view")
	  	{
	  		$data = '';
        
		$data .= '<script type="text/javascript">
        $(document).ready(function() {
          $(".js-example-basic-single").select2();
        });
        </script>';
		$data .='<input type="hidden" name="add_branch_req" value="1">';
        $data .='<input type="hidden" name="class_id" id="class_id" value="'.$branch_row['class_branch_classid'].'" >';
        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Branch Name</label>';
		$data .= '<div class="controls">';
		$data .= '<select disabled="disabled"  style="width:30%" id ="view_area" name="area" data-rule-required="true" class = "js-example-basic-single" >';
		$data .= '<option value="">Select State</option>';
		
		$sql_get_area ="SELECT * FROM tbl_area WHERE area_status ='1' ";
		$res_get_area	= mysqli_query($db_con,$sql_get_area) or die(mysqli_error($db_con));
		while($area     =mysqli_fetch_array($res_get_area))
		{
			$data .= '<option value="'.$area['area_id'].'" ';
			if($area['area_id']==$branch_row['class_branch_areaid'])
			{
			 $data .= ' selected ';	
			}
			$data .=' >'.ucwords($area['area_name']).' ( '.ucwords($area['area_direction']).' ) </option>';
		}
		
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Area Address</label>';
		$data .= '<div class="controls">';
		$data .= '<textarea readonly="readonly" id="view_address" rows="4" name="address" class="input-xlarge" data-rule-required="true" >'.$branch_row['class_branch_address'];
		$data .= '</textarea>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Mobile Number</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly="readonly" type="text" value="'.$branch_row['class_branch_phone'].'" minlength=10 maxlength="10" id="mob_no" onKeyPress="return numsonly(event);" placeholder="Enter Mobile Number" Name="mob_no" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Landline Number</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" disabled="disabled" value="'.$branch_row['branch_landno'].'" minlength=10 maxlength="10" id="land_no" onKeyPress="return numsonly(event);" placeholder="Enter Landline Number" Name="land_no" class="input-xlarge"  />';
		$data .= '</div>';
		$data .= '</div>';
			
			
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Email</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly="readonly" value="'.$branch_row['class_branch_email'].'" type="email" id="email"  placeholder="Enter Email" Name="email" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Branch Location</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly="readonly" value="'.$branch_row['branch_location'].'" type="text" id="branch_location"  placeholder="Enter Email" Name="branch_location" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';
        
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Category</label>';      $data .= '<div class="controls">';
		$data .= '<select disabled="disabled" style=" width: 95%;margin-top:10px;" name="catid[]" onchange="getsubcat(\''.$req_type.'\')" multiple="multiple"  id="parent_catid" onChange="console.log($(this).children(":selected").length)" required placeholder="Type Category" class="input-block-level ">';
		$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND cat_type='parent'";
		$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
		while($row_cat = mysqli_fetch_array($res_get_cat))
		{
			if($req_type=="view" || $req_type=="edit")
			{
				$cat_ids = explode(',',$row_class_data['class_catids']);
				$cat_array = array();
				$sql ="SELECT DISTINCT cat_id FROM tbl_class_cats WHERE class_id ='".$class_id."' AND parent = 1 AND branch_id ='".$branch_row['class_branch_id']."'  ";
				$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
				while($parent_ids      = mysqli_fetch_array($res))
				{
					array_push($cat_array,$parent_ids['cat_id']);
				}
				
			  	if($row_cat['cat_type']=="parent")
			   {
				   $cat_path=$row_cat['cat_name'];
			   }
			   else
			   {  
				  $demo =show_full_path($row_cat['cat_type'],$array=array());
				  $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
			   }
				
				$data .='<option value="'.$row_cat['cat_id'].'" ';
				if(in_array($row_cat['cat_id'],$cat_array))
				{
					$data .=' selected="selected" ';
				}
				$data .=' >'.ucwords($cat_path).'</option>';
				
			}
			
		}
		$data .='</select>';
		$data .='</div>';
		$data .='</div>';
		//$data .=implode(',',$parent_ids);
		//$data .=$sql;
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Sub Category</label>';      $data .= '<div class="controls">';
		$data .= '<select  disabled="disabled" style=" width: 95%;margin-top:10px;" name="catid[]" multiple="multiple"  id="catid" onChange="console.log($(this).children(":selected").length)" placeholder="Type Category" class="input-block-level ">';
		if($req_type=="view" || $req_type=="edit")
		{
			$cat_array =array();
			$sql ="SELECT DISTINCT cat_id FROM tbl_class_cats WHERE class_id ='".$class_id."' AND parent != 1 AND branch_id ='".$branch_row['class_branch_id']."'";
			$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
			
			while($parent_ids      = mysqli_fetch_array($res))
			{
				array_push($cat_array,$parent_ids['cat_id']);
			}
			
			if(!empty($cat_array))
			{
				$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND cat_type !='parent'";
				$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
				while($row_cat = mysqli_fetch_array($res_get_cat))
				{
					   if($row_cat['cat_type']=="parent")
					   {
						   $cat_path=$row_cat['cat_name'];
					   }
					   else
					   {  
						  $demo =show_full_path($row_cat['cat_type'],$array=array());
						  $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
					   }
					
					$data .='<option value="'.$row_cat['cat_id'].'" ';
					if(in_array($row_cat['cat_id'],$cat_array))
					{
						$data .=' selected="selected" ';
					}
					$data .=' >'.ucwords($cat_path).'</option>';
				}
			}
			
			
		}
		$data .='</select>';
		$data .='</div>';
		$data .='</div>';
		
        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status</label>';
		$data .= '<div class="controls">';
		
		 if($branch_row['class_branch_status']==1)
		 {
			 $data .=' Active ';
		 }
		
		
		if($branch_row['class_branch_status']==0)
		 {
			 $data .=' Inactive ';
		 }
		
		$data .= '</div>';
		$data .= '</div>';
 		 $data .= '<script type="text/javascript">';
		$data .= '$("#view_area").select2();';
		$data .= '$("#catid").select2();';
		$data .= '$("#parent_catid").select2();';
		//$data .= 'CKEDITOR.replace("view_address",{height:"150", width:"100%"})';
        $data .='</script>';
		
        $response_array = array("Success"=>"Success","resp"=>$data,"class_name"=>ucwords($class_row['class_name']));				
	    echo json_encode($response_array);exit();
        }
	  }
	  else
	  {
		  
	  }
	  echo json_encode($response_array);
	}
	
	if((isset($_POST['add_branch_req'])) == "1" && isset($_POST['add_branch_req']))
	{
		$class_id	 = $_POST['class_id'];
		$area		 = $_POST['area'];
		$address	 =$_POST['address'];
		$mob_no		 = $_POST['mob_no'];
		$email 		 = $_POST['email'];
		$status      =$_POST['status'];
		$land_no      =$_POST['land_no'];
		$class_catids =$_POST['catid'];
		$response_array =array();
			
		
		    $sql_insert_branch	 = "INSERT INTO `tbl_class_branch`(`class_branch_classid`, `class_branch_areaid`, `class_branch_phone`, `class_branch_email`,";
			$sql_insert_branch	.= " branch_landno ,`class_branch_address`,class_branch_status,class_branch_created,class_branch_created_by ) ";
			$sql_insert_branch .=" VALUES ('".$class_id."', '".$area."', '".$mob_no."', '".$email."','".$land_no."','".$address."', '".$status."',";
			$sql_insert_branch .="  '".$datetime."', '".$uid."')";
			$res_insert_branch = mysqli_query($db_con, $sql_insert_branch) or die(mysqli_error($db_con));
			if($res_insert_branch)
			{
				$branch_id =mysqli_insert_id($db_con);
				if(!empty($class_catids))
				{  
					$sql_get_catid =" SELECT DISTINCT cat_id,cat_type FROM tbl_coaching_category WHERE cat_id IN (".implode(',',$class_catids).")";
					$res_get_catid = mysqli_query($db_con,$sql_get_catid) or die(mysqli_error($db_con));
					while($row_cat_id = mysqli_fetch_array($res_get_catid))
					{  
					    $parent = 0; 
					
						if($row_cat_id['cat_type']=="parent")
						{
							$parent = 1;
						}
						$sql_insert  ="INSERT INTO `tbl_class_cats`(`cat_id`, `class_id`,branch_id, `parent`, `status`) ";
						$sql_insert .=" VALUES ('".$row_cat_id['cat_id']."','".$class_id."','".$branch_id."','".$parent."','".$row_cat_id['cat_status']."')";
						$result = mysqli_query($db_con,$sql_insert) or die(mysqli_error($db_con));
					}
				}
				$response_array = array("Success"=>"Success","resp"=>"Branch Added Successfully","class_id"=>$class_id);
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Branch Not Added");
			}
			echo json_encode($response_array);
	}

	if((isset($_POST['update_branch_req'])) == "1" && isset($_POST['update_branch_req']))
	{
		$branch_id	 = $_POST['branch_id'];
		$area		 = $_POST['area'];
		$address	 =$_POST['address'];
		$mob_no		 = $_POST['mob_no'];
		$email 		 = $_POST['email'];
		$status      = $_POST['status'];
		$class_id    =$_POST['class_id'];
		$land_no      =$_POST['land_no'];
		$class_catids =$_POST['catid'];
		$response_array =array();
		    
			 $sql_update_branch  = " UPDATE  tbl_class_branch SET class_branch_areaid='".$area."',class_branch_phone='".$mob_no."',class_branch_email='".$email."'";
		     $sql_update_branch .= " ,class_branch_address='".$address."',branch_landno ='".$land_no."',class_branch_modified='".$datetime."' ,class_branch_status='".$status."' ,";
			 $sql_update_branch .=" class_branch_modified_by='".$uid."'  WHERE class_branch_id='".$branch_id."' ";
			 $res_update_branch = mysqli_query($db_con, $sql_update_branch) or die(mysqli_error($db_con));
			if($res_update_branch)
			{
				if(!empty($class_catids))
				{
								
			 $sql_delete_cats ="DELETE FROM tbl_class_cats WHERE class_id ='".$class_id."' AND branch_id='".$branch_id."' AND cat_id NOT IN (".implode(',',$class_catids).")";
			 mysqli_query($db_con,$sql_delete_cats) or die(mysqli_error($db_con));
			 
				$sql_get_catid =" SELECT DISTINCT cat_id,cat_type FROM tbl_coaching_category WHERE cat_id IN (".implode(',',$class_catids).")";
				$res_get_catid = mysqli_query($db_con,$sql_get_catid) or die(mysqli_error($db_con));
				while($row_cat_id = mysqli_fetch_array($res_get_catid))
				{  
					$parent = 0;
					if($row_cat_id['cat_type']=="parent")
					{
						$parent = 1;
					}
	$sql_check_cat =" SELECT * FROM tbl_class_cats WHERE cat_id ='".$row_cat_id['cat_id']."' AND branch_id='".$branch_id."' AND  class_id='".$class_id."'";
	
					if(mysqli_num_rows(mysqli_query($db_con,$sql_check_cat))==0)
					{
						$sql_insert  ="INSERT INTO `tbl_class_cats`(`cat_id`, `class_id`,branch_id, `parent`, `status`) ";
						$sql_insert .=" VALUES ('".$row_cat_id['cat_id']."','".$class_id."','".$branch_id."','".$parent."','".$row_cat_id['cat_status']."')";
						$result = mysqli_query($db_con,$sql_insert) or die(mysqli_error($db_con));
					}
					
				}
				}
				$response_array = array("Success"=>"Success","resp"=>"Branch Updated Successfully","class_id"=>$class_id);
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Branch Not Updated");
			}
			echo json_encode($response_array);
	}
	
	if((isset($obj->change_branch_status)) == "1" && isset($obj->change_branch_status))
	{
		$branch_id					= mysqli_real_escape_string($db_con,$obj->branch_id);
		$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
		$response_array 		= array();
	
		$sql_update_branch_status	= "UPDATE `tbl_class_branch` 
														SET `class_branch_status`='".$curr_status."',
															`class_branch_modified`='".$datetime."',
															`class_branch_modified_by`='".$uid."' 
													WHERE `class_branch_id`='".$branch_id."'";
					$res_update_branch_status	= mysqli_query($db_con, $sql_update_branch_status) or die(mysqli_error($db_con));
					if($res_update_branch_status)
					{
						$sql_update_off_status	= "UPDATE `tbl_class_branchoffering` 
														SET `branchoff_status`='".$curr_status."',
															`branchoff_modified`='".$datetime."',
															`branchoff_modified_by`='".$uid."' 
													WHERE `branchoff_branchid`='".$branch_id."'";
					  $res_update_off_status	= mysqli_query($db_con, $sql_update_off_status) or die(mysqli_error($db_con));
						$flag_class	= 1;	
					}
					else
					{
						$flag_class	= 0;	
					}
		
		if($flag_class == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
		}
		
		echo json_encode($response_array);	
	}
	
	if((isset($obj->delete_branch)) == "1" && isset($obj->delete_branch))
	{
		$ar_branch_id 		= $obj->branch;
		$response_array = array();
		
		$del_flag =0;
		foreach($ar_branch_id as $branch_id)
		{
			$sql_delete_branch	= " DELETE FROM `tbl_class_branch` WHERE `class_branch_id`='".$branch_id."' ";
		    $res_delete_branch	= mysqli_query($db_con, $sql_delete_branch) or die(mysqli_error($db_con));
			if($sql_delete_branch)
			{   
			  
				// for delete branches offering 
				$sql_delete_offering	= " DELETE FROM `tbl_class_branchoffering` WHERE `branchoff_branchid`='".$branch_id."' ";
				$res_delete_offering	= mysqli_query($db_con, $sql_delete_offering) or die(mysqli_error($db_con));
				
				// for delete branches offering day 
				$sql_delete_offering	= " DELETE FROM `tbl_offeringday` WHERE `day_branchid`='".$branch_id."' ";
				$res_delete_offering	= mysqli_query($db_con, $sql_delete_offering) or die(mysqli_error($db_con));
				
				// for delete offering filter 
				$sql_delete_offfilt	= " DELETE FROM `tbl_class_offfilter` WHERE `offfilt_barnchid`='".$branch_id."' ";
				$res_delete_offfilt	= mysqli_query($db_con, $sql_delete_offfilt) or die(mysqli_error($db_con));
				
				//// for delete levels 
				$sql_delete_offlevel	= " DELETE FROM `tbl_class_offlevel` WHERE `offlevel_branch_id`='".$branch_id."' ";
				$res_delete_offlevel	= mysqli_query($db_con, $sql_delete_offlevel) or die(mysqli_error($db_con));
				
				$del_flag =1;
			}
		}	
		
		if($del_flag == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Class Deleted Successfully.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Deletion Failed.");
		}
		
		echo json_encode($response_array);	
	}
	
	
/////////////////----------------End :  Branch Part---------------------------////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////----------------Start :  offeringPart---------------------------////////////////////////////////////////////////////////////////	
	if((isset($obj->get_offering_days)) != "" && isset($obj->get_offering_days))
	{
  
           $offering_id 		= $obj->offering_id;
	       $sql_get_days ="SELECT * FROM tbl_offeringday WHERE day_offeringid='".$offering_id."'";
           $res_get_days	= mysqli_query($db_con, $sql_get_days) or die(mysqli_error($db_con));
           echo json_encode($res_get_days);
	}

	if((isset($obj->load_offering)) != "" && isset($obj->load_offering))
	{
		$start_offset   = 0;
	
		$page 			= $obj->page;	
		$class_id 		= $obj->class_id;
		$per_page		= $obj->row_limit;
		$search_text	= $obj->search_text;
		$branch_id	= $obj->branch_id;
		$response_array = array();
		
		if($page != "" && $per_page != "")
		{
			$cur_page 		= $page;
			$page 	   	   	= $page - 1;
			$start_offset += $page * $per_page;
			$start 			= $page * $per_page;
			
			$sql_load_data	= " SELECT tf.*,tcbo.*,tcb.*,tc.class_name,ta.*,(SELECT fullname FROM tbl_cadmin_users WHERE id=branchoff_created_by) AS name_created_by, ";
			$sql_load_data	.= " (SELECT fullname FROM tbl_cadmin_users WHERE id = branchoff_modified_by) AS name_modified_by "; 
		    $sql_load_data	.= " FROM `tbl_class_branchoffering` as tcbo INNER JOIN tbl_class_branch as tcb ON tcbo.branchoff_branchid=tcb.class_branch_id";
			$sql_load_data .= " INNER JOIN tbl_class as tc ON tcbo.branchoff_classid=tc.class_id ";
			$sql_load_data .= " INNER JOIN tbl_offering as tf ON tcbo.branchoff_offid=tf.offering_id ";
			$sql_load_data .= " INNER JOIN tbl_area as ta ON tcb.class_branch_areaid=ta.area_id ";
			$sql_load_data	.= " WHERE branchoff_branchid='".$branch_id."' ";
			if(strcmp($utype,'1')!==0)
			{
				$sql_load_data  .= " AND class_branch_created_by='".$uid."' ";
			}
			if($search_text != "")
			{
				$sql_load_data .= " AND (branchoff_type like '%".$search_text."%' or offering_name like '%".$search_text."%' ";
				$sql_load_data .= " or branchoff_fee like '%".$search_text."%' )  ";	
			}
			
			$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
			$sql_load_data .=" ORDER BY branchoff_id DESC LIMIT $start, $per_page ";
			$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
			
			if(strcmp($data_count,"0") !== 0)
			{
				$class_data = '';
				$class_data .= '<table id="tbl_org" class="table table-bordered dataTable" style="width:100%;text-align:center">';
				$class_data .= '<thead>';
				$class_data .= '<tr>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Sr No.</th>';
				//$class_data .= '<th style="text-align:center;vertical-align:middle;">Branch Id</th>';
				//$class_data .= '<th style="text-align:center;vertical-align:middle;">Branch</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Offering</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Offering Type</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Fee</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Duration</th>';
				/*$class_data .= '<th style="text-align:center;vertical-align:middle;">Created</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Created By</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Modified</th>';
				$class_data .= '<th style="text-align:center;vertical-align:middle;">Modified By</th>';*/
				$dis = checkFunctionalityRight("view_coaching_class.php",3);
				if($dis)
				{
					$class_data .= '<th style="text-align:center">Status</th>';
				}
				$edit = checkFunctionalityRight("view_coaching_class.php",1);
				if($edit)
				{
					$class_data .= '<th style="text-align:center">Edit</th>';
				}
				$del = checkFunctionalityRight("view_coaching_class.php",2);
				if($del)
				{
					$class_data .= '<th style="text-align:center">';
					$class_data .= '<div style="text-align:center">';
					$class_data .= '<input type="button"  value="Delete" onclick="multipleOfferingDelete('.$branch_id.');" class="btn-danger"/></div></th>';
				}
				$class_data .= '</tr>';
				$class_data .= '</thead>';
				$class_data .= '<tbody>';
				while($row_load_data = mysqli_fetch_array($result_load_data))
				{
					$class_data .= '<tr>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.++$start_offset.'</td>';
					//$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['class_branch_id'].'</td>';
					//$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['class_branch_address'].'</td>';
					/*$class_data .= '<td><div><button class="btn-link txtoverflow" id="'.$row_load_data['branchoff_id'].'" onclick="addMoreOffering(this.id,\'view\');">'.ucwords($row_load_data['offering_name']).'</button><br>';
					$class_data .= '</td>';*/
					
					 $class_data .='<td><div><button class="btn-link txtoverflow" id="'.$row_load_data['branchoff_id'].'" onclick="addMoreOffering(this.id,\'view\');">'.ucwords($row_load_data['offering_name']).'</button>
					 <i class="icon-chevron-down" id="'.$row_load_data['branchoff_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['branchoff_id'].'off_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i></div>';
					$class_data .=' <div style="display:none" id="'.$row_load_data['branchoff_id'].'off_div" style="">
									<div><b>Created:</b>'.$row_load_data['branchoff_created'].'</div>
									<div><b>Created By:</b>'.$row_load_data['name_created_by'].'</div>
									<div><b>Modified</b>'.$row_load_data['branchoff_modified'].'</div>
									<div><b>Modified By:</b>'.$row_load_data['name_modified_by'].'</div>
									</div>';
					$class_data .= '</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['branchoff_type'].'</td>';
					$class_data .= '<td style="text-align:center">'.$row_load_data['branchoff_fee'].' Rs.</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['branchoff_duration'].' '.$row_load_data['branchoff_durationtype'].'</td>';
					/*$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['branchoff_created'].'</td>';
					$class_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['branchoff_modified'].'</td>';
					$class_data .= '<td style="text-align:center;vertical-align:middle;">'.$row_load_data['name_modified_by'].'</td>';*/
					$edit = checkFunctionalityRight("view_coaching_class.php",1);
					if($edit)			
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;">';
						if($row_load_data['branchoff_status'] == 1)
						{
							$class_data .= '<input type="button" value="Active" id="'.$row_load_data['branchoff_id'].'" class="btn-success" onclick="changeOfferingStatus(this.id,0);">';
						}
						else
						{
							$class_data .= '<input type="button" value="Inactive" id="'.$row_load_data['branchoff_id'].'" class="btn-danger" onclick="changeOfferingStatus(this.id,1);">';
						}
						$class_data .= '</td>';	
					}
					$edit = checkFunctionalityRight("view_coaching_class.php",1);				
					if($edit)
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;">';
						$class_data .= '<input type="button" value="Edit" id="'.$row_load_data['branchoff_id'].'" class="btn-warning" onclick="addMoreOffering(this.id,\'edit\');"></td>';
					   
					}
					$del = checkFunctionalityRight("view_coaching_class.php",2);
					if($del)				
					{
						$class_data .= '<td style="text-align:center;vertical-align:middle;"><div class="controls" align="center">';
						$class_data .= '<input type="checkbox" value="'.$row_load_data['branchoff_id'].'" id="offering'.$row_load_data['branchoff_id'].'" name="branch'.$row_load_data['branchoff_id'].'" class="css-checkbox offering">';
						$class_data .= '<label for="offering'.$row_load_data['branchoff_id'].'" class="css-label"></label>';
						$class_data .= '</div></td>';
					}
					$branch_name =ucwords($row_load_data['class_name']).' , '.ucwords($row_load_data['area_name']).' ( '.ucwords($row_load_data['area_direction']).' )  ,'.$row_load_data['class_branch_address'];
					$branch_name .= '( '.$row_load_data['class_branch_id'].' )';
					$class_data .= '</tr>';		
				}
				$class_data .= '</tbody>';
				$class_data .= '</table>';
				$class_data .= $data_count;
				$response_array = array("Success"=>"Success","resp"=>$class_data,"branch_name"=>$branch_name);
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
	
	
	if((isset($obj->load_offering_part)) != "" && isset($obj->load_offering_part))
	{
	  $branch_id 		= $obj->branch_id;
	  $offering_id 		= $obj->offering_id;
	  $req_type 		= $obj->req_type;
	  if($req_type !="")
	  {
		if($req_type =="view" || $req_type =="edit")
		{
			$sql_get_branch 	= " SELECT * FROM tbl_class_branchoffering WHERE branchoff_id='".$offering_id."'";
			$res_get_branch 	= mysqli_query($db_con, $sql_get_branch) or die(mysqli_error($db_con));
			$off_row 		=mysqli_fetch_array($res_get_branch);
			
			
			$sql_get_existing_off ="SELECT DISTINCT branchoff_offid FROM  tbl_class_branchoffering WHERE branchoff_branchid ='".$branch_id."'";
			
			
		}
	  	if($req_type =="add")
	  	{
			 $data = '';
	         $sql = "SELECT tcb.*,tc.*,ta.area_name,ta.area_direction FROM tbl_class_branch as tcb INNER JOIN tbl_class as tc ON tcb.class_branch_classid= tc.class_id ";
			 $sql .=" INNER JOIN tbl_area as ta ON tcb.class_branch_areaid= ta.area_id ";
			 $sql .= "WHERE class_branch_id='".$branch_id."' ";
			 $res_get_branch 	= mysqli_query($db_con, $sql) or die(mysqli_error($db_con));
			 $branch_row 		=mysqli_fetch_array($res_get_branch);
		  
			$data .= '';
			$data .='<input type="hidden" name="add_offering_req" value="1">';
			$data .='<input type="hidden" name="class_id" id="class_id" value="'.$branch_row['class_branch_classid'].'" >';
			$data .='<input type="hidden" name="branch_id" id="branch_id" value="'.$branch_row['class_branch_id'].'" >';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Class Name</label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" class="input-xlarge" value="'.ucwords($branch_row['class_name']).'" readonly="readonly" />';
			$data .= '</div>';
			$data .= '</div>';
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Branch</label>';
			$data .= '<div class="controls">';
			$data .= '<textarea readonly="readonly" class="input-xlarge"  >'.ucwords($branch_row['area_name']).'( '.ucwords($branch_row['area_direction']).' ) '.ucwords($branch_row['class_branch_address']).'</textarea>';
			$data .= '</div>';
			$data .= '</div>';
			
			/*$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Select Offering Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<select  style="width:30%" id ="add_off_type" name="off_type" data-rule-required="true" class ="select2-me"  onchange="getoffering(this.value,'.$branch_id.');">';
			$data .= '<option value="">Select Offering Type</option>';
			$sql_get_offering = " SELECT DISTINCT offering_type FROM tbl_offering WHERE offering_status ='1' ";
			$res_get_offering	= mysqli_query($db_con,$sql_get_offering) or die(mysqli_error($db_con));
			while($row= mysqli_fetch_array($res_get_offering))
			{
				$data .= '<option value="'.$row['offering_type'].'" >'.$row['offering_type'].'</option>';
			}
			$data .= '</select>'; 
			$data .= '</div>';
			$data .= '</div>';*/
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Select Offering<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<select style="width:30%" id="offering" name="offering" data-rule-required="true" class = "select2-me offering1" onChange="coupon(this.value);">';
			$data .= '<option value="">Select Offering</option>';
				$sql_get_offring =" SELECT * FROM tbl_offering WHERE  cat_id IN (SELECT DISTINCT cat_id FROM tbl_class_cats WHERE branch_id ='".$branch_id."' )";             $sql_get_offring .=" AND offering_id NOT IN (SELECT DISTINCT branchoff_offid FROM tbl_class_branchoffering WHERE branchoff_branchid='".$branch_id."')";
				$res_get_offering =mysqli_query($db_con,$sql_get_offring) or die(mysqli_error($db_con));
				while($row_get_off =mysqli_fetch_array($res_get_offering))
				{
					$data .= '<option value="'.$row_get_off['offering_id'].'">'.ucwords($row_get_off['offering_name']).'</option>';
				}
			$data .= '</select>'; 
			$data .= '</div>';
			$data .= '</div>';
	
		   
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Time<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '
					<table class="table table-bordered dataTable" style="width:50%;text-align:center" >
					<thead>
					<tr>
					  <th>Days</th>
					  <th>From</th>
					  <th>To</th>
					</thead>  
					</tr>
					<tbody>
					<tr>
					<td>Mon</td>
					  <td><input  type="text" name="Monday_form" id="Monday_form" class="form_time timepicker"></td>
					  <td><input  type="text" name="Monday_to" id="Monday_to" class="to_time timepicker"></td>
					</tr>
					<tr>
					  <td>Tue</td>
					  <td><input  type="text" name="Tuesday_form" id="Tuesday_form" class="form_time timepicker"></td>
					  <td><input  type="text" name="Tuesday_to" id="Tuesday_to" class="to_time timepicker"></td>
					</tr>
					<tr>
					 <td>Wed</td>
					  <td><input  type="text" name="Wednesday_form" id="Wednesday_form" class="form_time timepicker"></td>
					  <td><input  type="text" name="Wednesday_to" id="Wednesday_to" class="to_time timepicker"></td>
					</tr>
					<tr>
					<td>Thu</td>
					  <td><input  type="text" name="Thursday_form" id="Thursday_form" class="form_time timepicker"></td>
					  <td><input  type="text" name="Thursday_to" id="Thursday_to" class="to_time timepicker"></td>
					</tr>
					<tr>
					<td>Fri</td>
					  <td><input  type="text" name="Friday_form" id="Friday_form" class="form_time timepicker"></td>
					  <td><input  type="text" name="Friday_to" id="Friday_to" class="to_time timepicker"  ></td>
					</tr>
					<tr>
					 <td>Sat</td>
					  <td><input  type="text" name="Saturday_form" id="Saturday_form" class="form_time timepicker"></td>
					  <td><input  type="text" name="Saturday_to" id="Saturday_to" class="to_time timepicker"></td>
					</tr> 
					<tr>
					 <td>Sun</td>
					  <td><input  type="text" name="Sunday_form" id="Sunday_form" class="form_time timepicker"></td>
					  <td><input  type="text" name="Sunday_to" id="Sunday_to" class="to_time timepicker"></td>
					</tr>
					</tbody>
					</table>
					<script type="text/javascript">
$(".timepicker").timepicker({
		        showInputs: false,
		        showMeridian : true,
		        defaultTime : false,
		        showSeconds: false
		    });
			
			$(".timepicker").click(function(){
    $(".bootstrap-timepicker-hour").html("'.date('h').'");
	 $(".bootstrap-timepicker-minute").html("'.date('i').'");
	  $(".bootstrap-timepicker-meridian").html("'.date('A').'");
});
</script>
					';
				
			$data .= '</div>';	
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Duration  Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<select  style="width:30%" id ="add_duration_type" name="duration_type" data-rule-required="true" class ="select2-me">';
			$data .= '<option value="">Select Duration Type</option>';
			$data .= '<option value="Days">In Days</option>';
			$data .= '<option value="Months">In Months</option>';
			$data .= '</select>'; 
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Duration<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" minlength="1" maxlength="2" id="duration" onKeyPress="return numsonly(event);" placeholder="Enter Duration" Name="duration" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';	
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">No. of Sessions<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" minlength="1" maxlength="2" id="sessions" onKeyPress="return numsonly(event);" placeholder="Enter Session" Name="sessions" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Fee in Rupees<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" minlength="1" maxlength="6" id="fee" onKeyPress="return numsonly(event);" placeholder="Enter Fee" Name="fee" class="input-xlarge" data-rule-required="true" />';
			$data .= '<i class="fa fa-inr" aria-hidden="true"></i></div>';
			$data .= '</div>';	
			
				
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Seat Availability<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" minlength="1" maxlength="5" id="seat" onKeyPress="return numsonly(event);" placeholder="Enter Seat Availability" Name="seat" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';	
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Teacher Student Ratio</label>';
			$data .= '<div class="controls">';
			$data .= '<input  value="" type="text"  id="ts_ratio" placeholder="1:10" Name="ts_ratio" class="input-xlarge"  />';
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Filters</label>';
			$data .= '<div class="controls">';
			$sql_get_parent_filters = " select * from tbl_filters where filt_type = 'parent' and filt_sub_child = 'parent' ";
			$result_get_parent_filters = mysqli_query($db_con,$sql_get_parent_filters) or die(mysqli_error($db_con));
			while($row_get_parent_filters = mysqli_fetch_array($result_get_parent_filters))
			{
				$data .= '<div style="border-bottom:1px solid #8f8f8f;padding:10px;">';
				$data .= '<input type="checkbox" onclick="checkchild('.$row_get_parent_filters['filt_id'].');" value="'.$row_get_parent_filters['filt_id'].'" id="filter_parent'.$row_get_parent_filters['filt_id'].'" name="filters[]" class="css-checkbox batch_filters filters_parent" ';
				$data .= '>';
				$data .= ucwords($row_get_parent_filters['filt_name']).'<label for="filter_parent'.$row_get_parent_filters['filt_id'].'" class="css-label" ></label>';
						
				$sql_get_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' and filt_sub_child = 'child' ";
				$result_get_child_filters = mysqli_query($db_con,$sql_get_child_filters) or die(mysqli_error($db_con));			
				$data .= '<div style="padding:10px;margin:3px;">';			
				while($row_get_child_filters = mysqli_fetch_array($result_get_child_filters))
				{
					$data .= '<div style="float:left;padding-right:5px;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid #8f8f8f;margin-right:10px;margin-top:10px;">';	
					$data .= '<input onclick="checksubchild('.$row_get_child_filters['filt_id'].');" type="checkbox" value="'.$row_get_child_filters['filt_id'].'" id="filter_child'.$row_get_child_filters['filt_id'].'" name="filters[]" class="css-checkbox batch_filters filters_child'.$row_get_parent_filters['filt_id'].' " ';
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
							$data .= '<input type="checkbox" value="'.$row_get_sub_child_filters['filt_id'].'" id="filters_sub_child'.$row_get_sub_child_filters['filt_id'].'" name="filters[]" class="css-checkbox batch_filters filters_sub_child'.$row_get_parent_filters['filt_id'].' filters_subchild'.$row_get_child_filters['filt_id'].' " ';
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
			
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Level</label>';
			$data .= '<div class="controls">';
			$sql_get_parent_levels 			= " select * from tbl_level where cat_type = 'parent' and cat_name != 'none'";
			$result_get_parent_levels 		= mysqli_query($db_con,$sql_get_parent_levels) or die(mysqli_error($db_con));
			while($row_get_parent_levels 	= mysqli_fetch_array($result_get_parent_levels))
			{
				$data .= '<div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid #8f8f8f;margin-right:10px;margin-top:10px;">';
				$data .= '<input onclick="checklevel('.$row_get_parent_levels['cat_id'].')" type="checkbox" value="'.$row_get_parent_levels['cat_id'].'" id="level_parent'.$row_get_parent_levels['cat_id'].'" name="level[]" class="css-checkbox batch_levels levels_parent"';
				$data .= '>';
				$data .= ucwords($row_get_parent_levels['cat_name']).'<label for="level_parent'.$row_get_parent_levels['cat_id'].'" class="css-label" ></label>';
						
				$sql_get_child_levels = " select * from tbl_level where cat_type = '".$row_get_parent_levels['cat_id']."' ";//and cat_name != 'none' ";
				$result_get_child_levels = mysqli_query($db_con,$sql_get_child_levels) or die(mysqli_error($db_con));			
				$data .= '<div style="margin:20px;">';			
				while($row_get_child_levels = mysqli_fetch_array($result_get_child_levels))
				{
					$data .= '<input onclick="checkparent('.$row_get_parent_levels['cat_id'].')" type="checkbox" value="'.$row_get_child_levels['cat_id'].'" id="level_child'.$row_get_child_levels['cat_id'].'" name="level[]" onchange="checkParent(\''.$row_get_parent_levels['cat_id'].'\',\''.$row_get_child_levels['cat_id'].'\');" class="css-checkbox batch_levels levels_child'.$row_get_parent_levels['cat_id'].' "';
					$data .= '>';
					$data .= ucwords($row_get_child_levels['cat_name']).'<label for="level_child'.$row_get_child_levels['cat_id'].'" class="css-label"></label>';
				}
				$data .= '</div>';			
				$data .= '</div>';			
			}
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="radio" name="status" value="1"  data-rule-required="true">Active';
			$data .= '<input type="radio" style="margin:10px;" name="status" value="0" checked="checked"  data-rule-required="true">Inactive<br>';
			$data .= '</div>';
			$data .= '</div>';
	  
			$data .= '<br><button class="btn-info" type="submit" onclick="">Add Offering</button>';
			$data .= '<script type="text/javascript">';
			$data .= '$("#add_off_type").select2();';
			$data .= '$("#add_duration_type").select2();';
			$data .= '$(".offering1").select2();';
			//$data .= '$(".bootstrap-timepicker-hour").html("1");';
			//$data .= 'CKEDITOR.replace( "address",{height:"150", width:"100%"});';
			$data .= '</script>';
			$response_array = array("Success"=>"Success","resp"=>$data);				
			echo json_encode($response_array);exit();

	  	}
		////////////////////////---------------------add request end here-------------------------//////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		
		
		if($req_type =="edit")
	  	{
			 $data = '';
	          $sql = "SELECT tcb.*,tc.*,ta.area_name,ta.area_direction FROM tbl_class_branch as tcb INNER JOIN tbl_class as tc ON tcb.class_branch_classid= tc.class_id ";
			 $sql .=" INNER JOIN tbl_area as ta ON tcb.class_branch_areaid= ta.area_id ";
			 $sql .= "WHERE class_branch_id='".$branch_id."' ";
			 $res_get_branch 	= mysqli_query($db_con, $sql) or die(mysqli_error($db_con));
			 $branch_row 		=mysqli_fetch_array($res_get_branch);
		  
			$data .= '';
			$data .='<input type="hidden" name="edit_offering_req" value="1">';
			$data .='<input type="hidden" name="offering_id" value="'.$off_row['branchoff_id'].'">';
			$data .='<input type="hidden" name="class_id" id="class_id" value="'.$branch_row['class_branch_classid'].'" >';
			$data .='<input type="hidden" name="branch_id" id="branch_id" value="'.$branch_row['class_branch_id'].'" >';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Class Name</label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" class="input-xlarge" value="'.ucwords($branch_row['class_name']).'" readonly="readonly" />';
			$data .= '</div>';
			$data .= '</div>';
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Branch</label>';
			$data .= '<div class="controls">';
			$data .= '<textarea readonly="readonly" class="input-xlarge"  >'.ucwords($branch_row['area_name']).'( '.ucwords($branch_row['area_direction']).' ) '.ucwords($branch_row['class_branch_address']).'</textarea>';
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label"> Offering</label>';
			$data .= '<div class="controls">';
			$data .= '<select readonly="readonly" class="select2-me edit_offering" style="width:30%" id="offering" name="offering" data-rule-required="true" >';
			$sql ="SELECT * FROM tbl_offering WHERE offering_type ='".$off_row['branchoff_type']."'";
			$result	= mysqli_query($db_con, $sql) or die(mysqli_error($db_con));
			$num    = mysqli_num_rows($result);
			    $data .='<option value="">Select Offering</option>';
				while($row = mysqli_fetch_array($result))
				{
					$data .='<option value="'.$row['offering_id'].'" ';
					if($row['offering_id']==$off_row['branchoff_offid'])
					{
						$data .=' selected ';
					}
					$data .=' >'.$row['offering_name'].'</option>';
				}
				
			
			$data .= '</select>'; 
			$data .= '</div>';
			$data .= '</div>';
	
		   
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Time<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '
					<table class="table table-bordered dataTable" style="width:50%;text-align:center" >
					<thead>
					<tr>
					  <th>Day</th>
					  <th>From</th>
					  <th>To</th>
					</thead>  
					</tr>
					<tbody>
					<tr>
					<td>Mon</td>
					  <td><input readonly type="text" name="Monday_form" id="Monday_form" value="" class="form_time Monday_form timepicker"></td>
					  <td><input readonly type="text" name="Monday_to" id="Monday_to" class="form_time Monday_to timepicker"></td>
					</tr>
					<tr>
					  <td>Tue</td>
					  <td><input readonly type="text" name="Tuesday_form" id="Tuesday_form" class="form_time Tuesday_form timepicker"></td>
					  <td><input readonly type="text" name="Tuesday_to" id="Tuesday_to" class="form_time Tuesday_to timepicker"></td>
					</tr>
					<tr>
					 <td>Wed</td>
					  <td><input readonly type="text" name="Wednesday_form" id="Wednesday_form" class="form_time Wednesday_form timepicker"></td>
					  <td><input readonly type="text" name="Wednesday_to" id="Wednesday_to" class="form_time Wednesday_to timepicker"></td>
					</tr>
					<tr>
					<td>Thu</td> 
					  <td><input readonly type="text" name="Thursday_form" id="Thursday_form" class="form_time Thursday_form timepicker"></td>
					  <td><input readonly type="text" name="Thursday_to" id="Thursday_to" class="form_time Thursday_to timepicker"></td>
					</tr>
					<tr>
					<td>Fri</td>
					  <td><input readonly type="text" name="Friday_form" id="Friday_form" class="timepicker Friday_form"></td>
					  <td><input readonly type="text" name="Friday_to" id="Friday_to" class="form_time timepicker Friday_to" ></td>
					</tr>
					<tr>
					 <td>Sat</td>
					  <td><input readonly type="text" name="Saturday_form" id="Saturday_form" class="Saturday_form form_time timepicker"></td>
					  <td><input readonly type="text" name="Saturday_to" id="Saturday_to" class=" Saturday_to form_time timepicker"></td>
					</tr> 
					<tr>
					 <td> Sun</td>
					  <td><input readonly type="text" name="Sunday_form" id="Sunday_form" class="Sunday_form form_time timepicker"></td>
					  <td><input readonly type="text" name="Sunday_to" id="Sunday_to" class=" Sunday_to form_time timepicker"></td>
					</tr>
					</tbody>
					</table>
					<script type="text/javascript">
$(".timepicker").timepicker({
		        showInputs: false,
		        showMeridian : true,
		        defaultTime : false,
		        showSeconds: false
		    });
			
			
			$(".timepicker").click(function(){
    $(".bootstrap-timepicker-hour").html("'.date('h').'");
	 $(".bootstrap-timepicker-minute").html("'.date('i').'");
	  $(".bootstrap-timepicker-meridian").html("'.date('A').'");
});
</script>';
 
	         $data .= '</div>';	
			$data .= $data1.'</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Duration  Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<select  style="width:30%" id ="edit_duration_type" name="duration_type" data-rule-required="true" class ="select2-me">';
			$data .= '<option value="">Select Duration Type</option>';
			$data .= '<option value="Days" ';
			if($off_row['branchoff_durationtype']=="Days")
			{
				$data .=' selected="selected" ';
			}
			$data.=' >In Days</option>';
			$data .= '<option value="Months" ';
			if($off_row['branchoff_durationtype']=="Months")
			{
				$data .=' selected="selected" ';
			}
			$data .=' >In Months</option>';
			$data .= '</select>'; 
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Duration<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input value="'.$off_row['branchoff_duration'].'" type="text" minlength="1" maxlength="2" id="duration" onKeyPress="return numsonly(event);" placeholder="Enter Duration" Name="duration" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">No. of Sessions <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input value="'.$off_row['branchoff_sessions'].'" type="text" minlength="1" maxlength="2" id="sessions" onKeyPress="return numsonly(event);" placeholder="Enter Session" Name="sessions" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';
			
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Fee<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input value="'.$off_row['branchoff_fee'].'" type="text" minlength="1" maxlength="6" id="fee" onKeyPress="return numsonly(event);" placeholder="Enter Fee" Name="fee" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';	
			
			/*$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Duration<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input value="'.$off_row['branchoff_duration'].'" type="text" minlength="1" maxlength="2" id="duration" onKeyPress="return numsonly(event);" placeholder="Enter Duration" Name="duration" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';*/
				
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Seat Availability<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input value="'.$off_row['branchoff_seats'].'" type="text" minlength="1" maxlength="5" id="seat" onKeyPress="return numsonly(event);" placeholder="Enter Seat Availabilyty" Name="seat" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';	
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Teacher Student Ratio</label>';
			$data .= '<div class="controls">';
			$data .= '<input  value="'.$off_row['ts_ratio'].'" type="text"  id="ts_ratio" placeholder="1:10" Name="ts_ratio" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Filters</label>';
			$data .= '<div class="controls">';
			$sql_get_off_filter 			= " select GROUP_CONCAT(offfilt_fildid) as filters  from tbl_class_offfilter where offfilt_offid = '".$offering_id."' ";
			$res_get_off_filter 		= mysqli_query($db_con,$sql_get_off_filter) or die(mysqli_error($db_con));
			$row_filt = mysqli_fetch_array($res_get_off_filter);
			$filters = explode(',',$row_filt['filters']);
			
			$sql_get_parent_filters = " select * from tbl_filters where filt_type = 'parent' and filt_sub_child = 'parent' ";
			$result_get_parent_filters = mysqli_query($db_con,$sql_get_parent_filters) or die(mysqli_error($db_con));
			while($row_get_parent_filters = mysqli_fetch_array($result_get_parent_filters))
			{
				$data .= '<div style="border-bottom:1px solid #8f8f8f;padding:10px;">';
				$data .= '<input onclick="checkchild('.$row_get_parent_filters['filt_id'].');" type="checkbox" value="'.$row_get_parent_filters['filt_id'].'" id="filter_parent'.$row_get_parent_filters['filt_id'].'" ';
				if(in_array($row_get_parent_filters['filt_id'],$filters))
				{
					$data .=' checked="checked" ';
				}
				$data .=' name="filters[]" class="css-checkbox batch_filters filters_parent" ';
				$data .= '>';
				$data .= ucwords($row_get_parent_filters['filt_name']).'<label for="filter_parent'.$row_get_parent_filters['filt_id'].'" class="css-label" ></label>';
						
				$sql_get_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' and filt_sub_child = 'child' ";
				$result_get_child_filters = mysqli_query($db_con,$sql_get_child_filters) or die(mysqli_error($db_con));			
				$data .= '<div style="padding:10px;margin:3px;">';			
				while($row_get_child_filters = mysqli_fetch_array($result_get_child_filters))
				{
					$data .= '<div style="float:left;padding-right:5px;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid #8f8f8f;margin-right:10px;margin-top:10px;">';	
					$data .= '<input onclick="checksubchild('.$row_get_child_filters['filt_id'].');" type="checkbox" value="'.$row_get_child_filters['filt_id'].'" id="filter_child'.$row_get_child_filters['filt_id'].'" ';
					if(in_array($row_get_child_filters['filt_id'],$filters))
					{
						$data .=' checked="checked" ';
					}
					$data .=' name="filters[]" class="css-checkbox batch_filters filters_child'.$row_get_parent_filters['filt_id'].' " ';
					$data .= '>';
					
					$data .= ucwords($row_get_child_filters['filt_name']).'<label for="filter_child'.$row_get_child_filters['filt_id'].'"  class="css-label"></label>';
					
					$sql_get_sub_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' and filt_sub_child = '".$row_get_child_filters['filt_id']."' ";
					$result_get_sub_child_filters = mysqli_query($db_con,$sql_get_sub_child_filters) or die(mysqli_error($db_con));	
					$num_rows_get_sub_child_filters = mysqli_num_rows($result_get_sub_child_filters);		
					if($num_rows_get_sub_child_filters != 0)
					{
						$data .= '<div style="padding:10px;">';			
						while($row_get_sub_child_filters = mysqli_fetch_array($result_get_sub_child_filters))
						{
							$data .= '<input type="checkbox" value="'.$row_get_sub_child_filters['filt_id'].'" id="filters_sub_child'.$row_get_sub_child_filters['filt_id'].'"';
							if(in_array($row_get_sub_child_filters['filt_id'],$filters))
							{
								$data .=' checked="checked" ';
							}
							$data .=' name="filters[]" class="css-checkbox batch_filters filters_sub_child'.$row_get_parent_filters['filt_id'].' filters_subchild'.$row_get_child_filters['filt_id'].' " ';
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
			
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Level</label>';
			$data .= '<div class="controls">';
			
			$sql_get_off_levels 			= " select GROUP_CONCAT(offlevel_level_id) as levels  from tbl_class_offlevel where offlevel_offid = '".$offering_id."' ";
			$res_get_off_levels 		= mysqli_query($db_con,$sql_get_off_levels) or die(mysqli_error($db_con));
			$row_level = mysqli_fetch_array($res_get_off_levels);
			$levels = explode(',',$row_level['levels']);
			
			//$data .=$sql_get_off_levels;
			$sql_get_parent_levels 			= " select * from tbl_level where cat_type = 'parent' and cat_name != 'none'";
			$result_get_parent_levels 		= mysqli_query($db_con,$sql_get_parent_levels) or die(mysqli_error($db_con));
			while($row_get_parent_levels 	= mysqli_fetch_array($result_get_parent_levels))
			{
				$data .= '<div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid #8f8f8f;margin-right:10px;margin-top:10px;">';
				$data .= '<input onclick="checklevel('.$row_get_parent_levels['cat_id'].')" type="checkbox" value="'.$row_get_parent_levels['cat_id'].'" id="level_parent'.$row_get_parent_levels['cat_id'].'" ';
				if(in_array($row_get_parent_levels['cat_id'],$levels))
				{
					$data .=' checked="checked" ';
				}
				$data .=' name="level[]" class="css-checkbox batch_levels levels_parent"';
				$data .= '>';
				$data .= ucwords($row_get_parent_levels['cat_name']).'<label for="level_parent'.$row_get_parent_levels['cat_id'].'" class="css-label" ></label>';
						
				$sql_get_child_levels = " select * from tbl_level where cat_type = '".$row_get_parent_levels['cat_id']."' ";//and cat_name != 'none' ";
				$result_get_child_levels = mysqli_query($db_con,$sql_get_child_levels) or die(mysqli_error($db_con));			
				$data .= '<div style="margin:20px;">';			
				while($row_get_child_levels = mysqli_fetch_array($result_get_child_levels))
				{
					$data .= '<input onclick="checkparent('.$row_get_parent_levels['cat_id'].')" type="checkbox" value="'.$row_get_child_levels['cat_id'].'" id="level_child'.$row_get_child_levels['cat_id'].'"  ';
					if(in_array($row_get_child_levels['cat_id'],$levels))
					{
						$data .=' checked="checked" ';
					}
					$data .=' name="level[]"  class="css-checkbox batch_levels levels_child'.$row_get_parent_levels['cat_id'].' "';
					$data .= '>';
					$data .= ucwords($row_get_child_levels['cat_name']).'<label for="level_child'.$row_get_child_levels['cat_id'].'" class="css-label"></label>';
				}
				$data .= '</div>';			
				$data .= '</div>';			
			}
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="radio" name="status" value="1" ';
			if($off_row['branchoff_status']==1)
			{
				$data .=' checked ';
			}
			$data .= '  data-rule-required="true">Active';
			$data .= '<input type="radio" style="margin:10px;" name="status" value="0" ';
			if($off_row['branchoff_status']==0)
			{
				$data .=' checked ';
			}
			$data .='  data-rule-required="true">Inactive<br>';
			$data .= '</div>';
			$data .= '</div>';
	  
			$data .= '<br><button class="btn-info" type="submit" onclick="">Update Offering</button>';
            $data .= '<script type="text/javascript">';
			$data .= '$("#off_type1").select2();';
			$data .= '$(".edit_offering").select2();';
			$data .= '$("#edit_duration_type").select2();';
			//$data .= '$("#timepicker1").timepicker()';
			
			
           $sql_get_days ="SELECT * FROM tbl_offeringday WHERE day_offeringid='".$offering_id."'";
           $res_get_days	= mysqli_query($db_con, $sql_get_days) or die(mysqli_error($db_con));
           while($day_row = mysqli_fetch_array($res_get_days))
           {
              $data .='$(".'.$day_row['day'].'_form").val("'.$day_row['from_time'].'");';
              $data .='$(".'.$day_row['day'].'_to").val("'.$day_row['to_time'].'");';
			}

			$data .= '</script>';
		    $response_array = array("Success"=>"Success","resp"=>$data,"offering_id"=>$offering_id);				
			echo json_encode($response_array);exit();

	  	}
		
		////////////////////////---------------------add request end here-------------------------//////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		if($req_type =="view")
	  	{
			 $data = '';
	         $sql = "SELECT tcb.*,tc.* FROM tbl_class_branch as tcb INNER JOIN tbl_class as tc ON tcb.class_branch_classid= tc.class_id WHERE class_branch_id='".$branch_id."' ";
			 $res_get_branch 	= mysqli_query($db_con, $sql) or die(mysqli_error($db_con));
			 $offering_row 		=mysqli_fetch_array($res_get_branch);
		    
			 $sql = "SELECT tcb.*,tc.*,ta.area_name,ta.area_direction FROM tbl_class_branch as tcb INNER JOIN tbl_class as tc ON tcb.class_branch_classid= tc.class_id ";
			 $sql .=" INNER JOIN tbl_area as ta ON tcb.class_branch_areaid= ta.area_id ";
			 $sql .= "WHERE class_branch_id='".$branch_id."' ";
			 $res_get_branch 	= mysqli_query($db_con, $sql) or die(mysqli_error($db_con));
			 $offering_row 		=mysqli_fetch_array($res_get_branch);
			
			$data .= '';
			$data .='<input type="hidden" name="edit_offering_req" value="1">';
			$data .='<input type="hidden" name="class_id" id="class_id" value="'.$offering_row['class_branch_classid'].'" >';
			$data .='<input type="hidden" name="branch_id" id="branch_id" value="'.$offering_row['class_branch_id'].'" >';
					$data .='<input type="hidden" name="offering_id" id="offering_id" value="'.$offering_id.'" >';
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Class Name</label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" class="input-xlarge" readonly="readonly" value="'.ucwords($offering_row['class_name']).'" readonly="readonly" />';
			$data .= '</div>';
			$data .= '</div>';
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Branch</label>';
			$data .= '<div class="controls">';
		//	$data .= '<input type="text" class="input-xlarge" readonly="readonly"  value="'.$offering_row['class_branch_address'].'" readonly="readonly" />';
			$data .= '<textarea readonly="readonly" class="input-xlarge"  >'.ucwords($offering_row['area_name']).' ( '.ucwords($offering_row['area_direction']).' ) '.ucwords($offering_row['class_branch_address']).'</textarea>';
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Select Offering Type</label>';
			$data .= '<div class="controls">';
			$data .= '<select  style="width:30%" id ="view_off_type" name="off_type" disabled data-rule-required="true" class ="select2-me"  onchange="getoffering(this.value);">';
			$data .= '<option value="">Select Offering Type</option>';
			$sql_get_offering = " SELECT DISTINCT offering_type FROM tbl_offering WHERE offering_status ='1' ";
			$res_get_offering	= mysqli_query($db_con,$sql_get_offering) or die(mysqli_error($db_con));
			while($row= mysqli_fetch_array($res_get_offering))
			{
				$data .= '<option value="'.$row['offering_type'].'"';
				if($row['offering_type']==$off_row['branchoff_type'])
				{
					$data .=' selected ';
				}
				
				$data .= ' >'.$row['offering_type'].'</option>';
			}
			$data .= '</select>'; 
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Select Offering</label>';
			$data .= '<div class="controls">';
			$data .= '<select disabled class="select2-me" style="width:30%" id="view_offering" name="offering" data-rule-required="true" >';
			$sql ="SELECT * FROM tbl_offering WHERE offering_type ='".$off_row['branchoff_type']."'";
			$result	= mysqli_query($db_con, $sql) or die(mysqli_error($db_con));
			$num    = mysqli_num_rows($result);
			    $data .='<option value="">Select Offering</option>';
				while($row = mysqli_fetch_array($result))
				{
					$data .='<option value="'.$row['offering_id'].'" ';
					if($row['offering_id']==$off_row['branchoff_offid'])
					{
						$data .=' selected ';
					}
					$data .=' >'.$row['offering_name'].'</option>';
				}
				
			
			$data .= '</select>'; 
			$data .= '</div>';
			$data .= '</div>';
	
		   
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Time</label>';
			$data .= '<div class="controls">';
			$data .= '
					<table class="table table-bordered dataTable" style="width:50%;text-align:center" >
					<thead>
					<tr>
					  <th>Day</th>
					  <th>From</th>
					  <th>To</th>
					</thead>  
					</tr>
					<tbody>
					<tr>
					<td>Mon</td>
					  <td><input type="text" disabled name="Monday_form" id="Monday_form" value="" class="form_time Monday_form timepicker"></td>
					  <td><input type="text" disabled name="Monday_to" id="Monday_to" class="form_time Monday_to timepicker"></td>
					</tr>
					<tr>
					  <td>Tue</td>
					  <td><input type="text" disabled name="Tuesday_form" id="timepicker" class="form_time Tuesday_form timepicker"></td>
					  <td><input type="text" name="Tuesday_to" id="timepicker" class="form_time Tuesday_to timepicker"></td>
					</tr>
					<tr>
					 <td>Wed</td>
					  <td><input type="text" disabled name="Wednesday_form" id="timepicker" class="form_time Wednesday_form timepicker"></td>
					  <td><input type="text" name="Wednesday_to" id="timepicker" class="form_time Wednesday_to timepicker"></td>
					</tr>
					<tr>
					<td>Thu</td>
					  <td><input type="text" disabled name="Thursday_form" id="timepicker" class="form_time Thursday_form timepicker"></td>
					  <td><input type="text" disabled name="Thursday_to" id="timepicker" class="form_time Thursday_to timepicker"></td>
					</tr>
					<tr>
					<td>Fri</td>
					  <td><input type="text" disabled name="Friday_form" id="timepicker1" class="timepicker Friday_form"></td>
					  <td><input type="text" disabled name="Friday_to" id="timepicker" class="form_time timepicker Friday_to" ></td>
					</tr>
					<tr>
					 <td>Sat</td>
					  <td><input type="text" disabled name="Saturday_form" id="timepicker" class="Saturday_form form_time timepicker"></td>
					  <td><input type="text" disabled name="Saturday_to" id="timepicker" class=" Saturday_to form_time timepicker"></td>
					</tr> 
					<tr>
					 <td> Sun</td>
					  <td><input type="text" disabled name="Sunday_form" id="timepicker" class="Sunday_form form_time timepicker"></td>
					  <td><input type="text" disabled name="Sunday_to" id="timepicker" class=" Sunday_to form_time timepicker"></td>
					</tr>
					</tbody>
					</table>
					
					';
            $data .= '</div>';	
			$data .= $data1.'</div>';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Duration Type</label>';
			$data .= '<div class="controls">';
			$data .= '<input readonly value="'.$off_row['branchoff_durationtype'].'" type="text"  id="duration" onKeyPress="return numsonly(event);" placeholder="Enter Duration" Name="duration" class="input-xlarge"  />';
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Duration</label>';
			$data .= '<div class="controls">';
			$data .= '<input readonly value="'.$off_row['branchoff_duration'].'" type="text" minlength="1" maxlength="2" id="duration" onKeyPress="return numsonly(event);" placeholder="Enter Duration" Name="duration" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">No. of Sessions </label>';
			$data .= '<div class="controls">';
			$data .= '<input readonly value="'.$off_row['branchoff_sessions'].'" type="text" minlength="1" maxlength="2" id="sessions" onKeyPress="return numsonly(event);" placeholder="Enter Session" Name="sessions" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';
			
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Fee</label>';
			$data .= '<div class="controls">';
			$data .= '<input readonly value="'.$off_row['branchoff_seats'].'" type="text" minlength="1" maxlength="6" id="fee" onKeyPress="return numsonly(event);" placeholder="Enter Fee" Name="fee" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';	
			
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Teacher Student Ratio</label>';
			$data .= '<div class="controls">';
			$data .= '<input readonly value="'.$off_row['ts_ratio'].'" type="text"  id="ts_ratio" placeholder="1:10" Name="ts_ratio" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';
			
				
			$data .= '<div class="control-group">';		
			$data .= '<label for="tasktitel" class="control-label">Seat Availability</label>';
			$data .= '<div class="controls">';
			$data .= '<input readonly value="'.$off_row['branchoff_fee'].'" type="text" minlength="1" maxlength="5" id="seat" onKeyPress="return numsonly(event);" placeholder="Enter Seat Availabilyty" Name="seat" class="input-xlarge" data-rule-required="true" />';
			$data .= '</div>';
			$data .= '</div>';	
			
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Filters</label>';
			$data .= '<div class="controls">';
			$sql_get_off_filter 			= " select GROUP_CONCAT(offfilt_fildid) as filters  from tbl_class_offfilter where offfilt_offid = '".$offering_id."' ";
			$res_get_off_filter 		= mysqli_query($db_con,$sql_get_off_filter) or die(mysqli_error($db_con));
			$row_filt = mysqli_fetch_array($res_get_off_filter);
			$filters = explode(',',$row_filt['filters']);
			
			$sql_get_parent_filters = " select * from tbl_filters where filt_type = 'parent' and filt_sub_child = 'parent' ";
			$result_get_parent_filters = mysqli_query($db_con,$sql_get_parent_filters) or die(mysqli_error($db_con));
			while($row_get_parent_filters = mysqli_fetch_array($result_get_parent_filters))
			{
				$data .= '<div style="border-bottom:1px solid #8f8f8f;padding:10px;">';
				$data .= '<input type="checkbox" value="'.$row_get_parent_filters['filt_id'].'" id="filter_parent'.$row_get_parent_filters['filt_id'].'" ';
				if(in_array($row_get_parent_filters['filt_id'],$filters))
				{
					$data .=' checked="checked" ';
				}
				$data .=' name="filters[]" disabled="disabled" class="css-checkbox batch_filters filters_parent" ';
				$data .= '>';
				$data .= ucwords($row_get_parent_filters['filt_name']).'<label for="filter_parent'.$row_get_parent_filters['filt_id'].'" class="css-label" ></label>';
						
				$sql_get_child_filters = " select * from tbl_filters where filt_type = '".$row_get_parent_filters['filt_id']."' and filt_sub_child = 'child' ";
				$result_get_child_filters = mysqli_query($db_con,$sql_get_child_filters) or die(mysqli_error($db_con));			
				$data .= '<div style="padding:10px;margin:3px;">';			
				while($row_get_child_filters = mysqli_fetch_array($result_get_child_filters))
				{
					$data .= '<div style="float:left;padding-right:5px;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid #8f8f8f;margin-right:10px;margin-top:10px;">';	
					$data .= '<input type="checkbox" value="'.$row_get_child_filters['filt_id'].'" id="filter_child'.$row_get_child_filters['filt_id'].'" ';
					if(in_array($row_get_child_filters['filt_id'],$filters))
					{
						$data .=' checked="checked" ';
					}
					$data .=' name="filters[]" disabled="disabled" class="css-checkbox batch_filters filters_child'.$row_get_parent_filters['filt_id'].' " ';
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
							$data .= '<input disabled type="checkbox" value="'.$row_get_sub_child_filters['filt_id'].'" id="filters_sub_child'.$row_get_sub_child_filters['filt_id'].'"';
							if(in_array($row_get_sub_child_filters['filt_id'],$filters))
							{
								$data .=' checked="checked" ';
							}
							$data .=' name="filters[]" disabled="disabled" class="css-checkbox batch_filters filters_sub_child'.$row_get_child_filters['filt_id'].' " ';
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
			
			$data .= '<div class="control-group">';
			$data .= '<label for="radio" class="control-label">Level</label>';
			$data .= '<div class="controls">';
			
			$sql_get_off_levels 			= " select GROUP_CONCAT(offlevel_level_id) as levels  from tbl_class_offlevel where offlevel_offid = '".$offering_id."' ";
			$res_get_off_levels 		= mysqli_query($db_con,$sql_get_off_levels) or die(mysqli_error($db_con));
			$row_level = mysqli_fetch_array($res_get_off_levels);
			$levels = explode(',',$row_level['levels']);
			
			//$data .=$sql_get_off_levels;
			$sql_get_parent_levels 			= " select * from tbl_level where cat_type = 'parent' and cat_name != 'none'";
			$result_get_parent_levels 		= mysqli_query($db_con,$sql_get_parent_levels) or die(mysqli_error($db_con));
			while($row_get_parent_levels 	= mysqli_fetch_array($result_get_parent_levels))
			{
				$data .= '<div style="float:left;border-bottom:1px solid #8f8f8f;padding:10px;border-right:1px solid #8f8f8f;margin-right:10px;margin-top:10px;">';
				$data .= '<input disabled  type="checkbox" value="'.$row_get_parent_levels['cat_id'].'" id="level_parent'.$row_get_parent_levels['cat_id'].'" ';
				if(in_array($row_get_parent_levels['cat_id'],$levels))
				{
					$data .=' checked="checked" ';
				}
				$data .=' name="level[]" class="css-checkbox batch_levels levels_parent"';
				$data .= '>';
				$data .= ucwords($row_get_parent_levels['cat_name']).'<label for="level_parent'.$row_get_parent_levels['cat_id'].'" class="css-label" ></label>';
						
				$sql_get_child_levels = " select * from tbl_level where cat_type = '".$row_get_parent_levels['cat_id']."' ";//and cat_name != 'none' ";
				$result_get_child_levels = mysqli_query($db_con,$sql_get_child_levels) or die(mysqli_error($db_con));			
				$data .= '<div style="margin:20px;">';			
				while($row_get_child_levels = mysqli_fetch_array($result_get_child_levels))
				{
					$data .= '<input disabled type="checkbox" value="'.$row_get_child_levels['cat_id'].'" id="level_child'.$row_get_child_levels['cat_id'].'"  ';
					if(in_array($row_get_child_levels['cat_id'],$levels))
					{
						$data .=' checked="checked" ';
					}
					$data .=' name="level[]" onchange="checkParent(\''.$row_get_parent_levels['cat_id'].'\',\''.$row_get_child_levels['cat_id'].'\');" class="css-checkbox batch_levels levels_child'.$row_get_parent_levels['cat_id'].' "';
					$data .= '>';
					$data .= ucwords($row_get_child_levels['cat_name']).'<label for="level_child'.$row_get_child_levels['cat_id'].'" class="css-label"></label>';
				}
				$data .= '</div>';			
				$data .= '</div>';			
			}
			$data .= '</div>';
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Status</label>';
			$data .= '<div class="controls">';
			if($off_row['branchoff_status']==1)
			{
				$data .='Active';
			}
			else
			{
				$data .='Inactive';
			}
			
			$data .= '</div>';
			$data .= '</div>';
	  
		
            $data .= '<script type="text/javascript">';
			$data .= '$("#view_off_type").select2();';
			$data .= '$("#view_offering").select2();';
			$data .= '$("#view_duration_type").select2();';
			//$data .= '$("#timepicker1").timepicker()';
			
			
           $sql_get_days ="SELECT * FROM tbl_offeringday WHERE day_offeringid='".$offering_id."'";
           $res_get_days	= mysqli_query($db_con, $sql_get_days) or die(mysqli_error($db_con));
           while($day_row = mysqli_fetch_array($res_get_days))
           {
              $data .='$(".'.$day_row['day'].'_form").val("'.$day_row['from_time'].'");';
              $data .='$(".'.$day_row['day'].'_to").val("'.$day_row['to_time'].'");';
			}

			$data .= '</script>';
		    $response_array = array("Success"=>"Success","resp"=>$data,"offering_id"=>$offering_id);				
			echo json_encode($response_array);exit();

	  	}
	  }
	  else
	  {
		  
	  }
	  echo json_encode($response_array);
	}
	
	if((isset($_POST['add_offering_req'])) == "1" && isset($_POST['add_offering_req']))
	{
		$class_id	 = $_POST['class_id'];
		$branch_id	 = $_POST['branch_id'];
		$off_type	 = $_POST['off_type'];
		$offering	 = $_POST['offering'];
		$fee		 = $_POST['fee'];
		$seat		 = $_POST['seat'];
		$duration	 	 = $_POST['duration'];
		$duration_type	 	 = $_POST['duration_type'];
		$status	 	 = $_POST['status'];
		$days		 = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
		$levels		 = $_POST['level'];
		$filters		 = $_POST['filters'];
		$sessions	 = $_POST['sessions'];
		$ts_ratio	 = mysqli_real_escape_string($db_con,$_POST['ts_ratio']);
		
		//echo json_encode(array("Success"=>"fail","resp"=>$ts_ratio));exit();
		
		$offering_id = insert_offering($class_id,$branch_id,$off_type,$offering,$fee,$seat,$status,$duration,$duration_type,$sessions,$ts_ratio);
		
		if($offering_id !=0)
		{  
		    if(!empty($days))
			{
				foreach($days as $day)
				{  
					$from 		= $_POST[$day.'_form'];
					$to 		= $_POST[$day.'_to'];
					if($from!="" && $to!="")
					{
						$insert_off = insert_offeringdays($offering_id,$class_id,$branch_id,$day,$from,$to);
					}
					
				}
			}
			
			if(!empty($levels))
			{
				foreach($levels as $level)
				{  
					$insert_level = insert_offlevel($class_id,$branch_id,$offering_id,$level);
					
				}
			}
			
			if(!empty($filters))
			{
				foreach($filters as $filt_id)
				{  
					$insert_filter = insert_offfilter($class_id,$branch_id,$offering_id,$filt_id);
					
				}
			}
			$response_array = array("Success"=>"Success","resp"=>"Offering Added Successfully","branch_id"=>$branch_id);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Offering Not Added");
		}
	echo json_encode($response_array);	
		
	}
	
	if((isset($_POST['edit_offering_req'])) == "1" && isset($_POST['edit_offering_req']))
	{
		$class_id	 = $_POST['class_id'];
		$offering_id = $_POST['offering_id'];
		$branch_id	 = $_POST['branch_id'];
		$off_type	 = $_POST['off_type'];
		$offering	 = $_POST['offering'];
		$duration	 = $_POST['duration'];
		$duration_type	 = $_POST['duration_type'];
		$fee		 = $_POST['fee'];
		$seat		 = $_POST['seat'];
		$status	 	 = $_POST['status'];
		$days		 = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
		$levels		 = $_POST['level'];
		$filters	 = $_POST['filters'];
		$sessions	 = $_POST['sessions'];
		$ts_ration	 = mysqli_real_escape_string($db_con,$_POST['ts_station']);
		
		$update_off = update_offering($offering_id,$off_type,$offering,$duration,$fee,$seat,$status,$duration_type,$sessions,$ts_ration);
		if($update_off)
		{   $sql_delete_off	= " DELETE FROM `tbl_offeringday` WHERE `day_offeringid`='".$offering_id."' ";
		    $res_delete_off	= mysqli_query($db_con, $sql_delete_off) or die(mysqli_error($db_con));
			
			$sql_delete_off	= " DELETE FROM `tbl_class_offfilter` WHERE `offfilt_offid`='".$offering_id."' ";
		    $res_delete_off	= mysqli_query($db_con, $sql_delete_off) or die(mysqli_error($db_con));
			
			$sql_delete_off	= " DELETE FROM `tbl_class_offlevel` WHERE `offlevel_offid`='".$offering_id."' ";
		    $res_delete_off	= mysqli_query($db_con, $sql_delete_off) or die(mysqli_error($db_con));
		    if(!empty($days))
			{    
			     
				foreach($days as $day)
				{  
					$from 		= $_POST[$day.'_form'];
					$to 		= $_POST[$day.'_to'];
					
					if($from!="" && $to!="")
					{    
					    $insert_off = insert_offeringdays($offering_id,$class_id,$branch_id,$day,$from,$to);
					}
					
				}
				
				
			}
			
				if(!empty($levels))
				{
					foreach($levels as $level)
					{  
						$insert_level = insert_offlevel($class_id,$branch_id,$offering_id,$level);
						
					}
				}
			
				if(!empty($filters))
				{
					foreach($filters as $filt_id)
					{  
						$insert_filter = insert_offfilter($class_id,$branch_id,$offering_id,$filt_id);
						
					}
				}
			
			
			
			$response_array = array("Success"=>"Success","resp"=>"Branch Added Successfully","branch_id"=>$branch_id);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Branch Not Added");
		}
	echo json_encode($response_array);	
		
	}
	
	if((isset($obj->delete_offering)) == "1" && isset($obj->delete_offering))
	{
		$offering 		= $obj->offering;
		$response_array = array();
		
		$del_flag =0;
		foreach($offering as $offering_id)
		{
			$sql_delete_off	= " DELETE FROM `tbl_class_branchoffering` WHERE `branchoff_id`='".$offering_id."' ";
		    $res_delete_off	= mysqli_query($db_con, $sql_delete_off) or die(mysqli_error($db_con));
			if($res_delete_off)
			{   $del_flag = 1;
				$sql_delete_off	= " DELETE FROM `tbl_offeringday` WHERE `day_offeringid`='".$offering_id."' ";
				$res_delete_off	= mysqli_query($db_con, $sql_delete_off) or die(mysqli_error($db_con));
				
				$sql_delete_off	= " DELETE FROM `tbl_class_offfilter` WHERE `offfilt_offid`='".$offering_id."' ";
				$res_delete_off	= mysqli_query($db_con, $sql_delete_off) or die(mysqli_error($db_con));
				
				$sql_delete_off	= " DELETE FROM `tbl_class_offlevel` WHERE `offlevel_offid`='".$offering_id."' ";
				$res_delete_off	= mysqli_query($db_con, $sql_delete_off) or die(mysqli_error($db_con));
			}
		}	
		
		if($del_flag == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Branch Deleted Successfully.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Deletion Failed.");
		}
		
		echo json_encode($response_array);	
	}
	
	if((isset($obj->change_off_status)) == "1" && isset($obj->change_off_status))
	{
		$branchoff_id					= mysqli_real_escape_string($db_con,$obj->branchoff_id);
		$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
		$response_array 		= array();
	
		$sql_update_branch_status	= "UPDATE `tbl_class_branchoffering` 
														SET `branchoff_status`='".$curr_status."',
															`branchoff_modified`='".$datetime."',
															`branchoff_modified_by`='".$uid."' 
													WHERE `branchoff_id`='".$branchoff_id."'";
					$res_update_branch_status	= mysqli_query($db_con, $sql_update_branch_status) or die(mysqli_error($db_con));
					if($res_update_branch_status)
					{
						$flag_class	= 1;	
					}
					else
					{
						$flag_class	= 0;	
					}
		
		if($flag_class == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
		}
		
		echo json_encode($response_array);	
	}
	
	if((isset($obj->get_offering)) == "1" && isset($obj->get_offering))
	{
		$off_type					=$obj->off_type;
		$response_array 		= array();
		$branch_id              = $obj->branch_id;
	    $data 					='';
		$sql  ="SELECT * FROM tbl_offering WHERE offering_type ='".$off_type."'";
		$sql .=" AND offering_id NOT IN (SELECT DISTINCT branchoff_offid FROM  tbl_class_branchoffering WHERE branchoff_branchid ='".$branch_id."')";
		$result	= mysqli_query($db_con, $sql) or die(mysqli_error($db_con));
		$num    = mysqli_num_rows($result);
		if($num>0)
		{   $data .='<option value="">Select Offering</option>';
			while($row = mysqli_fetch_array($result))
			{
				$data .='<option value="'.$row['offering_id'].'">'.$row['offering_name'].'</option>';
			}
			$response_array = array("Success"=>"Success","resp"=>$data);
		}
		else
		{
			$response_array = array("Success"=>"Success","resp"=>"No records Found");
		}
		
		
		echo json_encode($response_array);	
	}
	
/////////////////----------------End :  Offering Part---------------------------////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	if((isset($obj->load_add_class_part)) != "" && isset($obj->load_add_class_part))
	{
		$class_id 	= $obj->class_id;
		$req_type 	= $obj->req_type;
		
		if($class_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$class_id."' "; // this org id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_class_data	= json_decode($row_error_data['error_data']);
		}
		elseif(($class_id != "" && $req_type == "edit") || ($class_id != "" && $req_type == "view"))
		{
			$sql_class_data 		= " SELECT * FROM `tbl_class` WHERE `class_id`='".$class_id."' ";
			$result_class_data 	= mysqli_query($db_con,$sql_class_data) or die(mysqli_error($db_con));
			$row_class_data		= mysqli_fetch_array($result_class_data);		
		}
		
		if($req_type != "add")
		{
			if($req_type != "error")
			{
				// Getting Billing Addrs
				$sql_billing_address 	= "SELECT * FROM `tbl_address_master` WHERE `add_user_id` = ";
				$sql_billing_address .= "'".$class_id."'";
				$sql_billing_address 	.= " and `add_id` = '".$row_class_data['org_billing_address']."' ";
				$sql_billing_address 	.= " and `add_user_type` = 'organisation' ";
				$result_billing_address	= mysqli_query($db_con,$sql_billing_address) or die(mysqli_error($db_con));
				$row_billing_address	= mysqli_fetch_array($result_billing_address);
	
				// Getting Shipping Addrs
				$sql_shipping_address 	= "SELECT * FROM `tbl_address_master` WHERE `add_user_id` = ";
				$sql_shipping_address 	.= "'".$class_id."'";	
				$sql_shipping_address 	.= " and `add_id` = '".$row_class_data['org_shipping_address']."' ";
				$sql_shipping_address 	.= " and `add_user_type` = 'organisation' ";
				$result_shipping_address	= mysqli_query($db_con,$sql_shipping_address) or die(mysqli_error($db_con));
				$row_shipping_address		= mysqli_fetch_array($result_shipping_address);	
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>$req_type);	
			}
		}
		
		$data = '';
		if($class_id != "" && $req_type == "edit")
		{
			$data = '<input type="hidden" name="class_id" id="class_id" value="'.$row_class_data['class_id'].'">';
		}
		elseif($class_id != "" && $req_type == "error")
		{
			$data = '<input type="hidden" id="error_id" value="'.$class_id.'">';
		}
		$data .= '<h3 style="margin:0px;">';
		$data .= 'Class Details';
		$data .= '</h3>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Class Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="class_name" name="class_name" placeholder="Class Name" class="input-large" data-rule-required="true"'; 
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.ucwords($row_class_data->org_name).'"';
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.ucwords($row_class_data['class_name']).'" disabled';					
		}		
		else
		{
			$data .= 'value="'.ucwords($row_class_data['class_name']).'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Organisation Name -->';
		
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Primary Email-ID <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="pri_email" name="pri_email" placeholder="Primary Email-ID" class="input-large" data-rule-email="true"  data-rule-required="true" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_primary_email.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_primary_email'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_primary_email'].'"';					
		}
		$data .= '/>';
		$data .= '<span id="comp_pri"></span>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group span6" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Primary Phone <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="pri_phone" name="pri_phone" placeholder="Primary Phone" class="input-large" data-rule-required="true" maxlength="10" minlength="10" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_primary_phone.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_primary_phone'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_primary_phone'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group span6">';
		$data .= '<label for="tasktitel" class="control-label">Secendory Email-ID </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="sec_email" name="sec_email" placeholder="Secendory Email-ID" data-rule-email="true"  class="input-large" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_secondary_email.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_secondary_email'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_secondary_email'].'"';					
		}
		$data .= '/>';
		$data .= '<span id="comp_sec"></span>';
		$data .= '</div>';
		$data .= '</div>';
		
		
		
		
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Alt Phone </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="alt_phone" minlength="6" name="alt_phone" placeholder="Alt. Phone" class="input-large" maxlength="15" onKeyPress="return isNumberKey(event)" onKeyUp="ValidateMobile(this.id)" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_secondary_phone.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_secondary_phone'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_secondary_phone'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Facebook Page Link</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="fb_link"  name="fb_link" placeholder="Facebook Page Link" class="input-large"  ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->fb_link.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['fb_link'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['fb_link'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		
		
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Tagline</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="tgline"  style="width:95%" name="tgline" placeholder="Tagline or small Desc" class="input-large"  ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->tgline.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['tgline'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['tgline'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		//=======About Instructor=============//
		
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">About Instructor</label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="about_instructor"  style="width:95%" name="about_instructor" placeholder="Tagline or small Desc" class="input-large"  >';
		if($class_id != "" && $req_type == "error")
		{
			$data .= $row_class_data->about_instructor;					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .=$row_class_data['about_instructor'];					
		}		
		else
		{
			$data .=$row_class_data['about_instructor'];					
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div>';
		//=======About Instructor=============//
		
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Logo</label>';
		$data .= '<div class="controls">';
		if($req_type != "view")
		{
			$data .='<input type="file"   name="logo" id="file"class="input-xlarge"  accept="image/jpg,image/png,image/jpeg">';
			$data .='<input type="hidden" name="old_logo" value="'.$row_class_data['class_logo'].'">';
		}
		if($req_type != "add" && $row_class_data['class_logo'] !="")
		{
			$data .='<img style="width:100px;height:100px" src="../images/coaching_class/class_id'.$class_id.'/'.$row_class_data['class_logo'].'">&nbsp;';
		}
		$data .= '</div>';
		$data .= '</div>';
		
		
		
		$data .='<div class="control-group">';
        $data .='<label class="control-label">Gallery</label>';
        $data .='<input type="hidden" value="1" name="site_banner_hid" id="site_banner_hid">';                                           
        $data .='<div id="siteBanners">';                                                                                                                                     
	    $data .='<div class="controls">';
		if($req_type != "view")
		{
	   
        $data .='<input type="file"   name="banner[]" multiple data-min-file-count="3" ';
		$data .=' id="file"' ;
		$data .= 'class="input-xlarge"  accept="image/jpg,image/png,image/jpeg" ';
		$data .=' ><br>';
		}
		if($req_type != "add")
		{
			$sql_get_banner	    = "SELECT * FROM tbl_classbanners WHERE banner_classid ='".$class_id."'";
			$res_get_banner 	= mysqli_query($db_con,$sql_get_banner) or die(mysqli_error($db_con));
			$banner_num         = mysqli_num_rows($res_get_banner);
			if($banner_num > 0)
			{
				while($img_row=mysqli_fetch_array($res_get_banner))
				{
				   $data .='<div class="span2" style="padding:10px"><img style="width:100px;height:100px" src="../images/coaching_class/class_id'.$class_id.'/'.$img_row['banner_name'].'">&nbsp;';
				   $edit	= checkFunctionalityRight("view_coaching_class.php",2);
				   if($edit && $req_type == "edit")
				   {
					   $data .='<a href="#" title="Click here to delete banner"><img style="width:25px;height:25px" onclick="delete_banner('.$img_row['banner_id'].','.$class_id.')";" src="images/delete.png"></a>';
				   }
				   $data .=' </div> ';
				}
			}
			else
			{
				$data .=' No banner available';
			}
		}
		
		 $data .='</div>';                                                
        $data .=' </div>';
        $data .='</div>';
		/*monika*/
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Website </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="class_website" name="class_website" placeholder="Website" class="input-large" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_website.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_website'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_website'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		
		/*done by monika on 11-01-2017*/
		
		$class_type = array("Learners Home","Institution","Trainers Home");
		$class_method =array("Individual","Group");
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Class Type<span style="color:#F00;font-size:20px;">*</span> </label>';
		$data .= '<div class="controls">';
		
		
		$data .= '<select style=" width: 95%;margin-top:10px;" name="classtype[]" multiple="multiple"  id="classtype" onChange="console.log($(this).children(":selected").length)" placeholder="Class Type" class="input-block-level ">';
		
		 foreach($class_type as $type)
		 {
			if($req_type=="add")
			{
				$data .='<option value="'.$type.'">'.$type.'</option>';
			}
			elseif($req_type=="view" || $req_type=="edit")
			{
				$class_types = explode(',',$row_class_data['class_type']);
				$data .='<option value="'.$type.'" ';
				if(in_array($type,$class_types))
				{
					$data .=' selected="selected" ';
				}
				$data .=' >'.$type.'</option>';
			}
			
		}
		$data .='</select>';
		
		$data .= '</div>';
		$data .= '</div>';
		
		
		
		
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Method<span style="color:#F00;font-size:20px;">*</span> </label>';
		$data .= '<div class="controls">';
		
		$data .= '<select style=" width: 95%;margin-top:10px;" name="method[]" multiple="multiple"  id="method" onChange="console.log($(this).children(":selected").length)" placeholder="Class Method" class="input-block-level ">';
		
		 foreach($class_method as $method)
		 {
			if($req_type=="add")
			{
				$data .='<option value="'.$method.'">'.$method.'</option>';
			}
			elseif($req_type=="view" || $req_type=="edit")
			{
				$class_method = explode(',',$row_class_data['class_method']);
				$data .='<option value="'.$method.'" ';
				if(in_array($method,$class_method))
				{
					$data .=' selected="selected" ';
				}
				$data .=' >'.$method.'</option>';
			}
			
		}
		$data .='</select>';
		$data .= '</div>';
		$data .= '</div>';
		/*done by monika on 11-01-2017*/

		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Beneficiary Name </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="class_beneficiary_name" name="class_beneficiary_name" placeholder="Benificiary Name" class="input-large" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_beneficiary_name.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_beneficiary_name'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_beneficiary_name'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Bank Name </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="class_bank_name" name="class_bank_name" placeholder="Bank Name" class="input-large" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_bank_name.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_bank_name'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_bank_name'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';		
		$data .= '</div>';
		$data .= '<div>';		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Bank Address </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="class_bank_address" name="class_bank_address" placeholder="Bank Address" class="input-large" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_bank_address.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_bank_address'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_bank_address'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';		
		$data .= '</div> ';	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Bank Account Number </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" onKeyPress="return isNumberKey(event)" maxlength="15" id="class_bank_account_number" name="class_bank_account_number" placeholder="Bank Account Number" class="input-large" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_bank_account_number.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_bank_account_number'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_bank_account_number'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';		
		$data .= '</div> ';	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">IFSC Code </label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="class_bank_ifsc_code" onkeypress="return charsnonly(event)" name="class_bank_ifsc_code" placeholder="Bank IFSC Code" class="input-large" ';
		if($class_id != "" && $req_type == "error")
		{
			$data .= 'value="'.$row_class_data->org_bank_ifsc_code.'"';					
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= 'value="'.$row_class_data['class_bank_ifsc_code'].'" disabled';					
		}		
		else
		{
			$data .= 'value="'.$row_class_data['class_bank_ifsc_code'].'"';					
		}
		$data .= '/>';
		$data .= '</div>';		
		$data .= '</div> ';						
		
		
		
		
		/*monika*/
		$data .= '<div class="control-group" style="clear:both;">';
		$data .= '<label for="tasktitel" class="control-label">Description</label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="class_desc" name="class_desc" class="input-large" rows="6"';
		if($class_id != "" && $req_type == "view")
		{
			$data .=' disabled="disabled" ';					
		}
		$data .=' >';
		if($class_id != "" && $req_type == "error")
		{
			$data .= $row_class_data->org_description;		
		}
		elseif($class_id != "" && $req_type == "view")
		{
			$data .= $row_class_data['class_description'];					
		}		
		else
		{
			$data .= $row_class_data['class_description'];
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!-- class Description -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($class_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="org_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_coaching_class.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_class_data->org_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="org_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_class_data->org_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($class_id != "" && $req_type == "view")
		{
			if($row_class_data['class_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_class_data['class_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="class_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_coaching_class.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_class_data['class_status'] == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="class_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_class_data['class_status'] == 0)
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
		if($class_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Class</button>';			
		}
		elseif($class_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Class</button>';
		}
		else if($class_id != "" && $req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update Class</button>';			
		}
		$data .= '</div> <!-- Save and cancel -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#classtype").select2();';
		$data .= '$("#method").select2();';
		$data .= '$("#catid").select2();';
		$data .= '$("#parent_catid").select2();';
		
		//$data .= 'CKEDITOR.replace( "class_desc",{height:"150", width:"100%"});';
		//$data .= 'CKEDITOR.replace( "org_ship_addrs",{height:"150", width:"100%"});';
		//$data .= 'CKEDITOR.replace( "class_desc",{height:"150", width:"100%"});';
		if($class_id != "" && $req_type == "view")
		{
			$data .= '$("#org_bill_addrs").prop("disabled","true");';
			$data .= '$("#org_ship_addrs").prop("disabled","true");';
			//$data .= '$("#class_desc").prop("disabled","true");';			
		}	
		$data .= '</script>';
		$response_array = array("Success"=>"Success","resp"=>$data);
		echo json_encode($response_array);
	}
	
	if((isset($obj->val_email)) != "" && isset($obj->val_email))
	{
		$email_val	= $obj->comp1_val;
		$response_array = array();
		
		if(strcmp($email_val,"")!==0)
		{
			$sql_chk_email	= " SELECT `org_primary_email`, `org_secondary_email`, `org_tertiary_email`
								FROM `tbl_oraganisation_master` 
								WHERE org_primary_email='".$email_val."'
									OR org_secondary_email='".$email_val."'
									OR org_tertiary_email='".$email_val."' ";
			$res_chk_email 	= mysqli_query($db_con, $sql_chk_email) or die(mysqli_error($db_con));
			$num_chk_email 	= mysqli_num_rows($res_chk_email);
			if(strcmp($num_chk_email,"0")===0)
			{
				$response_array = array("Success"=>"Success","resp"=>$num_chk_email);
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Email-ID Already Exist");	
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Empty Email");	
		}
		echo json_encode($response_array);	
	}
	
	if((isset($_POST['add_clas_request'])) == "1" && isset($_POST['add_clas_request']))
	{  
	
	   				$files 					= $_FILES['banner']['name'];
					$logo					= $_FILES['logo']['name'];
					$class_name			    = strtolower(mysqli_real_escape_string($db_con,$_POST['class_name']));
					$class_description		= mysqli_real_escape_string($db_con,$_POST['class_desc']);
					
					$class_primary_email	= mysqli_real_escape_string($db_con,$_POST['pri_email']);
					$class_secondary_email	= mysqli_real_escape_string($db_con,$_POST['sec_email']);
					$class_primary_phone    = mysqli_real_escape_string($db_con,$_POST['pri_phone']);
					$class_secondary_phone	= mysqli_real_escape_string($db_con,$_POST['alt_phone']);
					$class_website			= mysqli_real_escape_string($db_con,$_POST['class_website']);
					$class_status			= mysqli_real_escape_string($db_con,$_POST['class_status']);
					$class_bank_ifsc_code	= mysqli_real_escape_string($db_con,$_POST['class_bank_ifsc_code']);
					$class_bank_account_number= mysqli_real_escape_string($db_con,$_POST['class_bank_account_number']);	
					$class_bank_address		= mysqli_real_escape_string($db_con,$_POST['class_bank_address']);
					$class_bank_name		= mysqli_real_escape_string($db_con,$_POST['class_bank_name']);
					$class_beneficiary_name = mysqli_real_escape_string($db_con,$_POST['class_beneficiary_name']);
					$classtype              = $_POST['classtype'];
					$method					= $_POST['method'];
					$tgline         		= mysqli_real_escape_string($db_con, $_POST['tgline']);
					$fb_link                = mysqli_real_escape_string($db_con,$_POST['fb_link']);
					
					
					$response_array = array();
	
	/*$response_array = array("Success"=>"fail","resp"=>$_FILES['banner']['name'][0]."=".$class_description."=".$org_primary_phone."=".$org_indid."=".$org_status);
		echo json_encode($response_array);		
		exit(0);*/
		
		if($class_name != '' && $class_primary_email!='' && $class_primary_phone!=''  && $class_status != "")
	   {    
	        $sql_check_email ="SELECT * FROM tbl_class WHERE class_primary_email ='".$class_primary_email."' or class_primary_phone ='".$class_primary_phone."'";
			$res_check_email 	= mysqli_query($db_con, $sql_check_email) or die(mysqli_error($db_con));
			$num = mysqli_num_rows($res_check_email);
			if($num > 0)
			{
				$response_array = array("Success"=>"fail","resp"=>"Mobile or Email already exist");
				echo json_encode($response_array);
				exit();
				
			}
			
			$class_id= insertClass($response_array,$class_name,$class_description,$class_primary_email,$class_secondary_email,$class_primary_phone,$class_secondary_phone,$class_website,$class_status,$class_bank_ifsc_code,$class_bank_account_number,$class_bank_address,$class_bank_name,$class_beneficiary_name,$class_owner,$classtype,$method,$logo,$fb_link,$tgline);
			if($class_id!="")
			{  
				$target_path="../images/coaching_class/class_id";
				$sec_dir 	= $target_path.$class_id;
				if(is_dir($sec_dir) === false)
				{
				mkdir($sec_dir);
				}
				move_uploaded_file($_FILES["logo"]["tmp_name"], $sec_dir.'/'.$logo);
				for($i=0;$i < count($_FILES['banner']['name']); $i++)
				{
				
				$file=$sec_dir.'/'.$_FILES['banner']['name'][$i];
				if($_FILES['banner']['name'][$i] !=""){
					
				$fileData = pathinfo(basename($_FILES["banner"]["name"][$i]));
				$fileName = uniqid() . '.' . $fileData['extension'];	
				$target_path ="../images/coaching_class/class_id".$class_id."/".$fileName;
			
				
				while(file_exists($target_path))
				{
				$fileName = uniqid() . '.' . $fileData['extension'];
				$target_path ="../images/coaching_class/class_id".$class_id."/".$fileName;
				}
				
				move_uploaded_file($_FILES["banner"]["tmp_name"][$i], $target_path);
				$sql_insert_image	= " INSERT INTO `tbl_classbanners`(`banner_classid`, `banner_name`) ";
				$sql_insert_image  .=" VALUES ('".$class_id."', '".$fileName."') ";
				$res_insert_image 	= mysqli_query($db_con, $sql_insert_image) or die(mysqli_error($db_con));
				}
			}// for end
			$response_array = array("Success"=>"Success","resp"=>$class_primary_phone);	
		}
			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>$class_primary_phone);		
		}
		
		echo json_encode($response_array);
	}
	
	
	
	if((isset($obj->delete_class)) == "1" && isset($obj->delete_class))
	{
		$ar_class_id 		= $obj->batch;
		$response_array = array();
		
		$del_flag =0;
		foreach($ar_class_id as $class_id)
		{
			$sql_delete_class	= " DELETE FROM `tbl_class` WHERE `class_id`='".$class_id."' ";
		    $res_delete_class	= mysqli_query($db_con, $sql_delete_class) or die(mysqli_error($db_con));
			if($res_delete_class)
			{   
			   // delete banner start//
				$sql_get_banner = "SELECT * FROM tbl_classbanners WHERE banner_classid ='".$class_id."'";
				$res_get_banner 	= mysqli_query($db_con,$sql_get_banner) or die(mysqli_error($db_con));
				while($img_row=mysqli_fetch_array($res_get_banner))
				{
				   $target_path="../images/coaching_class/class_id".$class_id.'/'.$img_row['banner_name'];
				   unlink($target_path);
				}
				
				$sql_delete_banner	= " DELETE FROM `tbl_classbanners` WHERE `banner_classid`='".$class_id."' ";
				$res_delete_banner	= mysqli_query($db_con, $sql_delete_banner) or die(mysqli_error($db_con));
				// delete banner end
				
				// for delete branches 
				$sql_delete_branch	= " DELETE FROM `tbl_class_branch` WHERE `class_branch_classid`='".$class_id."' ";
				$res_delete_branch	= mysqli_query($db_con, $sql_delete_branch) or die(mysqli_error($db_con));
				
				// for delete branches offering 
				$sql_delete_offering	= " DELETE FROM `tbl_class_branchoffering` WHERE `branchoff_classid`='".$class_id."' ";
				$res_delete_offering	= mysqli_query($db_con, $sql_delete_offering) or die(mysqli_error($db_con));
				
				// for delete branches offering day 
				$sql_delete_offering	= " DELETE FROM `tbl_offeringday` WHERE `day_classid`='".$class_id."' ";
				$res_delete_offering	= mysqli_query($db_con, $sql_delete_offering) or die(mysqli_error($db_con));
				
				// for delete offering filter 
				$sql_delete_offfilt	= " DELETE FROM `tbl_class_offfilter` WHERE `offfilt_classid`='".$class_id."' ";
				$res_delete_offfilt	= mysqli_query($db_con, $sql_delete_offfilt) or die(mysqli_error($db_con));
				
				//// for delete levels 
				$sql_delete_offlevel	= " DELETE FROM `tbl_class_offlevel` WHERE `offlevel_class_id`='".$class_id."' ";
				$res_delete_offlevel	= mysqli_query($db_con, $sql_delete_offlevel) or die(mysqli_error($db_con));
				
				$del_flag =1;
			}
		}	
		
		if($del_flag == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Class Deleted Successfully.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Deletion Failed.");
		}
		
		echo json_encode($response_array);	
	}
	
	
	
	
	
	if((isset($obj->change_status)) == "1" && isset($obj->change_status))
	{
		$class_id					= mysqli_real_escape_string($db_con,$obj->class_id);
		$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
		$response_array 		= array();
	
		$sql_update_class_status	= "UPDATE `tbl_class` 
														SET `class_status`='".$curr_status."',
															`class_modified`='".$datetime."',
															`class_modified_by`='".$uid."' 
													WHERE `class_id`='".$class_id."'";
					$res_update_class_status	= mysqli_query($db_con, $sql_update_class_status) or die(mysqli_error($db_con));
					if($res_update_class_status)
					{    
					    $sql_update_branch_status	= "UPDATE `tbl_class_branch` 
														SET `class_branch_status`='".$curr_status."',
															`class_branch_modified`='".$datetime."',
															`class_branch_modified_by`='".$uid."' 
					 								WHERE `class_branch_classid`='".$class_id."'";
					   $res_update_branch_status	= mysqli_query($db_con, $sql_update_branch_status) or die(mysqli_error($db_con));
					
						$sql_update_off_status	= "UPDATE `tbl_class_branchoffering` 
														SET `branchoff_status`='".$curr_status."',
															`branchoff_modified`='".$datetime."',
															`branchoff_modified_by`='".$uid."' 
													WHERE `branchoff_classid`='".$class_id."'";
					  $res_update_off_status	= mysqli_query($db_con, $sql_update_off_status) or die(mysqli_error($db_con));
						
						$flag_class	= 1;	
					}
					else
					{
						$flag_class	= 0;	
					}
		
		if($flag_class == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
		}
		
		echo json_encode($response_array);	
	}
	
	if((isset($_POST['update_clas_request'])) == "1" && isset($_POST['update_clas_request']))
	{
					$class_id					= $_POST['class_id'];
					$file=$_FILES['file']['name'];
					
					$logo=$_FILES['logo']['name'];
					if($logo=="")
					{
						$logo = $_POST['old_logo'];
					}
					$class_name			    = strtolower(mysqli_real_escape_string($db_con,$_POST['class_name']));
					$class_description		= mysqli_real_escape_string($db_con,$_POST['class_desc']);
					$class_primary_email	= mysqli_real_escape_string($db_con,$_POST['pri_email']);
					$class_secondary_email	= mysqli_real_escape_string($db_con,$_POST['sec_email']);
					$class_primary_phone    = mysqli_real_escape_string($db_con,$_POST['pri_phone']);
					$class_secondary_phone	= mysqli_real_escape_string($db_con,$_POST['alt_phone']);
					$class_website			= mysqli_real_escape_string($db_con,$_POST['class_website']);
					$class_status			= mysqli_real_escape_string($db_con,$_POST['class_status']);
					$class_bank_ifsc_code	= mysqli_real_escape_string($db_con,$_POST['class_bank_ifsc_code']);
					$class_bank_account_number= mysqli_real_escape_string($db_con,$_POST['class_bank_account_number']);	
					$class_bank_address		= mysqli_real_escape_string($db_con,$_POST['class_bank_address']);
					$class_bank_name		= mysqli_real_escape_string($db_con,$_POST['class_bank_name']);
					$class_beneficiary_name = mysqli_real_escape_string($db_con,$_POST['class_beneficiary_name']);
					$classtype              = $_POST['classtype'];
					$method					= $_POST['method'];
					$fb_link                = mysqli_real_escape_string($db_con,$_POST['fb_link']);
					$tgline					= mysqli_real_escape_string($db_con,$_POST['tgline']);
					
		            $response_array = array();
					$sql_check_email ="SELECT * FROM tbl_class WHERE class_id !='".$class_id."' and (class_primary_email ='".$class_primary_email."' or class_primary_phone ='".$class_primary_phone."') ";
					$res_check_email 	= mysqli_query($db_con, $sql_check_email) or die(mysqli_error($db_con));
					$num = mysqli_num_rows($res_check_email);
					if($num > 0)
					{
						$response_array = array("Success"=>"fail","resp"=>"Mobile or Email already exist");
						echo json_encode($response_array);
						exit();
						
					}
		
		//echo json_encode($method); exit();
		
		if($class_name != '' && $class_primary_email!='' && $class_primary_phone!='' )
		{
			
			$response_array = updateClass($response_array,$class_id,$class_name,$class_description,$class_primary_email,$class_secondary_email,$class_primary_phone,$class_secondary_phone,$class_website,$class_status,$class_bank_ifsc_code,$class_bank_account_number,$class_bank_address,$class_bank_name,$class_beneficiary_name,$class_owner,$logo,$classtype,$method,$fb_link,$tgline);
				//$response_array = array("Success"=>"fail","resp"=>$class_catids);
						echo json_encode($response_array);
						exit();
		    
				$target_path="../images/coaching_class/class_id";
				$sec_dir 	= $target_path.$class_id;
				if(is_dir($sec_dir) === false)
				{
				mkdir($sec_dir);
				}
				move_uploaded_file($_FILES["logo"]["tmp_name"], $sec_dir.'/'.$logo);
				for($i=0;$i < count($_FILES['banner']['name']); $i++)
				{
				
				$file=$sec_dir.'/'.$_FILES['banner']['name'][$i];
				if($_FILES['banner']['name'][$i] !=""){
					
				$fileData = pathinfo(basename($_FILES["banner"]["name"][$i]));
				$fileName = uniqid() . '.' . $fileData['extension'];	
				$target_path ="../images/coaching_class/class_id".$class_id."/".$fileName;
			
				
				while(file_exists($target_path))
				{
					$fileName = uniqid() . '.' . $fileData['extension'];
					$target_path ="../images/coaching_class/class_id".$class_id."/".$fileName;
				}
				
				move_uploaded_file($_FILES["banner"]["tmp_name"][$i], $target_path);
				$sql_insert_image	= " INSERT INTO `tbl_classbanners`(`banner_classid`, `banner_name`) ";
				$sql_insert_image  .=" VALUES ('".$class_id."', '".$fileName."') ";
				$res_insert_image 	= mysqli_query($db_con, $sql_insert_image) or die(mysqli_error($db_con));
				}
			}// for end
			$sql_update_branch_status	= "UPDATE `tbl_class_branch` 
														SET `class_branch_status`='".$class_status."',
															`class_branch_modified`='".$datetime."',
															`class_branch_modified_by`='".$uid."' 
					 								WHERE `class_branch_classid`='".$class_id."'";
					   $res_update_branch_status	= mysqli_query($db_con, $sql_update_branch_status) or die(mysqli_error($db_con));
					
						$sql_update_off_status	= "UPDATE `tbl_class_branchoffering` 
														SET `branchoff_status`='".$class_status."',
															`branchoff_modified`='".$datetime."',
															`branchoff_modified_by`='".$uid."' 
													WHERE `branchoff_classid`='".$class_id."'";
					  $res_update_off_status	= mysqli_query($db_con, $sql_update_off_status) or die(mysqli_error($db_con));
					  
			$response_array = array("Success"=>"Success","resp"=>$class_id);	
		
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
		}	
		
		echo json_encode($response_array);	
	}
	
	
	if((isset($obj->delete_banner)) == "1" && isset($obj->delete_banner))
	{
		$class_id					= mysqli_real_escape_string($db_con,$obj->class_id);
		$img_id			= mysqli_real_escape_string($db_con,$obj->img_id);
		$response_array 		= array();
		
		
		$sql_get_img = "SELECT * FROM tbl_classbanners WHERE banner_id='".$img_id."'";
		$res_get_img =mysqli_query($db_con,$sql_get_img) or die(mysqli_error($db_con));
		$row_img = mysqli_fetch_array($res_get_img);
		
		unlink('../images/coaching_class/class_id'.$class_id.'/'.$row_img['banner_name']);
		
		$sql_delete_img ="DELETE FROM `tbl_classbanners` WHERE banner_id='".$img_id."' ";
		$res_delete_img =mysqli_query($db_con,$sql_delete_img) or die(mysqli_error($db_con));
	
		if($res_delete_img)
		{
			$response_array = array("Success"=>"Success","resp"=>"Image Deleted Successfully.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Image not deleted");
		}
		
		echo json_encode($response_array);	
	}
	
   
    if((isset($obj->get_subcat)) == "1" && isset($obj->get_subcat))
	{
		$data ="";
		$cat_ids  = $obj->cat_ids;
		$req_type = $obj->req_type;
		
		$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND main_parent IN (".implode(',',$cat_ids)." ) ";
		$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
		while($row_cat = mysqli_fetch_array($res_get_cat))
		{
			if($req_type=="add")
			{
				    if($row_cat['cat_type']=="parent")
				   {
					   $cat_path=$row_cat['cat_name'];
				   }
				   else
				   {  
				      $demo =show_full_path($row_cat['cat_type'],$array=array());
                      $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
				   }
				   
					$data .='<option value="'.$row_cat['cat_id'].'">'.$cat_path.'</option>';
					
			}
			elseif($req_type=="view" || $req_type=="edit")
			{
				$cat_ids = explode(',',$row_class_data['class_catids']);
			  	if($row_cat['cat_type']=="parent")
			   {
				   $cat_path=$row_cat['cat_name'];
			   }
			   else
			   {  
				  $demo =show_full_path($row_cat['cat_type'],$array=array());
				  $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
			   }
				
				$data .='<option value="'.$row_cat['cat_id'].'" ';
				if(in_array($row_cat['cat_id'],$cat_ids))
				{
					$data .=' selected="selected" ';
				}
				$data .=' >'.ucwords($cat_path).'</option>';
				
			}
			
		}
		
		echo json_encode(array("Success"=>"Success","resp"=>$data));
	}

?>