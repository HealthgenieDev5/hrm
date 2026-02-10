<?php

namespace App\Pipes\DashboardPipes;

use App\Libraries\Pipeline;
use App\Pipes\AttendanceProcessor\AddDataToPunchingRow;
use App\Pipes\AttendanceProcessor\AdjustDayShiftAfterNightShift;
use App\Pipes\AttendanceProcessor\AdjustNightShiftAndSwitchToDay;
use App\Pipes\AttendanceProcessor\ApplyManualPunching;
use App\Pipes\AttendanceProcessor\ApplyShiftOverride;
use App\Pipes\AttendanceProcessor\ApplyStatusCodeAndRemarks;
use App\Pipes\AttendanceProcessor\CheckFraudPunches;
use App\Pipes\AttendanceProcessor\CheckFraudPunchesAndOverride;
use App\Pipes\AttendanceProcessor\PunchTimeCleanup;
use App\Pipes\AttendanceProcessor\RefactorPunchingRow;
use App\Pipes\DashboardPipes\AddDataToPunchingRow as DashboardPipesAddDataToPunchingRow;
use App\Pipes\DashboardPipes\ApplyShiftOverride as DashboardPipesApplyShiftOverride;
use App\Pipes\DashboardPipes\RefactorPunchingRow as DashboardPipesRefactorPunchingRow;
use Closure;

class ProcessAttendance
{
    public function handle($data, Closure $next)
    {
        $get_punching_data = $data['RawPunchingData'];
        $punching_data = array();
        foreach ($get_punching_data as $punching_row) {

            $punching_row['current_user_data'] = $data['current_user_data'];

            $punching_row = (new Pipeline())
                ->send($punching_row)
                ->through([
                    DashboardPipesApplyShiftOverride::class,
                    DashboardPipesRefactorPunchingRow::class,

                    AdjustNightShiftAndSwitchToDay::class, //main class
                    AdjustDayShiftAfterNightShift::class, //main class

                    ApplyManualPunching::class, //main class

                    PunchTimeCleanup::class, //main class

                    CheckFraudPunchesAndOverride::class,

                    DashboardPipesAddDataToPunchingRow::class, //real calculation is happening here
                ])
                ->then(function ($data) {
                    return $data;
                });

            if (!empty($punching_row)) {
                $punching_data[] = $punching_row;
            }
        }
        $punching_data_sorted = orderResultSet($punching_data, 'date_time_ordering', TRUE);
        // return $punching_data_sorted;
        $data['punching_data'] = $punching_data_sorted;

        return $next($data);
    }
}
