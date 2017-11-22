<?php
include("include/db_con.php");
include("include/query-helper.php");
include("include/routines.php");
$json = file_get_contents('php://input');
$obj = json_decode($json);
//var_dump($obj = json_decode($json));
$uid				= $_SESSION['panel_user']['id'];
$utype				= $_SESSION['panel_user']['utype'];


//------------------this is used for inserting records---------------------
if((isset($_POST['insert_fixtures'])) == "1" && isset($_POST['insert_fixtures']))
{
	$data['fixture_place']       = strtolower(mysqli_real_escape_string($db_con,$_POST['fixture_place']));
	$fixture_date			     = mysqli_real_escape_string($db_con,$_POST['fixture_date']);
	$data['fixture_time_start']  = mysqli_real_escape_string($db_con,$_POST['fixture_time_start']);
	$data['fixture_time_end']    = mysqli_real_escape_string($db_con,$_POST['fixture_time_end']);
	
	
	
	$fixture_date                = explode('-',$fixture_date);// d/m/y
	$data['fixture_date']        = $fixture_date[2].'-'.$fixture_date[1].'-'.$fixture_date[0];
	
	$data['team_a']          	 = strtolower(mysqli_real_escape_string($db_con,$_POST['team_a']));
	$data['team_b']          	 = strtolower(mysqli_real_escape_string($db_con,$_POST['team_b']));
	$data['pool']            	 = strtolower(mysqli_real_escape_string($db_con,$_POST['pool']));
	$data['field']           	 = strtolower(mysqli_real_escape_string($db_con,$_POST['field']));
	
	$data['created_by']   		 = $uid;
	$data['created_date']        = $datetime;
	
	$sql_check             = " SELECT * FROM tbl_fixtures WHERE (team_a='".$data['team_a']."' or team_b='".$data['team_a']."') ";
	$sql_check            .= "  AND (team_a='".$data['team_b']."' or team_b='".$data['team_b']."')  AND fixture_date='".$data['fixture_date']."' AND fixture_time_start ='".$data['fixture_time_start']."' ";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = insert('tbl_fixtures',$data); 
		    
			if($insert_id)
			{
				quit('Fixture Added Successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
			
		  
	}
	else
	{
		quit('Fixture already Exist...!');
	}	
}


if((isset($_POST['update_fixture'])) == "1" && isset($_POST['update_fixture']))
{
	$data['fixture_place']       = strtolower(mysqli_real_escape_string($db_con,$_POST['fixture_place']));
	$fixture_date			     = mysqli_real_escape_string($db_con,$_POST['fixture_date']);
	$data['fixture_time_start']  = mysqli_real_escape_string($db_con,$_POST['fixture_time_start']);
	$data['fixture_time_end']    = mysqli_real_escape_string($db_con,$_POST['fixture_time_end']);
	$fixture_id 				 = mysqli_real_escape_string($db_con,$_POST['fixture_id']);
	
	
	
	$fixture_date                = explode('-',$fixture_date);// d/m/y
	$data['fixture_date']        = $fixture_date[2].'-'.$fixture_date[1].'-'.$fixture_date[0];
	
	$data['team_a']          	 = strtolower(mysqli_real_escape_string($db_con,$_POST['team_a']));
	$data['team_b']          	 = strtolower(mysqli_real_escape_string($db_con,$_POST['team_b']));
	$data['pool']            	 = strtolower(mysqli_real_escape_string($db_con,$_POST['pool']));
	$data['field']           	 = strtolower(mysqli_real_escape_string($db_con,$_POST['field']));
	
	$data['created_by']   		 = $uid;
	$data['created_date']        = $datetime;
	
	$sql_check             = " SELECT * FROM tbl_fixtures WHERE (team_a='".$data['team_a']."' or team_b='".$data['team_a']."') ";
	$sql_check            .= "  AND (team_a='".$data['team_b']."' or team_b='".$data['team_b']."')  AND fixture_date='".$data['fixture_date']."' AND fixture_time_start ='".$data['fixture_time_start']."' AND fixture_id !='".$fixture_id."'";
	$res_check             = mysqli_query($db_con,$sql_check) or die(mysqli_error($db_con));
	$num_check             = mysqli_num_rows($res_check);
	if($num_check==0)
	{
		    $insert_id = update('tbl_fixtures',$data,array('fixture_id'=>$fixture_id)); 
		    
			if($insert_id)
			{
				quit('Fixture updated Successfully...!',1);
			}
			else
			{
				quit('Please try letter...!');
			}
			
		  
	}
	else
	{
		quit('Fixture already Exist...!');
	}	
}



if((isset($obj->load_competition)) == "1" && isset($obj->load_competition))
{
	$response_array = array();	
	$start_offset   = 0;
	$page 			= $obj->page;	
	$per_page		= $obj->row_limit;
	$search_text	= mysqli_real_escape_string($db_con,$obj->search_text);

	$ft_date 		= $obj->ft_date;	
	$ft_time		= $obj->ft_time;
	
	if($page != "" && $per_page != "")	
	{
		$cur_page 		= $page;
		$page 	   	   	= $page - 1;
		$start_offset  += $page * $per_page;
		$start 			= $page * $per_page;
			
		$sql_load_data  = " SELECT tf.*,
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tf.created_by) AS name_created_by, 
								(SELECT fullname FROM `tbl_cadmin_users` WHERE id = tf.modified_by) AS name_midified_by 
							FROM `tbl_fixtures` AS tf WHERE 1=1";
		if(strcmp($utype,'1') !== 0)
		{
			$sql_load_data  .= " AND created_by='".$uid."' ";
		}

		if($ft_date!="")
		{
			$sql_load_data .=" AND fixture_date='".$ft_date."'";
		}

		if($ft_time!="")
		{
			$sql_load_data .=" AND fixture_time_start like '%".$ft_time."%'";
		}

		if($search_text != "")
		{
			$sql_load_data .= " and (fixture_place like '%".$search_text."%' or fixture_date like '%".$search_text."%' ";
			$sql_load_data .= " or team_a like '%".$search_text."%' or 	team_b like '%".$search_text."%') ";	
		}
		$data_count		  = dataPagination($sql_load_data,$per_page,$start,$cur_page);		
		$sql_load_data   .= " GROUP BY fixture_date  ORDER BY fixture_date DESC LIMIT $start, $per_page ";
		$result_load_data = mysqli_query($db_con,$sql_load_data) or die(mysqli_error($db_con));	
				
		if(strcmp($data_count,"0") !== 0)
		{		
			$area_data  = "";	
			$area_data .= '<table id="tbl_user" class="table table-bordered dataTable" style="width:100%;text-align:center">';
    	 	$area_data .= '<thead>';
    	  	$area_data .= '<tr>';
         	$area_data .= '<th style="text-align:center">Sr No.</th>';
			$area_data .= '<th style="text-align:center">Date</th>';
			$area_data .= '<th style="text-align:center">Time</th>';
			$area_data .= '<th style="text-align:center">Team A</th>';
			$area_data .= '<th style="text-align:center">Team B</th>';
			$area_data .= '<th style="text-align:center">Pool</th>';
			$area_data .= '<th style="text-align:center">Field</th>';
	        $dis = checkFunctionalityRight("view_fixtures.php",3);
		
			if($dis)
			{			
				$area_data .= '<th style="text-align:center">Status</th>';						
			}
			$edit = checkFunctionalityRight("view_fixtures.php",1);
			
			if($edit)
			{			
				$area_data .= '<th style="text-align:center">Edit</th>';			
			}
			$delete = checkFunctionalityRight("view_fixtures.php",2);
			
			if($delete)
			{			
				$area_data .= '<th style="text-align:center"><div style="text-align:center">';
				$area_data .= '<input type="button"  value="Delete" onclick="multipleDelete();" class="btn-danger"/></div></th>';
			}
          	$area_data .= '</tr>';
      		$area_data .= '</thead>';
      		$area_data .= '<tbody>';
			while($row_load_data = mysqli_fetch_array($result_load_data))
			{
	    	  	$area_data .= '<tr>';	

	    	  	$sql_get_fixture = " SELECT * FROM tbl_fixtures WHERE fixture_date='".$row_load_data['fixture_date']." ' ";
	    	  	if($search_text != "")
				{
					$sql_get_fixture .= " and (fixture_place like '%".$search_text."%' or fixture_date like '%".$search_text."%' ";
					$sql_get_fixture .= " or team_a like '%".$search_text."%' or 	team_b like '%".$search_text."%') ";	
				}
				if($ft_time!="")
				{
					$sql_get_fixture .=" AND fixture_time_start like '%".$ft_time."%'";
				}
	    	  	$res_get_fixture = mysqli_query($db_con,$sql_get_fixture) or die(mysqli_error($db_con));
	    	  	$num_get_fixture = mysqli_num_rows($res_get_fixture);

				$area_data .= '<td style="text-align:center;vertical-align:middle" rowspan="'.$num_get_fixture.'">'.++$start_offset.'</td>';				
			   

			   	$area_data .= '<td rowspan="'.$num_get_fixture.'" style="text-align:center;vertical-align:middle">
			   	               <input type="button" value="'.ucwords($row_load_data['fixture_date']).'" class="btn-link" id="'.$row_load_data['fixture_id'].'" onclick="addMoreFixture1(this.id,\'view\');"></td>';

			   $i=0;
			  
			   while($row = mysqli_fetch_array($res_get_fixture))
			   {
			   		if($i!=0)
			   		{
			   			$area_data .='<tr>';
			   			$i++;
			   		}


					
					    $area_data .= '<td  style="text-align:center">'.ucwords($row['fixture_time_start']).' to '.ucwords($row['fixture_time_end']).'</td>';
					    
					    

					   			$area_data .= '<td style="text-align:center">'.strtoupper($row['team_a']).'</td>';		
								$area_data .= '<td style="text-align:center">'.strtoupper($row['team_b']).'</td>';		
								$area_data .= '<td style="text-align:center">'.strtoupper($row['pool']).'</td>';
								$area_data .= '<td style="text-align:center">'.strtoupper($row['field']).'</td>';
								
								$dis = checkFunctionalityRight("view_fixtures.php",3);
							
								if($dis)
								{					
									$area_data .= '<td style="text-align:center">';					
									if($row['status']!='0')
									{
										$area_data .= '<input type="button" value="Active" id="'.$row['fixture_id'].'" class="btn-success" onclick="changeStatus(this.id,0);">';
									}
									else
									{
										$area_data .= '<input type="button" value="Inactive" id="'.$row['fixture_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
									}
									$area_data .= '</td>';
								}
								$edit = checkFunctionalityRight("view_fixtures.php",1);
							
								if($edit)
								{				
									$area_data .= '<td style="text-align:center">';
									$area_data .= '<input type="button" value="Edit" id="'.$row['fixture_id'].'" class="btn-warning" onclick="addMoreFixture(this.id,\'edit\');"></td>';												
								}
								$delete = checkFunctionalityRight("view_fixtures.php",2);
								
								if($delete)
								{			
								
									if($row['status']==0)
									{
										$area_data .= '<td style="text-align:center">';					
									    $area_data .= '<input type="button" value="Inactive" id="'.$row['fixture_id'].'" class="btn-danger" onclick="changeStatus(this.id,1);">';
									    $area_data .= '</td>';
									}
									else
									{
										$area_data .= '<td><div class="controls" align="center">';
										$area_data .= '<input type="checkbox" value="'.$row['fixture_id'].'" id="batch'.$row['fixture_id'].'" name="batch'.$row['cefixture_idnter_id'].'" class="css-checkbox batch">';
										$area_data .= '<label for="batch'.$row['fixture_id'].'" class="css-label"></label>';
										$area_data .= '</div></td>';	
									}
								}
							



					


					$area_data .='<tr>';

			   }// while end

				
	          	$area_data .= '</tr>';															
			}	
      		$area_data .= '</tbody>';
      		$area_data .= '</table>';	
			$area_data .= $data_count;
			$response_array = array("Success"=>"Success","resp"=>$area_data);				
		}
		else
		{
			$response_array = array("Success"=>"fail","resp"=>"No Data Available");
		}
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"No Row Limit and Page Number Specified");
	}
	echo json_encode($response_array);	
}



