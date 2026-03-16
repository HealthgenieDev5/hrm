<div class="shadow-sm mb-5">
    <table id="probation_ended" class="table table-sm table-striped table-row-bordered nowrap" style="font-size: 0.75rem !important">
        <thead>
            <tr>
                <th style="text-align: left"><strong>Employee</strong></th>
                <th style="text-align: center"><strong>D.O.J</strong></th>
                <th style="text-align: center"><strong>Type</strong></th>
                <th style="text-align: right"><strong></strong></th>
            </tr>
        </thead>
    </table>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {

        /*begin::probation_ended*/
        var probation_ended = $("#probation_ended").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">f>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-probation-employees') ?>",
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
                    data: "employee_name"
                },
                // { data: "formatted_joining_date" },
                {
                    data: {
                        _: 'joining_date.formatted',
                        sort: 'joining_date.ordering',
                    },
                },
                {
                    data: "probation_status"
                },
                {
                    data: "employee_id",
                    render: function(data, type, row, meta) {
                        var link = "<?php echo base_url('/backend/master/employee/edit/id'); ?>/" + row.employee_id;
                        return '<a href="' + link + '" class="btn btn-icon btn-sm btn-bg-light btn-active-color-primary edit-employee p-0" style="width: max-content; height: max-content;" target="_blank"><span class="svg-icon svg-icon-3"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true" ></i></span></a>';
                    }
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": true,
            "columnDefs": [{
                "className": 'text-center small',
                "targets": '_all'
            }, ],
            // initComplete: function(json) {
            // let returned_data = json;
            // console.log(returned_data);
            // }
        });
        $('#probation_ended_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Confirmation pending from HR</h3>');

    });
</script>
<?= $this->endSection() ?>