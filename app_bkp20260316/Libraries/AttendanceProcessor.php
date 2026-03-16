<?php

namespace App\Libraries;

use App\Controllers\Attendance\Processor;
use App\Models\EmployeeModel;
use App\Models\PreFinalPaidDaysModel;
use App\Models\PreFinalSalaryModel;
use CodeIgniter\CLI\CLI;

class AttendanceProcessor
{
    public $session;

    public $uri;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session = session();
    }

    public function processAll(int $chunkSize = 25, ?string $month = null, ?array $employeeIds = null): void
    {

        CLI::write('Processing has Started at ' . date('Y-m-d H:i:s'), 'yellow');

        $employeeIds = ! empty($employeeIds) ? $employeeIds : [];
        $isBulkProcessing = empty($employeeIds) ? true : false;
        $isBulkProcessing = in_array('all', $employeeIds) ? true : false;
        $isBulkProcessing = in_array('All', $employeeIds) ? true : false;
        $isBulkProcessing = in_array('ALL', $employeeIds) ? true : false;

        // dd($employeeIds);
        if (! empty($month)) {
            $from = date('Y-m-01', strtotime($month));
            $to = date('Y-m-t', strtotime($month));
        } else {
            $from = first_date_of_last_month();
            $to = last_date_of_last_month();
            // $to = first_date_of_last_month();
        }

        // dd($from, $to);

        if ($isBulkProcessing) {
            CLI::write('Pulling Fresh Attendance from eTimeOffice', 'blue');
            save_raw_punching_data('ALL', $from, $to);
            CLI::write("Done\n", 'blue');
        }

        $EmployeeModel = new EmployeeModel;
        $EmployeeModel
            ->select('employees.*')
            ->select('d.department_name as department_name')
            ->select('c.company_name as company_name')
            ->select('c.company_short_name as company_short_name')
            ->select('deg.designation_name as designation_name')
            ->select('s.shift_name as shift_name')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "monday" and shift_id = employees.shift_id) as Monday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "tuesday" and shift_id = employees.shift_id) as Tuesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "wednesday" and shift_id = employees.shift_id) as Wednesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "thursday" and shift_id = employees.shift_id) as Thursday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "friday" and shift_id = employees.shift_id) as Friday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "saturday" and shift_id = employees.shift_id) as Saturday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "sunday" and shift_id = employees.shift_id) as Sunday')
            ->join('departments as d', 'd.id = employees.department_id', 'left')
            ->join('companies as c', 'c.id = employees.company_id', 'left')
            ->join('designations as deg', 'deg.id = employees.designation_id', 'left')
            ->join('shifts as s', 's.id = employees.shift_id', 'left')
            ->join('shift_per_day as spd', 'spd.id = s.id', 'left');

        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.joining_date is null');
        $EmployeeModel->orWhere("employees.joining_date <= ('" . $to . "')");
        $EmployeeModel->groupEnd();

        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.date_of_leaving is null');
        $EmployeeModel->orWhere("employees.date_of_leaving >= ('" . $from . "')");
        $EmployeeModel->groupEnd();

        // $EmployeeModel->whereIn('employees.company_id', [1, 3, 5]);
        // $EmployeeModel->whereIn('employees.company_id', [2]);
        // $EmployeeModel->whereIn('employees.company_id', [4]);

        if (! empty($employeeIds)) {
            $EmployeeModel->whereIn('employees.id', $employeeIds);
        }
        // if (empty($employeeIds)) {
        // $EmployeeModel->where('employees.id >=', '541');
        // $EmployeeModel->where('employees.id !=', '541');
        // $EmployeeModel->where('employees.id !=', '546');
        // $EmployeeModel->whereIn('employees.id', [1, 41, 50, 54, 79, 146, 154, 164, 165, 218, 296, 297, 325, 336, 352, 370, 401, 411, 452, 454, 486, 487, 498, 518, 519, 522, 547, 550]);
        // }

        // $EmployeeModel->where('employees.id =', '40');

        // $EmployeeModel->whereIn('employees.internal_employee_id', ['HN1023', 'HN890', 'HN1019', 'HN1073', 'HN938', 'HN878', '819', '781', '817', '619', '997', '770', 'HN1080', 'HN1081', 'HN935', 'HN936', 'HN1013', 'HN862', 'HN1123', 'HN1097', 'HN1100', 'HN1012', 'HN955', 'HN1093', 'HN1060', 'HN1135', 'HN33', 'HN141', 'HN1', 'HN1103', 'HN960', 'HN965', 'HN1061', 'HN988', 'HN1141', 'HN1052', 'HN937', 'HN1031', 'HN1069', 'HN1126', 'HN954', 'HN1116', 'HN1099', '727', 'HN953', '855', 'HN952', 'HN989', '798', 'HN941', 'HN1029', 'HN1028', 'HN991', 'HN959', '867', 'HN5', 'HN956', 'HN1104', 'HN1071', 'HN1025', 'HN957', 'HN961']);

        $allEmployees = $EmployeeModel->findAll();

        // print_r(count($allEmployees));
        // die();

        $chunks = array_chunk($allEmployees, $chunkSize);

        foreach ($chunks as $i => $group) {
            CLI::write('Processing batch #' . ($i + 1), 'blue');
            foreach ($group as $employee_row) {
                $start = microtime(true);
                CLI::write("→ Employee ID: {$employee_row['id']} - Processing...", 'white');
                $this->processEmployee($employee_row, $from, $to, $isBulkProcessing);
                $end = microtime(true);
                $executionTime = $end - $start;
                CLI::write('→ Done in ' . round($executionTime, 4) . " seconds\n", 'white');
            }
            CLI::write('Completed batch #' . ($i + 1), 'light_gray');
        }

        CLI::write('Processing has completed at ' . date('Y-m-d H:i:s'), 'yellow');
    }

    protected function processEmployee($employee_row, $from, $to, $isBulkProcessing = false): void
    {

        $PreFinalSalaryModel = new PreFinalSalaryModel;
        $PreFinalSalaryModel->select('pre_final_salary.*');
        $PreFinalSalaryModel->where('pre_final_salary.employee_id =', $employee_row['id']);
        $PreFinalSalaryModel->where('pre_final_salary.month =', date('m', strtotime($from)));
        $PreFinalSalaryModel->where('pre_final_salary.year =', date('Y', strtotime($from)));
        $FinalSalary = $PreFinalSalaryModel->first();

        if (! empty($FinalSalary) && ! in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
            $message = 'Salary not updatable of ' . trim($employee_row['first_name'] . ' ' . $employee_row['last_name']) . ' (' . $employee_row['internal_employee_id'] . ')';
            CLI::write("→ Employee ID: {$employee_row['id']} - Failed...$message", 'white');
            if (! $isBulkProcessing) {
                exit();
            }
        }

        // Begin::Pulling Attendance from E time office for this employee
        // excluded for March 2025
        // if (!in_array($employee_row['internal_employee_id'], ['809', '745', '856', '857', 'HN1011'])) {
        // save_raw_punching_data($employee_row['internal_employee_id'], $from, $to);
        // }

        // We are pulling fresh attendance of this employee if we are trying to regenerate the attendance of this employee only
        // If in case we are processing all employees then this will be excluded




        if (! $isBulkProcessing) {
            $employee_joining_date = $employee_row['joining_date'] ?? null;
            if (! empty($employee_joining_date)) {
                if ($employee_joining_date > $from) {
                    $from = $employee_joining_date;
                }
            }
            save_raw_punching_data($employee_row['internal_employee_id'], $from, $to);
        }

        // End::Pulling Attendance from E time office for this employee

        $employee_id = $employee_row['id'];
        $shift_id = $employee_row['shift_id'];

        $PunchingData = Processor::getProcessedPunchingData($employee_id, $from, $to);


        if (! empty($PunchingData)) {
            foreach ($PunchingData as $rowIndex => $rowData) {
                $PunchingData[$rowIndex]['shift_start'] = (! empty($rowData['shift_start'])) ? date('H:i:s', strtotime($rowData['shift_start'])) : null;
                $PunchingData[$rowIndex]['shift_end'] = (! empty($rowData['shift_end'])) ? date('H:i:s', strtotime($rowData['shift_end'])) : null;
                $PunchingData[$rowIndex]['in_time_between_shift_with_od'] = (! empty($rowData['in_time_between_shift_with_od'])) ? date('H:i:s', strtotime($rowData['in_time_between_shift_with_od'])) : null;
                $PunchingData[$rowIndex]['out_time_between_shift_with_od'] = (! empty($rowData['out_time_between_shift_with_od'])) ? date('H:i:s', strtotime($rowData['out_time_between_shift_with_od'])) : null;
                $PunchingData[$rowIndex]['punch_in_time'] = (! empty($rowData['punch_in_time'])) ? date('H:i:s', strtotime($rowData['punch_in_time'])) : null;
                $PunchingData[$rowIndex]['punch_out_time'] = (! empty($rowData['punch_out_time'])) ? date('H:i:s', strtotime($rowData['punch_out_time'])) : null;
                $PunchingData[$rowIndex]['in_time_including_od'] = (! empty($rowData['in_time_including_od'])) ? date('H:i:s', strtotime($rowData['in_time_including_od'])) : null;
                $PunchingData[$rowIndex]['out_time_including_od'] = (! empty($rowData['out_time_including_od'])) ? date('H:i:s', strtotime($rowData['out_time_including_od'])) : null;
                $PunchingData[$rowIndex]['late_coming_grace'] = $rowData['grace'];
                $PunchingData[$rowIndex]['wave_off_minutes'] = $rowData['wave_off_minutes'];
                $PunchingData[$rowIndex]['wave_off_remarks'] = $rowData['wave_off_remarks'];
                $PunchingData[$rowIndex]['wave_off_half_day_who_did_not_work_for_half_day'] = $rowData['wave_off_half_day_who_did_not_work_for_half_day'];
                $PunchingData[$rowIndex]['wave_off_half_day_who_did_not_work_for_half_day_remarks'] = $rowData['wave_off_half_day_who_did_not_work_for_half_day_remarks'];
                $PunchingData[$rowIndex]['leave_request_type'] = (! empty($rowData['LeaveData'])) ? $rowData['LeaveData']['type_of_leave'] : '';
                if (! empty($rowData['LeaveData'])) {
                    $PunchingData[$rowIndex]['leave_request_amount'] = ($rowData['LeaveData']['number_of_days'] > 1) ? 1 : $rowData['LeaveData']['number_of_days'];
                } else {
                    $PunchingData[$rowIndex]['leave_request_amount'] = '';
                }
                $PunchingData[$rowIndex]['leave_request_status'] = (! empty($rowData['LeaveData'])) ? $rowData['LeaveData']['status'] : '';
                $PunchingData[$rowIndex]['late_coming_rule'] = (! empty($rowData['late_coming_rule'])) ? json_encode($rowData['late_coming_rule']) : null;
            }




            $PreFinalPaidDaysModel = new PreFinalPaidDaysModel;
            $result = $this->insertOrUpdateBatch($PunchingData, $PreFinalPaidDaysModel);
            if (! $result) {
                CLI::write('❌ Failed to insert/update: ' . trim($employee_row['first_name'] . ' ' . $employee_row['last_name']), 'red');
                CLI::write('→ Query: ' . $PreFinalPaidDaysModel->getLastQuery()->getQuery(), 'red');
                exit();
            } else {
                CLI::write('✅ Success: ' . trim($employee_row['first_name'] . ' ' . $employee_row['last_name']), 'green');
            }
        } else {
            CLI::write('❌ PunchingData is blank: ' . trim($employee_row['first_name'] . ' ' . $employee_row['last_name']), 'red');
        }
    }

    private function insertOrUpdateBatch(array $rows, $model)
    {

        $employeeIds = array_column($rows, 'employee_id');
        $dates = array_column($rows, 'date');

        $existingRows = $model->whereIn('employee_id', $employeeIds)
            ->whereIn('date', $dates)
            ->findAll();

        $existingMap = [];
        foreach ($existingRows as $existing) {
            $key = $existing['employee_id'] . '_' . $existing['date'];
            $existingMap[$key] = $existing['id'];
        }

        $toInsert = [];
        $toUpdate = [];

        foreach ($rows as $row) {
            $key = $row['employee_id'] . '_' . $row['date'];

            if (isset($existingMap[$key])) {
                $row['id'] = $existingMap[$key]; // required for updateBatch
                $toUpdate[] = $row;
            } else {
                $toInsert[] = $row;
            }
        }

        if (! empty($toInsert)) {
            if ($model->insertBatch($toInsert) === false) {
                return false;
            }
        }

        if (! empty($toUpdate)) {
            if ($model->updateBatch($toUpdate, 'id') === false) {
                return false;
            }
        }

        return true;
    }

    // private function insertOrUpdate(array $data, $builder)
    // {

    //     $builder
    //         ->where('employee_id =', $data['employee_id'])
    //         ->where('date =', $data['date']);

    //     $existing = $builder->first();

    //     if ($existing) {
    //         return $builder->update($existing['id'], $data);
    //     }

    //     return $builder->insert($data);
    // }
}
