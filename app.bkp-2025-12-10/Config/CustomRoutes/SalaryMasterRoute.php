<?php
/*begin::Salary Master*/
######commented for security purpose#######

use App\Controllers\Master\Salary;

$routes->match(['get', 'post'], '/backend/master/salary/id/(:num)', [Salary::class, 'index/$1']);
$routes->post('/ajax/master/salary/validate', [Salary::class, 'updateSalary']);
$routes->post('/ajax/master/stipend/validate', [Salary::class, 'updateStipend']);
/*end::Salary Master*/
