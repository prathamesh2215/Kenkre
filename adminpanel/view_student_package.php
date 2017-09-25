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
  height: 200px;
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
                            
                                  <?php
									    $add =checkFunctionalityRight($filename,0);
										$edit =checkFunctionalityRight($filename,1);
										if(($add) || ($edit))
										{
											?>                                  
            				      <div class="box box-color box-bordered">
                                    
                                  
                                </div>
                                
			                	   <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Student Data
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                     <?php
                                $add = checkFunctionalityRight($filename,0);
                                if(true)
                                {
                                    ?>
                                    <!--<button type="button" class="btn-info" onClick="addMoreStd('','add')" ><i class="icon-plus"></i>&nbspAdd Student</button>-->
                                    <?php		
                                }
                            ?>
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
                                                <div id="container3" class="data_container">
                                                    <div class="data"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 	}	?>
                            
                                  <?php
									    $add =checkFunctionalityRight($filename,0);
										$edit =checkFunctionalityRight($filename,1);
										if(($add) || ($edit))
										{
										?>                                  
            				      <div class="box box-color box-bordered">
                                    
                                  
                                </div>
                                
			                	   <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Upload Student Data
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    	<div id="req_resp1"></div>
                                        <div class="profileGallery">
                                            <div style="width:99%;" align="center">
                                                <div id="loading"></div>                                            
                                                <div id="contain" class="data_container">
                                                <form method="post" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data" id="frm_stud_excel">
      		   										  
                                                            
                                                            
                                                            
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
                                                    <div class="data"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 	}	?>
                                
                                
                                
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
                 
                    
				<div id="div_add_student" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Student',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Student
                                        </h3>
                                    <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload()" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_student" class="form-horizontal form-bordered form-validate" >
                                        <input type="hidden" name="add_student_req" value="1">
                                        <div id="div_add_package_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Branch -->
                
                
                <div id="view_product" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Customer Product',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                              <div class="box box-color box-bordered" >
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                          Customer's Product
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtoview();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    <div style="padding:10px 15px 10px 15px !important">
                                                                            
										<br>
                                    	<input type="hidden" name="hid_page1" id="hid_page1" value="1">
                                        <select name="rowlimit1" value="" id="rowlimit1" onChange="view_products(this.value);"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "srch1" name="srch1" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                </div> <!-- Add Branch -->
                
                
                
				<div id="div_edit_student" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Student ',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Student
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_edit_student');" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_student" class="form-horizontal form-bordered form-validate" >
                                         <input type="hidden" name="edit_student_req" id="edit_student_req" value="1">
                                        <div id="div_edit_student_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Branch -->
                
				<div id="div_error_package" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Update Error Package',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Error Branch
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_branch_error" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_error_package_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- error Branch -->   
                
                
                
                
            	<div id="div_view_student_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Student Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                          Student Detail
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
		
		
		function multipleDelete()
		{			
			loading_show();		
			var students = [];
			$(".students:checked").each(function ()
			{
				students.push(parseInt($(this).val()));
			});
			
			if (students.length == 0)
			{
				alert("Please select checkbox to delete Student");				
			}
			else
			{
				var sendInfo 	= {"students":students, "delete_student":1};
				var delete_school 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_student_package.php?",
					type: "POST",
					data: delete_school,
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
								
								
						loading_hide();	
						//$("#model_body").html('<span style="style="color:#F00;">Excel Successfully Uploaded</span>');
						//$('#error_model').modal('toggle');	
						alert('Student Data Successfully Uploaded');							
											
						
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
		
		function addMoreStd(stud_pkg_id,req_type)
		{   
		    loading_show();
			$('#div_view_package').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_student').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_student').css("display", "block");	
				$('.select2-me').select2();		
			}	
			else if(req_type == "error")
			{
				$('#div_error_package').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_student_details').css("display", "block");				
			}		
		    			
			var sendInfo = {"stud_pkg_id":stud_pkg_id,"student_req_type":req_type,"load_student_add_part":"1"};
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
								$("#div_edit_student_part").html(data.resp);	
											
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
			
			//loadData();
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
	
		
		// end multiple delete		

		// getProducts 2 by satish on 9 nov 2016
		
					
			

	
		
		
		
		  
	
		   
	    function view_products(cust_id,school_id)
		{   
		        loading_show();
				$("#div_view_package").css("display","none");
			    $("#view_product").css("display","block"); 
		        var row_limit = $.trim($('select[name="rowlimit1"]').val());
			    var page = $.trim($("#hid_page1").val());
			    var search_text = $.trim($('#srch1').val());	
				var sendInfo 		= {"school_id":school_id,"search_text":search_text,"cust_id":cust_id,"row_limit":row_limit,"page":page, "view_products":1};
				
				var child_load 	= JSON.stringify(sendInfo);			
				$.ajax({
					url: "load_stud_pkg_products.php",
					type: "POST",
					data: child_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{				
						data = JSON.parse(response);
					
						if(data.Success == "Success") 
						{    
							$("#container2").html(data.resp);
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{
							$("#container2").html('<span style="style="color:#F00;">'+data.resp+'</span>');														
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
		
		function backtoview()
		{
			$("#div_view_package").css("display","block");
			$("#view_product").css("display","none"); 
			loadData1();
		}
		
		$('#frm_add_student').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_add_student').valid())
			{       loading_show();
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
							    $("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal({
									backdrop: 'static'
								});
								
								loading_hide();
								$('.btn-default').on('click', function()
								{	
									
									location.reload();
								});
							    
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
		
		$('#frm_edit_student').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_edit_student').valid())
			{       loading_show();
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
							    $("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal({
									backdrop: 'static'
								});
								
								loading_hide();
								$('.btn-default').on('click', function()
								{	
									
									location.reload();
								});
							    
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
		</script>
 <script type="text/javascript" src="./jquery/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./jquery/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    </body>
</html>