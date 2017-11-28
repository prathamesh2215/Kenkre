<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
$utype				= $_SESSION['panel_user']['utype'];
$uid				= $_SESSION['panel_user']['id'];

function insertFLink($flink_type,$flink_name,$flink_url,$flink_status,$response_array)
{          

	global $uid;
	global $db_con, $datetime;
	global $obj;
      
	$sql_check_flink  = "select * from  tbl_footer_link where  flink_name like '".$flink_name."' "; 
       	$result_check_flink 	= mysqli_query($db_con,$sql_check_flink) or die(mysqli_error($db_con));
	$num_rows_check_flink = mysqli_num_rows($result_check_flink);
            
	if($num_rows_check_flink == 0)
	{

		$sql_last_rec = "Select * from tbl_footer_link order by flink_id desc LIMIT 0,1";
		$result_last_rec = mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
		$num_rows_last_rec = mysqli_num_rows($result_last_rec);
              
		if($num_rows_last_rec == 0)
		{
			$flink_id 		= 1;				
		}
		else
		{
			$row_last_rec = mysqli_fetch_array($result_last_rec);				
			$flink_id     = $row_last_rec['flink_id']+1;
		}	

		$sql_insert_flink = " INSERT INTO `tbl_footer_link`(`flink_id`,`flink_name`, `flink_url`, `flink_type`,`flink_status`,";
		$sql_insert_flink .= "`flink_created`,`flink_created_by`) VALUES ('".$flink_id."','".$flink_name."',";
		$sql_insert_flink .= "'".$flink_url."','".$flink_type."','".$flink_status."','".$datetime."','".$uid."') ";
		$result_insert_flink = mysqli_query($db_con,$sql_insert_flink) or die(mysqli_error($db_con));
//                 $response_array = array("Success"=>"fail","resp"=>$num_rows_last_rec);
//               echo json_encode($response_array);	
//               exit(0);
                $response_array = array("Success"=>"Success","resp"=>"Data Inserted Successfully");
				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Footer Link <b>".$flink_name."</b> already Exist");
	}	
	return $response_array;
}



