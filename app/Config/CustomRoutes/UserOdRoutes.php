<?php
/*begin::User OD*/

use App\Controllers\Requests\Od;

$routes->get('/backend/user/od', [Od::class, 'index']);
$routes->match(['get', 'post'], '/ajax/create-od-request', [Od::class, 'createOdRequest']);
$routes->match(['get', 'post'], '/ajax/get-all-od-requests', [Od::class, 'getAllOdRequests']);
/*end::User OD*/
