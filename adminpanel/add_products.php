<?php
	include("include/routines.php");
	checkuser();
	//chkRights(basename($_SERVER['PHP_SELF']));
	// This is for dynamic title, bread crum, etc.
	if(isset($_GET['pag']))
	{
		$title 	= $_GET['pag'];
	}
	else
	{
		$title	= 'Add Products';	
	}
	$path_parts   		= pathinfo(__FILE__);
	$filename 	  		= $path_parts['filename'].".php";
	$sql_feature 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
	$result_feature 	= mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
	$row_feature  		= mysqli_fetch_row($result_feature);
	$feature_name 		= 'Add Products'; // $row_feature[1];
	$home_name    		= "Home";
	$home_url 	  		= "view_dashboard.php?pag=Dashboard";
	$utype				= $_SESSION['panel_user']['utype'];
	$tbl_users_owner 	= $_SESSION['panel_user']['tbl_users_owner'];
?>
<!DOCTYPE html>
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
                    breadcrumbs($home_url,$home_name,'Add Products',$filename,$feature_name);
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
                                    <button type="button" class="btn-info_1" style= "float:right" onClick="location.href='<?php echo $BaseFolder; ?>view_products.php';" >
                                    	<i class="icon-arrow-left"></i>&nbsp Back
                                   	</button>
                                </div>
                                <div class="box-content nopadding">
                                	<form id="frm_add_prod" class="form-horizontal form-bordered form-validate" >
                                    <input type="hidden"  value="1" name="add_product_req" id="add_product_req"> 
                                    	
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Product Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            	<input type="text" placeholder="Product Name" id="prod_name" name="prod_name" class="input-large keyup-char" data-rule-required="true" />
                                            </div>
                                        </div>	<!-- Product Name -->
                                    
                                    	<div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Model Number<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            	<input type="text" placeholder="Model Number" id="prod_model_number" name="prod_model_number" class="input-large keyup-char" data-rule-required="true" />
                                            </div>
                                        </div>	<!-- Product Model Number -->
                                        
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Category<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            <select name="txt_cat" id="txt_cat" class = "select2-me input-large" style="width:90%">
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
                                            </div>
                                        </div><!--Category=====-->
                                        
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Form Factor<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            <select name="txt_factor" id="txt_factor"  class = "select2-me input-large" style="width:90%">
                                            	<?php
											// Query For getting all categories from the system
											$sql_get_factor	= " SELECT * FROM `tbl_form_factor` ";
											$sql_get_factor	.= " WHERE `status`='1' ";
										    $sql_get_factor	.= " ORDER BY `form_factor_name` ASC ";
											$res_get_factor	= mysqli_query($db_con, $sql_get_factor) or die(mysqli_error($db_con));
											$num_get_factor	= mysqli_num_rows($res_get_factor);
											
											if($num_get_factor != 0)
											{
												?>
												<option  value="">Select Form Factor</option>
												<?php
												while($row_get_factor = mysqli_fetch_array($res_get_factor))
												{
													?>
													<option value="<?php echo $row_get_factor['cat_id']; ?>">
														<?php echo ucwords($row_get_factor['form_factor_name']); ?>
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
                                            </div>
                                        </div><!--form factor-->
                                        
                                        <div class="control-group " style="clear:both;">
                                        	<label for="tasktitel" class="control-label">Pharmacopia<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            <select name="txt_pharmacopia" id="txt_pharmacopia" onChange="loadData();"  class = "select2-me input-large" style="width:70%">
                                            	<?php
											// Query For getting all categories from the system
											$sql_get_pharmacopia	= " SELECT * FROM `tbl_pharmacopia` ";
											$sql_get_pharmacopia	.= " WHERE `pharmacopia_status`='1' ";
										    $sql_get_pharmacopia	.= " ORDER BY `pharmacopia_name` ASC ";
											$res_get_pharmacopia	= mysqli_query($db_con, $sql_get_pharmacopia) or die(mysqli_error($db_con));
											$num_get_pharmacopia	= mysqli_num_rows($res_get_pharmacopia);
											
											if($num_get_pharmacopia != 0)
											{
												?>
												<option  value="">Select Pharmacopia</option>
												<?php
												while($row_get_pharmacopia = mysqli_fetch_array($res_get_pharmacopia))
												{
													?>
													<option value="<?php echo $row_get_pharmacopia['pharmacopia_id']; ?>">
														<?php echo ucwords($row_get_pharmacopia['pharmacopia_name']); ?>
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
                                            </div>
                                        </div><!--Pharmacopia-->
                                        
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Drug Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                           
                                            <div class="controls">
                                            <select name="txt_drug_type" id="txt_drug_type" onChange="showComposition(this.value);"  class = "select2-me input-large" style="width:90%">
                                            <option  value="">Select Drug Type</option>
											<option  value="Single">Single</option>
                                            <option  value="Multiple">Multiple</option>
                                            <option  value="Specialized">Specialized</option>
											</select>
                                            </div>
                                        </div><!--Composition Type-->
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Composition<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                             <?php
											// Query For getting all categories from the system
											$sql_get_composition	= " SELECT * FROM `tbl_composition` ";
											$sql_get_composition	.= " WHERE `spec_status`='1' ";
										    $sql_get_composition	.= " ORDER BY `spec_name` ASC ";
											$res_get_composition	= mysqli_query($db_con, $sql_get_composition) or die(mysqli_error($db_con));
											$num_get_composition	= mysqli_num_rows($res_get_composition);
											
											if($num_get_composition != 0)
											{
											?>
                                            <div class="controls" id="single">
                                            <select name="txt_cmp" id="txt_cmp" onChange="loadData();"  class = "select2-me input-large" style="width:90%">
                                            <option  value="">Select Composition</option>
                                            <?php 
											while($row_get_composition= mysqli_fetch_array($res_get_composition))
											{?>
												<option value="<?php echo $row_get_composition['spec_id']; ?>">
                                                <?php echo ucwords($row_get_composition['spec_name']); ?>
                                                </option>
											<?php 
											}
											?>
											
											</select>
                                            </div><!--single-->
                                            <div class="controls" style="display:none" id="multiple">
                                            <select multiple="multiple"  id="prod_catid" onChange="console.log($(this).children(":selected").length)" name="txt_cmp_type" id="txt_cmp_type" placeholder="Select Composition"  class = "select2-me input-large" style="width:90%">
                                            <?php 
											$res_get_composition	= mysqli_query($db_con, $sql_get_composition) or die(mysqli_error($db_con));
											while($row_get_composition= mysqli_fetch_array($res_get_composition))
											{?>
												<option value="<?php echo $row_get_composition['spec_id']; ?>">
                                                <?php echo ucwords($row_get_composition['spec_name']); ?>
                                                </option>
											<?php 
											}
											?>
											</select>
                                            </div><!--multiple and specilized-->
                                            <?php 
											} 
											else
											{
											?>
                                            <div class="controls"  id="multiple">
                                            <select multiple="multiple"  onChange="console.log($(this).children(":selected").length)" name="txt_cmp" id="txt_cmp" onChange="loadData();"  class = "select2-me input-large" style="width:90%">
                                            <option  value="">Select Composition</option>
											
											</select>
                                            </div><!--No match found-->
                                            <?php
											 } ?>
                                        </div><!--Composition Type-->
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Brand<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                           <div class="controls">
                                            <select name="txt_brand" id="txt_brand"  class = "select2-me input-large" style="width:90%">
                                            <?php
											// Query For getting all categories from the system
											$sql_get_brand	= " SELECT * FROM `tbl_brands_master` ";
											$sql_get_brand	.= " WHERE `brand_status`='1' ";
										    $sql_get_brand	.= " ORDER BY `brand_name` ASC ";
											$res_get_brand	= mysqli_query($db_con, $sql_get_brand) or die(mysqli_error($db_con));
											$num_get_brand	= mysqli_num_rows($res_get_brand);
											
											if($num_get_brand != 0)
											{
											?>
                                            <option  value="">Select Brand</option>
											<?php
												while($row_get_brand = mysqli_fetch_array($res_get_brand))
												{
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
                                            	<option  value="">No Match Found</option>
                                            <?php
											}
											?>
											</select>
                                            </div>
                                        </div><!--Brand Type-->
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Tax Class<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                           <div class="controls">
                                            <select name="txt_tax" id="txt_tax"  class = "select2-me input-large" style="width:90%">
                                            <?php
											// Query For getting all categories from the system
											$sql_get_gst	= " SELECT * FROM `tbl_gst_master` ";
											$sql_get_gst	.= " WHERE `gst_status`='1' ";
										    $sql_get_gst	.= " ORDER BY `gst_name` ASC ";
											$res_get_gst	= mysqli_query($db_con, $sql_get_gst) or die(mysqli_error($db_con));
											$num_get_gst	= mysqli_num_rows($res_get_gst);
											
											if($num_get_gst!= 0)
											{
											?>
                                            <option  value="">Select Tax Class</option>
											<?php
												while($row_get_gst = mysqli_fetch_array($res_get_gst))
												{
													?>
													<option value="<?php echo $row_get_gst['gst_id']; ?>">
														<?php echo ucwords($row_get_gst['gst_name']); ?>
													</option>
													<?php
												}
                                           }
											else
											{
											?>
                                            	<option  value="">No Match Found</option>
                                            <?php
											}
											?>
											</select>
                                            </div>
                                        </div><!--Tax Type-->
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Packing<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                           <div class="controls">
                                            <select name="txt_packing" id="txt_packing"  class = "select2-me input-large" style="width:90%">
                                            <?php
											// Query For getting all categories from the system
											$sql_get_packing	= " SELECT * FROM `tbl_packing` ";
											$sql_get_packing   .= " WHERE `status`='1' ";
										    $sql_get_packing   .= " ORDER BY `packing_name` ASC ";
											$res_get_packing	= mysqli_query($db_con, $sql_get_packing) or die(mysqli_error($db_con));
											$num_get_packing	= mysqli_num_rows($res_get_packing);
											
											if($num_get_packing!= 0)
											{
											?>
                                            <option  value="">Select Packing</option>
											<?php
												while($row_get_packing = mysqli_fetch_array($res_get_packing))
												{
													?>
													<option value="<?php echo $row_get_packing['id']; ?>">
														<?php echo ucwords($row_get_packing['packing_name']); ?> 
													</option>
													<?php
												}
                                           }
										else
										{
										?>
											<option  value="">No Match Found</option>
										<?php
										}
										?>
											</select>
                                            </div>
                                        </div><!--Packing Type-->
                                        
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Dimension<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                           <div class="controls">
                                            <input placeholder="Dimension" type="text" name="txt_dimension" id="txt_dimension"  class=" input-large" style="" />
                                           </div>
                                        </div><!--Dimension -->
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Unit of Weight<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                           <div class="controls">
                                            <select name="txt_weight" id="txt_weight"  class="select2-me input-large" style="width:90%">
                                            <?php
											// Query For getting all categories from the system
											$sql_get_weight	= " SELECT * FROM `tbl_unit_of_weight` ";
											$sql_get_weight   .= " WHERE `uow_status`='1' ";
										    $sql_get_weight   .= " ORDER BY `uow_name` ASC ";
											$res_get_weight	= mysqli_query($db_con, $sql_get_weight) or die(mysqli_error($db_con));
											$num_get_weight	= mysqli_num_rows($res_get_weight);
											
											if($num_get_weight!= 0)
											{
											?>
                                            <option  value="">Select Weight</option>
											<?php
												while($row_get_weight = mysqli_fetch_array($res_get_weight))
												{
													?>
													<option value="<?php echo $row_get_weight['uow_id']; ?>">
														<?php echo ucwords($row_get_weight['uow_name']); ?> 
													</option>
													<?php
												}
                                           }
										else
										{
										?>
											<option  value="">No Match Found</option>
										<?php
										}
										?>
											</select>
                                            </div>
                                        </div><!--Unit of weight Type-->
                                        
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Available Pack<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                           <div class="controls">
                                            <input placeholder="Available Pack" type="text" name="txt_avai_pack" id="txt_avai_pack"  class=" input-large" style="" />
                                           </div>
                                        </div><!--Available -->
                                        
                                        
                                         
                                        
                                        <div class="control-group span6">
                                        	<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                           <div class="controls">
                                            <input type="radio" name="prod_status" value="1" class="css-radio" data-rule-required="true" checked >Active
                                         <input type="radio" name="prod_status" value="0" class="css-radio" data-rule-required="true"  >Inactive
                                            
                                           </div>
                                           
                                        </div><!--Status -->
                                        
                                        
			
                                        
                                        
                                         <div class="form-actions" style="clear:both">
                                         <button type="submit" name="reg_submit_add" class="btn-success">Add Product</button>
                                         </div>
                                       
                                    </form>
                                </div>
                            </div>
                       	</div>
                   	</div>
               	</div>
           	</div>
       	</div>
        
    <!--======================Start : Javascript Dn By satish 12sep2017=========================-->
       <script type="text/javascript" >
	   	
			function showComposition(type)
			{
				alert(type);
				if(type=="Single")
				{
					$('#single').css('display','block');
					$('#multiple').css('display','none');
				}
				else
				{
					$('#single').css('display','none');
					$('#multiple').css('display','block');
				}
			}
			
			
			$('#frm_add_prod').on('submit', function(e) 
			{
				e.preventDefault();
				if ($('#frm_add_prod').valid())
				{
					
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
								
								alert("Product Added Successfully");
								window.location.assign("view_products.php?pag=Products");
							} 
							else 
							{
								//alert("Wrong Entries");
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
											
							}
						},
						error: function (request, status, error) 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
							$('#error_model').modal('toggle');	
												
						},
						complete: function()
						{
						}
					});
				}
			});		
	   	
	   </script>
     <!--======================Start : Javascript Dn By satish 12sep2017=========================-->
    </body>
</html>