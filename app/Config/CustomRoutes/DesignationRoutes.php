<?php

/*begin::Designation Master*/

use App\Controllers\Master\Designation;

$routes->match(['get', 'post'], '/backend/master/designation', [Designation::class, 'index']);
$routes->match(['get', 'post'], '/ajax/load-designations', [Designation::class, 'getAllDesignations']);
$routes->match(['get', 'post'], '/ajax/add-designation', [Designation::class, 'addDesignation']);
$routes->match(['get', 'post'], '/ajax/delete-designation', [Designation::class, 'deleteDesignation']);
$routes->match(['get', 'post'], '/ajax/get-designation', [Designation::class, 'getDesignation']);
$routes->match(['get', 'post'], '/ajax/update-designation', [Designation::class, 'updateDesignation']);
/*end::Designation Master*/