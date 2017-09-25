<?php
include("include/routines.php");
include("include/db_con.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];
$uemail				= $_SESSION['panel_user']['email'];

#===========================================================================
# START : Function For finding the Sub Cat Value [ Hierarchical Structure ]
#===========================================================================
function getSubCatValue($cat_id, $userType,$count)	// Parameters : Parent ID and userType [i.e. Add, Edit, View]
{
	global $db_con;
	$data	= '';
	
	if($userType == 'add')
	{
		// Get The children of this Cat ID from Category Master Table
		$sql_get_children_frm_cm	= " SELECT * FROM `tbl_category` WHERE `cat_type`='".$cat_id."' AND `cat_name`!='none' AND `cat_status`='1' ";
		$res_get_children_frm_cm	= mysqli_query($db_con, $sql_get_children_frm_cm) or die(mysqli_error($db_con));
		$num_get_children_frm_cm	= mysqli_num_rows($res_get_children_frm_cm);
		
		if($num_get_children_frm_cm != 0)
		{
			while($row_get_children_frm_cm = mysqli_fetch_array($res_get_children_frm_cm))
			{
				// Get the count and the related parent ids of this category from the Category Path table
				$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` ";
				$sql_get_count_and_all_parent	.= " WHERE `cat_id`='".$row_get_children_frm_cm['cat_id']."' ";
				$sql_get_count_and_all_parent	.= " ORDER BY level ASC ";
				$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
				$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
				
				if($num_get_count_and_all_parent != 0)
				{
					$opt_value	= '';
					while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
					{
						// Find the name of the category
						$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` ";
						$sql_get_name_of_cat	.= " FROM `tbl_category` ";
						$sql_get_name_of_cat	.= " WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
						$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
						$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
						
						$parent_cat_name		= $row_get_name_of_cat['cat_name'];
						
						$opt_value	.= $parent_cat_name.' > ';
					}
					$data .= '<option id="cat'.$row_get_children_frm_cm['cat_id'].'" value="'.$row_get_children_frm_cm['cat_id'].'">'.substr(ucwords($opt_value),0,-3).'</option>';
					$data1	= getSubCatValue($row_get_children_frm_cm['cat_id'],'add',$count);
					if($data1 != '')
					{
						$data	.= $data1;
					}
				}
			}
			return $data;	
		}
		else
		{
			return $data;	
		}
	}
	elseif($userType == 'view')
	{
		// Get the count and the related parent ids of this category from the Category Path table
		$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` ";
		$sql_get_count_and_all_parent	.= " WHERE `cat_id`='".$cat_id."' ";
		$sql_get_count_and_all_parent	.= " ORDER BY level ASC ";
		$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
		$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
		
		if($num_get_count_and_all_parent != 0)
		{
			$opt_value	= '';
			while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
			{
				// Find the name of the category
				$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` ";
				$sql_get_name_of_cat	.= " FROM `tbl_category` ";
				$sql_get_name_of_cat	.= " WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
				$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
				$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
				
				$parent_cat_name		= $row_get_name_of_cat['cat_name'];
				
				$opt_value	.= $parent_cat_name.' > ';
			}
			$data .= '<label class="control-label" >'.substr(ucwords($opt_value),0,-3).'</label>';
		}
		
		return $data;		
	}
	else
	{
		return $data;	
	}
}
#===========================================================================
# END : Function For finding the Sub Cat Value [ Hierarchical Structure ]
#===========================================================================

