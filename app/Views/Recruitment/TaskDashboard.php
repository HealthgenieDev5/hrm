<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .summary-card .card-body {
        padding: 1.25rem 1.5rem;
    }

    .summary-card .summary-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .summary-card .summary-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .filter-section label {
        font-size: 0.85rem;
        font-weight: 600;
    }

    .assignee-line {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 3px;
        font-size: 0.82rem;
    }

    .task-action-btn {
        margin-bottom: 2px;
    }

    #task-dashboard-table td {
        vertical-align: middle;
    }

    .overdue-date {
        color: #f1416c;
        font-weight: 600;
    }
</style>

<!-- ── Summary Cards ─────────────────────────────────────────────────────── -->
<div class="row g-4 mb-5">
    <div class="col-6 col-md-3">
        <div class="card card-flush summary-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="symbol symbol-40px me-3 bg-light-primary rounded">
                        <span class="symbol-label"><i class="fas fa-tasks text-primary fs-5"></i></span>
                    </div>
                    <span class="summary-label text-muted">Total Active</span>
                </div>
                <div class="summary-value text-dark" id="card-total">—</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-flush summary-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="symbol symbol-40px me-3 bg-light-warning rounded">
                        <span class="symbol-label"><i class="fas fa-clock text-warning fs-5"></i></span>
                    </div>
                    <span class="summary-label text-muted">Pending</span>
                </div>
                <div class="summary-value text-warning" id="card-pending">—</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-flush summary-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="symbol symbol-40px me-3 bg-light-primary rounded">
                        <span class="symbol-label"><i class="fas fa-spinner text-primary fs-5"></i></span>
                    </div>
                    <span class="summary-label text-muted">In Progress</span>
                </div>
                <div class="summary-value text-primary" id="card-in-progress">—</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-flush summary-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="symbol symbol-40px me-3 bg-light-danger rounded">
                        <span class="symbol-label"><i class="fas fa-exclamation-triangle text-danger fs-5"></i></span>
                    </div>
                    <span class="summary-label text-muted">Overdue</span>
                </div>
                <div class="summary-value text-danger" id="card-overdue">—</div>
            </div>
        </div>
    </div>
</div>

