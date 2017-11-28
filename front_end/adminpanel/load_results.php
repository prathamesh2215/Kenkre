<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];



if((isset($obj->load_results)) == "1" && isset($obj->load_results))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;

	$ft_date 		= $obj->ft_date;	
	$ft_time		= $obj->ft_time;

	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT tf.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tf.created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tf.modified_by) AS name_midified_by 
							FROM `tbl_fixtures` AS tf WHERE 1=1 ";
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND created_by='".$uid."' ";
		}

		if($ft_date!="")
		{
			$sql_load_data .=" AND fixture_date='".$ft_date."'";
		}

		if($ft_time!="")
		{
			$sql_load_data .=" AND fixture_time_start like '%".$ft_time."%'";
		}

		if($search_text != "")
		{
			$sql_load_data .= " and (fixture_place like '%".$search_text."%' or fixture_date like '%".$search_text."%' ";
			$sql_load_data .= " or team_a like '%".$search_text."%' or 	team_b like '%".$search_text."%') ";	
		}

		//quit($sql_load_data);
		$data_count		  = dataPagination($sql_load_data.' GROUP BY fixture_date',$per_page,$start,$cur_page);	
		
		$sql_load_data   .= " GROUP BY fixture_date  ORDER BY fixture_date DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		

			$result_data  = "";	
			$result_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$result_data .= '<thead>';
    	  	$result_data .= '<tr>';
         	$result_data .= '<th style="text-align:center">Sr No.</th>';
			$result_data .= '<th style="text-align:center">Date</th>';
			$result_data .= '<th style="text-align:center">Time</th>';
			$result_data .= '<th style="text-align:center">Team A</th>';
			$result_data .= '<th style="text-align:center">Team A Point</th>';
			$result_data .= '<th style="text-align:center">Vs</th>';
			$result_data .= '<th style="text-align:center">Team B Point</th>';
			$result_data .= '<th style="text-align:center">Team B</th>';
			$result_data .= '<th style="text-align:center">Pool</th>';
			$result_data .= '<th style="text-align:center">Field</th>';
	        $dis = checkFunctionalityRight("view_results.php",3);
		
			if($dis)
			{			
				$result_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_results.php",1);
			
			if($edit)
			{			
				$result_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_results.php",2);
			
			if($delete)
			{			
				$result_data .= '<th style="text-align:center"><div style="text-align:center">';
				$result_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$result_data .= '</tr>';
      		$result_data .= '</thead>';
      		$result_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$result_data .= '<tr>';	

	    	  	$sql_get_fixture = " SELECT * FROM tbl_fixtures WHERE fixture_date='".$row_load_data['fixture_date']." ' ";
	    	  	if($ft_time!="")
				{
					$sql_get_fixture .=" AND fixture_time_start like '%".$ft_time."%'";
				}
	    	  	if($search_text != "")
				{
					$sql_get_fixture .= " and (fixture_place like '%".$search_text."%' or fixture_date like '%".$search_text."%' ";
					$sql_get_fixture .= " or team_a like '%".$search_text."%' or 	team_b like '%".$search_text."%') ";	
				}
	    	  	$res_get_fixture = mysqli_query($db_con,$sql_get_fixture) or die(mysqli_error($db_con));
	    	  	$num_get_fixture = mysqli_num_rows($res_get_fixture);

				$result_data .= '<td style="text-align:center;vertical-align:middle" rowspan="'.$num_get_fixture.'">'.++$start_offset.'</td>';				
			   

			   	$result_data .= '<td rowspan="'.$num_get_fixture.'" style="text-align:center;vertical-align:middle">
			   	               <input type="button" value="'.ucwords($row_load_data['fixture_date']).'" class="btn-link" id="'.$row_load_data['fixture_id'].'" onclick="addMoreFixture1(this.id,\'view\');"></td>';

			   $i=0;
			 
			   while($row = mysqli_fetch_array($res_get_fixture))
			   {

			   		if($i!=0)
			   		{
			   			$result_data .='<tr>';
			   			$i++;
			   		}


					
					   // $result_data .= '<td  style="text-align:center">'.$row['fixture_time_start'].'</td>';
					   $result_data .= '<td style="text-align:center">'.htmlentities($row['fixture_time_start']).' to '.htmlentities($row['fixture_time_end']).'</td>';
						//   quit($sql_get_fixture);

					   			$result_data .= '<td style="text-align:center">';
					   			// query for getting the team A name 
					    		$sql_get_team_a_name = " SELECT * FROM tbl_team WHERE team_id = '".$row['team_a']."' ";
					    		$res_get_team_a_name = mysqli_query($db_con, $sql_get_team_a_name) or die(mysqli_error($db_con));
					    		$row_get_team_a_name = mysqli_fetch_array($res_get_team_a_name);
					    		$team_a_name = $row_get_team_a_name['team_name'];

					   			// $result_data .= strtoupper($row['team_a']);
					   			$result_data .= strtoupper($team_a_name);
					   			$result_data .= '</td>';



 								//quit('<td  style="text-align:center">'.ucwords($row['fixture_time_start']).'
					          	//           to '.ucwords($row['fixture_time_end']).'</td>');	
					   			// point start

					   			$point_row = checkExist('tbl_results' ,array('fixture_id'=>$row['fixture_id']));

					   			$result_data .= '<td style="text-align:center">
					   			<input type="text" value="'.@$point_row['team_a_point'].'" onchange="updatePoint('.$row['fixture_id'].',\'team_a\',this.value);"  class="input-small" />
					   			</td>';		
								$result_data .= '<td style="text-align:center">Vs</td>';
								$result_data .= '<td style="text-align:center">
								<input type="text" value="'.@$point_row['team_b_point'].'" onchange="updatePoint('.$row['fixture_id'].',\'team_b\',this.value);" class="input-small" />
								</td>';
								// point end


								// $result_data .= '<td style="text-align:center">'.strtoupper($row['team_b']).'</td>';		
								$result_data .= '<td style="text-align:center">';
								
								$sql_get_team_b_name = " SELECT * FROM tbl_team WHERE team_id = '".$row['team_b']."' ";
					    		$res_get_team_b_name = mysqli_query($db_con, $sql_get_team_b_name) or die(mysqli_error($db_con));
					    		$row_get_team_b_name = mysqli_fetch_array($res_get_team_b_name);
					    		$team_b_name = $row_get_team_b_name['team_name'];

								$result_data .= strtoupper($team_b_name);
								$result_data .= '</td>';	
									
								$result_data .= '<td style="text-align:center">'.strtoupper($row['pool']).'</td>';
								$result_data .= '<td style="text-align:center">'.strtoupper($row['field']).'</td>';
								
								$dis = checkFunctionalityRight("view_results.php",3);
							
								if($dis)
								{					
									$result_data .= '<td style="text-align:center">';					
									if($row['status']!='0')
									{
										$result_data .= '<input type="button" value="Active" id="'.$row['fixture_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
									}
									else
									{
										$result_data .= '<input type="button" value="Inactive" id="'.$row['fixture_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
									}
									$result_data .= '</td>';
								}
								$edit = checkFunctionalityRight("view_results.php",1);
							
								if($edit)
								{				
									$result_data .= '<td style="text-align:center">';
									$result_data .= '<input type="button" value="Edit" id="'.$row['fixture_id'].'" class="btn-warning" onclick="addMoreFixture(this.id,\'edit\');"></td>';												
								}
								$delete = checkFunctionalityRight("view_results.php",2);
								
								if($delete)
								{			
								
									if($row['status']==0)
									{
										$result_data .= '<td style="text-align:center">';					
									    $result_data .= '<input type="button" value="Inactive" id="'.$row['fixture_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
									    $result_data .= '</td>';
									}
									else
									{
										$result_data .= '<td><div class="controls" align="center">';
										$result_data .= '<input type="checkbox" value="'.$row['fixture_id'].'" id="batch'.$row['fixture_id'].'" name="batch'.$row['cefixture_idnter_id'].'" class="css-checkbox batch">';
										$result_data .= '<label for="batch'.$row['fixture_id'].'" class="css-label"></label>';
										$result_data .= '</div></td>';	
									}
								}
						$result_data .='<tr>'; 	

			   }// while end

				
	          	$result_data .= '</tr>';															
			}

      		$result_data .= '</tbody>';
      		$result_data .= '</table>';	
			$result_data .= $data_count;

			$response_array = array("Success"=>"Success","resp"=>$result_data);				
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available".$sql_load_data);
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}


//---------------This is used for status change--------------------------------
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$fixture_id			= $obj->competition_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$data['status']      = $curr_status;
	$data['modified_by']             = $logged_uid;
	$data['modified_date']           = $datetime;
	$res = update('tbl_fixtures',$data,array('fixture_id'=>$fixture_id));
	
	if($res)
	{
		
			quit('Success',1);
		
	}
	else
	{
		quit('Please try lettre...!');
	}
}

//------------------This is used for delete data---------------------------------
if((isset($obj->updatepoint)) == "1" && isset($obj->updatepoint))
{

	$team        = $obj->team;
	$fixture_id  = $obj->fixture_id;
	$point       = $obj->point;

	$num =  isExist('tbl_results' ,array('fixture_id'=>$fixture_id));
	if($num)
	{
		$data['fixture_id']    = $fixture_id;
		$data[$team.'_point']  = $point;
		$data['modified_by']    = $uid;
		$data['modified_date']  = $datetime;
		update('tbl_results',$data,array('fixture_id'=>$data['fixture_id']));
		quit('Success',1);
	}
	else
	{
		$data['fixture_id']    = $fixture_id;
		$data[$team.'_point']  = $point;
		$data['created_by']    = $uid;
		$data['created_date']  = $datetime;
		insert('tbl_results',$data);
		quit('Success',1);
	}
}

// ==========================================================================


// ====================================================================
// START : Update The Result table for win team
// ====================================================================

if((isset($obj->updateResults)) == '1' && (isset($obj->updateResults)))
{
	$fixture_id = $obj->fixture_id;

	$data = array();

	$data['is_draw']   = 0;
	$data['win_team']  = 0;
	$data['lose_team'] = 0;

	if($fixture_id != '')
	{
		// query for getting the team ids from tbl_fixtures
		$sql_get_team_ids = " SELECT * FROM tbl_fixtures WHERE fixture_id = '".$fixture_id."' ";
		$res_get_team_ids = mysqli_query($db_con, $sql_get_team_ids) or die(mysqli_error($db_con));
		$num_get_team_ids = mysqli_num_rows($res_get_team_ids);

		if($num_get_team_ids != 0)
		{
			$row_get_team_ids = mysqli_fetch_array($res_get_team_ids);

			$team_a_id = $row_get_team_ids['team_a'];	// id of team A
			$team_b_id = $row_get_team_ids['team_b'];	// id Of team B

			// getting the points of the each team from the result table
			$sql_get_points = " SELECT * FROM tbl_results WHERE fixture_id = '".$fixture_id."' ";
			$res_get_points = mysqli_query($db_con, $sql_get_points) or die(mysqli_error($db_con));
			$num_get_points = mysqli_num_rows($res_get_points);

			if($num_get_points != 0)
			{
				$row_get_points = mysqli_fetch_array($res_get_points);

				$team_a_points = $row_get_points['team_a_point'];
				$team_b_points = $row_get_points['team_b_point'];

				if($team_a_points == $team_b_points)
				{
					$data['is_draw']   = '1';
				}
				elseif($team_a_points < $team_b_points)
				{
					$data['win_team']  = $team_b_id;
					$data['lose_team'] = $team_a_id;
				}
				else
				{
					$data['win_team']  = $team_a_id;
					$data['lose_team'] = $team_b_id;
				}

				$res_update_result = update('tbl_results',$data,array('fixture_id'=>$fixture_id));

				if($res_update_result)
				{
					quit('Success', 1);
				}
				else
				{
					quit('Error');					
				}
			}
			else
			{
				quit('Error');
			}
		}
		else
		{
			quit('Error');
		}

	}
	else
	{
		quit('Error');
	}
	exit();
}

// ====================================================================
// END : Update The Result table for win team
// ====================================================================



?>
