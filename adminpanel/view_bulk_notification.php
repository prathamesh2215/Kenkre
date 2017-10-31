<?php
include("include/db_con.php");
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "Bulk Notification";
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
                    breadcrumbs($home_url,$home_name,'Bulk Notification',$filename,$feature_name); 
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
                                    
                                    <div id="req_resp"></div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="">
                                            <div id="loading"></div>                                            
                                            <div id="container1" class="data_container">
                                                <form method="post" id="frm_notification" class="form-horizontal form-bordered form-validate" novalidate>
                                                <div class="control-group">
                                                    <label for="textarea" class="control-label">Notification Type</label>
                                                    <div class="controls">
                                                         <select name="notification_type" id="notification_type"  class="select2-me input-medium" data-rule-required="true" onChange="loadData();">
                                                         <option value="All">All</option>
                                                         <option value="SMS">SMS</option>
                                                         <option value="Email">Email</option>
                                                         </select>      
                                                    </div>                                             
                                                </div>	<!-- Notification Type -->
                                                
                                               
                                                
                                                <div class="control-group">
                                                    <label for="textarea" class="control-label">Whom</label>
                                                    <div class="controls">
                                                        <select name="type" id="type"  class="select2-me input-medium" data-rule-required="true" onChange="getType(this.value)">
                                                            <option value="all">All</option>
                                                            <option value="competition">Competitions</option>
                                                            <option value="team">Teams</option>
                                                            <option value="batch">Trainig Batches</option>
                                                           
                                                </select>
                                                
                                                <div>
                                                
                                                <div id="competition"></div>
                                                <div id="team"></div>
		 										<div id="batch"></div>
		
                                                </div>
                                                
                                                <!-- &nbsp;&nbsp;<span id="student_count">
                                                 <?php
												 $sql_get_count ='SELECT * FROM tbl_students WHERE student_status =1';
												 $res_get_count = mysqli_query($db_con,$sql_get_count) or die(mysqli_error($db_con));
												 $num_get_count = mysqli_num_rows($res_get_count);
												 echo $num_get_count;
												 ?>
                                                 </span> Student's-->
                                                    </div>
                                                </div>	<!-- Whom -->
                                                
                                                <div id="test" class="control-group" style="display:none;">
                                                    <label for="textarea" class="control-label">Users</label>
                                                    <div class="controls" id="test1">
                                                    
                                                    </div>
                                                </div>	<!-- User Display Part -->
                                                
                                                <div class="control-group" id="textmessage2">
                                                    <label for="textarea" class="control-label">Message</label>
                                                    <div class="controls">
                                                        <span id="sp_indi_msg">
                                                            <textarea id="msg1" name="msg1" maxlength="500" style="width:90%;height:100px;" data-rule-required-"true"></textarea>
                                                        </span> 
                                                        <br><span id="ctxt1"></span>
                                                    </div>
                                                </div>	<!-- Message -->
                                                
                                                <div class="form-actions">
                                                    <input id="btn_indivil_send" value="Send"  class="btn-success" type="submit">
                                                    <button type="button" class="btn" onclick="location.reload();">Cancel</button>
                                                </div> <!-- Save and cancel -->
                                        
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>  <!-- view Area -->
            
                                             
                       
                              
                </div>
            </div>
        </div>
            <?php getloder();?>
        <script type="text/javascript">
		
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
       			   	//loadData();
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
			
		});  /*Search Area*/
		
		
		$('#frm_notification').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_notification').valid())
			{
			    loading_show();	
				$.ajax({
						url: "load_bulk_notification.php",
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
								window.location.assign("view_notification.php?pag=Notification");
								
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
		
		
				
				
		 function charsonly(e)
		 {
  			  var unicode=e.charCode? e.charCode : e.keyCode
			 
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
				  if (unicode<65||unicode>90 && unicode<97||unicode>122  )  //if not a number
				  return false //disable key press
              }
		}
	
		
		function getBatch(competion_id)
		{
			loading_show();	
			var sendInfo 	= {"competion_id":competion_id,"getBatch":1};
			var area_status = JSON.stringify(sendInfo);								
			$.ajax({
				url: "load_bulk_notification.php?",
				type: "POST",
				data: area_status,
				contentType: "application/json; charset=utf-8",						
				success: function(response) 
				{	
				     data = JSON.parse(response);
					if(data.Success == "Success") 
					{							
						$('#ft_batch').html(data.resp);
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
		
	
		
		function getType(type)
		{
			$('#competition').html(' ');
			$('#batch').html(' ');
			$('#team').html(' ');
			
			loading_show();
			var sendInfo 	= {"type":type,"getType":1};
			var area_status = JSON.stringify(sendInfo);								
			$.ajax({
				url: "load_bulk_notification.php?",
				type: "POST",
				data: area_status,
				contentType: "application/json; charset=utf-8",						
				success: function(response) 
				{	
				     data = JSON.parse(response);
					if(data.Success == "Success") 
					{							
						$('#'+type).html(data.resp);
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
		
		
		function getData(type)
		{
			
			var comp = [];
			$(".acomp:checked").each(function ()
			{
				comp.push(parseInt($(this).val()));
			});
			
			if(comp.length==0)
			{
				$('#'+type).html('');
				$("#acomp").prop("checked",false);
				return false;
			}
			loading_show();
			var sendInfo 	= {"comp":comp,"type":type,"getData":1};
			var area_status = JSON.stringify(sendInfo);								
			$.ajax({
				url: "load_bulk_notification.php?",
				type: "POST",
				data: area_status,
				contentType: "application/json; charset=utf-8",						
				success: function(response) 
				{	
				     data = JSON.parse(response);
					if(data.Success == "Success") 
					{							
						$('#'+type).html(data.resp);
						loading_hide();			
					} 
					else 
					{
						$('#'+type).html('');
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
		
		function checkCheckBoxes(id)
		{
			if($("#"+id).attr("checked")) 
			{
				$("."+id).prop("checked",true);
			} 
			else 
			{
				$("."+id).prop("checked",false);
			}
		}
		
		function unCheck(id,uncheckid)
		{
			if(!$("#"+id).attr("checked")) 
			{
				$("#"+uncheckid).prop("checked",false);
			} 
		}
		</script>     
		
    </body>
</html>
