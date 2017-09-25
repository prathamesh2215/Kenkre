<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));
// This is for dynamic title, bread crum, etc.
if(isset($_GET['pag']))
{
	$title = $_GET['pag'];
}
$path_parts   		= pathinfo(__FILE__);
$filename 	  		= $path_parts['filename'].".php";
$sql_feature 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature 	= mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  		= mysqli_fetch_row($result_feature);
$feature_name 		= $row_feature[1];
$home_name    		= "Home";
$home_url 	  		= "view_dashboard.php?pag=Dashboard";
$utype				= $_SESSION['panel_user']['utype'];
$tbl_users_owner 	= $_SESSION['panel_user']['tbl_users_owner'];
?>
<?php
function getSubCatValue($cat_id, $userType)	// Parameters : Parent ID and userType [i.e. Add, Edit, View]
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
					$data1	= getSubCatValue($row_get_children_frm_cm['cat_id'],'add');
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
?>

<!doctype html>
<html>
<head>	
<?php 
	/* This function used to call all header data like css files and links */
	headerdata($feature_name);
	subCategory();
	/* This function used to call all header data like css files and links */	
?>
<style type="text/css">
.txtoverflow
{
	color:#1C93A0 !important;
	Font-size:14px;
}
.icon-star
{
  	color: #FFD700;
    cursor: pointer;
    font-size: 20px;	
}
.icon-star:hover
{
	box-shadow: 0 1px 4px #ccc;
}
</style>
</head>
<body   class="<?php echo $theme_name;?>" data-theme="<?php echo $theme_name;?>" > 
	<?php 
	/*include Bootstrap model pop up for error display*/
	modelPopUp();
	/*include Bootstrap model pop up for error display*/	
	/* this function used to add navigation menu to the page*/ 
	navigation_menu();
	/* this function used to add navigation menu to the page*/  
	?> <!-- Navigation Bar --> 
			<div class="container-fluid" id="content">
            <div id="main" style="margin-left:0px !important">
                <div class="container-fluid" id="div_view_prod">                
					<?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'View Product',$filename,$feature_name); 
                    /* this function used to add navigation menu to the page*/ 
                    ?>          
                    <div class="row-fluid">
                    	<div class="span12">
                                <div class="box box-color box-bordered" >
                                    <div class="box-title"> 
                                    	<div style="margin-bottom:10px">                                   	
                                        	<div style="float:left;">
                                            	<h3>
                                            <i class="icon-table"></i>
                                            <?php echo $feature_name; ?>
                                        </h3> 
<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
		<?php  
			if($utype == "1"){
		?>
<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->

 		                                    	<i class="icon-chevron-down" id="chev_id" onClick="toggleMyDiv(this.id,'open_filters')" style="cursor:pointer;font-size:20px;color:#FFF"></i>
<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
		<?php  
			}
		?>
<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->
</button>
                                            </div>
                                        	&nbsp;&nbsp;&nbsp;&nbsp;                                        	
	                                        <div style="float:right">
                                        		<span style="color:#fff;font-size:13px;">Product Status</span>
                                        		<input type="radio" value="" name="prod_status" id="all_products" onChange="loadProducts();" checked>All
                                        		<input type="radio" value="1" name="prod_status" id="active_products" onChange="loadProducts();"><span style="color:#fff;font-size:13px;">Active</span>
                                        		<input type="radio" value="0" name="prod_status" id="in_active_products" onChange="loadProducts();"><span style="color:#F00;font-size:13px;">In-Active</span>
												<div style="float:right;padding:5px;color:#Fff;border-radius:5px;">
													<input type="checkbox" id="no_gpc" name="no_gpc" class="css-checkbox" onChange="loadProducts();">
													<label for="no_gpc" class="css-label">No Google Product Category</label>
												</div>
                                                <div style="float:right;padding:5px;color:#Fff;border-radius:5px;">
													<input type="checkbox" id="no_image" name="no_image" class="css-checkbox" onChange="loadProducts();">
													<label for="no_image" class="css-label">No Image</label>
												</div>  
												<div style="float:right;padding:5px;color:#Fff;border-radius:5px;">
													<input type="checkbox" id="no_desc" name="no_desc" class="css-checkbox" onChange="loadProducts();">
													<label for="no_desc" class="css-label">No Description</label>
												</div>  
<!------------------------------Start - Done by Monika 10-NOV-2016--------------------------->
						<div style="float:right;padding:5px;color:#Fff;border-radius:5px;">
													<input type="checkbox" id="no_stock" name="no_stock" class="css-checkbox" onChange="loadProducts();">
													<label for="no_stock" class="css-label">In Stock</label>
												</div>  
						<div style="float:right;padding:5px;color:#Fff;border-radius:5px;">
													<input type="checkbox" id="out_stock" name="out_stock" class="css-checkbox" onChange="loadProducts();">
													<label for="out_stock" class="css-label">Out Stock</label>
												</div>
<!------------------------------End - Done by Monika 10-NOV-2016--------------------------->                                        
											</div>
                                        </div>                                        
                                        <div id="open_filters" style="display:none;">
                                        	<div style="float:left">
<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
		<?php  
			if($utype == "1"){
		?>
<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->
	                                        	<select name="prod_org_id_list" id="prod_org_id_list" onChange="loadProducts();"  class = "select2-me input-medium">
                                        <?php

											$sql_get_org_id_list = "SELECT `org_id`,`org_name` FROM `tbl_oraganisation_master` WHERE `org_id` IN(SELECT distinct(prod_orgid) FROM `tbl_products_master`); ";
											$result_get_org_id_list = mysqli_query($db_con,$sql_get_org_id_list) or die(mysqli_error($db_con));
												?>
												<option value="">Select Organisation</option>
                                                <?php
											while($row_get_org_id_list = mysqli_fetch_array($result_get_org_id_list))
											{
												?>
	                                                <option value="<?php echo $row_get_org_id_list['org_id'];?>"><?php echo ucwords($row_get_org_id_list['org_name']);?></option>
                                                <?php
											}
											
										?>
                                        </select>
                                        	</div>                  
                                        	<div style="float:left">                                                                  
	                                        	<select name="prod_brand_id_list" id="prod_brand_id_list" onChange="loadProducts();"  class = "select2-me input-medium">
                                        <?php
											$sql_get_brand_id_list = "SELECT `brand_id`,`brand_name` FROM `tbl_brands_master` WHERE `brand_id` IN (SELECT distinct(prod_brandid) FROM `tbl_products_master`) ";
											$result_get_brand_id_list = mysqli_query($db_con,$sql_get_brand_id_list) or die(mysqli_error($db_con));
												?>
												<option value="">Select Brand</option>
                                                <?php
											while($row_get_brand_id_list = mysqli_fetch_array($result_get_brand_id_list))




											{
												?>
	                                                <option value="<?php echo $row_get_brand_id_list['brand_id'];?>"><?php echo ucwords($row_get_brand_id_list['brand_name']);?></option>
                                                <?php
											}
											
										?>
                                        </select>
                                            </div>                                        
                                        	<div style="float:left">                                                                                    
												<select name="prod_created_by" id="prod_created_by" onChange="loadProducts();"  class = "select2-me input-medium">
                                        		<?php
													$sql_get_prod_created_by = " SELECT `id`, `fullname` FROM `tbl_cadmin_users` WHERE `id` IN (SELECT distinct(prod_created_by) FROM `tbl_products_master`) ";
													$result_get_prod_created_by = mysqli_query($db_con,$sql_get_prod_created_by) or die(mysqli_error($db_con));
														?>
															<option value="">Select Created By</option>
                                                			<?php
											while($row_get_prod_created_by = mysqli_fetch_array($result_get_prod_created_by))
											{
												?>
	                                                <option value="<?php echo $row_get_prod_created_by['id'];?>"><?php echo ucwords($row_get_prod_created_by['fullname']);?></option>
                                                <?php
											}
											
										?>
                                        </select>
                                            </div>                                        
                                        	<div style="float:left">                                        
												<select name="prod_modified_by" id="prod_modified_by" onChange="loadProducts();"  class = "select2-me input-medium">
                                        		<?php
													$sql_get_prod_modified_by = " SELECT `id`, `fullname` FROM `tbl_cadmin_users` WHERE `id` IN (SELECT distinct(prod_modified_by) FROM `tbl_products_master`) ";
													$result_get_prod_modified_by = mysqli_query($db_con,$sql_get_prod_modified_by) or die(mysqli_error($db_con));
													?>
														<option value="">Select Modified By</option>
                                            			<?php
															while($row_get_prod_modified_by = mysqli_fetch_array($result_get_prod_modified_by))
															{
																?>
	                                        		        		<option value="<?php echo $row_get_prod_modified_by['id'];?>"><?php echo ucwords($row_get_prod_modified_by['fullname']);?></option>
                                            		    		<?php
															}											
														?>
                                        	</select>         
                                            </div>                                            
                                        	<div style="float:left">  
                                            <!--    done by monika on 03-01-2017    -->
                                            	<select name="prod_cat_id_list" id="prod_cat_id_list" onChange="loadProducts();"  class = "select2-me input-medium">      
                                                <!--    done by monika on 03-01-2017    -->                                                                  
	                                        	<!--<select name="prod_cat_id_list" id="prod_cat_id_list" onChange="getSubSection('category','prod_cat_id_list','prod_sub_cat_id_list');"  class = "select2-me input-medium">-->
                                        		<option value="">Select Category</option>
												
												<?php
		$data ='';
        $sql_get_cat_id_list  = " SELECT `cat_id`,`cat_name` FROM `tbl_category` ";
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
					$data .= '<option value="'.$row_chk_isParent['cat_id'].'">'.ucwords($row_chk_isParent['cat_name']).'</option>';
					$data	.= getSubCatValue($row_chk_isParent['cat_id'], 'add');
				}
			}	
			
			echo $data;									
		?>
                                        	</select>
                                            </div>                                            
                                                                                   
                                        	<div style="float:left">                                            
	                                        	<select name="prodlevel_levelid_parent" id="prodlevel_levelid_parent" onChange="getSubSection('level','prodlevel_levelid_parent','prodlevel_levelid_child');"  class = "select2-me input-medium">
                                        		<?php
													$sql_get_level_id_list = " SELECT `cat_id`,`cat_name` FROM `tbl_level` WHERE `cat_type` = 'parent' ";
													$result_get_level_id_list = mysqli_query($db_con,$sql_get_level_id_list) or die(mysqli_error($db_con));
													?>
														<option value="">Select Level</option>
                                            	    <?php
														while($row_get_level_id_list = mysqli_fetch_array($result_get_level_id_list))
														{
															?>
	                                            			    <option value="<?php echo $row_get_level_id_list['cat_id'];?>"><?php echo ucwords($row_get_level_id_list['cat_name']);?></option>
                                                			<?php
														}											
													?>
                                        	</select>
                                            </div>                                            
                                        	<div style="float:left">                                            
                                        		<select name="prodlevel_levelid_child" id="prodlevel_levelid_child" onChange="loadProducts();"  class = "select2-me input-medium">
												<option value="">Select Sub-Level</option>
                                        	</select>                                            
                                            </div>
                                        	<div style="float:left">
                                            	<select name="prodfilt_filtid_parent" id="prodfilt_filtid_parent" onChange="getSubSection('filter','prodfilt_filtid_parent','prodfilt_filtid_child');"  class = "select2-me input-medium">
                                        		<?php
													$sql_get_filter_id_list = " SELECT `filt_id`, `filt_name` FROM `tbl_filters` WHERE `filt_type` = 'parent' and `filt_sub_child` = 'parent' ";
													$result_get_filter_id_list = mysqli_query($db_con,$sql_get_filter_id_list) or die(mysqli_error($db_con));
													?>
														<option value="">Select Parent Filter</option>
                                            	    <?php
														while($row_get_filter_id_list = mysqli_fetch_array($result_get_filter_id_list))
														{
															?>
	                                            			    <option value="<?php echo $row_get_filter_id_list['filt_id'].":child";?>"><?php echo ucwords($row_get_filter_id_list['filt_name']);?></option>
                                                			<?php
														}											
													?>
                                        	</select>
                                            </div>                                            
                                        	<div style="float:left">                                            
                                        		<select name="prodfilt_filtid_child" id="prodfilt_filtid_child" onChange="getSubSection('filter','prodfilt_filtid_child','prodfilt_filtid_sub_child');"  class = "select2-me input-medium">
												<option value="">Select Child Filter</option>
                                        	</select>         
                                            </div>                                            
                                        	<div style="float:left">                                                                               
                                        		<select name="prodfilt_filtid_sub_child" id="prodfilt_filtid_sub_child" onChange="loadProducts();"  class = "select2-me input-medium">
												<option value="">Select Sub-Child Filter</option>
                                        	</select>
