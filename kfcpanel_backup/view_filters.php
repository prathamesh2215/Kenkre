<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Filters";	
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
        	<div class="container-fluid" id="div_view_cat">                
	<?php 
	/* this function used to add navigation menu to the page*/ 
	breadcrumbs($home_url,$home_name,'View Filters',$filename,$feature_name); 
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
                                            <button type="button" class="btn-info" onClick="addMoreFilters('','add')" ><i class="icon-plus"></i>&nbspAdd Filters</button>
  											<?php		
										}
									?>
                                    <div style="padding:10px 15px 10px 15px !important">
                                    	<input type="hidden" name="hid_page" id="hid_page" value="1">
                                    	<input type="hidden" name="filt_type_val" id="filt_type_val" value="parent">
                                       	<input type="hidden" name="filt_sub_child_val" id="filt_sub_child_val" value="parent">
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
                                            Excel Bulk Upload For Filters
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <div class="profileGallery">
                                            <div style="width:50%;" align="center">
                                                <div id="loading"></div>                                            
                                                <div id="container2">
                                                    <div class="data">
                                                        <form method="post" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data" id="frm_add_cat_excel">
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
                                            Wrong Entries For Filters
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
                    </div>
            <div id="div_add_cat" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Filters',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Filters
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_cat" class="form-horizontal form-bordered form-validate" >
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
                        breadcrumbs($home_url,$home_name,'Edit Filters',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Filters
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_cat" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_cat_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- edit category -->
			<div id="div_error_cat" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Update Error Filters',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Error Filters
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_error_cat" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_error_cat_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- error category -->   
            <div id="div_view_cat_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Filters Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Filters Details
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
            <div id="div_view_filt_level_assign" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Filter Level Assignment',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                           Filter Level Assignment
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                        <div id="div_view_filt_level_assign_details">                                        	
                                        </div>                                    
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- Level Assignment -->                             
		</div>
	</div>
</div>     
        <?php getloder();?>
        <script type="text/javascript">
		/*sub child filter*/
		function getFilterChild(grandfather_id)
		{
			my_id = ""+grandfather_id;
			var grandfather_value			= $.trim($('select[name="'+my_id+'"]').val());
			if(grandfather_value == "parent" && grandfather_value != "")
			{
				$("#filt_sub_child").html('<option value="parent">Parent</option>');
				$("#filt_sub_child").select2();				
			}
			else if(grandfather_value != "")
			{
				var sendInfo = {"child_id":grandfather_value,"get_child_list":"1"};
				var cat_child = JSON.stringify(sendInfo);
				$.ajax({
					url: "load_filters.php",
					type: "POST",
					data: cat_child,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#filt_sub_child").html(data.resp);
							$("#filt_sub_child").select2();
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
		/* sub child filter*/
		/*Level Assignment*/
		function levelAssign(filt_id,filt_parent_type)
		{
			loading_show();
			if(filt_id == "" && filt_parent_type == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Error...</span>');	
				$('#error_model').modal('toggle');	
				loading_hide();
			}
			else
			{
				var sendInfo 	= {"filt_id":filt_id,"filt_parent_type":filt_parent_type,"load_filt_level":1};
				var filt_level 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_filters.php",
					type: "POST",
					data: filt_level,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							$("#div_view_cat").slideUp();
							$("#div_view_filt_level_assign").slideDown();
							$("#div_view_filt_level_assign_details").html(data.resp);
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
		function assignFiltLevel()
		{
			loading_show();
			filters_data = [];
			$("input:checkbox[name=filt_parent]:checked").each(function () 		
			{
   				var parent_filt = parseInt($(this).attr("id"));
				$("input:checkbox[name="+parent_filt+"filt_child]:checked").each(function () 				
				{					
   					child_filt = parseInt($(this).attr("id"));
					filters_data.push(parent_filt+":"+child_filt);						
				});								
			});
			levels_data = [];	
			$("input:checkbox[name=level_parent]:checked").each(function () 		
			{
   				var parent_level = parseInt($(this).attr("id"));
				var checked = $("input[@id=" + id + "]:checked").length;
				$("input:checkbox[name="+parent_level+"level_child]:checked").each(function () 				
				{					
   					child_level = parseInt($(this).attr("id"));
					levels_data.push(parent_level+":"+child_level);
				});								
			});					
			if(levels_data.length == 0 || filters_data.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please check Filters and Levels.</span>');	
				$('#error_model').modal('toggle');	
				loading_hide();
			}
			else
			{
				var sendInfo 	= {"filters_data":filters_data,"levels_data":levels_data,"assign_filt_level":1};
				var filt_level 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_filters.php",
					type: "POST",
					data: filt_level,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');
							location.reload();
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
		function levelBox(parent_value)
		{
			if(parent_value == "parent")
			{
				$("#level_assign").slideUp();
			}
			else
			{
				$("#level_assign").slideDown();
			}
		}
		/*Level Assignment*/		
		function viewChild(filt_type_val,filt_sub_child_val)
		{
			$.trim($('#srch').val(""));			
			$.trim($('#filt_type_val').val(filt_type_val));
			$.trim($('#filt_sub_child_val').val(filt_sub_child_val));
			if(filt_type_val == "parent" && filt_sub_child_val == "parent")		
			{
			}
			else
			{
				$("#hid_page").val(1);				
			}
			loadData();
		}
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
				delete_category 	= 1;
				var sendInfo 	= {"batch":batch, "delete_category":delete_category};
				var del_cat 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_filters.php",
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
			filt_type_val	= $.trim($('#filt_type_val').val());
			filt_sub_child_val	= $.trim($('#filt_sub_child_val').val());			
			load_cat = "1";			
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');	
				loading_hide();
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_cat":load_cat, "page":page,"filt_type_val":filt_type_val,"filt_sub_child_val":filt_sub_child_val};
				var cat_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_filters.php",
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
                
       /*Code By Tariq -16-09-2016*/
               
                function changeFiltSlug(filt_id)
		{
                  
			loading_show();
			filt_id			= parseInt(filt_id);
			var filt_slug	= $("textarea#"+filt_id+"filt_slug").val();
                      
			if(filt_id == "" && filt_slug == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
				$('#error_model').modal('toggle');		
				loading_hide();		
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"filt_id":filt_id, "filt_slug":filt_slug, "change_filt_slug":1};
				var filt_status 	= JSON.stringify(sendInfo);
				$.ajax({
					url: "load_filters.php",
					type: "POST",
					data: filt_status,
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
                	}
			    });						
			}
		}
                
       /*Code End By Tariq -16-09-2016*/
		function changeStatus(cat_id,curr_status)
		{
			loading_show();
			if(cat_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
				$('#error_model').modal('toggle');		
				loading_hide();		
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"cat_id":cat_id, "curr_status":curr_status, "change_status":change_status};
				var cat_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_filters.php",
					type: "POST",
					data: cat_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadData();
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
		function changeOrder(cat_id,new_order)
		{
			loading_show();			
			if(cat_id == "" && new_order == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				change_sort_order 	= 1;
				var sendInfo 	= {"cat_id":cat_id, "new_order":new_order, "change_sort_order":change_sort_order};
				var cat_order 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_filters.php",
					type: "POST",
					data: cat_order,
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
		function addMoreFilters(cat_id,req_type)
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
			var sendInfo = {"cat_id":cat_id,"req_type":req_type,"load_add_cat_part":"1"};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_filters.php",
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
								$("#div_add_cat_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_cat_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_cat_part").html(data.resp);				
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
		$( document ).ready(function() 
		{
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
       			   	loadData();	
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
			load_error_cat 	= "1";			
			
			if(row_limit1 == "" && page1 == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');
				$('#error_model').modal('toggle');				
			}
			else
			{
				var sendInfo_error 		= {"row_limit1":row_limit1, "search_text1":search_text1, "load_error_cat":load_error_cat, "page1":page1,"cat_parent1":cat_parent1};
				var cat_load_error = JSON.stringify(sendInfo_error);				
				$.ajax({
					url: "load_filters.php",
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
				var sendInfo 	= {"batch":batch, "delete_catogery_error":1};
				var del_cat 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_filters.php",
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
		$('#frm_add_cat_excel').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_add_cat_excel').valid())
			{
				loading_show();	
				$.ajax({
						url: "load_filters.php",
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
								window.location.assign("view_filters.php?pag=<?php echo $title; ?>");
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
		$('#frm_add_cat').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_add_cat').valid())
			{
				loading_show();	
				var cat_name 			= $.trim($('input[name="cat_name"]').val());
				var cat_description 	= $.trim(CKEDITOR.instances['cat_description'].getData());
				var cat_parent			= $.trim($('select[name="cat_parent"]').val());
				var filt_meta_tags 		= $.trim($('input[name="filt_meta_tags"]').val());
				var filt_meta_description= $.trim($("textarea#filt_meta_description").val());
				var filt_meta_title 		= $.trim($('input[name="filt_meta_title"]').val());
				var filt_sub_child		= $.trim($('select[name="filt_sub_child"]').val());
				var cat_status 			= $('input[name=cat_status]:checked', '#frm_add_cat').val();
				
				var levels_data = "";
				$("input[name='level_parent']:checked").each(function ()
				{
					var parent_level= parseInt($(this).attr("id"));
					var child_level = [];
					$("input[name="+parent_level+"level_child]:checked").each(function ()
					{
						child_level.push(parseInt($(this).attr("id")));
					});
					levels_data = levels_data+"("+parent_level+":"+child_level+")*";
				});			
				if(cat_name == "" && cat_parent == "" && filt_sub_child == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">PLease fill all * fileds.</span>');
					$('#error_model').modal('toggle');	
					loading_hide();						
				} 
				else
				{ 
					e.preventDefault();
					$('input[name="reg_submit_add"]').attr('disabled', 'true');
					var sendInfo 		= {"filt_sub_child":filt_sub_child,"levels_data":levels_data,"cat_name":cat_name, "cat_description":cat_description, "cat_parent":cat_parent,"filt_meta_tags":filt_meta_tags,"filt_meta_description":filt_meta_description,"filt_meta_title":filt_meta_title,"cat_status":cat_status,"insert_req":"1"};
					var cat_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_filters.php",
						type: "POST",
						data: cat_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								window.location.assign("view_filters.php?pag=<?php echo $title; ?>");
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
		$('#frm_edit_cat').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_cat').valid())
			{
				loading_show();
				var cat_id				= $.trim($('#cat_id').val());
				var cat_name 			= $.trim($('input[name="cat_name"]').val());
				var cat_description 	= $.trim(CKEDITOR.instances['cat_description'].getData());
				var cat_parent			= $.trim($('select[name="cat_parent"]').val());
				var filt_sub_child		= $.trim($('select[name="filt_sub_child"]').val());				
				var cat_status 			= $('input[name=cat_status]:checked', '#frm_edit_cat').val()	

				var levels_data = "";
				$("input[name='level_parent']:checked").each(function ()
				{
					var parent_level= parseInt($(this).attr("id"));
					var child_level = [];
					$("input[name="+parent_level+"level_child]:checked").each(function ()
					{
						child_level.push(parseInt($(this).attr("id")));
					});
					levels_data = levels_data+"("+parent_level+":"+child_level+")*";
				});					
										
				if(cat_name == "" && cat_parent == "" && filt_sub_child == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">PLease fill all * fileds.</span>');
					$('#error_model').modal('toggle');	
					loading_hide();						
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_edit"]').attr('disabled', 'true');
					var sendInfo 		= {"filt_sub_child":filt_sub_child,"levels_data":levels_data,"cat_id":cat_id,"cat_name":cat_name, "cat_description":cat_description, "cat_parent":cat_parent,"cat_status":cat_status,"update_req":"1"};
					var cat_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_filters.php",
						type: "POST",
						data: cat_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response)
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{	
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');							
								$('#error_model').modal('toggle');		
								loading_hide();						
								window.location.assign("view_filters.php?pag=<?php echo $title; ?>");
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
		});		
		$('#frm_error_cat').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_error_cat').valid())
			{
				loading_show();
				var cat_name 			= $.trim($('input[name="cat_name"]').val());
				var cat_description 	= $.trim(CKEDITOR.instances['cat_description'].getData());
				var cat_parent			= $.trim($('select[name="cat_parent"]').val());
				var filt_sub_child		= $.trim($('select[name="filt_sub_child"]').val());								
				var cat_status 			= $('input[name=cat_status]:checked', '#frm_error_cat').val()	
				var error_id			= $.trim($("#error_id").val());	
				if(parent == 1)
				{
					$("#model_body").html('<span style="style="color:#F00;">Please select parent type</span>');							
					$('#error_model').modal('toggle');	
					loading_hide();					
				} 
				else
				{
					e.preventDefault();
					$('input[name="reg_submit_error"]').attr('disabled', 'true');
					var sendInfo 		= {"cat_name":cat_name, "cat_description":cat_description, "cat_parent":cat_parent,"cat_status":cat_status,"error_id":error_id,"insert_req":"1"};
					var cat_insert = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_filters.php",
						type: "POST",
						data: cat_insert,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{	
								loading_hide();						
								window.location.assign("view_filters.php?pag=<?php echo $title; ?>");
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
		});					
		// ******************************************************************************************
		// scripts for excel bulk upload [ for showing the error logs ] ends here 
		// ******************************************************************************************	
		</script>
    </body>
</html>