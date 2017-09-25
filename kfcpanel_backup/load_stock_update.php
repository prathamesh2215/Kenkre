<?php
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//===============================Start : Common Function ==============================================================================
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//////////////////// INSERT INTO DATABASE == Done BY satish : 04052017========//

function insert($table, $variables = array() )
{
        //Make sure the array isn't empty
		global $db_con;
        if( empty( $variables ) )
        {
            return false;
            exit;
        }
        
        $sql = "INSERT INTO ". $table;
        $fields = array();
        $values = array();
        foreach( $variables as $field => $value )
        {
            $fields[] = $field;
            $values[] = "'".$value."'";
        }
        $fields = ' (' . implode(', ', $fields) . ')';
        $values = '('. implode(', ', $values) .')';
        
        $sql .= $fields .' VALUES '. $values;

        $result_check_spec 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
        
        if($result_check_spec)
        {
            return false;
        }
        else
        {
            return true;
        }
}

//////////////////// UPDATE INTO DATABASE == Done BY satish : 04052017========//

function update($table, $variables = array(), $where,$not_where_array=array(),$and_like_array=array(),$or_like_array=array())
{
        //Make sure the array isn't empty
		global $db_con;
        if( empty( $variables ) )
        {
            return false;
            exit;
        }
        
        $sql = "UPDATE ". $table .' SET ';
        $fields = array();
        $values = array();
		
        foreach($variables as $field => $value )
        {   
            $sql  .= $field ."='".$value."' ,";
        }
        $sql   =chop($sql,',');
        
        $sql .=" WHERE 1 = 1 ";
		//==Check Where Condtions=====//
	if(!empty($where))
	{
		foreach($where as $field1 => $value1 )
		{   
			$sql  .= " AND ".$field1 ."='".$value1."' ";
		}
	}
	
	//==Check Not Equal Condtions=====//
	if(!empty($not_where_array))
	{
		foreach($not_where_array as $field2 => $value2 )
		{   
			$sql  .= " AND ".$field2 ."!='".$value2."' ";
		}
	}
	
	//==Check AND Like Condtions=====//
	if(!empty($and_like_array))
	{
		foreach($like_array as $field3 => $value3 )
		{   
			$sql  .= " AND ".$field3 ." like '".$value3."' ";
		}
	}
	//==Check AND Like Condtions=====//
	if(!empty($or_like_array))
	{
		foreach($or_like_array as $field4 => $value4 )
		{   
			$sql  .= " AND ".$field4 ." like '".$value4."' ";
		}
	}

        $result_check_spec 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
        
        if($result_check_spec)
        {
            return true;
        }
        else
        {
            return false;
        }
}

//////////////////// Exit Json Response For Debuggng == Done BY satish : 04052017========//
function quit($msg,$Success="")
{
	if($Success ==1)
	{
		$Success=" Success ";
	}
	else
	{
		$Success=" fail ";
	}
	echo json_encode(array("Success"=>$Success,"resp"=>$msg));
	exit();
}

//////////////////=== Fetch Records From Database ====//======Done By satish 04052017===//
function lookup_value($table,$col_array,$where,$not_where_array,$and_like_array,$or_like_array)
{
	global $db_con;
	$colums  =implode(',',$col_array);
	if($colums=="")
	{
		$colums =' * ';
	}
	$sql =" SELECT ".$colums." FROM ".$table." ";
	$sql .=" WHERE 1 = 1 ";
	//==Check Where Condtions=====//
	if(!empty($where))
	{
		foreach($where as $field1 => $value1 )
		{   
			$sql  .= " AND ".$field1 ."='".$value1."' ";
		}
	}
	
	//==Check Not Equal Condtions=====//
	if(!empty($not_where_array))
	{
		foreach($not_where_array as $field2 => $value2 )
		{   
			$sql  .= " AND ".$field2 ."!='".$value2."' ";
		}
	}
	
	//==Check AND Like Condtions=====//
	if(!empty($and_like_array))
	{
		foreach($like_array as $field3 => $value3 )
		{   
			$sql  .= " AND ".$field3 ." like '".$value3."' ";
		}
	}
	//==Check AND Like Condtions=====//
	if(!empty($or_like_array))
	{
		foreach($or_like_array as $field4 => $value4 )
		{   
			$sql  .= " AND ".$field4 ." like '".$value4."' ";
		}
	}
	$result	   = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	$nums      = mysqli_num_rows($result);
	if($nums !=0)
	{
		if($colums=="")
		{
			return $result;
		}
		else
		{
			$row = mysqli_fetch_array($result);
			return $row[$colums];
		}
	}
	else
	{
		return false;
	}
}

