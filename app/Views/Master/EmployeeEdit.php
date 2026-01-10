<?= $this->extend('Templates/DashboardLayout') ?>
<?= $this->section('content') ?>

<style>
    .input-group.flatpicker-repeater>input[type=text] {
        border-top-left-radius: 0.475rem !important;
        border-bottom-left-radius: 0.475rem !important;
    }

    .overlay .overlay-layer {
        gap: 10px;
    }

    .jstree-node.custom-node {
        display: flex;
    }

    .jstree-node.custom-node>a.jstree-anchor {
        height: auto;
        display: flex;
    }

    .floating-label {
        font-size: 0.75rem;
        margin: 0 0.5rem !important;
        background: #fff;
        transform: translateY(50%);
        padding: 0px 0.5rem;
        color: #8a8a8a;
    }

    .drawer .card .card-header {
        min-height: 80px;
    }

    .form-control.is-invalid {
        border-color: #f1416c;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23f1416c'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23f1416c' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .form-control.is-invalid:focus {
        border-color: #f1416c;
        box-shadow: 0 0 0 0.25rem rgba(241, 65, 108, 0.25);
    }
</style>
<!--begin::Row-->
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-12 mt-0">

        <form class="post d-flex flex-column-fluid" id="update_employee" method="post" enctype="multipart/form-data" action="<?php #echo base_url('/master/employee/edit/validate');
                                                                                                                                ?>">
            <!--begin::Container-->
            <div class="container-fluid px-0 mx-0">

                <div class="d-flex flex-column flex-lg-row">
                    <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                        <!--begin::Card-->
                        <div class="card mt-8 mb-5 mb-xl-8">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Summary-->
                                <!--begin::User Info-->
                                <div class="d-flex flex-center flex-column py-5">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                        <img src="<?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? base_url() . $attachment['avatar']['file'] : base_url() . '/assets/media/svg/files/blank-image.svg'; ?>" alt="image">
                                    </div>
                                    <!--end::Avatar-->
                                    <!--begin::Name-->
                                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3"><?php echo trim($first_name . " " . $last_name); ?></a>
                                    <!--end::Name-->
                                </div>
                                <!--end::User Info-->
                                <!--end::Summary-->
                                <div class="separator"></div>
                                <!--begin::Details content-->
                                <div id="kt_user_view_details" class="collapse show">
                                    <div class="pb-5 fs-6">
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Employee Code</div>
                                        <div class="text-gray-600"><?= $internal_employee_id ?></div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Company</div>
                                        <div class="text-gray-600"><?= $company_name ?></div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Department</div>
                                        <div class="text-gray-600"><?= $department_name ?></div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Designation</div>
                                        <div class="text-gray-600"><?= $designation_name ?></div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Email</div>
                                        <div class="text-gray-600">
                                            <a href="#" class="text-gray-600 text-hover-primary"><?= $work_email ?></a>
                                        </div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Desk Location</div>
                                        <div class="text-gray-600"><?= $desk_location ?></div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Extension</div>
                                        <div class="text-gray-600"><?= $work_phone_extension_number ?></div>
                                        <!--begin::Details item-->
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        <!--begin::Connected Accounts-->
                        <div class="card mb-5 mb-xl-8">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h3 class="fw-bolder m-0">Important Links</h3>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-2">

                                <!--begin::Notice-->
                                <a id="kt_drawer_example_basic_button" href="#" class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">View Revisions (<?= count($revisions) ?>)</a>
                                <div id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle="#kt_drawer_example_basic_button" data-kt-drawer-close="#kt_drawer_example_basic_close" data-kt-drawer-width="1200px">
                                    <div class="card w-100 rounded-0">
                                        <div class="card-header pe-5 " style="min-height: unset;">
                                            <div class="card-title">
                                                <div class="d-flex justify-content-center flex-column me-3">
                                                    Revisions
                                                </div>
                                            </div>
                                            <div class="card-toolbar">
                                                <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_example_basic_close">
                                                    <span class="svg-icon svg-icon-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body hover-scroll-overlay-y">
                                            <?php
                                            if (!empty($revisions)) {
                                                foreach ($revisions as $revision) {
                                            ?>
                                                    <div class="card mb-3 shadow">
                                                        <div class="card-body">
                                                            <?php
                                                            if (!empty($revision['changes'])) {
                                                            ?>
                                                                <div class="changes_tree">
                                                                    <ul>
                                                                        <li data-jstree='{ "opened" : true }'>
                                                                            <strong>Changes Made at the time of saving this revision</strong>
                                                                            <?php
                                                                            foreach ($revision['changes'] as $key => $changes) {
                                                                                if ($key == 'attachment') {
                                                                            ?>
                                                                                    <ul>
                                                                                        <li data-jstree='{ "opened" : true }'>
                                                                                            <strong class="text-capitalize">
                                                                                                <?= str_replace("_", " ", $key) ?>
                                                                                            </strong>
                                                                                            <?php
                                                                                            if (!empty($changes)) {
                                                                                            ?>
                                                                                                <ul>
                                                                                                    <?php
                                                                                                    foreach ($changes as $l1_key => $l1) {
                                                                                                    ?>
                                                                                                        <li data-jstree='{ "opened" : true }'>
                                                                                                            <strong class="text-capitalize l1">
                                                                                                                <?= str_replace("_", " ", $l1_key) ?>
                                                                                                            </strong>

                                                                                                            <?php
                                                                                                            if (!empty($l1)) {
                                                                                                            ?>
                                                                                                                <ul>
                                                                                                                    <?php
                                                                                                                    foreach ($l1 as $l2_key => $l2) {
                                                                                                                    ?>
                                                                                                                        <li data-jstree='{ "type" : "file" }'>
                                                                                                                            <strong class="text-capitalize l2">
                                                                                                                                <?= str_replace("_", " ", $l2_key) ?>:
                                                                                                                            </strong>

                                                                                                                            <small class="ms-2 badge badge-secondary text-decoration-line-through text-muted text-wrap text-start">&nbsp;&nbsp;
                                                                                                                                <?php if (!empty($l2['old_value'])) {
                                                                                                                                    if (strpos($l2['old_value'], 'uploads/') !== false) {
                                                                                                                                        $url = base_url() . $l2['old_value'];
                                                                                                                                        echo '<a class="text-muted" href="' . $url . '" target="_blank">' . basename($l2['old_value']) . '</a>';
                                                                                                                                    } else {
                                                                                                                                        echo $l2['old_value'];
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                                                                }
                                                                                                                                ?>
                                                                                                                                &nbsp;&nbsp;</small>

                                                                                                                            <small class="ms-2 badge text-wrap" style="color: #626262;">
                                                                                                                                <svg width="25" height="12" viewBox="0 0 25 12" xmlns="http://www.w3.org/2000/svg">
                                                                                                                                    <line x1="1" y1="6" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                                                    <line x1="20" y1="2" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                                                    <line x1="20" y1="10" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                                                </svg>
                                                                                                                            </small>

                                                                                                                            <small class="ms-2 badge badge-success bg-success bg-opacity-10 text-success text-wrap text-start <?= empty($l2['new_value']) ? 'text-decoration-line-through' : '' ?>">
                                                                                                                                &nbsp;&nbsp;
                                                                                                                                <?php if (!empty($l2['new_value'])) {

                                                                                                                                    if (strpos($l2['new_value'], 'uploads/') !== false) {
                                                                                                                                        $url = base_url() . $l2['new_value'];
                                                                                                                                        echo '<a class="text-success" href="' . $url . '" target="_blank">' . basename($l2['new_value']) . '</a>';
                                                                                                                                    } else {
                                                                                                                                        echo $l2['new_value'];
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                                                                }
                                                                                                                                ?>
                                                                                                                                &nbsp;&nbsp;
                                                                                                                            </small>
                                                                                                                        </li>
                                                                                                                    <?php
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                </ul>
                                                                                                            <?php
                                                                                                            }
                                                                                                            ?>
                                                                                                        </li>
                                                                                                    <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </ul>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </li>
                                                                                    </ul>
                                                                                <?php
                                                                                } elseif ($key == 'family_members') {
                                                                                ?>
                                                                                    <ul>
                                                                                        <li data-jstree='{ "type" : "file" }' class="custom-node">
                                                                                            <strong class="text-capitalize">
                                                                                                <?= str_replace("_", " ", $key) ?>:
                                                                                            </strong>
                                                                                            <div class="d-flex aling-items-center">
                                                                                                <?php
                                                                                                if (isset($changes['old_value']) && !empty($changes['old_value'])) {
                                                                                                ?>
                                                                                                    <table class="table border table-sm mb-0 ms-2 text-muted text-decoration-line-through">
                                                                                                        <tr>
                                                                                                            <th class="py-0 px-1 border"><small><strong>&nbsp;&nbsp;Name&nbsp;&nbsp;</strong></small></th>
                                                                                                            <th class="py-0 px-1 border"><small><strong>&nbsp;&nbsp;Relation&nbsp;&nbsp;</strong></small></th>
                                                                                                            <th class="py-0 px-1 border"><small><strong>&nbsp;&nbsp;DOB&nbsp;&nbsp;</strong></small></th>
                                                                                                            <th class="py-0 px-1 border"><small><strong>&nbsp;&nbsp;Age&nbsp;&nbsp;</strong></small></th>
                                                                                                        </tr>
                                                                                                        <?php
                                                                                                        foreach ($changes['old_value'] as $old_value) {
                                                                                                        ?>
                                                                                                            <tr>
                                                                                                                <td class="py-0 px-1 border"><small>&nbsp;&nbsp;<?= @$old_value['member_name'] ?>&nbsp;&nbsp;</small></td>
                                                                                                                <td class="py-0 px-1 border"><small>&nbsp;&nbsp;<?= @$old_value['member_relation'] ?>&nbsp;&nbsp;</small></td>
                                                                                                                <td class="py-0 px-1 border"><small>&nbsp;&nbsp;<?= @$old_value['member_dob'] ?>&nbsp;&nbsp;</small></td>
                                                                                                                <td class="py-0 px-1 border"><small>&nbsp;&nbsp;<?= @$old_value['member_age'] ?>&nbsp;&nbsp;</small></td>
                                                                                                            </tr>
                                                                                                        <?php
                                                                                                        }
                                                                                                        ?>
                                                                                                    </table>
                                                                                                <?php
                                                                                                }
                                                                                                ?>
                                                                                                <small class="ms-2 badge text-wrap" style="color: #626262;">
                                                                                                    <svg width="25" height="12" viewBox="0 0 25 12" xmlns="http://www.w3.org/2000/svg">
                                                                                                        <line x1="1" y1="6" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                        <line x1="20" y1="2" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                        <line x1="20" y1="10" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                    </svg>
                                                                                                </small>
                                                                                                <?php
                                                                                                if (isset($changes['new_value']) && !empty($changes['new_value'])) {
                                                                                                ?>
                                                                                                    <table class="table border border-success table-sm mb-0 ms-2 text-success">
                                                                                                        <tr>
                                                                                                            <th class="py-0 px-1 border border-success"><small><strong>Name</strong></small></th>
                                                                                                            <th class="py-0 px-1 border border-success"><small><strong>Relation</strong></small></th>
                                                                                                            <th class="py-0 px-1 border border-success"><small><strong>DOB</strong></small></th>
                                                                                                            <th class="py-0 px-1 border border-success"><small><strong>Age</strong></small></th>
                                                                                                        </tr>
                                                                                                        <?php
                                                                                                        foreach ($changes['new_value'] as $new_value) {
                                                                                                        ?>
                                                                                                            <tr>
                                                                                                                <td class="py-0 px-1 border border-success"><small><?= @$new_value['member_name'] ?></small></td>
                                                                                                                <td class="py-0 px-1 border border-success"><small><?= @$new_value['member_relation'] ?></small></td>
                                                                                                                <td class="py-0 px-1 border border-success"><small><?= @$new_value['member_dob'] ?></small></td>
                                                                                                                <td class="py-0 px-1 border border-success"><small><?= @$new_value['member_age'] ?></small></td>
                                                                                                            </tr>
                                                                                                        <?php
                                                                                                        }
                                                                                                        ?>
                                                                                                    </table>
                                                                                                <?php
                                                                                                }
                                                                                                ?>
                                                                                            </div>
                                                                                        </li>
                                                                                    </ul>
                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                    <ul>
                                                                                        <li data-jstree='{ "type" : "file" }'>
                                                                                            <strong class="text-capitalize">
                                                                                                <?= str_replace("_", " ", $key) ?>:
                                                                                            </strong>
                                                                                            <small class="ms-2 badge badge-secondary text-decoration-line-through text-muted text-wrap text-start">&nbsp;&nbsp;<?= !empty($changes['old_value']) ? $changes['old_value'] : "&nbsp;&nbsp;&nbsp;&nbsp;" ?>&nbsp;&nbsp;</small>

                                                                                            <small class="ms-2 badge text-wrap" style="color: #626262;">
                                                                                                <svg width="25" height="12" viewBox="0 0 25 12" xmlns="http://www.w3.org/2000/svg">
                                                                                                    <line x1="1" y1="6" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                    <line x1="20" y1="2" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                    <line x1="20" y1="10" x2="24" y2="6" stroke="currentColor" stroke-width="1" />
                                                                                                </svg>
                                                                                            </small>

                                                                                            <small class="ms-2 badge badge-success bg-success bg-opacity-10 text-success text-wrap text-start <?= empty($changes['new_value']) ? 'text-decoration-line-through' : '' ?>">&nbsp;&nbsp;<?= !empty($changes['new_value']) ? $changes['new_value'] : "&nbsp;&nbsp;&nbsp;&nbsp;" ?>&nbsp;&nbsp;</small>
                                                                                        </li>
                                                                                    </ul>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            <?php

                                                            } else {
                                                            ?>
                                                                <small>No Changes were made</small>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="card-footer text-end">
                                                            <?= date('d M, Y h:i A', strtotime($revision['revision_date_time'])) ?> by <?= $revision['revised_by_name'] ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Notice-->

                                <!--begin::Notice-->
                                <?php
                                if (@$salary['basic_salary'] > 0) {
                                ?>
                                    <a target="_blank" href="<?= base_url('/backend/master/employee/appintment-letter/' . $id) ?>" class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">Appointment Letter</a>
                                <?php
                                } elseif (preg_match('/\bintern\b/i', $designation_name)) {
                                ?>
                                    <!-- <span class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">LOA Not Available for Interns</span> -->
                                    <a target="_blank" href="<?= base_url('/backend/master/employee/loa-letter/' . $id) ?>" class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">LOA Letter</a>

                                <?php
                                } else {
                                ?><span class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">Please update salary to download LOA</span><?php
                                                                                                                                                                                    }
                                                                                                                                                                                        ?>

                                <!--end::Notice-->

                                <!--begin::Notice-->
                                <a target="_blank" href="<?= base_url('/backend/master/employee/probation-confirmation-letter/' . $id) ?>" class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">Confirmation Letter</a>
                                <!--end::Notice-->

                                <!--begin::Notice for Termination Letter -->
                                <a id="termination_letter_link" target="_blank" href="<?= base_url('/backend/master/employee/termination-letter/' . $id) ?>" class="notice d-flex bg-light-danger rounded border-danger border border-dashed mb-9 px-6 py-3 d-none">Termination Letter</a>
                                <!--end::Notice for Termination Letter -->

                                <!--begin::Notice-->
                                <?php
                                if (@$salary['loyalty_incentive'] == 'yes') {
                                ?>
                                    <a target="_blank" href="<?= base_url('/backend/master/employee/loyalty-incentive-letter/' . $id) ?>" class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 px-6 py-3">Loyalty Incentive</a>
                                <?php
                                }
                                ?>
                                <!--end::Notice-->

                                <!--begin::Notice-->
                                <?php
                                if (@$salary['non_compete_loan'] == 'yes' && !in_array($company_id, ['1', '3', '5'])) {
                                ?>
                                    <a target="_blank" href="<?= base_url('/backend/master/employee/ncl-letter-gstc-category-a/' . $id) ?>" class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9  px-6 py-3">Non Compete Loan</a>
                                <?php
                                }
                                ?>
                                <!--end::Notice-->

                                <!--begin::Probation_extended-->


                                <a id="probation_extended_letter_link" target="_blank" href="<?= base_url('/backend/master/employee/probation-extended-letter/' . $id) ?>" class="notice d-flex bg-light-danger rounded border-danger border border-dashed mb-9 px-6 py-3 d-none">Probation Extended Letter</a>





                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Connected Accounts-->
                    </div>

                    <div class="flex-lg-row-fluid flex-grow-1 ms-lg-15">
                        <!--begin::Tab Nav-->
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder mb-8">
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#Personal_Details">Personal</a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Contact_Details">Contact</a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Professional_Details">Professional</a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Documents_Upload">Documents</a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#Salary_Structure">Salary</a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 pt-5 pb-0" data-bs-toggle="tab" href="#Overrides">
                                    <span class="text-center" style="line-height: 1;">Overrides
                                        <br>
                                        <small style="font-size: .65em; font-weight: normal; color: #00df00; opacity: 0.8;">and Waiver & deduction</small>
                                    </span>
                                </a>
                            </li>
                            <!--end::Nav item-->
                        </ul>
                        <!--begin::Tab Nav-->

                        <!-- begin::tab panes -->
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="Personal_Details" role="tabpanel">
                                <!--begin::Basic info-->
                                <div class="card shadow-none">
                                    <!--begin::Card header-->
                                    <div class="card-header ">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Personal Details</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body border-bottom">
                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <input type="hidden" id="probation_response" value="<?= @$probation_response ?>">
                                                <label class="form-label form-label-sm required fw-bold fs-6">First Name</label>
                                                <input type="text" id="first_name" name="first_name" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="First name" value="<?php echo @$first_name; ?>" required />
                                                <small class="text-danger error-text" id="first_name_error"><?= isset($validation) ? display_error($validation, 'first_name') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" class="form-control form-control-sm " placeholder="Last name" value="<?php echo @$last_name; ?>" required />
                                                <small class="text-danger error-text" id="last_name_error"><?= isset($validation) ? display_error($validation, 'last_name') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Father's Name</label>
                                                <input type="text" id="fathers_name" class="form-control form-control-sm" name="fathers_name" placeholder="Father's Name" value="<?= set_value('fathers_name', @$fathers_name) ?>" required />
                                                <small class="text-danger error-text" id="fathers_name_error"><?= isset($validation) ? display_error($validation, 'fathers_name') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Gender</label>
                                                <select class="form-control form-control-sm mb-3 mb-lg-0" id="gender" name="gender" data-control="select2" data-placeholder="Select Gender" data-allow-clear="true">
                                                    <option></option>
                                                    <option value="female" <?= edit_set_select('gender', 'female', $gender) ?>>Female</option>
                                                    <option value="male" <?= edit_set_select('gender', 'male', $gender) ?>>Male</option>
                                                    <option value="other" <?= edit_set_select('gender', 'other', $gender) ?>>Other</option>
                                                </select>
                                                <small class="text-danger error-text" id="gender_error"><?= isset($validation) ? display_error($validation, 'gender') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-3">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Marital Status</label>
                                                <select class="form-control form-control-sm mb-3 mb-lg-0" id="marital_status" name="marital_status" data-control="select2" data-placeholder="Select Marital Status" data-allow-clear="true">
                                                    <option></option>
                                                    <option value="married" <?= edit_set_select('marital_status', 'married', $marital_status) ?>>Married</option>
                                                    <option value="unmarried" <?= edit_set_select('marital_status', 'unmarried', $marital_status) ?>>Un-Married</option>
                                                    <option value="divorced" <?= edit_set_select('marital_status', 'divorced', $marital_status) ?>>Divorced</option>
                                                </select>
                                                <small class="text-danger error-text" id="marital_status_error"><?= isset($validation) ? display_error($validation, 'marital_status') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-5 husband-name-wrapper" style="display: <?php echo ($gender == 'female' && $marital_status == 'married') ? 'block' : 'none'; ?>">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Husband's Name</label>
                                                <input type="text" id="husband_name" class="form-control form-control-sm" name="husband_name" placeholder="Husband's Name" value="<?= set_value('husband_name', @$husband_name) ?>" required />
                                                <small class="text-danger error-text" id="husband_name_error"><?= isset($validation) ? display_error($validation, 'husband_name') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-4">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Date Of Anniversary</label>
                                                <div class="input-group input-group-flatpicker" id="date_of_anniversary_picker" data-wrap="true">
                                                    <input type="text" id="date_of_anniversary" class="form-control form-control-sm" name="date_of_anniversary" placeholder="Date Of Anniversary" value="<?= set_value('date_of_anniversary', @$date_of_anniversary) ?>" data-input data-open>
                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="date_of_anniversary_error"><?= isset($validation) ? display_error($validation, 'date_of_anniversary') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-4">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Date Of Birth</label>
                                                <div class="input-group input-group-flatpicker" id="date_of_birth_picker" data-wrap="true">
                                                    <input type="text" id="date_of_birth" class="form-control form-control-sm" name="date_of_birth" placeholder="Date Of Birth" value="<?= set_value('date_of_birth', @$date_of_birth) ?>" data-input data-open>
                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="date_of_birth_error"><?= isset($validation) ? display_error($validation, 'date_of_birth') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->

                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Highest Qualification</label>
                                                <input type="text" id="highest_qualification" class="form-control form-control-sm" name="highest_qualification" placeholder="Highest Qualification" value="<?= set_value('highest_qualification', @$highest_qualification) ?>" />
                                                <small class="text-danger error-text" id="highest_qualification_error"><?= isset($validation) ? display_error($validation, 'highest_qualification') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Total Experience</label>
                                                <input type="text" id="total_experience" class="form-control form-control-sm" name="total_experience" placeholder="Total Experience" value="<?= set_value('total_experience', @$total_experience) ?>" />
                                                <small class="text-danger error-text" id="total_experience_error"><?= isset($validation) ? display_error($validation, 'total_experience') : '' ?></small>
                                            </div>
                                            <!--end::Col-->

                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Last company name</label>
                                                <input type="text" id="last_company_name" class="form-control form-control-sm" name="last_company_name" placeholder="Last company name" value="<?= set_value('last_company_name', @$last_company_name) ?>" />
                                                <small class="text-danger error-text" id="last_company_name_error"><?= isset($validation) ? display_error($validation, 'last_company_name') : '' ?></small>
                                            </div>
                                            <!--end::Col-->

                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Relevant experience</label>
                                                <input type="text" id="relevant_experience" class="form-control form-control-sm" name="relevant_experience" placeholder="Relevant experience" value="<?= set_value('relevant_experience', @$relevant_experience) ?>" />
                                                <small class="text-danger error-text" id="relevant_experience_error"><?= isset($validation) ? display_error($validation, 'relevant_experience') : '' ?></small>
                                            </div>
                                            <!--end::Col-->

                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">College university</label>
                                                <input type="text" id="college_university" class="form-control form-control-sm" name="college_university" placeholder="College university" value="<?= set_value('college_university', @$college_university) ?>" />
                                                <small class="text-danger error-text" id="college_university_error"><?= isset($validation) ? display_error($validation, 'college_university') : '' ?></small>
                                            </div>
                                            <!--end::Col-->

                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Hobbies</label>
                                                <input type="text" id="hobbies" class="form-control form-control-sm" name="hobbies" placeholder="Hobbies" value="<?= set_value('hobbies', @$hobbies) ?>" />
                                                <small class="text-danger error-text" id="hobbies_error"><?= isset($validation) ? display_error($validation, 'hobbies') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>

                                    </div>
                                    <!--end::Card body-->
                                    <!--begin::Card header-->
                                    <div class="card-header ">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Permanent Address</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body border-bottom">
                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Permanent City</label>
                                                <input type="text" id="permanent_city" class="form-control form-control-sm" name="permanent_city" placeholder="Permanent City" value="<?= set_value('permanent_city', @$permanent_city) ?>" />
                                                <small class="text-danger error-text" id="permanent_city_error"><?= isset($validation) ? display_error($validation, 'permanent_city') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Permanent District</label>
                                                <input type="text" id="permanent_district" class="form-control form-control-sm" name="permanent_district" placeholder="Permanent District" value="<?= set_value('permanent_district', @$permanent_district) ?>" />
                                                <small class="text-danger error-text" id="permanent_district_error"><?= isset($validation) ? display_error($validation, 'permanent_district') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Permanent State</label>
                                                <input type="text" id="permanent_state" class="form-control form-control-sm" name="permanent_state" placeholder="Permanent State" value="<?= set_value('permanent_state', @$permanent_state) ?>" />
                                                <small class="text-danger error-text" id="permanent_state_error"><?= isset($validation) ? display_error($validation, 'permanent_state') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Permanent Pincode</label>
                                                <input type="text" id="permanent_pincode" class="form-control form-control-sm" name="permanent_pincode" placeholder="Permanent Pincode" value="<?= set_value('permanent_pincode', @$permanent_pincode) ?>" />
                                                <small class="text-danger error-text" id="permanent_pincode_error"><?= isset($validation) ? display_error($validation, 'permanent_pincode') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Permanent Address</label>
                                                <textarea id="permanent_address" class="form-control form-control-sm" name="permanent_address" placeholder="Permanent Address"><?= set_value('permanent_address', @$permanent_address) ?></textarea>
                                                <small class="text-danger error-text" id="permanent_address_error"><?= isset($validation) ? display_error($validation, 'permanent_address') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card body-->
                                    <!--begin::Card header-->
                                    <div class="card-header ">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Present Address</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body border-bottom">
                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Present City</label>
                                                <input type="text" id="present_city" class="form-control form-control-sm" name="present_city" placeholder="Present City" value="<?= set_value('present_city', @$present_city) ?>" />
                                                <small class="text-danger error-text" id="present_city_error"><?= isset($validation) ? display_error($validation, 'present_city') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Present District</label>
                                                <input type="text" id="present_district" class="form-control form-control-sm" name="present_district" placeholder="Present District" value="<?= set_value('present_district', @$present_district) ?>" />
                                                <small class="text-danger error-text" id="present_district_error"><?= isset($validation) ? display_error($validation, 'present_district') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Present State</label>
                                                <input type="text" id="present_state" class="form-control form-control-sm" name="present_state" placeholder="Present State" value="<?= set_value('present_state', @$present_state) ?>" />
                                                <small class="text-danger error-text" id="present_state_error"><?= isset($validation) ? display_error($validation, 'present_state') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Present Pincode</label>
                                                <input type="text" id="present_pincode" class="form-control form-control-sm" name="present_pincode" placeholder="Present Pincode" value="<?= set_value('present_pincode', @$present_pincode) ?>" />
                                                <small class="text-danger error-text" id="present_pincode_error"><?= isset($validation) ? display_error($validation, 'present_pincode') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Present Address</label>
                                                <textarea id="present_address" class="form-control form-control-sm" name="present_address" placeholder="Present Address"><?= set_value('present_address', @$present_address) ?></textarea>
                                                <small class="text-danger error-text" id="present_address_error"><?= isset($validation) ? display_error($validation, 'present_address') : '' ?></small>
                                            </div>
                                            <!--end::Col-->

                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card body-->
                                    <!--begin::Card header-->
                                    <div class="card-header ">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Family Details</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body border-bottom">

                                        <!--begin::Input group-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <!--begin::Repeater-->
                                                <div id="family_members">

                                                    <!--begin::Form group-->
                                                    <div class="form-group">
                                                        <div data-repeater-list="family_members">
                                                            <div data-repeater-item>
                                                                <div class="form-group row mb-5">
                                                                    <div class="col-md-3">
                                                                        <label class="form-label">Family Member Name</label>
                                                                        <input type="text" class="form-control form-control-sm form-control-solid" name="member_name" placeholder="Family Member Name" />
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="form-label">Relation</label>
                                                                        <select class="form-control form-control-sm form-control-solid" name="member_relation">
                                                                            <option value="">Select Relation</option>
                                                                            <option value="Mother">Mother</option>
                                                                            <option value="Father">Father</option>
                                                                            <option value="Daughter">Daughter</option>
                                                                            <option value="Son">Son</option>
                                                                            <option value="Wife">Wife</option>
                                                                            <option value="Husband">Husband</option>
                                                                            <option value="Sister">Sister</option>
                                                                            <option value="Brother">Brother</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="form-label">DOB</label>
                                                                        <div class="input-group flatpicker-repeater" id="date_of_birth_picker" data-wrap="true">
                                                                            <input type="text" class="form-control form-control-sm" name="member_dob" placeholder="Date Of Birth" data-input data-open>
                                                                            <span class="input-group-text cursor-pointer" data-toggle>
                                                                                <i class="far fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label class="form-label">Age</label>
                                                                        <div class="input-group input-group-sm">
                                                                            <input type="text" class="form-control form-control-sm form-control-age" name="member_age" placeholder="29" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                                                            <span class="input-group-text">Years</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <label class="form-label">&nbsp;</label><br>
                                                                        <div class="d-flex align-items-center justify-content-end">
                                                                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                                                <i class="la la-trash-o"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Form group-->
                                                    <!--begin::Form group-->
                                                    <div class="form-group">
                                                        <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                                            <i class="la la-plus"></i>Add a family member
                                                        </a>
                                                    </div>
                                                    <!--end::Form group-->
                                                </div>
                                                <!--end::Repeater-->
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                                <!--end::Basic info-->
                            </div>

                            <div class="tab-pane fade" id="Contact_Details" role="tabpanel">
                                <!--begin::Basic info-->
                                <div class="card shadow-none">
                                    <!--begin::Card header-->
                                    <div class="card-header ">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Personal Contact Details</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body border-bottom">

                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Personal Email</label>
                                                <input type="email" id="personal_email" class="form-control form-control-sm" name="personal_email" placeholder="Personal Email" value="<?= set_value('personal_email', @$personal_email) ?>" />
                                                <small class="text-danger error-text" id="personal_email_error"><?= isset($validation) ? display_error($validation, 'personal_email') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Personal Mobile</label>
                                                <input type="text" id="personal_mobile" class="form-control form-control-sm" name="personal_mobile" placeholder="Personal Mobile" value="<?= set_value('personal_mobile', @$personal_mobile) ?>" />
                                                <small class="text-danger error-text" id="personal_mobile_error"><?= isset($validation) ? display_error($validation, 'personal_mobile') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Emergency Contact</label>
                                                <input type="text" id="emergency_contact_number" class="form-control form-control-sm" name="emergency_contact_number" placeholder="Emergency Contact" value="<?= set_value('emergency_contact_number', @$emergency_contact_number) ?>" />
                                                <small class="text-danger error-text" id="emergency_contact_number_error"><?= isset($validation) ? display_error($validation, 'emergency_contact_number') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                    </div>

                                    <!--begin::Card header-->
                                    <div class="card-header ">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Official Contact Details</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body border-bottom">

                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Work Email</label>
                                                <input type="email" id="work_email" class="form-control form-control-sm" name="work_email" placeholder="Work Email" value="<?= set_value('work_email', @$work_email) ?>" />
                                                <small class="text-danger error-text" id="work_email_error"><?= isset($validation) ? display_error($validation, 'work_email') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Work Mobile</label>
                                                <input type="text" id="work_mobile" class="form-control form-control-sm" name="work_mobile" placeholder="Work Mobile" value="<?= set_value('work_mobile', @$work_mobile) ?>" />
                                                <small class="text-danger error-text" id="work_mobile_error"><?= isset($validation) ? display_error($validation, 'work_mobile') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>

                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Work Phone Extension Number</label>
                                                <input type="text" id="work_phone_extension_number" class="form-control form-control-sm" name="work_phone_extension_number" placeholder="Work Phone Extension Number" value="<?= set_value('work_phone_extension_number', @$work_phone_extension_number) ?>" />
                                                <small class="text-danger error-text" id="work_phone_extension_number_error"><?= isset($validation) ? display_error($validation, 'work_phone_extension_number') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Work Phone CUG Number</label>
                                                <input type="text" id="work_phone_cug_number" class="form-control form-control-sm" name="work_phone_cug_number" placeholder="Work Phone CUG Number" value="<?= set_value('work_phone_cug_number', @$work_phone_cug_number) ?>" />
                                                <small class="text-danger error-text" id="work_phone_cug_number_error"><?= isset($validation) ? display_error($validation, 'work_phone_cug_number') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>

                                        <div class="row mb-6">
                                            <!--begin::Col-->
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Desk Location</label>
                                                <input type="text" id="desk_location" class="form-control form-control-sm" name="desk_location" placeholder="Desk Location" value="<?= set_value('desk_location', @$desk_location) ?>" />
                                                <small class="text-danger error-text" id="desk_location_error"><?= isset($validation) ? display_error($validation, 'desk_location') : '' ?></small>
                                            </div>
                                            <!--end::Col-->
                                        </div>




                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="Professional_Details" role="tabpanel">
                                <!--begin::Basic info-->
                                <div class="card shadow-none">
                                    <!--begin::Card header-->
                                    <div class="card-header ">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Professional Details</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body border-bottom">

                                        <div class="row mb-6">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Internal Employee ID</label>
                                                <input type="hidden" id="employee_id" name="employee_id" value="<?= $id ?>" />
                                                <small class="text-danger error-text" id="employee_id_error"><?= isset($validation) ? display_error($validation, 'employee_id') : '' ?></small>
                                                <input type="text" id="internal_employee_id" class="form-control form-control-sm" name="internal_employee_id" placeholder="Inernal Employee ID" value="<?= set_value('internal_employee_id', @$internal_employee_id) ?>" />
                                                <small class="text-danger error-text" id="internal_employee_id_error"><?= isset($validation) ? display_error($validation, 'internal_employee_id') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Company</label>
                                                <select class="form-control form-control-sm" id="company_id" name="company_id" data-control="select2" data-placeholder="Select a Company" data-allow-clear="true">
                                                    <option></option>
                                                    <?php
                                                    foreach ($companies as $company) {
                                                    ?>
                                                        <option value="<?php echo $company['id']; ?>" <?= edit_set_select('company_id', $company['id'], $company_id) ?>><?php echo $company['company_name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-danger error-text" id="company_id_error"><?= isset($validation) ? display_error($validation, 'company_id') : '' ?></small>
                                            </div>
                                        </div>

                                        <div class="row mb-6">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Department</label>
                                                <select class="form-control form-control-sm" id="department_id" name="department_id" data-control="select2" data-placeholder="Select a Department" data-allow-clear="true">
                                                    <option></option>
                                                    <?php
                                                    if (isset($departments) && !empty($departments)) {
                                                        foreach ($departments as $department) {
                                                    ?>
                                                            <option value="<?php echo $department['id']; ?>" <?= edit_set_select('department_id', $department['id'], $department_id) ?>><?php echo $department['department_name']; ?> - <?php echo $department['company_short_name']; ?>
                                                            </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-danger error-text" id="department_id_error"><?= isset($validation) ? display_error($validation, 'department_id') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Designation</label>
                                                <select class="form-control form-control-sm" id="designation_id" name="designation_id" data-control="select2" data-placeholder="Select a Designation" data-allow-clear="true">
                                                    <option></option>
                                                    <?php
                                                    if (isset($designations) && !empty($designations)) {
                                                        foreach ($designations as $designation) {
                                                    ?>
                                                            <option value="<?php echo $designation['id']; ?>" <?= edit_set_select('designation_id', $designation['id'], $designation_id) ?>><?php echo $designation['designation_name']; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-danger error-text" id="designation_id_error"><?= isset($validation) ? display_error($validation, 'designation_id') : '' ?></small>
                                            </div>
                                        </div>

                                        <div class="row mb-6">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Reporting Manager</label>
                                                <select class="form-control form-control-sm" id="reporting_manager_id" name="reporting_manager_id" data-control="select2" data-placeholder="Select a Reporting Manager" data-allow-clear="true">
                                                    <option></option>
                                                    <?php
                                                    if (isset($reportingManagers) && !empty($reportingManagers)) {
                                                        foreach ($reportingManagers as $reportingManager) {
                                                    ?>
                                                            <option value="<?php echo $reportingManager['id']; ?>" <?= edit_set_select('reporting_manager_id', $reportingManager['id'], $reporting_manager_id) ?>><?php echo $reportingManager['name']; ?> - <?php echo $reportingManager['department_name']; ?> - <?php echo $reportingManager['company_short_name']; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-danger error-text" id="reporting_manager_id_error"><?= isset($validation) ? display_error($validation, 'reporting_manager_id') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Joining Date</label>
                                                <div class="input-group input-group-flatpicker" id="joining_date_picker" data-wrap="true">
                                                    <input type="text" id="joining_date" class="form-control form-control-sm" name="joining_date" placeholder="Joining Date" value="<?= set_value('joining_date', @$joining_date) ?>" data-input data-open>
                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="joining_date_error"><?= isset($validation) ? display_error($validation, 'joining_date') : '' ?></small>
                                            </div>
                                        </div>

                                        <div class="row mb-6">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Notice Period</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" id="notice_period" class="form-control" name="notice_period" placeholder="Notice Period" value="<?= set_value('notice_period', @$notice_period) ?>" data-inputmask="'mask': '9', 'repeat': 3, 'greedy' : false" oninput="$('#notice_period_error').html('')" />
                                                    <span class="input-group-text">Days</span>
                                                </div>
                                                <small class="text-danger error-text" id="notice_period_error"><?= isset($validation) ? display_error($validation, 'notice_period') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Date of leaving / Retirement</label>
                                                <div class="input-group input-group-flatpicker" id="date_of_leaving_picker" data-wrap="true">
                                                    <input type="text" id="date_of_leaving" class="form-control form-control-sm" name="date_of_leaving" placeholder="Date of leaving" value="<?= set_value('date_of_leaving', @$date_of_leaving) ?>" data-input data-open>
                                                    <span class="input-group-text cursor-pointer" data-toggle>
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="date_of_leaving_error"><?= isset($validation) ? display_error($validation, 'date_of_leaving') : '' ?></small>
                                            </div>
                                        </div>

                                        <div class="row mb-6">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Employment Status</label>
                                                <select class="form-control form-control-sm" id="status" name="status" data-control="select2" data-placeholder="Select Status" data-allow-clear="true">
                                                    <option></option>
                                                    <option value="active" <?= edit_set_select('status', 'active', $status) ?>>Active</option>
                                                    <option value="abscond" <?= edit_set_select('status', 'abscond', $status) ?>>Abscond</option>
                                                    <option value="left" <?= edit_set_select('status', 'left', $status) ?>>Left</option>
                                                    <option value="retired" <?= edit_set_select('status', 'retired', $status) ?>>Retired</option>
                                                    <option value="left in probation" <?= edit_set_select('status', 'left in probation', $status) ?>>Left in Probation</option>
                                                </select>
                                                <small class="text-danger error-text" id="status_error"><?= isset($validation) ? display_error($validation, 'status') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Probation Status</label>
                                                <p class="d-none" id="old_probation"><?= $probation ?></p>
                                                <select class="form-control form-control-sm" id="probation" name="probation" data-control="select2" data-placeholder="Select Probation" data-allow-clear="true">
                                                    <option></option>
                                                    <option value="45 Days Probation" <?= edit_set_select('probation', '45 Days Probation', $probation) ?>>45 Days Probation</option>
                                                    <option value="90 Days Probation" <?= edit_set_select('probation', '90 Days Probation', $probation) ?>>90 Days Probation</option>
                                                    <option value="confirmed" <?= edit_set_select('probation', 'confirmed', $probation) ?>>Confirmed</option>
                                                </select>
                                                <small class="text-danger error-text" id="probation_error"><?= isset($validation) ? display_error($validation, 'probation') : '' ?></small>
                                            </div>
                                        </div>

                                        <div class="row mb-6">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Role</label>
                                                <select class="form-control form-control-sm" id="role" name="role" data-control="select2" data-placeholder="Select a Role" data-allow-clear="true">
                                                    <option></option>
                                                    <?php
                                                    if (isset($roles) && !empty($roles)) {
                                                        foreach ($roles as $role_row) {
                                                    ?>
                                                            <option
                                                                <?php
                                                                if ($role_row['role_name'] == 'superuser' && session()->get('current_user')['employee_id'] != 40) {
                                                                    echo 'disabled';
                                                                }
                                                                ?>
                                                                value="<?php echo $role_row['role_name']; ?>"
                                                                <?= edit_set_select('role', $role_row['role_name'], $role) ?>>
                                                                <?php echo ucfirst($role_row['role_name']); ?>
                                                            </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-danger error-text" id="role_error"><?= isset($validation) ? display_error($validation, 'role') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Shift</label>
                                                <select class="form-control form-control-sm" id="shift_id" name="shift_id" data-control="select2" data-placeholder="Select a Shift" data-allow-clear="true">
                                                    <option></option>
                                                    <?php
                                                    if (isset($shifts) && !empty($shifts)) {
                                                        foreach ($shifts as $shift_row) {
                                                    ?>
                                                            <option value="<?php echo $shift_row['id']; ?>" <?= edit_set_select('shift_id', $shift_row['id'], $shift_id) ?>><?php echo $shift_row['shift_name']; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-danger error-text" id="shift_id_error"><?= isset($validation) ? display_error($validation, 'shift_id') : '' ?></small>
                                            </div>
                                        </div>

                                        <div class="row mb-6">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Machine</label>
                                                <select class="form-control form-control-sm" id="machine" name="machine" data-control="select2" data-placeholder="Select a Machine" data-allow-clear="true">
                                                    <option></option>
                                                    <option value="del" <?= edit_set_select('machine', 'del', $machine) ?>>Delhi</option>
                                                    <option value="ggn" <?= edit_set_select('machine', 'ggn', $machine) ?>>Gurugram</option>
                                                    <option value="hn" <?= edit_set_select('machine', 'hn', $machine) ?>>Hueuer Noida</option>
                                                    <option value="skbd" <?= edit_set_select('machine', 'skbd', $machine) ?>>Sikandrabad</option>
                                                </select>
                                                <small class="text-danger error-text" id="machine_error"><?= isset($validation) ? display_error($validation, 'machine') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-sm required fw-bold fs-6">Min Wages Category</label>
                                                <select class="form-control form-control-sm" id="min_wages_category" name="min_wages_category" data-control="select2" data-placeholder="Select an option" data-allow-clear="true">
                                                    <option></option>
                                                    <?php
                                                    if (isset($MinWagesCategories) && !empty($MinWagesCategories)) {
                                                        foreach ($MinWagesCategories as $MinWagesCategory) {
                                                    ?>
                                                            <option
                                                                value="<?php echo $MinWagesCategory['id']; ?>"
                                                                <?= edit_set_select('min_wages_category', $MinWagesCategory['id'], $min_wages_category) ?>>
                                                                <?php echo $MinWagesCategory['minimum_wages_category_name']; ?> (<?php echo $MinWagesCategory['minimum_wages_category_state']; ?>)
                                                            </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-danger error-text" id="min_wages_category_error"><?= isset($validation) ? display_error($validation, 'min_wages_category') : '' ?></small>
                                            </div>
                                        </div>

                                    </div>
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Allowed Leaves</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body border-bottom">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center" style="gap:1rem">
                                                    <label for="cl_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer;">
                                                        <input type="checkbox" class="form-check" name="cl_allowed" id="cl_allowed" value="yes" <?php echo $cl_allowed == 'yes' ? 'checked' : ''; ?> />
                                                        <span>Allow CL</span>
                                                    </label>
                                                    <label for="el_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                        <input type="checkbox" class="form-check" name="el_allowed" id="el_allowed" value="yes" <?php echo $el_allowed == 'yes' ? 'checked' : ''; ?> />
                                                        <span>Allow EL</span>
                                                    </label>
                                                    <label for="co_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                        <input type="checkbox" class="form-check" name="co_allowed" id="co_allowed" value="yes" <?php echo $co_allowed == 'yes' ? 'checked' : ''; ?> />
                                                        <span>Allow Comp Off</span>
                                                    </label>
                                                    <label for="sl_allowed" class="badge badge-primary d-flex align-items-center py-1" style="gap:0.5rem; cursor: pointer">
                                                        <input type="checkbox" class="form-check" name="sl_allowed" id="sl_allowed" value="yes" <?php echo $sl_allowed == 'yes' ? 'checked' : ''; ?> />
                                                        <span>Allow Sick Leave</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="Documents_Upload" role="tabpanel">
                                <!--begin::Basic info-->
                                <div class="card shadow-none">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h3 class="fw-bolder">Documents Upload</h3>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-6 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-7 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="avatar_number">Photo</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <div
                                                                id="avatar_attachment_select"
                                                                class="image-input image-input-outline <?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div
                                                                    class="image-input-wrapper w-75px h-75px"
                                                                    <?php
                                                                    if (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) {
                                                                        if (pathinfo($attachment['avatar']['file'], PATHINFO_EXTENSION) == 'pdf') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"';
                                                                        } else {
                                                                            echo 'style="background-image: url(\'' . base_url() . $attachment['avatar']['file'] . '\')"';
                                                                        }
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?>>
                                                                    <a href="#" class="<?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" data-bs-target="#avatar_attachment_lightbox" data-bs-toggle="modal">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                    </a>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="avatar_attachment" name="avatar_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                    <input type="hidden" name="avatar_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="avatar_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="avatar_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['avatar']['file']) && !empty($attachment['avatar']['file'])) ? base_url() . $attachment['avatar']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="avatar_attachment_error"><?= isset($validation) ? display_error($validation, 'avatar_attachment') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-7 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="pan_card_number">Pan Card</label>
                                                            <input type="text" id="pan_card_number" class="form-control form-control-sm" name="pan_card_number" placeholder="Pan Card Number" value="<?= set_value('pan_card_number', @$attachment['pan']['number']) ?>" />
                                                            <small class="text-danger error-text" id="pan_card_number_error"><?= isset($validation) ? display_error($validation, 'pan_card_number') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <div id="pan_card_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-75px h-75px" <?php echo (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) ? (pathinfo($attachment['pan']['file'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['pan']['file'] . '\')"' : ''; ?>>
                                                                    <a href="#" class="<?php echo (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" data-bs-target="#pan_card_attachment_lightbox" data-bs-toggle="modal">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                    </a>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="pan_card_attachment" name="pan_card_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                    <input type="hidden" name="pan_card_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="pan_card_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="pan_card_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['pan']['file']) && !empty($attachment['pan']['file'])) ? base_url() . $attachment['pan']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="pan_card_attachment_error"><?= isset($validation) ? display_error($validation, 'pan_card_attachment') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-7 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="bank_name">Bank Name</label>
                                                            <input type="text" id="bank_name" class="form-control form-control-sm" name="bank_name" placeholder="Bank Name" value="<?= set_value('bank_name', @$attachment['bank_account']['name']) ?>" />
                                                            <small class="text-danger error-text" id="bank_name_error"><?= isset($validation) ? display_error($validation, 'bank_name') : '' ?></small>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="bank_account_number">Bank Account</label>
                                                            <input type="text" id="bank_account_number" class="form-control form-control-sm" name="bank_account_number" placeholder="Bank Account Number" value="<?= set_value('bank_account_number', @$attachment['bank_account']['number']) ?>" />
                                                            <small class="text-danger error-text" id="bank_account_number_error"><?= isset($validation) ? display_error($validation, 'bank_account_number') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <div id="bank_account_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-75px h-75px" <?php echo (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) ? (pathinfo($attachment['bank_account']['file'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['bank_account']['file'] . '\')"' : ''; ?>>
                                                                    <a href="#" class="<?php echo (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" data-bs-target="#bank_account_attachment_lightbox" data-bs-toggle="modal">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                    </a>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="bank_account_attachment" name="bank_account_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                    <input type="hidden" name="bank_account_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="bank_account_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="bank_account_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['bank_account']['file']) && !empty($attachment['bank_account']['file'])) ? base_url() . $attachment['bank_account']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="bank_account_attachment_error"><?= isset($validation) ? display_error($validation, 'bank_account_attachment') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="row me-md-n1 pt-5 mb-3 bg-light rounded">
                                                    <div class="col-md-7 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="passport_number">Passport</label>
                                                            <input type="text" id="passport_number" class="form-control form-control-sm" name="passport_number" placeholder="Passport Number" value="<?= set_value('passport_number', @$attachment['passport']['number']) ?>" />
                                                            <small class="text-danger error-text" id="passport_number_error"><?= isset($validation) ? display_error($validation, 'passport_number') : '' ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <div id="passport_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-75px h-75px" <?php echo (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) ? (pathinfo($attachment['passport']['file'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['passport']['file'] . '\')"' : ''; ?>>
                                                                    <a href="#" class="<?php echo (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" data-bs-target="#passport_attachment_lightbox" data-bs-toggle="modal">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                    </a>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="passport_attachment" name="passport_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                    <input type="hidden" name="passport_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="passport_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="passport_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['passport']['file']) && !empty($attachment['passport']['file'])) ? base_url() . $attachment['passport']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="passport_attachment_error"><?= isset($validation) ? display_error($validation, 'passport_attachment') : '' ?></small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="row me-md-n1 pt-5 bg-light rounded">
                                                            <div class="col-md-7 d-flex flex-column justify-content-center">
                                                                <div class="form-group mb-3">
                                                                    <label class="mb-3" for="uan_number">UAN</label>
                                                                    <input type="text" id="uan_number" class="form-control form-control-sm" name="uan_number" placeholder="UAN Number" value="<?= set_value('uan_number', @$attachment['uan']['number']) ?>" />
                                                                    <small class="text-danger error-text" id="uan_number_error"><?= isset($validation) ? display_error($validation, 'uan_number') : '' ?></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group mb-3" style="width: max-content;">
                                                                    <div id="uan_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['uan']['file']) && !empty($attachment['uan']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                        <div class="image-input-wrapper w-75px h-75px" <?php echo (isset($attachment['uan']['file']) && !empty($attachment['uan']['file'])) ? (pathinfo($attachment['uan']['file'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['uan']['file'] . '\')"' : ''; ?>>
                                                                            <a href="#" class="<?php echo (isset($attachment['uan']['file']) && !empty($attachment['uan']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" data-bs-target="#uan_attachment_lightbox" data-bs-toggle="modal">
                                                                                <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                            </a>
                                                                        </div>
                                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                                            <input type="file" id="uan_attachment" name="uan_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                                            <input type="hidden" name="uan_attachment_remove" />
                                                                        </label>
                                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                            <i class="bi bi-x fs-2"></i>
                                                                        </span>
                                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                            <i class="bi bi-x fs-2"></i>
                                                                        </span>
                                                                        <div class="modal fade" id="uan_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body" style="min-height: 70vh;">
                                                                                        <iframe id="uan_attachment_lightbox_iframe" class="loaded_content" width="100%"  src="<?php #echo (isset($attachment['uan']['file']) && !empty($attachment['uan']['file'])) ? base_url().$attachment['uan']['file'] : '';
                                                                                                                                                                                ?>"></iframe>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <small class="text-danger error-text" id="uan_attachment_error"><?= isset($validation) ? display_error($validation, 'uan_attachment') : '' ?></small>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                            </div>

                                            <div class="col-md-12 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="adhar_card_number">Adhar Card</label>
                                                            <input type="text" id="adhar_card_number" class="form-control form-control-sm" name="adhar_card_number" placeholder="Adhar Card Number" value="<?= set_value('adhar_card_number', @$attachment['adhar']['number']) ?>" />
                                                            <small class="text-danger error-text" id="adhar_card_number_error"><?= isset($validation) ? display_error($validation, 'adhar_card_number') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <label class="mb-3">Front</label><br>
                                                            <div id="adhar_card_attachment_front_select" class="image-input image-input-outline <?php echo (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-75px h-75px" <?php echo (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) ? (pathinfo($attachment['adhar']['front'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['adhar']['front'] . '\')"' : ''; ?>>
                                                                    <a href="#" class="<?php echo (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" data-bs-target="#adhar_card_attachment_front_lightbox" data-bs-toggle="modal">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                    </a>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="adhar_card_attachment_front" name="adhar_card_attachment_front" accept=".png, .jpg, .jpeg, .pdf" />
                                                                    <input type="hidden" name="adhar_card_attachment_front_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="adhar_card_attachment_front_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="adhar_card_attachment_front_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['adhar']['front']) && !empty($attachment['adhar']['front'])) ? base_url() . $attachment['adhar']['front'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="adhar_card_attachment_front_error"><?= isset($validation) ? display_error($validation, 'adhar_card_attachment_front') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3" style="width: max-content;">
                                                            <label class="mb-3">Back</label><br>
                                                            <div id="adhar_card_attachment_back_select" class="image-input image-input-outline <?php echo (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-75px h-75px" <?php echo (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) ? (pathinfo($attachment['adhar']['back'], PATHINFO_EXTENSION) == 'pdf') ? 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"' : 'style="background-image: url(\'' . base_url() . $attachment['adhar']['back'] . '\')"' : ''; ?>>
                                                                    <a href="#" class="<?php echo (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button" data-bs-target="#adhar_card_attachment_back_lightbox" data-bs-toggle="modal">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                                    </a>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="adhar_card_attachment_back" name="adhar_card_attachment_back" accept=".png, .jpg, .jpeg, .pdf" />
                                                                    <input type="hidden" name="adhar_card_attachment_back_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="adhar_card_attachment_back_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="adhar_card_attachment_back_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['adhar']['back']) && !empty($attachment['adhar']['back'])) ? base_url() . $attachment['adhar']['back'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="adhar_card_attachment_back_error"><?= isset($validation) ? display_error($validation, 'adhar_card_attachment_back') : '' ?></small>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="kye_documents_remarks">K. Y. E. Documents</label>
                                                            <br><small style="font-size: 0.7rem">Know your employee</small><br><small style="font-size: 0.7rem">or Onboarding Document (Appointment letter, Offer letter etc)</small>
                                                            <textarea id="kye_documents_remarks" class="form-control form-control-sm" name="kye_documents_remarks" placeholder="Additional Information"><?= set_value('kye_documents_remarks', @$attachment['kye_documents']['remarks']) ?></textarea>
                                                            <small class="text-danger error-text" id="kye_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'kye_documents_remarks') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" style="width: max-content;">

                                                            <div id="kye_documents_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['kye_documents']['file']) && !empty($attachment['kye_documents']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">


                                                                <div class="image-input-wrapper w-150px h-150px"
                                                                    <?php
                                                                    if (isset($attachment['kye_documents']['file']) && !empty($attachment['kye_documents']['file'])) {
                                                                        if (pathinfo($attachment['kye_documents']['file'], PATHINFO_EXTENSION) == 'pdf') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"';
                                                                        } elseif (pathinfo($attachment['kye_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/zip-file-icon.svg' . ')"';
                                                                        } else {
                                                                            echo 'style="background-image: url(\'' . base_url() . $attachment['kye_documents']['file'] . '\')"';
                                                                        }
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?>>

                                                                    <div class="<?php echo (isset($attachment['kye_documents']['file']) && !empty($attachment['kye_documents']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-eye-fill text-white fs-2x cursor-pointer <?php if (@pathinfo($attachment['kye_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                                                                                            echo 'd-none';
                                                                                                                                        } else {
                                                                                                                                            echo 'd-block';
                                                                                                                                        } ?>" data-bs-target="#kye_documents_attachment_lightbox" data-bs-toggle="modal"></i>
                                                                            <a href="<?php echo base_url() . @$attachment['kye_documents']['file']; ?>" target="_blank"><i class="bi bi-download text-white fs-2x"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="kye_documents_attachment" name="kye_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                    <input type="hidden" name="kye_documents_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="kye_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="kye_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['kye_documents']['file']) && !empty($attachment['kye_documents']['file']) && pathinfo($attachment['kye_documents']['file'], PATHINFO_EXTENSION) !== 'zip') ? base_url() . $attachment['kye_documents']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="kye_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'kye_documents_attachment') : '' ?></small>
                                                        </div>
                                                        <small class="text-muted" style="font-size:0.75rem">
                                                            JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                            No Preview will be available for zip files
                                                        </small>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="family_details_remarks">Family Details</label>
                                                            <textarea id="family_details_remarks" class="form-control form-control-sm" name="family_details_remarks" placeholder="Additional Information"><?= set_value('family_details_remarks', @$attachment['family_details']['remarks']) ?></textarea>
                                                            <small class="text-danger error-text" id="family_details_remarks_error"><?= isset($validation) ? display_error($validation, 'family_details_remarks') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" style="width: max-content;">

                                                            <div id="family_details_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['family_details']['file']) && !empty($attachment['family_details']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">


                                                                <div class="image-input-wrapper w-150px h-150px"
                                                                    <?php
                                                                    if (isset($attachment['family_details']['file']) && !empty($attachment['family_details']['file'])) {
                                                                        if (pathinfo($attachment['family_details']['file'], PATHINFO_EXTENSION) == 'pdf') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"';
                                                                        } elseif (pathinfo($attachment['family_details']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/zip-file-icon.svg' . ')"';
                                                                        } else {
                                                                            echo 'style="background-image: url(\'' . base_url() . $attachment['family_details']['file'] . '\')"';
                                                                        }
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?>>

                                                                    <div class="<?php echo (isset($attachment['family_details']['file']) && !empty($attachment['family_details']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-eye-fill text-white fs-2x cursor-pointer <?php if (@pathinfo($attachment['family_details']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                                                                                            echo 'd-none';
                                                                                                                                        } else {
                                                                                                                                            echo 'd-block';
                                                                                                                                        } ?>" data-bs-target="#family_details_attachment_lightbox" data-bs-toggle="modal"></i>
                                                                            <a href="<?php echo base_url() . @$attachment['family_details']['file']; ?>" target="_blank"><i class="bi bi-download text-white fs-2x"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="family_details_attachment" name="family_details_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                    <input type="hidden" name="family_details_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="family_details_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="family_details_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['family_details']['file']) && !empty($attachment['family_details']['file']) && pathinfo($attachment['family_details']['file'], PATHINFO_EXTENSION) !== 'zip') ? base_url() . $attachment['family_details']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="family_details_attachment_error"><?= isset($validation) ? display_error($validation, 'family_details_attachment') : '' ?></small>
                                                        </div>
                                                        <small class="text-muted" style="font-size:0.75rem">
                                                            JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                            No Preview will be available for zip files
                                                        </small>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="loan_documents_remarks">Loan Documents</label>
                                                            <textarea id="loan_documents_remarks" class="form-control form-control-sm" name="loan_documents_remarks" placeholder="Additional Information"><?= set_value('loan_documents_remarks', @$attachment['loan_documents']['remarks']) ?></textarea>
                                                            <small class="text-danger error-text" id="loan_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'loan_documents_remarks') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" style="width: max-content;">

                                                            <div id="loan_documents_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['loan_documents']['file']) && !empty($attachment['loan_documents']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">

                                                                <div class="image-input-wrapper w-150px h-150px"
                                                                    <?php
                                                                    if (isset($attachment['loan_documents']['file']) && !empty($attachment['loan_documents']['file'])) {
                                                                        if (pathinfo($attachment['loan_documents']['file'], PATHINFO_EXTENSION) == 'pdf') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"';
                                                                        } elseif (pathinfo($attachment['loan_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/zip-file-icon.svg' . ')"';
                                                                        } else {
                                                                            echo 'style="background-image: url(\'' . base_url() . $attachment['loan_documents']['file'] . '\')"';
                                                                        }
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?>>

                                                                    <div class="<?php echo (isset($attachment['loan_documents']['file']) && !empty($attachment['loan_documents']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-eye-fill text-white fs-2x cursor-pointer <?php if (pathinfo(@$attachment['loan_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                                                                                            echo 'd-none';
                                                                                                                                        } else {
                                                                                                                                            echo 'd-block';
                                                                                                                                        } ?>" data-bs-target="#loan_documents_attachment_lightbox" data-bs-toggle="modal"></i>
                                                                            <a href="<?php echo base_url() . @$attachment['loan_documents']['file']; ?>" target="_blank"><i class="bi bi-download text-white fs-2x"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="loan_documents_attachment" name="loan_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                    <input type="hidden" name="loan_documents_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="loan_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="loan_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['loan_documents']['file']) && !empty($attachment['loan_documents']['file']) && pathinfo($attachment['loan_documents']['file'], PATHINFO_EXTENSION) !== 'zip') ? base_url() . $attachment['loan_documents']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="loan_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'loan_documents_attachment') : '' ?></small>
                                                        </div>
                                                        <small class="text-muted" style="font-size:0.75rem">
                                                            JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                            No Preview will be available for zip files
                                                        </small>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="educational_documents_remarks">Educational documents</label>
                                                            <textarea id="educational_documents_remarks" class="form-control form-control-sm" name="educational_documents_remarks" placeholder="Additional Information"><?= set_value('educational_documents_remarks', @$attachment['educational_documents']['remarks']) ?></textarea>
                                                            <small class="text-danger error-text" id="educational_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'educational_documents_remarks') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" style="width: max-content;">

                                                            <div id="educational_documents_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['educational_documents']['file']) && !empty($attachment['educational_documents']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">


                                                                <div class="image-input-wrapper w-150px h-150px"
                                                                    <?php
                                                                    if (isset($attachment['educational_documents']['file']) && !empty($attachment['educational_documents']['file'])) {
                                                                        if (pathinfo($attachment['educational_documents']['file'], PATHINFO_EXTENSION) == 'pdf') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"';
                                                                        } elseif (pathinfo($attachment['educational_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/zip-file-icon.svg' . ')"';
                                                                        } else {
                                                                            echo 'style="background-image: url(\'' . base_url() . $attachment['educational_documents']['file'] . '\')"';
                                                                        }
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?>>

                                                                    <div class="<?php echo (isset($attachment['educational_documents']['file']) && !empty($attachment['educational_documents']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-eye-fill text-white fs-2x cursor-pointer <?php if (@pathinfo($attachment['educational_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                                                                                            echo 'd-none';
                                                                                                                                        } else {
                                                                                                                                            echo 'd-block';
                                                                                                                                        } ?>" data-bs-target="#educational_documents_attachment_lightbox" data-bs-toggle="modal"></i>
                                                                            <a href="<?php echo base_url() . @$attachment['educational_documents']['file']; ?>" target="_blank"><i class="bi bi-download text-white fs-2x"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="educational_documents_attachment" name="educational_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                    <input type="hidden" name="educational_documents_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="educational_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="educational_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['educational_documents']['file']) && !empty($attachment['educational_documents']['file']) && pathinfo($attachment['educational_documents']['file'], PATHINFO_EXTENSION) !== 'zip') ? base_url() . $attachment['educational_documents']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="educational_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'educational_documents_attachment') : '' ?></small>
                                                        </div>
                                                        <small class="text-muted" style="font-size:0.75rem">
                                                            JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                            No Preview will be available for zip files
                                                        </small>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="relieving_documents_remarks">Relieving documents</label>
                                                            <textarea id="relieving_documents_remarks" class="form-control form-control-sm" name="relieving_documents_remarks" placeholder="Additional Information"><?= set_value('relieving_documents_remarks', @$attachment['relieving_documents']['remarks']) ?></textarea>
                                                            <small class="text-danger error-text" id="relieving_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'relieving_documents_remarks') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" style="width: max-content;">

                                                            <div id="relieving_documents_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['relieving_documents']['file']) && !empty($attachment['relieving_documents']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">

                                                                <div class="image-input-wrapper w-150px h-150px"
                                                                    <?php
                                                                    if (isset($attachment['relieving_documents']['file']) && !empty($attachment['relieving_documents']['file'])) {
                                                                        if (pathinfo($attachment['relieving_documents']['file'], PATHINFO_EXTENSION) == 'pdf') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"';
                                                                        } elseif (pathinfo($attachment['relieving_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/zip-file-icon.svg' . ')"';
                                                                        } else {
                                                                            echo 'style="background-image: url(\'' . base_url() . $attachment['relieving_documents']['file'] . '\')"';
                                                                        }
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?>>
                                                                    <div class="<?php echo (isset($attachment['relieving_documents']['file']) && !empty($attachment['relieving_documents']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-eye-fill text-white fs-2x cursor-pointer <?php if (@pathinfo($attachment['relieving_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                                                                                            echo 'd-none';
                                                                                                                                        } else {
                                                                                                                                            echo 'd-block';
                                                                                                                                        } ?>" data-bs-target="#relieving_documents_attachment_lightbox" data-bs-toggle="modal"></i>
                                                                            <a href="<?php echo base_url() . @$attachment['relieving_documents']['file']; ?>" target="_blank"><i class="bi bi-download text-white fs-2x"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="relieving_documents_attachment" name="relieving_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                    <input type="hidden" name="relieving_documents_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="relieving_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="relieving_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['relieving_documents']['file']) && !empty($attachment['relieving_documents']['file']) && pathinfo($attachment['relieving_documents']['file'], PATHINFO_EXTENSION) !== 'zip') ? base_url() . $attachment['relieving_documents']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="relieving_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'relieving_documents_attachment') : '' ?></small>
                                                        </div>
                                                        <small class="text-muted" style="font-size:0.75rem">
                                                            JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                            No Preview will be available for zip files
                                                        </small>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4">
                                                <div class="row me-md-n1 pt-5 bg-light rounded">
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="misc_documents_remarks">Misc documents</label>
                                                            <textarea id="misc_documents_remarks" class="form-control form-control-sm" name="misc_documents_remarks" placeholder="Additional Information"><?= set_value('misc_documents_remarks', @$attachment['misc_documents']['remarks']) ?></textarea>
                                                            <small class="text-danger error-text" id="misc_documents_remarks_error"><?= isset($validation) ? display_error($validation, 'misc_documents_remarks') : '' ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" style="width: max-content;">

                                                            <div id="misc_documents_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['misc_documents']['file']) && !empty($attachment['misc_documents']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-150px h-150px"
                                                                    <?php
                                                                    if (isset($attachment['misc_documents']['file']) && !empty($attachment['misc_documents']['file'])) {
                                                                        if (pathinfo($attachment['misc_documents']['file'], PATHINFO_EXTENSION) == 'pdf') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"';
                                                                        } elseif (pathinfo($attachment['misc_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/zip-file-icon.svg' . ')"';
                                                                        } else {
                                                                            echo 'style="background-image: url(\'' . base_url() . $attachment['misc_documents']['file'] . '\')"';
                                                                        }
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?>>
                                                                    <div class="<?php echo (isset($attachment['misc_documents']['file']) && !empty($attachment['misc_documents']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-eye-fill text-white fs-2x cursor-pointer <?php if (@pathinfo($attachment['misc_documents']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                                                                                            echo 'd-none';
                                                                                                                                        } else {
                                                                                                                                            echo 'd-block';
                                                                                                                                        } ?>" data-bs-target="#misc_documents_attachment_lightbox" data-bs-toggle="modal"></i>
                                                                            <a href="<?php echo base_url() . @$attachment['misc_documents']['file']; ?>" target="_blank"><i class="bi bi-download text-white fs-2x"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="misc_documents_attachment" name="misc_documents_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                    <input type="hidden" name="misc_documents_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="misc_documents_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="misc_documents_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['misc_documents']['file']) && !empty($attachment['misc_documents']['file']) && pathinfo($attachment['misc_documents']['file'], PATHINFO_EXTENSION) !== 'zip') ? base_url() . $attachment['misc_documents']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="misc_documents_attachment_error"><?= isset($validation) ? display_error($validation, 'misc_documents_attachment') : '' ?></small>
                                                        </div>
                                                        <small class="text-muted" style="font-size:0.75rem">
                                                            JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                            No Preview will be available for zip files
                                                        </small>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Additional Documents Section (Repeater) -->
                                            <div class="col-md-12 mb-4">
                                                <div class="separator separator-dashed my-6"></div>

                                                <div class="d-flex justify-content-between align-items-center mb-4">
                                                    <h4 class="fw-bold text-gray-800">Additional Documents</h4>
                                                    <!-- <span class="badge badge-light-info">Multiple files allowed</span> -->
                                                </div>

                                                <div id="existing_attachments_container" class="mb-5">
                                                    <?php if (!empty($employee_attachments) && is_array($employee_attachments)): ?>
                                                        <div class="row g-5">
                                                            <?php foreach ($employee_attachments as $attachment_item):
                                                                $extension = strtolower($attachment_item['file_extension'] ?? '');
                                                                $file_path = $attachment_item['file_path'];
                                                                if ($extension == 'pdf') {
                                                                    $bg_image = base_url() . '/assets/media/svg/files/pdf.svg';
                                                                } elseif (in_array($extension, ['zip', 'rar'])) {
                                                                    $bg_image = base_url() . '/assets/media/svg/files/zip-file-icon.svg';
                                                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                                                    $bg_image = base_url() . '/assets/media/svg/files/doc.svg';
                                                                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                                                    $bg_image = base_url() . '/assets/media/svg/files/csv.svg';
                                                                } elseif (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                                                                    $bg_image = base_url() . $file_path;
                                                                } else {
                                                                    $bg_image = base_url() . '/assets/media/svg/files/blank-image.svg';
                                                                }
                                                            ?>
                                                                <div class="col-md-4 col-lg-3" data-attachment-id="<?= $attachment_item['id'] ?>">
                                                                    <div class="card card-bordered shadow-sm h-100">
                                                                        <div class="card-body p-5">
                                                                            <div class="form-group text-center mb-0">
                                                                                <div class="">
                                                                                    <h6 class="fw-bold text-gray-800 mb-0" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                                                        <?= esc($attachment_item['title']) ?>
                                                                                    </h6>
                                                                                </div>

                                                                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(<?= base_url() ?>assets/media/svg/files/blank-image.svg)">
                                                                                    <div class="image-input-wrapper w-150px h-150px" style="background-image: url('<?= $bg_image ?>')">
                                                                                        <div class="w-100 h-100 overlay preview-button">
                                                                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                                                <i class="bi bi-eye-fill text-white fs-2x cursor-pointer <?= in_array($extension, ['zip', 'rar']) ? 'd-none' : '' ?>"
                                                                                                    data-bs-target="#attachment_lightbox_<?= $attachment_item['id'] ?>"
                                                                                                    data-bs-toggle="modal"></i>
                                                                                                <a href="<?= base_url($file_path) ?>" target="_blank">
                                                                                                    <i class="bi bi-download text-white fs-2x"></i>
                                                                                                </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow delete-attachment-btn"
                                                                                        data-attachment-id="<?= $attachment_item['id'] ?>"
                                                                                        data-bs-toggle="tooltip"
                                                                                        title="Remove attachment">
                                                                                        <i class="bi bi-x fs-2"></i>
                                                                                    </span>

                                                                                    <div class="modal fade" id="attachment_lightbox_<?= $attachment_item['id'] ?>" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title"><?= esc($attachment_item['title']) ?></h5>
                                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                </div>
                                                                                                <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                                    <iframe class="loaded_content" width="100%" src="<?= base_url($file_path) ?>"></iframe>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="separator separator-dashed my-4"></div>

                                                                                <!-- <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <span class="text-muted fs-8 fw-semibold">Type:</span>
                                                                                    <span class="badge badge-light-primary fw-bold">.<?= strtoupper($extension) ?></span>
                                                                                </div> -->

                                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <span class="text-muted fs-8 fw-semibold">Size:</span>
                                                                                    <span class="text-gray-800 fw-bold fs-8">
                                                                                        <?php if (!empty($attachment_item['file_size'])): ?>
                                                                                            <?= number_format($attachment_item['file_size'] / 1024, 2) ?> KB
                                                                                        <?php else: ?>
                                                                                            N/A
                                                                                        <?php endif; ?>
                                                                                    </span>
                                                                                </div>

                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <span class="text-muted fs-8 fw-semibold">Uploaded:</span>
                                                                                    <span class="text-gray-800 fw-bold fs-8">
                                                                                        <?= date('d M Y', strtotime($attachment_item['created_at'])) ?>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-dismissible bg-light-info d-flex flex-column flex-sm-row p-5 mb-5">
                                                            <i class="bi bi-info-circle fs-2x text-info me-4 mb-5 mb-sm-0"></i>
                                                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                                                <span class="text-gray-700">No additional documents uploaded yet.</span>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="card card-bordered shadow-sm">
                                                    <!-- <div class="card-header ribbon ribbon-top">
                                                        <h3 class="card-title">Upload New Documents</h3>
                                                    </div> -->
                                                    <div class="card-body">
                                                        <div id="employee_additional_attachments">
                                                            <div class="form-group">
                                                                <div data-repeater-list="additional_attachments">
                                                                    <div data-repeater-item>
                                                                        <div class="form-group row mb-5 align-items-center">
                                                                            <div class="col-md-4">
                                                                                <label class="form-label fw-semibold">Document Title</label>
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm form-control-solid"
                                                                                    name="attachment_title"
                                                                                    placeholder="e.g., Educational Certificate, Experience Letter" />
                                                                                <small class="text-muted form-text" style="font-size:0.75rem">
                                                                                    <i class="bi bi-info-circle me-1"></i>Enter a descriptive title to identify this document
                                                                                </small>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Choose File</label>
                                                                                <input type="file"
                                                                                    class="form-control form-control-sm attachment-file-input"
                                                                                    name="attachment_file"
                                                                                    accept=".png,.jpg,.jpeg,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar" />
                                                                                <small class="text-muted form-text" style="font-size:0.75rem">
                                                                                    Allowed: PNG, JPG, PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR (Max: 5MB)
                                                                                </small>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <label class="form-label">&nbsp;</label><br>
                                                                                <div class="d-flex align-items-center justify-content-end">
                                                                                    <a href="javascript:;"
                                                                                        data-repeater-delete
                                                                                        class="btn btn-sm btn-light-danger">
                                                                                        <i class="la la-trash-o"></i>Remove
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <a href="javascript:;"
                                                                    data-repeater-create
                                                                    class="btn btn-sm btn-light-primary">
                                                                    <i class="la la-plus"></i>Add Another Document
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" id="attachments_to_delete" name="attachments_to_delete" value="" />
                                            </div>
                                            <!-- End Additional Documents Section -->

                                            <div class="col-md-12 mb-4">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class=" form-label">PDC Cheques</label>
                                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                                <label class="form-check-label me-3" for="enable_pdc">
                                                                    No
                                                                </label>
                                                                <input class="form-check-input min-w-75px" type="checkbox" value="yes" id="enable_pdc" name="enable_pdc" <?php echo (isset($attachment['pdc_cheque']['enable_pdc']) && $attachment['pdc_cheque']['enable_pdc'] == 'yes') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="enable_pdc">
                                                                    Yes
                                                                </label>
                                                            </div>
                                                            <small class="text-danger error-text" id="enable_pdc_error"></small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row me-md-n1 pt-5 bg-light rounded" id="pdc_container" style="display: <?php echo (@$attachment['pdc_cheque']['enable_pdc'] == 'yes') ? 'flex' : 'none'; ?>">
                                                    <div class="col-md-8 d-flex flex-column justify-content-center">
                                                        <div class="form-group mb-3">
                                                            <label class="mb-3" for="pdc_cheque_numbers">PDC Cheque Numbers</label>
                                                            <div>
                                                                <div class="input-group input-group-sm mb-3">
                                                                    <span class="input-group-text">Bank name</span>
                                                                    <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_1" name="pdc_bank_name_1" value="<?= set_value('pdc_bank_name_1', @$attachment['pdc_cheque']['bank_name_1']) ?>">
                                                                    <span class="input-group-text">Cheque Number</span>
                                                                    <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_1" name="pdc_cheque_number_1" value="<?= set_value('pdc_cheque_number_1', @$attachment['pdc_cheque']['cheque_number_1']) ?>">
                                                                </div>
                                                                <small class="text-danger error-text" id="pdc_bank_name_1_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_1') : '' ?></small>
                                                                <small class="text-danger error-text" id="pdc_cheque_number_1_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_1') : '' ?></small>
                                                            </div>
                                                            <div>
                                                                <div class="input-group input-group-sm mb-3">
                                                                    <span class="input-group-text">Bank name</span>
                                                                    <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_2" name="pdc_bank_name_2" value="<?= set_value('pdc_bank_name_2', @$attachment['pdc_cheque']['bank_name_2']) ?>">
                                                                    <span class="input-group-text">Cheque Number</span>
                                                                    <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_2" name="pdc_cheque_number_2" value="<?= set_value('pdc_cheque_number_2', @$attachment['pdc_cheque']['cheque_number_2']) ?>">
                                                                </div>
                                                                <small class="text-danger error-text" id="pdc_bank_name_2_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_2') : '' ?></small>
                                                                <small class="text-danger error-text" id="pdc_cheque_number_2_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_2') : '' ?></small>
                                                            </div>
                                                            <div>
                                                                <div class="input-group input-group-sm mb-3">
                                                                    <span class="input-group-text">Bank name</span>
                                                                    <input type="text" class="form-control" placeholder="Bank name" id="pdc_bank_name_3" name="pdc_bank_name_3" value="<?= set_value('pdc_bank_name_3', @$attachment['pdc_cheque']['bank_name_3']) ?>">
                                                                    <span class="input-group-text">Cheque Number</span>
                                                                    <input type="text" class="form-control" placeholder="Cheque number" id="pdc_cheque_number_3" name="pdc_cheque_number_3" value="<?= set_value('pdc_cheque_number_3', @$attachment['pdc_cheque']['cheque_number_3']) ?>">
                                                                </div>
                                                                <small class="text-danger error-text" id="pdc_bank_name_3_error"><?= isset($validation) ? display_error($validation, 'pdc_bank_name_3') : '' ?></small>
                                                                <small class="text-danger error-text" id="pdc_cheque_number_3_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_number_3') : '' ?></small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3" style="width: max-content;">

                                                            <div id="pdc_cheque_documents_attachment_select" class="image-input image-input-outline <?php echo (isset($attachment['pdc_cheque']['file']) && !empty($attachment['pdc_cheque']['file'])) ? '' : 'image-input-empty'; ?>" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                                <div class="image-input-wrapper w-150px h-150px"
                                                                    <?php
                                                                    if (isset($attachment['pdc_cheque']['file']) && !empty($attachment['pdc_cheque']['file'])) {
                                                                        if (pathinfo($attachment['pdc_cheque']['file'], PATHINFO_EXTENSION) == 'pdf') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/pdf.svg' . ')"';
                                                                        } elseif (pathinfo($attachment['pdc_cheque']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                            echo 'style="background-image: url(' . base_url() . '/assets/media/svg/files/zip-file-icon.svg' . ')"';
                                                                        } else {
                                                                            echo 'style="background-image: url(\'' . base_url() . $attachment['pdc_cheque']['file'] . '\')"';
                                                                        }
                                                                    } else {
                                                                        echo '';
                                                                    }
                                                                    ?>>
                                                                    <div class="<?php echo (isset($attachment['pdc_cheque']['file']) && !empty($attachment['pdc_cheque']['file'])) ? 'd-block' : 'd-none'; ?> w-100 h-100 overlay preview-button">
                                                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                            <i class="bi bi-eye-fill text-white fs-2x cursor-pointer <?php if (pathinfo(@$attachment['pdc_cheque']['file'], PATHINFO_EXTENSION) == 'zip') {
                                                                                                                                            echo 'd-none';
                                                                                                                                        } else {
                                                                                                                                            echo 'd-block';
                                                                                                                                        } ?>" data-bs-target="#pdc_cheque_attachment_lightbox" data-bs-toggle="modal"></i>
                                                                            <a href="<?php echo base_url() . @$attachment['pdc_cheque']['file']; ?>" target="_blank"><i class="bi bi-download text-white fs-2x"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                                    <input type="file" id="pdc_cheque_attachment" name="pdc_cheque_attachment" accept=".png, .jpg, .jpeg, .pdf, .zip" />
                                                                    <input type="hidden" name="pdc_cheque_attachment_remove" />
                                                                </label>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                                <div class="modal fade" id="pdc_cheque_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body d-flex" style="min-height: 70vh;">
                                                                                <iframe id="pdc_cheque_attachment_lightbox_iframe" class="loaded_content" width="100%" src="<?php echo (isset($attachment['pdc_cheque']['file']) && !empty($attachment['pdc_cheque']['file']) && pathinfo($attachment['pdc_cheque']['file'], PATHINFO_EXTENSION) !== 'zip') ? base_url() . $attachment['pdc_cheque']['file'] : ''; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <small class="text-danger error-text" id="pdc_cheque_attachment_error"><?= isset($validation) ? display_error($validation, 'pdc_cheque_attachment') : '' ?></small>
                                                        </div>
                                                        <small class="text-muted" style="font-size:0.75rem">
                                                            JPEG, PNG, PDF and ZIP Files are allowed. <br>
                                                            No Preview will be available for zip files
                                                        </small>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Basic info-->
                            </div>

                            <div class="tab-pane fade" id="Salary_Structure" role="tabpanel">
                                <?php

                                if ($can_view_salary == true || $can_update_salary == true) {
                                ?>
                                    <div class="card shadow-none">
                                        <!--begin::Card header-->
                                        <div class="card-header bg-info bg-gradient bg-opacity-50">
                                            <!--begin::Card title-->
                                            <div class="card-title">
                                                <h3 class="fw-bolder">Salary Structure</h3>
                                            </div>
                                            <!--end::Card title-->
                                        </div>
                                        <!--begin::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body border-bottom">
                                            <div class="row gy-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label d-flex align-items-center justify-content-start" for="employee_id"><span>Employee ID:</span> <span class="ms-2"><span class="text-muted">#</span><?php echo @$salary['employee_id']; ?></span></label>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label d-flex align-items-center justify-content-start" for="ctc"><span>CTC:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span> <?php echo @$salary['ctc']; ?></span></label>

                                                        <small class="text-muted">Includes 1.25 EL and 1 CL</small><br>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label d-flex align-items-center justify-content-start" for="gross_salary"><span>Gross Salary:</span> <span class="ms-2"><span><i class="fa-solid fa-indian-rupee-sign"></i></span> <?php echo @$salary['gross_salary']; ?></span></label>

                                                        <small class="text-danger error-text" id="gross_salary_error"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label">Basic Salary</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('basic_salary', @$salary['basic_salary']) ?> </span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="house_rent_allowance">HRA</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('house_rent_allowance', @$salary['house_rent_allowance']) ?> </span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="conveyance">Conveyance</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('conveyance', @$salary['conveyance']) ?> </span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="medical_allowance">Medical Allowance</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('medical_allowance', @$salary['medical_allowance']) ?> </span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="special_allowance">Special Allowance</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('special_allowance', @$salary['special_allowance']) ?> </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="fuel_allowance">Fuel Allowance</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('fuel_allowance', @$salary['fuel_allowance']) ?> </span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="vacation_allowance">Vacation Allowance</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('vacation_allowance', @$salary['vacation_allowance']) ?> </span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="other_allowance">Other Allowance</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('other_allowance', @$salary['other_allowance']) ?></span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="gratuity">Gratuity</label>
                                                        <span class="form-control form-control-sm border-dashed" style="background-color: #d4eeff;">((BasicSalary/26)*15)*(1/12) </span>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!--begin::Card header-->
                                        <div class="card-header bg-info bg-gradient bg-opacity-50">
                                            <!--begin::Card title-->
                                            <div class="card-title">
                                                <h3 class="fw-bolder">Deductions</h3>
                                            </div>
                                            <!--end::Card title-->
                                        </div>
                                        <!--begin::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body border-bottom">

                                            <div class="row gy-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label">PF</label>
                                                        <span class="form-control form-control-sm border-dashed"><?php echo (isset($salary['pf']) && $salary['pf'] == 'yes') ? 'yes' : 'no'; ?></span>


                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="pf_number_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="pf_number">UAN Number</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('pf_number', @$salary['pf_number']) ?></span>

                                                    </div>
                                                </div>

                                                <div class="col-md-4"></div>

                                                <div class="col-md-4" id="pf_employee_contribution_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="pf_employee_contribution">Employee Contribution</label>
                                                        <span class="form-control form-control-sm border-dashed">12%</span>
                                                        <br>
                                                        <small class="text-muted">if BasicSalary >= 15000 or <br>GrossSalary-HRA >= 15000 <br>then value will be 12% of 15000, <br>otherwise value will be <br>12% of (GrossSalary-HRA)</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="pf_employer_contribution_container" style="display: <?php echo (@$salary['pf'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="pf_employer_contribution">Employer Contribution</label>
                                                        <span class="form-control form-control-sm border-dashed">13%</span>
                                                        <br>
                                                        <small class="text-muted">if BasicSalary >= 15000 or <br>GrossSalary-HRA >= 15000 <br>then value will be 13% of 15000, <br>otherwise value will be <br>13% of (GrossSalary-HRA)</small>
                                                    </div>
                                                </div>

                                            </div>

                                            <hr class="my-3 opacity-10">

                                            <div class="row gy-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label">ESI</label>
                                                        <span class="form-control form-control-sm border-dashed"><?php echo (@$salary['esi'] == 'yes') ? 'yes' : 'no'; ?></span>

                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="esi_number_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="esi_number">ESI Number</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('esi_number', @$salary['esi_number']) ?></span>

                                                    </div>
                                                </div>

                                                <div class="col-md-4"></div>

                                                <div class="col-md-4" id="esi_employee_contribution_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="esi_employee_contribution">Employee Contribution</label>
                                                        <span class="form-control form-control-sm border-dashed">0.75%</span>
                                                        <br>
                                                        <small class="text-muted">0.75% of GrossSalary</small><br>

                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="esi_employer_contribution_container" style="display: <?php echo (@$salary['esi'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="esi_employer_contribution">Employer Contribution</label>
                                                        <span class="form-control form-control-sm border-dashed">3.25%</span>
                                                        <br>
                                                        <small class="text-muted">3.25% of GrossSalary</small>

                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-3 opacity-10">

                                            <div class="row gy-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label">LWF</label>
                                                        <span class="form-control form-control-sm border-dashed"><?php echo (@$salary['lwf'] == 'yes') ? 'yes' : 'no'; ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="lwf_employee_contribution_container" style="display: <?php echo (@$salary['lwf'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="lwf_employee_contribution">Employee Contribution</label>
                                                        <span class="form-control form-control-sm border-dashed">0.2%</span>
                                                        <br>
                                                        <small class="text-muted">if state is HARYANA then value will be 0.2% maximum ₹31/-</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="lwf_employer_contribution_container" style="display: <?php echo (@$salary['lwf'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="lwf_employer_contribution">Employer Contribution</label>
                                                        <span class="form-control form-control-sm border-dashed">2 X employee_contribution</span>
                                                        <br>
                                                        <small class="text-muted">if state is HARYANA Twice of the employee contribution</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--begin::TDS New-->
                                        <div class="card-header bg-info bg-gradient bg-opacity-50">
                                            <h5 class="card-title">TDS Records month wise</h5>
                                            <div class="card-toolbar my-0"></div>
                                        </div>
                                        <div class="card-body">
                                            <table id="tds_records_table" class="w-100 table rounded table-row-bordered table-striped table-row-gray-100 align-middle gs-0 gy-3 mb-0 text-center">
                                                <thead>
                                                    <tr>
                                                        <th><strong>Month</strong></th>
                                                        <th><strong>Amount</strong></th>
                                                        <th><strong>Actions</strong></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="card-footer border-bottom">
                                            <p class="mb-0">Accounts team will decide how much TDS will be deducted for every month individualy</p>
                                        </div>
                                        <!--begin::TDS New-->

                                        <!--begin::Imprest-->
                                        <div class="card-header bg-info bg-gradient bg-opacity-50">
                                            <h5 class="card-title">IMPREST Records month wise</h5>
                                            <div class="card-toolbar my-0"></div>
                                        </div>
                                        <div class="card-body">
                                            <table id="imprest_records_table" class="w-100 table rounded table-row-bordered table-striped table-row-gray-100 align-middle gs-0 gy-3 mb-0 text-center">
                                                <thead>
                                                    <tr>
                                                        <th><strong>Month</strong></th>
                                                        <th><strong>Amount</strong></th>
                                                        <th><strong>Actions</strong></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="card-footer border-bottom">
                                            <p class="mb-0">Accounts team will decide how much IMPREST will be deducted for every month individualy</p>
                                        </div>
                                        <!--end::Imprest-->

                                        <!--begin::Phone bill deduction-->
                                        <div class="card-header bg-info bg-gradient bg-opacity-50">
                                            <h5 class="card-title">Phone Bill Deduction month wise</h5>
                                            <div class="card-toolbar my-0"></div>
                                        </div>
                                        <div class="card-body">
                                            <table id="phone_bill_records_table" class="w-100 table rounded table-row-bordered table-striped table-row-gray-100 align-middle gs-0 gy-3 mb-0 text-center">
                                                <thead>
                                                    <tr>
                                                        <th><strong>Month</strong></th>
                                                        <th><strong>Amount</strong></th>
                                                        <th><strong>Actions</strong></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="card-footer border-bottom">
                                            <p class="mb-0">Accounts team will decide how much Phone Bill will be deducted for every month individualy</p>
                                        </div>
                                        <!--end::Phone bill deduction-->

                                        <!--begin::Voucher Entry-->
                                        <div class="card-header bg-info bg-gradient bg-opacity-50">
                                            <h5 class="card-title">Voucher Entry month wise</h5>
                                            <div class="card-toolbar my-0">


                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table id="voucher_records_table" class="w-100 table rounded table-row-bordered table-striped table-row-gray-100 align-middle gs-0 gy-3 mb-0 text-center">
                                                <thead>
                                                    <tr>
                                                        <th><strong>Month</strong></th>
                                                        <th><strong>Amount</strong></th>
                                                        <th><strong>Reason</strong></th>
                                                        <th><strong>Note</strong></th>
                                                        <th><strong>Actions</strong></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <!--end::Voucher deduction-->

                                        <!--begin::Other benefits-->
                                        <div class="card-header bg-info bg-gradient bg-opacity-50">
                                            <h5 class="card-title">Other Benefits</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row gy-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label">Bonus</label>
                                                        <span class="form-control form-control-sm border-dashed"><?php echo (isset($salary['enable_bonus']) && $salary['enable_bonus'] == 'yes') ? 'yes' : 'no'; ?></span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="bonus_container" style="display: <?php echo (@$salary['enable_bonus'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="bonus">Bonus</label>
                                                        <span class="form-control form-control-sm border-dashed">8.33% of Minimum Wages</span>
                                                        <br>
                                                        <small class="text-muted">Per month</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-3 opacity-10">

                                            <div class="row gy-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label">Non Compete Loan</label>
                                                        <span class="form-control form-control-sm border-dashed"><?php echo (@$salary['non_compete_loan'] == 'yes') ? 'yes' : 'no'; ?></span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="non_compete_loan_from_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="non_compete_loan_from">From</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('non_compete_loan_from', @$salary['non_compete_loan_from']) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="non_compete_loan_to_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="non_compete_loan_to">To</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('non_compete_loan_to', @$salary['non_compete_loan_to']) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="non_compete_loan_amount_per_month_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="non_compete_loan_amount_per_month">Amount Per Month</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('non_compete_loan_amount_per_month', @$salary['non_compete_loan_amount_per_month']) ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" id="non_compete_loan_remarks_container" style="display: <?php echo (@$salary['non_compete_loan'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="non_compete_loan_remarks">Remarks</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('non_compete_loan_remarks', @$salary['non_compete_loan_remarks']) ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-3 opacity-10">

                                            <div class="row gy-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="floating-label">Loyalty Incentive</label>
                                                        <span class="form-control form-control-sm border-dashed"><?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'yes' : 'no'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="loyalty_incentive_from_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="loyalty_incentive_from">From</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('loyalty_incentive_from', @$salary['loyalty_incentive_from']) ?></span>

                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="loyalty_incentive_to_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="loyalty_incentive_to">To</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('loyalty_incentive_to', @$salary['loyalty_incentive_to']) ?></span>

                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="loyalty_incentive_amount_per_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="loyalty_incentive_amount_per_month">Amount Per Month</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('loyalty_incentive_amount_per_month', @$salary['loyalty_incentive_amount_per_month']) ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="loyalty_incentive_mature_after_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="loyalty_incentive_mature_after_month">Mature every X month</label>
                                                        <span class="form-control form-control-sm border-dashed">
                                                            <?php
                                                            if (@$salary['loyalty_incentive_mature_after_month'] == '1') {
                                                                echo "1 Month";
                                                            } else if (@$salary['loyalty_incentive_mature_after_month'] == '3') {
                                                                echo "3 Month";
                                                            } else if (@$salary['loyalty_incentive_mature_after_month'] == '6') {
                                                                echo "6 Month";
                                                            } else if (@$salary['loyalty_incentive_mature_after_month'] == '12') {
                                                                echo "12 Month";
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="loyalty_incentive_pay_after_month_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="loyalty_incentive_pay_after_month">Pay every X month</label>
                                                        <span class="form-control form-control-sm border-dashed">
                                                            <?php
                                                            if (@$salary['loyalty_incentive_pay_after_month'] == '1') {
                                                                echo "1 Month after Maturity";
                                                            } else if (@$salary['loyalty_incentive_pay_after_month'] == '3') {
                                                                echo "3 Month after Maturity";
                                                            } else if (@$salary['loyalty_incentive_pay_after_month'] == '6') {
                                                                echo "6 Month after Maturity";
                                                            } else if (@$salary['loyalty_incentive_pay_after_month'] == '12') {
                                                                echo "12 Month after Maturity";
                                                            }
                                                            ?>
                                                        </span>
                                                        <small class="text-muted">Amount will be paid after maturity time + selected value</small><br>
                                                    </div>
                                                </div>




                                                <div class="col-md-8" id="loyalty_incentive_remarks_container" style="display: <?php echo (@$salary['loyalty_incentive'] == 'yes') ? 'block' : 'none'; ?>">
                                                    <div class="form-group">
                                                        <label class="floating-label" for="loyalty_incentive_remarks">Remarks</label>
                                                        <span class="form-control form-control-sm border-dashed"><?= set_value('loyalty_incentive_remarks', @$salary['loyalty_incentive_remarks']) ?></span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Other benefits-->
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <div class="card shadow-none">
                                        <!--begin::Card header-->
                                        <div class="card-body">
                                            <p class="mb-0 text-muted">You are not authorised to view or update Salary</p>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="tab-pane fade" id="Overrides" role="tabpanel">
                                <!--begin::Basic info-->
                                <div class="card shadow-none">

                                    <?php
                                    if ($can_override_rh) {
                                    ?>
                                        <div class="card-header py-0">
                                            <div class="card-title">
                                                <h3 class="fw-bolder text-info">Religious Holidays</h3>
                                            </div>
                                        </div>
                                        <div class="card-body border-bottom">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4">
                                                    <label class="floating-label">First RH</label>
                                                    <span class="form-control form-control-sm border-dashed" id="first_rh_view">
                                                        <?php
                                                        if (!empty($allRH)) {
                                                            $firstRh = array_filter($allRH, function ($the_rh)  use ($rh_id_1) {
                                                                return $the_rh['id'] == @$rh_id_1;
                                                            });
                                                        } else {
                                                            $firstRh = null;
                                                        }
                                                        if (!empty($firstRh)) {
                                                            echo reset($firstRh)['holiday_name'] . "(" . date('d M', strtotime(reset($firstRh)['holiday_date'])) . ")";
                                                        } else {
                                                            echo "None";
                                                        }
                                                        ?>
                                                    </span>
                                                </div>

                                                <div class="col-lg-3 col-md-4">
                                                    <label class="floating-label">Second RH</label>
                                                    <span class="form-control form-control-sm border-dashed" id="second_rh_view">
                                                        <?php
                                                        if (!empty($allRH)) {
                                                            $secondRh = array_filter($allRH, function ($the_rh)  use ($rh_id_2) {
                                                                return $the_rh['id'] == @$rh_id_2;
                                                            });
                                                        } else {
                                                            $secondRh = null;
                                                        }
                                                        if (!empty($secondRh)) {
                                                            echo reset($secondRh)['holiday_name'] . "(" . date('d M', strtotime(reset($secondRh)['holiday_date'])) . ")";
                                                        } else {
                                                            echo "None";
                                                        }
                                                        ?>
                                                    </span>
                                                </div>

                                                <div class="col-lg-3 col-md-4">
                                                    <!--begin::modify-->
                                                    <button target="_blank" id="rh_override_drawer_toggle" class="btn btn-sm btn-light-primary border border-dashed border-primary py-2 mt-6">Override</button>
                                                    <!--end::modify-->
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($can_override_special_benefits) {
                                    ?>
                                        <div class="card-header py-0">
                                            <div class="card-title">
                                                <h3 class="fw-bolder text-info">Special Benefits</h3>
                                            </div>
                                        </div>
                                        <div class="card-body border-bottom d-flex gap-5 flex-wrap">
                                            <div>
                                                <label class="floating-label">Second Saturday fixed off</label>
                                                <span class="form-control form-control-sm border-dashed" id="second_saturday_fixed_off_view">
                                                    <?php echo $second_saturday_fixed_off == 'yes' ? 'Yes' : 'No'; ?>
                                                </span>
                                            </div>

                                            <div>
                                                <label class="floating-label">Over Time allowed</label>
                                                <span class="form-control form-control-sm border-dashed" id="over_time_allowed_view">
                                                    <?php echo $over_time_allowed == 'yes' ? 'Yes' : 'No'; ?>
                                                </span>
                                            </div>

                                            <div>
                                                <label class="floating-label">Late sitting allowed</label>
                                                <span class="form-control form-control-sm border-dashed" id="late_sitting_allowed_view">
                                                    <?php echo $late_sitting_allowed == 'yes' ? 'Yes' : 'No'; ?>
                                                </span>
                                            </div>

                                            <div>
                                                <label class="floating-label">Late sitting formula</label>
                                                <span class="form-control form-control-sm border-dashed" id="late_sitting_allowed_view">
                                                    <?php echo $late_sitting_formula; ?>
                                                </span>
                                            </div>

                                            <div>
                                                <label class="floating-label">Late sitting effective from</label>
                                                <span class="form-control form-control-sm border-dashed" id="late_sitting_formula_effective_from_view">
                                                    <?php echo !empty($late_sitting_formula_effective_from) ? date('d M, Y', strtotime($late_sitting_formula_effective_from)) : ''; ?>
                                                </span>
                                            </div>

                                            <div class="">
                                                <!--begin::modify-->
                                                <button target="_blank" id="special_benefits_override_drawer_toggle" class="btn btn-sm btn-light-primary border border-dashed border-primary py-2 mt-6">Override</button>
                                                <!--end::modify-->
                                            </div>

                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($can_change_password) {
                                    ?>
                                        <div class="card-header py-0">
                                            <div class="card-title">
                                                <h3 class="fw-bolder text-info">Password</h3>
                                            </div>
                                        </div>
                                        <div class="card-body border-bottom">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <!--begin::modify-->
                                                    <button target="_blank" id="change_password_drawer_toggle" class="btn btn-sm btn-light-primary border border-dashed border-primary py-2">Change Password</button>
                                                    <!--end::modify-->
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($can_override_leave_balance) {
                                    ?>
                                        <div class="card-header py-0">
                                            <div class="card-title">
                                                <h3 class="fw-bolder text-info">Leave Balance Overrides</h3>
                                            </div>
                                        </div>
                                        <div class="card-body border-bottom">
                                            <div class="mb-3 d-flex gap-5 flex-wrap">
                                                <div>
                                                    <label class="floating-label">CL Balance</label>
                                                    <span class="form-control form-control-sm border-dashed" id="cl_balance_view">
                                                        <?php echo !empty($cl_balance) ? $cl_balance : '0'; ?>
                                                    </span>
                                                </div>

                                                <div class="">
                                                    <!--begin::modify-->
                                                    <button target="_blank" id="cl_balance_drawer_toggle" class="btn btn-sm btn-light-primary border border-dashed border-primary py-2 mt-6">Override CL</button>
                                                    <!--end::modify-->
                                                </div>
                                            </div>
                                            <div class="mb-3 d-flex gap-5 flex-wrap">
                                                <div>
                                                    <label class="floating-label">EL Balance</label>
                                                    <span class="form-control form-control-sm border-dashed" id="el_balance_view">
                                                        <?php echo !empty($el_balance) ? $el_balance : '0'; ?>
                                                    </span>
                                                </div>

                                                <div class="">
                                                    <!--begin::modify-->
                                                    <button target="_blank" id="el_balance_drawer_toggle" class="btn btn-sm btn-light-primary border border-dashed border-primary py-2 mt-6">Override EL</button>
                                                    <!--end::modify-->
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($can_override_special_holiday) {
                                    ?>
                                        <div class="card-header py-0">
                                            <div class="card-title">
                                                <h3 class="fw-bolder text-info">Special Holiday</h3>
                                            </div>
                                        </div>
                                        <div class="card-body border-bottom">
                                            <div class="mb-3 d-flex gap-5 flex-wrap">
                                                <div>
                                                    <div class="d-flex gap-4 flex-wrap" id="special_holidays_view">
                                                        <span class="form-control form-control-sm border-dashed">
                                                            Coming soon
                                                        </span>
                                                    </div>
                                                </div>

                                                <div style="margin-top: 0.10rem">
                                                    <!--begin::modify-->
                                                    <button target="_blank" id="special_holiday_drawer_toggle" class="btn btn-sm btn-light-primary border border-dashed border-primary py-2 ">Override Special Holidays</button>
                                                    <!--end::modify-->
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if (
                                        $can_override_rh == false &&
                                        $can_override_special_benefits == false &&
                                        $can_change_password == false &&
                                        $can_override_leave_balance == false &&
                                        $can_override_special_holiday == false
                                    ) {
                                    ?>
                                        <div class="card shadow-none">
                                            <!--begin::Card header-->
                                            <div class="card-body">
                                                <p class="mb-0 text-muted">You are not authorised to Override</p>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>


                        </div>
                        <!-- end::tab panes -->


                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end py-6 px-9">
                            <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $id; ?>" required />
                            <button type="submit" id="submit_update_employee" class="btn btn-primary d-inline">
                                <span class="indicator-label">Save Changes</span>
                                <span class="indicator-progress">
                                    Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Actions-->


                    </div>
                </div>







            </div>
        </form>

    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/jstree/jstree.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>



<script type="text/javascript">
    jQuery(document).ready(function($) {

        var can_update_salary = '<?php echo isset($can_update_salary) && !empty($can_update_salary) ? "yes" : "no"; ?>';
        var can_view_salary = '<?php echo isset($can_view_salary) && !empty($can_view_salary) ? "yes" : "no"; ?>';

        $(document).on('shown.bs.tab', function(e) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust();
        });

        $('.input-group-flatpicker').flatpickr({
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'Y-m-d',
            altInputClass: "form-control form-control-sm"
        })

        /*begin::Show validation error message*/
        var response = "<?php echo session()->getFlashdata('fail'); ?>";
        if (response.length) {
            Swal.fire({
                html: response,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
            })
        }
        /*end::Show validation error message*/

        $(document).on('input', '.form-control', function() {
            $(this).parent().find('.error-text').html('');
        })

        $(document).on('change', '#company_id', function(e) {
            var company_id = $('#company_id').val();
            var data = {
                'company_id': company_id,
            };
            //load departments
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/get-department-by-company-id'); ?>",
                data: data,
                success: function(response) {
                    console.log(response);
                    if (response.response_type == 'error') {
                        $('#department_id').html('<option></option>');
                        if (company_id !== '') {
                            $('#department_id_error').html(response.response_description);
                        }
                    }

                    if (response.response_type == 'success') {
                        if (typeof response.response_data.departments != 'undefined') {
                            $('#department_id').html('<option></option>');
                            var department_data = response.response_data.departments;
                            $.each(department_data, function(index, department) {
                                $('#department_id').append('<option value="' + department.id + '">' + department.department_name + ' - ' + department.company_short_name + '</option>');
                            });
                        }
                    }
                },
                failed: function() {
                    Swal.fire({
                        html: "Ajax Failed while loading departments conditionally, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                }
            });
            //load Reporting managers
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/get-reporting-managers-by-company-id'); ?>",
                data: data,
                success: function(response) {
                    console.log(response);
                    if (response.response_type == 'error') {
                        $('#reporting_manager_id').html('<option></option>');
                        if (company_id !== '') {
                            $('#reporting_manager_id_error').html(response.response_description);
                        }
                    }

                    if (response.response_type == 'success') {
                        if (typeof response.response_data.reportingManagers != 'undefined') {
                            $('#reporting_manager_id').html('<option></option>');
                            var reportingManagers_data = response.response_data.reportingManagers;
                            $.each(reportingManagers_data, function(index, reportingManager) {
                                $('#reporting_manager_id').append('<option value="' + reportingManager.id + '">' + reportingManager.name + ' - ' + reportingManager.department_name + ' - ' + reportingManager.company_short_name + '</option>');
                            });
                        }
                    }
                },
                failed: function() {
                    Swal.fire({
                        html: "Ajax Failed while loading Reporting Managers conditionally, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                }
            });
        })

        $(document).on('change', '#gender, #marital_status', function(e) {
            var gender = $("#gender").val();
            var marital_status = $("#marital_status").val();
            if (gender == 'female' && marital_status == 'married') {
                $('.husband-name-wrapper').show();
            } else {
                $('.husband-name-wrapper').hide();
            }
        })

        $(document).on('change', 'div.flatpicker-repeater#date_of_birth_picker > input.flatpickr-input', function(e) {
            // console.log("staterd", $(this).val());
            var newAge = _calculateAge($(this).val());
            // console.log("ended", newAge);
            $(this).closest('.form-group').find('.form-control-age').val(newAge);
        });

        function _calculateAge(dob) {
            if (dob != '') {
                // var str = dob.split('-');
                // var firstdate = new Date(str[0],str[1],str[2]);
                // var today = new Date();
                // var dayDiff = Math.ceil(today.getTime() - firstdate.getTime()) / (1000 * 60 * 60 * 24 * 365);
                // var age = parseInt(dayDiff);

                var mdate = dob.toString();
                var yearThen = parseInt(mdate.substring(0, 4), 10);
                var monthThen = parseInt(mdate.substring(5, 7), 10);
                var dayThen = parseInt(mdate.substring(8, 10), 10);
                var today = new Date();
                var birthday = new Date(yearThen, monthThen - 1, dayThen);
                var differenceInMilisecond = today.valueOf() - birthday.valueOf();
                var year_age = Math.floor(differenceInMilisecond / 31536000000);
                var day_age = Math.floor((differenceInMilisecond % 31536000000) / 86400000);
                var month_age = Math.floor(day_age / 30);
                day_age = day_age % 30;
                if (isNaN(year_age) || isNaN(month_age) || isNaN(day_age)) {
                    var age = 0;
                } else {
                    var age = year_age;
                }
            } else {
                var age = 0;
            }
            return age;
        }

        $(document).on('click', '#submit_update_employee', function(e) {
            e.preventDefault();

            var hasValidationError = false;
            var errorMessage = '';
            $('#employee_additional_attachments [data-repeater-item]').each(function() {
                var $row = $(this);
                var titleInput = $row.find('input[type="text"]').first();
                var fileInput = $row.find('input.attachment-file-input');

                if (titleInput.length > 0 && fileInput.length > 0) {
                    var titleValue = titleInput.val() ? titleInput.val().trim() : '';
                    var fileValue = fileInput.val() ? fileInput.val() : '';

                    if (fileValue && !titleValue) {
                        hasValidationError = true;
                        errorMessage = 'Please enter a document title for all uploaded files.';
                        titleInput.addClass('is-invalid');
                    } else {
                        titleInput.removeClass('is-invalid');
                    }
                }
            });

            if (hasValidationError) {
                Swal.fire({
                    title: 'Validation Error',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }


            var old_probation = $("#old_probation").text();
            var new_probation = $("#probation").val();

            console.log("old_probation", old_probation);
            console.log("new_probation", new_probation);

            if (old_probation == 'confirmed' && new_probation != 'confirmed') {
                Swal.fire({
                    html: "You want to change the probation from CONFIRMED to " + new_probation,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes Change",
                    denyButtonText: `Don't Change`
                }).then((result) => {
                    if (result.isConfirmed) {
                        do_submit_form();
                    } else {
                        Swal.fire("Then change the probation status to CONFIRMED", "", "info");
                    }
                });
            } else {
                do_submit_form();
            }

        })

        function do_submit_form() {
            var form = $('#update_employee');
            var submitButton = $(this);
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/master/employee/edit/validate'); ?>",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    submitButton.removeAttr("data-kt-indicator");
                    submitButton.removeAttr("disabled");
                    if (response.response_type == 'error') {
                        if (response.response_description.length) {
                            Swal.fire({
                                html: response.response_description,
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                            }).then(function(e) {
                                if (typeof response.response_data.validation != 'undefined') {
                                    var validation = response.response_data.validation;
                                    $.each(validation, function(index, value) {
                                        form.find('#' + index + '_error').html(value);
                                    });
                                }
                            });
                        }
                    }

                    if (response.response_type == 'success') {
                        if (response.response_description.length) {

                            var employee_updated_response = response.response_description;

                            Swal.fire({
                                html: `${response.response_description}`,
                                icon: "success",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                            });

                            // if (can_update_salary === 'yes') {
                            //     $.ajax({
                            //         method: "post",
                            //         url: "<?php echo base_url('/ajax/master/salary/validate'); ?>",
                            //         data: data,
                            //         processData: false,
                            //         contentType: false,
                            //         success: function(response) {
                            //             console.log(response);
                            //             submitButton.removeAttr("data-kt-indicator");
                            //             submitButton.removeAttr("disabled");
                            //             if (response.response_type == 'error') {
                            //                 if (response.response_description.length) {
                            //                     Swal.fire({
                            //                         html: response.response_description,
                            //                         icon: "error",
                            //                         buttonsStyling: !1,
                            //                         confirmButtonText: "Ok, got it!",
                            //                         customClass: {
                            //                             confirmButton: "btn btn-primary"
                            //                         },
                            //                     }).then(function(e) {
                            //                         if (typeof response.response_data.validation != 'undefined') {
                            //                             var validation = response.response_data.validation;
                            //                             $.each(validation, function(index, value) {
                            //                                 form.find('#' + index + '_error').html(value);
                            //                             });
                            //                         }
                            //                     });
                            //                 }
                            //             }

                            //             if (response.response_type == 'success') {
                            //                 if (response.response_description.length) {
                            //                     Swal.fire({
                            //                         html: `${employee_updated_response}<br>${response.response_description}`,
                            //                         icon: "success",
                            //                         buttonsStyling: !1,
                            //                         confirmButtonText: "Ok, got it!",
                            //                         customClass: {
                            //                             confirmButton: "btn btn-primary"
                            //                         },
                            //                     });
                            //                 }
                            //             }
                            //         },
                            //         failed: function() {
                            //             Swal.fire({
                            //                 html: "Ajax Failed, Please contact administrator",
                            //                 icon: "error",
                            //                 buttonsStyling: !1,
                            //                 confirmButtonText: "Ok, got it!",
                            //                 customClass: {
                            //                     confirmButton: "btn btn-primary"
                            //                 },
                            //             })
                            //             submitButton.removeAttr("data-kt-indicator");
                            //             submitButton.removeAttr("disabled");
                            //         }
                            //     })
                            // } else {
                            //     Swal.fire({
                            //         html: `${response.response_description}`,
                            //         icon: "success",
                            //         buttonsStyling: !1,
                            //         confirmButtonText: "Ok, got it!",
                            //         customClass: {
                            //             confirmButton: "btn btn-primary"
                            //         },
                            //     });
                            // }

                        }
                    }
                },
                failed: function() {
                    Swal.fire({
                        html: "Ajax Failed, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                    submitButton.removeAttr("data-kt-indicator");
                    submitButton.removeAttr("disabled");
                }
            })
        }

        $('.image-input').each(function() {
            var id = $(this).attr('id');
            // var imageInputElement = document.querySelector("#" + id);
            // var imageInput = KTImageInput.getInstance(imageInputElement);
            var id = this.id;
            if (!id) return;
            var imageInput = KTImageInput.getInstance(this);
            if (!imageInput) return;
            var iframe_src_backup = '';


            imageInput.on("kt.imageinput.changed", function() {
                var fileInput = $("#" + id).find("input[type=file]")[0];
                var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                var lightboxIframe = $("#" + id).find("iframe");
                var reader = new FileReader();
                reader.onload = function(e) {
                    var extension = fileInput.files[0].name.split('.').pop().toLowerCase();
                    switch (extension) {
                        case 'pdf':
                            lightboxIframe.attr('src', e.target.result);
                            imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                            imageInputWrapper.css({
                                'background-image': 'url(<?php echo base_url(); ?>assets/media/svg/files/pdf.svg)'
                            });
                            break;
                        case 'zip':
                            imageInputWrapper.css({
                                'background-image': 'url(<?php echo base_url(); ?>assets/media/svg/files/zip-file-icon.svg)'
                            });
                            imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                            break;
                        default:
                            lightboxIframe.attr('src', e.target.result);
                            imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                            break;
                    }
                }
                reader.readAsDataURL(fileInput.files[0]);
            });

            imageInput.on("kt.imageinput.change", function() {
                var lightboxIframe = $("#" + id).find("iframe");
                if (iframe_src_backup == '') {
                    iframe_src_backup = lightboxIframe.attr('src');
                }
            });

            imageInput.on("kt.imageinput.canceled", function() {
                var lightboxIframe = $("#" + id).find("iframe");
                if (iframe_src_backup !== '') {
                    lightboxIframe.attr('src', iframe_src_backup);
                } else {
                    var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                    imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                    lightboxIframe.attr('src', '');
                }
            });

            imageInput.on("kt.imageinput.removed", function() {
                var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                var lightboxIframe = $("#" + id).find("iframe");
                lightboxIframe.attr('src', '');
            });
        });

        var $family_members = $('form#update_employee #family_members').repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown();
                $(this).find('.flatpicker-repeater').flatpickr({
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'Y-m-d',
                    altInputClass: "form-control form-control-sm"
                })
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {
                // $(this).find('[data-kt-repeater="select2"]').select2();
                Inputmask({
                    regex: "([01]?[0-9]|2[0-3]):[0-5][0-9]",
                }).mask($(this).find('[data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]"]'));
                $(this).find('.flatpicker-repeater').flatpickr({
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'Y-m-d',
                    altInputClass: "form-control form-control-sm"
                })
            }
        });

        $family_members.setList(<?php echo $family_members; ?>);

        // Additional Attachments Repeater
        var $additional_attachments = $('#employee_additional_attachments').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
                validateAttachmentFileInputs();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {
                validateAttachmentFileInputs();
            }
        });

        // File input validation for attachments
        function validateAttachmentFileInputs() {
            $('.attachment-file-input').off('change').on('change', function() {
                var file = this.files[0];
                var $input = $(this);
                var maxSize = 5 * 1024 * 1024; // 5MB in bytes
                var allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar'];

                if (file) {
                    // Check file size
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'File size must not exceed 5MB. Selected file: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB',
                            buttonsStyling: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        $input.val(''); // Clear the input
                        return false;
                    }

                    // Check file extension
                    var extension = file.name.split('.').pop().toLowerCase();
                    if (allowedExtensions.indexOf(extension) === -1) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: 'Only PNG, JPG, JPEG, PDF, DOC, DOCX, XLS, XLSX, ZIP, and RAR files are allowed.',
                            buttonsStyling: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        $input.val(''); // Clear the input
                        return false;
                    }
                }
            });
        }

        validateAttachmentFileInputs();

        // Delete existing attachment handler
        $(document).on('click', '.delete-attachment-btn', function(e) {
            e.preventDefault();
            var attachmentId = $(this).data('attachment-id');
            var $container = $('#existing_attachments_container').find('.col-md-4[data-attachment-id="' + attachmentId + '"], .col-lg-3[data-attachment-id="' + attachmentId + '"]').first();
            Swal.fire({
                title: 'Are you sure?',
                text: "This document will be removed from the page and deleted when you save the form!",
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('User confirmed deletion');
                    var currentDeleteList = $('#attachments_to_delete').val();
                    var deleteArray = currentDeleteList ? currentDeleteList.split(',') : [];
                    if (deleteArray.indexOf(attachmentId.toString()) === -1) {
                        deleteArray.push(attachmentId);
                    }
                    $('#attachments_to_delete').val(deleteArray.join(','));
                    $container.slideUp(400, function() {
                        $(this).remove();
                        var remainingCount = $('#existing_attachments_container [data-attachment-id]').length;
                        if (remainingCount === 0) {
                            console.log('No more attachments, showing empty message');
                            $('#existing_attachments_container').html(
                                '<div class="alert alert-dismissible bg-light-info d-flex flex-column flex-sm-row p-5 mb-5">' +
                                '<i class="bi bi-info-circle fs-2x text-info me-4 mb-5 mb-sm-0"></i>' +
                                '<div class="d-flex flex-column pe-0 pe-sm-10">' +
                                '<span class="text-gray-700">No additional documents uploaded yet.</span>' +
                                '</div>' +
                                '</div>'
                            );
                        }
                    });
                    Swal.fire({
                        title: 'Removed!',
                        text: 'The document has been removed and will be deleted when you save.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                }
            });
        });

        $(document).on('change', '#update_employee input#enable_pdc', function(e) {
            if ($(this).is(':checked')) {
                $('#update_employee #pdc_container').slideDown();
            } else {
                $('#update_employee #pdc_container').slideUp();
            }
        });

        // $(document).on('change', 'input#pf', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#pf_number_container').slideDown();
        //         $('#pf_employee_contribution_container').slideDown();
        //         $('#pf_employer_contribution_container').slideDown();
        //     } else {
        //         $('#pf_number_container').slideUp();
        //         $('#pf_employee_contribution_container').slideUp();
        //         $('#pf_employer_contribution_container').slideUp();
        //     }
        // });

        // $(document).on('change', 'input#esi', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#esi_number_container').slideDown();
        //         $('#esi_employee_contribution_container').slideDown();
        //         $('#esi_employer_contribution_container').slideDown();
        //     } else {
        //         $('#esi_number_container').slideUp();
        //         $('#esi_employee_contribution_container').slideUp();
        //         $('#esi_employer_contribution_container').slideUp();
        //     }
        // });

        // $(document).on('change', 'input#lwf', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#lwf_employee_contribution_container').slideDown();
        //         $('#lwf_employer_contribution_container').slideDown();
        //         $('#lwf_deduction_on_every_n_month_container').slideDown();
        //     } else {
        //         $('#lwf_employee_contribution_container').slideUp();
        //         $('#lwf_employer_contribution_container').slideUp();
        //         $('#lwf_deduction_on_every_n_month_container').slideUp();
        //     }
        // });

        /*begin::TDS*/
        // $(document).on('change', 'input#tds', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#tds_amount_per_month_container').slideDown();
        //         $('#tds_preferred_slab_container').slideDown();
        //         $('#tds_slab_container').slideDown();
        //         initialise_switch();
        //     } else {
        //         $('#tds_amount_per_month_container').slideUp();
        //         $('#tds_preferred_slab_container').slideUp();
        //         $('#tds_slab_container').slideUp();
        //     }
        // });

        var tds_records_table = $("#tds_records_table").DataTable({
            "ajax": {
                url: "<?= base_url('ajax/backend/master/tds-master/get/' . $salary["employee_id"]) ?>",
                type: "POST",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "data",
            },
            "columns": [{
                    data: "year_month"
                },
                {
                    data: "deduction_amount",
                    render: function(data, type, row, meta) {
                        return "₹ " + data;
                    }
                },
                {
                    data: "actions",
                    render: function(data, type, row, meta) {
                        if (row.salary_disbursed == 'yes') {
                            return '<span>Locked</span><br><small>Salary Disbursed</small>';
                        } else {

                            if (can_update_salary === 'yes') {
                                // return '<div class="d-flex justify-content-center">' +
                                //     '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-tds-record" data-id="' + row.id + '">' +
                                //     '<span class="svg-icon svg-icon-3">' +
                                //     '<i class="fas fa-trash"></i>' +
                                //     '</span>' +
                                //     '</a>' +
                                //     '</div>';
                                return '<div class="d-flex justify-content-center"><a href="https://hrm.healthgenie.in/backend/master/salary/id/<?php echo $salary['employee_id']; ?>" target="_blank" class="btn btn-link btn-sm" >Edit</a></div>';
                            } else if (can_view_salary === 'yes') {
                                return '<span>Action Not Allowed</span>';
                            } else {
                                return '<span>Action Not Allowed</span>';
                            }
                        }
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "paging": false,
            "bInfo": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ]
        });

        // $("#tds_salary_month").flatpickr({
        //     minDate: "<?php echo $last_month_salary_disbursed == 'no' ? date('Y-m-01', strtotime('first day of last month')) : date('Y-m-01'); ?>",
        //     maxDate: "<?php echo date('Y-m-t'); ?>",
        //     plugins: [
        //         new monthSelectPlugin({
        //             shorthand: true,
        //             dateFormat: "F Y",
        //             altFormat: "F Y",
        //             theme: "dark",
        //         })
        //     ]
        // });

        // $(document).on('click', '#add_tds_master_record_submit_button', function(e) {
        //     e.preventDefault();
        //     var submitButton = $(this);
        //     submitButton.attr("data-kt-indicator", "on");
        //     submitButton.attr("disabled", "true");
        //     $('#add_tds_master_record_modal').modal('hide');
        //     var data = {
        //         'tds_employee_id': $("#tds_employee_id").val(),
        //         'tds_salary_month': $("#tds_salary_month").val(),
        //         'tds_deduction_amount': $("#tds_deduction_amount").val()
        //     };
        //     $.ajax({
        //         method: "post",
        //         url: "<?php echo base_url('ajax/backend/master/tds-master/add'); ?>",
        //         data: data,
        //         // processData: false,
        //         // contentType: false,
        //         success: function(response) {
        //             console.log(response);
        //             submitButton.removeAttr("data-kt-indicator");
        //             submitButton.removeAttr("disabled");
        //             if (response.response_type == 'error') {
        //                 if (response.response_description.length) {
        //                     Swal.fire({
        //                         html: response.response_description,
        //                         icon: "error",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     }).then(function(e) {
        //                         $('#add_tds_master_record_modal').modal('show');
        //                         if (typeof response.response_data.validation != 'undefined') {
        //                             var validation = response.response_data.validation;
        //                             $.each(validation, function(index, value) {
        //                                 $('#' + index + '_error').html(value);
        //                             });
        //                         }
        //                     });
        //                 }
        //             }

        //             if (response.response_type == 'success') {
        //                 if (response.response_description.length) {
        //                     Swal.fire({
        //                         html: response.response_description,
        //                         icon: "success",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     }).then(function(e) {
        //                         $("#add_tds_master_record_modal").modal('hide');
        //                         $("#tds_records_table").DataTable().ajax.reload();
        //                     });
        //                 }
        //             }
        //         },
        //         failed: function() {
        //             Swal.fire({
        //                 html: "Ajax Failed, Please contact administrator",
        //                 icon: "error",
        //                 buttonsStyling: !1,
        //                 confirmButtonText: "Ok, got it!",
        //                 customClass: {
        //                     confirmButton: "btn btn-primary"
        //                 },
        //             }).then(function(e) {
        //                 $('#add_tds_master_record_modal').modal('show');
        //                 submitButton.removeAttr("data-kt-indicator");
        //                 submitButton.removeAttr("disabled");
        //             })
        //         }
        //     })
        // });

        // $(document).on('click', '.delete-tds-record', function(e) {
        //     e.preventDefault();
        //     var tds_record_id = $(this).data('id');
        //     var data = {
        //         'tds_record_id': tds_record_id,
        //     };

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Yes, delete it!',
        //         customClass: {
        //             confirmButton: "btn btn-sm btn-primary",
        //             cancelButton: "btn btn-sm btn-secondary"
        //         },
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 method: "post",
        //                 url: "<?php echo base_url('ajax/backend/master/tds-master/delete'); ?>",
        //                 data: data,
        //                 success: function(response) {
        //                     console.log(response);
        //                     if (response.response_type == 'error') {
        //                         if (response.response_description.length) {
        //                             Swal.fire({
        //                                 html: response.response_description,
        //                                 icon: "error",
        //                                 buttonsStyling: !1,
        //                                 confirmButtonText: "Ok, got it!",
        //                                 customClass: {
        //                                     confirmButton: "btn btn-primary"
        //                                 },
        //                             })
        //                         }
        //                     }

        //                     if (response.response_type == 'success') {
        //                         if (response.response_description.length) {
        //                             Swal.fire({
        //                                 html: response.response_description,
        //                                 icon: "success",
        //                                 buttonsStyling: !1,
        //                                 confirmButtonText: "Ok, got it!",
        //                                 customClass: {
        //                                     confirmButton: "btn btn-primary"
        //                                 },
        //                             }).then(function() {
        //                                 $("#tds_records_table").DataTable().ajax.reload();
        //                             })
        //                         }
        //                     }
        //                 },
        //                 failed: function() {
        //                     Swal.fire({
        //                         html: "Ajax Failed, Please contact administrator",
        //                         icon: "error",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     })
        //                 }
        //             })
        //         }
        //     })
        // });
        /*end::TDS*/

        /*begin::IMPREST*/
        var imprest_records_table = $("#imprest_records_table").DataTable({
            "ajax": {
                url: "<?= base_url('ajax/backend/master/imprest-master/get/' . $salary["employee_id"]) ?>",
                type: "POST",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "data",
            },
            "columns": [{
                    data: "year_month"
                },
                {
                    data: "deduction_amount",
                    render: function(data, type, row, meta) {
                        return "₹ " + data;
                    }
                },
                {
                    data: "actions",
                    render: function(data, type, row, meta) {
                        if (row.salary_disbursed == 'yes') {
                            return '<span>Locked</span><br><small>Salary Disbursed</small>';
                        } else {


                            if (can_update_salary === 'yes') {
                                // return '<div class="d-flex justify-content-center">' +
                                // '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-imprest-record" data-id="' + row.id + '">' +
                                // '<span class="svg-icon svg-icon-3">' +
                                // '<i class="fas fa-trash"></i>' +
                                // '</span>' +
                                // '</a>' +
                                // '</div>';
                                return '<div class="d-flex justify-content-center"><a href="https://hrm.healthgenie.in/backend/master/salary/id/<?php echo $salary['employee_id']; ?>" target="_blank" class="btn btn-link btn-sm" >Edit</a></div>';
                            } else if (can_view_salary === 'yes') {
                                return '<span>Action Not Allowed</span>';
                            } else {
                                return '<span>Action Not Allowed</span>';
                            }




                        }
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "paging": false,
            "bInfo": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ]
        });

        // $("#imprest_salary_month").flatpickr({
        //     minDate: "<?php echo $last_month_salary_disbursed == 'no' ? date('Y-m-01', strtotime('first day of last month')) : date('Y-m-01'); ?>",
        //     maxDate: "<?php echo date('Y-m-t'); ?>",
        //     plugins: [
        //         new monthSelectPlugin({
        //             shorthand: true,
        //             dateFormat: "F Y",
        //             altFormat: "F Y",
        //             theme: "dark",
        //         })
        //     ]
        // });

        // $(document).on('click', '#add_imprest_master_record_submit_button', function(e) {
        //     e.preventDefault();
        //     var submitButton = $(this);
        //     submitButton.attr("data-kt-indicator", "on");
        //     submitButton.attr("disabled", "true");
        //     $("#add_imprest_master_record_modal").modal('hide');
        //     var data = {
        //         'imprest_employee_id': $("#imprest_employee_id").val(),
        //         'imprest_salary_month': $("#imprest_salary_month").val(),
        //         'imprest_deduction_amount': $("#imprest_deduction_amount").val()
        //     };
        //     $.ajax({
        //         method: "post",
        //         url: "<?php echo base_url('ajax/backend/master/imprest-master/add'); ?>",
        //         data: data,
        //         // processData: false,
        //         // contentType: false,
        //         success: function(response) {
        //             console.log(response);
        //             submitButton.removeAttr("data-kt-indicator");
        //             submitButton.removeAttr("disabled");
        //             if (response.response_type == 'error') {
        //                 if (response.response_description.length) {
        //                     Swal.fire({
        //                         html: response.response_description,
        //                         icon: "error",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     }).then(function(e) {
        //                         $("#add_imprest_master_record_modal").modal('show');
        //                         if (typeof response.response_data.validation != 'undefined') {
        //                             var validation = response.response_data.validation;
        //                             $.each(validation, function(index, value) {
        //                                 $('#' + index + '_error').html(value);
        //                             });
        //                         }
        //                     });
        //                 }
        //             }

        //             if (response.response_type == 'success') {
        //                 if (response.response_description.length) {
        //                     Swal.fire({
        //                         html: response.response_description,
        //                         icon: "success",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     }).then(function(e) {
        //                         $("#add_imprest_master_record_modal").modal('hide');
        //                         $("#imprest_records_table").DataTable().ajax.reload();
        //                     });
        //                 }
        //             }
        //         },
        //         failed: function() {
        //             Swal.fire({
        //                 html: "Ajax Failed, Please contact administrator",
        //                 icon: "error",
        //                 buttonsStyling: !1,
        //                 confirmButtonText: "Ok, got it!",
        //                 customClass: {
        //                     confirmButton: "btn btn-primary"
        //                 },
        //             }).then(function(e) {
        //                 $("#add_imprest_master_record_modal").modal('show');
        //                 submitButton.removeAttr("data-kt-indicator");
        //                 submitButton.removeAttr("disabled");
        //             })
        //         }
        //     })
        // });

        // $(document).on('click', '.delete-imprest-record', function(e) {
        //     e.preventDefault();
        //     var imprest_record_id = $(this).data('id');
        //     var data = {
        //         'imprest_record_id': imprest_record_id,
        //     };

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Yes, delete it!',
        //         customClass: {
        //             confirmButton: "btn btn-sm btn-primary",
        //             cancelButton: "btn btn-sm btn-secondary"
        //         },
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 method: "post",
        //                 url: "<?php echo base_url('ajax/backend/master/imprest-master/delete'); ?>",
        //                 data: data,
        //                 success: function(response) {
        //                     console.log(response);
        //                     if (response.response_type == 'error') {
        //                         if (response.response_description.length) {
        //                             Swal.fire({
        //                                 html: response.response_description,
        //                                 icon: "error",
        //                                 buttonsStyling: !1,
        //                                 confirmButtonText: "Ok, got it!",
        //                                 customClass: {
        //                                     confirmButton: "btn btn-primary"
        //                                 },
        //                             })
        //                         }
        //                     }

        //                     if (response.response_type == 'success') {
        //                         if (response.response_description.length) {
        //                             Swal.fire({
        //                                 html: response.response_description,
        //                                 icon: "success",
        //                                 buttonsStyling: !1,
        //                                 confirmButtonText: "Ok, got it!",
        //                                 customClass: {
        //                                     confirmButton: "btn btn-primary"
        //                                 },
        //                             }).then(function() {
        //                                 $("#imprest_records_table").DataTable().ajax.reload();
        //                             })
        //                         }
        //                     }
        //                 },
        //                 failed: function() {
        //                     Swal.fire({
        //                         html: "Ajax Failed, Please contact administrator",
        //                         icon: "error",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     })
        //                 }
        //             })
        //         }
        //     })
        // });
        /*end::IMPREST*/

        /*begin::PHONE BILL*/
        var phone_bill_records_table = $("#phone_bill_records_table").DataTable({
            "ajax": {
                url: "<?= base_url('ajax/backend/master/phone-bill-master/get/' . $salary["employee_id"]) ?>",
                type: "POST",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "data",
            },
            "columns": [{
                    data: "year_month"
                },
                {
                    data: "deduction_amount",
                    render: function(data, type, row, meta) {
                        return "₹ " + data;
                    }
                },
                {
                    data: "actions",
                    render: function(data, type, row, meta) {
                        if (row.salary_disbursed == 'yes') {
                            return '<span>Locked</span><br><small>Salary Disbursed</small>';
                        } else {

                            if (can_update_salary === 'yes') {
                                // return '<div class="d-flex justify-content-center">' +
                                // '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-phone-bill-record" data-id="' + row.id + '">' +
                                // '<span class="svg-icon svg-icon-3">' +
                                // '<i class="fas fa-trash"></i>' +
                                // '</span>' +
                                // '</a>' +
                                // '</div>';
                                return '<div class="d-flex justify-content-center"><a href="https://hrm.healthgenie.in/backend/master/salary/id/<?php echo $salary['employee_id']; ?>" target="_blank" class="btn btn-link btn-sm" >Edit</a></div>';
                            } else if (can_view_salary === 'yes') {
                                return '<span>Action Not Allowed</span>';
                            } else {
                                return '<span>Action Not Allowed</span>';
                            }

                        }
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "paging": false,
            "bInfo": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ]
        });

        // $("#phone_bill_salary_month").flatpickr({
        //     minDate: "<?php echo $last_month_salary_disbursed == 'no' ? date('Y-m-01', strtotime('first day of last month')) : date('Y-m-01'); ?>",
        //     maxDate: "<?php echo date('Y-m-t'); ?>",
        //     plugins: [
        //         new monthSelectPlugin({
        //             shorthand: true,
        //             dateFormat: "F Y",
        //             altFormat: "F Y",
        //             theme: "dark",
        //         })
        //     ]
        // });

        // $(document).on('click', '#add_phone_bill_master_record_submit_button', function(e) {
        //     e.preventDefault();
        //     var submitButton = $(this);
        //     submitButton.attr("data-kt-indicator", "on");
        //     submitButton.attr("disabled", "true");
        //     $("#add_phone_bill_master_record_modal").modal('hide');
        //     var data = {
        //         'phone_bill_employee_id': $("#phone_bill_employee_id").val(),
        //         'phone_bill_salary_month': $("#phone_bill_salary_month").val(),
        //         'phone_bill_deduction_amount': $("#phone_bill_deduction_amount").val()
        //     };
        //     $.ajax({
        //         method: "post",
        //         url: "<?php echo base_url('ajax/backend/master/phone-bill-master/add'); ?>",
        //         data: data,
        //         // processData: false,
        //         // contentType: false,
        //         success: function(response) {
        //             console.log(response);
        //             submitButton.removeAttr("data-kt-indicator");
        //             submitButton.removeAttr("disabled");
        //             if (response.response_type == 'error') {
        //                 if (response.response_description.length) {
        //                     Swal.fire({
        //                         html: response.response_description,
        //                         icon: "error",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     }).then(function(e) {
        //                         $("#add_phone_bill_master_record_modal").modal('show');
        //                         if (typeof response.response_data.validation != 'undefined') {
        //                             var validation = response.response_data.validation;
        //                             $.each(validation, function(index, value) {
        //                                 $('#' + index + '_error').html(value);
        //                             });
        //                         }
        //                     });
        //                 }
        //             }

        //             if (response.response_type == 'success') {
        //                 if (response.response_description.length) {
        //                     Swal.fire({
        //                         html: response.response_description,
        //                         icon: "success",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     }).then(function(e) {
        //                         $("#add_phone_bill_master_record_modal").modal('hide');
        //                         $("#phone_bill_records_table").DataTable().ajax.reload();
        //                     });
        //                 }
        //             }
        //         },
        //         failed: function() {
        //             Swal.fire({
        //                 html: "Ajax Failed, Please contact administrator",
        //                 icon: "error",
        //                 buttonsStyling: !1,
        //                 confirmButtonText: "Ok, got it!",
        //                 customClass: {
        //                     confirmButton: "btn btn-primary"
        //                 },
        //             }).then(function(e) {
        //                 $("#add_phone_bill_master_record_modal").modal('show');
        //                 submitButton.removeAttr("data-kt-indicator");
        //                 submitButton.removeAttr("disabled");
        //             })
        //         }
        //     })
        // });

        // $(document).on('click', '.delete-phone-bill-record', function(e) {
        //     e.preventDefault();
        //     var phone_bill_record_id = $(this).data('id');
        //     var data = {
        //         'phone_bill_record_id': phone_bill_record_id,
        //     };

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Yes, delete it!',
        //         customClass: {
        //             confirmButton: "btn btn-sm btn-primary",
        //             cancelButton: "btn btn-sm btn-secondary"
        //         },
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 method: "post",
        //                 url: "<?php echo base_url('ajax/backend/master/phone-bill-master/delete'); ?>",
        //                 data: data,
        //                 success: function(response) {
        //                     console.log(response);
        //                     if (response.response_type == 'error') {
        //                         if (response.response_description.length) {
        //                             Swal.fire({
        //                                 html: response.response_description,
        //                                 icon: "error",
        //                                 buttonsStyling: !1,
        //                                 confirmButtonText: "Ok, got it!",
        //                                 customClass: {
        //                                     confirmButton: "btn btn-primary"
        //                                 },
        //                             })
        //                         }
        //                     }

        //                     if (response.response_type == 'success') {
        //                         if (response.response_description.length) {
        //                             Swal.fire({
        //                                 html: response.response_description,
        //                                 icon: "success",
        //                                 buttonsStyling: !1,
        //                                 confirmButtonText: "Ok, got it!",
        //                                 customClass: {
        //                                     confirmButton: "btn btn-primary"
        //                                 },
        //                             }).then(function() {
        //                                 $("#phone_bill_records_table").DataTable().ajax.reload();
        //                             })
        //                         }
        //                     }
        //                 },
        //                 failed: function() {
        //                     Swal.fire({
        //                         html: "Ajax Failed, Please contact administrator",
        //                         icon: "error",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     })
        //                 }
        //             })
        //         }
        //     })
        // });
        /*end::PHONE BILL*/

        /*begin::VOUCHER ENTRY*/
        var voucher_records_table = $("#voucher_records_table").DataTable({
            "ajax": {
                url: "<?= base_url('ajax/backend/master/voucher-master/get/' . $salary["employee_id"]) ?>",
                type: "POST",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "data",
            },
            "columns": [{
                    data: "year_month"
                },
                {
                    data: "amount",
                    render: function(data, type, row, meta) {
                        return "₹ " + data;
                    }
                },
                {
                    data: "reason"
                },
                {
                    data: "note",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap mb-0 lh-sm " style="width: 100px"><small>' + data + '</small></p>';
                    }
                },
                {
                    data: "actions",
                    render: function(data, type, row, meta) {
                        if (row.salary_disbursed == 'yes') {
                            return '<span>Locked</span><br><small>Salary Disbursed</small>';
                        } else {


                            if (can_update_salary === 'yes') {
                                // return '<div class="d-flex justify-content-center">' +
                                // '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-voucher-record" data-id="' + row.id + '">' +
                                // '<span class="svg-icon svg-icon-3">' +
                                // '<i class="fas fa-trash"></i>' +
                                // '</span>' +
                                // '</a>' +
                                // '</div>';
                                return '<div class="d-flex justify-content-center"><a href="https://hrm.healthgenie.in/backend/master/salary/id/<?php echo $salary['employee_id']; ?>" target="_blank" class="btn btn-link btn-sm" >Edit</a></div>';
                            } else if (can_view_salary === 'yes') {
                                return '<span>Action Not Allowed</span>';
                            } else {
                                return '<span>Action Not Allowed</span>';
                            }



                        }
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "paging": false,
            "bInfo": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ]
        });

        // $("#voucher_salary_month").flatpickr({
        //     minDate: "<?php echo $last_month_salary_disbursed == 'no' ? date('Y-m-01', strtotime('first day of last month')) : date('Y-m-01'); ?>",
        //     maxDate: "<?php echo date('Y-m-t'); ?>",
        //     plugins: [
        //         new monthSelectPlugin({
        //             shorthand: true,
        //             dateFormat: "F Y",
        //             altFormat: "F Y",
        //             theme: "dark",
        //         })
        //     ]
        // });

        // $(document).on('click', '#add_voucher_master_record_submit_button', function(e) {
        //     e.preventDefault();
        //     var submitButton = $(this);
        //     submitButton.attr("data-kt-indicator", "on");
        //     submitButton.attr("disabled", "true");
        //     $("#add_voucher_record_modal").modal('hide');
        //     var data = {
        //         'voucher_employee_id': $("#voucher_employee_id").val(),
        //         'voucher_salary_month': $("#voucher_salary_month").val(),
        //         'voucher_amount': $("#voucher_amount").val(),
        //         'voucher_reason': $("#voucher_reason").val(),
        //         'voucher_note': $("#voucher_note").val(),
        //     };
        //     $.ajax({
        //         method: "post",
        //         url: "<?php echo base_url('ajax/backend/master/voucher-master/add'); ?>",
        //         data: data,
        //         // processData: false,
        //         // contentType: false,
        //         success: function(response) {
        //             console.log(response);
        //             submitButton.removeAttr("data-kt-indicator");
        //             submitButton.removeAttr("disabled");
        //             if (response.response_type == 'error') {
        //                 if (response.response_description.length) {
        //                     Swal.fire({
        //                         html: response.response_description,
        //                         icon: "error",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     }).then(function(e) {
        //                         $("#add_voucher_record_modal").modal('show');
        //                         if (typeof response.response_data != "undefined" && typeof response.response_data.validation != "undefined") {
        //                             var validation = response.response_data.validation;
        //                             $.each(validation, function(index, value) {
        //                                 $('#' + index + '_error').html(value);
        //                             });
        //                         }
        //                     });
        //                 }
        //             }

        //             if (response.response_type == 'success') {
        //                 if (response.response_description.length) {
        //                     Swal.fire({
        //                         html: response.response_description,
        //                         icon: "success",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     }).then(function(e) {
        //                         // $("#add_voucher_record_modal").modal('hide');
        //                         $("#close_voucher_modal").trigger('click');

        //                         $("#voucher_records_table").DataTable().ajax.reload();
        //                     });
        //                 }
        //             }
        //         },
        //         failed: function() {
        //             Swal.fire({
        //                 html: "Ajax Failed, Please contact administrator",
        //                 icon: "error",
        //                 buttonsStyling: !1,
        //                 confirmButtonText: "Ok, got it!",
        //                 customClass: {
        //                     confirmButton: "btn btn-primary"
        //                 },
        //             }).then(function(e) {
        //                 $("#add_voucher_record_modal").modal('show');
        //                 submitButton.removeAttr("data-kt-indicator");
        //                 submitButton.removeAttr("disabled");
        //             })
        //         }
        //     })
        // });

        // $(document).on('click', '.delete-voucher-record', function(e) {
        //     e.preventDefault();
        //     var voucher_record_id = $(this).data('id');
        //     var data = {
        //         'voucher_record_id': voucher_record_id,
        //     };

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Yes, delete it!',
        //         customClass: {
        //             confirmButton: "btn btn-sm btn-primary",
        //             cancelButton: "btn btn-sm btn-secondary"
        //         },
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 method: "post",
        //                 url: "<?php echo base_url('ajax/backend/master/voucher-master/delete'); ?>",
        //                 data: data,
        //                 success: function(response) {
        //                     console.log(response);
        //                     if (response.response_type == 'error') {
        //                         if (response.response_description.length) {
        //                             Swal.fire({
        //                                 html: response.response_description,
        //                                 icon: "error",
        //                                 buttonsStyling: !1,
        //                                 confirmButtonText: "Ok, got it!",
        //                                 customClass: {
        //                                     confirmButton: "btn btn-primary"
        //                                 },
        //                             })
        //                         }
        //                     }

        //                     if (response.response_type == 'success') {
        //                         if (response.response_description.length) {
        //                             Swal.fire({
        //                                 html: response.response_description,
        //                                 icon: "success",
        //                                 buttonsStyling: !1,
        //                                 confirmButtonText: "Ok, got it!",
        //                                 customClass: {
        //                                     confirmButton: "btn btn-primary"
        //                                 },
        //                             }).then(function() {
        //                                 $("#voucher_records_table").DataTable().ajax.reload();
        //                             })
        //                         }
        //                     }
        //                 },
        //                 failed: function() {
        //                     Swal.fire({
        //                         html: "Ajax Failed, Please contact administrator",
        //                         icon: "error",
        //                         buttonsStyling: !1,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn btn-primary"
        //                         },
        //                     })
        //                 }
        //             })
        //         }
        //     })
        // });
        /*end::VOUCHER ENTRY*/

        $(document).on('change', 'input#enable_bonus', function(e) {
            if ($(this).is(':checked')) {
                $('#bonus_container').slideDown();
            } else {
                $('#bonus_container').slideUp();
            }
        });

        /*begin::NON COMPETE LOAN*/
        // $(document).on('change', 'input#non_compete_loan', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#non_compete_loan_from_container').slideDown();
        //         $('#non_compete_loan_to_container').slideDown();
        //         $('#non_compete_loan_amount_per_month_container').slideDown();
        //         $('#non_compete_loan_remarks_container').slideDown();
        //     } else {
        //         $('#non_compete_loan_from_container').slideUp();
        //         $('#non_compete_loan_to_container').slideUp();
        //         $('#non_compete_loan_amount_per_month_container').slideUp();
        //         $('#non_compete_loan_remarks_container').slideUp();
        //     }
        // });
        /*end::NON COMPETE LOAN*/

        /*begin::LOYALTY INCENTIVE*/
        // $(document).on('change', 'input#loyalty_incentive', function(e) {
        //     if ($(this).is(':checked')) {
        //         $('#loyalty_incentive_from_container').slideDown();
        //         $('#loyalty_incentive_to_container').slideDown();
        //         $('#loyalty_incentive_amount_per_month_container').slideDown();
        //         $('#loyalty_incentive_mature_after_month_container').slideDown();
        //         $('#loyalty_incentive_pay_after_month_container').slideDown();
        //         $('#loyalty_incentive_remarks_container').slideDown();
        //     } else {
        //         $('#loyalty_incentive_from_container').slideUp();
        //         $('#loyalty_incentive_to_container').slideUp();
        //         $('#loyalty_incentive_amount_per_month_container').slideUp();
        //         $('#loyalty_incentive_mature_after_month_container').slideUp();
        //         $('#loyalty_incentive_pay_after_month_container').slideUp();
        //         $('#loyalty_incentive_remarks_container').slideUp();
        //     }
        // });
        /*end::LOYALTY INCENTIVE*/

    })
</script>

<script>
    jQuery(document).ready(function() {


        $('.changes_tree').jstree({
            "core": {
                "themes": {
                    "responsive": false
                }
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder"
                },
                "file": {
                    "icon": "fa fa-file"
                }
            },
            "plugins": ["types"]
        });


    })
</script>

<?php
if ($can_override_rh) {
?>
    <div
        id="rh_override_drawer"
        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#rh_override_drawer_toggle"
        data-kt-drawer-close="#rh_override_drawer_dismiss"
        data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}">
        <div class="card rounded-0 w-100">
            <div class="card-header pe-5">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 lh-1">RH Override</a>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="rh_override_drawer_dismiss">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body hover-scroll-overlay-y">
                <form id="override_rh_form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="employee_id" value="<?php echo $id ?>">
                    <div class="mb-3">
                        <label class="form-label" for="rh_id_1" class="mb-3">First RH</label>
                        <select class="form-select form-select-sm" id="rh_id_1" name="rh_id_1" data-control="select2" data-placeholder="Select First RH">
                            <option></option>
                            <?php
                            if (!empty($allRH)) {
                                foreach ($allRH as $the_rh) {
                            ?>
                                    <option value="<?php echo $the_rh['id']; ?>" <?php echo @$rh_id_1 == $the_rh['id'] ? 'selected' : ''; ?>><?php echo $the_rh['holiday_name'] . "(" . date('d M', strtotime($the_rh['holiday_date'])) . ")"; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <small class="text-danger error-text" id="rh_id_1_error"></small>
                    </div>
                    <div class="mb-5">
                        <label class="form-label" for="rh_id_2" class="mb-3">Second RH</label>
                        <select class="form-select form-select-sm" id="rh_id_2" name="rh_id_2" data-control="select2" data-placeholder="Select Second RH">
                            <option></option>
                            <?php
                            if (!empty($allRH)) {
                                foreach ($allRH as $the_rh) {
                            ?>
                                    <option value="<?php echo $the_rh['id']; ?>" <?php echo @$rh_id_2 == $the_rh['id'] ? 'selected' : ''; ?>><?php echo $the_rh['holiday_name'] . "(" . date('d M', strtotime($the_rh['holiday_date'])) . ")"; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <small class="text-danger error-text" id="rh_id_2_error"></small>
                    </div>
                    <div class="">
                        <button type="submit" id="submit_update_rh" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
                            <span class="indicator-label">Update</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <script type="text/javascript">
        function checkTerminationStatus() {
            if ($('#status').val() === 'left in probation') {
                $('#termination_letter_link').removeClass('d-none');
            } else {
                $('#termination_letter_link').addClass('d-none');
            }
        }

        function checkProbationResponseStatus() {
            if ($('#probation').val() == '90 Days Probation') {
                $('#probation_extended_letter_link').removeClass('d-none');
                console.log($('#probation_response').val());
            } else {
                $('#probation_extended_letter_link').addClass('d-none');
                console.log($('#probation_response').val());
            }
            // if ($('#probation_response').val() === 'To be Extended' || $('#status').val() != 'left') {
            //     $('#probation_extended_letter_link').removeClass('d-none');
            // } else {
            //     $('#probation_extended_letter_link').addClass('d-none');
            // }
        }

        $(document).on('change', '#status', function(e) {
            checkTerminationStatus();
        });
        $(document).on('change', '#probation', function(e) {
            checkProbationResponseStatus();
        });

        jQuery(document).ready(function($) {
            checkTerminationStatus();
            checkProbationResponseStatus();
            $(document).on('submit', '#override_rh_form', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/override-rh'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                        if (response.response_type == 'error') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    if (typeof response.response_data.validation != 'undefined') {
                                        var validation = response.response_data.validation;
                                        $.each(validation, function(index, value) {
                                            form.find('#' + index + '_error').html(value);
                                        });
                                    }
                                });
                            }
                        }

                        if (response.response_type == 'success') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    $('#first_rh_view').html(form.find('#rh_id_1 option:selected').text());
                                    $('#second_rh_view').html(form.find('#rh_id_2 option:selected').text());
                                });
                            }
                        }
                    },
                    failed: function() {
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        })
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    }
                })
            })
        })
    </script>
<?php
}
?>

<?php
if ($can_override_special_benefits) {
?>
    <div
        id="special_benefits_override_drawer"
        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#special_benefits_override_drawer_toggle"
        data-kt-drawer-close="#special_benefits_override_drawer_dismiss"
        data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}">
        <div class="card rounded-0 w-100">
            <div class="card-header pe-5">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 lh-1">Special Benefits Override</a>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="special_benefits_override_drawer_dismiss">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body hover-scroll-overlay-y">
                <form id="special_benefits_form" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="employee_id" value="<?php echo $id ?>">

                    <div class="mb-3">
                        <label class="form-label" for="second_saturday_fixed_off" class="mb-3">Second Saturday fixed off</label>
                        <select class="form-select form-select-sm" id="second_saturday_fixed_off" name="second_saturday_fixed_off" data-control="select2" data-placeholder="Select an option">
                            <option></option>
                            <option value="yes" <?php echo $second_saturday_fixed_off == 'yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo $second_saturday_fixed_off != 'yes' ? 'selected' : ''; ?>>No</option>
                        </select>
                        <small class="text-danger error-text" id="second_saturday_fixed_off_error"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="late_sitting_allowed" class="mb-3">Late sitting allowed</label>
                        <select class="form-select form-select-sm" id="late_sitting_allowed" name="late_sitting_allowed" data-control="select2" data-placeholder="Select an option">
                            <option></option>
                            <option value="yes" <?php echo $late_sitting_allowed == 'yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo $late_sitting_allowed != 'yes' ? 'selected' : ''; ?>>No</option>
                        </select>
                        <small class="text-danger error-text" id="late_sitting_allowed_error"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="late_sitting_formula" class="mb-3">Late sitting formula</label>
                        <select class="form-select form-select-sm" id="late_sitting_formula" name="late_sitting_formula" data-control="select2" data-placeholder="Select an option" data-allow-clear="true">
                            <option></option>
                            <option value="1/2" <?php echo $late_sitting_formula == '1/2' ? 'selected' : ''; ?>>1/2</option>
                            <option value="1/3" <?php echo $late_sitting_formula == '1/3' ? 'selected' : ''; ?>>1/3</option>
                            <option value="1/5" <?php echo $late_sitting_formula == '1/5' ? 'selected' : ''; ?>>1/5</option>
                        </select>
                        <small class="text-danger error-text" id="late_sitting_formula_error"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="late_sitting_formula_effective_from" class="mb-3">Late sitting formula effective from</label>
                        <div class="input-group input-group-flatpicker" id="late_sitting_formula_effective_from_picker" data-wrap="true">
                            <input type="text" id="late_sitting_formula_effective_from" class="form-control form-control-sm" name="late_sitting_formula_effective_from" placeholder="Effective from" value="<?php echo !empty($late_sitting_formula_effective_from) ? $late_sitting_formula_effective_from : ''; ?>" data-input data-open>
                            <span class="input-group-text cursor-pointer" data-toggle>
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <small class="text-danger error-text" id="late_sitting_formula_effective_from_error"></small>
                    </div>

                    <div class="mb-5">
                        <label class="form-label" for="over_time_allowed" class="mb-3">Over Time allowed</label>
                        <select class="form-select form-select-sm" id="over_time_allowed" name="over_time_allowed" data-control="select2" data-placeholder="Select an option">
                            <option></option>
                            <option value="yes" <?php echo $over_time_allowed == 'yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo $over_time_allowed != 'yes' ? 'selected' : ''; ?>>No</option>
                        </select>
                        <small class="text-danger error-text" id="over_time_allowed_error"></small>
                    </div>

                    <div>
                        <button type="submit" id="submit_update_password" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
                            <span class="indicator-label">Update</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>

                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(document).on('submit', '#special_benefits_form', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/update-special-benefits'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                        if (response.response_type == 'error') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    if (typeof response.response_data.validation != 'undefined') {
                                        var validation = response.response_data.validation;
                                        $.each(validation, function(index, value) {
                                            form.find('#' + index + '_error').html(value);
                                        });
                                    }
                                });
                            }
                        }

                        if (response.response_type == 'success') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    $('#second_saturday_fixed_off_view').html(form.find('#second_saturday_fixed_off option:selected').text());
                                    $('#late_sitting_allowed_view').html(form.find('#late_sitting_allowed option:selected').text());
                                    $('#late_sitting_formula_view').html(form.find('#late_sitting_formula').val());
                                    $('#late_sitting_formula_view').html(form.find('#late_sitting_formula option:selected').text());
                                    $('#late_sitting_formula_effective_from_view').html(form.find('#late_sitting_formula_effective_from').val());
                                    $('#over_time_allowed_view').html(form.find('#over_time_allowed option:selected').text());
                                });
                            }
                        }
                    },
                    failed: function() {
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        })
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    }
                })
            })
        })
    </script>
<?php
}
?>

<?php
if ($can_change_password) {
?>
    <div
        id="change_password_drawer"
        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#change_password_drawer_toggle"
        data-kt-drawer-close="#change_password_drawer_dismiss"
        data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}">
        <div class="card rounded-0 w-100">
            <div class="card-header pe-5">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 lh-1">Special Benefits Override</a>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="change_password_drawer_dismiss">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body hover-scroll-overlay-y">
                <form id="update_password" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="employee_id" value="<?php echo $id ?>">
                    <div class="mb-6">
                        <label class="form-label" for="new_password" class="mb-3">New Password</label>
                        <input class="form-control from-control-sm" type="text" id="new_password" name="new_password" placeholder="New Password" />
                        <small class="text-danger error-text" id="new_password_error"></small>
                    </div>
                    <div>
                        <button type="submit" id="submit_update_password" class="form-control btn btn-primary d-inline">
                            <span class="indicator-label">Update</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(document).on('submit', '#update_password', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/hr/employee/password-update'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                        if (response.response_type == 'error') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    if (typeof response.response_data.validation != 'undefined') {
                                        var validation = response.response_data.validation;
                                        $.each(validation, function(index, value) {
                                            form.find('#' + index + '_error').html(value);
                                        });
                                    }
                                });
                            }
                        }

                        if (response.response_type == 'success') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                });
                            }
                        }
                    },
                    failed: function() {
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        })
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    }
                })
            })
        })
    </script>
