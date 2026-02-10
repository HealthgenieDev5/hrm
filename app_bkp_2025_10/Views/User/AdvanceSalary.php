<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<!--begin::Row-->
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Advance Salary Requests</h3>
                <div class="card-toolbar d-flex flex-column align-items-end">
                    <!-- <a class="btn btn-sm btn-light-primary" data-bs-toggle="modal" href="#create_advance_salary_request_modal" >
                            <i class="fa fa-plus" ></i> Create New Request
                        </a> -->
                    <div class="modal fade" tabindex="-1" id="create_advance_salary_request_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="create_advance_salary_request" method="post" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Request New Leave</h5>
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                        <!--end::Close-->
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Amount</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" min="0" step="0.01" id="amount" class="form-control" name="amount" placeholder="Amount" value="<?= set_value('amount') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                    <span class="input-group-text input-group-solid">
                                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                                    </span>
                                                </div>
                                                <small class="text-danger error-text" id="amount_error"><?= isset($validation) ? display_error($validation, 'amount') : '' ?></small>
                                            </div>
                                            <!-- <div class="col-lg-6 mb-3">
                                                    <label class="form-label">EMI Tenure</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" min="0" step="1" id="emi_tenure" class="form-control" name="emi_tenure" placeholder="EMI Tenure" value="<?= set_value('emi_tenure') ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" >
                                                        <span class="input-group-text input-group-solid">
                                                            Month
                                                        </span>
                                                    </div>
                                                    <small class="text-danger error-text" id="emi_tenure_error"><?= isset($validation) ? display_error($validation, 'emi_tenure') : '' ?></small>
                                                </div> -->
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Reason</label>
                                                <select class="form-select form-select-sm" id="reason" name="reason" data-control="select2" data-placeholder="Select Reason">
                                                    <option></option>
                                                    <option value="personal">Personal</option>
                                                    <option value="medical">Medical</option>
                                                    <option value="wedding">Wedding</option>
                                                </select>
                                                <small class="text-danger error-text" id="reason_error"><?= isset($validation) ? display_error($validation, 'reason') : '' ?></small>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Note</label>
                                                <textarea class="form-control form-control-sm" id="note" name="note" placeholder="Detailed reason for this loan"></textarea>
                                                <small class="text-danger error-text" id="note_error"><?= isset($validation) ? display_error($validation, 'note') : '' ?></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <button id="create_advance_salary_request_submit_button" class="btn btn-sm btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="advance_salary_requests_table" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Amount</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed Date</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Disbursed</strong></th>
                            <th class="text-center"><strong>Disbursed Date</strong></th>
                            <th class="text-center"><strong>Date Time</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Amount</strong></th>
                            <th class="text-center"><strong>Status</strong></th>
                            <th class="text-center"><strong>Reviewed Date</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Disbursed</strong></th>
                            <th class="text-center"><strong>Disbursed Date</strong></th>
                            <th class="text-center"><strong>Date Time</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<?= $this->section('javascript') ?>

<!-- <script src="<?php echo base_url(); ?>assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script> -->
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('#from_date').flatpickr({
            dateFormat: 'Y-m-d',
            altInput: false,
            static: true,
            onClose: function(selectedDates, dateStr, instance) {
                check_days_interval('from_date', 'to_date', 'from_date', 'number_of_days');
            }
        })

        $('#to_date').flatpickr({
            dateFormat: 'Y-m-d',
            altInput: false,
            static: true,
            onClose: function(selectedDates, dateStr, instance) {
                check_days_interval('from_date', 'to_date', 'to_date', 'number_of_days');
            }
        })

        $(document).on('input', '.form-control', function() {
            $(this).parent().find('.error-text').html('');
        })
        $(document).on('change', '.flatpickr-input', function() {
            $(this).parent().parent().parent().find('.error-text').html('');
        })

        $(document).on('click', '.parent-picker', function() {
            $(this).parent().find('.flatpickr-input').focus();
        })

        //begin::Initialize Datatable
        var table = $("#advance_salary_requests_table").DataTable({
            "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
            "buttons": [],
            "ajax": {
                url: "<?= base_url('/ajax/get-all-advance-salary-requests') ?>",
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
            },
            "columns": [{
                    data: "amount"
                },
                {
                    data: "review_status",
                    render: function(data, type, row, meta) {
                        var badge_class = "bg-secondary text-dark";
                        if (data == 'rejected') {
                            badge_class = "bg-danger text-danger bg-opacity-15";
                        } else if (data == 'approved') {
                            badge_class = "bg-success text-success bg-opacity-15";
                        } else if (data == 'disbursed') {
                            badge_class = "bg-info text-info bg-opacity-15";
                        }
                        return '<span class="badge text-capitalize rounded-pill ' + badge_class + '">' + data + '</span>';
                    }
                },
                {
                    data: "reviewed_date"
                },
                {
                    data: "remarks"
                },
                {
                    data: "disbursed"
                },
                {
                    data: "disbursed_date"
                },
                {
                    data: "date_time"
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": 'auto',
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }, ],
        });
        //end::Initialize Datatable

        //begin::Create loan request Ajax
        $(document).on('click', '#create_advance_salary_request_submit_button', function(e) {
            e.preventDefault();
            var form = $('#create_advance_salary_request');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/create-advance-salary-request'); ?>",
                data: data,
                processData: false,
                contentType: false,
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
                                form[0].reset();
                                form.closest('.modal').modal('hide');
                                $("#advance_salary_requests_table").DataTable().ajax.reload();
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
                }
            })
        })
        //end::Create loan request Ajax



    })
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>