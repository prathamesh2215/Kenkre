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
					$data .= '<option id="cat'.$row_get_children_frm_cm['cat_id'].'sec'.$count.'" value="'.$row_get_children_frm_cm['cat_id'].'">'.substr(ucwords($opt_value),0,-3).'</option>';
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





//----------------------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------- Filter start here -------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------//

if((isset($obj->filter)) == "1" && isset($obj->filter))
{
	//echo json_encode(($obj->info_id));exit();
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
	
	//-----------------------check existing Product-------------//
	if($obj->info_id != -1)
	{ 
		$info_id =$obj->info_id;
		$sql_get_section = "SELECT * FROM tbl_campaign_info WHERE cmp_info_id = ".$info_id."";
		$result_section_data = mysqli_query($db_con,$sql_get_section) or die(mysqli_error($db_con));
		$row_data = mysqli_fetch_array($result_section_data);
		$existing_product =$row_data['cmp_info_products'];
	}
	
	//----------------------end check existing Product------//
	$response_array = array();	
	$start_offset   = 0;
	$cat_id     	= $obj->cat_id;
	$prod_cat_id	= $cat_id;	
    $brand_id     	= $obj->brand_id;
	$org_id     	= $obj->org_id;
	$cat_txt     	= $obj->cat_txt;
	$subcat_txt     = $obj->subcat_txt;
	$brand_txt     	= $obj->brand_txt;
	$org_txt     	= $obj->org_txt;
    $search_text	= $obj->search_text;
	$section_id     = $obj->section_id;
	
	
	
   		$breadcrumbs='';
	    $sql_load_data   = " SELECT DISTINCT tpm.prod_id ,  tpm.*,tbm.brand_name, tom.org_name, tpi.* ";
		$sql_load_data  .= " FROM tbl_products_master AS tpm INNER JOIN tbl_oraganisation_master AS tom ";
		$sql_load_data  .= " 	ON tpm.prod_orgid = tom.org_id INNER JOIN tbl_products_images AS tpi ";
		$sql_load_data  .= " 	ON tpm.prod_id = tpi.prod_img_prodid INNER JOIN tbl_brands_master AS tbm ";
		$sql_load_data  .= "    ON tpm.prod_brandid = tbm.brand_id ";
		$sql_load_data  .= " WHERE 1=1 ";
		$sql_load_data  .= " 	AND tpi.prod_img_status = '1' ";
        $sql_load_data  .= " 	AND tpi.prod_img_type = 'main' ";
		
		if($obj->info_id != -1 && $existing_product !="")
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
			//$filter_data .= '<th style="text-align:center;width:10%">Select Product<br></th>';
			$filter_data .= '<th style="text-align:center;width:10%">Select Product<br><input type="checkbox" id="'.$section_id.'checkall" onclick="checkAll('.$section_id.',\'checkall\',\'sel_prod\');" > check all</th>';
       		  //	$filter_data .= '<th style="text-align:center">Sr No.</th>';
			$filter_data .= '<th style="text-align:center">Product Name</th>';
			$filter_data .= '<th style="text-align:center">Image</th>';
			$filter_data .= '<th style="text-align:center">Organisation</th>';
			$filter_data .= '<th style="text-align:center">Brand</th>';
			$filter_data .= '</tr>';
      		$filter_data .= '</thead>';
		    $filter_data .= '<tbody id="show_more'.$section_id.'">';
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
				$filter_data .= '<td style="text-align:center;width:10%"><input type="checkbox" class="sel_prod'.$section_id.'" id="sel_prod'.$section_id.'" name="sel_prod'.$section_id.'" value="'.$row_load_data['prod_id'].'" > </td>';				
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
			if($info_id =="")
			{
				$info_id =-1;
			}
			
			$filter_data .='<input id="show_more_btn'.$section_id.'" onClick="getProducts('.$section_id.','.$info_id.',1)" ';
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
} 

//----------------------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------- Filter  End here -------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------//

//----------------------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------- insertcampaign function start here--------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------//

function insertcampaign($campaign_name,$no_of_section,$campaign_status,$uid,$response_array)
{       global $uid;
	    global $db_con;
		
		$sql_insert_campaign = " INSERT INTO `tbl_campaign`(`cam_name`,`no_of_section`, `status`, `created_by`, `created`)";
		$sql_insert_campaign .= "  VALUES ('".$campaign_name."','".$no_of_section."','".$campaign_status."','".$uid."',now()) ";
		$res_insert_campaign 	= mysqli_query($db_con, $sql_insert_campaign) or die(mysqli_error($db_con));
		
		$last_id = $db_con->insert_id;
	
		if($res_insert_campaign)
		{   
			$slug                   =str_replace(' ','-',$campaign_name);
			$slug                   = strtolower($slug.'-'.$last_id.'-mr');
			$sql_update_data 		= " UPDATE tbl_campaign SET campion_slug= '".$slug."'   WHERE campaign_id =".$last_id." ";
			$result_update_data 	= mysqli_query($db_con,$sql_update_data) or die(mysqli_error($db_con));
			return $last_id;
		}
		else 
		{
			return 0;
		}
		
}

//----------------------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------- insertcampaign function End here--------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------//




//----------------------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------- INSERT REQUEST Start-----------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------//

if((isset($_POST['insert_req'])) == "1" && isset($_POST['insert_req']))
{   
	$campaign_name			= mysqli_real_escape_string($db_con,$_POST['campaign_name']);
	$no_of_section			= $_POST['no_of_section'];
	$campaign_status		= $_POST['campaign_status'];
	$response_array 		= array();	
	
	//---------------- for image validation start here---------------------//
	
	for($i=0;$i < count($_FILES['file']['name']); $i++)
	{
			$imagedata = getimagesize($_FILES['file']['tmp_name'][$i]);
			if($imagedata[0] >1920 || $imagedata[0] <1920 )
			{
				$response_array = array("Success"=>"fail","image_fail"=>1,"campaign_id"=>$campaign_id,"resp"=>" Image width is not equal to 1920");	
				echo json_encode($response_array);exit();
			}
	}
	
	//---------------- for image validation end here---------------------//
	
	if($campaign_name != "" && $no_of_section != "")
	{   
		$campaign_id 	= insertcampaign($campaign_name,$no_of_section,$campaign_status,$uid,$response_array);
		
		
   //-------------------------- start image upload-----------------------------//
		if($campaign_id != 0)
		{    $dir ="../images/banners_img";
			 if(is_dir($dir) === false)
			{
				mkdir($dir);
		 	}
		 	 $target_path="../images/banners_img/campaign_id";
			 $order = 1;
		    for($i=0;$i < count($_FILES['file']['name']); $i++)
			{
				$sec_dir 	= $target_path.$campaign_id;
				if(is_dir($sec_dir) === false)
				{
					mkdir($sec_dir);
				}
			$imagedata = getimagesize($_FILES['file']['tmp_name'][$i]);
			$file=$sec_dir.'/'.$_FILES['file']['name'][$i];
			$img_upload = move_uploaded_file($_FILES['file']['tmp_name'][$i], $file);
				if($img_upload)
				{
					$sql_insert_image	= " INSERT INTO `tbl_campaign_imgs`(`cmp_img_campid`, `cmp_img_name` ,cmp_img_url,cmp_img_order) ";
					$sql_insert_image	.= " VALUES ('".$campaign_id."', '".$_FILES['file']['name'][$i]."','".$_POST['img_slug'.$i]."','".$order."') ";
					$res_insert_image 	= mysqli_query($db_con, $sql_insert_image) or die(mysqli_error($db_con));
				} 
				$order++;
	     }
		 
		//------------------------------------- for check rights for update button start here-----------------------------//
	    $dis = checkFunctionalityRight("view_marketing.php",1);
		$rights = 1;
		if(!$dis)
		{
		$rights = 0;
		}
		//------------------------------------- for check rights for update button end here-----------------------------//
		
	    $response_array = array("Success"=>"Success","rights"=>$rights,"resp"=>$data,"counter"=>$counter,"campaign_id"=>$campaign_id);
	
		}
	    else
	    {
			$response_array = array("Success"=>"fail","resp"=>"Empty Data.");
		}
		
	//-------------------------- enc image upload-----------------------------//
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>$campaign_name);	
	}
	echo json_encode($response_array);		
}

//----------------------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------- INSERT REQUEST End-----------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------//

//----------------------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------- Start Load Correct Campaign Data-----------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------//

