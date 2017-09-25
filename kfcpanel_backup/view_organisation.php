<?php
	include("include/routines.php");	
	checkuser();
	chkRights(basename($_SERVER['PHP_SELF']));
	if(isset($_GET['pag']))
	{
		$title 	= $_GET['pag'];
	}
	else
	{
		$title	= "Organisation";	
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
	$uid				= $_SESSION['panel_user']['id'];
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
    
    <body class="<?php echo $theme_name; ?>" data-theme="<?php echo $theme_name; ?>">
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
                <div class="container-fluid" id="div_view_org"> 
                    <?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'View Organisation',$filename,$feature_name); 
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
                                	<?php
                                        $add = checkFunctionalityRight($filename,0);
                                        if($add)
                                        {
                                            ?>
                                            <button type="button" class="btn-info" onClick="addMoreOrganisation('','add')" ><i class="icon-plus"></i>&nbspAdd Organisation</button>
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
                                </div>	<!-- Main Body -->
                            </div>
                                	<?php
                                        $add = checkFunctionalityRight($filename,0);
                                        $edit = checkFunctionalityRight($filename,1);
                                        if($add || $edit)
                                        {
                                            ?>
                					            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        Excel Bulk Upload For Organisation
                                    </h3>
                                   
                                </div> <!-- header title-->
                                <div class="box-content nopadding">
                                    <div class="profileGallery">
                                        <div style="width:50%;" align="center">
                                            <div id="loading"></div>                                            
                                            <div id="container2">
                                                <div class="data">
                                                    <form method="post" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data" id="frm_org_excel">
                                                        <div class="control-group">
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
                                        Wrong Entries For Organisation
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
                </div> <!-- View Organisation -->
                
                <div id="div_add_org" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Organisation',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Organisation
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_org" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_org_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Add Organisation -->
                
                <div id="div_edit_org" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Organisation',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Organisation
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_org" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_org_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Edit Organisation -->
                
                <div id="div_view_org_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'View Details Of Organisation',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            View Details Of Organisation
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_org_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_org_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- View Details Organisation -->
                
                <div id="div_error_org" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Update Wrong Entry Of Organisation',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Wrong Entry Of Organisation
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backToMainView();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_error_org" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_error_org_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Error Organisation -->
                
                <div class="container-fluid" id="div_view_org_prod" style="display:none;"> 
                    <?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,"View Organisation Products",$filename,$feature_name); 
                    /* this function used to add navigation menu to the page*/ 
                    ?>          
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        Level and Filter Assignment
                                    </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                    
                                </div> <!-- header title-->
                                <div class="box-content nopadding">
                                    <div style="padding:10px 15px 10px 15px !important"> 
                                    	<input type="hidden" id="org_id" value="0">
                                    	<input type="hidden" id="hid_prod_page" value="1">
                                    </div>
                                    <div id="req_resp"></div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="center">
                                            <div id="loading"></div>
                                            <div id="div_view_org_prod_data">

<?php /*?><label class="control-label"><h3>Select Products</h3></label>';
			$data .= 'Select Per Page &nbsp;&nbsp;<select name="prod_row_limit" id="prod_row_limit" onChange="loadProductData();"  class = "select2-me">';
			$data .= '<option value="5">5</option>';			
			$data .= '<option value="10" selected>10</option>';
			$data .= '<option value="25">25</option>';
			$data .= '<option value="50">50</option>';
			$data .= '</select>';
			$data .= '<div class="controls data_container" style="overflow-y:scroll;margin-top:10px;" id="product_data">';
			$data .= '</div>';
			$data .= '</div>';					
			$data .= '<button class="btn-success" onClick="assignLevel();" style="margin:30px;width:70%;height:30px;">Assign Level or Filters to Products</button>';			
                                            
<?php */?>                                            </div>
                                        </div>
                                    </div>
                                </div>	<!-- Main Body -->
                            </div>
                        </div>
                    </div>
                </div> <!-- View Organisation -->
            </div>
        </div>
        <?php getloder();?>
        <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
		<script type="text/javascript">
		//done by monika start
		function backToMainView()
		{
			/* product show hide*/
			$("#div_view_org").slideDown();
			$("#div_add_org").slideUp();
			$("#div_edit_org").slideUp();
			$("#div_error_org").slideUp();	
			$('#div_view_org_details').slideUp();	
			/* product show hide*/
			loadData();																
		}		
		//done by monika end
			$( document ).ready(function() 
			{
				$('#srch').keypress(function(e) 
				{
					if(e.which == 13) 
					{	
						loadData();
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
				loadData();
				<?php
					$add = checkFunctionalityRight($filename,0);
					$edit = checkFunctionalityRight($filename,1);
					if($add || $edit)
					{
						?>				
							loadData1();
						<?php
					}
				?>
				$('#container1 .pagination li.active').live('click',function()
				{
					var page = $(this).attr('p');
					$("#hid_page").val(page);
					loadData();				
				});
				$('#container3 .pagination li.active').live('click',function()
				{
					var page = $(this).attr('p');
					$("#hid_page1").val(page);
					loadData1();						
				});
				$('#product_data .pagination li.active').live('click',function()
				{
					var page = $(this).attr('p');
					$("#hid_prod_page").val(page);
					loadProductData();
				});				
				CKEDITOR.replace( 'org_desc',{height:"150", width:"100%"});
			});	
			
			function loadProductData()
			{
				loading_show();
				row_limit   = $.trim($('select[name="prod_row_limit"]').val());
				page        = $.trim($("#hid_prod_page").val());
				org_id		= $.trim($('#org_id').val());
				
				load_org = "1";			
				
				if(row_limit == "" && page == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
					$('#error_model').modal('toggle');			
				}
				else
				{
					var sendInfo 		= {"row_limit":50,"row_limit":row_limit, "loadOrgProduct":1, "page":page,"org_id":org_id};
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
								$("#product_data").html(data.resp);
								loading_hide();
							} 
							else if(data.Success == "fail") 
							{
								$("#product_data").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$("#product_data").html('');
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
			}	
					
			function levelAssignment(org_id)
			{
				$('#org_id').val(org_id);
				loading_show();	
				if(org_id == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Ineternal Server Error</span>');
					$("#div_view_org_prod_data").html('');
					loading_hide();							
				}
				else
				{
					var sendInfo = {"org_id":org_id, "loadOrgProducts":1};
					var orgProd_load = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_products.php",
						type: "POST",
						data: orgProd_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#div_view_org_prod").slideDown();
								$("#div_view_org").slideUp();
								$("#div_view_org_prod_data").html(data.resp);
								$("#prod_row_limit").select2();
								loadProductData();
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
						}
					});					
				}
			}
			
			function assignFilterNLevelAssignment()
			{			
				var products_batch = [];
				$("input:checkbox[name=org_prod_batch]:checked").each(function ()
				{
					prod_id			= $(this).attr("id");
					var levels_batch = [];	
					level_parent_name = "level_parent_"+prod_id;
					$("input:checkbox[name="+level_parent_name+"]:checked").each(function ()
					{
						var level_parentId 	= parseInt($(this).attr("id"));
						child_level_name	= level_parentId+"level_child_"+prod_id;
						$("input:checkbox[name="+child_level_name+"]:checked").each(function ()
						{
							var level_childId = parseInt($(this).attr("id"));
							levels_batch.push(level_parentId+":"+level_childId);
						});									
					});
					var filters_batch = [];	
					filter_parent_name = "filters_parent_"+prod_id;	
					$("input:checkbox[name="+filter_parent_name+"]:checked").each(function () 
					{
						var filter_parentId = parseInt($(this).attr("id"));
						child_filter_name	= filter_parentId+"filter_child_"+prod_id;
						$("input:checkbox[name="+child_filter_name+"]:checked").each(function () 
						{
							var filter_childId = parseInt($(this).attr("id"));						
							sub_child_filter_name	= filter_parentId+"_"+filter_childId+"filter_sub_child_"+prod_id;
							$("input:checkbox[name="+sub_child_filter_name+"]:checked").each(function () 
							{
								var filter_sub_childId = parseInt($(this).attr("id"));
								filters_batch.push(filter_parentId+":"+filter_childId+":"+filter_sub_childId);
							});													
						});									
					});
					products_batch.push(prod_id+";"+levels_batch.toString()+";"+filters_batch.toString()+"*");				
        		});	
				if(products_batch.length == 0)
				{
					$("#model_body").html('<span style="style="color:#F00;">Please Select Products.</span>');
					$('#error_model').modal('toggle');						
					loading_hide();					
				}
				else
				{	
					products_batch.join();				
					var sendInfo 		= {"products_batch":products_batch,"assignFiltersNLevels":1,};
					var prod_assign 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_products.php",
						type: "POST",
						data: prod_assign,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								org_id = $("#org_id").val();
								levelAssignment(org_id);
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
							//alert("complete");
						}
					});							
				}
			}
			
			function loadData()
			{
				loading_show();
				row_limit   = $.trim($('select[name="rowlimit"]').val());
				search_text = $.trim($('#srch').val());
				page        = $.trim($("#hid_page").val());
				
				load_org = "1";			
				
				if(row_limit == "" && page == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
					$('#error_model').modal('toggle');			
				}
				else
				{
					var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_org":load_org, "page":page};
					var org_load = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_organisation.php?",
						type: "POST",
						data: org_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#container1").html(data.resp);
								<!---------------------------------------------------------------------------
								    <!----------START - done by monika on 16-02-2017
								<!---------------------------------------------------------------------------
								if(page!=1)
								{
									window.location.href = 'view_organisation.php?pag=Organisation#div_view_org';	
								}
								if(page==1)
								{
									window.location.href = 'view_organisation.php?pag=Organisation#div_view_org';	
								}
								<!---------------------------------------------------------------------------
								    <!----------END - done by monika on 16-02-2017
								<!---------------------------------------------------------------------------
								loading_hide();
							} 
							else if(data.Success == "fail") 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$("#container1").html('');
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
			
			function multipleDelete()
			{			
				loading_show();		
				var batch = [];
				$(".batch:checked").each(function ()
				{
					batch.push(parseInt($(this).val()));
				});

				delete_org 	= 1;
				var sendInfo 	= {"batch":batch, "delete_org":delete_org};
				var del_org 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_organisation.php?",
					type: "POST",
					data: del_org,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadData();
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
						//alert("complete");
					}
				});					
			}
			
			function changeStatus(org_id,curr_status)
			{
				loading_show();
				if(org_id == "" && curr_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
					$('#error_model').modal('toggle');
				}
				else
				{
					change_status 	= 1;
					var sendInfo 	= {"org_id":org_id, "curr_status":curr_status, "change_status":change_status};
					var cat_status 	= JSON.stringify(sendInfo);								
					$.ajax({
						url: "load_organisation.php?",
						type: "POST",
						data: cat_status,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{			
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{							
								loadData();
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
							//alert("complete");
						}
					});						
				}
			}
			
			function changeProductData(org_prod_id,change_type)
			{
				var prod_data_to_change = 0;
				if(change_type == 1)
				{
					var prod_data_to_change = $("#"+org_prod_id).val();				
				}
				else if(change_type == 6)
				{
					prod_data_to_change		= $('input[name='+parseInt(org_prod_id)+'product_return]:checked').val();
				}			
				if(org_prod_id == "" || prod_data_to_change == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Data Not available</span>');
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
								loadData();
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
		
		//done by monika
		 function numsonly(e)
		 {
  			  var unicode=e.charCode? e.charCode : e.keyCode
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
     		  if ( unicode<48||unicode>57  )  //if not a number
          	  return false //disable key press
              }
		}
		//done by monika
		
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
					return false;
				return true;
			}
			
			function ValidateMobile(mobileid) //This is for validating mobile number
			{
				var mobilenum = document.getElementById(mobileid).value;
				var firstdigit = mobilenum.charAt(0);
			
				if(firstdigit==0)
				{
					$("#"+mobileid).attr('maxlength','11');
				}
				else
				{
					$("#"+mobileid).attr('maxlength','10');
				}
			}
			
			function EMail(comp1_id, comp1_val)
			{
				var email_id = comp1_id;
				
				var val_email = 'val_email';
				var sendInfo = {"val_email":val_email, "comp1_val":comp1_val};
				var org_load = JSON.stringify(sendInfo);
				$.ajax({
						url: "load_organisation.php?",
						type: "POST",
						data: org_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								if(email_id == 'pri_email')
								{
									$('#comp_pri').html('<br/><span style="color:#0C0;margin-left:15px;">Emalid available</span>').show().fadeOut(5000);
								}
								else if(email_id == 'sec_email')
								{
									$('#comp_sec').html('<br/><span style="color:#0C0;margin-left:15px;">Emalid available</span>').show().fadeOut(5000);
								}
								else
								{
									$('#comp_ter').html('<br/><span style="color:#0C0;margin-left:15px;">Emalid available</span>').show().fadeOut(5000);	
								}
								loading_hide();
							} 
							else if(data.Success == "fail") 
							{
								if(email_id == 'pri_email')
								{
									$('#comp_pri').html('<br/><span style="color:#F00;margin-left:15px;">'+ data.resp +'</span>').show().fadeOut(7000);
								}
								else if(email_id == 'sec_email')
								{
									$('#comp_sec').html('<br/><span style="color:#F00;margin-left:15px;">'+ data.resp +'</span>').show().fadeOut(7000);
								}
								else
								{
									$('#comp_ter').html('<br/><span style="color:#F00;margin-left:15px;">'+ data.resp +'</span>').show().fadeOut(7000);
								}
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
			
			function moveToOrgView()
			{
				document.location.href='view_organisation.php?pag=Organization';	
			}
			
			function addMoreOrganisation(org_id,req_type)
			{
				//$('#div_view_org').css("display", "none");
				$('#div_view_org').slideUp();//done by monika
				if(req_type == "add")
				{
					//$('#div_add_org').css("display", "block");	
					$('#div_add_org').slideDown();//done by monika					
				}
				else if(req_type == "edit")
				{
					//$('#div_edit_org').css("display", "block");	
					$('#div_edit_org').slideDown();//done by monika			
				}	
				else if(req_type == "error")
				{
					//$('#div_error_org').css("display", "block");	
					$('#div_error_org').slideDown();//done by monika			
				}
				else if(req_type == "view")
				{
					//alert();
					//$('#div_edit_org_part').html(" ");
					//$('#div_view_org_details').css("display", "block");
					$('#div_edit_org_part').html(" ");//done by monika
				$('#div_view_org_details').slideDown();	//done by monika					
				}							
				var sendInfo = {"org_id":org_id,"req_type":req_type,"load_add_org_part":"1"};
				var cat_load = JSON.stringify(sendInfo);
				$.ajax({
						url: "load_organisation.php?",
						type: "POST",
						data: cat_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								if(req_type == "add")
								{
									$("#div_add_org_part").html(data.resp);
									
								}
								else if(req_type == "edit")
								{
									$("#div_edit_org_part").html(data.resp);
								}	
								else if(req_type == "error")
								{
									$("#div_error_org_part").html(data.resp);				
								}
								else if(req_type == "view")
								{
									$("#div_view_org_part").html(data.resp);
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
							loading_hide();
							//alert("complete");
						}
					});
			}	
			
			//$(document).change(function () 
			function same_as_bill()   //done by monika
			{
				if($("#address_check").prop('checked') == true)
				{
					org_bill_addrs = $.trim(CKEDITOR.instances['org_bill_addrs'].getData());
					CKEDITOR.instances['org_ship_addrs'].setData(org_bill_addrs);
					CKEDITOR.instances['org_ship_addrs'].setReadOnly(true);	// disable ckeditor 
					/* state select change*/
					bill_state = $("#bill_state").val();
					$("#ship_state").val(bill_state);
					$("#ship_state").prop("disabled",true); // disable  state select
					$("#ship_state").select2(); 				
					/* state select change*/
					/* City select change*/				
					getCity(bill_state,'ship_city');
					stopExecution();
				}
				else if($("#address_check").prop('checked') == false)
				{
					//done by monika
					CKEDITOR.instances['org_ship_addrs'].setData('');
					$("#ship_state").val('');
					$("#ship_city").val('');
					$("#ship_pincode").val('');
					//done by monika
					CKEDITOR.instances['org_ship_addrs'].setReadOnly(false);	// enable ckeditor 				
					$("#ship_state").prop( "disabled", false );	// enable  state select			
					$("#ship_state").select2();
					$("#ship_city").prop("disabled",false);
					$("#ship_city").select2();				
					$("#ship_pincode").prop("disabled",false);
				}						
			}
			
			function stopExecution()
			{
				loading_show();
				setTimeout(continueExecution, 3000) //wait ten seconds before continuing
			}
			
			function continueExecution()
			{
				perm_city = $("#bill_city").val();
				$("#ship_city").val(perm_city);
				$("#ship_city").prop("disabled",true); // disable city select
				$("#ship_city").select2();
				/* City select change*/								
				$("#ship_pincode").prop("disabled",true); // disable pincode
				$("#ship_pincode").val($("#bill_pincode").val());				
				loading_hide();			
			}

			$('#frm_org_info').on('submit', function(e) {
				e.preventDefault();
				if($('#frm_org_info').valid())
				{
					var org_name 		= $.trim($('input[name="org_name"]').val());
					var org_description	= $.trim(CKEDITOR.instances['org_desc'].getData());
					var pri_email		= $.trim($('input[name="pri_email"]').val());
					var sec_email		= $.trim($('input[name="sec_email"]').val());
					var ter_email		= $.trim($('input[name="ter_email"]').val());
					var pri_phone		= $.trim($('input[name="pri_phone"]').val());
					var alt_phone		= $.trim($('input[name="alt_phone"]').val());
					var org_fax			= $.trim($('input[name="org_fax"]').val());
					var org_website		= $.trim($('input[name="org_website"]').val());
					var org_indid	= $.trim($('select[name="org_indid"]').val());
					var org_cst			= $.trim($('input[name="org_cst"]').val());
					var org_vat			= $.trim($('input[name="org_vat"]').val());
					
					if (!$('#chk1').is(':checked'))
					{
						// Not Checked
						var addrs_type		= 'different';
						var org_bill_addrs	= $.trim($('textarea[name="org_bill_addrs"]').val());
						var bill_state		= $.trim($('select[name="bill_state"]').val());
						var bill_city		= $.trim($('select[name="bill_city"]').val());
						var bill_pincode	= $.trim($('input[name="bill_pincode"]').val());
						
						var org_ship_addrs	= $.trim($('textarea[name="org_ship_addrs"]').val());
						var ship_state		= $.trim($('select[name="ship_state"]').val());
						var ship_city		= $.trim($('select[name="ship_city"]').val());
						var ship_pincode	= $.trim($('input[name="ship_pincode"]').val());
					}
					else
					{
						// Checked
						var addrs_type		= 'same'; 
						var org_bill_addrs	= $.trim($('textarea[name="org_bill_addrs"]').val());
						var bill_state		= $.trim($('select[name="bill_state"]').val());
						var bill_city		= $.trim($('select[name="bill_city"]').val());
						var bill_pincode	= $.trim($('input[name="bill_pincode"]').val());
						
						var org_ship_addrs	= $.trim($('textarea[name="org_bill_addrs"]').val());
						var ship_state		= $.trim($('select[name="bill_state"]').val());
						var ship_city		= $.trim($('select[name="bill_city"]').val());
						var ship_pincode	= $.trim($('input[name="bill_pincode"]').val());
					}
					var insert_req = 1;
					e.preventDefault();
					$('input[name="reg_submit_1"]').attr('disabled', 'true');
					var sendInfo = {"org_name":org_name, "org_description":org_description, "pri_email":pri_email, "sec_email":sec_email, "ter_email":ter_email, "pri_phone":pri_phone, "alt_phone":alt_phone, "org_fax":org_fax, "org_website":org_website, "org_indid":org_indid, "org_cst":org_cst, "org_vat":org_vat, "org_bill_addrs":org_bill_addrs, "bill_state":bill_state, "bill_city":bill_city, "bill_pincode":bill_pincode, "org_ship_addrs":org_ship_addrs, "ship_state":ship_state, "ship_city":ship_city, "ship_pincode":ship_pincode, "addrs_type":addrs_type, "insert_req":insert_req};
					var org_insert = JSON.stringify(sendInfo);
					$.ajax({
						url: "load_organisation.php?",
						type: "POST",
						data: org_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{	
								document.location.href='view_organisation.php?pag=Organization';
								loading_hide();
							} 
							else 
							{
								$("#req_resp").html('<span style="style="color:#F00;">'+data.resp+'</span>');	
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
			});
			
			function loadData1()
			{
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
						url: "load_organisation.php?",
						type: "POST",
						data: cat_load_error,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							
							if(data.Success == "Success") 
							{
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
							loading_hide();
							//alert("complete");
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
					alert("Please select checkbox to delete Organisation");				
				}
				else
				{
					//delete_catogery_error 	= 1;
					var sendInfo 	= {"batch":batch, "delete_org_error":1};
					var del_cat 	= JSON.stringify(sendInfo);	
					
					$.ajax({
						url: "load_organisation.php?",
						type: "POST",
						data: del_cat,
						contentType: "application/json; charset=utf-8",						
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
							loading_hide();
							//alert("complete");
						}
					});					
				}
			}
			
			$('#frm_org_excel').on('submit', function(e) 
			{
				e.preventDefault();
				if ($('#frm_org_excel').valid())
				{
					loading_show();	
					$.ajax({
							url: "load_organisation.php?",
							type: "POST",
							data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
							contentType: false,       // The content type used when sending data to the server.
							cache: false,             // To unable request pages to be cached
							processData:false,        // To send DOMDocument or non processed data file it is set to false
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									$("#req_resp").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									window.location.assign("view_organisation.php?pag=<?php echo $title; ?>");
									loading_hide();
								} 
	
								else 
								{
									$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');	
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
			});
			
			$('#frm_add_org').on('submit', function(e){
				e.preventDefault();
				if ($('#frm_add_org').valid())
				{
					loading_show();
					var org_name 				=  $.trim($("#org_name").val());
					var pri_email				=  $.trim($("#pri_email").val());
					var pri_phone				=  $.trim($("#pri_phone").val());
					var sec_email				=  $.trim($("#sec_email").val());
					var alt_phone				=  $.trim($("#alt_phone").val());
					var ter_email				=  $.trim($("#ter_email").val());
					var org_fax					=  $.trim($("#org_fax").val());
					var org_website				=  $.trim($("#org_website").val());
					var org_indid				=  $.trim($("#org_indid").val());
					var org_cst					=  $.trim($("#org_cst").val());
					var org_vat					=  $.trim($("#org_vat").val());
					var org_bill_addrs			= $.trim(CKEDITOR.instances['org_bill_addrs'].getData());
					var bill_city				= $("#bill_city").val();
					var bill_state				= $("#bill_state").val();
					var bill_pincode			= $("#bill_pincode").val();	
					var org_ship_addrs			= $.trim(CKEDITOR.instances['org_ship_addrs'].getData());
					var ship_city				= $("#ship_city").val();
					var ship_state				= $("#ship_state").val();
					var ship_pincode			= $("#ship_pincode").val();
					var org_desc 				= $.trim(CKEDITOR.instances['org_desc'].getData());
					var org_status 				= $('input[name=org_status]:checked', '#frm_add_org').val();
					var org_bank_ifsc_code		= $("#org_bank_ifsc_code").val();
					var org_bank_account_number	= $("#org_bank_account_number").val();
					var org_bank_address		= $("#org_bank_address").val();
					var org_bank_name			= $("#org_bank_name").val();
					var org_beneficiary_name	= $("#org_beneficiary_name").val();
					
					//if()
					{
					//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
					//$('#error_model').modal('toggle');	
					} 
				//else
					{
						e.preventDefault();
						$('input[name="reg_submit_add"]').attr('disabled', 'true');
						var sendInfo 	= {"org_beneficiary_name":org_beneficiary_name,"org_bank_name":org_bank_name,"org_bank_address":org_bank_address,"org_bank_account_number":org_bank_account_number,"org_bank_ifsc_code":org_bank_ifsc_code,"org_name":org_name, "pri_email":pri_email, "pri_phone":pri_phone, "sec_email":sec_email, "alt_phone":alt_phone, "ter_email":ter_email, "org_fax":org_fax, "org_website":org_website, "org_indid":org_indid, "org_cst":org_cst, "org_vat":org_vat, "org_bill_addrs":org_bill_addrs, "bill_city":bill_city, "bill_state":bill_state, "bill_pincode":bill_pincode, "org_ship_addrs":org_ship_addrs, "ship_city":ship_city, "ship_state":ship_state, "ship_pincode":ship_pincode, "org_desc":org_desc, "org_status":org_status,"insert_req_1":"1"};
						var org_insert 	= JSON.stringify(sendInfo);				
						$.ajax({
							url: "load_organisation.php",
							type: "POST",
							data: org_insert,
							contentType: "application/json; charset=utf-8",						
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									//$('#error_model').modal('toggle');
									alert("Organisation Added Successfully");
									window.location.assign("view_organisation.php?pag=<?php echo $title; ?>");									
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
								//alert("complete");
							}
						});
					}
				}	
			});	
			
			$('#frm_edit_org').on('submit', function(e){
				e.preventDefault();
				if ($('#frm_edit_org').valid())
				{
					loading_show();
					var org_id					=  $.trim($("#org_id").val());
					var org_name 				=  $.trim($("#org_name").val());
					var pri_email				=  $.trim($("#pri_email").val());
					var pri_phone				=  $.trim($("#pri_phone").val());
					var sec_email				=  $.trim($("#sec_email").val());
					var alt_phone				=  $.trim($("#alt_phone").val());
					var ter_email				=  $.trim($("#ter_email").val());
					var org_fax					=  $.trim($("#org_fax").val());
					var org_website				=  $.trim($("#org_website").val());
					var org_indid				=  $.trim($("#org_indid").val());
					var org_cst					=  $.trim($("#org_cst").val());
					var org_vat					=  $.trim($("#org_vat").val());
					var org_bill_addrs			= $.trim(CKEDITOR.instances['org_bill_addrs'].getData());
					var bill_city				= $("#bill_city").val();
					var bill_state				= $("#bill_state").val();
					var bill_pincode			= $("#bill_pincode").val();	
					var org_ship_addrs			= $.trim(CKEDITOR.instances['org_ship_addrs'].getData());
					var ship_city				= $("#ship_city").val();
					var ship_state				= $("#ship_state").val();
					var ship_pincode			= $("#ship_pincode").val();
					var org_desc 				= $.trim(CKEDITOR.instances['org_desc'].getData());
					var org_status 				= $('input[name=org_status]:checked', '#frm_edit_org').val();
					var org_bank_ifsc_code		= $("#org_bank_ifsc_code").val();
					var org_bank_account_number	= $("#org_bank_account_number").val();
					var org_bank_address		= $("#org_bank_address").val();
					var org_bank_name			= $("#org_bank_name").val();
					var org_beneficiary_name	= $("#org_beneficiary_name").val();									
					//alert(org_beneficiary_name);
					//if()
					{
					//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
					//$('#error_model').modal('toggle');	
					} 
				//else
					{
						e.preventDefault();
						$('input[name="reg_submit_edit"]').attr('disabled', 'true');
						var sendInfo 	= {"org_beneficiary_name":org_beneficiary_name,"org_bank_name":org_bank_name,"org_bank_address":org_bank_address,"org_bank_account_number":org_bank_account_number,"org_bank_ifsc_code":org_bank_ifsc_code,"org_name":org_name, "pri_email":pri_email, "pri_phone":pri_phone, "sec_email":sec_email, "alt_phone":alt_phone, "ter_email":ter_email, "org_fax":org_fax, "org_website":org_website, "org_indid":org_indid, "org_cst":org_cst, "org_vat":org_vat, "org_bill_addrs":org_bill_addrs, "bill_city":bill_city, "bill_state":bill_state, "bill_pincode":bill_pincode, "org_ship_addrs":org_ship_addrs, "ship_city":ship_city, "ship_state":ship_state, "ship_pincode":ship_pincode, "org_desc":org_desc, "org_status":org_status, "org_id":org_id, "update_req_1":"1"};
						var org_insert 	= JSON.stringify(sendInfo);				
						$.ajax({
							url: "load_organisation.php",
							type: "POST",
							data: org_insert,
							contentType: "application/json; charset=utf-8",						
							success: function(response) 
							{//alert(response);
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									//$('#error_model').modal('toggle');
									backToMainView();
									alert("Organisation Updated Successfully");							
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
								//alert("complete");
							}
						});
					}
				}	
			});
				
			$('#frm_error_org').on('submit', function(e){
				e.preventDefault();
				if ($('#frm_error_org').valid())
				{
					loading_show();
					var org_name 				=  $.trim($("#org_name").val());
					var pri_email				=  $.trim($("#pri_email").val());
					var pri_phone				=  $.trim($("#pri_phone").val());
					var sec_email				=  $.trim($("#sec_email").val());
					var alt_phone				=  $.trim($("#alt_phone").val());
					var ter_email				=  $.trim($("#ter_email").val());
					var org_fax					=  $.trim($("#org_fax").val());
					var org_website				=  $.trim($("#org_website").val());
					var org_indid				=  $.trim($("#org_indid").val());
					var org_cst					=  $.trim($("#org_cst").val());
					var org_vat					=  $.trim($("#org_vat").val());
					var org_bill_addrs			= $.trim(CKEDITOR.instances['org_bill_addrs'].getData());
					var bill_city				= $("#bill_city").val();
					var bill_state				= $("#bill_state").val();
					var bill_pincode			= $("#bill_pincode").val();	
					var org_ship_addrs			= $.trim(CKEDITOR.instances['org_ship_addrs'].getData());
					var ship_city				= $("#ship_city").val();
					var ship_state				= $("#ship_state").val();
					var ship_pincode			= $("#ship_pincode").val();
					var org_desc 				= $.trim(CKEDITOR.instances['org_desc'].getData());
					var org_status 				= $('input[name=org_status]:checked', '#frm_error_org').val();
					var error_id				= $.trim($("#error_id").val());
									
					var org_bank_ifsc_code		= $("#org_bank_ifsc_code").val();
					var org_bank_account_number	= $("#org_bank_account_number").val();
					var org_bank_address		= $("#org_bank_address").val();
					var org_bank_name			= $("#org_bank_name").val();
					var org_beneficiary_name	= $("#org_beneficiary_name").val();
					
					//if()
					{
					//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
					//$('#error_model').modal('toggle');	
					} 
				//else
					{
						e.preventDefault();
						$('input[name="reg_submit_error"]').attr('disabled', 'true');
						var sendInfo 	= {"org_beneficiary_name":org_beneficiary_name,"org_bank_name":org_bank_name,"org_bank_address":org_bank_address,"org_bank_account_number":org_bank_account_number,"org_bank_ifsc_code":org_bank_ifsc_code,"org_name":org_name, "pri_email":pri_email, "pri_phone":pri_phone, "sec_email":sec_email, "alt_phone":alt_phone, "ter_email":ter_email, "org_fax":org_fax, "org_website":org_website, "org_indid":org_indid, "org_cst":org_cst, "org_vat":org_vat, "org_bill_addrs":org_bill_addrs, "bill_city":bill_city, "bill_state":bill_state, "bill_pincode":bill_pincode, "org_ship_addrs":org_ship_addrs, "ship_city":ship_city, "ship_state":ship_state, "ship_pincode":ship_pincode, "org_desc":org_desc, "org_status":org_status, "error_id":error_id, "insert_req_1":"1"};
						var org_insert 	= JSON.stringify(sendInfo);				
						$.ajax({
							url: "load_organisation.php",
							type: "POST",
							data: org_insert,
							contentType: "application/json; charset=utf-8",						
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									//$('#error_model').modal('toggle');
									loading_hide();
									window.location.assign("view_organisation.php?pag=<?php echo $title; ?>");									
									
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
								//alert("complete");
							}
						});
					}
				}	
			});			
		</script>
        
        <!--done by monika-->
        <script type="text/javascript">
        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
        function IsAlphaNumeric(e) {
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
            document.getElementById("error").style.display = ret ? "none" : "inline";
            return ret;
        }
    </script>
    <!--done by monika-->
    </body>
</html>