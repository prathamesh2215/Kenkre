<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

function insertArea($area_name,$area_direction,$area_pincode,$area_status,$response_array)
{
	global $obj;
	global $db_con, $datetime;
	global $uid;
	$sql_check_area 	 = " select * from tbl_area where area_name = '".$area_name."' "; 
	$result_check_area 	 = mysqli_query($db_con,$sql_check_area) or die(mysqli_error($db_con));
	$num_rows_check_area = mysqli_num_rows($result_check_area);
	
	$sql_last_rec      = "Select * from tbl_area order by area_id desc LIMIT 0,1";
	$result_last_rec   = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
	$num_rows_last_rec = mysqli_num_rows($result_last_rec);
	
	if($num_rows_last_rec == 0)
	{
		$area_id 		= 1;				
	}
	else
	{
		$row_last_rec = mysqli_fetch_array($result_last_rec);				
		$area_id 	  = $row_last_rec['area_id']+1;
	}
	$sql_insert_area    = " INSERT INTO `tbl_area`(`area_id`, `area_name`, `area_direction`, `area_pincode`, `area_created`, `area_created_by`,`area_status`) ";
	$sql_insert_area   .= "VALUES ('".$area_id."', '".$area_name."', '".$area_direction."', '".$area_pincode."', '".$datetime."', '".$uid."', '".$area_status."')";			
	$result_insert_area = mysqli_query($db_con,$sql_insert_area) or die(mysqli_error($db_con));
	if($result_insert_area)
	{
		$response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully");					
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Not Inserted.");					
	}			
	return $response_array;
}

//------------------this is used for inserting records---------------------
if((isset($_POST['insert_country'])) == "1" && isset($_POST['insert_country']))
{
	
	$data['country_id']    = mysqli_real_escape_string($db_con,$_POST['country_id']);
	$data['country_name']  = mysqli_real_escape_string($db_con,$_POST['country_name']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['country_name']);
	
	$data['created_date']  = $datetime;
	$data['created_by']    = $logged_uid;
	
	if($data['country_id']=="" || $data['country_name']=="" || $data['status']=="")
	{
		quit('All fileds are required...!');
	}
	
	$sql_check =" SELECT * FROM tbl_country WHERE (country_id ='".$data['country_id']."' or country_name ='".$data['country_name']."') ";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		insert('tbl_country',$data);
		quit('Country Added Successfully...!',1);
	}
	else
	{
		quit('Country Id or Country Name already Exist...!');
	}
}

//------------------this is used for update records---------------------
if((isset($_POST['update_country'])) == "1" && isset($_POST['update_country']))
{	
	$data['country_id']    = mysqli_real_escape_string($db_con,$_POST['country_id']);
	$data['country_name']  = mysqli_real_escape_string($db_con,$_POST['country_name']);
	$data['status']        = mysqli_real_escape_string($db_con,$_POST['country_name']);
	
	$id                    = mysqli_real_escape_string($db_con,$_POST['id']);

	$data['modified_date']  = $datetime;
	$data['modified_by']    = $logged_uid;
		
	if($data['country_id']=="" || $data['country_name']=="" || $data['status']=="")
	{
		quit('All fileds are required...!');
	}
	
	$sql_check =" SELECT * FROM tbl_country WHERE (country_id ='".$data['country_id']."' or country_name ='".$data['country_name']."') AND id !='".$id."'";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		update('tbl_country',$data,array('id'=>$id));
		quit('Country Updated Successfully...!',1);
	}
	else
	{
		quit('Country Id or Country Name already Exist...!');
	}

}

//----------------------This is used for viewing records---------------------------------
if((isset($obj->load_country)) == "1" && isset($obj->load_country))
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
			
		$sql_load_data  = " SELECT * FROM `tbl_country` AS tc WHERE 1=1";
		
		if($search_text != "")
		{
			$sql_load_data .= " and (country_id like '%".$search_text."%' or country_name = '".$search_text."' ";
			$sql_load_data .= " or id = '".$search_text."') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY country_name ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Country ID</th>';
			$area_data .= '<th style="text-align:center">Country Name</th>';
			$dis = checkFunctionalityRight("view_country.php",3);
		
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_country.php",1);
			
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_country.php",2);
			
			if($delete)
			{			
				$area_data .= '<th style="text-align:center"><div style="text-align:center">';
				$area_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$area_data .= '</tr>';
      		$area_data .= '</thead>';
      		$area_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$area_data .= '<tr>';				
				$area_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$area_data .= '<td style="text-align:center">'.$row_load_data['country_id'].'</td>';
				
				$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['country_name']).'" class="btn-link" id="'.$row_load_data['id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				
				
				$dis = checkFunctionalityRight("view_country.php",3);
				
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['status']!='0')
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_country.php",1);
			
				if($edit)
				{		
						$area_data .= '<td style="text-align:center">';
					    $area_data .= '<input type="button" value="Edit" id="'.$row_load_data['id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';	
																
				}
				$delete = checkFunctionalityRight("view_country.php",2);
				
				if($delete)
				{					
					
					
				    if($row_load_data['status']==0)
					{
						$area_data .= '<td style="text-align:center">';	
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
						$area_data .= '</td>';
					}
					else
					{
						$area_data .= '<td><div class="controls" align="center">';
					$area_data .= '<input type="checkbox" value="'.$row_load_data['id'].'" id="batch'.$row_load_data['id'].'" name="batch'.$row_load_data['id'].'" class="css-checkbox batch">';
					$area_data .= '<label for="batch'.$row_load_data['id'].'" class="css-label"></label>';
					$area_data .= '</div></td>';	
					}
					
					
														
				}
	          	$area_data .= '</tr>';															
			}	
      		$area_data .= '</tbody>';
      		$area_data .= '</table>';	
			$area_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$area_data);				
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

