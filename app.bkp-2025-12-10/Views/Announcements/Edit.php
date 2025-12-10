<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">
                <i class="bi bi-pencil me-2"></i> Edit Announcement
            </h3>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="/announcements/update/<?= $announcement['id'] ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="<?= old('title', $announcement['title']) ?>" required maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="8" required><?= old('message', $announcement['message']) ?></textarea>
                            <small class="text-muted">You can use HTML formatting</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="info" <?= old('type', $announcement['type']) === 'info' ? 'selected' : '' ?>>Info</option>
                                <option value="success" <?= old('type', $announcement['type']) === 'success' ? 'selected' : '' ?>>Success</option>
                                <option value="warning" <?= old('type', $announcement['type']) === 'warning' ? 'selected' : '' ?>>Warning</option>
                                <option value="danger" <?= old('type', $announcement['type']) === 'danger' ? 'selected' : '' ?>>Danger</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="low" <?= old('priority', $announcement['priority']) === 'low' ? 'selected' : '' ?>>Low</option>
                                <option value="medium" <?= old('priority', $announcement['priority']) === 'medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="high" <?= old('priority', $announcement['priority']) === 'high' ? 'selected' : '' ?>>High</option>
                                <option value="critical" <?= old('priority', $announcement['priority']) === 'critical' ? 'selected' : '' ?>>Critical</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="target_type" class="form-label">Target Audience <span class="text-danger">*</span></label>
                            <select class="form-select" id="target_type" name="target_type" required>
                                <option value="all" <?= old('target_type', $announcement['target_type']) === 'all' ? 'selected' : '' ?>>All Employees</option>
                                <option value="department" <?= old('target_type', $announcement['target_type']) === 'department' ? 'selected' : '' ?>>Specific Departments</option>
                                <option value="designation" <?= old('target_type', $announcement['target_type']) === 'designation' ? 'selected' : '' ?>>Specific Designations</option>
                                <option value="specific" <?= old('target_type', $announcement['target_type']) === 'specific' ? 'selected' : '' ?>>Specific Employees</option>
                            </select>
                        </div>

                        <?php
                        $targetIds = !empty($announcement['target_ids']) ? explode(',', $announcement['target_ids']) : [];
                        ?>

                        <div class="mb-3" id="target_departments_div" style="display: none;">
                            <label class="form-label">Select Departments</label>
                            <select class="form-select" id="target_departments" name="target_departments[]" multiple size="5">
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"
                                        <?= in_array($dept['id'], $targetIds) ? 'selected' : '' ?>>
                                        <?= esc($dept['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Hold Ctrl to select multiple</small>
                        </div>

                        <div class="mb-3" id="target_designations_div" style="display: none;">
                            <label class="form-label">Select Designations</label>
                            <select class="form-select" id="target_designations" name="target_designations[]" multiple size="5">
                                <?php foreach ($designations as $desig): ?>
                                    <option value="<?= $desig['id'] ?>"
                                        <?= in_array($desig['id'], $targetIds) ? 'selected' : '' ?>>
                                        <?= esc($desig['designation']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Hold Ctrl to select multiple</small>
                        </div>

                        <div class="mb-3" id="target_employees_div" style="display: none;">
                            <label class="form-label">Employee IDs</label>
                            <input type="text" class="form-control" id="target_employees" name="target_employees"
                                   value="<?= old('target_employees', $announcement['target_ids']) ?>"
                                   placeholder="e.g., 1,2,3,4">
                            <small class="text-muted">Comma-separated employee IDs</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date (Optional)</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                   value="<?= old('start_date', $announcement['start_date'] ? date('Y-m-d\TH:i', strtotime($announcement['start_date'])) : '') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date (Optional)</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                                   value="<?= old('end_date', $announcement['end_date'] ? date('Y-m-d\TH:i', strtotime($announcement['end_date'])) : '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   value="1" <?= old('is_active', $announcement['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="requires_acknowledgment" name="requires_acknowledgment"
                                   value="1" <?= old('requires_acknowledgment', $announcement['requires_acknowledgment']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="requires_acknowledgment">
                                Requires Acknowledgment
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="show_once" name="show_once"
                                   value="1" <?= old('show_once', $announcement['show_once']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="show_once">
                                Show Only Once
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Announcement
                    </button>
                    <a href="/announcements" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Handle target type change
        $('#target_type').on('change', function() {
            const targetType = $(this).val();

            $('#target_departments_div').hide();
            $('#target_designations_div').hide();
            $('#target_employees_div').hide();

            if (targetType === 'department') {
                $('#target_departments_div').show();
            } else if (targetType === 'designation') {
                $('#target_designations_div').show();
            } else if (targetType === 'specific') {
                $('#target_employees_div').show();
            }
        });

        // Trigger on page load
        $('#target_type').trigger('change');
    });
</script>
<?= $this->endSection() ?>
