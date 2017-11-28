<?php
include("include/db_con.php");
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

$ar_client_user = array();
$start_offset   = 0;
//-----------------------------------------------------
// Fetching all admin types except student (Listbox)
//-----------------------------------------------------
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
	if(isset($_POST['user_type']) && $_POST['user_type']!="")
	{
		$user_type 		= mysqli_real_escape_string($db_con,$_POST['user_type']);
	}
	if(isset($_POST['city_selction']) && $_POST['city_selction']!="")
	{
		$city_selction 		= mysqli_real_escape_string($db_con,$_POST['city_selction']);
	}
	if(isset($_POST['owner_selction']) && $_POST['owner_selction']!="")
	{
		$owner_selction 		= mysqli_real_escape_string($db_con,$_POST['owner_selction']);
	}	
	$start_offset += $page * $per_page;
	$start 			= $page * $per_page;
	$mainquery =  "select id,fullname, userid, email, password, mobile_num, (select clientname from tbl_users_owner where id = tbl_users_owner ) ";
	$mainquery .= "  as user_owner, ( SELECT city_name  FROM `tbl_city` where city_id = city ) as city_name, ";
	$mainquery .= " (select at_name from tbl_admin_type where  at_id = utype) as user_type from tbl_cadmin_users where `utype` != '5' ";
	
	if((strcmp($user_type,"All") !== 0))
	{
		$mainquery .="  and utype = $user_type ";
	}
	if((strcmp($city_selction,"All") !== 0))
	{
		$mainquery .="  and city = '".$city_selction."' ";	
	}
	if((strcmp($owner_selction,"All") !== 0))
	{
		$mainquery .="  and tbl_users_owner = '".$owner_selction."' ";	
	}
	if(isset($_POST['search_txt']) && $_POST['search_txt'] != "")
	{		
		$search_txt 		= trim(mysqli_real_escape_string($db_con,$_POST['search_txt']));
		$mainquery .= " and (fullname like '%".$search_txt."%' or userid like '%".$search_txt."%' or email like '%".$search_txt."%' or password  like '%".$search_txt."%' or  mobile_num like '%".$search_txt."%' ) ";
	}
	$val = "";
	$count 			= 	dataPagination($mainquery,$per_page,$start,$cur_page);
	$mainquery .=" ORDER BY `tbl_cadmin_users`.`tbl_users_owner` ASC LIMIT $start, $per_page ";
	$result = mysqli_query($db_con,$mainquery) or die(mysql_error($db_con));
	if(strcmp($count,"0") !== 0)
	{
		?>
		<table id="tbl_user" class="table table-bordered dataTable">
    	 <thead>
    	  <tr>
         <th>Sr. No.</th>
              <th style="text-align:center">User Type</th>
                  <th style="text-align:center">Full Name</th>
                  <th style="text-align:center">Userid</th>
                  <th style="text-align:center">Email</th>
                  <th style="text-align:center">Mobile</th>
                  <th style="text-align:center">City</th>
                  <th style="text-align:center">User Owner</th>
                  <th style="text-align:center">Edit</th>
                  <th style="text-align:center">
                  <!--<input type="checkbox" id="selectall" />Select all<br>-->
                  <input type="submit"  value="Delete" class="btn-danger" onClick="delete_user();"/>
              </th>
          </tr>
      </thead>
      <tbody>
    <?php  while($vals = mysqli_fetch_array($result))
			{  
				$start_offset++; ?>
      			<tr>
          			<td  style="text-align:center"><?php print $start_offset; ?>			</td>
                    <td  style="text-align:center"><?php echo ucwords($vals['user_type']); ?>			</td>
                    <td  style="text-align:center"><a href="edit_user.php?id=<?php echo $vals['id'] ?>" title="Edit">
					<?php echo ucwords
					($vals['fullname']); ?></a></td>
                    <td  style="text-align:center"><?php echo $vals['userid']; ?>			</td>
                    <td style="text-align:center"><?php echo $vals['email']; ?>			</td>
                    <td style="text-align:center"><?php	if(strcmp($vals['mobile_num'],"") !== 0) {echo $vals['mobile_num'];}else{ echo '<span style="color:#E63A3A">Not Available.<span>'; } ?>		</td>
                    <td style="text-align:center"><?php	if(strcmp($vals['city_name'],"") !== 0) {echo $vals['city_name'];}else{ echo '<span style="color:#E63A3A">Not Available.<span>'; } ?>		</td>
                    <td style="text-align:center"><?php echo $vals['user_owner']; ?></td>
                    <!--
                    <td><?php //echo $vals['created']; ?></td>
                    <td style="text-align:center"><?php //echo $vals['modified']; ?></td>
                    -->
                    <td style="text-align:center"><div align="center" style="margin-right:5px"><a href="edit_user.php?id=<?php echo $vals['id'] ?>" title="Edit"  class="icon-edit">Edit</a></div></td>
                    <td style="text-align:center">                                                        
		           	 	<div class="controls" align="center">
    		        	    <input type="checkbox" value = "<?php print $vals['id']; ?>" id="batch<?php print $vals['id']; ?>" name="batch<?php print $vals['id']; ?>"  class="css-checkbox batch">
        		    	    <label for="batch<?php print $vals['id']; ?>" class="css-label"></label>
        		    	 </div>
          			</td>			
      			</tr>
   <?php }?>
   </tbody>
  </table>  <!-- Main content goes here -->
	  	<?php echo $count;
    }
	else
	{
		echo "No Records Found!!!"	;
	}  
	exit(0);
}
//-------------------------------------------------------------------------------------------------------------------
// Start : This is for deleting existing user
//-------------------------------------------------------------------------------------------------------------------
if (isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'deleteuser' && isset($_POST['batch']) && $_POST['batch'] != "") 
{
	$id	=	$_POST['batch'];	
	foreach($id as $id)
	{
		$sql 	= 	"delete from tbl_cadmin_users where id='$id'";
		$res	=	mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		$sql1 	= 	"delete from tbl_assign_rights where ar_user_owner_id='$id'";
		$res1	=	mysqli_query($db_con,$sql1) or die(mysqli_error($db_con));
	}
 	exit(0);
}
//-------------------------------------------------------------------------------------------------------------------
// End : This is for deleting existing user
//-------------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------------------------
// Start : For fetching created user data
//------------------------------------------------------------------------------------------------------------------