if((isset($obj->load_camp)) == "1" && isset($obj->load_camp))
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
		
		$sql_load_data  = " SELECT tcm.* , tcu.fullname ,(SELECT fullname FROM tbl_cadmin_users WHERE id = tcm.modified_by ) AS name_mod ";					
		$sql_load_data  .= " FROM `tbl_campaign` as tcm INNER JOIN tbl_cadmin_users AS tcu ";
		$sql_load_data  .= " 	ON tcm.created_by = tcu.id   WHERE 1=1 and sec_status = 1 ";
		
		if($utype !=1)
		{
		$sql_load_data  .= " and tcm.created_by ='".$uid."' ";	
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (cam_name like '%".$search_text."%'or tcm.created like '%".$search_text."%' or tcm.modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY campaign_id DESC LIMIT $start, $per_page ";
		//$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{	
		    $result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));		
			
			$campaign_data  = "";	
			$campaign_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$campaign_data .= '<thead>';
    	  	$campaign_data .= '<tr>';
         	$campaign_data .= '<th style="text-align:center">Sr No.</th>';
			$campaign_data .= '<th style="text-align:center">Campaign  Name</th>';
			$campaign_data .= '<th style="width:6%;text-align:center">Number of Section</th>';
			$campaign_data .= '<th style="text-align:center">Created</th>';
			$campaign_data .= '<th style="text-align:center">Created By</th>';
			$campaign_data .= '<th style="text-align:center">Modified</th>';
			$campaign_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_marketing.php",1);
			if($dis)
			{			
			$campaign_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_marketing.php",1);
			if($edit){
			$campaign_data .= '<th style="text-align:center">Action</th>';
			}
			$del = checkFunctionalityRight("view_marketing.php",2);
			if($del)
			{			
			$campaign_data .= '<th style="text-align:center">';
			$campaign_data .= '<div style="text-align:center">';
			$campaign_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/>';
			$campaign_data .= '</div></th>';
			}
			$campaign_data .= '</tr>';
      		$campaign_data .= '</thead>';
      		$campaign_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{   
	    	  	$campaign_data .= '<tr>';				
				$campaign_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$campaign_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['cam_name']).'"';
				$campaign_data .=' class="btn-link" id="'.$row_load_data['campaign_id'].'" onclick="addMoreCamp(this.id,\'view\',1);"></td>';
				$campaign_data .= '<td style="text-align:center">'.$row_load_data['no_of_section'].'</td>';
				$campaign_data .= '<td style="text-align:center">'.$row_load_data['created'].'</td>';
				$campaign_data .= '<td style="text-align:center">'.ucwords($row_load_data['fullname']).'</td>';
				$campaign_data .= '<td style="text-align:center">'.$row_load_data['modified'].'</td>';
				$campaign_data .= '<td style="text-align:center">'.ucwords($row_load_data['name_mod']).'</td>';
				$dis = checkFunctionalityRight("view_marketing.php",1);
				if($dis)
				{				
				$campaign_data .= '<td style="text-align:center">';	
				if($row_load_data['status'] == 1)
				{
				$campaign_data .= '<input type="button" value="Active" id="'.$row_load_data['campaign_id'].'"';
				$campaign_data .= ' class="btn-success" onclick="changeStatus(this.id,0);">';
				}
				else
				{
				$campaign_data .= '<input type="button" value="Inactive" id="'.$row_load_data['campaign_id'].'"';
				$campaign_data .=' class="btn-danger" onclick="changeStatus(this.id,1);">';
				}
				$campaign_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_marketing.php",1);
				
				if($edit)
				{				
					$campaign_data .= '<td style="text-align:center">';
					$campaign_data .= '<input type="button" value="Edit" id="'.$row_load_data['campaign_id'].'"';
					$campaign_data .=' class="btn-warning" onclick="addMoreCamp(this.id,\'edit\',1);"></td>';				
				}
				$del = checkFunctionalityRight("view_marketing.php",2);
				
				if($del)
				{					
					$campaign_data .= '<td><div class="controls" align="center">';
					$campaign_data .= '<input type="checkbox" value="'.$row_load_data['campaign_id'].'" id="batch'.$row_load_data['campaign_id'].'"';
					$campaign_data .='  name="batch'.$row_load_data['campaign_id'].'" class="css-checkbox camp">';
					$campaign_data .= '<label for="batch'.$row_load_data['campaign_id'].'" class="css-label"></label>';
					$campaign_data .= '</div></td>';										
				}
	          	$campaign_data .= '</tr>';	
				
			}	
      		$campaign_data .= '</tbody>';
      		$campaign_data .= '</table>';	
			$campaign_data .= '<script>';	
			//$campaign_data .= '$(".select2-me").prop("disabled","true");';	
			$campaign_data .= '</script>';	
			
			$campaign_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$campaign_data);					
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

//----------------------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------- Start Load Correct Campaign Data-----------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------//

