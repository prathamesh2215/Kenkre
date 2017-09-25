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
                <div class="container-fluid">                
	<?php 
	/* this function used to add navigation menu to the page*/ 
	breadcrumbs($home_url,$home_name,'View Sales',$filename,$feature_name); 
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
                                        <div style="float:right;">
				  							<input type="checkbox" id="starred_items" name="starred_items" value="1" onChange="loadData();" class="css-checkbox"  >
				  							<label for="starred_items" class="css-label" style="color:#FFF"><i class="icon-star"></i> (Only Starred)</label>
                                       </div>
                                       <div style="clear:both">
                                       </div>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    <div style="padding:10px 15px 10px 15px !important">
                                    	<input type="hidden" name="hid_page" id="hid_page" value="1">
                                        <select name="rowlimit" id="rowlimit" onChange="loadData();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                		<?php
                                        	$add = checkFunctionalityRight("view_organisation.php",0);
                                        	if($add)
                                        	{
                                            ?>
                                            <a href="view_organisation.php" class="btn-info" style="text-decoration:none;color:white;margin-top:20px;"><i class="icon-plus"></i>&nbsp Organisation</a>
                                            <?php		
                                    	    }
                                    	?>       
                                		<?php
                                        	$add = checkFunctionalityRight("view_employee.php",0);
                                        	if($add)
                                        	{
                                        	    ?>
                                        	    <a href="view_employee.php" class="btn-info" style="text-decoration:none;color:white;margin-top:20px;"><i class="icon-plus"></i>&nbsp Employee</a>
                                    	        <?php		
                                    	    }
                                    	?>                                                                        
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
                </div>
            </div>
        </div>
               <?php getloder();?>
        <?php ?>
        <script type="text/javascript">
		function loadData()
		{
			loading_show();
			row_limit = $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page = $.trim($("#hid_page").val());
			star_status = 0;
			$("input[name='starred_items']:checked").each(function ()
			{
				star_status = 1;
			});			
			if(row_limit == "" && page == "")
			{
				//$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text,"star_status":star_status, "load_sales":1, "page":page};
				var branch_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_sales_details.php?",
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
					error: function (error) 
					{
						alert("Error Occured :(");
					}
			    });
			}
		}
		function changeStarStatus(org_id,curr_status)
		{
			loading_show();
			if(org_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Something Missing</span>');
				$('#error_model').modal('toggle');	
			}
			else
			{
				var sendInfo 	= {"org_id":org_id, "curr_status":curr_status, "update_star_status":1};
				var org_star_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_sales_details.php?",
					type: "POST",
					data: org_star_status,
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
						alert(request.responseText);
					},
					complete: function()
					{
						//alert("complete");
                	}
			    });						
			}
		}	
		function updateLeadDropdown(sm_orgid)
		{
			if(sm_orgid == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Something Missing</span>');
				$('#error_model').modal('toggle');	
			}
			else
			{
				var sm_empid	= $("#org_emp"+sm_orgid).val();
				var sendInfo 	= {"sm_orgid":sm_orgid,"sm_empid":sm_empid,"update_lead_dropDown":1};
				var lead_dp= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_sales_details.php?",
					type: "POST",
					data: lead_dp,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{			
							$("#lead_status"+sm_orgid).html(data.resp);
							$("#lead_status"+sm_orgid).select2();
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
		function updateComment(sm_orgid)
		{
			loading_show();
			var sm_empid 		= $.trim($("#org_emp"+sm_orgid).val());
			var sm_leadid		= $.trim($("#lead_status"+sm_orgid).val());
			var sm_comment		= $.trim($("#comment"+sm_orgid).val());
			if(sm_orgid == "" && sm_empid == "" )
			{
				$("#model_body").html('<span style="style="color:#F00;">Something Missing</span>');
				$('#error_model').modal('toggle');	
			}
			else
			{
				var sendInfo 	= {"sm_orgid":sm_orgid, "sm_empid":sm_empid,"sm_comment":sm_comment,"sm_leadid":sm_leadid,"add_comment":1};
				var show_comment= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_sales_details.php?",
					type: "POST",
					data: show_comment,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadComment(sm_orgid);
							$("#comment"+sm_orgid).val("");								
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
		function loadComment(org_id)
		{
			loading_show();
			var emp_id = $("#org_emp"+org_id).val();
			if(org_id == "" && emp_id == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Something Missing</span>');
				$('#error_model').modal('toggle');					
			}
			else
			{
				var sendInfo 	= {"org_id":org_id, "emp_id":emp_id, "show_comment":1};
				var show_comment= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_sales_details.php?",
					type: "POST",
					data: show_comment,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							$("#commentbox"+org_id).html(data.resp);
							loading_hide();
						} 
						else
						{
							$("#commentbox"+org_id).html(data.resp);																		
							loading_hide();
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');	
						$('#error_model').modal('toggle');						
					},
					complete: function()
					{
						//alert("complete");
                	}
			    });						
			}
		}							
		$( document ).ready(function() {
			$('#srch').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page").val(1);
       			   	loadData();
				}
			});
			loadData();	
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page").val(page);
				loadData();						
		});

	});  
		</script>
    </body>
</html>