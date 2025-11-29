<?php require_once("includes/config.php"); ?>
<?php if( !isset($_SESSION['CURRENT_USER']) ){ header('Location: '.SITE_URL.'/login.php'); } ?>
<!DOCTYPE html>
<html lang="en" >
<!--begin::Head-->
<head>
	<base href="">
	<meta charset="utf-8"/>
	<title>HR Management</title>

	<?php require_once("includes/header-top.php"); ?>

<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.datatables.net/fixedcolumns/4.0.1/css/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css" rel="stylesheet" type="text/css"/>

	<link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>


	<link href="assets/css/custom-style.css" rel="stylesheet" type="text/css"/>
	<style type="text/css">
		/*div.DTFC_LeftHeadWrapper table, div.DTFC_RightHeadWrapper table {
			margin: 0px !important;
		}*/
		.dataTables_wrapper .dataTable{
			margin:  0px !important;
		}
		#create_listing,
		#update_listing{
			width: 768px;
			left: unset;
			right: -768px;
		}
		#update_listing.offcanvas.offcanvas-on,
		#update_listing.offcanvas.offcanvas-on{
			width: 768px;
			right: 0;
		}
		.filter-card{
			height: auto !important;
		}

		ul.filter-counts > li{
			margin-right: 0.5rem !important;
		}

		.slidertooltip {
			background: #cc96bf;
			color: #fff;
			position: absolute;
			top: -100%;
			left: 50%;
			transform: translateY(-25px) translateX(-50%);
			padding: 5px 8px;
			border-radius: 50px;
			min-width: 20px;
			text-align: center;
		}
		.slidertooltip:before{
			content: "";
			background: #cc96bf;
			color: #fff;
			position: absolute;
			bottom: -5%;
			left: 50%;
			transform: translateY(0) translateX(-50%) rotate(45deg);
			width: 15px;
			height: 15px;
			text-align: center;
			z-index: -1;
		}
		table.dataTable tr.selected td.select-checkbox::after, table.dataTable tr.selected th.select-checkbox::after {
			margin-top: -8px;
		}
		table.dataTable tbody td.select-checkbox::before, table.dataTable tbody th.select-checkbox::before {
			margin-top: 12px;
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
										$single_listing_sql = "select * from job_listing where id = ".$id;

										$single_listing_query = mysqli_query($conn, $single_listing_sql) or die("unable to fetch job listing from database ".mysqli_error($conn));
										if( mysqli_num_rows($single_listing_query) > 0 ){
											$listing = mysqli_fetch_assoc($single_listing_query);
											?>
											<!--begin::Base Table Widget 5-->
											<div class="card card-custom card-stretch gutter-b">
												<!--begin::Header-->
												<div class="card-header border-0 pt-5">
													<h3 class="card-title align-items-start flex-column">
														<span class="card-label font-weight-bolder text-dark"><?php echo $listing['listing_title']." (#".$id.")"; ?> </span>
														<span class="text-muted mt-3 font-weight-bold font-size-sm"><?php echo $listing['listing_description']; ?></span>
													</h3>
													<div class="card-toolbar">
														<ul class="nav nav-pills nav-pills-sm nav-dark-75">

															<li class="nav-item">
																<a class="nav-link py-2 px-4 active" id="update_listing_toggle" href="#" data-bs-toggle="offcanvas" aria-controls="update_listing">Edit</a>
															</li>
													
															<!-- begin::update Listing Panel-->
															<div id="update_listing" class="offcanvas offcanvas-start p-10">
																<!--begin::Header-->
																<div class="offcanvas-header d-flex align-items-center justify-content-between mb-10">
																	<h3 class="font-weight-bold m-0">
																		Update Listing
																		<!-- <small class="text-muted font-size-sm ml-2">24 New</small> -->
																	</h3>
																	<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="update_listing_close" data-bs-dismiss="offcanvas">
																		<i class="ki ki-close icon-xs text-muted"></i>
																	</a>
																</div>
																<!--end::Header-->
																<!--begin::Content-->
																<div class="offcanvas-content pr-5 mr-n5" id="update_listing_form_wrapper">
																	<form method="post" action="<?php echo SITE_URL.'/controller/update-listing.php'; ?>">
																		<input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>" id="listing_id" >
																		<div class="form-group">
																			<label for="listing_title">Title <span class="text-danger">*</span></label>
																			<input type="text" id="listing_title" class="form-control" name="listing_title" placeholder="Title" value="<?php echo $listing['listing_title']; ?>" required>
																		</div>

																		<div class="form-group">
																			<label for="listing_description">Description <span class="text-danger">*</span></label>
																			<textarea id="listing_description" class="form-control" name="listing_description" placeholder="Description" required><?php echo $listing['listing_description']; ?></textarea>
																		</div>

																		<div class="form-group">
																			<label for="position_id">Position <span class="text-danger">*</span></label>
																			<select class="form-control select2" id="position_id" name="position_id" data-placeholder="Position" required>
																				<option></option>
																				<?php
																				$get_positions = mysqli_query($conn, "select * from positions order by position_name asc") or die("unable to fetch positions from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_positions) > 0 ){
																					while( $position = mysqli_fetch_assoc($get_positions) ){
																						?>
																						<option value="<?php echo $position['id']; ?>" <?php if( isset($listing['position_id']) && $listing['position_id'] == $position['id'] ){ echo 'selected'; } ?> >
																							<?php echo $position['position_name']; ?>
																						</option>
																						<?php
																					}
																				}
																				?>
																			</select>
																		</div>

																		<div class="form-group">
																			<label for="company_id">Company <span class="text-danger">*</span></label>
																			<select class="form-control select2" id="company_id" name="company_id" data-placeholder="Company" required>
																				<option></option>
																				<?php
																				$get_companies = mysqli_query($conn, "select * from companies order by company_name asc") or die("unable to fetch companies from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_companies) > 0 ){
																					while( $company = mysqli_fetch_assoc($get_companies) ){
																						?>
																						<option value="<?php echo $company['id']; ?>" <?php if( isset($listing['company_id']) && $listing['company_id'] == $company['id'] ){ echo 'selected'; } ?> ><?php echo $company['company_name']; ?>
																						</option>
																						<?php
																					}
																				}
																				?>
																			</select>
																		</div>

																		<div class="form-group">
																			<label for="department_id">Department <span class="text-danger">*</span></label>
																			<select class="form-control select2" id="department_id" name="department_id" data-placeholder="Department" required>
																				<option></option>
																				<?php
																				$get_departments = mysqli_query($conn, "select * from departments order by department_name asc") or die("unable to fetch departments from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_departments) > 0 ){
																					while( $department = mysqli_fetch_assoc($get_departments) ){
																						?>
																						<option value="<?php echo $department['id']; ?>" <?php if( isset($listing['department_id']) && $listing['department_id'] == $department['id'] ){ echo 'selected'; } ?> >
																							<?php echo $department['department_name']; ?>
																								
																							</option>
																						<?php
																					}
																				}
																				?>
																			</select>
																		</div>

																		<div class="form-group">
																			<label for="job_location">Job Location <span class="text-danger">*</span></label>
																			<input type="text" id="job_location" class="form-control" name="job_location" placeholder="Job Location" value="<?php echo $listing['job_location']; ?>" required>
																		</div>


																		<div class="form-group" style="overflow-x: hidden">
																			<label>Experience <span class="text-danger">*</span></label>
																			<div class="row">
																				<div class="col-6">
																					<div class="input-group">
																						<div class="input-group-prepend">
																							<span class="input-group-text">Min</span>
																						</div>
																						<input type="number" id="min_experience" class="form-control" name="min_experience" min="0" placeholder="Min Exp" value="<?php echo $listing['min_experience']; ?>" required>
																						<div class="input-group-append">
																							<span class="input-group-text">Years</span>
																						</div>
																					</div>
																				</div>
																				<div class="col-6">
																					<div class="input-group">
																						<div class="input-group-prepend">
																							<span class="input-group-text">Max</span>
																						</div>
																						<input type="number" id="max_experience" class="form-control" name="max_experience" min="0" placeholder="Max Exp" value="<?php echo $listing['max_experience']; ?>" required>
																						<div class="input-group-append">
																							<span class="input-group-text">Years</span>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="form-group" style="overflow-x: hidden">
																			<label>Budget <span class="text-danger">*</span></label>
																			<div class="row">
																				<div class="col-6">
																					<div class="input-group">
																						<div class="input-group-prepend">
																							<span class="input-group-text">Min</span>
																						</div>
																						<input type="number" id="min_budget" class="form-control" name="min_budget" min="0" placeholder="Min Budget" value="<?php echo $listing['min_budget']; ?>" required>
																						<div class="input-group-append">
																							<span class="input-group-text">
																								<svg style="max-width:10px" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="rupee-sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-rupee-sign fa-w-10 fa-3x">
																									<path fill="currentColor" d="M320 60V44c0-6.627-5.373-12-12-12H12C5.373 32 0 37.373 0 44v16c0 6.627 5.373 12 12 12h72.614c47.093 0 81.306 20.121 93.376 56H12c-6.627 0-12 5.373-12 12v16c0 6.627 5.373 12 12 12h170.387c-4.043 50.107-41.849 79.554-98.41 79.554H12c-6.627 0-12 5.373-12 12v15.807c0 2.985 1.113 5.863 3.121 8.072l175.132 192.639a11.998 11.998 0 0 0 8.879 3.928h21.584c10.399 0 15.876-12.326 8.905-20.043L62.306 288h23.407c77.219 0 133.799-46.579 138.024-120H308c6.627 0 12-5.373 12-12v-16c0-6.627-5.373-12-12-12h-87.338c-4.96-22.088-15.287-40.969-29.818-56H308c6.627 0 12-5.373 12-12z" class="">
																										
																									</path>
																								</svg>
																							</span>
																						</div>
																						<div class="input-group-append">
																							<span class="input-group-text">Yearly</span>
																						</div>
																					</div>
																				</div>
																				<div class="col-6">
																					<div class="input-group">
																						<div class="input-group-prepend">
																							<span class="input-group-text">Max</span>
																						</div>
																						<input type="number" id="max_budget" class="form-control" name="max_budget" min="0" placeholder="Max budget" value="<?php echo $listing['max_budget']; ?>" required>
																						<div class="input-group-append">
																							<span class="input-group-text">
																								<svg style="max-width:10px" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="rupee-sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-rupee-sign fa-w-10 fa-3x">
																									<path fill="currentColor" d="M320 60V44c0-6.627-5.373-12-12-12H12C5.373 32 0 37.373 0 44v16c0 6.627 5.373 12 12 12h72.614c47.093 0 81.306 20.121 93.376 56H12c-6.627 0-12 5.373-12 12v16c0 6.627 5.373 12 12 12h170.387c-4.043 50.107-41.849 79.554-98.41 79.554H12c-6.627 0-12 5.373-12 12v15.807c0 2.985 1.113 5.863 3.121 8.072l175.132 192.639a11.998 11.998 0 0 0 8.879 3.928h21.584c10.399 0 15.876-12.326 8.905-20.043L62.306 288h23.407c77.219 0 133.799-46.579 138.024-120H308c6.627 0 12-5.373 12-12v-16c0-6.627-5.373-12-12-12h-87.338c-4.96-22.088-15.287-40.969-29.818-56H308c6.627 0 12-5.373 12-12z" class="">
																										
																									</path>
																								</svg>
																							</span>
																						</div>
																						<div class="input-group-append">
																							<span class="input-group-text">Yearly</span>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label for="no_of_vacancy">Number of vacancies <span class="text-danger">*</span></label>
																			<input type="number" id="no_of_vacancy" class="form-control" name="no_of_vacancy" placeholder="Number of vacancies" value="<?php if( isset($listing['no_of_vacancy']) &&  !empty($listing['no_of_vacancy']) ){ echo $listing['no_of_vacancy']; } ?>" min="0" required />
																		</div>

																		<div class="form-group">
																			<label for="priority">Priority <span class="text-danger">*</span></label>
																			<input type="number" id="priority" class="form-control" name="priority" placeholder="Priority" value="<?php if( isset($listing['priority']) &&  !empty($listing['priority']) ){ echo $listing['priority']; } ?>" min="0" required/>
																		</div>

																		<div class="form-group">
																			<label for="target_closure_date">Target Closure Date <span class="text-danger">*</span></label>
																			<input type="text" id="target_closure_date" class="form-control" name="target_closure_date" placeholder="Target Closure Date" value="<?php if( isset($listing['target_closure_date']) &&  !empty($listing['target_closure_date']) ){ echo $listing['target_closure_date']; } ?>" required/>
																		</div>

																		<div class="form-group">
																			<label for="expected_closure_date">Expected Closure Date <span class="text-danger">*</span></label>
																			<input type="text" id="expected_closure_date" class="form-control" name="expected_closure_date" placeholder="Expected Closure Date" value="<?php if( isset($listing['expected_closure_date']) &&  !empty($listing['expected_closure_date']) ){ echo $listing['expected_closure_date']; } ?>" required/>
																		</div>

																		<div class="form-group">
																			<label>Listing Status <span class="text-danger">*</span></label>
																			<div class="radio-inline">
													                            <label class="radio">
													                                <input type="radio" name="listing_status" value="open" <?php if( $listing['listing_status'] == 'open'){ echo 'checked'; }?> required>
													                                <span></span>
													                                Open
													                            </label>
													                            <label class="radio">
													                                <input type="radio" name="listing_status" value="hold" <?php if( $listing['listing_status'] == 'hold'){ echo 'checked'; }?>>
													                                <span></span>
													                                Hold
													                            </label>
													                            <label class="radio">
													                                <input type="radio" name="listing_status" value="closed" <?php if( $listing['listing_status'] == 'closed'){ echo 'checked'; }?>>
													                                <span></span>
													                                Closed
													                            </label>
													                            <label class="radio">
													                                <input type="radio" name="listing_status" value="suspended" <?php if( $listing['listing_status'] == 'suspended'){ echo 'checked'; }?>>
													                                <span></span>
													                                Suspended
													                            </label>
													                        </div>
																		</div>
																		<?php if( $listing['listing_status'] == 'closed' || $listing['listing_status'] == 'suspended'){ $listing_closing_reason_class = 'd-block'; }else{ $listing_closing_reason_class = 'd-none'; }?>
																		<div id="listing_closing_reason_div" class="form-group <?php echo $listing_closing_reason_class; ?>">
																			<label for="listing_closing_reason">Listing Closing Reason <span class="text-danger">*</span></label>
																			<textarea id="listing_closing_reason" class="form-control" name="listing_closing_reason" placeholder="Listing Closing Reason" <?php if( $listing_closing_reason_class == 'd-block'){ echo 'required'; }?> ><?php echo $listing['listing_closing_reason']; ?></textarea>
																		</div>

																		<div class="form-group">
																			<label>Published on <span class="text-danger">*</span></label><br>
																			<span class="text-primary"><?php echo date('l F d, Y h:i a', strtotime($listing['date_time'])); ?></span>
																		</div>

																		<?php 
																		if( $listing['listing_status'] == 'closed' || $listing['listing_status'] == 'suspended'){
																			?>
																			<div class="form-group">
																				<label><?php echo ucfirst($listing['listing_status']); ?> on <span class="text-danger">*</span></label><br>
																				<span class="text-primary"><?php echo date('l F d', strtotime($listing['listing_closure_date'])); ?></span>
																			</div>
																			<?php
																		}
																		?>

																		<div class="form-group">
																			<input type="submit" class="form-control btn btn-primary" name="submit_update_listing_form" value="submit">
																		</div>
																	</form>
																</div>
															</div>
														</ul>
													</div>
												</div>
												<!--end::Header-->
												<!--begin::Body-->
												<div class="card-body pt-2 pb-0">
													<!--begin::Filter Card-->
													<div class="card card-custom card-stretch gutter-b filter-card">
														<!--begin::Filter Card Header-->

														<?php
														#process filteration here
														if( isset($_REQUEST['filter']) && $_REQUEST['filter'] == 'Filter' ){
															$post = $_REQUEST;
															$where_array = array();
															if( isset($post['id']) && !empty($post['id']) ){
																$where_array[] = " c.listing_id = ".$post['id'];
															}
															if( isset($post['gender']) && !empty($post['gender']) ){
																$where_array[] = " c.gender in ('".implode("','", $post['gender'])."')";
															}
															if( isset($post['marital_status']) && !empty($post['marital_status']) ){
																$where_array[] = " c.marital_status in ('".implode("','", $post['marital_status'])."')";
															}
															if( isset($post['present_city']) && !empty($post['present_city']) ){
																$where_array[] = " c.present_city in ('".implode("','", $post['present_city'])."')";
															}
															if( isset($post['skills']) && !empty($post['skills']) ){
																$where_array[] = " c.skills in ('".implode("','", $post['skills'])."')";
															}
															if( isset($post['is_working']) && !empty($post['is_working']) ){
																$where_array[] = " c.is_working in ('".implode("','", $post['is_working'])."')";
															}
															if( isset($post['disposition_id']) && !empty($post['disposition_id']) ){
																$where_array[] = " c.disposition_id in ('".implode("','", $post['disposition_id'])."')";
															}
															if( isset($post['subdisposition_id']) && !empty($post['subdisposition_id']) ){
																$where_array[] = " c.subdisposition_id in ('".implode("','", $post['subdisposition_id'])."')";
															}
															if( isset($post['source']) && !empty($post['source']) ){
																$where_array[] = " c.source in ('".implode("','", $post['source'])."')";
															}
															if( isset($post['ug_degree']) && !empty($post['ug_degree']) ){
																$where_array[] = " c.ug_degree in ('".implode("','", $post['ug_degree'])."')";
															}
															if( isset($post['notice_period']) && !empty($post['notice_period']) ){
																$where_array[] = " c.notice_period in ('".implode("','", $post['notice_period'])."')";
															}
															if( isset($post['industry']) && !empty($post['industry']) ){
																$where_array[] = " c.industry in ('".implode("','", $post['industry'])."')";
															}
															if( isset($post['role']) && !empty($post['role']) ){
																$where_array[] = " c.role in ('".implode("','", $post['role'])."')";
															}
															if( isset($post['functional_area']) && !empty($post['functional_area']) ){
																$where_array[] = " c.functional_area in ('".implode("','", $post['functional_area'])."')";
															}
															if( isset($post['current_designation']) && !empty($post['current_designation']) ){
																$where_array[] = " c.current_designation in ('".implode("','", $post['current_designation'])."')";
															}
															if( isset($post['min_salary']) && !empty($post['min_salary']) and isset($post['max_salary']) && !empty($post['max_salary']) ){
																$where_array[] = " (c.annual_salary between ".$post['min_salary']." and ".$post['max_salary'].")";
															}
															if( isset($post['min_exp']) && !empty($post['min_exp']) and isset($post['max_exp']) && !empty($post['max_exp']) ){
																$where_array[] = " (c.total_experience_year between ".$post['min_exp']." and ".$post['max_exp'].")";
															}
															if( isset($post['min_relevent_experience']) && !empty($post['min_relevent_experience']) and isset($post['max_relevent_experience']) && !empty($post['max_relevent_experience']) ){
																$where_array[] = " (c.relevent_experience_year between ".$post['min_relevent_experience']." and ".$post['max_relevent_experience'].")";
															}
															if( isset($post['search']) && !empty($post['search']) ){
																$search_term = $post['search'];
																$where_array[] = " (c.first_name like '%".$search_term."%' or c.last_name like '%".$search_term."%' or c.email like '%".$search_term."%' or c.alternate_email like '%".$search_term."%' or c.mobile like '%".$search_term."%' or c.alternate_mobile like '%".$search_term."%' or c.skills like '%".$search_term."%')";
															}

															if( !empty($where_array) ){
																$where = " where ".implode(' and ', $where_array);
															}
														}else{
															$where = " where c.listing_id = ".$id;
														}

														$disposed_sql = "select ( select count(c.id) from candidates c where c.listing_id = ".$id.") as total_count, 
														(select count(c.id) from candidates c left join dispositions d on d.id = c.disposition_id where c.listing_id = ".$id." and c.disposition_id = 1 ) as untouched_count, 
														(select count(c.id) from candidates c left join dispositions d on d.id = c.disposition_id where c.listing_id = ".$id." and d.disposition_name not in ( 'Blank', 'Non Connect' ) ) as contacted_count, 
														(select count(c.id) from candidates c left join dispositions d on d.id = c.disposition_id where c.listing_id = ".$id." and d.disposition_name in ( 'Interviewed' ) ) as interviewed_count, 
														(select count(c.id) from candidates c left join dispositions d on d.id = c.disposition_id where c.listing_id = ".$id." and d.disposition_name in ( 'Shortlisted' ) ) as shortlisted_count, 
														(select count(c.id) from candidates c left join dispositions d on d.id = c.disposition_id where c.listing_id = ".$id." and d.disposition_name in ( 'Selected' ) ) as selected_count, 
														(select count(c.id) from candidates c left join dispositions d on d.id = c.disposition_id left join subdispositions sd on sd.id = c.subdisposition_id where c.listing_id = ".$id." and d.disposition_name in ( 'Interviewed', 'Shortlisted' ) and sd.subdisposition_name in ( 'On Hold from HOD', 'On Hold from HR', 'On Hold' ) ) as hold_count, 
														(select count(c.id) from candidates c left join dispositions d on d.id = c.disposition_id where c.listing_id = ".$id." and d.disposition_name in ( 'Rejected' ) ) as rejected_count";
														$disposed_query = mysqli_query($conn, $disposed_sql);
														if(mysqli_num_rows($disposed_query) > 0){
															$tab_data = mysqli_fetch_assoc($disposed_query);
														}
														$total_count = $tab_data['total_count'];
														$untouched_count = $tab_data['untouched_count'];
														$contacted_count = $tab_data['contacted_count'];
														$interviewed_count = $tab_data['interviewed_count'];
														$shortlisted_count = $tab_data['shortlisted_count'];
														$selected_count = $tab_data['selected_count'];
														$hold_count = $tab_data['hold_count'];
														$rejected_count = $tab_data['rejected_count'];
														?>
														<div class="card-header border-0 pt-5">
															<h3 class="card-title align-items-start flex-column">
																<span class="card-label font-weight-bolder text-dark">Filters</span>
																<span class="text-muted mt-3 font-weight-bold font-size-sm">Total <?php echo $total_count; ?> candidates are associated with this job listing.</span>
															</h3>
															<div class="card-toolbar">
																<ul class="nav nav-pills nav-pills-sm nav-dark-75 filter-counts">
																	
																	<li class="nav-item">
																		<a class="btn btn-outline-secondary py-2 px-4" href="#">Total<br><strong class="badge badge-secondary"><?php echo $total_count; ?></strong></a>
																	</li>
																	<li class="nav-item">
																		<a class="btn btn-outline-secondary py-2 px-4" href="#">Untouched<br><strong class="badge badge-secondary"><?php echo $untouched_count; ?></strong></a>
																	</li>
																	<li class="nav-item">
																		<a class="btn btn-outline-info py-2 px-4" href="#">Contacted<br><strong class="badge badge-info"><?php echo $contacted_count; ?></strong></a>
																	</li>
																	<li class="nav-item">
																		<a class="btn btn-outline-primary py-2 px-4" href="#">Interviewed<br><strong class="badge badge-primary"><?php echo $interviewed_count; ?></strong></a>
																	</li>
																	<li class="nav-item">
																		<a class="btn btn-outline-success py-2 px-4" href="#">Shortlisted<br><strong class="badge badge-success"><?php echo $shortlisted_count; ?></strong></a>
																	</li>
																	<li class="nav-item">
																		<a class="btn btn-outline-success py-2 px-4" href="#">Selected<br><strong class="badge badge-success"><?php echo $selected_count; ?></strong></a>
																	</li>
																	<li class="nav-item">
																		<a class="btn btn-outline-warning py-2 px-4" href="#">Hold<br><strong class="badge badge-warning"><?php echo $hold_count; ?></strong></a>
																	</li>
																	<li class="nav-item">
																		<a class="btn btn-outline-danger py-2 px-4" href="#">Rejected<br><strong class="badge badge-danger"><?php echo $rejected_count; ?></strong></a>
																	</li>
																</ul>
															</div>
														</div>
														<!--end::Filter Card Header-->
														<!--begin::Filter Card Body-->
														<div class="card-body pt-2 pb-0">


															



															<form id="filter_form">
																<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" >
																<div class="row">
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="gender">Gender <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="gender" name="gender[]" data-placeholder="Gender">
																				<option></option>
																				<?php
																				$get_gender = mysqli_query($conn, "select distinct gender from candidates order by gender asc") or die("unable to fetch gender from database ".mysqli_error($conn));
																				$gender_array = array('Male', 'Female', 'Other');
																				if( mysqli_num_rows($get_gender) > 0 ){
																					while( $gender_row = mysqli_fetch_assoc($get_gender) ){
																						?>
																						<option value="<?php echo $gender_row['gender']; ?>" <?php if( isset($_REQUEST['gender']) && in_array($gender_row['gender'], $_REQUEST['gender']) ){ echo 'selected'; } ?> ><?php echo $gender_row['gender']; ?></option>
																						<?php
																					}
																				}
																				
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="marital_status">Marital Status <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="marital_status" name="marital_status[]" data-placeholder="Marital Status">
																				<option></option>
																				<?php
																				$get_marital_status = mysqli_query($conn, "select distinct marital_status from candidates order by marital_status asc") or die("unable to fetch marital_status from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_marital_status) > 0 ){
																					while( $marital_status_row = mysqli_fetch_assoc($get_marital_status) ){
																						?>
																						<option value="<?php echo $marital_status_row['marital_status']; ?>" <?php if( isset($_REQUEST['marital_status']) && in_array($marital_status_row['marital_status'], $_REQUEST['marital_status']) ){ echo 'selected'; } ?> ><?php echo $marital_status_row['marital_status']; ?></option>
																						<?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="present_city">Present City <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="present_city" name="present_city[]" data-placeholder="Present City">
																				<?php
																				$get_present_city = mysqli_query($conn, "select distinct present_city from candidates where listing_id = ".$id) or die("unable to fetch present_city from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_present_city) > 0 ){
																					while( $present_cities = mysqli_fetch_assoc($get_present_city) ){
																						?><option value="<?php echo $present_cities['present_city']; ?>" <?php if( isset($_REQUEST['present_city']) && in_array($present_cities['present_city'], $_REQUEST['present_city']) ){ echo 'selected'; } ?> ><?php echo $present_cities['present_city']; ?></option><?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="skills">Skills <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="skills" name="skills[]" data-placeholder="Skills">
																				<?php
																				$get_skills = mysqli_query($conn, "select distinct skills from candidates where listing_id = ".$id) or die("unable to fetch skills from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_skills) > 0 ){
																					$skills = array();
																					while( $skills_row = mysqli_fetch_assoc($get_skills) ){
																						$skills[] = $skills_row['skills'];
																					}
																					$skills_imploded = implode(',', $skills);
																					$skills_exploded = explode(',', $skills_imploded);
																					$final_skills_unique = array_unique($skills_exploded);
																					foreach( $final_skills_unique as $unique_skill ){
																						?>
																						<option value="<?php echo $unique_skill; ?>" <?php if( isset($_REQUEST['skills']) && in_array($unique_skill, $_REQUEST['skills']) ){ echo 'selected'; } ?> >
																							<?php echo trim($unique_skill); ?>
																						</option>
																						<?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="is_working">Working <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="is_working" name="is_working[]" data-placeholder="Working">
																				<option value="yes" <?php if( isset($_REQUEST['is_working']) && in_array('yes', $_REQUEST['is_working']) ){ echo 'selected'; } ?> >Yes</option>
																				<option value="no" <?php if( isset($_REQUEST['is_working']) && in_array('no', $_REQUEST['is_working']) ){ echo 'selected'; } ?> >No</option>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label>Annual Salary <span class="text-danger">*</span></label>
																			<?php
																			$get_min_max_salary = mysqli_query($conn, "select min(annual_salary+0) as min_salary, max(annual_salary+0) as max_salary from candidates where listing_id = ".$id) or die("unable to fetch salary from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_min_max_salary) > 0 ){
																					while( $row = mysqli_fetch_assoc($get_min_max_salary) ){
																						$min_salary = $row['min_salary'];
																						$max_salary = $row['max_salary'];
																					}
																				}
																			?>
																			<input type="hidden" name="min_salary" id="min_salary" value="<?php if( isset($_REQUEST['min_salary']) ){ echo $_REQUEST['min_salary']; }else{ echo $min_salary; } ?>" >
																			<input type="hidden" name="max_salary" id="max_salary" value="<?php if( isset($_REQUEST['max_salary']) ){ echo $_REQUEST['max_salary']; }else{ echo $max_salary; } ?>" >
																			<div id="annual_salary" class="slider" style="margin-top: 35px;"></div>	
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label>Total Experience <span class="text-danger">*</span></label>
																			<?php
																			$get_min_max_experience = mysqli_query($conn, "select min(total_experience_year+0) as min_exp, max(total_experience_year+0) as max_exp from candidates where listing_id = ".$id) or die("unable to fetch experience from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_min_max_experience) > 0 ){
																					while( $row = mysqli_fetch_assoc($get_min_max_experience) ){
																						$min_exp = $row['min_exp'];
																						$max_exp = $row['max_exp'];
																					}
																				}
																			?>
																			<input type="hidden" name="min_exp" id="min_exp" value="<?php if( isset($_REQUEST['min_exp']) ){ echo $_REQUEST['min_exp']; }else{ echo $min_exp; } ?>" >
																			<input type="hidden" name="max_exp" id="max_exp" value="<?php if( isset($_REQUEST['max_exp']) ){ echo $_REQUEST['max_exp']; }else{ echo $max_exp; } ?>" >
																			<div id="total_experience" class="slider" style="margin-top: 35px;"></div>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label>Relevent Experience <span class="text-danger">*</span></label>
																			<?php
																			$get_min_max_relevent_experience = mysqli_query($conn, "select min(relevent_experience_year+0) as min_relevent_experience, max(relevent_experience_year+0) as max_relevent_experience from candidates where listing_id = ".$id) or die("unable to fetch relevent_experience from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_min_max_relevent_experience) > 0 ){
																					while( $row = mysqli_fetch_assoc($get_min_max_relevent_experience) ){
																						$min_relevent_experience = $row['min_relevent_experience'];
																						$max_relevent_experience = $row['max_relevent_experience'];
																					}
																				}
																			?>
																			<input type="hidden" name="min_relevent_experience" id="min_relevent_experience" value="<?php if( isset($_REQUEST['min_relevent_experience']) ){ echo $_REQUEST['min_relevent_experience']; }else{ echo $min_relevent_experience; } ?>" >
																			<input type="hidden" name="max_relevent_experience" id="max_relevent_experience" value="<?php if( isset($_REQUEST['max_relevent_experience']) ){ echo $_REQUEST['max_relevent_experience']; }else{ echo $max_relevent_experience; } ?>" >
																			<div id="relevent_experience" class="slider" style="margin-top: 35px;"></div>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="disposition_id">Disposition <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="disposition_id" name="disposition_id[]" data-placeholder="Disposition">
																				<?php
																				$get_dispositions = mysqli_query($conn, "select * from dispositions") or die("unable to fetch dispositions from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_dispositions) > 0 ){
																					while( $disposition_row = mysqli_fetch_assoc($get_dispositions) ){
																						?>
																						<option value="<?php echo $disposition_row['id']; ?>" <?php if( isset($_REQUEST['disposition_id']) && in_array($disposition_row['id'], $_REQUEST['disposition_id']) ){ echo 'selected'; } ?> >
																							<?php echo $disposition_row['disposition_name']; ?>
																						</option>
																						<?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="subdisposition_id">Sub Disposition <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="subdisposition_id" name="subdisposition_id[]" data-placeholder="Sub Disposition">
																				<?php
																				if( isset($_REQUEST['disposition_id']) && !empty($_REQUEST['disposition_id']) ){
																					$disposition_id = implode("','", $_REQUEST['disposition_id']);
																					$get_subdispositions = mysqli_query($conn, "select * from subdispositions where disposition_id in ('".$disposition_id."')") or die("unable to fetch subdispositions from database ".mysqli_error($conn));
																					if( mysqli_num_rows($get_subdispositions) > 0 ){
																						while( $subdisposition_row = mysqli_fetch_assoc($get_subdispositions) ){
																							?>
																							<option value="<?php echo $subdisposition_row['id']; ?>" <?php if( isset($_REQUEST['subdisposition_id']) && in_array($subdisposition_row['id'], $_REQUEST['subdisposition_id']) ){ echo 'selected'; } ?> >
																								<?php echo $subdisposition_row['subdisposition_name']; ?>
																							</option>
																							<?php
																						}
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="source">Source <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="source" name="source[]" data-placeholder="Source">
																				<?php
																				$get_source = mysqli_query($conn, "select distinct source from candidates where listing_id = ".$id) or die("unable to fetch source from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_source) > 0 ){
																					while( $source_row = mysqli_fetch_assoc($get_source) ){
																						?><option value="<?php echo $source_row['source']; ?>" <?php if( isset($_REQUEST['source']) && in_array($source_row['source'], $_REQUEST['source']) ){ echo 'selected'; } ?> ><?php echo $source_row['source']; ?></option><?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="ug_degree">Ug Degree <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="ug_degree" name="ug_degree[]" data-placeholder="Ug Degree">
																				<?php
																				$get_ug_degree = mysqli_query($conn, "select distinct ug_degree from candidates where listing_id = ".$id) or die("unable to fetch ug_degree from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_ug_degree) > 0 ){
																					while( $ug_degree_row = mysqli_fetch_assoc($get_ug_degree) ){
																						?><option value="<?php echo $ug_degree_row['ug_degree']; ?>" <?php if( isset($_REQUEST['ug_degree']) && in_array($ug_degree_row['ug_degree'], $_REQUEST['ug_degree']) ){ echo 'selected'; } ?> ><?php echo $ug_degree_row['ug_degree']; ?></option><?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="notice_period">Notice Period <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="notice_period" name="notice_period[]" data-placeholder="Notice Period">
																				<?php
																				$get_notice_period = mysqli_query($conn, "select distinct notice_period from candidates where listing_id = ".$id) or die("unable to fetch notice_period from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_notice_period) > 0 ){
																					while( $notice_period_row = mysqli_fetch_assoc($get_notice_period) ){
																						?><option value="<?php echo $notice_period_row['notice_period']; ?>" <?php if( isset($_REQUEST['notice_period']) && in_array($notice_period_row['notice_period'], $_REQUEST['notice_period']) ){ echo 'selected'; } ?> ><?php echo $notice_period_row['notice_period']; ?></option><?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="industry">Industry <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="industry" name="industry[]" data-placeholder="Industry">
																				<?php
																				$get_industry = mysqli_query($conn, "select distinct industry from candidates where listing_id = ".$id) or die("unable to fetch industry from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_industry) > 0 ){
																					while( $industry_row = mysqli_fetch_assoc($get_industry) ){
																						?><option value="<?php echo $industry_row['industry']; ?>" <?php if( isset($_REQUEST['industry']) && in_array($industry_row['industry'], $_REQUEST['industry']) ){ echo 'selected'; } ?> ><?php echo $industry_row['industry']; ?></option><?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="role">Role <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="role" name="role[]" data-placeholder="Role">
																				<?php
																				$get_role = mysqli_query($conn, "select distinct role from candidates where listing_id = ".$id) or die("unable to fetch role from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_role) > 0 ){
																					while( $role_row = mysqli_fetch_assoc($get_role) ){
																						?><option value="<?php echo $role_row['role']; ?>" <?php if( isset($_REQUEST['role']) && in_array($role_row['role'], $_REQUEST['role']) ){ echo 'selected'; } ?> ><?php echo $role_row['role']; ?></option><?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="functional_area">Functional Area <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="functional_area" name="functional_area[]" data-placeholder="Functional Area">
																				<?php
																				$get_functional_area = mysqli_query($conn, "select distinct functional_area from candidates where listing_id = ".$id) or die("unable to fetch functional_area from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_functional_area) > 0 ){
																					while( $functional_area_row = mysqli_fetch_assoc($get_functional_area) ){
																						?><option value="<?php echo $functional_area_row['functional_area']; ?>" <?php if( isset($_REQUEST['functional_area']) && in_array($functional_area_row['functional_area'], $_REQUEST['functional_area']) ){ echo 'selected'; } ?> ><?php echo $functional_area_row['functional_area']; ?></option><?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="current_designation">Current Designation <span class="text-danger">*</span></label>
																			<select class="form-control select2" multiple id="current_designation" name="current_designation[]" data-placeholder="Current designation">
																				<?php
																				$get_current_designation = mysqli_query($conn, "select distinct current_designation from candidates where listing_id = ".$id) or die("unable to fetch current_designation from database ".mysqli_error($conn));
																				if( mysqli_num_rows($get_current_designation) > 0 ){
																					while( $current_designation_row = mysqli_fetch_assoc($get_current_designation) ){
																						?><option value="<?php echo $current_designation_row['current_designation']; ?>" <?php if( isset($_REQUEST['current_designation']) && in_array($current_designation_row['current_designation'], $_REQUEST['current_designation']) ){ echo 'selected'; } ?> ><?php echo $current_designation_row['current_designation']; ?></option><?php
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="search">Search <span class="text-danger">*</span></label>
																			<input type="text" class="form-control" name="search" id="search" placeholder="Search..." value="<?php if( isset($_REQUEST['search']) ){ echo $_REQUEST['search']; } ?>">
																		</div>
																	</div>

																	<div class="col-md-2">
																		<div class="form-group">
																			<label>&nbsp;</label>
																			<button class="form-control btn btn-secondary" id="filter_reset">Reset</button>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label>&nbsp;</label>
																			<input type="submit" class="form-control btn btn-primary" name="filter" id="filter" value="Filter">
																		</div>
																	</div>
																</div>
															</form>
														</div>
														<!--end::Filter Card Body-->
													</div>
													<!--end::Filter Card-->

													<div class="d-flex align-items-center justify-content-end mb-3">
														<a class="btn btn-sm btn-primary" id="add_candidate" href="<?php echo SITE_URL.'/add-candidate.php?listing_id='.$id; ?>" target="_blank">Add Candidate <i class="fa fa-plus ml-2" style="font-size: 0.9rem; "></i></a>
													</div>

													<?php													
													$get_candidates_sql = "select c.*, d.disposition_name as disposition_name, sd.subdisposition_name as subdisposition_name, concat(u.first_name, ' ', u.last_name) as agent_name from candidates c left join dispositions d on d.id = c.disposition_id left join subdispositions sd on sd.id = c.subdisposition_id left join users u on u.id = c.agent_id ".$where;
													$get_candidates_query = mysqli_query($conn, $get_candidates_sql) or die("unable to fetch candidates from database ".mysqli_error($conn));
													if( mysqli_num_rows( $get_candidates_query ) > 0 ){
														?>
														<table id="candidates_table" class="table nowrap text-center" >
															<thead>
																<tr>
																	<th>
																		<div class="checkbox checkbox-primary">
																			<input id="select_all" type="checkbox" selected_rows="none">
																			<label id="select_all_label" for="select_all" style="width: 44px; margin-bottom: 0px;">
																				ALL
																			</label>
																		</div>
																	</th>
																	<th>ID</th>
																	<th>Name</th>
																	<th>Disposition</th>
																	<th>Sub Disposition</th>
																	<th>Mobile</th>
																	<th>OBC / IBC</th>
																	<th>Email</th>
																	<th>R.Headline</th>
																	<th>TTL. Exp.</th>
																	<th>NP</th>
																	<th>Annual Salary</th>
																	<th>Cr. Desig.</th>
																	<th>Func. Area.</th>
																	<th>Role</th>
																	<th>Industry</th>
																	<th>Prs. City</th>
																	<th>Working</th>
																	<th>Cr. Comp</th>
																	<th>Prm City</th>
																	<th>Prf Loc</th>
																	<th>Skills</th>
																	<th>Resume</th>
																	<th>UG</th>
																	<th>Gender</th>
																	<th>Marital St</th>
																	<th>DOB</th>
																	<th>Agent</th>
																	<th>Source</th>
																	<th>Date Time</th>
																</tr>
															</thead>
															<tfoot>
																<tr>
																	<th></th>
																	<th>ID</th>
																	<th>Name</th>
																	<th>Disposition</th>
																	<th>Sub Disposition</th>
																	<th>Mobile</th>
																	<th>OBC / IBC</th>
																	<th>Email</th>
																	<th>R.Headline</th>
																	<th>TTL. Exp.</th>
																	<th>NP</th>
																	<th>Annual Salary</th>
																	<th>Cr. Desig.</th>
																	<th>Func. Area.</th>
																	<th>Role</th>
																	<th>Industry</th>
																	<th>Prs. City</th>
																	<th>Working</th>
																	<th>Cr. Comp</th>
																	<th>Prm City</th>
																	<th>Prf Loc</th>
																	<th>Skills</th>
																	<th>Resume</th>
																	<th>UG</th>
																	<th>Gender</th>
																	<th>Marital St</th>
																	<th>DOB</th>
																	<th>Agent</th>
																	<th>Source</th>
																	<th>Date Time</th>
																</tr>
															</tfoot>
															<tbody>
																<?php
																while( $candidate = mysqli_fetch_assoc($get_candidates_query) ){
																	?>
																	<tr id="<?php echo $candidate['id']; ?>" >
																		<td data-row_id="<?php echo $candidate['id']; ?>"></td>
																		<td>
																			<a href="<?php echo SITE_URL.'/candidate.php?id='.$candidate['id']; ?>" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg" target="_blank">
																				<?php echo $candidate['id']; ?>
																			</a>
																		</td>
																		<td>
																			<a href="<?php echo SITE_URL.'/candidate.php?id='.$candidate['id']; ?>" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg" target="_blank">
																				<?php echo trim( $candidate['first_name'].' '.$candidate['last_name'] ); ?>
																			</a>
																		</td>
																		<td><?php echo $candidate['disposition_name']; ?></td>
																		<td><?php echo $candidate['subdisposition_name']; ?></td>
																		<td class="candidate_mobile">
																			<?php 
																			if( !empty($candidate['mobile']) ) { 
																				echo $candidate['mobile']; 
																			}else{ 
																				echo $candidate['alternate_mobile']; 
																			} 
																			?>
																		</td>
																		<td class="obc_ibc">
																			<span class="obc_ibc_span" id="obc_ibc_<?php echo $candidate['id']; ?>" data-mob = "<?php echo $candidate['mobile']; ?>"></span>
																			<?php //echo "OBC / IBC Here"; ?>
																		</td>
																		<td>
																			<?php 
																			if( !empty( $candidate['email'] ) ){
																				$emails = explode(',', $candidate['email']);
																				foreach( $emails as $email ){
																					?>
																					<span class="badge badge-primary">
																						<?php echo $email; ?>
																					</span>
																					<?php
																				}
																			}else{
																				?>
																				<span class="badge badge-primary">
																					<?php echo $email; ?>
																				</span>
																				<?php
																			}
																			?>
																		</td>
																		<td><?php echo $candidate['resume_headline']; ?></td>
																		<td><?php echo $candidate['total_experience_year'].' Year '.$candidate['total_experience_year'].' Month'; ?></td>
																		<td><?php echo $candidate['notice_period']; ?></td>
																		<td><?php echo $candidate['annual_salary']; ?></td>
																		<td><?php echo $candidate['current_designation']; ?></td>
																		<td><?php echo $candidate['functional_area']; ?></td>
																		<td><?php echo $candidate['role']; ?></td>
																		<td><?php echo $candidate['industry']; ?></td>
																		<td><?php echo $candidate['present_city']; ?></td>
																		<td><?php echo $candidate['is_working']; ?></td>
																		<td><?php echo $candidate['current_company']; ?></td>
																		<td><?php echo $candidate['permanent_city']; ?></td>
																		<td><?php echo $candidate['preferred_location']; ?></td>
																		<td>
																			<?php
																			if( !empty( $candidate['skills'] ) ){
																				$skills = explode(',', $candidate['skills']);
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
																		<td>
																			<?php 
																			if( !empty($candidate['resume']) ){ 
																				?>
																				<a 
																				class="resume-name label label-sm label-light-primary label-inline" 
																				href="<?php echo !empty($candidate['resume']) ? SITE_URL.$candidate['resume'] : '#'; ?>" 
																				target="_blank">
																					<?php echo basename($candidate['resume']); ?>
																				</a>
																				<?php 
																			}
																			?>
																		</td>
																		<td><?php echo $candidate['ug_degree']."<br>".$candidate['ug_specialization']; ?></td>
																		<td><?php echo $candidate['gender']; ?></td>
																		<td><?php echo $candidate['marital_status']; ?></td>
																		<td><?php echo $candidate['date_of_birth']; ?></td>
																		<td><?php echo trim($candidate['agent_name']); ?></td>
																		<td><?php echo $candidate['source']; ?></td>
																		<td><?php echo $candidate['date_time']; ?></td>																		
																	</tr>
																	<?php
																}
																?>
															</tbody>
														</table>
														<?php
													}else{
														?>
														<div class="card card-custom gutter-b">
															<div class="card-body pt-2 pb-0">
																<h3 class="text-center text-primary p-5 m-5">No Candidates</h3>
															</div>
														</div>
														<?php
													}
													?>
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
														<span class="card-label font-weight-bolder text-dark">Listing Not Found With <?php echo $id; ?></span>
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
	<script src="http://crm.healthgenie.in/plugins/datatables/dataTables.select.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var selected = [];
			var table = $("#candidates_table").DataTable({
				"paging": false,
				"pageLength": -1,
				"scrollY": '47vh',
				"scrollX": true,
				// "fixedColumns": { "leftColumns": 3 },
				"dom": '<"d-flex align-items-center justify-content-between"l<"ml-2"B><"flex-grow-1"f>>rtip',
				"select": { "style": 'multi', "selector": 'td:first-child' },
				// "select": { style: 'os', selector: 'td:first-child' },
				"columnDefs": [ 
								{ "orderable": false, "className": 'select-checkbox', "targets": 0 },
							  ],
			});

			// $('table#candidates_table thead th:first-child').removeClass("sorting_asc").addClass("sorting_disabled");
			$('<span class="btn btn-secondary"><select class="form-control dropdown_actions select2" id="dropdown_actions" ><option value="">Actions</option><option value="edit">Edit</option><option value="delete">Delete</option></select></span>').prependTo('#candidates_table_wrapper .dt-buttons');
			table.on( 'select deselect', function ( e, dt, type, indexes ) {
				var selected_rows =  [];
				$('#candidates_table tbody tr.selected').each( function () {
					selected_rows.push(this.id);
				});
				selected = selected_rows;
				console.log(selected);
			});	

			$("#select_all").change(function() {
                if(this.checked) {
					$(this).attr("selected_rows", "all");
					selected = [];
					table.rows().select();
					$("#select_all_label").html("None");
					$(".DTFC_LeftHeadWrapper table thead tr th #select_all_label").html("None");
					$(".DTFC_LeftHeadWrapper table thead tr th #select_all").prop( "checked", true );
				}else{
					$(this).attr("selected_rows", "none");
					selected = [];
					table.rows().deselect();						
					$("#select_all_label").html("All");
					$(".DTFC_LeftHeadWrapper table thead tr th #select_all_label").html("All");
					$(".DTFC_LeftHeadWrapper table thead tr th #select_all").prop( "checked", false );
				}
            });

            //Action trigger after selecting rows
			$("#dropdown_actions").change( function () {
				var dropdown_actions_val = $(this).val();
				var selected_ids = "None";
				if(selected.length > 0){
					selected_ids = selected;
				}
				if(dropdown_actions_val === 'delete'){
					$(this).val('').trigger('change');
					if(selected_ids !== "None"){
						Swal.fire({
							title: "Are you sure?",
							html: "<p>You will not be able to recover selected items!</p><p>"+selected_ids+"</p>",
							type: "warning",
							showCancelButton: true,
							cancelButtonClass: 'btn-secondary waves-effect',
							confirmButtonClass: "btn-warning waves-effect",
							confirmButtonText: "Yes, delete them!",
							cancelButtonText: "No, cancel pls!",
							closeOnConfirm: false,
							closeOnCancel: false
						}).then((result) => {
							if (result.value){
								$.ajax({
									url: '<?php echo SITE_URL; ?>/controller/ajax.php',
									type: 'POST',
									data:  {selected_ids : selected_ids, ajax_for: 'delete_multiple'}
								})
								.done(function(returned_data){
									var obj=JSON.parse(returned_data);
									if(obj.response == "success"){
										$.each(selected_ids, function (index, value) {
											$("#candidates_table tbody tr#"+value).remove();
											$("table.DTFC_Cloned tbody tr.selected").remove();
										});
										Swal.fire(
											'Deleted!',
											'Selected rows '+selected_ids+' have been deleted.',
											'success'
										).then((result) => {
											location.reload();
										});
									}else{
										Swal.fire(
											'Not Deleted!',
											'Selected rows '+selected_ids+' have not been deleted.'+'  ERROR='+obj.description,
											'danger'
										).then((result) => {
											location.reload();
										});
									}
								})
								.fail(function(){
									Swal.fire(
										'Not Deleted!',
										'Something went wrong',
										'warning'
									).then((result) => {
										location.reload();
									});
								});
							}else if( result.dismiss === Swal.DismissReason.cancel ){
								Swal.fire(
									'Cancelled',
									'Selected rows '+selected_ids+' are safe',
									'error'
								)
							}
						});
					}else{
						Swal.fire(
							'Doing What?',
							'Nothing selected !',
							'question'
						)
					}
				}
				if(dropdown_actions_val === 'edit'){
					$("span#bulk_edit_selected_ids").html(selected_ids.join(','));
					$("input#bulk_edit_selected_ids").val(selected_ids.join(','));
					$(this).val(null).trigger('change');
					$("#bulk_edit_offcanvas").addClass('offcanvas-on').after('<div class="offcanvas-overlay" id="bulk_edit_offcanvas_overlay"></div>');
				}
			});
			$(document).on('click', '#bulk_edit_offcanvas_overlay', function(){
				$("#bulk_edit_offcanvas").removeClass('offcanvas-on');
				$(this).remove();
			})

			$(document).on('submit', '#bulk_edit_offcanvas_form', function(e){
				e.preventDefault();
				var selected_ids = $("input#bulk_edit_selected_ids").val();
				if(selected_ids.length){
					var bulk_edit_agent_id = $('#bulk_edit_agent_id').val();
					$.ajax({
						url: '<?php echo SITE_URL; ?>/controller/ajax.php',
						type: 'POST',
						data:  {selected_ids : selected_ids, ajax_for: 'assign_multiple', agent_id: bulk_edit_agent_id}
					})
					.done(function(returned_data){
						/*console.log('returned_data', returned_data);
						return false;*/
						var obj=JSON.parse(returned_data);
						if(obj.response == "success"){
							Swal.fire(
								'Updated!',
								'Selected rows '+selected_ids+' have been updated.',
								'success'
							).then((result) => {
								location.reload();
							});
						}else{
							Swal.fire(
								'Not Updated!',
								'Selected rows '+selected_ids+' have not been updated.'+'  ERROR='+obj.description,
								'danger'
							).then((result) => {
								location.reload();
							});
						}
					})
					.fail(function(){
						Swal.fire(
							'Not Updated!',
							'Something went wrong',
							'warning'
						).then((result) => {
							location.reload();
						});
					});
				}else{
					Swal.fire(
						'Doing What?',
						'Nothing selected !',
						'question'
					)
				}
			})

		})
	</script>

	<div id="bulk_edit_offcanvas" class="offcanvas offcanvas-start p-10">
		<!--begin::Header-->
		<div class="offcanvas-header d-flex align-items-center justify-content-between mb-10">
			<h3 class="font-weight-bold m-0">
				Bulk Assign 
				<!-- <small class="text-muted font-size-sm ml-2">24 New</small> -->
			</h3>
			<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="update_listing_close" data-bs-dismiss="offcanvas">
				<i class="ki ki-close icon-xs text-muted"></i>
			</a>
		</div>
		<!--end::Header-->
		<!--begin::Content-->
		<div class="offcanvas-content pr-5 mr-n5" id="update_listing_form_wrapper">
			<form id="bulk_edit_offcanvas_form">
				<div class="form-group">
					<h4>Selected ID's <span class="text-secondary font-size-sm ml-2" id="bulk_edit_selected_ids"></span></h4>
				</div>
				<div class="form-group">
					<input type="hidden" id="bulk_edit_selected_ids" >
					<label for="agent_id_bulk">Agent</label>
					<select class="form-control select2" id="bulk_edit_agent_id" data-placeholder="Agent">
						<option></option>
						<?php
						$get_agents = mysqli_query($conn, "select * from users") or die("unable to fetch users from database ".mysqli_error($conn));
						if( mysqli_num_rows($get_agents) > 0 ){
							while( $agent_row = mysqli_fetch_assoc($get_agents) ){
								?>
								<option value="<?php echo $agent_row['id']; ?>" <?php if( $agent_row['id'] == $_SESSION['CURRENT_USER']['id']){ echo 'selected'; } ?> ><?php echo trim($agent_row['first_name'].' '.$agent_row['last_name']); ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<input type="submit" id="submit_bulk_edit" class="form-control btn btn-primary" value="Submit" />
				</div>
			</form>
		</div>
	</div>

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

	<script type="text/javascript">
		$(document).ready(function(){
		    $('#target_closure_date').datepicker({
		    	format: 'yyyy-mm-dd'
		    });
		    $('#expected_closure_date').datepicker({
		    	format: 'yyyy-mm-dd'
		    });
		    $('#listing_closure_date').datepicker({
		    	format: 'yyyy-mm-dd'
		    });
		})
	</script>

	<?php
	if( isset( $_SESSION['UPDATE_LISTING_TITLE'] ) && !empty( $_SESSION['UPDATE_LISTING_TITLE'] ) && isset( $_SESSION['UPDATE_LISTING_MESSAGE'] ) && !empty( $_SESSION['UPDATE_LISTING_MESSAGE'] ) ){
		if( $_SESSION['UPDATE_LISTING_TITLE'] == 'Success'){
			?>
			<script type="text/javascript">
				$(document).ready(function(){
					toastr.success("<?php echo $_SESSION['UPDATE_LISTING_MESSAGE']; ?>", "<?php echo $_SESSION['UPDATE_LISTING_TITLE']; ?>");
				})
			</script>
			<?php
		}elseif( $_SESSION['UPDATE_LISTING_TITLE'] == 'Error'){
			?>
			<script type="text/javascript">
				$(document).ready(function(){
					toastr.error("<?php echo $_SESSION['UPDATE_LISTING_MESSAGE']; ?>", "<?php echo $_SESSION['UPDATE_LISTING_TITLE']; ?>");
				})
			</script>
			<?php
		}
	}
	unset($_SESSION['UPDATE_LISTING_TITLE']);
	unset($_SESSION['UPDATE_LISTING_MESSAGE']);
	?>
	<script type="text/javascript">
		var _tooltip = jQuery.fn.tooltip;
		var _datepicker = jQuery.fn.datepicker;
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript">
		jQuery.fn.tooltip = _tooltip;
		jQuery.fn.datepicker = _datepicker;
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var min_salary = parseInt('<?php 
											if( isset($min_salary) ){ 
												echo $min_salary; 
											}else{ 
												echo '0'; 
											}
										?>');
			var max_salary = parseInt('<?php 
											if( isset($max_salary) ){ 
												echo $max_salary; 
											}else{ 
												echo '0'; 
											}
										?>');
			var min_salary_posted = parseInt('<?php 
												if(isset( $_REQUEST['min_salary']) ){
											 		echo $_REQUEST['min_salary']; 
											 	}elseif( isset($min_salary) ){ 
											 		echo $min_salary; 
											 	}else{
											 		echo '0';
											 	} 
										 	?>');
			var max_salary_posted = parseInt('<?php 
												if(isset( $_REQUEST['max_salary']) ){
											 		echo $_REQUEST['max_salary']; 
											 	}elseif( isset($max_salary) ){ 
											 		echo $max_salary; 
											 	}else{
											 		echo '0';
											 	} 
										 	?>');
			$("#annual_salary").slider({
				step: 100000,
				range: true,
				min: min_salary, 
				max: max_salary, 
				values: [min_salary_posted, max_salary_posted], 
				create: function(event, ui) {
					var $slider = $(this);
				    var $sliderWrapper = $slider.closest('.store-slider-wrapper');
				    var $sliderHandlers = $slider.find('.ui-slider-handle');
				    var $sliderHandlersMin = $sliderHandlers.eq(0);
				    var $sliderHandlersMax = $sliderHandlers.eq(1);
				    $sliderHandlersMin.html('<div class="slidertooltip">'+min_salary_posted+'</div>');
				    $sliderHandlersMax.html('<div class="slidertooltip">'+max_salary_posted+'</div>');
				},
				slide: function(event, ui){
					var $slider = $(this);
					var $sliderHandlers = $slider.find(".ui-slider-handle");
					$sliderHandlers.eq(0).find('.slidertooltip').html(ui.values[0]);
					$sliderHandlers.eq(1).find('.slidertooltip').html(ui.values[1]);
					$('#min_salary').val(ui.values[0]);
					$('#max_salary').val(ui.values[1]);
				}
			});
		});
	</script>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var min_exp = parseInt('<?php 
											if( isset($min_exp) ){ 
												echo $min_exp; 
											}else{ 
												echo '0'; 
											}
										?>');
			var max_exp = parseInt('<?php 
											if( isset($max_exp) ){ 
												echo $max_exp; 
											}else{ 
												echo '0'; 
											}
										?>');
			var min_exp_posted = parseInt('<?php 
												if(isset( $_REQUEST['min_exp']) ){
											 		echo $_REQUEST['min_exp']; 
											 	}elseif( isset($min_exp) ){ 
											 		echo $min_exp; 
											 	}else{
											 		echo '0';
											 	} 
										 	?>');
			var max_exp_posted = parseInt('<?php 
												if(isset( $_REQUEST['max_exp']) ){
											 		echo $_REQUEST['max_exp']; 
											 	}elseif( isset($max_exp) ){ 
											 		echo $max_exp; 
											 	}else{
											 		echo '0';
											 	} 
										 	?>');
			$("#total_experience.slider").slider({
				step: 1,
				range: true,
				min: min_exp, 
				max: max_exp, 
				values: [min_exp_posted, max_exp_posted], 
				create: function(event, ui) {
					var $slider = $(this);
				    var $sliderWrapper = $slider.closest('.store-slider-wrapper');
				    var $sliderHandlers = $slider.find('.ui-slider-handle');
				    var $sliderHandlersMin = $sliderHandlers.eq(0);
				    var $sliderHandlersMax = $sliderHandlers.eq(1);
				    $sliderHandlersMin.html('<div class="slidertooltip">'+min_exp_posted+'</div>');
				    $sliderHandlersMax.html('<div class="slidertooltip">'+max_exp_posted+'</div>');
				},
				slide: function(event, ui){
					var $slider = $(this);
					var $sliderHandlers = $slider.find(".ui-slider-handle");
					$sliderHandlers.eq(0).find('.slidertooltip').html(ui.values[0]);
					$sliderHandlers.eq(1).find('.slidertooltip').html(ui.values[1]);
					$('#min_exp').val(ui.values[0]);
					$('#max_exp').val(ui.values[1]);
				}
			});
		});
	</script>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var min_relevent_experience = parseInt('<?php 
											if( isset($min_relevent_experience) ){ 
												echo $min_relevent_experience; 
											}else{ 
												echo '0'; 
											}
										?>');
			var max_relevent_experience = parseInt('<?php 
											if( isset($max_relevent_experience) ){ 
												echo $max_relevent_experience; 
											}else{ 
												echo '0'; 
											}
										?>');
			var min_relevent_experience_posted = parseInt('<?php 
												if(isset( $_REQUEST['min_relevent_experience']) ){
											 		echo $_REQUEST['min_relevent_experience']; 
											 	}elseif( isset($min_relevent_experience) ){ 
											 		echo $min_relevent_experience; 
											 	}else{
											 		echo '0';
											 	} 
										 	?>');
			var max_relevent_experience_posted = parseInt('<?php 
												if(isset( $_REQUEST['max_relevent_experience']) ){
											 		echo $_REQUEST['max_relevent_experience']; 
											 	}elseif( isset($max_relevent_experience) ){ 
											 		echo $max_relevent_experience; 
											 	}else{
											 		echo '0';
											 	} 
										 	?>');
			$("#relevent_experience.slider").slider({
				step: 1,
				range: true,
				min: min_relevent_experience, 
				max: max_relevent_experience, 
				values: [min_relevent_experience_posted, max_relevent_experience_posted], 
				create: function(event, ui) {
					var $slider = $(this);
				    var $sliderWrapper = $slider.closest('.store-slider-wrapper');
				    var $sliderHandlers = $slider.find('.ui-slider-handle');
				    var $sliderHandlersMin = $sliderHandlers.eq(0);
				    var $sliderHandlersMax = $sliderHandlers.eq(1);
				    $sliderHandlersMin.html('<div class="slidertooltip">'+min_relevent_experience_posted+'</div>');
				    $sliderHandlersMax.html('<div class="slidertooltip">'+max_relevent_experience_posted+'</div>');
				},
				slide: function(event, ui){
					var $slider = $(this);
					var $sliderHandlers = $slider.find(".ui-slider-handle");
					$sliderHandlers.eq(0).find('.slidertooltip').html(ui.values[0]);
					$sliderHandlers.eq(1).find('.slidertooltip').html(ui.values[1]);
					$('#min_relevent_experience').val(ui.values[0]);
					$('#max_relevent_experience').val(ui.values[1]);
				}
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('change', '#disposition_id', function(){
				var target = $('#subdisposition_id');
				var disposition_id = $(this).val();
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

	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('click', '#filter_reset', function(e){
				e.preventDefault();
				var form = $('#filter_form');
				form.find('select').empty().trigger('change');
				form.find('input[type=text]').val('');
				form.find('input#min_salary').val( parseInt('<?php if( isset($min_salary) ){ echo $min_salary; }else{ echo '0'; } ?>') );
				form.find('input#max_salary').val( parseInt('<?php if( isset($max_salary) ){ echo $max_salary; }else{ echo '0'; } ?>') );
				form.find('input#min_exp').val( parseInt('<?php if( isset($min_exp) ){ echo $min_exp; }else{ echo '0'; } ?>') );
				form.find('input#max_exp').val( parseInt('<?php if( isset($max_exp) ){ echo $max_exp; }else{ echo '0'; } ?>') );
				form.find('input#min_relevent_experience').val( parseInt('<?php if( isset($min_relevent_experience) ){ echo $min_relevent_experience; }else{ echo '0'; } ?>') );
				form.find('input#max_relevent_experience').val( parseInt('<?php if( isset($max_relevent_experience) ){ echo $max_relevent_experience; }else{ echo '0'; } ?>') );
			})
		})
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('change', 'input[name=listing_status]', function(e){
				var listing_status = $(this).val();
				if( listing_status == 'closed' || listing_status == 'suspended' ){
					$('#listing_closing_reason_div').removeClass('d-none').addClass('d-block');
					$('#listing_closing_reason').attr('required', true);
				}else{
					$('#listing_closing_reason_div').removeClass('d-block').addClass('d-none');
					$('#listing_closing_reason').removeAttr('required');
				}
			})
		})
	</script>




	<script>
		$(document).ready(function(){
			var loop_one="<?php echo mysqli_num_rows( $get_candidates_query ); ?>";
			
			var loop_two=0;
			
			$("#candidates_table tbody tr").each(function(){
				var id = $(this).attr('id');
				var obc_ibc_column = $(this).find("td.obc_ibc");
				var candidate_mobile_column = $(this).find("td.candidate_mobile");
				// var candidate_mobile = candidate_mobile_column.html().trim();
				var candidate_mobile = candidate_mobile_column.text().split(',')[0].trim();
				
				// console.log('candidate_mobile',candidate_mobile);
				
				var asterisk_outgoing_call_recording_url="//182.71.52.186/hg-ajax-api.php";
				var data = {
					'recording_type'	:  'obc_ibc',
					'view_type'			:	'data_listing',
					'mobile'			:	candidate_mobile,
				};
				$.ajax({
					url: asterisk_outgoing_call_recording_url,
					type: 'POST',
					data:  data,
					dataType: 'html',
				})
				.done(function(returned_data){
					obc_ibc_column.html();
					obc_ibc_column.html(returned_data);
					// console.log(returned_data);
					loop_two++;
					if(loop_one == loop_two){
						$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
						// $("table.DTFC_Cloned thead tr th:nth-child(2)").removeClass('sorting');
					}
				})
				.fail(function(){
					obc_ibc_column.html('<a href="https://182.71.52.186/hg-ajax-api.php" target="_blank">Activate API</a>');
				});
			});
			// setTimeout(function(){ $($.fn.dataTable.tables(true)).DataTable().columns.adjust(); }, 10000);
		})
	</script>			
</body>
<!--end::Body-->
</html>