if((isset($obj->load_add_camp_part)) == "1" && isset($obj->load_add_camp_part))
{
	$campaign_id 	= $obj->campaign_id;
	$req_type 	= $obj->req_type;
	$response_array = array();
	
	
	//-------------------- Get campaign Data Start here------------------------//
	if(( $req_type == "edit") || ($campaign_id != "" && $req_type == "view"))
	{   $sql_camp_data 		= "Select * from tbl_campaign where campaign_id = '".$campaign_id."' ";
	

		$result_camp_data 	= mysqli_query($db_con,$sql_camp_data) or die(mysqli_error($db_con));
		$row_camp_data		= mysqli_fetch_array($result_camp_data);		
	}
	//-------------------- Get campaign Data end here------------------------//
	
	
	
	///////////////////////////////////////////////////////////////////////
    //--------------------ADD REQUEST START HERE------------------------//
	
	if($req_type != "" && $req_type =="add")
	{
		
		
		$data = ''; 	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Campaign Name <sup class="validfield">';
		$data .='<span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="campaign_name" name="campaign_name" class="input-large keyup-char" ';
		$data .= 'onkeypress=" return charsonly(event)" data-rule-required="true" ';	 	
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- Campaign Name -->';  
		$data .='<div class="control-group">';
        $data .='<label class="control-label">Site Banners<span style="color:#F00;font-size:20px;">*</span></label>';
        $data .='<input type="hidden" value="1" name="site_banner_hid" id="site_banner_hid">';                                           
        $data .='<div id="siteBanners">';                                                                                                                                     
	    $data .='<div class="controls">';
        $data .=' <ul class="css-ul-list">';
        $data .='<li>Min 1 and Max 3 Banners.</li>';
        $data .='<li>Only "jpg" , "png" or "jpeg" image will be accepted.</li>';
        $data .='<li>Press \'ctrl\' to select multiple images.</li>';   
		$data .='<li>Image size should be \'1920\' pixel.</li>';                                                      
        $data .='</ul>';
	    $data .='<input type="file" data-overwrite-initial="false"  name="file[]" multiple data-min-file-count="3" ';
		$data .=' id="file" onchange="readURL(this.value)"' ;
		$data .= 'class="input-xlarge" data-rule-required="true" accept="image/jpg,image/png,image/jpeg" ';
		$data .=' data-type="image" >';
		$data .='<br><div style="display:none" id="img1">&nbsp;</div>';
		$data .='<div style="display:none" id="img2">&nbsp;<input placeholder="Enter image 2 slug" type="text" id="img_slug1" name="img_slug1" class="input-large keyup-char"> </div>' ;
		$data .='<div style="display:none" id="img3">&nbsp;<input placeholder="Enter image 3 slug" type="text" id="img_slug2" name="img_slug2" class="input-large keyup-char"> </div>';
		$data .='<div id="banner_url"></div>';   
		                                                                                                     
        $data .='</div>';                                                
        $data .=' </div>';
        $data .='</div>';   
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">No of Section <sup class="validfield"> ';
		$data .='<span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" min="1" id="no_of_section" onkeypress="return numsonly(event)" ';
		$data .= ' name="no_of_section" class="input-large keyup-char" data-rule-required="true" '; 	
		$data .= '/>';
		$data .= '</div>';
		$data .= '</div> <!-- No. Of Section  Name -->';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status <sup class="validfield">';
		$data .='<span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="radio" id="campaign_status" name="campaign_status" class="" value="1" data-rule-required="true" ';
		$data .= '/> Active';
		$data .= '<input type="radio" id="campaign_status" name="campaign_status" class="" value="0" data-rule-required="true" ';
		$data .= '/> Inactive';
		$data .= '</div>';
		$data .= '</div> <!-- Capmaign status-->';
		$data .='<div id="sections"<br></div>';
		$data .= '<div class="form-actions">';
		$data .= '<button type="submit" id="reg_submit_add" name="reg_submit_add" class="btn-success">Create campaign</button>';					
		$data .= '</div> <!-- Save and cancel -->';			
		$data .= '<script type="text/javascript">';
		//$data .= '$("#branch_orgid").select2();';
	//	$data .= '$("#branch_state").select2();';
	//	$data .= '$("#branch_city").select2();';
	//	$data .= 'CKEDITOR.replace("branch_detail_add",{height:"150", width:"100%"});';
	//	$data .= 'CKEDITOR.replace("branch_meta_description",{height:"150", width:"100%"});';		
		if($branch_id != "" && $req_type == "view")
		{
			$data .= '$("#branch_detail_add").prop("disabled","true");';
			$data .= '$("#branch_meta_description").prop("disabled","true");';			
		}	
		$data .= '</script>';
		$response_array = array("Success"=>"Success","resp"=>$data);
		echo json_encode($response_array);exit();
	} 
	
	//-------------------- ADD REQUEST END HERE------------------------//
	////////////////////////////////////////////////////////////////////
	
	
	//////////////////////////////////////////////////////////////////////////
	//-------------------- VIEW REQUSET START HERE--------------------------//
	if($req_type != "" && $req_type =="view")
	{
        $data = ''; 	
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">campaign Name</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" value="'.$row_camp_data['cam_name'].'" onkeypress=" return charsonly(event)" ';
		$data .= ' class="input-large keyup-char" data-rule-required="true" disabled />';
		$data .= '&nbsp;<input type="text" value="'.$row_camp_data['campion_slug'].'" onkeypress=" return charsonly(event)" ';
		$data .= ' class="input-large keyup-char" data-rule-required="true"  />';
		$data .= '</div>';
		$data .= '</div> <!-- Campaign Name -->';  
		$data .='<div class="control-group">';
        $data .='<label class="control-label">Site Banners</label>';
        $data .='<input type="hidden" value="1" name="site_banner_hid" id="site_banner_hid">';
		                                           
        $data .='<div id="siteBanners">';        $data .='<div class="controls">';
	    $sql_get_banner ="SELECT * FROM tbl_campaign_imgs WHERE cmp_img_campid =".$row_camp_data['campaign_id']." ORDER BY cmp_img_order ASC"; 
		$result_get_banner 	= mysqli_query($db_con,$sql_get_banner) or die(mysqli_error($db_con));
		while($row_img_data		= mysqli_fetch_array($result_get_banner))
		{
		$data .='<img src="../images/banners_img/campaign_id'.$row_camp_data['campaign_id'].'/'.$row_img_data['cmp_img_name'].'" ';
		$data .=' style="width:100px;height:100px"><br>';
		$data .='<input style="margin-top:10px" type="text" name="img_slug'.$banner_count.'"  value="'.$row_img_data['cmp_img_url'].'">&nbsp;&nbsp;';
		$data .='<input style="margin-top:10px" type="text" name="img_order'.$banner_count.'" placeholder="" value="'.$row_img_data['cmp_img_order'].'"><br><br>';
		}                                                                                                     
        $data .='</div>';                                                
        $data .=' </div>';
        $data .='</div>';  
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status</label>';
		$data .= '<div class="controls">';
		if($row_camp_data['status'] == 1)
		{
			$data .='<span style="color:green">Active</span>';
		} 
		else
		{
			$data .='<span style="color:red">Inactive</span>';
		}
		$count =1;
		$data .= '</div>';
		$data .= '</div> <!-- Capmaign status-->'; 
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">No of Section</label>';
		$data .= '<div class="controls">';
		$data .= $row_camp_data['no_of_section']; 	
		$data .= '</div>';
		$data .= '</div> <!-- End No. Of Section  Name -->';
		
		//-------------------- Start get Section wise Product---------------------------------------//
		
		$sql_get_section = "SELECT * FROM tbl_campaign_info WHERE cmp_info_campid = ".$row_camp_data['campaign_id']." ";
		$sql_get_section .=" LIMIT ".$row_camp_data['no_of_section']."";
		$result_section_data = mysqli_query($db_con,$sql_get_section) or die(mysqli_error($db_con));
		while($row_sec_data		= mysqli_fetch_array($result_section_data))
		{
		$product_count=0;
		$data .= '<div class="control-group">';
		$data .= '<label style="text-align:center" for="tasktitel" class="control-label"><h4>Section '.$count++.'</h4></label><br>';
		
		$data .= '<div class="controls">';
		$data .='<input style="" class="input-large keyup-char" type="text" onkeypress=" return charsonly(event)" ';
		    $data .=' readonly="readonly" value="'.$row_sec_data['cmp_info_section_name'].'"';
			$data .=' >';
			
			$data .='&nbsp;&nbsp;<input style="" class="input-large keyup-char" type="text"';
		    $data .=' readonly="readonly" value="'.$row_sec_data['section_slug'].'"7';
			$data .='>';
		$data .= '<table id="tbl_user" class="table table-bordered" style="margin-top:10px" >';
        $data .= '<thead>';
    	$data .= '<tr>';
        $data .= '<th style="text-align:center;width:10%">Sr No.</th>';
		$data .= '<th style="text-align:center;width:50%">Product Name</th>';
		$data .= '<th style="text-align:center;width:20%">Image</th>';
		$data .= '<th style="text-align:center;width:20%">Organization</th>';
		$data .= '</tr>';
      	$data .= '</thead>';
      	$data .= '<tbody>';
		
		$products = explode(',',$row_sec_data['cmp_info_products']);
		
		for($i=0 ;$i<sizeof($products);$i++){
		$j=$i+1;
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
			$data .= '<td style="text-align:center">'.$product_count.'</td>';				
			$data .= '<td style="text-align:center">'.$row_prod_data['prod_name'].'</td>';
			
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
				$data .= '<td ><img style="width:50px;height:50px" src="'.$imagepath.'?'.rand().'"/></td>';
			}
			else
			{
				$data .= '<td ><img style="width:50px;height:50px" src="../images/no-image.jpg"/></td>';
			}
			$data .= '<td style="text-align:center">'.$row_prod_data['org_name'].'</td>';
			$data .= '</tr>';	
		
		
		}
		$data .= '</tbody>';
		$data .='</table>';
		$data .='</div>';
		$data .='</div>';
		}
		
		//-------------------- End get Section wise Product---------------------------------------//
				
		$data .= '<script type="text/javascript">';
		$data .= '$("#branch_orgid").select2();';
		$data .= '$("#branch_state").select2();';
		$data .= '$("#branch_city").select2();';
		$data .= 'CKEDITOR.replace("branch_detail_add",{height:"150", width:"100%"});';
		$data .= 'CKEDITOR.replace("branch_meta_description",{height:"150", width:"100%"});';		
		if($branch_id != "" && $req_type == "view")
		{
			$data .= '$("#branch_detail_add").prop("disabled","true");';
			$data .= '$("#branch_meta_description").prop("disabled","true");';			
		}	
		$data .= '</script>';
		$response_array = array("Success"=>"Success","resp"=>$data);
		echo json_encode($response_array);exit();
	} 
	
	//-------------------- VIEW RWQUEST END HERE------------------------//
	//////////////////////////////////////////////////////////////////////
	
	
	
	//////////////////////////////////////////////////////////////////////////
	//-------------------- EDIT REQUSET START HERE--------------------------//
	
	if($req_type != "" && $req_type =="edit")
	 {  $section_no =$obj->section_no;
        $data = ''; 
		
		$data .='<input type="hidden" value="'.$row_camp_data['campaign_id'].'" name="campaign_id" id="campaign_id">';
		$data .='<input type="hidden" value="edit" name="req_type" id="req_type">';
		$data .='<input type="hidden" value="'.$section_no.'" name="section_num" id="section_num">';
		
		//-------------------------------------------------Update Campiangn Start Here-------------------------------------//
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Campaign Name</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="campaign_name" onkeypress=" return charsonly(event)" name="campaign_name" ';
		$data .=' value="'.$row_camp_data['cam_name'].'"';
		$data .=' class="input-large keyup-char" data-rule-required="true"  />';
		$data .= '&nbsp;<input type="text" value="'.$row_camp_data['campion_slug'].'"';
		$data .= ' class="input-large keyup-char" data-rule-required="true"  />';
		$data .= '</div>';
		$data .= '</div> <!-- Campaign Name -->';
		  
		$data .='<div class="control-group">';
        $data .='<label class="control-label">Campaign Banners</label>';
        $data .='<input type="hidden" value="1" name="site_banner_hid" id="site_banner_hid">';                                           
        $data .='<div id="siteBanners">';
		$data .='<div class="controls">';
	    $sql_get_banner ="SELECT * FROM tbl_campaign_imgs WHERE cmp_img_campid =".$row_camp_data['campaign_id']." ORDER BY cmp_img_order ASC"; 
		$result_get_banner 	= mysqli_query($db_con,$sql_get_banner) or die(mysqli_error($db_con));
		$banner_count =0;
		while($row_img_data		= mysqli_fetch_array($result_get_banner))
		{
		
		$data .='<img src="../images/banners_img/campaign_id'.$row_camp_data['campaign_id'].'/'.$row_img_data['cmp_img_name'].'" ';
		$data .=' style="height:100px;width:200px">';
		$data .='<a href="#" title="Click here to delete image" >';
		$data .='<img style="width:25px;height:25px" ';
		$data .=' src="images/delete.png" onclick="delete_image('.$row_img_data['cmp_img_id'].','.$row_camp_data['campaign_id'].','.$section_no.');"></a><br>';
	   	$data .='<input style="margin-top:10px" type="text" name="img_slug'.$banner_count.'" onchange="update_img_slug(this.value,'.$row_img_data['cmp_img_id'].');" placeholder="Enter Slug" value="'.$row_img_data['cmp_img_url'].'">&nbsp;&nbsp;';
		$data .='<input style="margin-top:10px" type="text" name="img_order'.$banner_count.'" onchange="update_img_order(this.value,'.$row_img_data['cmp_img_id'].','.$row_camp_data['campaign_id'].');" placeholder="Enter Order" value="'.$row_img_data['cmp_img_order'].'"><br><br>';
		$banner_count++;
	    }
		$file_limit =$banner_count;  
		if($banner_count ==0)
		{
		$data .='<input type="hidden" name="banner_check" id="banner_check" value="'.$banner_count.'" >';
		$data.='<input value="" id="new_file" name="file[]" multiple="multiple" ';
		$data .=' class="css-checkbox camp" type="file"  data-rule-required="true"> ';
		}
		 else
	    {
		$data .='<input type="hidden" name="banner_check" id="banner_check" value="'.$file_limit.'" >';
	    $data.='<input value=""  id="new_file" name="file[]" ';
		$data .=' multiple="multiple" class="css-checkbox camp" type="file"> ';
			
		}
		                                                                                                 
        $data .='</div>';                                                
        $data .=' </div>';
        $data .='</div>';  
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="radio" id="campaign_status" name="campaign_status" class="" value="1" data-rule-required="true" ';
		if($row_camp_data['status'] == 1){
			$data .='checked ';
		} 
		$data .= '/> Active';
		$data .= '<input type="radio" id="campaign_status" name="campaign_status" class="" value="0" data-rule-required="true" ';
		if($row_camp_data['status'] == 0){
			$data .='checked ';
		}
		$data .= '/> Inactive';
		$data .= '</div>';
		$data .= '</div> <!-- Capmaign status-->'; 
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">No of Section</label>';
		$data .= '<div class="controls">';
		
		
		$data .= '<input type="text" id="no_of_section_old" onkeypress=" return numsonly(event)" name="no_of_section" class="input-large keyup-char"';
		$sql_check_section ="SELECT * FROM tbl_campaign_info WHERE cmp_info_campid = '".$row_camp_data['campaign_id']."'";
	    $result_check_section = mysqli_query($db_con,$sql_check_section) or die(mysqli_error($db_con));
	    $section_num_rows     =mysqli_num_rows($result_check_section);
		$data .=' data-rule-required="true"  value="'.$row_camp_data['no_of_section'].'">';
		$data .='<input type="hidden" id="nos_limit" value="'.$section_num_rows.'">';
		
		$dis = checkFunctionalityRight("view_marketing.php",1);
		$data .= '</div>';
		
		if($dis){
		$data .= '<div class="control-group" style="border-bottom:2px solid #18BB7C">';	
		$data .= '<div class="controls">';
		$data .= '<button type="submit" id="reg_submit_add" name="reg_submit_add" class="btn-success">Update campaign</button>';					
		$data .= '</div></div> <!-- Save and cancel -->';	
		}
		$data .= '</div> ';
		
	//-------------------------------------------------Update Campiangn End Here-------------------------------------//
	
	
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//----------------------------Existing Section Part Start Here-------------------------------------------------------------//	
	
	
		$sql_get_section = "SELECT * FROM tbl_campaign_info WHERE cmp_info_campid = ".$row_camp_data['campaign_id']." ";
		$sql_get_section .= " LIMIT ".$row_camp_data['no_of_section']."";
		$result_section_data = mysqli_query($db_con,$sql_get_section) or die(mysqli_error($db_con));
		$count =1;
		while($row_sec_data		= mysqli_fetch_array($result_section_data))
		{
		//----- get curent secction number and info id-------------------------//	
		if($count ==$section_no)
		{
		$data .='<input type="hidden" value="'.$count.'" id="active_section">';	
		$data .='<input type="hidden" value="'.$row_sec_data['cmp_info_id'].'" id="active_info_id">';	
		}
		//----- get curent secction number and info id-------------------------//	
		
		$data .= '<div class=" control-group" ';
		if($count ==$section_no)
		{
		$data .=' style="display:block"';	
		}
		else 
		{
			$data .=' style="display:none"';
		}
		
		$data .=' id="section_no_'.$count.'">';
		$data .= '<label for="tasktitel" class="control-label" style="text-align:center"><h5 style="font-size: 18px"> Section '.$count.'</h5><h4>';
		
		//------------------- Start previous button---------------------------//
		if($count >1)
		{
		$data .= '<input style="width:140px"    class=" btn-pn" id="nxt_sec'.$count.'" onclick="nxt_sec('.$count.',0,'.$row_camp_data['no_of_section'].','.$row_camp_data['campaign_id'].');"';
		$data .=' type="button" value="<< Pre Section " >';	
		}
		//------------------- End previous button-----------------------------//
		
		//------------------- Start Next button-------------------------------//
		if($count <$row_camp_data['no_of_section'])
		{
		$data .= '<input style="width:140px;margin-top:10px"    class=" btn-pn" id="nxt_sec'.$count.'" onclick="nxt_sec('.$count.',1,'.$row_camp_data['no_of_section'].','.$row_camp_data['campaign_id'].');"';
		$data .=' type="button" value="Next Section  >>" >';	
		}
		//------------------- End Next button-------------------------------//
		
		//------------------- Start Update button-------------------------------//
		$data .='<input style="width:140px;margin-top:10px"  class=" btn-success" id="save_sec_btn'.$count.'" ';
		$data .='onclick="save_section('.$count.',2,'.$row_sec_data['cmp_info_id'].',0);"';
		$data .=' type="button" value="Update Section" >';
		//------------------- End Update button-------------------------------//
		
		
		//------------------- Start Delete button-------------------------------//
		$data .='<input style="width:140px;margin-top:10px"  value="Delete Section" class="btn-danger" type="button" ';
		$data .=' onclick="delete_section('.$row_sec_data['cmp_info_id'].','.$row_camp_data['no_of_section'].','.$row_camp_data['campaign_id'].','.$count.');" >';
		//------------------- End Delete button-------------------------------//
		
		if($row_camp_data['no_of_section']==$section_num_rows && $row_camp_data['submit_status']==1)
		{
		$data .='<input style="width:140px;margin-top:10px"  type="button" class=" btn-warning nxt-pre-btn" id="save_sec_btn'.$count.'" ';
		$data .=' onclick="save_section('.$count.',2,'.$row_sec_data['cmp_info_id'].',1);" style="display:none;width:"140px" value="Submit Section" />';	
		}
		if($row_camp_data['no_of_section']==$section_num_rows && $count == $section_num_rows && $row_camp_data['submit_status']==0)
		{
		$data .='<input style="width:140px;margin-top:10px"  type="button" class=" btn-warning" id="save_sec_btn'.$count.'" ';
		$data .=' onclick="save_section('.$count.',2,'.$row_sec_data['cmp_info_id'].',1);" style="display:none;width:"140px" value="Submit Section" />';	
		}
		
		$data .='</h4></label>';
		
		
			$data .= '<div class="controls">';
		    $products = explode(',',$row_sec_data['cmp_info_products']);
			$products = array_unique($products);
			$product_count = 0;
		    $data .='<input style="" class="input-large keyup-char" type="text" onkeypress=" return charsonly(event)" ';
		    $data .='placeholder="Enter Category Name" value="'.$row_sec_data['cmp_info_section_name'].'" name="section'.$count.'"';
			$data .=' id="section'.$count.'">';
			
			$data .='&nbsp;&nbsp;<input style="" class="input-large keyup-char" type="text"';
		    $data .='placeholder="Enter Section Slug" value="'.$row_sec_data['section_slug'].'" name="section_slug'.$count.'" ';
			$data .='id="section_slug'.$count.'">';
			
			
			$data .='&nbsp;&nbsp;<input style="" class="input-large keyup-char" type="text"';
		    $data .='placeholder="Sort Order" onChange="update_sec_order(this.value,'.$row_sec_data['cmp_info_id'].');" value="'.$row_sec_data['sec_sort_order'].'" name="section_sort_order'.$count.'" ';
			$data .='id="section_sort_order'.$count.'">';
			
			//---------------Start show campaign product Table------------------------------------//
			if(sizeof($products)>=1){
			//$data .=sizeof($products);
			if($products[0]!=""){
			
			$data .= '<div class="" style="max-height: 300px;padding:10px 10px 10px;border-bottom: 1px solid #dadada;" >';
			$data .='<table id="tbl_user" class="table scroll table-bordered" >';
			$data .= '<thead>';
    	  	$data .= '<tr>';
			$data .= '<th style="text-align:center;width:12%">';
			$data .='<input  onclick="remove_prod('.$row_sec_data['cmp_info_id'].','.$count.','.$row_camp_data['campaign_id'].');" ';
			$data .=' class="btn-danger" type="button" value="Remove Product"></th>';
         //	$data .= '<th style="text-align:center ;width:5%">Sr No.</th>';
		    $data .= '<th style="text-align:center ;width:40%">Product Name</th>';
			$data .= '<th style="text-align:center">Image</th>';
			$data .= '<th style="text-align:center">Organisation</th>';
			$data .= '<th style="text-align:center">Brand</th>';
			$data .= '</tr>';
      		$data .= '</thead>';
    	 	$data .= '<tbody>';
			
		for($i=0 ;$i<sizeof($products);$i++)
		{
		$j=$i+1;
		if($products[$i]!=""){
		$sql_get_product=" ";
		$sql_get_product  .= " SELECT tpm.*, tom.org_name, tpi.prod_img_file_name ,tbm.brand_name";
		$sql_get_product  .= " FROM tbl_products_master AS tpm INNER JOIN tbl_oraganisation_master AS tom ";
		$sql_get_product  .= " 	ON tpm.prod_orgid = tom.org_id INNER JOIN tbl_products_images AS tpi ";
		$sql_get_product  .= " 	ON tpm.prod_id = tpi.prod_img_prodid INNER JOIN tbl_brands_master AS tbm ON tpm.prod_brandid = tbm.brand_id";
		$sql_get_product  .= " WHERE tpm.prod_id IN ('".$products[$i]."')";
		$result_prod_data = mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
		
		    $row_prod_data		= mysqli_fetch_array($result_prod_data);
		    $product_count++;
		    $data .= '<tr>';				
				
			$data .= '<td style="text-align:center;width:12%"><input  type="checkbox" class="del_prod'.$count.'" id="sel_prod'.$count.'" name="sel_prod'.$count.'" value="'.$row_prod_data['prod_id'].'" > </td>';				
		//	$data .= '<td style="text-align:center ; width:4%">'.$product_count.'</td>';
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
            $data .= '<td style="text-align:center;width:15%">';
			$data .='<img style="text-align:center;width:50px;height:50px" src="'.$imagepath.'?'.rand().'"/></td>';
	        }
			else
			{
			 $data .= '<td style="text-align:center;width:15%">';
			$data .='<img style="text-align:center;width:50px;height:50px" src="../images/no-image.jpg"/></td>';
			}
			
			$data .= '<td style="text-align:center">'.$row_prod_data['org_name'].'</td>';
			$data .= '<td style="text-align:center">'.$row_prod_data['brand_name'].'</td>';
			$data .= '</tr>';	
	
		}// if end
		}// for end
		  $data .= '</tbody>';
		  $data .= '</table>';
		  $data .='<br><br></div>';
		 }// if products end
		 }
		   //---------------End show campaign product Table------------------------------------//
		 
	    $data .='<input type="hidden" value="'.$product_count.'" id="current_prod_'.$count.'">';
		
		
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		//---------------------------filter for Existing Secton strat here--------------------------------------------------------//
		$data .='<div style="padding: 10px;" id="'.$count.'">';
		$data .='<select  class ="select2-me input-large marginr10" name="prod_cat_id_list'.$count.'" id="prod_cat_id_list'.$count.'"';
		$data .='   class = "select2-me input-medium">';
		$data .= '<option value="">Select Category</option>';
        $sql_get_cat_id_list = " SELECT `cat_id`,`cat_name` FROM `tbl_category` ";
		$sql_get_cat_id_list .= " WHERE `cat_id` IN (SELECT distinct(prod_catid) FROM `tbl_products_master`) ";
		$sql_get_cat_id_list .= " 	and `cat_type` = 'parent' ";
		$result_get_cat_id_list = mysqli_query($db_con,$sql_get_cat_id_list) or die(mysqli_error($db_con));
		// Query for checking Category is not empty
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
					$data .= '<option id="cat'.$row_chk_isParent['cat_id'].'sec'.$count.'" value="'.$row_chk_isParent['cat_id'].'">'.ucwords($row_chk_isParent['cat_name']).'</option>';
					$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'add',$count);
				}
				
				
			}										
		$data .='</select>';
       
		// for organization
		$data .='<select name="prod_org_id_list'.$count.'" id="prod_org_id_list'.$count.'" onChange="loadProducts();"  ';
		$data .=' class = "select2-me input-large marginr10">';
        $sql_get_org_id_list = "SELECT `org_id`,`org_name` FROM `tbl_oraganisation_master` WHERE `org_id` IN(SELECT distinct(prod_orgid) ";
		$sql_get_org_id_list .=" FROM `tbl_products_master`); ";
		$result_get_org_id_list = mysqli_query($db_con,$sql_get_org_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select Organisation</option>';
        while($row_get_org_id_list = mysqli_fetch_array($result_get_org_id_list))
		{
		$data .='<option id="org'.$row_get_org_id_list['org_id'].'sec'.$count.'" value="'.$row_get_org_id_list['org_id'].'">'.ucwords($row_get_org_id_list['org_name']).'</option>';
        }
		$data .='</select>';
        //organization end
		// for brand
		$data .='<select name="prod_brand_id_list'.$count.'" id="prod_brand_id_list'.$count.'"   class = "select2-me input-large marginr10">';
        $sql_get_brand_id_list = "SELECT `brand_id`,`brand_name` FROM `tbl_brands_master` WHERE `brand_id` ";
		$sql_get_brand_id_listIN .="  (SELECT distinct(prod_brandid) FROM `tbl_products_master`) ";
		$result_get_brand_id_list = mysqli_query($db_con,$sql_get_brand_id_list) or die(mysqli_error($db_con));
		$data .='<option value="">Select Brand</option>';
		while($row_get_brand_id_list = mysqli_fetch_array($result_get_brand_id_list))
		{
		$data .='<option id="brand'.$row_get_brand_id_list['brand_id'].'sec'.$count.'" value="'. $row_get_brand_id_list['brand_id'].'">'.ucwords($row_get_brand_id_list['brand_name']).'</option>';
        }
		$data .='</select>';
         // brand end   
		$data .='<input class="search_product'.$count.' input-large keyup-char marginr10" type="text" ';
		$data .='onkeypress="return searchProduct(event);" placeholder="Search here..." name="srch'.$count.'" id="srch'.$count.'">';
		$data .='<input  class=" btn-success marginr10"  id="filter'.$count.'" onClick="getProducts('.$count.','.$row_sec_data['cmp_info_id'].',0);" ';
		$data .='value="Filter" type="button">';
		$data .='<input  class=" btn-warning"  id="reset'.$count.'" onClick="resetfilter('.$count.');" ';
		$data .='value="Reset" type="button">';
	    $data .='</div>';
		//----------------------------------------------End filter for existing section ----------------------------------------//
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	    //----------------- start  display filtered product------------------------------//
		$data .='<div class=" span12" id="product_table'.$count++.'" style="max-height:300px;display:none;margin-left: 0.2%">';
		$data .='</div>';
		//----------------- End display filtered product------------------------------//
		
		$data .='</div>';
		$data .='</div>';
        $data .= '<script type="text/javascript">';
		$data .= '$(".select2-me").select2();';	
		$data .= '</script>';
		}
		//----------------------------Existing Section Part End Here-------------------------------------------------------------//
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//----------------------------ADD New Section Part End Here-------------------------------------------------------------//
		if($count <= $row_camp_data['no_of_section'] )// for error updation
		{  
			$n = $row_camp_data['no_of_section']+-$count + 1;
			for($i =1;$i<=$n;$i++)
			{
			if($count ==$section_no)
			{
				$data .='<input type="hidden" value="'.$count.'" id="active_section">';	
				$data .='<input type="hidden" value="0" id="active_info_id">';	
			}	
			$data .= '<div class="control-group" ';
			if($count ==$section_no)
			{
			$data .=' style="display:block"';
			}
			else 
			{
				$data .=' style="display:none"';
			}
			$data .=' id="section_no_'.$count.'">';
			$data .='<input type="hidden" value="'.$count.'" id="ative_section" >';	
			$data .= '<label for="tasktitel" class="control-label" style="text-align:center"><h5 style="font-size:19px"> Section '.$count.'</h5><h4>';
			
			if($count >1){
				$data .= '<input style="width:140px;"   class=" btn-pn" id="nxt_sec'.$count.'" onclick="nxt_sec('.$count.',0,'.$row_camp_data['no_of_section'].','.$row_camp_data['campaign_id'].');"';
				$data .=' type="button" value="<< Pre Section" >';	
			}
			
			$data .='<input style="width:140px;margin-top:10px"  type="button" class=" btn-success" id="save_sec_btn'.$count.'" onclick="save_section('.$count.',1,'.$row_camp_data['campaign_id'].',0);" style="display:none;width:"140px" value="Save Section" />';
			
			$data .='</h4></label>';
			$data .= '<div class="controls">';
			$products = explode(',',$row_sec_data['cmp_info_products']);
			$data .='<input class="input-large keyup-char" type="text" onkeypress=" return charsonly(event)" ';
			$data .='placeholder="Enter Category Name" value="'.$row_sec_data['cmp_info_section_name'].'" name="section'.$count.'" id="section'.$count.'">';
			
			$data .='&nbsp;&nbsp;<input class="input-large keyup-char" type="text"';
			$data .='placeholder="Enter Section Slug" value="" name="section_slug'.$count.'" id="section_slug'.$count.'">';
			
		  /////////////////////////////////////////////////////////////////////////////////////////////////////
		  //----------------------------- Start Filetr  for Add new  Section -----------------------------  // 
		  
			$data .='<div id="'.$count.'" style="margin-top: 10px;">';	//count start	 
			$data .='<select name="prod_cat_id_list'.$count.'" id="prod_cat_id_list'.$count.'"';
			$data .='   class = "select2-me input-large marginr10">';
			$data .= '<option value="">Select Category</option>';
			$sql_get_cat_id_list = " SELECT `cat_id`,`cat_name` FROM `tbl_category` ";
			$sql_get_cat_id_list .= " WHERE `cat_id` IN (SELECT distinct(prod_catid) FROM `tbl_products_master`) ";
			$sql_get_cat_id_list .= " 	and `cat_type` = 'parent' ";
			$result_get_cat_id_list = mysqli_query($db_con,$sql_get_cat_id_list) or die(mysqli_error($db_con));
			// Query for checking Category is not empty
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
						$data .= '<option id="cat'.$row_chk_isParent['cat_id'].'sec'.$count.'" value="'.$row_chk_isParent['cat_id'].'">'.ucwords($row_chk_isParent['cat_name']).'</option>';
						$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'add',$count);
					}
				}										
			$data .='</select>';
		  
			// for organization
			$data .='<select name="prod_org_id_list'.$count.'" id="prod_org_id_list'.$count.'" onChange="loadProducts();"  ';
			$data .=' class = "select2-me input-large marginr10">';
			$sql_get_org_id_list = "SELECT `org_id`,`org_name` FROM `tbl_oraganisation_master` WHERE `org_id` ";
			$sql_get_org_id_list .=" IN(SELECT distinct(prod_orgid) FROM `tbl_products_master`); ";
			$result_get_org_id_list = mysqli_query($db_con,$sql_get_org_id_list) or die(mysqli_error($db_con));
			$data .='<option value="">Select Organisation</option>';
			while($row_get_org_id_list = mysqli_fetch_array($result_get_org_id_list))
			{
				$data .='<option id="org'.$row_get_org_id_list['org_id'].'sec'.$count.'" value="'.$row_get_org_id_list['org_id'].'">'.ucwords($row_get_org_id_list['org_name']).'</option>';
			}
			$data .='</select>';
			//organization end
			// for brand
			$data .='<select name="prod_brand_id_list'.$count.'" id="prod_brand_id_list'.$count.'"   class = "select2-me input-large marginr10">';
			$sql_get_brand_id_list = "SELECT `brand_id`,`brand_name` FROM `tbl_brands_master` WHERE `brand_id` ";
			$sql_get_brand_id_list .=" IN (SELECT distinct(prod_brandid) FROM `tbl_products_master`) ";
			$result_get_brand_id_list = mysqli_query($db_con,$sql_get_brand_id_list) or die(mysqli_error($db_con));
			$data .='<option value="">Select Brand</option>';
			while($row_get_brand_id_list = mysqli_fetch_array($result_get_brand_id_list))
			{
				$data .='<option id="brand'.$row_get_brand_id_list['brand_id'].'sec'.$count.'" value="'. $row_get_brand_id_list['brand_id'].'">'.ucwords($row_get_brand_id_list['brand_name']).'</option>';
			}
			$data .='</select>';
			$data .= '<script type="text/javascript">';
			$data .= '$(".select2-me").select2();';	
			$data .= '</script>';
			 // brand end   
			$data .='<input class="input-large keyup-char marginr10" onkeypress="return searchProduct(event);" type="text" placeholder="Search here..." name="srch'.$count.'" id="srch'.$count.'">';
			$data .='<input class=" btn-success marginr10"  id="filter'.$count.'" onClick="getProducts('.$count.',-1,0);" value="Filter" type="button">';
			$data .='<input  class=" btn-warning"  id="reset'.$count.'" onClick="resetfilter('.$count.');" ';
			$data .='value="Reset" type="button">';
			$data .='</div>';
			//----------------------------------------------end filter for add new section ----------------------------------------------------//
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
			$data .='<div class="show_product_data span12" id="product_table'.$count++.'" style="height:300px;display:none;margin-left:0.2%">';
			$data .='</div>';
			
			$data .='</div>';
			$data .='</div>';
			
			//----------------------------- End Section -----------------------------		
			
			$data .= '<script type="text/javascript">';
			$data .= '$("#prod_brand_id_list"+'.$count.').select2();';
		//	$data .= '$("#branch_state").select2();';
			$data .= '$("#branch_city").select2();';
		//	$data .= 'CKEDITOR.replace("branch_detail_add",{height:"150", width:"100%"});';
		//	$data .= 'CKEDITOR.replace("branch_meta_description",{height:"150", width:"100%"});';		
			$data .= '</script>';
			} //for end
			
		}
		
		//----------------------------End New Section Part End Here-------------------------------------------------------------//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
			
		
		$response_array = array("Success"=>"Success","resp"=>$data);
		echo json_encode($response_array);exit();
	} 
	
	//--------------------EDIT RWQUEST END HERE------------------------//
	//////////////////////////////////////////////////////////////////////
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Not received");		
	}
	echo json_encode($response_array);
}


