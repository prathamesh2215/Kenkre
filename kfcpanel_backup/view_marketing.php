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
<style>
.btn-pn {
	width:140px;
    color: #fff;
    background-color: #1295c9;
    border: 1px solid #18BB7C;
    padding: 2px 12px;
}
.btn-pn.active, .btn-pn.disabled, .btn-pn:active, .btn-pn:focus, .btn-pn:hover, .btn-pn[disabled] {
    color: #1295c9;
    background-color: #fff;
    border: 1px solid #1295c9;
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

</style>
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
                <div class="container-fluid" id="div_view_campaign">                
	<?php 
	/* this function used to add navigation menu to the page*/ 
	breadcrumbs($home_url,$home_name,'Marketing',$filename,$feature_name);  
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
                                    <div style="padding:10px 15px 10px 15px !important">
                                    <?php
								$add = checkFunctionalityRight($filename,0);
									    
										if($add)
										{
											?>
                                            <button type="button" class="btn-info" onClick="addMoreCamp('','add')" ><i class="icon-plus"></i>&nbsp;Create Campaign</button>
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
            				      <div class="box box-color box-bordered">
                                    
                                  
                                </div>
                                
			                	   <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Incomplete Entries For Campaign
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
                                                <div id="container3" class="data_container">
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
				<div id="div_add_campaign" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Create Campaign',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Create Campaign 
                                        </h3>
                                    <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_campaign" class="form-horizontal form-bordered form-validate" >
                                        <input type="hidden" name="insert_req" value="1">
                                    
                                        <div id="div_add_campaign_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Branch -->
				<div id="div_edit_campaign" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Campaign ',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Campaign 
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_camp" class="form-horizontal form-bordered form-validate" >
                                        <input type="hidden" name="hid_page2" id="hid_page2" value="1">
                                        <div id="div_edit_campaign_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Branch -->
				<div id="div_error_campaign" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Update Error Campaign',$filename,$feature_name); 
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
                                        <div id="div_error_campaign_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- error Branch -->   
            	<div id="div_view_campaign_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Campaign Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                           Campaign Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_branch_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_campaign_part">
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
		function save_section(section_id,req_type,cmp_info_id,submit_req)
		{			
			loading_show();		
			var no_of_section = $('#no_of_section').val();
			var banner_check = $('#banner_check').val();
			//banner_check = 3-banner_check ;
			if(banner_check ==0)
			{
				alert('Please Upload Banner Image First');
				loading_hide();
				return false;
			}
			var batch = [];
			$(".sel_prod"+section_id+":checked").each(function ()
			{
				batch.push(parseInt($(this).val()));
			});
			
			
			if(req_type == 1)
			{
				if(batch.length == 0)
				{
					$("#model_body").html('<span style="style="color:#F00;">Please Select Some Products</span>');
					$('#error_model').modal('toggle');
					loading_hide();
				    return false;
				}
			}
			
			if(req_type ==2)
			{
					  
					if(batch.length == 0)
				{
				   var current_prod = $('#current_prod_'+section_id).val();
				
			       if(current_prod ==0)
			      {
					$("#model_body").html('<span style="style="color:#F00;">Please Select Some Products</span>');
					$('#error_model').modal('toggle');
					loading_hide();
				    return false;
					
				  }
				}
					
				
			}
			
			
			var campaign_id =  $.trim($('#campaign_id').val());
			var section_slug =  $.trim($('#section_slug'+section_id).val());
			var section_name    =  $.trim($('#section'+section_id).val());
			if(section_name =="")
			{
				
				$("#model_body").html('<span style="style="color:#F00;">Please Enter Section Name</span>');
				$('#error_model').modal('toggle');
				loading_hide();
				return false;
		    }
	      
			else
			{ 
		var sendInfo = {"section_slug":section_slug,"submit_req":submit_req,"products":batch,"cmp_info_id":cmp_info_id, "add_product":1,"campaign_id":campaign_id,"section_name":section_name,"req_type":req_type};
				var del_branch 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_marketing.php",
					type: "POST",
					data: del_branch,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
						    if(submit_req==1)
							{  
							
							alert('Campaign Successfully Submitted');
							$("#model_body").html('<span style="style="color:#F00;">Campaign Successfully Submitted</span>');
							$('#error_model').modal('toggle');
						    loading_hide();
							location.reload();
							}
							
							
							
							
							$('#filter'+section_id).css("display", "none");
						    loading_hide();
							$('#div_add_branch').css("display", "none");
							addMoreCamp(campaign_id,'edit',section_id);
							
							
							
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
		}// save section end 
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
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_camp":1, "page":page};
				var branch_load 	= JSON.stringify(sendInfo);	
							
				$.ajax({
					url: "load_marketing.php",
					type: "POST",
					data: branch_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{					
						data = JSON.parse(response);
						
						if(data.Success == "Success") 
						{    
							$("#container1").html(data.resp);
							loadData1();
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
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_error":1, "page":page};
				var branch_load 	= JSON.stringify(sendInfo);	
							
				$.ajax({
					url: "load_marketing.php",
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
		function changeStatus(campaign_id,curr_status)
		{
			loading_show();
			if(campaign_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{   var sendInfo 	= {"campaign_id":campaign_id, "curr_status":curr_status, "change_status":1};
				var camp_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: camp_status,
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
		function addMoreCamp(campaign_id,req_type,section_no)
		{   
		  
			$('#div_view_campaign').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_campaign').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_campaign').css("display", "block");	
				$('.select2-me').select2();		
			}	
			else if(req_type == "error")
			{
				$('#div_error_campaign').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_campaign_details').css("display", "block");				
			}		
							
			var sendInfo = {"campaign_id":campaign_id,"section_no":section_no,"req_type":req_type,"load_add_camp_part":"1"};
			var camp_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_marketing.php",
					type: "POST",
					data: camp_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{ data = JSON.parse(response);
					 
						if(data.Success == "Success") 
						{   
							if(req_type == "add")
							{
								$("#div_add_campaign_part").html(data.resp);
								loading_hide();
							}
							else if(req_type == "edit")
							{   
								$("#div_edit_campaign_part").html(data.resp);	
											
							}	
							else if(req_type == "error")
							{
								$("#div_error_campaign_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_campaign_part").html(data.resp);
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
			
			var active_section 		= 1;
			//alert(active_section);
			$('#product_table'+active_section+' .pagination li.active').live('click',function()
			{  
			    var page = $(this).attr('p');
				$("#hid_page1").val(page);
			getProducts(active_section,1)	;			
			});
			
			$('#container3 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page2").val(page);
				loadData1();						
			});
		});
        //for multiple delete
	 function multipleDelete()
		{
			loading_show();
			var batch = [];
			$(".camp:checked").each(function ()
			{
				batch.push(parseInt($(this).val()));
			});
			if (typeof batch.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to delete Campaign-Specification</span>');
				$('#error_model').modal('toggle');						
			}
			else
			{
			    var sendInfo 	= {"camp":batch, "delete_campaign":1};
				var del_camp 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_marketing.php?",
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
		function resetfilter(section_id)
		{
			$('#srch'+section_id).val(" ");
			$(".select2-me").select2("val", ""); 
		}
       

		// getProducts 2 by satish on 9 nov 2016
		function getProducts(section_id,info_id,req)
		{    
		
			loading_show();
			
			//$('.select2-me').val('').trigger('change')
			//$('.select2-me').select2('refresh')
			if(req==0)
			{
			$("#hid_page2").val(0);
			}
			row_limit = $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch'+section_id).val());
			
			page = $.trim($("#hid_page2").val());
			cat_id	=$('#prod_cat_id_list'+section_id).val();
			
			org_id	=$('#prod_org_id_list'+section_id).val();
			brand_id	=$('#prod_brand_id_list'+section_id).val();
			cat_txt	=$('#cat'+cat_id+"sec"+section_id ).text();
			
			org_txt	=$('#org'+org_id+"sec"+section_id).text();
			
			//alert(cat_id);
			if(cat_id ==""  && org_id=="" && search_text=="" && brand_id =="")
			{
			  
				$("#model_body").html('<span style="style="color:#F00;">Please Select Filter</span>');
				$('#error_model').modal('toggle');				
				loading_hide();
				return false;
			}
			
			brand_txt	=$('#brand'+brand_id+"sec"+section_id).text();
			var sendInfo		= {"req":req,"cat_txt":cat_txt,"info_id":info_id,"org_txt":org_txt,"brand_txt":brand_txt,"section_id":section_id,"row_limit":row_limit,"org_id":org_id,"brand_id":brand_id,"cat_id":cat_id,"filter":1,"search_text":search_text,"page":page};
				var filter_data = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: filter_data,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{   //alert(response);
						data = JSON.parse(response);
						//alert(data.page);
						if(data.Success == "Success") 
						{   
						    $("#save_sec_btn"+section_id).css("display","block");
						    $("#product_table"+section_id).css("display","block");
							$("#hid_page2").val(data.page);
							if(req==0)
							{
						
						    $("#product_table"+section_id).html(data.resp);
							}
							else
							{     
								  $("#show_more"+section_id).append(data.resp);
							}
							
							if(data.product_count < 20)
							{
								$("#show_more_btn"+section_id).css("display","none");
							}
							else
							{
								$("#show_more_btn"+section_id).css("display","block");
							}
							
							loading_hide();
						} 
						else if(data.Success == "fail") 
						{    
						      if(req ==1)
							  {
								  alert('No more Product Available');
								  $("#show_more_btn"+section_id).css("display","none");
								  loading_hide();	
								  return false;
							  }
						     
						    $("#product_table"+section_id).css("display","block");
						    $("#product_table"+section_id).html(data.resp);
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
			$( "#frm_add_campaign" ).submit();
			}

		$('#frm_add_campaign').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_add_campaign').valid())
			{
				loading_show();	
				var campaign_name 		= $.trim($("#campaign_name").val());
				var file 		=1;// $.trim($("#file").val());
				var no_of_section 		= $.trim($("#no_of_section").val());					
				var cam_status 		= $('input[name=campaign_status]:checked', '#frm_add_campaign').val();		
				
				var inp = document.getElementById('file');
				if(inp.files.length > 3)
                {
					/*$("#model_body").html('<span style="style="color:#F00;">Max Banner limit is 3</span>');
					$('#error_model').modal('toggle');
					loading_hide();
					return false;*/
				}
				if(campaign_name == "" && no_of_section == "" && file == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
					$('#error_model').modal('toggle');	
				} 
				else
				{   
					
				    $.ajax({
					url: "load_marketing.php?",
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
							    $("#model_body").html('<span style="style="color:#F00;">Campaign Successfully Created</span>');
								$('#error_model').modal('toggle');	
								loading_hide();
							
							    $('#div_add_campaign').css("display", "none");
								addMoreCamp(data.campaign_id ,'edit',1);	
								
								
					            $('#div_add_campaign').css("display", "none");
							    addMoreCamp(data.campaign_id ,'edit',1);							
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
			}
		});//add
		
		$('#frm_edit_camp').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_edit_camp').valid())
			{    
			    var file_limit = $('#banner_check').val();
			    var inp = document.getElementById('new_file');
				
				if(inp.files.length > file_limit)
                {
					/*$("#model_body").html('<span style="style="color:#F00;">Max Banner limit is 3</span>');
					$('#error_model').modal('toggle');
					loading_hide();
					return false;*/
				}
				
				  var no_of_section = parseInt($('#no_of_section_old').val());
				  var nos_limit = parseInt($('#nos_limit').val());
				 
				  if(no_of_section < nos_limit)
				  {
					$("#model_body").html('<span style="style="color:#F00;">No of section should be greater or equal to '+nos_limit+'</span>');
					$('#error_model').modal('toggle');
					loading_hide();
					return false;
				  }
				  
			    
			    
				$.ajax({
					url: "load_marketing.php?",
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
							  
							    $("#model_body").html('<span style="style="color:#F00;">Campaign Successfully Updated</span>');
							    $('#error_model').modal('toggle');	
								loading_hide();
								
								addMoreCamp(data.campaign_id,'edit',data.section_num)
								loading_hide();
							} 
							else 
							{   
								$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$('#error_model').modal('toggle');	
								loading_hide();
								if(data.image_fail == 1){
								$('#div_add_campaign').css("display", "none");
								//addMoreCamp(data.campaign_id ,'edit',data.section_num);	
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
		
		  
		
		function getSubcat(section_id,cat_id)
		{   
			loading_show();
			if(cat_id == "")
			{
				
				
				$("#prod_subcat_id_list"+section_id).select2("val", "");
				var subcat ='<option value="">Select Sub Category</option>'; 
				$("#prod_subcat_id_list"+section_id).html("");
				loading_hide();				
			}
			else
			{   
				var sendInfo		= {"cat_id":cat_id,"getsubcat":1,"section_id":section_id};
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
							$("#prod_subcat_id_list"+section_id).html(data.resp);
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
		   
	     function delete_section(cmp_info_id,no_of_section,campaign_id,section_no)
	    {
			 
			 var r = confirm("Are you sure Want to delete this section!");
		
		var section_no = section_no -1;
		if(section_no == 0)
		{
			section_no =1;
		}
			if(r != true)
			{   loading_hide();
				return false;			
			}
			else
			{   
				var sendInfo		= {"cmp_info_id":cmp_info_id,"no_of_section":no_of_section,"campaign_id":campaign_id,"delete_section":1};
				var delete_data = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: delete_data,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{   data = JSON.parse(response);
						if(data.Success == "Success") 
						{
						  loading_hide();
						  addMoreCamp(campaign_id,'edit',section_no);
						} 
						else if(data.Success == "fail") 
						{
							$("#container3").html('<span style="style="color:#F00;">'+data.resp+'</span>');														
							loading_hide();									
						}
					},
				});
			}
		}
		
		function nxt_sec(section_id,req,no_of_section,campaign_id)
		{     
             var current_product 		= $("#current_prod_"+section_id).val();
			if(current_product==0 && req==1)
			 {
				$("#model_body").html('<span style="style="color:#F00;">Please Add some Products</span>');
				$('#error_model').modal('toggle');
				loading_hide();	 
				return false;
			 }
			
			 		
			 if(req==0)
			 { 
			 section_id = section_id -1;
			 }
			 else 
			 {
			 section_id = section_id +1;	   
			 }
			   addMoreCamp(campaign_id,'edit',section_id); 
			    
			 
				/*for(var i=1;i<=no_of_section;i++){
				if(i == section_id){
				$("#section_no_"+i).css("display","block");
				}
				else
				{
					 $("#section_no_"+i).css("display","none");}
				}
				
			     $( "#active_section").prop('value',section_id);*/
			
		}
			
		function delete_image(img_id,campaign_id,section_num)
		  {
			
			 var r = confirm("Are you sure Want to delete this Image!");
		
			 loading_show();
			if(r != true)
			{   loading_hide();
				return false;			
			}
			else
			{   
				var sendInfo		= {"img_id":img_id,"campaign_id":campaign_id,"delete_banner":1};
				var delete_data = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: delete_data,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{  
					    data = JSON.parse(response);
						if(data.Success == "Success") 
						{
						  loading_hide();
						  addMoreCamp(campaign_id,'edit',section_num);
						} 
						else if(data.Success == "fail") 
						{
							$("#container3").html('<span style="style="color:#F00;">'+data.resp+'</span>');														
							loading_hide();									
						}
					},
				});
			}
			}
			
	    function remove_prod(info_id,section_id,campaign_id)
	     {
			loading_show();
			var delete_data = [];
			
			$(".del_prod"+section_id+":checked").each(function ()
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
				var sendInfo 	= {"info_id":info_id,"delete_data":delete_data, "remove_products":1};
				var remove_products 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: remove_products,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{		
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	loading_hide();
						    addMoreCamp(campaign_id,'edit',section_id);
							
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
			
	 	function submit_section(section_no,file_limi,cmp_info_id)
		{    
			
			loading_show();		
			var no_of_section = $('#no_of_section').val();
			var banner_check = $('#banner_check').val();
			banner_check = 3-banner_check ;
			if(banner_check ==0)
			{
				alert('Please Upload Banner Image First');
				loading_hide();
				return false;
			}
			
			
			var section_name = $('#section'+section_no).val();
			if(section_name =="")
			{
				alert('Please Enter Section Name');
				loading_hide();
				return false;
			}
			
			
			save_section(section_no,2,cmp_info_id,1);
			
			

	}
	
		function searchProduct(e)
		{ 
			var unicode=e.charCode? e.charCode : e.keyCode
			if (unicode ==13)
			{  
				var active_section 		= $("#active_section").val();
				var info_id 		= $("#active_info_id").val();
				getProducts(active_section,info_id,0);
				return false;
			}	
		}
		
		
		function update_img_slug(slug,img_id)
		{
			    loading_show();
				var sendInfo 	= {"slug":slug,"img_id":img_id, "update_slug":1};
				var update_img_slug 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: update_img_slug,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{		
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
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
		
		function update_sec_order(order,section_id)
		{       
			    loading_show();
				var nos_limit  =$('#nos_limit').val();
				var campaign_id =$('#campaign_id').val();
			
				if(order > nos_limit || order < 1)
				{
					$("#model_body").html('<span style="style="color:#F00;">you can not enter grater than no of section or less than 0</span>');							
					$('#error_model').modal('toggle');
					loading_hide();
					return false;
				}
			
				var sendInfo 	= {"section_id":section_id,"order":order,"campaign_id":campaign_id, "update_section_order":1};
				var update_img_order 	= JSON.stringify(sendInfo);	
			
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: update_img_order,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{		
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
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
		
		</script>
        <script type="text/javascript">
           function charsonly(e)
		 {
  			  var unicode=e.charCode? e.charCode : e.keyCode
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
     		  if ( unicode<48||unicode>57 && unicode<65||unicode>90 && unicode<97||unicode>122  )  //if not a number
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
		
			function readURL(input)
			{  
			     
				var inp = document.getElementById('file');
				 $('#img1').html(' ');
				
				$('#img1').css('display','block');
				var i=0;
				for(var j=1;j<=inp.files.length ; j++)
                {  
			       var inner_data ='<input placeholder="Enter image '+j+' slug" type="text" id="img_slug'+i+'" name="img_slug'+i+'" class="input-large keyup-char"><br>';
				  $('#img1').append(inner_data);
				  i++;
				}
				
				/*for(var i=0;i<inp.files.length ; i++)
                {
			
				
				var fReader = new FileReader();
				fReader.readAsDataURL(inp.files[i]);
				fReader.onloadend = function(event)
				{
			    var img = document.getElementById(i);
				img.src = event.target.result;
				
				}
				} */
				 
			
			}
  
  function readURL1(input)
			{  
			var pre ="<img src='' id='1' style='height:10px;width;10px'>";
				
				 $("#banner_url").html(pre);
			
				var inp = document.getElementById('file');
				var pre ="1";
				for(var i=1;i<=inp.files.length ; i++)
                {
				var pre ="<img src='' id='"+i+"' style='height:10px;width;10px'>";
				
				 $("#banner_url").html(pre);
			
				
			 	var fReader = new FileReader();
				fReader.readAsDataURL(inp.files[i]);
				fReader.onloadend = function(event)
				{
			    var img = document.getElementById(i);
				img.src = event.target.result;
				}
				} 
				
			
			}

  
   
			$("#imgInp").change(function(){
				readURL(this);
			});

function update_img_order(order,img_id,campaign_id)
		{       
			    loading_show();
var banner_count  =$('#banner_check').val();
				if(order > banner_count || order < 1)
				{
					$("#model_body").html('<span style="style="color:#F00;">you can not enter grater than no of banner or less than 0 </span>');							
					$('#error_model').modal('toggle');
					loading_hide();
					return false;
				}
				var sendInfo 	= {"campaign_id":campaign_id,"order":order,"img_id":img_id, "update_order":1};
				var update_img_order 	= JSON.stringify(sendInfo);	
				
				$.ajax({
					url: "load_marketing.php?",
					type: "POST",
					data: update_img_order,
					contentType: "application/json; charset=utf-8",						
					async:true,					
					success: function(response) 
					{		
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
							loading_hide();
addMoreCamp(campaign_id,'edit',1);
						} 
						else
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');							
							$('#error_model').modal({
						backdrop: 'static'
					});
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
		function checkAll(section_id,checkbox_id,checkbox_class)
		{
		   var parent = $('#'+section_id+checkbox_id+':checkbox:checked').length > 0;	
			
					
			if(parent)
			{
				
				$('.'+checkbox_class+section_id).prop( "checked", true );
        		
			}
			else
			{
				$('.'+checkbox_class+section_id).prop( "checked", false );			
			}
		}
		</script>
        
    </body>
</html>