<?= $this->extend('Templates/DashboardLayout') ?>
<?= $this->section('content') ?>
<style>
    .form-floating>.form-control,
    .form-floating>.form-select {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }

    .form-floating>.form-control::placeholder {
        color: transparent;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label,
    .form-floating>.form-select~label {
        opacity: 1;
        transform: scale(0.85) translateY(-0.85rem) translateX(0.15rem);
        /* background: #fff; */
        /* background: #f5f8fa; */
        height: max-content;
        padding: 0rem 0.5rem;
        margin: 0px 5px;
        color: #000;
        color: #393939;
        font-weight: 500;
    }

    .form-floating>.form-control:focus~label::after,
    .form-floating>.form-control:not(:placeholder-shown)~label::after,
    .form-floating>.form-select~label::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #f5f8fa;
        transform: translateY(-50%);
        z-index: -1;
    }

    .select2-selection.select2-selection--single.form-select {
        line-height: 1.85;
    }

    .la.la-trash-o {
        font-size: 18px;
    }
</style>
<div class="row gy-5 g-xl-8">
    <div class="col-12">
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <style>
                    .form-floating>.form-control,
                    .form-floating>.form-select {
                        height: calc(3.5rem + 2px);
                        line-height: 1.25;
                    }

                    .form-floating>.form-control::placeholder {
                        color: transparent;
                    }

                    .form-floating>.form-control:focus~label,
                    .form-floating>.form-control:not(:placeholder-shown)~label,
                    .form-floating>.form-select~label {
                        opacity: 1;
                        transform: scale(0.85) translateY(-0.85rem) translateX(0.15rem);
                        height: max-content;
                        padding: 0rem 0.5rem;
                        margin: 0px 5px;
                        color: #000;
                        color: #393939;
                        font-weight: 500;
                    }

                    .form-floating>.form-control:focus~label::after,
                    .form-floating>.form-control:not(:placeholder-shown)~label::after,
                    .form-floating>.form-select~label::after {
                        content: '';
                        position: absolute;
                        top: 50%;
                        left: 0;
                        width: 100%;
                        height: 2px;
                        background-color: #f5f8fa;
                        transform: translateY(-50%);
                        z-index: -1;
                    }
                </style>
                <form id="filter_form" class="row gy-5 g-xl-8">
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
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
                            <label for="company">Company</label>
                            <small class="text-danger error-text" id="company_error"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating mb-3">
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
                            <label for="department">Department</label>
                            <small class="text-danger error-text" id="department_error"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
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
                            <label for="employee">Employee</label>
                            <small class="text-danger error-text" id="employee_error"></small>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" id="filter_form_submit" class="btn btn-primary d-inline h-100">
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


        <table id="appraisals_table" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th class="text-center bg-white"><strong>Name</strong></th>
                    <th class="text-center bg-white"><strong>Appraisal Date</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center bg-white"><strong>Total Appraisal</strong></th>
                    <th class="text-center"><strong>Gross Salary</strong></th>
                    <th class="text-center"><strong>CTC</strong></th>
                    <th class="text-center"><strong>Basic Salary</strong></th>
                    <th class="text-center"><strong>HRA</strong></th>
                    <th class="text-center"><strong>Conveyance</strong></th>
                    <th class="text-center"><strong>Medical Allowance</strong></th>
                    <th class="text-center"><strong>Special Allowance</strong></th>
                    <th class="text-center"><strong>Fuel Allowance</strong></th>
                    <th class="text-center"><strong>Other Allowance</strong></th>
                    <th class="text-center"><strong>Other</strong></th>
                    <th class="text-center"><strong>Gratuity</strong></th>
                    <!-- <th class="text-center"><strong>Bonus</strong></th> -->
                    <th class="text-center"><strong>PF</strong></th>
                    <th class="text-center"><strong>ESI</strong></th>
                    <th class="text-center"><strong>LWF</strong></th>
                    <th class="text-center"><strong>Non-Compete</strong></th>
                    <th class="text-center"><strong>Loyalty</strong></th>
                    <th class="text-center bg-white"><strong>Action</strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center bg-white"><strong>Name</strong></th>
                    <th class="text-center bg-white"><strong>Appraisal Date</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center bg-white"><strong>Total Appraisal</strong></th>
                    <th class="text-center"><strong>Gross Salary</strong></th>
                    <th class="text-center"><strong>CTC</strong></th>
                    <th class="text-center"><strong>Basic Salary</strong></th>
                    <th class="text-center"><strong>HRA</strong></th>
                    <th class="text-center"><strong>Conveyance</strong></th>
                    <th class="text-center"><strong>Medical Allowance</strong></th>
                    <th class="text-center"><strong>Special Allowance</strong></th>
                    <th class="text-center"><strong>Fuel Allowance</strong></th>
                    <th class="text-center"><strong>Other Allowance</strong></th>
                    <th class="text-center"><strong>Other</strong></th>
                    <th class="text-center"><strong>Gratuity</strong></th>
                    <!-- <th class="text-center"><strong>Bonus</strong></th> -->
                    <th class="text-center"><strong>PF</strong></th>
                    <th class="text-center"><strong>ESI</strong></th>
                    <th class="text-center"><strong>LWF</strong></th>
                    <th class="text-center"><strong>Non-Compete</strong></th>
                    <th class="text-center"><strong>Loyalty</strong></th>
                    <th class="text-center bg-white"><strong>Action</strong></th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>


    </div>
