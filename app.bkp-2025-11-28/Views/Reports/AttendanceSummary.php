<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
    }

    .table-bordered> :not(caption)>* {
        border-width: 1px 0 !important;
    }

    .table-bordered> :not(caption)>*>* {
        border-width: 0 1px !important;
    }

    .table-bordered tbody tr:last-child,
    .table-bordered tfoot tr:last-child {
        border: 1px 0px !important;
        border-bottom-color: inherit !important;
        border-bottom-style: inherit !important;
    }

    .table-bordered tbody tr:last-child td,
    .table-bordered tbody tr:last-child th,
    .table-bordered tfoot tr:last-child td,
    .table-bordered tfoot tr:last-child th {
        border-bottom-width: 1px !important;
        border-bottom-color: inherit !important;
        border-bottom-style: inherit !important;
    }

    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .select2-container--bootstrap5 .select2-selection.loading {
        background: #fff url("<?php echo base_url() . '/assets/media/select2/select2-spinner.gif'; ?>") no-repeat calc(100% - 10px) 50% !important;
    }

    .bg-light-danger {
        background-color: #fff5f8 !important;
    }

    .bg-light-success {
        background-color: #e8fff3 !important;
    }

    .bg-light-warning {
        background-color: #fff8dd !important;
    }

    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
    }

    .flatpickr-monthSelect-month.flatpickr-disabled {
        opacity: 0.25;
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
                <form id="filter_form" class="row gy-5 g-xl-8" enctype='multipart/form-data'>
                    <div class="col-lg-12">
                        <div class="row gy-5 g-xl-8">

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
                                            <option value="<?php echo $employee_row['id']; ?>" <?php echo (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array($employee_row['id'], $_REQUEST['employee']) && !in_array('all_employees', $_REQUEST['employee'])) ? 'selected' : ''; ?>><?php echo $employee_row['employee_name'] . '(' . $employee_row['internal_employee_id'] . ') - ' . $employee_row['department_name'] . ' -' . $employee_row['company_short_name']; ?><?php echo $employee_row['status'] != 'active' ? ' --' . $employee_row['status'] : ''; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <small class="text-danger error-text" id="employee_error"></small>
                            </div>

                            <div class="col-lg-2">
                                <label class="form-label" for="month" class="mb-3">From</label>
                                <div class="position-relative d-flex align-items-center ">
                                    <!--begin::Icon-->
                                    <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                        <!-- <i class="fas fa-clock"></i> -->
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                    <input type="text" id="from" name="from" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select from month" value="<?php echo (isset($_REQUEST['from']) && !empty($_REQUEST['from'])) ? date('Y-m', strtotime($_REQUEST['from'])) : date('Y-m', strtotime(first_date_of_last_month())); ?>" />
                                </div>
                                <span class="text-danger error-text d-block" id="from_error"></span>
                            </div>

                            <div class="col-lg-2">
                                <label class="form-label" for="to" class="mb-3">To</label>
                                <div class="position-relative d-flex align-items-center ">
                                    <!--begin::Icon-->
                                    <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                        <!-- <i class="fas fa-clock"></i> -->
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                    <input type="text" id="to" name="to" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select to month" value="<?php echo (isset($_REQUEST['to']) && !empty($_REQUEST['to'])) ? date('Y-m', strtotime($_REQUEST['to'])) : date('Y-m', strtotime(first_date_of_last_month())); ?>" />
                                </div>
                                <span class="text-danger error-text d-block" id="to_error"></span>
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

                        </div>
                    </div>
                    <!-- <div class="col-lg-2">
                            <label class="form-label"> &nbsp; </label><br>
                            <button type="submit" id="filter_form_submit" class="btn btn-sm btn-primary d-inline">
                                <span class="indicator-label">Filter</span>
                                <span class="indicator-progress">
                                    Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div> -->
                </form>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Mixed Widget 2-->

    <!--begin::Col-->
    <div class="col-12 table-responsive">
        <pre>
            <?php
            // print_r($AttendanceSummary);
            // die();
            ?>
            </pre>
        <table id="attendance_summary" class="table table-row-bordered nowrap">
            <thead>
                <tr>
                    <th class="text-center bg-white"><strong>Name</strong></th>
                    <th class="text-center"><strong>Month</strong></th>
                    <th class="text-center"><strong>Present</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>OD</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>EL+Sick Leave</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>CL</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>COMP OFF</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Week OFF</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Fixed OFF</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Holiday</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>INC</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>RH</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Late</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Early Departure</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Manual Deduction</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>INC</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Daily Grace</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Used COMP OFF</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Waiver</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Extra work</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Late sitting</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Total Credit</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Total adjusted</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Days in month</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Paid</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Unpaid</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Action</strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center bg-white"><strong>Name</strong></th>
                    <th class="text-center"><strong>Month</strong></th>
                    <th class="text-center"><strong>Present</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>OD</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>EL+Sick Leave</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>CL</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>COMP OFF</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Week OFF</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Fixed OFF</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Holiday</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>INC</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>RH</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Late</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Early Departure</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Manual Deduction</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>INC</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Daily Grace</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Used COMP OFF</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Waiver</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Extra work</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Late sitting</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Total Credit</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Total adjusted</strong><br><small>Minutes</small></th>
                    <th class="text-center"><strong>Days in month</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Paid</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Unpaid</strong><br><small>Days</small></th>
                    <th class="text-center"><strong>Action</strong></th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                // dd($attendanceSummary);
                if (!empty($attendanceSummary)) {
                    foreach ($attendanceSummary as $reportRow) {
                ?>
                        <tr>
                            <td class="text-center bg-white">
                                <strong><?= @$reportRow['employee_name'] ?> - <?= @$reportRow['internal_employee_id'] ?></strong><br>
                                <small>(<?= @$reportRow['department_name'] ?> - <?= @$reportRow['company_short_name'] ?>)</small>
                            </td>
                            <td class="text-center"><?= @date('M Y', strtotime($reportRow['year'] . "-" . $reportRow['month'] . "-01")) ?></td>
                            <td class="text-center"><?= @$reportRow['present_days'] ?></td>
                            <td class="text-center"><?= @$reportRow['od_days'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_el_plus_sick_leave'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_cl'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_compoff'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_weekoff'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_fixedoff'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_holidays'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_INC'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_rh'] ?></td>
                            <td class="text-center"><?= @$reportRow['late_coming_minutes'] ?></td>
                            <td class="text-center"><?= @$reportRow['early_going_minutes'] ?></td>
                            <td class="text-center"><?= @$reportRow['deduction_minutes'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_INC_minutes'] ?></td>
                            <td class="text-center"><?= @$reportRow['late_coming_grace'] ?></td>
                            <td class="text-center"><?= @$reportRow['comp_off_minutes'] ?></td>
                            <td class="text-center"><?= @$reportRow['wave_off_minutes'] ?></td>
                            <td class="text-center"><?= @$reportRow['ExtraWorkMinutes'] ?></td>
                            <td class="text-center"><?= @$reportRow['LateSittingMinutes'] ?></td>
                            <td class="text-center"><?= @($reportRow['late_coming_grace'] + $reportRow['comp_off_minutes'] + $reportRow['wave_off_minutes'] + $reportRow['LateSittingMinutes']) ?></td>
                            <td class="text-center"><?= @$reportRow['adjusted_minutes'] ?></td>
                            <td class="text-center"><?= @date('t', strtotime($reportRow['year'] . "-" . $reportRow['month'] . "-01")) ?></td>
                            <td class="text-center"><?= @$reportRow['adjusted_paid_days'] ?></td>
                            <td class="text-center"><?= @$reportRow['total_absent_days'] ?></td>
                            <td class="text-center">
                                <a
                                    target="_blank"
                                    href="<?= base_url(); ?>backend/reports/final-paid-days/final-paid-days-sheet?company[]=all_companies&department[]=all_departments&employee[]=<?= @$reportRow['employee_id'] ?>&month=<?= @$reportRow['year'] . '-' . str_pad($reportRow['month'], 2, '0', STR_PAD_LEFT) ?>">
                                    Show Attendance
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!--end::Col-->


</div>
<!--end::Row-->

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $("#from, #to").flatpickr({
            maxDate: "<?php echo first_date_of_last_month(); ?>",
            altInput: true,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    altFormat: "F Y",
                    theme: "dark"
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
        })

        /*begin::attendance_summary*/
        var attendance_summary = $("#attendance_summary").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                extend: 'excelHtml5',
                title: '',
                text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                className: 'btn btn-sm btn-light',
            }],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                searchPlaceholder: "Search"
            },
            "oLanguage": {
                "sSearch": ""
            },
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "paging": false,
            "columnDefs": [{
                    "className": 'border-end border-secondary td-border-left text-center',
                    "targets": [0]
                },
                {
                    "className": 'bg-success text-center',
                    "targets": [2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                {
                    "className": 'bg-light-danger text-center',
                    "targets": [12, 13, 14, 15]
                },
                {
                    "className": 'bg-light-warning text-center',
                    "targets": [19]
                },
                {
                    "className": 'bg-light-success text-center',
                    "targets": [16, 17, 18, 20]
                },
                {
                    "className": 'bg-light-warning text-center',
                    "targets": [21]
                },
                {
                    "className": 'bg-light-success text-center',
                    "targets": [22]
                },
                {
                    "className": 'bg-light-warning text-center',
                    "targets": [23]
                },
                {
                    "className": 'bg-success text-center',
                    "targets": [24]
                },
                {
                    "className": 'bg-danger text-center',
                    "targets": [25]
                },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
                {
                    targets: '_all',
                    searchable: true,
                    visible: true
                },
            ],
            "fixedColumns": {
                left: 1,
                right: 0
            },

        });
        $('#attendance_summary_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title"><?= $page_title ?></h3>');

        /*end::attendance_summary*/
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