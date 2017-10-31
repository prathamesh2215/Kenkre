<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

 
if(isset($_POST['student_file']))
{   
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$prod_id	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$counter= 0;
	$response_array = array("ghv");
	
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
	
	if(strcmp($inputFileName, "")!==0)
	{
           
		for($i=2;$i<=$arrayCount;$i++)
		{	
		    $flag         				= 1;
			$update_error 				= '';		
				
			$data['student_fname']		= strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["A"]))), ENT_HTML5));
			$data['student_mname']		= strtolower( htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["B"])), ENT_HTML5));
			$data['student_lname']		= strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["C"]))), ENT_HTML5));
			 
			$data['student_dob']   		= htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["D"])), ENT_HTML5);
			$data['student_dob']   		= str_replace(".","-",$data['student_dob']);
			
				
			$data['student_email']		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"]));
			$data['student_mobile']		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["F"]));
			$data['student_mobile']		= explode(',',$data['student_mobile']);
			$data['student_mobile']		= $data['student_mobile'][0];
			
			$adata['add_details']	    = trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["G"]));
			$adata['add_city']	        = trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["H"]));
			$adata['add_state']	        = trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["I"]));
			$data['student_institute']	= strtolower(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["K"])));
			$data['stud_joinig_date']		= trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["L"]));
			
			$data['student_created_by']	= $uid;
			$data['student_created']	= $datetime;
			$adata['add_created_by']	= $uid;
			$adata['add_created']		= $datetime;
			
			if($data['student_fname']!="" && $data['student_lname']!="" && $data['student_mobile']!="" )
			{
				if(!isExist('tbl_students',array('student_mobile'=>$data['student_mobile'])) && !isExist('tbl_students',array('student_email'=>$data['student_email'])))
				{
					$insert_id			    = insert('tbl_students',$data);
					$adata['add_user_id']   = $insert_id;
					$adata['add_user_type'] = 'student';
					$insert_id 				= insert('tbl_address_master',$adata);
				}
			}
		}// for end  
		
		quit('Students uploaded Successfully...!',1);
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}


if(isset($_POST['coach_file']))
{   
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$prod_id	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$counter= 0;
	$response_array = array("ghv");
	
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
	
	if(strcmp($inputFileName, "")!==0)
	{
           
		for($i=2;$i<=$arrayCount;$i++)
		{	
		    $flag         =1;
			$update_error ='';		
			
			$f_name              	= strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["A"]))), ENT_HTML5));
			$m_name 	            = strtolower( htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["B"])), ENT_HTML5));
			$l_name 	            = strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["C"]))), ENT_HTML5));
			 
			$data['fullname']      = $f_name.' '.$m_name.' '.$l_name;
			
			$data['email']         = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["D"])), ENT_HTML5);
			if($data['email']=='')
			{
				$email             = explode(' ',$f_name);
				$data['email']     = $email[0].'@kenkre.com';
				
			}
			$data['mobile_num']    = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["E"])), ENT_HTML5);
			$data['mobile_num']	   = explode(',',$data['mobile_num']);
			$data['mobile_num']	   = $data['mobile_num'][0];
			
			$data['created_by']    = $logged_uid;
			$data['created']       = $datetime;
			$password              = 123456;//generateRandomString(8);
			$data['salt_value']    = generateRandomString(6);
			$data['password']      = md5($password.$data['salt_value']);
			$data['sms_status']    = 1;
			$data['userid']        = $data['email'];
			$data['utype']         = 15;
			$data['email_status']  = 1;
	
	
			$adata['add_details']          = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["F"])), ENT_HTML5);
			$data['city']                  = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["G"])), ENT_HTML5);
			$data['state']                 = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["H"])), ENT_HTML5);
			//$data['city']                = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["I"])), ENT_HTML5);
			$idata['coach_experience']     = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["J"])), ENT_HTML5);
			$idata['coach_designation']    = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["K"])), ENT_HTML5);
			//$data['state']               = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["L"])), ENT_HTML5);
			$idata['contract_start_date']  = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["M"])), ENT_HTML5);
			$idata['contract_end_date']    = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["N"])), ENT_HTML5);
			
			$data['status']                = 1;
			
		   
			
			if($data['fullname']!=""  && $data['mobile_num']!="" )
			{
				if(!isExist('tbl_cadmin_users',array('mobile_num'=>$data['mobile_num'])))
				{ 
					$insert_id = insert('tbl_cadmin_users',$data);
					$adata['add_user_id']      = $insert_id;
					$adata['add_user_type']    = 'coach';
					$adata['add_created']      = $datetime;
					$adata['add_created_by']   = $uid;
					$adata['add_user_id']      = $insert_id;
					$insert_id                 = insert('tbl_address_master',$adata);
					$insert_id                 = insert('coach_additional_info',$idata);
				}
			}
		}// for end  
		
		quit('Coach uploaded Successfully...!',1);
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}


