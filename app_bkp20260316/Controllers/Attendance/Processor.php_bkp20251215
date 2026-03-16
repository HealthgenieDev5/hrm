<?php

namespace App\Controllers\Attendance;

use App\Libraries\Pipeline;
use App\Pipes\BasicDetails;
use App\Models\EmployeeModel;
use App\Pipes\ProcessAttendance;
use App\Pipes\GetAttendanceClean;
use App\Pipes\SandwichSecondPass;
use App\Controllers\BaseController;
use App\Pipes\FetchFreshAttendance;
use App\Pipes\LateComingAdjustment;
use App\Pipes\ShiftRulesAndDetails;
use App\Pipes\AdjustLastWorkingDate;
use App\Pipes\ApplyAttendanceOverride;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\GraceBalanceModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;
use App\Pipes\DashboardPipes\BasicDetails as DashboardPipesBasicDetails;
use App\Pipes\DashboardPipes\GetAttendanceClean as DashboardPipesGetAttendanceClean;
use App\Pipes\DashboardPipes\ProcessAttendance as DashboardPipesProcessAttendance;
use App\Pipes\RecalculateForHeuer;

class Processor extends BaseController
{
    public $session;
    public $uri;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public static function getProcessedPunchingData($employee_id, $dateFrom, $dateTo, $do_sw_second_pass = true)
    {
        $processedAttendanceArray = self::ProcessPunchingData($employee_id, $dateFrom, $dateTo, $do_sw_second_pass = true);

        // print_r($processedAttendanceArray);
        // die();
        $GraceBalanceModel = new GraceBalanceModel();
        $balance_grace = $processedAttendanceArray['balance_grace'];
        $GraceBalanceModel->updateOrCreate(
            ['employee_id' => $employee_id, 'year_month' => date('Y-m', strtotime($dateFrom ?? date('Y-m')))],
            ['employee_id' => $employee_id, 'year_month' => date('Y-m', strtotime($dateFrom ?? date('Y-m'))), 'minutes' => $balance_grace]
        );
        return $processedAttendanceArray['punching_data'];
    }

    private static function ProcessPunchingData($employee_id, $dateFrom, $dateTo, $do_sw_second_pass = true)
    {

        $data = [
            'employee_id' => $employee_id,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'do_sw_second_pass' => $do_sw_second_pass,
        ];

        $result = (new Pipeline())
            ->send($data)
            ->through([
                BasicDetails::class,
                ShiftRulesAndDetails::class,
                GetAttendanceClean::class,
                ProcessAttendance::class, //real work is happening here
                LateComingAdjustment::class,
                SandwichSecondPass::class,
                ApplyAttendanceOverride::class,
                RecalculateForHeuer::class,
                AdjustLastWorkingDate::class,
            ])
            ->then(function ($data) {
                return $data;
            });



        return $result;
    }


    public static function getProcessedDashboardDataNew($employee_row, $RawPunchingData)
    {

        $data = [
            // 'employee_id' => $employee_id,
            'current_user_data' => $employee_row,
            'RawPunchingData' => $RawPunchingData,
        ];

        $result = (new Pipeline())
            ->send($data)
            ->through([
                DashboardPipesGetAttendanceClean::class,
                DashboardPipesProcessAttendance::class, //real work is happening here
            ])
            ->then(function ($data) {
                return $data;
            });

        $punching_data = $result['punching_data'];

        return $punching_data;
    }

