<?php
    include("include/db_con.php");
	include("include/routines.php");
	
	include("include/query-helper.php");
	
	checkuser();
	
	
	chkRights(basename($_SERVER['PHP_SELF']));
	
	// This is for dynamic title, bread crum, etc.
	if(isset($_GET['pag']))
	{
		$title = $_GET['pag'];
	}
  $path_parts      = pathinfo(__FILE__);
  $filename        = $path_parts['filename'].".php";
  $sql_feature     = "select * from tbl_admin_features where af_page_url = '".$filename."'";
  $result_feature  = mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
  $row_feature     = mysqli_fetch_row($result_feature);
  $feature_name    = $row_feature[1];
  $home_name       = "Home";
  $home_url        = "view_dashboard.php?pag=Dashboard";
  $utype           = $_SESSION['panel_user']['utype'];
  $tbl_users_owner = $_SESSION['panel_user']['tbl_users_owner'];
  $start_offset    = 0;
?>
<!doctype html>
<html>
<head>
      <?php
        /* This function used to call all header data like css files and links */
        headerdata($feature_name);
        /* This function used to call all header data like css files and links */
    	?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" media="all">
</head>
<body  class="<?php echo $theme_name;?>" data-theme="<?php echo $theme_name;?>" >
<?php
		/*include Bootstrap model pop up for error display*/
		modelPopUp();
		/*include Bootstrap model pop up for error display*/
		/* this function used to add navigation menu to the page*/
		navigation_menu();
		/* this function used to add navigation menu to the page*/
		?>
<!-- Navigation Bar -->
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
                <h3 style="margin-right:50px"> <i class="icon-dashboard"></i><?php echo $feature_name; ?> </h3>
                <div style="clear:both;"></div>
              </div>
              <!-- header title-->
              
              <div class="box-content nopadding accordion-content well">
                <div style="clear:both">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 text-center">
                        <div class="col-sm-12 col-md-3">
                          <div class="panel panel-primary">
                            <div class="panel-heading">
                              <div class="row">
                                <div class="col-sm-12 col-md-4 text-center">
                                <i style="color:#FFF" class="fa fa-futbol-o fa-4x"></i></div>
                                <div class="col-sm-12 col-md-8 text-center">
                                  <div class="huge" style="color:#FFF">
                                    <h2>
                                    <?php
                  										$num  = isExist('tbl_competition',array("competition_status"=>1));
                  										echo $num;
                  									?>
                                    </h2>
                                  </div>
                                  <div style="color:#FFF">
                                    <h4>Total Competitions</h4>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> <!--col-3 end-->
                        <div class="col-sm-12 col-md-3">
                          <div class="panel panel-primary">
                            <div class="panel-heading">
                              <div class="row">
                                <div class="col-sm-12 col-md-4 text-center">
                                <i style="color:#FFF" class="fa fa-users fa-4x"></i></div>
                                <div class="col-sm-12 col-md-8 text-center">
                                  <div class="huge" style="color:#FFF">
                                    <h2>
                                    <?php
                  										$num  = isExist('tbl_team',array("team_status"=>1));
                  										echo $num;
                  									?>
                                    </h2>
                                  </div>
                                  <div style="color:#FFF">
                                    <h4>Total Teams</h4>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> <!--col-3 end-->
                        <div class="col-sm-12 col-md-3">
                          <div class="panel panel-primary">
                            <div class="panel-heading">
                              <div class="row">
                                <div class="col-sm-12 col-md-4 text-center">
                                <i style="color:#FFF" class="fa fa-users fa-4x"></i></div>
                                <div class="col-sm-12 col-md-8 text-center">
                                  <div class="huge" style="color:#FFF">
                                    <h2>
                                    <?php
										$num  = isExist('tbl_batches',array("batch_status"=>1));
										echo $num;
									?>
                                    </h2>
                                  </div>
                                  <div style="color:#FFF">
                                    <h4>Total Batches</h4>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> <!--col-3 end-->
                        <div class="col-sm-12 col-md-3">
                          <div class="panel panel-primary">
                            <div class="panel-heading">
                              <div class="row">
                                <div class="col-sm-12 col-md-4 text-center">
                                <i style="color:#FFF" class="fa fa-user fa-4x"></i></div>
                                <div class="col-sm-12 col-md-8 text-center">
                                  <div class="huge" style="color:#FFF">
                                    <h2>
                                    <?php
										$num  = isExist('tbl_students',array("student_status"=>1));
										echo $num;
									?>
                                    </h2>
                                  </div>
                                  <div style="color:#FFF">
                                    <h4>Total Students</h4>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> <!--col-3 end-->
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