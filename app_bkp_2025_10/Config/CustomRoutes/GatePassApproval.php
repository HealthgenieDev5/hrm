<?php
/*begin::GATE PASS Approval*/

use App\Controllers\Approval\GatePass;

$routes->get('/backend/administrative/gate-pass-approval', [GatePass::class, 'index']);
$routes->match(['get', 'post'], '/ajax/get-all-gate-pass-approval-requests', [GatePass::class, 'getAllGatePassApprovalRequests']);
$routes->match(['get', 'post'], '/ajax/administrative/get-gate-pass-request', [GatePass::class, 'getGatePassRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/update-gate-pass-request', [GatePass::class, 'updateGatePassRequest']);
/*end::GATE PASS Approval*/
