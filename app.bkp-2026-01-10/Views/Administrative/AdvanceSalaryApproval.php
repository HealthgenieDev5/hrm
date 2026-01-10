<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<style type="text/css">
    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
    }

    span.review_status.select2-selection--clearable {
        background-position: right 2rem center;
    }
</style>
<!--begin::Row-->
<div class="row gy-5 g-xl-8">

    <!--begin::Col-->
    <div class="col-xl-12">
        <!--begin::Mixed Widget 2-->
        <div class="card">
            <!--begin::Body-->
            <div class="card-body">
                <form id="filter_form" class="row gy-5 g-xl-8">

                    <div class="col-md-2">
                        <label class="form-label" for="company_id_for_filter" class="mb-3">Company</label>
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

                    <div class="col-md-2">
                        <label class="form-label" for="department_id_for_filter" class="mb-3">Department</label>
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

                    <div class="col-md-2">
                        <label class="form-label" for="employee_id_for_filter" class="mb-3">Employee</label>
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
                        <label class="form-label" for="status" class="mb-3">Status</label>
                        <select class="form-select form-select-sm" id="status" name="status[]" multiple data-control="select2" data-placeholder="Select Status">
                            <option value=""></option>
                            <option value="all_status">All Status</option>
                            <option value="pending" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('pending', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('approved', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('rejected', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>Rejected</option>

                        </select>
                        <small class="text-danger error-text" id="status_error"></small>
                    </div>

                    <div class="col-md-2">
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
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Advance Salary Requests</h3>
            </div>
            <div class="card-body">
                <table id="advance_salary_requests_table" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center bg-white"><strong>ID</strong></th>
                            <th class="text-center bg-white"><strong>Code</strong></th>
                            <th class="text-center bg-white"><strong>Name</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Amount</strong></th>
                            <th class="text-center"><strong>Emi Tenure</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Request Note</strong></th>
                            <th class="text-center"><strong>Review Status</strong></th>
                            <th class="text-center"><strong>Reviewed Date</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Review Remarks</strong></th>
                            <th class="text-center"><strong>Disbursed</strong></th>
                            <th class="text-center"><strong>Disbursed Date</strong></th>
                            <th class="text-center"><strong>Disbursed By</strong></th>
                            <th class="text-center"><strong>Disbursal Remarks</strong></th>
                            <th class="text-center"><strong>Deduct From Month</strong></th>
                            <th class="text-center"><strong>Date Time</strong></th>
                            <th class="text-center bg-white"><strong>Action</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center bg-white"><strong>ID</strong></th>
                            <th class="text-center bg-white"><strong>Code</strong></th>
                            <th class="text-center bg-white"><strong>Name</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Amount</strong></th>
                            <th class="text-center"><strong>Emi Tenure</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Request Note</strong></th>
                            <th class="text-center"><strong>Review Status</strong></th>
                            <th class="text-center"><strong>Reviewed Date</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Review Remarks</strong></th>
                            <th class="text-center"><strong>Disbursed</strong></th>
                            <th class="text-center"><strong>Disbursed Date</strong></th>
                            <th class="text-center"><strong>Disbursed By</strong></th>
                            <th class="text-center"><strong>Disbursal Remarks</strong></th>
                            <th class="text-center"><strong>Deduct From Month</strong></th>
                            <th class="text-center"><strong>Date Time</strong></th>
                            <th class="text-center bg-white"><strong>Action</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<div class="modal fade" tabindex="-1" id="update_advance_salary_request_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="update_advance_salary_request" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Update Advance Salary Request</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" id="advance_salary_request_id" name="advance_salary_request_id" value="" />
                            <small class="text-danger error-text" id="advance_salary_request_id_error"><?= isset($validation) ? display_error($validation, 'advance_salary_request_id') : '' ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Employee Name</label>
                                    <strong id="employee_name" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Department</label>
                                    <strong id="department_name" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Company</label>
                                    <strong id="company_short_name" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Reason</label>
                                    <strong id="reason" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Status</label>
                                    <strong id="review_status_html" class="ms-4 badge badge-danger rounded-pill text-capitalize"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Last Reviewed by</label>
                                    <strong id="reviewed_by_name" class="ms-4 text-info opacity-75"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Last Reviewed at</label>
                                    <strong id="reviewed_date" class="ms-4 text-info opacity-75"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Requested at</label>
                                    <strong id="date_time" class="ms-4 text-info opacity-75"></strong>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <label class="form-label mb-0">Note: </label>
                                    <small id="note" class="ms-4 text-muted"></small>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <label class="form-label mb-0">Reviewer's Remarks: </label>
                                    <small id="reviewers_remarks" class="ms-4 text-muted"></small>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Amount</label>
                                    <strong id="amount" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">EMI Tenure</label>
                                    <strong id="emi_tenure" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Disbursed</label>
                                    <strong id="disbursed" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Disbursed Date</label>
                                    <strong id="disbursed_date" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Disbursed By</label>
                                    <strong id="disbursed_by_name" class="ms-4 text-primary opacity-75"></strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div>
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-sm review_status" id="review_status" name="review_status" data-control="select2" data-placeholder="Select Status" data-allow-clear="true">
                                    <option value=""></option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <small class="text-danger error-text" id="review_status_error"><?= isset($validation) ? display_error($validation, 'review_status') : '' ?></small>
                            </div>
                            <!-- <div class="d-none" id="interest_type_container">
                                    <label class="form-label">Interest Type</label>
                                    <select class="form-select form-select-sm interest_type" id="interest_type" name="interest_type" data-control="select2" data-placeholder="Select Interest Type" data-allow-clear="true">
                                        <option value=""></option>
                                        <option value="flat" >Flat</option>
                                        <option value="reducing" >Reducing</option>
                                    </select>
                                    <small class="text-danger error-text" id="interest_type_error"><?= isset($validation) ? display_error($validation, 'interest_type') : '' ?></small>
                                </div>
                                <div class="d-none" id="interest_percent_container">
                                    <label class="form-label">Interest Rate</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" min="0" step="0.01" id="interest_percent" name="interest_percent" class="form-control" placeholder="Interest Rate">
                                        <span class="input-group-text">
                                            <i class="fa-solid fa-percent me-3"></i> Per Month
                                        </span>
                                    </div>
                                    <small class="text-danger error-text" id="interest_percent_error"><?= isset($validation) ? display_error($validation, 'interest_percent') : '' ?></small>
                                </div> -->
                            <div class="d-none" id="deduct_from_month_container">
                                <label class="form-label">Deduct IN</label>
                                <input type="text" id="deduct_from_month" name="deduct_from_month" class="form-control form-control-sm" placeholder="Deduct From" />
                                <small class="text-danger error-text" id="deduct_from_month_error"><?= isset($validation) ? display_error($validation, 'deduct_from_month') : '' ?></small>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3 d-flex flex-column">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control flex-grow-1" id="remarks" name="remarks"></textarea>
                            <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button id="update_advance_salary_request_submit_button" class="btn btn-sm btn-danger">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="view_emi_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advance Salary Emi</h5>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>
                <!--end::Close-->
            </div>
            <div class="modal-body">
                <table id="advance_salary_emi_table" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Principle Amount</strong></th>
                            <th class="text-center"><strong>Emi Amount</strong></th>
                            <th class="text-center"><strong>Emi Month</strong></th>
                            <th class="text-center"><strong>Deducted</strong></th>
                            <th class="text-center"><strong>Deduction Date</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Principle Amount</strong></th>
                            <th class="text-center"><strong>Emi Amount</strong></th>
                            <th class="text-center"><strong>Emi Month</strong></th>
                            <th class="text-center"><strong>Deducted</strong></th>
                            <th class="text-center"><strong>Deduction Date</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
                <p class="d-none">This module is under development, data will come soon here.
                    The review_status is no longer responsible for deduction from salary, therefore I have to modify the formula the regenerate the Salary </p>
            </div>
        </div>
    </div>
</div>

<?= $this->section('javascript') ?>

<!-- <script src="<?php echo base_url(); ?>assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script> -->
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $(document).on('input', '.form-control', function() {
            $(this).parent().find('.error-text').html('');
        })

        $("#deduct_from_month").flatpickr({
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "F Y",
                    altFormat: "F Y",
                    theme: "dark",
                })
            ]
        });

        $(document).on('change', '#company', function() {
            var company = $('#company').val();
            var department = $('#department').val();
            if (jQuery.inArray("all_companies", company) !== -1 && company.length > 1) {
                $('#company').select2("val", ['all_companies']);
            }

            $('#department_error').html('');
            $('#department').parent().find('.select2-selection').addClass('loading');
            getDepatmentByCompany($('#company').val()).then(function() {
                $('#department').parent().find('.select2-selection').removeClass('loading');
            });
        })

        $(document).on('change', '#department', function() {
            $('#employee_error').html('');
            var department = $('#department').val();
            if (jQuery.inArray("all_departments", department) !== -1 && department.length > 1) {
                $('#department').select2("val", ['all_departments']);
            }
            $('#employee').parent().find('.select2-selection').addClass('loading');
            getEmployeesByDepatment($('#company').val(), $('#department').val()).then(function() {
                $('#employee').parent().find('.select2-selection').removeClass('loading');
            });
        })

        $(document).on('change', '#employee', function() {
            var employee = $('#employee').val();
            if (jQuery.inArray("all_employees", employee) !== -1 && employee.length > 1) {
                $('#employee').select2("val", ['all_employees']);
            }
        });

        //begin::Initialize Datatable
        var table = $("#advance_salary_requests_table").DataTable({
            "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
            // "buttons": [],
            "buttons": ['excel'],
            "ajax": {
                url: "<?= base_url('/ajax/administrative/get-all-advance-salary-requests') ?>",
                type: "POST",
                data: {
                    filter: function() {
                        console.log($('#filter_form').serialize());
                        return $('#filter_form').serialize();
                    }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
            },
            "columns": [{
                    data: "id"
                },
                {
                    data: "internal_employee_id"
                },
                {
                    data: "employee_name"
                },
                {
                    data: "company_short_name"
                },
                {
                    data: "department_name"
                },
                {
                    data: "amount"
                },
                {
                    data: "emi_tenure"
                },
                {
                    data: "reason"
                },
                {
                    data: "note",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap" style="width: 200px; text-align: center;">' + data + '</p>';
                    }
                },
                {
                    data: "review_status",
                    render: function(data, type, row, meta) {
                        var badge_class = "bg-secondary text-dark";
                        if (data == 'rejected') {
                            badge_class = "bg-danger text-danger bg-opacity-15";
                        } else if (data == 'approved') {
                            badge_class = "bg-success text-success bg-opacity-15";
                        }
                        var content = '<span class="badge text-capitalize rounded-pill ' + badge_class + '">' + data + '</span>';
                        if (data == 'approved' && row.disbursed == 'yes') {
                            content += '<br>&<br><span class="badge text-capitalize rounded-pill bg-info text-info bg-opacity-15">Disbursed</span>';
                        }
                        return content;
                    }
                },
                {
                    data: "reviewed_date"
                },
                {
                    data: "reviewed_by_name"
                },
                {
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap" style="width: 200px; text-align: center;">' + data + '</p>';
                    }
                },
                {
                    data: "disbursed"
                },
                {
                    data: "disbursed_date"
                },
                {
                    data: "disbursed_by_name"
                },
                {
                    data: "disbursal_remarks",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap" style="width: 200px; text-align: center;">' + data + '</p>';
                    }
                },
                {
                    data: "deduct_from_month"
                },
                {
                    data: "date_time"
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        if (row.review_status == 'pending') {
                            var review_buttons = '<a href="#" class="btn btn-sm btn-primary view-advance-salary-request" data-id="' + row.id + '">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa fa-check" aria-hidden="true" ></i> <small>Approve</small>' +
                                '</span>' +
                                '</a>' +
                                '<a href="#" class="btn btn-sm btn-danger view-advance-salary-request" data-id="' + row.id + '">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa fa-times" aria-hidden="true" ></i> <small>Reject</small>' +
                                '</span>' +
                                '</a>';
                        } else {
                            var review_buttons = '<a href="#" class="btn btn-sm btn-primary view-advance-salary-request disabled" data-id="">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa fa-check" aria-hidden="true" ></i> <small>Approve</small>' +
                                '</span>' +
                                '</a>' +
                                '<a href="#" class="btn btn-sm btn-danger view-advance-salary-request disabled" data-id="">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa fa-times" aria-hidden="true" ></i> <small>Reject</small>' +
                                '</span>' +
                                '</a>';
                        }

                        if (row.review_status == 'approved' && row.disbursed !== 'yes') {
                            var disbursal_button = '<a href="#" class="btn btn-sm btn-success view-advance-salary-request" data-id="' + row.id + '">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa-solid fa-money-bill-trend-up"></i> <small>Disburse</small>' +
                                '</span>' +
                                '</a>';
                        } else {
                            var disbursal_button = '<a href="#" class="btn btn-sm btn-success view-advance-salary-request disabled" data-id="">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa-solid fa-money-bill-trend-up"></i> <small>Disburse</small>' +
                                '</span>' +
                                '</a>';
                        }

                        if (row.review_status == 'approved' && row.disbursed == 'yes') {
                            var view_emi_button = '<a href="#" class="btn btn-sm btn-info view-emi" data-id="' + row.id + '">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa-solid fa-money-bill-transfer"></i> <small>View EMI</small>' +
                                '</span>' +
                                '</a>';
                        } else {
                            var view_emi_button = '<a href="#" class="btn btn-sm btn-info  view-emi disabled" data-id="">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa-solid fa-money-bill-transfer"></i> <small>View EMI</small>' +
                                '</span>' +
                                '</a>';
                        }

                        return '<div class="d-flex flex-column"><div class="btn-group mb-2">' + review_buttons + '</div><div class="btn-group">' + disbursal_button + view_emi_button + '</div></div>';
                    }
                }
            ],
            "fixedColumns": {
                left: 2,
                right: 1
            },
            "order": [],
            "scrollX": true,
            "scrollY": '50vh',
            "paging": false,
            "columnDefs": [{
                    "className": 'border-start border-secondary td-border-left text-center',
                    "targets": [-1]
                },
                {
                    "className": 'border-end border-secondary td-border-right text-center',
                    "targets": [2]
                },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
            ],
            /*"initComplete": function(settings, json){
                <?php
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view' && isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
                ?>
                    var id = "<?php echo $_REQUEST['id']; ?>";
                    $("a.view-advance-salary-request[data-id="+id+"]").trigger('click');
                    <?php
                }
                    ?>
            }*/
        });
        //end::Initialize Datatable

        $(document).on('change', 'form#update_advance_salary_request select[name="review_status"]', function() {
            if ($(this).val() == 'disbursed') {
                $("form#update_advance_salary_request").find('div#deduct_from_month_container').removeClass('d-none').addClass('d-block');
                // $("form#update_advance_salary_request").find('div#interest_type_container').removeClass('d-none').addClass('d-block');
                // $("form#update_advance_salary_request").find('div#interest_percent_container').removeClass('d-none').addClass('d-block');
            } else {
                $("form#update_advance_salary_request").find('div#deduct_from_month_container').removeClass('d-block').addClass('d-none');
                // $("form#update_advance_salary_request").find('div#interest_type_container').removeClass('d-block').addClass('d-none');
                // $("form#update_advance_salary_request").find('div#interest_percent_container').removeClass('d-block').addClass('d-none');
            }
        });

        //begin::get Advance Salary to reject
        var $this;
        var $icon;
        $(document).on('click', '.view-advance-salary-request', function(e) {
            e.preventDefault();
            $('.disabled-note').remove();
            $this = $(this);
            $icon = $this.find('i');
            var $ButtonText = $this.text().trim();
            $this.find('i').replaceWith('<i class="fa fa-refresh fa-spin"></a>');
            $("#update_advance_salary_request_submit_button").html($this.text()).attr("class", "");
            var classList = $this.attr('class').split(/\s+/);
            $.each(classList, function(index, item) {
                if (item !== 'view-advance-salary-request') {
                    $("#update_advance_salary_request_submit_button").addClass(item);
                }
            });
            var advance_salary_request_id = $(this).data('id');
            if (advance_salary_request_id == '') {
                return false;
            }
            var data = {
                'advance_salary_request_id': advance_salary_request_id,
            };

            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/administrative/get-advance-salary-request'); ?>",
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
                        if (typeof response.response_data.advance_salary_request_data != 'undefined') {
                            var advance_salary_request_data = response.response_data.advance_salary_request_data;
                            $("form#update_advance_salary_request").find('small.error-text').html('');
                            $("form#update_advance_salary_request").find('input[name="advance_salary_request_id"]').val(advance_salary_request_data.id);
                            $("form#update_advance_salary_request").find('select[name="review_status"]').html('<option></option>');

                            if (advance_salary_request_data.review_status == 'pending') {
                                if ($ButtonText === 'Approve') {
                                    $("form#update_advance_salary_request").find('select[name="review_status"]').html('<option></option><option value="approved">Approved</option>');
                                } else if ($ButtonText === 'Reject') {
                                    $("form#update_advance_salary_request").find('select[name="review_status"]').html('<option></option><option value="rejected">Rejected</option>');
                                }
                                if ('40' != "<?php echo session()->get('current_user')['employee_id']; ?>") {
                                    $("form#update_advance_salary_request").find('select[name="review_status"]').attr('disabled', true);
                                    $("form#update_advance_salary_request").find('#review_status_error').before('<small class="text-danger disabled-note">You are not authorised to Approve or Reject<br></small>');
                                }
                            } else if (advance_salary_request_data.review_status == 'approved') {
                                if ($ButtonText === 'Disburse') {
                                    $("form#update_advance_salary_request").find('select[name="review_status"]').html('<option></option><option value="disbursed">Disburse</option>');
                                }
                                if ('40' != "<?php echo session()->get('current_user')['employee_id']; ?>") {
                                    $("form#update_advance_salary_request").find('select[name="review_status"]').attr('disabled', true);
                                    $("form#update_advance_salary_request").find('#review_status_error').before('<small class="text-danger disabled-note">You are not authorised to disburse<br></small>');
                                }
                            }

                            if (advance_salary_request_data.review_status == 'pending') {
                                $("form#update_advance_salary_request").find('small#reviewers_remarks').html("");
                                $("form#update_advance_salary_request").find('textarea#remarks').html(advance_salary_request_data.remarks);
                            } else {
                                $("form#update_advance_salary_request").find('small#reviewers_remarks').html(advance_salary_request_data.remarks);
                                $("form#update_advance_salary_request").find('textarea#remarks').html("");
                            }

                            $.each(advance_salary_request_data, function(index, value) {
                                $("form#update_advance_salary_request").find('strong#' + index).html(value);
                                $("form#update_advance_salary_request").find('small#' + index).html(value);
                            });
                            $("#update_advance_salary_request_modal").modal('show');


                            /*if(advance_salary_request_data.review_status == 'approved'){
                                $("form#update_advance_salary_request").find('select[name="review_status"]').html('<option></option><option value="disbursed">Disburse</option>');
                            }else{
                                $("form#update_advance_salary_request").find('select[name="review_status"]').val(advance_salary_request_data.review_status).trigger('change');
                            }
                            
                            $("form#update_advance_salary_request").find('textarea#remarks').html(advance_salary_request_data.remarks);
                            $.each(advance_salary_request_data, function(index, value){
                                $("form#update_advance_salary_request").find('strong#'+index).html(value);
                                $("form#update_advance_salary_request").find('small#'+index).html(value);
                            });
                            $("#update_advance_salary_request_modal").modal('show');*/
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
        $("#update_advance_salary_request_modal").on('hide.bs.modal', function() {
            $this.find('i').replaceWith($icon);
        })
        //end::get Advance Salary Request to reject

        //begin::Update Advance Salary Request  Ajax
        $(document).on('click', '#update_advance_salary_request_submit_button', function(e) {
            e.preventDefault();
            var form = $('#update_advance_salary_request');
            form.closest('.modal').modal('hide');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update it!',
                cancelButtonText: 'No, Go Back!',
                customClass: {
                    confirmButton: "btn btn-sm btn-primary",
                    cancelButton: "btn btn-sm btn-secondary"
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = new FormData(form[0]);
                    $.ajax({
                        method: "post",
                        url: "<?php echo base_url('/ajax/administrative/approve-advance-salary-request'); ?>",
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
                                        $("#advance_salary_requests_table").DataTable().ajax.reload();
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
        //end::Update Advance Salary Request  Ajax

        //begin::view emi
        $(document).on('click', '.view-emi', function(e) {
            e.preventDefault();
            $("#view_emi_modal").modal("show");
            var advance_salary_emi_table = $("#advance_salary_emi_table").DataTable({
                "destroy": true,
                "dom": 't',
                "buttons": [],
                "ajax": {
                    url: "<?= base_url('/ajax/get-advance-salary-emi') ?>" + "/" + $(this).data('id'),
                    type: "POST",
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                },
                "columns": [{
                        data: "principle_amount"
                    },
                    {
                        data: "emi"
                    },
                    {
                        data: "emi_month"
                    },
                    {
                        data: "deducted"
                    },
                    {
                        data: "deduction_date"
                    },
                ],
                "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                }, ],
                "paging": false,
            });
        })
        //end::view emi

    })

    const getDepatmentByCompany = async (company_id) => {
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
                        $('#department').val([]).trigger('change');
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

    const getEmployeesByDepatment = async (company_id, department_id) => {
        $('#employee').html('<option></option>');
        $('#employee').append('<option value="all_employees">All Employees</option>');
        var data = {
            'company_id': company_id,
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
                        $('#employee').val([]).trigger('change');
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
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>