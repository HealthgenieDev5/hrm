<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<!--begin::Row-->
<div class="row gy-5 g-xl-8 h-100">
    <!--begin::Col-->
    <div class="col-md-12">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-center">
                <h3><?php echo isset($page_content) && !empty($page_content) ? $page_content : "You are not authorised to view this page"; ?></h3>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<?= $this->section('javascript') ?>

<?= $this->endSection() ?>
<?= $this->endSection() ?>