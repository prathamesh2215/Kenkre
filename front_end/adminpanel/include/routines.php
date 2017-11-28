<?php
include("../includes/sess.php");
// Done By satish //
$logged_uid=0;
if (isset($_SESSION['panel_user']['email']) && strlen($_SESSION['panel_user']['email']) > 0) 
{
	$logged_uid = $_SESSION['panel_user']['id'];

}	
if (isset($_POST['jsubmit']) && isset($_POST['emailid']) && isset($_POST['password']) && $_POST['jsubmit'] == "SiteLogin") 
{
	$email 		= $_POST['emailid'];
	$password 	= $_POST['password'];
	
	$sql_login = "select * from tbl_cadmin_users where `email` = '".addslashes($email)."' ";
	$result_login = mysqli_query($db_con,$sql_login) or die(mysqli_error($db_con));
	$num_rows_login = mysqli_num_rows($result_login);
	if ($num_rows_login != 0) 
	{
		$row_login = mysqli_fetch_array($result_login);
		$db_password = $row_login['password'];
		$salt_value = $row_login['salt_value'];
		$user_pass = md5($password.$salt_value);
	
		
		if(strcmp($db_password,$user_pass) == 0)
		{
			$_SESSION['panel_user'] = array();		
			$_SESSION['panel_user'] = $row_login;	
		   // print_r($_SESSION['panel_user']);
			//echo 'Success';
		}
		else
		{
			echo 'Password you entered does not match.If problem persist contact admin to resolve your query.';			
		}
	} 
	elseif ($num_rows_login == 0) 
	{
		echo 'Email you entered does not exist.If problem persist contact admin to resolve your query.';
	}
	exit(0);
}
else
{
	$email = "";
	$password = "";	
}

/* function to get new id i.e last+1 while inserting records */
function getNewId($new_id,$table_name)
{
	global $db_con;
	$sql_get_last_reco	= " SELECT `".$new_id."` FROM `".$table_name."` ORDER BY `".$new_id."` DESC LIMIT 0,1 ";
	$res_get_last_reco 	= mysqli_query($db_con, $sql_get_last_reco) or die(mysqli_error($db_con));
	$num_get_last_reco	= mysqli_num_rows($res_get_last_reco);	
	if($num_get_last_reco == 0)
	{
		$new_id = '1';
	}
	else
	{
		$row_get_last_reco	= mysqli_fetch_array($res_get_last_reco);
		$new_id 			= $row_get_last_reco["".$new_id.""] + 1;
	}
	return $new_id;
}





function modelPopUp()
{
	?>
    <div class="modal fade" id="error_model" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body text-center" id="model_body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>
    <?php
}

// For Generating Random Salt Value
function generateRandomString($length) 
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) 
	{
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}


function randomString($query,$length)
{
	global $db_con;
	$random_string = generateRandomString($length);
	if($random_string != "")
	{
		$sql_check_string		= $query;
		$result_check_string 	= mysqli_query($db_con,$sql_check_string) or die(mysqli_error($db_con));
		$num_rows_check_string 	= mysqli_num_rows($result_check_string); 
		if($num_rows_check_string == 0)
		{
			return $random_string;
		}
		else
		{
			randomString($query,$length);
		}
	}
	else
	{
		randomString($query,$length);
	}
}



function checkFunctionalityRight($filename,$pos)
{
   	global $db_con;
	$sql_file_name 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
    
	$result_file_name 	= mysqli_query($db_con,$sql_file_name) or die(mysqli_error($db_con));
	$row_file_name		= mysqli_fetch_array($result_file_name);
	$af_id 		        = $row_file_name['af_id'];
		
	$sql_parent_menu 	= "select * from tbl_assign_rights where ar_user_owner_id = '".$_SESSION['panel_user']['id']."'";
	
	$res_parent_menu 	= mysqli_query($db_con,$sql_parent_menu);
	$row_parent_menu 	= mysqli_fetch_array($res_parent_menu);
 	$part_main			= explode("{",$row_parent_menu['ar_current_rights']);
	$ar_parent_menu  	= array();
	foreach($part_main as $part)
	{
		$part_sub_1			= explode(":",$part);
        if($part_sub_1[0] == $af_id)
		{
            $part	= explode(",",$part_sub_1[1]);
			if($part[$pos] == 1)
			{
				return 1;
				exit();	
			}
			else
			{
                return 0;
				exit();
			}
		}
	}	
}


