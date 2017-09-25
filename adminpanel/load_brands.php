<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
$utype				= $_SESSION['panel_user']['utype'];
$uid				= $_SESSION['panel_user']['id'];

function insertBrand($brand_name,$brand_description, $brand_owner,$brand_meta_title,$brand_meta_description,$brand_meta_tags,$brand_status,$response_array,$brand_image)
{
	global $uid;
	global $db_con, $datetime;
	global $obj;
	$sql_check_brand 		= " select * from tbl_brands_master where brand_name like '".$brand_name."' "; 
	$result_check_brand 	= mysqli_query($db_con,$sql_check_brand) or die(mysqli_error($db_con));
	$num_rows_check_brand = mysqli_num_rows($result_check_brand);
	if($num_rows_check_brand == 0)
	{
		$brand_slug 			= getSlug(strtolower(str_replace(" ","-",$brand_name)));
		
		$sql_insert_brand = " INSERT INTO `tbl_brands_master`(`brand_name`, `brand_description`, `brand_owner`,`brand_slug`, `brand_meta_tags`, `brand_meta_description`, `brand_meta_title`, ";
		$sql_insert_brand .= " `brand_created_by`, `brand_created`,`brand_status`) VALUES ('".$brand_name."','".$brand_description."',";
		$sql_insert_brand .= " '".$brand_owner."','".$brand_slug."','".$brand_meta_tags."','".$brand_meta_description."','".$brand_meta_title."','".$uid."','".$datetime."','".$brand_status."') ";
		$result_insert_brand = mysqli_query($db_con,$sql_insert_brand) or die(mysqli_error($db_con));
		if($result_insert_brand)
		{
			if(sizeof($brand_image) != 0)
			{
				$brand_image_name		= $brand_image[0];
				$brand_image_tmp		= $brand_image[1];
				$brand_path				= "../images/brands/brand_".$brand_id;
				if(is_dir($brand_path) === false)
				{
					mkdir($brand_path);
				}
				if(move_uploaded_file($brand_image_tmp,$brand_path."/".$brand_image_name))
				{
					$sql_update_brand_image 	= "  UPDATE `tbl_brands_master` SET `brand_image`= '".$brand_image_name."' WHERE `brand_id` = '".$brand_id."' ";
					$result_update_brand_image 	= mysqli_query($db_con,$sql_update_brand_image) or die(mysqli_error($db_con));
					if($result_update_brand_image)
					{
						$response_array = array("Success"=>"Success","resp"=>"Brand Image Uploaded and Updated in System.");
					}
					else
					{
						$response_array = array("Success"=>"Success","resp"=>"Brand Image Uploaded but not Updated in System.Contact Admin");
					}
				}
				else
				{
					$response_array = array("Success"=>"Success","resp"=>"Brand Image Not Uploaded.");
				}
			}
			else
			{
				$response_array = array("Success"=>"Success","resp"=>" NO Image Data Inserted Successfully");									
			}			
			if(isset($obj->error_id) && (isset($obj->insert_req)) != "")			
			{
				$sql_delete_error_brand = "DELETE FROM `tbl_error_data` WHERE `error_id`='".$obj->error_id."'";
				$res_delete_error_brand = mysqli_query($db_con, $sql_delete_error_brand) or die(mysqli_error($db_con));				
				if($res_delete_error_brand)
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
		$response_array = array("Success"=>"fail","resp"=>"Brands <b>".ucwords($brand_name)."</b> already Exist");
	}	
	return $response_array;
}
if((isset($_POST['insert_brand'])) == "1" && isset($_POST['insert_brand']))
{
	$brand_name				= strtolower(mysqli_real_escape_string($db_con,$_POST['brand_name']));
	$brand_description		= mysqli_real_escape_string($db_con,$_POST['brand_description']);	
	$brand_owner			= strtolower(mysqli_real_escape_string($db_con,$_POST['brand_owner']));
	$brand_status			= mysqli_real_escape_string($db_con,$_POST['brand_status']);
	$brand_meta_description	= mysqli_real_escape_string($db_con,$_POST['brand_meta_description']);
	$brand_meta_title		= mysqli_real_escape_string($db_con,$_POST['brand_meta_title']);
	$brand_meta_tags		= mysqli_real_escape_string($db_con,$_POST['brand_meta_tags']);
	$response_array = array();
	if($brand_name != "" && $brand_owner != "" && $brand_status != "")
	{
		$brand_image 		= array();
		if(isset($_FILES['brand_image']['name']) && trim($_FILES['brand_image']['name']) != "" )
		{			
			$brand_image_name		= trim($_FILES["brand_image"]["name"]);
			$brand_image_tmp		= trim($_FILES["brand_image"]["tmp_name"]);
			$brand_image_file_size	= trim($_FILES['brand_image']['size']);
			$brand_image_extension	= getExtension($brand_image_name);
			if($brand_image_name != "" && $brand_image_tmp != "" && $brand_image_extension == "png")
			{
				array_push($brand_image,$brand_image_name,$brand_image_tmp);
				$response_array = insertBrand($brand_name,$brand_description,$brand_owner,$brand_meta_title,$brand_meta_description,$brand_meta_tags,$brand_status,$response_array,$brand_image);				
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Please Upload PNG Image Only.");
			}
		}
		else
		{
			$response_array = insertBrand($brand_name,$brand_description,$brand_owner,$brand_meta_title,$brand_meta_description,$brand_meta_tags,$brand_status,$response_array,$brand_image);			
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->load_brand_parts)) == "1" && isset($obj->load_brand_parts))
{
	$brand_id = $obj->brand_id;
	$req_type = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($brand_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$brand_id."' "; // this brand_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_brand_data		= json_decode($row_error_data['error_data']);
		}
		else if($brand_id != "" && $req_type == "edit")
		{
			$sql_brand_data 	= "Select * from tbl_brands_master where brand_id = '".$brand_id."' ";
			$result_brand_data 	= mysqli_query($db_con,$sql_brand_data) or die(mysqli_error($db_con));
			$row_brand_data		= mysqli_fetch_array($result_brand_data);		
		}	
		else if($brand_id != "" && $req_type == "view")
		{
			$sql_brand_data 	= "Select * from tbl_brands_master where brand_id = '".$brand_id."' ";
			$result_brand_data 	= mysqli_query($db_con,$sql_brand_data) or die(mysqli_error($db_con));
			$row_brand_data		= mysqli_fetch_array($result_brand_data);		
		}			
		$data = '';
		if($brand_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="brand_id" name="brand_id" value="'.$row_brand_data['brand_id'].'">';
			$data .= '<input type="hidden" id="update_brand" name="update_brand" value="1" >';			
		}
		elseif($brand_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$brand_id.'">';
			$data .= '<input type="hidden" id="insert_brand" name="insert_brand" value="1" >';			
		}
		elseif($brand_id == "" && $req_type == "add")
		{
			$data .= '<input type="hidden" id="insert_brand" name="insert_brand" value="1" >';			
		}		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Brand Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="brand_name" name="brand_name" class="input-large" data-rule-required="true" ';
		if($brand_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_brand_data['brand_name']).'"'; 
		}
		elseif($brand_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_brand_data->brand_name).'"'; 			
		}
		elseif($brand_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_brand_data['brand_name']).'" disabled'; 				
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Brand Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Description <sup class="validfield"><span style="color:#F00;font-size:20px;"></span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea id="brand_description" name="brand_description" >';
		if($brand_id != "" && $req_type == "edit")
		{
			$data .= $row_brand_data['brand_description']; 
		}
		elseif($brand_id != "" && $req_type == "error")
		{
			$data .= $row_brand_data->brand_description;
		}
		elseif($brand_id != "" && $req_type == "view")
		{
			$data .= $row_brand_data['brand_description']; 				
		}		
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!--Description-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Brand Owner <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="brand_owner" name="brand_owner" class="input-large" data-rule-required="true"';
		if($brand_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_brand_data['brand_owner']).'"'; 
		}
		elseif($brand_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_brand_data->brand_owner).'"';
		}
		elseif($brand_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_brand_data['brand_owner']).'" disabled';
		}			
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Brand Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Image<sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="file" name="brand_image" id="brand_image" >';
		$data .= '<span>Please Upload Png Image Only.</span>';				
		$data .= '</div>';
		$data .= '</div>';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Tags <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="brand_meta_tags" name="brand_meta_tags" class="input-xlarge"';
		if($brand_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_brand_data['brand_meta_tags']).'"'; 
		}
		elseif($brand_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_brand_data->brand_meta_tags).'"';
		}
		elseif($brand_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_brand_data['brand_meta_tags']).'" disabled'; 				
		}			
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Tags-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Description <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea rows="5" cols="50" id="brand_meta_description" name="brand_meta_description" style="width:30%" data-rule-required="false">';
		if($brand_id != "" && $req_type == "edit")
		{
			$data .= $row_brand_data['brand_meta_description']; 
		}
		elseif($brand_id != "" && $req_type == "error")
		{
			$data .= $row_brand_data->brand_meta_description;
		}
		elseif($brand_id != "" && $req_type == "view")
		{
			$data .= $row_brand_data['brand_meta_description']; 				
		}		
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Description-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Meta Title <sup class="validfield"></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="brand_meta_title" name="brand_meta_title" class="input-large"';
		if($brand_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.ucwords($row_brand_data['brand_meta_title']).'"'; 
		}
		elseif($brand_id != "" && $req_type == "error")
		{
			$data .= ' value="'.ucwords($row_brand_data->brand_meta_title).'"'; 			
		}
		elseif($brand_id != "" && $req_type == "view")
		{
			$data .= ' value="'.ucwords($row_brand_data['brand_meta_title']).'" disabled'; 				
		}			
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Title-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($brand_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="brand_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_brands.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_brand_data->brand_status == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="brand_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_brand_data->brand_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($brand_id != "" && $req_type == "view")
		{
			if($row_brand_data['brand_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_brand_data['brand_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="brand_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_brands.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_brand_data['brand_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="brand_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_brand_data['brand_status'] == 0)
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
		if($brand_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($brand_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
		}			
		elseif($brand_id != "" && $req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update</button>';						
		}
		$data .= '</div> <!-- Save and cancel -->';
		$data .= '<script type="text/javascript">';
		$data .= 'CKEDITOR.replace("brand_description",{height:"150", width:"100%"});';
		$data .= 'CKEDITOR.replace("brand_meta_description",{height:"150", width:"100%"});';
		if($brand_id != "" && $req_type == "view")
		{
			$data .= '$("#brand_description").prop("disabled","true");';
			$data .= '$("#brand_meta_description").prop("disabled","true");';								
		}
		$data .= '</script>';		
		$response_array = array("Success"=>"Success","resp"=>$data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Type Not Defined");		
	}
	echo json_encode($response_array);
}
if((isset($obj->load_brand)) == "1" && isset($obj->load_brand))
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
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT `brand_id`, `brand_name`, `brand_description`, `brand_owner`, `brand_image`, `brand_meta_tags`, `brand_meta_description`,";
		$sql_load_data  .= " `brand_meta_title`, `brand_status`, `brand_created_by`, `brand_created`, `brand_modified_by`, `brand_modified`,";
		$sql_load_data  .= "(SELECT fullname FROM tbl_cadmin_users WHERE id=tbm.brand_created_by) AS name_created_by,brand_slug, ";
		$sql_load_data  .= " (SELECT fullname FROM tbl_cadmin_users WHERE id=tbm.brand_modified_by) AS name_modified_by FROM `tbl_brands_master` AS tbm where 1=1 ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND brand_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (`brand_name` like '%".$search_text."%' or `brand_owner` like '%".$search_text."%' or `brand_description` like '%".$search_text."%') ";
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY brand_name ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$brand_data = "";	
			$brand_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
    	 	$brand_data .= '<thead>';
    	  	$brand_data .= '<tr>';
         	$brand_data .= '<th class="center-text">Sr. No.</th>';
			$brand_data .= '<th class="center-text">Brand Id</th>';
			$brand_data .= '<th class="center-text">Brand Details</th>';
			$brand_data .= '<th class="center-text">Image</th>';
			$brand_data .= '<th class="center-text">URL/Slug</th>';			
			$brand_data .= '<th class="center-text">Product Discount</th>';			
			$dis = checkFunctionalityRight("view_brands.php",3);
			if($dis)
			{					
				$brand_data .= '<th>Status</th>';						
			}
			$edit = checkFunctionalityRight("view_brands.php",1);
			if($edit)
			{					
				$brand_data .= '<th>Edit</th>';			
			}	
			$delete = checkFunctionalityRight("view_brands.php",2);
			if($delete)
			{					
				$brand_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDeleteBrands();" class="btn-danger"/></div></th>';
			}					
          	$brand_data .= '</tr>';
      		$brand_data .= '</thead>';
      		$brand_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$brand_data .= '<tr>';				
				$brand_data .= '<td>'.++$start_offset.'</td>';				
				$brand_data .= '<td>'.$row_load_data['brand_id'].'</td>';
				$brand_data .= '<td>';
				$brand_data .= '<div class="center-text">';				
				$brand_data .= '<input type="button" value="'.ucwords($row_load_data['brand_name']).'" class="btn-link" id="'.$row_load_data['brand_id'].'" onclick="DoMoreBrand(this.id,\'view\');">';
				$brand_data .= '</div>';								
				$brand_data .= '<i class="icon-chevron-down" id="'.$row_load_data['brand_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['brand_id'].'brand_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$brand_data .= '</div>';				
				$brand_data .= '<div style="display:none" id="'.$row_load_data['brand_id'].'brand_div">';
				$brand_data .= '<div><b>Organisation:</b>'.$row_load_data['brand_owner'].'</div>';
				if($row_load_data['name_created_by'] == "")
				{
					$brand_data .= '<b>Created By:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$brand_data .= '<span><b>Created By:</b>'.$row_load_data['name_created_by'].'</span><br>';
				}
				if($row_load_data['brand_created'] == "" || $row_load_data['brand_created'] == "0000-00-00 00:00:00")
				{
					$brand_data .= '<b>Created :</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$brand_data .= '<span><b>Created :</b>'.$row_load_data['brand_created'].'</span><br>';
				}				
				if($row_load_data['name_modified_by'] == "")
				{
					$brand_data .= '<b>Modified By:</b><span style="color:#f00;">Not Available</span><br>';					
				}
				else
				{
					$brand_data .= '<span><b>Modified By:</b>'.$row_load_data['name_modified_by'].'</span><br>';
				}
				if($row_load_data['brand_modified'] == "" || $row_load_data['brand_modified'] == "0000-00-00 00:00:00")
				{
					$brand_data .= '<b>Modified:</b><span style="color:#f00;">Not Available</span><br>';						
				}
				else
				{
					$brand_data .= '<span><b>Modified:</b>'.$row_load_data['brand_modified'].'</span><br>';								
				}				
				$brand_data .= '</div>';				
				$brand_data .= '</td>';
				$brand_data .= '<td class="center-text" style="width:100px;height:100px;">';				
				if(trim($row_load_data['brand_image']) != "")
				{
					$brand_imgage= "../images/brands/brand_".$row_load_data['brand_id']."/".$row_load_data['brand_image'];
					$brand_data .= '<img src="'.$brand_imgage.'" />';					
				}
				else
				{
					$brand_data .= '<span style="color:#f00;">No Image</span>';
				}
				$brand_data .= '</td>';		
				$brand_data .= '<td class="center-text">';		
				$brand_data .= '<Div>';
				$brand_data .= '<textarea id="'.$row_load_data['brand_id'].'brand_slug" onblur="changeBrandsSlug(this.id)">'.$row_load_data['brand_slug'].'</textarea>';				
				$brand_data .= '</div>';				
				$brand_data .= '</td>';												
				$brand_data .= '<td style="text-align:center">';
				$brand_data .= '<div>';
				$brand_data .= '<span><input type="radio" name="'.$row_load_data['brand_id'].'discount" value="flat">Flat </span>';
				$brand_data .= '<span><input type="radio" name="'.$row_load_data['brand_id'].'discount" value="percent">Percent(%) </span>';
				$brand_data .= '</div><br>';					
				$brand_data .= '<div class="center-text">';
				$brand_data .= '<input type="text" name="'.$row_load_data['brand_id'].'discount_value" id="'.$row_load_data['brand_id'].'discount_value">';					
				$brand_data .= '</div>';															
				$brand_data .= '<div class="center-text">';
				$brand_data .= '<input type="button" onClick="productDiscount(this.id,5);" class="btn-success" id="'.$row_load_data['brand_id'].'dis_btn" value="Apply to '.ucwords($row_load_data['brand_name']).'">';
				$brand_data .= '</div>';								
				$brand_data .= '</td>';				
				if($dis)
				{						
					$brand_data .= '<td style="text-align:center">';	
					if($row_load_data['brand_status'] == 1)
					{
						$brand_data .= '<input type="button" value="Active" id="'.$row_load_data['brand_id'].'" class="btn-success" onclick="changeBrandsStatus(this.id,0);">';
					}
					else
					{
						$brand_data .= '<input type="button" value="Inactive" id="'.$row_load_data['brand_id'].'" class="btn-danger" onclick="changeBrandsStatus(this.id,1);">';
					}
					$brand_data .= '</td>';	
				}
				if($edit)
				{			
					$brand_data .= '<td style="text-align:center">';
					$brand_data .= '<input type="button" value="Edit" id="'.$row_load_data['brand_id'].'" class="btn-warning" onclick="DoMoreBrand(this.id,\'edit\');"></td>';
				}
				if($delete)
				{					
					$brand_data .= '<td><div class="controls" align="center">';
					$brand_data .= '<input type="checkbox"  id="batch'.$row_load_data['brand_id'].'" name="batch'.$row_load_data['brand_id'].'" value="'.$row_load_data['brand_id'].'"  class="css-checkbox batch">';
					$brand_data .= '<label for="batch'.$row_load_data['brand_id'].'" class="css-label" style="color:#FFF"></label>';
					$brand_data .= '</div></td>';										
				}
	          	$brand_data .= '</tr>';															
			}	
      		$brand_data .= '</tbody>';
      		$brand_data .= '</table>';	
			$brand_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$brand_data);					
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
	$brand_id				= $obj->brand_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();		
	$sql_update_status 		= " UPDATE `tbl_brands_master` SET `brand_status`= '".$curr_status."' ,`brand_modified` = '".$datetime."' ,`brand_modified_by` = '".$utype."' WHERE `brand_id` like '".$brand_id."' ";
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
if((isset($obj->change_brand_slug)) == "1" && isset($obj->change_brand_slug))
{
	$brand_id				= mysqli_real_escape_string($db_con,$obj->brand_id);	
	$brand_slug				= mysqli_real_escape_string($db_con,$obj->brand_slug);
	$response_array 		= array();		
	$sql_update_slug 		= " UPDATE `tbl_brands_master` SET `brand_slug`= '".$brand_slug."' ,`brand_modified` = '".$datetime."' ,`brand_modified_by` = '".$uid."' WHERE `brand_id` = '".$brand_id."' ";
	$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
	if($result_update_slug)
	{
		$response_array = array("Success"=>"Success","resp"=>"Slug Updated Successfully.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Slug Update Failed.");
	}		
	echo json_encode($response_array);	
}
if((isset($_POST['update_brand'])) == "1" && isset($_POST['update_brand']))
{
	$brand_id				= strtolower(mysqli_real_escape_string($db_con,$_POST['brand_id']));	
	$brand_name				= strtolower(mysqli_real_escape_string($db_con,$_POST['brand_name']));
	$brand_description		= mysqli_real_escape_string($db_con,$_POST['brand_description']);	
	$brand_owner			= strtolower(mysqli_real_escape_string($db_con,$_POST['brand_owner']));
	$brand_status			= mysqli_real_escape_string($db_con,$_POST['brand_status']);
	$brand_meta_description	= mysqli_real_escape_string($db_con,$_POST['brand_meta_description']);
	$brand_meta_title		= mysqli_real_escape_string($db_con,$_POST['brand_meta_title']);
	$brand_meta_tags		= mysqli_real_escape_string($db_con,$_POST['brand_meta_tags']);	
	if($brand_name != ""  && $brand_owner != "" && $brand_status != "")
	{
		$sql_check_brand 		= "select * from tbl_brands_master where brand_name like '".$brand_name."' and `brand_id` != '".$brand_id."' "; 
		$result_check_brand 	= mysqli_query($db_con,$sql_check_brand) or die(mysqli_error($db_con));
		$num_rows_check_brand 	= mysqli_num_rows($result_check_brand);
		if($num_rows_check_brand == 0)
		{
			$brand_slug 			= getSlug(strtolower(str_replace(" ","-",$brand_name)));
			
			$sql_update_brand = " UPDATE `tbl_brands_master` SET `brand_name`='".$brand_name."',`brand_description`='".$brand_description."',`brand_owner`='".$brand_owner."',`brand_slug`='".$brand_slug."',";		
			$sql_update_brand .= " `brand_meta_title`='".$brand_meta_title."',`brand_meta_tags`='".$brand_meta_tags."',`brand_meta_description`='".$brand_meta_description."',`brand_status`='".$brand_status."',";
			$sql_update_brand .= " `brand_modified`='".$datetime."',`brand_modified_by`='".$uid."' WHERE `brand_id` = '".$brand_id."' ";		
			$result_update_brand = mysqli_query($db_con,$sql_update_brand) or die(mysqli_error($db_con));
			if($result_update_brand)
			{
				if(isset($_FILES['brand_image']['name']) && trim($_FILES['brand_image']['name']) != "" )				  
				{
					$brand_image_name		= trim($_FILES["brand_image"]["name"]);
					$brand_image_tmp		= trim($_FILES["brand_image"]["tmp_name"]);
					$brand_image_file_size	= trim($_FILES['brand_image']['size']);
					$brand_image_extension	= getExtension($brand_image_name);
					if($brand_image_name != "" && $brand_image_tmp != "" && $brand_image_extension == "png")					
					{
				  		$brand_path				= "../images/brands/brand_".$brand_id;
						if(is_dir($brand_path) === false)
						{
							mkdir($brand_path);
						}
						if(move_uploaded_file($brand_image_tmp,$brand_path."/".$brand_image_name))
						{
							$sql_update_brand_image 	= "  UPDATE `tbl_brands_master` SET `brand_image`= '".$brand_image_name."' WHERE `brand_id` = '".$brand_id."' ";
							$result_update_brand_image 	= mysqli_query($db_con,$sql_update_brand_image) or die(mysqli_error($db_con));
							if($result_update_brand_image)
							{
								$response_array = array("Success"=>"Success","resp"=>"Brand Image Uploaded and Updated in System.");
							}
							else
							{
								$response_array = array("Success"=>"Success","resp"=>"Brand Image Uploaded but not Updated in System.Contact Admin");
							}
						}
						else
						{
							$response_array = array("Success"=>"Success","resp"=>"Brand Image Not Uploaded.");
						}						
					}
					else
					{
						$response_array = array("Success"=>"fail","resp"=>"Please Upload Png Image Only.");						
					}					
				}
				else
				{
					$response_array = array("Success"=>"Success","resp"=>"Record Updated.");
				}
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Record Not Updated.");					
			}								
		}		
		else
		{
				  $response_array = array("Success"=>"fail","resp"=>"Brand".$brand_name." already exists.");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->delete_brand)) == "1" && isset($obj->delete_brand))
{
	$response_array = array();		
	$ar_brand_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_brand_id as $brand_id)	
	{
		/* Delete Brand Image*/
		$sql_get_brand_details 		= " SELECT * FROM `tbl_brands_master` WHERE `brand_id` = '".$brand_id."' ";
		$result_get_brand_details 	= mysqli_query($db_con,$sql_get_brand_details) or die(mysqli_error($db_con));
		$num_rows_get_brand_details = mysqli_num_rows($result_get_brand_details);
		if($num_rows_get_brand_details != 0)
		{
			$row_get_brand_details	= mysqli_fetch_array($result_get_brand_details);
			$brand_image_name		= $row_get_brand_details['brand_image'];
			$brand_image_path		= "../images/brands/brand_".$brand_id."/".$brand_image_name;					
			unlink($brand_image_path);
			unlink("../images/brands/brand_".$brand_id);
			$sql_delete_brand		= " DELETE FROM `tbl_brands_master` WHERE `brand_id` = '".$brand_id."' ";
			$result_delete_brand	= mysqli_query($db_con,$sql_delete_brand) or die(mysqli_error($db_con));			
			if($result_delete_brand)
			{
				$del_flag = 1;	
			}			
		}
		/* Delete Brand Image*/
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
?>