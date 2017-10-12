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
	$start_offset = 0;
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
            <div id="main" style="margin-left:0px">
                <div class="container-fluid">
					<?php 
                    /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,$title,$filename,$feature_name); 
                    /* this function used to add navigation menu to the page*/ 	
                    ?>
                	<div class="accordion-container">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3 style="margin-right:50px">
                                        	<i class="icon-dashboard"></i><?php echo $feature_name; ?>
                                        </h3>
                                        <div style="clear:both;"></div>
                                    </div><!-- header title-->
                                    
                                    <div class="box-content nopadding accordion-content well">
                                        <div style="clear:both">
                                            <div class="panel-body"> 
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 text-center">
                                                    
                                                    </div>
                                                </div>
                                            </div>
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
	</body>
</html>