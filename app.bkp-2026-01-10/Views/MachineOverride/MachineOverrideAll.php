<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">

            <div class="card shadow-sm mb-5">

                <div class="card-body">
                    <table id="wave_off_minutes_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>Employee ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center bg-white"><strong>Employee Code</strong></th>
                                <th class="text-center bg-white"><strong>Company</strong></th>
                                <th class="text-center bg-white"><strong>Department</strong></th>
                                <th class="text-center"><strong>From Date</strong></th>
                                <th class="text-center"><strong>To Date</strong></th>
                                <th class="text-center"><strong>Machine</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>Employee ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center bg-white"><strong>Employee Code</strong></th>
                                <th class="text-center bg-white"><strong>Company</strong></th>
                                <th class="text-center bg-white"><strong>Department</strong></th>
                                <th class="text-center"><strong>From Date</strong></th>
                                <th class="text-center"><strong>To Date</strong></th>
                                <th class="text-center"><strong>Machine</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach( $allMachineOverrideEntries as $MachineOverrideEntry){
                            ?>
                                <tr>
                                    <td class="text-center bg-white">
                                        <?php echo $MachineOverrideEntry['employee_id']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $MachineOverrideEntry['employee_name']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $MachineOverrideEntry['internal_employee_id']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $MachineOverrideEntry['company_short_name']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $MachineOverrideEntry['department_name']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo date('d M, Y', strtotime($MachineOverrideEntry['from_date'])); ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo date('d M, Y', strtotime($MachineOverrideEntry['to_date'])); ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $MachineOverrideEntry['machine']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small><?php echo $MachineOverrideEntry['remarks']; ?></small></p>
                                    </td>
                                    <td class="text-center bg-white">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- <button type="button" class="btn btn-sm btn-info">Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger">Delete</button> -->
                                            <button type="button" class="btn btn-sm btn-default">Coming Soon</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <?= $this->section('javascript') ?>

    <script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript">

        jQuery(document).ready(function($){


            var table = $("#wave_off_minutes_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rtl',
                "paging": false,
                "scrollX": true,
                "scrollY": '400px',
                "scrollCollapse": true,
                "order": [],
                "buttons": ['excel'],
                "columnDefs": [
                    { "className": 'text-center', "targets": '_all' },
                ],
            }); 
           
        })
    </script>
    
    <?= $this->endSection() ?>
<?= $this->endSection() ?>