if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$campaign_id			= $obj->campaign_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();
	$sql_update_status 		= " UPDATE `tbl_campaign` SET `status`= '".$curr_status."' ,`modified` = now() ,`modified_by` = '".$uid."' WHERE `campaign_id` = '".$campaign_id."' ";
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




if((isset($obj->delete_campaign)) == "1" && isset($obj->delete_campaign))
{   
	$data 			="";
	$camp 		= $obj->camp;
	foreach($camp as $camp_id)
	{

	$sql_delete_camp	= " DELETE FROM `tbl_campaign` WHERE `campaign_id` = '".$camp_id."' ";
	$result_delete_camp= mysqli_query($db_con,$sql_delete_camp) or die(mysqli_error($db_con));
	if($result_delete_camp){	
	$sql_delete_camp	= " DELETE FROM `tbl_campaign_info` WHERE `cmp_info_campid` = '".$camp_id."' ";
	$result_delete_camp= mysqli_query($db_con,$sql_delete_camp) or die(mysqli_error($db_con));
	}
	if($result_delete_camp){
	$sql_delete_camp	= " DELETE FROM `tbl_campaign_imgs` WHERE `cmp_img_campid` = '".$camp_id."' ";
	$result_delete_camp= mysqli_query($db_con,$sql_delete_camp) or die(mysqli_error($db_con));
	}
	if($result_delete_camp){
	
	
	$files = glob('../images/banners_img/campaign_id'.$camp_id.'/*'); //get all file names
	foreach($files as $file){
    if(is_file($file))
    unlink($file); // for delete file
	}
	unlink('../images/banners_img/campaign_id'.$camp_id);// for delete folder
	}
	}
	if($result_delete_camp)
	{
	
	 $response_array = array("Success"=>"Success","resp"=>"Deleted");	
		   		
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Deletion Failed.");
	}
	
	echo json_encode($response_array);	
}


