<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];








if((isset($obj->load_add_faq_part)) != "" && isset($obj->load_add_faq_part))
{
	$faq_id 	= $obj->faq_id;
	$req_type 	= $obj->req_type;		
	
	 if($faq_id != "" && $req_type == "view" || $req_type == "edit")
	{
		$sql_faq_data 		= " SELECT * FROM tbl_faqs WHERE `faq_id` = '".$faq_id."' ";
		$res_faq_data 	    = mysqli_query($db_con,$sql_faq_data) or die(mysqli_error($db_con));
		$row_faq_data		= mysqli_fetch_array($res_faq_data);		
	}		
	$data = '';
		if($faq_id != "" && $req_type == "edit")
		{
			$data = '<input type="hidden" name="faq_id" id="faq_id" value="'.$row_faq_data['faq_id'].'">';
			$data .= '<input type="hidden" name="update_faq" id="update_faq" value="1">';
		}	
		else
		{
			$data = '<input type="hidden" name="insert_faq" id="insert_faq" value="1">';
		}
			
		$disabled="";
		if($req_type=="view")
		{
			$disabled=" disabled " ;
		}
				
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">FAQ Question <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea '.$disabled.' type="text" id="faq_ques" name="faq_ques"  style="width:90%"  rows="4" class="input-large" data-rule-required="true" >' ;
		if($req_type == "view" || $req_type == "edit")
		{
			$data .= $row_faq_data['faq_ques'];	
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!-- Faq Ques -->';
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">FAQ Answer <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea '.$disabled.' type="text" id="faq_ans" name="faq_ans"  style="width:90%"  rows="4" class="input-large" data-rule-required="true" >' ;
		if($req_type == "view" || $req_type == "edit")
		{
			$data .= $row_faq_data['faq_ans'];	
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!-- Faq Ques -->';
		
		
		
		$type_array  =array("Planet");
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Type <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select '.$disabled.' name="faq_type" id="faq_type" placeholder="Type" class="select2-me input-large" data-rule-required="true" >';
		$data .= '<option value="">Select Type</option>';
		
		$faq_type ='';
		if($req_type=="edit" || $req_type=="view")
		{
			$faq_type =$row_faq_data['faq_type'];
		}
		foreach($type_array as $type)
		{
		$data .= '<option ';
		if($faq_type==$type)
		{
			$data .=' selected="selected" ';
		}
		$data .= ' value="'.$type.'">'.$type.'</option>';
		}
		
		$data .= '</select>';
		$data .= '</div>';
		$data .= '</div> <!--Parent-->';
		
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		
			$data .= '<input type="radio" name="faq_status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_faq.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_faq_data['faq_status'] == 1)
			{
				$data .= 'checked';
			}
			$data .= '>Active';
			$data .= '<input type="radio" name="faq_status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_faq_data['faq_status'] == 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
			
		$data .= '<label for="radiotest" class="css-label"></label>';
		$data .= '<label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';				
		$data .= '<div class="form-actions">';
		if($faq_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Faq</button>';			
		}
		elseif($faq_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Faq</button>';
		}
		
		$data .= '</div> <!-- Save and cancel -->';
		$data .= '<script type="text/javascript">';
		$data .= '$("#faq_type").select2();';		
				
		$data .= '</script>';
	$response_array = array("Success"=>"Success","resp"=>$data);
	echo json_encode($response_array);
}



if((isset($_POST['insert_faq'])) == "1" && isset($_POST['insert_faq']))
{
	$faq_ques   = mysqli_real_escape_string($db_con,$_POST['faq_ques']);
	$faq_ans    = mysqli_real_escape_string($db_con,$_POST['faq_ans']);
	$faq_type   = $_POST['faq_type'];
	$faq_status = $_POST['faq_status'];
	
	if($faq_ques !="" && $faq_ans!="" && $faq_type !="")
	{
		$sql_get_order =" SELECT faq_sort_order FROM tbl_faqs WHERE faq_type ='".$faq_type."' ORDER BY faq_sort_order DESC LIMIT 1";
		$res_get_order = mysqli_query($db_con, $sql_get_order) or die(mysqli_error($db_con));
		if(mysqli_num_rows($res_get_order)==0)
		{
			$sort_order =1;
		}
		else
		{
			$row_get_order =mysqli_fetch_array($res_get_order);
			$sort_order    =$row_get_order['faq_sort_order'] + 1;
		}
		
		
		$sql_insert_faq	 = " INSERT INTO `tbl_faqs`(`faq_ques`, `faq_ans`, `faq_status`,faq_type,faq_sort_order,faq_created,faq_created_by ) ";
		$sql_insert_faq	.= " VALUES ('".$faq_ques."', '".$faq_ans."', '".$faq_status."','".$faq_type."', '".$sort_order."', '".$datetime."','".$uid."') ";
		$res_insert_faq	 = mysqli_query($db_con, $sql_insert_faq) or die(mysqli_error($db_con));
		if($res_insert_faq)
		{
			$response_array = array("Success"=>"Success","resp"=>"FAQ Added Successfully");
		}
		else
		{ 
		     $response_array = array("Success"=>"fail","resp"=>"FAQ Not Added");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"FAQ Question and Answer is required");
	}
	
	
	echo json_encode($response_array);		
}



//==========================================================================================================
//====================Start : Done by satish 15032017 for update_category=====================================
//==========================================================================================================



if((isset($_POST['update_faq'])) == "1" && isset($_POST['update_faq']))
{
	$faq_ques   = mysqli_real_escape_string($db_con,$_POST['faq_ques']);
	$faq_ans    = mysqli_real_escape_string($db_con,$_POST['faq_ans']);
	$faq_type   = $_POST['faq_type'];
	$faq_status = $_POST['faq_status'];
	$faq_id     =$_POST['faq_id'];
	
	if($faq_ques !="" && $faq_ans!="" && $faq_type !="" && $faq_id !="")
	{
		$sql_update_faq	 = " UPDATE tbl_faqs SET `faq_ques`='".$faq_ques."', `faq_ans`='".$faq_ans."', `faq_status`='".$faq_status."',";
		$sql_update_faq	.= "faq_type='".$faq_type."',";
		$sql_update_faq	.= " faq_modified='".$datetime."',faq_modified_by='".$uid."' WHERE faq_id ='".$faq_id."'";
		$res_update_faq	 = mysqli_query($db_con, $sql_update_faq) or die(mysqli_error($db_con));
		if($res_update_faq)
		{
			$response_array = array("Success"=>"Success","resp"=>"FAQ Updated Successfully");
		}
		else
		{ 
		     $response_array = array("Success"=>"fail","resp"=>"FAQ Not Updated");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"FAQ Question and Answer is required".$faq_id);
	}
	
	
	echo json_encode($response_array);		
}





//================================Strat :  Modified BY satish 16032017 update status==========//

if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$faq_id					= mysqli_real_escape_string($db_con,$obj->faq_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();

	$status_flag = 0;
	$sql_check_cat 		= "Select * from tbl_faqs where `faq_id` = '".$faq_id."' ";
	$result_check_cat 	= mysqli_query($db_con,$sql_check_cat) or die(mysqli_error($db_con));
	$num_check_cat          = mysqli_num_rows($result_check_cat);
	
	
	if($num_check_cat !=0)
	{
		$sql_update_status =" UPDATE tbl_faqs SET faq_status='".$curr_status."',faq_modified='".$datetime."',faq_modified_by='".$uid."' WHERE faq_id ='".$faq_id."' ";
		$res_update_status =mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
		if($res_update_status)
		{
			$response_array = array("Success"=>"Success","resp"=>"Status Updated");
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"FAQ Not Found.");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"FAQ Not Found.");
	}
	echo json_encode($response_array);	
}

//================================End :  Modified BY satish 16032017 update status==========//



if((isset($obj->delete_faq)) == "1" && isset($obj->delete_faq))
{
	$response_array = array();		
	$ar_faq_id 		= $obj->batch;
	$del_flag 		= 0; 
	if(!empty($ar_faq_id))
	{
		foreach($ar_faq_id as $faq_id)	
		{
				$sql_delete_faq ="DELETE FROM tbl_faqs WHERE faq_id ='".$faq_id."' ";
				mysqli_query($db_con,$sql_delete_faq) or die(mysqli_error($db_con));
		}
		
		$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");
	}		
	echo json_encode($response_array);	
}

//==========================================================================================================
//====================End : Done by satish 15032017 for update_category=====================================
//==========================================================================================================





if((isset($obj->load_faq)) == "1" && isset($obj->load_faq))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= mysqli_real_escape_string($db_con,$obj->page);	
	$per_page		= mysqli_real_escape_string($db_con,$obj->row_limit);
	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);	
	$cat_parent		= mysqli_real_escape_string($db_con,$obj->cat_parent);		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT tf.*, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tf.faq_created_by) as faq_by_created,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tf.faq_modified_by) as faq_by_modified ";
		$sql_load_data  .= " FROM tbl_faqs as tf  WHERE 1=1  ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND tf.faq_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (faq_ques like '%".$search_text."%' or faq_ans like '%".$search_text."%' ";
			$sql_load_data .= " or faq_created like '%".$search_text."%' or faq_modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY tf.faq_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$feq_data = "";			
			
			$feq_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$feq_data .= '<thead>';
    	  	$feq_data .= '<tr>';
         	$feq_data .= '<th class="center-text">Sr No.</th>';
			$feq_data .= '<th class="center-text">Faq Id</th>';
			$feq_data .= '<th style="width:20%">Faq Question</th>';
			$feq_data .= '<th style="width:25%;text-align:center">Faq Answer</th>';
			$feq_data .= '<th style="width:6%;text-align:center">Sort Order</th>';
		    $dis = checkFunctionalityRight("view_faq.php",3);
			if($dis)
			{
				$feq_data .= '<th class="center-text">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_faq.php",1);
			if($edit)
			{
				$feq_data .= '<th class="center-text">Edit</th>';
			}
			$del = checkFunctionalityRight("view_faq.php",2);
			if($del)
			{
				$feq_data .= '<th class="center-text">';
				$feq_data .= '<div class="center-text">';
				$feq_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$feq_data .= '</tr>';
      		$feq_data .= '</thead>';
      		$feq_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$feq_data .= '<tr>';				
				$feq_data .= '<td class="center-text">'.++$start_offset.'</td>';				
				$feq_data .= '<td class="center-text">'.$row_load_data['faq_id'].'</td>';
				$feq_data .= '<td style="width:20%">';
				$feq_data .= '<div class="center-text" >';			 	
				$feq_data .= '<input type="button" value="'.ucwords(substr($row_load_data['faq_ques'], 0, 50)).'..." class="btn-link" id="'.$row_load_data['faq_id'].'" onclick="addMoreFaq(this.id,\'view\');">';
				$feq_data .= '<i class="icon-chevron-down" id="'.$row_load_data['faq_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['faq_id'].'cat_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$feq_data .= '</div>';				
				$feq_data .= '<div style="display:none" id="'.$row_load_data['faq_id'].'cat_div">';
				if($row_load_data['faq_by_created'] == "")
				{
					$feq_data .= '<b>Created By:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$feq_data .= '<span><b>Created By:</b>'.$row_load_data['faq_by_created'].'</span><br>';
				}
				if($row_load_data['faq_created'] == "" || $row_load_data['faq_created'] == "0000-00-00 00:00:00")
				{
					$feq_data .= '<b>Created :</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$feq_data .= '<span><b>Created :</b>'.$row_load_data['faq_created'].'</span><br>';
				}				
				if($row_load_data['faq_by_modified'] == "")
				{
					$feq_data .= '<b>Modified By:</b><span style="color:#f00;">Not Available</span><br>';					
				}
				else
				{
					$feq_data .= '<span><b>Modified By:</b>'.$row_load_data['faq_by_modified'].'</span><br>';
				}
				if($row_load_data['faq_modified'] == "" || $row_load_data['faq_modified'] == "0000-00-00 00:00:00")
				{
					$feq_data .= '<b>Modified:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$feq_data .= '<span><b>Modified:</b>'.$row_load_data['faq_modified'].'</span><br>';								
				}
				$feq_data .= '</div>';
				$feq_data .= '</td>';								
				$feq_data .= '<td style="width:20%" class="center-text">';
				$feq_data .= $row_load_data['faq_ans'];
				$feq_data .= '</td>';
				
				
								
				$feq_data .= '<td class="center-text">';				
									
				$feq_data .= '<div class="center-text">';
				$feq_data .= '<input type="text" value="'.$row_load_data['faq_sort_order'].'" onchange="changesortorder(this.value,'.$row_load_data['faq_id'].')">';					
				$feq_data .= '</div>';															
				$feq_data .= '</td>';
				
				$dis = checkFunctionalityRight("view_faq.php",3);
				if($dis)			
				{
					$feq_data .= '<td class="center-text">';	
					if($row_load_data['faq_status'] == 1)
					{
						$feq_data .= '<input type="button" value="Active" id="'.$row_load_data['faq_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$feq_data .= '<input type="button" value="Inactive" id="'.$row_load_data['faq_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$feq_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_faq.php",1);				
				if($edit)
				{
					$feq_data .= '<td class="center-text">';
					if($row_load_data['cat_name'] !="none")
					{
					$feq_data .= '<input type="button" value="Edit" id="'.$row_load_data['faq_id'].'" class="btn-warning" onclick="addMoreFaq(this.id,\'edit\');">';
					}
					else 
					{
							$feq_data .='-';
					}
					$feq_data .='</td>';
				}
				$del = checkFunctionalityRight("view_faq.php",2);
				if($del)				
				{    
				  
					$feq_data .= '<td>';
					
					$feq_data .=' <div class="controls" align="center">';					
					$feq_data .= '		<input type="checkbox" value="'.$row_load_data['faq_id'].'" id="batch'.$row_load_data['faq_id'].'" name="batch'.$row_load_data['faq_id'].'" class="css-checkbox batch">';
					$feq_data .= '		<label for="batch'.$row_load_data['faq_id'].'" class="css-label"></label>';
					$feq_data .= '	</div>';
					
					$feq_data .= '	</td>';										
				}
	          	$feq_data .= '</tr>';															
			}	
      		$feq_data .= '</tbody>';
      		$feq_data .= '</table>';	
			$feq_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$feq_data);					
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












if((isset($obj->change_sort_order)) == "1" && isset($obj->change_sort_order))
{
	$faq_id					= mysqli_real_escape_string($db_con,$obj->faq_id);
	$order				=  $obj->new_order;
	$response_array 		= array();		
	
	$sql_check_self_order	= " SELECT * from tbl_faqs WHERE faq_id = '".$faq_id."' ";
	$result_check_self_order= mysqli_query($db_con,$sql_check_self_order) or die(mysqli_error($db_con));	
	$row_check_self_order	= mysqli_fetch_array($result_check_self_order);
	
	$faq_type			    = $row_check_self_order['faq_type'];
	$sort_order				= $row_check_self_order['faq_sort_order'];
	
	
	
	
	if($sort_order < $order)
	{
			$sql_check_order  ="SELECT  * FROM tbl_faqs WHERE faq_type ='".$faq_type ."' AND faq_sort_order >".$sort_order."";
			$sql_check_order .=" AND faq_sort_order <= ".$order." ";
			
			$res_check_order  =mysqli_query($db_con,$sql_check_order) or die(mysqli_error($db_con));
			while($row =mysqli_fetch_array($res_check_order))
			{
				$new_order =$row['faq_sort_order']-1;
				$sql_update_order 		= " UPDATE `tbl_faqs` SET `faq_sort_order`= '".$new_order."'  WHERE `faq_id` = '".$row['faq_id']."' ";
	            $result_update_order 	= mysqli_query($db_con,$sql_update_order) or die($mysqli_error($db_con));
			}
			
		$sql_update_order 		= " UPDATE `tbl_faqs` SET `faq_sort_order`= '".$order."'  WHERE `faq_id` = '".$faq_id."' ";
	    $result_update_order 	= mysqli_query($db_con,$sql_update_order) or die($mysqli_error($db_con));	
		if($result_update_order)
		{  
		  
			$response_array = array("Success"=>"Success","resp"=>"Updation Success.");
		}
		 else 
		{
			$response_array = array("Success"=>"fail","resp"=>"Updation Failed.");
		}
	}
	else
	{
		    $sql_check_order  ="SELECT  * FROM tbl_faqs WHERE faq_type ='".$faq_type ."' AND faq_sort_order >='".$order."'";
			$sql_check_order .=" AND faq_sort_order <= '".$sort_order."' ";
			$res_check_order  =mysqli_query($db_con,$sql_check_order) or die(mysqli_error($db_con));
			while($row =mysqli_fetch_array($res_check_order))
			{
				$new_order =$row['faq_sort_order']+1;
				$sql_update_order 		= " UPDATE `tbl_faqs` SET `faq_sort_order`= '".$new_order."'  WHERE `faq_id` = '".$row['faq_id']."' ";
	            $result_update_order 	= mysqli_query($db_con,$sql_update_order) or die($mysqli_error($db_con));
			}
			
		$sql_update_order 		= " UPDATE `tbl_faqs` SET `faq_sort_order`= '".$order."'  WHERE `faq_id` = '".$faq_id."' ";
	    $result_update_order 	= mysqli_query($db_con,$sql_update_order) or die($mysqli_error($db_con));
		
		if($result_update_order)
		{  
		  
			$response_array = array("Success"=>"Success","resp"=>"Updation Success.");
		}
		 else 
		{
			$response_array = array("Success"=>"fail","resp"=>"Updation Failed.");
		}
	}
		
	
	
	
	
	
	echo json_encode($response_array);	
}


?>