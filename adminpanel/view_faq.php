<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Category";
$path_parts   		= pathinfo(__FILE__);
$filename 	  		= $path_parts['filename'].".php";
$sql_feature 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature 	= mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  		= mysqli_fetch_row($result_feature);
$feature_name 		= $row_feature[1];
$home_name    		= "Home";
$home_url 	  		= "view_faq.php?pag=Dashboard";
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
        	<div class="container-fluid" id="div_view_cat">                
				<?php 
                /* this function used to add navigation menu to the page*/ 
                breadcrumbs($home_url,$home_name,'View FAQs',$filename,$feature_name); 
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
                                    <button type="button" class="btn-info" onClick="addMoreFaq('','add')" ><i class="icon-plus"></i>&nbspAdd FAQ</button>
                                    <?php		
                                }
                            ?>
                            <div style="padding:10px 15px 10px 15px !important">
                                <input type="hidden" name="hid_page" id="hid_page" value="1">
                                <input type="hidden" name="cat_parent" id="cat_parent" value="parent">
                                <select name="rowlimit" id="rowlimit" onChange="loadCategoryData();"  class = "select2-me">
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
            </div>
            <div id="div_add_cat" style="display:none;">
                <div class="container-fluid"> 
                    <?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'Add FAQ',$filename,$feature_name); 
                    /* this function used to add navigation menu to the page*/ 
                    ?>          
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        Add FAQ
                                    </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                </div> <!-- header title-->
                                <div class="box-content nopadding" >
                                    <form id="frm_add_faq" class="form-horizontal form-bordered form-validate" >
                                    <div id="div_add_cat_part">
                                    </div>                                    
                                    </form>
                                </div>	<!-- Main Body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- Add category -->
			<div id="div_edit_cat" style="display:none;">
                <div class="container-fluid"> 
                    <?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'Edit FAQ',$filename,$feature_name); 
                    /* this function used to add navigation menu to the page*/ 
                    ?>          
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        Edit FAQ
                                    </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                </div> <!-- header title-->
                                <div class="box-content nopadding" >
                                    <form id="frm_edit_faq" class="form-horizontal form-bordered form-validate" >
                                    <div id="div_edit_cat_part">
                                    </div>                                    
                                    </form>
                                </div>	<!-- Main Body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- edit category -->
			 
            <div id="div_view_cat_details" style="display:none;">
                <div class="container-fluid"> 
                    <?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'FAQ Details',$filename,$feature_name); 
                    /* this function used to add navigation menu to the page*/ 
                    ?>          
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        FAQ Details
                                    </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                </div> <!-- header title-->
                                <div class="box-content nopadding" >
                                    <form id="frm_view_cat_details" class="form-horizontal form-bordered form-validate" >
                                    <div id="div_view_cat_part">
                                    </div>                                    
                                    </form>
                                </div>	<!-- Main Body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- view category -->             
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
				$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');	
				$('#error_model').modal('toggle');	
				loading_hide();		
			}
			else
			{
				delete_faq 	= 1;
				var sendInfo 	= {"batch":batch, "delete_faq":delete_faq};
				var del_cat 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_faq.php?",
					type: "POST",
					data: del_cat,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadCategoryData();
							loading_hide();
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');	
							$('#error_model').modal('toggle');													
							loading_hide();						
							$('#srch').val("");							
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
		
		function loadCategoryData()
		{
			loading_show();
			
			
			row_limit = $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page = $.trim($("#hid_page").val());
			
			load_faq = "1";			
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');	
				loading_hide();
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_faq":load_faq, "page":page};
				var cat_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_faq.php?",
					type: "POST",
					data: cat_load,
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
		
		function changeStatus(faq_id,curr_status)
		{
			loading_show();
			if(faq_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Faq id or Status to change not available</span>');
				$('#error_model').modal('toggle');		
				loading_hide();		
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"faq_id":faq_id, "curr_status":curr_status, "change_status":change_status};
				var cat_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_faq.php?",
					type: "POST",
					data: cat_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadCategoryData();
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');													
							loading_hide();
							$('#error_model').modal('toggle');	
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');													
							loading_hide();
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
		
		function changesortorder(new_order,faq_id)
		{
			loading_show();			
			if(faq_id == "" && new_order == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				faq_id =parseInt(faq_id);
				if(faq_id=="")
				{
					faq_id=1;
				}
				change_sort_order 	= 1;
				var sendInfo 	= {"faq_id":faq_id, "new_order":new_order, "change_sort_order":change_sort_order};
				var cat_order 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_faq.php?",
					type: "POST",
					data: cat_order,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadCategoryData();
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
		
		function addMoreFaq(faq_id,req_type)
		{
			$('#div_view_cat').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_cat').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_cat').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_cat').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_cat_details').css("display", "block");				
			}							
			var sendInfo = {"faq_id":faq_id,"req_type":req_type,"load_add_faq_part":"1"};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_faq.php?",
					type: "POST",
					data: cat_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#div_add_cat_part").html(" ");
							$("#div_edit_cat_part").html(" ");
							$("#div_view_cat_part").html(" ");
							
							if(req_type == "add")
							{
								$("#div_add_cat_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_cat_part").html(data.resp);				
							}	
							else if(req_type == "view")
							{
								$("#div_view_cat_part").html(data.resp);
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
       			   	loadCategoryData();	
				}
			});
			$('#srch1').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page1").val("1");					
       			   	loadCategoryData();	
				}
			});			
			loadCategoryData();
			
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page").val(page);
				loadCategoryData();						
		});
			$('#container3 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page1").val(page);
				loadErrorData();						
			});				
	});  
		// ******************************************************************************************
		// scripts for excel bulk upload [ for showing the error logs ] starts here 
		// ******************************************************************************************
		
		
	
	
		$('#frm_add_faq').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_add_faq').valid())
			{
				$.ajax({
					url: "load_faq.php?",
					type: "POST",
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					async:true,						
						success: function(response) 
						{   data = JSON.parse(response);
							
							if(data.Success == "Success") 
							{   
							   alert("FAQ Successfully Added");
							   $('#div_view_cat').css("display", "block");
							   $('#div_add_cat').css("display", "none");		
							  loadCategoryData();
								
							} 
							else 
							{   
							    
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								//loading_hide();	
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
		});	
		
		
		$('#frm_edit_faq').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_edit_faq').valid())
			{
				$.ajax({
					url: "load_faq.php?",
					type: "POST",
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					async:true,						
						success: function(response) 
						{   data = JSON.parse(response);
							
							if(data.Success == "Success") 
							{   
							   
								alert("FAQ Successfully Updated");	
								$('#div_view_cat').css("display", "block");
								$('#div_edit_cat').css("display", "none");				
									
								loadCategoryData();
							} 
							else 
							{   
							    
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								//loading_hide();	
								
												
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
		});		
					
		// ******************************************************************************************
		// scripts for excel bulk upload [ for showing the error logs ] ends here 
		// ******************************************************************************************	
		</script>
    </body>
</html>