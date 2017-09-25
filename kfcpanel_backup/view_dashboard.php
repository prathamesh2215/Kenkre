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

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" media="all">
  <script src="https://use.fontawesome.com/ab4df8b3f6.js"></script>
<?php 
	/* This function used to call all header data like css files and links */
	headerdata($feature_name);
	/* This function used to call all header data like css files and links */	
?>
</head>
<body  class="theme-orange" data-theme="theme-orange" >
<?php 

/* this function used to add navigation menu to the page*/ 
	//echo $_SESSION['panel_user']['id'];
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
                    <h3 style="margin-right:50px"><i class="icon-dashboard"></i><?php echo $feature_name; ?></h3>
                    <div style="clear:both;"></div>
                </div>
                <!-- header title-->
                
                
                <!--------------------  START: DONE BY ANJALI 20-02-2015--------------------------->
                <!--------------------------------------------------------------------------------->
  				<?php
                	function getCount($icon, $table_name, $condition, $panel_name)
					{
						global $db_con;
						$data	= '';
					
							$data	.= '<div class="col-sm-12 col-md-4">';
							$data	.= '<div class="panel panel-primary">';
							$data	.= '<div class="panel-heading">';
							$data	.= '<div class="row">';
							$data	.= '<div class="col-sm-12 col-md-4 text-center">';
							$data	.= $icon;
							$data	.= '</div>';
							$data	.= '<div class="col-sm-12 col-md-8 text-center">';
							$sql_current_count  = " SELECT * FROM ".$table_name." WHERE 1=1 ";
								if($condition != '')
								{
									$sql_current_count  .= " AND ".$condition." ";	
								}
							$res_current_count	= mysqli_query($db_con,$sql_current_count) or  die(mysqli_error($db_con));
							$num_current_count  = mysqli_num_rows($res_current_count);
							$data	.= '<div class="huge" style="color:#FFF" ><h2>'.$num_current_count.'</h2></div>';
							$data	.= '<div style="color:#FFF"><h4>'.$panel_name.'</h4></div>';
							$data	.= '</div>';
							$data	.= '</div>';
							$data	.= '</div>';
							$data	.= '</div>';
							$data	.= '</div>';
							
							return $data;
							}
				?>
                <div class="box-content nopadding accordion-content well">
                   <div style="clear:both">
      
                     <div class="panel-body"> 
                        <div class="row">
                        	   
                            <div class="col-md-12 col-sm-12 text-center">
                        
                        <?php
                            echo $total_products	= getCount('<i style="color:#FFF" class="fa fa-shopping-cart fa-4x"></i>', 'tbl_products_master', '', 'Total Products')
                        ?>
                        
                        <?php
                            echo $out_of_stock_products	= getCount('<i class="fa fa-exclamation fa-4x" style="color:#FFF"></i>', 'tbl_products_master', 'prod_quantity =0', 'Out of Stock Products')
                        ?>
                        
                        <?php
                            echo $no_of_users	= getCount('<i style="color:#FFF" class="fa fa-users fa-4x"></i>', 'tbl_customer', 'cust_status =1', 'Number of Registered Users')
                        ?>
                        
                        <?php
                            echo $no_of_emp	= getCount('<i style="color:#FFF" class="fa fa-user fa-4x"></i>', 'tbl_employee_master', '', 'Number of Employees')
                        ?>
                        
                        <?php
                            echo $no_of_active_org	= getCount('<i style="color:#FFF" class="fa fa-sitemap fa-4x"></i>', 'tbl_oraganisation_master', 'org_status =1', 'Active Organizations')
                        ?>
                        
                        <?php
                            echo $no_of_inactive_org	= getCount('<i style="color:#FFF" class="fa fa-sitemap fa-4x"></i>', 'tbl_oraganisation_master', 'org_status =0', 'Inactive Organizations')
                        ?>
                        
                        <?php
                            echo $active_schools	= getCount('<i style="color:#FFF" class="fa fa-id-card fa-4x"></i>', 'tbl_schools', 'status =1', 'Active Schools')
                        ?>
                        
                        <?php
                            echo $inactive_schools	= getCount('<i style="color:#FFF" class="fa fa-id-card fa-4x"></i>', 'tbl_schools', 'status =0', 'Inactive Schools')
                        ?>
                        
                        <?php
                            echo $active_classes	= getCount('<i style="color:#FFF" class="fa fa-id-badge fa-4x"></i>', 'tbl_class', 'class_status =1', 'Active Classes')
                        ?>
                        
                        <?php
                            echo $inactive_classes	= getCount('<i style="color:#FFF" class="fa fa-id-badge fa-4x"></i>', 'tbl_class', 'class_status =0', 'Inactive Classes')
                        ?>
                        
                        <?php
                            echo $active_brands	= getCount('<i style="color:#FFF" class="fa fa-first-order fa-4x"></i>', 'tbl_brands_master', 'brand_status =1', 'Active Brands')
                        ?>
                        
                        <?php
                            echo $inactive_brands	= getCount('<i style="color:#FFF" class="fa fa-first-order fa-4x"></i>', 'tbl_brands_master', 'brand_status =0', 'Inactive Brands')
                        ?>
                        
						<?php
                            echo $active_campaign	= getCount('<i style="color:#FFF" class="fa fa-list-alt fa-4x"></i>', 'tbl_campaign', 'status =1', 'Active Campaigns')
                        ?>
                        
                        <?php
                            echo $inactive_campaign	= getCount('<i style="color:#FFF" class="fa fa-list-alt fa-4x"></i>', 'tbl_campaign', 'status =0', 'Inactive Campaigns')
                        ?>
                        
                        <?php
                            echo $complete_orders	= getCount('<i style="color:#FFF" class="fa fa-cart-plus fa-4x"></i>', 'tbl_cart', 'cart_type ="complete"', 'Complete Orders')
                        ?>
                        
                        <?php
                            echo $incomplete_orders	= getCount('<i style="color:#FFF" class="fa fa-cart-arrow-down fa-4x"></i>', 'tbl_cart', 'cart_type ="incomplete"', 'Incomplete Orders')
                        ?>
                        
                         <?php
                            echo $student_package_verified	= getCount('<i style="color:#FFF" class="fa fa-gift fa-4x"></i>', 'tbl_student_package', 'student_package_mobile_verify =1', 'Student Package (Verified)')
                        ?>
                        
                        <?php
                            echo $student_package_not_verified	= getCount('<i style="color:#FFF" class="fa fa-gift fa-4x"></i>', 'tbl_student_package', 'student_package_mobile_verify =0', 'Student Package(Unverified)')
                        ?>
                        
                        </div>
                      	
    <!--container-fluid-->
   				</div>
    
    <!--main content area-->
    </div>
    </div>
    <?php getloder();?>
        </div>
       
   </div>
		<style>
        body { padding-top:20px; }
		.panel-body .btn:not(.btn-block)
		 {
			  width:120px;margin-bottom:10px; 
		 }
		 
		 .hvr-icon-forward:before {
    content: "\f138";
    position: absolute;
    right: 1em;
    padding: 0 1px;
    font-family: FontAwesome;
    transform: translateZ(0);
    transition-duration: 0.1s;
    transition-property: transform;
    transition-timing-function: ease-out;
}

        </style>
        </body>

        </html>