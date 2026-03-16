<?php
/*begin::Final Salary*/

use App\Controllers\Reports\FinalSalary;

$routes->match(['get', 'post'], '/backend/reports/salary/final-salary', [FinalSalary::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/reports/salary/final-salary/load-salary', [FinalSalary::class, 'loadSalary']);

$routes->match(['get', 'post'], '/backend/reports/salary/final-salary-intern', [FinalSalary::class, 'internSalary']);
$routes->match(['get', 'post'], '/ajax/backend/reports/salary/final-salary/load-intern-salary', [FinalSalary::class, 'loadInternSalary']);

$routes->match(['get', 'post'], '/ajax/backend/reports/salary/final-salary/disburse', [FinalSalary::class, 'disburseSalary']);
#$routes->match(['get', 'post'], '/backend/reports/salary/final-salary/single/(:any)', [FinalSalary::class, 'Single/$1/$2/']);
$routes->match(['get', 'post'], '/backend/reports/salary/final-salary/salary-slip/(:any)', [FinalSalary::class, 'salarySlip/$1/$2/$3']);

$routes->match(['get', 'post'], '/ajax/backend/reports/salary/final-salary/do-action', [FinalSalary::class, 'doAction']);
/*end::Final Salary*/
