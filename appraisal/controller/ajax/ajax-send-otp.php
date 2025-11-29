<?php

session_start();
$returned_data = array();
if( isset( $_REQUEST['employee_id'] ) && !empty($_REQUEST['employee_id']) ){
	require_once '../../inc/config.php';
	$employee_id = $_REQUEST['employee_id'];
	$sql = "select * from employees where id = '".$employee_id."'";
	$query = mysqli_query($conn, $sql) or die('unable to check for existing user in the database'.$mysqli_error($conn) );
	if( mysqli_num_rows($query) == 1 ){
		$personal_mobile = mysqli_fetch_assoc($query)['personal_mobile'];
		if( !empty($personal_mobile) && is_numeric($personal_mobile) && strlen($personal_mobile) == 10 ){
			$otp = rand(100000, 999999);
			$_SESSION['otp'] = $otp;
			$_SESSION['personal_mobile'] = $personal_mobile;
			$_SESSION['employee_id'] = $employee_id;

			
			$content = $otp." is your OTP for your mobile number verification on Healthgenie.in";
			
			$url = "http://sms6.rmlconnect.net/bulksms/bulksms";
			$postData = "username=healthgenie&password=hjd6hjks&type=0&dlr=1&destination=".$personal_mobile."&source=hgenie&message=".urlencode($content)."&entityid=1201159083846376389&tempid=1007165397626214742";
			$ch = curl_init();
			curl_setopt_array( $ch, array( CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $postData ) );
			$output = curl_exec($ch);
			curl_close($ch);

			// $output = 'otp sent testing';

			if(!empty($output)){
				$returned_data['message'] = "success";
				$returned_data['description'] = "OTP Sent on mobile xxxxxx".substr($personal_mobile, 6, 10) ; 
			}else{
				$returned_data['message'] = "error";
				$returned_data['description'] = "OTP was not send, because of an error, Please contact Developers";
			}
		}else{
			$returned_data['message'] = "error";
			$returned_data['description'] = "Either Mobile number is not registered or invalid, Please contact Developers";
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