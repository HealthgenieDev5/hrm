<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">
            



            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Departments</h3>
                    <div class="card-toolbar">
                        <div class="m-0">
                            <!--begin::Menu toggle-->
                            <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder me-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <!--begin::Svg Icon-->
                                <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
                                    <i class="fa-solid fa-filter"></i>
                                </span>
                                <!--end::Svg Icon-->
                                Filter
                            </a>
                            <!--end::Menu toggle-->
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-350px" data-kt-menu="true" id="filter_dropdown">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bolder">Filter Options</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Menu separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Menu separator-->
                                <!--begin::Form-->
                                <form id="filter_form" class="px-7 py-5">
                                    <!--begin::Input group-->
                                    <style>
                                        .select2-selection--single{
                                            background-position: right 0.5rem center;
                                        }
                                        .select2-container--bootstrap5 .select2-selection__clear {
                                            right: 2.3rem;
                                        }
                                    </style>
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold">Company:</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select id="filter_company" name="filter_company[]" multiple class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select Company" data-dropdown-parent="#filter_dropdown" data-allow-clear="true">
                                            <option></option>
                                            <?php
                                            if( !empty($all_companies) ){
                                                foreach( $all_companies as $company ){
                                                    ?>
                                                    <!-- <option value="<?= $company['id'] ?>" 
                                                        <?= edit_set_select('filter_company', $company['id'], session()->get('current_user')['company_id']) ?>
                                                        ><?= $company['company_name'] ?></option> -->
                                                    <option value="<?= $company['id'] ?>" ><?= $company['company_name'] ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                                        <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
                                    </div>
                                    <!--end::Actions-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Menu 1-->
                        </div>
                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_department_modal">
                            <i class="fa fa-plus" ></i> Add New
                        </button>
                        <div class="modal fade" tabindex="-1" id="add_department_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="add_department" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Department</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Department Name</label>
                                                    <input type="text" name="department_name" class="form-control form-control-solid" placeholder="Department Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="department_name_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">HOD</label>
                                                    <select name="hod_employee_id" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#add_department_modal" data-placeholder="Select HOD">
                                                        <option></option>
                                                        <?php
                                                        if( !empty($all_employees) ){
                                                            foreach( $all_employees as $employee ){
                                                                ?>
                                                                <option value="<?= $employee['id'] ?>"><?= trim($employee['first_name']." ".$employee['last_name']) ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="hod_employee_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Company</label>
                                                    <select name="company_id" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#add_department_modal" data-placeholder="Select Company">
                                                        <option></option>
                                                        <?php
                                                        if( !empty($all_companies) ){
                                                            foreach( $all_companies as $company ){
                                                                ?>
                                                                <option value="<?= $company['id'] ?>"><?= $company['company_name'] ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="company_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="add_department_submit_field" name="add_department_submit_field" value="Add"/>
                                            <button type="submit" id="add_department_submit_button" class="btn btn-sm btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" tabindex="-1" id="update_department_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="update_department" method="post">                                        
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Department</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Department Name</label>
                                                    <input type="text" name="department_name" class="form-control form-control-solid" placeholder="Department Name" value="" oninput="$(this).next().html('')" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="department_name_error"></span>
                                                    <!--end::Error Message-->
                                                    <input type="hidden" name="department_id" />
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger error-text d-block" id="department_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">HOD</label>
                                                    <select name="hod_employee_id" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#update_department_modal" data-placeholder="Select HOD">
                                                        <option></option>
                                                        <?php
                                                        if( !empty($all_employees) ){
                                                            foreach( $all_employees as $employee ){
                                                                ?>
                                                                <option value="<?= $employee['id'] ?>"><?= trim($employee['first_name']." ".$employee['last_name']) ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="hod_employee_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="required form-label">Company</label>
                                                    <select name="company_id" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#update_department_modal" data-placeholder="Select Company">
                                                        <option></option>
                                                        <?php
                                                        if( !empty($all_companies) ){
                                                            foreach( $all_companies as $company ){
                                                                ?>
                                                                <option value="<?= $company['id'] ?>"><?= $company['company_name'] ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <!--begin::Error Message-->
                                                    <span class="text-danger d-block" id="company_id_error"></span>
                                                    <!--end::Error Message-->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="update_department_submit_field" name="update_department_submit_field" value="Add"/>
                                            <button type="submit" id="update_department_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="departments_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>ID</strong></th>
                                <th class="text-center"><strong>Department Name</strong></th>
                                <th class="text-center"><strong>Company Name</strong></th>
                                <th class="text-center"><strong>HOD Name</strong></th>
                                <th class="text-center"><strong>Date Time</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>ID</strong></th>
                                <th class="text-center"><strong>Department Name</strong></th>
                                <th class="text-center"><strong>Company Name</strong></th>
                                <th class="text-center"><strong>HOD Name</strong></th>
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
            var table = $("#departments_table").DataTable({
                // "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
                // "buttons": [],
                "ajax": {
                    url:  "<?= base_url('ajax/load-departments') ?>",
                    type:  "POST",
                    data:  { filter : function(){ return $('#filter_form').serialize(); } },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
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
                    { data: "department_id" },
                    { data: "department_name" },
                    { data: "company_short_name" },
                    { data: "hod_name" },
                    { data: "date_time" },
                    { data: "actions", 
                      render: function(data, type, row, meta){
                        return '<div class="btn-group">'+
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-department" data-id="'+row.department_id+'">'+
                                    '<span class="svg-icon svg-icon-3">'+
                                        '<i class="fa fa-pencil-alt" aria-hidden="true" ></i>'+
                                    '</span>'+
                                '</a>'+
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-department" data-id="'+row.department_id+'">'+
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
                "scrollY": 'auto',
                "columnDefs": [
                    { "className": 'text-center', "targets": '_all' },
                ],
            });
            //end::Initialize Datatable

            //begin::Add Department Ajax
            $(document).on('click', '#add_department_submit_button', function(e){
                e.preventDefault();
                var form = $('#add_department');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/add-department'); ?>",
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
                                    // form[0].reset();
                                    form.closest('.modal').modal('hide');
                                    $("#departments_table").DataTable().ajax.reload();
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
            //end::Add Department Ajax

            //begin::Delete Department Ajax
            $(document).on('click', '.delete-department', function(e){
                e.preventDefault();
                var department_id = $(this).data('id');
                var data = {
                    'department_id'        :   department_id,
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
                            url: "<?php echo base_url('ajax/delete-department'); ?>",
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
                                            $("#departments_table").DataTable().ajax.reload();
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
            //end::Delete Department Ajax

            //begin::Open Edit Department Modal
            $(document).on('click', '.edit-department', function(e){
                e.preventDefault();
                var department_id = $(this).data('id');
                var data = {
                    'department_id'        :   department_id,
                };

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/get-department'); ?>",
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
                            if( typeof response.response_data.department != 'undefined' ){
                                var department_data = response.response_data.department;
                                console.log(department_data);
                                $("form#update_department").find('small.error-text').html('');
                                $("form#update_department").find('input[name="department_id"]').val(department_data.id);
                                $("form#update_department").find('input[name="department_name"]').val(department_data.department_name);
                                $("form#update_department").find('select[name="hod_employee_id"]').val(department_data.hod_employee_id).trigger('change');
                                $("form#update_department").find('select[name="company_id"]').val(department_data.company_id).trigger('change');
                                $("#update_department_modal").modal('show');
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
            //end::Open Edit Department Modal

            //begin::Update Department Ajax
            $(document).on('click', '#update_department_submit_button', function(e){
                e.preventDefault();
                var form = $('#update_department');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/update-department'); ?>",
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
                                    $("#departments_table").DataTable().ajax.reload();
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
            //end::Update Department Ajax

            $(document).on('submit', '#filter_form', function(e){
                e.preventDefault();
                $("#departments_table").DataTable().ajax.reload();
            })
            
        })
    </script>

    <?= $this->endSection() ?>
<?= $this->endSection() ?>