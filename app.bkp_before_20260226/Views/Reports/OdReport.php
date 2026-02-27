<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>


<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .select2-container--bootstrap5 .select2-selection.loading {
        background: #fff url("<?php echo base_url() . '/assets/media/select2/select2-spinner.gif'; ?>") no-repeat calc(100% - 10px) 50% !important;
    }

    .select2-container--bootstrap5 .select2-selection--multiple.form-select-sm .select2-selection__choice .select2-selection__choice__remove {
        height: 0.65rem;
        width: 0.65rem;
    }

    .select2-container--bootstrap5 .select2-selection--multiple.form-select-sm .select2-selection__choice .select2-selection__choice__display {
        margin-left: 1.10rem;
        font-size: 0.8rem;
    }
</style>
<?php
if (!in_array(session()->get('current_user')['role'], array('admin', 'superuser', 'hr'))) {
    $company_id_disabled = 'disabled';
?>
    <div class="row gy-5 g-xl-8">
        <div class="col-xl-6">
            <input type="hidden" id="company_id_for_filter" value="<?= set_value('company_id_for_filter', @$company_id_for_filter) ?>" />
        </div>
    </div>
<?php
} else {
?>
    <div class="card mb-7">
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
                                <option
                                    value="<?php echo $department_row['id']; ?>"
                                    <?php echo (isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array($department_row['id'], $_REQUEST['department']) && !in_array('all_departments', $_REQUEST['department'])) ? 'selected' : ''; ?>>
                                    <?php echo $department_row['department_name'] . ' - ' . $department_row['company_short_name']; ?>
                                </option>
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
                                <option
                                    value="<?php echo $employee_row['id']; ?>"
                                    <?php echo (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array($employee_row['id'], $_REQUEST['employee']) && !in_array('all_employees', $_REQUEST['employee'])) ? 'selected' : ''; ?>>
                                    <?php echo $employee_row['employee_name'] . '(' . $employee_row['internal_employee_id'] . ') - ' . $employee_row['department_name'] . ' -' . $employee_row['company_short_name']; ?>
                                </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <small class="text-danger error-text" id="employee_error"></small>
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="status" class="mb-3">OD Status</label>
                    <select class="form-select form-select-sm" id="status" name="status[]" multiple data-control="select2" data-placeholder="Select OD Status">
                        <option value=""></option>
                        <option value="all_status">All Status</option>
                        <?php
                        if (!empty($statuses)) {
                            foreach ($statuses as $status) {
                        ?>
                                <option value="<?php echo $status['status']; ?>" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array($status['status'], $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>><?php echo ucfirst($status['status']); ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <small class="text-danger error-text" id="status_error"></small>
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="date_range_for_filter" class="mb-3">Date Range</label>
                    <div class="position-relative d-flex align-items-center ">
                        <!--begin::Icon-->
                        <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                            <!-- <i class="fas fa-clock"></i> -->
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <input type="text" id="date_range_for_filter" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select date range" value="<?php echo (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) ? $_REQUEST['from_date'] . ' to ' . $_REQUEST['to_date'] : first_date_of_year() . ' to ' . current_date_of_month(); ?>" />
                        <input type="hidden" id="from_date" name="from_date" value="<?php echo (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) ? $_REQUEST['from_date'] : first_date_of_year(); ?>" />
                        <input type="hidden" id="to_date" name="to_date" value="<?php echo (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) ? $_REQUEST['to_date'] : current_date_of_month(); ?>" />
                    </div>
                    <span class="text-danger error-text d-block" id="date_range_for_filter_error"></span>
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
    </div>
<?php
}
?>



<div class="row gy-5 g-xl-8">
    <!--begin::Punching report-->
    <div class="col-xl-12">
        <table id="od_report_table" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Pre/Post</strong></th>
                    <th class="text-center"><strong>Estimated From</strong></th>
                    <th class="text-center"><strong>Estimated To</strong></th>
                    <th class="text-center"><strong>Hours</strong></th>
                    <th class="text-center"><strong>Assigned By</strong></th>
                    <th class="text-center"><strong>Status</strong></th>
                    <th class="text-center"><strong>Duty Location</strong></th>
                    <th class="text-center"><strong>Reason</strong></th>
                    <th class="text-center"><strong>Reviewed By</strong></th>
                    <th class="text-center"><strong>Reviewed Date Time</strong></th>
                    <th class="text-center"><strong>Remarks</strong></th>
                    <th class="text-center"><strong>Updated Date Time</strong></th>
                    <th class="text-center"><strong>Requested Date Time</strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Pre/Post</strong></th>
                    <th class="text-center"><strong>Estimated From</strong></th>
                    <th class="text-center"><strong>Estimated To</strong></th>
                    <th class="text-center"><strong>Hours</strong></th>
                    <th class="text-center"><strong>Assigned By</strong></th>
                    <th class="text-center"><strong>Status</strong></th>
                    <th class="text-center"><strong>Duty Location</strong></th>
                    <th class="text-center"><strong>Reason</strong></th>
                    <th class="text-center"><strong>Reviewed By</strong></th>
                    <th class="text-center"><strong>Reviewed Date Time</strong></th>
                    <th class="text-center"><strong>Remarks</strong></th>
                    <th class="text-center"><strong>Updated Date Time</strong></th>
                    <th class="text-center"><strong>Requested Date Time</strong></th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
    <!--end::Punching report-->
</div>
<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        /*begin::od_report_table*/
        var od_report_table = $("#od_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                extend: 'excel',
                text: '<i class="fa-solid fa-file-excel"></i> Excel',
                className: 'btn btn-sm btn-light'
            }, ],
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/backend/reports/get-od-report') ?>",
                type: "POST",
                data: {
                    filter: function() {
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
                searchPlaceholder: "Search"
            },
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    data: "internal_employee_id"
                },
                {
                    data: "employee_name"
                },
                {
                    data: "pre_post"
                },
                {
                    data: {
                        _: 'estimated_from_date_time.formatted',
                        sort: 'estimated_from_date_time.ordering',
                    }
                },
                {
                    data: {
                        _: 'estimated_to_date_time.formatted',
                        sort: 'estimated_to_date_time.ordering',
                    }
                },
                {
                    data: "interval"
                },
                {
                    data: "assigned_by"
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        var badge_class = "bg-secondary";
                        if (data == 'rejected') {
                            badge_class = "bg-danger bg-opacity-15";
                        } else if (data == 'approved') {
                            badge_class = "bg-success bg-opacity-15";
                        }
                        return '<span class="badge text-capitalize text-dark fw-normal rounded-pill ' + badge_class + '">' + data + '</span>';
                    }
                },
                {
                    data: "duty_location"
                },
                {
                    data: "reason",
                    render: function(data, type, row, meta) {
                        if (row.international == 'yes') {
                            return '<p class="text-wrap" style="width: 120px">' + data + '<br><strong>International trip included</strong></p>';
                        } else {
                            return '<p class="text-wrap" style="width: 120px">' + data + '</p>';
                        }
                    }
                },
                {
                    data: "reviewed_by_name"
                },
                {
                    data: {
                        _: 'reviewed_date_time.formatted',
                        sort: 'reviewed_date_time.ordering',
                    }
                },
                {
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap" style="width: 120px">' + data + '</p>';
                    }
                },
                {
                    data: {
                        _: 'updated_date_time.formatted',
                        sort: 'updated_date_time.ordering',
                    }
                },
                {
                    data: {
                        _: 'date_time.formatted',
                        sort: 'date_time.ordering',
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": 'auto',
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ],
        });
        $('#od_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">OD Report</h3>');
        /*end::od_report_table*/


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

        $("#date_range_for_filter").flatpickr({
            altInput: true,
            altFormat: "M j, Y",
            dateFormat: "Y-m-d",
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
        });

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
                            $('#employee').append('<option value="' + employee.id + '" >' + employee.employee_name + ' (' + employee.internal_employee_id + ') - ' + employee.department_name + ' - ' + employee.company_short_name + '</option>');
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