if((isset($obj->load_school)) == "1" && isset($obj->load_school))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit_coupons;
	$search_text	= $obj->search_text_coupons;	
		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT  tc.* ,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE email = tc.created_by) AS name_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.modified_by) AS name_midified_by , ";
		$sql_load_data  .= " (SELECT city_name FROM `city` WHERE city_id = tc.city) AS city_name ";
		$sql_load_data  .= " FROM `tbl_schools` AS tc WHERE 1=1 ";
			
		
		if(strcmp($utype,'1')!==0)
		{
			//$sql_load_data  .= " AND coup_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (sname like '%".$search_text."%' or branch like '%".$search_text."%' or tc.id like '%".$search_text."%' ";
			
			$sql_load_data .= "	or 	created_by like '%".$search_text."%' or  tc.modified_by like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY tc.id DESC LIMIT $start, $per_page ";
	
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));		
			
		if(strcmp($data_count,"0") !== 0)
		{		
			$school_data = "";	
			$school_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$school_data .= '<thead>';
    	  	$school_data .= '<tr>';
         	$school_data .= '<th style="text-align:center">Sr No.</th>';
			$school_data .= '<th style="text-align:center">School Id</th>';
			$school_data .= '<th style="text-align:center">School Name</th>';
			$school_data .= '<th style="text-align:center">Branch Name</th>';
			$school_data .= '<th style="text-align:center">City</th>';
			$school_data .= '<th style="text-align:center">State</th>';	
			$school_data .= '<th style="text-align:center">Country</th>';						
			$school_data .= '<th style="text-align:center">Created</th>';
			$school_data .= '<th style="text-align:center">Created By</th>';
			$school_data .= '<th style="text-align:center">Modified</th>';
			$school_data .= '<th style="text-align:center">Modified By</th>';
			$school_data .= '<th style="text-align:center">Product</th>';
			$dis = checkFunctionalityRight("view_school.php",3);
			if($dis)
			{					
				$school_data .= '<th>Status</th>';											
			}
			$edit = checkFunctionalityRight("view_school.php",1);
			if($edit)
			{					
				$school_data .= '<th>Edit</th>';			
			}	
			$delete = checkFunctionalityRight("view_school.php",2);
			if($delete)
			{					
				$school_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}					
          	$school_data .= '</tr>';
      		$school_data .= '</thead>';
      		$school_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$school_data .= '<tr>';				
				$school_data .= '<td style="text-align:center">'.++$start_offset.'</td>';		
				$school_data .= '<td style="text-align:center">'.$row_load_data['id'].'</td>';		
				$school_data .= '<td style="text-align:center"><input type="button" value=" '.ucwords($row_load_data['sname']).'" class="btn-link" id="'.$row_load_data['id'].'" onclick="addMoreSchool(this.id,\'view\');"></td>';
				$school_data .= '<td style="text-align:center">'.ucwords($row_load_data['branch']).'</td>';				
				$school_data .= '<td style="text-align:center">'.$row_load_data['city_name'].'</td>';
				$school_data .= '<td style="text-align:center">'.$row_load_data['state'].'</td>';	
				$school_data .= '<td style="text-align:center">'.$row_load_data['country'].'</td>';							
				$school_data .= '<td style="text-align:center">'.$row_load_data['created'].'</td>';
				$school_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$school_data .= '<td style="text-align:center">'.$row_load_data['modified'].'</td>';
				$school_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$school_data .= '<td style="text-align:center">';					
					
				$school_data .= '<input type="button" onclick="view_product('.$row_load_data['id'].');" value="View Products" id="'.$row_load_data['id'].'" class="btn-success">';
					
					
					$school_data .= '</td>';
				$dis = checkFunctionalityRight("view_school.php",3);
				if($dis)
				{					
					$school_data .= '<td style="text-align:center">';					
					if($row_load_data['status'] == 1)
					{
						$school_data .= '<input type="button" value="Active" id="'.$row_load_data['id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$school_data .= '<input type="button" value="Inactive" id="'.$row_load_data['id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$school_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_school.php",1);
				if($edit)
				{				
						$school_data .= '<td style="text-align:center">';
						$school_data .= '<input type="button" value="Edit" id="'.$row_load_data['id'].'" class="btn-warning" onclick="addMoreSchool(this.id,\'edit\');"></td>';				
				}
				$delete = checkFunctionalityRight("view_school.php",2);
				if($delete)
				{					
					$school_data .= '<td><div class="controls" align="center">';
					$school_data .= '<input type="checkbox" value="'.$row_load_data['id'].'" id="customers'.$row_load_data['id'].'" name="customers'.$row_load_data['coup_id'].'" class="css-checkbox schools">';
					$school_data .= '<label for="customers'.$row_load_data['id'].'" class="css-label"></label>';
					$school_data .= '</div></td>';										
				}
	          	$school_data .= '</tr>';															
			}	
      		$school_data .= '</tbody>';
      		$school_data .= '</table>';	
			$school_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$school_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available in Customers");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}
if($obj->req_type == "add" && isset($obj->req_type))
{
		
		
		$data = '';

		$data .= '<script type="text/javascript">
        $(document).ready(function() {
           $( "#datepicker1" ).datepicker();
           $( "#datepicker" ).datepicker();
        
           $(".js-example-basic-single").select2();


           

        });
     
         
        </script>';
       // $data .= '<style> .form-line-message{display: none; } </style>';
        $data .= '<style> label.error{position: absolute; left: 200px; } </style>';
		$data .='<input type="hidden" name="add_req" value="1">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">School Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="school_name" placeholder="Enter School Name" name="school_name" class="input-xlarge" data-rule-required="true" ';
		
		
		$data .= '/>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Branch <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="branch_name" placeholder="Enter Branch name" Name="branch_name" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
			
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select State <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select onchange="getcity(this.value);" style="width:30%" id ="state" name="state" data-rule-required="true" class = "js-example-basic-single" onChange="coupon(this.value);">';
		$data .= '<option value="">Select State</option>';
		
		$sql_get_coupon ="SELECT * FROM state WHERE country_id ='IN' ";
		$res_get_coupon	= mysqli_query($db_con,$sql_get_coupon) or die(mysqli_error($db_con));
		while($city_row     =mysqli_fetch_array($res_get_coupon))
		{
			$data .= '<option value="'.$city_row['state'].'" >'.$city_row['state_name'].'</option>';
		}
		
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';	
        
       

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select City <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select style="width:30%" id ="city" name="city" data-rule-required="true" class = "js-example-basic-single" >';
		$data .= '<option value="">Select City</option>';
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';

        $data .= '<div  class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Pincode<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
        $data .= '<input type="text" minlength="6" maxlength="6"  onkeypress="return numsonly(event);" name="pincode" id="pincode" class="input-xlarge" data-rule-required="true">';
		$data .= '</div>';
		$data .= '</div>';

		

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="radio" name="status" value="1"  data-rule-required="true">Active';
		$data .= '<input type="radio" style="margin:10px;" name="status" value="0"  data-rule-required="true">Inactive<br>';
		$data .= '</div>';
		$data .= '</div>';
  
		$data .= '<br><button class="btn-info" type="submit" onclick="">Add School</button>';
        $response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);exit();
}

if((isset($_POST['add_req'])) == "1" && isset($_POST['add_req']))
{   
    $sname 		 = $_POST['school_name'];
	$branch		 = $_POST['branch_name'];
	$city 		 = $_POST['city'];
	$state		 = $_POST['state'];
	$pincode 	 = $_POST['pincode'];
	$category	 = $_POST['category'];
	$status	     = $_POST['status'];
	
	$sql_add_school  ="INSERT INTO `tbl_schools`(`sname`, `branch`, `state`, `city`, `pincode`, category ,status ,created_by,created) VALUES ";
	$sql_add_school .=" ( '".$sname."', '".$branch."', '".$state."', '".$city."', '".$pincode."', '".$category."', '".$status."','".$uemail."','".$datetime."') ";
	$result_update_coupon	= mysqli_query($db_con,$sql_add_school) or die(mysqli_error($db_con));
	if($result_update_coupon){
	$response_array = array("Success"=>"Success","resp"=>"School Successfully Added");	
	}
	else
	{
		 $response_array = array("Success"=>"fail","resp"=>"School Not Added");	
	}
	
	
	echo json_encode($response_array);	
}

if($obj->req_type == "edit" && isset($obj->req_type))
{
	
	    $scool_id = $obj->school_id;
	    $sql_get_school = " SELECT *, (SELECT city_name FROM `city` WHERE city_id = city) AS city_name  FROM tbl_schools WHERE id = '".$scool_id."' ";
	    $res_get_city	= mysqli_query($db_con,$sql_get_school) or die(mysqli_error($db_con));
	    $row 		    = mysqli_fetch_array($res_get_city);
		
		$data = '';

		$data .= '<script type="text/javascript">
        $(document).ready(function() {
           $( "#datepicker1" ).datepicker();
           $( "#datepicker" ).datepicker();
        
           $(".js-example-basic-single").select2();
         });
         </script>';
 
        $data .= '<style> label.error{position: absolute; left: 200px; } </style>';
		$data .='<input type="hidden" name="update_req" value="1">';
		$data .='<input type="hidden" name="school_id" value="'.$row['id'].'">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">School Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="school_name" placeholder="Enter School Name" name="school_name" value="'.$row['sname'].'" class="input-xlarge" data-rule-required="true" ';
		
		
		$data .= '/>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Branch <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="branch_name" value="'.$row['branch'].'"placeholder="Enter Branch name" Name="branch_name" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
			
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select State <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select onchange="getcity(this.value);" style="width:30%" id ="state" name="state" data-rule-required="true" class = "js-example-basic-single" onChange="coupon(this.value);">';
		$data .= '<option value="">Select State</option>';
		
		$sql_get_state ="SELECT * FROM state WHERE country_id ='IN' ";
		$res_get_state	= mysqli_query($db_con,$sql_get_state) or die(mysqli_error($db_con));
		while($state_row     = mysqli_fetch_array($res_get_state))
		{
			$data .= '<option value="'.$state_row['state'].'" ';
			
			if($state_row['state']== $row['state'])
			{
				$data .=' selected ';
			}
			
			$data .=' >'.$state_row['state_name'].'</option>';
		}
		
		$data .= '</select>'; 
		//

		$data .= '</div>';
		$data .= '</div>';	
        
        //$data .= '<div hidden id="lkpm">aaaaaaaaaa</div>'; 

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select City <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select style="width:30%" id ="city" name="city" data-rule-required="true" class = "js-example-basic-single" >';
		$sql_get_city = " SELECT * FROM city WHERE state_id = '".$row['state']."' ";
	    $res_get_city	= mysqli_query($db_con,$sql_get_city) or die(mysqli_error($db_con));
	    $data .='<option value="" >Select City</option>';
	    while($city_row = mysqli_fetch_array($res_get_city))
		 {
			 $data .='<option value="'.$city_row['city_id'].'" ';
			 if($city_row['city_id']== $row['city'])
			 {
				 $data .=' selected ';
			 }
			 $data .=' >'.$city_row['city_name'].'</option>';
		 }
		
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';

        $data .= '<div  class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Pincode<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
        $data .= '<input type="text" maxlength="6" minlength="6" value="'.$row['pincode'].'" onkeypress="return numsonly(event);" name="pincode" id="pincode" class="input-xlarge" >';
		$data .= '</div>';
		$data .= '</div>';

		

	

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="radio" name="status" value="1"  data-rule-required="true" ';
		if($row['status']==1)
		{
		$data .=' checked ';	
		}
		$data .=' >Active';
		$data .= '<input type="radio" style="margin:10px;" name="status" value="0"  data-rule-required="true" ';
		if($row['status']==0)
		{
		$data .=' checked ';	
		}
		$data .=' >Inactive<br>';
		$data .= '</div>';
		$data .= '</div>';
  
		$data .= '<br><button class="btn-info" type="submit" onclick="">Update School</button>';
        $response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);exit();
}







