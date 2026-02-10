<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
    .form-floating>.form-select~label,
    .form-floating>textarea:focus~label,
    .form-floating>textarea:not(:placeholder-shown)~label {
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
    .form-floating>.form-select~label::after,
    .form-floating>textarea:focus~label::after,
    .form-floating>textarea:not(:placeholder-shown)~label::after {
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

    textarea.form-control {
        height: 120px !important;
        padding-top: 1.5rem;
    }

    .card-body {
        background-color: #f5f8fa;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!--begin::Row-->
<div class="container mt-4">
    <div class="row gy-5 g-xl-8 justify-content-md-center">
        <!--begin::Col-->
        <div class="col col-xl-8">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Create Employee Notification</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Fill in the details to create a new notification</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="<?= base_url('backend/notifications') ?>" class="btn btn-sm btn-light">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body">
                    <form id="createNotificationForm">
                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-9">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="notif_title" name="title" placeholder="Notification Title" required>
                                    <label for="notif_title">Notification Title <span class="text-danger">*</span></label>
                                </div>
                            </div>


                            <!-- Event Date -->
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control notification-datepicker" id="notif_event_date" name="event_date" placeholder="Select Event Date" required>
                                    <label for="notif_event_date">Event Date <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <!-- Notification Type -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Notification Type <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="notification_type" value="event" id="type_event" required>
                                            <label class="form-check-label" for="type_event">
                                                🎉 Event
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="notification_type" value="reminder" id="type_reminder" required>
                                            <label class="form-check-label" for="type_reminder">
                                                ⏰ Reminder
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="notification_type" value="alert" id="type_alert" required>
                                            <label class="form-check-label" for="type_alert">
                                                ⚠️ Alert
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="notification_type" value="announcement" id="type_announcement" required>
                                            <label class="form-check-label" for="type_announcement">
                                                📢 Announcement
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="notification_type" value="policy" id="type_policy" required>
                                            <label class="form-check-label" for="type_policy">
                                                📋 Policy
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="notification_type" value="other" id="type_other" required>
                                            <label class="form-check-label" for="type_other">
                                                💬 Other
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Description -->
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="notif_description" name="description" placeholder="Description" style="min-height: 120px; resize: vertical;" required></textarea>
                                    <label for="notif_description">Description <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <!-- Reminder Dates Repeater -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Reminder Dates <small class="text-muted">(Optional)</small></label>
                                    <div id="reminder_dates_repeater">
                                        <div class="form-group">
                                            <div data-repeater-list="reminder_dates">
                                                <div data-repeater-item>
                                                    <div class="form-group row mb-3">
                                                        <div class="col-md-10">
                                                            <div class="form-floating">
                                                                <input type="text" name="reminder_date" class="form-control notification-datepicker-repeater" placeholder="Select Reminder Date">
                                                                <label>Reminder Date</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
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
                                        <div class="form-group">
                                            <a href="javascript:;" data-repeater-create class="btn btn-sm btn-link">
                                                <i class="la la-plus"></i>Add Reminder Date
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Target Employees -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Target Employees <small class="text-muted">(Leave empty for all employees)</small></label>
                                    <select class="form-select" id="notif_target_employees" name="target_employees[]" multiple data-control="select2" data-placeholder="Select specific employees or leave empty for all">
                                        <?php foreach ($employees as $emp): ?>
                                            <option value="<?= $emp['id'] ?>"><?= $emp['internal_employee_id'] ?> - <?= $emp['first_name'] ?> <?= $emp['last_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Hidden field to always set notifications as active -->
                            <input type="hidden" name="is_active" value="1">

                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary" id="saveNotificationBtn">
                                        <i class="fa fa-save me-2"></i>Create Notification
                                    </button>
                                    <a href="<?= base_url('backend/notifications') ?>" class="btn btn-secondary">
                                        <i class="fa fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<?= $this->section('javascript') ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for target employees dropdown
        $('#notif_target_employees').select2({
            placeholder: 'Select employees (or leave empty for all)',
            allowClear: true,
            width: '100%'
        });

        // Initialize Flatpickr for static date inputs
        $('.notification-datepicker').flatpickr({
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'F j, Y',
            allowInput: true
        });

        // Function to initialize Flatpickr on dynamically added fields
        function initializeFlatpickr(element) {
            $(element).find('.notification-datepicker-repeater').flatpickr({
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'F j, Y',
                allowInput: true
            });
        }

        // Initialize repeater for reminder dates with max limit of 3
        var $reminder_dates = $('#reminder_dates_repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
                // Initialize Flatpickr for the newly added field
                initializeFlatpickr($(this));
                // Check reminder count and toggle add button
                toggleReminderAddButton();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
                // Check reminder count after deletion
                setTimeout(toggleReminderAddButton, 300);
            },
            ready: function() {
                // Initialize Flatpickr for the initial field
                initializeFlatpickr($('#reminder_dates_repeater'));
                toggleReminderAddButton();
            }
        });

        // Function to toggle the "Add Reminder Date" button based on count
        function toggleReminderAddButton() {
            var reminderCount = $('#reminder_dates_repeater [data-repeater-item]').length;
            var addButton = $('#reminder_dates_repeater [data-repeater-create]');

            if (reminderCount >= 3) {
                addButton.hide();
            } else {
                addButton.show();
            }
        }

        // Handle Create Notification Form Submission
        $('#createNotificationForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var saveBtn = $('#saveNotificationBtn');

            saveBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Saving...');

            $.ajax({
                url: '<?= base_url("backend/notifications/store") ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.response_type === 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: response.response_description,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then(function() {
                            window.location.href = '<?= base_url("backend/notifications") ?>';
                        });
                    } else {
                        var errorMsg = response.response_description;
                        if (response.response_data && response.response_data.validation) {
                            errorMsg += '<br><ul style="text-align: left; margin-top: 10px;">';
                            $.each(response.response_data.validation, function(field, message) {
                                errorMsg += '<li>' + message + '</li>';
                            });
                            errorMsg += '</ul>';
                        }
                        Swal.fire({
                            title: 'Error!',
                            html: errorMsg,
                            icon: 'error',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                },
                complete: function() {
                    saveBtn.prop('disabled', false).html('<i class="fa fa-save me-2"></i>Create Notification');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>