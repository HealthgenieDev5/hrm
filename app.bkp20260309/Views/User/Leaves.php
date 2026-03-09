<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Leave Requests</h3>
                </div>
                <div class="card-body">
                    <table id="leave_requests_table" class="table table-striped nowrap">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Req#</strong></th>
                                <th class="text-center"><strong>From</strong></th>
                                <th class="text-center"><strong>To</strong></th>
                                <th class="text-center"><strong>Days</strong></th>
                                <th class="text-center"><strong>DayType</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Attachment</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Address During Leave</strong></th>
                                <th class="text-center"><strong>Contact During Leave</strong></th>
                                <th class="text-center"><strong>Attachment</strong></th>
                                <th class="text-center"><strong>Reporting Manager</strong></th>
                                <th class="text-center"><strong>HOD</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Requested Date Time</strong></th>
                                <th class="text-center"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Req#</strong></th>
                                <th class="text-center"><strong>From</strong></th>
                                <th class="text-center"><strong>To</strong></th>
                                <th class="text-center"><strong>Days</strong></th>
                                <th class="text-center"><strong>DayType</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Attachment</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Address During Leave</strong></th>
                                <th class="text-center"><strong>Contact During Leave</strong></th>
                                <th class="text-center"><strong>Attachment</strong></th>
                                <th class="text-center"><strong>Reporting Manager</strong></th>
                                <th class="text-center"><strong>HOD</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Requested Date Time</strong></th>
                                <th class="text-center"><strong>Actions</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Balance</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                    <?php
                    foreach($leave_balance as $leave_balance_row ){
                        ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?= $leave_balance_row['leave_code'] ?></span>
                            <span><?= $leave_balance_row['balance'] ?></span>
                        </li>
                        <?php
                    }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <div class="modal fade" tabindex="-1" id="cancel_leave_request_modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="cancel_leave_request" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Leave Request</h5>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-2x"></span>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" id="leave_id" name="leave_id" value="" />
                                <small class="text-danger error-text" id="leave_id_error"><?= isset($validation) ? display_error($validation, 'leave_id') : '' ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">Leave Request ID</label>
                                        <strong id="leave_request_id" class="ms-4 text-primary opacity-75"></strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">Employee Name</label>
                                        <strong id="employee_name" class="ms-4 text-primary opacity-75"></strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">Department</label>
                                        <strong id="department_name" class="ms-4 text-primary opacity-75"></strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">From</label>
                                        <strong id="from_date" class="ms-4 text-info opacity-75"></strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">To</label>
                                        <strong id="to_date" class="ms-4 text-info opacity-75"></strong>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">Status</label>
                                        <strong id="status" class="ms-4 badge badge-danger rounded-pill text-capitalize"></strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">Requested Date Tame</label>
                                        <strong id="date_time" class="ms-4 text-info opacity-75"></strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">Reporting Manager</label>
                                        <strong id="reporting_manager_name" class="ms-4 text-primary opacity-75"></strong>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0">HOD</label>
                                        <strong id="hod_name" class="ms-4 text-muted"></strong>
                                    </li>
                                    <li class="list-group-item d-flex flex-column">
                                        <label class="form-label">Reason</label>
                                        <small id="reason_of_leave" class="text-muted"></small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button id="cancel_leave_request_submit_button" class="btn btn-danger">Mark as Cancelled</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <?= $this->section('javascript') ?>

    <!-- <script src="<?php echo base_url(); ?>assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>    
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            //begin::Initialize Datatable
            var leave_requests_table = $("#leave_requests_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('ajax/get-all-leave-requests') ?>",
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
                    { data: "req_id" },
                    {
                        data: {
                            _: 'from_date.formatted',
                            sort: 'from_date.ordering',
                        }
                    },
                    {
                        data: {
                            _: 'to_date.formatted',
                            sort: 'to_date.ordering',
                        }
                    },
                    { data: "number_of_days" },
                    { data: "day_type" },
                    { 
                        data: "type_of_leave",
                        render : function(data, type, row, meta) {
                            if( row.sick_leave == 'yes' ){
                                return 'SICK LEAVE';
                            }
                            return row.type_of_leave;
                        }
                    },
                    { data: "status", 
                        render : function(data, type, row, meta) {
                            var badge_class = "bg-secondary";
                            if (data == 'cancelled') {
                                badge_class = "bg-dark bg-opacity-50";
                            } else if( data == 'rejected'){
                                badge_class = "bg-danger";
                            }else if(data == 'approved'){
                                badge_class = "bg-success";
                            }else if(data == 'disbursed'){
                                badge_class = "bg-info";
                            }
                            return '<span class="badge text-capitalize rounded-pill '+badge_class+'">'+data+'</span>';
                        }
                    },
                    { 
                        data: "attachment", 
                        render : function(data, type, row, meta) {
                            if( data.length ){
                                var link = '<?php echo base_url(); ?>'+data;
                                return '<a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="'+link+'" target="_blank">View</a>';
                            }else{
                                return '-';
                            }
                        }
                    },
                    { 
                        data: "reason_of_leave",
                        render: function(data, type, row, meta) {
                            return '<p class="text-wrap" style="width: 250px; text-align: justify; text-align-last: center;"><small>' + data + '</small></p>';
                        }
                    },
                    { data: "address_d_l" },
                    { data: "emergency_contact_d_l" },
                    { data: "attachment", 
                        render : function(data, type, row, meta) {
                            if( data.length ){
                                var link = '<?php echo base_url(); ?>'+data;
                                return '<a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="'+link+'" target="_blank">View</a>';
                            }else{
                                return '-';
                            }
                        }
                    },
                    { data: "reporting_manager_name" },
                    { data: "hod_name" },
                    { data: "reviewed_by_name" },
                    {
                        data: {
                            _: 'reviewed_date.formatted',
                            sort: 'reviewed_date.ordering',
                        }
                    },
                    {
                        data: "remarks",
                        render: function(data, type, row, meta) {
                            return '<p class="text-wrap" style="width: 200px; text-align: justify; text-align-last: center;"><small>' + data + '</small></p>';
                        }
                    },
                    {
                        data: {
                            _: 'date_time.formatted',
                            sort: 'date_time.ordering',
                        }
                    },
                    {
                        data: "req_id",
                        render: function(data, type, row, meta) {
                            if(row.status == 'pending'){
                                return '<div class="btn-group btn-group-sm">'
                                +'<button class="cancel-self-leave-request btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-success btn-active-light-success" data-id="'+data+'">Cancel</button>'
                                +'</div>';
                            }
                            else if( row.status == 'approved' && row.type_of_leave == 'CL' && row.is_the_cl_cancellable == 'yes' ){
                                return '<div class="btn-group btn-group-sm">'
                                +'<button class="cancel-self-leave-request btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-success btn-active-light-success" data-id="'+data+'">Cancel</button>'
                                +'</div>';
                            }
                            else{
                                return  '<div class="btn-group btn-group-sm">'
                                +'<button class="btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger disabled" disabled>Locked</button>'
                                +'</div>';
                            }
                        }
                    },
                ],
                "order": [],
                "scrollX": true,
                "scrollY": 'calc(100vh - 410px)',
                "paging" : false,
                "fixedColumns": {
                    right: 1
                },
                "columnDefs": [
                    { "className": 'border-start border-secondary td-border-left text-center', "targets": [-1] },
                    { "className": 'text-center', "targets": '_all' },
                ],
            });
            //end::Initialize Datatable

            //begin::get leave to cancel
            $(document).on('click', '.cancel-self-leave-request', function(e){
                e.preventDefault();
                var leave_id = $(this).data('id');
                if( leave_id == '' ){
                    return false;
                }
                var data = {
                    'leave_id'        :   leave_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/user/get-leave-request'); ?>",
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
                            if( typeof response.response_data.leave_data != 'undefined' ){
                                var leave_data = response.response_data.leave_data;
                                $("form#cancel_leave_request").find('small.error-text').html('');
                                $("form#cancel_leave_request").find('input[name="leave_id"]').val(leave_data.leave_request_id);
                                $("form#cancel_leave_request").find('textarea#remarks').html(leave_data.remarks);
                                console.log(leave_data);
                                $.each(leave_data, function(index, value){
                                    $("form#cancel_leave_request").find('strong#'+index).html(value);
                                    $("form#cancel_leave_request").find('small#'+index).html(value);
                                });
                                $("#cancel_leave_request_modal").modal('show');
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    }
                })
            })
            //end::get leave to cancel

            //begin::Cancel Leave  Ajax
            $(document).on('click', '#cancel_leave_request_submit_button', function(e){
                e.preventDefault();
                var form = $('#cancel_leave_request');
                form.closest('.modal').modal('hide');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel it!',
                    cancelButtonText: 'No, Go Back!',
                    customClass: { confirmButton: "btn btn-sm btn-primary", cancelButton: "btn btn-sm btn-secondary" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        var data = new FormData(form[0]);
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('ajax/user/cancel-leave-request'); ?>",
                            data: data,
                            processData: false,
                            contentType: false,
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
                                        }).then(function (e) {
                                            if( typeof response.response_data.validation != 'undefined' ){
                                                var validation = response.response_data.validation;
                                                $.each(validation, function(index, value){
                                                    form.find('#'+index+'_error').html(value);
                                                });
                                                form.closest('.modal').modal('show');
                                            }
                                        });
                                    }
                                }

                                if( response.response_type == 'success' ){
                                    if( response.response_description.length ){
                                        Swal.fire({
                                            html: response.response_description,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function (e) {
                                            form[0].reset();
                                            $("#leave_requests_table").DataTable().ajax.reload();
                                        });
                                    }
                                }
                            },
                            failed: function(){
                                Swal.fire({
                                    html: "Ajax Failed, Please contact administrator",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        })
                    }else{
                        form.closest('.modal').modal('show');
                    }
                })
            })
            //end::Cancel Leave  Ajax 

        })
        
    
    </script>

    <?= $this->endSection() ?>
<?= $this->endSection() ?>