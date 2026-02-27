<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>


<style>
    /* Modal background animation */
    .modal-content {
        border-radius: 15px;
        border: none;
        overflow: hidden;
        animation: popIn 0.4s ease-out;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* Animation */
    @keyframes popIn {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Header style */
    .modal-header {
        background: linear-gradient(135deg, #0d6efd00, #00b4d824);
        color: white;
        border-bottom: none;
        text-align: center;
        display: block;
    }

    /* Body style */
    .modal-body {
        text-align: center;
        font-size: 1.1rem;
        padding: 30px 20px;
        background: #f8f9fa;
    }

    /* Celebration emoji */
    .celebration-icon {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    /* Footer style */
    .modal-footer {
        background: linear-gradient(135deg, #0d6efd00, #00b4d824);
        border-top: none;
        justify-content: center;
    }
</style>

<div class="row gy-5 g-xl-8">
    <div class="col-lg-6 col-xxl-8 order-2 order-lg-1">

        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <div class="row d-flex flex-wrap gy-3 g-x-3 stats-container">
                    <div class="col-12 text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
                        <div class="mt-3 text-muted">Loading attendance statistics...</div>
                    </div>
                </div>
            </div>
        </div>


        <!--begin::Job Listing Notifications-->
        <div class="mb-5">
            <div class="card shadow-sm" id="job-notifications-card">
                <div class="card-header">
                    <div class="card-title d-flex align-items-center">
                        <i class="fa fa-bell text-primary me-2"></i>
                        <h3 class="fw-bold mb-0">Job Listing Notifications</h3>
                        <span class="badge badge-light-danger ms-2" id="notification-badge" style="display: none;"></span>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light" id="refresh-notifications" title="Refresh">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" id="notifications-container">
                    <!--begin::Loading state-->
                    <div class="d-flex justify-content-center py-5" id="notifications-loading">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Loading notifications...</span>
                    </div>
                    <!--end::Loading state-->

                    <!--begin::Empty state-->
                    <div class="d-flex flex-column justify-content-center align-items-center py-5 text-center d-none" id="notifications-empty">
                        <div class="text-muted mb-3">
                            <i class="fa fa-bell-slash fs-2x"></i>
                        </div>
                        <h5 class="text-muted">No New Notifications</h5>
                        <p class="text-muted fs-7">You're all caught up! No new job listing messages at this time.</p>
                    </div>
                    <!--end::Empty state-->

                    <!--begin::Notification items-->
                    <div id="notifications-list" class="row g-3"></div>
                    <!--end::Notification items-->
                </div>
            </div>
        </div>
        <!--end::Job Listing Notifications-->

        <div class="mb-5">
            <style>
                .dataTables_scrollBody {
                    max-height: 400px;
                }
            </style>
            <table id="punching_report_table" class="table table-row-bordered table-striped nowrap">
                <thead>
                    <tr>
                        <th class="text-center"><strong>Date</strong></th>
                        <th class="text-center"><strong>Day</strong></th>
                        <th class="text-center"><strong>Status</strong></th>
                        <th class="text-center"><strong>Shift</strong></th>
                        <th class="text-center"><strong>IN/OUT</strong></th>
                        <th class="text-center"><strong>Late IN</strong></th>
                        <th class="text-center"><strong>Early Out</strong></th>
                        <th class="text-center"><strong>Late+Early</strong></th>
                        <th class="text-center"><strong>Work+OD</strong><br><small>Within Shift</small></th>
                        <th class="text-center"><strong>paid</strong></th>
                        <th class="text-center"><strong>Grace</strong></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="text-center"><strong>Date</strong></th>
                        <th class="text-center"><strong>Day</strong></th>
                        <th class="text-center"><strong>Status</strong></th>
                        <th class="text-center"><strong>Shift</strong></th>
                        <th class="text-center"><strong>IN/OUT</strong></th>
                        <th class="text-center"><strong>Late IN</strong></th>
                        <th class="text-center"><strong>Early Out</strong></th>
                        <th class="text-center"><strong>Late+Early</strong></th>
                        <th class="text-center"><strong>Work+OD</strong><br><small>Within Shift</small></th>
                        <th class="text-center"><strong>paid</strong></th>
                        <th class="text-center"><strong>Grace</strong></th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="mb-5">
            <style>
                .dataTables_scrollBody {
                    max-height: 400px;
                }
            </style>
            <table id="leave_report_table" class="table table-striped table-row-bordered nowrap">
                <thead>
                    <tr>
                        <th class="text-center"><strong>Leave Date</strong></th>
                        <th class="text-center"><strong>Days</strong></th>
                        <th class="text-center"><strong>Day Type</strong></th>
                        <th class="text-center"><strong>Leave Code</strong></th>
                        <th class="text-center"><strong>Status</strong></th>
                        <th class="text-center"><strong>Reviewed By</strong></th>
                        <th class="text-center"><strong>Reviewed Date</strong></th>
                        <th class="text-center"><strong>Remarks</strong></th>
                        <th class="text-center"><strong>Address During Leave</strong></th>
                        <th class="text-center"><strong>Contact During Leave</strong></th>
                        <th class="text-center"><strong>Reason</strong></th>
                        <th class="text-center"><strong>Attachment</strong></th>
                        <th class="text-center"><strong>Requested Date Time</strong></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="text-center"><strong>Leave Date</strong></th>
                        <th class="text-center"><strong>Days</strong></th>
                        <th class="text-center"><strong>Day Type</strong></th>
                        <th class="text-center"><strong>Leave Code</strong></th>
                        <th class="text-center"><strong>Status</strong></th>
                        <th class="text-center"><strong>Reviewed By</strong></th>
                        <th class="text-center"><strong>Reviewed Date</strong></th>
                        <th class="text-center"><strong>Remarks</strong></th>
                        <th class="text-center"><strong>Address During Leave</strong></th>
                        <th class="text-center"><strong>Contact During Leave</strong></th>
                        <th class="text-center"><strong>Reason</strong></th>
                        <th class="text-center"><strong>Attachment</strong></th>
                        <th class="text-center"><strong>Requested Date Time</strong></th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="row mb-5">
            <style>
                .dataTables_scrollBody {
                    max-height: 400px;
                }
            </style>
            <div class="col-md-6">
                <table id="od_report_table_approved" class="table table-striped table-row-bordered nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Pre/Post</strong></th>
                            <th class="text-center"><strong>Estimated From</strong></th>
                            <th class="text-center"><strong>Estimated To</strong></th>
                            <th class="text-center"><strong>Actual From</strong></th>
                            <th class="text-center"><strong>Actual To</strong></th>
                            <th class="text-center"><strong>Hours</strong></th>
                            <th class="text-center"><strong>Duty Location</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date Time</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Updated Date Time</strong></th>
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Pre/Post</strong></th>
                            <th class="text-center"><strong>Estimated From</strong></th>
                            <th class="text-center"><strong>Estimated To</strong></th>
                            <th class="text-center"><strong>Actual From</strong></th>
                            <th class="text-center"><strong>Actual To</strong></th>
                            <th class="text-center"><strong>Hours</strong></th>
                            <th class="text-center"><strong>Duty Location</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date Time</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Updated Date Time</strong></th>
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table id="od_report_table_pending" class="table table-striped table-row-bordered nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Pre/Post</strong></th>
                            <th class="text-center"><strong>Estimated From</strong></th>
                            <th class="text-center"><strong>Estimated To</strong></th>
                            <th class="text-center"><strong>Actual From</strong></th>
                            <th class="text-center"><strong>Actual To</strong></th>
                            <th class="text-center"><strong>Hours</strong></th>
                            <th class="text-center"><strong>Duty Location</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date Time</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Updated Date Time</strong></th>
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Pre/Post</strong></th>
                            <th class="text-center"><strong>Estimated From</strong></th>
                            <th class="text-center"><strong>Estimated To</strong></th>
                            <th class="text-center"><strong>Actual From</strong></th>
                            <th class="text-center"><strong>Actual To</strong></th>
                            <th class="text-center"><strong>Hours</strong></th>
                            <th class="text-center"><strong>Duty Location</strong></th>
                            <th class="text-center"><strong>Assigned By</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed By</strong></th>
                            <th class="text-center"><strong>Reviewed Date Time</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Updated Date Time</strong></th>
                            <th class="text-center"><strong>Requested Date Time</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>



    <div class="col-lg-6 col-xxl-4 order-1 order-lg-2 ">

        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-7 d-flex flex-column">
                        <div class="symbol w-100px position-relative">
                            <?php
                            $avatar_url = '';
                            $attachment_json = session()->get('current_user')['attachment'];
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
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-white h-20px w-20px"></div>
                        </div>
                        <div class="border border-gray-400 border-dashed rounded py-3 px-4 mt-3">

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="fw-bold fs-6 text-gray-600">Code</div>
                                <div class="fw-bold fs-6 text-gray-900"><?php echo $current_user_data['internal_employee_id']; ?></div>
                            </div>

                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-2">
                            <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">
                                <?php echo trim($current_user_data['first_name'] . ' ' . $current_user_data['last_name']); ?>
                            </a>
                            <a href="#">
                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                    <i class="fa-solid fa-badge-check text-primary" style="font-size: 1.37rem;"></i>
                                </span>
                            </a>
                        </div>
                        <div class="d-flex flex-column fw-bold fs-6">
                            <?php
                            if (!empty($current_user_data['designation_name'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-duotone fa-circle-user text-hover-primary"></i>
                                    </span>
                                    <?php echo $current_user_data['designation_name']; ?>
                                </a>
                            <?php
                            }
                            if (!empty($current_user_data['department_name'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-solid fa-building-user text-hover-primary"></i>
                                    </span>
                                    <?php echo $current_user_data['department_name']; ?>
                                </a>
                            <?php
                            }
                            if (!empty($current_user_data['company_short_name'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-solid fa-house-building text-hover-primary"></i>
                                    </span>
                                    <?php echo $current_user_data['company_short_name']; ?>
                                </a>
                            <?php
                            }
                            if (!empty($current_user_data['desk_location'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-duotone fa-location-dot text-hover-primary"></i>
                                    </span>
                                    <?php echo $current_user_data['desk_location']; ?>
                                </a>
                            <?php
                            }
                            if (!empty($current_user_data['work_email'])) {
                            ?>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-duotone fa-envelope text-hover-primary"></i>
                                    </span>
                                    <?php echo $current_user_data['work_email']; ?>
                                </a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


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
        <div class="shadow-sm mb-5">
            <table id="leave_balance_current_month" class="table table-sm table-row-bordered">
                <thead>
                    <tr>
                        <th style="text-align: left"><strong>Type</strong></th>
                        <th style="text-align: right"><strong>Balance</strong></th>
                    </tr>
                </thead>
            </table>
            <!-- <table id="employee_holidays_table" class="table table-sm table-row-bordered table-striped">
                <thead>
                    <tr>
                        <th style="text-align: left"><strong>Date</strong></th>
                        <th style="text-align: left"><strong>Holiday</strong></th>
                        <th style="text-align: center"><strong>Type</strong></th>
                        <th style="text-align: center"><strong>Day</strong></th>
                    </tr>
                </thead>
            </table> -->
            <div id="_rh_dates">
                <input type="hidden" id="first_rh_date" value="" />
                <input type="hidden" id="second_rh_date" value="" />
            </div>
        </div>
        <div class="card shadow-sm mb-5">
            <div class="card-footer pb-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <button type="button" class="btn btn-sm btn-primary flex-grow-1 mb-2 mx-1" data-bs-toggle="modal" data-bs-target="#create_leave_request_modal" id="create_leave_request_button_trigger" disabled>
                        Please Wait
                    </button>

                    <button type="button" class="btn btn-sm btn-info flex-grow-1 mb-2 mx-1" data-bs-toggle="modal" data-bs-target="#create_od_request_modal">
                        <i class="fa fa-plus"></i> Request OD
                    </button>

                    <button type="button" class="btn btn-sm btn-warning text-dark flex-grow-1 mb-2 mx-1" data-bs-toggle="modal" data-bs-target="#comp_off_credit_request_modal">
                        <i class="fa fa-plus text-dark"></i> COMP OFF Credit Request
                    </button>
                    <button type="button" class="btn btn-sm btn-danger flex-grow-1 mb-2 mx-1" data-bs-toggle="modal" data-bs-target="#comp_off_minutes_utilization_request_modal" style="max-width: max-content;">
                        <i class="fa fa-plus"></i> Use Comp Off Minutes
                    </button>

                    <div class="modal fade" tabindex="-1" id="create_leave_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="create_leave_request" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Request New Leave</h5>
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row ">
                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">From Date</label>
                                                <div class="input-group">
                                                    <input type="text" id="from_date" class="leave-control form-control form-control-sm" name="from_date" placeholder="Pick a Date" value="<?= set_value('from_date') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                    <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="from_date_error"><?= isset($validation) ? display_error($validation, 'from_date') : '' ?></small>
                                            </div>

                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">To Date</label>
                                                <div class="input-group">
                                                    <input type="text" id="to_date" class="leave-control form-control form-control-sm" name="to_date" placeholder="Pick a Date" value="<?= set_value('to_date') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                    <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="to_date_error"><?= isset($validation) ? display_error($validation, 'to_date') : '' ?></small>
                                            </div>

                                            <div class="col-lg-3 mb-3">
                                                <label class="required form-label">Day Type</label>
                                                <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                    <label for="day_type_half_day" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                                        Half Day <input type="radio" name="day_type" class="opacity-1  day_type" id="day_type_half_day" value="0.5" style="width: 0px; opacity: 0;" />
                                                    </label>
                                                    <label for="day_type_full_day" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                                        Full Day <input type="radio" name="day_type" class="opacity-1  day_type" id="day_type_full_day" value="1" style="width: 0px; opacity: 0;" checked />
                                                    </label>
                                                    <a class="bg-info form-control form-control-sm p-0 position-absolute" style="opacity: 0.5"></a>
                                                </div>
                                                <span class="text-danger error-text" id="type_of_leave_error"><?= isset($validation) ? display_error($validation, 'type_of_leave') : '' ?></span>
                                            </div>

                                            <div class="col-lg-3 mb-3">
                                                <label class="form-label">Days</label>
                                                <input type="text" class="form-control form-control-sm leave_request_number_of_days" id="number_of_days" name="number_of_days" placeholder="--" />
                                                <strong class="w-100 text-success included-rh-days"></strong>
                                                <small class="w-100 text-danger error-text" id="number_of_days_error"><?= isset($validation) ? display_error($validation, 'number_of_days') : '' ?></small>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="required form-label">Type of leave</label>
                                                <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                    <?php if ($current_user_data['el_allowed'] == 'yes') { ?>
                                                        <label for="type_of_leave_el" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                                            EL <input type="radio" name="type_of_leave" class="opacity-0 position-absolute type_of_leave" id="type_of_leave_el" value="EL" />
                                                        </label>
                                                    <?php } ?>
                                                    <?php if ($current_user_data['cl_allowed'] == 'yes') { ?>
                                                        <label for="type_of_leave_cl" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                                            CL <input type="radio" name="type_of_leave" class="opacity-0 position-absolute type_of_leave" id="type_of_leave_cl" value="CL" checked />
                                                        </label>
                                                    <?php } ?>
                                                    <?php if ($current_user_data['co_allowed'] == 'yes') { ?>
                                                        <label for="type_of_leave_comp_off" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                                            COMP OFF <input type="radio" name="type_of_leave" class="opacity-0 position-absolute type_of_leave" id="type_of_leave_comp_off" value="COMP OFF" />
                                                        </label>
                                                    <?php } ?>
                                                    <?php if ($current_user_data['sl_allowed'] == 'yes') { ?>
                                                        <label for="type_of_leave_sick_leave" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                                            SICK LEAVE <input type="radio" name="type_of_leave" class="opacity-0 position-absolute type_of_leave" id="type_of_leave_sick_leave" value="SICK LEAVE" />
                                                        </label>
                                                    <?php } ?>
                                                    <a class="bg-danger form-control form-control-sm p-0 position-absolute"></a>
                                                </div>
                                                <span class="text-danger error-text" id="type_of_leave_error">
                                                    <?= isset($validation) ? display_error($validation, 'type_of_leave') : '' ?>
                                                </span>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Emergency Contact During Leave</label>
                                                <input type="text" id="emergency_contact_d_l" name="emergency_contact_d_l" class="form-control form-control-sm" placeholder="Emergency Contact During Leave" value="<?= set_value('emergency_contact_d_l') ?>" />
                                                <small class="text-danger error-text" id="emergency_contact_d_l_error"><?= isset($validation) ? display_error($validation, 'emergency_contact_d_l') : '' ?></small>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Address During Leave</label>
                                                <input type="text" id="address_d_l" name="address_d_l" class="form-control form-control-sm" placeholder="Address During Leave" value="<?= set_value('address_d_l') ?>" />
                                                <small class="text-danger error-text" id="address_d_l_error"><?= isset($validation) ? display_error($validation, 'address_d_l') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Reason of Leave</label>
                                                <input type="text" id="reason_of_leave" name="reason_of_leave" class="form-control form-control-sm" placeholder="Reason of Leave" value="<?= set_value('reason_of_leave') ?>" />
                                                <small class="text-danger error-text" id="reason_of_leave_error"><?= isset($validation) ? display_error($validation, 'reason_of_leave') : '' ?></small>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Attachment</label><br>
                                                <div id="attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                    <div class="image-input-wrapper w-125px h-125px">
                                                        <a class="d-none w-100 h-100 overlay preview-button" data-bs-target="#leave_attachment_lightbox" data-bs-toggle="modal">
                                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-3x"></i></div>
                                                        </a>
                                                    </div>
                                                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Change Attachment">
                                                        <i class="bi bi-pencil-fill fs-7"></i>
                                                        <input type="file" id="attachment" name="attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                        <input type="hidden" name="attachment_remove" />
                                                    </label>
                                                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancel Attachment">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Remove Attachment">
                                                        <i class="bi bi-x fs-2"></i>
                                                    </span>
                                                </div>
                                                <br>
                                                <small class="text-danger error-text" id="attachment_error"><?= isset($validation) ? display_error($validation, 'attachment') : '' ?></small>
                                            </div>
                                            <div class="col-lg-8 mb-3 d-flex flex-column justify-content-center">
                                                <small class="mb-3">Comp Off Leave request will be approved by HR only</small>
                                                <small>Unpaid leave has been removed, Employees who doesn't have EL/CL/CompOff balance can contact HR</small>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <input type="hidden" id="create_leave_request_submit_field" name="create_leave_request_submit_field" value="Add" />
                                        <button id="create_leave_request_submit_button" class="btn btn-sm btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="create_od_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="create_od_request" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">New OD Request <?= session()->get('current_user')['employee_id'] ?></h5>

                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>

                                    </div>

                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-lg-5 mb-3">
                                                <label class="form-label">Estimated From</label>
                                                <div class="input-group">
                                                    <span class="form-control p-0 border-0 flatpicker-wrapper-parent">
                                                        <input type="text" id="estimated_from_date_time" class="form-control od-control form-control-sm" name="estimated_from_date_time" placeholder="Pick a Date" value="<?= set_value('estimated_from_date_time') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                    </span>
                                                    <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="estimated_from_date_time_error"><?= isset($validation) ? display_error($validation, 'estimated_from_date_time') : '' ?></small>
                                            </div>
                                            <div class="col-lg-5 mb-3">
                                                <label class="form-label">Estimated To</label>
                                                <div class="input-group">
                                                    <span class="form-control p-0 border-0 flatpicker-wrapper-parent">
                                                        <input type="text" id="estimated_to_date_time" class="form-control od-control form-control-sm" name="estimated_to_date_time" placeholder="Pick a Date" value="<?= set_value('estimated_to_date_time') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                    </span>
                                                    <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="estimated_to_date_time_error"><?= isset($validation) ? display_error($validation, 'estimated_to_date_time') : '' ?></small>
                                            </div>

                                            <div class="col-lg-2 mb-3">
                                                <label class="form-label">Hours</label>
                                                <input type="text" class="form-control form-control-sm" id="hours_od" placeholder="--:--" disabled />
                                            </div>

                                            <div class="col-lg-5 mb-3">
                                                <label class="form-label">International</label>
                                                <select class="form-select form-select-sm" id="international" name="international" data-control="select2" data-placeholder="Select Yes / No" data-allow-clear="true">
                                                    <option></option>
                                                    <option value="yes" <?= edit_set_select('international', 'yes', 'no') ?>>Yes</option>
                                                    <option value="no" <?= edit_set_select('international', 'yes', 'no') ?>>No</option>
                                                </select>
                                                <small class="text-danger error-text" id="international_error"><?= isset($validation) ? display_error($validation, 'international') : '' ?></small>
                                            </div>

                                            <div class="col-lg-7 mb-3">
                                                <label class="form-label">Duty Location</label>
                                                <input type="text" id="duty_location" name="duty_location" class="form-control form-control-sm" placeholder="Duty Location" value="<?= set_value('duty_location') ?>" />
                                                <small class="text-danger error-text" id="duty_location_error"><?= isset($validation) ? display_error($validation, 'duty_location') : '' ?></small>
                                            </div>
                                            <div class="col-lg-5 mb-3">
                                                <label class="form-label">Assigned by</label>
                                                <select class="form-select form-select-sm" id="duty_assigner" name="duty_assigner" data-control="select2" data-placeholder="Assigned By" data-allow-clear="true">
                                                    <option></option>
                                                    <?php
                                                    foreach ($employees as $employee) {
                                                    ?>
                                                        <option value="<?php echo $employee['id']; ?>" <?= edit_set_select('duty_assigner', $employee['id'], $current_user_data['id']) ?>>
                                                            <?php echo ($employee['id'] == $current_user_data['id']) ? 'Self' : trim($employee['first_name'] . ' ' . $employee['last_name']) . ' (' . $employee['internal_employee_id'] . ') ' . $employee['department_name'] . '-' . $employee['company_short_name']; ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-danger error-text" id="duty_assigner_error"><?= isset($validation) ? display_error($validation, 'duty_assigner') : '' ?></small>
                                            </div>
                                            <div class="col-lg-7 mb-3">
                                                <label class="form-label">Reason</label>
                                                <input type="text" id="reason" name="reason" class="form-control form-control-sm" placeholder="Reason" value="<?= set_value('reason') ?>" />
                                                <small class="text-danger error-text" id="reason_error"><?= isset($validation) ? display_error($validation, 'reason') : '' ?></small>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <input type="hidden" id="create_od_request_submit_field" name="create_od_request_submit_field" value="Add" />
                                        <button id="create_od_request_submit_button" class="btn btn-sm btn-primary">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="create_gate_pass_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="create_gate_pass_request" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Request For Gate Pass</h5>

                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>

                                    </div>

                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label mb-0">Type</label>
                                                <br><small class="text-muted mb-1">( Only Early Going option is available )</small>
                                                <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                                    <label for="gate_pass_type_early_going" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                                        Early Going <input type="radio" name="gate_pass_type" class="opacity-0 position-absolute" id="gate_pass_type_early_going" value="Early Going" checked />
                                                    </label>
                                                    <a class="bg-danger form-control form-control-sm p-0 position-absolute"></a>
                                                </div>
                                                <small class="text-danger error-text" id="gate_pass_type_error"><?= isset($validation) ? display_error($validation, 'gate_pass_type') : '' ?></small>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label mb-0">Date</label>
                                                <br><small class="text-muted mb-1">Date is fixed and can not be changed</small>
                                                <div class="input-group">
                                                    <span class="form-control p-0 border-0">
                                                        <input type="text" id="gate_pass_date" class="form-control form-control-sm" name="gate_pass_date" placeholder="Pick a Date" value="<?php echo date('Y-m-d'); ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" readonly>
                                                    </span>
                                                    <span class="input-group-text input-group-solid">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="gate_pass_date_error"><?= isset($validation) ? display_error($validation, 'gate_pass_date') : '' ?></small>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label mb-0">Time (Estd)</label>
                                                <br><small class="text-muted mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 0.85;">(Select the estimated time)</small>
                                                <div class="input-group">
                                                    <span class="form-control p-0 border-0 flatpicker-wrapper-parent">
                                                        <input type="text" id="gate_pass_hours" class="form-control form-control-sm" name="gate_pass_hours" placeholder="Estimated Time" value="<?= set_value('gate_pass_hours') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                    </span>
                                                    <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="gate_pass_hours_error"><?= isset($validation) ? display_error($validation, 'gate_pass_hours') : '' ?></small>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Reason</label>
                                                <input type="text" id="reason" name="reason" class="form-control " placeholder="Reason" value="<?= set_value('reason') ?>" />
                                                <small class="text-danger error-text" id="reason_error"><?= isset($validation) ? display_error($validation, 'reason') : '' ?></small>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <button id="create_gate_pass_request_submit_button" class="btn btn-sm btn-primary">Inform Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="comp_off_credit_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="comp_off_credit_request" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">COMP OFF Credit Request</h5>
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-lg-6 d-flex flex-column mb-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Date</label>
                                                    <div class="input-group">
                                                        <span class="form-control p-0 border-0 flatpicker-wrapper-parent">
                                                            <input type="text" id="comp_off_credit_request_date" class="form-control cocr-control form-control-sm" name="comp_off_credit_request_date" placeholder="Pick a Date" value="<?= set_value('comp_off_credit_request_date') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                        </span>
                                                        <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <small class="text-danger error-text" id="comp_off_credit_request_date_error"><?= isset($validation) ? display_error($validation, 'comp_off_credit_request_date') : '' ?></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Assigned by</label>
                                                    <select class="form-select form-select-sm cocr-control" id="comp_off_credit_request_duty_assigner" name="comp_off_credit_request_duty_assigner" data-control="select2" data-placeholder="Assigned By" data-allow-clear="true">
                                                        <option></option>
                                                        <?php
                                                        foreach ($employees as $employee) {
                                                        ?>
                                                            <option value="<?php echo $employee['id']; ?>" <?= edit_set_select('comp_off_credit_request_duty_assigner', $employee['id'], $current_user_data['id']) ?>>
                                                                <?php echo ($employee['id'] == $current_user_data['id']) ? 'Self' : trim($employee['first_name'] . ' ' . $employee['last_name']) . ' (' . $employee['internal_employee_id'] . ') ' . $employee['department_name'] . '-' . $employee['company_short_name']; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <small class="text-danger error-text" id="comp_off_credit_request_duty_assigner_error"><?= isset($validation) ? display_error($validation, 'comp_off_credit_request_duty_assigner') : '' ?></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Detailed information</label>
                                                    <input type="text" id="comp_off_credit_request_reason" name="comp_off_credit_request_reason" class="form-control form-control-sm cocr-control" placeholder="Please specify details" value="<?= set_value('comp_off_credit_request_reason') ?>" />
                                                    <small class="text-danger error-text" id="comp_off_credit_request_reason_error"><?= isset($validation) ? display_error($validation, 'comp_off_credit_request_reason') : '' ?></small>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Attachment</label><br>
                                                    <div id="compoff_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                        <div class="image-input-wrapper w-125px h-125px">
                                                            <a class="d-none w-100 h-100 overlay preview-button" data-bs-target="#compoff_attachment_lightbox" data-bs-toggle="modal" id="compoff_attachment_lightbox_toggle">
                                                                <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-3x"></i></div>
                                                            </a>
                                                        </div>
                                                        <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Change Attachment">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <input type="file" id="compoff_attachment" name="compoff_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                            <input type="hidden" name="compoff_attachment_remove" />
                                                        </label>
                                                        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancel Attachment">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Remove Attachment">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>

                                                    </div>
                                                    <br>
                                                    <small class="text-danger error-text" id="compoff_attachment_error"><?= isset($validation) ? display_error($validation, 'compoff_attachment') : '' ?></small>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <div id="comp_off_credit_request_working_details">

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <button id="comp_off_credit_request_submit_button" class="btn btn-sm btn-primary">Request Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="comp_off_minutes_utilization_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="comp_off_minutes_utilization_form" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Comp Off Utilization Request</h5>
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-lg-6 d-flex flex-column mb-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Date</label>
                                                    <div class="input-group">
                                                        <span class="form-control p-0 border-0 flatpicker-wrapper-parent">
                                                            <input type="text" id="comp_off_minutes_utilization_date" class="form-control form-control-sm" name="comp_off_minutes_utilization_date" placeholder="Pick a Date" value="<?= set_value('comp_off_minutes_utilization_date') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                        </span>
                                                        <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <small class="text-danger error-text" id="comp_off_minutes_utilization_date_error"><?= isset($validation) ? display_error($validation, 'comp_off_minutes_utilization_date') : '' ?></small>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex flex-column mb-3">
                                                <label class="form-label">Minutes:</label>
                                                <input class="form-control form-control-sm form-control-solid" id="comp_off_minutes_utilization_minutes" name="comp_off_minutes_utilization_minutes" data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="__:__" style="width: 65px;" />
                                                <span class="text-muted d-block">Example: 00:20</span>
                                                <small class="text-danger error-text" id="comp_off_minutes_utilization_minutes_error"><?= isset($validation) ? display_error($validation, 'comp_off_minutes_utilization_minutes') : '' ?></small>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <button id="comp_off_minutes_utilization_submit_button" class="btn btn-sm btn-primary">Utilize Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="compoff_attachment_lightbox" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="compoff_attachment_lightbox_toggle" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-toggle="modal" href="#comp_off_credit_request_modal" role="button"></button>
                                </div>
                                <div class="modal-body" style="min-height: 70vh;">
                                    <iframe id="compoff_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="leave_attachment_lightbox" data-bs-backdrop="static" aria-hidden="true" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-toggle="modal" href="#create_leave_request_modal" role="button"></button>
                                </div>
                                <div class="modal-body" style="min-height: 70vh;">
                                    <iframe id="leave_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="shadow-sm mb-5">
            <table id="employee_holidays_table" class="table table-sm table-row-bordered table-striped">
                <thead>
                    <tr>
                        <th style="text-align: left"><strong>Date</strong></th>
                        <th style="text-align: left"><strong>Holiday</strong></th>
                        <th style="text-align: center"><strong>Type</strong></th>
                        <th style="text-align: center"><strong>Day</strong></th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="shadow-sm mb-5">
            <table id="leave_balance_next_month" class="table table-sm table-row-bordered">
                <thead>
                    <tr>
                        <th style="text-align: left"><strong>Type</strong></th>
                        <th style="text-align: center"><strong>Estimated</strong></th>
                        <th style="text-align: right"><strong>Eligible</strong></th>
                    </tr>
                </thead>
            </table>
        </div>

        <?php
        if (in_array(session()->get('current_user')['role'], ['superuser', 'hr'])) {
        ?>
            <div class="shadow-sm mb-5">
                <table id="probation_ended" class="table table-sm table-striped table-row-bordered nowrap" style="font-size: 0.75rem !important">
                    <thead>
                        <tr>
                            <th style="text-align: left"><strong>Employee</strong></th>
                            <th style="text-align: center"><strong>D.O.J</strong></th>
                            <th style="text-align: center"><strong>Type</strong></th>
                            <th style="text-align: right"><strong></strong></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php
        }
        ?>

        <?php
        if (in_array(session()->get('current_user')['role'], ['superuser', 'hr'])) {
        ?>
            <div class="shadow-sm mb-5">
                <table id="recently_joined" class="table table-sm table-striped table-row-bordered nowrap" style="font-size: 0.75rem !important">
                    <thead>
                        <tr>
                            <th style="text-align: left"><strong>Employee</strong></th>
                            <th style="text-align: center"><strong>D.O.J</strong></th>
                            <th style="text-align: right"><strong></strong></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php
        }
        ?>

        <div class="shadow-sm mb-5">
            <table id="upcoming_birthdays" class="table table-sm table-striped table-row-bordered nowrap" style="font-size: 0.75rem !important">
                <thead>
                    <tr>
                        <th style="text-align: left"><strong>Employee</strong></th>
                        <th style="text-align: center"><strong>Birthday</strong></th>
                        <th style="text-align: right"><strong>When</strong></th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>

<div class="modal fade" id="probationNotificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="probationNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="probationNotificationModalLabel">
                    🎉 Probation Completion 🎉
                </h5>
            </div>
            <div class="modal-body">
                <div class="celebration-icon">🎊</div>
                <p><strong>Congratulations!</strong><br> You are now a confirmed employee of <strong><?= $current_user_data['company_name'] ?></strong>.</p>
                <p class="text-muted">Please collect your confirmation letter from the HR Department.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-success" id="acknowledgeProbationBtn">
                    <i class="fa-solid fa-check"></i> Acknowledge
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="jobListingNotificationModal" tabindex="-1" aria-labelledby="jobListingNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobListingNotificationModalLabel">Pending Job Approvals</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The following job listings require your attention:</p>
                <ul class="list-group" id="pending-jobs-list">
                    <!-- Job items will be inserted here by JavaScript -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?= base_url('/recruitment/job-listing/all') ?>" class="btn btn-primary">View All Listings</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="announcementPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="announcementPopupLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="announcementPopupLabel" style="width: 100%; text-align: center; font-size: 22px; color: red;">Important Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; text-align: center; border: none; background: transparent;">
                    <i class="fa fa-times fa-2x"></i>
                </button>
            </div>
            <div class="modal-body">

                <div class="alert alert-danger d-flex" style="font-size: 17px;">
                    <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"></path>
                            <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="black"></path>
                        </svg>
                    </span>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger" style="font-size: 22px;">Attendance not updating</h4>
                        <p class="mb-1">This is to inform all employees that:</p>
                        <p class="mb-1">Due to a server issue, attendance is not updating in the system.</p>
                        <p class="mb-1">We apologize for the inconvenience.</p>
                        <p class="mb-1">Please continue punching in the machine as usual to ensure your attendance reflects correctly once the issue is resolved.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- <div class="modal fade" id="employeeNotificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="employeeNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="employeeNotificationModalLabel" style="text-align: center;">
                    <span id="notification-icon" class="me-2"></span><span id="notification-title"></span>
                </h5>
            </div>
            <div class="modal-body" style="text-align: center;">
                <div class="celebration-icon" id="notification-emoji" style="font-size: 3rem; margin-bottom: 15px;"></div>
                <div class="mb-3">
                    <span class="badge badge-light-primary" id="notification-type" style="font-size: 0.9rem; padding: 6px 12px;"></span>
                    <span class="text-muted ms-3" id="notification-event-date" style="font-size: 0.9rem;"></span>
                </div>
                <div class="separator separator-dashed my-4"></div>
                <div class="notification-description" id="notification-description" style="white-space: pre-wrap; font-size: 1rem; line-height: 1.6; text-align: left; padding: 0 20px;"></div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-lg btn-success" id="mark-as-read-btn">
                    <i class="fa fa-check me-2"></i>Acknowledge
                </button>
            </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="employeeNotificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered notif-dialog">
        <div class="modal-content notif-glass-card">
            <div class="row g-0 notif-card-row">

                <!-- ── LEFT PANEL ── -->
                <div class="col-5 d-flex flex-column align-items-center justify-content-center text-center notif-left-panel" id="notif-left-panel">

                    <!-- Photo with glow halo -->
                    <div id="notification-employee-image" class="notif-photo-wrapper" style="display: none;">
                        <div class="notif-photo-halo" id="notif-photo-halo"></div>
                        <div class="notif-photo-frame">
                            <img id="notification-emp-img" src="" alt="Employee" class="notif-emp-img">
                        </div>
                        <div class="notif-float-badge">
                            <i id="notif-badge-icon" class="bi bi-stars"></i>
                        </div>
                    </div>

                    <!-- Emoji fallback -->
                    <div id="notification-emoji" class="notif-emoji-fallback"></div>

                    <!-- "Happy\nBirthday" text block -->
                    <div class="notif-event-text mt-2">
                        <div id="notif-label-top" class="notif-label-top"></div>
                        <div id="notif-styled-event" class="notif-styled-event"></div>
                    </div>

                    <!-- "Today we celebrate Ayush" -->
                    <p id="notif-celebrating" class="notif-celebrating" style="display: none;">
                        Celebrating <strong id="notif-first-name"></strong>
                    </p>
                </div>

                <!-- ── RIGHT PANEL ── -->
                <div class="col-7 d-flex flex-column justify-content-between notif-right-panel">
                    <div>
                        <!-- Type badge -->
                        <div class="mb-3">
                            <span id="notification-type" class="notif-type-badge"></span>
                        </div>

                        <!-- Name / title -->
                        <h4 id="notification-title" class="notif-name-title mb-1"></h4>

                        <!-- Designation • date -->
                        <div id="notification-subtitle" class="notif-subtitle mb-4"></div>

                        <!-- Quote / description -->
                        <div id="notification-description" class="notif-description"></div>

                        <!-- Contact grid -->
                        <div id="notification-employee-details" style="display: none;">
                            <div class="row g-0 notif-contact-grid">
                                <div class="col-6 notif-contact-item" id="notif-row-mobile" style="display: none;">
                                    <p class="notif-contact-label">Mobile Number</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-phone"></i><span id="notif-mobile"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item" id="notif-row-email" style="display: none;">
                                    <p class="notif-contact-label">Work Email</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-envelope"></i><span id="notif-email" class="notif-email-truncate"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item" id="notif-row-ext" style="display: none;">
                                    <p class="notif-contact-label">Extension</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-telephone"></i><span id="notif-ext"></span>
                                    </div>
                                </div>
                                <div class="col-6 notif-contact-item" id="notif-row-emp-code" style="display: none;">
                                    <p class="notif-contact-label">Employee ID</p>
                                    <div class="notif-contact-value">
                                        <i class="bi bi-fingerprint"></i><span id="notif-emp-code"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date (non-employee notifications) -->
                        <div id="notification-event-date" class="notif-date-only"></div>
                    </div>

                    <!-- Action button -->
                    <div class="notif-btn-wrap">
                        <button type="button" id="mark-as-read-btn"
                            class="btn notif-action-btn w-100 d-flex align-items-center justify-content-center gap-2">
                            <span id="notif-btn-text">Acknowledge</span>
                            <i id="notif-btn-icon" class="bi bi-check-circle"></i>
                        </button>
                        <p id="notif-event-date-label" class="notif-footer-text text-center mt-3 mb-0"></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .modal-fullscreen .modal-content {
        height: max-content;
        border: 0;
        border-radius: 0;
        max-width: 90vw;
        max-height: 90vh;
        margin-left: auto;
        margin-right: auto;
    }

    .notif-dialog {
        max-width: 760px;
    }

    .notif-glass-card {
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.45) !important;
        border-radius: 40px !important;
        overflow: hidden !important;
        box-shadow: 0 32px 64px -16px rgba(0, 0, 0, 0.13) !important;
    }

    .notif-card-row {
        min-height: 500px;
    }

    /* ── LEFT PANEL ── */
    .notif-left-panel {
        background: #eef1f8;
        border-right: 1px solid #dde2ec;
        padding: 3rem 2rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Photo wrapper */
    .notif-photo-wrapper {
        position: relative;
        width: 180px;
        margin-bottom: 2rem;
    }

    /* Gradient glow behind photo (set via JS per event type) */
    .notif-photo-halo {
        position: absolute;
        inset: -18px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #3b82f6, #8b5cf6);
        opacity: 0.18;
        transition: opacity 0.4s ease;
    }

    .notif-photo-wrapper:hover .notif-photo-halo {
        opacity: 0.34;
    }

    /* Circular photo frame */
    .notif-photo-frame {
        position: relative;
        z-index: 1;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 7px solid #fff;
        box-shadow: 0 20px 48px rgba(0, 0, 0, 0.14);
        overflow: hidden;
    }

    .notif-emp-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .notif-photo-wrapper:hover .notif-emp-img {
        transform: scale(1.07);
    }

    /* Floating badge (bottom-right of photo) — neumorphic style */
    .notif-float-badge {
        position: absolute;
        bottom: -4px;
        right: -4px;
        z-index: 2;
        width: 46px;
        height: 46px;
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.13);
        font-size: 1.4rem;
        line-height: 1;
    }

    /* Emoji fallback — neumorphic circle matching photo wrapper style */
    .notif-emoji-fallback {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: #eef1f8;
        box-shadow: 16px 16px 36px rgba(163, 177, 198, 0.55),
            -16px -16px 36px rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        line-height: 1;
        margin-bottom: 1.25rem;
    }

    /* "Happy" plain text */
    .notif-label-top {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
    }

    /* "Birthday" / "Anniversary" — Playfair Display italic gradient */
    .notif-styled-event {
        font-family: 'Playfair Display', Georgia, serif;
        font-style: italic;
        font-size: 3.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.1;
        margin-bottom: 0.65rem;
    }

    /* "Today we celebrate Name" */
    .notif-celebrating {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.05rem;
        font-weight: 300;
        color: #475569;
        letter-spacing: -0.01em;
        margin-top: 2px;
    }

    .notif-celebrating strong {
        font-weight: 700;
        color: #0f172a;
    }

    /* ── RIGHT PANEL ── */
    .notif-right-panel {
        background: rgba(255, 255, 255, 0.25);
        padding: 2.5rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Type badge */
    .notif-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 0.625rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        /* color set via JS */
    }

    /* Name heading */
    .notif-name-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    /* Designation • Date subtitle */
    .notif-subtitle {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: #64748b;
        flex-wrap: wrap;
    }

    .notif-subtitle-dot {
        width: 5px;
        height: 5px;
        background: #cbd5e1;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .notif-subtitle strong {
        font-weight: 600;
        color: #334155;
    }

    /* Description / quote box */
    .notif-description {
        background: rgba(255, 255, 255, 0.55);
        border: 1px solid rgba(255, 255, 255, 0.85);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        font-style: italic;
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.75;
        white-space: pre-wrap;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        margin-bottom: 0;
    }

    /* Contact grid */
    .notif-contact-grid {
        margin-top: 1.25rem;
    }

    .notif-contact-item {
        padding: 0.6rem 0;
    }

    .notif-contact-label {
        font-size: 0.6rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        margin-bottom: 5px;
    }

    .notif-contact-value {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
    }

    .notif-contact-value i {
        color: #94a3b8;
        font-size: 1rem;
        flex-shrink: 0;
        transition: color 0.2s;
    }

    .notif-contact-item:hover .notif-contact-value i {
        color: #10b981;
    }

    .notif-email-truncate {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }

    /* Date only (non-employee) */
    .notif-date-only {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 0.5rem;
    }

    /* Button area */
    .notif-btn-wrap {
        margin-top: 2rem;
    }

    .notif-action-btn {
        padding: 1.1rem 1.5rem !important;
        background: #0f172a !important;
        color: #fff !important;
        border: none !important;
        border-radius: 18px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.18);
        transition: all 0.3s ease;
    }

    .notif-action-btn:hover {
        background: #1e293b !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.22);
    }

    .notif-action-btn:active {
        transform: translateY(0);
    }

    /* Footer text */
    .notif-footer-text {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    /* Responsive — stack on small screens */
    @media (max-width: 600px) {
        .notif-card-row {
            flex-direction: column;
        }

        #employeeNotificationModal .col-5,
        #employeeNotificationModal .col-7 {
            width: 100% !important;
            max-width: 100% !important;
        }

        .notif-left-panel {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 2rem;
        }

        .notif-styled-event {
            font-size: 2.5rem !important;
        }

        .notif-photo-wrapper,
        .notif-photo-frame {
            width: 140px;
            height: 140px;
        }
    }
</style>

<div class="modal fade" id="probationConfirmationModal" tabindex="-1" aria-labelledby="probationConfirmationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="probationConfirmationModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Probation Confirmation Required
                </h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pc-record-id" value="">

                <!-- Employee Details Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Employee Information</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Name:</strong></div>
                            <div class="col-md-8" id="pc-employee-name"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Department:</strong></div>
                            <div class="col-md-8" id="pc-department"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Designation:</strong></div>
                            <div class="col-md-8" id="pc-designation"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Joining Date:</strong></div>
                            <div class="col-md-8" id="pc-joining-date"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Probation Period:</strong></div>
                            <div class="col-md-8" id="pc-probation-period"></div>
                        </div>
                        <!-- <div class="row mb-2">
                            <div class="col-md-4"><strong>Probation End Date:</strong></div>
                            <div class="col-md-8" id="pc-probation-end-date"></div>
                        </div> -->
                    </div>
                </div>

                <!-- Confirmation Details Card -->
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Confirmation Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Confirmed By (HOD):</strong></div>
                            <div class="col-md-8" id="pc-hod-name"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Confirmation Date:</strong></div>
                            <div class="col-md-8" id="pc-confirmation-date"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="pc-remind-later-btn">
                    <i class="bi bi-clock-history me-1"></i>Remind me later
                </button>
                <button type="button" class="btn btn-success" id="pc-confirmed-btn">
                    <i class="bi bi-check-circle me-1"></i>Confirmed
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        // Check for employee notifications 30 seconds after page load
        setTimeout(function() {
            checkForEmployeeNotifications();
        }, 10000);

        function checkForEmployeeNotifications() {
            $.ajax({
                url: '<?= base_url("ajax/notifications/dashboard") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.notifications && response.notifications.length > 0) {
                        showEmployeeNotificationModal(response.notifications[0]);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching notifications:', error);
                }
            });
        }

        function showEmployeeNotificationModal(notification) {
            const titleLower = (notification.title || '').toLowerCase();
            const isBirthday = titleLower.includes('birthday');
            const isAnniversary = titleLower.includes('anniversary') || titleLower.includes('wedding');

            // ── Left panel — update photo halo gradient per event type ──────
            var haloGradient;
            if (isBirthday) {
                haloGradient = 'linear-gradient(135deg, #f43f5e, #fb923c, #fbbf24)';
            } else if (isAnniversary) {
                haloGradient = 'linear-gradient(135deg, #10b981, #3b82f6, #8b5cf6)';
            } else {
                haloGradient = 'linear-gradient(135deg, #3b82f6, #8b5cf6)';
            }
            $('#notif-photo-halo').css('background', haloGradient);

            if (notification.related_employee_id) {
                // Show photo or hide it (emoji hidden for employee notifications)
                $('#notification-emoji').hide();
                if (notification.employee_image) {
                    $('#notification-emp-img').attr('src', notification.employee_image);
                    $('#notification-employee-image').show();
                } else {
                    $('#notification-employee-image').hide();
                }

                // Badge icon on photo (Bootstrap Icons)
                const badgeIcon = isBirthday ? 'bi-stars' : (isAnniversary ? 'bi-stars' : 'bi-bell');
                const badgeColor = isBirthday ? '#e91e8c' : (isAnniversary ? '#6c3fc5' : '#1976d2');
                $('#notif-badge-icon').attr('class', 'bi ' + badgeIcon).css('color', badgeColor);

                // Styled text
                if (isBirthday) {
                    $('#notif-label-top').text('Happy');
                    $('#notif-styled-event').text('Birthday!');
                } else if (isAnniversary) {
                    $('#notif-label-top').text('Happy');
                    $('#notif-styled-event').text('Anniversary!');
                } else {
                    $('#notif-label-top').text('');
                    $('#notif-styled-event').text('');
                }

                // "Celebrating [FirstName]"
                if (notification.employee_first_name) {
                    $('#notif-first-name').text(notification.employee_first_name);
                    $('#notif-celebrating').show();
                } else {
                    $('#notif-celebrating').hide();
                }
            } else {
                // Non-employee notification — show emoji, hide photo
                $('#notification-employee-image').hide();
                $('#notif-celebrating').hide();
                var emojiMap = {
                    'event': '🎉',
                    'reminder': '⏰',
                    'alert': '⚠️',
                    'announcement': '📢',
                    'policy': '📋',
                    'other': '💬'
                };
                $('#notification-emoji')
                    .text(emojiMap[notification.notification_type] || '💬')
                    .show();
                $('#notif-label-top').text('');
                $('#notif-styled-event').text(
                    notification.notification_type.charAt(0).toUpperCase() +
                    notification.notification_type.slice(1)
                );
            }

            // ── Right panel ─────────────────────────────────────────────────
            var formattedDate = formatNotificationDate(notification.event_date);
            var ordDate = notification.event_date ?
                new Date(notification.event_date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) :
                '';

            // Badge — type + date e.g. "Event on February 24, 2026"
            var badgeColors = {
                'event': 'background: #dbeafe; color: #2563eb;',
                'reminder': 'background: #fef3c7; color: #d97706;',
                'alert': 'background: #fee2e2; color: #dc2626;',
                'announcement': 'background: #d1fae5; color: #059669;',
                'policy': 'background: #f1f5f9; color: #475569;',
                'other': 'background: #ede9fe; color: #7c3aed;'
            };
            var badgeStyle = badgeColors[notification.notification_type] || badgeColors['other'];
            var typeWord = notification.notification_type.charAt(0).toUpperCase() + notification.notification_type.slice(1);
            var badgeLabel = ordDate ? (typeWord) : typeWord;
            $('#notification-type').attr('style',
                'font-size: 0.68rem; padding: 5px 14px; border-radius: 20px; letter-spacing: 0.5px; font-weight: 700; ' + badgeStyle
            ).text(badgeLabel);

            // Title & subtitle
            if (notification.related_employee_id && notification.employee_name) {
                $('#notification-title').text(notification.employee_name);

                // Build "Designation • Department - Company"
                var deptCompany = [
                    notification.employee_department || '',
                    notification.employee_company || ''
                ].filter(Boolean).join(' - ');

                var subtitleHtml = '';
                if (notification.employee_designation) {
                    subtitleHtml += '<strong>' + notification.employee_designation + '</strong>';
                }
                if (deptCompany) {
                    if (subtitleHtml) subtitleHtml += ' <span class="notif-subtitle-dot"></span> ';
                    subtitleHtml += deptCompany;
                }
                $('#notification-subtitle').html(subtitleHtml);
            } else {
                $('#notification-title').text(notification.title);
                $('#notification-subtitle').text(formattedDate);
            }

            // Description
            $('#notification-description').text(notification.description);

            // Contact grid
            if (notification.related_employee_id) {
                function setDetailRow(rowId, cellId, value) {
                    if (value) {
                        $('#' + cellId).text(value);
                        $('#' + rowId).show();
                    } else {
                        $('#' + rowId).hide();
                    }
                }
                setDetailRow('notif-row-mobile', 'notif-mobile', notification.employee_mobile);
                setDetailRow('notif-row-email', 'notif-email', notification.employee_email);
                setDetailRow('notif-row-ext', 'notif-ext', notification.employee_extension);
                setDetailRow('notif-row-emp-code', 'notif-emp-code', notification.employee_code);
                $('#notification-employee-details').show();
                $('#notification-event-date').text('');
            } else {
                $('#notification-employee-details').hide();
                $('#notification-event-date').text('Date: ' + formatNotificationDate(notification.event_date));
            }

            // Button
            var currentEmployeeId = '<?= session()->get('current_user')['employee_id'] ?>';
            var isOwnEvent = notification.related_employee_id &&
                String(notification.related_employee_id) === String(currentEmployeeId);

            if (isOwnEvent && (isBirthday || isAnniversary)) {
                // Own birthday/anniversary — "Acknowledge" is more meaningful
                $('#notif-btn-text').text('Acknowledge');
                $('#notif-btn-icon').removeClass('bi-send').addClass('bi-check-circle');
                $('#notif-event-date-label').text('Wishing you a wonderful day!');
            } else if (notification.related_employee_id && (isBirthday || isAnniversary)) {
                // Colleague's birthday/anniversary — send wishes
                //var wishText = isBirthday ? 'Send Birthday Wishes' : 'Send Anniversary Wishes';
                //$('#notif-btn-text').text(wishText);
                $('#notif-btn-text').text('Acknowledge');
                $('#notif-btn-icon').removeClass('bi-check-circle').addClass('bi-send');
                $('#notif-event-date-label').text('Join your colleagues in wishing them well!');
            } else {
                // Generic notification
                $('#notif-btn-text').text('Acknowledge');
                $('#notif-btn-icon').removeClass('bi-send').addClass('bi-check-circle');
                $('#notif-event-date-label').text('');
            }

            $('#mark-as-read-btn').data('notification-id', notification.id);
            $('#employeeNotificationModal').modal('show');
        }

        function formatNotificationDate(dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return date.toLocaleDateString('en-US', options);
        }

        $('#mark-as-read-btn').on('click', function() {
            var notificationId = $(this).data('notification-id');

            if (!notificationId) {
                console.error('No notification ID found');
                return;
            }

            $.ajax({
                url: '<?= base_url("ajax/notifications/mark-as-read") ?>',
                method: 'POST',
                data: {
                    notification_id: notificationId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#employeeNotificationModal').modal('hide');
                        setTimeout(function() {
                            checkForEmployeeNotifications();
                        }, 500);
                    } else {
                        console.error('Failed to mark notification as read:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error marking notification as read:', error);
                }
            });
        });
    });
</script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>

<?php
$current_employee_id = session()->get('current_user')['employee_id'];
// if( $current_employee_id == '40' && !empty($probationPopUpEmployees) ){
if (!empty($probationPopUpEmployees)) {
?>
    <style>
        .swal2-container {
            z-index: 1100 !important;
        }

        .swal2-select {
            position: relative;
            z-index: 1200;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            const probationPopUpEmployees = JSON.parse('<?php echo json_encode($probationPopUpEmployees); ?>');
            let htmlContent = '<ul class="list-group text-start">';
            let cancellable = true;
            probationPopUpEmployees.forEach(employee => {
                cancellable = (cancellable == true && employee.cancellable == true) ? true : false;
                htmlContent += `
                <li class="employee-dropdown list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">${employee.employee_name}</div>

                            <small style="font-size: 0.70rem;">is currently on ${employee.probation_status}</small>
                        </div>
                        <span class="badge bg-white border border-primary p-0">
                             <select id="employee_${employee.employee_id}">
                                <option value="" disabled selected>Select an action</option>
                                ${employee.available_actions.map(action => 
                                    `<option value="${action}">${action}</option>`
                                ).join('')}
                            </select>
                        </span>
                    </div>
                    <div id="error_${employee.employee_id}" style="color: red; display: none; font-size: 0.70rem;">Please select an action!</div>
              </li>
            `;
            });
            htmlContent += '</ul>';

            Swal.fire({
                title: 'Manage Employees on Probation',
                html: htmlContent,
                padding: "1rem",
                confirmButtonText: 'Save',
                showCancelButton: cancellable,
                allowOutsideClick: false,
                preConfirm: () => {
                    let valid = true;
                    probationPopUpEmployees.forEach(employee => {
                        const selectedValue = $(`#employee_${employee.employee_id}`).val();
                        if (!selectedValue) {
                            $(`#error_${employee.employee_id}`).show();
                            valid = false;
                        } else {
                            $(`#error_${employee.employee_id}`).hide();
                        }
                    });

                    if (!valid) {
                        // Swal.showValidationMessage('Please select an action for all employees.');
                        return false;
                    }

                    const formData = {};
                    probationPopUpEmployees.forEach(employee => {
                        formData[`${employee.employee_id}`] = $(`#employee_${employee.employee_id}`).val();
                    });

                    return formData;
                },
            }).then(async (result) => {

                console.log(result);
                if (result.isConfirmed) {
                    const selectedData = {
                        'reponses': result.value
                    };
                    try {
                        const response = await $.ajax({
                            method: "POST",
                            url: "<?php echo base_url('backend/master/employee/save-probation-response-of-hod'); ?>",
                            data: selectedData,
                            success: function(response) {
                                console.log(response);
                                Swal.fire('Saved!', 'Actions have been saved successfully.', 'success');
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to save actions. Please try again.', 'error');
                            },
                        });
                    } catch (error) {
                        Swal.fire('Error', 'Failed to save actions. Please try again.', 'error');
                    }
                }
                //showAnnouncementPopup();
            });
        })
    </script>
<?php
} else {
?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            //showAnnouncementPopup();
            oneYearEmpAnniversary();
            setTimeout(function() {
                sendAbsentWithoutLeaveNotification();
            }, 10000);
            setTimeout(function() {
                sendAbsentWithoutLeaveNotificationHeuerOnly();
            }, 15000);
        });
    </script>
<?php
}
?>

<?php if (in_array(session()->get('current_user')['employee_id'], array_map('intval', explode(',', env('app.recruitmentManagerIds'))))): ?>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                checkForHrProbationConfirmations();
            }, 2500);

            function checkForHrProbationConfirmations() {
                $.ajax({
                    url: '<?= base_url("/ajax/probation/hr-confirmations") ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.confirmations.length > 0) {
                            showHrProbationModal(response.confirmations[0]);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching HR confirmations:', error);
                    }
                });
            }

            function showHrProbationModal(confirmation) {
                $('#pc-record-id').val(confirmation.id);
                $('#pc-employee-name').text(confirmation.first_name + ' ' + confirmation.last_name);
                $('#pc-department').text(confirmation.department_name || 'N/A');
                $('#pc-designation').text(confirmation.designation_name || 'N/A');
                $('#pc-joining-date').text(formatDate(confirmation.joining_date));
                $('#pc-probation-period').text(confirmation.probation);
                //$('#pc-probation-end-date').text(calculateProbationEndDate(confirmation.joining_date, confirmation.probation_days));
                $('#pc-hod-name').text(confirmation.hod_first_name + ' ' + confirmation.hod_last_name);
                $('#pc-confirmation-date').text(formatDate(confirmation.date_time));

                $('#probationConfirmationModal').modal('show');
            }

            $('#pc-remind-later-btn').click(function() {
                handleHrAction('remind_later', null);
            });

            $('#pc-confirmed-btn').click(function() {
                Swal.fire({
                    title: 'Confirm Probation',
                    text: 'Are you sure you want to confirm this probation?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Confirm',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        handleHrAction('confirmed', null);
                    }
                });
            });

            function handleHrAction(action, notes) {
                const recordId = $('#pc-record-id').val();
                const $remindBtn = $('#pc-remind-later-btn');
                const $confirmBtn = $('#pc-confirmed-btn');

                const remindBtnHtml = $remindBtn.html();
                const confirmBtnHtml = $confirmBtn.html();

                $remindBtn.prop('disabled', true);
                $confirmBtn.prop('disabled', true);

                if (action === 'remind_later') {
                    $remindBtn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...');
                } else {
                    $confirmBtn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...');
                }

                $.ajax({
                    url: '<?= base_url("/ajax/probation/hr-action") ?>',
                    method: 'POST',
                    data: {
                        record_id: recordId,
                        action: action,
                        notes: notes
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#probationConfirmationModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            setTimeout(checkForHrProbationConfirmations, 1000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process action. Please try again.'
                        });
                    },
                    complete: function() {
                        // Restore buttons
                        $remindBtn.html(remindBtnHtml).prop('disabled', false);
                        $confirmBtn.html(confirmBtnHtml).prop('disabled', false);
                    }
                });
            }

            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-GB');
            }

            function calculateProbationEndDate(joiningDate, probationDays) {
                if (!joiningDate || !probationDays) return 'N/A';
                const date = new Date(joiningDate);
                date.setDate(date.getDate() + parseInt(probationDays));
                return date.toLocaleDateString('en-GB');
            }
        });
    </script>
