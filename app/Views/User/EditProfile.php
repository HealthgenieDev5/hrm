<?= $this->extend('Templates/DashboardLayout') ?>
<?= $this->section('content') ?>

<style>
    .input-group.flatpicker-repeater>input[type=text] {
        border-top-left-radius: 0.475rem !important;
        border-bottom-left-radius: 0.475rem !important;
    }
</style>

<!--begin::Post-->
<form class="post d-flex flex-column-fluid" id="update_employee" method="post" enctype="multipart/form-data" action="<?php #echo base_url('/master/employee/edit/validate'); 
                                                                                                                        ?>">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Navbar-->
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap mb-3">
                    <!--begin: Pic-->
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative border border-4 ">
                            <img src="<?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? base_url() . $attachment['avatar']['file'] : base_url() . '/assets/media/svg/files/blank-image.svg'; ?>" alt="avatar" />
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-white h-20px w-20px"></div>
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Info-->
                    <div class="flex-grow-1 me-7">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1"><?php echo @$first_name . ' ' . @$last_name; ?></a>
                                    <a href="#">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen026.svg-->
                                        <span class="svg-icon svg-icon-1 svg-icon-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                                <path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="#00A3FF" />
                                                <path class="permanent" d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </a>
                                </div>
                                <!--end::Name-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    <?php
                                    if (!empty($designation_name)) {
                                    ?>
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <span class="svg-icon svg-icon-4 me-3">
                                                <i class="fa-duotone fa-circle-user text-hover-primary"></i>
                                            </span>
                                            <?php echo $designation_name; ?>
                                        </a>
                                    <?php
                                    }
                                    if (!empty($department_name)) {
                                    ?>
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <span class="svg-icon svg-icon-4 me-3">
                                                <i class="fa-solid fa-building-user text-hover-primary"></i>
                                            </span>
                                            <?php echo $department_name; ?>
                                        </a>
                                    <?php
                                    }
                                    if (!empty($company_short_name)) {
                                    ?>
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <span class="svg-icon svg-icon-4 me-3">
                                                <i class="fa-solid fa-house-building text-hover-primary"></i>
                                            </span>
                                            <?php echo $company_short_name; ?>
                                        </a>
                                    <?php
                                    }
                                    if (!empty($desk_location)) {
                                    ?>
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <span class="svg-icon svg-icon-4 me-3">
                                                <i class="fa-duotone fa-location-dot text-hover-primary"></i>
                                            </span>
                                            <?php echo $desk_location; ?>
                                        </a>
                                    <?php
                                    }
                                    if (!empty($work_email)) {
                                    ?>
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <span class="svg-icon svg-icon-4 me-3">
                                                <i class="fa-duotone fa-envelope text-hover-primary"></i>
                                            </span>
                                            <?php echo $work_email; ?>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap">
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="fw-bold fs-6 text-gray-400 me-5">Employee Code</div>
                                            <div class="fs-2 fw-bolder"><?php echo @$internal_employee_id; ?></div>
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                    <ul class="list-group list-group-flush min-w-250px">
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="fw-bold fs-6 text-gray-400 me-5">Reporting Manager</span>
                            <span class="fw-bold fs-6 text-gray-800"><?php echo !empty($reporting_manager_name) ? $reporting_manager_name : '--'; ?></span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="fw-bold fs-6 text-gray-400 me-5">HOD</span>
                            <span class="fw-bold fs-6 text-gray-800"><?php echo !empty($hod_name) ? $hod_name : '--'; ?></span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="fw-bold fs-6 text-gray-400 me-5">Joining Date</span>
                            <span class="fw-bold fs-6 text-gray-800"><?php echo !empty($joining_date) ? date('d M Y', strtotime($joining_date)) : '--'; ?></span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="fw-bold fs-6 text-gray-400 me-5">Notice Period</span>
                            <span class="fw-bold fs-6 text-gray-800"><?php echo !empty($notice_period) ? $notice_period . ' days' : '--'; ?></span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="fw-bold fs-6 text-gray-400 me-5">Shift Name</span>
                            <span class="fw-bold fs-6 text-gray-800"><?php echo !empty($shift_name) ? $shift_name : '--'; ?></span>
                        </li>
                    </ul>
                </div>
                <!--end::Details-->
                <!--begin::Navs-->
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#Basic_Details">Basic</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Personal_Details">Personal</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="family_details_trigger nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Family_Details">Family</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Contact_Details">Contact</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Professional_Details">Professional</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Documents_Upload">Documents</a>
                    </li>
                    <!--end::Nav item-->
                </ul>
                <!--begin::Navs-->
            </div>
        </div>
        <!--end::Navbar-->
        <div class="card mb-5 mb-xl-10">
            <div class="card-body border-top p-9">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="Basic_Details" role="tabpanel">
                        <!--begin::Basic info-->
                        <div class="card shadow-none">
                            <!--begin::Card header-->
                            <div class="card-header px-0 pt-0">
                                <!--begin::Card title-->
                                <div class="card-title m-0">
                                    <h3 class="fw-bolder m-0">Basic Details</h3>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--begin::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body border-top px-0 pb-0">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Full Name</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-6 fv-row">
                                                <input type="text" id="first_name" name="first_name" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="First name" value="<?php echo @$first_name; ?>" />
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6 fv-row">
                                                <input type="text" id="last_name" name="last_name" class="form-control form-control-sm " placeholder="Last name" value="<?php echo @$last_name; ?>" />
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Company</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?php echo !empty($company_name) ? $company_name : '--'; ?></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Department</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?php echo !empty($department_name) ? $department_name : '--'; ?></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Designation</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?php echo !empty($designation_name) ? $designation_name : '--'; ?></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Reporting Manager</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?php echo !empty($reporting_manager_name) ? $reporting_manager_name : '--'; ?></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Joining Date</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?php echo !empty($joining_date) ? date('d M Y', strtotime($joining_date)) : '--'; ?></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Notice Period</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?php echo !empty($notice_period) ? $notice_period . ' days' : '--'; ?></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Shift Name</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?php echo !empty($shift_name) ? $shift_name : '--'; ?></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Basic info-->
                    </div>
                    <div class="tab-pane fade" id="Personal_Details" role="tabpanel">
                        <!--begin::Basic info-->
                        <div class="card shadow-none">
                            <!--begin::Card header-->
                            <div class="card-header px-0 pt-0">
                                <!--begin::Card title-->
                                <div class="card-title m-0">
                                    <h3 class="fw-bolder m-0">Personal Details</h3>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--begin::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body border-top px-0 pb-0">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Father's Name</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="fathers_name" class="form-control form-control-sm" name="fathers_name" placeholder="Father's Name" value="<?= set_value('fathers_name', @$fathers_name) ?>" required />
                                        <small class="text-danger error-text" id="fathers_name_error"><?= isset($validation) ? display_error($validation, 'fathers_name') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Gender</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <select class="form-control form-control-sm mb-3 mb-lg-0" id="gender" name="gender" data-control="select2" data-placeholder="Select Gender" data-allow-clear="true">
                                            <option></option>
                                            <option value="female" <?= edit_set_select('gender', 'female', $gender) ?>>Female</option>
                                            <option value="male" <?= edit_set_select('gender', 'male', $gender) ?>>Male</option>
                                            <option value="other" <?= edit_set_select('gender', 'other', $gender) ?>>Other</option>
                                        </select>
                                        <small class="text-danger error-text" id="gender_error"><?= isset($validation) ? display_error($validation, 'gender') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Marital Status</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <select class="form-control form-control-sm mb-3 mb-lg-0" id="marital_status" name="marital_status" data-control="select2" data-placeholder="Select Marital Status" data-allow-clear="true">
                                            <option></option>
                                            <option value="married" <?= edit_set_select('marital_status', 'married', $marital_status) ?>>Married</option>
                                            <option value="unmarried" <?= edit_set_select('marital_status', 'unmarried', $marital_status) ?>>Un-Married</option>
                                            <option value="divorced" <?= edit_set_select('marital_status', 'divorced', $marital_status) ?>>Divorced</option>
                                        </select>
                                        <small class="text-danger error-text" id="marital_status_error"><?= isset($validation) ? display_error($validation, 'marital_status') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="husband-name-wrapper" style="display: <?php echo ($gender == 'female' && $marital_status == 'married') ? 'block' : 'none'; ?>">
                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Husband's Name</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 fv-row">
                                            <input type="text" id="husband_name" class="form-control form-control-sm" name="husband_name" placeholder="Husband's Name" value="<?= set_value('husband_name', @$husband_name) ?>" required />
                                            <small class="text-danger error-text" id="fathers_name_error"><?= isset($validation) ? display_error($validation, 'husband_name') : '' ?></small>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                                <!--end::Input group-->


                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Date Of Anniversary</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <div class="input-group input-group-flatpicker" id="date_of_anniversary_picker" data-wrap="true">
                                            <input type="text" id="date_of_anniversary" class="form-control form-control-sm" name="date_of_anniversary" placeholder="Date Of Anniversary" value="<?= set_value('date_of_anniversary', @$date_of_anniversary) ?>" data-input data-open>
                                            <span class="input-group-text cursor-pointer" data-toggle>
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <small class="text-danger error-text" id="date_of_anniversary_error"><?= isset($validation) ? display_error($validation, 'date_of_anniversary') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Personal Email</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="email" id="personal_email" class="form-control form-control-sm" name="personal_email" placeholder="Personal Email" value="<?= set_value('personal_email', @$personal_email) ?>" />
                                        <small class="text-danger error-text" id="personal_email_error"><?= isset($validation) ? display_error($validation, 'personal_email') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Personal Mobile</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="personal_mobile" class="form-control form-control-sm" name="personal_mobile" placeholder="Personal Mobile" value="<?= set_value('personal_mobile', @$personal_mobile) ?>" />
                                        <small class="text-danger error-text" id="personal_mobile_error"><?= isset($validation) ? display_error($validation, 'personal_mobile') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Date Of Birth</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <div class="input-group input-group-flatpicker" id="date_of_birth_picker" data-wrap="true">
                                            <input type="text" id="date_of_birth" class="form-control form-control-sm" name="date_of_birth" placeholder="Date Of Birth" value="<?= set_value('date_of_birth', @$date_of_birth) ?>" data-input data-open>
                                            <span class="input-group-text cursor-pointer" data-toggle>
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <small class="text-danger error-text" id="date_of_birth_error"><?= isset($validation) ? display_error($validation, 'date_of_birth') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Permanent City</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="permanent_city" class="form-control form-control-sm" name="permanent_city" placeholder="Permanent City" value="<?= set_value('permanent_city', @$permanent_city) ?>" />
                                        <small class="text-danger error-text" id="permanent_city_error"><?= isset($validation) ? display_error($validation, 'permanent_city') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Permanent District</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="permanent_district" class="form-control form-control-sm" name="permanent_district" placeholder="Permanent District" value="<?= set_value('permanent_district', @$permanent_district) ?>" />
                                        <small class="text-danger error-text" id="permanent_district_error"><?= isset($validation) ? display_error($validation, 'permanent_district') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Permanent State</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="permanent_state" class="form-control form-control-sm" name="permanent_state" placeholder="Permanent State" value="<?= set_value('permanent_state', @$permanent_state) ?>" />
                                        <small class="text-danger error-text" id="permanent_state_error"><?= isset($validation) ? display_error($validation, 'permanent_state') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Permanent Pincode</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="permanent_pincode" class="form-control form-control-sm" name="permanent_pincode" placeholder="Permanent Pincode" value="<?= set_value('permanent_pincode', @$permanent_pincode) ?>" />
                                        <small class="text-danger error-text" id="permanent_pincode_error"><?= isset($validation) ? display_error($validation, 'permanent_pincode') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Permanent Address</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <textarea id="permanent_address" class="form-control form-control-sm" name="permanent_address" placeholder="Permanent Address"><?= set_value('permanent_address', @$permanent_address) ?></textarea>
                                        <small class="text-danger error-text" id="permanent_address_error"><?= isset($validation) ? display_error($validation, 'permanent_address') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Present City</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="present_city" class="form-control form-control-sm" name="present_city" placeholder="Present City" value="<?= set_value('present_city', @$present_city) ?>" />
                                        <small class="text-danger error-text" id="present_city_error"><?= isset($validation) ? display_error($validation, 'present_city') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Present District</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="present_district" class="form-control form-control-sm" name="present_district" placeholder="Present District" value="<?= set_value('present_district', @$present_district) ?>" />
                                        <small class="text-danger error-text" id="present_district_error"><?= isset($validation) ? display_error($validation, 'present_district') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Present State</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="present_state" class="form-control form-control-sm" name="present_state" placeholder="Present State" value="<?= set_value('present_state', @$present_state) ?>" />
                                        <small class="text-danger error-text" id="present_state_error"><?= isset($validation) ? display_error($validation, 'present_state') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Present Pincode</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" id="present_pincode" class="form-control form-control-sm" name="present_pincode" placeholder="Present Pincode" value="<?= set_value('present_pincode', @$present_pincode) ?>" />
                                        <small class="text-danger error-text" id="present_pincode_error"><?= isset($validation) ? display_error($validation, 'present_pincode') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm fw-bold fs-6">Present Address</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <textarea id="present_address" class="form-control form-control-sm" name="present_address" placeholder="Present Address"><?= set_value('present_address', @$present_address) ?></textarea>
                                        <small class="text-danger error-text" id="present_address_error"><?= isset($validation) ? display_error($validation, 'present_address') : '' ?></small>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Basic info-->
                    </div>
                    <div class="tab-pane fade" id="Family_Details" role="tabpanel">
                        <!--begin::Basic info-->
                        <div class="card shadow-none">
                            <!--begin::Card header-->
                            <div class="card-header px-0 pt-0">
                                <!--begin::Card title-->
                                <div class="card-title m-0">
                                    <h3 class="fw-bolder m-0">Family Details</h3>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--begin::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body border-top px-0 pb-0">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row">
                                        <!--begin::Repeater-->
                                        <div id="family_members">
                                            <!-- <pre><?php #print_r($family_members); 
                                                        ?></pre> -->
                                            <!--begin::Form group-->
                                            <div class="form-group">
                                                <div data-repeater-list="family_members">
                                                    <div data-repeater-item>
                                                        <div class="form-group row mb-5">
                                                            <div class="col-md-3">
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
                                                                    <input type="text" class="form-control form-control-sm" name="member_dob" placeholder="Date Of Birth" data-input data-open>
                                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                                        <i class="far fa-calendar-alt"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Age</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control form-control-sm form-control-age" name="member_age" placeholder="29" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                                                    <span class="input-group-text">Years</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label class="form-label">&nbsp;</label><br>
                                                                <div class="d-flex align-items-center justify-content-end">
                                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                                        <i class="la la-trash-o"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Form group-->
                                            <!--begin::Form group-->
                                            <div class="form-group">
                                                <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                                    <i class="la la-plus"></i>Add a family member
                                                </a>
                                            </div>
                                            <!--end::Form group-->
                                        </div>
                                        <!--end::Repeater-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Basic info-->
                    </div>
                    <div class="tab-pane fade" id="Contact_Details" role="tabpanel">
                        <!--begin::Basic info-->
                        <div class="card shadow-none">
                            <!--begin::Card header-->
                            <div class="card-header px-0 pt-0">
                                <!--begin::Card title-->
                                <div class="card-title m-0">
                                    <h3 class="fw-bolder m-0">Contact Details</h3>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--begin::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body border-top px-0 pb-0">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Work Email</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$work_email ?></span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Work Mobile</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$work_mobile ?></span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Work Phone Extension Number</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" id="work_phone_extension_number" class="form-control form-control-sm" name="work_phone_extension_number" placeholder="Work Phone Extension Number" value="<?= set_value('work_phone_extension_number', @$work_phone_extension_number) ?>" />
                                                <small class="text-danger error-text" id="work_phone_extension_number_error"><?= isset($validation) ? display_error($validation, 'work_phone_extension_number') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Work Phone CUG Number</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$work_phone_cug_number ?></span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Desk Location</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" id="desk_location" class="form-control form-control-sm" name="desk_location" placeholder="Desk Location" value="<?= set_value('desk_location', @$desk_location) ?>" />
                                                <small class="text-danger error-text" id="desk_location_error"><?= isset($validation) ? display_error($validation, 'desk_location') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Emergency Contact</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$emergency_contact_number ?></span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Basic info-->
                    </div>
                    <div class="tab-pane fade" id="Professional_Details" role="tabpanel">
                        <!--begin::Basic info-->
                        <div class="card shadow-none">
                            <!--begin::Card header-->
                            <div class="card-header px-0 pt-0">
                                <!--begin::Card title-->
                                <div class="card-title m-0">
                                    <h3 class="fw-bolder m-0">Professional Details</h3>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--begin::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body border-top px-0 pb-0">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Highest Qualification</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" id="highest_qualification" class="form-control form-control-sm" name="highest_qualification" placeholder="Highest Qualification" value="<?= set_value('highest_qualification', @$highest_qualification) ?>" />
                                                <small class="text-danger error-text" id="highest_qualification_error"><?= isset($validation) ? display_error($validation, 'highest_qualification') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Total Experience</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" id="total_experience" class="form-control form-control-sm" name="total_experience" placeholder="Total Experience" value="<?= set_value('total_experience', @$total_experience) ?>" />
                                                <small class="text-danger error-text" id="total_experience_error"><?= isset($validation) ? display_error($validation, 'total_experience') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Basic info-->
                    </div>
                    <div class="tab-pane fade" id="Documents_Upload" role="tabpanel">
                        <!--begin::Basic info-->
                        <div class="card shadow-none">
                            <!--begin::Card header-->
                            <div class="card-header px-0 pt-0">
                                <!--begin::Card title-->
                                <div class="card-title m-0">
                                    <h3 class="fw-bolder m-0">Documents Upload</h3>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--begin::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body border-top px-0 pb-0">
                                <!--begin::Input group-->
                                <div class="row align-items-center mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Profile Photo</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row d-flex align-items-center justify-content-center">
                                                <div class="row me-md-n1 pt-5 bg-light rounded w-100">
                                                    <div class="col-12 col-xl-6 d-flex flex-column justify-content-center">

                                                    </div>

                                                    <div class="col-12 col-xl-6">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <div id="avatar_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-100px h-100px" <?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? (pathinfo($attachment['avatar']['file'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['avatar']['file'] . '\')"' : ''; ?>>
                                                                    <a href="#" class="<?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" data-bs-target="#avatar_attachment_lightbox" data-bs-toggle="modal">
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
                                                                                <iframe id="avatar_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src="<?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? base_url() . $attachment['avatar']['file'] : ''; ?>"></iframe>
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
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row align-items-center mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Adhar Card</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row d-flex align-items-center justify-content-center">
                                                <div class="row me-md-n1 pt-5 bg-light rounded w-100">
                                                    <div class="col-12 col-xl-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="adhar_card_number">Adhar Card Number</label>
                                                            <div class="fv-row">
                                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$attachment['adhar']['number'] ?></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-6 col-xl-3">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <label class="mb-3">Front</label><br>
                                                            <div id="adhar_card_attachment_front_select" class="image-input image-input-outline <?php echo (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-100px h-100px" <?php echo (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) ? (pathinfo($attachment['adhar']['front'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['adhar']['front'] . '\')"' : ''; ?>>
                                                                    <a href="<?php echo (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) ? base_url() . $attachment['adhar']['front'] : ''; ?>" class="<?php echo (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" target="_blank">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-download text-white fs-2x"></i>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="adhar_card_attachment_front_error"><?= isset($validation) ? display_error($validation, 'adhar_card_attachment_front') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-6 col-xl-3">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <label class="mb-3">Back</label><br>
                                                            <div id="adhar_card_attachment_back_select" class="image-input image-input-outline <?php echo (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-100px h-100px" <?php echo (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) ? (pathinfo($attachment['adhar']['back'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['adhar']['back'] . '\')"' : ''; ?>>
                                                                    <a href="<?php echo (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) ? base_url() . $attachment['adhar']['back'] : ''; ?>" class="<?php echo (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" target="_blank">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-download text-white fs-2x"></i>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="adhar_card_attachment_back_error"><?= isset($validation) ? display_error($validation, 'adhar_card_attachment_back') : '' ?></small>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row align-items-center mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Pan Card</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row d-flex align-items-center justify-content-center">
                                                <div class="row me-md-n1 pt-5 bg-light rounded w-100">
                                                    <div class="col-12 col-xl-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="pan_card_number">Pan Card Number</label>
                                                            <div class="fv-row">
                                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$attachment['pan']['number'] ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-xl-6">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <label class="mb-3">Front</label><br>
                                                            <div id="pan_card_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-100px h-100px" <?php echo (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) ? (pathinfo($attachment['pan']['file'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['pan']['file'] . '\')"' : ''; ?>>
                                                                    <a href="<?php echo (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) ? base_url() . $attachment['pan']['file'] : ''; ?>" class="<?php echo (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" target="_blank">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-download text-white fs-2x"></i>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="pan_card_attachment_error"><?= isset($validation) ? display_error($validation, 'pan_card_attachment') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row align-items-center mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Passport</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row d-flex align-items-center justify-content-center">
                                                <div class="row me-md-n1 pt-5 bg-light rounded w-100">
                                                    <div class="col-12 col-xl-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="passport_number">Passport</label>
                                                            <div class="fv-row">
                                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$attachment['passport']['number'] ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-xl-6">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <label class="mb-3">Front</label><br>
                                                            <div id="passport_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-100px h-100px" <?php echo (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) ? (pathinfo($attachment['passport']['file'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['passport']['file'] . '\')"' : ''; ?>>
                                                                    <a href="<?php echo (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) ? base_url() . $attachment['passport']['file'] : ''; ?>" class="<?php echo (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" target="_blank">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-download text-white fs-2x"></i>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="passport_attachment_error"><?= isset($validation) ? display_error($validation, 'passport_attachment') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row align-items-center mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label col-form-label-sm required fw-bold fs-6">Bank Details</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row d-flex align-items-center justify-content-center">
                                                <div class="row me-md-n1 pt-5 bg-light rounded w-100">
                                                    <div class="col-12 col-xl-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="bank_name">Bank Name</label>
                                                            <div class="fv-row">
                                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$attachment['bank_account']['name'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="bank_account_number">Bank Account</label>
                                                            <div class="fv-row">
                                                                <span class="fw-bold fs-6 text-gray-400 p-3 border-bottom"><?= @$attachment['bank_account']['number'] ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-xl-6">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <label class="mb-3">Passbook</label><br>
                                                            <div id="bank_account_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-100px h-100px" <?php echo (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) ? (pathinfo($attachment['bank_account']['file'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['bank_account']['file'] . '\')"' : ''; ?>>
                                                                    <a href="<?php echo (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) ? base_url() . $attachment['bank_account']['file'] : ''; ?>" class="<?php echo (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" target="_blank">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-download text-white fs-2x"></i>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="bank_account_attachment_error"><?= isset($validation) ? display_error($validation, 'bank_account_attachment') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Basic info-->
                    </div>
                </div>
            </div>
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $id; ?>" required />
                <button type="reset" class="btn btn-secondary btn-active-light-secondary me-2">Discard</button>
                <button type="submit" id="submit_update_employee" class="btn btn-primary d-inline">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">
                        Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
            <!--end::Actions-->
        </div>

    </div>
    <!--end::Container-->
</form>
<!--end::Post-->

<?= $this->section('javascript') ?>

<script src="<?php echo base_url(); ?>assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.input-group-flatpicker').flatpickr({
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd M Y',
            altInputClass: "form-control form-control-sm"
        })

        /*begin::Show validation error message*/
        var response = "<?php echo session()->getFlashdata('fail'); ?>";
        if (response.length) {
            Swal.fire({
                html: response,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
            })
        }
        /*end::Show validation error message*/


        $(document).on('input', '.form-control', function() {
            $(this).parent().find('.error-text').html('');
        })


        $(document).on('click', '#submit_update_employee', function(e) {
            e.preventDefault();
            var form = $('#update_employee');
            var submitButton = $(this);
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/user/profile/update'); ?>",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);

                    submitButton.removeAttr("data-kt-indicator");
                    submitButton.removeAttr("disabled");
                    if (response.response_type == 'error') {
                        if (response.response_description.length) {
                            Swal.fire({
                                html: response.response_description,
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                            }).then(function(e) {
                                if (typeof response.response_data.validation != 'undefined') {
                                    var validation = response.response_data.validation;
                                    $.each(validation, function(index, value) {
                                        form.find('#' + index + '_error').html(value);
                                    });
                                }
                            });
                        }
                    }

                    if (response.response_type == 'success') {
                        if (response.response_description.length) {
                            Swal.fire({
                                html: response.response_description + '<p><strong class="text-info">Please reload this page to take effect</strong><br><span>Do you want to reload?</span></p>',
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: 'Yes, reload',
                                customClass: {
                                    confirmButton: "btn btn-sm btn-primary",
                                    cancelButton: "btn btn-sm btn-secondary"
                                },
                            }).then(
                                (result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                }
                            );
                        }
                    }
                },
                failed: function() {
                    Swal.fire({
                        html: "Ajax Failed, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                    submitButton.removeAttr("data-kt-indicator");
                    submitButton.removeAttr("disabled");
                }
            })
        })

        $('.image-input').each(function() {
            var id = $(this).attr('id');
            var imageInputElement = document.querySelector("#" + id);
            var imageInput = KTImageInput.getInstance(imageInputElement);

            var iframe_src_backup = '';

            imageInput.on("kt.imageinput.changed", function() {
                var fileInput = $("#" + id).find("input[type=file]")[0];
                var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                var lightboxIframe = $("#" + id).find("iframe");
                var reader = new FileReader();
                reader.onload = function(e) {
                    lightboxIframe.attr('src', e.target.result);
                    imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                    var extension = fileInput.files[0].name.split('.').pop().toLowerCase();
                    switch (extension) {
                        case 'pdf':
                            imageInputWrapper.css({
                                'background-image': 'url(<?php echo base_url(); ?>assets/media/svg/files/pdf.svg)'
                            });
                            break;
                        default:
                            break;
                    }
                }
                reader.readAsDataURL(fileInput.files[0]);
            });

            imageInput.on("kt.imageinput.change", function() {
                var lightboxIframe = $("#" + id).find("iframe");
                if (iframe_src_backup == '') {
                    iframe_src_backup = lightboxIframe.attr('src');
                }
            });

            imageInput.on("kt.imageinput.canceled", function() {
                var lightboxIframe = $("#" + id).find("iframe");
                if (iframe_src_backup !== '') {
                    lightboxIframe.attr('src', iframe_src_backup);
                } else {
                    var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                    imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                    lightboxIframe.attr('src', '');
                }
            });

            imageInput.on("kt.imageinput.removed", function() {
                var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                var lightboxIframe = $("#" + id).find("iframe");
                lightboxIframe.attr('src', '');
            });
        });

        $(document).on('change', '#gender, #marital_status', function(e) {
            var gender = $("#gender").val();
            var marital_status = $("#marital_status").val();
            if (gender == 'female' && marital_status == 'married') {
                $('.husband-name-wrapper').show();
            } else {
                $('.husband-name-wrapper').hide();
            }
        });

        $(document).on('change', 'div.flatpicker-repeater#date_of_birth_picker > input.flatpickr-input', function(e) {
            var newAge = _calculateAge($(this).val());
            $(this).closest('.form-group').find('.form-control-age').val(newAge);
        });

        function _calculateAge(dob) {
            if (dob != '') {
                // var str = dob.split('-');    
                // var firstdate = new Date(str[0],str[1],str[2]);
                // var today = new Date();        
                // var dayDiff = Math.ceil(today.getTime() - firstdate.getTime()) / (1000 * 60 * 60 * 24 * 365);
                // var age = parseInt(dayDiff);

                var mdate = dob.toString();
                var yearThen = parseInt(mdate.substring(0, 4), 10);
                var monthThen = parseInt(mdate.substring(5, 7), 10);
                var dayThen = parseInt(mdate.substring(8, 10), 10);
                var today = new Date();
                var birthday = new Date(yearThen, monthThen - 1, dayThen);
                var differenceInMilisecond = today.valueOf() - birthday.valueOf();
                var year_age = Math.floor(differenceInMilisecond / 31536000000);
                var day_age = Math.floor((differenceInMilisecond % 31536000000) / 86400000);
                var month_age = Math.floor(day_age / 30);
                day_age = day_age % 30;
                if (isNaN(year_age) || isNaN(month_age) || isNaN(day_age)) {
                    var age = 0;
                } else {
                    var age = year_age;
                }

            } else {
                var age = 0;
            }
            return age;
        }


        var $family_members = $('form#update_employee #family_members').repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown();
                $(this).find('.flatpicker-repeater').flatpickr({
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'Y-m-d',
                    altInputClass: "form-control form-control-sm"
                })
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {
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

        $family_members.setList(<?php echo $family_members; ?>);

    })
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>