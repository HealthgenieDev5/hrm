<?php
/*begin::COMPOFF Credit Approval*/

use App\Controllers\Approval\CompOffCredit;

$routes->get('/backend/administrative/comp-off-credit-approval-requests', [CompOffCredit::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/administrative/get-all-comp-off-credit-approval-requests', [CompOffCredit::class, 'getAllCompOffCreditApprovalRequests']);
$routes->match(['get', 'post'], '/ajax/backend/administrative/get-comp-off-credit-approval-request', [CompOffCredit::class, 'getCOMPOFFCreditApprovalRequest']);
$routes->match(['get', 'post'], '/ajax/backend/administrative/update-comp-off-credit-approval-request', [CompOffCredit::class, 'updateCOMPOFFCreditApprovalRequest']);
$routes->match(['get', 'post'], '/ajax/backend/administrative/cancel-comp-off-credit-request', [CompOffCredit::class, 'CancelCompOffCreditRequest']);
$routes->match(['get', 'post'], '/ajax/backend/administrative/get-comp-off-pending-counts', [CompOffCredit::class, 'getCompOffPendingCounts']);
/*end::COMP OFF Credit Approval*/
