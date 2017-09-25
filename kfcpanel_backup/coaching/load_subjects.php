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
		if(sizeof($col_array)>1)
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



function get_parent($cat_id)
{
	global $db_con;
	$sql_get_cat = "SELECT * FROM tbl_coaching_category WHERE cat_id ='".$cat_id."' ";
	$res_get_cat = mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
	$num_rows    = mysqli_num_rows($res_get_cat);
	if($num_rows !=0)
	{   
	    $row = mysqli_fetch_array($res_get_cat);
		//$cat_id          = $row['cat_id'];
	    if($row['cat_type']!="parent")
		{
			$cat_id=get_parent($row['cat_type']);
		}
		
	}
	return $cat_id;
}

function get_child_ids($cat_id,$array)
{
	global $db_con;
	$sql_get_cat = "SELECT * FROM tbl_coaching_category WHERE cat_type ='".$cat_id."' ";
	$res_get_cat = mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
	$num_rows    = mysqli_num_rows($res_get_cat);
	if($num_rows !=0)
	{   
	    while($row =mysqli_fetch_array($res_get_cat))
		{
			array_push($array,$row['cat_id']);
			$array = get_child_ids($row['cat_id'],$array);
		}
	}
	return $array;
}

function show_full_path($cat_id,$array)
{
  	global $db_con;
	$sql_get_cat = "SELECT * FROM tbl_coaching_category WHERE cat_id ='".$cat_id."' AND cat_name !='none' AND cat_status =1 ";
	$res_get_cat = mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
	$num_rows    = mysqli_num_rows($res_get_cat);
	if($num_rows !=0)
	{   $row =mysqli_fetch_array($res_get_cat);
		array_push($array,ucwords($row['cat_name']));
		if($row['cat_type']!="parent")
		{
		  $array = show_full_path($row['cat_type'],$array);
		}
	}
	return $array;
}



