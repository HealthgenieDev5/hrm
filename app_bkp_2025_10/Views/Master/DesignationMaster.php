<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">
            



            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Designations</h3>
                    <div class="card-toolbar">

                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_designation_modal">
                            <i class="fa fa-plus" ></i> Add New
                        </button>
                        <div class="modal fade" tabindex="-1" id="add_designation_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="add_designation" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Designation</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="required form-label">Designation Name</label>
                                                    <input type="text" name="designation_name" class="form-control form-control-solid" placeholder="Designation Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="designation_name_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="add_designation_submit_field" name="add_designation_submit_field" value="Add"/>
                                            <button type="submit" id="add_designation_submit_button" class="btn btn-sm btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" tabindex="-1" id="update_designation_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="update_designation" method="post">                                        
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Designation</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Designation Name</label>
                                                    <input type="text" name="designation_name" class="form-control form-control-solid" placeholder="Designation Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="designation_name_error"></span>
                                                    <!--end::Error Message-->
                                                    <input type="hidden" name="designation_id" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="designation_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="update_designation_submit_field" name="update_designation_submit_field" value="Add"/>
                                            <button type="submit" id="update_designation_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="designations_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Designation ID</strong></th>
                                <th class="text-center"><strong>Designation Name</strong></th>
                                <th class="text-center"><strong>Date Time</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Designation ID</strong></th>
                                <th class="text-center"><strong>Designation Name</strong></th>
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
            var table = $("#designations_table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "<?= base_url('ajax/load-designations') ?>",
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td')
                        .addClass('text-center');
                }
            });
            //end::Initialize Datatable

            //begin::Add Designation Ajax
            $(document).on('click', '#add_designation_submit_button', function(e){
                e.preventDefault();
                var form = $('#add_designation');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/add-designation'); ?>",
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
                                    $("#designations_table").DataTable().ajax.reload();
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
            //end::Add Designation Ajax

            //begin::Delete Designation Ajax
            $(document).on('click', '.delete-designation', function(e){
                e.preventDefault();
                var designation_id = $(this).data('id');
                var data = {
                    'designation_id'        :   designation_id,
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
                            url: "<?php echo base_url('ajax/delete-designation'); ?>",
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
                                            $("#designations_table").DataTable().ajax.reload();
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
            //end::Delete Designation Ajax

            //begin::Open Edit Designation Modal
            $(document).on('click', '.edit-designation', function(e){
                e.preventDefault();
                var designation_id = $(this).data('id');
                var data = {
                    'designation_id'        :   designation_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/get-designation'); ?>",
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
                            if( typeof response.response_data.designation != 'undefined' ){
                                var designation_data = response.response_data.designation;
                                console.log(designation_data);
                                $("form#update_designation").find('small.error-text').html('');
                                $("form#update_designation").find('input[name="designation_id"]').val(designation_data.id);
                                $("form#update_designation").find('input[name="designation_name"]').val(designation_data.designation_name);
                                $("#update_designation_modal").modal('show');
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
            //end::Open Edit Designation Modal

            //begin::Update Designation Ajax
            $(document).on('click', '#update_designation_submit_button', function(e){
                e.preventDefault();
                var form = $('#update_designation');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/update-designation'); ?>",
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
                                        console.log();
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
                                    $("#designations_table").DataTable().ajax.reload();
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
            //end::Update Designation Ajax
            
        })
    </script>

    <?= $this->endSection() ?>
<?= $this->endSection() ?>