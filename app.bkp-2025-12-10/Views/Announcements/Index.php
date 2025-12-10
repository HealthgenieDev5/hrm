<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="bi bi-megaphone-fill me-2"></i> Manage Announcements
            </h3>
            <a href="/announcements/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Create Announcement
            </a>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="announcementsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Priority</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($announcements)): ?>
                            <?php foreach ($announcements as $announcement): ?>
                                <tr>
                                    <td><?= $announcement['id'] ?></td>
                                    <td><?= esc($announcement['title']) ?></td>
                                    <td>
                                        <?php
                                        $typeClass = [
                                            'info' => 'bg-info',
                                            'warning' => 'bg-warning text-dark',
                                            'success' => 'bg-success',
                                            'danger' => 'bg-danger'
                                        ];
                                        ?>
                                        <span class="badge <?= $typeClass[$announcement['type']] ?? 'bg-secondary' ?>">
                                            <?= ucfirst($announcement['type']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $priorityClass = [
                                            'low' => 'bg-secondary',
                                            'medium' => 'bg-primary',
                                            'high' => 'bg-warning text-dark',
                                            'critical' => 'bg-danger'
                                        ];
                                        ?>
                                        <span class="badge <?= $priorityClass[$announcement['priority']] ?? 'bg-secondary' ?>">
                                            <?= ucfirst($announcement['priority']) ?>
                                        </span>
                                    </td>
                                    <td><?= ucfirst($announcement['target_type']) ?></td>
                                    <td>
                                        <?php if ($announcement['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $announcement['start_date'] ? date('Y-m-d', strtotime($announcement['start_date'])) : '-' ?></td>
                                    <td><?= $announcement['end_date'] ? date('Y-m-d', strtotime($announcement['end_date'])) : '-' ?></td>
                                    <td><?= date('Y-m-d H:i', strtotime($announcement['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/announcements/statistics/<?= $announcement['id'] ?>"
                                               class="btn btn-sm btn-info"
                                               title="View Statistics">
                                                <i class="bi bi-bar-chart"></i>
                                            </a>
                                            <a href="/announcements/edit/<?= $announcement['id'] ?>"
                                               class="btn btn-sm btn-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="/announcements/delete/<?= $announcement['id'] ?>"
                                               class="btn btn-sm btn-danger"
                                               title="Delete"
                                               onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">No announcements found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#announcementsTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 25
        });
    });
</script>
<?= $this->endSection() ?>
