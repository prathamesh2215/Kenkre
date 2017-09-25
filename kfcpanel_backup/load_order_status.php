<?php
include("include/routines.php");
$json 	= file_get_contents('php://input');
$obj 	= json_decode($json);
$uid	= $_SESSION['panel_user']['id'];
$utype	= $_SESSION['panel_user']['utype'];

function insertOrderStatus($orstat_name,$orstat_status,$response_array)
{
	global $db_con, $datetime;
	global $uid;
	global $obj;
	$sql_check_order_status 	 = " select * from tbl_order_status where orstat_name like '".$orstat_name."' "; 
	$result_check_order_status 	 = mysqli_query($db_con,$sql_check_order_status) or die(mysqli_error($db_con));
	$num_rows_check_order_status = mysqli_num_rows($result_check_order_status);
	if($num_rows_check_order_status == 0)
	{
		$sql_last_rec = "Select * from tbl_order_status order by orstat_id desc LIMIT 0,1";
		$result_last_rec = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec = mysqli_num_rows($result_last_rec);
		if($num_rows_last_rec == 0)
		{
			$orstat_id 		= 1;				
		}
		else
		{
			$row_last_rec = mysqli_fetch_array($result_last_rec);				
			$orstat_id		= $row_last_rec['orstat_id']+1;
		}			
		
		$sql_total_num_rows		= " SELECT * from tbl_order_status ";
		$result_total_num_rows	=  mysqli_query($db_con,$sql_total_num_rows) or die(mysqli_error($db_con));
		$count_total_num_rows	= mysqli_num_rows($result_total_num_rows);
		
		$sql_insert_order_status 	= " INSERT INTO `tbl_order_status`(`orstat_id`,`orstat_name`,`orstat_sort_order`,`orstat_created_by`, `orstat_created`,`orstat_status`) VALUES ('".$orstat_id."','".$orstat_name."','".($count_total_num_rows+1)."','".$uid."','".$datetime."','".$orstat_status."') ";
		$result_insert_order_status = mysqli_query($db_con,$sql_insert_order_status) or die(mysqli_error($db_con));
		if($result_insert_order_status)
		{
			
				$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully");					
							
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
		}			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Order Name <b>".ucwords($orstat_name)."</b> already Exist");
	}	
	return $response_array;
}