if((isset($obj->load_area_parts)) == "1" && isset($obj->load_area_parts))
{
	$country_id        = $obj->area_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($country_id != "" && $req_type == "edit")
		{
			$sql_area_data 	    = "Select * from tbl_country where id = '".$country_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}	
		else if($country_id != "" && $req_type == "view")
		{
			$sql_area_data 	    = "Select * from tbl_country where id = '".$country_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}			
		$data = '';
		if($country_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="id" name="id" value="'.$row_area_data['id'].'">';
			$data .= '<input type="hidden" name="update_country" id="update_country" value="1">';
		}  
		
		if($req_type == "add")
		{
			$data .= '<input type="hidden" name="insert_country" id="insert_country" value="1">';
		} 
		                                                       		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Country Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return charsonly(event);" type="text" id="country_name" name="country_name" class="input-large keyup-char" data-rule-required="true" placeholder="Country Name" ';
		if($country_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_area_data['country_name'].'"'; 
		}
		elseif($country_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_area_data['country_name'].'" disabled'; 				
		}
		$data .= '/><br>';
		
						
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		
		$data .= '</div>';
		$data .= '</div> <!-- country_name Name -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Country Code<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return charsonly(event);" type="text" placeholder="Country ID" id="country_id" name="country_id"  class="input-large keyup-char" data-rule-required="true" ';
		if($country_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_area_data['country_id'].'"'; 
		}
		elseif($country_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_area_data['country_id'].'" disabled'; 				
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Pincode -->';

		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		if($country_id != "" && $req_type == "view")
		{
			if($row_area_data['status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_area_data['status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}
		else
		{  
          if($country_id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="country_status" value="1" class="css-radio" data-rule-required="true" ';
				$dis	= checkFunctionalityRight("view_country.php",3);
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['status'] == 1)
				{
					$data .= 'checked ';
				}
				$data .= '>Active';
				$data .= '<input type="radio" name="country_status" value="0" class="css-radio" data-rule-required="true"';
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['status'] == 0  )
				{
					$data .= 'checked ';
				}
				$data .= '>Inactive';
			} 
			else  
			{
				$data .= '<input type="radio" name="country_status" value="1" class="css-radio" data-rule-required="true" ';
				$data .= '> Active ';
				$data .= ' <input type="radio" name="country_status" value="0" class="css-radio" data-rule-required="true"';
			
		 		$data .= '> Inactive';
			}
		}					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		
		$data .= '<div class="form-actions">';
		if($country_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Country</button>';			
		}
		elseif($country_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Country</button>';			
		}			
		$data .= '</div> <!-- Save and cancel -->';	
			
		$response_array = array("Success"=>"Success","resp"=>$data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Type Not Defined");		
	}
	echo json_encode($response_array);
}


//---------------This is used for status change--------------------------------
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$area_id				= $obj->area_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$sql_update_status 		= " UPDATE `tbl_country` SET `status`= '".$curr_status."' WHERE `id`='".$area_id."' ";
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

//------------------This is used for delete data---------------------------------
if((isset($obj->delete_area)) == "1" && isset($obj->delete_area))
{
	$response_array   = array();		
	$ar_area_id 	  = $obj->batch;
	$del_flag 		  = 0; 
	foreach($ar_area_id as $area_id)	
	{
		/*$sql_delete_area	= " DELETE FROM `tbl_country` WHERE `id` = '".$area_id."' ";
		$result_delete_area	= mysqli_query($db_con,$sql_delete_area) or die(mysqli_error($db_con));		*/
		$sql_update_status 		= " UPDATE `tbl_country` SET `status`= '0' WHERE `id`='".$area_id."' ";
	    $result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));	
		if($result_update_status)
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

// ==========================================================================

?>
