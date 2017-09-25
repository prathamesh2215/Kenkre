<?php // created by satish on 8 nov 2016
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
$uid	= $_SESSION['panel_user']['id'];
$utype	= $_SESSION['panel_user']['utype'];

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
if((isset($obj->filter)) == "1" && isset($obj->filter))
  { 
  	
   $page 			= $obj->page;	
	$req 			= $obj->req;
	$per_page		= 20;

	$cur_page 		= $page;
	if($req==1)
	{  
		$start1 	   	   	= $page + $per_page;
		$start = $page +1;
	}
	else
	{
		$start1 	 = $page + $per_page;
		$start       = $page;
	}
		 
		 
	$sr_no =$start + 1;
         
	$stud_pkg_info_id=$obj->stud_pkg_info_id;
	
	$existing_product = array();
		if($obj->stud_pkg_info_id !=0)
		{ 
			$info_id 			    = $obj->info_id;
			$sql_get_products       = "SELECT * FROM tbl_student_package_info ";
			$sql_get_products      .=" WHERE stud_pkg_info_id = ".$stud_pkg_info_id."";
			$result_get_products    = mysqli_query($db_con,$sql_get_products) or die(mysqli_error($db_con));
			$row_data 				= mysqli_fetch_array($result_get_products);
			$existing_product 		= $row_data['stud_pkg_products'];
		}
	$response_array = array();	
	$start_offset   = 0;
	$cat_id     	= $obj->cat_id;
	$prod_cat_id     	= $cat_id;
	
    $brand_id     	= $obj->brand_id;
	$org_id     	= $obj->org_id;
	$cat_txt     	= $obj->cat_txt;
	$subcat_txt     = $obj->subcat_txt;
	$brand_txt     	= $obj->brand_txt;
	$org_txt     	= $obj->org_txt;
	$search_text	= $obj->search_text;
	$section_id     = $obj->section_id;
	$grade_txt     	= $obj->grade_txt;
	$grade     	    = $obj->grade;
	
        $breadcrumbs='';
	    $sql_load_data   = " SELECT DISTINCT tpm.prod_id ,  tpm.*,tbm.brand_name, tom.org_name, tpi.* ";
		$sql_load_data  .= " FROM tbl_products_master AS tpm INNER JOIN tbl_oraganisation_master AS tom ";
		$sql_load_data  .= " 	ON tpm.prod_orgid = tom.org_id INNER JOIN tbl_products_images AS tpi ";
		$sql_load_data  .= " 	ON tpm.prod_id = tpi.prod_img_prodid INNER JOIN tbl_brands_master AS tbm ";
		$sql_load_data  .= "    ON tpm.prod_brandid = tbm.brand_id ";
		$sql_load_data  .= " WHERE 1=1 ";
		$sql_load_data  .= " 	AND tpi.prod_img_status = '1' ";
        $sql_load_data  .= " 	AND tpi.prod_img_type = 'main' ";
		
		if($obj->stud_pkg_info_id != 0 && $existing_product !="")
		{
			$sql_load_data  .= " AND tpm.prod_id NOT IN (".$existing_product.") ";
		}
		
		if($prod_cat_id !='')
		{  
		 
		    $sql_load_data  .= " AND tpm.prod_id IN (SELECT DISTINCT(prodcat_prodid) FROM tbl_product_cats WHERE ";
			$sql_load_data  .= " prodcat_prod_status='1' AND prodcat_catid IN (SELECT tcpm1.`cat_id` ";
			$sql_load_data  .= "FROM `tbl_category` AS tc INNER JOIN tbl_category_path_master AS tcpm1 ";
			$sql_load_data  .=" ON tc.cat_id=tcpm1.cat_id ";
			$sql_load_data  .=" WHERE tcpm1.path_id='".$prod_cat_id."' ";
			$sql_load_data  .=" AND tc.`cat_name`!='none' ";
			$sql_load_data  .=" AND tc.`cat_status`='1')) ";
		 
		 /*$sql_load_data  .= " AND tpm.prod_id IN (SELECT DISTINCT(prodcat_prodid) FROM tbl_product_cats WHERE ";
		 $sql_load_data  .= " prodcat_prod_status='1' AND prodcat_catid = ".$prod_cat_id.") ";*/
			
		 $breadcrumbs    .="<strong>Category: </strong>".$cat_txt."  ";
		}
		
		if($org_id != '')
		{
			$sql_load_data  .= " AND tpm.prod_orgid = '".$org_id."' ";
		  if($prod_cat_id !="")
		   {
				$breadcrumbs    .=" -> ";
		   }
			$breadcrumbs    .="<strong> Organisation: </strong>".$org_txt."  ";
		}
		
		if($brand_id != '')
		{
			$sql_load_data  .= " AND tpm.prod_brandid = '".$brand_id."' ";	
			if($org_id != '' || $prod_cat_id !="")
		{
			$breadcrumbs    .="  -> " ;
		}
			$breadcrumbs    .="<strong> Brand: </strong>".$brand_txt." ";
		}
		
		if($search_text != "")
		{
			$sql_load_data .= " and ( tom.org_name like '%".$search_text."%'or tpm.prod_name like '%".$search_text."%'";
			$sql_load_data .= " or tbm.brand_name like '%".$search_text."%'";
			$sql_load_data .= " or tpm.prod_meta_tags like '%".$search_text."%' ) ";
				if($org_id != '' || $prod_cat_id !="" || $brand_id != '')
				{	
					$breadcrumbs    .="  -> ";
				}
			$breadcrumbs    .=" <strong>Search By: </strong>". $search_text;
		}
		 if($grade !='')
		{   
		     $sql_load_data  .= " AND tpm.prod_id IN ( SELECT DISTINCT(prodfilt_prodid) FROM tbl_product_filters WHERE prodfilt_filtid_child = ".$grade." AND prodfilt_status='1') ";
			if($org_id != '' || $prod_cat_id !="" || $brand_id != '' || $search_text !="")
				{	
					$breadcrumbs    .="  -> ";
				}
			 $breadcrumbs    .="Grade : ".$grade_txt."  ";
		}
	    $sql_load_data .= " ORDER BY tpm.prod_id ASC LIMIT $start, $per_page   ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
		$num_rows 		  = mysqli_num_rows($result_load_data);
		if($num_rows > 0)
		{    
		   	$filter_data  = "";
			
			//------------------------for load more id--->
			if($req==0)
			{  
		    $filter_data .= $breadcrumbs;
			$filter_data .= '<table id="tbl_user" class="table table-bordered  scroll">';
      		$filter_data .= '<thead>';
    	  	$filter_data .= '<tr>';
			$filter_data .= '<th style="text-align:center;width:10%">Select Product</th>';
       		  //	$filter_data .= '<th style="text-align:center">Sr No.</th>';
			$filter_data .= '<th style="text-align:center">Product Name</th>';
			$filter_data .= '<th style="text-align:center">Image</th>';
			$filter_data .= '<th style="text-align:center">Organisation</th>';
			$filter_data .= '<th style="text-align:center">Brand</th>';
			$filter_data .= '</tr>';
      		$filter_data .= '</thead>';
		    $filter_data .= '<tbody id="show_more">';
		   }
			$sr_no =1;
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{   
			    $sr_no++;
				$prod_orgid				= $row_load_data['prod_orgid'];
				$prod_catid				= $row_load_data['prod_catid'];
				$prod_subcatid			= $row_load_data['prod_subcatid'];
				$prod_id				= $row_load_data['prod_id'];
				
				$filter_data .= '<tr>';				
				$filter_data .= '<td style="text-align:center;width:10%"><input type="checkbox" class="sel_prod'.$section_id.'" id="sel_prod'.$section_id.'" name="prod[]" value="'.$row_load_data['prod_id'].'" > </td>';				
			    //	$filter_data .= '<td style="text-align:center">'.$sr_no++.'</td>';
				$filter_data .= '<td style="text-align:center">'.$row_load_data['prod_name'].'</td>';
				
				$dir 		  = "../images/planet/org";
				
				$org_dir 	= $dir.$prod_orgid;
				
				$cat_dir 	= $org_dir."/cat".$prod_catid;
				
				$subcat_dir = $cat_dir."/subcat".$prod_subcatid;		
				
				$prod_dir 	= $org_dir."/prod_id_".$prod_id;				
				
				$imagepath	= $prod_dir."/small/".$row_load_data['prod_img_file_name'];
				
				if(is_file($imagepath))
				{
					$filter_data .= '<td style="text-align:center" ><img style="width:50px;height:50px" src="'.$imagepath.'?'.rand().'"/></td>';
				}
				else
				{
					$filter_data .= '<td style="text-align:center" ><img style="text-align:center;width:50px;height:50px" src="../images/no-image.jpg"/></td>';
				}
				
				$filter_data .= '<td style="text-align:center">'.$row_load_data['org_name'].'</td>';
				$filter_data .= '<td style="text-align:center">'.$row_load_data['brand_name'].'</td>';
				$filter_data .= '</tr>';
				//in_array end
			}	
			
			//--------------------- not display when show more request-------------------------------------------------//
			 if($req==0)
		   {
      		$filter_data .= '</tbody>';
      		$filter_data .= '</table>';	
			
		    $filter_data .= '<br><span style="padding-top:10px;text-align:center">';
			
			
			$filter_data .='<input id="show_more_btn" onClick="getProducts(1)" ';
			$filter_data .=' type="button" class="btn-showmore" value="Show More" /></span><br>';
		
			}
			//---------------------end not display when show more request-------------------------------------------------//
			
			$response_array = array("Success"=>"Success","resp"=>$filter_data,"breadcrumbs"=>$breadcrumbs,"result"=>$sql_load_data,"page"=>$start1,"product_count"=>$sr_no);	
			
			
			
							
		}
		else
		{
		$response_array = array("Success"=>"fail","resp"=>"No Products Available");
		}
		
	    
	echo json_encode($response_array);
} // filter end here