<?php endif; ?>

<!-- HR Manager Resignation Notification Bootstrap Modal -->
<div class="modal fade" id="resignationHrNotificationModal" tabindex="-1" aria-labelledby="resignationHrNotificationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="resignationHrNotificationModalLabel" style="text-align: center;">
                    <span class="me-2">🔔</span><span>Resignation Response Notification</span>
                </h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rn-record-id">

                <div class="alert alert-warning mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    The Head of Department (HOD) has reviewed and responded to the following resignation. Please acknowledge to confirm receipt.
                </div>
                <!-- Employee Information Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Employee Information</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Name:</strong></div>
                            <div class="col-md-8" id="rn-employee-name"></div>
                        </div>
                        <!-- <div class="row mb-2">
                                <div class="col-md-4"><strong>Employee ID:</strong></div>
                                <div class="col-md-8" id="rn-employee-id"></div>
                            </div> -->
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Department:</strong></div>
                            <div class="col-md-8" id="rn-department"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Designation:</strong></div>
                            <div class="col-md-8" id="rn-designation"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Company:</strong></div>
                            <div class="col-md-8" id="rn-company"></div>
                        </div>
                    </div>
                </div>

                <!-- Resignation Details Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Resignation Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-5"><strong>Resignation Date:</strong></div>
                            <div class="col-md-7" id="rn-resignation-date"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"><strong>Last Working Date:</strong></div>
                            <div class="col-md-7" id="rn-last-working-date"></div>
                        </div>
                    </div>
                </div>

                <!-- HOD Response Card -->
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>HOD Response</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>HOD Name:</strong></div>
                            <div class="col-md-8" id="rn-hod-name"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Response:</strong></div>
                            <div class="col-md-8">
                                <span id="rn-hod-response" class="badge bg-success"></span>
                            </div>
                        </div>
                        <div id="rn-rejection-reason-container" style="display: none;">
                            <div class="row">
                                <div class="col-md-4"><strong>Reason:</strong></div>
                                <div class="col-md-8">
                                    <div class="alert alert-danger mb-0" id="rn-rejection-reason"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-lg btn-success" id="rn-acknowledge-btn">
                    <i class="fa fa-check me-2"></i>Acknowledge
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== END HR MANAGER RESIGNATION NOTIFICATION ==================== -->
<script>
    $(document).ready(function() {
        checkForHrManagerNotifications();
    });

    function checkForHrManagerNotifications() {
        $.ajax({
            url: '<?php echo base_url('ajax/resignation/hr-manager-notifications'); ?>',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                // if (response.success && response.data && response.data.length > 0) {
                //     displayHrManagerNotifications(response.data);
                // }
                if (response.success && response.notifications && response.notifications.length > 0) {
                    displayHrManagerNotifications(response.notifications);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching HR Manager notifications:', error);
            }
        });
    }

    function displayHrManagerNotifications(notifications) {
        if (notifications.length === 0) return;

        let html = '<div class="container-fluid">';
        html += '<div class="alert alert-info mb-3">';
        html += '<strong>You have ' + notifications.length + ' resignation(s) to review as HR Manager</strong>';
        html += '</div>';

        notifications.forEach((notification, index) => {
            let hodResponseBadge = '';
            switch (notification.hod_response) {
                case 'accept':
                    hodResponseBadge = '<span class="badge badge-success">Accepted</span>';
                    break;
                case 'reject':
                    hodResponseBadge = '<span class="badge badge-danger">Rejected</span>';
                    break;
                case 'pending':
                    hodResponseBadge = '<span class="badge badge-warning">Pending</span>';
                    break;
                case 'too_early':
                    hodResponseBadge = '<span class="badge badge-info">Too Early</span>';
                    break;
                default:
                    hodResponseBadge = '<span class="badge badge-secondary">Unknown</span>';
            }

            html += '<div class="card mb-3">';
            html += '<div class="card-header bg-light"><strong>Resignation ' + (index + 1) + ' of ' + notifications.length + '</strong></div>';
            html += '<div class="card-body">';
            html += '<div class="row">';
            html += '<div class="col-md-6">';
            html += '<p><strong>Employee:</strong> ' + notification.first_name + ' ' + notification.last_name + ' (' + notification.internal_employee_id + ')</p>';
            html += '<p><strong>Department:</strong> ' + (notification.department_name || 'N/A') + '</p>';
            html += '<p><strong>Company:</strong> ' + (notification.company_name || 'N/A') + '</p>';
            html += '<p><strong>Resignation Date:</strong> ' + notification.resignation_date + '</p>';
            html += '</div>';
            html += '<div class="col-md-6">';
            html += '<p><strong>HOD Response:</strong> ' + hodResponseBadge + '</p>';
            html += '<p><strong>HOD:</strong> ' + (notification.hod_first_name || '') + ' ' + (notification.hod_last_name || '') + '</p>';
            html += '<p><strong>Response Date:</strong> ' + (notification.hod_response_date || 'N/A') + '</p>';
            html += '<p><strong>Reason:</strong> ' + (notification.resignation_reason || 'N/A') + '</p>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        });

        html += '</div>';

        $('#hrManagerResignationNotificationContent').html(html);
        $('#hrManagerResignationNotificationModal').modal('show');

        // Store notification IDs for marking as viewed
        $('#markHrManagerNotificationViewedBtn').data('record-ids', notifications.map(n => n.id));
    }

    $(document).ready(function() {
        $('#markHrManagerNotificationViewedBtn').on('click', function() {
            const recordIds = $(this).data('record-ids');

            if (!recordIds || recordIds.length === 0) return;

            // Mark all notifications as viewed
            const promises = recordIds.map(recordId => {
                return $.ajax({
                    url: '<?php echo base_url('ajax/resignation/hr-manager-notification-action'); ?>',
                    method: 'POST',
                    data: {
                        record_id: recordId,
                        action: 'viewed'
                    },

                });
            });

            Promise.all(promises).then(() => {
                $('#hrManagerResignationNotificationModal').modal('hide');
                toastr.success('Notifications marked as viewed');
            }).catch(() => {
                toastr.error('Failed to mark some notifications as viewed');
            });
        });
    });
</script>
<!-- ==================== END RESIGNATION HOD ACKNOWLEDGMENT MODALS ==================== -->


<script>
    function oneYearEmpAnniversary() {
        const currentDate = new Date();
        const oneYearAgo = new Date(currentDate);
        oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1); // Adjust year safely

        const oneYearAgoDate = oneYearAgo.toISOString().split('T')[0]; // Format YYYY-MM-DD

        $.ajax({ // Ensure jQuery is used
            url: "<?= base_url('/ajax/hr/employee/get-one-year-anniversary-employees') ?>",
            type: "POST",
            contentType: 'application/json',
            data: JSON.stringify({
                date: oneYearAgoDate
            }),
            dataType: "json",
            success: function(response) {
                if ($.trim(response.status) === 'success' && response.data) {

                    console.log('Anniversary Employees:', response.data);
                    var DOA_employees = $("#DOA_employees").DataTable({
                        "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end">>>>',
                        "data": response.data,
                        "columns": [{

                                "data": function(row) {
                                    return row.first_name + ' ' + row.last_name;
                                },
                                "title": "Employee Name"
                            },
                            {
                                "data": "joining_date",
                                "title": "Date of Joining",
                                "render": function(data, type, row) {
                                    return moment(data).format('DD-MMM-YYYY');
                                }
                            },
                            {
                                "data": "anniversary_date",
                                "title": "1st Anniversary Date",
                                "render": function(data, type, row) {
                                    return moment(data).format('DD-MMM-YYYY');
                                }
                            }
                        ],
                        "order": [
                            [1, 'asc']
                        ],
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        "scrollX": true,
                        "scrollY": "300px",
                        "scrollCollapse": true,
                        "paging": false,
                        "buttons": []
                    });
                    $("#DOA_employees").closest(".card").find(".card-title").text("Completed 1 Year");
                } else {
                    console.error('Unexpected response:', response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Failed to fetch anniversary employees:', errorThrown,
                    'Status:', textStatus,
                    'Response:', jqXHR.responseText);
            }
        });
    }

    function sendAbsentWithoutLeaveNotification() {
        $.ajax({
            url: "<?= base_url('/ajax/hr/employee/send-absent-without-leave-notification') ?>",
            type: "POST",
            dataType: "json",
            success: function(response) {
                if ($.trim(response.response_type) === 'failed') {
                    console.log('Failed to send absent without leave notification');
                }
            }
        });


    }

    function sendAbsentWithoutLeaveNotificationHeuerOnly() {
        $.ajax({
            url: "<?= base_url('/ajax/hr/employee/send-absent-without-leave-notification-heuer-only') ?>",
            type: "POST",
            dataType: "json",
            success: function(response) {
                if ($.trim(response.response_type) === 'failed') {
                    console.log('Failed to send absent without leave notification');
                }
            }
        });


    }
    // function showAnnouncementPopup(){
    //     document.addEventListener('DOMContentLoaded', function () {
    //         var announcementPopup = new bootstrap.Modal(document.getElementById('announcementPopup'));
    //         announcementPopup.show();
    //     });
    // }

    //function showAnnouncementPopup() {
    //   const modalElement = document.getElementById('announcementPopup');
    //  if (modalElement) {
    //      const modalInstance = new bootstrap.Modal(modalElement);
    //      modalInstance.show();
    //  } else {
    //      console.error('Modal with id "announcementPopup" not found.');
    //  }


    //}

    function showAnnouncementPopup() {
        window.onload = function() {
            setTimeout(() => {
                const modalElement = document.getElementById('announcementPopup');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    console.error('Modal with id "announcementPopup" not found.');
                }
            }, 2000); // Show modal after 2 seconds
        };
    }
</script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        /*begin::punching_report_table*/
        var punching_report_table = $("#punching_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',

            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-punching-reports') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
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
                    data: {
                        _: 'date_time_new.formatted',
                        sort: 'date_time_new.ordering',
                    }
                },
                {
                    data: "day",
                    /*render: function(data, type, row, meta) {
                        return data.substring(0,3);
                    }*/
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        /*var status_html = '<div class="d-flex flex-column">'+
                        '<strong class="cursor-pointer" data-bs-toggle="tooltip" data-bs-html="true" title="'+row.status_remarks+'">' + data + '</strong>';
                        status_html += '</div>';*/
                        var status_html = `<div class='d-flex flex-column'><strong class='cursor-pointer' data-bs-toggle='tooltip' data-bs-html='true' title='${row.status_remarks}'>${data}</strong></div>`;
                        return status_html;
                    }
                },
                {
                    render: function(data, type, row, meta) {
                        var shift_start = (row.shift_start != null) ? row.shift_start : '';
                        var shift_end = (row.shift_end != null) ? row.shift_end : '';
                        var status_html = '<div class="d-flex flex-column"><span>' + shift_start + '</span><span>' + shift_end + '</span></div>';
                        return status_html;
                    }
                },
                {
                    render: function(data, type, row, meta) {
                        var in_time_between_shift_with_od = row.in_time_between_shift_with_od !== null ? row.in_time_between_shift_with_od : '--';
                        var out_time_between_shift_with_od = row.out_time_between_shift_with_od !== null ? row.out_time_between_shift_with_od : '--';
                        var punch_in_time = row.punch_in_time !== null ? row.punch_in_time : '--';
                        var punch_out_time = row.punch_out_time !== null ? row.punch_out_time : '--';

                        var biometric_time_html = "<small class='w-100 d-flex align-items-center justify-content-between text-center text-danger' style='max-width: max-content;'>Machine: " + row.machine + "</small><div class='d-flex align-items-center justify-content-between' style='max-width: max-content; padding-top: 2px; padding-bottom: 2px;'>" +
                            "<div class='d-flex flex-column'>";
                        biometric_time_html += "<small class='text-info fs-9 px-1' style='line-height: 1.15;'>Within Shift</small>";
                        if (row.is_onOD == 'yes') {
                            biometric_time_html += "<small class='text-info fs-9 px-1' style='line-height: 1.15;'>With OD</small>";
                        }
                        biometric_time_html += "</div>";

                        biometric_time_html += "<div class='d-flex flex-column border-start border-info'>" +
                            "<small class='text-info fs-9 border-bottom border-info px-1' style='line-height: 1.15;'>" + in_time_between_shift_with_od + "</small>" +
                            "<small class='text-info fs-9 px-1' style='line-height: 1.15;'>" + out_time_between_shift_with_od + "</small>" +
                            "</div>" +
                            "</div>";

                        var punching_time_html = '<div class="cursor-pointer d-flex flex-column align-items-center" data-bs-toggle="tooltip" data-bs-html="true" title="' + biometric_time_html + '" >' +
                            '<span>' + punch_in_time + '</span>' +
                            '<span>' + punch_out_time + '</span>' +
                            '</div>';
                        return punching_time_html;
                    }
                },
                {
                    data: "late_coming_minutes",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "early_going_minutes",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "late_coming_plus_early_going_minutes",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "work_hours_between_shifts_including_od",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "paid",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: "grace",
                    render: function(data, type, row, meta) {
                        return '<strong>' + data + '</strong>';
                    }
                }
            ],
            "order": [
                [0, 'desc']
            ],
            "scrollX": true,
            "paging": false,
            "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                },
                /*{
                    targets: [-1,-2],
                    visible: false
                },*/
            ],
            "drawCallback": function(settings) {
                var response = settings.json;
            },
            "initComplete": function(settings, json) {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
                get_attendance_stats();

            },
            "headerCallback": function(thead, data, start, end, display) {
                // $(thead).find('th').eq(0).html( 'Displaying '+(end-start)+' records' );
                var api = this.api(),
                    data;
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\-,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                //late_coming
                late_coming = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(5).header()).html(
                    '<strong>Late IN<br><span class="ms-1 badge badge-danger">' + Math.round(late_coming) + '<span></strong>'
                );

                //early_going_minutes
                early_going_minutes = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(6).header()).html(
                    '<strong>Early Out<br><span class="ms-1 badge badge-danger">' + Math.round(early_going_minutes) + '<span></strong>'
                );

                //non_working_minutes_including_od
                non_working_minutes_including_od = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(7).header()).html(
                    '<strong>Late+Early<br><span class="ms-1 badge badge-danger">' + Math.round(non_working_minutes_including_od) + '<span></strong>'
                );

                //non_working_minutes_including_od
                paid_days = api
                    .column(9)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(9).header()).html(
                    '<strong>Paid Days<br><span class="ms-1 badge badge-success">' + paid_days + '<span></strong>'
                );

                //non_working_minutes_including_od
                grace_minutes = api
                    .column(10)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(10).header()).html(
                    '<strong>Grace<br><span class="ms-1 badge badge-success">' + Math.round(grace_minutes) + '<span></strong>'
                );
            }
        });
        $('#punching_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<div class="d-flex flex-column"><h3 class="card-title">Punching Report</h3><small>As per your shift timings</small></div>');



        function get_attendance_stats() {
            function get_icon(value) {
                if (value <= 10) {
                    icon = '<i class="fa-solid fa-arrow-down text-success me-2"></i>';
                } else if (value > 10 && value <= 20) {
                    icon = '<i class="fa-solid fa-arrow-up text-warning me-2"></i>';
                } else {
                    icon = '<i class="fa-solid fa-arrow-up text-danger me-2"></i>';
                }
                return icon;
            }

            function get_stat_html(value, title, icon = false) {
                var html = `<div class="border border-gray-400 border-dashed rounded w-auto min-w-125px py-3 px-4 me-3">
                            <div class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                    ${icon ? icon : get_icon(value)}
                                </span>
                                <div class="fs-5 fw-bolder counted">${value} Minutes</div>
                            </div>
                            <div class="fw-bold fs-9 text-gray-600">${title}</div>
                        </div>`;
                return html;
            }

            $.ajax({
                method: "get",
                url: "<?php echo base_url('ajax/profile/get-attendance-stats'); ?>",
                success: function(stats) {
                    console.log(stats);

                    $('.stats-container').html(
                        get_stat_html(stats.seven_days_late_minutes_avg, "Avg Late in last 7 days") +
                        get_stat_html(stats.fifteen_days_late_minutes_avg, "Avg Late in last 15 days") +
                        get_stat_html(stats.current_month_late_minutes_avg, "Avg Late in Current Month") +
                        get_stat_html(stats.current_month_late_minutes, "Total Late in Current Month") +
                        get_stat_html(stats.seven_days_early_going_minutes_avg, "Avg Early Going in last 7 days") +
                        get_stat_html(stats.fifteen_days_early_going_minutes_avg, "Avg Early Going in last 15 days") +
                        get_stat_html(stats.current_month_early_going_minutes_avg, "Avg Early Going in Current Month") +
                        get_stat_html(stats.current_month_early_going_minutes, "Total Early Going in Current Month") +
                        get_stat_html(stats.current_date_early_going_minutes, "Total Early Going Today") +
                        get_stat_html(stats.balance_grace, "Balance Grace", `<i class="fa-solid fa-clock text-primary me-2"></i>`)
                    );
                },
                failed: function() {
                    console.log('error at ajax');
                    $('.stats-container').html('<div class="col-12 text-center py-5 text-danger"><i class="fa-solid fa-exclamation-triangle me-2"></i>Failed to load attendance statistics</div>');
                }
            });
        }

        /*end::punching_report_table*/

        /*begin::leave_report_table*/
        var leave_report_table = $("#leave_report_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [
                // { extend: 'excel', text: '<i class="fa-solid fa-file-excel"></i> Excel', className: 'btn btn-sm btn-light' },
                {
                    text: '<i class="fa-solid fa-arrow-up-right-from-square"></i> Details',
                    action: function(e, dt, node, config) {
                        // alert( 'Under construction' );
                        window.open('<?= base_url('/backend/user/leaves') ?>', '_blank');
                    },
                    className: 'btn btn-sm btn-light'
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-leave-reports') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
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
                    data: "from_date",
                    render: function(data, type, row, meta) {
                        var status_html = '<div class="d-flex flex-column">' +
                            '<span>' + data + '</span>' +
                            '<span>' + row.to_date + '</span>' +
                            '</div>';
                        return status_html;
                    }
                },
                {
                    data: "number_of_days"
                },
                {
                    data: "day_type"
                },
                {
                    data: "type_of_leave",
                    render: function(data, type, row, meta) {
                        if (row.sick_leave == 'yes') {
                            return 'SICK LEAVE';
                        }
                        return row.type_of_leave;
                    }
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        var badge_class = "bg-secondary";
                        if (data == 'cancelled') {
                            /*badge_class = "bg-danger bg-opacity-15";*/
                            badge_class = "bg-dark bg-opacity-50";
                        } else if (data == 'rejected') {
                            /*badge_class = "bg-danger bg-opacity-15";*/
                            badge_class = "bg-danger";
                        } else if (data == 'approved') {
                            /*badge_class = "bg-success bg-opacity-15";*/
                            badge_class = "bg-success";
                        }
                        /*return '<span class="badge text-capitalize text-dark fw-normal rounded-pill ' + badge_class + '">' + data + '</span>';*/
                        return '<span class="badge text-capitalize rounded-pill ' + badge_class + '">' + data + '</span>';
                    }
                },
                {
                    data: "reviewed_by_name"
                },
                {
                    data: "reviewed_date"
                },
                {
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap" style="width: 200px">' + data + '</p>';
                    }
                },
                {
                    data: "address_d_l"
                },
                {
                    data: "emergency_contact_d_l"
                },
                {
                    data: "reason_of_leave",
                    render: function(data, type, row, meta) {
                        return '<p class="text-wrap" style="width: 200px">' + data + '</p>';
                    }
                },
                {
                    data: "attachment",
                    render: function(data, type, row, meta) {
                        if (data.length) {
                            var link = '<?php echo base_url(); ?>' + data;
                            return '<a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="' + link + '" target="_blank">View</a>';
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: "date_time"
                },
            ],
            "order": [],
            // "order": [[8, 'desc']],
            "scrollX": true,
            // "scrollY": 'auto',
            // "scrollY": '400px',
            "paging": false,
            "columnDefs": [
                // { "className": 'text-center fw-bold', "targets": [2] },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
            ],
        });
        $('#leave_report_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Leave Report</h3>');
        /*end::leave_report_table*/
        /*begin::od_report_table*/
        var od_report_table_approved = $("#od_report_table_approved").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                text: '<i class="fa-solid fa-arrow-up-right-from-square"></i> Details',
                action: function(e, dt, node, config) {
                    window.open('<?= base_url('/backend/user/od') ?>', '_blank');
                },
                className: 'btn btn-sm btn-light'
            }],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-od-reports-approved') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
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
                    data: "pre_post"
                },
                {
                    data: "estimated_from_date_time"
                },
                {
                    data: "estimated_to_date_time"
                },
                {
                    data: "actual_from_date_time"
                },
                {
                    data: "actual_to_date_time"
                },
                {
                    data: "interval"
                },
                {
                    data: "duty_location"
                },
                {
                    data: "assigned_by"
                },
                {
                    data: "reason"
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        var badge_class = "bg-secondary";
                        if (data == 'rejected') {
                            badge_class = "bg-danger bg-opacity-15";
                        } else if (data == 'approved') {
                            badge_class = "bg-success bg-opacity-15";
                        }
                        return '<span class="badge text-capitalize text-dark fw-normal rounded-pill ' + badge_class + '">' + data + '</span>';
                    }
                },
                {
                    data: "reviewed_by_name"
                },
                {
                    data: "reviewed_date_time"
                },
                {
                    data: "remarks"
                },
                {
                    data: "updated_date_time"
                },
                {
                    data: "date_time"
                },
            ],
            "order": [
                [8, 'desc']
            ],
            "scrollX": true,
            "paging": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ],
            fnInitComplete: function() {
                if ($(this).find('tbody tr').length < 1) {
                    /*$(this).parent().hide();*/
                    $("#od_report_table_approved_wrapper").hide();
                }
            },
        });
        $('#od_report_table_approved_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">OD Report Approved</h3>');
        /*end::od_report_table*/
        /*begin::od_report_table*/
        var od_report_table_pending = $("#od_report_table_pending").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                text: '<i class="fa-solid fa-arrow-up-right-from-square"></i> Details',
                action: function(e, dt, node, config) {
                    window.open('<?= base_url('/backend/user/od') ?>', '_blank');
                },
                className: 'btn btn-sm btn-light'
            }],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-od-reports-pending') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
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
                    data: "pre_post"
                },
                {
                    data: "estimated_from_date_time"
                },
                {
                    data: "estimated_to_date_time"
                },
                {
                    data: "actual_from_date_time"
                },
                {
                    data: "actual_to_date_time"
                },
                {
                    data: "interval"
                },
                {
                    data: "duty_location"
                },
                {
                    data: "assigned_by"
                },
                {
                    data: "reason"
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        var badge_class = "bg-secondary";
                        if (data == 'rejected') {
                            badge_class = "bg-danger bg-opacity-15";
                        } else if (data == 'approved') {
                            badge_class = "bg-success bg-opacity-15";
                        }
                        return '<span class="badge text-capitalize text-dark fw-normal rounded-pill ' + badge_class + '">' + data + '</span>';
                    }
                },
                {
                    data: "reviewed_by_name"
                },
                {
                    data: "reviewed_date_time"
                },
                {
                    data: "remarks"
                },
                {
                    data: "updated_date_time"
                },
                {
                    data: "date_time"
                },
            ],
            "order": [
                [8, 'desc']
            ],
            "scrollX": true,
            "paging": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ],
            fnInitComplete: function() {
                if ($(this).find('tbody tr').length < 1) {
                    /*$(this).parent().hide();*/
                    $("#od_report_table_pending_wrapper").hide();
                }
            },
        });
        $('#od_report_table_pending_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">OD Report Pending</h3>');
        /*end::od_report_table*/


        /*begin::leave_balance_current_month*/
        var leave_balance_current_month = $("#leave_balance_current_month").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-leave-balance-on-profile-page') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                // dataSrc: "",
                dataSrc: function(receivedData) {
                    if (receivedData.length) {
                        $.each(receivedData, function(index, item) {
                            if (item.leave_code == 'RH') {
                                var _rh_dates = item.rh_dates;
                                if (_rh_dates.length > 0) {
                                    if (_rh_dates.length == 2) {
                                        $('#first_rh_date').val(_rh_dates[0]).trigger('change');
                                        $('#second_rh_date').val(_rh_dates[1]).trigger('change');
                                    } else {
                                        $('#first_rh_date').val(_rh_dates[0]).trigger('change');
                                        $('#second_rh_date').val('').trigger('change');
                                    }
                                }
                            }
                        })
                    }
                    $("#create_leave_request_button_trigger").html('<i class="fa fa-plus"></i> Request a Leave').removeAttr('disabled');
                    // console.log('The data has arrived', receivedData);
                    return receivedData;
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
                    data: "leave_code"
                },
                {
                    data: "balance"
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": false,
            "columnDefs": [{
                "className": 'text-end',
                "targets": [1]
            }, ],
            // initComplete: function(json) {
            // let returned_data = json;
            // console.log(returned_data);
            // }
        });
        $('#leave_balance_current_month_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Leave Balance</h3>');
        $('#leave_balance_current_month_wrapper > .card > .card-footer').html('<small class="d-block">If leave balance is incorrect Please contact Developer on ext 452</small>');
        /*end::leave_balance_current_month*/
        /*begin::leave_balance_next_month*/
        var leave_balance_next_month = $("#leave_balance_next_month").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-leave-balance-of-next-month-on-profile-page') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
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
                    data: "leave_code"
                },
                {
                    data: "balance"
                },
                {
                    data: "eligible_balance"
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": false,
            "columnDefs": [{
                    "className": 'text-center',
                    "targets": [1]
                },
                {
                    "className": 'text-end',
                    "targets": [2]
                },
                {
                    "defaultContent": "-",
                    "targets": "_all"
                }
            ],
        });
        $('#leave_balance_next_month_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Estimated/Eligible Leave Balance Next Month</h3>');
        $('#leave_balance_next_month_wrapper > .card > .card-footer').html('<small style="font-size: 0.7em;">Estimated = Current month balance + Estiamted credit of next month excluding next month requests</small><hr><small style="font-size: 0.7em;">Eligible = Estiamted credit of next month excluding next month requests (System cannot predict how many leaves are you going to apply in current month, Therefore usable balance for next month can be only the balance which will be credited next month excluding next month requests)</small>');
        /*end::leave_balance_next_month*/

        jQuery(document).on('change', '.leave_request_number_of_days', function() {
            console.log('I was here');
            var number_of_days = $(this).val();
            if ($('#day_type_half_day').is(':checked')) {
                var day_type = 0.5;
            } else {
                var day_type = 1;
            }
            var number_of_days_excluding_rh = get_interval_considering_half_day_excluding_rh('from_date', 'to_date');
            number_of_days_excluding_rh = number_of_days_excluding_rh * day_type;
            $('.included-rh-days').html('');
            var total_rh_days = number_of_days - number_of_days_excluding_rh;
            if (total_rh_days > 0) {
                $('.included-rh-days').html(total_rh_days + ' RH Day included');
            }
        });

        $('#from_date').flatpickr({
            dateFormat: 'Y-m-d',
            minDate: "<?php echo date('Y-m-01'); ?>",
            maxDate: "<?php echo date('Y-m-t'); ?>",
            altInput: false,
            static: true,
            onClose: function(selectedDates, dateStr, instance) {
                if ($('#day_type_half_day').is(':checked')) {
                    var day_type = 0.5;
                } else {
                    var day_type = 1;
                }
                var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                number_of_days = number_of_days * day_type;
                $('#number_of_days').val(number_of_days).trigger('change');



                if (day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5) {
                    Swal.fire({
                        html: 'Select same date in from date and to date',
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                        didRender: function(x) {
                            $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                            var buttons = $(".swal2-popup > .swal2-actions > button");
                            buttons.each(function(index, elem) {
                                var btnClass = $(this).attr("class");
                                var btnStyle = $(this).attr("style");
                                var btnHtml = $(this).html();
                                var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                $(this).replaceWith(newButton);
                            })
                        },
                        didClose: function(x) {
                            $('#from_date').val('').focus();
                        },
                    })
                } else if ($('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0) {
                    Swal.fire({
                        html: 'Number of days can not be negative or 0',
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                        didRender: function(x) {
                            $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                            var buttons = $(".swal2-popup > .swal2-actions > button");
                            buttons.each(function(index, elem) {
                                var btnClass = $(this).attr("class");
                                var btnStyle = $(this).attr("style");
                                var btnHtml = $(this).html();
                                var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                $(this).replaceWith(newButton);
                            })
                        },
                        didClose: function(x) {
                            $('#from_date').val('').focus();
                        },
                    })
                }
            }
        })
        $('#to_date').flatpickr({
            dateFormat: 'Y-m-d',
            minDate: "<?php echo date('Y-m-01'); ?>",
            maxDate: "<?php echo date('Y-m-t'); ?>",
            altInput: false,
            static: true,
            onClose: function(selectedDates, dateStr, instance) {
                if ($('#day_type_half_day').is(':checked')) {
                    var day_type = 0.5;
                } else {
                    var day_type = 1;
                }
                var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                number_of_days = number_of_days * day_type;
                $('#number_of_days').val(number_of_days).trigger('change');


                if (day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5) {
                    Swal.fire({
                        html: 'select same date in from date and to date',
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                        didRender: function(x) {
                            $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                            var buttons = $(".swal2-popup > .swal2-actions > button");
                            buttons.each(function(index, elem) {
                                var btnClass = $(this).attr("class");
                                var btnStyle = $(this).attr("style");
                                var btnHtml = $(this).html();
                                var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                $(this).replaceWith(newButton);
                            })
                        },
                        didClose: function(x) {
                            $('#to_date').val('').focus();
                        },
                    })
                } else if ($('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0) {
                    Swal.fire({
                        html: 'Number of days can not be negative or 0',
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                        didRender: function(x) {
                            $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                            var buttons = $(".swal2-popup > .swal2-actions > button");
                            buttons.each(function(index, elem) {
                                var btnClass = $(this).attr("class");
                                var btnStyle = $(this).attr("style");
                                var btnHtml = $(this).html();
                                var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                $(this).replaceWith(newButton);
                            })
                        },
                        didClose: function(x) {
                            $('#to_date').val('').focus();
                        },
                    })
                }
            }
        })
        $(document).on('change', '.type_of_leave', function(e) {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#number_of_days').val('').trigger('change');
            if ($('.type_of_leave#type_of_leave_el').is(':checked') == true) {
                // $('#day_type_full_day').prop('checked', true);
                $('#day_type_half_day').parent().hide();
                $('#day_type_full_day').parent().trigger('click');
                $('#from_date').flatpickr({
                    dateFormat: 'Y-m-d',
                    // minDate: "<?php echo date('Y-m-d', strtotime('+3 days')); ?>",
                    minDate: "<?php echo date('Y-m-d', strtotime('+4 days')); ?>",
                    // maxDate: "<?php echo date('Y-12-31'); ?>",
                    // maxDate: "<?php echo date('Y-m-d', strtotime('+4 month')); ?>",
                    maxDate: "<?php echo date('Y-m-t', strtotime(date('Y-m-t') . ' +1 days')); ?>",
                    altInput: false,
                    static: true,
                    onClose: function(selectedDates, dateStr, instance) {
                        if ($('#day_type_half_day').is(':checked')) {
                            var day_type = 0.5;
                        } else {
                            var day_type = 1;
                        }
                        var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                        number_of_days = number_of_days * day_type;
                        $('#number_of_days').val(number_of_days).trigger('change');

                        if (day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5) {
                            Swal.fire({
                                html: 'Select same date in from date and to date',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function(index, elem) {
                                        var btnClass = $(this).attr("class");
                                        var btnStyle = $(this).attr("style");
                                        var btnHtml = $(this).html();
                                        var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#from_date').val('').focus();
                                },
                            });
                        } else if ($('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0) {
                            Swal.fire({
                                html: 'Number of days can not be negative or 0',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function(index, elem) {
                                        var btnClass = $(this).attr("class");
                                        var btnStyle = $(this).attr("style");
                                        var btnHtml = $(this).html();
                                        var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#from_date').val('').focus();
                                },
                            })
                        }
                    }
                })
                $('#to_date').flatpickr({
                    dateFormat: 'Y-m-d',
                    minDate: "<?php echo date('Y-m-d', strtotime('+6 days')); ?>",
                    // maxDate: "<?php echo date('Y-12-31'); ?>",
                    // maxDate: "<?php echo date('Y-m-d', strtotime('+4 month')); ?>",
                    maxDate: "<?php echo date('Y-m-t', strtotime(date('Y-m-t') . ' +1 days')); ?>",
                    altInput: false,
                    static: true,
                    onClose: function(selectedDates, dateStr, instance) {
                        if ($('#day_type_half_day').is(':checked')) {
                            var day_type = 0.5;
                        } else {
                            var day_type = 1;
                        }
                        var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                        number_of_days = number_of_days * day_type;
                        $('#number_of_days').val(number_of_days).trigger('change');

                        if (day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5) {
                            Swal.fire({
                                html: 'select same date in from date and to date',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function(index, elem) {
                                        var btnClass = $(this).attr("class");
                                        var btnStyle = $(this).attr("style");
                                        var btnHtml = $(this).html();
                                        var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#to_date').val('').focus();
                                },
                            })
                        } else if ($('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0) {
                            Swal.fire({
                                html: 'Number of days can not be negative or 0',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function(index, elem) {
                                        var btnClass = $(this).attr("class");
                                        var btnStyle = $(this).attr("style");
                                        var btnHtml = $(this).html();
                                        var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#to_date').val('').focus();
                                },
                            })
                        }
                    }
                })
            } else {
                $('#day_type_half_day').parent().show();
                $('#day_type_full_day').parent().trigger('click');

                $('#from_date').flatpickr({
                    dateFormat: 'Y-m-d',
                    minDate: "<?php echo date('Y-m-01'); ?>",
                    maxDate: "<?php echo date('Y-m-t'); ?>",
                    altInput: false,
                    static: true,
                    onClose: function(selectedDates, dateStr, instance) {
                        if ($('#day_type_half_day').is(':checked')) {
                            var day_type = 0.5;
                        } else {
                            var day_type = 1;
                        }
                        var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                        number_of_days = number_of_days * day_type;
                        $('#number_of_days').val(number_of_days).trigger('change');

                        if (day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5) {
                            Swal.fire({
                                html: 'Select same date in from date and to date',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function(index, elem) {
                                        var btnClass = $(this).attr("class");
                                        var btnStyle = $(this).attr("style");
                                        var btnHtml = $(this).html();
                                        var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#from_date').val('').focus();
                                },
                            })
                        } else if ($('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0) {
                            Swal.fire({
                                html: 'Number of days can not be negative or 0',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function(index, elem) {
                                        var btnClass = $(this).attr("class");
                                        var btnStyle = $(this).attr("style");
                                        var btnHtml = $(this).html();
                                        var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#from_date').val('').focus();
                                },
                            })
                        }
                    }
                })
                $('#to_date').flatpickr({
                    dateFormat: 'Y-m-d',
                    minDate: "<?php echo date('Y-m-01'); ?>",
                    maxDate: "<?php echo date('Y-m-t'); ?>",
                    altInput: false,
                    static: true,
                    onClose: function(selectedDates, dateStr, instance) {
                        if ($('#day_type_half_day').is(':checked')) {
                            var day_type = 0.5;
                        } else {
                            var day_type = 1;
                        }
                        var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                        number_of_days = number_of_days * day_type;
                        $('#number_of_days').val(number_of_days).trigger('change');

                        if (day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5) {
                            Swal.fire({
                                html: 'select same date in from date and to date',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function(index, elem) {
                                        var btnClass = $(this).attr("class");
                                        var btnStyle = $(this).attr("style");
                                        var btnHtml = $(this).html();
                                        var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#to_date').val('').focus();
                                },
                            })
                        } else if ($('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0) {
                            Swal.fire({
                                html: 'Number of days can not be negative or 0',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function(index, elem) {
                                        var btnClass = $(this).attr("class");
                                        var btnStyle = $(this).attr("style");
                                        var btnHtml = $(this).html();
                                        var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#to_date').val('').focus();
                                },
                            })
                        }
                    }
                })
            }
        })
        $(document).on('change', '.day_type', function(e) {
            if ($(this).is(':checked')) {
                var day_type = $(this).val();
                var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                number_of_days = number_of_days * day_type;
                $('#number_of_days').val(number_of_days).trigger('change');

                if (day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5) {
                    Swal.fire({
                        html: 'select same date in from date and to date',
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                        didRender: function(x) {
                            $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                            var buttons = $(".swal2-popup > .swal2-actions > button");
                            buttons.each(function(index, elem) {
                                var btnClass = $(this).attr("class");
                                var btnStyle = $(this).attr("style");
                                var btnHtml = $(this).html();
                                var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                $(this).replaceWith(newButton);
                            })
                        },
                        didClose: function(x) {
                            $('#to_date').val('').focus();
                        },
                    })
                } else if ($('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0) {
                    Swal.fire({
                        html: 'Number of days can not be negative or 0',
                        icon: "error",
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                        didRender: function(x) {
                            $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                            var buttons = $(".swal2-popup > .swal2-actions > button");
                            buttons.each(function(index, elem) {
                                var btnClass = $(this).attr("class");
                                var btnStyle = $(this).attr("style");
                                var btnHtml = $(this).html();
                                var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
                                $(this).replaceWith(newButton);
                            })
                        },
                        didClose: function(x) {
                            $('#to_date').val('').focus();
                        },
                    })
                }
            }
        })


        $(document).on('input', '.form-control', function() {
            $(this).parent().find('.error-text').html('');
        })
        $(document).on('change', '.leave-control.flatpickr-input', function() {
            $(this).parent().parent().parent().find('.error-text').html('');
        })
        $(document).on('click', '.parent-picker', function() {
            $(this).parent().find('.flatpickr-input').focus();
        })
        $('#duty_assigner').select2({
            dropdownParent: $("#create_od_request_modal")
        });
        $('#international').select2({
            dropdownParent: $("#create_od_request_modal")
        });
        $('#estimated_from_date_time').flatpickr({
            minDate: "<?php echo date('Y-m-01'); ?>",
            /*minDate: "<?php echo date('Y-m-d', strtotime('-1 days')); ?>",*/
            maxDate: "<?php echo date('Y-m-t'); ?>",
            enableTime: true,
            altInput: false,
            static: true,
            dateFormat: "Y-m-d H:i",
            defaultDate: "<?php echo date('Y-m-d 10:00'); ?>",
            onClose: function(selectedDates, dateStr, instance) {
                check_time_interval('estimated_from_date_time', 'estimated_to_date_time', 'estimated_from_date_time', 'hours_od');
            }
        })
        $('#estimated_to_date_time').flatpickr({
            minDate: "<?php echo date('Y-m-01'); ?>",
            /*minDate: "<?php echo date('Y-m-d', strtotime('-1 days')); ?>",*/
            maxDate: "<?php echo date('Y-m-t'); ?>",
            enableTime: true,
            altInput: false,
            static: true,
            /*dateFormat: "Y-m-d H:i",
            defaultDate: "<?php #echo date('Y-m-d 18:30');
                            ?>",*/
            onClose: function(selectedDates, dateStr, instance) {
                check_time_interval('estimated_from_date_time', 'estimated_to_date_time', 'estimated_to_date_time', 'hours_od');
            }
        })
        /*$('#gate_pass_date').flatpickr({
            minDate: "<?php echo date('Y-m-01'); ?>",
            maxDate: "<?php echo date('Y-m-t'); ?>",
            altInput: false,
            static: true,
        })*/
        $('#gate_pass_hours').flatpickr({
            altInput: false,
            static: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: false,
            enableTime: true,
            defaultHour: '10',
            defaultMinute: '20',
        })
        $(document).on('change', '.od-control.flatpickr-input', function() {
            $(this).parent().parent().parent().find('.error-text').html('');
        })
        $(document).on('input', 'form#create_od_request .form-control', function() {
            $(this).parent().find('.error-text').html('');
        })
        $(document).on('change', 'form#create_od_request .flatpickr-input', function() {
            $(this).parent().parent().parent().parent().find('.error-text').html('');
        })
        $(document).on('click', 'form#create_od_request .parent-picker', function() {
            $(this).parent().find('.flatpickr-input').focus();
        })
        //begin::Add Leave Ajax
        $(document).on('click', '#create_leave_request_submit_button', function(e) {
            e.preventDefault();
            var currentEmployeeId = "<?php echo session()->get('current_user')['employee_id']; ?>";
            /*if( $('.type_of_leave#type_of_leave_sick_leave').is(':checked') == true && currentEmployeeId != '40' ){
                alert('this feature is under custruction');
                return false;
            }*/
            // return false;
            var form = $('#create_leave_request');
            form.closest('.modal').modal('hide');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                // url: "<?php echo session()->get('current_user')['employee_id'] == '40' ? base_url('ajax/create-leave-request-test') : base_url('ajax/create-leave-request'); ?>",
                url: "<?php echo base_url('ajax/create-leave-request'); ?>",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {

                    console.log(response);

                    // if( currentEmployeeId == '40' ){
                    //     return false;
                    // }

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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                form.closest('.modal').modal('show');
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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                var imageInputParent = $("#attachment_select");
                                imageInputParent.removeClass('image-input-changed').addClass('image-input-empty');
                                var imageInputWrapper = $("#attachment_select .image-input-wrapper");
                                imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                                imageInputWrapper.css({
                                    'background-image': ''
                                });
                                var previewLightboxContent = $("#previewLightboxContent");
                                previewLightboxContent.attr('src', '');
                                form[0].reset();
                                // form.closest('.modal').modal('hide');
                                $("#leave_report_table").DataTable().ajax.reload();
                                $("#leave_balance_current_month").DataTable().ajax.reload();
                                $("#leave_balance_next_month").DataTable().ajax.reload();
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
                        stopKeydownPropagation: false
                    })
                }
            })
        })
        //end::Add Leave Ajax

        /*var imageInputElement = document.querySelector("#attachment_select");
        var imageInput = KTImageInput.getInstance(imageInputElement);
        imageInput.on("kt.imageinput.changed", function() {
            setTimeout(function() {
                var fileInput = $("input#attachment")[0];
                var imageInputWrapper = $("#attachment_select .image-input-wrapper");
                var previewLightboxContent = $("#previewLightboxContent");
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewLightboxContent.attr('src', e.target.result);
                    imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                    var extension = fileInput.files[0].name.split('.').pop().toLowerCase();
                    switch (extension) {
                        case 'pdf':
                            imageInputWrapper.css({
                                'background-image': 'url(<?php echo base_url(); ?>assets/media/svg/files/pdf.svg)'
                            });
                            break;
                        default:
                            break;
                    }
                }
                reader.readAsDataURL(fileInput.files[0]);
            }, 100)
        });
        imageInput.on("kt.imageinput.canceled", function() {
            setTimeout(function() {
                var imageInputWrapper = $("#attachment_select .image-input-wrapper");
                imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                imageInputWrapper.css({
                    'background-image': ''
                });
                var previewLightboxContent = $("#previewLightboxContent");
                previewLightboxContent.attr('src', '');
            }, 100)
        });

        var imageInputElement = document.querySelector("#compoff_attachment_select");
        var imageInput = KTImageInput.getInstance(imageInputElement);
        imageInput.on("kt.imageinput.changed", function() {
            setTimeout(function() {
                var fileInput = $("input#compoff_attachment")[0];
                var imageInputWrapper = $("#compoff_attachment_select .image-input-wrapper");
                var previewLightboxContent = $("#previewLightboxContent");
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewLightboxContent.attr('src', e.target.result);
                    imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                    var extension = fileInput.files[0].name.split('.').pop().toLowerCase();
                    switch (extension) {
                        case 'pdf':
                            imageInputWrapper.css({
                                'background-image': 'url(<?php echo base_url(); ?>assets/media/svg/files/pdf.svg)'
                            });
                            break;
                        default:
                            break;
                    }
                }
                reader.readAsDataURL(fileInput.files[0]);
            }, 100)
        });
        imageInput.on("kt.imageinput.canceled", function() {
            setTimeout(function() {
                var imageInputWrapper = $("#compoff_attachment_select .image-input-wrapper");
                imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                imageInputWrapper.css({
                    'background-image': ''
                });
                var previewLightboxContent = $("#previewLightboxContent");
                previewLightboxContent.attr('src', '');
            }, 100)
        });*/

        $('.image-input').each(function() {
            var id = $(this).attr('id');
            var imageInputElement = document.querySelector("#" + id);
            var imageInput = KTImageInput.getInstance(imageInputElement);

            var iframe_src_backup = '';

            imageInput.on("kt.imageinput.changed", function() {
                var fileInput = $("#" + id).find("input[type=file]")[0];
                var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                var lightboxIframe = $($("#" + id).find(".preview-button").data("bs-target")).find("iframe");
                var reader = new FileReader();
                reader.onload = function(e) {
                    lightboxIframe.attr('src', e.target.result);
                    imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                    var extension = fileInput.files[0].name.split('.').pop().toLowerCase();
                    switch (extension) {
                        case 'pdf':
                            imageInputWrapper.css({
                                'background-image': 'url(<?php echo base_url(); ?>assets/media/svg/files/pdf.svg)'
                            });
                            break;
                        default:
                            break;
                    }
                }
                reader.readAsDataURL(fileInput.files[0]);
            });

            imageInput.on("kt.imageinput.change", function() {
                var lightboxIframe = $($("#" + id).find(".preview-button").data("bs-target")).find("iframe");;
                if (iframe_src_backup == '') {
                    iframe_src_backup = lightboxIframe.attr('src');
                }
            });

            imageInput.on("kt.imageinput.canceled", function() {
                var lightboxIframe = $($("#" + id).find(".preview-button").data("bs-target")).find("iframe");;
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
                var lightboxIframe = $($("#" + id).find(".preview-button").data("bs-target")).find("iframe");;
                lightboxIframe.attr('src', '');
            });
        });


        //begin::Add OD Ajax
        $(document).on('click', '#create_od_request_submit_button', function(e) {
            e.preventDefault();
            // return false;
            var form = $('#create_od_request');
            form.closest('.modal').modal('hide');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/create-od-request'); ?>",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {

                    console.log(response);
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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                if (typeof response.response_data.validation != 'undefined') {
                                    var validation = response.response_data.validation;
                                    $.each(validation, function(index, value) {
                                        form.find('#' + index + '_error').html(value);
                                        form.closest('.modal').modal('show');
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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                form[0].reset();
                                // form.closest('.modal').modal('hide');
                                $("#od_report_table").DataTable().ajax.reload();
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
                        stopKeydownPropagation: false
                    })
                }
            })
        })
        //end::Add OD Ajax
        //begin::Add Gate Pass Ajax
        $(document).on('click', '#create_gate_pass_request_submit_button', function(e) {
            e.preventDefault();
            // return false;
            $('.error-text').html('');
            var form = $('#create_gate_pass_request');
            form.closest('.modal').modal('hide');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/create-gate-pass-request'); ?>",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {

                    console.log(response);


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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                if (typeof response.response_data.validation != 'undefined') {
                                    var validation = response.response_data.validation;
                                    $.each(validation, function(index, value) {
                                        form.find('#' + index + '_error').html(value);
                                        form.closest('.modal').modal('show');
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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                form[0].reset();
                                // form.closest('.modal').modal('hide');
                                // $("#od_report_table").DataTable().ajax.reload();
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
                        stopKeydownPropagation: false
                    })
                }
            })
        })
        //end::Add Gate Pass Ajax

        $('#comp_off_credit_request_date').flatpickr({
            enableTime: false,
            enable: <?php echo json_encode(array_column($EnabledDateForCompOffCredit, 'date')); ?>,
            minDate: "<?php echo date('Y-m-d', strtotime('-90 days')) ?>",
            maxDate: "<?php echo date('Y-m-d') ?>",
            altInput: false,
            static: true
        });
        var enabled_date_and_value = <?php echo json_encode($EnabledDateForCompOffCredit); ?>;

        $(document).on('change', '.cocr-control.flatpickr-input', function() {
            $(this).parent().parent().parent().find('.error-text').html('');
        })
        $(document).on('input', 'form#comp_off_credit_request .form-control', function() {
            $(this).parent().find('.error-text').html('');
        })
        $(document).on('change', 'form#comp_off_credit_request .flatpickr-input', function() {
            $(this).parent().parent().parent().parent().find('.error-text').html('');
        })
        $(document).on('click', 'form#comp_off_credit_request .parent-picker', function() {
            $(this).parent().find('.flatpickr-input').focus();
        })

        $('#comp_off_credit_request_duty_assigner').select2({
            dropdownParent: $("#comp_off_credit_request")
        });

        $(document).on('change', '#comp_off_credit_request_date', function(e) {
            $('#comp_off_credit_request_working_details').html(
                '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-refresh fa-spin fa-fw"></i><span class="ms-3 fs-6 text-dark">Fetching your data...</span></div>'
            );
            var comp_off_credit_request_date = $(this).val();
            $.each(enabled_date_and_value, function(enabled_date_index, enabled_date_value) {
                if (enabled_date_value.date == comp_off_credit_request_date) {
                    console.log(enabled_date_value);
                    var shift_start = (enabled_date_value.shift_start !== null) ? enabled_date_value.shift_start : '';
                    var shift_end = (enabled_date_value.shift_end !== null) ? enabled_date_value.shift_end : '';
                    var in_time__Raw = (enabled_date_value.in_time__Raw !== null) ? enabled_date_value.in_time__Raw : '';
                    var out_time__Raw = (enabled_date_value.out_time__Raw !== null) ? enabled_date_value.out_time__Raw : '';
                    var in_time_including_od = (enabled_date_value.in_time_including_od !== null) ? enabled_date_value.in_time_including_od : '';
                    var out_time_including_od = (enabled_date_value.out_time_including_od !== null) ? enabled_date_value.out_time_including_od : '';
                    $('#comp_off_credit_request_working_details').html(
                        '<ul class="list-group">' +
                        '<li class="list-group-item d-flex align-items-center justify-content-between">' +
                        '<span>Shift Start</span>' +
                        '<span>' + shift_start + '</span>' +
                        '</li>' +
                        '<li class="list-group-item d-flex align-items-center justify-content-between">' +
                        '<span>Shift End</span>' +
                        '<span>' + shift_end + '</span>' +
                        '</li>' +
                        '<li class="list-group-item d-flex align-items-center justify-content-between">' +
                        '<span>Punch In</span>' +
                        '<span>' + in_time__Raw + '</span>' +
                        '</li>' +
                        '<li class="list-group-item d-flex align-items-center justify-content-between">' +
                        '<span>Punch Out</span>' +
                        '<span>' + out_time__Raw + '</span>' +
                        '</li>' +
                        '<li class="list-group-item d-flex align-items-center justify-content-between">' +
                        '<span>Punch&OD IN</span>' +
                        '<span>' + in_time_including_od + '</span>' +
                        '</li>' +
                        '<li class="list-group-item d-flex align-items-center justify-content-between">' +
                        '<span>Punch&OD Out</span>' +
                        '<span>' + out_time_including_od + '</span>' +
                        '</li>' +
                        '</ul>'
                    );
                }
            });
            // alert(comp_off_credit_request_date);
        });
        //begin::Add COMP OFF Credit Request Ajax
        $(document).on('click', '#comp_off_credit_request_submit_button', function(e) {
            e.preventDefault();
            // return false;
            $('.error-text').html('');
            var form = $('#comp_off_credit_request');
            form.closest('.modal').modal('hide');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/backend/user/create-comp-off-credit-request'); ?>",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {

                    console.log(response);


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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                if (typeof response.response_data.validation != 'undefined') {
                                    var validation = response.response_data.validation;
                                    $.each(validation, function(index, value) {
                                        form.find('#' + index + '_error').html(value);
                                        form.closest('.modal').modal('show');
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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                form[0].reset();
                                // form.closest('.modal').modal('hide');
                                // $("#od_report_table").DataTable().ajax.reload();
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
                        stopKeydownPropagation: false
                    })
                }
            })
        })
        //end::Add COMP OFF Credit Request Ajax

        $('#comp_off_minutes_utilization_date').flatpickr({
            enableTime: false,
            minDate: "<?php echo first_date_of_last_month(); ?>",
            maxDate: "<?php echo date('Y-m-d'); ?>",
            altInput: false,
            static: true
        });

        $(document).on('click', '#comp_off_minutes_utilization_submit_button', function(e) {
            e.preventDefault();
            $('.error-text').html('');
            var form = $('#comp_off_minutes_utilization_form');
            form.closest('.modal').modal('hide');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/backend/user/create-comp-off-utilization-request'); ?>",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    // return false;

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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                if (typeof response.response_data.validation != 'undefined') {
                                    var validation = response.response_data.validation;
                                    $.each(validation, function(index, value) {
                                        form.find('#' + index + '_error').html(value);
                                        form.closest('.modal').modal('show');
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
                                stopKeydownPropagation: false
                            }).then(function(e) {
                                form[0].reset();

                                $("#leave_report_table").DataTable().ajax.reload();
                                $("#leave_balance_current_month").DataTable().ajax.reload();
                                $("#leave_balance_next_month").DataTable().ajax.reload();
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
                        stopKeydownPropagation: false
                    })
                }
            })
        })

    })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#create_leave_request_modal, #create_gate_pass_request_modal').on('shown.bs.modal', function() {
            /*$(this).find('#type_of_leave_ul').parent().trigger('click');*/
            $(this).find('#type_of_leave_cl').parent().trigger('click');
            var toggleSwitch = $(this).find('.switch-toggle');
            toggleSwitch.each(function(index, thisSwitch) {
                var checked_input = $(thisSwitch).find('label > input:checked').parent();
                var w = checked_input.outerWidth();
                var indexoflabel = checked_input.index();
                $(thisSwitch).find('a').css({
                    'width': w,
                    'left': indexoflabel * w
                });
            })
        })
        $(document).on('click', '.switch-toggle > label:not(.disabled)', function(e) {
            var w = $(this).outerWidth();
            $(this).find('input').prop('checked', true).trigger('change');
            if ($(this).find('input').val() == 'CL') {
                $(this).parent().find('a').removeClass('bg-danger').removeClass('bg-warning').removeClass('bg-info').addClass('bg-success');
            } else if ($(this).find('input').val() == 'EL') {
                $(this).parent().find('a').removeClass('bg-danger').removeClass('bg-success').removeClass('bg-info').addClass('bg-warning');
            } else if ($(this).find('input').val() == 'COMP OFF') {
                $(this).parent().find('a').removeClass('bg-warning').removeClass('bg-success').removeClass('bg-info').addClass('bg-danger');
            } else if ($(this).find('input').val() == 'SICK LEAVE') {
                $(this).parent().find('a').removeClass('bg-warning').removeClass('bg-success').removeClass('bg-danger').addClass('bg-info');
            } else if ($(this).find('input').val() == 'Early Going') {
                $(this).parent().find('a').removeClass('bg-warning').removeClass('bg-success').addClass('bg-danger');
            } else if ($(this).find('input').val() == 'Late Coming') {
                $(this).parent().find('a').removeClass('bg-danger').removeClass('bg-success').addClass('bg-warning');
            } else if ($(this).find('input').val() == 'Break Pass') {
                $(this).parent().find('a').removeClass('bg-danger').removeClass('bg-warning').addClass('bg-success');
            }
            $(this).parent().find('a').css({
                'width': w,
                'left': $(this).position().left
            });
        })
    })

    const get_interval_considering_half_day = (from_id, to_id) => {
        var from_date = $('#' + from_id).val();
        var to_date = $('#' + to_id).val();
        if (from_date.length && to_date.length) {
            var dt1 = new Date(from_date);
            var dt2 = new Date(to_date);
            var time_difference = dt2.getTime() - dt1.getTime();
            var result = time_difference / (1000 * 60 * 60 * 24);
            var number_of_days = result + 1;
        } else {
            var number_of_days = 0;
        }
        return number_of_days;
    }

    const get_interval_considering_half_day_excluding_rh = (from_id, to_id) => {
        var from_date = $('#' + from_id).val();
        var to_date = $('#' + to_id).val();
        if (from_date.length && to_date.length) {
            var dt1 = new Date(from_date);
            var dt2 = new Date(to_date);
            var time_difference = dt2.getTime() - dt1.getTime();
            var result = time_difference / (1000 * 60 * 60 * 24);

            //Begin::exclude RH Date
            var _first_rh_date = $("#first_rh_date").val();
            var _second_rh_date = $("#second_rh_date").val();

            if (_first_rh_date.length) {
                _first_rh_date_parsed = new Date(_first_rh_date);
                if (_first_rh_date_parsed) {
                    var _first_rh_date_timestamp = _first_rh_date_parsed.getTime();
                    var from_timestamp = dt1.getTime();
                    var to_timestamp = dt2.getTime();
                    if (_first_rh_date_timestamp >= from_timestamp && _first_rh_date_timestamp <= to_timestamp) {
                        result--;
                    }
                }
            } else if (_second_rh_date.length) {
                _second_rh_date_parsed = new Date(_second_rh_date);
                if (_second_rh_date_parsed) {
                    var _second_rh_date_timestamp = _second_rh_date_parsed.getTime();
                    var from_timestamp = dt1.getTime();
                    var to_timestamp = dt2.getTime();
                    if (_second_rh_date_timestamp >= from_timestamp && _second_rh_date_timestamp <= to_timestamp) {
                        result--;
                    }
                }
            }
            //End::exclude RH Date
            var number_of_days = result + 1;
        } else {
            var number_of_days = 0;
        }
        return number_of_days;
    }
