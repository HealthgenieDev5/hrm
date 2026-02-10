<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">

            <div class="card shadow-sm mb-5">
                <form id="shift_override_form"class="card-body" method="post" enctype="multipart/form-data" >
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
                            <label class="form-label">Date Range</label>
                            <!-- <div class="input-group">
                                <input type="text" id="date_range" class="leave-control form-control form-control-sm" name="date_range" placeholder="Pick a Date Range" value="<?= set_value('date_range') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div> -->

                            <div class="position-relative d-flex align-items-center ">
                                <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                                <input type="text" id="date_range" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select date range" />
                                <input type="hidden" id="from_date" name="from_date" />
                                <input type="hidden" id="to_date" name="to_date" />
                            </div>
                            <small class="text-danger error-text" id="date_range_error"><?= isset($validation) ? display_error($validation, 'date_range') : '' ?></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="shift">Shift</label>
                            <select class="form-control form-control-sm" id="shift_id" name="shift_id" data-control="select2" data-placeholder="Select a Shift" data-allow-clear="true">
                                <option></option>
                                <?php
                                if( isset($shifts) && !empty($shifts) ){
                                    foreach( $shifts as $shift_row){
                                        ?>
                                        <option value="<?php echo $shift_row['id']; ?>" <?= @edit_set_select('shift_id', $shift_row['id'], $shift_id) ?> ><?php echo $shift_row['shift_name']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="shift_id_error"><?= isset($validation) ? display_error($validation, 'shift_id') : '' ?></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label">Remarks</label>
                            <div class="input-group">
                                <input type="text" id="remarks" class="form-control form-control-sm" name="remarks" placeholder="Remarks" value="<?= set_value('remarks') ?>">
                            </div>
                            <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" class="mb-3">&nbsp;</label>
                            <button type="submit" id="submit_update_shift_override" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
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

            <!--  -->


        </div>
        <!--end::Col-->

        <div class="col-12">

            <div class="card shadow-sm mb-5">

                <div class="card-body">

                    <table id="existing_overrides" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>From Date</strong></th>
                                <th class="text-center"><strong>To Date</strong></th>
                                <th class="text-center"><strong>Shift</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>From Date</strong></th>
                                <th class="text-center"><strong>To Date</strong></th>
                                <th class="text-center"><strong>Shift</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
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

            
            var $date_range_pickr = $("#date_range").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                mode: "range"
            });

            $(document).on('change', '#date_range', function(e){
                console.log($(this).val());
                var date_range = $(this).val();
                date_range_array = date_range.split('to');
                console.log(date_range_array);
                if( date_range_array.length == 1 ){
                    $('#from_date').val(date_range_array[0]);
                    $('#to_date').val(date_range_array[0]);
                } else if( date_range_array.length == 2 ){
                    $('#from_date').val(date_range_array[0]);
                    $('#to_date').val(date_range_array[1]);
                }
            })

            $(document).on('change', '#employee_id', function(e){              
                // $date_range_pickr.clear();
                // $('#from_date').val('').trigger('change');
                // $('#to_date').val('').trigger('change');
                // $('#shift').val('').trigger('change');
                // $('#remarks').val('').trigger('change');
                $("#existing_overrides").DataTable().ajax.reload();
            })

            $(document).on('submit', '#shift_override_form', function(e){
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/override-shift'); ?>",
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
                                    $("#existing_overrides").DataTable().ajax.reload();
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
            })

            var existing_overrides = $("#existing_overrides").DataTable({
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('/ajax/backend/hr/existing-shift-overrides') ?>",
                    type:  "POST",
                    data:  { filter : function(){ return $('#shift_override_form').serialize(); } },
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
                    { data: "employee_name",
                        render: function(data, type, row, meta){
                            return row.employee_name+" - "+row.department_name+" - "+row.company_short_name;
                        }
                    },
                    { data: "from_date" },
                    { data: "to_date" },
                    { data: "shift_name" },
                    { 
                        data: "remarks", 
                        render: function(data, type, row, meta){
                            return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small>${row.remarks}</small></p>`
                        }
                    },
                    { 
                        data: "remarks",
                        render: function(data, type, row, meta){
                            return `<div class="d-flex justify-content-center">
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-shift-override" data-id="${row.id}">
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

            $(document).on('click', '.delete-shift-override', function(e){
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
                        var override_id = $(this).data('id');
                        var data = {
                            'override_id' : override_id,
                        };
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('/ajax/backend/hr/delete-shift-override'); ?>",
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
                                            $("#existing_overrides").DataTable().ajax.reload();
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
        })
    </script>
    
    <?= $this->endSection() ?>
<?= $this->endSection() ?>