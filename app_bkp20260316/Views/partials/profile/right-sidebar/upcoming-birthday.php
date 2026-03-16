<div class="shadow-sm mb-5">
    <table id="upcoming_birthdays" class="table table-sm table-striped table-row-bordered nowrap" style="font-size: 0.75rem !important">
        <thead>
            <tr>
                <th style="text-align: left"><strong>Employee</strong></th>
                <th style="text-align: center"><strong>Birthday</strong></th>
                <th style="text-align: right"><strong>When</strong></th>
            </tr>
        </thead>
    </table>
</div>
<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        console.log("Hello from upcoming birthday");
        var upcoming_birthdays = $("#upcoming_birthdays").DataTable({
            "dom": '<"card"<"card-header py-0 pe-0"<"card-title"><"card-toolbar my-0"f>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-upcoming-birthdays') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.error(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No upcoming birthdays</span></div>',
                searchPlaceholder: "Search"
            },
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    data: "employee_name",
                    render: function(data, type, row) {
                        //return data + ' <span class="text-muted">(' + row.employee_code + ')</span>';
                        return data + ' <span class="text-muted">(' + row.department_name + ') - ' + row.company_name + '</span>';
                        return data;
                    }
                },
                {
                    data: "birthday_display"
                },
                {
                    data: "days_left_label",
                    render: function(data, type, row) {
                        if (type === 'sort') return row.days_left;
                        return data;
                    }
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": true,
            "order": [
                [2, "asc"]
            ],
            "columnDefs": [{
                    "className": "text-center small",
                    "targets": "_all"
                },
                {
                    "className": "text-start",
                    "targets": 0
                },
                {
                    "className": "text-end",
                    "targets": 2
                },
            ],
        });
        $('#upcoming_birthdays_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title my-0">Upcoming Birthdays 🎂</h3>');
    });
</script>
<?= $this->endSection() ?>