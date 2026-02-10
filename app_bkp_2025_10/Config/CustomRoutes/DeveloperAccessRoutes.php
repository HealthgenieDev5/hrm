<?php
/*begin::User Leave*/

use App\Controllers\Override\EmergencyLeave;

$routes->get('/developer-access', [EmergencyLeave::class, 'index']);
$routes->match(['get', 'post'], '/ajax/developer-access/get-leave-balance-current-month', [EmergencyLeave::class, 'getLeaveBalanceCurrentMonth']);
$routes->match(['get', 'post'], '/ajax/developer-access/create-leave-request', [EmergencyLeave::class, 'createLeaveRequest']);
/*end::User Leave*/