if(isset($_POST['jsubmit']) && $_POST['jsubmit'] == 'exporttoexcel')
{
	//--------------------------------------------------------------------------------
	// Start : Export to excel
	//--------------------------------------------------------------------------------
	$filename 	= 	strtolower("export/user/userinfo".date('dnY').".xls");
	//echo $filename 	= 	strtolower($schoolname.".xls");

	error_reporting (E_ALL ^ E_NOTICE);
	
	require_once("include/excelwriter.class.php");
	$excel		= 	new ExcelWriter($filename);
	
	if($excel==false)	
	echo $excel->error;
	$myArr		=	array("");
	$myArr		=	array("User Information");
	$excel		->	writeLine($myArr);
	$myArr		=	array("");
	$excel		->	writeLine($myArr);
	$myArr		=	array("Sr. No.","User Type","Full Name","UserID","Email","Password","Mobile","User Owner");
	$excel		->	writeLine($myArr);

	$query_excel1 	 = "SELECT tat.at_name, tcu.id,tcu.utype,tcu.fullname,tcu.userid,tcu.email,tcu.password,";
	$query_excel1 	.= " tcu.mobile_num,tcu.created,tcu.modified,tuo.clientname";
	$query_excel1 	.= " FROM `tbl_cadmin_users` as tcu join tbl_users_owner as tuo on tuo.id=tcu.tbl_users_owner";
	$query_excel1 	.= " join tbl_admin_type as tat on tat.at_id = tcu.utype where tcu.utype != '5'";

	$qry1		=	mysqli_query($db_con,$query_excel1) or die(mysqli_error($db_con));
	if($qry1 != false)
	{
		$i=1;
		while($res1=mysqli_fetch_array($qry1))
		{
			$myArr 	= 	array($i,$res1['at_name'],$res1['fullname'],$res1['userid'],$res1['email'],$res1['password'],$res1['mobile_num'],$res1['clientname'],'');
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

<head>	
<?php 
	/* This function used to call all header data like css files and links */
	headerdata($feature_name);
	/* This function used to call all header data like css files and links */	
?>
<script type="text/javascript">
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
			window.location='export/user/userinfo<?php echo date('dnY'); ?>.xls';
			var filename = 'userinfo<?php echo date('dnY'); ?>.xls';
			var path = 'export/user/';
			$.post(location.href,{filename:filename,path:path,jsubmit:'delete_excel'},function(data){
				if(data.length > 0)
				{
					alert(data);
				}
			});
		}
	});
}
function loadData()
{
	var search_txt = $('input[name="srch"]').val();
	var row_limit = $('select[name="rowlimit"]').val();
	var user_type = $('select[name="user_type"]').val();
	var city_selction = $('select[name="city_selction"]').val();
	var owner_selction = $('select[name="owner1"]').val();	
	var page 		= $("#hid_page").val();
	if((typeof page == "undefined") || (page == ""))
	{
		page = 1;	
	}	
	//loading_show();
	$.post(location.href,{user_type:user_type,page:page,row_limit:row_limit,search_txt:search_txt,city_selction:city_selction,owner_selction:owner_selction,jsubmit:'loadusers'},function(data)
	{
		$("#container1").html(data);
		//loading_hide();
	});	
} 
$( document ).ready(function() {
		$('#srch').keypress(function(e) {
		if(e.which == 13) 
		{	
       	   	loadData();
		}
	});
	loadData();	
	$('#container1 .pagination li.active').live('click',function(){
		var page = $(this).attr('p');
		$("#hid_page").val(page);
		loadData();						
	});

	});            