<!-- ── Filter Card ────────────────────────────────────────────────────────── -->
<div class="card mb-5">
    <div class="card-header border-0 pt-5 pb-0">
        <h3 class="card-title fw-bold fs-5 m-0"><i class="fas fa-filter text-primary me-2"></i>Filters</h3>
    </div>
    <div class="card-body pt-4 filter-section">
        <div class="row g-3 align-items-end">

            <!-- Status checkboxes -->
            <div class="col-12 col-md-auto">
                <label class="d-block mb-2">Status</label>
                <div class="d-flex gap-4">
                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="filter-status form-check-input" value="pending" checked>
                        <span>Pending</span>
                    </label>
                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="filter-status form-check-input" value="in_progress" checked>
                        <span>In Progress</span>
                    </label>
                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="filter-status form-check-input" value="completed">
                        <span>Completed</span>
                    </label>
                </div>
            </div>

            <!-- Task Type -->
            <div class="col-12 col-md-2">
                <label class="d-block mb-2">Task Type</label>
                <select class="form-select form-select-sm" id="filter-task-type" data-control="select2" data-placeholder="All Types" data-allow-clear="true">
                    <option value=""></option>
                    <?php foreach ($task_types as $tt): ?>
                        <option value="<?= esc($tt) ?>"><?= esc($tt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($current_user_id === $hr_executive): ?>
                <!-- Assigned To (manager only) -->
                <div class="col-12 col-md-2">
                    <label class="d-block mb-2">Assigned To</label>
                    <select class="form-select form-select-sm" id="filter-assigned-to" data-control="select2" data-placeholder="All HR Members" data-allow-clear="true">
                        <option value=""></option>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Due Date Range -->
            <div class="col-12 col-md-2">
                <label class="d-block mb-2">Due Date Range</label>
                <input type="text" class="form-control form-control-sm" id="filter-due-range" placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly>
            </div>

            <!-- Buttons -->
            <div class="col-12 col-md-auto">
                <button class="btn btn-sm btn-primary me-2" id="btn-apply-filters">
                    <i class="fas fa-search me-1"></i>Apply Filters
                </button>
                <button class="btn btn-sm btn-light" id="btn-clear-filters">
                    <i class="fas fa-times me-1"></i>Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Task Table Card ────────────────────────────────────────────────────── -->
<div class="card">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title fw-bold fs-5 m-0">
            <i class="fas fa-clipboard-list text-primary me-2"></i>Recruitment Tasks
        </h3>
        <div class="card-toolbar">
            <!-- Loading indicator -->
            <span id="table-loading-indicator" class="me-3 d-none">
                <span class="spinner-border spinner-border-sm text-primary me-1" role="status"></span>
                <span class="text-muted fs-7">Loading…</span>
            </span>
            <button class="btn btn-sm btn-light-success me-2" id="btn-export-excel">
                <i class="fas fa-file-excel me-1"></i>Excel
            </button>
            <?php if ($current_user_id === $hr_executive): ?>
                <button class="btn btn-sm btn-primary" id="btn-create-task">
                    <i class="fas fa-plus me-1"></i>Create Task
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3" id="task-dashboard-table">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 rounded-start w-30px">#</th>
                        <th>Job / Company / Dept</th>
                        <th>Task Type</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th class="rounded-end text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<!-- ════════════════════════════════════════════════════════════════════════ -->
<!--  MODALS                                                                  -->
<!-- ════════════════════════════════════════════════════════════════════════ -->

<?php if ($current_user_id === $hr_executive): ?>
    <!-- ── Create Task Modal (manager only) ──────────────────────────────────── -->
    <div class="modal fade" id="createTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2 text-primary"></i>Create Recruitment Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="create-task-form">
                        <input type="hidden" name="job_listing_id" id="create-job-listing-id">

                        <div class="mb-4">
                            <label class="form-label fw-bold required">Job Listing</label>
                            <select class="form-select" id="create-job-listing-select" required>
                                <option value="">-- Search Job Listing --</option>
                            </select>
                            <div class="form-text text-muted">Only approved open/partially-closed listings</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold required">Task Type</label>
                            <select class="form-select" name="task_type" id="create-task-type-select" required>
                                <option value="">-- Select Task Type --</option>
                                <?php foreach ($task_types as $taskType): ?>
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
                            <select class="form-select" name="assigned_to[]" id="create-assign-to-select" multiple required>
                            </select>
                            <div class="form-text text-muted">Select one or more HR team members</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold required">Due Date</label>
                            <input type="text" class="form-control" id="create-due-date" name="due_date" placeholder="YYYY-MM-DD" readonly required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirm-create-task">
                        <i class="fas fa-check me-1"></i>Assign Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Edit Task Modal (manager only) ────────────────────────────────────── -->
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
                                <?php foreach ($task_types as $taskType): ?>
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
                            <input type="text" class="form-control" name="due_date" id="edit-task-due-date" placeholder="YYYY-MM-DD" readonly required>
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

    <!-- ── Reassign Task Modal (manager only) ────────────────────────────────── -->
    <div class="modal fade" id="reassignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2 text-warning"></i>Reassign Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reassign-assignee-record-id">

                    <!-- Shown when the task has multiple non-completed assignees -->
                    <div class="mb-4" id="reassign-which-assignee-wrapper">
                        <label class="form-label fw-bold required">Select Assignee to Reassign</label>
                        <select class="form-select" id="reassign-which-assignee">
                            <option value="">-- Select Assignee --</option>
                        </select>
                    </div>

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
<?php endif; ?>

<!-- ── Update Status Modal (HR assignee) ─────────────────────────────────── -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-sync-alt me-2 text-info"></i>Update Task Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="update-status-assignee-id">
                <div class="mb-4">
                    <label class="form-label fw-bold required">New Status</label>
                    <select class="form-select" id="update-status-select">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white" id="confirm-update-status">
                    <i class="fas fa-check me-1"></i>Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Task History Modal (all roles) ────────────────────────────────────── -->
<div class="modal fade" id="taskHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-history me-2 text-secondary"></i>Task Change History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="history-loading" class="text-center py-6">
                    <div class="spinner-border text-secondary" role="status"></div>
                    <p class="mt-2 text-muted">Loading history…</p>
                </div>
                <div id="history-empty" class="text-center py-6 d-none">
                    <i class="fas fa-scroll fa-2x text-muted mb-3"></i>
                    <p class="text-muted fw-semibold">No history recorded for this task yet.</p>
                </div>
                <ul id="history-timeline" class="list-unstyled mb-0 d-none"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    (function() {
        'use strict';

        // ── PHP-injected constants ────────────────────────────────────────────────
        const BASE_URL = '<?= base_url('/') ?>';
        const IS_MANAGER = <?= ($current_user_id === $hr_executive) ? 'true' : 'false' ?>;
        const CURRENT_USER_ID = <?= (int) $current_user_id ?>;

        // ── Flatpickr instances ───────────────────────────────────────────────────
        let fpDueRange, fpCreateDue, fpEditDue;

        // ── Helpers ──────────────────────────────────────────────────────────────

        function escHtml(str) {
            if (!str) return '';
            return $('<div>').text(str).html();
        }

        function statusBadgeClass(status) {
            return {
                pending: 'badge-light-warning',
                in_progress: 'badge-light-primary',
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

        function renderAssignees(row) {
            if (!row.assignees || row.assignees.length === 0) {
                return '<span class="text-muted">—</span>';
            }
            return row.assignees.map(function(a) {
                const cls = statusBadgeClass(a.status);
                const label = statusLabel(a.status);
                return '<div class="assignee-line">' +
                    '<i class="fas fa-user-circle text-muted fs-7"></i>' +
                    '<span>' + escHtml(a.assigned_to_name) + '</span>' +
                    '<span class="badge ' + cls + ' fs-8">' + label + '</span>' +
                    '</div>';
            }).join('');
        }

        function renderActions(row) {
            if (IS_MANAGER) {
                return '<button class="btn btn-icon btn-sm btn-light-primary edit-task-btn task-action-btn me-1" title="Edit Task" data-task-id="' + row.task_id + '">' +
                    '<i class="fas fa-edit fa-sm"></i>' +
                    '</button>' +
                    '<button class="btn btn-icon btn-sm btn-light-warning reassign-btn task-action-btn me-1" title="Reassign" data-task-id="' + row.task_id + '">' +
                    '<i class="fas fa-user-edit fa-sm"></i>' +
                    '</button>' +
                    '<button class="btn btn-icon btn-sm btn-light-secondary task-history-btn task-action-btn" title="History" data-task-id="' + row.task_id + '">' +
                    '<i class="fas fa-history fa-sm"></i>' +
                    '</button>';
            } else {
                const myAssignee = row.assignees ?
                    row.assignees.find(function(a) {
                        return parseInt(a.assigned_to) === CURRENT_USER_ID;
                    }) :
                    null;
                let html = '';
                if (myAssignee && myAssignee.status !== 'completed') {
                    html += '<button class="btn btn-icon btn-sm btn-light-info update-status-btn task-action-btn me-1" title="Update Status"' +
                        ' data-assignee-id="' + myAssignee.id + '"' +
                        ' data-current-status="' + myAssignee.status + '">' +
                        '<i class="fas fa-sync-alt fa-sm"></i>' +
                        '</button>';
                }
                html += '<button class="btn btn-icon btn-sm btn-light-secondary task-history-btn task-action-btn" title="History" data-task-id="' + row.task_id + '">' +
                    '<i class="fas fa-history fa-sm"></i>' +
                    '</button>';
                return html;
            }
        }

        // ── DataTable init ────────────────────────────────────────────────────────

        const table = $('#task-dashboard-table').DataTable({
            data: [],
            columns: [{
                    data: null,
                    orderable: false,
                    width: '40px',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const link = BASE_URL + 'recruitment/job-listing/view/' + row.job_listing_id;
                        return '<a href="' + link + '" class="fw-bold text-hover-primary" target="_blank">' +
                            escHtml(row.job_title) +
                            '</a>' +
                            '<div class="text-muted fs-7 mt-1">' +
                            escHtml(row.company_name) + ' / ' + escHtml(row.department_name) +
                            '</div>';
                    },
                },
                {
                    data: 'task_type'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return renderAssignees(row);
                    },
                },
                {
                    data: 'due_date',
                    render: function(data, type, row) {
                        if (type === 'sort' || type === 'type') return row.due_date;
                        if (row.is_overdue) {
                            return '<span class="overdue-date">' + escHtml(row.due_date) + '</span>' +
                                ' <span class="badge badge-light-danger ms-1">Overdue</span>';
                        }
                        return escHtml(row.due_date);
                    },
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-end pe-4',
                    render: function(data, type, row) {
                        return renderActions(row);
                    },
                },
            ],
            order: [
                [4, 'asc']
            ],
            pageLength: 25,
            language: {
                emptyTable: 'No tasks found for the selected filters.'
            },
            "dom": '<"mb-3 d-flex justify-content-end"f>rt',
            "buttons": [{
                extend: 'excel',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }],
        });

        // ── Excel export button ───────────────────────────────────────────────────

        $('#btn-export-excel').on('click', function() {
            table.button(0).trigger();
        });

        // ── Summary cards ─────────────────────────────────────────────────────────

        function updateSummaryCards(s) {
            $('#card-total').text(s.total);
            $('#card-pending').text(s.pending);
            $('#card-in-progress').text(s.in_progress);
            $('#card-overdue').text(s.overdue);
        }

        // ── Core load function ────────────────────────────────────────────────────

        function loadDashboardTasks() {
            const statuses = $('.filter-status:checked').map(function() {
                return this.value;
            }).get().join(',');
            const taskType = $('#filter-task-type').val() || '';
            const assignedTo = IS_MANAGER ? ($('#filter-assigned-to').val() || '') : '';
            const dueFrom = (fpDueRange && fpDueRange.selectedDates[0]) ? flatpickr.formatDate(fpDueRange.selectedDates[0], 'Y-m-d') : '';
            const dueTo = (fpDueRange && fpDueRange.selectedDates[1]) ? flatpickr.formatDate(fpDueRange.selectedDates[1], 'Y-m-d') : '';

            $('#table-loading-indicator').removeClass('d-none');

            $.ajax({
                url: BASE_URL + 'recruitment/task-dashboard/tasks',
                type: 'GET',
                dataType: 'json',
                data: {
                    status: statuses,
                    task_type: taskType,
                    assigned_to: assignedTo,
                    due_date_from: dueFrom,
                    due_date_to: dueTo,
                },
                success: function(resp) {
                    if (resp.success) {
                        table.clear().rows.add(resp.data).draw();
                        updateSummaryCards(resp.summary);
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to load tasks. Please refresh the page.', 'error');
                },
                complete: function() {
                    $('#table-loading-indicator').addClass('d-none');
                },
            });
        }

        // ── HR Employee loader (reused across modals) ─────────────────────────────

        function loadHrEmployees(callback) {
            $.ajax({
                url: BASE_URL + 'recruitment/job-listing/tasks/hr-employees',
                type: 'GET',
                dataType: 'json',
                success: function(resp) {
                    if (resp.success && resp.data) {
                        callback(resp.data);
                    }
                },
            });
        }

        // ── Filters ───────────────────────────────────────────────────────────────

        $('#btn-apply-filters').on('click', function() {
            loadDashboardTasks();
        });

        $('#btn-clear-filters').on('click', function() {
            $('.filter-status').prop('checked', false);
            $('.filter-status[value="pending"], .filter-status[value="in_progress"]').prop('checked', true);
            $('#filter-task-type').val(null).trigger('change');
            if (IS_MANAGER) {
                $('#filter-assigned-to').val(null).trigger('change');
            }
            if (fpDueRange) fpDueRange.clear();
            loadDashboardTasks();
        });

        // ── Populate Assigned To filter (manager) & HR employees ─────────────────

        if (IS_MANAGER) {
            loadHrEmployees(function(employees) {
                const sel = $('#filter-assigned-to');
                employees.forEach(function(e) {
                    sel.append('<option value="' + e.id + '">' + escHtml(e.text) + '</option>');
                });
            });
        }

        // ── CREATE TASK MODAL (manager only) ──────────────────────────────────────

        if (IS_MANAGER) {
            $('#btn-create-task').on('click', function() {
                $('#createTaskModal').modal('show');
            });

            $('#createTaskModal').on('shown.bs.modal', function() {
                // Init job listing Select2 with AJAX
                if (!$('#create-job-listing-select').hasClass('select2-hidden-accessible')) {
                    $('#create-job-listing-select').select2({
                        dropdownParent: $('#createTaskModal'),
                        placeholder: 'Search job listing…',
                        allowClear: true,
                        minimumInputLength: 0,
                        ajax: {
                            url: BASE_URL + 'recruitment/task-dashboard/job-listings',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term || ''
                                };
                            },
                            processResults: function(resp) {
                                return {
                                    results: resp.results || []
                                };
                            },
                            cache: true,
                        },
                    });
                }

                // Init assign-to Select2
                loadHrEmployees(function(employees) {
                    const sel = $('#create-assign-to-select');
                    sel.empty();
                    employees.forEach(function(e) {
                        sel.append('<option value="' + e.id + '">' + escHtml(e.text) + '</option>');
                    });
                    if (!sel.hasClass('select2-hidden-accessible')) {
                        sel.select2({
                            dropdownParent: $('#createTaskModal'),
                            placeholder: 'Select HR team members',
                            allowClear: true,
                        });
                    }
                });
            });

            // Sync hidden job_listing_id when Select2 changes
            $('#create-job-listing-select').on('change', function() {
                $('#create-job-listing-id').val($(this).val());
            });

            $('#createTaskModal').on('hidden.bs.modal', function() {
                $('#create-task-form')[0].reset();
                if ($('#create-job-listing-select').hasClass('select2-hidden-accessible')) {
                    $('#create-job-listing-select').val(null).trigger('change');
                }
                if ($('#create-assign-to-select').hasClass('select2-hidden-accessible')) {
                    $('#create-assign-to-select').val(null).trigger('change');
                }
                if (fpCreateDue) fpCreateDue.clear();
                $('#create-job-listing-id').val('');
            });

            $('#confirm-create-task').on('click', function() {
                const jobId = $('#create-job-listing-id').val();
                const taskType = $('#create-task-form select[name="task_type"]').val();
                const assignTo = $('#create-assign-to-select').val();
                const dueDate = $('#create-task-form input[name="due_date"]').val();

                if (!jobId) {
                    Swal.fire('Error', 'Please select a job listing.', 'error');
                    return;
                }
                if (!taskType) {
                    Swal.fire('Error', 'Please select a task type.', 'error');
                    return;
                }
                if (!assignTo || !assignTo.length) {
                    Swal.fire('Error', 'Please select at least one assignee.', 'error');
                    return;
                }
                if (!dueDate) {
                    Swal.fire('Error', 'Please select a due date.', 'error');
                    return;
                }

                $.ajax({
                    url: BASE_URL + 'recruitment/job-listing/tasks/assign',
                    type: 'POST',
                    data: $('#create-task-form').serialize(),
                    dataType: 'json',
                    success: function(resp) {
                        $('#createTaskModal').modal('hide');
                        if (resp.success) {
                            Swal.fire('Success', resp.message, 'success').then(loadDashboardTasks);
                        } else {
                            Swal.fire('Error', resp.message, 'error');
                        }
                    },
                    error: function() {
                        $('#createTaskModal').modal('hide');
                        Swal.fire('Error', 'Failed to assign task. Please try again.', 'error');
                    },
                });
            });

            // ── EDIT TASK MODAL ───────────────────────────────────────────────────

            $(document).on('click', '.edit-task-btn', function() {
                const taskId = $(this).data('task-id');
                const rowData = table.rows(function(idx, data) {
                    return data.task_id === taskId;
                }).data()[0];
                if (!rowData) return;
                $('#edit-task-id').val(rowData.task_id);
                $('#edit-task-type-select').val(rowData.task_type);
                $('#edit-task-remarks').val(rowData.remarks || '');
                if (fpEditDue) fpEditDue.setDate(rowData.due_date, false);
                $('#editTaskModal').modal('show');
            });

            $('#editTaskModal').on('hidden.bs.modal', function() {
                $('#edit-task-form')[0].reset();
                if (fpEditDue) fpEditDue.clear();
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
                    url: BASE_URL + 'recruitment/job-listing/tasks/edit',
                    type: 'POST',
                    data: $('#edit-task-form').serialize(),
                    dataType: 'json',
                    success: function(resp) {
                        $('#editTaskModal').modal('hide');
                        if (resp.success) {
                            Swal.fire('Success', resp.message, 'success').then(loadDashboardTasks);
                        } else {
                            Swal.fire('Error', resp.message, 'error');
                        }
                    },
                    error: function() {
                        $('#editTaskModal').modal('hide');
                        Swal.fire('Error', 'Failed to update task. Please try again.', 'error');
                    },
                });
            });

            // ── REASSIGN MODAL ────────────────────────────────────────────────────

            $(document).on('click', '.reassign-btn', function() {
                const taskId = $(this).data('task-id');
                const rowData = table.rows(function(idx, data) {
                    return data.task_id === taskId;
                }).data()[0];
                if (!rowData) return;

                const activeAssignees = (rowData.assignees || []).filter(function(a) {
                    return a.status !== 'completed';
                });

                // Populate "Which Assignee" section
                const $whichWrapper = $('#reassign-which-assignee-wrapper');
                const $whichSelect = $('#reassign-which-assignee');
                $whichSelect.empty().append('<option value="">-- Select Assignee --</option>');

                if (activeAssignees.length === 1) {
                    // Auto-select single assignee, hide the picker
                    $('#reassign-assignee-record-id').val(activeAssignees[0].id);
                    $whichWrapper.addClass('d-none');
                } else {
                    activeAssignees.forEach(function(a) {
                        $whichSelect.append('<option value="' + a.id + '">' + escHtml(a.assigned_to_name) + '</option>');
                    });
                    $('#reassign-assignee-record-id').val('');
                    $whichWrapper.removeClass('d-none');
                }

                // Load HR employees for "Reassign To"
                loadHrEmployees(function(employees) {
                    const sel = $('#reassign-to-select');
                    sel.empty().append('<option value="">-- Select HR Member --</option>');
                    employees.forEach(function(e) {
                        sel.append('<option value="' + e.id + '">' + escHtml(e.text) + '</option>');
                    });
                });

                $('#reassignModal').modal('show');
            });

            // Sync record id when "Which Assignee" changes
            $('#reassign-which-assignee').on('change', function() {
                $('#reassign-assignee-record-id').val($(this).val());
            });

            $('#confirm-reassign').on('click', function() {
                const assigneeRecordId = $('#reassign-assignee-record-id').val();
                const newAssignedTo = $('#reassign-to-select').val();

                if (!assigneeRecordId) {
                    Swal.fire('Error', 'Please select an assignee to reassign.', 'error');
                    return;
                }
                if (!newAssignedTo) {
                    Swal.fire('Error', 'Please select a new HR member.', 'error');
                    return;
                }

                $.ajax({
                    url: BASE_URL + 'recruitment/job-listing/tasks/reassign',
                    type: 'POST',
                    data: {
                        assignee_record_id: assigneeRecordId,
                        new_assigned_to: newAssignedTo
                    },
                    dataType: 'json',
                    success: function(resp) {
                        $('#reassignModal').modal('hide');
                        if (resp.success) {
                            Swal.fire('Success', resp.message, 'success').then(loadDashboardTasks);
                        } else {
                            Swal.fire('Error', resp.message, 'error');
                        }
                    },
                    error: function() {
                        $('#reassignModal').modal('hide');
                        Swal.fire('Error', 'Failed to reassign task. Please try again.', 'error');
                    },
                });
            });
        } // end IS_MANAGER block

        // ── UPDATE STATUS MODAL (HR) ──────────────────────────────────────────────

        $(document).on('click', '.update-status-btn', function() {
            const assigneeId = $(this).data('assignee-id');
            const currentStatus = $(this).data('current-status');
            $('#update-status-assignee-id').val(assigneeId);
            $('#update-status-select').val(currentStatus);
            $('#updateStatusModal').modal('show');
        });

        $('#confirm-update-status').on('click', function() {
            const assigneeId = $('#update-status-assignee-id').val();
            const newStatus = $('#update-status-select').val();

            $.ajax({
                url: BASE_URL + 'recruitment/job-listing/tasks/update-status',
                type: 'POST',
                data: {
                    assignee_record_id: assigneeId,
                    status: newStatus
                },
                dataType: 'json',
                success: function(resp) {
                    $('#updateStatusModal').modal('hide');
                    if (resp.success) {
                        Swal.fire('Success', 'Status updated successfully.', 'success').then(loadDashboardTasks);
                    } else {
                        Swal.fire('Error', resp.message, 'error');
                    }
                },
                error: function() {
                    $('#updateStatusModal').modal('hide');
                    Swal.fire('Error', 'Failed to update status. Please try again.', 'error');
                },
            });
        });

        // ── TASK HISTORY MODAL ────────────────────────────────────────────────────

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
            return '<span class="badge ' + cls + '">' + label + '</span>';
        }

        function resolveHistoryValue(row, side) {
            const val = side === 'old' ? row.old_value : row.new_value;
            const empName = side === 'old' ? row.old_employee_name : row.new_employee_name;
            if (val === null || val === '') return '<span class="text-muted">—</span>';
            if (row.field_name === 'assigned_to' || row.field_name === 'assignee_added') {
                return empName ? escHtml(empName) : 'Employee #' + val;
            }
            if (row.field_name === 'status') {
                return statusBadgeHtml(val);
            }
            return escHtml(val);
        }

        function renderHistoryItem(row, idx) {
            const actor = row.updated_by_name ? escHtml(row.updated_by_name) : 'System';
            const label = FIELD_LABELS[row.field_name] || row.field_name;
            const ts = row.created_at || '';

            let description = '';
            if (row.field_name === 'task_created') {
                description = 'Task created with type <strong>' + resolveHistoryValue(row, 'new') + '</strong>';
            } else if (row.field_name === 'assignee_added') {
                description = '<strong>' + resolveHistoryValue(row, 'new') + '</strong> added as assignee';
            } else {
                description = '<strong>' + escHtml(label) + '</strong> changed from ' +
                    resolveHistoryValue(row, 'old') +
                    ' <i class="fas fa-arrow-right fa-xs text-muted mx-1"></i> ' +
                    resolveHistoryValue(row, 'new');
            }

            return '<li class="d-flex gap-3 mb-5">' +
                '<div class="d-flex flex-column align-items-center">' +
                '<div class="symbol symbol-circle symbol-35px bg-light-secondary flex-shrink-0">' +
                '<span class="symbol-label fs-7 fw-bold text-secondary">' + (idx + 1) + '</span>' +
                '</div>' +
                '<div class="border-start border-dashed border-gray-300 flex-grow-1 mt-2"></div>' +
                '</div>' +
                '<div class="flex-grow-1 pb-5">' +
                '<div class="d-flex justify-content-between align-items-start mb-1">' +
                '<span class="fw-semibold text-dark fs-7">' + actor + '</span>' +
                '<small class="text-muted ms-3 text-nowrap">' + escHtml(ts) + '</small>' +
                '</div>' +
                '<div class="text-gray-600 fs-7">' + description + '</div>' +
                '</div>' +
                '</li>';
        }

        $(document).on('click', '.task-history-btn', function() {
            const taskId = $(this).data('task-id');
            const $loading = $('#history-loading');
            const $empty = $('#history-empty');
            const $timeline = $('#history-timeline');

            $loading.removeClass('d-none');
            $empty.addClass('d-none');
            $timeline.addClass('d-none').empty();

            $('#taskHistoryModal').modal('show');

            $.ajax({
                url: BASE_URL + 'recruitment/job-listing/tasks/revisions/' + taskId,
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
                },
            });
        });

        // ── Init ──────────────────────────────────────────────────────────────────

        $(document).ready(function() {

            const fpCommonOpts = {
                dateFormat: 'Y-m-d',
                allowInput: true,
                altInput: true,
                altFormat: 'Y-m-d',
                altInputClass: 'form-control form-control-sm',
                appendTo: document.body,
            };

            // Filter date range picker
            fpDueRange = flatpickr('#filter-due-range', $.extend({}, fpCommonOpts, {
                mode: 'range',
                altInputClass: 'form-control form-control-sm',
            }));

            // Modal date pickers
            if (IS_MANAGER) {
                fpCreateDue = flatpickr('#create-due-date', $.extend({}, fpCommonOpts, {
                    altInputClass: 'form-control',
                    minDate: 'today',
                }));
                fpEditDue = flatpickr('#edit-task-due-date', $.extend({}, fpCommonOpts, {
                    altInputClass: 'form-control',
                }));
            }

            loadDashboardTasks();
        });

    })();
</script>
<?= $this->endSection() ?>