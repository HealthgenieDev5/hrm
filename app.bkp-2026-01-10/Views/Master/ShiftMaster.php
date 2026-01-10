<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<div class="row gy-5 g-xl-8">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Shifts</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_shift_modal">
                        <i class="fa fa-plus"></i> Add New
                    </button>
                    <div class="modal fade" tabindex="-1" id="add_shift_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="add_shift" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Shift</h5>
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-4 mb-3">
                                                <label class="required form-label">Shift Code</label>
                                                <input type="text" name="shift_code" id="shift_code" class="form-control form-control-solid" placeholder="Shift Code" value="" oninput="$(this).next().html('')" />
                                                <span class="text-danger error-text d-block" id="shift_code_error"></span>
                                            </div>
                                            <div class="col-lg-8 mb-3">
                                                <label class="required form-label">Shift Name</label>
                                                <input type="text" name="shift_name" id="shift_name" class="form-control form-control-solid" placeholder="Shift Name" value="" oninput="$(this).next().html('')" />
                                                <span class="text-danger error-text d-block" id="shift_name_error"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 mb-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="required form-label me-3">Shift Timings</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="position-relative d-flex align-items-center ">
                                                            <span class="svg-icon svg-icon-2 position-absolute ms-2" style="z-index:1">
                                                                <i class="fas fa-clock" style="font-size: 0.85rem !important;"></i>
                                                            </span>
                                                            <input type="text" id="shift_start" class="form-control form-control-sm form-control-solid ps-6 timepicker text-center datetimepicker-input" placeholder="Shift Start" value="" onchange="$(this).parent().parent().find('input[type=hidden]').val($(this).val()); $(this).closest('small.error-text').html('');" style="font-size: 10px; min-width: 77px;" />
                                                        </div>
                                                        <span class="text-danger error-text d-block" id="shift_start_error"></span>
                                                        <input type="hidden" name="shift_start_monday" id="shift_start_monday" />
                                                        <input type="hidden" name="shift_start_tuesday" id="shift_start_tuesday" />
                                                        <input type="hidden" name="shift_start_wednesday" id="shift_start_wednesday" />
                                                        <input type="hidden" name="shift_start_thursday" id="shift_start_thursday" />
                                                        <input type="hidden" name="shift_start_friday" id="shift_start_friday" />
                                                        <input type="hidden" name="shift_start_saturday" id="shift_start_saturday" />
                                                        <input type="hidden" name="shift_start_sunday" id="shift_start_sunday" />
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="position-relative d-flex align-items-center ">
                                                            <span class="svg-icon svg-icon-2 position-absolute ms-2" style="z-index:1">
                                                                <i class="fas fa-clock" style="font-size: 0.85rem !important;"></i>
                                                            </span>
                                                            <input type="text" id="shift_end" class="form-control form-control-sm form-control-solid ps-6 timepicker text-center datetimepicker-input" placeholder="Shift End" value="" onchange="$(this).parent().parent().find('input[type=hidden]').val($(this).val()); $(this).closest('small.error-text').html('');" style="font-size: 10px; min-width: 77px;" />
                                                        </div>
                                                        <span class="text-danger error-text d-block" id="shift_end_error"></span>
                                                        <input type="hidden" name="shift_end_monday" id="shift_end_monday" />
                                                        <input type="hidden" name="shift_end_tuesday" id="shift_end_tuesday" />
                                                        <input type="hidden" name="shift_end_wednesday" id="shift_end_wednesday" />
                                                        <input type="hidden" name="shift_end_thursday" id="shift_end_thursday" />
                                                        <input type="hidden" name="shift_end_friday" id="shift_end_friday" />
                                                        <input type="hidden" name="shift_end_saturday" id="shift_end_saturday" />
                                                        <input type="hidden" name="shift_end_sunday" id="shift_end_sunday" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 mb-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="required form-label me-3">Week off</label>
                                                    </div>
                                                    <div class="col-12 d-flex align-items-center justify-content-start flex-wrap" style="gap:5px">
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="monday" id="weekoff_monday" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_monday">Monday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="tuesday" id="weekoff_tuesday" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_tuesday">Tuesday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="wednesday" id="weekoff_wednesday" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_wednesday">Wednesday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="thursday" id="weekoff_thursday" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_thursday">Thursday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="friday" id="weekoff_friday" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_friday">Friday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="saturday" id="weekoff_saturday" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_saturday">Saturday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="sunday" id="weekoff_sunday" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_sunday">Sunday</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-danger error-text d-block" id="weekoff_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Daily grace minutes:</label>
                                                <input class="late_coming_rule_hours form-control form-control-sm form-control-solid" name="late_coming_rule[0][hours]" data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="__:__" style="width: 65px;" />
                                                <input class="late_coming_rule_name" type="hidden" name="late_coming_rule[0][name]" value="Daily Grace Minutes">
                                                <input class="late_coming_rule_applicable" type="hidden" name="late_coming_rule[0][applicable]" value="Daily">
                                                <input class="late_coming_rule_count" type="hidden" name="late_coming_rule[0][count]" value="Half Day Present">
                                                <span class="text-muted d-block">Example: 00:20</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="required form-label">Absent if work hours < </label>
                                                        <input type="text" name="attendance_rule[absent_for_work_hours]" id="absent_for_work_hours" class="form-control form-control-sm form-control-solid" data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="__:__" oninput="$(this).next().html('')" style="width: 65px;" />
                                                        <span class="text-muted d-block">Example: 03:30</span>
                                                        <span class="text-danger error-text d-block" id="absent_for_work_hours_error"></span>
                                            </div>

                                            <div class="col-lg-4">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="form-check form-check-custom form-check-success form-check-solid">
                                                    <input class="form-check-input" type="checkbox" value="reduce" id="shift_type" name="shift_type" />
                                                    <label class="form-check-label" for="shift_type">For workers</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="add_shift_close_button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <input type="hidden" id="add_shift_submit_field" name="add_shift_submit_field" value="Add" />
                                        <button type="submit" id="add_shift_submit_button" class="btn btn-sm btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" tabindex="-1" id="update_shift_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Shift</h5>
                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-2x"></span>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <form id="update_shift" method="post">
                                        <div class="row">
                                            <div class="col-lg-6 mb-3">
                                                <label class="required form-label">Shift Code</label>
                                                <input type="text" name="shift_code" id="shift_code" class="form-control form-control-solid" placeholder="Shift Code" value="" oninput="$(this).next().html('')" />
                                                <input type="hidden" name="shift_id" id="shift_id" value="" />
                                                <span class="text-danger error-text d-block" id="shift_id_error"></span>
                                                <span class="text-danger error-text d-block" id="shift_code_error"></span>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="required form-label">Shift Name</label>
                                                <input type="text" name="shift_name" id="shift_name" class="form-control form-control-solid" placeholder="Shift Name" value="" oninput="$(this).next().html('')" />
                                                <span class="text-danger error-text d-block" id="shift_name_error"></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 mb-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="required form-label me-3">Shift Timings</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="position-relative d-flex align-items-center ">
                                                            <span class="svg-icon svg-icon-2 position-absolute ms-2" style="z-index:1">
                                                                <i class="fas fa-clock" style="font-size: 0.85rem !important;"></i>
                                                            </span>
                                                            <input type="text" id="shift_start_edit" class="form-control form-control-sm form-control-solid ps-6 timepicker text-center datetimepicker-input" placeholder="Shift Start" value="" onchange="$(this).parent().parent().find('input[type=hidden]').val($(this).val()); $(this).closest('small.error-text').html('');" style="font-size: 10px; min-width: 77px;" />
                                                        </div>
                                                        <span class="text-danger error-text d-block" id="shift_start_edit_error"></span>
                                                        <input type="hidden" name="shift_start_monday_edit" id="shift_start_monday_edit" />
                                                        <input type="hidden" name="shift_start_tuesday_edit" id="shift_start_tuesday_edit" />
                                                        <input type="hidden" name="shift_start_wednesday_edit" id="shift_start_wednesday_edit" />
                                                        <input type="hidden" name="shift_start_thursday_edit" id="shift_start_thursday_edit" />
                                                        <input type="hidden" name="shift_start_friday_edit" id="shift_start_friday_edit" />
                                                        <input type="hidden" name="shift_start_saturday_edit" id="shift_start_saturday_edit" />
                                                        <input type="hidden" name="shift_start_sunday_edit" id="shift_start_sunday_edit" />
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="position-relative d-flex align-items-center ">
                                                            <span class="svg-icon svg-icon-2 position-absolute ms-2" style="z-index:1">
                                                                <i class="fas fa-clock" style="font-size: 0.85rem !important;"></i>
                                                            </span>
                                                            <input type="text" id="shift_end_edit" class="form-control form-control-sm form-control-solid ps-6 timepicker text-center datetimepicker-input" placeholder="Shift End" value="" onchange="$(this).parent().parent().find('input[type=hidden]').val($(this).val()); $(this).closest('small.error-text').html('');" style="font-size: 10px; min-width: 77px;" />
                                                        </div>
                                                        <span class="text-danger error-text d-block" id="shift_end_edit_error"></span>
                                                        <input type="hidden" name="shift_end_monday_edit" id="shift_end_monday_edit" />
                                                        <input type="hidden" name="shift_end_tuesday_edit" id="shift_end_tuesday_edit" />
                                                        <input type="hidden" name="shift_end_wednesday_edit" id="shift_end_wednesday_edit" />
                                                        <input type="hidden" name="shift_end_thursday_edit" id="shift_end_thursday_edit" />
                                                        <input type="hidden" name="shift_end_friday_edit" id="shift_end_friday_edit" />
                                                        <input type="hidden" name="shift_end_saturday_edit" id="shift_end_saturday_edit" />
                                                        <input type="hidden" name="shift_end_sunday_edit" id="shift_end_sunday_edit" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 mb-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="required form-label me-3">Week off</label>
                                                    </div>
                                                    <div class="col-12 d-flex align-items-center justify-content-start flex-wrap" style="gap:5px">
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="monday" id="weekoff_monday_edit" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_monday_edit">Monday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="tuesday" id="weekoff_tuesday_edit" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_tuesday_edit">Tuesday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="wednesday" id="weekoff_wednesday_edit" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_wednesday_edit">Wednesday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="thursday" id="weekoff_thursday_edit" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_thursday_edit">Thursday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="friday" id="weekoff_friday_edit" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_friday_edit">Friday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="saturday" id="weekoff_saturday_edit" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_saturday_edit">Saturday</label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="sunday" id="weekoff_sunday_edit" name="weekoff[]" />
                                                            <label class="form-check-label" for="weekoff_sunday_edit">Sunday</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-danger error-text d-block" id="weekoff_edit_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Daily grace minutes:</label>
                                                <input class="late_coming_rule_hours form-control form-control-sm form-control-solid" name="late_coming_rule[0][hours]" data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="__:__" style="width: 65px;" />
                                                <input class="late_coming_rule_name" type="hidden" name="late_coming_rule[0][name]" value="Daily Grace Minutes">
                                                <input class="late_coming_rule_applicable" type="hidden" name="late_coming_rule[0][applicable]" value="Daily">
                                                <input class="late_coming_rule_count" type="hidden" name="late_coming_rule[0][count]" value="Half Day Present">
                                                <span class="text-muted d-block">Example: 00:20</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="required form-label">Absent if work hours < </label>
                                                        <input type="text" name="attendance_rule[absent_for_work_hours]" id="absent_for_work_hours" class="form-control form-control-sm form-control-solid" data-inputmask-regex="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="__:__" oninput="$(this).next().html('')" style="width: 65px;" />
                                                        <span class="text-muted d-block">Example: 03:30</span>
                                                        <span class="text-danger error-text d-block" id="absent_for_work_hours_error"></span>
                                            </div>


                                            <div class="col-lg-4">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="form-check form-check-custom form-check-success form-check-solid">
                                                    <input class="form-check-input" type="checkbox" value="reduce" id="shift_type" name="shift_type" />
                                                    <label class="form-check-label" for="shift_type">For workers</label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" id="update_shift_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="shifts_table" class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th class="text-center bg-white"><strong>Shift ID</strong></th>
                            <th class="text-center bg-white"><strong>Shift Name</strong></th>
                            <th class="text-center bg-white"><strong>Shift Type</strong></th>
                            <th class="text-center"><strong>Shift Timings</strong></th>
                            <th class="text-center"><strong>Emp#</strong></th>
                            <th class="text-center bg-white"><strong>Action</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center bg-white"></th>
                            <th class="text-center bg-white"><strong>Shift Name</strong></th>
                            <th class="text-center bg-white"><strong>Shift Type</strong></th>
                            <th class="text-center"><strong>Shift Timings</strong></th>
                            <th class="text-center"><strong>Emp#</strong></th>
                            <th class="text-center bg-white"><strong>Action</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/md/mdtimepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/moment.min.js" crossorigin="anonymous"></script>
