<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">

            <div class="card shadow-sm mb-5">
                <form id="update_password"class="card-body" method="post" enctype="multipart/form-data" >
                    <div class="row">
                        <div class="col-lg-5 col-md-6">
                            <label class="form-label" for="employee_id" class="mb-3">Employee Name</label>
                            <select class="form-select " id="employee_id" name="employee_id"  data-control="select2" data-placeholder="Select an Employee">
                                <option></option>
                                <?php
                                foreach( $employees as $employee_row){
                                    ?>
                                    <option 
                                    <?php
                                    if( $employee_row['id'] == 95 && !in_array(session()->get('current_user')['employee_id'], [95,40]) ){
                                        echo 'disabled';
                                    }
                                    ?>
                                    value="<?php echo $employee_row['user_id_in_users_table']; ?>" ><?php echo trim($employee_row['first_name'].' '.$employee_row['last_name']); ?> (<?php echo $employee_row['department_name'].' - '.$employee_row['company_name']; ?>)</option>
                                    <?php
                                }
                                ?>
                            </select>
                            <small class="text-danger error-text" id="employee_id_error"></small>
                        </div>

                        <div class="col-lg-5 col-md-6">
                            <label class="form-label" for="new_password" class="mb-3">New Password</label>
                            <input class="form-control from-control-solid" type="text" id="new_password" name="new_password" placeholder="New Password" />
                            <small class="text-danger error-text" id="new_password_error"></small>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label class="form-label" class="mb-3">&nbsp;</label>
                            <!-- <input class="form-control btn btn-primary" type="submit" id="update_password_submit" value="Change" /> -->
                            <button type="submit" id="submit_update_password" class="form-control btn btn-primary d-inline">
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
            $(document).on('submit', '#update_password', function(e){
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/hr/employee/password-update'); ?>",
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