<?php
/*begin::Holiday Master*/

use App\Controllers\Master\Holiday;

$routes->match(['get', 'post'], '/backend/master/holiday', [Holiday::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/master/load-holidays', [Holiday::class, 'getAllHolidays']);
$routes->match(['get', 'post'], '/ajax/backend/master/add-holiday', [Holiday::class, 'addHoliday']);
$routes->match(['get', 'post'], '/ajax/backend/master/delete-holiday', [Holiday::class, 'deleteHoliday']);
$routes->match(['get', 'post'], '/ajax/backend/master/get-holiday', [Holiday::class, 'getHoliday']);
$routes->match(['get', 'post'], '/ajax/backend/master/get-employee-of-this-holiday', [Holiday::class, 'getEmployeeList']);
$routes->match(['get', 'post'], '/ajax/backend/master/update-holiday', [Holiday::class, 'updateHoliday']);

$routes->match(['get', 'post'], '/backend/master/holiday/single/(:any)', [Holiday::class, 'holidaySingle/$1']);
/*end::Holiday Master*/
