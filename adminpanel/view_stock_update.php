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
    ?><script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
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
					breadcrumbs($home_url,$home_name,'Stock Update',$filename,$feature_name); 
					/* this function used to add navigation menu to the page*/ 
					?>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Qty / Price Using ISBN
                                        </h3>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding">
                                    Excel Format : 
                                    <table class="table table-bordered dataTable">
                                      <tr>
                                      <td style="text-align:center">A</td>
                                      <td style="text-align:center">B</td>
                                      <td style="text-align:center">C</td>
                                      <td style="text-align:center">D</td>
                                      <td style="text-align:center">E</td>
                                      <td style="text-align:center">F</td>
                                      </tr>
                                      <tr>
                                      <td style="text-align:center">ISBN NO</td>
                                      <td style="text-align:center">Model NO</td>
                                      <td style="text-align:center">Product Quantity</td>
                                      <td style="text-align:center">Product Max Quantity</td>
                                      <td style="text-align:center">Product List Price</td>
                                      <td style="text-align:center">Product Recommended Price</td>
                                      </tr>
                                     </table>
                                     
                                     <form method="post" enctype="multipart/form-data" id="frm_isbn_qty" class="form-horizontal form-bordered form-validate" novalidate="novalidate">
                                            
                                            <input id="stock_update" name="stock_update" value="1" type="hidden">
                                            <div class="control-group">
                                                <label for="tasktitel" class="control-label">Select file </label>
                                                <div class="controls">
                                                    <input name="isbn_qty_file" data-rule-extension="xlsx" id="isbn_qty_file" data-rule-required="true" type="file">
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" name="isbn_qty_update" class="btn-success">Submit</button>
                                            </div>
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
				
		
	
	
		
		
	
		
		
		$('#frm_isbn_qty').on('submit', function(e) {
			e.preventDefault();
			if ($('#frm_isbn_qty').valid())
			{
				loading_show();	
				$.ajax({
						type: "POST",
						url: "load_stock_update.php",
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
		
		
	
						
		function back_to_main(close_div,show_div)
		{
			$('#'+close_div).css('display','none');
			$('#'+show_div).css('display','block');
		}
		
		</script>
    </body>
</html>