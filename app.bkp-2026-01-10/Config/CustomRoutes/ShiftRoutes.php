<?php
/*begin::Shift Master*/

use App\Controllers\Master\Shift;

$routes->match(['get', 'post'], '/backend/master/shift', [Shift::class, 'index']);
$routes->match(['get', 'post'], '/backend/master/shift/(:any)', [Shift::class, 'index/$1']);
$routes->match(['get', 'post'], '/ajax/load-shifts', [Shift::class, 'getAllShifts']);
$routes->match(['get', 'post'], '/ajax/add-shift', [Shift::class, 'addShift']);
$routes->match(['get', 'post'], '/ajax/delete-shift', [Shift::class, 'deleteShift']);
$routes->match(['get', 'post'], '/ajax/get-shift', [Shift::class, 'getShift']);
$routes->match(['get', 'post'], '/ajax/update-shift', [Shift::class, 'updateShift']);
/*end::Shift Master*/
