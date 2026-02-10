<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">OD Requests</h3>
                    <div class="card-toolbar d-flex flex-column align-items-end">
                        <!-- <button type="button" class="btn btn-sm btn-light-primary disabled" data-bs-toggle="modal" data-bs-target="#create_od_request_modal">
                            <i class="fa fa-plus" ></i> Create New Request
                        </button> -->
                        <small class="text-muted">New Requests can be created from Homepage</small>
                        <div class="modal fade" tabindex="-1" id="create_od_request_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="create_od_request" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">New OD Request</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">

                                                <div class="col-lg-5 mb-3">
                                                    <label class="form-label">Estimated From</label>
                                                    <div class="input-group">
                                                        <span class="form-control p-0 border-0 flatpicker-wrapper-parent">
                                                            <input type="text" id="estimated_from_date_time" class="form-control" name="estimated_from_date_time" placeholder="Pick a Date" value="<?= set_value('estimated_from_date_time') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" >
                                                        </span>
                                                        <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <small class="text-danger error-text" id="estimated_from_date_time_error"><?= isset($validation) ? display_error($validation, 'estimated_from_date_time') : '' ?></small>
                                                </div>
                                                <div class="col-lg-5 mb-3">
                                                    <label class="form-label">Estimated To</label>
                                                    <div class="input-group">
                                                        <span class="form-control p-0 border-0 flatpicker-wrapper-parent">
                                                            <input type="text" id="estimated_to_date_time" class="form-control" name="estimated_to_date_time" placeholder="Pick a Date" value="<?= set_value('estimated_to_date_time') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" >
                                                        </span>
                                                        <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <small class="text-danger error-text" id="estimated_to_date_time_error"><?= isset($validation) ? display_error($validation, 'estimated_to_date_time') : '' ?></small>
                                                </div>

                                                <div class="col-lg-2 mb-3">
                                                    <label class="form-label">Hours</label>
                                                    <input type="text" class="form-control" id="hours_od" placeholder="--:--" disabled />
                                                </div>
                                                
                                                <div class="col-lg-5 mb-3">
                                                    <label class="form-label">Duty Location</label>
                                                    <input type="text" id="duty_location" name="duty_location" class="form-control " placeholder="Duty Location" value="<?= set_value('duty_location') ?>"/>
                                                    <small class="text-danger error-text" id="duty_location_error"><?= isset($validation) ? display_error($validation, 'duty_location') : '' ?></small>
                                                </div>
                                                <div class="col-lg-7 mb-3">
                                                    <label class="form-label">Assigned by</label>
                                                    <select class="form-control form-control" id="duty_assigner" name="duty_assigner" data-control="select2" data-placeholder="Assigned By" data-allow-clear="true">
                                                        <option></option>
                                                        <?php
                                                        foreach( $employees as $employee){
                                                            ?>                                                            
                                                            <option 
                                                            value="<?php echo $employee['id']; ?>" 
                                                            <?= edit_set_select('duty_assigner', $employee['id'], session()->get('current_user')['employee_id']) ?> 
                                                            >
                                                                <?php #echo trim($employee['first_name'].' '.$employee['last_name']); ?>
                                                                <?php echo ( $employee['id'] == session()->get('current_user')['employee_id'] ) ? 'Self' : trim($employee['first_name'].' '.$employee['last_name']); ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <small class="text-danger error-text" id="duty_assigner_error"><?= isset($validation) ? display_error($validation, 'duty_assigner') : '' ?></small>
                                                </div>
                                                <div class="col-lg-5 mb-3">
                                                    <label class="form-label">Reason</label>
                                                    <input type="text" id="reason" name="reason" class="form-control " placeholder="Reason" value="<?= set_value('reason') ?>"/>
                                                    <small class="text-danger error-text" id="reason_error"><?= isset($validation) ? display_error($validation, 'reason') : '' ?></small>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="create_od_request_submit_field" name="create_od_request_submit_field" value="Add"/>
                                            <button id="create_od_request_submit_button" class="btn btn-sm btn-primary">Create</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="modal fade" tabindex="-1" id="update_od_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="update_od" method="post">                                        
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Leave</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Leave Name</label>
                                                    <input type="text" name="od_name" class="form-control " placeholder="Department Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="od_name_error"></span>
                                                    <!--end::Error Message-->
                                                    <input type="hidden" name="od_id" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="od_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">City</label>
                                                    <input type="text" name="city" class="form-control " placeholder="City"/>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">State</label>
                                                    <input type="text" name="state" class="form-control " placeholder="State"/>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Pincode</label>
                                                    <input type="text" name="pincode" class="form-control " placeholder="Pincode"/>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Phone No</label>
                                                    <input type="text" name="phone_number" class="form-control " placeholder="Phone No"/>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Contact Person Name</label>
                                                    <input type="text" name="contact_person_name" class="form-control " placeholder="Contact Person Name"/>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Contact Person Mobile</label>
                                                    <input type="text" name="contact_person_mobile" class="form-control " placeholder="Contact Person Mobile"/>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Contact Person Email Id</label>
                                                    <input type="text" name="contact_person_email_id" class="form-control " placeholder="Phone No"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="address" class="form-control " placeholder="Address" ></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="update_od_submit_field" name="update_od_submit_field" value="Add"/>
                                            <button type="submit" id="update_od_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="od_requests_table" class="table table-striped nowrap">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Request ID</strong></th>
                                <th class="text-center"><strong>Pre/Post</strong></th>
                                <th class="text-center"><strong>Estimated From</strong></th>
                                <th class="text-center"><strong>Estimated To</strong></th>
                                <th class="text-center"><strong>International</strong></th>
                                <th class="text-center"><strong>Hours</strong></th>
                                <th class="text-center"><strong>Duty Location</strong></th>
                                <th class="text-center"><strong>Assigned By</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Pending Days</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date Time</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Updated Date Time</strong></th>
                                <th class="text-center"><strong>Requested Date Time</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Request ID</strong></th>
                                <th class="text-center"><strong>Pre/Post</strong></th>
                                <th class="text-center"><strong>Estimated From</strong></th>
                                <th class="text-center"><strong>Estimated To</strong></th>
                                <th class="text-center"><strong>International</strong></th>
                                <th class="text-center"><strong>Hours</strong></th>
                                <th class="text-center"><strong>Duty Location</strong></th>
                                <th class="text-center"><strong>Assigned By</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Pending Days</strong></th>
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
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <?= $this->section('javascript') ?>

    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <!-- <link href="<?php echo base_url(); ?>assets/plugins/custom/bootstrap5-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>assets/plugins/custom/bootstrap5-datetimepicker/js/bootstrap-datetimepicker.js"></script> -->

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script> -->

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#duty_assigner').select2({
                dropdownParent: $("#create_od_request_modal")

            });
            $('#estimated_from_date_time').flatpickr({
                enableTime: true,
                static : true,
                onClose: function(selectedDates, dateStr, instance){
                    check_time_interval('estimated_from_date_time', 'estimated_to_date_time', 'estimated_from_date_time', 'hours_od');
                }
            })

            $('#estimated_to_date_time').flatpickr({
                enableTime: true,
                static : true,
                onClose: function(selectedDates, dateStr, instance){
                    check_time_interval('estimated_from_date_time', 'estimated_to_date_time', 'estimated_to_date_time', 'hours_od');
                }
            })

            $(document).on('input', '.form-control', function(){
                $(this).parent().find('.error-text').html('');
            })
            $(document).on('change', '.flatpickr-input', function(){
                $(this).parent().parent().parent().parent().find('.error-text').html('');
            })

            $(document).on('click', '.parent-picker', function(){
                $(this).parent().find('.flatpickr-input').focus();
            })

            //begin::Initialize Datatable
            var table = $("#od_requests_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('ajax/get-all-od-requests') ?>",
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                },
                "columns": [
                    { data: "id" },
                    { data: "pre_post" },
                    {
                        data: {
                            _: 'estimated_from_date_time.formatted',
                            sort: 'estimated_from_date_time.ordering',
                        }
                    },
                    {
                        data: {
                            _: 'estimated_to_date_time.formatted',
                            sort: 'estimated_to_date_time.ordering',
                        }
                    },
                    { 
                        data: "international", 
                        render : function(data, type, row, meta) {
                            return '<span class="text-capitalize">'+data+'</span>';
                        }
                    },
                    { data: "interval" },
                    { data: "duty_location" },
                    { data: "assigned_by_name" },
                    { data: "reason" },
                    { data: "status", 
                        render : function(data, type, row, meta) {
                            var badge_class = "bg-secondary text-dark";
                            if( data == 'rejected'){
                                badge_class = "bg-danger text-danger bg-opacity-15";
                            }else if(data == 'approved'){
                                badge_class = "bg-success text-success bg-opacity-15";
                            }else if(data == 'disbursed'){
                                badge_class = "bg-info text-info bg-opacity-15";
                            }
                            return '<span class="badge text-capitalize rounded-pill '+badge_class+'">'+data+'</span>';
                        }
                    },
                    { data: "pending_days" },
                    { data: "reviewed_by_name" },
                    {
                        data: {
                            _: 'reviewed_date_time.formatted',
                            sort: 'reviewed_date_time.ordering',
                        }
                    },
                    { data: "remarks" },
                    {
                        data: {
                            _: 'updated_date_time.formatted',
                            sort: 'updated_date_time.ordering',
                        }
                    },
                    {
                        data: {
                            _: 'date_time.formatted',
                            sort: 'date_time.ordering',
                        }
                    },
                ],
                "order": [],
                "scrollX": true,
                "scrollY": '400px',
                "paging": false,
                "columnDefs": [
                    { "className": 'text-center', "targets": '_all' },
                ],
            });
            //end::Initialize Datatable

            //begin::Add Leave Ajax
            $(document).on('click', '#create_od_request_submit_button', function(e){
                e.preventDefault();
                // return false;
                var form = $('#create_od_request');
                form.closest('.modal').modal('hide');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/create-od-request'); ?>",
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
                                    stopKeydownPropagation: false
                                }).then(function (e) {
                                    if( typeof response.response_data.validation != 'undefined' ){
                                        var validation = response.response_data.validation;
                                        $.each(validation, function(index, value){
                                            form.find('#'+index+'_error').html(value);
                                            form.closest('.modal').modal('show');
                                        });
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
                                    stopKeydownPropagation: false
                                }).then(function (e) {
                                    form[0].reset();
                                    // form.closest('.modal').modal('hide');
                                    $("#od_requests_table").DataTable().ajax.reload();
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
                            stopKeydownPropagation: false
                        })
                    }
                })
            })
            //end::Add Leave Ajax
            
        })
    </script>

    <?= $this->endSection() ?>
<?= $this->endSection() ?>