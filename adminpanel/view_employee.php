<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Employee";
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
	branch($db_con);
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
                <div class="container-fluid" id="div_view_emp">                
	<?php 
	/* this function used to add navigation menu to the page*/ 
	breadcrumbs($home_url,$home_name,'View Employee',$filename,$feature_name); 
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
                                            <button type="button" class="btn-info" onClick="addMoreEmployee('','add')" ><i class="icon-plus"></i>&nbspAdd Employee</button>
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
                                            Excel Bulk Upload For Employee
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <div class="profileGallery">
                                            <div style="width:50%;" align="center">
                                                <div id="loading"></div>                                            
                                                <div id="container2">
                                                    <div class="data">
                                                        <form method="post" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data" id="frm_emp_excel">
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
                                            Wrong Entries For Employee
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
                                            <input type="text" class="input-medium" id="srch1" name="srch1" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                                <?php 	}	?>
                            </div>
                        </div>
                    </div> <!-- View Employee -->
				<div id="div_add_emp" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Employee',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Employee
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_emp" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_emp_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Employee -->
				<div id="div_edit_emp" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Employee',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Employee
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_emp" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_emp_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Employee -->
				<div id="div_error_emp" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Update Error Employee',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Error Employee
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_error_emp" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_error_emp_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- error Employee -->   
            	<div id="div_view_emp_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Employee Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Employee Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_emp_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_emp_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- view Employee -->                     
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
				alert("Please select checkbox to delete Employee");				
			}
			else
			{
				var sendInfo 	= {"batch":batch, "delete_emp":1};
				var del_emp 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_employee.php",
					type: "POST",
					data: del_emp,
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
				//$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_emp":1, "page":page};
				var branch_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_employee.php?",
					type: "POST",
					data: branch_load,
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
		function changeStatus(emp_id,curr_status)
		{
			loading_show();
			if(emp_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				var sendInfo 	= {"emp_id":emp_id, "curr_status":curr_status, "change_status":1};
				var branch_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_employee.php?",
					type: "POST",
					data: branch_status,
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
		function addMoreEmployee(emp_id,req_type)
		{
			$('#div_view_emp').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_emp').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_emp').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_emp').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_emp_details').css("display", "block");				
			}							
			var sendInfo = {"emp_id":emp_id,"req_type":req_type,"load_add_emp_part":"1"};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_employee.php?",
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
								$("#div_add_emp_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_emp_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{								
								$("#div_error_emp_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_emp_part").html(data.resp);
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
		function stopExecution()
		{
			loading_show();
	  		setTimeout(continueExecution, 3000) //wait ten seconds before continuing
		}
		function continueExecution()
		{
			perm_city = $("#perm_city").val();
			$("#corrs_city").val(perm_city);
			$("#corrs_city").prop("disabled",true); // disable city select
			$("#corrs_city").select2();
			/* City select change*/								
			$("#corrs_pincode").prop("disabled",true); // disable pincode
			$("#corrs_pincode").val($("#perm_pincode").val());				
			loading_hide();			
		}			
		$( document ).ready(function() 
		{
			$('#srch').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page").val(1);
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
		});
		$(document).change(function () 
		{
			if($("#address_check").prop('checked') == true)
			{
				perm_details_add = $.trim(CKEDITOR.instances['perm_details_add'].getData());
				CKEDITOR.instances['corrs_details_add'].setData(perm_details_add);
				CKEDITOR.instances['corrs_details_add'].setReadOnly(true);	// disable ckeditor 
				/* state select change*/
				perm_state = $("#perm_state").val();
				$("#corrs_state").val(perm_state);
				$("#corrs_state").prop("disabled",true); // disable  state select
				$("#corrs_state").select2(); 				
				/* state select change*/
				/* City select change*/				
				getCity(perm_state,'corrs_city');
				stopExecution();
			}
			else if($("#address_check").prop('checked') == false)
			{
				CKEDITOR.instances['corrs_details_add'].setReadOnly(false);	// enable ckeditor 				
				$("#corrs_state").prop( "disabled", false );	// enable  state select			
				$("#corrs_state").select2();
				$("#corrs_city").prop("disabled",false);
				$("#corrs_city").select2();				
				$("#corrs_pincode").prop("disabled",false);
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
					url: "load_employee.php?",
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
				alert("Please select checkbox to delete Employee");				
			}
			else
			{
				//delete_empogery_error 	= 1;
				var sendInfo 	= {"batch":batch, "delete_emp_error":1};
				var del_emp 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_employee.php?",
					type: "POST",
					data: del_emp,
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
		$('#frm_emp_excel').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_emp_excel').valid())
			{
				loading_show();	
				$.ajax({
						url: "load_employee.php?",
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
								window.location.assign("view_employee.php?pag=<?php echo $title; ?>");
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
		$('#frm_add_emp').on('submit', function(e) {
		e.preventDefault();
		if ($('#frm_add_emp').valid())
		{
				loading_show();	
				var emp_name 				= $.trim($("#emp_name").val());
				var emp_orgid 				= $.trim($("#emp_orgid").val());				
				var emp_branchid 			= $.trim($("#emp_branchid").val());				
				var emp_desg 				= $.trim($("#emp_desg").val());
				var emp_office_email		= $.trim($("#emp_office_email").val());
				var emp_primary_email		= $.trim($("#emp_primary_email").val());												
				var emp_secondary_email 	= $.trim($("#emp_secondary_email").val());
				var emp_landline			= $.trim($("#emp_landline").val());
				var emp_primary_mobile		= $.trim($("#emp_primary_mobile").val());
				var emp_secondary_mobile	= $.trim($("#emp_secondary_mobile").val());					
				var emp_status 				= $('input[name=emp_status]:checked', '#frm_add_emp').val();				
				
				var perm_details_add		= $.trim(CKEDITOR.instances['perm_details_add'].getData());
				var perm_city				= $("#perm_city").val();
				var perm_state				= $("#perm_state").val();
				var perm_pincode			= $("#perm_pincode").val();	
											
				var corrs_details_add		= $.trim(CKEDITOR.instances['corrs_details_add'].getData());
				var corrs_city				= $("#corrs_city").val();
				var corrs_state				= $("#corrs_state").val();
				var corrs_pincode			= $("#corrs_pincode").val();								
															
				//if()
				{
					//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
					//$('#error_model').modal('toggle');	
				} 
				//else
				{
					e.preventDefault();
					$('input[name="reg_submit_1"]').attr('disabled', 'true');
					var sendInfo 	= {"emp_name":emp_name,"emp_orgid":emp_orgid,"emp_branchid":emp_branchid,"emp_desg":emp_desg,"emp_office_email":emp_office_email,"emp_primary_email":emp_primary_email,"emp_secondary_email":emp_secondary_email,"emp_landline":emp_landline,"emp_primary_mobile":emp_primary_mobile,"emp_secondary_mobile":emp_secondary_mobile,"emp_status":emp_status,"perm_details_add":perm_details_add,"perm_city":perm_city,"perm_state":perm_state,"perm_pincode":perm_pincode,"corrs_details_add":corrs_details_add,"corrs_city":corrs_city,"corrs_state":corrs_state,"corrs_pincode":corrs_pincode,"insert_req":1};
					var emp_insert 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_employee.php",
						type: "POST",
						data: emp_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');									
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
		$('#frm_edit_emp').on('submit', function(e) {
		e.preventDefault();
		if ($('#frm_edit_emp').valid())
		{
				loading_show();	
				var emp_id 					= $.trim($("#emp_id").val());				
				var emp_name 				= $.trim($("#emp_name").val());
				var emp_orgid 				= $.trim($("#emp_orgid").val());				
				var emp_branchid 			= $.trim($("#emp_branchid").val());				
				var emp_desg 				= $.trim($("#emp_desg").val());
				var emp_office_email		= $.trim($("#emp_office_email").val());
				var emp_primary_email		= $.trim($("#emp_primary_email").val());												
				var emp_secondary_email 	= $.trim($("#emp_secondary_email").val());
				var emp_landline			= $.trim($("#emp_landline").val());
				var emp_primary_mobile		= $.trim($("#emp_primary_mobile").val());
				var emp_secondary_mobile	= $.trim($("#emp_secondary_mobile").val());					
				var emp_status 				= $('input[name=emp_status]:checked', '#frm_edit_emp').val();				
				
				var perm_details_add		= $.trim(CKEDITOR.instances['perm_details_add'].getData());
				var perm_city				= $("#perm_city").val();
				var perm_state				= $("#perm_state").val();
				var perm_pincode			= $("#perm_pincode").val();	
											
				var corrs_details_add		= $.trim(CKEDITOR.instances['corrs_details_add'].getData());
				var corrs_city				= $("#corrs_city").val();
				var corrs_state				= $("#corrs_state").val();
				var corrs_pincode			= $("#corrs_pincode").val();								
															
				//if()
				{
					//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
					//$('#error_model').modal('toggle');	
				} 
				//else
				{
					e.preventDefault();
					$('input[name="reg_submit_1"]').attr('disabled', 'true');
					var sendInfo 	= {"emp_id":emp_id,"emp_name":emp_name,"emp_orgid":emp_orgid,"emp_branchid":emp_branchid,"emp_desg":emp_desg,"emp_office_email":emp_office_email,"emp_primary_email":emp_primary_email,"emp_secondary_email":emp_secondary_email,"emp_landline":emp_landline,"emp_primary_mobile":emp_primary_mobile,"emp_secondary_mobile":emp_secondary_mobile,"emp_status":emp_status,"perm_details_add":perm_details_add,"perm_city":perm_city,"perm_state":perm_state,"perm_pincode":perm_pincode,"corrs_details_add":corrs_details_add,"corrs_city":corrs_city,"corrs_state":corrs_state,"corrs_pincode":corrs_pincode,"update_req":1};
					var emp_insert 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_employee.php",
						type: "POST",
						data: emp_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');									
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
		$('#frm_error_emp').on('submit', function(e) {
		e.preventDefault();
		if ($('#frm_error_emp').valid())
		{
				loading_show();	
				var emp_name 				= $.trim($("#emp_name").val());
				var emp_orgid 				= $.trim($("#emp_orgid").val());				
				var emp_branchid 			= $.trim($("#emp_branchid").val());				
				var emp_desg 				= $.trim($("#emp_desg").val());
				var emp_office_email		= $.trim($("#emp_office_email").val());
				var emp_primary_email		= $.trim($("#emp_primary_email").val());												
				var emp_secondary_email 	= $.trim($("#emp_secondary_email").val());
				var emp_landline			= $.trim($("#emp_landline").val());
				var emp_primary_mobile		= $.trim($("#emp_primary_mobile").val());
				var emp_secondary_mobile	= $.trim($("#emp_secondary_mobile").val());					
				var emp_status 				= $.trim($('input[name=emp_status]:checked', '#frm_error_emp').val());
				
				var perm_details_add		= $.trim(CKEDITOR.instances['perm_details_add'].getData());
				var perm_city				= $.trim($("#perm_city").val());
				var perm_state				= $.trim($("#perm_state").val());
				var perm_pincode			= $.trim($("#perm_pincode").val());
											
				var corrs_details_add		= $.trim(CKEDITOR.instances['corrs_details_add'].getData());
				var corrs_city				= $.trim($("#corrs_city").val());
				var corrs_state				= $.trim($("#corrs_state").val());
				var corrs_pincode			= $.trim($("#corrs_pincode").val());
								
				var error_id				= $.trim($("#error_id").val());
															
				if(error_id == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
					$('#error_model').modal('toggle');	
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_1"]').attr('disabled', 'true');
					var sendInfo 	= {"error_id":error_id,"emp_name":emp_name,"emp_orgid":emp_orgid,"emp_branchid":emp_branchid,"emp_desg":emp_desg,"emp_office_email":emp_office_email,"emp_primary_email":emp_primary_email,"emp_secondary_email":emp_secondary_email,"emp_landline":emp_landline,"emp_primary_mobile":emp_primary_mobile,"emp_secondary_mobile":emp_secondary_mobile,"emp_status":emp_status,"perm_details_add":perm_details_add,"perm_city":perm_city,"perm_state":perm_state,"perm_pincode":perm_pincode,"corrs_details_add":corrs_details_add,"corrs_city":corrs_city,"corrs_state":corrs_state,"corrs_pincode":corrs_pincode,"insert_req":"1"};
					var emp_insert 	= JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_employee.php",
						type: "POST",
						data: emp_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');									
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
		</script>
    </body>
</html>