<?php

namespace App\Pipes;

use App\Controllers\Cron\ServerCron;
use Closure;

class FetchFreshAttendance
{
    public function handle($data, Closure $next)
    {
        $ServerCron = new ServerCron;
        $ServerCron->update_my_punching_data_today($data['current_user_data']['internal_employee_id']);

        return $next($data);
    }
}
