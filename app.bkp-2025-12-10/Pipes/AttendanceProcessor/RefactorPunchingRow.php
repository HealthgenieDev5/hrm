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

        //date - 2025-11-27
        $punching_row['shift_type']    = ProcessorHelper::get_shiftType($punching_row['shift_id']);
        //date - 2025-11-27
        $punching_row['shift_start']    = !empty($punching_row['shift']['shift_start']) ? $punching_row['shift']['shift_start'] : null;
        $punching_row['shift_end']      = !empty($punching_row['shift']['shift_end']) ? $punching_row['shift']['shift_end'] : null;


        // dd([$punching_row['shift_id'], $punching_row['shift_type']]);



        return $next($punching_row);
    }
}
