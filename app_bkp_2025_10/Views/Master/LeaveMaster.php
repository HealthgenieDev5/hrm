<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">
            



            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Leaves</h3>
                    <div class="card-toolbar">

                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_leave_modal">
                            <i class="fa fa-plus" ></i> Add New
                        </button>
                        <div class="modal fade" tabindex="-1" id="add_leave_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="add_leave" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Leave</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Leave Code</label>
                                                    <input type="text" name="leave_code" class="form-control form-control-sm form-control-solid" placeholder="Leave Code" value="" oninput="$(this).next().html(''); $(this).val($(this).val().toUpperCase())" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="leave_code_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Leave Name</label>
                                                    <input type="text" name="leave_name" class="form-control form-control-sm form-control-solid" placeholder="Leave Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="leave_name_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Encash</label>
                                                    <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                        <label for="switch_encash_no" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            No
                                                            <input type="radio" name="encash" class="opacity-0 position-absolute" id="switch_encash_no" value="no" checked>
                                                        </label>
                                                        <label for="switch_encash_yes" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            Yes
                                                            <input type="radio" name="encash" class="opacity-0 position-absolute" id="switch_encash_yes" value="yes" >
                                                        </label>
                                                        <a class="bg-success form-control form-control-sm p-0 position-absolute"></a>
                                                    </div>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="encash_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Allocation</label>
                                                    <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                        <label for="switch_annually" class="text-center form-control form-control-sm bg-transparent border-0 " >
                                                            Annually
                                                            <input type="radio" name="allocation" class="opacity-0" id="switch_annually" value="annually" checked>
                                                        </label>
                                                        <label for="switch_monthly" class="text-center form-control form-control-sm bg-transparent border-0 " >
                                                            Monthly
                                                            <input type="radio" name="allocation" class="opacity-0" id="switch_monthly" value="monthly" >
                                                        </label>
                                                        <!-- <label for="switch_weekly" class="text-center form-control form-control-sm bg-transparent border-0 " >
                                                            Weekly
                                                            <input type="radio" name="allocation" class="opacity-0" id="switch_weekly" value="weekly" >
                                                        </label>
                                                        <label for="switch_daily" class="text-center form-control form-control-sm bg-transparent border-0 " >
                                                            Daily
                                                            <input type="radio" name="allocation" class="opacity-0" id="switch_daily" value="daily" >
                                                        </label> -->
                                                        <a class="bg-success form-control form-control-sm p-0 position-aboslute"></a>
                                                    </div>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="allocation_error"></span>
                                                    <!--end::Error Message-->
                                                    
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Limit</label>
                                                    <input type="number" name="limit" class="form-control form-control-sm form-control-solid" placeholder="Limit" value="0" min="0" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="limit_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Carry Forward</label>
                                                    <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                        <label for="switch_carry_forward_no" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            No
                                                            <input type="radio" name="carry_forward" class="opacity-0 position-absolute" id="switch_carry_forward_no" value="no" checked>
                                                        </label>
                                                        <label for="switch_carry_forward_yes" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            Yes
                                                            <input type="radio" name="carry_forward" class="opacity-0 position-absolute" id="switch_carry_forward_yes" value="yes" >
                                                        </label>
                                                        <a class="bg-success form-control form-control-sm p-0 position-absolute"></a>
                                                    </div>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="carry_forward_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Carry Forward Threshold</label>
                                                    <input type="number" name="carry_forward_threshold" class="form-control form-control-sm form-control-solid" placeholder="Carry Forward Threshold" value="0" min="0" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="carry_forward_threshold_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="add_leave_submit_field" name="add_leave_submit_field" value="Add"/>
                                            <button type="submit" id="add_leave_submit_button" class="btn btn-sm btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" tabindex="-1" id="update_leave_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="update_leave" method="post">                                        
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
                                                    <label class="required form-label">Leave Code</label>
                                                    <input type="text" name="leave_code" class="form-control form-control-sm form-control-solid" placeholder="Leave Code" value="" oninput="$(this).next().html(''); $(this).val($(this).val().toUpperCase())" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="leave_code_error"></span>
                                                    <!--end::Error Message-->
                                                    <input type="hidden" name="leave_id" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="leave_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Leave Name</label>
                                                    <input type="text" name="leave_name" class="form-control form-control-sm form-control-solid" placeholder="Leave Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="leave_name_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Encash</label>
                                                    <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                        <label for="switch_encash_no" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            No
                                                            <input type="radio" name="encash" class="opacity-0 position-absolute" id="switch_encash_no" value="no">
                                                        </label>
                                                        <label for="switch_encash_yes" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            Yes
                                                            <input type="radio" name="encash" class="opacity-0 position-absolute" id="switch_encash_yes" value="yes" >
                                                        </label>
                                                        <a class="bg-success form-control form-control-sm p-0 position-absolute"></a>
                                                    </div>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="encash_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Allocation</label>
                                                    <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                        <label for="switch_annually" class="text-center form-control form-control-sm bg-transparent border-0 " >
                                                            Annually
                                                            <input type="radio" name="allocation" class="opacity-0" id="switch_annually" value="annually">
                                                        </label>
                                                        <label for="switch_monthly" class="text-center form-control form-control-sm bg-transparent border-0 " >
                                                            Monthly
                                                            <input type="radio" name="allocation" class="opacity-0" id="switch_monthly" value="monthly" >
                                                        </label>
                                                        <a class="bg-success form-control form-control-sm p-0 position-aboslute"></a>
                                                    </div>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="allocation_error"></span>
                                                    <!--end::Error Message-->
                                                    
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Limit</label>
                                                    <input type="number" name="limit" class="form-control form-control-sm form-control-solid" placeholder="Limit" value="0" min="0" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="limit_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Carry Forward</label>
                                                    <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                        <label for="switch_carry_forward_no" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            No
                                                            <input type="radio" name="carry_forward" class="opacity-0 position-absolute" id="switch_carry_forward_no" value="no">
                                                        </label>
                                                        <label for="switch_carry_forward_yes" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            Yes
                                                            <input type="radio" name="carry_forward" class="opacity-0 position-absolute" id="switch_carry_forward_yes" value="yes" >
                                                        </label>
                                                        <a class="bg-success form-control form-control-sm p-0 position-absolute"></a>
                                                    </div>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="carry_forward_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Carry Forward Threshold</label>
                                                    <input type="number" name="carry_forward_threshold" class="form-control form-control-sm form-control-solid" placeholder="Carry Forward Threshold" value="0" min="0" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="carry_forward_threshold_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="update_leave_submit_field" name="update_leave_submit_field" value="Add"/>
                                            <button type="submit" id="update_leave_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="leaves_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Leave ID</strong></th>
                                <th class="text-center"><strong>Leave Code</strong></th>
                                <th class="text-center"><strong>Leave Name</strong></th>
                                <th class="text-center"><strong>Encash</strong></th>
                                <th class="text-center"><strong>Allocation</strong></th>
                                <th class="text-center"><strong>Limit</strong></th>
                                <th class="text-center"><strong>Carry Forward</strong></th>
                                <th class="text-center"><strong>Carry Forward Threshold</strong></th>
                                <th class="text-center"><strong>Date Time</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Leave ID</strong></th>
                                <th class="text-center"><strong>Leave Code</strong></th>
                                <th class="text-center"><strong>Leave Name</strong></th>
                                <th class="text-center"><strong>Encash</strong></th>
                                <th class="text-center"><strong>Allocation</strong></th>
                                <th class="text-center"><strong>Limit</strong></th>
                                <th class="text-center"><strong>Carry Forward</strong></th>
                                <th class="text-center"><strong>Carry Forward Threshold</strong></th>
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
            var table = $("#leaves_table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "<?= base_url('ajax/load-leaves') ?>",
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td')
                        .addClass('text-center');
                }
            });
            //end::Initialize Datatable

            //begin::Add Leave Ajax
            $(document).on('click', '#add_leave_submit_button', function(e){
                e.preventDefault();
                var form = $('#add_leave');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/add-leave'); ?>",
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
                                    form.closest('.modal').modal('hide');
                                    $("#leaves_table").DataTable().ajax.reload();
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
            })
            //end::Add Leave Ajax

            //begin::Delete Leave Ajax
            $(document).on('click', '.delete-leave', function(e){
                e.preventDefault();
                var leave_id = $(this).data('id');
                var data = {
                    'leave_id'        :   leave_id,
                };

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    customClass: { confirmButton: "btn btn-sm btn-primary", cancelButton: "btn btn-sm btn-secondary" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('ajax/delete-leave'); ?>",
                            data: data,
                            success: function(response){
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
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function(){
                                            $("#leaves_table").DataTable().ajax.reload();
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
                                    customClass: { confirmButton: "btn btn-primary" },
                                })
                            }
                        })
                    }
                })
            })
            //end::Delete Leave Ajax

            //begin::Open Edit Leave Modal
            $(document).on('click', '.edit-leave', function(e){
                e.preventDefault();
                var leave_id = $(this).data('id');
                var data = {
                    'leave_id'        :   leave_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/get-leave'); ?>",
                    data: data,
                    success: function(response){
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
                            if( typeof response.response_data.leave != 'undefined' ){
                                var leave_data = response.response_data.leave;
                                $("form#update_leave").find('small.error-text').html('');
                                $("form#update_leave").find('input[name="leave_id"]').val(leave_data.id);
                                $("form#update_leave").find('input[name="leave_code"]').val(leave_data.leave_code);
                                $("form#update_leave").find('input[name="leave_name"]').val(leave_data.leave_name);
                                $("form#update_leave").find('input[name="encash"][value="'+leave_data.encash+'"]').prop('checked', true);
                                $("form#update_leave").find('input[name="allocation"][value="'+leave_data.allocation+'"]').prop('checked', true);
                                $("form#update_leave").find('input[name="limit"]').val(leave_data.limit);
                                $("form#update_leave").find('input[name="carry_forward"][value="'+leave_data.carry_forward+'"]').prop('checked', true);
                                $("form#update_leave").find('input[name="carry_forward_threshold"]').val(leave_data.carry_forward_threshold);
                                $("#update_leave_modal").modal('show');
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
            //end::Open Edit Leave Modal

            //begin::Update Leave Ajax
            $(document).on('click', '#update_leave_submit_button', function(e){
                e.preventDefault();
                var form = $('#update_leave');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/update-leave'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        
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
                                    form.closest('.modal').modal('hide');
                                    $("#leaves_table").DataTable().ajax.reload();
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
            })
            //end::Update Leave Ajax
            
        })
    </script>
    <script>
        $(document).ready(function(){
            $('#add_leave_modal, #update_leave_modal').on('shown.bs.modal', function (e) {
                var toggleSwitch = $(this).find('.switch-toggle');
                toggleSwitch.each(function( index, thisSwitch){
                    var checked_input = $(thisSwitch).find('label > input:checked').parent();
                    var w = checked_input.outerWidth();
                    var indexoflabel = checked_input.index();
                    $(thisSwitch).find('a').css({ 'width': w, 'left' : indexoflabel*w});
                })
            })

            $(document).on('click', '.switch-toggle > label', function(e){
                var w = $(this).outerWidth();
                $(this).find('input').prop('checked', true);
                $(this).parent().find('a').css({ 'width': w, 'left' : $(this).position().left});
            })
        })
    </script>
    <?= $this->endSection() ?>
<?= $this->endSection() ?>