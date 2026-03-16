<?php

namespace App\Pipes\AttendanceProcessor;

use Closure;

class CheckFraudPunchesAndOverride
{
    public function handle($punching_row, Closure $next)
    {
        if ($punching_row['in_time_including_od'] !== null && $punching_row['out_time_including_od'] !== null) {

            $in_time_including_od = $punching_row['in_time_including_od'];
            $out_time_including_od = $punching_row['out_time_including_od'];
            // Check if both punches are before shift start
            // Remove punch out if fraud
            if (
                strtotime($in_time_including_od) < strtotime($punching_row['shift_start'])
                && strtotime($out_time_including_od) < strtotime($punching_row['shift_start'])
                && strtotime($in_time_including_od) < strtotime($out_time_including_od)
            ) {
                // $punching_row['INTime'] = $punching_row['OUTTime'];
                $punching_row['in_time'] = null;
                $punching_row['in_time__Raw'] = null;
                $punching_row['in_time_including_od'] = null;
                $punching_row['in_time_between_shift_including_od'] = null;
                $punching_row['out_time'] = null;
                $punching_row['out_time__Raw'] = null;
                $punching_row['out_time_including_od'] = null;
                $punching_row['out_time_between_shift_including_od'] = null;
                $punching_row['fraud_remarks'] = "<span class='text-danger'>Both punches are before shift start, INTime = " . $punching_row['INTime'] . ', OUTTime=' . $punching_row['OUTTime'] . '</span><br>';
            }

            // Check if both punches are after shift end
            // Remove punch in if fraud
            if (
                strtotime($in_time_including_od) > strtotime($punching_row['shift_end'])
                && strtotime($out_time_including_od) > strtotime($punching_row['shift_end'])
                && strtotime($in_time_including_od) < strtotime($out_time_including_od)
            ) {
                // $punching_row['INTime'] = $punching_row['OUTTime'];
                $punching_row['in_time'] = null;
                $punching_row['in_time__Raw'] = null;
                $punching_row['in_time_including_od'] = null;
                $punching_row['in_time_between_shift_including_od'] = null;
                $punching_row['out_time'] = null;
                $punching_row['out_time__Raw'] = null;
                $punching_row['out_time_including_od'] = null;
                $punching_row['out_time_between_shift_including_od'] = null;
                $punching_row['fraud_remarks'] = "<span class='text-danger'>Both punches are after shift end, INTime = " . $punching_row['INTime'] . ', OUTTime=' . $punching_row['OUTTime'] . '</span><br>';
            }
        }

        /* if ($punching_row['DateString_2'] == '2025-04-12') {
            print_r([
                // $in_time_including_od,
                // $out_time_including_od,
                $punching_row['in_time'],
                $punching_row['out_time'],
                $punching_row['in_time__Raw'],
                $punching_row['out_time__Raw'],
                $punching_row['in_time_including_od'],
                // $punching_row['in_time_between_shift_including_od'],
                $punching_row['out_time_including_od'],
                // $punching_row['out_time_between_shift_including_od'],
                // $punching_row['fraud_remarks'],
            ]);
            die();
        } */


        return $next($punching_row);
    }
}
