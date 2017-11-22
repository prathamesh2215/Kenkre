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
		
		<title>Kenkre | Results</title>
		
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
  
    
	  
	    <div class="canvas-overlay">
	    	<!-- Close Button -->
	    	<a class="overlay-btn btn-closer" href="#"></a>
	    	<!-- Content Container -->
	    	<div class="container" data-anijs="if: scroll, on:window, do: fadeInDown animated, before: scrollReveal, after: $fireOnce removeAnim ">
	    		<h2>Search Website</h2>
	    		<form class="search-hero">
	    			<div class="input-group">
	    				<label class="input">
		    				<input type="text" class="form-control" placeholder="Search for...">
	    				</label>
	    				<span class="input-group-btn">
	    					<button class="btn btn-success" type="button">Search</button>
	    				</span>
	    			</div>
	    		</form>
	
	    		<br/><br/>
	    		<div class="row">
	    			<div class="col-sm-4">
			    		<h3>Quick Links</h3>
			    		<ul class="list-styled list-bordered">
			    			<li><a href="index.html">Home page</a></li>
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
							<!-- Sport <div class="tag-line">Sporting Club Theme</div> -->
							<img  class="logo-img" src="img/logo1.png" alt="Logo" />
						</a>
					</div>
					
					<ul class="menuzord-menu head-right">
						<li class="active"><a href="index.html">Home</a></li>
						<li><a href="about.html">About</a>
							 
						</li>
						
					</ul>
				</div><!--/.menuzord -->
			  </div>
			</nav>
			<!-- End Main Header -->
			
			
			
			<!-- End Post Header -->
			
			
		</header>
		<!-- End Header -->
		
		
		
		
	
	
		
		<!-- Start Main Content -->
		<main class="main-content">
		    <div class="container body-content">
		    	
		    	<div class="row">
		    		<div class="col-sm-12 main-column fixtures-page">
		    			
		    			<div class="page-header">
		    				<h2>Points Leaderboard</h2>
		    			</div>
		    			
		    			
		    			<!-- Responsive Table Heading Labels -->
		    			<style>
		    				@media only screen and (max-width: 767px) {
		    				td:nth-of-type(1):before { content: "Competitor"; }
		    				td:nth-of-type(2):before { content: "Position"; }
		    				td:nth-of-type(3):before { content: "Wins"; }
		    				td:nth-of-type(4):before { content: "Draws"; }
		    				td:nth-of-type(5):before { content: "Losses"; }
		    				td:nth-of-type(6):before { content: "%"; }
		    				}
		    			</style>
		    			
		    			
		    			<!-- Start Events Filter -->
		    			<div class="events-filter">
		    				<ul class="list-inline">
		    					<li><strong>View: </strong></li>
		    					<li><a class="meta-text" href="index.html"><em class="fa fa-table"></em> Game Fixtures</a> &nbsp;</li>
		    					<li><a class="feat-color" href="results.html"><em class="fa fa-trophy"></em> Results</a> &nbsp;</li>
		    					<li><a class="meta-text" href="points-leaderboard.html"><em class="fa fa-trophy"></em> Points Leaderboard  </a> &nbsp;</li>
		    					<li><a class="meta-text" href="format.html"><em class="fa fa-sticky-note-o"></em> Format</a> &nbsp;</li>
		    					<li><a class="meta-text" href="grouping.html"><em class="fa fa-users"></em> Grouping  </a> &nbsp;</li>
		    				</ul>
		    			</div>
		    			
		    			
		    			<!-- Time Leaderboard -->			
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
	                                        	$sql ="SELECT DISTINCT fixture_date FROM tbl_fixtures ";
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
		    						$sql_get .=" GROUP By fixture_time_start ORDER By fixture_time_start ASC";
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

				    						 <?php
				    						 	$point  = checkExist('tbl_results',array('fixture_id'=>$r['fixture_id']));
				    						 ?>
				    						 <td  style="vertical-align: middle;"><?php echo @$point['team_a_point']; ?></td>
				    						 <td  style="vertical-align: middle;">Vs</td>
				    						 <td  style="vertical-align: middle;"><?php echo @$point['team_b_point']; ?></td>


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
								<h3><img  class="logo-img" src="img/logo1.png" alt="Logo" />
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
							$('#state_code').select2();
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