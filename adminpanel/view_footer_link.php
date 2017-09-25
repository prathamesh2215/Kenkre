<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Footer Link";
$path_parts   		= pathinfo(__FILE__);
$filename 	  		= $path_parts['filename'].".php";
$sql_feature 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature 	= mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  		= mysqli_fetch_row($result_feature);
$feature_name 		= $row_feature[1];
$home_name    		= "Home";
$home_url 	  	= "view_dashboard.php?pag=Dashboard";
$utype			= $_SESSION['panel_user']['utype'];
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
                <div class="container-fluid" id="div_view_flink">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'View Footer Link',$filename,$feature_name); 
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
                                          <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>   
                                        <span id="error" style="color: Red; display: none">* Input digits (0 - 9)</span>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    <?php
									  $add = checkFunctionalityRight($filename,0);//comment by Tariq-9/09/2016
//                                                                                $add =1;
										if($add)
										{
											?>
                                            <button type="button" class="btn-info" onClick="DoMoreFlink('','add')" ><i class="icon-plus"></i>&nbspAdd Link</button>
  											<?php		
										}
									?>                                       
                                    <div style="padding:10px 15px 10px 15px !important">                                  
                                    	<input type="hidden" name="hid_page_flinks" id="hid_page_flinks" value="1">
                                    	<input type="hidden" name="flink_parent" id="flink_parent" value="parent">                                       
                                        <select name="rowlimit" id="rowlimit" onChange="loadFlinkssdata();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id ="search_txt_flinks" name="search_txt_flinks" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
						breadcrumbs($home_url,$home_name,'Add Footer Link',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>           
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Footer Link
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                         
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">                                     
                                    	<form id="frm_add_flink" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_add_flink_part">
                                        	</div>                                    
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Add Brand -->
                <div class="container-fluid" id="div_edit_flink" style="display:none">   
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Edit Footer Link',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>                                    
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Footer Link
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                         
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<form id="frm_view_flink" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_edit_flink_part">
                                        	</div>                                    
					</form>                                    
    	                            </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Edit Brand -->                    
                <div class="container-fluid" id="div_view_flink_details" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'View Footer Link Details',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>        
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                           Footer Link Details
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                         
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<form id="frm_view_brand_details" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_view_flink_details_part">
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
            
            	function viewChild(flink_parent)
		{
			$.trim($('#srch').val(""));			
			$.trim($('#flink_parent').val(flink_parent));	
                       
			if(flink_parent == "parent")		
			{  
			}
			else
			{
				$("#hid_page_flinks").val(1);				
			}
			loadFlinkssdata();
		}
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
				delete_flink 	= 1;
				var sendInfo 	= {"batch":batch, "delete_flink":delete_flink};
				var del_brand 	= JSON.stringify(sendInfo);						
				$.ajax({
					url: "load_footer_link.php",
					type: "POST",
					data: del_brand,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							loadFlinkssdata();
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
		function loadFlinkssdata()
		{
			loading_show();
			row_limit 	= $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#search_txt_flinks').val());
			page 		= $.trim($("#hid_page_flinks").val());
                        flink_parent	= $.trim($('#flink_parent').val());
			load_flink 	= "1";			
			if(row_limit == "" && page == "")
			{
				$("#req_resp").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo = {"row_limit":row_limit, "search_text":search_text, "load_flink":load_flink, "page":page,'flink_parent':flink_parent};
				var flink_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_footer_link.php",
					type: "POST",
					data: flink_load,
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
		function changeFlinksStatus(flink_id,curr_status)
		{
			loading_show();
			if(flink_id == "" && curr_status == "")
			{
				$("#req_resp").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"flink_id":flink_id, "curr_status":curr_status, "change_status":change_status};
				var brand_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_footer_link.php",
					type: "POST",
					data: brand_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadFlinkssdata();
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
					url: "load_footer_link.php",
					type: "POST",
					data: brand_slug_data,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadFlinkssdata();
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
		function DoMoreFlink(flink_id,req_type)
		{  
			$('#div_view_flink').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_brand').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_flink').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_brand').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_flink_details').css("display", "block");				
			}							
			var sendInfo = {"flink_id":flink_id,"req_type":req_type,"load_flink_parts":1};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_footer_link.php?",
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
								$("#div_add_flink_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
                                                           
								$("#div_edit_flink_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_brand_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_flink_details_part").html(data.resp);
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
			$('#search_txt_flinks').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page_flinks").val("1");					
       			   	loadFlinkssdata();					
				}
			});
			loadFlinkssdata();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page_flinks").val(page);
				loadFlinkssdata();						
			});
		}); 	
		$('#frm_add_flink').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_add_flink').valid())
			{
				loading_show();
								
				e.preventDefault();		
				$.ajax({
						url: "load_footer_link.php?",
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
								window.location.assign("view_footer_link.php?pag=<?php echo $title; ?>");
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
		$('#frm_view_flink').on('submit', function(e) 
		{
                    
			e.preventDefault();
			if ($('#frm_view_flink').valid())
			{
				loading_show();
				e.preventDefault();
                                
                                $.ajax({
					url: "load_footer_link.php?",
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
//							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');							
//							$('#error_model').modal('toggle');							
							window.location.assign("view_footer_link.php?pag=<?php echo $title; ?>");
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