<?php
/*begin::User Gate Pass*/

use App\Controllers\Requests\GatePass;

$routes->get('/backend/user/gate-pass', [GatePass::class, 'index']);
$routes->match(['get', 'post'], '/ajax/create-gate-pass-request', [GatePass::class, 'createGatePassRequest']);
$routes->match(['get', 'post'], '/ajax/get-all-gate-pass-requests', [GatePass::class, 'getAllGatePassRequests']);
$routes->match(['get', 'post'], '/ajax/check-gate-pass-request-today', [GatePass::class, 'getGatePassRequestToday']);
/*end::User Gate Pass*/
