<?= $this->extend('Templates/DashboardLayout') ?>
<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css" rel="stylesheet">
<div class="container mt-4">
    <h2 class="mb-4">Job Opening Form</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif;
    $savedMaxExp = $job->max_experience;
    $savedMaxBudget = $job->max_budget;
    $decoded = json_decode($job->requirement ?? '{}', true);
    $points = isset($decoded['points']) && is_array($decoded['points']) ? $decoded['points'] : [];


    ?>
    <style>
        .form-floating>.form-control,
        .form-floating>.form-select {
            height: calc(3.5rem + 2px);
            line-height: 1.25;
        }

        .form-floating>.form-control::placeholder {
            color: transparent;
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label,
        .form-floating>.form-select~label {
            opacity: 1;
            transform: scale(0.85) translateY(-0.85rem) translateX(0.15rem);
            /* background: #fff; */
            /* background: #f5f8fa; */
            height: max-content;
            padding: 0rem 0.5rem;
            margin: 0px 5px;
            color: #000;
            color: #393939;
            font-weight: 500;
        }

        .form-floating>.form-control:focus~label::after,
        .form-floating>.form-control:not(:placeholder-shown)~label::after,
        .form-floating>.form-select~label::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #f5f8fa;
            transform: translateY(-50%);
            z-index: -1;
        }

        .select2-selection.select2-selection--single.form-select {
            line-height: 1.85;
        }

        .la.la-trash-o {
            font-size: 18px;
        }

        .btn.btn-outline-success {
            border: 1px solid #50cc88 !important;
        }

        .btn.btn-outline-primary {
            border: 1px solid #007bff !important;
        }

        /* Summernote toolbar styling */
        .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 8px 12px;
        }

        .note-toolbar .note-btn-group {
            margin-right: 5px;
        }

        .note-toolbar .note-btn {
            padding: 4px 8px;
            margin-right: 2px;
            font-size: 12px;
        }

        .note-editable {
            padding: 15px;
            min-height: 120px;
        }

        /* Fix floating labels for input groups */
        .form-floating>.input-group {
            position: relative;
        }

        .form-floating>.input-group>label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
            color: #6c757d;
            z-index: 5;
        }

        .form-floating>.input-group>.form-control:focus~label,
        .form-floating>.input-group>.form-control:not(:placeholder-shown)~label {
            opacity: 1;
            transform: scale(0.85) translateY(-0.85rem) translateX(0.15rem);
            height: max-content;
            padding: 0rem 0.5rem;
            margin: 0px 5px;
            color: #393939;
            font-weight: 500;
            background-color: #f5f8fa;
        }

        .form-floating>.input-group>.form-control::placeholder {
            color: transparent;
        }

        .form-floating>.input-group>.form-control:focus::placeholder {
            color: transparent;
        }

        .note-toolbar.card-header {
            display: inline;
        }

        /* Right slide modal styles */
        .modal-dialog-slideout {
            position: fixed;
            margin: 0;
            width: 400px;
            height: 100%;
            right: -400px;
            top: 0;
            transition: right 0.3s ease-in-out;
        }

        .modal.fade.show .modal-dialog-slideout {
            right: 0;
        }

        .modal-dialog-slideout .modal-content {
            height: 100%;
            border: 0;
            border-radius: 0;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }
    </style>
    <form action="<?= esc($form_action) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row">
            <!-- <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="job_title" name="job_title" placeholder="Job Title" required>
                    <label for="job_title">Position *</label>
                </div>
            </div> -->
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="job_title" name="job_title" placeholder="Job Title" value="<?= esc($job->job_title) ?>" required>
                    <label for="job_title">Job Title <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="form-floating mb-3">
                    <select class="form-select" id="company_id" name="company_id" required>
                        <option value="" disabled selected>Select Company</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= esc($company['id']) ?>" <?= ($company['id'] == $job->company_id) ? 'selected' : '' ?>><?= esc($company['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="company_id">Company <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="form-floating mb-3">
                    <select class="form-select" id="department_id" name="department_id" data-control="select2" required>
                        <option value="" disabled selected>Select Department</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= esc($department['id']) ?>" <?= ($department['id'] == $job->department_id) ? 'selected' : '' ?>><?= esc($department['department_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="department_id">Department <span class="text-danger">* </span></label>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="form-floating mb-3">
                    <!-- <input type="text" class="form-control" id="job_type" name="job_type" placeholder="Job Type">
                    <label for="job_type">Job Type</label> -->
                    <select class="form-select" id="type_of_job" name="type_of_job" required>
                        <option value="" disabled selected>Select Job Type</option>
                        <option value="Full-time" <?= $job->type_of_job == 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                        <option value="Part-time" <?= $job->type_of_job == 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                        <option value="Contract" <?= $job->type_of_job == 'Contract' ? 'selected' : '' ?>>Contract</option>
                        <option value="Internship" <?= $job->type_of_job == 'Internship' ? 'selected' : '' ?>>Internship</option>

                    </select>
                    <label for="type_of_job">Job Type <span class="text-danger">* </span></label>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <input type="number" class="form-control" id="min_budget" name="min_budget" placeholder=" " min="0" value="<?= esc($job->min_budget) ?>" required>
                        <span class="input-group-text">INR</span>
                        <label for="min_budget">Min Budget <span class="text-danger">* </span></label>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <input type="number" class="form-control" id="max_budget" name="max_budget" placeholder=" " min="0" value="<?= esc($job->max_budget) ?>" required>
                        <span class="input-group-text">INR</span>
                        <label for="max_budget">Max Budget <span class="text-danger">* </span></label>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="form-floating mb-3">
                    <select class="form-select" id="system_required" name="system_required" required>
                        <option value="" disabled selected>Select System Requirement</option>
                        <option value="yes" <?= $job->system_required == 'yes' ? 'selected' : '' ?>>Yes</option>
                        <option value="no" <?= $job->system_required == 'no' ? 'selected' : '' ?>>No</option>
                        <option value="Optional">Optional</option>
                    </select>
                    <label for="system_required">Is PC Required? <span class="text-danger">* </span></label>
                </div>
            </div>


            <div class="col-6 col-md-4 col-lg-4 col-xl-4">
                <div class="form-floating mb-3">
                    <select class="form-select" id="shift_timing" name="shift_timing" data-control="select2" data-placeholder="Select Shift Timing" required>
                        <option value="" disabled selected>Select Shift Timing</option>
                    </select>
                    <label for="shift_timing">Shift Timing <span class="text-danger">* </span></label>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <input type="number" class="form-control" id="min_experience" name="min_experience" placeholder=" " min="0" value="<?= esc($job->min_experience) ?>" required>
                        <span class="input-group-text">year</span>
                        <label for="min_experience">Min Experience <span class="text-danger">* </span></label>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <input type="number" class="form-control" id="max_experience" name="max_experience" placeholder=" " min="0" value="<?= esc($job->max_experience) ?>" required>
                        <span class="input-group-text">year</span>
                        <label for="max_experience">Max Experience <span class="text-danger">* </span></label>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <select class="form-select" id="interview_location" name="interview_location" required>
                        <option value="" disabled selected>Select Interview Location</option>
                        <option value="Delhi" <?= $job->interview_location == 'Delhi' ? 'selected' : '' ?>>Delhi</option>
                        <option value="Noida" <?= $job->interview_location == 'Noida' ? 'selected' : '' ?>>Noida</option>
                        <option value="Gurugram" <?= $job->interview_location == 'Gurugram' ? 'selected' : '' ?>>Gurugram</option>
                    </select>
                    <label for="interview_location">Interview Location <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <!-- <input type="text" class="form-control" id="job_location" name="job_location" placeholder="Job Location" value="<?= esc($job->job_location ?? '') ?>" required> -->
                    <select class="form-select" id="job_location" name="job_location" required>
                        <option value="" disabled selected>Select Job Location</option>
                        <option value="Delhi" <?= $job->job_location  == 'Delhi' ? 'selected' : '' ?>>Delhi</option>
                        <option value="Noida" <?= $job->job_location  == 'Noida' ? 'selected' : '' ?>>Noida</option>
                        <option value="Sikandrabad" <?= $job->job_location  == 'Sikandrabad' ? 'selected' : '' ?>>Sikandrabad</option>
                        <option value="Gurugram" <?= $job->job_location  == 'Gurugram' ? 'selected' : '' ?>>Gurugram</option>
                    </select>
                    <label for="job_location">Job Location <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="seating_location" name="seating_location" placeholder="Seating Location" value="<?= esc($job->seating_location) ?>">
                    <label for="seating_location">Office Seating Location <span class="text-danger">* </span></label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <!-- <input type="text" class="form-control" id="reporting_to" name="reporting_to" placeholder="Reporting To">
                    <label for="reporting_to">Reporting To</label> -->
                    <!-- <option value="" disabled selected>Select Reporting To</option> -->
                    <select class="form-select" id="reporting_to" name="reporting_to" data-control="select2" data-placeholder="Select Reporting To" required>

                        <option value="" disabled selected>Select Reporting To</option>
                        <?php
                        foreach ($employees as $employee_row) {
                        ?>
                            <option value="<?php echo $employee_row['id']; ?>" <?= $job->reporting_to == $employee_row['id'] ? 'selected' : '' ?>>
                                <?php echo $employee_row['employee_name'] . ' [ ' . $employee_row['internal_employee_id'] . ' ] ' . $employee_row['department_name'] . ' - ' . $employee_row['company_short_name'] . ''; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="reporting_to">Reporting To <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="educational_qualification" name="educational_qualification" value="<?= esc($job->educational_qualification) ?>" placeholder="Educational Qualification">
                    <label for="educational_qualification">Educational Qualification <span class="text-danger">* </span></label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label for="job_description" class="form-label">Job Description <span class="text-danger">* </span></label>
                    <textarea class="form-control summernote" id="job_description" name="job_description"><?= esc($job->job_description) ?></textarea>
                </div>
            </div>



            <div class="col-md-12 mb-3">
                <?php
                $hasFile = false;
                $fileName = '';
                if (!empty($job->attachment)) {
                    $attachments = json_decode($job->attachment, true);
                    if (isset($attachments['kra_distribution_file']['file']) && !empty($attachments['kra_distribution_file']['file'])) {
                        $hasFile = true;
                        $filePath = $attachments['kra_distribution_file']['file'];
                        $fileName = basename($filePath);
                    }
                }
                ?>
                <div id="existing_file_section" class="d-flex justify-content-between align-items-center p-3 border rounded bg-light-info shadow-sm">
                    <?php if ($hasFile): ?>
                        <!-- File exists - show file details with download/delete -->
                        <div class="d-flex align-items-center gap-2">
                            <i class="la la-file-text text-primary" style="font-size: 24px;"></i>
                            <div>
                                <div class="fw-bold text-dark"><?= esc($fileName) ?></div>
                                <small class="text-muted">Existing KRA Distribution File</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('recruitment/job-listing/download-attachment/' . $job->id) ?>"
                                target="_blank"
                                class="btn btn-outline-primary btn-sm">
                                <i class="la la-download"></i> Download
                            </a>
                            <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                id="delete_existing_file"
                                onclick="deleteExistingFile()">
                                <i class="la la-trash"></i> Delete
                            </button>
                        </div>
                    <?php else: ?>
                        <!-- No file exists - show upload option -->
                        <div class="d-flex align-items-center gap-3">
                            <label for="kras_file" class="btn btn-outline-primary btn-sm">
                                <i class="la la-paperclip"></i> Attach KRA's File
                            </label>
                            <input type="file" class="d-none" id="kras_file" name="kras_file" accept=".pdf,.doc,.docx,.xls,.xlsx">
                            <span id="kras_file_name" class="text-primary" style="font-weight:500;"></span>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="hidden" id="remove_kras_file" name="remove_kras_file" value="0">
            </div>



            <div class="col-6 col-md-6 col-lg-4 col-xl-4">
                <div class="input-group">
                    <button type="button" class="btn btn-secondary" id="vacancy_minus">−</button>

                    <input type="hidden" name="no_of_vacancy" id="no_of_vacancy" value="<?= esc($job->no_of_vacancy) ?>">

                    <input type="text"
                        class="form-control text-center"
                        id="vacancyDisplay"
                        value="1 Vacancy"
                        autocomplete="off"
                        placeholder="Enter number of vacancies">

                    <button type="button" class="btn btn-secondary" id="vacancy_plus">+</button>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 col-xl-4">
                <div class="form-floating mb-3">
                    <select class="form-select" id="specific_industry" name="specific_industry" data-control="select2" data-placeholder="Select Industry">
                        <option value="" disabled selected>Select Industry</option>
                    </select>
                    <label for="specific_industry">Any Specific Industry <span class="text-danger">* </span></label>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 col-xl-4">
                <div class="mb-3">
                    <div id="testSummary" class="text-muted small"></div>
                    <a
                        id="tests_drawer_button"
                        href="#"
                        class="btn btn-lg btn-primary w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                        Configure Tests Required
                    </a>
                    <div
                        id="tests_drawer"
                        class="bg-white"
                        data-kt-drawer="true"
                        data-kt-drawer-activate="true"
                        data-kt-drawer-toggle="#tests_drawer_button"
                        data-kt-drawer-close="#tests_drawer_close"
                        data-kt-drawer-width="480px">
                        <div class="card w-100 rounded-0">
                            <div class="card-header pe-5 " style="min-height: unset;">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        Test Configuration
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="tests_drawer_close">
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
                                <div class="row">
                                    <div class="col-md-12 align-items-center bg-light-warning rounded p-5 mb-7">
                                        <label class="form-label fw-bold">IQ Test Required <span class="text-danger"> *</span></label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="iq_test" id="iq_yes" value="Yes" <?= json_decode($job->iq_test_required ?? '{}', true)['required'] == 'Yes' ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="iq_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="iq_test" id="iq_no" value="No" <?= json_decode($job->iq_test_required ?? '{}', true)['required'] == 'No' ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="iq_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 align-items-center bg-light-danger rounded p-5 mb-7">
                                        <label class="form-label fw-bold">English Test Required <span class="text-danger"> *</span></label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="english_test" id="english_yes" value="Yes" <?= json_decode($job->eng_test_required ?? '{}', true)['required'] == 'Yes' ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="english_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="english_test" id="english_no" value="No" <?= json_decode($job->eng_test_required ?? '{}', true)['required'] == 'No' ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="english_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 align-items-center bg-light-info rounded p-5 mb-7">
                                        <label class="form-label fw-bold">Operation Test Required <span class="text-danger"> *</span></label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="operation_test" id="operation_yes" value="Yes" <?= json_decode($job->operation_test_required ?? '{}', true)['required'] == 'Yes' ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="operation_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="operation_test" id="operation_no" value="No" <?= json_decode($job->operation_test_required ?? '{}', true)['required'] == 'No' ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="operation_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 align-items-center bg-light-success rounded p-5 mb-7">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <label class="form-label fw-bold m-0">Other Test Required <span class="text-danger"> *</span></label>
                                        </div>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="other_test" id="other_yes" value="Yes" <?= json_decode($job->other_test_required ?? '{}', true)['required'] == 'Yes' ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="other_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="other_test" id="other_no" value="No" <?= json_decode($job->other_test_required ?? '{}', true)['required'] == 'No' ? 'checked' : '' ?> required>
                                                <label class="form-check-label" for="other_no">No</label>
                                            </div>
                                        </div>

                                        <div id="otherTestNameDiv" class="mt-3" style="display: none;">
                                            <div id="other_tests">
                                                <div class="form-group">
                                                    <div data-repeater-list="other_tests">
                                                        <?php
                                                        $otherTests = json_decode($job->other_test_required ?? '{}', true)['tests'] ?? [];
                                                        if (!empty($otherTests)):
                                                            foreach ($otherTests as $test): ?>
                                                                <div data-repeater-item>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-10">
                                                                            <div class="form-floating mt-3">
                                                                                <input type="text" name="other_test" class="form-control" value="<?= esc($test) ?>" placeholder="Other Test">
                                                                                <label>Other Test <span class="text-danger"> *</span></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="d-flex align-items-center justify-content-end mt-3">
                                                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                                                    <i class="la la-trash-o"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach;
                                                        else: ?>
                                                            <div data-repeater-item>
                                                                <div class="form-group row">
                                                                    <div class="col-md-10">
                                                                        <div class="form-floating mt-3">
                                                                            <input type="text" name="other_test" class="form-control" placeholder="Other Test">
                                                                            <label>Other Test <span class="text-danger"> *</span></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="d-flex align-items-center justify-content-end mt-3">
                                                                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                                                <i class="la la-trash-o"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-link">
                                                        <i class="la la-plus"></i>Add a Other Test
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light-primary d-flex align-items-center justify-content-center gap-3">
                                <button type="button" class="btn btn-primary" id="saveTestConfig">Save Configuration</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <!-- <input type="text" class="form-control" id="review_schedule_3m" name="review_schedule_3m" placeholder="Review Schedule 3 Months"> -->
                    <select class="form-select" id="review_schedule_3m" name="review_schedule_3m" data-control="select2" data-placeholder="Select Review Schedule 3 Months" required>
                        <option value="" disabled selected>Select Review Schedule 3 Months</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['id']; ?>" <?= $job->review_schedule_3m == $employee['id'] ? 'selected' : '' ?>><?= esc($employee['employee_name']) ?> (<?= esc($employee['company_short_name']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <label for="review_schedule_3m">Candidate Review Schedule 3 Months With <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <!-- <input type="text" class="form-control" id="review_schedule_6m" name="review_schedule_6m" placeholder="Review Schedule 6 Months"> -->
                    <select class="form-select" id="review_schedule_6m" name="review_schedule_6m" data-control="select2" data-placeholder="Select Review Schedule 6 Months" required>
                        <option value="" disabled selected>Select Review Schedule 6 Months</option>
                        <?php foreach ($employees as $employee): ?>

                            <option value="<?php echo $employee['id']; ?>" <?= $job->review_schedule_6m == $employee['id'] ? 'selected' : '' ?>><?= esc($employee['employee_name']) ?> (<?= esc($employee['company_short_name']) ?>)</option>

                        <?php endforeach; ?>
                    </select>
                    <label for="review_schedule_6m">Candidate Review Schedule 6 Months With <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <!-- <input type="text" class="form-control" id="review_schedule_12m" name="review_schedule_12m" placeholder="Review Schedule 12 Months"> -->
                    <select class="form-select" id="review_schedule_12m" name="review_schedule_12m" data-control="select2" data-placeholder="Select Review Schedule 12 Months" required>
                        <option value="" disabled selected>Select Review Schedule 12 Months</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['id']; ?>" <?= $job->review_schedule_12m == $employee['id'] ? 'selected' : '' ?>><?= esc($employee['employee_name']) ?> (<?= esc($employee['company_short_name']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <label for="review_schedule_12m">Candidate Review Schedule 12 Months With <span class="text-danger">* </span></label>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update Job Requirement</button>
        </div>
    </form>
</div>
<?= $this->section('javascript') ?>


<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Enter job description...',
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36'],
            styleTags: [
                'p',
                {
                    title: 'Blockquote',
                    tag: 'blockquote',
                    className: 'blockquote',
                    value: 'blockquote'
                },
                'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ]
        });
    });
    jQuery(document).ready(function($) {
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: true,
            altInput: true,
            altFormat: 'Y-m-d',
            altInputClass: "form-control form-control-sm",
            minDate: "today"
        })
    });

    $(document).ready(function() {
        $('form').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                return false;
            }
        });

        $('textarea').on('keypress', function(e) {
            e.stopPropagation();
        });

        let vacancy = parseInt($('#no_of_vacancy').val()) || 1;

        function updateVacancyDisplay(val) {
            vacancy = val < 1 ? 1 : val;
            $('#no_of_vacancy').val(vacancy);
            const label = vacancy === 1 ? 'Vacancy' : 'Vacancies';
            $('#vacancyDisplay').val(`${vacancy} ${label}`);
        }

        updateVacancyDisplay(vacancy);

        $('#vacancy_plus').on('click', function() {
            updateVacancyDisplay(vacancy + 1);
        });

        $('#vacancy_minus').on('click', function() {
            if (vacancy > 1) {
                updateVacancyDisplay(vacancy - 1);
            }
        });

        $('#vacancyDisplay').on('focus', function() {
            $(this).val('');
            $(this).attr("placeholder", "Enter number of vacancies");
        });

        $('#vacancyDisplay').on('keyup', function() {
            const val = this.value.replace(/\D/g, '').replace(/^0+/, '');
            this.value = val.slice(0, 3);
        });

        $('#vacancyDisplay').on('blur keypress', function(e) {
            if (e.type === 'blur' || e.which === 13) {
                const inputVal = parseInt($(this).val());
                if (!isNaN(inputVal) && inputVal >= 1) {
                    updateVacancyDisplay(inputVal);
                } else {
                    updateVacancyDisplay(vacancy);
                }
            }
        });
    });

    $(document).ready(function() {
        const shiftTimings = [{
                id: "9:00 AM - 5:00 PM",
                text: "9:00 AM - 5:00 PM (Day Shift)"
            },
            {
                id: "10:00 AM - 6:00 PM",
                text: "10:00 AM - 6:00 PM (Day Shift)"
            },
            {
                id: "11:00 AM - 7:00 PM",
                text: "11:00 AM - 7:00 PM (Day Shift)"
            },
            {
                id: "12:00 PM - 8:00 PM",
                text: "12:00 PM - 8:00 PM (Afternoon Shift)"
            },
            {
                id: "1:00 PM - 9:00 PM",
                text: "1:00 PM - 9:00 PM (Afternoon Shift)"
            },
            {
                id: "2:00 PM - 10:00 PM",
                text: "2:00 PM - 10:00 PM (Evening Shift)"
            },
            {
                id: "3:00 PM - 11:00 PM",
                text: "3:00 PM - 11:00 PM (Evening Shift)"
            },
            {
                id: "6:00 PM - 2:00 AM",
                text: "6:00 PM - 2:00 AM (Night Shift)"
            },
            {
                id: "10:00 PM - 6:00 AM",
                text: "10:00 PM - 6:00 AM (Night Shift)"
            },
            {
                id: "11:00 PM - 7:00 AM",
                text: "11:00 PM - 7:00 AM (Night Shift)"
            },
            {
                id: "8:00 AM - 5:00 PM",
                text: "8:00 AM - 5:00 PM (Early Day Shift)"
            },
            {
                id: "7:00 AM - 4:00 PM",
                text: "7:00 AM - 4:00 PM (Early Day Shift)"
            },
            {
                id: "Flexible",
                text: "Flexible Timing"
            },
            {
                id: "Rotational",
                text: "Rotational Shifts"
            }
        ];

        $('#shift_timing').select2({
            data: shiftTimings,
            placeholder: 'Select Shift Timing'
        });

        // Set the selected shift timing from database
        var savedShiftTiming = "<?= esc($job->shift_timing ?? '') ?>";
        if (savedShiftTiming) {
            $('#shift_timing').val(savedShiftTiming).trigger('change');
        }

        const industries = [
            "Accounting", "Airlines/Aviation", "Alternative Dispute Resolution", "Alternative Medicine", "Animation",
            "Apparel & Fashion", "Architecture & Planning", "Arts & Crafts", "Automotive", "Aviation & Aerospace",
            "Banking", "Biotechnology", "Broadcast Media", "Building Materials", "Business Supplies & Equipment",
            "Capital Markets", "Chemicals", "Civic & Social Organization", "Civil Engineering", "Commercial Real Estate",
            "Computer & Network Security", "Computer Games", "Computer Hardware", "Computer Networking", "Computer Software",
            "Construction", "Consumer Electronics", "Consumer Goods", "Consumer Services", "Cosmetics",
            "Dairy", "Defense & Space", "Design", "Education Management", "E-learning",
            "Electrical & Electronic Manufacturing", "Entertainment", "Environmental Services", "Events Services", "Executive Office",
            "Facilities Services", "Farming", "Financial Services", "Fine Art", "Fishery",
            "Food & Beverages", "Food Production", "Fundraising", "Furniture", "Gambling & Casinos",
            "Glass, Ceramics & Concrete", "Government Administration", "Government Relations", "Graphic Design", "Health, Wellness & Fitness",
            "Higher Education", "Hospital & Health Care", "Hospitality", "Human Resources", "Import & Export",
            "Individual & Family Services", "Industrial Automation", "Information Services", "Information Technology & Services", "Insurance",
            "International Affairs", "International Trade & Development", "Internet", "Investment Banking/Venture", "Investment Management",
            "Judiciary", "Law Enforcement", "Law Practice", "Legal Services", "Legislative Office",
            "Leisure & Travel", "Libraries", "Logistics & Supply Chain", "Luxury Goods & Jewelry", "Machinery",
            "Management Consulting", "Maritime", "Marketing & Advertising", "Market Research", "Mechanical or Industrial Engineering",
            "Media Production", "Medical Device", "Medical Practice", "Mental Health Care", "Military",
            "Mining & Metals", "Motion Pictures & Film", "Museums & Institutions", "Music", "Nanotechnology",
            "Newspapers", "Nonprofit Organization Management", "Oil & Energy", "Online Publishing", "Outsourcing/Offshoring",
            "Package/Freight Delivery", "Packaging & Containers", "Paper & Forest Products", "Performing Arts", "Pharmaceuticals",
            "Philanthropy", "Photography", "Plastics", "Political Organization", "Primary/Secondary",
            "Printing", "Professional Training", "Program Development", "Public Policy", "Public Relations",
            "Public Safety", "Publishing", "Railroad Manufacture", "Ranching", "Real Estate",
            "Recreational Facilities & Services", "Religious Institutions", "Renewables & Environment", "Research", "Restaurants",
            "Retail", "Security & Investigations", "Semiconductors", "Shipbuilding", "Sporting Goods",
            "Sports", "Staffing & Recruiting", "Supermarkets", "Telecommunications", "Textiles",
            "Think Tanks", "Tobacco", "Translation & Localization", "Transportation/Trucking/Railroad", "Utilities",
            "Venture Capital", "Veterinary", "Warehousing", "Wholesale", "Wine & Spirits",
            "Wireless", "Writing & Editing"
        ];

        const industryOptions = industries.map(industry => ({
            id: industry,
            text: industry
        }));

        $('#specific_industry').select2({
            data: industryOptions,
            placeholder: 'Select Industry',
        });

        // Set the selected industry from database
        var savedIndustry = "<?= esc($job->specific_industry ?? '') ?>";
        if (savedIndustry) {
            $('#specific_industry').val(savedIndustry).trigger('change');
        }

        $('input[name="technical_test"]').on('change', function() {
            if ($(this).val() === 'Yes') {
                const wrapper = $('#technicalTestNameDiv');
                wrapper.show();
            } else {
                $('#technicalTestNameDiv').hide();
            }
        });

        var $technical_tests = $('#technical_tests').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {}
        });

        $('input[name="other_test"]').on('change', function() {
            if ($(this).val() === 'Yes') {
                const wrapper = $('#otherTestNameDiv');
                wrapper.show();
            } else {
                $('#otherTestNameDiv').hide();
            }
        });

        var $other_tests = $('#other_tests').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {}
        });

        if ($('input[name="technical_test"]:checked').val() === 'Yes') {
            $('#technicalTestNameDiv').show();
        }
        if ($('input[name="other_test"]:checked').val() === 'Yes') {
            $('#otherTestNameDiv').show();
        }

        $('#saveTestConfig').on('click', function() {
            const technical = $('input[name="technical_test"]:checked').val() || '';
            const iq = $('input[name="iq_test"]:checked').val() || '';
            const english = $('input[name="english_test"]:checked').val() || '';
            const operation = $('input[name="operation_test"]:checked').val() || '';
            const other = $('input[name="other_test"]:checked').val() || '';

            $('#tests_drawer').removeClass('show');
        });

        $('#kras_file').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#kras_file_name').text(fileName ? fileName : '');
        });
    });

    function deleteExistingFile() {
        if (confirm('Are you sure you want to delete this file?')) {
            $('#remove_kras_file').val('1');

            // Replace the content within existing_file_section div with attach file option
            const attachSection = `
                <div class="d-flex align-items-center gap-3">
                    <label for="kras_file" class="btn btn-outline-primary btn-sm" style="font-weight:600;">
                        <i class="la la-paperclip"></i> Attach KRA's File
                    </label>
                    <input type="file" class="d-none" id="kras_file" name="kras_file" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    <span id="kras_file_name" class="text-primary" style="font-weight:500;"></span>
                </div>
            `;

            $('#existing_file_section').html(attachSection);

            // Re-bind the file change event
            $('#kras_file').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $('#kras_file_name').text(fileName ? fileName : '');
            });

            // Show success message
            if (typeof toastr !== 'undefined') {
                toastr.success('File marked for deletion. Save the form to complete the action.');
            } else {
                alert('File marked for deletion. Save the form to complete the action.');
            }
        }
    }
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>