<?php
/*begin::User Loan*/

use App\Controllers\Requests\Loan;

$routes->match(['get', 'post'], '/backend/user/loan', [Loan::class, 'index']);
$routes->match(['get', 'post'], '/ajax/create-loan-request', [Loan::class, 'createLoanRequest']);
$routes->match(['get', 'post'], '/ajax/get-all-loan-requests', [Loan::class, 'getAllLoanRequests']);
$routes->match(['get', 'post'], '/ajax/get-loan-emi/(:num)', [Loan::class, 'getLoanEmi/$1']);
/*end::User Loan*/