</div>

<div class="modal fade" id="viewAppraisalsModal" tabindex="-1" aria-labelledby="viewAppraisalsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAppraisalsLabel">View Appraisals</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be loaded here -->
                <div id="appraisalsDetails">
                    <p>Loading details...</p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="<?php echo base_url('/backend/master/appraisals/edit/'); ?>" class="btn btn-warning" id="editAppraisalsButton">Edit</a>

                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<?= $this->section('javascript') ?>
<script src="<?php echo base_url(''); ?>/assets/plugins/global/plugins.bundle.js"></script>
<script src="<?php echo base_url(''); ?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(''); ?>/assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>

<script type="text/javascript">
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

    var appraisals_table = $("#appraisals_table").DataTable({

        "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3 mb-md-0"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end php-pagination-container"p>>>>',
        "buttons": [],
        "ajax": {
            url: "<?= base_url('ajax/master/appraisals/table') ?>",
            type: "GET",
            dataType: "json",
            debugger: true,
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
        "oLanguage": {
            "sSearch": ""
        },

        "columns": [{
                data: null,
                render: function(data, type, row, meta) {
                    return '<a href="<?= base_url('/backend/master/appraisals/edit/'); ?>' + row.id + '" class="fw-bolder">' + row.first_name + ' ' + row.last_name + ' : ' + row.internal_employee_id + '</a>';
                }

            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    var date = row.appraisal_date;
                    var formattedDate = new Date(date).toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                    return formattedDate;

                }


            },

            {
                data: "company_short_name"
            },
            {
                data: "department_name"
            },
            {
                data: "total_appraisal"
            },
            {
                data: "gross_salary"
            },
            {
                data: "ctc"
            },
            {
                data: "basic_salary"
            },
            {
                data: "house_rent_allowance"
            },
            {
                data: "conveyance"
            },
            {
                data: "medical_allowance"
            },
            {
                data: "special_allowance"
            },
            {
                data: "fuel_allowance"
            },
            {
                data: "other_allowance"
            },
            {
                data: "other_benefits"
            },
            {
                data: "gratuity"
            },
            // {
            //     data: "bonus"
            // },
            {
                data: "pf"
            },
            {
                data: "esi"
            },
            {
                data: "lwf"
            },
            {
                data: null,
                className: "text-center",
                render: (d, t, row) => {
                    if (row.non_compete_loan !== 'yes') return '<span class="badge badge-light">No</span>';
                    const amt = row.non_compete_loan_amount_per_month ?
                        '₹ ' + Number(row.non_compete_loan_amount_per_month).toLocaleString('en-IN') + '/mo' :
                        '-';
                    const from = row.non_compete_loan_from || '';
                    const to = row.non_compete_loan_to || '';
                    return `<span class="badge badge-light-success">Yes</span><br><small>${amt}</small><br><small>${from}${to ? ' → '+to : ''}</small>`;
                }
            },
            {
                data: null,
                className: "text-center",
                render: (d, t, row) => {
                    if (row.loyalty_incentive !== 'yes') return '<span class="badge badge-light">No</span>';
                    const amt = row.loyalty_incentive_amount_per_month ?
                        '₹ ' + Number(row.loyalty_incentive_amount_per_month).toLocaleString('en-IN') + '/mo' :
                        '-';
                    return `<span class="badge badge-light-success">Yes</span><br><small>${amt}</small>`;
                }
            },
            {
                data: "id",
                render: function(data, type, row, meta) {
                    var view_buttons = '<a href="#" class="btn btn-sm btn-success view-appraisals" data-id="' + row.id + '">' +
                        '<span class="svg-icon svg-icon-3 m-0">' +
                        '<i class="fa fa-eye" aria-hidden="true" ></i> <small>View</small>' +
                        '</span>' +
                        '</a>'
                    var edit_buttons = '<a href="<?= base_url('/backend/master/appraisals/edit/') ?>' + row.id + '" class="btn btn-sm btn-warning edit-appraisals" data-id="' + row.id + '">' +
                        '<span class="svg-icon svg-icon-3 m-0">' +
                        '<i class="fa fa-edit" aria-hidden="true" ></i> <small>Edit</small>' +
                        '</span>' +
                        '</a>';

                    var delete_button = '<a href="#" class="btn btn-sm btn-danger delete-appraisals" data-id="' + row.id + '">' +
                        '<span class="svg-icon svg-icon-3 m-0">' +
                        '<i class="fa fa-trash"></i> <small>Delete</small>' +
                        '</span>' +
                        '</a>';

                    var download_button = '<a href="<?= base_url('/backend/master/appraisals/pdf/') ?>' + row.employee_id + '" class="btn btn-sm btn-info  download-appraisals " data-id="">' +
                        '<span class="svg-icon svg-icon-3 m-0">' +
                        '<i class="fa fa-download"></i> <small>Download</small>' +
                        '</span>' +
                        '</a>';

                    return '<div class="d-flex flex-column"><div class="btn-group mb-2">' + view_buttons + edit_buttons + '</div><div class="btn-group">' + delete_button + download_button + '</div></div>';
                }
            },
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
                "targets": [1]
            },
            {
                "className": 'text-center',
                "targets": '_all'
            },
        ],

    });
    $('#appraisals_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Appraisals</h3>');
    $('#appraisals_table_filter').addClass("d-flex align-items-center justify-content-center gap-3").append('<a href="<?= base_url('backend/master/appraisals/employee/' . $employee_id . '/create'); ?>" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between gap-3" ><i class="fa fa-plus"></i>Add Appraisal</a>');

    $('#filter_form').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        let queryString = formData.map(item => `${encodeURIComponent(item.name)}=${encodeURIComponent(item.value)}`).join('&');
        const currentUrl = window.location.origin + window.location.pathname;
        const newUrl = `${currentUrl}?${queryString}`;
        window.history.pushState({}, '', newUrl);
        appraisals_table.ajax.reload();
    });

    $(document).on('click', '.view-appraisals', function(e) {
        e.preventDefault();

        var appraisalId = $(this).data('id'); // Get the appraisal ID

        $('#viewAppraisalsModal').modal('show');

        $('#appraisalsDetails').html('<p>Loading details...</p>');
        $.ajax({
            url: "<?php echo base_url('ajax/master/appraisals/details'); ?>/" + appraisalId,
            type: 'POST',
            data: {
                id: appraisalId
            },
            success: function(response) {
                if (response.status === 'success') {

                    var details = `
                            <div class="card-body border-bottom row g-3">
                                <div class="col-md-4">
                                    <label class="floating-label">Employee ID</label>
                                    <span class="form-control form-control-sm border-dashed" id="employee_id_view">
                                        ${response.data.internal_employee_id}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Name</label>
                                    <span class="form-control form-control-sm border-dashed" id="employee_name_view">
                                        ${response.data.first_name} ${response.data.last_name}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Appraisal Date</label>
                                    <span class="form-control form-control-sm border-dashed" id="appraisal_date_view">
                                        ${response.data.appraisal_date}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Appraisal Remarks</label>
                                    <span class="form-control form-control-sm border-dashed" id="appraisal_remarks_view">
                                        ${response.data.appraisal_remarks || 'N/A'}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body border-bottom row g-3">
                                <!-- Salary Information -->
                                <div class="col-md-4">
                                    <label class="floating-label">CTC</label>
                                    <span class="form-control form-control-sm border-dashed" id="ctc_view">
                                        ${response.data.ctc}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Gross Salary</label>
                                    <span class="form-control form-control-sm border-dashed" id="gross_salary_view">
                                        ${response.data.gross_salary}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Basic Salary</label>
                                    <span class="form-control form-control-sm border-dashed" id="basic_salary_view">
                                        ${response.data.basic_salary}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">HRA</label>
                                    <span class="form-control form-control-sm border-dashed" id="hra_view">
                                        ${response.data.house_rent_allowance}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Conveyance</label>
                                    <span class="form-control form-control-sm border-dashed" id="conveyance_view">
                                        ${response.data.conveyance}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Medical Allowance</label>
                                    <span class="form-control form-control-sm border-dashed" id="medical_allowance_view">
                                        ${response.data.medical_allowance}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Special Allowance</label>
                                    <span class="form-control form-control-sm border-dashed" id="special_allowance_view">
                                        ${response.data.special_allowance}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Other Allowance</label>
                                    <span class="form-control form-control-sm border-dashed" id="other_allowance_view">
                                        ${response.data.other_allowance}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Others </label>
                                    <span class="form-control form-control-sm border-dashed" id="other_benefits_view">
                                                ${response.data.other_benefits}
                                    </span>
                            </div>

                            <div class="card-body border-bottom row g-3">
                                <!--<div class="col-md-4">
                                    <label class="floating-label">Bonus</label>
                                    <span class="form-control form-control-sm border-dashed" id="bonus_view">
                                        ${response.data.enable_bonus === 'yes' ? 'Enabled' : 'Disabled'}
                                    </span>
                                </div>-->
                                <div class="col-md-4">
                                    <label class="floating-label">Loyalty Incentive</label>
                                    <span class="form-control form-control-sm border-dashed" id="loyalty_incentive_view">
                                        ${response.data.loyalty_incentive || 'N/A'}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="floating-label">Remarks</label>
                                    <span class="form-control form-control-sm border-dashed" id="loyalty_incentive_remarks_view">
                                        ${response.data.loyalty_incentive_remarks || 'N/A'}
                                    </span>
                                </div>
                            </div>

                        `;
                    $('#appraisalsDetails').html(details);
                    var editUrl = '<?= base_url("/backend/master/appraisals/edit/"); ?>' + appraisalId;
                    $('#editAppraisalsButton').attr('href', editUrl);
                } else {
                    $('#appraisalsDetails').html('<p>Error loading details.</p>');
                }
            },
            error: function() {
                $('#appraisalsDetails').html('<p>Failed to load details.</p>');
            }
        });
    });

    $(document).on('click', '.delete-appraisals', function(e) {
        e.preventDefault();
        var appraisalId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone. Do you really want to delete this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete operation if confirmed
                deleteItem(appraisalId);
            }
        });

        function deleteItem(appraisalId) {
            $.ajax({
                url: "<?= base_url('backend/master/appraisals'); ?>/" + appraisalId,
                type: 'POST',
                data: {
                    id: appraisalId,
                    "_method": "DELETE"
                },
                success: function(response) {
                    console.log(response);
                    swal.fire({
                        title: response.title,
                        text: response.message,
                        icon: response.status,
                        confirmButtonText: 'Ok, got it!',
                    }).then(function() {
                        $('#appraisals_table').DataTable().ajax.reload();
                    });

                },
                error: function() {
                    swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete appraisal.',
                        icon: 'error',
                        confirmButtonText: 'Ok, got it!',
                    });
                }
            });

        }

    });
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>