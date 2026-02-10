<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>


<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }
</style>
<?php
if (!in_array(session()->get('current_user')['role'], array('admin', 'superuser', 'hr'))) {
    $company_id_disabled = 'disabled';
?>
    <div class="row">
        <div class="col-xl-6">
            <input type="hidden" id="company_id_for_filter" value="<?= set_value('company_id_for_filter', @$company_id_for_filter) ?>" />
        </div>
    </div>
<?php
} else {
?>
    <div class="card mb-7">
        <div class="card-body">
            <form id="filter_form" class="row" enctype='multipart/form-data'>
                <div class="row justify-content-end">
                    <div class="col-lg-2">
                        <label class="form-label" for="reporting_to_me">Reporting To me</label>
                        <select class="form-select form-select-sm" id="reporting_to_me" name="reporting_to_me" data-control="select2" data-placeholder="Select an option" onchange="$('#filter_form').trigger('submit')">
                            <option value="no rule" <?php echo (isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'no rule') ? 'selected' : ''; ?>>Show All</option>
                            <option value="yes" <?php echo (isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'yes') ? 'selected' : ''; ?>>Show Reporting to me</option>
                            <option value="no" <?php echo (isset($_REQUEST['reporting_to_me']) && !empty($_REQUEST['reporting_to_me']) && $_REQUEST['reporting_to_me'] == 'no') ? 'selected' : ''; ?>>Hide Reporting to me</option>
                        </select>
                        <small class="text-danger error-text" id="reporting_to_me_error"></small>
                    </div>

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
        <table id="late_coming_early_going_late_going_table" class="table table-sm table-bordered table-border-custom nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong>Empcode</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Coming </small><br><small> MTD Total </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Coming </small><br><small> MTD Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Coming </small><br><small> 7D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Coming </small><br><small> 15D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Early Leaving </small><br><small> MTD Total </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Early Leaving </small><br><small> MTD Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Early Leaving </small><br><small> 7D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Early Leaving </small><br><small> 15D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Leaving </small><br><small> MTD Total </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Leaving </small><br><small> MTD Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Leaving </small><br><small> 7D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Leaving </small><br><small> 15D Avg </small></strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center"><strong>Empcode</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Coming </small><br><small> MTD Total </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Coming </small><br><small> MTD Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Coming </small><br><small> 7D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Coming </small><br><small> 15D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Early Leaving </small><br><small> MTD Total </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Early Leaving </small><br><small> MTD Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Early Leaving </small><br><small> 7D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Early Leaving </small><br><small> 15D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Leaving </small><br><small> MTD Total </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Leaving </small><br><small> MTD Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Leaving </small><br><small> 7D Avg </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Late Leaving </small><br><small> 15D Avg </small></strong></th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
    <!--end::Punching report-->

    <!--begin::Missed Punching report-->
    <!-- <div class="col-xl-6">
        <table id="missed_punching_table" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong>EmpCode</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Missed Punch </small><br><small> MTD </small><br><small style="font-size: 0.55rem; margin-top: -5px; display: block;"> Missed / Including OD </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Missed Punch </small><br><small> Last 7 Day </small><br><small style="font-size: 0.55rem; margin-top: -5px; display: block;"> Missed / Including OD </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Missed Punch </small><br><small> Last 15 Day </small><br><small style="font-size: 0.55rem; margin-top: -5px; display: block;"> Missed / Including OD </small></strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center"><strong>EmpCode</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Missed Punch </small><br><small> MTD </small><br><small style="font-size: 0.55rem; margin-top: -5px; display: block;"> Missed / Including OD </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Missed Punch </small><br><small> Last 7 Day </small><br><small style="font-size: 0.55rem; margin-top: -5px; display: block;"> Missed / Including OD </small></strong></th>
                    <th class="text-center"><strong><small class="border-bottom"> Missed Punch </small><br><small> Last 15 Day </small><br><small style="font-size: 0.55rem; margin-top: -5px; display: block;"> Missed / Including OD </small></strong></th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div> -->
    <!--end::Missed Punching report-->
</div>
<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        /*begin::late_coming_early_going_late_going_table*/
        var late_coming_early_going_late_going_table = $("#late_coming_early_going_late_going_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                extend: 'excel',
                text: '<i class="fa-solid fa-file-excel"></i> Excel',
                className: 'btn btn-sm btn-light'
            }, ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('/ajax/dashboards/historical-dashboard/get-late-early-late-going-report') ?>",
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
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex flex-column align-items-center justify-content-center h-100 m-auto" style="max-width:max-content">' +
                    '<div class="d-flex align-items-center justify-content-center">' +
                    '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span>' +
                    '</div>' +
                    '<p class="text-center">Big Data Loading please do not refresh</p>' +
                    '</div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                searchPlaceholder: "Search"
            },
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    render: function(data, type, row, meta) {
                        return `<small>${row.internal_employee_id}</small>`;
                    }
                },
                {
                    render: function(data, type, row, meta) {
                        return `<span>${row.employee_name}</span>`;
                    }
                },
                {
                    data: "late_in_mtd_total"
                },
                {
                    data: "late_in_mtd_avg"
                },
                {
                    data: "late_in_7d_avg"
                },
                {
                    data: "late_in_15d_avg"
                },
                {
                    data: "early_out_mtd_total"
                },
                {
                    data: "early_out_mtd_avg"
                },
                {
                    data: "early_out_7d_avg"
                },
                {
                    data: "early_out_15d_avg"
                },
                {
                    data: "late_out_mtd_total"
                },
                {
                    data: "late_out_mtd_avg"
                },
                {
                    data: "late_out_7d_avg"
                },
                {
                    data: "late_out_15d_avg"
                },
            ],
            "order": [2],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "paging": false,
            "columnDefs": [{
                    "className": 'text-center',
                    "targets": [0, 1]
                },
                {
                    "className": 'text-center bg-danger bg-opacity-25',
                    "targets": [2, 3, 4, 5]
                },
                {
                    "className": 'text-center bg-warning bg-opacity-25',
                    "targets": [5, 6, 7, 8]
                },
                {
                    "className": 'text-center bg-success bg-opacity-25',
                    "targets": [9, 10, 11, 12]
                }
            ],
        });
        $('#late_coming_early_going_late_going_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">MTD Report Historical <small>(Unit: Minutes)</small></h3>');
        /*end::punching_report_table*/

        /*begin::missed_punching_report_table*/
        // var missed_punching_table = $("#missed_punching_table").DataTable({
        //     "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
        //     "buttons": [{
        //         extend: 'excel',
        //         text: '<i class="fa-solid fa-file-excel"></i> Excel',
        //         className: 'btn btn-sm btn-light'
        //     }, ],
        //     "lengthMenu": [
        //         [5, 10, 25, 50, 100, -1],
        //         [5, 10, 25, 50, 100, 'All'],
        //     ],
        //     "ajax": {
        //         url: "<?= base_url('/ajax/dashboards/historical-dashboard/get-missing-punching-report') ?>",
        //         type: "POST",
        //         type: "POST",
        //         data: {
        //             filter: function() {
        //                 return $('#filter_form').serialize();
        //             }
        //         },
        //         dataSrc: "",
        //         error: function(jqXHR, ajaxOptions, thrownError) {
        //             alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        //         },
        //     },
        //     "deferRender": true,
        //     "processing": true,
        //     "language": {
        //         processing: '<div class="d-flex flex-column align-items-center justify-content-center h-100 m-auto" style="max-width:max-content">' +
        //             '<div class="d-flex align-items-center justify-content-center">' +
        //             '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span>' +
        //             '</div>' +
        //             '<p class="text-center">Big Data Loading please do not refresh</p>' +
        //             '</div>',
        //         emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
        //         searchPlaceholder: "Search"
        //     },
        //     "oLanguage": {
        //         "sSearch": ""
        //     },
        //     "columns": [{
        //             // data: "employee_name" 
        //             render: function(data, type, row, meta) {
        //                 return `<small>${row.internal_employee_id}</small>`;
        //             }
        //         },
        //         {
        //             // data: "employee_name" 
        //             render: function(data, type, row, meta) {
        //                 return `<span>${row.employee_name}</span>`;
        //             }
        //         },
        //         {
        //             render: function(data, type, row, meta) {
        //                 return row.missed_punch_mtd + ' / ' + row.missed_punch_on_od_mtd;
        //             }
        //         },
        //         {
        //             render: function(data, type, row, meta) {
        //                 return row.missed_punch_last_7day + ' / ' + row.missed_punch_on_od_last_7day;
        //             }
        //         },
        //         {
        //             render: function(data, type, row, meta) {
        //                 return row.missed_punch_last_15day + ' / ' + row.missed_punch_on_od_last_15day;
        //             }
        //         },
        //     ],
        //     "order": [1],
        //     "scrollX": true,
        //     "scrollY": '400px',
        //     "scrollCollapse": true,
        //     "paging": false,
        //     "columnDefs": [{
        //         "className": 'text-center',
        //         "targets": '_all'
        //     }, ],
        // });
        // $('#missed_punching_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Missed Punching</h3>');
        /*end::missed_punching_report_table*/

        $(document).on('change', '#company_id_for_filter', function() {
            $("#punching_report_table").DataTable().ajax.reload();
            //  $("#missed_punching_report_table").DataTable().ajax.reload();
            $("#late_coming_report_table").DataTable().ajax.reload();
            $("#employee_on_leave_table_pending").DataTable().ajax.reload();
            $("#employee_on_leave_table_approved").DataTable().ajax.reload();
            $("#employee_on_od_table_pending").DataTable().ajax.reload();
            $("#employee_on_od_table_approved").DataTable().ajax.reload();
        })
    })
</script>

<?= $this->endSection() ?>

<?= $this->endSection() ?>