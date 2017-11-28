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
		    			<!-- Time Leaderboard -->			
		    			<table class="table-striped table-responsive table-hover table-leaderboard table-tops table-top-5 tbl" >
		    				<thead>
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


		    				<!-- Under 10 A -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 10 A</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />SCOTTISH BRAVEHEARTS</td>
		    					<td>2</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>5</td>
		    					<td>2</td>
		    					<td>3</td>
		    					<td>6</td>
		    				</tr>
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />STANIS KNIGHTS</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>3</td>
		    					<td>4</td>
		    					<td>-1</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />BOSCO 7</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>2</td>
		    					<td>3</td>
		    					<td>-1</td>
		    					<td>0</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />SCOTTISH DEVIL RED</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>2</td>
		    					<td>3</td>
		    					<td>-1</td>
		    					<td>0</td>
		    				</tr>
		    				
		    				<!-- Under 10 B -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 10 B</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />DON BOSCO A</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>15</td>
		    					<td>1</td>
		    					<td>14</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />EFIB</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>4</td>
		    					<td>1</td>
		    					<td>3</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KOPANA FS U10 A</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>9</td>
		    					<td>17</td>
		    					<td>-8</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />YOUTH SA</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>2</td>
		    					<td>3</td>
		    					<td>12</td>
		    					<td>-9</td>
		    					<td>0</td>
		    				</tr>


		    				<!-- Under 10 C -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 10 C</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />FFA</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>5</td>
		    					<td>1</td>
		    					<td>4</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />DON BOSCO B</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>4</td>
		    					<td>0</td>
		    					<td>4</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />YOUNG SHALL GROW B</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>4</td>
		    					<td>5</td>
		    					<td>-1</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />DIAMOND FC</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>7</td>
		    					<td>-7</td>
		    					<td>0</td>
		    				</tr>

		    				<!-- Under 10 D -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 10 D</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />ASPIRE U10 A</td>
		    					<td>2</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>17</td>
		    					<td>3</td>
		    					<td>14</td>
		    					<td>6</td>
		    				</tr>
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />BOMBAY STALLIONS U10</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>7</td>
		    					<td>0</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />DON BOSCO C</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>5</td>
		    					<td>-5</td>
		    					<td>0</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KOPANA FS U10 B</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>10</td>
		    					<td>-9</td>
		    					<td>0</td>
		    				</tr>
		    				<!-- Under 10 E -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 10 E</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />RISING STAR</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>4</td>
		    					<td>0</td>
		    					<td>4</td>
		    					<td>3</td>
		    				</tr>
		    				
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />SCOTTISH DEVIL BLUE</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>4</td>
		    					<td>-4</td>
		    					<td>0</td>
		    				</tr>
		    				
		    				<!-- Start for Under 12 -->

		    				<!-- Under 10 A -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 12 A</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />CASTLE BY A U12</td>
		    					<td>2</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>11</td>
		    					<td>0</td>
		    					<td>11</td>
		    					<td>6</td>
		    				</tr>
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />SHINING STARS</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>7</td>
		    					<td>1</td>
		    					<td>6</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />ASPIRE U12 B</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>5</td>
		    					<td>-5</td>
		    					<td>0</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />YOUNG SHALL GROW U12</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>13</td>
		    					<td>-12</td>
		    					<td>0</td>
		    				</tr>
		    				
		    				<!-- Under 10 B -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 12 B</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />BOSCO FALCONS</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>9</td>
		    					<td>0</td>
		    					<td>9</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />CASTLE BY B U12</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>2</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>2</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KOOH A U12</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />MUMBAI ROOKIES</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>2</td>
		    					<td>11</td>
		    					<td>-9</td>
		    					<td>1</td>
		    				</tr>


		    				<!-- Under 10 C -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 12 C</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />BOMBAY STALLIONS U12</td>
		    					<td>3</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>17</td>
		    					<td>4</td>
		    					<td>13</td>
		    					<td>6</td>
		    				</tr>
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />DBOSCO DRAGONS</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>3</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KOPANA FS A U12</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>4</td>
		    					<td>-3</td>
		    					<td>0</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />PANTHERS</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>11</td>
		    					<td>-11</td>
		    					<td>0</td>
		    				</tr>

		    				<!-- Under 10 D -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 12 D</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />GOALSTERS U12</td>
		    					<td>2</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>8</td>
		    					<td>5</td>
		    					<td>3</td>
		    					<td>6</td>
		    				</tr>
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />GOALSTERS U12</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>3</td>
		    					<td>1</td>
		    					<td>2</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />FFA RED</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>2</td>
		    					<td>3</td>
		    					<td>-1</td>
		    					<td>0</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />EFIB A U12</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>2</td>
		    					<td>4</td>
		    					<td>8</td>
		    					<td>-4</td>
		    					<td>0</td>
		    				</tr>
		    				<!-- Under 10 E -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 12 E</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />SCOTTISH SHOOTERS</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>6</td>
		    					<td>2</td>
		    					<td>4</td>
		    					<td>4</td>
		    				</tr>
		    				
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />HUSKIES</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>5</td>
		    					<td>1</td>
		    					<td>4</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />FFA BLUE</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>2</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>1</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />EFIB B U12</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>9</td>
		    					<td>-8</td>
		    					<td>0</td>
		    				</tr>
		    				<!-- Under12 F -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 12 F</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />ASPIRE U12 A</td>
		    					<td>2</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>3</td>
		    					<td>0</td>
		    					<td>3</td>
		    					<td>6</td>
		    				</tr>
		    				
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />HEARTS</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>3</td>
		    					<td>2</td>
		    					<td>1</td>
		    					<td>3</td>
		    				</tr>
		    				<tr>
		    					<td>3</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KENKRE 06 A </td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>-1</td>
		    					<td>0</td>
		    				</tr>
		    				<tr>
		    					<td>4</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KOPANA FS B U12</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>3</td>
		    					<td>-3</td>
		    					<td>0</td>
		    				</tr>

		    				<!-- Under14 a -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 14 A</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />JOGA BONITO</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>8</td>
		    					<td>0</td>
		    					<td>8</td>
		    					<td>3</td>
		    				</tr>
		    				
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />GLADIATORS</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>8</td>
		    					<td>-8</td>
		    					<td>0</td>
		    				</tr>

		    				<!-- Under14 F -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 14 B</td>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KOOH SPORTS U14</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>3</td>
		    				</tr>
		    				
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />SCOTTISH STRIKERS</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>-1</td>
		    					<td>0</td>
		    				</tr>


		    				<!-- Under14 C -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 14 C</td>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />GOALSTERS</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>17</td>
		    					<td>1</td>
		    					<td>16</td>
		    					<td>3</td>
		    				</tr>
		    				
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KOPANA FS U14 A</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>17</td>
		    					<td>-16</td>
		    					<td>0</td>
		    				</tr>

		    				<!-- Under14 D -->
		    				<tr>
		    					<th style="text-align: center;" colspan="10" class="head1">Under 14 D</th>
		    				</tr>
		    				<tr>
		    					<td>1</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />BOMBAY STALLIONS U14</td>
		    					<td>2</td>
		    					<td>2</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>5</td>
		    					<td>1</td>
		    					<td>5</td>
		    					<td>6</td>
		    				</tr>
		    				
		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />YOUNG SHALL GROW U14</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>1</td>
		    					<td>2</td>
		    					<td>-1</td>
		    					<td>0</td>
		    				</tr>

		    				<tr>
		    					<td>2</td>
		    					<td><img src="img/teamwork.png" alt="icon" style="max-width: 2.75em;float: left;margin-right: .5em;" />KOPANA FS U14 B</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>0</td>
		    					<td>1</td>
		    					<td>0</td>
		    					<td>3</td>
		    					<td>-3</td>
		    					<td>0</td>
		    				</tr>
		    				
		    				</tbody>
		    			</table>
		    				    			    				
					</div><!--/.main-column -->
		    	</div><!-- /.container -->
            </div>
		</main>
        <!-- /.main-content -->
		
		<?php include('st-footer.php'); ?>
		<?php include('st-javascript.php'); ?>
		
		
	</body>


</html>