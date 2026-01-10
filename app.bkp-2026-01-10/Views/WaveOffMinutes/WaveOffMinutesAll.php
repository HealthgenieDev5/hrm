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
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>Employee ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center bg-white"><strong>Employee Code</strong></th>
                                <th class="text-center bg-white"><strong>Company</strong></th>
                                <th class="text-center bg-white"><strong>Department</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach( $allWaveOffMinutesEntries as $WaveOffMinutesEntry){
                            ?>
                                <tr>
                                    <td class="text-center bg-white">
                                        <?php echo $WaveOffMinutesEntry['employee_id']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $WaveOffMinutesEntry['employee_name']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $WaveOffMinutesEntry['internal_employee_id']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $WaveOffMinutesEntry['company_short_name']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $WaveOffMinutesEntry['department_name']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo date('d M, Y', strtotime($WaveOffMinutesEntry['date'])); ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $WaveOffMinutesEntry['minutes']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px"><small><?php echo $WaveOffMinutesEntry['remarks']; ?></small></p>
                                        
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