if($_POST['update_req'] == "1" && isset($_POST['update_req']))
{   
    $sname 		 = $_POST['school_name'];
	$branch		 = $_POST['branch_name'];
	$city 		 = $_POST['city'];
	$state		 = $_POST['state'];
	$pincode 	 = $_POST['pincode'];
	$category	 = $_POST['category'];
	$status	     = $_POST['status'];
	$school_id	 = $_POST['school_id'];
	
	
	$sql_update_school  = " UPDATE tbl_schools SET sname='".$sname."' ,branch='".$branch."' , state='".$state."' ,city='".$city."', ";
	$sql_update_school .= "pincode = '".$pincode."',category='".$category."', status ='".$status."',";
	$sql_update_school .=" modified_by='".$uid."',";
	$sql_update_school .="modified ='".$datetime."' WHERE id='".$school_id."'";
	$result_update_coupon	= mysqli_query($db_con,$sql_update_school) or die(mysqli_error($db_con));
	
	
	if($result_update_coupon)
	{
		
		 $sql_update_status=" UPDATE tbl_student_package_info SET stud_pkg_status='".$status."',stud_pkg_modified_by='".$uid."', ";
		 $sql_update_status .=" stud_pkg_modified ='".$datetime."' WHERE stud_pkg_sch_id='".$school_id."' ";
		 $result_update_status	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	 
		$response_array = array("Success"=>"Success","resp"=>"School Successfully Updated","sql"=>$sql_update_school,"chekc"=>$type_times_use1);	
	}
	else
	{
		 $response_array = array("Success"=>"fail","resp"=>"Coupon Not Update");	
	}
	
	echo json_encode($response_array);	
}
if($obj->req_type == "view" && isset($obj->req_type))
{
	
	    $scool_id = $obj->school_id;
	    $sql_get_school = " SELECT *, (SELECT city_name FROM `city` WHERE city_id = city) AS city_name  FROM tbl_schools WHERE id = '".$scool_id."' ";
	    $res_get_city	= mysqli_query($db_con,$sql_get_school) or die(mysqli_error($db_con));
	    $row 		    = mysqli_fetch_array($res_get_city);
		
		$data = '';

		$data .= '<script type="text/javascript">
        $(document).ready(function() {
           $( "#datepicker1" ).datepicker();
           $( "#datepicker" ).datepicker();
        
           $(".js-example-basic-single").select2();
         });
         </script>';
 
        $data .= '<style> label.error{position: absolute; left: 200px; } </style>';
		$data .='<input type="hidden" name="update_req" value="1">';
		$data .='<input type="hidden" name="school_id" value="'.$row['id'].'">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">School Name</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly="readonly"  type="text" id="school_name" placeholder="Enter School Name" name="school_name" value="'.$row['sname'].'" class="input-xlarge" data-rule-required="true" ';
		$data .= '/>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Branch </label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly="readonly"  type="text" id="branch_name" value="'.$row['branch'].'"placeholder="Enter Branch name" Name="branch_name" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
			
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select State </label>';
		$data .= '<div class="controls">';
		$data .= '<select disabled style="width:30%" id ="state" name="state" data-rule-required="true" class = "js-example-basic-single" onChange="coupon(this.value);">';
		$data .= '<option value="">Select State</option>';
		
		$sql_get_coupon ="SELECT * FROM state WHERE country_id ='IN' ";
		$res_get_coupon	= mysqli_query($db_con,$sql_get_coupon) or die(mysqli_error($db_con));
		while($city_row     =mysqli_fetch_array($res_get_coupon))
		{
			$data .= '<option value="'.$city_row['state'].'" ';
			
			if($city_row['state']== $row['state'])
			{
				$data .=' selected ';
			}
			
			$data .=' >'.$city_row['state_name'].'</option>';
		}
		
		$data .= '</select>'; 
		//

		$data .= '</div>';
		$data .= '</div>';	
        
        //$data .= '<div hidden id="lkpm">aaaaaaaaaa</div>'; 

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select City</label>';
		$data .= '<div class="controls">';
		$data .= '<select disabled style="width:30%" id ="city" name="city" data-rule-required="true" class = "js-example-basic-single" >';
		$data .= '<option value="'.$row['city'].'" selected="selected">'.$row['city_name'].'</option>';
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';

        $data .= '<div  class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Pincode</label>';
		$data .= '<div class="controls">';
        $data .= '<input readonly="readonly" type="text" maxlength="6" value="'.$row['pincode'].'" onkeypress="return numsonly(event);" name="pincode" id="pincode" class="input-xlarge" >';
		$data .= '</div>';
		$data .= '</div>';

		

	

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status</label>';
		$data .= '<div class="controls">';
		
		if($row['status']==1)
		{
		$data .=' Active ';	
		}
		
		if($row['status']==0)
		{
		$data .=' Inactive ';	
		}
		
		$data .= '</div>';
		$data .= '</div>';
  
	
        $response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);exit();
}

