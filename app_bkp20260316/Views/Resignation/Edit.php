<?= $this->extend('Templates/DashboardLayout') ?>
<?= $this->section('content') ?>

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
        height: max-content;
        padding: 0rem 0.5rem;
        margin: 0px 5px;
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
</style>

<div class="container mt-4">
    <h2 class="mb-4">Edit Resignation</h2>

    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= base_url('resignation/update/' . $resignation['id']) ?>" method="post" id="resignationForm">
        <?= csrf_field() ?>
        <input type="hidden" name="employee_id" value="<?= esc($resignation['employee_id']) ?>">

        <div class="row">
            <!-- Left Side: Input Fields -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h5 class="mb-0 text-primary"><i class="fa-solid fa-edit"></i> Resignation Details</h5>
                    </div>
                    <div class="card-body">
                        <!-- Employee Display (Read-only in edit mode) -->
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control bg-light" id="employee_display"
                                value="<?= esc(trim($employee['first_name'] . ' ' . $employee['last_name'])) ?> - <?= esc($employee['internal_employee_id']) ?>"
                                disabled placeholder=" ">
                            <label for="employee_display"><i class="fa-solid fa-user"></i> Employee</label>
                        </div>

                        <!-- Resignation Date -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Resignation Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="resignation_date" id="resignation_date"
                                    value="<?= set_value('resignation_date', $resignation['resignation_date']) ?>" required placeholder="Select date">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-calendar-alt"></i>
                                </span>
                            </div>
                            <div class="form-text">Date when HR received the resignation email/letter</div>
                        </div>

                        <!-- Buyout Days -->
                        <div class="form-floating mb-4">
                            <input type="number" class="form-control" name="buyout_days" id="buyout_days"
                                value="<?= set_value('buyout_days', $resignation['buyout_days'] ?? '0') ?>" min="0" placeholder=" ">
                            <label for="buyout_days">Buyout Days</label>
                            <div class="form-text">Number of days the employee is buying out from notice period</div>
                        </div>

                        <!-- Resignation Reason -->
                        <div class="mb-3">
                            <label for="resignation_reason" class="form-label">Resignation Reason</label>
                            <textarea class="form-control" name="resignation_reason" id="resignation_reason" rows="5"
                                placeholder="Enter resignation reason (optional)"><?= set_value('resignation_reason', $resignation['resignation_reason']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Display Fields -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-info bg-opacity-10">
                        <h5 class="mb-0 text-info"><i class="fa-solid fa-info-circle"></i> Employee Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Company -->
                        <div class="mb-4">
                            <label class="text-muted small mb-1">Company</label>
                            <div class="fs-5 fw-semibold"><?= esc($employee['company_name'] ?? 'N/A') ?></div>
                        </div>

                        <!-- Department -->
                        <div class="mb-4">
                            <label class="text-muted small mb-1">Department</label>
                            <div class="fs-5 fw-semibold"><?= esc($employee['department_name'] ?? 'N/A') ?></div>
                        </div>

                        <!-- Notice Period -->
                        <div class="mb-4">
                            <label class="text-muted small mb-1">Notice Period</label>
                            <div class="fs-5 fw-semibold"><?= esc($employee['notice_period'] ?? '30') ?> days</div>
                        </div>

                        <hr class="my-4">

                        <!-- Last Working Day -->
                        <div class="p-3 bg-warning bg-opacity-10 rounded-3 border border-warning">
                            <label class="text-muted small mb-2 d-block">
                                <i class="fa-solid fa-calendar-check text-warning"></i> Last Working Day
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control fw-bold" name="last_working_date" id="last_working_date"
                                    value="<?= set_value('last_working_date', $resignation['last_working_date'] ?? '') ?>" placeholder="Auto-calculated">
                                <button type="button" class="btn btn-outline-warning btn-sm" id="recalculate_lwd" title="Reset to calculated date">
                                    <i class="fa-solid fa-calculator"></i>
                                </button>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 mb-0">
                            <i class="fa-solid fa-lightbulb"></i> <strong>Note:</strong> Last working day is auto-calculated from resignation date, notice period & buyout days. You can override it manually.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-start">
            <div>
                <a href="<?= base_url('resignation') ?>" class="btn btn-light">
                    <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-save"></i> Update Resignation
            </button>
        </div>
    </form>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        var lwdPicker = flatpickr("#last_working_date", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Initialize Flatpickr date picker
        flatpickr("#resignation_date", {
            dateFormat: "Y-m-d",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                calculateLastWorkingDay();
            }
        });

        // If no saved last_working_date, calculate on page load
        <?php if (empty($resignation['last_working_date'])): ?>
            calculateLastWorkingDay();
        <?php endif; ?>

        // Calculate last working day when buyout days changes
        $('#buyout_days').on('input', function() {
            calculateLastWorkingDay();
        });

        $('#recalculate_lwd').on('click', function() {
            calculateLastWorkingDay();
        });

        function calculateLastWorkingDay() {
            const resignationDate = $('#resignation_date').val();
            const noticePeriod = <?= $employee['notice_period'] ?? 30 ?>;
            const buyoutDays = parseInt($('#buyout_days').val()) || 0;

            if (resignationDate) {
                const effectiveNoticePeriod = noticePeriod - buyoutDays;
                const resDate = new Date(resignationDate);
                resDate.setDate(resDate.getDate() + effectiveNoticePeriod);

                // If Last Working Day falls on Sunday (0), push to Monday
                if (resDate.getDay() === 0) {
                    resDate.setDate(resDate.getDate() + 1);
                }

                const yyyy = resDate.getFullYear();
                const mm = String(resDate.getMonth() + 1).padStart(2, '0');
                const dd = String(resDate.getDate()).padStart(2, '0');
                const isoDate = yyyy + '-' + mm + '-' + dd;

                lwdPicker.setDate(isoDate, true);
            } else {
                lwdPicker.clear();
            }
        }

        // Form validation
        $('#resignationForm').on('submit', function(e) {
            if (!$('#resignation_date').val()) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
        });
    });
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>