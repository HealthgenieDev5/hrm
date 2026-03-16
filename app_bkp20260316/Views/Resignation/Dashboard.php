<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>

<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .resignation-urgent {
        background-color: #ffe2e2 !important;
    }

    .resignation-warning {
        background-color: #fff4e5 !important;
    }

    /* Fixed columns styling */
    .dtfc-fixed-left,
    .dtfc-fixed-right {
        background-color: #fff !important;
    }

    .resignation-urgent td.dtfc-fixed-left,
    .resignation-urgent td.dtfc-fixed-right {
        background-color: #ffe2e2 !important;
    }

    .resignation-warning td.dtfc-fixed-left,
    .resignation-warning td.dtfc-fixed-right {
        background-color: #fff4e5 !important;
    }

    table.dataTable thead th.dtfc-fixed-left,
    table.dataTable thead th.dtfc-fixed-right {
        background-color: #f8f9fa !important;
    }

    .dtfc-fixed-right {
        box-shadow: -3px 0 5px rgba(0, 0, 0, 0.1);
    }

    .dtfc-fixed-left {
        box-shadow: 3px 0 5px rgba(0, 0, 0, 0.1);
    }
</style>

<?php
if (!in_array(session()->get('current_user')['role'], array('admin', 'superuser', 'hr'))) {
    $company_id_disabled = 'disabled';
?>
    <div class="row gy-5 g-xl-8">
        <div class="col-xl-6">
            <input type="hidden" id="company_id_for_filter" value="<?= set_value('company_id_for_filter', @$company_id_for_filter) ?>" />
        </div>
    </div>
<?php
} else {
?>
    <div class="card mb-7">
        <div class="card-body">
            <div class="row gy-5 g-xl-8">
                <div class="col-xl-6">
                    <label class="form-label" for="company_id_for_filter" class="mb-3">Company</label>
                    <select class="form-select form-select-sm" id="company_id_for_filter" data-control="select2" data-placeholder="Select a Company">
                        <option value=""></option>
                        <option value="all_companies" <?= edit_set_select('company_id_for_filter', 'all_companies', $company_id_for_filter) ?>>All Companies</option>
                        <?php foreach ($Companies as $company_row): ?>
                            <option value="<?= $company_row['id'] ?>" <?= edit_set_select('company_id_for_filter', $company_row['id'], $company_id_for_filter) ?>>
                                <?= $company_row['company_name'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-xl-6 d-flex align-items-end justify-content-end">
                    <?php if (session()->get('current_user')['employee_id'] == 52): ?>
                        <a href="<?= base_url('resignation/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus"></i> Add Resignation
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<!-- Statistics Cards -->
<div class="row gy-5 g-xl-8 mb-5">
    <div class="col-xl-2 col-md-6">
        <div class="card bg-primary bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted d-block">Total Resignations</span>
                        <h2 class="mb-0" id="stat_total_active">-</h2>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-user-minus fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6">
        <div class="card bg-dark bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted d-block">Action Required</span>
                        <h2 class="mb-0 text-dark" id="stat_overdue">-</h2>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-exclamation-circle fa-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6">
        <div class="card bg-danger bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted d-block">Ending Within 7 Days</span>
                        <h2 class="mb-0 text-danger" id="stat_urgent_alerts">-</h2>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-triangle-exclamation fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6">
        <div class="card bg-warning bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted d-block">New (Month)</span>
                        <h2 class="mb-0 text-warning" id="stat_month_new">-</h2>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-calendar-plus fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6">
        <div class="card bg-success bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted d-block">Completed (Month)</span>
                        <h2 class="mb-0 text-success" id="stat_month_completed">-</h2>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6">
        <div class="card bg-success bg-opacity-25">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted d-block">Retained</span>
                        <h2 class="mb-0 text-success" id="stat_total_retained">-</h2>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-user-check fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-stretch">
        <div class="card-title">
            <h3 class="m-0 text-gray-800">Resignation Details</h3>
        </div>
        <div class="card-toolbar">
            <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#all_resignations_tab">Employees On Resignations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#urgent_alerts_tab">
                        Urgent Alerts
                        <span class="badge badge-light-danger ms-2" id="urgent_alerts_count_badge">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#completed_resignations_tab">
                        Completed Resignations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#abscond_resignations_tab">
                        Abscond Resignations
                        <span class="badge badge-light-dark ms-2" id="abscond_count_badge">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#retained_tab">
                        Retained
                        <span class="badge badge-light-success ms-2" id="retained_count_badge">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content" id="myTabContent">
            <!-- All Resignations Tab -->
            <div class="tab-pane fade show active" id="all_resignations_tab" role="tabpanel">
                <table id="resignation_reports_table" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Notice Period</strong></th>
                            <th class="text-center"><strong>Buyout Days</strong></th>
                            <th class="text-center"><strong>Last Working Day</strong></th>
                            <th class="text-center"><strong>Days Remaining</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Retention</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                            <th style="display:none">Last Working Day As Per Resignation</th>
                            <th style="display:none">Current Status</th>
                            <th style="display:none">Remarks</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Notice Period</strong></th>
                            <th class="text-center"><strong>Buyout Days</strong></th>
                            <th class="text-center"><strong>Last Working Day</strong></th>
                            <th class="text-center"><strong>Days Remaining</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Retention</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Urgent Alerts Tab -->
            <div class="tab-pane fade" id="urgent_alerts_tab" role="tabpanel">
                <table id="resignation_alerts_table" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Notice Period</strong></th>
                            <th class="text-center"><strong>Buyout Days</strong></th>
                            <th class="text-center"><strong>Last Working Day</strong></th>
                            <th class="text-center"><strong>Days Remaining</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Retention</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Notice Period</strong></th>
                            <th class="text-center"><strong>Buyout Days</strong></th>
                            <th class="text-center"><strong>Last Working Day</strong></th>
                            <th class="text-center"><strong>Days Remaining</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Retention</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Completed Resignations Tab -->
            <div class="tab-pane fade" id="completed_resignations_tab" role="tabpanel">
                <table id="completed_resignations_table" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Last Working Day</strong></th>
                            <th class="text-center"><strong>Completed On</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Last Working Day</strong></th>
                            <th class="text-center"><strong>Completed On</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Abscond Resignations Tab -->
            <div class="tab-pane fade" id="abscond_resignations_tab" role="tabpanel">
                <table id="abscond_resignations_table" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Last Working Day</strong></th>
                            <th class="text-center"><strong>Abscond On</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Last Working Day</strong></th>
                            <th class="text-center"><strong>Abscond On</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Reason</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- Retained Resignations Tab -->
            <div class="tab-pane fade" id="retained_tab" role="tabpanel">
                <table id="retained_resignations_table" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Retained On</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Emp Code</strong></th>
                            <th class="text-center"><strong>Name</strong></th>
                            <th class="text-center"><strong>Department</strong></th>
                            <th class="text-center"><strong>Company</strong></th>
                            <th class="text-center"><strong>Resignation Date</strong></th>
                            <th class="text-center"><strong>Retained On</strong></th>
                            <th class="text-center"><strong>HOD Acknowledgment</strong></th>
                            <th class="text-center"><strong>Manager Acknowledgment</strong></th>
                            <th class="text-center"><strong>Remarks</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Revision History Offcanvas Sidebar -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="revisionHistoryOffcanvas" aria-labelledby="revisionHistoryOffcanvasLabel" style="width: 600px;">
    <div class="offcanvas-header align-items-center border-bottom" style="min-height: 70px; padding: 1rem 1.5rem;">
        <h5 class="offcanvas-title mb-0 fw-bold" id="revisionHistoryOffcanvasLabel">
            <i class="fa fa-history me-2"></i> Revision History
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" style="padding: 1.5rem;">
        <div id="revisionHistoryContent">
            <div class="text-center py-5">
                <i class="fa fa-spinner fa-spin fa-3x text-primary"></i>
                <p class="mt-3">Loading revision history...</p>
            </div>
        </div>
    </div>
</div>

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script>
    $(document).ready(function() {

        function loadStatistics() {
            $.ajax({
                url: "<?= base_url('ajax/resignation/stats') ?>",
                type: "POST",
                data: {
                    company_id: $('#company_id_for_filter').val()
                },
                success: function(response) {
                    const totalActive = response.total_active || 0;
                    const overdue = response.overdue || 0;
                    const urgentAlerts = response.urgent_alerts || 0;
                    const monthNew = response.month_new || 0;
                    const monthCompleted = response.month_completed || 0;
                    const totalAbscond = response.total_abscond || 0;
                    const totalRetained = response.total_retained || 0;

                    $('#stat_total_active').text(totalActive);
                    $('#stat_overdue').text(overdue);
                    $('#stat_urgent_alerts').text(urgentAlerts);
                    $('#stat_month_new').text(monthNew);
                    $('#stat_month_completed').text(monthCompleted);
                    $('#stat_total_retained').text(totalRetained);
                    $('#urgent_alerts_count_badge').text(urgentAlerts);
                    $('#abscond_count_badge').text(totalAbscond);
                    $('#retained_count_badge').text(totalRetained);
                },
                error: function() {
                    console.error('Failed to load statistics');
                }
            });
        }

        var resignation_reports_table = $("#resignation_reports_table").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "buttons": [{
                extend: 'excelHtml5',
                title: 'Resignation_Reports_' + new Date().toISOString().slice(0, 10),
                text: '<i class="fa-solid fa-file-excel me-1"></i>Download Excel',
                className: 'btn btn-sm btn-light',
                exportOptions: {
                    // 0:EmpCode 1:Name 2:Dept 3:Company 4:ResignDate
                    // 14:LastWorkingDate(hidden) 15:CurrentStatus(hidden) 16:Remarks(hidden)
                    // 9:HOD 10:Manager 12:Reason
                    columns: [0, 1, 2, 3, 4, 14, 15, 16, 9, 10, 12],
                    orthogonal: 'export',
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Only rename headers that differ from the <th> text.
                    // Hidden <th> elements now have text so DataTables generates cells F2/G2/H2.
                    // Visible columns I2=HOD Acknowledgment, J2=Manager Acknowledgment, K2=Reason need renaming.
                    var renames = {
                        'I2': 'HOD Acceptance',
                        'J2': 'Reporting Manager Acceptance',
                        'K2': 'Resignation Reason'
                    };
                    Object.keys(renames).forEach(function(ref) {
                        var $cell = $('c[r="' + ref + '"]', sheet);
                        if ($cell.length) {
                            $cell.find('v, is').remove();
                            $cell.attr('t', 'inlineStr').removeAttr('s');
                            $cell.append('<is><t>' + renames[ref] + '</t></is>');
                        }
                    });
                    // Set column widths to prevent "########" in date columns
                    var colWidths = [6, 14, 26, 14, 14, 18, 22, 30, 30, 32, 25];
                    var colsXml = '<cols>';
                    colWidths.forEach(function(w, i) {
                        colsXml += '<col min="' + (i + 1) + '" max="' + (i + 1) + '" width="' + w + '" customWidth="1"/>';
                    });
                    colsXml += '</cols>';
                    if ($('cols', sheet).length) {
                        $('cols', sheet).replaceWith(colsXml);
                    } else {
                        $('sheetData', sheet).before(colsXml);
                    }
                },
            }],
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/resignation/reports') ?>",
                type: "POST",
                data: {
                    company_id: function() {
                        return $('#company_id_for_filter').val();
                    }
                },
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText);
                },
                dataSrc: "",
            },
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Active Resignations</span></div>',
            },
            "oLanguage": {
                "sSearch": ""
            },
            "columns": [{
                    data: "internal_employee_id"
                },
                {
                    data: "employee_name"
                },
                {
                    data: "department_name"
                },
                {
                    data: "company_short_name"
                },
                {
                    data: "resignation_date"
                },
                {
                    data: "notice_period",
                    render: function(data) {
                        return data + ' days';
                    }
                },
                {
                    data: "buyout_days",
                    render: function(data) {
                        return data + ' days';
                    }
                },
                {
                    data: {
                        _: 'calculated_last_working_day.formatted',
                        sort: 'calculated_last_working_day.ordering',
                    }
                },
                {
                    data: "remaining_days",
                    render: function(data, type, row) {
                        return '<strong>' + data + ' days</strong>';
                    }
                },
                {
                    data: "hod_response_display",
                    render: function(data, type, row) {
                        if (type === 'export') {
                            var statusMap = {
                                'accept': 'Accepted',
                                'rejected': 'Rejected',
                                'try_to_retain': 'Try to Retain',
                                'too_early': 'Too Early',
                                'pending': 'Pending'
                            };
                            var status = statusMap[row.hod_response] || 'Pending';
                            return status + (row.hod_name ? ' By ' + row.hod_name : '');
                        }
                        let html = data;
                        if (row.hod_name) {
                            html += '<br><small class="text-muted">' + row.hod_name + '</small>';
                        }
                        if (row.hod_response_date) {
                            html += '<br><small class="text-muted">' + row.hod_response_date + '</small>';
                        }
                        return html;
                    }
                },
                {
                    data: "manager_response_display",
                    render: function(data, type, row) {
                        if (type === 'export') {
                            var statusMap = {
                                'accept': 'Accepted',
                                'rejected': 'Rejected',
                                'try_to_retain': 'Try to Retain',
                                'too_early': 'Too Early',
                                'pending': 'Pending'
                            };
                            var status = statusMap[row.manager_response] || 'Pending';
                            return status + (row.manager_name ? ' By ' + row.manager_name : '');
                        }
                        let html = data;
                        if (row.manager_name) {
                            html += '<br><small class="text-muted">' + row.manager_name + '</small>';
                        }
                        if (row.manager_response_date) {
                            html += '<br><small class="text-muted">' + row.manager_response_date + '</small>';
                        }
                        return html;
                    }
                },
                {
                    data: "retention_outcome_display"
                },
                {
                    data: "resignation_reason"
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        let actions = '<div class="d-flex align-items-center gap-2">';
                        const currentUserId = <?= session()->get('current_user')['id'] ?? 0 ?>;

                        // Only show dropdown for employee_id 52
                        if (currentUserId === 52) {
                            const hasRetentionAttempt = (row.hod_response === 'try_to_retain' || row.manager_response === 'try_to_retain');
                            actions += '<select class="form-select form-select-sm status-change-select" data-id="' + row.resignation_id + '" data-hod-response="' + (row.hod_response || '') + '" data-manager-response="' + (row.manager_response || '') + '" style="width: 140px;">';
                            actions += '<option value="active"' + (row.resignation_status === 'active' ? ' selected' : '') + '>On Resignation</option>';
                            actions += '<option value="withdrawn"' + (row.resignation_status === 'withdrawn' ? ' selected' : '') + '>Withdrawn</option>';
                            actions += '<option value="completed"' + (row.resignation_status === 'completed' ? ' selected' : '') + '>Completed</option>';
                            actions += '<option value="abscond"' + (row.resignation_status === 'abscond' ? ' selected' : '') + '>Abscond</option>';
                            if (hasRetentionAttempt) {
                                actions += '<option value="retention_failed"' + (row.resignation_status === 'retention_failed' ? ' selected' : '') + '>Retention Failed</option>';
                                actions += '<option value="retained"' + (row.resignation_status === 'retained' ? ' selected' : '') + '>Retention Successful</option>';
                            }
                            actions += '</select>';
                        } else {
                            // Show status as text only for other users
                            let statusText = '';
                            switch (row.resignation_status) {
                                case 'active':
                                    statusText = 'On Resignation';
                                    break;
                                case 'withdrawn':
                                    statusText = 'Withdrawn';
                                    break;
                                case 'completed':
                                    statusText = 'Completed';
                                    break;
                                case 'abscond':
                                    statusText = 'Abscond';
                                    break;
                                case 'left':
                                    statusText = 'Left';
                                    break;
                                case 'retained':
                                    statusText = 'Retained';
                                    break;
                                case 'retention_failed':
                                    statusText = 'Retention Failed';
                                    break;
                                default:
                                    statusText = row.resignation_status;
                            }
                            actions += '<span class="badge bg-primary" style="min-width: 100px;">' + statusText + '</span>';
                        }
                        if (currentUserId === 52) {
                            if (row.resignation_status === 'active') {
                                actions += '<a href="<?= base_url('resignation/edit') ?>/' + row.resignation_id + '" class="btn btn-sm btn-primary p-3" title="Edit"><i class="fa-solid fa-edit"></i></a>';
                            }
                            actions += '<button type="button" class="btn btn-sm btn-danger delete-resignation-btn p-3" data-id="' + row.resignation_id + '" title="Delete"><i class="fa-solid fa-trash"></i></button>';
                        }
                        actions += '<button type="button" class="btn btn-sm btn-info view-history-btn p-3" data-id="' + row.resignation_id + '" title="View History"><i class="fa-solid fa-history"></i></button>';
                        actions += '</div>';
                        return actions;
                    }
                },
                // Hidden columns for Excel export — must be AFTER Actions to land at indices 14, 15, 16
                {
                    data: 'last_working_date',
                    visible: false,
                    searchable: false,
                    defaultContent: '',
                },
                {
                    data: 'current_status_text',
                    visible: false,
                    searchable: false,
                    defaultContent: '',
                },
                {
                    data: 'remarks',
                    visible: false,
                    searchable: false,
                    defaultContent: '',
                },
            ],
            "order": [
                [7, 'asc']
            ],
            "scrollX": true,
            "scrollY": '500px',
            "paging": true,
            "fixedColumns": {
                "left": 2,
                "right": 1
            },
            "columnDefs": [{
                "className": 'text-center',
                "targets": '_all'
            }],
            "rowCallback": function(row, data) {
                if (data.alert_status === 'urgent') {
                    $(row).addClass('resignation-urgent');
                } else if (data.alert_status === 'warning') {
                    $(row).addClass('resignation-warning');
                }
            }
        });
        $('#resignation_reports_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">All Active Resignations</h3>');

        // Lazy-init tables in hidden tabs to avoid fixedColumns rendering bug
        var resignation_alerts_table = null;
        var completed_resignations_table = null;
        var abscond_resignations_table = null;
        var retained_resignations_table = null;

        function initAlertsTable() {
            if (resignation_alerts_table) return;
            resignation_alerts_table = $("#resignation_alerts_table").DataTable({
                "dom": '<"card"<"card-header bg-danger bg-opacity-10"<"card-title text-danger"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
                "buttons": [{
                    extend: 'excelHtml5',
                    title: 'Urgent_Resignation_Alerts_' + new Date().toISOString().slice(0, 10),
                    text: '<i class="fa-solid fa-file-excel"></i>Excel',
                    className: 'btn btn-sm btn-light',
                }],
                "lengthMenu": [
                    [5, 10, 25, -1],
                    [5, 10, 25, 'All'],
                ],
                "ajax": {
                    url: "<?= base_url('ajax/resignation/alerts') ?>",
                    type: "POST",
                    data: {
                        company_id: function() {
                            return $('#company_id_for_filter').val();
                        }
                    },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText);
                    },
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Urgent Alerts</span></div>',
                },
                "oLanguage": {
                    "sSearch": ""
                },
                "columns": [{
                        data: "internal_employee_id"
                    },
                    {
                        data: "employee_name"
                    },
                    {
                        data: "department_name"
                    },
                    {
                        data: "company_short_name"
                    },
                    {
                        data: "resignation_date"
                    },
                    {
                        data: "notice_period",
                        render: function(data) {
                            return data + ' days';
                        }
                    },
                    {
                        data: "buyout_days",
                        render: function(data) {
                            return data + ' days';
                        }
                    },
                    {
                        data: {
                            _: 'calculated_last_working_day.formatted',
                            sort: 'calculated_last_working_day.ordering',
                        }
                    },
                    {
                        data: "remaining_days",
                        render: function(data, type, row) {
                            return '<strong class="text-danger">' + data + ' days</strong>';
                        }
                    },
                    {
                        data: "hod_response_display",
                        render: function(data, type, row) {
                            let html = data;
                            if (row.hod_name) {
                                html += '<br><small class="text-muted">' + row.hod_name + '</small>';
                            }
                            if (row.hod_response_date) {
                                html += '<br><small class="text-muted">' + row.hod_response_date + '</small>';
                            }
                            return html;
                        }
                    },
                    {
                        data: "manager_response_display",
                        render: function(data, type, row) {
                            let html = data;
                            if (row.manager_name) {
                                html += '<br><small class="text-muted">' + row.manager_name + '</small>';
                            }
                            if (row.manager_response_date) {
                                html += '<br><small class="text-muted">' + row.manager_response_date + '</small>';
                            }
                            return html;
                        }
                    },
                    {
                        data: "retention_outcome_display"
                    },
                    {
                        data: "resignation_reason"
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            let actions = '<div class="d-flex align-items-center gap-2">';

                            const currentUserId = <?= session()->get('current_user')['id'] ?? 0 ?>;

                            // Only show dropdown for employee_id 52
                            if (currentUserId === 52) {
                                const hasRetentionAttempt = (row.hod_response === 'try_to_retain' || row.manager_response === 'try_to_retain');
                                actions += '<select class="form-select form-select-sm status-change-select" data-id="' + row.resignation_id + '" data-hod-response="' + (row.hod_response || '') + '" data-manager-response="' + (row.manager_response || '') + '" style="width: 140px;">';
                                actions += '<option value="active"' + (row.resignation_status === 'active' ? ' selected' : '') + '>On Resignation</option>';
                                actions += '<option value="retention_failed"' + (row.resignation_status === 'retention_failed' ? ' selected' : '') + '>Retention Failed</option>';
                                actions += '<option value="withdrawn"' + (row.resignation_status === 'withdrawn' ? ' selected' : '') + '>Withdrawn</option>';
                                actions += '<option value="completed"' + (row.resignation_status === 'completed' ? ' selected' : '') + '>Completed</option>';
                                actions += '<option value="abscond"' + (row.resignation_status === 'abscond' ? ' selected' : '') + '>Abscond</option>';
                                if (hasRetentionAttempt) {
                                    actions += '<option value="retained"' + (row.resignation_status === 'retained' ? ' selected' : '') + '>Retention Successful</option>';
                                }
                                actions += '</select>';
                            } else {
                                // Show status as text only for other users
                                let statusText = '';
                                switch (row.resignation_status) {
                                    case 'active':
                                        statusText = 'On Resignation';
                                        break;
                                    case 'withdrawn':
                                        statusText = 'Withdrawn';
                                        break;
                                    case 'completed':
                                        statusText = 'Completed';
                                        break;
                                    case 'abscond':
                                        statusText = 'Abscond';
                                        break;
                                    case 'left':
                                        statusText = 'Left';
                                        break;
                                    case 'retained':
                                        statusText = 'Retained';
                                        break;
                                    case 'retention_failed':
                                        statusText = 'Retention Failed';
                                        break;
                                    default:
                                        statusText = row.resignation_status;
                                }
                                actions += '<span class="badge bg-primary" style="min-width: 100px;">' + statusText + '</span>';
                            }
                            if (currentUserId === 52) {
                                if (row.resignation_status === 'active') {
                                    actions += '<a href="<?= base_url('resignation/edit') ?>/' + row.resignation_id + '" class="btn btn-sm btn-primary p-3" title="Edit"><i class="fa-solid fa-edit"></i></a>';
                                }
                                actions += '<button type="button" class="btn btn-sm btn-danger delete-resignation-btn p-3" data-id="' + row.resignation_id + '" title="Delete"><i class="fa-solid fa-trash"></i></button>';
                            }
                            actions += '<button class="btn btn-sm btn-info view-history-btn p-3" data-id="' + row.resignation_id + '" title="View History"><i class="fa-solid fa-history"></i></button>';
                            actions += '</div>';
                            return actions;
                        }
                    },
                ],
                "order": [
                    [7, 'asc']
                ],
                "scrollX": true,
                "scrollY": '500px',
                "paging": false,
                "fixedColumns": {
                    "left": 2,
                    "right": 1
                },
                "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                }],
                "rowCallback": function(row, data) {
                    $(row).addClass('resignation-urgent');
                }
            });
            $('#resignation_alerts_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title text-danger"><i class="fa-solid fa-triangle-exclamation"></i> URGENT ALERTS (≤7 Days)</h3>');
        }

        function initCompletedTable() {
            if (completed_resignations_table) return;
            completed_resignations_table = $("#completed_resignations_table").DataTable({
                "dom": '<"card"<"card-header bg-success bg-opacity-10"<"card-title text-success"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
                "buttons": [{
                    extend: 'excelHtml5',
                    title: 'Completed_Resignations_' + new Date().toISOString().slice(0, 10),
                    text: '<i class="fa-solid fa-file-excel"></i>Excel',
                    className: 'btn btn-sm btn-light',
                }],
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
                ],
                "ajax": {
                    url: "<?= base_url('ajax/resignation/completed') ?>",
                    type: "POST",
                    data: {
                        company_id: function() {
                            return $('#company_id_for_filter').val();
                        }
                    },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText);
                    },
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Completed Resignations</span></div>',
                },
                "oLanguage": {
                    "sSearch": ""
                },
                "columns": [{
                        data: "internal_employee_id"
                    },
                    {
                        data: "employee_name"
                    },
                    {
                        data: "department_name"
                    },
                    {
                        data: "company_short_name"
                    },
                    {
                        data: "resignation_date"
                    },
                    {
                        data: "last_working_day"
                    },
                    {
                        data: "completed_on"
                    },
                    {
                        data: "hod_response_display",
                        render: function(data, type, row) {
                            let html = data;
                            if (row.hod_name) {
                                html += '<br><small class="text-muted">' + row.hod_name + '</small>';
                            }
                            if (row.hod_response_date) {
                                html += '<br><small class="text-muted">' + row.hod_response_date + '</small>';
                            }
                            return html;
                        }
                    },
                    {
                        data: "manager_response_display",
                        render: function(data, type, row) {
                            let html = data;
                            if (row.manager_name) {
                                html += '<br><small class="text-muted">' + row.manager_name + '</small>';
                            }
                            if (row.manager_response_date) {
                                html += '<br><small class="text-muted">' + row.manager_response_date + '</small>';
                            }
                            return html;
                        }
                    },
                    {
                        data: "resignation_reason"
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return '<button class="btn btn-sm btn-info view-history-btn" data-id="' + row.resignation_id + '" title="View History"><i class="fa-solid fa-history"></i></button>';
                        }
                    },
                ],
                "order": [
                    [6, 'desc']
                ],
                "scrollX": true,
                "scrollY": '500px',
                "paging": true,
                "fixedColumns": {
                    "left": 2,
                    "right": 1
                },
                "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                }]
            });
            $('#completed_resignations_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title text-success"><i class="fa-solid fa-check-circle"></i> Completed Resignations</h3>');
        }

        function initAbscondTable() {
            if (abscond_resignations_table) return;
            abscond_resignations_table = $("#abscond_resignations_table").DataTable({
                "dom": '<"card"<"card-header bg-dark bg-opacity-10"<"card-title text-dark"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
                "buttons": [{
                    extend: 'excelHtml5',
                    title: 'Abscond_Resignations_' + new Date().toISOString().slice(0, 10),
                    text: '<i class="fa-solid fa-file-excel"></i>Excel',
                    className: 'btn btn-sm btn-light',
                }],
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
                ],
                "ajax": {
                    url: "<?= base_url('ajax/resignation/abscond') ?>",
                    type: "POST",
                    data: {
                        company_id: function() {
                            return $('#company_id_for_filter').val();
                        }
                    },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText);
                    },
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Abscond Resignations</span></div>',
                },
                "oLanguage": {
                    "sSearch": ""
                },
                "columns": [{
                        data: "internal_employee_id"
                    },
                    {
                        data: "employee_name"
                    },
                    {
                        data: "department_name"
                    },
                    {
                        data: "company_short_name"
                    },
                    {
                        data: "resignation_date"
                    },
                    {
                        data: "last_working_day"
                    },
                    {
                        data: "abscond_on"
                    },
                    {
                        data: "hod_response_display",
                        render: function(data, type, row) {
                            let html = data;
                            if (row.hod_name) {
                                html += '<br><small class="text-muted">' + row.hod_name + '</small>';
                            }
                            if (row.hod_response_date) {
                                html += '<br><small class="text-muted">' + row.hod_response_date + '</small>';
                            }
                            return html;
                        }
                    },
                    {
                        data: "manager_response_display",
                        render: function(data, type, row) {
                            let html = data;
                            if (row.manager_name) {
                                html += '<br><small class="text-muted">' + row.manager_name + '</small>';
                            }
                            if (row.manager_response_date) {
                                html += '<br><small class="text-muted">' + row.manager_response_date + '</small>';
                            }
                            return html;
                        }
                    },
                    {
                        data: "resignation_reason"
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return '<button class="btn btn-sm btn-info view-history-btn" data-id="' + row.resignation_id + '" title="View History"><i class="fa-solid fa-history"></i></button>';
                        }
                    },
                ],
                "order": [
                    [6, 'desc']
                ],
                "scrollX": true,
                "scrollY": '500px',
                "paging": true,
                "fixedColumns": {
                    "left": 2,
                    "right": 1
                },
                "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                }]
            });
            $('#abscond_resignations_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title text-dark"><i class="fa-solid fa-user-slash"></i> Abscond Resignations</h3>');
        }

        function initRetainedTable() {
            if (retained_resignations_table) return;
            retained_resignations_table = $("#retained_resignations_table").DataTable({
                "dom": '<"card"<"card-header bg-success bg-opacity-10"<"card-title text-success"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
                "buttons": [{
                    extend: 'excelHtml5',
                    title: 'Retained_Resignations_' + new Date().toISOString().slice(0, 10),
                    text: '<i class="fa-solid fa-file-excel"></i>Excel',
                    className: 'btn btn-sm btn-light',
                }],
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
                ],
                "ajax": {
                    url: "<?= base_url('ajax/resignation/retained') ?>",
                    type: "POST",
                    data: {
                        company_id: function() {
                            return $('#company_id_for_filter').val();
                        }
                    },
                    error: function(jqXHR, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText);
                    },
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Retained Resignations</span></div>',
                },
                "oLanguage": {
                    "sSearch": ""
                },
                "columns": [{
                        data: "internal_employee_id"
                    },
                    {
                        data: "employee_name"
                    },
                    {
                        data: "department_name"
                    },
                    {
                        data: "company_short_name"
                    },
                    {
                        data: "resignation_date"
                    },
                    {
                        data: "retained_on"
                    },
                    {
                        data: "hod_response_display",
                        render: function(data, type, row) {
                            let html = data;
                            if (row.hod_name) html += '<br><small class="text-muted">' + row.hod_name + '</small>';
                            if (row.hod_response_date) html += '<br><small class="text-muted">' + row.hod_response_date + '</small>';
                            return html;
                        }
                    },
                    {
                        data: "manager_response_display",
                        render: function(data, type, row) {
                            let html = data;
                            if (row.manager_name) html += '<br><small class="text-muted">' + row.manager_name + '</small>';
                            if (row.manager_response_date) html += '<br><small class="text-muted">' + row.manager_response_date + '</small>';
                            return html;
                        }
                    },
                    {
                        data: "remarks"
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return '<button class="btn btn-sm btn-info view-history-btn" data-id="' + row.resignation_id + '" title="View History"><i class="fa-solid fa-history"></i></button>';
                        }
                    },
                ],
                "order": [
                    [5, 'desc']
                ],
                "scrollX": true,
                "scrollY": '500px',
                "paging": true,
                "fixedColumns": {
                    "left": 2,
                    "right": 1
                },
                "columnDefs": [{
                    "className": 'text-center',
                    "targets": '_all'
                }]
            });
            $('#retained_resignations_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title text-success"><i class="fa-solid fa-user-check"></i> Retained Resignations</h3>');
        }

        $('#company_id_for_filter').on('change.select2', function() {
            var company_id = $(this).val();
            window.location.href = "<?= base_url('resignation') ?>/" + company_id;
        });

        // Initialize hidden-tab tables only when their tab is first shown
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const target = $(e.target).attr("href");
            if (target === '#urgent_alerts_tab') {
                initAlertsTable();
            } else if (target === '#completed_resignations_tab') {
                initCompletedTable();
            } else if (target === '#abscond_resignations_tab') {
                initAbscondTable();
            } else if (target === '#retained_tab') {
                initRetainedTable();
            }
        });

        // Change resignation status - works for all tables
        $(document).on('change', '.status-change-select', function() {
            const selectEl = $(this);
            const id = selectEl.data('id');
            const newStatus = selectEl.val();

            const statusConfig = {
                'active': {
                    title: 'Revert to On Resignation',
                    placeholder: 'Reason for reverting'
                },
                'withdrawn': {
                    title: 'Mark as Withdrawn',
                    placeholder: 'Reason for withdrawal'
                },
                'completed': {
                    title: 'Mark as Completed',
                    placeholder: 'Completion remarks'
                },
                'abscond': {
                    title: 'Mark as Abscond',
                    placeholder: 'Abscond remarks'
                },
                'left': {
                    title: 'Mark as Left',
                    placeholder: 'Remarks'
                },
                'retained': {
                    title: 'Retention Successful',
                    placeholder: 'Why was employee retained?'
                },
                'retention_failed': {
                    title: 'Retention Failed',
                    placeholder: 'Why did retention fail?'
                },
            };
            const cfg = statusConfig[newStatus] || {
                title: 'Change Status',
                placeholder: 'Remarks'
            };

            // Statuses that require last working date
            const statusesRequiringDate = ['completed', 'abscond', 'left'];
            const needsDate = statusesRequiringDate.includes(newStatus);

            const dateHtml = needsDate ?
                `<div class="mb-3">
                       <label class="form-label text-start d-block">Last Working Date</label>
                       <input type="text" id="swal_last_working_date" class="form-control" placeholder="Select last working date">
                   </div>` :
                '';

            Swal.fire({
                title: cfg.title,
                html: `
                    ${dateHtml}
                    <div class="mb-1">
                        <label class="form-label text-start d-block">Remarks <span class="text-danger">*</span></label>
                        <textarea id="swal_remarks" class="form-control" rows="3" placeholder="${cfg.placeholder}"></textarea>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-sm btn-primary",
                    cancelButton: "btn btn-sm btn-secondary"
                },
                didOpen: () => {
                    if (needsDate) {
                        flatpickr('#swal_last_working_date', {
                            dateFormat: 'Y-m-d',
                            defaultDate: new Date(),
                            allowInput: true
                        });
                    }
                    // Focus remarks textarea
                    setTimeout(() => document.getElementById('swal_remarks').focus(), 100);
                },
                preConfirm: () => {
                    const remarks = document.getElementById('swal_remarks').value.trim();
                    if (!remarks) {
                        Swal.showValidationMessage('Remarks are required.');
                        return false;
                    }
                    if (needsDate) {
                        const lastWorkingDate = document.getElementById('swal_last_working_date').value;
                        if (!lastWorkingDate) {
                            Swal.showValidationMessage('Please select a last working date.');
                            return false;
                        }
                        return {
                            remarks,
                            lastWorkingDate
                        };
                    }
                    return {
                        remarks,
                        lastWorkingDate: null
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateResignationStatus(id, newStatus, result.value.lastWorkingDate, result.value.remarks);
                } else {
                    // Revert to previous value if cancelled
                    resignation_reports_table.ajax.reload();
                    if (resignation_alerts_table) resignation_alerts_table.ajax.reload();
                }
            });
        });

        // Function to update resignation status via AJAX
        function updateResignationStatus(id, newStatus, lastWorkingDate, remarks) {
            let postData = {
                status: newStatus,
                remarks: remarks || '',
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            };

            if (lastWorkingDate) {
                postData.last_working_date = lastWorkingDate;
            }

            $.ajax({
                url: '<?= base_url('resignation/change-status') ?>/' + id,
                type: 'POST',
                dataType: 'json',
                data: postData,
                success: function(response) {
                    resignation_reports_table.ajax.reload();
                    if (resignation_alerts_table) resignation_alerts_table.ajax.reload();
                    if (completed_resignations_table) completed_resignations_table.ajax.reload();
                    if (abscond_resignations_table) abscond_resignations_table.ajax.reload();
                    if (retained_resignations_table) retained_resignations_table.ajax.reload();
                    loadStatistics();
                    Swal.fire('Success', response.success || 'Status updated successfully', 'success');
                },
                error: function(xhr) {
                    // Revert select to previous value
                    resignation_reports_table.ajax.reload();
                    if (resignation_alerts_table) resignation_alerts_table.ajax.reload();
                    Swal.fire({
                        html: xhr.responseJSON?.error || "Error changing status. Please try again.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    });
                }
            });
        }

        // Delete resignation (only for employee_id 52)
        $(document).on('click', '.delete-resignation-btn', function() {
            const resignationId = $(this).data('id');
            Swal.fire({
                title: 'Delete Resignation?',
                text: 'This will permanently delete the resignation record and all related history. This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('<?= base_url('resignation/delete') ?>/' + resignationId, {
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                        })
                        .done(function(response) {
                            if (response.response_type === 'success') {
                                Swal.fire('Deleted!', response.response_description, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', response.response_description, 'error');
                            }
                        })
                        .fail(function() {
                            Swal.fire('Error', 'An unexpected error occurred.', 'error');
                        });
                }
            });
        });

        // View revision history - works for all tables
        $(document).on('click', '.view-history-btn', function() {
            const resignationId = $(this).data('id');
            loadRevisionHistory(resignationId);
        });

        // Function to load revision history
        function loadRevisionHistory(resignationId) {
            $('#revisionHistoryContent').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-3">Loading revision history...</p></div>');

            const offcanvas = new bootstrap.Offcanvas(document.getElementById('revisionHistoryOffcanvas'));
            offcanvas.show();

            $.ajax({
                url: '<?= base_url('ajax/resignation/history') ?>/' + resignationId,
                type: 'GET',
                success: function(revisions) {
                    if (revisions.length === 0) {
                        $('#revisionHistoryContent').html('<div class="alert alert-info"><i class="fa fa-info-circle"></i> No revision history available.</div>');
                        return;
                    }

                    let html = '<div class="timeline-container">';

                    revisions.forEach(function(revision, index) {
                        const isLast = index === revisions.length - 1;

                        const colorConfig = {
                            'created': {
                                color: 'success',
                                icon: 'fa-plus-circle',
                                bgLight: '#d1e7dd'
                            },
                            'updated': {
                                color: 'warning',
                                icon: 'fa-pencil-alt',
                                bgLight: '#fff3cd'
                            },
                            'completed': {
                                color: 'info',
                                icon: 'fa-check-circle',
                                bgLight: '#cff4fc'
                            },
                            'withdrawn': {
                                color: 'secondary',
                                icon: 'fa-times-circle',
                                bgLight: '#e2e3e5'
                            }
                        };

                        const config = colorConfig[revision.action] || colorConfig['withdrawn'];

                        html += '<div class="d-flex mb-4 position-relative">';

                        // Left side - Timeline
                        html += '<div class="flex-shrink-0 d-flex flex-column align-items-center" style="width: 50px;">';
                        html += '<div class="rounded-circle bg-' + config.color + ' text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; z-index: 2;">';
                        html += '<i class="fa ' + config.icon + '"></i>';
                        html += '</div>';
                        if (!isLast) {
                            html += '<div class="flex-grow-1 border-start border-2 border-' + config.color + ' mt-2" style="width: 2px; min-height: 100px;"></div>';
                        }
                        html += '</div>';

                        // Right side - Content
                        html += '<div class="flex-grow-1 ms-3">';
                        html += '<div class="card border-0 shadow-sm">';
                        html += '<div class="card-body p-3">';

                        // Header
                        html += '<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">';
                        html += '<div class="d-flex align-items-center gap-2">';
                        html += '<span class="badge bg-' + config.color + ' px-2 py-1">' + revision.action.toUpperCase() + '</span>';
                        html += '<span class="fw-semibold">' + revision.action_by_name + '</span>';
                        html += '<span class="text-muted small">(' + revision.action_by_emp_id + ')</span>';
                        html += '</div>';
                        html += '<span class="text-muted small text-nowrap">' + formatDate(revision.created_at).replace('<br>', ' ') + '</span>';
                        html += '</div>';

                        // Action note
                        if (revision.action_note) {
                            html += '<div class="alert alert-light border-0 mb-2 p-2" style="background-color: ' + config.bgLight + ';">';
                            html += '<small><i class="fa fa-info-circle me-1"></i>' + revision.action_note + '</small>';
                            html += '</div>';
                        }

                        // Data section
                        if (revision.action == 'updated' && Object.keys(revision.revision_data).length > 0) {
                            html += '<div class="mt-2">';
                            Object.keys(revision.revision_data).forEach(function(field) {
                                const change = revision.revision_data[field];
                                const fieldLabel = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                                html += '<div class="mb-2 p-2 rounded" style="background-color: #f8f9fa;">';
                                html += '<small class="text-muted d-block mb-1">' + fieldLabel + '</small>';
                                html += '<div class="d-flex align-items-center flex-wrap gap-2">';
                                html += '<span class="badge bg-light text-dark border text-decoration-line-through">' + (change.old || 'Empty') + '</span>';
                                html += '<i class="fa fa-long-arrow-alt-right text-success"></i>';
                                html += '<span class="badge bg-success">' + (change.new || 'Empty') + '</span>';
                                html += '</div></div>';
                            });
                            html += '</div>';
                        } else if (revision.action != 'updated') {
                            // Show snapshot for created/completed/withdrawn
                            html += '<div class="mt-2">';
                            html += '<small class="text-muted d-block mb-2"><strong>Details:</strong></small>';
                            html += '<div class="row g-2">';
                            if (revision.revision_data.resignation_date) {
                                html += '<div class="col-6"><div class="p-2 rounded" style="background-color: #f8f9fa;"><small class="text-muted d-block">Date</small><strong class="small">' + revision.revision_data.resignation_date + '</strong></div></div>';
                            }
                            if (revision.revision_data.buyout_days !== undefined) {
                                html += '<div class="col-6"><div class="p-2 rounded" style="background-color: #f8f9fa;"><small class="text-muted d-block">Buyout</small><strong class="small">' + revision.revision_data.buyout_days + ' days</strong></div></div>';
                            }
                            if (revision.revision_data.last_working_date) {
                                html += '<div class="col-6"><div class="p-2 rounded" style="background-color: #f8f9fa;"><small class="text-muted d-block">Last Working Day</small><strong class="small">' + revision.revision_data.last_working_date + '</strong></div></div>';
                            }
                            if (revision.revision_data.status) {
                                html += '<div class="col-6"><div class="p-2 rounded" style="background-color: #f8f9fa;"><small class="text-muted d-block">Status</small><strong class="small text-capitalize">' + revision.revision_data.status + '</strong></div></div>';
                            }
                            if (revision.revision_data.resignation_reason) {
                                html += '<div class="col-12"><div class="p-2 rounded" style="background-color: #f8f9fa;"><small class="text-muted d-block">Reason</small><strong class="small">' + revision.revision_data.resignation_reason + '</strong></div></div>';
                            }
                            html += '</div></div>';
                        }

                        html += '</div></div></div></div>';
                    });
                    html += '</div>';

                    $('#revisionHistoryContent').html(html);
                },
                error: function() {
                    $('#revisionHistoryContent').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Error loading revision history.</div>');
                }
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }) + '<br>' +
                date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
        }

        loadStatistics();
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>