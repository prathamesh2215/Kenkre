<ul class='main-nav' style="margin-top:10px !important">

<!--    <li class="active">
        <a href="home.php?pag=home">
            <i class="icon-home"></i>
            <span>Dashboard</span>
        </a>
    </li>
-->
<?php
	global $db_con;
	$ar_parent_menu  	= array();
	$ar_child_menu  	= array();
	$ar_child_menu_id  	= array();
	$ar_feature_id  	= array();
	$ar_menu_order  	= array();
	$ar_combine  		= array();
	
	$str_feature_id  	= "";
	$str_menu_order  	= "";
	//This is for fetching the current rights of user
	$sql_parent_menu 	= "select * from tbl_assign_rights where ar_user_owner_id = '".$_SESSION['panel_user']['id']."'";
	$res_parent_menu 	= mysqli_query($db_con,$sql_parent_menu);
	$row_parent_menu 	= mysqli_fetch_array($res_parent_menu);
	$part_main			= explode("{",$row_parent_menu['ar_current_rights']);
	$ar_parent_menu  	= array();
	//print_r($part_main);
	foreach($part_main as $part)
	{
		$part_sub_1			= explode(":",$part);
		if($part_sub_1[0] != "")
		{
			array_push($ar_parent_menu,$part_sub_1[0]);				
		}
	}
	//print_r($ar_parent_menu);
	foreach($ar_parent_menu as $val)
	{
		$sql_parent_menu_name = "select * from tbl_admin_features where af_id = '".$val."' and af_status = '1'";
		$res_parent_menu_name = mysqli_query($db_con,$sql_parent_menu_name);
		$row_parent_menu_name = mysqli_fetch_array($res_parent_menu_name);
		$str_menu_order .= $row_parent_menu_name['af_menu_order'].",";
		$str_feature_id .= $val.",";
	}
	//This is for sorting main menus (Parent) according to menu order 
	$ar_feature_id 	= explode(',', rtrim($str_feature_id,','));
	$ar_menu_order 	= explode(',',rtrim($str_menu_order,','));
	$ar_combine 	= array_combine($ar_feature_id,$ar_menu_order);
	
	asort($ar_combine);
	$ar_assigned_child_menu = array();
	foreach($ar_combine as $x => $x_value) 
	{
		//This is for fetching parent menu name
		$sql_parent_menu_name1 = "select * from tbl_admin_features where af_id = '".$x."' and af_parent_type = 'Parent' and af_status = '1'";
		$res_parent_menu_name1 = mysqli_query($db_con,$sql_parent_menu_name1) or die(mysqli_error($db_con));
		$row_parent_menu_name1 = mysqli_fetch_array($res_parent_menu_name1);
		if($row_parent_menu_name1['af_name'] != "")
		{
			//This is for fetching child menu name according to menu order
			$af_parent_type 	 = $row_parent_menu_name1['af_name'];
			$ar_child_menu 		 = array();
			$sql_child_menu_name = "select * from tbl_admin_features where af_parent_type = '".$af_parent_type."' and af_status = '1' order by af_menu_order";
			$res_child_menu_name = mysqli_query($db_con,$sql_child_menu_name);
			while($row_child_menu_name = mysqli_fetch_array($res_child_menu_name))
			{
				$ar_child_menu[$row_child_menu_name['af_name']] = $row_child_menu_name['af_page_url'];
			}	
			
			$ar_child_menu_count = count($ar_child_menu);				
		?>
		<li <?php if(isset($_REQUEST['pag']) && $_REQUEST['pag'] == $row_parent_menu_name1['af_name']) { ?> class="active" <?php } ?> >
		   <a href="<?php if($ar_child_menu_count <= 0){ echo $row_parent_menu_name1['af_page_url']."?pag=".$row_parent_menu_name1['af_name']; } else { echo $row_parent_menu_name1['af_page_url']; }?>" <?php if($ar_child_menu_count > 0) { ?> data-toggle="dropdown" class="dropdown-toggle" <?php } ?> >
				<i class="icon-edit"></i>
				<span><?php echo $row_parent_menu_name1['af_name']; ?></span>
            <?php if($ar_child_menu_count > 0)//To check is there any child menu
				  {
			?>
                    <span class="caret"></span>
            <?php } ?>        
			</a>
            <?php if($ar_child_menu_count > 0)//To check is there any child menu
				  {
			?>
                    <ul class="dropdown-menu">
			<?php 
					//This is for fetching child menu name according to menu order
					$af_parent_type 	 = $row_parent_menu_name1['af_name'];
					$sql_child_menu_name = "select * from tbl_admin_features where af_parent_type = '".$af_parent_type."' and af_status = '1' order by af_menu_order";					
					$res_child_menu_name = mysqli_query($db_con,$sql_child_menu_name) or die($db_con);
					while($row_child_menu_name = mysqli_fetch_array($res_child_menu_name))
					{
						$ar_child_menu[$row_child_menu_name['af_name']] = $row_child_menu_name['af_page_url'];
						if(in_array($row_child_menu_name['af_id'],$ar_feature_id))
						{
							//array_push($ar_assigned_child_menu,$ar_feature_id);
							foreach($ar_child_menu as $name => $url)
							{
								if($name == $row_child_menu_name['af_name'])
								{
									?>
									<li>
										<a href="<?php echo $url."?pag=".$row_parent_menu_name1['af_name']; ?>"><?php echo $name; ?></a>
									</li>	
									<?php					
								}
							}
						}
						else
						{
							//$ar_assigned_child_menu = 0;
							//echo "NO";
						}
					}	
					$ar_child_menu = "";
				
			?>
            		</ul>
            <?php } ?>
		 </li>
		 
		<?php
		}//if($row_parent_menu_name1['af_name'] != "")
	}//foreach($ar_combine as $x => $x_value)
?>
</ul>
<div class="user">
				<div class="dropdown asdf">
					<a href="#" class='dropdown-toggle' data-toggle="dropdown"><?php echo $_SESSION['panel_user']['fullname']; ?><span class="caret"></span></a>
					<ul class="dropdown-menu pull-right">
						<li>
							<a href="#">Edit profile</a>
						</li>
						<li>
							<a href="change_password.php">Change Password</a>
						</li>
						<li>
							<a href="logout.php">Sign out</a>
						</li>
					</ul>
				</div>
			</div>
