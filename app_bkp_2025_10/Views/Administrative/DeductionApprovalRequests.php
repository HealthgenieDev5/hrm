<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<style type="text/css">
    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
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
                    <form id="filter_form" class="row" enctype='multipart/form-data'>
                        <div class="col-lg-2">
                            <label class="form-label" for="company" class="mb-3">Company</label>
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
                                if( !empty($statuses) ){
	                                foreach($statuses as $status){
	                                	?>
	                                	<option value="<?php echo $status['current_status']; ?>" <?php echo ( isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array( $status['current_status'], $_REQUEST['status']) && !in_array( 'all_status', $_REQUEST['status']) ) ? 'selected' : ''; ?> ><?php echo ucwords($status['current_status']); ?></option>
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
                                <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                                <input type="text" id="deduction_month" name="deduction_month" class="form-control form-control-sm ps-7" value="<?php echo isset($_REQUEST['deduction_month']) && !empty($_REQUEST['deduction_month']) ? $_REQUEST['deduction_month'] : date('M Y') ?>" placeholder="Deduct From" />
                            </div>
                            <small class="text-danger error-text" id="deduction_month_error"></small>
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

        <div class="col-12">
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <table id="deduction_approval_requests_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Current Status</strong></th>
                                <th class="text-center"><strong>Deducted By</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewer's Remarks</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Current Status</strong></th>
                                <th class="text-center"><strong>Deducted By</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewer's Remarks</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



		<div class="modal fade" tabindex="-1" id="update_deduction_request_modal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="update_deduction_request" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title">Approve/Reject Deduction Request</h5>
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
                                    <input type="hidden" id="deduction_request_id" name="deduction_request_id" value="" />
                                    <small class="text-danger error-text" id="deduction_request_id_error"><?= isset($validation) ? display_error($validation, 'deduction_request_id') : '' ?></small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5">
                                    <ul class="list-group list-group-flush mb-5">
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <label class="form-label mb-0">Req. ID</label>
                                            <strong id="deduction_request_id" class="ms-4 text-primary opacity-75"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <label class="form-label mb-0">Deducted Minutes</label>
                                            <strong id="minutes" class="ms-4 text-danger text-capitalize"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <label class="form-label mb-0">Deduction Date</label>
                                            <strong id="date" class="ms-4 text-info text-capitalize"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <label class="form-label mb-0">Deducted by</label>
                                            <strong id="deducted_by_name" class="ms-4 text-info text-capitalize"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <label class="form-label mb-0">Current Status</label>
                                            <strong id="current_status" class="ms-4 badge badge-danger rounded-pill text-capitalize"></strong>
                                        </li>
                                    </ul>

                                    <ul class="list-group list-group-flush" style="font-size: 0.85rem;">
                                        <li class="list-group-item d-flex align-items-center justify-content-center">
                                            <h5>Additional Details</h5>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between py-1">
                                            <label class="form-label mb-0" style="font-size: 0.85rem;">Rep. Mgr.</label>
                                            <strong id="reporting_manager_name" class="ms-4 opacity-75" style="font-size: 0.85rem;"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between py-1">
                                            <label class="form-label mb-0" style="font-size: 0.85rem;">HOD</label>
                                            <strong id="department_hod_name" class="ms-4 text-muted" style="font-size: 0.85rem;"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between py-1">
                                            <label class="form-label mb-0" style="font-size: 0.85rem;">In Time</label>
                                            <strong id="in_time" class="ms-4 text-muted" style="font-size: 0.85rem;"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between py-1">
                                            <label class="form-label mb-0" style="font-size: 0.85rem;">Out Time</label>
                                            <strong id="out_time" class="ms-4 text-muted" style="font-size: 0.85rem;"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between py-1">
                                            <label class="form-label mb-0" style="font-size: 0.85rem;">Shift Start</label>
                                            <strong id="shift_start" class="ms-4 opacity-75" style="font-size: 0.85rem;"></strong>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between py-1">
                                            <label class="form-label mb-0" style="font-size: 0.85rem;">Shift End</label>
                                            <strong id="shift_end" class="ms-4 opacity-75" style="font-size: 0.85rem;"></strong>
                                        </li>                                        
                                    </ul>
                                </div>
                                <div class="col-lg-7">
                                    <ul class="list-group list-group-sm list-group-flush">
                                    	<li class="list-group-item d-flex align-items-center justify-content-center">
                                            <label class="form-label mb-0">Timeline</label>
                                        </li>
                                        <li class="list-group-item" id="timeline_content" style="max-height: 40vh; overflow-y: auto;">
                                        </li>
                                    </ul>
                                </div>


                                <div class="col-lg-12">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <label class="form-label">Remarks</label>
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
                                <button id="approve_deduction_request_submit_button" data-status="approved" style="margin: .25rem;" class="btn btn-sm btn-primary update_deduction_request_submit_button">Approve</button>
                                <button id="reject_deduction_request_submit_button" data-status="rejected" style="margin: .25rem;" class="btn btn-sm btn-warning update_deduction_request_submit_button">Reject</button>
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
    <!--end::Row-->
    <?= $this->section('javascript') ?>
    <script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

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
            
            $("#deduction_month").flatpickr({
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "F Y",
                        altFormat: "F Y",
                        theme: "dark",
                    })
                ]
            });

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

            var deduction_approval_requests_table = $("#deduction_approval_requests_table").DataTable({
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('/ajax/backend/administrative/get-all-deduction-approval-requests') ?>",
                    type:  "POST",
                    data:  { filter : function(){ return $('#filter_form').serialize(); } },
                    dataSrc: "",
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
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
                    { data: "id" },
                    { data: "employee_name",
                        render: function(data, type, row, meta){
                            return row.employee_name+"<br>("+row.department_name+" - "+row.company_short_name+")";
                        }
                    },
                    { data: "date" },
                    { data: "minutes" },
                    { 
                    	data: "current_status", 
                        render: function(data, type, row, meta){ 
                        	if(row.current_status == 'pending'){
                        		return `<span class="badge text-capitalize rounded-pill bg-transparent text-dark border border-dashed border-dark">${row.current_status}</span>`;
                        	}else if(row.current_status == 'approved'){
                        		return `<span class="badge text-capitalize rounded-pill bg-success text-white">${row.current_status}</span>`;
                        	}else if(row.current_status == 'rejected'){
                        		return `<span class="badge text-capitalize rounded-pill bg-danger text-white opacity-50">${row.current_status}</span>`;
                        	}
                            return `<strong class="text-capitalize">${row.current_status}</strong>`;
                        }
                	},
                    { data: "deducted_by_name" },
                    { 
                        data: "initial_remarks", 
                        render: function(data, type, row, meta){
                            if( row.deducted_by_name != '' && row.date_time != '' && row.initial_remarks != '' ){
                            	if( row.attachment != '' ){
                            		return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                            <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.deducted_by_name}</strong></small>
                                            <small class="d-block text-start mb-2">on <strong class="text-danger">${row.date_time}</strong></small>
                                            <small class="d-flex justify-content-start mb-2">
                                                <a class="d-block" href="${row.attachment}" target="_blank">
                                                    <img src="${row.attachment}" class="w-100" style="object-fit: contain; max-height:100px;" />
                                                </a>
                                            </small>
                                            <small class="d-block text-start fst-italic">${row.initial_remarks}</small>
                                        </p>`;
                            	}else{
                            		return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                            <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.deducted_by_name}</strong></small>
                                            <small class="d-block text-start mb-2">on <strong class="text-danger">${row.date_time}</strong></small>
                                            <small class="d-block text-start fst-italic">${row.initial_remarks}</small>
                                        </p>`;
                            	}
                            	
                            }else{
                            	return '';
                            }                             
                        } 
                    },                    
                    { data: "reviewed_by_name" },
                    { 
                        data: "reviewer_remarks", 
                        render: function(data, type, row, meta){
                            if( row.reviewed_by_name != '' && row.reviewed_date != '' && row.reviewer_remarks != '' ){
                            	return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                            <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.reviewed_by_name}</strong></small>
                                            <small class="d-block text-start mb-2">on <strong class="text-danger">${row.reviewed_date}</strong></small>
                                            <small class="d-block text-start fst-italic">${row.reviewer_remarks}</small>
                                        </p>`
                            }else{
                            	return '';
                            }                             
                        } 
                    },
                    { data: "id",
                        render: function(data, type, row, meta){
	                        var is_btn_disabled = '';
                            var data_id = row.id;
                            var btn_opacity_class = '';
	                        var view_deduction_request_button = '<a href="#" class="btn btn-primary btn-sm open-deduction-request px-2 py-1'+btn_opacity_class+is_btn_disabled+'" data-id="'+data_id+'">'+
	                                                '<span class="svg-icon svg-icon-3 m-0">'+
	                                                    '<span class="fa fa-eye" aria-hidden="true" ></span>'+
	                                                '</span>'+
	                                            '</a>';
	                        var action = '<div class="btn-group">'+view_deduction_request_button+'</div>';
	                        // var current_user_employee_id = "<?php echo session()->get('current_user')['employee_id']; ?>";
	                        // return (current_user_employee_id == '1' || current_user_employee_id == '40') ? action : 'Not Allowed';
                            return action;
                        }
                    },
                ],
                "order": [],
                "scrollX": true,
                "scrollY": '400px',
                "scrollCollapse": true,
                "paging" : false,
                "columnDefs": [
                    { "className": 'text-center', "targets": '_all' },
                ],
            });


			//begin::get Deduction Request to approve
            $(document).on('click', '.open-deduction-request', function(e){
                e.preventDefault();
                var deduction_request_id = $(this).data('id');
                if( deduction_request_id == '' ){
                    return false;
                }
                var data = {
                    'deduction_request_id' : deduction_request_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/administrative/get-deduction-approval-requests'); ?>",
                    data: data,
                    success: function(response){

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
                            if( typeof response.response_data.deduction_request_data != 'undefined' ){
                                var deduction_request_data = response.response_data.deduction_request_data;
                                $("form#update_deduction_request").find('small.error-text').html('');
                                $("form#update_deduction_request").find('input[name="deduction_request_id"]').val(deduction_request_data.id);
                                $.each(deduction_request_data, function(index, value){
                                    $("form#update_deduction_request").find('strong#'+index).html(value);
                                    $("form#update_deduction_request").find('small#'+index).html(value);
                                });
                                $("form#update_deduction_request").find('strong#deduction_request_id').html(deduction_request_data.id);

                                var initial_timeline = `<div style="display: flex;">
                                        		<div style="margin-right: 1.5rem; position: relative;">
							                        <span style="display: block; width: 1rem; height: 1rem; border-radius: 50%; background: #62b36a;"></span>
							                        <span style="display: block; margin: 0 auto; width: 2px; height: 100%; background: #62b36a;"></span>
							                    </div>
							                    <div class="border border-dashed border-1 border-dark px-3 py-2 rounded w-100 shadow-sm mb-3 flex-grow-1">
								                    <p><strong>${deduction_request_data.minutes}</strong> minutes deducted for <strong>${deduction_request_data.date}</strong></p>
								                    ${ deduction_request_data.attachment != '' ? `<p><a class="d-block" href="${deduction_request_data.attachment}" target="_blank"><img src="${deduction_request_data.attachment}" class="w-100" style="object-fit: contain; max-height:150px;" /></a></p>` : `` }
	                                        		<p>Remarks: <small style="font-style: italic;">${deduction_request_data.initial_remarks}</small></p>
	                                        		<p class="mb-0" style="font-size: 0.85rem; text-align: right;">by <small><strong>${deduction_request_data.deducted_by_name}</strong></small><br>on <small style="color: #5b5b5b;">${deduction_request_data.date_time}</small></p>
								                </div>
                                        	</div>`;
                                var approval_timeline = '';
								if( deduction_request_data.reviewed_by_name != '' && deduction_request_data.reviewed_date != '' && deduction_request_data.reviewer_remarks != '' ){
									approval_timeline = `<div style="display: flex;">
                                        		<div style="margin-right: 1.5rem; position: relative;">
							                        <span style="display: block; width: 1rem; height: 1rem; border-radius: 50%; background: #62b36a;"></span>
							                    </div>
							                    <div class="border border-dashed border-1 border-dark px-3 py-2 rounded w-100 shadow-sm mb-3 flex-grow-1">
	                                        		<p>Remarks: <small style="font-style: italic;">${deduction_request_data.reviewer_remarks}</small></p>
	                                        		<p class="mb-0" style="font-size: 0.85rem; text-align: right;">by <small><strong>${deduction_request_data.reviewed_by_name}</strong></small><br>on <small style="color: #5b5b5b;">${deduction_request_data.reviewed_date}</small></p>
								                </div>
                                        	</div>`;
								}

								var timeline_html = `${initial_timeline}${approval_timeline}`;

                                $("form#update_deduction_request").find('li#timeline_content').html(timeline_html);

                                $("#update_deduction_request_modal").modal('show');
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

			//begin::Approve/Reject Leave  Ajax
            $(document).on('click', '.update_deduction_request_submit_button', function(e){
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
                var form = $('#update_deduction_request');

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
                            url: "<?php echo base_url('/ajax/backend/administrative/update-deduction-approval-requests'); ?>",
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
                                            $("#deduction_approval_requests_table").DataTable().ajax.reload();
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
            //end::Approve/Reject Leave  Ajax


        })
    </script>

    

    <?= $this->endSection() ?>
<?= $this->endSection() ?>