

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
            <div class="container-fluid" id="div_view_school">                
					<?php 
                	/* this function used to add navigation menu to the page*/ 
                	breadcrumbs($home_url,$home_name,'View School',$filename,$feature_name); 
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
                                    <button type="button" class="btn-info" onClick="addMoreSchool('','add')" ><i class="icon-plus"></i>&nbspAdd School</button>
                                    <?php		
                                }
                            ?>                                       
                            <div style="padding:10px 15px 10px 15px !important">
                                <input type="hidden" name="hid_page_customers" id="hid_page_customers" value="1">
                                <select name="row_limit_coupons" id="row_limit_coupons" onChange="loadSchoolData();"  class = "select2-me">
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
			<div id="div_add_school" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add School',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add School
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_school_add" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_school_part">
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
			<div id="div_edit_school" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit School',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit School
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                                                          
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_school" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_school_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- edit Customers -->
          	<div id="div_view_school_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'School Detail',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            School Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_coupon_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_school_details_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- view Customers -->     
                
            <div id="view_school_product" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'School Product',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            School Product Details
                                        </h3>
                                            <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('view_school_product');" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                                                                   
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_pkg" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_prod">
                                        </div>                                    
                                        </form>
                                        <form id="frm_add_pkg" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_prod">
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
			var schools = [];
			$(".schools:checked").each(function ()
			{
				schools.push(parseInt($(this).val()));
			});
			if (typeof schools.length == 0)
			{
				alert("Please select checkbox to delete School");				
			}
			else
			{
				var sendInfo 	= {"schools":schools, "delete_schools":1};
				var delete_coupons 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_school.php?",
					type: "POST",
					data: delete_coupons,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
							loadSchoolData();
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
		
		function loadSchoolData()
		{
			//loading_show();
			row_limit_coupons 	= $.trim($('select[name="row_limit_coupons"]').val());
			search_text_coupons 	= $.trim($('#search_text_coupons').val());
			page 					= $.trim($("#hid_page_customers").val());
			
			load_school = "1";			
			
			if(row_limit_coupons == "" && page == "")
			{
				$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');				
			}
			else
			{
				var sendInfo 	= {"row_limit_coupons":row_limit_coupons, "search_text_coupons":search_text_coupons, "load_school":load_school, "page":page};
				var page_module_load 	= JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_school.php?",
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
		
		function addMoreSchool(school_id,req_type)
		{
			$('#div_view_school').css("display", "none");
			if(req_type == "add")
			{
				$('#div_add_school').css("display", "block");				
			}
			else if(req_type == "edit")
			{
				$('#div_edit_school').css("display", "block");				
			}	
			else if(req_type == "error")
			{
				$('#div_error_school').css("display", "block");				
			}
			else if(req_type == "view")
			{
				$('#div_view_school_details').css("display", "block");				
			}		
								
			var sendInfo = {"school_id":school_id,"req_type":req_type};
			var page_module_load = JSON.stringify(sendInfo);
			$.ajax({
					url: "load_school.php?",
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
								$("#div_add_school_part").html(data.resp);
							}
							else if(req_type == "edit")
							{
								$("#div_edit_school_part").html(data.resp);				
							}	
							else if(req_type == "error")
							{
								$("#div_error_school_part").html(data.resp);				
							}
							else if(req_type == "view")
							{
								$("#div_view_school_details_part").html(data.resp);
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
					url: "load_school.php?",
					type: "POST",
					data: page_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{							
							loadSchoolData();
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
       			   	loadSchoolData();
				}
			});
			loadSchoolData();
			$('#container1 .pagination li.active').live('click',function()
			{
				var page = $(this).attr('p');
				$("#hid_page_customers").val(page);
				loadSchoolData();						
			});

			//$(".js-example-basic-single").select2();
			//$(".js-example-basic-single").select2();


		}); 
		
		

          $('#frm_school_add').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_school_add').valid())
			{      
			       $.ajax({
					url: "load_school.php?",
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
							  alert('School Added Successfully');
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
			
		$('#frm_edit_school').on('submit', function(e) 
		{
			e.preventDefault();
			if ($('#frm_edit_school').valid())
			{       
			        
					loading_show();	
					$.ajax({
						url: "load_school.php?",
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
								alert('School Updated Successfully');	
								location.reload();					
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
		
		function getcity(state_id)
		{
			     if(state_id==0)
				 {
					var  state_id =$("#state").val();
				 }
				
				var sendInfo 	= {"state_id":state_id, "get_city":1};
				var page_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_school.php?",
					type: "POST",
					data: page_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
					    data = JSON.parse(response);
						$('#city').html(data.resp);
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
		
		function view_product(school_id)
		{
			  loading_show();	
			  $('#div_view_school').css('display','none');
			  $('#view_school_product').css('display','block');
			   var sendInfo 	= {"school_id":school_id, "get_products":1};
				var product 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_school.php?",
					type: "POST",
					data: product,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
					    data = JSON.parse(response);
					    if(data.stud_pkg_info_id !=-1)
						{
						$('#div_edit_prod').html(data.resp);
						}
						else
						{
							$('#div_add_prod').html(data.resp);
						}
						loading_hide();
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
        $('#frm_edit_pkg').on('submit', function(e) {
            
            e.preventDefault();
            if ($('#frm_edit_pkg').valid())
            {   loading_show();
                var product_count = $('#product_count').val();
				var school_id = $('#school_id').val();
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
                                     view_product(school_id);
                                   
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
		$('#frm_add_pkg').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_add_pkg').valid())
			{
				    loading_show();	
					var prod_check =  $('#check_prod').val();
					var school_id =  $('#school_id').val();
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
							    $('#div_add_prod').html(" ");
								
								
					            $('#div_add_package').css("display", "none");
							 view_product(school_id);							
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
			var sendInfo		= {"grade":grade,"grade_txt":grade_txt,"req":req,"cat_txt":cat_txt,"org_txt":org_txt,"brand_txt":brand_txt,"stud_pkg_info_id":stud_pkg_info_id,"org_id":org_id,"brand_id":brand_id,"cat_id":cat_id,"filter":1,"search_text":search_text,"page":page};
				var filter_data = JSON.stringify(sendInfo);				
				$.ajax({
					url: "load_student_package.php?",
					type: "POST",
					data: filter_data,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{    
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
		function searchProduct(e)
			{ 
			 var unicode=e.charCode? e.charCode : e.keyCode
			  if (unicode ==13)
			  {  
			    getProducts(0);
			   return false;
			  
              }	
			}
			function backtomain(div_id)
			{   
				loading_show();
				$('#'+div_id).css("display", "none");
				$('#div_view_school').css("display", "block");
				loadSchoolData();
				loading_hide();
			}
			
			
		function remove_prod(package_id)
	     {  
			loading_show();
			var school_id = $('#school_id').val();
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
						    view_product(school_id);
							
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
			function check_req()
			{
				$('#submit_req').val(1);
			}
	</script>
 <!-- added By satish for datepicker-->  
  


<script type="text/javascript" src="./jquery/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./jquery/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>

   
    </body>
</html>