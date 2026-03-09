<?php
/*begin::Cron*/

use App\Controllers\Cron\FinalSalary;
use App\Controllers\Cron\ServerCron;
use App\Controllers\Cron\UpdateCreditandBalance;

$routes->match(['get', 'post'], '/cron/rawattendance/save', [ServerCron::class, 'index']);
$routes->match(['get', 'post'], '/cron/rawattendance/update-from-last-month/save', [ServerCron::class, 'updateFromLastMonth']);

########## Commented so that no one can regenerate the leave balance ##########
$routes->match(['get', 'post'], '/cron/backend/reports/salary/update-credit-and-balance', [UpdateCreditandBalance::class, 'index']);
########## Commented so that no one can regenerate the leave balance ##########
########## This is commented because we are now regenerating salary at the time of regenerating final paid days ##########
$routes->match(['get', 'post'], '/cron/backend/reports/salary/final-salary/calculate', [FinalSalary::class, 'calculateSalaryAll']);
/*end::Cron*/
