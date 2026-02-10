<?php
/*begin::User Comp Off Utilized Minutes*/

use App\Controllers\Requests\CompOffMinute;

$routes->get('/backend/user/comp-off-utilization-requests', [CompOffMinute::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/user/get-all-comp-off-utilization-requests', [CompOffMinute::class, 'getAllCompOffUtilizationRequests']);
$routes->match(['get', 'post'], '/ajax/backend/user/cancel-comp-off-utilization-requests', [CompOffMinute::class, 'cancelCompOffUtilizationRequest']);
$routes->match(['get', 'post'], '/ajax/backend/user/create-comp-off-utilization-request', [CompOffMinute::class, 'createCompOffUtilizationRequest']);
/*end::User Comp Off Utilized Minutes*/
