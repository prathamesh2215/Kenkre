<?php
$url = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php print $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="-1" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="keyphrases" content="" />
	<link rel="shortcut icon" href="<?php print $BaseFolder; ?>../../images/favicon.png" type="image/png" />
	<link href="<?php print $BaseFolder; ?>../lib/css/common.css" rel="stylesheet" type="text/css" />
	<script src="<?php print $BaseFolder; ?>../lib/js/jquery-1.9.1.js" type="text/javascript" language="javascript"></script>
	<script type="text/javascript" src="<?php print $BaseFolder; ?>../lib/js/common.js"></script>
</head>
<body style="margin:0 10px"><div class="popup_box_layer"></div>
<div class="popup" style="border-radius:8px 8px 0 0"><?php include("popup.php"); ?></div>
<div class="mainwrapper">
	<div class="header" style="width:97%;" align="center">
		<div class="logo" style="float:left;margin:5px 0px 0px 10px" ><img src="../../images/logo.png" alt="MSC Logo" height="100" /></div>
		<?php if (isset($_SESSION['mystudyc_admin']['email'])) { ?>			
		<div id="login_part" style="margin-top:5px;margin-left:528px;">
		 <div style="float: left;background:#FFFFFF;border-radius:5px;padding:5px 10px;">Hi <?php print $_SESSION['mystudyc_admin']['fullname']; ?></div>
		</div>
        <?php  
		
			$ar_parent_menu  	= array();
			$ar_feature_id  	= array();
			$ar_menu_order  	= array();
			$ar_combine  		= array();
			
			$str_feature_id  = "";
			$str_menu_order  = "";
			$sql_parent_menu = mysql_query("select * from tbl_assign_rights where ar_user_owner_id = '".$_SESSION['mystudyc_admin']['id']."'");
			$res_parent_menu = mysql_fetch_array($sql_parent_menu);
			$ar_parent_menu  = explode(",",$res_parent_menu['ar_current_rights']);
			foreach($ar_parent_menu as $val)
			{
				$sql_parent_menu_name = mysql_query("select * from tbl_admin_features where af_id = '".$val."' and af_status = '1'");
				$res_parent_menu_name = mysql_fetch_array($sql_parent_menu_name);
				$str_menu_order .= $res_parent_menu_name['af_menu_order'].",";
				$str_feature_id .= $val.",";
			}
			$ar_feature_id = explode(',', rtrim($str_feature_id,','));
			$ar_menu_order = explode(',',rtrim($str_menu_order,','));
			$ar_combine = array_combine($ar_feature_id,$ar_menu_order);
			asort($ar_combine);
			?>
            <a href="../logout.php"><div style="float: left;background: none repeat scroll 0 0 #72bd27;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;margin-left:10px;" class="commondiv">Logout</div></a>
		<!--
        <div id="navigation" style="float:right;margin-top:5px;width:714px;"> 
			<a href="list-products.php"><div style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">Products</div></a>
			<a href="reports.php"><div style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">Student Report</div></a>
			<a href="productreports.php"><div style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">Products Report</div></a>
			<a href="create-user.php"><div style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">Create User</div></a>
			<a href="notifications.php"><div style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">Notifications</div></a>
			<a href="next-level-products.php"><div style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">Next Level</div></a>
			<a href="<?php print $BaseFolder; ?>"><div style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">My Account</div></a>
			<a href="changepassword.php"><div style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">Change Password</div></a>
            <a href="../logout.php"><div style="float: left;background: none repeat scroll 0 0 #72bd27;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;margin-left:10px;" class="commondiv">Logout</div></a>
		</div>
        -->
	<?php } ?>		
		</div>
		<div style="margin-top:5px;clear:both;float:left;width:100%;">
	<div style="clear:both;float:left;height:5px;background:#17839d;width:99%;">  
	</div>
            <table border="1" width="100%">
            <tr>
            <?php
			foreach($ar_combine as $x => $x_value) 
			{
				$sql_parent_menu_name1 = mysql_query("select * from tbl_admin_features where af_id = '".$x."' and af_status = '1'");
				$res_parent_menu_name1 = mysql_fetch_array($sql_parent_menu_name1);
				?><td style="float: left;background: none repeat scroll 0 0 #3ebbc8;border-radius:5px;color: #ffffff !important;font-size: 14px !important;padding: 5px 20px;" class="commondiv">
				<a href="<?php echo $res_parent_menu_name1['af_page_url']; ?>"><?php echo $res_parent_menu_name1['af_name']; ?></a>
				 </td>
				<?php
			}
			
		?>	</tr>
			</table>

<style>
.commondiv { margin:5px 20px 5px 10px; }
</style>	
</div>
