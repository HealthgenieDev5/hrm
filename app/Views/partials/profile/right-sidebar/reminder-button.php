<div class="card shadow-sm mb-5">
    <?php // if (in_array(session()->get('current_user')['role'], ['superuser', 'hr']) || in_array(session()->get('current_user')['employee_id'], ['40', '93'])): 
    ?>
    <a href="<?= base_url('backend/notifications/create') ?>" class="btn btn-success btn-lg" title="Create Notification">
        <i class="fa fa-bell fa-lg"></i>
        Create a reminder
    </a>
    <?php // endif; 
    ?>

</div>