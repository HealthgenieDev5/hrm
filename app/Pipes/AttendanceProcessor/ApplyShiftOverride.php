<?php

namespace App\Pipes\AttendanceProcessor;


use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use Closure;

class ApplyShiftOverride
{
    public function handle($punching_row, Closure $next)
    {

        $shift_id_current_user_data = $punching_row['shift_id_current_user_data'];
        $ShiftAttendanceRule_current_user_data = $punching_row['ShiftAttendanceRule_current_user_data'];
        $absent_for_work_hours_minutes_current_user_data = $punching_row['absent_for_work_hours_minutes_current_user_data'];
        $half_day_for_work_hours_minutes_current_user_data = $punching_row['half_day_for_work_hours_minutes_current_user_data'];
        $current_user_data = $punching_row['current_user_data'];


        $punching_row['shift_override']                             = ProcessorHelper::get_shift_override($punching_row['employee_id'], $punching_row['date']);
        if (!empty($punching_row['shift_override'])) {
            $punching_row['shift_id']                               = $punching_row['shift_override']['shift_override_id'];
            $ShiftAttendanceRuleModel                               =  new ShiftAttendanceRuleModel();
            $ShiftAttendanceRule_this_row                           = $ShiftAttendanceRuleModel->where('shift_id =', $punching_row['shift_id'])->first();
            $punching_row['late_coming_rule']                       = json_decode($ShiftAttendanceRule_this_row['late_coming_rule'], true);
            $punching_row['attendance_rule']                        = json_decode($ShiftAttendanceRule_this_row['attendance_rule'], true);
            $absent_for_work_hours_this_row                         = date_create($punching_row['attendance_rule']['absent_for_work_hours']);
            $absent_for_work_hours_hrs_this_row                     = $absent_for_work_hours_this_row->format('h');
            $absent_for_work_hours_minutes_this_row                 = $absent_for_work_hours_this_row->format('i');
            $punching_row['absent_for_work_hours_minutes']          = $absent_for_work_hours_minutes_this_row + ($absent_for_work_hours_hrs_this_row * 60);
            $half_day_for_work_hours_this_row                       = date_create($punching_row['attendance_rule']['half_day_for_work_hours']);
            $half_day_for_work_hours_hrs_this_row                   = $half_day_for_work_hours_this_row->format('h');
            $half_day_for_work_hours_minutes_this_row               = $half_day_for_work_hours_this_row->format('i');
            $punching_row['half_day_for_work_hours_minutes']        = $half_day_for_work_hours_minutes_this_row + ($half_day_for_work_hours_hrs_this_row * 60);
        } else {
            $punching_row['shift_id']                               = $shift_id_current_user_data;
            $punching_row['late_coming_rule']                       = json_decode($ShiftAttendanceRule_current_user_data['late_coming_rule'], true);
            $punching_row['attendance_rule']                        = json_decode($ShiftAttendanceRule_current_user_data['attendance_rule'], true);
            $punching_row['absent_for_work_hours_minutes']          = $absent_for_work_hours_minutes_current_user_data;
            $punching_row['half_day_for_work_hours_minutes']        = $half_day_for_work_hours_minutes_current_user_data;
        }



        return $next($punching_row);
    }
}
