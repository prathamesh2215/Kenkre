<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);

$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

if((isset($obj->send_cart_mail)) == "1" && isset($obj->send_cart_mail))
{
	global $db_con;
	global $datetime;
	$response_array = array();		
	$ar_ind_id 		= $obj->batch;
	
	foreach($ar_ind_id as $cust_id)	
	{
		
		if($cust_id != '')
		{
			$sql_load_data  = " SELECT * ";
			$sql_load_data .= " FROM `tbl_cart` AS ti ";
			$sql_load_data .= "	INNER JOIN `tbl_customer` AS c ON ti.cart_custid = c.cust_id ";
			$sql_load_data .= "	INNER JOIN `tbl_products_master` AS p ON ti.cart_prodid = p.prod_id ";
			$sql_load_data .= "	WHERE 1 =1 AND c.cust_id = '".$cust_id."' ORDER BY cart_id DESC ";
		
			$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
	
	         
	   
		        $row_load_data    = mysqli_fetch_array($result_load_data);
			
			$cust_email	  = $row_load_data['cust_email'];
			$cust_name        = $row_load_data['cust_fname'];
			$cust_mobile      = $row_load_data['cust_mobile_num'];
			$cart_type        = $row_load_data['cart_type'];
                        
			
			$email_text = '<table class="" data-module="main Content" height="347" width="100%" bgcolor="#e2e2e2" border="0" cellpadding="0" cellspacing="0">';
						$email_text .= '<tr>';
							$email_text .= '<td>';
								$email_text	.= '<table data-bgcolor="BG Color" height="347" width="800" align="center" bgcolor="#EDEFF0" border="0" cellpadding="0" cellspacing="0">';
									$email_text	.= '<tr>';
										$email_text	.= '<td>';
											$email_text	.= '<table data-bgcolor="BG Color 01" height="347" width="700" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
												$email_text	.= '<tr>';
													$email_text	.= '<td>';
														$email_text	.= '<table height="347" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
															$email_text	.= '<tr>';
																$email_text	.= '<td data-bgcolor="Line Color" height="1" width="520" bgcolor="#cedcce"></td>';
															$email_text	.= '</tr>';	
															$email_text	.= '<tr>';
																$email_text	.= '<td height="345" width="520">';
																	$email_text	.= '<table height="75" width="520" align="center" border="0" cellpadding="0" cellspacing="0">';
																		/*$email_text	.= '<tr>';
																			$email_text	.= '<td data-color="Title" data-size="Title" data-min="10" data-max="30" class="td-pad10" style="font-weight:bold; letter-spacing: 0.025em; font-size:20px; color:#494949; font-family:\'Open Sans\', sans-serif; mso-line-height-rule: exactly;" align="center"> Response Mail </td>';
																		$email_text	.= '</tr>';	*/
																	$email_text	.= '</table>';
											
																	
															
					//===============================End : Done By Satish 11042017 Mail Content==================//												
																	
																	
																
																	
$email_text	.= '<p>Hi '.ucwords($cust_name).',</p>	';											
							
$email_text	.= '<p>Greetings from Planet Educate ! </p><br>';

$email_text	.= 'We noticed that you left a few item(s) in your cart. We don\'t want you to miss out on them. <br>
Use code \'CART12\' to avail a special discount of 12% just for you on our website.<br><br>';

$email_text	.= '<b>Items in your cart:</b>';

$email_text .='<div style="border-bottom:2px solid #000;border-top:2px solid #000">';
$email_text						.='<style>th, td { border-bottom: 1px solid #ddd;}</style>';
$email_text						.= '<table style="width:100%;">';
$email_text						.= '<tbody>';

$sql_get_cart  = " SELECT  p.*,(SELECT `prod_img_file_name` FROM `tbl_products_images` WHERE `prod_img_prodid` = p.prod_id and prod_img_type = 'main') as prod_img_file_name ,(SELECT `brand_name` FROM `tbl_brands_master` WHERE `brand_id` =p.prod_brandid) as brand_name ";
$sql_get_cart .= " FROM `tbl_cart` AS tc ";
$sql_get_cart .= "	INNER JOIN `tbl_products_master` AS p ON tc.cart_prodid = p.prod_id ";
//$sql_get_cart .= "	INNER JOIN `tbl_brands_master` AS tb ON p.prod_brandid = tb.brand_id ";
$sql_get_cart .= "	WHERE 1 =1 AND tc.cart_custid = '".$cust_id."' AND cart_status = 0 ORDER BY cart_id DESC ";
$res_get_cart  = mysqli_query($db_con,$sql_get_cart) or die(mysqli_error($db_con));
while($row_get_cart = mysqli_fetch_array($res_get_cart))
{
$prod_imagepath 	= "https://www.planeteducate.com/images/planet/org".$row_get_cart['prod_orgid']."/prod_id_".$row_get_cart['prod_id']."/small/".$row_get_cart['prod_img_file_name'];
$product_name       =$row_get_cart['prod_title'];
$email_text					.= '<tr>';

$email_text					.= '<td align="left" valign="top" width="10%">';
$email_text					.= '<img style="padding:15px" src="'.$prod_imagepath.'" alt="'.$product_name.'">';
$email_text					.= '</td>';		
$email_text					.= '<td><span><a style="color:#4bbcd7" href="https://www.planeteducate.com/page-product.php?prod_id='.$row_get_cart['prod_id'].'"  target="_blank">'.$product_name.'</a></span>';
		
$email_text 				.= '</td>';
//$email_text					.= 'By  '.$row_get_cart['brand_name'].'<br><br>';

$email_text					.='<td><b>Rs . '.$row_get_cart['prod_recommended_price'].'</b>';
$email_text					.= '</td>';
$email_text					.= '<tr>';
}
$email_text .='</tbody>';
$email_text .='</table>
</div>';
$email_text  .='Here\'s a <a href="https://www.planeteducate.com/page-checkout" style="text-decoration:underline">link</a> to so you complete your purchase.';
																	
				//===============================End : Done By Satish 11042017 Mail Content==================//														
																	//$email_text	.= '<p>Greetings from Planet Educate.</p>';
																//	$email_text	.= '<br>';
																	//$email_text	.= '<p>Your requested School has been added in our school list.</p>';
																	//$email_text	.= '<p>Please check your dashboard and update your school.</p>';
																	


																	$email_text	.= '<p>If you have any queries, we are here to help!<br></p>';
																	$email_text	.= '<p>Give us a call at 022-61572606 or email us at support@planeteducate.com,</p><br/>';
																	
																	


																	$email_text	.= 'Thank you for stopping by,<br/>';
																	$email_text	.= 'Team Planet Educate!</p>';
																$email_text	.= '</td>';
															$email_text	.= '</tr>';
														$email_text	.= '</table>';
													$email_text	.= '</td>';
												$email_text	.= '</tr>';
											$email_text	.= '</table>';
										$email_text	.= '</td>';
									$email_text	.= '</tr>';
								$email_text	.= '</table>';
							$email_text .= '</td>';
						$email_text .= '</tr>';
					$email_text .= '</table>';
					
		//	echo json_encode(array("Success"=>"fail","resp"=>$email_text));exit();
			
			$to 		= $cust_email;
			$subject 	= "Complete your order now to get 12% off.";
			$message 	= mail_template_header()."".$email_text."".mail_template_footer();

            if(sendEmail($to,$subject,$message))
			{
				$sql_update_email_entry_into_cart_tbl	= " UPDATE `tbl_cart` SET `cart_mail_status` = '1' ";
				$sql_update_email_entry_into_cart_tbl  .= " WHERE cart_id = '".$cart_id."' OR cart_custid = '".$row_load_data['cart_custid']."' ";
				if($cart_type == 'incomplete')
				{
					$sql_update_email_entry_into_cart_tbl	.= " AND cart_type = 'incomplete' ";
				}
				if($cart_type == 'abundant')
				{
					$sql_update_email_entry_into_cart_tbl	.= " AND cart_type = 'abundant' ";
				}
				$res_update_email_entry_into_cart_tbl	= mysqli_query($db_con,$sql_update_email_entry_into_cart_tbl) or die(mysqli_error($db_con));	
				
				if($res_update_email_entry_into_cart_tbl)
				{
					$sql_insert_email_entry_into_noti_tbl	 = " INSERT INTO `tbl_notification`(`type`, ";
					$sql_insert_email_entry_into_noti_tbl	.= " `message`, `user_email`, `user_mobile_num`, ";
					$sql_insert_email_entry_into_noti_tbl	.= " `created_date`) ";
					$sql_insert_email_entry_into_noti_tbl	.= " VALUES ( ";
					if($cart_type == 'incomplete')
					{
						$sql_insert_email_entry_into_noti_tbl	.= "'Response Sent Incomplete', ";
					}
					if($cart_type == 'abundant')
					{
						$sql_insert_email_entry_into_noti_tbl	.= "'Response Sent Abundant', ";
					}
					$sql_insert_email_entry_into_noti_tbl	.= " '".htmlspecialchars($message, ENT_QUOTES)."','".$cust_email."','".$cust_mobile."', ";
					$sql_insert_email_entry_into_noti_tbl	.= " NOW()) ";
					$result_load_data = mysqli_query($db_con,$sql_insert_email_entry_into_noti_tbl) or die(mysqli_error($db_con));	
				}
				$responce_array	= array("Success"=>"Success", "resp"=>"Response Mail Sent Successfully");
			}
			else
			{ 
				$responce_array	= array("Success"=>"Fail", "resp"=>"Error while Sending Your Response...");	
			}
        }
		else
		{
			$responce_array	= array("Success"=>"fail", "resp"=>"Cart ID Not Found");
		}
          }
	  echo json_encode($responce_array);	
}



