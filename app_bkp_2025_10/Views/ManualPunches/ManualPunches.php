<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-12">

        <div class="card shadow-sm mb-5">
            <form id="manual_punch_form" class="card-body" method="post" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <label class="form-label" for="employee_id" class="mb-3">Employee Name</label>
                        <select class="form-select form-select-sm" id="employee_id" name="employee_id" data-control="select2" data-placeholder="Select an Employee">
                            <option></option>
                            <?php
                            foreach ($employees as $employee_row) {
                            ?>
                                <option value="<?php echo $employee_row['id']; ?>" <?php echo ($employee_row['id'] == session()->get('current_user')['employee_id']) ? 'selected' : ''; ?>>
                                    <?php echo $employee_row['employee_name'] . ' [ ' . $employee_row['internal_employee_id'] . ' ] ' . $employee_row['department_name'] . ' - ' . $employee_row['company_short_name'] . ''; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <small class="text-danger error-text" id="employee_id_error"></small>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <label class="form-label">Punch Date </label>
                        <div class="position-relative d-flex align-items-center ">
                            <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            <input type="text" id="punch_date" class="form-control form-control-sm form-control-solid ps-7" name="punch_date" placeholder="Pick a Date" value="<?= set_value('punch_date') ?>">
                        </div>
                        <small class="text-danger error-text" id="punch_date_error"><?= isset($validation) ? display_error($validation, 'punch_date') : '' ?></small>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <label class="form-label">Punch In </label>
                        <input type="text" name="punch_in" id="punch_in" class="form-control form-control-sm form-control-solid" data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="__:__" oninput="$(this).next().html('')" style="width: 65px;" />
                        <span class="text-muted d-block">Example: 10:30</span>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <label class="form-label">Punch Out</label>
                        <input type="text" name="punch_out" id="punch_out" class="form-control form-control-sm form-control-solid" data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="__:__" oninput="$(this).next().html('')" style="width: 65px;" />
                        <span class="text-muted d-block">Example: 18:30</span>
                    </div>

                    <div class="col-lg-9 col-md-8">
                        <label class="form-label">Remarks</label>
                        <div class="input-group">
                            <input type="text" id="remarks" class="form-control form-control-sm" name="remarks" placeholder="Remarks" value="<?= set_value('remarks') ?>">
                        </div>
                        <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <label class="form-label" class="mb-3">&nbsp;</label>
                        <button type="submit" id="submit_update_machine_override" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <table id="existing_manual_punches" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                    <thead class="bg-white">
                        <tr>
                            <th>Employee</th>
                            <th>Punch Date</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Employee</th>
                            <th>Punch Date</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>
<!--end::Col-->


<!--end::Row-->





<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/md/mdtimepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/moment.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('form .timepicker').each(function(index, elem) {
            mdtimepicker(
                '#' + $(this).attr("id"), {
                    clearBtn: true,
                    format: 'h:mm tt',
                    hourPadding: true,
                }
            );
        })


        $(document).on('change', '#employee_id', function(e) {
            $('#punch_date').val('').trigger('change');
            $('#deduction_minutes').val('0').trigger('change');
            $("#deduction_requests_table").DataTable().ajax.reload();
        })

        $('#punch_date').flatpickr({
            dateFormat: 'Y-m-d',
            minDate: "<?php echo first_date_of_last_month(); ?>",
            maxDate: "<?php echo current_date_of_month(); ?>",
            altInput: false,
            static: true,
        })





        $(document).on('change', '#employee_id', function(e) {

            existing_manual_punches.ajax.reload();
        })

        $(document).on('submit', '#manual_punch_form', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = $(this).find('button[type=submit]');
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            $('.error-text').html('');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/backend/hr/create-manual-punch'); ?>",
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
                                existing_manual_punches.ajax.reload();
                                // $("#existing_punches").DataTable().ajax.reload();
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

        var existing_manual_punches = $("#existing_manual_punches").DataTable({
            buttons: [],
            ajax: {
                url: "<?= base_url('/ajax/backend/hr/get-manual-punch') ?>",
                type: "POST",
                data: function(d) {
                    return {
                        employee_id: $('#employee_id').val()
                        // punch_date: $('#punch_date').val()
                    };
                },
                dataSrc: "", // Use [] if your controller returns plain array
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
            },
            deferRender: true,
            processing: true,
            language: {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                searchPlaceholder: "Search"
            },
            columns: [{
                    data: "employee_name"
                },
                {
                    data: "punch_date"
                },
                {
                    data: "punch_in",
                    render: function(data, type, row, meta) {
                        if (!data) {
                            return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small>--:--</small></p>`;
                        }
                        return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small>${data}</small></p>`;
                    }
                },
                {
                    data: "punch_out",
                    render: function(data, type, row, meta) {
                        if (!data) {
                            return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small>--:--</small></p>`;
                        }
                        return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small>${data}</small></p>`;
                    }
                },
                {
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small>${data}</small></p>`;
                    }
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex justify-content-center">
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-manual-punch" data-id="${data}">
                                            <span class="svg-icon svg-icon-3">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </a>
                                    </div>`;
                    }
                },
            ],
            scrollX: true,
            scrollY: '400px',
            scrollCollapse: true,
            paging: false,
            columnDefs: [{
                className: 'text-center',
                targets: '_all'
            }, ],
        });

        //end::Initialize Datatable

    })


    $(document).on('click', '.delete-manual-punch', function(e) {
        e.preventDefault();

        let punch_id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: "btn btn-sm btn-primary",
                cancelButton: "btn btn-sm btn-secondary"
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "POST",
                    url: "<?= base_url('/ajax/backend/hr/delete-manual-punch') ?>",
                    data: {
                        id: punch_id
                    },
                    success: function(response) {
                        if (response.response_type === 'success') {
                            Swal.fire({
                                icon: 'success',
                                text: response.response_description,
                                confirmButtonText: 'OK',
                            }).then(() => {
                                $("#existing_manual_punches").DataTable().ajax.reload();

                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.response_description || 'Something went wrong',
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            text: 'Server error while deleting.',
                        });
                    }
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>