<?php
	include('includes/db_con.php');
	include('includes/query-helper.php');

	function totalGoalCount($team_id)
	{
		global $db_con;
		// Query For getting the total number of goals
		// For Team A
		$sql_get_goal_count_while_a = " SELECT SUM(tr.team_a_point) AS team_a_points FROM tbl_fixtures AS tf INNER JOIN tbl_results AS tr ON tf.fixture_id = tr.fixture_id WHERE tf.team_a = '".$team_id."' ";
		$res_get_goal_count_while_a = mysqli_query($db_con, $sql_get_goal_count_while_a) or die(mysqli_error($db_con));
		$row_get_goal_count_while_a = mysqli_fetch_array($res_get_goal_count_while_a);

		$team_a_num_goals = $row_get_goal_count_while_a['team_a_points'];

		// For Team B
		$sql_get_goal_count_while_b = " SELECT SUM(tr.team_b_point) AS team_b_points FROM tbl_fixtures AS tf INNER JOIN tbl_results AS tr ON tf.fixture_id = tr.fixture_id WHERE tf.team_b = '".$team_id."' ";
		$res_get_goal_count_while_b = mysqli_query($db_con, $sql_get_goal_count_while_b) or die(mysqli_error($db_con));
		$row_get_goal_count_while_b = mysqli_fetch_array($res_get_goal_count_while_b);

		$team_b_num_goals = $row_get_goal_count_while_b['team_b_points'];

		// Total Count
		$total_num_goals = (int)$team_a_num_goals + (int)$team_b_num_goals;

		return $total_num_goals;
	}

	function getArrayAgainstTeamA_B($team_id, $team)
	{
		global $db_con;

		$select_col_name = '';
		$where_col_name  = '';
		if($team == 'A')
		{
			$select_col_name = 'team_b';
			$where_col_name  = 'team_a';
		}
		else
		{
			$select_col_name = 'team_a';
			$where_col_name  = 'team_b';
		}

		$arr_of_against_team = array();

		$sql_get_against_team_arr = " SELECT tf.".$select_col_name." AS team_id FROM tbl_fixtures AS tf INNER JOIN tbl_results AS tr ON tf.fixture_id = tr.fixture_id WHERE tf.".$where_col_name." = '".$team_id."' ";
		$res_get_against_team_arr = mysqli_query($db_con, $sql_get_against_team_arr) or die(mysqli_error($db_con));
		$num_get_against_team_arr = mysqli_num_rows($res_get_against_team_arr);

		if($num_get_against_team_arr != 0)
		{
			while ($row_get_against_team_arr = mysqli_fetch_array($res_get_against_team_arr)) 
			{
				$arr_of_against_team[] = $row_get_against_team_arr['team_id'];
			}
		}

		return $arr_of_against_team;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Design Lots">
		<link rel="icon" href="favicon.ico">
		
		<title>Points Leaderboard | Kenkre Sport Club</title>
		
		
		<?php include('st-head.php'); ?>

		<style type="text/css">
			
			.tbl th,th{
				text-align: center;
			}
			@media only screen and (max-width: 767px) {
		    				td:nth-of-type(1):before { content: "Competitor"; }
		    				td:nth-of-type(2):before { content: "Position"; }
		    				td:nth-of-type(3):before { content: "Wins"; }
		    				td:nth-of-type(4):before { content: "Draws"; }
		    				td:nth-of-type(5):before { content: "Losses"; }
		    				td:nth-of-type(6):before { content: "%"; }
		    				}
		</style>
	</head>
	
	
	
	<body class="">
		<?php include('st-header.php'); ?>	
		
        <!-- Start Main Content -->
		<main class="main-content">
		    <div class="container body-content">
		    	<div class="row">
		    		<div class="col-sm-12 main-column fixtures-page">
		    			
		    			<div class="page-header">
		    				<h2>Points Leaderboard</h2>
		    			</div> 
	    			
		    			<div class="events-filter">
		    				<?php include('st-eventfilter.php'); ?>
		    			</div>

		    			<?php
    					// Query For getting all the Pools
    					$sql_get_pools 	= " SELECT DISTINCT(`pool`) FROM `tbl_fixtures` ORDER BY `tbl_fixtures`.`pool`  ASC ";
    					$res_get_pools	= mysqli_query($db_con, $sql_get_pools) or die(mysqli_fetch_array($db_con));
    					$num_get_pools  = mysqli_num_rows($res_get_pools);

    					if($num_get_pools != 0)
    					{
    						while ($row_get_pools = mysqli_fetch_array($res_get_pools)) 
    						{
    							?>
	    						<table class="table-striped table-responsive table-hover table-leaderboard table-tops table-top-5 tbl" >
	    							<thead>
	    								<tr>
	    									<th style="text-align: center;background-color: #eee;" colspan="10" class="head1">
	    										<?php echo strtoupper($row_get_pools['pool']); ?>
	    									</th>
	    								</tr>
	    								<tr>
	    									<th class="head1">Position</th>
					    					<th class="head1">Team</th>
					    					<th class="head1">P</th>
					    					<th class="head1">W</th>
					    					<th class="head1">D</th>
					    					<th class="head1">L</th>
					    					<th class="head1">F</th>
					    					<th class="head1">A</th>
					    					<th class="head1">GD</th>
					    					<th class="head1">Pts</th>
	    								</tr>
	    							</thead>
	    							<tbody>
	    								<?php
	    								// Query For getting the Team Name and its respective points
										$sql_get_team_details = " SELECT *  FROM `tbl_team` WHERE `pool` LIKE '".$row_get_pools['pool']."' ";
										$res_get_team_details = mysqli_query($db_con, $sql_get_team_details) or die(mysqli_error($db_con));
										$num_get_team_details = mysqli_num_rows($res_get_team_details);

	    								$startOffset = 0;

	    								if($num_get_team_details != 0)
	    								{
	    									while ($row_get_team_details = mysqli_fetch_array($res_get_team_details)) 
	    									{
	    										?>
		    									<tr>
		    										<td style="text-align: center;"><?php echo ++$startOffset; ?></td>
		    										<td>
		    											<img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />
		    											<?php echo strtoupper($row_get_team_details['team_name']); ?>
		    										</td>
		    										<td style="text-align: center;">
		    											<?php
		    											// Query For Getting the Total Number Of Matches Played by This team with Others
		    											$sql_num_played_matches = " SELECT tr.* FROM tbl_fixtures AS tf INNER JOIN tbl_results AS tr ON tf.fixture_id = tr.fixture_id WHERE tf.pool = '".$row_get_pools['pool']."' AND (tf.team_a = '".$row_get_team_details['team_id']."' OR tf.team_b = '".$row_get_team_details['team_id']."') ";
		    											$res_num_played_matches = mysqli_query($db_con, $sql_num_played_matches) or die(mysqli_error($db_con));
		    											$num_num_played_matches	= mysqli_num_rows($res_num_played_matches);

		    											echo $num_num_played_matches;
		    											?>
		    										</td>	<!-- Played -->
							    					<td style="text-align: center;">
							    						<?php
							    						// Query For getting the Win Count of the Team
							    						$sql_get_win_count = " SELECT * FROM `tbl_results` WHERE `win_team`='".$row_get_team_details['team_id']."' ";
							    						$res_get_win_count = mysqli_query($db_con, $sql_get_win_count) or die(mysqli_error($db_con));
							    						$num_get_win_count = mysqli_num_rows($res_get_win_count);

							    						echo $num_get_win_count;

							    						?>
							    					</td>	<!-- Win -->
							    					<td style="text-align: center;">
							    						<?php
							    						// Team A
							    						$sql_draw_count_a = " SELECT tr.* FROM tbl_fixtures AS tf INNER JOIN tbl_results AS tr ON tf.fixture_id = tr.fixture_id WHERE tf.team_a = '".$row_get_team_details['team_id']."' AND tr.is_draw = '1' ";
							    						$res_draw_count_a = mysqli_query($db_con, $sql_draw_count_a) or die(mysqli_error($db_con));
							    						$num_draw_count_a = mysqli_num_rows($res_draw_count_a);

							    						// Team B
							    						$sql_draw_count_b = " SELECT tr.* FROM tbl_fixtures AS tf INNER JOIN tbl_results AS tr ON tf.fixture_id = tr.fixture_id WHERE tf.team_b = '".$row_get_team_details['team_id']."' AND tr.is_draw = '1' ";
							    						$res_draw_count_b = mysqli_query($db_con, $sql_draw_count_b) or die(mysqli_error($db_con));
							    						$num_draw_count_b = mysqli_num_rows($res_draw_count_b);

							    						echo $total_draw_count = (int)$num_draw_count_a + (int)$num_draw_count_b;
							    						?>
							    					</td>	<!-- Draw -->
							    					<td style="text-align: center;">
							    						<?php
							    						// Query For getting the Lose Count of the Team
							    						$sql_get_lose_count = " SELECT * FROM `tbl_results` WHERE `lose_team`='".$row_get_team_details['team_id']."' ";
							    						$res_get_lose_count = mysqli_query($db_con, $sql_get_lose_count) or die(mysqli_error($db_con));
							    						$num_get_lose_count = mysqli_num_rows($res_get_lose_count);

							    						echo $num_get_lose_count;

							    						?>
							    					</td>	<!-- Lose -->
							    					<td style="text-align: center;">
							    						<?php
							    						echo $total_num_goals = totalGoalCount($row_get_team_details['team_id']);
							    						?>
							    					</td>	<!-- F [Number Of Goals Of this team] -->
							    					<td style="text-align: center;">
							    						<?php
							    						$arr_team_a_against = array();
							    						$arr_team_b_against = array();
							    						$main_array_against = array();

							    						$arr_team_a_against = getArrayAgainstTeamA_B($row_get_team_details['team_id'], 'A');
							    						$arr_team_b_against = getArrayAgainstTeamA_B($row_get_team_details['team_id'], 'B');

							    						$main_array_against = array_merge($arr_team_a_against, $arr_team_b_against);

							    						$total_num_goal_against = 0;
						    							$team_a_against = 0;
						    							$team_b_against = 0;

							    						foreach ($main_array_against as $team_id) 
							    						{

							    							// Team A
							    							$sql_get_goal_count_against_a = " SELECT SUM(tr.team_a_point) team_points FROM tbl_fixtures AS tf INNER JOIN tbl_results AS tr ON tf.fixture_id = tr.fixture_id WHERE tf.team_a = '".$team_id."' AND tf.team_b = '".$row_get_team_details['team_id']."' ";
							    							$res_get_goal_count_against_a = mysqli_query($db_con, $sql_get_goal_count_against_a) or die(mysqli_error($db_con));
							    							$num_get_goal_count_against_a = mysqli_num_rows($res_get_goal_count_against_a);

							    							if($num_get_goal_count_against_a != 0)
							    							{
							    								while($row_get_goal_count_against_a = mysqli_fetch_array($res_get_goal_count_against_a))
							    								{
							    									if($row_get_goal_count_against_a['team_points'] != null)
							    									{
																		$team_a_against += $row_get_goal_count_against_a['team_points'];	
							    									}	
							    								}
							    							}

							    							// Team B
							    							$sql_get_goal_count_against_b = " SELECT SUM(tr.team_b_point) team_points FROM tbl_fixtures AS tf INNER JOIN tbl_results AS tr ON tf.fixture_id = tr.fixture_id WHERE tf.team_a = '".$row_get_team_details['team_id']."' AND tf.team_b = '".$team_id."' ";
							    							$res_get_goal_count_against_b = mysqli_query($db_con, $sql_get_goal_count_against_b) or die(mysqli_error($db_con));
							    							$num_get_goal_count_against_b = mysqli_num_rows($res_get_goal_count_against_b);

							    							if($num_get_goal_count_against_b != 0)
							    							{
							    								while($row_get_goal_count_against_b = mysqli_fetch_array($res_get_goal_count_against_b))
							    								{
							    									if($row_get_goal_count_against_b['team_points'] != null)
							    									{
																		$team_b_against += $row_get_goal_count_against_b['team_points'];	
							    									}	
							    								}
							    							}
							    						}
							    						echo $total_num_goal_against = (int)$team_a_against + (int)$team_b_against;
							    						// echo $total_num_goal_against .' = '. (int)$team_a_against .' + '. (int)$team_b_against.'<br>';
							    						?>
							    					</td>	<!-- A [Number Of Goals of opposite team] -->
							    					<td style="text-align: center;">
							    						<?php
							    						echo $goal_diff = (int)$total_num_goals - (int)$total_num_goal_against;
							    						?>
							    					</td>	<!-- GD [Goal Difference] -->
							    					<td style="text-align: center;">
							    						<?php
							    						echo (int)$num_get_win_count * 3;
							    						?>
							    					</td>	<!-- Pts [Points] [For Win 3 points] -->
		    									</tr>
		    									<?php
	    									}
		    							}
	    								else
	    								{
	    									?>
	    									<tr>
	    										<td colspan="10">
		    										NO TEAMS FOUND
		    									</td>
	    									</tr>
	    									<?php
	    								}
	    								?>
	    							</tbody>
	    						</table>
	    						<?php
    						}
    					}
    					else
    					{
    						?>
    						<td>No DATA FOUND</td>
    						<?php
    					}


    					?>
					</div><!--/.main-column -->
		    	</div><!-- /.container -->
            </div>
		</main>
        <!-- /.main-content -->
		
		<?php include('st-footer.php'); ?>
		<?php include('st-javascript.php'); ?>
		
		
	</body>


</html>