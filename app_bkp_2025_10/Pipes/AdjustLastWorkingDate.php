<?php

namespace App\Pipes;

use App\AttendanceProcessor\ProcessorHelper;
use App\Models\AttendanceOverrideModel;
use Closure;

class AdjustLastWorkingDate
{
    public function handle($data, Closure $next)
    {
        $attendance_override_done = $data['punching_data'];
        $lastWorkingDate = $data['current_user_data']['date_of_leaving'];
        if (!empty($attendance_override_done) && !empty($lastWorkingDate)) {
            foreach ($attendance_override_done as $i => $itemRow) {

                // code added by sunny to remove everything after last working day
                // if (!empty($itemRow['is_attendance_overridden'])) {
                //     $attendance_override_done[$i]['grace'] = 0;
                // }

                if ($itemRow['date_time_ordering'] > strtotime($lastWorkingDate)) {
                    unset($attendance_override_done[$i]);
                }
            }
        }
        $data['punching_data'] = $attendance_override_done;
        return $next($data);
    }
}
