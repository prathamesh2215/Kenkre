<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
if(isset($_GET['pag']))
{
	$title = $_GET['pag'];
}
$path_parts   	= pathinfo(__FILE__);
$filename 	 	= $path_parts['filename'].".php";
$sql_feature 	= "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature = mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  	= mysqli_fetch_row($result_feature);
$feature_name 	= $row_feature[1];
$home_name    	= "Home";
$home_url 	  	= "view_dashboard.php?pag=Dashboard";

$assigntype 	= array();
$start_offset   = 0;
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "changeStatus")
{
	$userid 	= mysqli_real_escape_string($db_con,$_POST['']);
	$newstatus	= mysqli_real_escape_string($db_con,$_POST['']);
	$sql_update_status = " UPDATE `tbl_admin_type` SET `status`= '".$newstatus."' WHERE `at_id` like '".$userid."' ";
	$result_update_status = mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	if($result_update_status)
	{
		echo "Success";
	}
	else
	{
		echo "Fail";
	}
}
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "loadusers" )
{
	if(isset($_POST['page']) && $_POST['page']!="")
	{
		$page			= mysqli_real_escape_string($db_con,$_POST['page']);
		$cur_page 		= mysqli_real_escape_string($db_con,$_POST['page']);
	}
	else
	{
		$page			= mysqli_real_escape_string($db_con,$_POST['page']);
		$cur_page 		= 1;
	}
	$page 	   	   		= $page - 1;
	if(isset($_POST['row_limit']) && $_POST['row_limit']!="")
	{
		$per_page 		= mysqli_real_escape_string($db_con,$_POST['row_limit']);
	}
	else
	{
		$per_page 		= 10;
	}

	$start_offset 		+= $page * $per_page;

	$start 				= $page * $per_page;
	$query_value = "SELECT * FROM `tbl_admin_type` where 1=1 ";
	if(isset($_POST['search_txt']) && $_POST['search_txt']!="")
	{		
		$search_txt 	= trim(mysqli_real_escape_string($db_con,$_POST['search_txt']));
		$query_value 	.= " and at_name like '%".$search_txt."%' ";
	}
	$count 				= dataPagination($query_value,$per_page,$start,$cur_page);
	$query_value 		.= " LIMIT $start, $per_page ";
	$result 			= mysqli_query($db_con,$query_value) or die(mysqli_error($db_con));
	if(!$count=='0')
	{	
		?>
		<form method="post" id="assign_type_anyway_form" onSubmit="return delete_assigntype();">
        <table id="tblassigntype" class="table table-bordered dataTable dataTable-scroll-x" width="98%">
            <thead>
              <tr>
                <th>Sr. No.</th>
                <th>User Level Name</th>
                <th>Created Date</th>
                <th>Created By</th>
                <th>Modified Date</th>
                <th>Modified By</th>
				<th>Status</th>
                <th style="text-align:center">Edit</th>
                <th style="text-align:center">
                    <!--<input type="checkbox" id="selectall" />Select all-->
                    <input type="button" class="btn-danger" value="Delete" onClick="delete_assigntype()"/>
                </th>
              </tr>
             </thead>
            <tbody>
              <?php  
					while($row = mysqli_fetch_array($result))
					{			
						$start_offset++; ?>
                		<tr>
                    		<td><?php print $start_offset; ?></td>
                    		<td><?php echo $row['at_name']; ?></td>
                    		<td ><?php echo $row['createddt']; ?></td>
                    		<td><?php echo $row['createdby']; ?></td>
                    		<td ><?php echo $row['modifieddt']; ?></td>
                    		<td><?php echo $row['modifiedby']; ?></td>                            
                    		<td><?php
									if($row['status'] == "1") 
									{
										?>
										<a href="#" onClick="changeStatus('<?php echo $row['at_id'];?>','0');"><span style="color:#0F0">Active</span></a>
                                        <?php
									}
									elseif($row['status'] == "0")
									{
										?>
										<a href="#" onClick="changeStatus('<?php echo $row['at_id'];?>','1');"><span style="color:#F00">Inactive</span></a>
                                        <?php
									}
							  	?>
							</td>                            
                    		<td><div align="center" style="margin-right:5px"><a href="edit_admintype.php?at_id=<?php echo $row['at_id'] ?>" title="Edit"  class="icon-edit">Edit</a></div></td>
                    		<td>
                        		<div align="center">
                        			<input type="checkbox" id="<?php echo $row['at_id']?>" name="batch[]" value="<?php echo $row['at_id']?>" class="css-checkbox"  >
				  					<label for="<?php echo $row['at_id']?>" class="css-label" style="color:#FFF"></label>                        
                        		</div>
                    		</td>			
                		</tr>
             <?php }?>
            </tbody>
        </table>
    </form>
		<?php echo $count;
	}
	else
	{
		echo "No Records found!!!";	
	}
	exit(0);
}
if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'delete_assign_type') 
{
	$ar_id	=	$_POST['batch'];

	foreach($ar_id as $at_id)
	{
		$sql_delete_feature 	= "delete from tbl_admin_type where at_id='$at_id'";
		$result_delete_feature 	= mysqli_query($db_con,$sql_delete_feature) or die(mysqli_error($db_con));
	}   
	exit(0); 
}
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'exporttoexcel')
{
	//--------------------------------------------------------------------------------
	// Start : Export to excel
	//--------------------------------------------------------------------------------
	$filename 	= 	strtolower("export/admin/admininfo".date('dnY').".xls");
	//echo $filename 	= 	strtolower($schoolname.".xls");
	
	error_reporting (E_ALL ^ E_NOTICE);
	
	require_once("include/excelwriter.class.php");
	$excel		= 	new ExcelWriter($filename);
	
	if($excel==false)	
	echo $excel->error;
	$myArr		=	array("");
	$myArr		=	array("Admin Information");
	$excel		->	writeLine($myArr);
	$myArr		=	array("");
	$excel		->	writeLine($myArr);
	$myArr		=	array("Sr. No.","Assigned Type","Created","Created By","Modified","Modified By");
	$excel		->	writeLine($myArr);
		
	$query_excel1 	 = "SELECT * FROM `tbl_admin_type`";
	
	$qry1		=	mysqli_query($db_con,$query_excel1) or die(mysqli_error($db_con));
	if($qry1 != false)
	{
		$i=1;
		while($res1=mysqli_fetch_array($qry1))
		{
			$myArr 	= 	array($i,$res1['at_name'],$res1['createddt'],$res1['createdby'],$res1['modifieddt'],$res1['modifiedby']);
			$excel	->	writeLine($myArr);
			$i++;			
		}
	}
	exit(0);
}
?>
<html>
<head>
<?php 
	/* This function used to call all header data like css files and links */
	headerdata($feature_name);
	/* This function used to call all header data like css files and links */
	