</script>

<?php
$etime_office_error = session()->getFlashdata('etime_office_error');
if (!empty($etime_office_error)) {
?>
    <script type="text/javascript">
        $(document).ready(function() {
            Swal.fire({
                html: "<?php echo $etime_office_error; ?>",
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                stopKeydownPropagation: false
            })
        })
    </script>
<?php
}
?>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        /*begin::probation_ended*/
        var probation_ended = $("#probation_ended").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">f>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-probation-employees') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
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
                    data: "employee_name"
                },
                // { data: "formatted_joining_date" },
                {
                    data: {
                        _: 'joining_date.formatted',
                        sort: 'joining_date.ordering',
                    },
                },
                {
                    data: "probation_status"
                },
                {
                    data: "employee_id",
                    render: function(data, type, row, meta) {
                        var link = "<?php echo base_url('/backend/master/employee/edit/id'); ?>/" + row.employee_id;
                        return '<a href="' + link + '" class="btn btn-icon btn-sm btn-bg-light btn-active-color-primary edit-employee p-0" style="width: max-content; height: max-content;" target="_blank"><span class="svg-icon svg-icon-3"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true" ></i></span></a>';
                    }
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": true,
            "columnDefs": [{
                "className": 'text-center small',
                "targets": '_all'
            }, ],
            // initComplete: function(json) {
            // let returned_data = json;
            // console.log(returned_data);
            // }
        });
        $('#probation_ended_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Confirmation pending from HR</h3>');
        // $('#probation_ended_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Probation completed</h3>');
        // $('#probation_ended_wrapper > .card > .card-footer').html('<small class="d-block">Probation completed</small>');
        /*end::leave_balance_current_month*/

    })