if((isset($obj->load_add_subject_part)) != "" && isset($obj->load_add_subject_part))
{
	$subject_id = $obj->subject_id;
	$req_type 	= $obj->req_type;		
	$data       ="";
	
	
	  		$data = '';
			
		if($req_type=="edit" || $req_type=="view")
		{
			$sql_get_subject  =" SELECT * FROM tbl_offering as tof ";
			$sql_get_subject .=" INNER JOIN tbl_cadmin_users as tcu ON tof.offering_created_by=tcu.id ";
			$sql_get_subject .=" WHERE offering_id ='".$subject_id."' ";
			
			$res_get_subject  = mysqli_query($db_con,$sql_get_subject) or die(mysqli_error($db_con));
			$row_get_subject  = mysqli_fetch_array($res_get_subject);
	
			if($req_type=="edit")
			{
				$disabled="";
			}
			else
			{
				$disabled=" disabled ";
			}
		}

		if($req_type=="add")
		{
			$data .='<input type="hidden" name="add_subject_req" value="1">';
		  
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Subject Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" id="sub_name" rows="4" name="sub_name" placeholder="Subject Name" class="input-xlarge" data-rule-required="true" >';
			$data .= '</div>';	
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
		    $data .= '<label for="tasktitel" class="control-label">Offering Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';  $data .= '<div class="controls">';
			$data .= '<select style=" width: 95%;margin-top:10px;" name="offering_type" onchange="getsubcat(this.value)"  id="offering_type"  required placeholder="Type Category" class="input-block-level ">';
			$data .='<option value="">Offering Type</option>';
			$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND cat_type='parent'";
			$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
			while($row_cat = mysqli_fetch_array($res_get_cat))
			{
				$cat_path=$row_cat['cat_name'];
			    $data .='<option value="'.$row_cat['cat_id'].'">'.	ucwords($cat_path).'</option>';
			}
			$data .='</select>';
			$data .='</div>';
			$data .='</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Category<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';       $data .= '<div class="controls">';
			$data .= '<select style=" width: 95%;margin-top:10px;" name="catid" required id="catid" onChange="console.log($(this).children(":selected").length)"  class="input-block-level ">';
			$data .='<option value="">Select Category</option>';
			$data .='</select>';
			$data .='</div>';
			$data .='</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="radio" name="status" value="1"  data-rule-required="true">Active';
			$data .= '<input type="radio" style="margin:10px;" name="status" value="0"  data-rule-required="true">Inactive<br>';
			$data .= '</div>';
			$data .= '</div>';
	  
			$data .= '<br><button class="btn-info" type="submit" onclick="">Add Subject</button>';
			$data .= '<script type="text/javascript">';
			$data .= '$("#area").select2();';
			$data .= '$("#catid").select2();';
			$data .= '$("#offering_type").select2();';
		//	$data .= 'CKEDITOR.replace( "address",{height:"150", width:"100%"});';
			$data .= '</script>';
			$response_array = array("Success"=>"Success","resp"=>$data,"class_name"=>'&nbsp;&nbsp;'.ucwords($class_row['class_name']));				
			echo json_encode($response_array);exit();
		}


        
	  	if($req_type=="edit" || $req_type=="view")
		{
			$data .='<input type="hidden" name="update_subject_req" value="1">';
		    $data .='<input type="hidden" name="offering_id" value="'.$subject_id.'">';
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Subject Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input '.$disabled.' type="text" value="'.$row_get_subject['offering_name'].'" id="sub_name" rows="4" name="sub_name" placeholder="Subject Name" class="input-xlarge" data-rule-required="true" >';
			$data .= '</div>';	
			$data .= '</div>';
			
			$data .= '<div class="control-group">';
		   $data .= '<label for="tasktitel" class="control-label">Offering Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';      $data .= '<div class="controls">';
			$data .= '<select style=" width: 95%;margin-top:10px;" name="offering_type" onchange="getsubcat(this.value)"  id="offering_type"  required placeholder="Type Category" class="input-block-level ">';
			$data .='<option value="">Offering Type</option>';
			$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND cat_type='parent'";
			$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
			$data .='<option value="'.$row_get_subject['offering_type'].'"  >'.	$row_get_subject['offering_type'].'</option>';
			while($row_cat = mysqli_fetch_array($res_get_cat))
			{
				
				if($req_type=="edit" || $req_type=="view")
				{
					   $cat_path=$row_cat['cat_name'];
					  
					   $data .='<option value="'.$row_cat['cat_id'].'" ';
					   if($row_cat['cat_name'] == trim($row_get_subject['offering_type']))
					   {
						   $data .=' selected="selected" ';
					   }
					   
					   $data .=' >'.	ucwords($cat_path).'</option>';
				}
			}
			
			$data .='</select>';
			$data .='</div>';
			$data .='</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Category<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';       $data .= '<div class="controls">';
			$data .= '<select style=" width: 95%;margin-top:10px;" name="catid" required id="catid" onChange="console.log($(this).children(":selected").length)"  class="input-block-level ">';
			$data .='<option value="">Select Category</option>';
			if($req_type=="view" || $req_type=="edit")
			{ 
			    $cat_id      = lookup_value("tbl_coaching_category",array('cat_id'),array("cat_name"=>$row_get_subject['offering_type']),array(),array(),array());
			  $data .=$cat_id;
				$sql_get_cat = "SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND main_parent = '".$cat_id."' or cat_id ='".$cat_id."'";
				$res_get_cat = mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
				while($row_cat = mysqli_fetch_array($res_get_cat))
				{
					    if($row_cat['cat_type']=="parent")
						{
							$cat_path=$row_cat['cat_name']; 
						}
						else
						{
							$demo =show_full_path($row_cat['cat_type'],$array=array());
							$cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
						}
						
						$data .='<option value="'.$row_cat['cat_id'].'" ';
						if($row_get_subject['cat_id']==$row_cat['cat_id'])
						{
							$data .=' selected="selected" ';
						}
						$data .=' >'.ucwords($cat_path).'</option>';
				}
			}
			$data .='</select>';
			$data .='</div>';
			$data .='</div>';
			
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="radio" name="status" value="1" ';
			if($row_get_subject['offering_status']==1)
			{
				$data .=' checked="checked" ';
			}
			$data .='  data-rule-required="true">Active';
			$data .= '<input type="radio" style="margin:10px;" name="status" value="0" ';
			if($row_get_subject['offering_status']==0)
			{
				$data .=' checked="checked" ';
			}
			$data .='  data-rule-required="true">Inactive<br>';
			$data .= '</div>';
			$data .= '</div>';
	  
			$data .= '<br><button class="btn-info" type="submit" onclick="">Update Subject</button>';
			$data .= '<script type="text/javascript">';
			$data .= '$("#area").select2();';
			$data .= '$("#catid").select2();';
			$data .= '$("#offering_type").select2();';
		//	$data .= 'CKEDITOR.replace( "address",{height:"150", width:"100%"});';
			$data .= '</script>';
			$response_array = array("Success"=>"Success","resp"=>$data,"class_name"=>'&nbsp;&nbsp;'.ucwords($class_row['class_name']));				
			echo json_encode($response_array);exit();
		}
	
	
	$response_array = array("Success"=>"Success","resp"=>$data);
	echo json_encode($response_array);exit();
}


