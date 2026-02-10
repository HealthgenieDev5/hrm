<?= $this->extend('Templates/exemployee-layout') ?>

<?= $this->section('content') ?>


<!--begin::Row-->
<div class="row gy-5 g-xl-8">


    <!--begin::Col-->
    <div class="col-">

        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-7 d-flex flex-column">
                        <div class="symbol w-100px position-relative">
                            <?php
                            $avatar_url = '';
                            $attachment_json = $ExEmployee['attachment'];
                            if (!empty($attachment_json)) {
                                $attachment = json_decode($attachment_json, true);
                                if (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) {
                                    $avatar_url = $attachment['avatar']['file'];
                                }
                            }
                            if (!empty($avatar_url)) {
                            ?>
                                <img class="w-100 h-auto" src="<?php echo base_url() . $avatar_url; ?>" alt="user" />
                            <?php
                            } else {
                            ?>
                                <img class="w-100 h-auto" src="<?= base_url() ?>/public/assets/media/avatars/blank.png" alt="image">
                            <?php
                            }
                            ?>
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-secondary rounded-circle border border-4 border-white h-20px w-20px"></div>
                        </div>
                        <div class="border border-gray-400 border-dashed rounded py-3 px-4 mt-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="fw-bold fs-6 text-gray-600">Code</div>
                                <div class="fw-bold fs-6 text-gray-900"><?php echo $ExEmployee['internal_employee_id']; ?></div>
                            </div>
                            <!--end::Number-->
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-2">
                            <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">
                                <?php echo trim($ExEmployee['first_name'] . ' ' . $ExEmployee['last_name']); ?>
                            </a>
                            <a href="#">
                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                    <i class="fa-solid fa-badge-check text-primary" style="font-size: 1.37rem;"></i>
                                </span>
                            </a>
                        </div>
                        <div class="d-flex flex-column fw-bold fs-6">
                            <?php
                            if (!empty($ExEmployee['designation_name'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-duotone fa-circle-user text-hover-primary"></i>
                                    </span>
                                    <?php echo $ExEmployee['designation_name']; ?>
                                </a>
                            <?php
                            }
                            if (!empty($ExEmployee['department_name'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-solid fa-building-user text-hover-primary"></i>
                                    </span>
                                    <?php echo $ExEmployee['department_name']; ?>
                                </a>
                            <?php
                            }
                            if (!empty($ExEmployee['company_short_name'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-solid fa-house-building text-hover-primary"></i>
                                    </span>
                                    <?php echo $ExEmployee['company_short_name']; ?>
                                </a>
                            <?php
                            }
                            if (!empty($ExEmployee['desk_location'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-duotone fa-location-dot text-hover-primary"></i>
                                    </span>
                                    <?php echo $ExEmployee['desk_location']; ?>
                                </a>
                            <?php
                            }
                            if (!empty($ExEmployee['work_email'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-duotone fa-envelope text-hover-primary"></i>
                                    </span>
                                    <?php echo $ExEmployee['work_email']; ?>
                                </a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end::Col-->
</div>
<!--end::Row-->



<?= $this->section('javascript') ?>

<script src="<?php echo base_url(); ?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {


    })
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>