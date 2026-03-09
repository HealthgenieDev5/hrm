<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
    }

    .flatpickr-monthSelect-month.flatpickr-disabled {
        color: #eee !important;
        opacity: 0.35;
    }

    .table-bordered> :not(caption)>* {
        border-width: 1px 0 !important;
    }

    .table-bordered> :not(caption)>*>* {
        border-width: 0 1px !important;
    }

    .table-bordered tbody tr:last-child,
    .table-bordered tfoot tr:last-child {
        border: 1px 0px !important;
        border-bottom-color: inherit !important;
        border-bottom-style: inherit !important;
    }

    .table-bordered tbody tr:last-child td,
    .table-bordered tbody tr:last-child th,
    .table-bordered tfoot tr:last-child td,
    .table-bordered tfoot tr:last-child th {
        border-bottom-width: 1px !important;
        border-bottom-color: inherit !important;
        border-bottom-style: inherit !important;
    }

    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .select2-container--bootstrap5 .select2-selection.loading {
        background: #fff url("<?php echo base_url() . '/assets/media/select2/select2-spinner.gif'; ?>") no-repeat calc(100% - 10px) 50% !important;
    }
</style>

<!--begin::Row-->
<div class="row gy-5 g-xl-8">

    <!--begin::Col-->
    <div class="col-xl-12">
        <!--begin::Mixed Widget 2-->
        <div class="card">
            <!--begin::header-->
            <div class="card-header">
                <h3 class="card-title mb-0">Final Paid Days</h3>
                <form id="filter_form" method="post" class="card-toolbar gap-5" enctype='multipart/form-data'>
                    <div class="d-flex align-items-center gap-3">
                        <label class="form-label mb-0" for="month" class="mb-3">Month</label>
                        <div class="position-relative d-flex align-items-center ">
                            <span class="svg-icon svg-icon-2 position-absolute mx-2" style="z-index:1">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            <!-- <input type="text" id="month" name="month" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select month" value="<?php #echo ( isset($_REQUEST['month']) && !empty($_REQUEST['month']) ) ? date('F Y', strtotime($_REQUEST['month'])) : date('F Y', strtotime(first_date_of_last_month())); 
                                                                                                                                                                            ?>" /> -->
                            <input type="text" id="month" name="month" class="form-control form-control-sm form-control-solid ps-7" placeholder="Select month" value="<?php echo $month; ?>" />
                        </div>
                        <span class="text-danger error-text d-block" id="month_error"></span>
                    </div>
                    <!-- <div class="">
                            <button type="submit" id="filter_form_submit" class="btn btn-sm btn-primary d-inline">
                                <span class="indicator-label">Filter</span>
                                <span class="indicator-progress">
                                    Please wait... 
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div> -->
                </form>
            </div>
            <!--end::header-->
            <!--begin::body-->
            <div class="card-body">
                <?php
                $lastMonthDates = date_range_between(date('Y-m-01', strtotime($month)), date('Y-m-t', strtotime($month)));
                ?>
                <table id="final_paid_days_report" class="table table-custom table-hover nowrap">
                    <thead>
                        <tr>
                            <th class="text-center bg-white"><strong>Column</strong></th>
                            <?php
                            foreach ($lastMonthDates as $date) {
                            ?>
                                <th class="text-center">
                                    <strong class="d-block w-100 border-bottom"><?php echo date('d M', strtotime($date)); ?></strong> <strong class="d-block w-100 <?php echo date('D', strtotime($date)) !== 'Sun' ? 'opacity-50' : 'text-danger'; ?>"><?php echo date('D', strtotime($date)); ?></strong>
                                </th>
                            <?php
                            }
                            ?>
                            <th class="text-center"><strong class="d-block w-100 border-bottom">Total Late Minutes</strong><small>Late Coming+Early Going+Deduction Minutes+INC</small></th>
                            <th class="text-center"><strong class="d-block w-100 border-bottom">Total Late Minutes allowed</strong><small>Grace+Late Sitting+Overtime+CompOff+WaveOff</small></th>
                            <th class="text-center"><strong>Present Days</strong></th>
                            <th class="text-center"><strong>P/2</strong></th>
                            <th class="text-center"><strong>Adjusted Incentive</strong></th>
                            <th class="text-center"><strong>IL (Incentive Leave)</strong><br><small style="font-size:0.65rem">Fixed OFF</small></th>
                            <th class="text-center"><strong>Sundays (WO)</strong></th>
                            <th class="text-center"><strong>OD</strong></th>
                            <th class="text-center"><strong>Compoff</strong></th>
                            <th class="text-center"><strong>Compoff/2</strong></th>
                            <th class="text-center"><strong>Holidays (H)</strong></th>
                            <th class="text-center"><strong>SPL Holidays (SPL HL)</strong></th>
                            <th class="text-center"><strong>EL</strong></th>
                            <th class="text-center"><strong>CL</strong></th>
                            <th class="text-center"><strong>CL/2</strong></th>
                            <th class="text-center"><strong>RH</strong></th>
                            <th class="text-center"><strong>Absent Days</strong><br><small style="font-size:0.65rem">Missed Punch Included</small></th>
                            <th class="text-center"><strong>Absent/2</strong></th>
                            <th class="text-center"><strong>Total Deduction Days</strong></th>
                            <th class="text-center"><strong>Paid Days</strong><br><small>Total</small></th>
                            <th class="text-center"><strong>Total Days</strong><br><small>in <?php echo date('M Y', strtotime($lastMonthDates[0])); ?></small></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center bg-white"><strong>Column</strong></th>
                            <?php
                            foreach ($lastMonthDates as $date) {
                            ?><th class="text-center"><strong><?php echo date('d M', strtotime($date)); ?></strong></th><?php
                                                                                                                    }
                                                                                                                        ?>
                            <th class="text-center"><strong class="d-block w-100 border-bottom">Total Late Minutes</strong><small>Late Coming+Early Going+Deduction Minutes+INC</small></th>
                            <th class="text-center"><strong class="d-block w-100 border-bottom">Total Late Minutes allowed</strong><small>Grace+Late Sitting+Overtime+CompOff+WaveOff</small></th>
                            <th class="text-center"><strong>Present Days</strong></th>
                            <th class="text-center"><strong>P/2</strong></th>
                            <th class="text-center"><strong>Adjusted Incentive</strong></th>
                            <th class="text-center"><strong>IL (Incentive Leave)</strong></th>
                            <th class="text-center"><strong>Sundays (WO)</strong></th>
                            <th class="text-center"><strong>OD</strong></th>
                            <th class="text-center"><strong>Compoff</strong></th>
                            <th class="text-center"><strong>Compoff/2</strong></th>
                            <th class="text-center"><strong>Holidays (H)</strong></th>
                            <th class="text-center"><strong>SPL Holidays (SPL HL)</strong></th>
                            <th class="text-center"><strong>EL</strong></th>
                            <th class="text-center"><strong>CL</strong></th>
                            <th class="text-center"><strong>CL/2</strong></th>
                            <th class="text-center"><strong>RH</strong></th>
                            <th class="text-center"><strong>Absent Days</strong></th>
                            <th class="text-center"><strong>Absent/2</strong></th>
                            <th class="text-center"><strong>Total Deduction Days</strong></th>
                            <th class="text-center"><strong>Paid Days</strong><br><small>Total</small></th>
                            <th class="text-center"><strong>Total Days</strong><br><small>in <?php echo date('M Y', strtotime($lastMonthDates[0])); ?></small></th>
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php
                        if (isset($PreFinalPaidDays_Data) && !empty($PreFinalPaidDays_Data)) {
                        ?>


                            <tr class="row-<?php echo $employee_code; ?>">
                                <td class="text-center">
                                    <strong class="small">Status</strong>
                                </td>
                                <?php
                                foreach ($lastMonthDates as $date) {
                                ?>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <strong class="border-bottom <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0 ? 'text-danger' : ''; ?>">
                                                <?php echo @$PreFinalPaidDays_Data[$date]['status']; ?>
                                            </strong>
                                            <strong><?php echo @$PreFinalPaidDays_Data[$date]['paid']; ?></strong>
                                        </div>
                                    </td>
                                <?php
                                }
                                ?>
                                <!-- late_coming_plus_early_going_minutes -->
                                <td class="text-center">
                                    <?php
                                    $INC_columns_array =    array_filter($PreFinalPaidDays_Data, function ($row) {
                                        return $row['status'] === 'INC';
                                    });
                                    $INC_columns = !empty($INC_columns_array) ? array_column($INC_columns_array, 'late_coming_plus_early_going_minutes_adjustable') : null;
                                    $total_inc_minutes = !empty($INC_columns) ? array_sum($INC_columns) : 0;

                                    $late_coming_minutes = array_sum(array_column($PreFinalPaidDays_Data, 'late_coming_minutes'));
                                    $early_going_minutes = array_sum(array_column($PreFinalPaidDays_Data, 'early_going_minutes'));
                                    $deduction_minutes = array_sum(array_column($PreFinalPaidDays_Data, 'deduction_minutes'));

                                    ?>
                                    <span class="d-block w-100 border-bottom">
                                        <?php echo $late_coming_minutes + $early_going_minutes + $deduction_minutes + $total_inc_minutes; ?>
                                    </span>
                                    <small>
                                        <?php echo $late_coming_minutes . "+" . $early_going_minutes . "+" . $deduction_minutes; ?>
                                        <?php echo ($total_inc_minutes > 0) ? "+" . $total_inc_minutes . " <small style='font-size: 0.75rem'>(INC)</small>" : ""; ?>
                                    </small>
                                </td>
                                <!-- late_coming_grace -->
                                <td class="text-center">
                                    <span class="d-block w-100 border-bottom">
                                        <?php
                                        echo
                                        array_sum(array_column($PreFinalPaidDays_Data, 'late_coming_grace'))
                                            + array_sum(array_column($PreFinalPaidDays_Data, 'LateSittingMinutes'))
                                            + array_sum(array_column($PreFinalPaidDays_Data, 'OverTimeMinutes'))
                                            + array_sum(array_column($PreFinalPaidDays_Data, 'comp_off_minutes'))
                                            + array_sum(array_column($PreFinalPaidDays_Data, 'wave_off_minutes'));
                                        ?>
                                    </span>
                                    <small>
                                        <?php
                                        echo
                                        array_sum(array_column($PreFinalPaidDays_Data, 'late_coming_grace'))
                                            . '+' . array_sum(array_column($PreFinalPaidDays_Data, 'LateSittingMinutes'))
                                            . '+' . array_sum(array_column($PreFinalPaidDays_Data, 'OverTimeMinutes'))
                                            . '+' . array_sum(array_column($PreFinalPaidDays_Data, 'comp_off_minutes'))
                                            . '+' . array_sum(array_column($PreFinalPaidDays_Data, 'wave_off_minutes'));
                                        ?>
                                    </small>
                                </td>
                                <!-- Present days -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_Present = array_filter($status_column, function ($d) {
                                        return $d == 'P';
                                    });
                                    echo count($All_Present);
                                    ?>
                                </td>
                                <!-- P/2 -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_HD =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D';
                                    });
                                    $All_HD_plus_cl_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+CL/2';
                                    });
                                    $All_HD_plus_CompOff_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+COMP OFF/2';
                                    });
                                    $All_HD_plus_ul_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+UL/2';
                                    });
                                    $All_HD_plus_hl_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+HL/2';
                                    });
                                    $All_HD_plus_el_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+EL/2';
                                    });
                                    echo
                                    count($All_HD) +
                                        count($All_HD_plus_cl_by2) +
                                        count($All_HD_plus_CompOff_by2) +
                                        count($All_HD_plus_ul_by2) +
                                        count($All_HD_plus_hl_by2) +
                                        count($All_HD_plus_el_by2);
                                    ?>
                                </td>
                                <!-- Adjusted Incentives -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_Adjusted_Incentives = array_filter($status_column, function ($d) {
                                        return $d == 'INC';
                                    });
                                    echo count($All_Adjusted_Incentives);
                                    ?>
                                </td>
                                <!-- incentive leave -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_IL =  array_filter($status_column, function ($d) {
                                        return $d == 'F/O';
                                    });
                                    echo count($All_IL);
                                    ?>
                                </td>
                                <!-- week off -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_WeekOff =  array_filter($status_column, function ($d) {
                                        return $d == 'W/O';
                                    });
                                    echo count($All_WeekOff);
                                    ?>
                                </td>
                                <!-- OD -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_OD =  array_filter($status_column, function ($d) {
                                        return $d == 'OD';
                                    });
                                    $All_OD_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2';
                                    });
                                    $All_OD_by2_plus_comp_off_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+COMP OFF/2';
                                    });
                                    $All_OD_by2_plus_cl_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+CL/2';
                                    });
                                    $All_OD_by2_plus_ul_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+UL/2';
                                    });
                                    $All_OD_by2_hl_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+HL/2';
                                    });
                                    $All_OD_by2_el_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+EL/2';
                                    });
                                    echo
                                    count($All_OD) +
                                        count($All_OD_by2) / 2 +
                                        count($All_OD_by2_plus_comp_off_by2) / 2 +
                                        count($All_OD_by2_plus_cl_by2) / 2 +
                                        count($All_OD_by2_plus_ul_by2) / 2 +
                                        count($All_OD_by2_hl_by2) / 2 +
                                        count($All_OD_by2_el_by2) / 2;
                                    ?>
                                </td>
                                <!-- COMP OFF -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_CompOff =  array_filter($status_column, function ($d) {
                                        return $d == 'COMP OFF';
                                    });
                                    echo count($All_CompOff);
                                    ?>
                                </td>
                                <!-- COMP OFF/2 -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_CompOff_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'COMP OFF/2';
                                    });
                                    $All_CompOff_by2_plus_hd =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+COMP OFF/2';
                                    });
                                    $All_CompOff_by2_plus_od_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+COMP OFF/2';
                                    });
                                    echo
                                    count($All_CompOff_by2) +
                                        count($All_CompOff_by2_plus_hd) +
                                        count($All_CompOff_by2_plus_od_by2);
                                    ?>
                                </td>
                                <!-- holiday -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_NH =  array_filter($status_column, function ($d) {
                                        return $d == 'NH';
                                    });
                                    $All_HL =  array_filter($status_column, function ($d) {
                                        return $d == 'HL';
                                    });
                                    $All_HL_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'HL/2';
                                    });
                                    $All_HD_plus_hl_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+HL/2';
                                    });
                                    $All_OD_by2_hl_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+HL/2';
                                    });
                                    echo
                                    count($All_NH) +
                                        count($All_HL) +
                                        (count($All_HL_by2) / 2) +
                                        (count($All_HD_plus_hl_by2) / 2) +
                                        (count($All_OD_by2_hl_by2) / 2);
                                    ?>
                                </td>
                                <!-- holiday -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_SPL_HL =  array_filter($status_column, function ($d) {
                                        return $d == 'SPL HL';
                                    });
                                    echo count($All_SPL_HL);
                                    ?>
                                </td>
                                <!-- EL -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_EL =  array_filter($status_column, function ($d) {
                                        return $d == 'EL';
                                    });
                                    $All_HD_el_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+EL/2';
                                    });
                                    $All_OD_by2_el_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+EL/2';
                                    });
                                    echo
                                    count($All_EL) +
                                        (count($All_HD_el_by2) / 2) +
                                        (count($All_OD_by2_el_by2) / 2);
                                    ?>
                                </td>
                                <!-- CL -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_CL =  array_filter($status_column, function ($d) {
                                        return $d == 'CL';
                                    });
                                    echo count($All_CL);
                                    ?>
                                </td>
                                <!-- CL/2 -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_CL_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'CL/2';
                                    });
                                    $All_CL_by2_plus_hd =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+CL/2';
                                    });
                                    $All_CL_by2_plus_od_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+CL/2';
                                    });
                                    echo
                                    count($All_CL_by2) +
                                        count($All_CL_by2_plus_hd) +
                                        count($All_CL_by2_plus_od_by2);
                                    ?>
                                </td>
                                <!-- Total RH -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_RH =  array_filter($status_column, function ($d) {
                                        return $d == 'RH';
                                    });
                                    echo count($All_RH);
                                    ?>
                                </td>
                                <!-- Absent -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_Absent =  array_filter($status_column, function ($d) {
                                        return $d == 'A';
                                    });
                                    $All_MissedPunch =  array_filter($status_column, function ($d) {
                                        return $d == 'M/P';
                                    });
                                    $All_UL =  array_filter($status_column, function ($d) {
                                        return $d == 'UL';
                                    });
                                    $All_UL_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'UL/2';
                                    });
                                    $All_sandwich =  array_filter($status_column, function ($d) {
                                        return $d == 'S/W';
                                    });
                                    $total_absents = count($All_Absent) + count($All_MissedPunch) + count($All_UL) + count($All_UL_by2) + count($All_sandwich);
                                    echo $total_absents;
                                    ?>
                                </td>
                                <!-- Absent/2 -->
                                <td class="text-center">
                                    <?php
                                    $status_column = array_column($PreFinalPaidDays_Data, 'status');
                                    $All_HD =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D';
                                    });
                                    $All_ul_by2_plus_hd =  array_filter($status_column, function ($d) {
                                        return $d == 'H/D+UL/2';
                                    });
                                    $All_ul_by2_plus_od_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2+UL/2';
                                    });
                                    $All_od_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'OD/2';
                                    });
                                    $All_HL_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'HL/2';
                                    });
                                    $All_CL_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'CL/2';
                                    });
                                    $All_COMP_OFF_by2 =  array_filter($status_column, function ($d) {
                                        return $d == 'COMP OFF/2';
                                    });
                                    $total_absent_by2 = count($All_HD) + count($All_ul_by2_plus_hd) + count($All_ul_by2_plus_od_by2) + count($All_HL_by2) + count($All_od_by2) + count($All_CL_by2) + count($All_COMP_OFF_by2);
                                    echo  $total_absent_by2;
                                    ?>
                                </td>
                                <!-- Total Deductions -->
                                <td class="text-center">
                                    <?php
                                    $total_deductions = $total_absents + ($total_absent_by2 / 2);
                                    echo  $total_deductions;
                                    ?>
                                </td>
                                <!-- Total Adjusted paid days -->
                                <td class="text-center">
                                    <?php
                                    $paid_day_column = array_column($PreFinalPaidDays_Data, 'paid');
                                    echo array_sum($paid_day_column);
                                    ?>
                                </td>
                                <!-- Total days in month -->
                                <td class="text-center">
                                    <?php echo count($lastMonthDates); ?>
                                </td>
                            </tr>

                            <tr class="main-row row-<?php echo $employee_code; ?>">
                                <!-- Shift start -->
                                <td class="text-center">
                                    <strong class="small">Shift</strong>
                                </td>
                                <!-- date -->
                                <?php
                                foreach ($lastMonthDates as $date) {
                                ?>
                                    <td class="text-center">
                                        <?php
                                        if (isset($PreFinalPaidDays_Data[$date])) {
                                        ?>
                                            <p class="mb-0 lh-sm small">
                                                <?php echo !empty($PreFinalPaidDays_Data[$date]['shift_start']) ? date('h:i A', strtotime($PreFinalPaidDays_Data[$date]['shift_start'])) : ''; ?>
                                                -
                                                <?php echo !empty($PreFinalPaidDays_Data[$date]['shift_end']) ? date('h:i A', strtotime($PreFinalPaidDays_Data[$date]['shift_end'])) : ''; ?>
                                                <br>
                                                Machine: <?php echo @$PreFinalPaidDays_Data[$date]['machine']; ?>
                                            </p>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                <?php
                                }
                                ?>

                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>

                            <tr class="row-<?php echo $employee_code; ?>">
                                <td class="text-center">
                                    <strong class="small">Punch </strong>
                                    <br>
                                    <small>includes OD</small>
                                </td>
                                <?php
                                foreach ($lastMonthDates as $date) {
                                ?>
                                    <td class="text-center">
                                        <?php
                                        if (isset($PreFinalPaidDays_Data[$date])) {
                                        ?>
                                            <p class="mb-0 lh-sm small">
                                                <?php echo !empty($PreFinalPaidDays_Data[$date]['in_time_including_od']) ? date('h:i A', strtotime($PreFinalPaidDays_Data[$date]['in_time_including_od'])) : ''; ?>
                                                -
                                                <?php echo !empty($PreFinalPaidDays_Data[$date]['out_time_including_od']) ? date('h:i A', strtotime($PreFinalPaidDays_Data[$date]['out_time_including_od'])) : ''; ?>
                                            </p>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                <?php
                                }
                                ?>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>

                            <tr class="row-<?php echo $employee_code; ?>">
                                <td class="text-center">
                                    <strong class="small">Deducted Minutes</strong>
                                </td>
                                <?php
                                foreach ($lastMonthDates as $date) {
                                ?>
                                    <td class="text-center">
                                        <?php
                                        if (isset($PreFinalPaidDays_Data[$date])) {
                                        ?>
                                            <p class="mb-0 lh-sm">
                                                <?php
                                                echo
                                                isset($PreFinalPaidDays_Data[$date]) ?
                                                    $PreFinalPaidDays_Data[$date]['late_coming_minutes'] +
                                                    $PreFinalPaidDays_Data[$date]['early_going_minutes'] +
                                                    $PreFinalPaidDays_Data[$date]['deduction_minutes'] +
                                                    ($PreFinalPaidDays_Data[$date]['status'] == 'INC' ? $PreFinalPaidDays_Data[$date]['late_coming_plus_early_going_minutes_adjustable'] : 0)
                                                    : 0;
                                                ?>
                                            </p>
                                            <p class="mb-1 lh-sm">
                                                <?php
                                                if (
                                                    isset($PreFinalPaidDays_Data[$date]) &&
                                                    (
                                                        $PreFinalPaidDays_Data[$date]['late_coming_minutes'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['early_going_minutes'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['deduction_minutes'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['status'] == 'INC'
                                                    )
                                                ) {
                                                ?>
                                                    <a style="font-size:0.75rem;" data-bs-toggle="collapse" href="#deducted_minutes_<?php echo strtotime($date); ?>" role="button" aria-expanded="false">Show details</a>
                                                <?php
                                                }
                                                ?>
                                            </p>
                                            <div class="collapse" id="deducted_minutes_<?php echo strtotime($date); ?>">
                                                <?php
                                                if (@$PreFinalPaidDays_Data[$date]['late_coming_minutes'] > 0) {
                                                ?>
                                                    <p class="mb-1 lh-sm border-bottom small">
                                                        Late Coming: <?php echo @$PreFinalPaidDays_Data[$date]['late_coming_minutes']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['early_going_minutes'] > 0) {
                                                ?>
                                                    <p class="mb-1 lh-sm border-bottom small">
                                                        Early Going: <?php echo @$PreFinalPaidDays_Data[$date]['early_going_minutes']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['deduction_minutes'] > 0) {
                                                ?>
                                                    <p class="mb-1 lh-sm small">
                                                        Short Leave: <?php echo @$PreFinalPaidDays_Data[$date]['deduction_minutes']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['status'] == 'INC') {
                                                ?>
                                                    <p class="mb-1 lh-sm border-top small">
                                                        INC: <?php echo ($PreFinalPaidDays_Data[$date]['status'] == 'INC') ? $PreFinalPaidDays_Data[$date]['late_coming_plus_early_going_minutes_adjustable'] : 0; ?>
                                                    </p>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                <?php
                                }
                                ?>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>

                            <tr class="row-<?php echo $employee_code; ?>">
                                <td class="text-center">
                                    <strong class="small">Grace</strong>
                                </td>
                                <?php
                                foreach ($lastMonthDates as $date) {
                                ?>
                                    <td class="text-center">
                                        <?php
                                        if (isset($PreFinalPaidDays_Data[$date])) {
                                        ?>
                                            <p class="mb-0 lh-sm">
                                                <?php
                                                echo
                                                isset($PreFinalPaidDays_Data[$date]) ?
                                                    $PreFinalPaidDays_Data[$date]['late_coming_grace'] +
                                                    $PreFinalPaidDays_Data[$date]['comp_off_minutes'] +
                                                    $PreFinalPaidDays_Data[$date]['wave_off_minutes'] +
                                                    $PreFinalPaidDays_Data[$date]['OverTimeMinutes'] +
                                                    ($PreFinalPaidDays_Data[$date]['LateSittingMinutes'] > 0 ? $PreFinalPaidDays_Data[$date]['LateSittingMinutes'] : 0)
                                                    : 0;
                                                ?>
                                            </p>
                                            <p class="mb-1 lh-sm">
                                                <?php
                                                if (
                                                    isset($PreFinalPaidDays_Data[$date]) &&
                                                    (
                                                        $PreFinalPaidDays_Data[$date]['late_coming_grace'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['comp_off_minutes'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['OverTimeMinutes'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['LateSittingMinutes'] > 0
                                                    )
                                                ) {
                                                ?>
                                                    <a style="font-size:0.75rem;" data-bs-toggle="collapse" href="#earned_minutes_<?php echo strtotime($date); ?>" role="button" aria-expanded="false">Show details</a>
                                                <?php
                                                }
                                                ?>
                                            </p>
                                            <div class="collapse" id="earned_minutes_<?php echo strtotime($date); ?>">
                                                <?php
                                                if (@$PreFinalPaidDays_Data[$date]['late_coming_grace'] > 0) {
                                                ?>
                                                    <p class="mb-1 lh-sm border-bottom small">
                                                        Daily Grace: <?php echo @$PreFinalPaidDays_Data[$date]['late_coming_grace']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['comp_off_minutes'] > 0) {
                                                ?>
                                                    <p class="mb-1 lh-sm border-bottom small">
                                                        Comp off Minutes: <?php echo @$PreFinalPaidDays_Data[$date]['comp_off_minutes']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0) {
                                                ?>
                                                    <p class="mb-1 lh-sm border-bottom small">
                                                        Wave off Minutes: <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_minutes']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['OverTimeMinutes'] > 0) {
                                                ?>
                                                    <p class="mb-1 lh-sm border-bottom small">
                                                        Overtime Minutes: <?php echo @$PreFinalPaidDays_Data[$date]['OverTimeMinutes']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['LateSittingMinutes'] > 0) {
                                                ?>
                                                    <p class="mb-1 lh-sm border-bottom small">
                                                        Late Sitting Minutes: <?php echo @$PreFinalPaidDays_Data[$date]['LateSittingMinutes']; ?>
                                                    </p>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                <?php
                                }
                                ?>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>

                            <tr class="row-<?php echo $employee_code; ?>">
                                <td class="text-center">
                                    <strong class="small">Remarks</strong>
                                </td>
                                <?php
                                foreach ($lastMonthDates as $date) {
                                ?>
                                    <td class="text-center">
                                        <?php
                                        if (isset($PreFinalPaidDays_Data[$date])) {
                                        ?>

                                            <p class="mx-auto text-wrap mb-0 lh-sm small" style="width: max-content; max-width: 150px;">
                                                <?php echo @$PreFinalPaidDays_Data[$date]['status_remarks']; ?>
                                            </p>
                                            <p class="mx-auto mb-1 lh-sm">
                                                <?php
                                                if (
                                                    isset($PreFinalPaidDays_Data[$date]) &&
                                                    (
                                                        $PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['deduction_minutes'] > 0
                                                        || $PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day'] == 'yes'
                                                        || !empty($PreFinalPaidDays_Data[$date]['leave_request_status'])
                                                    )
                                                ) {
                                                ?>
                                                    <a style="font-size:0.75rem;" data-bs-toggle="collapse" href="#addition_remarks_<?php echo strtotime($date); ?>" role="button" aria-expanded="false">Additional remarks</a>
                                                <?php
                                                }
                                                ?>
                                            </p>
                                            <div class="collapse" id="addition_remarks_<?php echo strtotime($date); ?>">
                                                <?php
                                                if (@$PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0) {
                                                ?>
                                                    <p class="mx-auto text-wrap mb-1 lh-sm text-danger border-bottom small" style="width: max-content; max-width: 150px;">
                                                        <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_remarks']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['deduction_minutes'] > 0) {
                                                ?>
                                                    <p class="mx-auto text-wrap mb-0 lh-sm text-danger border-bottom small" style="width: max-content; max-width: 150px;">
                                                        <?php echo @$PreFinalPaidDays_Data[$date]['deduction_remarks']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (@$PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day'] == 'yes') {
                                                ?>
                                                    <p class="mx-auto text-wrap mb-0 lh-sm text-danger border-bottom small" style="width: max-content; max-width: 150px;">
                                                        <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day_remarks']; ?>
                                                    </p>
                                                <?php
                                                }
                                                if (!empty(@$PreFinalPaidDays_Data[$date]['leave_request_status'])) {
                                                ?>
                                                    <p class="mx-auto text-wrap mb-0 lh-sm border-bottom small" style="width: max-content; max-width: 150px;">
                                                        <?php echo @$PreFinalPaidDays_Data[$date]['leave_request_type']; ?>: <?php echo @$PreFinalPaidDays_Data[$date]['leave_request_status']; ?>
                                                    </p>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                <?php
                                }
                                ?>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>


                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!--end::body-->
            <!--begin::footer-->
            <div class="card-footer d-flex justify-content-end">
                <?php
                if ($salary_slip_downloadable == 'yes') {
                ?>
                    <a
                        href="<?php
                                echo base_url("/backend/reports/salary/final-salary/salary-slip/")
                                    . '/' . session()->get('current_user')['employee_id']
                                    . '/' . date('Y', strtotime($month))
                                    . '/' . date('F', strtotime($month)); ?>"
                        target="_blank"
                        class="ms-auto btn btn-sm btn-primary mb-2">
                        Download Salary Slip
                    </a>
                <?php
                } else {
                ?>
                    <a
                        href="#"
                        class="ms-auto btn btn-sm btn-warning mb-2">
                        Salary Slip Not Available
                    </a>
                <?php
                }
                ?>
            </div>
            <!--end::footer-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Mixed Widget 2-->

</div>
<!--end::Row-->

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $("#month").flatpickr({
            altInput: true,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "F Y",
                    altFormat: "F Y",
                    theme: "dark"
                })
            ],
            maxDate: "<?php echo first_date_of_last_month(); ?>",
            onChange: function(selectedDates, dateStr, instance) {
                $('#filter_form').submit();
            }
        });

        /*begin::final_paid_days_report*/
        var final_paid_days_report = $("#final_paid_days_report").DataTable({
            // "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B><"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            "dom": 't',
            "buttons": [
                // 'excelHtml5',
            ],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "deferRender": true,
            "processing": true,
            "language": {
                processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
                emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
                searchPlaceholder: "Search"
            },
            // "oLanguage": { "sSearch": "" },
            "order": [],
            "scrollX": true,
            "scrollY": '500px',
            "scrollCollapse": true,
            "paging": false,
            "columnDefs": [{
                    "className": 'border-end border-secondary td-border-left text-center',
                    "targets": [0]
                },
                {
                    "className": 'border-start border-secondary td-border-left text-center',
                    "targets": [-2]
                },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
            ],
            "fixedColumns": {
                left: 1,
                right: 2,
                // top: 1
            },
        });
        $('#final_paid_days_report_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Final Paid Days</h3>');
        $('#final_paid_days_report_wrapper > .card > .card-header > .card-toolbar ').append(``);
        /*end::final_paid_days_report*/
    })
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>