if((isset($_POST['insert_req'])) == "1" && isset($_POST['insert_req']))
{   
	$school_id				= $_POST['school_id'];
	$status					= $_POST['status'];
	$prod					= $_POST['prod'];
	$prod					= implode(',',$prod);
	$start_date				= $_POST['start_date'];
	$end_date				= $_POST['end_date'];
	
	$response_array 		= array();	
	
	   $sql_insert_package  = " INSERT INTO `tbl_student_package_info`(`stud_pkg_sch_id`,`stud_pkg_status`, `stud_pkg_products`, ";
	   $sql_insert_package .=" `stud_pkg_start_date`, `stud_pkg_end_date`,stud_pkg_created_by,stud_pkg_created)";
	   $sql_insert_package .= "  VALUES ('".$school_id."','".$status."','".$prod."','".$start_date."','".$end_date."','".$uid."','".$datetime."') ";
	   $res_insert_package 	= mysqli_query($db_con, $sql_insert_package) or die(mysqli_error($db_con));
	   $pakage_id = $db_con->insert_id;
	
	if($res_insert_package){
	$response_array = array("Success"=>"Success","pakage_id"=>$pakage_id);		
	}
	else
	{
	$response_array = array("Success"=>"fail","resp"=>"Fail");	
	}
	echo json_encode($response_array);		
}