?>
<script type="text/javascript">
function CommonUpdate_limit()
{
	var srch = $('input[name="srch"]').val();
	var limit = $('select[name="rowlimit"]').val();
	loadData('1','1',limit,srch);
}
function changeStatus(userid,curr_status)
{
	loading_show();
	$.post(location.href,{userid:userid,curr_status:curr_status,jsubmit:'changeStatus'},function(data)
	{
		if (data == "Success") 
		{
			location.reload();
			loading_hide();
		} 
		else 
		{
			
		}
	});			
}
function loadData(valu,page,row_limit,search_txt)
{
	loading_show();
	$.post(location.href,{page:page,row_limit:row_limit,search_txt:search_txt,jsubmit:'loadusers'},function(data)
	{
			if (data.length > 0) 
			{
				$("#container1").html(data);
				loading_hide();
			} 
			else 
			{
				
			}
	});	
} 
$(document ).ready(function() {
		$('#srch').keypress(function(e) {
		if(e.which == 13) 
		{	
			var srch = $('input[name="srch"]').val();
			var limit = $('select[name="rowlimit"]').val();		
       	   	loadData('1','1',limit,srch);
		}
	});
	loadData(1,1,10,"");	
	$('#container1 .pagination li.active').live('click',function(){
		var page 				= $(this).attr('p');
		var row_limit 			= $(this).attr('row_limit');
		var search_txt 			= $(this).attr('search_txt');
		var valu = 1;
		loadData(valu,page,row_limit,search_txt);						
	});
});
function downld()
{
	$.post(location.href,{jsubmit:'exporttoexcel'},function(data){
	if (data.length > 0) 
		{
			alert(data);
		} 
		else 
		{
			window.location='export/admin/admininfo<?php echo date('dnY'); ?>.xls';
		}
	});
}
	//============================================
	//This is function for de-activating status
	//============================================
	function delete_assigntype()
	{	
		var batch = []
		$("input[name='batch[]']:checked").each(function ()
		{
			batch.push(parseInt($(this).val()));
		});
loading_show();
		$.post(location.href, {batch:batch,jsubmit:'delete_assign_type'}, function(data)
		{
			$('table#tblassigntype tbody').html(data);
			loading_hide();
			loadData(1,1,10,"");				
		});
	}

	</script>
    </head>
<body  class="theme-orange" data-theme="theme-orange" >
	<?php 
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
             <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-bordered box-color">
                                <div class="box-title">
                                    <h3 style="margin-right:50px"><i class="icon-th-list"></i><?php echo $feature_name; ?></h3>
                                </div>
                                <div class="box-content nopadding">
                                    <div style="padding:10px 15px 10px 15px !important">
                                        <select name="rowlimit" id="rowlimit" onChange="CommonUpdate_limit();"  class = "select2-me">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries per page
                                        <a href="javascript:void(0);" id="a_export" onClick="downld();" style="float:right;margin-right:10px;"><img src="images/Excelicon.png" height="20" width="20"></a>                                    
                                        <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Search here..."  onKeyPress="CommonUpdate_srch();" style="float:right;margin-right:10px;margin-top:10px;height:24px; " >
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
                            </div> <!-- main actual content-->
                        </div>
                    </div>
                 </div> <!--right side main content panel-->
            </div> <!--main content area-->
        </div>
	</div>  
       <?php getloder();?>
</body>
</html>