if((isset($obj->add_product)) == "1" && isset($obj->add_product))
{
	$campaign_id 		= $obj->campaign_id;
	$section_name 		= $obj->section_name;
	$products 		    = $obj->products;
	$req_type 			= $obj->req_type; //  1 for add product 2 for update request
	$cmp_info_id 		= $obj->cmp_info_id;
	$submit_req 		= $obj->submit_req;
	$section_slug       = $obj->section_slug;
	
	$response_array = array();	
	$add_flag_product = 0; 
	
	
	
	$products       = array_unique($products);
	$prod           =implode(',',$products);
	
	//---------------------Start ADD Section Requset--------------------------------------//
	if($req_type == 1)
	{
		
		$sql_get_sort_order =" SELECT * FROM tbl_campaign_info WHERE cmp_info_campid='".$campaign_id."' ";
		$res_get_sort_order = mysqli_query($db_con,$sql_get_sort_order) or die(mysqli_error($db_con));
		$num_get_sort_order = mysqli_num_rows($res_get_sort_order);
		$sec_sort_order     = $num_get_sort_order + 1;
		
		$sql_add_product	=" INSERT INTO `tbl_campaign_info`(`cmp_info_campid`,section_slug, `cmp_info_section_name`, `cmp_info_products`,sec_sort_order)"; 
		$sql_add_product   .=" VALUES ('".$campaign_id."', '".$section_slug."','".$section_name."','".$prod."','".$sec_sort_order."')";
		$result_add_product= mysqli_query($db_con,$sql_add_product) or die(mysqli_error($db_con));			
		$last_id = $db_con->insert_id;	
		if($result_add_product )
		{       
				$sec_status =1;
				$add_flag_product = 1; 
				
				$sql_check_img ="SELECT * FROM  tbl_campaign_imgs WHERE cmp_img_campid = '".$campaign_id."' ";
				$result_check_img 	= mysqli_query($db_con,$sql_check_img) or die(mysqli_error($db_con));
				$present_img        =mysqli_num_rows($result_check_img);
				  
				$sql_check_sec ="SELECT * FROM  tbl_campaign_info WHERE cmp_info_campid = '".$campaign_id."' ";
				$result_check_sec 	= mysqli_query($db_con,$sql_check_sec) or die(mysqli_error($db_con));
				$present_sec        =mysqli_num_rows($result_check_sec);
				while($row =mysqli_fetch_array($result_check_sec))
				{
					if($present_img ==0 || $row['cmp_info_products']=="")
					  { 
						 $sec_status =0;
					  }
				}
				
				$sql_no_of__sec ="SELECT * FROM tbl_campaign WHERE campaign_id = '".$campaign_id."' ";
				$result_no_of__sec 	= mysqli_query($db_con,$sql_no_of__sec) or die(mysqli_error($db_con));
				$row = mysqli_fetch_array($result_no_of__sec);
				 
				if($row['no_of_section']==$present_sec && $present_img !=0)
				{
				$sql_update_status 		= " UPDATE `tbl_campaign` SET `sec_status`= '".$sec_status."'  WHERE `campaign_id` = '".$campaign_id."' ";
				$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
				}
				
		}
		if($add_flag_product == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Section Insertion Success.");			
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Record Insertion failed.");
		}
		echo json_encode($response_array); exit();	
	}// if end
	//---------------------Start ADD Section Requset--------------------------------------//
	
	$result_update_product =0;
	if($req_type == 2)
	{
	$sql_get_product	=" SELECT * FROM tbl_campaign_info WHERE cmp_info_id = '".$cmp_info_id."'"; 
	$result_add_product= mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
	$product_data =mysqli_fetch_array($result_add_product);
	$prod ="";
	if($product_data['cmp_info_products'] !="")
	{
	$prod =$product_data['cmp_info_products'];
	
	if(sizeof($products)>0)
	{
	$prod = $prod.",";	
	}
	}
	
	$new_prod         =implode(",",$products);
	$prod            =$prod.$new_prod;
	//$prod            =explode(',',$prod);
	//$prod            =array_unique($prod);
	//$prod            =implode(',',$prod);	
	
	$sql_update_product 		= " UPDATE `tbl_campaign_info` SET `cmp_info_products`= '".$prod."',section_slug='".$section_slug."', ";
	$sql_update_product 		.= "cmp_info_section_name='".$section_name."'  WHERE `cmp_info_id` = '".$cmp_info_id."' ";
	$result_update_product 	= mysqli_query($db_con,$sql_update_product) or die(mysqli_error($db_con));
			
	if($submit_req ==1)
	{
	$sql_update_status 		= " UPDATE `tbl_campaign` SET `submit_status`= '1'  WHERE `campaign_id` = '".$campaign_id."' ";
	$result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	
	}
	
	if($result_update_product)
	{ 
	
	}
	if($result_update_product == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Section Updation Success.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Updation failed.");
	}
	echo json_encode($response_array); exit();
	}//elseif end
	else 
	{
		$response_array = array("Success"=>"fail","resp"=>"No Request Found.");
	}

	
	echo json_encode($response_array);	
}


