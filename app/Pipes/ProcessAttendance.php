<?php

namespace App\Pipes;

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
use Closure;

class ProcessAttendance
{
    public function handle($data, Closure $next)
    {
        $get_punching_data = $data['get_punching_data'];
        $punching_data = [];
        foreach ($get_punching_data as $punching_row) {

            $punching_row['shift_id_current_user_data'] = $data['shift_id_current_user_data'];
            $punching_row['ShiftAttendanceRule_current_user_data'] = $data['ShiftAttendanceRule_current_user_data'];
            $punching_row['absent_for_work_hours_minutes_current_user_data'] = $data['absent_for_work_hours_minutes_current_user_data'];
            $punching_row['half_day_for_work_hours_minutes_current_user_data'] = $data['half_day_for_work_hours_minutes_current_user_data'];
            $punching_row['current_user_data'] = $data['current_user_data'];

            $punching_row = (new Pipeline)
                ->send($punching_row)
                ->through([
                    ApplyShiftOverride::class,
                    RefactorPunchingRow::class,

                    AdjustNightShiftAndSwitchToDay::class,
                    AdjustDayShiftAfterNightShift::class,

                    // CheckFraudPunches::class,

                    ApplyManualPunching::class,
                    // Apply last working date condition
                    PunchTimeCleanup::class,

                    CheckFraudPunchesAndOverride::class,

                    AddDataToPunchingRow::class, // real calculation is happening here
                    ApplyStatusCodeAndRemarks::class, // real calculation is happening here
                ])
                ->then(function ($data) {
                    return $data;
                });

            if (! empty($punching_row)) {
                $punching_data[] = $punching_row;
            }
        }
        $data['punching_data'] = $punching_data;

        return $next($data);
    }
}
