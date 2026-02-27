<?php
/*begin::OD Approval*/

use App\Controllers\Approval\Od as OdApproval;

$routes->get('/backend/administrative/odapproval', [OdApproval::class, 'index']);
$routes->match(['get', 'post'], '/ajax/get-all-od-approval-requests', [OdApproval::class, 'getAllOdApprovalRequests']);
$routes->match(['get', 'post'], '/ajax/administrative/get-od-request', [OdApproval::class, 'getOdRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/approve-od-request', [OdApproval::class, 'approveOdRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/reject-od-request', [OdApproval::class, 'rejectOdRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/cancel-od-request', [OdApproval::class, 'cancelOdRequest']);
/*end::OD Approval*/
