<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->

    <div class="col-lg-3 col-md-4">
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <form id="filter_form" class="clearfix">
                    <label class="form-label" for="employee_id" class="mb-3">Employee Name</label>
                    <select class="form-select form-select-sm" id="employee_id" name="employee_id" data-control="select2" data-placeholder="Select an Employee">
                        <option></option>
                        <?php
                        foreach ($employees as $employee_row) {
                        ?>
                            <option
                                value="<?php echo $employee_row['id']; ?>"
                                <?php echo $employee_row['id'] == ($_GET['employee_id'] ?? 0) ? "selected" : ""; ?>>
                                <?php echo $employee_row['employee_name'] . ' [ ' . $employee_row['internal_employee_id'] . ' ] ' . $employee_row['department_name'] . ' - ' . $employee_row['company_short_name'] . ''; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </form>

                <table id="leave_balance_current_month" class="table table-sm table-row-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: left"><strong>Type</strong></th>
                            <th style="text-align: right"><strong>Balance</strong></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8">

        <form id="create_leave_request" class="card" method="post">
            <div class="card-header">
                <h5 class="card-title">Request New Leave</h5>
                <input
                    type="hidden"
                    name="current_employee_id"
                    id="current_employee_id"
                    value="<?php
                            if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
                                echo $_GET['employee_id'];
                            }
                            ?>
                            " />
                <small class="text-danger error-text" id="current_employee_id_error"><?= isset($validation) ? display_error($validation, 'current_employee_id') : '' ?></small>
            </div>

            <div class="card-body">
                <div class="row">

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
                        <input type="text" class="form-control form-control-sm" id="number_of_days" name="number_of_days" placeholder="--" readonly />
                        <small class="text-danger error-text" id="number_of_days_error"><?= isset($validation) ? display_error($validation, 'number_of_days') : '' ?></small>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="required form-label">Type of leave</label>
                        <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                            <label for="type_of_leave_ul" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                UL <input type="radio" name="type_of_leave" class="opacity-0 position-absolute type_of_leave" id="type_of_leave_ul" value="UL" checked />
                            </label>
                            <label for="type_of_leave_el" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                EL <input type="radio" name="type_of_leave" class="opacity-0 position-absolute type_of_leave" id="type_of_leave_el" value="EL" />
                            </label>
                            <label for="type_of_leave_cl" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                CL <input type="radio" name="type_of_leave" class="opacity-0 position-absolute type_of_leave" id="type_of_leave_cl" value="CL" />
                            </label>
                            <label for="type_of_leave_comp_off" class="text-center form-control form-control-sm bg-transparent border-0 position-relative">
                                COMP OFF <input type="radio" name="type_of_leave" class="opacity-0 position-absolute type_of_leave" id="type_of_leave_comp_off" value="COMP OFF" />
                            </label>
                            <a class="bg-danger form-control form-control-sm p-0 position-absolute"></a>
                        </div>
                        <span class="text-danger error-text" id="type_of_leave_error"><?= isset($validation) ? display_error($validation, 'type_of_leave') : '' ?></span>
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
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Attachment</label><br>
                        <div id="attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                            <div class="image-input-wrapper w-125px h-125px" style="">
                                <a class="d-none w-100 h-100 overlay preview-button" data-bs-target="#previewLightbox" data-bs-toggle="modal">
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


                </div>
            </div>

            <div class="card-footer">
                <button id="create_leave_request_submit_button" class="btn btn-sm btn-primary">Request Now</button>
            </div>
        </form>
    </div>