if((isset($obj->changeStatus)) == "1" && isset($obj->changeStatus))
{    
     $response_array= array();
     $curr_status  = $obj->curr_status; 
	 $school_id      = $obj->coup_id;
	 $sql_update_status=" UPDATE tbl_schools SET status='".$curr_status."',modified_by='".$uid."', ";
	 $sql_update_status .=" modified ='".$datetime."' WHERE id='".$school_id."' ";
	 $result_update_status	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	 
	 if($result_update_status){
		
	
		 $sql_update_status=" UPDATE tbl_student_package_info SET stud_pkg_status='".$curr_status."',stud_pkg_modified_by='".$uid."', ";
		 $sql_update_status .=" stud_pkg_modified ='".$datetime."' WHERE stud_pkg_sch_id='".$school_id."' ";
		 $result_update_status	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	
     $response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully");
     echo json_encode($response_array);
     }
     else
     {  
     	$response_array = array("Success"=>"Fail","resp"=>"Status Updation Failed");
     echo json_encode($response_array);
     }
}

if((isset($obj->get_city)) == "1" && isset($obj->get_city))
{    
	$response_array  = array();
	$state_id 	     = $obj->state_id; 
	$data			 = "";
	 
	 $sql_get_city = " SELECT * FROM city WHERE state_id = '".$state_id."' ";
	 $res_get_city	= mysqli_query($db_con,$sql_get_city) or die(mysqli_error($db_con));
	 $num_rows 		= mysqli_num_rows($res_get_city);
	 
	  $data .='<option value="" >Select City</option>';
	  
	 if($num_rows!= 0)
	 {
		 while($row = mysqli_fetch_array($res_get_city))
		 {
			 $data .='<option value="'.$row['city_id'].'" >'.$row['city_name'].'</option>';
		 }
		 
	   $response_array = array("Success"=>"Success","resp"=>$data);
     }
     else
     {  
	    $data .='<option>Not Available</option>';
     	$response_array = array("Success"=>"Success","resp"=>$data);
  
     }
echo json_encode($response_array);
}

