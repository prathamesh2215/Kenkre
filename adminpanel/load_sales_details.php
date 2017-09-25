<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
if((isset($obj->update_star_status)) == "1" && isset($obj->update_star_status))
{
	$org_id					= trim(mysqli_real_escape_string($db_con,$obj->org_id));
	$curr_status			= trim(mysqli_real_escape_string($db_con,$obj->curr_status));
	$response_array 		= array();
	$sql_update_status 		= " UPDATE `tbl_oraganisation_master` SET `org_star_status`= '".$curr_status."' ,`org_modified` = '".$datetime."' ,`org_modified_by` = '".$uid."' WHERE `org_id` like '".$org_id."' ";
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
if((isset($obj->add_comment)) == "1" && isset($obj->add_comment))
{
	$sm_orgid 		= trim(mysqli_real_escape_string($db_con,$obj->sm_orgid));
	$sm_empid 		= trim(mysqli_real_escape_string($db_con,$obj->sm_empid));	
	$sm_comment 	= trim(mysqli_real_escape_string($db_con,$obj->sm_comment));
	$sm_leadid 		= trim(mysqli_real_escape_string($db_con,$obj->sm_leadid));	
	$sm_file 	= "";
	$response_array = array();	
	if($sm_orgid != "" && $sm_empid != "" )	
	{				
		$sql_get_name			= "SELECT fullname,'".$datetime."' as todayDate1 FROM `tbl_cadmin_users` WHERE id = '".$uid."' ";
		$result_get_name 		= mysqli_query($db_con,$sql_get_name) or die(mysqli_error($db_con));
		$row_get_name			= mysqli_fetch_array($result_get_name);		
		
		$sql_get_lead_status 	= " SELECT `ls_name` FROM `tbl_lead_status` WHERE `ls_id` = '".$sm_leadid."' ";
		$result_get_lead_status = mysqli_query($db_con,$sql_get_lead_status) or die(mysqli_error($db_con));
		$row_get_lead_status 	= mysqli_fetch_array($result_get_lead_status);

		$lead_status			= $row_get_lead_status['ls_name'];	

		$sql_get_comment 		= " SELECT * FROM `tbl_sales_module` WHERE `sm_empid` = '".$sm_empid."' AND `sm_orgid` = '".$sm_orgid."'";
		$result_get_comment 	= mysqli_query($db_con,$sql_get_comment) or die(mysqli_error($db_con));
		$num_rows_get_comment 	= mysqli_num_rows($result_get_comment);	
		
		$sql_get_emp_name 		= "SELECT * FROM `tbl_employee_master` WHERE `emp_id` = '".$sm_empid."' ";
		$result_get_emp_name	= mysqli_query($db_con,$sql_get_emp_name) or die(mysqli_error($db_con));
		$row_get_emp_name 		= mysqli_fetch_array($result_get_emp_name);		
		
		$emp_name				= $row_get_emp_name['emp_name'];	
			
		if($num_rows_get_comment == 0)
		{									
			
			if($sm_comment != "")
			{
				$sm_comment 		= str_replace("No Comments","",$sm_comment);			
				$final_comment 		= "<hr/><b>".$row_get_name['fullname']."&nbsp;".$row_get_name['todayDate1'].": </b>".$sm_comment."";
			}
			else
			{
				$final_comment 		= "<hr/><b>".$row_get_name['fullname']."&nbsp;".$row_get_name['todayDate1'].": </b>Had word with <b>\"".ucwords($emp_name)."\"</b> and status updated to <b>\"".ucwords($lead_status)."\"</b>";
			}
						
			$sql_insert_comment 	= " INSERT INTO `tbl_sales_module`(`sm_empid`,`sm_orgid`,`sm_comment`,`sm_leadid`,";
			$sql_insert_comment 	.= "`sm_file`,`sm_created_by`,`sm_created`) VALUES ('".$sm_empid."','".$sm_orgid."',";
			$sql_insert_comment 	.= "'".$final_comment."','".$sm_leadid."','".$sm_file."','".$uid."','".$datetime."')";
			$result_insert_comment = mysqli_query($db_con,$sql_insert_comment) or die(mysqli_error($db_con)); 	
			if($result_insert_comment)
			{
				$response_array = array("Success"=>"Success","resp"=>"Comment Updated");		
			}
			else
			{		
				$response_array = array("Success"=>"fail","resp"=>"Comment not Updated");
			}
		}
		else
		{				
			$row_get_comment	= mysqli_fetch_array($result_get_comment);			
			if($sm_comment != "")
			{
				$sm_comment 		= str_replace("No Comments","",$sm_comment);			
				$final_comment 		= "<hr/><b>".$row_get_name['fullname']."&nbsp;".$row_get_name['todayDate1'].": </b>".$sm_comment."<br>".$row_get_comment['sm_comment'];
			}
			else
			{
				$final_comment 		= "<hr/><b>".$row_get_name['fullname']."&nbsp;".$row_get_name['todayDate1'].": </b>Had word with <b>\"".ucwords($emp_name)."\"</b> and status updated to <b>\"".ucwords($lead_status)."\"</b><br>".$row_get_comment['sm_comment'];
			}		
			
			$sql_update_comment = " UPDATE `tbl_sales_module` SET `sm_comment`='".$final_comment."',`sm_leadid` ";
			$sql_update_comment .= " = '".$sm_leadid."' WHERE `sm_empid` = '".$sm_empid."' AND `sm_orgid` = '".$sm_orgid."'";	
			$result_update_comment = mysqli_query($db_con,$sql_update_comment) or die(mysqli_error($db_con));
			if($result_update_comment)
			{
				$response_array = array("Success"=>"Success","resp"=>"Comment Updated");		
			}
			else
			{		
				$response_array = array("Success"=>"fail","resp"=>"Comment not Updated");
			}							
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Data not Available");		
	}
	echo json_encode($response_array);	
}
if((isset($obj->show_comment)) == "1" && isset($obj->show_comment))
{
	$org_id = trim(mysqli_real_escape_string($db_con,$obj->org_id));
	$emp_id = trim(mysqli_real_escape_string($db_con,$obj->emp_id));
	
	$response_array = array();	
	$sql_get_comment = " SELECT `sm_comment`,`sm_created`, (SELECT fullname FROM `tbl_cadmin_users` WHERE id = sm_created_by) as comment_by ";
	$sql_get_comment .= " FROM `tbl_sales_module` WHERE `sm_empid` = '".$emp_id."' AND `sm_orgid` = '".$org_id."' ";
	if(strcmp($utype,'1')!==0)
	{
		$sql_get_comment  .= " AND sm_created_by ='".$uid."' ";
	}
	$sql_get_comment  .= " order by id desc";
	$result_get_comment = mysqli_query($db_con,$sql_get_comment) or die(mysqli_error($db_con));
	$num_rows_get_comment = mysqli_num_rows($result_get_comment);	
	if($num_rows_get_comment != 0)
	{
		$data= "<span>";
		while($row_get_comment = mysqli_fetch_array($result_get_comment))
		{
			$data .= $row_get_comment['sm_comment'];
		}		
		$data .= "</span>";
		$response_array = array("Success"=>"Success","resp"=>$data);		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Comments");
	}
	echo json_encode($response_array);	
}
if((isset($obj->update_lead_dropDown)) == "1" && isset($obj->update_lead_dropDown))
{
	$sm_orgid 					= trim(mysqli_real_escape_string($db_con,$obj->sm_orgid));
	$sm_empid 					= trim(mysqli_real_escape_string($db_con,$obj->sm_empid));	
	
	$response_array 			= array();	
	$sql_get_lead_status 		= " SELECT * FROM `tbl_lead_status` WHERE  ls_status = 1 ";
	$result_get_lead_status 	= mysqli_query($db_con,$sql_get_lead_status) or die(mysqli_error($db_con));
	$num_rows_get_lead_status 	= mysqli_num_rows($result_get_lead_status);		
	
	$sql_lead_status 			= " SELECT `sm_leadid` FROM `tbl_sales_module` WHERE `sm_empid` = '".$sm_empid."' and `sm_orgid` = '".$sm_orgid."' ";
	$result_lead_status 		= mysqli_query($db_con,$sql_lead_status) or die(mysqli_error($db_con));
	$row_lead_status			= mysqli_fetch_array($result_lead_status);
				
	if($num_rows_get_lead_status != 0)
	{	
		$data	=	"<option value=''>Select Lead Status</option>";
		
		while($row_get_lead_status = mysqli_fetch_array($result_get_lead_status))
		{
			$data	.= '<option value="'.$row_get_lead_status['ls_id'].'" ';
			if($row_get_lead_status['ls_id'] == $row_lead_status['sm_leadid'])
			{
				$data	.= 'selected';		
			}
			$data	.= '>'.ucwords($row_get_lead_status['ls_name']).'</option>';
		}
		$response_array = array("Success"=>"Success","resp"=>$data);		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Data Available");		
	}						
	echo json_encode($response_array);	
}
if((isset($obj->load_sales)) == "1" && isset($obj->load_sales))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= trim(mysqli_real_escape_string($db_con,$obj->page));	
	$per_page		= trim(mysqli_real_escape_string($db_con,$obj->row_limit));
	$search_text	= trim(mysqli_real_escape_string($db_con,$obj->search_text));
	$star_status	= trim(mysqli_real_escape_string($db_con,$obj->star_status));
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
				
		$sql_load_data  = " SELECT org_id,org_name,org_star_status,org_status FROM `tbl_oraganisation_master` WHERE org_star_status = '".$star_status."' ";		
		if($search_text != "")
		{
			$sql_load_data .= " and org_name like '".$search_text."' ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY org_id ASC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$org_data = "";	
			$org_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$org_data .= '<thead>';
    	  	$org_data .= '<tr>';
         	$org_data .= '<th style="text-align:center">Sr No.</th>';
			$org_data .= '<th style="text-align:center">Org Id</th>';
			$org_data .= '<th style="text-align:center">Org Name</th>';
			$org_data .= '<th style="text-align:center">Employee List</th>';
			$org_data .= '<th style="text-align:center">Lead Status</th>';			
			$org_data .= '<th style="text-align:center">&nbsp</th>';				
			$org_data .= '<th style="text-align:center">Star Status</th>';		
          	$org_data .= '</tr>';
      		$org_data .= '</thead>';
      		$org_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$emp_flag = 0;
	    	  	$org_data .= '<tr>';				
				$org_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$org_data .= '<td style="text-align:center">'.$row_load_data['org_id'].'</td>';
				$org_data .= '<td style="text-align:center">'.ucwords($row_load_data['org_name']);
				if($row_load_data['org_status'] == 1)
				{
					$org_data .= '<label class="control-label" style="color:#30DD00">(Active)</label>';
				}
				else
				{
					$org_data .= '<label class="control-label" style="color:#E63A3A">(Inactive)</label>';
				}
				$org_data .= '</td>';						
				$sql_get_emp 		= " SELECT emp_id,emp_name FROM tbl_employee_master where emp_orgid = '".$row_load_data['org_id']."' and emp_status != 0";
				$result_get_emp 	= mysqli_query($db_con,$sql_get_emp) or die(mysqli_error($db_con)); 
				$num_rows_get_emp 	= mysqli_num_rows($result_get_emp);
				if($num_rows_get_emp != 0)
				{
					$org_data .= '<td style="text-align:center">';						
					$org_data	.= '<select id="org_emp'.$row_load_data['org_id'].'" name="org_emp'.$row_load_data['org_id'].'" onchange="loadComment(\''.$row_load_data['org_id'].'\'),updateLeadDropdown(\''.$row_load_data['org_id'].'\');" class="select2-me input-medium" data-rule-required="true">';					
					$org_data	.= '<option value="">Select Employee</option>';										
					while($row_get_emp = mysqli_fetch_array($result_get_emp))
					{
						$org_data	.= '<option value="'.$row_get_emp['emp_id'].'">'.ucwords($row_get_emp['emp_name']).'</option>';
					}					
					$org_data	.= '</select></td>';
				}
				else
				{
					$emp_flag 	= 1;
					$org_data .= '<td style="text-align:center">Employee Not Available</td>';					
				}	
				if($emp_flag == 0)
				{
					$sql_get_lead_status 	= " SELECT * FROM `tbl_lead_status` where `ls_status` = 1 ";
					$result_get_lead_status = mysqli_query($db_con,$sql_get_lead_status) or die(mysqli_error($db_con));
					$org_data .= '<td style="text-align:center">';						
					$org_data	.= '<select id="lead_status'.$row_load_data['org_id'].'" name="lead_status'.$row_load_data['org_id'].'" class="select2-me input-medium" onChange="updateComment(\''.$row_load_data['org_id'].'\');" data-rule-required="true">';
					$org_data	.= '<option value="">Select Lead Status</option>';										
					while($row_get_lead_status = mysqli_fetch_array($result_get_lead_status))
					{
						$org_data	.= '<option value="'.$row_get_lead_status['ls_id'].'"';
						if($row_get_lead_status['ls_id'] == $row_get_last_status['sm_leadid'])
						{
							$org_data	.= 'selected';		
						}
						$org_data	.= '>'.ucwords($row_get_lead_status['ls_name']).'</option>';
					}
					$org_data	.= '</select></td>';
				}
				else
				{
					$org_data .= '<td style="text-align:center">Lead Status Not Available</td>';
				}
				$org_data .= '<td style="width:40%">';
				if($emp_flag == 0)
				{
					$org_data .= '<div id="showBox'.$row_load_data['org_id'].'">';					
					$org_data .= '<div id="commentbox'.$row_load_data['org_id'].'" class="input-xlarge" style="height:150px;width:97%;overflow-y:scroll;background-color:#CCC;padding:3%;"></div>';
					$org_data .= '<div><textarea name="comment'.$row_load_data['org_id'].'" id="comment'.$row_load_data['org_id'].'" class="input-xlarge" rows="2" style="text-align:left;margin:10px;width:90%"></textarea><br>';
					$org_data .= '<input type="button" id="cmnt_btn'.$row_load_data['org_id'].'" class="btn-warning" value="Comment" style="width:20%;margin:0 0 20px 10px" onclick="updateComment(\''.$row_load_data['org_id'].'\');">';					
					$org_data .='</div></div>';
				}				
				$org_data .='</td>';
				$org_data .= '<td style="text-align:center">';					
				$current_star_status = $row_load_data['org_star_status']; 
				if($current_star_status == 1)
				{
					$org_data .= '<i class="icon-star" style="cursor:pointer;font-size:xx-large;color:#F90" id="'.$row_load_data['org_id'].'" onclick="changeStarStatus(this.id,\'0\')"></i>';
				}
				else if($current_star_status == 0)
				{
					$org_data .= '<i class="icon-star-empty" style="cursor:pointer;font-size:xx-large;color:#F90" id="'.$row_load_data['org_id'].'" onclick="changeStarStatus(this.id,\'1\')"></i>';
				}	
				$org_data .= '</td>';	
				$org_data .= '<script type="text/javascript">';
				$org_data .= '$("#org_emp"+'.$row_load_data['org_id'].').select2();';
				$org_data .= '$("#lead_status"+'.$row_load_data['org_id'].').select2();';				
				$org_data .= '</script>';																						
			}	
      		$org_data .= '</tbody>';
      		$org_data .= '</table>';
			$org_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$org_data);					
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
?>