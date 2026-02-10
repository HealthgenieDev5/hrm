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
<!--begin::Filter Card-->
<div class="row gy-5 g-xl-8 mb-5">
    <!--begin::Col-->
    <div class="col-xl-12">
        <!--begin::Filter Card-->
        <div class="card">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Employee Notifications</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Manage and filter notifications</span>
                </h3>
            </div>
            <!--end::Header-->

            <!--begin::Body-->
            <div class="card-body">
                <form id="filter_form" class="row" enctype='multipart/form-data'>
                    <div class="col-lg-3">
                        <label class="form-label" for="notification_type">Notification Type</label>
                        <select class="form-select form-select-sm" id="notification_type" name="notification_type[]" multiple data-control="select2" data-placeholder="Select Type">
                            <option value=""></option>
                            <option value="all_types">All Types</option>
                            <option value="event">Event</option>
                            <option value="reminder">Reminder</option>
                            <option value="alert">Alert</option>
                            <option value="announcement">Announcement</option>
                            <option value="policy">Policy</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select form-select-sm" id="status" name="status[]" multiple data-control="select2" data-placeholder="Select Status">
                            <option value=""></option>
                            <option value="all_status">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label" for="date_range_for_filter">Event Date Range</label>
                        <div class="position-relative d-flex align-items-center">
                            <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            <input type="text" id="date_range_for_filter" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select date range" />
                            <input type="hidden" id="from_date" name="from_date" />
                            <input type="hidden" id="to_date" name="to_date" />
                        </div>
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

<!--begin::Notifications Table-->
<div class="row gy-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card">
            <!--begin::Header-->
            <div class="card-header">
                <h3 class="card-title"><?= $is_admin ? 'All Notifications' : 'My Notifications' ?></h3>
                <div class="card-toolbar">
                    <?php //if ($is_admin): 
                    ?>
                    <a href="<?= base_url('backend/notifications/create') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Create Notification
                    </a>
                    <?php //endif; 
                    ?>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body">
                <div id="notifications_container">
                    <table id="notificationsTable" class="table table-sm table-hover table-striped table-row-bordered" style="width: 100% !important;">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>ID</strong></th>
                                <th class="text-center"><strong>Title</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Event Date</strong></th>
                                <?php if ($is_admin): ?>
                                    <th class="text-center"><strong>Read Count</strong></th>
                                    <th class="text-center"><strong>Created By</strong></th>
                                <?php endif; ?>
                                <th class="text-center"><strong>Created At</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center bg-white"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data loaded via AJAX -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>ID</strong></th>
                                <th class="text-center"><strong>Title</strong></th>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Event Date</strong></th>
                                <?php if ($is_admin): ?>
                                    <th class="text-center"><strong>Read Count</strong></th>
                                    <th class="text-center"><strong>Created By</strong></th>
                                <?php endif; ?>
                                <th class="text-center"><strong>Created At</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
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
    var isAdmin = <?= $is_admin ? 'true' : 'false' ?>;
    var currentEmployeeId = '<?= $current_employee_id ?>';

    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize DataTable with AJAX
        var table = $('#notificationsTable').DataTable({
            "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
            "paging": false,
            "processing": true,
            "ajax": {
                url: '<?= base_url("ajax/notifications/table") ?>',
                type: 'POST',
                data: {
                    filter: function() {
                        return $('#filter_form').serialize();
                    }
                },
                dataSrc: '',
                error: function(jqXHR, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText);
                }
            },
            "columns": (function() {
                var columns = [{
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return `<span class="text-gray-600 fs-6 fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'title',
                        render: function(data, type, row, meta) {
                            return `<div class="d-flex flex-column">
                                <span class="text-gray-800 text-hover-primary fs-6 fw-bolder">${data}</span>
                                <span class="text-muted fs-7">${row.description ? row.description.substring(0, 50) + '...' : ''}</span>
                            </div>`;
                        }
                    },
                    {
                        data: 'notification_type',
                        render: function(data, type, row, meta) {
                            var badges = {
                                'event': 'info',
                                'reminder': 'warning',
                                'alert': 'danger',
                                'announcement': 'success',
                                'policy': 'secondary',
                                'other': 'dark'
                            };
                            var badgeClass = badges[data] || 'secondary';
                            return `<span class="badge badge-light-${badgeClass}">${data.toUpperCase()}</span>`;
                        }
                    },
                    {
                        data: 'event_date',
                        render: function(data, type, row, meta) {
                            return `<span class="text-gray-600 fs-7">${new Date(data).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            })}</span>`;
                        }
                    }
                ];

                // Add admin-only columns
                if (isAdmin) {
                    columns.push({
                        data: 'read_count',
                        render: function(data, type, row, meta) {
                            return `<span class="badge badge-light-primary">${data} reads</span>`;
                        }
                    });
                    columns.push({
                        data: 'created_by_name',
                        render: function(data, type, row, meta) {
                            return `<span class="text-gray-600 fs-7">${data || 'N/A'}</span>`;
                        }
                    });
                }

                // Common columns
                columns.push({
                    data: 'created_at',
                    render: function(data, type, row, meta) {
                        return `<span class="text-gray-600 fs-7">${new Date(data).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</span>`;
                    }
                });
                columns.push({
                    data: 'is_active',
                    render: function(data, type, row, meta) {
                        return data == 1 ?
                            '<span class="badge badge-light-success">Active</span>' :
                            '<span class="badge badge-light-secondary">Inactive</span>';
                    }
                });

                // Actions column - shows Edit/Delete for admins or notification creators
                columns.push({
                    data: null,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        // Check if user can edit/delete (admin or creator)
                        var canEdit = isAdmin || row.created_by == currentEmployeeId;

                        if (canEdit) {
                            return `
                                <div class="btn-group">
                                    <a href="<?= base_url('backend/notifications/edit/') ?>${row.id}"
                                       class="btn btn-light-warning btn-active-warning btn-sm"
                                       data-bs-toggle="tooltip"
                                       title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-light-danger btn-active-danger btn-sm delete-notification"
                                            data-id="${row.id}"
                                            data-bs-toggle="tooltip"
                                            title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        } else {
                            // For notifications user cannot edit, show read status
                            return row.is_read_by_me ?
                                '<span class="badge badge-light-success"><i class="fa fa-check me-1"></i>Read</span>' :
                                '<span class="badge badge-light-warning"><i class="fa fa-eye me-1"></i>Unread</span>';
                        }
                    }
                });

                return columns;
            })(),
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "order": [
                [0, 'desc']
            ],
            "buttons": ['excel'],
            "fixedColumns": {
                left: 1,
                right: 1
            },
            "language": {
                emptyTable: "No notifications found",
                processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
            }
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

        // Filter form submit
        $('#filter_form').on('submit', function(e) {
            e.preventDefault();

            var $submitBtn = $('#filter_form_submit');
            var $indicator = $submitBtn.find('.indicator-label');
            var $progress = $submitBtn.find('.indicator-progress');

            $submitBtn.attr('disabled', true);
            $indicator.hide();
            $progress.show();

            table.ajax.reload(function() {
                $submitBtn.attr('disabled', false);
                $indicator.show();
                $progress.hide();
            });
        });

        // Auto filter on select change
        $('#notification_type, #status').on('change', function() {
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

        // Delete notification handler
        $(document).on('click', '.delete-notification', function() {
            var notificationId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("backend/notifications/delete/") ?>' + notificationId,
                        method: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.response_type === 'success') {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.response_description,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    table.ajax.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.response_description,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>