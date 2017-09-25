<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Bulkupload";
$path_parts   		= pathinfo(__FILE__);
$filename 	  		= $path_parts['filename'].".php";
$sql_feature 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature 	= mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  		= mysqli_fetch_row($result_feature);
$feature_name 		= $row_feature[1];
$home_name    		= "Home";
$home_url 	  		= "view_bulkupload.php?pag=Dashboard";
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
	breadcrumbs($home_url,$home_name,'View Bulk Upload',$filename,$feature_name); 
	/* this function used to add navigation menu to the page*/ 
	?>    
<div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Excel Bulk Upload For Products
                                        </h3>
                                       
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                        <div class="profileGallery">
                                            <div style="width:100%;" align="center">
                                                <div id="loading"></div>                                            
                                                <div id="container2">
                                                    <div class="data">
                                                        <form method="post" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data" id="frm_category_excel">
                                                        <table class="table table-bordered dataTable" id="DataTables_Table_0">
                                                          <tbody>
                                                          <tr>
                                                          <td style="text-align:center">A</td>
                                                          <td style="text-align:center">B</td>
                                                          <td style="text-align:center">C</td>
                                                          <td style="text-align:center">D</td>
                                                          <td style="text-align:center">E</td>
                                                          <td style="text-align:center">F</td>
                                                          <td style="text-align:center">G</td>
                                                          <td style="text-align:center">H</td>
                                                          <td style="text-align:center">I</td>
                                                          <td style="text-align:center">J</td>
                                                          <td style="text-align:center">K</td>
                                                          <td style="text-align:center">L</td>
                                                          <td style="text-align:center">M</td>
                                                          <td style="text-align:center">N</td>
                                                          <td style="text-align:center">O</td>
                                                          <td style="text-align:center">P</td>
                                                          <td style="text-align:center">Q</td>
                                                          <td style="text-align:center">R</td>
                                                          <td style="text-align:center">S</td>
                                                          <td style="text-align:center">T</td>
                                                         
                                                          </tr>
                                                          <tr>
                                                          <td style="text-align:center">Model Number</td>
                                                          <td style="text-align:center">Name</td>
                                                          <td style="text-align:center">Title</td>
                                                          <td style="text-align:center">Description</td>
                                                          <td style="text-align:center">Organisation</td>
                                                          <td style="text-align:center">Brand</td>
                                                          <td style="text-align:center">Category</td>
                                                          <td style="text-align:center">Google Product Category</td>
                                                          <td style="text-align:center">Content</td>
                                                          <td style="text-align:center">Quantity</td>
                                                          <td style="text-align:center">Min quantity</td>
                                                          <td style="text-align:center">Max quantity</td>
                                                          <td style="text-align:center">List Price</td>
                                                          <td style="text-align:center">Recommended Price</td>
                                                          <td style="text-align:center">Meta Description</td>
                                                          <td style="text-align:center">Meta Title</td>
                                                          <td style="text-align:center">Meta Tags</td>
                                                          <td style="text-align:center">Specification</td>
                                                          <td style="text-align:center">Filters</td>
                                                          <td style="text-align:center">Levels</td>
                                                          </tr>
                                                         </tbody>
                                                       </table>
                                                            <div class="control-group">
                                                                <label for="tasktitel" class="control-label">Select file </label>
                                                                <div class="controls" style="float:left;">
                                                                    <input type="file" name="file" id="file" data-rule-required="true" data-rule-extension="xlsx"/>
                                                            		<!--Note<sup style="color:#F00">*</sup> : <br>You can only update the follwing <br> Recommended Price, List Price, Name of the Products, Product Description and the Quantity of the Products.    	-->
                                                                </div>
                                                            </div>
                                                            <div align="left" style="font-size:12px;">
                                                            <!--	Note<sup style="color:#F00">*</sup> : You can only update the follwing : Recommended Price, List Price, Name of the Products, Product Description and the Quantity of the Products through Excel.-->
                                                            </div>
                                                            <div class="form-actions">
                                                                <button type="submit" name="reg_submit_excel" class="btn-success">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                 </div>
             </div>
      </div>
          <?php getloder();?>                                                               
                                                                                                   
<script type="text/javascript">   
  
  		$('#frm_category_excel').on('submit', function(e)
		 {
			e.preventDefault();
		
			if ($('#frm_category_excel').valid())
			{
				//loading_show();	
				$.ajax({
						url: "load_bulkupload.php?",
						type: "POST",
						data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
						contentType: false,       // The content type used when sending data to the server.
						cache: false,             // To unable request pages to be cached
						processData:false,        // To send DOMDocument or non processed data file it is set to false
						success: function(response) 
						{   
							data = JSON.parse(response);
							//$("#model_body").html('<span style="style="color:#F00;">'+data+'</span>');
							if(data.Success == "Success") 
							{
								alert("File uploaded Successfully");
								//$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								loading_hide();
								window.location.assign("view_bulkupload.php?pag=<?php echo $title; ?>");
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
		}); /* excel */
		
		
   </script>         
</body>
</html>