if((isset($obj->insert_order)) == "1" && isset($obj->insert_order))
{
	$orstat_name		= strtolower(mysqli_real_escape_string($db_con,$obj->orstat_name));
	$orstat_status		= $obj->orstat_status;
	$response_array 	= array();	
	if($orstat_name != "" && $orstat_status != "")
	{
		$response_array = insertOrderStatus($orstat_name,$orstat_status,$response_array);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->update_order_status)) == "1" && isset($obj->update_order_status))
{
	$orstat_id			= $obj->orstat_id;
	$orstat_name		= strtolower(mysqli_real_escape_string($db_con,$obj->orstat_name));
	$orstat_status		= $obj->orstat_status;
	$response_array 	= array();
	if($orstat_id != "" && $orstat_name != "" && $orstat_status != "")
	{
		$sql_check_order_status 		= " select * from tbl_order_status where orstat_name like '".$orstat_name."' and `orstat_id` != '".$orstat_id."' "; 
		$result_check_order_status 		= mysqli_query($db_con,$sql_check_order_status) or die(mysqli_error($db_con));
		$num_rows_check_order_status 	= mysqli_num_rows($result_check_order_status);
		if($num_rows_check_order_status == 0)
		{		
			$sql_update_order_status 	= " UPDATE `tbl_order_status` SET `orstat_name`='".$orstat_name."',`orstat_status`='".$orstat_status."',";
			$sql_update_order_status  .= " `orstat_modified`='".$datetime."',`orstat_modified_by`='".$uid."' WHERE `orstat_id` = '".$orstat_id."' ";		
			$result_update_order_status = mysqli_query($db_con,$sql_update_order_status) or die(mysqli_error($db_con));
			if($result_update_order_status)
			{
				$response_array = array("Success"=>"Success","resp"=>"Update Succesfull");			
			}
			else
			{
			$response_array 	= array("Success"=>"fail","resp"=>"Record Not Inserted.");					
			}					
		}
		else
		{
			$response_array 	= array("Success"=>"fail","resp"=>"Order Status ".$orstat_name." already Exist");			
		}
	}
	else
	{
		$response_array 		= array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->load_order_parts)) == "1" && isset($obj->load_order_parts))
{
	$orstat_id = $obj->orstat_id;
	$req_type = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($orstat_id != "" && $req_type == "edit")
		{
			$sql_order_status_data 	= "Select * from tbl_order_status where orstat_id = '".$orstat_id."' ";
			$result_order_status_data 	= mysqli_query($db_con,$sql_order_status_data) or die(mysqli_error($db_con));
			$row_order_status_data		= mysqli_fetch_array($result_order_status_data);		
		}	
		else if($orstat_id != "" && $req_type == "view")
		{
			$sql_order_status_data 	= "Select * from tbl_order_status where orstat_id = '".$orstat_id."' ";
			$result_order_status_data 	= mysqli_query($db_con,$sql_order_status_data) or die(mysqli_error($db_con));
			$row_order_status_data		= mysqli_fetch_array($result_order_status_data);		
		}			
		$data = '';
		if($orstat_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="orstat_id" value="'.$row_order_status_data['orstat_id'].'">';
		}	                                                         		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Order Status Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="orstat_name" name="orstat_name" class="input-large" data-rule-required="true" ';
		if($orstat_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_order_status_data['orstat_name']).'"'; 
		}
		elseif($orstat_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_order_status_data['orstat_name']).'" disabled';
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Order Status Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($orstat_id != "" && $req_type == "view")
		{
			if($row_order_status_data['orstat_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_order_status_data['orstat_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="orstat_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_order_status.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_order_status_data['orstat_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="orstat_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_order_status_data['orstat_status'] == 0)
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
		if($orstat_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($orstat_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
		}			
		$data .= '</div> <!-- Save and cancel -->';		
		$response_array = array("Success"=>"Success","resp"=>utf8_encode($data));
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Type Not Defined");		
	}
	echo json_encode($response_array);
}

if((isset($obj->load_order)) == "1" && isset($obj->load_order))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= $obj->search_text;	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT *,(SELECT `fullname` FROM `tbl_cadmin_users` WHERE `id` = orstat_modified_by) as modified_user_name,(SELECT `fullname` FROM `tbl_cadmin_users` WHERE `id` = orstat_created_by) as created_user_name from tbl_order_status WHERE 1=1 ";
		
		if($search_text != "")
		{
			$sql_load_data .= " AND (orstat_id like '%".$search_text."%' or orstat_name like '%".$search_text."%' or orstat_created like '%".$search_text."%' or  orstat_modified like '%".$search_text."%')";
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY orstat_sort_order ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
		if($result_load_data)		
		{
			if(strcmp($data_count,"0") !== 0)
			{		
				$order_data = "";	
				$order_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 		$order_data .= '<thead>';
    	  		$order_data .= '<tr>';
         		$order_data .= '<th style="text-align:center">Sr. No.</th>';
				$order_data .= '<th style="text-align:center">Order Status Id</th>';
				$order_data .= '<th style="text-align:center">Order Status Name</th>';
				$order_data .= '<th style="width:6%;text-align:center">Sort Order</th>';
				$order_data .= '<th style="text-align:center">Created</th>';
				$order_data .= '<th style="text-align:center">Created By</th>';
				$order_data .= '<th style="text-align:center">Modified </th>';
				$order_data .= '<th style="text-align:center">Modified By</th>';	
				$dis = checkFunctionalityRight("view_order_status.php",3);
				if($dis)
				{					
					$order_data .= '<th style="text-align:center">Status</th>';											
				}
				$edit = checkFunctionalityRight("view_order_status.php",1);
				if($edit)
				{					
					$order_data .= '<th style="text-align:center">Edit</th>';			
				}	
				$delete = checkFunctionalityRight("view_order_status.php",2);
				if($delete)
				{					
					$order_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
				}					
          		$order_data .= '</tr>';
      			$order_data .= '</thead>';
      			$order_data .= '<tbody>';
				while($row_load_data = mysqli_fetch_array($result_load_data))
				{
	    		  	$order_data .= '<tr>';				
					$order_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
					$order_data .= '<td style="text-align:center">'.$row_load_data['orstat_id'].'</td>';
					$order_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['orstat_name']).'" class="btn-link" id="'.$row_load_data['orstat_id'].'" onclick="addMoreOrderStatus(this.id,\'view\');"></td>';
					$order_data .= '<td style="text-align:center">';
					$order_data .= '<input type="text" style="text-align:center;width:50%" onblur="changeOrder(this.id,this.value)" id="'.$row_load_data['orstat_id'].'" value="'.$row_load_data['orstat_sort_order'].'">';
				    $order_data .= '</td>';
					$order_data .= '<td style="text-align:center">'.$row_load_data['orstat_created'].'</td>';
					$order_data .= '<td style="text-align:center">'.$row_load_data['created_user_name'].'</td>';
					if( $row_load_data['orstat_modified'] == "0000-00-00 00:00:00")
					{
						$order_data .= '<td style="text-align:center;color:red">Not Available</td>';
					}
					else
					{
						$order_data .= '<td style="text-align:center">'.$row_load_data['orstat_modified'].'</td>';
					}
					if( $row_load_data['orstat_modified_by'] == "0")
					{
						$order_data .= '<td style="text-align:center;color:red">Not Available</td>';
					}
					else
					{
						$order_data .= '<td style="text-align:center">'.$row_load_data['modified_user_name'].'</td>';
					}
					
					$dis = checkFunctionalityRight("view_order_status.php",3);
					if($dis)
					{					
						$order_data .= '<td style="text-align:center">';	
						if($row_load_data['orstat_status'] == 1)
						{
							$order_data .= '<input type="button" value="Active" id="'.$row_load_data['orstat_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
						}
						else
						{
							$order_data .= '<input type="button" value="Inactive" id="'.$row_load_data['orstat_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
						}
						$order_data .= '</td>';	
					}
					$edit = checkFunctionalityRight("view_order_status.php",1);
					if($edit)
					{						
						$order_data .= '<td style="text-align:center">';
						$order_data .= '<input type="button" value="Edit" id="'.$row_load_data['orstat_id'].'" class="btn-warning" onclick="addMoreOrderStatus(this.id,\'edit\');"></td>';						
					}
					$delete = checkFunctionalityRight("view_order_status.php",2);
					if($delete)
					{					
						$order_data .= '<td><div class="controls" align="center">';
						$order_data .= '<input type="checkbox"  id="batch'.$row_load_data['orstat_id'].'" name="batch'.$row_load_data['orstat_id'].'" value="'.$row_load_data['orstat_id'].'"  class="css-checkbox batch">';
						$order_data .= '<label for="batch'.$row_load_data['orstat_id'].'" class="css-label" style="color:#FFF"></label>';
						$order_data .= '</div></td>';										
					}
	        	  	$order_data .= '</tr>';															
				}	
      			$order_data .= '</tbody>';
      			$order_data .= '</table>';	
				$order_data .= $data_count;
				$response_array = array("Success"=>"Success","resp"=>utf8_encode($order_data));
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"No Data Available");
			}			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Data not available");
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
	$orstat_id				= $obj->orstat_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();		
	$sql_update_status 		= " UPDATE `tbl_order_status` SET `orstat_status`= '".$curr_status."' ,`orstat_modified` = '".$datetime."' ,`orstat_modified_by` = '".$uid."' WHERE `orstat_id` like '".$orstat_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	if($result_update_status)
	{
		$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Update Failed.");
	}		
	echo json_encode($response_array);	
}

if((isset($obj->delete_order_status)) == "1" && isset($obj->delete_order_status))
{
	$response_array = array();		
	$ar_order_status_id 	= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_order_status_id as $orstat_id)	
	{
		$sql_delete_order_status		= " DELETE FROM `tbl_order_status` WHERE `orstat_id` = '".$orstat_id."' ";
		$result_delete_order_status	= mysqli_query($db_con,$sql_delete_order_status) or die(mysqli_error($db_con));			
		if($result_delete_order_status)
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
if((isset($obj->change_sort_order)) == "1" && isset($obj->change_sort_order))
{
	$orstat_id				= $obj->orstat_id;
	$new_sort_order 		= $obj->new_order;
	
	$sql_total_num_rows		= " SELECT * from tbl_order_status ";
	$result_total_num_rows	=  mysqli_query($db_con,$sql_total_num_rows) or die(mysqli_error($db_con));
	$count_total_num_rows	= mysqli_num_rows($result_total_num_rows);
	
	if($new_sort_order <= $count_total_num_rows)
	{
		$sql_get_current_sort_order 	=" SELECT * from tbl_order_status  where orstat_id ='".$orstat_id."' ";
		$result_get_current_sort_order  =mysqli_query($db_con,$sql_get_current_sort_order) or die(mysqli_error($db_con));
		$row_current_sort_order			=mysqli_fetch_array ($result_get_current_sort_order);
		$current_sort_order				=$row_current_sort_order['orstat_sort_order'];
		
		$sql_get_exchange_id 			=" SELECT * from tbl_order_status  where orstat_sort_order ='".$new_sort_order."' ";
		$result_get_exchange_id  		=mysqli_query($db_con,$sql_get_exchange_id) or die(mysqli_error($db_con));
		$row_exchange_id				=mysqli_fetch_array ($result_get_exchange_id);
		$exchange_id					=$row_exchange_id['orstat_id'];
			
		$sql_update_new_sort_order		=" UPDATE tbl_order_status SET orstat_sort_order ='".$new_sort_order."',orstat_modified = '".$datetime."', orstat_modified_by ='".$uid."' WHERE orstat_id ='".$orstat_id."' ";
		$result_update_new_sort_order	=  mysqli_query($db_con,$sql_update_new_sort_order) or die(mysqli_error($db_con));
		$row_update_new_sort_order		=  mysqli_fetch_array($result_update_new_sort_order);
		
		$sql_update_previous_sort_order		=" UPDATE tbl_order_status SET orstat_sort_order ='".$current_sort_order."',orstat_modified = '".$datetime."', orstat_modified_by ='".$uid."' WHERE orstat_id ='".$exchange_id."' ";
		$result_update_previous_sort_order	=  mysqli_query($db_con,$sql_update_previous_sort_order) or die(mysqli_error($db_con));
		$row_update_previous_sort_order		=  mysqli_fetch_array($result_update_previous_sort_order);		
		
		if( $result_update_new_sort_order && $result_update_previous_sort_order)
		{
			$response_array = array("Success"=>"Success","resp"=>"Sort Updated Successfully.");	
		}
		else
		{
			$response_array = array("Success"=>"Success","resp"=>"Sort Updation Failed.");	
		}
		
	}
	else
	{
			$response_array = array("Success"=>"Success","resp"=>"Sort Order exceeds LIMIT ! ");	
	}
	
	echo json_encode($response_array);	
}
?>