<?php
}
?>

<?php
if ($can_override_leave_balance) {
?>
    <div
        id="cl_balance_drawer"
        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#cl_balance_drawer_toggle"
        data-kt-drawer-close="#cl_balance_drawer_dismiss"
        data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}">
        <div class="card rounded-0 w-100">
            <div class="card-header pe-5">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 lh-1">Override CL Balance</a>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="cl_balance_drawer_dismiss">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body" style="overflow-Y: auto;">
                <form id="override_cl_balance_form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="employee_id" value="<?php echo $id ?>">
                    <input type="hidden" name="leave_type" value="CL">
                    <div class="mb-3">
                        <label class="form-label" for="new_balance" class="mb-3">New Balance</label>
                        <input class="form-control from-control-sm" type="number" max="2" min="0" id="new_balance" name="new_balance" placeholder="New Balance" value="<?php echo $cl_balance ?? 0; ?>" />
                        <small class="text-danger error-text" id="new_balance_error"></small>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="custom_remarks" class="mb-3">Remarks</label>
                        <textarea class="form-control from-control-sm" id="custom_remarks" name="custom_remarks" placeholder="Remarks"></textarea>
                        <small class="text-danger error-text" id="custom_remarks_error"></small>
                    </div>
                    <div>
                        <button type="submit" id="override_cl_balance_password" class="form-control btn btn-primary btn-sm d-inline">
                            <span class="indicator-label">Update</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
                <div class="mt-6 border-top pt-3">
                    <table id="override_cl_balance_history_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center"><strong>History</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var override_cl_balance_history_table = $("#override_cl_balance_history_table").DataTable({
                "buttons": [],
                "ajax": {
                    url: "<?= base_url('/ajax/backend/hr/leave-override-history') ?>",
                    type: "POST",
                    data: {
                        filter: function() {
                            return $('#override_cl_balance_form').serialize();
                        }
                    },
                    "dataSrc": function(json) {
                        var filteredData = json.filter(function(row) {
                            return row.leave_code === "CL";
                        });
                        return filteredData;
                    },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                    searchPlaceholder: "Search"
                },
                "oLanguage": {
                    "sSearch": ""
                },
                "columns": [{
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return `<p class="text-wrap mx-auto mb-0 lh-sm" style="">
                                        <small class="d-block text-start mb-2"><strong>${row.leave_code}</strong> balance updated from <strong>${row.previous_balance}</strong> to <strong>${row.new_balance}</strong></small>
                                        <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.overriden_by_name}</strong></small>
                                        <small class="d-block text-start mb-3">on <strong class="text-danger">${row.date_time}</strong></small>
                                        <small class="d-block text-start fst-italic">Remarks:<br>${row.remarks}</small>
                                    </p>`;
                    }
                }, ],
                "order": [],
                "scrollX": true,
                "scrollY": '400px',
                "scrollCollapse": true,
                "paging": false,
                "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                }, ]
            });

            $(document).on('submit', '#override_cl_balance_form', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/override-leave-balance'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                        if (response.response_type == 'error') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    if (typeof response.response_data.validation != 'undefined') {
                                        var validation = response.response_data.validation;
                                        $.each(validation, function(index, value) {
                                            form.find('#' + index + '_error').html(value);
                                        });
                                    }
                                });
                            }
                        }
                        if (response.response_type == 'success') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    $("#override_cl_balance_history_table").DataTable().ajax.reload();
                                    $("#cl_balance_view").html(form.find('#new_balance').val());
                                });
                            }
                        }
                    },
                    failed: function() {
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        })
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    }
                })
            })
        })
    </script>