</script>


<script type="text/javascript">
    jQuery(document).ready(function($) {

        /*begin::recently_joined*/
        var recently_joined = $("#recently_joined").DataTable({
            "dom": '<"card"<"card-header py-0 pe-0"<"card-title"><"card-toolbar my-0"<"datatable-buttons-container me-1"B><"toolbar-buttons">f>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-welcome-email-waiting') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
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
                    data: "employee_name"
                },
                {
                    data: {
                        _: 'joining_date.formatted',
                        sort: 'joining_date.ordering',
                    },
                },
                {
                    data: "employee_id",
                    render: function(data, type, row, meta) {
                        return `<a href="#" data-id="${row.employee_id}" class="btn btn-icon btn-sm btn-bg-light btn-active-color-primary send-welcome-email p-0" style="width: max-content; height: max-content;" target="_blank"><span class="svg-icon svg-icon-3"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true" ></i></span></a>`;
                    }
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": true,
            "columnDefs": [{
                "className": 'text-center small',
                "targets": '_all'
            }, ],
            // initComplete: function(json) {
            // let returned_data = json;
            // console.log(returned_data);
            // }
        });
        $('#recently_joined_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title my-0">Pending welcome email</h3>');
        // $('#recently_joined_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title my-0">Send welcome email</h3>');
        /*begin::upcoming_birthdays*/
        var upcoming_birthdays = $("#upcoming_birthdays").DataTable({
            "dom": '<"card"<"card-header py-0 pe-0"<"card-title"><"card-toolbar my-0"f>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-upcoming-birthdays') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.error(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No upcoming birthdays</span></div>',
                searchPlaceholder: "Search"
            },
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    data: "employee_name",
                    render: function(data, type, row) {
                        //return data + ' <span class="text-muted">(' + row.employee_code + ')</span>';
                        return data + ' <span class="text-muted">(' + row.department_name + ') - ' + row.company_name + '</span>';
                        return data;
                    }
                },
                {
                    data: "birthday_display"
                },
                {
                    data: "days_left_label",
                    render: function(data, type, row) {
                        if (type === 'sort') return row.days_left;
                        return data;
                    }
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": true,
            "order": [
                [2, "asc"]
            ],
            "columnDefs": [{
                    "className": "text-center small",
                    "targets": "_all"
                },
                {
                    "className": "text-start",
                    "targets": 0
                },
                {
                    "className": "text-end",
                    "targets": 2
                },
            ],
        });
        $('#upcoming_birthdays_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title my-0">Upcoming Birthdays 🎂</h3>');
        /*end::upcoming_birthdays*/


        $(document).on('click', '.send-welcome-email', function(e) {
            e.preventDefault();
            var button = $(this);
            var button_html = button.html();

            console.log(button_html);
            console.log(button.data('id'));

            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/hr/employee/send-welcome-email'); ?>",
                data: {
                    'employee_id': $(this).data('id')
                },
                success: function(response) {

                    console.log(response);
                    if (response.response_type == 'failed') {
                        Swal.fire({
                            html: response.response_description,
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        })
                    }

                    if (response.response_type == 'success') {
                        Swal.fire({
                            html: response.response_description,
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        }).then(function(e) {
                            $("#recently_joined").DataTable().ajax.reload();
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        html: "Ajax Failed while sending welcome email, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                }
            }).always(function() {
                button.html(button_html);
            });

            $(this).html(`<span class="svg-icon svg-icon-3"><i class="fa fa-refresh fa-spin" aria-hidden="true" ></i></span>`);
        })

    })

    $(document).ready(function() {
        // Check for probation completion on page load
        $.ajax({
            url: "<?= base_url('/ajax/profile/probation-completed-notification') ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === 'show_modal') {
                    var myModal = new bootstrap.Modal(document.getElementById('probationNotificationModal'), {});
                    myModal.show();
                }
            }
        });

        // Handle acknowledgment
        $('#acknowledgeProbationBtn').on('click', function() {
            $.ajax({
                url: "<?= base_url('/ajax/profile/acknowledge-probation') ?>",
                type: "POST",
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        var myModal = bootstrap.Modal.getInstance(document.getElementById('probationNotificationModal'));
                        myModal.hide();
                        Swal.fire('Success', 'Probation status acknowledged.', 'success');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        });
    });
