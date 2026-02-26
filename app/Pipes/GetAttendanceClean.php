<?php

namespace App\Pipes;

use Closure;

class GetAttendanceClean
{
    public function handle($data, Closure $next)
    {
        $get_punching_data = json_decode(get_punching_data($data['current_user_data']['internal_employee_id'], $data['dateFrom'], $data['dateTo']), true)['InOutPunchData'];

        $current_user_data = $data['current_user_data'];
        $employee_id = $data['employee_id'];
        foreach ($get_punching_data as $punching_data_index => $punching_data_row) {
            $get_punching_data[$punching_data_index]['employee_data'] = $current_user_data;
            $get_punching_data[$punching_data_index]['employee_id'] = $employee_id;
            $get_punching_data[$punching_data_index]['late_sitting_allowed'] = $current_user_data['late_sitting_allowed'];
            $get_punching_data[$punching_data_index]['late_sitting_formula'] = $current_user_data['late_sitting_formula'];
            $get_punching_data[$punching_data_index]['late_sitting_formula_effective_from'] = $current_user_data['late_sitting_formula_effective_from'];
            $get_punching_data[$punching_data_index]['over_time_allowed'] = $current_user_data['over_time_allowed'];
            $day = date('l', strtotime($punching_data_row['DateString']));
            $date_time = date('d M Y', strtotime(str_replace('/', '-', $punching_data_row['DateString'])));
            $get_punching_data[$punching_data_index]['date'] = date('Y-m-d', strtotime($date_time));
            $get_punching_data[$punching_data_index]['date_time'] = $date_time;
            $get_punching_data[$punching_data_index]['date_time_new'] = $date_time;
            $get_punching_data[$punching_data_index]['day'] = $day;
        }

        $data['get_punching_data'] = $get_punching_data;

        return $next($data);
    }
}
