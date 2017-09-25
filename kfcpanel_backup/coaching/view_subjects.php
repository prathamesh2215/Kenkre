<?php
	include("include/routines.php");

	checkuser();
	chkRights(basename($_SERVER['PHP_SELF']));
	
	// This is for dynamic title, bread crum, etc.
	$title = "View Category";
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
<!DOCTYPE html>
<html>
    <head>
	<?php 
        /* This function used to call all header data like css files and links */
        headerdata($feature_name);
        /* This function used to call all header data like css files and links */	
    ?>
    </head>
    
    <body class="<?php echo $theme_name;?>" data-theme="<?php echo $theme_name;?>">
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
					breadcrumbs($home_url,$home_name,'View Subjects',$filename,$feature_name); 
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
										$add = 1;	// Have to remove 
                                        if($add)
                                        {
                                            ?>
                                            <button type="button" class="btn-info" onClick="addMoreSubject('','add')" ><i class="icon-plus"></i>&nbspAdd Subject</button><!--<button  type="button" class="btn-info" onClick="download_excel()" ><i class="icon-plus"></i>&nbsp Download Excel</button>-->
                                            <?php		
                                        }
                                    ?>
                                    <div style="padding:10px 15px 10px 15px !important">
                                        <input type="hidden" name="hid_page" id="hid_page" value="1">
                                        <input type="hidden" name="cat_parent" id="cat_parent" value="parent">
                                        <select name="rowlimit" id="rowlimit" onChange="loadSubjectData();"  class = "select2-me">
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
                <div id="div_add_cat" style="display:none;">
                	<div class="container-fluid"> 
                    	<?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Subject',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Subject
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_subject" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_cat_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_edit_cat" style="display:none;">
                	<div class="container-fluid"> 
                    	<?php 
						/* this function used to add navigation menu to the page*/ 
						breadcrumbs($home_url,$home_name,'Edit Subject',$filename,$feature_name); 
						/* this function used to add navigation menu to the page*/ 
						?>          
						<div class="row-fluid">
							<div class="span12">
								<div class="box box-color box-bordered">
									<div class="box-title">
										<h3>
											<i class="icon-table"></i>
											Edit Subject
										</h3>
											<button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
									</div> <!-- header title-->
									<div class="box-content nopadding" >
										<form id="frm_edit_subject" class="form-horizontal form-bordered form-validate" >
										<div id="div_edit_cat_part">
										</div>                                    
										</form>
									</div>	<!-- Main Body -->
								</div>
							</div>
						</div>
                    </div>
                </div>
                
                <div id="div_view_cat_details" style="display:none;">
                	<div class="container-fluid">
                    	<?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Category Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Subject Details
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
                </div>
            </div>
       	</div>
        <button onClick="sandy();" value="check">check</button>
        <?php getloder();?>
        <script type="text/javascript">
		
		function sandy()
		{
			var activeAjaxConnections=0;
			$.ajax({
        	url: "http://192.168.1.8:8080/DikshaPortal/login.do", 
        	data:{
        		password:"satish",
        		method:"login",
        		userName:"password",
        		cType:"LOGIN"
        		},
        	type:'post',
            dataType: 'xml',
			async: true,
        	contentType: "text/xml; charset=\"utf-8\"",
        	//contentType:"application/x-www-form-urlencoded",
        	success: function(result){
        		
        		console.log(result);
        			alert("Msg: "+result.data);
        	},
        	error: function(xhr, ajaxOptions, thrownError){
				console.log(xhr);
				console.log(ajaxOptions);
        		console.log(thrownError);
        	alert("msg: "+thrownError.message+" , status: "+xhr.status);
			 activeAjaxConnections--;
  if (0 == activeAjaxConnections) {
    // this was the last Ajax connection, do the thing
  }
        	}
			});
		}
		function viewChild(cat_parent)
		{
			$.trim($('#srch').val(""));			
			$.trim($('#cat_parent').val(cat_parent));	
			if(cat_parent == "parent")		
			{
			}
			else
			{
				$("#hid_page").val(1);				
			}
			loadSubjectData();
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
				$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');	
				$('#error_model').modal('toggle');	
				loading_hide();		
			}
			else
			{
				delete_subject 	= 1;
				var sendInfo 	= {"batch":batch, "delete_subject":delete_subject};
				var del_cat 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "coaching/load_subjects.php?",
					type: "POST",
					data: del_cat,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
					   
						data = JSON.parse(response);
						
						if(data.Success == "Success") 
						{		alert();				
							loadSubjectData();
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
		function loadSubjectData()
		{
			loading_show();
			row_limit 	= $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			page 		= $.trim($("#hid_page").val());
			cat_parent	= $.trim($('#cat_parent').val());
			load_subjects = "1";			
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');	
				loading_hide();
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_subjects":load_subjects, "page":page,"cat_parent":cat_parent};
				var cat_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "coaching/load_subjects.php?",
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
		
	
		
		function changeStatus(offering_id,curr_status)
		{
			loading_show();
			if(offering_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
				$('#error_model').modal('toggle');		
				loading_hide();		
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"offering_id":offering_id, "curr_status":curr_status, "change_status":change_status};
				var cat_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "coaching/load_subjects.php?",
					type: "POST",
					data: cat_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadSubjectData();
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
		
		
	
		
		function addMoreSubject(subject_id,req_type)
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
			var sendInfo = {"subject_id":subject_id,"req_type":req_type,"load_add_subject_part":"1"};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "coaching/load_subjects.php?",
					type: "POST",
					data: cat_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#div_add_cat_part").html("");
							$("#div_edit_cat_part").html("");
							$("#div_view_cat_part").html("");
							
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
		
		
		$( document ).ready(function() {
			$('#srch').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page").val("1");					
       			   	loadSubjectData();	
				}
			});
			$('#srch1').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page1").val("1");					
       			   	loadSubjectData();	
				}
			});			
			loadSubjectData();
			
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page").val(page);
				loadSubjectData();						
		});
			$('#container3 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page1").val(page);
				loadErrorData();						
			});				
	});  
		
		
		$('#frm_add_subject').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_add_subject').valid())
			{
				loading_show();	
				$.ajax({
						type: "POST",
						url: "coaching/load_subjects.php",
						data: new FormData(this),
						processData: false,
  						contentType: false,
						cache: false,
						success: function(msg)
						{
							data = JSON.parse(msg);
							
							if(data.Success == "Success")
							{
								alert(data.resp);
								alert(data.resp);
								location.reload();
								//loading_hide();
							}
							else if(data.Success == "fail") 
							{
								alert(data.resp);
								loading_hide();	
							}	
						},
						error: function (request, status, error)
						{
							//loading_hide();	
						},
						complete: function()
						{
							//loading_hide();	
						}	
					});
			}
		});	
		
		
		$('#frm_edit_subject').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_edit_subject').valid())
			{
				loading_show();	
				$.ajax({
						type: "POST",
						url: "coaching/load_subjects.php",
						data: new FormData(this),
						processData: false,
  						contentType: false,
						cache: false,
						success: function(msg)
						{
							data = JSON.parse(msg);
							
							if(data.Success == "Success")
							{
								alert(data.resp);
								alert(data.resp);
								location.reload();
								//loading_hide();
							}
							else if(data.Success == "fail") 
							{
								alert(data.resp);
								loading_hide();	
							}	
						},
						error: function (request, status, error)
						{
							//loading_hide();	
						},
						complete: function()
						{
							//loading_hide();	
						}	
					});
			}
		});	
						
		
		function getsubcat(cat_id)
		{
			
			
			if(cat_id=="")
			{
				return false;
			}
			loading_show();
			var sendInfo 	= {"cat_id":cat_id,"get_subcat":1};
			var cat_data 	= JSON.stringify(sendInfo);								
			$.ajax({
			url: "coaching/load_subjects.php?",
			type: "POST",
			data: cat_data,
			contentType: "application/json; charset=utf-8",						
			success: function(response) 
			{		
			   
		    	$("#catid").select2("val", ""); 
		     	$('#catid').select2()
			   	data = JSON.parse(response);
				$('#catid').html(data.resp);
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
		
	
		
		</script>
    </body>
</html>