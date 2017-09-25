<?php
include("include/routines.php");

function createThumbnail($prod_img, $prod_dir, $explode_image_name_2, $prod_id, $product_name)
{ 
 	$file_path_for_small	= $prod_dir.'/small/';
	
	if(is_dir($file_path_for_small) === false)
	{
		 mkdir($file_path_for_small);
	}
	
	
	make_thumb($prod_img,$file_path_for_small.$explode_image_name_2,100,100);
	
	$file_path_for_medium	= $prod_dir.'/medium/';
	if(is_dir($file_path_for_medium) === false)
	{
		mkdir($file_path_for_medium);
	}
	make_thumb($prod_img,$file_path_for_medium.$explode_image_name_2,200,200);
	
	$file_path_for_large	= $prod_dir.'/large/';
	if(is_dir($file_path_for_large) === false)
	{
		mkdir($file_path_for_large);
	}
	make_thumb($prod_img,$file_path_for_large.$explode_image_name_2,400,400);
	
	$explode_main_sub	= explode('_', $explode_image_name_2);
	
	if(count($explode_main_sub) == 1)
	{
		$prod_img_type	= 'main';	
	}
	elseif(count($explode_main_sub) == 2)
	{
		$prod_img_type	= 'sub';	
	}
	
	imageUpload($prod_img_type,$prod_id,$explode_image_name_2,$product_name);
	
	if(unlink($prod_img))
	{
	//  echo "<br>".$prod_img;
    }	
}

function imageUpload($prod_img_type,$prod_img_prodid,$prod_img_file_name,$prod_img_name)
{	
	global $uid;
	global $db_con;
	
	$sql_check_img					= " SELECT * FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$prod_img_prodid."' and `prod_img_type` = '".$prod_img_type."' ";
	$result_check_img 				= mysqli_query($db_con,$sql_check_img) or die(mysqli_error($db_con));
	$num_rows_check_img				= mysqli_num_rows($result_check_img);
	if($num_rows_check_img != 0)
	{
		$row_check_img				= mysqli_fetch_array($result_check_img);
		$sql_delete_img				= " DELETE FROM `tbl_products_images` WHERE `prod_img_id` = '".$row_check_img['prod_img_id']."' ";
		$result_delete_img 			= mysqli_query($db_con,$sql_delete_img) or die(mysqli_error($db_con));		
	}
	
	if($prod_img_type == "main")
	{
		$prod_img_sort_order		= 1;
	}
	else
	{		
		$sql_check_sort_ava = " SELECT `prod_img_sort_order` FROM `tbl_products_images` WHERE `prod_img_prodid` = '".$prod_img_prodid."' and prod_img_type = '".$prod_img_type."' ";
		$sql_check_sort_ava .= " ORDER by prod_img_id DESC LIMIT 0,1";
		$result_check_sort_ava = mysqli_query($db_con,$sql_check_sort_ava) or die(mysqli_error($db_con));
		$num_rows_check_sort_ava = mysqli_fetch_array($result_check_sort_ava);
		if($num_rows_check_sort_ava != 0)
		{
			$prod_img_sort_order		= $num_rows_check_sort_ava['prod_img_sort_order']+1;	
		}
		else
		{
			$prod_img_sort_order		= 1;
		}		
	}
	
	$sql_last_rec 		= "Select * from tbl_products_images order by prod_img_id desc LIMIT 0,1";
	$result_last_rec 	= mysqli_query($db_con,$sql_last_rec) or die(mysqli_error($db_con));
	$num_rows_last_rec 	= mysqli_num_rows($result_last_rec);
	if($num_rows_last_rec == 0)
	{
		$prod_img_id 		= 1;				
	}
	else
	{
		$row_last_rec 	= mysqli_fetch_array($result_last_rec);				
		$prod_img_id 		= $row_last_rec['prod_img_id']+1;
	}
	$prod_img_status 	= 1;	
	$sql_insert_img_details 		= " INSERT INTO `tbl_products_images`(`prod_img_id`, `prod_img_prodid`, `prod_img_name`, `prod_img_type`,";
	$sql_insert_img_details 		.= " `prod_img_sort_order`, `prod_img_file_name`, `prod_img_status`,`prod_img_created_by`,`prod_img_created`) ";
	$sql_insert_img_details 		.= "  VALUES ('".$prod_img_id."','".$prod_img_prodid."','".$prod_img_name."','".$prod_img_type."',";
	$sql_insert_img_details 		.= "  '".$prod_img_sort_order."','".$prod_img_file_name."','".$prod_img_status."','".$uid."',now()) ";
	$result_insert_img_details		= mysqli_query($db_con,$sql_insert_img_details) or die(mysqli_error($db_con));
	if($result_insert_img_details)
	{
		return true;
	}
	else
	{
		return false;
	}
}	