<?php
}
?>

<?php
if ($can_override_leave_balance) {
?>
    <div
        id="el_balance_drawer"
        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#el_balance_drawer_toggle"
        data-kt-drawer-close="#el_balance_drawer_dismiss"
        data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}">
        <div class="card rounded-0 w-100">
            <div class="card-header pe-5">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 lh-1">Override EL Balance</a>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="el_balance_drawer_dismiss">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body" style="overflow-Y: auto;">
                <form id="override_el_balance_form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="employee_id" value="<?php echo $id ?>">
                    <input type="hidden" name="leave_type" value="EL">
                    <div class="mb-3">
                        <label class="form-label" for="new_balance" class="mb-3">New Balance</label>
                        <input class="form-control from-control-sm" type="number" step="0.25" max="30" min="0" id="new_balance" name="new_balance" placeholder="New Balance" value="<?php echo $el_balance ?? 0; ?>" />
                        <small class="text-danger error-text" id="new_balance_error"></small>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="custom_remarks" class="mb-3">Remarks</label>
                        <textarea class="form-control from-control-sm" id="custom_remarks" name="custom_remarks" placeholder="Remarks"></textarea>
                        <small class="text-danger error-text" id="custom_remarks_error"></small>
                    </div>
                    <div>
                        <button type="submit" id="override_el_balance_password" class="form-control btn btn-primary btn-sm d-inline">
                            <span class="indicator-label">Update</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
                <div class="mt-6 border-top pt-3">
                    <table id="override_el_balance_history_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center"><strong>History</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var override_el_balance_history_table = $("#override_el_balance_history_table").DataTable({
                "buttons": [],
                "ajax": {
                    url: "<?= base_url('/ajax/backend/hr/leave-override-history') ?>",
                    type: "POST",
                    data: {
                        filter: function() {
                            return $('#override_el_balance_form').serialize();
                        }
                    },
                    "dataSrc": function(json) {
                        var filteredData = json.filter(function(row) {
                            return row.leave_code === "EL";
                        });
                        return filteredData;
                    },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    },
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                    searchPlaceholder: "Search"
                },
                "oLanguage": {
                    "sSearch": ""
                },
                "columns": [{
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return `<p class="text-wrap mx-auto mb-0 lh-sm" style="">
                                        <small class="d-block text-start mb-2"><strong>${row.leave_code}</strong> balance updated from <strong>${row.previous_balance}</strong> to <strong>${row.new_balance}</strong></small>
                                        <small class="d-block text-start"><strong>By</strong> <strong class="text-danger">${row.overriden_by_name}</strong></small>
                                        <small class="d-block text-start mb-3">on <strong class="text-danger">${row.date_time}</strong></small>
                                        <small class="d-block text-start fst-italic">Remarks:<br>${row.remarks}</small>
                                    </p>`;
                    }
                }, ],
                "order": [],
                "scrollX": true,
                "scrollY": '400px',
                "scrollCollapse": true,
                "paging": false,
                "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                }, ]
            });

            $(document).on('submit', '#override_el_balance_form', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/hr/override-leave-balance'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                        if (response.response_type == 'error') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    if (typeof response.response_data.validation != 'undefined') {
                                        var validation = response.response_data.validation;
                                        $.each(validation, function(index, value) {
                                            form.find('#' + index + '_error').html(value);
                                        });
                                    }
                                });
                            }
                        }
                        if (response.response_type == 'success') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    $("#override_el_balance_history_table").DataTable().ajax.reload();
                                    $("#el_balance_view").html(form.find('#new_balance').val());
                                });
                            }
                        }
                    },
                    failed: function() {
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        })
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    }
                })
            })
        })
    </script>
