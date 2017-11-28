<?php
	include("include/routines.php");
	checkuser();
	//chkRights(basename($_SERVER['PHP_SELF']));
	// This is for dynamic title, bread crum, etc.
	if(isset($_GET['pag']))
	{
		$title 	= $_GET['pag'];
	}
	else
	{
		$title	= 'Pincode Plugin';	
	}
	$path_parts   		= pathinfo(__FILE__);
	$filename 	  		= $path_parts['filename'].".php";
	$sql_feature 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
	$result_feature 	= mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
	$row_feature  		= mysqli_fetch_row($result_feature);
	$feature_name 		= 'Pincode Plugin'; // $row_feature[1];
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
            	<div class="container-fluid" id="div_view_spec">
					<?php
                    /* this function used to add navigation menu to the page*/
                    breadcrumbs($home_url,$home_name,'Add Customer',$filename,$feature_name);
                    /* this function used to add navigation menu to the page*/
                    ?>
               		<div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                            	<div class="box-title">
                                	<h3>
                                        <i class="icon-table"></i>
                                        <?php echo 'Pincode Plugin'; //$feature_name; ?>
                                    </h3>
                                </div>
                                <div class="box-content nopadding">
                                	<form id="frm_pincode" name="frm_pincode" class="form-horizontal form-bordered form-validate" >
                                    	<input type="hidden" id="hid_countryId" name="hid_countryId" value="">
                                        <input type="hidden" id="hid_stateId" name="hid_stateId" value=""> 
                                        
                                    	<div class="control-group">	<!-- span6 -->
                                        	<label for="tasktitel" class="control-label">Pincode<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            	<input type="text" placeholder="6 digit Pincode" id="txt_pincode" name="txt_pincode" class="input-large" data-rule-required="true" data-rule-number="true" maxlength="6" size="6" autofocus />
                                            </div>
                                        </div> <!-- Pincode -->
                                        
                                        <div class="control-group">	<!-- span6 -->
                                        	<label for="tasktitel" class="control-label">Country<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            	<input type="text" id="txt_country" name="txt_country" class="input-large keyup-char" data-rule-required="true" readonly/>
                                            </div>
                                        </div> <!-- Country -->
                                        
                                        <div class="control-group">	<!-- span6 -->
                                        	<label for="tasktitel" class="control-label">State<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls">
                                            	<input type="text" id="txt_state" name="txt_state" class="input-large keyup-char" data-rule-required="true" readonly/>
                                            </div>
                                        </div> <!-- State -->
                                        
                                        <div class="control-group">	<!-- span6 -->
                                        	<label for="tasktitel" class="control-label">District<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls" id="div_district">
                                            	<select name="ddl_district" id="ddl_district" class = "select2-me input-large" >
                                                	<option value="">Select District</option>
                                                </select>
                                            </div>
                                        </div> <!-- District -->
                                        
                                        <div class="control-group">	<!-- span6 -->
                                        	<label for="tasktitel" class="control-label">Taluka<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls" id="div_taluka">
                                            	<select name="ddl_taluka" id="ddl_taluka" class = "select2-me input-large" >
                                                	<option value="">Select Taluka</option>
                                                </select>
                                            </div>
                                        </div> <!-- Taluka -->
                                        
                                        <div class="control-group">	<!-- span6 -->
                                        	<label for="tasktitel" class="control-label">Area<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>
                                            <div class="controls" id="div_area">
                                            	<select name="ddl_area" id="ddl_area" class = "select2-me input-large" >
                                                	<option value="">Select Area</option>
                                                </select>
                                            </div>
                                        </div> <!-- Area -->
                                        
                                    </form>
                                </div>
                          	</div>
                       	</div>
                  	</div>
                </div>
           	</div>
      	</div>
        <?php getloder();?>
        <script type="text/javascript">
        	$('#txt_pincode').keyup(function(e){
				
				var pincode	= $('#txt_pincode').val();
				
				if(pincode.length == 6)
				{
					if(e.which == 13)
					{
						loading_show();
						var sendInfo	= {"pincode":pincode, "load_address":1};
						var addres_load = JSON.stringify(sendInfo);
						
						$.ajax({
							url: "load_plugin.php?",
							type: "POST",
							data: addres_load,
							contentType: "application/json; charset=utf-8",						
							success: function(response) 
							{
								data = JSON.parse(response);
								
								if(data.Success == "Success") 
								{
									$('#hid_countryId').val(data.country_id);
									$('#txt_country').val(data.country_name);
									
									$('#hid_stateId').val(data.state_id);
									$('#txt_state').val(data.state_name);
									
									var district_data	= '';
									var taluka_data		= '';
									var area_data		= '';
									
									district_data	+= '<select name="ddl_district" id="ddl_district" class = "select2-me input-large" onChange="getTaluka(this.value, '+pincode+')">';
										district_data	+= '<option value="">Select District</option>';
										for(var i = 0; i < data.district.length; i++)
										{
											district_data	+= '<option value="'+data.district[i]['district']+'">';
												district_data	+= data.district[i]['district'];
											district_data	+= '</option>';
										}
									district_data	+= '</select>';
									
									taluka_data	+= '<select name="ddl_taluka" id="ddl_taluka" class = "select2-me input-large" onChange="getArea(this.value, '+pincode+')">';
										taluka_data	+= '<option value="">Select Taluka</option>';
										for(var i = 0; i < data.taluka.length; i++)
										{
											taluka_data	+= '<option value="'+data.taluka[i]['taluka']+'">';
												taluka_data	+= data.taluka[i]['taluka'];
											taluka_data	+= '</option>';
										}
									taluka_data	+= '</select>';
									
									area_data	+= '<select name="ddl_area" id="ddl_area" class = "select2-me input-large">';
										area_data	+= '<option value="">Select Area</option>';
										for(var i = 0; i < data.area.length; i++)
										{
											area_data	+= '<option value="'+data.area[i]['id']+'">';
												area_data	+= data.area[i]['office_name'];
											area_data	+= '</option>';
										}
									area_data	+= '</select>';
									
									$('#div_district').html(district_data);
									$('#div_taluka').html(taluka_data);
									$('#div_area').html(area_data);
									
									$('#ddl_district').select2();
									$('#ddl_taluka').select2();
									$('#ddl_area').select2();
									
									loading_hide();
								} 
								else if(data.Success == "fail") 
								{
									alert(data.resp);
									//console.log(data.resp);
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
			});
			
			function getTaluka(distVal, pincode)
			{
				//alert(distVal+'<==>'+pincode);
				if(distVal != '' && pincode != '')
				{
					loading_show();
					var sendInfo	= {"pincode":pincode, "distVal":distVal, "load_taluka":1};
					var taluka_load = JSON.stringify(sendInfo);
					
					$.ajax({
						url: "load_plugin.php?",
						type: "POST",
						data: taluka_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							
							if(data.Success == "Success") 
							{
								var taluka_data		= '';
								var area_data		= '';
								
								taluka_data	+= '<select name="ddl_taluka" id="ddl_taluka" class = "select2-me input-large" onChange="getArea(this.value, '+pincode+')">';
									taluka_data	+= '<option value="">Select Taluka</option>';
									for(var i = 0; i < data.taluka.length; i++)
									{
										taluka_data	+= '<option value="'+data.taluka[i]['taluka']+'">';
											taluka_data	+= data.taluka[i]['taluka'];
										taluka_data	+= '</option>';
									}
								taluka_data	+= '</select>';
								
								area_data	+= '<select name="ddl_area" id="ddl_area" class = "select2-me input-large">';
									area_data	+= '<option value="">Select Area</option>';
									for(var i = 0; i < data.area.length; i++)
									{
										area_data	+= '<option value="'+data.area[i]['id']+'">';
											area_data	+= data.area[i]['office_name'];
										area_data	+= '</option>';
									}
								area_data	+= '</select>';
								
								$('#div_taluka').html(taluka_data);
								$('#div_area').html(area_data);
								
								$('#ddl_taluka').select2();
								$('#ddl_area').select2();
								
								loading_hide();
							} 
							else if(data.Success == "fail") 
							{
								alert(data.resp);
								//console.log(data.resp);
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
			
			function getArea(talVal, pincode)
			{
				var ddl_district	= $('#ddl_district').val();
				//alert(talVal+'<==>'+pincode+'<===>'+ddl_district);
				
				if(talVal != '' && pincode != '' && ddl_district != '')
				{
					loading_show();
					var sendInfo	= {"pincode":pincode, "talVal":talVal, "ddl_district":ddl_district, "load_area":1};
					var area_load = JSON.stringify(sendInfo);
					
					$.ajax({
						url: "load_plugin.php?",
						type: "POST",
						data: area_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							
							if(data.Success == "Success") 
							{
								var area_data		= '';
								
								area_data	+= '<select name="ddl_area" id="ddl_area" class = "select2-me input-large">';
									area_data	+= '<option value="">Select Area</option>';
									for(var i = 0; i < data.area.length; i++)
									{
										area_data	+= '<option value="'+data.area[i]['id']+'">';
											area_data	+= data.area[i]['office_name'];
										area_data	+= '</option>';
									}
								area_data	+= '</select>';
								
								$('#div_area').html(area_data);
								
								$('#ddl_area').select2();
								
								loading_hide();
							} 
							else if(data.Success == "fail") 
							{
								alert(data.resp);
								//console.log(data.resp);
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
        </script>
    </body>
</html>