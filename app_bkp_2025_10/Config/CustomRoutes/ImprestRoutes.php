<?php
/*begin::IMPREST Master*/

use App\Controllers\Master\Imprest;

$routes->match(['get', 'post'], '/ajax/backend/master/imprest-master/get/(:any)', [Imprest::class, 'get/$1']);
$routes->match(['get', 'post'], '/ajax/backend/master/imprest-master/add', [Imprest::class, 'add']);
$routes->match(['get', 'post'], '/ajax/backend/master/imprest-master/delete', [Imprest::class, 'delete']);
