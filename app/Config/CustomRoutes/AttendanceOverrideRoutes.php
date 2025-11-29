<?php
/*begin::Attendance Override*/

use App\Controllers\Override\Attendance;

$routes->match(['get', 'post'], '/backend/hr/attendance-override', [Attendance::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/override-attendance', [Attendance::class, 'overrideAttendance']);
$routes->match(['get', 'post'], '/ajax/backend/hr/existing-attendance-overrides', [Attendance::class, 'existingAttendanceOverrides']);
$routes->match(['get', 'post'], '/backend/reports/attendance-override-all', [Attendance::class, 'getAttendanceOverrideAll']);
$routes->match(['get', 'post'], '/ajax/backend/hr/delete-attendance-override', [Attendance::class, 'deleteAttendanceOverride']);
/*end::Attendance Override*/
