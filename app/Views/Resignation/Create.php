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
    <h2 class="mb-4">Add New Resignation</h2>

    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= base_url('resignation/store') ?>" method="post" id="resignationForm">
        <?= csrf_field() ?>

        <div class="row">
            <!-- Left Side: Input Fields -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h5 class="mb-0 text-primary"><i class="fa-solid fa-edit"></i> Resignation Details</h5>
                    </div>
                    <div class="card-body">
                        <!-- Employee Selection -->
                        <div class="form-floating mb-4">
                            <select class="form-select" name="employee_id" id="employee_id" required data-control="select2">
                                <option value="">Select Employee</option>
                                <?php foreach ($employees as $employee):
                                    $is_selected = (isset($preselected_employee_id) && $preselected_employee_id == $employee['id']);
                                ?>
                                    <option value="<?= $employee['id'] ?>"
                                        data-company="<?= esc($employee['company_name'] ?? 'N/A') ?>"
                                        data-department="<?= esc($employee['department_name'] ?? 'N/A') ?>"
                                        data-designation="<?= esc($employee['designation'] ?? 'N/A') ?>"
                                        data-notice-period="<?= esc($employee['notice_period'] ?? '90') ?>"
                                        data-employee-id="<?= esc($employee['internal_employee_id']) ?>"
                                        <?= $is_selected ? 'selected' : set_select('employee_id', $employee['id']) ?>>
                                        <?= esc(trim($employee['first_name'] . ' ' . $employee['last_name'])) ?> - <?= esc($employee['internal_employee_id']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                            <label for="employee_id">Employee <span class="text-danger">*</span></label>
                        </div>

                        <!-- Resignation Date -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Resignation Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="resignation_date" id="resignation_date"
                                    value="<?= set_value('resignation_date', date('Y-m-d')) ?>" required placeholder="Select date">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-calendar-alt"></i>
                                </span>
                            </div>
                            <div class="form-text">Date when HR received the resignation email/letter</div>
                        </div>

                        <!-- Buyout Days -->
                        <div class="form-floating mb-4">
                            <input type="number" class="form-control" name="buyout_days" id="buyout_days"
                                value="<?= set_value('buyout_days', '0') ?>" min="0" placeholder=" ">
                            <label for="buyout_days">Buyout Days</label>
                            <div class="form-text">Number of days the employee is buying out from notice period</div>
                        </div>

                        <!-- Resignation Reason -->
                        <div class="mb-3">
                            <label for="resignation_reason" class="form-label">Resignation Reason</label>
                            <textarea class="form-control" name="resignation_reason" id="resignation_reason" rows="5"
                                placeholder="Enter resignation reason (optional)"><?= set_value('resignation_reason') ?></textarea>
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
                            <div class="fs-5 fw-semibold" id="display_company">-</div>
                        </div>

                        <!-- Department -->
                        <div class="mb-4">
                            <label class="text-muted small mb-1">Department</label>
                            <div class="fs-5 fw-semibold" id="display_department">-</div>
                        </div>

                        <!-- Notice Period -->
                        <div class="mb-4">
                            <label class="text-muted small mb-1">Notice Period</label>
                            <div class="fs-5 fw-semibold" id="display_notice_period">-</div>
                        </div>

                        <hr class="my-4">

                        <!-- Last Working Day -->
                        <div class="p-3 bg-warning bg-opacity-10 rounded-3 border border-warning">
                            <label class="text-muted small mb-2 d-block">
                                <i class="fa-solid fa-calendar-check text-warning"></i> Last Working Day
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control fw-bold" name="last_working_date" id="last_working_date"
                                    value="<?= set_value('last_working_date') ?>" placeholder="Auto-calculated">
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
                <a href="<?= base_url('resignations') ?>" class="btn btn-light">
                    <i class="fa-solid fa-arrow-left"></i> Back to List
                </a>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-save"></i> Submit Resignation
            </button>
        </div>
    </form>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        $('#employee_id').select2();

        var lwdPicker = flatpickr("#last_working_date", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        flatpickr("#resignation_date", {
            dateFormat: "Y-m-d",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                calculateLastWorkingDay();
            }
        });

        $('#employee_id').on('change.select2', function() {
            const selectedOption = $(this).find('option:selected');

            if (selectedOption.val()) {
                $('#display_company').text(selectedOption.data('company'));
                $('#display_department').text(selectedOption.data('department'));
                $('#display_notice_period').text(selectedOption.data('notice-period') + ' days');

                const newUrl = new URL(window.location);
                newUrl.searchParams.set('employee_id', selectedOption.val());
                window.history.replaceState({}, '', newUrl);

                calculateLastWorkingDay();
            } else {
                $('#display_company').text('-');
                $('#display_department').text('-');
                $('#display_notice_period').text('-');
                lwdPicker.clear();

                const newUrl = new URL(window.location);
                newUrl.searchParams.delete('employee_id');
                window.history.replaceState({}, '', newUrl);
            }
        });

        <?php if (isset($preselected_employee_id) && !empty($preselected_employee_id)): ?>
            $('#employee_id').trigger('change.select2');
        <?php endif; ?>

        $('#buyout_days').on('input', function() {
            calculateLastWorkingDay();
        });

        $('#recalculate_lwd').on('click', function() {
            calculateLastWorkingDay();
        });

        function calculateLastWorkingDay() {
            const selectedOption = $('#employee_id').find('option:selected');
            const resignationDate = $('#resignation_date').val();
            const buyoutDays = parseInt($('#buyout_days').val()) || 0;

            if (selectedOption.val() && resignationDate) {
                const noticePeriod = parseInt(selectedOption.data('notice-period')) || 30;
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

        $('#resignationForm').on('submit', function(e) {
            if (!$('#employee_id').val() || !$('#resignation_date').val()) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
        });
    });
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>