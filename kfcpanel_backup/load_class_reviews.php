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
	$review_class_id	= mysqli_real_escape_string($db_con,$obj->review_class_id);	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
		
		$sql_load_data = " SELECT  tcr.*,tc.class_name,tct.cust_fname,tct.cust_lname,(SELECT fullname FROM tbl_cadmin_users WHERE id =tcr.modified_by ) as fullname ";
		$sql_load_data .= " from tbl_class_reviews as tcr ";
		$sql_load_data .= " INNER JOIN tbl_class as tc ON tcr.class_id =tc.class_id  ";
		$sql_load_data .= " INNER JOIN tbl_customer as tct ON tcr.user_id =tct.cust_id  ";
		//$sql_load_data .= " INNER JOIN tbl_cadmin_users as tcu ON tcr.modified_by =tcu.id  ";
		$sql_load_data .= "  where 1=1 ";
		
		
		if($review_customer_id!="")
		{
			$sql_load_data  .= " AND tcr.class_id='".$review_class_id."' ";
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
		$sql_load_data .=" ORDER BY class_reviewid LIMIT $start, $per_page ";
		
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));
		if(strcmp($data_count,"0") !== 0)
		{		
			$review_data = "";			
			$review_data .= '<table id="tbl_user1" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$review_data .= '<thead>';
    	  	$review_data .= '<tr>';
         	$review_data .= '<th style="text-align:center">Sr No.</th>';
			$review_data .= '<th style="text-align:center">Review Title</th>';
			$review_data .= '<th style="text-align:center"> Class Name</th>';
			$review_data .= '<th style="text-align:center"> User</th>';			
			$review_data .= '<th style="text-align:center"> Modified</th>';
			$review_data .= '<th style="text-align:center"> Modified by </th>';
			$review_data .= '<th style="text-align:center">Star Ratings</th>';
			$dis = checkFunctionalityRight("view_class_reviews.php",3);
			if($dis)
			{			
				$review_data .= '<th style="text-align:center">Status</th>';						
			}
			$del = checkFunctionalityRight("view_class_reviews.php",2);
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
				$review_data .= '<td style="text-align:center;width:40%"><div>'.$row_load_data['review'].'';				
				$review_data .= '<i class="icon-chevron-down" id="'.$row_load_data['class_reviewid'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['class_reviewid'].'review_id\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i></div>';
				$review_data .= '<br><div id="'.$row_load_data['class_reviewid'].'review_id" style="display:none;">';
				
				$review_data .='<div><textarea name="comment'.$row_load_data['class_reviewid'].'" id="comment'.$row_load_data['class_reviewid'].'" class="input-xlarge" rows="2" style="text-align:left;margin:10px;width:90%">'.$row_load_data['review'].'</textarea><br><input type="button" name="cmnt_btn'.$row_load_data['review_id'].'" id="cmnt_btn'.$row_load_data['class_reviewid'].'" class="btn-warning" value="Comment" style="width:20%;margin:0px 0px 20px 370px"  onclick="updateComment(\''.$row_load_data['class_reviewid'].'\');" ></div>';
				$review_data .= '</div>';				
				$review_data .= '</td>';
				
				
				$review_data .= '<td style="text-align:center">'.$row_load_data['class_name'].'</td>';
				$review_data .= '<td style="text-align:center">'.$row_load_data['cust_fname'].' '.$row_load_data['cust_lname'].'</td>';
				if($row_load_data['modified_date'] == "0000-00-00 00:00:00" || $row_load_data['modified_date'] == "")
				{
					$review_data .= '<td style="text-align:center">Not Modified</td>';
				}
				else
				{
					$review_data .= '<td style="text-align:center">'.$row_load_data['modified_date'].'</td>';
				}
				$review_data .= '<td style="text-align:center">'.$row_load_data['fullname'].'</td>';
				$review_data .= '<td style="text-align:center;width:10%"><div>';
				$review_data .=$row_load_data['stars'];
				$review_data .= '</div></td>';
				$review_data .= '</td>';
				$dis = checkFunctionalityRight("view_class_reviews.php",3);
				if($dis)
				{				
					$review_data .= '<td style="text-align:center">';						
					if($row_load_data['status'] == 1)
					{
						$review_data .= '<input type="button" onclick="changeStatus('.$row_load_data['class_reviewid'].',0);" value="Active" id="'.$row_load_data['class_reviewid'].'" class="btn-success" >';
                   	}
					else if($row_load_data['status'] == 0)
					{
						$review_data .= '<input type="button" onclick="changeStatus('.$row_load_data['class_reviewid'].',1);"  value="Inactive" id="'.$row_load_data['class_reviewid'].'" class="btn-danger" >';
					} 
					else if($row_load_data['status'] == 2)
					{
						$review_data .= '<input type="button" value="Moderate" id="'.$row_load_data['class_reviewid'].'" class="btn-warning" >';
					} 
										
					$review_data .= '</td>';	
		      		$review_data .= '<script type="text/javascript">';
      							
      				$review_data .= '</script>';																				
				}
				$del = checkFunctionalityRight("view_class_reviews.php",2);
				if($del)
				{					
					$review_data .= '<td><div class="controls" align="center">';
					$review_data .= '<input type="checkbox" value="'.$row_load_data['class_reviewid'].'" id="batch'.$row_load_data['class_reviewid'].'" name="batch'.$row_load_data['class_reviewid'].'" class="css-checkbox batch">';
					$review_data .= '<label for="batch'.$row_load_data['class_reviewid'].'" class="css-label"></label>';
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
	$sql_update_status 		= " UPDATE `tbl_class_reviews` SET `status`= '".$curr_status."' ,`modified_date` = '".$datetime."' ,`modified_by` = '".$uid."' WHERE `class_reviewid` like '".$review_id."' ";
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
		$sql_delete_review_error	= " DELETE FROM `tbl_class_reviews` WHERE `class_reviewid`='".$review_id."'";
		
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



if((isset($obj->update_comment)) == "1" && isset($obj->update_comment))
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
		$sql_update_rewiew =" UPDATE tbl_class_reviews SET review='".$admin_comment."',modified_by='".$uid."',modified_date='".$datetime."'";
		$sql_update_rewiew .=" WHERE class_reviewid='".$review_id."'";
		$sql_update_review =mysqli_query($db_con,$sql_update_rewiew) or die(mysqli_error($db_con));
		if($sql_update_review)
		{
			$response_array = array("Success"=>"Success","resp"=>"Review Successfully Updated");
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Review Not Updated");
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