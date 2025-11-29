<?= $this->extend('Templates/DashboardLayout') ?>
<!--begin::Main Content-->
<?= $this->section('content') ?>
<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
    }

    #salary_details_table.table-bordered> :not(caption)>* {
        border-width: 1px 0 !important;
    }

    #salary_details_table.table-bordered> :not(caption)>*>* {
        border-width: 0 1px !important;
    }

    #salary_details_table.table-bordered tbody tr:last-child,
    #salary_details_table.table-bordered tfoot tr:last-child {
        border: 1px 0px !important;
        border-bottom-color: inherit !important;
        border-bottom-style: inherit !important;
    }

    #salary_details_table.table-bordered tbody tr:last-child td,
    #salary_details_table.table-bordered tbody tr:last-child th,
    #salary_details_table.table-bordered tfoot tr:last-child td,
    #salary_details_table.table-bordered tfoot tr:last-child th {
        border-bottom-width: 1px !important;
        border-bottom-color: inherit !important;
        border-bottom-style: inherit !important;
    }

    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .select2-container--bootstrap5 .select2-selection.loading {
        background: #fff url("<?php echo base_url() . '/assets/media/select2/select2-spinner.gif'; ?>") no-repeat calc(100% - 10px) 50% !important;
    }

    td.bg-light-warning,
    th.bg-light-warning {
        background-color: #fff8dd;
    }

    td.bg-light-success,
    th.bg-light-success {
        background-color: #e8fff3;
    }

    td.bg-light-danger,
    th.bg-light-danger {
        background-color: #fff5f8;
    }

    tr.selected>td {
        background-color: #009ef7 !important;
        color: #ffffff !important;
    }

    .swal2-popup .swal2-actions {
        margin: 0.5rem auto 1rem auto;
    }
</style>

