<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];
$admin_fullname			= $_SESSION['panel_user']['fullname'];
if((isset($obj->load_reviews)) == "1" && isset($obj->load_reviews))
{
	$response_array 	= array();	
	$start_offset   	= 0;
	$page 				= mysqli_real_escape_string($db_con,$obj->page);	
	$per_page			= mysqli_real_escape_string($db_con,$obj->row_limit);
	$search_text		= mysqli_real_escape_string($db_con,$obj->search_text);	
	$cat_parent			= mysqli_real_escape_string($db_con,$obj->cat_parent);		
	$review_star		= mysqli_real_escape_string($db_con,$obj->review_star);	
	$review_customer_id	= mysqli_real_escape_string($db_con,$obj->review_customer_id);	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
		
		$sql_load_data = " SELECT  `review_id`,`review_title`, `review_content`, `review_cust_id`, `review_prod_id`, `review_thread_id`,";
		$sql_load_data .= " `review_star_rating`, `review_status`, `review_created`, `review_modified`, `review_modified_by`,";
		$sql_load_data .= " (SELECT `prod_title` FROM `tbl_products_master` WHERE `prod_id`=review_prod_id) as review_product_name ";
		$sql_load_data .= " from tbl_review_master where review_thread_id='1' ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND review_created_by='".$uid."' ";
		}
		if($review_customer_id!="")
		{
			$sql_load_data  .= " AND review_cust_id='".$review_customer_id."' ";
		}
		if($review_star!="")
		{
			$sql_load_data  .= " AND review_star_rating='".$review_star."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (review_title like '%".$search_text."%' or review_content like '%".$search_text."%' ";
			$sql_load_data .= " or review_created like '%".$search_text."%' or review_modified like '%".$search_text."%') ";	
		}
		$data_count	= dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY review_id LIMIT $start, $per_page ";
		
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
		if(strcmp($data_count,"0") !== 0)
		{		
			$review_data = "";			
			$review_data .= '<table id="tbl_user1" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$review_data .= '<thead>';
    	  	$review_data .= '<tr>';
         	$review_data .= '<th style="text-align:center">Sr No.</th>';
			$review_data .= '<th style="text-align:center">Review Title</th>';
			$review_data .= '<th style="text-align:center"> Product Name</th>';
			$review_data .= '<th style="text-align:center"> Created</th>';			
			$review_data .= '<th style="text-align:center"> Modified</th>';
			$review_data .= '<th style="text-align:center"> Modified by </th>';
			$review_data .= '<th style="text-align:center">Star Ratings</th>';
			$dis = checkFunctionalityRight("view_review.php",3);
			if($dis)
			{			
				$review_data .= '<th style="text-align:center">Status</th>';						
			}
			$del = checkFunctionalityRight("view_review.php",2);
			if($del)
			{			
				$review_data .= '<th style="text-align:center">';
				$review_data .= '<div style="text-align:center">';
				$review_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/>';
				$review_data .= '</div></th>';
			}			
			$review_data .= '</tr>';
      		$review_data .= '</thead>';
     		$review_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$review_data .= '<tr>';				
				$review_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$review_data .= '<td style="text-align:center;width:40%"><div>'.$row_load_data['review_title'].'';				
				$review_data .= '<i class="icon-chevron-down" id="'.$row_load_data['review_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['review_id'].'review_id\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i></div>';
				$review_data .= '<br><div id="'.$row_load_data['review_id'].'review_id" style="display:none;">';
				$review_data .= '<div id="commentbox1" class="input-xlarge" style="height:150px;width:94%;overflow-y:scroll;background-color:#CCC;padding:3%;">';				
				$sql_get_review_content="SELECT * FROM `tbl_review_master` WHERE  `review_prod_id`='".$row_load_data['review_prod_id']."' and `review_title`='".$row_load_data['review_title']."' ";
				$sql_get_review_row=mysqli_query($db_con,$sql_get_review_content);
				while($get_review_content=mysqli_fetch_array($sql_get_review_row))
				{					
					$sql_get_customer_name    = " SELECT * from tbl_customer where cust_id ='".$get_review_content['review_cust_id']."' "; 
					$result_get_customer_name = mysqli_query($db_con,$sql_get_customer_name);
					$row_get_customer_name	  = mysqli_fetch_array($result_get_customer_name);	
					if(($get_review_content['review_created_by']) ==0)
					{
						$review_data .='<div align="left"><b>'.ucwords($row_get_customer_name['cust_fname'].' '.$row_get_customer_name['cust_lname']).':&nbsp;</b>'.$get_review_content['review_content'];
						if($get_review_content['review_thread_id']!=1)
						{
							if($get_review_content['review_status']==0)	
							{
								$review_data .='<div style="float:right;"><input type="button" value="Inactive" id="'.$get_review_content['review_content'].'" class="btn-danger" onclick="ThreadReplyStatus(this.id,1);" ></div>';
							}
							else if($get_review_content['review_status']==1)
							{
								$review_data .='<div style="float:right;"><input type="button" value="Active" id="'.$get_review_content['review_content'].'" class="btn-success" onclick="ThreadReplyStatus(this.id,0);" ></div>';
							}							
						}
						$review_data .='<div style="clear:both;"></div> ';
						$review_data .='</div><hr><br>';
					}
					else
					{
						$review_data .='<div align="right"><b>Posted By: </b>'.$admin_fullname.' on '.$get_review_content['review_created'].'<br><b>Admin:&nbsp;</b>'.$get_review_content['review_content'].'</div><hr><br>';
					}		
				}
				$review_data .= '</div>';	
				$review_data .='<div><textarea name="comment'.$row_load_data['review_id'].'" id="comment'.$row_load_data['review_id'].'" class="input-xlarge" rows="2" style="text-align:left;margin:10px;width:90%"></textarea><br><input type="button" name="cmnt_btn'.$row_load_data['review_id'].'" id="cmnt_btn'.$row_load_data['review_id'].'" class="btn-warning" value="Comment" style="width:20%;margin:0px 0px 20px 370px"  onclick="updateComment(\''.$row_load_data['review_id'].'\');" ></div>';
				$review_data .= '</div>';				
				$review_data .= '</td>';
				$review_data .= '<td style="text-align:center">'.$row_load_data['review_product_name'].'</td>';
				$review_data .= '<td style="text-align:center">'.$row_load_data['review_created'].'</td>';
				if($row_load_data['review_modified'] == "0000-00-00 00:00:00" || $row_load_data['review_modified'] == "")
				{
					$review_data .= '<td style="text-align:center">Not Modified</td>';
				}
				else
				{
					$review_data .= '<td style="text-align:center">'.$row_load_data['review_modified'].'</td>';
				}
				$review_data .= '<td style="text-align:center">'.$admin_fullname.'</td>';
				$review_data .= '<td style="text-align:center;width:10%"><div>';
				while($row_load_data['review_star_rating']--)
				{
					$review_data .=	'<i class="icon-star" style="font-size:x-large;color:#F90"></i>';
				}
				$review_data .= '</div></td>';
				$review_data .= '</td>';
				$dis = checkFunctionalityRight("view_review.php",3);
				if($dis)
				{				
					$review_data .= '<td style="text-align:center">';						
					if($row_load_data['review_status'] == 1)
					{
						$review_data .= '<input type="button" value="Active" id="'.$row_load_data['review_id'].'" class="btn-success" >';
                   	}
					else if($row_load_data['review_status'] == 0)
					{
						$review_data .= '<input type="button" value="Inactive" id="'.$row_load_data['review_id'].'" class="btn-danger" >';
					} 
					else if($row_load_data['review_status'] == 2)
					{
						$review_data .= '<input type="button" value="Moderate" id="'.$row_load_data['review_id'].'" class="btn-warning" >';
					} 
					$review_data .= '<select name="review_status'.$row_load_data['review_id'].'" id="review_status'.$row_load_data['review_id'].'" onChange="changeStatus(\''.$row_load_data['review_id'].'\');"  class = "select2-me input-medium">';
					$review_data .= '<option value="2" ';
					if($row_load_data['review_status'] == 2)
					{
						$review_data .= ' selected="selected"';
					}					
					$review_data .= '>Moderate</option>';
					$review_data .= '<option value="1" ';
					if($row_load_data['review_status'] == 1)
					{
						$review_data .= ' selected="selected"';
					}					
					$review_data .= '>Active</option>';
					$review_data .= '<option value="0" ';					
					if($row_load_data['review_status'] == 0)
					{
						$review_data .= ' selected="selected"';
					}										
					$review_data .= ' >In-Active</option>';
					$review_data .= '</select>';					
					$review_data .= '</td>';	
		      		$review_data .= '<script type="text/javascript">';
      				$review_data .= '$("#review_status'.$row_load_data['review_id'].'").select2();';				
      				$review_data .= '</script>';																				
				}
				$del = checkFunctionalityRight("view_review.php",2);
				if($del)
				{					
					$review_data .= '<td><div class="controls" align="center">';
					$review_data .= '<input type="checkbox" value="'.$row_load_data['review_id'].'" id="batch'.$row_load_data['review_id'].'" name="batch'.$row_load_data['review_id'].'" class="css-checkbox batch">';
					$review_data .= '<label for="batch'.$row_load_data['review_id'].'" class="css-label"></label>';
					$review_data .= '</div></td>';										
				}	       						
				$review_data .= '</tr>';
			}
			$review_data .= '</tbody>';
      		$review_data .= '</table>';	
			$review_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$review_data);
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No data");
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
	$review_id				= $obj->review_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();
	$sql_update_status 		= " UPDATE `tbl_review_master` SET `review_status`= '".$curr_status."' ,`review_modified` = '".$datetime."' ,`review_modified_by` = '".$uid."' WHERE `review_id` like '".$review_id."' ";
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
if((isset($obj->delete_rev)) == "1" && isset($obj->delete_rev))
{
	$ar_review_id 	= $obj->batch;
	$response_array = array();	
	$del_flag_error = 0; 
	foreach($ar_review_id as $review_id)	
	{				
		$sql_get_thread_details 	= "SELECT * from tbl_review_master where review_id ='".$review_id."'";
		$result_get_thread_details	= mysqli_query($db_con,$sql_get_thread_details) or die(mysqli_error($db_con));	
		$row_get_thread_details 	= mysqli_fetch_array($result_get_thread_details);
		
		$sql_delete_review_error	= " DELETE FROM `tbl_review_master` WHERE `review_title`='".$row_get_thread_details['review_title']."' and ";
		$sql_delete_review_error	.= " `review_cust_id`='".$row_get_thread_details['review_cust_id']."' and `review_star_rating`='".$row_get_thread_details['review_star_rating']."' ";
		$sql_delete_review_error	.= " and `review_prod_id`='".$row_get_thread_details['review_prod_id']."' ";
		$result_delete_review_error	= mysqli_query($db_con,$sql_delete_review_error) or die(mysqli_error($db_con));				
		if($result_delete_review_error)
		{
			$del_flag_error = 1;	
		}			
	}
	if($del_flag_error == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");
	}
	echo json_encode($response_array);	
}