if((isset($obj->load_error)) == "1" && isset($obj->load_error))
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
		
		$sql_load_data  = " SELECT tcm.* , tcu.fullname ,(SELECT fullname FROM tbl_cadmin_users WHERE id = tcm.modified_by ) AS name_mod ";					
		$sql_load_data  .= " FROM `tbl_campaign` as tcm INNER JOIN tbl_cadmin_users AS tcu ";
		$sql_load_data  .= " 	ON tcm.created_by = tcu.id   WHERE 1=1  and sec_status = 0 ";
		if($utype !=1)
		{
		$sql_load_data  .= " and tcm.created_by ='".$uid."' ";	
		}
		
		if(strcmp($utype,'1')!==0)
		{
			//$sql_load_data  .= " AND branch_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (cam_name like '%".$search_text."%'or tcm.created like '%".$search_text."%' or tcm.modified like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY campaign_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{		
			$campaign_data  = "";	
			$campaign_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$campaign_data .= '<thead>';
    	  	$campaign_data .= '<tr>';
         	$campaign_data .= '<th style="text-align:center">Sr No.</th>';
			$campaign_data .= '<th style="text-align:center">Campaign  Name</th>';
			$campaign_data .= '<th style="width:6%;text-align:center">Number of Section</th>';
			$campaign_data .= '<th style="text-align:center">Created</th>';
			$campaign_data .= '<th style="text-align:center">Created By</th>';
			$campaign_data .= '<th style="text-align:center">Modified</th>';
			$campaign_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_marketing.php",3);
			
			$edit = checkFunctionalityRight("view_marketing.php",1);
			if($edit){
			$campaign_data .= '<th style="text-align:center">Action</th>';
			}
			$del = checkFunctionalityRight("view_marketing.php",2);
			
			if($del)
			{			
			$campaign_data .= '<th style="text-align:center">';
			$campaign_data .= '<div style="text-align:center">';
			$campaign_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/>';
			$campaign_data .= '</div></th>';
			}
			$campaign_data .= '</tr>';
      		$campaign_data .= '</thead>';
      		$campaign_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{   
			    
	    	  	$campaign_data .= '<tr>';				
				$campaign_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$campaign_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['cam_name']).'"';
				$campaign_data .=' class="btn-link" id="'.$row_load_data['campaign_id'].'" onclick="addMoreCamp(this.id,\'view\',1);"></td>';
				$campaign_data .= '<td style="text-align:center">'.$row_load_data['no_of_section'].'</td>';
				$campaign_data .= '<td style="text-align:center">'.$row_load_data['created'].'</td>';
				$campaign_data .= '<td style="text-align:center">'.ucwords($row_load_data['fullname']).'</td>';
				$campaign_data .= '<td style="text-align:center">'.$row_load_data['modified'].'</td>';
				$campaign_data .= '<td style="text-align:center">'.ucwords($row_load_data['name_mod']).'</td>';
				/*$dis = checkFunctionalityRight("view_marketing.php",3);
				if($dis)
				{				
				$campaign_data .= '<td style="text-align:center">';	
				if($row_load_data['status'] == 1)
				{
				$campaign_data .= '<input type="button" value="Active" id="'.$row_load_data['campaign_id'].'"';
				$campaign_data .= ' class="btn-success" onclick="changeStatus(this.id,0);">';
				}
				else
				{
				$campaign_data .= '<input type="button" value="Inactive" id="'.$row_load_data['campaign_id'].'"';
				$campaign_data .=' class="btn-danger" onclick="changeStatus(this.id,1);">';
				}
				$campaign_data .= '</td>';	
				}*/
				$edit = checkFunctionalityRight("view_marketing.php",1);
				
				if($edit)
				{				
					$campaign_data .= '<td style="text-align:center">';
					$campaign_data .= '<input type="button" value="Edit" id="'.$row_load_data['campaign_id'].'"';
					$campaign_data .=' class="btn-warning" onclick="addMoreCamp(this.id,\'edit\',1);"></td>';				
				}
				$del = checkFunctionalityRight("view_marketing.php",2);
			
				if($del)
				{					
					$campaign_data .= '<td><div class="controls" align="center">';
					$campaign_data .= '<input type="checkbox" value="'.$row_load_data['campaign_id'].'" id="batch'.$row_load_data['campaign_id'].'"';
					$campaign_data .='  name="batch'.$row_load_data['campaign_id'].'" class="css-checkbox camp">';
					$campaign_data .= '<label for="batch'.$row_load_data['campaign_id'].'" class="css-label"></label>';
					$campaign_data .= '</div></td>';										
				}
	          	$campaign_data .= '</tr>';		
			
			}	
      		$campaign_data .= '</tbody>';
      		$campaign_data .= '</table>';	
			$campaign_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$campaign_data);					
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


