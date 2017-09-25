<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Redirection";
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
                <div class="container-fluid" id="div_view_indus">                
					<?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'View Redirection',$filename,$feature_name); 
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
					$add = 1;
					if($add)
					{
					?>
                                            <button type="button" class="btn-info" onClick="addMoreRedirection('','add')" ><i class="icon-plus"></i>&nbspAdd Redirection</button>
  					<?php		
					}
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
                                        <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Redirection Id, Source Url, Destination Url can be Search..."  style="float:right;margin-right:10px;margin-top:10px;width:400px" >
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
                    </div>  <!-- view Redirection -->
                <div class="container-fluid" id="div_add_indus" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Add Redirection',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>           
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Redirection
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">                                     
                                    	<form id="frm_red_add" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_add_indus_part">
                                        	</div>                                    
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
             	</div> <!-- Add Redirection -->
                <div class="container-fluid" id="div_edit_indus" style="display:none">   
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Redirection',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>                                    
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Redirection
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <form id="frm_red_edit" class="form-horizontal form-bordered form-validate" >
                                            <div id="div_edit_indus_part">
                                            </div>                                    
                                        </form>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Edit Redirection -->   
                <div class="container-fluid" id="div_error_indus" style="display:none">   
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Error Redirection',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>                                    
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Error Redirection
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <form id="frm_indus_error" class="form-horizontal form-bordered form-validate" >
                                            <div id="div_error_indus_part">
                                            </div>                                    
                                        </form>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Error Redirection -->   
                <div class="container-fluid" id="div_view_indus_details" style="display:none">                
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'View Redirection Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>        
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Redirection Details
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <form id="frm_view_indus_details" class="form-horizontal form-bordered form-validate" >
                                            <div id="div_view_indus_details_part">
                                            </div>                                    
                                        </form>  
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div> <!-- View Details Redirection -->                      
                </div>
            </div>
        </div>
            <?php getloder();?>
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
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to delete catogery</span>');
				$('#error_model').modal('toggle');
				loading_hide();						
			}
			else
			{
				delete_category 	= 1;
				var sendInfo 	= {"batch":batch, "delete_red":1};
				var del_cat 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_redirection.php?",
					type: "POST",
					data: del_cat,
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
			row_limit = $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page = $.trim($("#hid_page").val());								
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');
				loading_hide();							
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_ind":1, "page":page};
				var ind_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_redirection.php?",
					type: "POST",
					data: ind_load,
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
		function addMoreRedirection(ru_id,req_type)
		{
			$('#div_view_indus').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_indus').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_indus').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_indus').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_indus_details').css("display", "block");				
			}							
			var sendInfo = {"ru_id":ru_id,"req_type":req_type,"load_ind_parts":1};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_redirection.php?",
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
								$("#div_add_indus_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_indus_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_indus_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_indus_details_part").html(data.resp);
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
		function changeStatus(ru_id,curr_status)
		{
			loading_show();
			if(ru_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">User id or Status to change not available</span>');
				$('#error_model').modal('toggle');				
				loading_hide();
			}
			else
			{
				var sendInfo 	= {"ru_id":ru_id, "curr_status":curr_status, "change_status":1};
				var ru_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_redirection.php?",
					type: "POST",
					data: ru_status,
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
					}
			    });						
			}
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
			if($add || $edit)
			{
			?>	
			//loadData1();
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
					url: "load_redirection.php?",
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
				alert("Please select checkbox to delete Redirection");				
			}
			else
			{
				//delete_catogery_error 	= 1;
				var sendInfo 	= {"batch":batch, "delete_ind_error":1};
				var del_cat 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_redirection.php?",
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
		
		
		$('#frm_red_add').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_red_add').valid())
			{
				loading_show();	
				var ru_source 		= $.trim($('input[name="ru_source"]').val());
				var ru_destination 		= $.trim($('input[name="ru_destination"]').val());
				var ru_status 		= $('input[name=ru_status]:checked', '#frm_red_add').val()			
				if(ru_source == "" || ru_destination == "" || ru_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please Fill Details.</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				}
				else
				{
					e.preventDefault();				
					$('input[name="reg_submit_add"]').attr('disabled', 'true');
					var sendInfo 		= {"ru_source":ru_source, "ru_destination":ru_destination, "ru_status":ru_status,"insert_req":"1"};
					var red_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_redirection.php?",
						type: "POST",
						data: red_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								alert("Record inserted successfully!!!");
								window.location.assign("view_redirection.php?pag=<?php echo $title; ?>");
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
		});	/* add Redirection*/
		$('#frm_red_edit').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_red_edit').valid())
			{
				loading_show();	
				var ru_id			= $.trim($('#ru_id').val());
				var ru_source 		= $.trim($('input[name="ru_source"]').val());
				var ru_destination 		= $.trim($('input[name="ru_destination"]').val());
				var ru_status 		= $('input[name=ru_status]:checked', '#frm_red_edit').val()			
				if(ru_id == "" || ru_source == "" || ru_destination == "" || ru_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please fill Details.</span>');
					$('#error_model').modal('toggle');
					loading_hide();
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_edit"]').attr('disabled', 'true');
					var sendInfo 		= {"ru_id":ru_id, "ru_source":ru_source, "ru_destination":ru_destination, "ru_status":ru_status,"update_req":"1"};
					var cat_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_redirection.php?",
						type: "POST",
						data: cat_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							//alert(response);
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{			
								alert("Record updated successfully!!!");				
								window.location.assign("view_redirection.php?pag=<?php echo $title; ?>");
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
		}); /* edit_Redirection*/		
		$('#frm_indus_error').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_indus_error').valid())
			{
				loading_show();	
				var ru_source 		= $.trim($('input[name="ru_source"]').val());
				var ru_status 		= $('input[name=ru_status]:checked', '#frm_indus_error').val()	
				var error_id		= $("#error_id").val();		
				if(ru_source == "" || ru_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please Fill Details.</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				}
				else
				{
					e.preventDefault();				
					$('input[name="reg_submit_error"]').attr('disabled', 'true');
					var sendInfo 	= {"error_id":error_id,"ru_source":ru_source,"ru_status":ru_status,"insert_req":"1"};
					var red_insert 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_redirection.php?",
						type: "POST",
						data: red_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								window.location.assign("view_redirection.php?pag=<?php echo $title; ?>");
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
		});	/* error Redirection*/		
		// ******************************************************************************************
		// scripts for excel bulk upload [ for showing the error logs ] ends here 
		// ******************************************************************************************
		</script>     
		<!-----------------------validation for source and destination---------------------------------->
    </body>
</html>
