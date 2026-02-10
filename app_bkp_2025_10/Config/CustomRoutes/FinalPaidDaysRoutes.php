<?php
/*begin::Final Paid Days*/

use App\Controllers\Reports\FinalAttendance;
use App\Controllers\Reports\FinalPaidDays;

$routes->match(['get', 'post'], '/backend/reports/final-paid-days', [FinalPaidDays::class, 'index']);
$routes->match(['get', 'post'], '/backend/reports/final-paid-days/generate', [FinalPaidDays::class, 'generateFinalPaidDays']);
$routes->match(['get', 'post'], '/backend/reports/final-paid-days/regenerate', [FinalPaidDays::class, 'reGenerateFinalPaidDays']);
// $routes->match(['get', 'post'], '/backend/reports/final-paid-days/hr-sheet', [FinalPaidDays::class, 'HRSHEET']);
$routes->match(['get', 'post'], '/backend/reports/final-paid-days/final-paid-days-sheet', [FinalPaidDays::class, 'finalPaidDaysSheet']);
$routes->match(['get', 'post'], '/backend/reports/final-paid-days/final-paid-days-sheet-new', [FinalPaidDays::class, 'finalPaidDaysSheetNew']);

$routes->match(['get', 'post'], '/backend/reports/attendance-report/attendance-sheet', [FinalAttendance::class, 'attendanceSheet']);
