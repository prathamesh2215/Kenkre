<?php
	include("include/routines.php");
	$uid 				= $_SESSION['panel_user']['id'];
	$utype				= $_SESSION['panel_user']['utype'];
	$tbl_users_owner	= $_SESSION['panel_user']['tbl_users_owner'];

	if((isset($obj->load_products)) == '1' && (isset($obj->load_products)))
	{
		echo json_encode($response_array);	
	}
	
	if((isset($_POST['add_product_req'])) == '1' && (isset($_POST['add_product_req'])))
	{
		$data['prod_name']          = mysqli_real_escape_string($db_con,$_POST['prod_name']);
		$data['prod_model_number']  = mysqli_real_escape_string($db_con,$_POST['prod_model_number']);
		$data['prod_factor'] 		= mysqli_real_escape_string($db_con,$_POST['txt_factor']);
		$data['prod_pharmacopia']   = mysqli_real_escape_string($db_con,$_POST['txt_pharmacopia']);
		$data['prod_drug_type']     = mysqli_real_escape_string($db_con,$_POST['txt_drug_type']);
		$data['prod_composition']   = mysqli_real_escape_string($db_con,$_POST['txt_cmp']);
		if($data['prod_drug_type']=="Single")
		{
			$data['prod_composition']   = mysqli_real_escape_string($db_con,$_POST['txt_cmp']);
		}
		else
		{
			$data['prod_composition']   = implode('1',mysqli_real_escape_string($db_con,$_POST['txt_cmp']));
		}
		
		$data['prod_brandid']    = mysqli_real_escape_string($db_con,$_POST['txt_brand']);
		$data['prod_tax_class']  = mysqli_real_escape_string($db_con,$_POST['txt_tax']);
		$data['prod_packing']    = mysqli_real_escape_string($db_con,$_POST['txt_packing']);
		$data['prod_dimension']  = mysqli_real_escape_string($db_con,$_POST['txt_dimension']);
		$data['prod_uow']  		 = mysqli_real_escape_string($db_con,$_POST['txt_weight']);
		$data['prod_available_pack']  = mysqli_real_escape_string($db_con,$_POST['txt_avai_pack']);
		$data['prod_status']     = mysqli_real_escape_string($db_con,$_POST['prod_status']);
		$data['prod_created_by'] = $logged_uid;
		$data['prod_created']    = $datetime;
		
		if(!isExist('tbl_products_master',array('prod_model_number'=>$data['prod_model_number'])))
		{
			if($data['prod_model_number']!="")
			{
				$res = insert('',$data);
				if($res)
				{
					quit('Product Added Successfully...!','1');
				}
				else
				{
					quit('Please try letter...!');
				}
			}
			else
			{
				quit('Model Number required...!');
			}
		}
		else
		{
			quit('Model Number already exist...!');
		}
	}
	
?>
