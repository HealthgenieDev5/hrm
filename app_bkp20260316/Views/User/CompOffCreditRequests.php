<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Comp Off Credit Requests</h3>
                    <div class="card-toolbar">
                        
                    </div>
                </div>
                <div class="card-body">
                    <table id="comp_off_credit_requests_table" class="table table-striped nowrap">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Expiry date</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Exchange</strong></th>
                                <th class="text-center"><strong>Reporting Manager</strong></th>
                                <th class="text-center"><strong>HOD</strong></th>
                                <th class="text-center"><strong>Stage 1</strong></th>
                                <th class="text-center"><strong>Review</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Expiry date</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Exchange</strong></th>
                                <th class="text-center"><strong>Reporting Manager</strong></th>
                                <th class="text-center"><strong>HOD</strong></th>
                                <th class="text-center"><strong>Stage 1</strong></th>
                                <th class="text-center"><strong>Review</strong></th>
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

    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            //begin::Initialize Datatable
            var table = $("#comp_off_credit_requests_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('ajax/backend/user/get-all-comp-off-credit-requests') ?>",
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                },
                "columns": [
                    {
                        data: {
                            _: 'working_date.formatted',
                            sort: 'working_date.ordering',
                        },
                        render: function (data, type, row) {
                            if (type === 'display') {
                                return '<span class="d-block badge text-capitalize rounded-pill text-dark border">'+row.working_day+'</span><span class="d-block">'+data+'</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: {
                            _: 'expiry_date.formatted',
                            sort: 'expiry_date.ordering',
                        },
                        render: function (data, type, row) {
                            if (type === 'display') {
                                return '<span class="d-block badge text-capitalize rounded-pill text-dark border">'+data+'</span>';
                            }
                            return data;
                        }
                    },
                    { 
                        data: {
                            _: 'date_time.formatted',
                            sort: 'date_time.ordering',
                        },
                        render: function (data, type, row, meta){
                            let reason_html = row.reason ? `<p class="d-block text-center ${row.attachment ? 'mb-1' : 'mb-3'}">${row.reason}</p>` : ``;
                            let attachment_html = row.attachment ? `<p class="d-block text-center mb-3"><a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="<?php echo base_url(); ?>${row.attachment}" target="_blank">View Attachment</a></p>` : ``;
                            // let attachment_html = `<p class="d-block text-center mb-3"><a class="badge bg-info bg-opacity-50 fw-normal rounded-pill" href="https://getbootstrap.com/docs/5.0/components/modal/" target="_blank">View Attachment</a></p>`;
                            let assigned_by_name_html = row.assigned_by_name ? `<small class="d-block text-center"><strong>Assigned By:</strong> <strong class="text-danger">${row.assigned_by_name}</strong></small>` : ``;
                            let date_time_html = row.date_time.formatted ? `<small class="d-block text-center">Requested on:<strong class="text-danger"> ${row.date_time.formatted}</strong></small>` : ``;

                            return `<div class="text-wrap mx-auto mb-0 lh-sm" style="width: max-content; max-width: 350px;">${reason_html}${attachment_html}${assigned_by_name_html}${date_time_html}</div>`;
                        }
                    },
                    { data: "status", 
                        render : function(data, type, row, meta) {
                            var badge_class = "bg-secondary text-dark";
                            if( data == 'rejected'){
                                badge_class = "bg-danger text-danger bg-opacity-15";
                            }else if(data == 'approved'){
                                badge_class = "bg-success text-success bg-opacity-15";
                            }else if(data == 'disbursed'){
                                badge_class = "bg-info text-info bg-opacity-15";
                            }
                            return '<span class="badge text-capitalize rounded-pill '+badge_class+'">'+data+'</span>';
                        }
                    },
                    { 
                        data: "exchange",
                        render: function (data, type, row) {
                            let exchange_html = '';
                            if(data == '0.5'){
                                exchange_html = `<p class="mb-1 text-center"><span>Comp Off:</span> <strong class="text-info">Half Day</strong></p>`;
                            }else if(data == '1'){
                                exchange_html = `<p class="mb-1 text-center"><span>Comp Off:</span> <strong class="text-info">Full Day</strong></p>`;
                            }
                            let minutes_html = row.minutes ? `<p class="mb-1 text-center"><span>Extra Minutes:</span> <strong class="text-info">${row.minutes}</strong></p>` : ``;

                            return `<div class="mx-auto text-wrap mb-0 lh-sm" style="width: max-content; max-width: 150px;">${exchange_html}${minutes_html}</div>`;
                        }
                    },
                    { data: "reporting_manager_name" },
                    { data: "hod_name" },
                    { 
                        data: {
                            _: 'stage_1_reviewed_date.formatted',
                            sort: 'stage_1_reviewed_date.ordering',
                        },
                        render: function (data, type, row, meta){
                            let stage_1_remarks_html = row.stage_1_remarks ? `<p class="d-block text-center mb-2">${row.stage_1_remarks}</p>` : ``;
                            let stage_1_reviewed_by_html = row.stage_1_reviewed_by_name ? `<small class="d-block text-center"><strong>Reviewed By:</strong> <strong class="text-danger">${row.stage_1_reviewed_by_name}</strong></small>` : ``;
                            let stage_1_reviewed_date_html = row.stage_1_reviewed_date.formatted ? `<small class="d-block text-center">Reviewed on: <strong class="text-danger">${row.stage_1_reviewed_date.formatted}</strong></small>` : ``;

                            return `<div class="text-wrap mx-auto mb-0 lh-sm" style="width:max-content; max-width: 220px;">${stage_1_remarks_html}${stage_1_reviewed_by_html}${stage_1_reviewed_date_html}</div>`;
                        } 
                    },
                    { 
                        data: {
                            _: 'reviewed_date.formatted',
                            sort: 'reviewed_date.ordering',
                        },
                        render: function (data, type, row, meta){
                            let remarks_html = row.remarks ? `<p class="d-block text-center mb-2">${row.remarks}</p>` : ``;
                            let reviewed_by_html = row.reviewed_by_name ? `<small class="d-block text-center"><strong>Reviewed By:</strong> <strong class="text-danger">${row.reviewed_by_name}</strong></small>` : ``;
                            let reviewed_date_html = row.reviewed_date.formatted ? `<small class="d-block text-center">Reviewed on: <strong class="text-danger">${row.reviewed_date.formatted}</strong></small>` : ``;

                            return `<div class="text-wrap mx-auto mb-0 lh-sm" style="width:max-content; max-width: 220px;">${remarks_html}${reviewed_by_html}${reviewed_date_html}</div>`;
                        } 
                    },
                ],
                "order": [],
                "scrollX": true,
                "scrollY": 'auto',
                "columnDefs": [
                    { "className": 'text-center', "targets": '_all' },
                ],
            });
            //end::Initialize Datatable
        })
    </script>

    <?= $this->endSection() ?>
<?= $this->endSection() ?>