<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">
            <div class="card shadow-sm mb-5">
                <form id="override_leave_balance_form"class="card-body" method="post" enctype="multipart/form-data" >
                <!-- <div class="card-body"> -->
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="employee_id" class="mb-3">Employee Name</label>
                            <select class="form-select form-select-sm" id="employee_id" name="employee_id"  data-control="select2" data-placeholder="Select an Employee">
                                <option></option>
                                <?php
                                foreach( $employees as $employee_row){
                                    ?>
                                    <option 
                                    value="<?php echo $employee_row['id']; ?>" 
                                    data-cl_balance="<?php echo $employee_row['cl_balance']; ?>" 
                                    data-cl_balance_id="<?php echo $employee_row['cl_balance_id']; ?>" 
                                    data-el_balance="<?php echo $employee_row['el_balance']; ?>" 
                                    data-el_balance_id="<?php echo $employee_row['el_balance_id']; ?>" 
                                    data-rh_balance="<?php echo $employee_row['rh_balance']; ?>" 
                                    data-rh_balance_id="<?php echo $employee_row['rh_balance_id']; ?>"

                                    <?php echo ($employee_row['id'] == session()->get('current_user')['employee_id']) ? 'selected' : ''; ?>
                                    >
                                        <?php echo $employee_row['employee_name'].' [ '.$employee_row['internal_employee_id'].' ] '.$employee_row['department_name'].' - '.$employee_row['company_short_name'].''; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="employee_id_error"></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="leave_type" class="mb-3">Leave Type</label>
                            <select class="form-select form-select-sm" id="leave_type" name="leave_type"  data-control="select2" data-placeholder="Select Leave Type">
                                <option></option>
                                <?php
                                foreach( $leave_types as $leave_type){
                                    ?>
                                    <option value="<?php echo $leave_type['leave_code']; ?>" ><?php echo $leave_type['leave_code']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="leave_type_error"></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="new_balance" class="mb-3">New Balance</label>
                            <input class="form-control form-control-sm" type="number" min="0" max="1" id="new_balance" name="new_balance" placeholder="New Balance" />
                            <input type="hidden" id="balance_id" name="balance_id" />
                            <small class="text-danger error-text" id="new_balance_error"></small>
                            <small class="text-danger error-text" id="balance_id_error"></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="custom_remarks" class="mb-3">Remarks</label>
                            <input class="form-control form-control-sm" type="text" id="custom_remarks" name="custom_remarks" placeholder="Your remarks" />
                            <small class="text-danger error-text" id="custom_remarks_error"></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" class="mb-3">&nbsp;</label>
                            <button type="submit" id="submit_update_password" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
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
                    <table id="leave_balance_override_history_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center"><strong>Leave code</strong></th>
                                <th class="text-center"><strong>Previous Balance</strong></th>
                                <th class="text-center"><strong>New Balance</strong></th>
                                <th class="text-center"><strong>By</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Date Time</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Leave code</strong></th>
                                <th class="text-center"><strong>Previous Balance</strong></th>
                                <th class="text-center"><strong>New Balance</strong></th>
                                <th class="text-center"><strong>By</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Date Time</strong></th>
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
                $('#leave_type').val('').trigger('change');
                $("#leave_balance_override_history_table").DataTable().ajax.reload();
            })
            $(document).on('change', '#leave_type', function(e){
                var leave_code = $(this).val();
                var cl_balance_old = $('#employee_id').find(':selected').data('cl_balance');
                var cl_balance_id = $('#employee_id').find(':selected').data('cl_balance_id');
                var el_balance_old = $('#employee_id').find(':selected').data('el_balance');
                var el_balance_id = $('#employee_id').find(':selected').data('el_balance_id');
                var rh_balance_old = $('#employee_id').find(':selected').data('rh_balance');
                var rh_balance_id = $('#employee_id').find(':selected').data('rh_balance_id');

                if( leave_code == 'CL' ){
                    $('#new_balance').attr('max', '3');
                    $('#new_balance').attr('step', '0.5');
                    $('#new_balance').val(cl_balance_old);
                    $('#balance_id').val(cl_balance_id);
                }else if( leave_code == 'EL' ){
                    $('#new_balance').attr('max', '30');
                    $('#new_balance').attr('step', '0.25');
                    $('#new_balance').val(el_balance_old);
                    $('#balance_id').val(el_balance_id);

                }else if( leave_code == 'RH' ){
                    $('#new_balance').attr('max', '2');
                    $('#new_balance').attr('step', '1');
                    $('#new_balance').val(rh_balance_old);
                    $('#balance_id').val(rh_balance_id);
                }
            })

            $(document).on('submit', '#override_leave_balance_form', function(e){
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/override-leave-balance'); ?>",
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
                                    location.reload();
                                    /*form[0].reset();
                                    form.find('select').val('').trigger('change');*/
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

            var leave_balance_override_history_table = $("#leave_balance_override_history_table").DataTable({
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('/ajax/backend/hr/leave-override-history') ?>",
                    type:  "POST",
                    data:  { filter : function(){ return $('#override_leave_balance_form').serialize(); } },
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
                    { data: "leave_code" },
                    { data: "previous_balance" },
                    { data: "new_balance" },
                    { data: "overriden_by_name" },
                    { 
                        data: "remarks", 
                        render: function(data, type, row, meta){
                            return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                            <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.overriden_by_name}</strong></small>
                                            <small class="d-block text-start mb-2">on <strong class="text-danger">${row.date_time}</strong></small>
                                            <small class="d-block text-start fst-italic">${row.remarks}</small>
                                        </p>`;                             
                        } 
                    },
                    { data: "date_time" },
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

        })
    </script>
    
    <?= $this->endSection() ?>
<?= $this->endSection() ?>