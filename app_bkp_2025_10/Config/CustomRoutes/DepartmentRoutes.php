<?php

/*begin::Department Master*/

use App\Controllers\Master\Department;

$routes->match(['get', 'post'], '/backend/master/department', [Department::class, 'index']);
$routes->match(['get', 'post'], '/ajax/load-departments', [Department::class, 'getAllDepartments']);
$routes->match(['get', 'post'], '/ajax/add-department', [Department::class, 'addDepartment']);
$routes->match(['get', 'post'], '/ajax/delete-department', [Department::class, 'deleteDepartment']);
$routes->match(['get', 'post'], '/ajax/get-department', [Department::class, 'getDepartment']);
$routes->match(['get', 'post'], '/ajax/update-department', [Department::class, 'updateDepartment']);
/*end::Department Master*/