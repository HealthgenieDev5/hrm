<?php

namespace App\Pipes;

use App\Pipes\AttendanceProcessor\ProcessorHelper;
use Closure;

class SandwichSecondPass
{
    public function handle($data, Closure $next)
    {
        $punchingData__LateComingAdjusted = $data['punching_data'];
        if ($data['do_sw_second_pass'] == true) {
            $sandwich_first_pass_done = orderResultSet($punchingData__LateComingAdjusted, 'date_time_ordering', FALSE);
            $sandwich_second_pass_done = ProcessorHelper::find_sandwich_second_pass($sandwich_first_pass_done);
            $punching_data_sorted = orderResultSet($sandwich_second_pass_done, 'date_time_ordering', TRUE);
            #return $punching_data_sorted;
        } else {
            $punching_data_sorted = orderResultSet($punchingData__LateComingAdjusted, 'date_time_ordering', TRUE);
            #return $punching_data_sorted;
        }
        $data['punching_data'] = $punching_data_sorted;
        return $next($data);
    }
}
