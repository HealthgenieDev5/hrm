<?php require_once("../includes/config.php"); ?>
<?php if( !isset($_SESSION['CURRENT_USER']) ){ header('Location: '.SITE_URL.'/login.php'); } ?>
<?php
// if( isset($_REQUEST['submit_update_candidate_form']) && !empty($_REQUEST['submit_update_candidate_form']) && isset($_REQUEST['candidate_id']) && !empty($_REQUEST['candidate_id']) ){
if( isset($_REQUEST['submit_update_candidate_form']) && !empty($_REQUEST['submit_update_candidate_form']) ){

	


	
	$candidate_id = $_REQUEST['candidate_id'];
	unset($_REQUEST['submit_update_candidate_form']);
	unset($_REQUEST['candidate_id']);



########## file upload ############
	#create upload folder
	$uploads = BASE_PATH."/uploads";
	$year = date('Y');
	$month = date('m');
	$year_folder = $uploads."/".$year;
	$month_folder = $uploads."/".$year."/".$month;
	if(!is_dir($year_folder)){
		mkdir($year_folder, 0774, true) or die("unable to create ".$year_folder." folder");
	}
	if(!is_dir($month_folder)){
		mkdir($month_folder, 0774, true) or die("unable to create ".$month_folder." folder");
	}

	$upload_folder = BASE_PATH."/uploads/".$year."/".$month."/";
	$attachment_folder_url = SITE_URL."/uploads/".$year."/".$month;
	
	#function to change file name
	function file_rename($filename, $folder, $ext){
		$full_path = $folder.$filename;
		if (!file_exists($full_path)) {
			return $full_path;
		}else{
			$basename =  basename( $filename,".".$ext);		
			$first_part = substr($basename, 0, -2);
			$last_part = substr($basename, -2);
			$dash = substr($last_part, 0, 1);
			$file_number = substr($last_part, 1, 1);
			if(is_numeric($file_number) && $dash == "-"){
				$file_number++;
				$last_part = $dash.$file_number;
			}else{
				$last_part.="-1";
			}
			$new_file_name = $first_part . $last_part . "." . $ext;
			$new_path = $folder . $new_file_name;
			$newfolder = $folder;
			$newext =  $ext;
			return file_rename($new_file_name, $newfolder, $newext);
		}	
	}


	#FIle upload check if file input is set
	if($_FILES["resume_file"]["size"] > 0 ){
		$target_file = $upload_folder . basename($_FILES["resume_file"]["name"]);
		$uploadOk = 1;
		$exstention = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		
		#Check if image file is a actual image or fake image
		$fileName = $_FILES["resume_file"]["name"];
		$fileSize = $_FILES["resume_file"]["size"]/(1024*1024);
		$fileType = $_FILES["resume_file"]["type"];
		$fileTmpName = $_FILES["resume_file"]["tmp_name"];
		$uploadOk = 1;
		#Check file size
		if ($fileSize > 5) {
			$message['file_error'] = "Sorry, your file is too large! Max 5mb is allowed!";
			$uploadOk = 0;
		}else{
			#Allow certain file formats
			if($exstention != "jpg" && $exstention != "png" && $exstention != "jpeg" && $exstention != "gif" && $exstention != "pdf" && $exstention != "doc" && $exstention != "docx" ) {
				$message['file_error'] = "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC, DOCX files are allowed.";
				$uploadOk = 0;
			}else{
				#Check if file already exists and change file name if necessary
				$target_file = str_replace(" ", "-", $target_file);
				$target_file = str_replace("%20", "-", $target_file);
				$target_file = file_rename(basename($target_file), $upload_folder, $exstention);
				#Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					$message['file_error'] = "Sorry, your file was not uploaded!";
					#if everything is ok, try to upload file
				}else {
					if (move_uploaded_file($fileTmpName, $target_file)) {
						$resume_file = str_replace(BASE_PATH, "", $target_file);
						$_REQUEST["resume"] = $resume_file;
					} else {
						$message['file_error'] = "Sorry, there was an error uploading your file.";
					}
				}
			}
		}
	}
