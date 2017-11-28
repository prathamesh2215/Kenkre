<?php
	include('includes/db_con.php');
 	include('includes/query-helper.php');

 	$json = file_get_contents('php://input');
	$obj = json_decode($json);

	if((isset($obj->getFixtures)) == '1' && (isset($obj->getFixtures)))
	{
		$selectedDate       = $obj->selectedDate;
		$n                  = 1;
		
		$game_fixtures_data = '';

		$game_fixtures_data .= '<tr>';
			$game_fixtures_data .= '<th class="head1" colspan="9" style="text-align: center;">';
				$fixture_date = explode('-',$selectedDate);
				$game_fixtures_data .= $fixture_date[2].'.'.$fixture_date[1].'.'.$fixture_date[0].'';
			$game_fixtures_data .= '</th>';
		$game_fixtures_data .= '</tr>';

		$sql_get_fixtures	= " SELECT * FROM tbl_fixtures WHERE status =1 AND  fixture_date='".$selectedDate."' GROUP By fixture_time_start ORDER By fixture_time_start ASC ";
		$res_get_fixtures	= mysqli_query($db_con, $sql_get_fixtures) or die(mysqli_error($db_con));
		$num_get_fixtures	= mysqli_num_rows($res_get_fixtures);

		$f =0;

		while($row_get_fixtures = mysqli_fetch_array($res_get_fixtures))
		{
			$game_fixtures_data .= '<tr>';

				$sql_get_fixtures1	= " SELECT * FROM tbl_fixtures WHERE status =1 AND  fixture_date='".$selectedDate."' AND fixture_time_start='".$row_get_fixtures['fixture_time_start']."' ORDER By fixture_time_start ASC ";
				$res_get_fixtures1	= mysqli_query($db_con, $sql_get_fixtures1) or die(mysqli_error($db_con));
				$num_get_fixtures1	= mysqli_num_rows($res_get_fixtures1);

				$color   = '#e0e0e0';

				if($f%2==0)
				{
					$color   = '#cccccc';
				}
				$f++;
				$game_fixtures_data .= '<td rowspan="'.$num_get_fixtures1.'"  style="vertical-align: middle;background-color: '.$color.'">'.$row_get_fixtures['fixture_time_start'].' TO '.$row_get_fixtures['fixture_time_end'].'</td>';

				$l = 0;

				while($row_get_fixtures1 = mysqli_fetch_array($res_get_fixtures1))
				{
					if($l!=0)
			 		{
			 			$game_fixtures_data .= '<tr>';
			 			$l++;
			 		}

						$game_fixtures_data .= '<td style="vertical-align: middle;">'.$n++.'</td>'; 
						$game_fixtures_data .= '<td style="vertical-align: middle;">';
							$game_fixtures_data .= '<img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;" />';
								// Query For getting the name of the Team
    						 	$sql_get_team_name_a = " SELECT * FROM tbl_team WHERE team_id = '".$row_get_fixtures1['team_a']."' ";
    						 	$res_get_team_name_a = mysqli_query($db_con, $sql_get_team_name_a) or die(mysqli_error($db_con));
    						 	$row_get_team_name_a = mysqli_fetch_array($res_get_team_name_a);

    						 	$team_a_name = $row_get_team_name_a['team_name'];

								$game_fixtures_data .= strtoupper($team_a_name);
							$game_fixtures_data .= '</td>';
						$game_fixtures_data .= '<td  style="vertical-align: middle;">Vs</td>';
						$game_fixtures_data .= '<td style="vertical-align: middle;">';
							$game_fixtures_data .= '<img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;" />';
								// Query For getting the name of the Team
    						 	$sql_get_team_name_b = " SELECT * FROM tbl_team WHERE team_id = '".$row_get_fixtures1['team_b']."' ";
    						 	$res_get_team_name_b = mysqli_query($db_con, $sql_get_team_name_b) or die(mysqli_error($db_con));
    						 	$row_get_team_name_b = mysqli_fetch_array($res_get_team_name_b);

    						 	$team_b_name = $row_get_team_name_b['team_name'];

								$game_fixtures_data .= strtoupper($team_b_name);
							$game_fixtures_data .= '</td>';
						$game_fixtures_data .= '<td style="vertical-align: middle;">'.strtoupper($row_get_fixtures1['pool']).'</td>';
						$game_fixtures_data .= '<td  style="vertical-align: middle;">'.strtoupper($row_get_fixtures1['field']).'</td>';
					$game_fixtures_data .= '</tr>';
				}

			$game_fixtures_data .= '</tr>';
		}

		quit($game_fixtures_data, 1);
	}
?>