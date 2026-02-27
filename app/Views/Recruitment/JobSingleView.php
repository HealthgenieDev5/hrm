<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css" rel="stylesheet">
<style>
    .card .card-header {
        display: inline;
    }

    .comments-container {
        max-height: 600px;
        overflow-y: auto;
    }

    .comment-item {
        border-left: 3px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        transition: all 0.3s ease;
    }


    .comment-item:hover {
        border-left-color: #007bff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .comment-type-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .comment-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .comment-content {
        background: white;
        border-radius: 6px;
        padding: 12px;
        margin-top: 10px;
        border: 1px solid #e9ecef;
    }

    .comment-type-question .comment-item {
        border-left-color: #ffc107;
    }

    .comment-type-concern .comment-item {
        border-left-color: #dc3545;
    }

    .comment-type-suggestion .comment-item {
        border-left-color: #28a745;
    }

    #comments-loading {
        width: 1rem;
        height: 1rem;
    }

    .comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    .comment-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f8f9fa;
        border: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 24px;
    }

    .reply-btn {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
        border: 1px solid #0d6efd !important;
        color: #0d6efd !important;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.15) !important;
        transform: scale(1) !important;
        font-weight: 500 !important;
    }

    .reply-btn:hover {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
        color: white !important;
        transform: scale(1.05) !important;
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3) !important;
        border-color: #0b5ed7 !important;
    }

    .reply-btn:active {
        transform: scale(0.98) !important;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.4) !important;
    }

    .reply-btn i {
        transition: transform 0.2s ease !important;
    }

    .reply-btn:hover i {
        transform: rotate(-10deg) !important;
    }

    .trail-indicator .badge {
        font-size: 10px !important;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 12px;
    }

    .timeline-item[data-comment-type="issue"] .timeline-content {
        position: relative;
    }

    .timeline-item[data-comment-type="resolution"] .timeline-content {
        position: relative;
        margin-left: 15px;
    }

    .submit-issue-btn {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 50%, #ff4757 100%) !important;
        border: none !important;
        color: white !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        padding: 12px 24px !important;
        border-radius: 10px !important;
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        transform: translateY(0) !important;
        position: relative !important;
        overflow: hidden !important;
        width: auto !important;
        text-transform: none !important;
        letter-spacing: 0.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 180px !important;
    }

    .submit-issue-btn:hover {
        background: linear-gradient(135deg, #ff5252 0%, #e53935 50%, #d32f2f 100%) !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 12px 35px rgba(255, 107, 107, 0.4) !important;
        color: white !important;
    }

    .submit-issue-btn:active {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.3) !important;
    }

    .submit-issue-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .submit-issue-btn:hover::before {
        left: 100%;
    }

    .submit-issue-btn i {
        margin-right: 8px !important;
        font-size: 14px !important;
        transition: transform 0.3s ease !important;
    }

    .submit-issue-btn:hover i {
        transform: scale(1.1) rotate(-5deg) !important;
    }

    .issue-button-container {
        background: linear-gradient(135deg, #f8faff 0%, #ffffff 40%, #f1f5f9 100%);
        border: 1px solid #e3e6f0;
        border-radius: 5px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .issue-button-container:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 30%, #e2e8f0 100%);
    }


    .issue-button-text {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 15px;
        text-align: center;
        font-weight: 500;
        opacity: 0.8;
    }
</style>

<div class="container mt-4">
    <div class="post d-flex flex-column-fluid">
        <div class="container-xxl">
            <!-- <div class="d-flex justify-content-end mb-4">
                <a href="<?= site_url('/recruitment/job-listing') ?>" class="btn btn-primary">Create Job Listing</a>
            </div> -->
            <?php if (empty($job)): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h2 class="text-muted">No Job Listing Found</h2>
                            <p class="text-muted">Get started by adding a new job listing.</p>
                            <a href="<?= site_url('/recruitment/job-listing') ?>" class="btn btn-primary">Add New Job Listing</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php
                $status = strtolower($job->status);
                $badgeClass = match ($status) {
                    'active'       => 'primary',
                    'in progress' => 'success',
                    'closed'     => 'danger',
                    'pending'    => 'warning',
                    'draft'      => 'secondary',
                    default      => 'info'
                };
                $jobOpeningDate = !empty($job->job_opening_date) && $job->job_opening_date !== '0000-00-00'
                    ? date('d M, Y', strtotime($job->job_opening_date))
                    : 'Not Set Yet';

                $created_at = !empty($job->created_at)
                    ? date('d M, Y', strtotime($job->created_at))
                    : 'Not Set';
                $currentEmployeeId = session()->get('current_user')['employee_id'] ?? null;
                $isCreator = isset($job->created_by) && $job->created_by === $currentEmployeeId;
                $isHod     = !empty($job->department_hod_id) && $job->department_hod_id === $currentEmployeeId;

                $canEditJob = $currentEmployeeId !== null && (
                    ($isCreator && empty($job->approved_by_hr_executive)) ||
                    ($isHod     && empty($job->approved_by_hr_manager))
                );


                ?>


                <div class="card my-5">
                    <div class="card mb-6 mb-xl-9">
                        <div class="card-body pt-9 pb-0">
                            <!--begin::Details-->



                            <div class="d-flex flex-wrap flex-sm-nowrap">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-3">
                                                    <?= esc($job->job_title) ?>
                                                </a>
                                                <span class="badge badge-light-<?= esc($badgeClass) ?> me-auto" id="show-status">
                                                    <?= esc(ucwords($job->status)) ?>
                                                </span>
                                            </div>

                                            <div class="d-flex flex-wrap fw-bold mb-4 fs-5 text-gray-400">
                                                <?= esc($job->company_name ?? 'N/A') ?> requires the
                                                <?= esc($job->type_of_job ?? 'N/A') ?> role in the </br>
                                                <?= esc($job->department_name ?? 'N/A') ?> department to be filled from
                                                <?= esc($created_at) ?>
                                            </div>
                                        </div>

                                        <div class="d-flex mb-4">
                                            <?php if (!empty($job->approved_by_hr_manager)): ?>
                                                <a href="<?= site_url('recruitment/job-listing/download-job-opening-pdf/' . $job->id) ?>" class="btn btn-sm btn-bg-light btn-active-color-primary me-3">
                                                    Download Job Opening PDF
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($status === 'closed'): ?>
                                                <a href="<?= site_url('recruitment/job-listing/download-job-closure-pdf/' . $job->id) ?>" class="btn btn-sm btn-bg-light btn-active-color-primary me-3">Download Job Closure PDF</a>
                                            <?php endif; ?>
                                            <?php if ($canEditJob): ?>
                                                <a href="<?= site_url('recruitment/job-listing/edit/' . $job->id) ?>" class="btn btn-sm btn-primary me-3">Edit</a>
                                            <?php endif; ?>
                                            <?php if (empty($job->approved_by_hr_executive) && $job->status != 'closed'): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger me-3 update-job-listing"
                                                    onclick="updateJobListingStatus(<?= $job->id ?>, 'rejected')">
                                                    Reject
                                                </button>
                                            <?php else: ?>
                                                <!-- <button type="button"
                                                    class="btn btn-sm btn-danger me-3 update-job-listing"
                                                    onclick="updateJobListingStatus(<?= $job->id ?>, 'closed')">
                                                    Close
                                                </button> -->
                                            <?php endif; ?>

                                            <!-- <div class="me-0">
                                                <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    <i class="bi bi-three-dots fs-3"></i>
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Status</div>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">Close</a>
                                                    </div>

                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">Rejected</a>
                                                    </div>


                                                </div>
                                            </div> -->

                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="row g-5 mb-5 mb-lg-15">
                                <div class="col-md-6 pe-lg-10">
                                    <div class="d-flex flex-stack position-relative">
                                        <div class="position-absolute h-100 w-4px bg-primary rounded top-0 start-0"></div>
                                        <div class="fw-bold ms-5 text-gray-600">
                                            <div class="fs-6 mb-3">
                                                <span class="text-muted">💰 Salary Package -</span>
                                                <span class="text-success fw-bolder">₹<?= esc(number_format($job->min_budget)) ?> - ₹<?= esc(number_format($job->max_budget)) ?></span>
                                            </div>
                                            <div class="fs-6 mb-3">
                                                <span class="text-muted">🎯 Experience Needed -</span>
                                                <span class="text-primary fw-bolder"><?= esc(number_format($job->min_experience)) ?> - <?= esc(number_format($job->max_experience)) ?> Years</span>
                                            </div>
                                            <div class="fs-6 mb-3">
                                                <span class="text-muted">👥 Vacancies -</span>
                                                <span class="text-warning fw-bolder"><?= esc($job->no_of_vacancy ?? '1') ?> Position<?= ($job->no_of_vacancy ?? 1) > 1 ? 's' : '' ?></span>
                                            </div>
                                            <div class="fs-6">
                                                <span class="text-muted">📅 Position Opens -</span>
                                                <span class="text-info fw-bold"><?= esc($jobOpeningDate) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-3 border-end">
                                    <div class="d-flex align-items-center justify-content-center flex-column h-100">
                                        <?php
                                        $currentUser = session()->get('current_user')['employee_id'];
                                        $hrExecutive = $job->approved_by_hr_executive;
                                        $hodApproval = $job->approved_by_hod ?? null;
                                        $hrManager = $job->approved_by_hr_manager;
                                        $departmentHodId = $job->department_hod_id;
                                        // Job Closure Section - only show if fully approved
                                        if (!empty($hrExecutive) && !empty($hodApproval) && !empty($hrManager)) {
                                            // Step 5: Job Closure Process
                                            if ($job->status == 'partially closed') {
                                                echo '<div class="alert alert-warning mb-3">';
                                                echo '<i class="fas fa-exclamation-triangle me-2"></i>';
                                                echo '<strong>Job Partially Closed by HR</strong><br>';
                                                echo '<small class="text-muted">Awaiting reporting manager to complete closure process</small>';
                                                echo '</div>';
                                                if ($currentUser == $departmentHodId || $currentUser == $job->reporting_to) {
                                                    echo '<button class="btn btn-success w-100 text-center job-close-btn mt-2" data-job-id="' . $job->id . '" data-closure-stage="reporting_manager">';
                                                    echo '<i class="fas fa-check-circle me-2"></i>Complete Final Closure';
                                                    echo '</button>';
                                                } else {
                                                    echo '<div class="text-center text-muted">';
                                                    echo '<i class="fas fa-user-clock fs-2 mb-2"></i>';
                                                    echo '<p class="small mb-0">Awaiting Manager Action</p>';
                                                    echo '</div>';
                                                }
                                            } elseif ($job->status != 'closed' && $job->status != 'partially closed') {
                                                // Show close button for HR Executive
                                                if ($currentUser == $hr_executive) { // HR Executive
                                                    echo '<div class="text-center">';
                                                    echo '<div class="card border-primary mb-3">';
                                                    echo '<div class="card-body p-4">';
                                                    echo '<i class="fas fa-clipboard-check text-primary fs-2 mb-3"></i>';
                                                    echo '<h6 class="text-primary fw-bold mb-2">Ready to Close Job</h6>';
                                                    echo '<p class="text-muted small mb-3">All approvals completed. Initiate the job closure process.</p>';
                                                    echo '<button class="btn btn-gradient-danger w-100 shadow-sm job-close-btn" data-job-id="' . $job->id . '" data-closure-stage="hr_executive" style="background: linear-gradient(45deg, #dc3545, #c82333); border: none;">';
                                                    echo '<i class="fas fa-handshake me-2"></i>Start Job Closure';
                                                    echo '</button>';
                                                    echo '<small class="text-muted d-block mt-2">Select candidate & finalize</small>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                } else {
                                                    echo '<div class="text-center text-muted">';
                                                    echo '<div class="card border-light">';
                                                    echo '<div class="card-body p-4">';
                                                    echo '<i class="fas fa-user-shield fs-2 mb-3 text-info"></i>';
                                                    echo '<h6 class="text-muted mb-2">HR Action Required</h6>';
                                                    echo '<p class="small mb-0">Waiting for HR Executive to initiate job closure</p>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                            } elseif ($job->status == 'closed') {
                                                echo '<div class="text-center">';
                                                echo '<div class="alert alert-success">';
                                                echo '<i class="fas fa-check-circle me-2"></i>Job Closed';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<div class="text-center text-muted">';
                                            echo '<i class="fas fa-clock fs-2 mb-2"></i>';
                                            echo '<p class="small mb-0">Awaiting Full Approval</p>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="d-flex align-items-center justify-content-center flex-column h-100">


                                        <?php


                                        if (empty($hrExecutive)) {
                                            if ($currentUser == $hr_executive && ($job->status != 'closed' && $job->status != 'rejected')) {
                                                echo '<button class="btn btn-warning w-100 text-center approval-btn" data-job-id="' . $job->id . '" data-approval-type="hr_executive">';
                                                echo '<span class="indicator-label">Approve as HR  </span>';
                                                echo '<span class="indicator-progress d-none">Processing... <span class="spinner-border spinner-border-sm ms-2"></span></span>';
                                                echo '</button>';
                                            } else {
                                                echo '<div class="badge badge-warning">Pending HR  Approval</div>';
                                            }
                                            echo '<small class="text-muted mt-2 d-block">Awaiting HR  approval</small>';
                                        } elseif (!empty($hrExecutive) && empty($hodApproval)) {
                                            echo '<div class="mb-3">';
                                            echo '<div class="badge badge-light-success mb-2">✓ HR  Approved</div>';
                                            echo '</div>';

                                            if ($currentUser == $departmentHodId && ($job->status != 'closed' && $job->status != 'rejected')) {
                                                echo '<button class="btn btn-info w-100 text-center approval-btn mb-3" data-job-id="' . $job->id . '" data-approval-type="hod">';
                                                echo '<span class="indicator-label">Approve as HOD</span>';
                                                echo '<span class="indicator-progress d-none">Processing... <span class="spinner-border spinner-border-sm ms-2"></span></span>';
                                                echo '</button>';
                                            } else {
                                                echo '<div class="badge badge-info">Awaiting HOD Approval</div>';
                                                if (!empty($job->department_hod_name)) {
                                                    echo '<small class="text-muted mt-2 d-block">Awaiting approval from ' . esc($job->department_hod_name) . ' (HOD)</small>';
                                                } else {
                                                    echo '<small class="text-muted mt-2 d-block">Awaiting HOD approval</small>';
                                                }
                                            }
                                        } elseif (!empty($hrExecutive) && !empty($hodApproval) && empty($hrManager)) {
                                            echo '<div class="mb-3">';
                                            echo '<div class="badge badge-light-success mb-2">✓ HR  Approved</div><br>';
                                            echo '<div class="badge badge-light-success mb-2">✓ HOD Approved</div>';
                                            echo '</div>';

                                            if ($currentUser == $hr_manager && ($job->status != 'closed' && $job->status != 'rejected')) {
                                                echo '<button class="btn btn-primary w-100 text-center approval-btn" data-job-id="' . $job->id . '" data-approval-type="hr_manager">';
                                                echo '<span class="indicator-label">Approve as HR Manager</span>';
                                                echo '<span class="indicator-progress d-none">Processing... <span class="spinner-border spinner-border-sm ms-2"></span></span>';
                                                echo '</button>';
                                            } else {
                                                echo '<div class="badge badge-primary">Awaiting HR Manager Approval</div>';
                                            }
                                            echo '<small class="text-muted mt-2 d-block">Awaiting HR Manager approval</small>';
                                        }
                                        // Step 4: Fully Approved
                                        elseif (!empty($hrExecutive) && !empty($hodApproval) && !empty($hrManager)) {
                                            echo '<div class="text-center">';
                                            echo '<div class="badge badge-light-success mb-2">✓ HR  Approved</div><br>';
                                            echo '<div class="badge badge-light-success mb-2">✓ HOD Approved</div><br>';
                                            echo '<div class="badge badge-light-success mb-3">✓ HR Manager Approved</div>';
                                            echo '<div class="fw-bold text-success">Fully Approved</div>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>

                            </div>


                            <div class="separator"></div>



                            <!--begin::Nav Tabs-->
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder" role="tablist">
                                <li class="nav-item"><a class="nav-link active py-5 me-6" data-bs-toggle="tab" href="#overview<?= $job->id ?>">Overview</a></li>
                                <li class="nav-item"><a class="nav-link py-5 me-6" data-bs-toggle="tab" href="#tests<?= $job->id ?>">Tests</a></li>
                                <li class="nav-item"><a class="nav-link py-5 me-6" data-bs-toggle="tab" href="#review<?= $job->id ?>">Review Schedule</a></li>
                                <li class="nav-item"><a class="nav-link py-5 me-6" data-bs-toggle="tab" href="#attachment<?= $job->id ?>">Attachment</a></li>
                                <li class="nav-item"><a class="nav-link py-5 me-6" data-bs-toggle="tab" href="#others<?= $job->id ?>">Job Description</a></li>
                            </ul>
                            <!--end::Nav Tabs-->

                            <div class="tab-content mt-4">
                                <!-- Overview -->
                                <div class="tab-pane fade show active" id="overview<?= $job->id ?>">
                                    <p>
                                        <i class="bi bi-gear text-muted me-1" data-bs-toggle="tooltip" title="Department Type"></i>
                                        <strong>Department:</strong> <?= esc($job->department_name) ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-building text-muted me-1" data-bs-toggle="tooltip" title="Industry Type"></i>
                                        <strong>Industry:</strong> <?= esc($job->specific_industry) ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-geo-alt text-muted me-1" data-bs-toggle="tooltip" title="Seating Location"></i>
                                        <strong>Seating Location:</strong> <?= esc($job->seating_location) ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-geo text-muted me-1" data-bs-toggle="tooltip" title="Interview Location"></i>
                                        <strong>Interview Location:</strong> <?= esc($job->interview_location) ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-calendar text-muted me-1" data-bs-toggle="tooltip" title="Shift"></i>
                                        <strong>Shift:</strong> <?= esc($job->shift_timing) ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-book text-muted me-1" data-bs-toggle="tooltip" title="Qualification"></i>
                                        <strong>Educational Qualification::</strong> <?= esc($job->educational_qualification) ?>
                                    </p>

                                    <p>
                                        <i class="bi bi-calendar text-muted me-1" data-bs-toggle="tooltip" title="System Required"></i>
                                        <strong>System Required:</strong> <?= esc($job->system_required) ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-person text-muted me-1" data-bs-toggle="tooltip" title="Created By"></i>
                                        <strong>Job Posted By:</strong> <?= esc($job->created_by_name ?? 'N/A') ?>
                                    </p>

                                </div>

                                <!-- Tests -->
                                <div class="tab-pane fade" id="tests<?= $job->id ?>">
                                    <?php /* Technical Test - commented out
                                    <p>
                                        <i class="bi bi-clipboard-check text-muted me-1" data-bs-toggle="tooltip" title="Technical Test"></i>
                                        <strong>Technical Test:</strong>
                                        <?php
                                        $technicalTest = json_decode($job->technical_test_required, true);
                                        if (is_array($technicalTest) && isset($technicalTest['required'])) {
                                            echo esc($technicalTest['required']);
                                            if ($technicalTest['required'] === 'Yes' && isset($technicalTest['tests']) && is_array($technicalTest['tests'])) {
                                                echo ' - ' . esc(implode(' | ', $technicalTest['tests']));
                                            }
                                        } else {
                                            echo esc($job->technical_test_required);
                                        }
                                        ?>
                                    </p>
                                    */ ?>
                                    <p>
                                        <i class="bi bi-lightbulb text-muted me-1" data-bs-toggle="tooltip" title="IQ Test"></i>
                                        <strong>IQ Test:</strong>
                                        <?php
                                        $iqTest = json_decode($job->iq_test_required, true);
                                        echo $iqTest['required'];

                                        ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-translate text-muted me-1" data-bs-toggle="tooltip" title="English Test"></i>
                                        <strong>English Test:</strong>
                                        <?php $englishTest = json_decode($job->eng_test_required, true);
                                        echo $englishTest['required'];
                                        ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-gear text-muted me-1" data-bs-toggle="tooltip" title="Operation Test"></i>
                                        <strong>Operation Test:</strong>
                                        <?php $operationTest = json_decode($job->operation_test_required, true);
                                        echo $operationTest['required'];
                                        ?>
                                    </p>
                                    <div>
                                        <i class="bi bi-plus-circle text-muted me-1" data-bs-toggle="tooltip" title="Other Tests"></i>
                                        <strong>Any Other Test Required:</strong>
                                        <?php
                                        $otherTest = json_decode($job->other_test_required, true);
                                        if (is_array($otherTest) && isset($otherTest['required'])) {
                                            echo esc($otherTest['required']);
                                            if ($otherTest['required'] === 'Yes' && isset($otherTest['tests']) && is_array($otherTest['tests'])) {
                                                echo '<ul class="mt-1 mb-0">';
                                                foreach ($otherTest['tests'] as $test) {
                                                    if (is_array($test)) {
                                                        // New format: {"name": "test", "file": "..."}
                                                        $testName = esc($test['name'] ?? '');
                                                        $testFile = $test['file'] ?? '';
                                                        echo '<li>' . $testName;
                                                        if (!empty($testFile)) {
                                                            echo ' <a href="' . base_url($testFile) . '" target="_blank" class="ms-2"><i class="bi bi-file-earmark-arrow-down"></i> View File</a>';
                                                        }
                                                        echo '</li>';
                                                    } else {
                                                        // Old format: "test"
                                                        echo '<li>' . esc($test) . '</li>';
                                                    }
                                                }
                                                echo '</ul>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!-- Review -->
                                <div class="tab-pane fade" id="review<?= $job->id ?>">
                                    <p>
                                        <i class="bi bi-person-badge text-muted me-1" data-bs-toggle="tooltip" title="Reporting Manager"></i>
                                        <strong>Reporting To:</strong> <?= esc($job->reporting_to_name ?? 'N/A') ?>
                                        <?php if (!empty($job->reporting_to_designation)): ?>
                                            (<?= esc($job->reporting_to_designation) ?>)
                                        <?php endif; ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-calendar3-week text-muted me-1" data-bs-toggle="tooltip" title="3 Month Reviewer"></i>
                                        <strong>3 Month Reviewer:</strong> <?= esc($job->review_schedule_3m_name ?? 'N/A') ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-calendar3 text-muted me-1" data-bs-toggle="tooltip" title="6 Month Reviewer"></i>
                                        <strong>6 Month Reviewer:</strong> <?= esc($job->review_schedule_6m_name ?? 'N/A') ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-calendar-check text-muted me-1" data-bs-toggle="tooltip" title="12 Month Reviewer"></i>
                                        <strong>12 Month Reviewer:</strong> <?= esc($job->review_schedule_12m_name ?? 'N/A') ?>
                                    </p>
                                </div>
                                <!-- file attachment -->
                                <div class="tab-pane fade" id="attachment<?= $job->id ?>">
                                    <?php
                                    // Try to get the file(s) from kra_distribution_file or attachment
                                    $kraFiles = [];
                                    if (!empty($job->attachment)) {
                                        $kraFiles = json_decode($job->attachment, true);
                                        if (is_string($kraFiles)) {
                                            $kraFiles = [$kraFiles];
                                        }
                                        if (isset($kraFiles['file'])) {
                                            $kraFiles = [$kraFiles['file']];
                                        }
                                    }
                                    ?>
                                    <?php if (!empty($kraFiles)): ?>
                                        <p>
                                            <i class="bi bi-file-earmark-text text-muted me-1" data-bs-toggle="tooltip" title="KRA's File"></i>
                                            <strong>KRA's File(s):</strong>
                                        </p>
                                        <?php foreach ($kraFiles as $kraFile): ?>
                                            <?php
                                            // If $kraFile is an array with 'file' key, use that
                                            if (is_array($kraFile) && isset($kraFile['file'])) {
                                                $kraFile = $kraFile['file'];
                                            }
                                            ?>
                                            <?php if (!empty($kraFile) && is_string($kraFile)): ?>
                                                <div class="mb-2">
                                                    <a href="<?= base_url(ltrim($kraFile, '/')) ?>" target="_blank" class="btn btn-sm btn-light-primary">
                                                        <i class="bi bi-download"></i> <?= basename($kraFile) ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No KRA's file attached.</span>
                                    <?php endif; ?>
                                </div>


                                <!-- Others -->
                                <div class="tab-pane fade" id="others<?= $job->id ?>">
                                    <div>
                                        <i class="bi bi-file-earmark-text text-muted me-1" data-bs-toggle="tooltip" title="Job Description"></i>
                                        <strong>Job Description / Requirement:</strong>
                                        <?php
                                        echo $job->job_description;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="fw-bolder m-0 text-gray-800">Issues and Resolution</h3>
                        </div>
                    </div>
                    <div class="card-body" id="comments-section">
                        <div id="comment-notifications"></div>

                        <div class="mb-5">
                            <div class="issue-button-container">
                                <div class="issue-button-text">
                                    If you notice incorrect job details, misaligned requirements, or any concerns with this posting, please raise an issue for review and correction.
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn submit-issue-btn" id="submit-issue-btn" data-bs-toggle="modal" data-bs-target="#commentModal">
                                        <i class="fas fa-exclamation-triangle"></i>Report a Job Posting Issue
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Comment Modal -->
                        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="commentModalLabel">Write Your Message</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="comment-form" method="post">
                                            <input type="hidden" name="job_id" value="<?= $job->id ?>">

                                            <!-- Hidden parent comment ID field -->
                                            <input type="hidden" id="parentCommentId" name="parent_comment_id" value="">

                                            <!-- Hidden comment type field -->
                                            <input type="hidden" id="commentType" name="type" value="issue">

                                            <div class="mb-3">
                                                <!-- <label for="commentText" class="form-label">Your Message</label> -->
                                                <textarea class="form-control summernote" id="commentText" name="comment" rows="5" placeholder="Write your message here..." required></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary" id="submit-comment-btn">
                                            <span id="submit-btn-text">Submit</span>
                                            <span id="submit-btn-loading" class="d-none">
                                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                Submitting...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    Timeline (<span id="comments-count">0</span>)
                                </h5>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2 small" id="last-updated">Last updated: Never</span>
                                    <div class="spinner-border spinner-border-sm d-none" role="status" id="comments-loading">
                                        <span class="visually-hidden ">Loading...</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <style>
                                        .timeline-label .timeline-label {
                                            width: 170px !important;
                                        }

                                        .timeline-label::before {
                                            left: 171px;
                                        }
                                    </style>

                                    <div id="comments-container" class="comments-container">
                                        <div class="text-center text-muted py-4" id="no-comments-message">
                                            <i class="bi bi-chat-dots fs-1 text-primary"></i>
                                            <p class="mt-2 fw-semibold">No issues reported yet</p>
                                            <p class="text-muted small">Be the first to report an issue or share your thoughts!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>

                <?php if (!empty($hrExecutive) && !empty($hodApproval) && !empty($hrManager)): ?>
                    <!-- Recruitment Tasks Card -->
                    <div class="card card-flush mt-6 mb-6">
                        <div class="card-header d-flex align-items-center py-5 gap-2 justify-content-between">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">
                                    <i class="fas fa-tasks text-primary me-2"></i>Recruitment Tasks
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <?php if ($currentUser == $hr_executive): ?>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignTaskModal">
                                        <i class="fas fa-plus me-1"></i>Assign Task
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <!-- Loading spinner -->
                            <div id="tasks-loading" class="text-center py-6 d-none">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Loading tasks…</p>
                            </div>
                            <!-- Empty state -->
                            <div id="tasks-empty" class="text-center py-6 d-none">
                                <i class="fas fa-clipboard-list fa-2x text-muted mb-3"></i>
                                <p class="text-muted fw-semibold">No tasks assigned yet</p>
                            </div>
                            <!-- Tasks table -->
                            <div id="tasks-table-wrapper" class="d-none">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3" id="tasks-table">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 rounded-start">#</th>
                                                <th>Task Type</th>
                                                <th>Remarks</th>
                                                <th>Assigned Date</th>
                                                <th>Due Date</th>
                                                <th>Assignees &amp; Status</th>
                                                <th class="rounded-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tasks-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!--end::Tab Content-->
        </div>


    <?php endif; ?>

    </div>
</div>

<!-- HR Executive Closure Modal -->
<div class="modal fade" id="hrClosureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Enhanced Header -->
            <div class="modal-header bg-gradient-success text-white border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <div>
                        <h4 class="modal-title mb-0 fw-bold">Job Closure Initiation</h4>
                        <small class="opacity-85 fst-italic text-dark">Start the closure process for this position</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-sm" data-bs-dismiss="modal"></button>
            </div>

            <!-- Progress Steps -->

            <div class="modal-body p-0">
                <form id="hr-closure-form">
                    <!-- Main Content -->
                    <div class="p-6">
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <!-- <div class="symbol symbol-circle symbol-40px bg-light-success me-3">
                                </div>
                                <div>
                                    <h5 class="mb-1">Candidate Selection</h5>
                                </div> -->
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-user-check text-success me-2"></i>Selected Candidate *
                                </label>
                                <select class="form-select form-select-lg" name="selected_candidate_id" data-control="select2" data-placeholder="Select candidate" required>
                                    <option value="">Select candidate</option>
                                    <?php if (isset($employees) && is_array($employees)): ?>
                                        <?php foreach ($employees as $employee): ?>
                                            <option value="<?= $employee['id'] ?>">
                                                <?= esc($employee['employee_name']) ?>
                                                (<?= esc($employee['internal_employee_id']) ?>) -
                                                <?= esc($employee['company_short_name']) ?> /
                                                <?= esc($employee['department_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-user-plus text-info me-2"></i>Replacement of Employee *
                                </label>
                                <select class="form-select form-select-lg" name="replacement_of_employee_id" data-control="select2" data-placeholder="Select employee (if applicable)">
                                    <option value="">Select employee (if applicable)</option>
                                    <?php if (isset($employees) && is_array($employees)): ?>
                                        <?php foreach ($employees as $employee): ?>
                                            <option value="<?= $employee['id'] ?>">
                                                <?= esc($employee['employee_name']) ?>
                                                (<?= esc($employee['internal_employee_id']) ?>) -
                                                <?= esc($employee['company_short_name']) ?> /
                                                <?= esc($employee['department_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-sticky-note text-warning me-2"></i>Closure Reason/Notes *
                                </label>
                                <textarea class="form-control form-control-lg" name="closure_notes" rows="4"
                                    placeholder="Provide reason for job closure and any relevant notes..." required></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Enhanced Footer -->
            <div class="modal-footer bg-light border-top">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        This will start the closure process
                    </div>
                    <div>
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-success shadow-sm" id="confirm-hr-closure">
                            <i class="fas fa-check me-2"></i>Initiate Closure
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reporting Manager Finalization Modal -->
<div class="modal fade" id="managerClosureModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Enhanced Header -->
            <div class="modal-header bg-gradient-primary text-white border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <!-- <i class="fas fa-clipboard-check fs-3 me-3 text-warning"></i> -->
                    <div>
                        <h4 class="modal-title mb-0 fw-bold">Job Closure Assessment</h4>
                        <small class="opacity-85 fst-italic text-dark">Complete the final evaluation to close this position</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-sm" data-bs-dismiss="modal"></button>
            </div>



            <div class="modal-body p-0">
                <form id="manager-closure-form">
                    <input type="hidden" name="confirm_closure" value="yes">
                    <input type="hidden" name="keep_posting_open" value="no">

                    <!-- Main Content -->
                    <div class="row g-0">
                        <!-- Left Column - Assessment -->
                        <div class="col-lg-6 p-6 border-end">
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="symbol symbol-circle symbol-40px bg-light-primary me-3">
                                        <!-- <i class="fas fa-star text-primary"></i> -->
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Candidate Assessment</h5>
                                        <!-- <p class="text-muted mb-0">Evaluate the hired candidate's performance</p> -->
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-thumbs-up text-success me-2"></i>Key Strengths *
                                    </label>
                                    <textarea class="form-control form-control-lg" name="strengths" rows="4"
                                        placeholder="List the candidate's main strengths and positive qualities..." required></textarea>
                                    <!-- <div class="form-text">Highlight what makes this hire valuable to the team</div> -->
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Weaknesses *
                                    </label>
                                    <textarea class="form-control form-control-lg" name="weaknesses" rows="4"
                                        placeholder="Identify areas where the candidate can grow..." required></textarea>
                                    <!-- <div class="form-text">Focus on constructive feedback for development</div> -->
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Team Overview -->
                        <div class="col-lg-6 p-6">
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="symbol symbol-circle symbol-40px bg-light-info me-3">
                                        <i class="fas fa-users text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Team Overview</h5>
                                        <p class="text-muted mb-0">Current team structure and performance</p>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <label class="form-label fw-bold text-dark">
                                            <i class="fas fa-hashtag text-primary me-2"></i>Team Size *
                                        </label>
                                        <input type="number" class="form-control form-control-lg" name="current_team_size"
                                            min="1" placeholder="Enter count" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold text-dark">
                                            <i class="fas fa-medal text-warning me-2"></i>Top Performer
                                        </label>
                                        <select class="form-select form-select-lg" name="best_performer_id" data-control="select2" data-placeholder="Select employee">
                                            <option value="">Choose employee</option>
                                            <?php if (isset($employees) && is_array($employees)): ?>
                                                <?php foreach ($employees as $employee): ?>
                                                    <option value="<?= $employee['id'] ?>">
                                                        <?= esc($employee['employee_name']) ?>
                                                        (<?= esc($employee['internal_employee_id']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="card bg-light-warning border-warning mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="text-warning mb-3">
                                            <i class="fas fa-user-times me-2"></i>Performance Issues (Optional)
                                        </h6>

                                        <div class="mb-3">
                                            <label class="form-label">Underperforming Team Member</label>
                                            <select class="form-select" name="worst_performer_id" data-control="select2" data-placeholder="Select if applicable">
                                                <option value="">No concerns</option>
                                                <?php if (isset($employees) && is_array($employees)): ?>
                                                    <?php foreach ($employees as $employee): ?>
                                                        <option value="<?= $employee['id'] ?>">
                                                            <?= esc($employee['employee_name']) ?>
                                                            (<?= esc($employee['internal_employee_id']) ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Replacement Needed?</label>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="need_replacement" value="no" id="replacement_no" checked>
                                                    <label class="form-check-label" for="replacement_no">No</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="need_replacement" value="yes" id="replacement_yes">
                                                    <label class="form-check-label" for="replacement_yes">Yes</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="replacement-details-section" style="display: none;">
                                            <label class="form-label">Replacement Details</label>
                                            <textarea class="form-control" name="replacement_details" rows="3"
                                                placeholder="Describe replacement requirements and timeline..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notice Period Assessment -->
                    <div class="bg-light-warning border-top p-6">
                        <div class="d-flex align-items-center mb-4">
                            <!--<div class="symbol symbol-circle symbol-40px bg-light-primary me-3">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                             <div>
                                <h5 class="mb-1">Notice Period Assessment</h5>
                                <p class="text-muted mb-0">Evaluate team commitment for transition planning</p>
                            </div> -->
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar-check text-info me-2"></i>Will all your team members serve 3 months of working Notice Period? *
                            </label>
                            <div class="d-flex gap-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="notice_period_compliance" value="no" id="notice_no" required>
                                    <label class="form-check-label fw-semibold text-success" for="notice_no">
                                        <i class="fas fa-check-circle me-1"></i>Yes, all will serve
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="notice_period_compliance" value="yes" id="notice_yes" required>
                                    <label class="form-check-label fw-semibold text-warning" for="notice_yes">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Some are doubtful
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="doubtful-members-section" style="display: none;">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-user-times text-warning me-2"></i>Doubtful Members (Names)
                            </label>
                            <textarea class="form-control form-control-lg" name="doubtful_notice_members" rows="3"
                                placeholder="List the names of team members who may not serve the full 3-month notice period..."></textarea>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Include reasons if known (e.g., personal commitments, other job offers)
                            </div>
                        </div>
                    </div>

                    <!-- <div class="bg-light-secondary border-top p-6">
                        <div class="d-flex align-items-center mb-4">
                            <div class="symbol symbol-circle symbol-40px bg-light-success me-3">
                                <i class="fas fa-comment-dots text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Additional Comments</h5>
                                <p class="text-muted mb-0">Share any additional insights or recommendations</p>
                            </div>
                        </div>

                        <textarea class="form-control form-control-lg" name="manager_comments" rows="4"
                            placeholder="Add any additional observations, recommendations, or notes about the hiring process..."></textarea>
                    </div> -->
                </form>
            </div>

            <!-- Enhanced Footer -->
            <div class="modal-footer bg-light border-top">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        All assessments will be recorded for HR records
                    </div>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-light btn-lg" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-success btn-lg px-6" id="confirm-manager-closure">
                            <i class="fas fa-check-circle me-2"></i>Complete Assessment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($currentUser == $hr_executive): ?>
    <!-- Assign Task Modal -->
    <div class="modal fade" id="assignTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-tasks me-2 text-primary"></i>Assign Recruitment Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="assign-task-form">
                        <input type="hidden" name="job_listing_id" value="<?= $job->id ?>">
                        <div class="mb-4">
                            <label class="form-label fw-bold required">Task Type</label>
                            <select class="form-select" name="task_type" id="task-type-select" required>
                                <option value="">-- Select Task Type --</option>
                                <?php foreach (
                                    [
                                        'Source Candidates',
                                        'Screen Resumes / CVs',
                                        'Schedule Interviews',
                                        'Conduct Telephonic Screening',
                                        'Send Job Offer Letter',
                                        'Background Verification',
                                        'Job Portal Posting / Update',
                                        'Follow-up with Candidates',
                                        'Reference Check',
                                        'Coordinate with Department HOD',
                                    ] as $taskType
                                ): ?>
                                    <option value="<?= esc($taskType) ?>"><?= esc($taskType) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Remarks <span class="text-muted">(Optional)</span></label>
                            <textarea class="form-control" name="remarks" rows="3" placeholder="Add any instructions or notes…"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold required">Assign To</label>
                            <select class="form-select" name="assigned_to[]" id="assign-to-select" multiple required>
                            </select>
                            <div class="form-text text-muted">Select one or more HR team members</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold required">Due Date</label>
                            <input type="date" class="form-control" name="due_date" min="<?= date('Y-m-d') ?>" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirm-assign-task">
                        <i class="fas fa-check me-1"></i>Assign Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reassign Task Modal -->
    <div class="modal fade" id="reassignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2 text-warning"></i>Reassign Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reassign-assignee-record-id">
                    <div class="mb-4">
                        <label class="form-label fw-bold required">Reassign To</label>
                        <select class="form-select" id="reassign-to-select">
                            <option value="">-- Select HR Member --</option>
                        </select>
                    </div>
                    <p class="text-muted small"><i class="fas fa-info-circle me-1"></i>The original assignee will be replaced and the status will reset to <strong>Pending</strong>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirm-reassign">
                        <i class="fas fa-exchange-alt me-1"></i>Reassign
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2 text-primary"></i>Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-task-form">
                        <input type="hidden" name="task_id" id="edit-task-id">
                        <div class="mb-4">
                            <label class="form-label fw-bold required">Task Type</label>
                            <select class="form-select" name="task_type" id="edit-task-type-select" required>
                                <option value="">-- Select Task Type --</option>
                                <?php foreach (
                                    [
                                        'Source Candidates',
                                        'Screen Resumes / CVs',
                                        'Schedule Interviews',
                                        'Conduct Telephonic Screening',
                                        'Send Job Offer Letter',
                                        'Background Verification',
                                        'Job Portal Posting / Update',
                                        'Follow-up with Candidates',
                                        'Reference Check',
                                        'Coordinate with Department HOD',
                                    ] as $taskType
                                ): ?>
                                    <option value="<?= esc($taskType) ?>"><?= esc($taskType) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Remarks <span class="text-muted">(Optional)</span></label>
                            <textarea class="form-control" name="remarks" id="edit-task-remarks" rows="3" placeholder="Add any instructions or notes…"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold required">Due Date</label>
                            <input type="date" class="form-control" name="due_date" id="edit-task-due-date" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirm-edit-task">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Task History Modal (available to all authorised roles) -->
<div class="modal fade" id="taskHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-history me-2 text-secondary"></i>Task Change History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Loading spinner -->
                <div id="history-loading" class="text-center py-6">
                    <div class="spinner-border text-secondary" role="status"></div>
                    <p class="mt-2 text-muted">Loading history…</p>
                </div>
                <!-- Empty state -->
                <div id="history-empty" class="text-center py-6 d-none">
                    <i class="fas fa-scroll fa-2x text-muted mb-3"></i>
                    <p class="text-muted fw-semibold">No history recorded for this task yet.</p>
                </div>
                <!-- Timeline -->
                <ul id="history-timeline" class="list-unstyled mb-0 d-none"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('javascript') ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js"></script>
<script>
    $(document).ready(function() {
        // Display success message
        <?php if (session()->has('success')): ?>
            var successMessage = "<?= session('success') ?>";
            if (successMessage.includes("||")) {
                var parts = successMessage.split("||");
                Swal.fire({
                    title: 'Success!',
                    text: parts[0],
                    icon: 'success',
                    confirmButtonText: 'Noted'
                });
            } else {
                // Display regular success messages
                Swal.fire({
                    title: 'Success!',
                    text: successMessage,
                    icon: 'success',
                    confirmButtonText: 'Noted'
                });
            }
        <?php endif; ?>
    });
</script>
<script>
    // $(document).ready(function() {
    //     $('[data-bs-toggle="tooltip"]').each(function() {
    //         new bootstrap.Tooltip(this);
    //     });

    // });

    function updateJobListingStatus(jobId, action) {

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            input: 'textarea',
            inputLabel: 'Remarks',
            inputPlaceholder: 'Enter your remarks here...',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, close it!',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to write something!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to update remarks
                $.ajax({
                    url: '<?= site_url("recruitment/job-listing/update-remarks/") ?>',
                    type: 'POST',
                    data: {
                        jobId: jobId,
                        action: action,
                        remarks: result.value
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#show-status').html(action);

                            Swal.fire(
                                'Updated!',
                                response.message,
                                'success'
                            ).then(() => {
                                //window.location.href = url;
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to update remarks: ' + response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        Swal.fire(
                            'Error!',
                            'Failed to update remarks. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    };
</script>

<script>
    $(document).ready(function() {


        $('.summernote').summernote({
            placeholder: 'Write your message here...',
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']]
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

        let lastCommentCount = 0;
        let refreshInterval;

        function loadComments() {
            $('#comments-loading').removeClass('d-none');

            $.ajax({
                url: '<?= site_url("recruitment/job-listing/comments/get-comments/" . $job->id) ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {

                        console.log('Comments loaded successfully:', response);
                        displayComments(response.comments);
                        updateCommentsCount(response.count);
                        updateLastUpdatedTime();
                        lastCommentCount = response.count;
                    } else {
                        console.error('Failed to load comments:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                },
                complete: function() {
                    $('#comments-loading').addClass('d-none');
                }
            });
        }



        function displayComments(comments) {
            const container = $('#comments-container');
            const noCommentsMsg = $('#no-comments-message');

            if (!comments || comments.length === 0) {
                noCommentsMsg.show();
                container.find('.timeline-item').remove();
                return;
            }

            noCommentsMsg.hide();
            container.empty();

            const timelineWrapper = $('<div class="timeline-label"></div>');

            // --- Index for fast lookup ---
            const commentById = {};
            comments.forEach(c => commentById[String(c.id)] = c);

            // --- Build issue map ---
            const trailMap = {};
            comments.forEach(c => {
                if (c.type === 'issue') {
                    trailMap[String(c.id)] = {
                        issue: c,
                        resolutions: []
                    };
                }
            });

            // --- Root finder with memoization ---
            const rootCache = {};

            function findRootIssue(id) {
                if (!id) return null;
                id = String(id);
                if (rootCache[id]) return rootCache[id];

                const c = commentById[id];
                if (!c) return null;

                if (c.type === 'issue') {
                    rootCache[id] = id;
                    return id;
                }
                if (c.type === 'resolution' && c.parent_comment_id) {
                    rootCache[id] = findRootIssue(c.parent_comment_id);
                    return rootCache[id];
                }
                return null;
            }

            // --- Attach resolutions only to their root issue ---
            comments.forEach(c => {
                if (c.type === 'resolution') {
                    const rootId = findRootIssue(c.parent_comment_id);
                    if (rootId && trailMap[rootId]) {
                        trailMap[rootId].resolutions.push(c);
                    }
                }
            });

            // --- Sort issues + resolutions ---
            const orderedIssues = Object.values(trailMap).sort(
                (a, b) => new Date(a.issue.created_at) - new Date(b.issue.created_at)
            );
            orderedIssues.forEach(trail =>
                trail.resolutions.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
            );

            // --- Map issue id → sequential number ---
            const issueNumberMap = {};
            orderedIssues.forEach((trail, idx) => {
                issueNumberMap[String(trail.issue.id)] = idx + 1;
            });

            // --- Render each issue and its resolutions ---
            orderedIssues.forEach(trail => {
                const issue = trail.issue;
                const issueNum = issueNumberMap[String(issue.id)];
                const resCount = trail.resolutions.length;

                // Render issue
                renderComment(issue, `Issue #${issueNum} • ${resCount ? resCount + " Resolution(s)" : "Awaiting Resolution"}`);

                // Render its resolutions
                trail.resolutions.forEach((res, idx) => {
                    let parentLabel = '';
                    const parent = commentById[String(res.parent_comment_id)];
                    if (parent) {
                        if (parent.type === 'issue') {
                            parentLabel = `Issue #${issueNum}`;
                        } else {
                            const parentIndex = trail.resolutions.findIndex(r => String(r.id) === String(parent.id));
                            if (parentIndex !== -1) {
                                parentLabel = `Resolution #${issueNum}.${parentIndex + 1}`;
                            }
                        }
                    }
                    renderComment(
                        res,
                        `Resolution #${issueNum}.${idx + 1}${parentLabel ? `<br><small class="text-muted">Reply to: ${parentLabel}</small>` : ''}`
                    );
                });
            });

            container.append(timelineWrapper);
            container.scrollTop(container[0].scrollHeight);

            // --- Helper: render one comment ---
            function renderComment(comment, trailInfoHtml) {
                const typeBadgeClass = getTypeBadgeClass(comment.type);
                const typeLabel = getTypeLabel(comment.type);
                const statusClass = getStatusClass(comment.type);

                const profilePicture = comment.profile_picture ?
                    `<img src="${comment.profile_picture}" alt="${comment.sender_name}">` :
                    `<div class="symbol symbol-circle symbol-45px bg-light-primary">
                   <span class="symbol-label text-primary fw-bold fs-6">
                       ${(comment.sender_name || 'U').charAt(0).toUpperCase()}
                   </span>
               </div>`;

                const html = `
            <div class="timeline-item" data-comment-type="${comment.type}">
                <div class="timeline-label fw-bolder text-gray-800 fs-6">${comment.time_ago}</div>
                <div class="timeline-badge"><i class="fa fa-genderless ${statusClass} fs-1"></i></div>
                <div class="fw-normal timeline-content text-muted ps-3">
                    <div class="d-flex mb-3">
                        <div class="symbol symbol-circle symbol-45px me-5">${profilePicture}</div>
                        <div class="d-flex flex-column flex-row-fluid p-3 shadow rounded-2">
                            <div class="trail-indicator mb-2">
                                <span class="badge ${comment.type === 'issue' ? 'badge-light-danger' : 'badge-light-primary'} fs-8">
                                    ${trailInfoHtml}
                                </span>
                            </div>
                            <div class="d-flex align-items-center flex-wrap mb-1">
                                <a href="#" class="text-gray-800 text-hover-primary fw-bolder me-2">
                                    ${comment.sender_name || 'Unknown User'}
                                </a>
                                <span class="badge ${typeBadgeClass} comment-type-badge">${typeLabel}</span>
                                <a href="#" class="ms-auto btn btn-sm btn-light-primary reply-btn"
                                   data-comment_id="${comment.id}" 
                                   style="font-size:11px;padding:4px 12px;border-radius:15px;">
                                   <i class="fas fa-reply me-1"></i>Reply
                                </a>
                            </div>
                            <div class="text-gray-800 fs-7 fw-normal pt-1">${comment.content}</div>
                        </div>
                    </div>
                </div>
            </div>`;
                timelineWrapper.append(html);
            }
        }




        function getTypeBadgeClass(type) {
            switch (type) {
                case 'question':
                    return 'bg-warning text-dark';
                case 'answer':
                    return 'bg-success text-white';
                case 'issue':
                    return 'bg-danger text-white';
                case 'resolution':
                    return 'bg-primary text-white';
                case 'concern':
                    return 'bg-danger text-white';
                case 'suggestion':
                    return 'bg-success text-white';
                default:
                    return 'bg-primary text-white';
            }




        }

        function getTypeLabel(type) {
            return (type + '').replace(/^([a-z])|\s+([a-z])/g, function($1) {
                return $1.toUpperCase();
            });
            // switch (type) {
            //     case 'question':
            //         return 'Question';
            //     case 'concern':
            //         return 'Concern';
            //     case 'suggestion':
            //         return 'Suggestion';
            //     default:
            //         return 'Comment';
            // }
        }

        function getStatusClass(type) {
            switch (type) {
                case 'question':
                    return 'text-warning';
                case 'answer':
                    return 'text-success';
                case 'issue':
                    return 'text-danger';
                case 'resolution':
                    return 'text-primary';
                case 'concern':
                    return 'text-danger';
                case 'suggestion':
                    return 'text-info';
                default:
                    return 'text-muted';
            }
        }

        function updateCommentsCount(count) {
            $('#comments-count').text(count);
        }

        function updateLastUpdatedTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', {
                hour12: true,
                hour: '2-digit',
                minute: '2-digit'
            });
            $('#last-updated').text('Last updated: ' + timeStr);
        }

        function startAutoRefresh() {
            loadComments();
            refreshInterval = setInterval(function() {
                loadComments();
            }, 60000); // 1 minute
        }

        $(document).on('visibilitychange', function() {
            if (document.hidden) {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                }
            } else {
                startAutoRefresh();
            }
        });

        // Handle modal form submission
        $('#submit-comment-btn').on('click', function(e) {
            e.preventDefault();
            const commentContent = $('#commentText').summernote('code');
            const commentType = $('#commentType').val();

            if (!commentContent || commentContent.trim() === '' || commentContent === '<p><br></p>') {
                alert('Please enter a comment before submitting.');
                return;
            }

            $('#submit-btn-text').addClass('d-none');
            $('#submit-btn-loading').removeClass('d-none');
            $('#submit-comment-btn').prop('disabled', true);

            const commentWithType = `${commentContent}`;

            const formData = {
                job_id: $('input[name="job_id"]').val(),
                comment: commentWithType,
                type: commentType,
                parent_comment_id: $('#parentCommentId').val()
            };

            $.ajax({
                url: '<?= site_url("recruitment/job-listing/comments/add-comment/" . $job->id) ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#commentText').summernote('reset');
                        $('#commentType').val('');
                        $('#parentCommentId').val(''); // Reset parent comment ID after successful submission
                        $('#commentModal').modal('hide');

                        showNotification('Comment posted successfully!', 'success');
                        loadComments();
                    } else {
                        showNotification('Failed to post comment: ' + response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to post comment:', error);
                    showNotification('Failed to post comment. Please try again.', 'error');
                },
                complete: function() {
                    $('#submit-btn-text').removeClass('d-none');
                    $('#submit-btn-loading').addClass('d-none');
                    $('#submit-comment-btn').prop('disabled', false);
                }
            });
        });


        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const notification = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            $('#comment-notifications').html(notification);

            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        }


        $('.approval-btn').on('click', function(e) {
            e.preventDefault();

            const jobId = $(this).data('job-id');
            const approvalType = $(this).data('approval-type');
            const button = $(this);
            const originalText = button.html();

            // OLD CODE - Commented out: HR Manager used to manually pick a Job Opening Date via popup
            // if (approvalType === 'hr_manager') {
            //     Swal.fire({
            //         title: 'Set Job Opening Date',
            //         html: '<input type="date" id="job-opening-date" class="form-control" min="' + new Date().toISOString().split('T')[0] + '" required>',
            //         showCancelButton: true,
            //         confirmButtonText: 'Approve',
            //         cancelButtonText: 'Cancel',
            //         confirmButtonColor: '#198754',
            //         cancelButtonColor: '#6c757d',
            //         preConfirm: () => {
            //             const date = document.getElementById('job-opening-date').value;
            //             if (!date) {
            //                 Swal.showValidationMessage('Please select a job opening date');
            //                 return false;
            //             }
            //             return date;
            //         }
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             processApproval(jobId, approvalType, button, originalText, result.value);
            //         }
            //     });
            // } else {
            //     processApproval(jobId, approvalType, button, originalText);
            // }

            // NEW CODE: HR Manager approval date is automatically set as the official Job Opening Date (server-side)
            processApproval(jobId, approvalType, button, originalText);
        });

        function processApproval(jobId, approvalType, button, originalText, jobOpeningDate = null) {
            button.html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
            button.prop('disabled', true);

            const data = {
                job_id: jobId,
                approval_type: approvalType
            };

            if (jobOpeningDate) {
                data.job_opening_date = jobOpeningDate;
            }

            $.ajax({
                url: '<?= site_url("recruitment/job-listing/approve/") ?>',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showNotification('Job listing approved successfully!', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification('Failed to approve: ' + response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Approval error:', error);
                    showNotification('Failed to approve job listing. Please try again.', 'error');
                },
                complete: function() {
                    button.html(originalText);
                    button.prop('disabled', false);
                }
            });
        }

        $('#submit-issue-btn').on('click', function(e) {
            $('#commentType').val('issue');
            $('#parentCommentId').val(''); // Clear parent comment ID for new issues
        });

        // Handle reply button click
        $(document).on('click', '.reply-btn', function(e) {
            e.preventDefault();

            // Get the parent comment ID from the data attribute
            const parentCommentId = $(this).data('comment_id');

            // Set comment type to resolution for replies
            $('#commentType').val('resolution');

            // Set the parent comment ID
            $('#parentCommentId').val(parentCommentId);

            // Open the comment modal
            $('#commentModal').modal('show');

            // Focus on the comment text area after modal is shown
            $('#commentModal').on('shown.bs.modal', function() {
                $('#commentText').summernote('focus');
            });
        });

        // Job Closure Functionality
        let currentJobId = null;
        let currentClosureStage = null;

        // Handle closure button clicks
        $(document).on('click', '.job-close-btn', function(e) {
            e.preventDefault();
            currentJobId = $(this).data('job-id');
            currentClosureStage = $(this).data('closure-stage');

            if (currentClosureStage === 'hr_executive') {
                $('#hrClosureModal').modal('show');
            } else if (currentClosureStage === 'reporting_manager') {
                $('#managerClosureModal').modal('show');

            }
        });

        $('#hrClosureModal').on('shown.bs.modal', function() {
            $('#hrClosureModal select[data-control="select2"]').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    dropdownParent: $('#hrClosureModal'),
                    placeholder: $(this).data('placeholder') || 'Select option',
                    allowClear: true
                });
            });
        });

        $('#managerClosureModal').on('shown.bs.modal', function() {
            $('#managerClosureModal select[data-control="select2"]').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    dropdownParent: $('#managerClosureModal'),
                    placeholder: $(this).data('placeholder') || 'Select option',
                    allowClear: true
                });
            });
        });

        // Clear form data when modals are hidden
        $('#hrClosureModal').on('hidden.bs.modal', function() {
            $('#hr-closure-form')[0].reset();
            $('#hrClosureModal select[data-control="select2"]').val(null).trigger('change');
        });

        $('#managerClosureModal').on('hidden.bs.modal', function() {
            $('#manager-closure-form')[0].reset();
            $('#managerClosureModal select[data-control="select2"]').val(null).trigger('change');
            // Hide conditional sections
            $('#replacement-details-section').hide();
            $('#posting-reason-section').hide();
        });

        // Handle HR Executive closure confirmation
        $('#confirm-hr-closure').on('click', function() {
            let formData = {
                job_id: currentJobId,
                selected_candidate_id: $('#hr-closure-form select[name="selected_candidate_id"]').val(),
                replacement_of_employee_id: $('#hr-closure-form select[name="replacement_of_employee_id"]').val(),
                closure_notes: $('#hr-closure-form textarea[name="closure_notes"]').val()
            };

            // Validate required fields
            if (!formData.selected_candidate_id || !formData.closure_notes) {
                Swal.fire('Error', 'Please fill in all required fields', 'error');
                return;
            }

            $.ajax({
                url: '<?= base_url('recruitment/job-listing/initiate-closure') ?>',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#hrClosureModal').modal('hide');
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    $('#hrClosureModal').modal('hide');
                    Swal.fire('Error', 'Failed to process closure request', 'error');
                }
            });
        });

        // Handle Manager closure confirmation
        $('#confirm-manager-closure').on('click', function() {
            let formData = {
                job_id: currentJobId,
                strengths: $('#manager-closure-form textarea[name="strengths"]').val(),
                weaknesses: $('#manager-closure-form textarea[name="weaknesses"]').val(),
                current_team_size: $('#manager-closure-form input[name="current_team_size"]').val(),
                best_performer_id: $('#manager-closure-form select[name="best_performer_id"]').val(),
                worst_performer_id: $('#manager-closure-form select[name="worst_performer_id"]').val(),
                need_replacement: $('#manager-closure-form input[name="need_replacement"]:checked').val(),
                replacement_details: $('#manager-closure-form textarea[name="replacement_details"]').val(),
                keep_posting_open: $('#manager-closure-form input[name="keep_posting_open"]').val(),
                keep_posting_reason: $('#manager-closure-form textarea[name="keep_posting_reason"]').val(),
                notice_period_compliance: $('#manager-closure-form input[name="notice_period_compliance"]:checked').val(),
                doubtful_notice_members: $('#manager-closure-form textarea[name="doubtful_notice_members"]').val(),
                manager_comments: $('#manager-closure-form textarea[name="manager_comments"]').val(),
                confirm_closure: $('#manager-closure-form input[name="confirm_closure"]').val()
            };

            console.log('Manager closure form data:', formData);

            // Debug specific field values
            console.log('confirm_closure value:', formData.confirm_closure);
            console.log('keep_posting_open value:', formData.keep_posting_open);

            // Validate required fields
            if (!formData.strengths || !formData.weaknesses || !formData.current_team_size || !formData.keep_posting_open || !formData.confirm_closure || !formData.notice_period_compliance) {
                let missingFields = [];
                if (!formData.strengths) missingFields.push('Strengths');
                if (!formData.weaknesses) missingFields.push('Weaknesses');
                if (!formData.current_team_size) missingFields.push('Current Team Size');
                if (!formData.keep_posting_open) missingFields.push('Keep Posting Open');
                if (!formData.confirm_closure) missingFields.push('Confirm Closure');
                if (!formData.notice_period_compliance) missingFields.push('Notice Period Compliance');

                Swal.fire('Error', 'Please fill in all required fields: ' + missingFields.join(', '), 'error');
                return;
            }

            // Validate conditional required fields
            if (formData.keep_posting_open === 'yes' && !formData.keep_posting_reason) {
                Swal.fire('Error', 'Reason for keeping posting open is required', 'error');
                return;
            }

            if (formData.need_replacement === 'yes' && !formData.replacement_details) {
                Swal.fire('Error', 'Replacement details are required when replacement is needed', 'error');
                return;
            }

            if (formData.notice_period_compliance === 'yes' && !formData.doubtful_notice_members) {
                Swal.fire('Error', 'Please mention the names of doubtful team members for notice period compliance', 'error');
                return;
            }

            $.ajax({
                url: '<?= base_url('recruitment/job-listing/finalize-closure') ?>',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#managerClosureModal').modal('hide');
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    $('#managerClosureModal').modal('hide');
                    Swal.fire('Error', 'Failed to process closure finalization', 'error');
                }
            });
        });

        // Toggle helper functions for modal
        window.toggleReplacementDetails = function(select) {
            const detailsSection = document.getElementById('replacement-details-section');
            if (select.value === 'yes') {
                detailsSection.style.display = 'block';
                detailsSection.querySelector('textarea').required = true;
            } else {
                detailsSection.style.display = 'none';
                detailsSection.querySelector('textarea').required = false;
            }
        };

        // Toggle notice period doubtful members section
        $(document).on('change', 'input[name="notice_period_compliance"]', function() {
            const doubtfulSection = $('#doubtful-members-section');
            if (this.value === 'yes') {
                doubtfulSection.show();
                doubtfulSection.find('textarea').prop('required', true);
            } else {
                doubtfulSection.hide();
                doubtfulSection.find('textarea').prop('required', false);
            }
        });

        window.togglePostingReason = function(select) {
            const reasonSection = document.getElementById('posting-reason-section');
            if (select.value === 'yes') {
                reasonSection.style.display = 'block';
                reasonSection.querySelector('textarea').required = true;
            } else {
                reasonSection.style.display = 'none';
                reasonSection.querySelector('textarea').required = false;
            }
        };

        startAutoRefresh();

    });
</script>

<?php if (!empty($hrExecutive) && !empty($hodApproval) && !empty($hrManager)): ?>
    <script>
        (function() {
            const JOB_ID = <?= (int) $job->id ?>;
            const CURRENT_USER = <?= (int) $currentUser ?>;
            const HR_EXECUTIVE = <?= (int) $hr_executive ?>;

            // ── Helpers ──────────────────────────────────────────────────────────────

            function loadHrEmployees(callback) {
                $.ajax({
                    url: '<?= base_url('recruitment/job-listing/tasks/hr-employees') ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.success && resp.data) {
                            callback(resp.data);
                        }
                    }
                });
            }

            function loadTasks() {
                $('#tasks-loading').removeClass('d-none');
                $('#tasks-empty').addClass('d-none');
                $('#tasks-table-wrapper').addClass('d-none');

                $.ajax({
                    url: '<?= base_url('recruitment/job-listing/tasks/') ?>' + JOB_ID,
                    type: 'GET',
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.success) {
                            renderTasks(resp.data);
                        }
                    },
                    complete: function() {
                        $('#tasks-loading').addClass('d-none');
                    }
                });
            }

            function statusBadgeClass(status) {
                return {
                    pending: 'badge-light-warning',
                    in_progress: 'badge-light-info',
                    completed: 'badge-light-success',
                } [status] || 'badge-light-secondary';
            }

            function statusLabel(status) {
                return {
                    pending: 'Pending',
                    in_progress: 'In Progress',
                    completed: 'Completed',
                } [status] || status;
            }

            function isOverdue(task) {
                const today = new Date().toISOString().slice(0, 10);
                const allDone = task.assignees.every(a => a.status === 'completed');
                return task.due_date < today && !allDone;
            }

            function renderAssigneeRow(assignee) {
                const isOwn = (CURRENT_USER === parseInt(assignee.assigned_to));
                const isDone = (assignee.status === 'completed');
                const isExec = (CURRENT_USER === HR_EXECUTIVE);
                let statusHtml = '';

                if (isOwn && !isDone) {
                    statusHtml = `
                <select class="form-select form-select-sm task-status-select" style="width:140px"
                        data-assignee-id="${assignee.id}">
                    <option value="pending"     ${assignee.status === 'pending'     ? 'selected' : ''}>Pending</option>
                    <option value="in_progress" ${assignee.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                    <option value="completed"   ${assignee.status === 'completed'   ? 'selected' : ''}>Completed</option>
                </select>`;
                } else {
                    statusHtml = `<span class="badge ${statusBadgeClass(assignee.status)}">${statusLabel(assignee.status)}</span>`;
                }

                let reassignBtn = '';
                if (isExec) {
                    reassignBtn = ` <button class="btn btn-icon btn-sm btn-light-warning reassign-btn ms-1"
                                    title="Reassign"
                                    data-assignee-id="${assignee.id}">
                                <i class="fas fa-user-edit fa-sm"></i>
                            </button>`;
                }

                return `<li class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-user-circle text-muted"></i>
                    <span>${$('<div>').text(assignee.assigned_to_name).html()}</span>
                    ${statusHtml}
                    ${reassignBtn}
                </li>`;
            }

            const taskDataMap = {};

            function renderTasks(tasks) {
                if (!tasks || tasks.length === 0) {
                    $('#tasks-empty').removeClass('d-none');
                    return;
                }

                $('#tasks-table-wrapper').removeClass('d-none');
                const tbody = $('#tasks-tbody');
                tbody.empty();

                tasks.forEach(function(task, idx) {
                    taskDataMap[task.id] = task;
                    const rowClass = isOverdue(task) ? 'table-danger' : '';
                    const assigneesList = task.assignees.map(renderAssigneeRow).join('');

                    <?php if ($currentUser == $hr_executive): ?>
                        let actionsCol = `<td>
                <button class="btn btn-icon btn-sm btn-light-primary edit-task-btn me-1" title="Edit Task" data-task-id="${task.id}">
                    <i class="fas fa-edit fa-sm"></i>
                </button>
                <button class="btn btn-icon btn-sm btn-light-secondary task-history-btn" title="View History" data-task-id="${task.id}">
                    <i class="fas fa-history fa-sm"></i>
                </button>
            </td>`;
                    <?php else: ?>
                        let actionsCol = `<td>
                <button class="btn btn-icon btn-sm btn-light-secondary task-history-btn" title="View History" data-task-id="${task.id}">
                    <i class="fas fa-history fa-sm"></i>
                </button>
            </td>`;
                    <?php endif; ?>

                    tbody.append(`
                <tr class="${rowClass}">
                    <td class="ps-4">${idx + 1}</td>
                    <td><span class="fw-semibold">${$('<div>').text(task.task_type).html()}</span></td>
                    <td class="text-muted" style="max-width:180px;white-space:normal;">${task.remarks ? $('<div>').text(task.remarks).html() : '<span class="text-muted">—</span>'}</td>
                    <td>${task.assigned_date}</td>
                    <td>${task.due_date}${isOverdue(task) ? ' <span class="badge badge-light-danger ms-1">Overdue</span>' : ''}</td>
                    <td><ul class="list-unstyled mb-0">${assigneesList}</ul></td>
                    ${actionsCol}
                </tr>
            `);
                });
            }

            function openReassignModal(assigneeId) {
                $('#reassign-assignee-record-id').val(assigneeId);
                loadHrEmployees(function(employees) {
                    const sel = $('#reassign-to-select');
                    sel.empty().append('<option value="">-- Select HR Member --</option>');
                    employees.forEach(e => sel.append(`<option value="${e.id}">${$('<div>').text(e.text).html()}</option>`));
                });
                $('#reassignModal').modal('show');
            }

            // ── Event handlers ────────────────────────────────────────────────────────

            <?php if ($currentUser == $hr_executive): ?>
                // Init Assign Task modal
                $('#assignTaskModal').on('shown.bs.modal', function() {
                    loadHrEmployees(function(employees) {
                        const sel = $('#assign-to-select');
                        sel.empty();
                        employees.forEach(e => sel.append(`<option value="${e.id}">${$('<div>').text(e.text).html()}</option>`));

                        if (!sel.hasClass('select2-hidden-accessible')) {
                            sel.select2({
                                dropdownParent: $('#assignTaskModal'),
                                placeholder: 'Select HR team members',
                                allowClear: true,
                            });
                        }
                    });
                });

                $('#assignTaskModal').on('hidden.bs.modal', function() {
                    $('#assign-task-form')[0].reset();
                    if ($('#assign-to-select').hasClass('select2-hidden-accessible')) {
                        $('#assign-to-select').val(null).trigger('change');
                    }
                });

                $('#confirm-assign-task').on('click', function() {
                    const taskType = $('#task-type-select').val();
                    const assignedTo = $('#assign-to-select').val();
                    const dueDate = $('#assign-task-form input[name="due_date"]').val();

                    if (!taskType) {
                        Swal.fire('Error', 'Please select a task type.', 'error');
                        return;
                    }
                    if (!assignedTo || assignedTo.length === 0) {
                        Swal.fire('Error', 'Please select at least one assignee.', 'error');
                        return;
                    }
                    if (!dueDate) {
                        Swal.fire('Error', 'Please select a due date.', 'error');
                        return;
                    }

                    const formData = $('#assign-task-form').serialize();

                    $.ajax({
                        url: '<?= base_url('recruitment/job-listing/tasks/assign') ?>',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(resp) {
                            $('#assignTaskModal').modal('hide');
                            if (resp.success) {
                                Swal.fire('Success', resp.message, 'success').then(() => loadTasks());
                            } else {
                                Swal.fire('Error', resp.message, 'error');
                            }
                        },
                        error: function() {
                            $('#assignTaskModal').modal('hide');
                            Swal.fire('Error', 'Failed to assign task. Please try again.', 'error');
                        }
                    });
                });

                // Reassign confirm
                $('#confirm-reassign').on('click', function() {
                    const assigneeRecordId = $('#reassign-assignee-record-id').val();
                    const newAssignedTo = $('#reassign-to-select').val();

                    if (!newAssignedTo) {
                        Swal.fire('Error', 'Please select a new assignee.', 'error');
                        return;
                    }

                    $.ajax({
                        url: '<?= base_url('recruitment/job-listing/tasks/reassign') ?>',
                        type: 'POST',
                        data: {
                            assignee_record_id: assigneeRecordId,
                            new_assigned_to: newAssignedTo
                        },
                        dataType: 'json',
                        success: function(resp) {
                            $('#reassignModal').modal('hide');
                            if (resp.success) {
                                Swal.fire('Success', resp.message, 'success').then(() => loadTasks());
                            } else {
                                Swal.fire('Error', resp.message, 'error');
                            }
                        },
                        error: function() {
                            $('#reassignModal').modal('hide');
                            Swal.fire('Error', 'Failed to reassign task. Please try again.', 'error');
                        }
                    });
                });

                // Reassign button (delegated)
                $(document).on('click', '.reassign-btn', function() {
                    openReassignModal($(this).data('assignee-id'));
                });

                // Edit Task
                function openEditModal(task) {
                    $('#edit-task-id').val(task.id);
                    $('#edit-task-type-select').val(task.task_type);
                    $('#edit-task-remarks').val(task.remarks || '');
                    $('#edit-task-due-date').val(task.due_date);
                    $('#editTaskModal').modal('show');
                }

                $(document).on('click', '.edit-task-btn', function() {
                    const taskId = $(this).data('task-id');
                    const task = taskDataMap[taskId];
                    if (task) {
                        openEditModal(task);
                    }
                });

                $('#editTaskModal').on('hidden.bs.modal', function() {
                    $('#edit-task-form')[0].reset();
                });

                $('#confirm-edit-task').on('click', function() {
                    const taskType = $('#edit-task-type-select').val();
                    const dueDate = $('#edit-task-due-date').val();

                    if (!taskType) {
                        Swal.fire('Error', 'Please select a task type.', 'error');
                        return;
                    }
                    if (!dueDate) {
                        Swal.fire('Error', 'Please select a due date.', 'error');
                        return;
                    }

                    $.ajax({
                        url: '<?= base_url('recruitment/job-listing/tasks/edit') ?>',
                        type: 'POST',
                        data: $('#edit-task-form').serialize(),
                        dataType: 'json',
                        success: function(resp) {
                            $('#editTaskModal').modal('hide');
                            if (resp.success) {
                                Swal.fire('Success', resp.message, 'success').then(() => loadTasks());
                            } else {
                                Swal.fire('Error', resp.message, 'error');
                            }
                        },
                        error: function() {
                            $('#editTaskModal').modal('hide');
                            Swal.fire('Error', 'Failed to update task. Please try again.', 'error');
                        }
                    });
                });
            <?php endif; ?>

            // ── Task History ──────────────────────────────────────────────────────────

            const FIELD_LABELS = {
                task_created: 'Task Created',
                assignee_added: 'Assignee Added',
                task_type: 'Task Type',
                remarks: 'Remarks',
                due_date: 'Due Date',
                assigned_to: 'Reassigned To',
                status: 'Status',
            };

            function statusBadgeHtml(value) {
                const cls = {
                    pending: 'badge-light-warning',
                    in_progress: 'badge-light-info',
                    completed: 'badge-light-success',
                } [value] || 'badge-light-secondary';
                const label = {
                    pending: 'Pending',
                    in_progress: 'In Progress',
                    completed: 'Completed',
                } [value] || value;
                return `<span class="badge ${cls}">${label}</span>`;
            }

            function resolveValue(row, side) {
                const val = side === 'old' ? row.old_value : row.new_value;
                const empName = side === 'old' ? row.old_employee_name : row.new_employee_name;
                if (val === null || val === '') return '<span class="text-muted">—</span>';
                if (row.field_name === 'assigned_to' || row.field_name === 'assignee_added') {
                    return empName ? $('<div>').text(empName).html() : `Employee #${val}`;
                }
                if (row.field_name === 'status') {
                    return statusBadgeHtml(val);
                }
                return $('<div>').text(val).html();
            }

            function renderHistoryItem(row, idx) {
                const actor = row.updated_by_name ? $('<div>').text(row.updated_by_name).html() : 'System';
                const label = FIELD_LABELS[row.field_name] || row.field_name;
                const ts = row.created_at || '';

                let description = '';
                if (row.field_name === 'task_created') {
                    description = `Task created with type <strong>${resolveValue(row, 'new')}</strong>`;
                } else if (row.field_name === 'assignee_added') {
                    description = `<strong>${resolveValue(row, 'new')}</strong> added as assignee`;
                } else {
                    const oldHtml = resolveValue(row, 'old');
                    const newHtml = resolveValue(row, 'new');
                    description = `<strong>${label}</strong> changed from ${oldHtml} <i class="fas fa-arrow-right fa-xs text-muted mx-1"></i> ${newHtml}`;
                }

                return `<li class="d-flex gap-3 mb-5">
            <div class="d-flex flex-column align-items-center">
                <div class="symbol symbol-circle symbol-35px bg-light-secondary flex-shrink-0">
                    <span class="symbol-label fs-7 fw-bold text-secondary">${idx + 1}</span>
                </div>
                <div class="border-start border-dashed border-gray-300 flex-grow-1 mt-2"></div>
            </div>
            <div class="flex-grow-1 pb-5">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="fw-semibold text-dark fs-7">${actor}</span>
                    <small class="text-muted ms-3 text-nowrap">${ts}</small>
                </div>
                <div class="text-gray-600 fs-7">${description}</div>
            </div>
        </li>`;
            }

            function openHistoryModal(taskId) {
                const $loading = $('#history-loading');
                const $empty = $('#history-empty');
                const $timeline = $('#history-timeline');

                $loading.removeClass('d-none');
                $empty.addClass('d-none');
                $timeline.addClass('d-none').empty();

                $('#taskHistoryModal').modal('show');

                $.ajax({
                    url: '<?= base_url('recruitment/job-listing/tasks/revisions/') ?>' + taskId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(resp) {
                        $loading.addClass('d-none');
                        if (resp.success && resp.data && resp.data.length > 0) {
                            resp.data.forEach(function(row, idx) {
                                $timeline.append(renderHistoryItem(row, idx));
                            });
                            $timeline.removeClass('d-none');
                        } else {
                            $empty.removeClass('d-none');
                        }
                    },
                    error: function() {
                        $loading.addClass('d-none');
                        $empty.removeClass('d-none');
                    }
                });
            }

            $(document).on('click', '.task-history-btn', function() {
                openHistoryModal($(this).data('task-id'));
            });

            // Status change (delegated — works for any logged-in assignee)
            $(document).on('change', '.task-status-select', function() {
                const assigneeId = $(this).data('assignee-id');
                const newStatus = $(this).val();

                $.ajax({
                    url: '<?= base_url('recruitment/job-listing/tasks/update-status') ?>',
                    type: 'POST',
                    data: {
                        assignee_record_id: assigneeId,
                        status: newStatus
                    },
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.success) {
                            loadTasks();
                        } else {
                            Swal.fire('Error', resp.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to update status. Please try again.', 'error');
                    }
                });
            });

            // ── Init ─────────────────────────────────────────────────────────────────
            $(document).ready(function() {
                loadTasks();
            });

        })();
    </script>
<?php endif; ?>

<?= $this->endSection() ?>
<?= $this->endSection() ?>