if((isset($obj->load_cart)) == "1" && isset($obj->load_cart))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= $obj->search_text;
	$cartlist		= $obj->cartlist;	
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;

	$sql_load_data  = " SELECT cart_id, cart_custid, cart_prodid, cart_prodquantity, cart_unit, cart_price, cart_coupon_code,
							cart_discount, cart_type, cart_orderid, cart_status, cart_created, cart_modified, cart_mail_status,
								(SELECT cust_fname FROM `tbl_customer` WHERE cust_id = ti.cart_custid) AS cust_name,
								(SELECT cust_lname FROM `tbl_customer` WHERE cust_id = ti.cart_custid) AS cust_lname,
								(SELECT cust_email FROM `tbl_customer` WHERE cust_id = ti.cart_custid) AS cust_email,  
								(SELECT prod_name FROM `tbl_products_master` WHERE prod_id = ti.cart_prodid) AS prodname 
							FROM `tbl_cart` AS ti ";
		
		if($search_text != "")
		{
			$sql_load_data  .= "INNER JOIN `tbl_customer` AS c ON ti.cart_custid = c.cust_id
					    INNER JOIN `tbl_products_master` AS p ON ti.cart_prodid = p.prod_id ";
		}
		$sql_load_data  .= " WHERE 1=1 AND cart_status = 0 and cart_type != 'complete' and cart_custid REGEXP '^[0-9]+$' ";
		if($search_text != "")
		{	
			$sql_load_data .= " and (c.cust_fname like '%".$search_text."%' ";
			$sql_load_data .= " or p.prod_name like '%".$search_text."%' or ti.cart_id = '".$search_text."') ";	
			
		}
		
		if($cartlist != 'all')
		{
				$sql_load_data	.= " and ti.cart_type='".$cartlist."' ";	
			
		}
		
                $sql_load_data .= " GROUP by cart_custid, cart_type ";
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);	
		$sql_load_data .= " ORDER BY cart_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if($result_load_data)
		{
		if(strcmp($data_count,"0") !== 0)
		{		
			$cart_data = "";	
			$cart_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$cart_data .= '<thead>';
    	  	$cart_data .= '<tr>';
         	$cart_data .= '<th style="text-align:center">Sr No.</th>';
			$cart_data .= '<th style="text-align:center">Cart ID</th>';
			$cart_data .= '<th style="text-align:center">Customer Name</th>';
			$cart_data .= '<th style="text-align:center">Product Name</th>';
			$cart_data .= '<th style="text-align:center">Customer Email</th>';
			$cart_data .= '<th style="text-align:center">Cart Type</th>';
                        
			$cart_data .= '<th style="text-align:center"><div style="text-align:center">';
			$cart_data .= '<input type="button" value="Send Email" onclick="sendCartEmail();" class="btn-success"/><br>';
			
			$cart_data .= '<input type="checkbox" id="parent_chk" class="css-checkbox parent_batch_prod" onchange="childCheckUncheck(this.id,\'batch\');">';
			$cart_data .= '<label for="parent_chk" class="css-label"></label>All</div></th>';
			
		     	$cart_data .= '<th style="text-align:center">Comments</th>';			
			
          	$cart_data .= '</tr>';
      		$cart_data .= '</thead>';
      		$cart_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	        $cart_data .= '<tr>';				
				$cart_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
					$cart_data .= '<td style="text-align:center">'.$row_load_data['cart_id'].'</td>';
					$cart_data .= '<td style="text-align:center">'.$row_load_data['cust_name'].' '.$row_load_data['cust_lname'].'</td>';
					
					$sql   = " SELECT * ,
									(SELECT prod_name FROM `tbl_products_master` WHERE prod_id = ti.cart_prodid) AS prodname 
								FROM `tbl_cart` AS ti WHERE ti.cart_custid = '".$row_load_data['cart_custid']."' AND ";
					$sql  .= " ti.cart_type = '".$row_load_data['cart_type']."' ";
					$result = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));	
					
					$cart_data .='<td>';
					$i = 0;
				while($row_load = mysqli_fetch_array($result))
				{	
                                $i++;
				$cart_data .= $i.')&nbsp;'.$row_load['prodname'].'<br>';
				
				}
				$cart_data .='</td>';
				$cart_data .= '<td style="text-align:center">'.$row_load_data['cust_email'].'</td>';
				$cart_data .= '<td style="text-align:center">'.$row_load_data['cart_type'].'</td>';
					
				$sql_chk_resp_mail	 = " SELECT * FROM `tbl_cart` WHERE cart_mail_status = '0' AND ";
				$sql_chk_resp_mail	.= " cart_custid = '".$row_load_data['cart_custid']."' AND cart_type = '".$row_load_data['cart_type']."' ";
				$res_chk_resp_mail	 = mysqli_query($db_con,$sql_chk_resp_mail) or die(mysqli_error($db_con));
				$num_chk_resp_mail	 = mysqli_num_rows($res_chk_resp_mail);
				if($num_chk_resp_mail < 1)
				{
					$cart_data .= '<td><div class="controls" align="center">';
					$cart_data .= '<span style="color:#18BB7C">Mail Sent</span>';
					$cart_data .= '</div></td>';	
				}
				else
				{
					$cart_data .= '<td><div class="controls" align="center">';
					
					$cart_data .= '<input type="checkbox" value="'.$row_load_data['cart_custid'].'" id="batch'.$row_load_data['cart_custid'].'" name="batch'.$row_load_data['cart_custid'].'" class="css-checkbox batch">';
					$cart_data .= '<label for="batch'.$row_load_data['cart_custid'].'" class="css-label"></label><br>';
					$cart_data .= '<input type="button" value="Send Email" onclick="check_box(\'batch'.$row_load_data['cart_custid'].'\');" class="btn-success"/><br>';
							$cart_data .= '</div></td>';	
				}
 
               
			       $sql_get_comment =" SELECT cust_comment FROM tbl_customer WHERE cust_id ='".$row_load_data['cart_custid']."' ";
				   $res_get_comment =mysqli_query($db_con,$sql_get_comment) or die(mysqli_error($db_con));
				   $row_get_comment =mysqli_fetch_array($res_get_comment);
			   
					$cart_data .= '<td>
					<textarea name="comment_'.$row_load_data['cart_custid'].'" id="comment_'.$row_load_data['cart_custid'].'" onchange="comments('.$row_load_data['cart_custid'].');">'.$row_get_comment['cust_comment'].'</textarea><br>
					</td>';							
				
     
				$cart_data .= '</tr>';															
				}	
      		$cart_data .= '</tbody>';
      		$cart_data .= '</table>';	
			$cart_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$cart_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
		}
		}
		else
			{
				$responce_array = array("Success"=>"fail","resp"=>"Data not available");
			}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}
// ==========================================================================

?>