</script>
<style type="text/css">
    #recently_joined_wrapper>.card>.card-header {
        min-height: unset;
    }
</style>


<div class="modal fade address-modal" id="addressConfirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="probationNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="probationNotificationModalLabel">
                    <i class="fas fa-home me-2"></i>Address Confirmation Required
                </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Please confirm or update your current address. This is required every 6 months as per company policy.
                </div>
                <form id="addressForm" enctype="multipart/form-data">
                    <!-- Address Text -->
                    <div class="mb-4">
                        <label for="address_text" class="form-label required-field">Current Address</label>
                        <textarea class="form-control" id="address_text" name="address_text" rows="4"
                            placeholder="Enter your complete current address..." required></textarea>
                        <div class="error-text" id="address_text_error"></div>
                    </div>

                    <!-- Document Type -->
                    <div class="mb-4">
                        <label for="document_type" class="form-label required-field">Document Type</label>
                        <select class="form-select" id="document_type" name="document_type" required>
                            <option value="">Select document type...</option>
                            <option value="rent_agreement">Rent Agreement</option>
                            <option value="aadhaar">Aadhaar Card</option>
                            <option value="other">Other Address Proof</option>
                        </select>
                        <div class="error-text" id="document_type_error"></div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label class="form-label required-field">Upload Address Document</label>
                        <div class="file-upload-area" id="fileUploadArea">
                            <input type="file" class="form-control" id="address_document" name="address_document"
                                accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                        <div class="error-text" id="address_document_error"></div>
                    </div>

                    <!-- Important Note -->
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> The address entered above must match the address on the uploaded document.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="snoozeBtn">
                    <i class="fas fa-clock me-1"></i>Snooze for 1 Day
                </button>
                <button type="submit" form="addressForm" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-paper-plane me-1"></i>Submit for Review
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Processing your request...</p>
            </div>
        </div>
    </div>