if((isset($obj->update_req)) == "1" && isset($obj->update_req))
{   $campaign_name			= mysqli_real_escape_string($db_con,$obj->campaign_name);
	$no_of_section			= $obj->no_of_section;
	$campaign_status		= $obj->campaign_status;
	$campaign_id            = $obj->campaign_id;
	$response_array 		= array();
	
	$sql_update_data 		= " UPDATE tbl_campaign SET cam_name= '".$campaign_name."' ,no_of_section= '".$no_of_section."' ,status='".$campaign_status."'  WHERE campaign_id =".$campaign_id." ";

	$result_update_data 	= mysqli_query($db_con,$sql_update_data) or die(mysqli_error($db_con));
	
		if($result_update_data == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Section Updation Success.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Updation failed.");
	}
	echo json_encode($response_array);	
}


////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------Strat Update Campiagn--------------------------------------------------

if((isset($_POST['req_type'])) == "1" && isset($_POST['req_type']))
{   $campaign_name			= mysqli_real_escape_string($db_con,$_POST['campaign_name']);
	$no_of_section			= $_POST['no_of_section'];
	$campaign_status		= $_POST['campaign_status'];
	$campaign_id            = $_POST['campaign_id'];
	$img_del_data           = $_POST['img_delete'];
	$slug                   =str_replace(' ','-',$campaign_name);
	$slug                   = strtolower($slug.'-'.$campaign_id.'-mr');
	$response_array 		= array();
	$section_num            = $_POST['section_num'];
	if($section_num==0)
	{
		$section_num =$_POST['no_of_section'];
	}
	
	
	for($i=0;$i < count($_FILES['file']['name']); $i++)
	{
	if($_FILES['file']['name'][$i] !="")
	{
	$imagedata = getimagesize($_FILES['file']['tmp_name'][$i]);
	if($imagedata[0] >1920 || $imagedata[0] <1920 )
	{
    $response_array = array("Success"=>"fail","image_fail"=>1,"campaign_id"=>$campaign_id,"resp"=>" Image width is not equal to 1920");	
	echo json_encode($response_array);exit();
	}
	
	}// if end
	}// for end
	
	$sql_update_data 		= " UPDATE tbl_campaign SET cam_name= '".$campaign_name."' ,no_of_section= '".$no_of_section."' ,status='".$campaign_status."',campion_slug='".$slug."',modified_by='".$uid."',modified=NOW()  WHERE campaign_id =".$campaign_id." ";
    $result_update_data 	= mysqli_query($db_con,$sql_update_data) or die(mysqli_error($db_con));
	
	
	
	$target_path ="../images/banners_img/campaign_id";
	$sort_order =$_POST['banner_check'];
	for($i=0;$i < count($_FILES['file']['name']); $i++)
	{   $sort_order++;
	    $sec_dir 	= $target_path.$campaign_id;
		if(is_dir($sec_dir) === false)
		{
		  mkdir($sec_dir);
		}
		$file=$sec_dir.'/'.$_FILES['file']['name'][$i];
		if($_FILES['file']['name'][$i] !="")
		{
			
			$fileData = pathinfo(basename($_FILES["file"]["name"][$i]));
			$fileName = uniqid() . '.' . $fileData['extension'];	
			$target_path ="../images/banners_img/campaign_id".$campaign_id."/".$fileName;
	
		
			while(file_exists($target_path))
			{
				$fileName = uniqid() . '.' . $fileData['extension'];
				$target_path ="../images/banners_img/campaign_id".$campaign_id."/".$fileName;
			}
											
			move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_path);
			$sql_insert_image	= " INSERT INTO `tbl_campaign_imgs`(`cmp_img_campid`, `cmp_img_name`,cmp_img_order) ";
			$sql_insert_image  .=" VALUES ('".$campaign_id."', '".$fileName."','".$sort_order."') ";
			$res_insert_image 	= mysqli_query($db_con, $sql_insert_image) or die(mysqli_error($db_con));
		  
		}// if end
	}// for end
	
	
	//for wrong entry validation
		
		    $sec_status =1;
			$submit_status =1;
	        $add_flag_error = 1; 
			$sql_check_sec ="SELECT * FROM  tbl_campaign_info WHERE cmp_info_campid = '".$campaign_id."' ";
			$result_check_sec 	= mysqli_query($db_con,$sql_check_sec) or die(mysqli_error($db_con));
			$present_sec        =mysqli_num_rows($result_check_sec);
			if($present_sec >0)
			{
				while($row =mysqli_fetch_array($result_check_sec))
				{
					if($row['cmp_info_products']=="" ||  $no_of_section !=$present_sec)
					{ 
						$sec_status =0;
						$submit_status =0;
					}
				}
			}
			else
			{
			$sec_status =0;
			$submit_status =0;
			}
		
		
			 $sql_update_status 		= " UPDATE `tbl_campaign` SET `sec_status`= '".$sec_status."',`submit_status`= '".$submit_status."'  WHERE `campaign_id` = '".$campaign_id."' ";
	         $result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
			
			 
	// wrong entry end
	if($section_num >$no_of_section)
	{
	$section_num =	$no_of_section;
	}
	
	if($result_update_data == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Section Updation Success.","campaign_id"=>$campaign_id,"section_num"=>$section_num);			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Updation failed.");
	}
	echo json_encode($response_array);	
}


//-----------------------------End Update Campiagn--------------------------------------------------
////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------Strat Delete Section--------------------------------------------------
if((isset($obj->delete_section)) == "1" && isset($obj->delete_section))
{   $sec_status    = 1;
	$campaign_id   = $obj->campaign_id;
	$no_of_section = $obj->no_of_section;
	$cmp_info_id   = $obj->cmp_info_id;
	
	$sql_delete_section	= " DELETE FROM `tbl_campaign_info` WHERE `cmp_info_id` = '".$cmp_info_id."' ";
	$result_delete_section= mysqli_query($db_con,$sql_delete_section) or die(mysqli_error($db_con));
	if($result_delete_section)
	{
	$no_of_section  = $no_of_section - 1;
	if($no_of_section==0){
	$sec_status = 0;
	}	
	$sql_update_data 		= " UPDATE tbl_campaign SET no_of_section= '".$no_of_section."' ,modified_by='".$uid."',modified=NOW() ,sec_status ='".$sec_status."'  WHERE campaign_id =".$campaign_id." ";
    $result_update_data 	= mysqli_query($db_con,$sql_update_data) or die(mysqli_error($db_con));
	if($result_update_data)
	{
		$response_array = array("Success"=>"Success","resp"=>"Section Deleted Successfully.");
	}
	else 
	{
		$response_array = array("Success"=>"fail","resp"=>"Status Deleted Unsuccessfully.");
	}
	}
	else
    {
	$response_array = array("Success"=>"fail","resp"=>"Request Not Found.");	
	}
	
	echo json_encode($response_array);	
}
//-----------------------------End Delete section--------------------------------------------------
////////////////////////////////////////////////////////////////////////////////////////////////////////




