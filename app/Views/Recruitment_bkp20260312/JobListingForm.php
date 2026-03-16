<?= $this->extend('Templates/DashboardLayout') ?>
<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css" rel="stylesheet">
<div class="container mt-4">
    <h2 class="mb-4">Job Opening Form</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
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
    <form action="<?= $form_action ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <!-- <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="job_title" name="job_title" placeholder="Job Title" required>
                    <label for="job_title">Position *</label>
                </div>
            </div> -->
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="job_title" name="job_title" placeholder="Job Title" required>
                    <label for="job_title">Job Title <span class="text-danger">*</span></label>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="form-floating mb-3">
                    <select class="form-select" id="company_id" name="company_id" required>
                        <option value="" disabled selected>Select Company</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= esc($company['id']) ?>"><?= esc($company['company_name']) ?></option>
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
                            <option value="<?= esc($department['id']) ?>"><?= esc($department['department_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="department_id">Department <span class="text-danger">* </span></label>
                </div>
            </div>
            <!-- <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control flatpickr-date" id="job_opening_date" name="job_opening_date" placeholder="Date of Job Opening" data-input required>
                    <label for="job_opening_date">Date of Job Opening</label>
                </div>
            </div> -->

            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="form-floating mb-3">
                    <!-- <input type="text" class="form-control" id="job_type" name="job_type" placeholder="Job Type">
                    <label for="job_type">Job Type</label> -->
                    <select class="form-select" id="type_of_job" name="type_of_job" required>
                        <option value="" disabled selected>Select Job Type</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Internship">Internship</option>
                    </select>
                    <label for="type_of_job">Job Type <span class="text-danger">* </span></label>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <input type="number" class="form-control" id="min_budget" name="min_budget" placeholder=" " min="0" step="1" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                        <span class="input-group-text">INR</span>
                        <label for="min_budget">Min Budget <span class="text-danger">* </span></label>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <input type="number" class="form-control" id="max_budget" name="max_budget" placeholder=" " min="0" step="1" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                        <span class="input-group-text">INR</span>
                        <label for="max_budget">Max Budget <span class="text-danger">* </span></label>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="form-floating mb-3">
                    <select class="form-select" id="system_required" name="system_required" required>
                        <option value="" disabled selected>Select System Requirement</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
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
                        <input type="number" class="form-control" id="min_experience" name="min_experience" placeholder=" " min="0" step="1" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                        <span class="input-group-text">year</span>
                        <label for="min_experience">Min Experience <span class="text-danger">* </span></label>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xl-3">
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <input type="number" class="form-control" id="max_experience" name="max_experience" placeholder=" " min="0" step="1" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                        <span class="input-group-text">year</span>
                        <label for="max_experience">Max Experience <span class="text-danger">* </span></label>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <select class="form-select" id="interview_location" name="interview_location" required>
                        <option value="" disabled selected>Select Interview Location</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Noida">Noida</option>
                        <option value="Gurugram">Gurugram</option>
                    </select>
                    <label for="interview_location">Interview Location <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <select class="form-select" id="job_location" name="job_location" required>
                        <option value="" disabled selected>Job Location</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Noida">Noida</option>
                        <option value="Sikandrabad">Sikandrabad</option>
                        <option value="Gurugram">Gurugram</option>


                    </select>
                    <!-- <input type="text" class="form-control" id="job_location" name="job_location" placeholder="Max Budget" required>-->
                    <label for="job_location">Job Location <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="seating_location" name="seating_location" placeholder="Seating Location">
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
                            <option value="<?php echo $employee_row['id']; ?>">
                                <?php echo $employee_row['employee_name']  . ' [ ' . $employee_row['department_name'] . ' ] ' . ' - ' . $employee_row['company_short_name'] . ''; ?>
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
                    <input type="text" class="form-control" id="educational_qualification" name="educational_qualification" placeholder="Educational Qualification">
                    <label for="educational_qualification">Educational Qualification <span class="text-danger">* </span></label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="job_description" class="form-label">Job Description <span class="text-danger">* </span></label>
                    <textarea class="form-control summernote" id="job_description" name="job_description"></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div id="existing_file_section" class="d-flex justify-content-between align-items-center p-3 border rounded bg-light-white shadow mb-3">

                    <div class="d-flex align-items-center gap-3">
                        <label for="kras_file" class="btn btn-outline-primary btn-sm">
                            <i class="la la-paperclip"></i> Attach KRA's File
                        </label>
                        <input type="file" class="d-none" id="kras_file" name="kras_file" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <span id="kras_file_name" class="text-primary" style="font-weight:500;"></span>
                    </div>

                </div>
            </div>


            <div class="col-6 col-md-6 col-lg-4 col-xl-4">
                <div class="input-group">
                    <button type="button" class="btn btn-secondary" id="vacancy_minus">−</button>

                    <input type="hidden" name="no_of_vacancy" id="no_of_vacancy" value="1">

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
                    <!-- <button type="button" class="btn btn-outline-primary" id="testRequiredBtn">
                        <i class="fas fa-clipboard-list"></i> Configure Tests Required
                    </button> -->
                    <a
                        id="tests_drawer_button"
                        href="#"
                        class="btn btn-lg btn-primary w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                        Configure Tests Required
                    </a>
                    <div id="testSummary" class="mt-2 text-info"></div>
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
                                        Test
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
                                                <input class="form-check-input" type="radio" name="iq_test" id="iq_yes" value="Yes">
                                                <label class="form-check-label" for="iq_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="iq_test" id="iq_no" value="No">
                                                <label class="form-check-label" for="iq_no">No</label>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- <hr class="my-4"> -->

                                    <div class="col-md-12 align-items-center bg-light-danger rounded p-5 mb-7">
                                        <label class="form-label fw-bold">English Test Required <span class="text-danger"> *</span></label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="english_test" id="english_yes" value="Yes">
                                                <label class="form-check-label" for="english_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="english_test" id="english_no" value="No">
                                                <label class="form-check-label" for="english_no">No</label>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- <hr class="my-4"> -->

                                    <div class="col-md-12 align-items-center bg-light-info rounded p-5 mb-7">
                                        <label class="form-label fw-bold">Operation Test Required <span class="text-danger"> *</span></label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="operation_test" id="operation_yes" value="Yes">
                                                <label class="form-check-label" for="operation_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="operation_test" id="operation_no" value="No">
                                                <label class="form-check-label" for="operation_no">No</label>
                                            </div>
                                            <!-- <div class="form-check">
                                                <input class="form-check-input" type="radio" name="operation_test" id="operation_optional" value="Optional">
                                                <label class="form-check-label" for="operation_optional">Optional</label>
                                            </div> -->
                                        </div>
                                    </div>

                                    <!-- <hr class="my-4"> -->
                                    <div class="col-md-12 align-items-center bg-light-success rounded p-5 mb-7">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <label class="form-label fw-bold m-0">Other Test Required <span class="text-danger"> *</span></label>
                                        </div>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="other_test" id="other_yes" value="Yes">
                                                <label class="form-check-label" for="other_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="other_test" id="other_no" value="No">
                                                <label class="form-check-label" for="other_no">No</label>
                                            </div>
                                        </div>

                                        <!-- <div id="technicalTestNameDiv" class="mt-3" style="display: none;">
                            <input type="text" class="form-control" id="technicalTestName" placeholder="Specify technical test name (e.g., Coding Test, Algorithm Test)">
                        </div> -->
                                        <div id="otherTestNameDiv" class="mt-3" style="display: none;">
                                            <!--begin::Repeater-->
                                            <div id="other_tests">

                                                <!--begin::Form group-->
                                                <div class="form-group">
                                                    <div data-repeater-list="other_tests">
                                                        <!-- OLD CODE - Without file upload
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
                                                        -->
                                                        <!-- NEW CODE - With file upload -->
                                                        <div data-repeater-item>
                                                            <div class="form-group row">
                                                                <div class="col-md-7">
                                                                    <div class="form-floating mt-3">
                                                                        <input type="text" name="other_test" class="form-control" placeholder="Other Test">
                                                                        <label>Other Test <span class="text-danger"> *</span></label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="mt-3">
                                                                        <label class="btn btn-outline-primary btn w-100 px-2">
                                                                            <i class="la la-paperclip"></i>Upload
                                                                            <input type="file" class="d-none other-test-file" name="other_test_file" accept=".pdf,.doc,.docx">
                                                                        </label>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="d-flex align-items-center justify-content-end mt-3">
                                                                        <a href="javascript:;" data-repeater-delete class="btn btn btn-light-danger">
                                                                            <i class="la la-trash-o"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <small class="text-muted file-name-display d-block mt-1"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-link">
                                                        <i class="la la-plus"></i>Add a Other Test
                                                    </a>
                                                </div>
                                            </div>
                                            <!--end::Repeater-->
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <div class="card-footer bg-light-primary d-flex align-items-center justify-content-center gap-3">
                                <button type="button" class="btn btn-primary" id="saveTestConfig">Save Test Configuration</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Hidden inputs to store test values -->
                <!-- <input type="hidden" id="technical_test_required" name="technical_test_required" value="">
                <input type="hidden" id="technical_test_name" name="technical_test_name" value="">
                <input type="hidden" id="iq_test_required" name="iq_test_required" value="">
                <input type="hidden" id="eng_test_required" name="eng_test_required" value="">
                <input type="hidden" id="operation_test_required" name="operation_test_required" value="">
                <input type="hidden" id="other_test_required" name="other_test_required" value="">
                <input type="hidden" id="other_test_name" name="other_test_name" value=""> -->
            </div>

            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <!-- <input type="text" class="form-control" id="review_schedule_3m" name="review_schedule_3m" placeholder="Review Schedule 3 Months"> -->
                    <select class="form-select" id="review_schedule_3m" name="review_schedule_3m" data-control="select2" data-placeholder="Select Review Schedule 3 Months" required>
                        <option value="" disabled selected>Select Review Schedule 3 Months</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= esc($employee['id']) ?>"><?= esc($employee['employee_name'] . ' [ ' . $employee['department_name'] . ' ] ' . ' - ' . $employee['company_short_name']) ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="review_schedule_3m">Candidate Review Schedule 3 Months With <span class="text-danger">* </span></label>
                </div>
            </div>




            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <select class="form-select" id="review_schedule_6m" name="review_schedule_6m" data-control="select2" data-placeholder="Select Review Schedule 6 Months" required>
                        <option value="" disabled selected>Select Review Schedule 6 Months</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= esc($employee['id']) ?>"><?= esc($employee['employee_name'] . ' [ ' . $employee['department_name'] . ' ] ' . ' - ' . $employee['company_short_name']) ?> </option>
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
                            <option value="<?= esc($employee['id']) ?>"><?= esc($employee['employee_name'] . ' [ ' . $employee['department_name'] . ' ] ' . ' - ' . $employee['company_short_name']) ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="review_schedule_12m">Candidate Review Schedule 12 Months With <span class="text-danger">* </span></label>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-start">
            <!-- <div class="d-flex align-items-center">
                <label for="kras_file" class="btn btn-outline-success mb-0" style="font-weight:600;">
                    <i class="la la-paperclip"></i> Attach KRA's File
                </label>
                <input type="file" class="d-none" id="kras_file" name="kras_file" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <span id="kras_file_name" class="ms-3 text-success" style="font-weight:500;"></span>
            </div> -->
            <button type="submit" class="btn btn-primary">Submit Job Opening</button>
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

    // function addTechnicalTest() {
    //     const wrapper = document.getElementById('technicalTestNameDiv');

    //     const newRow = document.createElement('div');
    //     newRow.className = 'row align-items-center mb-3 technical-test-row';
    //     newRow.innerHTML = `
    //         <div class="col-10">
    //             <div class="form-floating">
    //                 <input type="text" name="technical_test[]" class="form-control" placeholder="Technical Test">
    //                 <label>Technical Test</label>
    //             </div>
    //         </div>
    //         <div class="col-2 d-flex justify-content-center">
    //             <button type="button" onclick="removeTechnicalTest(this)" class="btn btn-sm btn-outline-danger">
    //                 <i class="la la-trash-o"></i>
    //             </button>
    //         </div>
    //     `;

    //     wrapper.appendChild(newRow);
    //     wrapper.style.display = 'block'; // Show the container when adding tests
    // }

    // function removeTechnicalTest(el) {
    //     const row = el.closest('.technical-test-row');
    //     const wrapper = document.getElementById('technicalTestNameDiv');
    //     const allRows = wrapper.querySelectorAll('.technical-test-row');

    //     if (allRows.length > 1) {
    //         row.remove();
    //     } else {
    //         // If this is the last row, clear it and hide the wrapper
    //         row.remove();
    //         wrapper.style.display = 'none';
    //     }
    // }

    // function addOtherTest() {
    //     const wrapper = document.getElementById('otherTestNameDiv');

    //     const newRow = document.createElement('div');
    //     newRow.className = 'row align-items-center mb-3 other-test-row';
    //     newRow.innerHTML = `
    //         <div class="col-10">
    //             <div class="form-floating">
    //                 <input type="text" name="other_test[]" class="form-control" placeholder="Other Test">
    //                 <label>Other Test</label>
    //             </div>
    //         </div>
    //         <div class="col-2 d-flex justify-content-center">
    //             <button type="button" onclick="removeOtherTest(this)" class="btn btn-sm btn-outline-danger">
    //                 <i class="la la-trash-o"></i>
    //             </button>
    //         </div>
    //     `;

    //     wrapper.appendChild(newRow);
    //     wrapper.style.display = 'block'; // Show the container when adding tests
    // }

    // function removeOtherTest(el) {
    //     const row = el.closest('.other-test-row');
    //     const wrapper = document.getElementById('otherTestNameDiv');
    //     const allRows = wrapper.querySelectorAll('.other-test-row');

    //     if (allRows.length > 1) {
    //         row.remove();
    //     } else {
    //         // If this is the last row, clear it and hide the wrapper
    //         row.remove();
    //         wrapper.style.display = 'none';
    //     }
    // }
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


    /*const budgetOptions = [10000, 20000, 30000, 40000, 50000, 60000, 80000, 100000, 120000, 150000, 200000];

    function updateMaxBudget() {
        const min = parseInt(document.getElementById('min_budget').value);
        const maxSelect = document.getElementById('max_budget');
        maxSelect.innerHTML = '<option value="" disabled selected>Select Max Budget</option>';

        budgetOptions.forEach(amount => {
            if (amount >= min) {
                const option = document.createElement('option');
                option.value = amount;
                option.text = amount.toLocaleString();
                maxSelect.appendChild(option);
            }
        });
    }*/

    // function updateMaxExperience() {
    //     const minExp = parseInt(document.getElementById('min_experience').value);
    //     const maxSelect = document.getElementById('max_experience');
    //     maxSelect.innerHTML = '<option value="" disabled selected>Select Max Experience</option>';

    //     for (let i = minExp + 1; i <= 30; i++) {
    //         const option = document.createElement('option');
    //         option.value = i;
    //         option.text = `${i} year${i === 1 ? '' : 's'}`;
    //         maxSelect.appendChild(option);
    //     }
    // }

    // $('#shift_start, #shift_end').on('change', function() {
    //     const start = shiftStartInstance.altInput.value;
    //     const end = shiftEndInstance.altInput.value;
    //     $('#shift_timing').val(`${start} - ${end}`);

    // });

    // $('#vacancy-increase').on('click', function() {
    //     let val = parseInt($('#no_of_vacancy').val()) || 0;
    //     $('#no_of_vacancy').val(val + 1);
    // });

    // $('#vacancy-decrease').on('click', function() {
    //     let val = parseInt($('#no_of_vacancy').val()) || 0;
    //     if (val > 0) {
    //         $('#no_of_vacancy').val(val - 1);
    //     }
    // });
    jQuery(document).ready(function($) {
        $('.flatpickr-time').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i", // 24-hour format like 14:30
            minuteIncrement: 5,
            altInput: true,
            altFormat: "h:i K", // AM/PM format if preferred
            altInputClass: "form-control form-control-sm"

        });
    });

    // let shiftStartInstance, shiftEndInstance;

    // jQuery(document).ready(function($) {
    //     shiftStartInstance = flatpickr("#shift_start", {
    //         enableTime: true,
    //         noCalendar: true,
    //         dateFormat: "H:i",
    //         altInput: true,
    //         altFormat: "h:i K",
    //         altInputClass: "form-control form-control-sm",
    //         minuteIncrement: 5,
    //         onReady: function(selectedDates, dateStr, instance) {
    //             instance.altInput.placeholder = "Start Time";
    //         }
    //     });

    //     shiftEndInstance = flatpickr("#shift_end", {
    //         enableTime: true,
    //         noCalendar: true,
    //         dateFormat: "H:i",
    //         altInput: true,
    //         altFormat: "h:i K",
    //         altInputClass: "form-control form-control-sm",
    //         minuteIncrement: 5,
    //         onReady: function(selectedDates, dateStr, instance) {
    //             instance.altInput.placeholder = "End Time";
    //         }
    //     });
    // });



    $(document).ready(function() {
        // Prevent form submission on Enter key press
        $('form').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                return false;
            }
        });

        // Allow Enter key only in textarea elements (for Summernote)
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

        // Initial display
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
                    updateVacancyDisplay(vacancy); // Reset if invalid
                }
            }
        });
    });

    $(document).ready(function() {
        // Fetch shift timings from database
        $.ajax({
            url: '<?= base_url('recruitment/job-listing/get-shift-timings') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(shiftTimings) {
                $('#shift_timing').select2({
                    data: shiftTimings,
                    placeholder: 'Select Shift Timing'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching shift timings:', error);
                // Fallback to empty select2 if fetch fails
                $('#shift_timing').select2({
                    placeholder: 'Select Shift Timing'
                });
            }
        });

        // Industry options in JSON format
        const industries = [
            "Accounting",
            "Airlines / Aviation",
            "Alternative Dispute Resolution",
            "Alternative Medicine",
            "Animation",
            "Apparel / Fashion",
            "Architecture / Planning",
            "Arts / Crafts",
            "Automotive",
            "Aviation / Aerospace",
            "Banking / Mortgage",
            "Biotechnology / Greentech",
            "Broadcast Media",
            "Building Materials",
            "Business Supplies / Equipment",
            "Capital Markets / Hedge Fund / Private Equity",
            "Chemicals",
            "Civic / Social Organization",
            "Civil Engineering",
            "Commercial Real Estate",
            "Computer Games",
            "Computer Hardware",
            "Computer Networking",
            "Computer Software / Engineering",
            "Computer / Network Security",
            "Construction",
            "Consumer Electronics",
            "Consumer Goods",
            "Consumer Services",
            "Cosmetics",
            "Dairy",
            "Defense / Space",
            "Design",
            "Ecommerce",
            "ELearning",
            "Education Management",
            "Electrical / Electronic Manufacturing",
            "Entertainment / Movie Production",
            "Environmental Services",
            "Events Services",
            "Executive Office",
            "Facilities Services",
            "Farming",
            "Financial Services",
            "Fine Art",
            "Fishery",
            "Food Production",
            "Food / Beverages",
            "Fundraising",
            "Furniture",
            "Gambling / Casinos",
            "Glass / Ceramics / Concrete",
            "Government Administration",
            "Government Relations",
            "Graphic Design / Web Design",
            "Health / Fitness",
            "Higher Education / Acadamia",
            "Hospital / Health Care",
            "Hospitality",
            "Human Resources / HR",
            "Import / Export",
            "Individual / Family Services",
            "Industrial Automation",
            "Information Services",
            "Information Technology / IT",
            "Insurance",
            "International Affairs",
            "International Trade / Development",
            "Internet",
            "Investment Banking / Venture",
            "Investment Management / Hedge Fund / Private Equity",
            "Judiciary",
            "Law Enforcement",
            "Law Practice / Law Firms",
            "Legal Services",
            "Legislative Office",
            "Leisure / Travel",
            "Library",
            "Logistics / Procurement",
            "Luxury Goods / Jewelry",
            "Machinery",
            "Management Consulting",
            "Maritime",
            "Market Research",
            "Marketing / Advertising / Sales",
            "Mechanical or Industrial Engineering",
            "Media Production",
            "Medical Equipment",
            "Medical Practice",
            "Mental Health Care",
            "Military Industry",
            "Mining / Metals",
            "Motion Pictures / Film",
            "Museums / Institutions",
            "Music",
            "Nanotechnology",
            "Newspapers / Journalism",
            "Non - Profit / Volunteering",
            "Oil / Energy / Solar / Greentech",
            "Online Publishing",
            "Other Industry",
            "Outsourcing / Offshoring",
            "Package / Freight Delivery",
            "Packaging / Containers",
            "Paper / Forest Products",
            "Performing Arts",
            "Pharmaceuticals",
            "Philanthropy",
            "Photography",
            "Plastics",
            "Political Organization",
            "Primary / Secondary Education",
            "Printing",
            "Professional Training",
            "Program Development",
            "Public Relations / PR",
            "Public Safety",
            "Publishing Industry",
            "Railroad Manufacture",
            "Ranching",
            "Real Estate / Mortgage",
            "Recreational Facilities / Services",
            "Religious Institutions",
            "Renewables / Environment",
            "Research Industry",
            "Restaurants",
            "Retail Industry",
            "Security / Investigations",
            "Semiconductors",
            "Shipbuilding",
            "Sporting Goods",
            "Sports",
            "Staffing / Recruiting",
            "Supermarkets",
            "Telecommunications",
            "Textiles",
            "Think Tanks",
            "Tobacco",
            "Translation / Localization",
            "Transportation",
            "Utilities",
            "Venture Capital / VC",
            "Veterinary",
            "Warehousing",
            "Wholesale",
            "Wine / Spirits",
            "Wireless",
            "Clinical Research",
            "Diagnostic Laboratories",
            "Dental Services",
            "Physical Therapy",
            "Occupational Therapy",
            "Speech Therapy",
            "Home Health Care",
            "Medical Devices",
            "Medical Billing Services",
            "Health Informatics",
            "Nursing",
            "Pathology",
            "Radiology",
            "Surgical Centers",
            "Telemedicine",
            "Genetics & Genomics",
            "Immunology",
            "Health Insurance",
            "Pharmacology",
            "Biomedical Engineering",
            "Rehabilitation Services",
            "Mental Health Services",
            "Pediatrics",
            "Geriatrics",
            "Emergency Medical Services",
            "Writing / Editing", "Any"
        ];

        // Convert industries array to Select2 format
        const industryOptions = industries.map(industry => ({
            id: industry,
            text: industry
        }));

        // Initialize Select2 for specific industry
        $('#specific_industry').select2({
            data: industryOptions,
            placeholder: 'Select Industry',
            allowClear: true
        });

        // Test Required Modal functionality
        $('#testRequiredBtn').on('click', function() {
            $('#testModal').modal('show');
        });

        // Show/hide technical test name input and add more button
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

        // NEW CODE - Display selected file name for other test files
        $(document).on('change', '.other-test-file', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).closest('.form-group').find('.file-name-display').text(fileName ? fileName : '');
        });

        // Variable to track if repeater has been initialized
        var otherTestsRepeaterInitialized = false;
        var $other_tests;

        // Show/hide other test name input and add more button
        $('input[name="other_test"][type="radio"]').on('change', function() {
            if ($(this).val() === 'Yes') {
                const wrapper = $('#otherTestNameDiv');
                wrapper.show();

                // Initialize repeater only once when first shown
                if (!otherTestsRepeaterInitialized) {
                    $other_tests = $('#other_tests').repeater({
                        initEmpty: false,
                        show: function() {
                            $(this).slideDown();
                        },
                        hide: function(deleteElement) {
                            $(this).slideUp(deleteElement);
                        },
                        ready: function() {}
                    });
                    otherTestsRepeaterInitialized = true;
                }
            } else {
                $('#otherTestNameDiv').hide();
            }
        });


        // Save test configuration
        $('#saveTestConfig').on('click', function() {
            $('.alert-danger').remove();
            $('.is-invalid').removeClass('is-invalid');

            const technical = $('input[name="technical_test"]:checked').val() || '';
            const iq = $('input[name="iq_test"]:checked').val() || '';
            const english = $('input[name="english_test"]:checked').val() || '';
            const operation = $('input[name="operation_test"]:checked').val() || '';
            const other = $('input[name="other_test"]:checked').val() || '';

            const errors = [];
            let firstErrorField = null;

            // if (!technical) {
            //     errors.push('Technical Test Required field is required');
            //     $('input[name="technical_test"]').addClass('is-invalid');
            //     if (!firstErrorField) firstErrorField = $('input[name="technical_test"]').first();
            // }

            if (!iq) {
                errors.push('IQ Test Required field is required');
                $('input[name="iq_test"]').addClass('is-invalid');
                if (!firstErrorField) firstErrorField = $('input[name="iq_test"]').first();
            }

            if (!english) {
                errors.push('English Test Required field is required');
                $('input[name="english_test"]').addClass('is-invalid');
                if (!firstErrorField) firstErrorField = $('input[name="english_test"]').first();
            }

            if (!operation) {
                errors.push('Operation Test Required field is required');
                $('input[name="operation_test"]').addClass('is-invalid');
                if (!firstErrorField) firstErrorField = $('input[name="operation_test"]').first();
            }

            if (!other) {
                errors.push('Other Test Required field is required');
                $('input[name="other_test"]').addClass('is-invalid');
                if (!firstErrorField) firstErrorField = $('input[name="other_test"]').first();
            }

            if (errors.length > 0) {
                showErrorNotification('Please fill all required fields:<br>• ' + errors.join('<br>• '));

                if (firstErrorField) {
                    firstErrorField.closest('.col-md-12').get(0).scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                return;
            }

            const technicalName = $('#technicalTestName').val();
            const otherName = $('#otherTestName').val();

            $('#technical_test_required').val(technical);
            $('#technical_test_name').val(technicalName);
            $('#iq_test_required').val(iq);
            $('#eng_test_required').val(english);
            $('#operation_test_required').val(operation);
            $('#other_test_required').val(other);
            $('#other_test_name').val(otherName);

            const summary = [];
            // if (technical === 'Yes' && technicalName) summary.push(`${technicalName}: Yes`);
            // else if (technical && technical !== 'No') summary.push(`Technical: ${technical}`);
            if (iq) summary.push(`IQ: ${iq}`);
            if (english) summary.push(`English: ${english}`);
            if (operation) summary.push(`Operation: ${operation}`);
            if (other === 'Yes' && otherName) summary.push(`${otherName}: Yes`);
            else if (other && other !== 'No') summary.push(`Other: ${other}`);


            const summaryText = summary.length > 0 ? summary.join(', ') : 'No tests configured';
            $('#testSummary').html(`<strong>Tests:</strong> ${summaryText}`);

            if (summary.length > 0) {
                $('#testRequiredBtn').removeClass('btn-outline-primary').addClass('btn-success');
            } else {
                $('#testRequiredBtn').removeClass('btn-success').addClass('btn-outline-primary');
            }

            $('#tests_drawer_close').click();

            showSuccessNotification('Test configuration saved successfully!');
        });

        $('#kras_file').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#kras_file_name').text(fileName ? fileName : '');
        });

        $('form').on('submit', function(e) {
            const testSummaryText = $('#testSummary').text();
            const hasTestsConfigured = testSummaryText && !testSummaryText.includes('No tests configured') && testSummaryText.trim() !== '';

            $('#tests_drawer_button').removeClass('border-danger bg-light-danger').addClass('border-primary bg-light-primary');

            if (!hasTestsConfigured) {
                e.preventDefault();

                $('#tests_drawer_button')
                    .removeClass('border-primary bg-light-primary')
                    .addClass('border-danger bg-light-danger');

                $('#tests_drawer_button')[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                showErrorNotificationOnPage('Please configure the required tests before submitting.');

                return false;
            }
        });
    });

    function showSuccessNotification(message) {
        $('.alert-success').remove();

        const alert = $(`<div class="alert alert-success alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`);

        $('h2.mb-4').after(alert);

        setTimeout(() => {
            alert.fadeOut(500, () => alert.remove());
        }, 3000);
    }

    function showErrorNotification(message) {
        $('.alert-danger').remove();

        const alert = $(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`);

        $('.card-body.hover-scroll-overlay-y').prepend(alert);

        setTimeout(() => {
            alert.fadeOut(500, () => alert.remove());
        }, 5000);
    }

    function showErrorNotificationOnPage(message) {
        $('.alert-danger').remove();

        const alert = $(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`);

        $('h2.mb-4').after(alert);

        setTimeout(() => {
            alert.fadeOut(500, () => alert.remove());
        }, 5000);
    }
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>