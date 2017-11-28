<?php 
	$currentFile = $_SERVER["PHP_SELF"];
    $parts = Explode('/', $currentFile);
    $pg_filename =  $parts[count($parts) - 1];
?>
<ul class="list-inline">
    <li><strong>View: </strong></li>
    <li><a <?php if($pg_filename == "index.php") {?> class="feat-color" <?php } else { ?>class="meta-text"<?php } ?> href="index.php"><em class="fa fa-table"></em> Game Fixtures</a> &nbsp;</li>
    <li><a <?php if($pg_filename == "results.php") {?> class="feat-color" <?php } else { ?>class="meta-text"<?php } ?>  href="results.php"><em class="fa fa-trophy"></em> Results</a> &nbsp;</li>
    <li><a <?php if($pg_filename == "points-leaderboard.php") {?> class="feat-color" <?php } else { ?>class="meta-text"<?php } ?> href="points-leaderboard.php"><em class="fa fa-trophy"></em> Points Leaderboard</a> &nbsp;</li>
    <li><a <?php if($pg_filename == "format.php") {?> class="feat-color" <?php } else { ?>class="meta-text"<?php } ?> href="format.php"><em class="fa fa-sticky-note-o"></em> Format</a> &nbsp;</li>
    <li><a <?php if($pg_filename == "grouping.php") {?> class="feat-color" <?php } else { ?>class="meta-text"<?php } ?> href="grouping.php"><em class="fa fa-users"></em> Grouping  </a> &nbsp;</li>
</ul>