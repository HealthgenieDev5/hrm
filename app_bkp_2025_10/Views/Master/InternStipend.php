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
    input[type=number] {
        -moz-appearance: textfield !important;
    }

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
                        <div class="card mb-3">
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
                                                <label class="form-label d-flex align-items-center justify-content-start" for="employee_id"><span>Employee ID:</span> <span class="ms-2"><span class="text-muted">#</span><?php echo @$employee_id; ?></span></label>
                                                <input type="hidden" id="employee_id" name="employee_id" value="<?= set_value('employee_id', @$salary['employee_id']) ?>" />
                                                <small class="text-danger error-text" id="employee_id_error"></small><br>
                                                <input type="hidden" id="salary_id" name="salary_id" value="<?= set_value('salary_id', @$salary['id']) ?>" />
                                                <small class="text-danger error-text" id="salary_id_error"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label d-flex align-items-center justify-content-start" for="ctc"><span>Stipend:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span> <?php echo @$salary['stipend']; ?></span></label>
                                                <input type="hidden" id="ctc" name="stipend" value="<?= set_value('stipend', @$salary['stipend']) ?>" />

                                            </div>
                                        </div>

                                        <div class="col-md-4">

                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="stipend">Stipend</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" id="stipend" class="form-control form-control-sm " name="stipend" placeholder="stipend" value="<?= set_value('stipend', @$salary['stipend']) ?>" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" />
                                                </div>
                                                <small class="text-muted">Max 10 digits</small><br>
                                                <small class="text-danger error-text" id="stipend_error"></small>
                                            </div>
                                        </div>









                                        <!-- <hr class="my-7 opacity-10"> -->



                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card">
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
                        </div> -->
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
        // $(document).on('change', '#update_salary select', function(e) {
        //     $(this).parent().find('.error-text').html('');
        // });
        // $(document).on('change', '#update_salary input[type=checkbox]', function(e) {
        //     $(this).parent().parent().find('.error-text').html('');
        // });

        // $(document).on('change', '#update_salary input#pf', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#update_salary #pf_number_container').slideDown();
        //         $('#update_salary #pf_employee_contribution_container').slideDown();
        //         $('#update_salary #pf_employer_contribution_container').slideDown();
        //     } else {
        //         $('#update_salary #pf_number_container').slideUp();
        //         $('#update_salary #pf_employee_contribution_container').slideUp();
        //         $('#update_salary #pf_employer_contribution_container').slideUp();
        //     }
        // });

        // $(document).on('change', '#update_salary input#enable_bonus', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#update_salary #bonus_container').slideDown();
        //     } else {
        //         $('#update_salary #bonus_container').slideUp();
        //     }
        // });

        // $(document).on('change', '#update_salary input#esi', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#update_salary #esi_number_container').slideDown();
        //         $('#update_salary #esi_employee_contribution_container').slideDown();
        //         $('#update_salary #esi_employer_contribution_container').slideDown();
        //     } else {
        //         $('#update_salary #esi_number_container').slideUp();
        //         $('#update_salary #esi_employee_contribution_container').slideUp();
        //         $('#update_salary #esi_employer_contribution_container').slideUp();
        //     }
        // });

        // $(document).on('change', '#update_salary input#lwf', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#update_salary #lwf_employee_contribution_container').slideDown();
        //         $('#update_salary #lwf_employer_contribution_container').slideDown();
        //         $('#update_salary #lwf_deduction_on_every_n_month_container').slideDown();
        //     } else {
        //         $('#update_salary #lwf_employee_contribution_container').slideUp();
        //         $('#update_salary #lwf_employer_contribution_container').slideUp();
        //         $('#update_salary #lwf_deduction_on_every_n_month_container').slideUp();
        //     }
        // });

        // $(document).on('change', '#update_salary input#tds', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#update_salary #tds_amount_per_month_container').slideDown();
        //         $('#update_salary #tds_preferred_slab_container').slideDown();
        //         $('#update_salary #tds_slab_container').slideDown();
        //         initialise_switch();
        //     } else {
        //         $('#update_salary #tds_amount_per_month_container').slideUp();
        //         $('#update_salary #tds_preferred_slab_container').slideUp();
        //         $('#update_salary #tds_slab_container').slideUp();
        //     }
        // });
        // initialise_switch();

        // function initialise_switch() {
        //     var toggleSwitch = $('.switch-toggle');
        //     toggleSwitch.each(function(index, thisSwitch) {
        //         var checked_input = $(thisSwitch).find('label > input:checked').parent();
        //         var w = checked_input.outerWidth();
        //         var indexoflabel = checked_input.index();
        //         $(thisSwitch).find('a').css({
        //             'width': w,
        //             'left': indexoflabel * w
        //         });
        //     })
        // }

        // $(document).on('click', '.switch-toggle > label', function(e) {
        //     var w = $(this).outerWidth();
        //     $(this).find('input').prop('checked', true);
        //     $(this).parent().find('a').css({
        //         'width': w,
        //         'left': $(this).position().left
        //     });
        // })

        // $(document).on('change', '#update_salary input#non_compete_loan', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#update_salary #non_compete_loan_from_container').slideDown();
        //         $('#update_salary #non_compete_loan_to_container').slideDown();
        //         $('#update_salary #non_compete_loan_amount_per_month_container').slideDown();
        //         $('#update_salary #non_compete_loan_remarks_container').slideDown();
        //     } else {
        //         $('#update_salary #non_compete_loan_from_container').slideUp();
        //         $('#update_salary #non_compete_loan_to_container').slideUp();
        //         $('#update_salary #non_compete_loan_amount_per_month_container').slideUp();
        //         $('#update_salary #non_compete_loan_remarks_container').slideUp();
        //     }
        // });

        // $(document).on('change', '#update_salary input#loyalty_incentive', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#update_salary #loyalty_incentive_from_container').slideDown();
        //         $('#update_salary #loyalty_incentive_to_container').slideDown();
        //         $('#update_salary #loyalty_incentive_amount_per_month_container').slideDown();
        //         $('#update_salary #loyalty_incentive_mature_after_month_container').slideDown();
        //         $('#update_salary #loyalty_incentive_pay_after_month_container').slideDown();
        //         $('#update_salary #loyalty_incentive_remarks_container').slideDown();
        //     } else {
        //         $('#update_salary #loyalty_incentive_from_container').slideUp();
        //         $('#update_salary #loyalty_incentive_to_container').slideUp();
        //         $('#update_salary #loyalty_incentive_amount_per_month_container').slideUp();
        //         $('#update_salary #loyalty_incentive_mature_after_month_container').slideUp();
        //         $('#update_salary #loyalty_incentive_pay_after_month_container').slideUp();
        //         $('#update_salary #loyalty_incentive_remarks_container').slideUp();
        //     }
        // });

        $(document).on('submit', '#update_salary', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = $(this).find('button[type=submit]');
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/master/stipend/validate'); ?>",
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