if((isset($obj->load_package)) == "1" && isset($obj->load_package))
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
		
		$sql_load_data  = " SELECT tsp.* ,ts.*, tcu.fullname ,(SELECT fullname FROM tbl_cadmin_users WHERE id = tsp.stud_pkg_modified_by ) AS name_mod ,";	
		$sql_load_data  .=" (SELECT sname FROM tbl_schools WHERE id = tsp.stud_pkg_sch_id ) AS school_name ";				
		$sql_load_data  .= " FROM `tbl_student_package_info` as tsp INNER JOIN tbl_cadmin_users AS tcu ";
		$sql_load_data  .= " 	ON tsp.stud_pkg_created_by = tcu.id ";
		$sql_load_data  .=" INNER JOIN tbl_schools as ts ON tsp.stud_pkg_sch_id = ts.id ";
		$sql_load_data  .="  WHERE 1=1  ";
		
		/*if($utype !=1)
		{
		$sql_load_data  .= " and tsp.stud_pkg_created_by ='".$uid."' ";	
		}*/
		if($search_text != "")
		{
		$sql_load_data .= " and (sname like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY stud_pkg_info_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{	
		    $result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));		
			
			$package_data  = "";	
			$package_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$package_data .= '<thead>';
    	  	$package_data .= '<tr>';
         	$package_data .= '<th style="text-align:center">Sr No.</th>';
			$package_data .= '<th style="text-align:center">School  Name</th>';
			$package_data .= '<th style="width:6%;text-align:center">Package Start Date</th>';
			$package_data .= '<th style="text-align:center">Package End Date</th>';
			$package_data .= '<th style="text-align:center">Created</th>';
			$package_data .= '<th style="text-align:center">Created By</th>';
			$package_data .= '<th style="text-align:center">Modified</th>';
			$package_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_student_package.php",1);
			if($dis)
			{			
			$package_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_student_package.php",1);
			if($edit){
			$package_data .= '<th style="text-align:center">Action</th>';
			}
			$del = checkFunctionalityRight("view_student_package.php",2);
			if($del)
			{			
			$package_data .= '<th style="text-align:center">';
			$package_data .= '<div style="text-align:center">';
			$package_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/>';
			$package_data .= '</div></th>';
			}
			$package_data .= '</tr>';
      		$package_data .= '</thead>';
      		$package_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{   
	    	  	$package_data .= '<tr>';				
				$package_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$package_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['school_name']).'"';
				$package_data .=' class="btn-link" id="'.$row_load_data['stud_pkg_info_id'].'" onclick="addMorePkg(this.id,\'view\',1);"></td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['stud_pkg_start_date'].'</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['stud_pkg_end_date'].'</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['stud_pkg_created'].'</td>';
				$package_data .= '<td style="text-align:center">'.ucwords($row_load_data['fullname']).'</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['stud_pkg_modified'].'</td>';
				$package_data .= '<td style="text-align:center">'.ucwords($row_load_data['name_mod']).'</td>';
				$dis = checkFunctionalityRight("view_student_package.php",1);
				if($dis)
				{				
				$package_data .= '<td style="text-align:center">';	
				if($row_load_data['stud_pkg_status'] == 1)
				{
				$package_data .= '<input type="button" value="Active" id="'.$row_load_data['stud_pkg_info_id'].'"';
				$package_data .= ' class="btn-success" onclick="changeStatus(this.id,0);">';
				}
				else
				{
				$package_data .= '<input type="button" value="Inactive" id="'.$row_load_data['stud_pkg_info_id'].'"';
				$package_data .=' class="btn-danger" onclick="changeStatus(this.id,1);">';
				}
				$package_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_student_package.php",1);
				
				if($edit)
				{				
					$package_data .= '<td style="text-align:center">';
					$package_data .= '<input type="button" value="Edit" id="'.$row_load_data['stud_pkg_info_id'].'"';
					$package_data .=' class="btn-warning" onclick="addMorePkg(this.id,\'edit\',1);"></td>';				
				}
				$del = checkFunctionalityRight("view_student_package.php",2);
				
				if($del)
				{					
					$package_data .= '<td><div class="controls" align="center">';
					$package_data .= '<input type="checkbox" value="'.$row_load_data['stud_pkg_info_id'].'" id="batch'.$row_load_data['stud_pkg_info_id'].'"';
					$package_data .='  name="batch'.$row_load_data['stud_pkg_info_id'].'" class="css-checkbox packges">';
					$package_data .= '<label for="batch'.$row_load_data['stud_pkg_info_id'].'" class="css-label"></label>';
					$package_data .= '</div></td>';										
				}
	          	$package_data .= '</tr>';	
				
			}	
      		$package_data .= '</tbody>';
      		$package_data .= '</table>';	
			$package_data .= '<script>';	
			//$package_data .= '$(".select2-me").prop("disabled","true");';	
			$package_data .= '</script>';	
			
			$package_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$package_data);					
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
if((isset($obj->load_add_pkg_part)) == "1" && isset($obj->load_add_pkg_part))
{
	$package_id 	= $obj->package_id;
	$req_type 	= $obj->req_type;
	$response_array = array();
 if($req_type != "")
 {
	if($req_type != "" && $req_type =="add")
	{
		$data = '';
		$data .='<input type="hidden" name="hid_page2" id="hid_page2" value="1">' ;	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select School<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .='<input type="hidden" name="insert_req" value="1">';
		$data .= ' <select name="school_id" id="school_id" class = "select2-me input-large" required>';
        $sql_get_school_id_list = "SELECT `sname`,`id` FROM `tbl_schools` WHERE  status = 1 AND  id NOT IN ";
		$sql_get_school_id_list .= " (SELECT DISTINCT stud_pkg_sch_id FROM tbl_student_package_info ) ";
		$result_get_school_id_list = mysqli_query($db_con,$sql_get_school_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select School</option>';
		while($row_get_school_id_list = mysqli_fetch_array($result_get_school_id_list))
		{
		$data .='<option id="brand'.$row_get_school_id_list['id'].'sec'.$count.'" value="'. $row_get_school_id_list['id'].'">'.ucwords($row_get_school_id_list['sname']).'</option>';
        }
		$data .='</select>';
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
		
		$response_array = array("Success"=>"Success","resp"=>$data);
		echo json_encode($response_array);exit();
	} // end add request
	
	if(($package_id != "" && $req_type == "edit") || ($package_id != "" && $req_type == "view"))
	{   $sql_package_sql 		= "Select * from tbl_student_package_info where  stud_pkg_info_id = '".$package_id."' ";
		$result_package_data 	= mysqli_query($db_con,$sql_package_sql) or die(mysqli_error($db_con));
		$row_pkg_data		= mysqli_fetch_array($result_package_data);		
	}
	
	// view request strat here
	if($req_type != "" && $req_type =="view")
	{  
	   $school_id = $row_pkg_data['stud_pkg_sch_id'];
	   
		$data .='<input type="hidden" name="stud_pkg_info_id" id="stud_pkg_info_id" value="'.$row_pkg_data['stud_pkg_info_id'].'">';	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select School</label>';
		$data .= '<div class="controls">';
		$data .= ' <select readonly name="school_id" id="school_id" class = "select2-me input-large">';
        $sql_get_school_id_list = "SELECT `sname`,`id` FROM `tbl_schools`  ";
		$result_get_school_id_list = mysqli_query($db_con,$sql_get_school_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select School</option>';
		while($row_get_school_id_list = mysqli_fetch_array($result_get_school_id_list))
		{
		$data .='<option id="" value="'. $row_get_school_id_list['id'].'"';
		if($school_id == $row_get_school_id_list['id'])
		{
			$data .=' selected ';
		}
		
		$data .='>';
		$data .= ucwords($row_get_school_id_list['sname']).'</option>';
        }
		$data .='</select>';
		$data .= '</div>';
		$data .= '</div> <!-- Campaign Name -->';  
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Start Date </label>';
		$data .= '<div class="controls">';
		
		$data .= '<input readonly type="datetime" value="'.$row_pkg_data['stud_pkg_start_date'].'"  id="start_date"  name="start_date" class="input-large keyup-char" data-rule-required="true" '; 	
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- start Date -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">End Date</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly type="datetime" value="'.$row_pkg_data['stud_pkg_end_date'].'" id="end_date" name="end_date" class="input-large keyup-char" data-rule-required="true" '; 	
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- End Date -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status</label>';
		$data .= '<div class="controls">';
		
		
		if($row_pkg_data['stud_pkg_status']==1)
		{
			$data .=' Active ';
		}
		
		
		
		if($row_pkg_data['stud_pkg_status']==0)
		{
			$data .=' Inactive ';
		}
		
		$data .= '</div>';
		$data .= '</div> <!-- End Date -->';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Products</label>';
		$data .= '<div class="controls">';
		$products =explode(',',$row_pkg_data['stud_pkg_products']);
		if(sizeof($products)>=1){
			if($products[0]!=""){
			$data .= '<table class="table table-bordered">';
				
		
            $data .= '</table>';
			$data .= '<div class="show_product_data" style="max-height: 300px;padding:10px 10px 10px;border-bottom: 1px solid #dadada;" >';
			
			$data .='<table id="tbl_user" class="table table-bordered dataTable scroll" style="height: 10px;overflow-y: scroll;width:100%;text-align:center">';
    	 	$data .= '<thead>';
    	  	$data .= '<tr>';
         	$data .= '<th style="text-align:center ;width:5%">Sr No.</th>';
			
			
			$data .= '<th style="text-align:center ;width:40%">Product Name</th>';
			$data .= '<th style="text-align:center">Image</th>';
			$data .= '<th style="text-align:center">Organization</th>';
			$data .= '</tr>';
      		$data .= '</thead>';
		 	$data .= '<tbody>';
			$product_count = 1;
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
		  
		    $data .= '<tr>';				
			$data .= '<td style="text-align:center ; width:4%">'.$product_count++.'</td>';	
						
			$data .= '<td style="text-align:center;width:42%">'.$row_prod_data['prod_name'].'</td>';
			
			$prod_orgid				= $row_prod_data['prod_orgid'];
			$prod_catid				= $row_prod_data['prod_catid'];
			$prod_subcatid			= $row_prod_data['prod_subcatid'];
			$prod_id				= $row_prod_data['prod_id'];
			$dir 		  = "../images/planet/org";
			$org_dir 	= $dir.$prod_orgid;
				
			$prod_dir 	= $org_dir."/prod_id_".$prod_id;					
			$imagepath	= $prod_dir."/small/".$row_prod_data['prod_img_file_name'];
			$data .= '<td style="text-align:center;width:15%"><img style="width:50px;height:50px" src="'.$imagepath.'?'.rand().'"/></td>';
			//$branch_data .= '<td style="text-align:center">'.$row_load_data['prod_img_file_name'].'</td>';
			$data .= '<td style="text-align:center">'.$row_prod_data['org_name'].'</td>';
			$data .= '</tr>';	
	
		}// if end
		}// for end
		  $data .= '</tbody>';
		  $data .= '</table>';
		  
		
		  
		  $data .='<br><br></div>';
		 }// if products end
		}
		
		
		
		
		$data .= '<script type="text/javascript">';
		$data .= '$(".select2-me").select2();';	
		$data .= '</script>';
         // brand end   
		
		
		                                                                          
	    $data .='&nbsp;&nbsp;';
		$data .= '';
     
		$data .='</div>';// count end
		
		$data .= '</div>';
		$data .= '</div> ';
		$data .='<div id="sections"<br></div>';
				
		
		$response_array = array("Success"=>"Success","resp"=>$data);
		echo json_encode($response_array);exit();} // end view request
	
	// edit request strat here
	if($req_type != "" && $req_type =="edit")
	 {  $school_id = $row_pkg_data['stud_pkg_sch_id'];
	     $data .='<input type="hidden" name="update_req" value="1">';
	    $data .='<input type="hidden" name="stud_pkg_info_id" id="stud_pkg_info_id" value="'.$row_pkg_data['stud_pkg_info_id'].'">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select School<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		
		$data .= ' <select name="school_id" id="school_id" class = "select2-me input-large" >';
        $sql_get_school_id_list = "SELECT `sname`,`id` FROM `tbl_schools`  ";
		$result_get_school_id_list = mysqli_query($db_con,$sql_get_school_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select School</option>';
		while($row_get_school_id_list = mysqli_fetch_array($result_get_school_id_list))
		{
		$data .='<option id="brand'.$row_get_school_id_list['id'].'sec'.$count.'" value="'. $row_get_school_id_list['id'].'"';
		if($school_id==$row_get_school_id_list['id'])
		{
			$data .=' selected ';
		}
		
		$data .='>';
		$data .= ucwords($row_get_school_id_list['sname']).'</option>';
        }
		$data .='</select>';
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
		
		$response_array = array("Success"=>"Success","resp"=>$data);
		echo json_encode($response_array);exit();
		
	} // end edit request
 }
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Not received");		
	}
	echo json_encode($response_array);
}


