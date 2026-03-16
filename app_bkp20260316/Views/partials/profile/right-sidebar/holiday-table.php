<div class="shadow-sm mb-5">
    <table id="employee_holidays_table" class="table table-sm table-row-bordered table-striped">
        <thead>
            <tr>
                <th style="text-align: left"><strong>Date</strong></th>
                <th style="text-align: left"><strong>Holiday</strong></th>
                <th style="text-align: center"><strong>Type</strong></th>
                <th style="text-align: center"><strong>Day</strong></th>
            </tr>
        </thead>
    </table>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        var employee_holidays_table = $("#employee_holidays_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-holidays-on-profile-page') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.error('Error loading holidays:', thrownError);
                },
                dataSrc: function(receivedData) {
                    return receivedData;
                },
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Loading Holidays...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Holidays Found</span></div>',
            },
            "columns": [{
                    data: "date",
                    orderable: true,
                    type: "date",
                    className: 'text-nowrap'
                },
                {
                    data: "name",
                    orderable: false
                },
                {
                    data: "code",
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        var badgeClass = 'badge-light-primary';
                        if (data == 'NH') badgeClass = 'badge-light-success';
                        else if (data == 'RH') badgeClass = 'badge-light-info';
                        else if (data == 'SPL HL') badgeClass = 'badge-light-warning';

                        return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                    }
                },
                {
                    data: "day",
                    orderable: false,
                    className: 'text-center text-muted'
                },
            ],
            "scrollX": false,
            "paging": false,
            "ordering": true,
            "order": [
                [0, 'asc']
            ], // Sort by date ascending
            "columnDefs": [],
        });

        $('#employee_holidays_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title"><i class="fa fa-calendar-alt me-2"></i>Holidays ' + new Date().getFullYear() + '</h3>');
        $('#employee_holidays_table_wrapper > .card > .card-footer').html('<small class="text-muted d-block"><i class="fa fa-info-circle me-1"></i>Showing general holidays and special holidays assigned to you</small>');

    });
</script>
<?= $this->endSection() ?>