if(isset($_POST['admin_file']))
{   
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$prod_id	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$counter= 0;
	$response_array = array("ghv");
	
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
	
	if(strcmp($inputFileName, "")!==0)
	{
           
		for($i=2;$i<=$arrayCount;$i++)
		{	
		    $flag         =1;
			$update_error ='';		
			
			$f_name              	= strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["A"]))), ENT_HTML5));
			$m_name 	            = strtolower( htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["B"])), ENT_HTML5));
			$l_name 	            = strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["C"]))), ENT_HTML5));
			
			$data['fullname']       = $f_name.' '.$m_name.' '.$l_name;
			$data['dob']            = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["D"])), ENT_HTML5);
			$data['dob']            = str_replace(".","-",$data['student_dob']);
			$data['email']         = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["E"])), ENT_HTML5);
			$data['mobile_num']    = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["F"])), ENT_HTML5);
			$data['mobile_num']	   = explode(',',$data['mobile_num']);
			$data['mobile_num']	   = $data['mobile_num'][0];
			
			$data['created_by']    = $logged_uid;
			$data['created']       = $datetime;
			$password              = 123456;//generateRandomString(8);
			$data['salt_value']    = generateRandomString(6);
			$data['password']      = md5($password.$data['salt_value']);
			$data['sms_status']    = 1;
			$data['userid']        = $data['email'];
			$data['utype']         = 15;
			$data['email_status']  = 1;
	
	
			$adata['add_details']          = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["G"])), ENT_HTML5);
			$data['city']                  = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["H"])), ENT_HTML5);
			$data['state']                 = htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["I"])), ENT_HTML5);
			
			
			$data['status']                = 1;
			$data['utype']                 = 1;
			
		   
			
			if($data['fullname']!=""  && $data['mobile_num']!="" )
			{
				if(!isExist('tbl_cadmin_users',array('mobile_num'=>$data['mobile_num'])) && !isExist('tbl_cadmin_users',array('email'=>$data['email'])))
				{ 
					$insert_id = insert('tbl_cadmin_users',$data);
					$adata['add_user_id']      = $insert_id;
					$adata['add_user_type']    = 'coach';
					$adata['add_created']      = $datetime;
					$adata['add_created_by']   = $uid;
					$adata['add_user_id']      = $insert_id;
					$insert_id                 = insert('tbl_address_master',$adata);
					
				}
			}
		}// for end  
		
		quit('Admin uploaded Successfully...!',1);
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}


if(isset($_POST['competition_file']))
{   
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$prod_id	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$counter= 0;
	$response_array = array("ghv");
	
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
	
	if(strcmp($inputFileName, "")!==0)
	{
           
		for($i=2;$i<=$arrayCount;$i++)
		{	
		    $flag         =1;
			$update_error ='';		
			
			$data['competition_name']       = strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["A"]))), ENT_HTML5));
			$data['start_date'] 	         = strtolower( htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["B"])), ENT_HTML5));
			$data['end_date'] 	            = strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["C"]))), ENT_HTML5));
			$data['competition_place']    = strtolower(htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["D"])), ENT_HTML5));
			$data['competition_limit']    = trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"]));
			
				
			$data['created_date']       = $datetime;
			$data['created_by']         = $uid;
			//quit($data['competition_name']);
			if($data['competition_name']!="" && $data['start_date']!="" && $data['end_date']!="" )
			{
				
				if(!isExist('tbl_competition',array('competition_name'=>$data['competition_name'])))
				{
					$insert_id = insert('tbl_competition',$data);
				}
			}
		}// for end  
		
		quit('Competition uploaded Successfully...!',1);
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}


