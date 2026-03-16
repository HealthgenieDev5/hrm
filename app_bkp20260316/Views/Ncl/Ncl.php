<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>


<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }
    .select2-container--bootstrap5 .select2-selection.loading {
        background: #fff url("<?php echo base_url().'/assets/media/select2/select2-spinner.gif'; ?>") no-repeat calc(100% - 10px) 50% !important;
    }
    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
    }
    .flatpickr-monthSelect-month.flatpickr-disabled {
        opacity: 0.25;
    }
</style>
<?php
if( !in_array(session()->get('current_user')['role'], array('admin', 'superuser', 'hr')) ){ 
    $company_id_disabled = 'disabled'; 
    ?>
    <div class="row gy-5 g-xl-8">
        <div class="col-xl-6">
            <input type="hidden" id="company_id_for_filter" value="<?= set_value('company_id_for_filter', @$company_id_for_filter) ?>" />
        </div>
    </div>
    <?php
}else{
    ?>
    <div class="card mb-7">
        <div class="card-body">
            <form id="filter_form" class="row gy-5 g-xl-8">

                <div class="col-md-2">
                    <label class="form-label" for="company_id_for_filter" class="mb-3">Company</label>
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

                <div class="col-md-2">
                    <label class="form-label" for="department_id_for_filter" class="mb-3">Department</label>
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

                <div class="col-md-2">
                    <label class="form-label" for="employee_id_for_filter" class="mb-3">Employee</label>
                    <select class="form-select form-select-sm" id="employee" name="employee[]" multiple data-control="select2" data-placeholder="Select an Employee" >
                        <option value=""></option>
                        <option value="all_employees" <?php echo ( isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array( 'all_employees', $_REQUEST['employee']) ) ? 'selected' : ''; ?> >All Employees</option>
                        <?php
                        if( isset($Employees) && !empty($Employees) ){
                            foreach( $Employees as $employee_row){
                                ?>
                                <option value="<?php echo $employee_row['id']; ?>" <?php echo ( isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array( $employee_row['id'], $_REQUEST['employee']) && !in_array( 'all_employees', $_REQUEST['employee']) ) ? 'selected' : ''; ?> ><?php echo $employee_row['employee_name'].'('.$employee_row['internal_employee_id'].') - '.$employee_row['department_name'].' -'.$employee_row['company_short_name']; ?> <?php echo $employee_row['status'] != 'active' ? ' --'.$employee_row['status'] : ''; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <small class="text-danger error-text" id="employee_error"></small>
                </div>

                <div class="col-lg-2">
                    <label class="form-label" for="year_month" class="mb-3">Month</label>
                    <div class="position-relative d-flex align-items-center ">
                        <!--begin::Icon-->
                        <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                            <!-- <i class="fas fa-clock"></i> -->
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <input type="text" id="year_month" name="year_month" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select month" value="<?php echo ( isset($_REQUEST['year_month']) && !empty($_REQUEST['year_month']) ) ? date('F Y', strtotime($_REQUEST['year_month'])) : date('F Y', strtotime(first_date_of_last_month())); ?>" />
                    </div>
                    <span class="text-danger error-text d-block" id="year_month_error"></span>
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
        <table id="ncl_report_table" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <th class="text-center"><strong>Month</strong></th>
                    <th class="text-center"><strong>NCL</strong></th>
                    <th class="text-center"><strong>Working Days</strong></th>
                    <th class="text-center"><strong>Eligibility</strong></th>
                    <th class="text-center"><strong>Payable</strong></th>
                    <th class="text-center"><strong>Bank Account</strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <th class="text-center"><strong>Month</strong></th>
                    <th class="text-center"><strong>NCL</strong></th>
                    <th class="text-center"><strong>Working Days</strong></th>
                    <th class="text-center"><strong>Eligibility</strong></th>
                    <th class="text-center"><strong>Payable</strong></th>
                    <th class="text-center"><strong>Bank Account</strong></th>
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($){

        /*begin::ncl_report_table*/
        var ncl_report_table = $("#ncl_report_table").DataTable({
            /*"dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',*/
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                { extend: 'excel', text: '<i class="fa-solid fa-file-excel"></i> Excel', className: 'btn btn-sm btn-light' },
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/backend/reports/get-ncl-report') ?>",
                type:  "POST",
                // data:  { filter : function(){ console.log($('#filter_form').serialize()); return $('#filter_form').serialize(); } },
                data:  function(){ return $('#filter_form').serialize(); },
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
            "oLanguage": { "sSearch": "" },
            "columns": [
                { data: "internal_employee_id" },
                { data: "employee_name" },
                { data: "department_name" },
                { data: "company_short_name" },
                { data: "month" },
                { data: "ncl" },
                { data: "working_days" },
                { data: "eligibility" },
                { data: "payable" },
                { data: "bank_account" }
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '40vh',
            "scrollCollapse": true,
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#ncl_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">NCL Report</h3>');
        /*end::ncl_report_table*/

        /*begin::Company Filter*/
        $(document).on('change', '#company', function(){
            var company = $('#company').val();
            var department = $('#department').val();
            if(jQuery.inArray("all_companies", company) !== -1 && company.length > 1){
                $('#company').select2("val", ['all_companies']);
            }

            $('#department_error').html('');
            $('#department').parent().find('.select2-selection').addClass('loading');
            getDepatmentByCompany( $('#company').val() ).then(function(){
                $('#department').parent().find('.select2-selection').removeClass('loading');
            });
        })
        /*end::Company Filter*/
        /*begin::Department Filter*/
        $(document).on('change', '#department', function(){
            $('#employee_error').html('');
            var department = $('#department').val();
            if(jQuery.inArray("all_departments", department) !== -1 && department.length > 1){
                $('#department').select2("val", ['all_departments']);
            }
            $('#employee').parent().find('.select2-selection').addClass('loading');
            getEmployeesByDepatment( $('#company').val(), $('#department').val() ).then(function(){
                $('#employee').parent().find('.select2-selection').removeClass('loading');
            });
        })
        /*end::department Filter*/
        /*begin::employee filter*/
        $(document).on('change', '#employee', function(){
            var employee = $('#employee').val();
            if(jQuery.inArray("all_employees", employee) !== -1 && employee.length > 1){
                $('#employee').select2("val", ['all_employees']);
            }
        });
        /*end::employee filter*/

        $("#year_month").flatpickr({
            minDate: "2023-11-01",
            maxDate: "<?php echo first_date_of_last_month(); ?>",
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "F Y",
                    altFormat: "F Y",
                    theme: "dark",
                })
            ]
        });

    })

    const getDepatmentByCompany = async (company_id ) => {
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
                        $('#department').val([]).trigger('change');
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

    const getEmployeesByDepatment = async (company_id, department_id) => {
        $('#employee').html('<option></option>');
        $('#employee').append('<option value="all_employees">All Employees</option>');
        var data = {
            'company_id' : company_id,
            'department_id' : department_id,
        };
        return $.ajax({
            method: "post",
            url: "<?php echo base_url('/ajax/backend/reports/get-employees-by-department-id'); ?>",
            data: data,
            success: function(response){
                if( response.response_type == 'error' ){
                    $('#employee_error').html(response.response_description);
                }

                if( response.response_type == 'success' ){
                    if( typeof response.response_data.employees != 'undefined' ){
                        var employee_data = response.response_data.employees;
                        $.each(employee_data, function(index, employee){
                            // $('#employee').append('<option value="'+employee.id+'" >'+employee.employee_name+' ('+employee.internal_employee_id+') - '+employee.department_name+' - '+employee.company_short_name+' </option>');

                            $('#employee').append(`<option value="${employee.id}" >${employee.employee_name} (${employee.internal_employee_id}) - ${employee.department_name} - ${employee.company_short_name} ${employee.status != 'active' ? ' --'+employee.status : ''}</option>`);


                        });
                        $('#employee').val([]).trigger('change');
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

</script>

<?= $this->endSection() ?>

<?= $this->endSection() ?>