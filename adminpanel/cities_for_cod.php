<?php
	include("include/routines.php");
	checkuser();
	chkRights(basename($_SERVER['PHP_SELF']));
	
	// This is for dynamic title, bread crum, etc.
	$title = "COD Cities";
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
	
	// Query For Getting all cities list 
	$sql_get_city_list	= " SELECT * ";
	$sql_get_city_list	.= " FROM city ";
	$sql_get_city_list	.= " WHERE city_id NOT IN (SELECT cod_city_city_id ";
	$sql_get_city_list	.= "                       FROM tbl_cod_cities) ";
	$res_get_city_list	= mysqli_query($db_con, $sql_get_city_list) or die(mysqli_error($db_con));
	$num_get_city_list	= mysqli_num_rows($res_get_city_list);
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
    
    <body class="<?php echo $theme_name;?>" data-theme="<?php echo $theme_name;?>">
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
                <div class="container-fluid" id="div_view_area">
              		<?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'View Area',$filename,$feature_name); 
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
                                </div> <!-- header title-->
                                <div class="box-content nopadding">
                                	<form id="frm_add_cod_cities" name="frm_add_cod_cities" method="post" class="form-horizontal form-bordered form-validate" >
                                    	<div class="control-group">
                                        	<label for="tasktitel" class="control-label">Select Cities<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            	<select style=" width: 95%;margin-top:10px;" name="cod_cities[]" multiple="multiple"  id="cod_cities" onChange="console.log($(this).children(":selected").length);" placeholder="Enter Cities For COD" class="input-block-level select2-me"  data-rule-required="true">
                                                	<option value="">Select Cities</option>
                                                	<?php
                                                    if($num_get_city_list != 0)
													{
														while($row_get_city_list = mysqli_fetch_array($res_get_city_list))
														{
															?>
															<option value="<?php echo $row_get_city_list['city_id']; ?>">
                                                            	<?php echo ucwords($row_get_city_list['city_name']); ?>
                                                            </option>
															<?php	
														}
													}
													else
													{
														?>
														<option value="">No Cities Available</option>
														<?php
													}
													?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions">
                                        	<button type="submit" name="reg_submit_add" class="btn-success">Save Cities For COD</button>
                                        </div>
                                    </form>
                                    <?php
									// Query For getting the list of all cities which are applicable for the COD
									$sql_get_cod_cities	= " SELECT * FROM `tbl_cod_cities` ";
									$res_get_cod_cities	= mysqli_query($db_con, $sql_get_cod_cities) or die(mysqli_error($db_con));
									$num_get_cod_cities	= mysqli_num_rows($res_get_cod_cities);
									?>
									<input type="hidden" id="hid_count_cod_city" name="hid_count_cod_city" value="<?php echo $num_get_cod_cities; ?>">
									<?php
									if($num_get_cod_cities != 0)
									{
										?>
                                        <div style="padding:10px 15px 10px 15px !important">
                                            <input type="hidden" name="hid_page" id="hid_page" value="1">
                                            <input type="hidden" name="ind_parent" id="ind_parent" value="Parent">
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
                                        <?php			
									}
									else
									{
										echo '<div><h3>No Cities Available For COD</h3></div>';
									}
									?>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php getloder(); ?>
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
				row_limit 	= $.trim($('select[name="rowlimit"]').val());
				search_text = $.trim($('#srch').val());
				page 		= $.trim($("#hid_page").val());								
				if(row_limit == "" && page == "")
				{
					//$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
					//$('#error_model').modal('toggle');
					//loading_hide();							
					row_limit	= 10;
					page		= 1;
				}
				//else
				{
					var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_cod_city":1, "page":page};
					var cod_city_load 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_cities_for_cod.php?",
						type: "POST",
						data: cod_city_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#container1").html(data.resp);
								loading_hide();
							} 
							else
							{					
								$("#container1").html('<span style="style="color:#F00;">'+data.resp+'</span>');														
								loading_hide();		
							}
						},
						error: function (request, status, error) 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');							
							$('#error_model').modal({
								backdrop: 'static'
							});					
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
			
			function changeStatus(isActiveID, curr_val)
			{
				change_status	= '1'
				
				var sendInfo 		= {"isActiveID":isActiveID, "curr_val":curr_val, "change_status":change_status};
				var cod_city_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_cities_for_cod.php?",
					type: "POST",
					data: cod_city_load,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{
						//alert(response);
						data = JSON.parse(response);
						
						if(data.Success == "Success") 
						{
							//alert("Some product entries goes in wrong entry section.");
							//$("#container1").html(data.resp);
							loadData();
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
						$('#error_model').modal({
							backdrop: 'static'
						});
						loading_hide();							
					},
					complete: function()
					{
					}
				});	
			}
			
			function multipleDelete()
			{			
				loading_show();		
				var batch = [];
				$(".batch:checked").each(function ()
				{
					batch.push(parseInt($(this).val()));
				});
				if (typeof batch.length == 0)
				{
					$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to delete catogery</span>');
					$('#error_model').modal('toggle');
					loading_hide();						
				}
				else
				{
					delete_category = 1;
					var sendInfo 	= {"batch":batch, "delete_cod_cities":1};
					var del_cat 	= JSON.stringify(sendInfo);								
					$.ajax({
						url: "load_cities_for_cod.php?",
						type: "POST",
						data: del_cat,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{	
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{						
								//loadData();
								location.reload();
								loading_hide();
							} 
							else
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal({
									backdrop: 'static'
								});
								loading_hide();											
							}
						},
						error: function (request, status, error) 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
							$('#error_model').modal({
								backdrop: 'static'
							});
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
			
			$('#frm_add_cod_cities').on('submit', function(e) 
			{
				e.preventDefault();
				if ($('#frm_add_cod_cities').valid())
				{
					loading_show();
					var cod_cities			= $('#cod_cities').val();
					var hid_count_cod_city	= $('#hid_count_cod_city').val();
												
					if(cod_cities == '')
					{
						$("#model_body").html('<span style="style="color:#F00;">Please fill all '*' fields</span>');
						$('#error_model').modal('toggle');	
						loading_hide();										
					}
					else
					{
						e.preventDefault();
						$('input[name="reg_submit_add"]').attr('disabled', 'true');
						var sendInfo 		= {"cod_cities":cod_cities,"insert_cod_cities":1};
						var cod_city_insert	= JSON.stringify(sendInfo);				
						$.ajax({
							url: "load_cities_for_cod.php",
							type: "POST",
							data: cod_city_insert,
							contentType: "application/json; charset=utf-8",						
							async:true,		
	
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									//if(hid_count_cod_city == 0)
									//{
										location.reload();	
									//}
									//else
									//{
									//	loadData();	
									//}
									loading_hide();
								} 
								else if(data.Success == "fail")
								{
									$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									$('#error_model').modal({
										backdrop: 'static'
									});	
									loading_hide();							
								}
								else if(data.Success == 'duplicate')
								{
									/*$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									$('#error_model').modal({
										backdrop: 'static'
									});*/
									alert(data.resp);
									location.reload();
									loading_hide();	
								}
							},
							error: function (request, status, error) 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
								$('#error_model').modal({
									backdrop: 'static'
								});	
								loading_hide();							
							},
							complete: function()
							{
							}
						});
					}
				}
			});	
        </script>
    </body>
</html>