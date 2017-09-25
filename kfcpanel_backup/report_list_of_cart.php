<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "Cart";
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
                <div class="container-fluid" id="div_view_indus">                
					<?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'Cart',$filename,$feature_name); 
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
                                        &nbsp;&nbsp;
                                    <select name="cartlist" id="cartlist"  class="select2-me input-medium" data-rule-required="true" onChange="loadData()" >
                                    	<option value="all">All Cart Type</option>
                                        <!-- Get Name Of the All students [Student is there but School and city are blancks] -->
                                    	<option value="abundant">Abundant</option>
                                        <!--<option value="complete">Complete</option>-->
                                        <option value="incomplete">Incomplete</option>	
                                       
                                    </select>
                                    </div> <!-- header title-->

                                    <div class="box-content nopadding">
                                                               
                                    <div style="padding:10px 15px 10px 15px !important">
                                    	<input type="hidden" name="hid_page" id="hid_page" value="1">
                                    	<input type="hidden" name="ind_parent" id="ind_parent" value="Parent">
                                        <select name="rowlimit" id="rowlimit" onChange="loadData();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Cart Id, Product Name, Customer Name can be Search..."  style="float:right;margin-right:10px;margin-top:10px;width:400px" >
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
                    </div>  <!-- Cart -->
               
                <div class="container-fluid" id="div_edit_indus" style="display:none">   
                  
                <div class="container-fluid" id="div_error_indus" style="display:none">   
                      
                <div class="container-fluid" id="div_view_indus_details" style="display:none">                
                    <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'View Cart Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                    ?>        
                    <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Cart Details
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <form id="frm_view_indus_details" class="form-horizontal form-bordered form-validate" >
                                            <div id="div_view_indus_details_part">
                                            </div>                                    
                                        </form>  
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div> <!-- View Details cart -->                      
                </div>
            </div>
        </div>
            <?php getloder();?>
        <script type="text/javascript">
		
		function loadData()
		{
			loading_show();
			row_limit = $.trim($('select[name="rowlimit"]').val());
			search_text = $.trim($('#srch').val());
			var cartlist			= $('#cartlist').val();
			page = $.trim($("#hid_page").val());								
			if(row_limit == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
				$('#error_model').modal('toggle');
				loading_hide();							
			}
			else
			{
				var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "cartlist":cartlist, "load_cart":1, "page":page};
				var ind_load = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_report_of_cart.php?",
					type: "POST",
					data: ind_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						//alert(response);
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
		});  
		
		function sendCartEmail()
		{			
			loading_show();		
			var batch = [];
			$(".batch:checked").each(function ()
			{
				batch.push(parseInt($(this).val()));
			});
			if (batch.length == 0)
			{
				$("#model_body").html('<span style="style="color:#F00;">Please select checkbox to send email</span>');
				$('#error_model').modal('toggle');
				loading_hide();						
			}
			else
			{
				var sendInfo 	= {"batch":batch, "send_cart_mail":1};
				var mail_send 	= JSON.stringify(sendInfo);									
				$.ajax({
					url: "load_report_of_cart.php?",
					type: "POST",
					data: mail_send,
					contentType: "application/json; charset=utf-8",	
					async:true,						
					success: function(response) 
					{	//alert(response);
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{		
							alert("Mail Sent Successfully");	
							//location.reload();			
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
						//loading_hide();
						//alert("complete");
                	}
			    });					
			}
		}
		
		/*function check_uncheck_checkbox(isChecked) {
		if(isChecked) {
			$('input[name="batch"]').each(function() { 
			this.checked = true; 
			});
		} else {
			$('input[name="batch"]').each(function() {
			this.checked = false;
		});
	}
	
}*/
		function comments(cust_id)
		{
			var  update_comments = "1";			
			var  comment         = $("#comment_"+cust_id).val();
			
				var sendInfo 	= {"cust_id":cust_id,"update_comments":update_comments,"comment":comment};
				var page_module_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_customers.php?",
					type: "POST",
					data: page_module_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{    
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{  
							
							loading_hide();
							loadData();
						} 
						else
						{							
						$("#model_body").html('<span style="style="color:#F00;">Comment not updated</span>');							
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
					}
			    });
			
		}
		
		function check_box(id)
		{
			
			
			$("#"+id).prop('checked', true);
			sendCartEmail();
		}
		
		
		</script>     
		
    </body>
</html>