//////////////////=== Check  Records is Exist Or NOT ====//======Done By satish 04052017===//
function check_exist($table,$where,$not_where_array,$and_like_array,$or_like_array)
{
	
	global $db_con;

	$sql =" SELECT * FROM ".$table." ";
	$sql .=" WHERE 1 = 1 ";
	
	//==Check Where Condtions=====//
	if(!empty($where))
	{
		foreach($where as $field1 => $value1 )
		{   
			$sql  .= " AND ".$field1 ."='".$value1."' ";
		}
	}
	
	//==Check Not Equal Condtions=====//
	if(!empty($not_where_array))
	{
		foreach($not_where_array as $field2 => $value2 )
		{   
			$sql  .= " AND ".$field2 ."!='".$value2."' ";
		}
	}
	
	//==Check AND Like Condtions=====//
	if(!empty($and_like_array))
	{
		foreach($and_like_array as $field3 => $value3 )
		{   
			$sql  .= " AND ".$field3 ." like '".$value3."' ";
		}
	}
	//==Check AND Like Condtions=====//
	if(!empty($or_like_array))
	{
		foreach($or_like_array as $field4 => $value4 )
		{   
			$sql  .= " AND ".$field4 ." like '".$value4."' ";
		}
	}
	
	$result	   = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	$nums      = mysqli_num_rows($result);
	if($nums ==0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/////////////////===Delete Records FRom tabke ================================================//
function delete($table,$where,$not_where_array=array(),$and_like_array=array(),$or_like_array=array())
{
        //Make sure the array isn't empty
		global $db_con;
        
        
        $sql =" DELETE FROM ".$table." ";
        $sql .=" WHERE 1 = 1 ";
		
		//==Check Where Condtions=====//
		if(!empty($where))
		{
			foreach($where as $field1 => $value1 )
			{   
				$sql  .= " AND ".$field1 ."='".$value1."' ";
			}
		}
	
	//==Check Not Equal Condtions=====//
		if(!empty($not_where_array))
		{
			foreach($not_where_array as $field2 => $value2 )
			{   
				$sql  .= " AND ".$field2 ."!='".$value2."' ";
			}
		}
	
	//==Check AND Like Condtions=====//
		if(!empty($and_like_array))
		{
			foreach($like_array as $field3 => $value3 )
			{   
				$sql  .= " AND ".$field3 ." like '".$value3."' ";
			}
		}
	//==Check AND Like Condtions=====//
		if(!empty($or_like_array))
		{
			foreach($or_like_array as $field4 => $value4 )
			{   
				$sql  .= " AND ".$field4 ." like '".$value4."' ";
			}
		}
  // return $sql;
        $result_check_spec 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
        
        if($result_check_spec)
        {
            return true;
        }
        else
        {
            return false;
        }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//===============================Common Function Ends==============================================================================
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




if((isset($_POST['stock_update'])) == "1" && isset($_POST['stock_update']))
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
	$no_of_records =0;
	if($inputFileName !="")
	{	

		
		for($i=2;$i<=$arrayCount;$i++)
		{   
			 $isbn						    = trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["A"]));
			 $model_no						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["B"]));
			 $quantity						= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["C"]));
			 $max_quantity					= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["D"]));
			 $list_price					= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"]));
			 $recom_price					= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"]));
			 
			if($isbn!="")
			{
				$sql  = "SELECT * FROM tbl_products_specifications as tps  ";
				if($model_no !="")
				{
					$sql .=" INNER JOIN tbl_products_master as tpm ON tps.prod_spec_prodid=tpm.prod_id";
				}
				$sql .= " WHERE 1=1  ";
				
			
				if($isbn !="")
				{
				    $sql .=" AND prod_spec_specid = '4' ";
					$sql .=" AND prod_spec_value='".$isbn."' ";
				}
				if($model_no !="")
				{
					$sql .=" AND tpm.prod_model_number='".$model_no."'";
				}
				
				$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
				while($row = mysqli_fetch_array($res))
				{
					if($quantity !="" || $max_quantity !="" || $list_price !="" || $recom_price !="")
					{
						$sql_update  = " UPDATE tbl_products_master SET ";
						if($quantity !="")
						{
							$sql_update .= " prod_quantity ='".$quantity."' , ";
						}
						if($max_quantity !="")
						{
							$sql_update .= " prod_max_quantity ='".$max_quantity."' , ";
						}
						if($list_price !="")
						{
							$sql_update .= " prod_list_price ='".$list_price."' , ";
						}
						if($recom_price !="")
						{
							$sql_update .= " prod_recommended_price ='".$recom_price."' , ";
						}
						$sql_update .= " prod_modified ='".$datetime."' , ";
						$sql_update .= " prod_modified_by ='".$uid."' ";
						$sql_update .= "    WHERE  prod_id = '".$row['prod_spec_prodid']."'";
						$res_update  = mysqli_query($db_con,$sql_update) or die(mysqli_error($db_con));
						$no_of_records++;
						
					}
					
				}
			}
			
			if($no_of_records ==0)
			{
				$response_array = array("Success"=>"fail",'resp'=>"Record Not Updated...!");
			}
			else
			{
				$response_array = array("Success"=>"Success",'resp'=>"Record  Updated Successfully...!");
			}
			
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"File name not found..!");
	}

	echo json_encode($response_array);	
}



?>