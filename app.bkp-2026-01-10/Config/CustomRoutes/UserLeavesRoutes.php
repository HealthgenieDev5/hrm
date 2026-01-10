<?php
/*begin::User Leave*/

use App\Controllers\Requests\Leave;

$routes->get('/backend/user/leaves', [Leave::class, 'index']);
$routes->match(['get', 'post'], '/ajax/create-leave-request', [Leave::class, 'createLeaveRequest']);
#2024-02-24
$routes->match(['get', 'post'], '/ajax/create-leave-request-test', [Leave::class, 'createLeaveRequestTest']);
#2024-02-24
$routes->match(['get', 'post'], '/ajax/nazrul/create-leave-request', [Leave::class, 'createLeaveRequestNazrul']);
$routes->match(['get', 'post'], '/ajax/get-all-leave-requests', [Leave::class, 'getAllLeaveRequests']);
$routes->match(['get', 'post'], '/ajax/user/get-leave-request', [Leave::class, 'getSelfLeaveRequest']);
$routes->match(['get', 'post'], '/ajax/user/cancel-leave-request', [Leave::class, 'cancelSelfLeaveRequest']);
/*end::User Leave*/
