<?php
/*begin::Loan Approval*/

use App\Controllers\Approval\Loan;

$routes->get('/backend/administrative/loan-approval', [Loan::class, 'index']);
$routes->match(['get', 'post'], '/ajax/get-all-loan-approval-requests', [Loan::class, 'getAllLoanApprovalRequests']);
$routes->match(['get', 'post'], '/ajax/administrative/get-loan-request', [Loan::class, 'getLoanApprovalRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/approve-loan-request', [Loan::class, 'approveLoanRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/reject-loan-request', [Loan::class, 'rejectLoanApprovalRequest']);
/*end::Loan Approval*/
