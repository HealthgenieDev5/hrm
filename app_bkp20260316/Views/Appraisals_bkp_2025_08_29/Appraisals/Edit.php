<?= $this->extend('Templates/DashboardLayout') ?>
<?= $this->section('content') ?>
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->

    <div class="col-md-3">


        <!--begin::Card-->
        <div class="card mt-8 mb-5 mb-xl-8">
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Summary-->
                <!--begin::User Info-->
                <div class="d-flex flex-center flex-column py-5">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <img src="<?php echo (isset($selectedEmployee['attachment']['avatar']['file']) && !empty($selectedEmployee['attachment']['avatar']['file'])) ? base_url() . $selectedEmployee['attachment']['avatar']['file'] : base_url() . '/assets/media/svg/files/blank-image.svg'; ?>" alt="image">
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Name-->
                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3"><?php echo @trim($selectedEmployee['first_name'] . " " . $selectedEmployee['last_name']); ?></a>
                    <!--end::Name-->
                </div>
                <!--end::User Info-->
                <!--end::Summary-->
                <div class="separator"></div>
                <!--begin::Details content-->
                <div id="kt_user_view_details" class="collapse show">
                    <div class="pb-5 fs-6">
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Employee Code</div>
                        <div class="text-gray-600"><?= @$selectedEmployee['internal_employee_id'] ?></div>
                        <!--begin::Details item-->
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Company</div>
                        <div class="text-gray-600"><?= @$selectedEmployee['company_name'] ?></div>
                        <!--begin::Details item-->
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Department</div>
                        <div class="text-gray-600"><?= @$selectedEmployee['department_name'] ?></div>
                        <!--begin::Details item-->
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Designation</div>
                        <div class="text-gray-600"><?= @$selectedEmployee['designation_name'] ?></div>
                        <!--begin::Details item-->
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Email</div>
                        <div class="text-gray-600">
                            <a href="#" class="text-gray-600 text-hover-primary"><?= @$selectedEmployee['work_email'] ?></a>
                        </div>
                        <!--begin::Details item-->
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Desk Location</div>
                        <div class="text-gray-600"><?= @$selectedEmployee['desk_location'] ?></div>
                        <!--begin::Details item-->
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Extension</div>
                        <div class="text-gray-600"><?= @$selectedEmployee['work_phone_extension_number'] ?></div>
                        <!--begin::Details item-->
                    </div>
                </div>
                <!--end::Details content-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

        <div class="card mb-5 mb-xl-8">
            <!--begin::Card header-->
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">Important Links</h3>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-2">
                <!-- <a href="#" class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">View Salary</a> -->
                <!--begin::Notice-->
                <a
                    id="salary_drawer_button"
                    href="#"
                    class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">
                    View Salary
                </a>
                <div
                    id="salary_drawer"
                    class="bg-white"
                    data-kt-drawer="true"
                    data-kt-drawer-activate="true"
                    data-kt-drawer-toggle="#salary_drawer_button"
                    data-kt-drawer-close="#salary_drawer_close"
                    data-kt-drawer-width="768px">
                    <div class="card w-100 rounded-0">
                        <div class="card-header pe-5 " style="min-height: unset;">
                            <div class="card-title">
                                <div class="d-flex justify-content-center flex-column me-3">
                                    Salary
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="btn btn-sm btn-icon btn-active-light-primary" id="salary_drawer_close">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body hover-scroll-overlay-y">

                            <ul>
                                <li class="d-flex align-items-center justify-content-between gap-3">
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
                <!--end::Notice-->
            </div>
            <!--end::Card body-->
        </div>

    </div>



    <div class="col-md-9">
        <style>
            .accordion-header.collapsed {
                border-radius: calc(-1px + 0.475rem) !important;
            }
        </style>
        <form id="update_appraisals" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT" />
            <div class="row">
                <div class="col-12">

                    <div class="d-flex flex-row gap-5 flex-wrap mb-5">

                        <div class="card bg-white" style="width:200px; border-radius: 1.5rem;">
                            <!--begin::Body-->
                            <div class="card-body my-3">
                                <a href="#" class="card-title fw-bolder text-primary fs-5 mb-3 d-block">Appraisal amount</a>
                                <div class="py-1">
                                    <span class="text-primary fs-1 fw-bolder me-2" id="appraisal_percentage_new">0%</span>
                                    <span class="fw-bold text-primary opacity-60 fs-7" id="appraisal_amount_new">0</span>
                                </div>
                            </div>
                            <!--end:: Body-->
                        </div>

                        <div class="card bg-white" style="width:200px; border-radius: 1.5rem;">
                            <!--begin::Body-->
                            <div class="card-body my-3">
                                <a href="#" class="card-title fw-bolder text-info fs-5 mb-3 d-block">Gross Salary</a>
                                <div class="py-1">
                                    <span class="fw-bold text-info opacity-50 fs-7" id="gross_salary_old" style="text-decoration: line-through"><?= $salary_totals['gross_salary'] - $appraisal['gross_salary'] ?></span>
                                    <i class="fa fa-arrow-right text-info mx-3"></i>
                                    <span class="fw-bold text-info opacity-60 fs-5" id="gross_salary_new"><?= $salary_totals['gross_salary'] ?></span>
                                </div>
                            </div>
                            <!--end:: Body-->
                        </div>

                        <style>
                            .text-pink {
                                color: var(--bs-pink);
                            }
                        </style>
                        <div class="card bg-white" style="width:200px; border-radius: 1.5rem;">
                            <!--begin::Body-->
                            <div class="card-body my-3">
                                <a href="#" class="card-title fw-bolder text-pink fs-5 mb-3 d-block">CTC</a>
                                <div class="py-1">
                                    <span class="fw-bold text-pink opacity-50 fs-7" id="ctc_old" style="text-decoration: line-through"><?= $salary_totals['ctc'] - $appraisal['ctc'] ?></span>
                                    <i class="fa fa-arrow-right text-pink mx-3"></i>
                                    <span class="fw-bold text-pink opacity-60 fs-5" id="ctc_new"><?= $salary_totals['ctc'] ?></span>
                                </div>
                            </div>
                            <!--end:: Body-->
                        </div>

                    </div>


                    <div class="accordion accordion-icon-toggle rounded border mb-5">
                        <div class="card">
                            <div class="card-header accordion-header bg-white" data-bs-toggle="collapse" data-bs-target="#general_information">
                                <div class="card-title">
                                    Appraisal Form
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
                            <div id="general_information" class="collapse show">
                                <div class="card-body">

                                    <div class="row mt-5">
                                        <div class="col-md-4">
                                            <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0" for="basic_salary">Basic Salary</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" id="basic_salary_old">
                                                        <?= $salary_totals['basic_salary'] - $appraisal['basic_salary'] ?>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="text" id="basic_salary" class="form-control form-control-sm" name="basic_salary" placeholder="00" value="<?= $appraisal['basic_salary'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-equals"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <small class="text-info" id="basic_salary_new"></small>
                                                    </span>
                                                </div>
                                                <input type="hidden" id="employee_id" name="employee_id" placeholder="00" value="<?= $appraisal['employee_id'] ?>" />

                                                <small class="text-danger error-text" id="basic_salary_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0" for="house_rent_allowance">HRA</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" id="house_rent_allowance_old">
                                                        <?= $salary_totals['house_rent_allowance'] - $appraisal['house_rent_allowance'] ?>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="text" id="house_rent_allowance" class="form-control form-control-sm" name="house_rent_allowance" placeholder="00" value="<?= $appraisal['house_rent_allowance'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-equals"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <small class="text-info" id="house_rent_allowance_new"></small>
                                                    </span>
                                                </div>

                                                <small class="text-danger error-text" id="house_rent_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0" for="medical_allowance">Medical Alw</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" id="medical_allowance_old">
                                                        <?= $salary_totals['medical_allowance'] - $appraisal['medical_allowance'] ?>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="text" id="medical_allowance" class="form-control form-control-sm" name="medical_allowance" placeholder="00" value="<?= $appraisal['medical_allowance'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-equals"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <small class="text-info" id="medical_allowance_new"></small>
                                                    </span>
                                                </div>

                                                <small class="text-danger error-text" id="medical_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0" for="conveyance">Conveyance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" id="conveyance_old">
                                                        <?= $salary_totals['conveyance'] - $appraisal['conveyance'] ?>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="text" id="conveyance" class="form-control form-control-sm" name="conveyance" placeholder="00" value="<?= $appraisal['conveyance'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-equals"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <small class="text-info" id="conveyance_new"></small>
                                                    </span>
                                                </div>

                                                <small class="text-danger error-text" id="conveyance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0" for="special_allowance">Special Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" id="special_allowance_old">
                                                        <?= $salary_totals['special_allowance'] - $appraisal['special_allowance'] ?>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="text" id="special_allowance" class="form-control form-control-sm" name="special_allowance" placeholder="00" value="<?= $appraisal['special_allowance'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-equals"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <small class="text-info" id="special_allowance_new"></small>
                                                    </span>
                                                </div>

                                                <small class="text-danger error-text" id="special_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0" for="fuel_allowance">Fuel Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" id="fuel_allowance_old">
                                                        <?= $salary_totals['fuel_allowance'] - $appraisal['fuel_allowance'] ?>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="text" id="fuel_allowance" class="form-control form-control-sm" name="fuel_allowance" placeholder="00" value="<?= $appraisal['fuel_allowance'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-equals"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <small class="text-info" id="fuel_allowance_new"></small>
                                                    </span>
                                                </div>

                                                <small class="text-danger error-text" id="fuel_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0" for="vacation_allowance">Vacation Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" id="vacation_allowance_old">
                                                        <?= $salary_totals['vacation_allowance'] - $appraisal['vacation_allowance'] ?>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="text" id="vacation_allowance" class="form-control form-control-sm" name="vacation_allowance" placeholder="00" value="<?= $appraisal['vacation_allowance'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-equals"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <small class="text-info" id="vacation_allowance_new"></small>
                                                    </span>
                                                </div>

                                                <small class="text-danger error-text" id="vacation_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0" for="other_allowance">Other Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" id="other_allowance_old">
                                                        <?= $salary_totals['other_allowance'] - $appraisal['other_allowance'] ?>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="text" id="other_allowance" class="form-control form-control-sm" name="other_allowance" placeholder="00" value="<?= $appraisal['other_allowance'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-equals"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <small class="text-info" id="other_allowance_new"></small>
                                                    </span>
                                                </div>

                                                <small class="text-danger error-text" id="other_allowance_error"></small>
                                            </div>
                                        </div>

                                        <hr class="my-7 opacity-10">

                                        <div class="d-flex justify-content-between flex-wrap gap-3">

                                            <!-- <div class="form-group mb-5">
                                                <label class="form-label fs-7 mb-0 w-100 text-center">Bonus</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="enable_bonus">
                                                        No
                                                    </label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="enable_bonus" name="enable_bonus" <?= $appraisal['enable_bonus'] == 'yes' ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="enable_bonus">
                                                        Yes
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="separater" style="width: 3px; background: #f0f0f0; height: 100%;"></div> -->
                                            <input type="hidden" id="enable_bonus" name="enable_bonus" value="<?= isset($lastAppraisal) && $lastAppraisal->enable_bonus == 'yes' ? 'yes' : 'no' ?>" />

                                            <div class="form-group mb-3">
                                                <label class="form-label fs-7 mb-0 w-100 text-center">PF</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="pf">No</label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="pf" name="pf" <?= $appraisal['pf'] == 'yes' ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="pf">Yes</label>
                                                </div>
                                            </div>

                                            <div class="separater" style="width: 3px; background: #f0f0f0; height: 100%;"></div>

                                            <div class="form-group mb-3">
                                                <label class="form-label fs-7 mb-0 w-100 text-center">ESI</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="esi">
                                                        No
                                                    </label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="esi" name="esi" <?= $appraisal['esi'] == 'yes' ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="esi">
                                                        Yes
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="separater" style="width: 3px; background: #f0f0f0; height: 100%;"></div>

                                            <div class="form-group mb-3">
                                                <label class="form-label fs-7 mb-0 w-100 text-center">LWF</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="lwf">
                                                        No
                                                    </label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="lwf" name="lwf" <?= $appraisal['lwf'] == 'yes' ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="lwf">
                                                        Yes
                                                    </label>
                                                </div>
                                                <small class="text-danger error-text" id="lwf_error"></small>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="accordion accordion-icon-toggle rounded border mb-5">
                        <div class="card">
                            <div class="card-header accordion-header bg-white" data-bs-toggle="collapse" data-bs-target="#other_benifits">
                                <div class="card-title">
                                    Others Benefits
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
                            <div id="other_benifits" class="collapse show">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between flex-wrap gap-3">

                                        <div class="form-group mb-3" style="max-width:max-content">
                                            <label class="form-label fs-7 mb-0 w-100 text-center">NCL</label>
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <label class="form-check-label me-3" for="non_compete_loan">
                                                    No
                                                </label>
                                                <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="non_compete_loan" name="non_compete_loan" data-bs-toggle="collapse" data-bs-target="#non_compete_loan_from_container" <?= $appraisal['non_compete_loan'] == 'yes' ? "checked" : "" ?>>
                                                <label class="form-check-label" for="non_compete_loan">
                                                    Yes
                                                </label>
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <div class="collapse <?= $appraisal['non_compete_loan'] == 'yes' ? 'show' : '' ?>" id="non_compete_loan_from_container">
                                                <div class="flex-1 d-flex flex-wrap gap-5">
                                                    <div class="separater" style="width: 3px; background: #f0f0f0;">&nbsp;</div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label fs-7 mb-0 w-100" for="non_compete_loan_amount_per_month">Amount Per Month</label>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                            <input type="text" id="non_compete_loan_amount_per_month" class="form-control form-control-sm " name="non_compete_loan_amount_per_month" placeholder="Amount Per Month" value="<?= $appraisal['non_compete_loan_amount_per_month'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fs-7 mb-0 w-100" for="non_compete_loan_from">From</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" id="non_compete_loan_from" class="form-control form-control-sm  date_picker" name="non_compete_loan_from" placeholder="From" value="<?= $appraisal['non_compete_loan_from'] ?>" />
                                                            <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <hr class="my-7 opacity-10">

                                    <div class="d-flex justify-content-between flex-wrap gap-3">

                                        <div class="form-group mb-3" style="max-width:max-content">
                                            <label class="form-label fs-7 mb-0 w-100 text-center">Loyalty Incentive</label>
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <label class="form-check-label me-3" for="loyalty_incentive">
                                                    No
                                                </label>
                                                <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="loyalty_incentive" name="loyalty_incentive" data-bs-toggle="collapse" data-bs-target="#li_details_container" <?= $appraisal['loyalty_incentive'] == 'yes' ? "checked" : "" ?>>
                                                <label class="form-check-label" for="loyalty_incentive">
                                                    Yes
                                                </label>
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <div class="collapse <?= $appraisal['loyalty_incentive'] == 'yes' ? 'show' : '' ?>" id="li_details_container">
                                                <div class="flex-1 d-flex flex-wrap gap-5">
                                                    <div class="separater" style="width: 3px; background: #f0f0f0;">&nbsp;</div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label fs-7 mb-0 w-100" for="loyalty_incentive_amount_per_month">Amount Per Month</label>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                            <input type="text" id="loyalty_incentive_amount_per_month" class="form-control form-control-sm " name="loyalty_incentive_amount_per_month" placeholder="Amount Per Month" value="<?= $appraisal['loyalty_incentive_amount_per_month'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label fs-7 mb-0 w-100" for="loyalty_incentive_from">From</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" id="loyalty_incentive_from" class="form-control form-control-sm  date_picker" name="loyalty_incentive_from" placeholder="From" value="<?= $appraisal['loyalty_incentive_from'] ?>" />
                                                            <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <hr class="my-7 opacity-10">

                                    <div class="d-flex justify-content-between flex-wrap gap-3">

                                        <div class="form-group mb-3" style="max-width:max-content">
                                            <label class="form-label fs-7 mb-0 w-100 text-center">Other Benefit</label>
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <label class="form-check-label me-3" for="enable_other_benefit">
                                                    No
                                                </label>
                                                <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="enable_other_benefit" name="enable_other_benefit" data-bs-toggle="collapse" data-bs-target="#other_benefit_details_container" <?= !empty($appraisal['other_benefits']) ? "checked" : "" ?>>
                                                <label class="form-check-label" for="enable_other_benefit">
                                                    Yes
                                                </label>
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <div class="collapse <?= !empty($appraisal['other_benefits']) == 'yes' ? 'show' : '' ?>" id="other_benefit_details_container">
                                                <div class="flex-1 d-flex flex-wrap gap-5">
                                                    <div class="separater" style="width: 3px; background: #f0f0f0;">&nbsp;</div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label fs-7 mb-0 w-100" for="other_benefit">Amount</label>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text">
                                                                <i class="fa-solid fa-indian-rupee-sign"></i>
                                                            </span>
                                                            <input type="text" id="other_benefits" class="form-control form-control-sm " name="other_benefits" placeholder="Amount" value="<?= $appraisal['other_benefits'] ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fs-7 mb-0 w-100" for="other_benefit_from">From</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" id="other_benefit_from" class="form-control form-control-sm  date_picker" name="other_benefit_from" placeholder="From" value="<?= $appraisal['other_benefit_from'] ?>" />
                                                            <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="accordion accordion-icon-toggle rounded border mb-5">
                        <div class="card">
                            <div class="card-header accordion-header bg-white" data-bs-toggle="collapse" data-bs-target="#appraisals_meta">
                                <div class="card-title">
                                    Appraisals Date and Remarks
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
                            <div id="appraisals_meta" class="collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-7" id="appraisal_remarks_container">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="appraisal_remarks">Remarks</label>
                                                <textarea id="appraisal_remarks" class="form-control " name="appraisal_remarks" placeholder="Remarks"><?= $appraisal['appraisal_remarks'] ?></textarea>
                                                <small class="text-danger error-text" id="appraisal_remarks_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-3" id="appraisal_date_container">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="appraisal_date">Affected From</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" id="appraisal_date" class="form-control form-control-sm  date_picker" name="appraisal_date" placeholder="From" value="<?= $appraisal['appraisal_date'] ?>" />
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                </div>
                                                <small class="text-danger error-text" id="appraisal_date_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label class=" form-label">&nbsp;</label>
                                                <div class="input-group input-group-sm">
                                                    <button type="submit" id="submit_update_appraisals" class="form-control btn btn-sm btn-primary d-inline" style="max-width: max-content">
                                                        <span class="indicator-label">Update</span>
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

            </div>
        </form>

        <!--end::Col-->
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <table id="appraisals_table" class="table table-striped nowrap">
                <thead>
                    <tr>
                        <th class="text-center bg-white"><strong>Emp Code</strong></th>
                        <th class="text-center bg-white"><strong>Appraisal Date</strong></th>
                        <th class="text-center"><strong>CTC</strong></th>
                        <th class="text-center"><strong>Gross Salary</strong></th>
                        <th class="text-center bg-white"><strong>Appraisal Amount</strong></th>
                        <th class="text-center"><strong>Basic Salary</strong></th>
                        <th class="text-center"><strong>HRA</strong></th>
                        <th class="text-center"><strong>Conveyance</strong></th>
                        <th class="text-center"><strong>Medical Allowance</strong></th>
                        <th class="text-center"><strong>Special Allowance</strong></th>
                        <th class="text-center"><strong>Fuel Allowance</strong></th>
                        <th class="text-center"><strong>Other Allowance</strong></th>
                        <th class="text-center"><strong>Other Benefits</strong></th>
                        <!-- <th class="text-center"><strong>Bonus</strong></th> -->
                        <th class="text-center"><strong>PF</strong></th>
                        <th class="text-center"><strong>ESI</strong></th>
                        <th class="text-center"><strong>LWF</strong></th>
                        <th class="text-center"><strong>Non-Compete</strong></th>
                        <th class="text-center"><strong>Loyalty</strong></th>
                        <th class="text-center bg-white"><strong>Action</strong></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="text-center bg-white"><strong>Emp Code</strong></th>
                        <th class="text-center bg-white"><strong>Appraisal Date</strong></th>
                        <th class="text-center"><strong>CTC</strong></th>
                        <th class="text-center"><strong>Gross Salary</strong></th>
                        <th class="text-center bg-white"><strong>Appraisal Amount</strong></th>
                        <th class="text-center"><strong>Basic Salary</strong></th>
                        <th class="text-center"><strong>HRA</strong></th>
                        <th class="text-center"><strong>Conveyance</strong></th>
                        <th class="text-center"><strong>Medical Allowance</strong></th>
                        <th class="text-center"><strong>Special Allowance</strong></th>
                        <th class="text-center"><strong>Fuel Allowance</strong></th>
                        <th class="text-center"><strong>Other Allowance</strong></th>
                        <th class="text-center"><strong>Other Benefits</strong></th>
                        <!-- <th class="text-center"><strong>Bonus</strong></th> -->
                        <th class="text-center"><strong>PF</strong></th>
                        <th class="text-center"><strong>ESI</strong></th>
                        <th class="text-center"><strong>LWF</strong></th>
                        <th class="text-center"><strong>Non-Compete</strong></th>
                        <th class="text-center"><strong>Loyalty</strong></th>
                        <th class="text-center bg-white"><strong>Action</strong></th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>





    <div class="modal fade" id="viewAppraisalsModal" tabindex="-1" aria-labelledby="viewAppraisalsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAppraisalsLabel">View Appraisals</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic content will be loaded here -->
                    <div id="appraisalsDetails">
                        <!-- <p>Loading details...</p> -->
                        <div class="card-body border-bottom row g-3">
                            <div class="col-md-4">
                                <label class="floating-label">Employee ID</label>
                                <span class="form-control form-control-sm border-dashed" id="employee_id_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Name</label>
                                <span class="form-control form-control-sm border-dashed" id="employee_name_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Appraisal Date</label>
                                <span class="form-control form-control-sm border-dashed" id="appraisal_date_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Appraisal Remarks</label>
                                <span class="form-control form-control-sm border-dashed" id="appraisal_remarks_view"></span>
                            </div>
                        </div>

                        <div class="card-body border-bottom row g-3">
                            <!-- Salary Information -->
                            <div class="col-md-4">
                                <label class="floating-label">CTC</label>
                                <span class="form-control form-control-sm border-dashed" id="ctc_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Gross Salary</label>
                                <span class="form-control form-control-sm border-dashed" id="gross_salary_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Basic Salary</label>
                                <span class="form-control form-control-sm border-dashed" id="basic_salary_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">HRA</label>
                                <span class="form-control form-control-sm border-dashed" id="hra_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Conveyance</label>
                                <span class="form-control form-control-sm border-dashed" id="conveyance_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Medical Allowance</label>
                                <span class="form-control form-control-sm border-dashed" id="medical_allowance_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Special Allowance</label>
                                <span class="form-control form-control-sm border-dashed" id="special_allowance_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Other Allowance</label>
                                <span class="form-control form-control-sm border-dashed" id="other_allowance_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Others </label>
                                <span class="form-control form-control-sm border-dashed" id="other_benefits_view"></span>
                            </div>
                        </div>

                        <div class="card-body border-bottom row g-3">
                            <!-- <div class="col-md-4">
                                <label class="floating-label">Bonus</label>
                                <span class="form-control form-control-sm border-dashed" id="bonus_view"></span>
                            </div> -->
                            <div class="col-md-4">
                                <label class="floating-label">Loyalty Incentive</label>
                                <span class="form-control form-control-sm border-dashed" id="loyalty_incentive_view"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="floating-label">Remarks</label>
                                <span class="form-control form-control-sm border-dashed" id="loyalty_incentive_remarks_view"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a href="<?php echo base_url('/backend/master/appraisals/edit/'); ?>" class="btn btn-warning" id="editAppraisalsButton">Edit</a>

                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    <?= $this->section('javascript') ?>
    <script src="<?php echo base_url(''); ?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="<?php echo base_url(''); ?>/assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {

            var employee = <?php echo json_encode($selectedEmployee); ?>;

            $(document).on('change', '#employee_name', function(e) {
                e.preventDefault();
                window.location.replace("<?php echo base_url('/backend/master/appraisals/add'); ?>/" + $(this).val());
            });

            $('form#update_appraisals .date_picker').each(function(index, elem) {
                var myid = $(this).attr('id');
                $(this).change(function(e) {
                    $('#' + myid + '_error').html('')
                })
                $(this).flatpickr();
            });

            recalculateAll();

            $(document).on('input', 'form#update_appraisals input[type=text]', function(e) {
                recalculateAll();
            });

            $(document).on('change', 'form#update_appraisals input[type=checkbox]', function(e) {
                recalculateAll();
            });

            function recalculateAll() {
                const components = ['basic_salary', 'house_rent_allowance', 'medical_allowance', 'conveyance', 'special_allowance', 'fuel_allowance', 'other_allowance', 'vacation_allowance'];
                let appraisal_amount_new = 0;

                let gross_salary_old = parseFloat($('#gross_salary_old').text()) || 0;
                let gross_salary_new = 0;

                let appraisal_percentage_new = 0;

                let ctc_old = parseFloat($('#ctc_old').text()) || 0;
                let ctc_new = 0;

                let sum_of_components = 0;

                // let bonus_amount = 0;

                components.forEach(component => {
                    let oldVal = parseFloat($('#' + component + '_old').text()) || 0;
                    let incVal = parseFloat($('#' + component).val()) || 0;
                    let newVal = oldVal + incVal;
                    sum_of_components += incVal;
                    // gross_salary_old += oldVal;
                    // ctc_old += oldVal;
                    $('#' + component + '_new').text(newVal);
                });

                gross_salary_new = gross_salary_old + sum_of_components;
                appraisal_amount_new = sum_of_components;
                ctc_new = ctc_old + appraisal_amount_new;

                if ($("#enable_bonus").prop('checked') == true) {
                    let minimum_wages = employee.minimum_wages;
                    bonus_amount = minimum_wages * 8.33 / 100;
                    console.log("bonus " + bonus_amount);
                    ctc_new += bonus_amount;
                    appraisal_amount_new += bonus_amount;
                }

                if ($('#pf').prop('checked') == true) {
                    let pf_base_salary_new = (gross_salary_new >= 15000) ? 15000 : gross_salary_new;
                    let pf_base_salary_old = (gross_salary_old >= 15000) ? 15000 : gross_salary_old;
                    let pf_employer_contribution_new = Math.round((pf_base_salary_new * 13) / 100);
                    let pf_employer_contribution_old = Math.round((pf_base_salary_old * 13) / 100);
                    let pf_difference = pf_employer_contribution_new - pf_employer_contribution_old;
                    ctc_new += pf_difference; // Only add the difference to CTC, not full amount
                    appraisal_amount_new += pf_difference; // Only add the difference, not full amount
                }

                if ($('#esi').prop('checked') && gross_salary_new <= 21000) {
                    let esi_employer_contribution_new = Math.round((gross_salary_new * 3.25) / 100);
                    let esi_employer_contribution_old = (gross_salary_old <= 21000) ? Math.round((gross_salary_old * 3.25) / 100) : 0;
                    let esi_difference = esi_employer_contribution_new - esi_employer_contribution_old;
                    ctc_new += esi_difference; // Only add the difference to CTC, not full amount
                    appraisal_amount_new += esi_difference; // Only add the difference, not full amount
                }

                if ($('#lwf').prop('checked') == true) {
                    let lwf_employee_contribution_new = ((gross_salary_new * 0.2) / 100 <= 31) ? (gross_salary_new * 0.2) / 100 : 31;
                    let lwf_employee_contribution_old = ((gross_salary_old * 0.2) / 100 <= 31) ? (gross_salary_old * 0.2) / 100 : 31;
                    let lwf_employer_contribution_new = Math.round(lwf_employee_contribution_new * 2);
                    let lwf_employer_contribution_old = Math.round(lwf_employee_contribution_old * 2);
                    let lwf_difference = lwf_employer_contribution_new - lwf_employer_contribution_old;
                    ctc_new += lwf_difference; // Only add the difference to CTC, not full amount
                    appraisal_amount_new += lwf_difference; // Only add the difference, not full amount
                    console.log("lwf difference: " + lwf_difference);
                }

                if ($('#non_compete_loan').prop('checked') == true) {
                    let non_compete_loan_amount_per_month = parseFloat($('#non_compete_loan_amount_per_month').val()) || 0;
                    ctc_new += non_compete_loan_amount_per_month;
                    appraisal_amount_new += non_compete_loan_amount_per_month;
                }

                if ($('#loyalty_incentive').prop('checked') == true) {
                    let loyalty_incentive_amount_per_month = parseFloat($('#loyalty_incentive_amount_per_month').val()) || 0;
                    ctc_new += loyalty_incentive_amount_per_month;
                    appraisal_amount_new += loyalty_incentive_amount_per_month;
                }

                if ($('#enable_other_benefit').prop('checked') == true) {
                    let other_benefit = parseFloat($('#other_benefits').val()) || 0;
                    ctc_new += other_benefit;
                    appraisal_amount_new += other_benefit;
                }

                appraisal_percentage_new = gross_salary_old > 0 ? (appraisal_amount_new / gross_salary_old * 100) : 0;



                $('#appraisal_amount_new').text(appraisal_amount_new.toFixed(2));
                $('#appraisal_percentage_new').text(appraisal_percentage_new.toFixed(2) + '%');
                // $('#gross_salary_old').text(gross_salary_old);
                $('#gross_salary_new').text(gross_salary_new.toFixed(2));
                // $('#ctc_old').text(ctc_old);
                $('#ctc_new').text(ctc_new.toFixed(2));
            }

            $(document).on('submit', '#update_appraisals', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                var formData = new FormData(form[0]);

                // for (const pair of formData.entries()) {
                //     console.log(pair[0], pair[1]);
                // }
                // return false;

                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('backend/master/appraisals/' . $appraisal['id']); ?>",
                    data: formData,
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
                                    console.log(response.response_data.validation);
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
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    location.reload();
                                });
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

            function inr(val) {
                if (val === null || val === undefined || val === '') return '-';
                const n = parseFloat(val);
                if (!isFinite(n)) return '-';
                return '₹ ' + n.toLocaleString('en-IN');
            }

            function yesNo(v) {
                return v === 'yes' ?
                    '<span class="badge badge-light-success">Yes</span>' :
                    '<span class="badge badge-light-danger">No</span>';
            }

            var appraisals_table = $("#appraisals_table").DataTable({

                "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3 mb-md-0"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end php-pagination-container"p>>>>',
                "buttons": [{
                    text: '<i class="fa fa-download"></i> Download PDF',
                    className: 'btn btn-sm btn-primary',
                    action: function() {
                        generatePDF();
                    }
                }],
                "ajax": {
                    url: "<?= base_url('ajax/master/appraisals/table-by-empid/' . $appraisal['employee_id']) ?>",
                    type: "GET",
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                },
                "oLanguage": {
                    "sSearch": ""
                },
                "columns": [{
                        data: "employee_code",
                        render: function(data, type, row) {
                            return `${row.employee_name} : ${row.employee_code}`;
                        }
                    },
                    {
                        data: "appraisal_date"
                    },
                    {
                        data: "ctc",
                        render: function(data, type, row) {
                            return inr(row.ctc);
                        }
                    },
                    {
                        data: "gross_salary",
                        render: function(data, type, row) {
                            return inr(row.gross_salary);
                        }
                    },
                    {
                        data: "total_appraisal",
                        className: "text-center",
                        render: function(data, type, row) {
                            return inr(row.total_appraisal);
                        }
                    },
                    {
                        data: "basic_salary",
                        render: function(data, type, row) {
                            return inr(row.basic_salary);
                        }
                    },
                    {
                        data: "house_rent_allowance",
                        render: function(data, type, row) {
                            return inr(row.house_rent_allowance);
                        }
                    },
                    {
                        data: "conveyance",
                        render: function(data, type, row) {
                            return inr(row.conveyance);
                        }
                    },
                    {
                        data: "medical_allowance",
                        render: function(data, type, row) {
                            return inr(row.medical_allowance);
                        }
                    },
                    {
                        data: "special_allowance",
                        render: function(data, type, row) {
                            return inr(row.special_allowance);
                        }
                    },
                    {
                        data: "fuel_allowance",
                        render: function(data, type, row) {
                            return inr(row.fuel_allowance);
                        }
                    },
                    {
                        data: "other_allowance",
                        render: function(data, type, row) {
                            return inr(row.other_allowance);
                        }
                    },
                    {
                        data: "other_benefits",
                        render: function(data, type, row) {
                            return inr(row.other_benefits);
                        }
                    },
                    // {
                    //     data: "enable_bonus",
                    //     render: function(d, t, row) {
                    //         if (row.enable_bonus == 'yes') return '<span class="badge badge-light-success">Yes</span>';
                    //         if (row.enable_bonus == 'no') return '<span class="badge badge-light-danger">No</span>';
                    //     }
                    // },
                    {
                        data: "pf",
                        render: function(d, t, row) {
                            if (row.pf == 'yes') return '<span class="badge badge-light-success">Yes</span>';
                            if (row.pf == 'no') return '<span class="badge badge-light-danger">No</span>';
                        }
                    },
                    {
                        data: "esi",
                        render: function(d, t, row) {
                            if (row.esi == 'yes') return '<span class="badge badge-light-success">Yes</span>';
                            if (row.esi == 'no') return '<span class="badge badge-light-danger">No</span>';
                        }
                    },
                    {
                        data: "lwf",
                        render: function(d, t, row) {
                            if (row.lwf == 'yes') return '<span class="badge badge-light-success">Yes</span>';
                            if (row.lwf == 'no') return '<span class="badge badge-light-danger">No</span>';
                        }
                    },

                    {
                        data: "non_compete_loan",
                        render: function(d, t, row) {
                            if (row.non_compete_loan == 'yes') return `<div class="">
                                    <span class="badge badge-light-success">Yes</span><br>
                                    <small class="text-muted">${inr(row.non_compete_loan_amount_per_month)}</small>
                                </div>`;
                            if (row.non_compete_loan == 'no') return '<span class="badge badge-light-danger">No</span>';
                        }
                    },
                    {
                        data: "loyalty_incentive",
                        render: function(d, t, row) {
                            if (row.loyalty_incentive == 'yes') return `<div class="">
                                    <span class="badge badge-light-success">Yes</span><br>
                                    <small class="text-muted">${inr(row.loyalty_incentive_amount_per_month)}</small>
                                </div>`;
                            if (row.loyalty_incentive == 'no') return '<span class="badge badge-light-danger">No</span>';
                        }
                    },

                    {
                        data: "id",
                        orderable: false,
                        render: function(id, type, row, meta) {
                            var viewBtn = '<a href="#" class="view-appraisals btn btn-sm btn-info" data-id="' + id + '">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa-solid fa-money-bill-transfer"></i> <small>View</small>' +
                                '</span></a>';
                            var editBtn = row.is_editable == 1 ? '<a href="<?= base_url('/backend/master/appraisals/edit/') ?>' + id + '" class="btn btn-sm btn-warning edit-appraisals">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa fa-pen"></i> <small>Edit</small>' +
                                '</span></a>' : '';
                            var delBtn = row.is_editable == 1 ? '<a href="javascript:void(0);" class="btn btn-sm btn-danger delete-appraisals" data-id="' + id + '">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa fa-times"></i> <small>Delete</small>' +
                                '</span></a>' : '';

                            return '<div class="d-flex flex-column"><div class="btn-group mb-2">' + viewBtn + '</div><div class="btn-group">' + editBtn + delBtn + '</div></div>';
                        },
                        className: "text-center"
                    }
                ],
                "fixedColumns": {
                    left: 2,
                    right: 1
                },
                "order": [],
                "scrollX": true,
                "scrollY": '50vh',
                "paging": false,
                "columnDefs": [{
                        "className": 'border-start border-secondary td-border-left text-center',
                        "targets": [-1]
                    },
                    {
                        "className": 'border-end border-secondary td-border-right text-center',
                        "targets": [1]
                    },
                    {
                        "className": 'text-center',
                        "targets": '_all'
                    },
                ],

            });
            $('#appraisals_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Appraisals</h3>');

            $('#filter_form').submit(function(e) {
                e.preventDefault();
                appraisals_table.ajax.reload();
            });

        })


        $(document).on('click', '.view-appraisals', function(e) {
            e.preventDefault();
            var appraisalId = $(this).data('id'); // Get the appraisal ID
            $('#viewAppraisalsModal').modal('show');
            $.ajax({
                url: "<?php echo base_url('ajax/master/appraisals/details'); ?>/" + appraisalId,
                type: 'POST',
                data: {
                    id: appraisalId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#appraisalsDetails').find("#employee_id_view").html(`${response.data.internal_employee_id}`);
                        $('#appraisalsDetails').find("#employee_name_view").html(`${response.data.first_name} ${response.data.last_name}`);
                        $('#appraisalsDetails').find("#appraisal_date_view").html(`${response.data.appraisal_date}`);
                        $('#appraisalsDetails').find("#appraisal_remarks_view").html(`${response.data.appraisal_remarks || 'N/A'}`);
                        $('#appraisalsDetails').find("#ctc_view").html(`${response.data.ctc}`);
                        $('#appraisalsDetails').find("#gross_salary_view").html(`${response.data.gross_salary}`);
                        $('#appraisalsDetails').find("#basic_salary_view").html(`${response.data.basic_salary}`);
                        $('#appraisalsDetails').find("#hra_view").html(`${response.data.house_rent_allowance}`);
                        $('#appraisalsDetails').find("#conveyance_view").html(`${response.data.conveyance}`);
                        $('#appraisalsDetails').find("#medical_allowance_view").html(`${response.data.medical_allowance}`);
                        $('#appraisalsDetails').find("#special_allowance_view").html(`${response.data.special_allowance}`);
                        $('#appraisalsDetails').find("#other_allowance_view").html(`${response.data.other_allowance}`);
                        $('#appraisalsDetails').find("#other_benefits_view").html(`${response.data.other_benefits}`);
                        //$('#appraisalsDetails').find("#bonus_view").html(`${response.data.enable_bonus === 'yes' ? 'Enabled' : 'Disabled'}`);
                        $('#appraisalsDetails').find("#loyalty_incentive_view").html(`${response.data.loyalty_incentive || 'N/A'}`);
                        $('#appraisalsDetails').find("#loyalty_incentive_remarks_view").html(`${response.data.loyalty_incentive_remarks || 'N/A'}`);
                        // $('#appraisalsDetails').html(details);
                        var editUrl = '<?= base_url("/backend/master/appraisals/edit/"); ?>' + appraisalId;
                        $('#editAppraisalsButton').attr('href', editUrl);
                    } else {
                        $('#appraisalsDetails').find("#employee_id_view").html(``);
                        $('#appraisalsDetails').find("#employee_name_view").html(``);
                        $('#appraisalsDetails').find("#appraisal_date_view").html(``);
                        $('#appraisalsDetails').find("#appraisal_remarks_view").html(``);
                        $('#appraisalsDetails').find("#ctc_view").html(``);
                        $('#appraisalsDetails').find("#gross_salary_view").html(``);
                        $('#appraisalsDetails').find("#basic_salary_view").html(``);
                        $('#appraisalsDetails').find("#hra_view").html(``);
                        $('#appraisalsDetails').find("#conveyance_view").html(``);
                        $('#appraisalsDetails').find("#medical_allowance_view").html(``);
                        $('#appraisalsDetails').find("#special_allowance_view").html(``);
                        $('#appraisalsDetails').find("#other_allowance_view").html(``);
                        $('#appraisalsDetails').find("#other_benefits_view").html(``);
                        // $('#appraisalsDetails').find("#bonus_view").html(``);
                        $('#appraisalsDetails').find("#loyalty_incentive_view").html(``);
                        $('#appraisalsDetails').find("#loyalty_incentive_remarks_view").html(``);
                    }
                },
                error: function() {
                    $('#appraisalsDetails').find("#employee_id_view").html(``);
                    $('#appraisalsDetails').find("#employee_name_view").html(``);
                    $('#appraisalsDetails').find("#appraisal_date_view").html(``);
                    $('#appraisalsDetails').find("#appraisal_remarks_view").html(``);
                    $('#appraisalsDetails').find("#ctc_view").html(``);
                    $('#appraisalsDetails').find("#gross_salary_view").html(``);
                    $('#appraisalsDetails').find("#basic_salary_view").html(``);
                    $('#appraisalsDetails').find("#hra_view").html(``);
                    $('#appraisalsDetails').find("#conveyance_view").html(``);
                    $('#appraisalsDetails').find("#medical_allowance_view").html(``);
                    $('#appraisalsDetails').find("#special_allowance_view").html(``);
                    $('#appraisalsDetails').find("#other_allowance_view").html(``);
                    $('#appraisalsDetails').find("#other_benefits_view").html(``);
                    // $('#appraisalsDetails').find("#bonus_view").html(``);
                    $('#appraisalsDetails').find("#loyalty_incentive_view").html(``);
                    $('#appraisalsDetails').find("#loyalty_incentive_remarks_view").html(``);
                }
            });

        });

        $(document).on('click', '.delete-appraisals', function(e) {
            e.preventDefault();
            var appraisalId = $(this).data('id');
            var id = $('#id').val();
            if (appraisalId == id) {
                swal.fire({
                    title: 'Error!',
                    text: 'You cannot delete the current open appraisal record.',
                    icon: 'error',
                    confirmButtonText: 'Ok, got it!',
                });
                return;
            }
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone. Do you really want to delete this item?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete operation if confirmed
                    deleteItem(appraisalId);
                }
            });

            function deleteItem(appraisalId) {
                $.ajax({
                    url: "<?= base_url('backend/master/appraisals'); ?>/" + appraisalId,
                    type: 'POST',
                    data: {
                        id: appraisalId,
                        "_method": "DELETE"
                    },
                    success: function(response) {
                        console.log(response);
                        swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: response.status,
                            confirmButtonText: 'Ok, got it!',
                        }).then(function() {
                            $('#appraisals_table').DataTable().ajax.reload();
                        });

                    },
                    error: function() {
                        swal.fire({
                            title: 'Error!',
                            text: 'Failed to delete appraisal.',
                            icon: 'error',
                            confirmButtonText: 'Ok, got it!',
                        });
                    }
                });

            }

        });

        function generatePDF() {
            var empId = $('#employee_id').val();
            window.location.href = '<?php echo base_url('/backend/master/appraisals/pdf/') ?>' + empId
        }
    </script>
    <?= $this->endSection() ?>
    <?= $this->endSection() ?>