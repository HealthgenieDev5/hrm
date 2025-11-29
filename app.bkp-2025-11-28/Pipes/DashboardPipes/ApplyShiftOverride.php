<?php

namespace App\Pipes\DashboardPipes;


use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;
use Closure;

class ApplyShiftOverride
{
    public function handle($punching_row, Closure $next)
    {
        $shift_id_current_user_data = $punching_row['shift_id_current_user_data'];

        $punching_row['shift_override'] = ProcessorHelper::get_shift_override($punching_row['employee_id'], $punching_row['date']);
        if (!empty($punching_row['shift_override'])) {
            $punching_row['shift_id']                               = $punching_row['shift_override']['shift_override_id'];
        } else {
            $punching_row['shift_id']                               = $shift_id_current_user_data;
        }

        return $next($punching_row);
    }
}