</div>
<!--end::Row-->

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        $(document).on('change', '#employee_id', function(e) {
            $("#leave_balance_current_month").DataTable().ajax.reload();
            $("#current_employee_id").val($(this).val());
        })

        $('#from_date').flatpickr({
            dateFormat: 'Y-m-d',
            minDate: "<?php echo ($_GET['employee_id'] ?? 0) == 7 ? date('Y-m-01', strtotime('first day of last month')) : date('Y-m-01'); ?>",
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
                $('#number_of_days').val(number_of_days);

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
            minDate: "<?php echo ($_GET['employee_id'] ?? 0) == 7 ? date('Y-m-01', strtotime('first day of last month')) : date('Y-m-01'); ?>",
            maxDate: "<?php echo date('Y-m-t', strtotime('+1 months')); ?>",
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
                $('#number_of_days').val(number_of_days);
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

        /*$(document).on('change', '.type_of_leave', function(e){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#number_of_days').val('');
            if( $('.type_of_leave#type_of_leave_el').is(':checked') == true ){
                $('#day_type_half_day').parent().hide();
                $('#day_type_full_day').parent().trigger('click');
                $('#from_date').flatpickr({
                    dateFormat: 'Y-m-d',
                    minDate: "<?php echo date('Y-m-d', strtotime('+4 days')); ?>",
                    maxDate: "<?php echo date('Y-m-t', strtotime(date('Y-m-t') . ' +1 days')); ?>",
                    altInput: false,
                    static: true,
                    onClose: function(selectedDates, dateStr, instance) {
                        if( $('#day_type_half_day').is(':checked') ) {
                            var day_type = 0.5;
                        }else{
                            var day_type = 1;
                        }
                        var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                        number_of_days = number_of_days*day_type;
                        $('#number_of_days').val(number_of_days);

                        if( day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5 ){
                            Swal.fire({
                                html: 'Select same date in from date and to date',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function( index, elem ){
                                        var btnClass= $(this).attr("class");
                                        var btnStyle= $(this).attr("style");
                                        var btnHtml= $(this).html();
                                        var newButton = '<a class="'+btnClass+'" style="'+btnStyle+'">'+btnHtml+'</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#from_date').val('').focus();
                                },
                            });
                        } else if( $('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0 ){
                            Swal.fire({
                                html: 'Number of days can not be negative or 0',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function( index, elem ){
                                        var btnClass= $(this).attr("class");
                                        var btnStyle= $(this).attr("style");
                                        var btnHtml= $(this).html();
                                        var newButton = '<a class="'+btnClass+'" style="'+btnStyle+'">'+btnHtml+'</a>';
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
                    minDate: "<?php echo date('Y-m-d', strtotime('+4 days')); ?>",
                    maxDate: "<?php echo date('Y-m-t', strtotime(date('Y-m-t') . ' +1 days')); ?>",
                    altInput: false,
                    static: true,
                    onClose: function(selectedDates, dateStr, instance) {
                        if( $('#day_type_half_day').is(':checked') ) {
                            var day_type = 0.5;
                        }else{
                            var day_type = 1;
                        }
                        var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                        number_of_days = number_of_days*day_type;
                        $('#number_of_days').val(number_of_days);
                        if( day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5 ){
                            Swal.fire({
                                html: 'select same date in from date and to date',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function( index, elem ){
                                        var btnClass= $(this).attr("class");
                                        var btnStyle= $(this).attr("style");
                                        var btnHtml= $(this).html();
                                        var newButton = '<a class="'+btnClass+'" style="'+btnStyle+'">'+btnHtml+'</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#to_date').val('').focus();
                                },
                            })
                        }else if( $('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0 ){
                            Swal.fire({
                                html: 'Number of days can not be negative or 0',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function( index, elem ){
                                        var btnClass= $(this).attr("class");
                                        var btnStyle= $(this).attr("style");
                                        var btnHtml= $(this).html();
                                        var newButton = '<a class="'+btnClass+'" style="'+btnStyle+'">'+btnHtml+'</a>';
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
            else{
                $('#day_type_half_day').parent().show();
                $('#day_type_full_day').parent().trigger('click');

                $('#from_date').flatpickr({
                    dateFormat: 'Y-m-d',
                    minDate: "<?php echo date('Y-m-01'); ?>",
                    maxDate: "<?php echo date('Y-m-t'); ?>",
                    altInput: false,
                    static: true,
                    onClose: function(selectedDates, dateStr, instance) {
                        if( $('#day_type_half_day').is(':checked') ) {
                            var day_type = 0.5;
                        }else{
                            var day_type = 1;
                        }
                        var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                        number_of_days = number_of_days*day_type;
                        $('#number_of_days').val(number_of_days);

                        if( day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5 ){
                            Swal.fire({
                                html: 'Select same date in from date and to date',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function( index, elem ){
                                        var btnClass= $(this).attr("class");
                                        var btnStyle= $(this).attr("style");
                                        var btnHtml= $(this).html();
                                        var newButton = '<a class="'+btnClass+'" style="'+btnStyle+'">'+btnHtml+'</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#from_date').val('').focus();
                                },
                            })
                        }else if( $('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0 ){
                            Swal.fire({
                                html: 'Number of days can not be negative or 0',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function( index, elem ){
                                        var btnClass= $(this).attr("class");
                                        var btnStyle= $(this).attr("style");
                                        var btnHtml= $(this).html();
                                        var newButton = '<a class="'+btnClass+'" style="'+btnStyle+'">'+btnHtml+'</a>';
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
                        if( $('#day_type_half_day').is(':checked') ) {
                            var day_type = 0.5;
                        }else{
                            var day_type = 1;
                        }
                        var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                        number_of_days = number_of_days*day_type;
                        $('#number_of_days').val(number_of_days);
                        if( day_type == 0.5 && $('#from_date').val().length && $('#to_date').val().length && number_of_days !== 0.5 ){
                            Swal.fire({
                                html: 'select same date in from date and to date',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function( index, elem ){
                                        var btnClass= $(this).attr("class");
                                        var btnStyle= $(this).attr("style");
                                        var btnHtml= $(this).html();
                                        var newButton = '<a class="'+btnClass+'" style="'+btnStyle+'">'+btnHtml+'</a>';
                                        $(this).replaceWith(newButton);
                                    })
                                },
                                didClose: function(x) {
                                    $('#to_date').val('').focus();
                                },
                            })
                        }else if( $('#from_date').val().length && $('#to_date').val().length && number_of_days <= 0 ){
                            Swal.fire({
                                html: 'Number of days can not be negative or 0',
                                icon: "error",
                                confirmButtonText: "Ok, got it!",
                                customClass: { confirmButton: "btn btn-primary" },
                                didRender: function(x) {
                                    $(".swal2-popup.swal2-modal").removeAttr("tabindex");
                                    var buttons = $(".swal2-popup > .swal2-actions > button");
                                    buttons.each(function( index, elem ){
                                        var btnClass= $(this).attr("class");
                                        var btnStyle= $(this).attr("style");
                                        var btnHtml= $(this).html();
                                        var newButton = '<a class="'+btnClass+'" style="'+btnStyle+'">'+btnHtml+'</a>';
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
        })*/

        $(document).on('change', '.day_type', function(e) {
            if ($(this).is(':checked')) {
                var day_type = $(this).val();
                var number_of_days = get_interval_considering_half_day('from_date', 'to_date');
                number_of_days = number_of_days * day_type;
                $('#number_of_days').val(number_of_days);
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

        var imageInputElement = document.querySelector("#attachment_select");
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

        $('#type_of_leave_ul').parent().trigger('click');
        var toggleSwitch = $('.switch-toggle');
        toggleSwitch.each(function(index, thisSwitch) {
            var checked_input = $(thisSwitch).find('label > input:checked').parent();
            var w = checked_input.outerWidth();
            var indexoflabel = checked_input.index();
            $(thisSwitch).find('a').css({
                'width': w,
                'left': indexoflabel * w
            });
        })

        $(document).on('click', '.switch-toggle > label:not(.disabled)', function(e) {
            var w = $(this).outerWidth();
            $(this).find('input').prop('checked', true).trigger('change');
            if ($(this).find('input').val() == 'CL') {
                $(this).parent().find('a').removeClass('bg-danger').removeClass('bg-warning').addClass('bg-success');
            } else if ($(this).find('input').val() == 'EL') {
                $(this).parent().find('a').removeClass('bg-danger').removeClass('bg-success').addClass('bg-warning');
            } else if ($(this).find('input').val() == 'UL') {
                $(this).parent().find('a').removeClass('bg-warning').removeClass('bg-success').addClass('bg-danger');
            } else if ($(this).find('input').val() == 'COMP OFF') {
                $(this).parent().find('a').removeClass('bg-warning').removeClass('bg-success').addClass('bg-danger');
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

        /*begin::leave_balance_current_month*/
        var leave_balance_current_month = $("#leave_balance_current_month").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                /*url: "<?= base_url('ajax/profile/get_leave_balance_on_profile_page') ?>",*/
                url: "<?= base_url('/ajax/developer-access/get-leave-balance-current-month') ?>",
                type: "POST",
                data: {
                    filter: function() {
                        return $('#filter_form').serialize();
                    }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
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
            ],
            "scrollX": true,
            "paging": false,
            "ordering": false,
            "columnDefs": [{
                "className": 'text-end',
                "targets": [1]
            }, ],
        });
        $('#leave_balance_current_month_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Leave Balance</h3>');
        $('#leave_balance_current_month_wrapper > .card > .card-footer').html('<small>If leave balance is incorrect Please contact Developer on ext 400</small>');
        /*end::leave_balance_current_month*/

        //begin::Add Leave Ajax
        $(document).on('click', '#create_leave_request_submit_button', function(e) {
            e.preventDefault();
            // return false;
            var form = $('#create_leave_request');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/developer-access/create-leave-request'); ?>",
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
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>