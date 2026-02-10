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
                    : 'Not Set';
                ?>


                <div class="card my-5">
                    <div class="card mb-6 mb-xl-9">
                        <div class="card-body pt-9 pb-0">
                            <!--begin::Details-->



                            <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-3">
                                                    <?= esc($job->job_title) ?>
                                                </a>
                                                <span class="badge badge-light-<?= esc($badgeClass) ?> me-auto">
                                                    <?= esc(ucwords($job->status)) ?>
                                                </span>
                                            </div>

                                            <div class="d-flex flex-wrap fw-bold mb-4 fs-5 text-gray-400">
                                                <?= esc($job->company_name ?? 'N/A') ?> requires the
                                                <?= esc($job->type_of_job ?? 'N/A') ?> role to be filled from
                                                <?= esc($jobOpeningDate) ?>
                                            </div>
                                        </div>

                                        <div class="d-flex mb-4">
                                            <a href="<?= site_url('recruitment/job-listing/download/' . $job->id) ?>" class="btn btn-sm btn-bg-light btn-active-color-primary me-3">Download PDF</a>
                                            <a href="<?= site_url('recruitment/job-listing/edit/' . $job->id) ?>" class="btn btn-sm btn-primary me-3">Edit</a>
                                            <a href="<?= site_url('recruitment/job-listing/close/' . $job->id) ?>" class="btn btn-sm btn-danger me-3 close-job-listing">Close</a>
                                        </div>
                                    </div>

                                    <!-- <div class="d-flex flex-wrap justify-content-start">
                                        <div class="d-flex flex-wrap">
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="fs-4 fw-bolder"><?= esc($jobOpeningDate) ?></div>
                                                <div class="fw-bold fs-6 text-gray-400">Opening Date</div>
                                            </div>

                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="fs-4 fw-bolder"><?= esc($job->no_of_vacancy ?? '0') ?></div>
                                                <div class="fw-bold fs-6 text-gray-400">Vacancies</div>
                                            </div>

                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="fs-4 fw-bolder">
                                                    ₹<?= esc(number_format($job->min_budget)) ?> - ₹<?= esc(number_format($job->max_budget)) ?>
                                                </div>
                                                <div class="fw-bold fs-6 text-gray-400">Budget</div>
                                            </div>
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="fs-4 fw-bolder">
                                                    <?= esc(number_format($job->min_experience)) ?>- <?= esc(number_format($job->max_experience)) ?> Yr
                                                </div>
                                                <div class="fw-bold fs-6 text-gray-400">Experience</div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>







                            <div class="d-flex flex-wrap flex-stack">
                                <div class="d-flex flex-column flex-grow-1 pe-8">
                                    <div class="d-flex flex-wrap">
                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <div class="fs-4 fw-bolder"><?= esc($jobOpeningDate) ?></div>
                                            <div class="fw-bold fs-6 text-gray-400">Opening Date</div>
                                        </div>

                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <div class="fs-4 fw-bolder"><?= esc($job->no_of_vacancy ?? '0') ?></div>
                                            <div class="fw-bold fs-6 text-gray-400">Vacancies</div>
                                        </div>

                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <div class="fs-4 fw-bolder">
                                                ₹<?= esc(number_format($job->min_budget)) ?> - ₹<?= esc(number_format($job->max_budget)) ?>
                                            </div>
                                            <div class="fw-bold fs-6 text-gray-400">Budget</div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <div class="fs-4 fw-bolder">
                                                <?= esc(number_format($job->min_experience)) ?>- <?= esc(number_format($job->max_experience)) ?> Yr
                                            </div>
                                            <div class="fw-bold fs-6 text-gray-400">Experience</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                                    <?php
                                    $currentUser = session()->get('current_user')['employee_id'];
                                    $hrExecutive = $job->approved_by_hr_executive;
                                    $hrManager = $job->approved_by_hr_manager;

                                    if (empty($hrExecutive) && empty($hrManager)) {
                                        if ($currentUser == 52) {
                                            echo '<button class="btn btn-warning w-100 text-center approval-btn" data-job-id="' . $job->id . '" data-approval-type="hr_executive">';
                                            echo '<span class="indicator-label">Approve as HR Executive</span>';
                                            echo '<span class="indicator-progress d-none">Processing... <span class="spinner-border spinner-border-sm ms-2"></span></span>';
                                            echo '</button>';
                                        } else {
                                            echo '<div class="badge badge-warning">Pending HR Executive Approval</div>';
                                        }
                                        echo '<small class="text-muted mt-2 d-block">Awaiting HR Executive approval</small>';
                                    } elseif (!empty($hrExecutive) && empty($hrManager)) {
                                        echo '<div class="mb-3">';
                                        echo '<div class="badge badge-light-success mb-2">✓ HR Executive Approved</div>';
                                        echo '</div>';

                                        if ($currentUser == 293) {
                                            echo '<button class="btn btn-primary w-100 text-center approval-btn" data-job-id="' . $job->id . '" data-approval-type="hr_manager">';
                                            echo '<span class="indicator-label">Approve as HR Manager</span>';
                                            echo '<span class="indicator-progress d-none">Processing... <span class="spinner-border spinner-border-sm ms-2"></span></span>';
                                            echo '</button>';
                                        } else {
                                            echo '<div class="badge badge-primary">Awaiting HR Manager Approval</div>';
                                        }
                                        echo '<small class="text-muted mt-2 d-block">Awaiting HR Manager approval</small>';
                                    } elseif (!empty($hrExecutive) && !empty($hrManager)) {
                                        echo '<div class="text-center">';
                                        echo '<div class="badge badge-light-success mb-2">✓ HR Executive Approved</div><br>';
                                        echo '<div class="badge badge-light-success mb-3">✓ HR Manager Approved</div>';
                                        echo '<div class="fw-bold text-success">Fully Approved</div>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>


                            <div class="separator"></div>



                            <!--begin::Nav Tabs-->
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder" role="tablist">
                                <li class="nav-item"><a class="nav-link active py-5 me-6" data-bs-toggle="tab" href="#overview<?= $job->id ?>">Overview</a></li>
                                <li class="nav-item"><a class="nav-link py-5 me-6" data-bs-toggle="tab" href="#tests<?= $job->id ?>">Tests</a></li>
                                <li class="nav-item"><a class="nav-link py-5 me-6" data-bs-toggle="tab" href="#review<?= $job->id ?>">Review Schedule</a></li>
                                <li class="nav-item"><a class="nav-link py-5 me-6" data-bs-toggle="tab" href="#attachment<?= $job->id ?>">Attachment</a></li>
                                <li class="nav-item"><a class="nav-link py-5 me-6" data-bs-toggle="tab" href="#others<?= $job->id ?>">Others</a></li>
                            </ul>
                            <!--end::Nav Tabs-->

                            <div class="tab-content mt-4">
                                <!-- Overview -->
                                <div class="tab-pane fade show active" id="overview<?= $job->id ?>">
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
                                        <i class="bi bi-person text-muted me-1" data-bs-toggle="tooltip" title="Created By"></i>
                                        <strong>Created By:</strong> <?= esc($job->created_by_name ?? 'N/A') ?>
                                    </p>
                                    <p>
                                        <i class="bi bi-person-badge text-muted me-1" data-bs-toggle="tooltip" title="Reporting Manager"></i>
                                        <strong>Reporting To:</strong> <?= esc($job->reporting_to_name ?? 'N/A') ?>
                                        <?php if (!empty($job->reporting_to_designation)): ?>
                                            (<?= esc($job->reporting_to_designation) ?>)
                                        <?php endif; ?>
                                    </p>
                                </div>

                                <!-- Tests -->
                                <div class="tab-pane fade" id="tests<?= $job->id ?>">
                                    <p><strong>Technical Test:</strong>
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
                                    <p><strong>IQ Test:</strong>
                                        <?php
                                        $iqTest = json_decode($job->iq_test_required, true);
                                        echo $iqTest['required'];

                                        ?></p>
                                    <p><strong>English Test:</strong>
                                        <?php $englishTest = json_decode($job->eng_test_required, true);
                                        echo $englishTest['required'];
                                        ?></p>
                                    <p><strong>Operation Test:</strong>
                                        <?php $operationTest = json_decode($job->operation_test_required, true);
                                        echo $operationTest['required'];
                                        ?></p>
                                    <p><strong>Any Other Test Required:</strong>
                                        <?php
                                        $otherTest = json_decode($job->other_test_required, true);
                                        if (is_array($otherTest) && isset($otherTest['required'])) {
                                            echo esc($otherTest['required']);
                                            if ($otherTest['required'] === 'Yes' && isset($otherTest['tests']) && is_array($otherTest['tests'])) {
                                                echo ' - ' . esc(implode(' | ', $otherTest['tests']));
                                            }
                                        }

                                        ?></p>
                                </div>

                                <!-- Review -->
                                <div class="tab-pane fade" id="review<?= $job->id ?>">
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
                                    <p><strong>Educational Qualification:</strong>
                                        <?= nl2br(esc($job->educational_qualification)) ?></p>

                                    <div>
                                        <div><strong>Job Description / Requirement:</strong>
                                            <?php
                                            echo $job->job_description;
                                            ?>


                                        </div>

                                        <div>
                                            <p><strong>System Required:</strong>
                                                <?= nl2br(esc($job->system_required)) ?></p>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="fw-bolder m-0 text-gray-800">Issues amd Resolution</h3>
                        </div>
                    </div>
                    <div class="card-body" id="comments-section">
                        <div id="comment-notifications"></div>

                        <div class="mb-5">
                            <div class="card card-body bg-light">
                                <form id="comment-form" method="post">
                                    <input type="hidden" name="job_id" value="<?= $job->id ?>">

                                    <div class="d-flex align-items-center mb-2 bg-light p-2 rounded-top border">
                                        <label for="commentType" class="form-label mb-0 me-2" style="white-space: nowrap;"><strong>Comment Type:</strong></label>
                                        <select class="form-select form-select-sm" id="commentType" name="type" style="width: auto; min-width: 150px;">
                                            <option value="comment">General Comment</option>
                                            <option value="issue">Issue</option>
                                            <option value="resolution">Resolution</option>
                                            <option value="question">Question</option>
                                            <option value="answer">Answer</option>
                                            <option value="feedback">Feedback</option>
                                            <option value="concern">Concern</option>
                                            <option value="suggestion">Suggestion</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="commentText" class="form-label">Describe your issue</label>
                                        <textarea class="form-control summernote" id="commentText" name="comment" rows="3" placeholder="Write your issue here..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="submit-comment-btn">
                                        <span id="submit-btn-text">Submit your issue</span>
                                        <span id="submit-btn-loading" class="d-none">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            Submitting...
                                        </span>
                                    </button>
                                </form>
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
                <!--end::Tab Content-->
        </div>


    <?php endif; ?>

    </div>
</div>






<?= $this->section('javascript') ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js"></script>
<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').each(function() {
            new bootstrap.Tooltip(this);
        });

        $('.close-job-listing').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, close it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Write your issue here...',
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

            if (comments.length === 0) {
                noCommentsMsg.show();
                container.find('.timeline-item').remove();
                return;
            }

            noCommentsMsg.hide();
            container.empty();

            // Create timeline wrapper
            const timelineWrapper = $('<div class="timeline-label"></div>');

            comments.forEach(function(comment) {
                const typeBadgeClass = getTypeBadgeClass(comment.type);
                const typeLabel = getTypeLabel(comment.type);
                const statusClass = getStatusClass(comment.type);

                // Profile picture HTML
                const profilePicture = comment.profile_picture ?
                    `<img src="${comment.profile_picture}" alt="${comment.sender_name}">` :
                    `<div class="symbol symbol-circle symbol-45px bg-light-primary">
                        <span class="symbol-label text-primary fw-bold fs-6">${(comment.sender_name || 'U').charAt(0).toUpperCase()}</span>
                    </div>`;

                const commentHtml = `
                    <!--begin::Item-->
                    <div class="timeline-item">
                        <!--begin::Label-->
                        <div class="timeline-label fw-bolder text-gray-800 fs-6">${comment.time_ago}</div>
                        <!--end::Label-->
                        <div class="timeline-badge">
                            <i class="fa fa-genderless ${statusClass} fs-1"></i>
                        </div>
                        <!--end::Badge-->
                        <!--begin::Text-->
                        <div class="fw-normal timeline-content text-muted ps-3">
                            <div class="d-flex mb-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-circle symbol-45px me-5">
                                    ${profilePicture}
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Info-->
                                <div class="d-flex flex-column flex-row-fluid p-3 shadow rounded-2">
                                    <!--begin::Info-->
                                    <div class="d-flex align-items-center flex-wrap mb-1">
                                        <a href="#" class="text-gray-800 text-hover-primary fw-bolder me-2">${comment.sender_name || 'Unknown User'}</a>
                                        <span class="badge ${typeBadgeClass} comment-type-badge">${typeLabel}</span>
                                        <a href="#" class="ms-auto text-gray-400 text-hover-primary fw-bold fs-7 reply-btn" data-comment_id="${comment.id || ''}" data-post_id="">Reply</a>
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Post-->
                                    <div class="text-gray-800 fs-7 fw-normal pt-1">${comment.content}</div>
                                    <!--end::Post-->
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                        <!--end::Text-->
                    </div>
                    <!--end::Item-->
                `;

                timelineWrapper.append(commentHtml);
            });

            container.append(timelineWrapper);
            container.scrollTop(0);
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

        $('#comment-form').on('submit', function(e) {
            e.preventDefault();
            const commentContent = $('#commentText').summernote('code');
            if (!commentContent || commentContent.trim() === '' || commentContent === '<p><br></p>') {
                alert('Please enter a comment before submitting.');
                return;
            }
            $('#submit-btn-text').addClass('d-none');
            $('#submit-btn-loading').removeClass('d-none');
            $('#submit-comment-btn').prop('disabled', true);
            const formData = {
                job_id: $('input[name="job_id"]').val(),
                comment: commentContent,
                type: $('#commentType').val()
            };

            $.ajax({
                url: '<?= site_url("recruitment/job-listing/comments/add-comment/" . $job->id) ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#commentText').summernote('reset');
                        $('#commentType').val('comment');

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

            if (approvalType === 'hr_executive') {
                Swal.fire({
                    title: 'Set Job Opening Date',
                    html: '<input type="date" id="job-opening-date" class="form-control" min="' + new Date().toISOString().split('T')[0] + '" required>',
                    showCancelButton: true,
                    confirmButtonText: 'Approve',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    preConfirm: () => {
                        const date = document.getElementById('job-opening-date').value;
                        if (!date) {
                            Swal.showValidationMessage('Please select a job opening date');
                            return false;
                        }
                        return date;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        processApproval(jobId, approvalType, button, originalText, result.value);
                    }
                });
            } else {
                processApproval(jobId, approvalType, button, originalText);
            }
        });

        function processApproval(jobId, approvalType, button, originalText, jobOpeningDate = null) {
            // Show loading state
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

        // Handle reply button click
        $(document).on('click', '.reply-btn', function(e) {
            e.preventDefault();

            // Focus on the comment text area using Summernote's focus method
            $('#commentText').summernote('focus');

            // Scroll to the comment form smoothly
            $('html, body').animate({
                scrollTop: $('#comment-form').offset().top - 100
            }, 500);
        });

        startAutoRefresh();
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>