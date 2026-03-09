<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">

            <div class="card shadow-sm mb-5">
                <form id="special_benifits_form"class="card-body" method="post" enctype="multipart/form-data" >
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
                                    data-second_saturday_fixed_off="<?php echo $employee_row['second_saturday_fixed_off']; ?>"
                                    data-late_sitting_allowed="<?php echo $employee_row['late_sitting_allowed']; ?>"
                                    data-late_sitting_formula="<?php echo $employee_row['late_sitting_formula']; ?>"
                                    data-late_sitting_formula_effective_from="<?php echo $employee_row['late_sitting_formula_effective_from']; ?>"
                                    data-over_time_allowed="<?php echo $employee_row['over_time_allowed']; ?>"
                                    >
                                        <?php echo $employee_row['employee_name'].' [ '.$employee_row['internal_employee_id'].' ] '.$employee_row['department_name'].' - '.$employee_row['company_short_name'].''; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="employee_id_error"></small>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label" for="second_saturday_fixed_off" class="mb-3">Second Saturday fixed off</label>
                            <select class="form-select form-select-sm" id="second_saturday_fixed_off" name="second_saturday_fixed_off"  data-control="select2" data-placeholder="Select an option">
                                <option></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <small class="text-danger error-text" id="second_saturday_fixed_off_error"></small>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label" for="late_sitting_allowed" class="mb-3">Late sitting allowed</label>
                            <select class="form-select form-select-sm" id="late_sitting_allowed" name="late_sitting_allowed"  data-control="select2" data-placeholder="Select an option">
                                <option></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <small class="text-danger error-text" id="late_sitting_allowed_error"></small>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label" for="late_sitting_formula" class="mb-3">Late sitting formula</label>
                            <select class="form-select form-select-sm" id="late_sitting_formula" name="late_sitting_formula"  data-control="select2" data-placeholder="Select an option">
                                <option></option>
                                <option value="1/2">1/2</option>
                                <option value="1/3">1/3</option>
                                <option value="1/5">1/5</option>
                            </select>
                            <small class="text-danger error-text" id="late_sitting_formula_error"></small>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label" for="late_sitting_formula_effective_from" class="mb-3">Late sitting formula effective from</label>
                            <div class="input-group input-group-flatpicker" id="late_sitting_formula_effective_from_picker" data-wrap="true">
                                <input type="text" id="late_sitting_formula_effective_from" class="form-control form-control-sm" name="late_sitting_formula_effective_from" placeholder="Effective from" data-input data-open >
                                <span class="input-group-text cursor-pointer" data-toggle>
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <small class="text-danger error-text" id="late_sitting_formula_effective_from_error"></small>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label" for="over_time_allowed" class="mb-3">Over Time allowed</label>
                            <select class="form-select form-select-sm" id="over_time_allowed" name="over_time_allowed"  data-control="select2" data-placeholder="Select an option">
                                <option></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <small class="text-danger error-text" id="over_time_allowed_error"></small>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label" class="mb-3">&nbsp;</label>
                            <!-- <input class="form-control btn btn-primary" type="submit" id="update_password_submit" value="Change" /> -->
                            <button type="submit" id="submit_update_password" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
                                <span class="indicator-label">Update</span>
                                <span class="indicator-progress">
                                    Please wait... 
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!--  -->


        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <?= $this->section('javascript') ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){

            var late_sitting_formula_effective_from = $('.input-group-flatpicker').flatpickr({
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'Y-m-d',
                altInputClass: "form-control form-control-sm"
            })

            
            $(document).on('change', '#employee_id', function(e){
                $('#second_saturday_fixed_off').val( $(this).find(':selected').data('second_saturday_fixed_off') ).trigger('change');
                $('#late_sitting_allowed').val( $(this).find(':selected').data('late_sitting_allowed') ).trigger('change');
                $('#late_sitting_formula').val( $(this).find(':selected').data('late_sitting_formula') ).trigger('change');

                var default_date = $(this).find(':selected').data('late_sitting_formula_effective_from');

                // if( default_date.length ){
                    /*$('#late_sitting_formula_effective_from').val( default_date ).trigger('change');
                    $('#late_sitting_formula_effective_from').flatpickr({
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'Y-m-d',
                        altInputClass: "form-control form-control-sm",
                        defaultDate: default_date,
                    })*/
                    late_sitting_formula_effective_from.setDate(default_date);
                // }
                

                $('#over_time_allowed').val( $(this).find(':selected').data('over_time_allowed') ).trigger('change');
            }) 


            $(document).on('submit', '#special_benifits_form', function(e){
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/update-special-benifits'); ?>",
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
        })
    </script>
    
    <?= $this->endSection() ?>
<?= $this->endSection() ?>