function chkRights($filename)
{
	global $db_con;
	$sql_file_name 		= "select * from tbl_admin_features where af_page_url = '".$filename."'";
        
	$result_file_name 	= mysqli_query($db_con,$sql_file_name) or die(mysqli_error($db_con));
	$row_file_name		= mysqli_fetch_array($result_file_name);
       
	$af_id 		        = $row_file_name['af_id'];
       
	$sql_check_auth 	= "select * from tbl_assign_rights where ar_user_owner_id = '".$_SESSION['panel_user']['id']."' and ar_current_rights like '%{".$af_id.":%' ";
	//	echo $sql_check_auth;die;
        $result_chk_auth 	= mysqli_query($db_con,$sql_check_auth) or die(mysqli_error($db_con));
	$num_rows_check_auth= mysqli_num_rows($result_chk_auth);
	//echo $sql_check_auth;die;
	if($num_rows_check_auth == 1)
	{
		//$row_check_auth = mysqli_fetch_array($result_chk_auth);
		//$check_rights	= $row_check_auth['ar_current_rights'];
	}
	elseif($num_rows_check_auth != 1)
	{
		header("Location: view_dashboard.php?pag=Dashboard");
		exit(0);
	}
}


function redirectPage($page) 
{
	if ($page == "home") 
	{
		header("Location: /");
	}
	exit(0);
}


function time_stamp($ymd)
{
	$parts   = explode(' ',$ymd);
	$numdate = explode('-',$parts[0]);
	$time 	 = explode(':',$parts[1]);
	$month   = date("F", mktime(null, null, null, $numdate[1]));
	return $numdate[2]." ".substr($month,0,3)." ".$numdate[0].' ('.$time[0].':'.$time[1].':'.$time[2].')';
}
function year_month_date($ymd)
{
	$numdate = explode('-',$ymd);
	$month   = date("F", mktime(null, null, null, $numdate[1]));
	return $numdate[2]." ".substr($month,0,3)." ".$numdate[0];
}
function logoutUser()
{
	session_destroy(); 
	header("Location: ".$BaseFolder);
	exit(0);		
}


function checkuser()
{
	global $BaseFolder;
	global $db_con;
   // print_r($_SESSION['panel_user']);die;
	$sql_logout = "SELECT `status` from tbl_cadmin_users WHERE email = '".$_SESSION['panel_user']['email']."' and password = '".$_SESSION['panel_user']['password']."' AND `status`='1'";
	$result_logout = mysqli_query($db_con,$sql_logout) or die(mysqli_error($db_con));
    $num_rows_logout = mysqli_num_rows($result_logout);
        	
	if ((!isset($_SESSION['panel_user']['email']) || strlen($_SESSION['panel_user']['email']) < 1 || !isset($_SESSION['panel_user']['password']) || strlen($_SESSION['panel_user']['password']) < 1)) 
	{
		echo "1";
		header("Location: ".$BaseFolder."");
		exit(0);
	} 
	else if($num_rows_logout == 0)
	{
		echo "2";
		logoutUser();
	}
	  
}

function spamcheck($field)
{
	$field	=	filter_var($field, FILTER_SANITIZE_EMAIL);
	if(filter_var($field, FILTER_VALIDATE_EMAIL))
	{
		return TRUE;
	} 
	else 
	{
		return FALSE;
	}
}


