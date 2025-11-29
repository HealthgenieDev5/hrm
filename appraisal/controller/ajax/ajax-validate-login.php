<?php
// session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$returned_data = array();
/*print_r($_REQUEST);*/
if( isset( $_REQUEST['employee_id'] ) && !empty($_REQUEST['employee_id']) ){
	require_once '../../inc/config.php';
	$employee_id = $_REQUEST['employee_id'];


	$sql = "select 
	e.*, 
	d.hod_employee_id as hod_employee_id, 
	dg.designation_name as designation_name, 
	u.role as user_role 
	from employees e 
	left join departments d on d.id = e.department_id 
	left join designations dg on dg.id = e.designation_id 
	left join users u on u.id = e.id
	where e.id = '".$employee_id."'";


	$query = mysqli_query($conn, $sql) or die('unable to check for existing user in the database'.$mysqli_error($conn) );
	if( mysqli_num_rows($query) == 1 ){
		$employee_data = mysqli_fetch_assoc($query);
		$personal_mobile = $employee_data['personal_mobile'];
		if( !empty($personal_mobile) && is_numeric($personal_mobile) && strlen($personal_mobile) == 10 ){
			if( $personal_mobile == $_SESSION['personal_mobile'] ){
				if( isset($_REQUEST['otp']) && !empty($_REQUEST['otp']) && strlen($_REQUEST['otp']) == 6 ){
					if( $_REQUEST['otp'] == $_SESSION['otp'] ){

						/*$login_session_array = array();
						$login_session_array['employee_id'] = $employee_data['id'];
						$login_session_array['personal_mobile'] = $employee_data['personal_mobile'];
						$login_session_array['first_name'] = $employee_data['first_name'];
						$login_session_array['last_name'] = $employee_data['last_name'];
						$login_session_array['reporting_manager_id'] = $employee_data['reporting_manager_id'];
						$login_session_array['hod_employee_id'] = $employee_data['hod_employee_id'];
						$_SESSION['login'] = $login_session_array;*/
						$_SESSION['login'] = $employee_data;
						unset( $_SESSION['employee_id'] ); 
						unset( $_SESSION['personal_mobile'] ); 
						unset( $_SESSION['otp'] ); 
						$returned_data['message'] = "success";
						$returned_data['description'] = "Login Valid";

					}else{
						$returned_data['message'] = "error";
						$returned_data['description'] = "OTP Mismatched";
					}
				}else{
					$returned_data['message'] = "error";
					$returned_data['description'] = "Please Enter valid 6 digit OTP send to your registered mobile number".strlen($_REQUEST['otp']);
				}
			}else{
				$returned_data['message'] = "error";
				$returned_data['description'] = "The mobile number doesnot match before and after sending OTP";
			}
		}else{
			$returned_data['message'] = "error";
			$returned_data['description'] = "The registered mobile number is not valid, Please contact Developers";
		}
	}else{
		$returned_data['error'] = "The selected employee doesnot exist in our database";
	}
}else{
	$returned_data['message'] = "error";
	$returned_data['description'] = "Plese select your name from the dropdown";
}

echo json_encode($returned_data);
?>