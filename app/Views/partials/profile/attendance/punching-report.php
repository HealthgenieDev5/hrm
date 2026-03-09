<div class="mb-5">


    <style>
        .dataTables_scrollBody {
            max-height: 400px;
        }
    </style>
    <table id="punching_report_table" class="table table-row-bordered table-striped nowrap">
        <thead>
            <tr>
                <th class="text-center"><strong>Date</strong></th>
                <th class="text-center"><strong>Day</strong></th>
                <th class="text-center"><strong>Status</strong></th>
                <th class="text-center"><strong>Shift</strong></th>
                <th class="text-center"><strong>IN/OUT</strong></th>
                <th class="text-center"><strong>Late IN</strong></th>
                <th class="text-center"><strong>Early Out</strong></th>
                <th class="text-center"><strong>Late+Early</strong></th>
                <th class="text-center"><strong>Work+OD</strong><br><small>Within Shift</small></th>
                <th class="text-center"><strong>paid</strong></th>
                <th class="text-center"><strong>Grace</strong></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="text-center"><strong>Date</strong></th>
                <th class="text-center"><strong>Day</strong></th>
                <th class="text-center"><strong>Status</strong></th>
                <th class="text-center"><strong>Shift</strong></th>
                <th class="text-center"><strong>IN/OUT</strong></th>
                <th class="text-center"><strong>Late IN</strong></th>
                <th class="text-center"><strong>Early Out</strong></th>
                <th class="text-center"><strong>Late+Early</strong></th>
                <th class="text-center"><strong>Work+OD</strong><br><small>Within Shift</small></th>
                <th class="text-center"><strong>paid</strong></th>
                <th class="text-center"><strong>Grace</strong></th>
            </tr>
        </tfoot>
        <tbody>
        </tbody>
    </table>

</div>

<?= $this->section('javascript') ?>
<script>
    jQuery(document).ready(function($) {
        var punching_report_table = $("#punching_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',

            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-punching-reports') ?>",
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
                searchPlaceholder: "Search"
            },
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    data: {
                        _: 'date_time_new.formatted',
                        sort: 'date_time_new.ordering',
                    }
                },
                {
                    data: "day",
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        var status_html = `<div class='d-flex flex-column'><strong class='cursor-pointer' data-bs-toggle='tooltip' data-bs-html='true' title='${row.status_remarks}'>${data}</strong></div>`;
                        return status_html;
                    }
                },
                {
                    render: function(data, type, row, meta) {
                        var shift_start = (row.shift_start != null) ? row.shift_start : '';
                        var shift_end = (row.shift_end != null) ? row.shift_end : '';
                        var status_html = '<div class="d-flex flex-column"><span>' + shift_start + '</span><span>' + shift_end + '</span></div>';
                        return status_html;
                    }
                },
                {
                    render: function(data, type, row, meta) {
                        var in_time_between_shift_with_od = row.in_time_between_shift_with_od !== null ? row.in_time_between_shift_with_od : '--';
                        var out_time_between_shift_with_od = row.out_time_between_shift_with_od !== null ? row.out_time_between_shift_with_od : '--';
                        var punch_in_time = row.punch_in_time !== null ? row.punch_in_time : '--';
                        var punch_out_time = row.punch_out_time !== null ? row.punch_out_time : '--';

                        var biometric_time_html = "<small class='w-100 d-flex align-items-center justify-content-between text-center text-danger' style='max-width: max-content;'>Machine: " + row.machine + "</small><div class='d-flex align-items-center justify-content-between' style='max-width: max-content; padding-top: 2px; padding-bottom: 2px;'>" +
                            "<div class='d-flex flex-column'>";
                        biometric_time_html += "<small class='text-info fs-9 px-1' style='line-height: 1.15;'>Within Shift</small>";
                        if (row.is_onOD == 'yes') {
                            biometric_time_html += "<small class='text-info fs-9 px-1' style='line-height: 1.15;'>With OD</small>";
                        }
                        biometric_time_html += "</div>";

                        biometric_time_html += "<div class='d-flex flex-column border-start border-info'>" +
                            "<small class='text-info fs-9 border-bottom border-info px-1' style='line-height: 1.15;'>" + in_time_between_shift_with_od + "</small>" +
                            "<small class='text-info fs-9 px-1' style='line-height: 1.15;'>" + out_time_between_shift_with_od + "</small>" +
                            "</div>" +
                            "</div>";

                        var punching_time_html = '<div class="cursor-pointer d-flex flex-column align-items-center" data-bs-toggle="tooltip" data-bs-html="true" title="' + biometric_time_html + '" >' +
                            '<span>' + punch_in_time + '</span>' +
                            '<span>' + punch_out_time + '</span>' +
                            '</div>';
                        return punching_time_html;
                    }
                },
                {
                    data: "late_coming_minutes",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "early_going_minutes",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "late_coming_plus_early_going_minutes",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "work_hours_between_shifts_including_od",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "paid",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "grace",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                }
            ],
            "order": [
                [0, 'desc']
            ],
            "scrollX": true,
            "paging": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ],
            "drawCallback": function(settings) {
                var response = settings.json;
            },
            "initComplete": function(settings, json) {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
                get_attendance_stats();

            },
            "headerCallback": function(thead, data, start, end, display) {
                var api = this.api(),
                    data;
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\-,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                late_coming = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(5).header()).html(
                    '<strong>Late IN<br><span class="ms-1 badge badge-danger">' + Math.round(late_coming) + '<span></strong>'
                );

                early_going_minutes = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(6).header()).html(
                    '<strong>Early Out<br><span class="ms-1 badge badge-danger">' + Math.round(early_going_minutes) + '<span></strong>'
                );

                non_working_minutes_including_od = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(7).header()).html(
                    '<strong>Late+Early<br><span class="ms-1 badge badge-danger">' + Math.round(non_working_minutes_including_od) + '<span></strong>'
                );

                paid_days = api
                    .column(9)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(9).header()).html(
                    '<strong>Paid Days<br><span class="ms-1 badge badge-success">' + paid_days + '<span></strong>'
                );

                grace_minutes = api
                    .column(10)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(10).header()).html(
                    '<strong>Grace<br><span class="ms-1 badge badge-success">' + Math.round(grace_minutes) + '<span></strong>'
                );
            }
        });
        $('#punching_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<div class="d-flex flex-column"><h3 class="card-title">Punching Report</h3><small>As per your shift timings</small></div>');
    });
</script>
<?= $this->endSection() ?>