function getRealIpAddr()
{
	$ip2 = '';
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip2 = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip2=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ip2=$_SERVER['REMOTE_ADDR'];
	}
	return $ip2;
}
function pagination($total_No_Record, $page, $limit, $targetpage)
{
	$adjacents = 2;
	$total_pages = $total_No_Record;
					
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;			
					
					
   /* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		//previous button
		if ($page > 1) 
		{
			$pagination.= "<a class=\"next_prev\"  href=\"".$targetpage."page=$prev\" style=\"float:left;margin-left:10px;\"><img src=\"images/left-icon.png\" /></a>&nbsp;&nbsp;"; //next_prev_new
		}
		else
		{
			//$pagination.= "<a class=\"next_prev current\">[previous]&nbsp;&nbsp;</a>";	 //next_prev_new
			
		}	
		
		//pages	
		
		//next button
		if ($page < $lastpage) 
		{
			$pagination.= "<a class=\"next_prev\"  href=\"".$targetpage."page=".$next."\" style=\"float:left;margin-left:10px;\"><img src=\"images/right-icon.png\" /></a>";  //next_prev_new
		}else{
			//$pagination.= "<a class=\"next_prev current\">[next]</a>";   //next_prev_new
			
		}
	}
	return $pagination;
}
function get_auto_next_id($table_name)
{
	global $dbname;
	global $db_con;
	$next_insert_id = 0;
	$table_schema = $dbname;
	$sql_last_insert = "select AUTO_INCREMENT from information_schema.Tables where TABLE_SCHEMA = '".$table_schema."' and TABLE_NAME='".$table_name."'";
	$result_last_insert = mysqli_query($db_con) or die(mysqli_error($db_con));	
	if (isset($result_last_insert['AUTO_INCREMENT'])) 
	{
		$next_insert_id = $result_last_insert['AUTO_INCREMENT'];
	}
	return $next_insert_id;
}
function sendSMS($mob,$data_msg)
{
	$data_msg = str_replace("<p>","",$data_msg);
	$data_msg = str_replace("</p>","",$data_msg);
	$data = '<?xml version="1.0" encoding="UTF-8"?>';
	$data .=<<<EOF
<xmlapi>
<auth>
<apikey>9422pzc28y9ud2ul3y5e</apikey>
</auth>
<sendSMS>
<to></to>
<text></text>
<msgid>0</msgid>
<sender>MYSTDY</sender>
</sendSMS>
<response>Y</response>
</xmlapi>
EOF;
	 if (preg_match("/^\d{10}$/",$mob)) 
	 {
		$data = str_replace("<to></to>","<to>".$mob."</to>",$data);
		$data = str_replace("<text></text>","<text>".$data_msg."</text>",$data);
		$url = "http://alerts.sinfini.com/api/xmlapi.php?data=".urlencode($data);
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output=curl_exec($ch);
		curl_close($ch);
		//return $output;
		return 1;
	}
	else
	{
		return 0;
	}
}
function load_session_timeout($uid)
{
	global $BaseFolder;
	if ($uid < 1) 
	{
		echo '<script>location.assign("'.$BaseFolder.'logout.php");</script>';
		exit(0);
	} 
}
function getloder()
{	
	?>
 		<div id="lodermodal"></div>
    	<div id="loderfade"></div>
	<?php
}
function headerdata($feature_name)
{
	?>
		<meta charset="utf8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<!-- Apple devices fullscreen -->
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<!-- Apple devices fullscreen -->
		<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />	
		<title><?php echo $feature_name; ?></title>
	    <!--<link rel="shortcut icon" href="img/logo.ico">-->
		<!-- Bootstrap -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Bootstrap responsive -->
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<!-- jQuery UI -->
		<link rel="stylesheet" href="css/plugins/jquery-ui/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="css/plugins/jquery-ui/smoothness/jquery.ui.theme.css">
		<!-- PageGuide -->
		<link rel="stylesheet" href="css/plugins/pageguide/pageguide.css">
	   	<!-- dataTables -->
		<link rel="stylesheet" href="css/plugins/datatable/TableTools.css">
		<!-- Fullcalendar -->
		<link rel="stylesheet" href="css/plugins/fullcalendar/fullcalendar.css">
		<link rel="stylesheet" href="css/plugins/fullcalendar/fullcalendar.print.css" media="print">
		<!-- chosen -->
		<link rel="stylesheet" href="css/plugins/chosen/chosen.css">
		<!-- select2 -->
		<link rel="stylesheet" href="css/plugins/select2/select2.css">
		<!-- icheck -->
		<link rel="stylesheet" href="css/plugins/icheck/all.css">	
		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/style.css">
		<!-- Color CSS -->
		<link rel="stylesheet" href="css/themes.css">		
	    <!-- this will include main css file -->
	    <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">
	    <!-- this will include main css file -->      
		<script src="js/jquery-1.8.3.js"></script>
	 	<!-- jQuery -->
		<!-- Nice Scroll -->
		<script src="js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
		<!-- jQuery UI -->
		<script src="js/plugins/jquery-ui/jquery.ui.core.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.widget.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.mouse.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.draggable.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.resizable.min.js"></script>
		<script src="js/plugins/jquery-ui/jquery.ui.sortable.min.js"></script>	
		<!-- Touch enable for jquery UI -->
		<script src="js/plugins/touch-punch/jquery.touch-punch.min.js"></script>
		<!-- slimScroll -->
		<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<!-- Bootstrap -->
		<script src="js/bootstrap.min.js"></script>
    	<!-- dataTables -->
		<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
		<script src="js/plugins/datatable/TableTools.min.js"></script>
		<script src="js/plugins/datatable/ColReorder.min.js"></script>
		<script src="js/plugins/datatable/ColVis.min.js"></script>
		<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
		<!-- vmap -->
		<script src="js/plugins/vmap/jquery.vmap.min.js"></script>
		<script src="js/plugins/vmap/jquery.vmap.world.js"></script>
		<script src="js/plugins/vmap/jquery.vmap.sampledata.js"></script>
		<!-- Bootbox -->
		<script src="js/plugins/bootbox/jquery.bootbox.js"></script>
		<!-- Flot -->
		<script src="js/plugins/flot/jquery.flot.min.js"></script>
		<script src="js/plugins/flot/jquery.flot.bar.order.min.js"></script>
		<script src="js/plugins/flot/jquery.flot.pie.min.js"></script>
		<script src="js/plugins/flot/jquery.flot.resize.min.js"></script>
		<!-- imagesLoaded -->
		<script src="js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>
		<!-- PageGuide -->
		<script src="js/plugins/pageguide/jquery.pageguide.js"></script>
		<!-- FullCalendar -->
		<script src="js/plugins/fullcalendar/fullcalendar.min.js"></script>
		<!-- Chosen -->
		<script src="js/plugins/chosen/chosen.jquery.min.js"></script>
		<!-- select2 -->
		<script src="js/plugins/select2/select2.min.js"></script>
		<!-- icheck -->
		<script src="js/plugins/icheck/jquery.icheck.min.js"></script>
		<!-- Validation -->
		<script src="js/plugins/validation/jquery.validate.min.js"></script>
		<script src="js/plugins/validation/additional-methods.min.js"></script>
		<!-- Theme framework -->
		<script src="js/eakroko.min.js"></script>
		<!-- Theme scripts -->
		<script src="js/application.min.js"></script>
		<!-- Just for demonstration -->
		<script src="js/demonstration.min.js"></script>
	    <script src="js/jquery-ui.js"></script>
		<!-- for date picker and validation-->
		 <!-- for date picker and validation-->
    	<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>	
		<script type="text/javascript" src="js/ckeditor/plugins/ckeditor_wiris/plugin.js"></script>
    	<link rel="stylesheet" href="css/jquery-ui.css" />
		<!-- Favicon -->
		<link rel="shortcut icon" href="img/favicon.ico" />
		<!-- Apple devices Homescreen icon -->
		<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png" />
		<!-- This is for multiple dropdown list with checkbox of page tagging -->
   		<link href="css/sumoselect.css" type="text/css" rel="stylesheet" />
    	<script src="js/jquery.sumoselect.js"></script>
		<!-- This is for multiple dropdown list with checkbox of page tagging -->
        <script type="text/javascript">
			$(document).ready(function () 
			{
				$("#selectall").click(function()
				{
					$(".case").attr("checked",this.checked);
				});		
				$(".case").click(function()
				{
					if($(".case").length==$(".case:checked").length)
					{
						$("#selectall").attr("checked","checked");
					}
					else
					{
						$("#selectall").removeAttr("checked");
					}
				});					
    			var charReg = /^\s*[a-zA-Z,\s]+\s*$/;
    			$('.keyup-char').keyup(function () 
				{
        			$('warning-char').hide();
        			var inputVal = $(this).val();
	        		if (!charReg.test(inputVal)) 
					{
            			$(this).parent().find(".warning-char").show();
						$('.keyup-char').val("");
        			} 
					else 
					{
            			$(this).parent().find(".warning-char").hide();
        			}
    			});
    			var numberReg = /^\s*[0-9,\s]+\s*$/;
    			$('.keyup-number').keyup(function () 
				{
        			$('warning-number').hide();
        			var inputVal = $(this).val();
	        		if (!numberReg.test(inputVal)) 
					{
            			$(this).parent().find(".warning-number").show();
						$('.keyup-number').val("");
        			} 
					else 
					{
            			$(this).parent().find(".warning-number").hide();
        			}
    			});				
			});
			$(function()
			{
  				$('ul.tabs li:first').addClass('active');
  				$('.block article').hide();
  				$('.block article:first').show();
  				$('ul.tabs li').on('click',function()
				{
	    			$('ul.tabs li').removeClass('active');
	    			$(this).addClass('active')
	    			$('.block article').hide();
	    			var activeTab = $(this).find('a').attr('href');
	    			$(activeTab).show();
	    			return false;
  				});
			})
		    $(window).load(function() 
			{
    	    	$(".loader").fadeOut("slow");
    		})
    		$(function() 
			{
        		$(".preload").fadeOut(2000, function() 
				{
            		$(".content").fadeIn(1000);        
        		});
    		});
			function loading_show()
			{
				document.getElementById('lodermodal').style.display = 'block';
				document.getElementById('loderfade').style.display = 'block';
			}
			function loading_hide()
			{
				document.getElementById('lodermodal').style.display = 'none';
				document.getElementById('loderfade').style.display = 'none';
			}
			function childCheckUncheck(parentId,childClass)
			{
				if($('#'+parentId).attr('checked')) 
				{
					//alert(parentId+":"+childClass);
					$("."+childClass).prop("checked",true);
				} 
				else 
				{
					//alert(parentId+":"+childClass);					
					$("."+childClass).prop("checked",false);
				}				
			}			
			function toggleMyDiv(chevron_id,div_id)
			{
				if($("#"+chevron_id).is('.icon-chevron-up'))
				{
				   $("#"+chevron_id).addClass('icon-chevron-down').removeClass('icon-chevron-up');
				   $("#"+div_id).slideUp();
				}		
				else if($("#"+chevron_id).is('.icon-chevron-down'))
				{				
					$("#"+chevron_id).addClass('icon-chevron-up').removeClass('icon-chevron-down');
				   $("#"+div_id).slideDown();				
				}
			}		
			
			function ValidateMobile(mobileid) //This is for validating mobile number
			{
			var mobilenum = $("#"+mobileid).val();
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
			function isNumberKey(evt) //This is for only numeric value
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				{
					return false;
				}
				return true;
			}	
		
			function closeMe(myId)
			{
				$("#"+myId).slideUp();
		    }	
			function openMe(myId)
			{
				$("#"+myId).slideDown();
		     }
			function divSwap(open_div,closeDiv)
			{
				$("#"+closeDiv).slideUp();
				$("#"+open_div).slideDown();
		    }				
			
			
			
			//===========Start : For Go to page Select Box 16052017 by satish====================//
			
			function get_option(page_no_prod,no_of_product_page)
			{
				var k=1;
				var l=100;
				if(page_no_prod < 100)
				{
					 k=1
					 l=parseInt(page_no_prod)+100;
				}
				else
				{
					k=page_no_prod-100;
					l=parseInt(page_no_prod)+100;
				}
				if(l>no_of_product_page)
				{
					l =parseInt(no_of_product_page);
				}
			
			
				$('#go_to_page').html('<option value="">Select Page</option');
				for(var i=k;i<=l;i++)
				{
					  var opt ='<option value="'+i+'" ';
					  if(i==parseInt(page_no_prod))
					  {
						   opt =opt + ' selected ';
					  }
					  opt =opt + ' >'+i+'</option>';
					  $('#go_to_page').append(opt);
				}
			}
			
			function go_to_page(val,input_id)
		    {
			
				$("#"+input_id).val(val);
				loadProducts();	
		}
			
			//===========End : For Go to page Select Box 16052017 by satish====================//
		</script>
		<style>
.wrapper {
  background: white;
  margin: auto;
  padding: 0.5em;
  width: 100%;
}
h1 {
  text-align: center;
}
ul.tabs {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
ul.tabs li {
  border: gray solid 1px;
  border-bottom: none;
  float: left;
  margin: 0 .25em 0 0;
  padding: .25em .5em;
}
ul.tabs li a {
  color: gray;
  font-weight: bold;
  text-decoration: none;
}
ul.tabs li.active {
  background: #1C93A0;
}
ul.tabs li.active a {
  color: white;
}
.clr {
  clear: both;
}
article {
  border-top: gray solid 1px;
  padding: 0 1em;
}
</style>    
    <?php
}

/* Generate Slug */
function getSlug($name)
{
	//	1.	(  = Remove and space before ( will be  -
	$name_1 = str_replace("(","-",$name);
	//	2.	)  = Remove and space after ) will be -
	$name_2 = str_replace(")","-",$name_1);
	//	3.	*  = Remove
	$name_3 = str_replace("*","",$name_2);	
	//	4.	"  = Remove and space before " will be -
	$name_4 = str_replace('"','-',$name_3);	
	//	5.	,  = Remove and space after , will be -
	$name_5 = str_replace(',','-',$name_4);		
	//	6.	.  = Remove
	$name_6 = str_replace(".","",$name_5);
	//	7.	$  = Don't Remove
	//	8.	#  = Don't Remove
	//	9.	@ = Removed (Done)
	//	10.	!  = Remove and space after ! will be -
	$name_7 = str_replace("!","-",$name_6);	
	//	11.	)( = Remove and keep -
	$name_8 = str_replace(")(","-",$name_7);
	// lower case all string
	$name_9	= strtolower($name_8);
	// replace all whitespace
	$name_10 = str_replace(" ","-",$name_9);	
	// replace & with and
	$name_11 = str_replace("&","and",$name_10);
	// replace + with -
	$name_12 = str_replace("+","-",$name_11);
	// remove :
	$name_13 = str_replace(":","",$name_12);
	// remove ;	
	$name_14 = str_replace(";","",$name_13);		
	// remove '
	$name_15 = str_replace("'","",$name_14);	
	// rtrim
	$name_16 = rtrim($name_15, '-');
	// ltrim	
	$name_17 = ltrim($name_16, '-');
	// replace / with -
	$name_18 = str_replace("/","-",$name_17);	
	// replace ? with -	
	$name_19 = str_replace("?","-",$name_18);		
	// replace | with -	
	$name_20 = str_replace("|","-",$name_19);			
	// replace | with -	
	$name_21 = str_replace("|","-",$name_20);
	// replace < with -	
	$name_22 = str_replace("<","-",$name_21);				
	// replace > with -		
	$name_23 = str_replace(">","-",$name_22);					
	// replace [ with -
	$name_24 = str_replace("[","-",$name_23);					
	// replace ] with -		
	$name_25 = str_replace("]","-",$name_24);					
	// replace { with -
	$name_26 = str_replace("{","-",$name_25);
	// replace } with -		
	$name_27 = str_replace("}","-",$name_26);					
	// replace % with -
	$name_28 = str_replace("%","-",$name_27);					
	// replace ` with -		
	$name_29 = str_replace("~","-",$name_28);					
	// replace -- with -	
	$name_30 = str_replace("--","-",$name_29);
	// replace --- with -	
	$name_31 = str_replace("---","-",$name_30);
	// replace whitespace
	$name_32 = preg_replace('/\s+/', '',$name_31);	
	$name_33 = str_replace(" ","-",$name_32);
	return $name_33;
}
/* Generate Slug*/

function navigation_menu()
{
	?>
    	<div id="navigation">
			<div class="container-fluid" >
				<a href="view_dashboard.php?pag=Dashboard" id="brand"><img src="images/logo.png" style="height:40px;"></a>
				<!-- main menu -->
            	<?php 
					$filepath = "include/admin_menu.php";
					include($filepath);
				?>
            	<!-- main menu -->
			</div>
		</div>	
	<?php
}


function breadcrumbs($home_url,$home_name,$title,$filename,$feature_name)
{
	global $db_con;
	$sql_add_feature = "select * from tbl_admin_features where af_parent_type = '".$title."' and af_name = 'Add ".$feature_name."' and af_status = 1";
	$result_add_feature = mysqli_query($db_con,$sql_add_feature) or die(mysqli_error($db));
	$num_rows_add_feature = mysqli_num_rows($result_add_feature);
	if($num_rows_add_feature != 0)
	{
	}
	?>
		<div class="page-header">
			<div class="pull-left">
				<h1><?php print $feature_name ?></h1>
			</div>
			<?php 
				date_default_timezone_set("Asia/Calcutta");
				$dt=date('F d, Y');
				$week=date('l');
			?>
			<div class="pull-right" style="margin-left:5px;">
				<ul class="stats">
					<li class='lightred'>
						<i class="icon-calendar"></i>
						<div class="details">
							<span class="big"><?php echo $dt; ?></span>
							<span><?php echo $week; ?></span>
						</div>
					</li>
				</ul>
			</div>
		</div> <!-- date BOX on right side-->
		<div class="breadcrumbs">
			<ul>
				<li>
					<a href="<?php echo $home_url; ?>"><?php echo $home_name; ?></a>
					<i class="icon-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $filename; ?>?pag=<?php echo $feature_name; ?>"><?php echo $feature_name; ?></a>
					<i class="icon-angle-right"></i>
				</li>
				<li>
					<a href="#"><?php echo $title; ?></a>
				</li>
			</ul>
			<?php /*?><div class="close-bread">
				<a href="#"><i class="icon-remove"></i></a>
			</div><?php */?>
		</div> <!--breadcrumb-->
	<?php
}

function dataPagination($query,$per_page,$start,$cur_page)
{
	global $db_con;
	
	$start_offset1  	= 1;	// Start Point
	$start_offset2  	= 1;	// End of the Limit
	$previous_btn 		= true;
	$next_btn 			= true;
	$first_btn 			= true;
	$last_btn 			= true;
	$msg 				= "";
	$result_pag_num 	= mysqli_query($db_con,$query) or die(mysqli_error($db_con));;
	$record_count		= mysqli_num_rows($result_pag_num);	// Total Count of the Record
	$no_of_paginations 	= ceil($record_count / $per_page);	// Getting the total number of pages
	
	/*Edit Count Query*/
	/* ---------------Calculating the starting and endign values for the loop----------------------------------- */
	
	if($cur_page >= 7) 
	{
		$start_loop = $cur_page - 3;
		if ($no_of_paginations > $cur_page + 3)
		{
			$end_loop = $cur_page + 3;			
		}
		elseif($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) 
		{
			$start_loop = $no_of_paginations - 6;
			$end_loop = $no_of_paginations;
		}
		else
		{
			$end_loop = $no_of_paginations;
		}
	} 
	else 
	{		
		$start_loop = 1;
		if ($no_of_paginations > 7)
		{
			$end_loop = 7;			
		}
		else
		{
			$end_loop = $no_of_paginations;			
		}
	}
	/* ----------------------------------------------------------------------------------------------------------- */
	$msg .= "<br><div class='pagination'><ul style='margin-right:20px'>";
	// FOR ENABLING THE FIRST BUTTON
	if ($first_btn && $cur_page > 1) 
	{
		$msg .= "<li p='1' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>First</li>";
	} 
	else if ($first_btn) 
	{
		$msg .= "<li p='1' class='inactive'  style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>First</li>";
	}
	// FOR ENABLING THE PREVIOUS BUTTON
	if ($previous_btn && $cur_page > 1) 
	{
		$pre = $cur_page - 1;
		$msg .= "<li p='$pre' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Previous</li>";
	} 
	else if ($previous_btn) 
	{
		$msg .= "<li class='inactive' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Previous</li>";
	}
	
	for ($i = $start_loop; $i <= $end_loop; $i++) 
	{
		if ($cur_page == $i)
			$msg .= "<li p='$i' value='$i' name='li_current' style='background: none repeat scroll 0 0 #F8A31F;color: #ffffff;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none' class='active selected'>{$i}</li>";
		else
			$msg .= "<li p='$i' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>{$i}</li>";
	}
	
	// TO ENABLE THE NEXT BUTTON
	if ($next_btn && $cur_page < $no_of_paginations) 
	{
		$nex = $cur_page + 1;
		$msg .= "<li p='$nex' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Next</li>";
	} 
	else if ($next_btn) 
	{
		$msg .= "<li class='inactive' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Next</li>";
	}
	// TO ENABLE THE END BUTTON
	if ($last_btn && $cur_page < $no_of_paginations) 
	{
		$msg .= "<li p='$no_of_paginations' class='active' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Last</li>";
	} 
	else if ($last_btn) 
	{
		$msg .= "<li p='$no_of_paginations' class='inactive' style='background: none repeat scroll 0 0 #eee;color: #333;font-family: Open Sans,sans-serif;font-size: 13px !important;font-weight:normal;border:none'>Last</li>";
	}
	$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;width:25px !important;'/>";
	$goto .= "<input type='button' id='go_btn' class='go_button' value='Go'/>";
	
	$start_offset1 = $cur_page * $per_page + 1 - $per_page;
	if($end_loop == $cur_page)
	{
		$start_offset2 = $record_count;
	}
	else
	{
		$start_offset2 = $cur_page * $per_page;
	}
	
	// $total_string [Is the actual string i.e. 1 to 20 of 4000 entries only, there is no any pagination include in it]
	$total_string = "</ul>";
	$total_string .= "<div class='total' a='$no_of_paginations' style='color:#333333;font-family: Open Sans,sans-serif;font-size: 13px !important;'>Showing <b>".$start_offset1."</b> to <b>$start_offset2</b> of <b>$record_count</b> entries</div>";
	
	// $msg	[Is the actual string that contain the pagination part first-prev-1-2-3-4-5-6-7-next-last only]
	
	$msg1 = $msg . $total_string ;  // Content for pagination
	if(!$record_count=='0')
	{
		return $msg1;
	}
	else
	{
		return 0;	
	}
}

function getSMSCredits()
{
	$url = "http://alerts.sinfini.com/api/v3/index.php?method=account.credits&api_key=9422pzc28y9ud2ul3y5e";
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output=curl_exec($ch);
	curl_close($ch);
	$output1 = json_decode($output,true);
	//print_r($output1);	
	//return($output1['data']['credits']);
}


function getExtension($str) 
{
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = strtolower(substr($str,$i+1,$l));
	return $ext;
}

function make_thumb($img_name,$filename,$new_w,$new_h)
{
//	$img_name="Desert.jpg";
	//get image extension.
	$ext=getExtension($img_name);
	
	
	//creates the new image using the appropriate function from gd library
	if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))
	{
		$t = "jpeg";
		$src_img = imagecreatefromjpeg($img_name);
		
	}
	
	if(!strcmp("png",$ext))
	{
		$t = "png";
		$src_img=imagecreatefrompng($img_name);
		
	}
		
	if(!strcmp("gif",$ext))
	{
		$t = "gif";
		$src_img=imagecreatefromgif($img_name);
	}
	
	
	
	//gets the dimmensions of the image
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);

	// next we will calculate the new dimmensions for the thumbnail image

	// the next steps will be taken:

	// 1. calculate the ratio by dividing the old dimmensions with the new ones

	// 2. if the ratio for the width is higher, the width will remain the one define in WIDTH variable

	// and the height will be calculated so the image ratio will not change

	// 3. otherwise we will use the height ratio for the image

	// as a result, only one of the dimmensions will be from the fixed ones

	$ratio1=$old_x/$new_w;
	$ratio2=$old_y/$new_h;
	if($ratio1>$ratio2) 
	{
		$thumb_w=$new_w;
		$thumb_h=$old_y/$ratio1;
	}
	else 
	{
		$thumb_h=$new_h;
		$thumb_w=$old_x/$ratio2;
	}

	// we create a new image with the new dimmensions
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	
	// resize the big image to the new created one
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	// output the created image to the file. Now we will have the thumbnail into the file named by $filename
	if(!strcmp("png",$ext))
		imagepng($dst_img,$filename);
	elseif(!strcmp("gif",$ext))
		imagegif($dst_img,$filename);
	else
		imagejpeg($dst_img,$filename);
	//destroys source and destination images.
	imagedestroy($dst_img);
	imagedestroy($src_img);
}