function delete_user()
{	
	var batch = []
	$(".batch:checked").each(function ()
	{
		batch.push(parseInt($(this).val()));
	});
	$.post(location.href, {batch:batch,jsubmit:'deleteuser'}, function(data)
	{
		$('table#tbl_user tbody').html(data);
		loadData();
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
        <div id="main" style="margin-left:0px !important">
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
                                <select name="user_type" style="margin-left:20px" class="select2-me input-large" data-rule-required="true" onChange="loadData()" id="user_type">
                                <?php 
                                    function loadtype($sql_get_user_type)
                                    {
                                        global $db_con;
                                        global $utype;
                                        $result_get_user_type = mysqli_query($db_con,$sql_get_user_type) or die(mysqli_error($db_con));
                                        while($row_get_user_type = mysqli_fetch_array($result_get_user_type))
                                        {
                                            $sql_alltype = "select at_id,at_name from tbl_admin_type where at_id = '".$row_get_user_type['utype']."' ";
                                            $result_allatype = mysqli_query($db_con,$sql_alltype) or die(mysqli_error());
                                             while($row_alltype =mysqli_fetch_array($result_allatype))
                                            { 
                                                ?>
                                                <option value="<?php print $row_alltype['at_id']; ?>" <?php if((strcmp($utype,$row_alltype['at_id']) == 0)){ echo "selected"; }?> ><?php print $row_alltype['at_name']; ?></option>
                                                <?php
                                            }	
                                        }																
                                    }	
                                    $sql_get_user_type = "SELECT distinct utype FROM `tbl_cadmin_users` ";
                                    if((strcmp($utype,"1") !== 0))
                                    {
                                        $sql_get_user_type .= " where`tbl_users_owner` = '".$tbl_users_owner."' order by utype  ";
                                        loadtype($sql_get_user_type);
                                    }
                                    else if((strcmp($utype,"1") == 0))
                                    {
                                        echo '<option value="All">All User Type</option>';											
                                        $sql_get_user_type .= " order by utype ";
                                        loadtype($sql_get_user_type);
                                    }										
                                ?>
                                </select>
                                <select name="owner1" id="owner1" data-rule-required="true" class="select2-me input-medium" onChange="loadData();">
                                <?php
                                    function loadOwner($sql_get_owner)
                                    {
                                        global $db_con;
                                        $result_get_owner = mysqli_query($db_con,$sql_get_owner) or die(mysqli_error());
                                        while($row_get_owner = mysqli_fetch_array($result_get_owner))
                                        {
                                            $sql_select_all_owner 		= "SELECT id,clientname FROM tbl_users_owner where id = '".$row_get_owner['tbl_users_owner']."' ";
                                            $result_select_all_owner 	= mysqli_query($db_con,$sql_select_all_owner) or die(mysqli_error($db_con));
                                            while($row_select_all_owner = mysqli_fetch_array($result_select_all_owner))
                                            {
                                                
                                                ?>                                                    
                                                    <option id= "<?php print $row_select_all_owner['id']; ?>" value = "<?php print $row_select_all_owner['id']; ?>"><?php print $row_select_all_owner['clientname']; ?></option>			
                                                <?php
                                            }
                                        }																
                                    }
                                    $sql_get_owner = "SELECT distinct tbl_users_owner FROM `tbl_cadmin_users` where tbl_users_owner != 0 ";
                                    if((strcmp($utype,"1") !== 0))
                                    {
                                        $sql_get_owner .= " and tbl_users_owner = '".$tbl_users_owner."' order by tbl_users_owner asc";
                                        loadOwner($sql_get_owner);
                                    }
                                    else if((strcmp($utype,"1") == 0))
                                    {
                                        echo '<option value="All">All Client Owner</option>';											
                                        $sql_get_owner .= " order by tbl_users_owner asc";
                                        loadOwner($sql_get_owner);
                                    }
                                ?>
                                </select>                                  
                                <select name="city_selction" id="city_selction" class="select2-me input-medium" data-rule-required="true" onChange="loadData();">                                                                           
                                    <option value="All">All City</option>
                                    <?php
                                    function loadCity($sql_get_city)
                                    {
                                        global $db_con;
                                        $result_city_id_data = mysqli_query($db_con,$sql_get_city) or die(mysqli_error($db_con));
                                        while($row_city_id_data = mysqli_fetch_array($result_city_id_data))
                                        {
                                            $sql_city_list = "SELECT city_id,city_name FROM `tbl_city` where city_id = '".$row_city_id_data['city']."' ";
                                            $result_city_list = mysqli_query($db_con,$sql_city_list) or die(mysqli_error($db_con));
                                            while($row_city_list = mysqli_fetch_array($result_city_list))
                                            {
                                                echo '<option id = '.$row_city_list['city_id'].' value = '.$row_city_list['city_id'].'>'.$row_city_list['city_name'].'</option>';	
                                            }
                                        }																	
                                    }
                                    $sql_get_city = "SELECT distinct city FROM `tbl_cadmin_users` WHERE city != '' and city !='0' ";
                                    if((strcmp($utype,"1") !== 0))
                                    {
                                        $sql_get_city .= " and tbl_users_owner = '".$tbl_users_owner."' order by city ASC";
                                        loadCity($sql_get_city);
                                    }
                                    else if((strcmp($utype,"1") == 0))
                                    {										
                                        $sql_get_city .= " order by city ASC";
                                        loadCity($sql_get_city);
                                    }
                                    ?>                                            
                            </select>
                            </div>
                            <div class="box-content nopadding">                         
                                <div style="padding:10px 15px 10px 15px !important">
                                    <input type="hidden" name = "hid_page" id = "hid_page">
                                    <select name="rowlimit" id="rowlimit" onChange="loadData();"  class = "select2-me">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> entries per page
									<?php
									$add 	= checkFunctionalityRight($filename,0);
									$add	= 1;
									if($add)
									{
										?>
										<a  href="add_user.php?pag=User" class="btn-info" style="text-decoration:none;color:white;margin-top:20px;"> <i class="icon-plus"></i>&nbsp Add User</a>
										<?php		
									}
                                    ?>                                               
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
        	</div>   
        </div> <!--right side main content panel-->
  	</div> <!--main content area-->
</body>
</html>