<!--begin::Filter-->
<div class="card mb-7">
    <div class="card-body">
        <form id="filter_form" class="row gy-5 g-xl-8">

            <div class="col-md-2">
                <label class="form-label" for="company_id_for_filter" class="mb-3">Company</label>
                <select class="form-select form-select-sm" id="company" name="company[]" multiple data-control="select2" data-placeholder="Select a Company">
                    <option value=""></option>
                    <option value="all_companies" <?php echo (isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array('all_companies', $_REQUEST['company'])) ? 'selected' : ''; ?>>All Companies</option>
                    <?php
                    if (isset($Companies) && !empty($Companies)) {
                        foreach ($Companies as $company_row) {
                    ?>
                            <option value="<?php echo $company_row['id']; ?>" <?php echo (isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array($company_row['id'], $_REQUEST['company']) && !in_array('all_companies', $_REQUEST['company'])) ? 'selected' : ''; ?>><?php echo $company_row['company_name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <br>
                <small class="text-danger error-text" id="company_error"></small>
            </div>

            <div class="col-md-2">
                <label class="form-label" for="department_id_for_filter" class="mb-3">Department</label>
                <select class="form-select form-select-sm" id="department" name="department[]" multiple data-control="select2" data-placeholder="Select a Department">
                    <option value=""></option>
                    <option value="all_departments" <?php echo (isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array('all_departments', $_REQUEST['department'])) ? 'selected' : ''; ?>>All Departments</option>
                    <?php
                    if (isset($Departments) && !empty($Departments)) {
                        foreach ($Departments as $department_row) {
                    ?>
                            <option value="<?php echo $department_row['id']; ?>" <?php echo (isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array($department_row['id'], $_REQUEST['department']) && !in_array('all_departments', $_REQUEST['department'])) ? 'selected' : ''; ?>><?php echo $department_row['department_name'] . ' - ' . $department_row['company_short_name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <br>
                <small class="text-danger error-text" id="department_error"></small>
            </div>

            <div class="col-md-2">
                <label class="form-label" for="employee_id_for_filter" class="mb-3">Employee</label>
                <select class="form-select form-select-sm" id="employee" name="employee[]" multiple data-control="select2" data-placeholder="Select an Employee">
                    <option value=""></option>
                    <option value="all_employees" <?php echo (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array('all_employees', $_REQUEST['employee'])) ? 'selected' : ''; ?>>All Employees</option>
                    <?php
                    if (isset($Employees) && !empty($Employees)) {
                        foreach ($Employees as $employee_row) {
                    ?>
                            <option value="<?php echo $employee_row['id']; ?>" <?php echo (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array($employee_row['id'], $_REQUEST['employee']) && !in_array('all_employees', $_REQUEST['employee'])) ? 'selected' : ''; ?>><?php echo $employee_row['employee_name'] . '(' . $employee_row['internal_employee_id'] . ') - ' . $employee_row['department_name'] . ' -' . $employee_row['company_short_name']; ?> <?php echo $employee_row['status'] != 'active' ? ' --' . $employee_row['status'] : ''; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <small class="text-danger error-text" id="employee_error"></small>
            </div>

            <div class="col-md-2">
                <label class="form-label" for="status" class="mb-3">Disbursal Status</label>
                <select class="form-select form-select-sm" id="status" name="status[]" multiple data-control="select2" data-placeholder="Select Disbursal Status">
                    <option value=""></option>
                    <option value="all_statuses" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('all_statuses', $_REQUEST['status'])) ? 'selected' : ''; ?>>All Status</option>
                    <option value="yes" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('yes', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>Disbursed</option>
                    <option value="no" <?php echo (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && in_array('no', $_REQUEST['status']) && !in_array('all_status', $_REQUEST['status'])) ? 'selected' : ''; ?>>not Disbursed</option>
                </select>
                <small class="text-danger error-text" id="status_error"></small>
            </div>

            <div class="col-md-3">
                <label class="form-label" class="mb-3">Select Month</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="year_month" name="year_month" class="form-control" placeholder="Year Month" value="<?= $year_month ?>" />
                    <span class="input-group-text">
                        <i class="fa-solid fa-calendar-days"></i>
                    </span>

                </div>
            </div>

            <div class="col-md-1">
                <label class="form-label"> &nbsp; </label><br>
                <button type="submit" id="filter_form_submit" class="btn btn-sm btn-primary d-inline">
                    <span class="indicator-label">Filter</span>
                    <span class="indicator-progress">
                        Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>

        </form>
    </div>
</div>
<!--end::Filter-->

<!--begin::Row-->
<div class="row gy-5 g-xl-8">
    <div class="col-12">
        <table id="salary_report" class="table table-row-bordered nowrap">
            <thead>
                <tr>
                    <th class="text-center"><strong><input type="checkbox" id="select_all" /></strong></th>
                    <th class="text-center"><strong>Emp ID</strong></th>
                    <th class="text-center"><strong>Name</strong></th>
                    <th class="text-center"><strong>Designation</strong></th>
                    <th class="text-center"><strong>Date of joining</strong></th>
                    <th class="text-center"><strong>Days in <?= $year_month ?></strong></th>
                    <th class="text-center"><strong>Attn in <?= $year_month ?></strong></th>
                    <th class="text-center bg-light-warning"><strong><span class="d-block">Stipend</span><span class="d-block" style="font-size:0.6rem"> (Rate of pay) </span></strong></th>
                    <th class="text-center bg-light-warning"><strong><span class="d-block">Gross Salary</span><span class="d-block" style="font-size:0.6rem"> (Rate of pay) </span></strong></th>
                    <th class="text-center bg-light-success"><strong><span class="d-block">Stpend</span><span class="d-block" style="font-size:0.6rem"> (Earned) </span></strong></th>
                    <th class="text-center bg-light-success"><strong><span class="d-block">Gross Salary</span><span class="d-block" style="font-size:0.6rem"> (Earned) </span></strong></th>
                    <th class="text-center bg-light-danger"><strong>TDS</strong></th>
                    <th class="text-center bg-light-danger"><strong>Advance</strong></th>
                    <th class="text-center bg-light-danger"><strong>Imprest</strong></th>
                    <th class="text-center bg-light-danger"><strong>Loan</strong></th>
                    <th class="text-center bg-light-danger"><strong>Phone</strong></th>
                    <th class="text-center bg-danger"><strong>Total Deduction</strong></th>
                    <th class="text-center bg-success"><strong>Net Salary</strong></th>
                    <th class="text-center"><strong>IOB SB/AC</strong></th>
                    <th class="text-center"><strong>Status</strong></th>
                    <th class="text-center"><strong>Disbursed</strong></th>
                    <th class="text-center"><strong>Disbursal Date</strong></th>
                    <th class="text-center"><strong>Timeline</strong></th>
                    <th class="text-center bg-white"><strong>Action</strong></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!--end::Row-->

<!--begin::View Salary Modal-->
<div class="modal fade" tabindex="-1" id="salary_details_modal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row flex-grow-1">
                    <div class="col-md-2 d-flex align-items-center company-logo"></div>
                    <div class="col-md-8 d-flex flex-column align-items-center justify-content-end">
                        <h5 class="modal-title company-name">Salary Details</h5>
                        <p class="company-address"></p>
                    </div>
                    <div class="col-md-2 d-flex align-items-end current-date"></div>
                </div>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-center"><strong class="salary-month"></strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Name</label>
                                <strong class="ms-4 text-primary opacity-75 name"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Employee ID</label>
                                <strong class="ms-4 text-primary opacity-75 internal-employee-id"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Joining Date</label>
                                <strong class="ms-4 text-primary opacity-75 joining-date"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Designation</label>
                                <strong class="ms-4 text-primary opacity-75 designation"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Department</label>
                                <strong class="ms-4 text-primary opacity-75 department"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">UAN Number</label>
                                <strong class="ms-4 text-primary opacity-75 uan-number"></strong>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Bank Name</label>
                                <strong class="ms-4 text-primary opacity-75 bank-name"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Bank Account Number</label>
                                <strong class="ms-4 text-primary opacity-75 bank-account-number"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Adhar Number</label>
                                <strong class="ms-4 text-primary opacity-75 adhar-number"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Pan Number</label>
                                <strong class="ms-4 text-primary opacity-75 pan-number"></strong>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">ESI Number</label>
                                <strong class="ms-4 text-primary opacity-75 esi-number"></strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="border-gray-300" />

                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center justify-content-end">
                                <div class="d-flex align-items-center justify-content-between px-3 py-1 mx-3 ms-0">
                                    <label class="form-label mb-0">Attendance</label>
                                    <strong class="ms-4 text-primary opacity-75"></strong>
                                </div>
                                <div class="d-flex align-items-center justify-content-between border border-gray-500 border-dashed rounded px-3 py-1 mx-3 me-0">
                                    <label class="form-label mb-0">Final Paid Days:</label>
                                    <strong class="ms-4 text-primary opacity-75 final-paid-days"></strong>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="border-gray-300 mb-5" />

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered gy-1" id="salary_details_table">
                            <tr>
                                <th class="text-center" onclick="return false;">Particulars</th>
                                <th class="text-center" onclick="return false;">Structure Monthly</th>
                                <th class="text-center" onclick="return false;">Earned Amount Monthly</th>
                                <th class="text-center" onclick="return false;" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important;"></th>
                                <th class="text-center" onclick="return false;">Deductions</th>
                                <th class="text-center" onclick="return false;">Amount</th>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Basic Salary</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-basic-salary"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-basic-salary"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">PF</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-pf_employee_contribution"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">HRA</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-hra"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-hra"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">ESI</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-esi_employee_contribution"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Conveyance</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-conveyance"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-conveyance"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">LWF</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-lwf_employee_contribution"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Medical Allowance</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-medical_allowance"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-medical_allowance"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">Loan</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-loan_emi"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Special Allowance</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-special_allowance"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-special_allowance"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">Advance</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-advance"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Fuel Allowance</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-fuel_allowance"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-fuel_allowance"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">Imprest</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-imprest"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Vacation Allowance</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-vacation_allowance"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-vacation_allowance"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">TDS</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-tds"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Other Allowance</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-other_allowance"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-other_allowance"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">Phone Bill</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-phone-bill"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Total Gross</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-total_gross"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-total_gross"></strong></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><label class="form-label mb-0">Total Deductions</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-total_deduction"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left" colspan="3"><label class="form-label mb-0 text-capitalize net_payable_in_words"></label></td>
                                <td class="text-left" style="border-bottom: 1px solid #fff !important;"></td>
                                <td class="text-left"><strong class="text-primary opacity-75">Net Amount in hand</strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 net_amount_in_hand"></strong></td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="4" style="border-left: 1px solid #fff !important; border-bottom: 1px solid #fff !important;">&nbsp;</td>
                                <td class="text-left" colspan="2">
                                    <a href="#" target="_blank" class="print-salary-slip btn btn-sm btn-primary mb-2">Get Salary Slip</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" colspan="6" style="border-left: 1px solid #fff !important; border-right: 1px solid #fff !important; min-height:30px"> &nbsp; </td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="3"><label class="form-label mb-0">Other Benifits</label></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Non Compete loan</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-non_compete_loan"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-non_compete_loan"></strong></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Loyality Incentive</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-loyalty_incentive"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-loyalty_incentive"></strong></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left" colspan="6" style="border-left: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0 structure-esi_employer_contribution_label"></label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-esi_employer_contribution"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-esi_employer_contribution"></strong></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0 structure-pf_employer_contribution_label"></label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-pf_employer_contribution"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-pf_employer_contribution"></strong></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0 structure-lwf_employer_contribution_label"></label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-lwf_employer_contribution"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-lwf_employer_contribution"></strong></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left" colspan="6" style="border-left: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0 structure-bonus_label"></label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-bonus">2000</strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-bonus">1000</strong></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Gratuity</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-gratuity"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-gratuity"></strong></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                            <tr>
                                <td class="text-left"><label class="form-label mb-0">Gross Total CTC</label></td>
                                <td class="text-center"><strong class="text-primary opacity-75 structure-total_ctc"></strong></td>
                                <td class="text-center"><strong class="text-primary opacity-75 salary-total_ctc"></strong></td>
                                <td class="text-left" colspan="3" style="border-top: 1px solid #fff !important; border-bottom: 1px solid #fff !important; border-right: 1px solid #fff !important;"></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::View Salary Modal-->

<!--begin::Javascript-->
<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
<!-- <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        /*begin::Company Filter*/
        $(document).on('change', '#company', function() {
            var company = $('#company').val();
            var department = $('#department').val();
            if (jQuery.inArray("all_companies", company) !== -1 && company.length > 1) {
                $('#company').select2("val", ['all_companies']);
            }

            $('#department_error').html('');
            $('#department').parent().find('.select2-selection').addClass('loading');
            getDepatmentByCompany($('#company').val()).then(function() {
                $('#department').parent().find('.select2-selection').removeClass('loading');
            });
        })
        /*end::Company Filter*/
        /*begin::Department Filter*/
        $(document).on('change', '#department', function() {
            $('#employee_error').html('');
            var department = $('#department').val();
            if (jQuery.inArray("all_departments", department) !== -1 && department.length > 1) {
                $('#department').select2("val", ['all_departments']);
            }
            $('#employee').parent().find('.select2-selection').addClass('loading');
            getEmployeesByDepatment($('#company').val(), $('#department').val()).then(function() {
                $('#employee').parent().find('.select2-selection').removeClass('loading');
            });
        })
        /*end::department Filter*/
        /*begin::employee filter*/
        $(document).on('change', '#employee', function() {
            var employee = $('#employee').val();
            if (jQuery.inArray("all_employees", employee) !== -1 && employee.length > 1) {
                $('#employee').select2("val", ['all_employees']);
            }
        });
        /*end::employee filter*/
        /*begin::status filter*/
        $(document).on('change', '#status', function() {
            var status = $('#status').val();
            if (jQuery.inArray("all_statuses", status) !== -1 && status.length > 1) {
                $('#status').select2("val", ['all_statuses']);
            }
        });
        /*end::status filter*/
        /*begin::don't know what is this for*/
        $(document).on('click', '#salary_details_table tr th', function(e) {
            e.preventDefault();
        });
        /*end::don't know what is this for*/
        /*begin::year month filter*/
        const today = new Date();
        const previousMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        $("#year_month").flatpickr({
            defaultDate: previousMonth,
            maxDate: previousMonth,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "F Y",
                    altFormat: "F Y",
                    theme: "dark",
                })
            ]
        });
        /*end::year month filter*/

        /*begin::salary_report*/
        var salary_data = [];
        var salary_report = $("#salary_report").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                extend: 'excelHtml5',
                title: '',
                text: '<i class="fa-solid fa-file-excel"></i>Download Excel',
                className: 'btn btn-sm btn-light',
                exportOptions: {
                    // columns: ':gt(0)' 
                    columns: function(idx, data, node) {
                        if (idx == 0) {
                            return false;
                        }
                        if (idx == 37) {
                            return false;
                        }
                        if (idx == 38) {
                            return false;
                        }
                        return true;
                    },
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="AO"]', sheet).each(function() {
                        var thisText = $('is t', this).text();
                        if (thisText != '') {
                            $(this).attr('s', '20');
                            var textArray = thisText.split("~~~~");
                            var modifiedText = textArray.join('\n').trim();
                            $('is t', this).text(modifiedText);
                        }
                    });
                    $('row c', sheet).attr('s', ['51', '56']);
                }
            }],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('/ajax/backend/reports/salary/final-salary/load-intern-salary') ?>",
                type: "POST",
                // data:  { filter : function(){ return $('#filter_form').serialize(); } },
                data: function() {
                    return $('#filter_form').serialize();
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                searchPlaceholder: "Search"
            },
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    data: "id",
                    render: function(data, type, row, meta) {
                        return '';
                    }
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return '<strong>' + row.employee_data.internal_employee_id + '</strong>';
                    }
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return '<strong>' + row.employee_data.employee_name + '</strong>';
                    }
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return row.employee_data.designation_name;
                    }
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return row.employee_data.joining_date;
                    }
                },
                {
                    data: "month_days"
                },
                {
                    data: "final_paid_days"
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return row.salary_structure.stipend;
                    }
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return row.salary_structure.gross_salary;
                    }
                },
                {
                    data: "stipend"
                },
                {
                    data: "gross_salary"
                },
                {
                    data: "tds"
                },
                {
                    data: "advance"
                },
                {
                    data: "imprest"
                },
                {
                    data: "loan_emi"
                },
                {
                    data: "phone_bill"
                },
                {
                    data: "total_deduction"
                },
                {
                    data: "net_salary",
                    render: function(data) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        var attachments = JSON.parse(row.employee_data.attachment);
                        if (attachments && attachments.bank_account && attachments.bank_account.number) {

                            return attachments.bank_account.number;

                            // var replaceCount = Math.max(0, 16 - attachments.bank_account.number.length);
                            // var maskedString = 'x'.repeat(replaceCount) + attachments.bank_account.number.slice(-Math.min(6, attachments.bank_account.number.length));
                            // maskedString = maskedString.padStart(16, 'x');
                            // return maskedString;
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: "status"
                },
                {
                    data: "disbursed"
                },
                {
                    data: "disbursal_date"
                },
                {
                    data: "remarks_timeline",
                    render: function(data, type, row, meta) {
                        var render_html = ``;
                        if (data.length) {
                            render_html += `<ul class="list-group list-group-flush" style="font-size: 0.8rem;">`;
                            var remarks_timeline = JSON.parse(data);
                            $.each(remarks_timeline, function(index, item) {
                                if (index == 0) {
                                    render_html += `<li class="list-group-item d-flex justify-content-between align-items-start" style="gap:1rem">`;
                                    render_html += `<span>${item.action} </span>`;
                                    render_html += `<span> ${moment(item.date).fromNow()} </span>`;
                                    render_html += `<span> by: ${item.by}</span>`;
                                    render_html += `</li>`;
                                }
                            })
                            render_html += `</ul>`;
                        }
                        return render_html;
                    }
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        salary_data[row.id] = row;
                        var isDisabled = (row.disbursed == 'yes') ? 'disabled' : '';
                        var disbusalBtnText = (row.disbursed == 'yes') ? 'Disbursed' : 'Disburse';
                        var disbusalBtnClass = (row.disbursed == 'yes') ? 'success' : 'warning';
                        var html = '<div class="btn-group btn-group-sm">';
                        html += '<a href="#" target="_blank" class="show-detailed-salary btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-success btn-active-light-success" data-rowid="' + row.id + '">Show Detail</a>';
                        html += '<a href="<?php echo base_url(); ?>backend/reports/final-paid-days/final-paid-days-sheet?company[]=all_companies&department[]=all_departments&employee[]=' + row.employee_id + '&month=' + row.year + '-' + row.month + '" target="_blank" class="btn py-1 px-3 btn-outline btn-outline-dashed btn-outline-info btn-active-light-info">Show Paid Days</a>';
                        html += '</div>';
                        return html;
                    }
                },
            ],
            "fixedColumns": {
                left: 3,
                right: 1
            },
            "order": [],
            "scrollX": true,
            "scrollY": '42vh',
            "scrollCollapse": true,
            "paging": false,
            "select": {
                "style": 'multi',
                "selector": 'td:first-child'
            },
            "columnDefs": [{
                    "orderable": false,
                    "className": 'select-checkbox',
                    "targets": 0
                },
                // {
                //     "className": 'rate-of-pay bg-light-warning text-center',
                //     "targets": [7, 8, 9, 10, 11, 12, 13, 14, 15]
                // },
                // {
                //     "className": 'earned bg-light-success text-center',
                //     "targets": [16, 17, 18, 19, 20, 21, 22, 23, 24]
                // },
                // {
                //     "className": 'deduction bg-light-danger text-center',
                //     "targets": [25, 26, 27, 28, 29, 30, 31, 32]
                // },
                // {
                //     "className": 'total-deduction bg-danger text-center',
                //     "targets": [33]
                // },
                // {
                //     "className": 'net-salary bg-success text-center',
                //     "targets": [34]
                // },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).attr('data-id', data.id);
                console.log(data);
            }
        });
        $('#salary_report_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">SALARY REPORT</h3>');
        var current_employee_id = "<?php echo session()->get('current_user')['employee_id']; ?>";
        if (current_employee_id == '93') {
            var $dropdown_content =
                `<div class="btn-group btn-group-sm" role="group">
                <button id="action_dropdown" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Bulk Action</button>
                <ul class="dropdown-menu" aria-labelledby="action_dropdown">
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="regenerate">Regenerate</a>
                    </li>
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="hold">Hold</a>
                    </li>
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="unhold">UnHold</a>
                    </li>
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="finalized">Finalize</a>
                    </li>
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="disbursed">Disburse</a>
                    </li>
                </ul>
            </div>`;
        } else if (current_employee_id == '79') {
            var $dropdown_content =
                `<div class="btn-group btn-group-sm" role="group">
                <button id="action_dropdown" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Bulk Action</button>
                <ul class="dropdown-menu" aria-labelledby="action_dropdown">
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="disbursed">Disburse</a>
                    </li>
                </ul>
            </div>`;
        } else if (current_employee_id == '40') {
            var $dropdown_content =
                `<div class="btn-group btn-group-sm" role="group">
                <button id="action_dropdown" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Bulk Action</button>
                <ul class="dropdown-menu" aria-labelledby="action_dropdown">
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="regenerate">Regenerate</a>
                    </li>
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="hold">Hold</a>
                    </li>
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="unhold">UnHold</a>
                    </li>
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="finalized">Finalize</a>
                    </li>
                    <li class="menu-item">
                        <a class="dropdown-item menu-link datatable-action" href="#" data-action="disbursed">Disburse</a>
                    </li>
                </ul>
            </div>`;
        } else {
            var $dropdown_content = ``;
        }

        $('#salary_report_wrapper > .card > .card-header > .card-toolbar > .datatable-buttons-container > .dt-buttons').append($dropdown_content);
        $(document).on('change', '#select_all', function(e) {
            if ($(this).prop('checked') == true) {
                salary_report.rows().select();
            } else {
                salary_report.rows().deselect();
            }
        });
        salary_report.on('select.dt deselect.dt', function(e, dt, type, indexes) {
            var allRowsSelected = salary_report.rows({
                selected: true
            }).count() === salary_report.rows().count();
            $(".DTFC_LeftHeadWrapper table thead tr th #select_all").prop("checked", allRowsSelected);
        });

        $(document).on('click', '.datatable-action', function(e) {
            e.preventDefault();

            var action = $(this).data('action');
            var actionText = $(this).text();
            var selectedRowsId = [];
            var selectedRowsEmployees = [];
            var selectedRowsData = salary_report.rows({
                selected: true
            }).data();

            $.each(selectedRowsData, function(index, item) {
                selectedRowsId.push(item.id);
                selectedRowsEmployees.push(item.employee_data.employee_name);
            })
            if (selectedRowsEmployees.length > 3) {
                selectedRowsEmployeesHtml = `${selectedRowsEmployees[0]}, ${selectedRowsEmployees[1]}, ${selectedRowsEmployees[2]} and ${selectedRowsEmployees.length-3} others`;
            } else {
                selectedRowsEmployeesHtml = `${selectedRowsEmployees.join(', ')}`;
            }
            if (selectedRowsId.length) {
                Swal.fire({
                    title: `Are you sure? You want to ${actionText}`,
                    html: `Selected Employees: <strong>${selectedRowsEmployeesHtml}</strong><br>
                                <div class="mt-3">                                    
                                    ${action==`disburse` ? `<div class="input-group mb-2">
                                        <input type="text" id="action_date" class="form-control form-control-sm" placeholder="Select Date">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>` : `<input type="hidden" id="action_date" value="<?php echo date('Y-m-d'); ?>">`}
                                    <textarea id="action_remarks" class="form-control form-control-sm" placeholder="Remarks"></textarea>
                                </div>
                            `,
                    icon: 'question',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: `Yes, ${actionText}`,
                    denyButtonText: `Don't ${actionText}`,
                    customClass: {
                        confirmButton: "btn btn-sm btn-danger",
                        denyButton: "btn btn-sm btn-success",
                        cancelButton: "btn btn-sm btn-secondary"
                    },
                    showLoaderOnConfirm: true,
                    didOpen: () => {
                        if (action == `disburse`) {
                            $("#action_date").flatpickr({
                                minDate: "<?php echo date('Y-m-01'); ?>",
                                maxDate: "<?php echo date('Y-m-t'); ?>",
                            });
                        }
                    },
                    preConfirm: () => {
                        return $.ajax({
                            method: "post",
                            url: "<?php echo base_url('/ajax/backend/reports/salary/final-salary/do-action'); ?>",
                            data: {
                                'ids': selectedRowsId,
                                'action': action,
                                'action_date': $('#action_date').val(),
                                'action_remarks': $('#action_remarks').val(),
                            },
                        }).then(response => {
                            console.log(response);
                            if (response.response_type == 'error') {
                                if (response.response_description.length) {
                                    return Swal.showValidationMessage(`${response.response_description}`);
                                } else {
                                    return Swal.showValidationMessage(`There was an error while processing you request`);
                                }
                            }
                            if (response.response_type == 'success') {
                                return response;
                            }
                        }).catch(error => {
                            Swal.showValidationMessage(`Ajax Failed, Please contact administrator`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: `${actionText} successful`,
                            html: `${result.value.response_description}`,
                            icon: 'success',
                        }).then(function() {
                            $("#salary_report").DataTable().ajax.reload();
                        })
                    } else if (result.isDenied) {
                        Swal.fire("Changes are not saved", "", "info");
                    }
                })
            } else {
                Swal.fire({
                    title: "Please select at least 1 row",
                    icon: "error"
                });
            }
        })
        /*end::salary_report*/

        /*begin::show detailed salary*/
        $(document).on('click', '.show-detailed-salary', function(e) {
            e.preventDefault();
            var data = salary_data[$(this).data('rowid')];
            var modal = $("#salary_details_modal");

            console.log(data);
            var salary_structure = data.salary_structure;
            var employee_data = data.employee_data;
            var attachments = JSON.parse(employee_data.attachment);
            var company_logo_url_full = "<?php echo base_url(); ?>" + employee_data.company_logo_url;
            modal.find('.company-logo').html('<img src="' + company_logo_url_full + '" style="width:100px; object-fit: contain;"/>');
            modal.find('.company-name').html(employee_data.company_name);
            modal.find('.current-date').html("Date: <?php echo date('d M, Y'); ?>");
            modal.find('.company-address').html(employee_data.company_address);
            modal.find('.salary-month').html("Payslip for " + data.month + ", " + data.year);
            modal.find('.name').html(employee_data.employee_name);
            modal.find('.designation').html(employee_data.designation_name);
            modal.find('.department').html(employee_data.department_name);
            modal.find('.location').html(employee_data.company_city);
            modal.find('.internal-employee-id').html(employee_data.internal_employee_id);
            modal.find('.joining-date').html(employee_data.joining_date);
            modal.find('.bank-name').html(attachments.bank_account && attachments.bank_account.name ? attachments.bank_account.name : '');

            modal.find('.bank-account-number').html('');
            if (attachments.bank_account && attachments.bank_account.number) {
                // var replaceCount = Math.max(0, 16 - attachments.bank_account.number.length);
                // var maskedString = 'x'.repeat(replaceCount) + attachments.bank_account.number.slice(-Math.min(6, attachments.bank_account.number.length));
                // maskedString = maskedString.padStart(16, 'x');
                // modal.find('.bank-account-number').html(maskedString);
                modal.find('.bank-account-number').html(attachments.bank_account.number);
            }

            modal.find('.adhar-number').html(attachments.adhar && attachments.adhar.number ? attachments.adhar.number : '');
            modal.find('.pan-number').html(attachments.pan && attachments.pan.number ? attachments.pan.number : '');
            modal.find('.uan-number').html(salary_structure.pf_number ? salary_structure.pf_number : '');
            modal.find('.esi-number').html(salary_structure.esi_number ? salary_structure.esi_number : '');

            modal.find('.final-paid-days').html(data.final_paid_days + "/" + data.month_days);

            modal.find('.structure-basic-salary').html(parseFloat((salary_structure.basic_salary * 1).toFixed(2)));
            modal.find('.salary-basic-salary').html(parseFloat((data.basic_salary * 1).toFixed(2)));
            modal.find('.structure-hra').html(parseFloat((salary_structure.house_rent_allowance * 1).toFixed(2)));
            modal.find('.salary-hra').html(parseFloat((data.house_rent_allowance * 1).toFixed(2)));
            modal.find('.structure-conveyance').html(parseFloat((salary_structure.conveyance * 1).toFixed(2)));
            modal.find('.salary-conveyance').html(parseFloat((data.conveyance * 1).toFixed(2)));
            modal.find('.structure-medical_allowance').html(parseFloat((salary_structure.medical_allowance * 1).toFixed(2)));
            modal.find('.salary-medical_allowance').html(parseFloat((data.medical_allowance * 1).toFixed(2)));
            modal.find('.structure-special_allowance').html(parseFloat((salary_structure.special_allowance * 1).toFixed(2)));
            modal.find('.salary-special_allowance').html(parseFloat((data.special_allowance * 1).toFixed(2)));
            modal.find('.structure-fuel_allowance').html(parseFloat((salary_structure.fuel_allowance * 1).toFixed(2)));
            modal.find('.salary-fuel_allowance').html(parseFloat((data.fuel_allowance * 1).toFixed(2)));
            modal.find('.structure-vacation_allowance').html(parseFloat((salary_structure.vacation_allowance * 1).toFixed(2)));
            modal.find('.salary-vacation_allowance').html(parseFloat((data.vacation_allowance * 1).toFixed(2)));
            modal.find('.structure-other_allowance').html(parseFloat((salary_structure.other_allowance * 1).toFixed(2)));
            modal.find('.salary-other_allowance').html(parseFloat((data.other_allowance * 1).toFixed(2)));
            modal.find('.structure-total_gross').html(parseFloat((salary_structure.gross_salary * 1).toFixed(2)));
            modal.find('.salary-total_gross').html(parseFloat((data.gross_salary * 1).toFixed(2)));

            modal.find('.salary-total_deduction').html(parseFloat((data.total_deduction * 1).toFixed(2)));
            modal.find('.net_payable_in_words').html('Rupees:- ' + numToWords(Math.round(data.net_salary)) + ' only');
            modal.find('.net_amount_in_hand').html(Math.round(data.net_salary));

            modal.find('.print-salary-slip').attr('href', '<?php echo base_url("/backend/reports/salary/final-salary/salary-slip/"); ?>' + '/' + data.employee_id + '/' + data.year + '/' + data.month);

            modal.find('.salary-pf_employee_contribution').html(parseFloat((data.pf_employee_contribution * 1).toFixed(2)));
            modal.find('.salary-esi_employee_contribution').html(parseFloat((data.esi_employee_contribution * 1).toFixed(2)));
            modal.find('.salary-lwf_employee_contribution').html(parseFloat((data.lwf_employee_contribution * 1).toFixed(2)));
            modal.find('.salary-loan_emi').html(parseFloat(((data.loan_emi ? data.loan_emi : '0') * 1).toFixed(2)));
            modal.find('.salary-advance').html(parseFloat(((data.advance ? data.advance : '0') * 1).toFixed(2)));
            modal.find('.salary-imprest').html(parseFloat(((data.imprest ? data.imprest : '0') * 1).toFixed(2)));
            modal.find('.salary-tds').html(parseFloat(((data.tds ? data.tds : '0') * 1).toFixed(2)));
            modal.find('.salary-phone-bill').html(parseFloat(((data.phone_bill ? data.phone_bill : '0') * 1).toFixed(2)));
            modal.find('.structure-non_compete_loan').html(parseFloat(((salary_structure.non_compete_loan == 'yes' ? salary_structure.non_compete_loan_amount_per_month : '0') * 1).toFixed(2)));
            modal.find('.salary-non_compete_loan').html(parseFloat((data.non_compete_loan * 1).toFixed(2)));
            modal.find('.structure-loyalty_incentive').html(parseFloat(((salary_structure.loyalty_incentive == 'yes' ? salary_structure.loyalty_incentive_amount_per_month : '') * 1).toFixed(2)));
            modal.find('.salary-loyalty_incentive').html(parseFloat((data.loyalty_incentive * 1).toFixed(2)));

            if (salary_structure.pf == 'yes') {
                modal.find('.structure-pf_employer_contribution_label').html('EPF Contributions 13%');
                if (salary_structure.gross_salary >= 15000) {
                    modal.find('.structure-pf_employer_contribution').html(parseFloat(((15000 * 13) / 100).toFixed(2)));
                } else {
                    modal.find('.structure-pf_employer_contribution').html(parseFloat(((salary_structure.gross_salary * 13) / 100).toFixed(2)));
                }
            } else {
                modal.find('.structure-pf_employer_contribution_label').html('EPF Contributions 0%');
                modal.find('.structure-pf_employer_contribution').html('0');
            }
            modal.find('.salary-pf_employer_contribution').html(parseFloat((data.pf_employer_contribution * 1).toFixed(2)));

            if (salary_structure.esi == 'yes' && salary_structure.gross_salary <= 21000) {
                modal.find('.structure-esi_employer_contribution_label').html('ESI Contributions 3.25%');
                modal.find('.structure-esi_employer_contribution').html(parseFloat(((salary_structure.gross_salary * 3.25) / 100).toFixed(2)));
            } else {
                modal.find('.structure-esi_employer_contribution_label').html('ESI Contributions 0%');
                modal.find('.structure-esi_employer_contribution').html('0');
            }
            modal.find('.salary-esi_employer_contribution').html(parseFloat((data.esi_employer_contribution * 1).toFixed(2)));

            if (salary_structure.lwf == 'yes') {
                modal.find('.structure-lwf_employer_contribution_label').html('LWF Contributions');
                modal.find('.structure-lwf_employer_contribution').html(parseFloat(((((salary_structure.gross_salary * 0.2) / 100 <= 31) ? (salary_structure.gross_salary * 0.2) / 100 : 31) * 2).toFixed(2)));
            } else {
                modal.find('.structure-lwf_employer_contribution_label').html('LWF Contributions');
                modal.find('.structure-lwf_employer_contribution').html('0');
            }
            modal.find('.salary-lwf_employer_contribution').html(parseFloat((data.lwf_employer_contribution * 1).toFixed(2)));

            if (salary_structure.enable_bonus == 'yes') {
                modal.find('.structure-bonus_label').html('Bonus 8.33%');
                modal.find('.structure-bonus').html(parseFloat((salary_structure.bonus * 1).toFixed(2)));
            } else {
                modal.find('.structure-bonus_label').html('Bonus 0%');
                modal.find('.structure-bonus').html('0');
            }
            modal.find('.salary-bonus').html(parseFloat((data.bonus * 1).toFixed(2)));

            modal.find('.structure-gratuity').html(parseFloat((((salary_structure.basic_salary / 26) * 15) * (1 / 12)).toFixed(2)));
            modal.find('.salary-gratuity').html(parseFloat((data.gratuity * 1).toFixed(2)));

            modal.find('.structure-total_ctc').html(parseFloat((salary_structure.ctc * 1).toFixed(2)));
            modal.find('.salary-total_ctc').html(parseFloat((data.ctc * 1).toFixed(2)));

            $("#salary_details_modal").modal('show');
        })
        /*end::show detailed salary*/

        /*$(document).on('click', '.disburse-salary', function(e){
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: { confirmButton: "btn btn-sm btn-primary", cancelButton: "btn btn-sm btn-secondary" },
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $(this).data('rowid');
                    var data = {
                        'id': id
                    };
                    $.ajax({
                        method: "post",
                        url: "<?php echo base_url('/ajax/backend/reports/salary/final-salary/disburse'); ?>",
                        data: data,
                        success: function(response){
                            console.log(response);
                            if( response.response_type == 'error' ){
                                if( response.response_description.length ){
                                    Swal.fire({
                                        html: response.response_description,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: { confirmButton: "btn btn-primary" },
                                    })
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
                                    }).then(function (e) {
                                        $("#salary_report").DataTable().ajax.reload();
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
                        }
                    })
                }
            })                
        })*/
    })

    const getDepatmentByCompany = async (company_id) => {
        $('#department').html('<option></option>');
        $('#department').append('<option value="all_departments">All Departments</option>');
        var data = {
            'company_id': company_id,
        };
        return $.ajax({
            method: "post",
            url: "<?php echo base_url('/ajax/backend/reports/get-department-by-company-id'); ?>",
            data: data,
            success: function(response) {
                if (response.response_type == 'error') {
                    $('#department_error').html(response.response_description);
                }

                if (response.response_type == 'success') {
                    if (typeof response.response_data.departments != 'undefined') {
                        var department_data = response.response_data.departments;
                        $.each(department_data, function(index, department) {
                            $('#department').append('<option value="' + department.id + '" >' + department.department_name + ' - ' + department.company_short_name + '</option>');
                        });
                        $('#department').val([]).trigger('change');
                    }
                }
            },
            failed: function() {
                Swal.fire({
                    html: "Ajax Failed while loading departments conditionally, Please contact administrator",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    },
                })
            }
        });
    }

    const getEmployeesByDepatment = async (company_id, department_id) => {
        $('#employee').html('<option></option>');
        $('#employee').append('<option value="all_employees">All Employees</option>');
        var data = {
            'company_id': company_id,
            'department_id': department_id,
        };
        return $.ajax({
            method: "post",
            url: "<?php echo base_url('/ajax/backend/reports/get-employees-by-department-id'); ?>",
            data: data,
            success: function(response) {
                if (response.response_type == 'error') {
                    $('#employee_error').html(response.response_description);
                }

                if (response.response_type == 'success') {
                    if (typeof response.response_data.employees != 'undefined') {
                        var employee_data = response.response_data.employees;
                        console.log(employee_data);
                        $.each(employee_data, function(index, employee) {
                            // $('#employee').append('<option value="'+employee.id+'" >'+employee.employee_name+' ('+employee.internal_employee_id+') - '+employee.department_name+' - '+employee.company_short_name+'</option>');

                            $('#employee').append(`<option value="${employee.id}" >${employee.employee_name} (${employee.internal_employee_id}) - ${employee.department_name} - ${employee.company_short_name} ${employee.status != 'active' ? ' --'+employee.status : ''}</option>`);

                        });
                        $('#employee').val([]).trigger('change');
                    }
                }
            },
            failed: function() {
                Swal.fire({
                    html: "Ajax Failed while loading employees conditionally, Please contact administrator",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    },
                })
            }
        });
    }

    const arr = x => Array.from(x);
    const num = x => Number(x) || 0;
    const str = x => String(x);
    const isEmpty = xs => xs.length === 0;
    const take = n => xs => xs.slice(0, n);
    const drop = n => xs => xs.slice(n);
    const reverse = xs => xs.slice(0).reverse();
    const comp = f => g => x => f(g(x));
    const not = x => !x;
    const chunk = n => xs =>
        isEmpty(xs) ? [] : [take(n)(xs), ...chunk(n)(drop(n)(xs))];
    let numToWords = n => {

        let a = [
            '', 'one', 'two', 'three', 'four',
            'five', 'six', 'seven', 'eight', 'nine',
            'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
            'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        ];

        let b = [
            '', '', 'twenty', 'thirty', 'forty',
            'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
        ];

        let g = [
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion',
            'quintillion', 'sextillion', 'septillion', 'octillion', 'nonillion'
        ];


        let makeGroup = ([ones, tens, huns]) => {
            return [
                num(huns) === 0 ? '' : a[huns] + ' hundred ',
                num(ones) === 0 ? b[tens] : b[tens] && b[tens] + ' ' || '',
                a[tens + ones] || a[ones]
            ].join('');
        };

        let thousand = (group, i) => group === '' ? group : `${group} ${g[i]}`;

        if (typeof n === 'number')
            return numToWords(String(n));
        else if (n === '0')
            return 'zero';
        else
            return comp(chunk(3))(reverse)(arr(n))
                .map(makeGroup)
                .map(thousand)
                .filter(comp(not)(isEmpty))
                .reverse()
                .join(' ');
    };
</script>
<?= $this->endSection() ?>
<!--end::Javascript-->

<?= $this->endSection() ?>
<!--end::Main Content-->