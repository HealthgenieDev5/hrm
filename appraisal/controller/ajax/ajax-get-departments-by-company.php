<?php
$response_array = array();
if( isset($_REQUEST['company_ids']) && !empty($_REQUEST['company_ids']) ){
	require_once '../../inc/config.php';
	$company_ids = "('".implode("','", $_REQUEST['company_ids'])."')";
	$departments_sql = "select d.*, c.company_short_name as company_short_name from departments d left join companies c on c.id = d.company_id where d.company_id in ".$company_ids;
	$departments_query = mysqli_query($conn, $departments_sql) or die('unable to fetch departments from database '.mysqli_error($conn));	
	if( mysqli_num_rows($departments_query) > 0 ){
		$departments = array();
		while( $department_row = mysqli_fetch_assoc($departments_query) ){
			/*print_r($department_row);*/
			$departments[] = $department_row;
		}
		$response_array['message'] = 'success';
		$response_array['description'] = 'Departments Found!';
		$response_array['data'] = $departments;
	}else{
		$response_array['message'] = 'error';
		$response_array['description'] = 'No Department Found!';
	}
}else{
	$response_array['message'] = 'error';
	$response_array['description'] = 'Invalid data received with ajax request';
}
echo json_encode($response_array);
?>