<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<style>
    .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    .flatpickr-monthSelect-month {
        width: calc(33% - 0.5px);
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

    .dataTables_filter input {
        border: none !important;
    }
</style>

<!--begin::Row-->
<div class="row gy-5 g-xl-8">

    <?php
    if (!empty(session()->get('current_user')['role']) && !in_array(session()->get('current_user')['role'], ['user'])) {
    ?>

        <!--begin::Col-->
        <div class="col-xl-12">
            <!--begin::Mixed Widget 2-->
            <div class="card">
                <!--begin::Body-->
                <div class="card-body">
                    <form id="filter_form" class="row gy-5 g-xl-8" enctype='multipart/form-data'>
                        <div class="col-lg-8">
                            <div class="row gy-5 g-xl-8">
                                <div class="col-lg-4">
                                    <label class="form-label" for="company" class="mb-3">Company</label>
                                    <!-- <select class="form-select form-select-sm" id="company" name="company[]" multiple data-placeholder="Select a Company" > -->
                                    <select class="form-select form-select-sm" id="company" name="company[]" multiple data-control="select2" data-placeholder="Select a Company">
                                        <option value=""></option>
                                        <option value="all_companies" <?php echo (isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array('all_companies', $_REQUEST['company'])) ? 'selected' : ''; ?>>All Companies</option>
                                        <?php
                                        if (isset($Companies) && !empty($Companies)) {
                                            foreach ($Companies as $company_row) {
                                        ?>
                                                <option value="<?php echo $company_row['id']; ?>" <?php echo (isset($_REQUEST['company']) && !empty($_REQUEST['company']) && in_array($company_row['id'], $_REQUEST['company']) && !in_array('all_companies', $_REQUEST['company'])) ? 'selected' : ''; ?>><?php echo $company_row['company_name']; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <br>
                                    <small class="text-danger error-text" id="company_error"></small>
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label" for="department" class="mb-3">Department</label>
                                    <select class="form-select form-select-sm" id="department" name="department[]" multiple data-control="select2" data-placeholder="Select a Department">
                                        <option value=""></option>
                                        <option value="all_departments" <?php echo (isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array('all_departments', $_REQUEST['department'])) ? 'selected' : ''; ?>>All Departments</option>
                                        <?php
                                        if (isset($Departments) && !empty($Departments)) {
                                            foreach ($Departments as $department_row) {
                                        ?>
                                                <option value="<?php echo $department_row['id']; ?>" <?php echo (isset($_REQUEST['department']) && !empty($_REQUEST['department']) && in_array($department_row['id'], $_REQUEST['department']) && !in_array('all_departments', $_REQUEST['department'])) ? 'selected' : ''; ?>><?php echo $department_row['department_name'] . ' - ' . $department_row['company_short_name']; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <br>
                                    <small class="text-danger error-text" id="department_error"></small>
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label" for="employee" class="mb-3">Employee</label>
                                    <select class="form-select form-select-sm" id="employee" name="employee[]" multiple data-control="select2" data-placeholder="Select an Employee">
                                        <option value=""></option>
                                        <option value="all_employees" <?php echo (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array('all_employees', $_REQUEST['employee'])) ? 'selected' : ''; ?>>All Employees</option>
                                        <?php
                                        if (isset($Employees) && !empty($Employees)) {
                                            foreach ($Employees as $employee_row) {
                                        ?>
                                                <option value="<?php echo $employee_row['id']; ?>" <?php echo (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array($employee_row['id'], $_REQUEST['employee']) && !in_array('all_employees', $_REQUEST['employee'])) ? 'selected' : ''; ?>><?php echo $employee_row['employee_name'] . '(' . $employee_row['internal_employee_id'] . ') - ' . $employee_row['department_name'] . ' -' . $employee_row['company_short_name']; ?><?php echo $employee_row['status'] != 'active' ? ' --' . $employee_row['status'] : ''; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <small class="text-danger error-text" id="employee_error"></small>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center justify-content-center" style="gap: 2rem;">
                            <div>
                                <label class="form-label"> &nbsp; </label><br>
                                <button type="submit" id="filter_form_submit" class="btn btn-sm btn-primary d-inline">
                                    <span class="indicator-label">Filter</span>
                                    <span class="indicator-progress">
                                        Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                            <div>
                                <label class="form-label"> &nbsp; </label><br>
                                <?php
                                $internal_employee_ids_to_refresh = [];
                                if (isset($Employees) && !empty($Employees)) {
                                    foreach ($Employees as $employee_row) {
                                        if (isset($_REQUEST['employee']) && !empty($_REQUEST['employee']) && in_array($employee_row['id'], $_REQUEST['employee']) && !in_array('all_employees', $_REQUEST['employee'])) {
                                            $internal_employee_ids_to_refresh[] = $employee_row['internal_employee_id'];
                                        }
                                    }
                                }
                                ?>
                                <button type="button" id="refresh_current_month_attendance" class="btn btn-sm btn-warning d-inline" data-internal_employee_id="<?php echo implode(',', $internal_employee_ids_to_refresh); ?>">
                                    <span class="indicator-label">Refresh</span>
                                    <span class="indicator-progress">
                                        Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Mixed Widget 2-->


    <?php
    }
    ?>

    <!--begin::Col-->
    <div class="col-12 table-responsive mt-3">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Data is heavy, Please wait untill your browser loads all data</strong> Browser can be freezed.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <?php $lastMonthDates = date_range_between(date('Y-m-01', strtotime($month)), date('Y-m-t', strtotime($month))); ?>
        <table id="final_paid_days_report" class="table table-custom table-hover nowrap">
            <thead>
                <tr>
                    <th class="text-center bg-white"><strong>Code</strong></th>
                    <th class="text-center bg-white"><strong>Name</strong></th>
                    <th class="text-center bg-white"><strong>Company</strong></th>
                    <th class="text-center bg-white"><strong>Column</strong></th>
                    <?php
                    #$lastMonthDates = date_range_between( date('Y-m-01'), date('Y-m-t') );
                    #$lastMonthDates = date_range_between( first_date_of_last_month(), last_date_of_last_month() );
                    foreach ($lastMonthDates as $date) {
                    ?>
                        <th class="text-center">
                            <strong class="d-block w-100 border-bottom"><?php echo date('d M', strtotime($date)); ?></strong> <strong class="d-block w-100 <?php echo date('D', strtotime($date)) !== 'Sun' ? 'opacity-50' : 'text-danger'; ?>"><?php echo date('D', strtotime($date)); ?></strong>
                        </th>
                    <?php
                    }
                    ?>
                    <th class="text-center"><strong class="d-block w-100 border-bottom">Total Late Minutes</strong><small>Late Coming+Early Going+Deduction Minutes+INC</small><br><small style="font-size:0.5rem;">Excluding Today's Missed Punch</small></th>
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
                    <th class="text-center"><strong>Absent Days</strong><br><small style="font-size:0.65rem">Missed Punch Included</small></th>
                    <th class="text-center"><strong>Absent/2</strong></th>
                    <th class="text-center"><strong>Total Deduction Days</strong></th>
                    <th class="text-center"><strong>Total Adjusted paid Days</strong></th>
                    <th class="text-center"><strong>Total Days in Month</strong></th>
                    <th class="text-center"><strong>search_names</strong></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="text-center bg-white"><strong>Code</strong></th>
                    <th class="text-center bg-white"><strong>Name</strong></th>
                    <th class="text-center bg-white"><strong>Company</strong></th>
                    <th class="text-center bg-white"><strong>Column</strong></th>
                    <?php
                    foreach ($lastMonthDates as $date) {
                    ?><th class="text-center"><strong><?php echo date('d M', strtotime($date)); ?></strong></th><?php
                                                                                                            }
                                                                                                                ?>
                    <th class="text-center"><strong class="d-block w-100 border-bottom">Total Late Minutes</strong><small>Late Coming+Early Going+Deduction Minutes+INC</small><br><small>Excluding Today's Missed Punch</small></th>
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
                    <th class="text-center"><strong>Total Adjusted paid Days</strong></th>
                    <th class="text-center"><strong>Total Days in Month</strong></th>
                    <th class="text-center"><strong>search_names</strong></th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                foreach ($ATTENDANCEData as $key => $data) {
                    $employee_name = $data['employee_name'];
                    $employee_code = $data['employee_code'];
                    $company_short_name = $data['company_short_name'];
                    $PreFinalPaidDays_Data = $data['PreFinalPaidDays_Data'];
                    #$dates = $data['dates'];
                ?>
                    <tr>

                        <!-- Code -->
                        <td class="text-center"><strong><?php echo $employee_code; ?></strong></td>
                        <!-- Name -->
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                        <!-- Company -->
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $company_short_name; ?></strong></p>
                        </td>
                        <!-- Shift start -->
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Shift start</strong></p>
                        </td>
                        <!-- date -->
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php #echo !empty($PreFinalPaidDays_Data[$date]['shift_start']) ? date('h:i A', strtotime($PreFinalPaidDays_Data[$date]['shift_start'])) : ''; 
                                ?>
                                <?php echo !empty($PreFinalPaidDays_Data[$date]['shift_start']) ? date('H:i', strtotime($PreFinalPaidDays_Data[$date]['shift_start'])) : ''; ?>
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
                            <?php
                            $late_coming_grace = array_sum(array_column($PreFinalPaidDays_Data, 'late_coming_grace'));
                            $LateSittingMinutes = array_sum(array_column($PreFinalPaidDays_Data, 'LateSittingMinutes'));
                            $OverTimeMinutes = array_sum(array_column($PreFinalPaidDays_Data, 'OverTimeMinutes'));
                            $comp_off_minutes = array_sum(array_column($PreFinalPaidDays_Data, 'comp_off_minutes'));
                            $wave_off_minutes = array_sum(array_column($PreFinalPaidDays_Data, 'wave_off_minutes'));
                            $deduction_minutes = array_sum(array_column($PreFinalPaidDays_Data, 'deduction_minutes'));
                            ?>
                            <span class="d-block w-100 border-bottom">
                                <?php echo $late_coming_grace + $LateSittingMinutes + $OverTimeMinutes + $comp_off_minutes + $wave_off_minutes; ?>
                            </span>
                            <small><?php echo $late_coming_grace . '+' . $LateSittingMinutes . '+' . $OverTimeMinutes . '+' . $comp_off_minutes . '+' . $wave_off_minutes; ?></small>
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
                            $All_HD = array_filter($status_column, function ($d) {
                                return $d == 'H/D';
                            });
                            $All_HD_plus_cl_by2 = array_filter($status_column, function ($d) {
                                return $d == 'H/D+CL/2';
                            });
                            $All_HD_plus_CompOff_by2 = array_filter($status_column, function ($d) {
                                return $d == 'H/D+COMP OFF/2';
                            });
                            $All_HD_plus_ul_by2 = array_filter($status_column, function ($d) {
                                return $d == 'H/D+UL/2';
                            });
                            $All_HD_plus_hl_by2 = array_filter($status_column, function ($d) {
                                return $d == 'H/D+HL/2';
                            });
                            echo count($All_HD) + count($All_HD_plus_cl_by2) + count($All_HD_plus_CompOff_by2) + count($All_HD_plus_ul_by2) + count($All_HD_plus_hl_by2);
                            ?>
                        </td>
                        <!-- Adjusted Incentives -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_Adjusted_Incentives = array_filter($status_column, function ($d) {
                                return $d == 'INC';
                            });
                            $All_ML = array_filter($status_column, function ($d) {
                                return $d == 'ML';
                            });
                            echo count($All_Adjusted_Incentives) + count($All_ML);
                            ?>
                        </td>
                        <!-- incentive leave -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_IL = array_filter($status_column, function ($d) {
                                return $d == 'F/O';
                            });
                            echo count($All_IL);
                            ?>
                        </td>
                        <!-- week off -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_WeekOff = array_filter($status_column, function ($d) {
                                return $d == 'W/O';
                            });
                            echo count($All_WeekOff);
                            ?>
                        </td>
                        <!-- OD -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_OD = array_filter($status_column, function ($d) {
                                return $d == 'OD';
                            });
                            $All_OD_by2 = array_filter($status_column, function ($d) {
                                return $d == 'OD/2';
                            });
                            $All_OD_by2_plus_comp_off_by2 = array_filter($status_column, function ($d) {
                                return $d == 'OD/2+COMP OFF/2';
                            });
                            $All_OD_by2_plus_cl_by2 = array_filter($status_column, function ($d) {
                                return $d == 'OD/2+CL/2';
                            });
                            $All_OD_by2_plus_ul_by2 = array_filter($status_column, function ($d) {
                                return $d == 'OD/2+UL/2';
                            });
                            echo count($All_OD) + count($All_OD_by2) / 2 + count($All_OD_by2_plus_comp_off_by2) / 2 + count($All_OD_by2_plus_cl_by2) / 2 + count($All_OD_by2_plus_ul_by2) / 2;
                            ?>
                        </td>
                        <!-- COMP OFF -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_CompOff =  array_filter($status_column, function ($d) {
                                return $d == 'COMP OFF';
                            });
                            /*
                            $All_CompOff_by2 =  array_filter($status_column, function($d) { return $d == 'COMP OFF/2'; });
                            $All_CompOff_by2_plus_hd =  array_filter($status_column, function($d) { return $d == 'H/D+COMP OFF/2'; });
                            $All_CompOff_by2_plus_od_by2 =  array_filter($status_column, function($d) { return $d == 'OD/2+COMP OFF/2'; });
                            echo count($All_CompOff) + count($All_CompOff_by2)/2 + count($All_CompOff_by2_plus_hd)/2 + count($All_CompOff_by2_plus_od_by2)/2;
                            */
                            echo count($All_CompOff);
                            ?>
                        </td>
                        <!-- COMP OFF/2 -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            /*$All_CompOff = array_filter($status_column, function($d) { return $d == 'COMP OFF'; });*/
                            $All_CompOff_by2 = array_filter($status_column, function ($d) {
                                return $d == 'COMP OFF/2';
                            });
                            $All_CompOff_by2_plus_hd = array_filter($status_column, function ($d) {
                                return $d == 'H/D+COMP OFF/2';
                            });
                            $All_CompOff_by2_plus_od_by2 = array_filter($status_column, function ($d) {
                                return $d == 'OD/2+COMP OFF/2';
                            });
                            echo count($All_CompOff_by2) + count($All_CompOff_by2_plus_hd) + count($All_CompOff_by2_plus_od_by2);
                            ?>
                        </td>
                        <!-- holiday -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_NH = array_filter($status_column, function ($d) {
                                return $d == 'NH';
                            });
                            $All_HL = array_filter($status_column, function ($d) {
                                return $d == 'HL';
                            });
                            $All_HL_by2 = array_filter($status_column, function ($d) {
                                return $d == 'HL/2';
                            });
                            $All_HD_plus_hl_by2 = array_filter($status_column, function ($d) {
                                return $d == 'H/D+HL/2';
                            });
                            echo count($All_NH) + count($All_HL) + (count($All_HL_by2) / 2) + (count($All_HD_plus_hl_by2) / 2);
                            ?>
                        </td>
                        <!-- holiday -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_SPL_HL = array_filter($status_column, function ($d) {
                                return $d == 'SPL HL';
                            });
                            echo count($All_SPL_HL);
                            ?>
                        </td>
                        <!-- Total EL -->
                        <td class="text-center">
                            <?php
                            // $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            // $All_EL =  array_filter($status_column, function($d) { return $d == 'EL'; });
                            // echo count($All_EL);
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_EL =  array_filter($status_column, function ($d) {
                                return $d == 'EL';
                            });
                            $All_el_by2 =  array_filter($status_column, function ($d) {
                                return $d == 'EL/2';
                            });
                            $All_HD_el_by2 =  array_filter($status_column, function ($d) {
                                return $d == 'H/D+EL/2';
                            });
                            $All_OD_by2_el_by2 =  array_filter($status_column, function ($d) {
                                return $d == 'OD/2+EL/2';
                            });
                            echo count($All_EL) + (count($All_HD_el_by2) / 2) + (count($All_OD_by2_el_by2) / 2) + (count($All_el_by2) / 2);
                            ?>
                        </td>
                        <!-- CL -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_CL =  array_filter($status_column, function ($d) {
                                return $d == 'CL';
                            });
                            /*$All_CL_by2 =  array_filter($status_column, function($d) { return $d == 'CL/2'; });
                            $All_CL_by2_plus_hd =  array_filter($status_column, function($d) { return $d == 'H/D+CL/2'; });
                            $All_CL_by2_plus_od_by2 =  array_filter($status_column, function($d) { return $d == 'OD/2+CL/2'; });
                            echo count($All_CL) + count($All_CL_by2)/2 + count($All_CL_by2_plus_hd)/2 + count($All_CL_by2_plus_od_by2)/2;*/
                            echo count($All_CL);
                            ?>
                        </td>
                        <!-- CL/2 -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            /*$All_CL =  array_filter($status_column, function($d) { return $d == 'CL'; });*/
                            $All_CL_by2 =  array_filter($status_column, function ($d) {
                                return $d == 'CL/2';
                            });
                            $All_CL_by2_plus_hd =  array_filter($status_column, function ($d) {
                                return $d == 'H/D+CL/2';
                            });
                            $All_CL_by2_plus_od_by2 =  array_filter($status_column, function ($d) {
                                return $d == 'OD/2+CL/2';
                            });
                            echo count($All_CL_by2) + count($All_CL_by2_plus_hd) + count($All_CL_by2_plus_od_by2);
                            ?>
                        </td>
                        <!-- Total RH -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_RH = array_filter($status_column, function ($d) {
                                return $d == 'RH';
                            });
                            echo count($All_RH);
                            ?>
                        </td>
                        <!-- Absent -->
                        <td class="text-center">
                            <?php
                            $status_column = array_column($PreFinalPaidDays_Data, 'status');
                            $All_Absent = array_filter($status_column, function ($d) {
                                return $d == 'A';
                            });
                            $All_MissedPunch = array_filter($status_column, function ($d) {
                                return $d == 'M/P';
                            });
                            $All_UL = array_filter($status_column, function ($d) {
                                return $d == 'UL';
                            });
                            $All_UL_by2 = array_filter($status_column, function ($d) {
                                return $d == 'UL/2';
                            });
                            /*
                            $All_ul_by2_plus_hd = array_filter($status_column, function($d) { return $d == 'H/D+UL/2'; });
                            $All_ul_by2_plus_od_by2 = array_filter($status_column, function($d) { return $d == 'OD/2+UL/2'; });
                            */
                            $All_sandwich = array_filter($status_column, function ($d) {
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
                            $All_HD = array_filter($status_column, function ($d) {
                                return $d == 'H/D';
                            });
                            $All_ul_by2_plus_hd = array_filter($status_column, function ($d) {
                                return $d == 'H/D+UL/2';
                            });
                            $All_ul_by2_plus_od_by2 = array_filter($status_column, function ($d) {
                                return $d == 'OD/2+UL/2';
                            });
                            $All_od_by2 = array_filter($status_column, function ($d) {
                                return $d == 'OD/2';
                            });
                            $All_HL_by2 = array_filter($status_column, function ($d) {
                                return $d == 'HL/2';
                            });
                            $All_CL_by2 = array_filter($status_column, function ($d) {
                                return $d == 'CL/2';
                            });
                            $All_COMP_OFF_by2 = array_filter($status_column, function ($d) {
                                return $d == 'COMP OFF/2';
                            });
                            $All_el_by2 = array_filter($status_column, function ($d) {
                                return $d == 'EL/2';
                            });
                            $total_absent_by2 = count($All_HD) + count($All_ul_by2_plus_hd) + count($All_ul_by2_plus_od_by2) + count($All_HL_by2) + count($All_od_by2) + count($All_CL_by2) + count($All_COMP_OFF_by2) + count($All_el_by2);
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
                        <!-- Name for search -->
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Shift end</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo !empty($PreFinalPaidDays_Data[$date]['shift_end']) ? date('H:i', strtotime($PreFinalPaidDays_Data[$date]['shift_end'])) : ''; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Machine</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo @$PreFinalPaidDays_Data[$date]['machine']; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Is Holiday</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo @$PreFinalPaidDays_Data[$date]['is_holiday']; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Punch in time</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo !empty($PreFinalPaidDays_Data[$date]['punch_in_time']) ? date('H:i', strtotime($PreFinalPaidDays_Data[$date]['punch_in_time'])) : ''; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Punch out time</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo !empty($PreFinalPaidDays_Data[$date]['punch_out_time']) ? date('H:i', strtotime($PreFinalPaidDays_Data[$date]['punch_out_time'])) : ''; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">In time between shift (OD Included)</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo !empty($PreFinalPaidDays_Data[$date]['in_time_between_shift_with_od']) ? date('H:i', strtotime($PreFinalPaidDays_Data[$date]['in_time_between_shift_with_od'])) : ''; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Out time between shift (OD Included)</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo !empty($PreFinalPaidDays_Data[$date]['out_time_between_shift_with_od']) ? date('H:i', strtotime($PreFinalPaidDays_Data[$date]['out_time_between_shift_with_od'])) : ''; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Punch in time (OD Included)</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo !empty($PreFinalPaidDays_Data[$date]['in_time_including_od']) ? date('H:i', strtotime($PreFinalPaidDays_Data[$date]['in_time_including_od'])) : ''; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Punch out time (OD Included)</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <?php echo !empty($PreFinalPaidDays_Data[$date]['out_time_including_od']) ? date('H:i', strtotime($PreFinalPaidDays_Data[$date]['out_time_including_od'])) : ''; ?>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Late coming minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['late_coming_minutes']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Early going minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['early_going_minutes']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Late coming grace</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['late_coming_grace']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Comp Off Minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <strong class="<?php echo @$PreFinalPaidDays_Data[$date]['comp_off_minutes'] > 0 ? 'text-danger' : ''; ?>">
                                    <?php echo @$PreFinalPaidDays_Data[$date]['comp_off_minutes']; ?>
                                </strong>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Wave Off Minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <strong class="<?php echo @$PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0 ? 'text-danger' : ''; ?>">
                                    <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_minutes']; ?>
                                </strong>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Wave Off Remarks</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0 ? 'text-danger' : ''; ?>" style="width: 100px">
                                    <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_remarks']; ?>
                                </p>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Deduction Minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <strong class="<?php echo @$PreFinalPaidDays_Data[$date]['deduction_minutes'] > 0 ? 'text-danger' : ''; ?>">
                                    <?php echo @$PreFinalPaidDays_Data[$date]['deduction_minutes']; ?>
                                </strong>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Deduction Remarks</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm <?php echo @$PreFinalPaidDays_Data[$date]['deduction_minutes'] > 0 ? 'text-danger' : ''; ?>" style="width: 100px"><?php echo @$PreFinalPaidDays_Data[$date]['deduction_remarks']; ?></p>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Half Day Adjust from Grace minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <strong class="<?php echo @$PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day'] == 'yes' ? 'text-danger' : ''; ?>">
                                    <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day']; ?>
                                </strong>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Remarks of Half Day Adjust from Grace minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day'] == 'yes' ? 'text-danger' : ''; ?>" style="width: 100px">
                                    <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day_remarks']; ?>
                                </p>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Extra Work Minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['ExtraWorkMinutes']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Late Sitting Minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['LateSittingMinutes']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Over Time Minutes</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['OverTimeMinutes']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Status</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <strong class="<?php echo @($PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0 || $PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day'] == 'yes') ? 'text-danger' : ''; ?>">
                                    <?php echo @$PreFinalPaidDays_Data[$date]['status']; ?>
                                </strong>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <?php if (session()->get('current_user')['employee_id'] == '40') { ?>
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">is_sandwitch</strong></p>
                            </td>
                            <?php
                            foreach ($lastMonthDates as $date) {
                            ?>
                                <td class="text-center">
                                    <strong class="<?php echo @$PreFinalPaidDays_Data[$date]['is_sandwitch'] > 0 ? 'text-danger' : ''; ?>">
                                        <pre><?php @print_r($PreFinalPaidDays_Data[$date]['is_sandwitch']); ?></pre>
                                    </strong>
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
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">is_missed_punch</strong></p>
                            </td>
                            <?php
                            foreach ($lastMonthDates as $date) {
                            ?>
                                <td class="text-center">
                                    <strong class="<?php echo @$PreFinalPaidDays_Data[$date]['is_missed_punch'] > 0 ? 'text-danger' : ''; ?>">
                                        <?php echo @$PreFinalPaidDays_Data[$date]['is_missed_punch']; ?>
                                    </strong>
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
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">is_RH</strong></p>
                            </td>
                            <?php
                            foreach ($lastMonthDates as $date) {
                            ?>
                                <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['is_RH']; ?></td>
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
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">RH_DATA</strong></p>
                            </td>
                            <?php
                            foreach ($lastMonthDates as $date) {
                            ?>
                                <td class="text-center">
                                    <?php #print_r($PreFinalPaidDays_Data[$date]['RH_DATA']); 
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
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">wave_off_half_day_who_did_not_work_for_half_day</strong></p>
                            </td>
                            <?php
                            foreach ($lastMonthDates as $date) {
                            ?>
                                <td class="text-center">
                                    <strong class="<?php echo @$PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day'] > 0 ? 'text-danger' : ''; ?>">
                                        <?php echo @$PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day']; ?>
                                    </strong>
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
                            <td class="text-center">
                                <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Status remarks</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center">
                                <p
                                    class="text-wrap mb-0 lh-sm <?php echo @($PreFinalPaidDays_Data[$date]['wave_off_minutes'] > 0 || $PreFinalPaidDays_Data[$date]['wave_off_half_day_who_did_not_work_for_half_day'] == 'yes') ? 'text-danger' : ''; ?>"
                                    style="width: 100px">
                                    <small><?php echo @$PreFinalPaidDays_Data[$date]['status_remarks']; ?></small>
                                    <!-- <small>.............</small> -->
                                </p>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Leave request type</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['leave_request_type']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Leave request amount</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['leave_request_amount']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Leave request status</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['leave_request_status']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small">Paid Day</strong></p>
                        </td>
                        <?php
                        foreach ($lastMonthDates as $date) {
                        ?>
                            <td class="text-center"><?php echo @$PreFinalPaidDays_Data[$date]['paid']; ?></td>
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
                        <td class="text-center">
                            <p class="text-wrap mb-0 lh-sm" style="width: 100px"><strong class="small"><?php echo $employee_name; ?></strong></p>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <!--end::Col-->


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
                    dateFormat: "Y-m",
                    altFormat: "F Y",
                    theme: "dark"
                })
            ]
        });

        $(document).on('change', '#company', function() {
            var company = $('#company').val();
            var department = $('#department').val();
            if (jQuery.inArray("all_companies", company) !== -1 && company.length > 1) {
                $('#company').select2("val", ['all_companies']);
            }

            $('#department_error').html('');
            $('#department').parent().find('.select2-selection').addClass('loading');
            getDepatmentByCompany($('#company').val()).then(function() {
                $('#department').parent().find('.select2-selection').removeClass('loading');
            });
        })

        $(document).on('change', '#department', function() {
            $('#employee_error').html('');
            var department = $('#department').val();
            if (jQuery.inArray("all_departments", department) !== -1 && department.length > 1) {
                $('#department').select2("val", ['all_departments']);
            }
            $('#employee').parent().find('.select2-selection').addClass('loading');
            getEmployeesByDepatment($('#company').val(), $('#department').val()).then(function() {
                $('#employee').parent().find('.select2-selection').removeClass('loading');
            });
        })

        $(document).on('change', '#employee', function() {
            var employee = $('#employee').val();
            if (jQuery.inArray("all_employees", employee) !== -1 && employee.length > 1) {
                $('#employee').select2("val", ['all_employees']);
            }
        })

        /*begin::final_paid_days_report*/
        var final_paid_days_report = $("#final_paid_days_report").DataTable({
            "dom": '<"card"<"card-header"<"card-title"><"card-toolbar my-0"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end"p>>>>',
            buttons: [{
                extend: 'excelHtml5',
                text: 'Download as Excel',
                className: 'btn btn-sm me-3'
            }],
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
            "oLanguage": {
                "sSearch": ""
            },
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "paging": false,
            "columnDefs": [{
                    targets: -1,
                    searchable: true,
                    visible: false
                },
                {
                    "className": 'border-end border-secondary td-border-left text-center',
                    "targets": [3]
                },
                {
                    "className": 'text-center',
                    "targets": '_all'
                },
                {
                    targets: '_all',
                    searchable: false,
                    visible: true
                },
            ],
            "fixedColumns": {
                left: 4,
                right: 0
            },
            initComplete: function() {
                $('div.dt-search input').addClass('border-0')
            }
        });
        $('#final_paid_days_report_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Final Paid Days</h3>');
        /*end::final_paid_days_report*/

        $(document).on('click', '#refresh_current_month_attendance', function(e) {
            e.preventDefault();
            var refreshButton = $(this);
            refreshButton.attr("data-kt-indicator", "on");
            var saved = `<?php echo save_raw_punching_data('ALL', first_date_of_month(), current_date_of_month(), true); ?>`;
            if (saved == true) {
                location.reload();
            }
            // console.log(`<?php save_raw_punching_data('ALL', first_date_of_month(), current_date_of_month()); ?>`);
            // $.ajax({
            //     method: "post",
            //     url: "<?php echo base_url('/ajax/backend/refresh-current-month-attendance'); ?>",
            //     data: {
            //         ''
            //     },
            //     success: function(response){
            //         if( response.response_type == 'error' ){
            //             $('#employee_error').html(response.response_description);
            //         }

            //         if( response.response_type == 'success' ){
            //             if( typeof response.response_data.employees != 'undefined' ){
            //                 var employee_data = response.response_data.employees;
            //                 $.each(employee_data, function(index, employee){
            //                     $('#employee').append('<option value="'+employee.id+'" >'+employee.employee_name+' ('+employee.internal_employee_id+') - '+employee.department_name+' - '+employee.company_short_name+'</option>');
            //                 });
            //                 $('#employee').val([]).trigger('change');
            //             }
            //         }
            //     },
            //     failed: function(){
            //         Swal.fire({
            //             html: "Ajax Failed while loading employees conditionally, Please contact administrator",
            //             icon: "error",
            //             buttonsStyling: !1,
            //             confirmButtonText: "Ok, got it!",
            //             customClass: { confirmButton: "btn btn-primary" },
            //         })
            //     }
            // });
        })
    })

    const getDepatmentByCompany = async (company_id) => {
        $('#department').html('<option></option>');
        $('#department').append('<option value="all_departments">All Departments</option>');
        var data = {
            'company_id': company_id,
        };
        return $.ajax({
            method: "post",
            url: "<?php echo base_url('/ajax/backend/reports/get-department-by-company-id'); ?>",
            data: data,
            success: function(response) {
                if (response.response_type == 'error') {
                    $('#department_error').html(response.response_description);
                }

                if (response.response_type == 'success') {
                    if (typeof response.response_data.departments != 'undefined') {
                        var department_data = response.response_data.departments;
                        $.each(department_data, function(index, department) {
                            $('#department').append('<option value="' + department.id + '" >' + department.department_name + ' - ' + department.company_short_name + '</option>');
                        });
                        $('#department').val([]).trigger('change');
                    }
                }
            },
            failed: function() {
                Swal.fire({
                    html: "Ajax Failed while loading departments conditionally, Please contact administrator",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    },
                })
            }
        });
    }

    const getEmployeesByDepatment = async (company_id, department_id) => {
        $('#employee').html('<option></option>');
        $('#employee').append('<option value="all_employees">All Employees</option>');
        var data = {
            'company_id': company_id,
            'department_id': department_id,
        };
        return $.ajax({
            method: "post",
            url: "<?php echo base_url('/ajax/backend/reports/get-employees-by-department-id'); ?>",
            data: data,
            success: function(response) {
                if (response.response_type == 'error') {
                    $('#employee_error').html(response.response_description);
                }

                if (response.response_type == 'success') {
                    if (typeof response.response_data.employees != 'undefined') {
                        var employee_data = response.response_data.employees;
                        $.each(employee_data, function(index, employee) {
                            // $('#employee').append('<option value="'+employee.id+'" >'+employee.employee_name+' ('+employee.internal_employee_id+') - '+employee.department_name+' - '+employee.company_short_name+'</option>');
                            $('#employee').append(`<option value="${employee.id}" >${employee.employee_name} (${employee.internal_employee_id}) - ${employee.department_name} - ${employee.company_short_name} ${employee.status != 'active' ? ' --'+employee.status : ''}</option>`);
                        });
                        $('#employee').val([]).trigger('change');
                    }
                }
            },
            failed: function() {
                Swal.fire({
                    html: "Ajax Failed while loading employees conditionally, Please contact administrator",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    },
                })
            }
        });
    }
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>