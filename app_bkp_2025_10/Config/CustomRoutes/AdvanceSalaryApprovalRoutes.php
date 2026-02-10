<?php
/*begin::Advance Salary Approval*/

use App\Controllers\Approval\AdvanceSalary;

$routes->get('/backend/administrative/advance-salary-approval', [AdvanceSalary::class, 'index']);
$routes->match(['get', 'post'], '/ajax/administrative/get-all-advance-salary-requests', [AdvanceSalary::class, 'getAllAdvanceSalaryRequests']);
$routes->match(['get', 'post'], '/ajax/administrative/get-advance-salary-request', [AdvanceSalary::class, 'GetAdvanceSalaryRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/approve-advance-salary-request', [AdvanceSalary::class, 'ApproveAdvanceSalaryRequest']);
$routes->match(['get', 'post'], '/ajax/administrative/reject-advance-salary-request', [AdvanceSalary::class, 'RejectAdvanceSalaryRequest']);
/*end::Advance Salary Approval*/
