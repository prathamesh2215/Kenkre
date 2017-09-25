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
                <div class="container-fluid" id="div_view_slider">                
				<?php 
                /* this function used to add navigation menu to the page*/ 
                breadcrumbs($home_url,$home_name,'View Slider',$filename,$feature_name); 
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
                               
                            </div><!-- header title-->
                            <div class="box-content nopadding">
                            <?php
                                $add = checkFunctionalityRight($filename,0);
                                if($add)
                                {
                                    ?>
                                    <button type="button" class="btn-info" onClick="addMoreSlider('','add')" ><i class="icon-plus"></i>&nbspAdd Slider</button>
                                    <?php		
                                }
                            ?>                                       
                            <div style="padding:10px 15px 10px 15px !important">
                                <input type="hidden" name="hid_slider_slider" id="hid_slider_slider" value="1">
                                <select name="row_limit_slider_error" id="row_limit_slider_error" onChange="loadSliderData();"  class = "select2-me">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries per page
                                <input type="text" class="input-medium" id = "search_text_slider" name="search_text_slider" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
                            </div>
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
									  Excel Bulk Upload For Slider
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
									  Wrong Entries For Slider
								  </h3>
								 
							  </div> <!-- header title-->
							  <div class="box-content nopadding">
								  <div style="padding:10px 15px 10px 15px !important">
									  <input type="hidden" name="hid_slider_slider_error" id="hid_slider_slider_error" value="1">
									  <select name="row_limit_slider_error" id="row_limit_slider_error" onChange="loadData1();"  class = "select2-me">
										  <option value="10" selected>10</option>
										  <option value="25">25</option>
										  <option value="50">50</option>
										  <option value="100">100</option>
									  </select> entries per page
									  <input type="text" class="input-medium" id = "search_text_slider_error" name="search_text_slider_error" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                </div>
				<div id="div_add_slider" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Slider',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Slider
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_slider_add" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data">
                                        <div id="div_add_slider_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Slider -->
				<div id="div_edit_slider" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Slider',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Slider
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_slider" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data">
                                        <div id="div_edit_slider_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Slider -->
				<div id="div_error_slider" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Update Error Slider',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Error Slider
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_error_slider" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data">
                                        <div id="div_error_slider_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- error Slider -->   
            	<div id="div_view_slider_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Slider Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Slider Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_slider_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_slider_details_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- view Slider -->                  
            </div>
        </div>
            <?php getloder();?>
        <?php ?>
        <script type="text/javascript">
		
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
				alert("Please select checkbox to delete catogery");				
			}
			else
			{
				var sendInfo 	= {"batch":batch, "delete_slider":1};
				var delete_slider 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_slider.php?",
					type: "POST",
					data: delete_slider,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadSliderData();
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
		}
		
		function loadSliderData()
		{
			loading_show();
			row_limit_slider_error 	= $.trim($('select[name="row_limit_slider_error"]').val());
			search_text_slider 		= $.trim($('#search_text_slider').val());
			page 					= $.trim($("#hid_slider_slider").val());
			
			load_slider = "1";			
			
			if(row_limit_slider_error == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 	= {"row_limit_slider_error":row_limit_slider_error, "search_text_slider":search_text_slider, "load_slider":load_slider, "page":page};
				var slider_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_slider.php?",
					type: "POST",
					data: slider_load,
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
							$("#container1").html(data.resp);
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
		
		function addMoreSlider(slider_id,req_type)
		{
			$('#div_view_slider').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_slider').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_slider').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_slider').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_slider_details').css("display", "block");				
			}							
			var sendInfo = {"slider_id":slider_id,"req_type":req_type,"load_slider_parts":1};
			var slider_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_slider.php?",
					type: "POST",
					data: slider_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							if(req_type == "add")
							{
								$("#div_add_slider_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_slider_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_slider_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_slider_details_part").html(data.resp);
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
		
		function changeStatus(slider_id,curr_status)
		{
			loading_show();
			if(slider_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"slider_id":slider_id, "curr_status":curr_status, "change_status":change_status};
				var slider_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_slider.php?",
					type: "POST",
					data: slider_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadSliderData();
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
		}	
				
		$( document ).ready(function() 
		{
			$('#search_text_slider').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_slider_slider").val("1");				
       			   	loadSliderData();
				}
			});
			$('#search_text_slider_error').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_slider_slider_error").val("1");					
       			   	loadSliderErrorData();	
				}
			});	
			loadSliderData();
			<?php
				$add = checkFunctionalityRight($filename,0);
				$edit = checkFunctionalityRight($filename,1);
				if($add || $edit)
				{
					?>
						loadSliderErrorData();
					<?php
				}
			?>
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_slider_slider").val(page);
				loadSliderData();						
			});
			$('#container3 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_slider_slider_error").val(page);
				loadSliderErrorData();						
			});
		}); 

		function loadSliderErrorData()
		{
			loading_show();
			row_limit_slider_error 	= $.trim($('select[name="row_limit_slider_error"]').val());
			search_text_slider_error 	= $.trim($('#search_text_slider_error').val());
			page1 							= $.trim($("#hid_slider_slider_error").val());			
			load_error 	= "1";			
			
			if(row_limit_slider_error == "" && page1 == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');
				$('#error_model').modal('toggle');				
			}
			else
			{
				var sendInfo_error 		= {"row_limit_slider_error":row_limit_slider_error, "search_text_slider_error":search_text_slider_error, "load_error":load_error, "page1":page1};
				var cat_load_error = JSON.stringify(sendInfo_error);				
				$.ajax({
					url: "load_slider.php?",
					type: "POST",
					data: cat_load_error,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						
						if(data.Success == "Success") 
						{
							$("#container3").html('<span style="style="color:#F00;">'+data.resp+'</span>');
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
				alert("Please select checkbox to delete Slider");				
			}
			else
			{
				//delete_catogery_error 	= 1;
				var sendInfo 	= {"batch":batch, "delete_slider_error":1};
				var del_cat 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_slider.php?",
					type: "POST",
					data: del_cat,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							loadSliderErrorData();
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
		
		$('#frm_slider_excel').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_slider_excel').valid())
			{
				loading_show();	
				$.ajax({
						url: "load_slider.php?",
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
								//$("#req_resp").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								window.location.assign("view_slider.php?pag=<?php echo $title; ?>");
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
			
		$('#frm_slider_add').on('submit', function(e) 
		{
			e.preventDefault();			
			if ($('#frm_slider_add').valid())
			{
				loading_show();	
				$('input[name="reg_submit_add"]').attr('disabled', 'true');				
				$.ajax({
					url: "load_slider.php?",
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
							window.location.assign("view_slider.php?pag=<?php echo $title; ?>");
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
		});	/*Add Slider*/
			
		$('#frm_edit_slider').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_slider').valid())
			{
				loading_show();	
				$('input[name="reg_submit_edit"]').attr('disabled', 'true');				
				$.ajax({
					url: "load_slider.php?",
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
							window.location.assign("view_slider.php?pag=<?php echo $title; ?>");
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
		}); /* edit Slider*/
		
		$('#frm_slider_error').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_slider_error').valid())
			{
				loading_show();	
				$('input[name="reg_submit_add"]').attr('disabled', 'true');				
				$.ajax({
					url: "load_slider.php?",
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
							window.location.assign("view_slider.php?pag=<?php echo $title; ?>");
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
		}); /* error add Slider*/
							
		// ******************************************************************************************
		// scripts for excel bulk upload [ for showing the error logs ] ends here 
		// ******************************************************************************************	 
		</script>
    </body>
</html>