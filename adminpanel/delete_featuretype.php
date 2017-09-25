<?php
include("include/routines.php");

$single_rights 	= array();
$ar_af_id		= array();
$ar_af_id 		= $_POST['batch'];
foreach($ar_af_id as $af_id)
{
	//To inactivate the feature
	$sql_delete_feature = "update tbl_admin_features set af_status = '0', modifieddt = '".$datetime."', modifiedby = '".$_SESSION['mystudyc_admin']['fullname']."' where af_id = '".$af_id."'";
	$result_delete_feature = mysqli_query($db_con,$sql_delete_feature) or die(mysqli_error($db_con));	
	
	//To check whether deleted rights assigned to any user 
	$sql_chk_feature_assigned   = "select * from tbl_assign_rights where find_in_set('$af_id',ar_current_rights)<> 0";
	$res_chk_feature_assigned 	= mysql_query($sql_chk_feature_assigned);
	if($res_chk_feature_assigned)//if feature is aleady assinged to any user
	{
		while($row_chk_feature_assigned = mysql_fetch_array($res_chk_feature_assigned))
		{
			$ar_id 				= $row_chk_feature_assigned['ar_id'];
			$ar_current_rights 	= $row_chk_feature_assigned['ar_current_rights']; //Get current rights of user
			
			if($row_chk_feature_assigned['ar_history_rights'] != "")	//If history rights is not empty then append
			{
				$ar_history_rights 	= $row_chk_feature_assigned['ar_history_rights'].",".$af_id;
			}
			else
			{
				$ar_history_rights 	= $af_id;
			}
			
			$single_rights 		= explode(",",$ar_current_rights);	//Save each right
			
			if($single_rights[0] == $af_id && $single_rights[1] == "")
			{
				$ar_current_rights = str_replace($af_id,"",$ar_current_rights);
			}
			else if($single_rights[0] == $af_id && $single_rights[1] != "")
			{
				$ar_current_rights = str_replace($af_id.",","",$ar_current_rights);
			}
			else
			{
				$ar_current_rights = str_replace(",".$af_id,"",$ar_current_rights);
			}
			$sql_update_assign_rights = " update tbl_assign_rights set ar_current_rights = '".$ar_current_rights."', ar_history_rights = ";
			$sql_update_assign_rights .= " '".$ar_history_rights."', modifieddt = '".$datetime."', modifiedby = '".$_SESSION['user_panel']['fullname']."' where ar_id = '".$ar_id."' ";
			$result_update_assign_rights = mysqli_query($db_con,$sql_update_assign_rights) or die(mysqli_error($db_con));
		}//while($row_chk_feature_assigned = mysql_fetch_array($res_chk_feature_assigned))
	}
			?>
			<script type="text/javascript">
			window.open('view_featuretype.php','_self');
			</script>
			<?php

}