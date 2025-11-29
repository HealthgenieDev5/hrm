<?php require_once("../includes/config.php"); ?>
<?php if( !isset($_SESSION['CURRENT_USER']) ){ header('Location: '.SITE_URL.'/login.php'); } ?>
<?php
if( isset($_REQUEST['ajax_for']) && !empty($_REQUEST['ajax_for']) ){
	$ajax_for = $_REQUEST['ajax_for'];
	$result_array = array();
	switch ($ajax_for) {
		
		case 'subdisposition_filter':
			if(isset($_REQUEST['disposition_id']) && !empty($_REQUEST['disposition_id'])){
				$disposition_id = implode(',', $_REQUEST['disposition_id']);
				$query = mysqli_query($conn, "select id, subdisposition_name from subdispositions where disposition_id in (".$disposition_id.")") or die(mysqli_error($conn));
				if(mysqli_num_rows($query) > 0){
					$result_array['response'] = 'success';
					$data = array();
					while($row = mysqli_fetch_assoc($query)){
						$data[] = $row;
					}
					$result_array['data'] = $data;
				}else{
					$result_array['response'] = 'failed';
					$result_array['description'] = 'Sub Dispositions Not Found';
				}
			}else{
				$result_array['response'] = 'failed';
				$result_array['description'] = 'Sub Dispositions Not Found';
			}
			echo json_encode($result_array);
			break;

		case 'import_candidates':
			if( isset($_REQUEST['db_fields']) && !empty($_REQUEST['db_fields']) && isset($_REQUEST['csv_fields']) && !empty($_REQUEST['csv_fields']) && isset($_REQUEST['csv_data']) && !empty($_REQUEST['csv_data']) ){
			
			
				/*$result_array['response'] = 'success';
				$result_array['description'] = 'all imported';
				$result_array['imported_ids'] = array(153,154,155);
				echo json_encode($result_array);
				die();*/

				$db_fields = $_REQUEST['db_fields'];
				$csv_fields = $_REQUEST['csv_fields'];
				$csv_data = $_REQUEST['csv_data'];
				// $agent_id = $_REQUEST['agent_id'];
				$agent_id = 3;

				if( count($csv_fields) <= count($db_fields) ){

					$db_keys = array();
					foreach( $csv_fields as $csv_field ){
						$db_keys[ $db_fields[ array_search($csv_field, $csv_fields) ] ] = $csv_field;
					}

					#Replace CSV header with actual database_fields
					$csv_array = array();
					foreach($csv_data as $data){
						$csv_array_row = array();
						foreach( $db_keys as $db_key => $csv_key){
							$csv_array_row[$db_key] = addslashes(trim($data[$csv_key]));
						}
						$csv_array[] = $csv_array_row;
					}
					#Replace CSV header with actual database_fields

					#add date_time to the csv_array if not exist
					foreach ($csv_array as $csv_index => $csv_row) {
						if( !isset( $csv_row['date_time'] ) || date( 'Y-m-d H:i:s', strtotime($csv_row['date_time']) ) == '1970-01-01 00:00:00' ){
							$csv_array[$csv_index]['date_time'] = date('Y-m-d H:i:s');
						}else{
							$csv_array[$csv_index]['date_time'] = date( 'Y-m-d H:i:s', strtotime($csv_row['date_time']) );
						}
					}
					#add date_time to the csv_array if not exist

					#add date to the csv_array if not exist
					foreach ($csv_array as $csv_index => $csv_row) {						
						if( isset( $csv_row['date_time'] ) && !empty( $csv_row['date_time'] ) ){
							$csv_array[$csv_index]['date'] = date( 'Y-m-d', strtotime($csv_row['date_time']) );
						}else{
							$csv_array[$csv_index]['date'] = date('Y-m-d');
						}
					}
					#add date to the csv_array if not exist

					#add agent_id to the csv_array if not exist
					foreach ($csv_array as $csv_index => $csv_row) {
						if( !isset( $csv_row['agent_id'] ) ){
							$csv_array[$csv_index]['agent_id'] = $agent_id;
						}
					}
					#add agent_id to the csv_array if not exist

					#add data_assigned_date to the csv_array if not exist
					foreach ($csv_array as $csv_index => $csv_row) {
						if( !isset( $csv_row['data_assigned_date'] ) || date( 'Y-m-d H:i:s', strtotime($csv_row['data_assigned_date']) ) == '1970-01-01 00:00:00' ){
							$csv_array[$csv_index]['data_assigned_date'] = date('Y-m-d H:i:s');
							$csv_array[$csv_index]['date_data_assigned_date'] = date( 'Y-m-d', strtotime($csv_array[$csv_index]['data_assigned_date']) );
						}
						else{
							$csv_array[$csv_index]['data_assigned_date'] = date('Y-m-d H:i:s', strtotime($csv_row['data_assigned_date']));
							$csv_array[$csv_index]['date_data_assigned_date'] = date( 'Y-m-d', strtotime($csv_row['data_assigned_date']) );
						}
					}
					#add data_assigned_date to the csv_array if not exist


					#add disposition_id to the csv_array if not exist
					foreach ($csv_array as $csv_index => $csv_row) {
						if( !isset( $csv_row['disposition_id'] ) || !is_numeric($csv_row['disposition_id']) ){
							$csv_array[$csv_index]['disposition_id'] = 1;
						}
					}
					#add disposition_id to the csv_array if not exist

					#add subdisposition_id to the csv_array if not exist
					foreach ($csv_array as $csv_index => $csv_row) {
						if( !isset( $csv_row['subdisposition_id'] ) || !is_numeric($csv_row['subdisposition_id']) ){
							$csv_array[$csv_index]['subdisposition_id'] = 1;
						}
					}
					#add subdisposition_id to the csv_array if not exist

					#add date_of_birth to the csv_array if not exist
					foreach ($csv_array as $csv_index => $csv_row) {
						$original = $csv_row['date_of_birth'];
						$converted = date('Y-m-d', strtotime($original));
						if( empty($original) || $converted=='1970-01-01' ){
							unset($csv_array[$csv_index]['date_of_birth']);
						}
						else{
							$csv_array[$csv_index]['date_of_birth'] = $converted;
						}
						
					}
					#add date_of_birth to the csv_array if not exist



					#Check invalid listing_id
					$invalid_listing_id_count = 0;
					$invalid_listing_id_index = array();
					foreach ($csv_array as $csv_index => $csv_row) {
						if( empty($csv_row['listing_id']) ){
							$invalid_listing_id_count++;
							$i = $csv_index+1;
							$invalid_listing_id_index[] = $i;
						}
					}
					if( $invalid_listing_id_count > 0){
						$result_array['response'] = 'failed';
						$result_array['description'] = "invalid_listing_id".$invalid_listing_id_count." at [".implode(', ', $invalid_listing_id_index)."]";
						echo json_encode($result_array);
						die();
					}
					#Check invalid listing_id

					#Check invalid date_of_birth
					$invalid_date_of_birth_count = 0;
					$invalid_date_of_birth_index = array();
					foreach ($csv_array as $csv_index => $csv_row) {
						if(isset($csv_row['date_of_birth'])){
							$original = $csv_row['date_of_birth'];
							$converted = date('Y-m-d', strtotime($original));
							if( empty($original) || $original !== $converted ){
								$invalid_date_of_birth_count++;
								$i = $csv_index+1;
								$invalid_date_of_birth_index[] = $i;
							}
						}
					}
					if( $invalid_date_of_birth_count > 0){
						$result_array['response'] = 'failed';
						$result_array['description'] = "invalid_date_of_birth_count ".$invalid_date_of_birth_count." at [".implode(', ', $invalid_date_of_birth_index)."]fgggggg";
						echo json_encode($result_array);
						die();
					}
					#Check invalid date_of_birth

					#Check invalid date_time
					$invalid_date_time_count = 0;
					$invalid_date_time_index = array();
					$i = 0;
					foreach ($csv_array as $csv_index => $csv_row) {
						$original = $csv_row['date_time'];
						$converted = date('Y-m-d H:i:s', strtotime($original));
						if( empty($original) || $original !== $converted ){
							$invalid_date_time_count++;
							$i = $csv_index+1;
							$invalid_date_time_index[] = $i;
						}
					}
					if( $invalid_date_time_count > 0){
						$result_array['response'] = 'failed';
						$result_array['description'] = "invalid_date_time_count".$invalid_date_time_count." at [".implode(', ', $invalid_date_time_index)."]";
						echo json_encode($result_array);
						die();
					}
					#Check invalid date_time


					#Check invalid agent_id
					$invalid_agent_id_count = 0;
					$invalid_agent_id_index = array();
					$i = 0;
					foreach ($csv_array as $csv_index => $csv_row) {
						if( empty($csv_row['agent_id']) || !is_numeric($csv_row['agent_id']) ){
							$invalid_agent_id_count++;
							$i = $csv_index+1;
							$invalid_agent_id_index[] = $i;
						}
					}
					if( $invalid_agent_id_count > 0){
						$result_array['response'] = 'failed';
						$result_array['description'] = "invalid_agent_id_count ".$invalid_agent_id_count." at [".implode(', ', $invalid_agent_id_index)."]";
						echo json_encode($result_array);
						die();
					}
					#Check invalid agent_id

					// print_r($csv_array);
					$imported_ids = array();
					$insert_error=array();
					// $csv_result_array = array();
					$failed_count=0;
					foreach($csv_array as $csv_index => $csv_row){
						$csv_row_filtered = array_filter($csv_row);
						foreach( $csv_row_filtered as $key => $val){
							$csv_row_filtered[$key] = addslashes($val);
						}
						$keys = array_keys($csv_row_filtered);
						$values = array_values($csv_row_filtered);
						$sql = "insert into candidates (".implode(',',$keys).") values ('".implode("','",$values)."')";
						$query = mysqli_query($conn, $sql);
						if( $query ){
							$imported_ids[] = mysqli_insert_id($conn);
							// $csv_result_array[] = array('row_number'=> $csv_index, 'generated_id'=> mysqli_insert_id($conn), 'result'=>'success', 'description'=> 'Candidate Imported successfully' );
						}else{
							$failed_count++;
							$insert_error[]=array('csv_row_number'=>$csv_index+1, 'error_message'=>mysqli_error($conn));
							// $csv_result_array[] = array('row_number'=> $csv_index, 'result'=>'failed', 'description'=> mysqli_error($conn) );
						}
					}
					
					if($failed_count>0){
						$imported_ids = array_filter($imported_ids);
						$imported_ids_imploded = implode(",", $imported_ids);
						$delete_sql = "delete from candidates where id in (".$imported_ids_imploded.")";
						$delete_query = mysqli_query($conn, $delete_sql) or die("unable to delete partially imported candidates ".mysqli_error($conn));
						
						$result_array['response'] = 'failed';
						$result_array['description'] = 'Failed to import some rows. Deleted imported rows. Try reupload the data.';
						$result_array['errors'] = $insert_error;
						echo json_encode($result_array);
						die();
					}
					else{
						$result_array['response'] = 'success';
						$result_array['description'] = 'All Rows are imported';
						$result_array['imported_ids'] = $imported_ids;
						$result_array['csv_result_array'] = $csv_result_array;
					}
					
					
					

					
					
				}else{
					$result_array['response'] = 'failed';
					$result_array['description'] = 'Fields in CSV is higher than Database';
				}
			}else{
				$result_array['response'] = 'failed';
				$result_array['description'] = 'You are not logged in or File is not uploaded';
			}
			echo json_encode($result_array);
			break;

		case 'delete_candidates':
			if( isset($_REQUEST['imported_ids']) && !empty($_REQUEST['imported_ids']) ){
				$imported_ids = $_REQUEST['imported_ids'];
				$imported_ids = array_filter($imported_ids);
				$imported_ids_imploded = implode(",", $_REQUEST['imported_ids']);
				$delete_sql = "delete from candidates where id in (".$imported_ids_imploded.")";
				$delete_query = mysqli_query($conn, $delete_sql) or die("unable to delete partially imported candidates ".mysqli_error($conn));
				if( $delete_query ){
					$result_array['response'] = 'success';
					$result_array['description'] = 'Partially imported candidates deleted';
				}else{
					$result_array['response'] = 'failed';
					$result_array['description'] = 'unable to delete partially imported candidates';
				}
			}else{
				$result_array['response'] = 'failed';
				$result_array['description'] = 'IDs to delete is not passed through ajax';
			}
			echo json_encode($result_array);
			break;

		case 'delete_multiple':
			if( isset($_REQUEST['selected_ids']) && !empty($_REQUEST['selected_ids']) ){
				$selected_ids = $_REQUEST['selected_ids'];
				$selected_ids_imploded = implode(",", $selected_ids);
				$current_user_id = $_SESSION['CURRENT_USER']['id'];
				$select_sql = "insert into candidates_trash 
				(candidate_id,listing_id,first_name,last_name,email,alternate_email,mobile,alternate_mobile,gender,marital_status,date_of_birth,present_address,present_city,present_state,present_pincode,permanent_address,permanent_city,permanent_state,permanent_pincode,total_experience_year,total_experience_month,relevent_experience_year,relevent_experience_month,is_working,current_company,current_company_joining_date,current_designation,functional_area,role,industry,notice_period,annual_salary,last_drawn_salary,last_drawn_salary_date,current_company_address,current_company_city,current_company_state,current_company_pincode,preferred_location,skills,resume,resume_headline,summary,ug_degree,ug_specialization,ug_university_institute,ug_graduation_year,pg_degree,pg_specialization,pg_university_institute,pg_graduation_year,dr_degree,dr_specialization,dr_university_institute,dr_graduation_year,source,source_url,disposition_id,subdisposition_id,call_remarks,first_call_date,date_first_call_date,last_call_date,date_last_call_date,call_back_date,date_call_back_date,interview_scheduled_date,date_interview_scheduled_date,date_data_assigned_date,data_assigned_date,agent_id,date,date_time,deleted_by) 
				

				select id,listing_id,first_name,last_name,email,alternate_email,mobile,alternate_mobile,gender,marital_status,date_of_birth,present_address,present_city,present_state,present_pincode,permanent_address,permanent_city,permanent_state,permanent_pincode,total_experience_year,total_experience_month,relevent_experience_year,relevent_experience_month,is_working,current_company,current_company_joining_date,current_designation,functional_area,role,industry,notice_period,annual_salary,last_drawn_salary,last_drawn_salary_date,current_company_address,current_company_city,current_company_state,current_company_pincode,preferred_location,skills,resume,resume_headline,summary,ug_degree,ug_specialization,ug_university_institute,ug_graduation_year,pg_degree,pg_specialization,pg_university_institute,pg_graduation_year,dr_degree,dr_specialization,dr_university_institute,dr_graduation_year,source,source_url,disposition_id,subdisposition_id,call_remarks,first_call_date,date_first_call_date,last_call_date,date_last_call_date,call_back_date,date_call_back_date,interview_scheduled_date,date_interview_scheduled_date,date_data_assigned_date,data_assigned_date,agent_id,date,date_time,'$current_user_id' 
				

				from candidates where id in ($selected_ids_imploded)";

				// echo $select_sql;
				// die();
				$select_query = mysqli_query($conn, $select_sql) or die(mysqli_error($conn));
				if( $select_query ){
					$delete_sql = "delete from candidates where id in (".$selected_ids_imploded.")";
					$delete_query = mysqli_query($conn, $delete_sql) or die("unable to delete selected candidates ".mysqli_error($conn));
					if( $delete_query ){
						$result_array['response'] = 'success';
						$result_array['description'] = 'Selected candidates deleted';
					}else{
						$result_array['response'] = 'failed';
						$result_array['description'] = 'unable to delete selected candidates';
					}
				}else{
					$result_array['response'] = 'failed';
					$result_array['description'] = 'some candidates are not present';
				}
			}else{
				$result_array['response'] = 'failed';
				$result_array['description'] = 'IDs to delete is not passed through ajax';
			}
			echo json_encode($result_array);
			break;
		case 'assign_multiple': 
			if( isset($_REQUEST['selected_ids']) && !empty($_REQUEST['selected_ids']) && isset($_REQUEST['agent_id']) && !empty($_REQUEST['agent_id']) ){
				$current_user_id = $_SESSION['CURRENT_USER']['id'];
				$selected_ids_imploded = $_REQUEST['selected_ids'];
				$agent_id = $_REQUEST['agent_id'];
				

				$select_sql = "insert into candidates_revision 
				(candidate_id,listing_id,first_name,last_name,email,alternate_email,mobile,alternate_mobile,gender,marital_status,date_of_birth,present_address,present_city,present_state,present_pincode,permanent_address,permanent_city,permanent_state,permanent_pincode,total_experience_year,total_experience_month,relevent_experience_year,relevent_experience_month,is_working,current_company,current_company_joining_date,current_designation,functional_area,role,industry,notice_period,annual_salary,last_drawn_salary,last_drawn_salary_date,current_company_address,current_company_city,current_company_state,current_company_pincode,preferred_location,skills,resume,resume_headline,summary,ug_degree,ug_specialization,ug_university_institute,ug_graduation_year,pg_degree,pg_specialization,pg_university_institute,pg_graduation_year,dr_degree,dr_specialization,dr_university_institute,dr_graduation_year,source,source_url,disposition_id,subdisposition_id,call_remarks,first_call_date,date_first_call_date,last_call_date,date_last_call_date,call_back_date,date_call_back_date,interview_scheduled_date,date_interview_scheduled_date,date_data_assigned_date,data_assigned_date,agent_id,date,date_time,updated_by)
				select id,listing_id,first_name,last_name,email,alternate_email,mobile,alternate_mobile,gender,marital_status,date_of_birth,present_address,present_city,present_state,present_pincode,permanent_address,permanent_city,permanent_state,permanent_pincode,total_experience_year,total_experience_month,relevent_experience_year,relevent_experience_month,is_working,current_company,current_company_joining_date,current_designation,functional_area,role,industry,notice_period,annual_salary,last_drawn_salary,last_drawn_salary_date,current_company_address,current_company_city,current_company_state,current_company_pincode,preferred_location,skills,resume,resume_headline,summary,ug_degree,ug_specialization,ug_university_institute,ug_graduation_year,pg_degree,pg_specialization,pg_university_institute,pg_graduation_year,dr_degree,dr_specialization,dr_university_institute,dr_graduation_year,source,source_url,disposition_id,subdisposition_id,call_remarks,first_call_date,date_first_call_date,last_call_date,date_last_call_date,call_back_date,date_call_back_date,interview_scheduled_date,date_interview_scheduled_date,date_data_assigned_date,data_assigned_date,agent_id,date,date_time,'$current_user_id' 
				

				from candidates where id in ($selected_ids_imploded) and agent_id != '$agent_id'";
				$select_query = mysqli_query($conn, $select_sql) or die(mysqli_error($conn));
				if( $select_query ){
					$date_data_assigned_date = date('Y-m-d');
					$data_assigned_date = date('Y-m-d H:i:s');


					$update_sql = "update candidates set agent_id = '$agent_id', date_data_assigned_date = '$date_data_assigned_date', data_assigned_date = '$data_assigned_date' where id in (".$selected_ids_imploded.") and agent_id != '$agent_id'";

					$update_query = mysqli_query($conn, $update_sql) or die("unable to update selected candidates ".mysqli_error($conn));
					if( $update_query ){
						$result_array['response'] = 'success';
						$result_array['description'] = 'Selected candidates updated';
					}else{
						$result_array['response'] = 'failed';
						$result_array['description'] = 'unable to update selected candidates';
					}
				}else{
					$result_array['response'] = 'failed';
					$result_array['description'] = 'some candidates are not present';
				}
			}else{
				$result_array['response'] = 'failed';
				$result_array['description'] = 'IDs or Agent id to update is not passed through ajax';
			}
			echo json_encode($result_array);
			break;

		default:
			break;
	}
}
?>