if(isset($_POST['team_file']))
{   
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,$inputFileName) ;
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$prod_id	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$counter= 0;
	$response_array = array("ghv");
	
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
	
	if(strcmp($inputFileName, "")!==0)
	{
           
		for($i=2;$i<=$arrayCount;$i++)
		{	
		    $flag         =1;
			$update_error ='';		
			
			$data['team_name']            = strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["A"]))), ENT_HTML5));
			$data['short_color'] 	      = strtolower( htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["B"])), ENT_HTML5));
			$data['socks_color'] 	      = strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["C"]))), ENT_HTML5));
			$data['team_limit']           = strtolower(htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["D"])), ENT_HTML5));
			
			
			$cdata['competition_name']    = strtolower(trim(mysqli_real_escape_string($db_con,$allDataInSheet[$i]["E"])));
			$crow                         = checkExist('tbl_competition',$cdata);
			$c_data['competition_id']     = $crow['competition_id'];
				
			$data['team_created']         = $datetime;
			$data['team_created_by']      = $uid;
			
			if($data['competition_name']!="" && $data['start_date']!="" && $data['end_date']!="" )
			{
				if(!isExist('tbl_team',array('team_name'=>$data['team_name'])))
				{
					$insert_id             = insert('tbl_team',$data);
					$c_data['team_id']     = $insert_id;
					$c_data['competition_created']         = $datetime;
			        $c_data['competition_created_by']      = $uid;
					$insert_id             = insert('tbl_competition_team',$c_data);
					
				}
			}
		}// for end  
		
		quit('Teams uploaded Successfully...!');
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}


if(isset($_POST['batch_file']))
{   
	$sourcePath 		= $_FILES['file']['tmp_name'];      // Storing source path of the file in a variable
	$inputFileName 		= $_FILES['file']['name']; 			// Target path where file is to be stored
	move_uploaded_file($sourcePath,'bulkupload/'.$inputFileName) ;
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$prod_id	= 0;
	$msg	= '';
	$insertion_flag	= 0;
	$counter= 0;
	$response_array = array("ghv");
	
	try 
	{
		$objPHPExcel = PHPExcel_IOFactory::load('bulkupload/'.$inputFileName);
	} 
	catch(Exception $e) 
	{
		die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	
	$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
	
	if(strcmp($inputFileName, "")!==0)
	{
           
		for($i=2;$i<=$arrayCount;$i++)
		{	
		    $flag         =1;
			$update_error ='';		
			
			$data['batch_name']      	= strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["A"]))), ENT_HTML5));
			$data['reg_start_date'] 	= strtolower( htmlspecialchars(trim(str_replace("'","",$allDataInSheet[$i]["B"])), ENT_HTML5));
			$data['reg_end_date']	    = strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["C"]))), ENT_HTML5));
			 
			$data['start_date']    		= strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["D"]))), ENT_HTML5));
			
			$data['end_date']       	= strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["E"]))), ENT_HTML5));
			$data['batch_status']    	= 1;
			$data['batch_created']  	= $datetime;
			$data['batch_created_by']   = $uid;
			$data['batch_limit']    	= 10;
			$days                   	= trim(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["F"]))), ENT_HTML5));
			$timing 					= strtolower(htmlspecialchars(trim(str_replace("'","",trim($allDataInSheet[$i]["G"]))), ENT_HTML5));
			               
			
			if($data['batch_name']!="")
			{
				if(!isExist('tbl_batches',array('batch_name'=>$data['batch_name'])))
				{ 
					$insert_id = insert('tbl_batches',$data);
					if($days!="")
					{
						$days     = explode('/',$days);
						$days_arr = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
						foreach($days_arr as $day)
						{
						  
							if(in_array($day,$days))
							{
								
								$ddata['batch_id']   = $insert_id;
								$ddata['batch_day']  = $day;
								$ddata['batch_time'] = $timing;
								$ddata['created']    = $datetime;
								$ddata['created_by'] = $uid;
								insert('tbl_batch_time',$ddata);
								
							}
						}
						
					}
				
				}
			}
		}// for end  
		
		quit('Batch uploaded Successfully...!',1);
	}
	else
	{
		echo 'Try to upload Different File';
		exit();
	}
	echo json_encode($response_array);
}
?>