    public static function getProcessedDashboardData($employee_id, $dateFrom, $dateTo)
    {
        $EmployeeModel = new EmployeeModel();
        $current_user_data = $EmployeeModel
            ->select('employees.*')
            ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
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
            ->join('shift_per_day as spd', 'spd.id = s.id', 'left')
            ->where('employees.id =', $employee_id)
            ->first();

        ################# Begin::Get Shift Rules [Late Coming rule] #################
        $shift_id_current_user_data = $current_user_data['shift_id'];
        $ShiftAttendanceRuleModel_current_user_data =  new ShiftAttendanceRuleModel();
        $ShiftAttendanceRule_current_user_data = $ShiftAttendanceRuleModel_current_user_data->where('shift_id =', $shift_id_current_user_data)->first();
        $late_coming_rule_current_user_data = json_decode($ShiftAttendanceRule_current_user_data['late_coming_rule'], true);
        $attendance_rule_current_user_data = json_decode($ShiftAttendanceRule_current_user_data['attendance_rule'], true);

        $absent_for_work_hours_current_user_data              = date_create($attendance_rule_current_user_data['absent_for_work_hours']);
        $absent_for_work_hours_hrs_current_user_data          = $absent_for_work_hours_current_user_data->format('h');
        $absent_for_work_hours_minutes_current_user_data      = $absent_for_work_hours_current_user_data->format('i');
        $absent_for_work_hours_minutes_current_user_data      = $absent_for_work_hours_minutes_current_user_data + ($absent_for_work_hours_hrs_current_user_data * 60);

        $half_day_for_work_hours_current_user_data              = date_create($attendance_rule_current_user_data['half_day_for_work_hours']);
        $half_day_for_work_hours_hrs_current_user_data          = $half_day_for_work_hours_current_user_data->format('h');
        $half_day_for_work_hours_minutes_current_user_data      = $half_day_for_work_hours_current_user_data->format('i');
        $half_day_for_work_hours_minutes_current_user_data      = $half_day_for_work_hours_minutes_current_user_data + ($half_day_for_work_hours_hrs_current_user_data * 60);

        ################# End::Get Shift Rules [Late Coming rule] #################

        $get_punching_data = json_decode(get_punching_data($current_user_data['internal_employee_id'], $dateFrom, $dateTo), true)['InOutPunchData'];
        foreach ($get_punching_data as $punching_data_index => $punching_data_row) {
            $get_punching_data[$punching_data_index]['employee_id'] = $employee_id;
            $get_punching_data[$punching_data_index]['late_sitting_allowed'] = $current_user_data['late_sitting_allowed'];
            $get_punching_data[$punching_data_index]['late_sitting_formula'] = $current_user_data['late_sitting_formula'];
            $get_punching_data[$punching_data_index]['over_time_allowed'] = $current_user_data['over_time_allowed'];
            $day = date('l', strtotime($punching_data_row['DateString']));
            $date_time = date('d M Y', strtotime(str_replace('/', '-', $punching_data_row['DateString'])));
            $get_punching_data[$punching_data_index]['date'] = date('Y-m-d', strtotime($date_time));
            $get_punching_data[$punching_data_index]['date_time'] = $date_time;
            $get_punching_data[$punching_data_index]['date_time_new'] = $date_time;
            $get_punching_data[$punching_data_index]['day'] = $day;

            $get_punching_data[$punching_data_index]['shift_override']  = ProcessorHelper::get_shift_override($get_punching_data[$punching_data_index]['employee_id'], $get_punching_data[$punching_data_index]['date']);
            if (!empty($get_punching_data[$punching_data_index]['shift_override'])) {
                $get_punching_data[$punching_data_index]['shift_id']    = $get_punching_data[$punching_data_index]['shift_override']['shift_override_id'];

                $ShiftAttendanceRuleModel =  new ShiftAttendanceRuleModel();
                $ShiftAttendanceRule = $ShiftAttendanceRuleModel->where('shift_id =', $get_punching_data[$punching_data_index]['shift_id'])->first();

                $get_punching_data[$punching_data_index]['late_coming_rule'] = json_decode($ShiftAttendanceRule['late_coming_rule'], true);
                $get_punching_data[$punching_data_index]['attendance_rule'] = json_decode($ShiftAttendanceRule['attendance_rule'], true);

                $absent_for_work_hours                                  = date_create($get_punching_data[$punching_data_index]['attendance_rule']['absent_for_work_hours']);
                $absent_for_work_hours_hrs                              = $absent_for_work_hours->format('h');
                $absent_for_work_hours_minutes                          = $absent_for_work_hours->format('i');
                $absent_for_work_hours_minutes                          = $absent_for_work_hours_minutes + ($absent_for_work_hours_hrs * 60);

                $half_day_for_work_hours                                = date_create($get_punching_data[$punching_data_index]['attendance_rule']['half_day_for_work_hours']);
                $half_day_for_work_hours_hrs                            = $half_day_for_work_hours->format('h');
                $half_day_for_work_hours_minutes                        = $half_day_for_work_hours->format('i');
                $half_day_for_work_hours_minutes                        = $half_day_for_work_hours_minutes + ($half_day_for_work_hours_hrs * 60);
            } else {
                $get_punching_data[$punching_data_index]['shift_id']    = $shift_id_current_user_data;
                $absent_for_work_hours_minutes                          = $absent_for_work_hours_minutes_current_user_data;
                $half_day_for_work_hours_minutes                        = $half_day_for_work_hours_minutes_current_user_data;
            }
        }

        $punching_data = array();
        foreach ($get_punching_data as $punching_row) {
            $date_time_formatted = $punching_row['date_time'];
            $date_time_ordering = !empty($punching_row['date_time']) ? strtotime($punching_row['date_time']) : '0';
            $punching_row['date_time_ordering'] = $date_time_ordering;
            $punching_row['date_time_new'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

            #add in_time in punching_data_row
            if ($punching_row['INTime'] !== '--:--') {
                $punching_row['in_time__Raw'] = date('H:i:s', strtotime($punching_row['INTime']));
            } else {
                $punching_row['in_time__Raw'] = null;
            }

            #add out_time in punching_data_row
            if ($punching_row['OUTTime'] !== '--:--') {
                $punching_row['out_time__Raw'] = date('H:i:s', strtotime($punching_row['OUTTime']));
            } else {
                $punching_row['out_time__Raw'] = null;
            }

            $punching_row['shift']          = !empty($punching_row['shift_override']) ? $punching_row['shift_override'] : ProcessorHelper::get_shift($current_user_data[$punching_row['day']]);
            $punching_row['shift_start']    = !empty($punching_row['shift']['shift_start']) ? $punching_row['shift']['shift_start'] : null;
            $punching_row['shift_end']      = !empty($punching_row['shift']['shift_end']) ? $punching_row['shift']['shift_end'] : null;

            $punching_row['punching_time_between_shift_including_od'] = $punching_time_between_shift_including_od = ProcessorHelper::get_punch_time_between_shift_including_od(
                $punching_row['in_time__Raw'],
                $punching_row['out_time__Raw'],
                $punching_row['shift']['shift_start'],
                $punching_row['shift']['shift_end'],
                $punching_row['date'],
                $employee_id
            );

            $punching_row['punch_time_including_od'] = ProcessorHelper::get_punch_time_including_od(
                $punching_row['in_time__Raw'],
                $punching_row['out_time__Raw'],
                $punching_row['shift']['shift_start'],
                $punching_row['shift']['shift_end'],
                $punching_row['date'],
                $employee_id
            );

            $punching_row['in_time_including_od'] = $punching_row['punch_time_including_od'][0];
            $punching_row['out_time_including_od'] = $punching_row['punch_time_including_od'][1];

            $punching_row['in_time'] = $punching_time_between_shift_including_od[0];
            $punching_row['out_time'] = $punching_time_between_shift_including_od[1];

            $punching_row['late_coming_minutes']    = ProcessorHelper::get_late_coming_minutes($punching_row['shift']['shift_start'], $punching_row['in_time']);
            $punching_row['early_going_minutes']    = ProcessorHelper::get_early_going_minutes($punching_row['shift']['shift_end'], $punching_row['out_time'], $punching_row['in_time']);

            $punching_row['is_weekoff']             = ProcessorHelper::is_weekoff($punching_row['shift_id'], $punching_row['date']);


            if (!empty($punching_row['out_time_including_od']) && !empty($punching_row['out_time_including_od']) && (strtotime($punching_row['out_time_including_od']) < strtotime($punching_row['in_time_including_od']))) {
                $extra_working_minutes_day1  = ProcessorHelper::get_time_difference($punching_row['shift']['shift_end'], '23:59', 'minutes');
                $extra_working_minutes_day2  = ProcessorHelper::get_time_difference('00:00', $punching_row['out_time_including_od'], 'minutes');
                $extra_working_minutes = $extra_working_minutes_day1 + $extra_working_minutes_day2 + 1;
            } else {
                $extra_working_minutes  = ProcessorHelper::get_time_difference($punching_row['shift']['shift_end'], $punching_row['out_time_including_od'], 'minutes');
            }

            $punching_row['extra_working_minutes']  = $extra_working_minutes > 0 ? $extra_working_minutes : 0;

            $punching_row['is_on_InternationOD']    = ProcessorHelper::is_on_InternationOD($punching_row['date'], $employee_id);
            if ($punching_row['is_on_InternationOD'] == 'yes' && $punching_row['is_weekoff'] == 'yes') {
                $punching_row['is_onOD']            = 'no';
            } else {
                $punching_row['is_onOD']            = ProcessorHelper::is_onOD($punching_row['date'], $employee_id);
            }

            $punching_row['shift_start'] = (!empty($punching_row['shift_start'])) ? date('h:i A', strtotime($punching_row['shift_start'])) : null;
            $punching_row['shift_end'] = (!empty($punching_row['shift_end'])) ? date('h:i A', strtotime($punching_row['shift_end'])) : null;

            $punching_row['in_time_between_shift_with_od'] = !empty($punching_row['in_time']) ? date('h:i A', strtotime($punching_row['in_time'])) : null;
            $punching_row['out_time_between_shift_with_od'] = !empty($punching_row['out_time']) ? date('h:i A', strtotime($punching_row['out_time'])) : null;
            $punching_row['punch_in_time'] = !empty($punching_row['in_time__Raw']) ? date('h:i A', strtotime($punching_row['in_time__Raw'])) : null;
            $punching_row['punch_out_time'] = !empty($punching_row['out_time__Raw']) ? date('h:i A', strtotime($punching_row['out_time__Raw'])) : null;

            $punching_row['in_time_including_od'] = !empty($punching_row['in_time_including_od']) ? date('h:i A', strtotime($punching_row['in_time_including_od'])) : null;
            $punching_row['out_time_including_od'] = !empty($punching_row['out_time_including_od']) ? date('h:i A', strtotime($punching_row['out_time_including_od'])) : null;

            if (!empty($punching_row)) {
                $punching_data[] = $punching_row;
            }
        }

        $punching_data_sorted = orderResultSet($punching_data, 'date_time_ordering', TRUE);
        return $punching_data_sorted;
    }
}
