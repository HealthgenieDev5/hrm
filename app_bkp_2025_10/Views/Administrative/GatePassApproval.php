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
                    <form id="filter_form" class="row gy-5 g-xl-8" enctype='multipart/form-data'>
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
                                        <option value="<?php echo $employee_row['id']; ?>" <?php echo ( isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array( $employee_row['id'], $_REQUEST['employee']) && !in_array( 'all_employees', $_REQUEST['employee']) ) ? 'selected' : ''; ?> ><?php echo $employee_row['employee_name'].'('.$employee_row['internal_employee_id'].') - '.$employee_row['department_name'].' -'.$employee_row['company_short_name']; ?></option>
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
                            <label class="form-label" for="status" class="mb-3">Status</label>
                            <select class="form-select form-select-sm" id="status" name="status[]" multiple data-control="select2" data-placeholder="Select Status" >
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
                                <input type="text" id="date_range_for_filter" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select date range" value="<?php echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['from_date'].' to '.$_REQUEST['to_date'] : current_date_of_month().' to '.current_date_of_month(); ?>" />
                                <input type="hidden" id="from_date" name="from_date" value="<?php echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['from_date'] : current_date_of_month(); ?>" />
                                <input type="hidden" id="to_date" name="to_date" value="<?php echo ( isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) ? $_REQUEST['to_date'] : current_date_of_month(); ?>" />
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
                    <h3 class="card-title">Gate Pass Requests</h3>
                    <div class="modal fade" tabindex="-1" id="update_gate_pass_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="update_gate_pass_request" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Approve/Reject Gate Pass Request</h5>
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
                                                <input type="hidden" id="gate_pass_id" name="gate_pass_id" value="" />
                                                <small class="text-danger error-text" id="gate_pass_id_error"><?= isset($validation) ? display_error($validation, 'gate_pass_id') : '' ?></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Req. ID</label>
                                                        <strong id="gate_pass_id" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Gate Pass Type</label>
                                                        <strong id="gate_pass_type" class="ms-4 fs-6 text-info opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Date</label>
                                                        <strong id="gate_pass_date" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">Hours/Minutes</label>
                                                        <strong id="gate_pass_hours" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex flex-column">
                                                        <label class="form-label">Reason</label>
                                                        <small id="reason" class="text-muted"></small>
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
                                                        <label class="form-label mb-0">Rep. Mgr.</label>
                                                        <strong id="reporting_manager_name" class="ms-4 text-primary opacity-75"></strong>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <label class="form-label mb-0">HOD</label>
                                                        <strong id="hod_name" class="ms-4 text-muted"></strong>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <label class="form-label">Remarks</label> <small class="text-muted ms-3">(Required if rejecting)</small>
                                                        <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                                        <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer flex-column align-items-end">
                                        <div class="d-flex align-items-center justify-content-end m-0">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" style="margin: .25rem;">Close</button>
                                            <button id="approve_gate_pass_request_submit_button" data-status="approved" style="margin: .25rem;" class="btn btn-sm btn-primary update_gate_pass_request_submit_button">Approve</button>
                                            <button id="reject_gate_pass_request_submit_button" data-status="rejected" style="margin: .25rem;" class="btn btn-sm btn-warning update_gate_pass_request_submit_button">Reject</button>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end m-0">
                                            <small class="text-danger error-text" id="status_error"><?= isset($validation) ? display_error($validation, 'status') : '' ?>dfghd gfh dgfh fghj fghjfgh</small>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">                   
                    <table id="gate_pass_approval_table" class="table table-custom table-hover nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center bg-white"><strong>Department</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Time</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Reporting Manager Name</strong></th>
                                <th class="text-center"><strong>HOD Name</strong></th>
                                <th class="text-center"><strong>Requested Date</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <!-- <th class="text-center bg-white"><strong>Actions</strong></th> -->
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center bg-white"><strong>Department</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Time</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Reporting Manager Name</strong></th>
                                <th class="text-center"><strong>HOD Name</strong></th>
                                <th class="text-center"><strong>Requested Date</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <!-- <th class="text-center bg-white"><strong>Actions</strong></th> -->
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
            var table = $("#gate_pass_approval_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rtl',
                "paging": false,
                "scrollX": true,
                "scrollY": '400px',
                "ajax": {
                    url:  "<?= base_url('ajax/get-all-gate-pass-approval-requests') ?>",
                    type:  "POST",
                    data:  { filter : function(){ return $('#filter_form').serialize(); } },
                    dataSrc: "",
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                },
                "columns": [
                    { data: "id" },
                    { data: "employee_name",
                        render: function(data, type, row, meta){
                            return '<strong>'+data+'</strong>';
                        }
                    },
                    { data: "department_name" },
                    { data: "gate_pass_type" },
                    {
                        data: {
                            _: 'gate_pass_date.formatted',
                            sort: 'gate_pass_date.ordering',
                        },
                        render: function (data, type, row) {
                            return '<strong>'+data+'</strong>';
                        }
                    },
                    { data: "gate_pass_hours" },
                    { data: "reason" },
                    { data: "status", 
                        render : function(data, type, row, meta) {
                            var badge_class = "bg-secondary text-dark";
                            badge_data = data;
                            if( data == 'rejected'){
                                badge_class = "bg-danger text-danger bg-opacity-15";
                            }else if(data == 'approved'){
                                badge_class = "bg-success text-success bg-opacity-15";
                                badge_data = 'Informed';
                            }else if(data == 'disbursed'){
                                badge_class = "bg-info text-info bg-opacity-15";
                            }else if(data == 'pending'){
                                badge_class = "bg-success text-success bg-opacity-15";
                                badge_data = 'Informed';
                            }
                            return '<span class="badge text-capitalize rounded-pill '+badge_class+'">'+badge_data+'</span>';
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
                    { data: "remarks" },
                    /*{ data: "actions", 
                      render: function(data, type, row, meta){
                        if( row.status !== 'pending' ){
                            var is_btn_disabled = ' disabled ';
                            var data_id = '';
                            var btn_opacity_class = ' opacity-25 ';
                        }else{
                            var is_btn_disabled = '';
                            var data_id = row.id;
                            var btn_opacity_class = '';
                        }
                        var view_gate_pass_button = '<a href="#" class="btn btn-primary btn-sm open-gate-pass-request px-2 py-1'+btn_opacity_class+is_btn_disabled+'" data-id="'+data_id+'">'+
                                                '<span class="svg-icon svg-icon-3 m-0">'+
                                                    '<span class="fa fa-eye" aria-hidden="true" ></span>'+
                                                '</span>'+
                                            '</a>';
                        var action = '<div class="btn-group">'+view_gate_pass_button+'</div>';
                        return action;
                      }
                    },*/
                ],
                "order": [],
                "buttons": [],
                "fixedColumns": {
                    left: 2,
                    right: 1
                },
                "columnDefs": [
                    { "className": 'border-end border-secondary td-border-left text-center', "targets": [1] },
                    { "className": 'border-start border-secondary td-border-left text-center', "targets": [-1] },
                    { "className": 'text-center', "targets": '_all' },
                ],
                "initComplete": function(settings, json){
                    <?php
                    if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'approve' || $_REQUEST['action'] == 'reject') && isset($_REQUEST['id']) && !empty($_REQUEST['id']) ){
                        ?>
                        var id = "<?php echo $_REQUEST['id']; ?>";
                        $("a.open-gate-pass-request[data-id="+id+"]").trigger('click');
                        <?php
                    }
                    ?>
                }
            });

            
            //end::Initialize Datatable


            //begin::get Gate Pass Request to approve
            $(document).on('click', '.open-gate-pass-request', function(e){
                e.preventDefault();
                var gate_pass_id = $(this).data('id');
                if( gate_pass_id == '' ){
                    return false;
                }
                var data = {
                    'gate_pass_id'        :   gate_pass_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/administrative/get-gate-pass-request'); ?>",
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
                            if( typeof response.response_data.gate_pass_data != 'undefined' ){
                                var gate_pass_data = response.response_data.gate_pass_data;
                                $("form#update_gate_pass_request").find('small.error-text').html('');
                                $("form#update_gate_pass_request").find('input[name="gate_pass_id"]').val(gate_pass_data.id);
                                $.each(gate_pass_data, function(index, value){
                                    $("form#update_gate_pass_request").find('strong#'+index).html(value);
                                    $("form#update_gate_pass_request").find('small#'+index).html(value);
                                });
                                $("form#update_gate_pass_request").find('strong#gate_pass_id').html(gate_pass_data.id);
                                $("#update_gate_pass_request_modal").modal('show');
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
            $(document).on('click', '.update_gate_pass_request_submit_button', function(e){
                e.preventDefault();
                var status = $(this).data('status');
                if( status == 'rejected'){
                    confirmButtonText_html = 'Reject';
                }
                if( status == 'approved'){
                    confirmButtonText_html = 'Approve';
                }
                /*console.log(action);
                return false;*/
                var form = $('#update_gate_pass_request');

                form.closest('.modal').modal('hide');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, '+confirmButtonText_html+' it!',
                    cancelButtonText: 'No, Go Back!',
                    customClass: { confirmButton: "btn btn-sm btn-primary", cancelButton: "btn btn-sm btn-secondary" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        var data = new FormData(form[0]);
                        data.append('status', status);
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('ajax/administrative/update-gate-pass-request'); ?>",
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
                                            $("#gate_pass_approval_table").DataTable().ajax.reload();
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
                            url: "<?php echo base_url('ajax/administrative/reject-gate-pass-request'); ?>",
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(response){
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
                                            $("#gate_pass_approval_table").DataTable().ajax.reload();
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
        })
    </script>

    

    <?= $this->endSection() ?>
<?= $this->endSection() ?>