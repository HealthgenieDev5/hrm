<?php

namespace App\Pipes\AttendanceProcessor;

use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use Closure;

class AdjustNightShiftAndSwitchToDay
{
    public function handle($punching_row, Closure $next)
    {
        $current_user_data = $punching_row['current_user_data'];
        // if (session()->get('current_user')['employee_id'] == '40000000000000') {

        $employeeShiftType = strtotime($punching_row['shift_start']) > strtotime($punching_row['shift_end']) ? 'night' : 'day';
        $punching_rowINTime = (isset($punching_row['INTime']) && !empty($punching_row['INTime']) && $punching_row['INTime'] != '--:--') ? $punching_row['INTime'] : null;
        $punching_rowOUTTime = (isset($punching_row['OUTTime']) && !empty($punching_row['OUTTime']) && $punching_row['OUTTime'] != '--:--') ? $punching_row['OUTTime'] : null;

        // $thresholdEvening = '19:15:00';
        // $thresholdMorning = '08:45:00';

        $shiftStart = $punching_row['shift_start'];
        $shiftEnd = $punching_row['shift_end'];
        $bufferMinutes = 45;
        $thresholdEvening = date('H:i:s', strtotime($shiftStart) - ($bufferMinutes * 60));
        $thresholdMorning = date('H:i:s', strtotime($shiftEnd) + ($bufferMinutes * 60));
        $thresholdMorning = $thresholdMorning = '10:45:00';

        if ($employeeShiftType == 'night' && !empty($punching_rowINTime)) {

            $cDate = $punching_row['date'];
            $pDate = date('Y-m-d', strtotime($cDate . ' -1 day'));
            $nDate = date('Y-m-d', strtotime($cDate . ' +1 day'));

            // $prevRecord = json_decode(get_punching_data($current_user_data['internal_employee_id'], $pDate, $pDate), true)['InOutPunchData'][0];
            $record = $punching_row;

            $nextRecord = json_decode(get_punching_data($current_user_data['internal_employee_id'], $nDate, $nDate), true)['InOutPunchData'][0] ?? null;

            #check if the punch in is after threshold and punch out is missing. then consider it to be genuine night punch
            if (strtotime($punching_rowINTime) > strtotime($thresholdEvening) && empty($punching_rowOUTTime)) {
                $punching_row['INTime'] = $punching_rowINTime;
            } else {
                $punching_row['INTime'] = $record['OUTTime'];
            }


            if (
                isset($nextRecord) &&
                !empty($nextRecord) &&

                isset($nextRecord['INTime']) &&
                !empty($nextRecord['INTime']) &&
                $nextRecord['INTime'] != '--:--' &&
                strtotime($nextRecord['INTime']) <= strtotime($thresholdMorning)

            ) {
                $punching_row['OUTTime'] = $nextRecord['INTime'];
            } else {
                $punching_row['OUTTime'] = '--:--';
            }
        }
        /* } else {
            // This is existing code as before from line 227,
            // we are just inversing the punch time which is wrong.
            if (strtotime($punching_row['shift_start']) > strtotime($punching_row['shift_end'])) {
                $i_time = $punching_row['INTime'];
                $o_time = $punching_row['OUTTime'];
                $punching_row['INTime'] = $o_time;
                $punching_row['OUTTime'] = $i_time;
            }
        } */



        return $next($punching_row);
    }
}
