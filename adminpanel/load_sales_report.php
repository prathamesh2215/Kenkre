<?php
include("include/routines.php");
include("include/db_con.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

//this function is used for showing data
if((isset($obj->sales_report)) == "1" && isset($obj->sales_report))
{
	$start_date     = $obj->start_date;
	$end_date       = $obj->end_date;
	$payment_mode   = $obj->payment_mode;
	$payment_type   = $obj->payment_type;
	$response_array = array();
	
	$order_data ='';
	
	$sql_get_order   =" SELECT *FROM `tbl_order` as tos INNER JOIN tbl_customer as tc ON  tos.ord_custid = tc.cust_id WHERE 1=1 ";
	if($start_date!="")
	{
		$start_date     =$start_date.' 00:00:00';
		$sql_get_order  .=" AND `ord_created` >='".$start_date."' ";
	}
	if($end_date!="")
	{
		$end_date       =$end_date.' 23:59:59';
		$sql_get_order  .=" AND `ord_created` <='".$end_date."' ";
	}
	$sql_get_order  .=" AND ord_pay_status IN(7)";
					
					// 0 = not ordered
					// 8 = cancelled
					// 9 = payment pending
					
	if($payment_mode=="Pay Online")
	{
		$sql_get_order   .=" AND ord_pay_type='".$payment_mode."' ";
	}
	if($payment_mode=="COD")
	{
		$sql_get_order   .=" AND ord_pay_type !='Pay Online' ";
	}
	if($payment_type!="All" && $payment_mode=="Pay Online")
	{
		$sql_get_order   .=" AND ord_pay_online_mode='".$payment_type."' ";
	}
	
	//echo $sql_get_order;			
	$res_get_order = mysqli_query($db_con,$sql_get_order) or die(mysqli_error($db_con));
	$num_get_order = mysqli_num_rows($res_get_order);
	
	$shipping_charge = 0;
	$discount        = 0;
	$order_total     = 0;
	
	if($num_get_order > 0)
	{
		    $order_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$order_data .= '<thead>';
    	  	$order_data .= '<tr>';
         	$order_data .= '<th style="text-align:center">Sr No.</th>';
			$order_data .= '<th style="text-align:center">Order No.</th>';
			$order_data .= '<th style="text-align:center">Customer Name</th>';
			$order_data .= '<th style="text-align:center">Order Discount</th>';
			$order_data .= '<th style="text-align:center">Shipping Charges</th>';
			$order_data .= '<th style="text-align:center">Order Total</th>';
			$order_data .= '<th style="text-align:center">Payment Type</th>';
			$order_data .= '<th style="text-align:center">Payment Success Date</th>';
			$order_data .= '</tr>';
      		$order_data .= '</thead>';
      		$order_data .= '<tbody>';
			$i=1;
			while($order_row = mysqli_fetch_array($res_get_order))
			{
				$order_data .= '<tr>';
				$order_data .= '<td style="text-align:center">'.$i++.'</td>';
				$order_data .= '<td style="text-align:center">'.$order_row['ord_id_show'].'</td>';
				$order_data .= '<td style="text-align:center">'.ucwords($order_row['cust_fname']).' '.ucwords($order_row['cust_lname']).'</td>';
				$order_data .= '<td style="text-align:center">'.$order_row['ord_discount'].'</td>';
				$order_data .= '<td style="text-align:center">'.$order_row['ord_shipping_charges'].'</td>';
				$order_data .= '<td style="text-align:center">'.$order_row['ord_total'].'</td>';
				$order_data .= '<td style="text-align:center">';
				$order_data .= $order_row['ord_pay_online_mode'];
				if($order_row['ord_pay_online_mode']=="")
				{
					$order_data .= $order_row['ord_pay_type'];
				}
				$order_data .='</td>';
				$order_data .= '<td style="text-align:center">'.$order_row['ord_created'].'</td>';
				$order_data .= '</tr>';
				$shipping_charge += $order_row['ord_discount'];
				$discount        += $order_row['ord_shipping_charges'];
				$order_total     += $order_row['ord_total'];
			}
			    $order_data .= '<tr>';
				$order_data .= '<th colspan="3" style="text-align:center">Total</th>';
				$order_data .= '<th style="text-align:center">'.$shipping_charge.'</th>';
				$order_data .= '<th style="text-align:center">'.$discount.'</th>';
				$order_data .= '<th style="text-align:center">'.$order_total.'</th>';
				$order_data .= '<th style="text-align:center"></th>';
				$order_data .= '<th style="text-align:center"></th>';
				$order_data .= '</tr>';
				
			$order_data .= '</tbody>';
			$order_data .= '</table>';
			
	  	    $response_array = array("Success"=>"Success","resp"=>$order_data);
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Data available");
	}
	echo json_encode($response_array);
	
}


if((isset($obj->exportToXlsx)) == "1" && isset($obj->exportToXlsx))
{
	
    $start_date     = $obj->start_date;
	$end_date       = $obj->end_date;
	$payment_mode   = $obj->payment_mode;
	$payment_type   = $obj->payment_type;
	
	$sql_get_order   =" SELECT *FROM `tbl_order` as tos INNER JOIN tbl_customer as tc ON  tos.ord_custid = tc.cust_id WHERE 1=1 ";
	if($start_date!="")
	{
		$start_date     =$start_date.' 00:00:00';
		$sql_get_order  .=" AND `ord_created` >='".$start_date."' ";
	}
	if($end_date!="")
	{
		$end_date       =$end_date.' 23:59:59';
		$sql_get_order  .=" AND `ord_created` <='".$end_date."' ";
	}
	$sql_get_order  .=" AND ord_pay_status IN(7)";
	
	if($payment_mode=="Pay Online")
	{
		$sql_get_order   .=" AND ord_pay_type='".$payment_mode."' ";
	}
	if($payment_mode=="COD")
	{
		$sql_get_order   .=" AND ord_pay_type !='Pay Online' ";
	}
	if($payment_type!="All" && $payment_mode=="Pay Online")
	{
		$sql_get_order   .=" AND ord_pay_online_mode='".$payment_type."' ";
	}
					
	$response_array =   exportToXlsx($sql_get_order);  
	echo json_encode($response_array);

}

function exportToXlsx($sql_get_order)
{
	include_once("xlsxwriter.class.php");
	
	global 			$db_con;
	$header			= array();
	$data1 			= array();
	$main_data		= array();
	
	$res_get_report	= mysqli_query($db_con,$sql_get_order) or die(mysqli_error($db_con));
	$num_get_report	= mysqli_num_rows($res_get_report);
	
	if($num_get_report != 0)
	{
		$header = array(
			'Sr NO'=>'integer',	
			'Order No.'=>'string',	
			'Customer Name'=>'string',						
			'Discount'=>'integer',			
			'Shipping Charges'=>'integer',			
			'Order Total'=>'integer',
			'Payment Type'=>'string',			
			'Payment Date'=>'string'
	    	);
			$i=1;
		while($order_row = mysqli_fetch_array($res_get_report))
		{	
			    $sr_no = $i++;
				$order_no   = $order_row['ord_id_show'];
				$cust_name  = ucwords($order_row['cust_fname']).' '.ucwords($order_row['cust_lname']);
				$discount  = $order_row['ord_discount'];
				$shipping =$order_row['ord_shipping_charges'];
				$total = $order_row['ord_total'];
				$payment_mode = $order_row['ord_pay_online_mode'];
				if($payment_mode=="")
				{
					$payment_mode = $order_row['ord_pay_type'];
				}
				$payment_date = $order_row['ord_created'];
				
				$shipping_charge += $order_row['ord_shipping_charges'];
				$total_discount  += $order_row['ord_discount'];
				$order_total     += $order_row['ord_total'];
			
			$data1 	= array($sr_no,$order_no,$cust_name,$discount,$shipping,$total,$payment_mode,$payment_date);
			array_push($main_data, $data1);
		}
		
		$data1 	= array("","","Total",$total_discount,$shipping_charge,$order_total,"","");
		array_push($main_data, $data1);
		
		
		
		$writer 			= new XLSXWriter();
		$writer->setAuthor('Punit Panchal');
		$writer->writeSheet($main_data,'Sheet1',$header);
		$timestamp			= date('mdYhis', time());
		$writer->writeToFile('salesReport/sales_sheet_'.$timestamp.'.xlsx');
		
		$response_array	= array("Success"=>"Success", "resp"=>'salesReport/sales_sheet_'.$timestamp.'.xlsx');
	}
	else
	{
		$response_array	= array("Success"=>"fail", "resp"=>"No Data");	
	}
	return $response_array;
}

?>