
<?php // created by satish on 8 nov 2016
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
$uid	= $_SESSION['panel_user']['id'];
$utype	= $_SESSION['panel_user']['utype'];


if((isset($obj->load_package)) == "1" && isset($obj->load_package))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= $obj->search_text;
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
		
		$sql_load_data  = " SELECT tsp.*,ts.sname,tc.cust_lname,tc.cust_fname ,tf.filt_name,(SELECT cust_fname FROM tbl_customer WHERE cust_id = tsp.stud_package_student_id ) AS fname ,";	
	    $sql_load_data  .=" (SELECT sname FROM tbl_schools WHERE id = tsp.stud_package_school ) AS school_name ";	
		$sql_load_data  .= " FROM `tbl_student_package` as tsp  ";
		$sql_load_data  .=" INNER JOIN tbl_filters as tf ON tsp.stud_package_grade = tf.filt_id ";
		$sql_load_data  .=" INNER JOIN tbl_schools as ts ON tsp.stud_package_school = ts.id ";
		$sql_load_data  .=" INNER JOIN tbl_customer as tc ON tsp.stud_package_student_id = tc.cust_id ";
		$sql_load_data  .="  WHERE 1=1 and stud_package_student_id != 0 ";
		
		
		if($search_text != "")
		{
			$sql_load_data .= " and ( ts.sname like '%".$search_text."%'or cust_fname like '%".$search_text."%' or cust_lname like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY stud_package_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{	
		    $result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));		
			
			$package_data  = "";	
			$package_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$package_data .= '<thead>';
    	  	$package_data .= '<tr>';
         	$package_data .= '<th style="text-align:center">Sr No.</th>';
			$package_data .= '<th style="text-align:center">School  Name</th>';
			$package_data .= '<th style="text-align:center">Customer Name</th>';
			$package_data .= '<th style="text-align:center">Student Name</th>';
			$package_data .= '<th style="text-align:center">Student Grade</th>';
			$package_data .= '<th style="text-align:center">Status</th>';
			$package_data .= '<th style="text-align:center">Action</th>';
			
      		$package_data .= '</thead>';
      		$package_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{   
			$package_data .= '</tr>';
	    	  	$package_data .= '<tr>';				
				$package_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$package_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['school_name']).'"';
				$package_data .=' class="btn-link" id="'.$row_load_data['stud_pkg_info_id'].'" onclick="addMorePkg(this.id,\'view\',1);"></td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['fname'].' '.$row_load_data['lname'].'</td>';
			    $package_data .= '<td style="text-align:center">'.$row_load_data['stud_pkg_stud_name'].'</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['filt_name'].'</td>';
				$package_data .= '<td style="text-align:center">';	
				if($row_load_data['stud_package_student_id'] != 0)
				{
				$package_data .= '<input type="button" value="Verified" ';
				$package_data .= ' class="btn-success" >';
				}
				else
				{
				$package_data .= '<input type="button" value="Not verified" ';
				$package_data .=' class="btn-danger">';
				}
				$package_data .= '</td>';
				$package_data .= '<td style="text-align:center">';
		     	$package_data .= '<input type="button"  value="View Products" onclick="view_products('.$row_load_data['stud_package_student_id'].','.$row_load_data['stud_package_school'].');" class="btn-success"/>';
			    $package_data .= '</td>';
				
	          	$package_data .= '</tr>';	
				
			}	
      		$package_data .= '</tbody>';
      		$package_data .= '</table>';	
			$package_data .= '<script>';	
			//$package_data .= '$(".select2-me").prop("disabled","true");';	
			$package_data .= '</script>';	
			
			$package_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$package_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}


