if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$stud_pkg_info_id			= $obj->package_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();
	$sql_update_status 		= " UPDATE `tbl_student_package_info` SET `stud_pkg_status`= '".$curr_status."' ,`stud_pkg_modified` = '".$datetime."' ,`stud_pkg_modified_by` = '".$uid."' WHERE `stud_pkg_info_id` = '".$stud_pkg_info_id."' ";
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



if((isset($obj->getsubcat)) == "1" && isset($obj->getsubcat))
{   
	$data 			="";
	$cat_id 		= $obj->cat_id;
	$section_id 		= $obj->section_id;
	$response_array = array();	
	
	$sql_get_subcat	= " SELECT cat_id, cat_name FROM `tbl_category` WHERE `cat_type` = '".$cat_id."' ";
	$result_getsubcat= mysqli_query($db_con,$sql_get_subcat) or die(mysqli_error($db_con));			
	
	if($result_getsubcat)
	{
		$flag = 1;	
	}			

	if($flag == 1)
	{   
	$data .='<option value="">Select Subcategory</option>';
	while($subcatlist = mysqli_fetch_array($result_getsubcat))
	{  
		  if($subcatlist['cat_name'] !="none")
	{
			$data .='<option id="subcat'.$subcatlist['cat_id'].'sec'.$section_id.'" value="'.$subcatlist['cat_id'].'">'.ucwords($subcatlist['cat_name']).'</option>';
	}
			
    }
		   $response_array = array("Success"=>"Success","resp"=>$data);			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Sub category Found.");
	}
	
	echo json_encode($response_array);	
}




