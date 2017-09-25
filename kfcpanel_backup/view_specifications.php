<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Specification";
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
				breadcrumbs($home_url,$home_name,'View Specification',$filename,$feature_name); 
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
                                            <button type="button" class="btn-info" onClick="addMoreSpec('','add')" ><i class="icon-plus"></i>&nbspAdd Specifications</button>
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
                                            Excel Bulk Upload For Specification
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <div class="profileGallery">
                                            <div style="width:50%;" align="center">
                                                <div id="loading"></div>                                            
                                                <div id="container2">
                                                    <div class="data">
                                                        <form method="post" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data" id="frm_category_excel">
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
                                            Wrong Entries For Specification
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
                                <?php 	} ?>
                            </div>
                        </div>
                    </div> <!-- View Specification-->          
                <div class="container-fluid" id="div_add_spec" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Add Specifications',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>           
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Specifications
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                               
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">                                     
                                    	<form id="frm_spec_add" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_add_spec_part" class="data_container">
                                        	</div>                                    
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Add Specification -->
                <div class="container-fluid" id="div_edit_spec" style="display:none">   
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Edit Specifications',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>                                    
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Specifications
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                               
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<form id="frm_spec_edit" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_edit_spec_part" class="data_container">
                                        	</div>                                    
										</form>                                    
    	                            </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Edit Specification -->   
				<div class="container-fluid" id="div_error_spec" style="display:none">   
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Edit Error Specifications',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>                                    
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Error Specifications
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                               
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<form id="frm_spec_error" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_error_spec_part" class="data_container">
                                        	</div>                                    
										</form>                                    
    	                            </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Error Specification -->   
                <div class="container-fluid" id="div_view_spec_details" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'View Specifications Details',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>        
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Specification  Details
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<form id="frm_view_spec_details" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_view_spec_details_part" class="data_container">
                                        	</div>                                    
										</form>  
                                	</div>
                                </div>    
                            </div>
                        </div>
                    </div> <!-- View Details Specification -->                        
                </div>
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
				$("#model_body").html('<span style="style="color:#F00;">Select ChaeckBox to Delete</span>');	
				$('#error_model').modal('toggle');							
				loading_hide();					
			}
			else
			{
				var sendInfo 	= {"batch":batch, "delete_spec":1};
				var del_spec 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_specifications.php",
					type: "POST",
					data: del_spec,
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
		function loadData()
		{
			loading_show();
			row_limit 	= $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page 		= $.trim($("#hid_page").val());
			load_spec 	= "1";			
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');
				loading_hide();							
			}
			else
			{
				var sendInfo = {"row_limit":row_limit, "search_text":search_text, "load_spec":load_spec, "page":page};
				var spec_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_specifications.php",
					type: "POST",
					data: spec_load,
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
		function changeStatus(spec_id,curr_status)
		{
			loading_show();
			if(spec_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
				$('#error_model').modal('toggle');
				loading_hide();				
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"spec_id":spec_id, "curr_status":curr_status, "change_status":change_status};
				var spec_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_specifications.php",
					type: "POST",
					data: spec_status,
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
		function addMoreSpec(spec_id,req_type)
		{
			$('#div_view_spec').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_spec').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_spec').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_spec').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_spec_details').css("display", "block");				
			}							
			var sendInfo = {"spec_id":spec_id,"req_type":req_type,"load_spec_parts":1};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_specifications.php?",
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
								$("#div_add_spec_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_spec_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_spec_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_spec_details_part").html(data.resp);
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
		$( document ).ready(function() {
			$('#srch').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page").val("1");				
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
			if(($add) || ($edit))
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

		}); 
		
		// ******************************************************************************************
		// scripts for excel bulk upload [ for showing the error logs ] starts here 
		// ******************************************************************************************
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
					url: "load_specifications.php?",
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
							$("#container3").html('');
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
				alert("Please select checkbox to delete catogery");				
			}
			else
			{
				//delete_catogery_error 	= 1;
				var sendInfo 	= {"batch":batch, "delete_spec_error":1};
				var del_cat 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_specifications.php?",
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
		
		$('#frm_category_excel').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_category_excel').valid())
			{
				loading_show();	
				$.ajax({
						url: "load_specifications.php?",
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
								window.location.assign("view_specifications.php?pag=<?php echo $title; ?>");
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
		$('#frm_spec_add').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_spec_add').valid())
			{
				loading_show();	
				var spec_name 		= $.trim($("#spec_name").val());
				var spec_status 	= $('input[name=spec_status]:checked', '#frm_spec_add').val()			
				if(spec_name == "" && spec_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please fill details</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_add"]').attr('disabled', 'true');
					var sendInfo 	= {"spec_name":spec_name,"spec_status":spec_status,"insert_req":"1"};
					var spec_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_specifications.php",
						type: "POST",
						data: spec_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								window.location.assign("view_specifications.php?pag=<?php echo $title; ?>");
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
			}
		});	/*Add spec*/	
		$('#frm_spec_edit').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_spec_edit').valid())
			{
				var spec_id			= $.trim($('#spec_id').val());
				var spec_name 		= $.trim($('input[name="spec_name"]').val());
				var spec_status 	= $('input[name=spec_status]:checked', '#frm_spec_edit').val()			
				if(parent == 1)
				{
					$("#req_resp").html('<span style="style="color:#F00;">Please select parent type</span>');
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit"]').attr('disabled', 'true');
					var sendInfo 		= {"spec_id":spec_id,"spec_name":spec_name,"spec_status":spec_status,"update_req":"1"};
					var spec_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_specifications.php?",
						type: "POST",
						data: spec_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{								
								window.location.assign("view_specifications.php?pag=<?php echo $title; ?>");
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
			}
		}); /* edit spec*/
		$('#frm_spec_error').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_spec_error').valid())
			{
				loading_show();	
				var spec_name 		= $.trim($("#spec_name").val());
				var spec_status 	= $('input[name=spec_status]:checked', '#frm_spec_error').val();
				var error_id		= $("#error_id").val();			
				if(spec_name == "" && spec_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please fill details</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_error"]').attr('disabled', 'true');
					var sendInfo 	= {"error_id":error_id,"spec_name":spec_name,"spec_status":spec_status,"insert_req":"1"};
					var spec_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_specifications.php",
						type: "POST",
						data: spec_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								window.location.assign("view_specifications.php?pag=<?php echo $title; ?>");
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
			}
		}); /* error add spec*/					
		// ******************************************************************************************
		// scripts for excel bulk upload [ for showing the error logs ] ends here 
		// ******************************************************************************************
		</script>
    </body>
</html>