if((isset($obj->view_products)) == "1" && isset($obj->view_products))
{
	$response_array = array();	
	$start_offset   = 0;
	$cust_id		= $obj->cust_id;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= $obj->search_text;
	$school_id	= $obj->school_id;
	
	$sql_get_product 	 =" SELECT  stud_pkg_products FROM tbl_student_package_info WHERE stud_pkg_sch_id='".$school_id."'";
	$res_get_product	 = mysqli_query($db_con,$sql_get_product) or die(mysqli_error($db_con));
	$row 			  	 = mysqli_fetch_array($res_get_product);
	$existing_product 	 = $row['stud_pkg_products'];
	if($page != "" && $per_page != "" && $existing_product !="")	
	{   
	      
		  
		  
	    
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
		
		$sql_load_data  =" SELECT * ,";
		$sql_load_data  .=" ( SELECT cust_fname FROM tbl_customer WHERE cust_id = '".$cust_id."' ) as fname , ";
		$sql_load_data  .=" ( SELECT cust_lname FROM tbl_customer WHERE cust_id = '".$cust_id."' ) as lname";
		$sql_load_data .=" FROM `tbl_order` AS tbo ";
		$sql_load_data .=" INNER JOIN tbl_cart AS tc ON tbo.ord_id = tc.cart_orderid ";
		$sql_load_data .=" INNER JOIN tbl_products_master AS tpm ON tc.cart_prodid = tpm.prod_id ";
		$sql_load_data .=" 	WHERE ord_pay_status =1 ";
		$sql_load_data .=" 	  AND ord_custid ='".$cust_id."' ";
		$sql_load_data .=" 	  AND cart_prodid IN (".$existing_product.") ";
		
		//if($search_text != "")
	//	{
	//		$sql_load_data .= " and (cam_name like '%".$search_text."%'or tcm.created like '%".$search_text."%' or tcm.modified like '%".$search_text."%') ";	
	//	}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY tbo.ord_id DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{	
		    $result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));		
			
			$package_data  = "";	
			$package_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$package_data .= '<thead>';
    	  	$package_data .= '<tr>';
         	$package_data .= '<th style="text-align:center">Sr No.</th>';
			//$package_data .= '<th style="text-align:center">School  Name</th>';
			$package_data .= '<th style="width:6%;text-align:center">Customer Name</th>';
			$package_data .= '<th style="text-align:center">Order ID</th>';
			$package_data .= '<th style="text-align:center">Product Name</th>';
			$package_data .= '<th style="text-align:center">Model Number</th>';
			$package_data .= '<th style="text-align:center">Order Created</th>';
			
			$package_data .= '</tr>';
      		$package_data .= '</thead>';
      		$package_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{   
	    	  	$package_data .= '<tr>';				
				$package_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				//$package_data .= '<td style="text-align:center"><input type="button" value="'.ucwords($row_load_data['school_name']).'"';
				//$package_data .=' class="btn-link" id="'.$row_load_data['stud_pkg_info_id'].'" onclick="addMorePkg(this.id,\'view\',1);"></td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['fname'].' '.$row_load_data['lname'].' ( '.$cust_id.' )</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['ord_id_show'].'</td>';
				$package_data .= '<td style="text-align:center">'.ucwords($row_load_data['prod_name']).'</td>';
				$package_data .= '<td style="text-align:center">'.ucwords($row_load_data['prod_model_number']).'</td>';
				$package_data .= '<td style="text-align:center">'.$row_load_data['ord_created'].'</td>';
				
	          	$package_data .= '</tr>';	
				
			}	
      		$package_data .= '</tbody>';
      		$package_data .= '</table>';	
			$package_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$package_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No products available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}










if(isset($_FILES['file']))
{
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	
	
	move_uploaded_file($sourcePath,$inputFileName) ;
	
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$branch_id 	= 0;
	$msg		= '';
	$insertion_flag	= 0;
	$response_array = array();
	
	try 
	{
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	} 
	catch(Exception $e) 
	{
		die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	
	$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
	//echo json_encode($arrayCount);exit();
	
	if(strcmp($inputFileName, "")!==0)
	{   
	    
		for($i=2;$i<=$arrayCount;$i++)
		{   
		
		    $school_id 				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"])), ENT_HTML5);
			$stud_name 				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["B"])), ENT_HTML5);
			$grade				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["C"])), ENT_HTML5);
			$parent_name 				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["D"])), ENT_HTML5);
			$mobile_no				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"])), ENT_HTML5);
			$start_date				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"])), ENT_HTML5);
			$end_date				= htmlspecialchars(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["G"])), ENT_HTML5);
			
		if($parent_name!='' && $mobile_no!='')
			{
			$reason="";
			$status=0;
			if(!preg_match('/[^a-z\s]/i',$parent_name) && is_numeric($mobile_no) && strlen($mobile_no) >9 && strlen($mobile_no) <11)
			{
				$status = 1;
			}
			if(preg_match('/[^a-z\s]/i',$parent_name))
			{
				$reason .="Parent name contains only letters,";
			}
			
			if(!is_numeric($mobile_no) || strlen($mobile_no)!=10)
			{
				$reason .="Mobile Number contains 10 Digit Number";
			}
			
			
			$sql_insert_package  = " INSERT INTO `tbl_student_package`(`stud_package_parent_name`,stud_pkg_stud_name,stud_package_grade,`stud_package_school`, `stud_package_mobile_no`, ";
			$sql_insert_package .=" `stud_package_start_date`, `stud_package_end_date`, 	stud_package_created_by,stud_package_created,complete_status,reason_for_wrong)";
			$sql_insert_package .= "  VALUES ('".$parent_name."','".$stud_name."','".$grade."','".$school_id."','".$mobile_no."','".$start_date."','".$end_date."','".$uid."','".$datetime."','".$status."','".$reason."') ";
			$res_insert_package 	= mysqli_query($db_con, $sql_insert_package) or die(mysqli_error($db_con));
			if($res_insert_package)
			{
				$insertion_flag = 1;  
			
			}
			
		}
			
		}
		if($insertion_flag == 1)
		{
			$response_array = array("Success"=>"Success","resp"=>"Insertion Successfully");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Insertion Error");			
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Try to upload Different File");
	}
	echo json_encode($response_array);
}


?>