if((isset($obj->delete_package)) == "1" && isset($obj->delete_package))
{   
	$packages 		= $obj->packages;
	foreach($packages as $pkg_id)
	{
	$sql_delete_pkg	= " DELETE FROM `tbl_student_package_info` WHERE `stud_pkg_info_id` = '".$pkg_id."' ";
	$result_delete_pkg= mysqli_query($db_con,$sql_delete_pkg) or die(mysqli_error($db_con));
	}
	$response_array = array("Success"=>"Success","resp"=>"Status Deleted Successfully.");
	
	echo json_encode($response_array);	
}





if((isset($obj->load_students)) == "1" && isset($obj->load_students))
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
		
		$sql_load_data  = " SELECT tsp.* ,ts.*,tc.cust_fname,tc.cust_lname, tcu.fullname ,(SELECT fullname FROM tbl_cadmin_users WHERE id = tsp.stud_package_modified_by ) AS name_mod ,";	
		$sql_load_data  .=" (SELECT sname FROM tbl_schools WHERE id = tsp.stud_package_school ) AS school_name , ";	
		$sql_load_data  .=" (SELECT filt_name FROM tbl_filters WHERE filt_id = tsp.stud_package_grade ) AS grade  ";
		$sql_load_data  .= " FROM `tbl_student_package` as tsp INNER JOIN tbl_cadmin_users AS tcu ";
		$sql_load_data  .= " 	ON tsp.stud_package_created_by = tcu.id ";
		$sql_load_data  .=" INNER JOIN tbl_schools as ts ON tsp.stud_package_school = ts.id ";
		$sql_load_data  .=" LEFT JOIN tbl_customer as tc ON tsp.stud_package_student_id = tc.cust_id ";
		$sql_load_data  .="   WHERE 1=1 ";
		
		if($utype !=1)
		{
		$sql_load_data  .= " and tsp.stud_package_created_by ='".$uid."' ";	
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (sname like '%".$search_text."%'or stud_package_parent_name like '%".$search_text."%' or stud_package_mobile_no like '%".$search_text."%'";
			$sql_load_data .= " or tc.cust_fname like '%".$search_text."%' or tc.cust_lname like '%".$search_text."%') ";	
	   }
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY stud_package_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{	
		    $result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));		
			
			$package_data  = "";	
			$package_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$package_data .= '<thead>';
    	  	$package_data .= '<tr>';
         	$package_data .= '<th style="text-align:center">Sr No.</th>';
			$package_data .= '<th style="width:6%;text-align:center">Student Name</th>';
			$package_data .= '<th style="width:6%;text-align:center">Parent Name</th>';
			$package_data .= '<th style="text-align:center">School  Name</th>';
			$package_data .= '<th style="text-align:center">Mobile Number</th>';
			$package_data .= '<th style="text-align:center">Customer Name (PE)</th>';
			$package_data .= '<th style="text-align:center">Grade</th>';
			$package_data .= '<th style="text-align:center">Created</th>';
			$package_data .= '<th style="text-align:center">Created By</th>';
						
			$package_data .= '<th style="text-align:center">Status</th>';						
			$package_data .= '<th style="text-align:center">Product</th>';	
			$package_data .= '<th style="text-align:center">Action</th>';
			$delete = checkFunctionalityRight("view_student_package.php",2);
				if($delete)
				{
					$package_data .= '<th style="text-align:center"><input type="button" value="Delete"';
				    $package_data .=' class="btn-danger delete_stud" id="'.$row_load_data['stud_package_id'].'" onclick="multipleDelete();"></th>';
				}
			$package_data .= '</tr>';
      		$package_data .= '</thead>';
      		$package_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{   
	    	  	$package_data .= '<tr>';				
				$package_data .= '<td style="text-align:center">'.++$start_offset.'</td>';
				$package_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['stud_pkg_stud_name']).'"';
				$package_data .=' class="btn-link" id="'.$row_load_data['stud_package_id'].'" onclick="addMoreStd(this.id,\'view\',1);"></td>';	
				//$package_data .= '<td style="text-align:center">'.$row_load_data[''].'</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['stud_package_parent_name'].'</td>';			
				$package_data .= '<td style="text-align:center">'.ucwords($row_load_data['sname']).'</td>';
			    $package_data .= '<td style="text-align:center">'.$row_load_data['stud_package_mobile_no'].'</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['cust_fname'].' '.$row_load_data['cust_lname'].'</td>';
				$package_data .= '<td style="text-align:center">'.ucwords($row_load_data['grade']).'</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['stud_package_created'].'</td>';
				$package_data .= '<td style="text-align:center">'.ucwords($row_load_data['fullname']).'</td>';
								
				
				if($row_load_data['stud_package_student_id'] != 0 && $row_load_data['student_package_mobile_verify']==1)
				{
				$package_data .= '<td style="text-align:center">';	
				$package_data .= '<input type="button" value="Verified" ';
				$package_data .= ' class="btn-success" >';
				$package_data .= '</td>';
				$package_data .= '<td style="text-align:center">';	
				$package_data .= '<input type="button" value="View Product" onclick="view_products('.$row_load_data['stud_package_student_id'].','.$row_load_data['stud_package_school'].');"';
				$package_data .= ' class="btn-success" >';
				$package_data .= '</td>';
				}
				else
				{
				$package_data .= '<td style="text-align:center">';	
				$package_data .= '<input type="button" value="Not verified" ';
				$package_data .=' class="btn-danger">';
				$package_data .= '</td>';
				$package_data .= '<td style="text-align:center">';	
				$package_data .= '-';
				$package_data .= '</td>';
				}
				
				$edit = checkFunctionalityRight("view_student_package.php",1);
				if($edit)
				{				
					$package_data .= '<td style="text-align:center">';
					$package_data .= '<input type="button" value="Edit" id="'.$row_load_data['stud_package_id'].'"';
					$package_data .=' class="btn-warning" onclick="addMoreStd(this.id,\'edit\',1);"></td>';				
				}
				$delete = checkFunctionalityRight("view_student_package.php",2);
				if($delete)
				{
					$package_data .= '<td><div class="controls" align="center">';
					$package_data .= '<input type="checkbox" value="'.$row_load_data['stud_package_id'].'" id="stud'.$row_load_data['stud_package_id'].'" name="customers'.$row_load_data['coup_id'].'" class="css-checkbox students">';
					$package_data .= '<label for="stud'.$row_load_data['stud_package_id'].'" class="css-label"></label>';
					$package_data .= '</div></td>';
				}	
				
				
				
	          	$package_data .= '</tr>';	
				
			}	
      		$package_data .= '</tbody>';
      		$package_data .= '</table>';	
			$package_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$package_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}