if((isset($obj->load_fixture_parts)) == "1" && isset($obj->load_fixture_parts))
{
	$fixture_id     = $obj->fixture_id;
	$req_type       = $obj->req_type;
	$response_array = array();
	if($req_type != "")
	{
		if($fixture_id != "" && $req_type != "add")
		{
			$sql_fixture_data 	    = "Select * from tbl_fixtures where fixture_id = '".$fixture_id."' ";
			$res_fixture_data 	= mysqli_query($db_con,$sql_fixture_data) or die(mysqli_error($db_con));
			$row_fixture_data		= mysqli_fetch_array($res_fixture_data);		
		}	
			
		$data = '';
		if($fixture_id != "" && $req_type == "edit")
		{
			$data .= '<input type="hidden" name="fixture_id" id="fixture_id" value="'.$fixture_id.'">';
			$data .= '<input type="hidden" name="update_fixture" id="update_fixture" value="1">';
		}        
		
		
		if($req_type=='add')
		{
				$data .= '<input type="hidden" name="insert_fixtures" id="insert_batch" value="1">';
		}
		
		if($req_type !="view")
		{
			
			//////=============================================Start : Place Name======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Place Name<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input type="text" id="fixture_place" name="fixture_place" class="input-large keyup-char" placeholder="Place Name" data-rule-required="true"  value="COMMUNITY CUP - ST. ANDREWS, BANDRA"';
			if($fixture_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_fixture_data['fixture_place'].'"'; 
			}
			elseif($fixture_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_fixture_data['fixture_place'].'" disabled'; 				
			}
			$data .= '/><br>';
			$data .= '</div>';
			$data .= '</div> <!-- Competition Name -->';
			


			//////=============================================Start : Duration======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Date<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input  type="text" id="fixture_date" value="22-11-2017" readonly="readonly" name="fixture_date" class="input-large datepicker" placeholder="Date" data-rule-required="true" ';
			
			if($fixture_id != "" && $req_type == "edit")
			{
				$sdate = explode('-',$row_fixture_data['fixture_date']);
				$sdate = $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
				$data .= ' value="'.$sdate.'"'; 
			}
			elseif($fixture_id != "" && $req_type == "view")
			{
				$sdate = explode('-',$row_fixture_data['fixture_date']);
				$sdate = $sdate[2].'-'.$sdate[1].'-'.$sdate[0];
				$data .= ' value="'.$sdate.'" disabled'; 				
			}
			$data .= '/>';
			
			
			$data .= '</div>';
			$data .= '</div> <!-- Coach Name -->';
			$data .="<script type=\"text/javascript\">	 $( '.datepicker' ).datepicker({
			changeMonth	: true,
			changeYear	: true,
			format: 'dd-mm-yyyy',
			yearRange 	: 'c:c',//replaced 'c+0' with c (for showing years till current year)
			startDate: '+d',
				
		   });</script>";


			//////=============================================Start : Place time======================================


			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Time<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input  type="text" id="fixture_time_start" name="fixture_time_start" class="input-large keyup-char timepicker" placeholder="Time" data-rule-required="true" ';
			if($fixture_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_fixture_data['fixture_time_start'].'"'; 
			}
			elseif($fixture_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_fixture_data['fixture_time_start'].'" disabled'; 				
			}
			else
			{
				$data .=' value="8.00 "';
			}
			$data .= '/>&nbsp;&nbsp;to&nbsp;&nbsp;';

			$data .= '<input  type="text" id="fixture_time_end" name="fixture_time_end" class="input-large keyup-char timepicker" placeholder="Time" data-rule-required="true" ';
			if($fixture_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_fixture_data['fixture_time_end'].'"'; 
			}
			elseif($fixture_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_fixture_data['fixture_time_end'].'" disabled'; 				
			}
			else
			{
				$data .= ' value="8.30 AM"';
			}
			$data .= '/>';


			$data .= '</div>';
			$data .= '</div> <!-- Competition time -->';
			$data .='<script type="application/javascript">';
		
			$data .="
				$('.timepicker').timepicker({
		        showInputs: false,
		        showMeridian : true,
		        defaultTime : false,
		        showSeconds: false
		   		 }); ";
			
			$data .='$(".timepicker").click(function(){
                  $(".bootstrap-timepicker-hour").html("'.date('h').'");
	              $(".bootstrap-timepicker-minute").html("'.date('i').'");
	              $(".bootstrap-timepicker-meridian").html("'.date('A').'");
			}); ';
			
			$data .='</script>';


			//////=============================================Start : Place time======================================


			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Teams<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input  type="text" id="team_a" name="team_a" class="input-large keyup-char" placeholder="Team A" data-rule-required="true" ';
			if($fixture_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_fixture_data['team_a'].'"'; 
			}
			elseif($fixture_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_fixture_data['team_a'].'" disabled'; 				
			}
			$data .= '/>&nbsp;&nbsp;to&nbsp;&nbsp;';

			$data .= '<input  type="text" id="team_b" name="team_b" class="input-large keyup-char" placeholder="Team B" data-rule-required="true" ';
			if($fixture_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_fixture_data['team_b'].'"'; 
			}
			elseif($fixture_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_fixture_data['team_b'].'" disabled'; 				
			}
			$data .= '/>';


			$data .= '</div>';
			$data .= '</div> <!-- Team -->';
			
			
			
			//////=============================================Start :Student Limit======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Pool<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input   type="text" id="pool" name="pool" class="input-large" placeholder="Pool" data-rule-required="true" ';
			if($fixture_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_fixture_data['pool'].'"'; 
			}
			elseif($fixture_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_fixture_data['pool'].'" disabled'; 				
			}
			$data .= '/>';
			$data .= '</div>';
			$data .= '</div> <!-- Pool-->';


			//////=============================================Start :Student Limit======================================
			$data .= '<div class="control-group">';
			$data .= '<label for="tasktitel" class="control-label">Field<sup class="validfield"><span style="color:#F00;font-size:20px;">*</span></sup></label>';
			$data .= '<div class="controls">';
			$data .= '<input   type="text" id="field" name="field" class="input-large" placeholder="Field" data-rule-required="true" ';
			if($fixture_id != "" && $req_type == "edit")
			{
				$data .= ' value="'.$row_fixture_data['field'].'"'; 
			}
			elseif($fixture_id != "" && $req_type == "view")
			{
				$data .= ' value="'.$row_fixture_data['field'].'" disabled'; 				
			}
			$data .= '/>';
			$data .= '</div>';
			$data .= '</div> <!-- Pool-->';
			
			
			$data .= '<label for="radiotest" class="css-label"></label>';
			$data .= '<label name = "radiotest" ></label>';
			$data .= '</div>';
			$data .= '</div><!--Status-->';
			
			$data .= '<div class="form-actions">';
			if($fixture_id == "" && $req_type == "add")
			{
				$data .= '<button type="submit" name="reg_submit_add" class="btn-success">Add Fixture</button>';			
			}
			elseif($fixture_id != "" && $req_type == "edit")
			{
				$data .= '<button type="submit" name="reg_submit_edit" class="btn-success">Update Fixture</button>';			
			}			
			$data .= '</div> <!-- Save and cancel -->';	
			
		}
		else
		{
			if($row_fixture_data['competition_status']==1)
			{
				$bgcolor = '#18BB7C';
				$color   = 'white';
			}
			else
			{
				$bgcolor = '#da4f49';
				$color    ='';
			}
			//==================Start :  Heading  ===========================================//
			$data .='<div class="control-group" style="background-color:'.$bgcolor.'">';
			
			$data .='<div class="span2">';
				
			$data .='</div>';
			
			$data .='<div class="span8">';
			    $data .='<div style="padding-bottom:20px;">';
					$data .='<h3 class=""  style="color:White">'.ucwords($row_fixture_data['competition_name']).'</h3>';
					
					$data .='<div class="control-group" style="background-color:'.$bgcolor.'">';
					    $start_date    = explode('-',$row_comp_data['start_date']);
						$data .='<div class="span6">
						<span class="head2" style="color:White"> Place: '.ucwords($row_comp_data['competition_place']).'</span><br>
						<span class="head2" style="color:White">Strat Date: '.@$start_date[2].' / '.@$start_date[1].' / '.@$start_date[0].'</span><br>
						
						';
						$data .='</div>';
						
						$end_date    = explode('-',$row_comp_data['end_date']);
						$data .='<div class="span6">
						<span class="head2" style="color:White">Team Limit :'.ucwords($row_comp_data['competition_limit']).'</span><br>
						<span class="head2" style="color:White">End Date: '.@$end_date[2].' / '.@$end_date[1].' / '.@$end_date[0].'</span><br>
						';
						$data .='</div>';
						
					$data .='</div>';
				$data .='</div>';
			$data .='</div>';
			
			$data .='<div class="span2">';
				
			$data .='</div>';
			
			$data .='</div>';// control-group end
			
			//==================End : Heading  ===========================================//
			
			
			
			//==================Start : Coaches  ===========================================//
			$sql_get_stud  = "SELECT * FROM tbl_competition_team  as tct ";
			$sql_get_stud .= " INNER JOIN tbl_team as  tm ON tct.team_id =tm.team_id ";
			$sql_get_stud .= " WHERE competition_id='".$competition_id."'";
			$res_get_stud  = mysqli_query($db_con,$sql_get_stud) or die(mysqli_error($db_con));
			$num_get_stud  = mysqli_num_rows($res_get_stud);
			$res_array     = array();
			while($row = mysqli_fetch_array($res_get_stud))
			{
				array_push($res_array,$row);
			}
			$num_get_stud1 = round(($num_get_stud)/2);
			$num_get_stud2 = $num_get_stud - $num_get_stud1;
			if(!empty($res_array))
			{
				$data .='<div class="control-group">';
		
				$data .='<div class="span12">';
					$data .='<div style="padding:20px">';
						$data .='<h5>Teams : </h5>';
					$data .='</div>';
				$data .='</div>';
				
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px">';
						$data .='<ul style="list-style:none">';
						for($i=0;$i<$num_get_stud1;$i++)
						{
							$data .='<li class="head2" style="padding:10px"><img src="images/team/small/'.$res_array[$i]['team_logo'].'" style="width:100px; height:100px"  alt="">
							&nbsp;&nbsp;<a href="view_team.php?pag=Teams&team_id='.$res_array[$i]['team_id'].'" target="_blank" >'.ucwords($res_array[$i]['team_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				
				
				$data .='<div class="span4">';
					$data .='<div style="padding:20px;">';
						$data .='<ul style="list-style:none">';
						for($i=$i;$i<$num_get_stud;$i++)
						{
							$data .='<li class="head2">
							<img src="images/team/small/'.$res_array[$i]['team_logo'].'"  style="width:100px; height:100px" > 
							&nbsp;&nbsp;<a href="view_team.php?pag=Teams&team_id='.$res_array[$i]['team_id'].'" target="_blank" > '.ucwords($res_array[$i]['team_name']).'</a></li>';
						}
						$data .='</ul>';
					$data .='</div>';
				$data .='</div>';
				
				$data .='</div>';// control-group
				//==================End : Coaches  ===========================================//
			}
			
		}
		$response_array = array("Success"=>"Success","resp"=>$data);				
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Request Type Not Defined");		
	}
	echo json_encode($response_array);
}