<!------------------------------Start - Done by Monika 9-NOV-2016--------------------------->
		<?php
			}
		?>
<!------------------------------End - Done by Monika 9-NOV-2016--------------------------->

                                            </div>    
                                            
                                     <!-----------------------START : Done By satish 20022016------------------------->     
                                             <div style="float:left">
                                            	<select name="campaign" id="campaign" onChange="getcamSection(this.value);loadProducts();"  class = "select2-me input-medium">
                                        		<?php
													$sql_get_campaign = " SELECT DISTINCT campaign_id ,cam_name FROM `tbl_campaign` as tc";
													$sql_get_campaign .=" INNER JOIN  tbl_campaign_info as tci ON tc.campaign_id =tci.cmp_info_campid  WHERE `status` = '1'  ";
													//echo $sql_get_campaign;
													$res_get_campaign = mysqli_query($db_con,$sql_get_campaign) or die(mysqli_error($db_con));
													?>
														<option value="-1">Select Campaign</option>
                                            	    <?php
														while($row_camp = mysqli_fetch_array($res_get_campaign))
														{
															?>
	                                            			    <option value="<?php echo $row_camp['campaign_id'];?>"><?php echo ucwords($row_camp['cam_name']);?></option>
                                                			<?php
														}											
													?>
                                        	</select>
                                            <select name="campaign_section" id="campaign_section" onChange="loadProducts();"  class = "select2-me input-medium">
                                        		
														<option value="">Select Section </option>
                                          
                                        	</select>
                                            </div> 
                                            
                                        <!-----------------------END : Done By satish 20022016------------------------->                                                                               	
                                        </div>    										                                        <div style="clear:both;"></div>                                        
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    <?php
										$add = checkFunctionalityRight($filename,0);
										if($add)
										{
											?>
                                            <button type="button" class="btn-info" onClick="addMoreProduct('','add')" ><i class="icon-plus"></i>&nbspAdd Product</button>
											<button type="button" class="btn-info" id="excel_click_btn" name="excel_click_btn" ><!--<img src="css\images\Excelicon.png" style="width:8% !important;">-->&nbsp Download Excel</button>
  											<?php		
										}
									?>   
									<div style="float:right;padding:5px;margin:10px">
                                    	<input type="hidden" value="" id="star_value">
										<?php
										if($utype == "1"){
										?>
										<i id="star_status" class="icon-star-empty" style="cursor:pointer;border:1px solid #ccc;border-radius:50%;font-size:30px;padding:7px;box-shadow: 0 2px 5px #989898;"></i>
										<?php
										}
										?>
									</div>                                                                          
                                    <div style="padding:10px 15px 10px 15px !important">
                                    	<input type="hidden" name="page_no_prod" id="page_no_prod" value="1">
                                    	<input type="hidden" name="excel_download" id="excel_download" value="0">
                                        <select name="row_limit_prod" id="row_limit_prod" onChange="loadProducts();"  class = "select2-me input-small">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>                                            
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                            <option value="300">300</option>
                                            <option value="400">400</option>
                                            <option value="500">500</option>                                                                                                                                                                                
                                        </select> entries per page
                                        <select name="go_to_page" id="go_to_page" onChange="go_to_page(this.value,'page_no_prod');"  class = "select2-me input-small">
                                            <option value="" disabled selected>Select Page</option>
                                        </select> Go to page
                                        <input type="text" class="input-medium" id = "search_text_prod" name="search_text_prod" placeholder="Product Id,Product Name,Model Number,Product Price,Slug/URL can be search..."  style="float:right;margin-right:10px;margin-top:10px;width:50%;" >
                                    </div>
                                    <div id="req_resp"></div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="center">
                                            <div id="loading"></div>                                            
                                            <div id="container1" class="data_container">
                                                <div class="data"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <?php
									$add = checkFunctionalityRight($filename,0);
									$edit = checkFunctionalityRight($filename,1);
									if(($add) || ($edit))
									{
										?>                                                                  
			                                <div class="box box-color box-bordered">
                                    			<div class="box-title">
                                        			<h3>
                                            			<i class="icon-table"></i>
                                            			Excel Bulk Upload For Products
                                        			</h3>                                       
                                    			</div> <!-- header title-->
                                    			<div class="box-content nopadding">
                                        			<div class="profileGallery">
                                            		<div style="width:50%;" align="center">
                                                		<div id="loading"></div>                                            
                                                		<div id="container2">
                                                    		<div class="data">
                                                        		<form method="post" enctype="multipart/form-data" id="frm_prod_excel" class="form-horizontal form-bordered form-validate">
                                                            		<div class="control-group">
                                                                    	<input type="hidden" name="excel_product_upload" id="excel_product_upload" value="1">
                                                                		<label for="tasktitel" class="control-label">Select file </label>
                                                                		<div class="controls">
                                                                    		<input type="file" name="file" id="file" data-rule-required="true" data-rule-extension="xlsx"/>
                                                                		</div>
                                                            		</div>
                                                            		<div class="form-actions">
                                                                		<button type="submit" name="reg_submit_excel" class="btn-success">Submit</button>
                                                            		</div>
                                                        		</form>
                                                    		</div>
                                                		</div>
                                            		</div>
                                                </div>
                                    			</div>
                                			</div>    
                                            <div class="box box-color box-bordered">
                                    			<div class="box-title">
                                        			<h3>
                                            			<i class="icon-table"></i>
                                            			Excel Bulk Upload Google Product Category
                                        			</h3>                                       
                                    			</div> <!-- header title-->
                                    			<div class="box-content nopadding">
                                        			<div class="profileGallery">
                                            		<div style="width:50%;" align="center">
                                                		<div id="loading"></div>                                            
                                                		<div id="container2">
                                                    		<div class="data">
                                                        		<form method="post" enctype="multipart/form-data" id="frm_prod_google_product_category_excel" class="form-horizontal form-bordered form-validate">
                                                            		<div class="control-group">
                                                                    	<input type="hidden" name="excel_prod_google_product_category" id="excel_prod_google_product_category" value="1">
                                                                		<label for="tasktitel" class="control-label">Select file </label>
                                                                		<div class="controls">
                                                                    		<input type="file" name="file" id="file" data-rule-required="true" data-rule-extension="xlsx"/>
                                                                		</div>
                                                            		</div>
                                                            		<div class="form-actions">
                                                                		<button type="submit" name="reg_submit_prod_google_product_category_excel" class="btn-success">Submit</button>
                                                            		</div>
                                                        		</form>
                                                    		</div>
                                                		</div>
                                            		</div>
                                                </div>
                                    			</div>
                                			</div>                            
        			                        <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Wrong Entries For Products
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<div style="padding:10px 15px 10px 15px !important">
                                            <input type="hidden" name="hid_page1" id="hid_page1" value="1">
                                            <input type="hidden" name="cat_parent1" id="cat_parent1" value="Parent">
                                            <select name="rowlimit1" id="rowlimit1" onChange="loadData1();"  class = "select2-me">
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> entries per page
                                            <input type="text" class="input-medium" id = "srch1" name="srch1" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
                                        </div>
                                        <div id="req_resp1"></div>
                                        <div class="profileGallery">
                                            <div style="width:99%;" align="center">
                                                <div id="loading"></div>                                            
                                                <div id="container3" class="data_container">
                                                    <div class="data"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                		                                        			        
                                		<?php
                                	}
								?> 	
                            </div>
                    </div>
                	<div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-table"></i>
                                    Bulk Image Upload For Products
                                </h3>                               
                            </div> <!-- header title-->
                            <div class="box-content nopadding">
                                <div class="profileGallery">
                                    <div style="width:50%;" align="center">
                                        <div id="loading"></div>                                            
                                        <div id="ImageUpload">
                                            <div class="data">
                                                <form method="post" enctype="multipart/form-data" id="frm_prod_image" class="form-horizontal form-bordered form-validate">
                                                    <input type="hidden" id="bulk_image_upload" name="bulk_image_upload" value="1">
                                                    <input type="hidden" id="file_count" name="file_count" value="">
                                                    <div class="control-group">
                                                        <label for="tasktitel" class="control-label">Select file(Max Image File's 20) </label>
                                                        <div class="controls">
                                                            <input type="file" name="bulk_image[]" id="bulk_image" data-rule-required="true" multiple/>
                                                        </div>
                                                    </div>
                                                    <div class="form-actions">
                                                        <button type="submit" name="reg_submit_image" class="btn-success">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div> <!-- view Product-->
				<div id="div_add_prod" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Product',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Product
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_prod" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_prod_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Product -->                               
				<div id="div_edit_prod" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Product',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Product
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_prod" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_prod_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Product -->
				<div id="div_error_prod" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Update Error Product',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Error Product
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_error_prod" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_error_prod_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- error Product -->                                                               
                <div class="container-fluid" id="div_view_prod_spec" style="display:none">  
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Product Specification',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>   
					<div id="div_add_prod_spec" style="display:none;">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                          	Add Product Specification
                                        </h3>
<?php /*?>                                            <button type="button" class="btn-info_1"  style= "float:right" onClick="closeMe('div_add_prod_spec');" ><i class="icon-minus"></i></button><?php */?>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_prod_spec" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_prod_spec_part">
                                        </div>                                    
				                         </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                </div> <!-- Add Product Specification --> 
					<div id="div_edit_prod_spec" style="display:none;">      
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Product Specification
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="closeMe('div_edit_prod_spec');" ><i class="icon-minus"></i></button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_prod_spec" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_prod_spec_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                </div> <!-- edit Product Specification -->
					<div id="div_error_prod_spec" style="display:none;">       
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Error Product Specification
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="closeMe('div_error_prod_spec');" ><i class="icon-minus"></i></button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_error_prod_spec" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_error_prod_spec_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                </div> <!-- error Product Specification-->                                                 
               		<div class="row-fluid" >
                	<div class="span12">
                    	<div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                          	View Product Specification
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                               
											<input type="hidden" id="prod_spec_prodid" name="prod_spec_prodid" value="0">
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding"> 
                                    <?php
										$add = checkFunctionalityRight($filename,0);
										if($add)
										{
											?>
                                            <button type="button" class="btn-info" id="addProdSpec" onClick="addMoreProductSpecification('','add')" ><i class="icon-plus"></i>&nbspAdd Product Specification</button>
  											<?php		
										}
									?>                                                                                                              
                                    <div style="padding:10px 15px 10px 15px !important">
                                    	<input type="hidden" name="page_no_prod_spec" id="page_no_prod_spec" value="1">
                                        <select name="row_limit_prod_spec" id="row_limit_prod_spec" onChange="loadProdSpecification();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "search_text_prod_spec" name="search_text_prod_spec" placeholder="Specification, Id can be search..."  style="float:right;margin-right:10px;margin-top:10px;width:30%;" >
                                    </div>
                                    <div id="req_resp"></div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="center">
                                            <div id="loading"></div>                                            
                                            <div id="container_prod_spec" class="data_container">
                                                <div class="data"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div> 	
                            </div>
                        </div>              
                </div>
            	<div id="div_view_prod_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Product Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>      
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Product Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_prod_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_prod_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- view Product -->  
				<div class="container-fluid" id="div_view_error_prod_spec" style="display:block" >                
               	<div class="row-fluid" >
                	<div class="span12">                
				<div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Wrong Entries For Products-Specification
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<div style="padding:10px 15px 10px 15px !important">
                                            <input type="hidden" name="hid_page2" id="hid_page2" value="1">
                                            <input type="hidden" name="cat_parent2" id="cat_parent2" value="Parent">
                                            <select name="rowlimit2" id="rowlimit2" onChange="loadData2();"  class = "select2-me">
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> entries per page
                                            <input type="text" class="input-medium" id = "srch2" name="srch2" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
                                        </div>
                                        <div id="req_resp2"></div>
                                        <div class="profileGallery">
                                            <div style="width:99%;" align="center">
                                                <div id="loading"></div>                                            
                                                <div id="container4" class="data_container">
                                                    <div class="data"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>     
                                </div> 	
                            </div>
                        </div> 	
				<div class="container-fluid" id="div_view_prod_img" style="display:none">  
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Product Images',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>   
					<div id="div_add_prod_img" style="display:none;">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                          	Add Product Images
                                        </h3>
<?php /*?>                                            <button type="button" class="btn-info_1"  style= "float:right" onClick="closeMe('div_add_prod_spec');" ><i class="icon-minus"></i></button><?php */?>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_prod_img" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data">
                                        <div id="div_add_prod_img_part">
                                        </div>                                    
				                         </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                </div> <!-- Add Product Images --> 
               		<div class="row-fluid" >
                	<div class="span12">
                    	<div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                          	View Product Images
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                               
											<input type="hidden" id="prod_img_prodid" name="prod_img_prodid" value="0">
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding"> 
                                    <?php
										$add = checkFunctionalityRight($filename,0);
										if($add)
										{
											?>
                                            <button type="button" class="btn-info" id="addProdImg" onClick="addMoreProductImages('')" ><i class="icon-plus"></i>&nbspAdd Product Image</button>
  											<?php		
										}
									?>                                                                                                              
                                    <div style="padding:10px 15px 10px 15px !important">
                                    	<input type="hidden" name="page_no_prod_img" id="page_no_prod_img" value="1">
                                        <select name="row_limit_prod_img" id="row_limit_prod_img" onChange="loadProdImages();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "search_text_prod_img" name="search_text_prod_img" placeholder="Image Id can be search..."  style="float:right;margin-right:10px;margin-top:10px;width:20%;" >
                                    </div>
                                    <div id="req_resp"></div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="center">
                                            <div id="loading"></div>                                            
                                            <div id="container_prod_img" class="data_container">
                                                <div class="data"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div> 	
                            </div>
                        </div>              
                </div>                        			
			</div>
            </div>          
    <?php getloder();?>
    <script type="text/javascript">	
		function backToMainView()
		{
			/* product show hide*/
			//location.reload();
			$("#div_view_prod").slideDown();
			$("#div_add_prod").slideUp();
			$("#div_edit_prod").slideUp();
			$("#div_error_prod").slideUp();	
			/* product show hide*/
			/* product specification show hide*/											
			$("#div_add_prod_spec").slideUp();
			$("#div_view_prod_spec").slideUp();
			$("#div_view_prod_details").slideUp();
			$("#div_view_error_prod_spec").slideUp();
			$("#addProdSpec").slideDown();						
			/* product specification show hide*/	
			/* product Image show hide*/									
			$("#div_view_prod_img").slideUp();
			$("#div_add_prod_img").slideUp();			
			$("#div_add_prod_img_part").html("");			
			$("#addProdImg").slideDown();	
			/* product Image show hide*/				
			addProdSpecfication();		
			loadProducts();																
		}		
		function checkParent(parent_id,child_id) // if child checked then check his parent
		{
/*			var level_child = $('#level_child'+child_id+':checkbox:checked').length > 0;
			if(level_child)
			{
				$('#level_parent'+parent_id).prop('checked', true);							
			}
			var filter_child = $('#filter_child'+child_id+':checkbox:checked').length > 0;
			if(filter_child)
			{
				$('#filter_parent'+parent_id).prop('checked', true);							
			}	*/		
		}
		$("#star_status").click(function()
		{
			if($(this).is('.icon-star'))
			{
			   $(this).addClass('icon-star-empty').removeClass('icon-star');				
				$("#star_value").val(0);
			}		
			else if($(this).is('.icon-star-empty'))
			{
				$(this).addClass('icon-star').removeClass('icon-star-empty');				
				$("#star_value").val(1);
			}
			loadProducts();
		});
		$( document ).ready(function() 
		{
			$('#search_text_prod').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#page_no_prod").val("1");
       			   	loadProducts();
				}
			});
			$('#srch1').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page1").val("1");					
       			   	loadData1();	
				}
			});
			$('#srch2').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page2").val("1");					
       			   	loadData2();	
				}
			}); 
			$('#search_text_prod_spec').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
								
       			   	loadProdSpecification();	
				}
			});
			$('#search_text_prod_img').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
								
       			   	loadProdImages();	
				}
			});
			loadProducts();	
			<?php
			$add = checkFunctionalityRight($filename,0);
			$edit = checkFunctionalityRight($filename,1);
			if(($add) || ($edit))
			{
			?>
			loadData1();
			<?php
			}
			?>
			loadData2();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#page_no_prod").val(page);
				loadProducts();						
			});
			$('#container3 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page1").val(page);
				loadData1();						
			});
			$('#container4 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page2").val(page);
				loadData2();						
			});
			
			/*monika*/
			$('#container_prod_spec .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#page_no_prod_spec").val(page);
				loadProdSpecification();						
			});
			$('#container_prod_img .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#page_no_prod_img").val(page);
				loadProdImages();						
			});
			/*monika*/
			
		});		
		function loadData1()
		{
			//return false;
			loading_show();
			row_limit1 		= $.trim($('select[name="rowlimit1"]').val());
			search_text1 	= $.trim($('#srch1').val());
			page1 			= $.trim($("#hid_page1").val());
			cat_parent1		= $.trim($('#cat_parent1').val());
			load_error 	= "1";						
			
			if(row_limit1 == "" && page1 == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');
				$('#error_model').modal('toggle');				
			}
			else
			{
				var sendInfo_error 		= {"row_limit1":row_limit1, "search_text1":search_text1, "load_error":load_error, "page1":page1,"cat_parent1":cat_parent1};
				var cat_load_error = JSON.stringify(sendInfo_error);				
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: cat_load_error,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{
						//alert(response);
						data = JSON.parse(response);
						
						if(data.Success == "Success") 
						{
							//alert("Some product entries goes in wrong entry section.");
							$("#container3").html(data.resp);
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#container3").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							loading_hide();
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
				});
			}
		}		
		function multipleDelete_error()
		{
			loading_show();
			var batch = [];
			$(".error_batch:checked").each(function ()
			{
				batch.push(parseInt($(this).val()));
			});
			if (typeof batch.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to delete Product</span>');
				$('#error_model').modal('toggle');						
			}
			else
			{
				//delete_catogery_error 	= 1;
				var sendInfo 	= {"batch":batch, "delete_prod_error":1};
				var del_cat 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: del_cat,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							loadData1();
							loading_hide();	
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');							
							$('#error_model').modal('toggle');
							loading_hide();													
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
				});					
			}
		}				
		// LOAD DATA FOR PRODUCT-SPECIFICATION DISPLAY [ERROR]
		function loadData2()
		{
			//return false;
			loading_show();
			row_limit2 		= $.trim($('select[name="rowlimit2"]').val());
			search_text2 	= $.trim($('#srch2').val());
			page2 			= $.trim($("#hid_page2").val());
			cat_parent2		= $.trim($('#cat_parent2').val());
			load_error1 	= "1";			
			
			if(row_limit2 == "" && page2 == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');
				$('#error_model').modal('toggle');				
			}
			else
			{
				var sendInfo_error 	= {"row_limit2":row_limit2, "search_text2":search_text2, "load_error1":load_error1, "page2":page2,"cat_parent2":cat_parent2};
				var cat_load_error 	= JSON.stringify(sendInfo_error);				
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: cat_load_error,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{
						data = JSON.parse(response);
						
						if(data.Success == "Success") 
						{
							$("#container4").html(data.resp);
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#container4").html('');
							$("#container4").html('<span style="style="color:#F00;">'+data.resp+'</span>');														
							loading_hide();						
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
				});
			}
		}							
		function multipleDelete_error1()
		{
			loading_show();
			var batch = [];
			$(".error_batch:checked").each(function ()
			{
				batch.push(parseInt($(this).val()));
			});
			if (typeof batch.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to delete Product-Specification</span>');
				$('#error_model').modal('toggle');						
			}
			else
			{
				//delete_catogery_error 	= 1;
				var sendInfo 	= {"batch":batch, "delete_prod_error":1};
				var del_cat 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: del_cat,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							loadData2();
							loading_hide();	
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');							
							$('#error_model').modal('toggle');
							loading_hide();													
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');							
						$('#error_model').modal('toggle');						
						loading_hide();
					},
					complete: function()
					{
						loading_hide();
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
				});					
			}
		}
		$('#frm_prod_excel').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_prod_excel').valid())
			{
				loading_show();	
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					async:true,					
					success: function(response) 
					{
						//alert(response);
						data = JSON.parse(response);
						//alert();
						if(data.Success == "Success") 
						{
							if(data.resp2 == 0)
							{
								alert("Some product entries goes in wrong entry section.");
							}
							else
							{
								alert("File uploaded Successfully");
							}
							loading_hide();
							window.location.assign("view_products.php?pag=<?php echo $title; ?>");
						} 
						else 
						{
							//alert("Wrong Entries");
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();							
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
				});
			}
		});		
		$('#frm_prod_google_product_category_excel').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_prod_google_product_category_excel').valid())
			{
				loading_show();	
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					async:true,					
					success: function(response) 
					{
						//alert(response);
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							alert("Google product category successfully uploaded.");
							loading_hide();
							window.location.assign("view_products.php?pag=<?php echo $title; ?>");
						} 
						else 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();							
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
				});
			}
		});
		/* Add Edit Error Product */	
		function deleteProducts()
		{			
			var batch_prod = [];
			$(".batch_prod:checked").each(function ()
			{
				batch_prod.push(parseInt($(this).val()));
			});
			if(batch_prod.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select products to delete</span>');
				$('#error_model').modal('toggle');	
				loading_hide();					
			}
			else
			{
				var sendInfo 	= {"batch_prod":batch_prod, "delete_product":1};
				var del_prod 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: del_prod,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							loadProducts();
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();	
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
				});					
			}
		}
		function changeStarStatus(prod_id,curr_status)
		{			
			loading_show();		
			if(prod_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">User id or Status to change not available</span>');
				$('#error_model').modal('toggle');
				loading_hide();				
			}
			else
			{
				var sendInfo 	= {"prod_id":parseInt(prod_id), "curr_status":curr_status, "change_product_star_status":1};
				var products_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: products_status,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadProducts();
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();								
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
			    });						
			}
				
		}
		$("#excel_click_btn").click(function()
		{
			$("#excel_download").val(1);
			loadProducts();
		});
		function loadProducts()
		{   
			loading_show();	
			
			
			///// start : done by satish 20022017 /////////////////
			
		    var campaign 				= $('#campaign').val();
			var campaign_section 		= $('#campaign_section').val();
			
			///// start : done by satish 20022017 /////////////////
			
			row_limit_prod 				= $.trim($('select[name="row_limit_prod"]').val());
			search_text_prod 			= $.trim($('#search_text_prod').val());
			page_no_prod 				= $.trim($("#page_no_prod").val());
			prod_status					= $('input[name=prod_status]:checked').val();//$.trim($('select[name="prod_status"]').val());
			prod_orgid					= $.trim($('select[name="prod_org_id_list"]').val());
			prod_brandid				= $.trim($('select[name="prod_brand_id_list"]').val());
			prod_catid					= $.trim($('select[name="prod_cat_id_list"]').val());
			
			prodlevel_levelid_parent	= $.trim($('select[name="prodlevel_levelid_parent"]').val());
			prodlevel_levelid_child		= $.trim($('select[name="prodlevel_levelid_child"]').val());
			
			prodfilt_filtid_parent		= $.trim($('select[name="prodfilt_filtid_parent"]').val());			
			prodfilt_filtid_child		= $.trim($('select[name="prodfilt_filtid_child"]').val());									
			prodfilt_filtid_sub_child	= $.trim($('select[name="prodfilt_filtid_sub_child"]').val());			
			
			prod_subcatid				= $.trim($('select[name="prod_sub_cat_id_list"]').val());	
			prod_created_by				= $.trim($('select[name="prod_created_by"]').val());	
			prod_modified_by			= $.trim($('select[name="prod_modified_by"]').val());				
			star_status					= $("#star_value").val();
			
			excel 					= $("#excel_download").val();
			if($('#no_image:checkbox:checked').length > 0)
			{
				no_image			= "image";				
			}
			else
			{
				no_image			= "";
			}
			//=========Start Done By satish 08082017=======================//
			if($('#no_desc:checkbox:checked').length > 0)
			{
				no_desc			= "no_desc";				
			}
			else
			{
				no_desc			= "";
			}
			//=========End Done By satish 08082017=======================//
//<!------------------------------Start - Done by Monika 10-NOV-2016--------------------------->
//alert($('#out_stock:checkbox:checked').length);	
			if($('#no_stock:checkbox:checked').length > 0)
			{
				no_stock			= "stock";				
			}
			else
			{
				no_stock			= "";
			}
			if($('#out_stock:checkbox:checked').length > 0)
			{

				out_stock			= "outstock";				
			}
			else
			{
				out_stock			= "";
			}	
//<!------------------------------End - Done by Monika 10-NOV-2016--------------------------->
			if($('#no_gpc:checkbox:checked').length > 0)
			{
				prod_google_product_category	= "1";				
			}
			else
			{
				prod_google_product_category	= "";
			}							
			if(row_limit_prod == "" && page_no_prod == "")
			{
				$("#req_resp").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 	= {"campaign":campaign,"campaign_section":campaign_section,"prod_google_product_category":prod_google_product_category,"excel":excel,"prod_status":prod_status,"prod_created_by":prod_created_by,"prod_modified_by":prod_modified_by,"star_status":star_status,"prod_orgid":prod_orgid,"prod_brandid":prod_brandid,"prod_catid":prod_catid,"prod_subcatid":prod_subcatid,"prodlevel_levelid_parent":prodlevel_levelid_parent,"prodlevel_levelid_child":prodlevel_levelid_child,"prodfilt_filtid_parent":prodfilt_filtid_parent,"prodfilt_filtid_child":prodfilt_filtid_child,"prodfilt_filtid_sub_child":prodfilt_filtid_sub_child,"row_limit_prod":row_limit_prod, "search_text_prod":search_text_prod, "page_no_prod":page_no_prod,"no_image":no_image,"no_stock":no_stock,"out_stock":out_stock,"no_desc":no_desc,"load_products":1};
				var prod_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: prod_load,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{  
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							if(excel == 1)
							{
								$("#excel_download").val(0);
								window.location.href = data.resp;	
								loadProducts();		
								loading_hide();
							}
							else
							{   
							      get_option(page_no_prod,data.no_of_page);
								$("#container1").html(data.resp);
							   if(page_no_prod!=1)
								{
									window.location.href = 'view_products.php?pag=Products#div_view_prod' 	;	
								}
								if(page_no_prod==1)
								{
									window.location.href = 'view_products.php?pag=Products#div_view_prod' 	;	
								}
								loading_hide();								
							}
						} 
						else if(data.Success == "fail") 
						{							
							$("#container1").html('<span style="style="color:#F00;">'+data.resp+'</span>');					
							loading_hide();							
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
			    });
			}
		}
		function addMoreProduct(prod_id,req_type)
		{			
			$('#div_view_prod').slideUp();
			$('#div_view_error_prod_spec').slideUp();
			if(req_type == "add")
			{
				$('#div_add_prod').slideDown();		
			}
			else if(req_type == "edit")
			{
				$('#div_edit_prod').slideDown();
			}	
			else if(req_type == "error")
			{
				$('#div_error_prod').slideDown();				
			}
			else if(req_type == "view")
			{
				$('#div_edit_prod_part').html(" ");
				$('#div_view_prod_details').slideDown();				
			}							
			var sendInfo = {"prod_id":prod_id,"req_type":req_type,"load_add_prod_part":"1"};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: cat_load,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{  
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							if(req_type == "add")
							{
								$("#div_add_prod_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_prod_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_prod_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_prod_part").html(data.resp);
							}
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');						
							$('#error_model').modal('toggle');						
							loading_hide();						
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
				});
		}			
		function changeProductStatus(prod_id,curr_status)
		{
			if(prod_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">User id or Status to change not available</span>');
				$('#error_model').modal('toggle');
				loading_hide();				
			}
			else
			{
				var sendInfo 	= {"prod_id":prod_id, "curr_status":curr_status, "change_product_status":1};
				var products_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: products_status,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadProducts();
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();								
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
			    });						
			}
		}	
		function changeProductData(org_prod_id,change_type)
		{
			var prod_data_to_change = 0;
			if(change_type == 2)
			{
				var prod_data_to_change = $("#"+org_prod_id).val();				
			}
			else if(change_type == 7)
			{
				prod_data_to_change		= $('input[name='+parseInt(org_prod_id)+'product_return]:checked').val();
			}		
			else if(change_type == 3 || change_type == 4 || change_type == 5)
			{
				var prod_data_to_change = $("textarea#"+org_prod_id).val();				
			}
			if(org_prod_id == "" || prod_data_to_change == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
				//$("#model_body").html('<span style="style="color:#F00;">Data Not available</span>');
				$('#error_model').modal('toggle');
				loading_hide();				
			}
				else
				{
					var sendInfo 	= {"org_prod_id":parseInt(org_prod_id),"change_type":change_type,"prod_data_to_change":prod_data_to_change, "change_product_data":1};
					var products_status 	= JSON.stringify(sendInfo);								
					$.ajax({
						url: "load_products.php?",
						type: "POST",
						data: products_status,
						contentType: "application/json; charset=utf-8",						
						async:true,					
						success: function(response) 
						{			
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{							
								loadProducts();
							} 
							else
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								loading_hide();								
							}
						},
						error: function (request, status, error) 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();							
						},
						complete: function()
						{
						}
			    	});						
				}
		}	
		function getSubSection(section_type,section_type_id,section_sub_type_id)
		{
			var section_id = $("#"+section_type_id).val();
			if(section_id == "" && section_type == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Please Select Parent to change.</span>');
				$('#error_model').modal('toggle');
				//$("#prod_subcat_id_list"+section_id).select2("val", "");
				loading_hide();				
			}
			else
			{
				var sendInfo 		= {"section_id":section_id, "section_type":section_type, "change_sub_section":1};
				var prod_slug_data 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php",
					type: "POST",
					data: prod_slug_data,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{		
							$("#"+section_sub_type_id).html(data.resp);
							loadProducts();
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();								
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
			    });						
			}			
		}
		$('#frm_prod_image').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_prod_image').valid())
			{
				
			
            //alert(input.files.length);
				loading_show();	
				
				$.ajax({
					url: "load_products.php",
					type: "POST",
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					async:false,					
					success: function(response) 
					{
						//alert(response);
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							alert("Images successfully uploaded");
							loading_hide();
							window.location.assign("view_products.php?pag=<?php echo $title; ?>");
						} 
						else 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();							
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
					}
				});
			}
		});			
		function updatePaymentOptionProducts(org_prod_id,changes_for)
		{
				loading_show();
				payment_mode = $("#payment_mode"+org_prod_id).val();
				if(payment_mode == "" || changes_for == "")
				{
					$("#model_body").html('<span style="style="color:#F00;"> Payment Mode not selected.</span>');
					$('#error_model').modal('toggle');
					loading_hide();
				}
				else
				{
					var sendInfo 	= {"payment_mode":payment_mode,"org_prod_id":org_prod_id,"changes_for":changes_for,"make_changes_for_payment_mode":1};
					var mode_changes= JSON.stringify(sendInfo);
					$.ajax({
					url: "load_organisation.php",
					type: "POST",
					data: mode_changes,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadProducts();
							loading_hide();
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');							
							$('#error_model').modal('toggle');
							loading_hide();												
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');							
						$('#error_model').modal('toggle');						
						loading_hide();
					},
					complete: function()
					{
					}
				});
				}
			}			
		$('#frm_add_prod').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_add_prod').valid())
			{
				loading_show();
				var prod_model_number				= $.trim($("#prod_model_number").val());
				var prod_name 						= $.trim($("#prod_name").val());
				var prod_description				= $.trim(CKEDITOR.instances['prod_description'].getData());
				var prod_orgid						= $.trim($('#prod_orgid').val());
				var prod_brandid					= $.trim($('#prod_brandid').val());				
				var prod_catid						= $.trim($('#prod_catid').val());
				
				
				var prod_subcatid					= $.trim($('#prod_subcatid').val());			
				var prod_google_product_category 	= $.trim($('textarea#prod_google_product_category').val());							
				var prod_content					= $.trim(CKEDITOR.instances['prod_content'].getData());
				var prod_min_quantity				= parseInt($.trim($('#prod_min_quantity').val()));				
				var prod_quantity					= $.trim($('#prod_quantity').val());
				var prod_max_quantity				= parseInt($.trim($('#prod_max_quantity').val()));				
				var prod_list_price					= $.trim($('#prod_list_price').val());								
				var prod_recommended_price			= $.trim($('#prod_recommended_price').val());
				var prod_meta_tags					= $.trim($('#prod_meta_tags').val());					
				var prod_meta_description			= $.trim(CKEDITOR.instances['prod_meta_description'].getData());
				var prod_meta_title 				= $.trim($('#prod_meta_title').val());				
				var prod_status 					= $('input[name=prod_status]:checked', '#frm_add_prod').val()	
				var filters_list 					= [];

				$(".filters_parent:checked").each(function ()
				{
					parent = parseInt($(this).val());
					$(".filters_child"+parent+":checked").each(function ()
					{
						child = parseInt($(this).val());
						$(".filters_sub_child"+child+":checked").each(function ()
						{
							filters_list.push(parent+":"+child+":"+parseInt($(this).val()));
						});							
					});								
				});		
				//alert(filters_list.length);
				var levels_list = [];
				$(".levels_parent:checked").each(function ()
				{
					parent = parseInt($(this).val());
					$(".levels_child"+parent+":checked").each(function ()
					{
						levels_list.push(parent+":"+parseInt($(this).val()));
					});								
				});							
				if(prod_model_number == "" && prod_orgid == "" && prod_catid == "" && prod_status =="")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please fill all '*' fields</span>');
					$('#error_model').modal('toggle');	
					loading_hide();										
				}
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_add_prod"]').attr('disabled', 'true');
					var sendInfo 		= {"prod_google_product_category":prod_google_product_category,"batch_filters":filters_list,"batch_levels":levels_list,"prod_model_number":prod_model_number,"prod_name":prod_name,"prod_description":prod_description,"prod_orgid":prod_orgid,"prod_brandid":prod_brandid,"prod_catid":prod_catid,"prod_subcatid":prod_subcatid,"prod_content":prod_content,"prod_min_quantity":prod_min_quantity,"prod_quantity":prod_quantity,"prod_max_quantity":prod_max_quantity,"prod_list_price":prod_list_price,"prod_recommended_price":prod_recommended_price,"prod_meta_tags":prod_meta_tags,"prod_meta_description":prod_meta_description,"prod_meta_title":prod_meta_title,"prod_status":prod_status,"insert_product":1};
					var product_insert 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_products.php",
						type: "POST",
						data: product_insert,
						contentType: "application/json; charset=utf-8",						
						async:true,		

						success: function(response) 
						{
							
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								alert("Product Inserted Successfully!!!");
								loading_hide();
								window.location.assign("view_products.php?pag=<?php echo $title; ?>");							
							} 
							else 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								loading_hide();							
							}
						},
						error: function (request, status, error) 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();							
						},
						complete: function()
						{
						}
				    });
				}
			}
		});	
		$('#frm_edit_prod').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_prod').valid())
			{
				loading_show();
				var prod_id							= $.trim($("#prod_id").val());				
				var prod_model_number				= $.trim($("#prod_model_number").val());
				var prod_name 						= $.trim($("#prod_name").val());
				var prod_description				= $.trim(CKEDITOR.instances['prod_description'].getData());
				var prod_orgid						= $.trim($('#prod_orgid').val());
				var prod_brandid					= $.trim($('#prod_brandid').val());				
				var prod_catid						= $.trim($('#prod_catid').val());
				var prod_subcatid					= $.trim($('#prod_subcatid').val());			
				var prod_google_product_category 	= $.trim($('textarea#prod_google_product_category').val());
				//alert(prod_google_product_category);exit();				
				var prod_content					= $.trim(CKEDITOR.instances['prod_content'].getData());
				var prod_min_quantity				= $.trim($('#prod_min_quantity').val());				
				var prod_quantity					= $.trim($('#prod_quantity').val());
				var prod_max_quantity				= $.trim($('#prod_max_quantity').val());
				var prod_list_price					= $.trim($('#prod_list_price').val());								
				var prod_recommended_price			= $.trim($('#prod_recommended_price').val());
				var prod_meta_tags					= $.trim($('#prod_meta_tags').val());					
				var prod_meta_description			= $.trim(CKEDITOR.instances['prod_meta_description'].getData());
				var prod_meta_title 				= $.trim($('#prod_meta_title').val());				
				var prod_status 					= $('input[name=prod_status]:checked', '#frm_edit_prod').val()	;
				
				var primary_cat			= $('input[name=primary_cat]:checked', '#frm_edit_prod').val();
				var prod_check          =0;
				
				
				var delete_cat =[];
				$(".delete_cat:checked").each(function ()
				{
					delete_cat.push(parseInt($(this).val()));
					
					 if(primary_cat==parseInt($(this).val()))
					  {
								   	
			 				       prod_check=1;
								 
					  }
				});
				 if(prod_check==1)
				 {
					 $("#model_body").html('<span style="style="color:#F00;">You Can Not delete primary category</span>');
			  		 $('#error_model').modal('toggle');
					 	loading_hide();	
						return false;
				 }
				var filters_list = [];
				$(".filters_parent:checked").each(function ()
				{
					parent = parseInt($(this).val());
					$(".filters_child"+parent+":checked").each(function ()
					{
						child = parseInt($(this).val());
						$(".filters_sub_child"+child+":checked").each(function ()
						{
							filters_list.push(parent+":"+child+":"+parseInt($(this).val()));
						});							
					});								
				});	
				var levels_list = [];
				$(".levels_parent:checked").each(function ()
				{
					parent = parseInt($(this).val());
					$(".levels_child"+parent+":checked").each(function ()
					{
						levels_list.push(parent+":"+parseInt($(this).val()));
					});								
				});	
				if(prod_id == "" && prod_model_number == "" && prod_orgid == "" && prod_catid == "" && prod_status =="")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please fill all '*' fields</span>');
					$('#error_model').modal('toggle');	
					loading_hide();										
				}
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_edit_prod"]').attr('disabled', 'true');
					var sendInfo 		= {"primary_cat":primary_cat,"remove_cat":delete_cat,"prod_google_product_category":prod_google_product_category,"batch_filters":filters_list,"batch_levels":levels_list,"prod_id":prod_id,"prod_model_number":prod_model_number,"prod_name":prod_name,"prod_description":prod_description,"prod_orgid":prod_orgid,"prod_brandid":prod_brandid,"prod_catid":prod_catid,"prod_subcatid":prod_subcatid,"prod_content":prod_content,"prod_min_quantity":prod_min_quantity,"prod_quantity":prod_quantity,"prod_max_quantity":prod_max_quantity,"prod_list_price":prod_list_price,"prod_recommended_price":prod_recommended_price,"prod_meta_tags":prod_meta_tags,"prod_meta_description":prod_meta_description,"prod_meta_title":prod_meta_title,"prod_status":prod_status,"update_product":1};
					var product_insert 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_products.php",
						type: "POST",
						data: product_insert,
						contentType: "application/json; charset=utf-8",						
						async:true,						
						success: function(response) 
						{
							//alert(response);
							//alert(prod_google_product_category);exit();
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								backToMainView();
								alert("Product Updated Successfully!!!");
								loading_hide();
								//window.location.assign("view_products.php?pag=<?php echo $title; ?>");															
							} 
							else 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								loading_hide();							
							}
						},
						error: function (request, status, error) 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();							
						},
						complete: function()
						{
						}
				    });
				}
			}
		});		
		$('#frm_error_prod').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_error_prod').valid())
			{
				//loading_show();				
				var prod_model_number				= $.trim($("#prod_model_number").val());
				var prod_name 						= $.trim($("#prod_name").val());
				var prod_description				= $.trim(CKEDITOR.instances['prod_description'].getData());
				var prod_orgid						= $.trim($('#prod_orgid').val());
				var prod_brandid					= $.trim($('#prod_brandid').val());
				var prod_catid						= $.trim($('#prod_catid').val());
				//var prod_subcatid					= $.trim($('#prod_subcatid').val());
				var prod_google_product_category 	= $.trim($('textarea#prod_google_product_category').val());											
				var prod_content					= $.trim(CKEDITOR.instances['prod_content'].getData());
				var prod_min_quantity				= $.trim($('#prod_min_quantity').val());				
				var prod_quantity					= $.trim($('#prod_quantity').val());
				var prod_max_quantity				= $.trim($('#prod_max_quantity').val());
				var prod_list_price					= $.trim($('#prod_list_price').val());								
				var prod_recommended_price			= $.trim($('#prod_recommended_price').val());
				var prod_meta_tags					= $.trim($('#prod_meta_tags').val());					
				var prod_meta_description			= $.trim(CKEDITOR.instances['prod_meta_description'].getData());
				var prod_meta_title 				= $.trim($('#prod_meta_title').val());				
				var prod_status 					= $('input[name=prod_status]:checked', '#frm_error_prod').val();
				var error_id 						= $.trim($('#error_id').val());
				var filters_list = [];
				//alert(prod_max_quantity);
				$(".filters_parent:checked").each(function ()
				{
					parent = parseInt($(this).val());
					$(".filters_child"+parent+":checked").each(function ()
					{
						child = parseInt($(this).val());
						$(".filters_sub_child"+child+":checked").each(function ()
						{
							filters_list.push(parent+":"+child+":"+parseInt($(this).val()));
						});							
					});								
				});			
				var levels_list = [];
				$(".levels_parent:checked").each(function ()
				{
					parent = parseInt($(this).val());
					$(".levels_child"+parent+":checked").each(function ()
					{
						levels_list.push(parent+":"+parseInt($(this).val()));
					});								
				});	
				if(isNaN(prod_quantity))
				{
					alert("Quantity should be numeric.");
					exit();
				}
				
				if(isNaN(prod_max_quantity))
				{
					alert("Maximum quantity should be greater than or equal to Minimum quantity.");
					exit();
				}
				if(isNaN(prod_list_price))
				{
					alert("List price should be greater than or equal to recommended price.");
					exit();
				}
													
				if(error_id == "" && prod_model_number == "" && prod_orgid == "" && prod_catid == "" && prod_status =="")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please fill all '*' fields</span>');
					$('#error_model').modal('toggle');	
					loading_hide();										
				}
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_error_prod"]').attr('disabled', 'true');
					var sendInfo 		= {"prod_max_quantity":prod_max_quantity,"prod_min_quantity":prod_min_quantity,"prod_google_product_category":prod_google_product_category,"batch_filters":filters_list,"batch_levels":levels_list,"error_id":error_id,"prod_model_number":prod_model_number,"prod_name":prod_name,"prod_description":prod_description,"prod_orgid":prod_orgid,"prod_brandid":prod_brandid,"prod_catid":prod_catid,"prod_content":prod_content,"prod_quantity":prod_quantity,"prod_list_price":prod_list_price,"prod_recommended_price":prod_recommended_price,"prod_meta_tags":prod_meta_tags,"prod_meta_description":prod_meta_description,"prod_meta_title":prod_meta_title,"prod_status":prod_status,"insert_product":1};
					var product_insert 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_products.php",
						type: "POST",
						data: product_insert,
						contentType: "application/json; charset=utf-8",						
						async:true,						
						success: function(response) 
						{//alert(response);
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								alert("Product Updated Successfully!!!");
								loading_show();	
								window.location.assign("view_products.php?pag=<?php echo $title; ?>");
							} 
							else 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								loading_hide();							
							}
						},
						error: function (request, status, error) 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();							
						},
						complete: function()
						{
						}
				    });
				}
			}
		});	
		/* Add Edit Error Product */
		
		/* Add Edit Error Product Specification*/
		function showSpecification(prod_spec_prodid)
		{
			$('#div_view_prod').slideUp();
			$('#div_view_prod_spec').slideDown();
			$("#prod_spec_prodid").val(prod_spec_prodid);
			loadProdSpecification();
		}
		function addMoreProductSpecification(prod_id,req_type)
		{
			if(prod_id == "")
			{
				prod_id = $("#prod_spec_prodid").val();
			}
			$('#div_view_prod').slideUp();
			$('#div_view_prod_spec').slideDown();						
			if(req_type == "add")
			{
				//$("#addProdSpec").slideUp();
				$('#div_add_prod_spec').slideDown();
				$('#div_edit_prod_spec').slideUp();
				$('#div_error_prod_spec').slideUp();
			}
			else if(req_type == "edit")
			{			
				$("#addProdSpec").slideDown();			
				$('#div_add_prod_spec').slideUp();
				$('#div_edit_prod_spec').slideDown();
				$('#div_error_prod_spec').slideUp();
			}	
			else if(req_type == "error")
			{
				$("#addProdSpec").slideDown();							
				$('#div_add_prod_spec').slideUp();
				$('#div_edit_prod_spec').slideUp();
				$('#div_error_prod_spec').slideDown();
			}						
			var sendInfo = {"prod_id":prod_id,"req_type":req_type,"load_add_prod_spec_part":"1"};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: cat_load,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							if(req_type == "add")
							{
								$("#div_add_prod_spec_part").slideUp();
								$("#div_add_prod_spec_part").html(data.resp);
								$("#div_add_prod_spec_part").slideDown();
							}
							else if(req_type == "edit")
							{
								$("#div_edit_prod_spec_part").slideUp();
								$("#div_edit_prod_spec_part").html(data.resp);				
								$("#div_edit_prod_spec_part").slideDown();
							}	
							else if(req_type == "error")
							{
								$("#div_error_prod_spec_part").slideUp();
								$("#div_error_prod_spec_part").html(data.resp);								
								$("#div_error_prod_spec_part").slideDown();
							}
							loadProdSpecification();
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');						
							$('#error_model').modal('toggle');						
							loading_hide();						
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
				});
		}			
		function addProdSpecfication()
		{
			loading_show();
			var prod_spec_prodid	= $.trim($("#prod_spec_prodid").val());
			if(prod_spec_prodid == "" || typeof prod_spec_prodid == "undefined")
			{
				$("#model_body").html('<span style="style="color:#F00;">Something Missing</span>');	
				$('#error_model').modal('toggle');
				loading_hide();					
			}
			else
			{
				var sendInfo 	  = {"prod_spec_prodid":prod_spec_prodid,"get_prod_spec":1};
				var get_spec_data = JSON.stringify(sendInfo);						
				$.ajax({
					url: "load_products.php",
					type: "POST",
					data: get_spec_data,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{		
							$("#div_add_prod_spec_part").slideUp();
							$("#div_add_prod_spec_part").html(data.resp);
							$("#div_add_prod_spec_part").slideDown();												
							loadProdSpecification();
							loading_hide();	
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();																									
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
			    });				
			}
		}
		function loadProdSpecification()
		{
			loading_show();
			row_limit_prod_spec 	= $.trim($('select[name="row_limit_prod_spec"]').val());
			search_text_prod_spec 	= $.trim($('#search_text_prod_spec').val());
			page_no_prod_spec 		= $.trim($("#page_no_prod_spec").val());	
			prod_spec_prodid 		= $("#prod_spec_prodid").val();	
			if(row_limit_prod_spec == "" && page_no_prod_spec == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');
				loading_hide();							
			}
			else
			{
				var sendInfo 		= {"row_limit_prod_spec":row_limit_prod_spec, "search_text_prod_spec":search_text_prod_spec,"page_no_prod_spec":page_no_prod_spec,"prod_spec_prodid":prod_spec_prodid,"load_prod_specification":1};
				var prod_spec_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_products.php",
					type: "POST",
					data: prod_spec_load,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{
						//alert(response);
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#container_prod_spec").html(data.resp);
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#container_prod_spec").html(data.resp);
							loading_hide();							
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');
						loading_hide();
					},
					complete: function()
					{
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
			    });
			}
		}	
		function changeProductSpecificationStatus(prod_spec_id,curr_status)
		{
			if(prod_spec_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">User id or Status to change not available</span>');
				$('#error_model').modal('toggle');
				loading_hide();				
			}
			else
			{
				var sendInfo 	= {"prod_spec_id":prod_spec_id, "curr_status":curr_status, "change_prod_specification_status":1};
				var prod_specification_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: prod_specification_status,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadProdSpecification();
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();								
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
			    });						
			}
		}	
		function deleteProductSpecification()
		{	
			loading_show();		
			var batch_prod_spec = [];
			$(".batch_prod_spec:checked").each(function ()
			{
				batch_prod_spec.push(parseInt($(this).val()));
			});
			if(batch_prod_spec.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select products to delete</span>');
				$('#error_model').modal('toggle');	
				loading_hide();					
			}
			else
			{
				var sendInfo 	= {"batch_prod_spec":batch_prod_spec, "delete_product_specification":1};
				var del_prod 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: del_prod,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{	
						//alert(response);
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							//alert("Specification Deleted Successfully.");
							prod_id = $("#prod_spec_prodid").val();
							addMoreProductSpecification(prod_id,"add");
							loadProdSpecification();
							$("#model_body").html('<span style="style="color:#F00;">Specification Deleted Successfully.</span>');
							$('#error_model').modal('toggle');	
							//alert("Specification Deleted Successfully.");
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();	
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
                	}
			    });					
			}
		}		
		$('#frm_add_prod_spec').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_add_prod_spec').valid())
			{
				loading_show();
				var prod_spec_prodid	= $("#prod_spec_prodid").val();
				var prod_spec_specid 	= $("#prod_spec_specid").val();
				var prod_spec_value 	= $("#prod_spec_value").val();
				if(prod_spec_specid == "" || prod_spec_value == "" || prod_spec_prodid == 0 || typeof prod_spec_prodid == "undefined" )
				{		
					$("#model_body").html('<span style="style="color:#F00;">Please fill all information</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				}
				else
				{												
						$('input[name="reg_submit_add_prod_spec"]').attr('disabled', 'true');
						var sendInfo 		= {"prod_spec_prodid":prod_spec_prodid,"prod_spec_specid":prod_spec_specid,"prod_spec_value":prod_spec_value,"insert_prod_specification":1};
						var prod_spec_insert = JSON.stringify(sendInfo);				
						$.ajax({
							url: "load_products.php",
							type: "POST",
							data: prod_spec_insert,
							contentType: "application/json; charset=utf-8",						
							async:true,							
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									$("#div_add_prod_spec_part").html("");
									
			                 	$('#div_add_prod_spec').slideUp();
									
									showSpecification(prod_spec_prodid);
									loadProdSpecification();
									loading_hide();								
								} 
								else 
								{
									$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									$('#error_model').modal('toggle');
									loading_hide();									
								}
							},
							error: function (request, status, error) 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
								$('#error_model').modal('toggle');
								loading_hide();
							},
							complete: function()
							{
								loading_hide();
								//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
            	    		}
					    });	
					}
			}
		});		
		$('#frm_edit_prod_spec').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_prod_spec').valid())
			{
				loading_show();
				var prod_spec_id		= $.trim($("#prod_spec_id").val());
				var prod_spec_prodid	= $.trim($("#prod_spec_prodid").val());
				var prod_spec_specid	= $.trim($("#prod_spec_specid").val());
				var prod_spec_value		= $.trim($("#prod_spec_value2").val());							
				if(typeof prod_spec_prodid == "undefined" || typeof prod_spec_specid == "undefined" || prod_spec_specid == "" || prod_spec_specid == "" || prod_spec_id == "") 
				{		
					$("#model_body").html('<span style="style="color:#F00;">Please fill all information</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				}
				else
				{	
					$('input[name="reg_submit_edit_prod_spec"]').attr('disabled', 'true');
					var sendInfo 		= {"prod_spec_id":prod_spec_id,"prod_spec_prodid":prod_spec_prodid,"prod_spec_specid":prod_spec_specid,"prod_spec_value":prod_spec_value,"update_prod_specification":1};
					var update_prod_specification 	= JSON.stringify(sendInfo);				
					$.ajax({
							url: "load_products.php",
							type: "POST",
							data: update_prod_specification,
							contentType: "application/json; charset=utf-8",						
							async:true,							
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{   closeMe('div_edit_prod_spec');
									loadProdSpecification();
									loading_hide();									
								} 
								else 
								{
									$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									$('#error_model').modal('toggle');
									loading_hide();									
								}
							},
							error: function (request, status, error) 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
								$('#error_model').modal('toggle');
								loading_hide();
							},
							complete: function()
							{
								loading_hide();
								//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
            	    		}
					    });	
					}			
			}
		});		
		$('#frm_error_prod_spec').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_error_prod_spec').valid())
			{
				var prod_spec_prodid	= $("#prod_spec_prodid").val();
				var prod_spec_specid 	= $("#prod_spec_specid").val();
				var prod_spec_value 	= $("#prod_spec_value").val();
				var error_id			= $("#error_id").val();
				if(prod_spec_specid == "" || prod_spec_value == "" || prod_spec_prodid == 0 || typeof prod_spec_prodid == "undefined" )
				{		
					$("#model_body").html('<span style="style="color:#F00;">Please fill all information</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				}
				else
				{												
						$('input[name="reg_submit_add_prod_spec"]').attr('disabled', 'true');
						var sendInfo 		= {"error_id":error_id,"prod_spec_prodid":prod_spec_prodid,"prod_spec_specid":prod_spec_specid,"prod_spec_value":prod_spec_value,"insert_prod_specification":1};
						var prod_spec_insert = JSON.stringify(sendInfo);		
						$.ajax({
							url: "load_products.php",
							type: "POST",
							data: product_insert,
							contentType: "application/json; charset=utf-8",						
							async:true,							
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									$("#div_error_prod_spec_part").html("");
									loadProdSpecification();
									loading_hide();									
								} 
								else 
								{
									$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									$('#error_model').modal('toggle');
									loading_hide();									
								}
							},
							error: function (request, status, error) 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
								$('#error_model').modal('toggle');
								loading_hide();
							},
							complete: function()
							{
								loading_hide();
								//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
            	    		}
					    });	
					}		
			}
		});					
		/* Add Edit Error Product Specification*/
		
		/* Add Edit Error Product Images*/
		function showImages(prod_img_prodid)
		{
			$('#div_view_prod').slideUp();
			$('#div_view_error_prod_spec').slideUp();			
			$('#div_view_prod_img').slideDown();
			$("#prod_img_prodid").val(prod_img_prodid);
			loadProdImages();
		}
		function addMoreProductImages(prod_id)
		{
			if(prod_id == "")
			{
				prod_id = $("#prod_img_prodid").val();
			}
			$('#div_view_prod').slideUp();
			$('#div_view_prod_img').slideDown();						
			$("#addProdImg").slideUp();
			$('#div_add_prod_img').slideDown();			
			var sendInfo = {"prod_id":prod_id,"load_add_prod_img_part":"1"};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: cat_load,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#div_add_prod_img_part").slideUp();
							$("#div_add_prod_img_part").html(data.resp);
							$("#div_add_prod_img_part").slideDown();
							loadProdImages();
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');						
							$('#error_model').modal('toggle');						
							loading_hide();						
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
				});
		}			
		function addProdImage()
		{
			loading_show();
			var prod_img_prodid	= $.trim($("#prod_img_prodid").val());
			if(prod_img_prodid == "" || typeof prod_img_prodid == "undefined")
			{
				$("#model_body").html('<span style="style="color:#F00;">Something Missing</span>');	
				$('#error_model').modal('toggle');
				loading_hide();					
			}
			else
			{
				var sendInfo 	  = {"prod_img_prodid":prod_img_prodid,"get_prod_img":1};
				var get_img_data = JSON.stringify(sendInfo);						
				$.ajax({
					url: "load_products.php",
					type: "POST",
					data: get_img_data,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{		
							$("#div_add_prod_img_part").slideUp();
							$("#div_add_prod_img_part").html(data.resp);
							$("#div_add_prod_img_part").slideDown();												
							loadProdImages();
							loading_hide();	
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();																									
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
			    });				
			}
		}
		function loadProdImages()
		{
			loading_show();
			row_limit_prod_img 	= $.trim($('select[name="row_limit_prod_img"]').val());
			search_text_prod_img 	= $.trim($('#search_text_prod_img').val());
			page_no_prod_img 		= $.trim($("#page_no_prod_img").val());	
			prod_img_prodid 		= $("#prod_img_prodid").val();	
			if(row_limit_prod_img == "" && page_no_prod_img == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');
				loading_hide();							
			}
			else
			{
				var sendInfo 		= {"row_limit_prod_img":row_limit_prod_img, "search_text_prod_img":search_text_prod_img,"page_no_prod_img":page_no_prod_img,"prod_img_prodid":prod_img_prodid,"load_prod_Images":1};
				var prod_img_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_products.php",
					type: "POST",
					data: prod_img_load,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#container_prod_img").html(data.resp);
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#container_prod_img").html(data.resp);
							loading_hide();							
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');
						loading_hide();
					},
					complete: function()
					{
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
			    });
			}
		}	
		function changeProductImagesStatus(prod_img_id,curr_status)
		{
			if(prod_img_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">User id or Status to change not available</span>');
				$('#error_model').modal('toggle');
				loading_hide();				
			}
			else
			{
				var sendInfo 	= {"prod_img_id":prod_img_id, "curr_status":curr_status, "change_prod_Images_status":1};
				var prod_Images_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: prod_Images_status,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadProdImages();
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();								
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
					}
			    });						
			}
		}	
		function deleteProductImages()
		{	
			loading_show();		
			var batch_prod_img = [];
			$(".batch_prod_img:checked").each(function ()
			{
				batch_prod_img.push(parseInt($(this).val()));
			});
			if(batch_prod_img.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select products to delete</span>');
				$('#error_model').modal('toggle');	
				loading_hide();					
			}
			else
			{
				var sendInfo 	= {"batch_prod_img":batch_prod_img, "delete_product_Images":1};
				var del_prod 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: del_prod,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							prod_id = $("#prod_img_prodid").val();
							addMoreProductImages(prod_id);
							loadProdImages();
							$("#model_body").html('<span style="style="color:#F00;">Images Deleted Successfully.</span>');
							$('#error_model').modal('toggle');	
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');	
							loading_hide();	
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
                	}
			    });					
			}
		}		
		$('#frm_add_prod_img').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_add_prod_img').valid())
			{
				loading_show();	
				$('input[name="reg_submit_add_prod_img"]').attr('disabled', 'true');				
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					async:true,
					success: function(response) 
					{
						  data = JSON.parse(response);
						  if(data.Success == "Success") 
						  {
							  $("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							  $('#error_model').modal('toggle');
							  $("#div_add_prod_img_part").html("");
							  addMoreProductImages(prod_img_prodid);
							  loadProdImages();
							  loading_hide();								
						  } 
						  else 
						  {
							  $("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							  $('#error_model').modal('toggle');
							  loading_hide();									
						  }
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
					}
				});
			}
		});		
		/* Add Edit Error Product Images*/		
		</script>

		  <script type="text/javascript">
           function charsonly(e)
		   {
  			  var unicode=e.charCode? e.charCode : e.keyCode
			  if (unicode !=8 && unicode !=32)
			  { 
			  //if the key isn't the backspace key (which we should allow)
			  // unicode<48||unicode>57 &&
     		  if ( unicode<48||unicode>57  )  //if not a number
          	  return false //disable key press
               }
			}
			 ///////////////// start done by satish 20022017 /////////////////////
		  function getcamSection(camp_id)
		  {    
		       
			    if(camp_id==-1)
				{
			       $('#campaign_section').prop('disabled','true');
				}
				else
				{
					$('#campaign_section').prop('disabled','false');
				}
				$('#campaign_section').html(" ");
				var sendInfo 	= {"camp_id":camp_id, "get_section":1};
				var del_prod 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_products.php?",
					type: "POST",
					data: del_prod,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							$('#campaign_section').html(data.resp);
						} 
						else
						{
							$('#campaign_section').html(data.resp);	
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');	
						loading_hide();							
					},
					complete: function()
					{
						loading_hide();								
						//$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			//$('#error_model').modal('toggle');
                	}
			    });					
			
		  }
		</script>
    </body>
</html>
