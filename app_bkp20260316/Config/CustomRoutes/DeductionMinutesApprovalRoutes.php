<?php
/*begin::Deduction Approval*/

use App\Controllers\Approval\DeductionMinute;

$routes->get('/backend/administrative/deduction-approval-requests', [DeductionMinute::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/administrative/get-all-deduction-approval-requests', [DeductionMinute::class, 'getAllDeductionApprovalRequests']);
$routes->match(['get', 'post'], '/ajax/backend/administrative/get-deduction-approval-requests', [DeductionMinute::class, 'getDeductionApprovalRequest']);
$routes->match(['get', 'post'], '/ajax/backend/administrative/update-deduction-approval-requests', [DeductionMinute::class, 'updateDeductionApprovalRequest']);
/*end::Deduction Approval*/
