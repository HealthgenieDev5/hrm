<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">

        <!--begin::Col-->
        <div class="col-xl-12">
            <!--begin::Mixed Widget 2-->
            <div class="card">
                <!--begin::Body-->
                <div class="card-body">
                    <form id="filter_form" class="row" enctype='multipart/form-data'>
                        <div class="col-lg-2">
                            <label class="form-label" for="company" class="mb-3">Company</label>
                            <!-- <select class="form-select form-select-sm" id="company" name="company[]" multiple data-placeholder="Select a Company" > -->
                            <select class="form-select form-select-sm" id="company" name="company[]" multiple data-control="select2" data-placeholder="Select a Company" >
                                <option value=""></option>
                                <option value="all_companies" <?php echo ( isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array( 'all_companies', $_REQUEST['company']) ) ? 'selected' : ''; ?> >All Companies</option>
                                <?php
                                if( isset($Companies) && !empty($Companies) ){
                                    foreach( $Companies as $company_row){
                                        ?>
                                        <option value="<?php echo $company_row['id']; ?>" <?php echo ( isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array( $company_row['id'], $_REQUEST['company']) && !in_array( 'all_companies', $_REQUEST['company']) ) ? 'selected' : ''; ?> ><?php echo $company_row['company_name']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <br>
                            <small class="text-danger error-text" id="company_error"></small>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label" for="department" class="mb-3">Department</label>
                            <select class="form-select form-select-sm" id="department" name="department[]" multiple data-control="select2" data-placeholder="Select a Department" >
                                <option value=""></option>
                                <option value="all_departments" <?php echo ( isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array( 'all_departments', $_REQUEST['department']) ) ? 'selected' : ''; ?> >All Departments</option>
                                <?php
                                if( isset($Departments) && !empty($Departments) ){
                                    foreach( $Departments as $department_row){
                                        ?>
                                        <option value="<?php echo $department_row['id']; ?>" <?php echo ( isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array( $department_row['id'], $_REQUEST['department']) && !in_array( 'all_departments', $_REQUEST['department']) ) ? 'selected' : ''; ?> ><?php echo $department_row['department_name']. ' - ' .$department_row['company_short_name']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <br>
                            <small class="text-danger error-text" id="department_error"></small>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label" for="employee" class="mb-3">Employee</label>
                            <select class="form-select form-select-sm" id="employee" name="employee[]" multiple data-control="select2" data-placeholder="Select an Employee" >
                                <option value=""></option>
                                <option value="all_employees" <?php echo ( isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array( 'all_employees', $_REQUEST['employee']) ) ? 'selected' : ''; ?> >All Employees</option>
                                <?php
                                if( isset($Employees) && !empty($Employees) ){
                                    foreach( $Employees as $employee_row){
                                        ?>
                                        <option value="<?php echo $employee_row['id']; ?>" <?php echo ( isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array( $employee_row['id'], $_REQUEST['employee']) && !in_array( 'all_employees', $_REQUEST['employee']) ) ? 'selected' : ''; ?> ><?php echo $employee_row['employee_name'].'('.$employee_row['internal_employee_id'].') - '.$employee_row['department_name'].' -'.$employee_row['company_short_name']; ?><?php echo $employee_row['status'] != 'active' ? ' --'.$employee_row['status'] : ''; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="employee_error"></small>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label" for="reporting_to_me">Reporting To me</label>
                            <select class="form-select form-select-sm" id="reporting_to_me" name="reporting_to_me" data-control="select2" data-placeholder="Select an option" >
                                <option value="no rule" <?php echo ( isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'no rule' ) ? 'selected' : ''; ?> >Show All</option>
                                <option value="yes" <?php echo ( isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'yes' ) ? 'selected' : ''; ?> >Show Reporting to me</option>
                                <option value="no" <?php echo ( isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'no' ) ? 'selected' : ''; ?> >Hide Reporting to me</option>
                            </select>
                            <small class="text-danger error-text" id="reporting_to_me_error"></small>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label" for="leave_type" class="mb-3">Leave Types</label>
                            <select class="form-select form-select-sm" id="leave_type" name="leave_type[]" multiple data-control="select2" data-placeholder="Select Leave Type" >
                                <option value=""></option>
                                <option value="all_leave_type">All Leave Type</option>
                                <?php
                                if( !empty($leave_types) ) {
                                    foreach($leave_types as $leave_type){
                                        ?>
                                        <option value="<?php echo $leave_type['type_of_leave']; ?>" <?php echo ( isset($_REQUEST['leave_type']) && !empty($_REQUEST['leave_type']) && in_array( $leave_type['type_of_leave'], $_REQUEST['leave_type']) && !in_array( 'all_leave_type', $_REQUEST['leave_type']) ) ? 'selected' : ''; ?> ><?php echo $leave_type['type_of_leave']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="status_error"></small>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label" for="status" class="mb-3">Leave Status</label>
                            <select class="form-select form-select-sm" id="status" name="status[]" multiple data-control="select2" data-placeholder="Select Leave Status" >
                                <option value=""></option>
                                <option value="all_status">All Status</option>
                                <?php
                                if( !empty($statuses) ) {
                                    foreach($statuses as $status){
                                        ?>
                                        <option value="<?php echo $status['status']; ?>" <?php echo ( isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array( $status['status'], $_REQUEST['status']) && !in_array( 'all_status', $_REQUEST['status']) ) ? 'selected' : ''; ?> ><?php echo ucfirst($status['status']); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="status_error"></small>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label" for="date_range_for_filter" class="mb-3">Date Range</label>
                            <div class="position-relative d-flex align-items-center ">
                                <!--begin::Icon-->
                                <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                    <!-- <i class="fas fa-clock"></i> -->
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                                <input 
                                type="text" 
                                id="date_range_for_filter" 
                                class="form-control form-control-sm form-control-solid ps-7" 
                                placeholder="Select date range" 
                                value="<?php echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['from_date'].' to '.$_REQUEST['to_date'] : first_date_of_last_month().' to '.last_date_of_month(); ?>" />


                                <input type="hidden" id="from_date" name="from_date" value="<?php echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['from_date'] : first_date_of_last_month(); ?>" />


                                <input type="hidden" id="to_date" name="to_date" value="<?php echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['to_date'] : last_date_of_month(); ?>" />
                            </div>
                            <small class="text-muted" >Leave date or date of application</small><br>
                            <span class="text-danger error-text d-block" id="date_range_for_filter_error"></span>
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label"> &nbsp; </label><br>
                            <button type="submit" id="filter_form_submit" class="btn btn-sm btn-primary d-inline">
                                <span class="indicator-label">Filter</span>
                                <span class="indicator-progress">
                                    Please wait... 
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Mixed Widget 2-->

        <!--begin::Col-->
        <div class="col-xl-12">
            <!--begin::Mixed Widget 2-->
            <div class="card">
                <!--begin::Header-->
                <div class="card-header">
                    <h3 class="card-title">Leave Requests</h3>

                    <div class="modal fade" tabindex="-1" id="approve_leave_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="approve_leave_request" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Approve Leave Request</h5>
                                        <div class="d-flex flex-column align-items-center justify-content-start">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <strong class="ms-4 text-primary opacity-75">
                                                        <strong id="employee_name"></strong> &nbsp; <small>(<strong id="department_name"></strong> - <strong id="company_short_name"></strong>)</small>
                                                    </strong>
                                                </li>
                                            </ul>
                                        </div>
                                        <!--begin::Close-->
                                        <!-- <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x">X</span>
                                        </div> -->
                                        <!--end::Close-->
                                    </div>

                                    <div class="modal-body">                                            
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="hidden" id="leave_id" name="leave_id" value="" />
                                                <small class="text-danger error-text" id="leave_id_error"><?= isset($validation) ? display_error($validation, 'leave_id') : '' ?></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Req. ID</label>
                                                                <strong id="leave_request_id" class="ms-4 text-primary opacity-75"></strong>
                                                            </li>
                                                            <!-- <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Employee</label>
                                                                <strong id="employee_name" class="ms-4 text-primary opacity-75"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Dep</label>
                                                                <strong id="department_name" class="ms-4 text-primary opacity-75"></strong>
                                                            </li> -->
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">From</label>
                                                                <strong id="from_date" class="ms-4 text-primary opacity-75"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">To</label>
                                                                <strong id="to_date" class="ms-4 text-primary opacity-75"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Number of days</label>
                                                                <strong id="number_of_days" class="ms-4 text-primary opacity-75"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Request Type</label>
                                                                <strong id="day_type" class="ms-4 fs-6 text-info opacity-75"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Type of leave</label>
                                                                <strong id="type_of_leave" class="ms-4 fs-6 text-info opacity-75"></strong>
                                                            </li>
                                                            <!-- <li class="list-group-item">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <label class="form-label mb-0">Distribution</label>
                                                                    <div class="ms-4 d-flex">
                                                                        <label class="form-label">CL: <strong class="text-info opacity-75" id="number_of_cl"></strong>,</label>
                                                                        <label class="form-label mx-2">EL: <strong class="text-info opacity-75" id="number_of_el"></strong>,</label>
                                                                        <label class="form-label">UL: <strong class="text-info opacity-75" id="number_of_ul"></strong></label>
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted">UL= Unpaid Leave</small>
                                                            </li> -->
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Status</label>
                                                                <strong id="status" class="ms-4 badge badge-danger rounded-pill text-capitalize"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Req. On</label>
                                                                <strong id="date_time" class="ms-4 text-pink opacity-75"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">Rep. Mgr.</label>
                                                                <strong id="reporting_manager_name" class="ms-4 text-primary opacity-75"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                                <label class="form-label mb-0">HOD</label>
                                                                <strong id="hod_name" class="ms-4 text-muted"></strong>
                                                            </li>
                                                            <li class="list-group-item d-flex flex-column">
                                                                <label class="form-label">Reason</label>
                                                                <small id="reason_of_leave" class="text-muted"></small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <ul class="list-group ">
                                                    <li class="list-group-item d-flex align-items-center justify-content-center">
                                                        <label class="form-label text-muted m-0">Leave Balance</label>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <small class="me-3"><strong style="color: #3f4254">Month</strong></small>
                                                        <small id="balance_month_year" class="text-pink opacity-75"></small>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <small class="me-3"><strong style="color: #3f4254">CL</strong></small>
                                                        <small id="cl_balance" class="text-pink opacity-75"></small>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <small class="me-3"><strong style="color: #3f4254">EL</strong></small>
                                                        <small id="el_balance" class="text-pink opacity-75"></small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                                <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <button id="approve_leave_request_submit_button" class="btn btn-sm btn-primary">Approve</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="reject_leave_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="reject_leave_request" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Leave Request</h5>
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                        <!--end::Close-->
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="hidden" id="leave_id" name="leave_id" value="" />
                                                <small class="text-danger error-text" id="leave_id_error"><?= isset($validation) ? display_error($validation, 'leave_id') : '' ?></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Leave Request ID</label>
                                                        <strong id="leave_request_id" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Employee Name</label>
                                                        <strong id="employee_name" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Department</label>
                                                        <strong id="department_name" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">From</label>
                                                        <strong id="from_date" class="ms-4 text-info opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">To</label>
                                                        <strong id="to_date" class="ms-4 text-info opacity-75"></strong>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Status</label>
                                                        <strong id="status" class="ms-4 badge badge-danger rounded-pill text-capitalize"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Requested Date Tame</label>
                                                        <strong id="date_time" class="ms-4 text-info opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Reporting Manager</label>
                                                        <strong id="reporting_manager_name" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">HOD</label>
                                                        <strong id="hod_name" class="ms-4 text-muted"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column">
                                                        <label class="form-label">Reason</label>
                                                        <small id="reason_of_leave" class="text-muted"></small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                                <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button id="reject_leave_request_submit_button" class="btn btn-danger">Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="cancel_leave_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="cancel_leave_request" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cancel Leave Request</h5>
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                        <!--end::Close-->
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="hidden" id="leave_id" name="leave_id" value="" />
                                                <small class="text-danger error-text" id="leave_id_error"><?= isset($validation) ? display_error($validation, 'leave_id') : '' ?></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Leave Request ID</label>
                                                        <strong id="leave_request_id" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Employee Name</label>
                                                        <strong id="employee_name" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Department</label>
                                                        <strong id="department_name" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">From</label>
                                                        <strong id="from_date" class="ms-4 text-info opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">To</label>
                                                        <strong id="to_date" class="ms-4 text-info opacity-75"></strong>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Status</label>
                                                        <strong id="status" class="ms-4 badge badge-danger rounded-pill text-capitalize"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Requested Date Tame</label>
                                                        <strong id="date_time" class="ms-4 text-info opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Reporting Manager</label>
                                                        <strong id="reporting_manager_name" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">HOD</label>
                                                        <strong id="hod_name" class="ms-4 text-muted"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column">
                                                        <label class="form-label">Reason</label>
                                                        <small id="reason_of_leave" class="text-muted"></small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                                <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button id="cancel_leave_request_submit_button" class="btn btn-danger">Mark as Cancelled</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">                   
                    <table id="leave_approval_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>Employee Name<br>& Department</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>DayType</strong></th>
                                <th class="text-center"><strong>From Date</strong></th>
                                <th class="text-center"><strong>To Date</strong></th>
                                <th class="text-center"><strong>Days</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Attachment</strong></th>
                                <th class="text-center"><strong>Reporting Manager Name</strong></th>
                                <th class="text-center"><strong>HOD Name</strong></th>
                                <th class="text-center"><strong>Requested Date</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center bg-white"><strong>Req#</strong></th>
                                <th class="text-center bg-white"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>Employee Name<br>& Department</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>DayType</strong></th>
                                <th class="text-center"><strong>From Date</strong></th>
                                <th class="text-center"><strong>To Date</strong></th>
                                <th class="text-center"><strong>Days</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Attachment</strong></th>
                                <th class="text-center"><strong>Reporting Manager Name</strong></th>
                                <th class="text-center"><strong>HOD Name</strong></th>
                                <th class="text-center"><strong>Requested Date</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center bg-white"><strong>Req#</strong></th>
                                <th class="text-center bg-white"><strong>Actions</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                     <form id="filter_form">
                        <!-- <input type="text" name="current_user_id" value="<?php echo session()->get('current_user')['employee_id']; ?>"> -->
                    </form>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 2-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <?= $this->section('javascript') ?>
    <script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            var getDepartment = true;
            var getEmployee = true;

            $(document).on('change', '#company', function(){
                var company = $('#company').val();
                $('#department_error').html('');
                $('#employee_error').html('');
                $('#department').parent().find('.select2-selection').addClass('loading');
                $('#department').val(null).trigger('change');
                $('#employee').val(null).trigger('change');
                if( getDepartment == true){
                    getDepatmentByCompany( $('#company').val(), true ).then(function(){
                        $('#department').parent().find('.select2-selection').removeClass('loading');
                        getDepartment = false;
                    });
                }else{
                    getDepatmentByCompany( $('#company').val(), false ).then(function(){
                        $('#department').parent().find('.select2-selection').removeClass('loading');
                    });
                }                
            })

            $(document).on('change', '#department', function(){
                var department = $('#department').val();
                if(jQuery.inArray("all_departments", department) !== -1 && department.length > 1){
                    $('#department').select2("val", ['all_departments']);
                }
                $('#employee_error').html('');
                $('#employee').parent().find('.select2-selection').addClass('loading');
                $('#employee').val(null).trigger('change');
                if( getEmployee == true){
                    getEmployeesByDepatment( $('#department').val(), true ).then(function(){
                        $('#employee').parent().find('.select2-selection').removeClass('loading');
                        getEmployee = false;
                    });
                }else{
                    getEmployeesByDepatment( $('#department').val(), false ).then(function(){
                        $('#employee').parent().find('.select2-selection').removeClass('loading');
                    });
                }            
            })

            $(document).on('change', '#employee', function(){
                var employee = $('#employee').val();
                if(jQuery.inArray("all_employees", employee) !== -1 && employee.length > 1){
                    $('#employee').select2("val", ['all_employees']);
                }
            })



            $("#date_range_for_filter").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                // defaultDate: ["<?= current_date_of_month() ?>", "<?= current_date_of_month() ?>"],
                mode: "range"
            });

            $(document).on('change', '#date_range_for_filter', function(e){
                console.log($(this).val());
                var date_range_for_filter = $(this).val();
                date_range_for_filter_array = date_range_for_filter.split('to');
                console.log(date_range_for_filter_array);
                if( date_range_for_filter_array.length == 1 ){
                    $('#from_date').val(date_range_for_filter_array[0]);
                    $('#to_date').val(date_range_for_filter_array[0]);
                } else if( date_range_for_filter_array.length == 2 ){
                    $('#from_date').val(date_range_for_filter_array[0]);
                    $('#to_date').val(date_range_for_filter_array[1]);
                }
            })


            const getDepatmentByCompany = async (company_id, preserve_get_param = true ) => {
                $('#department').html('<option></option>');
                $('#department').append('<option value="all_departments">All Departments</option>');
                var data = {
                    'company_id' : company_id,
                };
                return $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/reports/get-department-by-company-id'); ?>",
                    data: data,
                    success: function(response){
                        if( response.response_type == 'error' ){
                            $('#department_error').html(response.response_description);
                        }

                        if( response.response_type == 'success' ){
                            if( typeof response.response_data.departments != 'undefined' ){
                                var department_data = response.response_data.departments;
                                $.each(department_data, function(index, department){
                                    $('#department').append('<option value="'+department.id+'" >'+department.department_name+' - '+department.company_short_name+'</option>');
                                });
                                if( preserve_get_param === true ){
                                    var department_from_url = [<?php echo isset($_REQUEST["department"]) && !empty($_REQUEST["department"]) ? '"'.implode('","', $_REQUEST["department"]).'"' : null; ?>];
                                    $('#department').val(department_from_url).trigger('change');
                                }

                            }
                        }                  
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed while loading departments conditionally, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    }
                });
            }

            const getEmployeesByDepatment = async (department_id, preserve_get_param = true) => {
                $('#employee').val(null).trigger('change');
                $('#employee').html('<option></option>');
                $('#employee').append('<option value="all_employees">All Employees</option>');
                var data = {
                    'department_id' : department_id,
                };
                return $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/reports/get-employees-by-department-id'); ?>",
                    data: data,
                    success: function(response){
                        console.log(response);
                        if( response.response_type == 'error' ){
                            $('#employee_error').html(response.response_description);
                        }

                        if( response.response_type == 'success' ){
                            if( typeof response.response_data.employees != 'undefined' ){
                                var employee_data = response.response_data.employees;
                                $.each(employee_data, function(index, employee){
                                    // $('#employee').append('<option value="'+employee.id+'" >'+employee.employee_name+' ('+employee.internal_employee_id+') - '+employee.department_name+' - '+employee.company_short_name+'</option>');

                                    $('#employee').append(`<option value="${employee.id}" >${employee.employee_name} (${employee.internal_employee_id}) - ${employee.department_name} - ${employee.company_short_name} ${employee.status != 'active' ? ' --'+employee.status : ''}</option>`);
                                });
                                if( preserve_get_param === true ){
                                    var employee_from_url = [<?php echo isset($_REQUEST["employee"]) && !empty($_REQUEST["employee"]) ? '"'.implode('","', $_REQUEST["employee"]).'"' : null; ?>];
                                    $('#employee').val(employee_from_url);
                                }
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed while loading employees conditionally, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    }
                });
            }


            //begin::Initialize Datatable
            var table = $("#leave_approval_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rtl',
                "paging": false,
                "scrollX": true,
                "scrollY": '400px',
                "scrollCollapse": true,
                "order": [],
                "buttons": ['excel'],
                "ajax": {
                    url:  "<?= base_url('ajax/get-all-leave-approval-requests') ?>",
                    type:  "POST",
                    // data:  { current_user_id : "<?php echo session()->get('current_user')['employee_id']; ?>" },
                    data:  { filter : function(){ return $('#filter_form').serialize(); } },
                    dataSrc: "",
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                },
                "columns": [
                    { 
                        data: "employee_name",
                        render: function(data, type, row, meta){
                            return '<div class="d-flex flex-column">'+
                                    '<strong>'+data+'</strong>'+
                                    '<strong>('+row.department_name+')</strong>'+
                                    '</div>';
                        }
                    },
                    { 
                        data: "type_of_leave",
                        render : function(data, type, row, meta) {
                            if( row.sick_leave == 'yes' ){
                                return 'SICK LEAVE';
                            }
                            return row.type_of_leave;
                        }
                    },
                    { data: "day_type" },
                    {
                        data: {
                            _: 'from_date.formatted',
                            sort: 'from_date.ordering',
                        },
                        render: function (data, type, row) {
                            return '<strong>'+data+'</strong>';
                        }
                    },
                    {
                        data: {
                            _: 'to_date.formatted',
                            sort: 'to_date.ordering',
                        },
                        render: function (data, type, row) {
                            return '<strong>'+data+'</strong>';
                        }
                    },
                    { data: "number_of_days" },
                    { data: "status", 
                        render : function(data, type, row, meta) {
                            var badge_class = "bg-secondary text-dark";
                            if( data == 'rejected'){
                                badge_class = "bg-danger text-danger bg-opacity-15";
                            }else if(data == 'approved'){
                                badge_class = "bg-success text-success bg-opacity-15";
                            }else if(data == 'disbursed'){
                                badge_class = "bg-info text-info bg-opacity-15";
                            }
                            return '<span class="badge text-capitalize rounded-pill '+badge_class+'">'+data+'</span>';
                        }
                    },
                    { 
                        data: "reason_of_leave",
                        render: function(data, type, row, meta){
                            if(row.backend_request == 'yes'){
                                return '<p class="text-wrap" style="width: 200px; text-align: justify; background-color: #f89fb5; padding: 5px 15px; border-radius: 8px;">'+data+'</p>';
                            }else{
                                return '<p class="text-wrap" style="width: 200px; text-align: justify;">'+data+'</p>';
                            }
                        }
                    },
                    { 
                        data: "attachment", 
                        render : function(data, type, row, meta) {
                            if( data.length ){
                                var link = '<?php echo base_url(); ?>'+data;
                                return '<a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="'+link+'" target="_blank">View</a>';
                            }else{
                                return '-';
                            }
                        }
                    },
                    { data: "reporting_manager_name" },
                    { data: "hod_name"},
                    {
                        data: {
                            _: 'date_time.formatted',
                            sort: 'date_time.ordering',
                        },
                    },
                    { data: "reviewed_by_name" },
                    {
                        data: {
                            _: 'reviewed_date.formatted',
                            sort: 'reviewed_date.ordering',
                        },
                    },
                    { 
                        data: "remarks",
                        render: function(data, type, row, meta){
                            return '<p class="text-wrap" style="width: 200px; text-align: justify;">'+data+'</p>';
                        }
                    },
                    { data: "leave_request_id" },
                    { data: "actions", 
                        render: function(data, type, row, meta){
                            if( row.status !== 'pending' ){
                                var is_btn_disabled = 'disabled';
                                var data_id = '';
                            }else{
                                var is_btn_disabled = '';
                                var data_id = row.leave_request_id;
                            }
                            var approve_button = '<a href="#" class="btn btn-light-success btn-active-success btn-sm approve-leave '+is_btn_disabled+'" data-id="'+data_id+'">'+
                                                    '<span class="svg-icon svg-icon-3">'+
                                                        '<span class="fa fa-check" aria-hidden="true" ></span>'+
                                                    '</span>'+
                                                '</a>';
                            var reject_button = '<a href="#" class="btn btn-light-danger btn-active-danger btn-sm reject-leave '+is_btn_disabled+'" data-id="'+data_id+'">'+
                                                    '<span class="svg-icon svg-icon-3">'+
                                                        '<span class="fa fa-times" aria-hidden="true" ></span>'+
                                                    '</span>'+
                                                '</a>';
                            /*var reject_button = '<a href="#" class="btn btn-light-danger btn-active-danger btn-sm " >'+
                                                    'Reject Disbaled till 02 Jan'+
                                                '</a>';*/

                            // if( row.status == 'cancelled' || row.status == 'canceled' ){
                            if( row.status == 'cancelled' || row.status == 'rejected' ){
                                var cancel_btn_disabled = 'disabled';
                                var data_id = '';
                            }else{
                                var cancel_btn_disabled = '';
                                var data_id = row.leave_request_id;
                            }

                            var cancel_button = '<a href="#" class="btn btn-danger btn-active-danger btn-sm cancel-leave '+cancel_btn_disabled+'" data-id="'+row.leave_request_id+'">'+
                                                    '<span class="svg-icon svg-icon-3">'+
                                                        '<span class="fa fa-times" aria-hidden="true" ></span>'+
                                                        '<span class="fa fa-times" aria-hidden="true" ></span>'+
                                                        '<span class="fa fa-times" aria-hidden="true" ></span>'+
                                                    '</span>'+
                                                '</a>';
                            var current_user_role = "<?php echo session()->get('current_user')['role']; ?>";
                            var current_user_employee_id = "<?php echo session()->get('current_user')['employee_id']; ?>";
                            
                            if( current_user_employee_id == '40' || current_user_role == 'hr' ){
                                var action = '<div class="btn-group">'+approve_button+reject_button+cancel_button+'</div>';
                            }else{
                                var action = '<div class="btn-group">'+approve_button+reject_button+'</div>';
                                // var action = '<div class="btn-group">Disabled until 03:00 PM</div>';
                            }
                            return action;
                        }
                    },
                ],
                "fixedColumns": {
                    left: 3,
                    right: 1
                },
                "columnDefs": [
                    { "className": 'border-end border-secondary td-border-left text-center', "targets": [2] },
                    { "className": 'border-start border-secondary td-border-left text-center', "targets": [-1] },
                    // { "className": 'bg-white ', "targets": [0,1,2] },
                    { "className": 'text-center', "targets": '_all' },
                ],
                "initComplete": function(settings, json){
                    <?php
                    if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'approve' && isset($_REQUEST['id']) && !empty($_REQUEST['id']) ){
                        ?>
                        var id = "<?php echo $_REQUEST['id']; ?>";
                        $("a.approve-leave[data-id="+id+"]").trigger('click');
                        <?php
                    }
                    if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'reject' && isset($_REQUEST['id']) && !empty($_REQUEST['id']) ){
                        ?>
                        var id = "<?php echo $_REQUEST['id']; ?>";
                        $("a.reject-leave[data-id="+id+"]").trigger('click');
                        <?php
                    }
                    ?>
                    var count = table.rows(function(idx, data, node) { return data.status == 'pending'; }).count();
                    // console.log('count: ', count);
                },
                /*"createdRow": function( row, data, dataIndex ) {
                    console.log(data);
                    if ( data.backend_request == "yes" ) {
                        $('td', row).css({'background-color': '#f89fb5'});
                    }
                }*/
            });

            
            //end::Initialize Datatable


            //begin::get leave to approve
            $(document).on('click', '.approve-leave', function(e){
                e.preventDefault();
                var leave_id = $(this).data('id');
                if( leave_id == '' ){
                    return false;
                }
                var data = {
                    'leave_id'        :   leave_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/administrative/get-leave-request'); ?>",
                    data: data,
                    success: function(response){
                        console.log(response);
                        if( response.response_type == 'error' ){
                            if( response.response_description.length ){
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        }

                        if( response.response_type == 'success' ){
                            if( typeof response.response_data.leave_data != 'undefined' ){
                                var leave_data = response.response_data.leave_data;
                                $("form#approve_leave_request").find('small.error-text').html('');
                                $("form#approve_leave_request").find('input[name="leave_id"]').val(leave_data.leave_request_id);
                                $("form#approve_leave_request").find('textarea#remarks').html(leave_data.remarks);
                                $.each(leave_data, function(index, value){
                                    if( index == 'type_of_leave' && value == 'EL' && leave_data.sick_leave == 'yes' ){
                                        $("form#approve_leave_request").find('strong#'+index).html('SICK LEAVE');
                                    }else{
                                        $("form#approve_leave_request").find('strong#'+index).html(value);
                                        $("form#approve_leave_request").find('small#'+index).html(value);
                                    }
                                    
                                });
                                $("#approve_leave_request_modal").modal('show');
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    }
                })
            })
            //end::get leave to approve

            //begin::Approve Leave  Ajax
            $(document).on('click', '#approve_leave_request_submit_button', function(e){
                e.preventDefault();
                var form = $('#approve_leave_request');
                form.closest('.modal').modal('hide');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve it!',
                    cancelButtonText: 'No, Go Back!',
                    customClass: { confirmButton: "btn btn-sm btn-primary", cancelButton: "btn btn-sm btn-secondary" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        var data = new FormData(form[0]);
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('ajax/administrative/approve-leave-request'); ?>",
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(response){
                                console.log(response);
                                if( response.response_type == 'error' ){
                                    if( response.response_description.length ){
                                        Swal.fire({
                                            html: response.response_description,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function (e) {
                                            if( typeof response.response_data.validation != 'undefined' ){
                                                var validation = response.response_data.validation;
                                                $.each(validation, function(index, value){
                                                    form.find('#'+index+'_error').html(value);
                                                });
                                                form.closest('.modal').modal('show');
                                            }
                                        });
                                    }
                                }

                                if( response.response_type == 'success' ){
                                    if( response.response_description.length ){
                                        Swal.fire({
                                            html: response.response_description,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function (e) {
                                            form[0].reset();
                                            $("#leave_approval_table").DataTable().ajax.reload();
                                        });
                                    }
                                }
                            },
                            failed: function(){
                                Swal.fire({
                                    html: "Ajax Failed, Please contact administrator",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        })
                    }else{
                        form.closest('.modal').modal('show');
                    }
                })
            })
            //end::Approve Leave  Ajax

            //begin::get leave to reject
            $(document).on('click', '.reject-leave', function(e){
                e.preventDefault();
                var leave_id = $(this).data('id');
                if( leave_id == '' ){
                    return false;
                }
                var data = {
                    'leave_id'        :   leave_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/administrative/get-leave-request'); ?>",
                    data: data,
                    success: function(response){
                        console.log(response);
                        if( response.response_type == 'error' ){
                            if( response.response_description.length ){
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        }

                        if( response.response_type == 'success' ){
                            if( typeof response.response_data.leave_data != 'undefined' ){
                                var leave_data = response.response_data.leave_data;
                                $("form#reject_leave_request").find('small.error-text').html('');
                                $("form#reject_leave_request").find('input[name="leave_id"]').val(leave_data.leave_request_id);
                                $("form#reject_leave_request").find('textarea#remarks').html(leave_data.remarks);
                                console.log(leave_data);
                                $.each(leave_data, function(index, value){
                                    $("form#reject_leave_request").find('strong#'+index).html(value);
                                    $("form#reject_leave_request").find('small#'+index).html(value);
                                });
                                $("#reject_leave_request_modal").modal('show');
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    }
                })
            })
            //end::get leave to reject

            //begin::Reject Leave  Ajax
            $(document).on('click', '#reject_leave_request_submit_button', function(e){
                e.preventDefault();
                var form = $('#reject_leave_request');
                form.closest('.modal').modal('hide');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Reject it!',
                    cancelButtonText: 'No, Go Back!',
                    customClass: { confirmButton: "btn btn-sm btn-primary", cancelButton: "btn btn-sm btn-secondary" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        var data = new FormData(form[0]);
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('ajax/administrative/reject-leave-request'); ?>",
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(response){
                                console.log(response);
                                if( response.response_type == 'error' ){
                                    if( response.response_description.length ){
                                        Swal.fire({
                                            html: response.response_description,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function (e) {
                                            if( typeof response.response_data.validation != 'undefined' ){
                                                var validation = response.response_data.validation;
                                                $.each(validation, function(index, value){
                                                    form.find('#'+index+'_error').html(value);
                                                });
                                                form.closest('.modal').modal('show');
                                            }
                                        });
                                    }
                                }

                                if( response.response_type == 'success' ){
                                    if( response.response_description.length ){
                                        Swal.fire({
                                            html: response.response_description,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function (e) {
                                            form[0].reset();
                                            $("#leave_approval_table").DataTable().ajax.reload();
                                        });
                                    }
                                }
                            },
                            failed: function(){
                                Swal.fire({
                                    html: "Ajax Failed, Please contact administrator",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        })
                    }else{
                        form.closest('.modal').modal('show');
                    }
                })
            })
            //end::Reject Leave  Ajax

            

            //begin::get leave to cancel
            $(document).on('click', '.cancel-leave', function(e){
                e.preventDefault();
                var leave_id = $(this).data('id');
                if( leave_id == '' ){
                    return false;
                }
                var data = {
                    'leave_id'        :   leave_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/administrative/get-leave-request'); ?>",
                    data: data,
                    success: function(response){
                        console.log(response);
                        if( response.response_type == 'error' ){
                            if( response.response_description.length ){
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        }

                        if( response.response_type == 'success' ){
                            if( typeof response.response_data.leave_data != 'undefined' ){
                                var leave_data = response.response_data.leave_data;
                                $("form#cancel_leave_request").find('small.error-text').html('');
                                $("form#cancel_leave_request").find('input[name="leave_id"]').val(leave_data.leave_request_id);
                                $("form#cancel_leave_request").find('textarea#remarks').html(leave_data.remarks);
                                console.log(leave_data);
                                $.each(leave_data, function(index, value){
                                    $("form#cancel_leave_request").find('strong#'+index).html(value);
                                    $("form#cancel_leave_request").find('small#'+index).html(value);
                                });
                                $("#cancel_leave_request_modal").modal('show');
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    }
                })
            })
            //end::get leave to cancel

            //begin::Cancel Leave  Ajax
            $(document).on('click', '#cancel_leave_request_submit_button', function(e){
                e.preventDefault();
                var form = $('#cancel_leave_request');
                form.closest('.modal').modal('hide');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel it!',
                    cancelButtonText: 'No, Go Back!',
                    customClass: { confirmButton: "btn btn-sm btn-primary", cancelButton: "btn btn-sm btn-secondary" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        var data = new FormData(form[0]);
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('ajax/administrative/cancel-leave-request'); ?>",
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(response){
                                console.log(response);
                                if( response.response_type == 'error' ){
                                    if( response.response_description.length ){
                                        Swal.fire({
                                            html: response.response_description,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function (e) {
                                            if( typeof response.response_data.validation != 'undefined' ){
                                                var validation = response.response_data.validation;
                                                $.each(validation, function(index, value){
                                                    form.find('#'+index+'_error').html(value);
                                                });
                                                form.closest('.modal').modal('show');
                                            }
                                        });
                                    }
                                }

                                if( response.response_type == 'success' ){
                                    if( response.response_description.length ){
                                        Swal.fire({
                                            html: response.response_description,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function (e) {
                                            form[0].reset();
                                            $("#leave_approval_table").DataTable().ajax.reload();
                                        });
                                    }
                                }
                            },
                            failed: function(){
                                Swal.fire({
                                    html: "Ajax Failed, Please contact administrator",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        })
                    }else{
                        form.closest('.modal').modal('show');
                    }
                })
            })
            //end::Cancel Leave  Ajax 

        })
    </script>

    

    <?= $this->endSection() ?>
<?= $this->endSection() ?>