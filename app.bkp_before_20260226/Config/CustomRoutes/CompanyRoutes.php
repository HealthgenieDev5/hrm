<?php

/*begin::Company Master*/

use App\Controllers\Master\Company;

$routes->match(['get', 'post'], '/backend/master/company', [Company::class, 'index']);
$routes->match(['get', 'post'], '/ajax/load-companies', [Company::class, 'getAllCompanies']);
$routes->match(['get', 'post'], '/ajax/add-company', [Company::class, 'addCompany']);
$routes->match(['get', 'post'], '/ajax/delete-company', [Company::class, 'deleteCompany']);
$routes->match(['get', 'post'], '/ajax/get-company', [Company::class, 'getCompany']);
$routes->match(['get', 'post'], '/ajax/update-company', [Company::class, 'updateCompany']);
// $routes->match(['get', 'post'], '/ajax/test/(:num)', [Company::class, 'Test/$1']);
/*end::Company Master*/