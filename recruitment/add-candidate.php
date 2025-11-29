<?php
ob_start();
?>
<?php require_once("includes/config.php"); ?>
<?php if( !isset($_SESSION['CURRENT_USER']) ){ header('Location: '.SITE_URL.'/login.php'); } ?>
<!DOCTYPE html>
<html lang="en" >
<!--begin::Head-->
<head>
	<base href="">
	<meta charset="utf-8"/>
	<title>HR Management | Dashboard</title>

	<?php require_once("includes/header-top.php"); ?>

	<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.datatables.net/fixedcolumns/4.0.1/css/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/custom-style.css" rel="stylesheet" type="text/css"/>


	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />



	<style type="text/css">
		/*div.DTFC_LeftHeadWrapper table, div.DTFC_RightHeadWrapper table {
			margin: 0px !important;
		}*/
		.dataTables_wrapper .dataTable {
			margin:  0px !important;
		}
		#history_offcanvas {
			width: 1024px;
			left: unset;
			right: -1024px;
		}
		#history_offcanvas.offcanvas.offcanvas-on {
			width: 1024px;
			right: 0;
		}
		.filter-card {
			height: auto !important;
		}

		ul.filter-counts > li {
			margin-right: 0.5rem !important;
		}
		.timeline.timeline-6 .timeline-item .timeline-label {
			width: 150px;
		}
		.timeline.timeline-6::before {
		  	left: 150.5px;
		}
		.alert.alert-custom {
		  	padding: 0.1rem 2rem;
		}
	</style>
</head>
<!--end::Head-->


