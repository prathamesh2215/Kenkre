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
                breadcrumbs($home_url,$home_name,'View Coupons',$filename,$feature_name); 
                /* this function used to add navigation menu to the page*/ 
                ?>          
				<div class="row-fluid" id="all_orders" style="display:block;">
					<div class="span12">
						<div class="box box-color box-bordered">
							<div class="box-title">
                            	<h3><i class="icon-table"></i><?php echo $feature_name; ?></h3>
                                
                                
                                        
                                        <div style="float:right; margin-right:20px;display:inline-block"> 
                                        
                                        	 <div style="float:left;">                                                 
                                        		<input type="radio" checked name="type_times_use"  id="" size="18" onChange="loadCouponData();" value="All"  class="form-control  input-small"  data-rule-required="true" style="height:22px;width:%" >&nbsp;All
											</div>
                                            
                                            <div style="float:left;">                                                              
												&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type_times_use" value="one_time_use" id="" onChange="loadCouponData();"   class="form-control  input-small"  data-rule-required="true" style="height:22px;width:%">&nbsp;Once Time
                                        	 </div>
                                        	
                                            <div style="float:left;">                                                 
                                        		&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type_times_use" id="" size="18" onChange="loadCouponData();" value="limited_use"  class="form-control  input-small"  data-rule-required="true" style="height:22px;width:%" >&nbsp;Limited
											</div>
                                            <div style="float:left;">                                                 
                                        		&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type_times_use" id="" size="18" onChange="loadCouponData();" value="unlimited_use"  class="form-control  input-small"  data-rule-required="true" style="height:22px;width:%" >&nbsp;Unlimited
											</div>
                                        </div>
                                        
                            </div> <!-- header title-->
                            <div class="box-content nopadding">
                                <div style="padding:10px 15px 10px 15px !important">
                      <button type="button" id="btn_download_excel" style="display:none" class="btn-info" onClick="download_xls()" >&nbspDownload Excel</button>                
                                  
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
                
                
                <div class="row-fluid" id="coupon_users_details" style="display:none;">
					<div class="span12">
						<div class="box box-color box-bordered">
							<div class="box-title">
                            	<h3><i class="icon-table"></i>Coupon Uses Detail<?php //echo $feature_name; ?></h3>
								<button type="button" class="btn-info_1" style= "float:right" onClick="backToMain('coupon_users_details','all_orders'),loadCouponData()" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                            </div> <!-- header title-->
                            <div class="box-content nopadding">
                            	<div style="padding:10px 15px 10px 15px !important">
                                </div>
                                <div class="profileGallery">
                                    <div style="width:99%;" align="center">
                                        <div id="loading"></div>                                            
                                        <div id="users_detail" class="data_container container">
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
		function loadCouponData()
		{  
		        var type_times_use = $('input[name=type_times_use]:checked').val();
			    loading_show();
			
				var sendInfo 	= {"type_times_use":type_times_use,"coupon_report":1};
				var desg_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_coupon_report.php?",
					type: "POST",
					data: desg_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{   
					   // alert(response);
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{ 
							loading_hide();
							$('#btn_download_excel').css('display','block');
							$("#container1").html(data.resp);
							loading_hide();
						} 
						else
						{	$('#btn_download_excel').css('display','none');						
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
		
		function download_xls()
		{
			var type_times_use = $('input[name=type_times_use]:checked').val();
			loading_show();
			var sendInfo 	= {"type_times_use":type_times_use,"exportToXlsx":1};
			//var sendInfo 	= {"start_date":start_date,"end_date":end_date};
			var mail_send 	= JSON.stringify(sendInfo);				
			$.ajax({
					url: "load_coupon_report.php?",
					type: "POST",
					data: mail_send,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{   
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							window.location.href = data.resp;
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
		
		function backToMain(div_hide,div_show)
		{
			$('#'+div_hide).css('display','none');
			$('#'+div_show).css('display','block');
		}
		
		function viewUsers(coup_id)
		{
			$('#all_orders').css('display','none');
			$('#coupon_users_details').css('display','block');
			
			loading_show();
			var sendInfo 	= {"coup_id":coup_id,"getCouponUsers":1};
			//var sendInfo 	= {"start_date":start_date,"end_date":end_date};
			var mail_send 	= JSON.stringify(sendInfo);				
			$.ajax({
					url: "load_coupon_report.php?",
					type: "POST",
					data: mail_send,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{   
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$('#users_detail').html(data.resp);
							loading_hide();
						} 
						else
						{							
							$('#users_detail').html(data.resp);
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
			loadCouponData();
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


<script type="text/javascript">
$('.datepicker').datepicker({
   
   changeMonth	: true,
		changeYear	: true,
		dateFormat	: 'yy-mm-dd',
		yearRange 	: 'c:c',//replaced "c+0" with c (for showing years till current year)
		maxDate		: new Date(),
});



</script>
    </body>
</html>

	

