<?php

namespace App\Pipes\AttendanceProcessor;

use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use Closure;

class AdjustDayShiftAfterNightShift
{
    public function handle($punching_row, Closure $next)
    {
        // dd($punching_row);
        $current_user_data = $punching_row['current_user_data'];
        // if (session()->get('current_user')['employee_id'] == '40') {

        $cDate = $punching_row['date'];
        $pDate = date('Y-m-d', strtotime($cDate . ' -1 day'));
        $nDate = date('Y-m-d', strtotime($cDate . ' +1 day'));

        $thresholdEvening = '19:15:00';
        $thresholdMorning = '08:45:00';

        $currentDateShiftType = strtotime($punching_row['shift_start']) > strtotime($punching_row['shift_end']) ? 'night' : 'day';

        $prevShiftOverride = ProcessorHelper::get_shift_override($punching_row['employee_id'], $pDate);
        $prevShift = !empty($prevShiftOverride) ? $prevShiftOverride : ProcessorHelper::get_shift($current_user_data[date('l', strtotime($pDate))]);

        $prevDateShiftType = strtotime($prevShift['shift_start']) > strtotime($prevShift['shift_end']) ? 'night' : 'day';

        if ($prevDateShiftType == 'night'  && $currentDateShiftType == 'day') {
            #Get first punch after prev punch out (current day's first punch)
            $recordInTime = $punching_row['INTime'] ? $punching_row['INTime'] : null;
            $fromDateTime = $recordInTime ? date('d/m/Y_H:i', strtotime($punching_row['date'] . ' ' . $recordInTime . ' +1 minute')) : null;
            $toDateTime = date('d/m/Y_H:i', strtotime($punching_row['date'] . ' 23:59'));
            $punchData = (!empty($fromDateTime) && !empty($toDateTime)) ?
                getAllPunchData($current_user_data['internal_employee_id'], $fromDateTime, $toDateTime, $punching_row['machine']) :
                null;

            if (!empty($punchData)) {
                #because $punchData is descending order so I will use the last record of the array as first punch post night shift punch out
                $lastPunchOfCurrentDateAfterNightPunchOutArray = $punchData ? end($punchData) : null;
                $lastPunchOfCurrentDateAfterNightPunchOutDate = $lastPunchOfCurrentDateAfterNightPunchOutArray  ? $lastPunchOfCurrentDateAfterNightPunchOutArray['PunchDate'] : null;
                $lastPunchOfCurrentDateAfterNightPunchOutDateParsed = $lastPunchOfCurrentDateAfterNightPunchOutDate ? \DateTime::createFromFormat('d/m/Y H:i:s', $lastPunchOfCurrentDateAfterNightPunchOutDate) : null;
            } else {
                $lastPunchOfCurrentDateAfterNightPunchOutDateParsed =  null;
            }

            $punching_row['INTime'] = $lastPunchOfCurrentDateAfterNightPunchOutDateParsed ? $lastPunchOfCurrentDateAfterNightPunchOutDateParsed->format('H:i') : '--:--';

            if ($punching_row['INTime'] == $punching_row['OUTTime']) {
                $punching_row['OUTTime'] = '--:--';
            }
        }
        // }
        // if ($punching_row['date'] == '2025-04-19') {
        //     dd($punching_row);
        // }

        // if ($punching_row['employee_id'] == 316 && $punching_row['date'] == '2025-07-01') {
        //     print_r($punching_row);
        //     die();
        // }
        return $next($punching_row);
    }
}