if((isset($obj->delete_schools)) == "1" && isset($obj->delete_schools))
{   
	$data 			="";
	$schools 		= $obj->schools;
	foreach($schools as $school_id)
	{
	$sql_delete_school	= " DELETE FROM `tbl_schools` WHERE `id` = '".$school_id."' ";
	$result_delete_school= mysqli_query($db_con,$sql_delete_school) or die(mysqli_error($db_con));
	}
	 if($result_delete_school){
     $response_array = array("Success"=>"Success","resp"=>"");
     echo json_encode($response_array);
     }
     else
     {  
     	$response_array = array("Success"=>"Fail","resp"=>"");
     echo json_encode($response_array);
	 }
	
}

if((isset($obj->get_products)) == "1" && isset($obj->get_products))
{   
	$data 			="";
	$school_id 		= $obj->school_id;

	    $sql_get_school 		= "Select * from tbl_schools  where  id = '".$school_id."' ";
		$res_get_school 	= mysqli_query($db_con,$sql_get_school) or die(mysqli_error($db_con));
		$row		= mysqli_fetch_array($res_get_school);	


	    $sql_package_sql 		= "Select tspi.*,ts.sname from tbl_student_package_info  as tspi INNER JOIN tbl_schools as ts ON tspi.stud_pkg_sch_id = ts.id where  stud_pkg_sch_id = '".$school_id."' ";
		$result_package_data 	= mysqli_query($db_con,$sql_package_sql) or die(mysqli_error($db_con));
		$row_pkg_data		= mysqli_fetch_array($result_package_data);	
		$num_rows           = mysqli_num_rows($result_package_data);
		$stud_pkg_info_id	= $row_pkg_data['stud_pkg_info_id'];
	 
        if($num_rows==1)
        {
	    $data .='<input type="hidden" name="school_id" id="school_id" value="'.$school_id.'">';
		$data .='<input type="hidden" name="stud_pkg_info_id" id="stud_pkg_info_id" value="'.$stud_pkg_info_id.'">';
		$data .='<input type="hidden" name="hid_page2" id="hid_page2" value="1">' ;
		$data .='<input type="hidden" name="update_req" value="1">';
        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label"> School Name</label>';
		$data .= '<div class="controls">';
		$data .=$row['sname'];
		$data .= '</div>';
		$data .= '</div> <!-- select school end -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Start date</label>';
		$data .= '<div class="controls date form_datetime">';
        $data .= '<input value="'.$row_pkg_data['stud_pkg_start_date'].'"  type="text" name="start_date" class="input-xlarge" id="start_date"  readonly="readonly"/>';
		 $data .= '<span class="add-on" style="padding:10px"><i  class="icon-remove"></i></span>';
		$data .= '<span style="cursor:pointer;width:14px;height:14px" class="add-on"><i class="icon-th"></i></span>';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">End date</label>';
		$data .= '<div class="controls date form_datetime">';
        $data .= '<input value="'.$row_pkg_data['stud_pkg_end_date'].'"  type="text" name="end_date" class="input-xlarge" id="end_date"  readonly="readonly"/>';
		 $data .= '<span class="add-on" style="padding:10px"><i  class="icon-remove"></i></span>';
		$data .= '<span style="cursor:pointer;width:14px;height:14px" class="add-on"><i class="icon-th"></i></span>';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
        $data .='<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        //language:  "fr",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	
</script>';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		
		$data .= '<input type="radio" id="status" name="status" class="input-large keyup-char" data-rule-required="true"';
		if($row_pkg_data['stud_pkg_status']==1)
		{
			$data .=' checked ';
		}
		$data .=' value="1"> Active '; 	
		
		$data .= '<input type="radio" id="status" name="status" class="input-large keyup-char" data-rule-required="true"';
		if($row_pkg_data['stud_pkg_status']==0)
		{
			$data .=' checked ';
		}
		$data .=' value="0"> Inactive '; 
		$data .= '</div>';
		$data .= '</div> <!-- End Date -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Products<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$products =explode(',',$row_pkg_data['stud_pkg_products']);
		if(sizeof($products)>=1)
		{
			if($products[0]!="")
			{
			$data .= '<table class="table table-bordered">';
				
			
            $data .= '</table>';
			$data .= '<div class="show_product_data" style="max-height: 300px;padding:10px 10px 10px;border-bottom: 1px solid #dadada;" >';
			
			$data .='<table id="tbl_user" class="table scroll table-bordered dataTable" style="height: 10px;overflow-y: scroll;width:100%;text-align:center">';
    	 	$data .= '<thead>';
    	  	$data .= '<tr>';
			$data .= '<th style="text-align:center;width:12%">';
			$data .='<input value="Remove Product" onclick="remove_prod('.$row_pkg_data['stud_pkg_info_id'].');"  class="btn-danger" type="button"></th>';
         	$data .= '<th style="text-align:center ;width:5%">Sr No.</th>';
			
			$data .= '<th style="text-align:center ;width:40%">Product Name</th>';
			$data .= '<th style="text-align:center">Image</th>';
			$data .= '<th style="text-align:center">Organization</th>';
			$data .= '</tr>';
      		$data .= '</thead>';
			$data .= '<tbody>';
			
		for($i=0 ;$i<sizeof($products);$i++)
			{
			$j=$i+1;
				if($products[$i]!="")
				{
				$sql_get_product=" ";
				$sql_get_product  .= " SELECT tpm.*, tom.org_name, tpi.prod_img_file_name ";
				$sql_get_product  .= " FROM tbl_products_master AS tpm INNER JOIN tbl_oraganisation_master AS tom ";
				$sql_get_product  .= " 	ON tpm.prod_orgid = tom.org_id INNER JOIN tbl_products_images AS tpi ";
				$sql_get_product  .= " 	ON tpm.prod_id = tpi.prod_img_prodid ";
				$sql_get_product  .= " WHERE tpm.prod_id IN ('".$products[$i]."')";
				$result_prod_data = mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
				$row_prod_data		= mysqli_fetch_array($result_prod_data);
					$product_count++;
					$data .= '<tr>';				
						
					$data .= '<td style="text-align:center;width:12%"><input  type="checkbox" class="del_prod'.$count.'" id="sel_prod'.$count.'" name="sel_prod'.$count.'" value="'.$row_prod_data['prod_id'].'" > </td>';				
					$data .= '<td style="text-align:center ; width:4%">'.$product_count.'</td>';
					$data .= '<td style="text-align:center;width:42%">'.$row_prod_data['prod_name'].'</td>';
					
					$prod_orgid				= $row_prod_data['prod_orgid'];
					$prod_catid				= $row_prod_data['prod_catid'];
					$prod_subcatid			= $row_prod_data['prod_subcatid'];
					$prod_id				= $row_prod_data['prod_id'];
					$dir 		  = "../images/planet/org";
				
				$org_dir 	= $dir.$prod_orgid;
				
				$cat_dir 	= $org_dir."/cat".$prod_catid;
				
				$subcat_dir = $cat_dir."/subcat".$prod_subcatid;		
				
				$prod_dir 	= $org_dir."/prod_id_".$prod_id;				
				
				$imagepath	= $prod_dir."/small/".$row_prod_data['prod_img_file_name'];
					
					
					 if(is_file($imagepath))
						{
						  $data .= '<td style="text-align:center" ><img style="width:50px;height:50px" src="'.$imagepath.'?'.rand().'"/></td>';
						}
						else
					   {
						   $data .= '<td style="text-align:center" ><img style="text-align:center;width:50px;height:50px" src="../images/no-image.jpg"/></td>';
					   }
					
					
					//$branch_data .= '<td style="text-align:center">'.$row_load_data['prod_img_file_name'].'</td>';
					$data .= '<td style="text-align:center">'.$row_prod_data['org_name'].'</td>';
					$data .= '</tr>';	
			
				}// if end
			}// for end
		  $data .= '</tbody>';
		  $data .= '</table>';
		  
		
		  
		  $data .='<br><br></div><br>';
		 }// if products end
	 }
		$data .='<input id="product_count" type="hidden" name="product_count" value="'.$product_count.'">';
		$data .='<select name="prod_cat_id_list'.$count.'" id="prod_cat_id_list'.$count.'"';
		$data .='   class = "select2-me input-large marginr10">';
        $data .= '<option value="">Select Category</option>';
        $sql_get_cat_id_list = " SELECT `cat_id`,`cat_name` FROM `tbl_category` ";
		$sql_get_cat_id_list .= " WHERE `cat_id` IN (SELECT distinct(prod_catid) FROM `tbl_products_master`) ";
		$sql_get_cat_id_list .= " 	and `cat_type` = 'parent' ";
		$result_get_cat_id_list = mysqli_query($db_con,$sql_get_cat_id_list) or die(mysqli_error($db_con));
		
			$sql_chk_isParent	= " SELECT * FROM `tbl_category` ";
			$sql_chk_isParent	.= " WHERE `cat_status`='1' ";
			$sql_chk_isParent	.= " 	AND `cat_name`!='none' ";
			$sql_chk_isParent	.= " 	AND `cat_type`='parent' ";
			$sql_chk_isParent	.= " ORDER BY `cat_name` ASC ";
			$res_chk_isParent	= mysqli_query($db_con, $sql_chk_isParent) or die(mysqli_error($db_con));
			$num_chk_isParent	= mysqli_num_rows($res_chk_isParent);
			
			if($num_chk_isParent != 0)
			{
				while($row_chk_isParent = mysqli_fetch_array($res_chk_isParent))
				{
					$data .= '<option id="cat'.$row_chk_isParent['cat_id'].'" value="'.$row_chk_isParent['cat_id'].'">'.ucwords($row_chk_isParent['cat_name']).'</option>';
					$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'add',$count);
				}
			}										
		$data .='</select>';
      
		
		$data .='<span><select name="prod_org_id_list" id="prod_org_id_list" onChange="loadProducts();"  class = "select2-me input-large marginr10">';
        $sql_get_org_id_list = "SELECT `org_id`,`org_name` FROM `tbl_oraganisation_master` WHERE `org_id` IN(SELECT distinct(prod_orgid) FROM `tbl_products_master`); ";
		$result_get_org_id_list = mysqli_query($db_con,$sql_get_org_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select Organisation</option>';
        while($row_get_org_id_list = mysqli_fetch_array($result_get_org_id_list))
		{
		$data .='<option id="org'.$row_get_org_id_list['org_id'].'" value="'.$row_get_org_id_list['org_id'].'">'.ucwords($row_get_org_id_list['org_name']).'</option>';
        }
		$data .='</select></span>';
       
	
		$data .='<span ><select name="prod_brand_id_list" id="prod_brand_id_list"   class = "select2-me input-large marginr10">';
        $sql_get_brand_id_list = "SELECT `brand_id`,`brand_name` FROM `tbl_brands_master` WHERE `brand_id` IN (SELECT distinct(prod_brandid) FROM `tbl_products_master`) ";
		$result_get_brand_id_list = mysqli_query($db_con,$sql_get_brand_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select Brand</option>';
		while($row_get_brand_id_list = mysqli_fetch_array($result_get_brand_id_list))
		{
		$data .='<option id="brand'.$row_get_brand_id_list['brand_id'].'" value="'. $row_get_brand_id_list['brand_id'].'">'.ucwords($row_get_brand_id_list['brand_name']).'</option>';
        }
		$data .='</select></span>';
		
		$data .='<select name="grade" id="grade"   class = "select2-me input-large marginr10">';
        $sql_get_brand_id_list = "SELECT `brand_id`,`brand_name` FROM `tbl_brands_master` WHERE `brand_id` IN (SELECT distinct(prod_brandid) FROM `tbl_products_master`) ";
		$result_get_brand_id_list = mysqli_query($db_con,$sql_get_brand_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select Grade</option>';
		$sql_sub_child_flit_data 		= "	SELECT distinct filt_id,filt_name,filt_slug ";
		$sql_sub_child_flit_data 		.= " FROM `tbl_filters` tf ";
		$sql_sub_child_flit_data 		.= " where tf.filt_status != 0 ";
		$sql_sub_child_flit_data 		.= " 	and tf.filt_type = '15' ";
		$sql_sub_child_flit_data 		.= " 	and tf.filt_sub_child = 'child' ";
		$sql_sub_child_flit_data 		.= " order by filt_sort_order ASC ";
					
		$result_sub_child_filt_data		= mysqli_query($db_con,$sql_sub_child_flit_data) or die(mysqli_error($db_con));
		while($row_sub_child_filt_data = mysqli_fetch_array($result_sub_child_filt_data))
		{	
			if($row_sub_child_filt_data['filt_name']!="none")
			{
				
				$data .='<option  id="grade'.$row_sub_child_filt_data['filt_id'].'" value="'. $row_sub_child_filt_data['filt_id'].'">'. ucwords($row_sub_child_filt_data['filt_name']).'</option>';
					   
				
			}
		} 
		$data .='</select>';
		
		$data .= '<script type="text/javascript">';
		$data .= '$(".select2-me").select2();';	
		$data .= '</script>';
         // brand end   
		$data .='<span><input class="input keyup-char input-large" onkeypress="return searchProduct(event);" type="text" placeholder="Search here..." name="srch" id="srchpkg_prod"></span>';
		$data .='<input style="" class=" btn-success"  id="filter" onClick="getProducts(0);" value="Filter" type="button">';
	    $data .='<input style="" class=" btn-success"  id="check_prod"  value="0" type="hidden">';
		
		                                                                          
	    $data .='&nbsp;&nbsp;';
		$data .= '';
        $data .='<div class="" id="product_table" style="height:;display:none">';
		$data .='</div>';//product table end
		$data .='</div>';// count end
		
		$data .= '</div>';
		$data .= '</div> <!-- Capmaign status-->';
		$data .='<div id="sections"<br></div>';
		$data .= '<div class="form-actions">';
		$data .= '<button type="submit" id="reg_submit_add" name="reg_submit_add" class="btn-success">Update Products</button>';
		$data .= '&nbsp;<input type="hidden" id="submit_req" value="">';	
		$data .= '&nbsp;<button onClick="check_req()" type="submit"  class="btn-warning">Submit</button>';		
		//$data .= '&nbsp;<button type="reset" id="reg_submit_add" name="" class="btn-warning">Reset</button>';	
				
		$data .= '</div> <!-- Save and cancel -->';			
	    $response_array = array("Success"=>"Success","resp"=>$data,"stud_pkg_sch_id"=>$stud_pkg_info_id);
	}
	    elseif($num_rows==0)
		{
			
		$data = '';
		$data .='<input type="hidden" name="school_id" id="school_id" value="'.$school_id.'">';
		$data .='<input type="hidden" name="stud_pkg_info_id" id="stud_pkg_info_id" value="-1">' ;
		$data .='<input type="hidden" name="hid_page2" id="hid_page2" value="1">' ;
			
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select School<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .='<input type="hidden" name="insert_req" value="1">';
		$data .=$row['sname'];
		$data .= '</div>';
		$data .= '</div> <!-- school end -->';  
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Start date</label>';
		$data .= '<div class="controls date form_datetime">';
        $data .= '<input type="text" name="start_date" class="input-xlarge" id="start_date"  readonly="readonly"/>';
		 $data .= '<span class="add-on" style="padding:10px"><i  class="icon-remove"></i></span>';
		$data .= '<span style="cursor:pointer;width:14px;height:14px" class="add-on"><i class="icon-th"></i></span>';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">End date</label>';
		$data .= '<div class="controls date form_datetime">';
        $data .= '<input type="text" name="end_date" class="input-xlarge" id="end_date"  readonly="readonly"/>';
		 $data .= '<span class="add-on" style="padding:10px"><i  class="icon-remove"></i></span>';
		$data .= '<span style="cursor:pointer;width:14px;height:14px" class="add-on"><i class="icon-th"></i></span>';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
        $data .='<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        //language:  "fr",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	
</script>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="radio" id="status" name="status" class="input-large keyup-char" data-rule-required="true" value="1"> Active '; 	
		$data .= '<input type="radio" id="status" name="status" class="input-large keyup-char" data-rule-required="true" value="0"> Inactive '; 
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Products<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		
		$data .='<select name="prod_cat_id_list'.$count.'" id="prod_cat_id_list'.$count.'"';
		$data .='   class = "select2-me input-large marginr10">';
        $data .= '<option value="">Select Category</option>';
        $sql_get_cat_id_list = " SELECT `cat_id`,`cat_name` FROM `tbl_category` ";
		$sql_get_cat_id_list .= " WHERE `cat_id` IN (SELECT distinct(prod_catid) FROM `tbl_products_master`) ";
		$sql_get_cat_id_list .= " 	and `cat_type` = 'parent' ";
		$result_get_cat_id_list = mysqli_query($db_con,$sql_get_cat_id_list) or die(mysqli_error($db_con));
		
			$sql_chk_isParent	= " SELECT * FROM `tbl_category` ";
			$sql_chk_isParent	.= " WHERE `cat_status`='1' ";
			$sql_chk_isParent	.= " 	AND `cat_name`!='none' ";
			$sql_chk_isParent	.= " 	AND `cat_type`='parent' ";
			$sql_chk_isParent	.= " ORDER BY `cat_name` ASC ";
			$res_chk_isParent	= mysqli_query($db_con, $sql_chk_isParent) or die(mysqli_error($db_con));
			$num_chk_isParent	= mysqli_num_rows($res_chk_isParent);
			
			if($num_chk_isParent != 0)
			{
				while($row_chk_isParent = mysqli_fetch_array($res_chk_isParent))
				{
					$data .= '<option id="cat'.$row_chk_isParent['cat_id'].'" value="'.$row_chk_isParent['cat_id'].'">'.ucwords($row_chk_isParent['cat_name']).'</option>';
					$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'add',$count);
				}
			}										
		$data .='</select>';
      
		
		$data .='<span ><select name="prod_org_id_list" id="prod_org_id_list" onChange="loadProducts();"  class = "select2-me input-large marginr10">';
        $sql_get_org_id_list = "SELECT `org_id`,`org_name` FROM `tbl_oraganisation_master` WHERE `org_id` IN(SELECT distinct(prod_orgid) FROM `tbl_products_master`); ";
		$result_get_org_id_list = mysqli_query($db_con,$sql_get_org_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select Organisation</option>';
        while($row_get_org_id_list = mysqli_fetch_array($result_get_org_id_list))
		{
		$data .='<option id="org'.$row_get_org_id_list['org_id'].'" value="'.$row_get_org_id_list['org_id'].'">'.ucwords($row_get_org_id_list['org_name']).'</option>';
        }
		$data .='</select></span>';
       
	
		$data .='<span><select name="prod_brand_id_list" id="prod_brand_id_list"   class = "select2-me input-large marginr10">';
        $sql_get_brand_id_list = "SELECT `brand_id`,`brand_name` FROM `tbl_brands_master` WHERE `brand_id` IN (SELECT distinct(prod_brandid) FROM `tbl_products_master`) ";
		$result_get_brand_id_list = mysqli_query($db_con,$sql_get_brand_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select Brand</option>';
		while($row_get_brand_id_list = mysqli_fetch_array($result_get_brand_id_list))
		{
		$data .='<option id="brand'.$row_get_brand_id_list['brand_id'].'" value="'. $row_get_brand_id_list['brand_id'].'">'.ucwords($row_get_brand_id_list['brand_name']).'</option>';
        }
		$data .='</select></span>';
		
		$data .='<select name="grade" id="grade"   class = "select2-me input-large marginr10 input-large">';
        $sql_get_brand_id_list = "SELECT `brand_id`,`brand_name` FROM `tbl_brands_master` WHERE `brand_id` IN (SELECT distinct(prod_brandid) FROM `tbl_products_master`) ";
		$result_get_brand_id_list = mysqli_query($db_con,$sql_get_brand_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select Grade</option>';
		$sql_sub_child_flit_data 		= "	SELECT distinct filt_id,filt_name,filt_slug ";
									$sql_sub_child_flit_data 		.= " FROM `tbl_filters` tf ";
									$sql_sub_child_flit_data 		.= " where tf.filt_status != 0 ";
									$sql_sub_child_flit_data 		.= " 	and tf.filt_type = '15' ";
									$sql_sub_child_flit_data 		.= " 	and tf.filt_sub_child = 'child' ";
									$sql_sub_child_flit_data 		.= " order by filt_sort_order ASC ";
                                                
                                    $result_sub_child_filt_data		= mysqli_query($db_con,$sql_sub_child_flit_data) or die(mysqli_error($db_con));
                                    while($row_sub_child_filt_data = mysqli_fetch_array($result_sub_child_filt_data))
                                    {	
										if($row_sub_child_filt_data['filt_name']!="none")
										{
											
											$data .='<option  id="grade'.$row_sub_child_filt_data['filt_id'].'" value="'. $row_sub_child_filt_data['filt_id'].'">'. ucwords($row_sub_child_filt_data['filt_name']).'</option>';
                                                   
											
										}
									} 
		$data .='</select>';
		
		$data .= '<script type="text/javascript">';
		$data .= '$(".select2-me").select2();';	
		$data .= '</script>';
         // brand end   
		$data .='<span><input class="input keyup-char input-large" onkeypress="return searchProduct(event);" type="text" placeholder="Search here..." name="srch" id="srchpkg_prod"></span>';
		$data .='<input style="" class=" btn-success"  id="filter" onClick="getProducts(0);" value="Filter" type="button">';
	    $data .='<input style="" class=" btn-success"  id="check_prod"  value="0" type="hidden">';
		
		                                                                          
	    $data .='&nbsp;&nbsp;';
		$data .= '';
        $data .='<div class="" id="product_table" style="height:300px;display:none">';
		$data .='</div>';//product table end
		$data .='</div>';// count end
		
		$data .= '</div>';
		$data .= '</div> <!-- Capmaign status-->';
		$data .='<div id="sections"<br></div>';
		$data .= '<div class="form-actions">';
		$data .= '<button type="submit" id="reg_submit_add" name="reg_submit_add" class="btn-success">Add Products</button>';					
		$data .= '</div> <!-- Save and cancel -->';			
		
		$response_array = array("Success"=>"Success","resp"=>$data,"stud_pkg_info_id"=>-1);
		
	
		}
		
		echo json_encode($response_array);exit();
	
}


?>