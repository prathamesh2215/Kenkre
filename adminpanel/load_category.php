<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


//------------------this is used for inserting records---------------------
if((isset($_POST['insert_category'])) == "1" && isset($_POST['insert_category']))
{
	
	$data['cat_name']      = mysqli_real_escape_string($db_con,$_POST['cat_name']);
	$data['cat_fee']       = mysqli_real_escape_string($db_con,$_POST['cat_fee']);
	$data['cat_status']    = mysqli_real_escape_string($db_con,$_POST['cat_status']);
	
	
	$data['cat_created']   = $datetime;
	$data['cat_created_by'] = $logged_uid;
	
	if($data['cat_name']=="" || $data['cat_fee']=="" || $data['cat_status']=="")
	{
		quit('All fileds are required...!');
	}
	
	$sql_check =" SELECT * FROM tbl_category WHERE (cat_name ='".$data['cat_name']."') ";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		insert('tbl_category',$data);
		quit('Category Added Successfully...!',1);
	}
	else
	{
		quit('Category Name already Exist...!');
	}
}

//------------------this is used for update records---------------------
if((isset($_POST['update_category'])) == "1" && isset($_POST['update_category']))
{	
    $data['cat_name']     	  = mysqli_real_escape_string($db_con,$_POST['cat_name']);
	$data['cat_fee']          = mysqli_real_escape_string($db_con,$_POST['cat_fee']);
	$data['cat_status']  	  = mysqli_real_escape_string($db_con,$_POST['cat_status']);
	
	$category_id              = mysqli_real_escape_string($db_con,$_POST['category_id']);

	$data['cat_modified']     = $datetime;
	$data['cat_modified_by']  = $logged_uid;
		
	if($data['cat_name']=="" || $data['cat_fee']=="" || $data['cat_status']=="")
	{
		quit('All fileds are required...!');
	}
	
	$sql_check =" SELECT * FROM tbl_category WHERE cat_name ='".$data['cat_name']."' AND cat_id !='".$category_id."'";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		update('tbl_category',$data,array('cat_id'=>$category_id));
		quit('Category Updated Successfully...!',1);
	}
	else
	{
		quit('Category Name already Exist...!'.$sql_check);
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
			
		$sql_load_data  = " SELECT * FROM `tbl_category` AS tc WHERE 1=1";
		
		if($search_text != "")
		{
			$sql_load_data .= " and (cat_id like '%".$search_text."%' or cat_name like '%".$search_text."%' ";
			$sql_load_data .= " or city_created like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " ORDER BY cat_name  ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Category ID</th>';
			$area_data .= '<th style="text-align:center">Category Name</th>';
			$area_data .= '<th style="text-align:center">Category  Fee</th>';
			$dis = checkFunctionalityRight("view_category.php",3);
		
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_category.php",1);
			
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_category.php",2);
			
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
				$area_data .= '<td style="text-align:center">'.$row_load_data['cat_id'].'</td>';
				
				$area_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['cat_name']).'" class="btn-link" id="'.$row_load_data['cat_id'].'" onclick="addMoreArea(this.id,\'view\');"></td>';
				$area_data .= '<td style="text-align:center">'.$row_load_data['cat_fee'].'</td>';
				
				$dis = checkFunctionalityRight("view_category.php",3);
				
				if($dis)
				{					
					$area_data .= '<td style="text-align:center">';					
					if($row_load_data['cat_status']!='0')
					{
						$area_data .= '<input type="button" value="Active" id="'.$row_load_data['cat_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['cat_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$area_data .= '</td>';
				}
				$edit = checkFunctionalityRight("view_category.php",1);
			
				if($edit)
				{				
					$area_data .= '<td style="text-align:center">';
					$area_data .= '<input type="button" value="Edit" id="'.$row_load_data['cat_id'].'" class="btn-warning" onclick="addMoreArea(this.id,\'edit\');"></td>';												
				}
				$delete = checkFunctionalityRight("view_category.php",2);
				
				if($delete)
				{			
				
					if($row_load_data['cat_status']==0)
					{
						$area_data .= '<td style="text-align:center">';					
					    $area_data .= '<input type="button" value="Inactive" id="'.$row_load_data['cat_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					    $area_data .= '</td>';
					}
					else
					{
						$area_data .= '<td><div class="controls" align="center">';
						$area_data .= '<input type="checkbox" value="'.$row_load_data['cat_id'].'" id="batch'.$row_load_data['cat_id'].'" name="batch'.$row_load_data['cat_id'].'" class="css-checkbox batch">';
						$area_data .= '<label for="batch'.$row_load_data['cat_id'].'" class="css-label"></label>';
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
	$cat_id     = $obj->area_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($cat_id != "" && $req_type == "edit")
		{
			$sql_area_data 	    = "Select * from tbl_category where cat_id = '".$cat_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}	
		else if($cat_id != "" && $req_type == "view")
		{
			$sql_area_data 	    = "Select * from tbl_category where cat_id = '".$cat_id."' ";
			$result_area_data 	= mysqli_query($db_con,$sql_area_data) or die(mysqli_error($db_con));
			$row_area_data		= mysqli_fetch_array($result_area_data);		
		}			
		$data = '';
		if($cat_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="category_id" name="category_id" value="'.$row_area_data['cat_id'].'">';
			$data .= '<input type="hidden" name="update_category" id="update_category" value="1">';
		}  
		
		
		if($req_type == "add")
		{
			$data .= '<input type="hidden" name="insert_category" id="insert_category" value="1">';
		} 
		 
		//====================Start : Category Name =================================================                                                      		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Category Name
		<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return charsonly(event);" type="text" id="cat_name" name="cat_name" ';
		$data .= 'class="input-large keyup-char" data-rule-required="true" placeholder="Category Name"  value="'.@$row_area_data['cat_name'].'"/><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Category Name -->';
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Category Fee
		<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input onkeypress="return nusOnly(event);" type="text" id="cat_fee" name="cat_fee" ';
		$data .= 'class="input-large keyup-char" data-rule-required="true" placeholder="Category Fee"  value="'.@$row_area_data['cat_fee'].'"/><br>';
		$data .= '</div>';
		$data .= '</div> <!-- Category Fee -->';
		
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		if($cat_id != "" && $req_type == "view")
		{
			if($row_area_data['cat_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_area_data['cat_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}
		else
		{  
          if($cat_id != "" && $req_type == "edit")
		  {
				$data  .= '<input type="radio" name="cat_status" value="1" class="css-radio" data-rule-required="true" ';
				$dis	= checkFunctionalityRight("view_category.php",3);
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['cat_status'] == 1)
				{
					$data .= 'checked ';
				}
				$data .= '> Active ';
				$data .= '<input type="radio" name="cat_status" value="0" class="css-radio" data-rule-required="true"';
				if(!$dis)
				{
				//$data .= ' disabled="disabled" ';
				}
				if($row_area_data['cat_status'] == 0  )
				{
					$data .= 'checked ';
				}
				$data .= '> Inactive ';
			} 
			else  
			{
				$data .= '<input type="radio" name="cat_status" value="1" class="css-radio" data-rule-required="true" ';
				$data .= '> Active ';
				$data .= ' <input type="radio" name="cat_status" value="0" class="css-radio" data-rule-required="true"';
			
		 		$data .= '> Inactive';
			}
		}					
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';
		
		$data .= '<div class="form-actions">';
		if($cat_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Category</button>';			
		}
		elseif($cat_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Category</button>';			
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
	$cat_id				 = $obj->cat_id;
	$curr_status		 = $obj->curr_status;
	$response_array 	 = array();	
	
	$sql_update_status 		= " UPDATE `tbl_category` SET `cat_status`= '".$curr_status."' WHERE `cat_id`='".$cat_id."' ";
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

//---------------This is used for status change--------------------------------
if((isset($obj->delete_category)) == "1" && isset($obj->delete_category))
{
	$cat_id				 = $obj->cat_id;
	$curr_status		 = $obj->curr_status;
	$response_array 	 = array();	
	
	$sql_update_status 		= " UPDATE `tbl_category` SET `cat_status`= '0' WHERE `cat_id`='".$cat_id."' ";
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
?>
