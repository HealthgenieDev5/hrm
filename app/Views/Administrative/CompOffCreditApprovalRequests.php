<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<!--begin::Row-->
<div class="row gy-5 g-xl-8">
    <?php if (!in_array(session()->get('current_user')['employee_id'], ['1', '54'])) { ?>
        <div class="col-xl-12">
            <div class="row g-xl-8">
                <!--begin::Pending from Manu Card-->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center">
                                <div class="text-center pe-4">
                                    <h1 class="fw-bolder text-primary mb-0">
                                        <span id="pending_from_manu_count">
                                            <span class="spinner-border spinner-border-sm align-middle"></span>
                                        </span>
                                    </h1>
                                </div>
                                <div class="border-start border-3 border-primary ps-4 flex-grow-1">
                                    <div class="fw-bold text-dark fs-5">Pending from <span class="text-primary">Manu Grover</span></div>
                                    <small class="text-muted">Stage 1 approved requests awaiting final approval</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Pending from Manu Card-->

                <!--begin::Pending from Aryan Card-->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center">
                                <div class="text-center pe-4">
                                    <h1 class="fw-bolder text-info mb-0">
                                        <span id="pending_from_aryan_count">
                                            <span class="spinner-border spinner-border-sm align-middle"></span>
                                        </span>
                                    </h1>
                                </div>
                                <div class="border-start border-3 border-info ps-4 flex-grow-1">
                                    <div class="fw-bold text-dark fs-5">Pending from <span class="text-info">Aryan Grover</span></div>
                                    <small class="text-muted">Stage 1 approved requests (HN) awaiting final approval</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Pending from Aryan Card-->
            </div>
        </div>
    <?php } ?>

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
                        <select class="form-select form-select-sm" id="company" name="company[]" multiple data-control="select2" data-placeholder="Select a Company">
                            <option value=""></option>
                            <option value="all_companies" <?php echo (isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array('all_companies', $_REQUEST['company'])) ? 'selected' : ''; ?>>All Companies</option>
                            <?php
                            if (isset($Companies) && !empty($Companies)) {
                                foreach ($Companies as $company_row) {
                            ?>
                                    <option value="<?php echo $company_row['id']; ?>" <?php echo (isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array($company_row['id'], $_REQUEST['company']) && !in_array('all_companies', $_REQUEST['company'])) ? 'selected' : ''; ?>><?php echo $company_row['company_name']; ?></option>
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
                        <select class="form-select form-select-sm" id="department" name="department[]" multiple data-control="select2" data-placeholder="Select a Department">
                            <option value=""></option>
                            <option value="all_departments" <?php echo (isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array('all_departments', $_REQUEST['department'])) ? 'selected' : ''; ?>>All Departments</option>
                            <?php
                            if (isset($Departments) && !empty($Departments)) {
                                foreach ($Departments as $department_row) {
                            ?>
                                    <option value="<?php echo $department_row['id']; ?>" <?php echo (isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array($department_row['id'], $_REQUEST['department']) && !in_array('all_departments', $_REQUEST['department'])) ? 'selected' : ''; ?>><?php echo $department_row['department_name'] . ' - ' . $department_row['company_short_name']; ?></option>
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
                        <select class="form-select form-select-sm" id="employee" name="employee[]" multiple data-control="select2" data-placeholder="Select an Employee">
                            <option value=""></option>
                            <option value="all_employees" <?php echo (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array('all_employees', $_REQUEST['employee'])) ? 'selected' : ''; ?>>All Employees</option>
                            <?php
                            if (isset($Employees) && !empty($Employees)) {
                                foreach ($Employees as $employee_row) {
                            ?>
                                    <option value="<?php echo $employee_row['id']; ?>" <?php echo (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array($employee_row['id'], $_REQUEST['employee']) && !in_array('all_employees', $_REQUEST['employee'])) ? 'selected' : ''; ?>><?php echo $employee_row['employee_name'] . '(' . $employee_row['internal_employee_id'] . ') - ' . $employee_row['department_name'] . ' -' . $employee_row['company_short_name']; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <small class="text-danger error-text" id="employee_error"></small>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label" for="reporting_to_me">Reporting To me</label>
                        <select class="form-select form-select-sm" id="reporting_to_me" name="reporting_to_me" data-control="select2" data-placeholder="Select an option">
                            <option value="no rule" <?php echo (isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'no rule') ? 'selected' : ''; ?>>Show All</option>
                            <option value="yes" <?php echo (isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'yes') ? 'selected' : ''; ?>>Show Reporting to me</option>
                            <option value="no" <?php echo (isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'no') ? 'selected' : ''; ?>>Hide Reporting to me</option>
                        </select>
                        <small class="text-danger error-text" id="reporting_to_me_error"></small>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label" for="status" class="mb-3">Status</label>
                        <select class="form-select form-select-sm" id="status" name="status[]" multiple data-control="select2" data-placeholder="Select Status">
                            <option value=""></option>
                            <option value="all_status">All Status</option>
                            <option value="pending" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('pending', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>
                                <?php
                                if (session()->get('current_user')['employee_id'] == '1') {
                                    echo "Pending from HOD";
                                } elseif (session()->get('current_user')['employee_id'] == '54') {
                                    echo "Pending from HOD";
                                } else {
                                    echo "Pending";
                                }
                                ?>
                            </option>
                            <option value="stage_1" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('stage_1', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>
                                <?php
                                if (session()->get('current_user')['employee_id'] == '1') {
                                    echo "Pending from Manu";
                                } elseif (session()->get('current_user')['employee_id'] == '54') {
                                    echo "Pending from Aryan";
                                } else {
                                    echo "Approved by HOD";
                                }
                                ?>
                            </option>
                            <option value="approved" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('approved', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>
                                <?php
                                if (session()->get('current_user')['employee_id'] == '1') {
                                    echo "Approved by Manu";
                                } elseif (session()->get('current_user')['employee_id'] == '54') {
                                    echo "Approved by Aryan";
                                } else {
                                    echo "Approved";
                                }
                                ?>
                            </option>

                            <?php if (session()->get('current_user')['employee_id'] == '1') { ?>
                                <option value="stage_1_aryan" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('stage_1_aryan', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>Pending from Aryan</option>
                                <option value="approved_aryan" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('approved_aryan', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>Approved by Aryan</option>
                            <?php } ?>

                            <option value="rejected" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('rejected', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>Rejected</option>
                            <option value="cancelled" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('cancelled', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>Cancelled</option>
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
                            <!-- <input type="text" id="date_range_for_filter" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select date range" value="<?php #echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['from_date'].' to '.$_REQUEST['to_date'] : first_date_2_months_ago().' to '.last_date_of_month(); 
                                                                                                                                                                                    ?>" />
                                <input type="hidden" id="from_date" name="from_date" value="<?php #echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['from_date'] : first_date_2_months_ago(); 
                                                                                            ?>" />
                                <input type="hidden" id="to_date" name="to_date" value="<?php #echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['to_date'] : last_date_of_month(); 
                                                                                        ?>" /> -->

                            <input type="text" id="date_range_for_filter" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select date range" value="<?php echo (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) ? $_REQUEST['from_date'] . ' to ' . $_REQUEST['to_date'] : date('Y-m-d', strtotime('-90 days')) . ' to ' . date('Y-m-d'); ?>" />
                            <input type="hidden" id="from_date" name="from_date" value="<?php echo (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) ? $_REQUEST['from_date'] : date('Y-m-d', strtotime('-90 days')); ?>" />
                            <input type="hidden" id="to_date" name="to_date" value="<?php echo (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) ? $_REQUEST['to_date'] : date('Y-m-d'); ?>" />
                        </div>
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
                <h3 class="card-title">COMP OFF Credit Requests</h3>
                <div class="modal fade" tabindex="-1" id="update_comp_off_credit_request_modal">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form id="update_comp_off_credit_request" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title">Approve/Reject COMPOFF Credit Request</h5>
                                    <div class="d-flex flex-column align-items-center justify-content-start">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                <strong class="ms-4 text-primary opacity-75">
                                                    <strong id="employee_name"></strong> &nbsp; <small>(<strong id="department_name"></strong> - <strong id="company_short_name"></strong>)</small>
                                                </strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="hidden" id="comp_off_credit_request_id" name="comp_off_credit_request_id" value="" />
                                            <small class="text-danger error-text" id="comp_off_credit_request_id_error"><?= isset($validation) ? display_error($validation, 'comp_off_credit_request_id') : '' ?></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Req. ID</label>
                                                    <strong id="comp_off_credit_request_id" class="ms-4 text-primary opacity-75"></strong>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Working Date</label>
                                                    <div class="d-flex gap-2 ">
                                                        <strong id="working_day" class="fs-6 text-info opacity-75"></strong>
                                                        <strong id="day_status" class="fs-6 text-info opacity-75"></strong>
                                                        <strong id="working_date" class="fs-6 text-info opacity-75"></strong>
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Shift Hours</label>
                                                    <strong id="shift_hours" class="ms-4 badge badge-success rounded-pill text-capitalize"></strong>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Work Hours <small>(includes OD)</small></label>
                                                    <strong id="total_work_hours" class="ms-4 badge badge-info rounded-pill text-capitalize"></strong>
                                                </li>
                                                <li class="list-group-item d-flex flex-column">
                                                    <label class="form-label">Reason</label>
                                                    <p id="reason" class=""></p>
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
                                                    <label class="form-label mb-0">Req. On</label>
                                                    <strong id="date_time" class="ms-4 text-pink opacity-75"></strong>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Assinged By</label>
                                                    <strong id="assigned_by_name" class="ms-4 opacity-75"></strong>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Rep. Mgr.</label>
                                                    <strong id="reporting_manager_name" class="ms-4 opacity-75"></strong>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">HOD</label>
                                                    <strong id="hod_name" class="ms-4 text-muted"></strong>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-lg-6">
                                            <ul class="list-group list-group-flush">
                                                <!-- <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Shift Start</label>
                                                        <strong id="shift_start" class="ms-4 opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Punch IN</label>
                                                        <strong id="in_time__Raw" class="ms-4 opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Punch&OD IN</label>
                                                        <strong id="in_time_including_od" class="ms-4 badge badge-success rounded-pill text-capitalize"></strong>
                                                    </li> -->
                                            </ul>
                                        </div>

                                        <div class="col-lg-6">
                                            <ul class="list-group list-group-flush">
                                                <!-- <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Shift End</label>
                                                        <strong id="shift_end" class="ms-4 opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Punch OUT</label>
                                                        <strong id="out_time__Raw" class="ms-4 opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Punch&OD OUT</label>
                                                        <strong id="out_time_including_od" class="ms-4 badge badge-success rounded-pill text-capitalize"></strong>
                                                    </li> -->
                                            </ul>
                                        </div>

                                        <hr>

                                        <?php if (session()->get('current_user')['employee_id'] == '1') { ?>
                                            <div class="col-lg-12">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex flex-column">
                                                        <label class="form-label mb-0 text-success">HOD Remarks</label>
                                                        <p id="stage_1_remarks" class=""></p>
                                                        <p><small class="d-block"><strong>By:</strong> <strong class="text-danger" id="stage_1_reviewed_by_name"></strong></small></p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <hr>
                                        <?php } ?>

                                        <div class="col-lg-12">
                                            <div class="px-3 d-flex flex-column flex-lg-row justify-content-between gap-5">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <label class="form-label">Exchange</label> <small class="text-muted ms-3">Required</small>
                                                        <div class="d-flex align-items-center justify-content-start" id="exchange_pills_container">
                                                            <label for="exchange_halfday" class="me-3 mb-2 badge badge-primary opacity-50 rounded-pill text-capitalize cursor-pointer overflow-hidden">
                                                                <input type="radio" name="exchange" id="exchange_halfday" value="0.5" style="opacity: 0; margin-left: -14px;" />
                                                                Half Day
                                                            </label>
                                                            <label for="exchange_fullday" class="me-3 mb-2 badge badge-primary opacity-50 rounded-pill text-capitalize cursor-pointer overflow-hidden">
                                                                <input type="radio" name="exchange" id="exchange_fullday" value="1" style="opacity: 0; margin-left: -14px;" />
                                                                Full Day
                                                            </label>
                                                            <label for="exchange_none" class="me-3 mb-2 badge badge-primary opacity-50 rounded-pill text-capitalize cursor-pointer overflow-hidden">
                                                                <input type="radio" id="exchange_none" name="exchange" value="0" style="opacity: 0; margin-left: -8px; width: 0px; padding: 0px; border: 0;" />
                                                                None
                                                            </label>
                                                        </div>
                                                        <small class="text-danger error-text" id="exchange_error"><?= isset($validation) ? display_error($validation, 'exchange') : '' ?></small>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center gap-3">
                                                        <div class="d-flex flex-column">
                                                            <label class="form-label mb-0">Hours</label>
                                                            <small class="fw-normal text-muted">(Optional)</small>
                                                        </div>
                                                        <input class="form-control form-control-sm" type="text" name="minutes" id="minutes" data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="__:__" style="width: 65px;" />
                                                        <small class="text-danger error-text" id="minutes_error"><?= isset($validation) ? display_error($validation, 'minutes') : '' ?></small>
                                                    </li>
                                                </ul>
                                                <ul class="list-group list-group-flush flex-grow-1">
                                                    <li class="list-group-item">
                                                        <label class="form-label">Remarks</label> <small class="text-muted ms-3">(Required if rejecting)</small>
                                                        <?php
                                                        if (session()->get('current_user')['employee_id'] == '1') {
                                                        ?>
                                                            <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <textarea class="form-control" id="stage_1_remarks" name="stage_1_remarks" rows="3"></textarea>
                                                        <?php
                                                        }
                                                        ?>
                                                        <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer flex-column align-items-end">
                                    <div class="d-flex align-items-center justify-content-end m-0">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" style="margin: .25rem;">Close</button>
                                        <button id="approve_comp_off_credit_request_submit_button" data-status="<?php echo (session()->get('current_user')['employee_id'] == '1' || session()->get('current_user')['employee_id'] == '54') ? 'approved' : 'stage_1'; ?>" style="margin: .25rem;" class="btn btn-sm btn-primary update_comp_off_credit_request_submit_button">Approve</button>
                                        <button id="reject_comp_off_credit_request_submit_button" data-status="rejected" style="margin: .25rem;" class="btn btn-sm btn-warning update_comp_off_credit_request_submit_button">Reject</button>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-end m-0">
                                        <small class="text-danger error-text" id="status_error"><?= isset($validation) ? display_error($validation, 'status') : '' ?>dfghd gfh dgfh fghj fghjfgh</small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cancel CompOff Credit Request Modal -->
                <div class="modal fade" tabindex="-1" id="cancel_comp_off_credit_request_modal">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form id="cancel_comp_off_credit_request" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title">Cancel COMPOFF Credit Request</h5>
                                    <div class="d-flex flex-column align-items-center justify-content-start">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                <strong class="ms-4 text-primary opacity-75">
                                                    <strong id="cancel_employee_name"></strong> &nbsp; <small>(<strong id="cancel_department_name"></strong> - <strong id="cancel_company_short_name"></strong>)</small>
                                                </strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="hidden" id="cancel_comp_off_credit_request_id" name="comp_off_credit_request_id" value="" />
                                            <small class="text-danger error-text" id="cancel_comp_off_credit_request_id_error"></small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Req. ID</label>
                                                    <strong id="cancel_comp_off_credit_request_display_id" class="ms-4 text-primary opacity-75"></strong>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Working Date</label>
                                                    <div class="d-flex gap-2">
                                                        <strong id="cancel_working_day" class="fs-6 text-info opacity-75"></strong>
                                                        <strong id="cancel_working_date" class="fs-6 text-info opacity-75"></strong>
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                                    <label class="form-label mb-0">Status</label>
                                                    <strong id="cancel_status" class="ms-4 badge badge-info rounded-pill text-capitalize"></strong>
                                                </li>
                                                <li class="list-group-item d-flex flex-column">
                                                    <label class="form-label">Reason</label>
                                                    <p id="cancel_reason" class=""></p>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-lg-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex flex-column">
                                                    <label class="form-label">Cancellation Remarks</label>
                                                    <small class="text-muted mb-2">Required - Please specify reason for cancellation</small>
                                                    <textarea class="form-control" id="cancellation_remarks" name="cancellation_remarks" rows="4" placeholder="Why are you cancelling this request?"></textarea>
                                                    <small class="text-danger error-text" id="cancellation_remarks_error"></small>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                    <button id="cancel_comp_off_credit_request_submit_button" type="button" class="btn btn-sm btn-danger">
                                        <i class="fa fa-ban me-2"></i>Cancel Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body">
                <table id="comp_off_credit_approval_requests_table" class="table table-custom table-hover table-row-dashed nowrap">
                    <thead class="bg-white">
                        <tr>
                            <!-- <th class="text-center bg-white"><strong>ID</strong></th> -->
                            <th class="text-center bg-white"><strong>Employee</strong></th>
                            <!-- <th class="text-center bg-white"><strong>Department</strong></th> -->
                            <th class="text-center"><strong>Date</strong></th>
                            <th class="text-center"><strong>Shift / Working hours</strong></th>
                            <!-- <th class="text-center"><strong>Assigned By</strong></th> -->
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Exchange</strong></th>
                            <!-- <th class="text-center"><strong>Reporting to</strong></th> -->
                            <!-- <th class="text-center"><strong>Department HOD</strong></th> -->
                            <!-- <th class="text-center"><strong>Company HOD</strong></th> -->
                            <!-- <th class="text-center"><strong>Requested on</strong></th> -->
                            <th class="text-center"><strong>HOD Approval</strong><br><small>& Remarks</small></th>
                            <th class="text-center"><strong>Final Approval</strong><br><small>& Remarks</small></th>
                            <!-- <th class="text-center"><strong>Reviewed Date</strong></th> -->
                            <!-- <th class="text-center"><strong>Remarks</strong></th> -->
                            <th class="text-center bg-white"><strong>Actions</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <!-- <th class="text-center bg-white"><strong>ID</strong></th> -->
                            <th class="text-center bg-white"><strong>Employee</strong></th>
                            <!-- <th class="text-center bg-white"><strong>Department</strong></th> -->
                            <th class="text-center"><strong>Date</strong></th>
                            <th class="text-center"><strong>Shift / Working hours</strong></th>
                            <!-- <th class="text-center"><strong>Assigned By</strong></th> -->
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Exchange</strong></th>
                            <!-- <th class="text-center"><strong>Reporting to</strong></th> -->
                            <!-- <th class="text-center"><strong>Department HOD</strong></th> -->
                            <!-- <th class="text-center"><strong>Company HOD</strong></th> -->
                            <!-- <th class="text-center"><strong>Requested on</strong></th> -->
                            <th class="text-center"><strong>HOD Approval</strong><br><small>& Remarks</small></th>
                            <th class="text-center"><strong>Final Approval</strong><br><small>& Remarks</small></th>
                            <!-- <th class="text-center"><strong>Reviewed Date</strong></th> -->
                            <!-- <th class="text-center"><strong>Remarks</strong></th> -->
                            <th class="text-center bg-white"><strong>Actions</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
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
<style>
    /*span.form-select{
        background: transparent;
        border: none;
        padding-right: 1.1rem;
    }
    span.form-select>.select2-selection__rendered{
        color: #3f4254 !important;
    }
    span.select2-dropdown{
        width: auto !important;
    }
    .select2-container--bootstrap5 .select2-dropdown .select2-results__option.select2-results__option--selected {
        background-image: unset;
    }*/
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var getDepartment = true;
        var getEmployee = true;
        <?php if (!in_array(session()->get('current_user')['employee_id'], ['54'])) { ?>

            loadPendingCounts();

            function loadPendingCounts() {
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/backend/administrative/get-comp-off-pending-counts'); ?>",
                    success: function(response) {
                        if (response.response_type == 'success') {
                            $('#pending_from_manu_count').html(response.pending_from_manu || 0);
                            $('#pending_from_aryan_count').html(response.pending_from_aryan || 0);
                        } else {
                            $('#pending_from_manu_count').html('0');
                            $('#pending_from_aryan_count').html('0');
                        }
                    },
                    error: function() {
                        $('#pending_from_manu_count').html('-');
                        $('#pending_from_aryan_count').html('-');
                    }
                });
            }
        <?php  } ?>


        $(document).on('change', '#company', function() {
            var company = $('#company').val();
            $('#department_error').html('');
            $('#employee_error').html('');
            $('#department').parent().find('.select2-selection').addClass('loading');
            $('#department').val(null).trigger('change');
            $('#employee').val(null).trigger('change');
            if (getDepartment == true) {
                getDepatmentByCompany($('#company').val(), true).then(function() {
                    $('#department').parent().find('.select2-selection').removeClass('loading');
                    getDepartment = false;
                });
            } else {
                getDepatmentByCompany($('#company').val(), false).then(function() {
                    $('#department').parent().find('.select2-selection').removeClass('loading');
                });
            }
        })
        $(document).on('change', '#department', function() {
            var department = $('#department').val();
            if (jQuery.inArray("all_departments", department) !== -1 && department.length > 1) {
                $('#department').select2("val", ['all_departments']);
            }
            $('#employee_error').html('');
            $('#employee').parent().find('.select2-selection').addClass('loading');
            $('#employee').val(null).trigger('change');
            if (getEmployee == true) {
                getEmployeesByDepatment($('#department').val(), true).then(function() {
                    $('#employee').parent().find('.select2-selection').removeClass('loading');
                    getEmployee = false;
                });
            } else {
                getEmployeesByDepatment($('#department').val(), false).then(function() {
                    $('#employee').parent().find('.select2-selection').removeClass('loading');
                });
            }
        })
        $(document).on('change', '#employee', function() {
            var employee = $('#employee').val();
            if (jQuery.inArray("all_employees", employee) !== -1 && employee.length > 1) {
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
        $(document).on('change', '#date_range_for_filter', function(e) {
            console.log($(this).val());
            var date_range_for_filter = $(this).val();
            date_range_for_filter_array = date_range_for_filter.split('to');
            console.log(date_range_for_filter_array);
            if (date_range_for_filter_array.length == 1) {
                $('#from_date').val(date_range_for_filter_array[0]);
                $('#to_date').val(date_range_for_filter_array[0]);
            } else if (date_range_for_filter_array.length == 2) {
                $('#from_date').val(date_range_for_filter_array[0]);
                $('#to_date').val(date_range_for_filter_array[1]);
            }
        })
        const getDepatmentByCompany = async (company_id, preserve_get_param = true) => {
            $('#department').html('<option></option>');
            $('#department').append('<option value="all_departments">All Departments</option>');
            var data = {
                'company_id': company_id,
            };
            return $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/backend/reports/get-department-by-company-id'); ?>",
                data: data,
                success: function(response) {
                    if (response.response_type == 'error') {
                        $('#department_error').html(response.response_description);
                    }

                    if (response.response_type == 'success') {
                        if (typeof response.response_data.departments != 'undefined') {
                            var department_data = response.response_data.departments;
                            $.each(department_data, function(index, department) {
                                $('#department').append('<option value="' + department.id + '" >' + department.department_name + ' - ' + department.company_short_name + '</option>');
                            });
                            if (preserve_get_param === true) {
                                var department_from_url = [<?php echo isset($_REQUEST["department"]) && !empty($_REQUEST["department"]) ? '"' . implode('","', $_REQUEST["department"]) . '"' : null; ?>];
                                $('#department').val(department_from_url).trigger('change');
                            }

                        }
                    }
                },
                failed: function() {
                    Swal.fire({
                        html: "Ajax Failed while loading departments conditionally, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                }
            });
        }
        const getEmployeesByDepatment = async (department_id, preserve_get_param = true) => {
            $('#employee').val(null).trigger('change');
            $('#employee').html('<option></option>');
            $('#employee').append('<option value="all_employees">All Employees</option>');
            var data = {
                'department_id': department_id,
            };
            return $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/backend/reports/get-employees-by-department-id'); ?>",
                data: data,
                success: function(response) {
                    if (response.response_type == 'error') {
                        $('#employee_error').html(response.response_description);
                    }

                    if (response.response_type == 'success') {
                        if (typeof response.response_data.employees != 'undefined') {
                            var employee_data = response.response_data.employees;
                            $.each(employee_data, function(index, employee) {
                                // $('#employee').append('<option value="'+employee.id+'" >'+employee.employee_name+' ('+employee.internal_employee_id+') - '+employee.department_name+' - '+employee.company_short_name+'</option>');
                                $('#employee').append(`<option value="${employee.id}" >${employee.employee_name} (${employee.internal_employee_id}) - ${employee.department_name} - ${employee.company_short_name} ${employee.status != 'active' ? ' --'+employee.status : ''}</option>`);
                            });
                            if (preserve_get_param === true) {
                                var employee_from_url = [<?php echo isset($_REQUEST["employee"]) && !empty($_REQUEST["employee"]) ? '"' . implode('","', $_REQUEST["employee"]) . '"' : null; ?>];
                                $('#employee').val(employee_from_url);
                            }
                        }
                    }
                },
                failed: function() {
                    Swal.fire({
                        html: "Ajax Failed while loading employees conditionally, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                }
            });
        }

        //begin::Initialize Datatable
        var table = $("#comp_off_credit_approval_requests_table").DataTable({
            //"dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rtl',
            "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
            "paging": false,
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,

            "ajax": {
                url: "<?= base_url('ajax/backend/administrative/get-all-comp-off-credit-approval-requests') ?>",
                type: "POST",
                data: {
                    filter: function() {
                        return $('#filter_form').serialize();
                    }
                },
                dataSrc: "",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
            },
            "columns": [
                // { data: "id" },
                {
                    data: "employee_name",
                    render: function(data, type, row, meta) {
                        return '<div class="mx-auto d-flex flex-column text-wrap" style="width: max-content; max-width: 200px;" ><strong>' + data + ' (' + row.internal_employee_id + ')</strong><small class="mb-2">' + row.department_name + ' (' + row.company_short_name + ')</small><small style="">Reports to: <strong>' + row.reporting_manager_name + '</strong></small><small>Company HOD: <strong>' + row.company_hod_name + '</strong></small></div>';
                    }
                },
                // { data: "department_name" },
                {
                    data: {
                        _: 'working_date.formatted',
                        sort: 'working_date.ordering',
                    },
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return '<span class="d-block badge text-capitalize rounded-pill text-dark border">' + row.working_day + row.day_status + '</span><strong class="d-block">' + data + '</strong>';
                        }
                        return data;
                    }
                    // ,render: function (data, type, row, meta){
                    //     return '<strong>'+data+'</strong>';
                    // }
                },
                {
                    data: "shift_hours",
                    render: function(data, type, row) {
                        let shift_hours_html = row.shift_hours ? `<p class="mb-1 text-center"><strong>Shift Hours:</strong> <strong class="">${row.shift_hours}</strong></p>` : ``;
                        let work_hours_html = row.total_work_hours ? `<p class="text-center"><strong>Work Hours:</strong> <strong class="">${row.total_work_hours}</strong></p>` : ``;
                        return `<div class="text-wrap mx-auto mb-0 lh-sm" style="">${shift_hours_html}${work_hours_html}</div>`;
                    }
                },
                // { 
                //     data: "assigned_by_name", 
                //     render: function (data, type, row, meta){
                //         return '<p class="text-wrap mx-auto small mb-0" style="width: max-content; max-width: 150px;">'+data+'</p>';
                //     }
                // },
                // { 
                //     data: "reason",
                //     render: function (data, type, row, meta){
                //         return '<p class="text-wrap mx-auto mb-0" style="width: max-content; max-width: 350px;">'+data+'</p>';
                //     }
                // },
                {
                    data: {
                        _: 'date_time.formatted',
                        sort: 'date_time.ordering',
                    },
                    render: function(data, type, row, meta) {
                        let reason_html = row.reason ? `<p class="d-block text-center ${row.attachment ? 'mb-1' : 'mb-3'}">${row.reason}</p>` : ``;
                        let attachment_html = row.attachment ? `<p class="d-block text-center mb-3"><a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="<?php echo base_url(); ?>${row.attachment}" target="_blank">View Attachment</a></p>` : ``;
                        // let attachment_html = `<p class="d-block text-center mb-3"><a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="https://getbootstrap.com/docs/5.0/components/modal/" target="_blank">View Attachment</a></p>`;
                        let assigned_by_name_html = row.assigned_by_name ? `<small class="d-block text-center"><strong>Assigned By:</strong> <strong class="text-danger">${row.assigned_by_name}</strong></small>` : ``;
                        let date_time_html = row.date_time.formatted ? `<small class="d-block text-center">Requested on:<strong class="text-danger"> ${row.date_time.formatted}</strong></small>` : ``;

                        return `<div class="text-wrap mx-auto mb-0 lh-sm" style="width: max-content; max-width: 350px;">${reason_html}${attachment_html}${assigned_by_name_html}${date_time_html}</div>`;
                    }
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        var badge_class = "bg-secondary text-dark";
                        if (data == 'rejected') {
                            badge_class = "bg-danger text-danger bg-opacity-15";
                        } else if (data == 'cancelled') {
                            badge_class = "bg-warning text-warning bg-opacity-15";
                        } else if (data == 'approved') {
                            badge_class = "bg-success text-success bg-opacity-15";
                        } else if (data == 'stage_1') {
                            badge_class = "bg-info text-info bg-opacity-15";
                        }
                        let badge_status = data;
                        if (data == 'stage_1') {
                            // badge_status = 'Stage 1';
                            badge_status = 'Approved by HOD';
                        } else if (data == 'approved') {
                            if (row.reviewed_by == 54) {
                                badge_status = 'Approved by Aryan';
                            } else {
                                badge_status = 'Approved by Manu';
                            }
                        } else if (data == 'cancelled') {
                            badge_status = 'Cancelled';
                        } else {

                        }
                        return '<span class="badge text-capitalize rounded-pill ' + badge_class + '">' + badge_status + '</span>';
                    }
                },
                {
                    data: "exchange",
                    render: function(data, type, row) {
                        let exchange_html = '';
                        if (data == '0.5') {
                            exchange_html = `<p class="mb-1 text-center"><span>Comp Off:</span> <strong class="text-info">Half Day</strong></p>`;
                        } else if (data == '1') {
                            exchange_html = `<p class="mb-1 text-center"><span>Comp Off:</span> <strong class="text-info">Full Day</strong></p>`;
                        }
                        let minutes_html = row.minutes ? `<p class="mb-1 text-center"><span>Extra Minutes:</span> <strong class="text-info">${row.minutes}</strong></p>` : ``;

                        return `<div class="mx-auto text-wrap mb-0 lh-sm" style="width: max-content; max-width: 150px;">${exchange_html}${minutes_html}</div>`;
                    }
                },
                // { data: "reporting_manager_name" },
                /*{ data: "department_hod_name"},*/
                // { data: "company_hod_name"},
                // {
                //     data: {
                //         _: 'date_time.formatted',
                //         sort: 'date_time.ordering',
                //     },
                // },
                // { data: "reviewed_by_name" },
                {
                    // data: "reviewed_by_name",
                    data: {
                        _: 'stage_1_reviewed_date.formatted',
                        sort: 'stage_1_reviewed_date.ordering',
                    },
                    render: function(data, type, row, meta) {
                        let stage_1_remarks_html = row.stage_1_remarks ? `<p class="d-block text-center mb-2">${row.stage_1_remarks}</p>` : ``;
                        let stage_1_reviewed_by_html = row.stage_1_reviewed_by_name ? `<small class="d-block text-center"><strong>Approved By:</strong> <strong class="text-danger">${row.stage_1_reviewed_by_name}</strong></small>` : ``;
                        let stage_1_reviewed_date_html = row.stage_1_reviewed_date.formatted ? `<small class="d-block text-center">on: <strong class="text-danger">${row.stage_1_reviewed_date.formatted}</strong></small>` : ``;

                        return `<div class="text-wrap mx-auto mb-0 lh-sm" style="width:max-content; max-width: 220px;">${stage_1_remarks_html}${stage_1_reviewed_by_html}${stage_1_reviewed_date_html}</div>`;
                    }
                },
                {
                    // data: "reviewed_by_name",
                    data: {
                        _: 'reviewed_date.formatted',
                        sort: 'reviewed_date.ordering',
                    },
                    render: function(data, type, row, meta) {
                        let remarks_html = row.remarks ? `<p class="d-block text-center mb-2">${row.remarks}</p>` : ``;
                        let reviewed_by_html = row.reviewed_by_name ? `<small class="d-block text-center"><strong>Final Approval By:</strong> <strong class="text-danger">${row.reviewed_by_name}</strong></small>` : ``;
                        let reviewed_date_html = row.reviewed_date.formatted ? `<small class="d-block text-center">on: <strong class="text-danger">${row.reviewed_date.formatted}</strong></small>` : ``;

                        return `<div class="text-wrap mx-auto mb-0 lh-sm" style="width:max-content; max-width: 220px;">${remarks_html}${reviewed_by_html}${reviewed_date_html}</div>`;
                    }
                },
                // {
                //     data: {
                //         _: 'reviewed_date.formatted',
                //         sort: 'reviewed_date.ordering',
                //     },
                // },
                // { 
                //     data: "remarks",
                //     render: function (data, type, row, meta){
                //         return '<p class="text-wrap" style="width: 200px">'+data+'</p>';
                //     }
                // },
                // {
                //     data: "actions",
                //     render: function(data, type, row, meta) {
                //         var current_user_employee_id = "<?php echo session()->get('current_user')['employee_id']; ?>";

                //         if (current_user_employee_id == '1') {
                //             if (row.status !== 'pending' && row.status !== 'stage_1') {
                //                 var is_btn_disabled = ' disabled ';
                //                 var data_id = '';
                //                 var btn_opacity_class = ' opacity-25 ';
                //             } else {
                //                 var is_btn_disabled = '';
                //                 var data_id = row.id;
                //                 var btn_opacity_class = '';
                //             }
                //         } else {
                //             if (row.status !== 'pending') {
                //                 var is_btn_disabled = ' disabled ';
                //                 var data_id = '';
                //                 var btn_opacity_class = ' opacity-25 ';
                //             } else {
                //                 var is_btn_disabled = '';
                //                 var data_id = row.id;
                //                 var btn_opacity_class = '';
                //             }
                //         }

                //         var view_comp_off_credit_request_button = '<a href="#" class="btn btn-success open-comp-off-credit-request px-2 py-1' + btn_opacity_class + is_btn_disabled + '" data-id="' + data_id + '" data-action="approve">' +
                //             '<span class="svg-icon svg-icon-3 m-0">' +
                //             '&nbsp;<span class="fa fa-check me-2" aria-hidden="true" ></span>' +
                //             '</span>' +
                //             ' <span style="font-size:0.85rem">Approve &nbsp;&nbsp;&nbsp;&nbsp;</span> ' +
                //             '</a>';
                //         view_comp_off_credit_request_button += '<a href="#" class="btn btn-danger open-comp-off-credit-request px-2 py-1' + btn_opacity_class + is_btn_disabled + '" data-id="' + data_id + '" data-action="reject">' +
                //             '<span class="svg-icon svg-icon-3 m-0">' +
                //             '&nbsp;<span class="fa fa-times me-2" aria-hidden="true" ></span>' +
                //             '</span>' +
                //             ' <span style="font-size:0.85rem">Reject &nbsp;&nbsp;&nbsp;&nbsp;</span> ' +
                //             '</a>';
                //         var action = '<div class="btn-group">' + view_comp_off_credit_request_button + '</div>';

                //         // return (current_user_employee_id == '1' || current_user_employee_id == '40') ? action : 'Not Allowed';
                //         return action;
                //     }
                // },

                {
                    data: "actions",
                    render: function(data, type, row, meta) {
                        var current_user_employee_id = "<?php echo session()->get('current_user')['employee_id']; ?>";
                        var current_user_role = "<?php echo session()->get('current_user')['role']; ?>";

                        if (current_user_employee_id == '1' || current_user_employee_id == '54') {
                            if (row.status !== 'pending' && row.status !== 'stage_1') {
                                var is_btn_disabled = ' disabled ';
                                var data_id = '';
                                var btn_opacity_class = ' opacity-25 ';
                            } else {
                                var is_btn_disabled = '';
                                var data_id = row.id;
                                var btn_opacity_class = '';
                            }
                        } else {
                            if (row.status !== 'pending') {
                                var is_btn_disabled = ' disabled ';
                                var data_id = '';
                                var btn_opacity_class = ' opacity-25 ';
                            } else {
                                var is_btn_disabled = '';
                                var data_id = row.id;
                                var btn_opacity_class = '';
                            }
                        }

                        var view_comp_off_credit_request_button = '<a href="#" class="btn btn-success open-comp-off-credit-request px-2 py-1' + btn_opacity_class + is_btn_disabled + '" data-id="' + data_id + '" data-action="approve">' +
                            '<span class="svg-icon svg-icon-3 m-0">' +
                            '&nbsp;<span class="fa fa-check me-2" aria-hidden="true" ></span>' +
                            '</span>' +
                            ' <span style="font-size:0.85rem">Approve &nbsp;&nbsp;&nbsp;&nbsp;</span> ' +
                            '</a>';
                        view_comp_off_credit_request_button += '<a href="#" class="btn btn-danger open-comp-off-credit-request px-2 py-1' + btn_opacity_class + is_btn_disabled + '" data-id="' + data_id + '" data-action="reject">' +
                            '<span class="svg-icon svg-icon-3 m-0">' +
                            '&nbsp;<span class="fa fa-times me-2" aria-hidden="true" ></span>' +
                            '</span>' +
                            ' <span style="font-size:0.85rem">Reject &nbsp;&nbsp;&nbsp;&nbsp;</span> ' +
                            '</a>';

                        // Add cancel button for HR users on stage_1 requests (not their own)
                        var cancel_button = '';
                        if ((current_user_role == 'hr' || current_user_employee_id == 40) && row.status == 'stage_1' && row.employee_id != current_user_employee_id) {
                            cancel_button = '<a href="#" class="btn btn-warning cancel-comp-off-credit-request px-2 py-1" data-id="' + row.id + '">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '&nbsp;<span class="fa fa-ban me-2" aria-hidden="true" ></span>' +
                                '</span>' +
                                ' <span style="font-size:0.85rem">Cancel &nbsp;&nbsp;&nbsp;&nbsp;</span> ' +
                                '</a>';
                        }

                        var action = '<div class="btn-group">' + view_comp_off_credit_request_button + cancel_button + '</div>';

                        // return (current_user_employee_id == '1' || current_user_employee_id == '40') ? action : 'Not Allowed';
                        return action;
                    }
                },
            ],
            "order": [],
            "buttons": ['excel'],
            "fixedColumns": {
                left: 1,
                right: 1
            },
            "columnDefs": [{
                    "className": 'border-end border-secondary td-border-left text-center',
                    "targets": [0]
                },
                {
                    "className": 'border-start border-secondary td-border-left text-center',
                    "targets": [-1]
                },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
            ],
            "initComplete": function(settings, json) {
                <?php
                if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'approve' || $_REQUEST['action'] == 'reject') && isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
                ?>
                    var id = "<?php echo $_REQUEST['id']; ?>";
                    $("a.open-comp-off-credit-request[data-id=" + id + "]").trigger('click');
                <?php
                }
                ?>
            }
        });


        //end::Initialize Datatable


        //begin::get Gate Pass Request to approve
        $(document).on('click', '.open-comp-off-credit-request', function(e) {
            e.preventDefault();
            var comp_off_credit_request_id = $(this).data('id');
            var data_action = $(this).data('action');
            if (comp_off_credit_request_id == '') {
                return false;
            }
            var data = {
                'comp_off_credit_request_id': comp_off_credit_request_id,
            };

            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/backend/administrative/get-comp-off-credit-approval-request'); ?>",
                data: data,
                success: function(response) {
                    console.log(response);
                    // return false;
                    if (response.response_type == 'error') {
                        if (response.response_description.length) {
                            Swal.fire({
                                html: response.response_description,
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                            })
                        }
                    }

                    if (response.response_type == 'success') {
                        if (typeof response.response_data.comp_off_credit_request_data != 'undefined') {
                            var comp_off_credit_request_data = response.response_data.comp_off_credit_request_data;

                            console.log(comp_off_credit_request_data);

                            $("form#update_comp_off_credit_request").find('small.error-text').html('');
                            $("form#update_comp_off_credit_request").find('input[name="comp_off_credit_request_id"]').val(comp_off_credit_request_data.id);
                            let exchange = comp_off_credit_request_data.exchange;
                            if (exchange == '0.5') {
                                $("form#update_comp_off_credit_request").find('input#exchange_halfday').prop('checked', true).trigger('change');
                            } else if (exchange == '1') {
                                $("form#update_comp_off_credit_request").find('input#exchange_fullday').prop('checked', true).trigger('change');
                            } else {
                                $("form#update_comp_off_credit_request").find('input#exchange_none').prop('checked', true).trigger('change');
                            }

                            let exchange_minutes = comp_off_credit_request_data.minutes;
                            if (exchange_minutes) {
                                $("form#update_comp_off_credit_request").find('input[name="minutes"]').val(comp_off_credit_request_data.minutes);
                            } else {
                                $("form#update_comp_off_credit_request").find('input[name="minutes"]').val('');
                            }


                            $.each(comp_off_credit_request_data, function(index, value) {
                                $("form#update_comp_off_credit_request").find('strong#' + index).html(value);
                                $("form#update_comp_off_credit_request").find('small#' + index).html(value);
                                $("form#update_comp_off_credit_request").find('p#' + index).html(value);
                                $("form#update_comp_off_credit_request").find('textarea#' + index).html(value);
                                // $("form#update_comp_off_credit_request").find('#'+index).html(value);
                                if (index == 'status' && value == 'stage_1') {
                                    $("form#update_comp_off_credit_request").find('strong#status').html('Approved by HOD');
                                }
                            });
                            $("form#update_comp_off_credit_request").find('strong#comp_off_credit_request_id').html(comp_off_credit_request_data.id);



                            $("form#update_comp_off_credit_request").find('#approve_comp_off_credit_request_submit_button').show();
                            $("form#update_comp_off_credit_request").find('#reject_comp_off_credit_request_submit_button').show();
                            if (data_action == 'approve') {
                                $("form#update_comp_off_credit_request").find('#approve_comp_off_credit_request_submit_button').show();
                                $("form#update_comp_off_credit_request").find('#reject_comp_off_credit_request_submit_button').hide();
                            }
                            if (data_action == 'reject') {
                                $("form#update_comp_off_credit_request").find('#approve_comp_off_credit_request_submit_button').hide();
                                $("form#update_comp_off_credit_request").find('#reject_comp_off_credit_request_submit_button').show();
                            }


                            $("#update_comp_off_credit_request_modal").modal('show');
                        }
                    }
                },
                failed: function() {
                    Swal.fire({
                        html: "Ajax Failed, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                }
            })
        })
        //end::get leave to approve

        $(document).on('change', '#exchange_pills_container input', function(e) {
            if ($(this).prop('checked') == true) {
                $('#exchange_pills_container label').removeClass('badge-success').removeClass('selected').addClass('badge-primary').addClass('opacity-50');
                $(this).parent().removeClass('badge-primary').removeClass('opacity-50').removeClass('selected').addClass('badge-success').addClass('selected');
            }
        })

        //begin::Approve/Reject Leave  Ajax
        $(document).on('click', '.update_comp_off_credit_request_submit_button', function(e) {
            e.preventDefault();
            var status = $(this).data('status');
            if (status == 'rejected') {
                confirmButtonText_html = 'Reject';
            }
            if (status == 'approved') {
                confirmButtonText_html = 'Approve';
            }
            if (status == 'stage_1') {
                confirmButtonText_html = 'Approve';
            }
            /*console.log(action);
            return false;*/
            var form = $('#update_comp_off_credit_request');
            if (status == 'approved' || status == 'stage_1') {
                var exchangeValue = form.find('input[name="exchange"]:checked').val();
                var minutesValue = form.find('input[name="minutes"]').val();
                if ((exchangeValue == 0 || exchangeValue == '') && (!minutesValue || minutesValue.trim() == '')) {
                    Swal.fire({
                        html: "Please select either Exchange (Half Day/Full Day) or enter Minutes value.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    });
                    return false;
                }
            }

            form.closest('.modal').modal('hide');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, ' + confirmButtonText_html + ' it!',
                cancelButtonText: 'No, Go Back!',
                customClass: {
                    confirmButton: "btn btn-sm btn-primary",
                    cancelButton: "btn btn-sm btn-secondary"
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = new FormData(form[0]);
                    data.append('status', status);
                    $.ajax({
                        method: "post",
                        url: "<?php echo base_url('ajax/backend/administrative/update-comp-off-credit-approval-request'); ?>",
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response);

                            if (response.response_type == 'error') {
                                if (response.response_description.length) {
                                    Swal.fire({
                                        html: response.response_description,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        },
                                    }).then(function(e) {
                                        if (typeof response.response_data.validation != 'undefined') {
                                            var validation = response.response_data.validation;
                                            $.each(validation, function(index, value) {
                                                form.find('#' + index + '_error').html(value);
                                            });
                                            form.closest('.modal').modal('show');
                                        }
                                    });
                                }
                            }

                            if (response.response_type == 'success') {
                                if (response.response_description.length) {
                                    Swal.fire({
                                        html: response.response_description,
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        },
                                    }).then(function(e) {
                                        form[0].reset();
                                        $("#comp_off_credit_approval_requests_table").DataTable().ajax.reload();
                                    });
                                }
                            }
                        },
                        failed: function() {
                            Swal.fire({
                                html: "Ajax Failed, Please contact administrator",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                            })
                        }
                    })
                } else {
                    form.closest('.modal').modal('show');
                }
            })
        })
        //end::Approve/Reject Leave  Ajax      

        //begin::Cancel CompOff Credit Request
        $(document).on('click', '.cancel-comp-off-credit-request', function(e) {
            e.preventDefault();
            var comp_off_credit_request_id = $(this).data('id');

            if (comp_off_credit_request_id == '') {
                return false;
            }

            var data = {
                'comp_off_credit_request_id': comp_off_credit_request_id,
            };

            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/backend/administrative/get-comp-off-credit-approval-request'); ?>",
                data: data,
                success: function(response) {
                    if (response.response_type == 'error') {
                        if (response.response_description.length) {
                            Swal.fire({
                                html: response.response_description,
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                            })
                        }
                    }

                    if (response.response_type == 'success') {
                        if (typeof response.response_data.comp_off_credit_request_data != 'undefined') {
                            var comp_off_credit_request_data = response.response_data.comp_off_credit_request_data;

                            // Clear previous data and populate cancel modal
                            $("form#cancel_comp_off_credit_request").find('small.error-text').html('');
                            $("form#cancel_comp_off_credit_request").find('input[name="comp_off_credit_request_id"]').val(comp_off_credit_request_data.id);
                            $("form#cancel_comp_off_credit_request").find('textarea[name="cancellation_remarks"]').val('');

                            // Populate display fields with 'cancel_' prefix
                            $.each(comp_off_credit_request_data, function(index, value) {
                                $("form#cancel_comp_off_credit_request").find('strong#cancel_' + index).html(value);
                                $("form#cancel_comp_off_credit_request").find('p#cancel_' + index).html(value);
                            });

                            // Set display ID and status specifically
                            $("form#cancel_comp_off_credit_request").find('strong#cancel_comp_off_credit_request_display_id').html(comp_off_credit_request_data.id);
                            if (comp_off_credit_request_data.status == 'stage_1') {
                                $("form#cancel_comp_off_credit_request").find('strong#cancel_status').html('Approved by HOD');
                            }

                            $("#cancel_comp_off_credit_request_modal").modal('show');
                        }
                    }
                },
                failed: function() {
                    Swal.fire({
                        html: "Ajax Failed, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                }
            })
        });

        //begin::Cancel Request Submit
        $(document).on('click', '#cancel_comp_off_credit_request_submit_button', function(e) {
            e.preventDefault();

            var form = $('#cancel_comp_off_credit_request');
            var cancellation_remarks = form.find('textarea[name="cancellation_remarks"]').val();

            if (cancellation_remarks.trim() == '') {
                form.find('#cancellation_remarks_error').html('Please specify reason for cancellation');
                return false;
            }

            form.closest('.modal').modal('hide');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to cancel this COMPOFF Credit Request? This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel it!',
                cancelButtonText: 'No, Go Back!',
                customClass: {
                    confirmButton: "btn btn-sm btn-danger",
                    cancelButton: "btn btn-sm btn-secondary"
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = new FormData(form[0]);

                    $.ajax({
                        method: "post",
                        url: "<?php echo base_url('ajax/backend/administrative/cancel-comp-off-credit-request'); ?>",
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.response_type == 'error') {
                                if (response.response_description.length) {
                                    Swal.fire({
                                        html: response.response_description,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        },
                                    }).then(function(e) {
                                        if (typeof response.response_data.validation != 'undefined') {
                                            var validation = response.response_data.validation;
                                            $.each(validation, function(index, value) {
                                                form.find('#' + index + '_error').html(value);
                                            });
                                            form.closest('.modal').modal('show');
                                        }
                                    });
                                }
                            }

                            if (response.response_type == 'success') {
                                if (response.response_description.length) {
                                    Swal.fire({
                                        html: response.response_description,
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        },
                                    }).then(function(e) {
                                        form[0].reset();
                                        $("#comp_off_credit_approval_requests_table").DataTable().ajax.reload();
                                    });
                                }
                            }
                        },
                        failed: function() {
                            Swal.fire({
                                html: "Ajax Failed, Please contact administrator",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                            })
                        }
                    })
                } else {
                    form.closest('.modal').modal('show');
                }
            })
        });
        //end::Cancel Request Submit
    })
</script>



<?= $this->endSection() ?>
<?= $this->endSection() ?>