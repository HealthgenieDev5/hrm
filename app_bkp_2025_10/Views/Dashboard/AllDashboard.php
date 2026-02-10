<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>


<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
  color: var(--bs-dark);
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
            <div class="row gy-5 g-xl-8">
                <div class="col-xl-6">
                    <label class="form-label" for="company_id_for_filter" class="mb-3">Company</label>
                    <select class="form-select form-select-sm" id="company_id_for_filter" data-control="select2" data-placeholder="Select a Company" >
                        <option value=""></option>
                        <option value="all_companies" selected >All Companies</option>
                        <?php
                        foreach( $Companies as $company_row){
                            ?>
                            <option value="<?php echo $company_row['id']; ?>" <?= edit_set_select('company_id_for_filter', $company_row['id'], $company_id_for_filter) ?> ><?php echo $company_row['company_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>



<div class="row gy-5 g-xl-8">

    <!--begin::Missed Punching report-->
    <div class="col-xl-6">
        <table id="absent_punching_report_table" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <!-- <th class="text-center"><strong>Date</strong></th> -->
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <!-- <th class="text-center"><strong>Date</strong></th> -->
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
    <!--end::absent Punching report-->

    <!--begin::Missed Punching report-->
    <div class="col-xl-6">
        <table id="missed_punching_report_table" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <!-- <th class="text-center"><strong>Date</strong></th> -->
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <!-- <th class="text-center"><strong>Date</strong></th> -->
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
    <!--end::Missed Punching report-->

    <!--begin::Late Coming report-->
    <div class="col-xl-12">
        <table id="late_coming_report_table" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Shift Time</strong></th>
                    <th class="text-center"><strong>In Time</strong></th>
                    <th class="text-center"><strong>Late Minutes</strong></th>
                    <th class="text-center"><strong>7 Days AVG</strong></th>
                    <th class="text-center"><strong>15 Days AVG</strong></th>
                    <th class="text-center"><strong>MTD AVG</strong></th>
                    <th class="text-center"><strong>Dept</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <!-- <th class="text-center"><strong>Date</strong></th> -->
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Shift Time</strong></th>
                    <th class="text-center"><strong>In Time</strong></th>
                    <th class="text-center"><strong>Late Minutes</strong></th>
                    <th class="text-center"><strong>7 Days AVG</strong></th>
                    <th class="text-center"><strong>15 Days AVG</strong></th>
                    <th class="text-center"><strong>MTD AVG</strong></th>
                    <th class="text-center"><strong>Dept</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <!-- <th class="text-center"><strong>Date</strong></th> -->
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
    <!--end::Late Coming report-->

    <!--begin::Employee On OD Today-->
    <div class="col-xl-6">
        <div class="row">
            <div class="col-md-6">
                <table id="employee_on_od_table_pending" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Emp Name</strong></th>
                            <th class="text-center"><strong>Pre/Post</strong></th>
                            <th class="text-center"><strong>Estimated From</strong></th>
                            <th class="text-center"><strong>Estimated To</strong></th>
                            <th class="text-center"><strong>Actual From</strong></th>
                            <th class="text-center"><strong>Actual To</strong></th>
                            <th class="text-center"><strong>Hours</strong></th>
                            <th class="text-center"><strong>Duty Location</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date Time</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <!-- <th class="text-center"><strong>Updated Date Time</strong></th> -->
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Emp Name</strong></th>
                            <th class="text-center"><strong>Pre/Post</strong></th>
                            <th class="text-center"><strong>Estimated From</strong></th>
                            <th class="text-center"><strong>Estimated To</strong></th>
                            <th class="text-center"><strong>Actual From</strong></th>
                            <th class="text-center"><strong>Actual To</strong></th>
                            <th class="text-center"><strong>Hours</strong></th>
                            <th class="text-center"><strong>Duty Location</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date Time</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <!-- <th class="text-center"><strong>Updated Date Time</strong></th> -->
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table id="employee_on_od_table_approved" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Emp Name</strong></th>
                            <th class="text-center"><strong>Pre/Post</strong></th>
                            <th class="text-center"><strong>Estimated From</strong></th>
                            <th class="text-center"><strong>Estimated To</strong></th>
                            <th class="text-center"><strong>Actual From</strong></th>
                            <th class="text-center"><strong>Actual To</strong></th>
                            <th class="text-center"><strong>Hours</strong></th>
                            <th class="text-center"><strong>Duty Location</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date Time</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <!-- <th class="text-center"><strong>Updated Date Time</strong></th> -->
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Emp Name</strong></th>
                            <th class="text-center"><strong>Pre/Post</strong></th>
                            <th class="text-center"><strong>Estimated From</strong></th>
                            <th class="text-center"><strong>Estimated To</strong></th>
                            <th class="text-center"><strong>Actual From</strong></th>
                            <th class="text-center"><strong>Actual To</strong></th>
                            <th class="text-center"><strong>Hours</strong></th>
                            <th class="text-center"><strong>Duty Location</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date Time</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <!-- <th class="text-center"><strong>Updated Date Time</strong></th> -->
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Employee On OD Today-->

    <!--begin::Employee On Leave Today-->
    <div class="col-xl-6">
        <div class="row">
            <div class="col-md-6">
                <table id="employee_on_leave_table_pending" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>From</strong></th>
                            <th class="text-center"><strong>To</strong></th>
                            <th class="text-center"><strong>Days</strong></th>
                            <th class="text-center"><strong>Pending</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>From</strong></th>
                            <th class="text-center"><strong>To</strong></th>
                            <th class="text-center"><strong>Days</strong></th>
                            <th class="text-center"><strong>Pending</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table id="employee_on_leave_table_approved" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>From</strong></th>
                            <th class="text-center"><strong>To</strong></th>
                            <th class="text-center"><strong>Days</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>From</strong></th>
                            <th class="text-center"><strong>To</strong></th>
                            <th class="text-center"><strong>Days</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Employee On Leave Today-->

    <!--Comp off credit request with expiry date-->
    <?php if( session()->get('current_user')['role'] == 'hr' ){ ?>
    <div class="col-xl-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Comp Off Credit Requests</h3>
                <div class="card-toolbar">
                    
                </div>
            </div>
            <div class="card-body">
                <table id="comp_off_credit_requests_table" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Date</strong></th>
                            <th class="text-center"><strong>Expiry Date</strong></th>
                            <th class="text-center"><strong>Employee Name</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Exchange</strong></th>
                            <th class="text-center"><strong>Reporting Manager</strong></th>
                            <th class="text-center"><strong>HOD</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Date</strong></th>
                            <th class="text-center"><strong>Expiry Date</strong></th>
                            <th class="text-center"><strong>Employee name</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Exchange</strong></th>
                            <th class="text-center"><strong>Reporting Manager</strong></th>
                            <th class="text-center"><strong>HOD</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
    <!--Comp off credit request with expiry date-->
    
    <!--begin::Punching report-->    
    <div class="col-xl-12">
        <table id="punching_report_table" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Machine</strong></th>
                    <th class="text-center"><strong>In Time</strong></th>
                    <th class="text-center"><strong>Out Time</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <!-- <th class="text-center"><strong>Date</strong></th> -->
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center"><strong>Emp Code</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Machine</strong></th>
                    <th class="text-center"><strong>In Time</strong></th>
                    <th class="text-center"><strong>Out Time</strong></th>
                    <th class="text-center"><strong>Department</strong></th>
                    <th class="text-center"><strong>Company</strong></th>
                    <!-- <th class="text-center"><strong>Date</strong></th> -->
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
    jQuery(document).ready(function($){

        /*begin::missed_punching_report_table*/
        var absent_punching_report_table = $("#absent_punching_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: '',
                    text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                    className: 'btn btn-sm btn-light',
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/dashboard/get-absent-reports') ?>",
                type:  "POST",
                data:  { 
                    company_id : function() { return $('#company_id_for_filter').val(); }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
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
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#absent_punching_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title d-flex flex-column"><span>Absent</span><small class="text-dark"><?php echo date('d M Y') ?></small></h3>');
        /*end::missed_punching_report_table*/

        /*begin::missed_punching_report_table*/
        var missed_punching_report_table = $("#missed_punching_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                // { extend: 'excel', text: '<i class="fa-solid fa-file-excel"></i> Excel', className: 'btn btn-sm btn-light' },
                // {
                //     text: '<i class="fa-solid fa-arrow-up-right-from-square"></i> Details',
                //     action: function ( e, dt, node, config ) {
                //         alert( 'Under construction' );
                //     },
                //     className: 'btn btn-sm btn-light'
                // }
                {
                    extend: 'excelHtml5',
                    title: '',
                    text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                    className: 'btn btn-sm btn-light',
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/dashboard/get-missed-punching-reports') ?>",
                type:  "POST",
                data:  { 
                    company_id : function() { return $('#company_id_for_filter').val(); }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
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
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#missed_punching_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title d-flex flex-column"><span>Missed Punching</span><small class="text-dark"><?php echo date('d M Y') ?></small></h3>');
        /*end::missed_punching_report_table*/

        /*begin::late_coming_report_table*/
        var late_coming_report_table = $("#late_coming_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                { 
                    extend: 'excel', 
                    text: '<i class="fa-solid fa-file-excel"></i> Excel', 
                    className: 'btn btn-sm btn-light',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/dashboard/get-late-coming-reports') ?>",
                type:  "POST",
                data:  { 
                    company_id : function() { return $('#company_id_for_filter').val(); }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
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
                { data: "shift_start" },
                { data: "in_time"},
                { data: "late_minutes", 
                    render: function(data, type, row, meta){
                        return '<strong>'+data+'</strong>';
                    }
                },
                { data: "avg_7d", 
                    render: function(data, type, row, meta){
                        return '<strong>'+data+'</strong>';
                    }
                },
                { data: "avg_15d", 
                    render: function(data, type, row, meta){
                        return '<strong>'+data+'</strong>';
                    }
                },
                { data: "avg_mtd", 
                    render: function(data, type, row, meta){
                        return '<strong>'+data+'</strong>';
                    }
                },
                { data: "department_name" },
                { data: "company_short_name" },
            ],
            "order": [[4, 'desc']],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#late_coming_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title d-flex flex-column"><span>Late Coming Report</span><small class="text-dark"></small></h3>');
        /*end::late_coming_report_table*/

        /*begin::punching_report_table*/
        var punching_report_table = $("#punching_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: '',
                    text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                    className: 'btn btn-sm btn-light',
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/dashboard/get-punching-reports') ?>",
                type:  "POST",
                data:  { 
                    company_id : function() { return $('#company_id_for_filter').val(); }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
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
                { data: "machine" },
                { data: "in_time", 
                    render: function(data, type, row, meta){
                        return '<strong>'+data+'</strong>';
                    }
                },
                { data: "out_time" },
                { data: "department_name" },
                { data: "company_short_name" },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#punching_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Punching Report</h3>');        
        /*end::punching_report_table*/

        /*begin::employee_on_od_table*/
        var employee_on_od_table_pending = $("#employee_on_od_table_pending").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: '',
                    text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                    className: 'btn btn-sm btn-light',
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/dashboard/get-on-od-today-pending') ?>",
                type:  "POST",
                data:  { 
                    company_id : function() { return $('#company_id_for_filter').val(); }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
            },
            "oLanguage": { "sSearch": "" },
            "columns": [
                { data: "internal_employee_id" },
                { data: "employee_name" },
                { data: "pre_post" },
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
                    data: {
                        _: 'actual_from_date_time.formatted',
                        sort: 'actual_from_date_time.ordering',
                    }
                },
                {
                    data: {
                        _: 'actual_to_date_time.formatted',
                        sort: 'actual_to_date_time.ordering',
                    }
                },
                { data: "interval" },
                { data: "duty_location" },
                { data: "assigned_by" },
                { data: "reason" },
                { data: "status", 
                    render : function(data, type, row, meta) {
                        var badge_class = "bg-secondary";
                        if( data == 'rejected'){
                            badge_class = "bg-danger bg-opacity-15";
                        }else if(data == 'approved'){
                            badge_class = "bg-success bg-opacity-15";
                        }
                        return '<span class="badge text-capitalize text-dark fw-normal rounded-pill '+badge_class+'">'+data+'</span>';
                    }
                },
                { data: "reviewed_by_name" },
                {
                    data: {
                        _: 'reviewed_date_time.formatted',
                        sort: 'reviewed_date_time.ordering',
                    }
                },
                { data: "remarks" },
                {
                    data: {
                        _: 'date_time.formatted',
                        sort: 'date_time.ordering',
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#employee_on_od_table_pending_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Pending OD</h3>');
        /*end::employee_on_od_table*/

        /*begin::employee_on_od_table*/
        var employee_on_od_table_approved = $("#employee_on_od_table_approved").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: '',
                    text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                    className: 'btn btn-sm btn-light',
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/dashboard/get-on-od-today-approved') ?>",
                type:  "POST",
                data:  { 
                    company_id : function() { return $('#company_id_for_filter').val(); }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
            },
            "oLanguage": { "sSearch": "" },
            "columns": [
                { data: "internal_employee_id" },
                { data: "employee_name" },
                { data: "pre_post" },
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
                    data: {
                        _: 'actual_from_date_time.formatted',
                        sort: 'actual_from_date_time.ordering',
                    }
                },
                {
                    data: {
                        _: 'actual_to_date_time.formatted',
                        sort: 'actual_to_date_time.ordering',
                    }
                },
                { data: "interval" },
                { data: "duty_location" },
                { data: "assigned_by" },
                { data: "reason" },
                { data: "status", 
                    render : function(data, type, row, meta) {
                        var badge_class = "bg-secondary";
                        if( data == 'rejected'){
                            badge_class = "bg-danger bg-opacity-15";
                        }else if(data == 'approved'){
                            badge_class = "bg-success bg-opacity-15";
                        }
                        return '<span class="badge text-capitalize text-dark fw-normal rounded-pill '+badge_class+'">'+data+'</span>';
                    }
                },
                { data: "reviewed_by_name" },
                {
                    data: {
                        _: 'reviewed_date_time.formatted',
                        sort: 'reviewed_date_time.ordering',
                    }
                },
                { data: "remarks" },
                {
                    data: {
                        _: 'date_time.formatted',
                        sort: 'date_time.ordering',
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#employee_on_od_table_approved_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Approved OD</h3>');
        /*end::employee_on_od_table*/

        /*begin::employee_on_leave_table*/
        var employee_on_leave_table_pending = $("#employee_on_leave_table_pending").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: '',
                    text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                    className: 'btn btn-sm btn-light',
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/dashboard/get-on-leave-today-pending') ?>",
                type:  "POST",
                data:  { 
                    company_id : function() { return $('#company_id_for_filter').val(); }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                  console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
            },
            "oLanguage": { "sSearch": "" },
            "columns": [
                { data: "internal_employee_id" },
                { data: "employee_name" },
                {
                    data: {
                        _: 'from_date.formatted',
                        sort: 'from_date.ordering',
                    }
                },
                { 
                    data: {
                        _: 'to_date.formatted',
                        sort: 'to_date.ordering',
                    }
                },
                { data: "number_of_days" },
                { data: "pending_days" },
                { data: "status",  
                    render: function(data, type, row, meta){
                        var badge_class = "bg-secondary text-dark";
                        if( data == 'approved'){
                            badge_class = "bg-success bg-opacity-20 text-success";
                        }
                        if( data == 'pending'){
                            badge_class = "bg-warning bg-opacity-25 text-black-50";
                        }
                        return '<span class="badge text-capitalize rounded-pill '+badge_class+'">'+data+'</span>';
                    }
                },
                { data: "department_name" },
                { data: "company_short_name" },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#employee_on_leave_table_pending_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Pending Leaves</h3>');
        /*end::employee_on_leave_table*/

        /*begin::employee_on_leave_table*/
        var employee_on_leave_table_approved = $("#employee_on_leave_table_approved").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: '',
                    text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                    className: 'btn btn-sm btn-light',
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url:  "<?= base_url('ajax/dashboard/get-on-leave-today-approved') ?>",
                type:  "POST",
                data:  { 
                    company_id : function() { return $('#company_id_for_filter').val(); }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
            },
            "oLanguage": { "sSearch": "" },
            "columns": [
                { data: "internal_employee_id" },
                { data: "employee_name" },
                {
                    data: {
                        _: 'from_date.formatted',
                        sort: 'from_date.ordering',
                    }
                },
                { 
                    data: {
                        _: 'to_date.formatted',
                        sort: 'to_date.ordering',
                    }
                },
                { data: "number_of_days" },
                { data: "status",  
                    render: function(data, type, row, meta){
                        var badge_class = "bg-secondary text-dark";
                        if( data == 'approved'){
                            badge_class = "bg-success bg-opacity-20 text-success";
                        }
                        if( data == 'pending'){
                            badge_class = "bg-warning bg-opacity-25 text-black-50";
                        }
                        return '<span class="badge text-capitalize rounded-pill '+badge_class+'">'+data+'</span>';
                    }
                },
                { data: "department_name" },
                { data: "company_short_name" },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                { "className": 'text-center', "targets": '_all' },
            ],
        });
        $('#employee_on_leave_table_approved_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Approved Leaves</h3>');
        /*end::employee_on_leave_table*/

        //begin::Initialize Datatable
        var comp_off_credit_requests_table = $("#comp_off_credit_requests_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        title: '',
                        text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                        className: 'btn btn-sm btn-light',
                    }
                ],
                "ajax": {
                    url:  "<?= base_url('ajax/dashboard/get-all-comp-off-credit-requests') ?>",
                    type:  "POST",
                    data:  { 
                        company_id : function() { return $('#company_id_for_filter').val(); }
                    },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                      alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                },
                "columns": [
                    {
                        data: {
                            _: 'working_date.formatted',
                            sort: 'working_date.ordering',
                        }
                    },
                    {
                        data: {
                            _: 'expiry_date.formatted',
                            sort: 'expiry_date.ordering',
                        }
                    },
                    { data: "employee_name" },
                    { data: "assigned_by_name" },
                    { 
                        data: "reason",
                        render: function(data, type, row, meta){
                            return '<p class="text-wrap" style="width: 200px; text-align: justify;">'+data+'</p>';
                        }
                    },
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
                    { data: "exchange" },
                    { data: "reporting_manager_name" },
                    { data: "hod_name" },
                    { data: "reviewed_by_name" },
                    {
                        data: {
                            _: 'reviewed_date.formatted',
                            sort: 'reviewed_date.ordering',
                        }
                    },
                    { 
                        data: "remarks",
                        render: function(data, type, row, meta){
                            return '<p class="text-wrap" style="width: 200px; text-align: justify;">'+data+'</p>';
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
                "columnDefs": [
                    { "className": 'text-center', "targets": '_all' },
                ],
            });

        $(document).on('change', '#company_id_for_filter', function(){
            $("#absent_punching_report_table").DataTable().ajax.reload();
            $("#punching_report_table").DataTable().ajax.reload();
            $("#missed_punching_report_table").DataTable().ajax.reload();
            $("#late_coming_report_table").DataTable().ajax.reload();
            $("#employee_on_leave_table_pending").DataTable().ajax.reload();
            $("#employee_on_leave_table_approved").DataTable().ajax.reload();
            $("#employee_on_od_table_pending").DataTable().ajax.reload();
            $("#employee_on_od_table_approved").DataTable().ajax.reload();
            $("#comp_off_credit_requests_table").DataTable().ajax.reload();
        })
    })
</script>

<?= $this->endSection() ?>

<?= $this->endSection() ?>