if((isset($_POST['add_subject_req'])) == "1" && isset($_POST['add_subject_req']))
{
	$data['offering_name']	 = mysqli_real_escape_string($db_con,$_POST['sub_name']);
	$offering_type	 		 = mysqli_real_escape_string($db_con,$_POST['offering_type']);
	$data['cat_id']			 = $_POST['catid'];
	$data['offering_status'] = $_POST['status'];
	$data['offering_created']= $datetime;
	$data['offering_type']   =lookup_value("tbl_coaching_category",array('cat_name'),array("cat_id"=>$offering_type),array(),array(),array());
	// check_exist(table_name,where_array,not_where_array,and_like_array,or_like_array);
	if($data['offering_name']!="" && $data['cat_id'] !="" && $data['offering_type'] !="" && $data['offering_status']!="")
	{
		if(check_exist("tbl_offering",array(),array(),array("offering_name"=>$data['offering_name']),array()))
		{
			insert("tbl_offering",$data);
			$response_array = array("Success"=>"Success","resp"=>"Subject  Added Successfully");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Subject  Already Exist..!!");	
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"All Fields are Mandotory");	
	}
	
	echo json_encode($response_array);		
}




//==========================================================================================================
//====================Start : Done by satish 15032017 for update_category=====================================
//==========================================================================================================

if((isset($_POST['update_subject_req'])) == "1" && isset($_POST['update_subject_req']))
{
	$data['offering_name']	 = mysqli_real_escape_string($db_con,$_POST['sub_name']);
	$offering_type	 		 = mysqli_real_escape_string($db_con,$_POST['offering_type']);
	$data['cat_id']			 = $_POST['catid'];
	$data['offering_status'] = $_POST['status'];
	$offering_id             = $_POST['offering_id'];
	$data['offering_created']= $datetime;
	$data['offering_type']   =lookup_value("tbl_coaching_category",array('cat_name'),array("cat_id"=>$offering_type),array(),array(),array());
	// check_exist(table_name,where_array,not_where_array,and_like_array,or_like_array);
	
	if($data['offering_name']!="" && $data['cat_id'] !="" && $data['offering_type'] !="" && $data['offering_status']!="")
	{
		if(check_exist("tbl_offering",array(),array("offering_id"=>$offering_id),array("offering_name"=>$data['offering_name']),array()))
		{
			update("tbl_offering",$data,array("offering_id"=>$offering_id));
			$response_array = array("Success"=>"Success","resp"=>"Subject  Updated Successfully");	
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"Subject  Already Exist..!!");	
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"All Fields are Mandotory");	
	}
	
	echo json_encode($response_array);		
}




function get_parents($cat_id,$array)
{
	global $db_con;
	
	$sql_get_type =" SELECT cat_type,cat_id FROM tbl_coaching_category WHERE cat_id ='".$cat_id."' ";
	$res_get_type = mysqli_query($db_con,$sql_get_type)or die(mysqli_error($db_con));
	$row          = mysqli_fetch_array($res_get_type);
	
	array_push($array,$row['cat_id']);
	if($row['cat_type']!='parent')
	{
		$array = get_parents($row['cat_type'],$array);
	}
	return $array;
}

///////////////  End get_parents////////////

//================================Strat :  Modified BY satish 16032017 update status==========//

