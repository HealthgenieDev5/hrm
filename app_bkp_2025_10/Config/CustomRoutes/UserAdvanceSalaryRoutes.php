<?php
/*begin::User AdvanceSalary*/

use App\Controllers\Requests\AdvanceSalary;

$routes->match(['get', 'post'], '/backend/user/advance-salary', [AdvanceSalary::class, 'index']);
$routes->match(['get', 'post'], '/ajax/create-advance-salary-request', [AdvanceSalary::class, 'createAdvanceSalaryRequest']);
$routes->match(['get', 'post'], '/ajax/get-all-advance-salary-requests', [AdvanceSalary::class, 'getAllAdvanceSalaryRequests']);
$routes->match(['get', 'post'], '/ajax/get-advance-salary-emi/(:num)', [AdvanceSalary::class, 'getAdvanceSalaryEmi/$1']);
/*end::User AdvanceSalary*/