if((isset($_POST['insert_flink'])) == "1" && isset($_POST['insert_flink']))
{
    	$flink_type			= mysqli_real_escape_string($db_con,$_POST['flink_type']);
	$flink_name			= mysqli_real_escape_string($db_con,$_POST['flink_name']);
        $flink_url		        = mysqli_real_escape_string($db_con,$_POST['flink_url']);
	$flink_status			= mysqli_real_escape_string($db_con,$_POST['flink_status']);
	
	
	$response_array = array();
	if($flink_name != "" && $flink_url != "" && $flink_status != "")
	{
//            $response_array = array("Success"=>"fail","resp"=>"Footer Link Test","flink_name"=>$flink_name);
//            echo json_encode($response_array);
//            exit;
		$response_array = insertFLink($flink_type,$flink_name,$flink_url,$flink_status,$response_array);			
		       
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->load_flink_parts)) == "1" && isset($obj->load_flink_parts))
{
	
	$flink_id = $obj->flink_id;
	$req_type = $obj->req_type;
       
	$response_array = array();
	if($req_type != "")
	{
           
		if($flink_id != "" && $req_type == "error")
		{
			$sql_error_data 	= " SELECT * FROM `tbl_error_data` WHERE `error_id` = '".$flink_id."' "; // this brand_id is error id from error table
			$result_error_data 	= mysqli_query($db_con,$sql_error_data) or die(mysqli_error($db_con));
			$row_error_data		= mysqli_fetch_array($result_error_data);	
			$row_flink_data		= json_decode($row_error_data['error_data']);
		}
		else if($flink_id != "" && $req_type == "edit")
		{
                        $sql_flink_data 	= "Select * from tbl_footer_link where flink_id = '".$flink_id."' ";
			$result_link_data 	= mysqli_query($db_con,$sql_flink_data) or die(mysqli_error($db_con));
			$row_flink_data		= mysqli_fetch_array($result_link_data);		
		}	
		else if($flink_id != "" && $req_type == "view")
		{
			$sql_link_data   	= "Select * from tbl_footer_link where flink_id = '".$flink_id."' ";
			$result_link_data 	= mysqli_query($db_con,$sql_link_data) or die(mysqli_error($db_con));
			$row_flink_data		= mysqli_fetch_array($result_link_data);		
		}			
		$data = '';
		if($flink_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" id="flink_id" name="flink_id" value="'.$row_flink_data['flink_id'].'">';
			$data .= '<input type="hidden" id="update_flink" name="update_flink" value="1" >';			
		}
		elseif($flink_id != "" && $req_type == "error")
		{
			$data .= '<input type="hidden" id="error_id" value="'.$flink_id.'">';
			$data .= '<input type="hidden" id="insert_flink" name="insert_flink" value="1" >';			
		}
		elseif($flink_id == "" && $req_type == "add")
		{
			$data .= '<input type="hidden" id="insert_flink" name="insert_flink" value="1" >';			
		}
                /*----------------------------------- type code Start----------------------------------------------*/    
                $data .= '<div class="control-group">';
	$data .= '<label for="tasktitel" class="control-label">Type <span style="color:#F00;font-size:20px;">*</span></label>';
	$data .= '<div class="controls">';
	if($req_type != "view")
	{
		$data .= '<select name="flink_type" id="flink_type" placeholder="Type" class="select2-me input-large" data-rule-required="true" onchange="getFilterChild(this.id);">';
		$data .= '<option value="">Select Type</option>';						
	}
	if(($flink_id == "" && $req_type == "add") || ($flink_id != "" && $req_type == "error"))
	{
			$data .= '<option value="parent">Parent</option>';			
			$sql_get_parent = "SELECT distinct flink_id,flink_name FROM `tbl_footer_link` where flink_type = 'Parent' ";
			$result_get_parent = mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));														
			while($row_get_parent = mysqli_fetch_array($result_get_parent))
			{															
				$data .= '<option value="'.$row_get_parent['flink_id'].'">'.$row_get_parent['flink_name'].'</option>';
			}
	}
	elseif($flink_id != "" && $req_type == "view")
	{
//		if(strcmp(trim($row_cat_data['filt_sub_child']),"parent") == 0)
//		{
//			$data .= '<label class="control-label" >'.ucwords($row_cat_data['flink_type']).'</label>';
//		}
//		elseif(strcmp(trim($row_cat_data['filt_sub_child']),"child") == 0)
//		{
			$data .= '<label class="control-label" >'.$row_flink_data['flink_type'].'</label>';
//		}		
//		else
//		{
//			$data .= '<label class="control-label" >'.ucwords($row_get_parent['filt_type_name']).'</label>';
//		}
	}		
	elseif($flink_id != "" && $req_type == "edit")
	{
			if(strcmp(trim($row_flink_data['flink_type']),"parent") == 0)
			{
				$data .= '<option value="parent" selected="selected">Parent</option>';			
			}
			else
			{
				$data .= '<option value="parent" >Parent</option>';							
			}
			$sql_get_parent 		= " SELECT distinct flink_id,flink_name FROM `tbl_footer_link` where flink_type = 'parent' and `flink_name` != '".$row_flink_data['flink_name']."' ";
			$result_get_parent 		= mysqli_query($db_con,$sql_get_parent) or die(mysqli_error($db_con));														
			while($row_get_parent 	= mysqli_fetch_array($result_get_parent))
			{		
				if($row_get_parent['flink_id'] == $row_flink_data['flink_type'])
				{
					$data .= '<option value="'.$row_get_parent['flink_id'].'" selected>'.$row_get_parent['flink_name'].'</option>';
				}
				//elseif(strcmp(trim($row_cat_data['filt_name']),$row_get_parent['filt_name']) == 0){}
				else
				{
					$data .= '<option value="'.$row_get_parent['flink_id'].'">'.$row_get_parent['flink_name'].'</option>';
				}
			}			
	}
		$data .= '</select>';
		$data .= '<div id="divparent" style="font-size:10px;color:#F00">';
		$data .= '</div>';
		$data .= '</div>';
		$data .= '</div> <!--Parent-->';
          /*----------------------------------- type code end----------------------------------------------*/
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Link Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="flink_name" name="flink_name" class="input-large" data-rule-required="true" ';
		if($flink_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_flink_data['flink_name'].'"'; 
		}
		elseif($flink_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_flink_data->flink_name.'"'; 			
		}
		elseif($flink_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_flink_data['flink_name'].'" disabled'; 				
		}
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Footer Link Name -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Link Url<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="flink_url" name="flink_url" data-rule-required="true" class="input-large"';
		if($flink_id != "" && $req_type == "edit")
		{
			$data .= ' value="'.$row_flink_data['flink_url'].'"'; 
		}
		elseif($flink_id != "" && $req_type == "error")
		{
			$data .= ' value="'.$row_flink_data->flink_url.'"'; 			
		}
		elseif($flink_id != "" && $req_type == "view")
		{
			$data .= ' value="'.$row_flink_data['flink_url'].'" disabled'; 				
		}			
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!--Meta Title-->';
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		if($flink_id != "" && $req_type == "error")
		{
			$data .= '<input type="radio" name="flink_status" value="1" class="css-radio" data-rule-required="true" ';
			
			$data .= '>Active';
			$data .= '<input type="radio" name="flink_status" value="0" class="css-radio" data-rule-required="true"';
//			
				$data .= ' disabled="disabled" ';
//			
			if($row_flink_data->flink_status == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
		}
		elseif($flink_id != "" && $req_type == "view")
		{
			if($row_flink_data['flink_status'] == 1)
			{
				$data .= '<label class="control-label" style="color:#30DD00">Active</label>';
			}
			if($row_flink_data['flink_status'] == 0)
			{
				$data .= '<label class="control-label" style="color:#E63A3A">Inactive</label>';
			}
		}			
		else
		{
			$data .= '<input type="radio" name="flink_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_footer_link.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_flink_data['flink_status'] == 1)
			{
				$data .= 'checked ';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="flink_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_flink_data['flink_status'] == 0)
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
		if($flink_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Create</button>';			
		}
		elseif($flink_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update</button>';			
		}			
		elseif($flink_id != "" && $req_type == "error")
		{
			$data .= '<button type="submit" name="reg_submit_error" class="btn-success">Update</button>';						
		}
		$data .= '</div> <!-- Save and cancel -->';
		$data .= '<script type="text/javascript">';
		
		if($flink_id != "" && $req_type == "view")
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
if((isset($obj->load_flink)) == "1" && isset($obj->load_flink))
{
        $start_offset   = 0;	
        $page 			= mysqli_real_escape_string($db_con,$obj->page);	
	$per_page		= mysqli_real_escape_string($db_con,$obj->row_limit);
	$search_text	        = mysqli_real_escape_string($db_con,$obj->search_text);	
	$flink_parent		= mysqli_real_escape_string($db_con,$obj->flink_parent);	

	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
//		$sql_load_data  = " SELECT `brand_id`, `brand_name`, `brand_description`, `brand_owner`, `brand_image`, `brand_meta_tags`, `brand_meta_description`,";
//		$sql_load_data  .= " `brand_meta_title`, `brand_status`, `brand_created_by`, `brand_created`, `brand_modified_by`, `brand_modified`,";
//		$sql_load_data  .= "(SELECT fullname FROM tbl_cadmin_users WHERE id=tbm.brand_created_by) AS name_created_by,brand_slug, ";
//		$sql_load_data  .= " (SELECT fullname FROM tbl_cadmin_users WHERE id=tbm.brand_modified_by) AS name_modified_by FROM `tbl_brands_master` AS tbm where 1=1 ";
                
                $sql_load_data  = " SELECT `flink_id`, `flink_name`,`flink_url`,`flink_type`,";
		$sql_load_data  .= "`flink_status`, `flink_created_by`, `flink_created`, `flink_modified_by`, `flink_modified`,";
		$sql_load_data  .= "(SELECT fullname FROM tbl_cadmin_users WHERE id=tfl.flink_created_by) AS name_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM tbl_cadmin_users WHERE id=tfl.flink_modified_by) AS name_modified_by FROM `tbl_footer_link` AS tfl WHERE 1=1  and  flink_type like '".$flink_parent."'  ";
       
           	if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND flink_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (`flink_name` like '%".$search_text."%' or `flink_url` like '%".$search_text."%') ";
		}
		$data_count	= dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY flink_name ASC LIMIT $start, $per_page ";
                $result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
		       $flink_data = "";

			if($flink_parent == "parent")
			{
				$flink_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Parent List</label><br>';
			}
			elseif($flink_parent != "parent")
			{
				$flink_data .= '<label style="font-size:20px;float:left;font-weight:bold;">Child List</label><br>';
			}
			$flink_data .= '<table id="tbl_user" class="table table-bordered dataTable">';
                        $flink_data .= '<thead>';
                        $flink_data .= '<tr>';
                        $flink_data .= '<th class="center-text">Sr. No.</th>';
			$flink_data .= '<th class="center-text">Footer Link Id</th>';
			$flink_data .= '<th class="center-text">Footer Link Details</th>';
                        $flink_data .= '<th class="center-text">Footer Link Url</th>';
			$flink_data .= '<th class="center-text">View</th>';		
			$dis = checkFunctionalityRight("view_footer_link.php",3);
					
			$flink_data .= '<th>Status</th>';						

//			$edit = checkFunctionalityRight("view_footer_link.php",1);
                        $edit=1;
			if($edit)
			{					
				$flink_data .= '<th>Edit</th>';			
			}	
			$delete = checkFunctionalityRight("view_footer_link.php",2);
//                        $delete=1;
			if($delete)
			{					
				$flink_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDeleteBrands();" class="btn-danger"/></div></th>';
			}					
          	$flink_data .= '</tr>';
      		$flink_data .= '</thead>';
      		$flink_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$flink_data .= '<tr>';				
				$flink_data .= '<td>'.++$start_offset.'</td>';				
				$flink_data .= '<td>'.$row_load_data['flink_id'].'</td>';
				$flink_data .= '<td>';
				$flink_data .= '<div class="center-text">';				
				$flink_data .= '<input type="button" value="'.$row_load_data['flink_name'].'" class="btn-link" id="'.$row_load_data['flink_id'].'" onclick="DoMoreFlink(this.id,\'view\');">';
				$flink_data .= '</div>';								
				$flink_data .= '<i class="icon-chevron-down" id="'.$row_load_data['flink_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['flink_id'].'flink_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$flink_data .= '</div>';				
				$flink_data .= '<div style="display:none" id="'.$row_load_data['flink_id'].'flink_div">';
//				$flink_data .= '<div><b>Organisation:</b>'.$row_load_data['brand_owner'].'</div>';
				if($row_load_data['name_created_by'] == "")
				{
					$flink_data .= '<b>Created By:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$flink_data .= '<span><b>Created By:</b>'.$row_load_data['name_created_by'].'</span><br>';
				}
				if($row_load_data['flink_created'] == "" || $row_load_data['flink_created'] == "0000-00-00 00:00:00")
				{
					$flink_data .= '<b>Created :</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$flink_data .= '<span><b>Created :</b>'.$row_load_data['flink_created'].'</span><br>';
				}				
				if($row_load_data['name_modified_by'] == "")
				{
					$flink_data .= '<b>Modified By:</b><span style="color:#f00;">Not Available</span><br>';					
				}
				else
				{
					$flink_data .= '<span><b>Modified By:</b>'.$row_load_data['name_modified_by'].'</span><br>';
				}
				if($row_load_data['flink_modified'] == "" || $row_load_data['flink_modified'] == "0000-00-00 00:00:00")
				{
					$flink_data .= '<b>Modified:</b><span style="color:#f00;">Not Available</span><br>';						
				}
				else
				{
					$flink_data .= '<span><b>Modified:</b>'.$row_load_data['flink_modified'].'</span><br>';								
				}				
				$flink_data .= '</div>';				
				$flink_data .= '</td>';
				$flink_data .= '<td>'.$row_load_data['flink_url'].'</td>';
                                $flink_data .= '<td class="center-text">';
				if($flink_parent == "parent")
				{
					$sql_check_parent 	= "SELECT * FROM `tbl_footer_link` WHERE `flink_type` like '".$row_load_data['flink_id']."'";
					$result_check_parent 	= mysqli_query($db_con,$sql_check_parent) or die(mysqli_error($db_con));
					$num_rows_check_parent 	= mysqli_num_rows($result_check_parent);
					if($num_rows_check_parent != 0)
					{
						$flink_data .= '<input type="button" class="btn-success"  value="View Child" id="'.$row_load_data['flink_id'].'" onclick="viewChild(this.id)"></td>';						
					}
                                       
				}
				else
				{
                                 
                                       $flink_data .= '<input type="button" class="btn-success"  value="View Parent" id="parent" onclick="viewChild(this.id)"></td>';
                                       
				}
				$flink_data .= '</td>';
					
					$flink_data .= '<td style="text-align:center">';	
					if($row_load_data['flink_status'] == 1)
					{
						$flink_data .= '<input type="button" value="Active" id="'.$row_load_data['flink_id'].'" class="btn-success" onclick="changeFlinksStatus(this.id,0);">';
					}
					else
					{
						$flink_data .= '<input type="button" value="Inactive" id="'.$row_load_data['flink_id'].'" class="btn-danger" onclick="changeFlinksStatus(this.id,1);">';
					}
					$flink_data .= '</td>';	

				if($edit)
				{			
					$flink_data .= '<td style="text-align:center">';
					$flink_data .= '<input type="button" value="Edit" id="'.$row_load_data['flink_id'].'" class="btn-warning" onclick="DoMoreFlink(this.id,\'edit\');"></td>';
				}
				if($delete)
				{					
					$flink_data .= '<td><div class="controls" align="center">';
					$flink_data .= '<input type="checkbox"  id="batch'.$row_load_data['flink_id'].'" name="batch'.$row_load_data['flink_id'].'" value="'.$row_load_data['flink_id'].'"  class="css-checkbox batch">';
					$flink_data .= '<label for="batch'.$row_load_data['flink_id'].'" class="css-label" style="color:#FFF"></label>';
					$flink_data .= '</div></td>';										
				}
	          	$flink_data .= '</tr>';															
			}	
      		$flink_data .= '</tbody>';
      		$flink_data .= '</table>';	
			$flink_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$flink_data);					
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
	$flink_id				= $obj->flink_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();		
	$sql_update_status 		= " UPDATE `tbl_footer_link` SET `flink_status`= '".$curr_status."' ,`flink_modified` = '".$datetime."' ,`flink_modified_by` = '".$utype."' WHERE `flink_id` like '".$flink_id."' ";
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

if((isset($_POST['update_flink'])) == "1" && isset($_POST['update_flink']))
{ 
      
	$flink_id			= mysqli_real_escape_string($db_con,$_POST['flink_id']);	
       	$flink_type			= mysqli_real_escape_string($db_con,$_POST['flink_type']);
	$flink_name			= mysqli_real_escape_string($db_con,$_POST['flink_name']);
        $flink_url		        = mysqli_real_escape_string($db_con,$_POST['flink_url']);
	$flink_status			= mysqli_real_escape_string($db_con,$_POST['flink_status']);
	$response_array = array();
       
	if($flink_name != "" && $flink_url != "" && $flink_status != "")
	{
            
		$sql_check_link	= " select * from tbl_footer_link where flink_name like '".$flink_name."' and `flink_id` !='".$flink_id."' "; 

		$result_check_link 	= mysqli_query($db_con,$sql_check_link) or die(mysqli_error($db_con));
		$num_rows_check_link 	= mysqli_num_rows($result_check_link);
                
		if($num_rows_check_link == 0)
		{
                     
			$sql_update_link = " UPDATE `tbl_footer_link` SET `flink_name`='".$flink_name."',`flink_type`='".$flink_type."',";		
			$sql_update_link .= " `flink_url`='".$flink_url."',`flink_status`='".$flink_status."',";
			$sql_update_link .= " `flink_modified`='".$datetime."',`flink_modified_by`='".$uid."' WHERE `flink_id` = '".$flink_id."' ";
			$result_update_link = mysqli_query($db_con,$sql_update_link) or die(mysqli_error($db_con));
                        if($flink_type!='parent')
                        {
                            $sql_check_child	= "select * from tbl_footer_link where `flink_type`='".$flink_id."' "; 
                            $result_check_child	= mysqli_query($db_con,$sql_check_child) or die(mysqli_error($db_con));
                            $num_rows_child_link 	= mysqli_num_rows($result_check_child);
                            if($num_rows_child_link!=0)
                            {

                                  while($row_get_child = mysqli_fetch_array($result_check_child))					
                                    {

                                        $sql_update_child_link = " UPDATE `tbl_footer_link` SET `flink_type`='".$flink_type."',";		
                                        $sql_update_child_link .= " `flink_modified`='".$datetime."',`flink_modified_by`='".$uid."' WHERE `flink_id` = '".$row_get_child['flink_id']."' ";
                                        $result_update_child_link = mysqli_query($db_con,$sql_update_child_link) or die(mysqli_error($db_con));

                                    }  

                            }
                        }
                        $response_array = array("Success"=>"Success","resp"=>"Records have updated successfully.");
		}		
		else
		{
				  $response_array = array("Success"=>"fail","resp"=>"Footer Link".$flink_name." already exists.");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Empty Data.");	
	}
	echo json_encode($response_array);		
}
if((isset($obj->delete_flink)) == "1" && isset($obj->delete_flink))
{
	$response_array = array();		
	$ar_brand_id 		= $obj->batch;
	$del_flag 		= 0; 
	foreach($ar_brand_id as $flink_id)	
	{
		/* Delete Brand Image*/
		$sql_get_brand_details 		= " SELECT * FROM `tbl_footer_link` WHERE `flink_id` = '".$flink_id."' ";
		$result_get_brand_details 	= mysqli_query($db_con,$sql_get_brand_details) or die(mysqli_error($db_con));
		$num_rows_get_brand_details = mysqli_num_rows($result_get_brand_details);
		if($num_rows_get_brand_details != 0)
		{
//			$row_get_brand_details	= mysqli_fetch_array($result_get_brand_details);
//			$brand_image_name		= $row_get_brand_details['brand_image'];
//			$brand_image_path		= "../images/brands/brand_".$brand_id."/".$brand_image_name;					
//			unlink($brand_image_path);
//			unlink("../images/brands/brand_".$brand_id);
			$sql_delete_flink		= " DELETE FROM `tbl_footer_link` WHERE `flink_id` = '".$flink_id."' ";
			$result_delete_flink	= mysqli_query($db_con,$sql_delete_flink) or die(mysqli_error($db_con));			
			if($result_delete_flink)
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