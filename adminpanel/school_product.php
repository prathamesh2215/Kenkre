
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
<link href="./bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<style>

.scroll {
  border: 0;
  border-collapse: collapse;
}

.scroll tr {
  display: flex;
}

.scroll td {
 
  flex: 0.6;

  width: 1px;
  word-wrap: break;
}

.scroll thead tr:after {
  content: '';
  overflow-y: scroll;
  visibility: hidden;
  height: 0;
}

.scroll thead th {
  flex: 0.6;
  display: block;
  
}

.scroll tbody {
  display: block;
  overflow-y: auto;
 max-height: 200px;
}
.btn-showmore {
	width:140px;
    color: #fff;
    background-color: #18BB7C;
    border: 1px solid #18BB7C;
    padding: 2px 12px;
}
  .btn-showmore:hover{
    color: #18BB7C;
    background-color: #fff;
    border: 1px solid #18BB7C;
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
                <div class="container-fluid" id="div_view_package">                
	<?php 
	/* this function used to add navigation menu to the page*/ 
	breadcrumbs($home_url,$home_name,'Student Package',$filename,$feature_name);  
	/* this function used to add navigation menu to the page*/ 
	?>          
                        <div class="row-fluid">
                            <div class="span12">
                            
                                
                            
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                           School Products
                                            <?php //echo $feature_name; ?>
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                 <div class="box-content nopadding">
                                    <div style="padding:10px 15px 10px 15px !important">
                                    <?php
								$add = checkFunctionalityRight($filename,0);
									    
										if($add)
										{
											?>
                                            <button type="button" class="btn-info" onClick="addMorePkg('','add')" ><i class="icon-plus"></i>&nbsp;Add School Products</button>
  											<?php		
										}
									?>                                         
										<br>
                                    	<input type="hidden" name="hid_page" id="hid_page" value="1">
                                        <select name="rowlimit" id="rowlimit" onChange="loadData();"  class = "select2-me">
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
                                
                              
                                
                                <?php
									    $add =checkFunctionalityRight($filename,0);
										$edit =checkFunctionalityRight($filename,1);
										if(($add) || ($edit))
										{
											?>                                  
            				      
                                
			                	   <div class="box box-color box-bordered" style="display:none">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Incomplete Entries For Student Info
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<div style="padding:10px 15px 10px 15px !important">
                                            <input type="hidden" name="hid_page1" id="hid_page1" value="1">
                                            <input type="hidden" name="branch_parent1" id="branch_parent1" value="Parent">
                                            <select name="rowlimit1" id="rowlimit1" onChange="loadData1();"  class = "select2-me">

                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> entries per page
                                            <input type="text" class="input-medium" id="srch0" name="srch0" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
                                        </div>
                                        <div id="req_resp1"></div>
                                        <div class="profileGallery">
                                            <div style="width:99%;" align="center">
                                                <div id="loading"></div>                                            
                                                <div id="container7" class="data_container">
                                                    <div class="data"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 	}	?>
                                
                            </div>
                        </div>
                    </div> <!-- View Branch -->
				<div id="div_add_package" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Package',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                           Add School Products
                                        </h3>
                                    <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_add_package');" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_package" class="form-horizontal form-bordered form-validate" >
                                        <input type="hidden" name="insert_req" value="1">
                                        <div id="div_add_package_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Branch -->
				<div id="div_edit_package" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Package ',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit School Products
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_edit_package');" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_pkg" class="form-horizontal form-bordered form-validate" >
                                         <input type="hidden" name="hid_page2" id="hid_page2" value="1">
                                        <div id="div_edit_package_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Branch -->
				
                
                
                
                
            	<div id="div_view_package_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Package Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                          School Product  Detail
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_branch_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_package_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- view Branch -->                    
            </div>
        </div>
		<?php getloder();?>
        <?php ?>
        <script type="text/javascript">
		
		$('#frm_stud_excel').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_stud_excel').valid())
			{  
				loading_show();	
				$.ajax({
						url: "load_student_package.php?",
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
								
								
								
						$("#model_body").html('<span style="style="color:#F00;">Excel Successfully Uploaded</span>');							
						$('#error_model').modal('toggle');						
						loading_hide();
						window.location.assign("view_student_package.php?pag=<?php echo $title; ?>");
						loading_hide();
							} 

							else 
							{
								$("#req_resp").html('<span style="style="color:#F00;">'+data.resp+'</span>');	
								loading_hide();						
							}
						},
						error: function (request, status, error) 
						{
							//alert(request.responseText);
						},
						complete: function()
						{
							//alert("complete");
                		}
				    });
			}
		});
		
		function loadData()
		{
			loading_show();
			row_limit = $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page = $.trim($("#hid_page").val());
			
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
				$('#error_model').modal('toggle');	
				loading_hide();			
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_package":1, "page":page};
				var branch_load 	= JSON.stringify(sendInfo);	
							
				$.ajax({
					url: "load_student_package.php",
					type: "POST",
					data: branch_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{				
						data = JSON.parse(response);
					//	loadData1();
						if(data.Success == "Success") 
						{    
							$("#container1").html(data.resp);
							
							//loading_hide();
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
						//alert("complete");
						loading_hide();
                	}
			    });
			}
		}
		// load data1 start here
		function loadData1()
		{
			loading_show();
	
			row_limit = $.trim($('select[name="rowlimit1"]').val());
			search_text = $.trim($('#srch0').val());
			page = $.trim($("#hid_page1").val());
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
				$('#error_model').modal('toggle');	
				loading_hide();			
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_students":1, "page":page};
				var branch_load 	= JSON.stringify(sendInfo);	
							
				$.ajax({
					url: "load_student_package.php",
					type: "POST",
					data: branch_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{		
						data = JSON.parse(response);
						
						if(data.Success == "Success") 
						{    
							$("#container3").html(data.resp);
							//loading_hide();
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
						//alert("complete");
						loading_hide();
                	}
			    });
			}
		}
		function changeStatus(package_id,curr_status)
		{
			loading_show();
			if(package_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{   
			    
				var sendInfo 	= {"package_id":package_id, "curr_status":curr_status, "change_status":1};
				var package_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_student_package.php?",
					type: "POST",
					data: package_status,
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
						//alert("complete");
						loading_hide();
                	}
			    });						
			}
		}	
		function addMorePkg(package_id,req_type)
		{   
			$('#div_view_package').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_package').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_package').css("display", "block");	
				$('.select2-me').select2();		
			}	
			else if(req_type == "error")
			{
				$('#div_error_package').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_package_details').css("display", "block");				
			}		
							
			var sendInfo = {"package_id":package_id,"req_type":req_type,"load_add_pkg_part":"1"};
			var camp_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_student_package.php",
					type: "POST",
					data: camp_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{ 
					    data = JSON.parse(response);
					
						if(data.Success == "Success") 
						{   
							if(req_type == "add")
							{
								$("#div_add_package_part").html(data.resp);
							}
							else if(req_type == "edit")
							{   
								$("#div_edit_package_part").html(data.resp);	
											
							}	
							else if(req_type == "error")
							{
								$("#div_error_package_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_package_part").html(data.resp);
							}
						//	$('#prod_cat_id_list1').select2();
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
						//alert("complete");
						loading_hide();
                	}
				});			
		} 
		
		$( document ).ready(function() {
			$('#srch').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page").val("1");
       			   	loadData();
				}
			});
			$('#srch0').keypress(function(e) 
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
			$edit =checkFunctionalityRight($filename,1);
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
        //for multiple delete
	 function multipleDelete()
		{
			loading_show();
			var packges = [];
			$(".packges:checked").each(function ()
			{
				packges.push(parseInt($(this).val()));
			});
			if (typeof packges.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to delete Packeges</span>');
				$('#error_model').modal('toggle');						
			}
			else
			{
			    var sendInfo 	= {"packages":packges, "delete_package":1};
				var del_camp 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_student_package.php?",
					type: "POST",
					data: del_camp,
					contentType: "application/json; charset=utf-8",						
					async:true,					
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
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			            $('#error_model').modal('toggle');
					}
				});					
			}
		}
		
		// end multiple delete		

		// getProducts 2 by satish on 9 nov 2016
		function getProducts(req)
		{    
			loading_show();
			
			stud_pkg_info_id = $('#stud_pkg_info_id').val();
			
			if(req==0)
			{
			$("#hid_page2").val(0);
			}
			
			var page = $.trim($("#hid_page2").val());
			var cat_id	=$('#prod_cat_id_list').val();
			
			var org_id	=$('#prod_org_id_list').val();
			var brand_id	=$('#prod_brand_id_list').val();
			var grade	=$('#grade').val();
			var cat_txt	=$('#cat'+cat_id).text();
		    var org_txt	=$('#org'+org_id).text();
			var brand_txt	=$('#brand'+brand_id).text();
			var grade_txt	=$('#grade'+grade).text();
			
		
			var search_text = $.trim($('#srchpkg_prod').val());
		
			if(cat_id ==""  && org_id=="" && search_text=="" && brand_id =="" && grade =="")
			{
			  
				$("#model_body").html('<span style="style="color:#F00;">Please Select Filter</span>');
				$('#error_model').modal('toggle');				
				loading_hide();
				return false;
			}
			
			
			//brand_txt	=$('#brand'+brand_id+"sec"+section_id).text();
			var sendInfo		= {"grade":grade,"grade_txt":grade_txt,"req":req,"cat_txt":cat_txt,"org_txt":org_txt,"brand_txt":brand_txt,"stud_pkg_info_id":stud_pkg_info_id,"row_limit":row_limit,"org_id":org_id,"brand_id":brand_id,"cat_id":cat_id,"filter":1,"search_text":search_text,"page":page};
				var filter_data = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_student_package.php?",
					type: "POST",
					data: filter_data,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{    //alert(response);
						 data = JSON.parse(response);
				         
						//$('#srch').val(" ");
						//$('#check_prod').val(1);
			           // $("#prod_cat_id_list").select2("val", ""); 
					  //  $("#prod_subcat_id_list").select2("val", ""); 
			           // $("#prod_org_id_list").select2("val", ""); 
			           // $("#prod_brand_id_list").select2("val", "");   
						//$("#prod_subcat_id_list"+section_id).html(""); 
						if(data.Success == "Success") 
						{   
						  
						    $("#save_sec_btn").css("display","block");
						    $("#product_table").css("display","block");
							$("#hid_page2").val(data.page);
							if(req==0)
							{
						      $("#product_table").html(data.resp);
							}
							else
							{     
								  $("#show_more").append(data.resp);
							}
							if(data.product_count < 20)
							{
								$("#show_more_btn").css("display","none");
							}
							else
							{
								$("#show_more_btn").css("display","block");
							}
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{   $("#product_table").css("display","block");
						    $("#product_table").html(data.resp);
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
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			            $('#error_model').modal('toggle');
					}
				});
			
		}// end get products 2
		function submitfrm(){
			$( "#frm_add_package" ).submit();
			}
			
			

		$('#frm_add_package').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_add_package').valid())
			{
				    loading_show();	
					var prod_check =  $('#check_prod').val();
					var start_date =  new Date($('#start_date').val());
				    var end_date   = new Date($('#end_date').val());
					/*if(start_date < end_date)
					{
						        $("#model_body").html('<span style="style="color:#F00;">Please Select Correct Date</span>');
								$('#error_model').modal('toggle');	
								loading_hide();		
								return false;	
					}*/
					
					
				   
						var sel_prod = [];
		              	$(".sel_prod:checked").each(function ()
			             {
							sel_prod.push(parseInt($(this).val()));
						 });
						if (sel_prod.length == 0)
							{
								$("#model_body").html('<span style="style="color:#F00;">Please Select Products</span>');
								$('#error_model').modal('toggle');	
								loading_hide();		
								return false;			
							}
					
				    $.ajax({
					url: "load_student_package.php?",
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
							    $("#model_body").html('<span style="style="color:#F00;">Product Successfully Added</span>');
								$('#error_model').modal('toggle');	
								loading_hide();
							$('#div_add_package').html("");
								
								
					            $('#div_add_package').css("display", "none");
							    addMorePkg(data.pakage_id ,'edit',1);							
								//$("#sections").html(data.resp);
								$('#reg_submit_add').css("display", "none");	
								//$('select').select2();
								loading_hide();
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
		});//add
		
		$('#frm_edit_pkg').on('submit', function(e) {
			
			e.preventDefault();
			if ($('#frm_edit_pkg').valid())
			{   loading_show();
			    var product_count = $('#product_count').val();
				
			    if(product_count==0 || product_count=="" )
                {   
				
				    var sel_prod = [];
		            $(".sel_prod:checked").each(function ()
			        {
					sel_prod.push(parseInt($(this).val()));
					});
					if (sel_prod.length == 0)
				    {
						$("#model_body").html('<span style="style="color:#F00;">Please Select Products</span>');
						$('#error_model').modal('toggle');	
						loading_hide();		
						return false;			
					}
				   
				}
			
		
				$.ajax({
					url: "load_student_package.php?",
					type: "POST",
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					async:true,						
						success: function(response) 
						{  
							//alert(response);
						    data = JSON.parse(response);
							if(data.Success == "Success") 
							{  
						
							    //$("#model_body").html('<span style="style="color:#F00;">Package Successfully Updated</span>');
							    //$('#error_model').modal('toggle');
									
								loading_hide();
								var req = $("#submit_req").val();
								if(req==1)
								{   alert('Product Successfully Submitted');
									location.reload();
								}
								else
								{  alert('Product Successfully Updated');
									addMorePkg(data.stud_pkg_info_id,'edit',0)
								   
								}
								
							} 
							else 
							{   
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								loading_hide();
								if(data.image_fail == 1){
								$('#div_add_package').css("display", "none");
								//addMorePkg(data.campaign_id ,'edit',data.section_num);	
								}						
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
		});//edit
		
		  
		
		function getSubcat(cat_id)
		{   
			loading_show();
			if(cat_id == "")
			{
				$("#prod_subcat_id_list").select2("val", "");
				$("#prod_subcat_id_list").html("");
				loading_hide();				
			}
			else
			{   
				var sendInfo		= {"cat_id":cat_id,"getsubcat":1};
				var filter_data = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: filter_data,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{  
					    data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#prod_subcat_id_list").html(data.resp);
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#container3").html('<span style="style="color:#F00;">'+data.resp+'</span>');														
							loading_hide();									
						}
					},
				});
			}
			
		}// get subcat
		   
	 
		
		
			
		
	    function remove_prod(package_id)
	     {  
			loading_show();
			var delete_data = [];
			
			$(".del_prod:checked").each(function ()
			{
				delete_data.push(parseInt($(this).val()));
			});
			
			if (delete_data.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to Remove Products</span>');
				$('#error_model').modal('toggle');
				loading_hide();						
			} 
			
			else
			{
				var sendInfo 	= {"package_id":package_id,"delete_data":delete_data, "remove_products":1};
				var remove_products 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_student_package.php?",
					type: "POST",
					data: remove_products,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{	data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
						    loading_hide();
						    addMorePkg(package_id,'edit',0);
							
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
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
			            $('#error_model').modal('toggle');
					}
				});				
			}
			}
			
	 
			function searchProduct(e)
			{ 
			 var unicode=e.charCode? e.charCode : e.keyCode
			  if (unicode ==13)
			  {  
			    getProducts(0);
			   return false;
			  
              }	
			}
		
		</script>
        <script type="text/javascript">
           function charsonly(e)
		 {
  			  var unicode=e.charCode? e.charCode : e.keyCode
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
     		  if ( unicode<65||unicode>90 && unicode<97||unicode>122  )  //if not a number
          	  return false //disable key press
              }
		 }
		 function numsonly(e)
		 {
  			  var unicode=e.charCode? e.charCode : e.keyCode
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
     		  if ( unicode<48||unicode>57  )  //if not a number
          	  return false //disable key press
              }
		}
		function backtomain(div_id)
		{   
		    loading_show();
			$('#div_view_package').css("display", "block");
			$('#'+div_id).css("display", "none");
			loading_hide();
		}
		
		</script>
        <script type="text/javascript">
		function check_req()
		{   
			 $("#submit_req").val(1);
			
		}
		</script>
 <script type="text/javascript" src="./jquery/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./jquery/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    </body>
</html>