</div>



<!-- ==================== RESIGNATION HOD ACKNOWLEDGMENT MODALS ==================== -->

<?php
// HOD Resignation Acknowledgment Modal (Bootstrap - one at a time)
if (!empty($resignationHodAcknowledgments)) {
?>
    <script type="text/javascript">
        $(document).ready(function() {
            const resignations = JSON.parse('<?php echo json_encode($resignationHodAcknowledgments); ?>');
            let currentIndex = 0;

            function showHodResignationModal(index) {
                if (index >= resignations.length) return;

                const r = resignations[index];
                $('#hod-record-id').val(r.id);
                $('#hod-employee-name').text(r.employee_name + ' - (' + (r.internal_employee_id) + ')');
                // $('#hod-employee-name').text(r.employee_name);
                // $('#hod-employee-id').text(r.internal_employee_id || 'N/A');
                $('#hod-department').text(r.department_name || 'N/A');
                $('#hod-designation').text(r.designation_name || 'N/A');
                $('#hod-company').text(r.company_name || 'N/A');
                $('#hod-manager-name').text(r.manager_name || 'N/A');
                $('#hod-resignation-date').text(r.resignation_date_formatted);
                $('#hod-last-working-date').text(r.last_working_date_formatted);
                $('#hod-reason').text(r.resignation_reason || 'Not specified');
                $('#hod-remaining-days').text(r.remaining_days + ' days');

                // Urgent badge
                const $urgentBadge = $('#hod-urgent-badge');
                if (r.is_urgent) {
                    $urgentBadge.show();
                } else {
                    $urgentBadge.hide();
                }

                // Counter
                $('#hod-counter').text('(' + (index + 1) + ' of ' + resignations.length + ')');

                // Reset action and rejection reason
                $('#hod-action-select').val('');
                $('#hod-rejection-reason-container').hide();
                $('#hod-rejection-reason').val('');
                $('#hod-action-error').hide();
                $('#hod-rejection-error').hide();

                $('#hodResignationAckModal').modal('show');
            }

            // Show first resignation
            showHodResignationModal(0);

            // Show/hide rejection reason based on action
            $('#hod-action-select').on('change', function() {
                if ($(this).val() === 'reject') {
                    $('#hod-rejection-reason-container').slideDown();
                } else {
                    $('#hod-rejection-reason-container').slideUp();
                    $('#hod-rejection-reason').val('');
                }
                $('#hod-action-error').hide();
                $('#hod-rejection-error').hide();
            });

            // Submit button
            $('#hod-submit-btn').on('click', function() {
                const recordId = $('#hod-record-id').val();
                const action = $('#hod-action-select').val();
                const rejectionReason = $('#hod-rejection-reason').val();

                // Validate
                $('#hod-action-error').hide();
                $('#hod-rejection-error').hide();

                if (!action) {
                    $('#hod-action-error').show();
                    return;
                }
                if (action === 'reject' && !rejectionReason.trim()) {
                    $('#hod-rejection-error').show();
                    return;
                }

                const $btn = $(this);
                const btnHtml = $btn.html();
                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm me-1"></span>Processing...');

                const responses = {};
                responses[recordId] = {
                    action: action,
                    rejection_reason: rejectionReason || null
                };

                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('/ajax/resignation/save-hod-response'); ?>",
                    data: {
                        responses: responses
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.response_type === 'success') {
                            $('#hodResignationAckModal').modal('hide');
                            currentIndex++;

                            if (currentIndex < resignations.length) {
                                // Show next resignation after brief delay
                                setTimeout(function() {
                                    showHodResignationModal(currentIndex);
                                }, 500);
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'All Done!',
                                    text: 'All resignation responses saved successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        } else {
                            Swal.fire('Error', response.response_description, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to save response. Please try again.', 'error');
                    },
                    complete: function() {
                        $btn.html(btnHtml).prop('disabled', false);
                    }
                });
            });
        });
    </script>

    <!-- HOD Resignation Acknowledgment Bootstrap Modal -->
    <div class="modal fade" id="hodResignationAckModal" tabindex="-1" aria-labelledby="hodResignationAckModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100" id="hodResignationAckModalLabel" style="text-align: center;">
                        <span class="me-2">📋</span><span>Resignation Acknowledgment Required</span>
                    </h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hod-record-id">

                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        As HOD, your acknowledgment is required for the following resignation.
                    </div>

                    <span id="hod-urgent-badge" class="badge bg-danger mb-3" style="display: none;">URGENT - Less than 7 days remaining</span>

                    <!-- Employee Information Card -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Employee Information</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Name:</strong></div>
                                <div class="col-md-8" id="hod-employee-name"></div>
                            </div>
                            <!-- <div class="row mb-2">
                                <div class="col-md-4"><strong>Employee ID:</strong></div>
                                <div class="col-md-8" id="hod-employee-id"></div>
                            </div> -->
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Department:</strong></div>
                                <div class="col-md-8" id="hod-department"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Designation:</strong></div>
                                <div class="col-md-8" id="hod-designation"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Company:</strong></div>
                                <div class="col-md-8" id="hod-company"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><strong>Reporting Manager:</strong></div>
                                <div class="col-md-8" id="hod-manager-name"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Resignation Details Card -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Resignation Details</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-5"><strong>Resignation Date:</strong></div>
                                <div class="col-md-7" id="hod-resignation-date"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-5"><strong>Last Working Date:</strong></div>
                                <div class="col-md-7" id="hod-last-working-date"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-5"><strong>Remaining Days:</strong></div>
                                <div class="col-md-7" id="hod-remaining-days"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5"><strong>Reason:</strong></div>
                                <div class="col-md-7" id="hod-reason"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Card -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong>Your Response</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <select id="hod-action-select" class="form-select">
                                    <option value="" disabled selected>Select Action</option>
                                    <option value="too_early">Remind Me Later</option>
                                    <option value="accept">Accept</option>
                                    <option value="reject">Reject</option>
                                </select>
                                <div id="hod-action-error" class="text-danger mt-1" style="display: none; font-size: 0.85rem;">Please select an action!</div>
                            </div>
                            <div id="hod-rejection-reason-container" style="display: none;">
                                <textarea id="hod-rejection-reason" class="form-control" placeholder="Rejection reason (required)" rows="3"></textarea>
                                <div id="hod-rejection-error" class="text-danger mt-1" style="display: none; font-size: 0.85rem;">Please provide a rejection reason!</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-lg btn-success" id="hod-submit-btn">
                        <i class="fa fa-check me-2"></i>Submit Response
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<!-- ==================== REPORTING MANAGER RESIGNATION NOTIFICATION ==================== -->

<!-- Reporting Manager Resignation Notification Script -->
<script>
    $(document).ready(function() {
        setTimeout(function() {
            checkForReportingManagerResignationNotifications();
        }, 3000);

        function checkForReportingManagerResignationNotifications() {
            $.ajax({
                url: '<?= base_url("/ajax/resignation/reporting-manager-notifications") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.notifications.length > 0) {
                        showReportingManagerResignationModal(response.notifications[0]);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching reporting manager notifications:', error);
                }
            });
        }

        function showReportingManagerResignationModal(notification) {
            $('#rm-record-id').val(notification.id);
            $('#rm-employee-name').text(notification.employee_name + ' - (' + (notification.internal_employee_id) + ')');
            // $('#rm-employee-name').text(notification.employee_name);
            // $('#rm-employee-id').text(notification.internal_employee_id || 'N/A');
            $('#rm-department').text(notification.department_name || 'N/A');
            $('#rm-designation').text(notification.designation_name || 'N/A');
            $('#rm-company').text(notification.company_name || 'N/A');
            $('#rm-resignation-date').text(notification.resignation_date_formatted);
            $('#rm-last-working-date').text(notification.last_working_date_formatted);
            $('#rm-reason').text(notification.resignation_reason || 'Not specified');
            $('#rm-hod-name').text(notification.hod_name || 'N/A');

            // Show HOD response status
            const hodResponse = notification.hod_response || 'pending';
            const $hodBadge = $('#rm-hod-response');
            $hodBadge.text(hodResponse.charAt(0).toUpperCase() + hodResponse.slice(1));
            $hodBadge.removeClass('bg-success bg-danger bg-warning bg-secondary');
            if (hodResponse === 'accept') {
                $hodBadge.addClass('bg-success');
            } else if (hodResponse === 'rejected') {
                $hodBadge.addClass('bg-danger');
            } else {
                $hodBadge.addClass('bg-warning text-dark');
            }

            $('#reportingManagerResignationModal').modal('show');
        }

        $('#rm-acknowledge-btn').click(function() {
            const recordId = $('#rm-record-id').val();
            const $btn = $(this);
            const btnHtml = $btn.html();

            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...');

            $.ajax({
                url: '<?= base_url("/ajax/resignation/reporting-manager-notification-action") ?>',
                method: 'POST',
                data: {
                    record_id: recordId,
                    action: 'viewed'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#reportingManagerResignationModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Acknowledged',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Check for next notification
                        setTimeout(checkForReportingManagerResignationNotifications, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process. Please try again.'
                    });
                },
                complete: function() {
                    $btn.html(btnHtml).prop('disabled', false);
                }
            });
        });
    });
