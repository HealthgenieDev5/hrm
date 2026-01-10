<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">

            <div class="card shadow-sm mb-5">

                <div class="card-body">
                    <table id="leave_approval_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>Employee ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center bg-white"><strong>Employee Code</strong></th>
                                <th class="text-center bg-white"><strong>Company</strong></th>
                                <th class="text-center bg-white"><strong>Department</strong></th>
                                <th class="text-center"><strong>EL</strong></th>
                                <th class="text-center"><strong>CL</strong></th>
                                <th class="text-center"><strong>RH</strong></th>
                                <th class="text-center"><strong>COMP OFF</strong></th>
                                <th class="text-center"><strong>COMP OFF Minutes</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>Employee ID</strong></th>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center bg-white"><strong>Employee Code</strong></th>
                                <th class="text-center bg-white"><strong>Company</strong></th>
                                <th class="text-center bg-white"><strong>Department</strong></th>
                                <th class="text-center"><strong>EL</strong></th>
                                <th class="text-center"><strong>CL</strong></th>
                                <th class="text-center"><strong>RH</strong></th>
                                <th class="text-center"><strong>COMP OFF</strong></th>
                                <th class="text-center"><strong>COMP OFF Minutes</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach( $employees as $employee_row){
                            ?>
                                <tr>
                                    <td class="text-center bg-white">
                                        <?php echo $employee_row['id']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $employee_row['employee_name']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $employee_row['internal_employee_id']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $employee_row['company_short_name']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $employee_row['department_name']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        foreach( $employee_row['leave_balance'] as $balance_row ){
                                            if( $balance_row['leave_code'] == 'EL' ){
                                                echo $balance_row['balance'];
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        foreach( $employee_row['leave_balance'] as $balance_row ){
                                            if( $balance_row['leave_code'] == 'CL' ){
                                                echo $balance_row['balance'];
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        foreach( $employee_row['leave_balance'] as $balance_row ){
                                            if( $balance_row['leave_code'] == 'RH' ){
                                                echo $balance_row['balance'];
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        foreach( $employee_row['leave_balance'] as $balance_row ){
                                            if( $balance_row['leave_code'] == 'COMP OFF' ){
                                                echo $balance_row['balance'];
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        foreach( $employee_row['leave_balance'] as $balance_row ){
                                            if( $balance_row['leave_code'] == 'COMP OFF Minutes' ){
                                                echo $balance_row['balance'];
                                            }
                                        }
                                        ?>
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


            var table = $("#leave_approval_table").DataTable({
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