if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$offering_id			= mysqli_real_escape_string($db_con,$obj->offering_id);
	$curr_status			= mysqli_real_escape_string($db_con,$obj->curr_status);
	$response_array 		= array();

    if(update("tbl_offering", $variables = array("offering_status"=>$curr_status),array("offering_id"=>$offering_id),array(),array(),array()))
	{
		$response_array =  array("Success"=>"Success","resp"=>"Status Changed Successfully...!");
	}
	else
	{
		$response_array =  array("Success"=>"fail","resp"=>"Status Updation Failed...!");
	}

	echo json_encode($response_array);	
}

//================================End :  Modified BY satish 16032017 update status==========//



if((isset($obj->delete_subject)) == "1" && isset($obj->delete_subject))
{
	$response_array = array();		
	$ar_offering_id 		= $obj->batch;
	$del_flag 		= 0; 
	if(!empty($ar_offering_id))
	{
		foreach($ar_offering_id as $offering_id)	
		{
				$check = delete("tbl_offering",array("offering_id"=>$offering_id),array(),array(),array());
		}
		
		quit("Record Deletion Success...!",1);
	}
	else
	{
		quit("Record Deletion Failed...!");
	}		
	
}

//==========================================================================================================
//====================End : Done by satish 15032017 for update_category=====================================
//==========================================================================================================


