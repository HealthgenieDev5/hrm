<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$returned_data = array();
if( isset( $_REQUEST['user_name'] ) && !empty($_REQUEST['user_name']) && isset( $_REQUEST['user_pass'] ) && !empty($_REQUEST['user_pass']) ){
	require_once '../../inc/config.php';
	$user_name = $_REQUEST['user_name'];
	$user_pass = $_REQUEST['user_pass'];

	if( !empty($user_name) && strlen($user_name) >= 5 ){
		if( !empty($user_pass) && strlen($user_pass) >= 5 ){
			$user_pass_encripted = md5($_REQUEST['user_pass']);
			$sql = "select 
			e.*, 
			d.hod_employee_id as hod_employee_id, 
			dg.designation_name as designation_name, 
			u.role as user_role 
			from employees e 
			left join departments d on d.id = e.department_id 
			left join designations dg on dg.id = e.designation_id 
			left join users u on u.id = e.id
			where u.username = '".$user_name."' and u.password = '".$user_pass_encripted."'";
			$query = mysqli_query($conn, $sql) or die('unable to check for existing user in the database'.$mysqli_error($conn) );
			if( mysqli_num_rows($query) == 1 ){
				$employee_data = mysqli_fetch_assoc($query);
				$_SESSION['login'] = $employee_data;
				$returned_data['message'] = "success";
				$returned_data['description'] = "Login Valid";
			}else{
				$returned_data['message'] = "error";
				$returned_data['description'] = "Login Failed: Username or Password didnot match";
			}
		}else{
			$returned_data['message'] = "error";
			$returned_data['description'] = "Password should be atleast 5 digits";
		}
	}else{
		$returned_data['message'] = "error";
		$returned_data['description'] = "Plese enter a valid username";
	}
	
}else{
	$returned_data['message'] = "error";
	$returned_data['description'] = "Username or password is empty";
}

echo json_encode($returned_data);
?>