//---------------This is used for status change--------------------------------
if((isset($obj->change_status)) == "1" && isset($obj->change_status))
{
	$fixture_id			= $obj->competition_id;
	$curr_status			= $obj->curr_status;
	$response_array 		= array();	
	
	$data['status']      = $curr_status;
	$data['modified_by']             = $logged_uid;
	$data['modified_date']           = $datetime;
	$res = update('tbl_fixtures',$data,array('fixture_id'=>$fixture_id));
	
	if($res)
	{
		
			quit('Success',1);
		
	}
	else
	{
		quit('Please try lettre...!');
	}
}

//------------------This is used for delete data---------------------------------
if((isset($obj->delete_fixture)) == "1" && isset($obj->delete_fixture))
{
	$response_array   = array();		
	$ids 	  = $obj->batch;
	$del_flag 		  = 0; 
	foreach($ids as $id)	
	{
		$data['status']      			=  0;
		$data['modified_by']             = $uid;
		$data['modified_date']           = $datetime;
		$res = update('tbl_fixtures',$data,array('fixture_id'=>$id));	
	}	
	if($res == 1)
	{
		$response_array = array("Success"=>"Success","resp"=>"Record Deletion Success.");			
	}
	else
	{
		$response_array = array("Success"=>"fail","resp"=>"Record Deletion failed.");
	}		
	echo json_encode($response_array);	
}

// ==========================================================================







?>