if((isset($obj->load_subjects)) == "1" && isset($obj->load_subjects))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= mysqli_real_escape_string($db_con,$obj->page);	
	$per_page		= mysqli_real_escape_string($db_con,$obj->row_limit);
	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);	
	$cat_parent		= mysqli_real_escape_string($db_con,$obj->cat_parent);		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT * FROM tbl_offering as tof ";
		$sql_load_data .= " INNER JOIN tbl_coaching_category as tcc ON tof.cat_id =tcc.cat_id ";
		if(strcmp($utype,'1')!==0)
		{
			$sql_load_data  .= " AND offering_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (offering_type like '%".$search_text."%' or cat_name like '%".$search_text."%' ";
			$sql_load_data .= " or offering_name like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY offering_id LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));			
		if(strcmp($data_count,"0") !== 0)
		{
					
			
			$subject_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$subject_data .= '<thead>';
    	  	$subject_data .= '<tr>';
         	$subject_data .= '<th class="center-text">Sr No.</th>';
			$subject_data .= '<th class="center-text">Subject Id</th>';
			$subject_data .= '<th>Subject Name</th>';
			$subject_data .= '<th style="width:15%;text-align:center">Category</th>';	
			$subject_data .= '<th style="text-align:center">Sub Category</th>';
				
			
			
			$dis = checkFunctionalityRight("view_subjects.php",3);
			if($dis)
			{
				$subject_data .= '<th class="center-text">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_subjects.php",1);
			if($edit)
			{
				$subject_data .= '<th class="center-text">Edit</th>';
			}
			$del = checkFunctionalityRight("view_subjects.php",2);
			if($del)
			{
				$subject_data .= '<th class="center-text">';
				$subject_data .= '<div class="center-text">';
				$subject_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$subject_data .= '</tr>';
      		$subject_data .= '</thead>';
      		$subject_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$subject_data .= '<tr>';				
				$subject_data .= '<td class="center-text">'.++$start_offset.'</td>';				
				$subject_data .= '<td class="center-text">'.$row_load_data['cat_id'].'</td>';
				$subject_data .= '<td>';
				$subject_data .= '<div class="center-text">';				
				$subject_data .= '<input type="button" value="'.ucwords($row_load_data['offering_name']).'" class="btn-link" id="'.$row_load_data['offering_id'].'" onclick="addMoreSubject(this.id,\'view\');">';
				$subject_data .= '<i class="icon-chevron-down" id="'.$row_load_data['cat_id'].'chevron" onclick="toggleMyDiv(this.id,\''.$row_load_data['offering_id'].'cat_div\');" style="cursor:pointer;float:right;font-size:20px;margin-right: 10px;"></i>';
				$subject_data .= '</div>';				
				$subject_data .= '<div style="display:none" id="'.$row_load_data['offering_id'].'cat_div">';
				if($row_load_data['cat_by_created'] == "")
				{
					$subject_data .= '<b>Created By:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$subject_data .= '<span><b>Created By:</b>'.$row_load_data['cat_by_created'].'</span><br>';
				}
				if($row_load_data['cat_created'] == "" || $row_load_data['cat_created'] == "0000-00-00 00:00:00")
				{
					$subject_data .= '<b>Created :</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$subject_data .= '<span><b>Created :</b>'.$row_load_data['cat_created'].'</span><br>';
				}				
				if($row_load_data['cat_by_modified'] == "")
				{
					$subject_data .= '<b>Modified By:</b><span style="color:#f00;">Not Available</span><br>';					
				}
				else
				{
					$subject_data .= '<span><b>Modified By:</b>'.$row_load_data['cat_by_modified'].'</span><br>';
				}
				if($row_load_data['cat_modified'] == "" || $row_load_data['cat_modified'] == "0000-00-00 00:00:00")
				{
					$subject_data .= '<b>Modified:</b><span style="color:#f00;">Not Available</span><br>';
				}
				else
				{
					$subject_data .= '<span><b>Modified:</b>'.$row_load_data['cat_modified'].'</span><br>';								
				}
				$subject_data .= '</div>';
				$subject_data .= '</td>';	
				
				$subject_data .= '<td class="center-text">';
				$subject_data .= ucwords($row_load_data['offering_type']);
				$subject_data .= '</td>';							
				$subject_data .= '<td class="center-text">';
				$dat =array_reverse(show_full_path($row_load_data['cat_id'],$array=array()));
				$subject_data .=implode('->',$dat);
				//$subject_data .=implode('->',show_full_path($row_load_data['cat_id']),$array=array()).'->'.$row_load_data['offering_name'];
				$subject_data .= '</td>';
				
				$dis = checkFunctionalityRight("view_subjects.php",3);
				if($dis)			
				{
					$subject_data .= '<td class="center-text">';	
					if($row_load_data['offering_status'] == 1)
					{
						$subject_data .= '<input type="button" value="Active" id="'.$row_load_data['offering_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$subject_data .= '<input type="button" value="Inactive" id="'.$row_load_data['offering_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$subject_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_subjects.php",1);				
				if($edit)
				{
					$subject_data .= '<td class="center-text">';
					if($row_load_data['cat_name'] !="none")
					{
					$subject_data .= '<input type="button" value="Edit" id="'.$row_load_data['offering_id'].'" class="btn-warning" onclick="addMoreSubject(this.id,\'edit\');">';
					}
					else 
					{
							$subject_data .='-';
					}
					$subject_data .='</td>';
				}
				$del = checkFunctionalityRight("view_subjects.php",2);
				if($del)				
				{    
				  
					$subject_data .= '<td>';
					if($row_load_data['cat_name'] !="none")
					{
					$subject_data .=' <div class="controls" align="center">';					
					$subject_data .= '		<input type="checkbox" value="'.$row_load_data['offering_id'].'" id="batch'.$row_load_data['offering_id'].'" name="batch'.$row_load_data['cat_id'].'" class="css-checkbox batch">';
					$subject_data .= '		<label for="batch'.$row_load_data['offering_id'].'" class="css-label"></label>';
					$subject_data .= '	</div>';
					}
					else 
					{
							$subject_data .='-';
					}
					$subject_data .= '	</td>';										
				}
	          	$subject_data .= '</tr>';															
			}	
      		$subject_data .= '</tbody>';
      		$subject_data .= '</table>';	
			$subject_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$subject_data);					
		
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



 
if((isset($obj->get_subcat)) == "1" && isset($obj->get_subcat))
{
	$data ="";
	$cat_id  = $obj->cat_id;

	$sql_get_cat ="SELECT * FROM tbl_coaching_category WHERE cat_status =1 AND main_parent = '".$cat_id."' or cat_id ='".$cat_id."'";
	$res_get_cat =mysqli_query($db_con,$sql_get_cat) or die(mysqli_error($db_con));
	while($row_cat = mysqli_fetch_array($res_get_cat))
	{
			if($row_cat['cat_type']=="parent")
			   {
				   $cat_path=$row_cat['cat_name'];
			   }
			   else
			   {  
				  $demo =show_full_path($row_cat['cat_type'],$array=array());
				  $cat_path=implode('>',array_reverse($demo)).' > '.$row_cat['cat_name']; 
			   }
			   $data .='<option value="'.$row_cat['cat_id'].'">'.$cat_path.'</option>';
	}
	echo json_encode(array("Success"=>"Success","resp"=>$data));
}

?>