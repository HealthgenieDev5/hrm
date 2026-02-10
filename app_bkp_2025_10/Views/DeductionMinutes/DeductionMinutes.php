<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">
            <div class="card shadow-sm mb-5">
                <form id="deduction_minutes_form"class="card-body" method="post" enctype="multipart/form-data" >
                <!-- <div class="card-body"> -->
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="employee_id" class="mb-3">Employee Name</label>
                            <select class="form-select form-select-sm" id="employee_id" name="employee_id"  data-control="select2" data-placeholder="Select an Employee">
                                <option></option>
                                <?php
                                foreach( $employees as $employee_row){
                                    ?>
                                    <option value="<?php echo $employee_row['id']; ?>" <?php echo ($employee_row['id'] == session()->get('current_user')['employee_id']) ? 'selected' : ''; ?> >
                                        <?php echo $employee_row['employee_name'].' [ '.$employee_row['internal_employee_id'].' ] '.$employee_row['department_name'].' - '.$employee_row['company_short_name'].''; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="employee_id_error"></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label">Deduction Date</label>
                            <div class="input-group">
                                <input type="text" id="deduction_date" class="leave-control form-control form-control-sm" name="deduction_date" placeholder="Pick a Date" value="<?= set_value('deduction_date') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <small class="text-danger error-text" id="deduction_date_error"><?= isset($validation) ? display_error($validation, 'deduction_date') : '' ?></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label">Deduction Minutes</label>
                            <div class="input-group">
                                <input type="number" min="0" step="1" id="deduction_minutes" class="form-control form-control-sm" name="deduction_minutes" placeholder="deduction minutes" value="<?= set_value('deduction_minutes', 0) ?>">
                            </div>
                            <small class="text-danger error-text" id="deduction_minutes_error"><?= isset($validation) ? display_error($validation, 'deduction_minutes') : '' ?></small>
                        </div>

                        <div class="col-lg-2 mb-3">
                            <label class="form-label">Attachment</label><br>
                            <div id="attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                <div class="image-input-wrapper w-125px h-125px" style="">
                                    <a class="d-none w-100 h-100 overlay preview-button" data-bs-target="#previewLightbox" data-bs-toggle="modal">
                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-3x"></i></div>
                                    </a>
                                </div>
                                <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Change Attachment">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" id="attachment" name="attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                    <input type="hidden" name="attachment_remove" />
                                </label>
                                <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancel Attachment">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Remove Attachment">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                            </div>
                            <br>
                            <small class="text-danger error-text" id="attachment_error"><?= isset($validation) ? display_error($validation, 'attachment') : '' ?></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label">Remarks</label>
                            <div class="input-group">
                                <input type="text" id="deduction_remarks" class="form-control form-control-sm" name="deduction_remarks" placeholder="Remarks" value="<?= set_value('deduction_remarks') ?>">
                            </div>
                            <small class="text-danger error-text" id="deduction_remarks_error"><?= isset($validation) ? display_error($validation, 'deduction_remarks') : '' ?></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" class="mb-3">&nbsp;</label>
                            <button type="submit" id="submit_update_deduction_minutes" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
                                <span class="indicator-label">Update</span>
                                <span class="indicator-progress">
                                    Please wait... 
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                <!-- </div> -->
                </form>
            </div>
        </div>
        <!--end::Col-->

        <div class="col-12">
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <table id="deduction_requests_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Current Status</strong></th>
                                <th class="text-center"><strong>Deducted By</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewer's Remarks</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Current Status</strong></th>
                                <th class="text-center"><strong>Deducted By</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewer's Remarks</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!--end::Row-->

    <?= $this->section('javascript') ?>

    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($){

            $(document).on('change', '#employee_id', function(e){              
                $('#deduction_date').val('').trigger('change');
                $('#deduction_minutes').val('0').trigger('change');
                $("#deduction_requests_table").DataTable().ajax.reload();
            })

            $('#deduction_date').flatpickr({
                dateFormat: 'Y-m-d',
                minDate: "<?php echo first_date_of_last_month(); ?>",
                maxDate: "<?php echo last_date_of_month(); ?>",
                altInput: false,
                static: true,
            })

            $(document).on('submit', '#deduction_minutes_form', function(e){
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/update-deduction-minutes'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        console.log(response);
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
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
                                }).then(function(e){
                                    /*location.reload();*/
                                    $("#deduction_requests_table").DataTable().ajax.reload();
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
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    }
                })
            });

            var deduction_requests_table = $("#deduction_requests_table").DataTable({
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('/ajax/backend/hr/existing-deduction-minutes') ?>",
                    type:  "POST",
                    data:  { filter : function(){ return $('#deduction_minutes_form').serialize(); } },
                    dataSrc: "",
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
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
                    { data: "employee_name",
                        render: function(data, type, row, meta){
                            return row.employee_name+"<br>("+row.department_name+" - "+row.company_short_name+")";
                        }
                    },
                    { data: "date" },
                    { data: "minutes" },
                    { 
                        data: "current_status", 
                        render: function(data, type, row, meta){ 
                            if(row.current_status == 'pending'){
                                return `<span class="badge text-capitalize rounded-pill bg-transparent text-dark border border-dashed border-dark">${row.current_status}</span>`;
                            }else if(row.current_status == 'approved'){
                                return `<span class="badge text-capitalize rounded-pill bg-success text-white">${row.current_status}</span>`;
                            }else if(row.current_status == 'rejected'){
                                return `<span class="badge text-capitalize rounded-pill bg-danger text-white opacity-50">${row.current_status}</span>`;
                            }
                            return `<strong class="text-capitalize">${row.current_status}</strong>`;
                        }
                    },
                    { data: "deducted_by_name" },
                    { 
                        data: "initial_remarks", 
                        render: function(data, type, row, meta){
                            if( row.deducted_by_name != '' && row.date_time != '' && row.initial_remarks != '' ){
                                if( row.attachment != '' ){
                                    return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                            <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.deducted_by_name}</strong></small>
                                            <small class="d-block text-start mb-2">on <strong class="text-danger">${row.date_time}</strong></small>
                                            <small class="d-flex justify-content-start mb-2">
                                                <a class="d-block" href="${row.attachment}" target="_blank">
                                                    <img src="${row.attachment}" class="w-100" style="object-fit: contain; max-height:100px;" />
                                                </a>
                                            </small>
                                            <small class="d-block text-start fst-italic">${row.initial_remarks}</small>
                                        </p>`;
                                }else{
                                    return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                            <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.deducted_by_name}</strong></small>
                                            <small class="d-block text-start mb-2">on <strong class="text-danger">${row.date_time}</strong></small>
                                            <small class="d-block text-start fst-italic">${row.initial_remarks}</small>
                                        </p>`;
                                }
                                
                            }else{
                                return '';
                            }                             
                        } 
                    },                    
                    { data: "reviewed_by_name" },
                    { 
                        data: "reviewer_remarks", 
                        render: function(data, type, row, meta){
                            if( row.reviewed_by_name != '' && row.reviewed_date != '' && row.reviewer_remarks != '' ){
                                return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                            <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.reviewed_by_name}</strong></small>
                                            <small class="d-block text-start mb-2">on <strong class="text-danger">${row.reviewed_date}</strong></small>
                                            <small class="d-block text-start fst-italic">${row.reviewer_remarks}</small>
                                        </p>`
                            }else{
                                return '';
                            }                             
                        } 
                    },
                    { data: "id",
                        render: function(data, type, row, meta){
                            return `<div class="d-flex justify-content-center">
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-deduction-minutes" data-id="${row.id}">
                                            <span class="svg-icon svg-icon-3">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </a>
                                    </div>`;
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
                ],
            });

            $(document).on('click', '.delete-deduction-minutes', function(e){
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    customClass: { confirmButton: "btn btn-sm btn-primary", cancelButton: "btn btn-sm btn-secondary" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        var deduction_id = $(this).data('id');
                        var data = {
                            'deduction_id' : deduction_id,
                        };
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('/ajax/backend/hr/delete-deduction-minutes'); ?>",
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
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function(){
                                            $("#deduction_requests_table").DataTable().ajax.reload();
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
            });

        })
    </script>
    
    <?= $this->endSection() ?>
<?= $this->endSection() ?>