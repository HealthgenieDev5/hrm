<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">
                <i class="bi bi-bar-chart me-2"></i> Announcement Statistics
            </h3>
        </div>
        <div class="card-body">
            <h5><?= esc($announcement['title']) ?></h5>
            <p class="text-muted"><?= esc($announcement['message']) ?></p>

            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h3 class="mb-0"><?= $stats['total_target'] ?></h3>
                            <p class="mb-0">Total Target Employees</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h3 class="mb-0"><?= $stats['total_acknowledged'] ?></h3>
                            <p class="mb-0">Acknowledged</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h3 class="mb-0"><?= $stats['pending'] ?></h3>
                            <p class="mb-0">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h3 class="mb-0"><?= $stats['percentage'] ?>%</h3>
                            <p class="mb-0">Completion Rate</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="progress mt-3" style="height: 30px;">
                <div class="progress-bar bg-success" role="progressbar"
                     style="width: <?= $stats['percentage'] ?>%"
                     aria-valuenow="<?= $stats['percentage'] ?>"
                     aria-valuemin="0" aria-valuemax="100">
                    <?= $stats['percentage'] ?>%
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-check-circle me-2"></i> Acknowledged Employees
                        (<?= count($acknowledgedList) ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" id="acknowledgedTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Acknowledged At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($acknowledgedList)): ?>
                                    <?php foreach ($acknowledgedList as $emp): ?>
                                        <tr>
                                            <td><?= esc($emp['employee_code']) ?></td>
                                            <td><?= esc($emp['employee_name']) ?></td>
                                            <td><?= esc($emp['department_name']) ?></td>
                                            <td><?= esc($emp['designation_name']) ?></td>
                                            <td><?= date('Y-m-d H:i', strtotime($emp['acknowledged_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No acknowledgments yet</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-hourglass-split me-2"></i> Pending Employees
                        (<?= count($pendingList) ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" id="pendingTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pendingList)): ?>
                                    <?php foreach ($pendingList as $emp): ?>
                                        <tr>
                                            <td><?= esc($emp['employee_code']) ?></td>
                                            <td><?= esc($emp['name']) ?></td>
                                            <td><?= esc($emp['department_name']) ?></td>
                                            <td><?= esc($emp['designation_name']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">All employees have acknowledged</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="/announcements" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Announcements
        </a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#acknowledgedTable').DataTable({
            pageLength: 10,
            order: [[4, 'desc']]
        });

        $('#pendingTable').DataTable({
            pageLength: 10,
            order: [[1, 'asc']]
        });
    });
</script>
<?= $this->endSection() ?>