</script>

<!-- Reporting Manager Resignation Notification Bootstrap Modal -->
<div class="modal fade" id="reportingManagerResignationModal" tabindex="-1" aria-labelledby="reportingManagerResignationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="reportingManagerResignationModalLabel" style="text-align: center;">
                    <span class="me-2">📋</span><span>Employee Resignation Notification</span>
                </h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rm-record-id">

                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    An employee under your supervision has submitted their resignation.
                </div>

                <div>
                    <!-- Employee Information Card -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Employee Information</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Name:</strong></div>
                                <div class="col-md-8" id="rm-employee-name"></div>
                            </div>
                            <!-- <div class="row mb-2">
                                <div class="col-md-4"><strong>Employee ID:</strong></div>
                                <div class="col-md-8" id="rm-employee-id"></div>
                            </div> -->
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Department:</strong></div>
                                <div class="col-md-8" id="rm-department"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Designation:</strong></div>
                                <div class="col-md-8" id="rm-designation"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><strong>Company:</strong></div>
                                <div class="col-md-8" id="rm-company"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Resignation Details Card -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Resignation Details</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-5"><strong>Resignation Date:</strong></div>
                                <div class="col-md-7" id="rm-resignation-date"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-5"><strong>Last Working Date:</strong></div>
                                <div class="col-md-7" id="rm-last-working-date"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5"><strong>Reason:</strong></div>
                                <div class="col-md-7" id="rm-reason"></div>
                            </div>
                        </div>
                    </div>

                    <!-- HOD Status Card -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong>HOD Status</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>HOD Name:</strong></div>
                                <div class="col-md-8" id="rm-hod-name"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><strong>HOD Response:</strong></div>
                                <div class="col-md-8">
                                    <span id="rm-hod-response" class="badge bg-warning text-dark"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-lg btn-success" id="rm-acknowledge-btn">
                    <i class="fa fa-check me-2"></i>Acknowledge
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hrManagerResignationNotificationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">Resignation Notification</h5>

            </div>
            <div class="modal-body" id="hrManagerResignationNotificationContent">
                <!-- Content populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-primary" id="markHrManagerNotificationViewedBtn">Acknowledge</button>
            </div>
        </div>
    </div>
