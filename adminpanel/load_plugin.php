<?php
	include("include/routines.php");
	
	function getPinCodeData($selectedCols, $tableName, $whereConditions, $whereNotConditions=array())
	{
		global $db_con;
		
		$sql_getPinCodeData	= " SELECT ".$selectedCols;
		$sql_getPinCodeData	.= " FROM ".$tableName." ";
		$sql_getPinCodeData	.= " WHERE 1=1 ";
		
		//==Check Where Condtions=====//
		if(!empty($whereConditions))
		{
			foreach($whereConditions as $field1 => $value1 )
			{   
				$sql_getPinCodeData  .= " AND ".$field1 ."='".$value1."' ";
			}
		}
		
		//==Check Not Where Condtions=====//
		if(!empty($whereNotConditions))
		{
			foreach($whereNotConditions as $field2 => $value2)
			{   
				$sql_getPinCodeData  .= " AND ".$field2 ."!='".$value2."' ";
			}
		}
		
		$res_getPinCodeData	= mysqli_query($db_con, $sql_getPinCodeData) or die(mysqli_error($db_con));
		
		return $res_getPinCodeData;
	}
	
	if((isset($obj->load_address)) == 1 && (isset($obj->load_address)))
	{
		$pincode	= $obj->pincode;
		$arr_dist	= array();
		$arr_taluka	= array();
		$arr_area	= array();
		
		if($pincode != '')
		{
			// query for getting pincode details from tbl_pincode [Only For State]
			$res_getPincodeData	= getPinCodeData('DISTINCT `pincode`, `state`', 'tbl_pincodes', array("pincode"=>$pincode));
			$num_getPincodeData	= mysqli_num_rows($res_getPincodeData);
			
			if($num_getPincodeData != 0)
			{
				$row_getPincodeData	= mysqli_fetch_array($res_getPincodeData);
				$rec_state_name		= $row_getPincodeData['state'];
				
				$sql_getCountryData	= " SELECT ts.state AS state_id, tc.country_id, tc.country_name ";
				$sql_getCountryData	.= " FROM `tbl_state` AS ts INNER JOIN `tbl_country` AS tc ";
				$sql_getCountryData	.= " 	ON ts.country_id = tc.country_id ";
				$sql_getCountryData	.= " WHERE ts.state_name = '".$rec_state_name."' ";
				$res_getCountryData	= mysqli_query($db_con,$sql_getCountryData) or die(mysqli_error($db_con));
				$num_getCountryData	= mysqli_num_rows($res_getCountryData);
				
				if($num_getCountryData != 0)
				{
					$row_getCountryData	= mysqli_fetch_array($res_getCountryData);
					$rec_country_name	= $row_getCountryData['country_name'];
					$rec_country_id		= $row_getCountryData['country_id'];
					$rec_state_id		= $row_getCountryData['state_id'];
					
					// query for getting pincode details from tbl_pincode [Only For District]
					$res_getDistData	= getPinCodeData('DISTINCT `pincode`, `district`', 'tbl_pincodes', array("pincode"=>$pincode));
					$num_getDistData	= mysqli_num_rows($res_getDistData);
					
					if($num_getDistData != 0)
					{
						while($row_getDistData = mysqli_fetch_array($res_getDistData))
						{
							array_push($arr_dist, $row_getDistData);
						}
						
						/*$arr_dist	.= '<select name="ddl_district" id="ddl_district" class = "select2-me input-large" onChange="getTaluka(this.value, '.$pincode.')">';
							$arr_dist	.= '<option value="">Select District</option>';
							while($row_getDistData = mysqli_fetch_array($res_getDistData))
							{
								$arr_dist	.= '<option value="'.$row_getDistData['district'].'">';
									$arr_dist	.= $row_getDistData['district'];
								$arr_dist	.= '</option>';
							}
						$arr_dist	.= '</select>';*/
						
						// query for getting pincode details from tbl_pincode [Only For Taluka]
						$res_getTalukaData	= getPinCodeData('DISTINCT `pincode`, `taluka`', 'tbl_pincodes', array("pincode"=>$pincode));
						$num_getTalukaData	= mysqli_num_rows($res_getTalukaData);
						
						if($num_getTalukaData != 0)
						{
							while($row_getTalukaData = mysqli_fetch_array($res_getTalukaData))
							{
								array_push($arr_taluka, $row_getTalukaData);
							}
							
							/*$arr_taluka	.= '<select name="ddl_taluka" id="ddl_taluka" class = "select2-me input-large" onChange="getArea(this.value, '.$pincode.')">';
								$arr_taluka	.= '<option value="">Select Taluka</option>';
								while($row_getTalukaData = mysqli_fetch_array($res_getTalukaData))
								{
									$arr_taluka	.= '<option value="'.$row_getTalukaData['taluka'].'">';
										$arr_taluka	.= $row_getTalukaData['taluka'];
									$arr_taluka	.= '</option>';
								}
							$arr_taluka	.= '</select>';*/
							
							// query for getting pincode details from tbl_pincode [Only For area]
							$res_getAreaData	= getPinCodeData('DISTINCT `pincode`, `office_name`, `id`', 'tbl_pincodes', array("pincode"=>$pincode));
							$num_getAreaData	= mysqli_num_rows($res_getAreaData);
							
							if($num_getAreaData != 0)
							{
								while($row_getAreaData = mysqli_fetch_array($res_getAreaData))
								{
									array_push($arr_area, $row_getAreaData);
								}
								
								/*$arr_area	.= '<select name="ddl_area" id="ddl_area" class = "select2-me input-large">';
								while($row_getAreaData = mysqli_fetch_array($res_getAreaData))
								{
									$arr_area	.= '<option value="'.$row_getAreaData['id'].'">';
										$arr_area	.= $row_getAreaData['office_name'];
									$arr_area	.= '</option>';
								}
								$arr_area	.= '</select>';*/
								
								$response_array	= array("Success"=>"Success", "resp"=>"Success", "country_id"=>$rec_country_id, "country_name"=>$rec_country_name, "state_id"=>$rec_state_id, "state_name"=>$rec_state_name, "district"=>$arr_dist, "taluka"=>$arr_taluka, "area"=>$arr_area);
								echo json_encode($response_array);
								exit();
							}
							else
							{
								quit('No Match Found For Taluka');	
							}	
						}
						else
						{
							quit('No Match Found For Taluka');	
						}	
					}
					else
					{
						quit('No Match Found For District');	
					}
				}
				else
				{
					quit('No Match Found For Country');	
				}
			}
			else
			{
				quit('No Match Found');	
			}
		}
		else
		{
			quit('Pincode should not be empty');	
		}	
	}
	
	if((isset($obj->load_taluka)) == '1' && (isset($obj->load_taluka)))
	{
		$pincode	= $obj->pincode;
		$distVal	= $obj->distVal;
		$arr_taluka	= array();
		$arr_area	= array();
		
		if($pincode != '' && $distVal != '')
		{
			// Have to find the respective Talukas and Areas
			$res_get_taluka	= getPinCodeData('DISTINCT `pincode`, `taluka`', 'tbl_pincodes', array("pincode"=>$pincode, "district"=>$distVal));
			$num_get_taluka	= mysqli_num_rows($res_get_taluka);
			
			if($num_get_taluka != 0)
			{
				while($row_get_taluka = mysqli_fetch_array($res_get_taluka))
				{
					array_push($arr_taluka, $row_get_taluka);
				}
				
				// Now Area
				$res_get_area	= getPinCodeData('DISTINCT `pincode`, `office_name`', 'tbl_pincodes', array("pincode"=>$pincode, "district"=>$distVal));
				$num_get_area	= mysqli_num_rows($res_get_area);
				
				if($num_get_area != 0)
				{
					while($row_get_area = mysqli_fetch_array($res_get_area))
					{
						array_push($arr_area, $row_get_area);	
					}
					
					$response_array	= array("Success"=>"Success", "resp"=>"Success", "taluka"=>$arr_taluka, "area"=>$arr_area);
					echo json_encode($response_array);
					exit();
				}
				else
				{
					quit('No Match Found');	
				}
			}
			else
			{
				quit('No Match Found');
			}
		}
		else
		{
			quit('District or Pincode should not be Empty');	
		}	
	}
	
	if((isset($obj->load_area)) == '1' && (isset($obj->load_area)))
	{
		
		$pincode		= $obj->pincode;
		$talVal			= $obj->talVal;
		$ddl_district	= $obj->ddl_district; 
		$arr_area		= array();
		
		if($pincode != '' && $talVal != '')
		{
			// Have to find the respective Talukas and Areas
			$res_get_area	= getPinCodeData('DISTINCT `pincode`, `office_name`', 'tbl_pincodes', array("pincode"=>$pincode, "taluka"=>$talVal, "district"=>$ddl_district));
			$num_get_area	= mysqli_num_rows($res_get_area);
			
			if($num_get_area != 0)
			{
				while($row_get_area = mysqli_fetch_array($res_get_area))
				{
					array_push($arr_area, $row_get_area);	
				}
				
				$response_array	= array("Success"=>"Success", "resp"=>"Success", "area"=>$arr_area);
				echo json_encode($response_array);
				exit();
			}
			else
			{
				quit('No Match Found');	
			}
		}
		else
		{
			quit('District or Pincode should not be Empty');	
		}	
		
	}
?>