$main_img_array_jpeg	= array();
$main_img_array_jpg		= array();
$main_img_array_png		= array();

$images_stored 	= "../myplanet_images/";

$sql_get_list_of_prod_id		= " SELECT * FROM `tbl_products_master` ";	// Get the all products from Product Master
$res_get_list_of_prod_id		= mysqli_query($db_con,$sql_get_list_of_prod_id) or die(mysql_error());
while($row_get_list_of_prod_id 	= mysqli_fetch_array($res_get_list_of_prod_id))
{
	$prod_model_number	= htmlspecialchars($row_get_list_of_prod_id['prod_model_number'], ENT_HTML5);
	$product_name		= htmlspecialchars(str_replace("'","&#146;",$row_get_list_of_prod_id['prod_name']), ENT_HTML5);
	$prod_orgid			= $row_get_list_of_prod_id['prod_orgid'];		// For Path [Create Folder]
	$prod_catid			= $row_get_list_of_prod_id['prod_catid'];		// For Path [Create Folder]
	$prod_subcatid		= $row_get_list_of_prod_id['prod_subcatid'];	// For Path [Create Folder]
	$prod_id			= $row_get_list_of_prod_id['prod_id'];			// For Path	[Create Folder]
	
	$dir 		= "../images/planet/org";	// For Path	[Create Folder for Organisation]
	$org_dir 	= $dir.$prod_orgid;
	if(is_dir($org_dir) === false)
	{
		mkdir($org_dir);
	}
	 $prod_dir = $org_dir."/prod_id_".$prod_id;	// For Path	[Create Folder for main Category]
	
	if(is_dir($prod_dir) === false)
	{
		mkdir($prod_dir);
	}
		
	$main_img_array_jpeg	= glob('../myplanet_images/'.$prod_model_number.'*.jpeg');	//=
	$main_img_array_jpg		= glob('../myplanet_images/'.$prod_model_number.'*.jpg');	//||= Get the JPG, JPEG and PNG images from "myplanet_images" folder
	$main_img_array_png		= glob('../myplanet_images/'.$prod_model_number.'*.png');	//=
	
	if(count($main_img_array_jpeg) > 0)	// check count for jpeg main images
	{
		for($i=0; $i<count($main_img_array_jpeg); $i++)
		{
			$prod_img		= $main_img_array_jpeg[$i];
			$explode_image_name	= explode('/',$prod_img);
			copy($prod_img, '../success_images/'.$explode_image_name[2]);	// moved into the "success_images" folder and cut (delete) from the "myplanet_images"
			
			if(file_exists($prod_img)) // Check, file is exist or not
			{
				createThumbnail($prod_img, $prod_dir, $explode_image_name[2], $prod_id, $product_name);
			}
		}
	}
	
	if(count($main_img_array_jpg) > 0)	// check count for jpg main images
	{
		for($i=0; $i<count($main_img_array_jpg); $i++)
		{
			$prod_img		= $main_img_array_jpg[$i];
			
			$explode_image_name	= explode('/',$prod_img);
			
			copy($prod_img, '../success_images/'.$explode_image_name[2]);
			
			if(file_exists($prod_img))
			{
				createThumbnail($prod_img, $prod_dir, $explode_image_name[2], $prod_id, $product_name);
			}
		}
	}
	
	if(count($main_img_array_png) > 0)	// check count for png main images
	{
		for($i=0; $i<count($main_img_array_png); $i++)
		{
			$prod_img		= $main_img_array_png[$i];
			$explode_image_name	= explode('/',$prod_img);
			
			copy($prod_img, '../success_images/'.$explode_image_name[2]);
			
			if(file_exists($prod_img))
			{
				createThumbnail($prod_img, $prod_dir, $explode_image_name[2], $prod_id, $product_name);
			}
		}
	} 
}
?>