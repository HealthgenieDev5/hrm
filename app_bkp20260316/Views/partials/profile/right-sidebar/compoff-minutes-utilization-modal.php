<button type="button" class="btn btn-sm btn-danger flex-grow-1 mb-2 mx-1" data-bs-toggle="modal" data-bs-target="#comp_off_minutes_utilization_request_modal" style="max-width: max-content;">
    <i class="fa fa-plus"></i> Use Comp Off Minutes
</button>

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


<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
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
    });
</script>
<?= $this->endSection() ?>