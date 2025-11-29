<?php

namespace App\Pipes\AttendanceProcessor;


use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use Closure;

class RefactorPunchingRow
{
    public function handle($punching_row, Closure $next)
    {
        $current_user_data = $punching_row['current_user_data'];
        $date_time_formatted = $punching_row['date_time'];
        $date_time_ordering = !empty($punching_row['date_time']) ? strtotime($punching_row['date_time']) : '0';
        $punching_row['date_time_ordering'] = $date_time_ordering;
        $punching_row['date_time_new'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

        $punching_row['shift']          = !empty($punching_row['shift_override']) ? $punching_row['shift_override'] : ProcessorHelper::get_shift($current_user_data[$punching_row['day']]);

        $punching_row['shift_start']    = !empty($punching_row['shift']['shift_start']) ? $punching_row['shift']['shift_start'] : null;
        $punching_row['shift_end']      = !empty($punching_row['shift']['shift_end']) ? $punching_row['shift']['shift_end'] : null;

        $punching_row['shift_type']      = ProcessorHelper::get_shift_type($punching_row['shift_id']);

        if ($punching_row['shift_type'] == 'reduce') {
            $shift_duration = ProcessorHelper::get_time_difference($punching_row['shift_start'], $punching_row['shift_end'], 'minutes');
            $expanded_shift_duration = $shift_duration * 3 / 2;
            $new_shift_end_timestamp = strtotime($punching_row['shift_start']) + $expanded_shift_duration * 60;
            $punching_row['shift_end'] = date('H:i:s', $new_shift_end_timestamp);

            $punching_row['absent_for_work_hours_minutes'] = $punching_row['absent_for_work_hours_minutes'] * 3 / 2;
            $punching_row['half_day_for_work_hours_minutes'] = $punching_row['half_day_for_work_hours_minutes'] * 3 / 2;
            $punching_row['attendance_rule']['absent_for_work_hours'] = ProcessorHelper::convertToTime(round(ProcessorHelper::convertToMinutes($punching_row['attendance_rule']['absent_for_work_hours']) * 3 / 2));
            $punching_row['attendance_rule']['half_day_for_work_hours'] = ProcessorHelper::convertToTime(round(ProcessorHelper::convertToMinutes($punching_row['attendance_rule']['half_day_for_work_hours']) * 3 / 2));
            $punching_row['late_coming_rule'][0]['hours'] = ProcessorHelper::convertToTime(round(ProcessorHelper::convertToMinutes($punching_row['late_coming_rule'][0]['hours']) * 3 / 2));
        }


        // if ($punching_row['date'] == '2025-10-10') {
        //     dd(
        //         [
        //             $punching_row['absent_for_work_hours_minutes'],
        //             $punching_row['half_day_for_work_hours_minutes'],
        //             $punching_row['attendance_rule'],
        //             $punching_row['late_coming_rule'],
        //             $punching_row['late_coming_rule'][0]['hours'],
        //         ]
        //     );
        // }
        return $next($punching_row);
    }
}
