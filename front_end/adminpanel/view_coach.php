<?php
include("include/db_con.php");
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Coach";
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
<link rel="stylesheet" href="css/datepicker.css" />
<style>
.head2
{
	font-size:17px;
}
</style>
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
                <div class="container-fluid" id="div_view_area">                
					<?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'View Coach',$filename,$feature_name); 
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
															<button type="button" class="btn-info" onClick="addMoreArea('','add')" ><i class="icon-plus"></i>&nbspAdd Coach</button>
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
                                        <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Search by Coach Name, Email, Mobile"  style="float:right;margin-right:10px;margin-top:10px;width:300px" >
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
                    </div>  <!-- view Area -->
                <div class="container-fluid" id="div_add_area" style="display:none">                
					<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Add Coach',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
					?>           
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Coach
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backToMain('div_add_area','div_view_area');loadData();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">                                     
                                    	<form id="frm_coach_add" class="form-horizontal form-bordered form-validate" >
                                        	<div id="div_add_area_part">
                                        	</div>                                    
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
             	</div> <!-- Add Area -->
                <div class="container-fluid" id="div_edit_area" style="display:none">   
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Coach',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>                                    
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Coach
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backToMain('div_edit_area','div_view_area');loadData();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <form id="frm_coach_edit" class="form-horizontal form-bordered form-validate" >
                                            <div id="div_edit_area_part">
                                            </div>                                    
                                        </form>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Edit Area -->                                
                       
                <div class="container-fluid" id="div_view_area_details" style="display:none">                
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'View Coach Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>        
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                      <!--  <h3>
                                            <i class="icon-table"></i>
                                            Coach Details
                                        </h3>-->
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backToMain('div_view_area_details','div_view_area');loadData();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <form id="frm_view_area_details" class="form-horizontal form-bordered form-validate" >
                                            <div id="div_view_area_details_part">
                                            </div>                                    
                                        </form>  
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div> <!-- View Details Area -->                      
                </div>
            </div>
        </div>
            <?php getloder();?>
        <script type="text/javascript">
		
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
			
			<?php
			if(isset($_REQUEST['coach_id']) && $_REQUEST['coach_id']!='')
			{?>
			    addMoreArea(<?php echo $_REQUEST['coach_id']; ?>,'view')
			<?php 
			}
			else
			{
			?>
			loadData();
			<?php 
			} 
			?>
			
			<?php
			$add = checkFunctionalityRight($filename,0);
			$edit = checkFunctionalityRight($filename,1);
			if($add || $edit)
			{
			?>	
			//loadData1();
			<?php
			}
			
			
			if(isset($_REQUEST['coach_id']) && $_REQUEST['coach_id']!='')
			{?>
			
			addMoreArea(<?php echo $_REQUEST['coach_id']; ?>,'view');
			
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
		});  /*Search Area*/
		
		
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
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to delete Area</span>');
				$('#error_model').modal('toggle');
				loading_hide();						
			}
			else
			{
				delete_coach 	= 1;
				var sendInfo 	= {"batch":batch, "delete_coach":1};
				var del_cat 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coach.php?",
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
		}    /*Delete Area*/
		
		function loadData()
		{
			loading_show();
			row_limit   = $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page        = $.trim($("#hid_page").val());								
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');
				loading_hide();							
			}
			else
			{
				var sendInfo = {"row_limit":row_limit, "search_text":search_text, "load_coach":1, "page":page};
				var ind_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_coach.php?",
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
		}   /*Load Data*/
		
		function addMoreArea(area_id,req_type)
		{
			$('#div_view_area').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_area').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_area').css("display", "block");				
			}	
			else if(req_type == "view")
			{
				$('#div_view_area_details').css("display", "block");				
			}							
			var sendInfo = {"area_id":area_id,"req_type":req_type,"load_coach_parts":1};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_coach.php?",
					type: "POST",
					data: cat_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#div_add_area_part").html(' ');
							$("#div_edit_area_part").html(' ');	
							$("#div_view_area_details_part").html(' ');
										
							if(req_type == "add")
							{
								$("#div_add_area_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_area_part").html(data.resp);				
							}	
							else if(req_type == "view")
							{
								$("#div_view_area_details_part").html(data.resp);
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
		}		/*Add more area*/
		
		function changeStatus(id,curr_status)
		{
			loading_show();
			if(id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">User id or Status to change not available</span>');
				$('#error_model').modal('toggle');				
				loading_hide();
			}
			else
			{
				var sendInfo 	= {"id":id, "curr_status":curr_status, "change_status":1};
				var area_status = JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coach.php?",
					type: "POST",
					data: area_status,
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
		}	/*Change Status*/
		
		function isNumberKey(evt)
		{
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
				
		
		
		
		$('#frm_coach_add').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_coach_add').valid())
			{
				start_date = $('#contract_start_date').val();
				start_date = start_date.split('-');
				start_date = start_date[2]+'-'+start_date[1]+'-'+start_date[0];
				
				end_date   = $('#contract_end_date').val();
				end_date = end_date.split('-');
				end_date = end_date[2]+'-'+end_date[1]+'-'+end_date[0];
				var x = new Date(start_date);
                var y = new Date(end_date);
				
				if(y < x)
				{
					alert('Contract End date should be greater than Contract start date...!');
					return false;
				}
				
				$.ajax({
						url: "load_coach.php",
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
								alert(data.resp);
								
								$('#div_view_area').css("display", "block");
								$('#div_add_area').css("display", "none");				
								$('#div_edit_area').css("display", "none");				
								$('#div_view_area_details').css("display", "none");		
								loadData();		
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
		});	/* Add Area*/
		
		$('#frm_coach_edit').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_coach_edit').valid())
			{
				start_date = $('#contract_start_date').val();
				end_date   = $('#contract_end_date').val();
				if(start_date !="" && end_date!="")
				{
					start_date = start_date.split('-');
					start_date = start_date[2]+'-'+start_date[1]+'-'+start_date[0];
					
					
					end_date = end_date.split('-');
					end_date = end_date[2]+'-'+end_date[1]+'-'+end_date[0];
					var x = new Date(start_date);
					var y = new Date(end_date);
					
					if(y < x)
					{
						alert('Contract End date should be greater than Contract start date...!');
						return false;
					}
				}
				
				
				$.ajax({
						url: "load_coach.php",
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
								alert(data.resp);
								
								$('#div_view_area').css("display", "block");
								$('#div_add_area').css("display", "none");				
								$('#div_edit_area').css("display", "none");				
								$('#div_view_area_details').css("display", "none");	
									
								loadData();
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
		}); /* Edit Area*/
				
				
		function charsonly(e)
		{
  			  var unicode=e.charCode? e.charCode : e.keyCode
			 
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
				  if (unicode<65||unicode>90 && unicode<97||unicode>122  )  //if not a number
				  return false //disable key press
              }
		}
		
		function backToMain(close_div,show_div)
		{
			$('#'+close_div).css('display','none');
			$('#'+show_div).css('display','block');
		}
		
		function getState(country_id)
		{
			if(country_id=="")
			{
				alert('Please select country...!');
				return false;
			}
			var sendInfo 	= {"country_id":country_id,"getState":1};
			var area_status = JSON.stringify(sendInfo);								
			$.ajax({
				url: "load_city.php?",
				type: "POST",
				data: area_status,
				contentType: "application/json; charset=utf-8",						
				success: function(response) 
				{			
					data = JSON.parse(response);
					if(data.Success == "Success") 
					{							
						$('#state_code').html(data.resp);
					} 
					else 
					{
						$('#state_code').select2();
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
		
		function getCityList(state_id,city_select_id)
		{
			if(state_id=="")
			{
				alert('Please select State...!');
				return false;
			}
			var sendInfo 	= {"state_id":state_id,"getCity":1};
			var area_status = JSON.stringify(sendInfo);								
			$.ajax({
				url: "load_coach.php?",
				type: "POST",
				data: area_status,
				contentType: "application/json; charset=utf-8",						
				success: function(response) 
				{			
					data = JSON.parse(response);
					$('#city').prop('disabled',false);
					if(data.Success == "Success") 
					{						
						$('#city').html(data.resp);
					} 
					else 
					{
						$('#state_code').select2();
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
		
		 function charsonly(e)
		 {
  			  var unicode=e.charCode? e.charCode : e.keyCode
			 
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
				  if (unicode<65||unicode>90 && unicode<97||unicode>122  )  //if not a number
				  return false //disable key press
              }
		}
		
		
		function numsonly(e)
		 {
  			  var unicode=e.charCode? e.charCode : e.keyCode
			 
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
				  if (unicode<48||unicode>57)  //if not a number
				  return false //disable key press
              }
		}
		
		function getState(country_id)
		{
			if(country_id=="")
			{
				alert('Please select country...!');
				return false;
			}
			var sendInfo 	= {"country_id":country_id,"getState":1};
			var area_status = JSON.stringify(sendInfo);								
			$.ajax({
				url: "load_city.php?",
				type: "POST",
				data: area_status,
				contentType: "application/json; charset=utf-8",						
				success: function(response) 
				{			
					data = JSON.parse(response);
					if(data.Success == "Success") 
					{							
						$('#state_code').html(data.resp);
					} 
					else 
					{
						$('#state_code').select2();
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
		
		function getArea(city_id)
		{
			if(city_id=="")
			{
				alert('Please select country...!');
				return false;
			}
			var sendInfo 	= {"city_id":city_id,"getArea":1};
			var area_status = JSON.stringify(sendInfo);								
			$.ajax({
				url: "load_coach.php?",
				type: "POST",
				data: area_status,
				contentType: "application/json; charset=utf-8",						
				success: function(response) 
				{			
					data = JSON.parse(response);
					if(data.Success == "Success") 
					{							
						$('#area').html(data.resp);
					} 
					else 
					{
						$('#area').select2();
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
		
		//==================== Start : For Image Preview======================//
		
		 function readURL(input)
	     {
			if (input.files && input.files[0])
			 {
				var reader = new FileReader();
				reader.onload = function (e) 
				{
					$('#coach_img_p').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
        }

		</script>     
			<script src="js/bootstrap-datepicker.js"></script>   
    </body>
</html>
