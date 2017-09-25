<?php
include("include/routines.php");
include("include/db_con.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];

if((isset($obj->load_coupons)) == "1" && isset($obj->load_coupons))
{
	$response_array = array();	
	$start_offset   = 0;
	
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit_coupons;
	$search_text	= $obj->search_text_coupons;	
		
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT  tc.* ,";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.coup_created_by) AS name_created_by, ";
		$sql_load_data  .= " (SELECT fullname FROM `tbl_cadmin_users` WHERE id = tc.coup_modified_by) AS name_midified_by ";
		$sql_load_data  .= " FROM `tbl_coupons` AS tc WHERE 1=1 ";
			
		
		if(strcmp($utype,'1')!==0)
		{
			//$sql_load_data  .= " AND coup_created_by='".$uid."' ";
		}
		if($search_text != "")
		{
			$sql_load_data .= " and (coup_name like '%".$search_text."%' or coup_type like '%".$search_text."%' or coup_code like '%".$search_text."%'";
			
			$sql_load_data .= "	or coup_created_by like '%".$search_text."%' or coup_modified_by like '%".$search_text."%') ";	
		}
		$data_count		= 	dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data .=" ORDER BY coup_id DESC LIMIT $start, $per_page ";
	
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));		
			
		if(strcmp($data_count,"0") !== 0)
		{		
			$coupons_data = "";	
			$coupons_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$coupons_data .= '<thead>';
    	  	$coupons_data .= '<tr>';
         	$coupons_data .= '<th style="text-align:center">Sr No.</th>';
			$coupons_data .= '<th style="text-align:center">Coupon Name</th>';
			$coupons_data .= '<th style="text-align:center">Coupon Type</th>';
			$coupons_data .= '<th style="text-align:center">Coupon Code</th>';
			$coupons_data .= '<th style="text-align:center">Coupon Start Date</th>';	
			$coupons_data .= '<th style="text-align:center">Coupon End Date</th>';						
			$coupons_data .= '<th style="text-align:center">Created</th>';
			$coupons_data .= '<th style="text-align:center">Created By</th>';
			$coupons_data .= '<th style="text-align:center">Modified</th>';
			$coupons_data .= '<th style="text-align:center">Modified By</th>';
			$dis = checkFunctionalityRight("view_coupons.php",3);
			if($dis)
			{					
				$coupons_data .= '<th>Status</th>';											
			}
			$edit = checkFunctionalityRight("view_coupons.php",1);
			if($edit)
			{					
				$coupons_data .= '<th>Edit</th>';			
			}	
			$delete = checkFunctionalityRight("view_coupons.php",2);
			if($delete)
			{					
				$coupons_data .= '<th><div style="text-align:center"><input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}					
          	$coupons_data .= '</tr>';
      		$coupons_data .= '</thead>';
      		$coupons_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$coupons_data .= '<tr>';				
				$coupons_data .= '<td style="text-align:center">'.++$start_offset.'</td>';				
				$coupons_data .= '<td style="text-align:center"><input type="button" value=" '.ucwords($row_load_data['coup_name']).'" class="btn-link" id="'.$row_load_data['coup_id'].'" onclick="addMoreCoupon(this.id,\'view\');"></td>';
				$coupons_data .= '<td style="text-align:center">'.ucwords($row_load_data['coup_type']).'</td>';				
				$coupons_data .= '<td style="text-align:center">'.$row_load_data['coup_code'].'</td>';
				$coupons_data .= '<td style="text-align:center">'.$row_load_data['coup_start_date'].'</td>';	
				$coupons_data .= '<td style="text-align:center">'.$row_load_data['coup_end_date'].'</td>';							
				$coupons_data .= '<td style="text-align:center">'.$row_load_data['coup_created_date'].'</td>';
				$coupons_data .= '<td style="text-align:center">'.$row_load_data['name_created_by'].'</td>';
				$coupons_data .= '<td style="text-align:center">'.$row_load_data['coup_modified_date'].'</td>';
				$coupons_data .= '<td style="text-align:center">'.$row_load_data['name_midified_by'].'</td>';
				$dis = checkFunctionalityRight("view_coupons.php",3);
				if($dis)
				{					
					$coupons_data .= '<td style="text-align:center">';					
					if($row_load_data['coup_status'] == 1)
					{
						$coupons_data .= '<input type="button" value="Active" id="'.$row_load_data['coup_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
					}
					else
					{
						$coupons_data .= '<input type="button" value="Inactive" id="'.$row_load_data['coup_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
					}
					$coupons_data .= '</td>';	
				}
				$edit = checkFunctionalityRight("view_coupons.php",1);
				if($edit)
				{				
						$coupons_data .= '<td style="text-align:center">';
						$coupons_data .= '<input type="button" value="Edit" id="'.$row_load_data['coup_id'].'" class="btn-warning" onclick="addMoreCoupon(this.id,\'edit\');"></td>';				
				}
				$delete = checkFunctionalityRight("view_coupons.php",2);
				if($delete)
				{					
					$coupons_data .= '<td><div class="controls" align="center">';
					$coupons_data .= '<input type="checkbox" value="'.$row_load_data['coup_id'].'" id="customers'.$row_load_data['coup_id'].'" name="customers'.$row_load_data['coup_id'].'" class="css-checkbox coupons">';
					$coupons_data .= '<label for="customers'.$row_load_data['coup_id'].'" class="css-label"></label>';
					$coupons_data .= '</div></td>';										
				}
	          	$coupons_data .= '</tr>';															
			}	
      		$coupons_data .= '</tbody>';
      		$coupons_data .= '</table>';	
			$coupons_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$coupons_data);					
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available in Customers");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}
if($obj->req_type == "add" && isset($obj->req_type))
{
		
		
		$data = '';

		$data .= '<script type="text/javascript">
        $(document).ready(function() {
           $( "#datepicker1" ).datepicker();
           $( "#datepicker" ).datepicker();
        
           $(".js-example-basic-single").select2();


           

        });
          function chText()
         {
          var str=document.getElementById("coupon10");
          var regex=/[^a-z0-9]/gi;
          str.value=str.value.replace(regex ,"");
         }
		 function startdatef()
		 {
			 var startdatevalue = $("#datepicker").val();
			 if(startdatevalue == ""){
				 $("#error5").show();
			 }
			 else
			 {
				 $("#error5").hide();
			 }
		 }
		 function enddatef()
		 {
			 var enddatevalue = $("#datepicker1").val();
			 if(enddatevalue == ""){
				 $("#error6").show();
			 }
			 else
			 {
				 $("#error6").hide();
			 }
		 }
		 
         $("#vmp").validate({
    rules: {
        myselect: { required: true }
    }
});
         
        </script>';
       // $data .= '<style> .form-line-message{display: none; } </style>';
        $data .= '<style> label.error{position: absolute; left: 200px; } </style>';
		$data .='<input type="hidden" name="add_req" value="1">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Coupon name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" id="coupon_name" name="coupon_name" class="input-xlarge" data-rule-required="true" ';
		
		
		$data .= '/>';
		$data .= '</div>';		
		$data .= '<label for="tasktitel" class="control-label">Occurrence <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="cust_lname" name="cust_lname" class="input-large" data-rule-required="true" ';
		
		//$data .= '/>';
		$data .= '<select name="occurrence" class="js-example-basic-single"  data-rule-required="true" >';
		$data .= '<option value="">Select Occurrence </option>';
		$data .= '<option value="first time">First Time</option>';
		$data .= '<option value="other">Other</option>';
		$data .= '</select>';
		//$data .= '<input type="checkbox" id="occurrence" name="occurrence" data-rule-required="true" value="First time">First time';
		//$data .= '<input type="checkbox" style="margin: 10px;" name="occurrence" id="occurrence" data-rule-required="true" value="Other">Other';

		$data .= '</div>';
		$data .= '</div>';		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label"> Coupon type <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select id ="coupon9" name="coupon_type" data-rule-required="true" class = "js-example-basic-single" onChange="coupon(this.value);">';
		$data .= '<option value="">Select coupon or gift card</option>';
		$data .= '<option value="coupon">Coupon</option>';
		$data .= '<option value="gift_card">Gift Card</option>';
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';	
        
        //$data .= '<div hidden id="lkpm">aaaaaaaaaa</div>'; 

        $data .= '<div hidden class="control-group" id="lkpm">';
		$data .= '<label for="tasktitel" class="control-label">Coupon<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
        $data .= '<input type="text" maxlength="10" onKeyUp="chText()" onKeyDown="chText()" name="coupon_code" id="coupon_code" class="input-xlarge" data-rule-required="true">';
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div hidden class="control-group" id="mcps">';
		$data .= '<label for="tasktitel" class="control-label">Gift card<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
        $length_random = 16;
        $data .= '<input id="random_input" class="input-xlarge" data-rule-required="true"  type="text" name="gift_card" readonly><button style="margin: 10px;" type="button" onclick="generateRandom(\''.$length_random.'\')">Generate</button>';
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Discount type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
        $data .= '<select id ="discount9" name="coupon_discount"  class="js-example-basic-single" onchange="discounts(this.value);" data-rule-required="true" >';
        $data .= '<option value="">Select discount type</option>';
		$data .= '<option value="percent" >Percentage</option>';
		$data .= '<option value="price" >Price</option>';
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div hidden class="control-group" id="percent5">';
		$data .= '<label for="tasktitel" class="control-label">Percentage<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input id="disc_per" type="text" onkeypress="return checkpercentage(event);"onchange="checklength(this.id,100)" name="percent"  class="input-xlarge" data-rule-required="true"> &nbsp;&nbsp;%';
		$data .= '</div>';
		$data .= '</div>';

		 $data .= '<div hidden class="control-group" id="price5">';
		$data .= '<label for="tasktitel" class="control-label">Price<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input id="disc_price" type="text" onkeypress="return checkpercentage(event);" name="price" data-rule-required="true" class="input-xlarge">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-inr" aria-hidden="true"></i>';
		$data .= '</div>';
		$data .= '</div>';


	    $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Type Times use <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<select onchange="validate_lpu(this.value);" id ="type_times_use" name="type_times_use" data-rule-required="true" class = "js-example-basic-single">';
		$data .= '<option value="">Select Type times use</option>';
		$data .= '<option value="unlimited_use">Unlimited Use</option>';
		$data .= '<option value="one_time_use">One Time Use</option>';
		$data .= '<option value="limited_use">Limited Use</option>';
		$data .= '</select>'; 
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">No. of users<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="number" min="0" data-rule-required="true" id="noofusers" name="noofusers" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Limit per user<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="number" min="0" data-rule-required="true" name="lpu" id="lpu" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Minimum purchase</label>';
		$data .= '<div class="controls">';
		$data .= '<input type="number"  min="0" max="70000" name="mp" id="mp" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Start date</label>';
		$data .= '<div class="controls date form_datetime">';
        $data .= '<input type="text" name="sd" class="input-xlarge" id="start_date"  readonly="readonly"/>';
		 $data .= '<span class="add-on" style="padding:10px"><i  class="icon-remove"></i></span>';
		$data .= '<span style="cursor:pointer;width:14px;height:14px" class="add-on"><i class="icon-th"></i></span>';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">End date</label>';
		$data .= '<div class="controls date form_datetime">';
        $data .= '<input type="text" name="ed" class="input-xlarge" id="end_date"  readonly="readonly"/>';
		 $data .= '<span class="add-on" style="padding:10px"><i  class="icon-remove"></i></span>';
		$data .= '<span style="cursor:pointer;width:14px;height:14px" class="add-on"><i class="icon-th"></i></span>';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
        $data .='<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        //language:  "fr",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	$(".form_date").datetimepicker({
        language:  "fr",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
	$(".form_time").datetimepicker({
        language:  "fr",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
</script>';

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="radio" name="status" value="1"  data-rule-required="true">Active';
		$data .= '<input type="radio" style="margin:10px;" name="status" value="0"  data-rule-required="true">Inactive<br>';
		$data .= '</div>';
		$data .= '</div>';
  
		$data .= '<br><button class="btn-info" type="submit" onclick="">Add Coupon</button>';


	$response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);exit();
}
if((isset($_POST['add_req'])) == "1" && isset($_POST['add_req']))
{   
    $coupon_name = $_POST['coupon_name'];
	$occurrence = $_POST['occurrence'];
	$coupon_type = $_POST['coupon_type'];
	
	if($_POST['coupon_code']!="")
	{
		$coupon_code = $_POST['coupon_code'];
	}
	if($_POST['gift_card']!="")
	{
		$coupon_code = $_POST['gift_card'];
	}
	
	$coupon_discount = $_POST['coupon_discount'];
	
	if($_POST['price']!=""){
		$percent = $_POST['price'];
	}
	if($_POST['percent']!="")
	{
		$percent = $_POST['percent'];
	}
	$type_times_use = $_POST['type_times_use'];
	
	$nofusers = $_POST['noofusers'];
	
	if($type_times_use=="unlimited_use")
	{ 
	  $nofusers=0;
	  $coup_left_users=0;	
	  $coup_limit_per_user ="";
	}
	else if($type_times_use=="one_time_use")
	{ 
	  $coup_left_users=$nofusers;	
	  $coup_limit_per_user =1;
	}
	else 
	{
	   $coup_limit_per_user =$_POST['lpu'];
	  $coup_left_users=($nofusers * $coup_limit_per_user);
	}
	
	$lim_p_user = $_POST['lpu'];
	$min_purchase = $_POST['mp'];
	$start_date = $_POST['sd'];
	$end_date = $_POST['ed'];
	$status = $_POST['status'];
	
	$response_array = array();		
	$response_array = array("Success"=>"Success","resp"=>$percent);	
	$sql_check_coupon ="SELECT * FROM tbl_coupons WHERE  coup_code ='".$coupon_code."'";
	$result_check_coupon	= mysqli_query($db_con,$sql_check_coupon) or die(mysqli_error($db_con));
	$num_rows_get_coup      =mysqli_num_rows($result_check_coupon);
	if($num_rows_get_coup >=1)
	{
		$response_array = array("Success"=>"fail","resp"=>"Coupon Code Already Exist");		
	}
	else
	{
	$sql_add_coupon  ="INSERT INTO `tbl_coupons`(`coup_name`, `coup_type`, `coup_code`, `coup_occurrence`, `coup_discount_type`, ";
	$sql_add_coupon .="`coup_discount_amount`, `type_times_use`, `coup_no_of_users`, `coup_left_users`, `coup_limit_per_user`, `coup_min_purch`, `coup_start_date`, `coup_end_date`, ";
	$sql_add_coupon .=" `coup_status`, `coup_created_by`, `coup_created_date`) VALUES ";
	$sql_add_coupon .=" ( '".$coupon_name."', '".$coupon_type."', '".$coupon_code."', '".$occurrence."', '".$coupon_discount."', '".$percent."', ";
	$sql_add_coupon .=" '".$type_times_use."', '".$nofusers."', '".$coup_left_users."', '".$coup_limit_per_user."', '".$min_purchase."', '".$start_date."', '".$end_date."', ";
	$sql_add_coupon .=" '".$status."', '".$uid."','".$datetime."') ";
	$result_add_coupon	= mysqli_query($db_con,$sql_add_coupon) or die(mysqli_error($db_con));
	
	if($result_add_coupon)
	{
		$response_array = array("Success"=>"Success","resp"=>"Coupon Successfully Added","sql"=>$sql_add_coupon);	
	}
	else
	{
		 $response_array = array("Success"=>"fail","resp"=>"Coupon Not Added");	
	}
	}
	
	echo json_encode($response_array);	
}

if($obj->req_type == "edit" && isset($obj->req_type))
{       
	    $coup_id =$obj->coup_id; 
		$data = '';

		
       // $data .= '<style> .form-line-message{display: none; } </style>';
	   
		$sql_get_coupon  	= "SELECT * FROM tbl_coupons WHERE coup_id=".$coup_id."";
		$result_get_coupon	= mysqli_query($db_con,$sql_get_coupon) or die(mysqli_error($db_con));
		$coup_data          = mysqli_fetch_array($result_get_coupon);  
		
	   
        $data .= '<style> label.error{position: absolute; left: 200px; } </style>';
		$data .='<input type="hidden" name="req_type" value="1">';
		$data .='<input type="hidden" name="coup_id" value="'.$coup_data['coup_id'].'">';
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Coupon name <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" value="'.$coup_data['coup_name'].'" id="coupon_name" name="coupon_name" class="input-xlarge" data-rule-required="true" ';
		
		
		$data .= '/>';
		$data .='<input type="hidden" value="1" name="update_req">';	
		$data .= '</div>';	
		
		$data .= '<label for="tasktitel" class="control-label">Occurrence <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		//$data .= '<input type="text" id="cust_lname" name="cust_lname" class="input-large" data-rule-required="true" ';
		
		//$data .= '/>';
		$data .= '<select name="occurrence" class="js-example-basic-single"  data-rule-required="true" >';
		$data .= '<option value="">Select Occurrence </option>';
		$selected="";
		if($coup_data['coup_occurrence']=="first time")
		{
		 $selected="selected";
		}
			
		$data .= '<option '.$selected.' value="first time">First Time</option>';
		$selected="";
		if($coup_data['coup_occurrence']=="other")
		{
		 $selected="selected";
		}
		$data .= '<option '.$selected.' value="other">Other</option>';
		$data .= '</select>';
		//$data .= '<input type="checkbox" id="occurrence" name="occurrence" data-rule-required="true" value="First time">First time';
		//$data .= '<input type="checkbox" style="margin: 10px;" name="occurrence" id="occurrence" data-rule-required="true" value="Other">Other';

		$data .= '</div>';
		$data .= '</div>';		
		
        $data .= '<div  class="control-group" id="">';
		$data .= '<label for="tasktitel" class="control-label">Coupon Type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" readonly="readonly"  value="'.$coup_data['coup_type'].'" onKeyUp="chText()" onKeyDown="chText()" name="coupon_type" id="coupon_code" class="input-xlarge" data-rule-required="true">';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div  class="control-group" id="">';
		$data .= '<label for="tasktitel" class="control-label">Coupon<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input type="text" readonly="readonly"  value="'.$coup_data['coup_code'].'" onKeyUp="chText()" onKeyDown="chText()" name="coupon_code" id="coupon_code" class="input-xlarge" data-rule-required="true">';
		$data .= '</div>';
		$data .= '</div>';

		if($coup_data['coup_status']==0)
		{ 

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Discount type<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
        $data .= '<select id ="discount9" name="coupon_discount"  class="js-example-basic-single " onchange="discounts(this.value);" data-rule-required="true" >';
        $data .= '<option value="">Select discount type</option>';
		$selected="";
		$price_style='';
		$dis_value="";
	    if($coup_data['coup_discount_type']=="percent")
		{
			$selected="selected";	
			$price_style='hidden';
			$dis_p_value=$coup_data['coup_discount_amount'];
		}
		$data .= '<option  '.$selected.' value="percent" >Percentage</option>';
		$selected="";
		$percen_style='';
		
		if($coup_data['coup_discount_type']=="price")
		{
			$selected="selected";
			$percen_style='hidden';	
			$dis_pr_value=$coup_data['coup_discount_amount'];
		}
		$data .= '<option '.$selected.' value="price" >Price</option>';
		$data .= '</select>'; 
        $data .= '</div>';
		$data .= '</div>';


   
		 $data .= '<div '.$percen_style.' class="control-group" id="percent5">';
		 $data .= '<label for="tasktitel" class="control-label">Percentage<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		 $data .= '<div class="controls">';
         $data .= '<input value="'.$dis_p_value.'" type="text" id="disc_per" name="percent" onkeypress=" return checkpercentage(event)" onchange="checklength(this.id,100)" class="input-xlarge" data-rule-required="true"> &nbsp;&nbsp;%';
		 $data .= '</div>';
		 $data .= '</div>';
		 $data .= '<div '.$price_style.' class="control-group" id="price5">';
		 $data .= '<label for="tasktitel" class="control-label">Price<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		 $data .= '<div class="controls">';
         $data .= '<input id="disc_price" value="'.$dis_pr_value.'" type="text"  onkeypress=" return checkpercentage(event)" name="price" data-rule-required="true" class="input-xlarge">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-inr" aria-hidden="true"></i>';
		 $data .= '</div>';
		 $data .= '</div>';
		}
		else
		{
		 $data .= '<div  class="control-group" id="percent5">';
		 $data .= '<label for="tasktitel" class="control-label">Discount Types</label>';
		 $data .= '<div class="controls">';
         $data .= '<input readonly="readonly" value="'.$coup_data['coup_discount_type'].'" id="discount9" type="text" name="coupon_discount"  class="input-xlarge" > ';
		 $data .= '</div>';
		 $data .= '</div>';
		 $data .= '<div  class="control-group" id="price5">';
		 $data .= '<label for="tasktitel" class="control-label">Amount / Percentage</label>';
		 $data .= '<div class="controls">';
         $data .= '<input readonly="readonly" value="'.$coup_data['coup_discount_amount'].'" type="text" onkeypress=" return checkpercentage(event)" id="disc_price" name="price" onchange="" data-rule-required="true" class="input-xlarge">';
			 if($coup_data['coup_discount_type']=="price")
			 {
				 $data .='&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-inr" aria-hidden="true"></i>';
			 }
			 else
			 {
				  $data .='&nbsp;&nbsp;%';
			 }
		 $data .= '</div>';
		 $data .= '</div>';  	
		 	
		}
        $data .='<input type="hidden" name="old_noofusers" value="'.$coup_data['coup_no_of_users'].'">';
		$data .='<input type="hidden" name="old_lpu" value="'.$coup_data['coup_limit_per_user'].'">';
		$data .='<input type="hidden" name="left_users" value="'.$coup_data['coup_left_users'].'">';
	    $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Type Times use <sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .='<input type="hidden" name="type_time_use" value="'.$coup_data['type_times_use'].'">';
		$data .= '<select disabled  onchange="validate_lpu(this.value);"  id ="type_times_use" name="type_times_use1" data-rule-required="true" class = "js-example-basic-single">';
		$data .= '<option value="">Select Type times use</option>';
		$selected="";
		if($coup_data['type_times_use']=="unlimited_use")
		{
			$selected="selected";
			$style1=" readonly ";
			$style2=" readonly ";
		}
		$data .= '<option '.$selected.' value="unlimited_use">Unlimited Use</option>';
		$selected="";
		if($coup_data['type_times_use']=="one_time_use")
		{   
		    $selected="selected";
			$style1="  ";
			$style2="readonly";
		}
		$data .= '<option '.$selected.' value="one_time_use">One Time Use</option>';
		$selected="";
		if($coup_data['type_times_use']=="limited_use")
		{
			
			$selected=" selected ";
			$style1="";
			$style2="";
		}
		$data .= '<option '.$selected.' value="limited_use">Limited Use</option>';
		$data .= '</select>'; 
		//

		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">No. of users<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input '.$style1.' value="'.$coup_data['coup_no_of_users'].'" type="number" min="0" data-rule-required="true" id="noofusers" name="noofusers" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Limit per user<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';
		$data .= '<input '.$style2.' type="number" value="'.$coup_data['coup_limit_per_user'].'"  min="0" data-rule-required="true" id="lpu" name="lpu" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';

        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Minimum purchase</label>';
		$data .= '<div class="controls">';
        $data .= '<input type="number" value="'.$coup_data['coup_min_purch'].'"  min="0" max="70000" id="mp" name="mp" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';

	    $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Start date</label>';
		$data .= '<div class="controls date form_datetime">';
        $data .= '<input value="'.$coup_data['coup_start_date'].'"  type="text" name="sd" class="input-xlarge" id="start_date" readonly="readonly" />';
		 $data .= '<span class="add-on" style="padding:10px"><i  class="icon-remove"></i></span>';
		$data .= '<span style="cursor:pointer;width:14px;height:14px" class="add-on"><i class="icon-th"></i></span>';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">End date</label>';
		$data .= '<div class="controls date form_datetime">';
        $data .= '<input value="'.$coup_data['coup_end_date'].'" type="text" name="ed" class="input-xlarge" id="end_date" readonly="readonly"/>';
		 $data .= '<span class="add-on" style="padding:10px"><i  class="icon-remove"></i></span>';
		$data .= '<span style="cursor:pointer;width:14px;height:14px" class="add-on"><i class="icon-th"></i></span>';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
        $data .='<script type="text/javascript">
		 $(".js-example-basic-single").select2();
    $(".form_datetime").datetimepicker({
        //language:  "fr",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	$(".form_date").datetimepicker({
        language:  "fr",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
	$(".form_time").datetimepicker({
        language:  "fr",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
</script>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
		$data .= '<div class="controls">';


        $selected="";
        if($coup_data['coup_status']==1){
		 $selected="checked";	
		}
		$data .= '<input '.$selected.' type="radio" name="status" value="1"  data-rule-required="true">Active';
		$selected="";
		if($coup_data['coup_status']==0){
			$selected="checked";	
		}
		$data .= '<input '.$selected.' type="radio" style="margin:10px;" name="status" value="0"  data-rule-required="true">Inactive<br>';
		$data .= '</div>';
		$data .= '</div>';
  
		$data .= '<br><button type="submit" class="btn-info" onclick="">Update Coupon</button>';
        $data .= '&nbsp;&nbsp;<button class="btn-warning" type="reset" onclick="">Reset</button>';

		
		$response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);
}
if($_POST['update_req'] == "1" && isset($_POST['update_req']))
{   
    $coupon_name = $_POST['coupon_name'];
	$occurrence = $_POST['occurrence'];
	$coupon_type = $_POST['coupon_type'];
	
	if(isset($_POST['coupon_code']))
	{
		$coupon_code = $_POST['coupon_code'];
	}
	if(isset($_POST['gift_card']))
	{
		$coupon_code = $_POST['gift_card'];
	}
	
	$coupon_discount = $_POST['coupon_discount'];
	
	if($_POST['price'] !="")
	{
		$percent = $_POST['price'];
	}
	if($_POST['percent']!="")
	{
		$percent = $_POST['percent'];
	}
	
     
	
	$type_times_use = $_POST['type_time_use'];
    $nofusers = $_POST['noofusers'];
	
	if($type_times_use=="unlimited_use")
	{
	  $coup_left_users=0;	
	  $coup_limit_per_user ="0";
	}
	else if($type_times_use=="one_time_use")
	{
	  if($_POST['noofusers']!=$_POST['old_noofusers'])
		{
		 
		  $left_diff       		=$_POST['old_noofusers']-$_POST['left_users'];
		  $coup_left_users 		=($_POST['noofusers'])-$left_diff;
		  $coup_limit_per_user  =$_POST['lpu'];
		}
	    else
		{
		 $coup_limit_per_user  =$_POST['lpu'];
         $coup_left_users =$_POST['left_users'];
		}
	}
	else 
	{  
	    if($_POST['noofusers']!=$_POST['old_noofusers'] || $_POST['lpu'] !=$_POST['old_lpu'])
		{
		  $old_leftusers   =($_POST['old_noofusers'] * $_POST['old_lpu']);
		  $left_diff       =($old_leftusers)-$_POST['left_users'];
		  $coup_left_users =($_POST['noofusers']*$_POST['lpu'] )-$left_diff;
		  $coup_limit_per_user =$_POST['lpu'];
		}
	    else
		{
			$coup_left_users =$_POST['left_users'];
			$coup_limit_per_user =$_POST['lpu'];
		}
			
	    
	}
	$min_purchase = $_POST['mp'];
	$start_date = $_POST['sd'];
	$end_date = $_POST['ed'];
	
	
	$status = $_POST['status'];
	$coup_id = $_POST['coup_id'];
	
	
	$sql_update_coupon  = " UPDATE tbl_coupons SET coup_name='".$coupon_name."' ,coup_type='".$coupon_type."' ,coup_code='".$coupon_code."' ,coup_occurrence='".$occurrence."', ";
	$sql_update_coupon .= "coup_discount_type = '".$coupon_discount."', coup_discount_amount='".$percent."',type_times_use ='".$type_times_use."',";
	$sql_update_coupon .="coup_no_of_users ='".$nofusers."',coup_left_users='".$coup_left_users."',coup_limit_per_user='".$coup_limit_per_user."',coup_min_purch='".$min_purchase."',";
    $sql_update_coupon .="coup_start_date='".$start_date."',coup_end_date='".$end_date."',coup_status='".$status."',coup_modified_by='".$uid."',";
	$sql_update_coupon .="coup_modified_date ='".$datetime."' WHERE coup_id='".$coup_id."'";
	$result_update_coupon	= mysqli_query($db_con,$sql_update_coupon) or die(mysqli_error($db_con));
	
	
	
	if($result_update_coupon)
	{
		$response_array = array("Success"=>"Success","resp"=>"Coupon  Updated Successfully","sql_update_coupon"=>$sql_update_coupon);
	}
	else
	{
		 $response_array = array("Success"=>"fail","resp"=>"Coupon Not Update");	
	}
	
	echo json_encode($response_array);	
}
if($obj->req_type == "view" && isset($obj->req_type))
{       
	    $coup_id =$obj->coup_id; 
		$data = '';

		$data .= '<script type="text/javascript">
        $(document).ready(function() {
           $( "#datepicker1" ).datepicker();
           $( "#datepicker" ).datepicker();
        
           $(".js-example-basic-single").select2();


           

        });
          function chText()
         {
          var str=document.getElementById("coupon10");
          var regex=/[^a-z0-9]/gi;
          str.value=str.value.replace(regex ,"");
         }
		 function startdatef()
		 {
			 var startdatevalue = $("#datepicker").val();
			 if(startdatevalue == ""){
				 $("#error5").show();
			 }
			 else
			 {
				 $("#error5").hide();
			 }
		 }
		 function enddatef()
		 {
			 var enddatevalue = $("#datepicker1").val();
			 if(enddatevalue == ""){
				 $("#error6").show();
			 }
			 else
			 {
				 $("#error6").hide();
			 }
		 }
		 
         $("#vmp").validate({
    rules: {
        myselect: { required: true }
    }
});
         
        </script>';
       // $data .= '<style> .form-line-message{display: none; } </style>';
	   
		$sql_get_coupon  	= "SELECT * FROM tbl_coupons WHERE coup_id=".$coup_id."";
		$result_get_coupon	= mysqli_query($db_con,$sql_get_coupon) or die(mysqli_error($db_con));
		$coup_data          = mysqli_fetch_array($result_get_coupon);  
		
	   
        $data .= '<style> label.error{position: absolute; left: 200px; } </style>';
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Coupon name</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly type="text" value="'.$coup_data['coup_name'].'"  class="input-xlarge"/>';
		$data .= '</div>';
		$data .='</div>';	
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Occurrence <sup class="validfield"></label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly type="text" value="'.$coup_data['coup_occurrence'].'"  class="input-xlarge" "/> ';
		$data .= '</div>';
		$data .='</div>';
		
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Coupon type </label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly type="text" value="'.$coup_data['coup_type'].'"  class="input-xlarge" /> ';	
		$data .= '</div>';
		$data .='</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Coupon Code  </label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly type="text" value="'.$coup_data['coup_code'].'"  class="input-xlarge" /> ';	
		$data .= '</div>';
		$data .='</div>';
		
         $data .= '<div class="control-group">';
		 $data .= '<label for="tasktitel" class="control-label">Discount type </label>';
		 $data .= '<div class="controls">';
		 $data .= '<input readonly type="text" value="'.$coup_data['coup_discount_type'].'"  class="input-xlarge" /> ';	
		 $data .= '</div>';
		 $data .='</div>';
         
	     
		 if($coup_data['coup_discount_type']=="percent")
		 {
			 $data .= '<div class="control-group" id="percent5">';
			 $data .= '<label for="tasktitel" class="control-label">Percentage</label>';
			 $data .= '<div class="controls">';
			 $data .= '<input readonly value="'.$coup_data['coup_discount_amount'].'" type="text" name="percent" class="input-xlarge"> &nbsp;&nbsp;%';
			 $data .= '</div>';
			 $data .= '</div>';
		 }
		 else 
		 {
			$data .= '<div  class="control-group" id="price5">';
			$data .= '<label for="tasktitel" class="control-label">Price</label>';
			$data .= '<div class="controls">';
			$data .= '<i class="fa fa-inr" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<input readonly value="'.$coup_data['coup_discount_amount'].'" type="text"  class="input-xlarge">';
			$data .= '</div>';
			$data .= '</div>';
		}
        
		$data .= '<div  class="control-group" id="price5">';
		$data .= '<label for="tasktitel" class="control-label">Type Times use </label>';
		$data .= '<div class="controls">';
        $data .= '<input readonly value="'.$coup_data['type_times_use'].'" type="text"  class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';
		
		
		
	    $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">No. of users</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly value="'.$coup_data['coup_no_of_users'].'" type="number" min="0" data-rule-required="true" name="noofusers" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Limit per user</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly type="number" value="'.$coup_data['coup_limit_per_user'].'"  min="0" data-rule-required="true" name="lpu" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';
 
        $data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Minimum purchase</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly type="number" value="'.$coup_data['coup_min_purch'].'"  min="0" max="70000" name="mp" class="input-xlarge">';
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Start date</label>';
		$data .= '<div class="controls">';
		$data .= '<input readonly type="text" value="'.$coup_data['coup_start_date'].'" name="sd" class="input-xlarge" />';
		$data .= '<label class="error5" style="display:none; color: red; position: absolute; left: 200px; " id="error5">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">End date</label>';
		$data .= '<div class="controls">';
        $data .= '<input readonly type="text" value="'.$coup_data['coup_end_date'].'" class="input-xlarge"  name="ed" id="" onchange = "date_validate(); enddatef();" />';
		$data .= '<label class="error6" style="display:none; position: absolute; color: red; left: 200px;" id="error6">This field is required.</label>';
		$data .= '</div>';
		$data .= '</div>';

		$data .= '<div class="control-group">';
		$data .= '<label for="tasktitel" class="control-label">Status</label>';
		$data .= '<div class="controls">';


       
        if($coup_data['coup_status']==1){
		$data .= 'Active';
		}
		
	
		if($coup_data['coup_status']==0){
		$data .= 'Inactive';	
		}
		
		$data .= '</div>';
		$data .= '</div>';
  
		


		
		$response_array = array("Success"=>"Success","resp"=>$data);				
	    echo json_encode($response_array);
}
if((isset($obj->random)) == "1" && isset($obj->random))
{    

     $length = $obj->len; 	
     if($length!=0){
     $rndno = generateRandomString($length);
     $response_array = array("Success"=>"Success","resprandom"=>$rndno);
     echo json_encode($response_array);
     }
     else
     {  $error = "The length supplied is zero!";
     	$response_array = array("Success"=>"Fail","resprandom"=>$error);
     echo json_encode($response_array);
     }
}
if((isset($obj->changeStatus)) == "1" && isset($obj->changeStatus))
{    
     $response_array= array();
     $curr_status  = $obj->curr_status; 
	 $coup_id      = $obj->coup_id;
	 $sql_update_status=" UPDATE tbl_coupons SET coup_status='".$curr_status."',coup_modified_by='".$uid."', ";
	 $sql_update_status .=" coup_modified_date ='".$datetime."' WHERE coup_id='".$coup_id."' ";
	 
     $result_update_status	= mysqli_query($db_con,$sql_update_status) or die(mysqli_error($db_con));
	 if($result_update_status){
     $response_array = array("Success"=>"Success","resp"=>"Status Updated Successfully");
     echo json_encode($response_array);
     }
     else
     {  
     	$response_array = array("Success"=>"Fail","resp"=>"Status Updation Failed");
     echo json_encode($response_array);
     }
}


if((isset($obj->delete_coupons)) == "1" && isset($obj->delete_coupons))
{   
	$data 			="";
	$coupons 		= $obj->coupons;
	foreach($coupons as $coup_id)
	{
	$sql_delete_coup	= " DELETE FROM `tbl_coupons` WHERE `coup_id` = '".$coup_id."' ";
	$result_delete_coup= mysqli_query($db_con,$sql_delete_coup) or die(mysqli_error($db_con));
	}
	 if($result_delete_coup){
     $response_array = array("Success"=>"Success","resp"=>"");
     echo json_encode($response_array);
     }
     else
     {  
     	$response_array = array("Success"=>"Fail","resp"=>"");
     echo json_encode($response_array);
	 }
	
}




?>