<?php
}
?>

<?php
if ($can_override_special_holiday) {
?>
    <div
        id="special_holiday_drawer"
        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#special_holiday_drawer_toggle"
        data-kt-drawer-close="#special_holiday_drawer_dismiss"
        data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}">
        <div class="card rounded-0 w-100">
            <div class="card-header pe-5">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 lh-1">Override Special Holidays</a>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="special_holiday_drawer_dismiss">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body" style="overflow-Y: auto;">
                <form id="override_special_holiday_form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="employee_id" value="<?php echo $id ?>">
                    <div class="mb-6 d-flex flex-column gap-6">
                        <?php
                        if (!empty($holidays)) {
                            foreach ($holidays as $holiday) {
                                if ($holiday['holiday_code'] == 'SPL HL') {
                                    $holiday_employees = !empty($holiday['employees']) ? explode(",", $holiday['employees']) : [];
                        ?>
                                    <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start px-6 py-3 <?php echo in_array($id, $holiday_employees) ? 'active' : ''; ?>">
                                        <div class="d-flex align-items-center me-2">
                                            <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                                <input class="form-check-input" type="checkbox" name="special_holiday[]" value="<?= $holiday['id'] ?>" <?php echo in_array($id, $holiday_employees) ? 'checked' : ''; ?> />
                                            </div>
                                            <div class="flex-grow-1 holiday-name">
                                                <h2 class="d-flex align-items-center fs-5 fw-bolder flex-wrap mb-1" style="width: max-content;">
                                                    <?= $holiday['holiday_name'] ?>
                                                </h2>
                                                <div class="fw-bold fs-8 opacity-50" style="width: max-content;">
                                                    <?= $holiday['holiday_date']['formatted'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-5">
                                            <span class="badge badge-light-success ms-2 fs-7"><?= $holiday['holiday_code'] ?></span>
                                        </div>
                                    </label>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <div>
                        <button type="submit" id="override_special_holiday" class="form-control btn btn-primary btn-sm d-inline">
                            <span class="indicator-label">Update</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {

            var special_holidays_view_html = `<div class="d-flex gap-4">`;
            $("input[name='special_holiday[]']:checked").each(function(index) {
                special_holidays_view_html += `<div class="form-control form-control-sm border-dashed d-flex flex-column">`;
                special_holidays_view_html += $(this).parent().next().html();
                special_holidays_view_html += `</div>`;
            });
            special_holidays_view_html += `</div>`;
            $("#special_holidays_view").html(special_holidays_view_html);

            $(document).on('change', "input[name='special_holiday[]']", function() {
                if ($(this).prop("checked") == true) {
                    $(this).closest('label').addClass('active');
                } else {
                    $(this).closest('label').removeClass('active');
                }
            })

            $(document).on('submit', '#override_special_holiday_form', function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData(form[0]);

                var submitButton = $(this).find('button[type=submit]');
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                $('.error-text').html('');

                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/master/assign-holiday'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                        if (response.response_type == 'error') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    if (typeof response.response_data.validation != 'undefined') {
                                        var validation = response.response_data.validation;
                                        $.each(validation, function(index, value) {
                                            form.find('#' + index + '_error').html(value);
                                        });
                                    }
                                });
                            }
                        }

                        if (response.response_type == 'success') {
                            if (response.response_description.length) {
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                }).then(function(e) {
                                    var special_holidays_view_html = `<div class="d-flex gap-4">`;
                                    $("input[name='special_holiday[]']:checked").each(function(index) {
                                        special_holidays_view_html += `<div class="form-control form-control-sm border-dashed d-flex flex-column">`;
                                        special_holidays_view_html += $(this).parent().next().html();
                                        special_holidays_view_html += `</div>`;
                                    });
                                    special_holidays_view_html += `</div>`;
                                    $("#special_holidays_view").html(special_holidays_view_html);
                                });
                            }
                        }
                    },
                    failed: function() {
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        })
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                    }
                })
            })

        })
    </script>
<?php
}
?>

<?= $this->endSection() ?>
<?= $this->endSection() ?>