<style type="text/css">
    .mdtp__clock .mdtp__minute_holder .mdtp__digit:not(.marker) span {
        margin-bottom: 2px;
    }

    .mdtp__wrapper {
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) scale(1) !important;
        max-height: max-content !important;
        box-shadow: none !important;
    }

    .form-check.form-check-custom {
        background: rgba(0, 0, 0, 0.3);
        padding: 5px 15px 5px 5px;
        border-radius: 8px;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('form#add_shift .timepicker').each(function(index, elem) {
            mdtimepicker(
                '#' + $(this).attr("id"), {
                    clearBtn: true,
                    format: 'h:mm tt',
                    hourPadding: true,
                }
            );
        })

        $(document).on("click", ".numInput.flatpickr-hour", function(e) {
            e.preventDefault();
            $(this).focus();
        })

        //begin::Initialize Datatable
        var table = $("#shifts_table").DataTable({
            "ajax": {
                url: "<?= base_url('ajax/load-shifts') ?>",
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "paging": false,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
            },
            "columns": [{
                    data: "shift_id"
                },
                {
                    data: "shift_name"
                },
                {
                    data: "shift_type",
                    render: function(data, type, row, meta) {
                        return row.shift_type == 'reduce' ? 'For Heuer' : 'Regular';
                    }
                },
                {
                    data: "monday"
                },
                {
                    data: "employee_count"
                },
                {
                    data: "actions",
                    render: function(data, type, row, meta) {
                        return '<div class="btn-group">' +
                            '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-shift" data-id="' + row.shift_id + '">' +
                            '<span class="svg-icon svg-icon-3">' +
                            '<i class="fa fa-pencil-alt" aria-hidden="true" ></i>' +
                            '</span>' +
                            '</a>' +
                            '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-shift" data-id="' + row.shift_id + '">' +
                            '<span class="svg-icon svg-icon-3">' +
                            '<i class="fas fa-trash"></i>' +
                            '</span>' +
                            '</a>' +
                            '</div>';
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '58vh',
            "select": {
                "style": 'multi',
                "selector": 'td:first-child'
            },
            "columnDefs": [{
                    "className": 'border-start border-secondary td-border-left text-center',
                    "targets": [-1]
                },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
            ],
            "initComplete": function(settings, json) {
                var the_shift_id = '<?php echo $the_shift_id; ?>';
                if (the_shift_id !== '') {
                    $('.edit-shift[data-id=' + the_shift_id + ']').trigger('click');
                }
            }
        });
        //end::Initialize Datatable

        //begin::Add Shift Ajax
        $(document).on('click', '#add_shift_submit_button', function(e) {
            e.preventDefault();
            var form = $('#add_shift');
            form.closest('.modal').modal('hide');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/add-shift'); ?>",
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
                            }).then(function(e) {
                                if (typeof response.response_data.validation != 'undefined') {
                                    form.closest('.modal').modal('show');
                                    var validation = response.response_data.validation;
                                    $.each(validation, function(index, value) {
                                        if (index.startsWith("shift_start")) {
                                            $('#shift_start_error').html(value);
                                        }
                                        if (index.startsWith("shift_end")) {
                                            $('#shift_end_error').html(value);
                                        }
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
                                location.reload();
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
        //end::Add Shift Ajax

        //begin::Delete Shift Ajax
        $(document).on('click', '.delete-shift', function(e) {
            e.preventDefault();
            var shift_id = $(this).data('id');
            var data = {
                'shift_id': shift_id,
            };

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
                    $.ajax({
                        method: "post",
                        url: "<?php echo base_url('ajax/delete-shift'); ?>",
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
                                        location.reload();
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
        //end::Delete Shift Ajax

        //begin::Open Edit Shift Modal
        $(document).on('click', '.edit-shift', function(e) {
            e.preventDefault();
            var shift_id = $(this).data('id');
            var data = {
                'shift_id': shift_id,
            };

            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/get-shift'); ?>",
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
                        if (typeof response.response_data.shift != 'undefined') {
                            var shift_data = response.response_data.shift;
                            // console.log(shift_data);
                            $("form#update_shift").find('small.error-text').html('');
                            $("form#update_shift").find('input:not(.form-check-input)').val('');
                            $("form#update_shift").find('input.form-check-input').prop('checked', false);
                            $.each(shift_data, function(index, item) {
                                if (index == 'weekoff') {
                                    $.each(item.value, function(weekoff_index, weekoff_day) {
                                        $("form#update_shift").find('input#weekoff_' + weekoff_day + '_edit').prop('checked', true);
                                    })
                                } else if (index == 'attendance_rule') {
                                    $.each(item, function(attendance_rule_index, attendance_rule_item) {
                                        $("form#update_shift").find('input#' + attendance_rule_index).val(attendance_rule_item).trigger('change');
                                    })
                                } else if (index == 'late_coming_rule') {
                                    if (item && item.length) {
                                        console.log(item);
                                        $("form#update_shift").find('input.late_coming_rule_hours').val(item[0].hours.length ? item[0].hours : '00:00');
                                        $("form#update_shift").find('input.late_coming_rule_name').val(item[0].name.length ? item[0].name : 'Daily Grace Minutes');
                                        $("form#update_shift").find('input.late_coming_rule_applicable').val(item[0].applicable.length ? item[0].applicable : 'Daily');
                                        $("form#update_shift").find('input.late_coming_rule_count').val(item[0].count.length ? item[0].count : 'Half Day Present');
                                    } else {
                                        $("form#update_shift").find('input.late_coming_rule_hours').val('00:00');
                                        $("form#update_shift").find('input.late_coming_rule_name').val('Daily Grace Minutes');
                                        $("form#update_shift").find('input.late_coming_rule_applicable').val('Daily');
                                        $("form#update_shift").find('input.late_coming_rule_count').val('Half Day Present');
                                    }
                                } else if (index == 'shift_type') {
                                    console.log(item);
                                    if (item.value == 'reduce') {
                                        $("form#update_shift").find('input#' + index).prop('checked', true);
                                    }
                                } else {
                                    $("form#update_shift").find('input#' + index).val(item.value);
                                    $("form#update_shift").find('input#' + index).attr('data-value', item.data_value);
                                    $("form#update_shift").find('input#' + index).trigger('change').trigger('input');
                                    if (index == "shift_start_monday_edit") {
                                        $('#shift_start_edit').val(item.value);
                                    }
                                    if (index == "shift_end_monday_edit") {
                                        $('#shift_end_edit').val(item.value);
                                    }
                                }
                            });

                            $('form#update_shift .timepicker').each(function(index, elem) {
                                mdtimepicker(
                                    '#' + $(this).attr("id"), {
                                        clearBtn: true,
                                        format: 'h:mm tt',
                                        hourPadding: true,
                                    }
                                );
                            })

                            $("#update_shift_modal").modal('show');
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
        //end::Open Edit Shift Modal

        //begin::Update Shift Ajax
        $(document).on('click', '#update_shift_submit_button', function(e) {
            e.preventDefault();
            var form = $('#update_shift');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/update-shift'); ?>",
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
                            }).then(function(e) {
                                if (typeof response.response_data.validation != 'undefined') {
                                    var validation = response.response_data.validation;
                                    console.log(validation);
                                    $.each(validation, function(index, value) {
                                        if (index.startsWith("shift_start_edit")) {
                                            $('#shift_start_edit_error').html(value);
                                        }
                                        if (index.startsWith("shift_end_edit")) {
                                            $('#shift_end_edit_error').html(value);
                                        }
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
                                $("form#update_shift").find('input').val('').trigger('input');
                                form.closest('.modal').modal('hide');
                                location.reload();
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
        //end::Update Shift Ajax

    })
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>