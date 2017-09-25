<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Review";
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
	breadcrumbs($home_url,$home_name,'View Registration',$filename,$feature_name); 
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
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                         <select name="class_id" id="class_id" onChange="getBranch();loadRegData();"  class = "select2-me input-medium">
                                        <?php
											$sql_get_class = " SELECT class_id,class_name FROM `tbl_class` ";
											$sql_get_class .= " WHERE `class_id` IN (SELECT distinct(class_id) FROM `tbl_class_reviews`) ";
											$res_get_class = mysqli_query($db_con,$sql_get_class) or die(mysqli_error($db_con));
												?>
												<option value="">All Class</option>
                                                <?php
											while($row_get_class = mysqli_fetch_array($res_get_class))
											{
												?>
	                               <option value="<?php echo $row_get_class['class_id'];?>"><?php echo ucwords($row_get_class['class_name']);?></option>
                                                <?php
											}
											
										?>
                                        </select>
                                        
                                        <select name="class_branch" id="class_branch" onChange="loadRegData();"  disabled class = "select2-me input-medium">	
                                        		<option value="">Class Branch </option>						
                                        </select>  
                                        
                                    <select name="class_type" id="class_type" onChange="loadRegData();"  class = "select2-me input-medium">	
                                        		<option value="">Class Type </option>						
                                        	<?php 
											   $sql_get_type = " SELECT DISTINCT branchoff_type FROM tbl_class_branchoffering WHERE 1=1 "; 
											   if($utype !=1)
											   {
											   }
											   $res_get_type = mysqli_query($db_con,$sql_get_type) or die(mysqli_error($db_con));
											   while($row_type = mysqli_fetch_array($res_get_type))
											   {
											?>
                                            <option value="<?php echo $row_type['branchoff_type']; ?>"><?php echo $row_type['branchoff_type']; ?></option>
                                            <?php } ?>
                                            	
                                        </select>
                                        
                                        <select name="class_offering" id="class_offering" onChange="loadRegData();"  disabled class = "select2-me input-medium">	
                                        		<option value="">Class Offering </option>						
                                        </select>    
                                        
                                        
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    <div style="padding:10px 15px 10px 15px !important">
                                    	<input type="hidden" name="hid_page" id="hid_page" value="1">
                                    	<input type="hidden" name="cat_parent" id="cat_parent" value="parent">
                                        <select name="rowlimit" id="rowlimit" onChange="loadRegData();"  class = "select2-me">
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
		<div id="div_view_cat_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Review Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Review Details
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
                </div><!-- view Review -->             
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
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to delete.</span>');	
				$('#error_model').modal('toggle');					
			}
			else
			{
				delete_rev 	= 1;
				var sendInfo 	= {"batch":batch, "delete_rev":delete_rev};
				var del_rev	= JSON.stringify(sendInfo);
				//alert("dasd");								
				$.ajax({
					url: "coaching/load_registration.php?",
					type: "POST",
					data: del_rev,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadRegData();
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
			function loadRegData()
			{
			loading_show();
			row_limit = $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page = $.trim($("#hid_page").val());
			review_star			= $.trim($('select[name="review_star"]').val());
			class_id			= $.trim($('select[name="review_class_id"]').val());
			load_registration = "1";			
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');	
				loading_hide();
			}
			else
			{
				var sendInfo 	= {"search_text":search_text,"row_limit":row_limit,"review_star":review_star,"class_id":class_id, "load_registrations":1, "page":page};
				var review_data = JSON.stringify(sendInfo);				
				$.ajax({
					url: "coaching/load_registration.php?",
					type: "POST",
					data: review_data,
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
			function changeStatus(review_id)
			{
			curr_status	= $("#review_status"+review_id).val();
			loading_show();
			if(review_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
				$('#error_model').modal('toggle');		
				loading_hide();		
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"review_id":review_id, "curr_status":curr_status, "change_status":change_status};
				var review_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "coaching/load_registration.php?",
					type: "POST",
					data: review_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadRegData();
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
                	}
			    });						
			}
		}			
			function toggleMyDiv(chevron_id,div_id)
			{
				if($("#"+chevron_id).is('.icon-chevron-up'))
				{
				   $("#"+chevron_id).addClass('icon-chevron-down').removeClass('icon-chevron-up');
				   $("#"+div_id).slideUp();
				}		
				else if($("#"+chevron_id).is('.icon-chevron-down'))
				{				
					$("#"+chevron_id).addClass('icon-chevron-up').removeClass('icon-chevron-down');
				   $("#"+div_id).slideDown();				
				}
			}
			$( document ).ready(function() 
			{
				$('#srch').keypress(function(e) 
				{
					if(e.which == 13) 
					{	
						$("#hid_page").val("1");					
   		   			   	loadRegData();	
					}
				});			
				loadRegData();			
				$('#container1 .pagination li.active').live('click',function()
				{
					var page = $(this).attr('p');
					$("#hid_page").val(page);
					loadRegData();						
			});					
		}); 
		
		 
		function updateComment(review_id)
		{
			loading_show();
			var admin_comment 		= $.trim($("#comment"+review_id).val());
			
			if(review_id == ""  )
			{
				$("#model_body").html('<span style="style="color:#F00;">Something Missing</span>');
				$('#error_model').modal('toggle');	
			}
			else
			{
				var sendInfo 	= {"admin_comment":admin_comment,"review_id":review_id,"add_comment":1};
				var show_comment= JSON.stringify(sendInfo);								
				$.ajax({
					url: "coaching/load_registration.php?",
					type: "POST",
					data: show_comment,
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
						//alert("complete");
					}
						
			    });	
									
			}
		}	
		

		
		function getBranch(class_id)
		{
		}
		</script>
    </body>
</html>