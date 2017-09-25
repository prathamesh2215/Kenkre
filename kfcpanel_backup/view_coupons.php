<?php
include("include/routines.php");
include("include/db_con.php");
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
            <div class="container-fluid" id="div_view_customers">                
					<?php 
                	/* this function used to add navigation menu to the page*/ 
                	breadcrumbs($home_url,$home_name,'View Customers',$filename,$feature_name); 
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
                               
                            </div><!-- header title-->
                            <div class="box-content nopadding">
                            <?php
                                $add = checkFunctionalityRight($filename,0);
                                if(true)
                                {
                                    ?>
                                    <button type="button" class="btn-info" onClick="addMoreCoupon('','add')" ><i class="icon-plus"></i>&nbspAdd Coupon</button>
                                    <?php		
                                }
                            ?>                                       
                            <div style="padding:10px 15px 10px 15px !important">
                                <input type="hidden" name="hid_page_customers" id="hid_page_customers" value="1">
                                <select name="row_limit_coupons" id="row_limit_coupons" onChange="loadCouponsData();"  class = "select2-me">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries per page
                                <input type="text" class="input-medium" id = "search_text_coupons" name="search_text_coupons" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                </div>
			<div id="div_add_coupon" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Coupon',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Coupon
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_coupon_add" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_coupon_part">
                                        </div>  
                                        <div id = "lkp">
                                        </div>                                  
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Add Customers -->
			<div id="div_edit_coupon" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Coupon',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Coupon
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" > 
                                    	<form id="frm_edit_coupon" class="form-horizontal form-bordered form-validate">
                                        <div id="div_edit_coupon_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Customers -->
          	<div id="div_view_coupons_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Coupon Details',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Coupon Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_coupon_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_coupons_details_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- view Customers -->                  
    	</div>
	</div>
	<?php getloder();?>


	<?php
	$hidden_ran = generateRandomString(16);
    echo '<input id="hidden_random" type="hidden" value="'.$hidden_ran.'">';

	?>
<script src="https://use.fontawesome.com/9b1c93c0f6.js"></script>
	<script type="text/javascript">
		

  function labelshow()
		 {
			var sd = $("#datepicker").val();
			var ed = $("#datepicker1").val();
			
			if(sd == ""){
				$("#error5").show();
			}
			if(ed == ""){
				$("#error6").show();
			}
		 }
function coupon(element)
{
if(element == "coupon"){
	$("#lkpm").slideDown();
	$("#mcps").slideUp();
	 $("#random_input").val("");
	
	$("#random_input").css("display",'none');
	$("#coupon_code").css("display",'block');
    //$("#mcps").hide();
}
else if(element == "gift_card"){
	
	$("#mcps").slideDown();
	$("#lkpm").slideUp();
	$("#coupon_code").val("");
	$("#coupon_code").css("display",'none');
	$("#random_input").css("display",'block');
	//$("#lkpm").hide();
}
else
{   
    $("#random_input").val("");
	$("#coupon_code").val("");
	$("#lkpm").css("display",'none');
	$("#coupon_code").css("display",'none');
}

//alert("good");

}
/*function gfx(mil)
{
	var c = mil;
	var d = '<?php // echo generateRandomString(c); ?>';
	alert(d);
	$("#randin").val(d);
}*/
/*function generateRandom(length) {
	var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var result = '';
    for (var i = length; i > 0; --i) 
    	{
    		result = result + chars[Math.floor(Math.random() * chars.length)];
    	}
    	//alert(result);
    $("#random_input").val(result);
    //return result;
}*/
function generateRandom(length){
	//alert(length);
	//var hidden_ran = $("#hidden_random").val();
	var len = length;
	var random = '1';
	//alert(hidden_ran);
	 //$("#random_input").val(result);
	 var sendInfo 	= {"len":len, "random":random};
				var giftcard 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coupons.php?",
					type: "POST",
					data: giftcard,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							//alert(data.resprandom);
							$("#random_input").val(data.resprandom);
						} 
						else
						{
							alert(data.resprandom);												
						}
					},
					
			    });			

    

}
function showOptions(s) {
  //console.log(s[s.selectedIndex].value); // get value
  console.log(s[s.selectedIndex].id); // get id
  //alert(ssw);

}

