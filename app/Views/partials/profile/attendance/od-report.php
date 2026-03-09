<style>
    .dataTables_scrollBody {
        max-height: 400px;
    }
</style>
<div class="row mb-5">
    <div class="col-md-6">
        <table id="od_report_table_approved" class="table table-striped table-row-bordered nowrap">
            <thead>
                <tr>
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
                    <th class="text-center"><strong>Updated Date Time</strong></th>
                    <th class="text-center"><strong>Requested Date Time</strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
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
                    <th class="text-center"><strong>Updated Date Time</strong></th>
                    <th class="text-center"><strong>Requested Date Time</strong></th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table id="od_report_table_pending" class="table table-striped table-row-bordered nowrap">
            <thead>
                <tr>
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
                    <th class="text-center"><strong>Updated Date Time</strong></th>
                    <th class="text-center"><strong>Requested Date Time</strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
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
                    <th class="text-center"><strong>Updated Date Time</strong></th>
                    <th class="text-center"><strong>Requested Date Time</strong></th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        /*begin::od_report_table*/
        var od_report_table_approved = $("#od_report_table_approved").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                text: '<i class="fa-solid fa-arrow-up-right-from-square"></i> Details',
                action: function(e, dt, node, config) {
                    window.open('<?= base_url('/backend/user/od') ?>', '_blank');
                },
                className: 'btn btn-sm btn-light'
            }],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-od-reports-approved') ?>",
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
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    data: "pre_post"
                },
                {
                    data: "estimated_from_date_time"
                },
                {
                    data: "estimated_to_date_time"
                },
                {
                    data: "actual_from_date_time"
                },
                {
                    data: "actual_to_date_time"
                },
                {
                    data: "interval"
                },
                {
                    data: "duty_location"
                },
                {
                    data: "assigned_by"
                },
                {
                    data: "reason"
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
                    data: "reviewed_by_name"
                },
                {
                    data: "reviewed_date_time"
                },
                {
                    data: "remarks"
                },
                {
                    data: "updated_date_time"
                },
                {
                    data: "date_time"
                },
            ],
            "order": [
                [8, 'desc']
            ],
            "scrollX": true,
            "paging": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ],
            fnInitComplete: function() {
                if ($(this).find('tbody tr').length < 1) {
                    /*$(this).parent().hide();*/
                    $("#od_report_table_approved_wrapper").hide();
                }
            },
        });
        $('#od_report_table_approved_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">OD Report Approved</h3>');
        /*end::od_report_table*/
        /*begin::od_report_table*/
        var od_report_table_pending = $("#od_report_table_pending").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                text: '<i class="fa-solid fa-arrow-up-right-from-square"></i> Details',
                action: function(e, dt, node, config) {
                    window.open('<?= base_url('/backend/user/od') ?>', '_blank');
                },
                className: 'btn btn-sm btn-light'
            }],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-od-reports-pending') ?>",
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
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    data: "pre_post"
                },
                {
                    data: "estimated_from_date_time"
                },
                {
                    data: "estimated_to_date_time"
                },
                {
                    data: "actual_from_date_time"
                },
                {
                    data: "actual_to_date_time"
                },
                {
                    data: "interval"
                },
                {
                    data: "duty_location"
                },
                {
                    data: "assigned_by"
                },
                {
                    data: "reason"
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
                    data: "reviewed_by_name"
                },
                {
                    data: "reviewed_date_time"
                },
                {
                    data: "remarks"
                },
                {
                    data: "updated_date_time"
                },
                {
                    data: "date_time"
                },
            ],
            "order": [
                [8, 'desc']
            ],
            "scrollX": true,
            "paging": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ],
            fnInitComplete: function() {
                if ($(this).find('tbody tr').length < 1) {
                    /*$(this).parent().hide();*/
                    $("#od_report_table_pending_wrapper").hide();
                }
            },
        });
        $('#od_report_table_pending_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">OD Report Pending</h3>');
        /*end::od_report_table*/
    });
</script>
<?= $this->endSection() ?>