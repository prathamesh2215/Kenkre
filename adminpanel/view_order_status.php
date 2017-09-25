<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title 				= "View Order Status";
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
								breadcrumbs($home_url,$home_name,'View Order Status',$filename,$feature_name); 
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
                                            <button type="button" class="btn-info" onClick="addMoreOrderStatus('','add')" ><i class="icon-plus"></i>&nbspAdd Order Status</button>
  											<?php		
										}
									?>                                      
                                    <div style="padding:10px 15px 10px 15px !important">
                                    	<input type="hidden" name="hid_page" id="hid_page" value="1">
                                        <select name="rowlimit" id="rowlimit" onChange="loadOrderStatusData();"  class = "select2-me">
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
                    </div> <!-- View Specification-->          
                <div class="container-fluid" id="div_add_spec" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Add Order Status',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>           
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Order Status
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
						breadcrumbs($home_url,$home_name,'Edit Order Status',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>                                    
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Order Status
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
                <div class="container-fluid" id="div_view_spec_details" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'View Order Status Details',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>        
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Order Status  Details
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
				var sendInfo 	= {"batch":batch, "delete_order_status":1};
				var del_spec 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_order_status.php",
					type: "POST",
					data: del_spec,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadOrderStatusData();
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
		function loadOrderStatusData()
		{
			loading_show();
			row_limit 	= $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page 		= $.trim($("#hid_page").val());
			load_order 	= "1";			
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');
				loading_hide();							
			}
			else
			{
				var sendInfo = {"row_limit":row_limit, "search_text":search_text, "load_order":load_order, "page":page};
				var spec_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_order_status.php",
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
					}
			    });
			}
		}
		function changeStatus(orstat_id,curr_status)
		{
			loading_show();
			if(orstat_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
				$('#error_model').modal('toggle');
				loading_hide();				
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"orstat_id":orstat_id, "curr_status":curr_status, "change_status":change_status};
				var spec_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_order_status.php",
					type: "POST",
					data: spec_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadOrderStatusData();
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
		
		function changeOrder(orstat_id,new_order)
		{
			loading_show();			
			if(orstat_id == "" && new_order == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				change_sort_order 	= 1;
				var sendInfo 	= {"orstat_id":orstat_id, "new_order":new_order, "change_sort_order":change_sort_order};
				var cat_order 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_order_status.php?",
					type: "POST",
					data: cat_order,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadOrderStatusData();
							loading_hide();
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
		
		
		
		function addMoreOrderStatus(orstat_id,req_type)
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

			else if(req_type == "view")
			{
				$('#div_view_spec_details').css("display", "block");				
			}							
			var sendInfo = {"orstat_id":orstat_id,"req_type":req_type,"load_order_parts":1};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_order_status.php?",
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
					}
				});
		}					
		$( document ).ready(function() {
			$('#srch').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page").val("1");				
       			   	loadOrderStatusData();
				}
			});
			loadOrderStatusData();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page").val(page);
				loadOrderStatusData();						
			});	

		}); 
		
		
		$('#frm_spec_add').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_spec_add').valid())
			{
				loading_show();	
				var orstat_name 		= $.trim($("#orstat_name").val());
				var orstat_status 	= $('input[name=orstat_status]:checked', '#frm_spec_add').val()			
				if(orstat_name == "" && orstat_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please fill details</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_add"]').attr('disabled', 'true');
					var sendInfo 	= {"orstat_name":orstat_name,"orstat_status":orstat_status,"insert_order":"1"};
					var spec_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_order_status.php",
						type: "POST",
						data: spec_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								window.location.assign("view_order_status.php?pag=<?php echo $title; ?>");
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
		});	/*Add spec*/	
		$('#frm_spec_edit').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_spec_edit').valid())
			{
				var orstat_id			= $.trim($('#orstat_id').val());
				var orstat_name 		= $.trim($('input[name="orstat_name"]').val());
				var orstat_status 	= $('input[name=orstat_status]:checked', '#frm_spec_edit').val()			
				if(parent == 1)
				{
					$("#req_resp").html('<span style="style="color:#F00;">Please select parent type</span>');
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit"]').attr('disabled', 'true');
					var sendInfo 		= {"orstat_id":orstat_id,"orstat_name":orstat_name,"orstat_status":orstat_status,"update_order_status":"1"};
					var spec_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_order_status.php?",
						type: "POST",
						data: spec_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{								
								window.location.assign("view_order_status.php?pag=<?php echo $title; ?>");
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
		});
        </script>
    </body>
</html>