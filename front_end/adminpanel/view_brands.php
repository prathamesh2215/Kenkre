<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Brands";
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
                <div class="container-fluid" id="div_view_brand">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'View Brands',$filename,$feature_name); 
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
                                        <span id="error" style="color: Red; display: none">* Input digits (0 - 9)</span>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    <?php
										$add = checkFunctionalityRight($filename,0);
										if($add)
										{
											?>
                                            <button type="button" class="btn-info" onClick="DoMoreBrand('','add')" ><i class="icon-plus"></i>&nbspAdd Brand</button>
  											<?php		
										}
									?>                                       
                                    <div style="padding:10px 15px 10px 15px !important">                                  
                                    	<input type="hidden" name="hid_page_brands" id="hid_page_brands" value="1">
                                    	<input type="hidden" name="cat_parent" id="cat_parent" value="Parent">                                       
                                        <select name="rowlimit" id="rowlimit" onChange="loadBrandsdata();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "search_txt_brands" name="search_txt_brands" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                    </div>	<!-- View Brand -->
                <div class="container-fluid" id="div_add_brand" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Add Brands',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>           
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Brands
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                         
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">                                     
                                    	<form id="frm_add_brand" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_add_brand_part">
                                        	</div>                                    
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Add Brand -->
                <div class="container-fluid" id="div_edit_brand" style="display:none">   
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Edit Brands',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>                                    
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Brands
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                         
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<form id="frm_edit_brand" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_edit_brand_part">
                                        	</div>                                    
										</form>                                    
    	                            </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Edit Brand -->                    
                <div class="container-fluid" id="div_view_brand_details" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'View Brand Details',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>        
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Brand Details
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                         
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<form id="frm_view_brand_details" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_view_brand_details_part">
                                        	</div>                                    
										</form>  
                                	</div>
                                </div>    
                            </div>
                        </div>
                    </div> <!-- View Details Brand -->                    
			</div> 
		</div>
        <?php getloder();?>    
        <script type="text/javascript">		
		function multipleDeleteBrands()
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
				delete_brand 	= 1;
				var sendInfo 	= {"batch":batch, "delete_brand":delete_brand};
				var del_brand 	= JSON.stringify(sendInfo);						
				$.ajax({
					url: "load_brands.php",
					type: "POST",
					data: del_brand,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							loadBrandsdata();
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
		function loadBrandsdata()
		{
			loading_show();
			row_limit 	= $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#search_txt_brands').val());
			page 		= $.trim($("#hid_page_brands").val());
			load_brand 	= "1";			
			if(row_limit == "" && page == "")
			{
				$("#req_resp").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_brand":load_brand, "page":page};
				var brand_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_brands.php",
					type: "POST",
					data: brand_load,
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
						//alert("complete");
						loading_hide();
					}
			    });
			}
		}
		function changeBrandsStatus(brand_id,curr_status)
		{
			loading_show();
			if(brand_id == "" && curr_status == "")
			{
				$("#req_resp").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"brand_id":brand_id, "curr_status":curr_status, "change_status":change_status};
				var brand_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_brands.php",
					type: "POST",
					data: brand_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadBrandsdata();
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
						//alert("complete");
						loading_hide();
					}
			    });						
			}
		}	
		function changeBrandsSlug(brand_id)
		{
			loading_show();
			brand_slug	= $('textarea#'+brand_id).val();
			brand_id	= parseInt(brand_id);
			if(brand_id == "" && brand_slug == "")
			{
				$("#req_resp").html('<span style="style="color:#F00;"> Brand id or Slug to change not available</span>');
			}
			else
			{
				var sendInfo 		= {"brand_id":brand_id, "brand_slug":brand_slug, "change_brand_slug":1};
				var brand_slug_data = JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_brands.php",
					type: "POST",
					data: brand_slug_data,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadBrandsdata();
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
		function DoMoreBrand(brand_id,req_type)
		{
			$('#div_view_brand').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_brand').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_brand').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_brand').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_brand_details').css("display", "block");				
			}							
			var sendInfo = {"brand_id":brand_id,"req_type":req_type,"load_brand_parts":1};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_brands.php?",
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
								$("#div_add_brand_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_brand_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_brand_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_brand_details_part").html(data.resp);
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
						//alert("complete");
						loading_hide();
					}
				});
		}			
		$( document ).ready(function() 
		{
			$('#search_txt_brands').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page_brands").val("1");					
       			   	loadBrandsdata();					
				}
			});
			loadBrandsdata();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page_brands").val(page);
				loadBrandsdata();						
			});
		}); 	
		$('#frm_add_brand').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_add_brand').valid())
			{
				loading_show();
				for(instance in CKEDITOR.instances) 
				{
        			CKEDITOR.instances[instance].updateElement();
    			}				
				e.preventDefault();		
				$.ajax({
						url: "load_brands.php?",
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
								loading_hide();									
								window.location.assign("view_brands.php?pag=<?php echo $title; ?>");
							} 
							else 
							{
								loading_hide();
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');							
								$('#error_model').modal('toggle');								
							}
						},
						error: function (request, status, error) 
						{
							loading_hide();
							$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');							
							$('#error_model').modal('toggle');								
						},
						complete: function()
						{
                		}
				    });
			}
		});		
		$('#frm_edit_brand').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_brand').valid())
			{
				loading_show();
				for(instance in CKEDITOR.instances) 
				{
        			CKEDITOR.instances[instance].updateElement();
    			}	
				e.preventDefault();
				$.ajax({
					url: "load_brands.php?",
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
							loading_hide();								
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');							
							$('#error_model').modal('toggle');							
							window.location.assign("view_brands.php?pag=<?php echo $title; ?>");
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
		</script>
    </body>
</html>