<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Comp Off Utilization Requests</h3>
                    <div class="card-toolbar">
                        
                    </div>
                </div>
                <div class="card-body">
                    <table id="comp_off_utilization_requests_table" class="table table-striped nowrap">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Requested By</strong></th>
                                <th class="text-center"><strong>Date Time</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Requested By</strong></th>
                                <th class="text-center"><strong>Date Time</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <?= $this->section('javascript') ?>

    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            //begin::Initialize Datatable
            var table = $("#comp_off_utilization_requests_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('ajax/backend/user/get-all-comp-off-utilization-requests') ?>",
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
                            _: 'date.formatted',
                            sort: 'date.ordering',
                        },
                        render: function (data, type, row) {
                            if (type === 'display') {
                                return '<span class="d-block badge text-capitalize rounded-pill text-dark border">'+data+'</span>';
                            }
                            return data;
                        }
                    },
                    { data: 'minutes' },
                    { data: 'type' },
                    { data: 'requested_by_name' },
                    { 
                        data: {
                            _: 'date_time.formatted',
                            sort: 'date_time.ordering',
                        },
                        render: function (data, type, row, meta){
                            return '<span class="d-block badge text-capitalize rounded-pill text-dark border">'+data+'</span>';
                        } 
                    },
                    { 
                        data: 'id',
                        render: function(data, type, row, meta) {
                            // if( ( row.salary_status != 'finalized' && row.salary_status != 'disbursed' ) && row.type == 'utilized' ){
                            // if( 
                            //     (row.salary_status == 'generated' || row.salary_status == 're-generated' || row.salary_status == 'unhold') 
                            //     && row.type == 'utilized' 
                            // ){
                            //     return '<div class="btn-group btn-group-sm">'
                            //     +'<button class="cancel-comp-off-minutes-utilization-request btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-success btn-active-light-success" data-id="'+data+'">Cancel</button>'
                            //     +'</div>';
                            // }
                            
                            if( row.cancellable == 'yes' && row.type == 'utilized' ){
                                return '<div class="btn-group btn-group-sm">'
                                +'<button class="cancel-comp-off-minutes-utilization-request btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-success btn-active-light-success" data-id="'+data+'">Cancel</button>'
                                +'</div>';
                            }else if(row.type !== 'utilized' ){
                                return  '<div class="btn-group btn-group-sm">'
                                +'<button class="btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger disabled" disabled>Cancelled</button>'
                                +'</div>';
                            }else{
                                return  '<div class="btn-group btn-group-sm">'
                                +'<button class="btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger disabled" disabled>Locked</button>'
                                +'</div>';
                            }
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
            //end::Initialize Datatable

            $(document).on('click', '.cancel-comp-off-minutes-utilization-request', function(e){
                e.preventDefault();
                var request_id = $(this).data('id');
                if( request_id == '' ){
                    return false;
                }
                var data = {
                    'request_id' : request_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/backend/user/cancel-comp-off-utilization-requests'); ?>",
                    data: data,
                    success: function(response){
                        console.log(response);
                        if( response.response_type == 'error' ){
                            if( response.response_description.length ){
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        }

                        if( response.response_type == 'success' ){
                            if( response.response_description.length ){
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-success" },
                                }).then(function(e) {
                                    $("#comp_off_utilization_requests_table").DataTable().ajax.reload();
                                    // $("#leave_balance_current_month").DataTable().ajax.reload();
                                    // $("#leave_balance_next_month").DataTable().ajax.reload();
                                })
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-warning" },
                        })
                    }
                })
            })

        })
    </script>

    <?= $this->endSection() ?>
<?= $this->endSection() ?>