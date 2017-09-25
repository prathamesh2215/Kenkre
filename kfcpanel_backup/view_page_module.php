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
                <div class="container-fluid" id="div_view_page_module">                
				<?php 
                /* this function used to add navigation menu to the page*/ 
                breadcrumbs($home_url,$home_name,'View Page Module',$filename,$feature_name); 
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
                                    <button type="button" class="btn-info" onClick="addMorePageModule('','add')" ><i class="icon-plus"></i>&nbspAdd Page Module</button>
                                    <?php		
                                }
                            ?>                                       
                            <div style="padding:10px 15px 10px 15px !important">
                                <input type="hidden" name="hid_page_page_module" id="hid_page_page_module" value="1">
                                <input type="hidden" name="page_module_parent" id="page_module_parent" value="Parent">
                                <select name="rowlimit_page_module" id="rowlimit_page_module" onChange="loadPageModuleData();"  class = "select2-me">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries per page
                                <input type="text" class="input-medium" id = "search_text_page_module" name="search_text_page_module" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
					</div>
				</div>                     
                </div>
				<div id="div_add_page_module" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Page Module',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Page Module
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_page_module_add" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_page_module_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Page Module -->
				<div id="div_edit_page_module" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Page Module',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Page Module
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_page_module" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_page_module_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Page Module -->
            	<div id="div_view_page_module_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Page Module Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Page Module Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_page_module_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_page_module_details_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- view Page Module -->                  
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
				var sendInfo 	= {"batch":batch, "delete_page_module":1};
				var delete_page_module 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_page_module.php?",
					type: "POST",
					data: delete_page_module,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadPageModuleData();
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
		
		function loadPageModuleData()
		{
			loading_show();
			rowlimit_page_module 	= $.trim($('select[name="rowlimit_page_module"]').val());
			search_text_page_module = $.trim($('#search_text_page_module').val());
			page 					= $.trim($("#hid_page_page_module").val());
			
			load_page_module = "1";			
			
			if(rowlimit_page_module == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 	= {"rowlimit_page_module":rowlimit_page_module, "search_text_page_module":search_text_page_module, "load_page_module":load_page_module, "page":page};
				var page_module_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_page_module.php?",
					type: "POST",
					data: page_module_load,
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
		
		function addMorePageModule(page_id,req_type)
		{
			$('#div_view_page_module').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_page_module').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_page_module').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_page_module').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_page_module_details').css("display", "block");				
			}							
			var sendInfo = {"page_id":page_id,"req_type":req_type,"load_page_module_parts":1};
			var page_module_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_page_module.php?",
					type: "POST",
					data: page_module_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							if(req_type == "add")
							{
								$("#div_add_page_module_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_page_module_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_page_module_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_page_module_details_part").html(data.resp);
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
		
		function changeStatus(page_id,curr_status)
		{
			loading_show();
			if(page_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"page_id":page_id, "curr_status":curr_status, "change_status":change_status};
				var page_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_page_module.php?",
					type: "POST",
					data: page_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadPageModuleData();
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
			$('#search_text_page_module').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page_page_module").val("1");				
       			   	loadPageModuleData();
				}
			});
			$('#search_text_page_module_error').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page_page_module_error").val("1");					
       			   	loadPageModuleErrorData();	
				}
			});	
			loadPageModuleData();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page_page_module").val(page);
				loadPageModuleData();						
			});
		}); 

		$('#frm_page_module_add').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_page_module_add').valid())
			{
				loading_show();	
				var page_name 			= $.trim($("#page_name").val());
				var page_content		= $.trim(CKEDITOR.instances['page_content'].getData());				
				var page_meta_title 	= $.trim($("#page_meta_title").val());
				var page_meta_description= $.trim(CKEDITOR.instances['page_meta_description'].getData());				
				var page_meta_tags 		= $.trim($("#page_meta_tags").val());								
				var page_status 		= $('input[name=page_status]:checked', '#frm_page_module_add').val()			
				if(page_name == "" && page_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Please fill details</span>');
					$('#error_model').modal('toggle');
					loading_hide();					
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_add"]').attr('disabled', 'true');
					var sendInfo 	= {"page_name":page_name,"page_content":page_content,"page_meta_title":page_meta_title,"page_meta_description":page_meta_description,"page_meta_tags":page_meta_tags,"page_status":page_status,"insert_req":"1"};
					var page_module_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_page_module.php",
						type: "POST",
						data: page_module_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								window.location.assign("view_page_module.php?pag=<?php echo $title; ?>");
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
		});	/*Add Page Module*/
			
		$('#frm_edit_page_module').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_page_module').valid())
			{
				var page_id 			= $.trim($("#page_id").val());				
				var page_name 			= $.trim($("#page_name").val());
				var page_content		= $.trim(CKEDITOR.instances['page_content'].getData());				
				var page_meta_title 	= $.trim($("#page_meta_title").val());
				var page_meta_description= $.trim(CKEDITOR.instances['page_meta_description'].getData());				
				var page_meta_tags 		= $.trim($("#page_meta_tags").val());								
				var page_status 		= $('input[name=page_status]:checked', '#frm_edit_page_module').val()			
				if(parent == 1)
				{
					$("#req_resp").html('<span style="style="color:#F00;">Please select parent type</span>');
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit"]').attr('disabled', 'true');
					var sendInfo 	= {"page_id":page_id,"page_name":page_name,"page_content":page_content,"page_meta_title":page_meta_title,"page_meta_description":page_meta_description,"page_meta_tags":page_meta_tags,"page_status":page_status,"update_req":"1"};
					var page_module_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_page_module.php?",
						type: "POST",
						data: page_module_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{								
								window.location.assign("view_page_module.php?pag=<?php echo $title; ?>");
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
		}); /* edit Page Module*/
		
		</script>
    </body>
</html>