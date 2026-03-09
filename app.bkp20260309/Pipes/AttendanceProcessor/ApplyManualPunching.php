<?php

namespace App\Pipes\AttendanceProcessor;

use App\Models\ManualPunchModel;
use Closure;

class ApplyManualPunching
{
    public function handle($punching_row, Closure $next)
    {
        $employee_id = $punching_row['current_user_data']['id'];
        $date = $punching_row['date'];
        $ManualPunchModel = new ManualPunchModel;
        $manualPunchData = $ManualPunchModel->where('employee_id =', $employee_id)->where('punch_date =', $date)->first();

        if (! empty($manualPunchData)) {
            $punching_row['INTime'] = $manualPunchData['punch_in'] ?? '--:--';
            $punching_row['OUTTime'] = $manualPunchData['punch_out'] ?? '--:--';
        }

        if ($employee_id == 40 && $date == '2026-02-04') {
            // print_r($punching_row['INTime']);
            #$punching_row['OUTTime'] = '19:12';
            // die();
        }

        if ($employee_id == 40 && $date == '2026-02-20') {
            // $punching_row['INTime'] = '10:02';
            #$punching_row['OUTTime'] = '17:10';
            // die();
        }

        return $next($punching_row);
    }
}
