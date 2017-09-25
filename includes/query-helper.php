<?php
	// Query For Insert
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
	
		$result		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		
		if($result)
		{
			return mysqli_insert_id($db_con);
		}
		else
		{
			return false;
		}
	}
	
	// Query For Update
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
	
		//==Check Not Where Condtions=====//
		if(!empty($not_where_array))
		{
			foreach($not_where_array as $field2 => $value2 )
			{   
				$sql  .= " AND ".$field2 ."!='".$value2."' ";
			}
		}
		
		$result 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		
		if($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	// For Compile
	function quit($msg,$Success="")
	{
		if($Success ==1)
		{
			$Success="Success";
		}
		else
		{
			$Success="fail";
		}
		echo json_encode(array("Success"=>$Success,"resp"=>$msg));
		exit();
	}
	// Select Query For getting the Record count
	function isExist($table ,$where, $not_where_array=array(), $and_like_array=array(), $or_like_array=array())
	{
		global $db_con;
		if($table=="")
		{
			quit('Table name can not be blank');
		}
		$sql = " SELECT * FROM ". $table ;
		$fields = array();
		$values = array();
		
		
		$sql .=" WHERE 1 = 1 ";
		
		//==Check Where Condtions=====//
		if(!empty($where))
		{
			foreach($where as $field1 => $value1 )
			{   
				$sql  .= " AND ".$field1 ."='".$value1."' ";
			}
		}
		
		//==Check Not Where Condtions=====//
		if(!empty($not_where_array))
		{
			foreach($not_where_array as $field2 => $value2)
			{   
				$sql  .= " AND ".$field2 ."!='".$value2."' ";
			}
		}
		
		$result 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		$num            = mysqli_num_rows($result);
		if($num > 0)
		{
			
			return $num;
		}
		else
		{
			return false;
		}
	}
	
	
	
	function checkExist($table ,$where, $not_where_array=array(), $and_like_array=array(), $or_like_array=array())
	{
		global $db_con;
		if($table=="")
		{
			quit('Table name can not be blank');
		}
		$sql = " SELECT * FROM ". $table ;
		$fields = array();
		$values = array();
		
		
		$sql .=" WHERE 1 = 1 ";
		
		//==Check Where Condtions=====//
		if(!empty($where))
		{
			foreach($where as $field1 => $value1 )
			{   
				$sql  .= " AND ".$field1 ."='".$value1."' ";
			}
		}
		
		//==Check Not Where Condtions=====//
		if(!empty($not_where_array))
		{
			foreach($not_where_array as $field2 => $value2)
			{   
				$sql  .= " AND ".$field2 ."!='".$value2."' ";
			}
		}
		
		$result 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		$num            = mysqli_num_rows($result);
		if($num > 0)
		{
			$row = mysqli_fetch_array($result);
			return $row;
		}
		else
		{
			return false;
		}
	}
	
	
	function getRecord($table ,$where, $not_where_array=array(), $and_like_array=array(), $or_like_array=array())
	{
		global $db_con;
		if($table=="")
		{
			quit('Table name can not be blank');
		}
		$sql = " SELECT * FROM ". $table ;
		$fields = array();
		$values = array();
		
		
		$sql .=" WHERE 1 = 1 ";
		
		//==Check Where Condtions=====//
		if(!empty($where))
		{
			foreach($where as $field1 => $value1 )
			{   
				$sql  .= " AND ".$field1 ."='".$value1."' ";
			}
		}
		
		//==Check Not Where Condtions=====//
		if(!empty($not_where_array))
		{
			foreach($not_where_array as $field2 => $value2)
			{   
				$sql  .= " AND ".$field2 ."!='".$value2."' ";
			}
		}
		
		$result 		= mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		$num            = mysqli_num_rows($result);
		if($num > 0)
		{
			
			return $result;
		}
		else
		{
			return false;
		}
	}
?>