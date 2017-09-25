<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];








if((isset($obj->load_add_test_part)) != "" && isset($obj->load_add_test_part))
{
	$testi_id 	= $obj->testi_id;
	$req_type 	= $obj->req_type;		
	
	 if($testi_id != "" && $req_type == "view" || $req_type == "edit")
	{
		$sql_faq_data 		= " SELECT * FROM tbl_testimonials WHERE `testi_id` = '".$testi_id."' ";
		$res_faq_data 	    = mysqli_query($db_con,$sql_faq_data) or die(mysqli_error($db_con));
		$row_faq_data		= mysqli_fetch_array($res_faq_data);		
	}		
	$data = '';
		if($testi_id != "" && $req_type == "edit")
		{
			$data = '<input type="hidden" name="testi_id" id="testi_id" value="'.$row_faq_data['testi_id'].'">';
			$data .= '<input type="hidden" name="update_tesimonial" id="update_tesimonial" value="1">';
		}	
		else
		{
			$data = '<input type="hidden" name="insert_tesimonial" id="insert_tesimonial" value="1">';
		}
			
		$disabled="";
		if($req_type=="view")
		{
			$disabled=" disabled " ;
		}
				
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input  '.$disabled.' type="text" id="name" name="name" placeholder="Name "  style="width:90%"  rows="4" class="input-large" data-rule-required="true" ' ;
		if($req_type == "view" || $req_type == "edit")
		{
			$data .= ' value="'.$row_faq_data['name'].'"';	
		}
		$data .= ' />';
		$data .= '</div>';
		$data .= '</div> <!-- Faq Ques -->';
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Content<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<textarea '.$disabled.' placeholder="Content  " type="text" id="content" name="content"  style="width:90%"  rows="4" class="input-large" data-rule-required="true" >' ;
		if($req_type == "view" || $req_type == "edit")
		{
			$data .= $row_faq_data['content'];	
		}
		$data .= '</textarea>';
		$data .= '</div>';
		$data .= '</div> <!-- Faq Ques -->';
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="radio" class="control-label">Status<span style="color:#F00;font-size:20px;">*</span></label>';
		$data .= '<div class="controls">';
		
		
			$data .= '<input type="radio" name="status" value="1" class="css-radio" data-rule-required="true" ';
			$dis	= checkFunctionalityRight("view_testimonials.php",3);
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if($row_faq_data['status'] == 1)
			{
				$data .= 'checked';
			}
			$data .= '> Active ';
			$data .= ' <input type="radio" name="status" value="0" class="css-radio" data-rule-required="true"';
			if(!$dis)
			{
				$data .= ' disabled="disabled" ';
			}
			if(isset($row_faq_data['status']) && $row_faq_data['status']== 0)
			{
				$data .= 'checked';
			}
			$data .= '>Inactive';
			
		$data .= ' <label for="radiotest" class="css-label"></label>';
		$data .= ' <label name = "radiotest" ></label>';
		$data .= '</div>';
		$data .= '</div><!--Status-->';				
		$data .= '<div class="form-actions">';
		if($testi_id == "" && $req_type == "add")
		{
			$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Testimonial</button>';			
		}
		elseif($testi_id != "" && $req_type == "edit")
		{
			$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Testimonial</button>';
		}
		
		$data .= '</div> <!-- Save and cancel -->';
	
	$response_array = array("Success"=>"Success","resp"=>$data);
	echo json_encode($response_array);
}



