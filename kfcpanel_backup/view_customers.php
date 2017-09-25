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
            <div class="container-fluid" id="div_view_customers">                
					<?php 
                	/* this function used to add navigation menu to the page*/ 
                	breadcrumbs($home_url,$home_name,'View Customers',$filename,$feature_name); 
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
                                <div style="float:right">
                                <input onChange="loadCustomersData();" style="float:right" value="1" id="star_customers" name="star_customers" class="css-checkbox customers" type="checkbox">
                                <label for="star_customers" class="css-label" style="float:right" >Star Customers</label>
                                </div>
                               
                               
                            </div><!-- header title-->
                            <div class="box-content nopadding">
                            <?php
                                $add = checkFunctionalityRight($filename,0);
                                if($add)
                                {
                                    ?>
                                    <button type="button" class="btn-info" onClick="addMoreCustomers('','add')" ><i class="icon-plus"></i>&nbspAdd Customers</button>
                                    <?php		
                                }
                            ?>                                       
                            <div style="padding:10px 15px 10px 15px !important">
                                <input type="hidden" name="hid_page_customers" id="hid_page_customers" value="1">
                                <select name="row_limit_customers" id="row_limit_customers" onChange="loadCustomersData();"  class = "select2-me">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries per page
                                <input type="text" class="input-medium" id = "search_text_customers" name="search_text_customers" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                 <div class="container-fluid" id="div_view_comments" style="display:none">                
					<?php 
                	/* this function used to add navigation menu to the page*/ 
                	breadcrumbs($home_url,$home_name,'View Comments',$filename,$feature_name); 
                	/* this function used to add navigation menu to the page*/ 
                	?>          
					<div class="row-fluid">
					<div class="span12">
						<div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-table"></i>
                                   Comments
                                </h3>
                               
                            </div><!-- header title-->
                            <div class="box-content nopadding">
                                                                   
                            <div style="padding:10px 15px 10px 15px !important">
                                <input type="hidden" name="hid_page_customers" id="hid_page_comments" value="1">
                                <select name="row_limit_comments" id="row_limit_comments" onChange="loadCustomersData();"  class = "select2-me">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries per page
                                <input type="text" class="input-medium" id = "search_comments" name="search_comments" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
                            </div>
                            <div class="profileGallery">
                                <div style="width:99%;" align="center">
                                    <div id="loading"></div>                                            
                                    <div id="container2" class="data_container">
                                        <div class="data"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
					</div>
				</div>                     
                </div>
			<div id="div_add_customers" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Customers',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Customers
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_customers_add" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_customers_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Customers -->
			<div id="div_edit_customers" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Customers',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Customers
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_customers" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_customers_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Customers -->
          	<div id="div_view_customers_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Customers Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Customers Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_customers_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_customers_details_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- view Customers -->                  
    	</div>
	</div>
	<?php getloder();?>
	<script type="text/javascript">
		
		function multipleDelete()
		{			
			loading_show();		
			var customers = [];
			$(".customers:checked").each(function ()
			{
				customers.push(parseInt($(this).val()));
			});
			if (typeof customers.length == 0)
			{
				alert("Please select checkbox to delete catogery");				
			}
			else
			{
				var sendInfo 	= {"customers":customers, "delete_customers":1};
				var delete_customers 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_customers.php?",
					type: "POST",
					data: delete_customers,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadCustomersData();
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
		
		function loadCustomersData()
		{
			loading_show();
			row_limit_customers 	= $.trim($('select[name="row_limit_customers"]').val());
			search_text_customers 	= $.trim($('#search_text_customers').val());
			page 					= $.trim($("#hid_page_customers").val());
			if($('#star_customers').is(':checked'))
			{
				var star_status = 1;
			}
			else
			{
				var star_status = 0;
			}
			load_customers = "1";			
			
			if(row_limit_customers == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 	= {"star_status":star_status,"row_limit_customers":row_limit_customers, "search_text_customers":search_text_customers, "load_customers":load_customers, "page":page};
				var page_module_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_customers.php?",
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
					}
			    });
			}
		}
		
		function addMoreCustomers(cust_id,req_type)
		{
			$('#div_view_customers').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_customers').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_customers').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_customers').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_customers_details').css("display", "block");				
			}							
			var sendInfo = {"cust_id":cust_id,"req_type":req_type,"load_customers_parts":1};
			var page_module_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_customers.php?",
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
								$("#div_add_customers_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_customers_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_customers_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_customers_details_part").html(data.resp);
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
		
		function changeStatus(cust_id,curr_status)
		{
			loading_show();
			if(cust_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"cust_id":cust_id, "curr_status":curr_status, "change_status":change_status};
				var page_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_customers.php?",
					type: "POST",
					data: page_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadCustomersData();
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
			$('#search_text_customers').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page_customers").val("1");				
       			   	loadCustomersData();
				}
			});
			loadCustomersData();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page_customers").val(page);
				loadCustomersData();						
			});
		}); 		
	
		$('#frm_customers_add').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_customers_add').valid())
			{
					loading_show();	
					$('input[name="reg_submit_add"]').attr('disabled', 'true');				
					$.ajax({
						url: "load_customers.php",
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
								window.location.assign("view_customers.php?pag=<?php echo $title; ?>");
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
		});	/*Add Customers*/
			
		$('#frm_edit_customers').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_customers').valid())
			{
					loading_show();	
					$('input[name="reg_submit_edit"]').attr('disabled', 'true');				
					$.ajax({
						url: "load_customers.php?",
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
								window.location.assign("view_customers.php?pag=<?php echo $title; ?>");
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
		}); /* edit Customers*/
		
		
		function resetpassword(cust_email)
		{
			
			
			$('#myModal').modal({
			  backdrop: 'static'
			});
					
			$('#send_email').on('click', function()
			{	
				$('#myModal').modal('hide')							
				loading_show();
				
				reset_pass 	= 1;
				var sendInfo 	= {"cust_email":cust_email,"reset_pass":reset_pass};
				var page_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_customers.php?",
					type: "POST",
					data: page_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{		
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadCustomersData();
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
						
					}
			    });						
			
			});
		}
		
		function comments1(cust_id)
		{
			loading_show();
			row_limit_customers 	= $.trim($('select[name="row_limit_comments"]').val());
			search_text_customers 	= $.trim($('#search_comments').val());
			page 					= $.trim($("#hid_page_comments").val());
			$('#div_view_customers').css('display','none');
			$('#div_view_comments').css('display','block');
			
			get_comments = "1";			
			
			if(row_limit_customers == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 	= {"cust_id":cust_id,"row_limit_customers":row_limit_customers, "search_text_customers":search_text_customers, "get_comments":get_comments, "page":page};
				var page_module_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_customers.php?",
					type: "POST",
					data: page_module_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{   
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{  
							$("#container2").html(data.resp);
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
					}
			    });
			}
		}
		function comments(cust_id)
		{
			loading_show();
                        var  update_comments = "1";			
			var  comment         = $("#comment_"+cust_id).val();
			
				var sendInfo 	= {"cust_id":cust_id,"update_comments":update_comments,"comment":comment};
				var page_module_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_customers.php?",
					type: "POST",
					data: page_module_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{    
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{  
							
							loading_hide();
							loadCustomersData();
						} 
						else
						{							
						$("#model_body").html('<span style="style="color:#F00;">Comment not updated</span>');							
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
		
		function changeStarStatus(cust_id,status)
		{
			
			var  update_starstatus = "1";			
			
			
				var sendInfo 	= {"cust_id":parseInt(cust_id),"update_starstatus":update_starstatus,"status":status};
				var page_module_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_customers.php?",
					type: "POST",
					data: page_module_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{    
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{  
							
							loading_hide();
							loadCustomersData();
						} 
						else
						{							
						$("#model_body").html('<span style="style="color:#F00;">Comment not updated</span>');							
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
	</script>
    <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Are you want to sure reset password</h4>
      </div>
      <div class="modal-body" style="text-align:center">
       <input type="button" id="send_email" class="btn-success" value="Yes" data-dismiss="modal" />
       <input type="button" class="btn-danger" data-dismiss="modal" value="No" />
      </div>
    </div>
  </div>
</div>

    <!-- Modal -->
<div class="modal fade" id="comments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Are you want to sure reset password</h4>
      </div>
      <div class="modal-body" style="text-align:center" id="comments_body">
       <input type="button" id="send_email" class="btn-success" value="Yes" data-dismiss="modal" />
       <input type="button" class="btn-danger" data-dismiss="modal" value="No" />
      </div>
    </div>
  </div>
</div>
    </body>
</html>