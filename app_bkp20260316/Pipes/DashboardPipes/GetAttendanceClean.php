<?php

namespace App\Pipes\DashboardPipes;

use App\Models\EmployeeModel;
use Closure;

class GetAttendanceClean
{
    public function handle($data, Closure $next)
    {
        // $RawPunchingData = json_decode(RawPunchingData($data['current_user_data']['internal_employee_id'], $data['dateFrom'], $data['dateTo']), true)['InOutPunchData'];
        $current_user_data = $data['current_user_data'];
        $RawPunchingData = $data['RawPunchingData'];
        foreach ($RawPunchingData as $punching_data_index => $punching_data_row) {
            $day = date('l', strtotime($punching_data_row['DateString']));
            $date_time = date('d M Y', strtotime(str_replace('/', '-', $punching_data_row['DateString'])));
            $RawPunchingData[$punching_data_index]['date'] = date('Y-m-d', strtotime($date_time));
            $RawPunchingData[$punching_data_index]['date_time_ordering'] = strtotime($date_time);
            $RawPunchingData[$punching_data_index]['day'] = $day;
            $RawPunchingData[$punching_data_index]['current_user_data'] = $current_user_data;
            $RawPunchingData[$punching_data_index]['shift_id_current_user_data'] = $current_user_data['shift_id'];
            $RawPunchingData[$punching_data_index]['employee_id'] = $current_user_data['employee_id'];
            
        }

        $data['RawPunchingData'] = $RawPunchingData;

        return $next($data);
    }
}
