<?php
 include('includes/db_con.php');
 include('includes/query-helper.php');
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
		
		<title>Results | Kenkre Sport Club</title>
		
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
		    				<h2>Results</h2>
		    			</div>
		    			<div class="events-filter">
		    				<?php include('st-eventfilter.php'); ?>
		    			</div>
		    			<table class="table-striped tbl table-responsive table-hover table-leaderboard table-tops">
		    				
		    				<?php

		    					$sql  = " SELECT * FROM tbl_fixtures  as tf ";
		    					$sql .= " INNER JOIN tbl_results as tr ON tf.fixture_id=tr.fixture_id ";
		    					$sql .=" WHERE tf.status = 1 ";
		    					$sql .= " AND tr.team_a_point!='' AND tr.team_b_point !=''" ;
		    					$sql .=" GROUP By fixture_date ORDER By fixture_date DESC LIMIT 1";
		    					$result = query($sql);
		    					$num    = mysqli_num_rows($result);
		    					$n      = 1;
		    					if($num !=0)
		    					{?>
		    						<thead>
		    						<tr>
				    				    <th class="head1" colspan="9">
				    				    	<select name="ft_date" id="ft_date"  class="select2-me input-xlarge" data-rule-required="true" onChange="loadData(this.value);">
	                                        <option value="">Date</option>
	                                        <?php
	                                        
	                                          //  $sql =" SELECT DISTINCT fixture_date FROM tbl_fixtures WHERE status = 1 AND fixture_id IN (SELECT DISTINCT fixture_id FROM tbl_results)"
	                                        	$sql ="SELECT DISTINCT fixture_date FROM tbl_fixtures WHERE status = 1 ";
	                                        	$res = mysqli_query($db_con,$sql) or die(mysqli_error($db_con));
	                                        	while($row = mysqli_fetch_array($res))
	                                        	{
	                                        		echo '<option value="'.$row['fixture_date'].'">'.$row['fixture_date'].'</option>';
	                                        	}
	                                        ?>
	                                        </select></th>
				    					
				    				</tr>
				    				<tr>
				    				    <th class="head1">Time</th>
				    					<th class="head1">Match</th>
				    					<th class="head1" colspan="5">COMMUNITY CUP - ST. ANDREWS, BANDRA</th>
				    					<th class="head1">Pool</th>
				    					<th class="head1">Field</th>
				    				</tr>
									</thead>
									<tbody id="resContainer">
		    					<?php 
		    					}
		    					while($row = mysqli_fetch_array($result))
		    					{
		    						$i=0;
		    					?>
		    						<tr>
				    					<th class="head1" colspan="9" style="text-align: center;">
				    						<?php 
				    						 $fixture_date = explode('-',$row['fixture_date']);
				    						  echo $fixture_date[2].'.'.$fixture_date[1].'.'.$fixture_date[0]; ?>
				    					</th>
				    				</tr>
				    				
		    					<?php

		    						$sql_get  ="SELECT * FROM tbl_fixtures as tf ";
		    						$sql_get .= " INNER JOIN tbl_results as tr ON tf.fixture_id=tr.fixture_id ";
		    						$sql_get .=" WHERE tr.status =1 AND  fixture_date='".$row['fixture_date']."' ";
		    						$sql_get .= " AND tr.team_a_point!='' AND tr.team_b_point !=''" ;
		    						$sql_get .=" GROUP By fixture_time_start ORDER By tf.fixture_id ASC";
		    						$res = query($sql_get);
		    						$num = mysqli_num_rows($res);
		    						$f =0;
		    						while($frow = mysqli_fetch_array($res))
		    						{
		    							?>
		    						 <tr>

		    						 	<?php 

		    						 		$sql_tget ="SELECT * FROM tbl_fixtures as tf ";
		    						 		$sql_tget .= " INNER JOIN tbl_results as tr ON tf.fixture_id=tr.fixture_id ";
		    						 		$sql_tget .= " WHERE tr.status =1 AND  fixture_date='".$row['fixture_date']."' ";
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
		    						 	?>
		    						 <td rowspan="<?php echo $num; ?>"  style="vertical-align: middle;background-color: <?php echo $color; ?>">
		    						     <?php echo strtoupper($frow['fixture_time_start']) ?> to <?php echo strtoupper($frow['fixture_time_end']); ?></td>

		    						 <?php
		    						 	$l =0;
		    						 	while($r = mysqli_fetch_array($fres))
		    						 	{
		    						 		if($l!=0)
		    						 		{
		    						 			echo '<tr>';
		    						 			$l++;
		    						 		}

		    						 		?>
											 <td style="vertical-align: middle;"><?php echo $n++; ?></td>
				    						
				    						 <td style="vertical-align: middle;">
				    						 	<img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;" />
				    						 	<?php
				    						 	// Query For getting the name of the Team
				    						 	$sql_get_team_name_a = " SELECT * FROM tbl_team WHERE team_id = '".$r['team_a']."' ";
				    						 	$res_get_team_name_a = mysqli_query($db_con, $sql_get_team_name_a) or die(mysqli_error($db_con));
				    						 	$row_get_team_name_a = mysqli_fetch_array($res_get_team_name_a);

				    						 	$team_a_name = $row_get_team_name_a['team_name'];
				    						 	?>

				    						 	<?php echo strtoupper($team_a_name) ?>
				    						 </td>

				    						 <?php
				    						 	$point  = checkExist('tbl_results',array('fixture_id'=>$r['fixture_id']));
				    						 ?>
				    						 <td  style="vertical-align: middle;"><?php echo @$point['team_a_point']; ?></td>
				    						 <td  style="vertical-align: middle;">Vs</td>
				    						 <td  style="vertical-align: middle;"><?php echo @$point['team_b_point']; ?></td>
				    						 <td style="vertical-align: middle;">
				    						 	<img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;" />
				    						 	<?php
				    						 	// Query For getting the name of the Team
				    						 	$sql_get_team_name_b = " SELECT * FROM tbl_team WHERE team_id = '".$r['team_b']."' ";
				    						 	$res_get_team_name_b = mysqli_query($db_con, $sql_get_team_name_b) or die(mysqli_error($db_con));
				    						 	$row_get_team_name_b = mysqli_fetch_array($res_get_team_name_b);

				    						 	$team_b_name = $row_get_team_name_b['team_name'];
				    						 	?>
				    						 	
				    						 	<?php echo strtoupper($team_b_name) ?>
				    						 </td>
				    						 <td style="vertical-align: middle;"><?php echo strtoupper($r['pool']); ?></td>
				    						 <td  style="vertical-align: middle;"><?php echo strtoupper($r['field']); ?></td>
				    						 </tr>
		    						 <?php
		    						   }
		    						 ?>
		    						
		    						<?php
   
		    						}
		    						?>
									   						
		    				   <?php
		    				    }
		    				    ?>
		    				</tbody>
		    			</table>
					</div>
			   </div>
            </div>   
		</main>
        <!-- /.main-content -->
		
		<?php include('st-footer.php'); ?>
	
    	<?php include('st-javascript.php'); ?>

		<script type="text/javascript">
			
			function loadData(fdate)
			{
				var sendInfo 	= {"fdate":fdate,"getResult":1};
				var area_status = JSON.stringify(sendInfo);								
				$.ajax({
					url: "load_results.php?",
					type: "POST",
					data: area_status,
					contentType: "application/json; charset=utf-8",						
					success: function(response) 
					{			
						data = JSON.parse(response);
						if(data.Success == "Success") 
						{	 
									
							$('#resContainer').html(data.resp);
							
						} 
						else 
						{
							$("#model_body").html('<span style="style="color:#F00;">'+data.resp+'</span>');
							$('#error_model').modal('toggle');
							loading_hide();					
						}
					},
					error: function (request, status, error) 
					{
						$("#model_body").html('<span style="style="color:#F00;">'+request.responseText+'</span>');
						$('#error_model').modal('toggle');
						loading_hide();
					},
					complete: function()
					{
						loading_hide();	
					}
				});	
			}


		</script>
	</body>

</html>