<?php

namespace App\Pipes\DashboardPipes;
use Closure;
use App\Models\EmployeeModel;

class BasicDetails
{
    public function handle($data, Closure $next)
    {
        // $data['greeting'] = "Hello, {$data['name']}!";
        $EmployeeModel = new EmployeeModel();
        $data['current_user_data'] = $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
            ->select('s.id as shift_id')
            ->select('s.shift_name as shift_name')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "monday" and shift_id = employees.shift_id) as Monday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "tuesday" and shift_id = employees.shift_id) as Tuesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "wednesday" and shift_id = employees.shift_id) as Wednesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "thursday" and shift_id = employees.shift_id) as Thursday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "friday" and shift_id = employees.shift_id) as Friday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "saturday" and shift_id = employees.shift_id) as Saturday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "sunday" and shift_id = employees.shift_id) as Sunday')
            ->join('shifts as s', 's.id = employees.shift_id', 'left')
            ->join('shift_per_day as spd', 'spd.id = s.id', 'left')
            ->where('employees.id =', $data['employee_id'])
            ->first();

        return $next($data);
    }
}
