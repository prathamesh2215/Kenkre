<?php
include("include/db_con.php");
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
$title = "View Students";
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
<link rel="stylesheet" href="css/datepicker.css" />
<style>
.head2
{
	font-size:17px;
}
</style>

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
                    breadcrumbs($home_url,$home_name,' Students',$filename,$feature_name); 
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
                                        
                                         <div style="float:right;width:;"> 
                                       <select name="ft_age" id="ft_age"  class="select2-me input-medium" data-rule-required="true" onChange="loadData();">
                                         <option value="">Age Group</option>
                                         <option value="6">Under 6</option>
                                         <option value="10">Under 10</option>
                                       	 <option value="15">Under 15</option>
                                         <option value="20">Under 20</option>
                                       </select>
                                        </div>
                                        
                                        <div style="float:right;width:;"> 
                                       <select name="ft_type" id="ft_type"  class="select2-me input-medium" data-rule-required="true" onChange="loadData();">										 <option value="">All</option>
                                         <option value="Kenkre">Kenkre</option>
                                         <option value="Other">Others</option>
                                       </select>
                                        </div>
                                        
                                         <div style="float:right;width:;"> 
                                       <select name="ft_team" id="ft_team"  class="select2-me input-medium" data-rule-required="true" onChange="loadData();">							 <option value="">All Team</option>
                                         <?php
										 	
											$sql_get_team =" SELECT team_id,team_name FROM tbl_team WHERE team_id IN ( SELECT DISTINCT team_id FROM tbl_team_students)";
											$res_get_team = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
											while($row_get_team =mysqli_fetch_array($res_get_team))
											{
												echo '<option value="'.$row_get_team['team_id'].'">'.ucwords($row_get_team['team_name']).'</option>';
											}
										 ?>
                                        
                                       </select>
                                        </div>
                                        
                                        
                                         <div style="float:right;width:;"> 
                                           <select name="ft_center" id="ft_center"  class="select2-me input-medium" data-rule-required="true" onChange="loadData();">						     <option value="">All Center</option>
                                              <?php
                                                
                                                $sql_get_team =" SELECT center_id,center_name FROM tbl_centers WHERE center_id IN ( SELECT DISTINCT batch_center FROM tbl_students)";
                                                $res_get_team = mysqli_query($db_con,$sql_get_team) or die(mysqli_error($db_con));
                                                while($row_get_team =mysqli_fetch_array($res_get_team))
                                                {
                                                    echo '<option value="'.$row_get_team['center_id'].'">'.ucwords($row_get_team['center_name']).'</option>';
                                                }
                                             ?>
                                          </select>
                                       
                                        </div>
                                       
                                        
                                    </div> <!-- header title-->

                                    <div class="box-content nopadding">
                                                                        
                                   
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
                    </div>  <!-- view Area -->
                             
                </div>
            </div>
        </div>
            <?php getloder();?>
        <script type="text/javascript">
		
		function loadData()
		{
			loading_show();
			ft_age	         = $("#ft_age").val();	
			ft_team	         = $("#ft_team").val();	
			ft_center	     = $("#ft_center").val();	
			ft_type	         = $("#ft_type").val();	
					
								
			
			var sendInfo = {"ft_center":ft_center,"ft_type":ft_type,"ft_age":ft_age,"ft_team":ft_team, "load_student":1};
			var ind_load = JSON.stringify(sendInfo);				
			$.ajax({
				url: "load_student_report.php?",
				type: "POST",
				data: ind_load,
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
			
		}   /*Load Data*/
		
		
				
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
			<?php
			if(isset($_REQUEST['student_id']) && $_REQUEST['student_id']!='')
			{?>
			    addMoreArea(<?php echo $_REQUEST['student_id']; ?>,'view')
			<?php 
			}
			else
			{
			?>
			loadData();
			<?php 
			} 
			?>
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
		});
		
	    function downld1()
		{
			//loading_show();
			ft_age	         = $("#ft_age").val();	
			ft_team	         = $("#ft_team").val();	
			ft_center	     = $("#ft_center").val();	
			ft_type	         = $("#ft_type").val();	
					
			var sendInfo = {"ft_center":ft_center,"ft_type":ft_type,"ft_age":ft_age,"ft_team":ft_team, "excelDownload":1};
			var ind_load = JSON.stringify(sendInfo);				
			$.ajax({
				url: "load_student_report.php?",
				type: "POST",
				data: ind_load,
				contentType: "application/json; charset=utf-8",						
				success: function(response) 
				{
					data = JSON.parse(response);
					if(data.Success == "Success") 
					{
						window.location.href=data.resp;
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
		</script>     
		<script src="js/bootstrap-datepicker.js"></script>   
		
    </body>
</html>
