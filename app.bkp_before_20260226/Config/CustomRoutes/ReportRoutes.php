<?php
/*begin::Leave Balance All*/

use App\Controllers\Override\DeductionMinute;
use App\Controllers\Override\LeaveBalance;
use App\Controllers\Override\WaveOffMinutes;
use App\Controllers\Reports\FinalPaidDays;
use App\Controllers\Reports\Leave;
use App\Controllers\Reports\LoyaltyIncentive;
use App\Controllers\Reports\Ncl;
use App\Controllers\Reports\Od;
use App\Controllers\Reports\Punching;
use App\Controllers\Reports\ReportAjax;
use App\Controllers\Reports\WagesRegister;

$routes->match(['get', 'post'], '/backend/reports/leave-balance-all', [LeaveBalance::class, 'getAllBalance']);
/*end::*/

/*begin::WaveOff Minutes All*/
$routes->match(['get', 'post'], '/backend/reports/waveoff-minutes-all', [WaveOffMinutes::class, 'getAllWaveOffMinutes']);
/*end::*/
/*begin::Deduction Minutes All*/
$routes->match(['get', 'post'], '/backend/reports/deduction-minutes-all', [DeductionMinute::class, 'getAllDeductionMinutes']);
/*end::*/

/*begin::NCL Report*/
$routes->match(['get', 'post'], '/backend/reports/ncl/ncl-report', [Ncl::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/reports/get-ncl-report', [Ncl::class, 'getAll']);
/*end::NCL Report*/

/*begin::Loyalty Incentive Report*/
$routes->match(['get', 'post'], '/backend/reports/loyalty-incentive/loyalty-incentive-report', [LoyaltyIncentive::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/reports/get-loyalty-incentive-report', [LoyaltyIncentive::class, 'getAll']);
/*end::Loyalty Incentive Report*/

/*begin::Attendance Sumamry*/
$routes->get('/backend/reports/attendance-summary', [FinalPaidDays::class, 'attendanceSummary']);
// $routes->get('/backend/reports/download-register', [FinalPaidDays::class, 'downloadRegister']);
/*end::Attendance Sumamry*/



/*begin::Detailed Dashboard*/
$routes->match(['get', 'post'], '/ajax/backend/reports/get-department-by-company-id', [ReportAjax::class, 'getDepartmentByCompanyID']);
$routes->match(['get', 'post'], '/ajax/backend/reports/get-employees-by-department-id', [ReportAjax::class, 'getEmployeesByDepartmentID']);

$routes->match(['get', 'post'], '/backend/reports/punching-report', [Punching::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/reports/get-punching-report', [Punching::class, 'getPunchingReports']);
// $routes->match(['get', 'post'], '/backend/reports/leave-report', [Leave::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/reports/get-leave-report', [Leave::class, 'getLeaveReports']);
// $routes->match(['get', 'post'], '/backend/reports/od-report', [Od::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/reports/get-od-report', [Od::class, 'getOdReports']);
/*end::Detailed Dashboard*/


$routes->get('/backend/reports/labour-register', [WagesRegister::class, 'index']);
