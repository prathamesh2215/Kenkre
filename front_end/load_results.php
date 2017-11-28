<?php
 include('includes/db_con.php');
 include('includes/query-helper.php');
$json = file_get_contents('php://input');
$obj = json_decode($json);




if((isset($obj->getResult)) == "1" && isset($obj->getResult))
{
	
	$fdate 			= $obj->fdate;
	

	$sql  = " SELECT * FROM tbl_fixtures  as tf ";
	$sql .= " INNER JOIN tbl_results as tr ON tf.fixture_id=tr.fixture_id ";
	$sql .=" WHERE tf.status = 1 AND fixture_date='".$fdate."'";
	$sql .= " AND tr.team_a_point!='' AND tr.team_b_point !=''" ;
	$sql .=" GROUP By fixture_date ORDER By fixture_date DESC LIMIT 1";
	$result = query($sql);
	$num    = mysqli_num_rows($result);
	$n      = 1;
	
	$i=0;
	$data ='
		<tr>
			<th class="head1" colspan="9" style="text-align: center;">';
			
			 $fixture_date = explode('-',$fdate); 

			  $data .=$fixture_date[2].'.'.$fixture_date[1].'.'.$fixture_date[0].'
			</th>
		</tr>';

	$sql_get  ="SELECT * FROM tbl_fixtures as tf ";
	$sql_get .= " INNER JOIN tbl_results as tr ON tf.fixture_id=tr.fixture_id ";
	$sql_get .=" WHERE tr.status =1 AND  fixture_date='".$fdate."' ";
	$sql_get .= " AND tr.team_a_point!='' AND tr.team_b_point !=''" ;
	$sql_get .=" GROUP By fixture_time_start ORDER By fixture_time_start ASC";
	$res = query($sql_get);
	$num = mysqli_num_rows($res);
	$f =0;
	while($frow = mysqli_fetch_array($res))
	{
		    							
		$data .='<tr>';

		$sql_tget ="SELECT * FROM tbl_fixtures as tf ";
 		$sql_tget .= " INNER JOIN tbl_results as tr ON tf.fixture_id=tr.fixture_id ";
 		$sql_tget .= " WHERE tr.status =1 AND  fixture_date='".$fdate."' ";
 		$sql_tget .= " AND tr.team_a_point!='' AND tr.team_b_point !=''" ;
 		$sql_tget .= " AND fixture_time_start='".$frow['fixture_time_start']."' ORDER By fixture_time_start ASC";
		$fres = query($sql_tget);
		$num = mysqli_num_rows($fres);
		$color   = '#e0e0e0';
		if($f%2==0)
		{
			$color   = '#cccccc';
		}
		$f++;
		    						 
		$data .=' <td rowspan='.$num.'"  style="vertical-align: middle;background-color:'.$color.'">8.00 TO 8.30AM</td>';

		    					
	 	$l =0;
	 	while($r = mysqli_fetch_array($fres))
	 	{
	 		if($l!=0)
	 		{
	 			echo '<tr>';
	 			$l++;
	 		}

	 			$data .=' <td style="vertical-align: middle;">'.$n++.'</td>';
				
				$data .='  <td style="vertical-align: middle;">
				 	<img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;" />';
				 	// Query For getting the name of the Team
				 	$sql_get_team_name_a = " SELECT * FROM tbl_team WHERE team_id = '".$r['team_a']."' ";
				 	$res_get_team_name_a = mysqli_query($db_con, $sql_get_team_name_a) or die(mysqli_error($db_con));
				 	$row_get_team_name_a = mysqli_fetch_array($res_get_team_name_a);

				 	$team_a_name = $row_get_team_name_a['team_name'];


				 	$data .=strtoupper($team_a_name);

				 $data .='</td>';

					    						 
				$point  = checkExist('tbl_results',array('fixture_id'=>$r['fixture_id']));
					    						 
				$data .='<td  style="vertical-align: middle;">'.@$point['team_a_point'].'</td>';
				$data .='  <td  style="vertical-align: middle;">Vs</td>';
				 $data .=' <td  style="vertical-align: middle;">'.@$point['team_b_point'].'</td>';


				$data .=' <td style="vertical-align: middle;">';
				 $data .=' <img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;" />';

				 // Query For getting the name of the Team
			 	$sql_get_team_name_b = " SELECT * FROM tbl_team WHERE team_id = '".$r['team_b']."' ";
			 	$res_get_team_name_b = mysqli_query($db_con, $sql_get_team_name_b) or die(mysqli_error($db_con));
			 	$row_get_team_name_b = mysqli_fetch_array($res_get_team_name_b);

			 	$team_b_name = $row_get_team_name_b['team_name'];
				 
				 $data .=strtoupper($team_b_name);
				 $data .= '</td>';
				 
				 $data .=' <td style="vertical-align: middle;">'.strtoupper($r['pool']).'</td>
				 <td  style="vertical-align: middle;">'.strtoupper($r['field']).'</td>';
				$data .='</tr>';
		    						
		}
		    						 
		$data .='</tr>';
	}
		    						
		quit($data,1);
}

?>