<!--begin::Body-->
<body  id="kt_body"  class="header-fixed header-mobile-fixed sidebar-enabled page-loading"  >
	<!--begin::Main-->
	<!--begin::Header Mobile-->
	<?php require_once('includes/header-mobile.php'); ?>
	<!--end::Header Mobile-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">
			<!--begin::Aside-->
			<?php include_once('includes/aside-left.php'); ?>
			<!--end::Aside-->
			<!--begin::Wrapper-->
			<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
				<!--begin::Page Header-->
				<?php //include_once('includes/page-header.php'); ?>
				<!--end::Page Header-->




				<!--begin::Content-->
				<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
					<!--begin::Entry-->
					<div class="d-flex flex-column-fluid">
						<!--begin::Container-->
						<div class="container-fluid">
							<!--begin::Dashboard-->
							<!--begin::Row-->
							<div class="row">
								<div class="col-xl-12">
									<!--begin::Base Table Widget 5-->
									<div class="card card-custom card-stretch gutter-b">
										<!--begin::Header-->
										<div class="card-header border-0 pt-5">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label font-weight-bolder text-dark">Add Candidate </span>
											</h3>
										</div>
										<!--end::Header-->
										<!--begin::Body-->
										<div class="card-body pt-2 pb-0">
											<?php
											if( isset($_REQUEST['listing_id']) && !empty($_REQUEST['listing_id']) ){
												if( isset($_REQUEST['submit_add_candidate_form']) && !empty($_REQUEST['submit_add_candidate_form']) ){
													#Submitting the form
													unset($_REQUEST['submit_add_candidate_form']);

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
														$file_error = '';
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
																$file_error = "Sorry, your file is too large! Max 5mb is allowed!";
																$uploadOk = 0;
															}else{
																#Allow certain file formats
																if($exstention != "jpg" && $exstention != "png" && $exstention != "jpeg" && $exstention != "gif" && $exstention != "pdf" && $exstention != "doc" && $exstention != "docx" ) {
																	$file_error = "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC, DOCX files are allowed.";
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
																			$file_error = "Sorry, there was an error uploading your file.";
																		}
																	}
																}
															}
														}
													########## file upload ############
													if( $file_error !== '' ){
														?>
														<div class="alert alert-custom alert-notice alert-light-primary fade show" role="alert" style="max-width:500px">
														    <div class="alert-icon"><i class="flaticon-warning"></i></div>
														    <div class="alert-text"><?php echo $file_error; ?></div>
														    <div class="alert-close">
														        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														            <span aria-hidden="true"><i class="ki ki-close"></i></span>
														        </button>
														    </div>
														</div>
														<?php
													}else{
														if( isset($_REQUEST['mobile']) && !empty($_REQUEST['mobile']) && strlen($_REQUEST['mobile']) == 10){
															$post_array = array();
															foreach( $_REQUEST as $key => $val ){
																$post_array[$key] = addslashes($val);
															}
															$post_array = array_filter($post_array);
															$post_array['date'] = date('Y-m-d');

															$post_array['date_data_assigned_date'] = date('Y-m-d');
															$post_array['data_assigned_date'] = date('Y-m-d H:i:s');
															
															if( isset($post_array['call_back_date']) && !empty($post_array['call_back_date']) ){
																$post_array['date_call_back_date'] = date('Y-m-d', strtotime($post_array['call_back_date']));
															}
															if( isset($post_array['last_call_date']) && !empty($post_array['last_call_date']) ){
																$post_array['date_last_call_date'] = date('Y-m-d', strtotime($post_array['last_call_date']));
															}
															if( isset($post_array['first_call_date']) && !empty($post_array['first_call_date']) ){
																$post_array['date_first_call_date'] = date('Y-m-d', strtotime($post_array['first_call_date']));
															}
															if( isset($post_array['interview_scheduled_date']) && !empty($post_array['interview_scheduled_date']) ){
																$post_array['date_interview_scheduled_date'] = date('Y-m-d', strtotime($post_array['interview_scheduled_date']));
															}


															$keys = array_keys($post_array);
															$values = array_values($post_array);
															$keys_imploded = implode(',', $keys);
															$values_imploded = "'".implode("', '", $values)."'";
															$insert_sql = "insert into candidates (".$keys_imploded.") values (".$values_imploded.")";
															$insert_query = mysqli_query($conn, $insert_sql) or die("unable to add candidates ".mysqli_error($conn));
															if( $insert_query ){
																$insert_id = mysqli_insert_id($conn); 
																header("Location: ".SITE_URL."/candidate.php?id=".$insert_id);
															}else{
																?>
																<div class="alert alert-custom alert-notice alert-light-primary fade show" role="alert" style="max-width:500px">
																    <div class="alert-icon"><i class="flaticon-warning"></i></div>
																    <div class="alert-text">Failed to add Candidate</div>
																    <div class="alert-close">
																        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
																            <span aria-hidden="true"><i class="ki ki-close"></i></span>
																        </button>
																    </div>
																</div>
																<?php
															}
														}else{
															?>
															<div class="alert alert-custom alert-notice alert-light-primary fade show" role="alert" style="max-width:500px">
															    <div class="alert-icon"><i class="flaticon-warning"></i></div>
															    <div class="alert-text">Mobile number should be 10 digit numeric only</div>
															    <div class="alert-close">
															        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
															            <span aria-hidden="true"><i class="ki ki-close"></i></span>
															        </button>
															    </div>
															</div>
															<?php
														}
													}
												}
												?>
												<form id="add_candidate_form" method="post" enctype='multipart/form-data'>
													<div class="row">
														<div class="col-md-9">
															<div class="accordion accordion-toggle-arrow">
																<div class="card">
																	<div class="card-header">
																		<div class="card-title" data-toggle="collapse" data-target="#personal_information">
																			<i class="flaticon2-layers-1"></i> Personal Information
																		</div>
																	</div>
																	<div id="personal_information" class="collapse show">
																		<div class="card-body">
																			<div class="row">
																				<div class="col-md-3">
																					<div class="form-group">
																						<input type="hidden" name="listing_id" id="listing_id" value="<?php echo $_REQUEST['listing_id']; ?>">
																						<label for="first_name">First Name</label>
																						<input type="text" id="first_name" class="form-control" name="first_name" placeholder="First Name" value="<?php echo isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="last_name">Last Name</label>
																						<input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="email">Email</label>
																						<input type="text" id="email" class="form-control" name="email" placeholder="Email" value="<?php echo isset($_REQUEST['email']) ? $_REQUEST['email'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="alternate_email">Alternate Email</label>
																						<input type="text" id="alternate_email" class="form-control" name="alternate_email" placeholder="Alternate Email" value="<?php echo isset($_REQUEST['alternate_email']) ? $_REQUEST['alternate_email'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="mobile">Mobile <span class="text-danger">*</span></label>
																						<input type="text" id="mobile" class="form-control" name="mobile" placeholder="Mobile" value="<?php echo isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="alternate_mobile">Alternate Mobile</label>
																						<input type="text" id="alternate_mobile" class="form-control" name="alternate_mobile" placeholder="Alternate Mobile" value="<?php echo isset($_REQUEST['alternate_mobile']) ? $_REQUEST['alternate_mobile'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="gender">Gender</label>
																						<select class="form-control select2" id="gender" name="gender" data-placeholder="Gender">
																							<option></option>
																							<?php
																							$get_gender = mysqli_query($conn, "select distinct gender from candidates order by gender asc") or die("unable to fetch gender from database ".mysqli_error($conn));
																							$gender_array = array('Male', 'Female', 'Other');
																							if( mysqli_num_rows($get_gender) > 0 ){
																								while( $gender_row = mysqli_fetch_assoc($get_gender) ){
																									$gender_array[] = trim($gender_row['gender']);
																								}
																							}
																							$gender_array_filtered = array_unique(array_filter($gender_array));
																							sort( $gender_array_filtered );
																							foreach( $gender_array_filtered as $gender){
																								?>
																								<option value="<?php echo $gender; ?>" <?php if( isset($_REQUEST['gender']) && $_REQUEST['gender'] == $gender ){ echo 'selected'; } ?> ><?php echo $gender; ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="marital_status">Marital Status</label>
																						<select class="form-control select2" id="marital_status" name="marital_status" data-placeholder="Marital Status">
																							<option></option>
																							<?php
																							$get_marital_status = mysqli_query($conn, "select distinct marital_status from candidates order by marital_status asc") or die("unable to fetch marital_status from database ".mysqli_error($conn));
																							$marital_status_array = array('Single', 'Married');
																							if( mysqli_num_rows($get_marital_status) > 0 ){
																								while( $marital_status_row = mysqli_fetch_assoc($get_marital_status) ){
																									$marital_status_array[] = trim($marital_status_row['marital_status']);
																								}
																							}
																							$marital_status_array_filtered = array_unique(array_filter($marital_status_array));

																							sort( $marital_status_array_filtered );

																							foreach( $marital_status_array_filtered as $marital_status){
																								?>
																								<option value="<?php echo $marital_status; ?>" <?php if( isset($_REQUEST['marital_status']) && $_REQUEST['marital_status'] == $marital_status ){ echo 'selected'; } ?> ><?php echo $marital_status; ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="date_of_birth">Date Of Birth</label>
																						<div class="input-group date" id="date_of_birth_picker" data-target-input="nearest">
																							<input type="text" id="date_of_birth" class="form-control datetimepicker-input" name="date_of_birth" placeholder="Date Of Birth" data-target="#date_of_birth_picker" value="<?php echo isset($_REQUEST['date_of_birth']) ? $_REQUEST['date_of_birth'] : ''; ?>"/>
																							<div class="input-group-append" data-target="#date_of_birth_picker" data-toggle="datetimepicker">
																								<span class="input-group-text">
																									<i class="far fa-calendar-alt"></i>
																								</span>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="present_address">Present Address</label>
																						<textarea id="present_address" class="form-control" name="present_address" placeholder="Present Address"><?php echo isset($_REQUEST['present_address']) ? $_REQUEST['present_address'] : ''; ?></textarea>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="present_city">Present City</label>
																						<input type="text" id="present_city" class="form-control" name="present_city" placeholder="Present City" value="<?php echo isset($_REQUEST['present_city']) ? $_REQUEST['present_city'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="present_state">Present State</label>
																						<input type="text" id="present_state" class="form-control" name="present_state" placeholder="Present State" value="<?php echo isset($_REQUEST['present_state']) ? $_REQUEST['present_state'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="present_pincode">Present Pincode</label>
																						<input type="text" id="present_pincode" class="form-control" name="present_pincode" placeholder="Present Pincode" value="<?php echo isset($_REQUEST['present_pincode']) ? $_REQUEST['present_pincode'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="permanent_address">Permanent Address</label>
																						<textarea id="permanent_address" class="form-control" name="permanent_address" placeholder="Permanent Address"><?php echo isset($_REQUEST['permanent_address']) ? $_REQUEST['permanent_address'] : ''; ?></textarea>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="permanent_city">Permanent City</label>
																						<input type="text" id="permanent_city" class="form-control" name="permanent_city" placeholder="Permanent City" value="<?php echo isset($_REQUEST['permanent_city']) ? $_REQUEST['permanent_city'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="permanent_state">Permanent State</label>
																						<input type="text" id="permanent_state" class="form-control" name="permanent_state" placeholder="Permanent State" value="<?php echo isset($_REQUEST['permanent_state']) ? $_REQUEST['permanent_state'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="permanent_pincode">Permanent Pincode</label>
																						<input type="text" id="permanent_pincode" class="form-control" name="permanent_pincode" placeholder="Permanent Pincode" value="<?php echo isset($_REQUEST['permanent_pincode']) ? $_REQUEST['permanent_pincode'] : ''; ?>" />
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="card">
																	<div class="card-header">
																		<div class="card-title" data-toggle="collapse" data-target="#education_qualification">
																			<i class="flaticon2-copy"></i> Education & Qualification
																		</div>
																	</div>
																	<div id="education_qualification" class="collapse show">
																		<div class="card-body">
																			<div class="row">
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="ug_degree">UG Degree</label>
																						<input type="text" id="ug_degree" class="form-control" name="ug_degree" placeholder="UG Degree" value="<?php echo isset($_REQUEST['ug_degree']) ? $_REQUEST['ug_degree'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="ug_specialization">UG Specialization</label>
																						<input type="text" id="ug_specialization" class="form-control" name="ug_specialization" placeholder="UG Specialization" value="<?php echo isset($_REQUEST['ug_specialization']) ? $_REQUEST['ug_specialization'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="ug_university_institute">UG University Institute</label>
																						<input type="text" id="ug_university_institute" class="form-control" name="ug_university_institute" placeholder="UG University Institute" value="<?php echo isset($_REQUEST['ug_university_institute']) ? $_REQUEST['ug_university_institute'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="ug_graduation_year">UG Graduation Year</label>
																						<div class="input-group year" id="ug_graduation_year_picker" data-target-input="nearest">
																							<input type="text" id="ug_graduation_year" class="form-control datetimepicker-input" name="ug_graduation_year" placeholder="UG Graduation Year" data-target="#ug_graduation_year_picker" value="<?php echo isset($_REQUEST['ug_graduation_year']) ? $_REQUEST['ug_graduation_year'] : ''; ?>"/>
																							<div class="input-group-append" data-target="#ug_graduation_year_picker" data-toggle="datetimepicker">
																								<span class="input-group-text">
																									<i class="far fa-calendar-alt"></i>
																								</span>
																							</div>
																						</div>
																					</div>

																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="pg_degree">PG Degree</label>
																						<input type="text" id="pg_degree" class="form-control" name="pg_degree" placeholder="PG Degree" value="<?php echo isset($_REQUEST['pg_degree']) ? $_REQUEST['pg_degree'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="pg_specialization">PG Specialization</label>
																						<input type="text" id="pg_specialization" class="form-control" name="pg_specialization" placeholder="PG Specialization" value="<?php echo isset($_REQUEST['pg_specialization']) ? $_REQUEST['pg_specialization'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="pg_university_institute">PG University Institute</label>
																						<input type="text" id="pg_university_institute" class="form-control" name="pg_university_institute" placeholder="PG University Institute" value="<?php echo isset($_REQUEST['pg_university_institute']) ? $_REQUEST['pg_university_institute'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="pg_graduation_year">PG Graduation Year</label>
																						<div class="input-group year" id="pg_graduation_year_picker" data-target-input="nearest">
																							<input type="text" id="pg_graduation_year" class="form-control datetimepicker-input" name="pg_graduation_year" placeholder="PG Graduation Year" data-target="#pg_graduation_year_picker" value="<?php echo isset($_REQUEST['pg_graduation_year']) ? $_REQUEST['pg_graduation_year'] : ''; ?>"/>
																							<div class="input-group-append" data-target="#pg_graduation_year_picker" data-toggle="datetimepicker">
																								<span class="input-group-text">
																									<i class="far fa-calendar-alt"></i>
																								</span>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="dr_degree">DR Degree</label>
																						<input type="text" id="dr_degree" class="form-control" name="dr_degree" placeholder="DR Degree" value="<?php echo isset($_REQUEST['dr_degree']) ? $_REQUEST['dr_degree'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="dr_specialization">DR Specialization</label>
																						<input type="text" id="dr_specialization" class="form-control" name="dr_specialization" placeholder="DR Specialization" value="<?php echo isset($_REQUEST['dr_specialization']) ? $_REQUEST['dr_specialization'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="dr_university_institute">DR University Institute</label>
																						<input type="text" id="dr_university_institute" class="form-control" name="dr_university_institute" placeholder="DR University Institute" value="<?php echo isset($_REQUEST['dr_university_institute']) ? $_REQUEST['dr_university_institute'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="dr_graduation_year">DR Graduation Year</label>
																						<div class="input-group year" id="dr_graduation_year_picker" data-target-input="nearest">
																							<input type="text" id="dr_graduation_year" class="form-control datetimepicker-input" name="dr_graduation_year" placeholder="DR Graduation Year" data-target="#dr_graduation_year_picker" value="<?php echo isset($_REQUEST['dr_graduation_year']) ? $_REQUEST['dr_graduation_year'] : ''; ?>"/>
																							<div class="input-group-append" data-target="#dr_graduation_year_picker" data-toggle="datetimepicker">
																								<span class="input-group-text">
																									<i class="far fa-calendar-alt"></i>
																								</span>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="card">
																	<div class="card-header">
																		<div class="card-title" data-toggle="collapse" data-target="#professional_information">
																			<i class="flaticon2-bell-alarm-symbol"></i> Professional Information
																		</div>
																	</div>
																	<div id="professional_information" class="collapse show">
																		<div class="card-body">
																			<div class="row">
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="total_experience_year">Total Experience</label>
																						<div class="input-group">
																							<input type="number" id="total_experience_year" class="form-control" name="total_experience_year" placeholder="Total Experience Years" value="<?php echo isset($_REQUEST['total_experience_year']) ? $_REQUEST['total_experience_year'] : '0'; ?>" />
																							<div class="input-group-append">
																								<span class="input-group-text">Y</span>
																							</div>
																							<input type="number" id="total_experience_month" class="form-control" name="total_experience_month" placeholder="Total Experience Months" value="<?php echo isset($_REQUEST['total_experience_month']) ? $_REQUEST['total_experience_month'] : '0'; ?>" />
																							<div class="input-group-append">
																								<span class="input-group-text">M</span>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="relevent_experience_year">Relevent Experience</label>
																						<div class="input-group">
																							<input type="number" id="relevent_experience_year" class="form-control" name="relevent_experience_year" placeholder="Relevent Experience Years" value="<?php echo isset($_REQUEST['relevent_experience_year']) ? $_REQUEST['relevent_experience_year'] : '0'; ?>" />
																							<div class="input-group-append">
																								<span class="input-group-text">Y</span>
																							</div>
																							<input type="number" id="relevent_experience_month" class="form-control" name="relevent_experience_month" placeholder="Relevent Experience Months" value="<?php echo isset($_REQUEST['relevent_experience_month']) ? $_REQUEST['relevent_experience_month'] : '0'; ?>" />
																							<div class="input-group-append">
																								<span class="input-group-text">M</span>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="is_working">Working</label>
																						<select class="form-control select2" id="is_working" name="is_working" data-placeholder="Working">
																							<option></option>
																							<option value="yes" <?php if( isset($_REQUEST['is_working']) && in_array('yes', $_REQUEST['is_working']) ){ echo 'selected'; } ?> >Yes</option>
																							<option value="no" <?php if( isset($_REQUEST['is_working']) && in_array('no', $_REQUEST['is_working']) ){ echo 'selected'; } ?> >No</option>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="current_company">Current Company</label>
																						<input type="text" id="current_company" class="form-control" name="current_company" placeholder="Current Company" value="<?php echo isset($_REQUEST['current_company']) ? $_REQUEST['current_company'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="current_company_joining_date">Current Company Joining Date</label>
																						<div class="input-group date" id="current_company_joining_date_picker" data-target-input="nearest">
																							<input type="text" id="current_company_joining_date" class="form-control datetimepicker-input" name="current_company_joining_date" placeholder="Current Company Joining Date" data-target="#current_company_joining_date_picker" value="<?php echo isset($_REQUEST['current_company_joining_date']) ? $_REQUEST['current_company_joining_date'] : ''; ?>"/>
																							<div class="input-group-append" data-target="#current_company_joining_date_picker" data-toggle="datetimepicker">
																								<span class="input-group-text">
																									<i class="far fa-calendar-alt"></i>
																								</span>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="current_designation">Current Designation</label>
																						<input type="text" id="current_designation" class="form-control" name="current_designation" placeholder="Current Designation" value="<?php echo isset($_REQUEST['current_designation']) ? $_REQUEST['current_designation'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="functional_area">Functional Area</label>
																						<input type="text" id="functional_area" class="form-control" name="functional_area" placeholder="Functional Area" value="<?php echo isset($_REQUEST['functional_area']) ? $_REQUEST['functional_area'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="role">Role</label>
																						<input type="text" id="role" class="form-control" name="role" placeholder="Role" value="<?php echo isset($_REQUEST['role']) ? $_REQUEST['role'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="industry">Industry</label>
																						<input type="text" id="industry" class="form-control" name="industry" placeholder="Industry" value="<?php echo isset($_REQUEST['industry']) ? $_REQUEST['industry'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="notice_period">Notice Period</label>
																						
																						<div class="input-group">
																							<input type="text" id="notice_period" class="form-control" name="notice_period" placeholder="Notice Period" value="<?php echo isset($_REQUEST['notice_period']) ? $_REQUEST['notice_period'] : ''; ?>" />
																							<div class="input-group-append">
																								<span class="input-group-text">Month</span>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="annual_salary">Annual Salary</label>
																						<div class="input-group">
																							<div class="input-group-prepend">
																								<span class="input-group-text"><i class="fa fa-rupee"></i></span>
																							</div>
																							<input type="text" id="annual_salary" class="form-control" name="annual_salary" placeholder="Annual Salary" value="<?php echo isset($_REQUEST['annual_salary']) ? $_REQUEST['annual_salary'] : ''; ?>" />
																							<div class="input-group-append">
																								<span class="input-group-text">Per Year</span>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="last_drawn_salary">Last Drawn Salary</label>
																						<div class="input-group">
																							<div class="input-group-prepend">
																								<span class="input-group-text"><i class="fa fa-rupee"></i></span>
																							</div>
																							<input type="text" id="last_drawn_salary" class="form-control" name="last_drawn_salary" placeholder="Last Drawn Salary" value="<?php echo isset($_REQUEST['last_drawn_salary']) ? $_REQUEST['last_drawn_salary'] : ''; ?>" />
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="last_drawn_salary_date">Last Drawn Salary Date</label>
																						<div class="input-group date" id="last_drawn_salary_date_picker" data-target-input="nearest">
																							<input type="text" id="last_drawn_salary_date" class="form-control datetimepicker-input" name="last_drawn_salary_date" placeholder="Last Drawn Salary Date" data-target="#last_drawn_salary_date_picker" value="<?php echo isset($_REQUEST['last_drawn_salary_date']) ? $_REQUEST['last_drawn_salary_date'] : ''; ?>"/>
																							<div class="input-group-append" data-target="#last_drawn_salary_date_picker" data-toggle="datetimepicker">
																								<span class="input-group-text">
																									<i class="far fa-calendar-alt"></i>
																								</span>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="current_company_address">Current Company Address</label>
																						<input type="text" id="current_company_address" class="form-control" name="current_company_address" placeholder="Current Company Address" value="<?php echo isset($_REQUEST['current_company_address']) ? $_REQUEST['current_company_address'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="current_company_city">Current Company City</label>
																						<input type="text" id="current_company_city" class="form-control" name="current_company_city" placeholder="Current Company City" value="<?php echo isset($_REQUEST['current_company_city']) ? $_REQUEST['current_company_city'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="current_company_state">Current Company State</label>
																						<input type="text" id="current_company_state" class="form-control" name="current_company_state" placeholder="Current Company State" value="<?php echo isset($_REQUEST['current_company_state']) ? $_REQUEST['current_company_state'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="current_company_pincode">Current Company Pincode</label>
																						<input type="text" id="current_company_pincode" class="form-control" name="current_company_pincode" placeholder="Current Company Pincode" value="<?php echo isset($_REQUEST['current_company_pincode']) ? $_REQUEST['current_company_pincode'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="preferred_location">Preferred Location</label>
																						<input type="text" id="preferred_location" class="form-control" name="preferred_location" placeholder="Preferred Location" value="<?php echo isset($_REQUEST['preferred_location']) ? $_REQUEST['preferred_location'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="skills">Skills</label>
																						<input type="text" id="skills" class="form-control" name="skills" placeholder="Skills" value="<?php echo isset($_REQUEST['skills']) ? $_REQUEST['skills'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="resume">Resume</label>
																						<input type="file" id="resume_file" class="form-control" name="resume_file" >
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="resume_headline">Resume Headline</label>
																						<input type="text" id="resume_headline" class="form-control" name="resume_headline" placeholder="Resume Headline" value="<?php echo isset($_REQUEST['resume_headline']) ? $_REQUEST['resume_headline'] : ''; ?>" />
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label for="summary">Summary</label>
																						<textarea id="summary" class="form-control" name="summary" placeholder="Summary"><?php echo isset($_REQUEST['summary']) ? $_REQUEST['summary'] : ''; ?></textarea>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>

															</div>
														</div>
														<div class="col-md-3">
															<div class="accordion accordion-toggle-arrow">
																<div class="card">
																	<div class="card-header">
																		<div class="card-title" data-toggle="collapse" data-target="#publishing_action">
																			<i class="flaticon2-layers-1"></i> Publish Or Update
																		</div>
																	</div>
																	<div id="publishing_action" class="collapse show">
																		<div class="card-body">
																				
																			

																			<div class="form-group">
																				<label for="agent_id">Agent</label>
																				<select class="form-control select2" id="agent_id" name="agent_id" data-placeholder="Agent">
																					<option></option>
																					<?php
																					$get_agents = mysqli_query($conn, "select * from users") or die("unable to fetch users from database ".mysqli_error($conn));
																					if( mysqli_num_rows($get_agents) > 0 ){
																						while( $agent_row = mysqli_fetch_assoc($get_agents) ){
																							?>
																							<option value="<?php echo $agent_row['id']; ?>" <?php if( !empty($agent_row['id']) && $agent_row['id'] == $_REQUEST['agent_id'] ){ echo 'selected'; }elseif( $agent_row['id'] == $_SESSION['CURRENT_USER']['id']){ echo 'selected'; } ?> ><?php echo trim($agent_row['first_name'].' '.$agent_row['last_name']); ?></option>
																							<?php
																						}
																					}
																					?>
																				</select>
																				<input type="hidden" id="data_assigned_date" name="data_assigned_date" value="<?php echo date('Y-m-d H:i:s'); ?>" />
																			</div>
																			<div class="form-group">
																				<input type="submit" id="submit" class="form-control btn btn-primary" name="submit_add_candidate_form" value="Submit" />
																			</div>
																		</div>
																	</div>
																</div>
																<div class="card">
																	<div class="card-header">
																		<div class="card-title" data-toggle="collapse" data-target="#disposition_action">
																			<i class="flaticon2-layers-1"></i> Disposition
																		</div>
																	</div>
																	<div id="disposition_action" class="collapse show">
																		<div class="card-body">
																			<div class="form-group">
																				<label >Source <span class="text-danger">*</span></label>
																				<div class="input-group">
																					<?php
																					$source_array = array( 'Naukri', 'Indeed', 'Timesjob', 'Inbound', 'HG Website', 'Reference'); 
																					$source_query = mysqli_query($conn, "select distinct source from candidates where source != ''") or die("unable to fetch source from database ".mysqli_error($conn)); 
																					if( mysqli_num_rows ($source_query) > 0 ){
																						while( $source_row = mysqli_fetch_assoc($source_query) ){
																							if( !in_array($source_row['source'], $source_array) ){
																								$source_array[] = $source_row['source'];
																							}
																						}
																					}
																					foreach( $source_array as $source ){
																						?>
																						<label for="source_<?php echo $source; ?>" class="label label-lg label-light-primary label-inline mr-2 mb-2" style="cursor: pointer;">
																							<input class="mr-1" type="radio" name="source" value="<?php echo $source; ?>" id="source_<?php echo $source; ?>" <?php if( $_REQUEST['source'] == $source ){ echo 'checked'; } ?> >
																							<?php echo $source; ?>
																						</label>
																						<?php
																					}
																					?>
																				</div>
																			</div>
																			<div class="form-group">
																				<label for="source_url">Source url</label>
																				<input type="text" id="source_url" class="form-control" name="source_url" placeholder="Source Url" value="<?php echo isset($_REQUEST['source_url']) ? $_REQUEST['source_url'] : ''; ?>">
																			</div>
																				
																			<div class="form-group">
																				<label for="first_call_date">First Call Date</label>
																				<div class="input-group date" id="first_call_date_picker" data-target-input="nearest">
																					<input type="text" id="first_call_date" class="form-control datetimepicker-input" name="first_call_date" placeholder="First Call Date" data-target="#first_call_date_picker" value="<?php echo isset($_REQUEST['first_call_date']) ? $_REQUEST['first_call_date'] : ''; ?>"/>
																					<div class="input-group-append" data-target="#first_call_date_picker" data-toggle="datetimepicker">
																						<span class="input-group-text">
																							<i class="far fa-calendar-alt"></i>
																						</span>
																					</div>
																				</div>
																			</div>
																			<div class="form-group">
																				<label for="last_call_date">Last Call Date</label>
																				<div class="input-group date" id="last_call_date_picker" data-target-input="nearest">
																					<input type="text" id="last_call_date" class="form-control datetimepicker-input" name="last_call_date" placeholder="Last Call Date" data-target="#last_call_date_picker" value="<?php echo isset($_REQUEST['last_call_date']) ? $_REQUEST['last_call_date'] : ''; ?>"/>
																					<div class="input-group-append" data-target="#last_call_date_picker" data-toggle="datetimepicker">
																						<span class="input-group-text">
																							<i class="far fa-calendar-alt"></i>
																						</span>
																					</div>
																				</div>
																			</div>
																			<div class="form-group">
																				<label for="call_back_date">Call Back Date</label>
																				<div class="input-group date" id="call_back_date_picker" data-target-input="nearest">
																					<input type="text" id="call_back_date" class="form-control datetimepicker-input" name="call_back_date" placeholder="Call Back Date" data-target="#call_back_date_picker" value="<?php echo isset($_REQUEST['call_back_date']) ? $_REQUEST['call_back_date'] : ''; ?>"/>
																					<div class="input-group-append" data-target="#call_back_date_picker" data-toggle="datetimepicker">
																						<span class="input-group-text">
																							<i class="far fa-calendar-alt"></i>
																						</span>
																					</div>
																				</div>
																			</div>
																			<div class="form-group">
																				<label for="disposition_id">Disposition</label>
																				<select class="form-control select2" id="disposition_id" name="disposition_id" data-placeholder="Disposition">
																					<option></option>
																					<?php
																					$get_dispositions = mysqli_query($conn, "select * from dispositions") or die("unable to fetch dispositions from database ".mysqli_error($conn));
																					if( mysqli_num_rows($get_dispositions) > 0 ){
																						while( $disposition_row = mysqli_fetch_assoc($get_dispositions) ){
																							?>
																							<option value="<?php echo $disposition_row['id']; ?>" <?php if( isset( $_REQUEST['disposition_id'] ) && $disposition_row['id'] == $_REQUEST['disposition_id'] ){ echo 'selected'; } ?> >
																								<?php echo $disposition_row['disposition_name']; ?>
																							</option>
																							<?php
																						}
																					}
																					?>
																				</select>
																			</div>
																			<div class="form-group">
																				<label for="subdisposition_id">Sub Disposition</label>
																				<select class="form-control select2" id="subdisposition_id" name="subdisposition_id" data-placeholder="Sub Disposition">
																					<option></option>
																					<?php
																					if( isset($_REQUEST['disposition_id']) && !empty($_REQUEST['disposition_id']) ){
																						$disposition_id = $_REQUEST['disposition_id'];
																						$get_subdispositions = mysqli_query($conn, "select * from subdispositions where disposition_id = ".$disposition_id) or die("unable to fetch subdispositions from database ".mysqli_error($conn));
																						if( mysqli_num_rows($get_subdispositions) > 0 ){
																							while( $subdisposition_row = mysqli_fetch_assoc($get_subdispositions) ){
																								?>
																								<option value="<?php echo $subdisposition_row['id']; ?>" <?php if( isset( $_REQUEST['subdisposition_id'] ) && $subdisposition_row['id'] == $_REQUEST['subdisposition_id'] ){ echo 'selected'; } ?> >
																									<?php echo $subdisposition_row['subdisposition_name']; ?>
																								</option>
																								<?php
																							}
																						}
																					}
																					?>
																				</select>
																			</div>
																			<div class="form-group">
																				<label for="call_remarks">Remarks</label>
																				<textarea id="call_remarks" class="form-control" name="call_remarks" placeholder="Remarks"><?php echo isset($_REQUEST['call_back_date']) ? $_REQUEST['call_remarks'] : ''; ?></textarea>
																			</div>
																			<div class="form-group">
																				<label for="interview_scheduled_date">Interview Scheduled Date</label>
																				<div class="input-group date" id="interview_scheduled_date_picker" data-target-input="nearest">
																					<input type="text" id="interview_scheduled_date" class="form-control datetimepicker-input" name="interview_scheduled_date" placeholder="Interview Scheduled Date" data-target="#interview_scheduled_date_picker" value="<?php echo isset($_REQUEST['interview_scheduled_date']) ? $_REQUEST['interview_scheduled_date'] : ''; ?>"/>
																					<div class="input-group-append" data-target="#interview_scheduled_date_picker" data-toggle="datetimepicker">
																						<span class="input-group-text">
																							<i class="far fa-calendar-alt"></i>
																						</span>
																					</div>
																				</div>
																			</div>																				
																		</div>
																	</div>
																</div>

															</div>
														</div>
													</div>
												</form>
												<?php
											}else{
												?>
												<div class="card card-custom card-stretch gutter-b">
													<div class="card-header border-0 pt-5">
														<h3 class="card-title align-items-start flex-column">
															<span class="card-label font-weight-bolder text-dark">Incorrect Listign ID</span>
														</h3>
													</div>
												</div>
												<?php
											}
											?>
										</div>
										<!--end::Body-->
									</div>
									<!--end::Base Table Widget 5-->
											
								</div>
							</div>
							<!--end::Row-->
							<!--end::Dashboard-->		
						</div>
					<!--end::Container-->
					</div>
					<!--end::Entry-->
				</div>
				<!--end::Content-->





				<!--begin::Footer-->
				<?php include_once('includes/page-footer.php'); ?>
				<!--end::Footer-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::Main-->

	



	<?php include_once('includes/theme-modal-and-offcanvas.php'); ?>


	<?php include_once('includes/footer-top.php'); ?>

	<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
	<script src="https://cdn.datatables.net/fixedcolumns/4.0.1/js/dataTables.fixedColumns.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#candidate_history_table").DataTable({
				"scrollY": '47vh',
				"scrollX": true,
				"order": [[ 59, 'desc' ]],
				"paging": false
			});
		})
	</script>

	<script src="assets/js/pages/crud/forms/widgets/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".select2").each(function( index ) {
				$(this).select2({
					placeholder: $(this).data('placeholder'),
					allowClear: true
				});
			})
		})
	</script>

	
	<script src="assets/js/offcanvas.js"></script>
	<script src="assets/js/custom-script.js"></script>

	<script src="assets/js/pages/crud/forms/widgets/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#date_of_birth_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['date_of_birth'] ) && !empty( $_REQUEST['date_of_birth'] ) ) ? $_REQUEST['date_of_birth'] : ''; ?>',
				format: 'YYYY-MM-DD'
			});
			$('#current_company_joining_date_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['current_company_joining_date'] ) && !empty( $_REQUEST['current_company_joining_date'] ) ) ? $_REQUEST['current_company_joining_date'] : ''; ?>',
				format: 'YYYY-MM-DD'
			});
			$('#last_drawn_salary_date_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['last_drawn_salary_date'] ) && !empty( $_REQUEST['last_drawn_salary_date'] ) ) ? $_REQUEST['last_drawn_salary_date'] : ''; ?>',
				format: 'YYYY-MM-DD'
			});
			$('#data_assigned_date_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['data_assigned_date'] ) && !empty( $_REQUEST['data_assigned_date'] ) ) ? $_REQUEST['data_assigned_date'] : ''; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#first_call_date_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['first_call_date'] ) && !empty( $_REQUEST['first_call_date'] ) ) ? $_REQUEST['first_call_date'] : ''; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#last_call_date_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['last_call_date'] ) && !empty( $_REQUEST['last_call_date'] ) ) ? $_REQUEST['last_call_date'] : ''; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#call_back_date_picker').datetimepicker({
				date:'<?php echo ( isset( $_REQUEST['call_back_date'] ) && !empty( $_REQUEST['call_back_date'] ) ) ? $_REQUEST['call_back_date'] : ''; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#interview_scheduled_date_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['interview_scheduled_date'] ) && !empty( $_REQUEST['interview_scheduled_date'] ) ) ? $_REQUEST['interview_scheduled_date'] : ''; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#ug_graduation_year_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['ug_graduation_year'] ) && !empty( $_REQUEST['ug_graduation_year'] ) ) ? $_REQUEST['ug_graduation_year'] : ''; ?>',
				format: 'YYYY'
			});
			$('#pg_graduation_year_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['ug_graduation_year'] ) && !empty( $_REQUEST['ug_graduation_year'] ) ) ? $_REQUEST['ug_graduation_year'] : ''; ?>',
				format: 'YYYY'
			});
			$('#dr_graduation_year_picker').datetimepicker({
				// date:'<?php echo ( isset( $_REQUEST['ug_graduation_year'] ) && !empty( $_REQUEST['ug_graduation_year'] ) ) ? $_REQUEST['ug_graduation_year'] : ''; ?>',
				format: 'YYYY'
			});
		}); 
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('change', '#disposition_id', function(){
				var target = $('#subdisposition_id');
				target.html('<option></option>');
				var disposition_id = [];
				disposition_id.push( $(this).val() );
				var data = {
					'ajax_for'		 :  'subdisposition_filter',
					'disposition_id' :	disposition_id,
				};
				jQuery.ajax({
					url: '<?php echo SITE_URL."/controller/ajax.php"; ?>',
					type: 'POST',
					data:  data,
					dataType: 'html',
				})
				.done(function(response_data){
					var response = $.parseJSON(response_data);												
					target.html('');
					target.append('<option>None</option>');
					if( response.response == 'success' ){
						var jsonData = response.data;
						$.each(jsonData, function (index, item) {
							target.append('<option value="'+item.id+'">'+item.subdisposition_name+'</option>');
						});
						target.select2({
							placeholder: target.data('placeholder'),
							allowClear: true
						});
					}else{
						alert(response.response + '\n' + response.description);
					}
  
				})
				.fail(function(){
					alert('Installation Sub Status not fetched, Please reload this page and try again');
				});
			})
		})
	</script>

	<?php
	if( isset( $_SESSION['UPDATE_CANDIDATE_TITLE'] ) && !empty( $_SESSION['UPDATE_CANDIDATE_TITLE'] ) && isset( $_SESSION['UPDATE_CANDIDATE_MESSAGE'] ) && !empty( $_SESSION['UPDATE_CANDIDATE_MESSAGE'] ) ){
		if( $_SESSION['UPDATE_CANDIDATE_TITLE'] == 'Success'){
			?>
			<script type="text/javascript">
				$(document).ready(function(){
					toastr.success("<?php echo $_SESSION['UPDATE_CANDIDATE_MESSAGE']; ?>", "<?php echo $_SESSION['UPDATE_CANDIDATE_TITLE']; ?>");
				})
			</script>
			<?php
		}elseif( $_SESSION['UPDATE_CANDIDATE_TITLE'] == 'Error'){
			?>
			<script type="text/javascript">
				$(document).ready(function(){
					toastr.error("<?php echo $_SESSION['UPDATE_CANDIDATE_MESSAGE']; ?>", "<?php echo $_SESSION['UPDATE_CANDIDATE_TITLE']; ?>");
				})
			</script>
			<?php
		}
	}
	unset($_SESSION['UPDATE_CANDIDATE_TITLE']);
	unset($_SESSION['UPDATE_CANDIDATE_MESSAGE']);
	?>

</body>
<!--end::Body-->
</html>
<?php
ob_end_flush();
?>

