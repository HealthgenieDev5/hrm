<style>
    .dataTables_scrollBody {
        max-height: 400px;
    }
</style>
<div class="mb-5">
    <table id="leave_report_table" class="table table-striped table-row-bordered nowrap">
        <thead>
            <tr>
                <th class="text-center"><strong>Leave Date</strong></th>
                <th class="text-center"><strong>Days</strong></th>
                <th class="text-center"><strong>Day Type</strong></th>
                <th class="text-center"><strong>Leave Code</strong></th>
                <th class="text-center"><strong>Status</strong></th>
                <th class="text-center"><strong>Reviewed By</strong></th>
                <th class="text-center"><strong>Reviewed Date</strong></th>
                <th class="text-center"><strong>Remarks</strong></th>
                <th class="text-center"><strong>Address During Leave</strong></th>
                <th class="text-center"><strong>Contact During Leave</strong></th>
                <th class="text-center"><strong>Reason</strong></th>
                <th class="text-center"><strong>Attachment</strong></th>
                <th class="text-center"><strong>Requested Date Time</strong></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="text-center"><strong>Leave Date</strong></th>
                <th class="text-center"><strong>Days</strong></th>
                <th class="text-center"><strong>Day Type</strong></th>
                <th class="text-center"><strong>Leave Code</strong></th>
                <th class="text-center"><strong>Status</strong></th>
                <th class="text-center"><strong>Reviewed By</strong></th>
                <th class="text-center"><strong>Reviewed Date</strong></th>
                <th class="text-center"><strong>Remarks</strong></th>
                <th class="text-center"><strong>Address During Leave</strong></th>
                <th class="text-center"><strong>Contact During Leave</strong></th>
                <th class="text-center"><strong>Reason</strong></th>
                <th class="text-center"><strong>Attachment</strong></th>
                <th class="text-center"><strong>Requested Date Time</strong></th>
            </tr>
        </tfoot>
        <tbody>
        </tbody>
    </table>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        /*begin::leave_report_table*/
        var leave_report_table = $("#leave_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                // { extend: 'excel', text: '<i class="fa-solid fa-file-excel"></i> Excel', className: 'btn btn-sm btn-light' },
                {
                    text: '<i class="fa-solid fa-arrow-up-right-from-square"></i> Details',
                    action: function(e, dt, node, config) {
                        // alert( 'Under construction' );
                        window.open('<?= base_url('/backend/user/leaves') ?>', '_blank');
                    },
                    className: 'btn btn-sm btn-light'
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-leave-reports') ?>",
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
                    data: "from_date",
                    render: function(data, type, row, meta) {
                        var status_html = '<div class="d-flex flex-column">' +
                            '<span>' + data + '</span>' +
                            '<span>' + row.to_date + '</span>' +
                            '</div>';
                        return status_html;
                    }
                },
                {
                    data: "number_of_days"
                },
                {
                    data: "day_type"
                },
                {
                    data: "type_of_leave",
                    render: function(data, type, row, meta) {
                        if (row.sick_leave == 'yes') {
                            return 'SICK LEAVE';
                        }
                        return row.type_of_leave;
                    }
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        var badge_class = "bg-secondary";
                        if (data == 'cancelled') {
                            /*badge_class = "bg-danger bg-opacity-15";*/
                            badge_class = "bg-dark bg-opacity-50";
                        } else if (data == 'rejected') {
                            /*badge_class = "bg-danger bg-opacity-15";*/
                            badge_class = "bg-danger";
                        } else if (data == 'approved') {
                            /*badge_class = "bg-success bg-opacity-15";*/
                            badge_class = "bg-success";
                        }
                        /*return '<span class="badge text-capitalize text-dark fw-normal rounded-pill ' + badge_class + '">' + data + '</span>';*/
                        return '<span class="badge text-capitalize rounded-pill ' + badge_class + '">' + data + '</span>';
                    }
                },
                {
                    data: "reviewed_by_name"
                },
                {
                    data: "reviewed_date"
                },
                {
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap" style="width: 200px">' + data + '</p>';
                    }
                },
                {
                    data: "address_d_l"
                },
                {
                    data: "emergency_contact_d_l"
                },
                {
                    data: "reason_of_leave",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap" style="width: 200px">' + data + '</p>';
                    }
                },
                {
                    data: "attachment",
                    render: function(data, type, row, meta) {
                        if (data.length) {
                            var link = '<?php echo base_url(); ?>' + data;
                            return '<a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="' + link + '" target="_blank">View</a>';
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: "date_time"
                },
            ],
            "order": [],
            // "order": [[8, 'desc']],
            "scrollX": true,
            // "scrollY": 'auto',
            // "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                // { "className": 'text-center fw-bold', "targets": [2] },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
            ],
        });
        $('#leave_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Leave Report</h3>');
        /*end::leave_report_table*/
    });
</script>
<?= $this->endSection() ?>