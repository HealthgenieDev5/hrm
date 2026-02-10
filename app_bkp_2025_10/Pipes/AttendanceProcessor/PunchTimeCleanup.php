<?php

namespace App\Pipes\AttendanceProcessor;


use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\OdRequestsModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use Closure;

class PunchTimeCleanup
{
    public function handle($punching_row, Closure $next)
    {
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

        $punching_row['punching_time_between_shift_including_od'] = $punching_time_between_shift_including_od = ProcessorHelper::get_punch_time_between_shift_including_od(
            $punching_row['in_time__Raw'],
            $punching_row['out_time__Raw'],
            $punching_row['shift_start'],
            $punching_row['shift_end'],
            $punching_row['date'],
            $punching_row['current_user_data']['id'],
        );
        // if ($punching_row['DateString_2'] == '2025-04-12') {
        //     dd($punching_time_between_shift_including_od);
        // }

        $punching_row['punch_time_including_od'] = ProcessorHelper::get_punch_time_including_od(
            $punching_row['in_time__Raw'],
            $punching_row['out_time__Raw'],
            $punching_row['shift_start'],
            $punching_row['shift_end'],
            $punching_row['date'],
            $punching_row['current_user_data']['id'],
        );

        // if ($punching_row['DateString_2'] == '2025-04-12') {
        //     dd($punching_row['punch_time_including_od']);
        // }

        $punching_row['in_time_including_od'] = $punching_row['punch_time_including_od'][0];
        $punching_row['out_time_including_od'] = $punching_row['punch_time_including_od'][1];

        $punching_row['in_time'] = $punching_time_between_shift_including_od[0];
        $punching_row['out_time'] = $punching_time_between_shift_including_od[1];

        // if ($punching_row['employee_id'] == '316' && $punching_row['date'] == '2025-12-03') {
        //     dd($punching_row);
        // }

        return $next($punching_row);
    }
}
