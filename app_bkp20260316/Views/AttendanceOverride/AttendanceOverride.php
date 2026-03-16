<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-12">

        <div class="card shadow-sm mb-5">
            <form id="attendance_override_form" class="card-body" method="post" enctype="multipart/form-data">
                <!-- <div class="card-body"> -->
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
                        <label class="form-label">Attendance Date</label>

                        <div class="position-relative d-flex align-items-center ">
                            <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            <input type="text" id="attendance_date" name="attendance_date" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select date" value="<?= set_value('attendance_date') ?>" />
                        </div>
                        <small class="text-danger error-text" id="attendance_date_error"><?= isset($validation) ? display_error($validation, 'attendance_date') : '' ?></small>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <label class="form-label" for="attendance">Attendance</label>
                        <select class="form-control form-control-sm" id="attendance" name="attendance" data-control="select2" data-placeholder="Select a Attendance" data-allow-clear="true">
                            <option></option>
                            <option value="A" <?= @edit_set_select('attendance', 'A', $attendance) ?>>A</option>
                            <option value="P" <?= @edit_set_select('attendance', 'P', $attendance) ?>>P</option>
                            <option value="H/D" <?= @edit_set_select('attendance', 'H/D', $attendance) ?>>H/D</option>
                            <option value="CL" <?= @edit_set_select('attendance', 'CL', $attendance) ?>>CL</option>
                            <option value="CL/2" <?= @edit_set_select('attendance', 'CL/2', $attendance) ?>>CL/2</option>
                            <option value="H/D+CL/2" <?= @edit_set_select('attendance', 'H/D+CL/2', $attendance) ?>>H/D+CL/2</option>


                            <option value="COMP OFF" <?= @edit_set_select('attendance', 'COMP OFF', $attendance) ?>>COMP OFF</option>
                            <option value="COMP OFF/2" <?= @edit_set_select('attendance', 'COMP OFF/2', $attendance) ?>>COMP OFF/2</option>
                            <option value="H/D+COMP OFF/2" <?= @edit_set_select('attendance', 'H/D+COMP OFF/2', $attendance) ?>>H/D+COMP OFF/2</option>

                            <option value="ML" <?= @edit_set_select('attendance', 'ML', $attendance) ?>>ML</option>
                            <option value="INC" <?= @edit_set_select('attendance', 'INC', $attendance) ?>>INC</option>

                        </select>
                        <small class="text-danger error-text" id="attendance_error"><?= isset($validation) ? display_error($validation, 'attendance') : '' ?></small>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <label class="form-label">Remarks</label>
                        <div class="input-group">
                            <input type="text" id="remarks" class="form-control form-control-sm" name="remarks" placeholder="Remarks" value="<?= set_value('remarks') ?>">
                        </div>
                        <small class="text-danger error-text" id="remarks_error"><?= isset($validation) ? display_error($validation, 'remarks') : '' ?></small>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <label class="form-label" class="mb-3">&nbsp;</label>
                        <button type="submit" id="submit_update_attendance_override" class="form-control form-control-sm btn btn-sm btn-primary d-inline">
                            <span class="indicator-label">Update</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <!-- </div> -->
            </form>
        </div>

        <!--  -->


    </div>
    <!--end::Col-->

    <div class="col-12">

        <div class="card shadow-sm mb-5">

            <div class="card-body">

                <table id="existing_overrides" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                    <thead class="bg-white">
                        <tr>
                            <th class="text-center bg-white"><strong>Employee Name</strong></th>
                            <th class="text-center"><strong>Date</strong></th>
                            <th class="text-center"><strong>Attendance</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Action</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center bg-white"><strong>Employee Name</strong></th>
                            <th class="text-center"><strong>Date</strong></th>
                            <th class="text-center"><strong>Attendance</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Action</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!--end::Row-->





<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {


        var $date_range_pickr = $("#attendance_date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(document).on('change', '#employee_id', function(e) {
            //$date_range_pickr.clear();
            /// $('#attendance_date').val('').trigger('change');
            // $('#attendance').val('').trigger('change');
            //  $('#remarks').val('').trigger('change');
            $("#existing_overrides").DataTable().ajax.reload();
        })

        $(document).on('submit', '#attendance_override_form', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = $(this).find('button[type=submit]');
            submitButton.attr("data-kt-indicator", "on");
            submitButton.attr("disabled", "true");
            $('.error-text').html('');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/backend/hr/override-attendance'); ?>",
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
                                /*location.reload();*/
                                $("#existing_overrides").DataTable().ajax.reload();
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

        const fetchOverrides = (employee_id) => {
            var data = {
                'employee_id': employee_id,
            };
            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/backend/hr/existing-attendance-overrides'); ?>",
                data: data,
                success: function(response) {
                    if (response.response_type == 'error') {
                        if (response.response_description.length) {
                            console.log(response.response_data);
                        }
                    }

                    if (response.response_type == 'success') {
                        if (typeof response.response_data != 'undefined') {
                            var overrides = response.response_data;
                        }
                    }
                },
                failed: function() {
                    console.log("Ajax Failed, Please contact administrator");
                }
            })
        }

        var existing_overrides = $("#existing_overrides").DataTable({
            "buttons": [],
            "ajax": {
                url: "<?= base_url('/ajax/backend/hr/existing-attendance-overrides') ?>",
                type: "POST",
                data: {
                    filter: function() {
                        return $('#attendance_override_form').serialize();
                    }
                },
                dataSrc: "",
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
                    data: "employee_name",
                    render: function(data, type, row, meta) {
                        return row.employee_name + " - " + row.department_name + " - " + row.company_short_name;
                    }
                },
                {
                    data: "attendance_date"
                },
                {
                    data: "attendance"
                },
                {
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return `<p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small>${row.remarks}</small></p>`
                    }
                },
                {
                    data: "remarks",
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex justify-content-center">
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-attendance-override" data-id="${row.id}">
                                            <span class="svg-icon svg-icon-3">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </a>
                                    </div>`;
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "paging": false,
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ],
        });
        //end::Initialize Datatable

        //begin::Delete Company Ajax
        $(document).on('click', '.delete-attendance-override', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: "btn btn-sm btn-primary",
                    cancelButton: "btn btn-sm btn-secondary"
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var override_id = $(this).data('id');
                    var data = {
                        'override_id': override_id,
                    };
                    $.ajax({
                        method: "post",
                        url: "<?php echo base_url('/ajax/backend/hr/delete-attendance-override'); ?>",
                        data: data,
                        success: function(response) {
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
                                    })
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
                                    }).then(function() {
                                        $("#existing_overrides").DataTable().ajax.reload();
                                    })
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
                        }
                    })
                }
            })
        })
        //end::Delete Company Ajax
    })
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>