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
									<?php
									if( isset($_REQUEST['id']) && !empty($_REQUEST['id']) ){

										$id = $_REQUEST['id'];
										$single_candidate_sql = "select * from candidates where id = ".$id;

										$single_candidate_query = mysqli_query($conn, $single_candidate_sql) or die("unable to fetch job candidate from database ".mysqli_error($conn));
										if( mysqli_num_rows($single_candidate_query) > 0 ){
											$single_candidate = mysqli_fetch_assoc($single_candidate_query);
											?>
											<!--begin::Base Table Widget 5-->
											<div class="card card-custom card-stretch gutter-b">
												<!--begin::Header-->
												<div class="card-header border-0 pt-5">
													<h3 class="card-title align-items-start flex-column">
														<?php
														$listing_title = '';
														$listing_id = $single_candidate['listing_id'];
														$listing_query = mysqli_query($conn, "select listing_title from job_listing where id = ".$listing_id) or die("unable to fetch listing name from database ".mysqli_error($conn));
														if( mysqli_num_rows($listing_query) == 1 ){
															while( $listing_row = mysqli_fetch_assoc($listing_query) ){
																$listing_title = $listing_row['listing_title'];
															}
														}
														?>

														<span class="card-label font-weight-bolder text-dark"><?php echo trim( $single_candidate['first_name'].' '.$single_candidate['last_name'] ); ?> ( <?php echo $listing_title; ?> ) </span>
													</h3>
												</div>
												<!--end::Header-->
												<!--begin::Body-->
												<div class="card-body pt-2 pb-0">
													<!-- <h1>Candidate details will come here</h1>
													<pre>
														<?php #print_r($single_candidate); ?>
													</pre> -->
													
													<form id="update_candidate_form" method="post" action="<?php echo SITE_URL.'/controller/update-candidate.php'; ?>" enctype='multipart/form-data'>
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
																							<input type="hidden" id="candidate_id" name="candidate_id" value="<?php echo $single_candidate['id']; ?>" />
																							<label for="first_name">First Name</label>
																							<input type="text" id="first_name" class="form-control" name="first_name" placeholder="First Name" value="<?php echo $single_candidate['first_name']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="last_name">Last Name</label>
																							<input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo $single_candidate['last_name']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="email">Email</label>
																							<input type="text" id="email" class="form-control" name="email" placeholder="Email" value="<?php echo $single_candidate['email']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="alternate_email">Alternate Email</label>
																							<input type="text" id="alternate_email" class="form-control" name="alternate_email" placeholder="Alternate Email" value="<?php echo $single_candidate['alternate_email']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="mobile">Mobile <span class="text-danger">*</span></label>
																							<input type="text" id="mobile" class="form-control" name="mobile" placeholder="Mobile" value="<?php echo $single_candidate['mobile']; ?>" required/>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="alternate_mobile">Alternate Mobile</label>
																							<input type="text" id="alternate_mobile" class="form-control" name="alternate_mobile" placeholder="Alternate Mobile" value="<?php echo $single_candidate['alternate_mobile']; ?>" />
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
																										$gender_array[] = $gender_row['gender'];
																									}
																								}
																								$gender_array_filtered = array_unique(array_filter($gender_array));

																								sort( $gender_array_filtered );

																								foreach( $gender_array_filtered as $gender){
																									?>
																									<option value="<?php echo $gender; ?>" <?php if( isset($single_candidate['gender']) && $single_candidate['gender'] == $gender ){ echo 'selected'; } ?> ><?php echo $gender; ?></option>
																									<?php
																								}
																								?>
																							</select>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="marital_status">Marital Status <?php echo $single_candidate['marital_status']; ?></label>
																							<select class="form-control select2" id="marital_status" name="marital_status" data-placeholder="Marital Status">
																								<option></option>
																								<?php
																								$get_marital_status = mysqli_query($conn, "select distinct marital_status from candidates order by marital_status asc") or die("unable to fetch marital_status from database ".mysqli_error($conn));
																								$marital_status_array = array('Single', 'Married');
																								if( mysqli_num_rows($get_marital_status) > 0 ){
																									while( $marital_status_row = mysqli_fetch_assoc($get_marital_status) ){
																										$marital_status_array[] = $marital_status_row['marital_status'];
																									}
																								}
																								$marital_status_array_filtered = array_unique(array_filter($marital_status_array));

																								sort( $marital_status_array_filtered );

																								foreach( $marital_status_array_filtered as $marital_status){
																									?>
																									<option value="<?php echo $marital_status; ?>" <?php if( isset($single_candidate['marital_status']) && $single_candidate['marital_status'] == $marital_status ){ echo 'selected'; } ?> ><?php echo $marital_status; ?></option>
																									<?php
																								}
																								?>
																							</select>
																							<?php #print_r($marital_status_array_filtered); ?>
																							<?php #print_r($marital_status_array_sorted); ?>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="date_of_birth">Date Of Birth</label>
																							<div class="input-group date" id="date_of_birth_picker" data-target-input="nearest">
																								<input type="text" id="date_of_birth" class="form-control datetimepicker-input" name="date_of_birth" placeholder="Date Of Birth" data-target="#date_of_birth_picker" value="<?php echo $single_candidate['date_of_birth']; ?>"/>
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
																							<textarea id="present_address" class="form-control" name="present_address" placeholder="Present Address"><?php echo $single_candidate['present_address']; ?></textarea>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="present_city">Present City</label>
																							<input type="text" id="present_city" class="form-control" name="present_city" placeholder="Present City" value="<?php echo $single_candidate['present_city']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="present_state">Present State</label>
																							<input type="text" id="present_state" class="form-control" name="present_state" placeholder="Present State" value="<?php echo $single_candidate['present_state']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="present_pincode">Present Pincode</label>
																							<input type="text" id="present_pincode" class="form-control" name="present_pincode" placeholder="Present Pincode" value="<?php echo $single_candidate['present_pincode']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="permanent_address">Permanent Address</label>
																							<textarea id="permanent_address" class="form-control" name="permanent_address" placeholder="Permanent Address"><?php echo $single_candidate['permanent_address']; ?></textarea>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="permanent_city">Permanent City</label>
																							<input type="text" id="permanent_city" class="form-control" name="permanent_city" placeholder="Permanent City" value="<?php echo $single_candidate['permanent_city']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="permanent_state">Permanent State</label>
																							<input type="text" id="permanent_state" class="form-control" name="permanent_state" placeholder="Permanent State" value="<?php echo $single_candidate['permanent_state']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="permanent_pincode">Permanent Pincode</label>
																							<input type="text" id="permanent_pincode" class="form-control" name="permanent_pincode" placeholder="Permanent Pincode" value="<?php echo $single_candidate['permanent_pincode']; ?>" />
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
																							<input type="text" id="ug_degree" class="form-control" name="ug_degree" placeholder="UG Degree" value="<?php echo $single_candidate['ug_degree']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="ug_specialization">UG Specialization</label>
																							<input type="text" id="ug_specialization" class="form-control" name="ug_specialization" placeholder="UG Specialization" value="<?php echo $single_candidate['ug_specialization']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="ug_university_institute">UG University Institute</label>
																							<input type="text" id="ug_university_institute" class="form-control" name="ug_university_institute" placeholder="UG University Institute" value="<?php echo $single_candidate['ug_university_institute']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="ug_graduation_year">UG Graduation Year</label>
																							<div class="input-group year" id="ug_graduation_year_picker" data-target-input="nearest">
																								<input type="text" id="ug_graduation_year" class="form-control datetimepicker-input" name="ug_graduation_year" placeholder="UG Graduation Year" data-target="#ug_graduation_year_picker" value="<?php echo isset($single_candidate['ug_graduation_year']) ? $single_candidate['ug_graduation_year'] : ''; ?>"/>
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
																							<input type="text" id="pg_degree" class="form-control" name="pg_degree" placeholder="PG Degree" value="<?php echo $single_candidate['pg_degree']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="pg_specialization">PG Specialization</label>
																							<input type="text" id="pg_specialization" class="form-control" name="pg_specialization" placeholder="PG Specialization" value="<?php echo $single_candidate['pg_specialization']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="pg_university_institute">PG University Institute</label>
																							<input type="text" id="pg_university_institute" class="form-control" name="pg_university_institute" placeholder="PG University Institute" value="<?php echo $single_candidate['pg_university_institute']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="pg_graduation_year">PG Graduation Year</label>
																							<div class="input-group year" id="pg_graduation_year_picker" data-target-input="nearest">
																								<input type="text" id="pg_graduation_year" class="form-control datetimepicker-input" name="pg_graduation_year" placeholder="PG Graduation Year" data-target="#pg_graduation_year_picker" value="<?php echo isset($single_candidate['pg_graduation_year']) ? $single_candidate['pg_graduation_year'] : ''; ?>"/>
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
																							<input type="text" id="dr_degree" class="form-control" name="dr_degree" placeholder="DR Degree" value="<?php echo $single_candidate['dr_degree']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="dr_specialization">DR Specialization</label>
																							<input type="text" id="dr_specialization" class="form-control" name="dr_specialization" placeholder="DR Specialization" value="<?php echo $single_candidate['dr_specialization']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="dr_university_institute">DR University Institute</label>
																							<input type="text" id="dr_university_institute" class="form-control" name="dr_university_institute" placeholder="DR University Institute" value="<?php echo $single_candidate['dr_university_institute']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="dr_graduation_year">DR Graduation Year</label>
																							<div class="input-group year" id="dr_graduation_year_picker" data-target-input="nearest">
																								<input type="text" id="dr_graduation_year" class="form-control datetimepicker-input" name="dr_graduation_year" placeholder="DR Graduation Year" data-target="#dr_graduation_year_picker" value="<?php echo isset($single_candidate['dr_graduation_year']) ? $single_candidate['dr_graduation_year'] : ''; ?>"/>
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
																								<input type="number" id="total_experience_year" class="form-control" name="total_experience_year" placeholder="Total Experience Years" value="<?php echo $single_candidate['total_experience_year']; ?>" />
																								<div class="input-group-append">
																									<span class="input-group-text">Y</span>
																								</div>
																								<input type="number" id="total_experience_month" class="form-control" name="total_experience_month" placeholder="Total Experience Months" value="<?php echo $single_candidate['total_experience_month']; ?>" />
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
																								<input type="number" id="relevent_experience_year" class="form-control" name="relevent_experience_year" placeholder="Relevent Experience Years" value="<?php echo $single_candidate['relevent_experience_year']; ?>" />
																								<div class="input-group-append">
																									<span class="input-group-text">Y</span>
																								</div>
																								<input type="number" id="relevent_experience_month" class="form-control" name="relevent_experience_month" placeholder="Relevent Experience Months" value="<?php echo $single_candidate['relevent_experience_month']; ?>" />
																								<div class="input-group-append">
																									<span class="input-group-text">M</span>
																								</div>
																							</div>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="is_working">Is Working</label>
																							<select class="form-control select2" id="is_working" name="is_working" data-placeholder="Working">
																								<option></option>
																								<option value="yes" <?php if( isset($single_candidate['is_working']) && $single_candidate['is_working'] == 'yes' ){ echo 'selected'; } ?> >Yes</option>
																								<option value="no" <?php if( isset($single_candidate['is_working']) && $single_candidate['is_working'] == 'no' ){ echo 'selected'; } ?> >No</option>
																							</select>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="current_company">Current Company</label>
																							<input type="text" id="current_company" class="form-control" name="current_company" placeholder="Current Company" value="<?php echo $single_candidate['current_company']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="current_company_joining_date">Current Company Joining Date</label>
																							<div class="input-group date" id="current_company_joining_date_picker" data-target-input="nearest">
																								<input type="text" id="current_company_joining_date" class="form-control datetimepicker-input" name="current_company_joining_date" placeholder="Current Company Joining Date" data-target="#current_company_joining_date_picker" value="<?php echo $single_candidate['current_company_joining_date']; ?>"/>
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
																							<input type="text" id="current_designation" class="form-control" name="current_designation" placeholder="Current Designation" value="<?php echo $single_candidate['current_designation']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="functional_area">Functional Area</label>
																							<input type="text" id="functional_area" class="form-control" name="functional_area" placeholder="Functional Area" value="<?php echo $single_candidate['functional_area']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="role">Role</label>
																							<input type="text" id="role" class="form-control" name="role" placeholder="Role" value="<?php echo $single_candidate['role']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="industry">Industry</label>
																							<input type="text" id="industry" class="form-control" name="industry" placeholder="Industry" value="<?php echo $single_candidate['industry']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="notice_period">Notice Period</label>
																							
																							<div class="input-group">
																								<input type="text" id="notice_period" class="form-control" name="notice_period" placeholder="Notice Period" value="<?php echo $single_candidate['notice_period']; ?>" />
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
																								<input type="text" id="annual_salary" class="form-control" name="annual_salary" placeholder="Annual Salary" value="<?php echo $single_candidate['annual_salary']; ?>" />
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
																								<input type="text" id="last_drawn_salary" class="form-control" name="last_drawn_salary" placeholder="Last Drawn Salary" value="<?php echo $single_candidate['last_drawn_salary']; ?>" />
																							</div>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="last_drawn_salary_date">Last Drawn Salary Date</label>
																							<div class="input-group date" id="last_drawn_salary_date_picker" data-target-input="nearest">
																								<input type="text" id="last_drawn_salary_date" class="form-control datetimepicker-input" name="last_drawn_salary_date" placeholder="Last Drawn Salary Date" data-target="#last_drawn_salary_date_picker" value="<?php echo $single_candidate['last_drawn_salary_date']; ?>"/>
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
																							<input type="text" id="current_company_address" class="form-control" name="current_company_address" placeholder="Current Company Address" value="<?php echo $single_candidate['current_company_address']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="current_company_city">Current Company City</label>
																							<input type="text" id="current_company_city" class="form-control" name="current_company_city" placeholder="Current Company City" value="<?php echo $single_candidate['current_company_city']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="current_company_state">Current Company State</label>
																							<input type="text" id="current_company_state" class="form-control" name="current_company_state" placeholder="Current Company State" value="<?php echo $single_candidate['current_company_state']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="current_company_pincode">Current Company Pincode</label>
																							<input type="text" id="current_company_pincode" class="form-control" name="current_company_pincode" placeholder="Current Company Pincode" value="<?php echo $single_candidate['current_company_pincode']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="preferred_location">Preferred Location</label>
																							<input type="text" id="preferred_location" class="form-control" name="preferred_location" placeholder="Preferred Location" value="<?php echo $single_candidate['preferred_location']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="skills">Skills</label>
																							<input type="text" id="skills" class="form-control" name="skills" placeholder="Skills" value="<?php echo $single_candidate['skills']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="resume">Resume</label>
																							<input type="file" id="resume_file" class="form-control" name="resume_file" >
																							<small style="position: relative;">
																								<a class="resume-name" href="<?php echo !empty($single_candidate['resume']) ? SITE_URL.$single_candidate['resume'] : '#'; ?>" target="_blank"><?php echo basename($single_candidate['resume']); ?></a>
																								<i class="fa fa-times <?php if( empty( $single_candidate['resume'] ) ){ echo 'd-none'; } ?>" style="cursor: pointer; position: absolute;, top:0px; right: 0px;
																								margin-right:-10px; color: red" onclick="$(this).parent().find('input#resume').val(''); $(this).parent().find('a.resume-name').hide(); $(this).hide();"></i>
																								<input type="hidden" name="resume" id="resume" value="<?php echo $single_candidate['resume']; ?>">
																							</small>
																						</div>
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<label for="resume_headline">Resume Headline</label>
																							<input type="text" id="resume_headline" class="form-control" name="resume_headline" placeholder="Resume Headline" value="<?php echo $single_candidate['resume_headline']; ?>" />
																						</div>
																					</div>
																					<div class="col-md-9">
																						<div class="form-group">
																							<label for="summary">Summary</label>
																							<textarea id="summary" class="form-control" name="summary" placeholder="Summary"><?php echo $single_candidate['summary']; ?></textarea>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="card">
																		<div class="card-header">
																			<div class="card-title" data-toggle="collapse" data-target="#communication_area">
																				<i class="flaticon2-bell-alarm-symbol"></i> Communication
																			</div>
																		</div>
																		<div id="communication_area" class="collapse show">
																			<div class="card-body">
																				<div class="row">
																					<div class="col-md-6">
																						<h3>Internal</h3>
																						<div class="form-group">
																							<label for="send_to">To</label>
																							<select class="form-control select2" id="send_to" data-placeholder="Send To">
																								<option></option>
																							</select>
																						</div>
																					</div>
																					<div class="col-md-6">
																						<h3>Candidate</h3>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="card">
																		<div class="card-header">
																			<div class="card-title" data-toggle="collapse" data-target="#candidate_history">
																				<i class="flaticon2-bell-alarm-symbol"></i> Candidate History
																			</div>
																		</div>
																		<div id="candidate_history" class="collapse show">
																			<div class="card-body">
																				<h3>Coming soon</h3>
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
																							
																								// $single_candidate_agent = $single_candidate['agent_id'];
																								// $logged_in_agent = $_SESSION['CURRENT_USER']['id'];
																								// $loop_agent = $agent_row['id'];
																							
																								// if( $loop_agent == $single_candidate_agent ){
																									// echo 'selected loop agent';
																								// }elseif( $loop_agent == $logged_in_agent ){
																									// echo 'selected logged in agent';
																								// }
																								
																								// if( $single_candidate_agent == $loop_agent ){
																									// echo 'selected loop agent2';
																								// }elseif( $logged_in_agent == $loop_agent  ){
																									// echo 'selected logged in agent2';
																								// }
																								
																								?>
																								<option value="<?php echo $agent_row['id']; ?>" <?php if(  $single_candidate['agent_id']==$agent_row['id'] ){ echo ' selected '; } ?> ><?php echo trim($agent_row['first_name'].' '.$agent_row['last_name']); ?></option>
																								<?php
																							}
																						}
																						?>
																					</select>
																				</div>
																				<?php
																				if( isset($single_candidate['data_assigned_date']) && !empty($single_candidate['data_assigned_date']) ){
																					?>
																					<div class="form-group mb-0">
																						<label for="data_assigned_date">Data Assigned Date <span class="label label-lg label-light-primary label-inline"><?php echo $single_candidate['data_assigned_date']; ?></span></label>
																					</div>
																					<?php
																				}
																				?>
																				<?php
																				if( isset($single_candidate['date_time']) && !empty($single_candidate['date_time']) ){
																					?>
																					<div class="form-group">
																						<label for="data_assigned_date">Data Received Date <span class="label label-lg label-light-primary label-inline"><?php echo $single_candidate['date_time']; ?></span></label>
																					</div>
																					<?php
																				}
																				?>
																				
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
																						$i = 0;
																						foreach( $source_array as $source ){
																							?>
																							<label for="source_<?php echo $source; ?>" class="label label-lg label-light-primary label-inline mr-2 mb-2" style="cursor: pointer;">
																								<input class="mr-1" type="radio" name="source" value="<?php echo $source; ?>" id="source_<?php echo $source; ?>" <?php if( $single_candidate['source'] == $source ){ echo 'checked'; } ?> <?php if($i==0){echo 'required';}?>>
																								<?php echo $source; ?>
																							</label>
																							<?php
																							$i++;
																						}
																						?>
																					</div>
																				</div>
																				<div class="form-group">
																					<label for="source_url">Source url / Reference Detail</label>
																					<input type="text" id="source_url" class="form-control" name="source_url" placeholder="Source Url" value="<?php echo $single_candidate['source_url']; ?>">
																				</div>
																				<div class="form-group">
																					<label for="first_call_date">First Call Date</label>
																					<div class="input-group date" id="first_call_date_picker" data-target-input="nearest">
																						<input type="text" id="first_call_date" class="form-control datetimepicker-input" name="first_call_date" placeholder="First Call Date" data-target="#first_call_date_picker" value="<?php echo $single_candidate['first_call_date']; ?>"/>
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
																						<input type="text" id="last_call_date" class="form-control datetimepicker-input" name="last_call_date" placeholder="Last Call Date" data-target="#last_call_date_picker" value="<?php echo $single_candidate['last_call_date']; ?>"/>
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
																						<input type="text" id="call_back_date" class="form-control datetimepicker-input" name="call_back_date" placeholder="Call Back Date" data-target="#call_back_date_picker" value="<?php echo $single_candidate['call_back_date']; ?>"/>
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
																								<option value="<?php echo $disposition_row['id']; ?>" <?php if( $disposition_row['id'] == $single_candidate['disposition_id'] ){ echo 'selected'; } ?> >
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
																						if( isset($single_candidate['disposition_id']) && !empty($single_candidate['disposition_id']) ){
																							$disposition_id = $single_candidate['disposition_id'];
																							$get_subdispositions = mysqli_query($conn, "select * from subdispositions where disposition_id = ".$disposition_id) or die("unable to fetch subdispositions from database ".mysqli_error($conn));
																							if( mysqli_num_rows($get_subdispositions) > 0 ){
																								while( $subdisposition_row = mysqli_fetch_assoc($get_subdispositions) ){
																									?>
																									<option value="<?php echo $subdisposition_row['id']; ?>" <?php if( $subdisposition_row['id'] == $single_candidate['subdisposition_id'] ){ echo 'selected'; } ?> >
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
																					<textarea id="call_remarks" class="form-control" name="call_remarks" placeholder="Remarks"><?php echo $single_candidate['call_remarks']; ?></textarea>
																				</div>
																				<div class="form-group">
																					<label for="interview_scheduled_date">Interview Scheduled Date</label>
																					<div class="input-group date" id="interview_scheduled_date_picker" data-target-input="nearest">
																						<input type="text" id="interview_scheduled_date" class="form-control datetimepicker-input" name="interview_scheduled_date" placeholder="Interview Scheduled Date" data-target="#interview_scheduled_date_picker" value="<?php echo $single_candidate['interview_scheduled_date']; ?>"/>
																						<div class="input-group-append" data-target="#interview_scheduled_date_picker" data-toggle="datetimepicker">
																							<span class="input-group-text">
																								<i class="far fa-calendar-alt"></i>
																							</span>
																						</div>
																					</div>
																				</div>
																				
																				<div class="form-group">
																					<input type="submit" id="submit" class="form-control btn btn-primary" name="submit_update_candidate_form" value="Update" />
																				</div>
																				
																			</div>
																		</div>
																	</div>
																	
																	
																	
																	
																	
																	
																	<div class="card">
																		<div class="card-header">
																			<div class="card-title" data-toggle="collapse" data-target="#call_recordings">
																				<i class="flaticon2-layers-1"></i> Call Recordings
																			</div>
																		</div>
																		<div id="call_recordings" class="collapse show">
																			<div class="card-body">
																			
																			
																			<table class="table table-striped sc-table sc-table-sidebar">
																				<tbody>
																					<tr>
																						<th>Outbound</th>
																					</tr>
																					<tr> 
																						<td>
																							<?php
																							$allowed_users=array(
																								'sharique_anwer'			=>	2,
																								'nazrul'					=>	1,
																								'rahul_hr'					=>	4,
																								'atul_agr_hr_mgr'			=>	5,
																							);
																							$current_user =	$_SESSION['CURRENT_USER']['id'];
																							
																							if( in_array($current_user, $allowed_users) ){
																							?><div id="hgsc_asterisk_outgoing_recordings" style=""></div><?php
																							}
																							else{
																								echo "<div id='' style=''>You do not have permission to view call recordings.</div>";
																							}
																							?>
																						</td>
																					</tr>
																					<tr>
																						<th>Inbound</th>
																					</tr>
																					<tr>
																						<td>
																							<?php
																							$allowed_users=array(
																								'sharique_anwer'			=>	2,
																								'nazrul'					=>	1,
																								'rahul_hr'					=>	4,
																								'atul_agr_hr_mgr'			=>	5,
																							);
																							$current_user =	$_SESSION['CURRENT_USER']['id'];
																							
																							if( in_array($current_user, $allowed_users) ){ ?>
																								<div id="hgsc_asterisk_incoming_recordings" style=""></div> <?php
																							}
																							else{
																								echo "<div id='' style=''>You do not have permission to view call recordings.</div>";
																							}
																							?>
																						</td>
																					</tr>
																				</tbody>
																			</table>					
																			
																			
																			</div>
																		</div>
																	</div>
																			
																			
																			
																			
																			
																	
																	

																	<div class="card">
																		<div class="card-header">
																			<div class="card-title" data-toggle="collapse" data-target="#updates_and_logs">
																				<i class="flaticon2-layers-1"></i> Updates & Logs
																			</div>
																		</div>
																		<div id="updates_and_logs" class="collapse show">
																			<div class="card-body">



																				<div class="form-group">
																					<div class="d-flex align-items-center justify-content-end">

																						<?php
																						$history_sql = "select cr.*, u.first_name as agent_first_name, u.last_name as agent_last_name, d.disposition_name as disposition_name, sd.subdisposition_name as subdisposition_name from candidates_revision cr left join users u on u.id = cr.agent_id left join dispositions d on d.id = cr.disposition_id left join subdispositions sd on sd.id = cr.subdisposition_id where cr.candidate_id = ".$single_candidate['id']." order by cr.updated_date_time DESC";
																						#echo $history_sql."<br><br><br><br><br>";

																						$history_query = mysqli_query($conn, $history_sql) or die("unable to fetch history from database ".mysqli_error($conn));
																						$history_count = mysqli_num_rows($history_query);
																						$history = array();
																						if( $history_count > 0 ){
																							while( $history_row = mysqli_fetch_assoc($history_query) ){
																								$history[] = $history_row;
																							}
																						}
																						?>

																						<!-- History modal trigger -->
																						<button type="button" class="btn btn-sm btn-primary" id="history_offcanvas_toggle" data-bs-toggle="offcanvas" aria-controls="#history_offcanvas"><i class="flaticon2-layers-1"></i> History <span class="badge badge-primary"><?php echo $history_count; ?></span></button>

																						<!-- begin::update Listing Panel-->
																						<div id="history_offcanvas" class="offcanvas offcanvas-start p-10">
																							<!--begin::Header-->
																							<div class="offcanvas-header d-flex align-items-center justify-content-between mb-10">
																								<h3 class="font-weight-bold m-0">
																									History
																									<small class="text-muted font-size-sm ml-2"><?php echo $history_count; ?> New</small>
																								</h3>
																								<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="history_offcanvas_close" data-bs-dismiss="offcanvas">
																									<i class="ki ki-close icon-xs text-muted"></i>
																								</a>
																							</div>
																							<!--end::Header-->
																							<!--begin::Content-->
																							<div class="offcanvas-content pr-5 mr-n5" >
																								<?php
																						    	if( count($history) > 0){
																						    		?>
																						    		<table id="candidate_history_table" class="table nowrap text-center" >
																										<thead>
																											<tr>
																												<th>ID</th>
																												<th>Candidate ID</th>
																												<th>First Name</th>
																												<th>Email</th>
																												<th>Alternate Email</th>
																												<th>Mobile</th>
																												<th>Alternate Mobile</th>
																												<th>Gender</th>
																												<th>Marital Status</th>
																												<th>Date Of Birth</th>
																												<th>Present Address</th>
																												<th>Present City</th>
																												<th>Present State</th>
																												<th>Present Pincode</th>
																												<th>Permanent Address</th>
																												<th>Permanent City</th>
																												<th>Permanent State</th>
																												<th>Permanent Pincode</th>
																												<th>Total Experience</th>
																												<th>Relevent Experience</th>
																												<th>Is Working</th>
																												<th>Current Company</th>
																												<th>Current Company Joining Date</th>
																												<th>Current Designation</th>
																												<th>Functional Area</th>
																												<th>Role</th>
																												<th>Industry</th>
																												<th>Notice Period</th>
																												<th>Annual Salary</th>
																												<th>Last Drawn Salary</th>
																												<th>Last Drawn Salary Date</th>
																												<th>Current Company Address</th>
																												<th>Current Company City</th>
																												<th>Current Company State</th>
																												<th>Current Company Pincode</th>
																												<th>Preferred Location</th>
																												<th>Skills</th>
																												<th>Resume</th>
																												<th>Resume Headline</th>
																												<th>Summary</th>
																												<th>UG Degree</th>
																												<th>UG Specialization</th>
																												<th>UG University Institute</th>
																												<th>UGGraduation Year</th>
																												<th>PG Degree</th>
																												<th>PG Specialization</th>
																												<th>PG University Institute</th>
																												<th>PGGraduation Year</th>
																												<th>DR Degree</th>
																												<th>DR Specialization</th>
																												<th>DR Uuniversity Institute</th>
																												<th>DR Graduation Year</th>
																												<th>Source</th>
																												<th>Source Url</th>
																												<th>Disposition</th>
																												<th>Subdisposition</th>
																												<th>Call Remarks</th>
																												<th>Published Date</th>
																												<th>Revision Saved By</th>
																												<th>Revision Saved On</th>
																											</tr>
																										</thead>
																										<tfoot>
																											<tr>
																												<th>ID</th>
																												<th>Candidate ID</th>
																												<th>First Name</th>
																												<th>Email</th>
																												<th>Alternate Email</th>
																												<th>Mobile</th>
																												<th>Alternate Mobile</th>
																												<th>Gender</th>
																												<th>Marital Status</th>
																												<th>Date Of Birth</th>
																												<th>Present Address</th>
																												<th>Present City</th>
																												<th>Present State</th>
																												<th>Present Pincode</th>
																												<th>Permanent Address</th>
																												<th>Permanent City</th>
																												<th>Permanent State</th>
																												<th>Permanent Pincode</th>
																												<th>Total Experience</th>
																												<th>Relevent Experience</th>
																												<th>Is Working</th>
																												<th>Current Company</th>
																												<th>Current Company Joining Date</th>
																												<th>Current Designation</th>
																												<th>Functional Area</th>
																												<th>Role</th>
																												<th>Industry</th>
																												<th>Notice Period</th>
																												<th>Annual Salary</th>
																												<th>Last Drawn Salary</th>
																												<th>Last Drawn Salary Date</th>
																												<th>Current Company Address</th>
																												<th>Current Company City</th>
																												<th>Current Company State</th>
																												<th>Current Company Pincode</th>
																												<th>Preferred Location</th>
																												<th>Skills</th>
																												<th>Resume</th>
																												<th>Resume Headline</th>
																												<th>Summary</th>
																												<th>UG Degree</th>
																												<th>UG Specialization</th>
																												<th>UG University Institute</th>
																												<th>UGGraduation Year</th>
																												<th>PG Degree</th>
																												<th>PG Specialization</th>
																												<th>PG University Institute</th>
																												<th>PGGraduation Year</th>
																												<th>DR Degree</th>
																												<th>DR Specialization</th>
																												<th>DR Uuniversity Institute</th>
																												<th>DR Graduation Year</th>
																												<th>Source</th>
																												<th>Source Url</th>
																												<th>Disposition</th>
																												<th>Subdisposition</th>
																												<th>Call Remarks</th>
																												<th>Published Date</th>
																												<th>Revision Saved By</th>
																												<th>Revision Saved On</th>
																											</tr>
																										</tfoot>
																										<tbody>
																											<?php
																											foreach( $history as $single_history ){
																												?>
																												<tr>
																													<td>
																														<?php echo $single_history['id']; ?>
																													</td>
																													<td>
																														<?php echo $single_history['candidate_id']; ?>
																													</td>
																													<td><?php echo trim( $single_history['first_name'].' '.$single_history['last_name'] ); ?></td>
																													<td>
																														<?php #echo $candidate['email']; ?>
																														<?php
																														if( !empty( $single_history['email'] ) ){
																															$emails = explode(',', $single_history['email']);
																															foreach( $emails as $email ){
																																?>
																																<span class="badge badge-primary">
																																	<?php echo $email; ?>
																																</span>
																																<?php
																															}
																														}
																														?>
																													</td>
																													<td><?php echo $single_history['alternate_email']; ?></td>
																													<td><?php echo $single_history['mobile']; ?></td>
																													<td><?php echo $single_history['alternate_mobile']; ?></td>
																													<td><?php echo $single_history['gender']; ?></td>
																													<td><?php echo $single_history['marital_status']; ?></td>
																													<td><?php echo $single_history['date_of_birth']; ?></td>
																													<td><?php echo $single_history['present_address']; ?></td>
																													<td><?php echo $single_history['present_city']; ?></td>
																													<td><?php echo $single_history['present_state']; ?></td>
																													<td><?php echo $single_history['present_pincode']; ?></td>
																													<td><?php echo $single_history['permanent_address']; ?></td>
																													<td><?php echo $single_history['permanent_city']; ?></td>
																													<td><?php echo $single_history['permanent_state']; ?></td>
																													<td><?php echo $single_history['permanent_pincode']; ?></td>
																													<td><?php echo $single_history['total_experience_year'].' Years '.$single_history['total_experience_month'].' Month'; ?></td>
																													<td><?php echo $single_history['relevent_experience_year'].' Years '.$single_history['relevent_experience_month'].' Month'; ?></td>
																													<td><?php echo $single_history['is_working']; ?></td>
																													<td><?php echo $single_history['current_company']; ?></td>
																													<td><?php echo $single_history['current_company_joining_date']; ?></td>
																													<td><?php echo $single_history['current_designation']; ?></td>
																													<td><?php echo $single_history['functional_area']; ?></td>
																													<td><?php echo $single_history['role']; ?></td>
																													<td><?php echo $single_history['industry']; ?></td>
																													<td><?php echo $single_history['notice_period']; ?></td>
																													<td><?php echo $single_history['annual_salary']; ?></td>
																													<td><?php echo $single_history['last_drawn_salary']; ?></td>
																													<td><?php echo $single_history['last_drawn_salary_date']; ?></td>
																													<td><?php echo $single_history['current_company_address']; ?></td>
																													<td><?php echo $single_history['current_company_city']; ?></td>
																													<td><?php echo $single_history['current_company_state']; ?></td>
																													<td><?php echo $single_history['current_company_pincode']; ?></td>
																													<td><?php echo $single_history['preferred_location']; ?></td>
																													<td>
																														<?php #echo $candidate['skills']; ?>
																														<?php
																														if( !empty( $single_history['skills'] ) ){
																															$skills = explode(',', $single_history['skills']);
																															foreach( $skills as $skill ){
																																?>
																																<span class="badge badge-primary">
																																	<?php echo $skill; ?>
																																</span>
																																<?php
																															}
																														}
																														?>
																													</td>
																													<td><?php echo $single_history['resume']; ?></td>
																													<td><?php echo $single_history['resume_headline']; ?></td>
																													<td><?php echo $single_history['summery']; ?></td>
																													<td><?php echo $single_history['ug_degree']; ?></td>
																													<td><?php echo $single_history['ug_specialization']; ?></td>
																													<td><?php echo $single_history['ug_university_institute']; ?></td>
																													<td><?php echo $single_history['ug_graduation_year']; ?></td>
																													<td><?php echo $single_history['pg_degree']; ?></td>
																													<td><?php echo $single_history['pg_specialization']; ?></td>
																													<td><?php echo $single_history['pg_university_institute']; ?></td>
																													<td><?php echo $single_history['pg_graduation_year']; ?></td>
																													<td><?php echo $single_history['dr_degree']; ?></td>
																													<td><?php echo $single_history['dr_specialization']; ?></td>
																													<td><?php echo $single_history['dr_university_institute']; ?></td>
																													<td><?php echo $single_history['dr_graduation_year']; ?></td>
																													<td><?php echo $single_history['source']; ?></td>
																													<td><?php echo $single_history['source_url']; ?></td>
																													<td><span class="label label-sm label-light-primary label-inline"><?php echo $single_history['disposition_name']; ?></span></td>
																													<td><span class="label label-sm label-light-primary label-inline"><?php echo $single_history['subdisposition_name']; ?></span></td>
																													<td><?php echo $single_history['call_remarks']; ?></td>
																													<td><?php echo $single_history['date_time']; ?></td>
																													<td><span class="label label-sm label-light-primary label-inline"><?php echo trim($single_history['agent_first_name'].' '.$single_history['agent_last_name']); ?></span></td>
																													<td><?php echo $single_history['updated_date_time']; ?></td>
																												</tr>
																												<?php
																											}
																											?>
																										</tbody>
																									</table>
																						    		<?php
																						    	}
																						    	?>
																							</div>
																							<!--end::Content-->
																						</div>
																						<!-- end::update Listing Panel-->

																						<!-- History Modal-->
																						<div class="modal fade" id="history_modal" >
																						    <div class="modal-dialog modal-xl" role="document">
																						        <div class="modal-content">
																						            <div class="modal-header">
																						                <h5 class="modal-title" id="exampleModalLabel">History</h5>
																						                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
																						                    <i aria-hidden="true" class="ki ki-close"></i>
																						                </button>
																						            </div>
																						            <div class="modal-body">
																						            	<div class="w-100 clearfix" style="max-height:80vh">
																							            	
																							            </div>
																						            </div>
																						            <div class="modal-footer">
																						                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
																						            </div>
																						        </div>
																						    </div>
																						</div>
																					</div>
																				</div>
																				<div class="form-group">
																					<div class="timeline timeline-6" style="max-height: 300px; overflow-y: auto;">
																						<?php
																						foreach( $history as $single_history ){
																							?>
																							<div class="timeline-item align-items-start">
																	                            <div class="timeline-label"><span class="label label-sm label-light-primary label-inline"><?php echo date('M d, Y h:i a', strtotime($single_history['updated_date_time'])); ?></span></div>
																	                            <div class="timeline-badge">
																	                                <i class="fa fa-genderless text-primary"></i>
																	                            </div>
																	                            <div class="timeline-content">
																	                                Disposition: <span class="label label-sm label-light-primary label-inline"><?php echo !empty($single_history['disposition_name']) ? $single_history['disposition_name'] : 'none'; ?></span><br>Sub Disposition: <span class="label label-sm label-light-primary label-inline"><?php echo !empty($single_history['subdisposition_name']) ? $single_history['subdisposition_name'] : 'none'; ?></span>
																	                            </div>
																	                        </div>
																							<?php
																						}
																						?>

																					    <div class="timeline-item align-items-start">
																                            <div class="timeline-label"><span class="label label-sm label-light-primary label-inline"><?php echo date('M d, Y h:i a', strtotime($single_candidate['date_time'])); ?></span></div>
																                            <div class="timeline-badge">
																                                <i class="fa fa-genderless text-primary"></i>
																                            </div>
																                            <div class="timeline-content">
																                                Published
																                            </div>
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
												</div>
												<!--end::Body-->
											</div>
											<!--end::Base Table Widget 5-->
											<?php
										}else{
											?>
											<div class="card card-custom card-stretch gutter-b">
												<div class="card-header border-0 pt-5">
													<h3 class="card-title align-items-start flex-column">
														<span class="card-label font-weight-bolder text-dark">Candidate Not Found With <?php echo $id; ?></span>
													</h3>
												</div>
											</div>
											<?php
										}
									}else{
										?>
										<div class="card card-custom card-stretch gutter-b">
											<div class="card-header border-0 pt-5">
												<h3 class="card-title align-items-start flex-column">
													<span class="card-label font-weight-bolder text-dark">No Data found</span>
												</h3>
											</div>
										</div>
										<?php
									}
									?>
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
				date:'<?php echo $single_candidate['date_of_birth']; ?>',
				format: 'YYYY-MM-DD'
			});
			$('#current_company_joining_date_picker').datetimepicker({
				date:'<?php echo $single_candidate['current_company_joining_date']; ?>',
				format: 'YYYY-MM-DD'
			});
			$('#last_drawn_salary_date_picker').datetimepicker({
				date:'<?php echo $single_candidate['last_drawn_salary_date']; ?>',
				format: 'YYYY-MM-DD'
			});
			$('#data_assigned_date_picker').datetimepicker({
				date:'<?php echo $single_candidate['data_assigned_date']; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#first_call_date_picker').datetimepicker({
				date:'<?php echo $single_candidate['first_call_date']; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#last_call_date_picker').datetimepicker({
				date:'<?php echo $single_candidate['last_call_date']; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#call_back_date_picker').datetimepicker({
				date:'<?php echo $single_candidate['call_back_date']; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#interview_scheduled_date_picker').datetimepicker({
				date:'<?php echo $single_candidate['interview_scheduled_date']; ?>',
				format: 'YYYY-MM-DD HH:mm:ss'
			});
			$('#ug_graduation_year_picker').datetimepicker({
				date:'<?php echo $single_candidate['ug_graduation_year']; ?>',
				format: 'YYYY'
			});
			$('#pg_graduation_year_picker').datetimepicker({
				date:'<?php echo $single_candidate['pg_graduation_year']; ?>',
				format: 'YYYY'
			});
			$('#dr_graduation_year_picker').datetimepicker({
				date:'<?php echo $single_candidate['dr_graduation_year']; ?>',
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





	<!---- Asterisk Call Recording ------------>
	<script type="text/javascript">
		jQuery( document ).ready(function(e) {
			var asterisk_outgoing_call_recording_url="//182.71.52.186/hg-ajax-api.php";
			var mobile= "<?php echo trim(explode(',', $single_candidate['mobile'])[0]); ?>";

			var data = {
				'recording_type'	:  'outgoing',
				'mobile'			:	mobile,
			};

			jQuery.ajax({
				url: asterisk_outgoing_call_recording_url,
				type: 'POST',
				data:  data,
				dataType: 'html',
			})
			.done(function(data){   
				jQuery('#hgsc_asterisk_outgoing_recordings').html('');
				jQuery('#hgsc_asterisk_outgoing_recordings').html(data); // load response 
				console.log(data);

			})
			.fail(function(){
				jQuery('#hgsc_asterisk_outgoing_recordings').html('API Connection Failed!<br/>1. <a href="https://182.71.52.186/hg-ajax-api.php" target="_blank">Click here to Activate</a><br/>2. On the new tab, Click advance and click on Proceed to 182.71.52.186 (unsafe).<br/>3. Return to this window and reload the page.<br/>Its a one time process until you clear your browser settings. To get rid of this, please point a domain and install a valid ssl certificate on asterisk server.');
			});
		});
		</script>
		<script type="text/javascript">
		jQuery( document ).ready(function(e) {
			var asterisk_incoming_call_recording_url="//182.71.52.186/hg-ajax-api.php";
			var mobile= "<?php echo trim(explode(',', $single_candidate['mobile'])[0]); ?>";

			var data = {
				'recording_type'	:  'incoming',
				'mobile'			:	mobile,
			};

			jQuery.ajax({
				url: asterisk_incoming_call_recording_url,
				type: 'POST',
				data:  data,
				dataType: 'html',
			})
			.done(function(data){   
				jQuery('#hgsc_asterisk_incoming_recordings').html('');
				jQuery('#hgsc_asterisk_incoming_recordings').html(data); // load response 
				console.log(data);

			})
			.fail(function(){
				jQuery('#hgsc_asterisk_incoming_recordings').html('API Connection Failed!<br/>1. <a href="https://182.71.52.186/hg-ajax-api.php" target="_blank">Click here to Activate</a><br/>2. On the new tab, Click advance and click on Proceed to 182.71.52.186 (unsafe).<br/>3. Return to this window and reload the page.<br/>Its a one time process until you clear your browser settings. To get rid of this, please point a domain and install a valid ssl certificate on asterisk server.');
			});
		});
	</script>
</body>
<!--end::Body-->
</html>

