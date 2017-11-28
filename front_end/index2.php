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
		
		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="library/bs/css/bootstrap.min.css">
		
		<!-- Menu Stylesheets -->
		<link rel="stylesheet" type="text/css" href="library/css/menuzord/menuzord.css">
		<link rel="stylesheet" type="text/css" href="library/css/menuzord/menuzord-animations.css">
		<link rel="stylesheet" type="text/css" href="library/css/menuzord/skins/menuzord-border-top.css">
		
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="library/css/library.css">
		<link rel="stylesheet" type="text/css" href="library/css/responsive.css">
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<!-- Custom styles for this template -->
		<link rel="stylesheet" type="text/css" href="styles.css">
		
		<style type="text/css">
			
			.tbl th,th{
				text-align: center;
			}
		</style>
	</head>
	
	
	
	<body class="">
  
    
	    <!-- Start Full Screen Overlay -->
	    <div class="canvas-overlay">
	    	<!-- Close Button -->
	    	<a class="overlay-btn btn-closer" href="#"></a>
	    	<!-- Content Container -->
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
	    </div>
	    <!-- End Full Screen Overlay -->
	    
	    
	    
		
		<!-- Start Header -->
		<header id="header">
			
			
			
			
			<!-- Start Main Header -->
			<nav class="main-header header-reveal">
			  <div class="container">
				<!-- Start Menu -->
				<div id="menuzord" class="menuzord">
					<div class="head-left">
						<a href="index.html" class="menuzord-brand">
							
							<img  class="logo-img" src="img/logo1.png" alt="Logo" />
						</a>
					</div>
					
					<ul class="menuzord-menu head-right">
						<li class="active"><a href="">Home</a></li>
						<li><a href="about.html">About</a>
						</li>
						
					</ul>
				</div><!--/.menuzord -->
			  </div>
			</nav>
			<!-- End Main Header -->
			
			
			
			
			
		</header>
		<!-- End Header -->
		
		
		
		
	
	
		
		<!-- Start Main Content -->
		<main class="main-content">
		    <div class="container body-content">
		    	
		    	<div class="row">
		    		<div class="col-sm-12 main-column fixtures-page">
		    			
		    			<div class="page-header">
		    				<h2>Fixtures</h2>
		    				
		    			</div>
		    			
		    			
		    			
		    			<!-- Responsive Table Heading Labels -->
		    			<style>
		    				@media only screen and (max-width: 767px) {
		    				.table-fixtures td:nth-of-type(1):before { content: "Team A"; }
		    				.table-fixtures td:nth-of-type(2):before { content: "Vs"; }
		    				.table-fixtures td:nth-of-type(3):before { content: "Team B"; }
		    				.table-fixtures td:nth-of-type(4):before { content: "Details"; }
		    				}
		    			</style>
		    			<!-- Start Events Filter -->
		    			<div class="events-filter">
		    				<ul class="list-inline">
		    					<li><strong>View: </strong></li>
		    					<li><a class="feat-color" href="#"><em class="fa fa-table"></em> Game Fixtures</a> &nbsp;</li>
		    					<li><a class="meta-text" href="results.html"><em class="fa fa-trophy"></em> Results</a> &nbsp;</li>
		    					<li><a class="meta-text" href="points-leaderboard.html"><em class="fa fa-trophy"></em> Points Leaderboard</a> &nbsp;</li>
		    					<li><a class="meta-text" href="format.html"><em class="fa fa-sticky-note-o"></em> Format</a> &nbsp;</li>
		    					<li><a class="meta-text" href="grouping.html"><em class="fa fa-users"></em> Grouping  </a> &nbsp;</li>
		    				</ul>
		    			</div>
		    			<table class="table-striped tbl table-responsive table-hover table-leaderboard table-tops tbl">
		    				<thead>
		    				<tr>
		    				    <th class="head1" style="">Time</th>
		    					<th class="head1">Match</th>
		    					<th class="head1" style="text-align: center;" colspan="3">COMMUNITY CUP - ST. ANDREWS, BANDRA</th>
		    					<th class="head1">Pool</th>
		    					<th class="head1">Field</th>
		    				</tr>

		    				</thead>
		    				<tbody>
		    			<?php

		    					$sql = " SELECT * FROM tbl_fixtures WHERE status = 1 GROUP By fixture_date ORDER By fixture_date ASC";
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
		    						 <td rowspan="<?php echo $num; ?>"  style="vertical-align: middle;background-color: <?php echo $color; ?>">8.00 TO 8.30AM</td>

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
		    						 ?>
		    						</tr>
		    						<?php
   
		    						}
		    						?>
									   						
		    				   <?php
		    				    }
		    				    ?>
		    				</tbody>
		    			</table>
		    			
		    			
		    			
		    			
		    			
		    			
		    				    			
		    				    			
		    				    			    				
					</div><!--/.main-column -->
								
					
					
					
		
		    </div><!-- /.container -->
		</main><!-- /.main-content -->
		
		
		
		
		
		<!--Start Footer -->
		<div id="footer">
			<!-- Start Pre Footer -->
			
			
			
			
			<!-- Start Footer -->
			<footer class="main-footer">
				<div class="container">
					<!-- Start Row -->
					<div class="row">
						<div class="col-md-6">
							<div class="widget widget-contact">
								<h3>
								<img class="logo-img" src="img/logo1.png" alt="Logo" />
								
								</h3>
							</div>		
						</div>
						
						
						
						<div class="col-md-6">
							<div class="widget widget-brand-address">
								
								<ul class="list-styled list-bordered">
									<li><strong>Address:</strong><br/>
												No. 2, Mahim House,
												Mogul Lane,Mahim (W), Mumbai 400016 
									</li>
									<li><strong>Phone:</strong><br/>
												 7738098599 / 7738054599 / 9821445880
									</li>
									
								</ul>
							</div>
						</div>	
					</div><!--/.row -->
				</div>
			</footer>
			
			
			
			<!-- Start Post Footer -->
			<footer class="post-footer">
				<div class="container">
					<div class="foot-center">
						<div>&copy; Kenkre Sports Club 2017-2018. All rights reserved.</div> 
					</div>
					
					
				</div>
			</footer>
		</div><!--/footer -->
	
	
	
    	<!-- JavaScript
	   	================================================== -->
	   	<script src="../../../ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	   	<script>window.jQuery || document.write('<script src="library/js/vendor/jquery.min.html"><\/script>')</script>
	   	<script src="library/bs/js/bootstrap.min.js"></script>
	   	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	    <script type="text/javascript" src="library/js/jquery.scrollUp.min.js"></script>
		<script type="text/javascript" src="library/js/menuzord.js"></script>
		<script src="js/scripts.js"></script>
	</body>


</html>