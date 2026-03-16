<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">
            



            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Holidays</h3>
                    <div class="card-toolbar">

                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_holiday_modal">
                            <i class="fa fa-plus" ></i> Add New
                        </button>

                        <div class="ms-2">
                            <!--begin::Menu toggle-->
                            <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
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
                                        <label class="form-label fw-bold">Year:</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select id="filter_year" name="filter_year" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select Year" data-dropdown-parent="#filter_dropdown" >
                                            <?php
                                            if( isset($years) && !empty($years) ){
                                                foreach($years as $year_row){
                                                    ?><option value="<?php echo $year_row['year']; ?>" <?php if( isset($_GET['filter_year']) && $_GET['filter_year'] == $year_row['year'] ){ echo 'selected'; } ?>><?php echo $year_row['year']; ?></option><?php
                                                }
                                            }
                                            ?>
                                            <!-- <option value="2023" >2023</option>
                                            <option value="2022" <?php #if( isset($_GET['filter_year']) && $_GET['filter_year'] == '2022' ){ echo 'selected'; } ?>>2022</option> -->
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


                        <div class="modal fade" tabindex="-1" id="add_holiday_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="add_holiday" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Holiday</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="required form-label">Holiday Code</label>
                                                        <!-- <input type="text" id="holiday_code" name="holiday_code" class="form-control form-control-sm" placeholder="Holiday Code" value="" oninput="$(this).next().html(''); $(this).val($(this).val().toUpperCase())" /> -->

                                                        <select class="form-select form-select-sm" id="holiday_code" name="holiday_code" data-control="select2" data-placeholder="Select Holiday Code" >
                                                            <option value=""></option>
                                                            <option value="HL">HL</option>
                                                            <option value="NH">NH</option>
                                                            <option value="RH">RH</option>
                                                            <option value="SPL HL">SPL HL</option>
                                                        </select>

                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="holiday_code_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="required form-label">Holiday Name</label>
                                                        <input type="text" id="holiday_name" name="holiday_name" class="form-control form-control-sm" placeholder="Holiday Name" value="" oninput="$(this).next().html('')" />
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="holiday_name_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="required form-label">Holiday Type</label>
                                                        <input type="text" id="holiday_type" name="holiday_type" class="form-control form-control-sm" placeholder="Holiday Type" value="" oninput="$(this).next().html('')" />
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="holiday_type_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="required form-label">Is Special Holiday</label>
                                                        <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                            <label for="switch_is_special_holiday_no" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                                No
                                                                <input type="radio" name="is_special_holiday" class="opacity-0 position-absolute" id="switch_is_special_holiday_no" value="no" checked>
                                                            </label>
                                                            <label for="switch_is_special_holiday_yes" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                                Yes
                                                                <input type="radio" name="is_special_holiday" class="opacity-0 position-absolute" id="switch_is_special_holiday_yes" value="yes" >
                                                            </label>
                                                            <a class="bg-success form-control form-control-sm p-0 position-absolute"></a>
                                                        </div>
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="is_special_holiday_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="required form-label">Holiday Date</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-control-sm" id="holiday_date" name="holiday_date" placeholder="Holiday Date" value="" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" oninput="$(this).parent().parent().next().html('')">
                                                            <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="holiday_date_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" id="add_holiday_submit_button" class="btn btn-sm btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" tabindex="-1" id="update_holiday_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="update_holiday" method="post">                                        
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Holiday</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="required form-label">Holiday Code</label>
                                                        <select class="form-select form-select-sm" id="holiday_code_update" name="holiday_code" data-control="select2" data-placeholder="Select Holiday Code" >
                                                            <option value=""></option>
                                                            <option value="HL">HL</option>
                                                            <option value="NH">NH</option>
                                                            <option value="RH">RH</option>
                                                            <option value="SPL HL">SPL HL</option>
                                                        </select>
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="holiday_code_error"></span>
                                                        <!--end::Error Message-->
                                                        <input type="hidden" name="holiday_id" />
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger error-text d-block" id="holiday_id_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="required form-label">Holiday Name</label>
                                                        <input type="text" id="holiday_name" name="holiday_name" class="form-control form-control-sm" placeholder="Holiday Name" value="" oninput="$(this).next().html('')" />
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="holiday_name_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="required form-label">Holiday Type</label>
                                                        <input type="text" id="holiday_type" name="holiday_type" class="form-control form-control-sm" placeholder="Holiday Type" value="" oninput="$(this).next().html('')" />
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="holiday_type_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="required form-label">Is Special Holiday</label>
                                                        <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                            <label for="switch_is_special_holiday_no" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                                No
                                                                <input type="radio" name="is_special_holiday" class="opacity-0 position-absolute" id="switch_is_special_holiday_no" value="no" checked>
                                                            </label>
                                                            <label for="switch_is_special_holiday_yes" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                                Yes
                                                                <input type="radio" name="is_special_holiday" class="opacity-0 position-absolute" id="switch_is_special_holiday_yes" value="yes" >
                                                            </label>
                                                            <a class="bg-success form-control form-control-sm p-0 position-absolute"></a>
                                                        </div>
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="is_special_holiday_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="required form-label">Holiday Date</label>
                                                        <div class="input-group">
                                                            <input type="text" id="holiday_date_edit" class="form-control form-control-sm" name="holiday_date" placeholder="Holiday Date" value="" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" oninput="$(this).parent().parent().next().html('')">
                                                            <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        <!--begin::Error Message-->
                                                        <span class="text-danger d-block" id="holiday_date_error"></span>
                                                        <!--end::Error Message-->
                                                    </div>
                                                </div>
                                                <!-- <div class="col-lg-8" style="overflow-y: auto; max-height: 50vh;">
                                                    <div class="d-flex flex-wrap">
                                                        <?php
                                                        if( !empty($companies) ){
                                                            foreach( $companies as $company ){
                                                                ?>
                                                                <label class="badge badge-primary me-3 mb-2">
                                                                    <input type="checkbox" id="<?php echo 'company_'.$company['id']; ?>" data-company="<?php echo $company['id']; ?>">
                                                                    <span><?php echo $company['company_name']; ?></span>
                                                                </label>
                                                                <?php
                                                            }
                                                        }
                                                        ?>                                                        
                                                    </div>
                                                    <table id="update_list_of_employees" class="table table-sm table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Select</th>
                                                                <th>Employee</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach($AllEmployees as $employee){
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <input class="form-check-input selected-employees" type="checkbox" name="employee_id[]" value="<?php echo $employee['id']; ?>" />
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $employee['employee_name']." (".$employee['internal_employee_id'].") - ".$employee['department_name']." - ".$employee['company_short_name']; ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div> -->
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                            <input type="hidden" id="update_holiday_submit_field" name="update_holiday_submit_field" value="Add"/>
                                            <button type="submit" id="update_holiday_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" tabindex="-1" id="show_employees_modal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">This leave is assiged to these employees</h5>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="svg-icon svg-icon-2x"></span>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <ul class="col-12 mb-3 list-group" id="show_employee_container">
                                                    
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="holidays_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Holiday ID</strong></th>
                                <th class="text-center"><strong>Holiday Code</strong></th>
                                <th class="text-center"><strong>Holiday Name</strong></th>
                                <th class="text-center"><strong>Holiday Type</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Holiday ID</strong></th>
                                <th class="text-center"><strong>Holiday Code</strong></th>
                                <th class="text-center"><strong>Holiday Name</strong></th>
                                <th class="text-center"><strong>Holiday Type</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
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
            var holidays_table = $("#holidays_table").DataTable({
                "ajax": {
                    url:  "<?= base_url('/ajax/backend/master/load-holidays') ?>",
                    type:  "POST",
                    data:  { filter : function(){ return $('#filter_form').serialize(); } },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                    dataSrc: "",
                },
                "dom": 'Bfrtlip',
                // "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3 mb-md-0"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end php-pagination-container"p>>>>',
                "buttons": ['excel'],
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                },
                "columns": [
                    { data: "id" },
                    { data: "holiday_code" },
                    { data: "holiday_name" },
                    { data: "holiday_type" },
                    /*{ data: "holiday_date" },*/
                    {
                        data: {
                            _: 'holiday_date.formatted',
                            sort: 'holiday_date.ordering',
                        }
                    },
                    { data: "actions", 
                      render: function(data, type, row, meta){
                        var spl_hl_action_button = '';
                        if(row.holiday_code == 'SPL HL'){
                            spl_hl_action_button = 
                            '<br>'+
                            '<div class="btn-group mb-1">'+
                                '<a href="#" class="btn btn-primary btn-sm me-1 show-employees" data-id="'+row.id+'">'+
                                    'Show Employees'+
                                '</a>'+
                            '</div>'+
                            '<br>'+
                            '<div class="btn-group">'+
                                '<a href="<?php echo base_url('backend/hr/special-holiday?holiday_id='); ?>'+row.id+'" class="btn btn-primary btn-sm me-1 add-employees">'+
                                    'Add Employees'+
                                '</a>'+
                            '</div>';
                        }
                        return '<div class="btn-group">'+
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-holiday" data-id="'+row.id+'">'+
                                    '<span class="svg-icon svg-icon-3">'+
                                        '<i class="fa fa-pencil-alt" aria-hidden="true" ></i>'+
                                    '</span>'+
                                '</a>'+
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-holiday" data-id="'+row.id+'">'+
                                    '<span class="svg-icon svg-icon-3">'+
                                        '<i class="fas fa-trash"></i>'+
                                    '</span>'+
                                '</a>'+
                            '</div>'+spl_hl_action_button;
                      }
                    },
                ],
                "columnDefs": [
                    { "className": 'text-center', "targets": '_all' },
                ],
                "order": [],
                "scrollX": true,
                "scrollY": '50vh',
                "paging" : false,
            });
            //end::Initialize Datatable

            $('#holidays_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Holidays</h3>');

            $('#holiday_date').flatpickr({
                dateFormat: 'Y-m-d',
                altInput: false,
                static : true,
            })

            $('#holiday_date_edit').flatpickr({
                dateFormat: 'Y-m-d',
                altInput: false,
                static : true,
            })

            $(document).on('click', '.parent-picker', function(){
                $(this).parent().find('.flatpickr-input').focus();
            })

            //begin::Add Holiday Ajax
            $(document).on('click', '#add_holiday_submit_button', function(e){
                e.preventDefault();
                var form = $('#add_holiday');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/master/add-holiday'); ?>",
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
                                    html: response.response_description+'<br>Click OK to select employees for this Holiday',
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                }).then(function (e) {
                                    // window.location.replace(`<?php echo base_url('/backend/master/holiday/single'); ?>/${response.holiday_id}`);
                                    // form[0].reset();
                                    // form.closest('.modal').modal('hide');
                                    // $("#holidays_table").DataTable().ajax.reload();
                                    window.location.reload();
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
            //end::Add Holiday Ajax

            //begin::Delete Holiday Ajax
            $(document).on('click', '.delete-holiday', function(e){
                e.preventDefault();
                var holiday_id = $(this).data('id');
                var data = {
                    'holiday_id'        :   holiday_id,
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
                            url: "<?php echo base_url('/ajax/backend/master/delete-holiday'); ?>",
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
                                            $("#holidays_table").DataTable().ajax.reload();
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
            //end::Delete Holiday Ajax

            //begin::Open Edit Holiday Modal
            $(document).on('click', '.edit-holiday', function(e){
                e.preventDefault();
                var holiday_id = $(this).data('id');
                var data = {
                    'holiday_id': holiday_id,
                };
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/master/get-holiday'); ?>",
                    data: data,
                    success: function(response){
                    	// console.log(response);
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
                            if( typeof response.response_data.holiday != 'undefined' ){
                                var holiday_data = response.response_data.holiday;
                                $("form#update_holiday").find('small.error-text').html('');
                                $("form#update_holiday").find('input[name="holiday_id"]').val(holiday_data.id);
                                $("form#update_holiday").find('select[name="holiday_code"]').val(holiday_data.holiday_code).trigger('change');
                                $("form#update_holiday").find('input[name="holiday_name"]').val(holiday_data.holiday_name);
                                $("form#update_holiday").find('input[name="holiday_type"]').val(holiday_data.holiday_type);
                                $("form#update_holiday").find('input[name="is_special_holiday"][value="'+holiday_data.is_special_holiday+'"]').prop('checked', true);

                                $("form#update_holiday").find('input.selected-employees').prop('checked', false);
                                var selected_employees = holiday_data.employees;
                                $.each(selected_employees, function(index, item){
                                    $("form#update_holiday").find('input.selected-employees[value="'+item+'"]').prop('checked', true);
                                });

                                $("form#update_holiday").find('input[name="holiday_date"]').val(holiday_data.holiday_date).flatpickr();
                                $("#update_holiday_modal").modal('show');
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
            //end::Open Edit Holiday Modal

            //begin::Update Holiday Ajax
            $(document).on('click', '#update_holiday_submit_button', function(e){
                e.preventDefault();
                var form = $('#update_holiday');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/master/update-holiday'); ?>",
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
                                    $("#holidays_table").DataTable().ajax.reload();
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
            //end::Update Holiday Ajax


            //begin::Open Edit Holiday Modal
            $(document).on('click', '.show-employees', function(e){
                e.preventDefault();
                var holiday_id = $(this).data('id');
                var data = {
                    'holiday_id': holiday_id,
                };
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/master/get-employee-of-this-holiday'); ?>",
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
                            if( typeof response.response_data.employees != 'undefined' ){
                                var employees = response.response_data.employees;
                                console.log(employees);
                                $("#show_employee_container").html('');
                                $.each(employees, function(index, employee){
                                    $("#show_employee_container").append('<li class="list-group-item">'+employee.employee_name+' ('+employee.internal_employee_id+') - '+employee.department_name+' - '+employee.company_short_name+'</li>');
                                });
                                $("#show_employees_modal").modal('show');
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
            //end::Open Edit Holiday Modal
            
        })
    </script>
    <script>
        $(document).ready(function(){
            $('#update_holiday_modal').on('shown.bs.modal', function (e) {
                var update_list_of_employees = $("#update_list_of_employees").DataTable({
                    "dom": 'ft',
                });
            });

            $('#add_holiday_modal, #update_holiday_modal').on('shown.bs.modal', function (e) {
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