// ===============================================================================================================
// START : function for getting the sub categories for dropdown list [Dn By Prathamesh on 11 Sept 2017]
// ===============================================================================================================
function getSubCatValue($cat_id, $userType)	// Parameters : Parent ID and userType [i.e. Add, Edit, View]
{
	global $db_con;
	$data	= '';
	
	if($userType == 'add')
	{
		// Get The children of this Cat ID from Category Master Table
		$sql_get_children_frm_cm	= " SELECT * FROM `tbl_category` WHERE `cat_type`='".$cat_id."' AND `cat_name`!='none' AND `cat_status`='1' ";
		$res_get_children_frm_cm	= mysqli_query($db_con, $sql_get_children_frm_cm) or die(mysqli_error($db_con));
		$num_get_children_frm_cm	= mysqli_num_rows($res_get_children_frm_cm);
		
		if($num_get_children_frm_cm != 0)
		{
			while($row_get_children_frm_cm = mysqli_fetch_array($res_get_children_frm_cm))
			{
				// Get the count and the related parent ids of this category from the Category Path table
				$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` ";
				$sql_get_count_and_all_parent	.= " WHERE `cat_id`='".$row_get_children_frm_cm['cat_id']."' ";
				$sql_get_count_and_all_parent	.= " ORDER BY level ASC ";
				$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
				$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
				
				if($num_get_count_and_all_parent != 0)
				{
					$opt_value	= '';
					while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
					{
						// Find the name of the category
						$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` ";
						$sql_get_name_of_cat	.= " FROM `tbl_category` ";
						$sql_get_name_of_cat	.= " WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
						$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
						$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
						
						$parent_cat_name		= $row_get_name_of_cat['cat_name'];
						
						$opt_value	.= $parent_cat_name.' > ';
					}
					$data .= '<option id="cat'.$row_get_children_frm_cm['cat_id'].'sec'.$count.'" value="'.$row_get_children_frm_cm['cat_id'].'">'.substr(ucwords($opt_value),0,-3).'</option>';
					$data1	= getSubCatValue($row_get_children_frm_cm['cat_id'],'add');
					if($data1 != '')
					{
						$data	.= $data1;
					}
				}
			}
			return $data;	
		}
		else
		{
			return $data;	
		}
	}
	elseif($userType == 'view')
	{
		// Get the count and the related parent ids of this category from the Category Path table
		$sql_get_count_and_all_parent	= " SELECT * FROM `tbl_category_path_master` ";
		$sql_get_count_and_all_parent	.= " WHERE `cat_id`='".$cat_id."' ";
		$sql_get_count_and_all_parent	.= " ORDER BY level ASC ";
		$res_get_count_and_all_parent	= mysqli_query($db_con, $sql_get_count_and_all_parent) or die(mysqli_error($db_con));
		$num_get_count_and_all_parent	= mysqli_num_rows($res_get_count_and_all_parent);
		
		if($num_get_count_and_all_parent != 0)
		{
			$opt_value	= '';
			while($row_get_count_and_all_parent = mysqli_fetch_array($res_get_count_and_all_parent))
			{
				// Find the name of the category
				$sql_get_name_of_cat	= " SELECT `cat_id`, `cat_name` ";
				$sql_get_name_of_cat	.= " FROM `tbl_category` ";
				$sql_get_name_of_cat	.= " WHERE `cat_id`='".$row_get_count_and_all_parent['path_id']."' ";
				$res_get_name_of_cat	= mysqli_query($db_con,$sql_get_name_of_cat) or die(mysqli_error($db_con));
				$row_get_name_of_cat	= mysqli_fetch_array($res_get_name_of_cat);
				
				$parent_cat_name		= $row_get_name_of_cat['cat_name'];
				
				$opt_value	.= $parent_cat_name.' > ';
			}
			$data .= '<label class="control-label" >'.substr(ucwords($opt_value),0,-3).'</label>';
		}
		
		return $data;		
	}
	else
	{
		return $data;	
	}
}
// ===============================================================================================================
// END : function for getting the sub categories for dropdown list [Dn By Prathamesh on 11 Sept 2017]
// ===============================================================================================================
?>