if((isset($_POST['update_req'])) == "1" && isset($_POST['update_req']))
{  
    $school_id				= $_POST['school_id'];
	$status					= $_POST['status'];
	$prod					= $_POST['prod'];
	$products				= implode(',',$prod);
	$start_date				= $_POST['start_date'];
	$end_date				= $_POST['end_date'];
	$stud_pkg_info_id       = $_POST['stud_pkg_info_id'];
	
	$sql_get_pkg 		="SELECT * FROM  tbl_student_package_info WHERE stud_pkg_info_id = '".$stud_pkg_info_id."' ";
	$result_get_pkg 	= mysqli_query($db_con,$sql_get_pkg) or die(mysqli_error($db_con));
	$row_pkg        =mysqli_fetch_array($result_get_pkg);
	$prod1="";
	if($row_pkg['stud_pkg_products'] !="" && $products!="")
	{
	$prod=$row_pkg['stud_pkg_products'].',';
	}
	else
	{
	$prod=$row_pkg['stud_pkg_products'];
	}
    $prod =$prod.$products;
	
	
	
	$sql_update_status 		= " UPDATE `tbl_student_package_info` SET `stud_pkg_sch_id`= '".$school_id."',`stud_pkg_status`= '".$status."',";
	$sql_update_status     .= " stud_pkg_products = '".$prod."',stud_pkg_start_date = '".$start_date."',stud_pkg_complete_status=1,stud_pkg_end_date = '".$end_date."',stud_pkg_modified_by='".$uid."',stud_pkg_modified='".$datetime."'  WHERE `stud_pkg_info_id` = '".$stud_pkg_info_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	
	if($result_update_status)
	{
		$response_array = array("Success"=>"Success","resp"=>"Section Updation Success.","stud_pkg_info_id"=>$stud_pkg_info_id,"products"=>$products,"prod1"=>$prod1);			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Updation failed.");
	}
	echo json_encode($response_array);	
}








if((isset($obj->remove_products)) == "1" && isset($obj->remove_products))
{   
	$data 			="";
	$remove_data 	= $obj->delete_data;
	$package_id 	= $obj->package_id;
	$response_array = array();	
	$new_products   = array();
	$sql_get_product	=" SELECT * FROM tbl_student_package_info WHERE stud_pkg_info_id = '".$package_id."'"; 
	$result_add_product= mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
	$product_data =mysqli_fetch_array($result_add_product);
	$prod =explode(',',$product_data['stud_pkg_products']);
	for($i=0;$i<sizeof($prod);$i++)
	{
	if(!in_array($prod[$i],$remove_data)){ 
	array_push($new_products,$prod[$i]);
	}
	
	   		
	}
	$prod = implode(',',$new_products);
	$complete_status=1;
	if($prod == "")
	{
	 $complete_status=0;     
	}
	 $sql_update_status 		= " UPDATE `tbl_student_package_info` SET stud_pkg_products='".$prod."',`stud_pkg_complete_status`= '".$complete_status."',stud_pkg_modified_by='".$uid."',stud_pkg_modified='".$datetime."'  WHERE `stud_pkg_info_id` = '".$package_id."' ";
	 $result_update_status 	    = mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	
	if($result_update_status){
	 $response_array = array("Success"=>"Success","resp"=>$package_id);
	} else {
		$response_array = array("Success"=>"fail","resp"=>"Deletion Failed.");
	}
	
	echo json_encode($response_array);	
}


if(isset($_FILES['file']))
{
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	
	
	move_uploaded_file($sourcePath,$inputFileName) ;
	
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$branch_id 	= 0;
	$msg		= '';
	$insertion_flag	= 0;
	$response_array = array();
	
	try 
	{
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	} 
	catch(Exception $e) 
	{
		die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	
	$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
	//echo json_encode($arrayCount);exit();
	$insertion_flag = 1;  
	if(strcmp($inputFileName, "")!==0)
	{   
	    
		for($i=2;$i<=$arrayCount;$i++)
		{   
		
		    $school_id 				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"])), ENT_HTML5);
			$stud_name 				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["B"])), ENT_HTML5);
			$grade				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["C"])), ENT_HTML5);
			$parent_name 				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["D"])), ENT_HTML5);
			$mobile_no				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"])), ENT_HTML5);
			//$start_date				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"])), ENT_HTML5);
			//$end_date				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["G"])), ENT_HTML5);
			
			$sql_get_filt_id	=" SELECT * FROM tbl_filters WHERE filt_name like '".$grade."'";
			$res_get_filt_id= mysqli_query($db_con,$sql_get_filt_id) or die(mysqli_error($db_con));
			$num_filt = mysqli_num_rows($res_get_filt_id);
			if($num_filt !=0)
			{
				$row_filt = mysqli_fetch_array($res_get_filt_id);
				$grade = $row_filt['filt_id'];
			}
			else
			{
				$grade ="";
			}
			
			
		if($parent_name!='' && $mobile_no!='')
			{
			$reason="";
			$status=0;
			if(!preg_match('/[^a-z\s]/i',$parent_name) && is_numeric($mobile_no) && strlen($mobile_no) >9 && strlen($mobile_no) <11)
			{
				$status = 1;
			}
			if(preg_match('/[^a-z\s]/i',$parent_name))
			{
				$reason .="Parent name contains only letters,";
			}
			
			if(!is_numeric($mobile_no) || strlen($mobile_no)!=10)
			{
				$reason .="Mobile Number contains 10 Digit Number";
			}
			
			
			$sql_check_data	   =" SELECT * FROM tbl_student_package WHERE stud_package_parent_name like '".$parent_name."' AND stud_package_grade like '".$grade."' AND `stud_package_school` = '".$school_id."'";
			$sql_check_data   .="  AND stud_package_school like '".$school_id."'  AND stud_package_mobile_no like '".$mobile_no."' ";
			$res_check_data    = mysqli_query($db_con,$sql_check_data) or die(mysqli_error($db_con));
			$num_check = mysqli_num_rows($res_check_data);
			
			if($mobile_no !="" && $school_id !="" && $grade!="" && $parent_name !="" )
			{
			$sql_insert_package  = " INSERT INTO `tbl_student_package`(`stud_package_parent_name`,stud_pkg_stud_name,stud_package_grade,`stud_package_school`, `stud_package_mobile_no`, ";
			$sql_insert_package .="  stud_package_created_by,stud_package_created,complete_status,reason_for_wrong)";
			$sql_insert_package .= "  VALUES ('".$parent_name."','".$stud_name."','".$grade."','".$school_id."','".$mobile_no."','".$uid."','".$datetime."','".$status."','".$reason."') ";
			$res_insert_package 	= mysqli_query($db_con, $sql_insert_package) or die(mysqli_error($db_con));
			$insertion_flag ++;  
			}
			
		}
			
		}
		if($insertion_flag > 0)
		{
			$response_array = array("Success"=>"Success","resp"=>"Insertion Successfully");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Insertion Error");			
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Try to upload Different File");
	}
	echo json_encode($response_array);
}

