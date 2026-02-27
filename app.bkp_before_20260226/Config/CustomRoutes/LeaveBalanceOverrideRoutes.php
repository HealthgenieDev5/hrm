<?php
/*begin::Leave Balance Override*/

use App\Controllers\Override\LeaveBalance;

$routes->match(['get', 'post'], '/backend/hr/leave-balance-override', [LeaveBalance::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/override-leave-balance', [LeaveBalance::class, 'overrideLeaveBalance']);
$routes->match(['get', 'post'], '/ajax/backend/hr/leave-override-history', [LeaveBalance::class, 'getLeaveBalanceOverrideHistory']);
/*end::Leave Balance Override*/
