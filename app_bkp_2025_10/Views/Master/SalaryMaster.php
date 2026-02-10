<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<style>
    /* Chrome, Safari, Edge, Opera */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none !important;
        margin: 0 !important;
    }

    /* Firefox */

    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
    }

    .flatpickr-monthSelect-month.flatpickr-disabled {
        opacity: 0.4 !important;
    }

    table.dataTable>tbody>tr>td.dataTables_empty {
        height: unset !important;
    }
</style>
<!--begin::Row-->
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-12">

        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label" for="employee_name" class="mb-3">Employee Name</label>
                        <select class="form-select " id="employee_name" data-control="select2" data-placeholder="Select an Employee">
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
        </div>
        <style>
            .accordion-header.collapsed {
                border-radius: calc(-1px + 0.475rem) !important;
            }
        </style>
        <form id="update_salary" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="accordion accordion-icon-toggle rounded border mb-5">
                        <div class="card">
                            <div class="card-header accordion-header bg-white" data-bs-toggle="collapse" data-bs-target="#general_information">
                                <div class="card-title">
                                    General
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
                                                <small class="text-danger error-text" id="employee_id_error"></small><br>
                                                <input type="hidden" id="salary_id" name="salary_id" value="<?= set_value('salary_id', @$salary['id']) ?>" />
                                                <small class="text-danger error-text" id="salary_id_error"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="ctc"><span>CTC:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span> <?php echo @$salary['ctc']; ?></span></label>
                                                <input type="hidden" id="ctc" name="ctc" value="<?= set_value('ctc', @$salary['ctc']) ?>" />
                                                <small class="text-muted">Includes 1.25 EL and 1 CL</small><br>
                                                <small class="text-danger error-text" id="ctc_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="gross_salary"><span>Gross Salary:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span> <?php echo @$salary['gross_salary']; ?></span></label>
                                                <input type="hidden" id="gross_salary" name="gross_salary" value="<?= set_value('gross_salary', @$salary['gross_salary']) ?>" />
                                                <small class="text-danger error-text" id="gross_salary_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="basic_salary">Basic Salary</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="basic_salary" class="form-control form-control-sm " name="basic_salary" placeholder="Basic Salary" value="<?= set_value('basic_salary', @$salary['basic_salary']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
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
                                                    <input type="text" id="house_rent_allowance" class="form-control form-control-sm " name="house_rent_allowance" placeholder="HRA" value="<?= set_value('house_rent_allowance', @$salary['house_rent_allowance']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
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
                                                    <input type="text" id="conveyance" class="form-control form-control-sm " name="conveyance" placeholder="Conveyance" value="<?= set_value('conveyance', @$salary['conveyance']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
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
                                                    <input type="text" id="medical_allowance" class="form-control form-control-sm " name="medical_allowance" placeholder="Medical Allowance" value="<?= set_value('medical_allowance', @$salary['medical_allowance']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
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
                                                    <input type="text" id="special_allowance" class="form-control form-control-sm " name="special_allowance" placeholder="Special Allowance" value="<?= set_value('special_allowance', @$salary['special_allowance']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
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
                                                    <input type="text" id="fuel_allowance" class="form-control form-control-sm " name="fuel_allowance" placeholder="Fuel Allowance" value="<?= set_value('fuel_allowance', @$salary['fuel_allowance']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="fuel_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="vacation_allowance">Vacation Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="vacation_allowance" class="form-control form-control-sm " name="vacation_allowance" placeholder="Vacation Allowance" value="<?= set_value('vacation_allowance', @$salary['vacation_allowance']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="vacation_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="other_allowance">Other Allowance</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="other_allowance" class="form-control form-control-sm " name="other_allowance" placeholder="Other Allowance" value="<?= set_value('other_allowance', @$salary['other_allowance']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="other_allowance_error"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class=" form-label" for="gratuity">Gratuity</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text" style="background-color: #d4eeff;"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <span class="form-control form-control-sm" style="background-color: #d4eeff;">((BasicSalary/26)*15)*(1/12)</span>
                                                </div>
                                                <small class="text-muted">Per month</small><br>
                                                <small class="text-danger error-text" id="gratuity_error"></small>
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
                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="enable_bonus" name="enable_bonus" <?php echo (isset($salary['enable_bonus']) && $salary['enable_bonus'] == 'yes') ? 'checked' : ''; ?>>
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

                                        <div class="col-md-4" id="pf_employee_contribution_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
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
                                        </div>
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

                                        <div class="col-md-4" id="esi_employee_contribution_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
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
                                        </div>
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

                                        <!-- <div class="col-md-4" id="lwf_deduction_on_every_n_month_container" style="display: <?php echo (@$salary['lwf'] == 'yes') ? 'block' : 'none'; ?>">
                                                <div class="form-group mb-3">
                                                    <label class=" form-label" for="lwf_deduction_on_every_n_month">Deduction every N month</label>
                                                    <select class="form-select form-select-sm" id="lwf_deduction_on_every_n_month" name="lwf_deduction_on_every_n_month" data-control="select2" data-placeholder="Select deduction month" onchange="$('#lwf_deduction_on_every_n_month_error').html('')">
                                                        <option></option>
                                                        <option value="01" <?php #echo (@$salary['lwf_deduction_on_every_n_month'] == '01') ? 'selected' : ''; 
                                                                            ?> >Monthly</option>
                                                        <option value="03" <?php #echo (@$salary['lwf_deduction_on_every_n_month'] == '03') ? 'selected' : ''; 
                                                                            ?> >Quarterly</option>
                                                        <option value="06" <?php #echo (@$salary['lwf_deduction_on_every_n_month'] == '06') ? 'selected' : ''; 
                                                                            ?> >Half Yearly</option>
                                                        <option value="12" <?php #echo (@$salary['lwf_deduction_on_every_n_month'] == '12') ? 'selected' : ''; 
                                                                            ?> >Yearly</option>
                                                    </select>
                                                    <small class="text-danger error-text" id="lwf_deduction_on_every_n_month_error"></small>
                                                </div>
                                            </div> -->
                                    </div>

                                    <!-- <hr class="my-7 opacity-10">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class=" form-label">TDS</label>
                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                        <label class="form-check-label me-3" for="tds">
                                                            No
                                                        </label>
                                                        <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="tds" name="tds" <?php echo (@$salary['tds'] == 'yes') ? 'checked' : ''; ?> >
                                                        <label class="form-check-label" for="tds">
                                                            Yes
                                                        </label>
                                                    </div>
                                                    <small class="text-danger error-text" id="tds_error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="tds_amount_per_month_container" style="display: <?php echo (@$salary['tds'] == 'yes') ? 'block' : 'none'; ?>">
                                                <div class="form-group mb-3">
                                                    <label class=" form-label" for="tds_amount_per_month">Amount per month</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                        <input type="number" step="0.01" min="0" id="tds_amount_per_month" class="form-control form-control-sm " name="tds_amount_per_month" placeholder="Amount per month" value="<?= set_value('tds_amount_per_month', @$salary['tds_amount_per_month']) ?>" oninput="$('#tds_amount_per_month_error').html('')"/>
                                                    </div>
                                                    <small class="text-muted">Max 10 digits</small><br>
                                                    <small class="text-danger error-text" id="tds_amount_per_month_error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="tds_preferred_slab_container" style="display: <?php echo (@$salary['tds'] == 'yes') ? 'block' : 'none'; ?>">
                                                <div class="form-group mb-3">
                                                    <label class=" form-label" for="tds_preferred_slab">Select Slab</label>
                                                    <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                        <label for="tds_preferred_slab_old" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            Old
                                                            <input type="radio" name="tds_preferred_slab" class="opacity-0 position-absolute" id="tds_preferred_slab_old" value="old" <?php if (empty(@$salary['tds_preferred_slab']) || @$salary['tds_preferred_slab'] == 'old') {
                                                                                                                                                                                            echo 'checked';
                                                                                                                                                                                        } ?>>
                                                        </label>
                                                        <label for="tds_preferred_slab_new" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                                            New
                                                            <input type="radio" name="tds_preferred_slab" class="opacity-0 position-absolute" id="tds_preferred_slab_new" value="new" <?php if (@$salary['tds_preferred_slab'] == 'new') {
                                                                                                                                                                                            echo 'checked';
                                                                                                                                                                                        } ?> >
                                                        </label>
                                                        <a class="bg-success form-control form-control-sm p-0 position-absolute"></a>
                                                    </div>
                                                    <small class="text-danger error-text" id="tds_preferred_slab_error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12" id="tds_slab_container" style="display: <?php echo (@$salary['tds'] == 'yes') ? 'block' : 'none'; ?>">
                                                <h3 class="text-center"><strong>Slab Rates</strong></h3>
                                                <ul class="list-group list-group-horizontal">
                                                    <li class="list-group-item flex-fill">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item text-center"><strong>Old Slab</strong></li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Up to ₹ 2,50,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">Nil</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                ₹ 2,50,001 - ₹ 5,00,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">5% above ₹ 2,50,000</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                ₹ 5,00,001 - ₹ 10,00,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">₹ 12,500 + 20% above ₹ 5,00,000</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Above ₹ 10,00,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">₹ 1,12,500 + 30% above ₹ 10,00,000</span>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="list-group-item flex-fill">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item text-center"><strong>New Slab</strong></li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Up to ₹ 2,50,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">Nil</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                ₹ 2,50,001 - ₹ 5,00,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">5% above ₹ 2,50,000</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                ₹ 5,00,001 - ₹ 7,50,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">₹ 12,500 + 10% above ₹ 5,00,000</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                ₹ 7,50,001 - ₹ 10,00,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">₹ 37,500 + 15% above ₹ 7,50,000</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                ₹ 10,00,001 - ₹ 12,50,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">₹ 75,000 + 20% above ₹ 10,00,000</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                ₹ 12,50,001 - ₹ 15,00,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">₹ 1,25,000 + 25% above ₹ 12,50,000</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Above ₹ 15,00,000
                                                                <span class="ms-3 badge bg-light-primary text-primary rounded-pill">₹ 1,87,500 + 30% above ₹ 15,00,000</span>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div> -->

                                    <!--begin::TDS New-->
                                    <hr class="my-2 mt-7 opacity-10">
                                    <hr class="my-2 opacity-10">
                                    <hr class="my-2 opacity-10">
                                    <hr class="my-2 mb-7 opacity-10">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card mb-0" style="border: 1px solid #e3e3e3;">
                                                <div class="card-header">
                                                    <h5 class="card-title">TDS Records month wise</h5>
                                                    <div class="card-toolbar">
                                                        <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_tds_master_record_modal">
                                                            <i class="fa fa-plus"></i> Add TDS Records
                                                        </a>
                                                        <div class="modal fade" tabindex="-1" id="add_tds_master_record_modal">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Add TDS Record</h5>
                                                                        <!--begin::Close-->
                                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                            <span class="svg-icon svg-icon-2x">
                                                                                <i class="fa fa-times"></i>
                                                                            </span>
                                                                        </div>
                                                                        <!--end::Close-->
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="required form-label">Salary Month</label>
                                                                                <input type="text" id="tds_salary_month" name="tds_salary_month" class="form-control form-control-solid" placeholder="Salary Month" value="" oninput="$(this).next().html('')" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="tds_salary_month_error"></span>
                                                                                <!--end::Error Message-->
                                                                                <input type="hidden" name="tds_employee_id" id="tds_employee_id" value="<?= @$salary["employee_id"] ?>" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="tds_employee_id_error"></span>
                                                                                <!--end::Error Message-->
                                                                            </div>
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="required form-label">Deduction Amount</label>
                                                                                <input type="number" id="tds_deduction_amount" name="tds_deduction_amount" class="form-control form-control-solid" placeholder="Deduction Amount" value="" oninput="$(this).next().html('')" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="tds_deduction_amount_error"></span>
                                                                                <!--end::Error Message-->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                                                        <a href="#" id="add_tds_master_record_submit_button" class="btn btn-sm btn-primary">
                                                                            <span class="indicator-label">Add Record</span>
                                                                            <span class="indicator-progress">
                                                                                Please wait...
                                                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body px-0">
                                                    <table id="tds_records_table" class="table rounded table-row-bordered table-striped table-row-gray-100 align-middle gs-0 gy-3 mb-0 text-center">
                                                        <thead>
                                                            <tr>
                                                                <th><strong>Month</strong></th>
                                                                <th><strong>Amount</strong></th>
                                                                <th><strong>Actions</strong></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <div class="card-footer">
                                                    <p class="mb-0">Accounts team will decide how much TDS will be deducted for every month individualy</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--begin::TDS New-->

                                    <!--begin::Imprest-->
                                    <hr class="my-7 opacity-10">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card mb-0" style="border: 1px solid #e3e3e3;">
                                                <div class="card-header">
                                                    <h5 class="card-title">IMPREST Records month wise</h5>
                                                    <div class="card-toolbar">
                                                        <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_imprest_master_record_modal">
                                                            <i class="fa fa-plus"></i> Add IMPREST Records
                                                        </a>
                                                        <div class="modal fade" tabindex="-1" id="add_imprest_master_record_modal">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Add IMPREST Record</h5>
                                                                        <!--begin::Close-->
                                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                            <span class="svg-icon svg-icon-2x">
                                                                                <i class="fa fa-times"></i>
                                                                            </span>
                                                                        </div>
                                                                        <!--end::Close-->
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="required form-label">Salary Month</label>
                                                                                <input type="text" id="imprest_salary_month" name="imprest_salary_month" class="form-control form-control-solid" placeholder="Salary Month" value="" oninput="$(this).next().html('')" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="imprest_salary_month_error"></span>
                                                                                <!--end::Error Message-->
                                                                                <input type="hidden" name="imprest_employee_id" id="imprest_employee_id" value="<?= @$salary["employee_id"] ?>" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="imprest_employee_id_error"></span>
                                                                                <!--end::Error Message-->
                                                                            </div>
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="required form-label">Deduction Amount</label>
                                                                                <input type="number" id="imprest_deduction_amount" name="imprest_deduction_amount" class="form-control form-control-solid" placeholder="Deduction Amount" value="" oninput="$(this).next().html('')" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="imprest_deduction_amount_error"></span>
                                                                                <!--end::Error Message-->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                                                        <a href="#" id="add_imprest_master_record_submit_button" class="btn btn-sm btn-primary">
                                                                            <span class="indicator-label">Add Record</span>
                                                                            <span class="indicator-progress">
                                                                                Please wait...
                                                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body px-0">
                                                    <table id="imprest_records_table" class="table rounded table-row-bordered table-striped table-row-gray-100 align-middle gs-0 gy-3 mb-0 text-center">
                                                        <thead>
                                                            <tr>
                                                                <th><strong>Month</strong></th>
                                                                <th><strong>Amount</strong></th>
                                                                <th><strong>Actions</strong></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <div class="card-footer">
                                                    <p class="mb-0">Accounts team will decide how much IMPREST will be deducted for every month individualy</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Imprest-->

                                    <!--begin::Phone bill deduction-->
                                    <hr class="my-7 opacity-10">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card mb-0" style="border: 1px solid #e3e3e3;">
                                                <div class="card-header">
                                                    <h5 class="card-title">Phone Bill Deduction month wise</h5>
                                                    <div class="card-toolbar">
                                                        <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_phone_bill_master_record_modal">
                                                            <i class="fa fa-plus"></i> Add Phone Bill
                                                        </a>
                                                        <div class="modal fade" tabindex="-1" id="add_phone_bill_master_record_modal">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Add Phone Bill Record</h5>
                                                                        <!--begin::Close-->
                                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                            <span class="svg-icon svg-icon-2x">
                                                                                <i class="fa fa-times"></i>
                                                                            </span>
                                                                        </div>
                                                                        <!--end::Close-->
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="required form-label">Salary Month</label>
                                                                                <input type="text" id="phone_bill_salary_month" name="phone_bill_salary_month" class="form-control form-control-solid" placeholder="Salary Month" value="" oninput="$(this).next().html('')" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="phone_bill_salary_month_error"></span>
                                                                                <!--end::Error Message-->
                                                                                <input type="hidden" name="phone_bill_employee_id" id="phone_bill_employee_id" value="<?= @$salary["employee_id"] ?>" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="phone_bill_employee_id_error"></span>
                                                                                <!--end::Error Message-->
                                                                            </div>
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="required form-label">Deduction Amount</label>
                                                                                <input type="number" id="phone_bill_deduction_amount" name="phone_bill_deduction_amount" class="form-control form-control-solid" placeholder="Deduction Amount" value="" oninput="$(this).next().html('')" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="phone_bill_deduction_amount_error"></span>
                                                                                <!--end::Error Message-->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                                                        <a href="#" id="add_phone_bill_master_record_submit_button" class="btn btn-sm btn-primary">
                                                                            <span class="indicator-label">Add Record</span>
                                                                            <span class="indicator-progress">
                                                                                Please wait...
                                                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body px-0">
                                                    <table id="phone_bill_records_table" class="table rounded table-row-bordered table-striped table-row-gray-100 align-middle gs-0 gy-3 mb-0 text-center">
                                                        <thead>
                                                            <tr>
                                                                <th><strong>Month</strong></th>
                                                                <th><strong>Amount</strong></th>
                                                                <th><strong>Actions</strong></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <div class="card-footer">
                                                    <p class="mb-0">Accounts team will decide how much Phone Bill will be deducted for every month individualy</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Phone bill deduction-->

                                    <!--begin::Voucher Entry-->
                                    <hr class="my-7 opacity-10">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card mb-0" style="border: 1px solid #e3e3e3;">
                                                <div class="card-header">
                                                    <h5 class="card-title">Voucher Entry month wise</h5>
                                                    <div class="card-toolbar">
                                                        <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_voucher_record_modal">
                                                            <i class="fa fa-plus"></i> Add Voucher Record
                                                        </a>
                                                        <div class="modal fade" tabindex="-1" id="add_voucher_record_modal">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Add Voucher Record</h5>
                                                                        <!--begin::Close-->
                                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                            <span class="svg-icon svg-icon-2x">
                                                                                <i class="fa fa-times"></i>
                                                                            </span>
                                                                        </div>
                                                                        <!--end::Close-->
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="required form-label">Salary Month</label>
                                                                                <input type="text" id="voucher_salary_month" name="voucher_salary_month" class="form-control form-control-solid" placeholder="Salary Month" value="" oninput="$(this).next().html('')" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="voucher_salary_month_error"></span>
                                                                                <!--end::Error Message-->
                                                                                <input type="hidden" name="voucher_employee_id" id="voucher_employee_id" value="<?= @$salary["employee_id"] ?>" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="voucher_employee_id_error"></span>
                                                                                <!--end::Error Message-->
                                                                            </div>
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="required form-label">Amount</label>
                                                                                <input type="number" id="voucher_amount" name="voucher_amount" class="form-control form-control-solid" placeholder="Amount" value="" oninput="$(this).next().html('')" />
                                                                                <!--begin::Error Message-->
                                                                                <span class="text-danger d-block" id="voucher_amount_error"></span>
                                                                                <!--end::Error Message-->
                                                                            </div>
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="form-label">Reason</label>
                                                                                <select class="form-control form-control-solid" id="voucher_reason" name="voucher_reason">
                                                                                    <option>Select Reason</option>
                                                                                    <option value="personal">Personal</option>
                                                                                    <option value="medical">Medical</option>
                                                                                    <option value="wedding">Wedding</option>
                                                                                </select>
                                                                                <small class="text-danger error-text" id="voucher_reason_error"><?= isset($validation) ? display_error($validation, 'voucher_reason') : '' ?></small>
                                                                            </div>
                                                                            <div class="col-lg-6 mb-3">
                                                                                <label class="form-label">Note</label>
                                                                                <textarea class="form-control form-control-solid" id="voucher_note" name="voucher_note" placeholder="Detailed reason for this loan"></textarea>
                                                                                <small class="text-danger error-text" id="voucher_note_error"><?= isset($validation) ? display_error($validation, 'voucher_note') : '' ?></small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                                                        <a href="#" id="add_voucher_master_record_submit_button" class="btn btn-sm btn-primary">
                                                                            <span class="indicator-label">Add Record</span>
                                                                            <span class="indicator-progress">
                                                                                Please wait...
                                                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body px-0">
                                                    <table id="voucher_records_table" class="table rounded table-row-bordered table-striped table-row-gray-100 align-middle gs-0 gy-3 mb-0 text-center">
                                                        <thead>
                                                            <tr>
                                                                <th><strong>Month</strong></th>
                                                                <th><strong>Amount</strong></th>
                                                                <th><strong>Reason</strong></th>
                                                                <th><strong>Note</strong></th>
                                                                <th><strong>Actions</strong></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Voucher deduction-->

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
                                    Other Benifits
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
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button type="submit" id="submit_update_salary" class="form-control btn btn-sm btn-primary d-inline" style="max-width: max-content">
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
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('change', '#employee_name', function(e) {
            e.preventDefault();
            window.location.replace("<?php echo base_url('/backend/master/salary/id'); ?>/" + $(this).val());
        });

        $('form#update_salary .date_picker').each(function(index, elem) {
            var myid = $(this).attr('id');
            $(this).change(function(e) {
                $('#' + myid + '_error').html('')
            })
            $(this).flatpickr();
        });

        $(document).on('input', '#update_salary input[type=text]', function(e) {
            $(this).parent().find('.error-text').html('');
        });
        $(document).on('change', '#update_salary select', function(e) {
            $(this).parent().find('.error-text').html('');
        });
        $(document).on('change', '#update_salary input[type=checkbox]', function(e) {
            $(this).parent().parent().find('.error-text').html('');
        });

        $(document).on('change', '#update_salary input#pf', function(e) {
            if ($(this).is(':checked')) {
                $('#update_salary #pf_number_container').slideDown();
                $('#update_salary #pf_employee_contribution_container').slideDown();
                $('#update_salary #pf_employer_contribution_container').slideDown();
            } else {
                $('#update_salary #pf_number_container').slideUp();
                $('#update_salary #pf_employee_contribution_container').slideUp();
                $('#update_salary #pf_employer_contribution_container').slideUp();
            }
        });

        $(document).on('change', '#update_salary input#enable_bonus', function(e) {
            if ($(this).is(':checked')) {
                $('#update_salary #bonus_container').slideDown();
            } else {
                $('#update_salary #bonus_container').slideUp();
            }
        });

        $(document).on('change', '#update_salary input#esi', function(e) {
            if ($(this).is(':checked')) {
                $('#update_salary #esi_number_container').slideDown();
                $('#update_salary #esi_employee_contribution_container').slideDown();
                $('#update_salary #esi_employer_contribution_container').slideDown();
            } else {
                $('#update_salary #esi_number_container').slideUp();
                $('#update_salary #esi_employee_contribution_container').slideUp();
                $('#update_salary #esi_employer_contribution_container').slideUp();
            }
        });

        $(document).on('change', '#update_salary input#lwf', function(e) {
            if ($(this).is(':checked')) {
                $('#update_salary #lwf_employee_contribution_container').slideDown();
                $('#update_salary #lwf_employer_contribution_container').slideDown();
                $('#update_salary #lwf_deduction_on_every_n_month_container').slideDown();
            } else {
                $('#update_salary #lwf_employee_contribution_container').slideUp();
                $('#update_salary #lwf_employer_contribution_container').slideUp();
                $('#update_salary #lwf_deduction_on_every_n_month_container').slideUp();
            }
        });

        $(document).on('change', '#update_salary input#tds', function(e) {
            if ($(this).is(':checked')) {
                $('#update_salary #tds_amount_per_month_container').slideDown();
                $('#update_salary #tds_preferred_slab_container').slideDown();
                $('#update_salary #tds_slab_container').slideDown();
                initialise_switch();
            } else {
                $('#update_salary #tds_amount_per_month_container').slideUp();
                $('#update_salary #tds_preferred_slab_container').slideUp();
                $('#update_salary #tds_slab_container').slideUp();
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

        $(document).on('change', '#update_salary input#non_compete_loan', function(e) {
            if ($(this).is(':checked')) {
                $('#update_salary #non_compete_loan_from_container').slideDown();
                $('#update_salary #non_compete_loan_to_container').slideDown();
                $('#update_salary #non_compete_loan_amount_per_month_container').slideDown();
                $('#update_salary #non_compete_loan_remarks_container').slideDown();
            } else {
                $('#update_salary #non_compete_loan_from_container').slideUp();
                $('#update_salary #non_compete_loan_to_container').slideUp();
                $('#update_salary #non_compete_loan_amount_per_month_container').slideUp();
                $('#update_salary #non_compete_loan_remarks_container').slideUp();
            }
        });

        $(document).on('change', '#update_salary input#loyalty_incentive', function(e) {
            if ($(this).is(':checked')) {
                $('#update_salary #loyalty_incentive_from_container').slideDown();
                $('#update_salary #loyalty_incentive_to_container').slideDown();
                $('#update_salary #loyalty_incentive_amount_per_month_container').slideDown();
                $('#update_salary #loyalty_incentive_mature_after_month_container').slideDown();
                $('#update_salary #loyalty_incentive_pay_after_month_container').slideDown();
                $('#update_salary #loyalty_incentive_remarks_container').slideDown();
            } else {
                $('#update_salary #loyalty_incentive_from_container').slideUp();
                $('#update_salary #loyalty_incentive_to_container').slideUp();
                $('#update_salary #loyalty_incentive_amount_per_month_container').slideUp();
                $('#update_salary #loyalty_incentive_mature_after_month_container').slideUp();
                $('#update_salary #loyalty_incentive_pay_after_month_container').slideUp();
                $('#update_salary #loyalty_incentive_remarks_container').slideUp();
            }
        });

        $(document).on('submit', '#update_salary', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = $(this).find('button[type=submit]');
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/master/salary/validate'); ?>",
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

        var imprest_records_table = $("#imprest_records_table").DataTable({
            "ajax": {
                url: "<?= base_url('ajax/backend/master/imprest-master/get/' . $salary["employee_id"]) ?>",
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
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-imprest-record" data-id="' + row.id + '">' +
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
        $("#imprest_salary_month").flatpickr({
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
        $(document).on('click', '#add_imprest_master_record_submit_button', function(e) {
            e.preventDefault();
            var submitButton = $(this);
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            $("#add_imprest_master_record_modal").modal('hide');
            var data = {
                'imprest_employee_id': $("#imprest_employee_id").val(),
                'imprest_salary_month': $("#imprest_salary_month").val(),
                'imprest_deduction_amount': $("#imprest_deduction_amount").val()
            };
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/backend/master/imprest-master/add'); ?>",
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
                                $("#add_imprest_master_record_modal").modal('show');
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
                                $("#add_imprest_master_record_modal").modal('hide');
                                $("#imprest_records_table").DataTable().ajax.reload();
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
                        $("#add_imprest_master_record_modal").modal('show');
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    })
                }
            })
        })
        $(document).on('click', '.delete-imprest-record', function(e) {
            e.preventDefault();
            var imprest_record_id = $(this).data('id');
            var data = {
                'imprest_record_id': imprest_record_id,
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
                        url: "<?php echo base_url('ajax/backend/master/imprest-master/delete'); ?>",
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
                                        $("#imprest_records_table").DataTable().ajax.reload();
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


        var phone_bill_records_table = $("#phone_bill_records_table").DataTable({
            "ajax": {
                url: "<?= base_url('ajax/backend/master/phone-bill-master/get/' . $salary["employee_id"]) ?>",
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
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-phone-bill-record" data-id="' + row.id + '">' +
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
        $("#phone_bill_salary_month").flatpickr({
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
        $(document).on('click', '#add_phone_bill_master_record_submit_button', function(e) {
            e.preventDefault();
            var submitButton = $(this);
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            $("#add_phone_bill_master_record_modal").modal('hide');
            var data = {
                'phone_bill_employee_id': $("#phone_bill_employee_id").val(),
                'phone_bill_salary_month': $("#phone_bill_salary_month").val(),
                'phone_bill_deduction_amount': $("#phone_bill_deduction_amount").val()
            };
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/backend/master/phone-bill-master/add'); ?>",
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
                                $("#add_phone_bill_master_record_modal").modal('show');
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
                                $("#add_phone_bill_master_record_modal").modal('hide');
                                $("#phone_bill_records_table").DataTable().ajax.reload();
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
                        $("#add_phone_bill_master_record_modal").modal('show');
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    })
                }
            })
        })
        $(document).on('click', '.delete-phone-bill-record', function(e) {
            e.preventDefault();
            var phone_bill_record_id = $(this).data('id');
            var data = {
                'phone_bill_record_id': phone_bill_record_id,
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
                        url: "<?php echo base_url('ajax/backend/master/phone-bill-master/delete'); ?>",
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
                                        $("#phone_bill_records_table").DataTable().ajax.reload();
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


        var voucher_records_table = $("#voucher_records_table").DataTable({
            "ajax": {
                url: "<?= base_url('ajax/backend/master/voucher-master/get/' . $salary["employee_id"]) ?>",
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
                    data: "amount",
                    render: function(data, type, row, meta) {
                        return "₹ " + data;
                    }
                },
                {
                    data: "reason"
                },
                {
                    data: "note",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap mb-0 lh-sm " style="width: 100px"><small>' + data + '</small></p>';
                    }
                },
                {
                    data: "actions",
                    render: function(data, type, row, meta) {
                        if (row.salary_disbursed == 'yes') {
                            return '<span>Locked</span><br><small>Salary Disbursed</small>';
                        } else {
                            return '<div class="d-flex justify-content-center">' +
                                '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-voucher-record" data-id="' + row.id + '">' +
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
        $("#voucher_salary_month").flatpickr({
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
        $(document).on('click', '#add_voucher_master_record_submit_button', function(e) {
            e.preventDefault();
            var submitButton = $(this);
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            $("#add_voucher_record_modal").modal('hide');
            var data = {
                'voucher_employee_id': $("#voucher_employee_id").val(),
                'voucher_salary_month': $("#voucher_salary_month").val(),
                'voucher_amount': $("#voucher_amount").val(),
                'voucher_reason': $("#voucher_reason").val(),
                'voucher_note': $("#voucher_note").val(),
            };
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/backend/master/voucher-master/add'); ?>",
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
                                $("#add_voucher_record_modal").modal('show');
                                if (typeof response.response_data != "undefined" && typeof response.response_data.validation != "undefined") {
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
                                $("#add_voucher_record_modal").modal('hide');
                                $("#voucher_records_table").DataTable().ajax.reload();
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
                        $("#add_voucher_record_modal").modal('show');
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    })
                }
            })
        })
        $(document).on('click', '.delete-voucher-record', function(e) {
            e.preventDefault();
            var voucher_record_id = $(this).data('id');
            var data = {
                'voucher_record_id': voucher_record_id,
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
                        url: "<?php echo base_url('ajax/backend/master/voucher-master/delete'); ?>",
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
                                        $("#voucher_records_table").DataTable().ajax.reload();
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

    })
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>