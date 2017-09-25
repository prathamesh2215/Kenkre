<?php
include("include/routines.php");
include("include/db_con.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

//this function is used for showing data
if((isset($obj->coupon_report)) == "1" && isset($obj->coupon_report))
{
	
	$type_times_use   = $obj->type_times_use;
	$response_array = array();

	$sql_get_coupon   =" SELECT * FROM `tbl_coupons` as tc WHERE 1=1 ";
	$sql_get_coupon  .=" AND coup_id IN(SELECT DISTINCT cart_coupon_code FROM tbl_cart WHERE cart_status!=0 )";
	if($type_times_use !="All")
	{
		$sql_get_coupon .=" AND type_times_use ='".$type_times_use."' ";
	}
	
	$res_get_coupon = mysqli_query($db_con,$sql_get_coupon) or die(mysqli_error($db_con));
	$num_get_coupon = mysqli_num_rows($res_get_coupon);
	
	$shipping_charge = 0;
	$discount        = 0;
	$order_total     = 0;
	
	if($num_get_coupon > 0)
	{
		    $coupon_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$coupon_data .= '<thead>';
    	  	$coupon_data .= '<tr>';
         	$coupon_data .= '<th style="text-align:center">Sr No</th>';
			$coupon_data .= '<th style="text-align:center">Coupon Name</th>';
			$coupon_data .= '<th style="text-align:center">Coupon Code</th>';
			$coupon_data .= '<th style="text-align:center">Coupon Type</th>';
			$coupon_data .= '<th style="text-align:center">Type Time Use</th>';
			$coupon_data .= '<th style="text-align:center">Coup Discount</th>';
			$coupon_data .= '<th style="text-align:center">Left Users( Limit/User )</th>';
			$coupon_data .= '<th style="text-align:center">Applied Count</th>';
			$coupon_data .= '<th style="text-align:center">Coupon Status</th>';
			$coupon_data .= '</tr>';
      		$coupon_data .= '</thead>';
      		$coupon_data .= '<tbody>';
			$i=1;
			while($coupon_row = mysqli_fetch_array($res_get_coupon))
			{
				$coupon_data .= '<tr>';
				$coupon_data .= '<td style="text-align:center">'.$i++.'</td>';
				$coupon_data .= '<td style="text-align:center">'.$coupon_row['coup_name'].'</td>';
				$coupon_data .= '<td style="text-align:center">'.$coupon_row['coup_code'].'</td>';
				if($coupon_row['coup_type']=="coupon")
				{
					$coup_type ="Coupon";
				}
				else
				{
					$coup_type ="Gift Card";
				}
				
				
				$coupon_data .= '<td style="text-align:center">'.$coup_type.'</td>';
				$type_times_use= ucwords(str_replace('_',' ',$coupon_row['type_times_use']));
				$coupon_data .= '<td style="text-align:center">'.$type_times_use.'</td>';
				$coupon_data .= '<td style="text-align:center">'.$coupon_row['coup_discount_amount'].' ( '.ucwords($coupon_row['coup_discount_type']).' )</td>';
				$coup_limit_per_user=$coupon_row['coup_limit_per_user'];
				if($coupon_row['coup_limit_per_user']=="")
				{
					$coup_limit_per_user =0;
				}
				if($type_times_use=="Unlimited Use")
				{
					$coupon_data .= '<td style="text-align:center">'.$type_times_use.'</td>';
				}
				else
				{
					$coupon_data .= '<td style="text-align:center">'.$coupon_row['coup_left_users'].' ( '.$coup_limit_per_user.' )</td>';
				}
				
				$sql_get_count  = " SELECT * FROM tbl_cart as tc ";
				$sql_get_count .= " INNER JOIN tbl_customer as tcr ON tc.cart_custid=tcr.cust_id ";
				$sql_get_count .= " WHERE tc.cart_status!=0 AND (tc.cart_coupon_code='".$coupon_row['coup_code']."' ";
				$sql_get_count .= " or tc.cart_coupon_code='".$coupon_row['coup_id']."') GROUP BY cart_custid ";
				
				$res            = mysqli_query($db_con,$sql_get_count) or die(mysqli_error($db_con));
				
				$coupon_data .= '<td style="text-align:center">
				                 <input type="button" class="btn-link"  value="'.mysqli_num_rows($res).'" onclick="viewUsers('.$coupon_row['coup_id'].')">
				                </td>';
				
				if($coupon_row['coup_status'] ==1)
				{
					$staus ='<span style="color:green"> Active </span>';
				}
				else
				{
					$staus ='<span style="color:red"> Inactive </span>';
				}
				
				$coupon_data .= '<td style="text-align:center">'.$staus.'</td>';
				$coupon_data .= '</tr>';
			}
			 	
			$coupon_data .= '</tbody>';
			$coupon_data .= '</table>';
			
	  	    $response_array = array("Success"=>"Success","resp"=>$coupon_data);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Data available");
	}
	
	echo json_encode($response_array);
	
}


if((isset($obj->exportToXlsx)) == "1" && isset($obj->exportToXlsx))
{
	$type_times_use   = $obj->type_times_use;
	$response_array = array();

	$sql_get_coupon   =" SELECT * FROM `tbl_coupons` as tc  WHERE 1=1 ";
	$sql_get_coupon  .=" AND coup_id IN(SELECT DISTINCT cart_coupon_code FROM tbl_cart WHERE cart_status!=0 )";
	$sql_get_coupon  .=" ";
	if($type_times_use !="All")
	{
		$sql_get_coupon .=" AND type_times_use ='".$type_times_use."' ";
	}
	$response_array =   exportToXlsx($sql_get_coupon);  
	echo json_encode($response_array);
}


function exportToXlsx($sql_get_coupon)
{
	include_once("xlsxwriter.class.php");
	global 			$db_con;
	$header			= array();
	$data1 			= array();
	$main_data		= array();
	
	$res_get_report	= mysqli_query($db_con,$sql_get_coupon) or die(mysqli_error($db_con));
	$num_get_report	= mysqli_num_rows($res_get_report);
	
	if($num_get_report != 0)
	{
		$header = array(
			'Sr NO'=>'integer',	
			'Coupon Name'=>'string',	
			'Coupon Code'=>'string',						
			'Coupon Type'=>'string',			
			'Type Time Use'=>'string',			
			'Coupon Discount'=>'string',
			'Left Users(limit/user)'=>'string',			
			'Applied Count '=>'string',
			'Status'=>'string'
	    	);
			$i=1;
		while($coupon_row = mysqli_fetch_array($res_get_report))
		{	
			    $sr_no 		= $i++;
				$name  	    = $coupon_row['coup_name'];
				$code  		= $coupon_row['coup_code'];
				$coup_type  = ucwords(str_replace('_',' ',$coupon_row['coup_type']));
				$type_times_use= ucwords(str_replace('_',' ',$coupon_row['type_times_use']));
				$discount   = $coupon_row['coup_discount_amount'].' ( '.ucwords($coupon_row['coup_discount_type']).' )';
				
				$coup_limit_per_user=$coupon_row['coup_limit_per_user'];
				if($coupon_row['coup_limit_per_user']=="")
				{
					$coup_limit_per_user =0;
				}
				
				if($type_times_use=="Unlimited Use")
				{
					$left_users =$type_times_use;
				}
				else
				{
					$left_users = $coupon_row['coup_left_users'].' ( '.$coup_limit_per_user.' )';
				}
				
				$sql_get_count  = " SELECT * FROM tbl_cart as tc ";
				$sql_get_count .= " INNER JOIN tbl_customer as tcr ON tc.cart_custid=tcr.cust_id ";
				$sql_get_count .= " WHERE tc.cart_status!=0 AND (tc.cart_coupon_code='".$coupon_row['coup_code']."' ";
				$sql_get_count .= " or tc.cart_coupon_code='".$coupon_row['coup_id']."') GROUP BY cart_custid ";
				$res            =mysqli_query($db_con,$sql_get_count) or die(mysqli_error($db_con));
				$applied_count  = mysqli_num_rows($res);
				
				if($coupon_row['coup_status'] == 1)
				{
					$status ='Active';
				}
				else
				{
					$status ='Inactive';
				}
				
			$data1 	= array($sr_no,$name,$code,$coup_type,$type_times_use,$discount,$left_users,$applied_count,$status);
			array_push($main_data, $data1);
		}
		$writer 			= new XLSXWriter();
		$writer->setAuthor('Punit Panchal');
		$writer->writeSheet($main_data,'Sheet1',$header);
		$timestamp			= date('mdYhis', time());
		$writer->writeToFile('couponReport/coup_sheet_'.$timestamp.'.xlsx');
		
		$response_array	= array("Success"=>"Success", "resp"=>'couponReport/coup_sheet_'.$timestamp.'.xlsx');
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"No Data");	
	}
	return $response_array;
}


