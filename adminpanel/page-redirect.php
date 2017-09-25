<?php

include("include/routines.php");

if(isset($_SESSION['front_panel']))
{
	$sql_check = " SELECT * FROM tbl_cadmin_users WHERE email ='".$_SESSION['front_panel']['cust_email']."'  AND status=1 ";
	$res_check = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	echo $num_check = mysqli_num_rows($res_check);
	if($num_check==1)
	{
		$row                   = mysqli_fetch_array($res_check);
		$_SESSION['panel_user']=$row;
		header("Location:view_dashboard.php");
	}
	else
	{?>
    <script>
	alert('Please try letter...!');
    window.close();
    </script>
	<?php }
}


?>