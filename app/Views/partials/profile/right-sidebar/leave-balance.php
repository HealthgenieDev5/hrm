<div class="shadow-sm mb-5">
    <table id="leave_balance_current_month" class="table table-sm table-row-bordered">
        <thead>
            <tr>
                <th style="text-align: left"><strong>Type</strong></th>
                <th style="text-align: right"><strong>Balance</strong></th>
            </tr>
        </thead>
    </table>
    <div id="_rh_dates">
        <input type="hidden" id="first_rh_date" value="" />
        <input type="hidden" id="second_rh_date" value="" />
    </div>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        /*begin::leave_balance_current_month*/
        var leave_balance_current_month = $("#leave_balance_current_month").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-leave-balance-on-profile-page') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                // dataSrc: "",
                dataSrc: function(receivedData) {
                    if (receivedData.length) {
                        $.each(receivedData, function(index, item) {
                            if (item.leave_code == 'RH') {
                                var _rh_dates = item.rh_dates;
                                if (_rh_dates.length > 0) {
                                    if (_rh_dates.length == 2) {
                                        $('#first_rh_date').val(_rh_dates[0]).trigger('change');
                                        $('#second_rh_date').val(_rh_dates[1]).trigger('change');
                                    } else {
                                        $('#first_rh_date').val(_rh_dates[0]).trigger('change');
                                        $('#second_rh_date').val('').trigger('change');
                                    }
                                }
                            }
                        })
                    }
                    $("#create_leave_request_button_trigger").html('<i class="fa fa-plus"></i> Request a Leave').removeAttr('disabled');
                    // console.log('The data has arrived', receivedData);
                    return receivedData;
                },
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
            ],
            "scrollX": true,
            "paging": false,
            "ordering": false,
            "columnDefs": [{
                "className": 'text-end',
                "targets": [1]
            }, ],
            // initComplete: function(json) {
            // let returned_data = json;
            // console.log(returned_data);
            // }
        });
        $('#leave_balance_current_month_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Leave Balance</h3>');
        $('#leave_balance_current_month_wrapper > .card > .card-footer').html('<small class="d-block">If leave balance is incorrect Please contact Developer on ext 452</small>');
        /*end::leave_balance_current_month*/
    });
</script>
<?= $this->endSection() ?>