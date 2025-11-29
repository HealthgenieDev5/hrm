<?php require_once("../includes/config.php"); ?>
<?php if( !isset($_SESSION['CURRENT_USER']) ){ header('Location: '.SITE_URL.'/login.php'); } ?>
<?php
if( isset($_REQUEST['submit_update_listing_form']) && !empty($_REQUEST['submit_update_listing_form']) && isset($_REQUEST['listing_id']) && !empty($_REQUEST['listing_id']) ){
	
	$listing_id = $_REQUEST['listing_id'];
	unset($_REQUEST['submit_update_listing_form']);
	unset($_REQUEST['listing_id']);


	###############Revision###############
	$current_data_sql = "select * from job_listing where id = ".$listing_id;
	$current_data_query = mysqli_query($conn, $current_data_sql) or die("unable to fetch data of the job_listing with id ".$listing_id." ".mysqli_error($conn));

	$current_data = array();
	if( mysqli_num_rows($current_data_query) == 1 ){
		while( $row = mysqli_fetch_assoc($current_data_query) ){
			$current_data = $row;
		}
		$current_data['listing_id'] = $current_data['id'];
		$current_data['updated_by'] = $_SESSION['CURRENT_USER']['id'];
		// $current_data['updated_date_time'] = date('Y-m-d H:i:s');
		unset($current_data['id']);
		$current_data = array_filter($current_data);
		$current_data_keys = array_keys($current_data);
		$current_data_values = array_values($current_data);
		foreach( $current_data_values as $key => $value ){
			$current_data_values[$key] = addslashes($value);
		}
		$current_data_keys_imploded = implode(',', $current_data_keys);
		$current_data_values_imploded = implode("','", $current_data_values);
		$revision_sql = "insert into job_listing_revision (".$current_data_keys_imploded.") values ('".$current_data_values_imploded."')";
		$revision_query = mysqli_query($conn, $revision_sql) or die("unable to save revision Please contact administrator ".mysqli_error($conn));
		if( $revision_query ){


			############update############
			if( isset($_REQUEST['listing_status']) && ($_REQUEST['listing_status'] == 'closed' || $_REQUEST['listing_status'] == 'suspended') ){
				$_REQUEST['listing_closure_date'] = date('Y-m-d');
			}else{
				$_REQUEST['listing_closure_date'] = '';
				$_REQUEST['listing_closing_reason'] = '';
			}

			$post_string_array = array();

			foreach($_REQUEST as $key => $val){
				if( $key == 'listing_closure_date' && empty($_REQUEST['listing_closure_date']) ){
					$post_string_array[] = $key." = NULL";
				}else{
					$post_string_array[] = $key." = '".addslashes($val)."'";
				}
			}
			$post_string = implode(", ", $post_string_array);
			// echo $post_string;
			// die();
			$update_string = "update job_listing set ".$post_string." where id=".$listing_id;

			// echo $update_string;
			// die();

			$update_listing_query = mysqli_query($conn, $update_string) or die("unable to update listing table".mysqli_error($conn));
			if( $update_listing_query ){
				$update_listing_message = "Job Listing [".$listing_id."] Updated";
				$update_listing_title = "Success";
			}else{
				$update_listing_message = "Failed to update listing [".$listing_id."].";
				$update_listing_title = "Error";
			}
			############update############
			
		}
		
	}
	###############Revision###############






	
}else{
	$update_listing_message = "Invalid Request";
	$update_listing_title = "Error";
}
$_SESSION['UPDATE_LISTING_TITLE'] = $update_listing_title;
$_SESSION['UPDATE_LISTING_MESSAGE'] = $update_listing_message;
header('Location: '.SITE_URL.'/listing.php?id='.$listing_id);
?>