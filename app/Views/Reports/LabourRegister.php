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

    /* Dropdown register buttons styling */
    .dt-button-collection {
        padding: 0.5rem 0 !important;
        min-width: 220px !important;
    }

    .dt-button-collection .dropdown-item {
        padding: 0.65rem 1.25rem !important;
        color: #181c32 !important;
        font-size: 0.95rem !important;
        transition: background-color 0.15s ease;
    }

    .dt-button-collection .dropdown-item:hover {
        background-color: #f1faff !important;
        color: #009ef7 !important;
    }

    .dt-button-collection .dropdown-item i {
        width: 20px;
        text-align: center;
        color: #7e8299;
    }

    .dt-button-collection .dropdown-item:hover i {
        color: #009ef7;
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
                                    if (isset($EmployeesFilter) && !empty($EmployeesFilter)) {
                                        foreach ($EmployeesFilter as $employee_row) {
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
                                <label class="form-label" for="month" class="mb-3">Month</label>
                                <div class="position-relative d-flex align-items-center ">
                                    <!--begin::Icon-->
                                    <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                        <!-- <i class="fas fa-clock"></i> -->
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                    <input type="text" id="month" name="month" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select month" value="<?php echo (isset($_REQUEST['month']) && !empty($_REQUEST['month'])) ? date('Y-m', strtotime($_REQUEST['month'])) : date('Y-m', strtotime(first_date_of_last_month())); ?>" />
                                </div>
                                <span class="text-danger error-text d-block" id="month_error"></span>
                            </div>

                            <div class="col-lg-2">
                                <label class="form-label" for="register_type" class="mb-3">Register Type</label>
                                <select class="form-select form-select-sm" id="register_type" name="register_type" data-control="select2" data-placeholder="Select register type">
                                    <option value=""></option>
                                    <?php
                                    if (isset($register_types) && !empty($register_types)) {
                                        foreach ($register_types as $register_type) {
                                    ?>
                                            <option
                                                value="<?php echo $register_type['key']; ?>"
                                                <?php echo $register_type['key'] == @$_REQUEST['register_type'] ? 'selected' : ''; ?>>
                                                <?= $register_type['label'] ?>
                                            </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <small class="text-danger error-text" id="register_type_error"></small>
                            </div>


                            <div class="col-lg-2">
                                <label class="form-label"> &nbsp; </label><br>
                                <button type="submit" id="filter_form_submit" class="btn btn-sm btn-primary d-inline">
                                    <span class="indicator-label">Get Reports</span>
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

        $("#month").flatpickr({
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