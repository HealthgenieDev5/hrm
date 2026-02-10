<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">

            <div class="card shadow-sm mb-5">
                <form id="override_rh_form"class="card-body" method="post" enctype="multipart/form-data" >
                <!-- <div class="card-body"> -->
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="employee_id" class="mb-3">Employee Name</label>
                            <select class="form-select form-select-sm" id="employee_id" name="employee_id"  data-control="select2" data-placeholder="Select an Employee">
                                <option></option>
                                <?php
                                foreach( $employees as $employee_row){
                                    ?>
                                    <option value="<?php echo $employee_row['id']; ?>"
                                        data-rh_index_1="<?php #echo $employee_row['rh_index_1']; ?>" 
                                        data-rh_id_1="<?php echo $employee_row['rh_id_1']; ?>" 
                                        data-rh_index_2="<?php #echo $employee_row['rh_index_2']; ?>"
                                        data-rh_id_2="<?php echo $employee_row['rh_id_2']; ?>" >
                                        <?php echo $employee_row['employee_name'].' [ '.$employee_row['internal_employee_id'].' ] '.$employee_row['department_name'].' - '.$employee_row['company_short_name'].''; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="employee_id_error"></small>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="rh_id_1" class="mb-3">First RH</label>
                            <select class="form-select form-select-sm" id="rh_id_1" name="rh_id_1"  data-control="select2" data-placeholder="Select First RH">
                                <option></option>
                                <?php
                                foreach( $allRH as $the_rh){
                                    ?>
                                    <option value="<?php echo $the_rh['id']; ?>" ><?php echo $the_rh['holiday_name']."(".date('d M', strtotime($the_rh['holiday_date'])).")"; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="rh_id_1_error"></small>
                            <!-- <input type="hidden" id="rh_index_1" name="rh_index_1" />
                            <small class="text-danger error-text" id="rh_index_1_error"></small> -->
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" for="rh_id_2" class="mb-3">Second RH</label>
                            <select class="form-select form-select-sm" id="rh_id_2" name="rh_id_2"  data-control="select2" data-placeholder="Select Second RH">
                                <option></option>
                                <?php
                                foreach( $allRH as $the_rh){
                                    ?>
                                    <option value="<?php echo $the_rh['id']; ?>" ><?php echo $the_rh['holiday_name']."(".date('d M', strtotime($the_rh['holiday_date'])).")"; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="rh_id_2_error"></small>
                            <!-- <input type="hidden" id="rh_index_2" name="rh_index_2" />
                            <small class="text-danger error-text" id="rh_index_2_error"></small> -->
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label" class="mb-3">&nbsp;</label>
                            <button type="submit" id="submit_update_rh" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
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
    </div>
    <!--end::Row-->

    <?= $this->section('javascript') ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){

            $(document).on('change', '#employee_id', function(e){
                /*var rh_index_1 = $('#employee_id').find(':selected').data('rh_index_1');
                $('#rh_index_1').val(rh_index_1).trigger('change');*/
                var rh_id_1 = $('#employee_id').find(':selected').data('rh_id_1');                
                $('#rh_id_1').val(rh_id_1).trigger('change');

                /*var rh_index_2 = $('#employee_id').find(':selected').data('rh_index_2');
                $('#rh_index_2').val(rh_index_2).trigger('change');*/
                var rh_id_2 = $('#employee_id').find(':selected').data('rh_id_2');
                $('#rh_id_2').val(rh_id_2).trigger('change');
            })

            $(document).on('submit', '#override_rh_form', function(e){
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/override-rh'); ?>",
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
                                    $('#employee_id').find(':selected').data('rh_id_1', $('#rh_id_1').val());
                                    $('#employee_id').find(':selected').data('rh_id_2', $('#rh_id_2').val());
                                    // location.reload();
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