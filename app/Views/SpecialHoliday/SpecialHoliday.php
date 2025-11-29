<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-12">

        <div class="card shadow-sm mb-5">
            <div class="card-body">

                <!--begin::Stepper-->
                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-lg-row" id="kt_stepper_example_vertical">
                    <!--begin::Aside-->
                    <div class="d-flex flex-row-auto w-100 w-lg-300px">
                        <!--begin::Nav-->
                        <div class="stepper-nav flex-cente">
                            <!--begin::Step 1-->
                            <div class="stepper-item me-5 current" data-kt-stepper-element="nav">
                                <!--begin::Line-->
                                <div class="stepper-line w-40px"></div>
                                <!--end::Line-->

                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">1</span>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Select special holiday
                                    </h3>
                                    <div class="stepper-desc">
                                        Create in the Holiday master if not available
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Step 1-->

                            <!--begin::Step 2-->
                            <div class="stepper-item me-5" data-kt-stepper-element="nav">
                                <!--begin::Line-->
                                <div class="stepper-line w-40px"></div>
                                <!--end::Line-->

                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">2</span>
                                </div>
                                <!--begin::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Employees
                                    </h3>

                                    <div class="stepper-desc">
                                        Select Employees who can avail the selected leave
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Step 2-->



                            <!--begin::Step 3-->
                            <div class="stepper-item me-5" data-kt-stepper-element="nav">
                                <!--begin::Line-->
                                <div class="stepper-line w-40px"></div>
                                <!--end::Line-->

                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">3</span>
                                </div>
                                <!--begin::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                        Finish
                                    </h3>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Step 3-->

                        </div>
                        <!--end::Nav-->
                    </div>

                    <!--begin::Content-->
                    <div class="flex-row-fluid">
                        <!--begin::Form-->
                        <form class="form w-lg-500px mx-auto" id="special_holiday_form">
                            <!--begin::Group-->
                            <div class="mb-5">
                                <!--begin::Step 1-->
                                <div class="flex-column current" data-kt-stepper-element="content">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label" for="holiday_id">Select Holiday</label>
                                        <!--end::Label-->

                                        <select class="form-select form-select-sm" id="holiday_id" name="holiday_id"  data-control="select2" data-placeholder="Select a Holiday">
                                            <option></option>
                                            <?php
                                            foreach( $special_holidays as $holiday_row){
                                                ?>
                                                <option value="<?php echo $holiday_row['id']; ?>" <?php if( isset($_GET['holiday_id']) && $_GET['holiday_id'] == $holiday_row['id'] ){ echo 'selected';} ?> >
                                                    <?php echo $holiday_row['holiday_name'].' ( '.$holiday_row['holiday_date'].' ) '.$holiday_row['holiday_code']; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <small class="text-danger error-text" id="holiday_id_error"></small>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Step 1-->

                                <!--begin::Step 2-->
                                <div class="flex-column" data-kt-stepper-element="content">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label">Filter Company</label>
                                        <!--end::Label-->

                                        <select class="form-select form-select-sm" id="company_id" data-control="select2" data-placeholder="Select a Company" >
                                            <option value=""></option>
                                            <option value="all_companies" <?php echo ( isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array( 'all_companies', $_REQUEST['company']) ) ? 'selected' : ''; ?> >All Companies</option>
                                            <?php
                                            if( isset($Companies) && !empty($Companies) ){
                                                foreach( $Companies as $company_row){
                                                    ?>
                                                    <option value="<?php echo $company_row['id']; ?>" <?php echo ( isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array( $company_row['id'], $_REQUEST['company']) && !in_array( 'all_companies', $_REQUEST['company']) ) ? 'selected' : ''; ?> ><?php echo $company_row['company_name']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <br>
                                        <small class="text-danger error-text" id="company_error"></small>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label">Select Employees</label>
                                        <!--end::Label-->

                                        <div id="employee_list" class="form-control" style="max-height: 60vh; overflow-y: auto">
                                            <?php
                                            if( !empty($AllEmployees) ){
                                                $selectedEmployees = null;
                                                foreach( $special_holidays as $spl_hl ){
                                                    if( isset($_GET['holiday_id']) && !empty($_GET['holiday_id']) && $spl_hl['id'] == $_GET['holiday_id'] ){
                                                        $selectedEmployees = !empty($spl_hl['employee_id']) ? explode(",", $spl_hl['employee_id']) : null;
                                                    }
                                                }
                                                foreach( $AllEmployees as $employee ){
                                                    ?>
                                                    <div class="form-check form-check-custom form-check-solid mb-2 company_id_<?php echo $employee['company_id']; ?>" >
                                                        <input class="form-check-input" type="checkbox" name="employee_id[]" value="<?php echo $employee['id']; ?>"
                                                        <?php echo !empty($selectedEmployees) && in_array($employee['id'], $selectedEmployees) ? 'checked' : ''; ?>
                                                         />
                                                        <label class="form-check-label" for="flexCheckDefault"><?php echo $employee['employee_name']; ?> (<?php echo $employee['internal_employee_id']; ?>) - <?php echo $employee['department_name']; ?> - <?php echo $employee['company_short_name']; ?></label>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>

                                        <small class="text-danger error-text" id="employee_list_error"></small>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--begin::Step 2-->

                                <!--begin::Step 3-->
                                <div class="flex-column" data-kt-stepper-element="content">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-10">
                                        <P>You are going to submit this form, there is no going back, Kindly review the Form before Hitting submit</P>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Step 3-->
                            </div>
                            <!--end::Group-->

                            <!--begin::Actions-->
                            <div class="d-flex flex-stack">
                                <!--begin::Wrapper-->
                                <div class="me-2">
                                    <button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="previous">
                                        Back
                                    </button>
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Wrapper-->
                                <div>
                                    <button type="submit" class="btn btn-primary" data-kt-stepper-action="submit">
                                        <span class="indicator-label">
                                            Submit
                                        </span>
                                        <span class="indicator-progress">
                                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>

                                    <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                                        Continue
                                    </button>
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
                <!--end::Stepper-->

            </div>
        </div>


        



    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<?= $this->section('javascript') ?>

<script type="text/javascript">

    // Stepper lement
    var element = document.querySelector("#kt_stepper_example_vertical");

    // Initialize Stepper
    var stepper = new KTStepper(element);

    // Handle next step
    stepper.on("kt.stepper.next", function (stepper) {
        stepper.goNext(); // go next step
    });

    // Handle previous step
    stepper.on("kt.stepper.previous", function (stepper) {
        stepper.goPrevious(); // go previous step
    });

    jQuery(document).ready(function($){

        var all_employees_id = [];

        $(document).on('submit', '#special_holiday_form', function(e){
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/special-holiday/update'); ?>",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        console.log(response.response_type);
                        if( response.response_type == 'error' ){
                            if( response.response_description.length ){
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
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
                        });
                    }
                })
        })

        $(document).on('change', '#holiday_id', function(e){
            e.preventDefault();
            window.location.replace(`<?php echo base_url('/backend/hr/special-holiday?holiday_id='); ?>${$(this).val()}`);
            
        })

        $(document).on('change', '#company_id', function(){
            var company_id = $('#company_id').val();
            var holiday_id = $('#holiday_id').val();
            if(company_id == 'all_companies'){
                $('#employee_list').find("div.form-check").slideDown('fast');
            }else{
                $('#employee_list').find("div.form-check").slideUp('fast');
                $('#employee_list').find("div.form-check.company_id_"+company_id).slideDown('fast');
            }
            
        })

    })
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>