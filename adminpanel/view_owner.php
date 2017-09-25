<?php
include("include/routines.php");
checkuser();
chkRights(basename($_SERVER['PHP_SELF']));

// This is for dynamic title, bread crum, etc.
if(isset($_GET['pag']))
{
	$title = $_GET['pag'];
}

$path_parts   = pathinfo(__FILE__);
$filename 	  = $path_parts['filename'].".php";
$sql_feature = "select * from tbl_admin_features where af_page_url = '".$filename."'";
$result_feature = mysqli_query($db_con,$sql_feature) or die(mysqli_error($db_con));
$row_feature  = mysqli_fetch_row($result_feature);
$feature_name = $row_feature[1];
$home_name    = "Home";
$home_url 	  = "view_dashboard.php?pag=Dashboard";

$assigntype 	= array();
$start_offset   = 0;

if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == "loadproducts" )
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
	$page 	   	   -= 1;
	if(isset($_POST['row_limit']) && $_POST['row_limit']!="")
	{
		$per_page 		= mysqli_real_escape_string($db_con,$_POST['row_limit']);
	}
	else
	{
		$per_page 		= 10;
	}

	$start_offset += $page * $per_page;

	$start 			= $page * $per_page;
	$arr_topic = array();
	$query_value = "SELECT * FROM `tbl_users_owner` where 1=1 ";
	if(isset($_POST['search_txt']) && $_POST['search_txt']!="")
	{
		
		$search_txt 		= mysqli_real_escape_string($db_con,$_POST['search_txt']);
		$query_value 		.= " and clientname like '".$search_txt."' ";
	}
	$val = "1";
	$count = dataPagination($query_value,$per_page,$start,$cur_page);	
	$query_value .=  " LIMIT $start, $per_page ";
	$result =	mysqli_query($db_con,$query_value) or die(mysqli_error($db_con));
	while($row = mysqli_fetch_array($result))
	{
		$assigntype[] = $row;
	} 
	if(!$count=='0')
	{
		?>
		<div id="register_div" align="center">
        <form method="post" id="assign_type_anyway_form" onSubmit="return delete_assigntype();">
            <table id="tblassigntype"  class="table table-bordered dataTable dataTable-scroll-x" width="98%">
                <thead>
              	<tr>
                <th>Sr. No.</th>
                <th>Id.</th>
                <th>Client Owner</th>
                <th>Short Code</th>
                <th>Promo Code</th>
                <th>Edit</th>
                <th style="text-align:center">
                    <!--<input type="checkbox" id="selectall" />Select all-->
                    <input type="button" class="btn-danger" value="Delete"  onClick="delete_assigntype()"/>
                </th>
              </tr>
             </thead>
                <tbody>
              <?php  foreach($assigntype as $vals){  $start_offset++; ?>
                <tr>
                    <td><?php print $start_offset; ?></td>
                    <td><?php echo $vals['id']; ?></td>
                    <td><?php echo $vals['clientname']; ?></td>
                    <td ><?php echo $vals['shortcode']; ?></td>
                    <td ><?php echo $vals['promocode']; ?></td>
                    <td><div  style="margin-right:5px"><a href="edit_owner.php?owner_id=<?php echo $vals['id'] ?>" title="Edit"  class="icon-edit">Edit</a></div></td>
                    <td>
                        <div style="text-align:center">
                        <input type="checkbox" id="<?php echo $vals['id']?>" name="batch[]" value="<?php echo $vals['id']?>" class="css-checkbox"  >
				  		<label for="<?php echo $vals['id']?>" class="css-label" style="color:#FFF"></label>
                        </div>
                    </td>			
                </tr>
             <?php }?>
             </tbody>
            </table>
        </form>	
                                    </div>
		<?php echo $count;
	}
	else
	{
		echo "No Records Found!!!";
	}
	exit(0);
}
if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'delete_owner') 
{
	$ar_id	=	$_POST['batch'];

	foreach($ar_id as $id)
	{
		$sql = "delete from tbl_users_owner where id='$id'";
		$res = mysql_query($sql);
	}
	exit(0);
}
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'exporttoexcel')
{
	//--------------------------------------------------------------------------------
	// Start : Export to excel
	//--------------------------------------------------------------------------------
	$filename 	= 	strtolower("export/owner/ownerinfo".date('dnY').".xls");
	//echo $filename 	= 	strtolower($schoolname.".xls");
	
	error_reporting (E_ALL ^ E_NOTICE);
	
	require_once("include/excelwriter.class.php");
	$excel		= 	new ExcelWriter($filename);
	
	if($excel==false)	
	echo $excel->error;
	$myArr		=	array("");
	$myArr		=	array("Owner Information");
	$excel		->	writeLine($myArr);
	$myArr		=	array("");
	$excel		->	writeLine($myArr);
	$myArr		=	array("Sr. No.","Client Owner","Short Code","Promo Code");
	$excel		->	writeLine($myArr);
	
	$query_excel1 	 = "SELECT * FROM `tbl_users_owner`";
	
	$qry1		=	mysqli_query($db_con,$query_excel1) or die(mysqli_error($db_con));
	if($qry1 != false)
	{
		$i=1;
		while($res1=mysqli_fetch_array($qry1))
		{
			$myArr 	= 	array($i,$res1['clientname'],$res1['shortcode'],$res1['promocode']);
			$excel	->	writeLine($myArr);
			$i++;
			
		}
	}
	exit(0);
}
if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'delete_excel')
{
	$path =  $_POST['path'];
	//echo $filename = $_POST['filename'];
	try
	{
		$files = glob($path.'*'); // get all file names
		foreach($files as $file)
		{ // iterate files
		  if(is_file($file))
			unlink($file); // delete file
		}
	}
	catch (Exception $e) 
	{
		throw $e;
	}
	exit(0);
}
?>
<!doctype html>
<html>
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
function loadData(valu,page,row_limit,search_txt)
{
	loading_show();
	$.post(location.href,{page:page,row_limit:row_limit,search_txt:search_txt,jsubmit:'loadproducts'},function(data)
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
$( document ).ready(function() {
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
		var d 	  = new Date();
		var day   = d.getDate();
		var month = d.getMonth()+1;
		var year  = d.getFullYear();
		$.post(location.href,{year:year,jsubmit:'exporttoexcel'},function(data){
			if (data.length > 0) 
			{
				alert(data);
			} 
			else 
			{
				window.location='export/owner/ownerinfo<?php echo date('dnY'); ?>.xls';
				
				var filename = 'ownerinfo<?php echo date('dnY'); ?>.xls';
				var path = 'export/owner/';
				
				$.post(location.href,{filename:filename,path:path,jsubmit:'delete_excel'},function(data){
					if(data.length > 0)
					{
						alert(data);
					}
					});
			}
		});
    }
    </script>
    <script language="javascript" type="text/javascript">
$('#selectall').click(function(event) {
  if(this.checked) {
      // Iterate each checkbox
      $(':checkbox').each(function() {
          this.checked = true;
      });
  }
  else {
    $(':checkbox').each(function() {
          this.checked = false;
      });
  }
});
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
		$.post(location.href, {batch:batch,jsubmit:'delete_owner'}, function(data)
		{
			$('table#tblassigntype tbody').html(data);
			loadData(1,1,10,"");	
			loading_hide();
		});
	}
	$(document).ready(function(e) {
        
		$("#selectall").click(function(){
			$(".case").attr("checked",this.checked);
		});
		
		$(".case").click(function(){
			if($(".case").length==$(".case:checked").length){
				$("#selectall").attr("checked","checked");
			}
			else{
				$("#selectall").removeAttr("checked");
			}
		});
		
    });
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
                                        <input type="text" class="input-medium" id = "srch" name="srch" placeholder="Search here..."  style="float:right;margin-right:10px;margin-top:10px;" >
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