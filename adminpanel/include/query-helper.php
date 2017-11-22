<?php
	// Query For Insert
	////////////////////////////////////////////////////////////////////////////////////

	
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
	
	function getRecord($table ,$where=array(), $not_where_array=array(), $and_like_array=array(), $or_like_array=array())
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
	
	function getList($table,$option_val,$option_name,$selected_id='',$where_arr=array(),$not_where=array())
	{
		global $db_con;
		if(!isset($table))
		{
			return 'Please provide table name';
		}
		$sql=" SELECT * FROM ".$table." WHERE 1=1 "; 
		//==Check Where Condtions=====//
		if(!empty($where_arr))
		{
			foreach($where_arr as $field => $value )
			{   
				$sql  .= " AND ".$field ."='".$value."' ";
			}
		}
		if(!empty($not_where))
		{
			foreach($not_where as $field1 => $value1 )
			{   
				$sql  .= " AND ".$field1 ."!='".$value1."' ";
			}
		}
		$sql  .= " ORDER BY ".$option_name." ASC  ";
		$res   = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
		$num   = mysqli_num_rows($res);
		if($num !=0)
		{
			$data ='';
			while($row = mysqli_fetch_array($res))
			{
				$data .='<option value="'.$row[$option_val].'" ';
				if($row[$option_val]==$selected_id)
				{
					$data .=' selected="selected" ';
				}
				$data .='>'.ucwords($row[$option_name]).'</option>';
			}
			return $data;
		}
		else
		{
		return '<option value="">NO item\'s found..! </option>';
		}
	}
	
	function query($sql)
	{
		global $db_con;
		return mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	}
	
	
	function fetch($res)
	{
		return mysqli_fetch_array($res);
	}
	
	
	function real_escape($data)
	{
		global $db_con;
		if(is_array($data))
		{
			
		}
		else
		{
			$data =mysqli_real_escape_string($db_con,$data);
			return $data;
		}
	}



	function insertActivity($module,$type,$uid,$change_id,$old_data,$new_data,$message)
	{
		global $datetime;
		global $db_con;

		$data['module'] 					= real_escape($module);
		$data['type'] 					= real_escape($type);
		$data['user_id'] 				= real_escape($uid);
		$data['change_id'] 				= real_escape($change_id);
		$data['old_data'] 				= real_escape($old_data);
		$data['new_data']				= real_escape($new_data);
		$data['message'] 				= real_escape($message);
		$data['created_date'] 			= real_escape($type);
		insert('tbl_users_activity',$data);

	}

?>