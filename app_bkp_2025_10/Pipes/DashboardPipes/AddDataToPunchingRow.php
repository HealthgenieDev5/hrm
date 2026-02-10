<?php

namespace App\Pipes\DashboardPipes;


use App\Libraries\Pipeline;
use App\Models\CompOffMinutesUtilizedModel;
use App\Models\DeductionModel;
use App\Models\EmployeeModel;
use App\Models\FixedRhModel;
use App\Models\HolidayModel;
use App\Models\LeaveRequestsModel;
use App\Models\OdRequestsModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftModel;
use App\Models\ShiftOverrideModel;
use App\Models\SpecialHolidayEmployeesModel;
use App\Models\WaveOffHalfDayWhoDidNotWorkForHalfDayModel;
use App\Models\WaveOffModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;
use Closure;

class AddDataToPunchingRow
{

    public function handle($punching_row, Closure $next)
    {

        $employee_id = $punching_row['current_user_data']['id'];

        $punching_row['is_missed_punch'] = ProcessorHelper::is_missed_punch($punching_row['in_time__Raw'], $punching_row['out_time__Raw']);
        
        return $next($punching_row);
    }
}