if((isset($_POST['insert_tesimonial'])) == "1" && isset($_POST['insert_tesimonial']))
{
	$name     = mysqli_real_escape_string($db_con,$_POST['name']);
	$content  = mysqli_real_escape_string($db_con,$_POST['content']);
	$status    = $_POST['status'];
	
	if($name !="" && $content!="" && $status !="")
	{
		$sql_get_order =" SELECT sort_order FROM tbl_testimonials  ORDER BY sort_order DESC LIMIT 1";
		$res_get_order = mysqli_query($db_con, $sql_get_order) or die(mysqli_error($db_con));
		if(mysqli_num_rows($res_get_order)==0)
		{
			$sort_order =1;
		}
		else
		{
			$row_get_order =mysqli_fetch_array($res_get_order);
			$sort_order    =$row_get_order['sort_order'] + 1;
		}
		$sql_insert_faq	 = " INSERT INTO `tbl_testimonials`(`name`, `content`, `status`,sort_order,created_date,created_by ) ";
		$sql_insert_faq	.= " VALUES ('".$name."', '".$content."', '".$status."','".$sort_order."','".$datetime."','".$uid."') ";
		$res_insert_faq	 = mysqli_query($db_con, $sql_insert_faq) or die(mysqli_error($db_con));
		if($res_insert_faq)
		{
			$response_array = array("Success"=>"Success","resp"=>"Testimonial Added Successfully");
		}
		else
		{ 
		     $response_array = array("Success"=>"fail","resp"=>"Testimonial Not Added");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Testimonial Name and Content is required");
	}
	echo json_encode($response_array);		
}
//==========================================================================================================
//====================Start : Done by satish 15032017 for update_category=====================================
//==========================================================================================================



if((isset($_POST['update_tesimonial'])) == "1" && isset($_POST['update_tesimonial']))
{
	$name      = mysqli_real_escape_string($db_con,$_POST['name']);
	$content   = mysqli_real_escape_string($db_con,$_POST['content']);
	$status    = $_POST['status'];
	$testi_id  = $_POST['testi_id'];
	
	if($name !="" && $content!="" && $status !="" && $status !="")
	{
		$sql_update_faq	 = " UPDATE tbl_testimonials SET `name`='".$name."', `content`='".$content."', `status`='".$status."',";
		$sql_update_faq	.= " modified_date='".$datetime."',modified_by='".$uid."' WHERE testi_id ='".$testi_id."'";
		$res_update_faq	 = mysqli_query($db_con, $sql_update_faq) or die(mysqli_error($db_con));
		if($res_update_faq)
		{
			$response_array = array("Success"=>"Success","resp"=>"Testimonial Updated Successfully");
		}
		else
		{ 
		     $response_array = array("Success"=>"fail","resp"=>"Testimonial Not Updated");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Testimonial name and Content is required".$faq_id);
	}
	
	
	echo json_encode($response_array);		
}

//================================Strat :  Modified BY satish 16032017 update status==========//

if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$testi_id					= mysqli_real_escape_string($db_con,$obj->testi_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();

	$status_flag = 0;
	$sql_check_cat 		= "Select * from tbl_testimonials where `testi_id` = '".$testi_id."' ";
	$result_check_cat 	= mysqli_query($db_con,$sql_check_cat) or die(mysqli_error($db_con));
	$num_check_cat          = mysqli_num_rows($result_check_cat);
	
	
	if($num_check_cat !=0)
	{
		$sql_update_status =" UPDATE tbl_testimonials SET status='".$curr_status."',modified_date='".$datetime."',modified_by='".$uid."' WHERE testi_id ='".$testi_id."' ";
		$res_update_status =mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
		if($res_update_status)
		{
			$response_array = array("Success"=>"Success","resp"=>"Status Updated");
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Status Not Updated.");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Testimonial Not Found.");
	}
	echo json_encode($response_array);	
}

//================================End :  Modified BY satish 16032017 update status==========//



if((isset($obj->delete_testimonial)) == "1" && isset($obj->delete_testimonial))
{
	$response_array = array();		
	$ar_test_ids		= $obj->batch;
	$del_flag 		= 0; 
	if(!empty($ar_test_ids))
	{
		foreach($ar_test_ids as $test_id)	
		{
				$sql_delete_testimonial ="DELETE FROM tbl_testimonials WHERE testi_id ='".$test_id."' ";
				mysqli_query($db_con,$sql_delete_testimonial) or die(mysqli_error($db_con));
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
			
		$sql_load_data  = " SELECT tt.*, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tt.created_by) as test_by_created,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tt.modified_by) as test_by_modified ";
		$sql_load_data  .= " FROM tbl_testimonials as tt  WHERE 1=1  ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND tt.test_by_created='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (tt.name like '%".$search_text."%' or tt.content like '%".$search_text."%' ";
			$sql_load_data .= " or created_date like '%".$search_text."%' or modified_date like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY tt.testi_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$feq_data = "";			
			
			$feq_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$feq_data .= '<thead>';
    	  	$feq_data .= '<tr>';
         	$feq_data .= '<th class="center-text">Sr No.</th>';
			$feq_data .= '<th class="center-text">Name</th>';
			$feq_data .= '<th style="width:20%"  class="center-text">Content</th>';
			$feq_data .= '<th style="width:6%;text-align:center">Sort Order</th>';
		    $dis = checkFunctionalityRight("view_testimonials.php",3);
			if($dis)
			{
				$feq_data .= '<th class="center-text">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_testimonials.php",1);
			if($edit)
			{
				$feq_data .= '<th class="center-text">Edit</th>';
			}
			$del = checkFunctionalityRight("view_testimonials.php",2);
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
				$feq_data .= '<td class="center-text">'.$row_load_data['name'].'</td>';
				$feq_data .= '<td style="width:20%">';
				$feq_data .= '<div class="center-text" >';			 	
				$feq_data .= '<input type="button" value="'.ucwords(substr($row_load_data['content'], 0, 50)).'..." class="btn-link" id="'.$row_load_data['testi_id'].'" onclick="addMoreTestimonial(this.id,\'view\');">';
				$feq_data .= '<i class="icon-chevron-down" id="'.$row_load_data['testi_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['testi_id'].'cat_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$feq_data .= '</div>';				
				$feq_data .= '<div style="display:none" id="'.$row_load_data['testi_id'].'cat_div">';
				if($row_load_data['test_created_by'] == "")
				{
					$feq_data .= '<b>Created By:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$feq_data .= '<span><b>Created By:</b>'.$row_load_data['test_created_by'].'</span><br>';
				}
				if($row_load_data['created_date'] == "" || $row_load_data['created_date'] == "0000-00-00 00:00:00")
				{
					$feq_data .= '<b>Created :</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$feq_data .= '<span><b>Created :</b>'.$row_load_data['created_date'].'</span><br>';
				}				
				if($row_load_data['test_by_modified'] == "")
				{
					$feq_data .= '<b>Modified By:</b><span style="color:#f00;">Not Available</span><br>';					
				}
				else
				{
					$feq_data .= '<span><b>Modified By:</b>'.$row_load_data['test_by_modified'].'</span><br>';
				}
				if($row_load_data['modified_date'] == "" || $row_load_data['modified_date'] == "0000-00-00 00:00:00")
				{
					$feq_data .= '<b>Modified:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$feq_data .= '<span><b>Modified:</b>'.$row_load_data['modified_date'].'</span><br>';								
				}
				$feq_data .= '</div>';
			    $feq_data .= '<td class="center-text">';				
									
				$feq_data .= '<div class="center-text">';
				$feq_data .= '<input type="text" value="'.$row_load_data['sort_order'].'" onchange="changesortorder(this.value,'.$row_load_data['testi_id'].')">';					
				$feq_data .= '</div>';															
				$feq_data .= '</td>';
				
				$dis = checkFunctionalityRight("view_testimonials.php",3);
				if($dis)			
				{
					$feq_data .= '<td class="center-text">';	
					if($row_load_data['status'] == 1)
					{
						$feq_data .= '<input type="button" value="Active" id="'.$row_load_data['testi_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$feq_data .= '<input type="button" value="Inactive" id="'.$row_load_data['testi_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$feq_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_testimonials.php",1);				
				if($edit)
				{
					$feq_data .= '<td class="center-text">';
					
					$feq_data .= '<input type="button" value="Edit" id="'.$row_load_data['testi_id'].'" class="btn-warning" onclick="addMoreTestimonial(this.id,\'edit\');">';
					
					$feq_data .='</td>';
				}
				$del = checkFunctionalityRight("view_testimonials.php",2);
				if($del)				
				{    
				  
					$feq_data .= '<td>';
					
					$feq_data .=' <div class="controls" align="center">';					
					$feq_data .= '		<input type="checkbox" value="'.$row_load_data['testi_id'].'" id="batch'.$row_load_data['testi_id'].'" name="batch'.$row_load_data['testi_id'].'" class="css-checkbox batch">';
					$feq_data .= '		<label for="batch'.$row_load_data['testi_id'].'" class="css-label"></label>';
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
	$testi_id			    = mysqli_real_escape_string($db_con,$obj->testi_id);
	$order				    =  $obj->new_order;
	$response_array 		= array();		
	
	$sql_check_self_order	= " SELECT * from tbl_testimonials WHERE testi_id = '".$testi_id."' ";
	$result_check_self_order= mysqli_query($db_con,$sql_check_self_order) or die(mysqli_error($db_con));	
	$row_check_self_order	= mysqli_fetch_array($result_check_self_order);
	
	
	$sort_order				= $row_check_self_order['sort_order'];
	
	
	
	
	if($sort_order < $order)
	{
			$sql_check_order  ="SELECT  * FROM tbl_testimonials WHERE  sort_order >".$sort_order."";
			$sql_check_order .=" AND sort_order <= ".$order." ";
			
			$res_check_order  =mysqli_query($db_con,$sql_check_order) or die(mysqli_error($db_con));
			while($row =mysqli_fetch_array($res_check_order))
			{
				$new_order =$row['sort_order']-1;
				$sql_update_order 		= " UPDATE `tbl_testimonials` SET `sort_order`= '".$new_order."'  WHERE `testi_id` = '".$row['testi_id']."' ";
	            $result_update_order 	= mysqli_query($db_con,$sql_update_order) or die($mysqli_error($db_con));
			}
			
		$sql_update_order 		= " UPDATE `tbl_testimonials` SET `sort_order`= '".$order."'  WHERE `testi_id` = '".$testi_id."' ";
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
		    $sql_check_order  ="SELECT  * FROM tbl_testimonials WHERE  sort_order >='".$order."'";
			$sql_check_order .=" AND sort_order <= '".$sort_order."' ";
			$res_check_order  =mysqli_query($db_con,$sql_check_order) or die(mysqli_error($db_con));
			while($row =mysqli_fetch_array($res_check_order))
			{
				$new_order =$row['sort_order']+1;
				$sql_update_order 		= " UPDATE `tbl_testimonials` SET `sort_order`= '".$new_order."'  WHERE `testi_id` = '".$row['testi_id']."' ";
	            $result_update_order 	= mysqli_query($db_con,$sql_update_order) or die($mysqli_error($db_con));
			}
			
		$sql_update_order 		= " UPDATE `tbl_testimonials` SET `sort_order`= '".$order."'  WHERE `testi_id` = '".$testi_id."' ";
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