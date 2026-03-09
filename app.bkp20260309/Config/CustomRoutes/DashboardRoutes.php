<?php

use App\Controllers\Dashboards\Dashboard;
use App\Controllers\Dashboards\HistoricalDashboard;
use App\Controllers\Dashboards\TwoMonthDashboard;

$routes->match(['get', 'post'], '/dashboard', [Dashboard::class, 'index']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-punching-reports', [Dashboard::class, 'getPunchingReports']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-absent-reports', [Dashboard::class, 'getAbsentReports']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-missed-punching-reports', [Dashboard::class, 'getMissedPunchingReports']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-late-coming-reports', [Dashboard::class, 'getLateComingReports']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-on-leave-today-pending', [Dashboard::class, 'getOnLeaveTodayPending']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-on-leave-today-approved', [Dashboard::class, 'getOnLeaveTodayApproved']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-on-od-today-pending', [Dashboard::class, 'getOnOdTodayPending']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-on-od-today-approved', [Dashboard::class, 'getOnOdTodayApproved']);
$routes->match(['get', 'post'], '/ajax/dashboard/get-all-comp-off-credit-requests', [Dashboard::class, 'getAllCompOffCreditRequests']);
/*end::Dashboard*/
/*begin::HOD Dashboard*/
$routes->match(['get', 'post'], '/historical-dashboard', [HistoricalDashboard::class, 'index']);
$routes->match(['get', 'post'], '/ajax/dashboards/historical-dashboard/get-late-early-late-going-report', [HistoricalDashboard::class, 'getLateEarlyLateGoingReport']);
$routes->match(['get', 'post'], '/ajax/dashboards/historical-dashboard/get-missing-punching-report', [HistoricalDashboard::class, 'getMissingPunchingReport']);
$routes->match(['get', 'post'], '/ajax/dashboards/historical-dashboard/get-absent-report', [HistoricalDashboard::class, 'getAbsentReport']);
$routes->match(['get', 'post'], '/ajax/dashboards/historical-dashboard/get-ggn-data', [HistoricalDashboard::class, 'getGGNData']);

$routes->match(['get', 'post'], '/detailed-dashboard', [TwoMonthDashboard::class, 'index']);
/*end::HOD Dashboard*/

$routes->match(['get', 'post'], '/miss-punch-dashboard', [HistoricalDashboard::class, 'getMissPunchDashboard']);
