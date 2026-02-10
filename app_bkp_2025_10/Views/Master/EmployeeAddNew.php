<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="container gy-5 g-xl-8">




            <div class="card shadow-sm">

                <?php
                // if( session()->get('current_user')['employee_id'] == '40' ){

                    ?>
                    <form id="add_employee" method="post" enctype="multipart/form-data" action="<?php echo base_url('/backend/master/employee/add-new/validate'); ?>">
                        <div class="container-fluid px-0 mx-0">
                            <div class="d-flex flex-column flex-lg-row">


                                <div class="flex-lg-row-fluid flex-grow-1 ms-lg-15">
                                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder mb-8">
                                        <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#Personal_Details">Personal</a>
                                        </li>
                                        <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Contact_Details">Contact</a>
                                        </li>
                                        <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Professional_Details">Professional</a>
                                        </li>
                                        <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Documents_Upload">Documents</a>
                                        </li>

                                        <?php
                                        if( $can_update_salary == true ) {
                                            ?>
                                            <li class="nav-item mt-2">
                                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Salary_Structure">Salary</a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                        <!-- <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 pt-5 pb-0" data-bs-toggle="tab" href="#Overrides">
                                                <span class="text-center" style="line-height: 1;">Overrides
                                                    <br>
                                                    <small style="font-size: .65em; font-weight: normal; color: #00df00; opacity: 0.8;">and Waiver & deduction</small>
                                                </span>
                                            </a>
                                        </li> -->
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="Personal_Details" role="tabpanel">
                                            <div class="card shadow-none">
                                                <div class="card-header ">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Personal Details</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body border-bottom">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">First Name</label>
                                                            <input type="text" id="first_name" name="first_name" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="First name" value="<?= set_value('first_name') ?>" />
                                                            <small class="text-danger error-text" id="first_name_error"><?= isset($validation) ? display_error($validation, 'first_name') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Last Name</label>
                                                            <input type="text" id="last_name" name="last_name" class="form-control form-control-sm " placeholder="Last name" value="<?= set_value('last_name') ?>" />
                                                            <small class="text-danger error-text" id="last_name_error"><?= isset($validation) ? display_error($validation, 'last_name') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Father's Name</label>
                                                            <input type="text" id="fathers_name" class="form-control form-control-sm" name="fathers_name" placeholder="Father's Name" value="<?= set_value('fathers_name') ?>" />
                                                            <small class="text-danger error-text" id="fathers_name_error"><?= isset($validation) ? display_error($validation, 'fathers_name') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Gender</label>
                                                            <select class="form-control form-control-sm mb-3 mb-lg-0" id="gender" name="gender" data-control="select2" data-placeholder="Select Gender" data-allow-clear="true">
                                                                <option></option>
                                                                <option value="female" <?= set_select('gender', 'female') ?> >Female</option>
                                                                <option value="male" <?= set_select('gender', 'male') ?> >Male</option>
                                                                <option value="other" <?= set_select('gender', 'other') ?> >Other</option>
                                                            </select>
                                                            <small class="text-danger error-text" id="gender_error"><?= isset($validation) ? display_error($validation, 'gender') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-3">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Marital Status</label>
                                                            <select class="form-control form-control-sm mb-3 mb-lg-0" id="marital_status" name="marital_status" data-control="select2" data-placeholder="Select Marital Status" data-allow-clear="true">
                                                                <option></option>
                                                                <option value="married" <?= set_select('marital_status', 'married') ?> >Married</option>
                                                                <option value="unmarried" <?= set_select('marital_status', 'unmarried') ?> >Un-Married</option>
                                                                <option value="divorced" <?= set_select('marital_status', 'divorced') ?> >Divorced</option>
                                                            </select>
                                                            <small class="text-danger error-text" id="marital_status_error"><?= isset($validation) ? display_error($validation, 'marital_status') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-5 husband-name-wrapper" style="display: <?php echo (@$gender == 'female' && @$marital_status == 'married' ) ? 'block' : 'none'; ?>">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Husband's Name</label>
                                                            <input type="text" id="husband_name" class="form-control form-control-sm" name="husband_name" placeholder="Husband's Name" value="<?= set_value('husband_name') ?>"  <?php echo (@$gender == 'female' && @$marital_status == 'married' ) ? '' : 'disabled'; ?> />
                                                            <small class="text-danger error-text" id="husband_name_error"><?= isset($validation) ? display_error($validation, 'husband_name') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Date Of Anniversary</label>
                                                            <div class="input-group input-group-flatpicker" id="date_of_anniversary_picker" data-wrap="true">
                                                                <input type="text" id="date_of_anniversary" class="form-control form-control-sm" name="date_of_anniversary" placeholder="Date Of Anniversary" value="<?= set_value('date_of_anniversary') ?>" data-input data-open >
                                                                <span class="input-group-text cursor-pointer" data-toggle>
                                                                    <i class="far fa-calendar-alt"></i>
                                                                </span>
                                                            </div>
                                                            <small class="text-danger error-text" id="date_of_anniversary_error"><?= isset($validation) ? display_error($validation, 'date_of_anniversary') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-4">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Date Of Birth</label>
                                                            <div class="input-group input-group-flatpicker" id="date_of_birth_picker" data-wrap="true">
                                                                <input type="text" id="date_of_birth" class="form-control form-control-sm" name="date_of_birth" placeholder="Date Of Birth" value="<?= set_value('date_of_birth') ?>" data-input data-open >
                                                                <span class="input-group-text cursor-pointer" data-toggle>
                                                                    <i class="far fa-calendar-alt"></i>
                                                                </span>
                                                            </div>
                                                            <small class="text-danger error-text" id="date_of_birth_error"><?= isset($validation) ? display_error($validation, 'date_of_birth') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Highest Qualification</label>
                                                            <input type="text" id="highest_qualification" class="form-control form-control-sm" name="highest_qualification" placeholder="Highest Qualification" value="<?= set_value('highest_qualification') ?>" />
                                                            <small class="text-danger error-text" id="highest_qualification_error"><?= isset($validation) ? display_error($validation, 'highest_qualification') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Total Experience</label>
                                                            <input type="text" id="total_experience" class="form-control form-control-sm" name="total_experience" placeholder="Total Experience" value="<?= set_value('total_experience') ?>" />
                                                            <small class="text-danger error-text" id="total_experience_error"><?= isset($validation) ? display_error($validation, 'total_experience') : '' ?></small>
                                                        </div>


                                                        <!--begin::Col-->
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Last company name</label>
                                                            <input type="text" id="last_company_name" class="form-control form-control-sm" name="last_company_name" placeholder="Last company name" value="<?= set_value('last_company_name') ?>" />
                                                            <small class="text-danger error-text" id="last_company_name_error"><?= isset($validation) ? display_error($validation, 'last_company_name') : '' ?></small>
                                                        </div>
                                                        <!--end::Col-->

                                                        <!--begin::Col-->
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Relevant experience</label>
                                                            <input type="text" id="relevant_experience" class="form-control form-control-sm" name="relevant_experience" placeholder="Relevant experience" value="<?= set_value('relevant_experience') ?>" />
                                                            <small class="text-danger error-text" id="relevant_experience_error"><?= isset($validation) ? display_error($validation, 'relevant_experience') : '' ?></small>
                                                        </div>
                                                        <!--end::Col-->

                                                        <!--begin::Col-->
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">College university</label>
                                                            <input type="text" id="college_university" class="form-control form-control-sm" name="college_university" placeholder="College university" value="<?= set_value('college_university') ?>" />
                                                            <small class="text-danger error-text" id="college_university_error"><?= isset($validation) ? display_error($validation, 'college_university') : '' ?></small>
                                                        </div>
                                                        <!--end::Col-->

                                                        <!--begin::Col-->
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Hobbies</label>
                                                            <input type="text" id="hobbies" class="form-control form-control-sm" name="hobbies" placeholder="Hobbies" value="<?= set_value('hobbies') ?>" />
                                                            <small class="text-danger error-text" id="hobbies_error"><?= isset($validation) ? display_error($validation, 'hobbies') : '' ?></small>
                                                        </div>
                                                        <!--end::Col-->


                                                    </div>

                                                </div>
                                                <div class="card-header ">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Permanent Address</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body border-bottom">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Permanent City</label>
                                                            <input type="text" id="permanent_city" class="form-control form-control-sm" name="permanent_city" placeholder="Permanent City" value="<?= set_value('permanent_city') ?>" />
                                                            <small class="text-danger error-text" id="permanent_city_error"><?= isset($validation) ? display_error($validation, 'permanent_city') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Permanent District</label>
                                                            <input type="text" id="permanent_district" class="form-control form-control-sm" name="permanent_district" placeholder="Permanent District" value="<?= set_value('permanent_district') ?>" />
                                                            <small class="text-danger error-text" id="permanent_district_error"><?= isset($validation) ? display_error($validation, 'permanent_district') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Permanent State</label>
                                                            <input type="text" id="permanent_state" class="form-control form-control-sm" name="permanent_state" placeholder="Permanent State" value="<?= set_value('permanent_state') ?>" />
                                                            <small class="text-danger error-text" id="permanent_state_error"><?= isset($validation) ? display_error($validation, 'permanent_state') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Permanent Pincode</label>
                                                            <input type="text" id="permanent_pincode" class="form-control form-control-sm" name="permanent_pincode" placeholder="Permanent Pincode" value="<?= set_value('permanent_pincode') ?>" />
                                                            <small class="text-danger error-text" id="permanent_pincode_error"><?= isset($validation) ? display_error($validation, 'permanent_pincode') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Permanent Address</label>
                                                            <textarea id="permanent_address" class="form-control form-control-sm" name="permanent_address" placeholder="Permanent Address"><?= set_value('permanent_address') ?></textarea>
                                                            <small class="text-danger error-text" id="permanent_address_error"><?= isset($validation) ? display_error($validation, 'permanent_address') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-header ">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Present Address</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body border-bottom">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Present City</label>
                                                            <input type="text" id="present_city" class="form-control form-control-sm" name="present_city" placeholder="Present City" value="<?= set_value('present_city') ?>" />
                                                            <small class="text-danger error-text" id="present_city_error"><?= isset($validation) ? display_error($validation, 'present_city') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Present District</label>
                                                            <input type="text" id="present_district" class="form-control form-control-sm" name="present_district" placeholder="Present District" value="<?= set_value('present_district') ?>" />
                                                            <small class="text-danger error-text" id="present_district_error"><?= isset($validation) ? display_error($validation, 'present_district') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Present State</label>
                                                            <input type="text" id="present_state" class="form-control form-control-sm" name="present_state" placeholder="Present State" value="<?= set_value('present_state') ?>" />
                                                            <small class="text-danger error-text" id="present_state_error"><?= isset($validation) ? display_error($validation, 'present_state') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Present Pincode</label>
                                                            <input type="text" id="present_pincode" class="form-control form-control-sm" name="present_pincode" placeholder="Present Pincode" value="<?= set_value('present_pincode') ?>" />
                                                            <small class="text-danger error-text" id="present_pincode_error"><?= isset($validation) ? display_error($validation, 'present_pincode') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Present Address</label>
                                                            <textarea id="present_address" class="form-control form-control-sm" name="present_address" placeholder="Present Address"><?= set_value('present_address') ?></textarea>
                                                            <small class="text-danger error-text" id="present_address_error"><?= isset($validation) ? display_error($validation, 'present_address') : '' ?></small>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="card-header ">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Family Details</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body border-bottom">
                                                    <div class="row">
                                                        <div class="col-lg-12 fv-row">
                                                            <div id="family_members">
                                                                <div class="form-group">
                                                                    <div data-repeater-list="family_members">
                                                                        <div data-repeater-item>
                                                                            <div class="form-group row mb-5">
                                                                                <div class="col-md-4">
                                                                                    <label class="form-label">Family Member Name</label>
                                                                                    <input type="text" class="form-control form-control-sm form-control-solid" name="member_name" placeholder="Family Member Name" />
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label class="form-label">Relation</label>
                                                                                    <select class="form-select form-select-sm form-select-solid" name="member_relation">
                                                                                        <option value="">Select Relation</option>
                                                                                        <option value="Mother">Mother</option>
                                                                                        <option value="Father">Father</option>
                                                                                        <option value="Daughter">Daughter</option>
                                                                                        <option value="Son">Son</option>
                                                                                        <option value="Wife">Wife</option>
                                                                                        <option value="Husband">Husband</option>
                                                                                        <option value="Sister">Sister</option>
                                                                                        <option value="Brother">Brother</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label class="form-label">DOB</label>
                                                                                    <div class="input-group flatpicker-repeater" id="date_of_birth_picker" data-wrap="true">
                                                                                        <input type="text" class="form-control form-control-sm " name="member_dob" placeholder="Date Of Birth" data-input data-open >
                                                                                        <span class="input-group-text cursor-pointer" data-toggle>
                                                                                            <i class="far fa-calendar-alt"></i>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <label class="form-label">&nbsp;</label><br>
                                                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                                                        <i class="la la-trash-o"></i>Delete
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                                                        <i class="la la-plus"></i>Add Row
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="Contact_Details" role="tabpanel">
                                            <div class="card shadow-none">
                                                <div class="card-header ">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Personal Contact Details</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body border-bottom">

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Personal Email</label>
                                                            <input type="email" id="personal_email" class="form-control form-control-sm" name="personal_email" placeholder="Personal Email" value="<?= set_value('personal_email') ?>" />
                                                            <small class="text-danger error-text" id="personal_email_error"><?= isset($validation) ? display_error($validation, 'personal_email') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Personal Mobile</label>
                                                            <input type="text" id="personal_mobile" class="form-control form-control-sm" name="personal_mobile" placeholder="Personal Mobile" value="<?= set_value('personal_mobile') ?>" />
                                                            <small class="text-danger error-text" id="personal_mobile_error"><?= isset($validation) ? display_error($validation, 'personal_mobile') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Emergency Contact</label>
                                                            <input type="text" id="emergency_contact_number" class="form-control form-control-sm" name="emergency_contact_number" placeholder="Emergency Contact" value="<?= set_value('emergency_contact_number') ?>" />
                                                            <small class="text-danger error-text" id="emergency_contact_number_error"><?= isset($validation) ? display_error($validation, 'emergency_contact_number') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-header ">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Official Contact Details</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body border-bottom">

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Work Email</label>
                                                            <input type="email" id="work_email" class="form-control form-control-sm" name="work_email" placeholder="Work Email" value="<?= set_value('work_email') ?>" />
                                                                <small class="text-danger error-text" id="work_email_error"><?= isset($validation) ? display_error($validation, 'work_email') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Work Mobile</label>
                                                            <input type="text" id="work_mobile" class="form-control form-control-sm" name="work_mobile" placeholder="Work Mobile" value="<?= set_value('work_mobile') ?>" />
                                                                <small class="text-danger error-text" id="work_mobile_error"><?= isset($validation) ? display_error($validation, 'work_mobile') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Work Phone Extension Number</label>
                                                            <input type="text" id="work_phone_extension_number" class="form-control form-control-sm" name="work_phone_extension_number" placeholder="Work Phone Extension Number" value="<?= set_value('work_phone_extension_number') ?>" />
                                                                <small class="text-danger error-text" id="work_phone_extension_number_error"><?= isset($validation) ? display_error($validation, 'work_phone_extension_number') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Work Phone CUG Number</label>
                                                            <input type="text" id="work_phone_cug_number" class="form-control form-control-sm" name="work_phone_cug_number" placeholder="Work Phone CUG Number" value="<?= set_value('work_phone_cug_number') ?>" />
                                                                <small class="text-danger error-text" id="work_phone_cug_number_error"><?= isset($validation) ? display_error($validation, 'work_phone_cug_number') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Desk Location</label>
                                                            <input type="text" id="desk_location" class="form-control form-control-sm" name="desk_location" placeholder="Desk Location" value="<?= set_value('desk_location') ?>" />
                                                            <small class="text-danger error-text" id="desk_location_error"><?= isset($validation) ? display_error($validation, 'desk_location') : '' ?></small>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="Professional_Details" role="tabpanel">
                                            <div class="card shadow-none">
                                                <div class="card-header ">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Professional Details</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body border-bottom">

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Internal Employee ID</label>
                                                            <input type="text" id="internal_employee_id" class="form-control form-control-sm" name="internal_employee_id" placeholder="Inernal Employee ID" value="<?= set_value('internal_employee_id') ?>" />
                                                            <small class="text-danger error-text" id="internal_employee_id_error"><?= isset($validation) ? display_error($validation, 'internal_employee_id') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Company</label>
                                                            <select class="form-control form-control-sm" id="company_id" name="company_id" data-control="select2" data-placeholder="Select a Company" data-allow-clear="true" >
                                                                <option></option>
                                                                <?php
                                                                foreach( $companies as $company){
                                                                    ?>
                                                                    <option value="<?php echo $company['id']; ?>" <?= set_select('company_id', $company['id']) ?> ><?php echo $company['company_name']; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <small class="text-danger error-text" id="company_id_error"><?= isset($validation) ? display_error($validation, 'company_id') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Department</label>
                                                            <select class="form-control form-control-sm" id="department_id" name="department_id" data-control="select2" data-placeholder="Select a Department" data-allow-clear="true" >
                                                                <option></option>
                                                                <?php
                                                                if( isset($departments) && !empty($departments) ){
                                                                    foreach( $departments as $department ){
                                                                        ?>
                                                                        <option value="<?php echo $department['id']; ?>" <?= set_select('department_id', $department['id']) ?> ><?php echo $department['department_name']; ?> - <?php echo $department['company_short_name']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <small class="text-danger error-text" id="department_id_error"><?= isset($validation) ? display_error($validation, 'department_id') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Designation</label>
                                                            <select class="form-control form-control-sm" id="designation_id" name="designation_id" data-control="select2" data-placeholder="Select a Designation" data-allow-clear="true" >
                                                                <option></option>
                                                                <?php
                                                                if( isset($designations) && !empty($designations) ){
                                                                    foreach( $designations as $designation){
                                                                        ?>
                                                                        <option
                                                                        value="<?php echo $designation['id']; ?>"
                                                                        <?= set_select('designation_id', $designation['id']) ?>
                                                                        >
                                                                            <?php echo $designation['designation_name']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <small class="text-danger error-text" id="designation_id_error"><?= isset($validation) ? display_error($validation, 'designation_id') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Reporting Manager</label>
                                                            <select class="form-control form-control-sm" id="reporting_manager_id" name="reporting_manager_id" data-control="select2" data-placeholder="Select a Reporting Manager" data-allow-clear="true" >
                                                                <option></option>
                                                                <?php
                                                                if( isset($reportingManagers) && !empty($reportingManagers) ){
                                                                    foreach( $reportingManagers as $reportingManager){
                                                                        ?>
                                                                        <option
                                                                        value="<?php echo $reportingManager['id']; ?>"
                                                                        <?= set_select('reporting_manager_id', $reportingManager['id']) ?>
                                                                        >
                                                                            <?php echo $reportingManager['name']; ?> - <?php echo $reportingManager['department_name']; ?> - <?php echo $reportingManager['company_short_name']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <small class="text-danger error-text" id="reporting_manager_id_error"><?= isset($validation) ? display_error($validation, 'reporting_manager_id') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Joining Date</label>
                                                            <div class="input-group input-group-flatpicker" id="joining_date_picker" data-wrap="true">
                                                                <input type="text" id="joining_date" class="form-control form-control-sm" name="joining_date" placeholder="Joining Date" value="<?= set_value('joining_date') ?>" data-input data-open >
                                                                <span class="input-group-text cursor-pointer" data-toggle>
                                                                    <i class="far fa-calendar-alt"></i>
                                                                </span>
                                                            </div>
                                                            <small class="text-danger error-text" id="joining_date_error"><?= isset($validation) ? display_error($validation, 'joining_date') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Notice Period</label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" id="notice_period" class="form-control" name="notice_period" placeholder="Notice Period" value="<?= set_value('notice_period') ?>" data-inputmask="'mask': '9', 'repeat': 3, 'greedy' : false" oninput="$('#notice_period_error').html('')" />
                                                                <span class="input-group-text">Days</span>
                                                            </div>
                                                            <small class="text-danger error-text" id="notice_period_error"><?= isset($validation) ? display_error($validation, 'notice_period') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Date of leaving / Retirement</label>
                                                            <div class="input-group input-group-flatpicker" id="date_of_leaving_picker" data-wrap="true">
                                                                <input type="text" id="date_of_leaving" class="form-control form-control-sm" name="date_of_leaving" placeholder="Date of leaving" value="<?= set_value('date_of_leaving') ?>" data-input data-open >
                                                                <span class="input-group-text cursor-pointer" data-toggle>
                                                                    <i class="far fa-calendar-alt"></i>
                                                                </span>
                                                            </div>
                                                            <small class="text-danger error-text" id="date_of_leaving_error"><?= isset($validation) ? display_error($validation, 'date_of_leaving') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Employment Status</label>
                                                            <select class="form-control form-control-sm" id="status" name="status" data-control="select2" data-placeholder="Select Status" data-allow-clear="true">
                                                                <option></option>
                                                                <option value="active" <?= set_select('status', 'active') ?> >Active</option>
                                                                <option value="abscond" <?= set_select('status', 'abscond') ?> >Abscond</option>
                                                                <option value="left" <?= set_select('status', 'left') ?> >Left</option>
                                                                <option value="retired" <?= set_select('status', 'retired') ?> >Retired</option>
                                                            </select>
                                                            <small class="text-danger error-text" id="status_error"><?= isset($validation) ? display_error($validation, 'status') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Probation Status</label>
                                                            <select class="form-control form-control-sm" id="probation" name="probation" data-control="select2" data-placeholder="Select Probation" data-allow-clear="true">
                                                                <option></option>
                                                                <option value="45 Days Probation" <?= set_select('probation', '45 Days Probation') ?> >45 Days Probation</option>
                                                                <option value="90 Days Probation" <?= set_select('probation', '90 Days Probation') ?> >90 Days Probation</option>
                                                                <option value="confirmed" <?= set_select('probation', 'confirmed') ?> >Confirmed</option>
                                                            </select>
                                                            <small class="text-danger error-text" id="probation_error"><?= isset($validation) ? display_error($validation, 'probation') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Role</label>
                                                            <select class="form-control form-control-sm" id="role" name="role" data-control="select2" data-placeholder="Select a Role" data-allow-clear="true">
                                                                <option></option>
                                                                <?php
                                                                if( isset($roles) && !empty($roles) ){
                                                                    foreach( $roles as $role_row){
                                                                        ?>
                                                                        <option
                                                                            <?php if( $role_row['role_name'] == 'superuser' && session()->get('current_user')['employee_id'] != 40 ){ echo 'disabled'; } ?>
                                                                            value="<?php echo $role_row['role_name']; ?>"
                                                                            <?= set_select('role', $role_row['role_name']) ?>
                                                                        >
                                                                            <?php echo ucfirst($role_row['role_name']); ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <small class="text-danger error-text" id="role_error"><?= isset($validation) ? display_error($validation, 'role') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Shift</label>
                                                            <select class="form-control form-control-sm" id="shift_id" name="shift_id" data-control="select2" data-placeholder="Select a Shift" data-allow-clear="true">
                                                                <option></option>
                                                                <?php
                                                                if( isset($shifts) && !empty($shifts) ){
                                                                    foreach( $shifts as $shift_row){
                                                                        ?>
                                                                        <option value="<?php echo $shift_row['id']; ?>" <?= set_select('shift_id', $shift_row['id']) ?> ><?php echo $shift_row['shift_name']; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <small class="text-danger error-text" id="shift_id_error"><?= isset($validation) ? display_error($validation, 'shift_id') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Machine</label>
                                                            <select class="form-control form-control-sm" id="machine" name="machine" data-control="select2" data-placeholder="Select a Machine" data-allow-clear="true">
                                                                <option></option>
                                                                <option value="del" <?= set_select('machine', 'del') ?> >Delhi</option>
                                                                <option value="ggn" <?= set_select('machine', 'ggn') ?> >Gurugram</option>
                                                                <option value="hn" <?= set_select('machine', 'hn') ?> >Hueuer Noida</option>
                                                                <option value="skbd" <?= set_select('machine', 'skbd') ?> >Sikandrabad</option>
                                                            </select>
                                                            <small class="text-danger error-text" id="machine_error"><?= isset($validation) ? display_error($validation, 'machine') : '' ?></small>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="form-label form-label-sm required fw-bold fs-6">Min Wages Category</label>
                                                            <select class="form-control form-control-sm" id="min_wages_category" name="min_wages_category" data-control="select2" data-placeholder="Select an option" data-allow-clear="true">
                                                                <option></option>
                                                                <?php
                                                                if( isset($MinWagesCategories) && !empty($MinWagesCategories) ){
                                                                    foreach( $MinWagesCategories as $MinWagesCategory){
                                                                        ?>
                                                                        <option
                                                                        value="<?php echo $MinWagesCategory['id']; ?>"
                                                                        <?= set_select('min_wages_category', $MinWagesCategory['id']) ?>
                                                                        >
                                                                            <?php echo $MinWagesCategory['minimum_wages_category_name']; ?> (<?php echo $MinWagesCategory['minimum_wages_category_state']; ?>)
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <small class="text-danger error-text" id="min_wages_category_error"><?= isset($validation) ? display_error($validation, 'min_wages_category') : '' ?></small>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Allowed Leaves</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body border-bottom">

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center" style="gap:1rem">
                                                                <label for="cl_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer;">
                                                                    <input type="checkbox" class="form-check" name="cl_allowed" id="cl_allowed" value="yes" <?php echo set_checkbox('cl_allowed', 'yes'); ?> />
                                                                    <span >Allow CL</span>
                                                                </label>
                                                                <label for="el_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                                    <input type="checkbox" class="form-check" name="el_allowed" id="el_allowed" value="yes" <?php echo set_checkbox('el_allowed', 'yes'); ?> />
                                                                    <span >Allow EL</span>
                                                                </label>
                                                                <label for="co_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                                    <input type="checkbox" class="form-check" name="co_allowed" id="co_allowed" value="yes" <?php echo set_checkbox('co_allowed', 'yes'); ?> />
                                                                    <span >Allow Comp Off</span>
                                                                </label>
                                                                <label for="sl_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                                    <input type="checkbox" class="form-check" name="sl_allowed" id="sl_allowed" value="yes" <?php echo set_checkbox('sl_allowed', 'yes'); ?> />
                                                                    <span >Allow Sick Leave</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="Documents_Upload" role="tabpanel">
                                            <div class="card shadow-none">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h3 class="fw-bolder">Documents Upload</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body">

                                                    <div class="row">

                                                        <div class="col-md-6 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="avatar_number">Photo</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="avatar_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#avatar_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="avatar_attachment" name="avatar_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="avatar_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="avatar_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="avatar_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="avatar_attachment_error"><?= isset($validation) ? display_error($validation, 'avatar_attachment') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="pan_card_number">Pan Card</label>
                                                                        <input type="text" id="pan_card_number" class="form-control form-control-sm" name="pan_card_number" placeholder="Pan Card Number" value="<?= set_value('pan_card_number', @$attachment['pan']['number']) ?>" />
                                                                        <small class="text-danger error-text" id="pan_card_number_error"><?= isset($validation) ? display_error($validation, 'pan_card_number') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="pan_card_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#pan_card_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="pan_card_attachment" name="pan_card_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="pan_card_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="pan_card_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="pan_card_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="pan_card_attachment_error"><?= isset($validation) ? display_error($validation, 'pan_card_attachment') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="bank_name">Bank Name</label>
                                                                        <input type="text" id="bank_name" class="form-control form-control-sm" name="bank_name" placeholder="Bank Name" value="<?= set_value('bank_name', @$attachment['bank_account']['name']) ?>" />
                                                                        <small class="text-danger error-text" id="bank_name_error"><?= isset($validation) ? display_error($validation, 'bank_name') : '' ?></small>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="bank_account_number">Bank Account</label>
                                                                        <input type="text" id="bank_account_number" class="form-control form-control-sm" name="bank_account_number" placeholder="Bank Account Number" value="<?= set_value('bank_account_number', @$attachment['bank_account']['number']) ?>" />
                                                                        <small class="text-danger error-text" id="bank_account_number_error"><?= isset($validation) ? display_error($validation, 'bank_account_number') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="bank_account_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay  preview-button" data-bs-target="#bank_account_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="bank_account_attachment" name="bank_account_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="bank_account_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="bank_account_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="bank_account_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="bank_account_attachment_error"><?= isset($validation) ? display_error($validation, 'bank_account_attachment') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mb-4">
                                                            <div class="row me-md-n1 pt-5 mb-3 bg-light rounded">
                                                                <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="passport_number">Passport</label>
                                                                        <input type="text" id="passport_number" class="form-control form-control-sm" name="passport_number" placeholder="Passport Number" value="<?= set_value('passport_number', @$attachment['passport']['number']) ?>" />
                                                                        <small class="text-danger error-text" id="passport_number_error"><?= isset($validation) ? display_error($validation, 'passport_number') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="passport_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#passport_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="passport_attachment" name="passport_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="passport_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="passport_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="passport_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="passport_attachment_error"><?= isset($validation) ? display_error($validation, 'passport_attachment') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="adhar_card_number">Adhar Card</label>
                                                                        <input type="text" id="adhar_card_number" class="form-control form-control-sm" name="adhar_card_number" placeholder="Adhar Card Number" value="<?= set_value('adhar_card_number', @$attachment['adhar']['number']) ?>" />
                                                                        <small class="text-danger error-text" id="adhar_card_number_error"><?= isset($validation) ? display_error($validation, 'adhar_card_number') : '' ?></small>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="adhar_card_attachment_front_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#adhar_card_attachment_front_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="adhar_card_attachment_front" name="adhar_card_attachment_front" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="adhar_card_attachment_front_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="adhar_card_attachment_front_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="adhar_card_attachment_front_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="adhar_card_attachment_front_error"><?= isset($validation) ? display_error($validation, 'adhar_card_attachment_front') : '' ?></small>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="adhar_card_attachment_back_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#adhar_card_attachment_back_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="adhar_card_attachment_back" name="adhar_card_attachment_back" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="adhar_card_attachment_back_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="adhar_card_attachment_back_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="adhar_card_attachment_back_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="adhar_card_attachment_back_error"><?= isset($validation) ? display_error($validation, 'adhar_card_attachment_back') : '' ?></small>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="kye_documents_remarks">K. Y. E. Documents</label>
                                                                        <br><small style="font-size: 0.7rem">Know your employee</small><br><small style="font-size: 0.7rem">or Onboarding Document (Appointment letter, Offer letter etc)</small>
                                                                        <textarea id="kye_documents_remarks" class="form-control form-control-sm" name="kye_documents_remarks" placeholder="Additional Information"><?= set_value('kye_documents_remarks', @$attachment['kye_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="kye_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'kye_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="kye_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#kye_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="kye_documents_attachment" name="kye_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="kye_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="kye_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="kye_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="kye_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'kye_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="family_details_remarks">Family details</label>
                                                                        <textarea id="family_details_remarks" class="form-control form-control-sm" name="family_details_remarks" placeholder="Additional Information"><?= set_value('family_details_remarks', @$attachment['family_details']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="family_details_remarks_error"><?= isset($validation) ? display_error($validation, 'family_details_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="family_details_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#family_details_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="family_details_attachment" name="family_details_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="family_details_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="family_details_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="family_details_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="family_details_attachment_error"><?= isset($validation) ? display_error($validation, 'family_details_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="loan_documents_remarks">Loan Documents</label>
                                                                        <textarea id="loan_documents_remarks" class="form-control form-control-sm" name="loan_documents_remarks" placeholder="Additional Information"><?= set_value('loan_documents_remarks', @$attachment['loan_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="loan_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'loan_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="loan_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#loan_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="loan_documents_attachment" name="loan_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="loan_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="loan_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="loan_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="loan_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'loan_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="educational_documents_remarks">Educational documents</label>
                                                                        <textarea id="educational_documents_remarks" class="form-control form-control-sm" name="educational_documents_remarks" placeholder="Additional Information"><?= set_value('educational_documents_remarks', @$attachment['educational_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="educational_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'educational_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="educational_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#educational_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="educational_documents_attachment" name="educational_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="educational_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="educational_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="educational_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="educational_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'educational_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="relieving_documents_remarks">Relieving documents</label>
                                                                        <textarea id="relieving_documents_remarks" class="form-control form-control-sm" name="relieving_documents_remarks" placeholder="Additional Information"><?= set_value('relieving_documents_remarks', @$attachment['relieving_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="relieving_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'relieving_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="relieving_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#relieving_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="relieving_documents_attachment" name="relieving_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="relieving_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="relieving_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="relieving_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="relieving_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'relieving_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="misc_documents_remarks">Misc documents</label>
                                                                        <textarea id="misc_documents_remarks" class="form-control form-control-sm" name="misc_documents_remarks" placeholder="Additional Information"><?= set_value('misc_documents_remarks', @$attachment['misc_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="misc_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'misc_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="misc_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#misc_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="misc_documents_attachment" name="misc_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="misc_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="misc_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="misc_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="misc_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'misc_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-3">
                                                                        <label class=" form-label">PDC Cheques</label>
                                                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                                                            <label class="form-check-label me-3" for="enable_pdc">
                                                                                No
                                                                            </label>
                                                                            <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="enable_pdc" name="enable_pdc" >
                                                                            <label class="form-check-label" for="enable_pdc">
                                                                                Yes
                                                                            </label>
                                                                        </div>
                                                                        <small class="text-danger error-text" id="enable_pdc_error"></small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row me-md-n1 pt-5 bg-light rounded" id="pdc_container" style="display: none;">
                                                                <div class="col-md-8 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="pdc_cheque_numbers">PDC Cheque Numbers</label>
                                                                        <div>
                                                                            <div class="input-group input-group-sm mb-3">
                                                                                <span class="input-group-text">Bank name</span>
                                                                                <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_1" name="pdc_bank_name_1" value="<?= set_value('pdc_bank_name_1', @$attachment['pdc_cheque']['bank_name_1']) ?>">
                                                                                <span class="input-group-text">Cheque Number</span>
                                                                                <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_1" name="pdc_cheque_number_1" value="<?= set_value('pdc_cheque_number_1', @$attachment['pdc_cheque']['cheque_number_1']) ?>">
                                                                            </div>
                                                                            <small class="text-danger error-text" id="pdc_bank_name_1_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_1') : '' ?></small>
                                                                            <small class="text-danger error-text" id="pdc_cheque_number_1_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_1') : '' ?></small>
                                                                        </div>
                                                                        <div>
                                                                            <div class="input-group input-group-sm mb-3">
                                                                                <span class="input-group-text">Bank name</span>
                                                                                <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_2" name="pdc_bank_name_2" value="<?= set_value('pdc_bank_name_2', @$attachment['pdc_cheque']['bank_name_2']) ?>">
                                                                                <span class="input-group-text">Cheque Number</span>
                                                                                <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_2" name="pdc_cheque_number_2" value="<?= set_value('pdc_cheque_number_2', @$attachment['pdc_cheque']['cheque_number_2']) ?>">
                                                                            </div>
                                                                            <small class="text-danger error-text" id="pdc_bank_name_2_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_2') : '' ?></small>
                                                                            <small class="text-danger error-text" id="pdc_cheque_number_2_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_2') : '' ?></small>
                                                                        </div>
                                                                        <div>
                                                                            <div class="input-group input-group-sm mb-3">
                                                                                <span class="input-group-text">Bank name</span>
                                                                                <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_3" name="pdc_bank_name_3" value="<?= set_value('pdc_bank_name_3', @$attachment['pdc_cheque']['bank_name_3']) ?>">
                                                                                <span class="input-group-text">Cheque Number</span>
                                                                                <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_3" name="pdc_cheque_number_3" value="<?= set_value('pdc_cheque_number_3', @$attachment['pdc_cheque']['cheque_number_3']) ?>">
                                                                            </div>
                                                                            <small class="text-danger error-text" id="pdc_bank_name_3_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_3') : '' ?></small>
                                                                            <small class="text-danger error-text" id="pdc_cheque_number_3_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_3') : '' ?></small>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-3" style="width: max-content;">

                                                                        <div id="pdc_cheque_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#pdc_cheque_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="pdc_cheque_attachment" name="pdc_cheque_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="pdc_cheque_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="pdc_cheque_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                            <iframe id="pdc_cheque_attachment_lightbox_iframe" class="loaded_content" width="100%"   src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="pdc_cheque_attachment_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        if( $can_update_salary == true ) {
                                            ?>
                                            <div class="tab-pane fade" id="Salary_Structure" role="tabpanel">
                                                <div class="card shadow-none">
                                                    <div class="card-header bg-info bg-gradient bg-opacity-50">
                                                        <div class="card-title">
                                                            <h3 class="fw-bolder">Salary Structure</h3>
                                                        </div>
                                                    </div>
                                                    <div class="card-body border-bottom">
                                                        <div class="row gy-3">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="basic_salary">Basic Salary</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="basic_salary" class="form-control form-control-sm " name="basic_salary" placeholder="Basic Salary" value="<?= set_value('basic_salary') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="basic_salary_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="house_rent_allowance">HRA</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="house_rent_allowance" class="form-control form-control-sm " name="house_rent_allowance" placeholder="HRA" value="<?= set_value('house_rent_allowance') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="house_rent_allowance_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="conveyance">Conveyance</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="conveyance" class="form-control form-control-sm " name="conveyance" placeholder="Conveyance" value="<?= set_value('conveyance') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="conveyance_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="medical_allowance">Medical Allowance</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="medical_allowance" class="form-control form-control-sm " name="medical_allowance" placeholder="Medical Allowance" value="<?= set_value('medical_allowance') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="medical_allowance_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="special_allowance">Special Allowance</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="special_allowance" class="form-control form-control-sm " name="special_allowance" placeholder="Special Allowance" value="<?= set_value('special_allowance') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="special_allowance_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="fuel_allowance">Fuel Allowance</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="fuel_allowance" class="form-control form-control-sm " name="fuel_allowance" placeholder="Fuel Allowance" value="<?= set_value('fuel_allowance') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="fuel_allowance_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="vacation_allowance">Vacation Allowance</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="vacation_allowance" class="form-control form-control-sm " name="vacation_allowance" placeholder="Vacation Allowance" value="<?= set_value('vacation_allowance') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="vacation_allowance_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="other_allowance">Other Allowance</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="other_allowance" class="form-control form-control-sm " name="other_allowance" placeholder="Other Allowance" value="<?= set_value('other_allowance') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="other_allowance_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="gratuity">Gratuity</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">((BasicSalary/26)*15)*(1/12)</span>
                                                                    </div>
                                                                    <small class="text-muted">Per month</small><br>
                                                                    <small class="text-danger error-text" id="gratuity_error"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-header bg-info bg-gradient bg-opacity-50">
                                                        <div class="card-title">
                                                            <h3 class="fw-bolder">Deductions</h3>
                                                        </div>
                                                    </div>
                                                    <div class="card-body border-bottom">

                                                        <div class="row gy-3">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label">PF</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label me-3" for="pf">
                                                                            No
                                                                        </label>
                                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="pf" name="pf" <?php echo set_checkbox('pf', 'yes'); ?> >
                                                                        <label class="form-check-label" for="pf">
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="non_compete_loan_error"></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4" id="pf_number_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="pf_number">UAN Number</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                                                        <input type="text" id="pf_number" class="form-control form-control-sm " name="pf_number" placeholder="UAN Number" value="<?= set_value('pf_number') ?>" />
                                                                    </div>
                                                                    <small class="text-danger error-text" id="pf_number_error"></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4"></div>

                                                            <div class="col-md-4" id="pf_employee_contribution_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="pf_employee_contribution">Employee Contribution</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">12%</span>
                                                                        <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-percent"></i></span>
                                                                    </div>
                                                                    <small class="text-muted">if BasicSalary >= 15000 or <br>GrossSalary-HRA >= 15000 <br>then value will be 12% of 15000, <br>otherwise value will be <br>12% of (GrossSalary-HRA)</small><br>
                                                                    <small class="text-danger error-text" id="pf_employee_contribution_error"></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4" id="pf_employer_contribution_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="pf_employer_contribution">Employer Contribution</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">13%</span>
                                                                        <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-percent"></i></span>
                                                                    </div>
                                                                    <small class="text-muted">if BasicSalary >= 15000 or <br>GrossSalary-HRA >= 15000 <br>then value will be 13% of 15000, <br>otherwise value will be <br>13% of (GrossSalary-HRA)</small><br>
                                                                    <small class="text-danger error-text" id="pf_employer_contribution_error"></small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr class="my-3 opacity-10">

                                                        <div class="row gy-3">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label">ESI</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label me-3" for="esi">
                                                                            No
                                                                        </label>
                                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="esi" name="esi" <?php echo set_checkbox('esi', 'yes'); ?> >
                                                                        <label class="form-check-label" for="esi">
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="esi_error"></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4" id="esi_number_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="esi_number">ESI Number</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                                                        <input type="text" id="esi_number" class="form-control form-control-sm " name="esi_number" placeholder="ESI Number" value="<?= set_value('esi_number') ?>" />
                                                                    </div>
                                                                    <small class="text-danger error-text" id="esi_number_error"></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4"></div>

                                                            <div class="col-md-4" id="esi_employee_contribution_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="esi_employee_contribution">Employee Contribution</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">0.75%</span>
                                                                        <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-percent"></i></span>
                                                                    </div>
                                                                    <small class="text-muted">0.75% of GrossSalary</small><br>
                                                                    <small class="text-danger error-text" id="esi_employee_contribution_error"></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4" id="esi_employer_contribution_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="esi_employer_contribution">Employer Contribution</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">3.25%</span>
                                                                        <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-percent"></i></span>
                                                                    </div>
                                                                    <small class="text-muted">3.25% of GrossSalary</small><br>
                                                                    <small class="text-danger error-text" id="esi_employer_contribution_error"></small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr class="my-3 opacity-10">

                                                        <div class="row gy-3">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label">LWF</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label me-3" for="lwf">
                                                                            No
                                                                        </label>
                                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="lwf" name="lwf" <?php echo set_checkbox('lwf', 'yes'); ?> >
                                                                        <label class="form-check-label" for="lwf">
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="lwf_error"></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4" id="lwf_employee_contribution_container" style="display: <?php echo (@$salary['lwf'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="lwf_employee_contribution">Employee Contribution</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">0.2%</span>
                                                                        <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-percent"></i></span>
                                                                    </div>
                                                                    <small class="text-muted">if state is HARYANA then value will be 0.2% maximum ₹31/-</small><br>
                                                                    <small class="text-danger error-text" id="lwf_employee_contribution_error"></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4" id="lwf_employer_contribution_container" style="display: <?php echo (@$salary['lwf'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="lwf_employer_contribution">Employer Contribution</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">2 X employee_contribution</span>
                                                                        <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-percent"></i></span>
                                                                    </div>
                                                                    <small class="text-muted">if state is HARYANA Twice of the employee contribution</small><br>
                                                                    <small class="text-danger error-text" id="lwf_employer_contribution_error"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-header bg-info bg-gradient bg-opacity-50">
                                                        <h5 class="card-title">Other Benifits</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row gy-3">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label">Bonus</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label me-3" for="enable_bonus">
                                                                            No
                                                                        </label>
                                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="enable_bonus" name="enable_bonus" <?php echo set_checkbox('enable_bonus', 'yes'); ?> >
                                                                        <label class="form-check-label" for="enable_bonus">
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="enable_bonus_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="bonus_container" style="display: <?php echo (@$salary['enable_bonus'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="bonus">Bonus</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">8.33% of Minimum Wages</span>
                                                                    </div>
                                                                    <small class="text-muted">Per month</small><br>
                                                                    <small class="text-danger error-text" id="bonus_error"></small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr class="my-3 opacity-10">

                                                        <div class="row gy-3">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label">Non Compete Loan</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label me-3" for="non_compete_loan">
                                                                            No
                                                                        </label>
                                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="non_compete_loan" name="non_compete_loan" <?php echo set_checkbox('non_compete_loan', 'yes'); ?> >
                                                                        <label class="form-check-label" for="non_compete_loan">
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="non_compete_loan_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="non_compete_loan_from_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="non_compete_loan_from">From</label>
                                                                    <div class="input-group input-group-flatpicker" id="non_compete_loan_from_picker" data-wrap="true">
                                                                        <input type="text" id="non_compete_loan_from" class="form-control form-control-sm" name="non_compete_loan_from" placeholder="From" value="<?= set_value('non_compete_loan_from') ?>" data-input data-open >
                                                                        <span class="input-group-text cursor-pointer" data-toggle>
                                                                            <i class="far fa-calendar-alt"></i>
                                                                        </span>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="non_compete_loan_from_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="non_compete_loan_to_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="non_compete_loan_to">To</label>
                                                                    <div class="input-group input-group-flatpicker" id="non_compete_loan_to_picker" data-wrap="true">
                                                                        <input type="text" id="non_compete_loan_to" class="form-control form-control-sm" name="non_compete_loan_to" placeholder="To" value="<?= set_value('non_compete_loan_to') ?>" data-input data-open >
                                                                        <span class="input-group-text cursor-pointer" data-toggle>
                                                                            <i class="far fa-calendar-alt"></i>
                                                                        </span>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="non_compete_loan_to_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="non_compete_loan_amount_per_month_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="non_compete_loan_amount_per_month">Amount Per Month</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="non_compete_loan_amount_per_month" class="form-control form-control-sm " name="non_compete_loan_amount_per_month" placeholder="Amount Per Month" value="<?= set_value('non_compete_loan_amount_per_month') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="non_compete_loan_amount_per_month_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8" id="non_compete_loan_remarks_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="non_compete_loan_remarks">Remarks</label>
                                                                    <textarea id="non_compete_loan_remarks" class="form-control " name="non_compete_loan_remarks" placeholder="Remarks"><?= set_value('non_compete_loan_remarks') ?></textarea>
                                                                    <small class="text-danger error-text" id="non_compete_loan_amount_per_month_error"></small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr class="my-3 opacity-10">

                                                        <div class="row gy-3">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class=" form-label">Loyalty Incentive</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label me-3" for="loyalty_incentive">
                                                                            No
                                                                        </label>
                                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="loyalty_incentive" name="loyalty_incentive" <?php echo set_checkbox('loyalty_incentive', 'yes'); ?> >
                                                                        <label class="form-check-label" for="loyalty_incentive">
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="loyalty_incentive_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="loyalty_incentive_from_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="loyalty_incentive_from">From</label>
                                                                    <div class="input-group input-group-flatpicker" id="loyalty_incentive_from_picker" data-wrap="true">
                                                                        <input type="text" id="loyalty_incentive_from" class="form-control form-control-sm" name="loyalty_incentive_from" placeholder="From" value="<?= set_value('loyalty_incentive_from') ?>" data-input data-open >
                                                                        <span class="input-group-text cursor-pointer" data-toggle>
                                                                            <i class="far fa-calendar-alt"></i>
                                                                        </span>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="loyalty_incentive_from_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="loyalty_incentive_to_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="loyalty_incentive_to">To</label>
                                                                    <div class="input-group input-group-flatpicker" id="loyalty_incentive_to_picker" data-wrap="true">
                                                                        <input type="text" id="loyalty_incentive_to" class="form-control form-control-sm" name="loyalty_incentive_to" placeholder="To" value="<?= set_value('loyalty_incentive_to') ?>" data-input data-open >
                                                                        <span class="input-group-text cursor-pointer" data-toggle>
                                                                            <i class="far fa-calendar-alt"></i>
                                                                        </span>
                                                                    </div>
                                                                    <small class="text-danger error-text" id="loyalty_incentive_to_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="loyalty_incentive_amount_per_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="loyalty_incentive_amount_per_month">Amount Per Month</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                                        <input type="text" id="loyalty_incentive_amount_per_month" class="form-control form-control-sm " name="loyalty_incentive_amount_per_month" placeholder="Amount Per Month" value="<?= set_value('loyalty_incentive_amount_per_month') ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false"/>
                                                                    </div>
                                                                    <small class="text-muted">Max 10 digits</small><br>
                                                                    <small class="text-danger error-text" id="loyalty_incentive_amount_per_month_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="loyalty_incentive_mature_after_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="loyalty_incentive_mature_after_month">Mature every X month</label>
                                                                    <select class="form-control form-control-sm" id="loyalty_incentive_mature_after_month" name="loyalty_incentive_mature_after_month" data-control="select2" data-placeholder="Select maturity month" onchange="$('#loyalty_incentive_mature_after_month_error').html('')">
                                                                        <option></option>
                                                                        <option value="0" <?= set_select('loyalty_incentive_mature_after_month', '0') ?> >Salary Month</option>
                                                                        <option value="01" <?= set_select('loyalty_incentive_mature_after_month', '1') ?> >1 Month</option>
                                                                        <option value="03" <?= set_select('loyalty_incentive_mature_after_month', '3') ?> >3 Month</option>
                                                                        <option value="06" <?= set_select('loyalty_incentive_mature_after_month', '6') ?> >6 Month</option>
                                                                        <option value="12" <?= set_select('loyalty_incentive_mature_after_month', '12') ?> >12 Month</option>
                                                                    </select>
                                                                    <small class="text-danger error-text" id="loyalty_incentive_mature_after_month_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="loyalty_incentive_pay_after_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="loyalty_incentive_pay_after_month">Pay every X month</label>
                                                                    <select class="form-control form-control-sm" id="loyalty_incentive_pay_after_month" name="loyalty_incentive_pay_after_month" data-control="select2" data-placeholder="Select month for payment" onchange="$('#loyalty_incentive_pay_after_month_error').html('')">
                                                                        <option></option>
                                                                        <option value="0" <?= set_select('loyalty_incentive_pay_after_month', '0') ?> >Salary Month</option>
                                                                        <option value="01" <?= set_select('loyalty_incentive_pay_after_month', '1') ?> >1 Month after Maturity</option>
                                                                        <option value="03" <?= set_select('loyalty_incentive_pay_after_month', '3') ?> >3 Month after Maturity</option>
                                                                        <option value="06" <?= set_select('loyalty_incentive_pay_after_month', '6') ?> >6 Month after Maturity</option>
                                                                        <option value="12" <?= set_select('loyalty_incentive_pay_after_month', '12') ?> >12 Month after Maturity</option>
                                                                    </select>
                                                                    <small class="text-muted">Amount will be paid after maturity time + selected value</small><br>
                                                                    <small class="text-danger error-text" id="loyalty_incentive_pay_after_month_error"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8" id="loyalty_incentive_remarks_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                                <div class="form-group">
                                                                    <label class=" form-label" for="loyalty_incentive_remarks">Remarks</label>
                                                                    <textarea id="loyalty_incentive_remarks" class="form-control " name="loyalty_incentive_remarks" placeholder="Remarks"><?= set_value('loyalty_incentive_remarks') ?></textarea>
                                                                    <small class="text-danger error-text" id="loyalty_incentive_amount_per_month_error"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                        <!-- <div class="tab-pane fade" id="Overrides" role="tabpanel">
                                            <div class="card shadow-none">

                                                <?php
                                                if( $can_override_rh ){
                                                    ?>
                                                    <div class="card-header py-0">
                                                        <div class="card-title">
                                                            <h3 class="fw-bolder text-info">Religious Holidays</h3>
                                                        </div>
                                                    </div>
                                                    <div class="card-body border-bottom">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-4">
                                                                <label class="floating-label">First RH</label>
                                                                <span class="form-control form-control-sm border-dashed" id="first_rh_view">N/A</span>
                                                            </div>

                                                            <div class="col-lg-3 col-md-4">
                                                                <label class="floating-label">Second RH</label>
                                                                <span class="form-control form-control-sm border-dashed" id="second_rh_view">N/A</span>
                                                            </div>

                                                            <div class="col-lg-3 col-md-4">
                                                                <button target="_blank" id="rh_override_drawer_toggle" class="btn btn-sm btn-light-primary border border-dashed border-primary py-2 mt-6">Override</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                                if(
                                                    $can_override_rh == false
                                                ){
                                                    ?>
                                                    <div class="card shadow-none">
                                                        <div class="card-body">
                                                            <p class="mb-0 text-muted">You are not authorised to Override</p>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                            </div>
                                        </div> -->
                                    </div>

                                    <div class="d-flex justify-content-end py-6 px-9">
                                        <button type="submit" id="submit_add_employee" class="form-control btn btn-sm btn-primary d-inline">
                                            <span class="indicator-label">Submit</span>
                                            <span class="indicator-progress">
                                                Please wait...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </form>
                    <?php

                // }
                // else{
                    ?>
                    <!-- <form id="add_employee" method="post" enctype="multipart/form-data" action="<?php echo base_url('/backend/master/employee/add-new/validate'); ?>">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="accordion accordion-icon-toggle rounded border">
                                        <div class="card">
                                            <div class="card-header accordion-header" data-bs-toggle="collapse" data-bs-target="#common_information">
                                                <div class="card-title">
                                                    Common
                                                </div>
                                                <span class="accordion-icon">
                                                    <span class="svg-icon svg-icon-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                            <div id="common_information" class="collapse show">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="internal_employee_id">Internal Employee ID</label>
                                                                <input type="text" id="internal_employee_id" class="form-control form-control-sm" name="internal_employee_id" placeholder="Inernal Employee ID" value="<?= set_value('internal_employee_id') ?>" />
                                                                <small class="text-danger error-text" id="internal_employee_id_error"><?= isset($validation) ? display_error($validation, 'internal_employee_id') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="first_name">First Name</label>
                                                                <input type="text" id="first_name" class="form-control form-control-sm" name="first_name" placeholder="First Name" value="<?= set_value('first_name') ?>" />
                                                                <small class="text-danger error-text" id="first_name_error"><?= isset($validation) ? display_error($validation, 'first_name') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="last_name">Last Name</label>
                                                                <input type="text" id="last_name" class="form-control form-control-sm" name="last_name" placeholder="Last Name" value="<?= set_value('last_name') ?>" />
                                                                <small class="text-danger error-text" id="last_name_error"><?= isset($validation) ? display_error($validation, 'last_name') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="company_id">Company</label>
                                                                <select class="form-control form-control-sm" id="company_id" name="company_id" data-control="select2" data-placeholder="Select a Company" data-allow-clear="true">
                                                                    <option></option>
                                                                    <?php
                                                                    foreach( $companies as $company){
                                                                        ?>
                                                                        <option value="<?php echo $company['id']; ?>" <?= set_select('company_id', $company['id']) ?> ><?php echo $company['company_name']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <small class="text-danger error-text" id="company_id_error"><?= isset($validation) ? display_error($validation, 'company_id') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="department_id">Department</label>
                                                                <select class="form-control form-control-sm" id="department_id" name="department_id" data-control="select2" data-placeholder="Select a Department" data-allow-clear="true">
                                                                    <option></option>
                                                                    <?php
                                                                    if( isset($departments) && !empty($departments) ){
                                                                        foreach( $departments as $department ){
                                                                            ?>
                                                                            <option value="<?php echo $department['id']; ?>" <?= set_select('department_id', $department['id']) ?> >    <?php echo $department['department_name']; ?>
                                                                            </option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <small class="text-danger error-text" id="department_id_error"><?= isset($validation) ? display_error($validation, 'department_id') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="designation_id">Designation</label>
                                                                <select class="form-control form-control-sm" id="designation_id" name="designation_id" data-control="select2" data-placeholder="Select a Designation" data-allow-clear="true">
                                                                    <option></option>
                                                                    <?php
                                                                    if( isset($designations) && !empty($designations) ){
                                                                        foreach( $designations as $designation){
                                                                            ?>
                                                                            <option value="<?php echo $designation['id']; ?>" <?= set_select('designation_id', $designation['id']) ?> ><?php echo $designation['designation_name']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <small class="text-danger error-text" id="designation_id_error"><?= isset($validation) ? display_error($validation, 'designation_id') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="reporting_manager_id">Reporting Manager</label>
                                                                <select class="form-control form-control-sm" id="reporting_manager_id" name="reporting_manager_id" data-control="select2" data-placeholder="Select a Reporting Manager" data-allow-clear="true">
                                                                    <option></option>
                                                                    <?php
                                                                    if( isset($reportingManagers) && !empty($reportingManagers) ){
                                                                        foreach( $reportingManagers as $reportingManager){
                                                                            ?>
                                                                            <option value="<?php echo $reportingManager['id']; ?>" <?= set_select('reporting_manager_id', $reportingManager['id']) ?> ><?php echo $reportingManager['name']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <small class="text-danger error-text" id="reporting_manager_id_error"><?= isset($validation) ? display_error($validation, 'reporting_manager_id') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="joining_date">Joining Date</label>
                                                                <div class="input-group input-group-flatpicker" id="joining_date_picker" data-wrap="true">
                                                                    <input type="text" id="joining_date" class="form-control form-control-sm" name="joining_date" placeholder="Joining Date" value="<?= set_value('joining_date') ?>" data-input data-open >
                                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                                        <i class="far fa-calendar-alt"></i>
                                                                    </span>
                                                                </div>
                                                                <small class="text-danger error-text" id="joining_date_error"><?= isset($validation) ? display_error($validation, 'joining_date') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="notice_period">Notice Period</label>

                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" id="notice_period" class="form-control" name="notice_period" placeholder="Notice Period" value="<?= set_value('notice_period') ?>" data-inputmask="'mask': '9', 'repeat': 3, 'greedy' : false" oninput="$('#notice_period_error').html('')" />
                                                                    <span class="input-group-text">Days</span>
                                                                </div>

                                                                <small class="text-danger error-text" id="notice_period_error"><?= isset($validation) ? display_error($validation, 'notice_period') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="date_of_leaving">Date of leaving / Retirement</label>
                                                                <div class="input-group input-group-flatpicker" id="date_of_leaving_picker" data-wrap="true">
                                                                    <input type="text" id="date_of_leaving" class="form-control form-control-sm" name="date_of_leaving" placeholder="Date of leaving" value="<?= set_value('date_of_leaving') ?>" data-input data-open >
                                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                                        <i class="far fa-calendar-alt"></i>
                                                                    </span>
                                                                </div>
                                                                <small class="text-danger error-text" id="date_of_leaving_error"><?= isset($validation) ? display_error($validation, 'date_of_leaving') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="role">Employment Status</label>
                                                                <span class="form-control form-control-sm">Active</span>
                                                                <small class="text-muted">This value is fixed</small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="status">Probation Status</label>
                                                                <select class="form-control form-control-sm" id="probation" name="probation" data-control="select2" data-placeholder="Select Probation" data-allow-clear="true">
                                                                    <option></option>
                                                                    <option value="45 Days Probation" <?= set_select('probation', '45 Days Probation') ?> >45 Days Probation</option>
                                                                    <option value="90 Days Probation" <?= set_select('probation', '90 Days Probation') ?> >90 Days Probation</option>
                                                                    <option value="confirmed" <?= set_select('probation', 'confirmed') ?> >Confirmed</option>
                                                                </select>
                                                                <small class="text-danger error-text" id="probation_error"><?= isset($validation) ? display_error($validation, 'probation') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="role">Role</label>
                                                                <select class="form-control form-control-sm" id="role" name="role" data-control="select2" data-placeholder="Select a Role" data-allow-clear="true">
                                                                    <option></option>
                                                                    <?php
                                                                    if( isset($roles) && !empty($roles) ){
                                                                        foreach( $roles as $roles_row){
                                                                            ?>
                                                                            <option
                                                                            <?php
                                                                            if( $roles_row['role_name'] == 'superuser' && session()->get('current_user')['employee_id'] != 40 ){
                                                                                echo 'disabled';
                                                                            }
                                                                            ?>
                                                                            value="<?php echo $roles_row['role_name']; ?>" <?= set_select('role', $roles_row['role_name']) ?> ><?php echo ucfirst($roles_row['role_name']); ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <small class="text-danger error-text" id="role_error"><?= isset($validation) ? display_error($validation, 'role') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="shift_id">Shift</label>
                                                                <select class="form-control form-control-sm" id="shift_id" name="shift_id" data-control="select2" data-placeholder="Select a Shift" data-allow-clear="true">
                                                                    <option></option>
                                                                    <?php
                                                                    if( isset($shifts) && !empty($shifts) ){
                                                                        foreach( $shifts as $shift_row){
                                                                            ?>
                                                                            <option value="<?php echo $shift_row['id']; ?>" <?= set_select('shift_id', $shift_row['id']) ?> ><?php echo $shift_row['shift_name']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <small class="text-danger error-text" id="shift_id_error"><?= isset($validation) ? display_error($validation, 'shift_id') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="machine">Machine</label>
                                                                <select class="form-control form-control-sm" id="machine" name="machine" data-control="select2" data-placeholder="Select a Machine" data-allow-clear="true">
                                                                    <option></option>
                                                                    <option value="del" <?= set_select('machine', 'del') ?> >Delhi</option>
                                                                    <option value="ggn" <?= set_select('machine', 'ggn') ?> >Gurugram</option>
                                                                    <option value="hn" <?= set_select('machine', 'hn') ?> >Hueuer Noida</option>
                                                                    <option value="skbd" <?= set_select('machine', 'skbd') ?> >Sikandrabad</option>
                                                                </select>
                                                                <small class="text-danger error-text" id="machine_error"><?= isset($validation) ? display_error($validation, 'machine') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="min_wages_category">Min Wages Category</label>
                                                                <select class="form-control form-control-sm" id="min_wages_category" name="min_wages_category" data-control="select2" data-placeholder="Select an option" data-allow-clear="true">
                                                                    <option></option>
                                                                    <?php
                                                                    if( isset($MinWagesCategories) && !empty($MinWagesCategories) ){
                                                                        foreach( $MinWagesCategories as $MinWagesCategory){
                                                                            ?>
                                                                            <option value="<?php echo $MinWagesCategory['id']; ?>" <?= set_select('min_wages_category', $MinWagesCategory['id']) ?> >
                                                                                <?php echo $MinWagesCategory['minimum_wages_category_name']; ?> (<?php echo $MinWagesCategory['minimum_wages_category_state']; ?>)
                                                                            </option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <small class="text-danger error-text" id="min_wages_category_error"><?= isset($validation) ? display_error($validation, 'min_wages_category') : '' ?></small>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header accordion-header border-top" data-bs-toggle="collapse" data-bs-target="#personal_information">
                                                <div class="card-title">
                                                    Personal Details
                                                </div>
                                                <span class="accordion-icon">
                                                    <span class="svg-icon svg-icon-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                            <div id="personal_information" class="collapse show">
                                                <div class="card-body">
                                                    <div class="row">

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="fathers_name">Father's Name</label>
                                                                <input type="text" id="fathers_name" class="form-control form-control-sm" name="fathers_name" placeholder="Father's Name" value="<?= set_value('fathers_name') ?>" required/>
                                                                <small class="text-danger error-text" id="fathers_name_error"><?= isset($validation) ? display_error($validation, 'fathers_name') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="gender">Gender</label>
                                                                <select class="form-control form-control-sm" id="gender" name="gender" data-control="select2" data-placeholder="Select Gender" data-allow-clear="true">
                                                                    <option></option>
                                                                    <option value="female" <?= set_select('gender', 'female') ?> >Female</option>
                                                                    <option value="male" <?= set_select('gender', 'male') ?> >Male</option>
                                                                    <option value="other" <?= set_select('gender', 'other') ?> >Other</option>
                                                                </select>
                                                                <small class="text-danger error-text" id="gender_error"><?= isset($validation) ? display_error($validation, 'gender') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="marital_status">Marital Status</label>
                                                                <select class="form-control form-control-sm" id="marital_status" name="marital_status" data-control="select2" data-placeholder="Select Marital Status" data-allow-clear="true">
                                                                    <option></option>
                                                                    <option value="married" <?= set_select('marital_status', 'married') ?> >Married</option>
                                                                    <option value="unmarried" <?= set_select('marital_status', 'unmarried') ?> >Un-Married</option>
                                                                    <option value="divorced" <?= set_select('marital_status', 'divorced') ?> >Divorced</option>
                                                                </select>
                                                                <small class="text-danger error-text" id="marital_status_error"><?= isset($validation) ? display_error($validation, 'marital_status') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 husband-name-wrapper" style="display: <?php echo (@$gender == 'female' && $marital_status == 'married' ) ? 'block' : 'none'; ?>">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="husband_name">Husband's Name</label>
                                                                <input type="text" id="husband_name" class="form-control form-control-sm" name="husband_name" placeholder="Husband's Name" value="<?= set_value('husband_name') ?>" required/>
                                                                <small class="text-danger error-text" id="husband_name_error"><?= isset($validation) ? display_error($validation, 'husband_name') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="date_of_anniversary">Date Of Anniversary</label>
                                                                <div class="input-group input-group-flatpicker" id="date_of_anniversary_picker" data-wrap="true">
                                                                    <input type="text" id="date_of_anniversary" class="form-control form-control-sm" name="date_of_anniversary" placeholder="Date Of Anniversary" value="<?= set_value('date_of_anniversary') ?>" data-input data-open >
                                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                                        <i class="far fa-calendar-alt"></i>
                                                                    </span>
                                                                </div>
                                                                <small class="text-danger error-text" id="date_of_anniversary_error"><?= isset($validation) ? display_error($validation, 'date_of_anniversary') : '' ?></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="personal_email">Personal Email</label>
                                                                <input type="email" id="personal_email" class="form-control form-control-sm" name="personal_email" placeholder="Personal Email" value="<?= set_value('personal_email') ?>" />
                                                                <small class="text-danger error-text" id="personal_email_error"><?= isset($validation) ? display_error($validation, 'personal_email') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="personal_mobile">Personal Mobile</label>
                                                                <input type="text" id="personal_mobile" class="form-control form-control-sm" name="personal_mobile" placeholder="Personal Mobile" value="<?= set_value('personal_mobile') ?>" />
                                                                <small class="text-danger error-text" id="personal_mobile_error"><?= isset($validation) ? display_error($validation, 'personal_mobile') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="date_of_birth">Date Of Birth</label>
                                                                <div class="input-group input-group-flatpicker" id="date_of_birth_picker" data-wrap="true">
                                                                    <input type="text" id="date_of_birth" class="form-control form-control-sm" name="date_of_birth" placeholder="Date Of Birth" value="<?= set_value('date_of_birth') ?>" data-input data-open required>
                                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                                        <i class="far fa-calendar-alt"></i>
                                                                    </span>
                                                                </div>
                                                                <small class="text-danger error-text" id="date_of_birth_error"><?= isset($validation) ? display_error($validation, 'date_of_birth') : '' ?></small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="separator separator-content border-dark my-10"><span class="w-250px fw-bold">Permanent Address</span></div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="permanent_city">Permanent City</label>
                                                                <input type="text" id="permanent_city" class="form-control form-control-sm" name="permanent_city" placeholder="Permanent City" value="<?= set_value('permanent_city') ?>" />
                                                                <small class="text-danger error-text" id="permanent_city_error"><?= isset($validation) ? display_error($validation, 'permanent_city') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="permanent_district">Permanent District</label>
                                                                <input type="text" id="permanent_district" class="form-control form-control-sm" name="permanent_district" placeholder="Permanent District" value="<?= set_value('permanent_district') ?>" />
                                                                <small class="text-danger error-text" id="permanent_district_error"><?= isset($validation) ? display_error($validation, 'permanent_district') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="permanent_state">Permanent State</label>
                                                                <input type="text" id="permanent_state" class="form-control form-control-sm" name="permanent_state" placeholder="Permanent State" value="<?= set_value('permanent_state') ?>" />
                                                                <small class="text-danger error-text" id="permanent_state_error"><?= isset($validation) ? display_error($validation, 'permanent_state') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="permanent_pincode">Permanent Pincode</label>
                                                                <input type="text" id="permanent_pincode" class="form-control form-control-sm" name="permanent_pincode" placeholder="Permanent Pincode" value="<?= set_value('permanent_pincode') ?>" />
                                                                <small class="text-danger error-text" id="permanent_pincode_error"><?= isset($validation) ? display_error($validation, 'permanent_pincode') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="permanent_address">Permanent Address</label>
                                                                <textarea id="permanent_address" class="form-control form-control-sm" name="permanent_address" placeholder="Permanent Address"><?= set_value('permanent_address') ?></textarea>
                                                                <small class="text-danger error-text" id="permanent_address_error"><?= isset($validation) ? display_error($validation, 'permanent_address') : '' ?></small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="separator separator-content border-dark my-10"><span class="w-250px fw-bold">Present Address</span></div>

                                                    <div class="form-check form-check-custom form-check-success form-check-sm mt-1">
                                                        <input class="form-check-input" type="checkbox" value="" id="same_as_permanent" />
                                                        <small class="ms-2">Same as Permanent</small>
                                                    </div>
                                                    <br>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="present_city">Present City</label>
                                                                <input type="text" id="present_city" class="form-control form-control-sm" name="present_city" placeholder="Present City" value="<?= set_value('present_city') ?>" />
                                                                <small class="text-danger error-text" id="present_city_error"><?= isset($validation) ? display_error($validation, 'present_city') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="present_district">Present District</label>
                                                                <input type="text" id="present_district" class="form-control form-control-sm" name="present_district" placeholder="Present District" value="<?= set_value('present_district') ?>" />
                                                                <small class="text-danger error-text" id="present_district_error"><?= isset($validation) ? display_error($validation, 'present_district') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="present_state">Present State</label>
                                                                <input type="text" id="present_state" class="form-control form-control-sm" name="present_state" placeholder="Present State" value="<?= set_value('present_state') ?>" />
                                                                <small class="text-danger error-text" id="present_state_error"><?= isset($validation) ? display_error($validation, 'present_state') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="present_pincode">Present Pincode</label>
                                                                <input type="text" id="present_pincode" class="form-control form-control-sm" name="present_pincode" placeholder="Present Pincode" value="<?= set_value('present_pincode') ?>" />
                                                                <small class="text-danger error-text" id="present_pincode_error"><?= isset($validation) ? display_error($validation, 'present_pincode') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="required" for="present_address">Present Address</label>
                                                                <textarea id="present_address" class="form-control form-control-sm" name="present_address" placeholder="Present Address"><?= set_value('present_address') ?></textarea>
                                                                <small class="text-danger error-text" id="present_address_error"><?= isset($validation) ? display_error($validation, 'present_address') : '' ?></small>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-lg-12 my-5 pb-3 border-bottom ">
                                                            <h3>Family Details</h3>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12 mb-3">
                                                            <div id="family_members">
                                                                <div class="form-group">
                                                                    <div data-repeater-list="family_members">
                                                                        <div data-repeater-item>
                                                                            <div class="form-group row mb-5">
                                                                                <div class="col-md-4">
                                                                                    <label class="form-label">Family Member Name</label>
                                                                                    <input type="text" class="form-control form-control-sm form-control-solid" name="member_name" placeholder="Family Member Name" />
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label class="form-label">Relation</label>
                                                                                    <select class="form-select form-select-sm form-select-solid" name="member_relation">
                                                                                        <option value="">Select Relation</option>
                                                                                        <option value="Mother">Mother</option>
                                                                                        <option value="Father">Father</option>
                                                                                        <option value="Daughter">Daughter</option>
                                                                                        <option value="Son">Son</option>
                                                                                        <option value="Wife">Wife</option>
                                                                                        <option value="Husband">Husband</option>
                                                                                        <option value="Sister">Sister</option>
                                                                                        <option value="Brother">Brother</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label class="form-label">DOB</label>
                                                                                    <div class="input-group flatpicker-repeater" id="date_of_birth_picker" data-wrap="true">
                                                                                        <input type="text" class="form-control form-control-sm " name="member_dob" placeholder="Date Of Birth" data-input data-open >
                                                                                        <span class="input-group-text cursor-pointer" data-toggle>
                                                                                            <i class="far fa-calendar-alt"></i>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <label class="form-label">&nbsp;</label><br>
                                                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                                                        <i class="la la-trash-o"></i>Delete
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                                                        <i class="la la-plus"></i>Add Row
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header accordion-header border-top" data-bs-toggle="collapse" data-bs-target="#leave_information">
                                                <div class="card-title">
                                                    Leave Details
                                                </div>
                                                <span class="accordion-icon">
                                                    <span class="svg-icon svg-icon-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                            <div id="leave_information" class="collapse show">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center" style="gap:1rem">
                                                                <label for="cl_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer;">
                                                                    <input type="checkbox" class="form-check" name="cl_allowed" id="cl_allowed" value="yes" <?php echo set_checkbox('cl_allowed', 'yes'); ?> >
                                                                    <span >CL</span>
                                                                </label>
                                                                <label for="el_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                                    <input type="checkbox" class="form-check" name="el_allowed" id="el_allowed" value="yes" <?php echo set_checkbox('el_allowed', 'yes'); ?> >
                                                                    <span >EL</span>
                                                                </label>
                                                                <label for="co_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                                    <input type="checkbox" class="form-check" name="co_allowed" id="co_allowed" value="yes" <?php echo set_checkbox('co_allowed', 'yes'); ?> >
                                                                    <span >Comp Off</span>
                                                                </label>
                                                                <label for="sl_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                                    <input type="checkbox" class="form-check" name="sl_allowed" id="sl_allowed" value="yes" <?php echo set_checkbox('sl_allowed', 'yes'); ?> >
                                                                    <span >Sick Leave</span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="accordion accordion-icon-toggle rounded border">

                                        <div class="card">
                                            <div class="card-header accordion-header" data-bs-toggle="collapse" data-bs-target="#contact_details">
                                                <div class="card-title">
                                                    <i class="flaticon2-layers-1"></i> Contact Details
                                                </div>
                                                <span class="accordion-icon">
                                                    <span class="svg-icon svg-icon-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                            <div id="contact_details" class="collapse show">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="work_email">Work Email</label>
                                                                <input type="email" id="work_email" class="form-control form-control-sm" name="work_email" placeholder="Work Email" value="<?= set_value('work_email') ?>" />
                                                                <small class="text-danger error-text" id="work_email_error"><?= isset($validation) ? display_error($validation, 'work_email') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="work_mobile">Work Mobile</label>
                                                                <input type="text" id="work_mobile" class="form-control form-control-sm" name="work_mobile" placeholder="Work Mobile" value="<?= set_value('work_mobile') ?>" />
                                                                <small class="text-danger error-text" id="work_mobile_error"><?= isset($validation) ? display_error($validation, 'work_mobile') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="work_phone_extension_number">Work Phone Extension Number</label>
                                                                <input type="text" id="work_phone_extension_number" class="form-control form-control-sm" name="work_phone_extension_number" placeholder="Work Phone Extension Number" value="<?= set_value('work_phone_extension_number') ?>" />
                                                                <small class="text-danger error-text" id="work_phone_extension_number_error"><?= isset($validation) ? display_error($validation, 'work_phone_extension_number') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="work_phone_cug_number">Work Phone CUG Number</label>
                                                                <input type="text" id="work_phone_cug_number" class="form-control form-control-sm" name="work_phone_cug_number" placeholder="Work Phone CUG Number" value="<?= set_value('work_phone_cug_number') ?>" />
                                                                <small class="text-danger error-text" id="work_phone_cug_number_error"><?= isset($validation) ? display_error($validation, 'work_phone_cug_number') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="desk_location">Desk Location</label>
                                                                <input type="text" id="desk_location" class="form-control form-control-sm" name="desk_location" placeholder="Desk Location" value="<?= set_value('desk_location') ?>" />
                                                                <small class="text-danger error-text" id="desk_location_error"><?= isset($validation) ? display_error($validation, 'desk_location') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="emergency_contact_number">Emergency Contact</label>
                                                                <input type="text" id="emergency_contact_number" class="form-control form-control-sm" name="emergency_contact_number" placeholder="Emergency Contact" value="<?= set_value('emergency_contact_number') ?>" />
                                                                <small class="text-danger error-text" id="emergency_contact_number_error"><?= isset($validation) ? display_error($validation, 'emergency_contact_number') : '' ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header accordion-header border-top" data-bs-toggle="collapse" data-bs-target="#professional_details">
                                                <div class="card-title">
                                                    <i class="flaticon2-layers-1"></i> Professional Details
                                                </div>
                                                <span class="accordion-icon">
                                                    <span class="svg-icon svg-icon-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                            <div id="professional_details" class="collapse show">
                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="highest_qualification">Highest Qualification</label>
                                                                <input type="text" id="highest_qualification" class="form-control form-control-sm" name="highest_qualification" placeholder="Highest Qualification" value="<?= set_value('highest_qualification') ?>" />
                                                                <small class="text-danger error-text" id="highest_qualification_error"><?= isset($validation) ? display_error($validation, 'highest_qualification') : '' ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label for="total_experience">Total Experience</label>
                                                                <input type="text" id="total_experience" class="form-control form-control-sm" name="total_experience" placeholder="Total Experience" value="<?= set_value('total_experience') ?>" />
                                                                <small class="text-danger error-text" id="total_experience_error"><?= isset($validation) ? display_error($validation, 'total_experience') : '' ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header accordion-header border-top" data-bs-toggle="collapse" data-bs-target="#documents_upload">
                                                <div class="card-title">
                                                    <i class="flaticon2-layers-1"></i> Documents Upload
                                                </div>
                                                <span class="accordion-icon">
                                                    <span class="svg-icon svg-icon-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                            <div id="documents_upload" class="collapse show">
                                                <div class="card-body">

                                                    <div class="row">

                                                        <div class="col-md-6 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="avatar_number">Photo</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="avatar_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#avatar_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="avatar_attachment" name="avatar_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="avatar_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="avatar_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="avatar_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="avatar_attachment_error"><?= isset($validation) ? display_error($validation, 'avatar_attachment') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="pan_card_number">Pan Card</label>
                                                                        <input type="text" id="pan_card_number" class="form-control form-control-sm" name="pan_card_number" placeholder="Pan Card Number" value="<?= set_value('pan_card_number', @$attachment['pan']['number']) ?>" />
                                                                        <small class="text-danger error-text" id="pan_card_number_error"><?= isset($validation) ? display_error($validation, 'pan_card_number') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="pan_card_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#pan_card_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="pan_card_attachment" name="pan_card_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="pan_card_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="pan_card_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="pan_card_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="pan_card_attachment_error"><?= isset($validation) ? display_error($validation, 'pan_card_attachment') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="bank_name">Bank Name</label>
                                                                        <input type="text" id="bank_name" class="form-control form-control-sm" name="bank_name" placeholder="Bank Name" value="<?= set_value('bank_name', @$attachment['bank_account']['name']) ?>" />
                                                                        <small class="text-danger error-text" id="bank_name_error"><?= isset($validation) ? display_error($validation, 'bank_name') : '' ?></small>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="bank_account_number">Bank Account</label>
                                                                        <input type="text" id="bank_account_number" class="form-control form-control-sm" name="bank_account_number" placeholder="Bank Account Number" value="<?= set_value('bank_account_number', @$attachment['bank_account']['number']) ?>" />
                                                                        <small class="text-danger error-text" id="bank_account_number_error"><?= isset($validation) ? display_error($validation, 'bank_account_number') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="bank_account_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay  preview-button" data-bs-target="#bank_account_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="bank_account_attachment" name="bank_account_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="bank_account_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="bank_account_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="bank_account_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="bank_account_attachment_error"><?= isset($validation) ? display_error($validation, 'bank_account_attachment') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mb-4">
                                                            <div class="row me-md-n1 pt-5 mb-3 bg-light rounded">
                                                                <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="passport_number">Passport</label>
                                                                        <input type="text" id="passport_number" class="form-control form-control-sm" name="passport_number" placeholder="Passport Number" value="<?= set_value('passport_number', @$attachment['passport']['number']) ?>" />
                                                                        <small class="text-danger error-text" id="passport_number_error"><?= isset($validation) ? display_error($validation, 'passport_number') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="passport_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#passport_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="passport_attachment" name="passport_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="passport_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="passport_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="passport_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="passport_attachment_error"><?= isset($validation) ? display_error($validation, 'passport_attachment') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="adhar_card_number">Adhar Card</label>
                                                                        <input type="text" id="adhar_card_number" class="form-control form-control-sm" name="adhar_card_number" placeholder="Adhar Card Number" value="<?= set_value('adhar_card_number', @$attachment['adhar']['number']) ?>" />
                                                                        <small class="text-danger error-text" id="adhar_card_number_error"><?= isset($validation) ? display_error($validation, 'adhar_card_number') : '' ?></small>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="adhar_card_attachment_front_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#adhar_card_attachment_front_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="adhar_card_attachment_front" name="adhar_card_attachment_front" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="adhar_card_attachment_front_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="adhar_card_attachment_front_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="adhar_card_attachment_front_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="adhar_card_attachment_front_error"><?= isset($validation) ? display_error($validation, 'adhar_card_attachment_front') : '' ?></small>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="adhar_card_attachment_back_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-75px h-75px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#adhar_card_attachment_back_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="adhar_card_attachment_back" name="adhar_card_attachment_back" accept=".png, .jpg, .jpeg, .pdf" />
                                                                                <input type="hidden" name="adhar_card_attachment_back_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="adhar_card_attachment_back_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="adhar_card_attachment_back_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="adhar_card_attachment_back_error"><?= isset($validation) ? display_error($validation, 'adhar_card_attachment_back') : '' ?></small>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="kye_documents_remarks">K. Y. E. Documents</label>
                                                                        <br><small style="font-size: 0.7rem">Know your employee</small><br><small style="font-size: 0.7rem">or Onboarding Document (Appointment letter, Offer letter etc)</small>
                                                                        <textarea id="kye_documents_remarks" class="form-control form-control-sm" name="kye_documents_remarks" placeholder="Additional Information"><?= set_value('kye_documents_remarks', @$attachment['kye_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="kye_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'kye_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="kye_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#kye_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="kye_documents_attachment" name="kye_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="kye_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="kye_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="kye_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="kye_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'kye_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="family_details_remarks">Family details</label>
                                                                        <textarea id="family_details_remarks" class="form-control form-control-sm" name="family_details_remarks" placeholder="Additional Information"><?= set_value('family_details_remarks', @$attachment['family_details']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="family_details_remarks_error"><?= isset($validation) ? display_error($validation, 'family_details_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="family_details_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#family_details_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="family_details_attachment" name="family_details_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="family_details_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="family_details_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="family_details_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="family_details_attachment_error"><?= isset($validation) ? display_error($validation, 'family_details_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="loan_documents_remarks">Loan Documents</label>
                                                                        <textarea id="loan_documents_remarks" class="form-control form-control-sm" name="loan_documents_remarks" placeholder="Additional Information"><?= set_value('loan_documents_remarks', @$attachment['loan_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="loan_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'loan_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="loan_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#loan_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="loan_documents_attachment" name="loan_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="loan_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="loan_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="loan_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="loan_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'loan_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="educational_documents_remarks">Educational documents</label>
                                                                        <textarea id="educational_documents_remarks" class="form-control form-control-sm" name="educational_documents_remarks" placeholder="Additional Information"><?= set_value('educational_documents_remarks', @$attachment['educational_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="educational_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'educational_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="educational_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#educational_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="educational_documents_attachment" name="educational_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="educational_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="educational_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="educational_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="educational_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'educational_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="relieving_documents_remarks">Relieving documents</label>
                                                                        <textarea id="relieving_documents_remarks" class="form-control form-control-sm" name="relieving_documents_remarks" placeholder="Additional Information"><?= set_value('relieving_documents_remarks', @$attachment['relieving_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="relieving_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'relieving_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="relieving_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#relieving_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="relieving_documents_attachment" name="relieving_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="relieving_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="relieving_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="relieving_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="relieving_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'relieving_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row me-md-n1 pt-5 bg-light rounded">
                                                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="misc_documents_remarks">Misc documents</label>
                                                                        <textarea id="misc_documents_remarks" class="form-control form-control-sm" name="misc_documents_remarks" placeholder="Additional Information"><?= set_value('misc_documents_remarks', @$attachment['misc_documents']['remarks']) ?></textarea>
                                                                        <small class="text-danger error-text" id="misc_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'misc_documents_remarks') : '' ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3" style="width: max-content;">
                                                                        <div id="misc_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#misc_documents_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="misc_documents_attachment" name="misc_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="misc_documents_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="misc_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body" style="min-height: 70vh;">
                                                                                            <iframe id="misc_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="misc_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'misc_documents_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mb-4">

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-3">
                                                                        <label class=" form-label">PDC Cheques</label>
                                                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                                                            <label class="form-check-label me-3" for="enable_pdc">
                                                                                No
                                                                            </label>
                                                                            <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="enable_pdc" name="enable_pdc" >
                                                                            <label class="form-check-label" for="enable_pdc">
                                                                                Yes
                                                                            </label>
                                                                        </div>
                                                                        <small class="text-danger error-text" id="enable_pdc_error"></small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row me-md-n1 pt-5 bg-light rounded" id="pdc_container" style="display: none;">
                                                                <div class="col-md-8 d-flex flex-column justify-content-center">
                                                                    <div class="form-group mb-3">
                                                                        <label class="mb-3" for="pdc_cheque_numbers">PDC Cheque Numbers</label>
                                                                        <div>
                                                                            <div class="input-group input-group-sm mb-3">
                                                                                <span class="input-group-text">Bank name</span>
                                                                                <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_1" name="pdc_bank_name_1" value="<?= set_value('pdc_bank_name_1', @$attachment['pdc_cheque']['bank_name_1']) ?>">
                                                                                <span class="input-group-text">Cheque Number</span>
                                                                                <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_1" name="pdc_cheque_number_1" value="<?= set_value('pdc_cheque_number_1', @$attachment['pdc_cheque']['cheque_number_1']) ?>">
                                                                            </div>
                                                                            <small class="text-danger error-text" id="pdc_bank_name_1_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_1') : '' ?></small>
                                                                            <small class="text-danger error-text" id="pdc_cheque_number_1_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_1') : '' ?></small>
                                                                        </div>
                                                                        <div>
                                                                            <div class="input-group input-group-sm mb-3">
                                                                                <span class="input-group-text">Bank name</span>
                                                                                <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_2" name="pdc_bank_name_2" value="<?= set_value('pdc_bank_name_2', @$attachment['pdc_cheque']['bank_name_2']) ?>">
                                                                                <span class="input-group-text">Cheque Number</span>
                                                                                <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_2" name="pdc_cheque_number_2" value="<?= set_value('pdc_cheque_number_2', @$attachment['pdc_cheque']['cheque_number_2']) ?>">
                                                                            </div>
                                                                            <small class="text-danger error-text" id="pdc_bank_name_2_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_2') : '' ?></small>
                                                                            <small class="text-danger error-text" id="pdc_cheque_number_2_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_2') : '' ?></small>
                                                                        </div>
                                                                        <div>
                                                                            <div class="input-group input-group-sm mb-3">
                                                                                <span class="input-group-text">Bank name</span>
                                                                                <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_3" name="pdc_bank_name_3" value="<?= set_value('pdc_bank_name_3', @$attachment['pdc_cheque']['bank_name_3']) ?>">
                                                                                <span class="input-group-text">Cheque Number</span>
                                                                                <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_3" name="pdc_cheque_number_3" value="<?= set_value('pdc_cheque_number_3', @$attachment['pdc_cheque']['cheque_number_3']) ?>">
                                                                            </div>
                                                                            <small class="text-danger error-text" id="pdc_bank_name_3_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_3') : '' ?></small>
                                                                            <small class="text-danger error-text" id="pdc_cheque_number_3_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_3') : '' ?></small>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group mb-3" style="width: max-content;">

                                                                        <div id="pdc_cheque_documents_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                            <div class="image-input-wrapper w-150px h-150px" >
                                                                                <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#pdc_cheque_attachment_lightbox" data-bs-toggle="modal">
                                                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                                </a>
                                                                            </div>
                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" id="pdc_cheque_attachment" name="pdc_cheque_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                                <input type="hidden" name="pdc_cheque_attachment_remove" />
                                                                            </label>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                                <i class="bi bi-x fs-2"></i>
                                                                            </span>
                                                                            <div class="modal fade" id="pdc_cheque_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                            <iframe id="pdc_cheque_attachment_lightbox_iframe" class="loaded_content" width="100%"   src=""></iframe>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <small class="text-danger error-text" id="pdc_cheque_attachment_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_attachment') : '' ?></small>
                                                                    </div>
                                                                    <small class="text-muted" style="font-size:0.75rem">
                                                                        JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                                        No Preview will be available for zip files
                                                                    </small>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body border-top">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="submit">&nbsp;</label>
                                                            <button type="submit" id="submit_add_employee" class="form-control btn btn-sm btn-primary d-inline">
                                                                <span class="indicator-label">Submit</span>
                                                                <span class="indicator-progress">
                                                                    Please wait...
                                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </form> -->
                    <?php
                // }
                ?>
            </div>
    </div>

    <?= $this->section('javascript') ?>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('.input-group-flatpicker').flatpickr({
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'Y-m-d',
                altInputClass: "form-control form-control-sm"
            })

            /*begin::Show validation error message*/
            var response = "<?php echo session()->getFlashdata('error'); ?>";
            if( response.length ){
                Swal.fire({
                    html: response,
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: { confirmButton: "btn btn-primary" },
                })
            }
            /*end::Show validation error message*/

            $(document).on('input', '.form-control', function(){
                // $(this).parent().find('.error-text').html('');
                var nameAttr = $(this).attr('name');
                $("#"+nameAttr+"_error").html('');
            });

            $(document).on('change', '#company_id', function(e){
                var company_id = $('#company_id').val();
                var data = {
                    'company_id'        :   company_id,
                };
                //load departments
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/get-department-by-company-id'); ?>",
                    data: data,
                    success: function(response){
                        console.log(response);
                        if( response.response_type == 'error' ){
                            $('#department_id').html('<option></option>');
                            if( company_id !== '' ){
                                $('#department_id_error').html(response.response_description);
                            }
                        }

                        if( response.response_type == 'success' ){
                            if( typeof response.response_data.departments != 'undefined' ){
                                $('#department_id').html('<option></option>');
                                var department_data = response.response_data.departments;
                                $.each(department_data, function(index, department){
                                    $('#department_id').append('<option value="'+department.id+'">'+department.department_name+' - '+department.company_short_name+'</option>');
                                });
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed while loading departments conditionally, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    }
                });
                //load Reporting managers
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/get-reporting-managers-by-company-id'); ?>",
                    data: data,
                    success: function(response){
                        console.log(response);
                        if( response.response_type == 'error' ){
                            $('#reporting_manager_id').html('<option></option>');
                            if( company_id !== '' ){
                                $('#reporting_manager_id_error').html(response.response_description);
                            }
                        }

                        if( response.response_type == 'success' ){
                            if( typeof response.response_data.reportingManagers != 'undefined' ){
                                $('#reporting_manager_id').html('<option></option>');
                                var reportingManagers_data = response.response_data.reportingManagers;
                                $.each(reportingManagers_data, function(index, reportingManager){
                                    $('#reporting_manager_id').append('<option value="'+reportingManager.id+'">'+reportingManager.name+' - '+reportingManager.department_name+' - '+reportingManager.company_short_name+'</option>');
                                });
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed while loading Reporting Managers conditionally, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    }
                });
            });

            $(document).on('change', '#gender, #marital_status', function(e){
                var gender = $("#gender").val();
                var marital_status = $("#marital_status").val();
                if( gender == 'female' && marital_status == 'married' ){
                    $('.husband-name-wrapper').show();
                    $('.husband-name-wrapper input').removeAttr("disabled");
                }else{
                    $('.husband-name-wrapper').hide();
                    $('.husband-name-wrapper input').attr("disabled");
                }
            })


            /*$(document).on('click', '#submit_add_employee', function(e){
                e.preventDefault();
                var form = $('#add_employee');
                var submitButton = $(this);
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                var data = new FormData(form[0]);
                Swal.fire({
                    html: "Do you want to create login credentials for this employee?",
                    icon: "question",
                    buttonsStyling: !1,
                    confirmButtonText: "Yes",
                    customClass: { confirmButton: "btn btn-primary", cancelButton: "btn btn-secondary" },
                    showCancelButton: true,
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        data.append('create_login_credentials', 'yes');
                        addNewEmployee(data);
                    }else{
                        addNewEmployee(data);
                    }
                })
            })*/


            $(document).on('submit', '#add_employee', function(e){
                e.preventDefault();
                var form = $(this);
                var submitButton = $("#submit_add_employee");
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                var data = new FormData(form[0]);
                data.append('create_login_credentials', 'yes');

                Swal.fire({
                    html: "To avoid problems after adding an employee, We are creating login credentials by default",
                    icon: "info",
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: { confirmButton: "btn btn-primary" },
                    showCancelButton: false,
                }).then((result) => {
                    $.ajax({
                        method: "post",
                        url: "<?php echo base_url('/ajax/master/employee/add-new/validate'); ?>",
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response){
                            console.log(response);
                            if( typeof response.returned_id != 'undefined' ){
                                var nextLocation = "<?php echo base_url(); ?>backend/master/employee/edit/id/"+response.returned_id;
                            }else{
                                var nextLocation = "<?php echo base_url(); ?>backend/master/employee";
                            }
                            // submitButton.removeAttr("data-kt-indicator");
                            // submitButton.removeAttr("disabled");
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
                                            var listOfErrors = '<p class="text-start text-danger">';
                                            $.each(validation, function(index, value){
                                                $(form).find('#'+index+'_error').html(value);
                                                // $('form#add_employee #'+index+'_error').html(value);
                                                listOfErrors += '<small>'+value+'</small><br>';
                                            });
                                            listOfErrors += '</p>';
                                            Swal.fire({
                                                title: `List of Errors`,
                                                html: listOfErrors,
                                                icon: "error",
                                                buttonsStyling: !1,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: { confirmButton: "btn btn-primary" },
                                            })
                                        }
                                        submitButton.removeAttr("data-kt-indicator");
                                        submitButton.removeAttr("disabled");
                                    });
                                }
                            }

                            if( response.response_type == 'success' ){
                                if( response.response_description.length ){
                                    var can_update_salary = '<?php echo isset($can_update_salary) && !empty($can_update_salary) ? "yes" : "no"; ?>';
                                    var employee_updated_response = response.response_description;

                                    if( can_update_salary === 'yes' ){

                                        console.log('response', response);
                                        // return false;
                                        data.append('employee_id', response.returned_id);
                                        $.ajax({
                                            method: "post",
                                            url: "<?php echo base_url('/ajax/master/salary/validate'); ?>",
                                            data: data,
                                            processData: false,
                                            contentType: false,
                                            success: function(response){
                                                console.log(response);
                                                if( response.response_type == 'error' ){
                                                    if( response.response_description.length ){
                                                        Swal.fire({
                                                            // html: response.response_description,
                                                            html: `${employee_updated_response}<br><small class="text-danger">But there was an error while updating salary,<br>Don't worry you can still update salary on edit page</small>`,
                                                            icon: "error",
                                                            buttonsStyling: !1,
                                                            confirmButtonText: "Ok, got it!",
                                                            customClass: { confirmButton: "btn btn-primary" },
                                                        }).then(function (e) {
                                                            // if( typeof response.response_data.validation != 'undefined' ){
                                                            //     var validation = response.response_data.validation;
                                                            //     $.each(validation, function(index, value){
                                                            //         form.find('#'+index+'_error').html(value);
                                                            //     });
                                                            // }
                                                            window.location.replace(nextLocation);
                                                        });
                                                    }
                                                }

                                                if( response.response_type == 'success' ){
                                                    if( response.response_description.length ){
                                                        Swal.fire({
                                                            html: `${employee_updated_response}<br><small class="text-success">${response.response_description}</small>`,
                                                            icon: "success",
                                                            buttonsStyling: !1,
                                                            confirmButtonText: "Ok, got it!",
                                                            customClass: { confirmButton: "btn btn-primary" },
                                                        }).then(function(e){
                                                            window.location.replace(nextLocation);
                                                        });
                                                    }
                                                }
                                            },
                                            failed: function(){
                                                Swal.fire({
                                                    html: `${employee_updated_response}<br><small class="text-danger">But there was an error while updating salary,<br>Don't worry you can still update salary on edit page</small>`,
                                                    icon: "error",
                                                    buttonsStyling: !1,
                                                    confirmButtonText: "Ok, got it!",
                                                    customClass: { confirmButton: "btn btn-primary" },
                                                }).then(function(e){
                                                    window.location.replace(nextLocation);
                                                });
                                                submitButton.removeAttr("data-kt-indicator");
                                                submitButton.removeAttr("disabled");
                                            }
                                        })

                                    }else{
                                        Swal.fire({
                                            html: `${response.response_description}<br><small class="text-danger">Salary didn't updated, you can still update salary on edit page</small>`,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" },
                                        }).then(function(e){
                                            window.location.replace(nextLocation);
                                        });
                                    }
                                }
                            }
                        },
                        failed: function(){
                            Swal.fire({
                                html: "Error while adding new Employee.",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                            })
                            submitButton.removeAttr("data-kt-indicator");
                            submitButton.removeAttr("disabled");
                        }
                    });
                })
            })

            function addNewEmployee(data) {
                /*for (var pair of data.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
                return false;*/
                // var form = $('#add_employee');
                // var submitButton = $('#submit_add_employee');

            }

            $('.image-input').each(function(){
                var id = $(this).attr('id');
                var imageInputElement = document.querySelector("#"+id);
                var imageInput = KTImageInput.getInstance(imageInputElement);

                var iframe_src_backup = '';

                imageInput.on("kt.imageinput.changed", function() {
                    var fileInput = $("#"+id).find("input[type=file]")[0];
                    var imageInputWrapper = $("#"+id+" .image-input-wrapper");
                    var lightboxIframe = $("#"+id).find("iframe");
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var extension = fileInput.files[0].name.split('.').pop().toLowerCase();
                        switch (extension) {
                            case 'pdf':
                                lightboxIframe.attr('src', e.target.result);
                                imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                                imageInputWrapper.css({'background-image' : 'url(<?php echo base_url(); ?>assets/media/svg/files/pdf.svg)'});
                                break;
                            case 'zip':
                                imageInputWrapper.css({'background-image' : 'url(<?php echo base_url(); ?>assets/media/svg/files/zip-file-icon.svg)'});
                                imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                                break;
                            default:
                                lightboxIframe.attr('src', e.target.result);
                                imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                                break;
                        }
                    }
                    reader.readAsDataURL(fileInput.files[0]);
                });

                imageInput.on("kt.imageinput.change", function() {
                    var lightboxIframe = $("#"+id).find("iframe");
                    if( iframe_src_backup == '' ){
                        iframe_src_backup = lightboxIframe.attr('src');
                    }
                });

                imageInput.on("kt.imageinput.canceled", function() {
                    var lightboxIframe = $("#"+id).find("iframe");
                    if(iframe_src_backup !== ''){
                        lightboxIframe.attr('src', iframe_src_backup);
                    }else{
                        var imageInputWrapper = $("#"+id+" .image-input-wrapper");
                        imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                        lightboxIframe.attr('src', '');
                    }
                });

                imageInput.on("kt.imageinput.removed", function() {
                    var imageInputWrapper = $("#"+id+" .image-input-wrapper");
                    imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                    var lightboxIframe = $("#"+id).find("iframe");
                    lightboxIframe.attr('src', '');
                });
            });

            var $family_members = $('form#add_employee #family_members').repeater({
                initEmpty: true,
                show: function () {
                    $(this).slideDown();
                    $(this).find('.flatpicker-repeater').flatpickr({
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'Y-m-d',
                        altInputClass: "form-control form-control-sm"
                    })
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                ready: function(){
                    // $(this).find('[data-kt-repeater="select2"]').select2();
                    Inputmask({
                        regex: "([01]?[0-9]|2[0-3]):[0-5][0-9]",
                    }).mask($(this).find('[data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]"]'));
                    $(this).find('.flatpicker-repeater').flatpickr({
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'Y-m-d',
                        altInputClass: "form-control form-control-sm"
                    })
                }
            });

            $(document).on('change', '#same_as_permanent', function(e){
                e.preventDefault();
                if( $(this).prop('checked') == true ){
                    $("#present_city").val( $("#permanent_city").val() );
                    $("#present_district").val( $("#permanent_district").val() );
                    $("#present_state").val( $("#permanent_state").val() );
                    $("#present_pincode").val( $("#permanent_pincode").val() );
                    $("#present_address").val( $("#permanent_address").val() );
                    /*$("#present_address").html( $("#permanent_address").val() );*/
                }else{
                    $("#present_city").val('');
                    $("#present_district").val('');
                    $("#present_state").val('');
                    $("#present_pincode").val('');
                    $("#present_address").val('');
                }
            })

            $(document).on('change', '#same_as_permanent_city', function(e){
                e.preventDefault();
                if( $(this).prop('checked') == true ){
                    $("#present_city").val( $("#permanent_city").val() );
                }else{
                    $("#present_city").val('');
                }
            })

            $(document).on('change', '#same_as_permanent_district', function(e){
                e.preventDefault();
                if( $(this).prop('checked') == true ){
                    $("#present_district").val( $("#permanent_district").val() );
                }else{
                    $("#present_district").val('');
                }
            })

            $(document).on('change', '#same_as_permanent_state', function(e){
                e.preventDefault();
                if( $(this).prop('checked') == true ){
                    $("#present_state").val( $("#permanent_state").val() );
                }else{
                    $("#present_state").val('');
                }
            })

            $(document).on('change', '#same_as_permanent_pincode', function(e){
                e.preventDefault();
                if( $(this).prop('checked') == true ){
                    $("#present_pincode").val( $("#permanent_pincode").val() );
                }else{
                    $("#present_pincode").val('');
                }
            })

            $(document).on('change', '#same_as_permanent_address', function(e){
                e.preventDefault();
                if( $(this).prop('checked') == true ){
                    $("#present_address").val( $("#permanent_address").val() );
                }else{
                    $("#present_address").val('');
                }
            });

            $(document).on('change', '#add_employee input#enable_pdc', function(e){
                if( $(this).is(':checked') ){
                    $('#add_employee #pdc_container').slideDown();
                }else{
                    $('#add_employee #pdc_container').slideUp();
                }
            });

            $(document).on('change', 'input#pf', function(e){
                if( $(this).is(':checked') ){
                    $('#pf_number_container').slideDown();
                    $('#pf_employee_contribution_container').slideDown();
                    $('#pf_employer_contribution_container').slideDown();
                }else{
                    $('#pf_number_container').slideUp();
                    $('#pf_employee_contribution_container').slideUp();
                    $('#pf_employer_contribution_container').slideUp();
                }
            });

            $(document).on('change', 'input#esi', function(e){
                if( $(this).is(':checked') ){
                    $('#esi_number_container').slideDown();
                    $('#esi_employee_contribution_container').slideDown();
                    $('#esi_employer_contribution_container').slideDown();
                }else{
                    $('#esi_number_container').slideUp();
                    $('#esi_employee_contribution_container').slideUp();
                    $('#esi_employer_contribution_container').slideUp();
                }
            });

            $(document).on('change', 'input#lwf', function(e){
                if( $(this).is(':checked') ){
                    $('#lwf_employee_contribution_container').slideDown();
                    $('#lwf_employer_contribution_container').slideDown();
                    $('#lwf_deduction_on_every_n_month_container').slideDown();
                }else{
                    $('#lwf_employee_contribution_container').slideUp();
                    $('#lwf_employer_contribution_container').slideUp();
                    $('#lwf_deduction_on_every_n_month_container').slideUp();
                }
            });

            $(document).on('change', 'input#enable_bonus', function(e){
                if( $(this).is(':checked') ){
                    $('#bonus_container').slideDown();
                }else{
                    $('#bonus_container').slideUp();
                }
            });

            $(document).on('change', 'input#non_compete_loan', function(e){
                if( $(this).is(':checked') ){
                    $('#non_compete_loan_from_container').slideDown();
                    $('#non_compete_loan_to_container').slideDown();
                    $('#non_compete_loan_amount_per_month_container').slideDown();
                    $('#non_compete_loan_remarks_container').slideDown();
                }else{
                    $('#non_compete_loan_from_container').slideUp();
                    $('#non_compete_loan_to_container').slideUp();
                    $('#non_compete_loan_amount_per_month_container').slideUp();
                    $('#non_compete_loan_remarks_container').slideUp();
                }
            });

            $(document).on('change', 'input#loyalty_incentive', function(e){
                if( $(this).is(':checked') ){
                    $('#loyalty_incentive_from_container').slideDown();
                    $('#loyalty_incentive_to_container').slideDown();
                    $('#loyalty_incentive_amount_per_month_container').slideDown();
                    $('#loyalty_incentive_mature_after_month_container').slideDown();
                    $('#loyalty_incentive_pay_after_month_container').slideDown();
                    $('#loyalty_incentive_remarks_container').slideDown();
                }else{
                    $('#loyalty_incentive_from_container').slideUp();
                    $('#loyalty_incentive_to_container').slideUp();
                    $('#loyalty_incentive_amount_per_month_container').slideUp();
                    $('#loyalty_incentive_mature_after_month_container').slideUp();
                    $('#loyalty_incentive_pay_after_month_container').slideUp();
                    $('#loyalty_incentive_remarks_container').slideUp();
                }
            });

        })
    </script>

    <?= $this->endSection() ?>
<?= $this->endSection() ?>