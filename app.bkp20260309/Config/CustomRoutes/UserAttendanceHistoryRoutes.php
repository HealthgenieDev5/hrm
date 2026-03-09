<?php
/*begin::User Attendance History*/

use App\Controllers\User\AttendanceHistory;

$routes->match(['get', 'post'], '/backend/user/attendance-history', [AttendanceHistory::class, 'index']);
/*end::User Attendance History*/