function discounts(t)
{
	
	if(t == "percent"){
	$("#percent5").slideDown();
	$("#price5").slideUp();
	
	$("#disc_price").val("")
    //$("#mcps").hide();
}
else{
	$("#price5").slideDown();
	$("#percent5").slideUp();
	$("#disc_per").val("")
	//$("#lkpm").hide();
}
}

function date_validate()
{
    var startDate = new Date(document.getElementById("datepicker").value);
    var endDate = new Date(document.getElementById("datepicker1").value);

    if (startDate.getTime() > endDate.getTime())
    {
        alert ("The ending date should have been after the start date!");
        //$("#startDate").val("");
        $("#datepicker1").val("");
    }
}/*function doCompareDate() {
var sd = document.getElementById("sd").value;
var ed = document.getElementById("ed").value
var sd1 = new Date(sd);
var ed1 = new Date(ed);

if (sd1 > ed1) {
alert("Customer Order date can not be greater than delivery date");
//return false;
}
}*/












   

		function multipleDelete()
		{			
			loading_show();		
			var coupons = [];
			$(".coupons:checked").each(function ()
			{
				coupons.push(parseInt($(this).val()));
			});
			if (typeof coupons.length == 0)
			{
				alert("Please select checkbox to delete catogery");				
			}
			else
			{
				var sendInfo 	= {"coupons":coupons, "delete_coupons":1};
				var delete_coupons 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coupons.php?",
					type: "POST",
					data: delete_coupons,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadCouponsData();
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
		
		function loadCouponsData()
		{
			//loading_show();
			row_limit_coupons 	= $.trim($('select[name="row_limit_coupons"]').val());
			search_text_coupons 	= $.trim($('#search_text_coupons').val());
			page 					= $.trim($("#hid_page_customers").val());
			
			load_coupons = "1";			
			
			if(row_limit_coupons == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 	= {"row_limit_coupons":row_limit_coupons, "search_text_coupons":search_text_coupons, "load_coupons":load_coupons, "page":page};
				var page_module_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_coupons.php?",
					type: "POST",
					data: page_module_load,
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
					}
			    });
			}
		}
		
		function addMoreCoupon(coup_id,req_type)
		{
			$('#div_view_customers').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_coupon').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_coupon').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_customers').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_coupons_details').css("display", "block");				
			}		
								
			var sendInfo = {"coup_id":coup_id,"req_type":req_type};
			var page_module_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_coupons.php?",
					type: "POST",
					data: page_module_load,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{
						data = JSON.parse(response);
						
						if(data.Success == "Success") 
						{   
							if(req_type == "add")
							{
								$("#div_add_coupon_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_coupon_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_customers_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_coupons_details_part").html(data.resp);
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
		
		function changeStatus(coup_id,curr_status)
		{ 
			loading_show();
			if(coup_id == "" && curr_status == "")
			{
				$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
			}
			else
			{
				change_status 	= 1;
				var sendInfo 	= {"coup_id":coup_id, "curr_status":curr_status, "changeStatus":change_status};
				var page_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coupons.php?",
					type: "POST",
					data: page_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadCouponsData();
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
				
		$( document ).ready(function() 
		{
			$('#search_text_coupons').keypress(function(e) 
			{
				if(e.which == 13) 
				{	
					$("#hid_page_customers").val("1");				
       			   	loadCouponsData();
				}
			});
			loadCouponsData();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page_customers").val(page);
				loadCouponsData();						
			});

			//$(".js-example-basic-single").select2();
			//$(".js-example-basic-single").select2();


		}); 
		
		function validate_lpu(value)
		{
			if(value=="one_time_use")
			{ 
			
				$("#lpu").val(1);
				$("#lpu").prop("disabled",true);
				$("#noofusers").val(" ");
				$("#noofusers").prop("disabled",false);
			}
			else if(value=="unlimited_use")
			{   
				$("#lpu").val(0);
				$("#lpu").prop("disabled",true);
				$("#noofusers").val(0);
				$("#noofusers").prop("disabled",true)
			}
			else
			{
				$("#lpu").val("");
				$("#lpu").prop("disabled",false);
				$("#noofusers").val("");
				$("#noofusers").prop("disabled",false);
			}
			
		}

          $('#frm_coupon_add').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_coupon_add').valid())
			{      
			       
   					var startDate = new Date(document.getElementById("start_date").value);
   					var endDate  = new Date(document.getElementById("end_date").value);
                    var curr_date =new Date();
					
					/*if(curr_date.getTime() > startDate.getTime())
					{
					 		$("#model_body").html('<span style="style="color:#F00;">The Starting date should be greater or equal to Current date!</span>');
							$('#error_model').modal('toggle');
							loading_hide();
							return false;
					}*/
					var discount_type =$('#discount9').val();
					if(discount_type=="price")
					{
						var disc_price =parseInt($('#disc_price').val());
						var min_pur =parseInt($('#mp').val());
						if(min_pur <= disc_price)
						{
							$("#model_body").html('<span style="style="color:#F00;">The Miniumum purchase should be greter than price !</span>');
							$('#error_model').modal('toggle');
							loading_hide();
							return false;
						}
					}
						
				   if(startDate !="")
				   {
					  if(endDate !="")
					  {
							if (startDate.getTime() > endDate.getTime())
							{
								$("#model_body").html('<span style="style="color:#F00;">The ending date should have been after the start date!</span>');
								$('#error_model').modal('toggle');
								loading_hide();
								return false;
							}
					 }
				}
			
			         
					$.ajax({
					url: "load_coupons.php?",
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
							  alert('Coupon Added Successfully');
							  location.reload();
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
			
		$('#frm_edit_coupon').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_coupon').valid())
			{       
			         var curr_date =Date();
			
			         var startDate = new Date(document.getElementById("start_date").value);
   					 var endDate = new Date(document.getElementById("end_date").value);
              
			      var discount_type =$('#discount9').val();
		
					if(discount_type=="price")
					{
						var disc_price =parseInt($('#disc_price').val());
						var min_pur =parseInt($('#mp').val());
						
						if(min_pur <= disc_price)
						{   
							$("#model_body").html('<span style="style="color:#F00;">The Miniumum purchase should be greter than price !</span>');
							$('#error_model').modal('toggle');
							loading_hide();
							return false;
						}
					}
			  
				  if(startDate !="")
				  {
					  	   if(endDate !="")
						   {
                  			 if (startDate.getTime() > endDate.getTime())
    							{
     				 				$("#model_body").html('<span style="style="color:#F00;">The ending date should have been after the start date!</span>');
									$('#error_model').modal('toggle');
									loading_hide();
									return false;
				   			   }
					    }
			  	}
					loading_show();	
					$('input[name="reg_submit_edit"]').attr('disabled', 'true');				
					$.ajax({
						url: "load_coupons.php?",
						type: "POST",
						data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
						contentType: false,       // The content type used when sending data to the server.
						cache: false,             // To unable request pages to be cached
						processData:false,        // To send DOMDocument or non processed data file it is set to false
						async:true,
						success: function(response) 
						{    //alert(response);
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{	
								alert('Coupon Updated Successfully');						
								window.location.assign("view_coupons.php?pag=<?php echo $title; ?>");
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
		}); /* edit Customers*/
		
		
		function numsonly(e)
		 {    
  			  var unicode=e.charCode? e.charCode : e.keyCode
			  
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
     		  if ( unicode<48||unicode>57  )  //if not a number
          	  return false //disable key press
              }
		}
		function checkpercentage(e)
		 {    
  			  var unicode=e.charCode? e.charCode : e.keyCode
			// var percentage =$("#disc_per").val();
			if(unicode !=8 )
			  {
			  if ( unicode<48||unicode>57) 
			  { //if not a number
          	  return false ;//disable key press
			  }
			  }
		}        
		function checklength(id,limit)
		{ 
			var disc_value = $("#"+id).val();
			
			if(id == "disc_per" && disc_value > 100)
			{
				alert("Percentage should be less than or equal to 100");
				$("#"+id).val(" ");
			}
		}
	</script>
 <!-- added By satish for datepicker-->  
  


<script type="text/javascript" src="./jquery/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./jquery/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>

   
    </body>
</html>