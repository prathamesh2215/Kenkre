<?php
	function getActiveCities($city_id)
	{
		global $db_con;
		$ddl_city_data;
		$sql_get_active_cities	= " SELECT * FROM `tbl_city` WHERE status='1' ";
		$res_get_active_cities	= mysqli_query($db_con, $sql_get_active_cities) or die(mysqli_error());
		$num_get_active_cities	= mysqli_num_rows($res_get_active_cities);

		if($num_get_active_cities != 0)
		{
			$ddl_city_data	.= '<option value="">Select City</option>';
			while($row_get_active_cities = mysqli_fetch_array($res_get_active_cities))
			{
				$ddl_city_data	.= '<option value="'.$row_get_active_cities['city_id'].'" ';
				if($city_id  == $row_get_active_cities['city_id'])
				{
					$ddl_city_data	.= ' selected ';
				}
				$ddl_city_data	.= '>';
					$ddl_city_data	.= ucwords($row_get_active_cities['city_name']);
				$ddl_city_data	.= '</option>';
			}
		}
		else
		{
			$ddl_city_data	.= '<option value="">No match found</option>';
		}

		return $ddl_city_data;
	}

	function getActiveStates($state_id)
	{
		global $db_con;
		$ddl_state_data	= '';
		$sql_get_active_states	= " SELECT * FROM `tbl_state` WHERE status='1' ";
		$res_get_active_states	= mysqli_query($db_con, $sql_get_active_states) or die(mysqli_error());
		$num_get_active_states	= mysqli_num_rows($res_get_active_states);

		if($num_get_active_states != 0)
		{
			$ddl_state_data	.= '<option value="">Select State</option>';
			while($row_get_active_states = mysqli_fetch_array($res_get_active_states))
			{
				$ddl_state_data	.= '<option value="'.$row_get_active_states['state'].'" ';
				if($state_id  == $row_get_active_states['state'])
				{
					$ddl_state_data	.= ' selected ';
				}
				$ddl_state_data	.= '>';
					$ddl_state_data	.= ucwords($row_get_active_states['state_name']);
				$ddl_state_data	.= '</option>';
			}
		}
		else
		{
			$ddl_state_data	.= '<option value="">No match found</option>';
		}

		return $ddl_state_data;
	}

	function getStatesCity($state_id, $city_select_id)
	{
		global $db_con;
		$ddl_state_data	= '';
		$sql_get_states_city	= " SELECT * FROM `tbl_city` WHERE state_id='".$state_id."' AND `status`='1' ";
		$res_get_states_city	= mysqli_query($db_con, $sql_get_states_city) or die(mysqli_error());
		$num_get_states_city	= mysqli_num_rows($res_get_states_city);
		
		//$ddl_state_data	.= '<select class="col-md-3 col-xs-12 selectpicker" data-live-search="true" name="'.$city_select_id.'" id="'.$city_select_id.'" data-rule-required="true">';
		if($num_get_states_city != 0)
		{
			$ddl_state_data	.= '<option value="">Select City</option>';
			while($row_get_states_city = mysqli_fetch_array($res_get_states_city))
			{
				$ddl_state_data	.= '<option value="'.$row_get_states_city['city_id'].'" >';
					$ddl_state_data	.= ucwords($row_get_states_city['city_name']);
				$ddl_state_data	.= '</option>';
			}
		}
		else
		{
			$ddl_state_data	.= '<option value="">No match found</option>';
		}
		//$ddl_state_data	.= '</select>';

		return $ddl_state_data;
	}
?>