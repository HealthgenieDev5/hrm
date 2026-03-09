<button type="button" class="btn btn-sm btn-primary flex-grow-1 mb-2 mx-1" data-bs-toggle="modal" data-bs-target="#create_leave_request_modal" id="create_leave_request_button_trigger" disabled>
    Please Wait
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

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {

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


        $('#create_leave_request_modal').on('shown.bs.modal', function() {
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

        $(document).on('click', '#create_leave_request_modal .switch-toggle > label:not(.disabled)', function(e) {
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
            }
            $(this).parent().find('a').css({
                'width': w,
                'left': $(this).position().left
            });
        })
    });
</script>
<?= $this->endSection() ?>