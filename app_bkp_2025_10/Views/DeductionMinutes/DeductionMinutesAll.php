<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">

            <div class="card shadow-sm mb-5">

                <div class="card-body">
                    <table id="deduction_minutes_table" class="table table-sm table-hover table-striped table-row-bordered nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Deducted by</strong></th>
                                <th class="text-center"><strong>Current Status</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Reviewed by</strong></th>
                                <th class="text-center"><strong>Reviewer's Remarks</strong></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center bg-white"><strong>Employee Name</strong></th>
                                <th class="text-center"><strong>Date</strong></th>
                                <th class="text-center"><strong>Minutes</strong></th>
                                <th class="text-center"><strong>Deducted by</strong></th>
                                <th class="text-center"><strong>Current Status</strong></th>
                                <th class="text-center"><strong>Remarks</strong></th>
                                <th class="text-center"><strong>Reviewed by</strong></th>
                                <th class="text-center"><strong>Reviewer's Remarks</strong></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach( $allDeductionMinutesEntries as $DeductionMinutesEntry){
                            ?>
                                <tr>
                                    <td class="text-center bg-white">
                                        <span class="d-block border-bottom">
                                            <?php echo $DeductionMinutesEntry['employee_name']." (".$DeductionMinutesEntry['internal_employee_id'].")"; ?>
                                        </span>
                                        <small>
                                            <?php echo $DeductionMinutesEntry['department_name']." - ".$DeductionMinutesEntry['company_short_name']; ?>
                                        </small>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo date('d M, Y', strtotime($DeductionMinutesEntry['date'])); ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo $DeductionMinutesEntry['minutes']; ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo ucwords($DeductionMinutesEntry['deducted_by_name']); ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php
                                        switch ( $DeductionMinutesEntry['current_status'] ) {
                                            case 'pending':
                                                ?>
                                                <span class="badge text-capitalize rounded-pill bg-transparent text-dark border border-dashed border-dark"><?= $DeductionMinutesEntry['current_status'] ?></span>
                                                <?php
                                                break;

                                            case 'approved':
                                                ?>
                                                <span class="badge text-capitalize rounded-pill bg-success text-white"><?= $DeductionMinutesEntry['current_status'] ?></span>
                                                <?php
                                                break;

                                            case 'rejected':
                                                ?>
                                                <span class="badge text-capitalize rounded-pill bg-danger text-white opacity-50"><?= $DeductionMinutesEntry['current_status'] ?></span>
                                                <?php
                                                break;
                                            
                                            default:
                                                // code...
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                            <small class="d-block text-start"><strong>By</strong> <strong class="text-danger"><?php echo $DeductionMinutesEntry['deducted_by_name']; ?></strong></small>
                                            <small class="d-block text-start mb-2">on <strong class="text-danger"><?php echo date('d M, Y h:i A', strtotime($DeductionMinutesEntry['date_time'])); ?></strong></small>
                                            <?php
                                            if( !empty($DeductionMinutesEntry['attachment']) ){
                                                ?>
                                                <small class="d-flex justify-content-start mb-2">
                                                    <a class="d-block" href="<?php echo base_url().$DeductionMinutesEntry['attachment']; ?>" target="_blank">
                                                        <img src="<?php echo base_url().$DeductionMinutesEntry['attachment']; ?>" class="w-100" style="object-fit: contain; max-height:100px;" />
                                                    </a>
                                                </small>
                                                <?php
                                            }
                                            ?>
                                            <small class="d-block text-start fst-italic"><?php echo $DeductionMinutesEntry['initial_remarks']; ?></small>
                                        </p>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php echo ucwords($DeductionMinutesEntry['reviewed_by_name']); ?>
                                    </td>
                                    <td class="text-center bg-white">
                                        <?php
                                        if( !empty($DeductionMinutesEntry['reviewed_by_name']) && !empty($DeductionMinutesEntry['reviewed_date']) && !empty($DeductionMinutesEntry['reviewer_remarks']) ){
                                            ?>
                                            <p class="text-wrap mx-auto mb-0 lh-sm" style="width: 150px">
                                                <small class="d-block text-start"><strong>By</strong> <strong class="text-danger"><?php echo $DeductionMinutesEntry['reviewed_by_name']; ?></strong></small>
                                                <small class="d-block text-start mb-2">on <strong class="text-danger"><?php echo date('d M, Y h:i A', strtotime($DeductionMinutesEntry['reviewed_date'])); ?></strong></small>
                                                <small class="d-block text-start fst-italic"><?php echo $DeductionMinutesEntry['reviewer_remarks']; ?></small>
                                            </p>
                                            <?php
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


            var table = $("#deduction_minutes_table").DataTable({
                "dom": '<"mb-3 d-flex align-items-center justify-content-between datatable-top-div"<"ml-2"B><"flex-grow-1"f>>rtl',
                "paging": false,
                "scrollX": true,
                "scrollY": '600px',
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