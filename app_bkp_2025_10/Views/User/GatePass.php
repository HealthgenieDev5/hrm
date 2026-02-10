<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Gate Pass Requests</h3>
                    <div class="card-toolbar">
                        
                    </div>
                </div>
                <div class="card-body">
                    <table id="gate_pass_requests_table" class="table table-striped nowrap">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Time</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Reporting Manager</strong></th>
                                <th class="text-center"><strong>HOD</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Requested Date Time</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><strong>Type</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Time</strong></th>
                                <th class="text-center"><strong>Reason</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Reporting Manager</strong></th>
                                <th class="text-center"><strong>HOD</strong></th>
                                <th class="text-center"><strong>Reviewed By</strong></th>
                                <th class="text-center"><strong>Reviewed Date</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Requested Date Time</strong></th>
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
            var table = $("#gate_pass_requests_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rt',
                "buttons": [],
                "ajax": {
                    url:  "<?= base_url('ajax/get-all-gate-pass-requests') ?>",
                    dataSrc: "",
                },
                "deferRender": true,
                "processing": true,
                "language": {
                    processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                    emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                },
                "columns": [
                    { data: "gate_pass_type" },
                    {
                        data: {
                            _: 'gate_pass_date.formatted',
                            sort: 'gate_pass_date.ordering',
                        }
                    },
                    { data: "gate_pass_hours" },
                    { data: "reason" },
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
                    { data: "reporting_manager_name" },
                    { data: "hod_name" },
                    { data: "reviewed_by_name" },
                    {
                        data: {
                            _: 'reviewed_date.formatted',
                            sort: 'reviewed_date.ordering',
                        }
                    },
                    { data: "remarks" },
                    {
                        data: {
                            _: 'date_time.formatted',
                            sort: 'date_time.ordering',
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