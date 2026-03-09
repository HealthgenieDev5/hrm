<?= $this->extend('Templates/DashboardLayout') ?>
<?= $this->section('content') ?>
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-12">

        <form class="card shadow-sm mb-5" id="filter_form">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label" for="employee_name" class="mb-3">Employee Name</label>
                        <select class="form-select " id="employee_name" name="employee_name" data-control="select2" data-placeholder="Select an Employee">
                            <option></option>
                            <?php
                            foreach ($employees as $employee_row) {
                            ?>
                                <option value="<?php echo $employee_row['id']; ?>" <?= edit_set_select('employee_id', $employee_row['id'], $employee_id) ?>><?php echo trim($employee_row['first_name'] . ' ' . $employee_row['last_name']); ?> [ <?php echo $employee_row['internal_employee_id']; ?> ] <?php echo $employee_row['department_name'] . ' - ' . $employee_row['company_name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <style>
            .accordion-header.collapsed {
                border-radius: calc(-1px + 0.475rem) !important;
            }
        </style>
        <form id="update_appraisals" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="accordion accordion-icon-toggle rounded border mb-5">
                        <div class="card">
                            <div class="card-header accordion-header bg-white" data-bs-toggle="collapse" data-bs-target="#general_information">
                                <div class="card-title">
                                    Current Salary
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
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="employee_id"><span>Employee ID:</span> <span class="ms-2"><span class="text-muted">#</span><?php echo @$salary['employee_id']; ?></span></label>
                                                <input type="hidden" id="employee_id" name="employee_id" value="<?= set_value('employee_id', @$salary['employee_id']) ?>" />
                                                <small class="text-info"><span>Total Appraisal:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span><span id="total_appraisal_amount">0</span> <span id="total_appraisal_percent" class="ms-1">(0%)</span></small><br>
                                                <small class="text-danger error-text" id="employee_id_error"></small><br>
                                                <input type="hidden" id="salary_id" name="salary_id" value="<?= set_value('salary_id', @$salary['id']) ?>" />

                                                <!-- <small class="text-danger error-text" id="salary_id_error"></small> -->
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="total_appraisal"><span>Total Appraisal:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span><span id="total_appraisal_amount">0</span></span></label>
                                                <small class="text-muted">Sum of all increments</small>
                                            </div>
                                        </div> -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="ctc"><span>Curr CTC:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span><?= set_value('ctc', @$salary['ctc']) ?> </span></label>
                                                <!-- <input type="hidden" id="ctc" name="ctc" value="<?= set_value('ctc', @$salary['ctc']) ?>" />
                                                <small class="text-muted">Includes 1.25 EL and 1 CL</small><br> -->
                                                <!--<small class="text-info"><span>Real-time CTC:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span><span id="real_time_ctc">0</span></span></small><br>
                                                <small class="text-danger error-text" id="ctc_error"></small>-->
                                            </div>
                                        </div>

                                        <!-- <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="ctc"><span>Real-time CTC:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span><span id="real_time_ctc">0</span></span></label>
                                                <small class="text-muted">Updates automatically as you change values</small>
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="gross_salary"><span>Real-time Gross Salary:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span><span id="real_time_gross_salary">0</span></span></label>
                                                <small class="text-muted">Updates automatically as you change values</small>
                                            </div>
                                        </div> -->

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="gross_salary"><span>Curr Gross Salary:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span> <?php echo @$salary['gross_salary']; ?></span></label>
                                                <input type="hidden" id="gross_salary" name="gross_salary" value="<?php echo @$salary['gross_salary']; ?>" />
                                                <small class="text-info"><span>Real-time Gross Salary:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span><span id="real_time_gross_salary">0</span></span></small><br>
                                                <small class="text-danger error-text" id="gross_salary_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="basic_salary">Basic Salary</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="basic_salary" class="form-control form-control-sm " name="basic_salary" placeholder="Basic Salary" value="" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">Curr: <?php echo @$salary['basic_salary']; ?></span>
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="basic_salary_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="house_rent_allowance">HRA</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="house_rent_allowance" class="form-control form-control-sm " name="house_rent_allowance" placeholder="HRA" value="" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">Curr: <?php echo @$salary['house_rent_allowance']; ?></span>
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="house_rent_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="conveyance">Conveyance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="conveyance" class="form-control form-control-sm " name="conveyance" placeholder="Conveyance" value="" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">Curr: <?php echo @$salary['conveyance']; ?></span>
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="conveyance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="medical_allowance">Medical Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="medical_allowance" class="form-control form-control-sm " name="medical_allowance" placeholder="Medical Allowance" value="" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">Curr: <?php echo @$salary['medical_allowance']; ?></span>
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="medical_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="special_allowance">Special Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="special_allowance" class="form-control form-control-sm " name="special_allowance" placeholder="Special Allowance" value="" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">Curr: <?php echo @$salary['special_allowance']; ?></span>
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="special_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="fuel_allowance">Fuel Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="fuel_allowance" class="form-control form-control-sm " name="fuel_allowance" placeholder="Fuel Allowance" value="" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">Curr: <?php echo @$salary['fuel_allowance']; ?></span>
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="fuel_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="other_allowance">Other Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="other_allowance" class="form-control form-control-sm " name="other_allowance" placeholder="Other Allowance" value="" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                    <span class="input-group-text">Curr: <?php echo @$salary['other_allowance']; ?></span>
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="other_allowance_error"></small>
                                            </div>
                                        </div>

                                        <hr class="my-7 opacity-10">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class=" form-label">Bonus</label>
                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                        <label class="form-check-label me-3" for="enable_bonus">
                                                            No
                                                        </label>
                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="enable_bonus" name="enable_bonus">
                                                        <label class="form-check-label" for="enable_bonus">
                                                            Yes
                                                        </label>
                                                    </div>
                                                    <small class="text-danger error-text" id="enable_bonus_error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="bonus_container" style="display: <?php echo (@$salary['enable_bonus'] == 'yes') ? 'block' : 'none'; ?>">
                                                <div class="form-group mb-3">
                                                    <label class=" form-label" for="bonus">Bonus</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="form-control form-control-sm" style="background-color: #d4eeff;">8.33% of Minimum Wages</span>
                                                    </div>
                                                    <small class="text-muted">Per month</small><br>
                                                    <small class="text-danger error-text" id="bonus_error"></small>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion accordion-icon-toggle rounded border">
                        <div class="card">
                            <div class="card-header accordion-header bg-white" data-bs-toggle="collapse" data-bs-target="#deduction_head">
                                <div class="card-title">
                                    Deduction
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
                            <div id="deduction_head" class="collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label">PF</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="pf">
                                                        No
                                                    </label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="pf" name="pf" <?php echo (isset($salary['pf']) && $salary['pf'] == 'yes') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="pf">
                                                        Yes
                                                    </label>
                                                </div>
                                                <small class="text-danger error-text" id="non_compete_loan_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="pf_number_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="pf_number">UAN Number</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                                    <input type="text" id="pf_number" class="form-control form-control-sm " name="pf_number" placeholder="UAN Number" value="<?= set_value('pf_number', @$salary['pf_number']) ?>" />
                                                </div>
                                                <small class="text-danger error-text" id="pf_number_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4"></div>

                                        <!-- <div class="col-md-4" id="pf_employee_contribution_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
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
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="pf_employer_contribution">Employer Contribution</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="form-control form-control-sm" style="background-color: #d4eeff;">13%</span>
                                                    <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-percent"></i></span>
                                                </div>
                                                <small class="text-muted">if BasicSalary >= 15000 or <br>GrossSalary-HRA >= 15000 <br>then value will be 13% of 15000, <br>otherwise value will be <br>13% of (GrossSalary-HRA)</small><br>
                                                <small class="text-danger error-text" id="pf_employer_contribution_error"></small>
                                            </div>
                                        </div> -->
                                    </div>

                                    <hr class="my-7 opacity-10">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label">ESI</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="esi">
                                                        No
                                                    </label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="esi" name="esi" <?php echo (@$salary['esi'] == 'yes') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="esi">
                                                        Yes
                                                    </label>
                                                </div>
                                                <small class="text-danger error-text" id="esi_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="esi_number_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="esi_number">ESI Number</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                                    <input type="text" id="esi_number" class="form-control form-control-sm " name="esi_number" placeholder="ESI Number" value="<?= set_value('esi_number', @$salary['esi_number']) ?>" />
                                                </div>
                                                <small class="text-danger error-text" id="esi_number_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4"></div>

                                        <!-- <div class="col-md-4" id="esi_employee_contribution_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
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
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="esi_employer_contribution">Employer Contribution</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="form-control form-control-sm" style="background-color: #d4eeff;">3.25%</span>
                                                    <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-percent"></i></span>
                                                </div>
                                                <small class="text-muted">3.25% of GrossSalary</small><br>
                                                <small class="text-danger error-text" id="esi_employer_contribution_error"></small>
                                            </div>
                                        </div> -->
                                    </div>

                                    <hr class="my-7 opacity-10">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label">LWF</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="lwf">
                                                        No
                                                    </label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="lwf" name="lwf" <?php echo (@$salary['lwf'] == 'yes') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="lwf">
                                                        Yes
                                                    </label>
                                                </div>
                                                <small class="text-danger error-text" id="lwf_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="lwf_employee_contribution_container" style="display: <?php echo (@$salary['lwf'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
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
                                            <div class="form-group mb-3">
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
                            </div>
                        </div>
                    </div>







                </div>
                <div class="col-md-6">
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

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label">Non Compete Loan</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="non_compete_loan">
                                                        No
                                                    </label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="non_compete_loan" name="non_compete_loan" <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="non_compete_loan">
                                                        Yes
                                                    </label>
                                                </div>
                                                <small class="text-danger error-text" id="non_compete_loan_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="non_compete_loan_from_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="non_compete_loan_from">From</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" id="non_compete_loan_from" class="form-control form-control-sm  date_picker" name="non_compete_loan_from" placeholder="From" value="<?= set_value('non_compete_loan_from', @$salary['non_compete_loan_from']) ?>" />
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                </div>
                                                <small class="text-danger error-text" id="non_compete_loan_from_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="non_compete_loan_to_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="non_compete_loan_to">To</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" id="non_compete_loan_to" class="form-control form-control-sm  date_picker" name="non_compete_loan_to" placeholder="To" value="<?= set_value('non_compete_loan_to', @$salary['non_compete_loan_to']) ?>" />
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                </div>
                                                <small class="text-danger error-text" id="non_compete_loan_to_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="non_compete_loan_amount_per_month_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="non_compete_loan_amount_per_month">Amount Per Month</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="non_compete_loan_amount_per_month" class="form-control form-control-sm " name="non_compete_loan_amount_per_month" placeholder="Amount Per Month" value="<?= set_value('non_compete_loan_amount_per_month', @$salary['non_compete_loan_amount_per_month']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="non_compete_loan_amount_per_month_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-8" id="non_compete_loan_remarks_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="non_compete_loan_remarks">Remarks</label>
                                                <textarea id="non_compete_loan_remarks" class="form-control " name="non_compete_loan_remarks" placeholder="Remarks"><?= set_value('non_compete_loan_remarks', @$salary['non_compete_loan_remarks']) ?></textarea>
                                                <small class="text-danger error-text" id="non_compete_loan_amount_per_month_error"></small>
                                            </div>
                                        </div>

                                    </div>

                                    <hr class="my-7 opacity-10">

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label">Loyalty Incentive</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <label class="form-check-label me-3" for="loyalty_incentive">
                                                        No
                                                    </label>
                                                    <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="loyalty_incentive" name="loyalty_incentive" <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="loyalty_incentive">
                                                        Yes
                                                    </label>
                                                </div>
                                                <small class="text-danger error-text" id="loyalty_incentive_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="loyalty_incentive_from_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="loyalty_incentive_from">From</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" id="loyalty_incentive_from" class="form-control form-control-sm  date_picker" name="loyalty_incentive_from" placeholder="From" value="<?= set_value('loyalty_incentive_from', @$salary['loyalty_incentive_from']) ?>" />
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                </div>
                                                <small class="text-danger error-text" id="loyalty_incentive_from_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="loyalty_incentive_to_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="loyalty_incentive_to">To</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" id="loyalty_incentive_to" class="form-control form-control-sm  date_picker" name="loyalty_incentive_to" placeholder="To" value="<?= set_value('loyalty_incentive_to', @$salary['loyalty_incentive_to']) ?>" />
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                </div>
                                                <small class="text-danger error-text" id="loyalty_incentive_to_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="loyalty_incentive_amount_per_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="loyalty_incentive_amount_per_month">Amount Per Month</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="loyalty_incentive_amount_per_month" class="form-control form-control-sm " name="loyalty_incentive_amount_per_month" placeholder="Amount Per Month" value="<?= set_value('loyalty_incentive_amount_per_month', @$salary['loyalty_incentive_amount_per_month']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="loyalty_incentive_amount_per_month_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="loyalty_incentive_mature_after_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="loyalty_incentive_mature_after_month">Mature every X month</label>
                                                <select class="form-select form-select-sm" id="loyalty_incentive_mature_after_month" name="loyalty_incentive_mature_after_month" data-control="select2" data-placeholder="Select maturity month" onchange="$('#loyalty_incentive_mature_after_month_error').html('')">
                                                    <option></option>
                                                    <option value="0" <?php echo (@$salary['loyalty_incentive_mature_after_month'] == '0') ? 'selected' : ''; ?>>Salary Month</option>
                                                    <option value="01" <?php echo (@$salary['loyalty_incentive_mature_after_month'] == '1') ? 'selected' : ''; ?>>1 Month</option>
                                                    <option value="03" <?php echo (@$salary['loyalty_incentive_mature_after_month'] == '3') ? 'selected' : ''; ?>>3 Month</option>
                                                    <option value="06" <?php echo (@$salary['loyalty_incentive_mature_after_month'] == '6') ? 'selected' : ''; ?>>6 Month</option>
                                                    <option value="12" <?php echo (@$salary['loyalty_incentive_mature_after_month'] == '12') ? 'selected' : ''; ?>>12 Month</option>
                                                </select>
                                                <small class="text-danger error-text" id="loyalty_incentive_mature_after_month_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="loyalty_incentive_pay_after_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="loyalty_incentive_pay_after_month">Pay every X month</label>
                                                <select class="form-select form-select-sm" id="loyalty_incentive_pay_after_month" name="loyalty_incentive_pay_after_month" data-control="select2" data-placeholder="Select month for payment" onchange="$('#loyalty_incentive_pay_after_month_error').html('')">
                                                    <option></option>
                                                    <option value="0" <?php echo (@$salary['loyalty_incentive_pay_after_month'] == '0') ? 'selected' : ''; ?>>Salary Month</option>
                                                    <option value="01" <?php echo (@$salary['loyalty_incentive_pay_after_month'] == '1') ? 'selected' : ''; ?>>1 Month after Maturity</option>
                                                    <option value="03" <?php echo (@$salary['loyalty_incentive_pay_after_month'] == '3') ? 'selected' : ''; ?>>3 Month after Maturity</option>
                                                    <option value="06" <?php echo (@$salary['loyalty_incentive_pay_after_month'] == '6') ? 'selected' : ''; ?>>6 Month after Maturity</option>
                                                    <option value="12" <?php echo (@$salary['loyalty_incentive_pay_after_month'] == '12') ? 'selected' : ''; ?>>12 Month after Maturity</option>
                                                </select>
                                                <small class="text-muted">Amount will be paid after maturity time + selected value</small><br>
                                                <small class="text-danger error-text" id="loyalty_incentive_pay_after_month_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-8" id="loyalty_incentive_remarks_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="loyalty_incentive_remarks">Remarks</label>
                                                <textarea id="loyalty_incentive_remarks" class="form-control " name="loyalty_incentive_remarks" placeholder="Remarks"><?= set_value('loyalty_incentive_remarks', @$salary['loyalty_incentive_remarks']) ?></textarea>
                                                <small class="text-danger error-text" id="loyalty_incentive_amount_per_month_error"></small>
                                            </div>
                                        </div>

                                    </div>
                                    <hr class="my-7 opacity-10">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label">Others</label>
                                                <input type="text" id="other_benefits" class="form-control form-control-sm " name="other_benefits" placeholder="Other Benefits" value="<?= set_value('other_benefits', @$salary['other_benefits']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="other_benefits_error"></small>
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

                                        <div class="col-md-4" id="appraisal_date_container">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="appraisal_date">Affected From</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" id="appraisal_date" class="form-control form-control-sm  date_picker" name="appraisal_date" placeholder="From" value="<?= set_value('appraisals_from', @$salary['appraisals_from']) ?>" />
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar-alt"></i></span>
                                                </div>
                                                <small class="text-danger error-text" id="appraisal_date_error"></small>
                                            </div>
                                        </div>



                                        <div class="col-md-8" id="appraisal_remarks_container">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="appraisal_remarks">Remarks</label>
                                                <textarea id="appraisal_remarks" class="form-control " name="appraisal_remarks" placeholder="Remarks"><?= set_value('appraisal_remarks', @$salary['appraisal_remarks']) ?></textarea>
                                                <small class="text-danger error-text" id="appraisal_remarks_error"></small>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end">
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
        </form>

        <!--end::Col-->
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <table id="appraisals_table" class="table table-striped nowrap">
                <!-- <thead>
                    <tr>
                        <th class="text-center bg-white"><strong>Code</strong></th>
                        <th class="text-center bg-white"><strong>Appraisal Date</strong></th>

                        <th class="text-center"><strong>Basic Salary</strong></th>
                        <th class="text-center"><strong>HRA</strong></th>
                        <th class="text-center"><strong>Conveyance</strong></th>
                        <th class="text-center"><strong>Medical Allowance</strong></th>

                        <th class="text-center"><strong>Special Allowance</strong></th>
                        <th class="text-center"><strong>Fuel Allowance</strong></th>
                        <th class="text-center"><strong>Other Allowance</strong></th>


                        <th class="text-center bg-white"><strong>Action</strong></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="text-center bg-white"><strong>Code</strong></th>
                        <th class="text-center bg-white"><strong>Appraisal Date</strong></th>

                        <th class="text-center"><strong>Basic Salary</strong></th>
                        <th class="text-center"><strong>HRA</strong></th>
                        <th class="text-center"><strong>Conveyance</strong></th>
                        <th class="text-center"><strong>Medical Allowance</strong></th>

                        <th class="text-center"><strong>Special Allowance</strong></th>
                        <th class="text-center"><strong>Fuel Allowance</strong></th>
                        <th class="text-center"><strong>Other Allowance</strong></th>


                        <th class="text-center bg-white"><strong>Action</strong></th>
                    </tr>
                </tfoot> -->
                <thead>
                    <tr>
                        <th class="text-center bg-white"><strong>Emp Code</strong></th>
                        <th class="text-center bg-white"><strong>Appraisal Date</strong></th>
                        <th class="text-center bg-white"><strong>total_appraisal</strong></th>
                        <th class="text-center"><strong>Basic Salary</strong></th>
                        <th class="text-center"><strong>HRA</strong></th>
                        <th class="text-center"><strong>Conveyance</strong></th>
                        <th class="text-center"><strong>Medical Allowance</strong></th>
                        <th class="text-center"><strong>Special Allowance</strong></th>
                        <th class="text-center"><strong>Fuel Allowance</strong></th>
                        <th class="text-center"><strong>Other Allowance</strong></th>
                        <th class="text-center"><strong>Other Benefits</strong></th>
                        <th class="text-center"><strong>Gross Salary</strong></th>
                        <th class="text-center"><strong>CTC</strong></th>
                        <th class="text-center"><strong>Gratuity</strong></th>
                        <th class="text-center"><strong>Bonus</strong></th>
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
                        <th class="text-center bg-white"><strong>total_appraisal</strong></th>
                        <th class="text-center"><strong>Basic Salary</strong></th>
                        <th class="text-center"><strong>HRA</strong></th>
                        <th class="text-center"><strong>Conveyance</strong></th>
                        <th class="text-center"><strong>Medical Allowance</strong></th>
                        <th class="text-center"><strong>Special Allowance</strong></th>
                        <th class="text-center"><strong>Fuel Allowance</strong></th>
                        <th class="text-center"><strong>Other Allowance</strong></th>
                        <th class="text-center"><strong>Other Benefits</strong></th>
                        <th class="text-center"><strong>Gross Salary</strong></th>
                        <th class="text-center"><strong>CTC</strong></th>
                        <th class="text-center"><strong>Gratuity</strong></th>
                        <th class="text-center"><strong>Bonus</strong></th>
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
                        <p>Loading details...</p>
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

            $(document).on('input', '#update_appraisals input[type=text]', function(e) {
                $(this).parent().find('.error-text').html('');
                let current_salary_text = $(this).parent().find('span.input-group-text').last().text();
                if (current_salary_text.includes('Curr:')) {
                    let current_salary = 0;
                    let new_salary = 0;
                    let total_salary = 0;
                    let label_text = $(this).parent().parent().find('label').text();
                    current_salary = parseFloat(current_salary_text.split(':')[1].trim());
                    new_salary = parseFloat($(this).val());
                    if (isNaN(new_salary)) {
                        new_salary = 0;
                    }
                    if (isNaN(current_salary)) {
                        current_salary = 0;
                    }
                    total_salary = current_salary + new_salary;
                    $(this).parent().parent().find('.text-muted').next('.total-salary').remove();
                    $(this).parent().parent().find('.text-muted').after('<small class="text-info total-salary"> New ' + label_text + ': ' + total_salary + '</small>');
                }
                updateCTC();
            });

            $(document).on('input', '#basic_salary, #house_rent_allowance, #conveyance, #medical_allowance, #special_allowance, #fuel_allowance, #other_allowance, #other_benefits', function(e) {
                let help_text = $(this).parent().parent().find('.text-muted').first();
                if ($(this).val().length > 0) {
                    help_text.hide();
                } else {
                    help_text.show();
                }
            });
            $(document).on('change', '#update_appraisals select', function(e) {
                $(this).parent().find('.error-text').html('');
                updateCTC();
            });

            $(document).on('change', '#update_appraisals input[type=checkbox]', function(e) {
                $(this).parent().parent().find('.error-text').html('');
                updateCTC();
            });

            $(document).on('change', '#update_appraisals input#pf', function(e) {
                if ($(this).is(':checked')) {
                    $('#update_appraisals #pf_number_container').slideDown();
                    $('#update_appraisals #pf_employee_contribution_container').slideDown();
                    $('#update_appraisals #pf_employer_contribution_container').slideDown();
                } else {
                    $('#update_appraisals #pf_number_container').slideUp();
                    $('#update_appraisals #pf_employee_contribution_container').slideUp();
                    $('#update_appraisals #pf_employer_contribution_container').slideUp();
                }
                updateCTC();
            });

            $(document).on('change', '#update_appraisals input#esi', function(e) {
                if ($(this).is(':checked')) {
                    $('#update_appraisals #esi_number_container').slideDown();
                    $('#update_appraisals #esi_employee_contribution_container').slideDown();
                    $('#update_appraisals #esi_employer_contribution_container').slideDown();
                } else {
                    $('#update_appraisals #esi_number_container').slideUp();
                    $('#update_appraisals #esi_employee_contribution_container').slideUp();
                    $('#update_appraisals #esi_employer_contribution_container').slideUp();
                }
                updateCTC();
            });

            $(document).on('change', '#update_appraisals input#lwf', function(e) {
                if ($(this).is(':checked')) {
                    $('#update_appraisals #lwf_employee_contribution_container').slideDown();
                    $('#update_appraisals #lwf_employer_contribution_container').slideDown();
                    $('#update_appraisals #lwf_deduction_on_every_n_month_container').slideDown();
                } else {
                    $('#update_appraisals #lwf_employee_contribution_container').slideUp();
                    $('#update_appraisals #lwf_employer_contribution_container').slideUp();
                    $('#update_appraisals #lwf_deduction_on_every_n_month_container').slideUp();
                }
                updateCTC();
            });




            $(document).on('change', '#update_appraisals input#enable_bonus', function(e) {
                if ($(this).is(':checked')) {
                    $('#update_appraisals #bonus_container').slideDown();
                } else {
                    $('#update_appraisals #bonus_container').slideUp();
                }
                updateCTC();
            });




            $(document).on('change', '#update_appraisals input#tds', function(e) {
                if ($(this).is(':checked')) {
                    $('#update_appraisals #tds_amount_per_month_container').slideDown();
                    $('#update_appraisals #tds_preferred_slab_container').slideDown();
                    $('#update_appraisals #tds_slab_container').slideDown();
                    initialise_switch();
                } else {
                    $('#update_appraisals #tds_amount_per_month_container').slideUp();
                    $('#update_appraisals #tds_preferred_slab_container').slideUp();
                    $('#update_appraisals #tds_slab_container').slideUp();
                }
            });
            initialise_switch();

            function initialise_switch() {
                var toggleSwitch = $('.switch-toggle');
                toggleSwitch.each(function(index, thisSwitch) {
                    var checked_input = $(thisSwitch).find('label > input:checked').parent();
                    var w = checked_input.outerWidth();
                    var indexoflabel = checked_input.index();
                    $(thisSwitch).find('a').css({
                        'width': w,
                        'left': indexoflabel * w
                    });
                })
            }

            $(document).on('click', '.switch-toggle > label', function(e) {
                var w = $(this).outerWidth();
                $(this).find('input').prop('checked', true);
                $(this).parent().find('a').css({
                    'width': w,
                    'left': $(this).position().left
                });
            })

            $(document).on('change', '#update_appraisals input#appraisal_remarks', function(e) {
                if ($(this).is(':checked')) {
                    $('#update_appraisals #appraisals_from_container').slideDown();
                    $('#update_appraisals #appraisals_to_container').slideDown();
                    $('#update_appraisals #appraisals_amount_per_month_container').slideDown();
                    $('#update_appraisals #appraisal_remarks_container').slideDown();
                } else {
                    $('#update_appraisals #appraisals_from_container').slideUp();
                    $('#update_appraisals #appraisals_to_container').slideUp();
                    $('#update_appraisals #appraisals_amount_per_month_container').slideUp();
                    $('#update_appraisals #appraisal_remarks_container').slideUp();
                }
                updateCTC();
            });

            $(document).on('change', '#update_appraisals input#non_compete_loan', function(e) {
                if ($(this).is(':checked')) {
                    $('#update_appraisals #non_compete_loan_from_container').slideDown();
                    $('#update_appraisals #non_compete_loan_to_container').slideDown();
                    $('#update_appraisals #non_compete_loan_amount_per_month_container').slideDown();
                    $('#update_appraisals #non_compete_loan_remarks_container').slideDown();
                } else {
                    $('#update_appraisals #non_compete_loan_from_container').slideUp();
                    $('#update_appraisals #non_compete_loan_to_container').slideUp();
                    $('#update_appraisals #non_compete_loan_amount_per_month_container').slideUp();
                    $('#update_appraisals #non_compete_loan_remarks_container').slideUp();
                }
                updateCTC();
            });


            $(document).on('change', '#update_appraisals input#loyalty_incentive', function(e) {
                if ($(this).is(':checked')) {
                    $('#update_appraisals #loyalty_incentive_from_container').slideDown();
                    $('#update_appraisals #loyalty_incentive_to_container').slideDown();
                    $('#update_appraisals #loyalty_incentive_amount_per_month_container').slideDown();
                    $('#update_appraisals #loyalty_incentive_mature_after_month_container').slideDown();
                    $('#update_appraisals #loyalty_incentive_pay_after_month_container').slideDown();
                    $('#update_appraisals #loyalty_incentive_remarks_container').slideDown();
                } else {
                    $('#update_appraisals #loyalty_incentive_from_container').slideUp();
                    $('#update_appraisals #loyalty_incentive_to_container').slideUp();
                    $('#update_appraisals #loyalty_incentive_amount_per_month_container').slideUp();
                    $('#update_appraisals #loyalty_incentive_mature_after_month_container').slideUp();
                    $('#update_appraisals #loyalty_incentive_pay_after_month_container').slideUp();
                    $('#update_appraisals #loyalty_incentive_remarks_container').slideUp();
                }
                updateCTC();
            });



            function updateGrossSalary() {
                // Get all input values for increments
                let basic_salary_inc = parseFloat($('#basic_salary').val()) || 0;
                let house_rent_allowance_inc = parseFloat($('#house_rent_allowance').val()) || 0;
                let conveyance_inc = parseFloat($('#conveyance').val()) || 0;
                let medical_allowance_inc = parseFloat($('#medical_allowance').val()) || 0;
                let special_allowance_inc = parseFloat($('#special_allowance').val()) || 0;
                let fuel_allowance_inc = parseFloat($('#fuel_allowance').val()) || 0;
                let other_allowance_inc = parseFloat($('#other_allowance').val()) || 0;
                let other_benefits_inc = parseFloat($('#other_benefits').val()) || 0;

                let total_appraisal = basic_salary_inc + house_rent_allowance_inc + conveyance_inc + medical_allowance_inc + special_allowance_inc + fuel_allowance_inc + other_allowance_inc + other_benefits_inc;

                if ($('#non_compete_loan').is(':checked')) {
                    total_appraisal += parseFloat($('#non_compete_loan_amount_per_month').val()) || 0;
                }

                if ($('#loyalty_incentive').is(':checked')) {
                    total_appraisal += parseFloat($('#loyalty_incentive_amount_per_month').val()) || 0;
                }

                $('#total_appraisal_amount').text(Math.round(total_appraisal).toLocaleString('en-IN'));

                // Get current values from existing salary data
                let curr_basic_salary = parseFloat($('#basic_salary').parent().find('span.input-group-text').last().text().split(':')[1].trim()) || 0;
                let curr_house_rent_allowance = parseFloat($('#house_rent_allowance').parent().find('span.input-group-text').last().text().split(':')[1].trim()) || 0;
                let curr_conveyance = parseFloat($('#conveyance').parent().find('span.input-group-text').last().text().split(':')[1].trim()) || 0;
                let curr_medical_allowance = parseFloat($('#medical_allowance').parent().find('span.input-group-text').last().text().split(':')[1].trim()) || 0;
                let curr_special_allowance = parseFloat($('#special_allowance').parent().find('span.input-group-text').last().text().split(':')[1].trim()) || 0;
                let curr_fuel_allowance = parseFloat($('#fuel_allowance').parent().find('span.input-group-text').last().text().split(':')[1].trim()) || 0;
                let curr_other_allowance = parseFloat($('#other_allowance').parent().find('span.input-group-text').last().text().split(':')[1].trim()) || 0;
                //let curr_other_benefits = parseFloat($('#other_benefits').val()) || 0;

                let new_basic_salary = curr_basic_salary + basic_salary_inc;

                // Calculate new gross salary
                let new_gross_salary = (new_basic_salary) +
                    (curr_house_rent_allowance + house_rent_allowance_inc) +
                    (curr_conveyance + conveyance_inc) +
                    (curr_medical_allowance + medical_allowance_inc) +
                    (curr_special_allowance + special_allowance_inc) +
                    (curr_fuel_allowance + fuel_allowance_inc) +
                    (curr_other_allowance + other_allowance_inc);

                $('#real_time_gross_salary').text(Math.round(new_gross_salary).toLocaleString('en-IN'));
                const currGross = parseFloat($('#gross_salary').val()) || 0;
                let hikePct = 0;
                if (currGross > 0) {
                    hikePct = (total_appraisal / currGross) * 100;
                }

                const pctText = Number.isFinite(hikePct) ?
                    `(${(Math.round(hikePct) === hikePct ? hikePct.toFixed(0) : hikePct.toFixed(2))}%)` :
                    '(0%)';

                $('#total_appraisal_percent').text(pctText);

                return {
                    new_gross_salary: new_gross_salary,
                    new_basic_salary: new_basic_salary
                };
            }

            function updateCTC() {
                let salary_data = updateGrossSalary();
                let new_gross_salary = salary_data.new_gross_salary;
                let new_basic_salary = salary_data.new_basic_salary;

                let ctc = new_gross_salary;

                // Gratuity calculation
                let gratuity = Math.round(((new_basic_salary / 26) * 15) * (1 / 12));
                ctc += gratuity;

                // PF calculation
                if ($('#pf').is(':checked')) {
                    let pf_base_salary = (new_gross_salary >= 15000) ? 15000 : new_gross_salary;
                    let pf_employer_contribution = Math.round((pf_base_salary * 13) / 100);
                    ctc += pf_employer_contribution;
                }

                // ESI calculation
                if ($('#esi').is(':checked') && new_gross_salary <= 21000) {
                    let esi_employer_contribution = Math.round((new_gross_salary * 3.25) / 100);
                    ctc += esi_employer_contribution;
                }

                // LWF calculation
                if ($('#lwf').is(':checked')) {
                    let lwf_employee_contribution = ((new_gross_salary * 0.2) / 100 <= 31) ? (new_gross_salary * 0.2) / 100 : 31;
                    let lwf_employer_contribution = Math.round(lwf_employee_contribution * 2);
                    ctc += lwf_employer_contribution;
                }

                // Bonus calculation
                if ($('#enable_bonus').is(':checked')) {
                    // This would require fetching minimum wage data, for now we'll use a placeholder
                    let bonus = Math.round((new_gross_salary * 8.33) / 100);
                    ctc += bonus;
                }

                // Non-compete loan calculation
                if ($('#non_compete_loan').is(':checked')) {
                    let non_compete_loan_amount = parseFloat($('#non_compete_loan_amount_per_month').val()) || 0;
                    ctc += non_compete_loan_amount;
                }

                // Loyalty incentive calculation
                if ($('#loyalty_incentive').is(':checked')) {
                    let loyalty_incentive_amount = parseFloat($('#loyalty_incentive_amount_per_month').val()) || 0;
                    ctc += loyalty_incentive_amount;
                }

                // EL and CL calculation
                let monthly_el = Math.round(new_basic_salary / 30 * 1.25);
                let monthly_cl = Math.round(new_gross_salary / 30 * 1);
                ctc += monthly_el + monthly_cl;

                // Update the real-time CTC display
                $('#real_time_ctc').text(Math.round(ctc).toLocaleString('en-IN'));
            }

            // Call the function on page load to initialize the CTC
            updateCTC();

            $(document).on('submit', '#update_appraisals', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/master/appraisals/validate'); ?>",
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

            var tds_records_table = $("#tds_records_table").DataTable({
                "ajax": {
                    url: "<?= base_url('ajax/backend/master/tds-master/get/' . $salary["employee_id"]) ?>",
                    type: "POST",
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                    dataSrc: "data",
                },
                "columns": [{
                        data: "year_month"
                    },
                    {
                        data: "deduction_amount",
                        render: function(data, type, row, meta) {
                            return "₹ " + data;
                        }
                    },
                    {
                        data: "actions",
                        render: function(data, type, row, meta) {
                            if (row.salary_disbursed == 'yes') {
                                return '<span>Locked</span><br><small>Salary Disbursed</small>';
                            } else {
                                return '<div class="d-flex justify-content-center">' +
                                    '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-tds-record" data-id="' + row.id + '">' +
                                    '<span class="svg-icon svg-icon-3">' +
                                    '<i class="fas fa-trash"></i>' +
                                    '</span>' +
                                    '</a>' +
                                    '</div>';
                            }
                        }
                    },
                ],
                "order": [],
                "scrollX": true,
                "scrollY": '400px',
                "scrollCollapse": true,
                "paging": false,
                "bInfo": false,
                "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                }, ]
            });
            $("#tds_salary_month").flatpickr({
                minDate: "<?php echo $last_month_salary_disbursed == 'no' ? date('Y-m-01', strtotime('first day of last month')) : date('Y-m-01'); ?>",
                maxDate: "<?php echo date('Y-m-t'); ?>",
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "F Y",
                        altFormat: "F Y",
                        theme: "dark",
                    })
                ]
            });
            $(document).on('click', '#add_tds_master_record_submit_button', function(e) {
                e.preventDefault();
                var submitButton = $(this);
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('#add_tds_master_record_modal').modal('hide');
                var data = {
                    'tds_employee_id': $("#tds_employee_id").val(),
                    'tds_salary_month': $("#tds_salary_month").val(),
                    'tds_deduction_amount': $("#tds_deduction_amount").val()
                };
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('ajax/backend/master/tds-master/add'); ?>",
                    data: data,
                    // processData: false,
                    // contentType: false,
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
                                    $('#add_tds_master_record_modal').modal('show');
                                    if (typeof response.response_data.validation != 'undefined') {
                                        var validation = response.response_data.validation;
                                        $.each(validation, function(index, value) {
                                            $('#' + index + '_error').html(value);
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
                                    $("#add_tds_master_record_modal").modal('hide');
                                    $("#tds_records_table").DataTable().ajax.reload();
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
                        }).then(function(e) {
                            $('#add_tds_master_record_modal').modal('show');
                            submitButton.removeAttr("data-kt-indicator");
                            submitButton.removeAttr("disabled");
                        })
                    }
                })
            })
            $(document).on('click', '.delete-tds-record', function(e) {
                e.preventDefault();
                var tds_record_id = $(this).data('id');
                var data = {
                    'tds_record_id': tds_record_id,
                };

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    customClass: {
                        confirmButton: "btn btn-sm btn-primary",
                        cancelButton: "btn btn-sm btn-secondary"
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "post",
                            url: "<?php echo base_url('ajax/backend/master/tds-master/delete'); ?>",
                            data: data,
                            success: function(response) {
                                console.log(response);
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
                                        })
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
                                        }).then(function() {
                                            $("#tds_records_table").DataTable().ajax.reload();
                                        })
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
                            }
                        })
                    }
                })
            })













            // var appraisals_table = $("#appraisals_table").DataTable({

            //     "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3 mb-md-0"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end php-pagination-container"p>>>>',
            //     // "buttons": [
            //     //     {
            //     //         extend: 'pdfHtml5',
            //     //         text: '<i class="fa fa-download"></i> Download PDF',
            //     //         className: 'btn btn-sm btn-primary',
            //     //         title: 'Appraisals Report',
            //     //         exportOptions: {
            //     //             columns: ':visible:not(.exclude)', 
            //     //         },
            //     //     }
            //     // ],
            //     "buttons": [{
            //         text: '<i class="fa fa-download"></i> Download PDF',
            //         className: 'btn btn-sm btn-primary',
            //         action: function() {
            //             generatePDF();
            //         }
            //     }],

            //     "ajax": {
            //         url: "<?= base_url('/ajax/get-appraisals-table-by-empid') ?>",
            //         type: "POST",
            //         data: {
            //             filter: function() {
            //                 // console.log($('#filter_form').serialize());
            //                 return $('#filter_form').serialize();
            //             }
            //         },
            //         error: function(jqXHR, ajaxOptions, thrownError) {
            //             console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            //         },
            //         dataSrc: "",
            //     },
            //     "deferRender": true,
            //     "processing": true,
            //     "language": {
            //         processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
            //         emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
            //         searchPlaceholder: "Search"
            //     },
            //     "oLanguage": {
            //         "sSearch": ""
            //     },
            //     "columns": [{
            //             data: "internal_employee_id",
            //             render: function(data, type, row, meta) {
            //                 return row.first_name + " " + row.last_name + ": " + row.internal_employee_id;
            //             }

            //         },

            //         {
            //             data: "appraisal_date"
            //         },
            //         {
            //             data: "basic_salary"
            //         },
            //         {
            //             data: "house_rent_allowance"
            //         },
            //         {
            //             data: "conveyance"
            //         },
            //         {
            //             data: "medical_allowance"
            //         },
            //         {
            //             data: "special_allowance"
            //         },
            //         {
            //             data: "fuel_allowance"
            //         },
            //         {
            //             data: "other_allowance"
            //         },



            //         {
            //             data: "id",
            //             render: function(data, type, row, meta) {

            //                 var view_appraisals_button = '<a href="#" class="btn btn-sm btn-info view-appraisals" data-id="' + row.id + '">' +
            //                     '<span class="svg-icon svg-icon-3 m-0">' +
            //                     '<i class="fa-solid fa-money-bill-transfer"></i> <small>View Appraisals</small>' +
            //                     '</span>' +
            //                     '</a>';

            //                 var edit_appraisals_button = '<a href="<?= base_url('/backend/master/appraisals/edit/') ?>' + row.id + '" class="btn btn-sm btn-warning edit-appraisals" data-id="' + row.id + '">' +
            //                     '<span class="svg-icon svg-icon-3 m-0">' +
            //                     '<i class="fa fa-times" aria-hidden="true" ></i> <small>Edit</small>' +
            //                     '</span>' +
            //                     '</a>';
            //                 var deleted_appraisals_button = '<a href="javascript:void(0);" class="btn btn-sm btn-danger delete-appraisals" data-id="' + row.id + '">' +
            //                     '<span class="svg-icon svg-icon-3 m-0">' +
            //                     '<i class="fa fa-times" aria-hidden="true" ></i> <small>Delete</small>' +
            //                     '</span>' +
            //                     '</a>';

            //                 return '<div class="d-flex flex-column"><div class="btn-group mb-2">' + view_appraisals_button + '</div><div class="btn-group">' + edit_appraisals_button + deleted_appraisals_button + '</div></div>';
            //             }
            //         },
            //     ],
            //     "fixedColumns": {
            //         left: 2,
            //         right: 1
            //     },
            //     "order": [],
            //     "scrollX": true,
            //     "scrollY": '50vh',
            //     "paging": false,
            //     "columnDefs": [{
            //             "className": 'border-start border-secondary td-border-left text-center',
            //             "targets": [-1]
            //         },
            //         {
            //             "className": 'border-end border-secondary td-border-right text-center',
            //             "targets": [1]
            //         },
            //         {
            //             "className": 'text-center',
            //             "targets": '_all'
            //         },
            //     ],

            // });
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
                    url: "<?= base_url('/ajax/get-appraisals-table-by-empid') ?>",
                    type: "POST",
                    data: {
                        filter: function() {
                            // console.log($('#filter_form').serialize());
                            return $('#filter_form').serialize();
                        }
                    },
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
                        data: "internal_employee_id",
                        render: function(data, type, row) {
                            return (row.first_name || '') + " " + (row.last_name || '') + ": " + (row.internal_employee_id || '');
                        }
                    },
                    {
                        data: "appraisal_date"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(d, type, row) {
                            const n = v => (v === null || v === undefined || v === '') ? 0 : parseFloat(v) || 0;

                            // Sum of all increment components
                            let total =
                                n(row.basic_salary) +
                                n(row.house_rent_allowance) +
                                n(row.conveyance) +
                                n(row.medical_allowance) +
                                n(row.special_allowance) +
                                n(row.fuel_allowance) +
                                n(row.other_allowance) +
                                n(row.other_benefits);

                            // Optional monthly add-ons if enabled
                            if (row.non_compete_loan === 'yes') {
                                total += n(row.non_compete_loan_amount_per_month);
                            }
                            if (row.loyalty_incentive === 'yes') {
                                total += n(row.loyalty_incentive_amount_per_month);
                            }

                            // Sort by the raw number, display as INR
                            if (type === 'sort' || type === 'type') return total;
                            return inr(total); // uses your helper from earlier
                        }
                    },


                    {
                        data: "basic_salary",
                        render: inr
                    },
                    {
                        data: "house_rent_allowance",
                        render: inr
                    },
                    {
                        data: "conveyance",
                        render: inr
                    },
                    {
                        data: "medical_allowance",
                        render: inr
                    },
                    {
                        data: "special_allowance",
                        render: inr
                    },
                    {
                        data: "fuel_allowance",
                        render: inr
                    },
                    {
                        data: "other_allowance",
                        render: inr
                    },
                    {
                        data: "other_benefits",
                        render: inr
                    }, // <-- new

                    {
                        data: "gross_salary",
                        render: inr
                    }, // <-- new
                    {
                        data: "ctc",
                        render: inr
                    }, // <-- new
                    {
                        data: "gratuity",
                        render: inr
                    }, // <-- new

                    {
                        data: "enable_bonus",
                        render: v => yesNo(v),
                        className: "text-center"
                    }, // <-- new
                    {
                        data: "pf",
                        render: (v, t, row) => yesNo(v) + (row.pf_number ? `<br><small>${row.pf_number}</small>` : ''),
                        className: "text-center"
                    }, // <-- new
                    {
                        data: "esi",
                        render: (v, t, row) => yesNo(v) + (row.esi_number ? `<br><small>${row.esi_number}</small>` : ''),
                        className: "text-center"
                    }, // <-- new
                    {
                        data: "lwf",
                        render: v => yesNo(v),
                        className: "text-center"
                    }, // <-- new

                    { // Non-Compete (compact)  <-- new
                        data: null,
                        className: "text-center",
                        render: function(d, t, row) {
                            if (row.non_compete_loan !== 'yes') return '<span class="badge badge-light">No</span>';
                            const amt = row.non_compete_loan_amount_per_month ? inr(row.non_compete_loan_amount_per_month) : '-';
                            const from = row.non_compete_loan_from || '';
                            const to = row.non_compete_loan_to || '';
                            return `<span class="badge badge-light-success">Yes</span><br><small>${amt}/mo</small><br><small>${from}${to ? ' → '+to : ''}</small>`;
                        }
                    },
                    { // Loyalty (compact)  <-- new
                        data: null,
                        className: "text-center",
                        render: function(d, t, row) {
                            if (row.loyalty_incentive !== 'yes') return '<span class="badge badge-light">No</span>';
                            const amt = row.loyalty_incentive_amount_per_month ? inr(row.loyalty_incentive_amount_per_month) : '-';
                            return `<span class="badge badge-light-success">Yes</span><br><small>${amt}/mo</small>`;
                        }
                    },

                    { // Actions (unchanged)
                        data: "id",
                        orderable: false,
                        render: function(id, type, row, meta) {
                            var viewBtn = '<a href="#" class="btn btn-sm btn-info view-appraisals" data-id="' + id + '">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa-solid fa-money-bill-transfer"></i> <small>View</small>' +
                                '</span></a>';
                            var editBtn = '<a href="<?= base_url('/backend/master/appraisals/edit/') ?>' + id + '" class="btn btn-sm btn-warning edit-appraisals">' +
                                '<span class="svg-icon svg-icon-3 m-0">' +
                                '<i class="fa fa-pen"></i> <small>Edit</small>' +
                                '</span></a>';
                            var delBtn = '';
                            if (meta.row !== 0) {
                                delBtn = '<a href="javascript:void(0);" class="btn btn-sm btn-danger delete-appraisals" data-id="' + id + '">' +
                                    '<span class="svg-icon svg-icon-3 m-0">' +
                                    '<i class="fa fa-times"></i> <small>Delete</small>' +
                                    '</span></a>';
                            }
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

            $('#appraisalsDetails').html('<p>Loading details...</p>');
            $.ajax({
                url: "<?php echo base_url('/ajax/master/appraisals/getAppraisalDetails'); ?>",
                type: 'POST',
                data: {
                    id: appraisalId
                },
                success: function(response) {
                    if (response.status === 'success') {

                        var details = `
                    <div class="card-body border-bottom row g-3">
    <div class="col-md-4">
        <label class="floating-label">Employee ID</label>
        <span class="form-control form-control-sm border-dashed" id="employee_id_view">
            ${response.data.internal_employee_id}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Name</label>
        <span class="form-control form-control-sm border-dashed" id="employee_name_view">
            ${response.data.first_name} ${response.data.last_name}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Appraisal Date</label>
        <span class="form-control form-control-sm border-dashed" id="appraisal_date_view">
            ${response.data.appraisal_date}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Appraisal Remarks</label>
        <span class="form-control form-control-sm border-dashed" id="appraisal_remarks_view">
            ${response.data.appraisal_remarks || 'N/A'}
        </span>
    </div>
</div>

<div class="card-body border-bottom row g-3">
    <!-- Salary Information -->
    <div class="col-md-4">
        <label class="floating-label">CTC</label>
        <span class="form-control form-control-sm border-dashed" id="ctc_view">
            ${response.data.ctc}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Gross Salary</label>
        <span class="form-control form-control-sm border-dashed" id="gross_salary_view">
            ${response.data.gross_salary}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Basic Salary</label>
        <span class="form-control form-control-sm border-dashed" id="basic_salary_view">
            ${response.data.basic_salary}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">HRA</label>
        <span class="form-control form-control-sm border-dashed" id="hra_view">
            ${response.data.house_rent_allowance}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Conveyance</label>
        <span class="form-control form-control-sm border-dashed" id="conveyance_view">
            ${response.data.conveyance}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Medical Allowance</label>
        <span class="form-control form-control-sm border-dashed" id="medical_allowance_view">
            ${response.data.medical_allowance}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Special Allowance</label>
        <span class="form-control form-control-sm border-dashed" id="special_allowance_view">
            ${response.data.special_allowance}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Other Allowance</label>
        <span class="form-control form-control-sm border-dashed" id="other_allowance_view">
            ${response.data.other_allowance}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Others </label>
        <span class="form-control form-control-sm border-dashed" id="other_benefits_view">
                     ${response.data.other_benefits}
        </span>
    </div>
</div>

<div class="card-body border-bottom row g-3">
    <!-- Additional Information -->
    <div class="col-md-4">
        <label class="floating-label">Bonus</label>
        <span class="form-control form-control-sm border-dashed" id="bonus_view">
            ${response.data.enable_bonus === 'yes' ? 'Enabled' : 'Disabled'}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Loyalty Incentive</label>
        <span class="form-control form-control-sm border-dashed" id="loyalty_incentive_view">
            ${response.data.loyalty_incentive || 'N/A'}
        </span>
    </div>
    <div class="col-md-4">
        <label class="floating-label">Remarks</label>
        <span class="form-control form-control-sm border-dashed" id="loyalty_incentive_remarks_view">
            ${response.data.loyalty_incentive_remarks || 'N/A'}
        </span>
    </div>
</div>

                `;
                        $('#appraisalsDetails').html(details);
                        var editUrl = '<?= base_url("/backend/master/appraisals/edit/"); ?>' + appraisalId;
                        $('#editAppraisalsButton').attr('href', editUrl);
                    } else {
                        $('#appraisalsDetails').html('<p>Error loading details.</p>');
                    }
                },
                error: function() {
                    $('#appraisalsDetails').html('<p>Failed to load details.</p>');
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
                    url: "<?= base_url('/ajax/master/appraisals/delete'); ?>",
                    type: 'POST',
                    data: {
                        id: appraisalId
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