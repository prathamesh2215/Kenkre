<?php
include("include/db_con.php");
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "Competition Report";
$path_parts   		= pathinfo(__FILE__);
$filename 	  		= $path_parts['filename'].".php";
$sql_feature 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature 	= mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  		= mysqli_fetch_row($result_feature);
$feature_name 		= $row_feature[1];
$home_name    		= "Home";
$home_url 	  		= "view_dashboard.php?pag=Dashboard";
$utype				= $_SESSION['panel_user']['utype'];
$uid				= $_SESSION['panel_user']['id'];
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
                <div class="container-fluid" id="div_view_area">                
					<?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'View Competition',$filename,$feature_name); 
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
                                        
                                      
                                       <!-- <div style="float:right;width:;"> 
                                <select name="ft_coach" id="ft_coach"  class="select2-me input-medium" data-rule-required="true" onChange="loadData();">
                                        <option value="">Select Coach</option>
                                        <?php   $sql_get_coach     = " SELECT * FROM `tbl_cadmin_users` 
																	WHERE `status` = 1 AND utype=15 order by fullname asc";
												$res_get_coach = mysqli_query($db_con,$sql_get_coach) or die(mysqli_error($db_con));
												while($coach_row  = mysqli_fetch_array($res_get_coach))
												{
										?>
                                                <option value="<?php echo $coach_row['id']; ?>"><?php echo ucwords($coach_row['fullname']); ?></option>
                                                
                                           <?php } ?>
                                                </select>
                                        </div>-->
                                      <div style="float:left;"> 
                                      &nbsp;&nbsp;&nbsp;
                                         <select name="ft_team" id="ft_team"  class="select2-me input-medium" data-rule-required="true" onChange="loadData();">
                                        <option value="">Select Team</option>
                                        <?php   $sql_get_batch  = " SELECT * FROM `tbl_team` WHERE `team_status` = 1 ";
												$sql_get_batch .= "	AND team_id IN( SELECT DISTINCT team_id FROM tbl_competition_team)";
												$sql_get_batch .= "						order by team_name asc";
												$res_get_batch = mysqli_query($db_con,$sql_get_batch) or die(mysqli_error($db_con));
												while($batch_row  = mysqli_fetch_array($res_get_batch))
												{
										?>
                                                <option value="<?php echo $batch_row['team_id']; ?>"><?php echo ucwords($batch_row['team_name']); ?></option>
                                                
                                           <?php } ?>
                                              </select>
                                        </div>
                                        
                                        <div style="float:left;"> 
                                         &nbsp;&nbsp;&nbsp;
                                         <select name="ft_competition" id="ft_competition"  class="select2-me input-medium" data-rule-required="true" onChange="loadData();">
                                        <option value="">Select Competition</option>
                                        <?php   $sql_get_batch     = " SELECT * FROM `tbl_competition` WHERE `competition_status` = 1 ";
												if($utype !=1)
												{
													$sql_get_batch    .= "AND created_by='".$id."' ";
												}
										        $sql_get_batch    .= "order by competition_name asc";
												$res_get_batch = mysqli_query($db_con,$sql_get_batch) or die(mysqli_error($db_con));
												while($batch_row  = mysqli_fetch_array($res_get_batch))
												{
										?>
                                                <option value="<?php echo $batch_row['competition_id']; ?>"><?php echo ucwords($batch_row['competition_name']); ?></option>
                                                
                                           <?php } ?>
                                              </select>
                                        </div>
                                        
                                        
                                        <div style="float:right;width:;">  &nbsp;End Date :
                                           <input type="text" class="input-medium datepicker" value="<?php echo date('d-m-Y'); ?>" onChange="loadData()" name="end_date" id="end_date" >
                                        </div>
                                           <div style="float:right;width:;">  Start Date : 
                                           <input type="text" class="input-medium datepicker" value="<?php echo date('01-m-Y'); ?>" name="start_date" onChange="loadData()" id="start_date" >
                                        </div>
                                        
                                          
                                        
                                        
                                        
                                    </div> <!-- header title-->

                                    <div class="box-content nopadding">
                                    
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
             
                                       
                       
                <div class="container-fluid" id="div_view_area_details" style="display:none">                
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'View Student Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>        
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Student Details
                                        </h3>
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
				
				var sendInfo 	= {"batch":batch, "delete_student":1};
				var del_cat 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_competition_report.php?",
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
			
			//coach_id         = $("#ft_coach").val();	
			team_id	     = $("#ft_team").val();
			start_date       = $("#start_date").val();	
			end_date	     = $("#end_date").val();
			competition_id   = $("#ft_competition").val();	
								
			
			var sendInfo = {"competition_id":competition_id,"team_id":team_id,"start_date":start_date,"end_date":end_date,"row_limit":row_limit, "search_text":search_text, "load_competition":1, "page":page};
				var ind_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_competition_report.php?",
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
			
		}   /*Load Data*/
		
		function viewStudnets(competition_id)
		{
		    var sendInfo = {"competition_id":competition_id,"load_students":1};
			var cat_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_competition_report.php?",
					type: "POST",
					data: cat_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{
							$("#div_view_area_details").css('display','block');
							$("#div_view_area").css('display','none');
							$("#div_view_area_details_part").html(data.resp);
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
		
		
		
		function isNumberKey(evt)
		{
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
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
			$('#srch1').keypress(function(e) 
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
			$edit = checkFunctionalityRight($filename,1);
			if($add || $edit)
			{
			?>	
			//loadData1();
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
		
		
		
		
		function backToMain(close_div,show_div)
		{
			$('#'+close_div).css('display','none');
			$('#'+show_div).css('display','block');
		}
		
		
		
		
		
		 $( ".datepicker" ).datepicker({
		changeMonth	: true,
		changeYear	: true,
		dateFormat	: 'dd-mm-yy',
		//yearRange 	: 'c:c',//replaced "c+0" with c (for showing years till current year)
		
			
	   });
	   
	   
		
	  </script>     
		
		<script src="js/bootstrap-datepicker.js"></script>   
		
    </body>
</html>
