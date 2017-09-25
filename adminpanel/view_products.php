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
<!doctype html>
<html>
    <head>
    <?php
        /* This function used to call all header data like css files and links */
        headerdata($feature_name);
        /* This function used to call all header data like css files and links */
    ?>
    </head>
	<body  class="<?php echo $theme_name;?>" data-theme="<?php echo $theme_name;?>" >
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
            	<div class="container-fluid" id="div_view_spec">
					<?php
                    /* this function used to add navigation menu to the page*/
                    breadcrumbs($home_url,$home_name,'View Products',$filename,$feature_name);
                    /* this function used to add navigation menu to the page*/
                    ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        <?php echo $feature_name; ?>
                                    </h3>
                                    &nbsp;&nbsp;
                                    <?php
                                    // ====================================================================================
									// START : DDL for Company Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_company" id="ddl_company" onChange="loadData();"  class = "select2-me input-large">
                                    	<?php
                                        // Query For getting all the companies from the system
										$sql_get_company	= " SELECT * FROM `tbl_customer_company` ";
										$res_get_company	= mysqli_query($db_con, $sql_get_company) or die(mysqli_error($db_con));
										$num_get_company	= mysqli_num_rows($res_get_company);
										
										if($num_get_company != 0)
										{
											?>
											<option value="">Select Company</option>
											<?php
											while($row_get_company = mysqli_fetch_array($res_get_company))
											{
												// Query for getting the idea about the status of the company
												$sql_get_status	= " SELECT * FROM `tbl_customer_company` WHERE `comp_id`='".$row_get_company['comp_id']."' ";
												$res_get_status	= mysqli_query($db_con, $sql_get_status) or die(mysqli_error($db_con));
												$row_get_status	= mysqli_fetch_array($res_get_status);
												$comp_status	= $row_get_status['comp_status'];
												?>
												<option value="<?php echo $row_get_company['comp_id']; ?>">
                                                	<?php echo ucwords($row_get_company['comp_name']); ?>
                                                </option>
												<?php
											}
										}
										else
										{
											?>
											<option value="">No Match Found</option>
											<?php	
										}
										?>	
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Company Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									
									// ====================================================================================
									// START : DDL for Brand Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_brand" id="ddl_brand" onChange="loadData();"  class = "select2-me input-large">
                                    	<?php
                                        // Query For getting all the brands from the system
										$sql_get_brand	= " SELECT * FROM `tbl_brands_master` ";
										$res_get_brand	= mysqli_query($db_con, $sql_get_brand) or die(mysqli_error($db_con));
										$num_get_brand	= mysqli_num_rows($res_get_brand);
										
										if($num_get_brand != 0)
										{
											?>
											<option value="">Select Brand</option>
											<?php
											while($row_get_brand = mysqli_fetch_array($res_get_brand))
											{
												// Query for getting the idea about the status of the company
												$sql_get_status	= " SELECT * FROM `tbl_brands_master` WHERE `brand_id`='".$row_get_brand['brand_id']."' ";
												$res_get_status	= mysqli_query($db_con, $sql_get_status) or die(mysqli_error($db_con));
												$row_get_status	= mysqli_fetch_array($res_get_status);
												$comp_status	= $row_get_status['brand_status'];
												?>
												<option value="<?php echo $row_get_brand['brand_id']; ?>">
                                                	<?php echo ucwords($row_get_brand['brand_name']); ?>
                                                </option>
												<?php
											}
										}
										else
										{
											?>
											<option value="">No Match Found</option>
											<?php	
										}
										?>	
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Brand Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									
									// ====================================================================================
									// START : DDL for Category Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_category" id="ddl_category" onChange="loadData();"  class = "select2-me input-large">
                                    	<?php
                                        // Query For getting all categories from the system
										$sql_get_cats	= " SELECT * FROM `tbl_category` ";
										$sql_get_cats	.= " WHERE `cat_status`='1' ";
										$sql_get_cats	.= " 	AND `cat_name`!='none' ";
										$sql_get_cats	.= " 	AND `cat_type`='parent' ";
										$sql_get_cats	.= " ORDER BY `cat_name` ASC ";
										$res_get_cats	= mysqli_query($db_con, $sql_get_cats) or die(mysqli_error($db_con));
										$num_get_cats	= mysqli_num_rows($res_get_cats);
										
										if($num_get_cats != 0)
										{
											?>
											<option  value="">Select Category</option>
											<?php
											while($row_get_cats = mysqli_fetch_array($res_get_cats))
											{
												?>
												<option value="<?php echo $row_get_cats['cat_id']; ?>">
                                                	<?php echo ucwords($row_get_cats['cat_name']); ?>
                                                </option>
												<?php
												echo getSubCatValue($row_get_cats['cat_id'], 'add');
											}
										}
										else
										{
											?>
											<option value="">No Match Found</option>
											<?php	
										}
										?>
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Category Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									
									// ====================================================================================
									// START : DDL for Created By Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_created_by" id="ddl_created_by" onChange="loadData();" class="select2-me input-large">
                                    	<?php
                                        // Query For getting the user list for created by
										$sql_get_created_by	= " SELECT `id`, `fullname` FROM `tbl_cadmin_users` ";
										$sql_get_created_by	.= " WHERE `id` IN (SELECT distinct(prod_created_by) ";
										$sql_get_created_by	.= " 				FROM `tbl_products_master`) ";
										$res_get_created_by	= mysqli_query($db_con, $sql_get_created_by) or die(mysqli_error($db_con));
										$num_get_created_by	= mysqli_num_rows($res_get_created_by);
										
										if($num_get_created_by != 0)
										{
											?>
											<option value="">Select Created By User</option>
											<?php
											while($row_get_created_by = mysqli_fetch_array($res_get_created_by))
											{
												?>
												<option value="<?php echo $row_get_created_by['id']; ?>">
                                                	<?php echo ucwords($row_get_created_by['fullname']); ?>
                                                </option>
												<?php	
											}
										}
										else
										{
											?>
											<option value="">No Match Found</option>
											<?php	
										}
										?>
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Created By Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									
									// ====================================================================================
									// START : DDL for Modified By Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_modified_by" id="ddl_modified_by" onChange="loadData();" class="select2-me input-large">
                                    	<?php
                                        // Query For getting the user list for modified by
										$sql_get_modified_by	= " SELECT `id`, `fullname` FROM `tbl_cadmin_users` ";
										$sql_get_modified_by	.= " WHERE `id` IN (SELECT distinct(prod_modified_by) ";
										$sql_get_modified_by	.= " 				FROM `tbl_products_master`) ";
										$res_get_modified_by	= mysqli_query($db_con, $sql_get_modified_by) or die(mysqli_error($db_con));
										$num_get_modified_by	= mysqli_num_rows($res_get_modified_by);
										
										if($num_get_modified_by != 0)
										{
											?>
											<option value="">Select Created By User</option>
											<?php
											while($row_get_modified_by = mysqli_fetch_array($res_get_modified_by))
											{
												?>
												<option value="<?php echo $row_get_modified_by['id']; ?>">
                                                	<?php echo ucwords($row_get_modified_by['fullname']); ?>
                                                </option>
												<?php	
											}
										}
										else
										{
											?>
											<option value="">No Match Found</option>
											<?php	
										}
										?>
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Modified By Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									
									// ====================================================================================
									// START : DDL for Product Status Filter [Active/Inactive] [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_prod_status" id="ddl_prod_status" onChange="loadData();"  class = "select2-me input-large">
                                    	<option value="" selected>All Products</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Product Status Filter [Active/Inactive] [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									
									// ====================================================================================
									// START : DDL for Stock Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_stock" id="ddl_stock" onChange="loadData();"  class = "select2-me input-large">
                                    	<option value="" selected>All Products</option>
                                        <option value="1">In-Stock</option>
                                        <option value="0">Out-Of-Stock</option>
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Stock Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									
									// ====================================================================================
									// START : DDL for Image/No Image Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_image" id="ddl_image" onChange="loadData();"  class = "select2-me input-large">
                                    	<option value="" selected>All Products</option>
                                        <option value="1">Has Image</option>
                                        <option value="0">Has No Image</option>
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Image/No Image Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									
									// ====================================================================================
									// START : DDL for Google Product Category Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
									<select name="ddl_gpc" id="ddl_gpc" onChange="loadData();"  class = "select2-me input-large">
                                    	<option value="" selected>All Products</option>
                                        <option value="1">Has Google Product Category</option>
                                        <option value="0">Has No Google Product Category</option>
                                    </select>
									<?php
									// ====================================================================================
									// END : DDL for Google Product Category Filter [dn by Prathamesh on 11 Sept 2017]
									// ====================================================================================
									?>
                                </div>
                                <div class="box-content nopadding">
                                    <?php
                                        $add = checkFunctionalityRight($filename,0);
                                        if($add)
                                        {
                                            ?>
                                            <a href="<?php echo $BaseFolder; ?>add_products.php" class="btn-info" target="_self">
                                            	<i class="icon-plus"></i>&nbspAdd Products
                                            </a>
                                            <?php
                                        }
                                    ?>
                                    <div style="padding:10px 15px 10px 15px !important">
                                        <input type="hidden" name="hid_page" id="hid_page" value="1">
                                        <select name="rowlimit" id="rowlimit" onChange="loadData();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php getloder();?>
        <script type="text/javascript">
			$( document ).ready(function()
			{
				$('#srch').keypress(function(e)
				{
					if(e.which == 13)
					{
						$("#hid_page").val("1");
						loadData();
					}
				});
				
				loadData();
				
				$('#container1 .pagination li.active').live('click',function()
				{
					var page = $(this).attr('p');
					$("#hid_page").val(page);
					loadData();
				});
			});
			
			function loadData()
			{
				loading_show();
				row_limit 		= $.trim($('select[name="rowlimit"]').val());
				search_text 	= $.trim($('#srch').val());
				page 			= $.trim($("#hid_page").val());
				load_products 	= "1";
				if(row_limit == "" && page == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');
					$('#error_model').modal('toggle');
					loading_hide();
				}
				else
				{
					var sendInfo = {"row_limit":row_limit, "search_text":search_text, "load_products":load_products, "page":page};
					var prod_load = JSON.stringify(sendInfo);
					$.ajax({
						url: "load_products.php",
						type: "POST",
						data: prod_load,
						contentType: "application/json; charset=utf-8",
						success: function(response)
						{
							data = JSON.parse(response);
							if(data.Success == "Success")
							{
								$("#container1").html(data.resp);
								loading_hide();
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
							loading_hide();
							//alert("complete");
						}
					});
				}
			}
			
			
		</script>
    </body>
</html>