<?php
	include("include/routines.php");

	$json 	= file_get_contents('php://input');
	$obj 	= json_decode($json);

	$uid 	= $_SESSION['panel_user']['id'];
	$utype	= $_SESSION['panel_user']['utype'];
	$tbl_users_owner = $_SESSION['panel_user']['tbl_users_owner'];
	
	if((isset($obj->load_cod_city)) == '1' && (isset($obj->load_cod_city)))
	{
		$response_array	= array();
		
		$start_offset   = 0;
		
		$page 			= $obj->page;	
		$per_page		= $obj->row_limit;
		$search_text	= $obj->search_text;
		
		if($page != "" && $per_page != "")	
		{
			$cur_page 		= $page;
			$page 	   	   	= $page - 1;
			$start_offset += $page * $per_page;
			$start 			= $page * $per_page;
				
			$sql_load_data  = " SELECT * FROM `tbl_cod_cities` WHERE 1=1 ";
			if(strcmp($utype,'1')!==0)
			{
				$sql_load_data  .= " AND cod_city_created_by = '".$uid."' ";
			}
			if($search_text != "")
			{
				$sql_load_data .= " AND (cod_city_city_name like '%".$search_text."%') ";	
			}
			$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
			$sql_load_data .=" ORDER BY cod_city_created_date DESC LIMIT $start, $per_page ";
			$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
			if(strcmp($data_count,"0") !== 0)
			{		
				$cod_city_data = "";	
				$cod_city_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
					$cod_city_data .= '<thead>';
						$cod_city_data .= '<tr>';
							$cod_city_data .= '<th style="text-align:center">Sr No.</th>';
							$cod_city_data .= '<th style="text-align:center">Name Of Cities</th>';
							$dis = checkFunctionalityRight("cities_for_cod.php",3);
							if($dis)
							{			
								$cod_city_data .= '<th style="text-align:center">Status</th>';						
							}
							$delete = checkFunctionalityRight("cities_for_cod.php",2);
							if($delete)
							{			
								$cod_city_data .= '<th style="text-align:center">';
									$cod_city_data .= '<div style="text-align:center">';
										$cod_city_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/>';
									$cod_city_data .= '</div>';
								$cod_city_data .= '</th>';
							}
						$cod_city_data .= '</tr>';
					$cod_city_data .= '</thead>';
					$cod_city_data .= '<tbody>';
					while($row_load_data = mysqli_fetch_array($result_load_data))
					{
						$cod_city_data .= '<tr>';				
							$cod_city_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
							$cod_city_data .= '<td style="text-align:center">'.$row_load_data['cod_city_city_name'].'</td>';
							$dis = checkFunctionalityRight("cities_for_cod.php",3);
							if($dis)
							{					
								$cod_city_data .= '<td style="text-align:center">';					
								if($row_load_data['cod_city_status'] == 1)
								{
									$cod_city_data .= '<input type="button" value="Active" id="'.$row_load_data['cod_city_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
								}
								else
								{
									$cod_city_data .= '<input type="button" value="Inactive" id="'.$row_load_data['cod_city_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
								}
								$cod_city_data .= '</td>';
							}
							$delete = checkFunctionalityRight("cities_for_cod.php",2);
							if($delete)
							{					
								$cod_city_data .= '<td>';
									$cod_city_data .= '<div class="controls" align="center">';
										$cod_city_data .= '<input type="checkbox" value="'.$row_load_data['cod_city_id'].'" id="batch'.$row_load_data['cod_city_id'].'" name="batch'.$row_load_data['cod_city_id'].'" class="css-checkbox batch">';
										$cod_city_data .= '<label for="batch'.$row_load_data['cod_city_id'].'" class="css-label"></label>';
									$cod_city_data .= '</div>';
								$cod_city_data .= '</td>';										
							}
						$cod_city_data .= '</tr>';															
					}	
					$cod_city_data .= '</tbody>';
				$cod_city_data .= '</table>';	
				$cod_city_data .= $data_count;
				$response_array = array("Success"=>"Success","resp"=>$cod_city_data);					
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
	
	if((isset($obj->change_status)) == '1' && (isset($obj->change_status)))
	{
		$response_array	= array();
		$isActiveID		= $obj->isActiveID;
		$curr_val		= $obj->curr_val;
		
		if($isActiveID != '')
		{
			// Query For updating the status
			$sql_update_cod_city_status	= " UPDATE `tbl_cod_cities` ";
			$sql_update_cod_city_status	.= " 	SET ";
			if($curr_val == 1)
			{
				$sql_update_cod_city_status	.= " `cod_city_status`='".$curr_val."', ";
			}
			else
			{
				$sql_update_cod_city_status	.= " `cod_city_status`='".$curr_val."', ";
			}
			$sql_update_cod_city_status	.= " 		`cod_city_modified_by`='".$uid."', ";
			$sql_update_cod_city_status	.= " 		`cod_city_modified_date`='".$datetime."' ";
			$sql_update_cod_city_status	.= " WHERE `cod_city_id`='".$isActiveID."' ";
			
			$res_update_cod_city_status	= mysqli_query($db_con, $sql_update_cod_city_status) or die(mysqli_error($db_con));
			
			if($res_update_cod_city_status)
			{
				$response_array	= array("Success"=>"Success", "resp"=>"Success");	
			}
			else
			{
				$response_array	= array("Success"=>"fail", "resp"=>"Oops, Something went wrong");
			}
		}
		else
		{
			$response_array	= array("Success"=>"fail", "resp"=>"Oops, Something went wrong");
		}
		
		echo json_encode($response_array);	
	}
	
	if((isset($obj->delete_cod_cities)) == "1" && isset($obj->delete_cod_cities))
	{
		$response_array 	= array();		
		$ar_cod_cities_id 	= $obj->batch;
		$del_flag 			= 0; 
		foreach($ar_cod_cities_id as $cod_city_id)	
		{
			$sql_delete_cod_city		= " DELETE FROM `tbl_cod_cities` WHERE `cod_city_id` = '".$cod_city_id."' ";
			$result_delete_cod_city		= mysqli_query($db_con,$sql_delete_cod_city) or die(mysqli_error($db_con));			
			if($result_delete_cod_city)
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
	
	if((isset($obj->insert_cod_cities)) == '1' && (isset($obj->insert_cod_cities)))
	{
		$response_array	= array();
		$cod_cities		= $obj->cod_cities;
		$flag			= 0;
		if($cod_cities != '')
		{
			for($i=0; $i<sizeof($cod_cities); $i++)
			{ 
				// Query for getting the name of the city in COD table
				$sql_chk_isExist	= " SELECT * FROM `tbl_cod_cities` WHERE `cod_city_city_id`='".$cod_cities[$i]."' ";
				$res_chk_isExist	= mysqli_query($db_con, $sql_chk_isExist) or die(mysqli_error($db_con));
				$num_chk_isExist	= mysqli_num_rows($res_chk_isExist);
				
				if($num_chk_isExist	== 0)
				{
					// Query for getting the name of the city
					$sql_get_city_name	= " SELECT city_name FROM city WHERE city_id='".$cod_cities[$i]."' ";
					$res_get_city_name	= mysqli_query($db_con, $sql_get_city_name) or die(mysqli_error($db_con));
					$num_get_city_name	= mysqli_num_rows($res_get_city_name);
					
					if($num_get_city_name != 0)
					{
						$row_get_city_name	= mysqli_fetch_array($res_get_city_name);
						
						$cod_city_name		= $row_get_city_name['city_name'];
						
						// Query for inserting the cod city entry
						$sql_insert_cod_city	= " INSERT INTO `tbl_cod_cities`(`cod_city_city_id`, `cod_city_city_name`, `cod_city_status`, ";
						$sql_insert_cod_city	.= " `cod_city_created_by`, `cod_city_created_date`) ";
						$sql_insert_cod_city	.= " VALUES ('".$cod_cities[$i]."', '".$cod_city_name."', '1', ";
						$sql_insert_cod_city	.= " '".$uid."', '".$datetime."') ";
						$res_insert_cod_city	= mysqli_query($db_con, $sql_insert_cod_city) or die(mysqli_error($db_con));
						if($res_insert_cod_city)
						{
							$flag	= 1;	
						}
						else
						{
							$flag	= 0;
						}
					}
					else
					{
						$response_array	= array("Success"=>"fail", "resp"=>"Oops, Something went wrong");
					}
				}
				else
				{
					$response_array	= array("Success"=>"duplicate", "resp"=>"City already exist");	
					echo json_encode($response_array);	
					exit();
				}
			}
			if($flag == 1)
			{
				$response_array	= array("Success"=>"Success", "resp"=>$flag);	
			}
			else
			{
				$response_array	= array("Success"=>"fail", "resp"=>$flag);
			}
		}
		else
		{
			$response_array	= array("Success"=>"fail", "resp"=>"Oops, Something went wrong");	
		}
		
		echo json_encode($response_array);	
	}
?>