if((isset($obj->delete_banner)) == "1" && isset($obj->delete_banner))
{   $response_array 		= array();
	$img_id = $obj->img_id; 
	$sql_get_img_data="SELECT * FROM tbl_campaign_imgs WHERE  `cmp_img_id` = '".$img_id."'";
	$result_get_img_data= mysqli_query($db_con,$sql_get_img_data) or die(mysqli_error($db_con));
	if($result_get_img_data){
	while($row_img_data =mysqli_fetch_array($result_get_img_data) ){
		
	$image_name= $row_img_data['cmp_img_name'];
	$camp_id= $row_img_data['cmp_img_campid'];
	$sql_delete_img	= " DELETE FROM `tbl_campaign_imgs` WHERE `cmp_img_id` = '".$img_id."' ";
	$result_delete_img= mysqli_query($db_con,$sql_delete_img) or die(mysqli_error($db_con));
			if($result_delete_img)
			{ 
		     $target_path="../images/banners_img/campaign_id".$camp_id.'/'.$image_name;
			unlink($target_path);
			}
		}
		
	$sql_check_img    ="SELECT * FROM tbl_campaign_imgs WHERE cmp_img_campid = '".$camp_id."'";
	$result_check_img = mysqli_query($db_con,$sql_check_img) or die(mysqli_error($db_con));
	$num_of_img       = mysqli_num_rows($result_check_img);
	if($num_of_img ==0){
		
	$sql_update_data 		= " UPDATE tbl_campaign SET modified_by='".$uid."',modified=NOW() ,sec_status =0  WHERE campaign_id =".$camp_id." ";
    $result_update_data 	= mysqli_query($db_con,$sql_update_data) or die(mysqli_error($db_con));
	
	    }
	
	$response_array = array("Success"=>"Success","resp"=>"Image Deleted Successfully.");
	}// if end
	else
    {
		$response_array = array("Success"=>"fail","resp"=>"Image Deletion failed.");
	}
	echo json_encode($response_array);
}


if((isset($obj->remove_products)) == "1" && isset($obj->remove_products))
{   
	$data 			="";
	$remove_data 	= $obj->delete_data;
	$info_id 		= $obj->info_id;
	$response_array = array();	
	$new_products   = array();
	$sql_get_product	=" SELECT * FROM tbl_campaign_info WHERE cmp_info_id = '".$info_id."'"; 
	$result_add_product= mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
	$product_data =mysqli_fetch_array($result_add_product);
	$prod =explode(',',$product_data['cmp_info_products']);
	$campaign_id =$product_data['cmp_info_campid'];
	for($i=0;$i<sizeof($prod);$i++)
	{
	if(!in_array($prod[$i],$remove_data)){ 
	array_push($new_products,$prod[$i]);
	}
	
	   		
	}
	$prod = implode(',',$new_products);
	$sql_update_product 		= " UPDATE `tbl_campaign_info` SET `cmp_info_products`= '".$prod."'  WHERE `cmp_info_id` = '".$info_id."' ";
	$result_update_product 	= mysqli_query($db_con,$sql_update_product) or die(mysqli_error($db_con));
	if($result_update_product){
	        if($prod == ""){
	        $sql_update_status 		= " UPDATE `tbl_campaign` SET `sec_status`= '0',modified_by='".$uid."',modified=now()  WHERE `campaign_id` = '".$campaign_id."' ";
	        $result_update_status 	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
			}
			
	$response_array = array("Success"=>"Success","resp"=>$product_data['cmp_info_products']);
	} else {
		$response_array = array("Success"=>"fail","resp"=>"Deletion Failed.");
	}
	
	echo json_encode($response_array);	
}

if((isset($obj->update_slug)) == "1" && isset($obj->update_slug))
{   

	$slug 			= $obj->slug;
	$img_id 		= $obj->img_id;
	$response_array = array();	
	
	$sql_update_slug 		= " UPDATE `tbl_campaign_imgs` SET `cmp_img_url`= '".$slug."'  WHERE `cmp_img_id` = '".$img_id."' ";
	$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
	if($result_update_slug)
	{
		$response_array = array("Success"=>"Success","resp"=>"");
	}
	 else 
	{
		$response_array = array("Success"=>"fail","resp"=>"Updation Failed.");
	}
	
	echo json_encode($response_array);	
}

if((isset($obj->update_order)) == "1" && isset($obj->update_order))
{   

	$order 			= $obj->order;
	$img_id 		= $obj->img_id;
	$campaign_id    = $obj->campaign_id;
	$response_array = array();	
	
	$sql_get_order = " SELECT * FROM tbl_campaign_imgs WHERE `cmp_img_id` = '".$img_id."'";
	$res_get_order 	= mysqli_query($db_con,$sql_get_order) or die(mysqli_error($db_con));
	$order_row      = mysqli_fetch_array($res_get_order);
	$sort_order     = $order_row['cmp_img_order'];
	
	if($sort_order < $order)
		{
			$sql_get_order1 = " SELECT * FROM tbl_campaign_imgs WHERE `cmp_img_campid` = '".$campaign_id."' AND `cmp_img_order`= '".$order."'";
			$res_get_order1 	= mysqli_query($db_con,$sql_get_order1) or die(mysqli_error($db_con));
			$order_row1      = mysqli_fetch_array($res_get_order1);
			
			$sql_update_slug 		= " UPDATE `tbl_campaign_imgs` SET `cmp_img_order`= '".$sort_order."'  WHERE `cmp_img_id` = '".$order_row1['cmp_img_id']."'  ";
			$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
		
		}
		
	$sql_update_slug 		= " UPDATE `tbl_campaign_imgs` SET `cmp_img_order`= '".$order."'  WHERE `cmp_img_id` = '".$img_id."' ";
	$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
	
	
	
	if($result_update_slug)
	{  
	    if($sort_order < $order)
		{
			/*$sql_update_slug 		= " UPDATE `tbl_campaign_imgs` SET `cmp_img_order`= '".$sort_order."'  WHERE `cmp_img_id` = '".$row['cmp_img_id']."' AND `cmp_img_order`= '".$order."' ";
			$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));*/
		
		}
		else
		{
			$sql_get_order = " SELECT * FROM tbl_campaign_imgs WHERE cmp_img_order >=".$order." AND  cmp_img_order <=".$sort_order." AND  `cmp_img_campid` = '".$campaign_id."' AND  `cmp_img_id` != '".$img_id."'";
			$res_get_order 	= mysqli_query($db_con,$sql_get_order) or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($res_get_order))
			{   
				$order                  = $row['cmp_img_order'] + 1;
				$sql_update_slug 		= " UPDATE `tbl_campaign_imgs` SET `cmp_img_order`= '".$order."'  WHERE `cmp_img_id` = '".$row['cmp_img_id']."' ";
				$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
			}
		}
		$response_array = array("Success"=>"Success","resp"=>"Updation Failed.");
	}
	 else 
	{
		$response_array = array("Success"=>"fail","resp"=>"Updation Failed.");
	}
	
	echo json_encode($response_array);	
}


if((isset($obj->update_section_order)) == "1" && isset($obj->update_section_order))
{
    $order 			= $obj->order;
	$section_id 		= $obj->section_id;
	$campaign_id    = $obj->campaign_id;
	$response_array = array();	
	
	
	
	$sql_get_order = " SELECT * FROM tbl_campaign_info WHERE `cmp_info_id` = '".$section_id."'";
	$res_get_order 	= mysqli_query($db_con,$sql_get_order) or die(mysqli_error($db_con));
	$order_row      = mysqli_fetch_array($res_get_order);
	$sort_order     = $order_row['sec_sort_order'];
	
	if($sort_order < $order)
		{
			$sql_get_order1 = " SELECT * FROM tbl_campaign_info WHERE `cmp_info_campid` = '".$campaign_id."' AND `sec_sort_order`= '".$order."'";
			$res_get_order1 	= mysqli_query($db_con,$sql_get_order1) or die(mysqli_error($db_con));
			$order_row1      = mysqli_fetch_array($res_get_order1);
			
			$sql_update_slug 		= " UPDATE `tbl_campaign_info` SET `sec_sort_order`= '".$sort_order."'  WHERE `cmp_info_id` = '".$order_row1['cmp_info_id']."'  ";
			$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
		
		}
		
	$sql_update_slug 		= " UPDATE `tbl_campaign_info` SET `sec_sort_order`= '".$order."'  WHERE `cmp_info_id` = '".$section_id."' ";
	$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
	
	
	
	if($result_update_slug)
	{  
	    if($sort_order < $order)
		{
			/*$sql_update_slug 		= " UPDATE `tbl_campaign_imgs` SET `cmp_img_order`= '".$sort_order."'  WHERE `cmp_img_id` = '".$row['cmp_img_id']."' AND `cmp_img_order`= '".$order."' ";
			$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));*/
		
		}
		else
		{
			$sql_get_order = " SELECT * FROM tbl_campaign_info WHERE sec_sort_order >=".$order." AND  sec_sort_order <=".$sort_order." AND  `cmp_info_campid` = '".$campaign_id."' AND  `cmp_info_id` != '".$section_id."'";
			$res_get_order 	= mysqli_query($db_con,$sql_get_order) or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($res_get_order))
			{   
				$order                  = $row['sec_sort_order'] + 1;
				$sql_update_slug 		= " UPDATE `tbl_campaign_info` SET `sec_sort_order`= '".$order."'  WHERE `cmp_info_id` = '".$row['cmp_info_id']."' ";
				$result_update_slug 	= mysqli_query($db_con,$sql_update_slug) or die(mysqli_error($db_con));
			}
		}
		$response_array = array("Success"=>"Success","resp"=>"Updation Failed.");
	}
	 else 
	{
		$response_array = array("Success"=>"fail","resp"=>"Updation Failed.");
	}
	
	echo json_encode($response_array);	
}
?>