<button type="button" class="btn btn-sm btn-info flex-grow-1 mb-2 mx-1" data-bs-toggle="modal" data-bs-target="#create_od_request_modal">
    <i class="fa fa-plus"></i> Request OD
</button>

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


<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
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
    });
</script>
<?= $this->endSection() ?>