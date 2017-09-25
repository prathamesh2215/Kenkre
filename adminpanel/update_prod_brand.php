
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php

 

include("include/routines.php");

$uid 	= $_SESSION['panel_user']['id'];
$utype	= $_SESSION['panel_user']['utype'];
$tbl_users_owner = $_SESSION['panel_user']['tbl_users_owner'];


if(isset($_POST['submit']))
{
	$date 					= date_create();
	$path 					= 'uploadedExcel/Google_Product_Category/'.date_format($date, 'U');
	mkdir($path);	
	$sourcePath 			= $_FILES['file']['tmp_name'];   // Storing source path of the file in a variable
	$inputFileName 			= $path."/".$_FILES['file']['name']; // Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;

	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
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
			$prod_id						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"]));							
			
			
			$sql ="UPDATE tbl_products_master SET prod_brandid ='238' WHERE prod_id ='".$prod_id."'";	
			$res=mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		    
		}
	}		
		echo $i;
}

if(isset($_POST['cart_submit']))
{
$sql_get_cart =" SELECT * FROM tbl_cart WHERE cart_status =0 ";
 $result       =mysqli_query($db_con,$sql_get_cart) or die($db_con);
 $i=1;
 while($row = mysqli_fetch_array($result))
 {
	 $sql_get_prod =" SELECT prod_recommended_price,prod_id FROM tbl_products_master WHERE prod_id ='".$row['cart_prodid']."'";
	 $prod_result  = mysqli_query($db_con,$sql_get_prod) or die(mysqli_error($db_con));
	 $prod_row     = mysqli_fetch_array($prod_result);
	 $cart_unit    = $prod_row['prod_recommended_price'];
	 $cart_price    = $prod_row['prod_recommended_price'] * $row['cart_prodquantity'];
	 
	$sql_update  = " UPDATE tbl_cart SET cart_unit ='".$cart_unit."' ,";
	$sql_update .= "  					cart_price ='".$cart_price."'";
	$sql_update .= "    WHERE  cart_id = '".$row['cart_id']."'";
	$res_update  = mysqli_query($db_con,$sql_update) or die(mysqli_error($db_con)); 
	 echo $i++;
 }
}

if(isset($_POST['isbn_submit']))
{
	$date 					= date_create();
	$path 					= 'uploadedExcel/Google_Product_Category/'.date_format($date, 'U');
	mkdir($path);	
	$sourcePath 			= $_FILES['isbn_file']['tmp_name'];   // Storing source path of the file in a variable
	$inputFileName 			= $path."/".$_FILES['isbn_file']['name']; // Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;

	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
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
	$prod_ids = array();
	
	if($inputFileName !="")
	{
		
		for($i=2;$i<=$arrayCount;$i++)
		{   
			 $isbn						    = trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"]));
			 
			 							
			$list_price						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["B"]));
			$rec_price						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["C"]));
			$quantity						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["D"]));
			
			if($isbn!="")
			{
				$sql = "SELECT * FROM tbl_products_specifications as tps WHERE prod_spec_specid = '4' AND prod_spec_value='".$isbn."'";
				$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
				while($row = mysqli_fetch_array($res))
				{
					$sql_update  = " UPDATE tbl_products_master SET prod_list_price ='".$list_price."' ,";
					$sql_update .= "  							    prod_recommended_price ='".$rec_price."' ,";
					$sql_update .= "  							    prod_quantity ='".$quantity."' ";
					$sql_update .= "    WHERE  prod_id = '".$row['prod_spec_prodid']."'";
					$res_update  = mysqli_query($db_con,$sql_update) or die(mysqli_error($db_con));
					$no_of_records++;
					array_push($prod_ids,$row['prod_spec_prodid']);
				}
			}
		}
	}		
	print_r($prod_ids);
		echo $no_of_records;
}

if(isset($_POST['spec_submit']))
{
	$date 					= date_create();
	$path 					= 'uploadedExcel/Google_Product_Category/'.date_format($date, 'U');
	mkdir($path);	
	$sourcePath 			= $_FILES['file']['tmp_name'];   // Storing source path of the file in a variable
	$inputFileName 			= $path."/".$_FILES['file']['name']; // Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;

	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
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
			$prod_id						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"]));							
			
			
			$sql ="UPDATE tbl_products_master SET prod_brandid ='238' WHERE prod_id ='".$prod_id."'";	
			$res=mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		    
		}
	}		
		echo $i;
}


if(isset($_POST['isbn_qty_update']))
{ 
	$date 					= date_create(); 
	$path 					= 'uploadedExcel/isbn_update/'.date_format($date, 'U');
	mkdir($path);	
	$sourcePath 			= $_FILES['isbn_qty_file']['tmp_name'];   // Storing source path of the file in a variable
	$inputFileName 			= $path."/".$_FILES['isbn_qty_file']['name']; // Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;

	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
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
	$prod_ids = array();
	
	if($inputFileName !="")
	{
		
		for($i=2;$i<=$arrayCount;$i++)
		{   
			 $isbn						    = trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"]));
			 $quantity						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["B"]));
			
			if($isbn!="")
			{
				$sql = "SELECT * FROM tbl_products_specifications as tps WHERE prod_spec_specid = '4' AND prod_spec_value='".$isbn."'";
				$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
				while($row = mysqli_fetch_array($res))
				{
					$sql_update  = " UPDATE tbl_products_master SET prod_quantity ='".$quantity."' ";
					$sql_update .= "    WHERE  prod_id = '".$row['prod_spec_prodid']."'";
					$res_update  = mysqli_query($db_con,$sql_update) or die(mysqli_error($db_con));
					$no_of_records++;
					array_push($prod_ids,$row['prod_spec_prodid']);
				}
			}
		}
	}		
	print_r($prod_ids);
	echo $no_of_records;
}

 ?>
 <form action="update_prod_brand.php" method="post"  enctype="multipart/form-data">
 <input type="file" name="file" />
 <input type="submit" name="submit" value="submit" />
 </form>
 
 
 <br /><br />
 <hr />
 
 
 <form action="update_prod_brand.php" method="post"  enctype="multipart/form-data">
 <input type="file" name="isbn_file" />
 <input type="submit" name="isbn_submit" value="ISBN Update" /> Update Price Using ISBN
 </form>
 
 <br /><br />
 <hr />
 
 <form action="update_prod_brand.php" method="post"  enctype="multipart/form-data">
 <input type="file" name="file" />
 <input type="submit" name="spec_submit" value="submit" /> Spec Submit
 </form>
 
 
 <br /><br />
 <hr />
 
 <form action="update_prod_brand.php" method="post"  enctype="multipart/form-data">
 <input type="file" name="isbn_qty_file" />
 <input type="submit" name="isbn_qty_update" value="submit" /> Update Qty Using ISBN
 </form>
 </body>
</html>