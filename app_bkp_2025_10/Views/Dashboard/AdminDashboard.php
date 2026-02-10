<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xl-6">
            <!--begin::Mixed Widget 2-->
            <div class="card">
                <!--begin::Header-->
                <div class="card-header">
                    <h3 class="card-title">Some dashboard</h3>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                   
                    <pre><?php print_r($leave_requests); ?></pre>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 2-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
<?= $this->endSection() ?>