########## file upload ############


	// echo "<pre>";
	// print_r($_SERVER);
	// echo "</pre>";
	// echo "<pre>";
	// print_r($_FILES);
	// echo "</pre>";
	// echo "<pre>";
	// print_r($_REQUEST);
	// echo "</pre>";
	// die();



	#these data should be set to null if empty
	$null_defaults = array('date_of_birth', 'present_address', 'permanent_address', 'current_company_joining_date', 'last_drawn_salary_date', 'skills', 'resume_headline', 'summary', 'disposition_id', 'subdisposition_id', 'first_call_date', 'date_first_call_date', 'last_call_date', 'date_last_call_date', 'call_back_date', 'date_call_back_date', 'interview_scheduled_date', 'date_interview_scheduled_date', 'data_assigned_date');


	###############Revision###############
	$current_data_sql = "select * from candidates where id = ".$candidate_id;
	$current_data_query = mysqli_query($conn, $current_data_sql) or die("unable to fetch data of the candidate with id ".$candidate_id." ".mysqli_error($conn));

	$current_data = array();
	if( mysqli_num_rows($current_data_query) == 1 ){
		while( $row = mysqli_fetch_assoc($current_data_query) ){
			$current_data = $row;
		}
		
		$current_data['candidate_id'] = $current_data['id'];
		$current_data['updated_by'] = $_SESSION['CURRENT_USER']['id'];
		unset($current_data['id']);
		$current_data = array_filter($current_data);
		$current_data_keys = array_keys($current_data);
		$current_data_values = array_values($current_data);
		foreach( $current_data_values as $key => $value ){
			$current_data_values[$key] = addslashes($value);
		}
		$current_data_keys_imploded = implode(',', $current_data_keys);
		$current_data_values_imploded = implode("','", $current_data_values);
		$revision_sql = "insert into candidates_revision (".$current_data_keys_imploded.") values ('".$current_data_values_imploded."')";
		$revision_query = mysqli_query($conn, $revision_sql) or die("unable to save revision Please contact administrator ".mysqli_error($conn));
		if( $revision_query ){

			###############Update###############
			if( $current_data['agent_id'] !== $_REQUEST['agent_id'] ){
				$_REQUEST['data_assigned_date'] = date('Y-m-d H:i:s');
				$_REQUEST['date_data_assigned_date'] = date('Y-m-d');
			}

			if( isset($_REQUEST['call_back_date']) && !empty($_REQUEST['call_back_date']) ){
				$_REQUEST['date_call_back_date'] = date('Y-m-d', strtotime($_REQUEST['call_back_date']));
			}else{
				$_REQUEST['date_call_back_date'] = '';
			}
			if( isset($_REQUEST['last_call_date']) && !empty($_REQUEST['last_call_date']) ){
				$_REQUEST['date_last_call_date'] = date('Y-m-d', strtotime($_REQUEST['last_call_date']));
			}else{
				$_REQUEST['date_last_call_date'] = '';
			}
			if( isset($_REQUEST['first_call_date']) && !empty($_REQUEST['first_call_date']) ){
				$_REQUEST['date_first_call_date'] = date('Y-m-d', strtotime($_REQUEST['first_call_date']));
			}else{
				$_REQUEST['date_first_call_date'] = '';
			}
			if( isset($_REQUEST['interview_scheduled_date']) && !empty($_REQUEST['interview_scheduled_date']) ){
				$_REQUEST['date_interview_scheduled_date'] = date('Y-m-d', strtotime($_REQUEST['interview_scheduled_date']));
			}else{
				$_REQUEST['date_interview_scheduled_date'] = '';
			}


			foreach($_REQUEST as $key => $val){
				if( empty($val) && in_array($key , $null_defaults) ){
					$post_string_array[] = $key." = NULL";
				}else{
					$post_string_array[] = $key." = '".addslashes($val)."'";
				}
			}
			$post_string = implode(", ", $post_string_array);
			$update_string = "update candidates set ".$post_string." where id=".$candidate_id;
			$update_candidate_query = mysqli_query($conn, $update_string) or die("unable to update candidates table".mysqli_error($conn));
			if( $update_candidate_query ){
				$update_candidate_message = "Candidate [".$candidate_id."] Updated";
				$update_candidate_title = "Success";
			}else{
				$update_candidate_message = "Failed to update candidate [".$candidate_id."].";
				$update_candidate_title = "Error";
			}
			###############Update###############
		}
		
	}
	###############Revision###############
	
	
}else{
	$update_candidate_message = "Invalid Request";
	$update_candidate_title = "Error";
}
$_SESSION['UPDATE_CANDIDATE_TITLE'] = $update_candidate_title;
$_SESSION['UPDATE_CANDIDATE_MESSAGE'] = $update_candidate_message;
header('Location: '.SITE_URL.'/candidate.php?id='.$candidate_id);
?>