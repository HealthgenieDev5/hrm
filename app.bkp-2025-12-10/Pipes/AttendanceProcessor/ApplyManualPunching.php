<?php

namespace App\Pipes\AttendanceProcessor;

use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\ManualPunchModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use Closure;

class ApplyManualPunching
{
    public function handle($punching_row, Closure $next)
    {
        $employee_id = $punching_row['current_user_data']['id'];
        $date = $punching_row['date'];
        $ManualPunchModel = new ManualPunchModel();
        $manualPunchData = $ManualPunchModel->where('employee_id =', $employee_id)->where('punch_date =', $date)->first();

        if (!empty($manualPunchData)) {
            $punching_row['INTime'] = $manualPunchData['punch_in'] ?? "--:--";
            $punching_row['OUTTime'] = $manualPunchData['punch_out'] ?? "--:--";
        }

        if ($employee_id == 40 && $date == '2025-11-08') {
            // print_r($punching_row['INTime']);
            #$punching_row['OUTTime'] = '19:10';
            // die();
        }

        return $next($punching_row);
    }
}
