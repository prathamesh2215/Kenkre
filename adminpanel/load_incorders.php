<?php
include("include/routines.php");
$json 		= file_get_contents('php://input');
$obj 		= json_decode($json);
$uid		= $_SESSION['panel_user']['id'];
$utype		= $_SESSION['panel_user']['utype'];
/* Mail On Order */
function sendOrderEmailVendor($ord_id,$type)
{
	global $db_con;
	$BaseFolder 		= "http://www.planeteducate.com/";
	global $date;
	global $min_order_value;
	global $shipping_charge;
	/*Select last Order and Details*/	
	$sql_get_vendor_info		= " SELECT distinct org_id,org_name,org_primary_email,org_secondary_email,org_primary_phone,org_secondary_phone,ord_created FROM `tbl_oraganisation_master` tom,`tbl_products_master` tpm,`tbl_cart` tc,`tbl_order` tord ";
	$sql_get_vendor_info		.= " where tom.org_id = tpm.prod_orgid and tpm.prod_id = tc.cart_prodid and tc.cart_orderid = tord.ord_id  ";
	if($type == 1)
	{
		$sql_get_vendor_info		.= " and cart_id = '".$ord_id."' ";
	}
	else if($type == 0)
	{
		$sql_get_vendor_info		.= " and tord.ord_id = '".$ord_id."' ";
	}
	$result_get_vendor_info		= mysqli_query($db_con,$sql_get_vendor_info) or die(mysqli_error($db_con));
	$num_rows_get_vendor_info 	= mysqli_num_rows($result_get_vendor_info);
	if($num_rows_get_vendor_info != 0)
	{			
		while($row_get_vendor_info	= mysqli_fetch_array($result_get_vendor_info))
		{
			$vendor_id				= $row_get_vendor_info['org_id'];
			$vendor_name			= $row_get_vendor_info['org_name'];
			$vendor_primary_email 	= $row_get_vendor_info['org_primary_email'];
			$vendor_secondary_email = $row_get_vendor_info['org_secondary_email'];
			$order_created			= $row_get_vendor_info['order_created'];
			$ord_id_show			= $row_get_vendor_info['ord_id_show'];
						
			$sql_order_details 		= " SELECT * FROM `tbl_order` WHERE `ord_id` =  '".$ord_id."' ";
			$result_order_details 	= mysqli_query($db_con,$sql_order_details) or die(mysqli_error($db_con));
			$row_order_details		= mysqli_fetch_array($result_order_details);
			$order_cust_id			= $row_order_details['ord_custid'];
			$order_add_id			= $row_order_details['ord_addid'];
			$ord_pay_type			= $row_order_details['ord_pay_type'];
				
			$sql_get_cart_details 	= " SELECT * FROM `tbl_products_master` tpm,`tbl_cart` tc,`tbl_order` tord where ";
			$sql_get_cart_details 	.= " tpm.prod_id = tc.cart_prodid and tc.cart_orderid = tord.ord_id and ";
			$sql_get_cart_details 	.= " tord.ord_id  = '".$ord_id."' and tpm.prod_orgid = '".$vendor_id."' "; 
			$result_get_cart_details = mysqli_query($db_con,$sql_get_cart_details) or die(mysqli_error($db_con));
			$order_data				.='<style>th, td { border-bottom: 1px solid #ddd;}</style>';
			$order_data				= '<table style="width:100%;">';
			$order_data				.= '<thead>';
			$order_data				.= '<th align="center" valign="top" style="width="30%">Product</th>';
			$order_data				.= '<th align="center" valign="top" style="width="30%">Name</th>';	
			$order_data				.= '<th align="center" valign="top" style="width="15%">Price</th>';
			$order_data				.= '<th align="center" valign="top" style="width="10%">Quantity</th>';
			$order_data				.= '<th align="center" valign="top" style="width="15%">Total</th>';	
			$order_data				.= '</thead>';
			$order_data				.= '<tbody>';
			$cart_total				= 0;			
			while($row_get_cart_details = mysqli_fetch_array($result_get_cart_details))
			{
				$sql_get_product_details 	= " SELECT `prod_id`, `prod_model_number`, `prod_name`, `prod_orgid`, `prod_brandid`, `prod_catid`,`prod_subcatid`,`prod_status`, ";
				$sql_get_product_details 	.= " (SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_prodid` = tpm.prod_id and prod_img_type = 'main') as prod_img_file_name  FROM `tbl_products_master` tpm WHERE prod_id = '".$row_get_cart_details['cart_prodid']."' ";
				$result_get_product_details = mysqli_query($db_con,$sql_get_product_details) or die(mysqli_error($db_con));
				$row_get_product_details 	= mysqli_fetch_array($result_get_product_details);
				$prod_imagepath				= $BaseFolder."images/planet/org".$row_get_product_details['prod_orgid']."/cat".$row_get_product_details['prod_catid']."/subcat".$row_get_product_details['prod_subcatid']."/prod".$row_get_product_details['prod_id']."/small/".$row_get_product_details['prod_img_file_name'];
				$product_name				= ucwords($row_get_product_details['prod_name']);		
				$order_data				.= '<tr>';
				$order_data				.= '<td align="center" valign="top" width="30%">';
				$order_data				.= '<img src="'.$prod_imagepath.'" alt="'.$product_name.'">';
				$order_data				.= '</td>';		
				$order_data				.= '<td align="center" valign="top" width="30%">';		
				$order_data 			.= '<span>'.$product_name.'</span>';
				$order_data				.= '</td>';
				$order_data				.= '<td align="center" valign="top" width="15%">';
				$order_data				.= $row_get_cart_details['cart_price'];
				$order_data				.= '</td>';		
				$order_data				.= '<td align="center" valign="top" width="10%">';
				$order_data				.= $row_get_cart_details['cart_prodquantity'];
				$order_data				.= '</td>';		
				$order_data				.= '<td align="center" valign="top" width="15%">';
				$order_data				.= $row_get_cart_details['cart_price'];
				$order_data				.= '</td>';				
				$order_data				.= '</tr>';
				$cart_total 			+= (int)$row_get_cart_details['cart_price'];
			}
			$order_data					.= '<tr><td>&nbsp;</td>';	
			$order_data					.= '<td>&nbsp;</td>';	
			$order_data					.= '<td colspan="3">';			
			$order_data					.= '<table>';
			$order_data					.= '<tbody>';
			$order_data					.= '<tr><td><b>Item Subtotal:</b></td>';
			$order_data					.= '<td>:'.$cart_total.'</td></tr>';
			$order_data					.= '<tr><td><b>Shipping & Handling:</b></td>';
			if($cart_total > $min_order_value)
			{
				$order_data				.= '<td><span style="color:f00;">Free Shipping</span></td></tr>';		
				$shipping_charge 		= 0;		
			}
			else
			{
				$order_data				.= $shipping_charge;
				$shipping_charge 		= $shipping_charge;
			}	
			$order_total				= $cart_total+$shipping_charge;
			$order_data					.= '<tr><td><b>Order Total</b></td>';
			$order_data					.= '<td>:'.$order_total.'</td>';
			$order_data					.= '</tr>';	
			$order_data					.= '<tr><td><b>Order Payment Status</b></td>';
			if($ord_pay_type == 'Pay Online')
			{
				$order_data					.= '<td>:Prepaid</td>';								
			}
			else
			{
				$order_data					.= '<td>:'.$ord_pay_type.'</td>';
			}
			$order_data					.= '</tr>';	
			$order_data					.= '</tbody>';
			$order_data					.= '</table>';	
			$order_data					.= '<td></tr>';
			$order_data					.= '</tbody>';
			$order_data					.= '</table>';	
			$sql_get_cust_details  		= " SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_email`, `cust_mobile_num`,`add_details`,`add_pincode`,";
			$sql_get_cust_details  		.= " (SELECT `state_name` FROM `state` WHERE `state`=tam.`add_state`) as state_name,";
			$sql_get_cust_details  		.= " (SELECT `city_name` FROM `city` WHERE `city_id`=tam.`add_city`) as city_name";
			$sql_get_cust_details  		.= " FROM `tbl_customer` tc,`tbl_address_master` tam WHERE tc.`cust_id` = tam.add_user_id and ";
			$sql_get_cust_details  		.= " tam.add_user_type = 'customer' and tc.`cust_id` = '".$order_cust_id."' and tam.add_id = '".$order_add_id."' ";
			
			$result_get_cust_details	= mysqli_query($db_con,$sql_get_cust_details) or die(mysqli_error($db_con));
			$row_get_cust_details		= mysqli_fetch_array($result_get_cust_details);
			$cust_email					= $row_get_cust_details['cust_email'];
			$cust_fname					= $row_get_cust_details['cust_fname'];
			$cust_lname					= $row_get_cust_details['cust_lname'];
			$cust_details_address		= $row_get_cust_details['add_details'];
			$cust_city					= $row_get_cust_details['city_name'];
			$cust_pincode				= $row_get_cust_details['add_pincode'];
			$cust_state					= $row_get_cust_details['state_name'];
			$cust_mobile_num			= $row_get_cust_details['cust_mobile_num'];					
				
			$subject					= "Order request : ".$ord_id_show;
			$message_body				= "";
			$message_body 				.= '<table class="" data-module="main Content" height="100" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td><table data-bgcolor="BG Color" height="100" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td><table data-bgcolor="BG Color 01" height="100" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td><table height="100" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td height="100" width="520"><table align="center" border="0" cellpadding="0" cellspacing="0">';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> Order Id : '.$ord_id_show.' </td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-size:14px; color:#494949; font-family:\'Open Sans\', sans-serif; align="left">Hello '.ucwords($vendor_name).'<br><br></td>';		
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left">This is to inform you that a new order has been placed from your storefront on <a href="'.$BaseFolder.'"><b>Planet Educate</b></a>.<br><br>';
			$message_body 				.= '</td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left"> <b>Order Summary:</b><br>Order ID : '.$ord_id_show.'<br>Purchased on  : '.$order_created.'<br><br> ';
			$message_body 				.= '</td>';
			$message_body 				.= '</tr>';			
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left"> <b>Order will be shipped to:</b><br> '.ucwords($cust_fname." ".$cust_lname).':<br> '.'<b>Contact Details:</b><br>'.$cust_mobile_num.'<br>'.$cust_details_address.',<br>'.$cust_pincode.'<br>'.$cust_city.'<br>'.$cust_state.'<br><br>';
			$message_body 				.= '</td>';
			$message_body 				.= '</tr>';				
			$message_body 				.= '<tr><td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td></tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left">'.$order_data;
			$message_body 				.= '<br></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr><td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td></tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-color="Name" data-size="Name" align="left" style="color:#545252;"><br><br>';
			$message_body 				.= '<br>If you have any questions, please get in touch with us at <a href="mailto:support@planeteducate.com">support@planeteducate.com</a>';
			$message_body 				.= '</td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '</table></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '<tr>';
			$message_body 				.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
			$message_body 				.= '</tr>';			
			$message_body 				.= '</table></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '</table></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '</table></td>';
			$message_body 				.= '</tr>';
			$message_body 				.= '</table>';				
			$message 					= mail_template_header()."".$message_body."".mail_template_footer();
			/*sendEmail($vendor_primary_email,$subject,$message);
			if(trim($vendor_secondary_email) != "")
			{
				sendEmail($vendor_secondary_email,$subject,$message);
			}
			if(trim($vendor_tertiary_email) != "")
			{
				sendEmail($vendor_tertiary_email,$subject,$message);
			}
			sendEmail('cynthia@planeteducate.com',$subject,$message);
			sendEmail('prog3.php@edmission.in',$subject,$message);			
			sendEmail('support@planeteducate.com',$subject,$message);*/
		}
	}
	return true;
	/*Select last Order and Details*/	
}
/* Mail On Order*/
/* Mail On Order */
function sendOrderMail($ord_id,$type)
{
	global $db_con;
	global $date;
	global $min_order_value;
	global $shipping_charge;
	$BaseFolder 			= "http://www.planeteducate.com/";	
	$mydate					= getdate(date("U"));
	$today_date				= "$mydate[weekday], $mydate[month] $mydate[mday], $mydate[year]";
	$date_expected_delivery	= getdate(date("U", strtotime('+7 days')));
	$expected_delivery_date	= "$date_expected_delivery[weekday], $date_expected_delivery[month] $date_expected_delivery[mday], $date_expected_delivery[year]";	
	
	/*Select last Order and Details*/
	$sql_order_details 		= " SELECT `ord_id`,`ord_id_show`,`ord_payment_id`, `ord_custid`, `ord_total`, `ord_discount`, `ord_shipping_charges`, `ord_status`, `ord_pay_type`, `ord_pay_status`, `ord_addid`, `ord_comment`, ";
	$sql_order_details 		.= " (SELECT `orstat_name` FROM `tbl_order_status` WHERE `orstat_id` = ord_pay_status) as orstat_name,ord_created ";	
	$sql_order_details 		.= " FROM `tbl_order` tord WHERE 1=1 ";
	if($type == 1)
	{
		$sql_order_details	.= " and tord.ord_id = (SELECT `cart_orderid` FROM `tbl_cart` WHERE `cart_id` = '".$ord_id."') ";
	}
	else if($type == 0)
	{
		$sql_order_details	.= " and tord.ord_id = '".$ord_id."' ";
	}		
	$result_order_details 	= mysqli_query($db_con,$sql_order_details) or die(mysqli_error($db_con));
	$row_order_details		= mysqli_fetch_array($result_order_details);
	$order_cust_id			= $row_order_details['ord_custid'];
	$order_add_id			= $row_order_details['ord_addid'];
	$order_created			= $row_order_details['ord_created'];
	$ord_id_show			= $row_order_details['ord_id_show'];
	if($type == 1)	
	{
		$sql_get_sub_order_status 		= " SELECT orstat_name FROM `tbl_cart` tc INNER JOIN `tbl_order_status` as tos ON tos.orstat_id = tc.`cart_status` where tc.`cart_id` = '".$ord_id."' "; // this is cart it i.e. sub order id
		$result_get_sub_order_status 	= mysqli_query($db_con,$sql_get_sub_order_status) or die(mysqli_error($db_con));	
		$row_get_sub_order_status		= mysqli_fetch_array($result_get_sub_order_status);
		$order_status					= ucwords($row_get_sub_order_status['orstat_name']);		
	}
	elseif($type == 0)
	{
		$order_status			= ucwords($row_order_details['orstat_name']);		
	}
		
	$sql_get_cart_details 			= " Select * from tbl_cart tc where 1=1 ";
	if($type == 1)	
	{
		$sql_get_cart_details 			.= " and tc.`cart_id` = '".$ord_id."' ";
	}	
	elseif($type == 0)	
	{
		$sql_get_cart_details 			.= " and tc.cart_orderid = '".$ord_id."' ";
	}
	$result_get_cart_details 		= mysqli_query($db_con,$sql_get_cart_details) or die(mysqli_error($db_con));
	$order_data						.='<style>th, td { border-bottom: 1px solid #ddd;}</style>';
	$order_data						= '<table style="width:100%;">';
	$order_data						.= '<thead>';
	$order_data						.= '<th align="center" valign="top" style="width="30%">Product</th>';
	$order_data						.= '<th align="center" valign="top" style="width="30%">Name</th>';	
	$order_data						.= '<th align="center" valign="top" style="width="15%">Price</th>';
	$order_data						.= '<th align="center" valign="top" style="width="10%">Quantity</th>';
	$order_data						.= '<th align="center" valign="top" style="width="15%">Total</th>';	
	$order_data						.= '</thead>';
	$order_data						.= '<tbody>';
	$cart_total						= 0;			
	while($row_get_cart_details = mysqli_fetch_array($result_get_cart_details))
	{
		$sql_get_product_details 	= " SELECT `prod_id`, `prod_model_number`, `prod_name`, `prod_orgid`, `prod_brandid`, `prod_catid`,`prod_subcatid`,`prod_status`, ";
		$sql_get_product_details 	.= " (SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_prodid` = tpm.prod_id and prod_img_type = 'main') as prod_img_file_name  FROM `tbl_products_master` tpm WHERE prod_id = '".$row_get_cart_details['cart_prodid']."' ";
		$result_get_product_details = mysqli_query($db_con,$sql_get_product_details) or die(mysqli_error($db_con));
		$row_get_product_details 	= mysqli_fetch_array($result_get_product_details);
		$prod_imagepath				= $BaseFolder."images/planet/org".$row_get_product_details['prod_orgid']."/cat".$row_get_product_details['prod_catid']."/subcat".$row_get_product_details['prod_subcatid']."/prod".$row_get_product_details['prod_id']."/small/".$row_get_product_details['prod_img_file_name'];
		$product_name				= ucwords($row_get_product_details['prod_name']);		
		$order_data					.= '<tr>';
		$order_data					.= '<td align="center" valign="top" width="30%">';
		$order_data					.= '<img src="'.$prod_imagepath.'" alt="'.$product_name.'">';
		$order_data					.= '</td>';		
		$order_data					.= '<td align="center" valign="top" width="30%">';		
		$order_data 				.= '<span>'.$product_name.'</span>';
		$order_data					.= '</td>';
		$order_data					.= '<td align="center" valign="top" width="15%">';
		$order_data					.= $row_get_cart_details['cart_price'];
		$order_data					.= '</td>';		
		$order_data					.= '<td align="center" valign="top" width="10%">';
		$order_data					.= $row_get_cart_details['cart_prodquantity'];
		$order_data					.= '</td>';		
		$order_data					.= '<td align="center" valign="top" width="15%">';
		$order_data					.= $row_get_cart_details['cart_price'];
		$order_data					.= '</td>';				
		$order_data					.= '</tr>';
		$cart_total 				+= (int)$row_get_cart_details['cart_price'];
	}
	$order_data						.= '<tr><td>&nbsp;</td>';	
	$order_data						.= '<td>&nbsp;</td>';	
	$order_data						.= '<td colspan="3">';			
	$order_data						.= '<table>';
	$order_data						.= '<tbody>';
	$order_data						.= '<tr><td><b>Order Status:</b></td>';
	$order_data						.= '<td>:'.$order_status.'</td></tr>';	
	$order_data						.= '<tr><td><b>Item Subtotal:</b></td>';
	$order_data						.= '<td>:'.$cart_total.'</td></tr>';
	$order_data						.= '<tr><td><b>Shipping & Handling:</b></td>';
	if($cart_total > $min_order_value)
	{
		$order_data					.= '<td><span style="color:f00;">Free Shipping</span></td></tr>';		
		$shipping_charge 			= 0;		
	}
	else
	{
		$order_data					.= $shipping_charge;
		$shipping_charge 			= $shipping_charge;
	}
	$order_total					= $cart_total+$shipping_charge;
	$order_data						.= '<tr><td><b>Order Total</b></td>';
	$order_data						.= '<td>:'.$order_total.'</td>';
	$order_data						.= '</tr>';	
	$order_data						.= '</tbody>';
	$order_data						.= '</table>';	
	$order_data						.= '<td></tr>';
	$order_data						.= '</tbody>';
	$order_data						.= '</table>';	
	$sql_get_cust_details   		= " SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_email`, `cust_mobile_num`,`add_details`,`add_pincode`,";
	$sql_get_cust_details   		.= " (SELECT `state_name` FROM `state` WHERE `state`=tam.`add_state`) as state_name,";
	$sql_get_cust_details   		.= " (SELECT `city_name` FROM `city` WHERE `city_id`=tam.`add_city`) as city_name";
	$sql_get_cust_details   		.= " FROM `tbl_customer` tc,`tbl_address_master` tam WHERE tc.`cust_id` = tam.add_user_id and ";
	$sql_get_cust_details   		.= " tam.add_user_type = 'customer' and tc.`cust_id` = '".$order_cust_id."' and tam.add_id = '".$order_add_id."' ";
	$result_get_cust_details 		= mysqli_query($db_con,$sql_get_cust_details) or die(mysqli_error($db_con));
	$row_get_cust_details			= mysqli_fetch_array($result_get_cust_details);
	$cust_email						= $row_get_cust_details['cust_email'];
	$cust_fname						= $row_get_cust_details['cust_fname'];
	$cust_lname						= $row_get_cust_details['cust_lname'];
	$cust_details_address			= $row_get_cust_details['add_details'];
	$cust_city						= $row_get_cust_details['city_name'];
	$cust_pincode					= $row_get_cust_details['add_pincode'];
	$cust_state						= $row_get_cust_details['state_name'];
	
	$subject						= "Order :".$ord_id_show;
	$message_body					= "";
	$message_body 					.= '<table class="" data-module="main Content" height="100" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td><table data-bgcolor="BG Color" height="100" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td><table data-bgcolor="BG Color 01" height="100" width="600" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td><table height="100" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td height="100" width="520"><table align="center" border="0" cellpadding="0" cellspacing="0">';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> Order Id : '.$ord_id_show.' </td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" style="font-size:14px; color:#494949; font-family:\'Open Sans\', sans-serif; align="left">Dear, '.ucwords($cust_fname).'<br><br></td>';		
	$message_body 					.= '</tr>';
/*	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-color="Name" data-size="Name" align="left"> Thank you for shopping at <a href="'.$BaseFolder.'"><b>Planet Educate</b></a>. Your order has been placed successfully.  <a href="'.$BaseFolder.'"><br>';//<b>Planet Educate</b></a> is preparing your order for shipment.<br><br>';
	$message_body 					.= '</td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-color="Name" data-size="Name" align="left"> Your order can be expected to arrive by: <br>'.$expected_delivery_date.'<br><br> ';
	$message_body 					.= '</td>';
	$message_body 					.= '</tr>';
*/	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-color="Name" data-size="Name" align="left"> <b>Order Summary:</b><br>Your Order ID : '.$ord_id_show.'<br>Purchased on  : '.$order_created.'<br><br> ';
	$message_body 					.= '</td>';
	$message_body 					.= '</tr>';			
	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-color="Name" data-size="Name" align="left"> <b>Shipping Details:</b><br> '.ucwords($cust_fname." ".$cust_lname).':<br> '.$cust_details_address.',<br>'.$cust_pincode.'<br>'.$cust_city.'<br>'.$cust_state.'<br><br> ';
	$message_body 					.= '</td>';
	$message_body 					.= '</tr>';	
	$message_body 					.= '<tr><td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td></tr>';									
	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-color="Name" data-size="Name" align="left">'.$order_data;
	$message_body 					.= '<br></td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '<tr><td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td></tr>';											
	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-color="Name" data-size="Name" align="left" style="color:#545252;">';//<br><br> You will receive a shipment confirmation mail once your order has shipped. ';
	$message_body 					.= '<br>If you have any questions, please get in touch with us at <a href="mailto:support@planeteducate.com">support@planeteducate.com</a>';
	$message_body 					.= '<br>We hope to see you again!!!<br><br>';
	$message_body 					.= '</td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '</table></td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '<tr>';
	$message_body 					.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
	$message_body 					.= '</tr>';			
	$message_body 					.= '</table></td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '</table></td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '</table></td>';
	$message_body 					.= '</tr>';
	$message_body 					.= '</table>';			
	
	$message 						= mail_template_header()."".$message_body."".mail_template_footer();	
	//$mail_response 					= sendEmail($cust_email,$subject,$message);
	//sendEmail('support@planeteducate.com',$subject,$message);
	return true;
	/*Select last Order and Details*/	
}
/* Mail On Order*/
/* This function will load all the orders of system.*/
if((isset($obj->load_orders)) == "1" && isset($obj->load_orders))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= $obj->search_text;
	
	$daterange 			= $obj->daterange;	
	$start_date			= $obj->start_date;
	$end_date			= $obj->end_date;	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 						= $page;
		$page 	   	   					= $page - 1;
		$start_offset 					+= $page * $per_page;
		$start 							= $page * $per_page;
			
		$sql_load_data  				 = " SELECT tcm.*,`ord_id`,`ord_id_show`,`ord_custid`, ";
		$sql_load_data  				.= " `ord_total`, `ord_discount`, `ord_shipping_charges`, `ord_status`, `ord_pay_type`, ";
		$sql_load_data  				.= " `ord_pay_status`, `ord_addid`, `ord_comment`, `ord_created`, ";
		$sql_load_data					.=" (SELECT `cust_fname` FROM `tbl_customer` where `cust_id` = ord_custid) as cust_fname,";
		$sql_load_data  				.= " (SELECT `cust_lname` FROM `tbl_customer` where `cust_id` = ord_custid) as cust_lname, ";
		$sql_load_data  				.= " (SELECT `orstat_name` FROM `tbl_order_status` WHERE `orstat_id` = ord_status) as  order_status FROM `tbl_order` as tos ";
		//$sql_load_data  				.= " INNER JOIN tbl_customer as tc ON tos.ord_custid = tc.cust_id  ";
		//$sql_load_data  				.= " INNER JOIN tbl_cart as tcs ON tos.ord_custid = tcs.cart_custid ";
		$sql_load_data  				.= " INNER JOIN tbl_cart as tcm ON tos.ord_id = tcm.cart_orderid ";
		///////done by satish ///////////////////////////////////////////
		$sql_load_data 					.= " WHERE 1=1 AND tcm.cart_type = 'incomplete' AND tos.ord_pay_status = '0' ";
		if($daterange !="All")
		{
			if($daterange =="today")
			{
				$sql_load_data 					.= " AND  ord_created > '".date('Y-m-d')." 00:00:00' ";
			}
			if($daterange =="yesterday")
			{
				$date = date('Y-m-d',strtotime("-1 days"));
				$sql_load_data 					.= " AND  ord_created > '".$date ." 00:00:00' AND  ord_created < '".$date ." 23:59:00' ";
			}
			if($daterange =="this_month")
			{
				$days =cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
				$sql_load_data 					.= " AND  ord_created > '".date('Y-m')."-01 00:00:00' AND  ord_created < '".date('Y-m')."-".$days." 23:59:00' ";
			}
			if($daterange =="last_month")
			{
				$date = date('Y-m-d',strtotime("-1 months"));
				$sql_load_data 					.= " AND  ord_created > '".$date." 00:00:00' AND  ord_created < '".date('Y-m')."-01 00:00:00' ";
			}
			if($daterange =="specific_date")
			{
				$datentime  = explode(' ',$start_date);
				
				$sql_load_data 					.= " AND  ord_created > '".$datentime[0]." 00:00:00' AND  ord_created < '".$datentime[0]." 23:59:00' ";
			}
			if($daterange =="date_range")
			{
				//$date = date('Y-m-d',strtotime("-1 months"));
				$sql_load_data 					.= " AND  ord_created > '".$start_date."' AND  ord_created < '".$end_date."' ";
			}
			
			
		}
		
		if($search_text != "")
		{
			
			$sql_load_data .= " AND (ord_id like '%".$search_text."%' or ord_total like '%".$search_text."%' ";
		    $sql_load_data .= " or cust_lname like '%".$search_text."%' or cust_fname like '%".$search_text."%' or ord_id_show like '%".$search_text."%' )  ";	
		}
		
		//////////// done by satish ////////////////////////////
		$sql_load_data 					.= " GROUP BY ord_id_show ";
		$data_count						= dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data 					.=" ORDER BY ord_created  DESC LIMIT $start, $per_page  ";
		//echo json_encode(array("Success"=>"Success","resp"=>$sql_load_data));exit();
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));					
		if(strcmp($data_count,"0") !== 0)
		{		
			$order_data 				= "";	
			$order_data 				.= '<table id="tbl_user" class="table table-bordered" style="width:100%;text-align:center">';
    	 	$order_data 				.= '<thead>';
    	  	$order_data 				.= '<tr>';
         	$order_data 				.= '<th style="text-align:center">Sr No.</th>';
			$order_data 				.= '<th style="text-align:center">Order Details</th>';
			$order_data 				.= '<th style="text-align:center">Customer Name</th>';
			$order_data 				.= '<th style="text-align:center">Order Date</th>';
			$order_data 				.= '<th style="text-align:center">Total Amount</th>';
			$order_data 				.= '<th style="text-align:center">View</th>';
			//$order_data 				.= '<th style="text-align:center">Order Status</th>';
          	$order_data 				.= '</tr>';
      		$order_data 				.= '</thead>';
      		$order_data 				.= '<tbody>';
			$sql_get_status 			= " SELECT * FROM `tbl_order_status` WHERE `orstat_status` = 1 order by orstat_sort_order";
			$result_get_status 			= mysqli_query($db_con,$sql_get_status) or die(mysqli_error($db_con));			
			$row_get_status				= array();
			while($row_get_ststus = mysqli_fetch_array($result_get_status))
			{				
				$row_get_status[$row_get_ststus['orstat_id']] = $row_get_ststus['orstat_name'];
			}
			
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
				$sql_load_cart_data	= " SELECT `cart_id`, `cart_custid`, `cart_prodid`, `cart_prodquantity`, `cart_unit`, `cart_price`, `cart_coupon_code`, `cart_discount`, `cart_orderid`, ";
				$sql_load_cart_data	.= " `cart_status`,(SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_prodid` = cart_prodid and `prod_img_type` = 'main') as cart_prod_image_file ";
				$sql_load_cart_data	.= " FROM `tbl_cart` WHERE `cart_orderid` = '".$row_load_data['ord_id']."' ";			
				$result_load_cart_data 	= mysqli_query($db_con,$sql_load_cart_data) or die(mysqli_query($db_con));	
				
				while($row_load_cart_data = mysqli_fetch_array($result_load_cart_data))
				{
					$prod_quentity		= $row_load_cart_data['cart_prodquantity'].'hsagdh';
					$prod_price			= $row_load_cart_data['cart_price'];
					$prod_unit_price	= $row_load_cart_data['cart_unit'];
					$prod_total			= $prod_quentity*$prod_unit_price;	
					$cart_total		   += $prod_total;//echo json_encode($cart_total);exit();
				}
				
				$order_data 			.= '<tr>';
				$order_data 			.= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$order_data 			.= '<td>';
				$order_data 			.= '<div><input type="button" value="'.$row_load_data['ord_id_show'].'" class="btn-link" id="'.$row_load_data['ord_id'].'" onClick="orderDeatils(this.id);">';
				$order_data 			.= '</div>';
				$order_data 			.= '<div>';
				$order_data 			.= '<div><b>Discount:</b>';
				if($row_load_data['ord_discount'] != 0)
				{
					$order_data 		.= $row_load_data['ord_discount'];
				}
				else
				{
					$order_data 		.= 'No Discount';
				}
				$order_data 			.= '</div>';
				$order_data 			.= '<div><b>Order Comment:</b>'.$row_load_data['ord_comment'].'</div>';
				$order_data 			.= '<div><b>Payment method:</b>'.$row_load_data['ord_pay_type'].'</div>';
				$order_data 			.= '</div>';
				$cust_name				 = ucwords($row_load_data['cust_fname'])."&nbsp;".ucwords($row_load_data['cust_lname']);
				$order_data				.='<td>'.$cust_name.'</td>';
				$order_data				.='<td>'.$row_load_data['ord_created'].'</td>';
				$order_data				.='<td>'.$row_load_data['ord_total'];
				
				
				
			$cart_total;//echo json_encode($cart_total);exit();
			
			$sql_get_fs_data	= " SELECT * FROM `tbl_free_shipping` WHERE fs_status='1' ";
		   $res_get_fs_data	= mysqli_query($db_con, $sql_get_fs_data) or die(mysqli_error($db_con));
			
			while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
			{
				if($row_get_fs_data['fs_type_value'] == 0)
				{
					if($cart_total >= $row_get_fs_data['fs_start_price'])
					{
						
						$cart_data		.= 'Free Shipping';
						//$shipping_charge = $row_get_fs_data['fs_type_value'];	
						$shipping_charge = 'Free Shipping';	
						//$order_data		.= $cart_data;
						$order_data		.= '('.$shipping_charge.')';
					}
				}
				elseif($row_get_fs_data['fs_type_value'] != 0)
				{
					if(($row_get_fs_data['fs_start_price'] <= $cart_total) && ($cart_total <= $row_get_fs_data['fs_end_price']))
					{
						
							$cart_data			.= $row_get_fs_data['fs_type_value'];
							$shipping_charge 	= $row_get_fs_data['fs_type_value'];
							$order_data		.= '('.$shipping_charge.')';	
					
					}
				}
			}
				
				
				
				
				
				
				//$order_data				.= '('.$row_load_data['ord_shipping_charges'].' ) </td>';
				$order_data 			.= '<td style="text-align:center" class="middle-text">';
				$order_data 			.= '<i class="icon-chevron-down" id="'.$row_load_data['ord_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['ord_id'].'order_div_1\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;">View Sub Orders</i>';
				$order_data 			.= '</td>';
				/*$order_data 			.= '<td style="text-align:center" class="middle-text">';
				$order_data 			.= '<div><select name="ord_status'.$row_load_data['ord_id'].'" id="ord_status'.$row_load_data['ord_id'].'" class="select2-me input-large" onchange="changeStatus(\''.$row_load_data['ord_id'].'\',0);">';
				$order_data 			.= '<option value="">Select Status</option>';	
				foreach($row_get_status as $orstat_id => $orstat_name)
				{
					$order_data 		.= '<option value="'.$orstat_id.'"';
					if($orstat_id == $row_load_data['ord_pay_status'])
					{
						$order_data 	.= 'selected="selected" ';
					}
					$order_data 		.= '>'.ucwords($orstat_name).'</option>';
				}
				$order_data 			.= '</select></div><br>';
				$order_data 			.= '<div>';
				$order_data				.= '<input type="button" value="Send Email for Order: '.$row_load_data['ord_id_show'].' to Vendor" onclick="sendOrderEmail(0,\'vendor\',\''.$row_load_data['ord_id'].'\');" class="btn-success"/>';
				$order_data 			.= '</div><br>';				
				$order_data 			.= '<div class="center-text">';				
				$order_data				.= '<input type="button" value="Send Email for Order: '.$row_load_data['ord_id_show'].' to Customer" onclick="sendOrderEmail(0,\'customer\',\''.$row_load_data['ord_id'].'\');" class="btn-success"/>';
				$order_data 			.= '</div>';
				$order_data 			.= '<script type="text/javascript">';
				$order_data 			.= '$("#ord_status"+'.$row_load_data['ord_id'].').select2();';
				$order_data 			.= '</script>';
				$order_data 			.= '</td>';*/
	          	$order_data 			.= '</tr>';
				$order_data 			.= '<tr style="display:none;" id="'.$row_load_data['ord_id'].'order_div_1"><td colspan="7">';
				$sql_get_sub_orders 	= " select cart_id,prod_title,cart_prodquantity,cart_price,cart_status ";
				$sql_get_sub_orders 	.= " from tbl_cart tc INNER JOIN tbl_products_master tpm ON tc.cart_prodid=tpm.prod_id where cart_orderid = '".$row_load_data['ord_id']."' AND  cart_status = 0 ";
				$result_get_sub_orders 	= mysqli_query($db_con,$sql_get_sub_orders) or die(mysqli_error($db_con));
				$order_data 			.= '<table class="table" style="background-color:#545252;">';
				$order_data 			.= '<th class="center-text">Sub Order Id</th>';
				$order_data 			.= '<th class="center-text">Product Name</th>';
				$order_data 			.= '<th class="center-text">Quantity</th>';
				$order_data 			.= '<th class="center-text">Price</th>';
				//$order_data 			.= '<th class="center-text">Sub Order Status</th>';					
				$order_data 			.= '<tbody style="color:#fff;">';									
				while($row_get_sub_orders 	= mysqli_fetch_array($result_get_sub_orders))
				{
					$order_data 		.= '<tr><td style="width:10%;" class="center-text">'.$row_get_sub_orders['cart_id'].'</td>';					
					$order_data 		.= '<td style="width:30%;">'.$row_get_sub_orders['prod_title'].'</td>';
					$order_data 		.= '<td style="width:15%;" class="center-text">'.$row_get_sub_orders['cart_prodquantity'].'</td>';
					$order_data 		.= '<td style="width:15%;" class="center-text">'.$row_get_sub_orders['cart_price'].'</td>';
					/*$order_data 		.= '<td style="width:30%;" class="center-text">';
					$order_data 		.= '<div><select name="cart_status'.$row_get_sub_orders['cart_id'].'" id="cart_status'.$row_get_sub_orders['cart_id'].'" class="select2-me input-large" onchange="changeStatus(\''.$row_get_sub_orders['cart_id'].'\',1);">';
					$order_data 		.= '<option value="">Select Status</option>';	
					foreach($row_get_status as $orstat_id => $orstat_name)
					{
						$order_data 		.= '<option value="'.$orstat_id.'"';
						if($orstat_id == $row_get_sub_orders['cart_status'])
						{
							$order_data 	.= 'selected="selected" ';
						}
						$order_data 		.= '>'.ucwords($orstat_name).'</option>';
					}
					$order_data 		.= '</select></div><br>';
					$order_data 		.= '<div class="center-text">';
					$order_data			.= '<input type="button" value="Send Email for Order '.$row_load_data['ord_id_show'].'-'.$row_get_sub_orders['cart_id'].' to Vendor" onclick="sendOrderEmail(1,\'vendor\',\''.$row_get_sub_orders['cart_id'].'\');" class="btn-success"/>';
					$order_data 		.= '</div class="center-text"><br>';				
					$order_data 		.= '<div>';				
					$order_data			.= '<input type="button" value="Send Email for Order '.$row_load_data['ord_id_show'].'-'.$row_get_sub_orders['cart_id'].' to Customer" onclick="sendOrderEmail(1,\'customer\',\''.$row_get_sub_orders['cart_id'].'\');" class="btn-success"/>';
					$order_data 		.= '</div>';					
					$order_data 		.= '</td>';*/
					$order_data 		.= '</tr>';					
					$order_data 		.= '<script type="text/javascript">';
					$order_data 		.= '$("#cart_status"+'.$row_get_sub_orders['cart_id'].').select2();';
					$order_data 		.= '</script>';
				}
				$order_data 			.= '</tbody>';
				$order_data 			.= '</table>';				
				$order_data 			.= '</td></tr>';
			}	
      		$order_data 				.= '</tbody>';
      		$order_data 				.= '</table>';	
			$order_data 				.= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$order_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available in Orders");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}
/* This function will load all the orders of system.*/
/* This function will load all the orders of perticular customer.*/
if((isset($obj->cust_orders)) == "1" && isset($obj->cust_orders))
{
	$cust_id 		= mysqli_real_escape_string($db_con,$obj->cust_id);
	if($cust_id != "")
	{
		$sql_get_user 		= "SELECT * FROM `tbl_customer` WHERE `cust_id` = '".$cust_id."' ";
		$result_get_user	= mysqli_query($db_con,$sql_get_user) or die(mysqli_error($db_con));
		$num_rows_get_user = mysqli_num_rows($result_get_user);
		if($num_rows_get_user != 0)
		{
				$row_get_user		= mysqli_fetch_array($result_get_user);
				$cust_id			= $row_get_user['cust_id'];
				$cust_id			= ucwords($row_get_user['cust_fname'])."&nbsp;".ucwords($row_get_user['cust_lname']);
				$sql_get_orders		= " SELECT `ord_id`, `ord_custid`, `ord_total`, `ord_discount`, `ord_shipping_charges`, `ord_status`,";
				$sql_get_orders		.= " `ord_pay_type`, `ord_pay_status`, `ord_addid`, `ord_comment`,(SELECT `orstat_name` FROM `tbl_order_status` WHERE `orstat_id` = ord_status ) as  order_status";
				$sql_get_orders		.= " FROM `tbl_order` WHERE `ord_custid` = '".$cust_id."' and `ord_pay_status` = '1' ";
				$result_get_orders	= mysqli_query($db_con,$sql_get_orders) or die(mysqli_error($db_con));
				$num_rows_get_orders = mysqli_num_rows($result_get_orders);
				if($num_rows_get_orders == 0)
				{
					$response_array = array("Success"=>"Success","resp"=>'<span style="color:red;text-align:center;">No Orders yet!!!</span>');
				}
				else
				{
					$order_data = '';
					$order_data .= '<div class="col-md-12">';	
					while($row_get_orders = mysqli_fetch_array($result_get_orders))
					{
						  $sql_get_cart_products		= " SELECT * FROM `tbl_cart` WHERE `cart_orderid` = '".$row_get_orders['ord_id']."' ";
						  $result_get_cart_products	= mysqli_query($db_con,$sql_get_cart_products) or die(mysqli_error($db_con));
						  $num_rows_get_cart_products = mysqli_num_rows($result_get_cart_products);
						  if($num_rows_get_cart_products == 0)
						  {
							  
						  }
						  else
						  {
						  $order_data .= '<input type="button" style="margin:20px;" class="btn btn-primary btn-md" onClick="ToggleMyDiv(\'order'.$row_get_orders['ord_id'].'\');" value="Order Id :'.$row_get_orders['ord_id'].'"><br>';
						  $order_data .= '<div class="col-md-12" id="order'.$row_get_orders['ord_id'].'" style="border: 1px solid #C0C0C0; margin:20px;">'; // div per order
						  $order_data .= '<div class="col-md-12">'; // div contain order wise cart data							
						  $order_data .= '<div class="col-md-4" >'; // div contain order name address etc data
						  $order_data .= '<b>Address Details:</b>';
						  $sql_get_address = " SELECT `add_id`, `add_details`, ( SELECT state_name FROM `state` where state = add_state) as add_state_name , ";
						  $sql_get_address .= " ( SELECT `city_name` FROM `city` WHERE `city_id` = add_city) as add_city_name , `add_pincode` ";
						  $sql_get_address .= " FROM `tbl_address_master` WHERE `add_id` = '".$row_get_orders['ord_addid']."' and ";
						  $sql_get_address .= " `add_user_type` =  'customer' and `add_user_id` = '".$row_get_orders['ord_custid']."' ";
						  $result_get_address = mysqli_query($db_con,$sql_get_address) or die(mysqli_error($db_con));
						  $num_rows_get_address = mysqli_num_rows($result_get_address);
						  if($num_rows_get_address == 0)
						  {
							  $order_data .= '<span style="color:f00;">Record Not Available.</span>';
						  }
						  else
						  {
							  $row_get_address = mysqli_fetch_array($result_get_address);
							  $order_data .= '<table class="table">';
							  $order_data .= '<tr><td><b>Address:</b></td><td>:&nbsp;&nbsp;';
							  if($row_get_address['add_details'] == "")
							  {
								  $order_data .= '<span style="color:#f00;">Not Available</span></td></tr>';
							  }
							  else
							  {
								  $order_data .= ucwords($row_get_address['add_details']).'</td></tr>';
							  }	
							  $order_data .= '<tr><td><b>Pincode</b></td><td>:&nbsp;&nbsp;';
							  if($row_get_address['add_pincode'] == 0)
							  {
								  $order_data .= '<span style="color:f00;">Not Available</span></td></tr>';
							  }
							  else
							  {
								  $order_data .= $row_get_address['add_pincode'].'</td></tr>';
							  }															
							  $order_data .= '<tr><td><b>City</b></td><td>:&nbsp;&nbsp;';
							  if($row_get_address['add_city_name'] == "")
							  {
								  $order_data .= '<span style="color:f00;">Not Available</span></td></tr>';
							  }
							  else
							  {
								  $order_data .= $row_get_address['add_city_name'].'</td></tr>';
							  }							
							  $order_data .= '<tr><td><b>State</b></td><td>:&nbsp;&nbsp;';
							  if($row_get_address['add_state_name'] == "")
							  {
								  $order_data .= '<span style="color:f00;">Not Available</span></td></tr>';
							  }
							  else
							  {
								  $order_data .= $row_get_address['add_state_name'].'</td></tr>';
							  }								
							  $order_data .= '</table>';															
						  }	
						  $order_data .= '</div>'; // div contain order name address etc data
						  $order_data .= '<div class="col-md-3">'; // div contain order name address etc data	
						  $order_data .= '<br><table class="table">';
						  $order_data .= '<tr><td><b>Order Status:</b></td><td>:&nbsp;&nbsp;';
						  if($row_get_orders['order_status'] == "")
						  {
							  $order_data .= '<span style="color:#f00;">Not Available</span></td></tr>';
						  }
						  else
						  {
							  $order_data .= ucwords($row_get_orders['order_status']).'</td></tr>';
						  }	
						  /*$order_data .= '<tr><td><b>Payment Status</b></td><td>:&nbsp;&nbsp;';
						  if($row_get_orders['ord_status'] == 0)
						  {
							  $order_data .= '<span style="color:f00;">Not Available</span></td></tr>';
						  }
						  else
						  {
							  $order_data .= $row_get_orders['ord_status'].'</td></tr>';
						  }*/
						  $order_data .= '<tr><td><b>Payment Mode</b></td><td>:&nbsp;&nbsp;';
						  if($row_get_orders['ord_pay_type'] == "")
						  {
							  $order_data .= '<span style="color:f00;">Not Available</span></td></tr>';
						  }
						  else
						  {
							  $order_data .= $row_get_orders['ord_pay_type'].'</td></tr>';
						  }
						  $order_data .= '<tr><td><b>Order Comment</b></td><td>:&nbsp;&nbsp;';
						  if($row_get_orders['ord_comment'] == "")
						  {
							  $order_data .= '<span style="color:f00;">Not Available</span></td></tr>';
						  }
						  else
						  {
							  $order_data .= $row_get_orders['ord_comment'].'</td></tr>';
						  }															
						  $order_data .= '</table>';											
						  $order_data .= '</div>'; // div contain order name address etc data	
						  $order_data .= '<div class="col-md-5">';
						  $order_data .= '</div>';
						  $order_data .= '</div>';							
						  $order_data .= '<div class="col-md-12">';												
						  $order_data .= '<table class="table table-condensed">'; // table for each cart items
						  $order_data .= '<thead>'; // header on tables
						  $order_data	.= '<th>Sr.No</th>';
						  /*$order_data	.= '<th>Image</th>';*/		
						  $order_data	.= '<th>Product</th>';		
						  $order_data	.= '<th>Quantity</th>';		
						  $order_data	.= '<th>Price</th>';
						  $order_data	.= '<th>Coupon</th>';
						  $order_data	.= '<th>Discount</th>';				
						  $order_data	.= '<th>Total</th>';							
						  $order_data .= '</thead>';					
						  $order_data .= '<tbody>';	
						  $start 		= 0; // to initialise product count
						  while($row_get_cart_products = mysqli_fetch_array($result_get_cart_products))				
						  {
							  $sql_get_products		= " SELECT * FROM `tbl_products_master` WHERE `prod_id` = '".$row_get_cart_products['cart_prodid']."' ";
							  $result_get_products	= mysqli_query($db_con,$sql_get_products) or die(mysqli_error($db_con));
							  $num_rows_get_products = mysqli_num_rows($result_get_products);
							  if($num_rows_get_products == 0)
							  {
								  
							  }
							  else
							  {
								  $row_get_products = mysqli_fetch_array($result_get_products);
							  }
							  $order_data .= '<tr>';
							  $order_data .= '<td>'.++$start.'</td>';								
							  /*$imagepath 	= "images/planet/org".$row_get_products['prod_orgid']."/cat".$row_get_products['prod_catid']."/subcat".$row_get_products['prod_subcatid']."/prod".$row_get_products['prod_id']."/medium/".$row_get_products['prod_images'];
							  $order_data	.= '<td><img src="'.$imagepath.'" onerror="this.src=\'images/no-image.jpg\'" style="width:60px"></td>';*/
							  $order_data	.= '<td style="text-overflow: ellipsis;">'.ucwords($row_get_products['prod_name']).'</td>';
							  $order_data	.= '<td>'.$row_get_cart_products['cart_prodquantity'].'</td>';			
							  $order_data	.= '<td>'.$row_get_products['prod_recommended_price'].'</td>';	
							  if(trim($row_get_cart_products['cart_coupon_code']) == "")
							  {
								  $order_data			.= '<td><span style="color:f00;">Not Applied</span></td>';				
							  }
							  else
							  {
								  $order_data			.= '<td>'.$row_get_cart_products['cart_coupon_code'].'</td>';				
							  }
							  if(trim($row_get_cart_products['cart_discount']) == "")
							  {
								  $order_data			.= '<td><span style="color:f00;">No Discount</span></td>';
							  }
							  else
							  {
								  $order_data			.= '<td>'.$row_get_cart_products['cart_discount'].'</td>';											
							  }
							  $order_data	.= '<td>'.$row_get_cart_products['cart_price'].'</td>';
							  $order_data .= '</tr>';								
						  }
						  $order_data .= '</tbody>';					
						  $order_data .= '</table>';	// table for each cart items
						  $order_data .= '</div>';							
						  $order_data .= '<div class="col-md-12">';														
						  $order_data .= '<div class="col-md-8">';							
						  $order_data .= '</div>';
						  $order_data .= '<div class="col-md-4">';
						  $order_data .= '<table class="table">';
						  $order_data .= '<tr><td><b>Shipping Charges</b></td><td>:&nbsp;&nbsp;';
						  if($row_get_orders['ord_shipping_charges'] == 0 || $row_get_orders['ord_shipping_charges'] == "")
						  {
							  $order_data .= '<span style="color:#00DF00;">No Shipping Charges</span></td></tr>';
						  }
						  else
						  {
							  $order_data .= $row_get_orders['ord_shipping_charges'].'</td></tr>';
						  }								
						  $order_data .= '<tr><td><b>Total Discount</b></td><td>:&nbsp;&nbsp;';
						  if($row_get_orders['ord_discount'] == 0)
						  {
							  $order_data .= '<span style="color:f00;">No Discount</span></td></tr>';
						  }
						  else
						  {
							  $order_data .= $row_get_orders['ord_discount'].'</td></tr>';
						  }							
						  $order_data .= '<tr><td><b>Total Amount</b></td><td>:&nbsp;&nbsp;'.$row_get_orders['ord_total'].'</td></tr>';
						  $order_data .= '</table>';							
						  $order_data .= '</div>';
						  $order_data .= '</div>'; // div per order				
						  $order_data .= '</div>'; // div contain order wise cart data
					  }
					  }																	
					$order_data .= '</div>';											
					$response_array = array("Success"=>"Success","resp"=>$order_data,"cust_name"=>$cust_name);																	
				}				
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Your account not exists.");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Id not available");
	}
	echo json_encode($response_array);
}
/* This function will load all the orders of perticular customer.*/
/* This function send mail to customer or vendor for order.*/
if((isset($obj->send_Order_mail)) == "1" && isset($obj->send_Order_mail))
{
	$towhom 	= mysqli_real_escape_string($db_con,$obj->towhom);
	$type_id 	= mysqli_real_escape_string($db_con,$obj->type_id);
	$type 		= mysqli_real_escape_string($db_con,$obj->type);	
	if($type_id != "")
	{
		if($towhom == "vendor")
		{
			if(sendOrderEmailVendor($type_id,$type))
			{
				$response_array = array("Success"=>"fail","resp"=>"Mail Sent to Vendor");
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Mail Not Sent");				
			}
		}
		elseif($towhom == "customer")
		{
/*			$response_array = array("Success"=>"fail","resp"=>sendOrderMail($type_id,$type)."Mail Sent to Vendor");
			echo json_encode($response_array);
			exit(0);	*/		
			if(sendOrderMail($type_id,$type))
			{
				$response_array = array("Success"=>"fail","resp"=>"Mail Not Sent to Customer");				
			}
			else
			{
				$response_array = array("Success"=>"fail","resp"=>"Mail Not Sent");				
			}
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Mail Not Sent");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Order Id not available");
	}
	echo json_encode($response_array);
}
/* This function send mail to customer or vendor for order.*/
/* This function will load perticular order details*/
if((isset($obj->load_order_details)) == "1" && isset($obj->load_order_details))
{
	$ord_id = mysqli_real_escape_string($db_con,$obj->ord_id);
	if($ord_id != "")
	{
		$sql_get_order 		= " SELECT `ord_payment_id`,`ord_id_show`, `ord_custid`, `ord_total`, `ord_discount`, `ord_shipping_charges`, ";
		$sql_get_order 		.= " `ord_status`, `ord_pay_type`, `ord_pay_status`, `ord_addid`, `ord_comment`, `ord_created`, ";
		$sql_get_order 		.= " `add_details`, (SELECT `state_name` FROM `state` WHERE `state`=`add_state`) as address_state ,";
		$sql_get_order 		.= " (SELECT `city_name` FROM `city` WHERE `city_id` = `add_city`) as address_city , `add_pincode`,`cust_fname`, `cust_lname`, `cust_email`, ";
		$sql_get_order 		.= " `cust_mobile_num` FROM `tbl_order` tord INNER JOIN `tbl_customer` tcust ON tord.ord_custid = tcust.cust_id ";
		$sql_get_order 		.= " INNER JOIN  `tbl_address_master` tam ON tord.ord_addid = tam.add_id WHERE tam.add_user_type = 'customer'  ";
		$sql_get_order 		.= " and `ord_id` = '".$ord_id."' ";
		$result_get_order 	= mysqli_query($db_con,$sql_get_order) or die(mysqli_error($db_con));
		$num_rows_get_order = mysqli_num_rows($result_get_order);
		if($num_rows_get_order == 1)
		{
			$row_get_order	= mysqli_fetch_array($result_get_order);
//			$order_id		= $row_get_order[''];
			$cust_fname		= $row_get_order['cust_fname'];
			$cust_lname		= $row_get_order['cust_lname'];
			$cust_contact	= $row_get_order['cust_mobile_num'];
			$cust_email		= $row_get_order['cust_email'];
			$detail_address = $row_get_order['add_details'];
			$cust_city		= $row_get_order['address_city'];
			$cust_state		= $row_get_order['address_state'];			
			$cust_pincode	= $row_get_order['add_pincode'];	
			$order_status	= $row_get_order['ord_status'];
			$payment_mode	= $row_get_order['ord_pay_type'];
			$order_comment	= $row_get_order['ord_comment'];	
			$ord_id_show	= $row_get_order['ord_id_show'];	
			$discount_total	= $row_get_order['ord_discount'];
			
			$sql_load_cart_data	= " SELECT `cart_id`, `cart_custid`, `cart_prodid`, `cart_prodquantity`, `cart_unit`, `cart_price`, `cart_coupon_code`, `cart_discount`, `cart_orderid`, ";
			$sql_load_cart_data	.= " `cart_status`,(SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_prodid` = cart_prodid and `prod_img_type` = 'main') as cart_prod_image_file ";
			$sql_load_cart_data	.= " FROM `tbl_cart` WHERE `cart_orderid` = '".$ord_id."' ";			
			$result_load_cart_data 	= mysqli_query($db_con,$sql_load_cart_data) or die(mysqli_query($db_con));			
			$order_data			= "";
			$order_data			.= '<div style="float:left:"><h3>Order id '.$ord_id_show.'</h3>';
			$order_data			.= '<input type="button" style="float:right;" value="Send Order Email to Vendor" onclick="sendOrderEmail(\'vendor\',\''.$ord_id.'\');" class="btn-success"/><br><br>';
			$order_data			.= '<input type="button" style="float:right;"value="Send Order Email to Customer" onclick="sendOrderEmail(\'customer\',\''.$ord_id.'\');" class="btn-success"/>';
			$order_data			.= '</div>';
			$order_data			.= '<div style="clear:both;"></div>';			
			$order_data 		.= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center;border: 1px solid #ccc;">';
    	 	$order_data 		.= '<thead>';
    	  	$order_data 		.= '<tr>';
         	$order_data 		.= '<th style="text-align:center">Sr No.</th>';
			$order_data 		.= '<th style="text-align:center">Product Id</th>';
			$order_data 		.= '<th style="text-align:center" colspan="2">Product</th>';
			$order_data 		.= '<th style="text-align:center">Quantity</th>';
			$order_data 		.= '<th style="text-align:center">Price</th>';
			$order_data 		.= '<th style="text-align:center">Total</th>';
          	$order_data 		.= '</tr>';
      		$order_data 		.= '</thead>';
      		$order_data 		.= '<tbody>';
			$start_offset		= 0;
			$cart_total			= 0;
			$final_total		= 0;	
			//$discount_total		= 0;
			while($row_load_cart_data = mysqli_fetch_array($result_load_cart_data))
			{
				//$discount_total		= $row_load_cart_data['cart_discount'];
				$prod_id			= $row_load_cart_data['cart_prodid'];
				$prod_quentity		= $row_load_cart_data['cart_prodquantity'];
				$prod_price			= $row_load_cart_data['cart_price'];
				$prod_unit_price	= $row_load_cart_data['cart_unit'];	//====================== Changes dn by Prathamesh on 02-01-2016
				$cart_prod_image_file = $row_load_cart_data['cart_prod_image_file'];
				$sql_prod_data	= " SELECT `prod_id`,`prod_name`, `prod_title`, `prod_orgid`, `prod_catid`, `prod_subcatid` FROM `tbl_products_master` WHERE `prod_id` = '".$prod_id."' ";
				$result_prod_data = mysqli_query($db_con,$sql_prod_data) or die(mysqli_error($db_con));
				$row_prod_data 	= mysqli_fetch_array($result_prod_data);				
				$prod_title		= $row_prod_data['prod_title'];				
				$prod_imagepath	= "../images/planet/org".$row_prod_data['prod_orgid']."/prod_id_".$row_prod_data['prod_id']."/small/".$cart_prod_image_file;
				//$prod_total		= $prod_quentity*$prod_price;	//========================== Changes dn by Prathamesh on 02-01-2016
				$prod_total		= $prod_quentity*$prod_unit_price;	//========================== Changes dn by Prathamesh on 02-01-2016
	    	  	$order_data 	.= '<tr>';
				$order_data 	.= '<td style="text-align:center">'.++$start_offset.'</td>';
				$order_data 	.= '<td style="text-align:center">'.$prod_id.'</td>';
				$order_data 	.= '<td style="text-align:center"><img src="'.$prod_imagepath.'"></td>';				
				$order_data 	.= '<td style="text-align:center">'.$prod_title.'</td>';
				$order_data 	.= '<td style="text-align:center">'.$prod_quentity.'</td>';				
				//$order_data 	.= '<td style="text-align:center">'.$prod_price.'</td>';		// Changes dn by Prathamesh on 02-01-2016
				$order_data 	.= '<td style="text-align:center">'.$prod_unit_price.'</td>';	// Changes dn by Prathamesh on 02-01-2016
				//$order_data 	.= '<td style="text-align:center">'.$prod_total.'</td>';		// Changes dn by Prathamesh on 02-01-2016
				$order_data 	.= '<td style="text-align:center">'.$prod_price.'</td>';		// Changes dn by Prathamesh on 02-01-2016				
	          	$order_data 	.= '</tr>';	
				$cart_total		+= $prod_total;
			}	
      		$order_data 		.= '</tbody>';
      		$order_data 		.= '</table>';
      		$order_data 		.= '<div style="width:100%;padding-top:20px;" align="center">';
			$order_data 		.= '<table class="table table-bordered" style="width:10%;float:left !important;border: 1px solid #ccc;">';
			$order_data 		.= '<thead>';
    	  	$order_data 		.= '<tr>';
         	$order_data 		.= '<th style="text-align:center">Customer Details:</th>';
          	$order_data 		.= '</tr>';
      		$order_data 		.= '</thead>';
      		$order_data 		.= '<tbody>';
          	$order_data 		.= '<tr><td>'.ucwords($cust_fname)." ".ucwords($cust_lname).'</td></tr>';							
          	$order_data 		.= '<tr><td>'.$cust_contact.'</td></tr>';
          	$order_data 		.= '<tr><td>'.$cust_email.'</td></tr>';
      		$order_data 		.= '</tbody>';						
      		$order_data 		.= '</table>';
			$order_data 		.= '<table class="table table-bordered" style="width:30%;float:left !important;border: 1px solid #ccc;float:left;">';
			$order_data 		.= '<thead>';
    	  	$order_data 		.= '<tr>';
         	$order_data 		.= '<th style="text-align:center">Address Details:</th>';
          	$order_data 		.= '</tr>';
      		$order_data 		.= '</thead>';
      		$order_data 		.= '<tbody>';
          	$order_data 		.= '<tr><td>'.$detail_address.'</td></tr>';							
          	$order_data 		.= '<tr><td>'.$cust_city.'</td></tr>';
          	$order_data 		.= '<tr><td>'.$cust_state.'</td></tr>';
          	$order_data 		.= '<tr><td>'.$cust_pincode.'</td></tr>';			
      		$order_data 		.= '</tbody>';						
      		$order_data 		.= '</table>';
			$order_data 		.= '<table class="table table-bordered" style="width:50%;float:left !important;border: 1px solid #ccc;float:left;">';
			$order_data 		.= '<thead>';
    	  	$order_data 		.= '<tr>';
         	$order_data 		.= '<th style="text-align:center">Payment method</th>';
         	$order_data 		.= '<th style="text-align:center">Shipping Charges</th>';
         	$order_data 		.= '<th style="text-align:center">Total Discount</th>';
         	$order_data 		.= '<th style="text-align:center">Final Total</th>';
          	$order_data 		.= '</tr>';
      		$order_data 		.= '</thead>';
      		$order_data 		.= '<tbody>';
          	$order_data 		.= '<tr><td style="text-align:center">'.$payment_mode.'</td>';							
          	$order_data 		.= '<td style="text-align:center">';
			$cart_total;
			/*if($cart_total > $min_order_value)
			{
				$order_data		.= 'Free Shipping';
				$shipping_charge = 0;			
			}
			else
			{
				$order_data		.= $shipping_charge;
				$shipping_charge = $shipping_charge;
			}*/
			$sql_get_fs_data	= " SELECT * FROM `tbl_free_shipping` WHERE fs_status='1' ";
		   $res_get_fs_data	= mysqli_query($db_con, $sql_get_fs_data) or die(mysqli_error($db_con));
			
			while($row_get_fs_data = mysqli_fetch_array($res_get_fs_data))
			{
				if($row_get_fs_data['fs_type_value'] == 0)
				{
					if($cart_total >= $row_get_fs_data['fs_start_price'])
					{
						
						$cart_data		 .= 'Free Shipping';
						$shipping_charge  = $row_get_fs_data['fs_type_value'];	
						$order_data		 .= $cart_data;
					}
				}
				elseif($row_get_fs_data['fs_type_value'] != 0)
				{
					if(($row_get_fs_data['fs_start_price'] <= $cart_total) && ($cart_total <= $row_get_fs_data['fs_end_price']))
					{
						
							$cart_data			.= $row_get_fs_data['fs_type_value'];
							$shipping_charge 	= $row_get_fs_data['fs_type_value'];
							$order_data		.= $shipping_charge;	
					
					}
				}
			}
			
						
      		$order_data 		.= '</td>';
          	$order_data 		.= '<td style="text-align:center">';
			if($discount_total == 0)
			{
				$order_data		.= 'No Discount';
			}
			else
			{
				$order_data		.= $discount_total;
			}										
      		$order_data 		.= '</td>';						
			$final_total		= $cart_total + $shipping_charge - $discount_total;
          	$order_data 		.= '<td style="text-align:center">'.$final_total.'</td>';									
      		$order_data 		.= '</tbody>';
      		$order_data 		.= '</table>';
      		$order_data 		.= '</div>';
			
			$response_array = array("Success"=>"Success","resp"=>$order_data);						
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Order Id not available");
		}
	}
	else
	{		
		$response_array = array("Success"=>"fail","resp"=>"Order Id not available");
	}
	echo json_encode($response_array);
}
/* This function will load perticular order details*/
if((isset($obj->change_order_status)) == "1" && isset($obj->change_order_status))
{
	$type 	= mysqli_real_escape_string($db_con,$obj->type);	
	$new_status = mysqli_real_escape_string($db_con,$obj->new_status);		
	if($type == 0)
	{
		$ord_id 	= trim(mysqli_real_escape_string($db_con,$obj->type_id));
		if($ord_id != "")
		{
			$sql_update_order_status 	= " UPDATE `tbl_order` SET `ord_pay_status`= '".$new_status."',`ord_modified`='".$datetime."' WHERE `ord_id` = '".$ord_id."' ";
			$result_update_order_status	= mysqli_query($db_con,$sql_update_order_status) or die(mysqli_error($db_con));
			if($result_update_order_status)		
			{
				$sql_get_orders_details 	= " SELECT `cart_id`,`cart_orderid`, `cart_status` ,`orstat_id`, `orstat_name`, `orstat_sort_order`  FROM `tbl_cart` tc ";
				$sql_get_orders_details 	.= " INNER JOIN `tbl_order_status` tos ON tc.cart_status = tos.orstat_id where cart_orderid = '".$ord_id."' and ";
				$sql_get_orders_details 	.= " orstat_sort_order <= (SELECT `orstat_sort_order` FROM `tbl_order_status` WHERE `orstat_id` = '".$new_status."') order by orstat_sort_order ";
				$result_get_orders_details 	= mysqli_query($db_con,$sql_get_orders_details) or die(mysqli_error($db_con));
				while($row_get_orders_details = mysqli_fetch_array($result_get_orders_details))
				{
					$sql_update_child_order 	= "  UPDATE `tbl_cart` SET `cart_status`= '".$new_status."',`cart_modified`='".$datetime."' WHERE `cart_id` = '".$row_get_orders_details['cart_id']."'  ";
					$result_update_child_order 	= mysqli_query($db_con,$sql_update_child_order) or die(mysqli_error($db_con));
				}
				$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");
			}
			else
			{
				$response_array = array("Success"=>"Fail","resp"=>"Something went wrong please try after some time...");			
			}
		}
		else
		{
			$response_array = array("Success"=>"Fail","resp"=>"Something went wrong please try after some time...");
		}		
	}
	elseif($type == 1) // changing sub orders i.e. cart product status
	{
		$cart_id 	= trim(mysqli_real_escape_string($db_con,$obj->type_id));
		if($cart_id != "")
		{
			$sql_update_sub_order_status 	= " UPDATE `tbl_cart` SET `cart_status`= '".$new_status."',`cart_modified`='".$datetime."' WHERE `cart_id` = '".$cart_id."' ";
			$result_update_sub_order_status	= mysqli_query($db_con,$sql_update_sub_order_status) or die(mysqli_error($db_con));
			if($result_update_sub_order_status)		
			{				
				$response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully.");				
			}
			else
			{
				$response_array = array("Success"=>"Fail","resp"=>"Something went wrong please try after some time...");
			}			
		}
		else
		{
			$response_array = array("Success"=>"Fail","resp"=>"Something went wrong please try after some time...");			
		}		
	}
	echo json_encode($response_array);
}
?>