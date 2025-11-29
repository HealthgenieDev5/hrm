<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .dataTables_wrapper {
        overflow-x: auto;
        width: 100%;
    }

    .dataTables_scroll {
        overflow-x: auto;
        width: 100%;
    }

    .dataTables_scrollBody {
        border-left: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        overflow-x: scroll !important;
        width: 100%;
    }

    .dataTables_scrollHead,
    .dataTables_scrollFoot {
        overflow: hidden;
    }

    table.dataTable {
        width: 100% !important;
        margin: 0 !important;
    }

    table.dataTable.fixedColumns-left tbody tr td:first-child,
    table.dataTable.fixedColumns-left thead tr th:first-child {
        border-left: 1px solid #dee2e6;
    }

    table.dataTable.fixedColumns-right tbody tr td:last-child,
    table.dataTable.fixedColumns-right thead tr th:last-child {
        border-right: 1px solid #dee2e6;
    }

    .dtfc-fixed-left,
    .dtfc-fixed-right {
        background-color: #f8f9fa !important;
    }

    /* Vertical line after Department column */
    #job_listings_table thead th:nth-child(2),
    #job_listings_table tbody td:nth-child(2),
    #job_listings_table tfoot th:nth-child(2) {
        border-right: 2px solid #dee2e6 !important;
    }

    /* Force Actions column header to be fixed */
    .dtfc-fixed-right th:last-child,
    .dtfc-fixed-right td:last-child {
        position: sticky !important;
        right: 0 !important;
        background-color: #f8f9fa !important;
        z-index: 10 !important;
    }

    /* Ensure header synchronization */
    table.dataTable thead th:last-child {
        position: sticky !important;
        right: 0 !important;
        background-color: #f8f9fa !important;
        z-index: 11 !important;
    }

    /* Footer synchronization */
    table.dataTable tfoot th:last-child {
        position: sticky !important;
        right: 0 !important;
        background-color: #f8f9fa !important;
        z-index: 11 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!--begin::Row-->
<div class="row gy-5 g-xl-8 mb-5">
    <!--begin::Col-->
    <div class="col-xl-12">
        <!--begin::Mixed Widget 2-->
        <div class="card">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Job Listings</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Manage and filter job listings</span>
                </h3>
                <div class="card-toolbar">
                    <a href="<?= site_url('/recruitment/job-listing') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Create Job Listing
                    </a>
                </div>
            </div>
            <!--end::Header-->

            <!--begin::Body-->
            <div class="card-body">
                <form id="filter_form" class="row" enctype='multipart/form-data'>
                    <div class="col-lg-2">
                        <label class="form-label" for="company" class="mb-3">Company</label>
                        <select class="form-select form-select-sm" id="company" name="company[]" multiple data-control="select2" data-placeholder="Select a Company">
                            <option value=""></option>
                            <option value="all_companies">All Companies</option>
                            <?php if (isset($companies) && !empty($companies)): ?>
                                <?php foreach ($companies as $company_row): ?>
                                    <option value="<?= $company_row['id'] ?>"><?= esc($company_row['company_name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label" for="department" class="mb-3">Department</label>
                        <select class="form-select form-select-sm" id="department" name="department[]" multiple data-control="select2" data-placeholder="Select a Department">
                            <option value=""></option>
                            <option value="all_departments">All Departments</option>
                            <?php if (isset($departments) && !empty($departments)): ?>
                                <?php foreach ($departments as $department_row): ?>
                                    <option value="<?= $department_row['id'] ?>"><?= esc($department_row['department_name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label" for="status" class="mb-3">Job Status</label>
                        <select class="form-select form-select-sm" id="status" name="status[]" multiple data-control="select2" data-placeholder="Select Job Status">
                            <option value=""></option>
                            <option value="all_status">All Status</option>
                            <option value="open">Open</option>
                            <option value="in progress">In Progress</option>
                            <option value="closed">Closed</option>
                            <option value="pending">Pending</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label" for="job_type" class="mb-3">Job Type</label>
                        <select class="form-select form-select-sm" id="job_type" name="job_type[]" multiple data-control="select2" data-placeholder="Select Job Type">
                            <option value=""></option>
                            <option value="all_job_types">All Job Types</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Internship">Internship</option>
                            <option value="Temporary">Temporary</option>
                            <option value="Freelance">Freelance</option>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label" for="date_range_for_filter" class="mb-3">Date Range</label>
                        <div class="position-relative d-flex align-items-center">
                            <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            <input type="text" id="date_range_for_filter" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select date range" />
                            <input type="hidden" id="from_date" name="from_date" />
                            <input type="hidden" id="to_date" name="to_date" />
                        </div>
                        <small class="text-muted">Job opening date range</small>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label"> &nbsp; </label><br>
                        <button type="submit" id="filter_form_submit" class="btn btn-sm btn-primary d-inline">
                            <span class="indicator-label">Filter</span>
                            <span class="indicator-progress">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button type="button" id="reset_filters" class="btn btn-sm btn-secondary ms-2">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--begin::Job Listings Table-->
<div class="row gy-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card">
            <!--begin::Header-->
            <div class="card-header">
                <h3 class="card-title">Job Listings</h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body">
                <div id="job_listings_container">
                    <table id="job_listings_table" class="table table-sm table-hover table-striped table-row-bordered" style="width: 100% !important;">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>Job Title<br> Company</strong></th>
                                <th class="text-center"><strong>Department</strong></th>
                                <th class="text-center"><strong>Job Type</strong></th>
                                <th class="text-center"><strong>Budget Range</strong></th>
                                <th class="text-center"><strong>Experience</strong></th>
                                <th class="text-center"><strong>Vacancies</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Opening Date</strong></th>
                                <th class="text-center"><strong>Interview<br>Location</strong></th>
                                <th class="text-center"><strong>Seating<br>Location</strong></th>
                                <th class="text-center"><strong>System<br>Required</strong></th>
                                <th class="text-center"><strong>Tests<br>Required</strong></th>
                                <th class="text-center"><strong>Shift Timing</strong></th>
                                <th class="text-center"><strong>Industry</strong></th>
                                <!--<th class="text-center"><strong>Reporting To</strong></th>
                                <th class="text-center"><strong>Target<br>Closure</strong></th> 
                                 <th class="text-center"><strong>Expected<br>Closure</strong></th> -->
                                <th class="text-center"><strong>Created Date</strong></th>
                                <th class="text-center bg-white"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>Job Title<br> Company</strong></th>
                                <th class="text-center"><strong>Department</strong></th>
                                <th class="text-center"><strong>Job Type</strong></th>
                                <th class="text-center"><strong>Budget Range</strong></th>
                                <th class="text-center"><strong>Experience</strong></th>
                                <th class="text-center"><strong>Vacancies</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Opening Date</strong></th>
                                <th class="text-center"><strong>Interview<br>Location</strong></th>
                                <th class="text-center"><strong>Seating<br>Location</strong></th>
                                <th class="text-center"><strong>System<br>Required</strong></th>
                                <th class="text-center"><strong>Tests<br>Required</strong></th>
                                <th class="text-center"><strong>Shift Timing</strong></th>
                                <th class="text-center"><strong>Industry</strong></th>
                                <!--<th class="text-center"><strong>Reporting To</strong></th>
                                 <th class="text-center"><strong>Target<br>Closure</strong></th>
                                <th class="text-center"><strong>Expected<br>Closure</strong></th> -->
                                <th class="text-center"><strong>Created Date</strong></th>
                                <th class="text-center bg-white"><strong>Actions</strong></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>





<?= $this->section('javascript') ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        const closeButtons = document.querySelectorAll('.close-job-listing');
        closeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, close it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    });

    $(document).ready(function() {


        var table = $("#job_listings_table").DataTable({
            "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
            "paging": false,
            "ajax": {
                url: "<?= base_url('recruitment/job-listing/ajax') ?>",
                type: "POST",
                data: {
                    filter: function() {
                        return $('#filter_form').serialize();
                    }
                },
                dataSrc: "",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                }
            },
            "columns": [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex flex-column">
                            <a href="<?= site_url('recruitment/job-listing/view/') ?>${row.id}" class="text-gray-800 text-hover-primary fs-6 fw-bolder">
                                ${row.job_title}
                            </a>
                            <span class="text-muted fs-7">${row.company_name}</span>
                        </div>`;
                    }
                },
                {
                    data: "department_name",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-6">${data}</span>`;
                    }
                },
                {
                    data: "type_of_job",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-6">${data}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex flex-column">
                            <span class="text-gray-600 fs-7">₹${parseInt(row.min_budget).toLocaleString()}</span>
                            <span class="text-gray-600 fs-7">₹${parseInt(row.max_budget).toLocaleString()}</span>
                        </div>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-6">${row.min_experience}-${row.max_experience} Yrs</span>`;
                    }
                },
                {
                    data: "no_of_vacancy",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-6 fw-bold">${data}</span>`;
                    }
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        return `<span class="badge badge-light-${row.status_badge_class}">${data}</span>`;
                    }
                },
                {
                    data: "job_opening_date",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-7">${data}</span>`;
                    }
                },
                {
                    data: "interview_location",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-7">${data}</span>`;
                    }
                },
                {
                    data: "seating_location",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-7">${data}</span>`;
                    }
                },
                {
                    data: "system_required",
                    render: function(data, type, row, meta) {
                        return `<span class="badge badge-light-${row.system_required_badge}">${data}</span>`;
                    }
                },
                {
                    data: "tests_required",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-7">${data}</span>`;
                    }
                },
                {
                    data: "shift_timing",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-7">${data}</span>`;
                    }
                },
                {
                    data: "specific_industry",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-7">${data}</span>`;
                    }
                },

                {
                    data: "created_at",
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-7">${data}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<div class="btn-group">
                            <a href="<?= site_url('recruitment/job-listing/download/') ?>${row.id}" class="btn btn-light-primary btn-active-primary btn-sm" data-bs-toggle="tooltip" title="Download PDF">
                                <span class="fa fa-download"></span>
                            </a>
                            <a href="<?= site_url('recruitment/job-listing/view/') ?>${row.id}" class="btn btn-light-success btn-active-success btn-sm" data-bs-toggle="tooltip" title="View Job">
                                <span class="fa fa-eye"></span>
                            </a>
                            <a href="<?= site_url('recruitment/job-listing/edit/') ?>${row.id}" class="btn btn-light-warning btn-active-warning btn-sm" data-bs-toggle="tooltip" title="Edit Job">
                                <span class="fa fa-edit"></span>
                            </a>
                            <a href="<?= site_url('recruitment/job-listing/close/') ?>${row.id}" class="btn btn-light-danger btn-active-danger btn-sm close-job-listing" data-bs-toggle="tooltip" title="Close Job">
                                <span class="fa fa-times"></span>
                            </a>
                        </div>`;
                    }
                }
            ],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "order": [],
            "buttons": ['excel'],
            "fixedColumns": {
                left: 2,
                right: 1
            },
        });
        // Initialize date range picker
        $('#date_range_for_filter').flatpickr({
            mode: 'range',
            dateFormat: 'Y-m-d',
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    $('#from_date').val(instance.formatDate(selectedDates[0], 'Y-m-d'));
                    $('#to_date').val(instance.formatDate(selectedDates[1], 'Y-m-d'));
                } else {
                    $('#from_date').val('');
                    $('#to_date').val('');
                }
                table.ajax.reload();
            }
        });

        // Handle filter form submission
        $('#filter_form').on('submit', function(e) {
            e.preventDefault();

            var $submitBtn = $('#filter_form_submit');
            var $indicator = $submitBtn.find('.indicator-label');
            var $progress = $submitBtn.find('.indicator-progress');

            // Show loading state
            $submitBtn.attr('disabled', true);
            $indicator.hide();
            $progress.show();

            // Reload DataTable with new filter data
            table.ajax.reload(function() {
                // Hide loading state after reload
                $submitBtn.attr('disabled', false);
                $indicator.show();
                $progress.hide();

                // Rebind close buttons after table reload
                bindCloseButtons();
            });
        });

        // Filter form inputs (for real-time filtering if needed)
        $('#company, #department, #job_type, #status').on('change', function() {
            table.ajax.reload();
        });

        // Reset filters
        $('#reset_filters').on('click', function() {
            $('#filter_form')[0].reset();
            $('#filter_form select').val(null).trigger('change');
            $('#date_range_for_filter').val('');
            $('#from_date').val('');
            $('#to_date').val('');
            table.ajax.reload();
        });


        // Company change event to filter departments
        $('#company').on('change', function() {
            var selectedCompanies = $(this).val();
            var departmentSelect = $('#department');

            if (selectedCompanies && selectedCompanies.length > 0) {
                if (selectedCompanies.includes('all_companies')) {
                    departmentSelect.find('option').show();
                } else {
                    departmentSelect.find('option').each(function() {
                        var option = $(this);
                        var companyId = option.data('company-id');

                        if (!companyId || selectedCompanies.includes(companyId.toString())) {
                            option.show();
                        } else {
                            option.hide();
                        }
                    });
                }
            } else {
                departmentSelect.find('option').show();
            }

            departmentSelect.trigger('change.select2');
        });

        function bindCloseButtons() {
            const closeButtons = document.querySelectorAll('.close-job-listing');
            closeButtons.forEach(button => {
                button.removeEventListener('click', handleCloseClick);
                button.addEventListener('click', handleCloseClick);
            });
        }

        function handleCloseClick(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, close it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>