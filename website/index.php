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
		
		<title>Home | Kenkre Sport Club</title>
		
		<?php include('st-head.php'); ?>
		
		<style type="text/css">
			
			.tbl th,th{
				text-align: center;
			}
		@media only screen and (max-width: 767px) {
				.table-fixtures td:nth-of-type(1):before { content: "Team A"; }
				.table-fixtures td:nth-of-type(2):before { content: "Vs"; }
				.table-fixtures td:nth-of-type(3):before { content: "Team B"; }
				.table-fixtures td:nth-of-type(4):before { content: "Details"; }
				}
		</style>
        
	</head>
	
	
	
	<body class="">
	    <!--<div class="canvas-overlay">
	    	<a class="overlay-btn btn-closer" href="#"></a>
	    	<div class="container" data-anijs="if: scroll, on:window, do: fadeInDown animated, before: scrollReveal, after: $fireOnce removeAnim ">
	    		
	
	    		<br/><br/>
	    		<div class="row">
	    			<div class="col-sm-4">
			    		<h3>Quick Links</h3>
			    		<ul class="list-styled list-bordered">
			    			<li><a href="index.html">Home</a></li>
			    			<li><a href="teams.html">Our teams</a></li>
			    			<li><a href="contact.html">Get in touch</a></li>
			    		</ul>
	    			</div>
	    			
	    			<div class="col-sm-4">
	    				<h3>Recent Posts</h3>
	    				<ul class="list-styled list-bordered">
	    					<li><a href="post-standard.html">Nominated club of the year</a></li>
	    					<li><a href="post-standard.html">Pre season camp success</a></li>
	    					<li><a href="post-standard.html">Road to the grand finals</a></li>
	    				</ul>
	    			</div>
	    			
	    			<div class="col-sm-4">
	    				<h3>Follow Us</h3>
	    				<ul class="social-links sl-vertical list-bordered">
	    					<li><a href="#"><i class="fa fa-facebook"></i>Facebook</a></li>
	    					<li><a href="#"><i class="fa fa-twitter"></i>Twitter</a></li>
	    					<li><a href="#"><i class="fa fa-globe"></i>Website</a></li>
	    				</ul>
	    			</div>
	    		</div>
	    	</div>
	    </div>-->
		
		<?php include('st-header.php'); ?>
		
		<!-- Start Main Content -->
		<main class="main-content">
		    <div class="container body-content">
		    	<div class="row">
		    		<div class="col-sm-12 main-column fixtures-page">
		    			<div class="page-header">
		    				<h2>Game Fixtures</h2>
		    			</div>
		    			<div class="events-filter">
		    				<?php include('st-eventfilter.php'); ?>
		    			</div>
		    			<table class="table-striped tbl table-responsive table-hover table-leaderboard table-tops tbl">
		    				<thead>

		    				<tr>
		    					
		    				</tr>

		    				<tr>
		    				    <th class="head1">Time</th>
		    					<th class="head1">Match</th>
		    					<th class="head1" style="text-align: center;" colspan="3">COMMUNITY CUP - ST. ANDREWS, BANDRA</th>
		    					<th class="head1">Pool</th>
		    					<th class="head1">Field</th>
		    				</tr>
		    				</thead>
		    				<tbody>
		    			    <?php
		    					$sql  = " SELECT * FROM tbl_fixtures WHERE status = 1 ";
		    					$sql .= " AND fixture_date >='".date('Y-m-d')."'";
		    					$sql .=" GROUP By fixture_date ORDER By fixture_date ASC";
		    					$result = query($sql);
		    					$num    = mysqli_num_rows($result);
		    					$n      = 1;

		    					while($row = mysqli_fetch_array($result))
		    					{
		    						$i=0;
		    					?>
		    						<tr>
				    					<th class="head1" colspan="7" style="text-align: center;">
				    						<?php 
				    						 $fixture_date = explode('-',$row['fixture_date']);
				    						  echo $fixture_date[2].'.'.$fixture_date[1].'.'.$fixture_date[0]; ?>
				    					</th>
				    				</tr>
				    				
		    					<?php

		    						$sql_get ="SELECT * FROM tbl_fixtures WHERE status =1 AND  fixture_date='".$row['fixture_date']."' GROUP By fixture_time_start ORDER By fixture_time_start ASC";
		    						$res = query($sql_get);
		    						$num = mysqli_num_rows($res);
		    						$f =0;
		    						while($frow = mysqli_fetch_array($res))
		    						{
		    							?>
		    						 <tr>

		    						 	<?php 

		    						 		$sql_tget ="SELECT * FROM tbl_fixtures WHERE status =1 AND  fixture_date='".$row['fixture_date']."' AND fixture_time_start='".$frow['fixture_time_start']."' ORDER By fixture_time_start ASC";
				    						$fres = query($sql_tget);
				    						$num = mysqli_num_rows($fres);
				    						$color   = '#e0e0e0';
				    						if($f%2==0)
				    						{
				    							$color   = '#cccccc';
				    						}
				    						$f++;
		    						 	?>
		    						 		<td rowspan="<?php echo $num; ?>"  style="vertical-align: middle;background-color: <?php echo $color; ?>"><?php echo $frow['fixture_time_start'] ?> TO <?php echo $frow['fixture_time_end'] ?></td>

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
				    						 	<?php echo strtoupper($r['team_a']) ?>
				    						 </td>
				    						 <td  style="vertical-align: middle;">Vs</td>
				    						 <td style="vertical-align: middle;">
				    						 	<img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;" />
				    						 	<?php echo strtoupper($r['team_b']) ?>
				    						 </td>
				    						 <td style="vertical-align: middle;"><?php echo strtoupper($r['pool']); ?></td>
				    						 <td  style="vertical-align: middle;"><?php echo strtoupper($r['field']); ?></td>
				    						 </tr>
		    						 <?php
		    						   }
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
	</body>


</html>