if((isset($obj->getCouponUsers)) == "1" && isset($obj->getCouponUsers))
{
	$coup_id        = $obj->coup_id;
	
	$sql_get_coup   = " SELECT * FROM tbl_coupons WHERE (coup_id='".$coup_id."' or coup_code='".$coup_id."')";
	$res_get_coup   = mysqli_query($db_con,$sql_get_coup) or die(mysqli_error($db_con));
	$row_get_coup   = mysqli_fetch_array($res_get_coup);
	$coup_id        = $row_get_coup['coup_id'];
	$coup_code      = $row_get_coup['coup_code'];
	
	$response_array = array();
	$coupon_data    = '';
    
	$sql_get_users  = " SELECT * FROM tbl_cart as tc ";
	$sql_get_users .= " INNER JOIN tbl_customer as tcr ON tc.cart_custid=tcr.cust_id ";
	$sql_get_users .= " WHERE tc.cart_status!=0 AND (tc.cart_coupon_code='".$coup_id."' ";
	$sql_get_users .= " or tc.cart_coupon_code='".$coup_code."')  GROUP BY cart_custid ORDER BY cart_id DESC";
	$res_get_users  = mysqli_query($db_con,$sql_get_users) or die(mysqli_error($db_con));
	$num_get_users  = mysqli_num_rows($res_get_users);
	
	
	if($num_get_users !=0)
	{
		$coupon_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
		$coupon_data .= '<thead>';
		$coupon_data .= '<tr>';
		$coupon_data .= '<th style="text-align:center">Sr No</th>';
		$coupon_data .= '<th style="text-align:center">Customer Name</th>';
		$coupon_data .= '<th style="text-align:center">Date</th>';
		$coupon_data .= '</tr>';
		$coupon_data .= '</thead>';
		$coupon_data .= '<tbody>';
		$i=1;
		while($coupon_row = mysqli_fetch_array($res_get_users))
		{
			$coupon_data .= '<tr>';
			$coupon_data .= '<td style="text-align:center">'.$i++.'</td>';
			$coupon_data .= '<td style="text-align:center">'.ucwords($coupon_row['cust_fname']).' '.ucwords($coupon_row['cust_lname']).'</td>';
		
			if($coupon_row['coup_type']=="coupon")
			{
				$coup_type ="Coupon";
			}
			else
			{
				$coup_type ="Gift Card";
			}
			$coupon_data .= '<td style="text-align:center">'.$coupon_row['cart_modified'].'</td>';
			$coupon_data .= '</tr>';
			
		}
		 $coupon_data .= '</tbody>';
		$coupon_data .= '</table>';
		$response_array = array("Success"=>"Success","resp"=>$coupon_data);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>'No Data Available...!');
	}
	
	echo json_encode($response_array);
}

?>