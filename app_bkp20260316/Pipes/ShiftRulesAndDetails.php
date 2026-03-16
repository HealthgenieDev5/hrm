<?php

namespace App\Pipes;

use App\Models\EmployeeModel;
use App\Models\ShiftAttendanceRuleModel;
use Closure;

class ShiftRulesAndDetails
{
    public function handle($data, Closure $next)
    {
        $shift_id_current_user_data = $data['current_user_data']['shift_id'];
        // $lastWorkingDate = $data['current_user_data']->date_of_leaving;

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

        $data['shift_id_current_user_data'] = $shift_id_current_user_data;
        $data['ShiftAttendanceRule_current_user_data'] = $ShiftAttendanceRule_current_user_data;
        $data['late_coming_rule_current_user_data'] = $late_coming_rule_current_user_data;
        $data['attendance_rule_current_user_data'] = $attendance_rule_current_user_data;
        $data['absent_for_work_hours_minutes_current_user_data'] = $absent_for_work_hours_minutes_current_user_data;
        $data['half_day_for_work_hours_minutes_current_user_data'] = $half_day_for_work_hours_minutes_current_user_data;

        // if ($data['current_user_data']['id'] == '252') {
        //     print_r($data['current_user_data']);
        //     die();
        // }


        return $next($data);
    }
}
