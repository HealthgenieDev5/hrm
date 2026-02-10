<?php

namespace App\Pipes\AttendanceProcessor;


use App\Libraries\Pipeline;
use App\Models\EmployeeModel;
use App\Models\OdRequestsModel;
use App\Models\ShiftAttendanceRuleModel;
use App\Models\ShiftOverrideModel;
use Closure;

class CheckFraudPunches
{
    public function handle($punching_row, Closure $next)
    {
        if ($punching_row['INTime'] !== '--:--' && $punching_row['OUTTime'] !== '--:--') {

            $INTime = $punching_row['INTime'];
            $OUTTime = $punching_row['OUTTime'];
            // Check if both punches are before shift start 
            // Remove punch out if fraud
            if (
                strtotime($punching_row['INTime']) < strtotime($punching_row['shift_start'])
                && strtotime($punching_row['OUTTime']) < strtotime($punching_row['shift_start'])
                && strtotime($punching_row['INTime']) < strtotime($punching_row['OUTTime'])
            ) {
                // $punching_row['INTime'] = $punching_row['OUTTime'];
                $punching_row['INTime'] = '--:--';
                $punching_row['OUTTime'] = '--:--';
                $punching_row['fraud_remarks'] = "<span class='text-danger'>Both punches are before shift start, INTime = " . $INTime . ", OUTTime=" . $OUTTime . "</span><br>";
            }

            // Check if both punches are after shift end 
            // Remove punch in if fraud
            if (
                strtotime($punching_row['INTime']) > strtotime($punching_row['shift_end'])
                && strtotime($punching_row['OUTTime']) > strtotime($punching_row['shift_end'])
                && strtotime($punching_row['INTime']) < strtotime($punching_row['OUTTime'])
            ) {
                // $punching_row['INTime'] = $punching_row['OUTTime'];
                $punching_row['INTime'] = '--:--';
                $punching_row['OUTTime'] = '--:--';
                $punching_row['fraud_remarks'] = "<span class='text-danger'>Both punches are after shift end, INTime = " . $INTime . ", OUTTime=" . $OUTTime . "<br></span>";
            }
        }

        return $next($punching_row);
    }
}