</div>
<!-- ==================== END REPORTING MANAGER RESIGNATION NOTIFICATION ==================== -->

<!-- ==================== HR MANAGER RESIGNATION NOTIFICATION (after HOD responds) ==================== -->

<!-- HR Manager Resignation Notification Script -->
<script>
    $(document).ready(function() {
        setTimeout(function() {
            checkForHrResignationNotifications();
        }, 2500);

        function checkForHrResignationNotifications() {
            $.ajax({
                url: '<?= base_url("/ajax/resignation/manager-notifications") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.notifications.length > 0) {
                        showHrResignationModal(response.notifications[0]);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching HR resignation notifications:', error);
                }
            });
        }

        function showHrResignationModal(notification) {
            $('#rn-record-id').val(notification.id);
            $('#rn-employee-name').text(notification.employee_name + ' - (' + (notification.internal_employee_id) + ')');
            // $('#rn-employee-name').text(notification.employee_name);
            // $('#rn-employee-id').text(notification.internal_employee_id || 'N/A');
            $('#rn-department').text(notification.department_name || 'N/A');
            $('#rn-designation').text(notification.designation_name || 'N/A');
            $('#rn-company').text(notification.company_name || 'N/A');
            $('#rn-resignation-date').text(notification.resignation_date_formatted);
            $('#rn-last-working-date').text(notification.last_working_date_formatted);
            $('#rn-hod-name').text(notification.hod_name);
            $('#rn-hod-response').text(notification.hod_response);

            // Set response badge color
            const $responseBadge = $('#rn-hod-response');
            $responseBadge.removeClass('bg-success bg-danger bg-warning');
            if (notification.hod_response === 'accept') {
                $responseBadge.addClass('bg-success');
            } else if (notification.hod_response === 'rejected') {
                $responseBadge.addClass('bg-danger');
            }

            // Show/hide rejection reason
            if (notification.hod_response === 'rejected' && notification.hod_rejection_reason) {
                $('#rn-rejection-reason-container').show();
                $('#rn-rejection-reason').text(notification.hod_rejection_reason);
            } else {
                $('#rn-rejection-reason-container').hide();
            }

            $('#resignationHrNotificationModal').modal('show');
        }

        $('#rn-acknowledge-btn').click(function() {
            const recordId = $('#rn-record-id').val();
            const $btn = $(this);
            const btnHtml = $btn.html();

            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...');

            $.ajax({
                url: '<?= base_url("/ajax/resignation/manager-notification-action") ?>',
                method: 'POST',
                data: {
                    record_id: recordId,
                    action: 'viewed'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#resignationHrNotificationModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Acknowledged',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Check for next notification
                        setTimeout(checkForHrResignationNotifications, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process. Please try again.'
                    });
                },
                complete: function() {
                    $btn.html(btnHtml).prop('disabled', false);
                }
            });
        });
    });
</script>

<!-- HR Manager Resignation Notification Bootstrap Modal -->
<div class="modal fade" id="resignationHrNotificationModal" tabindex="-1" aria-labelledby="resignationHrNotificationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="resignationHrNotificationModalLabel" style="text-align: center;">
                    <span class="me-2">🔔</span><span>Resignation Response Notification</span>
                </h5>
            </div>
            <div class="modal-body" style="text-align: center;">
                <div class="alert alert-warning text-start" role="alert" style="margin-bottom: 15px;">
                    The Head of Department (HOD) has reviewed and responded to the following resignation. Please acknowledge to confirm receipt.
                </div>
                <input type="hidden" id="rn-record-id">

                <div class="text-start" style="padding: 0 20px;">
                    <!-- Employee Information Card -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Employee Information</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Name:</strong></div>
                                <div class="col-md-8" id="rn-employee-name"></div>
                            </div>
                            <!-- <div class="row mb-2">
                                <div class="col-md-4"><strong>Employee ID:</strong></div>
                                <div class="col-md-8" id="rn-employee-id"></div>
                            </div> -->
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Department:</strong></div>
                                <div class="col-md-8" id="rn-department"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Designation:</strong></div>
                                <div class="col-md-8" id="rn-designation"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><strong>Company:</strong></div>
                                <div class="col-md-8" id="rn-company"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Resignation Details Card -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Resignation Details</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-5"><strong>Resignation Date:</strong></div>
                                <div class="col-md-7" id="rn-resignation-date"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5"><strong>Last Working Date:</strong></div>
                                <div class="col-md-7" id="rn-last-working-date"></div>
                            </div>
                        </div>
                    </div>

                    <!-- HOD Response Card -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong>HOD Response</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>HOD Name:</strong></div>
                                <div class="col-md-8" id="rn-hod-name"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Response:</strong></div>
                                <div class="col-md-8">
                                    <span id="rn-hod-response" class="badge bg-success"></span>
                                </div>
                            </div>
                            <div id="rn-rejection-reason-container" style="display: none;">
                                <div class="row">
                                    <div class="col-md-4"><strong>Reason:</strong></div>
                                    <div class="col-md-8">
                                        <div class="alert alert-danger mb-0" id="rn-rejection-reason"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-lg btn-success" id="rn-acknowledge-btn">
                    <i class="fa fa-check me-2"></i>Acknowledge
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== END HR MANAGER RESIGNATION NOTIFICATION ==================== -->

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // checkAddressPopup();
    });

    $(document).on('click', '#snoozeBtn', function() {
        handleSnooze();
    });

    function checkAddressPopup() {
        $.ajax({
            url: '/address-confirmation/check-popup',
            method: 'GET',
            success: function(response) {
                if (response.show_popup) {
                    showAddressConfirmationModal();
                }
            }
        });
    }

    function showAddressConfirmationModal() {
        // var addressConfirmationModal = new bootstrap.Modal(document.getElementById('addressConfirmationModal'), {});
        // addressConfirmationModal.show();
        $("#addressConfirmationModal").modal('show');
    }

    function handleSnooze() {
        $('#snoozeBtn').prop('disabled', true);

        $.ajax({
            url: '/address-confirmation/snooze',
            method: 'POST',
            success: function(response) {
                if (response.status === 'success') {
                    // var addressConfirmationModal = new bootstrap.Modal(document.getElementById('addressConfirmationModal'), {});
                    $("#addressConfirmationModal").modal('hide');
                    alert('Address confirmation snoozed for 1 day');
                }
            },
            error: function() {
                alert('Failed to snooze. Please try again.');
            },
            complete: function() {
                $('#snoozeBtn').prop('disabled', false);
            }
        });
    }

    $('#addressForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '/address-confirmation/submit',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    $('#addressConfirmationModal').modal('hide');
                    alert('Address confirmation submitted successfully');
                } else {
                    alert('Failed to submit the form');
                }
            }
        });
    });

    function snoozePopup() {
        $.ajax({
            url: '/address-confirmation/snooze',
            method: 'POST',
            success: function(response) {
                $('#addressConfirmationModal').modal('hide');
            }
        });
    }


    $(document).ready(function() {
        var badgeRequest;
        loadJobNotifications();
        updateNotificationBadge();

        setInterval(function() {
            updateNotificationBadge();
        }, 30000);


        $('#refresh-notifications').on('click', function() {
            loadJobNotifications();
            updateNotificationBadge();
        });

        $(document).on('click', '.notification-item', function() {
            const jobId = $(this).data('job-id');
            const redirectUrl = '<?= base_url('/recruitment/job-listing/view/') ?>' + jobId;
            markNotificationAsRead(jobId, redirectUrl);
        });

        function loadJobNotifications() {

            $('#notifications-loading').removeClass('d-none');
            $('#notifications-empty').addClass('d-none');
            $('#notifications-list').empty();

            $.ajax({
                url: '<?= base_url('/recruitment/job-listing/comments/get-notifications') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('#notifications-loading').addClass('d-none');

                    if (response && response.status === 'success') {
                        displayJobNotifications(response.notifications);
                        updateNotificationCount(response.total_unread);
                    } else {
                        console.error('Response success but wrong format:', response);
                        $('#notifications-list').html('<div class="text-center text-muted py-3">Could not load notifications.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#notifications-loading').addClass('d-none');
                    $('#notifications-list').html('<div class="text-center text-muted py-3"><i class="fa fa-exclamation-circle me-1"></i>Failed to load notifications. <a href="#" onclick="loadJobNotifications();return false;">Retry</a></div>');
                },
                complete: function() {
                    $('#notifications-loading').hide();
                }
            });
        }

        function displayJobNotifications(notifications) {
            const container = $('#notifications-list');

            if (notifications.length === 0) {
                $('#notifications-empty').removeClass('d-none');
                return;
            }

            notifications.forEach(function(notification) {
                const timeAgo = formatTimeAgo(notification.latest_time);
                const typeIcon = getTypeIcon(notification.latest_type);
                const companyName = notification.company_name ? ` - ${notification.company_name}` : '';

                const notificationHtml = `
                    <div class="col-md-6 col-lg-4">
                        <div class="card border border-hover-primary notification-item cursor-pointer" data-job-id="${notification.job_id}">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="symbol symbol-45px me-3">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="${typeIcon} text-primary fs-3"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-gray-800 fs-6">${notification.job_title}</div>
                                        <div class="text-muted fs-7">${companyName}</div>
                                    </div>
                                    <span class="badge badge-light-danger">${notification.unread_count}</span>
                                </div>
                                <div class="mb-3">
                                    <div class="text-muted fs-7 mb-1">Latest from ${notification.latest_sender}:</div>
                                    <div class="text-gray-800 fs-7 text-truncate" style="max-height: 40px; overflow: hidden;">
                                        ${notification.latest_message}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-light-${getTypeBadgeColor(notification.latest_type)}">${notification.latest_type.replace('_', ' ').toUpperCase()}</span>
                                    <span class="text-muted fs-8">${timeAgo}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.append(notificationHtml);
            });
        }



        function updateNotificationBadge() {
            // Abort any pending badge request before starting a new one
            if (badgeRequest && badgeRequest.readyState !== 4) {
                badgeRequest.abort();
            }

            badgeRequest = $.ajax({
                url: '<?= base_url('/recruitment/job-listing/comments/unread-count') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const count = response.unread_count;
                        const badge = $('#notification-badge');

                        if (count > 0) {
                            badge.text(count).show();
                        } else {
                            badge.hide();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Ignore aborted requests
                    if (status !== 'abort') {
                        console.error('Error updating notification badge:', error);
                    }
                }
            });
        }

        function updateNotificationCount(count) {
            const badge = $('#notification-badge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        }

        function markNotificationAsRead(jobId, redirectUrl) {
            $.ajax({
                url: '<?= base_url('/recruitment/job-listing/comments/mark-as-read') ?>',
                type: 'POST',
                data: {
                    job_id: jobId
                },
                dataType: 'json',
                complete: function() {
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    }
                }
            });
        }

        function showEmptyState() {
            $('#notifications-empty').removeClass('d-none');
        }

        function getTypeIcon(type) {
            switch (type) {
                case 'question':
                    return 'fa fa-question-circle';
                case 'answer':
                    return 'fa fa-check-circle';
                case 'issue':
                    return 'fa fa-exclamation-triangle';
                case 'resolution':
                    return 'fa fa-check-square';
                case 'concern':
                    return 'fa fa-exclamation-circle';
                case 'suggestion':
                    return 'fa fa-lightbulb';
                case 'feedback':
                    return 'fa fa-comment';
                default:
                    return 'fa fa-comment';
            }
        }

        function getTypeBadgeColor(type) {
            switch (type) {
                case 'question':
                    return 'warning';
                case 'answer':
                    return 'success';
                case 'issue':
                    return 'danger';
                case 'resolution':
                    return 'primary';
                case 'concern':
                    return 'danger';
                case 'suggestion':
                    return 'info';
                case 'feedback':
                    return 'secondary';
                default:
                    return 'secondary';
            }
        }

        function formatTimeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' min ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hr ago';
            if (diffInSeconds < 2592000) return Math.floor(diffInSeconds / 86400) + ' day ago';
            return Math.floor(diffInSeconds / 2592000) + ' month ago';
        }

        function checkJobListingNotifications() {
            if ($('#jobListingNotificationModal').is(':visible') || $('body').hasClass('modal-open') || $('.swal2-container').is(':visible')) {
                return;
            }

            $.ajax({
                url: "<?= base_url('/recruitment/job-listing/pending-notifications') ?>",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'show_modal' && response.jobs?.length > 0) {
                        const pendingList = $('#pending-jobs-list').empty();

                        response.jobs.forEach(job => {
                            const jobUrl = `<?= base_url('/recruitment/job-listing/view/') ?>${job.id}`;
                            pendingList.append(`
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${job.job_title}</strong>
                                    <small class="d-block text-muted">
                                        For ${job.department_name || 'N/A'} | By: ${job.created_by_name || 'N/A'}
                                    </small>
                                </div>
                                <a href="${jobUrl}" class="btn btn-sm btn-outline-primary mark-job-as-read" data-job-id="${job.id}">Viewed</a>
                            </li>
                        `);
                        });

                        $('#jobListingNotificationModal').modal('show');
                    }
                }
            });
        }

        setTimeout(checkJobListingNotifications, 5000);

        $(document).on('click', '.mark-job-as-read', function(e) {

            e.preventDefault();

            const jobId = $(this).data('job-id');
            const redirectUrl = $(this).attr('href');

            if (jobId) {
                $.ajax({
                    url: "<?= base_url('/recruitment/job-listing/mark-as-read') ?>",
                    type: 'POST',
                    data: {
                        job_id: [jobId],
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = redirectUrl;
                    }
                });
            }
        });

    });
</script>



<?= $this->endSection() ?>
<?= $this->endSection() ?>