if((isset($obj->add_comment)) == "1" && isset($obj->add_comment))
{
	$admin_comment 		= trim(mysqli_real_escape_string($db_con,$obj->admin_comment));
	$review_id 			= trim(mysqli_real_escape_string($db_con,$obj->review_id));	
	$response_array = array();		
	if($admin_comment=="")
	{
		$response_array = array("Success"=>"Success","resp"=>"Type something. You dumb !.");
	}
	else
	{
		$sql_get_review_id_row="Select * from tbl_review_master where review_id='".$review_id."'";
		$sql_get_review_row=mysqli_query($db_con,$sql_get_review_id_row);
		$sql_get_review_num_rows=mysqli_num_rows($sql_get_review_row);
		$get_review_row=mysqli_fetch_array($sql_get_review_row);
	
		$sql_get_all_review_rows="select * from tbl_review_master where review_title='".$get_review_row['review_title']."' and review_cust_id='".$get_review_row['review_cust_id']."' and review_prod_id='".$get_review_row['review_prod_id']."' ";
		$get_all_review_rows=mysqli_query($db_con,$sql_get_all_review_rows);		
		$max=0;
		while($get_review_rows=mysqli_fetch_array($get_all_review_rows))
		{
			$get_thread_id=$get_review_rows['review_thread_id'];
			if($get_thread_id > $max)
			$max=$get_thread_id;
		}
		$max++;
		$sql_insert_admin_review	= " INSERT INTO `tbl_review_master`(`review_title`, `review_content`, `review_cust_id`, `review_prod_id`, 							`review_star_rating`,";
		$sql_insert_admin_review	.= " `review_status`, `review_created`,`review_created_by`,`review_thread_id`) VALUES";
		$sql_insert_admin_review	.= " ('".$get_review_row['review_title']."','".$admin_comment."','".$get_review_row['review_cust_id']."','".$get_review_row['review_prod_id']."','".$get_review_row['review_star_rating']."','".$get_review_row['review_status']."','".$datetime."','".$uid."','".$max."')";
		$sql_admin_review_row=mysqli_query($db_con,$sql_insert_admin_review);
		if($sql_admin_review_row)
		{
			$response_array = array("Success"=>"Success","resp"=>"Comment Updated Successfully.");
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"$sql_insert_admin_review	");
		}				
	}
	echo json_encode($response_array);	
}
if((isset($obj->change_thread_status)) == "1" && isset($obj->change_thread_status))
{
	$review_content			= $obj->review_content;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();
	$sql_update_status 		= " UPDATE `tbl_review_master` SET `review_status`= '".$curr_status."' ,`review_modified` = '".$datetime."' ,`review_modified_by` = '".$uid."' WHERE `review_content` like '".$review_content."' ";
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