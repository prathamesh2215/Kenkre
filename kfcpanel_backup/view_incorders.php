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
<!-- added by satish for datepicker-->

<link href="./bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<!-- datepicker end here-->
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
        	<div class="container-fluid" id="div_view_order">                
				<?php 
                /* this function used to add navigation menu to the page*/ 
                breadcrumbs($home_url,$home_name,'View Orders',$filename,$feature_name); 
                /* this function used to add navigation menu to the page*/ 
                ?>          
				<div class="row-fluid" id="all_orders" style="display:block;">
					<div class="span12">
						<div class="box box-color box-bordered">
							<div class="box-title">
                            	<h3><i class="icon-table"></i><?php echo $feature_name; ?></h3>
                                
                                
                                        
                            </div> <!-- header title-->
                            <div class="box-content nopadding">
                                <div style="padding:10px 15px 10px 15px !important">
                                    <input type="hidden" name="hid_page" id="hid_page" value="1">
                                    <input type="hidden" name="desg_parent" id="desg_parent" value="Parent">
                                    <select name="rowlimit" id="rowlimit" onChange="loadOrderData();"  class = "select2-me">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> entries per page
                                    <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                <div class="row-fluid" id="users_order_details" style="display:none;">
					<div class="span12">
						<div class="box box-color box-bordered">
							<div class="box-title">
                            	<h3><i class="icon-table"></i>Order Details<?php //echo $feature_name; ?></h3>
								<button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                            </div> <!-- header title-->
                            <div class="box-content nopadding">
                            	<div style="padding:10px 15px 10px 15px !important">
                                </div>
                                <div class="profileGallery">
                                    <div style="width:99%;" align="center">
                                        <div id="loading"></div>                                            
                                        <div id="order_detail" class="data_container">
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
    <?php getloder();?>		     
    <script type="text/javascript">
		function loadOrderData()
		{  
		    var daterange =$('#daterange1').val();
			var start_date =$('#start_date').val();
			var end_date   =$('#end_date').val();
		    if(daterange=="specific_date")
			{
				if(start_date=="")
				{
					return false;
				}
			}
			if(daterange=="date_range")
			{
				if(start_date=="" || end_date=="")
				{
					return false;
				}
			}
			loading_show();
			row_limit 	= $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page 		= $.trim($("#hid_page").val());					
			
			
			
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 	= {"daterange":daterange,"start_date":start_date,"end_date":end_date,"row_limit":row_limit, "search_text":search_text, "load_orders":1, "page":page};
				var desg_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_incorders.php?",
					type: "POST",
					data: desg_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{//alert(response);
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{ //  alert(data.resp);
							loading_hide();
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
		function sendOrderEmail(type,towhom,type_id)
		{
			var sendInfo 	= {"towhom":towhom,"type_id":type_id,type:type,"send_Order_mail":1};
			var mail_send 	= JSON.stringify(sendInfo);				
			$.ajax({
					url: "load_incorders.php?",
					type: "POST",
					data: mail_send,
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
		function orderDeatils(ord_id)
		{
			loading_show();
			if(ord_id == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Order Does not Exists.</span>');
				$('#error_model').modal('toggle');
				loading_hide();
			}
			else
			{
				var sendInfo 	= {"ord_id":ord_id, "load_order_details":1};
				var cust_orders = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_incorders.php?",
					type: "POST",
					data: cust_orders,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{//alert(response);
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#all_orders").slideUp();							
							$("#users_order_details").slideDown();							
							$("#order_detail").html(data.resp);
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
		function changeStatus(type_id,type)
		{
			loading_show();
			if(type == 0)
			{
				var new_status 	= $("#ord_status"+type_id).val();				
			}
			else if(type == 1)
			{
				var new_status 	= $("#cart_status"+type_id).val();				
			}
			if(type_id == "" && new_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Order or Status not Available.</span>');
				$('#error_model').modal('toggle');
				loading_hide();
			}
			else
			{
				var sendInfo 	= {"type_id":type_id,"type":type,"new_status":new_status, "change_order_status":1};
				var ord_status = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_incorders.php?",
					type: "POST",
					data: ord_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							loadOrderData();
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
		function changeOrderStatus(ord_id)
		{
			loading_show();
			var new_status 	= $("#ord_status"+ord_id).val();
			if(ord_id == "" && new_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Order or Status not Available.</span>');
				$('#error_model').modal('toggle');
				loading_hide();
			}
			else
			{
				var sendInfo 	= {"ord_id":ord_id,"new_status":new_status, "change_order_status":1};
				var ord_status = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_incorders.php?",
					type: "POST",
					data: ord_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							loadOrderData();
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
		$( document ).ready(function() 
		{
			$('#srch').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page").val("1");				
					loadOrderData();
				}
			});
			loadOrderData();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page").val(page);
				loadOrderData();						
			});
			
			$(".form_date").datetimepicker({
      //:  "fr",
      
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        //showMeridian: 1
    });
		}); 
		
function datefilter(date_type,valu)
{
	if(valu != 'excel')
	{
		valu = 1
	}
	if(date_type == 'All')
	{
		var today = new Date();
   		var dd = today.getDate();
   		var mm = today.getMonth()+1; //January is 0!
   		var yyyy = today.getFullYear();	
		document.getElementById("end_date").disabled = true;
		document.getElementById("start_date").value="";
		document.getElementById("end_date").value="";
		document.getElementById("labelstart").style.display="none";
		document.getElementById("start_date").style.display="none";	
		document.getElementById("labelend").style.display="none";
		document.getElementById("end_date").style.display="none";
		var enddate = yyyy+"-"+mm+"-"+dd;	
		var startdate = '0000-00-00';
		loadData(valu,startdate,enddate);
	}
	else 
	if(date_type == 'today')
	{
		var today = new Date();
   		var dd = today.getDate();
   		var mm = today.getMonth()+1; //January is 0!
   		var yyyy = today.getFullYear();	
		document.getElementById("end_date").disabled = true;
		document.getElementById("start_date").value="";
		document.getElementById("end_date").value="";
		document.getElementById("labelstart").style.display="none";
		document.getElementById("start_date").style.display="none";	
		document.getElementById("labelend").style.display="none";
		document.getElementById("end_date").style.display="none";		
		var startdate = yyyy+"-"+mm+"-"+dd;
		var enddate = yyyy+"-"+mm+"-"+dd;
		loadOrderData();		
	}
	else if(date_type == 'yesterday')
	{
		var today = new Date();
   		var dd = today.getDate()-1;
   		var mm = today.getMonth()+1; //January is 0!
   		var yyyy = today.getFullYear();			
		document.getElementById("end_date").disabled = true;
		document.getElementById("start_date").value="";
		document.getElementById("end_date").value="";
		document.getElementById("labelstart").style.display="none";
		document.getElementById("start_date").style.display="none";	
		document.getElementById("labelend").style.display="none";
		document.getElementById("end_date").style.display="none";
		var startdate = yyyy+"-"+mm+"-"+dd;
		var enddate = yyyy+"-"+mm+"-"+dd;
			loadOrderData();
	}
	else if(date_type == 'this_month')
	{
		var today = new Date();
   		var dd = today.getDate();
   		var mm = today.getMonth()+1; //January is 0!
   		var yyyy = today.getFullYear();			
		document.getElementById("end_date").disabled = true;
		document.getElementById("start_date").value="";
		document.getElementById("end_date").value="";
		document.getElementById("labelstart").style.display="none";
		document.getElementById("start_date").style.display="none";	
		document.getElementById("labelend").style.display="none";
		document.getElementById("end_date").style.display="none";				
    	var dd1 = 01;
		var test = new Date(yyyy, mm, 0);			
		var dd2 = test.getDate();
		startdate = yyyy+"-"+mm+"-"+dd1;
		enddate = yyyy+"-"+mm+"-"+dd2;	
			loadOrderData();
	}
	else if(date_type == 'last_month')
	{	
		var today = new Date();
   		var dd = today.getDate();
   		var mm = today.getMonth()+1; //January is 0!
   		var yyyy = today.getFullYear();		
		document.getElementById("end_date").disabled = true;
		document.getElementById("start_date").value="";
		document.getElementById("end_date").value="";
		document.getElementById("labelstart").style.display="none";
		document.getElementById("start_date").style.display="none";	
		document.getElementById("labelend").style.display="none";
		document.getElementById("end_date").style.display="none";		
    	var dd1 = 01;
		var test = new Date(yyyy,(mm-1), 0);			
		var dd2 = test.getDate();
		startdate = yyyy+"-"+(mm-1)+"-"+dd1;
		enddate = yyyy+"-"+(mm-1)+"-"+dd2;	
			loadOrderData();
	}	
	else if(date_type == 'specific_date')
	{			
		document.getElementById("labelstart").style.display="block";
		document.getElementById('labelstart').innerHTML = "Specify Date";
		document.getElementById("start_date").style.display="block";	
		document.getElementById("labelend").style.display="none";
		document.getElementById("end_date").style.display="none";
		startdate = $('input[name="start_date"]').val();
		enddate	= $('input[name="start_date"]').val();
		if(startdate != '')
		{
			loadOrderData();
		}
	}
	else if(date_type == 'date_range')
	{	
		document.getElementById("labelstart").style.display="block";
		document.getElementById("labelstart").innerHTML = "From";	
		document.getElementById("start_date").style.display="block";	
		document.getElementById("labelend").style.display="block";
		document.getElementById("end_date").style.display="block";
		startdate = $('input[name="start_date"]').val();
		enddate = $('input[name="end_date"]').val();	
		if(startdate != '')
		{
			if(enddate != '')
			{
				loadOrderData();
			}	
		}			
	}		
}


	</script>
    <script type="text/javascript" src="./jquery/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./jquery/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    </body>
</html>

	

