<?php
	include("include/db_con.php");
	include("include/query-helper.php");
	include("include/routines.php");

	$sql_get_result_data = " SELECT * FROM `tbl_results` WHERE 1 ";
	$res_get_result_data = mysqli_query($db_con, $sql_get_result_data) or die(mysqli_error($db_con));
	$num_get_result_data = mysqli_num_rows($res_get_result_data);

	if($num_get_result_data != 0) 
	{
		while($row_get_result_data = mysqli_fetch_array($res_get_result_data))
		{
			$fixture_id = $row_get_result_data['fixture_id'];

			$data['is_draw']   = 0;
			$data['win_team']  = 0;
			$data['lose_team'] = 0;

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

					echo 'Team A : '.$team_a_points = $row_get_points['team_a_point'];
					echo '<br>';
					echo 'Team B : '.$team_b_points = $row_get_points['team_b_point'];
					echo '<br>==========================<br>';

					if($team_a_points != '' && $team_b_points != '')
					{
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

						// if($res_update_result)
						// {
						// 	quit('Success', 1);
						// }
						// else
						// {
						// 	quit('Error');					
						// }
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
	}
	else
	{
		quit('Error');
	}
	exit();
?>