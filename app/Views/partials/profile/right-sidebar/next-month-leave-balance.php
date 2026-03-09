<div class="shadow-sm mb-5">
    <table id="leave_balance_next_month" class="table table-sm table-row-bordered">
        <thead>
            <tr>
                <th style="text-align: left"><strong>Type</strong></th>
                <th style="text-align: center"><strong>Estimated</strong></th>
                <th style="text-align: right"><strong>Eligible</strong></th>
            </tr>
        </thead>
    </table>
</div>
<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        /*begin::leave_balance_next_month*/
        var leave_balance_next_month = $("#leave_balance_next_month").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-leave-balance-of-next-month-on-profile-page') ?>",
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
                    data: "leave_code"
                },
                {
                    data: "balance"
                },
                {
                    data: "eligible_balance"
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": false,
            "columnDefs": [{
                    "className": 'text-center',
                    "targets": [1]
                },
                {
                    "className": 'text-end',
                    "targets": [2]
                },
                {
                    "defaultContent": "-",
                    "targets": "_all"
                }
            ],
        });
        $('#leave_balance_next_month_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Estimated/Eligible Leave Balance Next Month</h3>');
        $('#leave_balance_next_month_wrapper > .card > .card-footer').html('<small style="font-size: 0.7em;">Estimated = Current month balance + Estiamted credit of next month excluding next month requests</small><hr><small style="font-size: 0.7em;">Eligible = Estiamted credit of next month excluding next month requests (System cannot predict how many leaves are you going to apply in current month, Therefore usable balance for next month can be only the balance which will be credited next month excluding next month requests)</small>');
        /*end::leave_balance_next_month*/
    });
</script>
<?= $this->endSection() ?>