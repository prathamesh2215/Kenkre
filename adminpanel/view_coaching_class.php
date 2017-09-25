<?php
	include("include/routines.php");	
	checkuser();
	chkRights(basename($_SERVER['PHP_SELF']));
	if(isset($_GET['pag']))
	{
		$title 	= $_GET['pag'];
	}
	else
	{
		$title	= "Organisation";	
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
	$uid				= $_SESSION['panel_user']['id'];
	$tbl_users_owner 	= $_SESSION['panel_user']['tbl_users_owner'];
?>
<!DOCTYPE html>
<html>
    <head>

<link href="css/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
<link href="css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />

<!-- datepicker end here-->
		<?php 
            /* This function used to call all header data like css files and links */
            headerdata($feature_name);
            /* This function used to call all header data like css files and links */	
        ?>	
    </head>
    
    <body class="<?php echo $theme_name; ?>" data-theme="<?php echo $theme_name; ?>">
    	<?php 
			/*include Bootstrap model pop up for error display*/
			modelPopUp();
			/*include Bootstrap model pop up for error display*/
            /* this function used to add navigation menu to the page*/ 
            navigation_menu();
            
        ?> <!-- Navigation Bar --> 
		<div class="container-fluid" id="content">
            <div id="main" style="margin-left:0px !important">
                <div id="div_view_class"> 
                <div class="container-fluid"> 
                    <?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,'View Classes',$filename,$feature_name); 
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
                                	<?php
                                        $add = checkFunctionalityRight($filename,0);
                                        if($add)
                                        {
                                            ?>
                                            <button type="button" class="btn-info" onClick="addMoreClass('','add')" ><i class="icon-plus"></i>&nbspAdd Class</button>
                                            <?php		
                                        }
                                    ?> 
                                    <div style="padding:10px 15px 10px 15px !important">
                                        <input type="hidden" name="hid_page" id="hid_page" value="1">
                                        <select name="rowlimit" id="rowlimit" onChange="loadData();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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
                                </div>	<!-- Main Body -->
                            </div>
                                	<?php
                                        $add = checkFunctionalityRight($filename,0);
                                        $edit = checkFunctionalityRight($filename,1);
                                        if($add || $edit)
                                        {
                                            ?>
                				 <div class="box box-color box-bordered" style="display:none">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        Excel Bulk Upload For Organisation
                                    </h3>
                                   
                                </div> <!-- header title-->
                                <div class="box-content nopadding">
                                    <div class="profileGallery">
                                        <div style="width:50%;" align="center">
                                            <div id="loading"></div>                                            
                                            <div id="container2">
                                                <div class="data">
                                                    <form method="post" class="form-horizontal form-bordered form-validate" enctype="multipart/form-data" id="frm_org_excel">
                                                        <div class="control-group">
                                                            <label for="tasktitel" class="control-label">Select file </label>
                                                            <div class="controls">
                                                                <input type="file" name="file" id="file" data-rule-required="true" data-rule-extension="xlsx"/>
                                                            </div>
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
                            
				 <div class="box box-color box-bordered" style="display:none">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        Wrong Entries For Organisation
                                    </h3>
                                   
                                </div> <!-- header title-->
                                <div class="box-content nopadding">
                                    <div style="padding:10px 15px 10px 15px !important">
                                        <input type="hidden" name="hid_page1" id="hid_page1" value="1">
                                        <input type="hidden" name="cat_parent1" id="cat_parent1" value="Parent">
                                        <select name="rowlimit1" id="rowlimit1" onChange="loadData1();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "srch1" name="srch1" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
                                    </div>
                                    <div id="req_resp1"></div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="center">
                                            <div id="loading"></div>                                            
                                            <div id="container3">
                                                <div class="data"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                            <?php		
                                        }
                                    ?>                             

                        </div>
                    </div>
                </div> <!-- View Organisation -->
                
                <div id="div_add_org" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Class',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Class
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_add_org','div_view_class'); loadData();"><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_class" class="form-horizontal form-bordered form-validate" >
                                        <input type="hidden" name="add_clas_request" value="1" >
                                        <div id="div_add_org_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Add Organisation -->
                
                <div id="div_edit_org" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Organisation',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Class
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_edit_org','div_view_class'); loadData();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_class" class="form-horizontal form-bordered form-validate" >
                                           <input type="hidden" name="update_clas_request" value="1" >
                                        <div id="div_edit_org_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Edit Organisation -->
                
                <div id="div_view_class_details" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'View Details Of Class',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            View Details Of Class
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_view_class_details','div_view_class'); loadData();"  ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_view_org_details" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_class_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- View Details Organisation -->
                
                <div id="div_error_org" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Update Wrong Entry Of Organisation',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Update Wrong Entry Of Organisation
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="location.reload();" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_error_org" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_error_org_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Error Organisation -->
                
                <div class="container-fluid" id="div_view_branch" style="display:none;"> 
                    <?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,"Branches",$filename,$feature_name); 
                    /* this function used to add navigation menu to the page*/ 
                    ?>          
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                              
                                    <h3>
                                    <span id="show_classname"></span>
                                        <i class="icon-arrow-right"></i>
                                        Branches
                                    </h3>&nbsp;&nbsp; 
                                    
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_view_branch','div_view_class'); loadData();" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                    
                                </div> <!-- header title-->
                              <div class="box-content nopadding">
                                	<?php
                                        $add = checkFunctionalityRight($filename,0);
                                        if($add)
                                        {
                                            ?>
                                            <button type="button" class="btn-info" onClick="addMoreBranch('','add')" ><i class="icon-plus"></i>&nbspAdd Branch</button>
                                            <?php		
                                        }
                                    ?> 
                                    <div style="padding:10px 15px 10px 15px !important">
                                         <input type="hidden" name="class_id" id="class_id" value="">
                                        <input type="hidden" name="hid_page2" id="hid_page2" value="1">
                                        <select name="rowlimit2" id="rowlimit2" onChange="branch();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "srch2" name="srch2" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
                                    </div>
                                    <div id="req_resp"></div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="center">
                                            <div id="loading"></div>                                            
                                            <div id="branches" class="data_container">
                                                <div class="data"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>	<!-- Main Body -->
                            </div>
                        </div>
                    </div>
                </div> <!-- View Branches -->
                
			    <div id="div_add_branch" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Branch',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Branch
                                        </h3>
                                        <h3 style="color:#060606; margin-left:10px" id="ashow_classname" ></h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_add_branch','div_view_branch');" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_branch" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_branch_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Add Branch-->
                
			    <div id="div_edit_branch" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Branch',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Branch
                                        </h3>&nbsp;&nbsp;
                                        <h3 style="color:#060606; margin-left:10px"" id="eshow_classname"></h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_edit_branch','div_view_branch');" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_branch" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_branch_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Edit Branch-->
                
                <div id="div_view_branch1" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'View Branch',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Branch Details
                                        </h3>
                                        <h3 style="color:#060606; margin-left:10px"" id="vshow_classname"></h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_view_branch1','div_view_branch');" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_branch" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_branch_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- View Branch-->
                
                <div class="container-fluid" id="div_view_offering" style="display:none;"> 
                    <?php 
                    /* this function used to add navigation menu to the page*/ 
                    breadcrumbs($home_url,$home_name,"Branch Offerings",$filename,$feature_name); 
                    /* this function used to add navigation menu to the page*/ 
                    ?>          
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-table"></i>
                                        Branch Offerings
                                    </h3>
                                    <h3 style="margin-left:10px;color:#000" id="show-branch-name">
                                        
                                    </h3>
                                        <button type="button" id="branch_id1" value="" class="btn-info_1" style= "float:right" onClick="backtomain('div_view_offering','div_view_branch');branch(this.value);" ><i class="icon-arrow-left"></i>&nbsp Back </button>                                    
                                </div> <!-- header title-->
                              <div class="box-content nopadding">
                                    <?php
                                        $add = checkFunctionalityRight($filename,0);
                                        if($add)
                                        {
                                            ?>
                                            <button type="button" class="btn-info" onClick="addMoreOffering('','add')" ><i class="icon-plus"></i>&nbspAdd Offering</button>
                                            <?php       
                                        }
                                    ?> 
                                    <div style="padding:10px 15px 10px 15px !important">
                                         <input type="hidden" name="branch_id" id="branch_id" value="">
                                        <input type="hidden" name="hid_page3" id="hid_page3" value="1">
                                        <select name="rowlimit3" id="rowlimit3" onChange="Offering();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <input type="text" class="input-medium" id = "srch3" name="srch3" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
                                    </div>
                                    <div id="req_resp"></div>
                                    <div class="profileGallery">
                                        <div style="width:99%;" align="center">
                                            <div id="loading"></div>                                            
                                            <div id="Offerings" class="data_container">
                                                <div class="data"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  <!-- Main Body -->
                            </div>
                        </div>
                    </div>
                </div> <!-- View Offering -->
                
                <div id="div_add_offering" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Add Offering',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Add Offering
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_add_offering','div_view_offering');" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_add_ofering" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_add_offering_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Add Offering-->
				 <div id="div_edit_Offering" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'Edit Offering',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Edit Offering
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_edit_Offering','div_view_offering');" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_edit_offering" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_edit_offering_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- Edit offering-->
                
                
			    
				 <div id="div_view_offering1" style="display:none;">
                	<div class="container-fluid"> 
                        <?php 
                        /* this function used to add navigation menu to the page*/ 
                        breadcrumbs($home_url,$home_name,'View Offering',$filename,$feature_name); 
                        /* this function used to add navigation menu to the page*/ 
                        ?>          
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box box-color box-bordered">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-table"></i>
                                            Offering Detail
                                        </h3>
                                        <button type="button" class="btn-info_1" style= "float:right" onClick="backtomain('div_view_offering1','div_view_offering');" ><i class="icon-arrow-left"></i>&nbsp Back </button>
                                    </div> <!-- header title-->
                                    <div class="box-content nopadding" >
                                    	<form id="frm_ewvt_branch" class="form-horizontal form-bordered form-validate" >
                                        <div id="div_view_offering_part">
                                        </div>                                    
                                        </form>
                                    </div>	<!-- Main Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	<!-- View offering-->
                
			</div>
        </div>
        <?php getloder();?>
        <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
		<script type="text/javascript">
			$( document ).ready(function() 
			{
				$('#srch').keypress(function(e) 
				{
					if(e.which == 13) 
					{	
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
				$('#srch2').keypress(function(e) 
				{
					if(e.which == 13) 
					{	 
					    var class_id =$("#class_id").val();	
						$("#hid_page2").val("1");					
						branch(class_id);	
					}
				});
				$('#srch3').keypress(function(e) 
				{
					if(e.which == 13) 
					{	 
					    var branch_id =$("#branch_id").val();	
						$("#hid_page3").val("1");					
						Offering(branch_id);	
					}
				});
				loadData();
				<?php
					$add = checkFunctionalityRight($filename,0);
					$edit = checkFunctionalityRight($filename,1);
					if($add || $edit)
					{
						?>				
							loadData1();
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
				
				$('#product_data .pagination li.active').live('click',function()
				{
					var page = $(this).attr('p');
					$("#hid_page3").val(page);
					var branch_id =$("#branch_id").val();
					Offering(branch_id);
				});			
				$('#Offerings .pagination li.active').live('click',function()
				{
					var page = $(this).attr('p');
					$("#hid_page3").val(page);
					var branch_id =$("#branch_id").val();
					Offering(branch_id);
				});		
				CKEDITOR.replace( 'class_desc',{height:"150", width:"100%"});
			});	
			function loadProductData()
			{
				loading_show();
				row_limit   = $.trim($('select[name="prod_row_limit"]').val());
				page        = $.trim($("#hid_prod_page").val());
				org_id		= $.trim($('#org_id').val());
				
				load_class = "1";			
				
				if(row_limit == "" && page == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
					$('#error_model').modal('toggle');			
				}
				else
				{
					var sendInfo 		= {"row_limit":50,"row_limit":row_limit, "loadOrgProduct":1, "page":page,"org_id":org_id};
					var prod_load = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_products.php",
						type: "POST",
						data: prod_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#product_data").html(data.resp);
								loading_hide();
							} 
							else if(data.Success == "fail") 
							{
								$("#product_data").html('<span style="style="color:#F00;">'+data.resp+'</span>');
								$("#product_data").html('');
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
			}			
		
			
			function loadData()
			{
				loading_show();
				row_limit   = $.trim($('select[name="rowlimit"]').val());
				search_text = $.trim($('#srch').val());
				page        = $.trim($("#hid_page").val());
				
				load_class = "1";			
				
				if(row_limit == "" && page == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
					$('#error_model').modal('toggle');			
				}
				else
				{
					var sendInfo 		= {"row_limit":row_limit, "search_text":search_text, "load_class":load_class, "page":page};
					var org_load = JSON.stringify(sendInfo);				
					$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: org_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								$("#container1").html(data.resp);
								loading_hide();
							} 
							else if(data.Success == "fail") 
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
							loading_hide();
							//alert("complete");
						}
					});
				}
			}
			
			function multipleDelete()
			{			
				loading_show();		
				var batch = [];
				$(".batch:checked").each(function ()
				{
					batch.push(parseInt($(this).val()));
				});

				delete_class 	= 1;
				var sendInfo 	= {"batch":batch, "delete_class":delete_class};
				var del_org 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coaching_class.php?",
					type: "POST",
					data: del_org,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
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
						loading_hide();
						//alert("complete");
					}
				});					
			}
			function changeStatus(class_id,curr_status)
			{
				loading_show();
				if(class_id == "" && curr_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
					$('#error_model').modal('toggle');
				}
				else
				{
					change_status 	= 1;
					var sendInfo 	= {"class_id":class_id, "curr_status":curr_status, "change_status":change_status};
					var cat_status 	= JSON.stringify(sendInfo);								
					$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: cat_status,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{			
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{							
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
							loading_hide();
							//alert("complete");
						}
					});						
				}
			}
			
			function changeBranchStatus(branch_id,curr_status)
			{
				loading_show();
				var class_id =$('#class_id').val();
				if(branch_id == "" && curr_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
					$('#error_model').modal('toggle');
				}
				else
				{
					change_status 	= 1;
					var sendInfo 	= {"branch_id":branch_id, "curr_status":curr_status, "change_branch_status":change_status};
					var cat_status 	= JSON.stringify(sendInfo);								
					$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: cat_status,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{			
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{							
								branch(class_id);
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
							//alert("complete");
						}
					});						
				}
			}
			
			function changeOfferingStatus(branchoff_id,curr_status)
			{
				//loading_show();
				var branch_id =$('#branch_id').val();
				
				
				if(branchoff_id == "" && curr_status == "")
				{
					$("#model_body").html('<span style="style="color:#F00;"> User id or Status to change not available</span>');
					$('#error_model').modal('toggle');
				}
				else
				{
					change_status 	= 1;
					var sendInfo 	= {"branchoff_id":branchoff_id, "curr_status":curr_status, "change_off_status":change_status};
					var cat_status 	= JSON.stringify(sendInfo);								
					$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: cat_status,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{			
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{							
								Offering(branch_id);
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
							//alert("complete");
						}
					});						
				}
			}
			
			function multipleBranchDelete()
			{			
				loading_show();		
				var branch = [];
				$(".branch:checked").each(function ()
				{
					branch.push(parseInt($(this).val()));
				});
                var class_id = $('#class_id').val();
			
				delete_branch 	= 1;
				var sendInfo 	= {"branch":branch, "delete_branch":delete_branch};
				var del_org 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coaching_class.php?",
					type: "POST",
					data: del_org,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
						    branch_d(class_id);
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
						//alert("complete");
					}
				});					
			}
			
			function  branch_d(class_id)
			{
				branch(class_id);
			}
			function multipleOfferingDelete(branch_id)
			{			
				loading_show();		
				var offering = [];
				$(".offering:checked").each(function ()
				{
					offering.push(parseInt($(this).val()));
				});

				delete_offering 	= 1;
				var sendInfo 	= {"offering":offering, "delete_offering":delete_offering};
				var del_org 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coaching_class.php?",
					type: "POST",
					data: del_org,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{	
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{						
						 Offering(branch_id);
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
						//alert("complete");
					}
				});					
			}
			
		
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
					return false;
				return true;
			}
			
			function ValidateMobile(mobileid) //This is for validating mobile number
			{
				var mobilenum = document.getElementById(mobileid).value;
				var firstdigit = mobilenum.charAt(0);
			
				if(firstdigit==0)
				{
					$("#"+mobileid).attr('maxlength','11');
				}
				else
				{
					$("#"+mobileid).attr('maxlength','10');
				}
			}
			
			function EMail(comp1_id, comp1_val)
			{
				var email_id = comp1_id;
				
				var val_email = 'val_email';
				var sendInfo = {"val_email":val_email, "comp1_val":comp1_val};
				var org_load = JSON.stringify(sendInfo);
				$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: org_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								if(email_id == 'pri_email')
								{
									$('#comp_pri').html('<br/><span style="color:#0C0;margin-left:15px;">Emalid available</span>').show().fadeOut(5000);
								}
								else if(email_id == 'sec_email')
								{
									$('#comp_sec').html('<br/><span style="color:#0C0;margin-left:15px;">Emalid available</span>').show().fadeOut(5000);
								}
								else
								{
									$('#comp_ter').html('<br/><span style="color:#0C0;margin-left:15px;">Emalid available</span>').show().fadeOut(5000);	
								}
								loading_hide();
							} 
							else if(data.Success == "fail") 
							{
								if(email_id == 'pri_email')
								{
									$('#comp_pri').html('<br/><span style="color:#F00;margin-left:15px;">'+ data.resp +'</span>').show().fadeOut(7000);
								}
								else if(email_id == 'sec_email')
								{
									$('#comp_sec').html('<br/><span style="color:#F00;margin-left:15px;">'+ data.resp +'</span>').show().fadeOut(7000);
								}
								else
								{
									$('#comp_ter').html('<br/><span style="color:#F00;margin-left:15px;">'+ data.resp +'</span>').show().fadeOut(7000);
								}
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
			
			function moveToOrgView()
			{
				document.location.href='view_coaching_class.php?pag=Organization';	
			}
			
			function addMoreClass(class_id,req_type)
			{   
				$('#div_view_class').css("display", "none");
				
				if(req_type == "add")
				{    
				    
					$('#div_add_org').css("display", "block");
								
				}
				else if(req_type == "edit")
				{
					$('#div_edit_org').css("display", "block");	
								
				}	
				else if(req_type == "error")
				{
					$('#div_error_org').css("display", "block");				
				}
				else if(req_type == "view")
				{
					$('#div_view_class_details').css("display", "block");
										
				}							
				var sendInfo = {"class_id":class_id,"req_type":req_type,"load_add_class_part":"1"};
				var cat_load = JSON.stringify(sendInfo);
				$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: cat_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{
								if(req_type == "add")
								{
									$("#div_edit_org_part").html(" ");
									$("#div_view_class_part").html(" ");
									$("#div_add_org_part").html(data.resp);
									
								}
								else if(req_type == "edit")
								{
									$("#div_add_org_part").html(" ");
									$("#div_view_class_part").html(" ");
									$("#div_edit_org_part").html(data.resp);
													
								}	
								else if(req_type == "error")
								{
									$("#div_error_org_part").html(data.resp);				
								}
								else if(req_type == "view")
								{
									$("#div_add_org_part").html(" ");
									$("#div_edit_org_part").html(" ");
									$("#div_view_class_part").html(data.resp);
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
			
			function addMoreBranch(branch_id,req_type)
			{
			    $('#div_view_branch').css("display","none");
			
				var class_id = $('#class_id').val();
				if(req_type == "add")
				{
					$('#div_add_branch').css("display", "block");				
				}
				else if(req_type == "edit")
				{
					$('#div_edit_branch').css("display", "block");				
				}	
				else if(req_type == "error")
				{
					$('#div_error_branch').css("display", "block");				
				}
				else if(req_type == "view")
				{
					$('#div_view_branch1').css("display", "block");				
				}	
									
				var sendInfo = {"class_id":class_id,"branch_id":branch_id,"req_type":req_type,"load_branch_part":"1"};
				var cat_load = JSON.stringify(sendInfo);
				$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: cat_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{  	
							data = JSON.parse(response);
							
							if(data.Success == "Success") 
							{
								$("#div_add_branch_part").html(" ");
								$("#div_edit_branch_part").html(" ");	
								$('#show_classname').html(" ");
								$('#vshow_classname').html(" ");
								if(req_type == "add")
								{
									$('#ashow_classname').html(data.class_name);
									$("#div_add_branch_part").html(data.resp);
								}
								else if(req_type == "edit")
								{   
								    $('#eshow_classname').html(data.class_name);
									$("#div_edit_branch_part").html(data.resp);				
								}	
								else if(req_type == "error")
								{   
									$('#show_classname').html(data.class_name);
									$("#div_add_branch_part").html(data.resp);				
								}
								else if(req_type == "view")
								{   
									$('#vshow_classname').html(data.class_name);
									$("#div_view_branch_part").html(data.resp);
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
			
			$(document).change(function () 
			{
				if($("#address_check").prop('checked') == true)
				{
					org_bill_addrs = $.trim(CKEDITOR.instances['org_bill_addrs'].getData());
					CKEDITOR.instances['org_ship_addrs'].setData(org_bill_addrs);
					CKEDITOR.instances['org_ship_addrs'].setReadOnly(true);	// disable ckeditor 
					/* state select change*/
					bill_state = $("#bill_state").val();
					$("#ship_state").val(bill_state);
					$("#ship_state").prop("disabled",true); // disable  state select
					$("#ship_state").select2(); 				
					/* state select change*/
					/* City select change*/				
					getCity(bill_state,'ship_city');
					stopExecution();
				}
				else if($("#address_check").prop('checked') == false)
				{
					CKEDITOR.instances['org_ship_addrs'].setReadOnly(false);	// enable ckeditor 				
					$("#ship_state").prop( "disabled", false );	// enable  state select			
					$("#ship_state").select2();
					$("#ship_city").prop("disabled",false);
					$("#ship_city").select2();				
					$("#ship_pincode").prop("disabled",false);
				}						
			});
			
			
		

			
			
			function loadData1()
			{
				loading_show();
				row_limit1 		= $.trim($('select[name="rowlimit1"]').val());
				search_text1 	= $.trim($('#srch1').val());
				page1 			= $.trim($("#hid_page1").val());
				cat_parent1		= $.trim($('#cat_parent1').val());
				load_error 	= "1";			
				
				if(row_limit1 == "" && page1 == "")
				{
					$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');
					$('#error_model').modal('toggle');				
				}
				else
				{
					var sendInfo_error 		= {"row_limit1":row_limit1, "search_text1":search_text1, "load_error":load_error, "page1":page1,"cat_parent1":cat_parent1};
					var cat_load_error = JSON.stringify(sendInfo_error);				
					$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: cat_load_error,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{
							data = JSON.parse(response);
							
							if(data.Success == "Success") 
							{
								$("#container3").html(data.resp);
								loading_hide();
							} 
							else if(data.Success == "fail") 
							{
								$("#container3").html('<span style="style="color:#F00;">'+data.resp+'</span>');														
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
			
			function multipleDelete_error()
			{
				loading_show();
				var batch = [];
				$(".error_batch:checked").each(function ()
				{
					batch.push(parseInt($(this).val()));
				});
				if (typeof batch.length == 0)
				{
					alert("Please select checkbox to delete Organisation");				
				}
				else
				{
					//delete_catogery_error 	= 1;
					var sendInfo 	= {"batch":batch, "delete_org_error":1};
					var del_cat 	= JSON.stringify(sendInfo);	
					
					$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: del_cat,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{	
							data = JSON.parse(response);
							if(data.Success == "Success") 
							{	
								loadData1();
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
							//alert("complete");
						}
					});					
				}
			}
			
		$('#frm_org_excel').on('submit', function(e) 
		{
				e.preventDefault();
				if ($('#frm_org_excel').valid())
				{
					loading_show();	
					$.ajax({
							url: "load_coaching_class.php?",
							type: "POST",
							data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
							contentType: false,       // The content type used when sending data to the server.
							cache: false,             // To unable request pages to be cached
							processData:false,        // To send DOMDocument or non processed data file it is set to false
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									$("#req_resp").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									window.location.assign("view_coaching_class.php?pag=<?php echo $title; ?>");
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
			});
			
			
			
			
		$('#frm_add_class').on('submit', function(e){
				e.preventDefault();
				if ($('#frm_add_class').valid())
				{   
					
					loading_show();
					for(instance in CKEDITOR.instances) 
					{
						CKEDITOR.instances[instance].updateElement();
					}
					$.ajax({
							url: "load_coaching_class.php?",
							type: "POST",
							data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
							contentType: false,       // The content type used when sending data to the server.
							cache: false,             // To unable request pages to be cached
							processData:false,        // To send DOMDocument or non processed data file it is set to false
							success: function(response) 
							{
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									 alert("Class Added successfully ");
									 location.reload();
									$('#div_view_branch').css('display','block');
									$('#div_add_branch').css('display','none');
									branch(data.class_id);
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
								//alert("complete");
							}
						});
				
				
				}	
			});	
			
		$('#frm_edit_class').on('submit', function(e)
		{
				e.preventDefault();
				if ($('#frm_edit_class').valid())
				{    
					
					loading_show();
					for(instance in CKEDITOR.instances) 
					{
						CKEDITOR.instances[instance].updateElement();
					}
					$.ajax({
							url: "load_coaching_class.php?",
							type: "POST",
							data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
							contentType: false,       // The content type used when sending data to the server.
							cache: false,             // To unable request pages to be cached
							processData:false,        // To send DOMDocument or non processed data file it is set to false
							success: function(response) 
							{; //alert(response);
							
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{
									 alert("Class Updated successfully ");
									 location.reload();
									$('#div_view_branch').css('display','block');
									$('#div_add_branch').css('display','none');
									//branch(data.class_id);
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
								//alert("complete");
							}
						});
				
				
				}	
			});
				
				
		function branch(class_id)
		{
					loading_show();
					$('#class_id').val(class_id);
					$('#div_view_class').css('display','none');
					
					$('#div_view_branch').css('display','block');
					row_limit   = $.trim($('select[name="rowlimit2"]').val());
					search_text = $.trim($('#srch2').val());
					page        = $.trim($("#hid_page2").val());
					
					load_branch = "1";			
					
					if(row_limit == "" && page == "")
					{
						$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
						$('#error_model').modal('toggle');			
					}
					else
					{
						var sendInfo 		= {"class_id":class_id,"row_limit":row_limit, "search_text":search_text, "load_branch":load_branch, "page":page};
						var org_load = JSON.stringify(sendInfo);				
						$.ajax({
							url: "load_coaching_class.php?",
							type: "POST",
							data: org_load,
							contentType: "application/json; charset=utf-8",						
							success: function(response) 
							{  
								data = JSON.parse(response);
								$('#show_classname').html(data.class_name);
								if(data.Success == "Success")
								{
									$("#branches").html(data.resp);
									loading_hide();
								} 
								else if(data.Success == "fail") 
								{
									$("#branches").html(data.resp);
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
				
				
		$('#frm_add_branch').on('submit', function(e) 
		{
				e.preventDefault();
				if ($('#frm_add_branch').valid())
				{ 
					loading_show();	
					$.ajax({
							url: "load_coaching_class.php?",
							type: "POST",
							data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
							contentType: false,       // The content type used when sending data to the server.
							cache: false,             // To unable request pages to be cached
							processData:false,        // To send DOMDocument or non processed data file it is set to false
							success: function(response) 
							{ 
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{    
								    alert('Branch Added Successfullly');
									$('#div_view_branch').css('display','block');
									$('#div_add_branch').css('display','none');
									branch(data.class_id);
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
			});
			
	    $('#frm_edit_branch').on('submit', function(e) 
	    {
				e.preventDefault();
				if ($('#frm_edit_branch').valid())
				{
					var branch_location =$('#branch_location').val();
					if(branch_location !="")
					{
						
					var regex = "^[0-9 . _ ,0-9 .]*$";
					var regex = new RegExp("^[0-9 . _ ,0-9 .]*$");
					if (!regex.test(branch_location)) 
					{
						alert("Lat , Lan Not Valid");
						return false;
					}
				}
					
					loading_show();	
					$.ajax({
							url: "load_coaching_class.php?",
							type: "POST",
							data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
							contentType: false,       // The content type used when sending data to the server.
							cache: false,             // To unable request pages to be cached
							processData:false,        // To send DOMDocument or non processed data file it is set to false
							success: function(response) 
							{  
								data = JSON.parse(response);
								
								if(data.Success == "Success") 
								{   alert('Branch Updated Successfullly');
									$('#div_view_branch').css('display','block');
									$('#div_edit_branch').css('display','none');
									branch(data.class_id);
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
			});
				
		 $('#frm_add_ofering').on('submit', function(e) 
		{
		e.preventDefault();
		if ($('#frm_add_ofering').valid())
		{
		    var days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"]; 
			for(var i=0;i<days.length;i++)
			{
				var sdate = $('#'+days[i]+'_form').val();
				var edate = $('#'+days[i]+'_to').val();
				
				if(sdate !="")
				{   
				    
				    if(edate =="")
					{
						alert("Please Enter to Time for "+days[i]);
						return false;
					}
					
					if (sdate > edate)
					{
						alert ("The ending time should have been after the start time  for "+days[i]);
						return false;
						
					}
				}
			}
			loading_show();	
			$.ajax({
					url: "load_coaching_class.php?",
					type: "POST",
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					success: function(response) 
					{   
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{    
							alert("Offering added successfully");
							$('#div_add_offering').css('display','none');
							$('#div_view_offering').css('display','block');
							Offering(data.branch_id);
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
	});
			
	    $('#frm_edit_offering').on('submit', function(e) 
		{
				e.preventDefault();
				if ($('#frm_edit_offering').valid())
				{   
					var days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"]; 
					for(var i=0;i<days.length;i++)
					{
						var sdate = $('#'+days[i]+'_form').val();
						var edate = $('#'+days[i]+'_to').val();
						
						if(sdate !="")
						{   
							if(edate =="")
							{
								alert("Please Enter to Time for "+days[i]);
								return false;
							}
							
							if (sdate > edate)
							{
								alert ("The ending time should have been after the start time  for "+days[i]);
								return false;
								
							}
						}
					}
					loading_show();	
					$.ajax({
							url: "load_coaching_class.php?",
							type: "POST",
							data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
							contentType: false,       // The content type used when sending data to the server.
							cache: false,             // To unable request pages to be cached
							processData:false,        // To send DOMDocument or non processed data file it is set to false
							success: function(response) 
							{   
								data = JSON.parse(response);
								if(data.Success == "Success") 
								{   alert("Offering updated successfully");
									$('#div_view_offering').css('display','block');
									$('#div_edit_Offering').css('display','none');
									Offering(data.branch_id);
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
			});

        function Offering(branch_id)
        {
            loading_show();
            $('#div_view_branch').css('display','none');
            $('#div_view_offering').css('display','block');
            $('#branch_id').val(branch_id);
			var class_id = $('#class_id').val();
            $('#branch_id1').val(class_id);
			row_limit   = $.trim($('select[name="rowlimit3"]').val());
					search_text = $.trim($('#srch3').val());
					page        = $.trim($("#hid_page3").val());
					
					load_offering = "1";			
					
					if(row_limit == "" && page == "")
					{
						$("#model_body").html('<span style="style="color:#F00;">Can not Get Row Limit and Page number</span>');	
						$('#error_model').modal('toggle');			
					}
					else
					{
						var sendInfo 		= {"branch_id":branch_id,"row_limit":row_limit, "search_text":search_text, "load_offering":load_offering, "page":page};
						var org_load = JSON.stringify(sendInfo);				
						$.ajax({
							url: "load_coaching_class.php?",
							type: "POST",
							data: org_load,
							contentType: "application/json; charset=utf-8",						
							success: function(response) 
							{  
								data = JSON.parse(response);
								$('#show-branch-name').html(data.branch_name);
								if(data.Success == "Success") 
								{
									$("#Offerings").html(data.resp);
									loading_hide();
								} 
								else if(data.Success == "fail") 
								{
									$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
									$("#Offerings").html(data.resp);
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
     
	    function addMoreOffering(offering_id,req_type)
		{
				
				loading_show();
				$('#div_view_offering').css("display", "none");
				var branch_id = $('#branch_id').val();
				if(req_type == "add")
				{
					$('#div_add_offering').css("display", "block");	
								
				}
				else if(req_type == "edit")
				{   
					$('#div_edit_Offering').css("display", "block");
									
				}	
				else if(req_type == "error")
				{
					$('#div_error_offering').css("display", "block");				
				}
				else if(req_type == "view")
				{
					$('#div_view_offering1').css("display", "block");
									
				}	
									
				var sendInfo = {"offering_id":offering_id,"branch_id":branch_id,"req_type":req_type,"load_offering_part":"1"};
				var cat_load = JSON.stringify(sendInfo);
				$.ajax({
						url: "load_coaching_class.php?",
						type: "POST",
						data: cat_load,
						contentType: "application/json; charset=utf-8",						
						success: function(response) 
						{  	
							data = JSON.parse(response);
						
							if(data.Success == "Success") 
							{
								if(req_type == "add")
								{
									$("#div_edit_offering_part").html(" ");
									$("#div_view_offering_part").html(" ");
									$("#div_add_offering_part").html(data.resp);
									
								}
								else if(req_type == "edit")
								{  
								    
								    $("#div_add_offering_part").html(" ");
									$("#div_view_offering_part").html(" ");
									$("#div_edit_offering_part").html(data.resp);
												
								}	
								else if(req_type == "error")
								{
									$("#div_error_offering_part").html(data.resp);				
								}
								else if(req_type == "view")
								{  
								    $("#div_edit_offering_part").html(" ");
									$("#div_add_offering_part").html(" ");
									$("#div_view_offering_part").html(data.resp);
									
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
			
		function getoffering(off_type,branch_id)
		{
			
			loading_show();
			
				var sendInfo 	= {"off_type":off_type,"branch_id":branch_id,"get_offering":1};
				var cat_status 	= JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_coaching_class.php?",
					type: "POST",
					data: cat_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	
						    $('.edit_offering').select2("val", ""); 
										
							$("#offering").html(data.resp);
							
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
						//alert("complete");
					}
				});						
			
		
		}
			
		function delete_banner(img_id,class_id)
		{
			loading_show();
			var sendInfo 	= {"img_id":img_id,"class_id":class_id,"delete_banner":1};
			var cat_status 	= JSON.stringify(sendInfo);								
			$.ajax({
			url: "load_coaching_class.php?",
			type: "POST",
			data: cat_status,
			contentType: "application/json; charset=utf-8",						
			success: function(response) 
			{			
				data = JSON.parse(response);
				if(data.Success == "Success") 
				{							
					addMoreClass(class_id,"edit");	
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
       
        function numsonly(e)
        {    
              var unicode=e.charCode? e.charCode : e.keyCode
              
              if (unicode !=8 && unicode !=32)
              {  // unicode<48||unicode>57 &&
              if ( unicode<48||unicode>57  )  //if not a number
              return false //disable key press
              }
        }
		
		function backtomain(hide_div,show_div)
		{
			$('#'+hide_div).css('display','none');
			$('#'+show_div).css('display','block');
		}		
		</script>
        <script type="text/javascript">
        $(document).ready(function() {
           $(".select2-me").select2();
		});
     
        function checkchild(filt_id)
		{ 
			if($('#filter_parent'+filt_id).is(':checked'))
			{
			     $('.filters_child'+filt_id).prop('checked', true);
				 $('.filters_sub_child'+filt_id).prop('checked', true);
		    }
			else
			{
				$('.filters_child'+filt_id).prop('checked', false);
				$('.filters_sub_child'+filt_id).prop('checked', false);
			}
		}
		
		function checksubchild(filt_id)
		{  
			if($('#filter_child'+filt_id).is(':checked'))
			{ 
			    $('.filters_subchild'+filt_id).prop('checked', true);
		    }
			else
			{
				$('.filters_subchild'+filt_id).prop('checked', false);
			}
		}
		
		function checklevel(level_id)
		{  
			if($('#level_parent'+level_id).is(':checked'))
			{ 
			    $('.levels_child'+level_id).prop('checked', true);
		    }
			else
			{
				$('.levels_child'+level_id).prop('checked', false);
			}
		}
		
		function checkparent(level_id)
		{
			$('#level_parent'+level_id).prop('checked', true);
		}
		
		function charsnonly(e)
		{
  			  var unicode=e.charCode? e.charCode : e.keyCode
			  if (unicode !=8 && unicode !=32)
			  {  // unicode<48||unicode>57 &&
     		  if ( unicode<48||unicode>57 && unicode<65||unicode>90 && unicode<97||unicode>122  )  //if not a number
          	  return false //disable key press
              }
		}
		
		function getsubcat(req_type,div_id)
		{
			$('#catid').select2()
			$('select').select2();
			var cat_ids = $('#parent_catid'+div_id).val();
			if(cat_ids.length==0)
			{
				return false;
			}
			loading_show();
			var sendInfo 	= {"req_type":req_type,"cat_ids":cat_ids,"get_subcat":1};
			var cat_data 	= JSON.stringify(sendInfo);								
			$.ajax({
			url: "load_coaching_class.php?",
			type: "POST",
			data: cat_data,
			contentType: "application/json; charset=utf-8",						
			success: function(response) 
			{		
		     	$('#catid'+div_id).select2()
			   	data = JSON.parse(response);
				$('#catid'+div_id).html(data.resp);
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
		
		function allDays()
		{
			
			if($("#checkall").attr("checked")) 
				{
					from = $("#Monday_form").val();
					to   = $("#Monday_to").val();
					
					$(".form_time").val(from);
					$(".to_time").val(to);
					 
					$(".cdays").prop("checked",true);
				} 
				else 
				{
					from = $("#Monday_form").val();
					to   = $("#Monday_to").val();
					
					$(".form_time").val('');
					$(".to_time").val('');
					 
					$(".cdays").prop("checked",false);
					$("#Monday_form").val(from);
					$("#Monday_to").val(to);
				}
		}
		
		function addBranch()
		{
			loading_show();
			addBranchCount =(parseInt($('#addBranchCount').val()))+1;
			var sendInfo 	= {"div_id":addBranchCount,"addBranchPart":1};
			var cat_data 	= JSON.stringify(sendInfo);								
			$.ajax({
			url: "load_coaching_class.php?",
			type: "POST",
			data: cat_data,
			contentType: "application/json; charset=utf-8",						
			success: function(response) 
			{		
		     	data = JSON.parse(response);
				$('#addBranchPart').append(data.resp).find('#addBranchPart'+addBranchCount).slideDown("slow");
				$('#addBranchCount').val(addBranchCount);
				$('#remove_btn').prop('disabled',false);
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
		
		function removeBranch()
		{
			loading_show();
			addBranchCount =(parseInt($('#addBranchCount').val()));
			$('#addBranchPart'+addBranchCount).remove().find('#addBranchPart'+addBranchCount).slideDown("slow");
			
			if(addBranchCount==2)
			{
				$('#remove_btn').prop('disabled',true)
			}
			$('#addBranchCount').val((addBranchCount-1));
			loading_hide();
			
		}
        </script>
 <script type="text/javascript" src="bootstrap/js/bootstrap-timepicker.min.js"></script>   
 <script type="text/javascript" src="bootstrap/js/bootstrap-timepicker.js"></script>       


    </body>
</html>
