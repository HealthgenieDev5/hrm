<!--begin::Aside-->
<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
	<!--begin::Brand-->
	<div class="aside-logo flex-column-auto" id="kt_aside_logo">
		<!--begin::Logo-->
		<a href="<?php echo base_url(); ?>">
			<!-- <img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/logo-1-dark.svg" class="h-25px logo" /> -->
			<img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/logo-healthgenie.png" class="h-50px logo" />
		</a>
		<!--end::Logo-->
		<!--begin::Aside toggler-->
		<div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
			<span class="svg-icon svg-icon-1 rotate-180">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="black" />
					<path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="black" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Aside toggler-->
	</div>
	<!--end::Brand-->
	<!--begin::Aside menu-->
	<div class="aside-menu flex-column-fluid">
		<!--begin::Aside Menu-->
		<div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
			<!--begin::Menu-->
			<div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 h-100" id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">

				<!--begin::DashboardMenu-->
				<!-- <div data-kt-menu-trigger="click" class="menu-item menu-accordion ">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-grid fs-3"></i>
						</span>
						<span class="menu-title">Dashboards</span>
						<span class="menu-arrow"></span>
					</span>
					<div class="menu-sub menu-sub-accordion menu-active-bg">
						<div class="menu-item">
							<a class="menu-link active" href="<?php echo base_url(); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Multipurpose</span>
							</a>
						</div>
					</div>
				</div> -->
				<!--end::DashboardMenu-->

				<div class="menu-item <?php if (isset($current_controller) && $current_controller == 'dashboard') {
											echo 'here show';
										} ?>">
					<a class="menu-link" href="<?php echo base_url('dashboard'); ?>">
						<span class="menu-icon">
							<i class="bi bi-grid fs-3"></i>
						</span>
						<span class="menu-title">Dashboard</span>
					</a>
				</div>

				<div class="menu-item <?php if (isset($current_controller) && $current_controller == 'historical-dashboard') {
											echo 'here show';
										} ?>">
					<a class="menu-link" href="<?php echo base_url('historical-dashboard'); ?>">
						<span class="menu-icon">
							<i class="bi bi-grid fs-3"></i>
						</span>
						<span class="menu-title">Historical Dashboard</span>
					</a>
				</div>

				<div class="menu-item <?php if (isset($current_controller) && $current_controller == 'detailed-dashboard') {
											echo 'here show';
										} ?>">
					<a class="menu-link" href="<?php echo base_url('detailed-dashboard'); ?>">
						<span class="menu-icon">
							<i class="bi bi-grid fs-3"></i>
						</span>
						<span class="menu-title">Detailed Dashboard</span>
					</a>
				</div>

				<div class="menu-item <?php if (isset($current_controller) && $current_controller == 'miss-punch-dashboard') {
											echo 'here show';
										} ?>">
					<a class="menu-link" href="<?php echo base_url('miss-punch-dashboard'); ?>">
						<span class="menu-icon">
							<i class="bi bi-grid fs-3"></i>
						</span>
						<span class="menu-title">Miss Punch Dashboard</span>
					</a>
				</div>

				<div class="menu-item <?php if (isset($current_controller) && $current_controller == 'contacts') {
											echo 'here show';
										} ?>">
					<a class="menu-link" href="<?php echo base_url('contacts'); ?>">
						<span class="menu-icon">
							<i class="fa fa-user fs-3"></i>
						</span>
						<span class="menu-title">Contacts</span>
					</a>
				</div>



				<?php
				$current_user_role = session()->get('current_user')['role'];
				$current_user_employee_id = session()->get('current_user')['employee_id'];
				// if( in_array($current_user_role, ['admin', 'super user'] ) ){
				?>
				<!--begin::MasterMenu-->
				<div class="menu-item">
					<div class="menu-content pt-8 pb-2">
						<span class="menu-section text-muted text-uppercase fs-8 ls-1">Master Panel</span>
					</div>
				</div>
				<div data-kt-menu-trigger="click" class="menu-item <?php if (isset($current_controller) && in_array($current_controller, ['master'])) {
																		echo 'here show';
																	} ?> menu-accordion">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-grid fs-3"></i>
						</span>
						<span class="menu-title">Master</span>
						<span class="menu-arrow"></span>
					</span>
					<div class="menu-sub menu-sub-accordion menu-active-bg">

						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'company') {
													echo 'active';
												} ?>" href="<?php echo base_url('backend/master/company'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Company</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'department') {
													echo 'active';
												} ?>" href="<?php echo base_url('backend/master/department'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Department</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'designation') {
													echo 'active';
												} ?>" href="<?php echo base_url('backend/master/designation'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Designation</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'leave') {
													echo 'active';
												} ?>" href="<?php echo base_url('backend/master/leave'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Leave</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'holiday') {
													echo 'active';
												} ?>" href="<?php echo base_url('backend/master/holiday'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Holiday</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link
								<?php
								if (isset($current_method) && $current_method == 'employee') {
									echo 'active';
								}
								?>" href="<?php echo base_url('backend/master/employee'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Employee</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'shift') {
													echo 'active';
												} ?>" href="<?php echo base_url('backend/master/shift'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Shift</span>
							</a>
						</div>
						<!-- Asked to remove by HR Rahul -->
						<?php
						if (in_array(session()->get('current_user')['role'], ['hr', 'superuser'])) {
						?>
							<div class="menu-item">
								<a class="menu-link
									<?php
									if (isset($current_method) && $current_method == 'salary') {
										echo 'active';
									}
									?>"
									href="<?php echo base_url('backend/master/salary/') . '/id/' . $current_user_employee_id; ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Salary</span>
								</a>
							</div>
						<?php
						}
						?>
						<?php
						if (in_array(session()->get('current_user')['role'], ['hr', 'superuser'])) {
						?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'minimum-wages-category') {
														echo 'active';
													} ?>" href="<?php echo base_url('backend/master/minimum-wages-category'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Minimum Wages</span>
								</a>
							</div>
						<?php
						}
						?>



						<div class="menu-item <?php
												if (isset($current_method) && $current_method == 'appraisals') {
													echo $current_method . 'active';
												}
												?>">
							<a
								class="menu-link"
								href="<?php echo base_url('backend/master/appraisals'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Appraisals</span>
							</a>
						</div>

						<?php //if (in_array(session()->get('current_user')['role'], ['superuser', 'hr']) || in_array(session()->get('current_user')['employee_id'], ['40', '93'])): 
						?>
						<!-- <div class="menu-item">
								<a class="menu-link <?php
													// if (isset($current_controller) && $current_controller == 'notifications') {
													// 						echo 'active';
													// 					} 
													?>"
									href="<?php //echo base_url('backend/notifications'); 
											?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Notifications</span>
								</a>
							</div> -->
						<?php //endif; 
						?>


						<!-- Asked to remove by HR Rahul -->

						<!-- <div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'location') {
														echo 'active';
													} ?>" href="<?php echo base_url('backend/master/location'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Location</span>
								</a>
							</div> -->
					</div>
				</div>
				<!--end::MasterMenu-->
				<?php
				// }else{
				?>



				<!--begin::Developer Access-->
				<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '95', '52'])) { ?>
					<div class="menu-item">
						<div class="menu-content pt-8 pb-2">
							<span class="menu-section text-muted text-uppercase fs-8 ls-1">Developer Panel</span>
						</div>
					</div>
					<div data-kt-menu-trigger="click" class="menu-item <?php if (isset($current_controller) && in_array($current_controller, ['developer-access'])) {
																			echo 'here show';
																		} ?> menu-accordion">
						<span class="menu-link">
							<span class="menu-icon">
								<i class="bi bi-grid fs-3"></i>
							</span>
							<span class="menu-title">Developer Panel</span>
							<span class="menu-arrow"></span>
						</span>
						<div class="menu-sub menu-sub-accordion menu-active-bg">

							<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '95', '52'])) { ?>
								<div class="menu-item">
									<a class="menu-link <?php if (isset($current_method) && $current_method == 'developer-access') {
															echo 'active';
														} ?>" href="<?php echo base_url('developer-access'); ?>">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">Back Date Leave Requests</span>
									</a>
								</div>
							<?php } ?>

						</div>
					</div>
				<?php } ?>
				<!--end::Developer Access-->

				<!--begin::OverrideMenu-->
				<div class="menu-item">
					<div class="menu-content pt-8 pb-2">
						<span class="menu-section text-muted text-uppercase fs-8 ls-1">Override Panel</span>
					</div>
				</div>
				<div data-kt-menu-trigger="click" class="menu-item <?php if (isset($current_controller) && in_array($current_controller, ['overrides', 'hr'])) {
																		echo 'here show';
																	} ?> menu-accordion">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-grid fs-3"></i>
						</span>
						<span class="menu-title">Overrides</span>
						<span class="menu-arrow"></span>
					</span>
					<div class="menu-sub menu-sub-accordion menu-active-bg">

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '95', '52'])) { ?>
							<div class="menu-item">
								<a class="menu-link
								<?php
								if (isset($current_method) && $current_method == 'wave-off-minutes') {
									echo 'active';
								}
								?>" href="<?php echo base_url('backend/hr/wave-off-minutes'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Wave Off Minutes</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '95', '52'])) { ?>
							<div class="menu-item">
								<a class="menu-link
								<?php
								if (isset($current_method) && $current_method == 'deduction-minutes') {
									echo 'active';
								}
								?>" href="<?php echo base_url('backend/hr/deduction-minutes'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Deduction Minutes</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '95', '52'])) { ?>
							<div class="menu-item">
								<a class="menu-link
								<?php
								if (isset($current_method) && $current_method == 'half-day-consideration') {
									echo 'active';
								}
								?>" href="<?php echo base_url('backend/hr/half-day-consideration'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Half Day Adjustment for Work below 03:30</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '95', '52', '93'])) { ?>
							<div class="menu-item">
								<a class="menu-link
								<?php
								if (isset($current_method) && $current_method == 'machine-override') {
									echo 'active';
								}
								?>" href="<?php echo base_url('backend/hr/machine-override'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Machine Override</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '95', '52', '93', '461'])) { ?>
							<div class="menu-item">
								<a class="menu-link
								<?php
								if (isset($current_method) && $current_method == 'shift-override') {
									echo 'active';
								}
								?>" href="<?php echo base_url('backend/hr/shift-override'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Shift Override</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '95', '52', '93'])) { ?>
							<div class="menu-item">
								<a class="menu-link
								<?php
								if (isset($current_method) && $current_method == 'attendance-override') {
									echo 'active';
								}
								?>" href="<?php echo base_url('backend/hr/attendance-override'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Attendance Override</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '93'])) { ?>
							<div class="menu-item">
								<a class="menu-link
									<?php
									if (isset($current_method) && $current_method == 'rh-override') {
										echo 'active';
									}
									?>
									"
									href="<?php echo base_url('backend/hr/rh-override'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">RH Override</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40', '93', '461'])) { ?>
							<div class="menu-item">
								<a class="menu-link
									<?php
									if (isset($current_method) && $current_method == 'manual-punches') {
										echo 'active';
									}
									?>"
									href="<?php echo base_url('backend/hr/manual-punches'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Manual Punch</span>
								</a>
							</div>
						<?php } ?>

					</div>
				</div>
				<!--begin::OverrideMenu-->

				<!--begin::Administrative Menu-->
				<div class="menu-item">
					<div class="menu-content pt-8 pb-2">
						<span class="menu-section text-muted text-uppercase fs-8 ls-1">Administrative</span>
					</div>
				</div>
				<div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php if (isset($current_controller) && $current_controller == 'administrative') {
																						echo 'here show';
																					} ?>">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-archive fs-3"></i>
						</span>
						<span class="menu-title">Administrative</span>
						<?php
						$count = 0;
						if (function_exists('get_pending_leaves_count')) {
							$count = $count + get_pending_leaves_count();
						}
						if (function_exists('get_pending_ods_count')) {
							$count = $count + get_pending_ods_count();
						}
						if (function_exists('get_pending_loans_count')) {
							$count = $count + get_pending_loans_count();
						}
						if (function_exists('get_pending_advance_salary_count')) {
							$count = $count + get_pending_advance_salary_count();
						}
						if (function_exists('get_pending_gate_pass_count')) {
							#$count = $count + get_pending_gate_pass_count();
						}
						if (function_exists('get_pending_comp_off_credit_request_count')) {
							$count = $count + get_pending_comp_off_credit_request_count();
						}

						if ($count > 0) {
						?>
							<span class="menu-badge">
								<span class="badge badge-sm badge-light-danger rounded-pill fs-9 mx-3">
									<?php echo $count; ?>
								</span>
							</span>
						<?php
						}
						?>
						<span class="menu-arrow"></span>
					</span>
					<div class="menu-sub menu-sub-accordion menu-active-bg">
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'leaveapproval') {
													echo 'active';
												} ?>" href="<?php echo (session()->get('current_user')['role'] !== 'hr') ? base_url('/backend/administrative/leaveapproval?status[]=pending&reporting_to_me=yes') : base_url('/backend/administrative/leaveapproval?reporting_to_me=no rule'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Leave Approval</span>
								<?php
								if (function_exists('get_pending_leaves_count')) {
									if (get_pending_leaves_count() > 0) {
								?>
										<span class="menu-badge">
											<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
												<?php echo get_pending_leaves_count(); ?>
											</span>
										</span>
								<?php
									}
								}
								?>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'odapproval') {
													echo 'active';
												} ?>" href="<?php echo (session()->get('current_user')['role'] !== 'hr') ? base_url('/backend/administrative/odapproval?status[]=pending&reporting_to_me=yes') : base_url('/backend/administrative/odapproval?reporting_to_me=no rule'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">OD Approval</span>
								<?php
								if (function_exists('get_pending_ods_count')) {
									if (get_pending_ods_count() > 0) {
								?>
										<span class="menu-badge">
											<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
												<?php echo get_pending_ods_count(); ?>
											</span>
										</span>
								<?php
									}
								}
								?>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'loan-approval') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/administrative/loan-approval'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Loan Approval</span>
								<?php
								if (function_exists('get_pending_loans_count')) {
									if (get_pending_loans_count() > 0) {
								?>
										<span class="menu-badge">
											<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
												<?php echo get_pending_loans_count(); ?>
											</span>
										</span>
								<?php
									}
								}
								?>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'advance-salary-approval') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/administrative/advance-salary-approval'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Advance Salary Approval</span>
								<?php
								if (function_exists('get_pending_advance_salary_count')) {
									if (get_pending_advance_salary_count() > 0) {
								?>
										<span class="menu-badge">
											<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
												<?php echo get_pending_advance_salary_count(); ?>
											</span>
										</span>
								<?php
									}
								}
								?>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'gate-pass-approval') {
													echo 'active';
												} ?>" href="<?php echo (session()->get('current_user')['role'] !== 'hr') ? base_url('/backend/administrative/gate-pass-approval?status[]=pending&reporting_to_me=yes') : base_url('/backend/administrative/gate-pass-approval?reporting_to_me=no rule'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Gate Pass Approval</span>
								<!-- <?php
										if (function_exists('get_pending_gate_pass_count')) {
											if (get_pending_gate_pass_count() > 0) {
										?>
											<span class="menu-badge">
                								<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
													<?php echo get_pending_gate_pass_count(); ?>
												</span>
											</span>
											<?php
											}
										}
											?> -->
							</a>
						</div>
						<?php
						if (in_array(session()->get('current_user')['employee_id'], ['40', '1'])) {



						?>
							<div class="menu-item">
								<a class="menu-link <?php
													if (isset($current_method) && $current_method == 'comp-off-credit-approval-requests') {
														echo 'active';
													} ?>" href="<?php echo (session()->get('current_user')['role'] !== 'hr') ? base_url('/backend/administrative/comp-off-credit-approval-requests') . '?status[]=pending&status[]=stage_1&reporting_to_me=no rule' : base_url('/backend/administrative/comp-off-credit-approval-requests') . '?company%5B%5D=all_companies&department%5B%5D=all_departments&employee%5B%5D=all_employees&reporting_to_me=no rule'; ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">COMP OFF Credit Request Approval</span>
									<?php
									if (function_exists('get_pending_comp_off_credit_request_count')) {
										if (get_pending_comp_off_credit_request_count() > 0) {
									?>
											<span class="menu-badge">
												<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
													<?php echo get_pending_comp_off_credit_request_count(); ?>
												</span>
											</span>
									<?php
										}
									}
									?>
								</a>
							</div>
						<?php
						} else {
						?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'comp-off-credit-approval-requests') {
														echo 'active';
													} ?>" href="<?php
																if (session()->get('current_user')['employee_id'] == '54') {
																	echo base_url('/backend/administrative/comp-off-credit-approval-requests') . '?status[]=pending&status[]=stage_1&reporting_to_me=no rule';
																} else {
																	echo (session()->get('current_user')['role'] !== 'hr') ? base_url('/backend/administrative/comp-off-credit-approval-requests') . '?status[]=pending&reporting_to_me=yes' : base_url('/backend/administrative/comp-off-credit-approval-requests') . '?reporting_to_me=no rule';
																}
																?>">


									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">COMP OFF Credit Request Approval</span>
									<?php
									if (function_exists('get_pending_comp_off_credit_request_count')) {
										if (get_pending_comp_off_credit_request_count() > 0) {
									?>
											<span class="menu-badge">
												<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
													<?php echo get_pending_comp_off_credit_request_count(); ?>
												</span>
											</span>
									<?php
										}
									}
									?>
								</a>
							</div>
						<?php
						}
						?>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'deduction-approval-requests') {
													echo 'active';
												} ?>" href="<?php echo (session()->get('current_user')['role'] !== 'hr') ? base_url('/backend/administrative/deduction-approval-requests') . '?status[]=pending&reporting_to_me=yes' : base_url('/backend/administrative/deduction-approval-requests') . '?reporting_to_me=no rule'; ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Deduction Approval</span>
								<?php
								if (function_exists('get_pending_deduction_request_count')) {
									if (get_pending_deduction_request_count() > 0) {
								?>
										<span class="menu-badge">
											<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
												<?php echo get_pending_deduction_request_count(); ?>
											</span>
										</span>
								<?php
									}
								}
								?>
							</a>
						</div>
					</div>
				</div>
				<!--end::Administrative Menu-->

				<!--begin::Frontend Menu-->
				<div class="menu-item">
					<div class="menu-content pt-8 pb-2">
						<span class="menu-section text-muted text-uppercase fs-8 ls-1">User Panel</span>
					</div>
				</div>
				<div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php if (isset($current_controller) && $current_controller == 'user') {
																						echo 'here show';
																					} ?>">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-archive fs-3"></i>
						</span>
						<span class="menu-title">User</span>
						<span class="menu-arrow"></span>
					</span>
					<div class="menu-sub menu-sub-accordion menu-active-bg">
						<!-- <div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'profile') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/user/profile'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Profile</span>
								</a>
							</div> -->
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'leaves') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/user/leaves'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">leaves</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'od') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/user/od'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">OD</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'loan') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/user/loan'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Loan</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'advance-salary') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/user/advance-salary'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Advance Salary</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'gate-pass') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/user/gate-pass'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Gate Pass</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'comp-off-credit-requests') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/user/comp-off-credit-requests'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">COMPOFF Credit Requests</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'comp-off-utilization-requests') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/user/comp-off-utilization-requests'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">CompOff Minutes Utilized</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_method) && $current_method == 'attendance-history') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/user/attendance-history'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Attendance History</span>
							</a>
						</div>
					</div>
				</div>
				<!--end::Frontend Menu-->


				<!--begin::Report Menu-->
				<div class="menu-item">
					<div class="menu-content pt-8 pb-2">
						<span class="menu-section text-muted text-uppercase fs-8 ls-1">Reports</span>
					</div>
				</div>
				<div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php if (isset($current_controller) && $current_controller == 'reports') {
																						echo 'here show';
																					} ?>">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-archive fs-3"></i>
						</span>
						<span class="menu-title">Reports</span>
						<span class="menu-arrow"></span>
					</span>
					<div class="menu-sub menu-sub-accordion menu-active-bg">
						<!-- <div class="menu-item">
								<?php
								#$url_param_array = array();
								#$url_param = '';
								#if( isset($company_id_for_menu_url) && !empty($company_id_for_menu_url) ){
								#$url_param_array[] = 'company[]='.$company_id_for_menu_url;
								#}
								#if( isset($department_id_for_menu_url) && !empty($department_id_for_menu_url) ){
								#$url_param_array[] = 'department[]='.$department_id_for_menu_url;
								#}
								#if( isset($employee_id_for_menu_url) && !empty($employee_id_for_menu_url) ){
								#$url_param_array[] = 'employee[]='.$employee_id_for_menu_url;
								#}
								#$url_param = implode('&', $url_param_array);
								#echo $url_param;
								?>
								<a class="menu-link
									<?php #if(isset($current_method) && $current_method == 'punching-report'){echo 'active';}
									?>"
									href="<?php #echo base_url('/backend/reports/punching-report'); if( !empty($url_param) ){ echo '?'.$url_param; }
											?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Punching Report</span>
								</a>
							</div> -->
						<div class="menu-item">
							<a
								class="menu-link
								<?php
								if (isset($current_method) && $current_method == 'attendance-summary') {
									echo 'active';
								}
								?>"
								href="<?php echo base_url('/backend/reports/attendance-summary?company[]=all_companies&department[]=all_departments&employee[]=' . session()->get("current_user")['employee_id'] . '&month=' . date("Y-m", strtotime(first_date_of_last_month()))); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Attendance Summary</span>
							</a>
						</div>
						<div class="menu-item">
							<!-- <a class="menu-link
									<?php if (isset($current_method) && $current_method == 'attendance-sheet') {
										echo 'active';
									} ?>"
								href="<?php echo base_url('/backend/reports/attendance-report/attendance-sheet?company[]=all_companies&department[]=all_departments&employee[]=' . session()->get("current_user")['employee_id']); ?>"> -->

							<a class="menu-link <?php if (isset($current_method) && $current_method == 'final-paid-days-sheet') {
													echo 'active';
												} ?>" href="<?php echo base_url('/backend/reports/final-paid-days/final-paid-days-sheet?company[]=all_companies&department[]=all_departments&employee[]=' . session()->get("current_user")['employee_id'] . '&month=' . date('Y-m')); ?>">

								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title d-flex flex-column align-items-baseline"><span>Punching Report</span><br><small style="font-size:0.65rem">(Current Month)</small></span>
							</a>
						</div>
						<!-- <div class="menu-item">
								<?php
								$url_param_array = array();
								$url_param = '';
								if (isset($company_id_for_menu_url) && !empty($company_id_for_menu_url)) {
									$url_param_array[] = 'company[]=' . $company_id_for_menu_url;
								}
								$url_param = implode('&', $url_param_array);
								?>
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'leave-report') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/leave-report');
																if (!empty($url_param)) {
																	echo '?' . $url_param;
																} ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Leave Report</span>
								</a>
							</div> -->
						<!-- <div class="menu-item">
								<?php
								$url_param_array = array();
								$url_param = '';
								if (isset($company_id_for_menu_url) && !empty($company_id_for_menu_url)) {
									$url_param_array[] = 'company[]=' . $company_id_for_menu_url;
								}
								$url_param = implode('&', $url_param_array);
								?>
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'od-report') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/od-report');
																if (!empty($url_param)) {
																	echo '?' . $url_param;
																} ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">OD Report</span>
								</a>
							</div> -->
						<?php if (
							session()->get('current_user')['role'] == 'hr'
							|| session()->get('current_user')['employee_id'] == '20'
							|| session()->get('current_user')['employee_id'] == '40'
							|| session()->get('current_user')['employee_id'] == '165'
						) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'final-paid-days-sheet') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/final-paid-days/final-paid-days-sheet?company[]=all_companies&department[]=all_departments&employee[]=' . session()->get("current_user")['employee_id']); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">HR SHEET</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40']) || in_array(session()->get('current_user')['role'], ['hr', 'superuser'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'leave-balance-all') {
														echo 'active';
													} ?>" href="<?php echo base_url('backend/reports/leave-balance-all'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Leave Balance All</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40']) || in_array(session()->get('current_user')['role'], ['hr'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'machine-override-all') {
														echo 'active';
													} ?>" href="<?php echo base_url('backend/reports/machine-override-all'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Machine Override All</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40']) || in_array(session()->get('current_user')['role'], ['hr'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'attendance-override-all') {
														echo 'active';
													} ?>" href="<?php echo base_url('backend/reports/attendance-override-all'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Attendance Override All</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40']) || in_array(session()->get('current_user')['role'], ['hr'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'shift-override-all') {
														echo 'active';
													} ?>" href="<?php echo base_url('backend/reports/shift-override-all'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Shift Override All</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40']) || in_array(session()->get('current_user')['role'], ['hr'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'waveoff-minutes-all') {
														echo 'active';
													} ?>" href="<?php echo base_url('backend/reports/waveoff-minutes-all'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">WaveOff Minutes All</span>
								</a>
							</div>
						<?php } ?>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40']) || in_array(session()->get('current_user')['role'], ['hr'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'deduction-minutes-all') {
														echo 'active';
													} ?>" href="<?php echo base_url('backend/reports/deduction-minutes-all'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Deduction Minutes All</span>
								</a>
							</div>
						<?php } ?>
						<!-- Asked to remove by HR Rahul -->
						<!-- <div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'final-paid-days') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/final-paid-days'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Final Paid Days</span>
								</a>
							</div>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'final-salary') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/salary/final-salary'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Final Salary</span>
								</a>
							</div> -->
						<!-- Asked to remove by HR Rahul -->
						<?php if (session()->get('current_user')['role'] == 'hr' || in_array(session()->get('current_user')['employee_id'], ['40', '93', '52'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'final-salary') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/salary/final-salary'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Final Salary</span>
								</a>
							</div>
						<?php } ?>


						<?php if (session()->get('current_user')['role'] == 'hr' || in_array(session()->get('current_user')['employee_id'], ['40', '93', '52'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'final-salary-intern') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/salary/final-salary-intern'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Final Salary <small>Intern</small></span>
								</a>
							</div>
						<?php } ?>


						<?php if (session()->get('current_user')['role'] == 'hr' || in_array(session()->get('current_user')['employee_id'], ['40', '93', '52'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'ncl-report') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/ncl/ncl-report?company[]=all_companies&department[]=all_departments&employee[]=' . session()->get("current_user")['employee_id']); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">NCL Report</span>
								</a>
							</div>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_method) && $current_method == 'loyalty-incentive-report') {
														echo 'active';
													} ?>" href="<?php echo base_url('/backend/reports/loyalty-incentive/loyalty-incentive-report?company[]=all_companies&department[]=all_departments&employee[]=' . session()->get("current_user")['employee_id']); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Loyalty Incentive Report</span>
								</a>
							</div>
							<div class="menu-item">
								<a class="menu-link 
									<?php
									if (isset($current_method) && $current_method == 'labour-register') {
										echo 'active';
									}
									?>" href="<?php echo base_url('/backend/reports/labour-register'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Labour Registers</span>
								</a>
							</div>


						<?php } ?>

						<div class="menu-item">
							<a class="menu-link <?php if (isset($current_controller) && $current_controller == 'notifications') {
													echo 'active';
												} ?>"
								href="<?php echo base_url('backend/notifications'); ?>">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Notifications</span>
							</a>
						</div>

						<?php if (in_array(session()->get('current_user')['employee_id'], ['40']) || in_array(session()->get('current_user')['role'], ['hr', 'superuser'])) { ?>
							<div class="menu-item">
								<a class="menu-link <?php if (isset($current_controller) && $current_controller == 'resignation') {
														echo 'active';
													} ?>"
									href="<?php echo base_url('resignation'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Resignation</span>
									<?php
									if (function_exists('get_active_resignation_count')) {
										$resignation_count = get_active_resignation_count();
										if ($resignation_count > 0) {
									?>
											<span class="menu-badge">
												<span class="badge badge-sm badge-light-danger rounded-pill fs-9">
													<?php echo $resignation_count; ?>
												</span>
											</span>
									<?php
										}
									}
									?>
								</a>
							</div>
						<?php } ?>

					</div>
				</div>
				<!--end::Report Menu-->
				<?php
				// }
				?>



				<?php if (in_array(session()->get('current_user')['role'], ['hr', 'manager', 'hod', 'tl']) || session()->get('current_user')['employee_id'] == '40') { ?>
					<div class="menu-item">
						<div class="menu-content pt-8 pb-2">
							<span class="menu-section text-muted text-uppercase fs-8 ls-1">Recruitment</span>
						</div>
					</div>
					<div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php if (isset($current_controller) && $current_controller == 'recruitment') {
																							echo 'here show';
																						} ?>">
						<span class="menu-link">
							<span class="menu-icon">
								<i class="bi bi-archive fs-3"></i>
							</span>
							<span class="menu-title">Recruitment</span>
							<?php
							$recruitment_count = 0;
							if (function_exists('getPendingApprovalCounts')) {
								$recruitment_count = getPendingApprovalCounts();
							}
							if ($recruitment_count > 0) :
							?>
								<span class="menu-badge">
									<span class="badge badge-sm badge-light-danger rounded-pill fs-9 mx-3"><?= $recruitment_count ?></span>
								</span>
							<?php endif; ?>
							<span class="menu-arrow"></span>
						</span>
						<div class="menu-sub menu-sub-accordion menu-active-bg">

							<div class="menu-item <?php
													if (isset($current_method) && $current_method == 'job-listing') {
														echo $current_method . 'active';
													}
													?>">
								<a class="menu-link"
									href="<?php echo base_url('/recruitment/job-listing/all'); ?>">
									<span class="menu-bullet">
										<span class="bullet bullet-dot"></span>
									</span>
									<span class="menu-title">Job Listing</span>
								</a>
							</div>
							<?php if (in_array(session()->get('current_user')['role'], ['hr']) || session()->get('current_user')['employee_id'] == '40') { ?>
								<div class="menu-item <?php
														if (isset($current_method) && $current_method == 'task-dashboard') {
															echo $current_method . 'active';
														}
														?>">
									<a class="menu-link"
										href="<?php echo base_url('/recruitment/task-dashboard'); ?>">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">Task Dashboard</span>
									</a>
								</div>
							<?php
							}
							?>
							

						</div>
					</div>
					<!--end::Report Menu-->
				<?php
				}
				?>



				<div class="menu-item flex-grow-1">
				</div>





				<!-- <div class="menu-item">
					<div class="menu-content pt-8 pb-0">
						<span class="menu-section text-muted text-uppercase fs-8 ls-1">Layout</span>
					</div>
				</div>
				<div class="menu-item">
					<div class="menu-content">
						<div class="separator mx-1 my-4"></div>
					</div>
				</div>
				<div class="menu-item">
					<a class="menu-link" href="<?php #echo base_url();
												?>">
						<span class="menu-icon">
							<i class="bi bi-calendar3-event fs-3"></i>
						</span>
						<span class="menu-title">Calendar</span>
					</a>
				</div>
				<div class="menu-item">
					<a class="menu-link" href="<?php #echo base_url();
												?>">
						<span class="menu-icon">
							<i class="fa fa-code"></i>
						</span>
						<span class="menu-title">Changelog v8.0.36</span>
					</a>
				</div> -->
			</div>
			<!--end::Menu-->
		</div>
		<!--end::Aside Menu-->
	</div>
	<!--end::Aside menu-->
	<!--begin::Footer-->
	<div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
		<a href="<?php echo base_url('logout'); ?>" class="btn btn-custom btn-primary w-100">
			<i class="fas fa-sign-out px-3"></i>
			<span class="btn-label">Sign Out</span>
		</a>
	</div>
	<!--end::Footer-->
</div>
<!--end::Aside