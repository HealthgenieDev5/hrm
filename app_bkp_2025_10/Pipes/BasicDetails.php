<?php

namespace App\Pipes;

use App\Models\EmployeeModel;
use Closure;

class BasicDetails
{
    public function handle($data, Closure $next)
    {
        // $data['greeting'] = "Hello";
        $EmployeeModel = new EmployeeModel();
        $data['current_user_data'] = $EmployeeModel
            ->select('employees.*')
            ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
            ->select('d.department_name as department_name')
            ->select('c.company_name as company_name')
            ->select('c.company_short_name as company_short_name')
            ->select('deg.designation_name as designation_name')
            ->select('s.shift_name as shift_name')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "monday" and shift_id = employees.shift_id) as Monday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "tuesday" and shift_id = employees.shift_id) as Tuesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "wednesday" and shift_id = employees.shift_id) as Wednesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "thursday" and shift_id = employees.shift_id) as Thursday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "friday" and shift_id = employees.shift_id) as Friday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "saturday" and shift_id = employees.shift_id) as Saturday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "sunday" and shift_id = employees.shift_id) as Sunday')
            ->join('departments as d', 'd.id = employees.department_id', 'left')
            ->join('companies as c', 'c.id = employees.company_id', 'left')
            ->join('designations as deg', 'deg.id = employees.designation_id', 'left')
            ->join('shifts as s', 's.id = employees.shift_id', 'left')
            ->join('shift_per_day as spd', 'spd.id = s.id', 'left')
            ->where('employees.id =', $data['employee_id'])
            ->first();

        return $next($data);
    }
}
