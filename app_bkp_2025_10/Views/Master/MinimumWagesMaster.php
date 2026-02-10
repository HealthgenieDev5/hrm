<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">
            



            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Minimum Wages Master</h3>
                    <div class="card-toolbar">

                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_minimum_wages_category_modal">
                            <i class="fa fa-plus" ></i> Add New
                        </button>
                        <div class="modal fade" tabindex="-1" id="add_minimum_wages_category_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="add_minimum_wages_category" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Min Wages Category</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Min Wages Category Name</label>
                                                    <input type="text" name="minimum_wages_category_name" class="form-control form-control-solid" placeholder="Min Wages Category Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="minimum_wages_category_name_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Min Wages State Name</label>
                                                    <input type="text" name="minimum_wages_category_state" class="form-control form-control-solid" placeholder="Min Wages State Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="minimum_wages_category_state_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Min Wages Category Value</label>
                                                    <input type="number" name="minimum_wages_category_value" class="form-control form-control-solid" placeholder="Min Wages Category Value" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="minimum_wages_category_value_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="add_minimum_wages_category_submit_field" name="add_minimum_wages_category_submit_field" value="Add"/>
                                            <button type="submit" id="add_minimum_wages_category_submit_button" class="btn btn-sm btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" tabindex="-1" id="update_minimum_wages_category_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="update_minimum_wages_category" method="post">                                        
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Min Wages Category</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Min Wages Category Name</label>
                                                    <input type="text" name="minimum_wages_category_name" class="form-control form-control-solid" placeholder="Min Wages Category Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="minimum_wages_category_name_error"></span>
                                                    <!--end::Error Message-->
                                                    <input type="hidden" name="minimum_wages_category_id" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="minimum_wages_category_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Min Wages State Name</label>
                                                    <input type="text" name="minimum_wages_category_state" class="form-control form-control-solid" placeholder="Min Wages State Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="minimum_wages_category_state_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Min Wages Category Value</label>
                                                    <input type="number" name="minimum_wages_category_value" class="form-control form-control-solid" placeholder="Min Wages Category Value" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="minimum_wages_category_value_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="update_minimum_wages_category_submit_field" name="update_minimum_wages_category_submit_field" value="Add"/>
                                            <button type="submit" id="update_minimum_wages_category_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="minimum_wages_categories_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>ID</strong></th>
                                <th class="text-center"><strong>Min Wages Category Name</strong></th>
                                <th class="text-center"><strong>Min Wages Category State</strong></th>
                                <th class="text-center"><strong>Min Wages Category Value</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>ID</strong></th>
                                <th class="text-center"><strong>Min Wages Category Name</strong></th>
                                <th class="text-center"><strong>Min Wages Category State</strong></th>
                                <th class="text-center"><strong>Min Wages Category Value</strong></th>
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

            var minimum_wages_categories_table = $("#minimum_wages_categories_table").DataTable({
                "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
                buttons: [
                    'excelHtml5',
                ],
                "ajax": {
                    url:  "<?= base_url('ajax/backend/master/minimum-wages-category/get-all') ?>",
                    type:  "POST",
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                    dataSrc: "data",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                    searchPlaceholder: "Search"
                },
                "oLanguage": { "sSearch": "" },
                "columns": [
                    { data: "id" },
                    { data: "minimum_wages_category_name" },
                    { data: "minimum_wages_category_state" },
                    { data: "minimum_wages_category_value" },
                    { data: "actions", 
                        render: function(data, type, row, meta){
                            return '<div class="d-flex justify-content-center">'+
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-minimum_wages_category" data-id="'+row.id+'">'+
                                    '<span class="svg-icon svg-icon-3">'+
                                        '<i class="fa fa-pencil-alt" aria-hidden="true" ></i>'+
                                    '</span>'+
                                '</a>'+
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-minimum_wages_category" data-id="'+row.id+'">'+
                                    '<span class="svg-icon svg-icon-3">'+
                                        '<i class="fas fa-trash"></i>'+
                                    '</span>'+
                                '</a>'+
                            '</div>';
                      }
                    },
                ],
                "order": [],
                "scrollX": true,
                "scrollY": '400px',
                "scrollCollapse": true,
                "paging" : false,
                "columnDefs": [
                    { "className": 'text-center', "targets": '_all' },
                ]
            });
            //end::Initialize Datatable

            //begin::Add Min Wages Category Ajax
            $(document).on('click', '#add_minimum_wages_category_submit_button', function(e){
                e.preventDefault();
                var form = $('#add_minimum_wages_category');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/backend/master/minimum-wages-category/add'); ?>",
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
                                    $("#minimum_wages_categories_table").DataTable().ajax.reload();
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
            //end::Add Min Wages Category Ajax

            //begin::Delete Min Wages Category Ajax
            $(document).on('click', '.delete-minimum_wages_category', function(e){
                e.preventDefault();
                var minimum_wages_category_id = $(this).data('id');
                var data = {
                    'minimum_wages_category_id'        :   minimum_wages_category_id,
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
                            url: "<?php echo base_url('ajax/backend/master/minimum-wages-category/delete'); ?>",
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
                                            $("#minimum_wages_categories_table").DataTable().ajax.reload();
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
            //end::Delete Min Wages Category Ajax

            //begin::Open Edit Min Wages Category Modal
            $(document).on('click', '.edit-minimum_wages_category', function(e){
                e.preventDefault();
                var minimum_wages_category_id = $(this).data('id');
                var data = {
                    'minimum_wages_category_id'        :   minimum_wages_category_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/backend/master/minimum-wages-category/get'); ?>",
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
                            if( typeof response.response_data.minimum_wages_category != 'undefined' ){
                                var minimum_wages_category_data = response.response_data.minimum_wages_category;
                                console.log(minimum_wages_category_data);
                                $("form#update_minimum_wages_category").find('small.error-text').html('');
                                $("form#update_minimum_wages_category").find('input[name="minimum_wages_category_id"]').val(minimum_wages_category_data.id);
                                $("form#update_minimum_wages_category").find('input[name="minimum_wages_category_name"]').val(minimum_wages_category_data.minimum_wages_category_name);
                                $("form#update_minimum_wages_category").find('input[name="minimum_wages_category_state"]').val(minimum_wages_category_data.minimum_wages_category_state);
                                $("form#update_minimum_wages_category").find('input[name="minimum_wages_category_value"]').val(minimum_wages_category_data.minimum_wages_category_value);
                                $("#update_minimum_wages_category_modal").modal('show');
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
            //end::Open Edit Min Wages Category Modal

            //begin::Update Min Wages Category Ajax
            $(document).on('click', '#update_minimum_wages_category_submit_button', function(e){
                e.preventDefault();
                var form = $('#update_minimum_wages_category');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/backend/master/minimum-wages-category/update'); ?>",
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
                                    $("#minimum_wages_categories_table").DataTable().ajax.reload();
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
            //end::Update Min Wages Category Ajax
            
        })
    </script>

    <?= $this->endSection() ?>
<?= $this->endSection() ?>