if($obj->student_req_type == "add" && isset($obj->student_req_type))
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
		$data .= '<label for="tasktitel" class="control-label">Student Name</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="stud_name" placeholder="Enter Student Name" name="stud_name" class="input-xlarge"  ';
		$data .= '/>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Parent Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="parent_name" placeholder="Enter Parent name" Name="parent_name" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select School <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
	    $data .= '<select style="width:30%" id ="school_id" name="school_id" data-rule-required="true" class = "js-example-basic-single" >';
        $sql_get_school_id_list = "SELECT `sname`,`id` FROM `tbl_schools` WHERE  status = 1  ";
		$result_get_school_id_list = mysqli_query($db_con,$sql_get_school_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select School</option>';
		while($row_get_school_id_list = mysqli_fetch_array($result_get_school_id_list))
		{
		$data .='<option id='.$row_get_school_id_list['id'].' value="'. $row_get_school_id_list['id'].'">'.ucwords($row_get_school_id_list['sname']).'</option>';
        }
		$data .='</select>'; 
		$data .= '</div>';
		$data .= '</div>';	
        
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select Grade <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
	    $data .= '<select style="width:30%" id ="grade" name="grade" data-rule-required="true" class = "js-example-basic-single" >';
		$data .='<option value=" ">Select Grade</option>';
		$sql_sub_child_flit_data 		 = "	SELECT distinct filt_id,filt_name,filt_slug ";
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
		$data .= '</div>';
		$data .= '</div>';
		
        $data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Mobile Number <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="mob_no" onkeypress="return numsonly(event);" minlength="10" maxlength="10"  placeholder="Enter Mobile Number " Name="mob_no" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	

        
;
  
		$data .= '<br><button class="btn-info" type="submit" onclick="">Add Student</button>';
        $response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);exit();
}
if($_POST['add_student_req'] == "1" && isset($_POST['add_student_req']))
{ 
	$student_name 	= $_POST['stud_name'];
	$parent_name 	= $_POST['parent_name'];
	$school_id 		= $_POST['school_id'];
	$grade 			= $_POST['grade'];
	$mobile_no	    = $_POST['mob_no'];
	$response_array =array();
	$sql_insert_student  = " INSERT INTO `tbl_student_package`(`stud_pkg_stud_name`,`stud_package_parent_name`, `stud_package_school`, ";
	$sql_insert_student .=" `stud_package_grade`, `stud_package_mobile_no`,stud_package_created_by,stud_package_created)";
	$sql_insert_student .= "  VALUES ('".$student_name."','".$parent_name."','".$school_id."','".$grade."','".$mobile_no."','".$uid."','".$datetime."') ";
	$res_insert_student 	= mysqli_query($db_con, $sql_insert_student) or die(mysqli_error($db_con));
	if($res_insert_student)
	{
		$response_array = array("Success"=>"Success","resp"=>"Student data added successfully");
	}
	else
	{
		$response_array = array("Success"=>"Success","resp"=>"Student data not added");
	}
 echo json_encode($response_array);
}
if($obj->student_req_type == "edit" && isset($obj->student_req_type))
{     
        $stud_pkg_id = $obj->stud_pkg_id;
		
		$sql_get_package = " SELECT * FROM tbl_student_package WHERE stud_package_id ='".$stud_pkg_id."' ";
		$res_get_package = mysqli_query($db_con,$sql_get_package) or die(mysqli_error($db_con));
		
		$row  			 =mysqli_fetch_array($res_get_package);
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
		$data .='<input type="hidden" name="stud_package_id" value="'.$stud_pkg_id.'">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Student Name</label>';
		$data .= '<div class="controls">';
		$data .= '<input value="'.$row['stud_pkg_stud_name'].'" type="text" id="stud_name" placeholder="Enter Student Name" name="stud_name" class="input-xlarge"  ';
		$data .= '/>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Parent Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" value="'.$row['stud_package_parent_name'].'" id="parent_name" placeholder="Enter Parent name" Name="parent_name" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select School <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
	    $data .= '<select style="width:30%" id ="school_id" name="school_id" data-rule-required="true" class = "js-example-basic-single" >';
        $sql_get_school_id_list = "SELECT `sname`,`id` FROM `tbl_schools` WHERE  status = 1  ";
		$result_get_school_id_list = mysqli_query($db_con,$sql_get_school_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select School</option>';
		while($row_get_school_id_list = mysqli_fetch_array($result_get_school_id_list))
		{
		$data .='<option id='.$row_get_school_id_list['id'].' value="'. $row_get_school_id_list['id'].'" ';
		if($row_get_school_id_list['id']==$row['stud_package_school'])
		{
			$data .=' selected ';
		}
		$data .='>'.ucwords($row_get_school_id_list['sname']).'</option>';
        }
		$data .='</select>'; 
		$data .= '</div>';
		$data .= '</div>';	
        
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select Grade <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
	    $data .= '<select style="width:30%" id ="grade" name="grade" data-rule-required="true" class = "js-example-basic-single" >';
		$data .='<option value=" ">Select Grade</option>';
		$sql_sub_child_flit_data 		 = "	SELECT distinct filt_id,filt_name,filt_slug ";
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
				 $data .='<option  id="grade'.$row_sub_child_filt_data['filt_id'].'" value="'. $row_sub_child_filt_data['filt_id'].'" ';
				 if($row_sub_child_filt_data['filt_id']==$row['stud_package_grade'])
				 {
					 $data .=' selected ';
				 }
				 $data .=' >'. ucwords($row_sub_child_filt_data['filt_name']).'</option>';
				}
			} 
		$data .='</select>'; 
		$data .= '</div>';
		$data .= '</div>';
		
        $data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Mobile Number <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="mob_no" value="'.$row['stud_package_mobile_no'].'" onkeypress="return numsonly(event);" minlength="10" maxlength="10"  placeholder="Enter Mobile Number " Name="mob_no" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	

        

  
		$data .= '<br><button class="btn-info" type="submit" onclick="">Update Student</button>';
        $response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);exit();
}
if($_POST['edit_student_req'] == "1" && isset($_POST['edit_student_req']))
{ 
	$stud_package_id 	= $_POST['stud_package_id'];
	$stud_name	 		= $_POST['stud_name'];
	$parent_name 		= $_POST['parent_name'];
	$school_id 			= $_POST['school_id'];
	$grade 				= $_POST['grade'];
	$mobile_no	   		 = $_POST['mob_no'];
	$response_array =array();
	
	$sql_update_package 		= " UPDATE `tbl_student_package` SET `stud_pkg_stud_name`= '".$stud_name."',`stud_package_parent_name`= '".$parent_name."',";
	$sql_update_package     .= " stud_package_school = '".$school_id."',stud_package_grade = '".$grade."',stud_package_mobile_no = '".$mobile_no."',stud_package_modified_by='".$uid."',stud_package_modified='".$datetime."'  WHERE `stud_package_id` = '".$stud_package_id."' ";
	$res_update_package 	= mysqli_query($db_con,$sql_update_package) or die(mysqli_error($db_con));
	
	if($res_update_package)
	{
		$response_array = array("Success"=>"Success","resp"=>"Student data Updated successfully");
	}
	else
	{
		$response_array = array("Success"=>"Success","resp"=>"Student data not Updated");
	}
 echo json_encode($response_array);
}
if($obj->student_req_type == "view" && isset($obj->student_req_type))
{     
        $stud_pkg_id = $obj->stud_pkg_id;
		
		$sql_get_package = " SELECT * FROM tbl_student_package WHERE stud_package_id ='".$stud_pkg_id."' ";
		$res_get_package = mysqli_query($db_con,$sql_get_package) or die(mysqli_error($db_con));
		
		$row  			 =mysqli_fetch_array($res_get_package);
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
		$data .='<input type="hidden" name="stud_package_id" value="'.$stud_pkg_id.'">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Student Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly="readonly" value="'.$row['stud_pkg_stud_name'].'" type="text" id="stud_name" placeholder="Enter Student Name" name="stud_name" class="input-xlarge" data-rule-required="true" ';
		$data .= '/>';
		$data .= '</div>';	
		$data .= '</div>';
		
		$data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Parent Name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly="readonly" type="text" value="'.$row['stud_package_parent_name'].'" id="parent_name" placeholder="Enter Parent name" Name="parent_name" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select School <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
	    $data .= '<select disabled="disabled" style="width:30%" id ="school_id" name="school_id" data-rule-required="true" class = "js-example-basic-single" >';
        $sql_get_school_id_list = "SELECT `sname`,`id` FROM `tbl_schools` WHERE  status = 1  ";
		$result_get_school_id_list = mysqli_query($db_con,$sql_get_school_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select School</option>';
		while($row_get_school_id_list = mysqli_fetch_array($result_get_school_id_list))
		{
		$data .='<option id='.$row_get_school_id_list['id'].' value="'. $row_get_school_id_list['id'].'" ';
		if($row_get_school_id_list['id']==$row['stud_package_school'])
		{
			$data .=' selected ';
		}
		$data .='>'.ucwords($row_get_school_id_list['sname']).'</option>';
        }
		$data .='</select>'; 
		$data .= '</div>';
		$data .= '</div>';	
        
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Select Grade <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
	    $data .= '<select disabled="disabled" style="width:30%" id ="grade" name="grade" data-rule-required="true" class = "js-example-basic-single" >';
		$data .='<option value=" ">Select Grade</option>';
		$sql_sub_child_flit_data 		 = "	SELECT distinct filt_id,filt_name,filt_slug ";
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
				 $data .='<option  id="grade'.$row_sub_child_filt_data['filt_id'].'" value="'. $row_sub_child_filt_data['filt_id'].'" ';
				 if($row_sub_child_filt_data['filt_id']==$row['stud_package_grade'])
				 {
					 $data .=' selected ';
				 }
				 $data .=' >'. ucwords($row_sub_child_filt_data['filt_name']).'</option>';
				}
			} 
		$data .='</select>'; 
		$data .= '</div>';
		$data .= '</div>';
		
        $data .= '<div class="control-group">';		
		$data .= '<label for="tasktitel" class="control-label">Mobile Number <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly="readonly" type="text" id="mob_no" value="'.$row['stud_package_mobile_no'].'" onkeypress="return numsonly(event);" minlength="10" maxlength="10"  placeholder="Enter Mobile Number " Name="mob_no" class="input-xlarge" data-rule-required="true" />';
		$data .= '</div>';
		$data .= '</div>';	

        $response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);exit();
}
if((isset($obj->delete_student)) == "1" && isset($obj->delete_student))
{   
	$data 			="";
	$students 		= $obj->students;
	$response_array =array();
	foreach($students as $stud_package_id)
	{
	$sql_delete_student	= " DELETE FROM `tbl_student_package`  WHERE `stud_package_id` = '".$stud_package_id."' ";
	$res_delete_student= mysqli_query($db_con,$sql_delete_student) or die(mysqli_error($db_con));
	}
	 if($res_delete_student){
     $response_array = array("Success"=>"Success","resp"=>"");
     echo json_encode($response_array);
     }
     else
     {  
     	$response_array = array("Success"=>"Fail","resp"=>"");
     echo json_encode($response_array);
	 }
	
}
?>