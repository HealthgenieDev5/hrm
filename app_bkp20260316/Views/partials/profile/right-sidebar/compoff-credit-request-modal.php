<button type="button" class="btn btn-sm btn-warning text-dark flex-grow-1 mb-2 mx-1" data-bs-toggle="modal" data-bs-target="#comp_off_credit_request_modal">
    <i class="fa fa-plus text-dark"></i> COMP OFF Credit Request
</button>

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


<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
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
        var comp_off_credit_current_user_machine = '<?php echo $current_user_data['machine'] ?? ''; ?>';
        //begin::Add COMP OFF Credit Request Ajax
        $(document).on('click', '#comp_off_credit_request_submit_button', function(e) {
            e.preventDefault();
            // return false;
            $('.error-text').html('');
            var form = $('#comp_off_credit_request');
            // if (comp_off_credit_current_user_machine === 'hn') {
            var attachmentFiles = $('#compoff_attachment')[0].files;
            if (!attachmentFiles || attachmentFiles.length === 0) {
                $('#compoff_attachment_error').html('Please Upload Screenshot of written approval.');
                return false;
            }
            // }
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
    });
</script>
<?= $this->endSection() ?>