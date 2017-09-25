<?php
include("include/routines.php");
include("include/db_con.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

//this function is used for showing data
if((isset($obj->purchase_report)) == "1" && isset($obj->purchase_report))
{
	
	$org_id         = $obj->org_id;
	$brand_id       = $obj->brand_id;
	$start_date     = $obj->start_date;
	$end_date       = $obj->end_date;
	$response_array = array();

	$sql_get_products   =" SELECT * FROM tbl_cart as tc ";
	$sql_get_products  .=" INNER JOIN tbl_products_master as tpm ON tc.cart_prodid = tpm.prod_id ";
	$sql_get_products  .=" INNER JOIN tbl_oraganisation_master as tom ON tpm.prod_orgid = tom.org_id ";
	$sql_get_products  .=" INNER JOIN tbl_brands_master as tbm ON tpm.prod_brandid = tbm.brand_id ";
	$sql_get_products  .=" WHERE 1=1 AND cart_status IN (7)";
	
	if($start_date !="")
	{
		$start_date1 = $start_date.' 00:00:00';
		$sql_get_products  .=" AND tc.cart_modified >='".$start_date1."' ";
	}
	
	if($end_date !="")
	{
		$end_date1  = $end_date.' 23:59:59';
		$sql_get_products  .=" AND tc.cart_modified <='".$end_date1."' ";
	}
	
	if($org_id!="")
	{
		$sql_get_products  .=" AND tom.org_id='".$org_id."'";
	}
	
	if($brand_id!="")
	{
		$sql_get_products  .=" AND tbm.brand_id='".$brand_id."'";
	}
	
	$res_get_coupon = mysqli_query($db_con,$sql_get_products) or die(mysqli_error($db_con));
	$num_get_coupon = mysqli_num_rows($res_get_coupon);
	
	$shipping_charge = 0;
	$discount        = 0;
	$order_total     = 0;
	
	if($num_get_coupon > 0)
	{
		    $product_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$product_data .= '<thead>';
    	  	$product_data .= '<tr>';
         	$product_data .= '<th style="text-align:center;width:10%">Sr No</th>';
			$product_data .= '<th style="text-align:center;width:10%">Product Id</th>';
			$product_data .= '<th style="text-align:center;width:30%">Product Name</th>';
			$product_data .= '<th style="text-align:center;width:20%">Organisation</th>';
			$product_data .= '<th style="text-align:center;width:20%">Brand</th>';
			$product_data .= '<th style="text-align:center;width:10%">Purchase Count</th>';
			$product_data .= '</tr>';
      		$product_data .= '</thead>';
      		$product_data .= '<tbody>';
			$i=1;
			while($product_row = mysqli_fetch_array($res_get_coupon))
			{
				$product_data .= '<tr>';
				$product_data .= '<td style="text-align:center">'.$i++.'</td>';
				$product_data .= '<td style="text-align:center">'.ucwords($product_row['prod_id']).'</td>';
				$product_data .= '<td style="text-align:center">'.ucwords($product_row['prod_name']).'</td>';
			    $product_data .= '<td style="text-align:center">'.ucwords($product_row['org_name']).'</td>';
				
				$product_data  .= '<td style="text-align:center">'.ucwords($product_row['brand_name']).'</td>';
				$sql_get_count  = " SELECT sum(cart_prodquantity) FROM tbl_cart WHERE cart_status=5 AND cart_prodid='".$product_row['prod_id']."'";
				if($start_date !="")
				{
					$start_date2 = $start_date.' 00:00:00';
					$sql_get_count  .=" AND cart_modified >='".$start_date2."' ";
				}
				
				if($end_date !="")
				{
					$end_date2       = $end_date.' 23:59:59';
					$sql_get_count  .=" AND cart_modified <='".$end_date2."' ";
				}
		        $res            = mysqli_query($db_con,$sql_get_count) or die(mysqli_error($db_con));
				
				$product_data .= '<td style="text-align:center">
				                 <input type="button" class="btn-link"  value="'.mysqli_num_rows($res).'" >
				                </td>';
				$product_data .= '</tr>';
			}
			 	
			$product_data .= '</tbody>';
			$product_data .= '</table>';
			
	  	    $response_array = array("Success"=>"Success","resp"=>$product_data);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Data available");
	}
	
	echo json_encode($response_array);
	
}


if((isset($obj->download_report)) == "1" && isset($obj->download_report))
{
	$org_id         = $obj->org_id;
	$brand_id       = $obj->brand_id;
	$start_date     = $obj->start_date;
	$end_date       = $obj->end_date;
	$response_array = array();

	$sql_get_products   =" SELECT * FROM tbl_cart as tc ";
	$sql_get_products  .=" INNER JOIN tbl_products_master as tpm ON tc.cart_prodid = tpm.prod_id ";
	$sql_get_products  .=" INNER JOIN tbl_oraganisation_master as tom ON tpm.prod_orgid = tom.org_id ";
	$sql_get_products  .=" INNER JOIN tbl_brands_master as tbm ON tpm.prod_brandid = tbm.brand_id ";
	$sql_get_products  .=" WHERE 1=1 AND cart_status IN (7)";
	
	if($start_date !="")
	{
		$start_date1 = $start_date.' 00:00:00';
		$sql_get_products  .=" AND tc.cart_modified >='".$start_date1."' ";
	}
	
	if($end_date !="")
	{
		$end_date1  = $end_date.' 23:59:59';
		$sql_get_products  .=" AND tc.cart_modified <='".$end_date1."' ";
	}
	
	if($org_id!="")
	{
		$sql_get_products  .=" AND tom.org_id='".$org_id."'";
	}
	
	if($brand_id!="")
	{
		$sql_get_products  .=" AND tbm.brand_id='".$brand_id."'";
	}
	$response_array =   exportToXlsx($sql_get_products,$start_date,$end_date);  
	echo json_encode($response_array);
}


function exportToXlsx($sql_get_products,$start_date,$end_date)
{
	include_once("xlsxwriter.class.php");
	global 			$db_con;
	$header			= array();
	$data1 			= array();
	$main_data		= array();
	
	$res_get_report	= mysqli_query($db_con,$sql_get_products) or die(mysqli_error($db_con));
	$num_get_report	= mysqli_num_rows($res_get_report);
	
	if($num_get_report != 0)
	{
		$header = array(
			'Sr NO'=>'integer',	
			'Product Id'=>'string',	
			'Product Name'=>'string',						
			'Organisation'=>'string',			
			'Brand'=>'string',			
			'Purchase Count'=>'string',
			);
			$i=1;
		while($product_row = mysqli_fetch_array($res_get_report))
		{	
			$product_id    = ucwords($product_row['prod_id']);
			$product_name  = ucwords($product_row['prod_name']);
			$org_name      = ucwords($product_row['org_name']);
			
			$brand_name    = ucwords($product_row['brand_name']);
			$sql_get_count  = " SELECT sum(cart_prodquantity) FROM tbl_cart WHERE cart_status=5 AND cart_prodid='".$product_row['prod_id']."'";
			if($start_date !="")
			{
				$start_date2 = $start_date.' 00:00:00';
				$sql_get_count  .=" AND cart_modified >='".$start_date2."' ";
			}
			
			if($end_date !="")
			{
				$end_date2       = $end_date.' 23:59:59';
				$sql_get_count  .=" AND cart_modified <='".$end_date2."' ";
			}
			
			$res            = mysqli_query($db_con,$sql_get_count) or die(mysqli_error($db_con));
			
			$purchase_count =mysqli_num_rows($res);
		
			$data1 	= array($i++,$product_id,$product_name,$org_name,$brand_name,$purchase_count);
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
	$product_data    = '';
    
	$sql_get_users  = " SELECT * FROM tbl_cart as tc ";
	$sql_get_users .= " INNER JOIN tbl_customer as tcr ON tc.cart_custid=tcr.cust_id ";
	$sql_get_users .= " WHERE tc.cart_status!=0 AND (tc.cart_coupon_code='".$coup_id."' ";
	$sql_get_users .= " or tc.cart_coupon_code='".$coup_code."')  GROUP BY cart_custid ORDER BY cart_id DESC";
	$res_get_users  = mysqli_query($db_con,$sql_get_users) or die(mysqli_error($db_con));
	$num_get_users  = mysqli_num_rows($res_get_users);
	
	
	if($num_get_users !=0)
	{
		$product_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
		$product_data .= '<thead>';
		$product_data .= '<tr>';
		$product_data .= '<th style="text-align:center">Sr No</th>';
		$product_data .= '<th style="text-align:center">Customer Name</th>';
		$product_data .= '<th style="text-align:center">Date</th>';
		$product_data .= '</tr>';
		$product_data .= '</thead>';
		$product_data .= '<tbody>';
		$i=1;
		while($product_row = mysqli_fetch_array($res_get_users))
		{
			$product_data .= '<tr>';
			$product_data .= '<td style="text-align:center">'.$i++.'</td>';
			$product_data .= '<td style="text-align:center">'.ucwords($product_row['cust_fname']).' '.ucwords($product_row['cust_lname']).'</td>';
		
			if($product_row['coup_type']=="coupon")
			{
				$coup_type ="Coupon";
			}
			else
			{
				$coup_type ="Gift Card";
			}
			$product_data .= '<td style="text-align:center">'.$product_row['cart_modified'].'</td>';
			$product_data .= '</tr>';
			
		}
		 $product_data .= '</tbody>';
		$product_data .= '</table>';
		$response_array = array("Success"=>"Success","resp"=>$product_data);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>'No Data Available...!');
	}
	
	echo json_encode($response_array);
}

?>