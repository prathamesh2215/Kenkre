<?php include("include/routines.php");
$_SESSION['panel_user']	= array();
// remove all session variables
session_unset();
// destroy the session
session_destroy(); 
header("Location: ".$BaseFolder);
exit(0);
?>